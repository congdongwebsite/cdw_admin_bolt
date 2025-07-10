$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-email").length > 0) email.initialize();
  });
});
function drillDownEmailFormatter(data, type, row, meta) {
  if (row.urlEmail) {
    return (
      ' <a class="btn-detail-index btn btn-primary" target="_blank" title="Đăng nhập Email" href="' +
      row.urlEmail +
      '">Đăng nhập' +
      "</a>"
    );
  } else {
    return data;
  }
}
var email = (function (self, base) {
  base.action = "ajax_get-client-email";
  base.tableID = "#tb-data";
  base.context = $(".client-email");
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Email</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byEmail();
      },
    },
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Gia Hạn</pan>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.addToCart();
      },
    },
  ];
  self.byEmail = (e) => {
    window.location.href = $("#url-choose").val();
  };
  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn email muốn gia hạn!",
        text: "Vui lòng chọn email!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn email",
      text: "Bạn có chắc muốn gia hạn cho các email vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-email-client-cart",
            security: $("#index-nonce").val(),
          },
          (res) => {
            if (res.success) {
              $(".top-navbar-cart").trigger("update-cart");
              Swal.fire({
                title: "Thêm vào giỏ hàng thành công",
                text: "Bạn muốn mở giỏ hàng?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Có",
                cancelButtonText: "Không",
              }).then((res2) => {
                if (res2.value) {
                  window.location.href = res.data.cart_url;
                }
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
    { data: "status", title: "Trạng thái" },
    { data: "email-type_label", title: "Gói" },
    // { data: "buy_date", title: "Thời gian mua" },
    // { data: "expiry_date", title: "Thời gian hết hạn" },
    { data: "price", title: "Giá" },
    { data: "url_admin", title: "URL Admin" },
    { data: "url_client", title: "URL Client" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
    // { data: "account", title: "Account" },
    // { data: "hhd", title: "Dung lượng" },
    { data: "action", title: "Hành động" },
  ];
  base.columnDefs = [
    // {
    //   targets: [2, 3],
    //   render: dateFormatter,
    // },
    {
      targets: 2,
      render: numberFormatterAmountVND,
    },
    {
      targets: 7,
      render: drillDownEmailFormatter,
      responsivePriority: 1,
    },
  ];
  let i = 1;
  base.addEvent = () => {
    $(".btn-reload").on("click", (e) => {
      e.preventDefault();

      data = {
        from_date: $("#from-date", base.context).val(),
        until_date: $("#until-date", base.context).val(),
        from_expiry_date: $("#from-expiry-date", base.context).val(),
        until_expiry_date: $("#until-expiry-date", base.context).val(),
        domain_status: $("#domain-status", base.context).val(),
      };
      base.ajaxData = {
        ...base.ajaxData,
        ...data,
      };
      base.loadData();
    });
  };
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("email");
    initSelect2DomainStatus("domain-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
  };

  return self;
})({}, baseReport({}));
