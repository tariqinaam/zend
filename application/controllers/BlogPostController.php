<?php

class BlogPostController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
         $postsTBL = new Application_Model_Posts();
         $this->view->posts = $postsTBL->fetchAll($where='Author_Id=2');
          //Zend_Auth::getInstance()->getStorage()->read()->Id;
        
    }
    
    public function addAction()
    {
         $blogpost = new Application_Model_Posts();
        $form = new Application_Form_Post();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();

            if ($form->isValid($postData)) {

               /* if ($postData['password'] != $postData['cpassword']) {

                    $this->errorMessage = "Password not matched";
                    return;
                }*/
                $formData = $form->getValues();
               // unset($formData['cpassword']);
                $blogpost->insert($formData);

                $this->view->successMsg = "post successfully saved.";
                $this->_redirect('blogPost/index');
            }
        }
    }
    
    public function editAction()
    {
        
    }
    
    public function deleteAction()
    {
        
    }


}

