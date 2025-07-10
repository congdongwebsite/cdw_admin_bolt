$(function () {
  "use strict";
  $(document).ready(function () {
    manageVersion.initialize();
  });
});
var manageVersion = (function (self, base) {
  base.action = "ajax_get-list-manage-version";
  base.actionDelete = "ajax_delete-list-manage-version";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "title", title: "Tiêu đề" },
    { data: "type", title: "Type" },
    { data: "name", title: "Name" },
    { data: "last_version", title: "Phiên bản cuối" },
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
    base.initialize("manageVersion");
  };

  return self;
})({}, baseIndexPostType({}));
