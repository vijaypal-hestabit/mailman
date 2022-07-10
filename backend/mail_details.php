<?php
include "validator.php";
include "process.php";
include "controller/mailDetailsController.php";
session_start();
class mail_details
{
    function __construct()
    {
        $data = json_decode(file_get_contents("php://input"), TRUE);
        $mail_id = $data['mail_id'];

        $this->mail_details($mail_id);
    }

    public function mail_details($mail_id)
    {
        $dashboardControllerObj = new mailDetailsController();
        $dashboardControllerObj->fetchSingleMail($mail_id);

    }
    
}
$ob = new mail_details();
