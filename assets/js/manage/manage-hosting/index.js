$(function () {
  "use strict";
  $(document).ready(function () {
    manageHosting.initialize();
  });
});
var manageHosting = (function (self, base) {
  base.action = "ajax_get-list-manage-hosting";
  base.actionDelete = "ajax_delete-list-manage-hosting";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "title", title: "Tiêu đề" },
    { data: "gia", title: "Giá" },
    { data: "gia_han", title: "Gia hạn" },
    { data: "note", title: "Ghi chú" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillDownFormatter,
    },
    {
      targets: [2, 3],
      render: numberFormatterAmountVND,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("manageHosting");
  };

  return self;
})({}, baseIndexPostType({}));
