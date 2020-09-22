<?php


  namespace NGS;

  use WC_Data_Exception;

  require('Helper.php');
  require('Notification.php');

  /**
   * Class Product
   * @package NGS
   */
  class Product {

    public $attribute_slugs = [
      3 => 'car_year',
      4 => 'make',
      5 => 'model',
      7 => 'condition',
      8 => 'mileage',
      22 => 'interiorcolor',
      23 => 'exteriorcolor',
      31 => 'location'
    ];

    /**
     * Main method. Imports all products from CSV file
     *
     * @param $csv_data
     * @throws WC_Data_Exception
     */
    public function import_all_products($csv_data) {

      $sold_out_products = Helper::detect_sold_out_products($csv_data);
      Helper::delete_sold_out_products($sold_out_products);

      foreach ($csv_data as $product_index => $product_data) {

        $main_data = Helper::extract_main_data($product_data);

        /**
         * @var $sku
         * @var $category_id
         * @var $product_name
         * @var $regular_price
         * @var $image_updated_time
         * @var $product_description
         * @var $vehicle_updated_time
         * @var $product_short_description
         */
        extract($main_data);

        $image_id = self::attach_img($product_data[34])[0]; // First image from gallery
        $gallery_ids = self::attach_img($product_data[34]);

        $category_ids = Helper::get_categories($category_id);
        $all_attributes = Helper::make_attributes($this->attribute_slugs, $product_data);

        self::add_all_attributes_to_wp($this->attribute_slugs, $product_data);

        // Preparing data for import
        $prepared_data = Helper::prepare_data($product_name, $product_description, $product_short_description, $sku, $regular_price, $category_ids, $all_attributes, $image_id, $gallery_ids);
        // Get product ID if it exists
        $existing_product_id = Helper::get_existing_product_id($sku);

        // Check if product data up-to-date
        $is_product_up_to_date = self::check_is_product_up_to_date($existing_product_id, $vehicle_updated_time, $image_updated_time);

        if ($existing_product_id == 0) {
          // If product doesn't exist, CREATE it
          $this->create_product_with_additional_data($prepared_data, $product_data, $sku);
        } else if (!$is_product_up_to_date) {
          // If product exists and out of date, then delete it (move the post (product) to the trash)
          $deleted_product = $this->delete_product($existing_product_id);

          if (is_object($deleted_product)) {
            // If product has been deleted successfully, then recreate it with new data
            $this->update_product($prepared_data, $product_data, $sku);
          }
        }
      }
    }

    /**
     * Creates and saves product with standard info, attributes, images, price etc.
     *
     * @param $args
     * @return false|int
     * @throws WC_Data_Exception
     */
    public function create_product($args) {
      global $woocommerce;

//      if (!function_exists('wc_get_product_object_type') && !function_exists('wc_prepare_product_attributes'))
//        return false;

      // Get an empty instance of the product object (defining it's type)
      $product = Helper::wc_get_product_object_type($args['type']);
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
        $product->set_attributes(Helper::wc_prepare_product_attributes($args['attributes']));
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
     * Creates product, sets values custom fields and notifies whether it succeed or failed
     *
     * @param array $prepared_data
     * @param $product_data
     * @param $product_sku
     * @return string
     * @throws WC_Data_Exception
     */
    public function create_product_with_additional_data(array $prepared_data, $product_data, $product_sku) {
      $created_product_id = $this->create_product($prepared_data);
      $this->set_custom_fields_values($created_product_id, $product_data);

      if ($created_product_id == 0) {
        Notification::create_new_product_failed($product_sku);
      } else {
        Notification::created_new_product_succeed($product_sku, $created_product_id);
      }

    }

    /**
     * Updates product info and notifies
     *
     * @param $prepared_data
     * @param $product_data
     * @param $product_sku
     * @throws WC_Data_Exception
     */
    public function update_product($prepared_data, $product_data, $product_sku) {
      $created_product_id = $this->create_product($prepared_data);
      $this->set_custom_fields_values($created_product_id, $product_data);

      Notification::update_product_succeed($product_sku, $created_product_id);
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


    /*** SECONDARY PRODUCT OPERATIONS ***/

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
     * Sets custom fields values
     *
     * @param $product_id
     * @param $csv_data
     */
    private function set_custom_fields_values($product_id, $csv_data) {
      $vin = $csv_data[1];
      $stock_id = $csv_data[2];
      $engine = $csv_data[10];
      $transmission = $csv_data[11];
      $drivetrain = $csv_data[12];
      $doors = $csv_data[13];
      $body_type = $csv_data[14];
      $trim = $csv_data[6];
      $description = $csv_data[32];
      $standard_features = $csv_data[30];
      $video = $csv_data[37];

      // Adding data to the custom fields
      update_field('field_vin', $vin, $product_id);
      update_field('field_stock_id', $stock_id, $product_id);
      update_field('field_engine', $engine, $product_id);
      update_field('field_transmission', $transmission, $product_id);
      update_field('field_drivetrain', $drivetrain, $product_id);
      update_field('field_doors', $doors, $product_id);
      update_field('field_body_type', $body_type, $product_id);
      update_field('field_trim', $trim, $product_id);
      update_field('field_description', $description, $product_id);
      update_field('field_standard_features', $standard_features, $product_id);
      update_field('field_video', $video, $product_id);
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
      $up_to_date_column = Helper::get_files_last_modification($vehicle_last_updated, $image_last_updated);

      // Check if post up-to-date
      if ($up_to_date_column > $post_updated_time) $is_product_up_to_date = false;

      return $is_product_up_to_date;
    }

    /**
     * Adds all possible attribute values to wp to assign them later
     *
     * @param $attributes
     * @param $prepared_data
     */
    public static function add_all_attributes_to_wp($attributes, $prepared_data) {
      foreach ($attributes as $column_num => $attribute) {
        wp_insert_term($prepared_data[$column_num], "pa_{$attribute}");
      }
    }

  }



