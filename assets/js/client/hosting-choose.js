$(function () {
  "use strict";
  $(document).ready(function () {
    hosting_choose.initialize();
  });
});
var hosting_choose = (function (self) {
  self.action = "ajax_choose-hosting-client-cart";
  self.context = $(".client-hosting-choose");

  self.addToCart = (e) => {
    e.preventDefault();
    Swal.fire({
      title: "Mua hosting",
      text: "Hosting bạn chọn sẽ được đưa vào giỏ hàng, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let hid = $(e.currentTarget).data().hid;
        callAjaxLoading(
          {
            id: hid,
            action: self.action,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              $(".top-navbar-cart").trigger("update-cart");
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

  self.addEvent = () => {
    $(".btn-choose").on("click", (e) => {
      self.addToCart(e);
    });
  };

  self.initialize = () => {
    self.addEvent();
  };

  return self;
})({});
