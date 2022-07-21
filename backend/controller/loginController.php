<?php
include '../connection.php';

class LoginController
{

    public function __construct()
    {
        $this->db = new dbConnection();
    }

    public function login($user_name, $password)
    {
        $password = md5($password);
        $register_query = "SELECT first_name,id,user_name,email,profile_pic,password FROM users WHERE (user_name = '$user_name' or email = '$user_name') and password ='$password'";
        $query = $this->db->conn->query($register_query);
        $result = mysqli_num_rows($query);
        $data = $query->fetch_assoc();
        if ($result > 0) {
            $responce = true;
            session_start();
            $_SESSION['user_name'] = $data['first_name'];
            $_SESSION['user_id'] = $user_name;
            $_SESSION['id'] = $data['id'];
            $_SESSION['profile_pic'] = $data['profile_pic'];
            $_SESSION['email'] = $data['email'];
        } else {
            $responce = false;
        }
        return $responce;
    }
}
