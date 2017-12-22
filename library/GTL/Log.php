<?php

/*
 * File library\GTL\Log.php
 * @package Mvs
 */

/**
 * Class Mvs_Log
 *
 * Mvs_Log class for this application.
 * This is used to manage all loging related activity.
 * @author      Gtl
 * @uses       Zend_Log
 * @package    Mvs
 * @subpackage Mvs_Library_Classes
 * @category   Zend
 */
class GTL_Log extends Zend_Log
{
    /**
     * Constant message limit
     * @var integer
     */

    const GTLLOG_MSG = 100;

    /**
     * Undefined method handler allows a shortcut:
     *   $log->priorityName('message')
     *     instead of
     *   $log->log('message', Zend_Log::PRIORITY_NAME)
     *
     * @param  string  $method  priority name
     * @param  string  $params  message to log
     * @return void
     * @throws Zend_Log_Exception
     */
    public function __call($method, $params)
    {
        parent::__call($method, $params);
    }

    /**
     * Class constructor.  Create a new logger
     *
     * @param Zend_Log_Writer_Abstract|null  $writer  default writer
     * @return void
     */
    public function __construct(Zend_Log_Writer_Abstract $writer = null)
    {
        parent::__construct();
        if ($writer !== null) {
            $this->addWriter($writer);
        }
    }

    /**
     * GTL_Log doLog
     *
     * This is used to do log .This function strores all loging activity
     * @param sting $message
     * @param sting $LogCode
     * @param integer $Initiator
     * @param integer $InitiatorId
     * @param string| mixed $extraParam
     */
    public function doLog($message, $LogCode, $Initiator = 1, $InitiatorId = '9999999999', $extraParam = null)
    {
        //$InitiatorId = str_pad($InitiatorId, 10, "0", STR_PAD_LEFT);
        $extras = array("initiator" => $Initiator, "initiatorid" => $InitiatorId, "logcode" => ltrim($LogCode, 'LG'));
        if (!empty($extraParam)) {
            if (is_array($extras)) {
                $extras = array_merge($extras, $extraParam);
            }
        }
        parent::setEventItem('log_time', date("Y-m-d H:i:s"));
        parent::setEventItem('log_type', $Initiator);
        parent::setEventItem('log_user', $InitiatorId);
        parent::setEventItem('log_code', ltrim($LogCode, 'LG'));
        parent::setEventItem('log_description', $message);
        $this->info($message);
    }

    /**
     * Log a message at a priority
     *
     * @param  string   $message   Message to log
     * @param  mixed    $extras    Extra information to log in event
     * @return void
     * @throws Zend_Log_Exception
     */
    public function err($message, $extras = null)
    {
        $backtrace = debug_backtrace(false);
        $trace = (isset($backtrace[1]['class']) ? $backtrace[1]['class'] : '') . '::' . (isset($backtrace[1]['function']) ? $backtrace[1]['function'] : '') . '@' . $backtrace[0]['line'] . ' ';
        parent::log($trace . $message, Zend_Log::ERR, $extras);
    }

}