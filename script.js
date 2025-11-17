var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {



  AOS.init();
  $(".dropdown-submenu a.dropdown-item").on("click", function (e) {
    $(this).next("ul").toggle();
    e.stopPropagation();
    e.preventDefault();
  });

  $(".pagination .page-item ").on(
    "click",
    ajax_arc_site_managers_pagination_data
  );

  $(".danh-muc-website>ul>li>a").on(
    "click",
    ajax_arc_site_managers_pagination_by_cat_data
  );
  $(".btn-modal-order", formOrder).on("click", (e) => {
    //Shw_order.getPrice();
    // let modal = $("#modal-order");
    // if (formOrder.length > 0) {
    //   modal.modal("show");
    // } else {
    //   Swal.fire({
    //     icon: "danger",
    //     title: "Yêu cầu đăng nhập",
    //     text: "Vui lòng đăng nhập trước khi đặt hàng.",
    //     showConfirmButton: true,
    //   }).then((result) => {
    //     $("#modalLogin").modal("show");
    //   });
    // }
    // e.preventDefault();
  });
  var formOrder = $("#form-order");
  if (formOrder.length > 0) {
    $(".btn-order", formOrder).on("click", (e) => {
      if (!formOrder[0].checkValidity()) return;

      ajax_arc_site_order_post(e);

      e.preventDefault();
    });
    function ajax_arc_site_order_post(e) {
      let status = $("#status-order", formOrder);
      let id_site = $("#id-site", formOrder).val();
      // let name = $("#Name", formOrder).val();
      // let email = $("#Email", formOrder).val();
      // let phone = $("#Phone", formOrder).val();
      // let address = $("#Address", formOrder).val();
      let note = $("#Note", formOrder).val();
      let price = $("#price", formOrder).val();
      let security = $("#order", formOrder).val();

      let captchaIndex = $("#order-captcha").attr("data-widget-id");
      let recaptcha = grecaptcha.getResponse(captchaIndex);
      if (!recaptcha) {
        status.removeClass("d-none");
        status.addClass("text-danger");
        status.text("Vui lòng hoàn thành reCAPTCHA.");
        e.preventDefault();
        return;
      }
      let data = {
        action: "ajax_arc_site_order_post",
        id: id_site,
        // name: name,
        // email: email,
        // phone: phone,
        // address: address,
        note: note,
        price: price,
        grecaptcha: recaptcha,
        security: security,
      };
      let modal = $("#modal-order");
      let modal_body = $("#modal-order .modal-body");

      $.ajax({
        type: "post",
        dataType: "json",
        url: cdwObjects.ajax_url,
        data: data,
        context: this,
        async: false,
        beforeSend: function () {
          status.removeClass("d-none");
          status.removeClass("text-danger");
          status.removeClass("text-success");
          status.addClass("text-secondary");
          status.text("Đang tiến tạo đơn hàng.");
          modal_body.addClass("congdongtheme-loading");
        },
        success: function (response) {
          modal_body.removeClass("congdongtheme-loading");
          if (response.order == true) {
            status.addClass("text-success");
            status.text(response.message);
            Swal.fire({
              icon: "success",
              title: "Tạo đơn hàng thành công",
              text: "Vui lòng đợi chúng tôi sẽ gọi lại để xác nhận!",
              showConfirmButton: true,
            }).then((result) => {
              // $("#Name", formOrder).val("");
              // $("#Email", formOrder).val("");
              // $("#Phone", formOrder).val("");
              // $("#Address", formOrder).val("");
              $("#Note", formOrder).val("");
              formOrder.removeClass("was-validated");
              status.addClass("d-none");
              modal.modal("hide");
            });
          } else {
            status.addClass("text-danger");
            status.text(response.message);
            if (!response.login) {
              Swal.fire({
                icon: "danger",
                title: "Yêu cầu đăng nhập",
                text: response.message,
                showConfirmButton: true,
              }).then((result) => {
                modal.modal("hide");
                $("#modalLogin").modal("show");
              });
            }
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
          modal_body.removeClass("congdongtheme-loading");
          status.addClass("text-danger");
          status.text(msg);
        },
      });
      grecaptcha.reset(captchaIndex);
    }
  }

  function ajax_arc_site_managers_pagination_data(e) {
    let page = $(e.currentTarget).data().pagi;
    let cat = -1;

    if ($(".pagination").data("idcat") != undefined)
      cat = $(".pagination").data().idcat;

    if (
      $(e.currentTarget).hasClass("active") ||
      $(e.currentTarget).attr("disabled")
    )
      return;

    let content_item = $(".content-archive-site-managers");
    $.ajax({
      type: "post",
      dataType: "json",
      url: cdwObjects.ajax_url,
      data: {
        action: "ajax_arc_site_managers_pagination_data",
        query_vars: cdwObjects.query_vars,
        page: page,
        cat: cat,
      },
      context: this,
      beforeSend: function () {
        setTimeout(() => {
          window.scroll({
            top: $(".main").offset().top - 20,
            left: 0,
            behavior: "smooth",
          });
        }, 500);
        content_item.addClass("congdongtheme-loading");
      },
      success: function (response) {
        content_item.html("");
        content_item.removeClass("congdongtheme-loading");
        let itemres = $(response.data).hide();
        content_item.append(itemres);
        $(".pagination", itemres).data("idcat", cat);
        $(".pagination .page-item ", itemres).on(
          "click",
          ajax_arc_site_managers_pagination_data
        );
        itemres.show("slow");
      },
    });
  }
  function ajax_arc_site_managers_pagination_by_cat_data(e) {
    e.stopPropagation();
    e.preventDefault();

    let page = 0;
    let cat = $(e.currentTarget).data().id;
    $(".danh-muc-website>ul>li>a").removeClass("active");
    $(e.currentTarget).addClass("active");

    let content_item = $(".content-archive-site-managers");
    $.ajax({
      type: "post",
      dataType: "json",
      url: cdwObjects.ajax_url,
      data: {
        action: "ajax_arc_site_managers_pagination_data",
        query_vars: cdwObjects.query_vars,
        page: page,
        cat: cat,
      },
      context: this,
      beforeSend: function () {
        content_item.addClass("congdongtheme-loading");
      },
      success: function (response) {
        content_item.html("");
        content_item.removeClass("congdongtheme-loading");

        let itemres = $(response.data).hide();
        content_item.append(itemres);
        $(".pagination", content_item).data("idcat", cat);
        $(".pagination .page-item ", content_item).on(
          "click",
          ajax_arc_site_managers_pagination_data
        );
        itemres.show("slow");
      },
    });
  }

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add("was-validated");
      },
      false
    );
  });

  if ($("#modalLogin").length > 0) {
    $("#show_login").on("click", (e) => {
      $("#modalLogin").one('shown.bs.modal', function () {
        $('#modalLogin a[href="#login"]').tab('show');
      });
      $("#modalLogin").modal("show");
      e.preventDefault();
    });
    $("#show_register").on("click", (e) => {
      $("#modalLogin").one('shown.bs.modal', function () {
        $('#modalLogin a[href="#register"]').tab('show');
      });
      $("#modalLogin").modal("show");

      e.preventDefault();
    });
  }
  if ($("form#f_login").length > 0) {
    $(".btn-login", "form#f_login").on("click", function (e) {
      let form = $("form#f_login");
      if (!form[0].checkValidity()) return;

      let status = $("#status-login", form);

      let username = $("#UsernameLogin", form).val();
      let password = $("#PasswordLogin", form).val();
      let remember = $("#flexCheckRemember", form).val();
      let security = $("#fn_login", form).val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: cdwObjects.ajax_url,
        data: {
          action: "ajax_frontend_login",
          username: username,
          password: password,
          remember: remember,
          urlRedirect: window.location.href,
          security: security,
        },
        beforeSend: function () {
          status.removeClass("d-none");
          status.removeClass("text-danger");
          status.addClass("text-secondary");
          status.text("Đang tiến hành đăng nhập.");
          form.addClass("congdongtheme-loading");
        },
        success: function (res) {
          if (res.success) {
            status.addClass("text-success");
            status.html(res.data.msg);
            window.location.href = res.data.urlRedirect + '?login=true';
          } else {
            status.addClass("text-danger");
            status.html(res.data.msg);
          }
          form.removeClass("congdongtheme-loading");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          status.addClass("text-danger");
          status.text("Đã xảy ra sự cố.");
          form.removeClass("congdongtheme-loading");
        },
      });
      e.preventDefault();
    });
  }

  if ($("form#f_register").length > 0) {

    let form = $("form#f_register");
    initSelect2DVHCTPWard_iNET("dvhc-tp", form, "dvhc-px");
    $(".btn-register", "form#f_register").on("click", function (e) {
      if (!$("#acceptTermsRegister").is(":checked")) {
        status.removeClass("d-none");
        status.addClass("text-danger");
        status.text("Vui lòng đọc và chấp nhận các điều khoản và chính sách để tiếp tục.");
        e.preventDefault();
        return;
      }

      if (!form[0].checkValidity()) return;
      let status = $("#status-register", form);
      status.removeClass("d-none");

      let captchaIndex = $("#register-captcha").attr("data-widget-id");
      let recaptcha = grecaptcha.getResponse(captchaIndex);

      if (!recaptcha) {
        status.addClass("text-danger");
        status.text("Vui lòng hoàn thành reCAPTCHA.");
        e.preventDefault();
        return;
      }
      let name = $("#NameRegister", form).val();
      let email = $("#EmailRegister", form).val();
      let phone = $("#PhoneRegister", form).val();
      let address = $("#AddressRegister", form).val();
      let tp = $("#dvhc-tp", form).val();
      let px = $("#dvhc-px", form).val();
      let username = $("#UsernameRegister", form).val();
      let password = $("#PasswordRegister", form).val();
      let security = $("#fn_register", form).val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: cdwObjects.ajax_url,
        data: {
          action: "ajax_frontend_register",
          name: name,
          email: email,
          phone: phone,
          tp: tp,
          px: px,
          address: address,
          username: username,
          password: password,
          urlRedirect: window.location.href,
          grecaptcha: recaptcha,
          security: security,
        },
        beforeSend: function () {
          status.removeClass("text-danger");
          status.addClass("text-secondary");
          status.text("Đang tiến hành đăng ký.");
          form.addClass("congdongtheme-loading");
        },
        success: function (res) {
          if (res.success) {
            status.addClass("text-success");
            status.text(res.data.msg);
            window.location.href = res.data.urlRedirect + '?register=true';
          } else {
            status.addClass("text-danger");
            status.text(res.data.msg);
            status.focus();
          }
          form.removeClass("congdongtheme-loading");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          status.addClass("text-danger");
          status.text("Đã xảy ra sự cố.");
          form.removeClass("congdongtheme-loading");
        },
      });
      grecaptcha.reset(captchaIndex);
      e.preventDefault();
    });
    function sb_password_strength(
      $pass1,
      $pass2,
      $strengthResult,
      $submitButton,
      blacklistArray
    ) {
      var pass1 = $pass1.val(),
        pass2 = $pass2.val(),
        strength = 0;
      $submitButton.attr("disabled", "disabled");
      $strengthResult.removeClass();
      blacklistArray = blacklistArray.concat(
        wp.passwordStrength.userInputDisallowedList()
      );
      strength = wp.passwordStrength.meter(pass1, blacklistArray, pass2);
      switch (strength) {
        case 2:
          $strengthResult.addClass("text-danger").html(pwsL10n.bad);
          break;
        case 3:
          $strengthResult.addClass("text-secondary").html(pwsL10n.good);
          break;
        case 4:
          $strengthResult.addClass("text-success").html(pwsL10n.strong);
          break;
        case 5:
          $strengthResult.addClass("text-danger").html(pwsL10n.mismatch);
          break;
        default:
          $strengthResult.addClass("text-secondary").html(pwsL10n.short);
      }
      if (3 <= strength && pass1 == pass2) {
        $submitButton.removeAttr("disabled");
      }
      return strength;
    }
    $("#PasswordRegister,#RePasswordRegister", "form#f_register").on(
      "keyup",
      function (e) {
        var form = $("form#f_register"),
          password = form.find("#PasswordRegister"),
          re_password = form.find("#RePasswordRegister"),
          password_strength = form.find("#status-password"),
          password_submit = form.find(".btn-register"),
          password_black_list = ["admin"];

        sb_password_strength(
          password,
          re_password,
          password_strength,
          password_submit,
          password_black_list
        );
      }
    );
  }
  if ($("form#form-contact").length > 0) {
    $(".btn-contact", "form#form-contact").on("click", function (e) {
      let form = $("form#form-contact");
      if (!form[0].checkValidity()) return;
      let status = $("#status-contact", form);
      status.removeClass("d-none");

      let captchaIndex = $("#contact-captcha").attr("data-widget-id");
      let recaptcha = grecaptcha.getResponse(captchaIndex);

      if (!recaptcha) {
        status.addClass("text-danger");
        status.text("Vui lòng hoàn thành reCAPTCHA.");
        e.preventDefault();
        return;
      }
      let name = $("#NameContact", form).val();
      let email = $("#EmailContact", form).val();
      let phone = $("#PhoneContact", form).val();
      let note = $("#LoinhanContact", form).val();
      let security = $("#fn_contact", form).val();

      $.ajax({
        type: "POST",
        dataType: "json",
        url: cdwObjects.ajax_url,
        data: {
          action: "ajax_congdongcontact",
          name: name,
          email: email,
          phone: phone,
          note: note,
          grecaptcha: recaptcha,
          security: security,
        },
        beforeSend: function () {
          status.removeClass("text-danger");
          status.addClass("text-secondary");
          status.text("Đang tiến hành gửi đăng ký.");
          form.addClass("congdongtheme-loading");
        },
        success: function (data) {
          if (data.contact == true) {
            status.addClass("text-success");
            status.text(data.message);
          } else {
            status.addClass("text-danger");
            status.text(data.message);
          }
          form.removeClass("congdongtheme-loading");
        },
        error: function (jqXHR, textStatus, errorThrown) {
          status.addClass("text-danger");
          status.text("Đã xảy ra sự cố.");
          form.removeClass("congdongtheme-loading");
        },
      });
      grecaptcha.reset(captchaIndex);
      e.preventDefault();
    });
  }

  $(".login-header").hover(
    function () {
      $(this).addClass("hover");
    },
    function () {
      $(this).removeClass("hover");
    }
  );
  $("#primary-menu .navbar-nav .nav-item ").hover(
    function (e) {
      $("a", this).addClass("show");
      $("a", this).attr("aria-expanded", true);
      $(".dropdown-menu", this).addClass("show");
      $(".dropdown-menu", this).attr("data-bs-popper", "none");
    },
    function () {
      $("a", this).removeClass("show");
      $("a", this).attr("aria-expanded", false);
      $(".dropdown-menu", this).removeClass("show");
      $(".dropdown-menu", this).attr("data-bs-popper", "");
    }
  );
  // contextmenu
  const popperContextMenu = document.querySelector("#popperContextMenu");
  if (popperContextMenu && window.innerWidth > 1200) {
    document.addEventListener("contextmenu", (e) => {
      e.preventDefault();
      if (popperContextMenu) {
        var mouseX = e.clientX;
        var mouseY = e.clientY;
        var windowWidth = window.innerWidth;
        var windowHeight = window.innerHeight;
        var contextMenuWidth = popperContextMenu.offsetWidth;
        var contextMenuHeight = popperContextMenu.offsetHeight;

        // Kiá»ƒm tra vá»‹ trÃ­ cá»§a chuá»™t so vá»›i khung mÃ n hÃ¬nh
        if (mouseX + contextMenuWidth > windowWidth) {
          mouseX = windowWidth - contextMenuWidth - 30;
        }
        if (mouseY + contextMenuHeight > windowHeight) {
          mouseY = windowHeight - contextMenuHeight - 30;
        }

        popperContextMenu.style.left = mouseX + "px";
        popperContextMenu.style.top = mouseY + "px";
        popperContextMenu.style.opacity = "1";
        popperContextMenu.style.pointerEvents = "auto";
      }
    });

    document.addEventListener("click", (e) => {
      popperContextMenu.style.opacity = "0";
      popperContextMenu.style.pointerEvents = "none";
    });

    popperContextMenu.onclick = (e) => {
      e.stopPropagation();
    };
  }
});

