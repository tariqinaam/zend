<?php

class Application_Form_Register extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Field can not be empty');
        
        $emailValidate = new Zend_Validate_EmailAddress();
        $emailValidate->setMessage('email is not valid');
        
        $alreadyExist = new Zend_Validate_Db_NoRecordExists('USers', 'email');
        $alreadyExist->setMessage('email already exist, try forgot password');
        
        $this->setMethod('post');
        
        $id = $this->createElement('hidden', 'id');
        
         $name = $this->createElement('text', 'name');
        $name->setLabel('Name')
                ->setRequired(TRUE)
               ->addValidator($notEmpty, True);
        
        
        $email = $this->createElement('text', 'email');
        $email->setLabel('Username')
                ->setRequired(TRUE);
              //  ->setValidators(new Zend_Validate_EmailAddress);
        $email->addValidator($alreadyExist , TRUE);
        $email->addValidator($notEmpty, TRUE);
        $email->addValidator($emailValidate, TRUE);
        //$email->getValidator('EmailAddress')->setMessage('invalid email address');
               
        $password = $this->createElement('password', 'password');
        $password->setLabel('Password')
                ->setRequired(TRUE)
               ->addValidator($notEmpty, True);
        
        $cpassword = $this->createElement('password', 'cpassword');
        $cpassword->setLabel('Confirm Password')
                ->setRequired(TRUE)
               ->addValidator($notEmpty, True);
        
        
        
        $submit = $this->createElement('submit', 'register');
                $submit->setLabel('register')->setIgnore(TRUE);
        
         $this->addElements(array(
            $id,
             $name,
            $email,
            $password,
             $cpassword,
            $submit
            ));
    }


}

