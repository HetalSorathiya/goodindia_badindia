<?php 
	class Form_Admin_AdminuserForm extends Form_Custom_General {
		
        public function init() {

			$admin_usr_id = $this->createElement("hidden", "admin_usr_id")
						 ->setRequired(FALSE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Id"));
						 $this->addElement($admin_usr_id);

			$admin_firstname = $this->createElement("text", "admin_firstname")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter first name")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"First Name"));
						 $this->addElement($admin_firstname);

			$admin_lastname = $this->createElement("text", "admin_lastname")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter last name")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Last Name"));
						 $this->addElement($admin_lastname);
						 
			$admin_phone = $this->createElement("text", "admin_phone")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter phone no")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Phone No"));
						 $this->addElement($admin_phone);
						 
			$admin_email = $this->createElement('text', 'admin_email')
					->setRequired(TRUE)
					->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Email address should not be blank.')))
					->addValidator(new Zend_Validate_EmailAddress())
					->addFilters(array(
						new Zend_Filter_StringTrim(),
						new Zend_Filter_StringToLower()
					))
					->setAttribs(Array('class' => "form-control", "placeholder" => "E-mail address"));
			$admin_email->getValidator('emailAddress')->setMessage("Email address is not valid.", Zend_Validate_EmailAddress::INVALID_FORMAT);
			$this->addElement($admin_email);

			$admin_password = $this->createElement('password', 'admin_password')
					->setRequired(TRUE)
					->addFilters(array(
						new Zend_Filter_StringTrim(),
						new Zend_Filter_StringToLower()
					))
					->addValidator('NotEmpty', true, array('messages' => array('isEmpty' => 'Password should not be blank.')))
					->setAttribs(Array('class' => "form-control", "placeholder" => "Your password"));
			$this->addElement($admin_password);

			$admin_status = $this->createElement("select", "admin_status")
						 ->setRequired(TRUE)
						 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please select status")))
						 ->addFilters(array(
							 new Zend_Filter_StringTrim(),
						 ))
						 ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
						 $this->addElement($admin_status);

			$submit = $this->createElement("submit", "Save")
					->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
			$this->addElement($submit);

			$reset = $this->createElement("reset", "Reset")
					->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
			$this->addElement($reset);   
		}
	}
?>