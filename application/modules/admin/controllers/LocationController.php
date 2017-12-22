<?php

class Admin_LocationController extends GTL_Action {

    public function init() {
        parent::init();
        /* Initialize action controller here */
        $this->list_sess_unset('controller_location');
        $this->session_search_key = 'controller_location';
        $this->session_search_name = 'search_label';
    }

    public function indexAction() {
        $this->_helper->redirector('list', 'location', 'admin');
    }

    public function unsetsessionAction() {
        $pagename = $this->_getParam('pagename');
        unset($_SESSION['_LISTING_SESS']);
        $this->_helper->redirector($pagename, 'location', 'admin');
    }

    public function viewAction() {
        if ($this->_getParam('ploc_id') && $this->_getParam('ploc_id') != '') {
            $ploc_id = $this->_getParam('ploc_id');
        } else {
            $this->_helper->redirector('list', 'location', 'admin');
        }
        $LocationModel = new Model_Location();
        $propertylocationalldata = $LocationModel->fetchEntryById($ploc_id);
        $this->view->propertylocationalldata = $propertylocationalldata;
    }

    

    public function deleteAction($ids = Array()) {
            //Load category Model 
            $locationModel = new Model_Location();
            if ($this->_getParam('loc_id') && $this->_getParam('loc_id') != '') {
                $loc_id = $this->_getParam('loc_id');
                $locationModel->deleteLocation($loc_id);
                $this->addSuccessMessage('Record Successfully Deleted');
               $this->_helper->redirector('list', 'location', 'admin');
            } else {
                $locationModel->deleteLocation($ids);
               $this->addSuccessMessage('Record Successfully Deleted');
                $this->_helper->redirector('list', 'location', 'admin');
            }
    }
		
    public function formAction() {
        //Initialize
        $successmessage = '';
        $errorMessageArray = Array();
        $data = array();
        $locationData = Array();
        $this->view->mode = 'Add';

        //Load propertylocation Model
        $LocationModel = new Model_Location();

        //Load propertylocation Form
        $LocationForm = new Form_Admin_LocationForm();

        //Load the status array for the select box in form 
        $statusArray = $LocationModel->getStatusArray();
        $LocationForm->loc_status->addMultiOptions($statusArray);

        //Check for the propertylocation Parameter
        $locationData['loc_id'] = $this->_request->getParam('loc_id');
        $loc_id = (!empty($locationData['loc_id'])) ? $locationData['loc_id'] : null;
        if (!empty($loc_id)) {
            //Set the Mode to Edit
            $this->view->mode = 'Edit';
            //Fetch data to be loaded in form 
            $data = $LocationModel->fetchEntryById($loc_id);
			
        }
        //Populate form 
        $this->view->form = $LocationForm->populate($data);
        if ($this->getRequest()->isPost()) {
            //Check for Errors
            $postedData = $this->_request->getPost();
            if (!$LocationForm->isValid($postedData)) {
                $errorMessage = $LocationForm->getMessages();
                foreach ($errorMessage as $_err) {
                    foreach ($_err as $_val) {
                        $errorMessageArray[] = $_val;
                    }
                }
            } else {


                if (count($errorMessageArray) > 0) {
                    
                } else {


                    if ($this->view->mode == 'Add') {
                        $locationData['loc_createddate'] = date('Y-m-d H:i:s');
                    }
                    //propertylocation save
                    $locationData['loc_id'] = $loc_id;
                    $locationData['loc_name'] = $postedData['loc_name'];
					$locationData['loc_latitude'] = $postedData['loc_latitude'];
					$locationData['loc_longitude'] = $postedData['loc_longitude'];
                    $locationData['loc_updateddate'] = date('Y-m-d H:i:s');
                    $locationData['loc_status'] = $postedData['loc_status'];


                    $LocationModel->save($locationData, 'loc_id');
                    if ($this->view->mode == 'Add') {
                        $successmessage = 'Location add successfully';
                    } else {
                        $successmessage = 'Location updated successfully';
                    }
                    $this->addSuccessMessage($successmessage);
                    $this->_helper->redirector('list', 'location', 'admin');
                }
            }
        }
        $this->view->propertylocationid = $loc_id;
        $this->view->errorMessageArray = $errorMessageArray;
        $this->view->succesMessage = $successmessage;
        $this->view->form = $LocationForm;
    }

