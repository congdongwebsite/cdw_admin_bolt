$(function () {
  "use strict";

  $(document).ready(function () {
    detailFinanceType.initialize();
  });
});

var detailFinanceType = (function (self, base) {
  base.form = $("form.form-detail-finance-type-small");
  base.action = "ajax_update-finance-type";
  base.actionDelete = "ajax_delete-finance-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      base.initialize();
    }
  };

  return self;
})({}, baseDetailPostType({}));
