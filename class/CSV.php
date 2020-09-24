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
      $path_to_file = File::get_proper_file($filename, $import_type);

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

  }