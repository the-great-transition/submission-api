<?php
defined('BASEPATH') or exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Login_model extends CI_Model
{

    public function login($input)
    {
        $r = $this->db->get_where('conf', array('conf_label' => 'jwt_key'))->result_array();
        $key = $r[0]['conf_value'];
        $t = 'user';
        if ($r = $this->db->get_where($t, array($t . '_email' => $input['email']))->result_array()) {
            if (password_verify($input['password'], $r[0]['user_password'])) {
                $payload = array(
                    "iss" => "http://edito.lagrandetransition.net/api",
                    "exp" => time() + (60 * 60 * 24 * 3),
                    "id" => $r[0]['user_id'],
                    "name" => $r[0]['user_name'],
                    "email" => $r[0]['user_email'],
                    "role" => $r[0]['user_role'],
                    "language" => $r[0]['user_language'],

                );
                $token = JWT::encode($payload, $key);
                $this->output->set_header('x-auth-token:' . $token);
                $this->output->set_header('access-control-expose-headers: x-auth-token');
                return 'success';
            } else {
                show_error('err_password', 404);
            }
        } else {
            show_error('err_email', 404);
        }
    }

}
