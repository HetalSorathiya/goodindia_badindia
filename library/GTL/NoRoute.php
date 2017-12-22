<?php

/**
 * Noroute Page
 *
 * This is the page which will check the existance of requesting class / controller / action
 *
 * PHP versions 4 and 5
 *
 * @category Authentication
 * @package  Noroute
 * @author   Original Author GTL
 * @author   Another Author GTL
 * @license  license information
 * @link     link information
 */
// Session
require_once 'Zend/Session/Namespace.php';

class GTL_NoRoute extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();
        $controllerName = $request->getControllerName();

        if (empty($controllerName)) {
            $controllerName = $dispatcher->getDefaultController();
        }
        $className = $dispatcher->formatControllerName($controllerName);

        if ($className) {
            try {
                Zend_Loader::loadClass($className, $dispatcher->getControllerDirectory());
                $actionName = $request->getActionName();
                if (empty($actionName)) {
                    $actionName = $dispatcher->getDefaultAction();
                }

                $methodName = $dispatcher->formatActionName($actionName);
                $class = new ReflectionClass($className);
                $method_exist = $class->hasMethod($methodName) ? "yes" : "No";
                if ($method_exist == 'yes') {
                    return;
                } else {
                    $error = "Sorry, the requested action does not exist";
                }
            } catch (Zend_Exception $e) {
                $error = "Sorry, the requested action does not exist";
            }
        }
        $request->setControllerName('error');
        $request->setActionName('denied');
        Zend_Layout::getMvcInstance()->getView()->error = $error;
        $request->setDispatched();
    }

}