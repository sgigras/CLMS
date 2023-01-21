        <tr>
            <?php
            $table_head_array = unserialize($table_header);
            foreach ($table_head_array as $column_header) {
                $column_array = explode(":", $column_header);
                echo "<th class='cart-table-text-align'><h6>" . trans($column_array[0]) . "<h6></th>";
            }
            ?>
        </tr>
        <?php
        foreach ($table_data_array as $record) {
            echo "<tr>";
            foreach ($table_head_array as $column_header) {
                $data_key = explode(":", $column_header);
                echo "<td>" . $record[$data_key[1]] . "</td>";
            }
            echo "</tr>";
        }
        ?>