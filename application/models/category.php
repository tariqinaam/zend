<?php

class Application_Model_Category extends Zend_Db_Table_Abstract {

       protected $_name = "category";

    public function getCategories() {
        $categories = $this->fetchAll("ParentId=0");
        $result = $categories->toArray();
        //vdump($result);exit;
        return $result;
    }
    
    public function getSubCategories($ParentId){
        
        $categories = $this->fetchAll("parentId=" .$ParentId);
        $result = $categories->toArray();
        
        return $result;
    }

}

