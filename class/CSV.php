<?php

  namespace NGS;

  use NGS\Notification;

  /**
   * Class CSV
   * @package NGS
   */
  class CSV {

    /**
     * Gets array of data from CSV file
     *
     * @param $filename
     * @param $import_type
     * @return array|void
     */
    public static function get_csv($filename, $import_type) {
      $path_to_file = self::get_proper_file($filename, $import_type);

      if (filesize($filename) == 0) {
        unlink($filename);
        return;
      }

      $output_array = [];

      $csv_file = fopen(plugin_dir_path(__DIR__) . $path_to_file, "r");

      if ($csv_file) {
        while (($csv_data = fgetcsv($csv_file, 1000, ',')) !== FALSE) {
          $output_array[] = $csv_data;
        }
        fclose($csv_file);
      }

      array_shift($output_array);
      return $output_array;
    }

    /**
     * Gets file size
     *
     * @param $file
     * @return false|int
     */
    public static function get_file_size($file){
      return filesize($file);
    }

    /**
     * Moves files
     *
     * @param $file
     */
    public static function move_imported_file($file) {
      $copied = copy(plugin_dir_path(__DIR__) . "tmp/{$file}", plugin_dir_path(__DIR__) . "feed/{$file}");

      if ($copied) {
        unlink(plugin_dir_path(__DIR__) . "tmp/{$file}");
      }
    }

    /**
     * Sets file's last modified date today
     *
     * @param $filename
     * @param $import_type
     * @return bool
     */
    public static function set_modified_date($filename, $import_type) {
      // returns "dir/file"
      $file = self::get_proper_file($filename, $import_type);
      $full_path = plugin_dir_path(__DIR__) . $file;
      return touch($full_path);
    }

    public static function get_files_last_modified_time($filename, $import_type) {
      $file = self::get_proper_file($filename, $import_type);
      $full_path = plugin_dir_path(__DIR__) . $file;
      $timestamp = filemtime($full_path);
      return date('Y-m-N H:i:s', $timestamp);
    }

    /**
     * Gets proper file from proper directory
     * returns as: folder_name/file_name
     *
     * @param $filename
     * @param $import_type
     * @return string
     */
    public static function get_proper_file($filename, $import_type) {
      if ($import_type == 'new') {
        $import_type = 'tmp';
      } else {
        $import_type = 'feed';
      }

      return (string)"{$import_type}/{$filename}";
    }

  }