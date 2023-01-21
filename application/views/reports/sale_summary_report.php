<!-- Author:Ujwal Jain
Subject:Sales Report
Date:16-12-21 -->

<?php $form_data = $this->session->flashdata('form_data');   ?>




<section class="content p-0">
    <div class="card card-default color-palette-bo">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-info-circle"></i>
                    Liquor Sales Summary</h3>
            </div>

        </div>


        <!-- /.card-header -->
        <!-- form start -->
        <?php $this->load->view('admin/includes/_messages.php') ?>
        <?php echo form_open_multipart(base_url('reports/Sales_Report/liquor_sales_summary'), 'class="form-horizontal"');  ?>
        <div class="card-body">
            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select  Start Time" value="<?= (isset($form_data['start_date']) ? $form_data['start_date'] : ''); ?>" autocomplete="off">

                    </div>
                </div>

                <!-- <div class="col-sm-6">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control" placeholder="Select End  Time" value="<?= (isset($form_data['end_date']) ? $form_data['end_date'] : ''); ?>" autocomplete="off">

                    </div>
                </div> -->


                <div class="col-md-12">
                    <div class="form-group">
                        <div class="card-footer mt-4 card-footer bg-transparent p-0 d-flex justify-content-end">

                            <button class="btn btn-warning mr-2" onclick="window.location=<?= base_url('reports/Sales_Report/liquor_sales_summary') ?>"><i class="fa fa-eraser mr-2"></i>Reset</button>
                            <button type="submit" name="submit" value="submit" id="trip_details" class="btn btn-success"><i class="fa fa-save mr-2"></i>View</button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php
if (isset($details)) {
    // print_r($cost_details);
?>
    <div style="margin-left: 10px; margin-right: 10px; margin-bottom: 20px; " class="card" id="temp_voilation">
        <!-- <input type="button" class="btn btn-info" style="margin-left: 10px;margin-top: 10px;width: 65px;" value="PDF"
        onclick="generate_pdf()"> -->
        <div class="card-body table-responsive">
            <table id="na_datatable" class="table datatable  table-bordered datatable-striped" width="100%">


                <thead class="m0 mb5" style="white-space:nowrap">
                    <tr>
                        <th colspan="5" style="text-align:center">
                            <?= $entity_name[0]['entity_name']; ?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="5">Liquor Sales Summary Report For:-
                            <?= $form_data['start_date'] ?></th>
                    </tr>

                    <tr>

                        <th>Sr No.</th>
                        <th>Liquor Details</th>
                        <th>Unit Sell Price</th>
                        <th>Sale Qty</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>

                    <?php $i = 0;
                    $sale_value = floatval(0.00);
                    $sale_qty = intval(0.00); ?>
                    <?php foreach ($details as $row) { ?>
                        <tr>
                            <td>
                                <?= ++$i; ?>
                            </td>
                            <td>
                                <?= $row['liquor_details'] ?>
                            </td>


                            <td>
                                <?= $row['liquor_unit_sell_price'] ?>
                            </td>

                            <td>
                                <?= $row['liquor_sale_qty'] ?>
                            </td>


                            <td>
                                <?= $row['liquor_total_sale_price'] ?>
                            </td>

                        </tr>
                    <?php
                        $sale_qty = $sale_qty + intval($row['liquor_sale_qty']);
                        $sale_value = $sale_value + floatval($row['liquor_total_sale_price']);
                    } ?>
                    <tr>
                        <td style="color:white"> <?= ++$i; ?></td>
                        <td>Total</td>
                        <td></td>
                        <td><?= $sale_qty; ?> </td>
                        <td><?= $sale_value ?></td>
                    </tr>
                    <!-- <tr>
                <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td  colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="1"></th>
                </tr> -->

                </tbody>


            </table>
        </div>
    </div>
<?php  }  ?>
</div>
</div>
<!-- /.content-wrapper -->
<link href="<?= base_url() ?>assets/plugins/datepicker/datepicker3.css" rel="stylesheet">
<script src="<?= base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js" defer></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css">
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jquery.dataTables.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.bootstrap4.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/dataTables.buttons.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatablesbtn/buttons.html5.min.js" defer></script>
<script src="<?= base_url() ?>assets/plugins/jspdf/jspdf.min.js" defer></script>



