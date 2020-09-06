<?php


  namespace NGS;

  /**
   * Class Ajax
   * @package NGS
   */
  class Ajax {

    /**
     * Ajax constructor.
     */
    public function __construct() {
      add_action('wp_ajax_import_csv', [$this, 'import_csv_callback']);
    }

    /**
     * Import all products from CSV file
     */
    public function import_csv_callback() {
      $file_to_import = $_POST['fileName'];

      $csv_data = CSV::get_csv($file_to_import);
      $product = new Product();
      $all_products = $product->create_all_products($csv_data);

      echo $all_products;
      wp_die();
    }
  }

  new Ajax();