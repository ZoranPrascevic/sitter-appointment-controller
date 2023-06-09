(function ($) {
    "use strict";
    appValidateForm($('.transfer-form'), {
        firstname: 'required',
        username: 'required',
        password: {
            required: {
                depends: function (element) {
                    return ($('input[name="isedit"]').length == 0) ? true : false
                }
            }
        },
        email: {
            required: true,
            email: true,
            remote: {
                url: site_url + "admin/misc/staff_email_exists",
                type: 'post',
                data: {
                    email: function () {
                        return $('input[name="email"]').val();
                    },
                    memberid: function () {
                        return $('input[name="memberid"]').val();
                    }
                }
            }
        },
        staff_identifi: {
            required: true,
            remote: {
                url: site_url + "admin/hrm/hr_code_exists",
                type: 'post',
                data: {
                    staff_identifi: function () {
                        return $('input[name="staff_identifi"]').val();
                    },
                    memberid: function () {
                        return $('input[name="memberid"]').val();
                    }
                }
            }
        }
    });
})(jQuery);

function action_transfer_hr() {
    "use strict";
    $('.transfer-form').submit();
    $.post(admin_url + 'appointment/action_transfer_hr/' + $('input[name="cd_id"]').val()).done(function (response) {
        response = JSON.parse(response);
    });
}