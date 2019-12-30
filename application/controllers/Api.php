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

function slugify($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

class Api extends REST_Controller
{

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

    public function subm_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        if ($post['type'] === "status") {
            $r = $this->subm_model->update($post['status'], $id);
        } else {
            $data = array('subm_title' => $post['title'], 'subm_description' => $post['description'], 'subm_type' => $post['type']['value'], 'subm_language' => $post['language']['value'], 'subm_level' => $post['level']['value'], 'subm_theme' => $post['theme']['value'], 'subm_orientation' => $post['orientation']['value'], 'subm_status' => $post['status']['value'], 'subm_info' => $post['info']);
            $r = $this->subm_model->insert($data, $id);
        }
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

    //Panelists

    public function part_get()
    {
        $id = $this->uri->segment(3);
        $r = $this->part_model->read($id);
        $this->response($r);
    }

    public function part_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        if ($post['type'] === "status") {
            $r = $this->part_model->update($post['status'], $id);
        } else {
            $data = array('part_fname' => $post['fname'], 'part_lname' => $post['lname'], 'part_pronouns' => $post['pronouns'], 'part_email' => $post['email'], 'part_photo' => $post['photo'], 'part_affiliation' => $post['affiliation'], 'part_bio' => $post['bio'], 'part_city' => $post['city'], 'part_country' => $post['country'], 'part_gender' => $post['gender'], 'part_minority' => $post['minority'], 'user_id' => $post['user']['value']);
            $r = $this->part_model->insert($data, $id);
        }
        $this->response($r);
    }

    public function associate_post()
    {
        $id = $this->uri->segment(3);
        $post = json_decode(file_get_contents('php://input'), true);
        if ($post['type'] === "comm") {
            $r = $this->subm_model->associate($post["comm_id"], $id);
        }
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
