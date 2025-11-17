<?php

add_action('wp_ajax_get_list_user_by_session', 'get_list_user_by_session');
add_action('wp_ajax_search_list_user_by_session', 'search_list_user_by_session');
add_action('wp_ajax_get_list_chat_by_session_userid_to', 'get_list_chat_by_session_userid_to');
add_action('wp_ajax_insert_chat_by_session_userid_to', 'insert_chat_by_session_userid_to');
function get_list_user_by_session()
{
    ob_start();

    $token = $_POST['token'];
    require_once(dirname(__DIR__) . '/database/ChatUser.php');
    require_once(dirname(__DIR__) . '/database/ChatRooms.php');
    $chat_object = new ChatRooms;
    $user_object = new ChatUser;
    $user_object->setUserToken($token);
    $user_id = $user_object->get_user_id_from_token();
    if ($user_id == "") {
        wp_send_json_error("Tài khoản được đăng nhập bằng một thiết bị khác!");
        return;
    }
    $chat_object->setUserId($user_id);
    $user_data = $user_object->get_user_all_data_by_user();

    $output = "";
    if (!is_array($user_data) || count($user_data) == 0) {
        $output .= "No users are available to chat";
    } else {
        foreach ($user_data as $key => $user) {
            $lastmsg = $chat_object->get_last_msg_chat($user['user_id']);
            $you = "";
            $msg = "No message available";
            $date = "";
            if (is_object($lastmsg)) {
                $result = $lastmsg->msg;
                $date = $lastmsg->created_on;
                (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
                if ($user_id == $lastmsg->userid) $you = "You: ";
            }


            ($user['user_login_status'] != "Login") ? $offline = "offline" : $offline = "";
            $output .= '<a href="?user_id=' . $user["user_id"] . '">
                        <div class="content">
                        <img src="' . $user['user_profile'] . '" alt="">
                        <div class="details">
                            <span>' . $user['user_name'] . '</span>
                            <p>' . $you . $msg . '</p>
                            <span class="time">' . $date . '</span>
                        </div>
                        </div>
                        <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }

    echo $output;

    $data = ob_get_clean();

    wp_send_json_success(minify_output($data));

    wp_send_json_error();
    wp_die();
}

function search_list_user_by_session()
{
    ob_start();

    $searchTerm = $_POST['searchTerm'];
    $token = $_POST['token'];
    require_once(dirname(__DIR__) . '/database/ChatUser.php');
    require_once(dirname(__DIR__) . '/database/ChatRooms.php');
    $chat_object = new ChatRooms;
    $user_object = new ChatUser;
    $user_object->setUserToken($token);
    $user_id = $user_object->get_user_id_from_token();
    if ($user_id == "") {
        wp_send_json_error("Tài khoản được đăng nhập bằng một thiết bị khác!");
        return;
    }
    $chat_object->setUserId($user_id);
    $user_data = $user_object->search_user_all_data_by_user($searchTerm);

    $output = "";
    if (!is_array($user_data) || count($user_data) == 0) {
        $output .= "No users are available to chat";
    } else {
        foreach ($user_data as $key => $user) {
            $lastmsg = $chat_object->get_last_msg_chat($user['user_id']);
            $you = "";
            $msg = "No message available";
            $date = "";
            if (is_object($lastmsg)) {
                $result = $lastmsg->msg;
                $date = $lastmsg->created_on;
                (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
                if ($user_id == $lastmsg->userid) $you = "You: ";
            }


            ($user['user_login_status'] != "Login") ? $offline = "offline" : $offline = "";
            $output .= '<a href="?user_id=' . $user["user_id"] . '">
                        <div class="content">
                        <img src="' . $user['user_profile'] . '" alt="">
                        <div class="details">
                            <span>' . $user['user_name'] . '</span>
                            <p>' . $you . $msg . '</p>
                            <span class="time">' . $date . '</span>
                        </div>
                        </div>
                        <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                    </a>';
        }
    }

    echo $output;

    $data = ob_get_clean();

    wp_send_json_success(minify_output($data));

    wp_send_json_error();
    wp_die();
}
function get_list_chat_by_session_userid_to()
{
    ob_start();

    $token = $_POST['token'];
    $userid_to = $_POST['userid_to'];
    require_once(dirname(__DIR__) . '/database/ChatUser.php');
    require_once(dirname(__DIR__) . '/database/ChatRooms.php');
    $chat_object = new ChatRooms;
    $user_object = new ChatUser;
    $user_object->setUserToken($token);
    $user_id = $user_object->get_user_id_from_token();
    if ($user_id == "") {
        wp_send_json_error("Tài khoản được đăng nhập bằng một thiết bị khác!");
        return;
    }
    $chat_object->setUserId($user_id);
    $user_to_object = new ChatUser;
    $user_to_object->setUserId($userid_to);
    $user_to_data = $user_to_object->get_user_data_by_id();
    $allmsg = $chat_object->get_all_msg_chat($userid_to);
    $output = "";

    if (is_array($allmsg) && count($allmsg) > 0) {
        foreach ($allmsg as $key => $value) {
            if ((int)$value->userid === (int)$user_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>' . $value->msg . '</p>
                                <span>' . $value->created_on . '</span>
                            </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                            <img src="' . $user_to_data['user_profile'] . '" alt="">
                            <div class="details">
                                <p>' . $value->msg . '</p>
                                <span>' . $value->created_on . '</span>
                            </div>
                            </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
    }

    echo $output;

    $data = ob_get_clean();

    wp_send_json_success(minify_output($data));

    wp_send_json_error();
    wp_die();
}

function insert_chat_by_session_userid_to()
{
    ob_start();

    $token = $_POST['token'];
    $userid_to = $_POST['userid_to'];
    $message = $_POST['message'];
    require_once(dirname(__DIR__) . '/database/ChatUser.php');
    require_once(dirname(__DIR__) . '/database/ChatRooms.php');
    $chat_object = new ChatRooms;
    $user_object = new ChatUser;
    $user_object->setUserToken($token);
    $user_id = $user_object->get_user_id_from_token();
    if ($user_id == "") {
        wp_send_json_error("Tài khoản được đăng nhập bằng một thiết bị khác!");
        return;
    }
    $chat_object->setUserId($user_id);
    $chat_object->setMessage($message);
    $chat_object->setCreatedOn();
    $allmsg = $chat_object->insert_message_chat($userid_to);

    $output = "";
    echo '<br>';
    echo $user_id;
    echo '/';
    echo $userid_to;
    echo '<br>';
    echo $output;

    $data = ob_get_clean();

    wp_send_json_success(minify_output($data));

    wp_send_json_error();
    wp_die();
}

function minify_output($code)
{
    $search = array(

        // Remove whitespaces after tags
        '/\>[^\S ]+/s',

        // Remove whitespaces before tags
        '/[^\S ]+\</s',

        // Remove multiple whitespace sequences
        '/(\s)+/s',

        // Removes comments
        '/<!--(.|\s)*?-->/'
    );
    $replace = array('>', '<', '\\1');
    $code = preg_replace($search, $replace, $code);
    return $code;
}
