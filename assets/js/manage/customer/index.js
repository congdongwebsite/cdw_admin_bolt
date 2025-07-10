$(function () {
  "use strict";
  $(document).ready(function () {
    customer.initialize();
  });
});
var customer = (function (self, base) {
  base.action = "ajax_get-list-customer";
  base.actionDelete = "ajax_delete-list-customer";
  base.tableID = "#tb-data";
  base.security = $("#nonce").val();
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-user-plus" aria-hidden="true"></i>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.createUser();
      },
    },
  ];
  self.createUser = (e) => {
    let data = base.getSelectedRows();
    if (data.length != 1) {
      Swal.fire({
        title: "Vui lòng chọn 1 khách hàng để tạo Tài khoản!",
        text: "Vui lòng chọn khách hàng!",
        icon: "warning",
      });
      return;
    }
    id = -1;
    email = "";

    data.map((row, index) => {
      id = row.id;
      email = row.email;
    });

    if (email == "" || id == -1) {
      Swal.fire({
        title:
          "Vui lòng cập chật email cho khách hàng trước khi tạo tài khoản!",
        text: "Email không tồn tại!",
        icon: "warning",
      });
      return;
    }
    Swal.fire({
      title: "Bạn có chắc muốn tạo Tài khoản cho khách hàng này?",
      text: "Tài khoản đã có sẽ được tạo lại mật khẩu, bạn có chắc chắn muốn tạo không?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: id,
            email: email,
            action: "ajax_create-user-customer",
            security: base.security,
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.msg,
                html:
                  "Tài khoản: " +
                  res.data.user.username +
                  "<br>" +
                  "Mật khẩu: " +
                  res.data.user.password,
                icon: "success",
                showConfirmButton: false,
              });
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(msg);
          }
        );
      }
    });
  };
  base.column = [
    { data: "name", title: "Họ và tên" },
    { data: "phone", title: "Điện thoại" },
    { data: "email", title: "Email" },
    { data: "address", title: "Địa chỉ" },
    { data: "cmnd", title: "CMND / CCCD" },
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
    base.initialize("customer");
  };

  return self;
})({}, baseIndexPostType({}));
