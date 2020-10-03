<?php

  use NGS\CSV;

  /**
   * Plugin Name: CSV importer
   * Description: Adds a custom admin pages with sample styles and scripts.
   * Version: 2.0.
   */
  register_activation_hook(__FILE__, 'create_import_log_table');
  function create_import_log_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'ci_import_logs';

    $sql = "CREATE TABLE $table_name (
        id smallint (5) NOT NULL AUTO_INCREMENT,
        file_name varchar (50) NOT NULL,
        import_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        last_modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        imported bit (1) DEFAULT NULL,
        UNIQUE KEY id (id)
	  ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }

  function csv_importer_init() {
    require plugin_dir_path(__FILE__) . 'class/AdminPage.php';
    require plugin_dir_path(__FILE__) . 'class/Product.php';
    require plugin_dir_path(__FILE__) . 'class/CSV.php';
    require plugin_dir_path(__FILE__) . 'class/Ajax.php';
    require plugin_dir_path(__FILE__) . 'class/CustomFields.php';
    require plugin_dir_path(__FILE__) . 'class/File.php';
  }

  csv_importer_init();
