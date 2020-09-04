<?php


  namespace NGS;


  class AdminPage {
    /**
     * AdminPage constructor.
     */
    public function __construct() {
      add_action('admin_menu', [$this, 'add_menu']);
    }

    /**
     * Add menu item
     */
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

    /**
     * Admin page content
     */
    public function content() {
      require_once(plugin_dir_path(__FILE__) . 'CSV.php');
      require_once(plugin_dir_path(__FILE__) . 'Check.php');
      require_once(plugin_dir_path(__DIR__) . 'view/view.php');
    }
  }