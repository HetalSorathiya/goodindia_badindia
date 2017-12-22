<?php

class Services_UserController extends GTL_Action {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        exit;
    }
	
	/* User Facebook Login Action */
    public function facebookloginAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $errorMessageArray = array();

        $loginModel = new Model_Webservice_Login(); // Load Login Model
        $userModel = new Model_Webservice_User(); // Load User Model
        $fbSessionModel = new Model_Webservice_Facebookresponse(); // Load Facebook Response Model

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
             if($postedData['fr_response'] != ""){
              $fr_response = json_decode($postedData['fr_response'],true);
              }else{
              $errorMessageArray[] = 'Please enter facebook response.';
              } 

            if ($postedData['usr_name'] == '') {
                $errorMessageArray[] = 'Please enter name.';
            }

            if ($postedData['usr_udid_number'] == '') {
                $errorMessageArray[] = 'Please enter device imei no.';
            }

            if ($postedData['usr_gcm_number'] == '') {
                $errorMessageArray[] = 'Please enter gcm no.';
            }

            if ($postedData['lgn_fb_auth_id'] == '') {
                $errorMessageArray[] = 'Please enter auth id.';
            }

           /* if ($postedData['lgn_email'] == '') {
                $errorMessageArray[] = 'Please enter email.';
            }*/
			
			if ($postedData['usr_lgn_type'] == '') {
                $errorMessageArray[] = 'Please enter login type.';
            }
			
			if ($postedData['fr_response'] == '') {
                $errorMessageArray[] = 'Please enter fb response.';
            }
			if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}	
            if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAILED',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
                $userAuthEntry = $loginModel->checkAuthExist("lgn_fb_auth_id = '" . $postedData['lgn_fb_auth_id'] . "'");
               
                if (count($userAuthEntry) > 0) {
                    $loginData['lgn_id'] = $userAuthEntry['lgn_id'];
                    $userData['usr_id'] = $userAuthEntry['usr_id'];
                } else {
                  
                    if ($postedData['lgn_email'] != '') {
						$logo_link = $this->view->BASE_URL . "public/img/";
						
                        /* * *send Mail to user with verification coe * */
						$metaDataModel = new Model_Metadata();
						$ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
						$ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
						$ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
						$ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
						$ADMIN_TO_MAIL = $metaDataModel->fetchEntryByKey("ADMIN_TO_MAIL");
						$ADMIN_TO_MAILID = $ADMIN_TO_MAIL['mtd_value'];
						$ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
						$ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

						$ADMIN_CONTACT_EMAIL_DATA = $metaDataModel->fetchEntryByKey("CONTACT_EMAIL");
						$ADMIN_CONTACT_EMAIL = $ADMIN_CONTACT_EMAIL_DATA['mtd_value'];

						$ADMIN_CONTACT_TELEPHONE_DATA = $metaDataModel->fetchEntryByKey("CONTACT_TELEPHONE");
						$ADMIN_CONTACT_TELEPHONE = $ADMIN_CONTACT_TELEPHONE_DATA['mtd_value'];
						$emailParuser = array(
							'fileName' => 'activation.phtml',
							'subjectName' => 'Registration Completed',
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => $postedData['lgn_email'],
							'toName' => $ADMIN_FROM,
							'pageType' => 'default',
							'assignFields' => array(
							
								'name' => $ADMIN_FROM,
								'usr_name' => $postedData['usr_name'],
								'logolink' => $logo_link,
								'sitelink' => $this->view->BASE_URL,        
								'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
								'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
								'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
							)
						);
										
						//echo"<pre>";print_r($emailPar);exit;				
						$result = $this->mailData($emailParuser); 
                    }
                }
				
				if ($postedData['usr_lgn_type']==1){						
					   $lgn_type = "Android" ;
				   }else {	
					  $lgn_type = "IOS" ;     
				   }
				/* pass default image of user */
				$default_user_image_name=$this->config->BASE_URL . "/public/img/user.png";
				if($postedData['usr_image'] == ''){
					$default_image= $default_user_image_name;
					
				}
				else{
					$default_image=$postedData['usr_image'];
					
				}   
				   
                $loginData['lgn_type'] = 3;
                $loginData['lgn_fb_auth_id'] = $postedData['lgn_fb_auth_id'];
				if($postedData['lgn_email'] != ""){
					$loginData['lgn_email'] = $postedData['lgn_email'];
				}
                $loginData['lgn_createddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_updateddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_status'] = 1;

                $lgn_id = $loginModel->save($loginData, 'lgn_id');

                $userData['usr_lgn_id'] = $lgn_id;

                $userData['usr_name'] = $postedData['usr_name'];
				if($fr_response['picture']['data']['url'] != ""){
					$userData['usr_image'] = $fr_response['picture']['data']['url'];
				}
				
				if($postedData['usr_gender'] == NULL){
					$postedData['usr_gender'] ='';
				}
				
                $userData['usr_lgn_type'] = $lgn_type;
                $userData['usr_udid_number'] = $postedData['usr_udid_number'];
				$userData['usr_image'] = $default_image;
                $userData['usr_gcm_number'] = $postedData['usr_gcm_number'];
                $userData['usr_createddate'] = date('Y-m-d H:i:s');
                $userData['usr_updateddate'] = date('Y-m-d H:i:s');
                $userData['usr_status'] = 1;
				$userData['usr_lgn_type'] = 2;
				$userData['usr_gender'] = $postedData['usr_gender'];
                $usr_id = $userModel->save($userData, 'usr_id');

                $userFbData['fr_lgn_id'] = $lgn_id;
                $userFbData['fr_response'] = serialize($postedData);
                $userFbData['fr_createddate'] = date('Y-m-d H:i:s');
                $userFbData['fr_updateddate'] = date('Y-m-d H:i:s');
                $userFbData['fr_status'] = 1;
                $fr_id = $fbSessionModel->save($userFbData, 'fr_id');
				
				
				if($postedData['lgn_email'] == NULL){
					$postedData['lgn_email'] = '';
				}
				if($postedData['usr_gender']=='1'){
						$gender_type='Male';
				}else if($postedData['usr_gender']=='2'){
						$gender_type='Female';
				}else{
						$gender_type='';
				}
				//$image=$this->IMAGE_PATH ."user.png";
				//echo $image;exit;
				
				//echo $default_image;exit;
                $resultArray = Array
                    (
                    'lgn_id' => $lgn_id,
                    'usr_id' => $usr_id,
					'usr_name' => $postedData['usr_name'],
					'usr_image' => $default_image,
					'lgn_email' => $postedData['lgn_email'],
                    'lgn_fb_auth_id' => $postedData['lgn_fb_auth_id'],
					'usr_gender' => $gender_type,
                );

                $JSONARRAY = Array(
                    'STATUS' => 'SUCCESS',
                    'MESSAGES' => 'Sign up complete!',
                    'DATA' => $resultArray,
                );
				$logo_link = $this->view->BASE_URL . "public/img/";
				$user_profile_link=$this->view->BASE_URL . "admin/user/profile/usr_id/" . "$usr_id";
					$emailPar = array(
						'fileName' => 'register_admin.phtml',
						'subjectName' => 'User registration',
						'FromEmailId' => $ADMIN_MAIL,
						'FromName' => $ADMIN_FROM,
						'toEmailId' => $ADMIN_TO_MAILID,
						'toName' => $ADMIN_FROM,
						'pageType' => 'default',
						'assignFields' => array(
						
							'name' => $ADMIN_FROM,
							'password' => $new_password,
							'logolink' => $logo_link,
							'sitelink' => $this->view->BASE_URL,
							'user_profile_link' => $user_profile_link,         
							'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
							'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
							'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
						)
					);
									
					//echo"<pre>";print_r($emailPar);exit;				
					$result = $this->mailData($emailPar); 
            }
        }
        //echo json_encode($JSONARRAY);
		echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
    }
	
	/* User Google Login Action */
    public function googleloginAction() {

        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $errorMessageArray = array();

        $loginModel = new Model_Webservice_Login(); // Load Login Model
        $userModel = new Model_Webservice_User(); // Load User Model
        $googleSessionModel = new Model_Webservice_Googleresponse(); // Load Facebook Response Model

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if ($postedData['usr_name'] == '') {
                $errorMessageArray[] = 'Please enter name.';
            }

            if ($postedData['usr_udid_number'] == '') {
                $errorMessageArray[] = 'Please enter device imei no.';
            }

            if ($postedData['usr_gcm_number'] == '') {
                $errorMessageArray[] = 'Please enter gcm no.';
            }

            if ($postedData['lgn_google_auth_id'] == '') {
                $errorMessageArray[] = 'Please enter id.';
            }

            if ($postedData['lgn_email'] == '') {
                $errorMessageArray[] = 'Please enter email.';
            }
			
			if ($postedData['usr_lgn_type'] == '') {
                $errorMessageArray[] = 'Please enter login type.';
            }
			
			if($postedData['usr_gender'] == NULL){
				$postedData['usr_gender'] ='';
			}
			
				if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
            if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAILED',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
                $userAuthEntry = $loginModel->checkAuthExist("lgn_google_auth_id = '" . $postedData['lgn_google_auth_id'] . "'");
            
                if (count($userAuthEntry) > 0) {
                    $loginData['lgn_id'] = $userAuthEntry['lgn_id'];
                    $userData['usr_id'] = $userAuthEntry['usr_id'];
                } else {
                   
                    if ($postedData['lgn_email'] != '') {
                        $logo_link = $this->view->BASE_URL . "public/img/";
					
						
						/* * *send Mail to user with verification coe * */
						$metaDataModel = new Model_Metadata();
						$ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
						$ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
						$ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
						$ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
						$ADMIN_TO_MAIL = $metaDataModel->fetchEntryByKey("ADMIN_TO_MAIL");
						$ADMIN_TO_MAILID = $ADMIN_TO_MAIL['mtd_value'];
						$ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
						$ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

						$ADMIN_CONTACT_EMAIL_DATA = $metaDataModel->fetchEntryByKey("CONTACT_EMAIL");
						$ADMIN_CONTACT_EMAIL = $ADMIN_CONTACT_EMAIL_DATA['mtd_value'];

						$ADMIN_CONTACT_TELEPHONE_DATA = $metaDataModel->fetchEntryByKey("CONTACT_TELEPHONE");
						$ADMIN_CONTACT_TELEPHONE = $ADMIN_CONTACT_TELEPHONE_DATA['mtd_value'];
						$emailParuser = array(
							'fileName' => 'activation.phtml',
							'subjectName' => 'Registration Completed',
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => $postedData['lgn_email'],
							'toName' => $ADMIN_FROM,
							'pageType' => 'default',
							'assignFields' => array(
							
								'name' => $ADMIN_FROM,
								'usr_name' => $postedData['usr_name'],
								'logolink' => $logo_link,
								'sitelink' => $this->view->BASE_URL,        
								'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
								'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
								'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
							)
						);
										
						//echo"<pre>";print_r($emailPar);exit;				
						$result = $this->mailData($emailParuser); 	
                    }
                }
				if ($postedData['usr_lgn_type']==1){
						
					   $lgn_type = "Android" ;
				   }else {	
					  $lgn_type = "IOS" ;     
				   }
				/* pass default image of user */
				$default_user_image_name=$this->config->BASE_URL . "/public/img/user.png";
				if($postedData['usr_image'] == ''){
					$default_image= $default_user_image_name;					
				}
				else{
					$default_image=$postedData['usr_image'];					
				}
                $loginData['lgn_type'] = 4;
                $loginData['lgn_google_auth_id'] = $postedData['lgn_google_auth_id'];
                $loginData['lgn_email'] = $postedData['lgn_email'];
                $loginData['lgn_createddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_updateddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_status'] = 1;
                $lgn_id = $loginModel->save($loginData, 'lgn_id');				
				
                $userData['usr_lgn_id'] = $lgn_id;

                $userData['usr_name'] = $postedData['usr_name'];
				$userData['usr_image'] = $default_image;
                $userData['usr_lgn_type'] = $lgn_type;
                $userData['usr_udid_number'] = $postedData['usr_udid_number'];
                $userData['usr_gcm_number'] = $postedData['usr_gcm_number'];
                $userData['usr_createddate'] = date('Y-m-d H:i:s');
                $userData['usr_updateddate'] = date('Y-m-d H:i:s');
                $userData['usr_status'] = 1;
				$userData['usr_lgn_type'] = 2;
				$userData['usr_gender'] = $postedData['usr_gender'];
                $usr_id = $userModel->save($userData, 'usr_id');

                $userGoogleData['gr_lgn_id'] = $lgn_id;
                $userGoogleData['gr_response'] = serialize($postedData);
                $userGoogleData['gr_createddate'] = date('Y-m-d H:i:s');
                $userGoogleData['gr_updateddate'] = date('Y-m-d H:i:s');
                $userGoogleData['gr_status'] = 1;
                $gr_id = $googleSessionModel->save($userGoogleData, 'gr_id');
				
				if($postedData['usr_gender']=='1'){
						$gender_type='Male';
				}else if($postedData['usr_gender']=='2'){
						$gender_type='Female';
				}else{
						$gender_type='';
				}
				
                $resultArray = Array
                    (
                    'lgn_id' => $lgn_id,
                    'lgn_email' => $postedData['lgn_email'],
                    'usr_id' => $usr_id,
					'usr_name' => $postedData['usr_name'],
					'usr_image' => $default_image,
                    'lgn_google_auth_id' => $postedData['lgn_google_auth_id'],
					'usr_gender'=>$gender_type,
                );

                $JSONARRAY = Array(
                    'STATUS' => 'SUCCESS',
                    'MESSAGES' => 'Sign up complete!',
                    'DATA' => $resultArray,
                );
				$logo_link = $this->view->BASE_URL . "public/img/";
				$user_profile_link=$this->view->BASE_URL . "admin/user/profile/usr_id/" . "$usr_id";
					$emailPar = array(
						'fileName' => 'register_admin.phtml',
						'subjectName' => 'User registration',
						'FromEmailId' => $ADMIN_MAIL,
						'FromName' => $ADMIN_FROM,
						'toEmailId' => $ADMIN_TO_MAILID,
						'toName' => $ADMIN_FROM,
						'pageType' => 'default',
						'assignFields' => array(
						
							'name' => $ADMIN_FROM,
							'password' => $new_password,
							'logolink' => $logo_link,
							'sitelink' => $this->view->BASE_URL,
							'user_profile_link' => $user_profile_link,         
							'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
							'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
							'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
						)
					);
									
					//echo"<pre>";print_r($emailPar);exit;				
					$result = $this->mailData($emailPar); 
            }
        }
       // echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
    }
	
	/* User Google Login Action */
    public function twitterloginAction() {
		
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $errorMessageArray = array();

        $loginModel = new Model_Webservice_Login(); // Load Login Model
        $userModel = new Model_Webservice_User(); // Load User Model
        $twiiterSessionModel = new Model_Webservice_Twitterresponse(); // Load Facebook Response Model

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if ($postedData['usr_name'] == '') {
                $errorMessageArray[] = 'Please enter name.';
            }

            if ($postedData['usr_udid_number'] == '') {
                $errorMessageArray[] = 'Please enter device imei no.';
            }

            if ($postedData['usr_gcm_number'] == '') {
                $errorMessageArray[] = 'Please enter gcm no.';
            }

           if ($postedData['lgn_twitter_auth_id'] == '') {
                $errorMessageArray[] = 'Please enter id.';
            } 

            /* if ($postedData['lgn_email'] == '') {
                $errorMessageArray[] = 'Please enter email.';
            } */
			
			if ($postedData['usr_lgn_type'] == '') {
                $errorMessageArray[] = 'Please enter login type.';
            }
			
				if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
            if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAILED',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
                $userAuthEntry = $loginModel->checktwitterAuthExist("lgn_twitter_auth_id = '" . $postedData['lgn_twitter_auth_id'] . "'");
            
                if (count($userAuthEntry) > 0) {
                    $loginData['lgn_id'] = $userAuthEntry['lgn_id'];
                    $userData['usr_id'] = $userAuthEntry['usr_id'];
                } else {
                   
                    if ($postedData['lgn_email'] != '') {
                         $logo_link = $this->view->BASE_URL . "public/img/";
					
						
						/* * *send Mail to user with verification coe * */
						 $metaDataModel = new Model_Metadata();
						$ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
						$ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
						$ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
						$ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
						$ADMIN_TO_MAIL = $metaDataModel->fetchEntryByKey("ADMIN_TO_MAIL");
						$ADMIN_TO_MAILID = $ADMIN_TO_MAIL['mtd_value'];
						$ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
						$ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

						$ADMIN_CONTACT_EMAIL_DATA = $metaDataModel->fetchEntryByKey("CONTACT_EMAIL");
						$ADMIN_CONTACT_EMAIL = $ADMIN_CONTACT_EMAIL_DATA['mtd_value'];

						$ADMIN_CONTACT_TELEPHONE_DATA = $metaDataModel->fetchEntryByKey("CONTACT_TELEPHONE");
						$ADMIN_CONTACT_TELEPHONE = $ADMIN_CONTACT_TELEPHONE_DATA['mtd_value'];
						$emailParuser = array(
							'fileName' => 'activation.phtml',
							'subjectName' => 'Registration Completed',
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => $postedData['lgn_email'],
							'toName' => $ADMIN_FROM,
							'pageType' => 'default',
							'assignFields' => array(
							
								'name' => $ADMIN_FROM,
								'usr_name' => $postedData['usr_name'],
								'logolink' => $logo_link,
								'sitelink' => $this->view->BASE_URL,        
								'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
								'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
								'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
							)
						);
										
						//echo"<pre>";print_r($emailPar);exit;				
						$result = $this->mailData($emailParuser);  	 
                    } 
                } 
				if ($postedData['usr_lgn_type']==1){
						
					   $lgn_type = "Android" ;
				   }else {	
					  $lgn_type = "IOS" ;     
				   }
				/* pass default image of user */
				$default_user_image_name=$this->config->BASE_URL . "/public/img/user.png";
				if($postedData['usr_image'] == ''){
					$default_image= $default_user_image_name;					
				}
				else{
					$default_image=$postedData['usr_image'];					
				}
				
				if($postedData['usr_gender'] == NULL){
					$postedData['usr_gender'] ='';
				}
                $loginData['lgn_type'] = 4;
                $loginData['lgn_twitter_auth_id'] = $postedData['lgn_twitter_auth_id'];
                $loginData['lgn_email'] = $postedData['lgn_email'];
                $loginData['lgn_createddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_updateddate'] = date('Y-m-d H:i:s');
                $loginData['lgn_status'] = 1;
                $lgn_id = $loginModel->save($loginData, 'lgn_id');				
				
                $userData['usr_lgn_id'] = $lgn_id;

                $userData['usr_name'] = $postedData['usr_name'];
				$userData['usr_image'] = $default_image;
                $userData['usr_lgn_type'] = $lgn_type;
                $userData['usr_udid_number'] = $postedData['usr_udid_number'];
                $userData['usr_gcm_number'] = $postedData['usr_gcm_number'];
                $userData['usr_createddate'] = date('Y-m-d H:i:s');
                $userData['usr_updateddate'] = date('Y-m-d H:i:s');
                $userData['usr_status'] = 1;
				$userData['usr_lgn_type'] = 2;
				$userData['usr_gender'] = $postedData['usr_gender'];
                $usr_id = $userModel->save($userData, 'usr_id');

                $userTwitterData['tr_lgn_id'] = $lgn_id;
                $userTwitterData['tr_response'] = serialize($postedData);
                $userTwitterData['tr_createddate'] = date('Y-m-d H:i:s');
                $userTwitterData['tr_updateddate'] = date('Y-m-d H:i:s');
                $userTwitterData['tr_status'] = 1;
                $tr_id = $twiiterSessionModel->save($userTwitterData, 'tr_id');
				
				if($postedData['usr_gender']=='1'){
						$gender_type='Male';
				}else if($postedData['usr_gender']=='2'){
						$gender_type='Female';
				}else{
						$gender_type='';
				}
				
                $resultArray = Array
                    (
                    'lgn_id' => $lgn_id,
                    'lgn_email' => $postedData['lgn_email'],
                    'usr_id' => $usr_id,
					'usr_name' => $postedData['usr_name'],
					'usr_image' => $default_image,
                    'lgn_twitter_auth_id' => $postedData['lgn_twitter_auth_id'],
					'usr_gender' => $gender_type,
                );

                $JSONARRAY = Array(
                    'STATUS' => 'SUCCESS',
                    'MESSAGES' => 'Sign up complete!',
                    'DATA' => $resultArray,
                );
				 $logo_link = $this->view->BASE_URL . "public/img/";
					$user_profile_link=$this->view->BASE_URL . "admin/user/profile/usr_id/" . "$usr_id";
					 $emailPar = array(
						'fileName' => 'register_admin.phtml',
						'subjectName' => 'User registration',
						'FromEmailId' => $ADMIN_MAIL,
						'FromName' => $ADMIN_FROM,
						'toEmailId' => $ADMIN_TO_MAILID,
						'toName' => $ADMIN_FROM,
						'pageType' => 'default',
						'assignFields' => array(
						
							'name' => $ADMIN_FROM,
							'password' => $new_password,
							'logolink' => $logo_link,
							'sitelink' => $this->view->BASE_URL,
							'user_profile_link' => $user_profile_link,         
							'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
							'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
							'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
						)
					);
									
								
					$result = $this->mailData($emailPar);   
					
            }
        }
       // echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
    
}
	
	/* All Post Action */
    public function postlistAction() {
		
		$errorMessageArray = array();
		$orderField = 'post_id';
        $sort = 'DESC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $postModel = new Model_Webservice_Post(); // Load post Model
		//$PostArray = $postModel->fetchPost();
		
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
			
			$itemsPerPageReviews = $this->PAGING_PER_PAGE;
          
			if($postedData['pageno'] != ''){
				$currentPageReviews = $postedData['pageno'];
			}
			else{
				$currentPageReviews = 1;
			}
            //PAGING_PER_PAGE
            if ($this->_getParam('page') && $this->_getParam('page') != '') {
                $currentPageReviews = $this->_getParam('page');
            }

            if ($this->_getParam('field') && $this->_getParam('field') != '') {
                $orderField = $this->_getParam('field');
            }

            if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
                $sort = $this->_getParam('sort');
            }
            $offset = ($currentPageReviews - 1) * $itemsPerPageReviews;
			
			
			if ($postedData['post_cat_id'] == "ALL"){
				
				$where = "post_type != 0" ;
			
			}
			else if ($postedData['post_cat_id'] == "GOOD"){
				
				$where = "post_type = 1 " ;
			
			}	/* fetch all good post  */
			else if($postedData['post_cat_id'] == "BAD"){
				$where = "post_type = 2 " ;
			}	/* fetch all bad post  */
			else{
				$where = "post_cat_id = ".$postedData['post_cat_id'];
			}
		
			
				$PostArray = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
				//echo"<pre>";print_r($PostArray);exit;
				$totalpost = $postModel->getpostCount($where);
				$totalcountpost = $postModel->getposttotalCount();
				
			/* Pagination Login */
			$paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);			 
            $paginator->setItemsTotal($postModel->getpostCount($where));			  
            $paginator->setCurrentPage($currentPageReviews);
								
			if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
			 if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0,
					'TOTALPOST' => $totalpost
                );
			 }else{
				 if ($PostArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $PostArray,
						'COUNT' => count($PostArray),
						'TOTALPOST' => $totalpost
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No Post present',
						'DATA' => Array(),
						'COUNT' => 0
					);
				}				 				 
			 }
			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No Post present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

       // echo json_encode($JSONARRAY);
	   echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	public function likeunlikepostAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'LIKE/UNLIKE POST',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();
        $likeUnlikeData = Array();
        $where = "lgn_type = 2";
		
		
		$notificationlogModel = new Model_Webservice_Notificationpostlikelog();
        $postModel = new Model_Webservice_Post(); // Load post model
        $loginModel = new Model_Webservice_Login(); // Load login model
        $likepostModel = new Model_Webservice_Postlike(); // Load post like model

        $LikepostForm = new Form_Services_LikeunlikepostForm(); // Load post like form

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$LikepostForm->isValid($postedData)) {
                $errorMessage = $LikepostForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }

                if (count($errorMessageArray) > 1) {
                    $errorMessageText = 'Please enter required details.';
                } else {
                    $errorMessageText = $errorMessageArray;
                }

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'LIKE/UNLIKE POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
				
                /** check user exist or not * */
                if ($postedData['like_lgn_id'] != "") {
                   $userExist = $loginModel->fetchEntryById($postedData['like_lgn_id']);				   				
                    if (count($userExist) == 0) {
                        $errorMessageArray[] = "User doesn't exist.";
                    }
                }

                /** check post exist or not * */
                if ($postedData['like_post_id'] != "") {
					$where_post = "post_lgn_type = 2";
                    $postExist = $postModel->fetchEntryBypostId($postedData['like_post_id'],$where_post);
                    if (count($postExist) == 0) {
                        $errorMessageArray[] = "Post doesn't exist.";
                    }
                }
				
				 $userpostid = $loginModel->fetchEntryById($postExist['post_lgn_id']);
