$(function () {
  "use strict";
  $(document).ready(function () {
    cart.initialize();
  });
});
var cart = (function (self) {
  self.actionCheckout = "ajax_client-cart-checkout";
  self.context = $(".client-cart");
  self.security = $("#nonce").val();
  self.action = "ajax_get-list-cart";

  self.update = (e) => {
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
            data: data,
            action: self.actionCheckout,
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
    let id = $(e.currentTarget).closest("tr").data().item;

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
    $(self.context).on("click", ".btn-checkout", (e) => {
      self.checkout();
    });
    $(self.context).on("click", ".btn-update", (e) => {
      self.update();
    });
    $(self.context).on("click", ".btn-delete-all", (e) => {
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

    $(".list-items", self.context).on("click", ".remove", (e) => {
      self.delete(e);
    });
  };

  self.refresh = () => {
    self.contextItems = $(".list-items", self.context);
    let data = {
      action: self.action,
      security: self.security,
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

          $(".checkout", self.context).html("");
          var template = window.wp.template(res.data.template.checkout);
          template = template(res.data.checkout);
          $(".checkout", self.context).append(template);
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  self.initialize = () => {
    self.addEvent();
    self.refresh();
  };

  return self;
})({});
