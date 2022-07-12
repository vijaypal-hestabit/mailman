<?php
error_reporting(E_ALL);
ini_set("diplay_errors", 1);

include_once '../connection.php';

class DashboardController
{

    public function __construct()
    {
        $this->db = new dbConnection();
        $this->conn = $this->db->conn;
    }
    public function mark_read($messageIds,$page_name){
        if ($page_name=='Inbox'){
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and receiver_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set is_read = 1 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_bcc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE blind_carbon_copy set is_read = 1 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_cc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE carbon_copy set is_read = 1 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
        } 
    }

    public function mark_unread($messageIds,$page_name){
        if ($page_name=='Inbox'){
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and receiver_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set is_read = 0 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_bcc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE blind_carbon_copy set is_read = 0 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_cc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE carbon_copy set is_read = 0 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
        } 
    }

    public function delete($messageIds,$userId,$userType,$page_name){

        if ($page_name == 'Trash') {
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and sender_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set sender_delete = 2 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and receiver_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set receiver_delete = 2 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_bcc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE blind_carbon_copy set is_trash = 2 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_cc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE carbon_copy set is_trash = 2 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }

        }else if ($page_name=='Inbox'){
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and receiver_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set receiver_delete = 1 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_bcc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE blind_carbon_copy set is_trash = 1 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_cc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE carbon_copy set is_trash = 1 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
        }   
        else if($page_name=='Sent'){
            $sql = "UPDATE inbox Set sender_delete=1 where id IN (".implode(',',$messageIds).")";
            $res= $this->conn->query($sql);
        }else if($page_name=='Draft'){
            $sql = "UPDATE inbox Set sender_delete=1 where id IN (".implode(',',$messageIds).")";
            $res= $this->conn->query($sql);
        }
        echo json_encode($res);
    }
    
    public function restore($messageIds,$userId,$userType,$page_name){


        
        if ($page_name == 'Trash') {
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and sender_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set sender_delete = 0 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $sql = "SELECT id from inbox where id in (".implode(',',$messageIds).") and receiver_id={$_SESSION['id']}" ;
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE inbox set receiver_delete = 0 where id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_bcc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE blind_carbon_copy set is_trash = 0 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }
            $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in (".implode(',',$messageIds).")";
            $res = $this->conn->query($check_cc_user);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
                $ids=[];
                foreach ($data as $key => $value) {
                    array_push($ids,$value['id']);
                }
                $values=implode(',',$ids);
                $sql = "UPDATE carbon_copy set is_trash = 0 where inbox_id in ($values)";
                $res= $this->conn->query($sql);
            }

            echo json_encode('true');
        }
    }
}
