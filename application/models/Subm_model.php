<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subm_model extends CI_Model
{

    public function read($id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = 'subm';
            if ($id === null) {
                $this->db->where($t . '_forms', 0);
                $array = $this->db->get($t)->result_array();
                $array_appended = [];
                foreach ($array as $a) {
                    $subm_user = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                    $subm_user = array('user_name' => $subm_user[0]['user_name'], 'user_email' => $subm_user[0]['user_email']);
                    $s = array_merge($a, $subm_user);
                    $rating = array('average' => 0, 'rated' => false);
                    $ratings = $this->db->get_where('user_subm', array('subm_id' => $a['subm_id']))->result_array();
                    foreach ($ratings as $r) {
                        if ($r['user_id'] === $user['id']) {
                            $rating['rated'] = true;
                        }
                        $rating['average'] += $r['user_subm_rating'];
                    }
                    if (sizeof($ratings) > 0) {
                        $rating['average'] /= sizeof($ratings);
                    }
                    $s = array_merge($s, $rating);
                    array_push($array_appended, $s);
                }
                return ($array_appended);
            } else {
                $array = $this->db->get_where($t, array($t . '_id' => $id))->result_array();
                if (count($array) > 0) {
                    $subm = $array[0];
                    $subm_user = $this->db->get_where('user', array('user_id' => $subm['user_id']))->result_array();
                    $subm_user = array('user_name' => $subm_user[0]['user_name'], 'user_email' => $subm_user[0]['user_email']);
                    $s = array_merge($subm, $subm_user);
                    if ($subm['subm_type'] == 1 || $subm['subm_type'] == 2) {
                        $array = $this->db->get_where('part_subm', array($t . '_id' => $id, 'part_subm_type' => 1))->result_array();
                        if (count($array) > 0) {
                            $chair = $array[0];
                            $array = $this->db->get_where('part', array('part_id' => $chair['part_id']))->result_array();
                            $s['chair'] = $array[0];
                        }
                    }
                    $parts = [];
                    $comms = [];
                    if ($subm['subm_type'] == 1) {
                        $array = $this->db->get_where($t, array($t . '_forms' => $id))->result_array();
                        foreach ($array as $c) {
                            $p = [];
                            $a = $this->db->get_where('part_subm', array($t . '_id' => $c['subm_id'], 'part_subm_type' => 0))->result_array();
                            foreach ($a as $part) {
                                $r = $this->db->get_where('part', array('part_id' => $part['part_id']))->result_array();
                                array_push($p, $r[0]);
                            }
                            $cp = $c;
                            $cp['parts'] = $p;
                            array_push($comms, $cp);
                        }
                    } else {
                        $array = $this->db->get_where('part_subm', array($t . '_id' => $id, 'part_subm_type' => 0))->result_array();
                        foreach ($array as $p) {
                            $a = $this->db->get_where('part', array('part_id' => $p['part_id']))->result_array();
                            array_push($parts, $a[0]);
                        }
                    }
                    $s['parts'] = $parts;
                    $s['comms'] = $comms;
                    $s['rating'] = '';
                    $array = $this->db->get_where('user_subm', array($t . '_id' => $id, 'user_id' => $user['id']))->result_array();
                    if (sizeof($array) == 1) {
                        $s['rating'] = $array[0]['user_subm_rating'];
                    }
                    return ($s);
                } else {
                    show_error('err_id', 404);
                }
            }
        }
    }

    public function readstatus($status)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            $t = "subm";
            $this->db->where($t . '_forms', 0);
            $array = $this->db->get_where($t, array($t . '_status' => $status))->result_array();
            $array_appended = [];
            foreach ($array as $a) {
                $subm_user = $this->db->get_where('user', array('user_id' => $a['user_id']))->result_array();
                $subm_user = array('user_name' => $subm_user[0]['user_name'], 'user_email' => $subm_user[0]['user_email']);
                $s = array_merge($a, $subm_user);
                if ($s['subm_type'] == 1 || $s['subm_type'] == 2) {
                    $array = $this->db->get_where('part_subm', array($t . '_id' => $s["subm_id"], 'part_subm_type' => 1))->result_array();
                    if (count($array) > 0) {
                        $chair = $array[0];
                        $array2 = $this->db->get_where('part', array('part_id' => $chair['part_id']))->result_array();
                        $s['chair'] = $array2[0];
                        $s['chair']['part_subm_type'] = $chair['part_subm_type'];
                        $s['chair']['part_subm_confirmation'] = $chair['part_subm_confirmation'];
                    }
                }
                if ($s['subm_type'] == 1) {
                    $comms = [];
                    $array = $this->db->get_where($t, array($t . '_forms' => $s["subm_id"]))->result_array();
                    foreach ($array as $c) {
                        $p = [];
                        $a = $this->db->get_where('part_subm', array($t . '_id' => $c['subm_id'], 'part_subm_type' => 0))->result_array();
                        foreach ($a as $part) {
                            $r = $this->db->get_where('part', array('part_id' => $part['part_id']))->result_array();
                            $return = array('part_subm_type' => $part['part_subm_type'], 'part_subm_confirmation' => $part['part_subm_confirmation']);
                            array_push($p, array_merge($return, $r[0]));
                        }
                        $cp = $c;
                        $cp['parts'] = $p;
                        array_push($comms, $cp);
                    }
                    $s["comms"] = $comms;
                } else {
                    $parts = [];
                    $array = $this->db->get_where('part_subm', array($t . '_id' => $s["subm_id"], 'part_subm_type' => 0))->result_array();
                    foreach ($array as $p) {
                        $a = $this->db->get_where('part', array('part_id' => $p['part_id']))->result_array();
                        $return = array('part_subm_type' => $p['part_subm_type'], 'part_subm_confirmation' => $p['part_subm_confirmation']);
                        array_push($parts, array_merge($return, $a[0]));

                    }
                    $s["parts"] = $parts;
                }
                array_push($array_appended, $s);
            }
            return ($array_appended);
        }
    }

    public function insert($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                $data = array_merge(array('subm_id' => '', 'subm_slug' => slugify($input['subm_title'])), $input);
                $data = array_merge($data, array('subm_meta' => ''));
                if ($this->db->insert('subm', $data)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            } else {
                $this->db->where('subm_id', $id);
                if ($this->db->update('subm', $input)) {
                    return false;
                } else {
                    show_error('err_insert', 500);
                }
            }
        } else {
            show_error('err_permission', 403);
        }
    }

    public function update($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                show_error('err_id', 404);
            }
            $t = 'subm';
            if ($this->db->update($t, array($t . "_status" => $input), array($t . '_id' => $id))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        }
    }

    public function associate($input, $id)
    {
        $user = (array) tryKey($this->db->get_where('conf', array('conf_label' => 'jwt_key')), apache_request_headers());
        if ($user) {
            if ($id === null) {
                show_error('err_id', 404);
            }
            $t = 'subm';
            if ($this->db->update($t, array($t . "_forms" => $id), array($t . '_id' => $input))) {
                return false;
            } else {
                show_error('err_update', 500);
            }
        }
    }

}
