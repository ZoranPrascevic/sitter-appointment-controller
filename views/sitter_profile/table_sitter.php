<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'pet_after_code',
    'pet_after_name',
    'rate',
    'status',
    'email',
    'phonenumber',
    'birthday',
    'rec_campaign',
];
$sIndexColumn = 'id';
$sTable       = db_backer() . 'rec_pet_after';
$join         = [];
$where = [];



$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == 'pet_after_name') {
            $name = '<a href="' . admin_url('appointment/pet_after/' . $aRow['id']) . '">' . pet_after_profile_image($aRow['id'], [
                'staff-profile-image-small mright5',
            ], 'small') . '</a>';

            $name .= '<a href="' . admin_url('appointment/pet_after/' . $aRow['id']) . '" >' . $aRow['pet_after_name'] . '</a>';

            $name .= '<div class="row-options">';

            $name .= '<a href="' . admin_url('appointment/pet_after/' . $aRow['id']) . '" >' . _l('view') . '</a>';

            if (has_permission('appointment', '', 'edit') || is_admin()) {
                $name .= ' | <a href="' . admin_url('appointment/pet_afters/' . $aRow['id']) . '" >' . _l('edit') . '</a>';
            }

            if (has_permission('appointment', '', 'delete') || is_admin()) {
                $name .= ' | <a href="' . admin_url('appointment/delete_pet_after/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $name .= '</div>';

            $_data = $name;
        } elseif ($aColumns[$i] == 'birthday') {
            $_data = _d($aRow['birthday']);
        } elseif ($aColumns[$i] == 'rec_campaign') {
            $cp = get_rec_campaign_hp($aRow['rec_campaign']);
            if (isset($cp)) {
                $_data = $cp->campaign_code . ' - ' . $cp->campaign_name;
            } else {
                $_data = '';
            }
        } elseif ($aColumns[$i] == 'rate') {
            if (has_permission('appointment', '', 'edit') || is_admin()) {
                if ($aRow['status'] == 6) {
                    $_data = '<a href="' . admin_url('appointment/transfer_to_hr/' . $aRow['id']) . '" class="btn btn-success" >' . _l('tranfer_personnels') . '</a>';
                } else {
                    $_data = '';
                }
            } else {
                $_data = '';
            }
        } elseif ($aColumns[$i] == 'status') {
            $_data = get_status_pet_after($aRow['status']);
        }

        $row[] = $_data;
    }
    $output['aaData'][] = $row;
}
