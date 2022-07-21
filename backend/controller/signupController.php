<?php
include_once '../connection.php';

class RegisterController{

    public function __construct(){
        $this->db = new dbConnection();
    }

    public function registration($first_name,$last_name,$user_name,$email,$profile_pic,$password,$backup_mail){
        $date = date("Y-m-d H:i:s"); 
        $password = md5($password);
        $register_query = "INSERT INTO users(first_name,last_name,user_name,email,profile_pic,password,backup_mail,date) values('$first_name','$last_name','$user_name','$email','$profile_pic','$password','$backup_mail','$date')";
        $result = $this->db->conn->query($register_query);
        return $result;
    }
    public function isEmailExist($email){
        $email = $email.'@mailman.com';
        $email_user = "SELECT user_name,email FROM users WHERE  email = '$email'";
        $result = $this->db->conn->query($email_user);
        if($result->num_rows > 0){
            return true;
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