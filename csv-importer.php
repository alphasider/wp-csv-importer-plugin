<?php

  /*

  Plugin Name: CSV importer

  Description: Adds a custom admin pages with sample styles and scripts.

  Version: 1.0.0

  Author: Artbees

  Author URI: http://artbees.net

  Text Domain: my-custom-admin-page

  */


  function my_admin_menu() {

    add_menu_page(

      __('CSV Importer', 'my-textdomain'),

      __('CSV Importer', 'my-textdomain'),

      'manage_options',

      'csv_importer',

      'my_admin_page_contents',

      'dashicons-schedule',

      3

    );

  }


  add_action('admin_menu', 'my_admin_menu');


  function my_admin_page_contents() {

    ?>

    <h1>

      <?php esc_html_e('CSV Import.'); ?>

    </h1>
    <?php

    require_once(plugin_dir_path(__FILE__) . 'class/CSV_importer.php');
    CSV_importer::get_csv();


  }


  function register_my_plugin_scripts() {

    wp_register_style('my-plugin', plugins_url('ddd/css/plugin.css'));

    wp_register_script('my-plugin', plugins_url('ddd/js/plugin.js'));

  }

  add_action('admin_enqueue_scripts', 'register_my_plugin_scripts');

  function load_my_plugin_scripts($hook) {

// Load only on ?page=sample-page

    if ($hook != 'toplevel_page_sample-page') {

      return;

    }

// Load style & scripts.

    wp_enqueue_style('my-plugin');

    wp_enqueue_script('my-plugin');

  }

  add_action('admin_enqueue_scripts', 'load_my_plugin_scripts');