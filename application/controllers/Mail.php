<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
use \Firebase\JWT\JWT;

function tryKey($query, $headers)
{
    $r = $query->result_array();
    $key = $r[0]['conf_value'];
    $token = $headers['x-auth-token'];
    try {
        $decoded = JWT::decode($token, $key, array('HS256'));
        return $decoded;
    } catch (\Firebase\JWT\ExpiredException $e) {
        show_error('err_expired', 401);
    }
}

class Mail extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_model');
    }

    public function send_post()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array("user" => $post["user"], "from" => $post["from"], "from_name" => $post["from_name"], "to" => $post["to"], "subject" => $post["subject"], "message" => $post["message"]);
        $r = $this->email_model->send($data);
        $this->response($r);
    }

}
