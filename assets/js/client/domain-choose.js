$(function () {
  "use strict";
  $(document).ready(function () {
    domain_choose.initialize();
  });
});
var domain_choose = (function (self) {
  self.action = "ajax_choose-domain-client-cart";
  self.actionSearch = "ajax_search-domain-client-cart";
  self.actionSearchPer = "ajax_search-per-domain-client-cart";
  self.actionInfo = "ajax_info-domain";
  self.context = $(".client-domain-choose");
  self.currentRequest;
  self.table = $(".domain-list", self.context);

  self.addToCart = (e) => {
    e.preventDefault();
    Swal.fire({
      title: "Mua domain",
      text: "Domain bạn chọn sẽ được đưa vào giỏ hàng, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let idd = $(e.currentTarget).data().idd;
        let domain = $(e.currentTarget).data().domain;
        callAjaxLoading(
          {
            id: idd,
            domain: domain,
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

  self.sendAjaxRequest = () => {
    let searchtext = $(".input-domain", self.context).val();
    let searchtype = $(".type-domain", self.context).val();
    if (searchtext == "") {
      Swal.fire({
        title: "Bạn đang tìm kiếm điều gì?",
        text: "Vui lòng nhập domain?",
        icon: "question",
      });
      return;
    }
    if (self.currentRequest) {
      self.currentRequest.abort();
    }
    let data = {
      action: self.actionSearch,
      security: $("#nonce").val(),
      search: searchtext,
      type: searchtype,
    };
    self.currentRequest = $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $("tbody", self.table).addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          $("tbody", self.table).html("");
          $.each(res.data.items, function (key, value) {
            var template = window.wp.template(value.template);

            template = $(template(value));
            self.sendAjaxRequestPer(template, value.domain, value.id);
            $("tbody", self.table).append(template);
          });
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        $("tbody", self.table).removeClass("admin-loading");
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

        $("tbody", self.table).removeClass("admin-loading");
      },
    });
  };

  self.sendAjaxRequestPer = ($el, domain, id) => {
    let data = {
      action: self.actionSearchPer,
      security: $("#nonce").val(),
      domain: domain,
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
          let template = window.wp.template(res.data.item.template);
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
  self.infoDomain = (e) => {
    let domain = $(e.currentTarget).data().domain;

    let data = {
      action: self.actionInfo,
      security: $("#nonce").val(),
      domain: domain,
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
          let template = window.wp.template(res.data.template);
          template = template(res.data.info);
          let title =
            "TÊN MIỀN " +
            domain.toUpperCase() +
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

    $(".input-domain", self.context).on("input", function () {
      self.sendAjaxRequest();
    });

    $(".type-domain", self.context).on("change", function () {
      self.sendAjaxRequest();
    });
    $(".btn-search", self.context).on("click", function (e) {
      e.preventDefault();
      self.sendAjaxRequest();
    });

    $(self.context).on("click", ".btn-info-domain", function (e) {
      e.preventDefault();
      self.infoDomain(e);
    });
  };

  self.initialize = () => {
    self.addEvent();
  };

  return self;
})({});
