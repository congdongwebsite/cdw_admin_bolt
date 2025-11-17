$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-billing").length > 0) billing.initialize();
  });
});
var billing = (function (self, base) {
  base.action = "ajax_get-client-billing";
  base.tableID = "#tb-data";
  base.context = $(".client-billing");

  base.column = [
    { data: "status", title: "Trạng thái" },
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
      render: dateBillingFormatter,
    },
    {
      targets: 4,
      render: numberFormatterAmountVND,
    },
  ];
  let i = 1;
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
    base.initialize("billing");
    initSelect2BillingStatus("billing-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
  };

  return self;
})({}, baseReport({}));
