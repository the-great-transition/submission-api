<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(600);

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

//Array keys
/*
1	Type : Communication, Panel (ou séminaire), Table ronde (ou atelier)
2	COMMUNICATION	Titre
49	PANEL			Titre
189	TABLE RONDE		Titre
249	Organisateur - Courriel
250 Organisateur - Nom
251 Infos supplémentaires

COMMUNICATION : 6 variables + nb de panélistes
PANEL : 6 variables + nb de communications
COMM DANS PANEL : 5 variables (manque Intro) + nb de panélistes
TABLE RONDE : 6 variables + nb de panélistes + 3 variables

PANÉLISTE : 10 variables
*/

class Import extends REST_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Import_model');
		
	}
	
	public function import_get() {
		//Forms: FR: 3553260, EN: 3553261
		$r = $this->import_model->import(3553260,'fr');
		$this->response($r);
	}
	
}

