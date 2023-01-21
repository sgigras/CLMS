<div class="Card-footer">
    <div class="form-group">
        <div class=row>
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <?php
                decideFooterButtons($cart_footer_data)
                ?>
                <input type="hidden" name="submit" value="submit" />
            </div>
        </div>
    </div>
</div>

<?php
function decideFooterButtons($cart_footer_data)
{
    $mode = $cart_footer_data['cart_footer_button_mode'];
    $cart_id = $cart_footer_data['cart_id'];
    $button_id_1 = $cart_footer_data['button_id_1'];
    $button_id_2 = $cart_footer_data['button_id_2'];
    $button_label_1 = $cart_footer_data['button_label_1'];
    $button_label_2 = $cart_footer_data['button_label_2'];
    $button_class_1 = $cart_footer_data['button_class_1'];
    $button_class_2 = $cart_footer_data['button_class_2'];
    $fa_button_icon = $cart_footer_data['fa_button_icon'];

    switch ($mode) {
        case 'place_order':
?>
            <button id="<?= $cart_id . "_" . $button_id_2 ?>" class="btn btn-md btn-outline-primary m-1 <?= $button_class_2 ?>" style="float:right"><i class="<?= $fa_button_icon ?>"></i>&nbsp;<?= trans($button_label_2) ?></button>
            <button id="<?= $cart_id . "_" . $button_id_1 ?>" class="btn btn-md btn-outline-secondary m-1 <?= $button_class_1 ?>" style="float:right"><i class="fa fa-arrow-left"></i>&nbsp;<?= trans($button_label_1) ?></button>



        <?php break;
        case 'deliver_order':
            $button_function_1 = "onclick=edit_order_delivery(" . "'" . $cart_id . "'" . ")";
            $button_function_2 = "onclick=submit_order_delivery(" . "'" . $cart_id . "'" . ")";
        ?>

            <button id="<?= $cart_id . "_" . $button_id_2 ?>" <?= $button_function_2 ?> class="btn btn-lg btn-md btn-outline-primary m-1 <?= $button_class_2 ?>" style="float:right"><i class="<?= $fa_button_icon ?>"></i>&nbsp;<?= trans($button_label_2) ?></button>
            <button id="<?= $cart_id . "_" . $button_id_1 ?>" <?= $button_function_1 ?> class="btn btn-lg btn-md btn-outline-secondary m-1 <?= $button_class_1 ?>" style="float:right"><i class="fa fa-arrow-left"></i>&nbsp;<?= trans($button_label_1) ?></button>

<?php


        default:
            '';
            break;
    }
}

?>