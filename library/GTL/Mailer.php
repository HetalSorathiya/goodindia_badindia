<?php

/**
 * GTL_Mailer class.
 * 
 * This class is used to send emails like plain text,HTML with attachments.
 * Set n number of mail recipients, eemail cc address and email bcc.
 * @author Vishnu Bhatvadekar <vishnub@gatewaytechnolabs.com>
 * @package GTL_Mailer
 * @subpackage GTL Library
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 */
class GTL_Mailer extends Zend_Mail {

    /**
     *
     * @var Zend_View
     */
    static $_defaultView;

    /**
     * current instance of our Zend_View
     * @var Zend_View
     */
    protected $_view;

    /**
     * Constructor for this class
     * 
     * @param string $chrset
     * @param array $mailParams
     * @param string $templCode
     * @param array $messageParams
     * @param array $attachmet
     * @param string $plainText
     */
    public function __construct($chrset = 'utf-8', array $mailParams, $templCode = null, array $messageParams = array(), array $attachmet = array(), $plainText = null) {
        // calling parent constructor
        parent::__construct($chrset);
        $this->_view = self::getDefaultView();
        // fetch from email address from site options
        GTL_Common::setConfigOptions();
        $mailFrom = GTL_Common::getConfigOption('MAILER_EMAIL_FROM');
        // fetch from name from site options
        $mailFromName = GTL_Common::getConfigOption('MAILER_EMAIL_FROM_NAME');
        // if from email and name is provided then over write the site option
        // email address value
        if ((isset($mailParams['from']) && !empty($mailParams['from']))
                && (isset($mailParams['fromName'])
                && !empty($mailParams['fromName']))) {
            $mailFrom = $mailParams['from'];
            $mailFromName = $mailParams['fromName'];
        }
        // set from email
        $this->setFrom($mailFrom, $mailFromName);
        // set message
        $this->_getReplacedMessage($templCode, $messageParams);
        // if plain text is given then set plain test
        if (!empty($plainText))
            $this->setBodyText($plainText);
        // to email add
        $this->_addToEmails($mailParams['to']);
        // add cc
        if (isset($mailParams['cc'])) {
            $this->_addCcEmails($mailParams['cc']);
        }
        // add bcc
        if (isset($mailParams['bcc'])) {
            $this->_addBccEmails($mailParams['bcc']);
        }
        // add attachments 
        $this->_addAttachments($attachmet);
        //$this->addHeader($name, $value);
    }

    /**
     * Method to replace and set email template values
     * 
     * @param string $templCode
     * @param array $mailParams
     * @throws Zend_Mail_Exception
     */
    private function _getReplacedMessage($templCode, array $messageParams = array()) {

        if (!empty($templCode)) {
            // Model template call for the email template	
            $modelEmailmessages = new Model_Emailmessage;
            $emailMsgData = $modelEmailmessages->fetchEntryByCode($templCode);
            if (!empty($emailMsgData)) {
                $this->setSubject($emailMsgData['msg_subject']);
                $data['gtlMaildata'] = '';
                $counter = 0;
                //if mail  template is there then do..
                // replacing the values			
                if (!empty($emailMsgData['msg_body'])) {
                    if (!empty($messageParams) && is_array($messageParams)) {
                        foreach ($messageParams as $mailKey => $mailValue) {
                            if ($counter == 0) {
                                $data['gtlMaildata'] = str_replace(
                                        "[" . trim($mailKey) . "]", $mailValue, $emailMsgData['msg_body']);
                            } else {
                                $data['gtlMaildata'] = str_replace(
                                        "[" . trim($mailKey) . "]", $mailValue, $data['gtlMaildata']);
                            }
                            $counter++;
                        }
                    } else {
                        // no replace value  and there is mail template
                        $data['gtlMaildata'] = $emailMsgData['msg_body'];
                    }
                } else {
                    // if no mail template is found for given code
                    throw new Zend_Mail_Exception(
                            'No mesaage body found for given code');
                }
                $modelMailTemplate = new Model_Emailtemplate();
                $mailtemplate = $modelMailTemplate->fetchEntryById($emailMsgData['msg_template']);
                $emaildatareplaced = str_replace('[CONTENT]', nl2br($data['gtlMaildata']), $mailtemplate['tmp_html']);
                //pr($emaildatareplaced);
                $this->setBodyHtml($emaildatareplaced);
            } else {
                throw new Zend_Mail_Exception(
                        'No template found for given code');
            }
        }
    }

