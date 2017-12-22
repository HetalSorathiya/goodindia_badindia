<?php

class Form_Services_LikeunlikecommentForm extends Form_Custom_General {

    public function init() {
		
         $digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Rider login id should be only in digits.');		
        $like_lgn_id = $this->createElement('text', 'like_lgn_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Login id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($like_lgn_id); 
		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Driver login id should be only in digits.');		
        $like_cmt_id = $this->createElement('text', 'like_cmt_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Comment id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($like_cmt_id);

		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Driver login id should be only in digits.');		
        $like_post_id = $this->createElement('text', 'like_post_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Post id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($like_post_id);
		
		
		$like_type = $this->createElement("text", "like_type")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter like/unlike")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($like_type);
				
    }

}

?>
