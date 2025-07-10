$(function () {
  "use strict";

  $(document).ready(function () {
    newVersion.initialize();
  });
});

var newVersion = (function (self, base, details) {
  base.form = $("form.form-new-manage-version-small");
  base.action = "ajax_new-manage-version";

  //detail
  details.tb = $("#tb-details");
  details.modalName = "#modal-add-detail";
  details.formName = "#modal-add-detail-form";
  details.column = [
    { data: "date", title: "Ngày" },
    { data: "version", title: "Phiên bản" },
    { data: "url", title: "Link" },
    { data: "note", title: "Ghi chú" },
  ];
  details.columnDefs = [
    {
      targets: 0,
      render: dateFormatter,
    },
  ];
  details.modalHide = () => {};

  details.createModel = () => {
    return {
      date: $("#date", details.modalName).val(),
      note: $("#note", details.modalName).val(),
      version: $("#version", details.modalName).val(),
      url: $("#url", details.modalName).val(),
    };
  };
  details.bindingModel = (model) => {
    $("#date", details.modalName).val(model.date);
    $("#note", details.modalName).val(model.note);
    $("#version", details.modalName).val(model.version);
    $("#url", details.modalName).val(model.url);
  };

  details.getData = () => {
    let items = [];

    details.api.data().each(function (row, index) {
      let item = {
        date: row.date,
        note: row.note,
        version: row.version,
        url: row.url,
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
    form_data["type"] = $("#type", base.form).val();
    form_data["name"] = $("#name", base.form).val();
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
    initSelect2VersionType("type", base.form);
  };

  return self;
})({}, baseNewPostType({}), initDetail({}));
