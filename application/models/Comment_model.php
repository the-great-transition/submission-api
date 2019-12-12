<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment_model extends CI_Model {

	public function read($id) {
		if ($id==null) {
			return 'ID required';
		} else {
			$output = array();
			$this->db->order_by('comm_time', 'ASC');
			$query = $this->db->get_where('comm',array('subm_id' => $id));
			$array = $query->result_array();
			foreach($array as $a) {
				$q = $this->db->get_where('user',array('user_id' => $a['user_id']));
				$r = $q->result_array();
				$user = array('user_id' => $r[0]['user_id'],'user_name' => $r[0]['user_name']);
				$c = array_merge($a,$user);
				array_push($output,$c);
			}
			return $output;
		}
	}
	
	public function insert($input,$id) {
		if ($id==null) {
			return 'ID required';
		} else {
			$data = array('subm_id' => $id,'user_id' => $input['user_id'],'comm_text' => $input['comment']);
			if ($this->db->insert('comm',$data)) {
				return 'Data inserted successfully';
			} else {
				return 'Error inserting data';
			}
		}
	}
	
	public function remove($id) {
		if ($id === null) {
			return 'ID required';
		}
		if ($this->db->delete('comm',array('comm_id' => $id))) {
			return 'Data deleted successfully';
		} else {
			return 'Error deleting data';
		}
	}

}