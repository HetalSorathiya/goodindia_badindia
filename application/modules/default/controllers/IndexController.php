<?php

class IndexController extends GTL_Action {

    public function init() {
        parent::init();
        /* Initialize action controller here */
	// $this->_helper->redirector('index', 'index', 'admin');
    }

    public function indexAction() {
        
		$postModel = new Model_Post(); // Load post Model	
		$PostArray = $postModel->fetchfrontPost("",6);
							 
		$this->view->PostArray=$PostArray;
    }
	
	public function postdetailAction() {
		
		if ($this->_getParam('post_id') && $this->_getParam('post_id') != '') {
            $post_id = $this->_getParam('post_id');
        } else {
            $this->_helper->redirector('index', 'index', 'default');
        }
		if ($this->_getParam('post_cat') && $this->_getParam('post_cat') != '') {
            $post_cat = $this->_getParam('post_cat');
        } else {
            $this->_helper->redirector('index', 'index', 'default');
        }
		//echo $post_id;exit;
		$postModel = new Model_Post(); // Load post Model
		$postcomModel = new Model_Postcomment();
		$wherecat="post_id !='$post_id' AND post_cat_id='$post_cat' ";
			$PostArray = $postModel->fetchfrontPost($wherecat,3);
			$where="post_id='$post_id'";			
			$PostDetailArray = $postModel->fetchfrontPostdetail($where);
			$wherecomment="cmt_post_id='$post_id' AND cmt_ref = 0";	
			$PostCommentArray=$postcomModel->fetchfrontPostComment($wherecomment);
			$i	 = 0;
			foreach($PostCommentArray as $key => $postcom){
				$finalArra[$i]['cmt_id'] = $postcom['cmt_id'];
				$finalArra[$i]['usr_name'] = $postcom['usr_name'];
				$finalArra[$i]['usr_image'] = $postcom['usr_image'];
				$finalArra[$i]['cmt_msg'] = $postcom['cmt_msg'];
				$finalArra[$i]['cmt_createddate'] = $postcom['cmt_createddate'];
				$i++;
				
				$commentreplyArray[$postcom['cmt_id']] = $postcomModel->fetchcommentreply("cmt_ref = ".$postcom['cmt_id']);
							
			}
			//echo '<pre>';print_r($commentreplyArray);exit;				  
			$this->view->PostDetailArray=$PostDetailArray;
			$this->view->commentreplyArray=$commentreplyArray;
			$this->view->finalArra=$finalArra;
			$this->view->post_id=$post_id;
			$this->view->PostArray=$PostArray;
			$this->_helper->layout()->setLayout('layout-header'); 
	}
	
	public function activateAction() {
		
		$postModel  = new Model_Post();
		
		//get login id from url
        $post_id = base64_decode($this->_request->getParam('post_id'));	
		$post_id = (!empty($post_id)) ? $post_id : null;	
		//echo $post_id;exit;
		if($post_id == ''){
			$this->_helper->redirector('index', 'index', 'default');
		}
				
		$this->view->post_id=$post_id;	
	}
	
	public function approvepostAction() {
		
		$postModel  = new Model_Post();
		
		//get login id from url
        $post_id = base64_decode($this->_request->getParam('post_id'));	
		$post_id = (!empty($post_id)) ? $post_id : null;	
		//echo $post_id;exit;
		if($post_id == ''){
			$this->_helper->redirector('index', 'index', 'default');
		}
		
		$postData['post_id'] = $post_id;
		$postData['post_updateddate'] = date('Y-m-d H:i:s');
		$postData['post_approve_status'] = 1;
		$post_id = $postModel->save($postData, 'post_id');	
		$this->_helper->layout()->setLayout('layout-header'); 
	}
	
	public function rejectpostAction() {
		
		$postModel  = new Model_Post();
		
		//get login id from url
        $post_id = base64_decode($this->_request->getParam('post_id'));	
		$post_id = (!empty($post_id)) ? $post_id : null;	
		//echo $post_id;exit;
		if($post_id == ''){
			$this->_helper->redirector('index', 'index', 'default');
		}
		
		$postData['post_id'] = $post_id;
		$postData['post_updateddate'] = date('Y-m-d H:i:s');
		$postData['post_approve_status'] = 2;
		$post_id = $postModel->save($postData, 'post_id');
		$this->_helper->layout()->setLayout('layout-header'); 
	}
	
}



?>
