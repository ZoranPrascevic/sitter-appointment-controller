<?php
defined('BASEPATH') or exit('No direct script access allowed');


/**
 * Check whether column exists in a table
 * Custom function because Codeigniter is caching the tables and this is causing issues in migrations
 * @param  string $column column name to check
 * @param  string $table table name to check
 * @return boolean
 */
function handle_rec_message_file($id)
{

    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = appointment_MODULE_UPLOAD_FOLDER . '/message/' . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the walker uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = &get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_message', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * reformat currency rec
 * @param  string $value
 * @return string
 */
function reformat_currency_rec($value)
{
    return str_replace(',', '', $value);
}

/**
 * get rec dpm name
 * @param  int $id
 * @return string
 */
function get_rec_dpm_name($id)
{
    $CI           = &get_instance();
    if ($id != 0) {
        $CI->db->where('sitter_available_timeid', $id);
        $dpm = $CI->db->get(db_backer() . 'sitter_available_times')->row();
        if ($dpm->name) {
            return $dpm->name;
        } else {
            return '';
        }
    } else {
        return '';
    }
}

/**
 * get rec position name
 * @param  int $id
 * @return string
 */
function get_rec_position_name($id)
{
    $CI           = &get_instance();
    if ($id != 0) {
        $CI->db->where('position_id', $id);
        $dpm = $CI->db->get(db_backer() . 'rec_sitterposition')->row();
        if ($dpm->position_name) {
            return $dpm->position_name;
        } else {
            return '';
        }
    } else {
        return '';
    }
}

/**
 * handle rec campaign file
 * @param  int $id 
 * @return bool
 */
function handle_rec_campaign_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = appointment_MODULE_UPLOAD_FOLDER . '/campaign/' . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the walker uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = &get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_campaign', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * handle rec pet_after file
 * @param  int $id
 * @return bool
 */
function handle_rec_pet_after_file($id)
{
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = appointment_MODULE_UPLOAD_FOLDER . '/pet_after/files/' . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the walker uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = &get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['file']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_file', $attachment);

                return true;
            }
        }
    }
    return false;
}

/**
 * handle rec pet_after avar file
 * @param  int $id
 * @return bool   
 */
