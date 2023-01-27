<?php
function UploadPics($folder, $file, $key)
    {
        $folder = 'C:/wamp64/www/CLMS/uploads/' . $folder . "/";
        $responseArray = array();

        $file = $folder . $file;
        if (move_uploaded_file($_FILES[$key]['tmp_name'], $file)) {
            $responseArray = array("status" => 1, "data" => array(), "msg" => "Upload Successfully");
        } else {
            $responseArray = array("status" => 0, "data" => array(), "msg" => "Upload Failed");
        }
        return $responseArray;
    }
