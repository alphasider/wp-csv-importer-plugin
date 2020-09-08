<?php


  namespace NGS;


  class CustomFields {

    public function __construct() {
      add_action('acf/init', [$this, 'register_custom_acf_fields']);
    }

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

        // Make
        acf_add_local_field(array(
          'key' => 'field_make',
          'label' => 'Make',
          'name' => 'make',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));
        // Make
        acf_add_local_field(array(
          'key' => 'field_model',
          'label' => 'Model',
          'name' => 'model',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Year
        acf_add_local_field(array(
          'key' => 'field_year',
          'label' => 'Year',
          'name' => 'year',
          'type' => 'text',
          'parent' => 'group_person_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Mileage
        acf_add_local_field(array(
          'key' => 'field_mileage',
          'label' => 'Mileage',
          'name' => 'mileage',
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

        // Color
        acf_add_local_field(array(
          'key' => 'field_color',
          'label' => 'Color',
          'name' => 'color',
          'type' => 'text',
          'parent' => 'group_vehicle_details',
          'instructions' => '',
          'required' => 0,
        ));

        // Interior Color
        acf_add_local_field(array(
          'key' => 'field_interior_color',
          'label' => 'Interior Color',
          'name' => 'interior_color',
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

        // Condition
        acf_add_local_field(array(
          'key' => 'field_condition',
          'label' => 'Condition',
          'name' => 'condition',
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
