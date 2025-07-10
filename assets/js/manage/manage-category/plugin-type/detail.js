$(function () {
  "use strict";

  $(document).ready(function () {
    detailPluginType.initialize();
  });
});

var detailPluginType = (function (self, base) {
  base.form = $("form.form-detail-plugin-type-small");
  base.action = "ajax_update-plugin-type";
  base.actionDelete = "ajax_delete-plugin-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "detail") {
      base.initialize();
    }
  };

  return self;
})({}, baseDetailPostType({}));
