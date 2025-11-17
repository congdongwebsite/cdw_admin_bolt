$(function () {
  "use strict";

  $(document).ready(function () {
    newSiteType.initialize();
  });
});

var newSiteType = (function (self, base) {
  base.form = $("form.form-new-site-type-small");
  base.action = "ajax_new-site-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      base.initialize();
    }
  };

  return self;
})({}, baseNewPostType({}));
