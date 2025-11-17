<?php
session_start();

require_once('database/ChatUser.php');
session_unset();
session_destroy();
$user_object = new ChatUser;
if (isset($_GET['logout_id'])) {
  
  session_unset();
  session_destroy();
  $logout_id = $_GET['logout_id'];
  $user_object->setUserToken($logout_id);
  $user_object->get_user_id_from_token();
  $user_object->setUserLoginStatus('LogOut');
  if ($user_object->logout()) {
    header("location: /");
  }
  return;
}
//session_destroy();
if (!is_user_logged_in()) {
  header("location: /");
}

if (!isset($_SESSION['token'])) {
  global $current_user;
  $user_object->setUserId($current_user->data->ID);
  $user_token = md5(uniqid());
  $user_object->setUserToken($user_token);
  $user_object->setUserLoginStatus('Login');
  if ($user_object->update_user_login_data()) {
    $_SESSION['token'] = $user_token;
  } else {
    header("location: /");
  }
} else {
  $user_object->setUserToken($_SESSION['token']);
  $user_object->get_user_id_from_token();
}
$user_data = $user_object->get_user_data_by_id();
if(!$user_data)
{  
  header("location: /");
}
include_once "header.php";
if (!isset($_GET['user_id'])) {
?>

  <body>
    <div class="wrapper">
      <section class="users">
        <header>
          <div class="content">
            <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
            <img src="<?php echo $user_data['user_profile']; ?>" alt="<?php echo $user_data['user_name']; ?>">
            <div class="details">
              <span><?php echo $user_data['user_name']; ?></span>
              <p><?php echo $user_object->getUserLoginStatusLabel(); ?></p>
            </div>
          </div>
          <a href="?logout_id=<?php echo $_SESSION['token']; ?>" class="logout">Logout</a>
        </header>
        <div class="search">
          <span class="text">Select an user to start chat</span>
          <input type="text" autocomplete="off" placeholder="Enter name to search...">
          <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">

        </div>
      </section>
    </div>

    <script type='text/javascript' src='https://www.congdongweb.com/wp-includes/js/jquery/jquery.min.js?ver=3.6.0' id='jquery-core-js'></script>
    <script src="<?php echo get_template_directory_uri(); ?>/templates/libs/chat-app/javascript/users.js"></script>
    <script type='text/javascript' id='main-script-js-extra'>
      /* <![CDATA[ */
      var congdongtheme_objects = {
        "ajaxurl": "https:\/\/www.congdongweb.com\/wp-admin\/admin-ajax.php",
      };
      /* ]]> */
    </script>
  </body>
<?php
} else {
  $userid_to = $_GET['user_id'];

  $user_to_object = new ChatUser;
  $user_to_object->setUserId($userid_to);
  $user_to_data = $user_to_object->get_user_data_by_id();
?>

  <body>
    <div class="wrapper">
      <section class="chat-area">
        <header>
          <a href="/chat" class="back-icon"><i class="fas fa-arrow-left"></i></a>
          <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
          <img src="<?php echo $user_to_data['user_profile']; ?>" alt="">
          <div class="details">
            <span><?php echo $user_to_data['user_name']; ?></span>
            <p><?php echo $user_to_object->getUserLoginStatusLabel(); ?></p>
          </div>
        </header>
        <div class="chat-box">

        </div>
        <form action="#" class="typing-area">
          <input type="text" class="incoming_id" id="userid_to" name="userid_to" value="<?php echo $userid_to; ?>" hidden>
          <input type="text" class="input-field" id="message" name="message" placeholder="Type a message here..." autocomplete="off">
          <button><i class="fab fa-telegram-plane"></i></button>
        </form>
      </section>
    </div>

    <script type='text/javascript' src='https://www.congdongweb.com/wp-includes/js/jquery/jquery.min.js?ver=3.6.0' id='jquery-core-js'></script>
    <script src="<?php echo get_template_directory_uri(); ?>/templates/libs/chat-app/javascript/chat.js"></script>
    <script type='text/javascript' id='main-script-js-extra'>
      /* <![CDATA[ */
      var congdongtheme_objects = {
        "ajaxurl": "https:\/\/www.congdongweb.com\/wp-admin\/admin-ajax.php",
      };
      /* ]]> */
    </script>
  </body>
<?php
} ?>

</html>