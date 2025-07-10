var baseReport = (self) => {
  self.dom =
    "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6'B><'col-sm-12 col-md-3'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

  self.buttons = [
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

  self.context;
  self.action;
  self.tableID;
  self.table;
  self.security = $("#nonce").val();

  self.api;
  self.column;
  self.columnDefs;
  self.order;
  self.actionDelete;
  self.ajaxData = {
    action: self.action,
    security: self.security,
  };
  self.checkToggle = (e) => {
    if ($(e).prop("checked")) self.api.rows().select();
    else self.api.rows().deselect();
  };
  self.loadData = () => {
    self.api.ajax.reload();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
  };
  self.getSelectedRows = () => {
    return self.api.rows({ selected: true }).data();
  };

  self.initialize = (name) => {
    self.ajaxData.action = self.action;
    self.table = $(self.tableID, self.context);
    self.column = [
      { data: null, title: "STT" },
      {
        data: null,
        title:
          '<input type="checkbox" onchange="' +
          name +
          '.checkToggle(this);" class="check-all"></input>',
      },
      ...self.column,
    ];
    self.columnDefs.map((value, index) => {
      if (!Array.isArray(value.targets)) {
        if (value.targets >= 0) {
          value.targets++;
          value.targets++;
        }
      } else
        value.targets.map((value2, index2) => {
          ++value2;
          value.targets[index2] = ++value2;
        });
    });
    self.columnDefs = [
      {
        targets: 0,
        width: 10,
      },
      {
        targets: 1,
        width: 10,
      },
      {
        targets: 1,
        orderable: false,
        className: "select-checkbox text-center",
        render: () => "",
      },
      ...self.columnDefs,
    ];
    self.api = self.table
      .dataTable({
        scrollX: true,
        stateSave: true,
        stateDuration: -1,
        scrollCollapse: true,
        responsive: true,
        paging: true,
        processing: true,
        ordering: false,
        select: {
          items: "row",
          info: false,
        },
        ajax: {
          url: objAdmin.ajax_url,
          type: "POST",
          data: function (d) {
            return $.extend(d, self.ajaxData);
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
        .column(0, { page: "current" })
        .nodes()
        .each((cell, i) => {
          cell.innerHTML = i + 1 + pageInfo.start;
        });
    });
    self.addEvent();
  };
  return self;
};
