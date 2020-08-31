<?php

  namespace NGS;

  class CSV {
    public static function get_csv() {
      $output_array = [];

      $path = plugin_dir_path(__DIR__);
      $csv_file = fopen($path . 'tmp/file.csv', "r");

      if ($csv_file) {
        while (($csv_data = fgetcsv($csv_file, 1000, ',')) !== FALSE) {
          $output_array[] = $csv_data;
        }
        fclose($csv_file);
      }

      return $output_array;
    }

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

    public static function search_attribute($attribute) {
      $data = self::get_csv();
      $product_name_column_index = array_search($attribute, $data[0]);
      $result = [];

      foreach ($data as $row) {
        foreach ($row as $index => $column) {
          if ($index == $product_name_column_index)
            $result[] .= $column;
        }
      }
      return $result;
    }
  }