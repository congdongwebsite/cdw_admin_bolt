var gRecaptcha = (function (self) {
  self.addEvent = () => {};
  self.getRecaptcha = (id) => {
    let el = $("#g-" + id);
    let g_recaptcha_id = el.data("g-recaptcha-id");
    let value = grecaptcha.getResponse(g_recaptcha_id);
    self.resetRecaptcha(id);
    return {
      key: el.data().name,
      value: value,
    };
  };
  self.resetRecaptcha = (id) => {
    let el = $("#g-" + id);
    let g_recaptcha_id = el.data("g-recaptcha-id");
    grecaptcha.reset(g_recaptcha_id);
  };
  self.initialize = () => {
    self.addEvent();
  };
  return self;
})({});

var CaptchaCallback = function () {
  $(".g-recaptcha").each(function (index, el) {
    var id = grecaptcha.render(el, {
      sitekey: objAdmin.sitekey,
    });
    $(this).data("g-recaptcha-id", id);
  });
};
$(function () {
  "use strict";

  //  Use by Device
  $(document).ready(function () {
    gRecaptcha.initialize();
  });
});
