<?php


  namespace NGS;


  use WC_Product;
  use WC_Product_Attribute;
  use WC_Product_External;
  use WC_Product_Grouped;
  use WC_Product_Simple;
  use WC_Product_Variable;

  class Helper {

    /**
     * Utility function that returns the correct product object instance
     *
     * @param $type
     * @return false|WC_Product|WC_Product_External|WC_Product_Grouped|WC_Product_Simple|WC_Product_Variable
     */
    public static function wc_get_product_object_type($type) {
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
     * Utility function that prepare product attributes before saving
     *
     * @param $attributes
     * @return array
     */
    public static function wc_prepare_product_attributes($attributes) {
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

    /**
     * Gets category id from CSV file
     *
     * @param $categories_id
     * @return array
     */
    public static function get_categories($categories_id) {
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

    /**
     * Gets product id by its SKU
     *
     * @param $sku
     * @return int
     */
    public static function get_existing_product_id($sku) {
      return wc_get_product_id_by_sku($sku);
    }

    public static function get_stored_products_sku() {
      $products_ids = wc_get_products(['return' => 'ids']);
      $output = [];
      foreach ($products_ids as $product_id) {
        $product = wc_get_product($product_id);
        $output[$product_id] = $product->get_sku();
      }
      return $output;
    }

    public static function detect_sold_out_products($csv_data) {
      $csv_file_sku = self::get_csv_file_sku($csv_data);
      $stored_products_csv = self::get_stored_products_sku();
      return array_diff($stored_products_csv, $csv_file_sku);
    }

    /**
     * Deletes product
     *
     * @param $products
     * @return void
     */
    public static function delete_sold_out_products($products) {
      foreach ($products as $product_id => $product_sku) {
        $post_object = wp_trash_post($product_id);
        self::notify_deleted_product_delete($post_object, $product_id, $product_sku);
      }
    }

    public static function notify_deleted_product_delete($post_object, $product_id, $product_sku) {
      if ($post_object !== false || $post_object !== null) {
        return "<p class='notification notification_success'>The product has successfully deleted! <b>ID: {$product_id}</b> | <b>SKU: {$product_sku}</b> </p>";
      } else {
        return "<p class='notification notification_success'>The product not deleted! <b>ID: {$product_id}</b> | <b>SKU: {$product_sku}</b> </p>";
      }
    }

    public static function get_csv_file_sku($csv_data) {
      $output = [];
      foreach ($csv_data as $row) {
        $output[] = $row[2];
      }
      return $output;
    }

  }