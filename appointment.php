<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: appointment
Description: appointment Management module for Perfex CRM
Version: 1.0.0
Requires at least: 2.3.*
Author: Hung Tran
Author URI: https://codecanyon.net/user/hungtran118
 */

define('appointment_MODULE_NAME', 'appointment');
define('appointment_MODULE_UPLOAD_FOLDER', module_dir_path(appointment_MODULE_NAME, 'uploads'));
define('appointment_PATH', 'modules/appointment/uploads/');

hooks()->add_action('admin_init', 'appointment_permissions');
hooks()->add_action('app_admin_footer', 'appointment_head_components');
hooks()->add_action('app_admin_footer', 'appointment_add_footer_components');
hooks()->add_action('admin_init', 'appointment_module_init_menu_items');

/**
 * Register activation module hook
 */
register_activation_hook(appointment_MODULE_NAME, 'appointment_module_activation_hook');
/**
 * Load the module helper
 */
$CI = &get_instance();
$CI->load->helper(appointment_MODULE_NAME . '/appointment');

function appointment_module_activation_hook()
{
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(appointment_MODULE_NAME, [appointment_MODULE_NAME]);

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function appointment_module_init_menu_items()
{

	$CI = &get_instance();
	if (has_permission('appointment', '', 'view')) {
		$CI->app_menu->add_sidebar_menu_item('appointment', [
			'name' => _l('appointment'),
			'icon' => 'fa fa-address-book',
			'position' => 60,
		]);
		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'appointment_dashboard',
			'name' => _l('dashboard'),
			'icon' => 'fa fa-home',
			'href' => admin_url('appointment/dashboard'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'appointment-message',
			'name' => _l('message'),
			'icon' => 'fa fa-address-card-o',
			'href' => admin_url('appointment/appointment_message'),
			'position' => 2,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'appointment-campaign',
			'name' => _l('campaign'),
			'icon' => 'fa fa-sitemap',
			'href' => admin_url('appointment/appointment_campaign'),
			'position' => 3,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'pet_after-profile',
			'name' => _l('pet_after_profile'),
			'icon' => 'fa fa-user-o',
			'href' => admin_url('appointment/pet_after_profile'),
			'position' => 4,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'walking-schedule',
			'name' => _l('walking_schedule'),
			'icon' => 'fa fa-calendar',
			'href' => admin_url('appointment/walking_schedule'),
			'position' => 5,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'appointment-channel',
			'name' => _l('_appointment_channel'),
			'icon' => 'fa fa-feed',
			'href' => admin_url('appointment/appointment_channel'),
			'position' => 6,
		]);

		$CI->app_menu->add_sidebar_children_item('appointment', [
			'slug' => 'rec_settings',
			'name' => _l('setting'),
			'icon' => 'fa fa-gears',
			'href' => admin_url('appointment/setting'),
			'position' => 7,
		]);
	}
}

/**
 * appointment permissions
 * @return
 */
function appointment_permissions()
{
	$capabilities = [];
	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
		'create' => _l('permission_create'),
		'edit' => _l('permission_edit'),
		'delete' => _l('permission_delete'),
	];
	register_staff_capabilities('appointment', $capabilities, _l('appointment'));
}

/**
 * add head components
 */
function appointment_head_components()
{
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/styles.css') . '"  rel="stylesheet" type="text/css" />';
	if ($viewuri == '/admin/appointment/dashboard') {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/dashboard.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if ($viewuri == '/admin/appointment/pet_afters') {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/pet_after.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/appointment/pet_after') === false)) {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/pet_after_detail.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/appointment/setting') === false)) {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/setting.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/appointment/walking_schedule') === false)) {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/walking_schedule_preview.css') . '"  rel="stylesheet" type="text/css" />';
	}
	if (!(strpos($viewuri, '/admin/appointment/appointment_campaign') === false)) {
		echo '<link href="' . module_dir_url(appointment_MODULE_NAME, 'assets/css/campaign_preview.css') . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * add footer_components
 * @return
 */
function appointment_add_footer_components()
{
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if ($viewuri == '/admin/appointment/dashboard') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/appointment_message') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/message.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/pet_afters') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/pet_after.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/pet_after_profile') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/pet_after_profile.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/appointment/transfer_to_hr') === false)) {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/transferhr.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/setting?group=evaluation_criteria') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/evaluation_criteria.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/setting?group=evaluation_form') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/evaluation_form.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/setting?group=sitterposition' || $viewuri == '/admin/appointment/setting') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/sitterposition.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/setting?group=tranfer_personnel') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/tranfer_personnel.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/walking_schedule') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/walking_schedule.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/appointment_campaign') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/campaign.js') . '"></script>';
	}
	if (!(strpos($viewuri, '/admin/appointment/appointment_campaign') === false)) {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/campaign_preview.js') . '"></script>';
	}
	if ($viewuri == '/admin/appointment/appointment_channel') {
		echo '<script src="' . module_dir_url(appointment_MODULE_NAME, 'assets/js/channel.js') . '"></script>';
	}
}
