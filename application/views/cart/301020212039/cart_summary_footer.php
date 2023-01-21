<div class="Card-footer">
    <div class="form-group">
        <div class=row>
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <button id="<?= $cart_id . "_" . $button_id_1 ?>" <?= $button_function_1 ?> class="btn btn-lg btn-info <?= $button_class_1 ?>"><i class="fa fa-arrow-left"></i>&nbsp;<?= trans($button_label_1) ?></button>
                <button id="<?= $cart_id . "_" . $button_id_2 ?>" <?= $button_function_2 ?> class="btn btn-lg btn-primary <?= $button_class_2 ?>"><i class="<?= $fa_button_icon ?>"></i>&nbsp;<?= trans($button_label_2) ?></button>
                <input type="hidden" name="submit" value="submit" />
            </div>
        </div>
    </div>
</div>