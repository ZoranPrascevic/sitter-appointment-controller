<script>
	var SetRatingStar = function() {
		"use strict";
		var $star_rating = $('.star-rating .fa');
		return $star_rating.each(function() {
			if (parseInt($star_rating.siblings('input[name="rating[' + $(this).data('id') + ']"]').val()) >= parseInt($(this).data('rating'))) {
				return $(this).removeClass('fa-star-o').addClass('fa-star');
			} else {
				return $(this).removeClass('fa-star').addClass('fa-star-o');
			}
		});
	};
	var SetRatingViewStar = function() {
		"use strict";
		var $star_rating_view = $('.star-rating-view .fa');
		return $star_rating_view.each(function() {
			if (parseInt($star_rating_view.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
				return $(this).removeClass('fa-star-o').addClass('fa-star');
			} else {
				return $(this).removeClass('fa-star').addClass('fa-star-o');
			}
		});
	};
	(function($) {
		"use strict";
		$('#toggle_popup_approval').on('click', function() {
			$('#popup_approval').toggle();
		});
		$('.star-rating .fa').on('click', function() {
			$('.star-rating .fa').siblings('input[name="rating[' + $(this).data('id') + ']"]').val($(this).data('rating'));
			return SetRatingStar();
		});
		SetRatingViewStar();
		SetRatingStar();
	})(jQuery);

	function send_mail_pet_after() {
		"use strict";
		$('#mail_modal').modal('show');
		appValidateForm($('#mail_pet_after-form'), {
			content: 'required',
			subject: 'required',
			email: 'required'
		});
	}

	function sendmail() {
		"use strict";
		$('.modal-title').html('');
		$('.modal-title').append('<span><?php echo _l('send_mail'); ?></span>');
		$('#care_rs').html('');
		$('#care_rs').append('<div class="form-group" app-field-wrapper="care_result"><label for="care_result" class="control-label"><?php echo _l('send_mail_rs') ?></label><input type="text" id="care_result" name="care_result" class="form-control" value=""></div>');

		$('#type_care').html('');
		$('#type_care').append('<input type="hidden" name="type" value="send_mail">');

		$('#care_modal').modal('show');
		appValidateForm($('#care_pet_after-form'), {
			care_time: 'required',
			is_name: 'required',
			care_result: 'required'
		});
	}

	function call() {
		"use strict";
		$('.modal-title').html('');
		$('.modal-title').append('<span><?php echo _l('call'); ?></span>');
		$('#care_rs').html('');
		$('#care_rs').append('<div class="form-group" app-field-wrapper="care_result"><label for="care_result" class="control-label"><?php echo _l('number_of_minutes_to_call') ?></label><input type="number" id="care_result" name="care_result" class="form-control" value=""></div>');

		$('#type_care').html('');
		$('#type_care').append('<input type="hidden" name="type" value="call">');

		$('#care_modal').modal('show');
		appValidateForm($('#care_pet_after-form'), {
			care_time: 'required',
			is_name: 'required',
			care_result: 'required'
		});

	}

	function test() {
		"use strict";
		$('.modal-title').html('');
		$('.modal-title').append('<span><?php echo _l('test'); ?></span>');
		$('#care_rs').html('');
		$('#care_rs').append('<div class="form-group" app-field-wrapper="care_result"><label for="care_result" class="control-label"><?php echo _l('result') ?></label><input type="text" id="care_result" name="care_result" class="form-control" value=""></div>');

		$('#type_care').html('');
		$('#type_care').append('<input type="hidden" name="type" value="test">');

		$('#care_modal').modal('show');
		appValidateForm($('#care_pet_after-form'), {
			care_time: 'required',
			is_name: 'required',
			care_result: 'required'
		});

	}

	function walking() {
		"use strict";
		$('.modal-title').html('');
		$('.modal-title').append('<span><?php echo _l('walking'); ?></span>');
		$('.modal-title').append('<span><?php echo _l('test'); ?></span>');
		$('#care_rs').html('');
		$('#care_rs').append('<div class="form-group" app-field-wrapper="care_result"><label for="care_result" class="control-label"><?php echo _l('result') ?></label><input type="text" id="care_result" name="care_result" class="form-control" value=""></div>');

		$('#type_care').html('');
		$('#type_care').append('<input type="hidden" name="type" value="walking">');

		$('#care_modal').modal('show');
		appValidateForm($('#care_pet_after-form'), {
			care_time: 'required',
			is_name: 'required',
			care_result: 'required'
		});

	}

	function submit_care_pet_after() {
		"use strict";
		var data = $('#care_pet_after-form').serialize();
		var url = $('#care_pet_after-form').action;
		$.post(admin_url + 'appointment/care_pet_after', data).done(function(response) {
			response = JSON.parse(response);
			alert_float('success', response.mess);
			$('#care_modal').modal('hide');
		});
	}

	function submit_rating_pet_after() {
		"use strict";
		var data = $('#rating-modal').serialize();
		$.post(admin_url + 'appointment/rating_pet_after', data).done(function(response) {
			response = JSON.parse(response);
			$('.star-rating-view input[name="rating"]').val(response.rate);
			SetRatingViewStar();
			SetRatingStar();
			alert_float('success', response.mess);
			$('#pet_after_rating').modal('hide');
		});
	}

	function preview_pet_after_btn(invoker) {
		"use strict";
		var id = $(invoker).attr('id');
		var rel_id = $(invoker).attr('rel_id');
		view_pet_after_file(id, rel_id);
	}

	function view_pet_after_file(id, rel_id) {
		"use strict";
		$('#pet_after_file_data').empty();
		$("#pet_after_file_data").load(admin_url + 'appointment/pet_after_file/' + id + '/' + rel_id, function(response, status, xhr) {
			if (status == "error") {
				alert_float('danger', xhr.statusText);
			}
		});
	}

	function close_modal_preview() {
		$('._project_file').modal('hide');
	}

	function delete_pet_after_attachment(id) {
		"use strict";
		if (confirm_delete()) {
			requestGet('appointment/delete_pet_after_attachment/' + id).done(function(success) {
				if (success == 1) {
					$("#pet_after_pv_file").find('[data-attachment-id="' + id + '"]').remove();
				}
			}).fail(function(error) {
				alert_float('danger', error.responseText);
			});
		}
	}

	function change_status_pet_after(invoker, id) {
		"use strict";
		$.post(admin_url + 'appointment/change_status_pet_after/' + invoker.value + '/' + id).done(function(reponse) {
			reponse = JSON.parse(reponse);
			window.location.href = admin_url + 'appointment/pet_after/' + id;
			alert_float('success', reponse.result);
		});
	}
</script>