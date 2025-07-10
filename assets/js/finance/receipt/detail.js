$(function () {
  "use strict";

  $(document).ready(function () {
    detailReceipt.initialize();
  });
});

var detailReceipt = (function (self, base, details) {
  base.form = $("form.form-detail-receipt-small");
  base.action = "ajax_update-receipt";
  base.actionDelete = "ajax_delete-receipt";

  //detail
  details.tb = $("#tb-details");
  details.modalName = "#modal-add-detail";
  details.formName = "#modal-add-detail-form";
  details.column = [
    { data: "note", title: "Nội dung thanh toán" },
    { data: "amount", title: "Tiền" },
  ];
  details.columnDefs = [
    {
      targets: 1,
      render: numberFormatterAmountVND,
    },
  ];
  details.action = "ajax_load-receipt-detail";
  details.security = base.security;
  details.id = $("#id", base.form).val();
  details.modalHide = () => {
    Amount.set(0);
  };

  details.createModel = (data) => {
    return {
      id: data ? data.id : "",
      note: $("#note", details.modalName).val(),
      amount: Amount.getNumber(),
      change: true,
    };
  };
  details.bindingModel = (model) => {
    $("#note", details.modalName).val(model.note);
    Amount.set(model.amount);
  };

  details.getData = () => {
    let items = [];

    details.api.data().each(function (row, index) {
      let item = {
        id: row.id,
        note: row.note,
        amount: row.amount,
        change: row.change,
      };

      items.push(item);
    });

    return items;
  };
  //end detail

  base.save = (e) => {
    if (!base.form.parsley().validate()) return;

    var form_data = getFormData(base.action, base.security, base.form);
    form_data["details"] = details.getData();
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
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      base.initialize();
      details.initialize();

      [Amount] = AutoNumeric.multiple(
        [details.modalName + " #amount"],
        siteSettings.OptionAutoNumericAmountVND
      );
      initSelect2FinanceType("type", base.form);
    }
  };

  return self;
})({}, baseDetailPostType({}), initDetail({}));
