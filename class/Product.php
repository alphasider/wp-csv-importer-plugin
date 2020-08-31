<?php

  namespace NGS;

  use WC_Product;

  class Product {
    public $product;

    public function __construct() {
      return $this->product = new WC_Product();
    }

    public function set_product_main_params($product_name, $product_description, $product_sku, $product_price, $product_category_ids = [], $product_status = 'publish') {
      $this->product->set_name($product_name);
      $this->product->set_status($product_status);  // can be publish,draft or any wordpress post status
      $this->product->set_description($product_description);
      $this->product->set_sku($product_sku); //can be blank in case you don't have sku, but You can't add duplicate sku's
      $this->product->set_price($product_price); // set product price
      $this->product->set_category_ids($product_category_ids); // array of category ids, You can get category id from WooCommerce Product Category Section of Wordpress Admin
      return $this->product;
    }

    public function save_product(){
      return $this->product->save();
    }

//    public static function set_product_attributes($product_stock_id, $product_year, $product_make, $product_model, $product_condition, $product_doors, $product_drivertrain, $product_engine, $product_interior_color, $product_mileage, $product_transmission) {
//
//    }
  }
