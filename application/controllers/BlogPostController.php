<?php

class BlogPostController extends Zend_Controller_Action
{
    private $_id ;
    private $_name;
    protected $_successMsg;

    public function init()
    {
        /* Initialize action controller here */
        $this->_id =  Zend_Auth::getInstance()->getStorage()->read()->Id;
        $this->_name =  Zend_Auth::getInstance()->getStorage()->read()->Name;
        
    }

    public function indexAction()
    {
        $postsTBL = new Application_Model_Posts();
        $form = new Application_Form_Search();
        $this->view->form = $form;
        if($this->getRequest()->isPost()){
            $sortData = $this->_request->getPost();
            if($form->isValid($sortData)){
                $column = $form->getValue('sort');
             $result = $postsTBL->select($where="Author_Id= $this->_id" )->order($column.' desc');
            }
        }else{
           
         
         $this->view->posts = $postsTBL->fetchAll($where="Author_Id= $this->_id");
         $result = $postsTBL->select($where="Author_Id= $this->_id");
        }
      /*if(isset($this->view->posts)){
          $this->view->emptyMsg = "You havn't created any post yet.";
      }*/
      if(isset($_GET['success'])){
          echo $this->view->successMsg= $this->_successMsg;
          $this->view->successMsg = "your post has successfully saved";
      }
      if (isset($result)){
          $paginator = Zend_Paginator::factory($result);
          $paginator->setItemCountPerPage(2);
          $paginator->setCurrentPageNumber($this->_getParam('page'));
          $this->view->paginator = $paginator;
          
          Zend_Paginator::setDefaultScrollingStyle('sliding');
          Zend_View_Helper_PaginationControl::setDefaultViewPartial(
                'blog-post/pagination.phtml' );
          //Zend_View_Helper_PaginationControl::setDefaultViewPartial($partial)
      }
                   
    }
    
    public function addAction()
    {
         $blogpost = new Application_Model_Posts();
        $form = new Application_Form_Post();
        $this->view->form = $form;

                
        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();
            //var_dump($postData);exit;

            if ($form->isValid($postData)) {

               /* if ($postData['password'] != $postData['cpassword']) {

                    $this->errorMessage = "Password not matched";
                    return;
                }*/
                if($form->getValue('status') == '1'){
                    $this->_successMsg = "your blog post has succesfully published.";
                }else{
                    $this->_successMsg ="your post has saved as draft.";
                }
               // $form->setAttrib('Author_Id', '2');
                $formData = $form->getValues();
                $formData += array('Author_Id' => $this->_id, 'Post_Author' => $this->_name );
               //var_dump($formData);exit;
               // unset($formData['cpassword']);
                $blogpost->insert($formData);

                $this->view->successMsg = "post successfully saved.";
                
                $this->_redirect('blogPost/index?success');
                
            }
        }
    }
    
    public function editAction()
    {
        $blogpost = new Application_Model_Posts();
        $form = new Application_Form_Post();
        $result = $blogpost->fetchAll($where="Post_Id= $_GET[post_id]");
        $rowarray = $result->toArray();
              
        $form->populate($rowarray[0]);
        $this->view->form = $form;
       // echo $rowarray[0]['Post_Id'];
        
         //$result = $postsTBL->fetchAll($where="Author_Id= $this->_id");
        
        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();
            //var_dump($postData);exit;

            if ($form->isValid($postData)) {

               /* if ($postData['password'] != $postData['cpassword']) {

                    $this->errorMessage = "Password not matched";
                    return;
                }*/
                if($form->getValue('status') == '1'){
                    $this->_successMsg = "your blog post has succesfully updated and published.";
                }else{
                    $this->_successMsg ="your post has updated as draft.";
                }
               // $form->setAttrib('Author_Id', '2');
                $formData = $form->getValues();
                //$formData += array('Author_Id' => $this->_id, 'Post_Author' => $this->_name );
                //echo $rowarray[0]['Post_Id'];
               //var_dump($formData);exit;
               // unset($formData['cpassword']);
               $where1 = array('Post_Id = ?' => $rowarray[0]['Post_Id'] );
               
               $blogpost->update($formData, $where1);
                

                $this->view->updateMsg = "post successfully updated.";
                
                $this->_redirect('blogPost/index?update');
                
            }
        }
    }
    
    public function deleteAction()
    {
        
    }


}

