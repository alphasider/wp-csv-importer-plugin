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
        Notification::delete_sold_out_product_status($post_object, $product_id, $product_sku);
      }
    }

    public static function get_csv_file_sku($csv_data) {
      $output = [];
      foreach ($csv_data as $row) {
        $output[] = $row[2];
      }
      return $output;
    }

    /**
     * Prepares data for create product function
     *
     * @param $product_name
     * @param $product_description
     * @param $product_short_description
     * @param $sku
     * @param $regular_price
     * @param $category_ids
     * @param $all_attributes
     * @param $image_id
     * @param $gallery_ids
     * @param string $product_type
     * @return array
     */
    public static function prepare_data($product_name, $product_description, $product_short_description, $sku, $regular_price, $category_ids, $all_attributes, $image_id, $gallery_ids, $product_type = 'simple') {
      return [
        'type' => $product_type,
        'name' => $product_name,
        'description' => $product_description,
        'short_description' => $product_short_description,
        'sku' => $sku,
        'regular_price' => $regular_price,
        'category_ids' => $category_ids,
        'attributes' => $all_attributes,
        'image_id' => $image_id,
        'gallery_ids' => $gallery_ids
      ];
    }

    /**
     * Creates proper array of attributes
     *
     * @param $attribute_slugs
     * @param $product_row
     * @return array
     */
    public static function make_attributes($attribute_slugs, $product_row) {
      $output = [];
      foreach ($attribute_slugs as $column_index => $slug) {
        $output["pa_{$slug}"] = [
          'term_names' => [$product_row[$column_index]],
          'is_visible' => true,
          'for_variation' => false,
        ];
      }
      return $output;
    }

    /**
     * Gets main data from the right columns, and assigns correct values
     *
     * @param $column
     * @return array
     */
    public static function extract_main_data($column) {
      return [
        'sku' => $column[2],
        'category_id' => $column[44],
        'vehicle_updated_time' => $column[42],
        'image_updated_time' => $column[43],
        'regular_price' => $column[17],
        'product_name' => $column[33],
        'product_description' => $column[32],
        'product_short_description' => $column[32]
      ];
    }

  }