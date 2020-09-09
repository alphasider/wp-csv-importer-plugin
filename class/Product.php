<?php


  namespace NGS;

  use WC_Product;
  use WC_Product_Attribute;
  use WC_Product_External;
  use WC_Product_Grouped;
  use WC_Product_Simple;
  use WC_Product_Variable;

  /**
   * Class Product
   * @package NGS
   */
  class Product {

    /**
     * Creates and saves product with standard info, attributes, images, price etc.
     *
     * @param $args
     * @return false|int
     * @throws \WC_Data_Exception
     */
    private function create_product($args) {
      global $woocommerce;

//      if (!function_exists('wc_get_product_object_type') && !function_exists('wc_prepare_product_attributes'))
//        return false;

      // Get an empty instance of the product object (defining it's type)
      $product = $this->wc_get_product_object_type($args['type']);
      if (!$product)
        return false;

      // Product name (Title) and slug
      $product->set_name($args['name']); // Name (title).
      if (isset($args['slug']))
        $product->set_name($args['slug']);

      // Description and short description:
      $product->set_description($args['description']);
      $product->set_short_description($args['short_description']);

      // Status ('publish', 'pending', 'draft' or 'trash')
      $product->set_status(isset($args['status']) ? $args['status'] : 'publish');

      // Visibility ('hidden', 'visible', 'search' or 'catalog')
      $product->set_catalog_visibility(isset($args['visibility']) ? $args['visibility'] : 'visible');

      // Featured (boolean)
      $product->set_featured(isset($args['featured']) ? $args['featured'] : false);

      // Virtual (boolean)
      $product->set_virtual(isset($args['virtual']) ? $args['virtual'] : false);

      // Prices
      $product->set_regular_price($args['regular_price']);
      $product->set_sale_price(isset($args['sale_price']) ? $args['sale_price'] : '');
      $product->set_price(isset($args['sale_price']) ? $args['sale_price'] : $args['regular_price']);
      if (isset($args['sale_price'])) {
        $product->set_date_on_sale_from(isset($args['sale_from']) ? $args['sale_from'] : '');
        $product->set_date_on_sale_to(isset($args['sale_to']) ? $args['sale_to'] : '');
      }

      // Downloadable (boolean)
      $product->set_downloadable(isset($args['downloadable']) ? $args['downloadable'] : false);
      if (isset($args['downloadable']) && $args['downloadable']) {
        $product->set_downloads(isset($args['downloads']) ? $args['downloads'] : array());
        $product->set_download_limit(isset($args['download_limit']) ? $args['download_limit'] : '-1');
        $product->set_download_expiry(isset($args['download_expiry']) ? $args['download_expiry'] : '-1');
      }

      // Taxes
      if (get_option('woocommerce_calc_taxes') === 'yes') {
        $product->set_tax_status(isset($args['tax_status']) ? $args['tax_status'] : 'taxable');
        $product->set_tax_class(isset($args['tax_class']) ? $args['tax_class'] : '');
      }

      if (isset($args['sku'])) {
        $product->set_sku($args['sku']);
      }

      // SKU and Stock (Not a virtual product)
      if (isset($args['virtual']) && !$args['virtual']) {
        $product->set_sku(isset($args['sku']) ? $args['sku'] : '');
        $product->set_manage_stock(isset($args['manage_stock']) ? $args['manage_stock'] : false);
        $product->set_stock_status(isset($args['stock_status']) ? $args['stock_status'] : 'instock');
        if (isset($args['manage_stock']) && $args['manage_stock']) {
          $product->set_stock_status($args['stock_qty']);
          $product->set_backorders(isset($args['backorders']) ? $args['backorders'] : 'no'); // 'yes', 'no' or 'notify'
        }
      }

      // Sold Individually
      $product->set_sold_individually(isset($args['sold_individually']) ? $args['sold_individually'] : false);

      // Weight, dimensions and shipping class
      $product->set_weight(isset($args['weight']) ? $args['weight'] : '');
      $product->set_length(isset($args['length']) ? $args['length'] : '');
      $product->set_width(isset($args['width']) ? $args['width'] : '');
      $product->set_height(isset($args['height']) ? $args['height'] : '');
      if (isset($args['shipping_class_id']))
        $product->set_shipping_class_id($args['shipping_class_id']);

      // Upsell and Cross sell (IDs)
      $product->set_upsell_ids(isset($args['upsells']) ? $args['upsells'] : '');
      $product->set_cross_sell_ids(isset($args['cross_sells']) ? $args['upsells'] : '');

      // Attributes et default attributes
      if (isset($args['attributes']))
        $product->set_attributes($this->wc_prepare_product_attributes($args['attributes']));
      if (isset($args['default_attributes']))
        $product->set_default_attributes($args['default_attributes']); // Needs a special formatting

      // Reviews, purchase note and menu order
      $product->set_reviews_allowed(isset($args['reviews']) ? $args['reviews'] : false);
      $product->set_purchase_note(isset($args['note']) ? $args['note'] : '');
      if (isset($args['menu_order']))
        $product->set_menu_order($args['menu_order']);

      // Product categories and Tags
      if (isset($args['category_ids']))
        $product->set_category_ids($args['category_ids']);
      if (isset($args['tag_ids']))
        $product->set_tag_ids($args['tag_ids']);


      // Images and Gallery
      $product->set_image_id(isset($args['image_id']) ? $args['image_id'] : "");
      $product->set_gallery_image_ids(isset($args['gallery_ids']) ? $args['gallery_ids'] : array());

      ## --- SAVE PRODUCT --- ##
      return $product->save();
    }

    /**
     * Sets custom fields values
     *
     * @param $product_id
     * @param $csv_data
     */
    private function set_custom_fields_values($product_id, $csv_data) {
      $vin = $csv_data[1];
      $stock_id = $csv_data[2];
      $make = $csv_data[4];
      $model = $csv_data[5];
      $year = $csv_data[3];
      $mileage = $csv_data[8];
      $engine = $csv_data[10];
      $color = $csv_data[22];
      $interior_color = $csv_data[23];
      $transmission = $csv_data[11];
      $drivetrain = $csv_data[12];
      $doors = $csv_data[13];
      $condition = $csv_data[7];
      $body_type = $csv_data[14];
      $trim = $csv_data[6];
      $description = $csv_data[32];
      $standard_features = $csv_data[30];
      $video = $csv_data[37];

      // Adding data to the custom fields
      update_field('field_vin', $vin, $product_id);
      update_field('field_stock_id', $stock_id, $product_id);
      update_field('field_make', $make, $product_id);
      update_field('field_model', $model, $product_id);
      update_field('field_year', $year, $product_id);
      update_field('field_mileage', $mileage, $product_id);
      update_field('field_engine', $engine, $product_id);
      update_field('field_color', $color, $product_id);
      update_field('field_interior_color', $interior_color, $product_id);
      update_field('field_transmission', $transmission, $product_id);
      update_field('field_drivetrain', $drivetrain, $product_id);
      update_field('field_doors', $doors, $product_id);
      update_field('field_condition', $condition, $product_id);
      update_field('field_body_type', $body_type, $product_id);
      update_field('field_trim', $trim, $product_id);
      update_field('field_description', $description, $product_id);
      update_field('field_standard_features', $standard_features, $product_id);
      update_field('field_video', $video, $product_id);
    }

    /**
     * Utility function that returns the correct product object instance
     *
     * @param $type
     * @return false|WC_Product|WC_Product_External|WC_Product_Grouped|WC_Product_Simple|WC_Product_Variable
     */
    private function wc_get_product_object_type($type) {
      // Get an instance of the WC_Product object (depending on his type)
      if (isset($args['type']) && $args['type'] === 'variable') {
        $product = new WC_Product_Variable();
      } elseif (isset($args['type']) && $args['type'] === 'grouped') {
        $product = new WC_Product_Grouped();
      } elseif (isset($args['type']) && $args['type'] === 'external') {
        $product = new WC_Product_External();
      } else {
        $product = new WC_Product_Simple(); // "simple" By default
      }

      if (!is_a($product, 'WC_Product'))
        return false;
      else
        return $product;
    }

    /**
     * Main method. Imports all products from CSV file
     *
     * @param $csv_data
     */
    public function import_all_products($csv_data) {

      foreach ($csv_data as $product_index => $product_data) {
        $sku = $product_data[2];
        $category_id = $product_data[44];
        $vehicle_updated_time = $product_data[42];
        $image_updated_time = $product_data[43];
        $regular_price = $product_data[17];
        $image_id = self::attach_img($product_data[34])[0]; // First image from gallery
        $gallery_ids = self::attach_img($product_data[34]);
        $product_name = $product_data[33];
        $product_description = $product_data[32];
        $product_short_description = $product_data[32];

        // Preparing data for import
        $product_info = [
          'type' => 'simple',
          'name' => $product_name,
          'description' => $product_description,
          'short_description' => $product_short_description,
          'sku' => $sku,
          'regular_price' => $regular_price,
          'category_ids' => self::get_category($category_id),
//          'image_id' => $image_id,
//          'gallery_ids' => $gallery_ids
        ];

        // Get product ID if it exists
        $existing_product_id = self::get_existing_product_id($sku);

        // Check if product data up-to-date
        $is_product_up_to_date = self::check_is_product_up_to_date($existing_product_id, $vehicle_updated_time, $image_updated_time);

        // If product doesn't exist, CREATE it
        if ($existing_product_id == 0) {
          $this->create_product_finally($product_info, $product_data, $sku);
        } else if (!$is_product_up_to_date) {
          // If product exists and out of date, then delete it

          // Move the post (product) to the trash
          $deleted_product = $this->delete_product($existing_product_id);

          // If product has been deleted successfully, then recreate it with new data
          if (is_object($deleted_product)) {
            $this->update_product($product_info, $product_data, $sku);
          }
        }
      }
    }

    /**
     * Creates product, sets custom fields values and notifies whether product created or failed
     *
     * @param $product_info
     * @param $product_data
     * @param $product_sku
     * @return string
     * @throws \WC_Data_Exception
     */
    public function create_product_finally($product_info, $product_data, $product_sku) {
      $created_product_id = $this->create_product($product_info);
      $this->set_custom_fields_values($created_product_id, $product_data);

      // Notify whether product has been created or not
      if ($created_product_id == 0) {
        return "<p  class='notification notification_failure'>Could not add a new product. <b>SKU: {$product_sku} </b> has not been created!</p>";
      } else {
        return "<p  class='notification notification_success'>Added new product. <b>SKU: {$product_sku}</b> | <b>ID: {$created_product_id}</b></p>";
      }
    }

    /**
     * Updates product info and notifies
     *
     * @param $product_info
     * @param $product_data
     * @param $product_sku
     * @return string
     * @throws \WC_Data_Exception
     */
    public function update_product($product_info, $product_data, $product_sku) {
      $created_product_id = $this->create_product($product_info);
      $this->set_custom_fields_values($created_product_id, $product_data);

      return "<p class='notification notification_success'>Product has been updated. <b>SKU: {$product_sku}</b> | <b>ID: {$created_product_id}</b></p>";
    }

    /**
     * Deletes product
     *
     * @param $product_id
     * @return array|false|\WP_Post|null
     */
    public function delete_product($product_id) {
      return wp_trash_post($product_id);
    }

    /**
     * Uploads images & returns ID
     *
     * @param $product_url
     * @return int|mixed|string|\WP_Error
     */
    public static function attach_img($product_url) {
      $imgs_url = explode(',', $product_url);
      $image_id = null;
      foreach ($imgs_url as $url) {
        $image_id[] = media_sideload_image($url, 0, '', 'id');
      }
      return $image_id;
    }

    /**
     * Gets category id from CSV file
     *
     * @param $categories_id
     * @return array
     */
    private static function get_category($categories_id) {
      /**
       * Category ID in CSV | Category ID in WordPress | Category Name
       * 1                  | 26                       | Automotive
       * 2                  | 27                       | Motorcycle
       * 3                  | 28                       | Powersports
       * 4                  | 29                       | Marine
       * 5                  | 30                       | Commercial
       * 6                  | 31                       | RV/camper
       * 7                  | 59                       | Spare
       * */
      $category_id = [];

      switch ($categories_id) {
        case 1:
          $category_id[] .= 26;
          break;
        case 2:
          $category_id[] .= 27;
          break;
        case 3:
          $category_id[] .= 28;
          break;
        case 4:
          $category_id[] .= 29;
          break;
        case 5:
          $category_id[] .= 30;
          break;
        case 6:
          $category_id[] .= 31;
          break;
        case 7:
          $category_id[] .= 59;
          break;
      }

      return $category_id;
    }

    /**
     * Gets product id by its SKU
     *
     * @param $sku
     * @return int
     */
    public static function get_existing_product_id($sku) {
      return wc_get_product_id_by_sku($sku);
    }

    /**
     * Checks is product up-to-date according to columns:
     * - vehicle_last_updated
     * - image_last_updated
     *
     * @param $post_id
     * @param $vehicle_last_updated
     * @param $image_last_updated
     * @return bool
     */
    public static function check_is_product_up_to_date($post_id, $vehicle_last_updated, $image_last_updated) {
      $is_product_up_to_date = true;
      $post_updated_time = get_the_modified_date('Y-m-N H:i:s', $post_id);

      // Get rid of unnecessary ending zero values
      $vehicle_last_updated = substr($vehicle_last_updated, 0, strpos($vehicle_last_updated, '.'));
      $image_last_updated = substr($image_last_updated, 0, strpos($image_last_updated, '.'));

      // Get last changed column
      $up_to_date_column = self::get_files_last_modification($vehicle_last_updated, $image_last_updated);

      // Check if post up-to-date
      if ($up_to_date_column > $post_updated_time) $is_product_up_to_date = false;

      return $is_product_up_to_date;
    }

    /**
     * Gets latest modified column from CSV
     *
     * @param $vehicle_last_updated
     * @param $image_last_updated
     * @return mixed
     */
    public static function get_files_last_modification($vehicle_last_updated, $image_last_updated) {
      if ($vehicle_last_updated > $image_last_updated) {
        return $vehicle_last_updated;
      } else {
        return $image_last_updated;
      }
    }

  }



