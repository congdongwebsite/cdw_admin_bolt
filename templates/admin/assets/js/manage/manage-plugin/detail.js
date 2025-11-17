$(function () {
  "use strict";

  $(document).ready(function () {
    detailManagePlugin.initialize();
  });
});

var detailManagePlugin = (function (self, base) {
  base.form = $("form.form-detail-manage-plugin-small");
  base.action = "ajax_update-manage-plugin";
  base.actionDelete = "ajax_delete-manage-plugin";
  let imagesContainer = $("#aniimated-thumbnials");

  base.save = (e) => {
    if (!base.form.parsley().validate()) return;

    window.tinyMCE.triggerSave();
    var form_data = getFormData(base.action, base.security, base.form);
    form_data["price"] = Price.getNumber();
    form_data["type"] = $("#type", base.form).val();
    form_data["module_version"] = $("#module-version", base.form).val();
    showLoading(
      $.ajax({
        type: "POST",
        dataType: "json",
        url: objAdmin.ajax_url,
        data: form_data,
        beforeSend: function () {},
        success: function (res) {
          if (res.success) {
            self.saveThumbnail(res.data.id);
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
  self.saveThumbnail = (id) => {
    if (!base.form.parsley().validate()) return;

    var formData = new FormData();
    formData.append("action", "ajax_update-thumbnail-manage-plugin");
    formData.append("security", base.security);
    formData.append("id", id);

    if ($("#thumbnail-custom", base.form)[0].files.length > 0)
      formData.append("file", $("#thumbnail-custom", base.form)[0].files[0]);

    showLoading(
      $.ajax({
        type: "POST",
        dataType: "json",
        url: objAdmin.ajax_url,
        data: formData,
        processData: !1,
        contentType: !1,
        beforeSend: function () {},
        success: function (res) {
          hideLoading();
          if (res.success) {
          } else showErrorMessage(res.data.msg);
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
    formData.append("action", "ajax_update-image-manage-plugin");
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

    $("#thumbnail-custom", base.form).on("change", function () {
      readURL(this);
    });
    $("#btn-upload-photo", base.form).on("click", function () {
      $("#thumbnail-custom", base.form).click();
    });
    var readURL = function (input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $(".thumbnail", base.form).attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    };
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

    [Price] = AutoNumeric.multiple(
      ["form.form-detail-manage-plugin-small #price"],
      siteSettings.OptionAutoNumericAmountVND
    );

    initSelect2PluginType("type", base.form);
    initSelect2ModuleVersion("module-version", base.form);
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
  initDetail({})
);
