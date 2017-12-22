<?php

class CmsController extends GTL_Action {

    public function init() {
        parent::init();
        /* Initialize action controller here */
    }

 
    public function disclaimerAction(){
        //Load Cms Model
		$cmsModel = new Model_Cms();
		$cmsData = $cmsModel->fetchEntryById(7);
		//print_r($cmsData);exit;
		$this->view->cmsData = $cmsData; 
		$this->_helper->layout()->setLayout('layout-header'); 
    }
	public function termsAction() {
		//Load Cms Model
		$cmsModel = new Model_Cms();
		$cmsData = $cmsModel->fetchEntryById(8);
		
		$this->view->cmsData = $cmsData;
		$this->_helper->layout()->setLayout('layout-header');
    }

}

?>