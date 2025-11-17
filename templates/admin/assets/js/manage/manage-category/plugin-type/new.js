$(function () {
  "use strict";

  $(document).ready(function () {
    newPluginType.initialize();
  });
});

var newPluginType = (function (self, base) {
  base.form = $("form.form-new-plugin-type-small");
  base.action = "ajax_new-plugin-type";

  self.initialize = () => {
    if (urlParams.has("action") && urlParams.get("action") == "new") {
      base.initialize();
    }
  };

  return self;
})({}, baseNewPostType({}));
