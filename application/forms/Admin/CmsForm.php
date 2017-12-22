<?php class Form_Admin_CmsForm extends Form_Custom_General {
        public function init() {

		$c_id = $this->createElement("hidden", "c_id")
                     ->setRequired(FALSE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter id")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Id"));
                     $this->addElement($c_id);

		$c_title = $this->createElement("text", "c_title")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter title.")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Title"));
                     $this->addElement($c_title);

		$c_description = $this->createElement("textarea", "c_description")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter description.")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control wysihtml5","placeholder"=>"Description"));
                     $this->addElement($c_description);

		$c_status = $this->createElement("select", "c_status")
                     ->setRequired(TRUE)
                     ->addValidator("NotEmpty", true, array("messages" => array("isEmpty" => "Please enter status.")))
                     ->addFilters(array(
                         new Zend_Filter_StringTrim(),
                     ))
                     ->setAttribs(Array("class"=>"form-control","placeholder"=>"Status"));
                     $this->addElement($c_status);

		$submit = $this->createElement("submit", "Save")
                ->setAttribs(Array("title" => "Save changes", "class"=>"btn-glow primary"));
        $this->addElement($submit);

        $reset = $this->createElement("reset", "Reset")
                ->setAttribs(Array("title" => "Save changes and continue to edit", "class"=>"btn-glow primary"));
        $this->addElement($reset);   
	}
}
?>