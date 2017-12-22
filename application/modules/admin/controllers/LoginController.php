<?php

class Admin_LoginController extends GTL_Action {

    public function init() {
        parent::init();
        $this->_helper->layout()->setLayout('admin-login');
        $this->sessionName = Zend_Registry::get('sessionName');
        $auth = Zend_Auth::getInstance();
        $this->view->LoggedIn = $auth->hasIdentity();
        $defaultSession = new Zend_Session_Namespace($this->sessionName);
        $defaultSessionVal = $defaultSession->getIterator();
        if (isset($defaultSessionVal['storage']) && !empty($defaultSessionVal['storage'])) {
            $this->view->user = $defaultSessionVal['storage'];
            $this->view->loggedinUserId = $this->view->user->usr_id;
            $this->_helper->redirector('index', 'index', 'admin');
        }
        /* Initialize action controller here */
    }

    public function indexAction() {
		//Load login Form
        $loginForm = new Form_Admin_Login();
        $this->view->form = $loginForm;
		
		//Load Forgotpassword Form
        $frgtPwdForm = new Form_Admin_Forgotpassword();
        $this->view->frgtForm = $frgtPwdForm;

        $errorMessage = Array();

        if ($this->_request->isPost()) {
            $postedData = $this->_request->getPost();
			 //Check for Errors
            if (!$loginForm->isValid($postedData)) {
                $errorMessage = $loginForm->getMessages();
            } else {
                $auth = Zend_Auth::getInstance();
                $adapter = GTL_Common::getAuthAdapter($postedData, 'default');
                $result = $auth->authenticate($adapter);
                if ($result->isValid()) {
                    $auth->getStorage();
                    $objUser = $adapter->getResultRowObject();
                    $objUser->lgn_id = $objUser->admin_usr_id;
                    $auth->setStorage(new Zend_Auth_Storage_Session($this->sessionName));
                    $auth->getStorage()->write($objUser);
                    $this->_helper->redirector('index', 'index', 'admin');
                } else {
                    $loginForm->addErrorMessage("Username and Password do not match.");
                    $errorMessage['0'] = $loginForm->getErrorMessages();
                }
            }
        }
        $this->view->errorMessage = $errorMessage;
    }

    public function forgotpasswordAction() {
		//Load Forgotpassword Form
        $frgtPwdForm = new Form_Admin_Forgotpassword();
        $this->view->frgtForm = $frgtPwdForm;
		
        $errorMessage = Array();
        $successmessage = "";
		
        if ($this->_request->isPost()) {
            $postedData = $this->_request->getPost();
			//Check for Errors
            if (!$frgtPwdForm->isValid($postedData)) {
                $errorMessage = $frgtPwdForm->getMessages();
            } else {

                $model = new Model_Adminuser();
                $usrArray = $model->checkEmailForForgotPassword($postedData['admin_email']);

                if (empty($usrArray) || $usrArray['admin_usr_id'] == '') {
                    $jsonArray = array('msg' => 'fail');
                } else {
                    $actualPassword = GTL_Common::generatePassword(9, 2);
                    $md5_password = md5($actualPassword);

                    /* * *send Mail to user with verification coe * */
                    $metaDataModel = new Model_Metadata();
                    $ADMIN_MAIL_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_EMAIL");
                    $ADMIN_MAIL = $ADMIN_MAIL_DATA['mtd_value'];
                    $ADMIN_FROM_DATA = $metaDataModel->fetchEntryByKey("ADMIN_FROM_NAME");
                    $ADMIN_FROM = $ADMIN_FROM_DATA['mtd_value'];
                    
                    $ADMIN_COPYRIGHT_DATA = $metaDataModel->fetchEntryByKey("COPYRIGHT");
                    $ADMIN_COPYRIGHT = $ADMIN_COPYRIGHT_DATA['mtd_value'];

                    $logo_link = $this->view->BASE_URL . "public/admin/img/";
                    $emailParams = array(
                        'fileName' => 'forgot.phtml',
                        'subjectName' => 'Forgot Password Request',
                        'FromEmailId' => $ADMIN_MAIL,
                        'FromName' => $ADMIN_FROM,
                        'toEmailId' => $usrArray['admin_email'],
                        'toName' => $ADMIN_FROM,
                        'pageType' => 'admin',
                        'assignFields' => array(
                            'name' => $ADMIN_FROM,
                            'email' => $usrArray['admin_email'],
                            'password' => $actualPassword,
                            'logolink' => $logo_link,
                            'public_admin_path'=>$this->view->ADMIN_PATH,
                            'ADMIN_COPYRIGHT' => $ADMIN_COPYRIGHT,
                        )
                    );

                    $result = $this->mailData($emailParams);
                    $dataUpdate = array();
                    $dataUpdate['admin_usr_id'] = $usrArray['admin_usr_id'];
                    $dataUpdate['admin_password'] = $md5_password;
                    $model->save($dataUpdate, 'admin_usr_id');

                    $jsonArray = array('msg' => 'success');
                }
                echo json_encode($jsonArray);
                exit;
            }
        }

        $this->view->successmessage = $successmessage;
        $this->view->errorMessage = $errorMessage;
    }

}
?>