<?php

class Form_Services_EdituserprofileForm extends Form_Custom_General {

    public function init() {
		
        $digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Rider login id should be only in digits.');		
        $usr_lgn_id = $this->createElement('text', 'usr_lgn_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Login id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($usr_lgn_id);
		
		$usr_dob = $this->createElement("text", "usr_dob")
                ->setRequired(False)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter title")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($usr_dob);
		$usr_gender = $this->createElement("text", "usr_gender")
                ->setRequired(False)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter title")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($usr_gender);

				
    }

}

?>
