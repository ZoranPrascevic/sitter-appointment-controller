<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php echo html_entity_decode($form->name); ?></title>
  <?php app_external_form_header($form); ?>
  <?php hooks()->do_action('app_web_to_lead_form_head'); ?>
</head>

<body class="web-to-lead <?php echo html_entity_decode($form->form_key); ?>" <?php if (is_rtl(true)) {
                                                                                echo ' dir="rtl"';
                                                                              } ?>>
  <div class="container-fluid">
    <div class="row">
      <div class="<?php if ($this->input->get('col')) {
                    echo html_entity_decode($this->input->get('col'));
                  } else {
                    echo 'col-md-12';
                  } ?>">
        <div id="response"></div>
        <?php echo form_open_multipart(admin_url() . 'appointment/add_pet_after_form_appointment_channel/' . $form->form_key); ?>

        <?php echo form_hidden('key', $form->form_key); ?>
        <div class="row">
          <?php foreach ($form_fields as $field) {
            render_form_builder_field($field);
          } ?>
          <?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '' && $form->recaptcha == 1) { ?>
            <div class="col-md-12">
              <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                <div id="recaptcha_response_field" class="text-danger"></div>
              </div>
            <?php } ?>
            <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) { ?>
              <div class="col-md-12">
                <div class="checkbox chk">
                  <input type="checkbox" name="accept_terms_and_conditions" required="true" id="accept_terms_and_conditions" <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
                  <label for="accept_terms_and_conditions">
                    <?php echo _l('gdpr_terms_agree', terms_url()); ?>
                  </label>
                </div>
              </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="text-left col-md-12 submit-btn-wrapper">
              <button class="btn btn-success" id="form_submit" type="submit"><?php echo html_entity_decode($form->submit_btn_name); ?></button>
            </div>
            </div>

            <?php hooks()->do_action('web_to_lead_form_end'); ?>
            <?php echo form_close(); ?>
        </div>
      </div>
    </div>
    <?php app_external_form_footer($form); ?>
    <?php require 'modules/appointment/assets/js/channel_form_js.php'; ?>
    <?php hooks()->do_action('app_web_to_lead_form_footer'); ?>
</body>

</html>