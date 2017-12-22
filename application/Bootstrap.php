<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initConfig() {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initView() {

        // Initialize view
        $view = new Zend_View();
        // Initialize CONFIG
        $config = Zend_Registry::get('config');
        

        $view->doctype('XHTML1_STRICT');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );
        $viewRenderer->setView($view);
        $view->addHelperPath('GTL/Helper', 'GTL_Helper');

        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    // initialization of controllers
    public function _initControllers() {
        $config = Zend_Registry::get('config');
        Zend_Session::setOptions($config->sessionAdmin->toArray());
        $session = new Zend_Session_Namespace($config->sessionAdmin->name);
        $session->setExpirationSeconds($config->sessionAdmin->remember_me_seconds);
        Zend_Registry::set('sessionName', $config->sessionAdmin->name);

        //removing cli module from front.
        $front = Zend_Controller_Front::getInstance();
        $front->removeControllerDirectory('cli');
    }

    protected function _initAutoload() {
        // Add autoloader empty namespace
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        #$autoLoader->registerNamespace(array('GTL_', 'Photobook'));
        $autoLoader->registerNamespace(array('GTL_'));

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH,
            'namespace' => '',
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'forms/',
                    'namespace' => 'Form_',
                ),
                'model' => array(
                    'path' => 'models/',
                    'namespace' => 'Model_'
                )
            ),
        ));

        //removing cli module from front.
        $front = Zend_Controller_Front::getInstance();
        //$front->registerPlugin(new GTL_Controller_Plugin());
        $front->registerPlugin(new GTL_Controller_Layout());
        // Return it so that it can be stored by the bootstrap
        return $autoLoader;
    }

}
