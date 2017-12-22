<?php

class Form_Admin_Forgotpassword extends Form_Custom_General {

    public function init() {

        $admin_email = $this->createElement('text', 'admin_email')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Email address should not be blank.')))
                ->addValidator(new Zend_Validate_EmailAddress())
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control placeholder-no-fix none", "placeholder" => "Email"));
        $admin_email->getValidator('emailAddress')->setMessage("Email address is not valid.", Zend_Validate_EmailAddress::INVALID_FORMAT);
        $this->addElement($admin_email);
    }

}