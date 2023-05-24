<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * appointment Controller
 */
class appointment extends AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('appointment_model');
	}

	/**
	 * setting
	 * @return view
	 */
	public function setting()
	{
		if (!has_permission('appointment', '', 'edit') && !is_admin()) {
			access_denied('appointment');
		}
		$data['group'] = $this->input->get('group');

		$data['title'] = _l('setting');
		$data['tab'][] = 'sitterposition';
		$data['tab'][] = 'evaluation_criteria';
		$data['tab'][] = 'evaluation_form';
		$data['tab'][] = 'tranfer_personnel';
		if ($data['group'] == '') {
			$data['group'] = 'sitterposition';
		}
		$data['tabs']['view'] = 'includes/' . $data['group'];

		$data['positions'] = $this->appointment_model->get_sitterposition();

		$data['list_group'] = $this->appointment_model->get_group_evaluation_criteria();

		$data['group_criterias'] = $this->appointment_model->get_list_child_criteria();

		$data['list_form'] = $this->appointment_model->get_list_evaluation_form();

		$data['list_set_tran'] = $this->appointment_model->get_list_set_transfer();

		$this->load->view('manage_setting', $data);
	}

	/**
	 * job position
	 * @return redirect
	 */
	public function sitterposition()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();
			if (!$this->input->post('id')) {
				$id = $this->appointment_model->add_sitterposition($data);
				if ($id) {
					$success = true;
					$message = _l('added_successfully', _l('sitterposition'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=sitterposition'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_sitterposition($data, $id);
				if ($success) {
					$message = _l('updated_successfully', _l('sitterposition'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=sitterposition'));
			}
			die;
		}
	}

	/**
	 * delete sitterposition
	 * @param  integer $id
	 * @return redirect
	 */
	public function delete_sitterposition($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/setting?group=sitterposition'));
		}
		$response = $this->appointment_model->delete_sitterposition($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('sitterposition')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('sitterposition')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('sitterposition')));
		}
		redirect(admin_url('appointment/setting?group=sitterposition'));
	}

	/**
	 * appointmentmessage
	 * @param  string $id 
	 * @return view
	 */
	public function appointment_message($id = '')
	{
		$this->load->model('sitter_available_times_model');
		$this->load->model('staff_model');

		$data['sitter_available_times'] = $this->sitter_available_times_model->get();
		$data['positions'] = $this->appointment_model->get_sitterposition();
		$data['staffs'] = $this->staff_model->get();
		$data['message_id'] = $id;

		$data['title'] = _l('appointment_message');
		$this->load->view('appointment_message', $data);
	}

	/**
	 * message
	 * @return redirect
	 */
	public function message()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();
			$data = $this->input->post();
			$data['sitter_description'] = $this->input->post('sitter_description', false);
			if ($this->input->post('no_editor')) {
				$data['sitter_description'] = nl2br(clear_textarea_breaks($this->input->post('sitter_description')));
			}
			if (!$this->input->post('id')) {
				$id = $this->appointment_model->add_appointment_message($data);
				if ($id) {
					handle_rec_message_file($id);
					$success = true;
					$message = _l('added_successfully', _l('appointment_message'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_message'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_appointment_message($data, $id);
				handle_rec_message_file($id);
				if ($success) {
					$message = _l('updated_successfully', _l('appointment_message'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_message'));
			}
			die;
		}
	}

	/**
	 * delete appointment message
	 * @param  integer $id
	 * @return redirect
	 */
	public function delete_appointment_message($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/appointment_message'));
		}
		$response = $this->appointment_model->delete_appointment_message($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('appointment_message')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('appointment_message')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('appointment_message')));
		}
		redirect(admin_url('appointment/appointment_message'));
	}

	/**
	 * table message
	 * @return
	 */
	public function table_message()
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('appointment', 'table_message'));
		}
	}

	/**
	 * table campaign
	 * @return
	 */
	public function table_campaign()
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('appointment', 'appointment_campaign/table_campaign'));
		}
	}

	/**
	 * get message data ajax
	 * @param  integer $id
	 * @return view
	 */
	public function get_message_data_ajax($id)
	{

		$data['id'] = $id;
		$data['messages'] = $this->appointment_model->get_rec_message($id);
		$data['message_file'] = $this->appointment_model->get_message_file($id);

		$this->load->view('message_preview', $data);
	}

	/**
	 * delete message attachment
	 * @param  int $id
	 * @return
	 */
	public function delete_message_attachment($id)
	{
		$this->load->model('misc_model');
		$file = $this->misc_model->get_file($id);
		if ($file->staffid == get_staff_user_id() || is_admin()) {
			echo html_entity_decode($this->appointment_model->delete_message_attachment($id));
		} else {
			header('HTTP/1.0 400 Bad error');
			echo _l('access_denied');
			die;
		}
	}

	/**
	 * file
	 * @param  int $id
	 * @param  int $rel_id
	 * @return view
	 */
	public function file($id, $rel_id)
	{
		$data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
		$data['current_user_is_admin'] = is_admin();
		$data['file'] = $this->appointment_model->get_file($id, $rel_id);
		if (!$data['file']) {
			header('HTTP/1.0 404 Not Found');
			die;
		}
		$this->load->view('_file', $data);
	}

	/**
	 * approve reject message
	 * @param  int $type
	 * @param  int $id
	 * @return redirect
	 */
	public function approve_reject_message($type, $id)
	{
		$result = $this->appointment_model->approve_reject_message($type, $id);
		if ($result == 'approved') {
			set_alert('success', _l('approved') . ' ' . _l('appointment_message') . ' ' . _l('successfully'));
		} elseif ($result == 'reject') {
			set_alert('success', _l('reject') . ' ' . _l('appointment_message') . ' ' . _l('successfully'));
		} else {
			set_alert('warning', _l('action') . ' ' . _l('fail'));
		}
		redirect(admin_url('appointment/appointment_message#' . $id));
	}

	/**
	 * appointment campaign
	 * @param  int $id
	 * @return view
	 */
	public function appointment_campaign($id = '')
	{
		$this->load->model('sitter_available_times_model');
		$this->load->model('staff_model');

		$data['rec_message'] = $this->appointment_model->get_rec_message_by_status(2);
		$data['sitter_available_times'] = $this->sitter_available_times_model->get();
		$data['positions'] = $this->appointment_model->get_sitterposition();
		$data['staffs'] = $this->staff_model->get();
		$data['campaign_id'] = $id;

		$data['title'] = _l('appointment_campaign');
		$this->load->view('appointment_campaign/appointment_campaign', $data);
	}

	/**
	 * campaign
	 * @return redirect
	 */
	public function campaign()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();
			$data = $this->input->post();
			$data['cp_sitter_description'] = $this->input->post('cp_sitter_description', false);
			if ($this->input->post('no_editor')) {
				$data['cp_sitter_description'] = nl2br(clear_textarea_breaks($this->input->post('cp_sitter_description')));
			}
			if (!$this->input->post('cp_id')) {
				$id = $this->appointment_model->add_appointment_campaign($data);
				if ($id) {
					handle_rec_campaign_file($id);
					$success = true;
					$message = _l('added_successfully', _l('appointment_campaign'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_campaign'));
			} else {
				$id = $data['cp_id'];
				unset($data['cp_id']);
				$success = $this->appointment_model->update_appointment_campaign($data, $id);
				handle_rec_campaign_file($id);
				if ($success) {
					$message = _l('updated_successfully', _l('appointment_campaign'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_campaign'));
			}
			die;
		}
	}

	/**
	 * delete appointment campaign
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_appointment_campaign($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/appointment_campaign'));
		}
		$response = $this->appointment_model->delete_appointment_campaign($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('appointment_campaign')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('appointment_campaign')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('appointment_campaign')));
		}
		redirect(admin_url('appointment/appointment_campaign'));
	}

	/**
	 * campaign code exists
	 * @return
	 */
	public function campaign_code_exists()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post()) {
				// First we need to check if the email is the same
				$cp_id = $this->input->post('cp_id');
				if ($cp_id != '') {
					$this->db->where('cp_id', $cp_id);
					$campaign = $this->db->get('tblrec_campaign')->row();
					if ($campaign->campaign_code == $this->input->post('campaign_code')) {
						echo json_encode(true);
						die();
					}
				}
				$this->db->where('campaign_code', $this->input->post('campaign_code'));
				$total_rows = $this->db->count_all_results('tblrec_campaign    ');
				if ($total_rows > 0) {
					echo json_encode(false);
				} else {
					echo json_encode(true);
				}
				die();
			}
		}
	}

	/**
	 * get campaign data ajax
	 * @param  int $id
	 * @return view
	 */
	public function get_campaign_data_ajax($id)
	{
		$data['id'] = $id;
		$data['campaigns'] = $this->appointment_model->get_rec_campaign($id);
		$data['campaign_file'] = $this->appointment_model->get_campaign_file($id);
		$this->load->view('appointment_campaign/campaign_preview', $data);
	}

	/**
	 * campaign file
	 * @param  int $id
	 * @param  int $rel_id
	 * @return
	 */
	public function campaign_file($id, $rel_id)
	{
		$data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
		$data['current_user_is_admin'] = is_admin();
		$data['file'] = $this->appointment_model->get_file($id, $rel_id);
		if (!$data['file']) {
			header('HTTP/1.0 404 Not Found');
			die;
		}
		$this->load->view('appointment_campaign/_file', $data);
	}

	/**
	 * delete campaign attachment
	 * @param  int $id
	 * @return
	 */
	public function delete_campaign_attachment($id)
	{
		$this->load->model('misc_model');
		$file = $this->misc_model->get_file($id);
		if ($file->staffid == get_staff_user_id() || is_admin()) {
			echo html_entity_decode($this->appointment_model->delete_campaign_attachment($id));
		} else {
			header('HTTP/1.0 400 Bad error');
			echo _l('access_denied');
			die;
		}
	}

	/**
	 * pet_after profile
	 * @return view
	 */
	public function pet_after_profile()
	{

		$data['pet_afters'] = $this->appointment_model->get_pet_afters();
		$data['title'] = _l('pet_after_profile');
		$this->load->view('pet_after_profile/pet_after_profile', $data);
	}

	/**
	 * pet_afters
	 * @param  int $id
	 * @return
	 */
	public function pet_afters($id = '')
	{
		if ($id != '') {

			$data['pet_after'] = $this->appointment_model->get_pet_afters($id);

			$data['title'] = $data['pet_after']->pet_after_name;
		} else {

			$data['title'] = _l('new_pet_after');
		}

		$data['rec_campaigns'] = $this->appointment_model->get_rec_campaign();

		$this->load->view('pet_after_profile/pet_after', $data);
	}

	/**
	 * add update pet_after
	 * @param int $id
	 */
	public function add_update_pet_after($id = '')
	{

		$data = $this->input->post();
		if ($data) {
			if ($id == '') {
				$ids = $this->appointment_model->add_pet_after($data);
				if ($ids) {
					handle_rec_pet_after_file($ids);
					handle_rec_pet_after_avar_file($ids);
					$success = true;
					$message = _l('added_successfully', _l('pet_after_profile'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/pet_after_profile'));
			} else {
				$success = $this->appointment_model->update_cadidate($data, $id);
				if ($success == true) {
					handle_rec_pet_after_file($id);
					handle_rec_pet_after_avar_file($id);
					$message = _l('updated_successfully', _l('pet_after_profile'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/pet_after_profile'));
			}
		}
	}

	/**
	 * table pet_afters
	 * @return
	 */
	public function table_pet_afters()
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('appointment', 'pet_after_profile/table_pet_afters'));
		}
	}

	/**
	 * change status campaign
	 * @param  int $status
	 * @param  int $cp_id
	 * @return
	 */
	public function change_status_campaign($status, $cp_id)
	{
		$change = $this->appointment_model->change_status_campaign($status, $cp_id);
		if ($change == true) {

			$message = _l('change_status_campaign') . ' ' . _l('successfully');
			echo json_encode([
				'result' => $message,
			]);
		} else {
			$message = _l('change_status_campaign') . ' ' . _l('fail');
			echo json_encode([
				'result' => $message,
			]);
		}
	}

	/**
	 * pet_after code exists
	 * @return
	 */
	public function pet_after_code_exists()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post()) {
				// First we need to check if the email is the same
				$pet_after = $this->input->post('pet_after');
				if ($pet_after != '') {
					$this->db->where('id', $pet_after);
					$cd = $this->db->get('tblrec_pet_after')->row();
					if ($cd->pet_after_code == $this->input->post('pet_after_code')) {
						echo json_encode(true);
						die();
					}
				}
				$this->db->where('pet_after_code', $this->input->post('pet_after_code'));
				$total_rows = $this->db->count_all_results('tblrec_pet_after');
				if ($total_rows > 0) {
					echo json_encode(false);
				} else {
					echo json_encode(true);
				}
				die();
			}
		}
	}

	/**
	 * pet_after email exists
	 * @return
	 */
	public function pet_after_email_exists()
	{
		if ($this->input->is_ajax_request()) {
			if ($this->input->post()) {
				// First we need to check if the email is the same
				$pet_after = $this->input->post('pet_after');
				if ($pet_after != '') {
					$this->db->where('id', $pet_after);
					$_current_email = $this->db->get(db_backer() . 'rec_pet_after')->row();
					if ($_current_email->email == $this->input->post('email')) {
						echo json_encode(true);
						die();
					}
				}
				$this->db->where('email', $this->input->post('email'));
				$total_rows = $this->db->count_all_results(db_backer() . 'rec_pet_after');
				if ($total_rows > 0) {
					echo json_encode(false);
				} else {
					echo json_encode(true);
				}
				die();
			}
		}
	}

	/**
	 * walking schedule
	 * @param  int $id
	 * @return view
	 */
	public function walking_schedule($id = '')
	{
		$data['staffs'] = $this->staff_model->get();
		$data['pet_afters'] = $this->appointment_model->get_pet_afters();
		$data['list_cd'] = $this->appointment_model->get_list_cd();
		$data['rec_campaigns'] = $this->appointment_model->get_rec_campaign();
		$data['walking_id'] = $id;
		$data['title'] = _l('walking_schedule');
		$this->load->view('walking_schedule/walking_schedule', $data);
	}

	/**
	 * get pet_after infor change
	 * @param  object $pet_after
	 * @return json
	 */
	public function get_pet_after_infor_change($pet_after)
	{
		$infor = $this->appointment_model->get_pet_afters($pet_after);
		echo json_encode([
			'email' => $infor->email,
			'phonenumber' => $infor->phonenumber,

		]);
	}

	/**
	 * walking schedules
	 * @return redirect
	 */
	public function walking_schedules()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();

			if (!$this->input->post('id')) {

				$id = $this->appointment_model->add_walking_schedules($data);
				if ($id) {
					$message = _l('added_successfully', _l('walking_schedule'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/walking_schedule'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_walking_schedules($data, $id);

				if ($success) {
					$message = _l('updated_successfully', _l('walking_schedule'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/walking_schedule'));
			}
			die;
		}
	}

	/**
	 * deletepet_after
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_pet_after($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/pet_after_profile'));
		}
		$response = $this->appointment_model->delete_pet_after($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('pet_after')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('pet_after')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('pet_after')));
		}
		redirect(admin_url('appointment/pet_after_profile'));
	}

	/**
	 * table walking
	 * @return
	 */
	public function table_walking()
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('appointment', 'walking_schedule/table_walking'));
		}
	}

	/**
	 * pet_after
	 * @param  int $id
	 * @return view
	 */
	public function pet_after($id)
	{
		$data['title'] = _l('pet_after_detail');

		$data['pet_after'] = $this->appointment_model->get_pet_afters($id);
		if ($data['pet_after']->rec_campaign > 0) {
			$campaign = $this->appointment_model->get_rec_campaign($data['pet_after']->rec_campaign);
			$data['evaluation'] = $this->appointment_model->get_evaluation_form_by_position($campaign->cp_position);
		} else {
			$data['evaluation'] = '';
		}

		$data['list_walking'] = $this->appointment_model->get_walking_by_pet_after($id);
		$data['cd_evaluation'] = $this->appointment_model->get_cd_evaluation($id);
		$data['assessor'] = '';
		$data['feedback'] = '';
		$data['evaluation_date'] = '';
		$data['avg_score'] = 0;
		$data['data_group'] = [];
		$rs_evaluation = [];
		if (count($data['cd_evaluation']) > 0) {
			$data['assessor'] = $data['cd_evaluation'][0]['assessor'];
			$data['feedback'] = $data['cd_evaluation'][0]['feedback'];
			$data['evaluation_date'] = $data['cd_evaluation'][0]['evaluation_date'];

			foreach ($data['cd_evaluation'] as $eval) {
				$data['avg_score'] += ($eval['rate_score'] * $eval['percent']) / 100;

				if (!isset($rs_evaluation[$eval['group_criteria']])) {
					$rs_evaluation[$eval['group_criteria']]['toltal_percent'] = 0;
					$rs_evaluation[$eval['group_criteria']]['result'] = 0;
				}
				$rs_evaluation[$eval['group_criteria']]['toltal_percent'] += $eval['percent'];
				$rs_evaluation[$eval['group_criteria']]['result'] += ($eval['rate_score'] * $eval['percent']) / 100;
			}

			$data['data_group'] = $rs_evaluation;
		}

		$this->load->view('pet_after_profile/pet_after_detail', $data);
	}

	/**
	 * pet_after file
	 * @param  int $id
	 * @param  int $rel_id
	 * @return view
	 */
	public function pet_after_file($id, $rel_id)
	{
		$data['discussion_user_profile_image_url'] = staff_profile_image_url(get_staff_user_id());
		$data['current_user_is_admin'] = is_admin();
		$data['file'] = $this->appointment_model->get_file($id, $rel_id);
		if (!$data['file']) {
			header('HTTP/1.0 404 Not Found');
			die;
		}
		$this->load->view('pet_after_profile/_file', $data);
	}

	/**
	 * deletec andidate attachment
	 * @param  int $id
	 * @return
	 */
	public function delete_pet_after_attachment($id)
	{
		$this->load->model('misc_model');
		$file = $this->misc_model->get_file($id);
		if ($file->staffid == get_staff_user_id() || is_admin()) {
			echo html_entity_decode($this->appointment_model->delete_pet_after_attachment($id));
		} else {
			header('HTTP/1.0 400 Bad error');
			echo _l('access_denied');
			die;
		}
	}

	/**
	 * care pet_after
	 * @return json
	 */
	public function care_pet_after()
	{
		if ($this->input->post()) {
			$data = $this->input->post();

			$id = $this->appointment_model->add_care_pet_after($data);
			if ($id) {
				$mess = _l('care_pet_after_success');
				echo json_encode([
					'mess' => $mess,
				]);
			} else {
				$mess = _l('care_pet_after_fail');
				echo json_encode([
					'mess' => $mess,
				]);
			}
		}
	}

	/**
	 * rating pet_after
	 * @return json
	 */
	public function rating_pet_after()
	{
		if ($this->input->post()) {
			$data = $this->input->post();

			$id = $this->appointment_model->rating_pet_after($data);
			if ($id == true) {
				$mess = _l('rating_pet_after_success');
				echo json_encode([
					'mess' => $mess,
					'rate' => $data['rating'],
				]);
			} else {
				$mess = _l('rating_pet_after_fail');
				echo json_encode([
					'mess' => $mess,
					'rate' => 0,
				]);
			}
		}
	}

	/**
	 * send mail pet_after
	 * @return redirect
	 */
	public function send_mail_pet_after()
	{
		if ($this->input->post()) {
			$data = $this->input->post();
			$rs = $this->appointment_model->send_mail_pet_after($data);
			if ($rs == true) {
				set_alert('success', _l('send_mail_successfully'));
			}
			redirect(admin_url('appointment/pet_after/' . $data['pet_after']));
		}
	}

	/**
	 * send mail list pet_after
	 * @return redirect
	 */
	public function send_mail_list_pet_after()
	{
		if ($this->input->post()) {
			$data = $this->input->post();
			foreach ($data['pet_after'] as $cd) {
				$cdate = $this->appointment_model->get_pet_afters($cd);
				$data['email'][] = $cdate->email;
			}
			$rs = $this->appointment_model->send_mail_list_pet_after($data);
			if ($rs == true) {
				set_alert('success', _l('send_mail_successfully'));
			}
			redirect(admin_url('appointment/pet_after_profile'));
		}
	}

	/**
	 * check time walking
	 * @return json
	 */
	public function check_time_walking()
	{
		if ($this->input->post()) {
			$data = $this->input->post();
			if ($data['pet_after'] != '') {
				if ($data['walking_day'] == '' || $data['from_time'] == '' || $data['to_time'] == '') {
					$rs = _l('please_enter_the_full_walking_time');
					echo json_encode([
						'return' => true,
						'rs' => $rs,
					]);
				} elseif ($data['walking_day'] != '' && $data['from_time'] != '' && $data['to_time'] != '') {

					$check = $this->appointment_model->check_pet_after_walking($data);

					if (count($check) > 0) {
						$rs = _l('check_pet_after_walking_1');
						echo json_encode([
							'return' => true,
							'rs' => $rs,
						]);
					} else {
						echo json_encode([
							'return' => false,
						]);
					}
				}
			}
		}
	}

	/**
	 * [get_pet_after_edit_walking description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function get_pet_after_edit_walking($id)
	{
		$list_cd = $this->appointment_model->get_list_pet_afters_walking($id);
		$cd = $this->appointment_model->get_pet_afters();
		$html = '';
		$count = 0;
		foreach ($list_cd as $l) {
			if ($count == 0) {
				$class = 'success';
				$class_btn = 'new_pet_afters';
				$i = 'plus';
			} else {
				$class_btn = 'remove_pet_afters';
				$class = 'danger';
				$i = 'minus';
			}
			$html .= '<div class="row col-md-12" id="pet_afters-item">
                        <div class="col-md-4 form-group">
                          <select name="pet_after[' . $count . ']" onchange="pet_after_infor_change(this); return false;" id="pet_after[' . $count . ']" class="selectpicker"  data-live-search="true" data-width="100%" data-none-selected-text="' . _l('ticket_settings_none_assigned') . '" required>
                              <option value=""></option>';
			foreach ($cd as $s) {
				$attr = '';
				if ($s['id'] == $l['pet_after']) {
					$attr = 'selected';
				}
				$html .= '<option value="' . $s['id'] . '" ' . $attr . ' >' . $s['pet_after_code'] . ' ' . $s['pet_after_name'] . '</option>';
			}
			$html .= '</select>
                        </div>
                        <div class="col-md-4">
                          <input type="text" disabled="true" name="email[' . $count . ']" id="email[' . $count . ']" value="' . $l['email'] . '" class="form-control" />
                        </div>
                        <div class="col-md-3">
                          <input type="text" disabled="true" name="phonenumber[' . $count . ']" id="phonenumber[' . $count . ']" value="' . $l['phonenumber'] . '" class="form-control" />
                        </div>
                        <div class="col-md-1 lightheight-34-nowrap">
                              <span class="input-group-btn pull-bot">
                                  <button name="add" class="btn ' . $class_btn . ' btn-' . $class . ' border-radius-4" data-ticket="true" type="button"><i class="fa fa-' . $i . '"></i></button>
                              </span>
                        </div>
                      </div>';
			$count++;
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	/**
	 * delete walking schedule
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_walking_schedule($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/walking_schedule'));
		}
		$response = $this->appointment_model->delete_walking_schedule($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('walking_schedule')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('walking_schedule')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('walking_schedule')));
		}
		redirect(admin_url('appointment/walking_schedule'));
	}

	/**
	 * get walking data ajax
	 * @param  int $id
	 * @return view
	 */
	public function get_walking_data_ajax($id)
	{
		$data['id'] = $id;
		$data['intv_sch'] = $this->appointment_model->get_walking_schedule($id);
		$this->load->view('walking_schedule/intv_sch_preview', $data);
	}

	/**
	 * evaluation criteria
	 * @return redirect
	 */
	public function evaluation_criteria()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();
			if (!$this->input->post('id')) {
				$id = $this->appointment_model->add_evaluation_criteria($data);
				if ($id) {
					$success = true;
					$message = _l('added_successfully', _l('evaluation_criteria'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=evaluation_criteria'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_evaluation_criteria($data, $id);
				if ($success) {
					$message = _l('updated_successfully', _l('evaluation_criteria'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=evaluation_criteria'));
			}
			die;
		}
	}

	/**
	 * delete evaluation criteria
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_evaluation_criteria($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/setting?group=evaluation_criteria'));
		}
		$response = $this->appointment_model->delete_evaluation_criteria($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('evaluation_criteria')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('evaluation_criteria')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('evaluation_criteria')));
		}
		redirect(admin_url('appointment/setting?group=evaluation_criteria'));
	}

	/**
	 * get criteria by group
	 * @param  int $id
	 * @return json
	 */
	public function get_criteria_by_group($id)
	{
		$list = $this->appointment_model->get_criteria_by_group($id);
		$html = '<option value=""></option>';
		foreach ($list as $li) {
			$html .= '<option value="' . $li['criteria_id'] . '">' . $li['criteria_title'] . '</option>';
		}
		echo json_encode([
			'html' => $html,
		]);
	}

	/**
	 * evaluation form
	 * @return redirect
	 */
	public function evaluation_form()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();
			if (!$this->input->post('id')) {
				$id = $this->appointment_model->add_evaluation_form($data);
				if ($id) {
					$success = true;
					$message = _l('added_successfully', _l('evaluation_form'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=evaluation_form'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_evaluation_form($data, $id);
				if ($success) {
					$message = _l('updated_successfully', _l('evaluation_form'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=evaluation_form'));
			}
			die;
		}
	}

	/**
	 * delete evaluation form
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_evaluation_form($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/setting?group=evaluation_form'));
		}
		$response = $this->appointment_model->delete_evaluation_form($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('evaluation_form')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('evaluation_form')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('evaluation_form')));
		}
		redirect(admin_url('appointment/setting?group=evaluation_form'));
	}

	/**
	 * get list criteria edit
	 * @param  int $id
	 * @return json
	 */
	public function get_list_criteria_edit($id)
	{
		$list = $this->appointment_model->get_list_criteria_edit($id);
		echo json_encode([
			'html' => $list,
		]);
	}

	/**
	 * change status pet_after
	 * @param  int $status
	 * @param  int $id
	 * @return json
	 */
	public function change_status_pet_after($status, $id)
	{
		$change = $this->appointment_model->change_status_pet_after($status, $id);
		if ($change == true) {

			$message = _l('change_status_campaign') . ' ' . _l('successfully');
			echo json_encode([
				'result' => $message,
			]);
		} else {
			$message = _l('change_status_campaign') . ' ' . _l('fail');
			echo json_encode([
				'result' => $message,
			]);
		}
	}

	/**
	 * change send to
	 * @param  int $type
	 * @return json
	 */
	public function change_send_to($type)
	{
		$this->load->model('staff_model');
		$this->load->model('sitter_available_times_model');
		if ($type == 'staff') {
			$staff = $this->staff_model->get();
			echo json_encode([
				'type' => $type,
				'list' => $staff,
			]);
		} elseif ($type == 'sitter_available_time') {
			$dpm = $this->sitter_available_times_model->get();
			echo json_encode([
				'type' => $type,
				'list' => $dpm,
			]);
		}
	}

	/**
	 * setting tranfer
	 * @return redirect
	 */
	public function setting_tranfer()
	{
		if ($this->input->post()) {
			$message = '';
			$data = $this->input->post();

			$data['content'] = $this->input->post('content', false);
			if ($this->input->post('no_editor')) {
				$data['content'] = nl2br(clear_textarea_breaks($this->input->post('content')));
			}
			if (!$this->input->post('id')) {
				$id = $this->appointment_model->add_setting_tranfer($data);
				if ($id) {
					handle_rec_set_transfer_record($id);
					$success = true;
					$message = _l('added_successfully', _l('setting_tranfer'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=tranfer_personnel'));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$success = $this->appointment_model->update_setting_tranfer($data, $id);
				if ($success) {
					handle_rec_set_transfer_record($id);
					$message = _l('updated_successfully', _l('setting_tranfer'));
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/setting?group=tranfer_personnel'));
			}
			die;
		}
	}

	/**
	 * delete setting tranfer
	 * @param  int $id
	 * @return redirect
	 */
	public function delete_setting_tranfer($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/setting?group=tranfer_personnel'));
		}
		$response = $this->appointment_model->delete_setting_tranfer($id);
		if (is_array($response) && isset($response['referenced'])) {
			set_alert('warning', _l('is_referenced', _l('setting_tranfer')));
		} elseif ($response == true) {
			set_alert('success', _l('deleted', _l('setting_tranfer')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('setting_tranfer')));
		}
		redirect(admin_url('appointment/setting?group=tranfer_personnel'));
	}

	/**
	 * transfer to hr
	 * @param  int $pet_after
	 * @return view
	 */
	public function transfer_to_hr($pet_after)
	{
		$this->load->model('roles_model');
		$data['pet_after'] = $this->appointment_model->get_pet_afters($pet_after);
		$data['title'] = _l('tranfer_personnel');
		$data['roles'] = $this->roles_model->get();
		$this->load->view('pet_after_profile/transfer_to_hr', $data);
	}

	/**
	 * transfer hr
	 * @param  int $pet_after
	 * @return redirect
	 */
	public function transfer_hr($pet_after)
	{

		$this->load->model('hrm/hrm_model');

		if ($this->input->post()) {
			$data = $this->input->post();
			$id = $this->hrm_model->add_staff($data);
			if ($id) {
				$change = $this->appointment_model->change_status_pet_after(9, $pet_after);
				//handle_staff_profile_image_upload($id);
				if ($change == true) {
					set_alert('success', _l('added_successfully', _l('staff_member')));
				}

				redirect(admin_url('appointment/pet_after_profile'));
			}
		}
	}

	/**
	 * action transfer hr
	 * @param  int $pet_after
	 * @return json
	 */
	public function action_transfer_hr($pet_after)
	{
		$this->load->model('sitter_available_times_model');
		$this->load->model('staff_model');
		$cd = $this->appointment_model->get_pet_afters($pet_after);
		$step_setting = $this->appointment_model->get_step_transfer_setting();
		$step = [];
		foreach ($step_setting as $st) {
			$step['id'] = $st['set_id'];
			$step['subject'] = $st['subject'];
			$step['content'] = $st['content'];
			if ($st['send_to'] = 'pet_after') {
				$step['email'] = $cd->email;
				$action_step = $this->appointment_model->action_transfer_hr($step);
			}

			if ($st['send_to'] = 'staff') {
				$step['email'] = $st['email_to'];
				$action_step = $this->appointment_model->action_transfer_hr($step);
			}

			if ($st['send_to'] = 'sitter_available_time') {
				$dpm = [];
				if (strlen($st['email_to']) == 1) {
					$dpm[] = $st['email_to'];
				} else {
					$dpm[] = explode(',', $st['email_to']);
				}
				$list_mail = [];
				foreach ($dpm as $dp) {
					$dpment = $this->sitter_available_times_model->get($dp);
					if (isset($dpment->manager_id) && $dpment->manager_id != '') {
						$mng_dpm = $this->staff_model->get($dpment->manager_id);
						if ($mng_dpm != '') {
							$list_mail[] = $mng_dpm->email;
						} else {
							$list_mail[] = '';
						}
					}
				}
				$step['email'] = implode(',', $list_mail);
				$action_step = $this->appointment_model->action_transfer_hr($step);
			}
		}
		echo json_encode([
			'rs' => true,
		]);
	}

	/**
	 * dashboard
	 * @return view
	 */
	public function dashboard()
	{
		$data['title'] = _l('dashboard');

		$data['rec_campaign_chart_by_status'] = json_encode($this->appointment_model->rec_campaign_chart_by_status());
		$data['rec_plan_chart_by_status'] = json_encode($this->appointment_model->rec_plan_chart_by_status());
		$data['cp_count'] = $this->appointment_model->get_rec_dashboard_count();
		$data['upcoming_walking'] = $this->appointment_model->get_upcoming_walking();
		$this->load->view('dashboard', $data);
	}

	/**
	 * get appointment message edit
	 * @param  int $id
	 * @return
	 */
	public function get_appointment_message_edit($id)
	{
		$list = $this->appointment_model->get_rec_message($id);
		if (isset($list)) {
			$description = $list->sitter_description;
		} else {
			$description = '';
		}
		echo json_encode([
			'description' => $description,

		]);
	}

	/**
	 * get appointment campaign edit
	 * @param  int $id
	 * @return json
	 */
	public function get_appointment_campaign_edit($id)
	{
		$list = $this->appointment_model->get_rec_campaign($id);
		if (isset($list)) {
			$description = $list->cp_sitter_description;
		} else {
			$description = '';
		}
		echo json_encode([
			'description' => $description,

		]);
	}

	/**
	 * get tranfer personnel edit
	 * @param  int $id
	 * @return json
	 */
	public function get_tranfer_personnel_edit($id)
	{
		$list = $this->appointment_model->get_list_set_transfer($id);
		if (isset($list)) {
			$description = $list->content;
		} else {
			$description = '';
		}
		echo json_encode([
			'description' => $description,

		]);
	}

	/**
	 * appointment channel
	 * @param  int $id
	 * @return view
	 */
	public function appointment_channel($id = '')
	{
		if (!has_permission('appointment', '', 'view') && !is_admin()) {
			access_denied('_appointment_channel');
		}
		$data['rec_channel_id'] = $id;
		$data['pet_afters'] = $this->appointment_model->get_pet_afters();
		$data['title'] = _l('_appointment_channel');

		$this->load->view('appointment_channel/manage_appointment_channel', $data);
	}

	/**
	 * add edit appointment channel
	 * @param string $id [description]
	 */
	public function add_edit_appointment_channel($id = '')
	{

		if ($this->input->post()) {
			$data = $this->input->post();

			if (!isset($data['appointment_channel_id'])) {

				if (!has_permission('appointment', '', 'create') && !is_admin()) {
					access_denied('_appointment_channel');
				}

				$ids = $this->appointment_model->add_appointment_channel($data);
				if ($ids) {
					$message = _l('added_successfully');
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_channel'));
			} else {

				$id = $data['appointment_channel_id'];

				if (!has_permission('appointment', '', 'edit') && !is_admin()) {
					access_denied('_appointment_channel');
				}

				if (isset($data['appointment_channel_id'])) {
					unset($data['appointment_channel_id']);
				}

				$success = $this->appointment_model->update_appointment_channel($data, $id);
				if ($success == true) {
					$message = _l('updated_successfully');
					set_alert('success', $message);
				}
				redirect(admin_url('appointment/appointment_channel'));
			}
		}

		if ($id != '') {
			/*edit*/
			$data['form'] = $this->appointment_model->get_appointment_channel($id);
			$data['formData'] = $data['form']->form_data;
			$data['appointment_channel_id'] = $id;
		} else {
			/*add*/
			$data['title'] = _l('new_pet_after');
			$data['formData'] = '';
			$data['form'] = $this->appointment_model->get_form([
				'id' => 1,
			]);
		}

		$custom_fields = get_custom_fields('leads', 'type != "link"');
		$cfields = format_external_form_custom_fields($custom_fields);

		$data['languages'] = $this->app->get_available_languages();
		$data['cfields'] = $cfields;

		$data['members'] = $this->staff_model->get('', [
			'active' => 1,
			'is_not_staff' => 0,
		]);

		$db_fields = [];
		$fields = [
			'pet_after_name',
			'pet_after_code',
			'birthday',
			'gender',
			'desired_walk_time',
			'birthplace',
			'home_town',
			'identification',
			'place_of_issue',
			'marital_status',
			'nation',
			'religion',
			'height',
			'weight',
			'email',
			'phonenumber',
			'walker',
			'resident',
			'nationality',
			'zip',
			'introduce_yourself',
			'skype',
			'facebook',
			'current_accommodation',
			'position',
			'contact_walker',
			'walk_time',
			'reason_quitwork',
			'sitter_description',
			'diploma',
			'training_places',
			'specialized',
			'training_form',
			'days_for_identity',
			'year_experience',
		];
		$className = 'form-control';

		foreach ($fields as $f) {
			$_field_object = new stdClass();
			$type = 'text';
			$subtype = '';
			$class = $className;
			if ($f == 'email') {
				$subtype = 'email';
			} elseif ($f == 'current_accommodation' || $f == 'address') {
				$type = 'textarea';
			} elseif ($f == 'nationality') {
				$type = 'select';
			} elseif ($f == 'marital_status') {
				$type = 'select';
			} elseif ($f == 'gender') {
				$type = 'select';
			} elseif ($f == 'diploma') {
				$type = 'select';
			} elseif ($f == 'days_for_identity') {
				$type = 'text';
				$class .= ' fc-datepicker';
			} elseif ($f == 'position') {
				$type = 'select';
			} elseif ($f == 'year_experience') {
				$type = 'select';
			}

			if ($f == 'pet_after_name') {
				$label = _l('pet_after_name');
			} elseif ($f == 'email') {
				$label = _l('lead_add_edit_email');
			} elseif ($f == 'phonenumber') {
				$label = _l('lead_add_edit_phonenumber');
			} elseif ($f == 'pet_after_code') {
				$label = _l('pet_after_code');
			} elseif ($f == 'birthday') {
				$label = _l('birthday');
			} elseif ($f == 'gender') {
				$label = _l('gender');
			} elseif ($f == 'desired_walk_time') {
				$label = _l('desired_walk_time');
			} elseif ($f == 'birthplace') {
				$label = _l('birthplace');
			} elseif ($f == 'home_town') {
				$label = _l('home_town');
			} elseif ($f == 'identification') {
				$label = _l('identification');
			} elseif ($f == 'place_of_issue') {
				$label = _l('place_of_issue');
			} elseif ($f == 'marital_status') {
				$label = _l('marital_status');
			} elseif ($f == 'nationality') {
				$label = _l('nationality');
			} elseif ($f == 'nation') {
				$label = _l('nation');
			} elseif ($f == 'religion') {
				$label = _l('religion');
			} elseif ($f == 'height') {
				$label = _l('height');
			} elseif ($f == 'weight') {
				$label = _l('weight');
			} elseif ($f == 'introduce_yourself') {
				$label = _l('introduce_yourself');
			} elseif ($f == 'skype') {
				$label = _l('skype');
			} elseif ($f == 'facebook') {
				$label = _l('facebook');
			} elseif ($f == 'resident') {
				$label = _l('resident');
			} elseif ($f == 'current_accommodation') {
				$label = _l('current_accommodation');
			} elseif ($f == 'position') {
				$label = _l('position');
			} elseif ($f == 'contact_walker') {
				$label = _l('contact_walker');
			} elseif ($f == 'reason_quitwork') {
				$label = _l('reason_quitwork');
			} elseif ($f == 'walk_time') {
				$label = _l('walk_time');
			} elseif ($f == 'sitter_description') {
				$label = _l('sitter_description');
			} elseif ($f == 'diploma') {
				$label = _l('diploma');
			} elseif ($f == 'training_places') {
				$label = _l('training_places');
			} elseif ($f == 'specialized') {
				$label = _l('specialized');
			} elseif ($f == 'training_form') {
				$label = _l('training_form');
			} elseif ($f == 'diploma') {
				$label = _l('diploma');
			} elseif ($f == 'days_for_identity') {
				$label = _l('days_for_identity');
			} elseif ($f == 'year_experience') {
				$label = _l('experience');
			} else {
				$label = _l('lead_' . $f);
			}

			$field_array = [
				'subtype' => $subtype,
				'type' => $type,
				'label' => $label,
				'className' => $class,
				'name' => $f,
			];

			if ($f == 'nationality') {
				$field_array['values'] = [];

				$field_array['values'][] = [
					'label' => '',
					'value' => '',
					'selected' => false,
				];

				$countries = get_all_countries();
				foreach ($countries as $country) {
					$selected = false;
					if (get_option('customer_default_country') == $country['country_id']) {
						$selected = true;
					}

					if ((int) $country['country_id'] == '54') {
						$label = str_replace("'", "", $country['short_name']);

						array_push($field_array['values'], [
							'label' => $label,
							'value' => (int) $country['country_id'],
							'selected' => $selected,
						]);
					} else {
						array_push($field_array['values'], [
							'label' => $country['short_name'],
							'value' => (int) $country['country_id'],
							'selected' => $selected,
						]);
					}
				}
			}
			if ($f == 'marital_status') {
				$field_array['values'] = [];

				$field_array['values'][] = [
					'label' => '',
					'value' => '',
					'selected' => false,
				];
				array_push($field_array['values'], [
					'label' => _l('single'),
					'value' => 'single',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('married'),
					'value' => 'married',
					'selected' => false,
				]);
			}
			if ($f == 'gender') {
				$field_array['values'] = [];

				$field_array['values'][] = [
					'label' => '',
					'value' => '',
					'selected' => false,
				];
				array_push($field_array['values'], [
					'label' => _l('male'),
					'value' => 'male',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('female'),
					'value' => 'female',
					'selected' => false,
				]);
			}
			if ($f == 'diploma') {
				$field_array['values'] = [];

				$field_array['values'][] = [
					'label' => '',
					'value' => '',
					'selected' => false,
				];

				array_push($field_array['values'], [
					'label' => _l('primary_level'),
					'value' => 'primary_level',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('intermediate_level'),
					'value' => 'intermediate_level',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('college_level'),
					'value' => 'college_level',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('masters'),
					'value' => 'masters',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('doctor'),
					'value' => 'doctor',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('bachelor'),
					'value' => 'bachelor',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('engineer'),
					'value' => 'engineer',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('university'),
					'value' => 'university',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('intermediate_vocational'),
					'value' => 'intermediate_vocational',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('college_vocational'),
					'value' => 'college_vocational',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('in-service'),
					'value' => 'in-service',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('high_school'),
					'value' => 'high_school',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('intermediate_level_pro'),
					'value' => 'intermediate_level_pro',
					'selected' => false,
				]);
			}
			if ($f == 'year_experience') {
				$field_array['values'] = [];

				$field_array['values'][] = [
					'label' => _l('no_experience_yet'),
					'value' => 'no_experience_yet',
					'selected' => false,
				];
				array_push($field_array['values'], [
					'label' => _l('less_than_1_year'),
					'value' => 'less_than_1_year',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('1_year'),
					'value' => '1_year',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('2_years'),
					'value' => '2_years',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('3_years'),
					'value' => '3_years',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('4_years'),
					'value' => '4_years',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('5_years'),
					'value' => '5_years',
					'selected' => false,
				]);
				array_push($field_array['values'], [
					'label' => _l('over_5_years'),
					'value' => 'over_5_years',
					'selected' => false,
				]);
			}
			if ($f == 'name') {
				$field_array['required'] = true;
			}

			$_field_object->label = $label;
			$_field_object->name = $f;
			$_field_object->fields = [];
			$_field_object->fields[] = $field_array;
			$db_fields[] = $_field_object;
		}
		$data['bodyclass'] = 'web-to-lead-form';
		$data['db_fields'] = $db_fields;
		$data['par_id'] = $id;

		$data['list_rec_campaign'] = $this->appointment_model->get_rec_campaign();
		$this->load->model('roles_model');

		$data['roles'] = $this->roles_model->get();
		$this->load->view('appointment_channel/appointment_channel_detail', $data);
	}

	/**
	 * table appointment channel
	 * @return
	 */
	public function table_appointment_channel()
	{
		if ($this->input->is_ajax_request()) {
			$this->app->get_table_data(module_views_path('appointment', 'appointment_channel/table_appointment_channel'));
		}
	}

	/**
	 * delete appointment channel
	 * @param  int $id
	 * @return [type]
	 */
	public function delete_appointment_channel($id)
	{
		if (!$id) {
			redirect(admin_url('appointment/appointment_campaign'));
		}

		if (!has_permission('appointment', '', 'delete()') && !is_admin()) {
			access_denied('_appointment_channel');
		}

		$response = $this->appointment_model->delete_appointment_channel($id);

		if ($response == true) {
			set_alert('success', _l('deleted'));
		} else {
			set_alert('warning', _l('problem_deleting'));
		}

		redirect(admin_url('appointment/appointment_channel'));
	}

	/**
	 * get appointment channel data ajax
	 * @param  int $id
	 * @return view
	 */
	public function get_appointment_channel_data_ajax($id)
	{

		$data['id'] = $id;

		$data['total_cv_form'] = $this->appointment_model->count_cv_from_appointment_channel($id, 1);

		$data['appointment_channel'] = $this->appointment_model->get_appointment_channel($id);

		$this->load->view('appointment_channel/appointment_channel_preview', $data);
	}

	/**
	 * add pet_after form appointment channel
	 * @param redirect
	 */
	public function add_pet_after_form_appointment_channel($form_key)
	{
		$data = $this->input->post();
		if ($data) {
			$ids = $this->appointment_model->add_pet_after_forms($data, $form_key);
			if ($ids) {
				handle_rec_pet_after_file($ids);
				handle_rec_pet_after_avar_file($ids);
				$success = true;
				$message = _l('added_successfully', _l('pet_after_profile'));
				set_alert('success', $message);
				redirect(site_url('appointment/forms/wtl/' . $form_key));
			}
		}
	}
}
