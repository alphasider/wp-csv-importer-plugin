<?php

  use NGS\CSV;
  use NGS\Product;

?>

<h1>Import products from CSV</h1>

<?php
  $csv_data = CSV::get_csv();
  $prod = new Product();

  $prod->create_all_products($csv_data);

?>
