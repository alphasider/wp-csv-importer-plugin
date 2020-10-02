<?php


  namespace NGS;


  class Database {

    public static $table_name = 'ci_import_logs';

    /**
     * Adds a new file to the DB
     *
     * @param $filename
     * @param $import_date
     * @return int
     */
    public static function add_imported_file_to_db($filename, $import_date) {
      $is_there_file_to_delete = self::check_files_to_delete();
      if($is_there_file_to_delete){
        $file_to_delete = self::get_the_oldest_file(self::get_all_files_list());
        self::delete_file($file_to_delete);
      }
      
      global $wpdb;
      $table = $wpdb->prefix . self::$table_name;

      $wpdb->insert(
        $table,
        [
          'file_name' => $filename,
          'import_date' => $import_date,
          'last_modified' => $import_date,
          'imported' => 1
        ]
      );
      return $wpdb->insert_id;

    }

    /**
     * Gets list of all imported files
     *
     * @return array|object|null
     */
    public static function get_all_files_list() {
      global $wpdb;
      $table = $wpdb->prefix . self::$table_name;

      // Return the result as an associative array
      return $wpdb->get_results(
        "
                SELECT *
                FROM $table
              ",
        ARRAY_A
      );
    }

    /**
     * Gets the oldest (imported) file id
     *
     * @param $files
     * @return array
     */
    public static function get_the_oldest_file($files) {
      $ids = [];
      foreach ($files as $file) {
        $ids[] = $file['id'];
      }
      return min($ids);
    }

    /**
     * Delete file from the DB
     * @param $to_delete_file_id
     */
    public static function delete_file($to_delete_file_id) {
      global $wpdb;
      $table = $wpdb->prefix . self::$table_name;
      $wpdb->delete($table, ['id' => $to_delete_file_id]);
    }

    /**
     * Checks if there files to delete
     * 
     * @return bool
     */
    public static function check_files_to_delete() {
      $all_imported_files = self::get_all_files_list();
      $files_count = count($all_imported_files);
      return $files_count >= 7;
    }
  }