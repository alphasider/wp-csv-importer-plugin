<?php


  namespace NGS;

  /**
   * Class Check
   * @package NGS
   */
  class Check {

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

      // Get rid of unnecessary ending zero values
      $vehicle_last_updated = self::clear_data($vehicle_last_updated);
      $image_last_updated = self::clear_data($image_last_updated);

      // Get last changed column
      $up_to_date_column = self::get_files_last_modification($vehicle_last_updated, $image_last_updated);

      // Check if post up-to-date
      if ($up_to_date_column > $post_updated_time) {
        $is_product_up_to_date = false;
      }

      return $is_product_up_to_date;
    }

    /**
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
     * @param $data
     * @return false|string
     */
    public static function clear_data($data){
      return substr($data, 0, strpos($data, '.'));
    }

  }