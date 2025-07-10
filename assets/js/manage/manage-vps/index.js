$(function () {
  "use strict";
  $(document).ready(function () {
    manageVPS.initialize();
  });
});
var manageVPS = (function (self, base) {
  base.action = "ajax_get-list-vps";
  base.actionDelete = "ajax_delete-list-vps";
  base.tableID = "#tb-data";

  base.column = [
    { data: "ip", title: "IP" },
    { data: "port", title: "Post" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
    { data: "cpu", title: "CPU" },
    { data: "ram", title: "RAM" },
    { data: "hhd", title: "HHD" },
    { data: "supplier_name", title: "Nhà cung cấp" },
    { data: "service_type", title: "Gói" },
    { data: "url", title: "Website" },
    { data: "supplier_user", title: "Tài khoản" },
    { data: "supplier_pass", title: "Mật khẩu" },
    { data: "service_buy_date", title: "Ngày mua" },
    { data: "service_expiry_date", title: "Ngày hết hạn" },
    { data: "service_price", title: "Giá" },
    { data: "buyer_name", title: "Họ và tên" },
    { data: "buyer_email", title: "Email" },
    { data: "buyer_phone", title: "Điện thoại" },
    { data: "buyer_address", title: "Địa chỉ" },
    { data: "buyer_card_id", title: "CMND/CCCD" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillDownFormatter,
    },
    {
      targets: [12, 13],
      render: dateFormatter,
    },
  ];
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("manageVPS");
  };

  return self;
})({}, baseIndexPostType({}));
