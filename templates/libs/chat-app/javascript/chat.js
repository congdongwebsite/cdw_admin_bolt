jQuery(document).ready(function ($) {
  const form = document.querySelector(".typing-area"),
    inputField = form.querySelector(".input-field"),
    sendBtn = form.querySelector("button"),
    chatBox = document.querySelector(".chat-box");

  form.onsubmit = (e) => {
    e.preventDefault();
  };

  inputField.focus();
  inputField.onkeyup = () => {
    if (inputField.value != "") {
      sendBtn.classList.add("active");
    } else {
      sendBtn.classList.remove("active");
    }
  };

  sendBtn.onclick = () => {
    ajax_insert_list_chat();
  };
  chatBox.onmouseenter = () => {
    chatBox.classList.add("active");
  };

  chatBox.onmouseleave = () => {
    chatBox.classList.remove("active");
  };

  setInterval(() => {
    if (!ajax_get_list_chat(chatBox)) clearInterval(this);
  }, 500);

  function ajax_get_list_chat(chatBox) {
    $.ajax({
      type: "post",
      dataType: "json",
      url: congdongtheme_objects.ajaxurl,
      data: {
        action: "get_list_chat_by_session_userid_to",
        token: $("#token").val(),
        userid_to: $("#userid_to").val(),
      },
      context: this,
      beforeSend: function () {},
      success: function (response) {
        chatBox.innerHTML = response.data;
        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
        if (!response.success) {
          return false;
        }
      },
    });
    return true;
  }
  function ajax_insert_list_chat() {
    $.ajax({
      type: "post",
      dataType: "json",
      url: congdongtheme_objects.ajaxurl,
      data: {
        action: "insert_chat_by_session_userid_to",
        token: $("#token").val(),
        userid_to: $("#userid_to").val(),
        message: $("#message").val(),
      },
      context: this,
      beforeSend: function () {},
      success: function (response) {
        if (response.success) {
          inputField.value = "";
          scrollToBottom();
        } else {
          return false;
        }
      },
    });
    return true;
  }
  function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
  }
});
