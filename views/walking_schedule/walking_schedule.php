<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12" id="small-table">
        <div class="panel_s">
          <div class="panel-body">
            <?php echo form_hidden('walking_id', $walking_id); ?>
            <div class="row">
              <div class="col-md-12">
                <h4 class="no-margin font-bold"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo _l($title); ?></h4>
                <hr />
              </div>
            </div>
            <?php if (has_permission('appointment', '', 'delete') || is_admin()) { ?>
              <a href="#" onclick="new_walking_schedule(); return false;" class="btn btn-info pull-left display-block"><?php echo _l('new_walking_schedule'); ?></a>
            <?php } ?>
            <div class="col-md-1 pull-right">
              <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs" onclick="toggle_small_view_walking_schedules('.walking_sm','#walking_sm_view'); return false;" data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i class="fa fa-angle-double-left"></i></a>
            </div>

            <br><br><br>
            <?php render_datatable(array(

              _l('walking_schedules_name'),
              _l('time'),
              _l('walking_day'),
              _l('appointment_campaign'),
              _l('pet_after'),
              _l('walkinger'),
              _l('date_add'),
              _l('add_from'),
            ), 'table_walking', ['walking_sm' => 'walking_sm']); ?>
          </div>
        </div>
      </div>
      <div class="col-md-7 small-table-right-col">
        <div id="walking_sm_view" class="hide">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="walking_schedules_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('appointment/walking_schedules'), array('id' => 'walking_schedule-form')); ?>
    <div class="modal-content width-135">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="add-title"><?php echo _l('new_walking_schedule'); ?></span>
          <span class="edit-title"><?php echo _l('edit_walking_schedule'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div id="additional_walking"></div>
          <div class="col-md-12">
            <h5 class="bold"><?php echo _l('general_infor') ?></h5>
            <hr class="margin-top-10" />
          </div>
          <div class="col-md-6">
            <label for="campaign"><?php echo _l('appointment_campaign'); ?></label>
            <select name="campaign" id="campaign" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>">
              <option value=""></option>
              <?php foreach ($rec_campaigns as $s) { ?>
                <option value="<?php echo html_entity_decode($s['cp_id']); ?>" <?php if (isset($pet_after) && $s['cp_id'] == $pet_after->rec_campaign) {
                                                                                  echo 'selected';
                                                                                } ?>><?php echo html_entity_decode($s['campaign_code'] . ' - ' . $s['campaign_name']); ?></option>
              <?php } ?>
            </select>

          </div>
          <div class="col-md-6">
            <?php echo render_input('is_name', 'walking_schedules_name') ?>

          </div>

          <div class="col-md-4">
            <?php echo render_date_input('walking_day', 'walking_day'); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_datetime_input('from_time', 'from_time') ?>
          </div>

          <div class="col-md-4">
            <?php echo render_datetime_input('to_time', 'to_time') ?>
          </div>

          <div class="col-md-12 form-group">
            <label for="walkinger"><span class="text-danger">* </span><?php echo _l('walkinger'); ?></label>
            <select name="walkinger[]" id="walkinger" class="selectpicker" multiple="true" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" required>

              <?php foreach ($staffs as $s) { ?>
                <option value="<?php echo html_entity_decode($s['staffid']); ?>"><?php echo html_entity_decode($s['firstname'] . ' ' . $s['lastname']); ?></option>
              <?php } ?>
            </select>
            <br><br>
          </div>

          <div class="col-md-12">
            <h5 class="bold"><?php echo _l('list_of_pet_afters_participating'); ?></h5>
            <hr class="margin-top-10" />
          </div>

          <div class="col-md-12">
            <div id="example"></div>
          </div>

          <div class="col-md-4"> <label for="pet_after[0]"><span class="text-danger">* </span><?php echo _l('pet_after'); ?></label> </div>
          <div class="col-md-4"> <label for="email"><?php echo _l('email'); ?></label> </div>
          <div class="col-md-3"> <label for="phonenumber"><?php echo _l('phonenumber'); ?></label> </div>

          <div class="list_pet_afters">

            <div class="row col-md-12" id="pet_afters-item">
              <div class="col-md-4 form-group">
                <select name="pet_after[0]" onchange="pet_after_infor_change(this); return false;" id="pet_after[0]" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" required>
                  <option value=""></option>
                  <?php foreach ($pet_afters as $s) { ?>
                    <option value="<?php echo html_entity_decode($s['id']); ?>"><?php echo html_entity_decode($s['pet_after_code'] . ' ' . $s['pet_after_name']); ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-md-4">

                <input type="text" disabled="true" name="email[0]" id="email[0]" class="form-control" />
              </div>

              <div class="col-md-3">
                <input type="text" disabled="true" name="phonenumber[0]" id="phonenumber[0]" class="form-control" />
              </div>
              <div class="col-md-1 lightheight-34-nowrap">
                <span class="input-group-btn pull-bot">
                  <button name="add" class="btn new_pet_afters btn-success border-radius-4" data-ticket="true" type="button"><i class="fa fa-plus"></i></button>
                </span>
              </div>

            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="
                    " class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button id="sm_btn" type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
      </div>
    </div><!-- /.modal-content -->
    <?php echo form_close(); ?>
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php init_tail(); ?>

</body>

</html>