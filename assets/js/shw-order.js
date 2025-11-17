jQuery(function ($) {
  "use strict";
  $(document).ready(function () {
    cart.initialize();
  });
});
var cart = (function (self) {
  self.actionCartCheckout = "ajax_client-cart-checkout";
  self.actionCheckout = "ajax_client-checkout_frontend";
  self.actionPaymentMomo = "ajax_client-checkout-payment-momo_frontend";
  self.actionCheckPaymentMomo = "ajax_client-checkout-check-payment-momo_frontend";
  self.context = $(".client-cart");
  self.security = $("#nonce").val();
  self.action = "ajax_get-list-cart-main";
  let shw_order_refresh_debounce_timer;
  self.orderId = 0;
  self.templateCountDown;
  self.time_out;
  self.countDown;

  self.update = (e, refresh = true) => {
    let data = [];
    $(".cartItems .basket-product ", self.context).map((index, value) => {
      data = [
        ...data,
        {
          id: $(value).data().item,
          quantity: $(".quantity-field", $(value)).val(),
        },
      ];
    });
    if (!refresh) {
      callAjax(
        {
          data: data,
          action: "ajax_client-cart-update",
          security: self.security,
        },
        (res) => {
          if (res.success) {
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
      return;
    }
    Swal.fire({
      title: "Cập nhật giỏ hàng",
      text: "Bạn có muốn cập nhật giỏ hàng?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            data: data,
            action: "ajax_client-cart-update",
            security: self.security,
          },
          (res) => {
            if (res.success) {
              self.refresh();
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
  self.redirectCheckout = (e) => {
    let data = [];
    $(".cartItems .basket-product ", self.context).map((index, value) => {
      data = [
        ...data,
        {
          id: $(value).data().item,
          quantity: $(".quantity-field", $(value)).val(),
        },
      ];
    });

    // Swal.fire({
    //   title: "Cập nhật giỏ hàng",
    //   text: "Bạn có muốn cập nhật giỏ hàng?",
    //   icon: "question",
    //   showCancelButton: true,
    //   confirmButtonText: "Có",
    //   cancelButtonText: "Không",
    // }).then((res) => {
    //   if (res.value) {
    callAjaxLoading(
      {
        data: data,
        action: "ajax_client-cart-update",
        security: self.security,
      },
      (res) => {
        if (res.success) {
          window.location.href = e.currentTarget.href;
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
    //   }
    // });
  };
  self.checkout = (e) => {
    if (!$("#acceptTerms").is(":checked")) {
      showErrorMessage("Vui lòng đọc và chấp nhận các điều khoản và chính sách để tiếp tục.", "Chấp nhận điều khoản");
      return;
    }

    Swal.fire({
      title: "Thanh toán",
      text: "Bắt đầu thanh toán?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            action: self.actionCartCheckout,
            security: self.security,
            tax_has: document.getElementById('xuat-vat').checked,
            tax_company: document.getElementById('name-company').value,
            tax_code: document.getElementById('mst-company').value,
            tax_email: document.getElementById('email-company').value
          },
          (res) => {
            if (res.success) {

              const paymentMethod = $(".payment-item:checked", self.context).val();
              callAjaxLoading(
                {
                  payment: paymentMethod,
                  hasvat: document.getElementById('xuat-vat').checked,
                  note: "",
                  action: self.actionCheckout,
                  security: self.security,
                  frontend: true,
                },
                (res) => {
                  if (res.success) {
                    if (paymentMethod === 'momo') {
                      const url = res.data.checkout_url;
                      self.orderId = url.substring(url.lastIndexOf('/') + 1);
                      self.loadQRMomo();
                    } else {
                      window.location.href = res.data.checkout_url;
                    }
                  } else {
                    showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
                  }
                },
                (msg) => {
                  showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
                }
              );
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
  self.deleteAll = () => {
    callAjaxLoading(
      {
        action: "ajax_client-cart-delete",
        security: self.security,
      },
      (res) => {
        if (res.success) {
          self.refresh();
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };

  self.delete = (e) => {
    let id = $(e.currentTarget).closest(".basket-product").data().item;

    Swal.fire({
      title: "Xóa dịch vụ",
      text: "Bạn có muốn xóa dịch vụ?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: id,
            action: "ajax_client-cart-delete-item",
            security: self.security,
          },
          (res) => {
            if (res.success) {
              self.refresh();
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
    $(self.context).on("click", ".summary-checkout .checkout-cta", (e) => {
      if (!cdwObjects.is_login) {
        $("#modalLogin").modal("show");
        return;
      }
      self.checkout();
    });
    $(self.context).on("click", ".btn-update", (e) => {
      if (!cdwObjects.is_login) {
        $("#modalLogin").modal("show");
        return;
      }
      self.update();
    });
    $(self.context).on("click", ".btn-delete-all", (e) => {
      if (!cdwObjects.is_login) {
        $("#modalLogin").modal("show");
        return;
      }
      Swal.fire({
        title: "Xóa giỏ hàng!",
        text: "Bạn chắc chắn thực hiện?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Có",
        cancelButtonText: "Không",
      }).then((res) => {
        if (res.value) {
          self.deleteAll();
        }
      });
    });

    $(self.context).on("click", ".remove", (e) => {
      if (!cdwObjects.is_login) {
        $("#modalLogin").modal("show");
        return;
      }
      self.delete(e);
    });

    $("#xuat-vat", self.context).on("change", (e) => {
      self.refresh();
    });
    $(".order-web a", self.context).on("click", (e) => {
      e.preventDefault();
      self.redirectCheckout(e);
    });
    $(self.context).on("change", ".quantity-field", (e) => {
      self.update(e, false);
      clearTimeout(shw_order_refresh_debounce_timer);
      shw_order_refresh_debounce_timer = setTimeout(() => {
        self.refresh();
      }, 800);
    })

  };

  self.refresh = () => {
    self.contextItems = $(".cartItems", self.context);
    let elChkVAT = $("#xuat-vat", self.context).prop("checked");

    let data = {
      action: self.action,
      security: self.security,
      vat: elChkVAT,
    };

    callAjax(
      data,
      (res) => {
        if (res.success) {
          self.contextItems.html("");
          res.data.items.map((value, index) => {
            var template = window.wp.template(res.data.template.item);
            template = template(value);
            self.contextItems.append(template);
          });

          $(".actions", self.context).html("");
          var template = window.wp.template(res.data.template.action);
          template = template(res.data);
          $(".actions", self.context).append(template);

          $(".summary-subtotal", self.context).html("");
          var template = window.wp.template(res.data.template.summary);
          template = template(res.data.summary);
          $(".summary-subtotal", self.context).append(template);

          $(".summary-vat", self.context).html("");
          var template = window.wp.template(res.data.template.vat);
          template = template(res.data.summary);
          $(".summary-vat", self.context).append(template);

          $(".summary-total", self.context).html("");
          var template = window.wp.template(res.data.template["summary-total"]);
          template = template(res.data.summary);
          $(".summary-total", self.context).append(template);

          $(".summary-checkout", self.context).html("");
          var template = window.wp.template(res.data.template.checkout);
          template = template(res.data.summary);
          $(".summary-checkout", self.context).append(template);
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      },
      $(".basket ", self.context)
    );
  };
  self.initialize = () => {
    self.addEvent();
    self.refresh();
  };

  self.loadQRMomo = async () => {
    await callAjaxLoading(
      {
        id: self.orderId,
        action: self.actionPaymentMomo,
        security: self.security,
      },
      (res) => {
        if (res.success) {
          if (res.data.pay_url) {
            window.location.href = res.data.pay_url;
          } else if (res.data.image_src) {
            $("#momo-qr-modal .image-qr-code").attr("src", res.data.image_src);
            self.templateCountDown = res.data.template.time;
            self.time_out = res.data.time_out;
            $("#momo-qr-modal").modal("show");
            self.checkPaymentMomo();
          }
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg, "Có lỗi xảy ra!");
      }
    );
  };

  self.startCountDown = () => {
    var template = window.wp.template(self.templateCountDown);
    let item = template(tachGioPhutGiay(self.time_out));
    $("#momo-qr-modal .time-expire-text").html(item);

    self.countDown = setTimeout(self.startCountDown, 1000);

    if (self.time_out <= 0) {
      self.stopCountDown();
    }
    self.time_out--;

    function tachGioPhutGiay(time_out) {
      var hour = Math.floor(time_out / 3600);
      var minute = Math.floor((time_out % 3600) / 60);
      var seconds = time_out % 60;

      return {
        hour: hour.toString().padStart(2, "0"),
        minute: minute.toString().padStart(2, "0"),
        seconds: seconds.toString().padStart(2, "0"),
      };
    }
  };

  self.stopCountDown = () => {
    clearTimeout(self.countDown);
  };

  self.checkPaymentMomo = () => {
    self.startCountDown();

    const pollingInterval = 1000;
    const timeoutInSeconds = self.time_out;
    let elapsedSeconds = 0;

    const intervalId = setInterval(() => {
      if (elapsedSeconds >= timeoutInSeconds) {
        clearInterval(intervalId);
        self.stopCountDown();
        showErrorMessage("Giao dịch đã hết hạn.", "Thanh toán thất bại!");
        $("#momo-qr-modal").modal("hide");
        return;
      }

      $.ajax({
        type: "POST",
        dataType: "json",
        url: cdwObjects.ajax_url,
        data: {
          id: self.orderId,
          action: self.actionCheckPaymentMomo,
          security: self.security,
        },
        success: function (res) {
          if (res.success) {
            clearInterval(intervalId);
            self.stopCountDown();
            $("#momo-qr-modal").modal("hide");
            showSuccessMessage(
              () => { window.location.href = res.data.checkout_url; },
              res.data.msg,
              "Thanh toán thành công"
            );
          }
        },
        error: function (jqXHR) {
          try {
            const res = jqXHR.responseJSON;
            if (res && res.data && [1000, 7000, 8000].includes(res.data.resultCode)) {
              // Pending, do nothing
            } else {
              clearInterval(intervalId);
              self.stopCountDown();
              $("#momo-qr-modal").modal("hide");
              showErrorMessage(
                (res && res.data && res.data.msg) ? res.data.msg : "Giao dịch thất bại.",
                "Giao dịch không thành công"
              );
            }
          }
          catch (e) {
            clearInterval(intervalId);
            self.stopCountDown();
            $("#momo-qr-modal").modal("hide");
            showErrorMessage("Lỗi không xác định khi kiểm tra thanh toán.", "Có lỗi xảy ra!");
          }
        }
      });

      elapsedSeconds++;
    }, pollingInterval);
  };

  return self;
})({});
