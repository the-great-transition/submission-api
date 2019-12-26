<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subm_model extends CI_Model {
	
	public function read($id,$data) {
		$t = 'subm';
		if ($id === null) {
			//$this->db->order_by('subm_time', 'ASC');
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
				$a['id'] = $a[$t.'_id'];
				$a['value'] = $a[$t.'_title'];
				array_push($array_appended,$s);
			}
			return ($array_appended);
		} else {
			$query = $this->db->get_where($t,array($t.'_id' => $id));
			$array = $query->result_array();
			$subm = $array[0];
			$query = $this->db->get_where('user',array('user_id' => $subm['user_id']));
			$user = $query->result_array();
			$user = array('user_name' => $user[0]['user_name'],'user_email' => $user[0]['user_email']);
			$s = array_merge($subm,$user);
			if ($subm['subm_type']==1 || $subm['subm_type']==2) {
				$query = $this->db->get_where('part_subm',array($t.'_id' => $id, 'part_subm_type' => 1));
				$array = $query->result_array();
				$chair = $array[0];
				$query = $this->db->get_where('part',array('part_id' => $chair['part_id']));
				$array = $query->result_array();
				$s['chair'] = $array[0];
			}
			$parts = [];
			$comms = [];
			if ($subm['subm_type']==1) {
				$query = $this->db->get_where($t,array($t.'_forms' => $id));
				$array = $query->result_array();
				foreach ($array as $c) {
					$p = [];
					$query = $this->db->get_where('part_subm',array($t.'_id' => $c['subm_id'], 'part_subm_type' => 0));
					$a = $query->result_array();
					foreach ($a as $part) {
						$query = $this->db->get_where('part',array('part_id' => $part['part_id']));
						$r = $query->result_array();
						array_push($p,$r[0]);
					}
					$cp = $c;
					$cp['parts'] = $p;
					array_push($comms,$cp);
				}
			} else {
				$query = $this->db->get_where('part_subm',array($t.'_id' => $id, 'part_subm_type' => 0));
				$array = $query->result_array();
				foreach ($array as $p) {
					$query = $this->db->get_where('part',array('part_id' => $p['part_id']));
					$a = $query->result_array();
					array_push($parts,$a[0]);
				}
			}
			$s['parts'] = $parts;
			$s['comms'] = $comms;
			$s['rating'] = '';
			$query = $this->db->get_where('user_subm',array($t.'_id' => $id, 'user_id' => $data['user_id']));
			$array = $query->result_array();
			if (sizeof($array)==1) {
				$s['rating'] = $array[0]['user_subm_rating'];
			}
			return ($s);
		}
	}
	
}