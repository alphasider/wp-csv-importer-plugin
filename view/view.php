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
  new \NGS\CustomFields();
?>

<div class="container" style="display: flex">
  <div class="left-column">
    <div class="table-wrapper">
      <h2 class="heading-2">File ready for import</h2>
      <?php echo $files->show_files_to_import('new') ?>
    </div>
    <div class="table-wrapper">
      <h2 class="heading-2">Imported files for the last 7 days</h2>
      <?php echo $files->show_files_to_import('restore') ?>
    </div>
  </div>

  <div class="right-column">
    <h2 class="heading-2">Results</h2>
    <div class="right-column__content">
      <div class="notifications-area">
        <div class="default-text show">The results will be displayed here</div>
      </div>
      <div class="loader-wrapper">
        <div class="loader-text">Please be patient, it may take a long time</div>
        <div class="loader "></div>
      </div>
    </div>
  </div>

</div>

<?php

  add_action('admin_print_footer_scripts', 'import_csv_javascript', 99);
  function import_csv_javascript() {
    ?>
    <script>
      jQuery(document).ready(function ($) {
        jQuery('.import-btn').click(function () {
          jQuery('.default-text').removeClass('show');
          jQuery('.loader-wrapper').addClass('show');
          let data = {
            action: 'import_csv',
            fileName: jQuery(this).attr('data-filename'),
            importType: jQuery(this).attr('data-importType')
          };

          jQuery.post(ajaxurl, data, function (response) {
            console.log(response);
            jQuery('.loader-wrapper').removeClass('show');

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
        margin-right: 30px;
    }

    .left-column {
        width: 40%;
    }

    .heading-2 {
        font-size: 22px;
    }

    .table-wrapper {
        margin-bottom: 45px;
    }

    .right-column {
        width: 57%;
    }

    .right-column__content {
        height: 100%;
        justify-items: flex-end;
        position: relative;

    }

    .notifications-area {
        max-width: 100%;
        height: 100%;
        max-height: 70vh;
        padding: 10px 13px;
        border-radius: 10px;
        background-color: #fff;
        overflow: auto;
        position: relative;
    }

    .default-text {
        height: 100%;
        font-size: 18px;
        color: rgba(0, 0, 0, .3);
    }

    .default-text {
        display: none;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

    }

    .default-text.show,
    .loader-wrapper.show {
        display: flex;
    }

    .loader-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        display: none;
        flex-direction: column-reverse;
        justify-content: center;
        align-items: center;
        /*background-color: rgba(255, 255, 255, .5);*/
    }

    .loader {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 8px solid #f3f3f3;
        border-top: 8px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        margin-bottom: 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .notification {
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
        height: 50px;
        background: #36304a;
    }

    table tbody tr {
        /*height: 50px;*/
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


    .table-head th {
        font-family: OpenSans-Regular, sans-serif;
        font-size: 18px;
        color: #fff;
        line-height: 1.2;
        font-weight: unset;
    }

    tbody tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    tbody tr {
        font-family: OpenSans-Regular, sans-serif;
        font-size: 15px;
        color: #808080;
        line-height: 1.2;
        font-weight: unset;
    }

    tbody tr:hover {
        color: #555555;
        background-color: #f5f5f5;
    }

    .import-btn {
        display: inline-block;
        font-weight: 400;
        color: #36304a;
        border: 1px solid #36304a;
        background-color: transparent;
        text-align: center;
        vertical-align: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        padding: 5px 15px;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        text-decoration: none;
        cursor: pointer;
    }

    tbody a:hover {
        color: #fff;
        background-color: #36304a;
        border-color: #36304a;
    }

    .column3 {
        text-align: center;
    }


</style>
