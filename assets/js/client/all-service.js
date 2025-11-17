$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-all-service").length > 0) clientAllService.initialize();
  });
});

function actionFormatter(data, type, row, meta) {
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
var clientAllService = (function (self, base) {
  base.action = "ajax_get-client-all-service";
  base.tableID = "#tb-data";
  base.context = $(".client-all-service");
  base.serverSide = true;
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Gia Hạn</pan>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.addToCart();
      },
    },
  ];

  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn dịch vụ muốn gia hạn!",
        text: "Vui lòng chọn dịch vụ!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn dịch vụ",
      text: "Bạn có chắc muốn gia hạn cho các dịch vụ vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-service-client-cart",
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
    { data: "type_label", title: "Loại dịch vụ" },
    { data: "name", title: "Tên dịch vụ" },
    { data: "buy_date", title: "Ngày đăng ký" },
    { data: "expiry_date", title: "Ngày hết hạn" },
    { data: "amount", title: "Thành tiền" },
    { data: "status", title: "Trạng thái" },
    // { data: "action", title: "Hành động" },
  ];
  base.columnDefs = [
    {
      targets: 1,
      render: drillDownFormatter,
    },
    {
      targets: 4,
      render: numberFormatterAmountVND,
    },
    {
      targets: [2, 3],
      render: dateFormatter,
    },
    // {
    //   targets: -1,
    //   render: actionFormatter,
    // },
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
        status: $("#status", base.context).val(),
        type: $("#type", base.context).val(),
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
    base.initialize("clientAllService");
    $('#status', base.context).on('change', function () {
      $('.btn-reload', base.context).trigger("click");
    });
    initSelect2DomainStatus("status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
  };

  return self;
})({}, baseReport({}));
