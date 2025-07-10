$(function () {
  "use strict";

  $(document).ready(function () {
    detailSiteType.initialize();
  });
});

var detailSiteType = (function (self, base) {
  base.form = $("form.form-detail-site-type-small");
  base.action = "ajax_update-site-type";
  base.actionDelete = "ajax_delete-site-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      base.initialize();
    }
  };

  return self;
})({}, baseDetailPostType({}));
