<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subm_model extends CI_Model {
	
	public function read($id) {
		$t = 'subm';
		if ($id === null) {
			$query = $this->db->get($t);
			return $query->result_array();
		}
		$query = $this->db->get_where($t,array($t.'_id' => $id));
		return $query->result_array();
	}
	
}