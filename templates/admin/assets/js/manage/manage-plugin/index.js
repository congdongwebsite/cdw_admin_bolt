$(function () {
  "use strict";
  $(document).ready(function () {
    managePlugin.initialize();
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
var managePlugin = (function (self, base) {
  base.action = "ajax_get-list-manage-plugin";
  base.actionDelete = "ajax_delete-list-manage-plugin";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "image", title: "Ảnh" },
    { data: "title", title: "Tiêu đề" },
    { data: "code", title: "Code" },
    { data: "name", title: "Tên plugin" },
    { data: "module_version_name", title: "Module Version" },
    { data: "url", title: "URL" },
    { data: "price", title: "Giá" },
    { data: "type", title: "Loại" },
  ];
  base.columnDefs = [
    {
      targets: 1,
      render: drillDownFormatter,
    },
    {
      targets: 0,
      render: imageFormatter,
    },
    {
      targets: 6,
      render: numberFormatterAmountVND,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("managePlugin");
  };

  return self;
})({}, baseIndexPostType({}));
