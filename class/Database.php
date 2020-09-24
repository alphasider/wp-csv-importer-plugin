<?php


  namespace NGS;


  class Database {

    /**
     * TODO:
     *  1. Get data
     */
    /**
     * @param $filename
     * @param $import_date
     * @param $last_modified
     * @return int
     */
    public static function add_moved_file_to_db($filename, $import_date, $last_modified) {
      global $wpdb;

      $table = $wpdb->prefix . 'ci_import_logs';

      $wpdb->insert(
        $table,
        [
          'file_name' => $filename,
          'import_date' => $import_date,
          'last_modified' => $last_modified,
          'imported' => 1
        ]
      );
      return $wpdb->insert_id;

    }
  }