<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $this->user = $auth->getIdentity();
            //new dBug($this->user);
            //exit;
        }
    }

    public function indexAction() {

        // action body
        $sessionStore = new Zend_Auth_Storage_Session();
        $data = $sessionStore->read();
        if (!$data) {
            $this->view->status = $this->_redirect('User/login');
            $this->view->login = "Login";
            $this->_redirect('User/login');
        }
        // $this->view->status = $this->_redirect('User/logout');
        $this->view->login = "Logout";
        $this->view->email = $this->user->Email;
    }

    public function registerAction() {
        $users = new Application_Model_User();
        $form = new Application_Form_Register();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();

            if ($form->isValid($postData)) {

                if ($postData['password'] != $postData['cpassword']) {

                    $this->errorMessage = "Password not matched";
                    return;
                }
                $formData = $form->getValues();
                unset($formData['cpassword']);
                $users->insert($formData);

                $this->view->successMsg = "Thank you for registering, Please Login now.";
                $this->_redirect('User/login');
            }
        }
    }

    public function logoutAction() {

        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('User/login');
    }

    public function loginAction() {

        $users = new Application_Model_User();
        $form = new Application_Form_Login();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();
            if ($form->isValid($postData)) {
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'users');
                $authAdapter->setIdentityColumn('email')->setCredentialColumn('password');
                $authAdapter->setIdentity($data['email'])->setCredential($data['password']);
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $storage = new Zend_Auth_Storage_Session();
                  $id = $authAdapter->getResultRowObject()->Id;
                   Zend_Registry::set('User_Id', $id);
                    $storage->write($authAdapter->getResultRowObject());
                    $this->_redirect('User/index');
                } else {
                    $this->view->errorMsg = "Invalid username or password";
                }
            }
        }
    }

}

