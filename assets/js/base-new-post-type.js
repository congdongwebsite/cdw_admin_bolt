var baseNewPostType = function (self) {
  self.form;
  self.action;
  self.security;
  self.event = () => {
    $(".btn-save", self.form).on("click", function (e) {
      e.preventDefault();
      self.save(e);
    });
  };
  self.save = (e) => {
    if (!self.form.parsley().validate()) return;

    var form_data = getFormData(self.action, self.security, self.form);

    callAjaxLoading(
      form_data,
      (res) => {
        if (res.success) {
          showSuccessMessage(
            () => {
              window.location.href = $("#urlredirect").val();
            },
            res.data.msg,
            "Lưu thành công"
          );
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      self.form.parsley();
      self.security = $("#nonce", self.form).val();
      self.event();
    }
  };

  return self;
};
