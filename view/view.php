<?php

  use NGS\CSV;
  use NGS\Check;
  use NGS\Product;

?>

<h1>Import products from CSV</h1>

<?php
  $csv_data = CSV::get_csv();
    $prod = new Product();

$prod->create_all_products($csv_data);

//echo Check::get_existing_product_id('JR8778JDB41');
//echo '<br>';
//var_dump(Check::check_modified_date(1115, "2020-01-09 10:47:22.647000000","2020-01-26 13:25:04.180000000"));

?>
