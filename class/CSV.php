<?php

  namespace NGS;

  class CSV {
    /**
     * Gets array of data from CSV file
     *
     * @return array
     */
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
  }