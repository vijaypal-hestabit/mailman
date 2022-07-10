<?php

error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/editPrifileController.php";
include "controller/profileController.php";

class editProfile extends CommonFunctions
{
    function __construct()
    {
       

        if ($_POST['show_details']) {
            $this->getDetail();
        }else{
            $profile_img = $_FILES['profile_pic'];
            // var_dump($profile_img);
            // die;
            // $dir = $_SERVER['HTTP_ORIGIN'].'/hbmail/images/';
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/hbmail/images/profile_pic/';
            $this->saveFiles($dir, $profile_img);
    
            $fname = ($_POST['f_name']);
            $lname = ($_POST['l_name']);
            $recoveryemail = ($_POST['r_mail']);
            $user_id = ($_POST['user_id']);
            $this->validate($fname, $lname, $recoveryemail, $profile_img,$user_id);
        }
    }
    public function getDetail()
    {
        
        $profile_data = new profileController();
        $data = $profile_data->fetchProfile();
        
        echo json_encode($data);
    }

    public function validate($fname, $lname,$recoveryemail, $profile_img,$user_id)
    {
        // first name validation
        if (validator::is_require($fname)['value']) {
            if (!validator::is_alphanumaric($fname)['value']) {
                $fnamer = ['fname_error' => "Please provide first name in alphanumeric characters"];
            } else {
                $fnamer = 1;
            }
        } else{
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


        // recovery email validation
        if (validator::is_require($recoveryemail)['value']) {
            if (!validator::is_email($recoveryemail)['value']) {
                $recoveryemailr = ['recoveryemail_error' => "Please provide correct email"];
            } else {
                $recoveryemailr = 1;
            }
        } else {
            $recoveryemailr = 1;
        }


        // profile pic
        $profile_img;
        $profile_img_name =null;

        if ($profile_img['name'] != null) {
            $profile_img_name = strtotime(date("Y-m-d H:i:s")).'_'.$profile_img['name'];
        }
        if (!validator::is_require($profile_img)['value']) {
            // $usernamer = ['username_error' => "Please enter user name"];
            $profile_msg_error['response']=1;
            // $profile_msg_error['message'] = ''
        }else{
            $profile_msg_error=validator::is_fileAuth($profile_img,$size=2097152,$allowedExtensions=['jpg','png']);
        }
       
        $edit_profile = new editProfileController();

        // insert data on database
        if ($fnamer == 1 && $lnamer == 1 && $recoveryemailr == 1 && $profile_msg_error['response'] == 1) {

            $edit_profile->edit_profile($fname, $lname, $profile_img_name, $recoveryemail,$user_id) ;

            echo json_encode(['edit_profile' => true]);
        } else {
            $response = ['fname' => $fnamer, 'lname' => $lnamer,'recoveryemail' => $recoveryemailr,'profile_img' => $profile_msg_error];
            echo json_encode($response);
        }
    }
}
$ob = new editProfile();
