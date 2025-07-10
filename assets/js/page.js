$(function () {
  "use strict";

  //  Use by Device
  $(document).ready(function () {
    load_page_secondary();
    load_page_list_hosting();
    load_page_list_domain();
    load_page_list_billing();
   // loadWidgetMoney();

    $(".page-secondary").on("click", () => {
      load_page_secondary();
    });
    $(".reload", $(".page-list-hosting")).on("click", () => {
      load_page_list_hosting();
    });
    $(".reload", $(".page-list-domain")).on("click", () => {
      load_page_list_domain();
    });
    $(".reload", $(".page-list-billing")).on("click", () => {
      load_page_list_billing();
    });
  });
  function update_item_page(context, value) {
    $(".value", context).text(value);
  }
  function load_page_secondary() {
    let security = $("#index-nonce").val();
    let context = $(".page-secondary");

    let data = {
      action: "ajax_page-secondary-user",
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
            res.data.item1_value
          );
          update_item_page(
            $(".page-secondary-2", context),
            res.data.item2_value
          );
          update_item_page(
            $(".page-secondary-3", context),
            res.data.item3_value
          );
          update_item_page(
            $(".page-secondary-4", context),
            res.data.item4_value
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

  function load_page_list_hosting() {
    let security = $("#index-nonce").val();
    let context = $(".page-list-hosting");

    let data = {
      action: "ajax_page-list-hosting-user",
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
          console.log("load_page_list_hosting", res.data.msg);
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
      action: "ajax_page-list-domain-user",
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

  function load_page_list_billing() {
    let security = $("#index-nonce").val();
    let context = $(".page-list-billing");

    let data = {
      action: "ajax_page-list-billing-user",
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
                value.status +
                "</td><td>" +
                value.code +
                "</td><td>" +
                value.date +
                "</td><td>" +
                value.note +
                "</td><td>" +
                value.amount +
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

});
