<?php
/**
 * A CDise class to play around with a dice
 *
 */
 class CDice {
	//properties
	protected $rolls = array();
	private $faces;
	private $last;
	
	/**
	* Contructor
	*/
	public function __construct($faces=6) {
		//echo __METHOD__;
		$this->faces = $faces;
	} 
	
	/**
	* Get the number of faces.
	*
	*/
	public function GetFaces() {
		return $this->faces;
	}
	
	/**
	* Get the rolls as an array.
	*
	*/
	public function GetRollsAsArray() {
		return $this->rolls;
	}
	
	/**
	* Roll the dice
	*
	*/
	public function Roll($times, $faces) {
		$this->rolls = array();
		if($faces != null) {
			$this->faces = $faces;
		}
		
		for($i = 0; $i < $times; $i++) {
			$this->last = rand (1, $this->faces);
			$this->rolls[] = $this->last;
		}
		return $this->last;
	}
	
	/**
	 * Get the last rolled value.
	 *
	 */
	public function GetLastRoll() {
		return $this->last;
	}
	
	/**
	* Get the total from the last roll(s).
	*
	*/
	public function GetTotal() {
		return array_sum($this->rolls);
	}
	
	/**
	* Get the median from the last roll(s).
	*
	*/
	public function GetMean($times) {
		$sum = array_sum($this->rolls);
		if($sum!=0){
			return $sum/$times;
		}
	}
	
	/**
	 * Destructor
	 */
	public function __destruct() {
	  //echo __METHOD__;
	}
 }