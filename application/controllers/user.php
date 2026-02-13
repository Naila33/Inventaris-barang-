<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 * @property CI_Upload $upload
 */

class User extends CI_Controller
{

    public function __construct()
{
    parent::__construct();
    $this->load->library('session');
    if (!$this->session->userdata('email')) {
        redirect('auth');
    }
}




    public function index()
    {
        $data['title'] = 'My profile';
        $data['user'] = $this->db->where(
            'email',
            $this->session->userdata('email')
        )->get('user')->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

}