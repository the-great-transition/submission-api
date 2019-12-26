<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {
	
	public function read() {
		$query = $this->db->get('user');
		return $query->result_array();
	}
	
}