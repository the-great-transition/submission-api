<?php
defined('BASEPATH') or exit('No direct script access allowed');

class part_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'part';
            if ($id === null) {
                $array = $this->db->get($t)->result_array();
                $array_appended = [];
                foreach ($array as $a) {
                    $part_user = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                    $part_user = array('user_name' => $part_user[0]['user_name'], 'user_email' => $part_user[0]['user_email']);
                    $s = array_merge($a, $part_user);
                    array_push($array_appended, $s);
                }
                return ($array_appended);
            } else {
                $array = $this->db->get_where($t, array($t . '_id' => $id))->result_array();
                if (count($array) > 0) {
                    $part = $array[0];
                    $part_user = $this->db->get_where('user', array('user_id' => $part['user_id']))->result_array();
                    $part_user = array('user_name' => $part_user[0]['user_name'], 'user_email' => $part_user[0]['user_email']);
                    $s = array_merge($part, $part_user);
                    return ($s);
                } else {
                    show_error('err_id', 404);
                }
            }
        }
    }

    public function insert($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                $data = array('part_id' => '', 'part_slug' => slugify($input['part_fname']." ".$input['part_lname']));
                $data = array_merge($data, $input);
                $add = array('part_status' => 0, 'subm_id' => 0, 'part_meta' => '');
                $data = array_merge($data, $add);
                if ($this->db->insert('part', $data)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            } else {

            }
        } else {
            show_error('err_update', 403);
        }
    }

    public function update($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                show_error('err_id', 404);
            }
            $t = 'part';
            if ($this->db->update($t, array($t . "_status" => $input), array($t . '_id' => $id))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        }
    }
}
