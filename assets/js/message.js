(function ($) {
  "use strict";
  var messageServerParams = {
    "dpm": "[name='sitter_available_time_filter[]']",
    "posiotion_ft": "[name='position_filter[]']",
    "status": "[name='status_filter[]']",
  };
  var table_rec_message = $('.table-table_rec_message');
  var _table_api = initDataTable(table_rec_message, admin_url + 'appointment/table_message', '', '', messageServerParams);

  $.each(messageServerParams, function (i, obj) {
    $('select' + obj).on('change', function () {
      table_rec_message.DataTable().ajax.reload()
        .columns.adjust()
        .responsive.recalc();
    });
  });

  _validate_form($('#appointment-message-form'), { pet_name: 'required', to_date: 'required', approver: 'required', position: 'required' });
  init_appointment_message();
  $("input[data-type='currency']").on({
    keyup: function () {
      formatCurrency($(this));
    },
    blur: function () {
      formatCurrency($(this), "blur");
    }
  });
})(jQuery);

function new_message() {
  "use strict";
  $('#appointment_message').modal('show');
  $('.edit-title').addClass('hide');
  $('.add-title').removeClass('hide');
  $('#additional_message').html('');
  $('#appointment_message input[name="pet_name"]').val('');
  $('#appointment_message input[name="amount_recruiment"]').val('');
  $('#appointment_message input[name="walkplace"]').val('');
  $('#appointment_message input[name="from_date"]').val('');
  $('#appointment_message input[name="to_date"]').val('');
  $('#appointment_message input[name="walk_time_from"]').val('');
  $('#appointment_message input[name="walk_time_to"]').val('');
  $('#appointment_message input[name="ages_from"]').val('');
  $('#appointment_message input[name="ages_to"]').val('');
  $('#appointment_message input[name="height"]').val('');
  $('#appointment_message input[name="weight"]').val('');
  $('#appointment_message textarea[name="reason_appointment"]').val('');
  $('#appointment_message select[name="position"]').val('');
  $('#appointment_message select[name="position"]').change();
  $('#appointment_message select[name="sitter_available_time"]').val('');
  $('#appointment_message select[name="sitter_available_time"]').change();
  $('#appointment_message select[name="form_work"]').val('');
  $('#appointment_message select[name="form_work"]').change();
  $('#appointment_message select[name="approver"]').val('');
  $('#appointment_message select[name="approver"]').change();
  $('#appointment_message select[name="gender"]').val('');
  $('#appointment_message select[name="gender"]').change();
  $('#appointment_message select[name="literacy"]').val('');
  $('#appointment_message select[name="literacy"]').change();
  $('#appointment_message select[name="experience"]').val('');
  $('#appointment_message select[name="experience"]').change();
}
function edit_message(invoker, id) {
  "use strict";
  $('#additional_message').html('');
  $('#additional_message').append(hidden_input('id', id));
  $('.edit-title').removeClass('hide');
  $('.add-title').addClass('hide');
  $('#appointment_message').modal('show');
  $('#appointment_message input[name="pet_name"]').val($(invoker).data('pet_name'));
  $('#appointment_message input[name="amount_recruiment"]').val($(invoker).data('amount_recruiment'));
  $('#appointment_message input[name="walkplace"]').val($(invoker).data('walkplace'));
  $('#appointment_message input[name="from_date"]').val($(invoker).data('from_date'));
  $('#appointment_message input[name="to_date"]').val($(invoker).data('to_date'));
  $('#appointment_message input[name="walk_time_from"]').val($(invoker).data('walk_time_from'));
  $('#appointment_message input[name="walk_time_to"]').val($(invoker).data('walk_time_to'));
  $('#appointment_message input[name="ages_from"]').val($(invoker).data('ages_from'));
  $('#appointment_message input[name="ages_to"]').val($(invoker).data('ages_to'));
  $('#appointment_message input[name="height"]').val($(invoker).data('height'));
  $('#appointment_message input[name="weight"]').val($(invoker).data('weight'));
  $('#appointment_message textarea[name="reason_appointment"]').val($(invoker).data('reason_appointment'));

  /*get sitter_description*/
  $.post(admin_url + 'appointment/get_appointment_message_edit/' + id).done(function (response) {
    response = JSON.parse(response);

    tinyMCE.activeEditor.setContent(response.description);

    $('.selectpicker').selectpicker({
    });

  });

  $('#appointment_message select[name="position"]').val($(invoker).data('position'));
  $('#appointment_message select[name="position"]').change();
  $('#appointment_message select[name="sitter_available_time"]').val($(invoker).data('sitter_available_time'));
  $('#appointment_message select[name="sitter_available_time"]').change();
  $('#appointment_message select[name="form_work"]').val($(invoker).data('form_work'));
  $('#appointment_message select[name="form_work"]').change();
  $('#appointment_message select[name="approver"]').val($(invoker).data('approver'));
  $('#appointment_message select[name="approver"]').change();
  $('#appointment_message select[name="gender"]').val($(invoker).data('gender'));
  $('#appointment_message select[name="gender"]').change();
  $('#appointment_message select[name="literacy"]').val($(invoker).data('literacy'));
  $('#appointment_message select[name="literacy"]').change();
  $('#appointment_message select[name="experience"]').val($(invoker).data('experience'));
  $('#appointment_message select[name="experience"]').change();
}
function init_appointment_message(id) {
  load_small_table_item_message(id, '#message_sm_view', 'message_id', 'appointment/get_message_data_ajax', '.message_sm');
}
function load_small_table_item_message(pr_id, selector, input_name, url, table) {
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
  if (!$("body").hasClass('small-table')) { toggle_small_view_message(table, selector); }
  $('input[name="' + input_name + '"]').val(pr_id);
  do_hash_helper(pr_id);
  $(selector).load(admin_url + url + '/' + pr_id);
  if (is_mobile()) {
    $('html, body').animate({
      scrollTop: $(selector).offset().top + 150
    }, 600);
  }
}
function toggle_small_view_message(table, main_data) {
  "use strict";
  var hidden_columns = [3];
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
function formatNumber(n) {
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}
function formatCurrency(input, blur) {
  "use strict";
  var input_val = input.val();
  if (input_val === "") { return; }
  var original_len = input_val.length;
  var caret_pos = input.prop("selectionStart");
  if (input_val.indexOf(".") >= 0) {
    var decimal_pos = input_val.indexOf(".");
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);
    left_side = formatNumber(left_side);
    right_side = formatNumber(right_side);
    right_side = right_side.substring(0, 2);
    input_val = left_side + "." + right_side;
  } else {

    input_val = formatNumber(input_val);
    input_val = input_val;
  }
  input.val(input_val);
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function preview_message_btn(invoker) {
  "use strict";
  var id = $(invoker).attr('id');
  var rel_id = $(invoker).attr('rel_id');
  view_message_file(id, rel_id);
}

function view_message_file(id, rel_id) {
  "use strict";
  $('#message_file_data').empty();
  $("#message_file_data").load(admin_url + 'appointment/file/' + id + '/' + rel_id, function (response, status, xhr) {
    if (status == "error") {
      alert_float('danger', xhr.statusText);
    }
  });
}
function close_modal_preview() {
  $('._project_file').modal('hide');
}