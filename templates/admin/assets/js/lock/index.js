$(function () {
  "use strict";

  $(document).ready(function () {
    let form = $("form.form-lock-small");
    form.parsley();
    $(".btn-unlock", form).on("click", function (e) {
      e.preventDefault();
      if (!form.parsley().validate()) return;
      let security = $("#nf_lock", form).val();
      let encryption = new Encryption();
      let formData = getFormData("ajax_unlock", security, form);
      formData["lock-password"] = encryption.encrypt(
        formData["lock-password"],
        security
      );
      callAjaxLoading(
        formData,
        (res) => {
          if (res.success) {
            showSuccessMessage(
              () => {
                window.location.href = $("#urlredirect").val();
              },
              res.data.msg,
              "Mở khóa thành công"
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
