<?php

/*
  @author:JItendra Pal
 * description:To  table head and data
 * array
 * 1st parameter from site_lang.php for display table head
 * 2nd paramter from database table column name 
 * in cart_product_id quantity is used as we are sending 12_1_30 12-first represent quantity 1-second entity liquor id  mapping from enityt table 30- third cartid
 *  */


define("BOOKED_LIQUOR_RECORD", serialize(array("alcohol:Liquor_details", "cart_unit_cost:unit_cost_lot_size", "booked_quantity:booked_quantity")));
define("LIQUOR_RANK_QUOTA_LIST", serialize(array("rank:bsf_rank", "alloted_quota:quota")));
define("LIQUOR_MAPPING_LIST", serialize(array("liquor_image:liquor_image", "liquor:liquor", "purchase_price:purchase_price", "selling_price:selling_price", "available_quantity:available_quantity", "physical_quantity:actual_available_quantity")));
define("LIQUOR_STOCK_MAPPING_LIST", serialize(array("liquor_image:liquor_image", "liquor:liquor", "purchase_price:purchase_price", "selling_price:selling_price", "available_quantity:available_quantity", "physical_quantity:actual_available_quantity")));
define("ALL_CANTEEN", serialize(array("entity_name:entity_name", "canteen_club:canteen_club", "state:state", "chairman:chairman", "supervisor:supervisor", "executive:executive")));
define("CANTEEN_SALES_LIQUORS_ADDED", serialize(array("entity_name:entity_name")));

define("RETIREE_VERIFICATION_CANTEEN_STATUS", serialize(array("irla_no:irla", "rank:rank", "name:name", "mobile_no:mobile_no", "email_id:email_id", "requested_by:requested_by", "approval_from:approval_from", "requested_time:request_time", "status:retiree_status")));

define("RETIREE_VERIFICATION_STATUS", serialize(array("canteen_name:entity_name", "irla_no:irla", "rank:rank", "name:name", "mobile_no:mobile_no", "email_id:email_id", "requested_by:requested_by", "approval_from:approval_from", "requested_time:requested_time")));

define("LIQUOR_BRAND_MASTER", serialize(array("liquor_brand:liquor_brand")));
define("USER_DETAILS_TABLE", serialize(array("irla_no:irla", "name:name", "mobile_no:mobile_no", "date_of_birth:date_of_birth", "rank:rank", "present_appointment:present_appoitment", "status:status", "location_name:location_name", "district_name:district_name", "state_name:state_name", "email_id:email_id", "creation_time:creation_time", "posting_unit:posting_unit", "frontier:frontier", "registration_status:registration_status")));
define("TAX_MASTER", serialize(array("tax_name:tax_name", "tax_category:tax_category")));
define("ALCOHOL_MASTER", serialize(array("liquor_type:liquor_type")));
define("CITY_MSTER", serialize(array("city_name:city_district_name", "state:state")));
define("ALCOHOL_MM_MASTER", serialize(array("alcohol_quantity_in_mm:liquor_ml")));
define("alcohol_type", serialize(array("title" => "Add Liquor Type", "heading" => "Liquor Type", "label" => "Liquor Name", "placeholder" => "Enter liquor type")));
define("alcohol_brand", serialize(array("title" => "Add Liquor Brand", "heading" => "Liquor Brand", "label" => "Brand Name", "placeholder" => " Enter brand type")));
define("alcohol_ml", serialize(array("title" => "Add Liquor Quantity", "heading" => "Liquor Quantity", "label" => "Liquor quantity", "placeholder" => "Enter liquor quantity")));
define("alcohol_type_tablehead", serialize(array("id" => "ID", "alcohol_type" => "Liquor Type", "action" => "Action")));
define("alcohol_brand_tablehead", serialize(array("id" => "ID", "alcohol_type" => "Liquor Brand", "action" => "Action")));
define("alcohol_ml_tablehead", serialize(array("id" => "ID", "alcohol_quantity" => "Liquor Quantity", "action" => "Action")));
define("tax_master", serialize(array("title" => "Add Tax Name", "heading" => "Tax Type", "label" => "Tax Name", "placeholder" => "Enter tax name")));
define("tax_master_tablehead", serialize(array("id" => "ID", "tax_type" => "Tax Type", "action" => "Action")));
// define("tax_master_tablehead", serialize(array("id" => "ID","tax_category" => "Tax Category","action" => "Action")));
define("city_master", serialize(array("title" => "Add City", "heading" => "City", "label" => "City", "placeholder" => "Enter city name", "label1" => "State", "placeholder1" => "Select State")));
define("city_master_tablehead", serialize(array("id" => "ID", "city" => "City", "state" => "State", "action" => "Action")));
define("bottle_description", serialize(array("title" => "Add Bottle Description", "heading" => "Bottle Description", "label" => "Bottle Size", "placeholder" => "Enter bottle size")));
define("bottle_description_tablehead", serialize(array("id" => "ID", "bottle_description" => "Bottle Description", "action" => "Action")));

