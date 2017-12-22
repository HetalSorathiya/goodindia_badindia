ProjectName.modules.LoginListing = {
    init: function () {
        this.selectAllCheckbox();
        this.forgotpassword();
    },
    selectAllCheckbox: function () {
        $('#selectall').click(function () {
            if ($(this).is(':checked') === true) {
                $('.content-checkbox').attr('checked', true);
            } else {
                $('.content-checkbox').attr('checked', false);
            }
        });
    },
    forgotpassword: function () {
        $(".fail_error").hide();
        $("#admin_email").val('');
        $('#submit').click(function () {
            $(".none").removeClass("gerror_message");

            var admin_email = $("#admin_email").val();

            var email_pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if (admin_email == '') {
                $("#admin_email").addClass("gerror_message");
                return false;
            } else if (admin_email != '' && email_pattern.test(admin_email) == false) {
                $("#admin_email").addClass("gerror_message");
                return false;
            } else {
                var postdata = {};
                var obj = $(this);
                postdata['admin_email'] = admin_email;

                jQuery.post(BASE_PATH + '/admin/login/forgotpassword', postdata, function (result) {
                    var resp = jQuery.parseJSON(result);
                    if (resp.msg == 'fail') {
                        $(".fail_error").show();
                        return false;
                    } else {
                        window.setTimeout(function () {
                            window.location = BASE_PATH + '/admin/login';
                        }, 1000);
                    }
                });
            }
        });
    }
};