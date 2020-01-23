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
                    $subm = [];
                    $part_subm = $this->db->get_where('part_subm', array('part_id' => $a['part_id']))->result_array();
                    if ($part_subm) {
                        foreach ($part_subm as $ps) {
                            array_push($subm, array('subm_id' => $ps['subm_id'], 'part_subm_type' => $ps['part_subm_type'], 'part_subm_confirmation' => $ps['part_subm_confirmation']));
                        }
                    }
                    $s['part_subm'] = $subm;
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
                    $subm = [];
                    $part_subm = $this->db->get_where('part_subm', array('part_id' => $part['part_id']))->result_array();
                    if ($part_subm) {
                        foreach ($part_subm as $ps) {
                            array_push($subm, array('subm_id' => $ps['subm_id'], 'part_subm_type' => $ps['part_subm_type'], 'part_subm_confirmation' => $ps['part_subm_confirmation']));
                        }
                    }
                    $s['part_subm'] = $subm;
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
                $data = array('part_id' => '', 'part_slug' => slugify($input['part_fname'] . ' ' . $input['part_lname']));
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
            if ($this->db->update($t, array($t . '_status' => $input), array($t . '_id' => $id))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        }
    }

    public function associate($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                show_error('err_id', 404);
            }
            $t = 'part_subm';
            if ($input['delete']) {
                if ($this->db->delete($t, array('part_id' => $input['part_id'], 'subm_id' => $id))) {
                    return false;
                } else {
                    show_error('err_update', 500);
                }
            } else {
                if ($this->db->insert($t, array('part_id' => $input['part_id'], 'subm_id' => $id, 'part_subm_type' => $input['part_type']))) {
                    return false;
                } else {
                    show_error('err_update', 500);
                }
            }
        }
    }
}
