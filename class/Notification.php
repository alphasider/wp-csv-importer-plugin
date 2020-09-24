<?php


  namespace NGS;

  /**
   * Class Notification - Display notifications
   *
   * @package NGS
   */
  class Notification {

    /** Notifies about successfully created product
     *
     * @param $product_sku
     * @param $created_product_id
     */
    public static function created_new_product_succeed($product_sku, $created_product_id) {
      echo "<p  class='notification notification_success'>Added a new product. <b>SKU: {$product_sku}</b> | <b>ID: {$created_product_id}</b></p>";
    }

    /**
     * Notifies about product creation failure
     *
     * @param $product_sku
     */
    public static function create_new_product_failed($product_sku) {
      echo "<p  class='notification notification_failure'>Could not add a new product. <b>SKU: {$product_sku} </b> has not been created!</p>";
    }

    /**
     * Notifies about successfully updated product
     *
     * @param $product_sku
     * @param $created_product_id
     */
    public static function update_product_succeed($product_sku, $created_product_id) {
      echo "<p class='notification notification_success'>Product has been updated. <b>SKU: {$product_sku}</b> | <b>ID: {$created_product_id}</b></p>";
    }

    public static function delete_sold_out_product_status($post_object, $product_id, $product_sku) {
      if ($post_object !== false || $post_object !== null) {
        echo "<p class='notification notification_success'>The sold out product has been successfully deleted! <b>ID: {$product_id}</b> | <b>SKU: {$product_sku}</b> </p>";
      } else {
        echo "<p class='notification notification_failure'>The sold out product has not been deleted! <b>ID: {$product_id}</b> | <b>SKU: {$product_sku}</b> </p>";
      }
    }

    /**
     * Notifies about empty file
     *
     * @param $filename
     */
    public static function file_is_empty($filename ) {
      echo "<p class='notification notification_failure'>The file <b>{$filename}</b> is empty! Import is not possible.</p>";
    }

    public static function file_import_success($filename){
      echo "<p class='notification notification_success'>File <b>{$filename}</b> stored in the database successfully!</p>";
    }

    public static function file_import_failure($filename){
      echo "<p class='notification notification_failure'>Something went wrong! File <b>{$filename}</b> has not been stored in the database!</p>";
    }
  }