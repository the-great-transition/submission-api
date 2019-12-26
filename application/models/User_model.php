<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user['role'] <= 3) {
            $t = 'user';
            if ($id === null) {
                $result = $this->db->get($t)->result_array();
                $output = array();
                foreach ($result as $r) {
                    $i = array('user_id' => $r['user_id'], 'user_name' => $r['user_name'], 'user_email' => $r['user_email'], 'user_role' => $r['user_role'], 'user_language' => $r['user_language'], 'user_arch' => $r['user_arch'], 'user_meta' => $r['user_meta']);
                    array_push($output, $i);
                }
                if (count($output) == 0) {
                    show_error('err_empty', 404);
                } else {
                    return $output;
                }
            }
            $result = $this->db->get_where($t, array($t . '_id' => $id))->result_array();
            if (count($result) == 0) {
                show_error('err_empty', 404);
            } else {
                $output = array('user_id' => $result[0]['user_id'], 'user_name' => $result[0]['user_name'], 'user_email' => $result[0]['user_email'], 'user_role' => $result[0]['user_role'], 'user_language' => $result[0]['user_language'], 'user_arch' => $result[0]['user_arch'], 'user_meta' => $result[0]['user_meta']);
                return $output;
            }
        } else {
            show_error('err_permission', 403);
        }

    }

    public function insert($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'JWT_Key')), apache_request_headers());
        if ($user['role'] <= 2) {
            $t = 'user';
            //Need to send or return password for new account
            $data = array($t . '_name' => $input['name'],
                $t . '_email' => $input['email'],
                $t . '_role' => $input['role']);
            if ($id === null) {
                $data[$t . '_password'] = password_hash(random_str(8), PASSWORD_DEFAULT);
                if ($this->db->insert($t, $data)) {
                    $user_id = $this->db->insert_id();
                    $r = array('id' => $user_id);
                    return $r;
                } else {
                    show_error('err_insert', 500);
                }
            } else {
                if ($this->db->update($t, $data, array($t . '_id' => $id))) {
                    return false;
                } else {
                    show_error('err_update', 500);
                }
            }
        }
    }

    public function modifyPW($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'JWT_Key')), apache_request_headers());
        if ($user['role'] <= 1) {
            $t = 'user';
            if ($this->db->update($t, array($t . '_password' => password_hash($input['password'], PASSWORD_DEFAULT)), array($t . '_id' => $id))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        }
    }

    public function remove($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'JWT_Key')), apache_request_headers());
        if ($user['role'] <= 1) {
            $t = 'user';
            if ($id === null) {
                show_error('err_id', 404);
            }
            if ($this->db->update($t, array($t . '_id' => $id))) {
                return false;
            } else {
                show_error('err_delete', 500);
            }
        }
    }

}
