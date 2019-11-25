<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subm_model extends CI_Model {
	
	public function read($id) {
		$t = 'subm';
		if ($id === null) {
			$query = $this->db->get($t);
			$array = $query->result_array();
			$array_appended = [];
			foreach ($array as $a) {
				$query = $this->db->get_where('user',array('user_id' => $a['user_id']));
				$user = $query->result_array();
				$user = array('user_name' => $user[0]['user_name'],'user_email' => $user[0]['user_email']);
				$s = array_merge($a,$user);
				array_push($array_appended, $s);
			}
			return ($array_appended);
			//return $query->result_array();
		}
		$query = $this->db->get_where($t,array($t.'_id' => $id));
		return $query->result_array();
		
	}
	
}