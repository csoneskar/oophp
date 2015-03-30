<?php
class CContent {

	/**
	 * Memebers
	 */
	 private $db = null;

	
	/**
	 * Constructor 
	 *
	 * @param 
	 *
	 */
	public function __construct($dbParams=null) {
		$this->db = new CDatabase($dbParams);
	}
	
	
	public function InitDB() {
	// Restore the database to its original settings
	//$sql      = 'movie.sql';
	//$mysql    = '/usr/bin/mysql';
	//$host     = 'blu-ray.student.bth.se';
	//$login    = 'cewe14';
	//$password = "r6I4e2Z(";


	// Use these settings on windows and WAMPServer, 
	// but you must check - and change - your path to the executable mysql.exe
	$sql 	  = 'reset.sql';
	$mysql    = 'C:\xampp\mysql\bin\mysql.exe';		//Localhost
	$login    = 'root';
	$password = '';
	$host 	  = 'localhost';
		
	// Use on Unix/Unix/Mac
	//$cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";

	// Use on Windows, remove password if its empty
	//$cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";
	$cmd = "$mysql -h{$host} -u{$login} < $sql";

	$res = exec($cmd);
	$output = "<p>Databasen är återställd via kommandot<br/><code>{$cmd}</code></p><p>{$res}</p>";	
	return $output;
	}
	
	public function GetContent($id) {
		// Select information on the post 
		$sql = 'SELECT * FROM Content WHERE id = ?';
		$params = array($id);
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
		 
		if(isset($res[0])) {
		  $post = $res[0];
		}
		else {
		  die('Failed: There is no post with that id');
		}
		return $post;
	}
	
	public function CreateContent() {
		
	}
	
	public function EditContent($params) {
		$sql = '
		UPDATE Content SET
		  title = ?,
		  slug 	= ?,
		  url 	= ?,
		  data 	= ?,
		  type 	= ?,
		  filter 	= ?,
		  published 	= ?,
		  updated 	= NOW()
		WHERE 
		  id = ?
		';
		$url = empty($url) ? null : $url;	//Check if URL is empty, if so set to NULL
		$res = $this->db->ExecuteQuery($sql, $params);
		$this->db->SaveDebug();
		if($res) {
			$output = 'Informationen sparades.';
		}
		else {
			$output = 'Informationen sparades EJ.<br><pre>' . print_r($this->db->ErrorInfo(), 1) . '</pre>';
		}
		return $output;
	}
	
	public function DeleteContent() {
		
	}
	
	
}