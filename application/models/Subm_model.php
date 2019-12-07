<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subm_model extends CI_Model {
	
	public function read($id,$data) {
		$t = 'subm';
		if ($id === null) {
			$this->db->order_by('subm_time', 'ASC');
			if ($data['language']!=-1) { $this->db->where($t.'_language', $data['language']); };
			if ($data['level']!=-1) { $this->db->where($t.'_level', $data['level']); };
			if ($data['theme']!=-1) { $this->db->where($t.'_theme', $data['theme']); };
			if ($data['orientation']!=-1) { $this->db->where($t.'_orientation', $data['orientation']); };
			if ($data['type']!=-1) { $this->db->where($t.'_type', $data['type']); };
			if ($data['status']!=-1) { $this->db->where($t.'_status', $data['status']); };
			$this->db->where($t.'_forms', 0);
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
		}
		$query = $this->db->get_where($t,array($t.'_id' => $id));
		return $query->result_array();
		
	}
	
}