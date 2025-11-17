jQuery(document).ready(function ($) {
  const WhoisDomain = {
    init() {
      this.processing = false;
      this.form = $("#form-whois-domain");
      this.result = $(".list-domain-whois");
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const product = urlParams.get("ten-mien");
      if (product != "") this.submit();
    },
    submit() {
      let $this = this;
      if (this.processing) return;
      let domain = this.form.find("#ten-mien").val();
      let data = {
        action: "get_list_domain_whois",
        domain: domain,
      };
      $.ajax({
        url: congdongtheme_objects.ajaxurl,
        type: "post",
        dataType: "json",
        data: {
          ...data,
        },
        beforeSend() {
          $this.processing = true;
          $this.result.addClass("congdongtheme-loading");
          $this.result.html("");
          $this.result.append(
            '<h3 class="title-whois">Các Domain Liên Quan</h3>'
          );
        },
        success(res) {
          if (res.success) {
            res.data.items &&
              res.data.items.map((value, index) => {
                $this.result.append(value.element);
                $this.checkAvailability(value.id, value.domain);
              });
          }
          $this.processing = false;
          $this.result.removeClass("congdongtheme-loading");
        },
      });
    },
    checkAvailability(id, domain) {
      let data = {
        action: "check-availability",
        domain: domain,
      };
      this.ajaxCheckAvailability(data, id);
    },
    ajaxCheckAvailability(data, id) {
      let $this = this;
      let element = this.result.find("#" + id);
      $.ajax({
        url: congdongtheme_objects.ajaxurl,
        type: "post",
        dataType: "json",
        data: {
          ...data,
        },
        beforeSend() {
          element.addClass("congdongtheme-loading");
        },
        success(res) {
          if (res.success) {
            if (res.data.item.status) {
              element.remove();
            }
          }
          element.removeClass("congdongtheme-loading");
        },
      });
    },
  };
  WhoisDomain.init();
});
