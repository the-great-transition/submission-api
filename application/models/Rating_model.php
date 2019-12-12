<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rating_model extends CI_Model {

	public function read($id) {
		if ($id==null) {
			return 'ID required';
		} else {
			$output = array();
			$output['average'] = 0;
			$query = $this->db->get_where('user_subm',array('subm_id' => $id));
			$array = $query->result_array();
			foreach($array as $a) {
				$q = $this->db->get_where('user',array('user_id' => $a['user_id']));
				$r = $q->result_array();
				array_push($output,array('user_id' => $r[0]['user_id'],'user_name' => $r[0]['user_name'],'subm_rating' => $a['user_subm_rating']));
				$output['average'] += $a['user_subm_rating'];
			}
			if (sizeof($array)>0) {
				$output['average'] /= sizeof($array);
			}
			return $output;
		}
	}
	
	public function rate($input,$id) {
		if ($id==null) {
			return 'ID required';
		} else {
			$data = array('subm_id' => $id,'user_id' => $input['user_id'],'user_subm_rating' => $input['rating']);
			$q = $this->db->delete('user_subm',array('subm_id' => $id, 'user_id' => $input['user_id']));
			if ($input['rating']!=0) {
				if ($this->db->insert('user_subm',$data)) {
					$output = array();
					$output['average'] = 0;
					$query = $this->db->get_where('user_subm',array('subm_id' => $id));
					$array = $query->result_array();
					foreach($array as $a) {
						$q = $this->db->get_where('user',array('user_id' => $a['user_id']));
						$r = $q->result_array();
						array_push($output,array('user_id' => $r[0]['user_id'],'user_name' => $r[0]['user_name'],'subm_rating' => $a['user_subm_rating']));
						$output['average'] += $a['user_subm_rating'];
					}
					if (sizeof($array)>0) {
						$output['average'] /= sizeof($array);
					}
					return $output;
				} else {
					return 'Error inserting data';
				}
			} else {
				$output = array();
				$output['average'] = 0;
				$query = $this->db->get_where('user_subm',array('subm_id' => $id));
				$array = $query->result_array();
				foreach($array as $a) {
					$q = $this->db->get_where('user',array('user_id' => $a['user_id']));
					$r = $q->result_array();
					array_push($output,array('user_id' => $r[0]['user_id'],'user_name' => $r[0]['user_name'],'subm_rating' => $a['user_subm_rating']));
					$output['average'] += $a['user_subm_rating'];
				}
				if (sizeof($array)>0) {
					$output['average'] /= sizeof($array);
				}
				return $output;
			}
		}
	}

}