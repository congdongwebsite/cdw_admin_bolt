$(function () {
  "use strict";
  $(document).ready(function () {
    manageEmail.initialize();
  });
});
var manageEmail = (function (self, base) {
  base.action = "ajax_get-list-manage-email";
  base.actionDelete = "ajax_delete-list-manage-email";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "title", title: "Tiêu đề" },
    { data: "gia", title: "Giá" },
    { data: "gia_han", title: "Gia hạn" },
    { data: "account", title: "Tài khoản" },
    { data: "hhd", title: "Dung lượng" },
    { data: "note", title: "Ghi chú" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillDownFormatter,
    },
    {
      targets: [1,2],
      render: numberFormatterAmountVND,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("manageEmail");
  };

  return self;
})({}, baseIndexPostType({}));
