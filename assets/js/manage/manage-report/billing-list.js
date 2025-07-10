$(function () {
  "use strict";
  $(document).ready(function () {
    reportBillingList.initialize();
  });
});

function drillDownBillingFormatter(data, type, row, meta) {
  if (row.urlredirectbilling) {
    return (
      '<a class="btn-detail-index" href="' +
      row.urlredirectbilling +
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

var reportBillingList = (function (self, base) {
  base.action = "ajax_get-manage-report-billing-list";
  base.context = $(".report-billing-list");
  base.tableID = "#tb-data";

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
  base.column = [
    { data: "status", title: "Trạng thái" },
    { data: "customer", title: "Khách hàng" },
    { data: "code", title: "Mã thanh toán" },
    { data: "date", title: "Ngày thanh toán" },
    { data: "note", title: "Nội dung thanh toán" },
    { data: "amount", title: "Tiền" },
  ];
  base.columnDefs = [
    {
      targets: 1,
      render: drillDownFormatter,
    },
    {
      targets: 2,
      render: drillDownBillingFormatter,
    },
    {
      targets: 3,
      render: dateFormatter,
    },
    {
      targets: 5,
      render: numberFormatterAmountVND,
    },
  ];
  let i = 1;

  self.check = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn danh sách thanh toán!",
        text: "Vui lòng chọn thanh toán!",
        icon: "warning",
      });
      return;
    }

    Swal.fire({
      title: "Bạn có chắc muốn đối soát thanh toán?",
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
            action: "ajax_check-billing",
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

  base.addEvent = () => {
    $(".btn-reload").on("click", (e) => {
      e.preventDefault();

      data = {
        from_date: $("#from-date", base.context).val(),
        until_date: $("#until-date", base.context).val(),
        billing_status: $("#billing-status", base.context).val(),
      };
      base.ajaxData = {
        ...base.ajaxData,
        ...data,
      };
      base.loadData();
    });
  };
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("reportBillingList");
    initSelect2BillingStatus("billing-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
  };

  return self;
})({}, baseReport({}));
