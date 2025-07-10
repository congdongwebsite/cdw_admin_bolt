$(function () {
  "use strict";
  $(document).ready(function () {
    report.initialize();
  });
});
function drillDownDomainFormatter(data, type, row, meta) {
  if (row.urlDomain) {
    return (
      (data != "" ? data : "---") +
      ' <a class="btn-detail-index" target="_blank" title="Đăng nhập DNS" href="' +
      row.urlDomain +
      '"><i class="fa fa-globe text-danger" aria-hidden="true"></i>' +
      "</a>"
    );
  } else {
    return data;
  }
}
function actionDomainFormatter(data, type, row, meta) {
  return (
    `<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <a href="` +
    row.urlUpdateDNS +
    `" target="_blank" class="btn btn-sm btn-secondary">DNS</a>
    <a href="` +
    row.urlUpdateRecord +
    `" target="_blank" class="btn btn-sm btn-primary">Bản ghi</a>  
  </div>`
  );
}
var report = (function (self, base) {
  base.action = "ajax_get-manage-report-index";
  base.tableID = "#tb-data";
  base.context = $(".report-index");

  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Gửi thông báo</pan>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.sendEmail();
      },
    },
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Domain</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byDomain();
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

  self.byDomain = (e) => {
    window.location.href = $("#url-choose").val();
  };
  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn domain muốn gia hạn!",
        text: "Vui lòng chọn domain!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn Domain",
      text: "Bạn có chắc muốn gia hạn cho các domain vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-domain-client-cart",
            security: objAdmin.nonce,
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
  self.sendEmail = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn dịch vụ muốn gửi thông báo!",
        text: "Vui lòng chọn!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gửi thông báo gia hạn",
      text: "Bạn có chắc muốn gửi thông báo cho các domain vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_send-email-domain",
            security: base.security,
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: "Đã thực hiện gửi thành công!",
                text: res.data.msg,
                icon: "info",
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
    { data: "customer", title: "Khách hàng" },
    { data: "url", title: "Website" },
    { data: "price", title: "Giá" },
    // { data: "buy_date", title: "Thời gian mua" },
    // { data: "expiry_date", title: "Thời gian hết hạn" },
    //{ data: "url_dns", title: "URL DNS" },
    { data: "ip", title: "IP" },
    { data: "action", title: "Hành động" },
  ];
  base.columnDefs = [
    {
      targets: 2,
      render: drillDownDomainFormatter,
    },
    {
      targets: 1,
      render: drillDownFormatter,
    },
    {
      targets: 3,
      render: numberFormatterAmountVND,
    },
    // {
    //   targets: [4, 5],
    //   render: dateFormatter,
    // },
    {
      targets: -1,
      render: actionDomainFormatter,
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
    base.initialize("report");
    initSelect2DomainStatus("domain-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
  };

  return self;
})({}, baseReport({}));
