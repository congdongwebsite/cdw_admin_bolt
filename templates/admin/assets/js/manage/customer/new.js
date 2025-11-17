$(function () {
  "use strict";

  $(document).ready(function () {
    newCustomer.initialize();
  });
});

function actionDomainFormatter(data, type, row, meta) {
  return (
    `<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <a href="` +
    row.urlUpdateDNS +
    `" target="_blank" class="btn btn-sm btn-secondary">DNS</a>
    <a href="` +
    row.urlUpdateRecord +
    `" target="_blank" class="btn btn-sm btn-primary">Bản ghi</a>  
  </div>`
  );
}
var newCustomer = (function (
  self,
  base,
  hostings,
  domains,
  themes,
  billings,
  plugins,
  emails
) {
  base.form = $("form.form-new-customer-small");
  base.action = "ajax_new-customer";
  let imagesContainer = $("#aniimated-thumbnials");

  //Hosting
  hostings.tb = $("#tb-hostings");
  hostings.modalName = "#modal-add-hosting";
  hostings.formName = "#modal-add-hosting-form";
  hostings.column = [
    { data: "type_label", title: "Gói" },
    { data: "buy_date", title: "Thời gian mua" },
    { data: "expiry_date", title: "Thời gian hết hạn" },
    { data: "price", title: "Giá" },
    { data: "cpu", title: "CPU" },
    { data: "ram", title: "RAM" },
    { data: "hhd", title: "Dung lượng" },
    { data: "ip", title: "IP" },
    { data: "port", title: "Port" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
  ];
  hostings.columnDefs = [
    {
      targets: 3,
      render: numberFormatterAmountVND,
    },
    {
      targets: [1, 2],
      render: dateFormatter,
    },
  ];

  hostings.modalHide = () => {
    PriceHosting.set(0);
  };

  hostings.createModel = () => {
    return {
      ip: $("#ip", hostings.modalName).val(),
      port: $("#port", hostings.modalName).val(),
      user: $("#user", hostings.modalName).val(),
      pass: $("#pass", hostings.modalName).val(),
      cpu: "",
      ram: "",
      hhd: "",
      type: $("#type", hostings.modalName).val(),
      type_label: $("#type option:selected", hostings.modalName).text(),
      buy_date: $("#buy-date", hostings.modalName).val(),
      expiry_date: $("#expiry-date", hostings.modalName).val(),
      price: PriceHosting.getNumber(),
    };
  };
  hostings.bindingModel = (model) => {
    $("#ip", hostings.modalName).val(model.ip);
    $("#port", hostings.modalName).val(model.port);
    $("#user", hostings.modalName).val(model.user);
    $("#pass", hostings.modalName).val(model.pass);
    $("#type", hostings.modalName).val(model.type);
    $("#buy-date", hostings.modalName).datepicker(
      "update",
      model.buy_date?.formatDate()
    );
    $("#expiry-date", hostings.modalName).datepicker(
      "update",
      model.expiry_date?.formatDate()
    );
    PriceHosting.set(model.price);
  };

  hostings.getData = () => {
    let items = [];

    hostings.api.data().each(function (row, index) {
      let item = {
        ip: row.ip,
        port: row.port,
        user: row.user,
        pass: row.pass,
        type: row.type,
        buy_date: row.buy_date,
        expiry_date: row.expiry_date,
        price: row.price,
      };

      items.push(item);
    });

    return items;
  };
  //end hosting
  //domain
  domains.tb = $("#tb-domains");
  domains.modalName = "#modal-add-domain";
  domains.formName = "#modal-add-domain-form";
  domains.column = [
    { data: "url", title: "Website" },
    { data: "price", title: "Giá" },
    { data: "domain-type_label", title: "Loại" },
    { data: "buy_date", title: "Thời gian mua" },
    { data: "expiry_date", title: "Thời gian hết hạn" },
    // { data: "url_dns", title: "URL DNS" },
    // { data: "ip", title: "IP" },
    // { data: "user", title: "Tài khoản" },
    // { data: "pass", title: "Mật khẩu" },
    // { data: "note", title: "Ghi chú" },
    { data: "action", title: "Hành động" },
  ];
  domains.columnDefs = [
    {
      targets: 1,
      render: numberFormatterAmountVND,
    },
    {
      targets: [3, 4],
      render: dateFormatter,
    },
    {
      targets: 5,
      render: actionDomainFormatter,
    },
  ];

  domains.modalHide = () => {
    PriceDomain.set(0);
  };

  domains.createModel = () => {
    return {
      url: $("#url", domains.modalName).val(),
      price: PriceDomain.getNumber(),
      "domain-type": $("#domain-type", domains.modalName).val(),
      "domain-type_label": $(
        "#domain-type option:selected",
        domains.modalName
      ).text(),
      buy_date: $("#buy-date", domains.modalName).val(),
      expiry_date: $("#expiry-date", domains.modalName).val(),
      // url_dns: $("#url-dns", domains.modalName).val(),
      // ip: $("#ip", domains.modalName).val(),
      // user: $("#user", domains.modalName).val(),
      // pass: $("#pass", domains.modalName).val(),
      // note: $("#note", domains.modalName).val(),
    };
  };
  domains.bindingModel = (model) => {
    $("#url", domains.modalName).val(model.url);
    PriceDomain.set(model.price);
    $("#domain-type", domains.modalName)
      .val(model["domain-type"])
      .trigger("change");
    $("#buy-date", domains.modalName).datepicker(
      "update",
      model.buy_date?.formatDate()
    );
    $("#expiry-date", domains.modalName).datepicker(
      "update",
      model.expiry_date?.formatDate()
    );
    // $("#url-dns", domains.modalName).val(model.url_dns);
    // $("#ip", domains.modalName).val(model.ip);
    // $("#user", domains.modalName).val(model.user);
    // $("#pass", domains.modalName).val(model.pass);
    // $("#note", domains.modalName).val(model.note);
  };

  domains.getData = () => {
    let items = [];

    domains.api.data().each(function (row, index) {
      let item = {
        url: row.url,
        price: row.price,
        "domain-type": row["domain-type"],
        buy_date: row.buy_date,
        expiry_date: row.expiry_date,
        // url_dns: row.url_dns,
        // ip: row.ip,
        // user: row.user,
        // pass: row.pass,
        // note: row.note,
      };

      items.push(item);
    });

    return items;
  };
  //end domain
  //theme
  themes.tb = $("#tb-themes");
  themes.modalName = "#modal-add-theme";
  themes.formName = "#modal-add-theme-form";
  themes.column = [
    { data: "date", title: "Ngày mua" },
    { data: "site-type_label", title: "Giao diện" },
    { data: "price", title: "Giá" },
    { data: "name", title: "Thông tin" },
  ];
  themes.columnDefs = [
    {
      targets: 2,
      render: numberFormatterAmountVND,
    },
    {
      targets: 0,
      render: dateFormatter,
    },
  ];
  themes.modalHide = () => {
    PriceTheme.set(0);
  };

  themes.createModel = () => {
    return {
      date: $("#date", themes.modalName).val(),
      name: $("#name", themes.modalName).val(),
      price: PriceTheme.getNumber(),
      "site-type": $("#site-type", themes.modalName).val(),
      "site-type_label": $(
        "#site-type option:selected",
        themes.modalName
      ).text(),
    };
  };
  themes.bindingModel = (model) => {
    $("#date", themes.modalName).datepicker("update", model.date?.formatDate());
    $("#name", themes.modalName).val(model.name);
    $("#site-type", themes.modalName).val(model["site-type"]).trigger("change");
    PriceTheme.set(model.price);
  };

  themes.getData = () => {
    let items = [];

    themes.api.data().each(function (row, index) {
      let item = {
        date: row.date,
        name: row.name,
        price: row.price,
        "site-type": row["site-type"],
      };

      items.push(item);
    });

    return items;
  };
  //end theme

  //billing
  billings.tb = $("#tb-billings");
  billings.modalName = "#modal-add-billing";
  billings.formName = "#modal-add-billing-form";
  billings.column = [
    { data: "date", title: "Ngày thanh toán" },
    { data: "note", title: "Nội dung thanh toán" },
    { data: "amount", title: "Tiền" },
  ];
  billings.columnDefs = [
    {
      targets: 2,
      render: numberFormatterAmountVND,
    },
    {
      targets: 0,
      render: dateFormatter,
    },
  ];
  billings.modalHide = () => {
    Amount.set(0);
  };

  billings.createModel = () => {
    return {
      date: $("#date", billings.modalName).val(),
      note: $("#note", billings.modalName).val(),
      amount: Amount.getNumber(),
    };
  };
  billings.bindingModel = (model) => {
    $("#date", billings.modalName).datepicker(
      "update",
      model.date?.formatDate()
    );
    $("#note", billings.modalName).val(model.note);
    Amount.set(model.amount);
  };

  billings.getData = () => {
    let items = [];

    billings.api.data().each(function (row, index) {
      let item = {
        date: row.date,
        note: row.note,
        amount: row.amount,
      };

      items.push(item);
    });

    return items;
  };
  //end billing

  //plugin
  plugins.tb = $("#tb-plugins");
  plugins.modalName = "#modal-add-plugin";
  plugins.formName = "#modal-add-plugin-form";
  plugins.column = [
    { data: "date", title: "Ngày mua" },
    { data: "expiry_date", title: "Thời gian hết hạn" },
    { data: "plugin-type_label", title: "Plugin" },
    { data: "price", title: "Giá" },
    { data: "license", title: "Giấy phép" },
    { data: "name", title: "Thông tin" },
  ];
  plugins.columnDefs = [
    {
      targets: 3,
      render: numberFormatterAmountVND,
    },
    {
      targets: [0,1],
      render: dateFormatter,
    },
  ];
  plugins.action = "ajax_load-customer-plugin";
  plugins.security = base.security;
  plugins.id = $("#id", base.form).val();
  plugins.modalHide = () => {
    PricePlugin.set(0);
  };

  plugins.createModel = (data) => {
    return {
      id: data ? data.id : "",
      date: $("#date", plugins.modalName).val(),
      expiry_date: $("#expiry-date", plugins.modalName).val(),
      name: $("#name", plugins.modalName).val(),
      license: $("#license", plugins.modalName).val(),
      price: PricePlugin.getNumber(),
      "plugin-type": $("#plugin-type", plugins.modalName).val(),
      "plugin-type_label": $(
        "#plugin-type option:selected",
        plugins.modalName
      ).text(),
      change: true,
    };
  };
  plugins.bindingModel = (model) => {
    $("#date", plugins.modalName).datepicker(
      "update",
      model.date?.formatDate()
    );
    $("#buy-date", plugins.modalName).datepicker(
      "update",
      model.buy_date?.formatDate()
    );
    $("#name", plugins.modalName).val(model.name);
    $("#license", plugins.modalName).val(model.license);
    $("#plugin-type", plugins.modalName)
      .val(model["plugin-type"])
      .trigger("change");
    PricePlugin.set(model.price);
  };

  plugins.getData = () => {
    let items = [];

    plugins.api.data().each(function (row, index) {
      let item = {
        id: row.id,
        date: row.date,
        expiry_date: row.expiry_date,
        name: row.name,
        license: row.license,
        price: row.price,
        "plugin-type": row["plugin-type"],
        change: row.change,
      };

      items.push(item);
    });

    return items;
  };
  //end plugin

  //Email
  emails.tb = $("#tb-emails");
  emails.modalName = "#modal-add-email";
  emails.formName = "#modal-add-email-form";
  emails.column = [
    { data: "email-type_label", title: "Gói" },
    { data: "domain", title: "Domain" },
    { data: "buy_date", title: "Thời gian mua" },
    { data: "expiry_date", title: "Thời gian hết hạn" },
    { data: "price", title: "Giá" },
    { data: "url_admin", title: "Url Admin" },
    { data: "url_client", title: "Url Client" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
  ];
  emails.columnDefs = [
    {
      targets: 4,
      render: numberFormatterAmountVND,
    },
    {
      targets: [2, 3],
      render: dateFormatter,
    },
  ];
  emails.action = "ajax_load-customer-email";
  emails.security = base.security;
  emails.id = $("#id", base.form).val();
  emails.modalHide = () => {
    PriceEmail.set(0);
  };

  emails.createModel = (data) => {
    return {
      id: data ? data.id : "",
      domain: $("#domain", emails.modalName).val(),
      url_admin: $("#url_admin", emails.modalName).val(),
      url_client: $("#url_client", emails.modalName).val(),
      user: $("#user", emails.modalName).val(),
      pass: $("#pass", emails.modalName).val(),
      "email-type": $("#email-type", emails.modalName).val(),
      "email-type_label": $(
        "#email-type option:selected",
        emails.modalName
      ).text(),
      buy_date: $("#buy-date", emails.modalName).val(),
      expiry_date: $("#expiry-date", emails.modalName).val(),
      price: PriceEmail.getNumber(),
      change: true,
    };
  };
  emails.bindingModel = (model) => {
    $("#domain", emails.modalName).val(model.domain);
    $("#url_admin", emails.modalName).val(model.url_admin);
    $("#url_client", emails.modalName).val(model.url_client);
    $("#user", emails.modalName).val(model.user);
    $("#pass", emails.modalName).val(model.pass);
    $("#email-type", emails.modalName)
      .val(model["email-type"])
      .trigger("change");
    $("#buy-date", emails.modalName).datepicker(
      "update",
      model.buy_date?.formatDate()
    );
    $("#expiry-date", emails.modalName).datepicker(
      "update",
      model.expiry_date?.formatDate()
    );
    PriceEmail.set(model.price);
  };

  emails.getData = () => {
    let items = [];

    emails.api.data().each(function (row, index) {
      let item = {
        id: row.id,
        domain: row.domain,
        url_admin: row.url_admin,
        url_client: row.url_client,
        user: row.user,
        pass: row.pass,
        "email-type": row["email-type"],
        buy_date: row.buy_date,
        expiry_date: row.expiry_date,
        price: row.price,
        change: row.change,
      };

      items.push(item);
    });

    return items;
  };
  //end email

  base.save = (e) => {
    if (!base.form.parsley().validate()) return;
    var form_data = getFormData(base.action, base.security, base.form);

    form_data['dvhc-tp-label'] = $('#dvhc-tp option:selected', base.form).text();
    form_data['dvhc-px-label'] = $('#dvhc-px option:selected', base.form).text();

    form_data["hostings"] = hostings.getData();
    form_data["domains"] = domains.getData();
    form_data["themes"] = themes.getData();
    form_data["billings"] = billings.getData();
    form_data["plugins"] = plugins.getData();
    form_data["emails"] = emails.getData();

    showLoading(
      $.ajax({
        type: "POST",
        dataType: "json",
        url: objAdmin.ajax_url,
        data: form_data,
        beforeSend: function () {},
        success: function (res) {
          if (res.success) {
            self.saveImage(res.data.id);
            showSuccessMessage(
              () => {
                window.location.href = $("#urlredirect").val();
              },
              res.data.msg,
              "Lưu thành công"
            );
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
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
          showErrorMessage(msg);
        },
      })
    );
  };
  self.saveImage = (id) => {
    if (!base.form.parsley().validate()) return;

    var formData = new FormData();
    formData.append("action", "ajax_new-image-customer");
    formData.append("security", base.security);
    formData.append("id", id);

    let i = 0;
    let j = 0;

    $(".item", imagesContainer).map((index, value) => {
      let dt = $(value).data();
      if (dt.idFile == -1 && dt.file) {
        formData.append("files[" + i++ + "]", dt.file);
      } else {
        formData.append("id_exsits[" + j++ + "]", dt.idFile);
      }
    });

    $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: formData,
      async: false,
      processData: false,
      contentType: false,
      beforeSend: function () {},
      success: function (res) {
        if (res.success) {
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
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
        showErrorMessage(msg);
      },
    });
  };
  self.addEvent = () => {
    var drEvent = $("#file-images");
    drEvent.on("change", function () {
      $(".item .remove", imagesContainer).off("click");
      imagesContainer.data("lightGallery").destroy(true);

      var fileInput = $("#file-images").get(0).files;

      for (let i = 0; i < fileInput.length; i++) {
        const file = fileInput[i];
        const image = $(`<div class="col-lg-3 col-md-4 col-sm-12 item">
        <div class="card " data-id-file="<?php echo $attachment->ID; ?>">
            <div class="file">
                <a class="image" href="">
                    <div class="hover">
                        <button type="button" class="btn btn-icon btn-danger remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="image">
                        <img src="" alt="" class="img-fluid img-thumbnail">

                    </div>
                    <div class="file-name">
                        <p class="m-b-5 text-muted">img21545ds.jpg</p>
                        <small>Size: <span class="size"></span>MB <span class="date text-muted"></span></small>
                    </div>
                </a>
            </div>
        </div>
    </div>`);
        image.data("file", file);
        image.data("idFile", "-1");

        $("a.image", image).attr("href", URL.createObjectURL(file));
        $(".file-name p", image).text(file.name);
        $(".file-name .size", image).text((file.size / 1024 / 1024).toFixed(2));
        const date = new Date();
        date.setTime(file.lastModified);

        const options = {
          year: "numeric",
          month: "numeric",
          day: "numeric",
        };
        const formattedDate = date.toLocaleDateString("vi-VN", options);

        $(".file-name .date", image).text(formattedDate);

        const img = $("div.image img", image).attr(
          "src",
          URL.createObjectURL(file)
        );
        if (file.type == "application/pdf") {
          img.attr(
            "src",
            "/wp-content/uploads/2023/02/free-pdf-file-icon-thumb.png"
          );
        }

        imagesContainer.append(image);
      }

      imagesContainer.lightGallery({
        thumbnail: true,
        selector: ".item a.image",
      });

      $(".item .remove", imagesContainer).on("click", (e) => {
        self.removeImage(e);
      });
    });

    $(".item .remove", imagesContainer).on("click", (e) => {
      self.removeImage(e);
    });
  };

  self.removeImage = (e) => {
    imagesContainer.data("lightGallery").destroy(true);
    e.preventDefault();
    $(e.currentTarget).closest(".item").remove();

    imagesContainer.lightGallery({
      thumbnail: true,
      selector: ".item a.image",
    });
  };
  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      base.initialize();
      hostings.initialize();
      domains.initialize();
      themes.initialize();
      billings.initialize();
      plugins.initialize();
      emails.initialize();

      [Amount, PriceTheme, PriceDomain, PriceHosting, PricePlugin, PriceEmail] =
        AutoNumeric.multiple(
          [
            billings.modalName + " #amount",
            themes.modalName + " #price",
            domains.modalName + " #price",
            hostings.modalName + " #price",
            plugins.modalName + " #price",
            emails.modalName + " #price",
          ],
          siteSettings.OptionAutoNumericAmountVND
        );
      initdatepickerlink("buy-date", "expiry-date", hostings.modalName);
      initdatepickerlink("buy-date", "expiry-date", domains.modalName);
      initSelect2Domains("domain-type", domains.modalName);
      initSelect2Sites("site-type", themes.modalName);
      initSelect2Plugins("plugin-type", plugins.modalName);
      initdatepickerlink("buy-date", "expiry-date", plugins.modalName);
      initdatepickerlink("buy-date", "expiry-date", emails.modalName);
      initSelect2BillingStatus("status", billings.modalName);

      initSelect2DVHCTPWard_iNET("dvhc-tp", base.form, "dvhc-px");
      $('#dvhc-tp', base.form).on('change', function(){
          var selectedText = $(this).find('option:selected').text();
          $('#dvhc-tp-label', base.form).val(selectedText);
      });
      $('#dvhc-px', base.form).on('change', function(){
          var selectedText = $(this).find('option:selected').text();
          $('#dvhc-px-label', base.form).val(selectedText);
      });
      initSelect2Hosting("type", hostings.modalName);
      initSelect2Email("email-type", emails.modalName);
      $(".datepicker").datepicker();

      imagesContainer.lightGallery({
        thumbnail: true,
        selector: ".item a.image",
      });

      self.addEvent();
    }
  };

  return self;
})(
  {},
  baseNewPostType({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({})
);
