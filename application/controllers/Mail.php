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

function random_str(
    $length,
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

class Mail extends REST_Controller
{

    public function send_post()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array("from_name" => $post["from_name"], "reply_to" => $post["reply_to"], "reply_to_name" => $post["reply_to_name"], "to" => $post["to"], "subject" => $post["subject"], "message" => $post["message"]);
        $r = $this->email_model->send($data);
        $this->response($r);
    }

    public function forgot_post()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array("from_name" => $post["from_name"], "reply_to" => $post["reply_to"], "reply_to_name" => $post["reply_to_name"], "to" => $post["to"], "subject" => $post["subject"], "message" => $post["message"]);
        $r = $this->forgot_model->send($data);
        $this->response($r);
    }

}
