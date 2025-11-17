$(function () {
  "use strict";
  $(document).ready(function () {
    server.initialize();
  });
});

var server = (function (self) {
  self.action = "ajax_choose-hosting-frontend-client-cart";
  self.actionInfo = "ajax_info-hosting-frontend";
  self.context = $(".price-by-server");
  self.currentRequest;

  self.addToCart = (e) => {
    e.preventDefault();

    if (self.currentRequest) {
      self.currentRequest.abort();
    }

    if (!cdwObjects.is_login) {
      $("#modalLogin").modal("show");
      return;
    }
    Swal.fire({
      title: "Mua hosting",
      text: "Hosting bạn chọn sẽ được đưa vào giỏ hàng, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let idd = $(e.currentTarget).data().idh;
        callAjaxLoading(
          {
            id: idd,
            action: self.action,
            security: cdwObjects.nonce,
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title
                  ? res.data.title
                  : "Thêm vào giỏ hàng thành công",
                text: res.data.msg,
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

  self.infoDomain = (e) => {
    let domain = $(e.currentTarget).data().domain;

    let data = {
      action: self.actionInfo,
      security: cdwObjects.nonce,
      domain: domain,
    };
    self.currentRequest = $.ajax({
      type: "POST",
      dataType: "json",
      url: cdwObjects.ajax_url,
      data: data,
      beforeSend: function () {
        $(e.currentTarget).addClass("congdongtheme-loading");
      },
      success: function (res) {
        if (res.success) {
          let template = window.wp.template(res.data.template);
          template = template(res.data.info);
          let title =
            "TÊN MIỀN " +
            domain.toUpperCase() +
            (res.data.info.available == "available"
              ? '<span class="text-success ml-2"> CÓ THỂ ĐĂNG KÝ</span>'
              : '<span class="text-danger ml-2"> ĐÃ SỞ HỮU</span>');
          Swal.fire({
            title: title,
            html: template,
            width: 600,
          });
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        $(e.currentTarget).removeClass("congdongtheme-loading");
      },
      error: function (jqXHR, exception) {
        var msg = "";
        if (jqXHR.status === 400) {
          msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status === 0) {
          msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status == 404) {
          msg = "Requested page not found. [404]";
        } else if (jqXHR.status == 500) {
          msg = "Internal Server Error [500].";
        } else if (exception === "parsererror") {
          msg = "Requested JSON parse failed.";
        } else if (exception === "timeout") {
          msg = "Time out error.";
        } else if (exception === "abort") {
          msg = "Ajax request aborted.";
        } else {
          msg = "Uncaught Error.\n" + jqXHR.responseText;
        }
        console.log("msg", msg);

        $(e.currentTarget).removeClass("congdongtheme-loading");
      },
    });
  };
  self.addEvent = () => {
    // $(self.context).on("click", ".btn-info-domain", function (e) {
    //   e.preventDefault();
    //   self.infoDomain(e);
    // });

    $(self.context).on("click", ".btn-buy", function (e) {
      e.preventDefault();
      self.addToCart(e);
    });
  };

  self.initialize = () => {
    self.addEvent();
  };

  return self;
})({});
