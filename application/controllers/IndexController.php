<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
        $this->view->accessDenied = $this->getParam('msg');
         $postsTBL = new Application_Model_Posts();
        //$form = new Application_Form_Search();
        //$this->view->form = $form;
        
        //$result = $postsTBL->select()->where("Post_Status=1");
             
        $this->view->posts = $postsTBL->fetchAll($where = "Post_Status=1", $order = "Post_Date Desc", $count = "2");
        //vdump($this->view->posts);
        /* if(isset($this->view->posts)){
          $this->view->emptyMsg = "You havn't created any post yet.";
          } */
        
    $content = "<div class='innerbanner'><span class='innerbanner_head'>Lorem ipsum is sample text used for dummy content </span><br />
                    <span class='innerbanner_head'>To give an idea how actual text will look.</span></div>";
        
    $this->view->homePageSpotlight = $content;
    }

}

