<?php

error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/resetPasswordController.php";

class reset
{
    function __construct()
    {
        if (isset($_POST['forgot_user_name'])) {
            $email = ($_POST['forgot_user_name']);
            //die;

            

            // if (!validator::is_email($email)['value']) {
            //     $error = (validator::is_email($email)['message']);
            //     $path = $_SERVER['HTTP_ORIGIN'] . '/hbmail/forgot.php?error=' . $error;
            //     header('location:' . $path);
            // }
            $this->generate_reset_link($email);
        }
        if (isset($_POST['verify_reset_code'])) {
            $code = $_POST['reset_code'];
            $unique_id = $_POST['unique_id'];
            $this->verify_reset_code($code, $unique_id);
        }
        if (isset($_POST['update_password'])) {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $match_password_status = validator::is_valid_pass($password, $confirm_password, 6);
            if ($match_password_status['message'] != '') {
                echo json_encode([
                    'result' => false,
                    'message' => $match_password_status['message']
                ]);
                die;
            } else {
                $unique_id = $_POST['unique_id'];
                $this->update_password($password, $unique_id);
            }
        }
    }
    function generate_reset_link($email)
    {
        $resetPasswordController = new resetPasswordController();
        $link = $resetPasswordController->reset_link($email);
        $resetPasswordController->send_reset_link($link, $email);
    }
    function verify_reset_code($code, $email)
    {
        $resetPasswordController = new resetPasswordController();
        $email = base64_decode($email);
        $verify_status = $resetPasswordController->verify_reset_hash($code, $email);
        echo json_encode([
            'result' => $verify_status
        ]);
    }
    function update_password($password, $unique_id)
    {
        $resetPasswordController = new resetPasswordController();
        $email = base64_decode($unique_id);
        $verify_status = $resetPasswordController->change_password($email, $password);
    }
}

new reset();
