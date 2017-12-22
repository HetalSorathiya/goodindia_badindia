<?php
    class Admin_AdminuserController extends GTL_Action {
		
        public function init() {
            parent::init();
			/* Initialize action controller here */
            $this->list_sess_unset('controller_adminuser');
            $this->session_search_key = 'controller_adminuser';
            $this->session_search_name = 'search_label';
        }
		
        public function indexAction() {
            $this->_helper->redirector('list', 'adminuser','admin');
        }
		
		public function viewAction() {
            if ($this->_getParam('admin_usr_id') && $this->_getParam('admin_usr_id') != '') {
                $admin_usr_id = $this->_getParam('admin_usr_id');
            } else {
                $this->_helper->redirector('list', 'user', 'admin');
            }
            $adminuserModel = new Model_Adminuser();
            $adminuseralldata = $adminuserModel->fetchEntryById($admin_usr_id);
            $this->view->adminuseralldata = $adminuseralldata;
        }
		
        public function listAction() {
			
            $errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'admin_usr_id';
            $sort = 'asc';

            /* Pagination Login */
            $itemsPerPageReviews = $this->PAGING_PER_PAGE;
            $currentPageReviews = 1;
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

            if ($this->_request->isPost()) {
                $postedData = $this->_request->getPost();
                //Multiple Delete Logic 
                if (isset($postedData['multiaction']) && ($postedData['multiaction'] != '')) {
                    $action = trim($postedData ['multiaction']);
                    if (isset($postedData['multicheck']) && count($postedData['multicheck']) > 0) {
                        $ids = implode(',', $_POST['multicheck']);
                        $result = $this->deleteAction($_POST['multicheck']);
                    } else {
                        $errorMessage[] = ' Please select atleast one checkbox! ';
                    }
                }
                /*search code here*/
                /*if ($postedData['searchtype'] == '') {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
                if (isset($postedData['searchtype']) && ($postedData['searchtype'] != '')) {
                    $searchtype = $postedData['searchtype'];
                    $where = " admin_firstname LIKE '%$searchtype%' OR admin_lastname LIKE '%$searchtype%' OR admin_email LIKE '%$searchtype%' OR admin_status LIKE '%$searchtype%' OR admin_phone LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;
			      
                } */   
                
            } else {

               /* if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) 
                    {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
                    $searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
                    $where = " admin_firstname LIKE '%$searchtype%' OR admin_lastname LIKE '%$searchtype%' OR admin_email LIKE '%$searchtype%' OR admin_status LIKE '%$searchtype%' OR admin_phone LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;*/
            }

            $adminuserModel = new Model_Adminuser();
            $adminuserData = $adminuserModel->fetchAdminuser($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($adminuserModel->getAdminuserCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'adminuser', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->adminuserData = $adminuserData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $adminuserModel->getStatusArray();
            $this->view->searchtype = $searchtype;
        }
		
		public function deleteAction($ids = Array()) {
            //Load adminuser Model 
            $adminuserModel = new Model_Adminuser();
            if ($this->_getParam('admin_usr_id') && $this->_getParam('admin_usr_id') != '') {
                $admin_usr_id = $this->_getParam('admin_usr_id');
                $adminuserModel->deleteAdminuser($admin_usr_id);
                $this->addSuccessMessage('Record Successfully Deleted.');
				$this->_helper->redirector('list', 'adminuser', 'admin');
            } else {
                $adminuserModel->deleteAdminuser($ids);
				$this->addSuccessMessage('Record Successfully Deleted.');
                $this->_helper->redirector('list', 'adminuser', 'admin');
            }
        }
		
		public function editprofileAction() {
			
			//Initialize       
			$successmessage = '';
			$errorMessageArray = Array();
			$data = array();
			$adminuserData = Array();
			$loggedinuserData = Array();

			//Load login Model
			$adminuserModel = new Model_Adminuser();

			//Load edit profile Form
			$editprofileForm = new Form_Admin_EditprofileForm();
			
			//get adminuser data from session			
			$admin_usr_id = $this->view->user->admin_usr_id;
			
			if (!empty($admin_usr_id)) {            
				$loggedinuserData = $adminuserModel->fetchLoggedinUserData($admin_usr_id);
			}			
			
			//Populate form 
			$this->view->form = $editprofileForm->populate($loggedinuserData);
			
			if ($this->getRequest()->isPost()) {
				$postedData = $this->_request->getPost();

				if (!$editprofileForm->isValid($postedData)) {

					$errorMessage = $editprofileForm->getMessages();
					foreach ($errorMessage as $_err) {
						foreach ($_err as $_val) {
							$errorMessageArray[] = $_val;
						}
					}
				} else {
					
					if ($postedData['admin_password'] != "") {

						if ($postedData['new_password'] == "") {
							$errorMessageArray[] = "New Password should not be blank.";
						}

						if ($postedData['repeat_password'] == "") {
							$errorMessageArray[] = "Repeat Password should not be blank.";
						}
					}


					if ($postedData['new_password'] != "") {
						if ($postedData['repeat_password'] == "") {
							$errorMessageArray[] = "Repeat Password should not be blank.";
						}
						if ($postedData['new_password'] != $postedData['repeat_password']) {
							$errorMessageArray[] = "Password and Repeat Password should not be blank.";
						}
					}                
					
					if ($postedData['repeat_password'] != "") {
						if ($postedData['new_password'] == "") {
							$errorMessageArray[] = "New Password should not be blank.";
						}
						
						if ($postedData['admin_password'] == "") {
							$errorMessageArray[] = "Old Password should not be blank.";
						}
					}

					if ($postedData['new_password'] != "" && $postedData['repeat_password'] != "") {

						if ($postedData['admin_password'] == "") {
							$errorMessageArray[] = "Old password should not be blank.";
						} else {
							if (md5($postedData['admin_password']) != $loggedinuserData['admin_password']) {
								$errorMessageArray[] = "Old Password doesn't match.";
							}
						}
					}

					if (count($errorMessageArray) > 0) {
						
					} else {					
						
						//update adminuser Data
						$adminuserData['admin_usr_id'] = $this->view->user->admin_usr_id;
						$adminuserData['admin_firstname'] = $postedData['admin_firstname'];
						$adminuserData['admin_lastname'] = $postedData['admin_lastname'];
						$adminuserData['admin_phone'] = $postedData['admin_phone'];
						$adminuserData['admin_email'] = $postedData['admin_email'];
						if ($postedData['new_password'] != "") {
							$adminuserData['admin_password'] = md5($postedData['new_password']);
						}
						$adminuserData['admin_type'] = $this->view->user->admin_type;
						$adminuserData['admin_updateddate'] = date('Y-m-d H:i:s');
						$adminuserData['admin_status'] = 1;
						
						$authData = Zend_Auth::getInstance()->getIdentity();
                        $authData->admin_firstname = $postedData['admin_firstname'];
						$authData->admin_lastname = $postedData['admin_lastname'];
						
						$admin_usr_id = $adminuserModel->save($adminuserData, 'admin_usr_id');
						$logo_link = $this->view->BASE_URL . "public/img/";
						$admin_profile_link=$this->view->BASE_URL . "admin/adminuser/editprofile";
						
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
						if($postedData['new_password']!=''){
							$new_password=$postedData['new_password'];
							$sub_name='Change Password';
						}
						else{
							$new_password='';
							$sub_name='Change Profile';
						}
						$emailPar = array(
							'fileName' => 'changepassword.phtml',
							'subjectName' => $sub_name,
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => 'hetal.citrusbug@gmail.com',
							'toName' => $ADMIN_FROM,
							'pageType' => 'default',
							'assignFields' => array(
							
								'name' => $ADMIN_FROM,
								'subject'=> $sub_name,
								'password' => $new_password,
								'admin_firstname' => $postedData['admin_firstname'],
								'logolink' => $logo_link,
								'sitelink' => $this->view->BASE_URL,
								'admin_profile_link' => $admin_profile_link,         
								'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
								'ADMIN_CONTACT_EMAIL' => $ADMIN_CONTACT_EMAIL,
								'ADMIN_CONTACT_TELEPHONE' => $ADMIN_CONTACT_TELEPHONE,
							)
						);
										
						//echo"<pre>";print_r($emailPar);exit;				
						$result = $this->mailData($emailPar); 
					
						
								
								if ($result == 'success') {						
									$successmessage = 'User profile updated successfully.';
									$this->addSuccessMessage($successmessage);
									$this->_helper->redirector('editprofile', 'adminuser', 'admin');
								} else {
									$errorMessageArray[] = $result;
								}
							}
						}
					}
					$this->view->admin_usr_id = $admin_usr_id;
					$this->view->errorMessageArray = $errorMessageArray;
					$this->view->succesMessage = $successmessage;
					$this->view->form = $editprofileForm;
					$this->view->loggedinuserData = $loggedinuserData;
		}
		
		public function formAction() {
			//Initialize
            $successmessage = '';
            $errorMessageArray = Array();
            $data = array();
            $adminData = Array();
            $this->view->mode = 'Add';

            //Load Admin User Model
            $adminuserModel = new Model_Adminuser();

            //Load category Form
            $adminuserForm = new Form_Admin_AdminuserForm();

            //Load the status array for the select box in form 
            $statusArray = $adminuserModel->getStatusArray();
            $adminuserForm->admin_status->addMultiOptions($statusArray);
			
            //Check for the category Parameter
            $adminData['admin_usr_id'] = $this->_request->getParam('admin_usr_id');
            $admin_usr_id = (!empty($adminData['admin_usr_id'])) ? $adminData['admin_usr_id'] : null;
			
            if (!empty($admin_usr_id)) {
				//Set the Mode to Edit
				$this->view->mode = 'Edit';
				//Fetch data to be loaded in form 
				$data = $adminuserModel->fetchEntryById($admin_usr_id);
				$adminuserForm->admin_email->setAttrib('readonly', 'readonly');
            }
			
            //Populate form 
            $this->view->form = $adminuserForm->populate($data);
			
            if ($this->getRequest()->isPost()) {
                //Check for Errors
                $postedData = $this->_request->getPost();
				
                if (!$adminuserForm->isValid($postedData)) {
                    $errorMessage = $adminuserForm->getMessages();
                    foreach ($errorMessage as $_err) {
                        foreach ($_err as $_val) {
                            $errorMessageArray[] = $_val;
                        }
                    }
                } else {
					
					$userExist = $adminuserModel->checkEmailExist($postedData['admin_email']);
					
					if ($this->view->mode == 'Add') {
						if ($userExist) {
							$errorMessageArray[] = 'Email id already exist.';
						}
					}
					
                    if (count($errorMessageArray) > 0) {
						
                    } else {
						                    
                        if ($this->view->mode == 'Add') {
                            $adminData['admin_createddate'] = date('Y-m-d H:i:s');
                        }
                        //category save
                        $adminData['admin_usr_id'] = $admin_usr_id;
                        $adminData['admin_firstname'] = $postedData['admin_firstname'];
						$adminData['admin_lastname'] = $postedData['admin_lastname'];
						$adminData['admin_phone'] = $postedData['admin_phone'];
						$adminData['admin_email'] = $postedData['admin_email'];
						$adminData['admin_password'] = md5($postedData['admin_password']);
						$adminData['admin_type'] = 1;
						$adminData['admin_status'] = $postedData['admin_status'];
						$adminData['admin_updateddate'] = date('Y-m-d H:i:s');
						
                        $adminuserModel->save($adminData, 'admin_usr_id');
						
                        /***send Mail to user with verification coe **/
						$metaDataModel = new Model_Metadata();
						$ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
						$ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
						//echo '<pre>'; print_r($ADMIN_MAIL_DATA); exit;
						$ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
						$ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
						
						$logo_link = $this->view->BASE_URL . "public/admin/img/";
						//$html->assign('logo_link', $this->view->BASE_URL . "public/img/");
						$emailParams = array(
							'fileName' => 'adminuser.phtml',
							'subjectName' => 'Admin User',
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => $postedData['admin_email'],
							'toName' => $postedData['admin_firstname']."".$postedData['admin_lastname'],
                            'pageType' => 'admin',
							'assignFields' => array(
								'name' => $postedData['admin_firstname']." ".$postedData['admin_lastname'],
								'admin_phone' => $postedData['admin_phone'],
								'admin_email' => $postedData['admin_email'],
								'admin_password' => $postedData['admin_password'],
								'logolink' => $logo_link,
								//'verify_link' => $this->view->BASE_URL . "register/verifyaccount/usr_id/" . base64_encode($usr_id),
							)
						);
						
						$change_emailParams = array(
							'fileName' => 'changepassword_admin.phtml',
							'subjectName' => 'Admin User Change Password',
							'FromEmailId' => $ADMIN_MAIL,
							'FromName' => $ADMIN_FROM,
							'toEmailId' => $postedData['admin_email'],
							'toName' => $postedData['admin_firstname']."".$postedData['admin_lastname'],
                            'pageType' => 'admin',
							'assignFields' => array(
								'name' => $postedData['admin_firstname']." ".$postedData['admin_lastname'],
								'admin_phone' => $postedData['admin_phone'],
								'admin_email' => $postedData['admin_email'],
								'admin_password' => $postedData['admin_password'],
								'logolink' => $logo_link,
								//'verify_link' => $this->view->BASE_URL . "register/verifyaccount/usr_id/" . base64_encode($usr_id),
							)
						);
						
						if($this->view->mode == 'Add'){
							$mailResponse = $this->mailData($emailParams);
						} 
						if($this->view->mode == 'Edit' && $postedData['admin_password'] != ''){
							$mailResponse = $this->mailData($change_emailParams);
						} 
						
						if ($mailResponse == 'success') {							
							$successmessage = 'Admin user add successfully.';
							$this->addSuccessMessage($successmessage);
							$this->_helper->redirector('list', 'adminuser', 'admin');
						} else {
							$errorMessageArray[] = $mailResponse;
						}
                    }
                }
            }
            $this->view->admin_usr_id = $admin_usr_id;
            $this->view->errorMessageArray = $errorMessageArray;
            $this->view->succesMessage = $successmessage;
            $this->view->form = $adminuserForm;
		}
		
	}
?>