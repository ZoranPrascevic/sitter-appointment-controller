<?php
defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'r_form_name',
    db_backer() . 'rec_campaign.campaign_name as rec_campaign_name',
    'form_type',
    'lead_status',

];
$sIndexColumn = 'id';
$sTable       = db_backer() . 'rec_campaign_form_web';
$join         = ['LEFT JOIN ' . db_backer() . 'rec_campaign ON ' . db_backer() . 'rec_campaign.cp_id = ' . db_backer() . 'rec_campaign_form_web.rec_campaign_id'];
$where = [];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == 'r_form_name') {

            $name = '<a href="' . admin_url('appointment/add_edit_appointment_channel/' . $aRow['id']) . '" onclick="init_appointment_channel(' . $aRow['id'] . '); return false;">' . $aRow['r_form_name'] . '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('appointment/appointment_campaign/' . $aRow['id']) . '" onclick="init_appointment_channel(' . $aRow['id'] . '); return false;">' . _l('view') . '</a>';

            if (has_permission('appointment', '', 'edit') || is_admin()) {
                $name .= ' | <a href="' . admin_url('appointment/add_edit_appointment_channel/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }
            if (has_permission('appointment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('appointment/delete_appointment_channel/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        } elseif ($aColumns[$i] == 'tblrec_campaign.campaign_name as rec_campaign_name') {
            $_data = $aRow['rec_campaign_name'];
        } elseif ($aColumns[$i] == 'form_type') {
            if ($aRow['form_type'] == '1') {
                $_data = _l('form');
            } else {
                $_data = '';
            }
        } elseif ($aColumns[$i] == 'lead_status') {
            $arr_status = [];
            $arr_status['1'] = _l('application');
            $arr_status['2'] = _l('potential');
            $arr_status['3'] = _l('walking');
            $arr_status['4'] = _l('won_walking');
            $arr_status['5'] = _l('send_offer');
            $arr_status['6'] = _l('elect');
            $arr_status['7'] = _l('non_elect');
            $arr_status['8'] = _l('unanswer');
            $arr_status['9'] = _l('transferred');
            $arr_status['10'] = _l('preliminary_selection');

            $_data = ($arr_status[$aRow['lead_status']]);
        }
        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
