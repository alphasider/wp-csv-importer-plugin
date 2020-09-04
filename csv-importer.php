<?php

  use NGS\CSV;

  /**
   * Plugin Name: CSV importer
   * Description: Adds a custom admin pages with sample styles and scripts.
   */

  function csv_importer_init() {
    require plugin_dir_path(__FILE__) . 'class/AdminPage.php';
    require plugin_dir_path(__FILE__) . 'class/Product.php';
    require plugin_dir_path(__FILE__) . 'class/CSV.php';

    new \NGS\AdminPage();
  }

  csv_importer_init();

  /**
   * Ajax
   */



  add_action('wp_ajax_import_csv', 'import_csv_callback');
  function import_csv_callback() {
    $file_to_import = $_POST['fileName'];

    $csv_data = CSV::get_csv('file.csv');

    echo $csv_data;

    wp_die();
  }