define("LIQUOR_MASTER_LIST", serialize(array("liquor_brand:liquor_brand", "liquor_name:liquor_name", "liquor_type:liquor_type", "bottle_size:bottle_size")));

define("CANTEEN_MASTER_LIST", serialize(array("canteen_name:entity_name","unit_type:battalion_unit" , "establishment_type:canteen_club", "state:state", "canteen_chairman:chairman", "canteen_executive:executive", "canteen_supervisor:supervisor")));
// define("LIQUOR_MASTER_LIST", serialize(array("liquor_name:liquor_name", "liquor_type:liquor_type", "liquor_image:liquor_image")));
// define("ENTITY_CART_TABLE", serialize(array("cart_product_id_hidden:quantity", "cart_liquor_image:liquor_image", "cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_in_lot_buttons:quantity", "cart_unit_in_lot:unit_lot_cost", "cart_total_quantity:total_quantity_cost", "remove:is_liquor_removed")));
define("ENTITY_CART_TABLE", serialize(array("cart_product_id_hidden:quantity", "cart_liquor_image:liquor_image", "cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_buttons:quantity", "cart_unit_cost:unit_lot_cost", "cart_total_cost:total_quantity_cost", "remove:is_liquor_removed")));
define("CONSUMER_CART_TABLE", serialize(array("cart_product_id_hidden:quantity", "cart_liquor_image:liquor_image", "cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_buttons:quantity", "cart_unit_cost:unit_lot_cost", "cart_total_cost:total_quantity_cost", "remove:is_liquor_removed")));
// define("ENTITY_CART_TOTAL_TABLE", serialize(array("cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_in_lot_buttons:quantity", "cart_unit_in_lot:unit_lot_cost", "cart_unit_cost:selling_price", "cart_total_quantity:total_quantity_cost", "cart_total_cost:total_cost")));
// define("CONSUMER_CART_TOTAL_TABLE", serialize(array("cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_buttons:quantity", "cart_unit_cost:unit_lot_cost", "cart_total_cost:total_quantity_cost")));
define("ENTITY_CART_TOTAL_TABLE", serialize(array("cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_buttons:quantity", "cart_unit_cost:selling_price", "cart_total_cost:total_quantity_cost")));
define("CONSUMER_CART_TOTAL_TABLE", serialize(array("cart_table_liquor_name:liquor_name", "liquor_type:liquor_type", "cart_quantity_buttons:quantity", "cart_unit_cost:unit_lot_cost", "cart_total_cost:total_quantity_cost")));
// define("TAX_MASTER", serialize(array("tax_name:tax_name")));
// define("ALCOHOL_MASTER", serialize(array("liquor_type:alcohol_type")));
// define("CITY_MSTER", serialize(array("city_name:city_district_name", "state:state")));


define("BILL_TABLE_HEAD", serialize(array("description:liquor_bill_description", "cart_quantity_buttons:quantity", "rate:unit_lot_cost", "total_cost:total_quantity_cost")));

define("BREWERY_MASTER_LIST", serialize(array("brewery_name:brewery_name", "address:address", "contact_person_name:contact_person_name", "mobile_no:mobile_no", "mail_id:mail_id"))); // "state:state", "serving_entity:serving_entity"

