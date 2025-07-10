$(function () {
  "use strict";
  $(document).ready(function () {
    theme.initialize();
  });
});

function imageFormatter(data, type, row, meta) {
  if (row.image) {
    return (
      '<a href="' +
      row.image +
      '" target="_blank" rel="noopener noreferrer"><img width="32" src="' +
      row.image +
      '"  alt="' +
      row.title +
      '"></a>'
    );
  } else {
    return data;
  }
}
var theme = (function (self, base) {
  base.action = "ajax_get-manage-report-theme-list";
  base.tableID = "#tb-data";
  base.context = $(".report-theme-list");
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Theme</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byTheme();
      },
    },
  ];
  self.byTheme = (e) => {
    window.location.href = $("#url-choose").val();
  };
  base.column = [
    { data: "date", title: "Date" },
    { data: "customer", title: "Khách hàng" },
    { data: "image", title: "Ảnh" },
    { data: "title", title: "Tiêu đề" },
    { data: "info", title: "Thông tin" },
    { data: "price", title: "Giá" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: dateFormatter,
    },
    {
      targets: 1,
      render: drillDownFormatter,
    },
    {
      targets: 2,
      render: imageFormatter,
    },
    {
      targets: 5,
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
        type: $("#type", base.context).val(),
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
    base.initialize("theme");
    initSelect2SiteType("type", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
  };

  return self;
})({}, baseReport({}));
