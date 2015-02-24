<?php
class CAside {

	private $p;
	private $menu;
	private $id;
	
	public function __construct($p) {
		$this->id = null;
		if(isset($p)) {
			$this->p = $p;
			$this->id = "id='".strip_tags($p)."'";
		}
	}
	
	public function printMenu() {
		$this->menu  = "<aside class='left'>";
		$this->menu .= "<nav class='vmenu'>";
		$this->menu .= "<ul {$this->id} >";
		$this->menu .= "<li><h4>Hur vill du spela?</h4>";
		$this->menu .= "<ul>";
		$this->menu .= "<li id='alone-'><a href='alone.php'>Spela själv</a>";
		$this->menu .= "<li id='friend-'><a href='friend.php'>Spela med en vän</a>";
		$this->menu .= "<li id='computer-'><a href='computer.php'>Spela mot datorn</a>";
		$this->menu .= "</ul>";
		$this->menu .= "</ul>";
		$this->menu .= "</nav>";
		$this->menu .= "</aside>";
		
		return $this->menu;
	}

}
?>