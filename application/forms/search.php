<?php

class Application_Form_Search extends Zend_Form {

    
    public function init() {
        $sortOrder = $this->createElement('select', 'sort');
        $sortOrder->addMultiOptions(array('' => '---', 'Post_Date' => 'Publish Date',
            'Post_Status' => 'Post Status'));
        $sortOrder->setOptions(array('class'=>'selecttextbox'))
                ->removeDecorator('label')
          ->removeDecorator('HtmlTag');
       


        $submit = $this->createElement('submit', 'submit');
        $submit->setLabel('Submit')->setIgnore(TRUE);
        $submit->setAttrib('class', 'login')
                ->removeDecorator('label')
          ->removeDecorator('HtmlTag')
                ->removeDecorator('DtDdWrapper');


        $this->addElements(array(
            // $id,
            $sortOrder,
            $submit
        ));
    }

}

