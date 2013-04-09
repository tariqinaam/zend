<?php

class ScannerController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       $client = new SoapClient('http://localhost/magento/api/v2_soap/?wsdl');

// If some stuff requires api authentification,
// then get a session token
$session = $client->login('testuser', '123456');

// get attribute set
$attributeSets = $client->catalogProductAttributeSetList($session);
$attributeSet = current($attributeSets);

$result = $client->catalogProductCreate($session, 'simple', $attributeSet->set_id, 'product_sku', array(
    'categories' => array(2),
    'websites' => array(1),
    'name' => 'Product name',
    'description' => 'Product description',
    'short_description' => 'Product short description',
    'weight' => '10',
    'status' => '1',
    'url_key' => 'product-url-key',
    'url_path' => 'product-url-path',
    'visibility' => '4',
    'price' => '100',
    'tax_class_id' => 1,
    'meta_title' => 'Product meta title',
    'meta_keyword' => 'Product meta keyword',
    'meta_description' => 'Product meta description'
));
vdump($result);exit;
//var_dump ($result);


}

}
