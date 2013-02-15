<?php

class Application_Form_Search extends Zend_Form
{

    public function init()
    {
         $sortOrder = $this->createElement('select', 'sort');
       $sortOrder->setLabel('Sort By : ')->addMultiOptions(array('Post_Date' => 'Publish Date',
                                           'Post_Status' => 'Post Status'));
               
              //  ->setValidators(new Zend_Validate_EmailAddress);
     
        
        
               $submit = $this->createElement('submit', 'submit');
                $submit->setLabel('Submit')->setIgnore(TRUE);
        
         $this->addElements(array(
           // $id,
             $sortOrder,
            $submit
            ));
    }


}