//print_r($userpostid);exit;
                if (count($errorMessageArray) > 0) {

                    if (count($errorMessageArray) > 1) {
                        $errorMessage = 'Please enter required details';
                    } else {
                        $errorMessage = $errorMessageArray[0];
                    }

                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'TYPE' => 'LIKE/UNLIKE POST',
                        'MESSAGES' => $errorMessage,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
					
					if($postedData['like_type'] == 1){
						$like_type = 'Good';
					} else {
						$like_type = 'Bad';
					}
					
					if($postExist['post_lgn_type'] == 1){
						$like_lgn_type = 'Admin';
					} else {
						$like_lgn_type = 'User';
					}
					
					
					
                    $resultArray = array(
                        'like_lgn_id' => $postedData['like_lgn_id'],
                        'like_post_id' => $postedData['like_post_id'],
                        'like_type' => $postedData['like_type'],
                        'like_lgn_type' => $like_lgn_type,
						//'count' => $badcount,
						
                    );
					
						
				$wherelike = "like_lgn_id = ".$postedData['like_lgn_id']." and like_post_id = ".$postedData['like_post_id']." ";
				$totallikepost = $likepostModel->fetchPostLike($wherelike);
					
					/*If comment already exists than update it*/
					if(count($totallikepost)>0){
						
						$likePostExist=$totallikepost['like_post_id'];
						$likeLgnExist=$totallikepost['like_lgn_id'];
						$liketypeExist=$totallikepost['like_type'];
						$likestatusExist=$totallikepost['like_status'];
						/* save data the notification log table*/
						
								if($postedData['like_type']==$liketypeExist)
								{
									if($postedData['like_type']==1)
									{
										$errorMessageArray[]="already liked";
									}else if($postedData['like_type']==2)
									{
										$errorMessageArray[]="already disliked";
									}
								}
								
									
									
								
								if(count($errorMessageArray)>0){
									if (count($errorMessageArray) > 1) {
										$errorMessage = 'Please enter required details';
									} else {
										$errorMessage = $errorMessageArray[0];
									}
									//count like and dislike post
									$bad_like = "like_post_id = ".$postedData['like_post_id']." and like_type = '2' ";
									$badcount = $likepostModel->getpostlikeCount($bad_like);
									$resultArray['badcount']=$badcount;
									
									$good_like = "like_post_id = ".$postedData['like_post_id']." and like_type = '1' ";
									$goodcount = $likepostModel->getpostlikeCount($good_like);
									$resultArray['goodcount']=$goodcount;
									
									// if post is already like then message is changed
								
									$JSONARRAY = Array(
										'STATUS' => 'FAIL',
										'TYPE' => 'LIKE/UNLIKE POST',
										'MESSAGES' => $errorMessage,
										'DATA' => $resultArray,
										//'COUNT' => 0
									);
									
									
								}else{
									
									$likeUnlikeData['like_id'] = $totallikepost['like_id'];
									$likeUnlikeData['like_type'] = $postedData['like_type'];
									$likeUnlikeData['like_lgn_id'] = $postedData['like_lgn_id'];
									$likeUnlikeData['like_post_id'] = $postedData['like_post_id'];
									$likeUnlikeData['like_lgn_type'] = $postExist['post_lgn_type'];
									$likeUnlikeData['like_cretaeddate'] = date('Y-m-d H:i:s');
									$likeUnlikeData['like_updateddate'] = date('Y-m-d H:i:s');
									$likeUnlikeData['like_status'] = 1;
									$likepostModel->save($likeUnlikeData, 'like_id');
									
									 $where = "like_post_id = ".$postedData['like_post_id']." and like_type = '2' ";
									$badcount = $likepostModel->getpostlikeCount($where);
									$resultArray['badcount']=$badcount;
									//echo $badcount;exit;
									
									$wheregood = "like_post_id = ".$postedData['like_post_id']." and like_type = '1' ";
									$goodcount = $likepostModel->getpostlikeCount($wheregood);
									$resultArray['goodcount']=$goodcount;	
//echo $postedData['like_lgn_id'];echo $userpostid['usr_lgn_id']; exit;									
									 $JSONARRAY = Array(
										'STATUS' => 'SUCCESS',
										'TYPE' => 'LIKE/UNLIKE POST',
										'MESSAGES' => 'Like/Unlike Post success.',
										'DATA' => $resultArray,
										
										);
																			
									if($postedData['like_lgn_id']!=$userpostid['usr_lgn_id']){	
										/* for send notification */
										$post_name=$postExist['post_title'];
										$usr_name=$userExist['usr_name'];
										//$userExist['usr_gcm_number'];
										if($postedData['notif_like_type'] == 1){
											$message='Your post '."$post_name".' is liked by '."$usr_name".'';
											$like='Liked';
											$type='detail';
										} else {
											$message='Your post '."$post_name".' is dis-liked by '."$usr_name".'';
											$like='Unliked';
											$type='detail';
										}
										
										
										$deviceid=$userpostid['usr_gcm_number'];
										/*Android notification start*/	
										if($deviceid != ""){
											/*  $fields['notification'] = Array(
														'title' => $message,
														'body' => $message,
														'sound' => 1
														);  */
							
											$fields['data'] = Array(
														'post_id' => $postedData['like_post_id'],
														'title' =>$like,
														'message' =>$message,
														'type'=>$type,
														
														//'body' => urlencode($message),	
														);
											$fields['to'] = $deviceid;
											//print_r($fields);
											$gtlCommon = new GTL_Common();
											$notificationResp = $gtlCommon->sendPushNotification($fields);
											//print_r($notificationResp);					
											/*Android notification end*/ 
										
										}
										/* Save data in Postlike Notification log tbl */
										$postcommentData['notf_like_lgn_id'] = $postedData['like_lgn_id'];
										$postcommentData['notf_like_post_id'] = $postedData['like_post_id'];
										$postcommentData['notf_createddate'] = date('Y-m-d H:i:s');
										if($postedData['like_type']=='1')
										{
											$postcommentData['notf_like_type'] = '1';
										}else
										{
											$postcommentData['notf_like_type'] = '2';
										}
										$postcommentData['notf_status'] = 1;
										$notif_id = $notificationlogModel->save($postcommentData, 'notif_id');
									}
								}
					}else
					/* If comment not exist than add it */
					{
						$likeUnlikeData['like_lgn_id'] = $postedData['like_lgn_id'];
						$likeUnlikeData['like_post_id'] = $postedData['like_post_id'];
						$likeUnlikeData['like_type'] = $postedData['like_type'];
						$likeUnlikeData['like_lgn_type'] = $postExist['post_lgn_type'];
						$likeUnlikeData['like_cretaeddate'] = date('Y-m-d H:i:s');
						$likeUnlikeData['like_updateddate'] = date('Y-m-d H:i:s');
						$likeUnlikeData['like_status'] = 1;
						
						$likepostModel->save($likeUnlikeData, 'like_id');
						
						 $where = "like_post_id = ".$postedData['like_post_id']." and like_type = '2' ";
									$badcount = $likepostModel->getpostlikeCount($where);
									$resultArray['badcount']=$badcount;
									//echo $badcount;exit;
									
									$wheregood = "like_post_id = ".$postedData['like_post_id']." and like_type = '1' ";
									$goodcount = $likepostModel->getpostlikeCount($wheregood);
									$resultArray['goodcount']=$goodcount; 
									
						 $JSONARRAY = Array(
							'STATUS' => 'SUCCESS',
							'TYPE' => 'LIKE/UNLIKE POST',
							'MESSAGES' => 'Like/Unlike Post success.',
							'DATA' => $resultArray,
						
						);
						if($postedData['like_lgn_id']!=$userpostid['usr_lgn_id']){	
							/* for send notification */
							$post_name=$postExist['post_title'];
							$usr_name=$userExist['usr_name'];
							if($postedData['notif_like_type'] == 1){
								$message='Your post '."$post_name".' is liked by '."$usr_name".'';
								$like='Liked';
								$type='detail';
							} else {
								$message='Your post '."$post_name".' is dis-liked by '."$usr_name".'';
								$like='Unliked';
								$type='detail';
							}
							
							
							$deviceid=$userpostid['usr_gcm_number'];
							/*Android notification start*/	
							if($deviceid != ""){
								 /* $fields['notification'] = Array(
											'title' => $message,
											'body' => $message,
											'sound' => 1
											); 
								*/
								$fields['data'] = Array(
											'post_id' => $postedData['like_post_id'],
											'title' =>$like,
											'message' =>$message,
											'type'=>$type,
											
											//'body' => urlencode($message),
											
											
										);
								$fields['to'] = $deviceid;
									//print_r($fields);exit;	
								$gtlCommon = new GTL_Common();
								$notificationResp = $gtlCommon->sendPushNotification($fields);
								//print_r($notificationResp);					
								/*Android notification end*/ 
								
							}	

							/* Save data in Postlike Notification log tbl */
							$postcommentData['notf_like_lgn_id'] = $postedData['like_lgn_id'];
							$postcommentData['notf_like_post_id'] = $postedData['like_post_id'];
							$postcommentData['notf_createddate'] = date('Y-m-d H:i:s');
							if($postedData['like_type']=='1')
							{
								$postcommentData['notf_like_type'] = '1';
							}else
							{
								$postcommentData['notf_like_type'] = '2';
							}
							$postcommentData['notf_status'] = 1;
							$notif_id = $notificationlogModel->save($postcommentData, 'notif_id');
						}
						
					}

                   
                }

                echo json_encode($JSONARRAY);
                exit;
            }
        }
        echo json_encode($JSONARRAY);
        exit;
    }
	
	/* All Post Action */
    public function mypostAction() {
		
		$errorMessageArray = array();
		$orderField = 'post_id';
        $sort = 'DESC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $postModel = new Model_Webservice_Post(); // Load post Model
		$loginModel = new Model_Webservice_Login(); // Load login model
		
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
			
			$itemsPerPageReviews = $this->PAGING_PER_PAGE;
          
			if($postedData['pageno'] != ''){
				$currentPageReviews = $postedData['pageno'];
			}
			else{
				$currentPageReviews = 1;
			}
            //PAGING_PER_PAGE
            if ($this->_getParam('page') && $this->_getParam('page') != '') {
                $currentPageReviews = $this->_getParam('page');
            }

            if ($this->_getParam('field') && $this->_getParam('field') != '') {
                $orderField = $this->_getParam('field');
            }

            if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
                $sort = $this->_getParam('sort');
            }
            $offset = ($currentPageReviews - 1) * $itemsPerPageReviews;
			
			if ($postedData['post_lgn_id'] == '') {
                $errorMessageArray[] = 'Please enter login id.';
            }
			if ($postedData['post_type'] == '') {
                $errorMessageArray[] = 'Please enter post type.';
            }
			/** check user exist or not * */
			if ($postedData['post_lgn_id'] != "") {
			   $userExist = $loginModel->fetchEntryById($postedData['post_lgn_id']);				   				
				if (count($userExist) == 0) {
					$errorMessageArray[] = "User doesn't exist.";
				}
			}
			
			if(count($errorMessageArray) > 1){
				$errorMessageText = 'Please enter required details.';
			}else{
				$errorMessageText = $errorMessageArray[0];
			}
			
			if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0,
                );
			} else {
				
				
				if ($postedData['post_lgn_id'] != "" ){					
					// filtered by good bad post start
					if($postedData['post_type']=='good' && $postedData['post_lattitude'] != "" && $postedData['post_longitude'] != ""){	
						$where = "post_lgn_id = ".$postedData['post_lgn_id']." and post_type = 1 and post_lattitude>=".$postedData['post_lattitude']."and post_longitude <=".$postedData['post_longitude']."" ;
					}
					else if($postedData['post_type']=='good' && $postedData['post_lattitude'] == "" && $postedData['post_longitude'] == ""){	
						$where = "post_lgn_id = ".$postedData['post_lgn_id']." and post_type = 1 " ;
					}
					elseif($postedData['post_type']=='bad' && $postedData['post_lattitude'] != "" && $postedData['post_longitude'] != ""){
						$where = "post_lgn_id = ".$postedData['post_lgn_id']." and post_type = 2 and post_lattitude>=".$postedData['post_lattitude']."and post_longitude <=".$postedData['post_longitude']."" ;
					}
					elseif($postedData['post_type']=='bad' && $postedData['post_lattitude'] == "" && $postedData['post_longitude'] == ""){
						$where = "post_lgn_id = ".$postedData['post_lgn_id']." and post_type = 2 " ;
					}
					elseif($postedData['post_type']=='all' && $postedData['post_lattitude'] != "" && $postedData['post_longitude'] != ""){
						$where = "post_lgn_id = ".$postedData['post_lgn_id']." and post_lattitude>=".$postedData['post_lattitude']."and post_longitude <=".$postedData['post_longitude']."" ;
					}
					else{	
						$where = "post_lgn_id = ".$postedData['post_lgn_id']."";
					}
					$PostArray = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
					$totalpost = $postModel->getpostCount($where);
				
				} else {
					$where = "";		
				}
				
								
				$totalcountpost = $postModel->getposttotalCount();
				/* Pagination Login */
				$paginator = new GTL_Paginator();
				$paginator->setItemsPerPage($itemsPerPageReviews);			 
				$paginator->setItemsTotal($postModel->getpostCount($where));			  
				$paginator->setCurrentPage($currentPageReviews);
				
				 if ($PostArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $PostArray,
						'FILTERTYPE'=>$postedData['post_type'],
						'COUNT' => count($PostArray),
						'TOTALPOST' => $totalpost
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No Post present',
						'DATA' => Array(),
						'COUNT' => 0
					);
				}				 				 
			}			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No Post present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

        //echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	public function addpostAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'ADD POST',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();
        $likeUnlikeData = Array();
        $where = "lgn_type = 2";

        $postModel = new Model_Webservice_Post(); // Load post model
        $loginModel = new Model_Webservice_Login(); // Load login model
		$categoryModel = new Model_Webservice_Category(); // Load login model

        $AddpostForm = new Form_Services_AddpostForm(); // Load like post form

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$AddpostForm->isValid($postedData)) {
                $errorMessage = $AddpostForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }

                if (count($errorMessageArray) > 1) {
                    $errorMessageText = 'Please enter required details.';
                } else {
                    $errorMessageText = $errorMessageArray;
                }

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'ADD POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
				
				if ($_FILES['post_image']['name'] == '') {
					$errorMessageArray[] = 'Please Upload image.';
				}

                /** check user exist or not * */
                if ($postedData['post_lgn_id'] != "") {
                   $userExist = $loginModel->fetchEntryById($postedData['post_lgn_id']);				   				
                    if (count($userExist) == 0) {
                        $errorMessageArray[] = "User doesn't exist.";
                    }
                }
				
				if ($postedData['post_img_status'] == '') {
                   
                        $errorMessageArray[] = "please enter upload file status.";
                    
                }

                /** check post exist or not * */
                if ($postedData['post_cat_id'] != "") {
                    $categoryExist = $categoryModel->fetchEntryById($postedData['post_cat_id']);
                    if (count($categoryExist) == 0) {
                        $errorMessageArray[] = "Category doesn't exist.";
                    }
                }

                if (count($errorMessageArray) > 0) {

                    if (count($errorMessageArray) > 1) {
                        $errorMessage = 'Please enter required details';
                    } else {
                        $errorMessage = $errorMessageArray[0];
                    }

                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'TYPE' => 'ADD POST',
                        'MESSAGES' => $errorMessage,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
					
					 
					$uploadvideo = new Zend_File_Transfer_Adapter_Http();
                    foreach ($uploadvideo->getFileInfo() as $fields => $asFileInfo) {
						
                        if ($asFileInfo["name"] != "") {
							if($postedData['post_img_status'] == 2){
                            $videofolder = $this->config->UPLOAD_PATH . "post";
                            $uploadvideo->setDestination($videofolder);
                            $originalFilename = pathinfo($asFileInfo["name"]);
                            $fileName = preg_replace("/[^-_a-zA-Z0-9]+/i", "_", $originalFilename["filename"]) . "_" . time() . "." . $originalFilename["extension"];
                            $uploadvideo->addFilter("Rename", $fileName, "filename");
                            $uploadvideo->receive($fields);
                            $postData["post_video"] = $fileName;
							
							
							$VideoPath = $videofolder."/".$fileName;
							
							
							$unique_number = uniqid();
							
							$video_file = "video_".$unique_number.".jpg";
							$video_thumbPath = $videofolder."/".$video_file;
							$size = "320x240";
							
							$shell = shell_exec("ffmpeg -i $VideoPath -pix_fmt yuvj422p -deinterlace -an -ss 5 -f mjpeg -t 1 -r 1 -y -s $size $video_thumbPath 2>&1"); 
							$postData["post_image"] = $video_file;
							
							}
							elseif($postedData['post_img_status'] == 1){
							
							$folder = $this->config->UPLOAD_PATH . "post";
                            $uploadvideo->setDestination($folder);
                            $originalFilename = pathinfo($asFileInfo["name"]);
                            $fileName = preg_replace("/[^-_a-zA-Z0-9]+/i", "_", $originalFilename["filename"]) . "_" . time() . "." . $originalFilename["extension"];
                            $uploadvideo->addFilter("Rename", $fileName, "filename");
                            $uploadvideo->receive($fields);
                            $postData["post_image"] = $fileName;
							/* get image height and width */
							$image_name=$folder . '/' . $fileName;
							list($width, $height, $type, $attr) = getimagesize($image_name); 
							
							
							
							

							}
							
                        }
                    }
					if($postData["post_image"]==NULL)
					{
						$postData["post_image"]='';
					}
					if($postData["post_video"]==NULL)
					{
						$postData["post_video"]='';
					}
					if($postData["post_img_status"]==NULL)
					{
						$postData["post_img_status"]='';
					}
					if($postData["post_lattitude"]==NULL)
					{
						$latitude=$postData["post_lattitude"]='';
					}
					else{
						$latitude=$postData["post_lattitude"];
					}
					if($postData["post_longitude"]==NULL)
					{
						$longitude=$postData["post_longitude"]='';
					}else{
						$longitude=$postData["post_longitude"];
					}
					                 
                    $postData['post_cat_id'] = $postedData['post_cat_id'];
                    $postData['post_lgn_id'] = $postedData['post_lgn_id'];
                    $postData['post_title'] = $postedData['post_title'];
                    $postData['post_desc'] = $postedData['post_desc'];
					//$postData['post_image'] = $postedData['post_image'];
					$postData['post_location'] = $postedData['post_location'];
					$postData['post_lattitude'] = $latitude;
					$postData['post_longitude'] = $longitude;
					$postData['post_type'] = $postedData['post_type'];
					$postData['post_createddate'] = date('Y-m-d H:i:s');
                    $postData['post_updateddate'] = date('Y-m-d H:i:s');
                    $postData['post_status'] = 1;
					$postData['post_approve_status'] = 1;
					$postData['post_lgn_type'] = 2;
					$postData['post_img_status'] = $postedData['post_img_status'];
					$last_id=$postModel->save($postData, 'post_id');
					
					if (file_exists($folder .'/' .$postData["post_image"])) {
					$img_path= $folder .'/' .$postData["post_image"];		
					}
					else{
						$img_path='';
					}

					if($postedData['post_type'] == 1){
						$post_type = 'Good';
					} else {
						$post_type = 'Bad';
					}
					
					if($postData['post_lgn_type'] == 1){
						$post_lgn_type = 'Admin';
					} else {
						$post_lgn_type = 'User';
					}
					
					if($postData['post_status'] == 1){
						$post_status = 'Active';
					} else {
						$post_status = 'In-Active';
					}
					 
					
                    $resultArray = array(
                        'post_cat_id' => $postData['post_cat_id'],
						'cat_name' => $categoryExist['cat_name'],
                        'post_lgn_id' => $postData['post_lgn_id'],
						'usr_name' => $userExist['usr_name'],
						'post_title' => $postData['post_title'],
                        'post_desc' => $postData['post_desc'],
						'post_image' => $postData['post_image'],
						'post_video' => $postData['post_video'],
						'post_location' => $postData['post_location'],
						'post_lattitude' => $latitude,
						'post_longitude' => $longitude,
                        'post_status' => $post_status,
                        'post_type' => $post_type,
                        'post_lgn_type' => $post_lgn_type,
						'post_img_status'=> $postData['post_img_status'],
						'img_path' =>$img_path ,
						'img_width' => $width,
						'img_height' => $height,
                    );

                    $JSONARRAY = Array(
                        'STATUS' => 'SUCCESS',
                        'TYPE' => 'ADD POST',
                        'MESSAGES' => 'Add Post success.',
                        'DATA' => $resultArray,
                        'COUNT' => count($resultArray)
                    );
					
					
					/* * *send Mail to user with verification coe * */
				$metaDataModel = new Model_Metadata();
                $ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
                $ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
                $ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
                $ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
				
				$ADMIN_TO_MAIL = $metaDataModel->fetchEntryByKey("ADMIN_TO_MAIL");
                $ADMIN_TO_MAILID = $ADMIN_TO_MAIL['mtd_value'];


                $ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
                $ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

                $ADMIN_CONTACT_EMAIL_DATA = $metaDataModel->fetchEntryByKey("CONTACT_EMAIL");
                $ADMIN_CONTACT_EMAIL = $ADMIN_CONTACT_EMAIL_DATA['mtd_value'];

                $ADMIN_CONTACT_TELEPHONE_DATA = $metaDataModel->fetchEntryByKey("CONTACT_TELEPHONE");
                $ADMIN_CONTACT_TELEPHONE = $ADMIN_CONTACT_TELEPHONE_DATA['mtd_value'];
				$postid=base64_encode($last_id);
                $logo_link = $this->view->BASE_URL . "public/img/";
				$viewpost_link=$this->view->BASE_URL . "/admin/post/form/post_id/" ."$last_id"; 
				$activation_link=$this->view->BASE_URL . 'index/activate/post_id/' . $postid ;
                $emailPar = array(
                    'fileName' => 'addpost_admin.phtml',
                    'subjectName' => 'Added New Post',
                    'FromEmailId' => $ADMIN_MAIL,
                    'FromName' => $ADMIN_FROM,
                    'toEmailId' => $ADMIN_TO_MAILID,
                    'toName' => $ADMIN_FROM,
                    'pageType' => 'default',
                    'assignFields' => array(
						'post_title' => $postData['post_title'],
						'viewpost_link' => $viewpost_link,
                        'name' => $ADMIN_FROM,
                        'password' => $actualPassword,
                        'logolink' => $logo_link,
						'sitelink' => $this->view->BASE_URL,
						'activation_link'=>$activation_link,             
                        'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
                        'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
                        'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
                    )
                );
								
								
				//$result = $this->mailData($emailPar); 
				
                }

                echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
        echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
    }
	
	public function categorylistAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'Category List',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();

        $categoryModel = new Model_Webservice_Category(); // Load category model
		$categoryArray = $categoryModel->fetchCategory();
		
		$JSONARRAY = Array(
			'STATUS' => 'SUCCESS',
			'TYPE' => 'Category List',
			'MESSAGES' => 'Show Category List.',
			'DATA' => $categoryArray,
			'COUNT' => count($categoryArray)
		);
		
		echo json_encode($JSONARRAY);
		exit;
	}
	
	public function addcommentAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'ADD COMMENT',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();
        $commentData = Array();
		$postcommentData = Array();
		$notificationlogModel = new Model_Webservice_Notificationlog();
        
        $postModel = new Model_Webservice_Post(); // Load post model
        $loginModel = new Model_Webservice_Login(); // Load login model
		$commentModel = new Model_Webservice_Postcomment(); // Load Comment model
		$userModel=new Model_Webservice_User();		
        $AddcommentForm = new Form_Services_AddcommentForm(); // Load Comment form
		 $likepostModel = new Model_Webservice_Postlike(); // Load post like model
		$postcomModel = new Model_Postcomment();
        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$AddcommentForm->isValid($postedData)) {
                $errorMessage = $AddcommentForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }

                if (count($errorMessageArray) > 1) {
                    $errorMessageText = 'Please enter required details.';
                } else {
                    $errorMessageText = $errorMessageArray;
                }

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'ADD COMMENT',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
							
                /** check user exist or not * */
                if ($postedData['cmt_lgn_id'] != "") {
                   $userExist = $loginModel->fetchEntryById($postedData['cmt_lgn_id']);				   				
                    if (count($userExist) == 0) {
                        $errorMessageArray[] = "User doesn't exist.";
                    }
                }
				
                /** check post exist or not * */
                if ($postedData['cmt_post_id'] != "") {
                    $postExist = $postModel->fetchEntryById($postedData['cmt_post_id']);
                    if (count($postExist) == 0) {
                        $errorMessageArray[] = "Post doesn't exist.";
                    }
                }
				$userpostid = $loginModel->fetchEntryById($postExist['post_lgn_id']);
				if ($postedData['cmt_type'] == 1) {
					if($postedData['cmt_ref'] == ""){
						$errorMessageArray[] = "Comment Reference should not be blank.";
					} else if($postedData['cmt_ref'] != "") {
						$commentExist = $commentModel->fetchEntryById($postedData['cmt_ref']);				   				
						if (count($commentExist) == 0) {
							$errorMessageArray[] = "Comment doesn't exist.";
						} 	
					}  					
				}

                if (count($errorMessageArray) > 0) {

                    if (count($errorMessageArray) > 1) {
                        $errorMessage = 'Please enter required details';
                    } else {
                        $errorMessage = $errorMessageArray[0];
                    }

                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'TYPE' => 'ADD COMMENT',
                        'MESSAGES' => $errorMessage,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
										             
                    $commentData['cmt_lgn_id'] = $postedData['cmt_lgn_id'];
                    $commentData['cmt_post_id'] = $postedData['cmt_post_id'];
                    $commentData['cmt_msg'] = $postedData['cmt_msg'];
					if ($postedData['cmt_type'] == 1) {
						$commentData['cmt_ref'] = $postedData['cmt_ref'];
						$commentData['cmt_post_count'] = $postedData['cmt_post_id'];
					} else {
						$commentData['cmt_ref'] = '0';
						$commentData['cmt_post_count'] = '0';
					}
					$commentData['cmt_createddate'] = date('Y-m-d H:i:s');
                    $commentData['cmt_updateddate'] = date('Y-m-d H:i:s');
                    $commentData['cmt_status'] = 1;
					$cmt_id = $commentModel->save($commentData, 'cmt_id');
					if($postedData['cmt_type']==0){
						/* $where_creply_que = "cmt_post_id = ".$postedData['cmt_post_id']." and cmt_lgn_id = ".$postedData['cmt_lgn_id']." and cmt_ref!=0";
						$Postrepcountcomment = $postcomModel->getcommentreplyCount($where_creply_que); */
						$Postrepcountcomment = '0';
						
						$bad_like = "like_post_id = ".$postedData['cmt_post_id']." and like_lgn_id = ".$postedData['cmt_lgn_id']." and like_type = '2'";
						$badcount = $likepostModel->getpostlikeCount($bad_like);
						//$resultArray['badcount']=$badcount;
					
						$good_like = "like_post_id = ".$postedData['cmt_post_id']." and like_lgn_id = ".$postedData['cmt_lgn_id']." and like_type = '1' ";
						$goodcount = $likepostModel->getpostlikeCount($good_like);
						
						$total_count = "cmt_post_id = ".$postedData['cmt_post_id']." and cmt_ref=0 ";
						$totalcount = $postcomModel->getcommentreplyCount($total_count); 
							if($postedData['like_lgn_id']!=$userpostid['usr_lgn_id']){	
								/* Send notification when parent comment is added code start*/
								$post_title=$postExist['post_title'];
								$usr_name=$userExist['usr_name'];
								$message='A comment posted on '."$post_title".' by '."$usr_name".'';
								$deviceid=$userpostid['usr_gcm_number'];
									
								if($deviceid != ""){
									/* $fields['notification'] = Array(
												'title' => $message,
												'body' => $message,
												'sound' => 1
												);  */
						
									$fields['data'] = Array(
												'post_id' => $postedData['cmt_post_id'],
												'title' =>$commentData['cmt_msg'],
												'message' =>$message,
												'type'=>'detail',
												//'body' => urlencode($message),
												
												
											);
									$fields['to'] = $deviceid;
										
									$gtlCommon = new GTL_Common();
									$notificationResp = $gtlCommon->sendPushNotification($fields);
									//print_r($notificationResp);				
								}
								/* Save data for comment notification log tbl */
								$postcommentData['notf_cmt_lgn_id'] = $postedData['cmt_lgn_id'];
								$postcommentData['notf_cmt_post_id'] = $postedData['cmt_post_id'];
								$postcommentData['notf_createddate'] = date('Y-m-d H:i:s');
								$postcommentData['notf_status'] = '1';
								$notif_id = $notificationlogModel->save($postcommentData, 'notif_id');
							
								/* Send notification when parent comment is added code end*/
							}
					}
					else{
						
					$where_creply_que = "cmt_post_id = ".$postedData['cmt_post_id']." and cmt_ref = ".$postedData['cmt_ref']." and cmt_lgn_id = ".$postedData['cmt_lgn_id']."";
					$Postrepcountcomment = $postcomModel->getcommentreplyCount($where_creply_que);
					
					$bad_like = "like_post_id = ".$postedData['cmt_post_id']." and like_type = '2' and like_lgn_id = ".$postedData['cmt_lgn_id']."";
					$badcount = $likepostModel->getpostlikeCount($bad_like);
					//$resultArray['badcount']=$badcount;
					
					$good_like = "like_post_id = ".$postedData['cmt_post_id']." and like_type = '1' and like_lgn_id = ".$postedData['cmt_lgn_id']."";
					$goodcount = $likepostModel->getpostlikeCount($good_like);
					//$resultArray['goodcount']=$goodcount;
					$total_count = "cmt_post_id = ".$postedData['cmt_post_id']." and cmt_ref = ".$postedData['cmt_ref']." and cmt_lgn_id = ".$postedData['cmt_lgn_id']."";
					$totalcount = $postcomModel->getcommentreplyCount($total_count);
						if($postedData['like_lgn_id']!=$userpostid['usr_lgn_id']){	
							/* Send notification when child comment is added code start*/
							
							$post_title=$postExist['post_title'];
							$usr_name=$userExist['usr_name'];
							
							$message='A comment reply on '."$post_title".' by '."$usr_name".'';
							$deviceid=$userpostid['usr_gcm_number'];
								
							if($deviceid != ""){
								/* $fields['notification'] = Array(
											'title' => $message,
											'body' => $message,
											'sound' => 1
											);  */
					
								$fields['data'] = Array(
											'post_id' => $postedData['cmt_post_id'],
											'title' =>$commentData['cmt_msg'],
											'message' =>$message,
											'type'=>'detail',
											//'body' => urlencode($message),
											
											
										);
								$fields['to'] = $deviceid;
									
								$gtlCommon = new GTL_Common();
								$notificationResp = $gtlCommon->sendPushNotification($fields);
								//print_r($notificationResp);				
							}
							/* Save data for comment notification log tbl */
							$postcommentData['notf_cmt_lgn_id'] = $postedData['cmt_lgn_id'];
							$postcommentData['notf_cmt_post_id'] = $postedData['cmt_post_id'];
							$postcommentData['notf_createddate'] = date('Y-m-d H:i:s');
							$postcommentData['notf_status'] = '1';
							$notif_id = $notificationlogModel->save($postcommentData, 'notif_id');
							/* Send notification when child comment is added code end*/
						}
					}

					$where = "usr_lgn_id = ".$postedData['cmt_lgn_id'];
					$userImage=$userModel->fetchUserprofileData($where); 
					//print_r($userImage);exit;
					$image=$userImage['usr_image'];
					
					
					if($commentData['cmt_status'] == 1){
						$cmt_status = 'Active';
					} else {
						$cmt_status = 'In-Active';
					}
					
					
                    $resultArray = array(
						'cmt_id' => $cmt_id,
                        'cmt_lgn_id' => $commentData['cmt_lgn_id'],
						'usr_name' => $userExist['usr_name'],
						'usr_image'=>$image,
						'cmt_post_id' => $commentData['cmt_post_id'],
						'post_title' => $postExist['post_title'],
						'cmt_msg' => $commentData['cmt_msg'],
                        'cmt_status' => $cmt_status,
						'cmt_ref' => $commentData['cmt_ref'],
						'cmt_createddate' => $commentData['cmt_createddate'],
						'cmt_updateddate' => $commentData['cmt_updateddate'],
						'badcount' => $badcount,
						'goodcount' => $goodcount,
						'reply_count' => $Postrepcountcomment,
						'total_count' => $totalcount,
						
                    );
					
					

                    $JSONARRAY = Array(
                        'STATUS' => 'SUCCESS',
                        'TYPE' => 'ADD COMMENT',
                        'MESSAGES' => 'Add Comment success.',
                        'DATA' => $resultArray,
						//'Userdata'=>$userData,
                        'COUNT' => count($resultArray)
                    );
                }

                //echo json_encode($JSONARRAY);
				 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
				 //print_r($JSONARRAY);
                exit;
            }
        }
        //echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
    }

	/* All comment Action */
    public function commentlistAction() {
		
		$errorMessageArray = array();
		$orderField = 'cmt_id';
        $sort = 'ASC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
		
		$postModel = new Model_Webservice_Post(); // Load post Model
        $postcommentModel = new Model_Webservice_Postcomment(); // Load post Model
		$postcomModel = new Model_Postcomment();
		$likecommentModel = new Model_Webservice_Commentlike(); // Load post like model
		
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
									
			if ($postedData['post_id'] == '') {
                $errorMessageArray[] = 'Please enter comment post id.';
            }
			
			/** check user exist or not * */
			if ($postedData['post_id'] != "") {
			   $postExist = $postModel->fetchEntryById($postedData['post_id']);				   				
				if (count($postExist) == 0) {
					$errorMessageArray[] = "Post doesn't exist.";
				}
			}
			
			if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
			 if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST COMMENT',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
			 }else{
				 
				 $itemsPerPageReviews = $this->PAGING_PER_PAGE;
          
				if($postedData['pageno'] != ''){
					$currentPageReviews = $postedData['pageno'];
				}
				else{
					$currentPageReviews = 1;
				}
				//PAGING_PER_PAGE
				if ($this->_getParam('page') && $this->_getParam('page') != '') {
					$currentPageReviews = $this->_getParam('page');
				}

				if ($this->_getParam('field') && $this->_getParam('field') != '') {
					$orderField = $this->_getParam('field');
				}

				if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
					$sort = $this->_getParam('sort');
				}
				$offset = ($currentPageReviews - 1) * $itemsPerPageReviews;
				
				$where = " cmt_post_id = ".$postedData['post_id']." AND cmt_ref = 0 ";					
				$PostcommentArray = $postcommentModel->fetchPostComment($where, $itemsPerPageReviews, $offset, $orderField, $sort);
				
				$where_ref = "cmt_post_id = ".$postedData['post_id']." AND cmt_ref != 0";
				$Postrefcomment = $postcommentModel->fetchEntryByrefId($where_ref);
				//echo"<pre>";print_r($PostcommentArray);exit;
				$finalArray = array();
				$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
				foreach($PostcommentArray as $p_key => $p_val){
					//echo"<pre>";print_r($p_key);exit;
					
						$image_url = $p_val['usr_image'];
					
					$finalArray[$p_key] = $p_val;
					//$finalArray[$p_key]['reply'] = array_values($Postrefcomment[$p_val['cmt_id']]);
					$finalArray[$p_key]['usr_image'] = $image_url;
					$where_creply = "cmt_post_id = ".$postedData['post_id']."  and cmt_ref = ".$p_val['cmt_id'];
					$Postrepcomment = $postcomModel->getcommentreplyCount($where_creply);
					$finalArray[$p_key]['reply_count'] = $Postrepcomment;
					
					//count like and dislike post
					$bad_like = "like_post_id = ".$postedData['post_id']." and like_cmt_id = ".$p_val['cmt_id']." and like_type = '2' ";
					$badcount = $likecommentModel->getcommentlikeCount($bad_like);
					$finalArray[$p_key]['badcount'] = $badcount;
					
					$good_like = "like_post_id = ".$postedData['post_id']." and like_cmt_id = ".$p_val['cmt_id']." and like_type = '1' ";
					$goodcount = $likecommentModel->getcommentlikeCount($good_like);
					$finalArray[$p_key]['goodcount'] = $goodcount;
					/*  if(array_search($p_val['cmt_id'], array_column($Postrefcomment, 'cmt_ref')) !== false) {
						$finalArray[$p_key]['reply'] = $Postrefcomment;
					} else {
						$finalArray[$p_key]['reply'] = array();
					} 	 */				
				}
				
				//echo "<pre>";print_r($finalArray);exit;
				$where_comment = "cmt_post_id = ".$postedData['post_id']."";
				$totalpostcomment = $postcommentModel->getpostcommentCount($where_comment);
				
		
				/* Pagination Login */
				$paginator = new GTL_Paginator();
				$paginator->setItemsPerPage($itemsPerPageReviews);			 
				$paginator->setItemsTotal($postcommentModel->getpostcommentCount($where));			  
				$paginator->setCurrentPage($currentPageReviews);
				
				 if ($finalArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $finalArray,
						'COUNT' => count($finalArray),
						'TOTALCOMMENT' => $totalpostcomment,
						
						//'TOTALCOMMENTREPLY' => $totalcommentreply
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No Post Comment present',
						'DATA' => Array(),
						'COUNT' => 0
					);
				}				 				 
			 }
			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No Post Comment present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

        //echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	/* All comment reply Action */
    public function commentreplylistAction() {
		
		$errorMessageArray = array();
		$orderField = 'cmt_id';
        $sort = 'ASC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
		$postModel = new Model_Webservice_Post(); // Load post Model
        $postcommentModel = new Model_Webservice_Postcomment(); // Load post Model
		$likecommentModel = new Model_Webservice_Commentlike(); // Load post like model
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
									
			if ($postedData['cmt_ref'] == '') {
                $errorMessageArray[] = 'Please enter comment post id.';
            }
			
			/** check user exist or not * */
			if ($postedData['cmt_ref'] != "") {
			   $postExist = $postcommentModel->fetchEntryBycmtrefId($postedData['cmt_ref']);				   				
				if (count($postExist) == 0) {
					$errorMessageArray[] = "comment doesn't exist.";
				}
			}
			
			if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
			 if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST COMMENT',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0,
					'TOTALCOMMENT' => '0'
                );
			 }else{
				
				$itemsPerPageReviews = $this->PAGING_PER_PAGE;
          
				if($postedData['pageno'] != ''){
					$currentPageReviews = $postedData['pageno'];
				}
				else{
					$currentPageReviews = 1;
				}
				//PAGING_PER_PAGE
				if ($this->_getParam('page') && $this->_getParam('page') != '') {
					$currentPageReviews = $this->_getParam('page');
				}

				if ($this->_getParam('field') && $this->_getParam('field') != '') {
					$orderField = $this->_getParam('field');
				}

				if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
					$sort = $this->_getParam('sort');
				}
				$offset = ($currentPageReviews - 1) * $itemsPerPageReviews;
				
				$where_ref = "cmt_ref != 0 AND cmt_ref = ".$postedData['cmt_ref'];
				$Postrefcomment = $postcommentModel->fetchcommentreply($where_ref, $itemsPerPageReviews, $offset, $orderField, $sort);
				
				$finalArray = array();
				$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
				foreach($Postrefcomment as $p_key => $p_val){
					//echo"<pre>";print_r($p_key);exit;
					
						$image_url = $p_val['usr_image'];
					
					$finalArray[$p_key] = $p_val;
					//$finalArray[$p_key]['reply'] = array_values($Postrefcomment[$p_val['cmt_id']]);
					$finalArray[$p_key]['usr_image'] = $image_url;
					/* $where_creply = "cmt_ref = ".$postedData['cmt_ref'];
					$Postrepcomment = $postcommentModel->getcommentreplyCount($where_creply);
					$finalArray[$p_key]['reply_count'] = $Postrepcomment; */
					
					//count like and dislike post
					$bad_like = "like_cmt_id = ".$p_val['cmt_id']." and like_type = '2' ";
					$badcount = $likecommentModel->getcommentlikeCount($bad_like);
					$finalArray[$p_key]['badcount'] = $badcount;
					
					$good_like = "like_cmt_id = ".$p_val['cmt_id']." and like_type = '1' ";
					$goodcount = $likecommentModel->getcommentlikeCount($good_like);
					$finalArray[$p_key]['goodcount'] = $goodcount;
					
					
				}
				
				
				$totalpost = $postcommentModel->getpostcommentreplyCount($where_ref);
				/* $totalcountpost = $postcommentModel->getposttotalCount(); */

				/* Pagination Login */
				$paginator = new GTL_Paginator();
				$paginator->setItemsPerPage($itemsPerPageReviews);			 
				$paginator->setItemsTotal($postcommentModel->getpostcommentreplyCount($where_ref));			  
				$paginator->setCurrentPage($currentPageReviews);
			
				 if ($finalArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $finalArray,
						'COUNT' => count($finalArray),
						'TOTALCOMMENT' => $totalpost
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No Comment Reply present',
						'DATA' => Array(),
						'COUNT' => 0
					);
				}				 				 
			 }
			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No Post Comment present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

       // echo json_encode($JSONARRAY);
	    echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	 public function userprofileAction() {
		
		$errorMessageArray = array();
		$orderField = 'usr_id';
        $sort = 'ASC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $userModel = new Model_Webservice_User(); // Load user Model
		$postModel = new Model_Webservice_Post(); // Load post Model
		$loginModel = new Model_Webservice_Login(); // Load login model
		$postcommentModel = new Model_Webservice_Postcomment();
		
		
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
			
			
			/** check user exist or not * */
			if ($postedData['usr_lgn_id'] != "") {
			   $userExist = $loginModel->fetchEntryById($postedData['usr_lgn_id']);				   				
				if (count($userExist) == 0) {
					$errorMessageArray[] = "User doesn't exist.";
				}
			}
			
			if(count($errorMessageArray) > 1){
				$errorMessageText = 'Please enter required details.';
			}else{
				$errorMessageText = $errorMessageArray[0];
			}
			
			if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0,
                );
			} else {
				
				if ($postedData['usr_lgn_id'] != ""){
				$id=$postedData['usr_lgn_id'];
				}
			
				$UserArray = $userModel->fetchuserprofilebyId($id);
				$gender=$UserArray['usr_gender'];
				$usriimage = $UserArray['usr_image'];
				$usrupdateimg = $UserArray['usr_update_img'];
				$usr_update_img=$this->config->BASE_URL ."public/upload/user/".$usrupdateimg;
				$male_image=$this->config->BASE_URL ."public/upload/user/man_avatar.png";
				$female_image=$this->config->BASE_URL ."public/upload/user/woman_avatar.png";
				if($usrupdateimg!=''){
					$UserArray['usr_image']=$usr_update_img;
				}else if($gender==1 && $usrupdateimg =='' && $usriimage==''){
					$UserArray['usr_image']=$male_image;
				}else if($gender==2 && $usrupdateimg =='' && $usriimage==''){
					$UserArray['usr_image']=$female_image;
				}else{
					$UserArray['usr_image']=$usriimage;
				}
				//echo $gender;exit;
				if($gender=='1')
				{
					$gender_type="Male";
				}else if($gender=='2'){
					$gender_type="Female";
				}else{
					$gender_type="";
				}
				$UserArray['usr_gender']=$gender_type;
				
				$wheregoodpost = "post_lgn_id = ".$id;
				$GoodPostCount = $postModel->getgoodpostCount($wheregoodpost);
				$BadPostCount = $postModel->getbadpostCount($wherebadpost);
				
				$UserArray['goodpost']=$GoodPostCount;
				$UserArray['badpost']=$BadPostCount;
			
				$wherecomment = "cmt_lgn_id = ".$id;
				$PostCommentCount=$postcommentModel->getpostcommentCount($wherecomment);
				$UserArray['comment']=$PostCommentCount;			
											
				 if ($UserArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $UserArray,
						//'goodpost' => $GoodPostCount,
						//'badpost' => $BadPostCount,
						//'TOTALPOST' => $totaluser
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No user present with this login id',
						'DATA' => Array(),
						'COUNT' => 0
					);
				}				 				 
			}			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No User Present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

        //echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	/* All Post Action */
    public function postdetailAction() {
		
		$errorMessageArray = array();
		$orderField = 'post_id';
        $sort = 'ASC';
	
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => '',
        );
        $postModel = new Model_Webservice_Post(); // Load post Model
		$postcommentModel = new Model_Webservice_Postcomment();
		
		 $likepostModel = new Model_Webservice_Postlike(); // Load post like model

        $LikepostForm = new Form_Services_LikeunlikepostForm(); // Load post like form
		
		//$PostArray = $postModel->fetchPost();
		
		if ($this->getRequest()->isPost()) {
            $postedData = $this->_request->getPost();
			
			$id=$postedData['post_id'];
					
			$PostArray = $postModel->fetchpostbyId($id);
			
			$postImage = $PostArray['post_image'];
			$postVideo = $PostArray['post_video'];
			$post_image=$this->config->BASE_URL ."public/upload/post/".$postImage;
			$post_video=$this->config->BASE_URL ."public/upload/post/".$postVideo;
			$post_share_url = $this->config->BASE_URL ."index/postdetail/post_id/".$PostArray['post_id'] ."/post_cat/" .$PostArray['post_cat_id'];
			$PostArray['post_image']=$post_image;
			$PostArray['post_video']=$post_video;
			$PostArray['post_share_link']=$post_share_url;
			$wherecomment = "cmt_post_id = ".$id;	
			$PostCommentCount=$postcommentModel->getpostcommentCount($wherecomment);
			$PostArray['commentcount']=$PostCommentCount;
			
			$wherelike = "like_post_id = ".$id." and like_type = 1 ";
				$totalpostlike = $likepostModel->getpostlikeCount($wherelike);
				
			$wheredislike = "like_post_id = ".$id." and like_type = 2 ";
				$totalpostdislike = $likepostModel->getpostlikeCount($wheredislike);
				
			$PostArray['post_good']=$totalpostlike;
			$PostArray['post_bad']=$totalpostdislike;
				
			if(count($errorMessageArray) > 1){
					$errorMessageText = 'Please enter required details.';
				}else{
					$errorMessageText = $errorMessageArray[0];
				}
			 if (count($errorMessageArray) > 0) {
                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'POST',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0,
					//'Commentcount'=>$PostCommentCount,
					//'TOTALPOST' => $totalpost
                );
			 }else{
				 if ($PostArray) {
					$JSONARRAY = Array(
						'STATUS' => 'SUCCESS',
						'MESSAGES' => '',
						'DATA' => $PostArray,
						
					);
					
				} else {
					$JSONARRAY = Array(
						'STATUS' => 'FAIL',
						'MESSAGES' => 'No Post present',
						'DATA' => Array(),
						
					);
				}				 				 
			 }
			
		} else {
            $JSONARRAY = Array(
                'STATUS' => 'FAIL',
                'MESSAGES' => 'No Post present',
                'DATA' => Array(),
                'COUNT' => 0
            );
        }

        //echo json_encode($JSONARRAY);
		 echo json_encode($JSONARRAY, JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	//post comment like/dislike count //
	
public function postcommentlikecountAction() 
{
	$JSONARRAY = Array(
		'STATUS' => 'FAIL',
		'TYPE' => 'LIKE/UNLIKE POST',
		'MESSAGES' => '',
	);
	$errorMessageArray = Array();
	$likeUnlikeCommentData = Array();
	$where = "lgn_type = 2";

	$postModel = new Model_Webservice_Post(); // Load post model
	$loginModel = new Model_Webservice_Login(); // Load login model
	$likecommentModel = new Model_Webservice_Commentlike(); // Load post like model

	$PostcommentlikecountForm = new Form_Services_LikeunlikecommentForm(); // Load post like form

        if ($this->getRequest()->isPost()) 
		{
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$PostcommentlikecountForm->isValid($postedData)) 
			{
                $errorMessage = $PostcommentlikecountForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }

                if (count($errorMessageArray) > 1) {
                    $errorMessageText = 'Please enter required details.';
                } else {
                    $errorMessageText = $errorMessageArray;
                }

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'TYPE' => 'LIKE/UNLIKE COMMENT',
                    'MESSAGES' => $errorMessageText,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } 
			else 
			{

                /** check user exist or not * */
                 if ($postedData['like_lgn_id'] != "") 
				 {
                   $userExist = $loginModel->fetchEntryById($postedData['like_lgn_id']);				   				
                    if (count($userExist) == 0) {
                        $errorMessageArray[] = "User doesn't exist.";
                    }
                 } 

                /** check post exist or not * */
                if ($postedData['like_post_id'] != "") 
				{
					$where_post = "post_lgn_type = 2";
                    $postExist = $postModel->fetchEntryBypostId($postedData['like_post_id'],$where_post);
                    if (count($postExist) == 0) {
                        $errorMessageArray[] = "Post doesn't exist.";
                    }
                }
			
				/** check Comment id exist or not * */
               /*  if ($postedData['like_cmt_id'] != "") 
				{
                    $commentExist = $postModel->fetchEntryById($postedData['like_cmt_id']);
                    if (count($commentExist) == 0) {
                        $errorMessageArray[] = "comment doesn't exist.";
                    }
                } */

                if (count($errorMessageArray) > 0) 
				{

                    if (count($errorMessageArray) > 1) 
					{
                        $errorMessage = 'Please enter required details';
                    } else 
					{
                        $errorMessage = $errorMessageArray[0];
                    }

                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'TYPE' => 'LIKE/UNLIKE COMMENT',
                        'MESSAGES' => $errorMessage,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                }
				else
				{
						if($postedData['like_type'] == 1)
						{
							$like_type = 'Good';
						} else {
							$like_type = 'Bad';
						}
						
						if($postExist['post_lgn_type'] == 1)
						{
							$like_lgn_type = 'Admin';
						} else {
							$like_lgn_type = 'User';
						}
						
						
						$resultArray = array(
						'like_lgn_id' => $postedData['like_lgn_id'],
                        'like_cmt_id' => $postedData['like_cmt_id'],
                        'like_post_id' => $postedData['like_post_id'],
                        'like_type' => $postedData['like_type'],
                        'like_lgn_type' => $like_lgn_type,
						//'good count' => $goodcount,
						//'bad count' => $badcount,
						
                    );
					
						
					$wherecommentlike = "like_cmt_id = ".$postedData['like_cmt_id']." and like_post_id = ".$postedData['like_post_id']." and like_lgn_id = ".$postedData['like_lgn_id']." ";
					$totallikecomment = $likecommentModel->fetchCommentLike($wherecommentlike);
				
					if(count($totallikecomment)>0)
					{
						
						$likePostExist=$totallikecomment['like_post_id'];
						$likeCmtExist=$totallikecomment['like_cmt_id'];
						$likeLgnExist=$totallikecomment['like_lgn_id'];
						$liketypeExist=$totallikecomment['like_type'];
						$likestatusExist=$totallikecomment['like_status'];
						/* save data the notification log table*/
						
								if($postedData['like_type']==$liketypeExist)
								{
									if($postedData['like_type']==1)
									{
										$errorMessageArray[]="already liked";
									}else if($postedData['like_type']==2)
									{
										$errorMessageArray[]="already disliked";
									}
								}
								
								if(count($errorMessageArray)>0)
								{
									if (count($errorMessageArray) > 1) {
										$errorMessage = 'Please enter required details';
									} 
									else 
									{
										$errorMessage = $errorMessageArray[0];
									}
									
									//count like and dislike post
									 $bad_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '2' ";
									$badcount = $likecommentModel->getcommentlikeCount($bad_like);
									$resultArray['badcount']=$badcount;
									
									$good_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '1' ";
									$goodcount = $likecommentModel->getcommentlikeCount($good_like);
									$resultArray['goodcount']=$goodcount; 
									// if comment is already like then message is changed
								
									$JSONARRAY = Array(
										'STATUS' => 'FAIL',
										'TYPE' => 'LIKE/UNLIKE COMMENT',
										'MESSAGES' => $errorMessage,
										'DATA' => $resultArray,
										//'COUNT' => 0
									);
									
									
								}
					
								else
								{
									$likeUnlikeCommentData['like_id'] = $totallikecomment['like_id'];				
									$likeUnlikeCommentData['like_lgn_id'] = $userExist['lgn_id'];
									$likeUnlikeCommentData['like_cmt_id'] = $postedData['like_cmt_id'];
									$likeUnlikeCommentData['like_post_id'] = $postExist['post_id'];					
									$likeUnlikeCommentData['like_type'] = $postedData['like_type'];
									$likeUnlikeCommentData['like_lgn_type'] = $postExist['post_lgn_type'];
									$likeUnlikeCommentData['like_status'] = 1;	
									$likeUnlikeCommentData['like_createddate'] = date('Y-m-d H:i:s');
									$likeUnlikeCommentData['like_updateddate'] = date('Y-m-d H:i:s');
												
									$likecommentModel->save($likeUnlikeCommentData, 'like_id');
									
									
									
									$resultArray = array(
										//'like_lgn_id' => $likeUnlikeCommentData['like_lgn_id'],
										'like_post_id' => $likeUnlikeCommentData['like_post_id'],
										'like_cmt_id' => $likeUnlikeCommentData['like_cmt_id'],
										'like_type' => $postedData['like_type'],
										'like_lgn_type' => $postExist['post_lgn_type'],
									);
									//count like and dislike post
									 $bad_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '2' ";
									$badcount = $likecommentModel->getcommentlikeCount($bad_like);
									$resultArray['badcount']=$badcount;
									
									$good_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '1' ";
									$goodcount = $likecommentModel->getcommentlikeCount($good_like);
									$resultArray['goodcount']=$goodcount; 
									

									$JSONARRAY = Array(
										'STATUS' => 'SUCCESS',
										'TYPE' => 'LIKE/UNLIKE COMMENT',
										'MESSAGES' => 'Like/Unlike comment success.',
										'DATA' => $resultArray,
									   
									);
								}
					}
					else
					/* If comment not exist than add it */
					{
					$likeUnlikeCommentData['like_cmt_id'] = $postedData['like_cmt_id'];
					$likeUnlikeCommentData['like_post_id'] = $postExist['post_id'];
					$likeUnlikeCommentData['like_lgn_id'] = $userExist['lgn_id'];
                    $likeUnlikeCommentData['like_type'] = $postedData['like_type'];
                    $likeUnlikeCommentData['like_lgn_type'] = $postExist['post_lgn_type'];
					$likeUnlikeCommentData['like_status'] = 1;		
					$likeUnlikeCommentData['like_createddate'] = date('Y-m-d H:i:s');
                    $likeUnlikeCommentData['like_updateddate'] = date('Y-m-d H:i:s');
                   			
						$likecommentModel->save($likeUnlikeCommentData, 'like_id');
						
							//count like and dislike post
							$bad_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '2' ";
							$badcount = $likecommentModel->getcommentlikeCount($bad_like);
							$resultArray['badcount']=$badcount;
							
							$good_like = "like_post_id = ".$postedData['like_post_id']." and like_cmt_id = ".$postedData['like_cmt_id']." and like_type = '1' ";
							$goodcount = $likecommentModel->getcommentlikeCount($good_like);
							$resultArray['goodcount']=$goodcount; 
							 $JSONARRAY = Array(
								'STATUS' => 'SUCCESS',
								'TYPE' => 'LIKE/UNLIKE COMMENT',
								'MESSAGES' => 'Like/Unlike Comment success.',
								'DATA' => $resultArray,
							
							);						
					}                  
                }
                echo json_encode($JSONARRAY);
                exit;
				}
        }
        echo json_encode($JSONARRAY);
        exit;
    }
	
	public function locationlistAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'Location List',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();

        $locationModel = new Model_Webservice_Location(); // Load Location model
		$locationArray = $locationModel->fetchLocation();
		
		$JSONARRAY = Array(
			'STATUS' => 'SUCCESS',
			'TYPE' => 'Location List',
			'MESSAGES' => 'Show Location List.',
			'DATA' => $locationArray,
			'COUNT' => count($locationArray)
		);
		
		echo json_encode($JSONARRAY);
		exit;
	}
	
	public function edituserprofileAction(){
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => Array(),
        );
        $errorMessageArray = Array();
        $userProfileData = Array();

        $loginModel = new Model_Webservice_Login(); // Load login model
        $userModel = new Model_Webservice_User(); // Load user model

        $userprofileForm = new Form_Services_EdituserprofileForm(); // Load user profile form

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$userprofileForm->isValid($postedData)) {
                $errorMessage = $userprofileForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }
				
				if (count($errorMessageArray) > 1) {
					$error_string = 'Please enter valid details.';
				} else {
					$error_string = $errorMessageArray[0];
				}

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'MESSAGES' => $error_string,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {

               /** check user exist or not * */
			if ($postedData['usr_lgn_id'] != "") {
			   $userExist = $loginModel->fetchEntryById($postedData['usr_lgn_id']);		   
				if (count($userExist) == 0) {
					$errorMessageArray[] = "User doesn't exist.";
				}
			}
                
                if (count($errorMessageArray) > 0) {
					
					if (count($errorMessageArray) > 1) {
						$error_string = 'Please enter valid details.';
					} else {
						$error_string = $errorMessageArray[0];
					}
					
                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'MESSAGES' => $error_string,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
					$upload = new Zend_File_Transfer_Adapter_Http();
						foreach ($upload->getFileInfo() as $fields => $asFileInfo) {

							if ($asFileInfo["name"] != "") {
                                $folder = $this->config->UPLOAD_PATH . "user/";
                                $upload->setDestination($folder);
                                $originalFilename = pathinfo($asFileInfo["name"]);
                                $fileName = preg_replace("/[^-_a-zA-Z0-9]+/i", "_", $originalFilename["filename"]) . "_" . time() . "." . $originalFilename["extension"];
                                $upload->addFilter("Rename", $fileName, "filename");
                                $upload->receive($fields);
                                $userProfileData["usr_update_img"] = $fileName;
                            }
                        }
					
					$usr_img_path=$this->config->BASE_URL ."public/upload/user/";
					$usr_exist_img=$userExist['usr_update_img'];
					if($postedData['usr_dob']==''){
						$usr_dob=$userExist['usr_dob'];
					}else{
						$usr_dob=$postedData['usr_dob'];
					}
					
					if($postedData['usr_gender']==''){
						$usr_gender=$userExist['usr_gender'];
					}else{
						$usr_gender=$postedData['usr_gender'];
					}
					
					if($usr_gender=='1'){
						$gender_type='Male';
					}else if($usr_gender=='2'){
						$gender_type='Female';
					}else{
						$gender_type='';
					}
					/* if($postedData["usr_update_img"]==''){
						$user_img=$usr_exist_img;
					}else {
						$user_img=$fileName;
					} */
					if($postedData["usr_location"]==''){
						$user_location=$userExist['usr_location'];
					}else {
						$user_location=$postedData['usr_location'];
					}
					
					if($postedData["usr_phone"]==''){
						$usr_phone=$userExist['usr_phone'];
					}else {
						$usr_phone=$postedData['usr_phone'];
					}
					
					$userProfileData['usr_lgn_id'] = $postedData['usr_lgn_id'];
					$userProfileData['usr_id'] = $userExist['usr_id'];
                    $userProfileData['usr_dob'] = $usr_dob;
					$userProfileData['usr_location'] = $user_location;
					$userProfileData['usr_phone'] = $usr_phone;
					$userProfileData['usr_gender'] = $usr_gender;
					
					$usr_id = $userModel->save($userProfileData, 'usr_id');
					
					$gender=$userExist['usr_gender'];
					$usriimage = $userExist['usr_image'];
					$usrupdateimg = $userExist['usr_update_img'];
					$usr_update_img=$this->config->BASE_URL ."public/upload/user/".$usrupdateimg;
					$male_image=$this->config->BASE_URL ."public/upload/user/man_avatar.png";
					$female_image=$this->config->BASE_URL ."public/upload/user/woman_avatar.png";
					$users_check_img=$postedData['usr_update_img'];					
					
					$img_path=$userProfileData["usr_update_img"];
					//echo $userProfileData["usr_update_img"];exit;
					if($usriimage!="" && $usrupdateimg!="" && $userProfileData["usr_update_img"]==""){
						$userss_img=$usr_img_path ."$usrupdateimg";
					}else if($usriimage!="" && $usrupdateimg=="" && $userProfileData["usr_update_img"]==""){
						$userss_img=$usriimage;
					}else if($usriimage=="" && $usrupdateimg=="" && $userProfileData["usr_update_img"]=="" && $usr_gender=='1'){
						$userss_img=$male_image;
					}else if($usriimage=="" && $usrupdateimg=="" && $userProfileData["usr_update_img"]=="" && $usr_gender=='2'){
						$userss_img=$female_image;
					}else{
						$userss_img=$usr_img_path ."$img_path";
					}
					$userprofileArray = Array
                    (
                    'usr_id' => $userExist['usr_id'],
                    'usr_lgn_id' => $postedData['usr_lgn_id'],
					'usr_dob'=> $usr_dob,
					'usr_location'=> $user_location,
					'usr_phone'=> $usr_phone,
					'usr_gender'=>$gender_type,
					'usr_update_img'=>$userss_img,
					//'usr_img'=>$userss_img,
                );
				
                    $JSONARRAY = Array(
                        'STATUS' => 'SUCCESS',
                        'MESSAGES' => 'User profile updated successfully',
                        'DATA' => $userprofileArray,                  
                    );
                }
                echo json_encode($JSONARRAY,JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
        echo json_encode($JSONARRAY,JSON_UNESCAPED_SLASHES);
        exit;
	}
	
	public function reporttypelistAction() {
        $JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'TYPE' => 'Report Type List',
            'MESSAGES' => '',
        );
        $errorMessageArray = Array();

        $reporttypeModel = new Model_Webservice_Reporttype(); // Load Location model
		$reporttypeArray = $reporttypeModel->fetchReporttype();
		
		$JSONARRAY = Array(
			'STATUS' => 'SUCCESS',
			'TYPE' => 'Report Type List',
			'MESSAGES' => 'Show Report Type List.',
			'DATA' => $reporttypeArray,
			'COUNT' => count($reporttypeArray)
		);
		
		echo json_encode($JSONARRAY);
		exit;
	}
	
	public function addreportabuseAction(){
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => Array(),
        );
        $errorMessageArray = Array();
        $reportRefuseData = Array();
		$reportRefuselogData = Array();
        $reportabuseModel = new Model_Webservice_Reportabuse(); // Load login model
        $userModel = new Model_Webservice_User(); // Load user model
		$loginModel = new Model_Webservice_Login(); // Load login model
		$postModel = new Model_Webservice_Post(); // Load post model
		$reportabuselogModel = new Model_Webservice_Reportabuselog(); // Load login model


        $reportabuseForm = new Form_Services_AddreportabuseForm(); // Load user profile form

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$reportabuseForm->isValid($postedData)) {
                $errorMessage = $reportabuseForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }
				
				if (count($errorMessageArray) > 1) {
					$error_string = 'Please enter valid details.';
				} else {
					$error_string = $errorMessageArray[0];
				}

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'MESSAGES' => $error_string,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {
			
				   /** check user exist or not * */
				if ($postedData['report_user_id'] != "") {
				   $userExist = $loginModel->checkexistuserId($postedData['report_user_id']);					   
					if (count($userExist) == 0) {
						$errorMessageArray[] = "User doesn't exist.";
					}
				}
				
				
				  /** check post exist or not * */
				if ($postedData['report_post_id'] != "") {
				   $postExist = $postModel->fetchEntryById($postedData['report_post_id']);				   
					if (count($postExist) == 0) {
						$errorMessageArray[] = "Post doesn't exist.";
					}
				}
                
                if (count($errorMessageArray) > 0) {
					
					if (count($errorMessageArray) > 1) {
						$error_string = 'Please Enter required details.';
					} else {
						$error_string = $errorMessageArray[0];
					}
					
                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'MESSAGES' => $error_string,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
					
					$reportRefuseData['report_type'] = $postedData['report_type'];
					$reportRefuseData['report_user_id'] = $postedData['report_user_id'];
					$reportRefuseData['report_post_id'] = $postedData['report_post_id'];
                    $reportRefuseData['report_comment'] = $postedData['report_comment'];
					$reportRefuseData['report_createddate'] = date('Y-m-d H:i:s');
					$reportRefuseData['report_status'] = 1;
					
					$report_abuse_id = $reportabuseModel->save($reportRefuseData, 'report_abuse_id');
					
					/* Save Data in Report Abuse Log tbl  */
					$reportRefuselogData['report_type'] = $postedData['report_type'];
					$reportRefuselogData['user_id'] = $postedData['report_user_id'];
					$reportRefuselogData['post_id'] = $postedData['report_post_id'];
                    $reportRefuselogData['comment'] = $postedData['report_comment'];
					$reportRefuselogData['createddate'] = date('Y-m-d H:i:s');
					
					$id = $reportabuselogModel->save($reportRefuselogData, 'id');
					
					/* Save Report Abuse Count in post tbl  */
					$abuse_count=$postExist['abuse_count'];
					$postData['abuse_count'] = $abuse_count + 1;	
					$postData['post_id'] = $postExist['post_id'];	
					$post_id = $postModel->save($postData, 'post_id');
						
					if($postedData['report_type']==1){
						$report_type='Illegal';
					}else{
						$report_type='Porn';
					}
					$reportabuseArray = Array
                    (
                    'report_type' => $report_type,
                    'report_user_id' => $postedData['report_user_id'],
					'report_post_id'=> $postedData['report_post_id'],
					'report_comment'=>$postedData['report_comment'],
					'abuse_count'=>$postData['abuse_count'],
                );
				
                    $JSONARRAY = Array(
                        'STATUS' => 'SUCCESS',
                        'MESSAGES' => 'Report Abuse Added successfully',
                        'DATA' => $reportabuseArray,                  
                    );
					
					
					/* * *send Mail to user with verification coe * */
				$metaDataModel = new Model_Metadata();
                $ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
                $ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
                $ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
                $ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];

                $ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
                $ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

                $ADMIN_CONTACT_EMAIL_DATA = $metaDataModel->fetchEntryByKey("CONTACT_EMAIL");
                $ADMIN_CONTACT_EMAIL = $ADMIN_CONTACT_EMAIL_DATA['mtd_value'];

                $ADMIN_CONTACT_TELEPHONE_DATA = $metaDataModel->fetchEntryByKey("CONTACT_TELEPHONE");
                $ADMIN_CONTACT_TELEPHONE = $ADMIN_CONTACT_TELEPHONE_DATA['mtd_value'];
				$postid=$postedData['report_post_id'];
				$userid=$postedData['report_user_id'];
                $logo_link = $this->view->BASE_URL . "public/img/";
				$viewpost_link=$this->view->BASE_URL . "/admin/post/view/post_id/" ."$postid"; 
				$view_user_link=$this->view->BASE_URL . '/admin/user/profile/usr_id/' . "$userid" ;
                $emailPar = array(
                    'fileName' => 'addreportabuse_admin.phtml',
                    'subjectName' => 'POST is been reported',
                    'FromEmailId' => $ADMIN_MAIL,
                    'FromName' => $ADMIN_FROM,
                    'toEmailId' => $ADMIN_TO_MAILID,
                    'toName' => $ADMIN_FROM,
                    'pageType' => 'default',
                    'assignFields' => array(
						'post_title' => $postExist['post_title'],
						'user_name' => $userExist['usr_name'],
						'viewpost_link' => $viewpost_link,
						'comment'=> $postedData['report_comment'],
                        'name' => $ADMIN_FROM,               
                        'logolink' => $logo_link,
						'sitelink' => $this->view->BASE_URL,
						'view_user_link'=>$view_user_link,             
                        'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
                        'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
                        'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
                    )
                );
											
				$result = $this->mailData($emailPar); 
				
                }
                echo json_encode($JSONARRAY);
                exit;
            }
        }
        echo json_encode($JSONARRAY);
        exit;
	}
	
	public function setusergcmdataAction(){
		//echo"here";exit;
		$JSONARRAY = Array(
            'STATUS' => 'FAIL',
            'MESSAGES' => Array(),
        );
        $errorMessageArray = Array();
        $usergcmData = Array();

        $loginModel = new Model_Webservice_Login(); // Load login model
        $userModel = new Model_Webservice_User(); // Load user model

        $usergcmdataForm = new Form_Services_SetusergcmdataForm(); // Load user profile form

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$usergcmdataForm->isValid($postedData)) {
                $errorMessage = $usergcmdataForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }
				
				if (count($errorMessageArray) > 1) {
					$error_string = 'Please enter valid details.';
				} else {
					$error_string = $errorMessageArray[0];
				}

                $JSONARRAY = Array(
                    'STATUS' => 'FAIL',
                    'MESSAGES' => $error_string,
                    'DATA' => Array(),
                    'COUNT' => 0
                );
            } else {

               /** check user exist or not * */
			if ($postedData['usr_id'] != "") {
			   $userExist = $loginModel->fetchEntryById($postedData['usr_id']);	
print_r($userExist);exit;			   
				if (count($userExist) == 0) {
					$errorMessageArray[] = "User doesn't exist.";
				}
			}
                
                if (count($errorMessageArray) > 0) {
					
					if (count($errorMessageArray) > 1) {
						$error_string = 'Please enter valid details.';
					} else {
						$error_string = $errorMessageArray[0];
					}
					
                    $JSONARRAY = Array(
                        'STATUS' => 'FAIL',
                        'MESSAGES' => $error_string,
                        'DATA' => Array(),
                        'COUNT' => 0
                    );
                } else {
					
					$usergcmData['usr_id'] = $postedData['usr_id'];
					$usergcmData['usr_gcm_number'] = $userExist['usr_gcm_number'];                 					
					$usr_id = $userModel->save($usergcmData, 'usr_id');				
					
					$userprofileArray = Array
                    (
                    'usr_id' => $userExist['usr_id'],
                    'usr_gcm_number' => $postedData['usr_gcm_number'],
					
                );
				
                    $JSONARRAY = Array(
                        'STATUS' => 'SUCCESS',
                        'MESSAGES' => 'User profile updated successfully',
                        'DATA' => $userprofileArray,                  
                    );
                }
                echo json_encode($JSONARRAY,JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
        echo json_encode($JSONARRAY,JSON_UNESCAPED_SLASHES);
        exit;
	}
	
					
}

?>
