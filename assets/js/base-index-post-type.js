var baseIndexPostType = (self) => {
  self.dom =
    "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6'B><'col-sm-12 col-md-3'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

  self.buttons = [
    {
      text: '<i class="fa fa-trash-o" aria-hidden="true"></i>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.delete(e);
      },
    },
    {
      text: '<i class="fa fa-refresh" aria-hidden="true"></i>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.loadData();
      },
    },
    {
      extend: "excelHtml5",
      text: '<i class="fa fa-file-excel-o"></i>',
      titleAttr: "Export to Excel",
      className: "",
    },
    {
      extend: "print",
      text: '<i class="fa fa-print"></i>',
      titleAttr: "Print table",
      className: "",
    },
  ];

  self.action;
  self.tableID;
  self.table;
  self.security = $("#nonce").val();

  self.api;
  self.column;
  self.columnDefs;
  self.order;
  self.actionDelete;

  self.checkToggle = (e) => {
    if ($(e).prop("checked")) self.api.rows().select();
    else self.api.rows().deselect();
  };
  self.addEvent = () => {
    $(".btn-reload").on("click", (e) => {
      e.preventDefault();
      self.loadData();
    });
  };

  self.loadData = () => {
    self.api.ajax.reload();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  };
  self.getSelectedRows = () => {
    return self.api.rows({ selected: true }).data();
  };
  self.delete = (e) => {
    let data = self.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Bạn chưa chọn dòng nào!",
        text: "Vui lòng chọn danh sách!",
        icon: "warning",
      });
      return;
    }
    Swal.fire({
      title: "Xóa danh sách đã chọn?",
      text: "Dữ liệu sẽ bị xoá, bạn có chắc chắn muốn xoá không?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        ids = [];

        data.map((row, index) => {
          ids.push(row.id);
        });

        callAjaxLoading(
          {
            ids: ids,
            action: self.actionDelete,
            security: self.security,
          },
          (res) => {
            if (res.success) {
              showSuccessMessage(
                () => {
                  self.loadData();
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
      }
    });
  };
  self.initialize = (name) => {
    if (urlParams.has("action") && urlParams.get("action") == "index") {
      self.table = $(self.tableID);
      self.column = [
        {
          data: null,
          title:
            '<input type="checkbox" onchange="' +
            name +
            '.checkToggle(this);" class="check-all"></input>',
        },
        { data: null, title: "STT" },
        ...self.column,
      ];
      self.columnDefs.map((value, index) => {});
      self.columnDefs.map((value, index) => {
        if (!Array.isArray(value.targets)) {
          value.targets++;
          value.targets++;
        } else
          value.targets.map((value2, index2) => {
            ++value2;
            value.targets[index2] = ++value2;
          });
      });
      self.columnDefs = [
        {
          targets: 1,
          width: 10,
        },
        {
          targets: 0,
          orderable: false,
          className: "select-checkbox text-center",
          render: () => "",
          width: 10,
        },
        ...self.columnDefs,
      ];
      self.api = self.table
        .dataTable({
          scrollX: true,
          scrollCollapse: true,
          stateSave: true,
          stateDuration: -1,
          responsive: true,
          paging: true,
          serverSide: true,
          processing: true,
          ordering: false,
          select: {
            items: "row",
            info: false,
          },
          ajax: {
            url: objAdmin.ajax_url,
            type: "POST",
            data: {
              action: self.action,
              security: self.security,
            },
          },
          columns: self.column,
          columnDefs: self.columnDefs,
          order: self.order,
          dom: self.dom,
          buttons: self.buttons,
        })
        .api();

      self.table.on("draw.dt", (e) => {
        let pageInfo = self.api.page.info();

        self.api
          .column(1, { page: "current" })
          .nodes()
          .each((cell, i) => {
            cell.innerHTML = i + 1 + pageInfo.start;
          });
      });
      self.addEvent();
    }
  };
  return self;
};
