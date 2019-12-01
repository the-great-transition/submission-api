<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Removed because of conflict with Import2
/*function slugify($text) {
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function textHTML($string) {
	$string = htmlspecialchars($string, ENT_QUOTES);
	$string = str_replace('|', '&#13;',$string);
	return $string;
}

function associateSubm($input) {
	$reference_subm_fr = array(
		'subm_language' => array(
			"Français" => 0,
			"Anglais" => 1,
			"Bilingue" => 2
		),
		'subm_level' => array(
			"NA" => 0,
			"Oui" => 1,
			"Non" => 2
		),
		'subm_theme' => array(
			"Une économie pour tout le monde" => 1,
			"Transformer notre rapport à la nature" => 2,
			"Lanti-impérialisme dans un monde turbulent" => 3,
			"Repenser la démocratie et le pouvoir" => 4,
			"Décoloniser les savoirs" => 5,
			"Lutter contre les oppressions" => 6
		),
		'subm_orientation' => array(
			"Stratégies" => 1,
			"Modèles" => 2
		)
	);
	$reference_subm_an = array(
		'subm_language' => array(
			"English" => 0,
			"Français" => 1,
			"Bilingual" => 2
		),
		'subm_level' => array(
			"NA" => 0,
			"Yes" => 1,
			"No" => 2
		),
		'subm_theme' => array(
			"An Economy in the Hands of Everyone" => 1,
			"Transforming our Relationship with Nature" => 2,
			"Anti-Imperialism in a Tempest-Tossed World" => 3,
			"Rethinking Democracy and Power" => 4,
			"Decolonizing Knowledge" => 5,
			"Fighting All Oppression" => 6
		),
		'subm_orientation' => array(
			"Strategies" => 1,
			"Models" => 2
		)
	);
	$array = array(
		'subm_id' => $input[0],
		'subm_time' => $input[1],
		'subm_slug' => $input[2],
		'subm_title' => $input[3],
		'subm_description' => $input[4],
		'subm_language' => $reference_subm_an['subm_language'][$input[5]],
		'subm_level' => $reference_subm_an['subm_level'][$input[6]],
		'subm_theme' => $reference_subm_an['subm_theme'][str_replace("&#039;", "", $input[7])],
		'subm_orientation' => $reference_subm_an['subm_orientation'][$input[8]],
		'subm_type' => $input[9],
		'subm_status' => $input[10],
		'subm_forms' => $input[11],
		'user_id' => $input[12],
		'subm_meta' => $input[13]
	);
	return $array;
}

function associatePart($input) {
	$array = array(
		'part_id' => $input[0],
		'part_slug' => $input[1],
		'part_fname' => $input[2],
		'part_lname' => $input[3],
		'part_pronouns' => $input[4],
		'part_email' => $input[5],
		'part_photo' => '',
		'part_affiliation' => $input[6],
		'part_bio' => $input[7],
		'part_city' => $input[8],
		'part_country' => $input[9],
		'part_gender' => $input[10],
		'part_minority' => $input[11],
		'user_id' => $input[12],
		'subm_id' => $input[13],
		'part_meta' => $input[14]
	);
	return $array;
}*/

class Import_model extends CI_Model {

