<?php
include_once '../connection.php';
session_start();
class mailDetailsController
{

    public function __construct()
    {
        $this->db = new dbConnection();
        $this->conn = $this->db->conn;
    }

    public function fetchSingleMail($inbox_id)
    {
        $sql = "SELECT id from inbox where id in ($inbox_id) and receiver_id={$_SESSION['id']}";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            $data = $res->fetch_all(MYSQLI_ASSOC);
            $ids = [];
            foreach ($data as $key => $value) {
                array_push($ids, $value['id']);
            }
            $values = implode(',', $ids);
            $sql = "UPDATE inbox set is_read = 1 where id in ($values)";
            $res = $this->conn->query($sql);
        }
        $check_bcc_user = "SELECT bcc_receiver_id,inbox_id as id FROM blind_carbon_copy WHERE bcc_receiver_id = {$_SESSION['id']} and inbox_id in ($inbox_id)";
        $res = $this->conn->query($check_bcc_user);
        if ($res->num_rows > 0) {
            $data = $res->fetch_all(MYSQLI_ASSOC);
            $ids = [];
            foreach ($data as $key => $value) {
                array_push($ids, $value['id']);
            }
            $values = implode(',', $ids);
            $sql = "UPDATE blind_carbon_copy set is_read = 1 where inbox_id in ($values)";
            $res = $this->conn->query($sql);
        }
        $check_cc_user = "SELECT cc_receiver_id,inbox_id as id  FROM carbon_copy WHERE cc_receiver_id = {$_SESSION['id']} and inbox_id in ($inbox_id)";
        $res = $this->conn->query($check_cc_user);
        if ($res->num_rows > 0) {
            $data = $res->fetch_all(MYSQLI_ASSOC);
            $ids = [];
            foreach ($data as $key => $value) {
                array_push($ids, $value['id']);
            }
            $values = implode(',', $ids);
            $sql = "UPDATE carbon_copy set is_read = 1 where inbox_id in ($values)";
            $res = $this->conn->query($sql);
        }
        $sql = "SELECT bcc_receiver_id from blind_carbon_copy where bcc_receiver_id={$_SESSION['id']} and inbox_id=$inbox_id";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            $sql = "SELECT * from inbox left join carbon_copy on (carbon_copy.inbox_id=inbox.id) left join blind_carbon_copy on (blind_carbon_copy.inbox_id=inbox.id) where inbox.id=$inbox_id";
        } else {
            $sql = "SELECT * from inbox left join carbon_copy on (carbon_copy.inbox_id=inbox.id) where inbox.id=$inbox_id";
        }
        $res = $this->conn->query($sql);
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * from attachments where inbox_id=$inbox_id";
        $res = $this->conn->query($sql);
        $attachments = $res->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT id,email from users";
        $res = $this->conn->query($sql);
        $user_records = $res->fetch_all(MYSQLI_ASSOC);
        $hashed_user_records = [];
        foreach ($user_records as $key => $value) {
            $hashed_user_records[$value['id']] = $value['email'];
        }
        echo json_encode([
            'message_data' => $data,
            'attachments' => $attachments,
            'users' => $hashed_user_records
        ]);
    }
    
}

