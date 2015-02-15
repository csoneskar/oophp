<?php
/**
 * A hand og dices, with graphical representation, to roll.
 *
 */
class CDiceHand {
	//Properties
	private $dices;		//Array with object of type CDiceImage
	private $numDices;	//Number of dices to roll
	private $sum;		//Sum of all dices
	private $sumRound;	//Sum of all rounds
	private $sumTotal;	//The greater sum for the player
	private $roll;		//What was the face of the dice?
	
	/**
	 * Constructor
	 * @param int $numDices, the number of dices in the hand, defaults to five dices.
	 */
	public function __construct($numDices = 5) {
		for($i=0; $i < $numDices; $i++) {
			$this->dices[] = new CDiceImage();
		}
		$this->numDices = $numDices;
		$this->sum = 0;
		$this->sumRound = 0;
		$this->sumTotal = 0;
	}
	
	/**
	 * Roll all dices in the hand.
	 *@return string message about your roll
	 */
	public function Roll() {
		$this->sum = 0;
		for($i=0; $i < $this->numDices; $i++) {
			$this ->roll = $this->dices[$i]->Roll(1, 0);
			if ($this->roll == 1){
				$string = "<hr />";
				$string .= $this->GetRollAsImageList();
				$string .= "<p>Åh nej, det blev en etta. Dina poäng för denna rundan nollställs.</p>";
				$string .= "<p>Din totala poäng är {$this->GetGreaterTotal()}</p>";
				$this->sumRound = 0;
			} else {
				$this->sum += $this->roll;
				$this->sumRound += $this->roll;
				$string = "<hr />";
				$string .= $this->GetRollAsImageList();
				$string .= "<p>Summan av detta kast är {$this->GetTotal()}</p>";
				$string .= "<p>Summan av alla tärningskast i denna rundan är {$this->GetRoundTotal()}</p>";
			}
		}
		return $string;
	}

	public function GetRoll() {
		return $this->roll;
	}
	
	/**
	 * Get the sum of the last roll.
	 * If sum equals 1, the sum for this round is scrapped.
	 * @return int as a sum of the last roll, or 0 if no roll has been made.
	 */
	public function GetTotal() {
		return $this->sum;
	}
	
	/**
	 *  Set the sum for the round
	 *
	 */
	public function SetSumRound($sum) {
		$this->sumRound = $sum;
	}
	
	/**
	 * Set the total sum for the game
	 *
	 */
	public function SetSumTotal($sum) {
		$this->sumTotal = $sum;
	}
	
	
	/**
	 * Calculate the total points.
	 *@return int with sumTotal.
	 */
	public function Calculate() {
		$this->sumTotal += $this->sumRound;
		$this->sumRound = 0;
		return $this->sumTotal;
	}
	
	/**
	 * Get the accumulated sum of the round
	 *@return int as a sum of the round, or 0 if no roll has been made.
	 */
	public function GetRoundTotal() {
		return $this->sumRound;
	}
	
	/**
	 * Get the accumulated sum of the player
	 *@return string as a sum of the player, or 0 if no roll has been made.
	 */
	public function GetGreaterTotal() {
		return $this->sumTotal;
	}

	
	/**
	 * Get the rolls as a serie of images
	 * @return string as the html representation of the last roll.
	 */
	public function GetRollAsImageList() {
		$html = "<ul class='dice'>";
		foreach($this->dices as $dice) {
			$val = $dice->GetLastRoll();
			$html .= "<li class='dice-{$val}'></li>";
		}
		$html .= "</ul>";
		return $html;
	}
}