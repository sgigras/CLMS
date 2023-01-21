
<div class="table-responsive">
    <!-- <table id="example2" style="white-space:nowrap;" class="table  table-bordered table-hover "> -->
    <table id="master_table" class="table table-bordered table-hover" style="white-space:nowrap;border-collapse: collapse !important;border-color: #DA0037">
        <thead style='background-color:#dc3545;color:white;border-color: #DA0037;box-shadow: 0px 1px 1px 0px #DA0037;'>
            <tr>
                <th style="border-color: #DA0037">Sr. No</th>
                <?php
                $table_head_array = unserialize($table_head);
                // print_r($table_head_array);
                // die();
                foreach ($table_head_array as $column_header) {
                    $column_array = explode(":", $column_header);
                    echo "<th style='border-color: #DA0037'>" . trans($column_array[0]) . "</th>";
                }
                ?>

                <!-- <th style="border-color: #DA0037">Action</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $count=1;
            foreach ($table_data as $record) {
                echo "<tr>";
                echo "<td>" . $count++ . "</td>";

                foreach ($table_head_array as $column_header) {
                    $data_key = explode(":", $column_header);
                    
                    echo "<td>" . $record[$data_key[1]] . "</td>";
                }
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
    <?php // $this->load->view('master/table_tr_td', array("table_header" => USER_DETAILS_TABLE, "table_data_array" => $user_details)) 
    ?>
    <!-- </table> -->
</div>
<script>
    $('#master_table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                        extend: 'excel',
                        className:'btn btn-info',
                        title: '<?= $report_title; ?>',
                    }]
                    
                });
</script>