// define("USER_DETAILS_TABLE", serialize(array("sr_no:sr_no","registration_status:registration_status","irla_no:irla", "name:name", "mobile_no:mobile_no", "date_of_birth:date_of_birth", "rank:rank", "present_appointment:present_appoitment", "status:status", "location_name:location_name", "district_name:district_name", "state_name:state_name", "email_id:email_id", "creation_time:creation_time")));
//
define("PURCHASE_CART", serialize(array(
  "title" => "cart", //from lang file
  "cart_button_1" => "continue_shopping", //from lang file
  "cart_button_2" => "place_order", //from lang file
  "page_mode" => 'shopping_cart', //for sp updatation
  "redirect_url" => 'cart/CartDetails/orderCodeDisplay',
  // "redirect_url" => 'cart/CartDetails/displaySessionOrder',
  // "redirected_from_serach_product_to" => 'cart/CartDetails/viewSessionOrder',//redirection from sea
  'mode' => 'A'
)));



define("DELIVERY_CART", serialize(array(
  "title" => "delivery_cart", //from lang file
  "cart_button_1" => "add_liquor", //from lang file
  "cart_button_2" => "check_out", //from lang file
  "page_mode" => 'delivery_cart',
  "redirect_url" => 'order/OrderDetails/displaySessionDeliveryOrder',
  // "redirected_from_serach_product_to" => 'cart/CartDetails/viewSessionOrder',
  'mode' => 'A' //for sp updatation
)));


define("Delivery_Summary", serialize(array("title" => "Delivery")));
// define("Cart_Summary", serialize(array("title" => "Cart")));
// define("Order_Summary", serialize(array("title" => "Order")));

//to change order details page according to the navigation panel

//summary form label
define("Order_Code_Summary", serialize(array(
  "title" => "order_summary",
  "mode" => "order_summary",
  "fa_form_icon" => "fas fa-receipt",
  "button_label_1" => "",
  "button_label_2" => "",
  "button_id_1" => "",
  "button_id_2" => "",
  "button_class_1" => "",
  "button_class_2" => "",
  "fa_button_icon" => "",
  "cart_footer_button_mode" => "order_code_confirm"

)));

// for cart summary
define("CART_SUMMARY", serialize(array(
  "title" => "cart_summary",
  "mode" => "cart_summary",
  "fa_form_icon" => "fas fa-receipt",
  "button_label_1" => "continue_shopping",
  "button_label_2" => "place_order",
  "button_id_1" =>
  "continueShopping_btn",
  "button_id_2" => "placeOrder_btn",
  "button_class_1" => "continue_shopping",
  "button_class_2" => "place_order",
  "fa_button_icon" => "fas fa-receipt",
  "cart_footer_button_mode" => "place_order"
)));

//for cart order summary
define("ORDER_SUMMARY", serialize(array(
  "title" => "order_summary",
  "mode" => "order_summary",
  "fa_form_icon" => "fas fa-receipt",
  "button_label_1" => "continue_shopping",
  "button_label_2" => "place_order",
  "button_id_1" =>
  "continueShopping_btn",
  "button_id_2" => "placeOrder_btn",
  "button_class_1" => "continue_shopping",
  "button_class_2" => "place_order",
  "fa_button_icon" => "fas fa-receipt",
  "cart_footer_button_mode" => "cart_code_display"
)));


//for order delivery summary
define("ORDER_DELIVERY_SUMMARY", serialize(array(
  "title" => "order_delivery_summary", //from lang file 
  "mode" => "order_delivery_summary",
  "fa_form_icon" => "fas fa-receipt",
  "button_label_1" => "edit_order_delivery_summary", //from lang file
  "button_label_2" => "submit_order_delivery_summary", //from lang file
  "button_id_1" =>
  "edit_order_delivery_summary_btn",
  "button_id_2" => "submit_order_delivery_summary_btn",
  "button_class_1" => "edit_order_delivery_summary", //classes for button
  "button_class_2" => "submit_order_delivery_summary", //classes for button
  "fa_button_icon" => "fas fa-receipt",
  "cart_footer_button_mode" => "deliver_order"
)));









// define("Order_Summary", serialize(array("title" => "Order")));
