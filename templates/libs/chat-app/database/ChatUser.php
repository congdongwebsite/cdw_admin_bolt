<?php

//ChatUser.php

class ChatUser
{
	private $user_id;
	private $user_name;
	private $user_email;
	private $user_password;
	private $user_profile;
	private $user_status;
	private $user_created_on;
	private $user_verification_code;
	private $user_login_status;
	private $user_token;
	private $user_connection_id;
	private $token_key = 'user_token_chat';
	private $status_login_key = 'user_status_login_chat';
	private $user_connection_key = 'user_user_connection_chat';
	private $user_avatar = 'user_avatar';
	public $connect;

	public function __construct()
	{
		//require_once('Database_connection.php');

		//$database_object = new Database_connection;

		//$this->connect = $database_object->connect();
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setUserName($user_name)
	{
		$this->user_name = $user_name;
	}

	function getUserName()
	{
		return $this->user_name;
	}

	function setUserEmail($user_email)
	{
		$this->user_email = $user_email;
	}

	function getUserEmail()
	{
		return $this->user_email;
	}

	function setUserPassword($user_password)
	{
		$this->user_password = $user_password;
	}

	function getUserPassword()
	{
		return $this->user_password;
	}

	function setUserProfile($user_profile)
	{
		$this->user_profile = $user_profile;
	}

	function getUserProfile()
	{
		return $this->user_profile;
	}

	function setUserStatus($user_status)
	{
		$this->user_status = $user_status;
	}

	function getUserStatus()
	{
		return $this->user_status;
	}

	function setUserCreatedOn($user_created_on)
	{
		$this->user_created_on = $user_created_on;
	}

	function getUserCreatedOn()
	{
		return $this->user_created_on;
	}

	function setUserVerificationCode($user_verification_code)
	{
		$this->user_verification_code = $user_verification_code;
	}

	function getUserVerificationCode()
	{
		return $this->user_verification_code;
	}

	function setUserLoginStatus($user_login_status)
	{
		$this->user_login_status = $user_login_status;
	}

	function getUserLoginStatus()
	{
		return $this->user_login_status;
	}

	function getUserLoginStatusLabel()
	{
		return ($this->user_login_status != "Login") ? "Offline" :  "Online";;
	}

	function setUserToken($user_token)
	{
		$this->user_token = $user_token;
	}

	function getUserToken()
	{
		return $this->user_token;
	}

	function setUserConnectionId($user_connection_id)
	{
		$this->user_connection_id = $user_connection_id;
	}

	function getUserConnectionId()
	{
		return $this->user_connection_id;
	}

	function make_avatar($character)
	{
		$path = "images/" . time() . ".png";
		$image = imagecreate(200, 200);
		$red = rand(0, 255);
		$green = rand(0, 255);
		$blue = rand(0, 255);
		imagecolorallocate($image, $red, $green, $blue);
		$textcolor = imagecolorallocate($image, 255, 255, 255);

		$font = dirname(__FILE__) . '/font/arial.ttf';

		imagettftext($image, 100, 0, 55, 150, $textcolor, $font, $character);
		imagepng($image, $path);
		imagedestroy($image);
		return $path;
	}

	function get_user_data_by_email()
	{
		$user = get_user_by('email', $this->user_email);
		$this->user_email =  $user->ID;

		return [
			'user_id'    => $user->ID,
			'user_name'  =>  $user->display_name,
			'user_profile'   =>  $user->user_nicename,
		];
	}

	function save_data()
	{
		$query = "
		INSERT INTO chat_user_table (user_name, user_email, user_password, user_profile, user_status, user_created_on, user_verification_code) 
		VALUES (:user_name, :user_email, :user_password, :user_profile, :user_status, :user_created_on, :user_verification_code)
		";
		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_password', $this->user_password);

		$statement->bindParam(':user_profile', $this->user_profile);

		$statement->bindParam(':user_status', $this->user_status);

		$statement->bindParam(':user_created_on', $this->user_created_on);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function is_valid_email_verification_code()
	{
		$query = "
		SELECT * FROM chat_user_table 
		WHERE user_verification_code = :user_verification_code
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		$statement->execute();

		if ($statement->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function enable_user_account()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_status = :user_status 
		WHERE user_verification_code = :user_verification_code
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_status', $this->user_status);

		$statement->bindParam(':user_verification_code', $this->user_verification_code);

		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}
	function logout()
	{
		$status = get_user_meta($this->user_id, $this->status_login_key, true);
		$a = update_user_meta($this->user_id, $this->token_key, "-1");
		$c = update_user_meta($this->user_id, $this->status_login_key, $this->user_login_status);

		return $a && ($c || $status == $this->user_login_status);
	}

	function update_user_login_data()
	{
		$status = get_user_meta($this->user_id, $this->status_login_key, true);
		$a = update_user_meta($this->user_id, $this->token_key, $this->user_token);
		$c = update_user_meta($this->user_id, $this->status_login_key, $this->user_login_status);

		return $a && ($c || $status == $this->user_login_status);
	}

	function get_user_data_by_id()
	{
		$user = get_user_by('id', $this->user_id);
		if ($user == null) return false;
		$this->user_email =  $user->user_email;
		$this->user_login_status = get_user_meta($this->user_id, $this->status_login_key, true);

		return [
			'user_id'    => $user->ID,
			'user_name'  =>  $user->display_name,
			'user_profile'   => wp_get_attachment_image_url(get_user_meta($this->user_id, $this->user_avatar, true)),
		];
	}

	function upload_image($user_profile)
	{
		$extension = explode('.', $user_profile['name']);
		$new_name = rand() . '.' . $extension[1];
		$destination = 'images/' . $new_name;
		move_uploaded_file($user_profile['tmp_name'], $destination);
		return $destination;
	}

	function update_data()
	{
		$query = "
		UPDATE chat_user_table 
		SET user_name = :user_name, 
		user_email = :user_email, 
		user_password = :user_password, 
		user_profile = :user_profile  
		WHERE user_id = :user_id
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_name', $this->user_name);

		$statement->bindParam(':user_email', $this->user_email);

		$statement->bindParam(':user_password', $this->user_password);

		$statement->bindParam(':user_profile', $this->user_profile);

		$statement->bindParam(':user_id', $this->user_id);

		if ($statement->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function get_user_all_data()
	{
		// $members = get_users();
		// array_map(function ($member) {
		// 	$member->usermeta =  array_map(function ($data) {
		// 		return reset($data);
		// 	}, get_user_meta($member->ID));
		// 	return $member;
		// }, $members);
		// echo '<pre>';
		// var_dump($members);
		// echo '</pre>';
		$data = get_users(array('fields' => array('display_name', 'ID', 'user_login', 'user_nicename', 'user_email', 'all_with_meta')));
		$users = array();
		foreach ($data as $key => $value) {
			$user = array();

			$user['user_id'] = $value->ID;
			$user['user_name'] = $value->display_name;
			$user['user_login_status'] = get_user_meta($value->ID, $this->status_login_key, true);
			$user['unique_id'] = get_user_meta($value->ID, $this->token_key, true);
			$user['user_profile'] =  wp_get_attachment_image_url(get_user_meta($value->ID, $this->user_avatar, true));
			$users[] = $user;
		}

		return $users;
	}
	function get_user_all_data_by_user()
	{
		// $members = get_users();
		// array_map(function ($member) {
		// 	$member->usermeta =  array_map(function ($data) {
		// 		return reset($data);
		// 	}, get_user_meta($member->ID));
		// 	return $member;
		// }, $members);
		// echo '<pre>';
		// var_dump($members);
		// echo '</pre>';
		$data = get_users(array('fields' => array('display_name', 'ID', 'user_login', 'user_nicename', 'user_email', 'all_with_meta')));
		$users = array();
		foreach ($data as $key => $value) {
			if ($value->ID == $this->user_id) continue;
			$user = array();

			$user['user_id'] = $value->ID;
			$user['user_name'] = $value->display_name;
			$user['user_login_status'] = get_user_meta($value->ID, $this->status_login_key, true);
			$user['unique_id'] = get_user_meta($value->ID, $this->token_key, true);
			$user['user_profile'] =  wp_get_attachment_image_url(get_user_meta($value->ID, $this->user_avatar, true));
			$users[] = $user;
		}

		return $users;
	}

	function search_user_all_data_by_user($searchTerm)
	{
		// $members = get_users();
		// array_map(function ($member) {
		// 	$member->usermeta =  array_map(function ($data) {
		// 		return reset($data);
		// 	}, get_user_meta($member->ID));
		// 	return $member;
		// }, $members);
		// echo '<pre>';
		// var_dump($members);
		// echo '</pre>';
		$data = get_users(array('fields' => array('display_name', 'ID', 'user_login', 'user_nicename', 'user_email', 'all_with_meta'), 'search' => '*' . $searchTerm . '*', 'search_columns' => array('user_login', 'display_name')));
		$users = array();
		foreach ($data as $key => $value) {
			if ($value->ID == $this->user_id) continue;
			$user = array();

			$user['user_id'] = $value->ID;
			$user['user_name'] = $value->display_name;
			$user['user_login_status'] = get_user_meta($value->ID, $this->status_login_key, true);
			$user['unique_id'] = get_user_meta($value->ID, $this->token_key, true);
			$user['user_profile'] =  wp_get_attachment_image_url(get_user_meta($value->ID, $this->user_avatar, true));
			$users[] = $user;
		}

		return $users;
	}
	function get_user_all_data_with_status_count()
	{
		$query = "
		SELECT user_id, user_name, user_profile, user_login_status, (SELECT COUNT(*) FROM chat_message WHERE to_user_id = :user_id AND from_user_id = chat_user_table.user_id AND status = 'No') AS count_status FROM chat_user_table
		";

		$statement = $this->connect->prepare($query);

		$statement->bindParam(':user_id', $this->user_id);

		$statement->execute();

		$data = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $data;
	}

	function update_user_connection_id()
	{
		$users = get_users(array(
			'meta_key' => $this->token_key,
			'meta_value' => $this->user_token
		));

		foreach ($users  as $user) {
			update_user_meta($user->user_id, $this->$user_connection_key, $this->user_connection_id);
		}
	}

	function get_user_id_from_token()
	{
		$this->user_id = "";
		$users = get_users(array(
			'meta_key' => $this->token_key,
			'meta_value' => $this->user_token
		));

		foreach ($users  as $user) {
			$this->user_id = $user->ID;
		}

		return $this->user_id;
	}
}
