<?php

class Admin_IndexController extends GTL_Action {

    public function init() {
        parent::init();
        /* Initialize action controller here */
    }

    
    public function indexAction() {
		//Initialize
		$where = "";
		
		//Load User Model
		$userModel = new Model_User();
		//Get Usercount Data
		$usercountArray = $userModel->getUserCount($where);
		
		//Load Admin Model
		$adminuserModel = new Model_Adminuser();
		//Get Admincount Data
		$admincountArray = $adminuserModel->getAdminuserCount("admin_status = 1");
		
		//Load post Model
        $postModel = new Model_Post();
		//Get postcount Data
		$postcountArray = $postModel->getPostCount("post_status = 1");
		
		//Load category Model
        $CategoryModel = new Model_Category();
		//Get categorycount Data
		$categorycountArray = $CategoryModel->getCategoryCount("cat_status = 1");
				
		$this->view->usercountArray = $usercountArray;
		$this->view->admincountArray = $admincountArray;
		$this->view->postcountArray = $postcountArray;
		$this->view->categorycountArray = $categorycountArray;
    }


}

?>
