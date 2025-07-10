$(function () {
  "use strict";

  $(document).ready(function () {
    let form = $("form.form-forgot-password-small");
    form.parsley();

    $(".btn-forgot-password", form).on("click", function (e) {
      e.preventDefault();
      if (!form.parsley().validate()) return;

      let email = $("#forgot-password-email", form).val();
      let security = $("#nf_forgot_password", form).val();
      let data = {
        action: "ajax_forgot-password",
        email: email,
        security: security,
      };
      let captcha = gRecaptcha.getRecaptcha("forgot-password");
      data[captcha.key] = captcha.value;

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            showSuccessMessage(
              () => {
                window.location.href =
                  $("#urlredirect").val() + "&username=" + email;
              },
              res.data.msg,
              "Tạo lại mật khẩu thành công"
            );
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });
  });
});
