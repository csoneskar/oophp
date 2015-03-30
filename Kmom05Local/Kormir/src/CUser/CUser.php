<?php
/**
 * Handles users in the database.
 * 
 */
class CUser {
	
	/**
	 * Memebers
	 */
	private $isAuth = null;
	private $user = null;
	private $password = null;
	private $acronym = null;
	private $output = null;
	private $myKormir = null;
	private $db = null;
	
	
	public function __construct($myKormir) {
		$this->myKormir = $myKormir;
		
		//if($this->myKormir){}

		//$this->Login($this->acronym, $this->password);	
		$this->db = new CDatabase($this->myKormir);
	}
	
	
	/**
	 * Logs in user if credentials are correct
	 * @return boolean Returns true if user is successfully logged in, otherwise false
	 */
	public function Login($acronym, $password) {
		$this->acronym = $acronym;
		$this->password = $password;
		if($this->IsAuthenticated()) {
			echo "redan inloggad";
			$this->output = "Du är inloggad som: $this->acronym ({$_SESSION['user']->name})";
		}
		else if(($this->acronym && $this->password) != null) {
			echo "Försöker logga in";
			//Logga in
			$sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
			$params=array($this->acronym, $this->password);
			$res = $this->db->ExecuteSelectQueryAndFetchAll($sql, $params);
			if(isset($res[0])) {
				$_SESSION['user'] = $res[0];
				$this->output = "Du är inloggad som: $acronym ({$_SESSION['user']->name})";
			} else {
				$this->output = "Fel inloggningsuppgifter";
			}
		}
	}
	
	/**
	 * Log out the user
	 *
	 */
	public function Logout() {
		unset($_SESSION['user']);
		$this->output = "Du är nu utloggad";
		$this->acronym = null;
		$this->password = null;
		return $this->output;
	}
	
	/**
	 * Checks if the user is logged in
	 * @return boolean True if user is logged in, otherwise false
	 */
	public function IsAuthenticated() {
		// Check if user is authenticated.
		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
		if($acronym != null) {
			$this->acronym = $acronym;
			$this->isAuth = true;
			$this->output = "Du är inloggad som: $acronym ({$_SESSION['user']->name})";
		}
		else {
			$this->isAuth = false;
			$this->output = "Du är INTE inloggad.";
		}
		return $this->isAuth;
	}
	
	/**
	 * Returns the acronym of the logged in user
	 * @return String The user acronym
	 */
	public function GetAcronym() {
		$this->acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
		return $this->acronym;
	}
	
	/**
	 * Returns the namne of the logged in user
	 * @return String The user name
	 */
	public function GetName() {
		$this->user = $_SESSION['user']->name;
		return $this->user;
	}
	
	public function GetOutput() {
		return $this->output;
	}
	
}