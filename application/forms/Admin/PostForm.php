<?php 
	class Form_Admin_PostForm extends Form_Custom_General {
		
        public function init() {

			$post_id = $this->createElement("hidden", "post_id")
						 ->setRequired(FALSE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Id"));
						 $this->addElement($post_id);
						 
			$post_cat_id = $this->createElement("select", "post_cat_id")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please select category.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Sort No."));
						 $this->addElement($post_cat_id);
						
			$post_lgn_id = $this->createElement("select", "post_lgn_id")
						 ->setRequired(FALSE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter sort no.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Sort No."));
						 $this->addElement($post_lgn_id);

			$post_title = $this->createElement("text", "post_title")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter title.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Title"));
						 $this->addElement($post_title);

			$post_desc = $this->createElement("textarea", "post_desc")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter description.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Description","rows"=>"5","cols"=>"2"));
						 $this->addElement($post_desc);
						 
			$post_location = $this->createElement("text", "post_location")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter location.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Location"));
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

			$post_status = $this->createElement("select", "post_status")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter status.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
						 $this->addElement($post_status);
						 
			$post_approve_status = $this->createElement("select", "post_approve_status")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please select post status.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
						 $this->addElement($post_approve_status);

			$submit = $this->createElement("submit", "Save")
					->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
			$this->addElement($submit);

			$reset = $this->createElement("reset", "Reset")
					->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
			$this->addElement($reset);   
		}
	}
?>