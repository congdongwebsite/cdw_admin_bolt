$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-domain").length > 0) mdomain.initialize();
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
  let buttons = `<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <a href="` +
    row.urlUpdateDNS +
    `" target="_blank" class="btn btn-sm btn-secondary">DNS</a>
    <a href="` +
    row.urlUpdateRecord +
    `" target="_blank" class="btn btn-sm btn-primary">Bản ghi</a>`;

  if (row.inet_domain_id) {
    if (row.privacy_protection_status) {
      buttons += `<button type="button" class="btn btn-sm btn-danger btn-toggle-privacy" data-id="${row.id}" data-action="unprivacy">Tắt bảo vệ</button>`;
    } else {
      buttons += `<button type="button" class="btn btn-sm btn-success btn-toggle-privacy" data-id="${row.id}" data-action="privacy">Bật bảo vệ</button>`;
    }
  }
  buttons += `</div>`;
  return buttons;
}
var mdomain = (function (self, base) {
  base.action = "ajax_get-client-domain";
  base.tableID = "#tb-data";
  base.context = $(".client-domain");
  base.buttons = [
    ...base.buttons,
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
    { data: "url", title: "Website" },
    { data: "price", title: "Giá" },
    // { data: "buy_date", title: "Thời gian mua" },
    // { data: "expiry_date", title: "Thời gian hết hạn" },
    //{ data: "url_dns", title: "URL DNS" },
    // { data: "ip", title: "IP" },
    { data: "customer", title: "Khách hàng" },
    { data: "action", title: "Hành động" },
  ];
  base.columnDefs = [
    {
      targets: 1,
      render: drillDownDomainFormatter,
    },
    {
      targets: 2,
      render: numberFormatterAmountVND,
    },
    // {
    //   targets: [3, 4],
    //   render: dateFormatter,
    // },
    {
      targets: -2,
      render: drillDownFormatter,
    },
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

    base.table.on("click", ".btn-toggle-privacy", function (e) {
      e.preventDefault();
      var domain_id = $(this).data("id");
      var action = $(this).data("action"); // 'privacy' or 'unprivacy'
      var action_text = action === 'privacy' ? 'Bật' : 'Tắt';
      var confirm_text = action === 'privacy' ? 'bật bảo vệ quyền riêng tư' : 'tắt bảo vệ quyền riêng tư';
      var ajax_action = action === 'privacy' ? 'ajax_client_domain_privacy_protection' : 'ajax_client_domain_unprivacy_protection';

      Swal.fire({
        title: `Bạn có chắc chắn muốn ${confirm_text} cho tên miền này?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: action_text,
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          callAjaxLoading(
            {
              action: ajax_action,
              security: $("#nonce").val(),
              id: domain_id,
            },
            (res) => {
              if (res.success) {
                showSuccessMessage(() => {
                  base.loadData(); // Reload table to reflect changes
                }, res.data.msg);
              } else {
                showErrorMessage(res.data.msg);
              }
            },
            (msg) => {
              showErrorMessage(msg);
            }
          );
        }
      });
    });
  };
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("mdomain");
    initSelect2DomainStatus("domain-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
  };

  return self;
})({}, baseReport({}));
