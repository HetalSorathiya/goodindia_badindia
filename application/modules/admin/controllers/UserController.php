<?php
    class Admin_UserController extends GTL_Action {
		
         public function init() {
            parent::init();
            /* Initialize action controller here */
            $this->list_sess_unset('controller_user');
            $this->session_search_key = 'controller_user';
            $this->session_search_name = 'search_label';
        }
		
        public function indexAction() {
            $this->_helper->redirector('list', 'user','admin');
        }
		
		//unsetsession action
		public function unsetsessionAction() {
			$pagename = $this->_getParam('pagename');
			unset($_SESSION['_LISTING_SESS']);
			$this->_helper->redirector($pagename, 'user', 'admin');
		}
		
		/**************Action for changing status in login and user table*********************/
		public function changestatusAction() {
			//Initialize
			$type = '';
			$usr_id = '';
			$userData = array();
			$loginData = array();
			
			$userModel = new Model_User(); //Load user model
			$loginModel = new Model_Login(); //Load login model
			//$restaurantModel = new Model_restaurant();//Load restaurant Model
								
			//Get type Parameter
			if ($this->_getParam('type') && $this->_getParam('type') != "") {
				$type = $this->_getParam('type');
			}
			if ($this->_getParam('usr_status') && $this->_getParam('usr_status') != "") {
				$usr_status = $this->_getParam('usr_status');
			}
			//Get user id Parameter
			if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != "") {
				$usr_id = $this->_getParam('usr_id');
				$USER = array('usr_id'=>$usr_id);
				$Redirector = $USER;			
			}
			
			if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != "") {
				$usr_id = $this->_getParam('usr_id');
				$USER = array('usr_id'=>$usr_id);
				$Redirector = $USER;			
			}
			//Get list id Parameter
			if ($this->_getParam('list_id') && $this->_getParam('list_id') != "") {
				$list_id = $this->_getParam('list_id');
				$LIST = array('list_id'=>$list_id);
				$Redirector = $LIST;			
			}
			
			/*Fetch Logindta*/
			$lgndata = $userModel->fetchEntryById($usr_id);
			
			#echo "<pre>";print_r($lgndata);exit;
			
			$userData['usr_id'] = $usr_id;
			$userData['usr_updateddate'] = date('Y-m-d H:i:s');
				
			$loginData['lgn_id'] = $lgndata['usr_lgn_id'];
			$loginData['lgn_updateddate'] = date('Y-m-d H:i:s');
			
			//Update status of user and login
			if($usr_status == "1"){				
				$userData['usr_status'] = 0;			
				$loginData['lgn_status'] = 0;
				
			} else {				
				$userData['usr_status'] = 1;
				$loginData['lgn_status'] = 1;
			}
						
			$userModel->save($userData, 'usr_id');
			$loginModel->save($loginData, 'lgn_id');
			
			$this->_helper->redirector('list', 'user', 'admin', $Redirector);
		}
  
        public function viewAction() {
			//Get user id Parameter
            if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != '') {
                $usr_id = $this->_getParam('usr_id');
            } else {
                $this->_helper->redirector('list', 'user', 'admin');
            }
			//Load user model
            $userModel = new Model_User();
			/*Fetch userdata*/
            $useralldata = $userModel->fetchEntryById($usr_id);
						
            $this->view->useralldata = $useralldata;
        }
				
        public function listAction() {
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'usr_id';
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
					$where = "usr_name LIKE '%$searchtype%'";
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
					$this->view->searchtype = $searchtype;
				}                
            } else {
					if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) 
                    {
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
					}
					$searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
					$where = " usr_name LIKE '%$searchtype%'";
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
					$this->view->searchtype = $searchtype;
				
            }
			//Load user model
            $userModel = new Model_User();
			/*Fetch userdata*/
            $usersData = $userModel->fetchUser($where, $itemsPerPageReviews, $offset, $orderField, $sort);
			
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($userModel->getUserCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'user', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->usersData = $usersData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $userModel->getStatusArray();
            $this->view->searchtype = $searchtype;
        }
		
		//User Profile Action	
		public function profileAction() {
			//Get user id Parameter
			if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != '') {
				$usr_id = $this->_getParam('usr_id');
			} else {
				$this->_helper->redirector('list', 'user', 'admin');
			}

			//Load user Model 
			$userModel = new Model_User();
			$useralldata = $userModel->fetchEntryById($usr_id);
			//echo '<pre>'; print_r($useralldata); exit;
			//Load user post Model 
			$postModel = new Model_Post();
			$where = "post_lgn_id = " . $useralldata['usr_lgn_id'] . " and post_status != '3' ";
			//Fetch user post Data 
			$userPostCountdata = $postModel->getpostCount($where);
			
			
			$this->view->usr_id = $usr_id;
			$this->view->useralldata = $useralldata;
			$this->view->userPostCountdata = $userPostCountdata;
		}
				
		//User Post List Action
		public function userpostAction() {
			//Get user id
			if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != '') {
				$usr_id = $this->_getParam('usr_id');
			} else {
				$this->_helper->redirector('list', 'user', 'admin');
			}
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';

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
                    $where = " post_id LIKE '%$searchtype%' OR post_title LIKE '%$searchtype%' OR post_status LIKE '%$searchtype%'  OR post_createddate LIKE '%$searchtype%' OR post_location LIKE '%$searchtype%' OR usr_name LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;			      
                }                   
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) 
                    {
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
                }
                    $searchtype = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name];
                    $where = " post_id LIKE '%$searchtype%' OR post_title LIKE '%$searchtype%' OR post_status LIKE '%$searchtype%'  OR post_createddate LIKE '%$searchtype%' OR post_location LIKE '%$searchtype%' OR usr_name LIKE '%$searchtype%' ";    
                    $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = $searchtype;
                    $this->view->searchtype = $searchtype;
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchPostByuserId($usr_id,$where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($postModel->getpostCountByuserId($usr_id,$where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'user', 'action' => 'userpost', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
            $this->view->searchtype = $searchtype;
		}
				
		public function deleteAction($ids = Array()) {
            //Load user Model 
            $userModel = new Model_User();
            if ($this->_getParam('usr_id') && $this->_getParam('usr_id') != '') {
                $usr_id = $this->_getParam('usr_id');
                $userModel->deleteUser($usr_id);
                $this->addSuccessMessage('Record Successfully Deleted');
               $this->_helper->redirector('list', 'user', 'admin');
            } else {
                $userModel->deleteUser($ids);
               $this->addSuccessMessage('Record Successfully Deleted');
                $this->_helper->redirector('list', 'user', 'admin');
            }
        }   
	}
?>