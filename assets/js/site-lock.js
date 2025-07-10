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
    onBeforeOpen: () => {
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
      url: objAdmin.ajax_url,
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
function callAjax(data, funcSuccess = null, funcError = null) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: objAdmin.ajax_url,
    data: data,
    beforeSend: function () {},
    success: function (res) {
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
      if (typeof funcError == "function") {
        funcError(msg);
      }
    },
  });
}

function getFormData(action, security, elForm) {
  var form_data = elForm.serializeArray().reduce(function (obj, item) {
    obj[item.name] = item.value;
    return obj;
  }, {});
  form_data["action"] = action;
  form_data["security"] = security;
  return form_data;
}

function clearInputData(context) {
  $("input:not(.datepicker), textarea:not(.summernote)", context).val("");
  $(".datepicker", context).datepicker("setDate", null);
  $("select.select2", context).val(null).trigger("change");
  $('input[type="radio"], input[type="checkbox"]', context).prop(
    "checked",
    false
  );
  $('input[type="checkbox"]#Status', context).prop("checked", true);
  $("label.custom-file-label", context).text("");
  $('input[type="file"]', context).val("");
}
