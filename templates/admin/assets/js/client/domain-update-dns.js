$(function () {
  "use strict";
  $(document).ready(function () {
    domainDNS.initialize();
  });
});
var domainDNS = (function (self) {
  self.action = "ajax_update-dns-domain-client-cart";
  self.actionDefault = "ajax_update-dns-domain-default-client-cart";
  self.actionLoadDNS = "ajax_load-dns-domain-client-cart";
  self.context = $(".client-domain-update-dns");
  self.form = $("form", self.context);
  self.currentRequest;

  self.updateDNS = (e) => {
    e.preventDefault();
    if (!self.form.parsley().validate()) return;

    Swal.fire({
      title: "Cập nhật DNS",
      text: "DNS sẽ được cập nhật, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let dns1 = $(".dns-server-1", self.context).val();
        let dns2 = $(".dns-server-2", self.context).val();
        let dns3 = $(".dns-server-3", self.context).val();
        let dns4 = $(".dns-server-4", self.context).val();
        let dns5 = $(".dns-server-5", self.context).val();
        let dns6 = $(".dns-server-6", self.context).val();
        let dns7 = $(".dns-server-7", self.context).val();
        let dns8 = $(".dns-server-8", self.context).val();
        callAjaxLoading(
          {
            dns1: dns1,
            dns2: dns2,
            dns3: dns3,
            dns4: dns4,
            dns5: dns5,
            dns6: dns6,
            dns7: dns7,
            dns8: dns8,
            id: self.id,
            action: self.action,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title ? res.data.title : "Cập nhật DNS",
                text: res.data.msg,
                icon: "question",
                confirmButtonText: "Có",
              }).then((res2) => {});
              self.loadDNS();
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

  self.updateDefault = (e) => {
    e.preventDefault();

    Swal.fire({
      title: "Cập nhật DNS Mặc định",
      text: "DNS mặc định sẽ được cập nhật, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: self.id,
            action: self.actionDefault,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title ? res.data.title : "Cập nhật DNS",
                text: res.data.msg,
                icon: "question",
                confirmButtonText: "Có",
              }).then((res2) => {});
              self.loadDNS();
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

  self.loadDNS = async () => {
    let data = {
      action: self.actionLoadDNS,
      domain: $("#domain", self.context).val(),
      security: $("#nonce").val(),
    };
    await $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(".dns-list", self.context).addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          $(".dns-list", self.context).html("");
          self.id = res.data.id;
          $.each(res.data.items, function (key, value) {
            var template = window.wp.template(res.data.template["item-list"]);
            template = $(template(value));
            $(".dns-list", self.context).append(template);
          });
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        $(".dns-list", self.context).removeClass("admin-loading");
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

        $(".dns-list", self.context).removeClass("admin-loading");
      },
    });
  };

  self.addEvent = () => {
    $(".btn-update", self.context).on("click", function (e) {
      e.preventDefault();
      self.updateDNS(e);
    });
    $(".btn-default", self.context).on("click", function (e) {
      e.preventDefault();
      self.updateDefault(e);
    });
  };

  self.initialize = async () => {
    self.addEvent();
    await self.loadDNS();
  };

  return self;
})({});