var CaptchaCallback = function () {
  jQuery(".g-recaptcha").each(function (index, el) {
    var widgetId = grecaptcha.render(el, {
      sitekey: cdwObjects.captcha,
    });
    jQuery(this).attr("data-widget-id", widgetId);
  });
};

async function showLoading(
  next,
  title = "Vui lòng chờ!",
  html = "Dữ liệu của bạn đang được xử lý, vui lòng chờ trong giây lát."
) {
  await Swal.fire({
    title: title,
    html: html,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false,
    didOpen: () => {
      Swal.showLoading();
    },
    onOpen: async () => {
      if (typeof next == "function") {
        await next();
      }
    },
  });
}

function hideLoading(
  next,
  text = "Dữ liệu của bạn đã được thực thi!",
  title = "Thành công",
  icon = "success",
  timer = 1500
) {
  if (typeof next == "undefined") {
    Swal.close();
  }

  if (typeof next == "function") {
    setTimeout(() => {
      Swal.fire({
        title: title,
        text: text,
        icon: icon,
        timer: timer,
        showConfirmButton: false,
      }).then(() => {
        next();
      });
    }, 1);
  }
}

function showErrorMessage(
  html = "Vui lòng liên hệ bộ phận kỹ thuật!",
  title = "Có lỗi xảy ra",
  icon = "error"
) {
  Swal.fire({
    title: title,
    html: html,
    icon: icon,
  });
}

