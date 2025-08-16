function initSelect2(id, context, action, parent) {
  let select = $("#" + id, context);
  let container = $(
    'span.select2-selection[aria-controls="select2-' + id + '-container"]',
    context
  );
  let val = select.data()?.value;
  $.ajax({
    placeholder: "Vui lòng chọn",

    url: typeof objAdmin != "undefined" ? objAdmin?.ajax_url : cdwObjects?.ajax_url,
    beforeSend: function () {
      container.addClass("admin-loading");
    },
    data: {
      action: action,
      parent: parent,
    },
    dataType: "json",
  }).done((res) => {
    select.find("option").remove();
    res.data.map((value, index) => {
      var option = new Option(value.text, value.id, false, false);
      select.append(option);
    });
    select.val(val?.toString().split(",")).trigger("change");
    container.removeClass("admin-loading");
  });
}
function initSelect2Searching(id, context, action, parent) {
  let select = $("#" + id, context);

  let val = select.data()?.value;
  select.select2({
    placeholder: "Vui lòng chọn",
    closeOnSelect: true,
    allowClear: true,
    debug: false,
    ajax: {

      url: typeof objAdmin != "undefined" ? objAdmin?.ajax_url : cdwObjects?.ajax_url,
      dataType: "json",
      data: function (params) {
        var query = {
          action: action,
          search: params.term,
          parent: parent,
        };
        return query;
      },
      processResults: function (response) {
        return {
          results: response.data,
        };
      },
    },
  });

  $.ajax({

    url: typeof objAdmin != "undefined" ? objAdmin?.ajax_url : cdwObjects?.ajax_url,
    data: {
      action: action,
      parent: parent,
    },
    dataType: "json",
  }).done((res) => {
    select.find("option").remove();
    res.data.map((value, index) => {
      var option = new Option(value.text, value.id, false, false);
      select.append(option);
    });
    select.val(val?.toString().split(",")).trigger("change");
    //select.val(null).trigger("change");
  });
}

function initSelect2DomainStatus(id, context) {
  initSelect2(id, context, "ajax_domain-status");
}
function initSelect2Domains(id, context) {
  initSelect2Searching(id, context, "ajax_domains");
}

function initSelect2TicketType(id, context) {
  initSelect2(id, context, "ajax_ticket-type");
}

function initSelect2FinanceType(id, context) {
  initSelect2Searching(id, context, "ajax_finance-types");
}
function initSelect2SiteType(id, context) {
  initSelect2Searching(id, context, "ajax_site-types");
}
function initSelect2Sites(id, context) {
  initSelect2Searching(id, context, "ajax_sites");
}
function initSelect2PluginType(id, context) {
  initSelect2Searching(id, context, "ajax_plugin-types");
}
function initSelect2ModuleVersion(id, context) {
  initSelect2Searching(id, context, "ajax_module-versions");
}
function initSelect2Plugins(id, context) {
  initSelect2Searching(id, context, "ajax_plugins");
}

function initSelect2Email(id, context) {
  initSelect2Searching(id, context, "ajax_emails");
}

function initSelect2DVHCTPQHXP(id, context, idqh, idpx) {
  if (idqh) {
    $("#" + id, context).on("change", function (e) {
      $("#" + idpx, context)
        .find("option")
        .remove();
      $("#" + idqh, context)
        .val(null)
        .trigger("change");
      let selectedId = $(this).val();
      if (selectedId != null)
        initSelect2(idqh, context, "ajax_dvhc-qh", selectedId);
    });
    if (idpx) {
      $("#" + idqh, context).on("change", function (e) {
        $("#" + idpx, context)
          .val(null)
          .trigger("change");
        let selectedId = $(this).val();
        if (selectedId != null)
          initSelect2(idpx, context, "ajax_dvhc-px", selectedId);
      });
    }
  }
  initSelect2(id, context, "ajax_dvhc-tp");
}

function initSelect2DVHCTP(id, context) {
  initSelect2Searching(id, context, "ajax_dvhc-tp");
}
function initSelect2DVHCQH(id, context, parent) {
  initSelect2Searching(id, context, "ajax_dvhc-qh", parent);
}
function initSelect2DVHCXP(id, context, parent) {
  initSelect2Searching(id, context, "ajax_dvhc-px", parent);
}

function initSelect2BillingStatus(id, context) {
  initSelect2(id, context, "ajax_billing-status");
}

function initSelect2Hosting(id, context) {
  initSelect2Searching(id, context, "ajax_hosting");
}

function initSelect2HostingFeature(id, context) {
  initSelect2(id, context, "ajax_hosting-feature");
}

function initSelect2Customer(id, context) {
  initSelect2Searching(id, context, "ajax_customer");
}

function initSelect2VersionType(id, context) {
  initSelect2(id, context, "ajax_version-type");
}