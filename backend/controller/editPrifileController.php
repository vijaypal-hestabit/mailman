<?php
include_once '../connection.php';

class editProfileController{

    public function __construct(){
        $this->db = new dbConnection();
        $this->conn = $this->db->conn;
    }

    public function edit_profile($first_name,$last_name,$profile_pic,$backup_mail,$user_id){

        $register_query = "UPDATE users set ";

        if ($first_name != null) {
            $register_query .= "first_name = '$first_name',";
        }

        if ($last_name != null) {
            $register_query .= "last_name = '$last_name',";
        }else{
            $register_query .= "last_name = '',";
        }

        if ($profile_pic) {
            $register_query .= "profile_pic = '$profile_pic',";
            session_start();
            $_SESSION['profile_pic'] = $profile_pic;
        }
        
        if ($backup_mail) {
            $register_query .= "backup_mail = '$backup_mail',";
        }else{
            $register_query .= "backup_mail = '',";
        }

        $new_register_query = rtrim($register_query, ",");
        
        $new_register_query .= " WHERE user_name = '$user_id'";
        $result = $this->conn->query($new_register_query);
        return $result;
    }
}


?>