function showSuccessMessage(
  next = null,
  html = "Thực thi thành công!",
  title = "Thành công",
  icon = "success",
  timer = 2000
) {
  Swal.fire({
    title: title,
    html: html,
    icon: icon,
    timer: timer,
    showConfirmButton: false,
  }).then(() => {
    if (typeof next == "function") {
      next();
    }
  });
}
async function callAjaxLoading(
  data,
  funcSuccess = null,
  funcError = null,
  funcBeforeSend = null
) {
  showLoading(
    $.ajax({
      type: "POST",
      dataType: "json",
      url: cdwObjects.ajax_url,
      data: data,
      beforeSend: function () {
        if (typeof funcBeforeSend == "function") {
          funcBeforeSend();
        }
      },
      success: function (res) {
        hideLoading();
        if (typeof funcSuccess == "function") {
          funcSuccess(res);
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
        hideLoading();
        if (typeof funcError == "function") {
          funcError();
        }
      },
    })
  );
}

function callAjax(data, funcSuccess = null, funcError = null, context = null) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: cdwObjects.ajax_url,
    data: data,
    beforeSend: function () {
      if ($(context).length != 0) $(context).addClass("congdongtheme-loading");
    },
    success: function (res) {
      if (typeof funcSuccess == "function") {
        funcSuccess(res);
      }
      if ($(context).length != 0)
        $(context).removeClass("congdongtheme-loading");
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
      if (typeof funcError == "function") {
        funcError(msg);
      }
      if ($(context).length != 0)
        $(context).removeClass("congdongtheme-loading");
    },
  });
}
