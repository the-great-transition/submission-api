<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Forgot_model extends CI_Model
{

    public function send($data)
    {
        $touser = $this->db->get_where('user', array('user_email' => $data["to"]))->result_array();
        if (count($touser) === 1) {
            $pw = $this->db->get_where('conf', array('conf_label' => 'email_pw'))->result_array();
            $user = $this->db->get_where('conf', array('conf_label' => 'email_user'))->result_array();
            $from = $this->db->get_where('conf', array('conf_label' => 'email_from'))->result_array();
            $config['smtp_user'] = $user[0]['conf_value'];
            $config['smtp_pass'] = $pw[0]['conf_value'];
            $this->email->initialize($config);
            $this->email->from($from[0]['conf_value'], $data["from_name"]);
            if ($data["reply_to"] && $data["reply_to_name"]) {
                $this->email->reply_to($data["reply_to"], $data["reply_to_name"]);
            }
            $this->email->to($data['to']);
            $this->email->subject($data['subject']);
            $newpassword = random_str(8);
            $hashedpassword = password_hash($newpassword, PASSWORD_DEFAULT);
            if ($this->db->update("user", array('user_password' => $hashedpassword), array('user_id' => $touser[0]["user_id"]))) {
                $message = str_replace("#/FROM_NAME/#", $touser[0]["user_name"], $data['message']);
                $message = str_replace("#/PASSWORD/#", $newpassword, $message);
                $this->email->message($message);
                return $this->email->send();
            } else {
                show_error('err_newpassword', 500);
            }
        } else if (count($touser) > 1) {
            return show_error('duplicate_email', 500);
        } else if (count($touser) === 0) {
            return show_error('no_user', 404);
        }
    }

}
