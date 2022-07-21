<?php

namespace validator;

class validator
{
    public static function is_require($value)
    {
        if ($value != null) {
            return array('value' => true, 'message' => 'Value exist.');
        } else {
            return array('value' => false, 'message' => 'Empty value');
        }
    }

    public static function is_email($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return array('value' => false, 'message' => 'invalid email');
        } else {
            return array('value' => true, 'message' => 'email varified');
        }
    }

    public static function is_mobile($value)
    {
        if (preg_match('/^[0-9]{10}+$/', $value)) {
            return array('value' => true, 'message' => 'mobile validated');
        } else {
            return array('value' => false, 'message' => 'Please enter valid mobile number');
        }
    }

    public static function is_number($number)
    {
        if (is_numeric($number)) {
            return array('value' => true, 'message' => 'Number validated');
        } else {
            return array('value' => false, 'message' => 'Not a number validated');
        }
    }

    public static function is_validStrLen($str, $min, $max)
    {
        $len = strlen($str);
        if ($len < $min) {
            return "Field Name is too short, minimum is $min characters ($max max).";
        } elseif ($len > $max) {
            return "Field Name is too long, maximum is $max characters ($min min).";
        }
        return TRUE;
    }

    public static function is_valid_pass($password, $cpassword, $pass_length)
    {
        $passwordErr = "";
        $status = "";
        if (!empty($password) && ($password == $cpassword)) {
            $password = $password;
            $cpassword = $cpassword;

            if (strlen($password) < $pass_length) {
                $passwordErr = "Your Password Must Contain At Least $pass_length Characters!";
            } elseif (!preg_match("#[0-9]+#", $password)) {
                $passwordErr = "Your Password Must contain a number!";
            } elseif (!preg_match("#[A-Z]+#", $password)) {
                $passwordErr = "Your Password Must contain one upper case character!";
            } elseif (!preg_match("#[a-z]+#", $password)) {
                $passwordErr = "Your Password Must contain a lower case character!";
            } elseif (!preg_match("/\W/", $password)) {
                $passwordErr = "Your Password Must contain a special character!";
            }else{
                $status = true;
            }
        } elseif (!($password == $cpassword)) {
            $passwordErr = "Please check, you've entered wrong confirm password!!";
        } else {
            $passwordErr = "Please enter new password";
        }
        return array('value' => 1, 'message' => $passwordErr,'status' => $status);
    }

    // Validate alphanumeric
    public static function is_alphanumaric($value)
    {
        if (preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $value)) {
            return array('value' => true, 'message' => 'Alphanumeric validated');
        } else {
            return array('value' => false, 'message' => 'No special characters allowed');
        }
    }
    public static function is_spacialChar($value)
    {
        if (preg_match("/\W/", $value)) {
            return array('value' => true, 'message' => "Special character exist.");
        } else {
            return array('value' => false, 'message' => "Special character not exist.");
        }
    }

    // file validation
    public static function is_fileAuth($file, $size, $allowedExtensions)
    {
        // var_dump($file);
        // die;
        // $allowedExtensions=['jpg','png'];
        $file_size = $file['size'];
        $file_type =  pathinfo($file['name'])['extension'];
        if ($file_size > $size) { // Check file size
            $message = "Sorry, your file is too large.File should be less than ".$size/(1024*1024)."MB";
            $response = 0;
        } else if (!in_array($file_type,$allowedExtensions)) { // Allow certain file formats
            $message = "Sorry, only JPG and PNG files are allowed.";
            $response = 0;
        }else{
            $message = "Image uploaded successfully.";
            $response = 1;
        }
        
        return ['response' => $response,'message' => $message];
    }
}
