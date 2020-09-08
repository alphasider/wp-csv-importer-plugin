<?php

  use NGS\CSV;

  /**
   * Plugin Name: CSV importer
   * Description: Allows to import products from csv files automatically.
   */

  function csv_importer_init() {
    require plugin_dir_path(__FILE__) . 'class/AdminPage.php';
    require plugin_dir_path(__FILE__) . 'class/Product.php';
    require plugin_dir_path(__FILE__) . 'class/CSV.php';
    require plugin_dir_path(__FILE__) . 'class/Ajax.php';


    new \NGS\AdminPage();
  }

  csv_importer_init();