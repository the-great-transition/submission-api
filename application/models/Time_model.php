<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Time_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'time';
            if ($id === null) {
                $query = $this->db->get($t);
                return $query->result_array();
            }
            $query = $this->db->get_where($t, array($t . '_id' => $id));
            return $query->result_array();
        } else {
            show_error('err_permission', 403);
        }
    }

    public function insert($id, $input)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'time';
            if ($id === null) {
                $input[$t . '_id'] = '';
                $input[$t . '_meta'] = '';
                if ($this->db->insert($t, $input)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            } else {
                $this->db->where($t . '_id', $id);
                if ($this->db->update($t, $input)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            }
        } else {
            show_error('err_permission', 403);
        }
    }

    public function remove($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = "time";
            if ($id === null) {
                show_error('err_id', 404);
            }
            if ($this->db->delete($t, array($t . '_id' => $id))) {
                $this->db->delete("room_time", array($t . '_id' => $id));
                return false;
            } else {
                show_error('err_delete', 500);
            }
        } else {
            show_error('err_permission', 403);
        }
    }

}
