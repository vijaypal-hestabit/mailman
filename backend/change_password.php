<?php


use validator\validator;

include "validator.php";
include "process.php";
include "controller/resetPasswordController.php";
include "controller/profileController.php";

class change_password
{
    function __construct()
    {
        $oldpassword = $_POST['old_password'];
        
        $profile_data = new profileController();
        $data = $profile_data->fetchProfile();

        $fetchPass = $data['password'];

        $password = $_POST['new_password'];
        $cpassword = $_POST['c_password'];

        

        $this->validate($password, $cpassword,$fetchPass,$oldpassword);
    }

    public function validate($password, $cpassword,$fetchPass,$oldpassword)
    {

        // password validation
        if ($oldpassword) {
            $oldpassword = md5($oldpassword);
            if ($fetchPass == $oldpassword) {

              
                if (validator::is_valid_pass($password, $cpassword, 6)) {
                    $passwordr = validator::is_valid_pass($password, $cpassword, 6);
                }

                if($passwordr['status']){

                    $email = $_SESSION['email'];
    
                    $resetPasswordController = new resetPasswordController();
                    $verify_status = $resetPasswordController->change_password_dash($email, $password);
    
                    $passwordr = array('value' => 1, 'message' => "Password change successfully.",'status'=>$verify_status);
                }
                else{
                    $passwordr = $passwordr;
                }

            }else{
                $passwordr = array('value' => 0, 'message' => "Please enter correct old password");
            }
        }else{
            $passwordr = array('value' => 0, 'message' => "Please enter old password");
        }
        echo json_encode($passwordr);
    }
}
$ob = new change_password();
