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
      $import_type = $_POST['importType']; // Might be: New Import or Re-Import

      $csv_data = CSV::get_csv($file_to_import, $import_type);
      $product = new Product();
      $all_products = $product->import_all_products($csv_data);

      echo $all_products;

      // Move CSV file after import
      CSV::move_imported_file($file_to_import);

      // Set modified time to imported file's
      CSV::set_modified_date($file_to_import, $import_type);

      wp_die();
    }
  }

  new Ajax();