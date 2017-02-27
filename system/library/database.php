<?php

/**
 *
 */
class Database {
	// private $username = '';
	// private $password = '';
	private $db = '';
	// private static $instance;
	// private $stmt = '';

	public function __construct() {
		$this->db = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME, DBUSERNAME, PASSWORD);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->exec('set names utf8');
	}

	// public static function &getInstance() {
	// 	if (empty(self::$instance)) {
	// 		self::$instance = new Database();
	// 	}
	// 	return $self::$instance;
	// }
	/*
		将参数组成SQL语句
		@param 		string
		@param 		array
		@return 	string
	*/
	private function insPre($table, $vals) {
		$keys = array_keys($vals);
		$col = implode(',', $keys);
		$val = implode(',:', $keys);
		$val = ":" . $val;
		$sql = "INSERT INTO " . $table . "(" . $col . ")VALUES(" . $val . ")";
		return $sql;

	}

	public function insert($table, $vals) {
		$sql = $this->insPre($table, $vals);
		$stmt = $this->db->prepare($sql);

		foreach ($vals as $key => $value) {
			$key = ":" . $key;
			$stmt->bindValue($key, $value);
		}
		if (!$stmt->execute()) {
			return false;
		}
		if ($table == 'novellist') {
			return $this->db->lastInsertId();
		}
		return true;
	}

	private function update() {

	}

	private function select() {

	}

	private function delete() {

	}

	private function output($s) {
		echo "<pre>";
		die(var_dump($s));
	}
}