<?php

function create_master_table($page_head, $table_head, $table_data, $addurl, $editurl) {
    $master_table = '<div class = "content-wrapper">'
            . '<section class = "content">'
            . '<div class = "card">'
            . '<div class = "card-header">'
            . "<div class = 'd-inline-block'>"
            . "<h3 class = 'card-title><i class = 'fa fa-list'></i>&nbsp"
            . "<i class = 'fa fa-list'></i>&nbsp" . $page_head . "&nbsp</h3>"
            . "</div>"
            . "<div class = 'd-inline-block float-right'>"
            . "<a href ='site_url($addurl); ' class = 'btn btn-secondary'><i class = 'fa fa-plus'></i>" . trans('add_new_canteen') . "</a>"
            . "&nbsp" . "<a href = '#' onclick='window.history.go(-1); return false;' class='btn btn-primary pull-right><i class='fa fa-reply mr5'></i>" . trans('back') . "</a>"
            . "</div>"
            . "</div>"
            . "<div class='card-body'>";
//    $this->load->view('admin/includes/_messages.php');
    $master_table .= "<table id='example2' class='table table-bordered table-hover'>"
            . "<thead>"
            . "<tr><th>" . trans('id') . "</th>";
    foreach ($table_head as $column_header) {

        $column_array = explode(":", $column_header);
        $master_table .= "<th>" . trans($column_array[0]) . "</th>";
    }
    $master_table .= "<th>Action</th></tr>"
            . "</thead>"
            . "<tbody>";
    $i = 1;
    foreach ($table_data as $record) {
//        echo '<pre>';
//        print_r($record);
//        echo '</pre>';
        $master_table .= "<tr><td>" . $i++ . "</td>";
        foreach ($table_head as $column_header) {
            $data_key = explode(":", $column_header);
//            print_r($data_key);
//            echo '<pre>';
//            print_r($record[$data_key[1]]);
//            echo '</pre>';
            $master_table .= "<td>" . $record[$data_key[1]] . "</td>";
        }
        $id = $record['id'];
        $master_table .= "<a href='" . site_url($editurl . "/" . $id) . " ?>' class='btn btn-warning btn-xs mr5' >"
                . "<i class='fa fa-edit'></i></a></tr>";
    }
    $master_table .= "</tbody>"
            . "</table>"
            . "</div>"
            . "</div>"
            . "</section>"
            . "</div>";

    return $master_table;
}
