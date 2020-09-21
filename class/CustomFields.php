<?php


  namespace NGS;

  /**
   * Class CustomFields
   * @package NGS
   */
  class CustomFields {

    public function __construct() {
      add_action('acf/init', [$this, 'register_custom_acf_fields']);
    }

    /**
     * Registers new custom fields locally
     */
    public function register_custom_acf_fields() {
      if (function_exists('acf_add_local_field_group')) {

        // ACF Group: People
        acf_add_local_field_group(array(
          'key' => 'group_vehicle_details',
          'title' => 'Vehicle details',
          'location' => array(
            array(
              array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'product',
              ),
            ),
          ),
          'menu_order' => 0,
          'position' => 'normal',
          'style' => 'default',
          'label_placement' => 'top',
          'instruction_placement' => 'label',
          'hide_on_screen' => '',
        ));

        // VIN
        acf_add_local_field(array(
          'key' => 'field_vin',
          'label' => 'VIN',
          'name' => 'vin',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Stock ID
        acf_add_local_field(array(
          'key' => 'field_stock_id',
          'label' => 'Stock ID',
          'name' => 'stock_id',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Engine
        acf_add_local_field(array(
          'key' => 'field_engine',
          'label' => 'Engine',
          'name' => 'engine',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Transmission
        acf_add_local_field(array(
          'key' => 'field_transmission',
          'label' => 'Transmission',
          'name' => 'transmission',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Drivetrain
        acf_add_local_field(array(
          'key' => 'field_drivetrain',
          'label' => 'Drivetrain',
          'name' => 'drivetrain',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Doors
        acf_add_local_field(array(
          'key' => 'field_doors',
          'label' => 'Doors',
          'name' => 'doors',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Body Type
        acf_add_local_field(array(
          'key' => 'field_body_type',
          'label' => 'Body Type',
          'name' => 'body_type',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Trim
        acf_add_local_field(array(
          'key' => 'field_trim',
          'label' => 'Trim',
          'name' => 'trim',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Description
        acf_add_local_field(array(
          'key' => 'field_description',
          'label' => 'Description',
          'name' => 'description',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Standard Features
        acf_add_local_field(array(
          'key' => 'field_standard_features',
          'label' => 'Standard Features',
          'name' => 'standard_features',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Video
        acf_add_local_field(array(
          'key' => 'field_video',
          'label' => 'Video',
          'name' => 'video',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));
      }
    }
  }

  new CustomFields();
