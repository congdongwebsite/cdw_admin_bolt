$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-plugin").length > 0) plugin.initialize();
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
var plugin = (function (self, base) {
  base.action = "ajax_get-client-plugin";
  base.tableID = "#tb-data";
  base.context = $(".client-plugin");
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Plugin</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byPlugin();
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
  self.byPlugin = (e) => {
    window.location.href = $("#url-choose").val();
  };

  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn plugin muốn gia hạn!",
        text: "Vui lòng chọn plugin!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn plugin",
      text: "Bạn có chắc muốn gia hạn cho các plugin vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-plugin-client-cart",
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
    { data: "date", title: "Ngày mua" },
    { data: "expiry_date", title: "Hết hạn" },
    { data: "image", title: "Ảnh" },
    { data: "title", title: "Tiêu đề" },
    { data: "license", title: "Giấy phép" },
    { data: "info", title: "Thông tin" },
    { data: "version", title: "Version" },
    { data: "price", title: "Giá" },
  ];
  base.columnDefs = [
    {
      targets: [0,1],
      render: dateFormatter,
    },
    {
      targets: 2,
      // render: drillDownFormatter,
    },
    {
      targets: 2,
      render: imageFormatter,
    },
    {
      targets: 7,
      render: numberFormatterAmountVND,
    },
  ];
  let i = 1;
  base.addEvent = () => {
    $(".btn-reload").on("click", (e) => {
      e.preventDefault();

      data = {
        from_date: $("#from-date", base.context).val(),
        until_date: $("#until-date", base.context).val(),
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
    base.initialize("plugin");
    initSelect2PluginType("type", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
  };

  return self;
})({}, baseReport({}));