function handle_rec_pet_after_avar_file($id)
{

    if (isset($_FILES['cd_avar']['name']) && $_FILES['cd_avar']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = appointment_MODULE_UPLOAD_FOLDER . '/pet_after/avartar/' . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['cd_avar']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['cd_avar']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the walker uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = &get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['cd_avar']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_cadidate_avar', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * get rec campaign hp
 * @param  string $id
 * @return string
 */
function get_rec_campaign_hp($id = '')
{
    $CI           = &get_instance();
    if ($id != '') {
        $CI->db->where('cp_id', $id);
        return $CI->db->get(db_backer() . 'rec_campaign')->row();
    } elseif ($id == '') {
        return $CI->db->get(db_backer() . 'rec_campaign')->result_array();
    }
}

/**
 * get status pet_after
 * @param  int $status
 * @return string
 */
function get_status_pet_after($status)
{
    $result = '';
    if ($status == 1) {
        $result = '<span class="label label inline-block project-status-' . $status . ' application-style"> ' . _l('application') . ' </span>';
    } elseif ($status == 2) {
        $result = '<span class="label label inline-block project-status-' . $status . ' potential-style"> ' . _l('potential') . ' </span>';
    } elseif ($status == 3) {
        $result = '<span class="label label inline-block project-status-' . $status . ' walking-style"> ' . _l('walking') . ' </span>';
    } elseif ($status == 4) {
        $result = '<span class="label label inline-block project-status-' . $status . ' won_walking-style"> ' . _l('won_walking') . ' </span>';
    } elseif ($status == 5) {
        $result = '<span class="label label inline-block project-status-' . $status . ' send_offer-style"> ' . _l('send_offer') . ' </span>';
    } elseif ($status == 6) {
        $result = '<span class="label label inline-block project-status-' . $status . ' elect-style"> ' . _l('elect') . ' </span>';
    } elseif ($status == 7) {
        $result = '<span class="label label inline-block project-status-' . $status . ' non_elect-style"> ' . _l('non_elect') . ' </span>';
    } elseif ($status == 8) {
        $result = '<span class="label label inline-block project-status-' . $status . ' unanswer-style"> ' . _l('unanswer') . ' </span>';
    } elseif ($status == 9) {
        $result = '<span class="label label inline-block project-status-' . $status . ' transferred-style"> ' . _l('transferred') . ' </span>';
    } elseif ($status == 10) {
        $result = '<span class="label label inline-block project-status-' . $status . ' freedom-style"> ' . _l('freedom') . ' </span>';
    }

    return $result;
}

/**
 * pet_after profile image
 * @param  int $id     
 * @param  array  $classes
 * @param  string $type   
 * @param  array  $img_attrs
 * @return string
 */
function pet_after_profile_image($id, $classes = ['staff-profile-image'], $type = 'small', $img_attrs = [])
{
    $CI           = &get_instance();
    $url = base_url('assets/images/user-placeholder.jpg');
    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . html_escape($val) . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    $CI->db->where('rel_id', $id);
    $CI->db->where('rel_type', 'rec_cadidate_avar');
    $result = $CI->db->get(db_backer() . 'files')->row();

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && $result->file_name !== null) {
        $profileImagePath = appointment_PATH . 'pet_after/avartar/' . $id . '/' . $result->file_name;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . site_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';
    }

    return $profile_image;
}

/**
 * get pet_after name
 * @param  int $id
 * @return string
 */
function get_pet_after_name($id)
{
    $CI           = &get_instance();
    $CI->db->where('id', $id);
    $pet_after = $CI->db->get(db_backer() . 'rec_pet_after')->row();
    if ($pet_after && $pet_after->pet_after_name != '') {
        return $pet_after->pet_after_name;
    } else {
        return '';
    }
}

/**
 * get pet_after walking
 * @param  int $id
 * @return 
 */
function get_pet_after_walking($id)
{
    $CI           = &get_instance();
    $CI->db->where('walking', $id);
    $data_rs = array();
    $cdwalking = $CI->db->get(db_backer() . 'cd_walking')->result_array();

    foreach ($cdwalking as $cd) {
        $data_rs[] = $cd['pet_after'];
    }

    return $data_rs;
}

/**
 * count criteria
 * @param  int $id
 * @return int
 */
function count_criteria($id)
{
    $CI           = &get_instance();
    $CI->db->where('evaluation_form', $id);
    $list = $CI->db->get(db_backer() . 'rec_list_criteria')->result_array();

    return count($list);
}

/**
 * get criteria name
 * @param  int $id
 * @return string
 */
function get_criteria_name($id)
{
    $CI           = &get_instance();
    $CI->db->where('criteria_id', $id);
    $CI->db->select('criteria_title');
    $list = $CI->db->get(db_backer() . 'rec_criteria')->row();
    if ($list->criteria_title) {
        return $list->criteria_title;
    } else {
        return '';
    }
}

/**
 * handle rec set transfer record
 * @param  int $id
 * @return bool
 */
function handle_rec_set_transfer_record($id)
{

    if (isset($_FILES['attachment']['name']) && $_FILES['attachment']['name'] != '') {

        hooks()->do_action('before_upload_contract_attachment', $id);
        $path = appointment_MODULE_UPLOAD_FOLDER . '/set_transfer/' . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['attachment']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['attachment']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the walker uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI           = &get_instance();
                $attachment   = [];
                $attachment[] = [
                    'file_name' => $filename,
                    'filetype'  => $_FILES['attachment']['type'],
                ];
                $CI->misc_model->add_attachment_to_database($id, 'rec_set_transfer', $attachment);

                return true;
            }
        }
    }

    return false;
}

/**
 * Gets the staff email by identifier.
 *
 * @param      int   $id     The identifier
 *
 * @return     String  The staff email by identifier.
 */
function get_staff_email_by_id_rec($id)
{
    $CI           = &get_instance();
    $CI->db->where('staffid', $id);
    $staff = $CI->db->select('email')->from(db_backer() . 'staff')->get()->row();

    return ($staff ? $staff->email : '');
}
