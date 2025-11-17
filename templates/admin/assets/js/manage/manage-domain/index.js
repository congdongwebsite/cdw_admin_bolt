$(function () {
  "use strict";
  $(document).ready(function () {
    manageDomain.initialize();
  });
});
var manageDomain = (function (self, base) {
  base.action = "ajax_get-list-manage-domain";
  base.actionDelete = "ajax_delete-list-manage-domain";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "stt", title: "STT" },
    { data: "title", title: "Tiêu đề" },
    { data: "gia", title: "Giá" },
    { data: "gia_han", title: "Gia hạn" },
    { data: "note", title: "Ghi chú" },
  ];
  base.columnDefs = [
    {
      targets: 1,
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
    base.initialize("manageDomain");
  };

  return self;
})({}, baseIndexPostType({}));
