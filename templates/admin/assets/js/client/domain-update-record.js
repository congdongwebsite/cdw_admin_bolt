$(function () {
  "use strict";
  $(document).ready(function () {
    domainRecord.initialize();
  });
});
var domainRecord = (function (self) {
  self.action = "ajax_update-record-domain-client-cart";
  self.actionUpdatePerRecord = "ajax_update-per-record-domain-client-cart";
  self.actionDefault = "ajax_update-record-domain-default-client-cart";
  self.actionLoadRecord = "ajax_load-record-domain-client-cart";
  self.context = $(".client-domain-update-record");
  self.form = $("form", self.context);
  self.currentRequest;
  self.id;

  self.updateRecords = (e) => {
    e.preventDefault();
    if (!self.form.parsley().validate()) return;

    Swal.fire({
      title: "Cập nhật Record",
      text: "Record sẽ được cập nhật, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let data = [];
        $("table.record tbody tr", self.context).map((index, value) => {
          let item = {
            id: $(value).data().id,
            name: $("#name", value).val(),
            type: $("#type", value).val(),
            ttl: $("#ttl", value).val(),
            value: $("#value", value).val(),
          };
          data = [...data, item];
        });
        callAjaxLoading(
          {
            data: data,
            id: self.id,
            action: self.action,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title ? res.data.title : "Cập nhật Record",
                text: res.data.msg,
                icon: "question",
                confirmButtonText: "Có",
              }).then((res2) => {});
              self.loadRecord();
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
  };

  self.updateRecord = (e) => {
    e.preventDefault();
    if (!self.form.parsley().validate()) return;

    Swal.fire({
      title: "Cập nhật Record",
      text: "Record sẽ được cập nhật, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        let record = {
          id: $(e.currentTarget).closest("tr").data().id,
          name: $("#name", $(e.currentTarget).closest("tr")).val(),
          type: $("#type", $(e.currentTarget).closest("tr")).val(),
          ttl: $("#ttl", $(e.currentTarget).closest("tr")).val(),
          value: $("#value", $(e.currentTarget).closest("tr")).val(),
        };

        callAjaxLoading(
          {
            record: record,
            id: self.id,
            action: self.actionUpdatePerRecord,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title ? res.data.title : "Cập nhật Record",
                text: res.data.msg,
                icon: "question",
                confirmButtonText: "Có",
              }).then((res2) => {});
              self.loadRecord();
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
  };

  self.updateDefault = (e) => {
    e.preventDefault();

    Swal.fire({
      title: "Cập nhật Record tự động",
      text: "Record sẽ được cập nhật tự động, bạn có muốn thực hiện?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            id: self.id,
            cd_id: $("#cd-id", self.context).val(),
            action: self.actionDefault,
            security: $("#nonce").val(),
          },
          (res) => {
            if (res.success) {
              Swal.fire({
                title: res.data.title ? res.data.title : "Cập nhật Record",
                text: res.data.msg,
                icon: "question",
                confirmButtonText: "Có",
              }).then((res2) => {});
              self.loadRecord();
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
  };
  self.loadRecord = async () => {
    let data = {
      action: self.actionLoadRecord,
      id: $("#cd-id", self.context).val(),
      security: $("#nonce").val(),
    };
    await $.ajax({
      type: "POST",
      dataType: "json",
      url: objAdmin.ajax_url,
      data: data,
      beforeSend: function () {
        $("table.record", self.context).addClass("admin-loading");
      },
      success: function (res) {
        if (res.success) {
          $("table.record tbody", self.context).html("");

          self.template = res.data.template["item-list"];
          self.id = res.data.id;
          $.each(res.data.items, function (key, value) {
            var template = window.wp.template(self.template);
            template = $(template(value));
            $("table.record tbody", self.context).append(template);
          });

          if ($("table.record tbody tr", self.context).length == 0)
            self.addRecord();
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }

        $("table.record", self.context).removeClass("admin-loading");
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

        $("table.record", self.context).removeClass("admin-loading");
      },
    });
  };

  self.addRecord = () => {
    var template = window.wp.template(self.template);
    $("table.record tbody", self.context).append(template);
  };

  self.deleteItem = (e) => {
    $(e.currentTarget).closest("tr").remove();
    if ($("table.record tbody tr", self.context).length == 0) self.addRecord();
  };
  self.deleteAll = (e) => {
    $("table.record tbody tr", self.context).map((index, value) => {
      if ($(".checkbox-tick", $(value)).prop("checked")) $(value).remove();
    });
    $(".select-all").prop("checked", false);
    if ($("table.record tbody tr", self.context).length == 0) self.addRecord();
  };

  self.addEvent = () => {
    $(".btn-save-all", self.context).on("click", function (e) {
      e.preventDefault();
      self.updateRecords(e);
    });
    $(".btn-delete-all", self.context).on("click", function (e) {
      e.preventDefault();
      self.deleteAll();
    });
    $(".btn-add", self.context).on("click", function (e) {
      e.preventDefault();
      self.addRecord(e);
    });

    $(".btn-default", self.context).on("click", function (e) {
      e.preventDefault();
      self.updateDefault(e);
    });

    $("table.record tbody", self.context).on(
      "click",
      ".btn-delete",
      function (e) {
        e.preventDefault();
        self.deleteItem(e);
      }
    );
    $("table.record tbody", self.context).on(
      "click",
      ".btn-save",
      function (e) {
        e.preventDefault();
        self.updateRecord(e);
      }
    );
  };

  self.initialize = async () => {
    self.addEvent();
    await self.loadRecord();
  };

  return self;
})({});
