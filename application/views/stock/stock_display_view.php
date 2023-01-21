<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/select2/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/core-style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<style>
.pretty.p-svg .state .svg {
    position: absolute;
    font-size: 1em;
    width: calc(1em + 2px);
    height: calc(1em + 2px);
    left: 0;
    z-index: 1;
    text-align: center;
    line-height: normal;
    top: calc((13% - (95% - 1em)) - 8%) !important;
    border: 1px solid transparent;
    opacity: 0;
}
.active .page-link{
    background-color: darkorange !important;
}
.nav div.active {
    color: white !important;
    background-color: #ff6600 !important;
    /* border-color: #ff8533 !important; */
}
</style>
<!-- <div class="content-wrapper"> -->
<!-- Main content -->
<section class="content p-0 connectedSortable">
    <div class="card card-default color-palette-bo px-0"
        style="background-color: transparent !important;  box-shadow: none;">
        <div class="card-header d-flex p-0" style="background-color: white;">
            <h3 class="card-title p-3">
                <i class="fa fa-pie-chart mr-1"></i>
                Stock Summary
            </h3>
        </div><!-- /.card-header -->
        <div class="nav h5" style="background-color: white;" hieght="50px">
            <div class="">
                <div class="tablinks active btn" style="background-color: inherit; color:black; font-size:20px;"
                    id="Stock_btn_1" onclick="openSTOCK(this.id,'Stock_1')" data-toggle="tab">stock summary</div>
            </div>
            <div class="">
                <div class="tablinks btn" style="background-color: inherit; color:black; font-size:20px; display:none"
                    id="Stock_btn_2" onclick="openSTOCK(this.id,'Stock_2')" data-toggle="tab">stock delivered</div>
            </div>
        </div>
        <div class="card-body px-3">
            <div class="tab-content p-0">
                <!-- Morris chart - Sales -->
                <div class="tabcontent1 tab-pane active" id="Stock_1">
                    <div id="collapsible_container">

                    </div>
                </div>
                <div class="tabcontent2 tab-pane" id="Stock_2">
                    <div id="collapsible_container_2">

                    </div>
                </div>
            </div>
        </div><!-- /.card-body -->
    </div>
</section>
</div>

<!-- First modal dialog -->
<div id="myModal" class="modal fade" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg" style="max-width: 60%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeader"></h4>
            </div>
            <div class="modal-body">
                <table id="liquortable" class="table table-hover" style="border-collapse: collapse !important; border: none !important;" width="100%">
                    <thead style="background-color: darkorange;">
                        <tr>
                            <th><center> Sr.no. </center></th>
                            <th><center> Irla/Regiment no. </center></th>
                            <th><center> Consumer name </center></th>
                            <th><center> Status </center></th>
                            <th><center> Issued Time </center></th>
                            <th><center> Sold Quantity </center></th>
                        </tr>
                    </thead>
                    <tbody id="stockdetailsbody">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
var baseurl = "<?php echo base_url(); ?>";

$(document).ready(function() {
    $('#Stock_btn_1').click();
    // $('#liquortable').DataTable();
});

