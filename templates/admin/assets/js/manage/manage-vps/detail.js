$(function () {
  "use strict";

  $(document).ready(function () {
    detailVPS.initialize();
  });
});

var detailVPS = (function (self, base) {
  base.form = $("form.form-detail-vps-small");
  base.action = "ajax_update-vps";
  base.actionDelete = "ajax_delete-vps";
  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      base.initialize();
      [ServicePrice] = AutoNumeric.multiple(
        ["form.form-detail-vps-small #service-price"],
        siteSettings.OptionAutoNumericAmountVND
      );
    }
  };

  base.save = (e) => {
    if (!base.form.parsley().validate()) return;

    var form_data = getFormData(base.action, base.security, base.form);
    form_data["service-price"] = AutoNumeric.getNumber(
      "form.form-detail-vps-small #service-price"
    );
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
})({}, baseDetailPostType({}));
