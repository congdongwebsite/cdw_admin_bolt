$(function () {
  "use strict";
  $(document).ready(function () {
    financeType.initialize();
  });
});
var financeType = (function (self, base) {
  base.action = "ajax_get-list-finance-type";
  base.actionDelete = "ajax_delete-list-finance-type";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "name", title: "Loại" },
    { data: "note", title: "Ghi chú" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillDownFormatter,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("financeType");
  };

  return self;
})({}, baseIndexPostType({}));
