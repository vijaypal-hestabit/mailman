<?php
include "validator.php";
include "process.php";
include "controller/dashboardActionController.php";
session_start();
class action
{
    function __construct()
    {
        $data = json_decode(file_get_contents("php://input"), TRUE);
        $msg_ids = $data['selected'];
        if (isset($data['mark_read'])) {
            $this->mark_read($msg_ids,$data['page_name']);
        } else if (isset($data['mark_unread'])) {
            $this->mark_unread($msg_ids,$data['page_name']);
        } else if (isset($data['delete'])) {
            $this->delete($msg_ids, $_SESSION['id'], $data['delete_column'],$data['page_name']);
        } else if (isset($data['restore'])) {
            $this->restore($msg_ids, $_SESSION['id'], $data['delete_column'],$data['page_name']);
        }
    }

    public function mark_read($msg_ids,$page_name)
    {
        $dashboardControllerObj = new DashboardController();
        $dashboardControllerObj->mark_read($msg_ids,$page_name);
    }
    public function mark_unread($msg_ids,$page_name)
    {
        $dashboardControllerObj = new DashboardController();
        $dashboardControllerObj->mark_unread($msg_ids,$page_name);
    }
    public function delete($msg_ids, $userId, $delete_column,$page_name)
    {
        $dashboardControllerObj = new DashboardController();
        $dashboardControllerObj->delete($msg_ids, $userId, $delete_column,$page_name);
    }
    public function restore($msg_ids, $userId, $delete_column,$page_name)
    {
        $dashboardControllerObj = new DashboardController();
        $dashboardControllerObj->restore($msg_ids, $userId, $delete_column,$page_name);
    }
}
$ob = new action();
