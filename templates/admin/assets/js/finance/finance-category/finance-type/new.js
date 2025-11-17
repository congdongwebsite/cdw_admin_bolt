$(function () {
  "use strict";

  $(document).ready(function () {
    newFinanceType.initialize();
  });
});

var newFinanceType = (function (self, base) {
  base.form = $("form.form-new-finance-type-small");
  base.action = "ajax_new-finance-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      base.initialize();
    }
  };

  return self;
})({}, baseNewPostType({}));
