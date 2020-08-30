<?php


  namespace NGS;


  class AdminPage {
    public function __construct() {
      add_action('admin_menu', [$this, 'add_menu']);
    }

    public function add_menu() {
      add_menu_page(
        'CSV Importer',
        'CSV Importer',
        'manage_options',
        'csv_importer',
        [$this, 'content'],
        'dashicons-schedule',
        3
      );
    }

    public function content() {
      require_once(plugin_dir_path(__FILE__) . 'CSV.php');
      require_once(plugin_dir_path(__DIR__) . 'view.php');
    }
  }