<?php 

error_reporting(E_ALL);
ini_set("diplay_errors",1);

require 'connection.php';
class CommonFunctions extends dbConnection{

    public function saveFiles($dir,$files){
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $name=strtotime(date("Y-m-d H:i:s")).'_'.$files['name'];
        $path = $dir.$name;
        $tmp_file_location = $files['tmp_name'];
        $res = move_uploaded_file($tmp_file_location,$path);
        return $name;
    }
}


