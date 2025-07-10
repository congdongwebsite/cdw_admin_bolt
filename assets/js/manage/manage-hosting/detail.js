$(function () {
  "use strict";

  $(document).ready(function () {
    detailManageHosting.initialize();
  });
});

var detailManageHosting = (function (self, base, details) {
  base.form = $("form.form-detail-manage-hosting-small");
  base.action = "ajax_update-manage-hosting";
  base.actionDelete = "ajax_delete-manage-hosting";

  //detail
  details.tb = $("#tb-details");
  details.modalName = "#modal-add-detail";
  details.formName = "#modal-add-detail-form";
  details.column = [
    { data: "date", title: "Ngày" },
    { data: "gia", title: "Giá" },
    { data: "gia_han", title: "Gia hạn" },
    { data: "note", title: "Ghi chú" },
  ];
  details.columnDefs = [
    {
      targets: 0,
      render: dateFormatter,
    },
    {
      targets: [1, 2],
      render: numberFormatterAmountVND,
    },
  ];
  details.action = "ajax_load-manage-hosting-detail";
  details.security = base.security;
  details.id = $("#id", base.form).val();
  details.modalHide = () => {
    DetailGia.set(0);
    DetailGiaHan.set(0);
  };

  details.createModel = (data) => {
    return {
      id: data ? data.id : "",
      date: $("#date", details.modalName).val(),
      note: $("#note", details.modalName).val(),
      gia: DetailGia.getNumber(),
      gia_han: DetailGiaHan.getNumber(),
      change: true,
    };
  };
  details.bindingModel = (model) => {
    $("#date", details.modalName).val(model.date);
    $("#note", details.modalName).val(model.note);
    DetailGia.set(model.gia);
    DetailGiaHan.set(model.gia_han);
  };

  details.getData = () => {
    let items = [];

    details.api.data().each(function (row, index) {
      let item = {
        id: row.id,
        date: row.date,
        note: row.note,
        gia: row.gia,
        gia_han: row.gia_han,
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
    form_data["gia"] = Gia.getNumber();
    form_data["gia_han"] = GiaHan.getNumber();
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
    base.initialize();
    details.initialize();

    [Gia, GiaHan] = AutoNumeric.multiple(
      [
        "form.form-detail-manage-hosting-small" + " #gia",
        "form.form-detail-manage-hosting-small" + " #gia_han",
      ],
      siteSettings.OptionAutoNumericAmountVND
    );

    [DetailGia, DetailGiaHan] = AutoNumeric.multiple(
      [details.modalName + " #gia", details.modalName + " #gia_han"],
      siteSettings.OptionAutoNumericAmountVND
    );
    initSelect2HostingFeature("feature", base.form);
  };

  return self;
})({}, baseDetailPostType({}), initDetail({}));
