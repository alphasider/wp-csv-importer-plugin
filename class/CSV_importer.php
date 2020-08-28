<?php


  class CSV_importer {
    public static function get_csv() {
      $file = plugin_dir_path(__DIR__);
      $csv_file = fopen($file . 'tmp/file.csv', "r");
      $row = 1;
      echo $file . "<br> ";

      if ($csv_file) {

        echo "<table border='1'>";
        while (($data = fgetcsv($csv_file, 1000, ",")) !== FALSE) {

          $num = count($data);

          echo "<p> $num fields in line $row: <br /></p>\n";

          $row++;
          print_r ($data);

//          for ($i = 0; $i < $num; $i++) {
//            echo $data[$i] ;
//          }

        }
        echo "</table>";
        fclose($csv_file);

      }
    }
  }