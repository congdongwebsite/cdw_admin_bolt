var indexDefault = (function (self) {
  self.counter = 0;
  self.intervalId;
  self.init = true;
  self.notificationItemDelete = (e) => {
    e.preventDefault();
    let id_notification = $(e.currentTarget).data().idNotification;

    let data = {
      action: "ajax_delete-bell-notification",
      security: $("#index-nonce").val(),
      id: id_notification,
    };

    callAjax(
      data,
      (res) => {
        if (res.success) {
          self.load_notification();
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  self.load_feature_info = () => {
    let context = $(".user-feature-info");

    let data = {
      action: "ajax_feature-info",
      security: objAdmin.nonce,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        // context.addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          context.html("");
          var template = window.wp.template(res.data.template);
          template = template(res.data.data);

          context.html(template);
        } else {
          console.log("load_feature_info", res.data.msg);
        }

        //  context.removeClass("admin-loading");
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
        showErrorMessage(msg);
        //  context.removeClass("admin-loading");
      },
    });
  };

  self.load_notification = () => {
    let context = $(".top-navbar-notification");

    let data = {
      action: "ajax_top-navbar-notification",
      security: objAdmin.nonce,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        // context.addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          var template = window.wp.template(res.data.template.dot);
          template = template(res.data.header);
          $(".icon-menu", context).html(template);

          $(".notification-main", context).html("");
          let notificationMain = "";
          var template = window.wp.template(res.data.template.header);
          notificationMain = template(res.data);

          res.data.items.map((value, index) => {
            var template = window.wp.template(res.data.template.item);
            notificationMain += template(value);
          });

          var template = window.wp.template(res.data.template.footer);
          notificationMain += template(res.data);

          $(".notification-main", context).html(notificationMain);
        } else {
          console.log("load_notification", res.data.msg);
        }
        //context.removeClass("admin-loading");
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
        showErrorMessage(msg);
        //context.removeClass("admin-loading");
      },
    });
  };
  self.load_notification_read = (e) => {
    e.preventDefault();
    let url = $(e.currentTarget).closest("li").data().url;
    let idn = $(e.currentTarget).closest("li").data().idn;

    let data = {
      action: "ajax_top-navbar-notification-read",
      security: objAdmin.nonce,
      id: idn,
    };

    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(e.currentTarget).addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          self.load_notification();
          window.open(url, "_blank");
        } else {
          console.log("load_notification", res.data.msg);
        }
        $(e.currentTarget).removeClass("admin-loading");
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
        showErrorMessage(msg);
        $(e.currentTarget).removeClass("admin-loading");
      },
    });
  };

  self.load_cart = () => {
    let context = $(".top-navbar-cart");

    let data = {
      action: "ajax_get_top-navbar-cart",
      security: objAdmin.nonce,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        //context.addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          var template = window.wp.template(res.data.template.dot);
          template = template(res.data);
          $(context).html(template);
        } else {
          console.log("load_cart", res.data.msg);
        }
        //context.removeClass("admin-loading");
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
        showErrorMessage(msg);
        //context.removeClass("admin-loading");
      },
    });
  };

  self.ping = (func) => {
    let data = {
      action: "ajax_ping",
      security: objAdmin.nonce,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {},
      success: function (res) {
        if (res.success) {
          if (res.data.cart) {
            indexDefault.load_cart();
          }
          if (res.data.notification) {
            indexDefault.load_notification();
          }
          if (res.data.feature) {
            indexDefault.load_feature_info();
          }
          if (!res.data.ping) {
            Swal.fire({
              title: "Kết nối máy chủ",
              text: "Bạn mất kết nối tới máy chủ, vui lòng đăng nhập lại!",
              icon: "error",
            }).then((res2) => {
              if (res2.value) {
                window.location.href = res.data.urlLogin;
              }
            });
          } else func();
        } else {
          console.log("load_feature_info", res.data.msg);
        }
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
        showErrorMessage(msg);
      },
    });
  };

  self.startPing = () => {
    self.counter = objAdmin.ping;
    self.intervalId = setInterval(function () {
      self.counter--;

      if (self.counter < 0) {
        clearInterval(self.intervalId);
        self.ping(self.startPing);
      }
    }, 1000);
  };
  self.resetPing = () => {
    clearInterval(self.intervalId);
    self.counter = objAdmin.ping;
    self.intervalId = setInterval(function () {
      self.counter--;

      if (self.counter < 0) {
        clearInterval(self.intervalId);
        self.ping(self.startPing);
      }
    }, 1000);
  };
  self.addEvent = () => {
    $(".top-navbar-notification .notification-main").on(
      "click",
      ".notification-item .notification-bell-delete",
      self.notificationItemDelete
    );
    $(".top-navbar-notification .notification-main").on(
      "click",
      ".notification-item a .media",
      self.load_notification_read
    );

    $(".top-navbar-cart").on("update-cart", function (e) {
      self.load_cart();
    });

    $(".navbar-fixed-top #customer-id").on("change", function (e) {
      callAjax(
        {
          id: $(e.currentTarget).val(),
          action: "ajax_admin-update-customer-default",
          security: objAdmin.nonce,
        },
        (res) => {
          if (res.success) {
            if (res.data.id && !self.init) {
              window.location.reload();
            }
            self.init = false;
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg, "Có lỗi xảy ra!");
        }
      );
    });
  };
  self.initialize = () => {
    self.addEvent();
    self.load_feature_info();
    self.load_notification();
    self.load_cart();
    self.startPing();
    if ($("#customer-id", self.context).length > 0)
      initSelect2Customer("customer-id", ".container-fluid");
  };
  return self;
})({});

$(function () {
  "use strict";

  //  Use by Device
  $(document).ready(function () {
    indexDefault.initialize();
  });
});
