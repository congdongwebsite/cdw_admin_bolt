$(function () {
  "use strict";
  $(document).ready(function () {
    manageSite.initialize();
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
var manageSite = (function (self, base) {
  base.action = "ajax_get-list-manage-site";
  base.actionDelete = "ajax_delete-list-manage-site";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();

  base.column = [
    { data: "image", title: "Ảnh" },
    { data: "title", title: "Tiêu đề" },
    { data: "name", title: "Tên giao diện" },
    { data: "url", title: "URL" },
    { data: "price", title: "Giá" },
    { data: "username", title: "Tài khoản" },
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
      targets: 4,
      render: numberFormatterAmountVND,
    },
  ];
  base.order = [[0, "asc"]];
  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("manageSite");
  };

  return self;
})({}, baseIndexPostType({}));
