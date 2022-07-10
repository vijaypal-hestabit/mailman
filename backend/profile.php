<?php
error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/profileController.php";

class profile
{
    function __construct()
    {
        $this->getDetail();
    }
    public function getDetail()
    {
        $profile_data = new profileController();
        $data = $profile_data->fetchProfile();
        echo json_encode($data);
    }
}
$ob = new profile();
