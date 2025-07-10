$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-hosting").length > 0) hosting.initialize();
  });
});
function drillDownHostingFormatter(data, type, row, meta) {
  if (row.urlHosting) {
    return (
      ' <a class="btn-detail-index btn btn-primary" target="_blank" title="Đăng nhập server" href="' +
      row.urlHosting +
      '">Đăng nhập' +
      "</a>"
    );
  } else {
    return data;
  }
}
var hosting = (function (self, base) {
  base.action = "ajax_get-client-hosting";
  base.tableID = "#tb-data";
  base.context = $(".client-hosting");
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Hosting</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byHosting();
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
  self.byHosting = (e) => {
    window.location.href = $("#url-choose").val();
  };
  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn hosting muốn gia hạn!",
        text: "Vui lòng chọn hosting!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn hosting",
      text: "Bạn có chắc muốn gia hạn cho các hosting vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-hosting-client-cart",
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
    { data: "type_label", title: "Gói" },
    // { data: "buy_date", title: "Thời gian mua" },
    // { data: "expiry_date", title: "Thời gian hết hạn" },
    { data: "price", title: "Giá" },
    { data: "ip", title: "IP" },
    //{ data: "port", title: "Port" },
    { data: "user", title: "Tài khoản" },
    // { data: "pass", title: "Mật khẩu" },
    // { data: "cpu", title: "CPU" },
    // { data: "ram", title: "RAM" },
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
      targets: 5,
      render: drillDownHostingFormatter,
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
    base.initialize("hosting");
    initSelect2DomainStatus("domain-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
  };

  return self;
})({}, baseReport({}));
