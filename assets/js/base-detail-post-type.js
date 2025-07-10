var baseDetailPostType = function (self) {
  self.form;
  self.action;
  self.actionDelete;
  self.security = $("#nonce", self.form).val();

  //self.ajaxCount = 0;

  $(document).ajaxSend(function (e, jqXHR, settings) {
    //self.ajaxCount++;
    const params = new URLSearchParams(settings.data);
    const security = params.get("security");
    if (!Swal.isVisible() && security != null && security == self.security) {
      showLoading();
    }
  });

  $(document).ajaxComplete(function () {
    // self.ajaxCount--;
    // if (self.ajaxCount === 0) {
    //   if (Swal.isVisible()) {
    //     hideLoading();
    //   }
    // }
  });
  $(document).ajaxStop(function () {
    if (Swal.isVisible()) {
      hideLoading();
    }
  });
  self.event = () => {
    console.log('self.form',self.form);
    $(".btn-save", self.form).on("click", function (e) {
      e.preventDefault();
      self.BaseSave(e);
    });
    $(".btn-delete", self.form).on("click", function (e) {
      e.preventDefault();
      self.BaseDelete(e);
    });
  };
  self.BaseSave = (e) => {
    Swal.fire({
      title: "Lưu?",
      text: "Bạn có chắc chắn muốn lưu?",
      icon: "question",
      showCancelButton: true,
      focusCancel: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        self.save(e);
      }
    });
  };
  self.BaseDelete = (e) => {
    Swal.fire({
      title: "Xóa bài đăng?",
      text: "Dữ liệu sẽ bị xoá, bạn có chắc chắn muốn xoá không?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        self.delete(e);
      }
    });
  };
  self.delete = (e) => {
    callAjaxLoading(
      {
        id: $("#id").val(),
        action: self.actionDelete,
        security: self.security,
      },
      (res) => {
        if (res.success) {
          showSuccessMessage(
            () => {
              window.location.href = $("#urlredirect").val();
            },
            res.data.msg,
            "Xóa thành công"
          );
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  self.save = (e) => {
    if (!self.form.parsley().validate()) return;

    var form_data = getFormData(self.action, self.security, self.form);

    callAjaxLoading(
      form_data,
      (res) => {
        if (res.success) {
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
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      self.form.parsley();
      self.event();
      $(".datepicker").each(function () {
        $(this).datepicker("update", $(this).val()?.formatDate());
      });
    }
  };

  return self;
};
