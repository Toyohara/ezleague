<?php 
/*
 * ######################################################
* Author: Michael Loring
* Project: ezLeague v3.x Gaming League Script
* Began: July 27th, 2014
* Finished: Code is never done, but in a working state
* Notes: Database Connection class...
* ######################################################
*/
class DB_Class {
	
	var $link;

	var $host 	  = "localhost"; //database host (check with your hosting provider, but localhost is usually correct)
	var $username = "root"; //database username
	var $password = "root"; //database password
	var $database = "ezleague"; //mysql database
	var $prefix	  = "ezl"; //table prefix
	var $site_url = "http://localhost/ezleague"; //do not leave a trailing slash (ex: http://www.mdloring.com/ezleague/)

	public function __construct() {
		
		global $connection;
		mb_internal_encoding( 'UTF-8' );
		mb_regex_encoding( 'UTF-8' );
		$this->link = new mysqli( $this->host, $this->username, $this->password, $this->database );
		
	}
	
	public function __destruct() {
		
		$this->disconnect();
		
	}
		
	public function query($query) {
		
		$result = mysqli_query($query, $this->link) or die ("Invalid query: " . mysqli_error());
		return $result;
		$this->disconnect();
		 
	}
	
	public function numRows($result) {
		
		$count = mysqli_num_rows($result);
		return $count;
		 
	}
	
	public function fetch($query) {
		
	  	$data = array();
		$result = $this->link->query($query);
			while($row = mysqli_fetch_assoc($result)) {
				$data[] = $row;
			}

	 	return $data;
	 	$this->disconnect();
		  
	}
	
	public function disconnect() {
		
		$this->link->close();
		
	}
	
	public function sanitize($text) {
		
		$text = $this->link->real_escape_string($text);
		return $text;
		
	}
	
	public function success($text) {
		
		echo "<strong>Успех!</strong> " . $text;
		
	}
	
	public function error($text) {
		
		echo "<strong>Ошибка</strong> " . $text;
		
	}
	
}
?>
