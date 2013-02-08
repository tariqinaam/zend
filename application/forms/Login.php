<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Field can not be empty');
        
        $emailValidate = new Zend_Validate_EmailAddress();
        $emailValidate->setMessage('email is not valid');
        
        $this->setMethod('post');
        
        $id = $this->createElement('hidden', 'id');
        
        $email = $this->createElement('text', 'email');
        $email->setLabel('Username')
                ->setRequired(TRUE);
              //  ->setValidators(new Zend_Validate_EmailAddress);
        $email->addValidator($notEmpty, TRUE);
        $email->addValidator($emailValidate, TRUE);
        //$email->getValidator('EmailAddress')->setMessage('invalid email address');
               
                
        
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password')
                ->setRequired(TRUE)
               ->addValidator($notEmpty, True);
        
        
        $submit = $this->createElement('submit', 'login');
                $submit->setLabel('Login')->setIgnore(TRUE);
        
         $this->addElements(array(
            $id,
            $email,
            $password,
            $submit
            ));
    }


}

