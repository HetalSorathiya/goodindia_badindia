<?php
    class Admin_PostController extends GTL_Action {
		
		public function init() {
            parent::init();
            /* Initialize action controller here */
            $this->list_sess_unset('controller_post');
            $this->session_search_key = 'controller_post';
            $this->session_search_name = 'search_label';
        }
		
        public function indexAction() {
            $this->_helper->redirector('list', 'post','admin');
        }
		
		//unsetsession action
		public function unsetsessionAction() {
			$pagename = $this->_getParam('pagename');
			unset($_SESSION['_LISTING_SESS']);
			$this->_helper->redirector($pagename, 'post', 'admin');
		}
		
		//change status action
		public function changestatusAction() {
			
			//Initialize
			$successmessage = '';			
			$postalldata = Array();
			//get status in url
			$post_status = $this->_getParam("post_status");
			//get id in url		
			$post_id = $this->_getParam('post_id');
		   
			//Load post Model
			$postModel = new Model_Post();
			//Fetch post Data
			$postalldata = $postModel->fetchEntryById($post_id);
			
			//Save post Data
			$postData['post_id'] = $post_id;
			if($postalldata['post_status'] == 0){
				$postData['post_status'] = 1;
			} else {
				$postData['post_status'] = 0;
			}
			
			$post_id = $postModel->save($postData, 'post_id');
			
			$successmessage = 'Status updated successfully';
			$this->addSuccessMessage($successmessage);
			
			$this->_helper->redirector('list', 'post', 'admin');
			
			$this->view->succesMessage = $successmessage;
			exit;
		}

        public function viewAction() {
			
			//Initialize
			$errorMessage= Array();
            $successmessage = '';
            $where = '';
            $searchtype = '';
            $orderField = 'cmt_id';
            $sort = 'asc';
			
			//get id in url		
            if ($this->_getParam('post_id') && $this->_getParam('post_id') != '') {
                $post_id = $this->_getParam('post_id');
            } else {
                $this->_helper->redirector('list', 'post', 'admin');
            }
			
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
			
			// Load Post Model
            $postModel = new Model_Post();
			// Load Post Data
            $postalldata = $postModel->fetchEntryById($post_id);
			
			// Load Post Like Model
			$postlikeModel = new Model_Postlike();
			
			// Load Post Data
            $postalldata = $postModel->fetchEntryById($post_id);
			
			// Load Post Like Model
			$postlikeModel = new Model_Postlike();
			
			
			$postcomModel = new Model_Postcomment();	
			$wherecomment="cmt_post_id='$post_id' AND cmt_ref = 0";	
			$PostCommentArray=$postcomModel->fetchadminbpostComment($wherecomment);
			$i	 = 0;
			foreach($PostCommentArray as $key => $postcom){
				$finalArra[$i]['cmt_id'] = $postcom['cmt_id'];
				$finalArra[$i]['usr_name'] = $postcom['usr_name'];
				$finalArra[$i]['usr_image'] = $postcom['usr_image'];
				$finalArra[$i]['cmt_msg'] = $postcom['cmt_msg'];
				$finalArra[$i]['cmt_createddate'] = $postcom['cmt_createddate'];
				$finalArra[$i]['cmt_status'] = $postcom['cmt_status'];
				$finalArra[$i]['cmt_lgn_id'] = $postcom['cmt_lgn_id'];
				$where_creply = "cmt_post_id = ".$post_id."  and cmt_ref = ".$postcom['cmt_id'];
				$Postrepcomment = $postcomModel->getcommentreplyCount($where_creply);
				$finalArra[$i]['reply_count'] = $Postrepcomment;
				$i++;
				
				$commentreplyArray[$postcom['cmt_id']] = $postcomModel->fetchadminpostcommentreply("cmt_ref = ".$postcom['cmt_id']);
			}
			
			// Load Post likegood Data
			$goodCount = "like_post_id = '".$post_id."' And like_type = 1";
			$PostlikegoodCount = $postlikeModel->getpostlikeCount($goodCount);
			
			// Load Post likebad Data
			$badCount = "like_post_id = '".$post_id."' And like_type = 2";
			$PostlikebadCount = $postlikeModel->getpostlikeCount($badCount);
			
            $this->view->postalldata = $postalldata;
			$this->view->PostlikegoodCount = $PostlikegoodCount;
			$this->view->PostlikebadCount = $PostlikebadCount;
			$this->view->commentreplydata = $commentreplydata;
			$this->view->commentreplyArray=$commentreplyArray;
			$this->view->finalArra=$finalArra;

        }
		
		
        public function listAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';

			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
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
                if ($postedData['searchpost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['searchpost'] = '';
				}
			   
				if (isset($postedData['searchpost']) && $postedData['searchpost'] != '') {
					$searchpost = $postedData['searchpost'];
					$where .= " and(post_id LIKE '%$searchpost%' OR post_title LIKE '%$searchpost%' OR post_status LIKE '%$searchpost%'  OR post_createddate LIKE '%$searchpost%' OR post_location LIKE '%$searchpost%' OR usr_name LIKE '%$searchpost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['searchpost'] = $searchpost;
					$this->view->searchpost = $searchpost;
				}
				
				if (isset($postedData['category']) && $postedData['category'] != '') {
                $category = $postedData['category'];
                $where .= " AND post_cat_id = '" . $category . "'";

                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['category'] = $category;
                $this->view->category = $category;
            }
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['searchpost'] = '';
				 
				}
				$searchpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['searchpost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['category'];
					
				if (isset($searchpost) && $searchpost != '') {
			
					$searchpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['searchpost'];
					$where .= " and(post_id LIKE '%$searchpost%' OR post_title LIKE '%$searchpost%' OR post_status LIKE '%$searchpost%'  OR post_createddate LIKE '%$searchpost%' OR post_location LIKE '%$searchpost%' OR usr_name LIKE '%$searchpost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['searchpost'] = $searchpost;
					$this->view->searchpost = $searchpost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['category'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['category'] = $category;
						$this->view->category = $category;
				}
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($postModel->getpostCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'post', 'action' => 'list', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
			$this->view->categoryArray = $categoryArray;
        }
		public function approvepostlistAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1 and post_approve_status=1';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
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
               
				if ($postedData['approvepost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepost'] = '';
				}
			   
				if (isset($postedData['approvepost']) && $postedData['approvepost'] != '') {
					$approvepost = $postedData['approvepost'];
					$where .= " and(post_id LIKE '%$approvepost%' OR post_title LIKE '%$approvepost%' OR post_status LIKE '%$approvepost%'  OR post_createddate LIKE '%$approvepost%' OR post_location LIKE '%$approvepost%' OR usr_name LIKE '%$approvepost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepost'] = $approvepost;
					$this->view->approvepost = $approvepost;
				}
				
				if (isset($postedData['approvepostcat']) && $postedData['approvepostcat'] != '') {
                $category = $postedData['approvepostcat'];
                $where .= " AND post_cat_id = '" . $category . "'";

                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepostcat'] = $category;
                $this->view->category = $category;
            }
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['approvepost'] = '';
				 
				}
				$approvepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepostcat'];
					
				if (isset($approvepost) && $approvepost != '') {
			
					$approvepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepost'];
					$where .= " and(post_id LIKE '%$approvepost%' OR post_title LIKE '%$approvepost%' OR post_status LIKE '%$approvepost%'  OR post_createddate LIKE '%$approvepost%' OR post_location LIKE '%$approvepost%' OR usr_name LIKE '%$approvepost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepost'] = $approvepost;
					$this->view->approvepost = $approvepost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepostcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['approvepostcat'] = $category;
						$this->view->category = $category;
				}
				
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($postModel->getpostCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'post', 'action' => 'approvepostlist', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
            $this->view->searchtype = $searchtype;
			$this->view->categoryArray = $categoryArray;
        }
		
		public function rejectpostlistAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1 and post_approve_status=2';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
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
               
				if ($postedData['rejectpost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['rejectpost'] = '';
				}
			   
				if (isset($postedData['rejectpost']) && $postedData['rejectpost'] != '') {
					$rejectpost = $postedData['rejectpost'];
					$where .= " and(post_id LIKE '%$rejectpost%' OR post_title LIKE '%$rejectpost%' OR post_status LIKE '%$rejectpost%'  OR post_createddate LIKE '%$rejectpost%' OR post_location LIKE '%$rejectpost%' OR usr_name LIKE '%$rejectpost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['rejectpost'] = $rejectpost;
					$this->view->rejectpost = $rejectpost;
				}
				
				if (isset($postedData['pendingpostcat']) && $postedData['pendingpostcat'] != '') {
					$category = $postedData['pendingpostcat'];
					$where .= " AND post_cat_id = '" . $category . "'";

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'] = $category;
					$this->view->category = $category;
				}
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['rejectpost'] = '';
				 
				}
				$rejectpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['rejectpost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'];
					
				if (isset($rejectpost) && $rejectpost != '') {
			
					$rejectpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['rejectpost'];
					$where .= " and(post_id LIKE '%$rejectpost%' OR post_title LIKE '%$rejectpost%' OR post_status LIKE '%$rejectpost%'  OR post_createddate LIKE '%$rejectpost%' OR post_location LIKE '%$rejectpost%' OR usr_name LIKE '%$rejectpost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['rejectpost'] = $rejectpost;
					$this->view->rejectpost = $rejectpost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'] = $category;
						$this->view->category = $category;
				}
				
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($postModel->getpostCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'post', 'action' => 'rejectpostlist', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
            $this->view->searchtype = $searchtype;
			$this->view->categoryArray = $categoryArray;
        }
		public function pendingpostlistAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1 and post_approve_status=0';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
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
               
				if ($postedData['pendingpost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpost'] = '';
				}
			   
				if (isset($postedData['pendingpost']) && $postedData['pendingpost'] != '') {
					$pendingpost = $postedData['pendingpost'];
					$where .= " and(post_id LIKE '%$pendingpost%' OR post_title LIKE '%$pendingpost%' OR post_status LIKE '%$pendingpost%'  OR post_createddate LIKE '%$pendingpost%' OR post_location LIKE '%$pendingpost%' OR usr_name LIKE '%$pendingpost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpost'] = $pendingpost;
					$this->view->pendingpost = $pendingpost;
				}
				
				if (isset($postedData['pendingpostcat']) && $postedData['pendingpostcat'] != '') {
					$category = $postedData['pendingpostcat'];
					$where .= " AND post_cat_id = '" . $category . "'";

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'] = $category;
					$this->view->category = $category;
				}
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['pendingpost'] = '';
				 
				}
				$pendingpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'];
					
				if (isset($pendingpost) && $pendingpost != '') {
			
					$pendingpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpost'];
					$where .= " and(post_id LIKE '%$pendingpost%' OR post_title LIKE '%$pendingpost%' OR post_status LIKE '%$pendingpost%'  OR post_createddate LIKE '%$pendingpost%' OR post_location LIKE '%$pendingpost%' OR usr_name LIKE '%$pendingpost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpost'] = $pendingpost;
					$this->view->pendingpost = $pendingpost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['pendingpostcat'] = $category;
						$this->view->category = $category;
				}
				
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchPost($where, $itemsPerPageReviews, $offset, $orderField, $sort);
            $paginator = new GTL_Paginator();
            $paginator->setItemsPerPage($itemsPerPageReviews);
            $paginator->setItemsTotal($postModel->getpostCount($where));
            $paginator->setCurrentPage($currentPageReviews);
            $link = $this->view->url(array('controller' => 'post', 'action' => 'approvepostlist', 'page' => 'PAGENO'), 'default', false);
            $paginator->paginate($link);

            $this->view->paginator = $paginator;
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
            $this->view->searchtype = $searchtype;
			$this->view->categoryArray = $categoryArray;
        }
		public function likepostlistAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
			if ($this->_getParam('field') && $this->_getParam('field') != '') {
                $orderField = $this->_getParam('field');
            }

            if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
                $sort = $this->_getParam('sort');
            }
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
               
				if ($postedData['likepost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepost'] = '';
				}
			   
				if (isset($postedData['likepost']) && $postedData['likepost'] != '') {
					$likepost = $postedData['likepost'];
					$where .= " and(post_id LIKE '%$likepost%' OR post_title LIKE '%$likepost%' OR post_status LIKE '%$likepost%'  OR post_createddate LIKE '%$likepost%' OR post_location LIKE '%$likepost%' OR usr_name LIKE '%$likepost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepost'] = $likepost;
					$this->view->likepost = $likepost;
				}
				
				if (isset($postedData['likepostcat']) && $postedData['likepostcat'] != '') {
					$category = $postedData['likepostcat'];
					$where .= " AND post_cat_id = '" . $category . "'";

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepostcat'] = $category;
					$this->view->category = $category;
				}
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['likepost'] = '';
				 
				}
				$likepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepostcat'];
					
				if (isset($likepost) && $likepost != '') {
			
					$likepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepost'];
					$where .= " and(post_id LIKE '%$likepost%' OR post_title LIKE '%$likepost%' OR post_status LIKE '%$likepost%'  OR post_createddate LIKE '%$likepost%' OR post_location LIKE '%$likepost%' OR usr_name LIKE '%$likepost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepost'] = $likepost;
					$this->view->likepost = $likepost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepostcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['likepostcat'] = $category;
						$this->view->category = $category;
				}
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchalllikePost($where,20);
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
			$this->view->categoryArray=$categoryArray;
        }
		public function dislikepostlistAction() {
			
            //Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1';
            $searchtype = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
			if ($this->_getParam('field') && $this->_getParam('field') != '') {
                $orderField = $this->_getParam('field');
            }

            if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
                $sort = $this->_getParam('sort');
            }
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
               
				if ($postedData['dislikepost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepost'] = '';
				}
			   
				if (isset($postedData['dislikepost']) && $postedData['dislikepost'] != '') {
					$dislikepost = $postedData['dislikepost'];
					$where .= " and(post_id LIKE '%$dislikepost%' OR post_title LIKE '%$dislikepost%' OR post_status LIKE '%$dislikepost%'  OR post_createddate LIKE '%$dislikepost%' OR post_location LIKE '%$dislikepost%' OR usr_name LIKE '%$dislikepost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepost'] = $dislikepost;
					$this->view->dislikepost = $dislikepost;
				}
				
				if (isset($postedData['dislikepostcat']) && $postedData['dislikepostcat'] != '') {
					$category = $postedData['dislikepostcat'];
					$where .= " AND post_cat_id = '" . $category . "'";

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepostcat'] = $category;
					$this->view->category = $category;
				}
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['dislikepost'] = '';
				 
				}
				$dislikepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepostcat'];
					
				if (isset($dislikepost) && $dislikepost != '') {
			
					$dislikepost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepost'];
					$where .= " and(post_id LIKE '%$dislikepost%' OR post_title LIKE '%$dislikepost%' OR post_status LIKE '%$dislikepost%'  OR post_createddate LIKE '%$dislikepost%' OR post_location LIKE '%$dislikepost%' OR usr_name LIKE '%$dislikepost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepost'] = $dislikepost;
					$this->view->dislikepost = $dislikepost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepostcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['dislikepostcat'] = $category;
						$this->view->category = $category;
				}
            }

            $postModel = new Model_Post();
            $postsData = $postModel->fetchalldislikePost($where,20);
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
            $this->view->statusArray = $postModel->getStatusArray();
			$this->view->categoryArray=$categoryArray;
        }
		
		public function commentpostlistAction() {
			
			//Initialize
            $errorMessage= Array();
            $successmessage = '';
            $where = '1=1';
            $category = '';
            $orderField = 'post_id';
            $sort = 'desc';
			
			$categoryModel = new Model_Category(); // Load Category Model
			$categoryArray = $categoryModel->CategoryArray();
			
			if ($this->_getParam('field') && $this->_getParam('field') != '') {
                $orderField = $this->_getParam('field');
            }

            if ($this->_getParam('sort') && $this->_getParam('sort') != '') {
                $sort = $this->_getParam('sort');
            }
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
               
				if ($postedData['commentedpost'] == '') {
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentedpost'] = '';
				}
			   
				if (isset($postedData['commentedpost']) && $postedData['commentedpost'] != '') {
					$commentedpost = $postedData['commentedpost'];
					$where .= " and(post_id LIKE '%$commentedpost%' OR post_title LIKE '%$commentedpost%' OR post_status LIKE '%$commentedpost%'  OR post_createddate LIKE '%$commentedpost%' OR post_location LIKE '%$commentedpost%' OR usr_name LIKE '%$commentedpost%')";  

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentedpost'] = $commentedpost;
					$this->view->commentedpost = $commentedpost;
				}
				
				if (isset($postedData['commentcat']) && $postedData['commentcat'] != '') {
					$category = $postedData['commentcat'];
					$where .= " AND post_cat_id = '" . $category . "'";

					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentcat'] = $category;
					$this->view->category = $category;
				}
			
            } else {
                if (!isset($_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name])) {
                $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name] = '';
				 $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name][$this->session_search_name]['searchtype']['commentedpost'] = '';
				 
				}
				$commentedpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentedpost'];
				
				$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentcat'];
					
				if (isset($commentedpost) && $commentedpost != '') {
			
					$commentedpost = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentedpost'];
					$where .= " and(post_id LIKE '%$commentedpost%' OR post_title LIKE '%$commentedpost%' OR post_status LIKE '%$commentedpost%'  OR post_createddate LIKE '%$commentedpost%' OR post_location LIKE '%$commentedpost%' OR usr_name LIKE '%$commentedpost%' )"; 
					$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentedpost'] = $commentedpost;
					$this->view->commentedpost = $commentedpost;
				}

				if (isset($category) && $category != '') {
						$category = $_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentcat'];
						$where .= " and post_cat_id = '" . $category . "'";
						$_SESSION['_LISTING_SESS'][$this->session_search_key][$this->session_search_name]['searchtype']['commentcat'] = $category;
						$this->view->category = $category;
				}
            }

            $postcommentModel = new Model_Postcomment();
            $postsData = $postcommentModel->fetchallcommentPost($where,20);
            $this->view->postsData = $postsData;
            $this->view->errorMessage = $errorMessage;
            $this->view->successmessage = $successmessage;
			$this->view->categoryArray=$categoryArray;
        }
		public function deleteAction($ids = Array()) {
            //Load post Model 
            $postModel = new Model_Post();
            if ($this->_getParam('post_id') && $this->_getParam('post_id') != '') {
                $post_id = $this->_getParam('post_id');
				
				$postData = $postModel->fetchEntryById($post_id);

				$folder = $this->config->UPLOAD_PATH . "post/";
				
				$imagePath = $folder . $postData['post_image'];

				if (file_exists($imagePath)) {
					unlink($imagePath);
				}
				
                $postModel->deletePost($post_id);
                $this->addSuccessMessage('Record Successfully Deleted');
				$this->_helper->redirector('list', 'post', 'admin');
            } else {
				foreach ($ids as $val) {
					$where = "post_id = " . $val . "";
					$postData = $postModel->fetchPost($where);

					$folder = $this->config->UPLOAD_PATH . "post/";

					$targetPath = $folder . $postData[0]['post_image'];

					if (file_exists($targetPath)) {
						unlink($targetPath);
					}
					$postModel->deletePost($val);
				}
				
                //$postModel->postModel($ids);
				$this->addSuccessMessage('Record Successfully Deleted');
                $this->_helper->redirector('list', 'post', 'admin');
            }
        }
		
		public function formAction() {			
			//Initialize
            $successmessage = '';
            $errorMessageArray = Array();
            $data = array();
            $postData = Array();
			$post_type = "";
			$imageName = "";
            $this->view->mode = 'Add';
			$login_id = $this->view->user->admin_usr_id;

            //Load post Model
            $postModel = new Model_Post();
			
			//Load category Model
            $categoryModel = new Model_Category();

            //Load post Form
            $postForm = new Form_Admin_PostForm();

            //Load the status array for the select box in form 
            $statusArray = $postModel->getStatusArray();
            $postForm->post_status->addMultiOptions($statusArray);
			
			//Load the status array for the select box in form 
            $poststatusArray = $postModel->getPoststatusArray();
            $postForm->post_approve_status->addMultiOptions($poststatusArray);
			
			//Load the status array for the select box in form 
            $categoryArray = $categoryModel->CategoryArray();
            $postForm->post_cat_id->addMultiOptions($categoryArray);
			
            //Check for the post Parameter
            $postData['post_id'] = $this->_request->getParam('post_id');
            $post_id = (!empty($postData['post_id'])) ? $postData['post_id'] : null;
			
            if (!empty($post_id)) {
				//Set the Mode to Edit
				$this->view->mode = 'Edit';
				//Fetch data to be loaded in form 
				$data = $postModel->fetchEntryById($post_id);
				
				$imageName = $data['post_image'];
				$this->view->LATITUDE = $data['post_lattitude'];
				$this->view->LONGITUDE = $data['post_longitude'];
				$post_type = $data['post_type'];
				$post_approve_status = $data['post_approve_status'];
				$post_video = $data['post_video'];
				
            }
			
            //Populate form 
            $this->view->form = $postForm->populate($data);
			
            if ($this->getRequest()->isPost()) {
                //Check for Errors
                $postedData = $this->_request->getPost();
                if (!$postForm->isValid($postedData)) {
                    $errorMessage = $postForm->getMessages();
                    foreach ($errorMessage as $_err) {
                        foreach ($_err as $_val) {
                            $errorMessageArray[] = $_val;
                        }
                    }
                } else {
					
					/*if ($this->view->mode == 'Add') {
						if ($_FILES['post_image']['name'] == '') {
							$errorMessageArray[] = 'please enter image.';
						}
					}*/
					
					if ($postedData['post_type'] == '') {
						$errorMessageArray[] = 'please select post type.';
					}
					if ($postedData['post_approve_status'] == '') {
						$errorMessageArray[] = 'please check post status.';
					}
               		
					if ($_FILES['post_image']['name'] != '') {
						$gtlObj = new GTL_Common();
						$imageAllowedFormat = $gtlObj->allowedImageFormats();
						$responseText = $gtlObj->image_validate($imageAllowedFormat, 'post_image');
						if ($responseText == 'IMPROPER_FORMAT') {
							$errorMessageArray[] = 'please upload valid image.';
						}
					}
					
                    if (count($errorMessageArray) > 0) {

                    } else {
						
						//upload image
						$upload = new Zend_File_Transfer_Adapter_Http();
						foreach ($upload->getFileInfo() as $fields => $asFileInfo) {

							if ($asFileInfo["name"] != "") {
                                $folder = $this->config->UPLOAD_PATH . "post/";
                                $upload->setDestination($folder);
                                $originalFilename = pathinfo($asFileInfo["name"]);
                                $fileName = preg_replace("/[^-_a-zA-Z0-9]+/i", "_", $originalFilename["filename"]) . "_" . time() . "." . $originalFilename["extension"];
                                $upload->addFilter("Rename", $fileName, "filename");
                                $upload->receive($fields);
                                $postData["post_image"] = $fileName;
                            }
                        }
						
                        if ($this->view->mode == 'Add') {
                            $postData['post_createddate'] = date('Y-m-d H:i:s');
                        }
                        //post save
                        $postData['post_id'] = $post_id;
						$postData['post_cat_id'] = $postedData['post_cat_id'];
						$postData['post_lgn_id'] = $login_id;
                        $postData['post_title'] = $postedData['post_title'];
						$postData['post_desc'] = $postedData['post_desc'];
						$postData['post_type'] = $postedData['post_type'];
						$postData['post_lgn_type'] = 1;
						$postData['post_location'] = $postedData['post_location'];
						$postData['post_lattitude'] = $postedData['post_lattitude'];
						$postData['post_longitude'] = $postedData['post_longitude'];
						$postData['post_status'] = $postedData['post_status'];
						$postData['post_approve_status'] = $postedData['post_approve_status'];
						$postData['post_updateddate'] = date('Y-m-d H:i:s');
						
                        $postModel->save($postData, 'post_id');
						
                        if ($this->view->mode == 'Add') {
                            $successmessage = 'Post add successfully';
                        } else {
                            $successmessage = 'Post updated successfully';
                        }
                        $this->addSuccessMessage($successmessage);
                        $this->_helper->redirector('list', 'post', 'admin');
						
						
						/* for send notification */
						$post_name=$postExist['post_title'];
						$usr_name=$userExist['usr_name'];
						if($postedData['notif_status'] == 1){
							$message='Your post '."$post_name".' is liked by '."$usr_name".'';
							$like='Liked';
							$type='good';
						} else {
							$message='Your post '."$post_name".' is dis-liked by '."$usr_name".'';
							$like='Unliked';
							$type='bad';
						}
						
						
						$deviceid='c5YfoZAYEpk:APA91bFXaSbZKKjF0wiW14uE1cNjvwSQBqTDg1aCuggAqDjawktpGxkOK6X189VhfnqNXCpN8SFmrRXgrWfbzCKcZkpu-lsByiVSwsp3eI6SzTmAKBFI13JySttG7GnSg91bYOE7iKy1';
						/*Android notification start*/	
						if($deviceid != ""){
							$fields['notification'] = Array(
										'title' => $message,
										'body' => $message,
										'sound' => 1
										); 
				
							$fields['data'] = Array(
										'post_id' => $postedData['like_post_id'],
										'title' =>$like,
										'message' =>$message,
										'type'=>$type,
										//'body' => urlencode($message),
										
										
									);
							$fields['to'] = $deviceid;
								//print_r($fields);exit;	
							$gtlCommon = new GTL_Common();
							$notificationResp = $gtlCommon->sendPushNotification($fields);
							//print_r($notificationResp);					
							/*Android notification end*/ 
							
						}	

						/* Save data in Postlike Notification log tbl */
						$postcommentData['notf_post_id'] = $postedData['like_lgn_id'];
						$postcommentData['notf_post_status'] = $postedData['like_post_id'];
						$postcommentData['notf_createddate'] = date('Y-m-d H:i:s');						
						$postcommentData['notf_status'] = 1;
						$notif_id = $notificationlogModel->save($postcommentData, 'notif_id');
						
                    }
                }
            }
            $this->view->postid = $post_id;
			$this->view->imageName = $imageName;
            $this->view->errorMessageArray = $errorMessageArray;
            $this->view->succesMessage = $successmessage;
            $this->view->form = $postForm;
			$this->view->post_type = $post_type;
			$this->view->post_video=$post_video;
		}
		
		public function deletecommentAction($ids = Array()) {
			
            //Load user Model 
            $postcommentModel = new Model_Postcomment();
            if ($this->_getParam('cmt_id') && $this->_getParam('cmt_id') != '') {
                $cmt_id = $this->_getParam('cmt_id');
				//echo $cmt_id; exit;
                $postcommentModel->deletepostComment($cmt_id);
                $this->addSuccessMessage('Record Successfully Deleted');
               $this->_helper->redirector('list', 'post', 'admin');
            } 
			
        }
    }
?>