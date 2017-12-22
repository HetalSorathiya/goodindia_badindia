<?php

class Form_Admin_Metadata extends Form_Custom_General {

    public function init() {
		
        $ADMIN_FROM_EMAIL = $this->createElement('text', 'ADMIN_FROM_EMAIL')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter  site name')))
				->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Admin From Email"));
        $this->addElement($ADMIN_FROM_EMAIL);
		       
        $ADMIN_FROM_NAME = $this->createElement('text', 'ADMIN_FROM_NAME')
               # ->setRequired(TRUE)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
               # ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter site website')))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Admin From Name"));
        $this->addElement($ADMIN_FROM_NAME);
        
		$ADMIN_SIGN = $this->createElement('text', 'ADMIN_SIGN')
                ->setRequired(TRUE)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter site website')))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Admin Sign"));
        $this->addElement($ADMIN_SIGN);

		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Record per page should be only in digits.');
        $PAGE_RECORD= $this->createElement('text', 'PAGE_RECORD')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter paging per page')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim()
                ))
                ->setAttribs(Array("class"=>"form-control","placeholder"=> "Paging Per Page"));
        $this->addElement($PAGE_RECORD);
		
		$ADMIN_REPLY = $this->createElement('text', 'ADMIN_REPLY')
                ->setRequired(TRUE)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter admin from')))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Admin Reply"));
        $this->addElement($ADMIN_REPLY);
		
		$ADMIN_REPLY_NAME = $this->createElement('text', 'ADMIN_REPLY_NAME')
                ->setRequired(TRUE)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Please enter admin name')))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Admin Reply Name"));
        $this->addElement($ADMIN_REPLY_NAME);
		
		$submit = $this->createElement('submit', 'Save')
                ->setAttribs(Array('title' => "Save changes", 'class' => "btn-glow primary"));
        $this->addElement($submit);


        $reset = $this->createElement('reset', 'Reset')
                ->setAttribs(Array('title' => "Save changes and continue to edit", 'class' => "btn-glow primary"));
        $this->addElement($reset);
    }
}