$(function () {
  "use strict";
  $(document).ready(function () {
    report.initialize();
  });
});
var report = (function (self, base) {
  base.action = "ajax_get-finance-report-index";
  base.context = $(".report-index");
  base.tableID = "#tb-data";

  base.column = [
    { data: "code", title: "Mã" },
    { data: "date", title: "Ngày" },
    { data: "note", title: "Nội dung" },
    { data: "receipt", title: "Thu" },
    { data: "payment", title: "Chi" },
    { data: "total", title: "Tổng" },
  ];
  base.columnDefs = [
    {
      targets: 0,
      render: drillDownFormatter,
    },
    {
      targets: 1,
      render: dateFormatter,
    },
    {
      targets: [3, 4, 5],
      render: numberFormatterAmountVND,
    },
  ];
  let i = 1;
  base.addEvent = () => {
    $(".btn-reload").on("click", (e) => {
      e.preventDefault();

      data = {
        from_date: $("#from-date", base.context).val(),
        until_date: $("#until-date", base.context).val(),
        type: $("#type", base.context).val(),
      };
      base.ajaxData = {
        ...base.ajaxData,
        ...data,
      };
      base.loadData();
      base.loadWidget();
      base.loadWidgetType();
    });
  };
  base.loadWidget = () => {
    data = {
      from_date: $("#from-date", base.context).val(),
      until_date: $("#until-date", base.context).val(),
      type: $("#type", base.context).val(),
    };
    callAjaxLoading(
      {
        ...base.ajaxData,
        ...data,
        action: "ajax_get-load-widget-data",
      },
      (res) => {
        if (res.success) {
          $(".widgets .dk .number").text(res.data.dk);
          $(".widgets .dk-dt .number").text(res.data.dk_dt);
          $(".widgets .ps-dt .number").text(res.data.ps_dt);
          $(".widgets .ps-thu .number").text(res.data.ps_thu);
          $(".widgets .ps-chi .number").text(res.data.ps_chi);
          $(".widgets .ck .number").text(res.data.ck);
        } else {
          showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
        }
      },
      (msg) => {
        showErrorMessage(msg);
      }
    );
  };
  base.loadWidgetType = () => {
    data = {
      from_date: $("#from-date", base.context).val(),
      until_date: $("#until-date", base.context).val(),
      action: "ajax_get-load-widget-type-data",
    };
    $(".item-type", ".widget-data-type").map((index, value) => {
      callAjax(
        {
          ...base.ajaxData,
          ...data,
          type: $(value).data().id,
        },
        (res) => {
          if (res.success) {
            $(".number", $(value)).text(res.data.ck);
            console.log(res.data);
          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });
  };
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("report");
    initSelect2FinanceType("type", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    base.loadWidget();
    base.loadWidgetType();
  };

  return self;
})({}, baseReport({}));
