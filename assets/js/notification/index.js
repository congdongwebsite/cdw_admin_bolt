$(function () {
  "use strict";
  $(document).ready(function () {
    notification.initialize();
  });
  var notification = (function (self) {
    self.context = $(".notification");
    self.contextList = $(".notification-list", self.context);
    self.contextAction = $(".notification-action", self.context);
    self.contextPagination = $(".pagination", self.context);
    self.action = "ajax_get-list-notification";
    self.security;

    self.event = () => {
      $(".notification-type li a", self.context).on("click", function (e) {
        e.preventDefault();
        $(".page-item", self.contextPagination).removeClass("active");
        $(".page-item[data-page='1']", self.contextPagination).addClass(
          "active"
        );
        $(".notification-search", self.contextAction).val("");
        let type = $(e.currentTarget).data().type;
        $(self.contextPagination).data("page", 1);
        self.loadListNotification(1, "", type);
      });

      $(self.contextPagination).on("click", ".pagination-back", function (e) {
        e.preventDefault();

        let page = $(self.contextPagination).data().page;
        if (page == 1) return;
        $(self.contextPagination).data("page", --page);
        let search = $(".notification-search", self.contextAction).val();
        let type = $(".notification-type .nav-item a.active").data().type;
        self.loadListNotification(page, search, type);
      });
      $(self.contextPagination).on("click", ".pagination-next", function (e) {
        e.preventDefault();
        let contrinue = $(e.currentTarget).data().contrinue;
        if (contrinue != 1) return;
        let page = $(self.contextPagination).data().page;
        $(self.contextPagination).data("page", ++page);
        let search = $(".notification-search", self.contextAction).val();
        let type = $(".notification-type .nav-item a.active").data().type;
        self.loadListNotification(page, search, type);
      });

      $(self.contextPagination).on("click", ".page-item .number", function (e) {
        e.preventDefault();
        if (!$(e.currentTarget).hasClass("active")) {
          let page = $(e.currentTarget).closest(".page-item").data().page;
          $(self.contextPagination).data("page", page);
          let search = $(".notification-search", self.contextAction).val();
          let type = $(".notification-type .nav-item a.active").data().type;
          self.loadListNotification(page, search, type);
        }
      });

      $(self.contextList).on("click", ".notification-item-read", (e) => {
        self.notificationItemRead(e);
      });
      $(self.contextList).on("click", ".notification-item-delete", (e) => {
        e.preventDefault();

        Swal.fire({
          title: "Bạn có muốn xóa thông báo?",
          text: "Dữ liệu sẽ bị xoá, bạn có chắc chắn muốn xoá không?",
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Có",
          cancelButtonText: "Không",
        }).then((res) => {
          if (res.value) {
            self.notificationItemDelete(e);
          }
        });
      });
      $(".btn-notification-search", self.contextAction).on(
        "click",
        function (e) {
          e.preventDefault();
          let search = $(".notification-search", self.contextAction).val();
          if (search != "") {
            $(".page-item", self.contextPagination).removeClass("active");
            $(".page-item[data-page='1']", self.contextPagination).addClass(
              "active"
            );
            let type = $(".filter-type", self.contextAction).val();
            $(self.contextPagination).data("page", 1);
            self.loadListNotification(1, search, type);
          }
        }
      );
      var delay = 500; // thời gian chờ giữa các request
      var searchRequest;

      $(".notification-search", self.contextAction).on(
        "keyup",
        _.debounce(function () {
          $(".notification-item-read", self.contextList).off("click");
          $(".notification-item-delete", self.contextList).off("click");
          var keyword = $(this).val();
          if (searchRequest) {
            searchRequest.abort(); // hủy request trước đó nếu có
          }
          $(self.contextPagination).data("page", 1);
          let type = $(".notification-type .nav-item a.active").data().type;
          let data = {
            action: self.action,
            security: self.security,
            page: 1,
            search: keyword,
            type: type,
          };
          searchRequest = $.ajax({
            type: "POST",
            dataType: "json",
            url: objAdmin.ajax_url,
            data: data,
            beforeSend: function () {},
            success: function (res) {
              if (res.success) {
                self.refreshContent(res.data);
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
            },
          });
        }, delay)
      );
    };

    self.notificationItemRead = (e) => {
      e.preventDefault();
      let id_notification = $(e.currentTarget)
        .closest(".notification-item")
        .data().idNotification;

      let data = {
        action: "ajax_update-read-notification",
        security: self.security,
        id: id_notification,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            if (res.data.status) {
              $(e.currentTarget)
                .closest(".notification-item")
                .addClass("unread");
              $(e.currentTarget).addClass("text-warning");
              $(e.currentTarget).text("Đánh dấu chưa đọc");
            } else {
              $(e.currentTarget)
                .closest(".notification-item")
                .removeClass("unread");
              $(e.currentTarget).removeClass("text-warning");
              $(e.currentTarget).text("Đánh dấu đã đọc");
            }
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.notificationItemDelete = (e) => {
      e.preventDefault();
      let id_notification = $(e.currentTarget)
        .closest(".notification-item")
        .data().idNotification;

      let data = {
        action: "ajax_delete-notification",
        security: self.security,
        id: id_notification,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };

    self.refreshContent = (data) => {
      self.contextList.html("");
      data.items.map((value, index) => {
        var template = window.wp.template(data.template.item);
        template = template(value);
        self.contextList.append(template);
      });

      $(self.contextPagination).html("");
      data.paginations.map((value, index) => {
        var template = window.wp.template(data.template.pagination);
        template = template(value);
        self.contextPagination.append(template);
      });
    };
    self.refresh = () => {
      let page = $(self.contextPagination).data().page;
      let search = $(".notification-search", self.contextAction).val();
      let type = $(".notification-type .nav-item a.active").data().type;
      self.loadListNotification(page, search, type);
    };
    self.loadListNotification = (page, search = "", type = "") => {
      let data = {
        action: self.action,
        security: self.security,
        page: page,
        search: search,
        type: type,
      };

      callAjax(
        data,
        (res) => {
          if (res.success) {
            self.refreshContent(res.data);
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        },
        self.contextList
      );
    };
    self.initialize = () => {
      self.security = $("#nonce", self.contextAction).val();
      self.event();
      self.refresh();
    };

    return self;
  })({});
});
