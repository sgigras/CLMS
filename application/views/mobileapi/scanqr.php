

<link  href="<?= base_url()?>assets/plugins/iCheck/all.css" rel="stylesheet">
<link  href="<?= base_url()?>assets/plugins/select2/select2.css" rel="stylesheet">

<script src="<?= base_url()?>assets/plugins/jquery/jquery.min.js" ></script>
<script>
    var domain="<?= base_url()?>";
    // console.log(domain);
</script>


<div  id="main-content">
<!--    <div class="row clearfix">-->
        <div class="container">
        <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <center><h3>QR Generate</h3></center>
                    </div>
                    <div class="body">
                        <!--<div class="row">-->
                            <center>
                            <div class="col s12">  
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Device Id<span style="color:red">*</span></label>
                                        <select class="form-control" multiple="multiple" id="device" style="width:100%">                                                   
                                            </select>
                                             <span id="vehicle" style="color:red"></span>
                                            <!-- <small id="device_error" class="validationError" style="color:red"></small> -->
                                        <!--</div>-->
                                    </div>
                                </div><br>
                            <div class="row">
                                <div class="col-md-12">
                                    <center><button type="button" class="btn btn-success" id="send" style="background-color: green;color: white" >Generate</button></center>
                                </div>
                            </div>
                            </div>
                        </center>
                        <br>
                        <div id="qrdata" ></div>
                    </div>
                </div>
           </div>
    </div>
</div> 

<script src="<?= base_url()?>assets/plugins/select2/select2.js"></script>
<script src="<?= base_url()?>assets/js/module/scanqr.js"></script>


