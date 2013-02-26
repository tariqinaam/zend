<?php

class Application_Form_Post extends ZendX_JQuery_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $notEmpty = new Zend_Validate_NotEmpty();
        $notEmpty->setMessage('Field can not be empty');
        
        $emailValidate = new Zend_Validate_EmailAddress();
        $emailValidate->setMessage('email is not valid');
        
        $this->setMethod('post');
        
        //$id = $this->createElement('hidden', 'Id');
        
        $name = $this->createElement('text', 'Post_Title');
        $name->setLabel('Title')
                ->setRequired(TRUE)
              
               ->addValidator($notEmpty, True);
        
        $publishDate = new Zend_Dojo_Form_Element_DateTextBox('Post_Date');
        $publishDate->setLabel('Publish Date');
        
        $category = $this->createElement('select', 'category1');
        $category->setLabel('Category')
                ->addValidator($notEmpty, TRUE);
        
        $subcategory = $this->createElement('select', 'category2');
        $subcategory->setLabel('Sub Category')
                ->addValidator($notEmpty, TRUE);
        
        
        $editor = new CKEditor('Post_content', array('required'=>true, 'label'=>'Content'));
        //new My_Form_Element_CKEditor($spec, $options)
       // $content = $this->createElement('text', 'content');
        //$content->setLabel('Content')
             //   ->setRequired(TRUE);
              //  ->setValidators(new Zend_Validate_EmailAddress);
        $editor->addValidator($notEmpty, TRUE);
        
        //$email->getValidator('EmailAddress')->setMessage('invalid email address');
               
       $postStatus = $this->createElement('select', 'Post_Status');
       $postStatus->setLabel('Status')->addMultiOptions(array('0' => 'Draft',
                                           '1' => 'Publish'));
               
              //  ->setValidators(new Zend_Validate_EmailAddress);
     
        
        
               $submit = $this->createElement('submit', 'submit');
                $submit->setLabel('Submit')->setIgnore(TRUE);
        
         $this->addElements(array(
           // $id,
             $name,
           $postStatus,
             $publishDate,
             $category,
             $subcategory,
             $editor,
            $submit
            ));
    }


}

