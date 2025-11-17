jQuery(document).ready(function ($) {
  const searchBar = document.querySelector(".search input"),
    searchIcon = document.querySelector(".search button"),
    usersList = document.querySelector(".users-list");

  searchIcon.onclick = () => {
    searchBar.classList.toggle("show");
    searchIcon.classList.toggle("active");
    searchBar.focus();
    if (searchBar.classList.contains("active")) {
      searchBar.value = "";
      searchBar.classList.remove("active");
    }
  };

  searchBar.onkeyup = () => {
    let searchTerm = searchBar.value;
    if (searchTerm != "") {
      searchBar.classList.add("active");
    } else {
      searchBar.classList.remove("active");
    }
    console.log(searchTerm);
    ajax_search_list_user_by_session(usersList, searchTerm);
  };

  setInterval(() => {
    ajax_get_list_user_by_session(usersList);
  }, 500);

  function ajax_get_list_user_by_session(usersList) {
    $.ajax({
      type: "post",
      dataType: "json",
      url: congdongtheme_objects.ajaxurl,
      data: {
        action: "get_list_user_by_session",
        token: $('#token').val()
      },
      context: this,
      beforeSend: function () {},
      success: function (response) {
        if (!searchBar.classList.contains("active")) {
          usersList.innerHTML = response.data;
        }
      },
    });
  }
  function ajax_search_list_user_by_session(usersList, searchTerm) {
    $.ajax({
      type: "post",
      dataType: "json",
      url: congdongtheme_objects.ajaxurl,
      data: {
        action: "search_list_user_by_session",
        searchTerm: searchTerm,
        token: $('#token').val()
      },
      context: this,
      beforeSend: function () {},
      success: function (response) {
        usersList.innerHTML = response.data;
      },
    });
  }
});
