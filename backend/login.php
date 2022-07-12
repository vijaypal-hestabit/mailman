<?php

error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/loginController.php";

class login
{
    function __construct()
    {
        
        echo $username = ($_POST['user_name']);
        die;
        $password = ($_POST['password']);
        // $email = null;

        $this->login_validate($username, $password,);
    }

    public function login_validate($username,$password)
    {
        $check_user = new LoginController();

        // user name validation
        if (!validator::is_require($username)['value']) {
            $usernamer = ['username_error' => "Please enter user name"];
        } else {
            $usernamer = 1;
        }

        if (!validator::is_require($password)['value']) {
            $passwordr = ['password_error' => "Please enter password"];
        } else {
            $passwordr = 1;
        }


        
        // login on database
        // echo $passwordr;
        // die;   
        if($usernamer == 1 && $passwordr == 1){
           $res = $check_user->login($username,$password);
            echo json_encode(['login'=>$res]);
        }else{
            $response = ['username' => $usernamer,'password' => $passwordr];
            echo json_encode($response);
        }
    }
}
$ob = new login();
