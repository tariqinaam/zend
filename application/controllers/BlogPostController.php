<?php

class BlogPostController extends Zend_Controller_Action {

    /**
     * Author : Tariq
     * 
     * 
     * A class to manage individual account posts. e.g view my post, add post, edit post.
     * 
     * TODO : replace message variable with flashmessenger.
     * 
     */
    /*
     * @param string $_id    id of logged in user
     * @param string $_name  name of logged in user
     * @param string $_successMsg  store get variable of success 
     */
    private $_id;
    private $_name;
    protected $_successMsg;
    
    public function init() {
        /* get currently logged in user id and its name from storage */
        $this->_id = Zend_Auth::getInstance()->getStorage()->read()->Id;
        $this->_name = Zend_Auth::getInstance()->getStorage()->read()->Name;
        

    }

    /*
     * default action for the class
     * it loads all the posts of logged in user.
     * it also configure pagination
     */

    public function indexAction() {

        $postsTBL = new Application_Model_Posts();   //object of model
        $form = new Application_Form_Search();  //object of form
        $this->view->form = $form;                  //assign form to current view.
        $this->view->msg = $this->_helper->FlashMessenger->getMessages();       //get message from flashmsgnr, if post is updated etc.

        $form->getElement('sort')->setValue($_SESSION['sort']); //set the sortby selectbox value in case if it is not post

        /*
         * check if request is post. this solely for changing sort order
         */
        if ($this->getRequest()->isPost()) {
            $sortData = $this->_request->getPost();   //get the data from post.

            if ($form->isValid($sortData)) {        //if form is valid
                $column = $form->getValue('sort');  //fetch sort select box value
                $_SESSION['sort'] = $form->getValue('sort'); //add sort value to session to keep sorting during pagination

                if ($column == 'Post_Date' || $column == 'Post_Status') {

                    $result = $postsTBL->select()->where("Author_Id= $this->_id")->order($column . ' desc');  //fetch all posts from db of logged in user in desc order of column selected in previous step.
                } else {
                    $result = $postsTBL->select()->where("Author_Id= $this->_id");  //its not post request, so fetch posts without any sorting.
                }
            }
            //condition to see if sort variable is set. this would be tha case when its not post request and user has done sorting in some previous step and now navigating between pagination.
        } else if (!empty($_SESSION['sort'])) {
            //echo $_SESSION['sort'];
            $result = $postsTBL->select()->where("Author_Id= $this->_id")->order($_SESSION['sort'] . ' desc');
        } else {

            //$this->view->posts = $postsTBL->select()->where("Author_Id= $this->_id");  
            $result = $postsTBL->select()->where("Author_Id= $this->_id");  //its not post request, so fetch posts without any sorting.
        }

        //pagination start here
        if (isset($result)) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setItemCountPerPage(2);
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $this->view->paginator = $paginator;

            Zend_Paginator::setDefaultScrollingStyle('sliding');
            Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                    'blog-post/pagination.phtml');
            //Zend_View_Helper_PaginationControl::setDefaultViewPartial($partial)
        }
    }

    //add a new blog post 
    public function addAction() {
        $blogpost = new Application_Model_Posts();
        $form = new Application_Form_Post();
        $categoryModel = new Application_Model_Category();
        $result = $categoryModel->getCategories();
        
        //$getCategories = $result->toArray();
        foreach($result as $key => $value){
            
        $form->getElement('category1')
                ->addMultiOptions(array($value['CategoryId'] => $value['CategoryName']));
        }
        
        $ParentId = $form->getElement('category1')->getValue();
        
        $subcat_result = $categoryModel->getSubCategories(1);
        
        foreach ($subcat_result as $key => $value) {
            $form->getElement('category2')
                    ->addMultiOptions(array($value['CategoryId'] => $value['CategoryName']));
        }
        
        $this->view->form = $form;
        //check if it is post request. i.e ideally call when user fill all data and press submit
        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();
            //var_dump($postData);exit;

            if ($form->isValid($postData)) {

                $formData = $form->getValues();
                //add author id, and author name taken from registry
                $formData += array('Author_Id' => $this->_id, 'Post_Author' => $this->_name);
                //insert record into db.
                $blogpost->insert($formData);
                //set flashmessenger variable to display after save.
                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage('post successfully saved.');
                //redirect user to my post
                $this->_redirect('blogPost/index');
            }
        }
    }

    //edit a blog post
    public function editAction() {
        $blogpost = new Application_Model_Posts();
        $form = new Application_Form_Post();
        //get post id from get request
        $post_id = $_GET['post_id'];

        if ($post_id) {
            //get row from db where matching post and author id
            $result = $blogpost->fetchRow("Post_Id=" . $post_id . " and Author_Id = " . $this->_id);

            if ($result) {
                //convert rowobject into array
                $resultInArray = $result->toArray();
                //populate form with array created in last step.
                // and assign form to view
                $this->view->form = $form->populate($resultInArray);
            } else {
                echo "you are not authorized";
            }
        } else {

            echo "url is not valid";
        }
        //if it post request, ideally run when user change post and press submit.
        if ($this->getRequest()->isPost()) {
            //get post data
            $postData = $this->_request->getPost();
           
            if ($form->isValid($postData)) {
                
                $formData = $form->getValues();
                //set post id for where clause
                $where1 = array("Post_Id = $post_id");
                //update db
                $blogpost->update($formData, $where1);
                //set flash variable to display update message.
                $flashMessenger = $this->_helper->FlashMessenger;
                $flashMessenger->addMessage('post successfully updated.');
                //redirect user back to my post page.
                $this->_redirect('blogPost/index');
            }
        }
    }
    
    public function singleAction(){
       $blogpost = new Application_Model_Posts();
       $post_id =  $_GET['post_id'];
       
        
        if ($post_id) {
            //get row from db where matching post and author id
            $result = $blogpost->fetchAll($where = "Post_Id=" .$post_id);

            if ($result) {
                $result1= $result->toArray();
               $this->view->post = $result;
               
            } else {
                echo "you are not authorized";
            }
        } else {

            echo "url is not valid";
        }
    }

    public function deleteAction() {
        
    }

}

