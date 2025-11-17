$(function () {
  "use strict";

  $(document).ready(function () {
    newVPS.initialize();
  });
});

var newVPS = (function (self, base) {
  base.form = $("form.form-new-vps-small");
  base.action = "ajax_new-vps";
  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      base.initialize();

      [ServicePrice] = AutoNumeric.multiple(
        ["form.form-new-vps-small #service-price"],
        siteSettings.OptionAutoNumericAmountVND
      );
    }
  };
  base.save = (e) => {
    if (!base.form.parsley().validate()) return;

    var form_data = getFormData(base.action, base.security, base.form);
    form_data["service-price"] = ServicePrice.getNumber();
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
  initdatepickerlink("service-buy-date", "service-expiry-date", base.form);
  return self;
})({}, baseNewPostType({}));
