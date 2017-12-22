<?php

class GTL_Controller_Layout extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $auth = Zend_Auth::getInstance();
        $storage = new Zend_Auth_Storage_Session(Zend_Registry::get('sessionName'));
        $auth->setStorage($storage);
        $user = $auth->getIdentity();
		$layout = Zend_Layout::getMvcInstance();
        if ($request->getModuleName() == 'admin') {
            //Check User is Logged In
            if ($user != null) {
                if ($layout->getMvcEnabled()) {
					$layout->setLayout('admin-page-layout');
                }
            }
        } else {
			if ($layout->getMvcEnabled()) {
				$layout->setLayout('layout');
			}
		}
		
    }

}

?>