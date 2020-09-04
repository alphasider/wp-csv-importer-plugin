<?php


  use NGS\CSV;
  use NGS\Product;
  use NGS\View;

?>

  <h1>Import products from CSV</h1>

<?php
  //    $csv_data = CSV::get_csv();
  //    $prod = new Product();
  //    $prod->create_all_products($csv_data);
?>

  <div class="container">

    <h3>File ready for import</h3>
    <?php echo View::show_files_to_import() ?>

    <h3>Imported files for the last 7 days</h3>
    <?php echo View::show_imported_files() ?>

  </div>

<?php


  add_action('admin_print_footer_scripts', 'import_csv_javascript', 99);
  function import_csv_javascript() {
    ?>
    <script>
      jQuery(document).ready(function ($) {
        jQuery('.import_now').click(function () {

          console.log(jQuery(this).attr('data-filename'))
          let data = {
            action: 'import_csv',
            fileName: jQuery(this).attr('data-filename')
          };

          jQuery.post(ajaxurl, data, function (response) {
            alert('Получено с сервера: ' + response);
          });
        })
      });
    </script>
    <?php
  }


?>