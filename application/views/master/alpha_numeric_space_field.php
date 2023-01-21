<div class="form-group">
    <label for="<?= $field_id ?>" class="col-md-12 control-label"><?php echo trans($label); ?></label>
    <div class="col-md-12">
        <input type="text" name="<?= $field_id ?>" class="form-control" name="<?= $field_id ?>" id="<?= $field_id ?>" style="text-transform: uppercase" onkeypress="return checkValidInputKeyPress(alphanumeric_space_regex_pattern);" maxlength="<?= $max_length ?>" placeholder="<?= $place_holder ?>" value="<?= $value ?>">
        <small class="error-messsage text-danger" id="<?= $field_id ?>_error"></small>
    </div>
</div>