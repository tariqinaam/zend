<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
     
       public static $_autoloader;
       public static $namespace = 'Tariq';
    protected function _initAutoload(){
               
         if (is_null(self::$_autoloader)) {

            self::$_autoloader = new Zend_Application_Module_Autoloader(array(

                'namespace' => self::$namespace,

                'basePath' => APPLICATION_PATH

            ));

            self::$_autoloader->setResourceTypes(array(

                'plugin' => array(

                    'namespace' => 'Plugin',

                    'path'      => 'plugin'

                ),

                'model' => array(

                    'namespace' => 'Model',

                    'path'      => 'models'

                ),

                'core' => array(

                    'namespace' => 'Core',

                    'path'      => 'core'

                ),

                'dbtable' => array(

                    'namespace' => 'DbTable',

                    'path'      => 'models/DbTable'

                ),

                'dbrowset' => array(

                    'namespace' => 'DbRowset',

                    'path'      => 'models/DbRowset'

                ),

                'dbrow' => array(

                    'namespace' => 'DbRow',

                    'path'      => 'models/DbRow'

                ),

                'viewhelper' => array(

                    'namespace' => 'View_Helper',

                    'path'      => 'views/helpers'

                ),

                'form'      => array(

                    'namespace' => 'Form',

                    'path'      => 'forms'

                )

            ));new Zend_Loader_PluginLoader();

        }        

        return self::$_autoloader;
    }

       protected function _initDbugLoad()
    {
        //$this->bootstrap('')
        Zend_Loader::loadFile('dBug.php', $dirs='library', $once=false);
        $this->bootstrap('autoload');
        
        $acl = new Application_Model_LibraryACL();
        $auth = Zend_Auth::getInstance();
        
         $fc = Zend_Controller_Front::getInstance();
         $fc->registerPlugin(new Tariq_Plugin_AccessCheck($acl, $auth));
         
    }
    public function _initViewHelper(){
        $this->bootstrap('layout');
        //$layout = $this->getResource('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        
        $view->doctype('HTML4_STRICT');
        
    }
    }

