<?php

error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/signupController.php";

class register extends CommonFunctions
{
    function __construct()
    {
        $profile_img = $_FILES['profile_pic'];
        // var_dump($profile_img);
        // die;
        // $dir = $_SERVER['HTTP_ORIGIN'].'/hbmail/images/';
        $dir = '../images/profile_pic/';
        $this->saveFiles($dir, $profile_img);

        $fname = ($_POST['f_name']);
        $lname = ($_POST['l_name']);
        $username = ($_POST['user_name']);
        $email = ($_POST['email']);
        $recoveryemail = ($_POST['recovery_email']);
        $password = ($_POST['password']);
        $cpassword = ($_POST['cpassword']);


        $this->validate($fname, $lname, $email, $username, $recoveryemail, $password, $cpassword, $profile_img);
    }

    public function validate($fname, $lname, $email, $username, $recoveryemail, $password, $cpassword, $profile_img)
    {
        // first name validation
        if (!validator::is_require($fname)['value']) {
            $fnamer = ['fname_error' => "Please enter first name"];
        } else if (!validator::is_alphanumaric($fname)['value']) {
            $fnamer = ['fname_error' => "Please provide first name in alphanumeric characters"];
        } else {
            $fnamer = 1;
        }

        // last namve validation
        if (validator::is_require($lname)['value']) {
            if (!validator::is_alphanumaric($lname)['value']) {
                $lnamer = ['lname_error' => "Please provide last name in alphanumeric characters"];
            } else {
                $lnamer = 1;
            }
        } else {
            $lnamer = 1;
        }

        $check_user_email = new RegisterController();

        // user name validation
        if (!validator::is_require($username)['value']) {
            $usernamer = ['username_error' => "Please enter user name"];
        } else if (!validator::is_alphanumaric($username)['value']) {
            $usernamer = ['username_error' => "Please provide user name in alphanumeric characters"];
        } else if (validator::is_spacialChar($username)['value']) {
            $usernamer = ['username_error' => "Special characters not allowed."];
        } else if ($check_user_email->isUserExist($username)) {
            $usernamer = ['username_error' => "User name already exist."];
        } else {
            $usernamer = 1;
        }

        // email validation
        // user name validation
        if (!validator::is_require($email)['value']) {
            $emailr = ['email_error' => "Please create email id "];
        } else if (!validator::is_alphanumaric($email)['value']) {
            $emailr = ['email_error' => "Please provide email in alphanumeric characters"];
        } else if (validator::is_spacialChar($email)['value']) {
            $emailr = ['email_error' => "Special characters not allowed."];
        } else if ($check_user_email->isUserExist($email)) {
            $emailr = ['email_error' => "Email id already exist."];
        } else {
            $emailr = 1;
        }

        // recovery email validation
        if (!validator::is_require($recoveryemail)['value']) {
            $recoveryemailr = ['recoveryemail_error' => "Please enter recovery email"];
        } else if (!validator::is_email($recoveryemail)['value']) {
            $recoveryemailr = ['recoveryemail_error' => "Please provide correct email"];
        } else {
            $recoveryemailr = 1;
        }

        // password validation
        if (validator::is_valid_pass($password, $cpassword, 6)) {
            $passwordr = validator::is_valid_pass($password, $cpassword, 6);
        }
        
        // profile pic
        $profile_img;
        if ($profile_img) {
            $profile_img_name = strtotime(date("Y-m-d H:i:s")).'_'.$profile_img['name'].PHP_EOL;
        }else{
            $profile_img_name = '';
        }
        if (!validator::is_require($profile_img)['value']) {
            $profile_msg_error['response']=1;
        }else{
            $profile_msg_error=validator::is_fileAuth($profile_img,$size=2097152,$allowedExtensions=['jpg','png']);
        }
        // insert data on database
        if ($fnamer == 1 && $lnamer == 1 && $usernamer == 1 && $emailr == 1 && $recoveryemailr == 1 &&  $passwordr['value'] == 1 && $profile_msg_error['response'] == 1) {
            $check_user_email->registration($fname, $lname, $username, $email . '@mailman.com', $profile_img_name, $password, $recoveryemail) ;
            echo json_encode(['signup' => true]);
        } else {
            $response = ['fname' => $fnamer, 'lname' => $lnamer, 'username' => $usernamer, 'email' => $emailr, 'recoveryemail' => $recoveryemailr, 'password' => $passwordr, 'profile_img' => $profile_msg_error];
            echo json_encode($response);
        }
    }
}
$ob = new register();
