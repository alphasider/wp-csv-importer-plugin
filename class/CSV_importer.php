<?php


  class CSV_importer {
    public static function get_csv() {
      $output = '<table border="1">';

      $path = plugin_dir_path(__DIR__);
      $csv_file = fopen($path . 'tmp/file.csv', "r");

      if ($csv_file) {
        while (($csv_data = fgetcsv($csv_file, 1000, ',')) !== FALSE) {

          $output .= "<tr>";
          $output .= '  <td>' . $csv_data[42] . '</td>';
          $output .= '  <td>' . $csv_data[43] . '</td>';
          $output .= "</tr>";
        }

        fclose($csv_file);
      }
      $output .= '</table>';
      return $output;
    }
  }