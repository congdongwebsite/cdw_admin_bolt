<?php

class ChatRooms
{
	private $chat_id;
	private $user_id;
	private $message;
	private $created_on;
	protected $connect;
	protected $version_db;
	protected $database_object;

	public function setChatId($chat_id)
	{
		$this->chat_id = $chat_id;
	}

	function getChatId()
	{
		return $this->chat_id;
	}

	function setUserId($user_id)
	{
		$this->user_id = $user_id;
	}

	function getUserId()
	{
		return $this->user_id;
	}

	function setMessage($message)
	{
		$this->message = $message;
	}

	function getMessage()
	{
		return $this->message;
	}

	function setCreatedOn($created_on = "")
	{
		if ($created_on == "")
			$this->created_on = current_time( 'Y-m-d H:i:s' );
		else
			$this->created_on = $created_on;
	}

	function getCreatedOn()
	{
		return $this->created_on;
	}

	public function __construct()
	{
		require_once("Database_connection.php");

		$this->database_object = new Database_connection;
		$this->version_db = $this->database_object->connect();
	}

	function save_chat()
	{
		$format = array('%d', '%s', '%s');
		$data = array('userid' => $this->user_id, 'msg' => $this->message, 'created_on' => $this->created_on);

		$this->database_object->insert_rows_format($this->database_object->tb_chat_rooms, $data, $format);
	}

	function insert_message_chat($userid_to)
	{
		$format = array('%d', '%d', '%s', '%s');
		$data = array('userid' => $this->user_id, 'userid_to' => $userid_to, 'msg' => $this->message, 'created_on' => $this->created_on);

		return	$this->database_object->insert_rows_format($this->database_object->tb_chat_rooms, $data, $format) != false;
	}
	function get_all_chat_data()
	{
		$where = "";
		$mixed = "";
		$order = "ORDER BY {$this->database_object->tb_chat_rooms}.id ASC";

		$result = $this->database_object->get_row($this->database_object->tb_chat_rooms, '', $order, $where, $mixed);

		return $result != null ? $result : [];
	}

	function get_last_msg_chat($userid_to)
	{
		if ($this->user_id == $userid_to) return [];
		$where = "(userid = %d AND userid_to = %d) OR (userid = %d AND userid_to = %d) ";
		$mixed = array($this->user_id, $userid_to, $userid_to, $this->user_id);
		$order = "ORDER BY {$this->database_object->tb_chat_rooms}.created_on DESC LIMIT 1";

		$result = $this->database_object->get_row($this->database_object->tb_chat_rooms, '', $order, $where, $mixed);

		return $result != null ? $result : [];
	}

	function get_all_msg_chat($userid_to)
	{
		if ($this->user_id == $userid_to) return [];

		$where = "(userid = %d AND userid_to = %d) OR (userid = %d AND userid_to = %d) ";
		$mixed = array($this->user_id, $userid_to, $userid_to, $this->user_id);
		$order = "ORDER BY {$this->database_object->tb_chat_rooms}.created_on ";

		$result = $this->database_object->get_results($this->database_object->tb_chat_rooms, '', '', $order, $where, $mixed);

		return $result != null ? $result : [];
	}
}
