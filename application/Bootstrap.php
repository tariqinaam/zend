<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public static $_autoloader;
    public static $namespace = 'Tariq';
    private $_acl = null;
    //private $_role;

    protected function _initAutoload() {

        if (is_null(self::$_autoloader)) {

            self::$_autoloader = new Zend_Application_Module_Autoloader(array(
                        'namespace' => self::$namespace,
                        'basePath' => APPLICATION_PATH
                    ));

            self::$_autoloader->setResourceTypes(array(
                'plugin' => array(
                    'namespace' => 'Plugin',
                    'path' => 'plugin'
                ),
                'model' => array(
                    'namespace' => 'Model',
                    'path' => 'models'
                ),
                'core' => array(
                    'namespace' => 'Core',
                    'path' => 'core'
                ),
                'dbtable' => array(
                    'namespace' => 'DbTable',
                    'path' => 'models/DbTable'
                ),
                'dbrowset' => array(
                    'namespace' => 'DbRowset',
                    'path' => 'models/DbRowset'
                ),
                'dbrow' => array(
                    'namespace' => 'DbRow',
                    'path' => 'models/DbRow'
                ),
                'viewhelper' => array(
                    'namespace' => 'View_Helper',
                    'path' => 'views/helpers'
                ),
                'form' => array(
                    'namespace' => 'Form',
                    'path' => 'forms'
                )
            ));
            new Zend_Loader_PluginLoader();
        }

        return self::$_autoloader;
    }

    protected function _initDbugLoad() {
        //$this->bootstrap('')
        Zend_Loader::loadFile('dBug.php', $dirs = 'library', $once = false);
        $this->bootstrap('autoload');

       
    }

    public function _initViewHelper() {
        
         //$identity = Zend_Auth::getInstance()->getStorage()->read()->Role;
        //$this->_role = $identity->Role;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->Role);
        } else {
            Zend_Registry::set('role', 'guest');
        }

        $this->_acl = new Application_Model_LibraryACL();
        //$auth = Zend_Auth::getInstance();

        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Tariq_Plugin_AccessCheck($this->_acl));
        
        $this->bootstrap('layout');
        //$layout = $this->getResource('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        //echo $x = Zend_Registry::get('role');
         
        $view->doctype('HTML4_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8')
                      ->appendHttpEquiv('Content-Language', 'en-US');
                 
        $navigationFromConfig = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
        $navConainer = new Zend_Navigation($navigationFromConfig);

        $view->navigation($navConainer)->setacl($this->_acl)->setRole(Zend_Registry::get('role'));

        $view->title = "Zend Blog Application";
    }

}

