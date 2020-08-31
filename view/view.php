<?php

  use NGS\CSV;
  use NGS\Product;
  use NGS\Helper;

?>

<h1>Import products from CSV</h1>

<?php
    $csv_data = CSV::get_csv();
    $product = new Product();

    $product->create_products($csv_data);

?>
