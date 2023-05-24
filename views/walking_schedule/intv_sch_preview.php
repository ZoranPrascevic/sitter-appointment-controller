<div class="panel_s">
  <div class="panel-body">
    <div class="col-md-12">
      <h4 class="isp-general-infor"><?php echo _l('general_infor') ?></h4>
      <hr class="isp-general-infor-hr" />
    </div>
    <div class="col-md-12">
      <table class="table border table-striped margin-top-0">
        <tbody>
          <tr class="project-overview">
            <td class="bold" width="30%"><?php echo _l('walking_schedules_name'); ?></td>
            <td><?php echo html_entity_decode($intv_sch->is_name); ?></td>
          </tr>
          <tr class="project-overview">
            <td class="bold" width="30%"><?php echo _l('time'); ?></td>
            <td><?php echo html_entity_decode($intv_sch->from_time . ' - ' . $intv_sch->to_time); ?></td>
          </tr>
          <tr class="project-overview">
            <td class="bold"><?php echo _l('walking_day'); ?></td>
            <td><?php echo _d($intv_sch->walking_day); ?></td>
          </tr>
          <tr class="project-overview">
            <td class="bold"><?php echo _l('appointment_campaign'); ?></td>
            <td><?php $cp = get_rec_campaign_hp($intv_sch->campaign);
                if (isset($cp)) {
                  $_data = $cp->campaign_code . ' - ' . $cp->campaign_name;
                } else {
                  $_data = '';
                }
                echo html_entity_decode($_data); ?></td>
          </tr>

          <tr class="project-overview">
            <td class="bold"><?php echo _l('date_add'); ?></td>
            <td>
              <?php echo _d($intv_sch->added_date); ?>
            </td>
          </tr>

          <tr class="project-overview">
            <td class="bold"><?php echo _l('walkinger'); ?></td>
            <td><?php
                $inv = explode(',', $intv_sch->walkinger);
                $ata = '';
                foreach ($inv as $iv) {
                  $ata .= '<a href="' . admin_url('staff/profile/' . $iv) . '">' . staff_profile_image($iv, [
                    'staff-profile-image-small mright5',
                  ], 'small', [
                    'data-toggle' => 'tooltip',
                    'data-title' => get_staff_full_name($iv),
                  ]) . '</a>';
                }
                echo html_entity_decode($ata);
                ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-12">
      <h4 class="isp-general-infor"><?php echo _l('list_of_pet_afters_participating') ?></h4>
      <hr class="isp-general-infor-hr" />
    </div>
    <?php foreach ($intv_sch->list_pet_after as $cd) { ?>
      <div class="col-md-6">
        <div class="col-md-12">
          <div class="row">
            <div class="thumbnail">
              <div class="caption" onclick="location.href='<?php echo admin_url('appointment/pet_after/' . $cd['pet_after']) ?>'">

                <h4 id="thumbnail-label"><?php echo pet_after_profile_image($cd['pet_after'], ['staff-profile-image-small mright5'], 'small') . ' #' . $cd['pet_after_code'] . ' - ' . $cd['pet_after_name']; ?></h4>

                <p><?php echo _l('email') . ': ' . $cd['email']; ?></p>

                <div class="thumbnail-description smaller"><?php echo _l('phonenumber') . ': ' . $cd['phonenumber']; ?></div>

              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>