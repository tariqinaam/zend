<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

 
    protected function _initDbugLoad()
    {
        //$this->bootstrap('')
        Zend_Loader::loadFile('dBug.php', $dirs='library', $once=false);
    }
}

