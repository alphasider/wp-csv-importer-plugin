<?php


  namespace NGS;

  /**
   * Class View
   * @package NGS
   */
  class View {

    public $imported_files_dir = 'feed';
    public $files_to_import_dir = 'tmp';

    /**
     * @param string $directory
     * @return array
     */
    public function get_files_from(string $directory) {
      $path = plugin_dir_path(__DIR__) . $directory;
      $directory_content = scandir($path);
      $files = array_diff($directory_content, [".", ".."]); // Return a files list without dots (current and parent directory paths)

      return array_values($files); // Return re-indexed array (index starts with 0)
    }

    /**
     * Show files to import
     *
     * @param string $import_type
     * @return string
     */
    public function show_files_to_import(string $import_type) {

      $files = '';

      if ($import_type == 'new') {
        $files = $this->get_files_from($this->files_to_import_dir);
      } else if ($import_type == 'restore') {
        $files = $this->get_files_from($this->imported_files_dir);
      }


      $output = "";

      if (count($files) >= 1) {

        $output .= "<table>";

        // Add table header to imported files table
        if ($import_type !== 'new') {
          $output .= "  <thead>";
          $output .= "    <tr class='table-head'>";
          $output .= "      <th class='column1'>File name</th>";
          $output .= "      <th class='column2'>Last modified at</th>";
          $output .= "      <th class='column3'>Action</th>";
          $output .= "    </tr>";
          $output .= "  </thead>";
        }

        $output .= "  <tbody>";

        foreach ($files as $file) {
          $output .= "<tr>";
          $output .= "  <td> {$file} </td>";

          if ($import_type !== 'new') {
            $output .= "<td> " . CSV::get_files_last_modified_time($file, $import_type) . "</td>";
          };

          $output .= "  <td>";
            $output .= "<button class='import-btn' data-filename='{$file}' data-importType='{$import_type}'>Import</button>";
//          if ($import_type !== 'new') {
//            $output .= "<button class='import-btn' data-filename='{$file}' data-importType='{$import_type}'>Import</button>";
//          } else {
//            $output .= " <div class='scheduled-label'> Scheduled</div > ";
//          }
          $output .= "     </td > ";
          $output .= "</tr > ";
        }

        $output .= "  </tbody > ";
        $output .= "</table > ";
      } else {
        $output = "There is no files to import";
      }
      return $output;
    }
  }

  new View();