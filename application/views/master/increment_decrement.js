function increment_decrement_quantity (quantity_box_id, product_id, add_sub_type) {
  var quantity_value = document.getElementById(quantity_box_id).value
  if (add_sub_type == 'A') {
    document.getElementById(quantity_box_id).value = ++quantity_value
  } else if (add_sub_type == 'S') {
    quantity_value = quantity_value > 1 ? --quantity_value : 1
    document.getElementById(quantity_box_id).value = quantity_value
  }
}
