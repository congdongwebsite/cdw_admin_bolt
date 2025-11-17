$(function () {
  "use strict";
  $(document).ready(function () {
    plugin_choose.initialize();
  });
});
var plugin_choose = (function (self) {
  self.action = "ajax_choose-plugin-client-cart";
  self.actionSearch = "ajax_search-plugin-client-cart";
  self.actionSearchPer = "ajax_search-per-plugin-client-cart";
  self.actionInfo = "ajax_info-plugin";
  self.context = $(".client-plugin-choose");
  self.currentRequest;
  self.plugin_list = $(".plugin-list", self.context);
  self.typeActive = -1;

  self.addToCart = (e) => {
    e.preventDefault();
    Swal.fire({
      title: "Mua plugin",
      text: "Plugin bạn chọn sẽ được đưa vào giỏ hàng, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let idt = $(e.currentTarget).data().idt;
        callAjaxLoading(
          {
            id: idt,
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

  self.sendAjaxRequest = (searchtext) => {
    if (self.currentRequest) {
      self.currentRequest.abort();
    }
    let data = {
      action: self.actionSearch,
      security: $("#nonce").val(),
      search: searchtext,
      type: self.typeActive,
    };
    self.currentRequest = $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        self.plugin_list.addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          self.plugin_list.html("");
          $.each(res.data.items, function (key, value) {
            var template = window.wp.template(value.template);
            template = $(template(value));
            let typelist = value.id_type_list.split(",");

            if (
              self.typeActive != -1 &&
              !typelist.includes(self.typeActive.toString())
            ) {
              template.hide();
            }
            self.sendAjaxRequestPer(template, value.id);
            self.plugin_list.append(template);
          });
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        self.plugin_list.removeClass("admin-loading");
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

        self.plugin_list.removeClass("admin-loading");
      },
    });
  };

  self.sendAjaxRequestPer = ($el, id) => {
    let data = {
      action: self.actionSearchPer,
      security: $("#nonce").val(),
      id: id,
    };
    self.currentRequest = $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $el.addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          var template = window.wp.template(res.data.item.template);
          template = template(res.data.item);

          $el.html($(template).html());
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        $el.removeClass("admin-loading");
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

        $el.removeClass("admin-loading");
      },
    });
  };

  self.infoPlugin = (e) => {
    let plugin = $(e.currentTarget).data().plugin;

    let data = {
      action: self.actionInfo,
      security: $("#nonce").val(),
      plugin: plugin,
    };
    self.currentRequest = $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(e.currentTarget).addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          let template = window.wp.template("info-plugin-template");
          template = template(res.data.info);
          let title =
            "TÊN MIỀN " +
            plugin.toUpperCase() +
            (res.data.info.available == "available"
              ? '<span class="text-success ml-2"> CÓ THỂ ĐĂNG KÝ</span>'
              : '<span class="text-danger ml-2"> ĐÃ CÓ SỞ HỮU</span>');
          Swal.fire({
            title: title,
            html: template,
            icon: res.data.info.available != "available" ? "error" : "info",
            width: 600,
          });
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
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

        $(e.currentTarget).removeClass("admin-loading");
      },
    });
  };

  self.addEvent = () => {
    $(self.context).on("click", ".btn-choose", function (e) {
      e.preventDefault();
      self.addToCart(e);
    });

    $(self.context).on("click", ".type-list .type-item", function (e) {
      e.preventDefault();
      self.typeActive = $(e.currentTarget).data().idt;
      let searchtext = $(".input-plugin", self.context).val();
      self.sendAjaxRequest(searchtext);
    });
    $(".input-plugin", self.context).on("input", function () {
      let searchtext = $(".input-plugin", self.context).val();
      self.sendAjaxRequest(searchtext);
    });

    $(".btn-search", self.context).on("click", function (e) {
      e.preventDefault();
      let searchtext = $(".input-plugin", self.context).val();
      self.sendAjaxRequest(searchtext);
    });

    $(self.context).on("click", ".btn-info-plugin", function (e) {
      e.preventDefault();
      self.infoPlugin(e);
    });
  };

  self.initialize = () => {
    self.addEvent();
    self.sendAjaxRequest("", "");
  };

  return self;
})({});
