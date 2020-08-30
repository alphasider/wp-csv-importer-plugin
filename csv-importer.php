<?php

  /**
   * Plugin Name: CSV importer
   * Description: Adds a custom admin pages with sample styles and scripts.
   */

  function csv_importer_init() {
    require plugin_dir_path(__FILE__) . 'class/AdminPage.php';
    new \NGS\AdminPage();
  }

  csv_importer_init();
