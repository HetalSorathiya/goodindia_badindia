<?php

class GTL_Action extends Zend_Controller_Action {

    public function init() {

        $this->initView();
        $this->config = Zend_Registry::get('config');
        $request = $this->getRequest();
        $controllerName = $request->getControllerName();
        $moduleName = $request->getModuleName();
        $action = $request->getActionName();
        $PAGING_PER_PAGE = $this->config->PAGING_PER_PAGE;

        $auth = Zend_Auth::getInstance();
        $storage = new Zend_Auth_Storage_Session(Zend_Registry::get('sessionName'));
        $auth->setStorage($storage);
        
        $this->view->user = $auth->getIdentity();
        $this->USER = $auth->getIdentity();
        /*if ($this->USER) {
            $PAGING_PER_PAGE = $this->USER->PAGING_PER_PAGE;
        }*/
        $this->view->SITE_NAME = $this->config->SITE_NAME;
        $this->view->IMAGE_PATH = $this->config->IMAGE_PATH;
        $this->view->BASE_URL = $this->config->BASE_URL;
        $this->view->FLIP_PATH = $this->config->FLIP_PATH;
		$this->view->ADMIN_PATH = $this->config->ADMIN_PATH;
        $this->view->JS_PATH = $this->config->JS_PATH;
        $this->view->CSS_PATH = $this->config->CSS_PATH;
		$this->view->ROOT_PATH = $this->config->ROOT_PATH;
		
        $this->view->PUBLIC_PATH = $this->config->PUBLIC_PATH;
        $this->view->MODULE_NAME = $moduleName;
        $this->view->CONTROLLER_NAME = $controllerName;
        $this->view->ACTION_NAME = $action;
        $this->view->request = $this->request = $this->getRequest();
        $this->PAGING_PER_PAGE = $PAGING_PER_PAGE;
		
		$this->view->DATE_FORMATE = $this->config->DATE_FORMATE;
		/* Front Path */
        $this->view->FRONT_JS_PATH = $this->config->FRONT_JS_PATH;
        $this->view->FRONT_CSS_PATH = $this->config->FRONT_CSS_PATH;
        $this->view->FRONT_IMAGE_PATH = $this->config->FRONT_IMAGE_PATH;
		
		//Load user Model
        $userModel = new Model_User();
        $userProfileData = $userModel->fetchUserprofileData();
        $this->view->userProfileData = $userProfileData;

		//SENDGRID DETAIL 

        $this->view->SENDGRID_USERNAME = $this->config->SENDGRID_USERNAME;
        $this->view->SENDGRID_PASSWORD = $this->config->SENDGRID_PASSWORD;
        $this->view->SENDGRID_URL = $this->config->SENDGRID_URL;
		
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	
	/* Generallize funtion of send mail */

    public function mailData($params) {

        $fileName = $params['fileName'];
        $subjectName = $params['subjectName'];
        $fromEmailId = $params['FromEmailId'];
        $fromName = $params['FromName'];
        $toEmailId = $params['toEmailId'];
        $toName = $params['toName'];
        $assignParamsFields = $params['assignFields'];

        $html = new Zend_View();

        if ($params['pageType'] == 'admin') {
            $html->setScriptPath(APPLICATION_PATH . '/modules/admin/views/scripts/mailtemplate/');
        } else {
            $html->setScriptPath(APPLICATION_PATH . '/modules/default/views/scripts/mailtemplate/');
        }


        foreach ($assignParamsFields as $key => $value) {
            $html->assign($key, $value);
        }

        $body = $html->render($fileName);

        
        try {

            $SENDGRID_USERNAME = $this->view->SENDGRID_USERNAME;
            $SENDGRID_PASSWORD = $this->view->SENDGRID_PASSWORD;
            $SENDGRID_URL = $this->view->SENDGRID_URL;

            $params = array(
                'api_user' => $SENDGRID_USERNAME,
                'api_key' => $SENDGRID_PASSWORD,
                'to' => $toEmailId,
                'subject' => $subjectName,
                'html' => $body,
                'text' => $body,
                'from' => $fromEmailId,
            );

            $request = $SENDGRID_URL . 'api/mail.send.json';
            $mailData = $this->send_grid_api($request, $params);

            return "success";
        } catch (Zend_Exception $e) {
            return "Due to technical problem, email has not been sent. Please try later or contact webmaster.";
        }
    }

    public function send_grid_api($request, $params) {
        $session = curl_init($request);
        curl_setopt($session, CURLOPT_POST, true);
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($session);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($session);
            exit;
        } else {
            //print_r($response);
        }
        curl_close($session);
    }

    /**
     * preDispatch method to check user authentication.
     * if user is not logged in , will dispaly login screen.
     * 
     * @return  void
     * 
     */
    public function preDispatch() {

        $request = $this->getRequest();
        $moduleName = $request->getModuleName();
        $controllerName = $request->getControllerName();
        $action = $request->getActionName();
        $this->config = Zend_Registry::get('config');

        if ('admin' == $moduleName) {

            $auth = Zend_Auth::getInstance();

            if (!$auth->hasIdentity() && $controllerName != 'login') {
                $this->_redirect('/admin/login');
            } else {
                return true;
            }
        }
    }

    /*
     * To display success message.
     */

    protected function addSuccessMessage($message) {
        #$this->_helper->_flashMessenger->addMessage(array('message' => $message, 'status' => 'successFlash'));
        if (is_array($message) && count($message) > 0) {
            foreach ($message as $val) {
                $this->_helper->_flashMessenger->addMessage(array('message' => $val, 'status' => 'successFlash'));
            }
        } else {
            $this->_helper->_flashMessenger->addMessage(array('message' => $message, 'status' => 'successFlash'));
        }
    }

    /*
     * To display warning message.
     */

    protected function addWarningMessage($message) {
        $this->_helper->_flashMessenger->addMessage(array('message' => $message, 'status' => 'warningFlash'));
    }

    /*
     * To display error message.
     */

    protected function addErrorMessage($message) {
        if (is_array($message) && count($message) > 0) {
            foreach ($message as $val) {
                $this->_helper->_flashMessenger->addMessage(array('message' => $val, 'status' => 'errorFlash'));
            }
        } else {
            $this->_helper->_flashMessenger->addMessage(array('message' => $message, 'status' => 'errorFlash'));
        }
    }
    
    //session unset for search
    public function list_sess_unset($controller_name) {
        if ($_SESSION) {
            if (array_key_exists('_LISTING_SESS', $_SESSION)) {
                if(isset($_SESSION['_LISTING_SESS'][$controller_name])){
                    $controllerSess = $_SESSION['_LISTING_SESS'][$controller_name];
                }else{
                    $controllerSess = "";
                }
                unset($_SESSION['_LISTING_SESS']);
                $_SESSION['_LISTING_SESS'][$controller_name] = $controllerSess;
                
            }
        }
    }

}

