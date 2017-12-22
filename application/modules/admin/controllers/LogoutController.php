<?php

class Admin_LogoutController extends GTL_Action {

    public function init() {
        parent::init();
    }

    public function indexAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'login', 'admin');
    }

}

?>
