<!-- Content Wrapper. Contains page content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fas fa-store"></i>&nbsp; <?= $title ?></h3>
            </div>
            <div class="d-inline-block float-right">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn  btn-info pull-right" style="border-radius:7px"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
            </div>
        </div>
        <br>
    </div>
    <div class="card card-body">

        <?php $this->load->view('master/simple_table_field', array("css_class" => "table table-hover table-stripped", "table_header" => BOOKED_LIQUOR_RECORD, "table_data_array" => $booked_liquor));
        // echo '<pre>';
        // print_r($booked_liquor);
        // echo '</pre>'; 
        ?>
    </div>



</section>

<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>