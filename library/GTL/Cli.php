<?php

/*
 * File library\GTL\Cli.php
 * @package Free_Racing_Poker
 */
/**
 * Class GTL_Cli
 *
 * GTL_Cli class for this application.
 * This class is used to manage system command requests.
 * @author 	   Gtl
 * @uses       Zend_Controller_Router_Abstract ,Zend_Controller_Router_Interface
 * @package    Free_Racing_Poker
 * @subpackage GTL_Library_Classes
 * @category   Zend
 */
require_once('Zend/Controller/Router/Interface.php');
require_once('Zend/Controller/Router/Abstract.php');

class GTL_Cli extends Zend_Controller_Router_Abstract implements
Zend_Controller_Router_Interface
{

    /**
     * GTL_Cli assemble
     *
     * This method Generates a URL path that can be used in URL creation,
     * redirection, etc.
     * voer writes Zend_Controller_Router_Interface::assemble();
     * @see Zend_Controller_Router_Interface::assemble()
     * @param array $userParams Options passed by a user used to override parameters
     * @param mixed $name The name of a Route to use
     * @param bool $reset Whether to reset to the route defaults ignoring URL params
     * @param bool $encode Tells to encode URL parts on output
     * @return string Resulting URL path
     */
    public function assemble($userParams, $name = null, $reset = false, $encode = true)
    {

    }

    /**
     * GTL_Cli route
     *
     * This method is used to route the reqiuest to cli controllers
     * @see Zend_Controller_Router_Interface::route()
     * @param Zend_Controller_Request_Abstract $dispatcher
     * @return void
     */
    public function route(Zend_Controller_Request_Abstract $dispatcher)
    {

    }

}
