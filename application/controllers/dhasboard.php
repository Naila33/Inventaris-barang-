<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 */ 

class Dhasboard extends CI_Controller {

public function __construct()
{
    parent::__construct();
    $this->load->library('session');
    $this->load->helper('pengelolaan');
    is_logged_in();
}

public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('dhasboard/index', $data);
        $this->load->view('templates/footer');
        
    }
}