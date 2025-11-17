$(function () {
  "use strict";

  $(document).ready(function () {
    newManageEmail.initialize();
  });
});

var newManageEmail = (function (self, base, details) {
  base.form = $("form.form-new-manage-email-small");
  base.action = "ajax_new-manage-email";

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
  details.modalHide = () => {
    DetailGia.set(0);
    DetailGiaHan.set(0);
  };

  details.createModel = () => {
    return {
      date: $("#date", details.modalName).val(),
      note: $("#note", details.modalName).val(),
      gia: DetailGia.getNumber(),
      gia_han: DetailGiaHan.getNumber(),
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
        date: row.date,
        note: row.note,
        gia: row.gia,
        gia_han: row.gia_han,
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

    initSelect2InetEmailPlans("inet_plan_id", base.form);

    $("#inet_plan_id", base.form).on("change", function () {
      var plan_id = $(this).val();
      if (!plan_id) {
        $("#title").val("");
        $("#account").val("");
        $("#hhd").val("");
        Gia.set(0);
        GiaHan.set(0);
        return;
      }

      $.ajax({
        url: objAdmin.ajax_url,
        type: "POST",
        data: {
          action: "ajax_get_inet_email_plan_detail",
          plan_id: plan_id,
          security: $("#nonce").val(),
        },
        dataType: "json",
        beforeSend: function () {
          base.form.addClass("admin-loading");
        },
        success: function (res) {
          if (res.success) {
            const plan = res.data;
            $("#title").val(plan.title);
            $("#account").val(plan.account);
            $("#hhd").val(plan.hhd);
            Gia.set(plan.gia);
            GiaHan.set(plan.gia_han);
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        complete: function () {
          base.form.removeClass("admin-loading");
        },
      });
    });

    [DetailGia, DetailGiaHan] = AutoNumeric.multiple(
      [details.modalName + " #gia", details.modalName + " #gia_han"],
      siteSettings.OptionAutoNumericAmountVND
    );

    [Gia, GiaHan] = AutoNumeric.multiple(
      [
        "form.form-new-manage-email-small" + " #gia",
        "form.form-new-manage-email-small" + " #gia_han",
      ],
      siteSettings.OptionAutoNumericAmountVND
    );
  };

  return self;
})({}, baseNewPostType({}), initDetail({}));
