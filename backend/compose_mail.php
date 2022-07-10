<?php
error_reporting(E_ALL);
ini_set("diplay_errors", 1);

use validator\validator;

include "validator.php";
include "process.php";
include "controller/composeEmailController.php";

class compose extends CommonFunctions
{
    function __construct()
    {
        $data = json_decode(file_get_contents("php://input"), TRUE);
        if (isset($data['to_mail'])) {
            $to_email = $data['to_mail'];
            $user_id = $data['user_id'];
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->make_draft($to_email, $user_id, $inbox_id);
        } else if (isset($data['cc_mail'])) {
            $cc_mail = $data['cc_mail'];
            $user_id = $data['user_id'];
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->save_cc_mail($cc_mail, $user_id, $inbox_id);
        } else if (isset($data['bcc_mail'])) {
            $bcc_mail = $data['bcc_mail'];
            $user_id = $data['user_id'];
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->save_bcc_mail($bcc_mail, $user_id, $inbox_id);
        } else if (isset($data['subject'])) {
            $subject = $data['subject'];
            $user_id = $data['user_id'];
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->save_subject($subject, $user_id, $inbox_id);
        } else if (isset($data['message'])) {
            $message = $data['message'];
            $user_id = $data['user_id'];
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->save_message($message, $user_id, $inbox_id);
        } else if (isset($_FILES['files'])) {
            $file = $_FILES['files'];
            $user_id = $_POST['user_id'];
            if (isset($_POST['inbox_id']) && ($_POST['inbox_id'] !== 'null')) {
                $inbox_id = $_POST['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $this->save_attachment($file, $user_id, $inbox_id);
        } else if (isset($data['send_mail'])) {
            if (isset($data['inbox_id'])) {
                $inbox_id = $data['inbox_id'];
            } else {
                $inbox_id = "";
            }
            $message = $data['to_message'];
            $to_email = $data['main_mail'];
            $this->send_email($inbox_id, $message, $to_email);
        } else if (isset($data['fetch_draft'])) {
            $id = $data['id'];
            $this->fetch_draft($id);
        }
    }
    function make_draft($to_email, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->make_draft($to_email, $user_id, $inbox_id);
    }
    function save_cc_mail($cc_mail, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->save_cc_mail($cc_mail, $user_id, $inbox_id);
    }
    function save_bcc_mail($bcc_mail, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->save_bcc_mail($bcc_mail, $user_id, $inbox_id);
    }
    function save_subject($subject, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->save_subject($subject, $user_id, $inbox_id);
    }
    function save_message($message, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->save_message($message, $user_id, $inbox_id);
    }
    function save_attachment($file, $user_id, $inbox_id)
    {
        $ob = new ComposeEmailController();
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/hbmail/images/mail_attachments/';
        $path = $this->saveFiles($dir, $file);
        $ob->save_attachment($path, $user_id, $inbox_id);
    }
    function send_email($inbox_id, $message, $to_email)
    {
        $inbox_id_check = validator::is_require($inbox_id);
        if (!$inbox_id_check['value']) {
            echo json_encode([
                'response' => false,
                'message' => 'Pls fill the sender mailer address',
                'variable' => 'email_error'
            ]);
            die;
        } else if (!validator::is_require($to_email)['value']) {
            echo json_encode([
                'response' => false,
                'message' => 'Pls fill the sender mailer address'
            ]);
            die;
        } else if (!validator::is_require($message)['value']) {
            echo json_encode([
                'response' => false,
                'message' => 'Pls provide valid mail msg'
            ]);
            die;
        } else {
            $ob = new ComposeEmailController();
            $ob->send_email($inbox_id);
        }
    }
    function fetch_draft($inbox_id)
    {
        $ob = new ComposeEmailController();
        $ob->fetch_draft_details($inbox_id);
    }
}
new compose();