function openSTOCK(evt, tabName) {
    var databaseObject = {
        csrf_test_name: csrfHash
    };
    // console.log(evt);
    if (tabName == "Stock_1") {
        $.ajax({
            url: baseurl + 'stock/Display_Stock/fetchstocksummary',
            type: 'post',
            data: databaseObject,
            dataType: 'html',
            success: function(response) {
                $('#collapsible_container').html(response);
                // Declare all variables
                var i, tabcontent, tablinks;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabcontent1");
                // console.log(tabcontent);
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Get all elements with class="tablinks" and remove the class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                    tablinks[i].className = tablinks[i].className.replace(" show", "");
                }

                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById(tabName).style.display = "block";
                document.getElementById(evt).className += " active show";
                // }
            }
        });
    }
    if (tabName == "Stock_2") {
        $.ajax({
            url: baseurl + 'stock/Display_Stock/fetchstocksummary',
            type: 'post',
            data: databaseObject,
            dataType: 'html',
            success: function(response) {
                console.log(response);
                if (response.length > 0) {
                    $('#collapsible_container').empty();
                    $('#collapsible_container_2').empty();
                    html = '<div id="infotable">' +
                        '<div class="card animate__animated animate__fadeInLeft collapsed-card"' +
                        'style="margin-bottom: 1px !important; border-radius:0px !important; background-color: #ff944d !important;">' +
                        '<div class="card-header">' +
                        '<h5 class="">' +
                        '<div class="row">' +
                        '<div class="col-md-2"><center>Liquor Image</center></div>' +
                        '<div class="col-md-3"><center>Name</center></div>' +
                        '<div class="col-md-2"><center>Type</center></div>' +
                        '<div class="col-md-2"><center>Volume</center></div>' +
                        '<div class="col-md-2"><center>Available Quantity</center></div>' +
                        '<div class="col-md-1"></div>' +
                        '</div>' +
                        '</h5>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    for (let i = 0; i < response.length; i++) {
                        html += '<div id="infotable">' +
                            '<div class="card animate__animated animate__fadeInLeft callout callout-' +
                            response[i].class + ' collapsed-card"' +
                            'style="margin-bottom: 1px !important; border-radius:0px !important;">' +
                            '<div class="card-header">' +
                            '<h6 class="">' +
                            '<div class="row">' +
                            '<div class="col-md-2"><img src="' + baseurl + response[i].liquor_image +
                            '" width="80px"></div>' +
                            '<div class="col-md-3" style="margin-top:20px"><center>' + response[i]
                            .liquor_name + '</center></div>' +
                            '<div class="col-md-2" style="margin-top:20px"><center>' + response[i]
                            .liquor_type + '</center></div>' +
                            '<div class="col-md-2" style="margin-top:20px"><center>' + response[i]
                            .liquor_ml + '</center></div>' +
                            '<div class="col-md-2" style="margin-top:20px"><center>' + response[i]
                            .available_quantity +
                            '</center></div>' +
                            '</div>' +
                            '</h6>' +
                            '<div class="card-tools">' +
                            '<button type="button" style="margin-top:20px" onclick="openmodal(' + response[
                                i].entity_mapping_id + ')"' +
                            ' id="' + response[i].entity_mapping_id + '" class="btn btn-tool">' +
                            '<i class="fa fa-info-circle" style="font-size:20px"></i>' +
                            '</button>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    }
                    $('#collapsible_container_2').append(html);
                    // Declare all variables
                    var i, tabcontent, tablinks;

                    // Get all elements with class="tabcontent" and hide them
                    tabcontent = document.getElementsByClassName("tabcontent2");
                    // console.log(tabcontent);
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }

                    // Get all elements with class="tablinks" and remove the class "active"
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                        tablinks[i].className = tablinks[i].className.replace(" show", "");
                    }

                    // Show the current tab, and add an "active" class to the button that opened the tab
                    document.getElementById(tabName).style.display = "block";
                    document.getElementById(evt).className += " active show";
                }
            }
        });
    }
}

function openmodal(entity_mapping_id,Modalheader) {
    var databaseObject = {
        csrf_test_name: csrfHash,
        entity_mapping_id: entity_mapping_id
    };
    $.ajax({
        url: baseurl + 'stock/Display_Stock/fetchdetailsofstocksummary',
        type: 'post',
        data: databaseObject,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var td = "";
            $('#modalHeader').html(Modalheader);
            var div = document.getElementById('stockdetailsbody');
                                    while (div.firstChild) {
                                        div.removeChild(div.firstChild);
                                    }
            // $('#liquortable').DataTable().clear().destroy();
            for (let i = 0; i < response.length; i++) {
                td += '<td><center>' + (i + 1) + '</center></td>' +
                    '<td><center>' + response[i].irla + '</center></td>' +
                    '<td><center>' + response[i].firstname + '</center></td>' +
                    '<td><center>' + response[i].status + '</center></td>' +
                    '<td><center>' + response[i].issued_time + '</center></td>' +
                    '<td><center>' + response[i].quantity + '</center></td>';
            }
            document.getElementById('stockdetailsbody').innerHTML=td;
            $('#liquortable').DataTable({
                // destroy: true
            });
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#myModal').modal('show');
        }
    });

}
</script>
<!-- <script src="<?= base_url() ?>assets/plugins/select2/select2.full.min.js"></script> -->
<!-- <script src="<?= base_url() ?>assets/js/module/stock/stockdisplay.js"></script> -->