    /**
     * Method to add one or more to emails
     * 
     * @param mixed (string|array) $toEmils
     * @throws Zend_Mail_Exception
     */
    private function _addToEmails($toEmils) {
        //if email is array and one ore more emails with email address and name
        if (is_array($toEmils) && !empty($toEmils)) {
            if (!isset($toEmils[0])) {
                $Emils[] = $toEmils;
            } else {
                $Emils = $toEmils;
            }

            foreach ($Emils as $evalue) {
                if (!empty($evalue['email'])) {
                    if (!empty($evalue['name'])) {
                        $this->addTo($evalue['email'], $evalue['name']);
                    } else {
                        $this->addTo($evalue['email']);
                    }
                } else {
                    throw new Zend_Mail_Exception('Emty cc email is given');
                }
            }
        } else if (!empty($toEmils)) {
            // single to with out name
            $this->addTo($toEmils);
        }
    }

    /**
     * Method to add one or more cc 
     * 
     * @param mixed (string | Array) $ccEmils
     * @throws Zend_Mail_Exception
     */
    private function _addCcEmails($ccEmils) {
        //if email is array and one ore more emails with email address and name
        if (is_array($ccEmils) && !empty($ccEmils)) {
            if (!isset($ccEmils[0])) {
                $Emils[0] = $ccEmils;
            } else {
                $Emils = $ccEmils;
            }
            foreach ($Emils as $evalue) {
                if (!empty($evalue['email'])) {
                    if (!empty($evalue['name'])) {
                        $this->addCc($evalue['email'], $evalue['name']);
                    } else {
                        $this->addCc($evalue['email']);
                    }
                } else {
                    throw new Zend_Mail_Exception('Emty cc email is given');
                }
            }
        } else if (!empty($ccEmils)) {
            // single cc with out name
            $this->addCc($ccEmils);
        }
    }

    /**
     * Method to add one or more bcc 
     * 
     * @param string $bccEmils
     * @throws Zend_Mail_Exception
     */
    private function _addBccEmails($bccEmils) {
        // setting up one or mmore bcc email address
        if (is_array($bccEmils) && !empty($bccEmils)) {
            $Emils = $bccEmils;
            foreach ($Emils as $evalue) {
                if (!empty($evalue)) {
                    $this->addBcc($evalue);
                } else {
                    throw new Zend_Mail_Exception('Emty bcc email is given');
                }
            }
        } else if (!empty($bccEmils)) {
            // single bcc
            $this->addBcc($bccEmils);
        }
    }

    /**
     * Method to add one or more attachments
     * 
     * @param array $attachments
     * @throws Zend_Mail_Exception
     */
    private function _addAttachments(array $attachments) {
        // adding attachments
        if (count($attachments) == 1) {
            $eattachments[0] = $attachments;
        } else {
            $eattachments = $attachments;
        }
        if (!empty($eattachments) && is_array($eattachments)) {
            foreach ($eattachments as $avalue) {
                if (!empty($avalue['filename'])) {
                    $file = $avalue['filename'];
                    $pathTofile = $avalue['filepath'];
                    $getfileCpath = $pathTofile . '/' . $file;
                    $attach = new Zend_Mime_Part(
                                    file_get_contents($getfileCpath));
                    $attach->filename = basename($file);
                    $attach->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
                    $attach->encoding = Zend_Mime::ENCODING_BASE64;
                    $this->addAttachment($attach);
                } else {
                    throw new Zend_Mail_Exception(
                            'No attachment file name is given');
                }
            }
        }
    }

    /**
     * Finally send email
     * 
     * @return Zend_Mail
     */
    public function sendEmail() {
        $registry = Zend_Registry::getInstance();
        $config = $registry->config;
        //pr($config->site_email_config,1);	
        if ($config->site_email_config->use_ssl) {
            $smtp_config = array('auth' => $registry->smtpAuth,
                'username' => $registry->smtpUserName,
                'password' => $registry->smtpPassword, 'ssl' => 'tls',
                'port' => 8025);
        } else {
            $smtp_config = array('auth' => $registry->smtpAuth,
                'username' => $registry->smtpUserName,
                'password' => $registry->smtpPassword);
        }

        $mailServer = $registry->smtpServer;
        $transport = new Zend_Mail_Transport_Smtp($mailServer, $smtp_config);
        //pr($transport,1);
        try {
            $send = $this->send($transport);
        } catch (Zend_Exception $e) {
            //pr($e->getMessage(),1);
            $registry->log
                    ->mvslog(
                            "EMAILEXP :: Zend_Mail_Protocol_Exception - {$e
                            ->getMessage()}", "LG217015", 2, 'Mailer clas');
        }
        return $send;
    }

    /**
     * sets default values
     * @return Zend_View
     */
    protected static function getDefaultView() {
        if (self::$_defaultView === null) {
            self::$_defaultView = new Zend_View();
            self::$_defaultView
                    ->setScriptPath(
                            APPLICATION_PATH
                            . '/modules/default/views/scripts/mails/');
        }
        return self::$_defaultView;
    }

    /**
     * Enter description here ...
     * @param unknown_type $property
     * @param unknown_type $value
     * @return GTL_Mailer
     */
    public function setViewParam($property, $value) {
        $this->_view->__set($property, $value);
        return $this;
    }

}

?>
