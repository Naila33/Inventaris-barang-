<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        $ci = get_instance();
        if (!$ci->session->userdata('email')) {
            redirect('auth');
        }
    }
}

if (!function_exists('check_access')) {
    function check_access($role_id, $menu_id) {
        $ci = get_instance();
        $ci->load->database();
        
        $result = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);
        
        if ($result->num_rows() > 0) {
            return "checked";
        }
        return "";
    }
}