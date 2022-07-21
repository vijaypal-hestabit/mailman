<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'connection.php';
include 'mail.php';
class ComposeEmailController
{
    public function __construct()
    {
        $obj = new dbConnection();
        $this->conn = $obj->conn;
    }

    // set value for draft
    public function make_draft($to_email, $user_id, $inbox_id)
    {
        $to_email = $this->conn->real_escape_string($to_email);
        $sender_id = $this->conn->real_escape_string($user_id);
        $sql = "SELECT id FROM users where email='{$to_email}'";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            $data = $res->fetch_assoc();
            $receiver_id = $data['id'];
            if ($inbox_id == '') {
                $sql = "INSERT INTO `inbox`(`receiver_id`, `short_subject_msg`, `sender_id`,  `draft_status`, `full_message`) VALUES ($receiver_id,'no subject',$sender_id,1,'')";
                $result = $this->conn->query($sql);
                $last_id = $this->conn->insert_id;
                echo json_encode(['inbox_id' => $last_id]);
            } else {
                $sql = "UPDATE inbox set receiver_id = $receiver_id where id= $inbox_id";
                $result = $this->conn->query($sql);
                echo json_encode($result);
            }
        } else {
            echo json_encode([
                'response' => false,
                'message' => 'Email Contact does not exist'
            ]);
        }
    }

    // set value for cc mail
    public function save_cc_mail($cc_mail, $user_id, $inbox_id)
    {
        $cc_mail = $this->conn->real_escape_string($cc_mail);
        $sender_id = $this->conn->real_escape_string($user_id);
        $sql = "SELECT id FROM users where email='{$cc_mail}'";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            $data = $res->fetch_assoc();
            $receiver_id = $data['id'];
            if ($inbox_id == '') {
                $sql = "INSERT INTO `inbox`(`sender_id`,  `draft_status`, `full_message`) VALUES ($sender_id,1,'')";
                $result = $this->conn->query($sql);
                $last_id = $this->conn->insert_id;
                $query = "INSERT INTO `carbon_copy`(`cc_email`, `inbox_id`) VALUES ('{$cc_mail}',$last_id)";
                $result = $this->conn->query($query);

                echo json_encode(['inbox_id' => $last_id]);
            } else {
                $sql = "SELECT cc_id from carbon_copy where inbox_id = $inbox_id";
                $res = $this->conn->query($sql);
                if ($res->num_rows > 0) {
                    $query = "UPDATE carbon_copy set cc_email='{$cc_mail}' where inbox_id=$inbox_id";
                    $result = $this->conn->query($query);
                } else {
                    $query = "INSERT INTO `carbon_copy`(`cc_email`, `inbox_id`,`cc_receiver_id`) VALUES ('{$cc_mail}',$inbox_id,$receiver_id)";
                    $result = $this->conn->query($query);
                }
                echo json_encode($result);
            }
        } else {
            echo json_encode([
                'response' => false,
                'message' => 'Email Contact does not exist',
                'variable' => 'cc_email_error'
            ]);
        }
    }

    // set value for bcc mail
    public function save_bcc_mail($bcc_mail, $user_id, $inbox_id)
    {

        $bcc_mail = $this->conn->real_escape_string($bcc_mail);
        $sender_id = $this->conn->real_escape_string($user_id);
        $sql = "SELECT id FROM users where email='{$bcc_mail}'";
        $res = $this->conn->query($sql);
        if ($res->num_rows > 0) {
            $data = $res->fetch_assoc();
            $receiver_id = $data['id'];
            if ($inbox_id == '') {
                $sql = "INSERT INTO `inbox`(`receiver_id`, `short_subject_msg`, `sender_id`,  `draft_status`, `full_message`) VALUES ('','',$sender_id,1,'')";
                $result = $this->conn->query($sql);
                $last_id = $this->conn->insert_id;
                $query = "INSERT INTO `blind_carbon_copy`(`bcc_email`, `inbox_id`,`bcc_receiver_id`) VALUES ('{$bcc_mail}',$last_id,{$data['id']})";
                $result = $this->conn->query($query);

                echo json_encode(['inbox_id' => $last_id]);
            } else {
                $sql = "SELECT bcc_id from blind_carbon_copy where inbox_id={$data['id']}";
                $res = $this->conn->query($sql);
                if ($res->num_rows > 0) {
                    $query = "UPDATE blind_carbon_copy set bcc_email='{$bcc_mail}' where inbox_id={$data['id']}";
                    $result = $this->conn->query($query);
                } else {
                    $query =  "INSERT INTO `blind_carbon_copy`(`bcc_email`, `inbox_id`,`bcc_receiver_id`) VALUES ('{$bcc_mail}',$inbox_id,{$data['id']})";
                    $result = $this->conn->query($query);
                }
                echo json_encode($result);
            }
        } else {
            echo json_encode([
                'response' => false,
                'message' => 'Email Contact does not exist'
            ]);
        }
    }
    
    // set value for subject mail
    public function save_subject($subject, $user_id, $inbox_id)
    {
        $subject =  $this->conn->real_escape_string($subject);
        if ($subject == '') {
            $subject = 'no subject';
        }
        $sender_id = $this->conn->real_escape_string($user_id);
        if ($inbox_id == '') {
            $sql = "INSERT INTO `inbox`(`short_subject_msg`, `sender_id`,  `draft_status`, `full_message`) VALUES ('$subject',$sender_id,1,'')";
            $result = $this->conn->query($sql);
            $last_id = $this->conn->insert_id;

            echo json_encode(['inbox_id' => $last_id]);
        } else {
            $query = "UPDATE inbox set short_subject_msg='$subject' where id=$inbox_id";
            $result = $this->conn->query($query);
            echo json_encode($result);
        }
    }

    // set value for messae mail
    public function save_message($message, $user_id, $inbox_id)
    {
        $message =  $this->conn->real_escape_string($message);
        $sender_id = $this->conn->real_escape_string($user_id);
        if ($inbox_id == '') {
            $sql = "INSERT INTO `inbox`(`sender_id`,  `draft_status`, `full_message`) VALUES ($sender_id,1,'$message')";
            $result = $this->conn->query($sql);
            $last_id = $this->conn->insert_id;

            echo json_encode(['inbox_id' => $last_id]);
        } else {
            $query = "UPDATE inbox set full_message='$message' where id=$inbox_id";
            $result = $this->conn->query($query);
            echo json_encode($result);
        }
    }

    // set value for attachment mail
    public function save_attachment($path, $user_id, $inbox_id)
    {
        $sender_id = $this->conn->real_escape_string($user_id);
        if ($inbox_id == '') {
            $sql = "INSERT INTO `inbox`(`sender_id`,  `draft_status`, `full_message`) VALUES ($sender_id,1,'')";
            $result = $this->conn->query($sql);
            $last_id = $this->conn->insert_id;
            $query = "INSERT INTO `attachments`(`path`, `inbox_id`) VALUES  ('$path',$last_id)";
            $result = $this->conn->query($query);
            $sql = "SELECT * from attachments where inbox_id=$last_id";
            $res = $this->conn->query($sql);
            $attachments = $res->fetch_all(MYSQLI_ASSOC);

            echo json_encode([
                'inbox_id' => $last_id,
                'attahcments'=>$attachments

            ]);
        } else {
            $query = "INSERT INTO `attachments`(`path`, `inbox_id`) VALUES  ('$path',$inbox_id)";
            $result = $this->conn->query($query);
            $last_id = $this->conn->insert_id;
            $sql = "SELECT * from attachments where inbox_id=$inbox_id";
            $res = $this->conn->query($sql);
            $attachments = $res->fetch_all(MYSQLI_ASSOC);
            echo json_encode(['attahcments'=>$attachments]);
        }
    }

    // set value draft to inbox
    function send_email($inbox_id)
    {
        $query = "UPDATE inbox set draft_status=0 where id=$inbox_id";
        $result = $this->conn->query($query);
        echo json_encode($result);
    }
    function fetch_draft_details($inbox_id)
    {
        $sql = "SELECT * from inbox left join carbon_copy on (carbon_copy.inbox_id=inbox.id) left join blind_carbon_copy on (blind_carbon_copy.inbox_id=inbox.id) where inbox.id=$inbox_id";
        $res = $this->conn->query($sql);
        $data = $res->fetch_all(MYSQLI_ASSOC);
        $user_records = $res->fetch_all(MYSQLI_ASSOC);
        $hashed_user_records = [];
        foreach ($user_records as $key => $value) {
            $hashed_user_records[$value['id']] = $value['email'];
        }
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
