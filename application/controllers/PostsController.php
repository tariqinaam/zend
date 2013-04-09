<?php

class PostsController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function listAction() {

        $postsTBL = new Application_Model_Posts();
        $form = new Application_Form_Search();
        $this->view->form = $form;

        $form->getElement('sort')->setValue($_SESSION['sort']);

        if ($this->getRequest()->isPost()) {
            $sortData = $this->_request->getPost();
            if ($form->isValid($sortData)) {
                $column = $form->getValue('sort');
                $_SESSION['sort'] = $form->getValue('sort');
                if ($column == 'Post_Date' || $column == 'Post_Status') {
                    $result = $postsTBL->select()->where("Post_Status=1")->order($column . ' desc');
                } else {
                    $result = $postsTBL->select()->where("Post_Status=1");
                }
            }
        } else if (!empty($_SESSION['sort'])) {
           
            $result = $postsTBL->select()->where("Post_Status=1")->order($_SESSION['sort'] . ' desc');
        } else {

            // $this->view->posts = $postsTBL->select("Post_Status=1");

            $result = $postsTBL->select()->where("Post_Status=1");
        }
        /* if(isset($this->view->posts)){
          $this->view->emptyMsg = "You havn't created any post yet.";
          } */
        
        if (isset($_GET['success'])) {
            echo $this->view->successMsg = $this->_successMsg;
            $this->view->successMsg = "your post has successfully saved";
        }
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
    
    public function categoryfilterAction(){
      $categoryTBL = new Application_Model_Category();
      
      $result = $categoryTBL->fetchAll($where = "ParentId=0");
      
      $this->view->category = $result;
        
    }

}

