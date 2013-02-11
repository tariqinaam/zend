<?php

class PostsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
    
    public function listAction()
    {
        $postsTBL = new Application_Model_Posts();
        $this->view->posts = $postsTBL->fetchAll();
        //var_dump($this->view->posts);
    }
    

}

