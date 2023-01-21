<?php
function UploadPics($folder, $file, $key)
    {

        // $folder = 'C:/xampp/htdocs/bsf_clms/CLMS/uploads/' . $folder . "/";
        
        $folder = '/var/www/html/uploads/' . $folder . "/";
        $responseArray = array();

        //         $destination_path = getcwd().DIRECTORY_SEPARATOR;
        // $target_path = $destination_path . basename( $_FILES["profpic"]["name"]);
        // @move_uploaded_file($_FILES['profpic']['tmp_name'], $target_path)
        // $year = date("Y");
        // $month = date("m");
        // $day = date("d");
        // $date_folder = $year . $month . $day;
        // $folder .= $date_folder . '/';
        // if (!is_dir($folder)) {
        //     mkdir($folder, 0777, true);
        //     chmod($folder, 0755);
        // }
        // print_r("File ",$file);
        // print_r("Folder ",$folder);
        $file = $folder . $file;
        // print_r("concat file ",$file);
        // echo 'inhelper';
        // print_r($_FILES);
        // echo 'inhelper';
        // print_r(" Move Uploaded File ".move_uploaded_file($_FILES[$key]['tmp_name'], $file));
        if (move_uploaded_file($_FILES[$key]['tmp_name'], $file)) {
            $responseArray = array("status" => 1, "data" => array(), "msg" => "Upload Successfully");
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Upload Failed");
        }

        return $responseArray;
    }
