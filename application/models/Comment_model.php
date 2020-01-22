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
        } else {
            show_error('err_permission', 403);
        }
    }

    public function readall($status)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($status == null) {
                show_error('err_status', 404);
            } else {
                $output = array();
                $submissions = $this->db->get_where("subm", array("subm_status" => $status))->result_array();
                foreach ($submissions as $s) {
                    $user = $this->db->get_where("user", array("user_id" => $s["user_id"]))->result_array();
                    $s["user_name"] = $user[0]["user_name"];
                    $append = array();
                    $comments = $this->db->get_where("comm", array("subm_id" => $s["subm_id"]))->result_array();
                    foreach ($comments as $c) {
                        $user = $this->db->get_where("user", array("user_id" => $c["user_id"]))->result_array();
                        $data = $c;
                        $data["user_name"] = $user[0]["user_name"];
                        array_push($append, $data);
                    }
                    if (sizeof($append) > 0) {
                        $a = array("comm" => $append, "subm" => $s);
                        array_push($output, $a);
                    }
                }
                return $output;
            }
        } else {
            show_error('err_permission', 403);
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
        } else {
            show_error('err_permission', 403);
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
        } else {
            show_error('err_permission', 403);
        }
    }

}
