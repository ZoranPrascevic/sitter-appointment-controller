(function ($) {
  "use strict";
  initDataTable('.table-table_walking', admin_url + 'appointment/table_walking');
  appValidateForm($('#walking_schedule-form'), {
    rec_campaign: 'required', is_name: 'required', walking_day: 'required', from_time: 'required', to_time: 'required'
  });

  init_appointment_walking_schedules();

  $('#from_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });
  $('#to_time').datetimepicker({
    datepicker: false,
    format: 'H:i'
  });
  var addMorepet_afterInputKey = $('.list_pet_afters input[name*="email"]').length;
  $("body").on('click', '.new_pet_afters', function () {
    if ($(this).hasClass('disabled')) { return false; }

    var newattachment = $('.list_pet_afters').find('#pet_afters-item').eq(0).clone().appendTo('.list_pet_afters');
    newattachment.find('button[data-toggle="dropdown"]').remove();
    newattachment.find('select').selectpicker('refresh');

    newattachment.find('select[name="pet_after[0]"]').attr('name', 'pet_after[' + addMorepet_afterInputKey + ']').val('');
    newattachment.find('select[id="pet_after[0]"]').attr('id', 'pet_after[' + addMorepet_afterInputKey + ']').selectpicker('refresh');

    newattachment.find('input[name="email[0]"]').attr('name', 'email[' + addMorepet_afterInputKey + ']').val('');
    newattachment.find('input[id="email[0]"]').attr('id', 'email[' + addMorepet_afterInputKey + ']').val('');

    newattachment.find('input[name="phonenumber[0]"]').attr('name', 'phonenumber[' + addMorepet_afterInputKey + ']').val('');
    newattachment.find('input[id="phonenumber[0]"]').attr('id', 'phonenumber[' + addMorepet_afterInputKey + ']').val('');

    newattachment.find('button[name="add"] i').removeClass('fa-plus').addClass('fa-minus');
    newattachment.find('button[name="add"]').removeClass('new_pet_afters').addClass('remove_pet_afters').removeClass('btn-success').addClass('btn-danger');

    addMorepet_afterInputKey++;

  });

  $("body").on('click', '.remove_pet_afters', function () {
    $(this).parents('#pet_afters-item').remove();
  });

})(jQuery);

function new_walking_schedule() {
  "use strict";
  $('#walking_schedules_modal').modal('show');
  $('.add-title').removeClass('hide');
  $('.edit-title').addClass('hide');
  $('#additional_walking').html('');
  $('select[id="pet_after"]').val('').change();
  $('input[id="email"]').val('');
  $('input[id="phonenumber"]').val('');

}
function edit_walking_schedule(invoker, id) {
  "use strict";
  $('#walking_schedules_modal').modal('show');
  $('.add-title').addClass('hide');
  $('.edit-title').removeClass('hide');
  $('#additional_walking').html('');
  $('#additional_walking').append(hidden_input('id', id));
  $('#walking_schedules_modal input[name="is_name"]').val($(invoker).data('is_name'));
  $('#walking_schedules_modal select[name="campaign"]').val($(invoker).data('campaign'));
  $('#walking_schedules_modal select[name="campaign"]').change();
  $('#walking_schedules_modal input[name="walking_day"]').val($(invoker).data('walking_day'));
  $('#walking_schedules_modal input[name="from_time"]').val($(invoker).data('from_time'));
  $('#walking_schedules_modal input[name="to_time"]').val($(invoker).data('to_time'));
  var walkinger = $(invoker).data('walkinger') + '';
  $('#walking_schedules_modal select[id="walkinger"]').val(walkinger.split(',')).change();

  $.post(admin_url + 'appointment/get_pet_after_edit_walking/' + id).done(function (response) {
    response = JSON.parse(response);
    $('.list_pet_afters').html('');
    $('.list_pet_afters').append(response.html);
    $('.selectpicker').selectpicker('refresh');
  });
}
function init_appointment_walking_schedules(id) {
  load_small_table_item_walking_schedules(id, '#walking_sm_view', 'walking_id', 'appointment/get_walking_data_ajax', '.walking_sm');
}
function load_small_table_item_walking_schedules(pr_id, selector, input_name, url, table) {
  "use strict";
  var _tmpID = $('input[name="' + input_name + '"]').val();
  // Check if id passed from url, hash is prioritized becuase is last
  if (_tmpID !== '' && !window.location.hash) {
    pr_id = _tmpID;
    // Clear the current id value in case user click on the left sidebar credit_note_ids
    $('input[name="' + input_name + '"]').val('');
  } else {
    // check first if hash exists and not id is passed, becuase id is prioritized
    if (window.location.hash && !pr_id) {
      pr_id = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
    }
  }
  if (typeof (pr_id) == 'undefined' || pr_id === '') { return; }
  if (!$("body").hasClass('small-table')) { toggle_small_view_walking_schedules(table, selector); }
  $('input[name="' + input_name + '"]').val(pr_id);
  do_hash_helper(pr_id);
  $(selector).load(admin_url + url + '/' + pr_id);
  if (is_mobile()) {
    $('html, body').animate({
      scrollTop: $(selector).offset().top + 150
    }, 600);
  }
}
function toggle_small_view_walking_schedules(table, main_data) {
  "use strict";
  var hidden_columns = [4, 6, 7];
  $("body").toggleClass('small-table');
  var tablewrap = $('#small-table');
  if (tablewrap.length === 0) { return; }
  var _visible = false;
  if (tablewrap.hasClass('col-md-5')) {
    tablewrap.removeClass('col-md-5').addClass('col-md-12');
    _visible = true;
    $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-right').addClass('fa fa-angle-double-left');
  } else {
    tablewrap.addClass('col-md-5').removeClass('col-md-12');
    $('.toggle-small-view').find('i').removeClass('fa fa-angle-double-left').addClass('fa fa-angle-double-right');
  }
  var _table = $(table).DataTable();
  // Show hide hidden columns
  _table.columns(hidden_columns).visible(_visible, false);
  _table.columns.adjust();
  $(main_data).toggleClass('hide');
  $(window).trigger('resize');
}
function pet_after_infor_change(invoker) {
  "use strict";
  var result = invoker.name.match(/\d/g);
  var data = {};
  data.walking_day = $('input[name="walking_day"]').val();
  data.from_time = $('input[name="from_time"]').val();
  data.to_time = $('input[name="to_time"]').val();
  data.pet_after = invoker.value;
  data.id = $('input[name="id"]').val();

  if (invoker.value == '') {
    $('input[name="email[' + result + ']"]').val('');
    $('input[name="phonenumber[' + result + ']"]').val('');

  } else {
    $.post(admin_url + 'appointment/get_pet_after_infor_change/' + invoker.value).done(function (response) {
      response = JSON.parse(response);
      $('input[name="email[' + result + ']"]').val(response.email);
      $('input[name="phonenumber[' + result + ']"]').val(response.phonenumber);

    });
    $.post(admin_url + 'appointment/check_time_walking', data).done(function (response) {
      response = JSON.parse(response);
      if (response.return == true) {
        alert_float('warning', response.rs, 6000);
        $('select[name="pet_after[' + result + ']"]').val('').change();

      }
    });
  }
}