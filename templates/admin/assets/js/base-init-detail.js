var initDetail = function (self) {
  self.tb;
  self.api;
  self.modalName;
  self.formName;
  self.column;
  self.columnDefs;
  self.buttons;
  self.action;
  self.id;

  self.createModel = (data) => {
    return {};
  };
  self.buttons = [
    {
      text: "Thêm mới",
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.new(e);
      },
    },
  ];
  self.bindingModel = (model) => { };

  self.getData = () => {
    let items = [];

    return items;
  };

  self.addModalEvents = () => {
    $(".btn-close", self.modalName).on("click", () => {
      self.close();
    });
    $(".btn-add", self.modalName).on("click", self.save);
    $(".btn-add-close", self.modalName).on("click", self.saveClose);

    self.modal.on("show.bs.modal", (e) => {
      let rowData = self.modal.data("rowData");

      if (typeof rowData === "undefined") {
        $("h5.modal-title", self.modalName).text("Thêm dòng mới");
        $("button.btn-add", self.modalName).show();
        $("button.btn-add-close", self.modalName).text("Thêm và đóng");
      } else {
        $("h5.modal-title", self.modalName).text("Sửa dữ liệu");
        $("button.btn-add", self.modalName).hide();
        $("button.btn-add-close", self.modalName).text("Lưu");
      }
    });

    self.modal.on("shown.bs.modal", (e) => {
      let rowData = self.modal.data("rowData");

      if (rowData !== undefined) {
        self.bindingModel(rowData);
      }
    });

    self.modal.on("hidden.bs.modal", (e) => {
      self.modal.removeData("rowIndex");
      self.modal.removeData("rowData");

      clearInputData(self.modalName);
      self.modalHide(e);
    });
  };

  self.modalHide = (e) => { };
  self.saveClose = () => {
    self.save(true);
  };
  self.save = (closeAfterAdd) => {
    if (!self.form.parsley().validate()) return;

    let index = self.modal.data("rowIndex");
    let rowData = self.modal.data("rowData");

    let data = self.createModel(rowData);
    data.change = true;

    if (index === undefined) {
      self.api.row.add(data).columns.adjust().draw();
    } else {
      self.api.row(index).data(data).columns.adjust().draw();
    }

    if (closeAfterAdd === true) {
      self.close();
    } else {
      self.modal.removeData("rowIndex");
    }
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().draw();
  };

  self.initialize = () => {
    self.modal = $(self.modalName);
    self.form = $(self.formName);
    self.column = [
      { data: null, title: "STT" },
      ...self.column,
      { data: null, title: "" },
    ];
    self.columnDefs.map((value, index) => {
      if (!Array.isArray(value.targets)) value.targets++;
      else
        value.targets.map((value2, index2) => {
          value.targets[index2] = ++value2;
        });
    });
    self.columnDefs = [
      {
        targets: 0,
        width: 10,
      },
      ...self.columnDefs,
      {
        targets: -1,
        width: 20,
        render: actionDetailFormatter,
        responsivePriority: 1,
      },
    ];

    self.form.parsley();
    let arr = {
      scrollX: true,
      scrollCollapse: true,
      ordering: false,
      paging: false,
      columns: self.column,
      columnDefs: self.columnDefs,
      fixedColumns: {
        right: 1,
      },
      buttons: self.buttons,
      dom:
        "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
    };
    if (self.id)
      arr = {
        ...arr,
        ajax: {
          url: objAdmin.ajax_url,
          type: "POST",
          data: {
            action: self.action,
            security: self.security,
            id: self.id,
          },
        },
      };
    self.api = self.tb.dataTable(arr).api();

    self.tb.on("dblclick", "tr", (e) => {
      self.edit(e);
    });

    self.tb.on("click", "td .edit", (e) => {
      self.edit(e);
    });

    self.tb.on("click", "td .delete", (e) => {
      self.delete(e);
    });
    self.tb.on("draw.dt", (e) => {
      let pageInfo = self.api.page.info();

      self.api
        .column(0, { page: "current" })
        .nodes()
        .each((cell, i) => {
          cell.innerHTML = i + 1 + pageInfo.start;
        });
    });

    self.addModalEvents();
  };

  self.new = (e) => {
    self.modal.modal("show");
  };
  self.edit = (e) => {
    let row = self.api.row($(e.target).parents("tr"));

    if (row.data()) {
      self.modal.data("rowData", row.data());
      self.modal.data("rowIndex", row.index());
      self.modal.modal("show");
    }
  };

  self.delete = (e) => {
    Swal.fire({
      title: "Xoá dòng?",
      text: "Bạn có chắc chắn muốn xoá dòng này không?",
      icon: "question",
      showCancelButton: true,
      focusCancel: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        self.api.row($(e.target).parents("tr")).remove().draw();
      }
    });
  };
  self.url = (url) => {
    self.api.ajax.reload(() => {
      self.api.columns.adjust().draw();
    });
  };
  self.close = () => {
    self.modal.modal("hide");
  };
  return self;
};
