<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {
	
	public function read($id) {
		$t = 'room';
		if ($id === null) {
			$query = $this->db->get($t);
			return $query->result_array();
		}
		$query = $this->db->get_where($t, array($t.'_id' => $id));
		return $query->result_array();
	}
	
}