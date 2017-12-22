<?php

class Form_Services_AddreportabuseForm extends Form_Custom_General {

    public function init() {
		
		
		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('user id should be only in digits.');		
        $report_user_id = $this->createElement('text', 'report_user_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'user id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($report_user_id);
		
		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('post id should be only in digits.');		
        $report_post_id = $this->createElement('text', 'report_post_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'post id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($report_post_id);
		
		$report_type = $this->createElement("text", "report_type")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter report Type")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($report_type);
		
		$report_comment = $this->createElement("text", "report_comment")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter comment")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($report_comment);
				
    }

}

?>
