<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <h4 class="no-margin font-bold"> <?php echo _l($title); ?></h4>
                <hr />
              </div>
            </div>
            <?php if (isset($pet_after)) {
              echo form_hidden('pet_afterid', $pet_after->id);
              echo form_open_multipart(admin_url('appointment/add_update_pet_after/' . $pet_after->id), array('id' => 'appointment-pet_after-form'));
            } else {
              echo form_open_multipart(admin_url('appointment/add_update_pet_after'), array('id' => 'appointment-pet_after-form'));
            } ?>


            <div class="col-md-7">
              <div class="panel panel-primary">
                <div class="panel-heading"><?php echo _l('general_infor') ?></div>
                <div class="panel-body">

                  <div class="col-md-12">
                    <label for="rec_campaign"><?php echo _l('appointment_campaign'); ?></label>
                    <select name="rec_campaign" id="rec_campaign" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                      <option value=""></option>
                      <?php foreach ($rec_campaigns as $s) { ?>
                        <option value="<?php echo html_entity_decode($s['cp_id']); ?>" <?php if (isset($pet_after) && $s['cp_id'] == $pet_after->rec_campaign) {
                                                                                          echo 'selected';
                                                                                        } ?>><?php echo html_entity_decode($s['campaign_code'] . ' - ' . $s['campaign_name']); ?></option>
                      <?php } ?>
                    </select>
                    <br><br>
                  </div>

                  <div class="col-md-4">
                    <?php $pet_after_code = (isset($pet_after) ? $pet_after->pet_after_code : '');
                    echo render_input('pet_after_code', 'pet_after_code', $pet_after_code) ?>
                  </div>

                  <div class="col-md-8">
                    <?php $pet_after_name = (isset($pet_after) ? $pet_after->pet_after_name : '');
                    echo render_input('pet_after_name', 'pet_after_name', $pet_after_name) ?>
                  </div>

                  <div class="col-md-4">
                    <?php $birthday = (isset($pet_after) ? _d($pet_after->birthday) : '');
                    echo render_date_input('birthday', 'birthday', $birthday) ?>
                  </div>

                  <div class="col-md-4">
                    <label for="gender"><?php echo _l('gender'); ?></label>
                    <select name="gender" id="gender" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
                      <option value=""></option>
                      <option value="male" <?php if (isset($pet_after) && $pet_after->gender == 'male') {
                                              echo 'selected';
                                            } ?>><?php echo _l('male'); ?></option>
                      <option value="female" <?php if (isset($pet_after) && $pet_after->gender == 'female') {
                                                echo 'selected';
                                              } ?>><?php echo _l('female'); ?></option>
                    </select>
                    <br><br>
                  </div>

                  <div class="col-md-4">
                    <?php $arrAtt = array();
                    $arrAtt['data-type'] = 'currency';
                    $desired_walk_time = (isset($pet_after) ? app_format_money($pet_after->desired_walk_time, '') : '');
                    echo render_input('desired_walk_time', 'desired_walk_time', $desired_walk_time, 'text', $arrAtt) ?>
                  </div>

                  <div class="col-md-6">
                    <?php $birthplace = (isset($pet_after) ? $pet_after->birthplace : '');
                    echo render_textarea('birthplace', 'birthplace', $birthplace) ?>
                  </div>

                  <div class="col-md-6">
                    <?php $home_town = (isset($pet_after) ? $pet_after->home_town : '');
                    echo render_textarea('home_town', 'home_town', $home_town) ?>
                  </div>

                  <div class="col-md-4">
                    <?php $identification = (isset($pet_after) ? $pet_after->identification : '');
                    echo render_input('identification', 'identification', $identification); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $days_for_identity = (isset($pet_after) ? _d($pet_after->days_for_identity) : '');
                    echo render_date_input('days_for_identity', 'days_for_identity', $days_for_identity); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $place_of_issue = (isset($pet_after) ? $pet_after->place_of_issue : '');
                    echo render_input('place_of_issue', 'place_of_issue', $place_of_issue); ?>
                  </div>

                  <div class="col-md-4">
                    <label for="marital_status" class="control-label"><?php echo _l('marital_status'); ?></label>
                    <select name="marital_status" class="selectpicker" id="marital_status" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                      <option value=""></option>
                      <option value="<?php echo 'single'; ?>" <?php if (isset($pet_after) && $pet_after->marital_status == 'single') {
                                                                echo 'selected';
                                                              } ?>><?php echo _l('single'); ?></option>
                      <option value="<?php echo 'married'; ?>" <?php if (isset($pet_after) && $pet_after->marital_status == 'married') {
                                                                  echo 'selected';
                                                                } ?>><?php echo _l('married'); ?></option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <?php $nationality = (isset($pet_after) ? $pet_after->nationality : '');
                    echo render_input('nationality', 'nationality', $nationality); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $nation = (isset($pet_after) ? $pet_after->nation : '');
                    echo render_input('nation', 'nation', $nation); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $religion = (isset($pet_after) ? $pet_after->religion : '');
                    echo render_input('religion', 'religion', $religion); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $height = (isset($pet_after) ? $pet_after->height : '');
                    echo render_input('height', 'height', $height); ?>
                  </div>
                  <div class="col-md-4">
                    <?php $weight = (isset($pet_after) ? $pet_after->weight : '');
                    echo render_input('weight', 'weight', $weight); ?>
                  </div>

                  <div class="col-md-12">
                    <?php $introduce_yourself = (isset($pet_after) ? $pet_after->introduce_yourself : '');
                    echo render_textarea('introduce_yourself', 'introduce_yourself', $introduce_yourself) ?>
                  </div>

                </div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="panel panel-info">
                <div class="panel-heading"><?php echo _l('contact_info') ?></div>
                <div class="panel-body">

                  <div class="col-md-6">
                    <?php $phonenumber = (isset($pet_after) ? $pet_after->phonenumber : '');
                    echo render_input('phonenumber', 'phonenumber', $phonenumber); ?>
                  </div>
                  <div class="col-md-6">
                    <?php $email = (isset($pet_after) ? $pet_after->email : '');
                    echo render_input('email', 'email', $email); ?>
                  </div>

                  <div class="col-md-6">
                    <?php $skype = (isset($pet_after) ? $pet_after->skype : '');
                    echo render_input('skype', 'skype', $skype); ?>
                  </div>
                  <div class="col-md-6">
                    <?php $facebook = (isset($pet_after) ? $pet_after->facebook : '');
                    echo render_input('facebook', 'facebook', $facebook); ?>
                  </div>
                  <div class="col-md-12">
                    <?php $resident = (isset($pet_after) ? $pet_after->resident : '');
                    echo render_textarea('resident', 'resident', $resident) ?>
                  </div>
                  <div class="col-md-12">
                    <?php $current_accommodation = (isset($pet_after) ? $pet_after->current_accommodation : '');
                    echo render_textarea('current_accommodation', 'current_accommodation', $current_accommodation) ?>

                  </div>
                  <div class="col-md-12 pull-left">
                    <div class="container">
                      <div class="picture-container pull-left">
                        <div class="picture pull-left">
                          <img src="<?php if (isset($pet_after->avar)) {
                                      echo site_url(appointment_PATH . 'pet_after/avartar/' . $pet_after->id . '/' . $pet_after->avar->file_name);
                                    } else {
                                      echo site_url(appointment_PATH . 'none_avatar.jpg');
                                    } ?>" class="picture-src" id="wizardPicturePreview" title="">
                          <input name="cd_avar" type="file" id="wizard-picture" accept=".png, .jpg, .jpeg" class="">
                        </div>

                        <h5 class=""><?php echo _l('choose_picture'); ?></h5>

                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr>
                    <?php echo render_input('file', 'file_campaign', '', 'file') ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-success">
                <div class="panel-heading"><?php echo _l('work_experience') ?></div>
                <div class="panel-body">
                  <div class="work_experience">
                    <?php if (isset($pet_after) && count($pet_after->work_experience) > 0) {
                      foreach ($pet_after->work_experience as $key => $val) {
                    ?>
                        <div class="row col-md-12" id="work_experience-item">
                          <div class="col-md-3">
                            <?php $from_date = _d($val['from_date']);
                            echo render_date_input('from_date[' . $key . ']', 'from_date', $from_date); ?>
                          </div>

                          <div class="col-md-3">
                            <?php $to_date = _d($val['to_date']);
                            echo render_date_input('to_date[' . $key . ']', 'to_date', $to_date); ?>
                          </div>

                          <div class="col-md-3">
                            <?php $walker = $val['walker'];
                            echo render_input('walker[' . $key . ']', 'walker', $walker) ?>
                          </div>

                          <div class="col-md-3">
                            <?php $position = $val['position'];
                            echo render_input('position[' . $key . ']', 'position', $position) ?>
                          </div>

                          <div class="col-md-3">
                            <?php $contact_walker = $val['contact_walker'];
                            echo render_input('contact_walker[' . $key . ']', 'contact_walker', $contact_walker) ?>
                          </div>
                          <div class="col-md-3">
                            <?php $walk_time = $val['walk_time'];
                            echo render_input('walk_time[' . $key . ']', 'walk_time', $walk_time) ?>
                          </div>

                          <div class="col-md-3">
                            <?php $reason_quitwork = $val['reason_quitwork'];
                            echo render_input('reason_quitwork[' . $key . ']', 'reason_quitwork', $reason_quitwork) ?>
                          </div>

                          <div class="col-md-3">
                            <?php $sitter_description = $val['sitter_description'];
                            echo render_input('sitter_description[' . $key . ']', 'sitter_description', $sitter_description) ?>
                          </div>

                          <?php if ($key == 0) { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn new_work_experience btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                              </span>
                            </div>
                          <?php } else { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn remove_work_experience btn-danger border-radius-4" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                              </span>
                            </div>
                          <?php } ?>
                        </div>

                      <?php }
                    } else { ?>
                      <div class="row col-md-12" id="work_experience-item">
                        <div class="col-md-3">
                          <?php echo render_date_input('from_date[0]', 'from_date', ''); ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_date_input('to_date[0]', 'to_date', ''); ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_input('walker[0]', 'walker') ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_input('position[0]', 'position') ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_input('contact_walker[0]', 'contact_walker') ?>
                        </div>
                        <div class="col-md-3">
                          <?php echo render_input('walk_time[0]', 'walk_time') ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_input('reason_quitwork[0]', 'reason_quitwork') ?>
                        </div>

                        <div class="col-md-3">
                          <?php echo render_input('sitter_description[0]', 'sitter_description') ?>
                        </div>

                        <div class="col-md-12 line-height-content">
                          <span class="input-group-btn pull-bot">
                            <button name="add" class="btn new_work_experience btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          </span>
                        </div>

                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading"><?php echo _l('literacy') ?></div>
                <div class="panel-body">
                  <div class="list_literacy">
                    <?php if (isset($pet_after) && count($pet_after->literacy) > 0) {
                      foreach ($pet_after->literacy as $key => $val) {
                    ?>
                        <div class="row col-md-12" id="literacy-item">
                          <div class="col-md-2">
                            <?php $literacy_from_date = _d($val['literacy_from_date']);
                            echo render_date_input('literacy_from_date[' . $key . ']', 'from_date', $literacy_from_date); ?>
                          </div>

                          <div class="col-md-2">
                            <?php $literacy_to_date = _d($val['literacy_to_date']);
                            echo render_date_input('literacy_to_date[' . $key . ']', 'to_date', $literacy_to_date); ?>
                          </div>

                          <div class="col-md-2">
                            <?php $diploma = $val['diploma'];
                            echo render_input('diploma[' . $key . ']', 'diploma', $diploma) ?>
                          </div>

                          <div class="col-md-2">
                            <?php $training_places = $val['training_places'];
                            echo render_input('training_places[' . $key . ']', 'training_places', $training_places) ?>
                          </div>

                          <div class="col-md-2">
                            <?php $specialized = $val['specialized'];
                            echo render_input('specialized[' . $key . ']', 'specialized', $specialized) ?>
                          </div>
                          <div class="col-md-2">
                            <?php $training_form = $val['training_form'];
                            echo render_input('training_form[' . $key . ']', 'training_form', $training_form) ?>
                          </div>
                          <?php if ($key == 0) { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn new_literacy btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                              </span>
                            </div>
                          <?php } else { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn remove_literacy btn-danger border-radius-4" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                              </span>
                            </div>
                          <?php } ?>


                        </div>
                      <?php }
                    } else { ?>
                      <div class="row col-md-12" id="literacy-item">
                        <div class="col-md-2">
                          <?php echo render_date_input('literacy_from_date[0]', 'from_date', ''); ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_date_input('literacy_to_date[0]', 'to_date', ''); ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('diploma[0]', 'diploma') ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('training_places[0]', 'training_places') ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('specialized[0]', 'specialized') ?>
                        </div>
                        <div class="col-md-2">
                          <?php echo render_input('training_form[0]', 'training_form') ?>
                        </div>

                        <div class="col-md-12 line-height-content">
                          <span class="input-group-btn pull-bot">
                            <button name="add" class="btn new_literacy btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          </span>
                        </div>

                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-warning">
                <div class="panel-heading"><?php echo _l('family_infor') ?></div>
                <div class="panel-body">
                  <div class="list_family_infor">
                    <?php if (isset($pet_after) && count($pet_after->family_infor) > 0) {
                      foreach ($pet_after->family_infor as $key => $val) {
                    ?>
                        <div class="row col-md-12" id="family_infor-item">
                          <div class="col-md-2">
                            <?php $relationship = $val['relationship'];
                            echo render_input('relationship[' . $key . ']', 'relationship', $relationship); ?>
                          </div>

                          <div class="col-md-2">
                            <?php $name = $val['name'];
                            echo render_input('name[' . $key . ']', 'name', $name); ?>
                          </div>

                          <div class="col-md-2">
                            <?php $fi_birthday = _d($val['fi_birthday']);
                            echo render_date_input('fi_birthday[' . $key . ']', 'birthday', $fi_birthday) ?>
                          </div>

                          <div class="col-md-2">
                            <?php $job = $val['job'];
                            echo render_input('job[' . $key . ']', 'job', $job) ?>
                          </div>

                          <div class="col-md-2">
                            <?php $address = $val['address'];
                            echo render_input('address[' . $key . ']', 'address', $address) ?>
                          </div>
                          <div class="col-md-2">
                            <?php $phone = $val['phone'];
                            echo render_input('phone[' . $key . ']', 'phone', $phone) ?>
                          </div>
                          <?php if ($key == 0) { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn new_family_infor btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                              </span>
                            </div>
                          <?php } else { ?>
                            <div class="col-md-12 line-height-content">
                              <span class="input-group-btn pull-bot">
                                <button name="add" class="btn remove_family_infor btn-danger border-radius-4" data-ticket="true" type="button"><i class="fa fa-minus"></i></button>
                              </span>
                            </div>
                          <?php } ?>
                        </div>
                      <?php }
                    } else { ?>
                      <div class="row col-md-12" id="family_infor-item">
                        <div class="col-md-2">
                          <?php echo render_input('relationship[0]', 'relationship', ''); ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('name[0]', 'name', ''); ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_date_input('fi_birthday[0]', 'birthday') ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('job[0]', 'job') ?>
                        </div>

                        <div class="col-md-2">
                          <?php echo render_input('address[0]', 'address') ?>
                        </div>
                        <div class="col-md-2">
                          <?php echo render_input('phone[0]', 'phone') ?>
                        </div>

                        <div class="col-md-12 line-height-content">
                          <span class="input-group-btn pull-bot">
                            <button name="add" class="btn new_family_infor btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                          </span>
                        </div>

                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
            <?php echo form_close(); ?>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
<?php init_tail(); ?>
<script>
  $(document).ready(function() {
    $("#wizard-picture").change(function() {
      readURL(this);
    });
  });
</script>
</body>

</html>