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
				return true;
			} else {
				return 'err_password';
			}
			//return $r[0]['user_password'];
			return "test";
		} else {
			return $input['email'];
		}
		//return $input['email'] . " " . $input['password'];
	}
	
}