<?php 
	class Form_Admin_CategoryForm extends Form_Custom_General {
		
        public function init() {

			$cat_id = $this->createElement("hidden", "cat_id")
						 ->setRequired(FALSE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Id"));
						 $this->addElement($cat_id);
						 
			/* $cat_sort = $this->createElement("text", "cat_sort")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter sort no.")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Sort No."));
						 $this->addElement($cat_sort); */

			$cat_name = $this->createElement("text", "cat_name")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter name")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Name"));
						 $this->addElement($cat_name);		

			$cat_status = $this->createElement("select", "cat_status")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter status")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
						 $this->addElement($cat_status);

			$submit = $this->createElement("submit", "Save")
					->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
			$this->addElement($submit);

			$reset = $this->createElement("reset", "Reset")
					->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
			$this->addElement($reset);   
		}
	}
?>