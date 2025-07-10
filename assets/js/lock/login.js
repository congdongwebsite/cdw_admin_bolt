$(function () {
  "use strict";
  $(document).ready(function () {
    let form = $("form.form-auth-small");
    form.parsley();
    $(".btn-login", form).on("click", function (e) {
      e.preventDefault();
      if (!form.parsley().validate()) return;
      let security = $("#nf_login", form).val();

      let encryption = new Encryption();
      let formData = getFormData("ajax_login", security, form);
      formData["signin-password"] = encryption.encrypt(
        formData["signin-password"],
        security
      );
      let captcha = gRecaptcha.getRecaptcha("login");
      formData[captcha.key] = captcha.value;

      callAjaxLoading(
        formData,
        (res) => {
          if (res.success) {
            showSuccessMessage(
              () => {
                window.location.href = res.data.urlredirect;
              },
              "Đăng nhập thành công, bạn sẽ được chuyển sang trang chủ!",
              "Đăng nhập thành công"
            );
          } else {
            showErrorMessage(res.data.msg);
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });
  });
});
