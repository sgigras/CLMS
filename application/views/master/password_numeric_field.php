<div class = "form-group">
    <label for = "<?= $field_id ?>" class = "col-md-6 control-label"><?php echo $label ?></label>
    <div class="col-md-12">
        <input type="password" name="<?= $field_id ?>" class="form-control" name="<?= $field_id ?>" id="<?= $field_id ?>" onkeypress="return checkValidInputKeyPress(numeric_regex_pattern);" maxlength="<?= $max_length ?>" placeholder="<?= $place_holder ?>" value="<?= $value ?>">
        <!-- <small class="error-messsage text-danger" id="<?= $field_id ?>_error"></small> -->
    </div>
</div>