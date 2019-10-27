<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function read($id) {
		$t = 'user';
		if ($id === null) {
			$query = $this->db->get($t);
			return $query->result_array();
		}
		$query = $this->db->get_where($t,array($t.'_id' => $id));
		return $query->result_array();
	}
	
	public function insert($input,$id) {
		$t = 'user';
		$data = array($t.'_name' => $input['name'],
		$t.'_email' => $input['email'],
		$t.'_password' => $input['password'],
		$t.'_role' => $input['role']);
		if ($id === null) {
			if ($this->db->insert($t,$data)) {
				return 'Data inserted successfully';
			} else {
				return 'Error inserting data';
			}
		} else {
			if ($this->db->update($t,$data,array($t.'_id' => $id))) {
				return 'Data updated successfully';
			} else {
				return 'Error updating data';
			}
		}
	}
	
	public function remove($id) {
		$t = 'user';
		if ($id === null) {
			return 'ID required';
		}
		if ($this->db->delete($t,array($t.'_id' => $id))) {
			return 'Data deleted successfully';
		} else {
			return 'Error deleting data';
		}
	}
	
}