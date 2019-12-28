<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rating_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user['role'] <= 3) {
            if ($id == null) {
                show_error('err_id', 404);
            } else {
                $output = array('average' => 0);
                $array = $this->db->get_where('user_subm', array('subm_id' => $id))->result_array();
                foreach ($array as $a) {
                    $r = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                    if ($r[0]['user_id'] === $user['id']) {
                        $output['myRating'] = $a['user_subm_rating'];
                    } else {
                        array_push($output, array('user_id' => $r[0]['user_id'], 'user_name' => $r[0]['user_name'], 'subm_rating' => $a['user_subm_rating']));
                    }
                    $output['average'] += $a['user_subm_rating'];
                }
                if (sizeof($array) > 0) {
                    $output['average'] /= sizeof($array);
                }
                return $output;
            }
        }
    }

    public function rate($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user['role'] <= 2) {
            if ($id === null) {
                show_error('err_id', 404);
            } else {
                $data = array('subm_id' => $id, 'user_id' => $user['id'], 'user_subm_rating' => $input['rating']);
                $q = $this->db->delete('user_subm', array('subm_id' => $id, 'user_id' => $user['id']));
                if ($input['rating'] != 0) {
                    if ($this->db->insert('user_subm', $data)) {
                        $output = array();
                        $output['average'] = 0;
                        $array = $this->db->get_where('user_subm', array('subm_id' => $id))->result_array();
                        foreach ($array as $a) {
                            $r = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                            if ($r[0]['user_id'] === $user['id']) {
                                $output['myRating'] = $a['user_subm_rating'];
                            } else {
                                array_push($output, array('user_id' => $r[0]['user_id'], 'user_name' => $r[0]['user_name'], 'subm_rating' => $a['user_subm_rating']));
                            }
                            $output['average'] += $a['user_subm_rating'];
                        }
                        if (sizeof($array) > 0) {
                            $output['average'] /= sizeof($array);
                        }
                        return $output;
                    } else {
                        show_error('err_insert', 500);
                    }
                } else {
                    $output = array();
                    $output['average'] = 0;
                    $array = $this->db->get_where('user_subm', array('subm_id' => $id))->result_array();
                    foreach ($array as $a) {
                        $r = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                        if ($r[0]['user_id'] === $user['id']) {
                            $output['myRating'] = $a['user_subm_rating'];
                        } else {
                            array_push($output, array('user_id' => $r[0]['user_id'], 'user_name' => $r[0]['user_name'], 'subm_rating' => $a['user_subm_rating']));
                        }
                        $output['average'] += $a['user_subm_rating'];
                    }
                    if (sizeof($array) > 0) {
                        $output['average'] /= sizeof($array);
                    }
                    return $output;
                }
            }
        }
    }

}
