<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
	'campaign_name',
	'cp_position',
	'cp_form_work',
	'cp_sitter_available_time',
	'cp_amount_recruiment',
	'cp_status',
];
$sIndexColumn = 'cp_id';
$sTable = db_backer() . 'rec_campaign';
$join = [
	'LEFT JOIN ' . db_backer() . 'rec_sitterposition on ' . db_backer() . 'rec_sitterposition.position_id = ' . db_backer() . 'rec_campaign.cp_position',
	'LEFT JOIN ' . db_backer() . 'sitter_available_times on ' . db_backer() . 'sitter_available_times.sitter_available_timeid = ' . db_backer() . 'rec_campaign.cp_sitter_available_time',
];
$where = [];

if ($this->ci->input->post('posiotion_ft')) {
	$posiotion_ft = $this->ci->input->post('posiotion_ft');
	$where_posiotion_ft = '';
	foreach ($posiotion_ft as $y) {
		if ($y != '') {
			if ($where_posiotion_ft == '') {
				$where_posiotion_ft .= ' AND (tblrec_campaign.cp_position = "' . $y . '"';
			} else {
				$where_posiotion_ft .= ' or tblrec_campaign.cp_position = "' . $y . '"';
			}
		}
	}
	if ($where_posiotion_ft != '') {
		$where_posiotion_ft .= ')';
		array_push($where, $where_posiotion_ft);
	}
}
if ($this->ci->input->post('dpm')) {
	$dpm = $this->ci->input->post('dpm');
	$where_dpm = '';
	foreach ($dpm as $y) {
		if ($y != '') {
			if ($where_dpm == '') {
				$where_dpm .= ' AND (tblrec_campaign.cp_sitter_available_time = "' . $y . '"';
			} else {
				$where_dpm .= ' or tblrec_campaign.cp_sitter_available_time = "' . $y . '"';
			}
		}
	}
	if ($where_dpm != '') {
		$where_dpm .= ')';
		array_push($where, $where_dpm);
	}
}
if ($this->ci->input->post('status')) {
	$status = $this->ci->input->post('status');
	$where_status = '';
	foreach ($status as $y) {
		if ($y != '') {
			if ($where_status == '') {
				$where_status .= ' AND (tblrec_campaign.cp_status = "' . $y . '"';
			} else {
				$where_status .= ' or tblrec_campaign.cp_status = "' . $y . '"';
			}
		}
	}
	if ($where_status != '') {
		$where_status .= ')';
		array_push($where, $where_status);
	}
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['campaign_code', 'cp_id', 'position_name', db_backer() . 'sitter_available_times.name as dpm_name', 'cp_walkplace', 'cp_walk_time_from', 'cp_walk_time_to', 'cp_from_date', 'cp_to_date', 'cp_ages_to', 'cp_ages_from', 'cp_height', 'cp_weight', 'cp_sitter_description', 'cp_reason_appointment', 'cp_manager', 'cp_follower', 'cp_gender', 'cp_experience', 'cp_literacy', 'cp_message']);

$output = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
	$row = [];
	for ($i = 0; $i < count($aColumns); $i++) {
		$_data = $aRow[$aColumns[$i]];
		if ($aColumns[$i] == 'campaign_name') {

			$name = '<a href="' . admin_url('appointment/appointment_campaign/' . $aRow['cp_id']) . '" onclick="init_appointment_campaign(' . $aRow['cp_id'] . '); return false;">' . $aRow['campaign_name'] . '</a>';

			$name .= '<div class="row-options">';

			$name .= '<a href="' . admin_url('appointment/appointment_campaign/' . $aRow['cp_id']) . '" onclick="init_appointment_campaign(' . $aRow['cp_id'] . '); return false;">' . _l('view') . '</a>';

			if (has_permission('appointment', '', 'edit') || is_admin()) {
				$name .= ' | <a href="#" onclick=' . '"' . 'edit_campaign(this,' . $aRow['cp_id'] . '); return false;' . '"' . ' data-campaign_code="' . $aRow['campaign_code'] . '" data-campaign_name="' . $aRow['campaign_name'] . '" data-position="' . $aRow['cp_position'] . '" data-form_work="' . $aRow['cp_form_work'] . '" data-sitter_available_time="' . $aRow['cp_sitter_available_time'] . '" data-amount_recruiment="' . $aRow['cp_amount_recruiment'] . '" data-walkplace="' . $aRow['cp_walkplace'] . '" data-walk_time_from="' . app_format_money($aRow['cp_walk_time_from'], '') . '" data-walk_time_to="' . app_format_money($aRow['cp_walk_time_to'], '') . '" data-from_date="' . _d($aRow['cp_from_date']) . '" data-to_date="' . _d($aRow['cp_to_date']) . '" data-ages_to="' . $aRow['cp_ages_to'] . '" data-ages_from="' . $aRow['cp_ages_from'] . '" data-height="' . $aRow['cp_height'] . '" data-weight="' . $aRow['cp_weight'] . '" data-reason_appointment="' . $aRow['cp_reason_appointment'] . '" data-gender="' . $aRow['cp_gender'] . '" data-literacy="' . $aRow['cp_literacy'] . '" data-experience="' . $aRow['cp_experience'] . '" data-message="' . $aRow['cp_message'] . '" data-manager="' . $aRow['cp_manager'] . '" data-follower="' . $aRow['cp_follower'] . '" >' . _l('edit') . '</a>';
			}

			if (has_permission('appointment', '', 'delete') || is_admin()) {
				$name .= ' | <a href="' . admin_url('appointment/delete_appointment_campaign/' . $aRow['cp_id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
			}

			$name .= '</div>';

			$_data = $name;
		} elseif ($aColumns[$i] == 'cp_form_work') {
			$_data = _l($aRow['cp_form_work']);
		} elseif ($aColumns[$i] == 'cp_position') {
			$_data = $aRow['position_name'];
		} elseif ($aColumns[$i] == 'cp_sitter_available_time') {
			$_data = $aRow['dpm_name'];
		} elseif ($aColumns[$i] == 'cp_status') {
			if ($aRow['cp_status'] == 1) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-planning-style"> ' . _l('planning') . ' </span>';
			} elseif ($aRow['cp_status'] == 3) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-progress-style"> ' . _l('in_progress') . ' </span>';
			} elseif ($aRow['cp_status'] == 4) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-finish-style"> ' . _l('finish') . ' </span>';
			} elseif ($aRow['cp_status'] == 5) {
				$_data = ' <span class="label label inline-block project-status-' . $aRow['cp_status'] . ' campaign-cancel-style"> ' . _l('cancel') . ' </span>';
			}
		}

		$row[] = $_data;
	}
	$output['aaData'][] = $row;
}
