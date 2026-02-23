<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_DB $db
 */ 

class Admin extends CI_Controller {

public function __construct()
{
    parent::__construct();
    $this->load->library('session');
    $this->load->library('form_validation');
    $this->load->helper('form');
    $this->load->helper('pengelolaan');
    is_logged_in();
    
    // Check if user is admin
    if ($this->session->userdata('role_id') != 1) {
        redirect('auth/blocked');
    }
}

public function index()
    {
        $data['title'] = 'Management Role';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
        
    }


    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();
        
        $data['role'] = $this->db->get('user_role')->result_array();
        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        $this->form_validation->set_rules('role', 'Role', 'required|trim');

        if ($this->form_validation->run() == true) {
            $this->db->insert('user_role', [
                'role' => $this->input->post('role')
            ]);
            redirect('admin/role');
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');

}


 public function roleaccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->where('email', 
        $this->session->userdata('email'))->get('user')->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role_access', $data);
        $this->load->view('templates/footer');

    }
}