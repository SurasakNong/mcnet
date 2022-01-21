<?php

    // specify your own database credentials
    /*private $host = "192.168.70.219";
    private $port = "3306";
    private $db_name = "mc_monitor";
    private $username = "administrator";
    private $password = "P@ssw0rd";*/

    // Test Database
    /*$host = "192.168.50.230";
    $port = "3392";
    $db_name = "mc_monitor";
    $username = "root";
    $password = "nong420631";

 
        try{
            $conn = new PDO("mysql:host=$host:$port;dbname=$db_name", "$username", "$password");
            //$this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", "$this->username", "$this->password");
            echo "Connection success.";
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        } */
		

	$dbhost = "192.168.50.230";
	 $dbuser = "root";
	 $dbpass = "nong420631";
	 $db = "mc_monitor";
	 $port = "3392";
	 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db,$port) or die("Connect failed: %s\n". $conn -> error);


?>