<?php

  namespace NGS;

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
     * @return array
     */
    public static function get_csv($filename, $import_type) {
      $output_array = [];
      $path_to_file = self::get_proper_file($filename, $import_type);

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
     * Builds a table from all the data.
     * For developing purposes only
     *
     * @return string
     */
    public static function build_table() {
      $data = self::get_csv();
      $table = '<table border="1">';
      foreach ($data as $row) {
        $table .= '<tr>';
        foreach ($row as $column) {
          $table .= "<td>{$column}</td>";
        }
        $table .= '</tr>';
      }
      $table .= '</table>';
      return $table;
    }

    /**
     * Searches for the attribute and returns whole column
     *
     * @param $attribute
     * @return array
     */
    public static function search_attribute($attribute) {
      $data = self::get_csv();
      $product_name_column_index = array_search($attribute, $data[0]);
      $result = [];

      foreach ($data as $row_index => $row) {
        foreach ($row as $column_index => $column) {
          if ($column_index == $product_name_column_index && $row_index != 0)
            $result[] .= $column;
        }
      }

      return $result;
    }

    public static function move_imported_file($file) {
      rename(plugin_dir_path(__DIR__) . "tmp/{$file}", plugin_dir_path(__DIR__) . "feed/{$file}");
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

    public static function get_sku_from_csv($csv_data){
      $csv_data_sku = [];
      foreach ($csv_data as $row) {
        $csv_data_sku[] = $row[2];
      }
      return $csv_data_sku;
    }
  }