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

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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

class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Login_model');
        $this->load->model('User_model');
        $this->load->model('Room_model');
    }

    //Login

    public function login_post()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $email = htmlspecialchars(clean_input($post['email']));
        $password = htmlspecialchars(clean_input($post['password']));
        $data = array('email' => $email,
            'password' => $password);
        $r = $this->login_model->login($data);
        $this->response($r);
    }

    //User

    public function user_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->user_model->read($id);
        $this->response($r);
    }

    public function user_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        if ($this->uri->segment(4) == "password") {
            $password = htmlspecialchars(clean_input($post['password']));
            $password_email = htmlspecialchars(clean_input($post['password_email']));
            $data = array('password' => $password,
                'password_email' => $password_email);
            $r = $this->user_model->modifyPW($data, $id);
            $this->response($r);
        } else {
            $name = htmlspecialchars(clean_input($post['name']));
            $email = htmlspecialchars(clean_input($post['email']));
            $role = $post['role']['value'];
            $data = array('name' => $name,
                'email' => $email,
                'role' => $role);
            $r = $this->user_model->insert($data, $id);
            $this->response($r);
        }
    }

    public function user_delete()
    {
        $id = $this->uri->segment(3);
        $r = $this->user_model->remove($id);
        $this->response($r);
    }

    //Submissions

    public function subm_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->subm_model->read($id);
        $this->response($r);
    }

    //Ratings

    public function rating_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->rating_model->read($id);
        $this->response($r);
    }

    public function rating_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array('user_id' => $post['id'],
            'rating' => $post['rating']);
        $r = $this->rating_model->rate($data, $id);
        $this->response($r);
    }

    //Comments

    public function comment_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->comment_model->read($id);
        $this->response($r);
    }

    public function comment_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        $data = array('comment' => $post['comment']);
        $r = $this->comment_model->insert($data, $id);
        $this->response($r);
    }

    public function comment_delete()
    {
        $id = $this->uri->segment(3);
        $r = $this->comment_model->remove($id);
        $this->response($r);
    }

    //Room

    public function room_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->room_model->read($id);
        $this->response($r);
    }

    //Admin

    public function admin_get()
    {
        $r = $this->admin_model->read();
        $this->response($r);
    }

}
