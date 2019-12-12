<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	public function login($input) {
		$t = 'user';
		$query = $this->db->get_where($t,array($t.'_email' => $input['email']));
		if ($r = $query->result_array()) {
			//if (password_verify($input['password'],$r[0]['user_password'])) {
			//Need to hash passwords
			if ($input['password'] === $r[0]['user_password']) {
				//Set session
				return array('response' => 'success', 'id' => $r[0]['user_id'], 'role' => $r[0]['user_role']);
			} else {
				return array('response' => 'err_password');
			}
		} else {
			return array('response' => 'err_email');
		}
	}
	
}