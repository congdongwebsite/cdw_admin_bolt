$(function () {
  "use strict";
  $(document).ready(function () {
    ticket.initialize();
  });
  var ticket = (function (self) {
    self.context = $(".ticket");
    self.contextList = $(".ticket .ticket-list ul");
    self.contextAction = $(".ticket .ticket-action");
    self.contextStatus = $(".ticket .ticket-status");
    self.contextTypes = $(".ticket .ticket-types");
    self.contextPagination = $(".ticket .ticket-pagination");
    self.action = "ajax_user-get-list-ticket";
    self.security;
    self.currentStatus = "pending";
    self.currentType = "";
    self.currentPage = "";

    self.event = () => {
      $(".btn-save", self.context).on("click", function (e) {
        e.preventDefault();
        self.save(e);
      });

      $(self.contextList).on("click", ".ticket-important", (e) => {
        self.ticketImportant(e);
      });
      $(".ticket-status", self.context).on("click", "li a", function (e) {
        e.preventDefault();
        self.currentStatus = $(e.currentTarget).closest("li").data().status;
        $(".ticket-header .ticket-search", self.context).val("");

        self.currentType = "";
        self.loadStatus();
        self.loadTypes();
        self.loadListTicket(1, "", self.currentStatus, self.currentType);
      });
      $(".ticket-types", self.context).on("click", "li a", function (e) {
        e.preventDefault();
        if ($(e.currentTarget).closest("li").hasClass("active")) {
          self.currentType = "";
        } else {
          self.currentType = $(e.currentTarget).closest("li").data().type;
        }

        $(".ticket-header .ticket-search", self.context).val("");

        self.loadTypes();
        self.loadListTicket(1, "", self.currentStatus, self.currentType);
      });

      $(".ticket-pagination", self.contextAction).on(
        "click",
        ".pagination-back",
        function (e) {
          e.preventDefault();
          let page = $(e.currentTarget).data().page;
          if (page == 1) return;
          let search = $(".ticket-header .ticket-search", self.context).val();

          self.loadListTicket(
            page,
            search,
            self.currentStatus,
            self.currentType
          );
        }
      );
      $(".ticket-pagination", self.contextAction).on(
        "click",
        ".pagination-next",
        function (e) {
          e.preventDefault();

          self.currentPage = $(e.currentTarget).data().page;
          let search = $(".ticket-header .ticket-search", self.context).val();

          self.loadListTicket(
            page,
            search,
            self.currentStatus,
            self.currentType
          );
        }
      );
      $(".ticket-refresh", self.contextAction).on("click", function (e) {
        e.preventDefault();
        self.refresh();
        self.loadStatus();
        self.loadTypes();
      });

      $(".btn-ticket-search", ".ticket-header").on("click", function (e) {
        e.preventDefault();
        let search = $(".ticket-header .ticket-search", self.context).val();
        if (search != "") {
          self.loadListTicket(1, search, self.currentStatus, self.currentType);
        }
      });
      var delay = 500; // thời gian chờ giữa các request
      var searchRequest;

      $(".ticket-header .ticket-search").on(
        "keyup",
        _.debounce(function () {
          var keyword = $(this).val();
          if (searchRequest) {
            searchRequest.abort(); // hủy request trước đó nếu có
          }

          let data = {
            action: self.action,
            security: self.security,
            page: 1,
            search: keyword,
            status: self.currentStatus,
            type: self.currentType,
          };
          searchRequest = $.ajax({
            type: "POST",
            dataType: "json",
            url: objAdmin.ajax_url,
            data: data,
            beforeSend: function () {
              self.contextList.addClass("admin-loading");
            },
            success: function (res) {
              if (res.success) {
                self.refreshContent(res.data);
              } else {
                showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
              }
              self.contextList.removeClass("admin-loading");
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
              self.contextList.removeClass("admin-loading");
            },
          });
        }, delay)
      );

      $(".ticket-important", self.contextList).on("click", (e) => {
        self.ticketImportant(e);
      });
      $(".select-all", self.contextAction).on("change", (e) => {
        self.checkAll(e);
      });
      $(".ticket-unread-list", self.contextAction).on("click", (e) => {
        let ids = self.getTickIDs();
        if (ids.length == 0) return;
        self.ticketUnreadList(ids);
      });
      $(".ticket-read-list", self.contextAction).on("click", (e) => {
        let ids = self.getTickIDs();
        if (ids.length == 0) return;
        self.ticketReadList(ids);
      });
      $(".ticket-pin-important-list", self.contextAction).on("click", (e) => {
        let ids = self.getTickIDs();
        if (ids.length == 0) return;
        self.ticketPinImportantList(ids);
      });
      $(".ticket-pin-unimportant-list", self.contextAction).on("click", (e) => {
        let ids = self.getTickIDs();
        if (ids.length == 0) return;
        self.ticketPinUnimportantList(ids);
      });
    };

    self.ticketUnreadList = (ids) => {
      let data = {
        action: "ajax_user-update-unreads-ticket",
        security: self.security,
        ids: ids,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
            self.loadStatus();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.ticketReadList = (ids) => {
      let data = {
        action: "ajax_user-update-reads-ticket",
        security: self.security,
        ids: ids,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
            self.loadStatus();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.ticketPinImportantList = (ids) => {
      let data = {
        action: "ajax_user-update-importants-ticket",
        security: self.security,
        ids: ids,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
            self.loadStatus();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.ticketPinUnimportantList = (ids) => {
      let data = {
        action: "ajax_user-update-unimportants-ticket",
        security: self.security,
        ids: ids,
      };

      callAjaxLoading(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
            self.loadStatus();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.ticketImportant = (e) => {
      e.preventDefault();
      let id_ticket = $(e.currentTarget)
        .closest(".ticket-item")
        .data().idTicket;

      let data = {
        action: "ajax_user-update-important-ticket",
        security: self.security,
        id: id_ticket,
      };

      callAjax(
        data,
        (res) => {
          if (res.success) {
            self.refresh();
            self.loadStatus();
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };

    self.loadStatus = () => {
      let data = {
        action: "ajax_user-load-status-ticket",
        security: self.security,
        status: self.currentStatus,
      };

      callAjax(
        data,
        (res) => {
          if (res.success) {
            self.contextStatus.html("");
            var template = window.wp.template(res.data.template);
            let item = template(res.data.data);
            self.contextStatus.append(item);
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    };
    self.loadTypes = () => {
      let data = {
        action: "ajax_user-load-type-ticket",
        security: self.security,
        type: self.currentType,
      };

      callAjax(
        data,
        (res) => {
          if (res.success) {
            self.contextTypes.html("");
            res.data.items.map((value, index) => {
              var template = window.wp.template(res.data.template);
              let item = template(value);
              self.contextTypes.append(item);
            });
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
        var template = window.wp.template(value.template);
        let item = $(template(value));

        value.types.map((type, index) => {
          var template = window.wp.template(type.template);
          $(".types", item).html(template(type));
        });

        self.contextList.append(item);
      });

      var template = window.wp.template(data.pagination.template);
      let item = template(data.pagination);
      self.contextPagination.html(item);

      $(".select-all", self.contextAction).prop("checked", false);
    };
    self.refresh = () => {
      let search = $(".ticket-header .ticket-search", self.context).val();
      self.loadListTicket(1, search, self.currentStatus, self.currentType);
    };
    self.loadListTicket = (page, search = "", status = "", type = "") => {
      self.currentPage = page;
      let data = {
        action: self.action,
        security: self.security,
        page: page,
        search: search,
        status: status,
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
    self.checkAll = (e) => {
      e.preventDefault();
      $(".checkbox-tick", self.contextList).prop(
        "checked",
        $(e.currentTarget).prop("checked")
      );
    };
    self.getTickIDs = () => {
      let ids = [];
      $(".checkbox-tick:checked", self.contextList).map((index, value) => {
        ids = [...ids, $(value).closest(".ticket-item").data().idTicket];
      });
      return ids;
    };
    self.initialize = () => {
      self.security = $("#nonce", self.context).val();
      self.event();
      self.loadStatus();
      self.loadTypes();
      self.refresh();
    };

    return self;
  })({});
});
