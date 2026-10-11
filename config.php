<?php
class configuracion {
    public $host;
	public $db;
	public $user;
	public $pass;
	public $charset;
	public $dsn;
    function __construct() {
		$this->host = 'localhost';
		$this->db = 'universidad_2025';
		$this->user = 'root';
		$this->pass = 'toor';
		$this->charset = 'utf8mb4';
		$this->dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
    }
}

?>
