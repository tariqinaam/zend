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
                $email = $form->getValue('email');

                if ($postData['password'] != $postData['cpassword']) {

                    $this->view->errorMessage = "Password not matched";
                    return;
                }

                /* if($users->checkUnique($email)){
                  $this->view->errorMessage = "email already in use";
                  return;
                  } */
                $token = new Application_Model_User();
                $token1 = $token->generateToken();
                

                $tr = new Zend_Mail_Transport_Smtp('mail.readingroom.com');
                Zend_Mail::setDefaultTransport($tr);
                $formData = $form->getValues();
                $password = $formData['password'];
                $formData['password'] = md5($password);
                $formData += array('TokenId' => $token1, 'IsActive' => '0');
                unset($formData['cpassword']);
               
                $users->insert($formData);

                $varification = APPLICATION_PATH . 'User/verification?email=' . $email . '&token=' . $token1;
                $mail = new Zend_Mail();
                $mail->setBodyText('Please click the link to complete registeration' . $varification)
                        ->setFrom('tariq@ewebzyme.co.uk')
                        ->addTo($email)
                        ->setSubject('complete registeration ')
                        ->send();
                $this->_helper->FlashMessenger("Thank you for registering, please verify your email address and then login.");
                $this->_redirect('User/login');
            }
        }
    }

    public function verifyAction() {
        $users = new Application_Model_User();

        if (!isset($_GET['token']) || !isset($_GET['email'])) {
            $this->view->errorMsg = "link is not valid";
        } else {
            $token = $_GET['token'];
            $email = $_GET['email'];

            $auth = Zend_Auth::getInstance();
            $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'users');
            $authAdapter->setIdentityColumn('email')->setCredentialColumn('TokenId');
            $authAdapter->setIdentity($email)->setCredential($token);
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $where = array('Email = ?' => $email);
                $users->update(array('IsActive' => '1'), $where);
                $this->_helper->FlashMessenger("Thanks you for verification, please login now");

                $this->logoutAction();
            } else {

                $this->view->errorMsg = "No user found";
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
        $this->view->flashMessages = $this->_helper->FlashMessenger->getMessages();
        if ($this->getRequest()->isPost()) {
            $postData = $this->_request->getPost();
            if ($form->isValid($postData)) {
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'users');
                $authAdapter->setIdentityColumn('email')->setCredentialColumn('password');
                $authAdapter->setIdentity($data['email'])->setCredential(md5($data['password']));
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    
 
                    if ($authAdapter->getResultRowObject()->IsActive) {

                        $storage = new Zend_Auth_Storage_Session();
                        $id = $authAdapter->getResultRowObject()->Id;
                        Zend_Registry::set('User_Id', $id);
                        $storage->write($authAdapter->getResultRowObject());
                        
                        //var_dump($authAdapter->getResultRowObject());exit;
                        $this->_redirect('User/index');
                    } else {
                        $storage = new Zend_Auth_Storage_Session();
                        $storage->clear();
                        $this->view->errorMsg = "Account not activated. please check your email to verify it.";
                    }
                } else {
                    $this->view->errorMsg = "Invalid username or password";
                }
            }
        }
    }

}

