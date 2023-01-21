<?php
$canteen_name = $cart_data[0]['entity_details'];
$order_by_name = $cart_data[0]['order_by_name'];
$dispatch_name = $cart_data[0]['dispatch_name'];
$ordertime = $cart_data[0]['ordertime'];
$dispatch_time = $cart_data[0]['dispatch_time'];
$order_code = $cart_data[0]['order_code'];

?>
<div class="card">
    <div class="card-header" style="background-color:cadetblue">
        <h3 class="card-title" style="color:white">Delivery From:- <?= $canteen_name; ?>
            <div style="float:right;font-size:14px">
                All the quantities(Qty) are in bottles
            </div>
        </h3>

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover" id="receive_liquor_table">
            <tbody>
                <tr>
                    <td colspan="9" style="padding:0px !important">
                        <table class="table mb-0">
                            <tr style="background-color:#78c7cabf">
                                <th>Order Code</th>
                                <th>Order By</th>
                                <th>Order Time</th>
                                <th>Dispatch By</th>
                                <th>Dispatch Time</th>
                            </tr>
                            <tr>
                                <td><?= $order_code; ?></td>
                                <td><?= $order_by_name ?></td>
                                <td><?= $ordertime ?></td>
                                <td><?= $dispatch_name ?></td>
                                <td><?= $dispatch_time ?></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr style="background-color:#78c7ca63">
                    <th>Sr No</th>
                    <th>Liquor Type</th>
                    <th>Liquor Name</th>
                    <th>Dispatch Qty</th>
                    <th>Dispatch Unit Cost</th>
                    <th>Dispatch Total Cost</th>
                    <th>Receive Total Qty</th>
                    <th>Damage Qty</th>
                    <th>Total Payable Amount</th>
                </tr>
                <?php
                $i = 0;
                foreach ($cart_data as $row) { ?>
                    <tr>
                        <?php $i++; ?>
                        <td><?= $i ?></td>
                        <td><?= $row['liquor_type'] ?></td>
                        <td><?= $row['liquor_name'] ?></td>
                        <td id="dispatch_total_quantity_<?= $i ?>"><?= intval($row['dispatch_total_quantity']) ?></td>
                        <td id="unit_sell_price_<?= $i ?>"><?= $row['unit_selling_price'] ?></td>
                        <td><?= $row['dispatch_total_cost'] ?></td>
                        <td id="received_total_bottles_display_<?= $i ?>">
                            <?= intval($row['received_total_bottles']) ?>
                        </td>
                        <td>

                            <input type="hidden" id="row_id_<?= $i ?>" value="<?= $row['id'] ?>">
                            <input type="hidden" id="lem_id_<?= $i ?>" value="<?= $row['liquor_entity_id'] ?>">

                            <input type="text" maxlength="10" placeholder="Enter damage quantity" onchange="checkQuantity(this.id)" class="form-control received_total_bottles" id="damage_quantity_<?= $i ?>" value="<?= intval("0") ?>">
                            <input type="hidden" id="received_total_bottles_<?= $i ?>" value="<?= intval($row['received_total_bottles']) ?>">

                        </td>
                        <td id="total_recevied_cost_<?= $i ?>"><?= $row['received_total_cost'] ?></td>
                    </tr>
                <?php  } ?>
            </tbody>
        </table>
    </div>
</div>