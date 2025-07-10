const urlParams = new URLSearchParams(window.location.search);

String.prototype.formatDate = function () {
  if (typeof this === "undefined" || this === "") return "";

  return moment(this, dateFormat).format(siteSettings.formatDate);
};
String.prototype.formatDateTime = function () {
  if (typeof this === "undefined" || this === "") return "";

  return moment(this, dateFormat).format(siteSettings.formatDate + " HH:mm:ss");
};
String.prototype.formatTime = function () {
  if (typeof this === "undefined" || this === "") return "";

  return moment(this, dateFormat).format("HH:mm:ss");
};

function initdatetimepickerlink(date1, date2, context) {
  let datetimepicker1 = $("#" + date1, context);
  let datetimepicker2 = $("#" + date2, context);

  datetimepicker1.on("dp.change", function (e) {
    datetimepicker2.data("DateTimePicker").minDate(e.date);
  });
  //datetimepicker2.on("dp.change", function (e) {
  //    datetimepicker1.data("DateTimePicker").maxDate(e.date);
  //});
}
function initdatepickerlink(date1, date2, context) {
  let datepicker1 = $("#" + date1, context);
  let datepicker2 = $("#" + date2, context);

  datepicker1.on("changeDate", function (e) {
    datepicker2.datepicker("setStartDate", e.date);
  });
  datepicker2.on("changeDate", function (e) {
    datepicker1.datepicker("setEndDate", e.date);
  });
}
$(function () {
  "use strict";
  $(document).ready(function () {
    $(".datepicker").datepicker({
      autoclose: true,
      format: siteSettings.formatDateDatepicker,
      todayBtn: "linked",
      clearBtn: true,
      todayHighlight: true,
      language: "vi",
    });
    $(".datepicker.date-now").datepicker("setDate", "now");
    $(".monthpicker").datepicker({
      autoclose: true,
      todayBtn: "linked",
      clearBtn: true,
      todayHighlight: true,
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months",
      language: "vi",
    });
    $(".monthpicker.date-now").datepicker("setDate", "now");

    $(".yearpicker").datepicker({
      autoclose: true,
      todayBtn: "linked",
      clearBtn: true,
      todayHighlight: true,
      format: "yyyy",
      startView: "years",
      minViewMode: "years",
      language: "vi",
    });
    $(".yearpicker.date-now").datepicker("setDate", "now");
    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.select2.defaults.set("language", "vi");
    $.fn.select2.defaults.set("width", "100%");
    $.fn.select2.defaults.set("dropdownAutoWidth", true);
    $(".select2").select2({
      placeholder: "Vui lòng chọn",
      closeOnSelect: true,
      allowClear: true,
      debug: false,
    });

    $(".summernote").summernote({
      height: 200,
      tabsize: 2,
      dialogsFade: true,
      toolbar: [
        ["style", ["style"]],
        ["font", ["strikethrough", "superscript", "subscript"]],
        ["font", ["bold", "italic", "underline", "clear"]],
        ["fontsize", ["fontsize"]],
        ["fontname", ["fontname"]],
        ["color", ["color"]],
        ["para", ["ul", "ol", "paragraph"]],
        ["height", ["height"]][("table", ["table"])],
        ["insert", ["link", "picture", "video"]],
        ["view", ["fullscreen", "codeview", "help"]],
      ],
    });

    $(".datetimepicker").datetimepicker({
      //sideBySide: true,
      dayViewHeaderFormat: siteSettings.formatDate,
      format: siteSettings.formatDate + " HH:mm",
      extraFormats: ["YYYY-MM-DDTHH:mm"],
      showTodayButton: true,
      showClear: true,
      showClose: true,
      keepOpen: true,
      toolbarPlacement: "top",
      useCurrent: true,
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar-star",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-calendar-day",
        clear: "fa fa-trash",
        close: "fa fa-times",
      },
    });
    $(".timepicker").datetimepicker({
      format: "LT",
      dayViewHeaderFormat: siteSettings.formatDate,
      format: "HH:mm:ss",
      extraFormats: ["YYYY-MM-DDTHH:mm:ss"],
      showClear: true,
      showClose: true,
      keepOpen: true,
      toolbarPlacement: "top",
      useCurrent: true,
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar-star",
        up: "fa fa-chevron-up",
        down: "fa fa-chevron-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-calendar-day",
        clear: "fa fa-trash",
        close: "fa fa-times",
      },
    });
  });
});
$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
$("table").on("page.dt", function (e) {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

$("table").on("length.dt", function (e) {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});

$("table").on("error.dt", function (e, settings, techNote, message) {
  $.fn.dataTable.tables({ visible: true, api: true }).clear();
  hideLoading();
  showErrorMessage(message);
});

window.addEventListener("resize", () => {
  $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
});
$.extend($.fn.dataTable.defaults, {
  language: {
    url: "https://www.congdongweb.com/wp-content/themes/CongDongTheme/templates/admin/assets/vendor/datatables/i18n/vi.json",
  },
  pageLength: 25,
});
$.fn.dataTable.ext.errMode = "none";

//
$(".dropify").dropify({
  messages: {
    default: "Kéo và thả tệp vào đây hoặc nhấp vào",
    replace: "Kéo và thả tệp hoặc nhấp để thay thế",
    remove: "Xóa",
    error: "Xin lỗi, tệp quá lớn",
  },
});

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
  icon = "error",
  next = null
) {
  Swal.fire({
    title: title,
    html: html,
    icon: icon,
  }).then(() => {
    if (typeof next == "function") {
      next();
    }
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

function callAjax(data, funcSuccess = null, funcError = null, context = null) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: objAdmin.ajax_url,
    data: data,
    beforeSend: function () {
      if ($(context).length != 0) $(context).addClass("admin-loading");
    },
    success: function (res) {
      if (typeof funcSuccess == "function") {
        funcSuccess(res);
      }
      if ($(context).length != 0) $(context).removeClass("admin-loading");
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
      if ($(context).length != 0) $(context).removeClass("admin-loading");
    },
  });
}

function getFormData(action, security, elForm) {
  window.tinyMCE && window.tinyMCE.triggerSave();
  var form_array = elForm.serializeArray();
  var form_data = form_array.reduce(function (obj, item) {
    if (obj[item.name]) {
      if (Array.isArray(obj[item.name])) {
        obj[item.name].push(item.value);
      } else {
        obj[item.name] = [obj[item.name], item.value];
      }
    } else {
      obj[item.name] = item.value;
    }
    return obj;
  }, {});
  form_data["action"] = action;
  form_data["security"] = security;
  return form_data;
}

function drillDownFormatter(data, type, row, meta) {
  if (row.urlredirect) {
    return (
      '<a class="btn-detail-index" href="' +
      row.urlredirect +
      '">' +
      (data != "" ? data : "---") +
      "</a>"
    );
  } else {
    return data;
  }
}

function actionDetailFormatter(data, type, row, meta) {
  return [
    '<a class="btn edit text-warning text-center px-0 py-0" href="javascript:void(0)" title="Edit">',
    '<i class="fa fa-edit"></i>',
    "</a>  ",
    '<a class="btn delete text-danger text-center px-0 py-0" href="javascript:void(0)" title="Delete">',
    '<i class="fa fa-times-circle"></i>',
    "</a>",
  ].join("");
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

function numberFormatterPercent(data, type, row, meta) {
  if (!isNaN(parseFloat(data))) {
    return accounting.formatNumber(
      data,
      siteSettings.roundPercent,
      siteSettings.thousandsSymbol,
      siteSettings.decimalSymbol
    );
  } else {
    return data;
  }
}

function numberFormatterPacking(data, type, row, meta) {
  if (!isNaN(parseFloat(data))) {
    return accounting.formatNumber(
      data,
      siteSettings.roundPacking,
      siteSettings.thousandsSymbol,
      siteSettings.decimalSymbol
    );
  } else {
    return data;
  }
}

function numberFormatterQuantity(data, type, row, meta) {
  if (!isNaN(parseFloat(data))) {
    return accounting.formatNumber(
      data,
      siteSettings.roundQuantity,
      siteSettings.thousandsSymbol,
      siteSettings.decimalSymbol
    );
  } else {
    return data;
  }
}

function numberFormatterAmount(data, type, row, meta) {
  if (!isNaN(parseFloat(data))) {
    return accounting.formatNumber(
      data,
      siteSettings.roundAmount,
      siteSettings.thousandsSymbol,
      siteSettings.decimalSymbol
    );
  } else {
    return data;
  }
}

function numberFormatterAmountVND(data, type, row, meta) {
  if (!isNaN(parseFloat(data))) {
    return accounting.formatNumber(
      data,
      siteSettings.roundAmountVND,
      siteSettings.thousandsSymbol,
      siteSettings.decimalSymbol
    );
  } else {
    return data;
  }
}

function dateFormatter(data, type, row, meta) {
  if (!data) return data;

  let date = moment(data, dateFormat);
  return date.isValid()
    ? date.year() === 1900
      ? ""
      : date.format(siteSettings.formatDate)
    : data;
}
function timeFormatter(data, type, row, meta) {
  if (!data) return data;

  let date = moment(data, dateFormat);
  return date.isValid() ? date.format("HH:mm") : data;
}

function bindDragScroll($container, $scroller) {
  var $window = $(window);

  var x = 0;
  var y = 0;

  var x2 = 0;
  var y2 = 0;
  var t = 0;

  $container.on("mousedown", down);
  $container.on("click", preventDefault);
  $scroller.on("mousewheel", horizontalMouseWheel); // prevent macbook trigger prev/next page while scrolling

  function down(evt) {
    //alert("down");
    if (evt.button === 0) {
      t = Date.now();
      x = x2 = evt.pageX;
      y = y2 = evt.pageY;

      $container.addClass("down");
      $window.on("mousemove", move);
      $window.on("mouseup", up);

      evt.preventDefault();
    }
  }

  function move(evt) {
    // alert("move");
    if ($container.hasClass("down")) {
      var _x = evt.pageX;
      var _y = evt.pageY;
      var deltaX = _x - x;
      var deltaY = _y - y;

      $scroller[0].scrollLeft -= deltaX;

      x = _x;
      y = _y;
    }
  }

  function up(evt) {
    $window.off("mousemove", move);
    $window.off("mouseup", up);

    var deltaT = Date.now() - t;
    var deltaX = evt.pageX - x2;
    var deltaY = evt.pageY - y2;
    if (deltaT <= 300) {
      $scroller.stop().animate(
        {
          scrollTop: "-=" + deltaY * 3,
          scrollLeft: "-=" + deltaX * 3,
        },
        500,
        function (x, t, b, c, d) {
          // easeOutCirc function from http://gsgd.co.uk/sandbox/jquery/easing/
          return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
        }
      );
    }

    t = 0;

    $container.removeClass("down");
  }

  function preventDefault(evt) {
    if (x2 !== evt.pageX || y2 !== evt.pageY) {
      evt.preventDefault();
      return false;
    }
  }

  function horizontalMouseWheel(evt) {
    evt = evt.originalEvent;
    var x = $scroller.scrollLeft();
    var max = $scroller[0].scrollWidth - $scroller[0].offsetWidth;
    var dir = evt.deltaX || evt.wheelDeltaX;
    var stop = dir > 0 ? x >= max : x <= 0;
    if (stop && dir) {
      evt.preventDefault();
    }
  }
}
