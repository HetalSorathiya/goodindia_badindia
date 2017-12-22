<?php

class Form_Admin_Login extends Form_Custom_General {

    public function init() {

        $email = $this->createElement('text', 'email')
                ->setRequired(TRUE)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Email address should not be blank.')))
                ->addValidator(new Zend_Validate_EmailAddress())
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "E-mail address"));
        $email->getValidator('emailAddress')->setMessage("Email address is not valid.", Zend_Validate_EmailAddress::INVALID_FORMAT);
        $this->addElement($email);

        $password = $this->createElement('password', 'password')
                ->setRequired(TRUE)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Password should not be blank.')))
                ->setAttribs(Array('class' => "form-control", "placeholder" => "Your password"));
        $this->addElement($password);
    }

}