$(function () {
  "use strict";
  $(document).ready(function () {
    let form = $("form.form-setting-base-small");
    initSelect2DVHCTPWard_iNET("dvhc-tp", form, "dvhc-px");
    form.parsley();
    $(".btn-setting-base", form).on("click", function (e) {
      e.preventDefault();
      if (!form.parsley().validate()) return;

      let security = $("#nonce", form).val();
      let formData = getFormData("ajax_setting-base", security, form);

      let fdata = new FormData();
      Object.keys(formData).forEach((key) => {
        fdata.append(key, formData[key]);
      });
      fdata.append('dvhc-tp-label', $('#dvhc-tp option:selected', form).text());
      fdata.append('dvhc-px-label', $('#dvhc-px option:selected', form).text());
      if ($("#avatar-custom", form)[0].files.length > 0)
        fdata.append("shw_file", $("#avatar-custom", form)[0].files[0]);
      if ($("#id-card-front-custom", form).length && $("#id-card-front-custom", form)[0].files.length > 0)
        fdata.append("id_card_front", $("#id-card-front-custom", form)[0].files[0]);
      if ($("#id-card-back-custom", form).length && $("#id-card-back-custom", form)[0].files.length > 0)
        fdata.append("id_card_back", $("#id-card-back-custom", form)[0].files[0]);

      showLoading(
        $.ajax({
          type: "POST",
          dataType: "json",
          url: objAdmin.ajax_url,
          data: fdata,
          processData: !1,
          contentType: !1,
          beforeSend: function () {},
          success: function (res) {
            hideLoading();
            if (res.success)
              showSuccessMessage(() => {}, res.data.msg, "Thành công");
            else showErrorMessage(res.data.msg);
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
    });

    var readURL = function (input, previewClass) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $(previewClass, form).attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    };

    $("#avatar-custom", form).on("change", function () {
      readURL(this, ".avatar");
    });

    $("#btn-upload-photo", form).on("click", function () {
      $("#avatar-custom", form).click();
    });

    $("#id-card-front-custom", form).on("change", function () {
      readURL(this, ".id-card-front-preview");
    });

    $("#btn-upload-id-card-front", form).on("click", function () {
      $("#id-card-front-custom", form).click();
    });

    $("#id-card-back-custom", form).on("change", function () {
      readURL(this, ".id-card-back-preview");
    });

    $("#btn-upload-id-card-back", form).on("click", function () {
      $("#id-card-back-custom", form).click();
    });

    let formaccount = $("form.form-setting-account-small");
    formaccount.parsley();
    $(".btn-setting-account", formaccount).on("click", function (e) {
      e.preventDefault();
      if (!formaccount.parsley().validate()) return;

      let security = $("#nonce", formaccount).val();
      let encryption = new Encryption();
      let formData = getFormData("ajax_setting-account", security, formaccount);

      formData["current-password"] = encryption.encrypt(
        formData["current-password"],
        security
      );

      formData["new-password"] = encryption.encrypt(
        formData["new-password"],
        security
      );
      formData["confirm-password"] = encryption.encrypt(
        formData["confirm-password"],
        security
      );

      callAjaxLoading(
        formData,
        (res) => {
          if (res.success) {
            showSuccessMessage(() => {}, res.data.msg, "Thành công");
          } else {
            showErrorMessage(res.data.msg);
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });

    $(".datepicker").each(function () {
      $(this).datepicker("update", $(this).val()?.formatDate());
    });
  });
});
