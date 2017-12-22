<?php

class Form_Services_AddpostForm extends Form_Custom_General {

    public function init() {
		
        $digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Rider login id should be only in digits.');		
        $post_cat_id = $this->createElement('text', 'post_cat_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Category id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($post_cat_id);
		
		$digitValidator = new Zend_Validate_Digits();
        $digitValidator->setMessage('Driver login id should be only in digits.');		
        $post_lgn_id = $this->createElement('text', 'post_lgn_id')
                ->setRequired(True)
                ->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Login id should not be blank.')))
                ->addValidator($digitValidator, true)
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                    new Zend_Filter_StringToLower()
                ))
                ->setAttribs(Array('class' => "form-control"));
        $this->addElement($post_lgn_id);
		
		$post_title = $this->createElement("text", "post_title")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter title")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($post_title);
		
		$post_desc = $this->createElement("text", "post_desc")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter description")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($post_desc);
		
		$post_location = $this->createElement("text", "post_location")
                ->setRequired(TRUE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter location")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($post_location);
		
		$post_lattitude = $this->createElement("text", "post_lattitude")
			 ->setRequired(FALSE) 
			 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter lattitude.")))
			 ->addFilters(array(
				 new Zend_Filter_StringTrim(),
			 ))
			 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Lattitude","readonly"=>"readonly"));
		$this->addElement($post_lattitude);
						 
		$post_longitude = $this->createElement("text", "post_longitude")
			 ->setRequired(FALSE)
			 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter longotude.")))
			 ->addFilters(array(
				 new Zend_Filter_StringTrim(),
			 ))
			 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Longitude","readonly"=>"readonly"));
		$this->addElement($post_longitude);

		$like_type = $this->createElement("text", "like_type")
                ->setRequired(FALSE)
                ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter like/unlike")))
                ->addFilters(array(
                    new Zend_Filter_StringTrim(),
                ))
                ->setAttribs(Array("class" => "span6"));
        $this->addElement($like_type);
				
    }

}

?>
