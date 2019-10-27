<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
class Api extends REST_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Room_model');
	}
	
	//User
	
	public function user_get(){
		$id = $this->uri->segment(3);
		$r = $this->user_model->read($id);
		$this->response($r); 
	}
	
	public function user_post() {
		$id = $this->uri->segment(3);
		$data = array('name' => $this->input->post('name'),
		'email' => $this->input->post('email'),
		'password' => $this->input->post('password'),
		'role' => $this->input->post('role'));
		$r = $this->user_model->insert($data,$id);
		$this->response($r); 
	}
	
	public function user_delete() {
		$id = $this->uri->segment(3);
		$r = $this->user_model->remove($id);
		$this->response($r); 
	}
	
	//Room
	
	public function room_get(){
		$id = $this->uri->segment(3);
		$r = $this->room_model->read($id);
		$this->response($r); 
	}
	
}