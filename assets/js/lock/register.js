$(function () {
  "use strict";
  $(document).ready(function () {
    let form = $("form.form-register-small");
    form.parsley();
    $(".btn-register", form).on("click", function (e) {
      e.preventDefault();
      if (!form.parsley().validate()) return;

      let security = $("#nf_register", form).val();
      let encryption = new Encryption();
      let formData = getFormData("ajax_register", security, form);
      formData["signup-password"] = encryption.encrypt(
        formData["signup-password"],
        security
      );

      formData["signup-password-re"] = encryption.encrypt(
        formData["signup-password-re"],
        security
      );

      let captcha = gRecaptcha.getRecaptcha("register");
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
