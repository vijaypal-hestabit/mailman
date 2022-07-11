<?php
include_once '../connection.php';

class LoginController
{

    public function __construct()
    {
        $this->db = new dbConnection();
    }

    public function login($user_name, $password)
    {
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
            if ($data['profile_pic'] == null) {
                $_SESSION['profile_pic'] = 'avatar.png';
            } else {
                $_SESSION['profile_pic'] = $data['profile_pic'];
            }
            $_SESSION['email'] = $data['email'];
        } else {
            $responce = false;
        }
        return $responce;
    }
}
