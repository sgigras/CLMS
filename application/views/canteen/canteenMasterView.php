<?php $form_data = $this->session->flashdata('form_data'); ?>
<!-- Content Wrapper. Contains page content -->
<!--<div class="content-wrapper">-->
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= $title ?></h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="<?php echo site_url("master/CanteenMaster/addCanteenClub"); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_new_canteen') ?></a>
                &nbsp;
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <div class="card-body">

            <?php $this->load->view('admin/includes/_messages.php') ?>

            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="50"><?= trans('id') ?></th>
                        <th><?= trans('canteen_name') ?></th>
                        <th><?= trans('establishment_type') ?></th>
                        <th><?= trans('state') ?></th>
                        <th width="200"><?= trans('canteen_chairman') ?></th>
                        <th width="200"><?= trans('canteen_supervisor') ?></th>
                        <th width="200"><?= trans('canteen_executive') ?></th>
                        <th width="200"><?= trans('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//                        $i = 1;
                    foreach ($records as $record):
                        ?>
                        <tr>
                            <td><?php echo $record['id']; ?></td>
                            <td><?php echo $record['entity_name']; ?></td>
                            <td><?php echo $record['canteen_club']; ?></td>
                            <td><?php echo $record['state']; ?></td>
                            <td><?php echo $record['chairman']; ?></td>
                            <td><?php echo $record['supervisor']; ?></td>
                            <td><?php echo $record['executive']; ?></td>
                            <td>
                                <a href="<?php echo site_url("master/CanteenMaster/editCanteenClub/" . $record['id']); ?>" class="btn btn-warning btn-xs mr5" >
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<!-- /.content -->
<!--</div>-->

<script>
    $("body").on("change", ".tgl_checkbox", function () {
        $.post('<?= base_url("admin/admin_roles/change_status") ?>',
                {
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                    id: $(this).data('id'),
                    status: $(this).is(':checked') == true ? 1 : 0
                },
                function (data) {
                    $.notify("Status Changed Successfully", "success");
                });
    });

</script>