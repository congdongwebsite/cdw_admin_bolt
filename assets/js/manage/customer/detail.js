function actionEmailFormatter(data, type, row, meta) {
  if (row.inet_email_id) {
    return (
      `<div class="btn-group" role="group">
        <button type="button" class="btn btn-sm btn-info btn-configure-email-inet" data-id="${row.id}" data-inet-id="${row.inet_email_id}" data-verified="${row._inet_records_verified}">Cấu hình</button>
        <button type="button" class="btn btn-sm btn-warning btn-gen-dkim-email-inet" data-id="${row.id}" data-inet-id="${row.inet_email_id}">Tạo DKIM</button>
        <button type="button" class="btn btn-sm btn-primary btn-change-email-plan" data-id="${row.id}" data-inet-id="${row.inet_email_id}">Đổi gói</button>
       </div>`
    );
  } else if (row.id) {
    return (
      `<div class="btn-group" role="group">
        <button type="button" class="btn btn-sm btn-success btn-register-email-inet" data-id="${row.id}">Đăng ký</button>
       </div>`
    );
  }
  return '';
}

$(function () {
  "use strict";

  $(document).ready(function () {
    detailCustomer.initialize();
  });
});
function actionDomainFormatter(data, type, row, meta) {
  if (row.has_inet_domain_id) {
    return (
      `<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <a href="` +
      row.urlUpdateDNS +
      `" target="_blank" class="btn btn-sm btn-secondary">DNS</a>
      <a href="` +
      row.urlUpdateRecord +
      `" target="_blank" class="btn btn-sm btn-primary">Bản ghi</a>
      <button type="button" class="btn btn-sm btn-warning btn-renew-inet" data-id="` +
      row.id +
      `">Gia hạn</button>
    </div>`
    );
  } else {
    return (
      `<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <button type="button" class="btn btn-sm btn-success btn-register-inet" data-id="` +
      row.id +
      `">Đăng ký</button>
      <button type="button" class="btn btn-sm btn-info btn-sync-inet-info" data-id="` +
      row.id +
      `">Đồng bộ</button>
    </div>`
    );
  }
}
var detailCustomer = (function (
  self,
  base,
  hostings,
  domains,
  themes,
  billings,
  plugins,
  emails,
  logs
) {
  base.form = $("form.form-detail-customer-small");
  base.action = "ajax_update-customer";
  base.actionDelete = "ajax_delete-customer";
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
  hostings.action = "ajax_load-customer-hosting";
  hostings.security = base.security;
  hostings.id = $("#id", base.form).val();
  hostings.modalHide = () => {
    PriceHosting.set(0);
  };

  hostings.createModel = (data) => {
    return {
      id: data ? data.id : "",
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
      change: true,
    };
  };
  hostings.bindingModel = (model) => {
    $("#ip", hostings.modalName).val(model.ip);
    $("#port", hostings.modalName).val(model.port);
    $("#user", hostings.modalName).val(model.user);
    $("#pass", hostings.modalName).val(model.pass);
    $("#type", hostings.modalName).val(model.type).trigger("change");
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
        id: row.id,
        ip: row.ip,
        port: row.port,
        user: row.user,
        pass: row.pass,
        type: row.type,
        buy_date: row.buy_date,
        expiry_date: row.expiry_date,
        price: row.price,
        change: row.change,
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
  domains.action = "ajax_load-customer-domain";
  domains.security = base.security;
  domains.id = $("#id", base.form).val();
  domains.modalHide = () => {
    PriceDomain.set(0);
  };

  domains.createModel = (data) => {
    return {
      id: data ? data.id : "",
      url: $("#url", domains.modalName).val(),
      price: PriceDomain.getNumber(),
      "domain-type": $("#domain-type", domains.modalName).val(),
      "domain-type_label": $(
        "#domain-type option:selected",
        domains.modalName
      ).text(),
      buy_date: $("#buy-date", domains.modalName).val(),
      expiry_date: $("#expiry-date", domains.modalName).val(),
      action: '',
      // url_dns: $("#url-dns", domains.modalName).val(),
      // ip: $("#ip", domains.modalName).val(),
      // user: $("#user", domains.modalName).val(),
      // pass: $("#pass", domains.modalName).val(),
      // note: $("#note", domains.modalName).val(),
      change: true,
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
        id: row.id,
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
        change: row.change,
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
  themes.action = "ajax_load-customer-theme";
  themes.security = base.security;
  themes.id = $("#id", base.form).val();
  themes.modalHide = () => {
    PriceTheme.set(0);
  };

  themes.createModel = (data) => {
    return {
      id: data ? data.id : "",
      date: $("#date", themes.modalName).val(),
      name: $("#name", themes.modalName).val(),
      price: PriceTheme.getNumber(),
      "site-type": $("#site-type", themes.modalName).val(),
      "site-type_label": $(
        "#site-type option:selected",
        themes.modalName
      ).text(),
      change: true,
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
        id: row.id,
        date: row.date,
        name: row.name,
        price: row.price,
        "site-type": row["site-type"],
        change: row.change,
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
    { data: "code", title: "Mã thanh toán" },
    { data: "date", title: "Ngày thanh toán" },
    { data: "note", title: "Nội dung thanh toán" },
    { data: "amount", title: "Tiền" },
    { data: "status_label", title: "Trạng thái" },
  ];
  billings.columnDefs = [
    {
      targets: 1,
      render: dateBillingFormatter,
    },
    {
      targets: 3,
      render: numberFormatterAmountVND,
    },
  ];
  billings.action = "ajax_load-customer-billing";
  billings.security = base.security;
  billings.id = $("#id", base.form).val();
  billings.modalHide = () => {
    Amount.set(0);
  };

  billings.createModel = (data) => {
    return {
      id: data ? data.id : "",
      code: data ? data.code : "",
      date: $("#date", billings.modalName).val(),
      note: $("#note", billings.modalName).val(),
      amount: Amount.getNumber(),
      status: $("#status", billings.modalName).val(),
      status_label: $("#status option:selected", billings.modalName).text(),
      change: true,
    };
  };
  billings.bindingModel = (model) => {
    $("#date", billings.modalName).datepicker(
      "update",
      model.date?.formatDate()
    );
    $("#note", billings.modalName).val(model.note);
    $("#status", billings.status).val(model.status).trigger("change");
    Amount.set(model.amount);
  };

  billings.getData = () => {
    let items = [];

    billings.api.data().each(function (row, index) {
      let item = {
        id: row.id,
        date: row.date,
        note: row.note,
        amount: row.amount,
        status: row.status,
        change: row.change,
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
      targets: [0, 1],
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
    $("#expiry-date", plugins.modalName).datepicker(
      "update",
      model.expiry_date?.formatDate()
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
    { data: "url_admin", title: "URL Admin" },
    { data: "url_client", title: "URL Client" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
    { data: "action", title: "Hành động" },
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
    {
      targets: 9,
      render: actionEmailFormatter,
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
      action: ''
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

  //logs
  logs.tb = $("#tb-logs");
  logs.column = [
    { data: "date", title: "Thời gian" },
    { data: "title", title: "Tiêu đề" },
    { data: "content", title: "Nội dung" },
    { data: "user", title: "Người thực hiện" },
  ];
  logs.buttons = [];
  logs.columnDefs = [
    {
      targets: 0,
      render: (data, type, row, meta) => data.formatDateTime(),
    },
  ];
  logs.action = "ajax_load-customer-log";
  logs.security = base.security;
  logs.id = $("#id", base.form).val();

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
        beforeSend: function () { },
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
    formData.append("action", "ajax_update-image-customer");
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
      beforeSend: function () { },
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
  self.syncToINET = () => {
    if (!base.form.parsley().validate()) return;
    var form_data = getFormData("ajax_sync_customer_to_inet", base.security, base.form);
    Swal.fire({
      title: 'Đồng bộ tài khoản iNet?',
      text: "Bạn có chắc chắn muốn đồng bộ tài khoản iNet?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Có',
      cancelButtonText: 'Không'
    }).then((result) => {
      if (result.value) {
        showLoading(
          $.ajax({
            type: "POST",
            dataType: "json",
            url: objAdmin.ajax_url,
            data: form_data,
            success: function (res) {
              if (res.success) {
                showSuccessMessage(() => {
                  location.reload();
                }, res.data.msg);
              } else {
                showErrorMessage(res.data.msg);
              }
            },
          })
        )
      }
    })
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
    billings.tb.on("click", "td .update-date-by-billing", (e) => {
      e.preventDefault();
      let row = billings.api.row($(e.target).parents("tr"));
      if (row.data()) {
        Swal.fire({
          title: "Bạn muốn cập nhật thông tin?",
          text: "Cập nhật lại thông tin theo hóa đơn?",
          icon: "question",
          showCancelButton: true,
          focusCancel: true,
          confirmButtonText: "Có",
          cancelButtonText: "Không",
        }).then((res) => {
          if (res.value) {
            showLoading(
              $.ajax({
                type: "POST",
                dataType: "json",
                url: objAdmin.ajax_url,
                data: {
                  action: "ajax_update-info-by-billing-customer",
                  id: row.data().id,
                  security: base.security,
                },
                beforeSend: function () { },
                success: function (res) {
                  if (res.success) {
                    showSuccessMessage(
                      () => { },
                      res.data.msg,
                      "Thực hiện thành công"
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
          }
        });
      } else
        Swal.fire({
          title: "Lỗi",
          text: "Không thể tìm thấy dữ liệu?",
          icon: "question",
        });
    });

    $(".btn-sync-inet").on("click", function (e) {
      e.preventDefault();
      self.syncToINET();
    });

    $(".btn-confirm-kyc").on("click", function (e) {
      e.preventDefault();
      $(this).addClass('active').text('Đã chọn Xác nhận');
      $('.btn-reject-kyc').removeClass('active').text('Từ Chối');
      $('#kyc_action').val('confirm');
      $('#rejection-reason-display').hide();
    });

    $("#btn-send-rejection").on("click", function (e) {
      e.preventDefault();
      const reason = $("#rejection-reason").val();
      if (!reason) {
        showErrorMessage('Vui lòng chọn lý do từ chối.');
        return;
      }

      $('.btn-reject-kyc').addClass('active').text('Đã chọn Từ chối');
      $('.btn-confirm-kyc').removeClass('active').text('Xác Nhận');

      $('#kyc_action').val('reject');
      $('#kyc_rejection_reason').val(reason);

      $('#rejection-reason-display').text('Lý do: ' + reason).show();

      $("#modal-reject-kyc").modal('hide');
    });

    domains.tb.on("click", ".btn-register-inet", function (e) {
      e.preventDefault();
      var id = $(this).data("id");
      Swal.fire({
        title: 'Bạn có chắc chắn muốn đăng ký domain này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Đăng ký',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          showLoading(
            $.ajax({
              type: "POST",
              dataType: "json",
              url: objAdmin.ajax_url,
              data: {
                action: "ajax_register_domain_to_inet",
                security: base.security,
                id: id,
              },
              success: function (res) {
                if (res.success) {
                  showSuccessMessage(() => {
                    domains.api.ajax.reload();
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
            })
          );
        }
      });
    });

    domains.tb.on("click", ".btn-sync-inet-info", function (e) {
      e.preventDefault();
      var id = $(this).data("id");
      Swal.fire({
        title: 'Bạn có chắc chắn muốn đồng bộ thông tin domain này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Đồng bộ',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          showLoading(
            $.ajax({
              type: "POST",
              dataType: "json",
              url: objAdmin.ajax_url,
              data: {
                action: "ajax_sync_domain_inet_info",
                security: base.security,
                id: id,
              },
              success: function (res) {
                if (res.success) {
                  showSuccessMessage(() => {
                    domains.api.ajax.reload();
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
            })
          );
        }
      });
    });

    domains.tb.on("click", ".btn-renew-inet", function (e) {
      e.preventDefault();
      var id = $(this).data("id");
      Swal.fire({
        title: 'Bạn có chắc chắn muốn gia hạn domain này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Gia hạn',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          showLoading(
            $.ajax({
              type: "POST",
              dataType: "json",
              url: objAdmin.ajax_url,
              data: {
                action: "ajax_renew_domain_inet",
                security: base.security,
                id: id,
              },
              success: function (res) {
                if (res.success) {
                  showSuccessMessage(() => {
                    domains.api.ajax.reload();
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
            })
          );
        }
      });
    });

    emails.tb.on("click", ".btn-gen-dkim-email-inet", function (e) {
      e.preventDefault();
      var inet_email_id = $(this).data("inet-id");
      var customer_email_id = $(this).data("id"); // Get customer_email_id
      Swal.fire({
        title: 'Bạn có chắc chắn muốn tạo DKIM cho gói email này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Tạo DKIM',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          showLoading(
            $.ajax({
              type: "POST",
              dataType: "json",
              url: objAdmin.ajax_url,
              data: {
                action: "ajax_gen_dkim_email_inet",
                security: base.security,
                inet_email_id: inet_email_id,
                customer_email_id: customer_email_id, // Pass customer_email_id
              },
              success: function (res) {
                if (res.success) {
                  showSuccessMessage(() => {
                    emails.api.ajax.reload();
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
            })
          );
        }
      });
    });

    emails.tb.on("click", ".btn-change-email-plan", function (e) {
      e.preventDefault();
      var customer_email_id = $(this).data("id");
      var inet_email_id = $(this).data("inet-id");

      $('#modal-change-email-plan').modal('show');
      $('#change-email-plan-customer-email-id').val(customer_email_id);
      $('#change-email-plan-inet-email-id').val(inet_email_id);

      // Fetch current email plan details
      callAjax(
        {
          action: 'ajax_get_email_detail_inet', // This action already exists in AjaxManageEmail
          security: base.security,
          inet_email_id: inet_email_id,
          customer_email_id: customer_email_id, // Pass customer_email_id for consistency
        },
        (res) => {
          if (res.success) {
            var data = res.data;
            $('#current-plan-name').text(data.plan);
            $('#current-plan-domain').text(data.domain);
            $('#current-plan-expiry-date').text(data.expiry_date);

            // Calculate remaining days
            var expiryDate = moment(data.expiry_date, "DD/MM/YYYY");
            var today = moment();
            var remainingDays = expiryDate.diff(today, 'days');
            $('#current-plan-remaining-days').text(remainingDays > 0 ? remainingDays : 0);

          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });

    function ajax_get_email_records_inet(inet_email_id, customer_email_id) {
      var tbody = $('#tb-email-records').find('tbody');
      tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
      $('#btn-step2-next').prop('disabled', true);

      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_get_email_records_inet',
          inet_email_id: inet_email_id,
          customer_email_id: customer_email_id, // Pass customer_email_id
          security: base.security
        },
        dataType: 'json',
        success: function (res) {
          tbody.empty();
          if (res.success) {
            res.data.records.forEach(function (record) {
              var status_html = record.verified
                ? '<span class="text-success">Đã xác thực</span>'
                : '<span class="text-danger">Chưa xác thực</span>';
              tbody.append(`<tr><td>${record.type}</td><td>${record.name}</td><td>${record.value}</td><td>${status_html}</td></tr>`);
            });

            if (res.data.all_verified) {
              $('#btn-step2-next').prop('disabled', false);
            } else {
              $('#btn-step2-next').prop('disabled', true);
            }
            // Dynamic text for btn-check-records-top
            if (res.data.is_verified) {
                $('#btn-check-records-top').text('Kiểm tra lại bản ghi');
            } else {
                $('#btn-check-records-top').text('Kiểm tra bản ghi');
            }

          } else {
            tbody.html('<tr><td colspan="4" class="text-center text-danger">' + res.data.msg + '</td></tr>');
          }
        },
        error: function () {
          tbody.html('<tr><td colspan="4" class="text-center text-danger">Lỗi khi tải bản ghi DNS.</td></td></tr>');
        }
      });
    }

    // Add click handler for the Step 2 tab to refresh records
    $('#register-email-tabs a[href="#step2-records"]').on('click', function (e) {
        e.preventDefault();
        if (!$(this).hasClass('disabled')) {
            var inet_email_id = $('#register-email-inet-email-id').val();
            var customer_email_id = $('#register-email-customer-email-id').val();
            if (inet_email_id && customer_email_id) {
                ajax_get_email_records_inet(inet_email_id, customer_email_id);
            }
            $(this).tab('show');
        }
    });

    emails.tb.on("click", ".btn-register-email-inet", function (e) {
      e.preventDefault();
      var customer_email_id = $(this).data("id");

      $('#modal-register-email').modal('show');
      $('#register-email-customer-email-id').val(customer_email_id);

      $('#form-step1-register-email')[0].reset();
      $('#domain-check-message').empty();
      $('#btn-activate-domain').prop('disabled', true);
      $('#tb-email-records tbody').empty();

      $('#register-email-tabs a[href="#step1-domain"]').removeClass('disabled').tab('show');
      $('#register-email-tabs a[href="#step2-records"]').addClass('disabled');
      $('#register-email-tabs a[href="#step3-finish"]').addClass('disabled');
    });

    emails.tb.on('click', '.btn-configure-email-inet', function (e) {
        e.preventDefault();
        var inet_email_id = $(this).data('inet-id');
        var customer_email_id = $(this).data('id');

        $('#modal-register-email').modal('show');

        $('#register-email-inet-email-id').val(inet_email_id);
        $('#register-email-customer-email-id').val(customer_email_id);

        // Clear previous state
        $('#form-step1-register-email')[0].reset();
        $('#domain-check-message').empty();
        $('#tb-email-records tbody').empty();
        $('#admin-password-section').hide();

        showLoading($.ajax({
            url: objAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'ajax_get_email_detail_inet',
                inet_email_id: inet_email_id,
                customer_email_id: customer_email_id,
                security: base.security
            },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    if (res.data.is_verified) {
                        // Already verified, jump to Step 3
                        var package_info = $('#email-package-info');
                        var admin_info = $('#email-admin-info');
                        var data = res.data;

                        $('.quota', package_info).text(data.quota);
                        $('.accounts', package_info).text(data.accounts);
                        $('.groups', package_info).text(data.groups);
                        $('.status', package_info).text(data.status);
                        $('.expiry-date', package_info).text(data.expiry_date);
                        $('.plan-name', package_info).text(data.plan);
                        $('.domain', package_info).text(data.domain);
                        $('.created-date', package_info).text(data.created_date);

                        $('.admin-url', admin_info).attr('href', data.admin_url).text(data.admin_url);
                        $('.admin-email', admin_info).text(data.admin_email);
                        $('.client-url', admin_info).attr('href', data.client_url).text(data.client_url);

                        $('#register-email-tabs a[href="#step3-finish"]').removeClass('disabled').tab('show');
                        $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled');
                        $('#register-email-tabs a[href="#step1-domain"]').addClass('disabled');
                    } else {
                        // Not verified, go to Step 2
                        $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled').tab('show');
                        $('#register-email-tabs a[href="#step1-domain"]').addClass('disabled');
                        $('#register-email-tabs a[href="#step3-finish"]').addClass('disabled');
                        ajax_get_email_records_inet(inet_email_id, customer_email_id);
                    }
                } else {
                    showErrorMessage(res.data.msg || 'Lỗi khi lấy chi tiết email.');
                    $('#modal-register-email').modal('hide');
                }
            },
            error: function () {
                showErrorMessage('Lỗi khi tải dữ liệu. Vui lòng thử lại.');
                $('#modal-register-email').modal('hide');
            },
            complete: function() {
                hideLoading();
            }
        }));
    });

    $('#btn-check-domain-step1').on('click', function () {
      var domain_name = $('#register-email-domain').val();
      var customer_email_id = $('#register-email-customer-email-id').val();

      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_check_email_domain_available_inet',
          domain: domain_name,
          customer_email_id: customer_email_id,
          security: base.security
        },
        dataType: 'json',
        beforeSend: function () { showLoading(); },
        success: function (res) {
          var messageDiv = $('#domain-check-message');
          if (res.success) {
            messageDiv.html('<span class="text-success">Tên miền có thể sử dụng.</span>');
            $('#btn-activate-domain').prop('disabled', false);
          } else {
            messageDiv.html(`<span class="text-danger">${res.data.msg}</span>`);
            $('#btn-activate-domain').prop('disabled', true);
          }
        },
        complete: function () { hideLoading(); }
      });
    });

    $('#btn-activate-domain').on('click', function () {
      var domain_name = $('#register-email-domain').val();
      var customer_id = $("#id", base.form).val();
      var customer_email_id = $('#register-email-customer-email-id').val();

      $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled').tab('show');
      
      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_create_email_package_inet',
          domain: domain_name,
          customer_id: customer_id,
          customer_email_id: customer_email_id,
          security: base.security
        },
        dataType: 'json',
        beforeSend: function () {
          showLoading();
          // $('#tb-email-records').find('tbody').html('<tr><td colspan="4" class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
        },
        success: function (res) {
          var messageDiv = $('#domain-check-message');
          if (res.success) {
            $('#register-email-inet-email-id').val(res.data.id);
            ajax_get_email_records_inet(res.data.id, customer_email_id);
          } else {
            messageDiv.html(`<span class="text-danger">${res.data.msg}</span>`);
            $('#register-email-tabs a[href="#step1-domain"]').tab('show');
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          showErrorMessage('Lỗi khi tạo gói email: ' + textStatus);
          $('#register-email-tabs a[href="#step1-domain"]').tab('show');
        },
        complete: function () { hideLoading(); }
      });
    });

    $('#btn-check-records-top').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      if (inet_email_id) {
        var customer_email_id = $('#register-email-customer-email-id').val(); // Get customer_email_id
        ajax_get_email_records_inet(inet_email_id, customer_email_id);
      } else {
        showErrorMessage('Không tìm thấy ID gói email để tải lại bản ghi.');
      }
    });

    $('#btn-gen-dkim-modal').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      var customer_email_id = $('#register-email-customer-email-id').val();
      if (inet_email_id) {
        Swal.fire({
          title: 'Bạn có chắc chắn muốn tạo DKIM cho gói email này?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Tạo DKIM',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.value) {
            showLoading(
              $.ajax({
                type: "POST",
                dataType: "json",
                url: objAdmin.ajax_url,
                data: {
                  action: "ajax_gen_dkim_email_inet",
                  security: base.security,
                  inet_email_id: inet_email_id,
                  customer_email_id: customer_email_id,
                },
                success: function (res) {
                  if (res.success) {
                    showSuccessMessage(() => {
                      ajax_get_email_records_inet(inet_email_id, customer_email_id); // Refresh records after DKIM generation
                    }, res.data.msg);
                  } else {
                    showErrorMessage(res.data.msg);
                  }
                },
              })
            );
          }
        });
      } else {
        showErrorMessage('Không tìm thấy ID gói email để tạo DKIM.');
      }
    });

    $('#btn-step2-next').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      var customer_email_id = $('#register-email-customer-email-id').val();
      if (inet_email_id) {
        $.ajax({
          url: objAdmin.ajax_url,
          type: 'POST',
          data: {
            action: 'ajax_get_email_detail_inet',
            inet_email_id: inet_email_id,
            customer_email_id: customer_email_id,
            security: base.security
          },
          dataType: 'json',
          beforeSend: function () { showLoading(); },
          success: function (clean_data) {
            var package_info = $('#email-package-info');
            var admin_info = $('#email-admin-info');
            var data = clean_data.data;

            $('.quota', package_info).text(data.quota);
            $('.accounts', package_info).text(data.accounts);
            $('.groups', package_info).text(data.groups);
            $('.status', package_info).text(data.status);
            $('.expiry-date', package_info).text(data.expiry_date);
            $('.plan-name', package_info).text(data.plan);
            $('.domain', package_info).text(data.domain);
            $('.created-date', package_info).text(data.created_date);


            $('.admin-url', admin_info).attr('href', data.admin_url).text(data.admin_url);
            $('.admin-email', admin_info).text(data.admin_email);
            $('.client-url', admin_info).attr('href', data.client_url).text(data.client_url);

            $('#register-email-tabs a[href="#step3-finish"]').removeClass('disabled').tab('show');
            $('#register-email-tabs a[href="#step1-domain"]').addClass('disabled');
          },
          error: function (jqXHR) {
            var errorMsg = "Lỗi không xác định.";
            if (jqXHR.responseJSON && jqXHR.responseJSON.data && jqXHR.responseJSON.data.msg) {
              errorMsg = jqXHR.responseJSON.data.msg;
            }
            showErrorMessage(errorMsg);
          }, complete: function () { hideLoading(); }
        });
      }
    });

    $(".btn-reset-kyc").on("click", function (e) {
      e.preventDefault();
      var id = $("#id", base.form).val();
      Swal.fire({
        title: "Bạn có chắc chắn muốn nhập lại KYC?",
        text: "Thao tác này sẽ đặt lại trạng thái KYC thành 'Đang Xác Thực'.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.value) {
          showLoading(
            $.ajax({
              type: "POST",
              dataType: "json",
              url: objAdmin.ajax_url,
              data: {
                action: "ajax_reset_kyc_status",
                security: base.security,
                id: id,
              },
              success: function (res) {
                if (res.success) {
                  showSuccessMessage(() => {
                    location.reload();
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
            })
          );
        }
      });
    });

    $('#btn-confirm-change-email-plan').on('click', function () {
      var customer_email_id = $('#change-email-plan-customer-email-id').val();
      var new_plan_id = $('#new-email-plan').val();

      if (!new_plan_id) {
        showErrorMessage('Vui lòng chọn một gói email mới.', 'Lỗi');
        return;
      }

      Swal.fire({
        title: 'Xác nhận đổi gói Email?',
        text: 'Bạn có chắc chắn muốn đổi gói email này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Đổi gói',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          callAjaxLoading(
            {
              action: 'ajax_change_email_plan', // New AJAX action
              security: base.security,
              customer_email_id: customer_email_id,
              new_plan_id: new_plan_id,
            },
            (res) => {
              if (res.success) {
                showSuccessMessage(() => {
                  $('#modal-change-email-plan').modal('hide');
                  emails.api.ajax.reload(); // Reload email table
                }, res.data.msg);
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
    });

    // Handler for generating password
    $('#modal-register-email').on('click', '.btn-generate-email-password', function() {
        var inet_email_id = $('#register-email-inet-email-id').val();
        if (!inet_email_id) {
            showErrorMessage('Không tìm thấy ID email của iNET.');
            return;
        }

        callAjaxLoading(
            {
                action: 'ajax_reset_email_password_inet',
                security: base.security,
                inet_email_id: inet_email_id,
            },
            (res) => {
                if (res.success) {
                        $('#email-admin-info .admin-password-display').val(res.data.newPassword);
                        // $('#email-admin-info .admin-password-display').attr('type', 'text');
                        $('#email-admin-info .btn-toggle-password-visibility i').removeClass('fa-eye').addClass('fa-eye-slash');
                        $('#admin-password-section').css('display', 'block');
                  
                } else {
                    showErrorMessage(res.msg, "Có lỗi xảy ra!");
                }
            },
            (msg) => {
                showErrorMessage(msg);
            }
        );
    });

    // Handler for toggling password visibility
    $('#modal-register-email').on('click', '.btn-toggle-password-visibility', function() {
        var passwordField = $(this).closest('.input-group').find('.admin-password-display');
        var icon = $(this).find('i');

        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Handler for copying password
    $('#modal-register-email').on('click', '.btn-copy-password', function() {
        var passwordField = $(this).closest('.input-group').find('.admin-password-display');
        var password = passwordField.val();

        if (password) {
            navigator.clipboard.writeText(password).then(() => {
                var copyButton = $(this);
                var originalIcon = copyButton.html();
                copyButton.html('<i class="fa fa-check"></i>');
                setTimeout(function() {
                    copyButton.html(originalIcon);
                }, 2000);
            }).catch(err => {
                showErrorMessage('Không thể sao chép mật khẩu.');
            });
        }
    });
  };

  self.removeImage = (e) => {
    e.preventDefault();

    imagesContainer.data("lightGallery").destroy(true);
    $(e.currentTarget).closest(".item").remove();

    imagesContainer.lightGallery({
      thumbnail: true,
      selector: ".item a.image",
    });
  };
  self.initialize = () => {
    base.initialize();
    hostings.initialize();
    domains.initialize();
    themes.initialize();
    billings.initialize();
    plugins.initialize();
    emails.initialize();
    logs.initialize();

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
    $('#dvhc-tp', base.form).on('change', function () {
      var selectedText = $(this).find('option:selected').text();
      $('#dvhc-tp-label', base.form).val(selectedText);
    });
    $('#dvhc-px', base.form).on('change', function () {
      var selectedText = $(this).find('option:selected').text();
      $('#dvhc-px-label', base.form).val(selectedText);
    });
    initSelect2Hosting("type", hostings.modalName);
    initSelect2Email("email-type", emails.modalName);
    initSelect2Email("new-email-plan", '#modal-change-email-plan');
    initGeneratePassword();
    $(".datepicker").datepicker();

    imagesContainer.lightGallery({
      thumbnail: true,
      selector: ".item a.image",
    });
    self.addEvent();
  };

  return self;
})(
  {},
  baseDetailPostType({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({}),
  initDetail({})
);
