$(function () {
  "use strict";

  //  Use by Device
  $(document).ready(function () {
    load_page_secondary();
    load_page_list_VPS();
    load_page_list_domain();
    loadWidgetMoney();
    $(".page-secondary").on("click", () => {
      load_page_secondary();
    });
    $(".reload", $(".page-list-vps")).on("click", () => {
      load_page_list_VPS();
    });
    $(".reload", $(".page-list-domain")).on("click", () => {
      load_page_list_domain();
    });
    $(".widgets-money").on("click", () => {
      loadWidgetMoney();
    });
  });
  function update_item_page(context, value, level, precent, sparkline) {
    $(".value", context).text(value);
    if (level && level == "up") {
      $(".level", context).addClass("fa-level-up");
      $(".level", context).text(precent + "%");
    } else if (level && level == "down") {
      $(".level", context).addClass("fa-level-down");
      $(".level", context).text(precent + "%");
    }
    sparkline &&
      $(".sparkline", context).sparkline(sparkline, {
        type: "line",
        width: "100%",
        height: "100",
        chartRangeMax: 50,
        resize: true,
        lineColor: "#51aaed",
        fillColor: "#60bafd",
        highlightLineColor: "rgba(0,0,0,.1)",
        highlightSpotColor: "rgba(0,0,0,.2)",
      });
  }
  function load_page_secondary() {
    let security = $("#index-nonce").val();
    let context = $(".page-secondary");

    let data = {
      action: "ajax_page-secondary",
      security: security,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(".value", context).hide();
        $(".i-loading", context).show();
      },
      success: function (res) {
        if (res.success) {
          $(".value", context).show();

          update_item_page(
            $(".page-secondary-1", context),
            res.data.item1_value,
            res.data.item1_level,
            res.data.item1_percent
          );
          update_item_page(
            $(".page-secondary-2", context),
            res.data.item2_value,
            res.data.item2_level,
            res.data.item2_percent
          );
          update_item_page(
            $(".page-secondary-3", context),
            res.data.item3_value,
            res.data.item3_level,
            res.data.item3_percent
          );
          update_item_page(
            $(".page-secondary-4", context),
            res.data.item4_value,
            res.data.item4_level,
            res.data.item4_percent
          );

          $(".i-loading", context).hide();
        } else {
          console.log("load_page_secondary", res.data.msg);
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
  }

  function load_page_list_VPS() {
    let security = $("#index-nonce").val();
    let context = $(".page-list-vps");

    let data = {
      action: "ajax_page-list-vps",
      security: security,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(".tb-data tbody tr", context).not(".i-loading").remove();
        $(".i-loading", context).show();
      },
      success: function (res) {
        if (res.success) {
          $(".i-loading", context).hide();
          res.data.map((value, index) => {
            $(".tb-data tbody", context).append(
              "<tr><td>" + value.name + "</td><td>" + value.info + "</td></tr>"
            );
          });
        } else {
          console.log("load_page_list_VPS", res.data.msg);
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
  }
  function load_page_list_domain() {
    let security = $("#index-nonce").val();
    let context = $(".page-list-domain");

    let data = {
      action: "ajax_page-list-domain",
      security: security,
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $(".tb-data tbody tr", context).not(".i-loading").remove();
        $(".i-loading", context).show();
      },
      success: function (res) {
        if (res.success) {
          $(".i-loading", context).hide();
          res.data.map((value, index) => {
            $(".tb-data tbody", context).append(
              "<tr><td>" +
                value.domain +
                "</td><td>" +
                value.status +
                "</td><td>" +
                value.buy_date +
                "</td><td>" +
                value.expiry_date +
                "</td></tr>"
            );
          });
        } else {
          console.log("load_page_list_domain", res.data.msg);
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
  }

  function loadWidgetMoney() {
    let security = $("#index-nonce").val();
    callAjax(
      {
        security: security,
        action: "ajax_page-load-widget-data-money",
      },
      (res) => {
        if (res.success) {
          $(".widgets-money .dt .number").text(res.data.dt);
          $(".widgets-money .ps-thu .number").text(res.data.ps_thu);
          $(".widgets-money .ps-chi .number").text(res.data.ps_chi);
          $(".widgets-money .ck .number").text(res.data.ck);
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
