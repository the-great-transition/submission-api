<?php
defined('BASEPATH') or exit('No direct script access allowed');

class confirmation_model extends CI_Model
{

    public function read($id)
    {
        if ($id !== null) {
            $array = [];
            $part_subm = $this->db->get_where('part_subm', array('part_id' => $id))->result_array();
            if ($part_subm) {
                foreach ($part_subm as $ps) {
                    $subm = $this->db->get_where('subm', array('subm_id' => $ps["subm_id"]))->result_array();
                    array_push($array, array("subm_id" => $subm[0]["subm_id"], "subm_title" => $subm[0]["subm_title"], 'part_subm_type' => $ps['part_subm_type'], 'part_subm_confirmation' => $ps['part_subm_confirmation']));
                }
                return $array;
            } else {
                show_error('err_id_empty', 404);
            }
        } else {
            show_error('err_no_id', 404);
        }
    }

    public function update($id, $input)
    {
        $t = "part_subm";
        if ($id !== null) {
            if ($this->db->update($t, array($t . "_confirmation" => $input["confirmation"]), array("part_id" => $id, "subm_id" => $input["subm_id"]))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        } else {
            show_error('err_id', 404);
        }
    }

}
