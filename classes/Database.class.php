<?php
class Database{
	
	var $server		 = "localhost";
	var $userName 	 = "db_user";
	var $psd 		 = "rootroot";
	var $db_name	 = "demo_db";
	var $con ='';
	
	public function DB_CONNECT() {
	$con = mysqli_connect($this->server, $this->userName, $this->psd, $this->db_name) or die("Connection failed: " . mysqli_connect_error());
	 
	/* check connection */
	if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
	} else {
	$this->conn = $con;
	}
	return $this->conn;
	}
}


?>
