<?php

class Application_Model_User extends Zend_Db_Table_Abstract {

    protected $_name = "users";

     function generateToken() {

        $length = 20;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ’';
        $random_string = "";
        for ($p = 0; $p < $length; $p++) {
            $random_string .= $characters[mt_rand(0, strlen($characters))];
        }
    
        return $random_string;
    }
    
   
}

