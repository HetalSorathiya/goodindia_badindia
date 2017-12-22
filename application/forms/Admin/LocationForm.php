<?php class Form_Admin_LocationForm extends Form_Custom_General {
        public function init() {

		$loc_id = $this->createElement("hidden", "loc_id")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control none","placeholder"=>"Id"));
                     $this->addElement($loc_id);

		$loc_name = $this->createElement("text", "loc_name")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter name")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control none","placeholder"=>"Name"));
                     $this->addElement($loc_name);
					 
		$loc_latitude = $this->createElement("text", "loc_latitude")
				 ->setRequired(FALSE)
				 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter postcode.")))
				 ->addFilters(array(
					 new Zend_Filter_StringTrim(),
				 ))
				 ->setAttribs(Array("class"=>"form-control none","placeholder"=>"Latitude","readonly"=>"readonly"));
				 $this->addElement($loc_latitude);
		
		$loc_longiitude = $this->createElement("text", "loc_longitude")
				 ->setRequired(FALSE)
				 ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter postcode.")))
				 ->addFilters(array(
					 new Zend_Filter_StringTrim(),
				 ))
				 ->setAttribs(Array("class"=>"form-control none","placeholder"=>"Longitude","readonly"=>"readonly"));
				 $this->addElement($loc_longiitude);
				 
		$loc_status = $this->createElement("select", "loc_status")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter status")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control none","placeholder"=>"Status"));
                     $this->addElement($loc_status);

		$submit = $this->createElement("submit", "Save")
                ->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
        $this->addElement($submit);


        $reset = $this->createElement("reset", "Reset")
                ->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
        $this->addElement($reset);   }
        }?>