    public function listAction() {
        $errorMessage = Array();
        $successmessage = '';
        $where = '';
        $searchtype = '';
        $orderField = 'loc_id';
        $sort = 'asc';

        //Load propertylocation Model
        $locationModel = new Model_Location();

        //Load propertylocation Form
        $locationForm = new Form_Admin_LocationForm();

        //Load the status array for the select box in form 
        $statusArray = $locationModel->getStatusArray();
        $locationForm->loc_status->addMultiOptions($statusArray);

        /* Pagination Login */
        $itemsPerPageReviews = $this->view->PAGING_PER_PAGE_RECORD;
        $currentPageReviews = 1;
        //PAGING_PER_PAGE
        if ($this->_getParam('page') && $this->_getParam('page') != '') {
            $currentPageReviews = $this->_getParam('page');
        }

        if ($this->_getParam('field') && $this->_getParam('field') != '') {
            $orderField = $this->_getParam('field');
        }

        if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
            $sort = $this->_getParam('sort');
        }
        $offset = ($currentPageReviews - 1) * $itemsPerPageReviews;

        if ($this->_request->isPost()) {
            $postedData = $this->_request->getPost();
            //Multiple Delete Logic
			if (array_key_exists('multiaction', $postedData)) {
				if (isset($postedData['multiaction']) && ($postedData['multiaction'] != '')) {
					$action = trim($postedData ['multiaction']);
					if (isset($postedData['multicheck']) && count($postedData['multicheck']) > 0) {
						$ids = implode(',', $_POST['multicheck']);
						$result = $this->deleteAction($_POST['multicheck']);
					} else {
						$errorMessage[] = ' Please select atleast one checkbox! ';
					}
				}
			}
            /* search code here */
            if ($postedData['searchtype'] == '') {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
            }
            if (isset($postedData['searchtype']) && ($postedData['searchtype'] != '')) {
                $searchtype = $postedData['searchtype'];
                $where = " loc_id LIKE '%$searchtype%'   OR loc_name LIKE '%$searchtype%'  OR loc_status LIKE '%$searchtype%' ";
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                $this->view->searchtype = $searchtype;
            }
        } else {

            if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
            }
            $searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
            $where = " loc_id LIKE '%$searchtype%'   OR loc_name LIKE '%$searchtype%'  OR loc_status LIKE '%$searchtype%' ";
            $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
            $this->view->searchtype = $searchtype;
        }

        $locationsData = $locationModel->fetchLocation($where, $itemsPerPageReviews, $offset, $orderField, $sort);
        $paginator = new GTL_Paginator();
        $paginator->setItemsPerPage($itemsPerPageReviews);
        $paginator->setItemsTotal($locationModel->getLocationCount($where));
        $paginator->setCurrentPage($currentPageReviews);
        $link = $this->view->url(array('controller' => 'location', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
        $paginator->paginate($link);

        $this->view->paginator = $paginator;
        $this->view->locationsData = $locationsData;
        $this->view->errorMessage = $errorMessage;
        $this->view->successmessage = $successmessage;
        $this->view->form = $locationForm;
        $this->view->searchtype = $searchtype;
    }

	//change status action
	 public function changestatusAction() {
		//Initialize
        $successmessage = '';
		
		$locationData = Array();
		//get status in url
		$loc_status = $this->_getParam("loc_status");
		//get id in url		
		$loc_id = $this->_getParam('loc_id');
       
        //Load propertylocation Model
        $locationModel = new Model_Location();
		//Fetch propertylocation Data
        $locationalldata = $locationModel->fetchEntryById($loc_id);
		
		//Save propertylocation Data
		$locationData['loc_id'] = $loc_id;
		if($locationalldata['loc_status'] == 0){
			$locationData['loc_status'] = 1;
		} else {
			$locationData['loc_status'] = 0;
		}
		
		$loc_id = $locationModel->save($locationData, 'loc_id');
		
		$successmessage = 'Status updated successfully';
        $this->addSuccessMessage($successmessage);
		
		$this->_helper->redirector('list', 'location', 'admin');
		
		$this->view->succesMessage = $successmessage;
		exit;
    }

}

?>