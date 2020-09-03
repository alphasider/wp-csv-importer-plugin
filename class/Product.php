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
    // Custom function for product creation (For Woocommerce 3+ only)
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

    // Utility function that returns the correct product object instance
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

    // Utility function that prepare product attributes before saving
    private function wc_prepare_product_attributes($attributes) {
      global $woocommerce;

      $data = array();
      $position = 0;

      foreach ($attributes as $taxonomy => $values) {
        if (!taxonomy_exists($taxonomy))
          continue;

        // Get an instance of the WC_Product_Attribute Object
        $attribute = new WC_Product_Attribute();

        $term_ids = array();

        // Loop through the term names
        foreach ($values['term_names'] as $term_name) {
          if (term_exists($term_name, $taxonomy))
            // Get and set the term ID in the array from the term name
            $term_ids[] = get_term_by('name', $term_name, $taxonomy)->term_id;
          else
            continue;
        }

        $taxonomy_id = wc_attribute_taxonomy_id_by_name($taxonomy); // Get taxonomy ID

        $attribute->set_id($taxonomy_id);
        $attribute->set_name($taxonomy);
        $attribute->set_options($term_ids);
        $attribute->set_position($position);
        $attribute->set_visible($values['is_visible']);
        $attribute->set_variation($values['for_variation']);

        $data[$taxonomy] = $attribute; // Set in an array

        $position++; // Increase position
      }
      return $data;
    }

    public function create_all_products($csv_data) {
      $attribute_slugs = [
        3 => 'car_year',
        4 => 'make',
        5 => 'model',
        7 => 'condition',
        10 => 'enginedescription',
        11 => 'transmission',
        12 => 'drivetrain',
        13 => 'doors',
        22 => 'interiorcolor',
        23 => 'exteriorcolor',
      ];
      $all_attributes = [];

      foreach ($csv_data as $product_index => $product_data) {
        foreach ($attribute_slugs as $column_index => $slug) {
          $all_attributes["pa_{$slug}"] = [
            'term_names' => [$product_data[$column_index]],
            'is_visible' => true,
            'for_variation' => false,
          ];
        }
        $product_info = [
          'type' => '', // Simple product by default
          'name' => $product_data[33],
          'description' => $product_data[32],
          'short_description' => $product_data[32],
          'sku' => $product_data[2],
          'regular_price' => $product_data[17],
          'reviews_allowed' => true,
          'attributes' => $all_attributes,
          'category_ids' => self::get_category($product_data[44]),
          'image_id' => self::attach_img($product_data[34])[0], // First image from gallery
          'gallery_ids' => self::attach_img($product_data[34])
        ];
        $this->create_product($product_info);
      }
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
  }



