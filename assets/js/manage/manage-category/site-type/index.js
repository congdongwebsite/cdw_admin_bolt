$(function () {
  "use strict";
  $(document).ready(function () {
    siteType.initialize();
  });
});
var siteType = (function (self, base) {
  base.action = "ajax_get-list-site-type";
  base.actionDelete = "ajax_delete-list-site-type";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "name", title: "Loại" },
    { data: "count", title: "Số lượng" },
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
    base.initialize("siteType");
  };

  return self;
})({}, baseIndexPostType({}));
