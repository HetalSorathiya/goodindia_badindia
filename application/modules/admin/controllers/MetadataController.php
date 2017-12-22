<?php

class Admin_MetadataController extends GTL_Action {

    public function init() {
        parent::init();
        /* Initialize action controller here */
        $this->list_sess_unset('controller_metadata');
        $this->session_search_key = 'controller_metadata';
        $this->session_search_name = 'search_label';
    }

    public function indexAction() {
        $this->_helper->redirector('list', 'metadata', 'admin');
    }

	public function formAction() {

        //Initialize
		//Load metadata Form
        $metadataForm = new Form_Admin_Metadata();
        $successmessage = '';
		$data = array();
        $errorMessageArray = Array();
        $this->view->mode = 'Add';

        //Load metadata Model
        $metaModel = new Model_Metadata();

        $data = $metaModel->fetchall_metadata();

        for ($i = 0; $i < count($data); $i++):
            $key = $data[$i]['mtd_key'];
            $value = $data[$i]['mtd_value'];
			$matadata[$key] = $value;
        endfor;
        
		//echo '<pre>'; print_r($matadata);exit;

        $this->view->form = $metadataForm->populate($matadata);

        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$metadataForm->isValid($postedData)) {
                $errorMessage = $metadataForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }
            } else {

                if (count($errorMessageArray) > 0) {
                    
                } else {

                    $matadata['ADMIN_FROM_EMAIL'] = $postedData['ADMIN_FROM_EMAIL'];
                    $matadata['ADMIN_FROM_NAME'] = $postedData['ADMIN_FROM_NAME'];
                    $matadata['ADMIN_SIGN'] = $postedData['ADMIN_SIGN'];
                    $matadata['ADMIN_REPLY'] = $postedData['ADMIN_REPLY'];
                    $matadata['PAGE_RECORD'] = $postedData['PAGE_RECORD'];
					$matadata['ADMIN_REPLY_NAME'] = $postedData['ADMIN_REPLY_NAME'];
					
                    $metaModel->save_update_data($matadata);
                    $successmessage = "Records updated successfully";
				}

            }
        }
        $this->view->errorMessageArray = $errorMessageArray;
        $this->view->succesMessage = $successmessage;
        $this->view->form = $metadataForm;
    }

}

?>