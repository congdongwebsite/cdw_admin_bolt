$(function () {
  "use strict";
  $(document).ready(function () {
    checkout.initialize();
    let params = new URLSearchParams(window.location.search);
    let isFrontend = params.get('frontend')
    if (isFrontend)
      checkout.checkoutNow();
  });
});
var checkout = (function (self) {
  self.action = "ajax_client-checkout";
  self.actionPayment = "ajax_client-checkout-payment";
  self.actionPaymentMomo = "ajax_client-checkout-payment-momo";
  self.actionCheckPaymentMomo = "ajax_client-checkout-check-payment-momo";
  self.context = $(".client-checkout");
  self.security = $("#nonce").val();
  self.templateCountDown;
  self.time_out;

  self.checkout = (e) => {
    let data = [];
    $("tbody .item ", self.context).map((index, value) => {
      data = [
        ...data,
        {
          id: $(value).data().item,
          quantity: $("input#quantity", $(value)).val(),
        },
      ];
    });

    let has_vat = $(".chk-vat", self.context).prop("checked");
    let payment = $(".payment-item:checked", self.context).val();
    let note = $(".notes", self.context).val();
    Swal.fire({
      title: "Bắt đầu thanh toán đơn hàng",
      text: "Bạn có muốn thanh toán đơn hàng?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            data: data,
            payment: payment,
            hasvat: has_vat,
            note: note,
            action: self.action,
            security: self.security,
          },
          (res) => {
            if (res.success) {
              window.location.href = res.data.checkout_url;
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        );
      }
    });
  };

  self.checkoutNow = (e) => {
    let payment = $(".payment-item:checked", self.context).val();
    callAjaxLoading(
      {
        id: $("#id", self.context).val(),
        payment: payment,
        action: self.actionPayment,
        security: self.security,
      },
      (res) => {
        if (res.success) {
          window.location.href = res.data.checkout_url;
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
      }
    );
  };
  self.checkoutPayment = (e) => {
    let payment = $(".payment-item:checked", self.context).val();
    Swal.fire({
      title: "Bắt đầu thanh toán đơn hàng",
      text: "Bạn có muốn thanh toán đơn hàng?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: $("#id", self.context).val(),
            payment: payment,
            action: self.actionPayment,
            security: self.security,
          },
          (res) => {
            if (res.success) {
              window.location.href = res.data.checkout_url;
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        );
      }
    });
  };
  self.cancel = (e) => {
    Swal.fire({
      title: "Hủy hóa đơn!",
      text: "Hóa đơn chưa thanh toán, bạn có muốn hủy?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: $("#id", self.context).val(),
            action: "ajax_client-checkout-cancel",
            security: self.security,
          },
          (res) => {
            if (res.success) {
              window.location.href = res.data.checkout_url;
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        );
      }
    });
  };
  self.addEvent = () => {
    $(".btn-checkout").on("click", (e) => {
      self.checkout();
    });
    $(".btn-checkout-payment").on("click", (e) => {
      self.checkoutPayment();
    });
    $(".btn-cancel").on("click", (e) => {
      self.cancel();
    });
    $("input#quantity").bind("keyup mouseup", function () {
      let quantity = $(this).val();
      let price = $(".price", $(this).closest("tr.item")).data().price;
      let elAmount = $(".amount", $(this).closest("tr.item"));
      let amount = quantity * price;
      $("span", elAmount)
        .text(amount.toLocaleString("vi-VN"))
        .trigger("change");
      elAmount.data("amount", amount);

      amount = 0;
      $(".item .amount").map((index, value) => {
        amount += $(value).data().amount;
      });
      let elSubTotal = $(".sub-total", self.context);
      let elTotal = $(".total", self.context);
      $("span", elSubTotal).text(amount.toLocaleString("vi-VN"));
      elSubTotal.data("total", amount);

      let elChkVAT = $(".chk-vat", self.context).prop("checked");
      let vatPercent = $(".chk-vat", self.context).data().percent;
      if (!elChkVAT) vatPercent = 0;
      let elVAT = $(".vat", self.context);
      let vat = (amount * vatPercent) / 100;
      amount += vat;
      $("span", elVAT).text(vat.toLocaleString("vi-VN"));
      elVAT.data("vat", vat);

      $("span", elTotal).text(amount.toLocaleString("vi-VN"));
      elTotal.data("total", amount);
    });

    $(".chk-vat", self.context).on("change", (e) => {
      let elChkVAT = $(e.currentTarget).prop("checked");

      let vatPercent = $(".chk-vat", self.context).data().percent;

      if (!elChkVAT) vatPercent = 0;

      let elVAT = $(".vat", self.context);
      let elSubTotal = $(".sub-total", self.context);
      let elTotal = $(".total", self.context);

      let amount = elSubTotal.data().total;
      let vat = (amount * vatPercent) / 100;

      amount += vat;

      $("span", elVAT).text(vat.toLocaleString("vi-VN"));
      elVAT.data("vat", vat);
      $("span", elTotal).text(amount.toLocaleString("vi-VN"));
      elTotal.data("total", amount);
    });

    $(".payment-item", self.context).on("change", (e) => {
      $(".payment-item", self.context).prop("checked", false);
      $(e.currentTarget, self.context).prop("checked", true);
    });

    $(".btn-print").click(function () {
      $("#details .print").printThis({
        pageTitle: "Thanh toán",
        header:
          '<div class="text-center"><div><h4 class="mb-5"><strong>THANH TOÁN</strong></h4></div></div>',
      });
    });
  };
  self.loadQR = async () => {
    await callAjaxLoading(
      {
        id: $("#id", self.context).val(),
        action: self.actionPaymentMomo,
        security: self.security,
      },
      (res) => {
        if (res.success) {
          //$(".image-qr-code", self.context).attr("src", res.data.image_src);
          self.templateCountDown = res.data.template.time;
          self.time_out = res.data.time_out;
          self.checkPayment();
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
    $(".time-expire-text").html(item);

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

  self.checkPayment = () => {
    callAjax(
      {
        id: $("#id", self.context).val(),
        action: self.actionCheckPaymentMomo,
        security: self.security,
      },
      (res) => {
        if (res.success) {
          console.log(
            "🚀 ~ file: billing-checkout.js:227 ~ self.checkPayment= ~ res:",
            res
          );
          showSuccessMessage(
            function () {
              window.location.href = res.data.checkout_url;
            },
            res.data.msg,
            (title = "Thanh toán thành công")
          );
        } else {
          showErrorMessage(
            res.data.msg,
            "Thanh toán thất bại!",
            "info",
            function () {
              window.location.href = res.data.checkout_url;
            }
          );
        }
        self.stopCountDown();
      },
      (msg) => {
        showErrorMessage(msg, "Có lỗi xảy ra!");
      },
      self.startCountDown()
    );
  };

  self.initialize = async () => {
    self.addEvent();
    if (
      $("#step", self.context).val() == 2 &&
      $("#payment", self.context).val() == "momo"
    )
      await self.loadQR();
  };

  return self;
})({});
