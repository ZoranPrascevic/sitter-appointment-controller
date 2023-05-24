(function ($) {
	"use strict";
	$('#reject_btn').on('click', function () {
		$('#approved_div').addClass('hide');
	});
	$('#approved_btn').on('click', function () {
		$('#reject_div').addClass('hide');
	});

	$('.selectpicker').selectpicker({});
})(jQuery);

function delete_campaign_attachment(id) {
	"use strict";
	if (confirm_delete()) {
		requestGet('appointment/delete_campaign_attachment/' + id).done(function (success) {
			if (success == 1) {
				$("#campaign_pv_file").find('[data-attachment-id="' + id + '"]').remove();
			}
		}).fail(function (error) {
			alert_float('danger', error.responseText);
		});
	}
}
function change_status_campaign(invoker, id_cp) {
	"use strict";
	$.post(admin_url + 'appointment/change_status_campaign/' + invoker.value + '/' + id_cp).done(function (reponse) {
		reponse = JSON.parse(reponse);
		window.location.href = admin_url + 'appointment/appointment_campaign/' + id_cp;
		alert_float('success', reponse.result);
	});
}