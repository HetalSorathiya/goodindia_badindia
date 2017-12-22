<?php
    class Admin_CmsController extends GTL_Action {
		
		public function init() {
            parent::init();
            /* Initialize action controller here */
            $this->list_sess_unset('controller_cms');
            $this->session_search_key = 'controller_cms';
            $this->session_search_name = 'search_label';
        }
		
        public function indexAction() {
            $this->_helper->redirector('list', 'cms','admin');
        }
		
		public function unsetsessionAction() {
			$pagename = $this->_getParam('pagename');
			unset($_SESSION['_LISTING_SESS']);
			$this->_helper->redirector($pagename, 'cms', 'admin');
		}
		
		//change status action
		public function changestatusAction() {
			
			//Initialize
			$successmessage = '';			
			$cmsData = Array();
			//get status in url
			$c_status = $this->_getParam("c_status");
			//get id in url		
			$c_id = $this->_getParam('c_id');
		   
			//Load cms Model
			$cmsModel = new Model_Cms();
			//Fetch cms Data
			$cmsalldata = $cmsModel->fetchEntryById($c_id);
			
			//Save cms Data
			$cmsData['c_id'] = $c_id;
			if($cmsalldata['c_status'] == 0){
				$cmsData['c_status'] = 1;
			} else {
				$cmsData['c_status'] = 0;
			}
			
			$c_id = $cmsModel->save($cmsData, 'c_id');
			
			$successmessage = 'Status updated successfully';
			$this->addSuccessMessage($successmessage);
			
			$this->_helper->redirector('list', 'cms', 'admin');
			
			$this->view->succesMessage = $successmessage;
			exit;
		}


        public function viewAction() {
            if ($this->_getParam('c_id') && $this->_getParam('c_id') != '') {
                $c_id = $this->_getParam('c_id');
            } else {
                $this->_helper->redirector('list', 'cms', 'admin');
            }
            $cmsModel = new Model_Cms();
            $cmsalldata = $cmsModel->fetchEntryById($c_id);
            $this->view->cmsalldata = $cmsalldata;
        }
		
        public function listAction() {
			
            $errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'c_id';
            $sort = 'asc';

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
                if (isset($postedData['multiaction']) && ($postedData['multiaction'] != '')) {
                    $action = trim($postedData ['multiaction']);
                    if (isset($postedData['multicheck']) && count($postedData['multicheck']) > 0) {
                        $ids = implode(',', $_POST['multicheck']);
                        $result = $this->deleteAction($_POST['multicheck']);
                    } else {
                        $errorMessage[] = ' Please select atleast one checkbox! ';
                    }
                }
                /*search code here*/
                if ($postedData['searchtype'] == '') {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
                if (isset($postedData['searchtype']) && ($postedData['searchtype'] != '')) {
                    $searchtype = $postedData['searchtype'];
                    $where = " c_id LIKE '%$searchtype%' OR c_title LIKE '%$searchtype%' OR c_createddate LIKE '%$searchtype%' OR c_status LIKE '%$searchtype%' OR c_description LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;			      
                }    
                
            } else {

                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
				$searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
				$where = " c_id LIKE '%$searchtype%' OR c_title LIKE '%$searchtype%' OR c_createddate LIKE '%$searchtype%' OR c_status LIKE '%$searchtype%' OR c_description LIKE '%$searchtype%' ";    
				$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
				$this->view->searchtype = $searchtype;
            }

            $cmsModel = new Model_Cms();
            $cmssData = $cmsModel->fetchCms($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($cmsModel->getCmsCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'cms', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->cmssData = $cmssData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $cmsModel->getStatusArray();
            $this->view->searchtype = $searchtype;
        }
		
		public function deleteAction($ids = Array()) {
            //Load cms Model 
            $cmsModel = new Model_Cms();
            if ($this->_getParam('c_id') && $this->_getParam('c_id') != '') {
                $c_id = $this->_getParam('c_id');
                $cmsModel->deleteCms($c_id);
                $this->addSuccessMessage('Record Successfully Deleted');
               $this->_helper->redirector('list', 'cms', 'admin');
            } else {
                $cmsModel->deleteCms($ids);
               $this->addSuccessMessage('Record Successfully Deleted');
                $this->_helper->redirector('list', 'cms', 'admin');
            }
        }
		
		public function formAction() {
			//Initialize
            $successmessage = '';
            $errorMessageArray = Array();
            $data = array();
            $cmsData = Array();
            $this->view->mode = 'Add';

            //Load cms Model
            $cmsModel = new Model_Cms();

            //Load cms Form
            $cmsForm = new Form_Admin_CmsForm();

            //Load the status array for the select box in form 
            $statusArray = $cmsModel->getStatusArray();
            $cmsForm->c_status->addMultiOptions($statusArray);

            //Check for the cms Parameter
            $cmsData['c_id'] = $this->_request->getParam('c_id');
            $c_id = (!empty($cmsData['c_id'])) ? $cmsData['c_id'] : null;
            if (!empty($c_id)) {
            //Set the Mode to Edit
            $this->view->mode = 'Edit';
            //Fetch data to be loaded in form 
            $data = $cmsModel->fetchEntryById($c_id);
            }
			
            //Populate form 
            $this->view->form = $cmsForm->populate($data);
            if ($this->getRequest()->isPost()) {
                //Check for Errors
                $postedData = $this->_request->getPost();
                if (!$cmsForm->isValid($postedData)) {
                    $errorMessage = $cmsForm->getMessages();
                    foreach ($errorMessage as $_err) {
                        foreach ($_err as $_val) {
                            $errorMessageArray[] = $_val;
                        }
                    }
                } else {
                                        
                    if (count($errorMessageArray) > 0) {

                    } else {
                                             
                        if ($this->view->mode == 'Add') {
                            $cmsData['c_createddate'] = date('Y-m-d H:i:s');
                        }
                        //cms save
                        $cmsData['c_id'] = $c_id;
                        $cmsData['c_title'] = $postedData['c_title'];
						$cmsData['c_description'] = $postedData['c_description'];
						$cmsData['c_updateddate'] = date('Y-m-d H:i:s');
						$cmsData['c_status'] = $postedData['c_status'];
					
                        $cmsModel->save($cmsData, 'c_id');
                        if ($this->view->mode == 'Add') {
                            $successmessage = 'Cms add successfully';
                        } else {
                            $successmessage = 'Cms updated successfully';
                        }
                        $this->addSuccessMessage($successmessage);
                        $this->_helper->redirector('list', 'cms', 'admin');
                    }
                }
            }
			
            $this->view->cmsid = $c_id;
            $this->view->errorMessageArray = $errorMessageArray;
            $this->view->succesMessage = $successmessage;
            $this->view->form = $cmsForm;
		}
    }
?>