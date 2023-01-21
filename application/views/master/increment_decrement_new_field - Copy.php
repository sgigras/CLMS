<div class="row lineheight">
    <div class="input-group mb-3 ">
        <div class="input-group-prepend">
            <button class="input-group-text  increment_decrement_quantity_button" id="<?= $field_id ?>_decrement_btn">-</button>
        </div>
        <input type="text" class="form-control " onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" style="background-color: #d6d6d7; height: 37px;padding-left:40%; color: black;text-align:center" step="1" id="<?= $field_id ?>_quantity_display" name="<?= $field_id ?>_decrement_btn" value="<?= $quantity ?>">
        <!-- <input type="text" class="form-control"> -->
        <div class="input-group-append">
            <button class="input-group-text increment_decrement_quantity_button" id="<?= $field_id ?>_increment_btn">+</button>
            <!-- <button class="input-group-text">+</button> -->
        </div>
    </div>
</div>