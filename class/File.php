<?php


  namespace NGS;
  require('Database.php');

  class File {

    /**
     * Gets files from a given folder
     * Returns a list (array) of files excluding current and parent directory paths (dots)
     *
     * @param string $directory
     * @return array
     */
    public static function get_files_from(string $directory) {
      $path = plugin_dir_path(__DIR__) . $directory;
      $directory_content = scandir($path);
      $files = array_diff($directory_content, [".", ".."]); // Return a files list without dots (current and parent directory paths)

      return array_values($files); // Return re-indexed array (index starts with 0)
    }

    /**
     * Moves files
     *
     * @param $file
     * @return string
     */
    public static function move_imported_file($file) {
      $copied = copy(plugin_dir_path(__DIR__) . "tmp/{$file}", plugin_dir_path(__DIR__) . "feed/{$file}");

      if ($copied) {
        unlink(plugin_dir_path(__DIR__) . "tmp/{$file}");
        return 'File moved successfully';
      }
      return 'Oops?! Something went wrong!';
    }

    /**
     * Deletes files
     *
     * @param $file
     * @return string
     */
    public static function delete_file($file){
      if(unlink(plugin_dir_path(__DIR__) . "feed/{$file}")){
        return "File deleted successfully!";
      }
      return "Error: File not deleted";
    }

    /**
     * Handles imported files:
     * 1. Moves file to imported files folder
     * 2.
     *
     * @param $file
     * @param $import_type
     */
    public static function handle_imported_files($file, $import_type) {

      // Delete unnecessary file from DB if there is and returns an array with its name and id
      $file_to_delete = Database::delete_the_oldest_file();
      print_r( $file_to_delete);
      // Delete a file from the directory
      self::delete_file($file_to_delete['name']);

      $current_date = Helper::get_current_date();
      $is_file_moved = self::move_imported_file($file);
      $moved_file_id_in_db = Database::add_imported_file_to_db($file, $current_date);

      if ((int)$moved_file_id_in_db && $is_file_moved) {
        Notification::file_import_success($file);
      } else {
        Notification::file_import_failure($file);
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
      $file = self::get_proper_file($filename, $import_type);
      $full_path = plugin_dir_path(__DIR__) . $file;
      return touch($full_path);
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

    public static function get_files_last_modified_time($filename, $import_type) {
      $file = self::get_proper_file($filename, $import_type);
      $full_path = plugin_dir_path(__DIR__) . $file;
      $timestamp = filemtime($full_path);
      return date('Y-m-N H:i:s', $timestamp);
    }

    /**
     * Gets file size
     *
     * @param $file
     * @return false|int
     */
    public static function get_file_size($file) {
      return filesize($file);
    }

  }