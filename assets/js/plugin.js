jQuery(function ($) {
  "use strict";
  jQuery(document).ready(function () {
    pluginFrontend.initialize();
  });
});

var pluginFrontend = (function (self) {
  self.action = "ajax_choose-plugin-frontend-client-cart";
  self.context = jQuery(".single-plugin");
  self.currentRequest;

  self.addToCart = (e) => {
    e.preventDefault();

    if (self.currentRequest) {
      self.currentRequest.abort();
    }

    if (typeof cdwObjects !== "undefined" && !cdwObjects.is_login) {
      jQuery("#modalLogin").modal("show");
      return;
    }
    Swal.fire({
      title: "Mua plugin",
      text: "Plugin bạn chọn sẽ được đưa vào giỏ hàng, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let id = jQuery(e.currentTarget).data("id");
        self.currentRequest = $.ajax({
          type: "POST",
          dataType: "json",
          url: cdwObjects.ajax_url,
          data: {
            action: self.action,
            security: cdwObjects.nonce,
            id: id,
          },
          beforeSend: function () {
            jQuery(e.currentTarget).addClass("congdongtheme-loading");
          },
          success: function (res) {
            if (res.success && res.data.cart_url) {
              window.location.href = res.data.cart_url;
            } else {
              Swal.fire("Lỗi", res.msg || "Không thể thêm vào giỏ hàng", "error");
            }
            jQuery(e.currentTarget).removeClass("congdongtheme-loading");
          },
          error: function () {
            Swal.fire("Lỗi", "Không thể thêm vào giỏ hàng", "error");
            jQuery(e.currentTarget).removeClass("congdongtheme-loading");
          },
        });
      }
    });
  };

  self.addEvent = () => {
    jQuery(self.context).on("click", ".add-to-cart-plugin", function (e) {
      self.addToCart(e);
    });
    jQuery(self.context).on("click", ".btn-modal-order", function (e) {
      self.addToCart(e);
    });
  };

  self.initialize = () => {
    self.addEvent();
  };

  return self;
})({});
