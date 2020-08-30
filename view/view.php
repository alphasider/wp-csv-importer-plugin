<?php
  use NGS\CSV;
  use NGS\Product;
  ?>

<h1>Import products from CSV</h1>

<?php
  echo CSV::build_table();


  function add_prod(){
    $product = new WC_Product();

    $product->set_name("Product Title");
    $product->set_status("publish");  // can be publish,draft or any wordpress post status
    $product->set_catalog_visibility('visible'); // add the product visibility status
    $product->set_description("Product Description");
    $product->set_sku(""); //can be blank in case you don't have sku, but You can't add duplicate sku's
    $product->set_price(10.55); // set product price
    $product->set_regular_price(10.55); // set product regular price
    $product->set_manage_stock(true); // true or false
    $product->set_stock_quantity(10);
    $product->set_stock_status('instock'); // in stock or out of stock value
    $product->set_backorders('no');
    $product->set_reviews_allowed(true);
    $product->set_sold_individually(false);
    $product->set_category_ids(array(15));

    $product->save();

    echo '<pre>';
    print_r($product);
    echo '</pre>';
  }

  add_prod();


?>
