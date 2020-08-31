<?php

  namespace NGS;

  use WC_Product;

  class Product {

    /**
     * Sets product parameters
     *
     * @param $product
     * @param array $csv_data
     * @return mixed
     */
    public function set_product_main_params($product, $csv_data = []) {
      $dealerid = $csv_data[0]; // DealerID
      $vin = $csv_data[1]; // VIN
      $product_sku = $csv_data[2]; // StockNumber
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
      $product_price = $csv_data[17]; // InternetPrice
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
      $product_description = $csv_data[32]; // Description
      $product_name = $csv_data[33]; // ShowroomTitle
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
      $product_category_ids = $csv_data[44]; // Vehicle Category ID

      $product->set_name($product_name);
      $product->set_status('publish');  // can be publish,draft or any wordpress post status
      $product->set_description($product_description);
      $product->set_sku($product_sku); //can be blank in case you don't have sku, but You can't add duplicate sku's
      $product->set_price($product_price); // set product price

      return $product;
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
