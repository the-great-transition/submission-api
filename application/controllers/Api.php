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
		/*header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}*/
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
	
	public function user_get() {
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