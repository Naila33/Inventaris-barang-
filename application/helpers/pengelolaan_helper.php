<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Check if user is logged in
 * If not logged in, redirect to auth page
 */
if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        $CI =& get_instance();
        if (!$CI->session->userdata('email')) {
            redirect('auth');
        }
    }
}

/**
 * Get current user data from session
 * @return array|null User data or null if not logged in
 */
if (!function_exists('get_current_user')) {
    function get_current_user()
    {
        $CI =& get_instance();
        return $CI->session->userdata('email') ? $CI->session->userdata() : null;
    }
}

/**
 * Check if current user is admin
 * @return bool True if user is admin, false otherwise
 */
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $CI =& get_instance();
        return $CI->session->userdata('role_id') == 1;
    }
}

/**
 * Format date to Indonesian format
 * @param string $date Date string
 * @return string Formatted date
 */
if (!function_exists('format_tanggal')) {
    function format_tanggal($date)
    {
        if ($date) {
            return date('d/m/Y', strtotime($date));
        }
        return '-';
    }
}

/**
 * Format currency to Indonesian Rupiah
 * @param int $amount Amount
 * @return string Formatted currency
 */
if (!function_exists('format_rupiah')) {
    function format_rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
