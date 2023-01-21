<div class="form-group" style='justify-content: center'>
    <?php
    $label_text = '';
    if ($image_title == '') {
        $image_path = 'liquor_images/liquor_preview.jpg';
        $label_text = 'Choose file';
    } else {
        $image_path =$image_title;
        $label_text = $image_title;
    }
    ?>
    <div class='<?= $css_class ?>'>
        <img src="<?= base_url() . $image_path ?>" id="<?= $field_id ?>_img" class="<?= $css_class ?>" width="<?= $width ?>" height="<?= $height ?>" alt="<?= $image_title ?>"></img>
    </div>
    <div style="margin-top:10px;">
        <div class="form-group">
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="<?= $field_id ?>" name="<?= $name ?>" data-parsley-filemaxmegabytes=".2" accept="image/x-png,image/gif,image/jpeg" data-parsley-trigger="change" data-parsley-filemimetypes="image/jpeg, image/png" onchange="readURL(this);">
                    <label class="custom-file-label" id="<?= $field_id ?>_label" for="<?= $field_id ?>"><?= $label_text ?>
                    </label>
                </div>
                <div class="input-group-append">
                </div>

            </div>
            <input type="hidden" id="<?= $field_id ?>_h" name="<?= $field_id ?>_h" value="<?= $image_title ?>">
            <small class="error-messsage text-danger" id="<?= $field_id ?>_error"></small>
        </div>
    </div>
</div>