	public function import($input) {

		foreach ($input as $a) {
			$user_id = '';
			$query = $this->db->get_where('user', array('user_email' => $a[249]));
			if ($r = $query->result_array()) {
				$user_id .= $r[0]['user_id'];
			} else {
				$user = array('user_id' => '','user_name' => $a[250],'user_email' => $a[249],'user_password' => '1A2B3C!','user_role' => 4,'user_meta' => '');
				$this->db->insert('user', $user);
				$user_id .= $this->db->insert_id();
			}
			$subm = array();
			array_push($subm,'');
			array_push($subm,$a[0]);
			if ($a[1]=='Communication') {
				$b = 1;
				array_push($subm,slugify($a[$b+1]));
				for ($i=1; $i<=6; $i++) {
					array_push($subm, textHTML($a[$b+$i]));
				}
				array_push($subm, 0); //Type
				array_push($subm, 0); //Status
				array_push($subm, 0); //Forms
				array_push($subm, $user_id);
				array_push($subm, ''); //Meta
				$this->db->insert('subm', associateSubm($subm));
				$subm_id = '';
				$subm_id .= $this->db->insert_id();
				for ($i=0; $i<$a[$b+7]; $i++) {
					$part = array('');
					$pslug = $a[$b+7+($i*10)+1].' '.$a[$b+7+($i*10)+2].' '.$a[$b+7+($i*10)+7];
					array_push($part,slugify($pslug));
					for ($x=1; $x<=10; $x++) {
						array_push($part, textHTML($a[$b+7+($i*10)+$x]));
					}
					array_push($part, $user_id);
					array_push($part, $subm_id);
					array_push($part, ''); //Meta
					$this->db->insert('part', associatePart($part));
					$part_id = '';
					$part_id .= $this->db->insert_id();
					$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
					$this->db->insert('part_subm', $part_subm);
				}
			} else if ($a[1]=='Panel (ou séminaire)') {
				$b = 48;
				array_push($subm,slugify($a[$b+1]));
				for ($i=1; $i<=6; $i++) {
					array_push($subm, textHTML($a[$b+$i]));
				}
				array_push($subm, 1); //Type
				array_push($subm, 0); //Status
				array_push($subm, 0); //Forms
				array_push($subm, $user_id);
				array_push($subm, ''); //Meta
				$this->db->insert('subm', associateSubm($subm));
				$panel_id = '';
				$panel_id .= $this->db->insert_id();
				$part = array('');
				$pslug = $a[$b+8].' '.$a[$b+9].' '.$panel_id;
				array_push($part,slugify($pslug));
				array_push($part, textHTML($a[$b+8]));
				array_push($part, textHTML($a[$b+9]));
				array_push($part, '');
				array_push($part, textHTML($a[$b+10]));
				for ($x=0; $x<6; $x++) {
					array_push($part, '');
				}
				array_push($part, $user_id);
				array_push($part, $panel_id);
				array_push($part, ''); //Meta
				$this->db->insert('part', associatePart($part));
				$part_id = '';
				$part_id .= $this->db->insert_id();
				$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 1);
				$this->db->insert('part_subm', $part_subm);
				for ($y=0;$y<$a[$b+7];$y++) {
					$c = $b+10+($y*26);
					$comm = array();
					array_push($comm,'');
					array_push($comm,$a[0]);
					array_push($comm,slugify($a[$c+1]));
					for ($p=1; $p<=6; $p++) {
						if ($p==4) {
							array_push($comm,'NA');
						} else {
							if ($p<4) {
								array_push($comm, textHTML($a[$c+$p]));
							} else if ($p>4) {
								array_push($comm, textHTML($a[$c+$p-1]));
							}
						}
					}
					array_push($comm, 0); //Type
					array_push($comm, 0); //Status
					array_push($comm, $panel_id);
					array_push($comm, $user_id);
					array_push($comm, ''); //Meta
					$this->db->insert('subm', associateSubm($comm));
					$subm_id = '';
					$subm_id .= $this->db->insert_id();
					for ($i=0; $i<$a[$c+6]; $i++) {
						$part = array('');
						$pslug = $a[$c+6+($i*10)+1].' '.$a[$c+6+($i*10)+2].' '.$a[$c+6+($i*10)+7];
						array_push($part,slugify($pslug));
						for ($x=1; $x<=10; $x++) {
							array_push($part, textHTML($a[$c+6+($i*10)+$x]));
						}
						array_push($part, $user_id);
						array_push($part, $subm_id);
						array_push($part, ''); //Meta
						$this->db->insert('part', associatePart($part));
						$part_id = '';
						$part_id .= $this->db->insert_id();
						$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
						$this->db->insert('part_subm', $part_subm);
					}
				}
			} else if ($a[1]=='Table ronde (ou atelier)') {
				$b = 188;
				array_push($subm,slugify($a[$b+1]));
				for ($i=1; $i<=6; $i++) {
					array_push($subm, textHTML($a[$b+$i]));
				}
				array_push($subm, 2); //Type
				array_push($subm, 0); //Status
				array_push($subm, 0); //Forms
				array_push($subm, $user_id);
				array_push($subm, ''); //Meta
				$this->db->insert('subm', associateSubm($subm));
				$subm_id = '';
				$subm_id .= $this->db->insert_id();
				$part = array('');
				$pslug = $a[$b+8].' '.$a[$b+9].' '.$subm_id;
				array_push($part,slugify($pslug));
				array_push($part, textHTML($a[$b+8]));
				array_push($part, textHTML($a[$b+9]));
				array_push($part, '');
				array_push($part, textHTML($a[$b+10]));
				for ($x=0; $x<6; $x++) {
					array_push($part, '');
				}
				array_push($part, $user_id);
				array_push($part, $subm_id);
				array_push($part, ''); //Meta
				$this->db->insert('part', associatePart($part));
				$part_id = '';
				$part_id .= $this->db->insert_id();
				$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 1);
				$this->db->insert('part_subm', $part_subm);
				for ($i=0; $i<$a[$b+7]; $i++) {
					$part = array('');
					$pslug = $a[$b+7+($i*10)+1].' '.$a[$b+7+($i*10)+2].' '.$a[$b+7+($i*10)+7];
					array_push($part,slugify($pslug));
					for ($x=1; $x<=10; $x++) {
						array_push($part, textHTML($a[$b+10+($i*10)+$x]));
					}
					array_push($part, $user_id);
					array_push($part, $subm_id);
					array_push($part, ''); //Meta
					$this->db->insert('part', associatePart($part));
					$part_id = '';
					$part_id .= $this->db->insert_id();
					$part_subm = array('part_id' => $part_id, 'subm_id' => $subm_id, 'part_subm_type' => 0);
					$this->db->insert('part_subm', $part_subm);
				}
			}
		}
		return 'Done';
	}
	
}