<?php
defined('ABSPATH') || exit;
class SQLServerConnection
{
    private $serverName;
    private $databaseName;
    private $username;
    private $password;
    private $conn;

    /**
     * Khởi tạo đối tượng SQLServerConnection với thông tin kết nối
     * @param string $serverName Tên máy chủ SQL Server
     * @param string $databaseName Tên cơ sở dữ liệu SQL Server
     * @param string $username Tên đăng nhập SQL Server
     * @param string $password Mật khẩu SQL Server
     */
    function __construct($serverName, $databaseName, $username, $password)
    {
        $this->serverName = $serverName;
        $this->databaseName = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Kết nối với SQL Server
     */
    function connect()
    {
        $connectionInfo = array(
            "Database" => $this->databaseName,
            "UID" => $this->username,
            "PWD" => $this->password
        );
        $this->conn = sqlsrv_connect($this->serverName, $connectionInfo);
        if ($this->conn === false) {
            // Nếu kết nối thất bại
            die(print_r(sqlsrv_errors(), true));
        }
    }

    /**
     * Kết nối với SQL Server
     */
    function isConnected()
    {
        $connectionInfo = array(
            "Database" => $this->databaseName,
            "UID" => $this->username,
            "PWD" => $this->password
        );
        $this->conn = sqlsrv_connect($this->serverName, $connectionInfo);
        if ($this->conn === false) {
            // Nếu kết nối thất bại
            die(print_r(sqlsrv_errors(), true));
        }
    }
    /**
     * Ngắt kết nối với SQL Server
     */
    function disconnect()
    {
        sqlsrv_close($this->conn);
    }

    /**
     * Thêm bảng mới vào cơ sở dữ liệu
     * @param string $tableName Tên bảng mới
     * @param string $columns Các cột trong bảng, ví dụ "id INT PRIMARY KEY, name VARCHAR(50), age INT"
     */
    function createTable($tableName, $columns)
    {
        $sql = "CREATE TABLE $tableName ($columns)";
        $this->executeNonQuery($sql);
    }

    /**
     * Xoá bảng khỏi cơ sở dữ liệu
     * @param string $tableName Tên bảng cần xoá
     */
    function dropTable($tableName)
    {
        $sql = "DROP TABLE $tableName";
        $this->executeNonQuery($sql);
    }

    /**
     * Thêm cột mới vào bảng
     * @param string $tableName Tên bảng cần thêm cột
     * @param string $columnName Tên cột mới
     * @param string $columnType Kiểu dữ liệu của cột, ví dụ "VARCHAR(50)"
     */
    function addColumn($tableName, $columnName, $columnType, $isNull = true)
    {
        $sql = "ALTER TABLE $tableName ADD $columnName $columnType";
        if ($isNull) {
            $sql .= " NULL";
        } else {
            $sql .= " NOT NULL";
        }
        $this->executeNonQuery($sql);
    }
    // Sửa thông tin của cột trong bảng
    function alterColumn($tableName, $columnName, $newDataType, $isNull = true)
    {
        $sql = "ALTER TABLE $tableName ALTER COLUMN $columnName $newDataType";
        if ($isNull) {
            $sql .= " NULL";
        } else {
            $sql .= " NOT NULL";
        }
        $this->executeNonQuery($sql);
    }
    /**
     * Xoá cột khỏi bảng
     * @param string $tableName Tên bảng cần xoá cột
     * @param string $columnName Tên cột cần xoá
     */
    function dropColumn($tableName, $columnName)
    {
        $sql = "ALTER TABLE $tableName DROP COLUMN $columnName";
        $this->executeNonQuery($sql);
    }

    /**
     * Thêm dữ liệu mới vào bảng
     * @param string $tableName Tên bảng cần thêm dữ liệu
     * @param array $data Mảng
     * chứa dữ liệu mới, ví dụ array("name" => "John", "age" => 25)
     */
    function insertData($tableName, $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_values($data));
        $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";
        $this->executeNonQuery($sql);
    }

    /**
     * Sửa dữ liệu trong bảng
     * @param string $tableName Tên bảng cần sửa dữ liệu
     * @param string $columnName Tên cột cần sửa dữ liệu
     * @param string $oldValue Giá trị cũ
     * @param string $newValue Giá trị mới
     */
    function updateData($tableName, $columnName, $oldValue, $newValue)
    {
        $sql = "UPDATE $tableName SET $columnName = '$newValue' WHERE $columnName = '$oldValue'";
        $this->executeNonQuery($sql);
    }

    /**
     * Xoá dữ liệu trong bảng
     * @param string $tableName Tên bảng cần xoá dữ liệu
     * @param string $columnName Tên cột cần xoá dữ liệu
     * @param string $value Giá trị cần xoá
     */
    function deleteData($tableName, $columnName, $value)
    {
        $sql = "DELETE FROM $tableName WHERE $columnName = '$value'";
        $this->executeNonQuery($sql);
    }

    /**
     * Chạy câu lệnh SQL trả về kết quả
     * @param string $sql Câu lệnh SQL
     * @return mixed Kết quả trả về của câu lệnh SQL
     */
    function executeQuery($sql)
    {
        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt === false) {
            // Nếu thực thi thất bại
            die(print_r(sqlsrv_errors(), true));
        }
        $results = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
        sqlsrv_free_stmt($stmt);
        return $results;
    }

    /**
     * Chạy store procedure trả về kết quả
     * @param string $procedureName Tên store procedure
     * @param array $params Mảng chứa tham số của store procedure, ví dụ array("param1" => "value1", "param2" => "value2")
     * @return mixed Kết quả trả về của store procedure
     */
    function executeStoredProcedure($procedureName, $params)
    {
        $paramString = "";
        foreach ($params as $key => $value) {
            $paramString .= "@" . $key . " = '" . $value . "', ";
        }
        $paramString = rtrim($paramString, ", ");
        $sql = "EXEC " . $procedureName . " " . $paramString;
        $stmt = sqlsrv_query($this->conn, $sql);
        if ($stmt === false) {
            // Nếu thực thi thất bại
            die(print_r(sqlsrv_errors(), true));
        }
        $results = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }
        sqlsrv_free_stmt($stmt);
        return $results;
    }

    /**
     * Đóng kết nối tới SQL Server
     */
    function closeConnection()
    {
        sqlsrv_close($this->conn);
    }
}
