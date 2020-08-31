<?php


  namespace NGS;

  use WC_Product;

  /**
   * Class Product
   * @package NGS
   */
  class Product {

    /**
     * Sets product parameters
     *
     * @param $product
     * @param array $csv_data
     * @return mixed
     */
    public function set_product_main_params($product, $csv_data = []) {
      // Used attributes
      $product_sku = $csv_data[2]; // StockNumber
      $product_price = $csv_data[17]; // InternetPrice
      $product_description = $csv_data[32]; // Description
      $product_name = $csv_data[33]; // ShowroomTitle
      $category_ids = $csv_data[44]; // Vehicle Category ID

      // Yet unused attributes
      $dealerid = $csv_data[0]; // DealerID
      $vin = $csv_data[1]; // VIN
      $year = $csv_data[3]; // Year
      $make = $csv_data[4]; // Make
      $model = $csv_data[5]; //   Model
      $trim = $csv_data[6]; // Trim
      $condition = $csv_data[7]; // Condition
      $mileage = $csv_data[8]; // Mileage
      $modelcode = $csv_data[9]; // ModelCode
      $enginedescription = $csv_data[10]; // EngineDescription
      $transmission = $csv_data[11]; // Transmission
      $drivetrain = $csv_data[12]; // DriveTrain
      $doors = $csv_data[13]; // Doors
      $bodytype = $csv_data[14]; // BodyType
      $inventorysince = $csv_data[15]; // InventorySince
      $ageindays = $csv_data[16]; // AgeInDays
      $msrp = $csv_data[18]; // MSRP
      $invoiceprice = $csv_data[19]; // InvoicePrice
      $stickerprice = $csv_data[20]; // StickerPrice
      $thirdpartyprice = $csv_data[21]; // ThirdPartyPrice
      $exteriorcolor = $csv_data[22]; // ExteriorColor
      $interiorcolor = $csv_data[23]; // InteriorColor
      $exteriorcolorbasic = $csv_data[24]; // ExteriorColorBasic
      $interiorcolorbasic = $csv_data[25]; // InteriorColorBasic
      $oemcertified = $csv_data[26]; // OEMCertified
      $dealercertified = $csv_data[27]; // DealerCertified
      $internetspecial = $csv_data[28]; // InternetSpecial
      $inventoryurl = $csv_data[29]; // InventoryURL
      $standardfeatures = $csv_data[30]; // StandardFeatures
      $lotlocation = $csv_data[31]; // LotLocation
      $pictureurls = $csv_data[34]; // PictureURLs
      $options = $csv_data[35]; // Options
      $carfaxhighlights = $csv_data[36]; // CARFAXHighlights
      $videolink = $csv_data[37]; //   VideoLink
      $videoflv = $csv_data[38]; // VideoFLV
      $videoembedcode = $csv_data[39]; // VideoEmbedCode
      $mpgcity = $csv_data[40]; // MPGCity
      $mpghighway = $csv_data[41]; // MPGHighway
      $vehiclelastupdate = $csv_data[42]; // VehicleLastUpdate
      $imagelastupdate = $csv_data[43]; // ImageLastUpdate

      // Getting array of categories IDs
      $product_category_id = $this->get_category($category_ids);

      // Setting basic product information
      $product->set_name($product_name);
      $product->set_description($product_description);
      $product->set_sku($product_sku); //can be blank in case you don't have sku, but You can't add duplicate sku's
      $product->set_regular_price($product_price); // set product price
      $product->set_status('publish');  // can be publish,draft or any wordpress post status
      $product->set_category_ids($product_category_id);

      return $product;
    }

    /**
     * Gets category id from CSV file
     *
     * @param $categories_id
     * @return array
     */
    private function get_category($categories_id) {
      //1: Automotive - ID: 26
      //2: Motorcycle - ID: 27
      //3: Powersports - ID: 28
      //4: Marine - ID: 29
      //5: Commercial - ID: 30
      //6: RV/camper - ID: 31
      //7: Spare - ID: 59
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
     * Creates new products from given array
     *
     * @param $csv_data
     */
    public function create_products($csv_data) {
      foreach ($csv_data as $row) {
        $product = new WC_Product();
        $this->set_product_main_params($product, $row);
        $product->save();
      }
    }
  }
