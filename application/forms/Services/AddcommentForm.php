<?php

class Form_Services_AddcommentForm extends Form_Custom_General {

    public function init() {
		
        $digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Rider login id should be only in digits.');		
        $cmt_lgn_id = $this->createElement('text', 'cmt_lgn_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Login id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($cmt_lgn_id);
		
		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Driver login id should be only in digits.');		
        $cmt_post_id = $this->createElement('text', 'cmt_post_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Post id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($cmt_post_id);

		$cmt_msg = $this->createElement("text", "cmt_msg")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter comment message.")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($cmt_msg);
				
    }

}

?>
