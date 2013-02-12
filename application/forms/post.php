<?php

class Application_Form_Post extends Zend_Form
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
        
         $name = $this->createElement('text', 'title');
        $name->setLabel('Title')
                ->setRequired(TRUE)
              
               ->addValidator($notEmpty, True);
        
        
        //$editor = $this->addElement(new CKEditor('summary', array('required'=>true, 'label'=>'Summary')));
        //new My_Form_Element_CKEditor($spec, $options)
        $content = $this->createElement('text', 'content');
        $content->setLabel('Content')
                ->setRequired(TRUE);
              //  ->setValidators(new Zend_Validate_EmailAddress);
        $content->addValidator($notEmpty, TRUE);
        
        //$email->getValidator('EmailAddress')->setMessage('invalid email address');
               
       $postStatus = $this->createElement('select', 'status', array('multiOption' =>array(
                                                                    'draft' => '0',
                                                                     'publish' => '1')));
        $postStatus->setLabel('Status')
                    
                   ->addMultiOption(array('0' => 'Draft',
                                           '1' => 'Publish'));
               
              //  ->setValidators(new Zend_Validate_EmailAddress);
     
        
        
        
        $submit = $this->createElement('submit', 'register');
                $submit->setLabel('register')->setIgnore(TRUE);
        
         $this->addElements(array(
            $id,
             $name,
           $postStatus,
             $content,
            $submit
            ));
    }


}

