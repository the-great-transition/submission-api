<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Email_model extends CI_Model
{

    public function send($data)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user['role'] <= 1) {
            $pw = $this->db->get_where('conf', array('conf_label' => 'email_pw'))->result_array();
            $pw = $pw[0]['conf_value'];
            $config['smtp_user'] = $data['user'];
            $config['smtp_pass'] = $pw;
            $this->email->initialize($config);
            $this->email->from($data['from'], $data['from_name']);
            //
            if ($data["reply_to"] && $data["reply_to_name"]) {
                $this->email->reply_to($data["reply_to"], $data["reply_to_name"]);
            }
            $this->email->to($data['to']);
            $this->email->subject($data['subject']);
            $this->email->message($data['message']);
            return $this->email->send();
        }
    }

}
