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
	
	public function printMenu($heading, $menuchoice) {
		$this->menu  = "<aside class='left'>";
		$this->menu .= "<nav class='vmenu'>";
		$this->menu .= "<ul {$this->id} >";
		$this->menu .= "<li><h4>$heading</h4>";
		$this->menu .= "<ul>";
		foreach($menuchoice AS $val) {
			$this->menu .= "<li id={$val['id']}-><a href='{$val['id']}.php'>{$val['heading']}</a>";
		}
		$this->menu .= "</ul>";
		$this->menu .= "</ul>";
		$this->menu .= "</nav>";
		$this->menu .= "</aside>";
		
		return $this->menu;
	}

}
?>