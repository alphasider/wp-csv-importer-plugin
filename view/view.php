<?php


  use NGS\CSV;
  use NGS\Product;
  use NGS\View;

?>

  <h1>Import products from CSV</h1>

<?php
//      $csv_data = CSV::get_csv('file.csv');
//      $prod = new Product();
//      $res = $prod->create_all_products($csv_data);
//      print_r($res);
  $files = new View();
?>

  <div class="container" style="display: flex">
    <div class="left-column">

      <h3>File ready for import</h3>
      <?php echo $files->show_files_to_import() ?>

      <h3>Imported files for the last 7 days</h3>
      <?php echo $files->show_imported_files() ?>
    </div>

    <div class="right-column" style="border: 1px solid red; padding: 10px">

    </div>

  </div>

<?php


  add_action('admin_print_footer_scripts', 'import_csv_javascript', 99);
  function import_csv_javascript() {
    ?>
    <script>
      jQuery(document).ready(function ($) {
        jQuery('.import_now').click(function () {
          let data = {
            action: 'import_csv',
            fileName: jQuery(this).attr('data-filename')
          };

          jQuery.post(ajaxurl, data, function (response) {
            // alert('Получено с сервера: ' + response);
            console.log(response);
            jQuery('.right-column').html(response);
          });
        })
      });
    </script>
    <?php
  }


?>