<?php


  namespace NGS;


  class Check {
    /**
     * TODO:
     * DONE 1. Check if the product exists by SKU
     * TODO 2. If there is a product:
     * TODO   2.1. Check if is it up-to-date (by ‘vehicle last updated’ and ‘image last updated’ columns):
     *          2.1. HowToDo: get greater value (date) of the 'vehicle last updated' and 'mage last updated' -> compare it with post updated time
     * TODO     2.1.1. If it is, SKIP
     * TODO     2.1.2. If not, UPDATE
     * DONE 3. If there is no one, CREATE
     */


    /**
     * @param $sku
     * @return int
     */
    public static function get_existing_product_id($sku) {
      return wc_get_product_id_by_sku($sku);
    }

    /**
     * @param $post_id
     * @param $vehicle_last_updated
     * @param $image_last_updated
     * @return bool
     */
    public static function check_modified_date($post_id, $vehicle_last_updated, $image_last_updated) {
      $is_product_up_to_date = true;
      $post_updated_time = get_the_modified_date('Y-m-N H:i:s', $post_id);

      // Get rid of unnecessary ending zeros
      $vehicle_last_updated = substr($vehicle_last_updated, 0, strpos($vehicle_last_updated, '.'));
      $image_last_updated = substr($image_last_updated, 0, strpos($image_last_updated, '.'));

      // Check if post up-to-date
      if ($vehicle_last_updated > $image_last_updated) {
        if ($vehicle_last_updated > $post_updated_time) {
          $is_product_up_to_date = false;
        }
      } else {
        if ($image_last_updated > $post_updated_time) {
          $is_product_up_to_date = false;
        }
      }

      return $is_product_up_to_date;
    }

  }