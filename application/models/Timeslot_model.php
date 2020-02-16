<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timeslot_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'room_time';
            if ($id === null) {
                $array = $this->db->get($t)->result_array();
            } else {
                $array = $this->db->get_where($t, array('room_id' => $id))->result_array();
            }
            $return = array();
            foreach ($array as $a) {
                $time = $this->db->get_where('time', array('time_id' => $a['time_id']))->result_array();
                $a = array_merge($a, $time[0]);
                $room = $this->db->get_where('room', array('room_id' => $a['room_id']))->result_array();
                $a = array_merge($a, $room[0]);
                if ($a['subm_id'] != 0) {
                    $subm = $this->db->get_where('subm', array('subm_id' => $a['subm_id']))->result_array();
                    $a = array_merge($a, $subm[0]);
                }
                array_push($return, $a);
            }
            return $return;
        } else {
            show_error('err_permission', 403);
        }
    }

    public function insert($id, $input)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'room_time';
            if ($id === null) {
                show_error('err_id', 404);
            } else {
                if ($input["subm"]) {
                    $this->db->where(array('subm_id' => $input["subm"]));
                    $this->db->update($t, array('subm_id' => 0));
                    $this->db->where(array('time_id' => $input["time"], 'room_id' => $id));
                    $this->db->update($t, array('subm_id' => $input["subm"]));
                } else {
                    $this->db->delete($t, array('room_id' => $id));
                    foreach ($input["time"] as $i) {
                        $insert = array('room_id' => $id, 'time_id' => $i);
                        if (!$this->db->insert($t, $insert)) {
                            show_error('err_insert', 500);
                        }
                    }}
                return false;
            }
        } else {
            show_error('err_permission', 403);
        }
    }

    public function remove($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = "room_time";
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
