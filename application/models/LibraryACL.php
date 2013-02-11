<?php

class Application_Model_LibraryACL extends Zend_Acl
{
    public function __construct() {
        
        $this->add(new Zend_Acl_Resource('index'));
        
        $this->add(new Zend_Acl_Resource('error'));
        
        $this->add(new Zend_Acl_Resource('User'));
        $this->add(new Zend_Acl_Resource('register'), 'User');
        $this->add(new Zend_Acl_Resource('login'), 'User');
        $this->add(new Zend_Acl_Resource('logout'), 'User');
        
        $this->add(new Zend_Acl_Resource('blogPost'));
        $this->add(new Zend_Acl_Resource('edit'), 'blogPost');
        $this->add(new Zend_Acl_Resource('add'), 'blogPost');
        $this->add(new Zend_Acl_Resource('delete'), 'blogPost');
        
        $this->add(new Zend_Acl_Resource('posts'));
        $this->add(new Zend_Acl_Resource('list'), 'posts');
        
        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('registered'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'registered');
                
       
        $this->allow('guest', 'index');
        $this->allow('guest', 'error');
        $this->allow('guest', 'User');
        $this->allow('guest', 'User', 'register');
        $this->allow('guest', 'User', 'login');
        $this->allow('guest', 'User', 'logout');
        
        $this->allow('guest', 'posts');
        $this->allow('guest', 'posts', 'list');
        
        $this->allow('guest', 'blogPost');
        $this->allow('registered', 'blogPost', 'add');
        $this->allow('registered', 'blogPost', 'edit');
        $this->allow('admin', 'blogPost', 'delete');
        
        
        
        
    }


}

