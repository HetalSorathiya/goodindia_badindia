<?php

class GTL_Layoutloader extends Zend_Controller_plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $config = Zend_Controller_Front::getInstance()
                        ->getParam('bootstrap')->getOptions();
        $moduleName = $request->getModuleName();
        if (isset($config[$moduleName]['layout'])) {
            $layoutScript = $config[$moduleName]['layout'];
            $layoutPath = $config[$moduleName]['layoutPath'];
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::startMvc()->setLayoutPath($layoutPath);
            Zend_Layout::startMvc()->setLayout($layoutScript);
            $registry->LayoutPath = $layoutPath;
            $registry->LayoutScript = $layoutScript;
            $loader = new Zend_Loader_Autoloader_Resource(array(
                        'basePath' => APPLICATION_PATH,
                        'namespace' => 'Application',
                    ));
            $loader->addResourceType('model', 'models', 'Model');
            $loader->addResourceType('form', '/modules/' . $moduleName . '/forms', 'Form');
        }
    }

}