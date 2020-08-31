<?php

  use NGS\CSV;
  use NGS\Product;

?>

<h1>Import products from CSV</h1>

<?php
  //  $product = new Product();
  //  $product->set_product_main_params(
  //    'name',
  //    'desc',
  //    '123',
  //    123,
  //    [26,30],
  //    'publish'
  //  );
  //  echo $product->save_product();
  echo '<pre>';
  print_r(CSV::search_attribute('ShowroomTitle'));
  echo '</pre>';

?>
