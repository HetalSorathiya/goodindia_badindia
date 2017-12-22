<?php class Form_Admin_UserForm extends Form_Custom_General {
        public function init() {

		$usr_id = $this->createElement("hidden", "usr_id")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"ID"));
                     $this->addElement($usr_id);

		$usr_name = $this->createElement("text", "usr_name")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter name")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Name"));
                     $this->addElement($usr_name);
		
		$lgn_email = $this->createElement("text", "lgn_email")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter email")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Email"));
                     $this->addElement($lgn_email);		

		$usr_lgn_type = $this->createElement("select", "usr_lgn_type")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter user type")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"User type"));
                     $this->addElement($usr_lgn_type);

		$usr_phone = $this->createElement("text", "usr_phone")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter phone")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Phone"));
                     $this->addElement($usr_phone);

		$usr_gender = $this->createElement("select", "usr_gender")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter gender")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Gender"));
                     $this->addElement($usr_gender);

		

		$usr_imei_number = $this->createElement("hidden", "usr_imei_number")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter imei")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Imei"));
                     $this->addElement($usr_imei_number);

		$usr_gcm_number = $this->createElement("hidden", "usr_gcm_number")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter gcm")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Gcm"));
                     $this->addElement($usr_gcm_number);

		$usr_status = $this->createElement("select", "usr_status")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter status")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
                     $this->addElement($usr_status);

		

		

		$usr_lgn_id = $this->createElement("hidden", "usr_lgn_id")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter login id")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Login id"));
                     $this->addElement($usr_lgn_id);

		

		$submit = $this->createElement("submit", "Save")
                ->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
        $this->addElement($submit);


        $reset = $this->createElement("reset", "Reset")
                ->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
        $this->addElement($reset);   }
        }?>