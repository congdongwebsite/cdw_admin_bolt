$(function () {
  "use strict";
  $(document).ready(function () {
    receipt.initialize();
  });
});

function drillReceiptDownFormatter(data, type, row, meta) {
  if (data) {
    return (
      '<a class="btn-detail-index" href="' +
      row.urlredirect +
      '">' +
      (data != "" ? data : "---") +
      "</a>" +
      (row.check
        ? '<i class="fa fa-check text-danger ml-3" aria-hidden="true"></i>'
        : "")
    );
  } else {
    return data;
  }
}
var receipt = (function (self, base) {
  base.action = "ajax_get-list-receipt";
  base.actionDelete = "ajax_delete-list-receipt";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-check" aria-hidden="true"></i>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.check();
      },
    },
  ];
  self.check = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn danh sách phiếu!",
        text: "Vui lòng chọn phiếu!",
        icon: "warning",
      });
      return;
    }

    Swal.fire({
      title: "Bạn có chắc muốn đối soát danh sách phiếu này?",
      text: "Chọn loại đối soát?",
      icon: "question",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Đã đối soát",
      denyButtonText: "Chưa đối soát",
      cancelButtonText: "Hủy",
    }).then((res) => {
      if (!res.isDismissed) {
        ids = [];

        data.map((row, index) => {
          ids.push(row.id);
        });
        let checked = res.value;

        callAjaxLoading(
          {
            ids: ids,
            checked: checked,
            action: "ajax_check-receipt",
            security: base.security,
          },
          (res) => {
            if (res.success) {
              showSuccessMessage(
                () => {
                  base.loadData();
                },
                res.data.msg,
                "Kiểm tra thành công"
              );
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(msg);
          }
        );
      }
    });
  };

  base.column = [
    { data: "code", title: "Mã" },
    { data: "date", title: "Ngày" },
    { data: "type", title: "Loại" },
    { data: "amount", title: "Tiền" },
    { data: "note", title: "Ghi chú" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillReceiptDownFormatter,
    },
    {
      targets: 1,
      render: dateFormatter,
    },
    {
      targets: 3,
      render: numberFormatterAmountVND,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("receipt");
  };

  return self;
})({}, baseIndexPostType({}));
