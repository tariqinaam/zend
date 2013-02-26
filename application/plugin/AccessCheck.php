<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Tariq_Plugin_AccessCheck extends Zend_Controller_Plugin_Abstract {

    private $_acl = null;

    public function __construct(Zend_Acl $acl) {

        $this->_acl = $acl;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
         $resource = $request->getControllerName();
         $action = $request->getActionName();
         $x = Zend_Registry::get('role');
        
        //var_dump($this->_acl->isAllowed(Zend_Registry::get('role'), $resource, $action));
         
         if (!$this->_acl->isAllowed(Zend_Registry::get('role'), $resource, $action)) {
             if(!Zend_Registry::get('role') == 'guest'){
                 $request->setControllerName('Index')
                    ->setActionName('index')->setParams(array('msg' => 'Access Denied.. ;-)'));
             }else{
                  $request->setControllerName('User')
                    ->setActionName('login')->setParams(array('deniedMsg' => 'please login'));
             }
            
        }
    }

}

?>
