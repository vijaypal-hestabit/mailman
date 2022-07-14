<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'connection.php';
include 'mail.php';
class ResetPasswordController{

    public function __construct(){
        $this->db = new dbConnection();
    }

    public function reset_link($email)
    {
        $resetcode = mysqli_real_escape_string($this->db->conn,md5(time()));

        $sql = "UPDATE users set reset_link='{$resetcode}' where email='$email' or user_name = '$email'";
        $res=$this->db->conn->query($sql);
        $resetlink="../../reset_password.php?reset_code=".$resetcode."&unique_id=".base64_encode($email);
        return $resetlink;
    }

    public function send_reset_link($link,$email) //send link to backup email
    {
        $sql = "SELECT backup_mail,first_name as name from users where email='$email' or user_name = '$email'";
        $res=$this->db->conn->query($sql)->fetch_assoc();

        if($res){
            $email = $res['backup_mail'];
            $subject = "Reset Password Link";
            $msg="
            <div style='width: 100%'>
                <h1>Reset Link:</h1>
                <br>
                <a href='".$link."' style=''width: 100%;background-color: blue'>Reset Password</a> 
            </div>
            ";
            // send email function to be called here
            send_mail($msg, $subject, $email, $res['name']);
            echo  "<h2>Link generated successfully. Please check your registered backup email address. Check here for <a href='https://mail.google.com/'>check gmail.</a></h2>";

        }else{
            echo "<h2>Link generating failed. Please check your registered email address or user ID. Check here for <a href='../forgot.php'>forgot password.</a></h2>";
        }
    }
    public function verify_reset_hash($code,$email)
    {
        $sql = "SELECT reset_link as reset_code from users where email='$email' or user_name = '$email'";
        $res=$this->db->conn->query($sql)->fetch_assoc();
        if($res['reset_code'] == $code){
            return true;
        }else{
            return false;
        }
    }
    function change_password($email,$password){
        // $password=$this->db->conn->real_escape_string($password);
        $sql = "UPDATE users set password='$password' where email='$email' or user_name = '$email'";
        $res = $this->db->conn->query($sql);
        if($res){
            $sql = "UPDATE users set reset_link='' where email='$email' or user_name = '$email'";
            $res = $this->db->conn->query($sql);
            echo json_encode([
                'result'=>true
            ]);
        }else{
            echo json_encode([
                'result'=>false,
                'message'=>'Please try again'
            ]);
        }
    }
    function change_password_dash($email,$password){
        // $password=$this->db->conn->real_escape_string($password);
        $sql = "UPDATE users set password='$password' where email='$email' or user_name = '$email'";
        $res = $this->db->conn->query($sql);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}