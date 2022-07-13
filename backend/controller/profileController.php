<?php
session_start();
// include '../connection.php';
class profileController{

    public function __construct(){
        $this->db = new dbConnection();
    }

    public function fetchProfile(){
        $user = $_SESSION['user_id'];
        $user_details = "SELECT first_name,last_name,user_name,email,backup_mail,profile_pic,password FROM users WHERE  email = '$user' or user_name = '$user'";
        $result = $this->db->conn->query($user_details);
        $rows= $result->fetch_assoc();
        if($rows > 0){
            return $rows;
        }else{
            return false;
        }
    }
    public function isUserExist($user_name){
        $email_user = "SELECT user_name,email FROM users WHERE  user_name = '$user_name'";
        $result = $this->db->conn->query($email_user);
        if($result->num_rows > 0){
            return true;
        }else{
            return false;
        }
    }
}


?>