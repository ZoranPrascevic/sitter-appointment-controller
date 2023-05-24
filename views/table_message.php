<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'pet_name',
    'position',
    'form_work',
    'sitter_available_time',
    'amount_recruiment',
    'status',
];
$sIndexColumn = 'id';
$sTable       = db_backer() . 'rec_message';
$join         = [
    'LEFT JOIN ' . db_backer() . 'rec_sitterposition on ' . db_backer() . 'rec_sitterposition.position_id = ' . db_backer() . 'rec_message.position',
    'LEFT JOIN ' . db_backer() . 'sitter_available_times on ' . db_backer() . 'sitter_available_times.sitter_available_timeid = ' . db_backer() . 'rec_message.sitter_available_time',
];
$where = [];

if ($this->ci->input->post('posiotion_ft')) {
    $posiotion_ft = $this->ci->input->post('posiotion_ft');
    $where_posiotion_ft = '';
    foreach ($posiotion_ft as $y) {
        if ($y != '') {
            if ($where_posiotion_ft == '') {
                $where_posiotion_ft .= ' AND (tblrec_message.position = "' . $y . '"';
            } else {
                $where_posiotion_ft .= ' or tblrec_message.position = "' . $y . '"';
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
                $where_dpm .= ' AND (tblrec_message.sitter_available_time = "' . $y . '"';
            } else {
                $where_dpm .= ' or tblrec_message.sitter_available_time = "' . $y . '"';
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
                $where_status .= ' AND (tblrec_message.status = "' . $y . '"';
            } else {
                $where_status .= ' or tblrec_message.status = "' . $y . '"';
            }
        }
    }
    if ($where_status != '') {
        $where_status .= ')';
        array_push($where, $where_status);
    }
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id', 'position_name', db_backer() . 'sitter_available_times.name as dpm_name', 'walkplace', 'walk_time_from', 'walk_time_to', 'from_date', 'to_date', 'ages_to', 'ages_from', 'height', 'weight', 'sitter_description', 'reason_appointment', 'approver', 'gender', 'experience', 'literacy']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'pet_name') {

            $name = '<a href="' . admin_url('appointment/appointment_message/' . $aRow['id']) . '" onclick="init_appointment_message(' . $aRow['id'] . '); return false;">' . $aRow['pet_name'] . '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('appointment/appointment_message/' . $aRow['id']) . '" onclick="init_appointment_message(' . $aRow['id'] . '); return false;">' . _l('view') . '</a>';

            if (has_permission('appointment', '', 'edit') || is_admin()) {
                $name .= ' | <a href="#" onclick=' . '"' . 'edit_message(this,' . $aRow['id'] . '); return false;' . '"' . ' data-pet_name="' . $aRow['pet_name'] . '" data-position="' . $aRow['position'] . '" data-form_work="' . $aRow['form_work'] . '" data-sitter_available_time="' . $aRow['sitter_available_time'] . '" data-amount_recruiment="' . $aRow['amount_recruiment'] . '" data-walkplace="' . $aRow['walkplace'] . '" data-walk_time_from="' . app_format_money($aRow['walk_time_from'], '') . '" data-walk_time_to="' . app_format_money($aRow['walk_time_to'], '') . '" data-from_date="' . _d($aRow['from_date']) . '" data-to_date="' . _d($aRow['to_date']) . '" data-ages_to="' . $aRow['ages_to'] . '" data-ages_from="' . $aRow['ages_from'] . '" data-height="' . $aRow['height'] . '" data-weight="' . $aRow['weight'] . '" data-reason_appointment="' . $aRow['reason_appointment'] . '" data-approver="' . $aRow['approver'] . '" data-gender="' . $aRow['gender'] . '" data-literacy="' . $aRow['literacy'] . '" data-experience="' . $aRow['experience'] . '"  >' . _l('edit') . '</a>';
            }

            if (has_permission('appointment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('appointment/delete_appointment_message/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        } elseif ($aColumns[$i] == 'form_work') {
            $_data = _l($aRow['form_work']);
        } elseif ($aColumns[$i] == 'position') {
            $_data = $aRow['position_name'];
        } elseif ($aColumns[$i] == 'sitter_available_time') {
            $_data = $aRow['dpm_name'];
        } elseif ($aColumns[$i] == 'status') {
            if ($aRow['status'] == 1) {
                $_data = ' <span class="label label inline-block project-status-' . $aRow['status'] . ' message-style"> ' . _l('message') . ' </span>';
            } elseif ($aRow['status'] == 2) {
                $_data = ' <span class="label label inline-block project-status-' . $aRow['status'] . ' approved-style"> ' . _l('approved') . ' </span>';
            } elseif ($aRow['status'] == 3) {
                $_data = ' <span class="label label inline-block project-status-' . $aRow['status'] . ' made_finish-style"> ' . _l('made_finish') . ' </span>';
            } elseif ($aRow['status'] == 4) {
                $_data = ' <span class="label label inline-block project-status-' . $aRow['status'] . ' reject-style"> ' . _l('reject') . ' </span>';
            }
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
