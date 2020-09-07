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
    <div class="table-wrapper">
      <h2>File ready for import</h2>
      <?php echo $files->show_files_to_import('new') ?>
    </div>
    <div class="table-wrapper">
      <h2>Imported files for the last 7 days</h2>
      <?php echo $files->show_files_to_import('recovery') ?>
    </div>
  </div>

  <div class="right-column">
    <h2>Results</h2>
    <div class="notifications-area">
      <div class="default-text">The results will be displayed here</div>
    </div>
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

            jQuery('.notifications-area').html(response);


          });
        })
      });
    </script>
    <?php
  }

?>

<style>
    .container {
        display: flex;
        justify-content: space-between;
        margin-right: 20px;
    }

    .left-column {
        width: 33%;
    }

    .table-wrapper {
        margin-bottom: 45px;
    }

    .right-column {
        width: 65%;
        justify-items: flex-end;
    }

    .notifications-area {
        max-width: 100%;
        height: 100%;
        padding: 10px 13px;
        border-radius: 10px;
        background-color: #fff;
    }
.default-text{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    font-size: 18px;
    color: rgba(0,0,0,.3);
}
    .notification{
        padding: .75rem 1.25rem;
        font-size: 15px;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
    .notification_success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .notification_failure {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    table {
        border-spacing: 1;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        margin: 0 auto;
        position: relative;
    }

    table * {
        position: relative;
    }

    table td, table th {
        padding: 10px 20px;
    }

    table thead tr {
        height: 60px;
        background: #36304a;
    }

    table tbody tr {
        height: 50px;
    }

    table tbody tr:last-child {
        border: 0;
    }

    table td, table th {
        text-align: left;
        font-family: sans-serif;
    }

    table td.l, table th.l {
        text-align: right;
    }

    table td.c, table th.c {
        text-align: center;
    }

    table td.r, table th.r {
        text-align: center;
    }

    table td:last-child {
        text-align: right;
    }


    .table100-head th {
        font-family: OpenSans-Regular;
        font-size: 18px;
        color: #fff;
        line-height: 1.2;
        font-weight: unset;
    }

    tbody tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    tbody tr {
        font-family: OpenSans-Regular;
        font-size: 15px;
        color: #808080;
        line-height: 1.2;
        font-weight: unset;
    }

    tbody tr:hover {
        color: #555555;
        background-color: #f5f5f5;
        cursor: pointer;
    }
tbody a{
    outline: transparent;
}
    tbody a:hover {
        text-decoration: none;
    }

</style>
