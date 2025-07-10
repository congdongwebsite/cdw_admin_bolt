$(function () {
  "use strict";

  $(document).ready(function () {
    detailTicket.initialize();
  });
});

var detailTicket = (function (self) {
  self.form = $("form.form-detail-ticket-small");
  self.contextList = $(".ticket-detail-list", self.form);
  self.action = "ajax_get-list-detail-ticket";
  self.security;
  let imagesContainer = $("#aniimated-thumbnials");
  self.event = () => {
    $(".btn-reply", self.form).on("click", function (e) {
      e.preventDefault();
      self.save(e);
    });
    $(".btn-close-ticket", self.form).on("click", function (e) {
      e.preventDefault();
      let id = $("#id", self.form).val();
      self.closeTicket(id);
    });

    $(".pagination-detail .pagination-back", self.form).on(
      "click",
      function (e) {
        e.preventDefault();

        let page = $(".pagination-detail", self.form).data().page;
        if (page == 1) return;
        $(".pagination-detail", self.form).data("page", --page);
        let search = "";
        self.loadListDetailTicket(page, search);
      }
    );
    $(".pagination-detail .pagination-next", self.form).on(
      "click",
      function (e) {
        e.preventDefault();
        let total = $(".pagination-detail .total", self.form).text();
        let contrinue = $(e.currentTarget).data().contrinue;
        if (contrinue != 1 || total == 0) return;
        let page = $(".pagination-detail", self.form).data().page;
        $(".pagination-detail", self.form).data("page", ++page);
        let search = "";
        self.loadListDetailTicket(page, search);
      }
    );
  };

  self.closeTicket = (id) => {
    let data = {
      action: "ajax_update-success-ticket",
      security: self.security,
      id: id,
    };

    callAjaxLoading(
      data,
      (res) => {
        if (res.success) {
          window.location.reload();
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };

  self.refresh = () => {
    let page = $(".pagination-detail", self.form).data().page;
    let search = "";
    self.loadListDetailTicket(page, search);
  };
  self.loadListDetailTicket = (page, search = "") => {
    let data = {
      action: self.action,
      security: self.security,
      id: $("#id", self.form).val(),
      page: page,
      search: search,
    };

    callAjaxLoading(
      data,
      (res) => {
        if (res.success) {
          self.contextList.html("");
          res.data.items.map((value, index) => {
            self.contextList.append($(value));
          });
          if (!res.data.continue) {
            $(".pagination-detail .pagination-next", self.form).data(
              "contrinue",
              0
            );
          } else
            $(".pagination-detail .pagination-next", self.form).data(
              "contrinue",
              1
            );

          $(".pagination-detail .count-from", self.form).text(
            res.data.count_from
          );
          $(".pagination-detail .count-to", self.form).text(res.data.count_to);
          $(".pagination-detail .total", self.form).text(res.data.total);
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
    var noteEditor = tinyMCE.get("note");
    window.tinyMCE.triggerSave();
    var form_data = getFormData(
      "ajax_new-detail-ticket",
      self.security,
      self.form
    );
    if (form_data["note"] == "") {
      showErrorMessage(null, "Vui lòng nhập nội dung", "Nhập nội dung");
      return;
    }
    form_data["note"] = noteEditor.getContent();
    callAjaxLoading(
      form_data,
      (res) => {
        if (res.success) {
          showSuccessMessage(
            () => {
              noteEditor.setContent("");
              self.loadListDetailTicket(1, "");
            },
            res.data.msg,
            "Gửi phản hồi"
          );
          $(".has-detail", self.form).removeClass("d-none");
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
    self.form.parsley();
    self.security = $("#nonce", self.form).val();
    imagesContainer.lightGallery({
      thumbnail: true,
      selector: ".item a.image",
    });

    self.event();
  };

  return self;
})({});
