<div class="form-group">
    <label for="<?= $field_id ?>" class="col-md-3 control-label"><?= trans('address') ?></label>
    <div class="col-md-12">
        <textarea id="<?= $field_id ?>" name="<?= $field_id ?>" class="form-control" maxlength="<?= $max_length ?>" onkeypress="return checkValidInputKeyPress(address_regex_pattern);" placeholder="<?= $place_holder ?>" value=""><?= $value ?></textarea>
        <small class="error-messsage text-danger" id="<?= $field_id ?>_error"></small>
    </div>
</div>