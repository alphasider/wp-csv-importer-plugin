<?php


  namespace NGS;

  /**
   * Class View
   * @package NGS
   */
  class View {

    /**
     * @param string $directory
     * @return array
     */
    public static function get_files_from(string $directory = '') {
      $path = plugin_dir_path(__DIR__) . $directory;
      $directory_content = scandir($path);
      $files = array_diff($directory_content, ['.', '..']); // Return a files list without dots (current and parent directory paths)

      return array_values($files); // Return re-indexed array (index starts with 0)
    }

    /**
     * @return string
     */
    public static function show_files_to_import() {
      $files = self::get_files_from('tmp');
      $output = "";
      if (count($files) > 1) {
        $output .= "<table>";
        foreach ($files as $file) {
          $output .= "<tr>";
          $output .= "  <td> {$file} </td>";
          $output .= "  <td> <a href='#' class='import_now' data-filename='{$file}'>Import now</a> </td>";
          $output .= "</tr>";
        }
        $output .= "</table>";
      } else {
        $output = "There is no files to import";
      }
      return $output;
    }

    /**
     * @return string
     */
    public static function show_imported_files() {
      $files = self::get_files_from('feed');
      $output = "";

      if (count($files) > 1) {
        $output .= "<table>";
        foreach ($files as $file) {
          $output .= "<tr>";
          $output .= "  <td> {$file} </td>";
          $output .= "  <td> <a href='#'>Reimport</a></td>";
          $output .= "</tr>";
        }
        $output .= "</table>";
      } else {
        $output = "No files have been imported yet";
      }

      return $output;
    }

  }

  new View();