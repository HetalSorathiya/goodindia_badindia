<?php
    class Admin_CategoryController extends GTL_Action {
		
		public function init() {
            parent::init();
            /* Initialize action controller here */
            $this->list_sess_unset('controller_category');
            $this->session_search_key = 'controller_category';
            $this->session_search_name = 'search_label';
        }
		
        public function indexAction() {
            $this->_helper->redirector('list', 'category','admin');
        }
		
		//unsetsession action
		public function unsetsessionAction() {
			$pagename = $this->_getParam('pagename');
			unset($_SESSION['_LISTING_SESS']);
			$this->_helper->redirector($pagename, 'category', 'admin');
		}
		
		//change status action
		public function changestatusAction() {
			
			//Initialize
			$successmessage = '';
			
			$categoryalldata = Array();
			//get status in url
			$cat_status = $this->_getParam("cat_status");
			//get id in url		
			$cat_id = $this->_getParam('cat_id');
		   
			//Load category Model
			$categoryModel = new Model_Category();
			//Fetch category Data
			$categoryalldata = $categoryModel->fetchEntryById($cat_id);
			
			//Save category Data
			$categoryData['cat_id'] = $cat_id;
			if($categoryalldata['cat_status'] == 0){
				$categoryData['cat_status'] = 1;
			} else {
				$categoryData['cat_status'] = 0;
			}
			
			$cat_id = $categoryModel->save($categoryData, 'cat_id');
			
			$successmessage = 'Status updated successfully';
			$this->addSuccessMessage($successmessage);
			
			$this->_helper->redirector('list', 'category', 'admin');
			
			$this->view->succesMessage = $successmessage;
			exit;
		}


        public function viewAction() {
            if ($this->_getParam('cat_id') && $this->_getParam('cat_id') != '') {
                $cat_id = $this->_getParam('cat_id');
            } else {
                $this->_helper->redirector('list', 'category', 'admin');
            }
            $categoryModel = new Model_Category();
            $categoryalldata = $categoryModel->fetchEntryById($cat_id);
            $this->view->categoryalldata = $categoryalldata;
        }
		
        public function listAction() {
            $errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'cat_id';
            $sort = 'asc';


            /* Pagination Login */
            $itemsPerPageReviews = $this->PAGING_PER_PAGE;
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
                    $where = " cat_id LIKE '%$searchtype%' OR cat_name LIKE '%$searchtype%' OR cat_status LIKE '%$searchtype%'  OR cat_createddate LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;			      
                }                   
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) 
                    {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
                    $searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
                    $where = " cat_id LIKE '%$searchtype%' OR cat_name LIKE '%$searchtype%' OR cat_status LIKE '%$searchtype%'  OR cat_createddate LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;
            }
			//Load category Model
            $categoryModel = new Model_Category();
            $categorysData = $categoryModel->fetchCategory($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($categoryModel->getCategoryCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'category', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->categorysData = $categorysData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $categoryModel->getStatusArray();
            $this->view->searchtype = $searchtype;
        }
		
		public function deleteAction($ids = Array()) {
			 $errorMessage= Array();
            //Load category Model 
            $categoryModel = new Model_Category();
            if ($this->_getParam('cat_id') && $this->_getParam('cat_id') != '') {
                $cat_id = $this->_getParam('cat_id');
				
                $CategoryId=$categoryModel->checkCategoryExist($cat_id);
				if ($CategoryId) {
					 $this->adderrorMessage('Category can not delete.Category is exist in post.');
				}
				else{
					$categoryModel->deleteCategory($cat_id);
					$this->addSuccessMessage('Record Successfully Deleted');
				}
				$this->_helper->redirector('list', 'category', 'admin');
            } else {
                foreach($ids as $key=>$value){
					 $CategoryId=$categoryModel->checkCategoryExist($value);
					if ($CategoryId) {
					 $this->adderrorMessage('Category can not delete.Category is exist in post.');
					}
					else{
						$categoryModel->deleteCategory($ids);
						$this->addSuccessMessage('Record Successfully Deleted');
					}
				}
                $this->_helper->redirector('list', 'category', 'admin');
            }
			$this->view->errorMessage=$errorMessage;
        }
		
		
		public function formAction() {
			//Initialize
            $successmessage = '';
            $errorMessageArray = Array();
            $data = array();
            $categoryData = Array();
			$imageName = "";
            $this->view->mode = 'Add';

            //Load category Model
            $categoryModel = new Model_Category();

            //Load category Form
            $categoryForm = new Form_Admin_CategoryForm();

            //Load the status array for the select box in form 
            $statusArray = $categoryModel->getStatusArray();
            $categoryForm->cat_status->addMultiOptions($statusArray);
			
            //Check for the category Parameter
            $categoryData['cat_id'] = $this->_request->getParam('cat_id');
            $cat_id = (!empty($categoryData['cat_id'])) ? $categoryData['cat_id'] : null;
			
            if (!empty($cat_id)) {
				//Set the Mode to Edit
				$this->view->mode = 'Edit';
				//Fetch data to be loaded in form 
				$data = $categoryModel->fetchEntryById($cat_id);
				$imageName = $data['cat_image'];
            }
			
            //Populate form 
            $this->view->form = $categoryForm->populate($data);
			
            if ($this->getRequest()->isPost()) {
                //Check for Errors
                $postedData = $this->_request->getPost();
                if (!$categoryForm->isValid($postedData)) {
                    $errorMessage = $categoryForm->getMessages();
                    foreach ($errorMessage as $_err) {
                        foreach ($_err as $_val) {
                            $errorMessageArray[] = $_val;
                        }
                    }
                } else {
               					
                    if (count($errorMessageArray) > 0) {

                    } else {
						                    
                        if ($this->view->mode == 'Add') {
                            $categoryData['cat_createddate'] = date('Y-m-d H:i:s');
                        }
                        //category save
                        $categoryData['cat_id'] = $cat_id;
                        $categoryData['cat_name'] = $postedData['cat_name'];
						//$categoryData['cat_sort'] = $postedData['cat_sort'];
						$categoryData['cat_status'] = $postedData['cat_status'];
						$categoryData['cat_updateddate'] = date('Y-m-d H:i:s');
						
                        $categoryModel->save($categoryData, 'cat_id');
                        if ($this->view->mode == 'Add') {
                            $successmessage = 'Category add successfully';
                        } else {
                            $successmessage = 'Category updated successfully';
                        }
                        $this->addSuccessMessage($successmessage);
                        $this->_helper->redirector('list', 'category', 'admin');
                    }
                }
            }
            $this->view->categoryid = $cat_id;
            $this->view->errorMessageArray = $errorMessageArray;
            $this->view->succesMessage = $successmessage;
            $this->view->form = $categoryForm;
		}
    }
?>