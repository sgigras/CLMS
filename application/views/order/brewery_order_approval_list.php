
<!-- Content Wrapper. Contains page content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"><i class="fas fa-store"></i>Order List</h3>
            </div>
        </div>
        <br>
        <div class="card-body">
            <table id="master_table" class="table table-bordered table-hover" style="border-collapse: collapse !important;border-color: #DA0037">
                <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
                    <tr>
                        <th style="width:150px;">Sr.No</th>
                        <th style="border-color: #007bff">Brewery Name</th>
                        <th style="border-color: #007bff">Order Code</th>
                        <th style="border-color: #007bff">Requested By</th>
                        <th style="border-color: #007bff">Requested Date</th>
                        <th style="border-color: #007bff">Approved By</th>
                        <th style="border-color: #007bff">Approval Date</th>
                        <th style="border-color: #007bff">Status</th>
                        <th style="border-color: #007bff">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $row_count = 1;
                    foreach ($orderlist as $row) {
                    ?>
                    <tr>
                        <td><?php echo $row_count; ?>.</td>
                        <td><?php echo $row["brewery_name"]; ?></td>
                        <td><?php echo $row["brewery_order_code"]; ?></td>
                        <td><?php echo $row["requested_by"]; ?></td>
                        <td><?php echo $row["creation_time"]; ?></td>
                        <td><?php echo $row["approved_by"]; ?></td>
                        <td><?php echo $row["approved_time"]; ?></td>
                        <td><?php echo $row["approval_status"];  ?></td>
                        <?php if ($row["approval_status"]=="P"){?>
                            <td><a href="loadBreweryOrder?order_id=<?php echo $row["brewery_order_code"]?>">Approve/Reject</a></td>
                        <?php } elseif ($row["approval_status"]=="R") {?>
                            <td><a href="javascript:vid(0)">Reject</a></td>
                        <?php } else {?>
                            <td><a href="printBreweryOrder?order_id=<?php echo $row["brewery_order_code"]?>">Print</a></td>
                        <?php }?>
                    </tr>
                    <?php 
                    $row_count++;
                    } ?>                   
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    $(function() {
        $('#master_table').DataTable({});
    });
</script>
<!-- /.content -->


