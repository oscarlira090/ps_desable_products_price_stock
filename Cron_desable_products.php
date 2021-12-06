<?php

include(dirname(__FILE__).'/../../config/config.inc.php');

$products = array();

$selectSQL = '
            SELECT 
            p.id_product
            , p.reference
            FROM 
                ' . _DB_PREFIX_ . 'product p 
            INNER JOIN 
                ' . _DB_PREFIX_ . 'product_shop ps 
            ON 
                p.id_product = ps.id_product 
            ';
if ($results = Db::getInstance()->ExecuteS($selectSQL)) {
   $products = $results;
}
            
foreach($products as $p)
{
    $productModel = new Product((int)$p['id_product']);
    if($productModel == null)
      continue;
    
    $stock = StockAvailable::getQuantityAvailableByProduct((int)$p['id_product']);
    if ($productModel->price == 0 || $stock == 0){
         Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product SET active = 0 WHERE id_product = ' . (int)$productModel->id);
         Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'product_shop SET active = 0 WHERE id_product = ' . (int)$productModel->id);
    }
}
