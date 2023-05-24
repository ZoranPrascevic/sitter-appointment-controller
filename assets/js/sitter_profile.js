(function ($) {
  "use strict";
  initDataTable('.table-table_rec_pet_after', admin_url + 'appointment/table_pet_afters');
})(jQuery);
function send_mail_pet_after() {
  "use strict";
  $('#mail_modal').modal('show');
  appValidateForm($('#mail_pet_after-form'), {
    content: 'required', subject: 'required', email: 'required'
  });
}