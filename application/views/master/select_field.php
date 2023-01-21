<?php $option_data = $data = json_decode(json_encode($option_record), true);
// if()
$CSS_CLASS = isset($CSS_CLASS) ? $CSS_CLASS : '';
?>

<div class="form-group">
    <label for="<?= $field_id ?>" class="col-md-12 control-label"><?php echo trans($label) ?></label>
    <div class="col-md-12">
        <select id="<?= $field_id ?>" name="<?= $field_id ?>" class="form-control form-select2 ">
            <option></option>
            <?php
            foreach ($option_data as $row) {
                $selct_option_value = $row[$option_value];
                $selct_option_text = $row[$option_text];
                $selected = "";
                if ($selected_value == $selct_option_value) {
                    $selected = "selected";
                }
                echo "<option id='$field_id" . "_" . "$selct_option_value' value='$selct_option_value' $selected>$selct_option_text</option>";
            }
            ?>
        </select>
        <small class="error-messsage text-danger" id="<?= $field_id ?>_error"></small>
    </div>
</div>