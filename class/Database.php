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
      self::delete_the_oldest_file();

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
     * Gets the oldest (imported) file's id & name
     *
     * @param $files
     * @return array|null
     */
    public static function get_the_oldest_file($files) {
      $old_files = [];
      foreach ($files as $file) {
        $old_files[$file['id']] = $file['file_name'];
      }

      // Get the oldest file id & name in the DB
      $oldest_file_id = min(array_keys($old_files));
      $oldest_file_name = $old_files[$oldest_file_id];

      return [
        'id' => $oldest_file_id,
        'name' => $oldest_file_name
      ];
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

    /**
     * Deletes the oldest file from the DB if it is necessary
     */
    public static function delete_the_oldest_file() {
      $is_there_file_to_delete = self::check_files_to_delete();

      if ($is_there_file_to_delete) {
        $file_to_delete = self::get_the_oldest_file(self::get_all_files_list());
        self::delete_file($file_to_delete['id']);
        return $file_to_delete;
      }
    }
  }