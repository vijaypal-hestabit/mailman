<?php
error_reporting(E_ALL);
ini_set("diplay_errors", 1);

include_once '../connection.php';

class mailController
{

    public function __construct()
    {
        $this->db = new dbConnection();
        $this->conn = $this->db->conn;
    }

    public function mails($userid, $page)
    {
        $sql = "SELECT * FROM inbox ";
        if ($page == 'inbox') {
            // fetch mail for to 
            $sql_to = "SELECT * FROM inbox WHERE receiver_id = $userid and draft_status=0 and receiver_delete=0";
            $result_to = $this->conn->query($sql_to);
            $rows_to = $result_to->fetch_all(MYSQLI_ASSOC);


            // fetch mail for cc
            $check_cc_user = "SELECT cc_receiver_id FROM carbon_copy WHERE cc_receiver_id = $userid";
            $result_cc = $this->conn->query($check_cc_user);
            if ($result_cc->num_rows > 0) {
                $sql_cc = "SELECT inbox.id,cc.cc_receiver_id AS receiver_id,inbox.short_subject_msg,inbox.sender_id AS sender_id,cc.is_trash as receiver_delete,inbox.sender_delete,inbox.is_replied,inbox.draft_status,inbox.delivered_date,inbox.full_message,cc.is_read as is_read FROM `inbox` join carbon_copy cc on inbox.id = cc.inbox_id WHERE cc.is_trash = 0 and cc.cc_receiver_id = $userid";
                $result_cc = $this->conn->query($sql_cc);
                $rows_cc = $result_cc->fetch_all(MYSQLI_ASSOC);
            } else {
                $rows_cc = [];
            }

            // fetch mail for bcc
            $check_bcc_user = "SELECT bcc_receiver_id FROM blind_carbon_copy WHERE bcc_receiver_id = $userid";
            $result_bcc = $this->conn->query($check_bcc_user);
            if ($result_bcc->num_rows > 0) {
                $sql_bcc = "SELECT inbox.id,bcc.bcc_receiver_id AS receiver_id,inbox.short_subject_msg,inbox.sender_id AS sender_id,bcc.is_trash as receiver_delete,inbox.sender_delete,inbox.is_replied,inbox.draft_status,inbox.delivered_date,inbox.full_message,bcc.is_read as is_read FROM `inbox` join blind_carbon_copy bcc on inbox.id = bcc.inbox_id WHERE bcc.is_trash = 0 and bcc.bcc_receiver_id= $userid";
                $result_bcc = $this->conn->query($sql_bcc);
                $rows_bcc = $result_bcc->fetch_all(MYSQLI_ASSOC);
            } else {
                $rows_bcc = [];
            }
            $data_duplicacy = array_merge($rows_to, $rows_cc, $rows_bcc);
            $data = array_unique($data_duplicacy, SORT_REGULAR);
        }
        if ($page == 'sent') {
            $sql .= "where inbox.sender_id = $userid and draft_status=0 and sender_delete=0";
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
            } else {
                $data = [];
            }
        }
        if ($page == 'draft') {
            $sql .= "where inbox.sender_id = $userid and draft_status=1 and sender_delete=0";
            $res = $this->conn->query($sql);
            if ($res->num_rows > 0) {
                $data = $res->fetch_all(MYSQLI_ASSOC);
            } else {
                $data = [];
            }
        }
        if ($page == 'trash') {

            // fetch trash mail from sender
            $sql_sent = "SELECT * FROM inbox WHERE sender_id = $userid and sender_delete=1";
            $result_sent = $this->conn->query($sql_sent);
            $rows_sent = $result_sent->fetch_all(MYSQLI_ASSOC);

            // fetch trash mail from to
            $sql_to = "SELECT * FROM inbox WHERE receiver_id = $userid and receiver_delete=1";
            $result_to = $this->conn->query($sql_to);
            $rows_to = $result_to->fetch_all(MYSQLI_ASSOC);


            // fetch trash mail for cc
            $check_cc_user = "SELECT cc_receiver_id FROM carbon_copy WHERE cc_receiver_id = $userid";
            $result_cc = $this->conn->query($check_cc_user);
            if ($result_cc->num_rows > 0) {
                $sql_cc = "SELECT inbox.id,cc.cc_receiver_id AS receiver_id,inbox.short_subject_msg,inbox.sender_id AS sender_id,cc.is_trash as receiver_delete,inbox.sender_delete,inbox.is_replied,inbox.draft_status,inbox.delivered_date,inbox.full_message,cc.is_read as is_read FROM `inbox` join carbon_copy cc on inbox.id = cc.inbox_id WHERE cc.is_trash = 1 and cc.cc_receiver_id = $userid";
                $result_cc = $this->conn->query($sql_cc);
                $rows_cc = $result_cc->fetch_all(MYSQLI_ASSOC);
            } else {
                $rows_cc = [];
            }

            // fetch trash mail for bcc
            $check_bcc_user = "SELECT bcc_receiver_id FROM blind_carbon_copy WHERE bcc_receiver_id = $userid";
            $result_bcc = $this->conn->query($check_bcc_user);
            if ($result_bcc->num_rows > 0) {
                $sql_bcc = "SELECT inbox.id,bcc.bcc_receiver_id AS receiver_id,inbox.short_subject_msg,inbox.sender_id AS sender_id,bcc.is_trash as receiver_delete,inbox.sender_delete,inbox.is_replied,inbox.draft_status,inbox.delivered_date,inbox.full_message,bcc.is_read as is_read FROM `inbox` join blind_carbon_copy bcc on inbox.id = bcc.inbox_id WHERE bcc.is_trash = 1 and bcc.bcc_receiver_id= $userid";
                $result_bcc = $this->conn->query($sql_bcc);
                $rows_bcc = $result_bcc->fetch_all(MYSQLI_ASSOC);
            } else {
                $rows_bcc = [];
            }
            $data_duplicacy = array_merge($rows_sent,$rows_to, $rows_cc, $rows_bcc);
            $data = array_unique($data_duplicacy, SORT_REGULAR);
        }


        $sql = "SELECT id,email from users";
        $res = $this->conn->query($sql);
        $user_records = $res->fetch_all(MYSQLI_ASSOC);
        $hashed_user_records = [];
        foreach ($user_records as $key => $value) {
            $hashed_user_records[$value['id']] = $value['email'];
        }
        return [$data, $hashed_user_records];
    }

}
