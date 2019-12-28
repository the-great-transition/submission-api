<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comment_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id == null) {
                show_error('err_id', 404);
            } else {
                $output = array();
                $this->db->order_by('comm_time', 'ASC');
                $array = $this->db->get_where('comm', array('subm_id' => $id))->result_array();
                foreach ($array as $a) {
                    $r = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                    $u = array('user_id' => $r[0]['user_id'], 'user_name' => $r[0]['user_name']);
                    $c = array_merge($a, $u);
                    array_push($output, $c);
                }
                return $output;
            }
        }
    }

    public function insert($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id == null) {
                show_error('err_id', 404);
            } else {
                $data = array('subm_id' => $id, 'user_id' => $user['id'], 'comm_text' => $input['comment']);
                if ($this->db->insert('comm', $data)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            }
        }
    }

    public function remove($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                show_error('err_id', 404);
            }
            if ($this->db->delete('comm', array('comm_id' => $id))) {
                return false;
            } else {
                show_error('err_delete', 500);
            }
        }
    }

}
