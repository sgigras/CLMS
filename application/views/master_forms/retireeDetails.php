<?php $form_data = $this->session->flashdata('form_data'); ?>
<!-- Content Wrapper. Contains page content -->
<!--<div class="content-wrapper">-->
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = '<?= base_url() ?>';
</script>
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fa fa-list"></i>&nbsp; Retiree Data</h3>
            </div>
            <div class="d-inline-block float-right">
                <!-- <a href="<?php echo site_url("master/CanteenMaster/addCanteenClub"); ?>" class="btn btn-secondary"><i class="fa fa-plus"></i> <?= trans('add_new_canteen') ?></a> -->
                &nbsp;
                <!-- <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a> -->
            </div>
        </div>
        <div class="card-body">

            <?php $this->load->view('admin/includes/_messages.php') ?>

            <table id="example2" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="50">Sr. No.</th>
                        <th>Personnel No.\Regimental No.</th>
                        <th>Name</th>
                        <th>Requested By</th>
                        <th>Requested Time</th>
                        <th>Verification Status</th>
                        <th width="200"><?= trans('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($verification_data as $record) :
                    ?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><?php echo $record['irla']; ?></td>
                            <td><?php echo $record['name']; ?></td>
                            <td><?php echo $record['requested_by']; ?></td>
                            <td><?php echo $record['requested_time']; ?></td>
                            <td><?php echo $record['verification_status']; ?></td>
                            <td>
                                <?php $id = $record['id'] ?>
                                <input type="hidden" value="<?= $record['hrms_id']; ?>" id="hrms_id_<?= $id ?>" name="hrms_id_<?= $id ?>">
                                <button class="btn btn-md btn-warning verification_check" id="verification_check<?= $id ?>">
                                    <i class="fa fa-eye"></i>
                                </button>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
</div>
<!-- First modal dialog -->
<div id="myModal" class="modal fade" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" style="max-width: 1140px;margin-left:280px !important;    height: calc(100% - 3.5rem);">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color:#1266F1;color:white">
                <h4 class="modal-title" id="modalHeader">Verify User</h4>
            </div>
            <div class="modal-body p-0" id="retiree_details_body">
                <?php // $this->load->view('master_forms/showRetireeDetails');
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>



<!-- /.content -->
<!--</div>-->


<script src="<?= base_url() ?>assets/js/module/verifyRetireeDetails.js"></script>