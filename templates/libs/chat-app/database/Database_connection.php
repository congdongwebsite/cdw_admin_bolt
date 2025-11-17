<?php

//Database_connection.php

class Database_connection
{
	public $tb_chat_rooms = 'chatrooms';

	function connect()
	{
		global $wpdb;
		$shw_db_version = '1';
		$installed_ver = get_option("shw_chat_db_version");
		$table_name = $wpdb->prefix . $this->tb_chat_rooms;
		$this->tb_chat_rooms = $table_name;
		if ($installed_ver != $shw_db_version) {
			$this->dropTable();
			$sql = "CREATE TABLE $table_name (
				id int NOT NULL AUTO_INCREMENT,
				userid int NOT NULL,
				userid_to int NOT NULL,
				msg TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
				created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY  (id)
			);";

			$wpdb->query($sql);
			update_option("shw_chat_db_version", $shw_db_version);
		}
		return $shw_db_version;
	}
	function dropTable()
	{
		global $wpdb;
		$tableArray = [   
			$this->tb_chat_rooms,
		 ];
  
		foreach ($tableArray as $tablename) {
		   $wpdb->query("DROP TABLE IF EXISTS $tablename");
		}
	}
	function insert_multiple_rows($table, $request)
	{
		global $wpdb;

		$column_keys   = '';
		$column_values = '';
		$sql           = '';
		$last_key      = array_key_last($request);
		$first_key     = array_key_first($request);
		foreach ($request as $k => $value) {
			$keys = array_keys($value);

			// Prepare column keys & values.
			foreach ($keys as $v) {
				$column_keys   .= sanitize_key($v) . ',';
				$sanitize_value = sanitize_text_field($value[$v]);
				$column_values .= is_numeric($sanitize_value) ? $sanitize_value . ',' : "'$sanitize_value'" . ',';
			}
			// Trim trailing comma.
			$column_keys   = rtrim($column_keys, ',');
			$column_values = rtrim($column_values, ',');
			if ($first_key === $k) {
				$sql .= "INSERT INTO {$table} ($column_keys) VALUES ($column_values),";
			} elseif ($last_key == $k) {
				$sql .= "($column_values)";
			} else {
				$sql .= "($column_values),";
			}

			// Reset keys & values to avoid duplication.
			$column_keys   = '';
			$column_values = '';
		}
		return $wpdb->query($sql);
	}
	function insert_multiple_rows_format($table, $request, $format)
	{
		global $wpdb;
		$ids = array();
		foreach ($request as $k => $value) {
			$wpdb->insert($table, $value, $format);
			$ids[] = $wpdb->insert_id;
		}
		return $ids;
	}

	function insert_rows_format($table, $data, $format)
	{
		global $wpdb;
		$wpdb->insert($table, $data, $format);
		return $wpdb->insert_id;
	}
	function get_row($table, $join, $order, $where, $mixed)
	{
		global $wpdb;

		if ($where == '')
			$mylink = "SELECT * FROM {$table} {$join} {$order}";
		else
			$mylink = $wpdb->prepare("SELECT * FROM {$table} {$join} WHERE {$where} {$order}", $mixed);

		return $wpdb->get_row($mylink);
	}

	function get_results($table, $select, $join, $order, $where, $mixed)
	{
		global $wpdb;
		if ($select == '') $select = "*";

		if ($where == '')
			$mylink = "SELECT * FROM {$table} {$join} {$order}";
		else
			$mylink = $wpdb->prepare("SELECT {$select} FROM {$table} {$join} WHERE {$where} {$order}", $mixed);

		return $wpdb->get_results($mylink);
	}
}
