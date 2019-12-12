<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

class Api extends REST_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Login_model');
		$this->load->model('User_model');
		$this->load->model('Room_model');
	}
	
	//Login
	
	public function login_post() {
		$post = json_decode(file_get_contents('php://input'),true);
		$email = htmlspecialchars(clean_input($post['data']['email']));
		$password = htmlspecialchars(clean_input($post['data']['password']));
		$data = array('email' => $email,
		'password' => $password);
		$r = $this->login_model->login($data);
		$this->response($r);
	}
	
	//User
	
	/*public function user_get() {
		$id = $this->uri->segment(3);
		$r = $this->user_model->read($id);
		$this->response($r); 
	}*/
	
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
	
	//Submissions
	
	public function subm_get() {
		$id = $this->uri->segment(3);
		$params = $this->input->get();
		$r = $this->subm_model->read($id,$params);
		$this->response($r); 
	}
	
	//Ratings
	
	public function rating_get() {
		$id = $this->uri->segment(3);
		$r = $this->rating_model->read($id);
		$this->response($r); 
	}
	
	public function rating_post() {
		$id = $this->uri->segment(3);
		$post = json_decode(file_get_contents('php://input'),true);
		$data = array('user_id' => $post['data']['user_id'],
		'rating' => $post['data']['rating']);
		$r = $this->rating_model->rate($data,$id);
		$this->response($r); 
	}
	
	//Comments
	
	public function comment_get() {
		$id = $this->uri->segment(3);
		$r = $this->comment_model->read($id);
		$this->response($r); 
	}
	
	public function comment_post() {
		$id = $this->uri->segment(3);
		$post = json_decode(file_get_contents('php://input'),true);
		$data = array('user_id' => $post['data']['user_id'],
		'comment' => $post['data']['comment']);
		$r = $this->comment_model->insert($data,$id);
		$this->response($r); 
	}
	
	public function comment_delete() {
		$id = $this->uri->segment(3);
		$r = $this->comment_model->remove($id);
		$this->response($r); 
	}
	
	//Room
	
	public function room_get(){
		$id = $this->uri->segment(3);
		$r = $this->room_model->read($id);
		$this->response($r); 
	}
	
}