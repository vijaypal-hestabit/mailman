<?php

error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/mailController.php";
session_start();
class inbox
{
    function __construct()
    {
        
        $userid = ($_SESSION['id']);
        $data = json_decode(file_get_contents("php://input"), TRUE);
        $page = $data['page'];

        $this->mails($userid,$page);
    }

    public function mails($userid,$page)
    {
        $check_user = new mailController();
        $data = $check_user->mails($userid,$page);
        echo json_encode([
            'data'=>$data[0],
            'user_records'=>$data[1]
        ]);
    }
}
$ob = new inbox();
