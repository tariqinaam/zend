<?php

class Application_Model_LibraryACL extends Zend_Acl {

    public function __construct() {

        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('error'));
        //$this->add(new Zend_Acl_Resource('register'));
        //$this->add(new Zend_Acl_Resource('logout'));


        $this->add(new Zend_Acl_Resource('User'));
        $this->add(new Zend_Acl_Resource('Scanner'));
        //$this->add(new Zend_Acl_Resource('verify'), 'User');
        //$this->add(new Zend_Acl_Resource('login'), 'User');
        // $this->add(new Zend_Acl_Resource('logout'), 'User');


        $this->add(new Zend_Acl_Resource('blogPost'));
        //  $this->add(new Zend_Acl_Resource('edit'));
        //  $this->add(new Zend_Acl_Resource('add'));
        //  $this->add(new Zend_Acl_Resource('delete'));

        $this->add(new Zend_Acl_Resource('posts'));
        //$this->add(new Zend_Acl_Resource('posts'), 'blogPost');
        // $this->add(new Zend_Acl_Resource('list'), 'posts');

        $this->addRole(new Zend_Acl_Role('guest'));
        $this->addRole(new Zend_Acl_Role('registered'), 'guest');
        $this->addRole(new Zend_Acl_Role('admin'), 'registered');

        $this->allow('guest', array('error', 'index', 'posts'));
        $this->allow('guest', 'User', array( 'index', 'login', 'verify', 'register'));
        $this->allow('guest', 'blogPost', array('single'));
        $this->allow('guest', 'Scanner', array('index'));
        
        $this->deny('registered', 'User', array('login', 'register'));
        $this->allow('registered', 'User', array('logout'));
        $this->allow('registered', 'blogPost', array('index', 'add', 'edit', 'subcategory'));
        

        $this->allow('admin', 'blogPost', 'delete');
        // $this->allow('guest', 'User', 'register');
        // $this->allow('guest', 'User', 'login');
        // $this->allow('guest', 'User', 'logout');
        //$this->deny('registered', 'blogPost',  'delete');
        //$this->allow('registered', array('blogPost', 'edit', 'add', 'logout'));
        //$this->deny('registered',  'login');
        //$this->allow('guest', 'posts');
        //$this->allow('guest', 'posts', 'list');
        //$this->allow('guest', 'blogPost');
        // $this->allow('registered', 'User', 'logout');
        //$this->allow('registered', 'blogPost', 'add');
        //$this->allow('registered', 'blogPost', 'edit');
        //$this->allow('admin', 'blogPost', 'delete');
        //$this->allow('admin', 'delete');
    }

}