<script type="text/javascript">
    $(document).ready(function() {
        // alert("ready");
        $('#start_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: "today",
            orientation: 'top'
        }).on('changeDate', function() {
            // set the "toDate" start to not be later than "fromDate" ends:
            $("#end_date").datepicker('setStartDate', $(this).val());
        });
        $('#end_date').datepicker({
            format: 'yyyy-mm-dd',
            endDate: "today",
            orientation: 'top'
        }).on('changeDate', function() {
            // set the "toDate" start to not be later than "fromDate" ends:
            $("#start_date").datepicker('setEndDate', $(this).val());
        });

        $('#na_datatable').DataTable({
            dom: 'Bfrtip',

            buttons: [{
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                className: 'btn btn-info',
                title: 'Liquor Sales Report',
                customize: function(pdfDocument) {
                    let headerRows = [];
                    let noOfColumn = 5;
                    let rowSpansOfColumns = [];
                    for (let i = 0; i < noOfColumn; i++) {
                        rowSpansOfColumns.push([]);
                    }
                    let noOfExtraHeaderRow = 2;
                    pdfDocument.content[1].table.headerRows = noOfExtraHeaderRow + 1;
                    for (let i = 1; i <= noOfExtraHeaderRow; i++) {
                        let headerRow = [];
                        let colIdx = 0;
                        while (colIdx < rowSpansOfColumns.length && rowSpansOfColumns[colIdx]
                            .includes(i)) {
                            headerRow.push({});
                            colIdx++
                        }

                        $('#na_datatable').find("thead>tr:nth-child(" + i + ")>th").each(
                            function(index, element) {
                                let colSpan = parseInt(element.getAttribute("colSpan"));
                                let rowSpan = parseInt(element.getAttribute("rowSpan"));
                                if (rowSpan > 1) {
                                    for (let col = colIdx; col < colIdx + colSpan; col++) {
                                        for (let row = i + 1; row < i + rowSpan; row++) {
                                            rowSpansOfColumns[col].push(row);
                                        }
                                    }
                                }
                                headerRow.push({
                                    text: element.innerHTML,
                                    style: "tableHeader",
                                    colSpan: colSpan,
                                    rowSpan: rowSpan,

                                });
                                colIdx++
                                for (let j = 0; j < colSpan - 1; j++) {
                                    headerRow.push({});
                                    colIdx++
                                }
                                while (colIdx < rowSpansOfColumns.length &&
                                    rowSpansOfColumns[colIdx].includes(i)) {
                                    headerRow.push({});
                                    colIdx++
                                }
                            });

                        headerRows.push(headerRow);
                    }
                    console.log(headerRows);
                    for (let i = 0; i < headerRows.length; i++) {
                        pdfDocument.content[1].table.body.unshift(headerRows[headerRows.length -
                            1 - i]);
                    }
                    // var objLayout = {};
                    // 		objLayout['hLineWidth'] = function(i) { return .5; };
                    // 		objLayout['vLineWidth'] = function(i) { return .5; };
                    // 		objLayout['hLineColor'] = function(i) { return '#aaa'; };
                    // 		objLayout['vLineColor'] = function(i) { return '#aaa'; };
                    // 		objLayout['paddingLeft'] = function(i) { return 4; };
                    // 		objLayout['paddingRight'] = function(i) { return 4; };
                    // 		pdfDocument.content[1].layout = objLayout;
                }
            }]
        });

    });

    function generate_pdf() {
        var doc = new jsPDF();
        var elementHTML = $('#pdf_div').html();
        var specialElementHandlers = {
            '#editor': function(element, renderer) {
                return true;
            }
        };
        doc.fromHTML(elementHTML, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        // Save the PDF
        doc.save('Liquor_Sales_Report.pdf');
    }
</script>