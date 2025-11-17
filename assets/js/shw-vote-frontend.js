jQuery(document).ready(function ($) {
  "use strict";

  const Shw_votes = {
    init() {
      let seft = this;
      $.getJSON("https://api.ipify.org/?format=json", function (e) {
        let ip_vote = seft.getCookie("shw_vote");
        if (ip_vote && ip_vote == e.ip) {
          $(".container-vote .vote-status .status").text(
            shwObjectvotes.shw_success_vote
          );
        } else $(".container-vote").on("click", "input:radio", (e) => seft.VoteClick(e));
      });
    },
    VoteClick(e) {
      e.preventDefault();
      let seft = this;
      let el = e;
      let isLoad = false;
      $.getJSON("https://api.ipify.org/?format=json", function (e) {
        let ip_vote = seft.getCookie("shw_vote");
        if (ip_vote && ip_vote == e.ip) return;
        $(".shw-upload-images-status").removeClass(
          "shw-upload-images-status-error"
        );

        let elParent = $(el.currentTarget).closest(".container-vote");
        let data = elParent.data("nonce");
        let post_id = elParent.data("post-id");
        let vote = $(el.currentTarget).val();
        let ajaxData = {
          action: "shw_vote_frontend",
          shw_nonce: data,
          shw_postid: post_id,
          shw_vote: vote,
        };
        if(isLoad) return;
        $.ajax({
          type: "POST",
          url: shwObjectvotes.ajaxUrl,
          data: ajaxData,
          beforeSend: function () {
            elParent.addClass("loading");
            $(".vote-status .status", elParent).removeClass("error");
            isLoad = true;
          },
          success: function (response) {
            if (200 === response["code"]) {
              $(".vote-status .status", elParent).text(
                shwObjectvotes.shw_success_vote
              );
              $(".container-vote input:radio").prop("checked", false);
              $("input:radio#vote-" + response["average"]).prop(
                "checked",
                true
              );
              $(".container-vote .vote-count .count").text(+response["count"]);

              // Set a cookie
              seft.setCookie("shw_vote", e.ip, 1);
            } else {
              $(".vote-status .status", elParent).addClass("error");
              $(".vote-status .status", elParent).text(response["message"]);
              seft.eraseCookie("shw_vote");
            }

            elParent.removeClass("loading");
            isLoad = false;
          },
          error: function (jqXHR, exception) {
            var msg = "";
            if (jqXHR.status === 400) {
              msg = "Not connect.\n Verify Network.";
            } else if (jqXHR.status === 0) {
              msg = "Not connect.\n Verify Network.";
            } else if (jqXHR.status == 404) {
              msg = "Requested page not found. [404]";
            } else if (jqXHR.status == 500) {
              msg = "Internal Server Error [500].";
            } else if (exception === "parsererror") {
              msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
              msg = "Time out error.";
            } else if (exception === "abort") {
              msg = "Ajax request aborted.";
            } else {
              msg = "Uncaught Error.\n" + jqXHR.responseText;
            }
            $(".vote-status .status", elParent).addClass("error");
            $(".vote-status .status", elParent).text(
              shwObjectvotes.shw_success_vote_error + "</br>" + msg
            );
            seft.eraseCookie("shw_vote");
            elParent.removeClass("loading");
            isLoad = false;
          },
        });
      });
    },
    setCookie(key, value, expiry) {
      var expires = new Date();
      expires.setTime(expires.getTime() + expiry * 24 * 60 * 60 * 1000);
      document.cookie = key + "=" + value + ";expires=" + expires.toUTCString();
    },
    getCookie(key) {
      var keyValue = document.cookie.match("(^|;) ?" + key + "=([^;]*)(;|$)");
      return keyValue ? keyValue[2] : null;
    },
    eraseCookie(key) {
      var keyValue = this.getCookie(key);
      this.setCookie(key, keyValue, "-1");
    },
  };
  Shw_votes.init();
});
