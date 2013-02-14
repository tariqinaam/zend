<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CKEditor extends Zend_Form_Element_Textarea{
 
public function __construct($spec, $options = null)
{
    parent::__construct($spec, $options);
       //grab a reference to the view rendering the form element
       $view = $this->getView();
       //include scripts and initialize the ckeditor
      echo $view->headScript()->appendFile('/ckeditor/ckeditor.js', 'text/javascript');
       //var_dump($view->headScript()->appendFile('/ckeditor/ckeditor.js', 'text/javascript'));
       //give the textarea a class name that ckeditor recognises
       $this->setAttrib('class', 'ckeditor');
  }
}

?>
