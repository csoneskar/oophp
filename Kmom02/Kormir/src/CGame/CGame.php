<?php
/**
 * A game.
 *
 */
class CGame {
	
	private $hand;
	
	
	/**
	 * Constructor - Creates a new hand
	 * 
	 */
	public function __construct() {
		// Create the object or get it from the session
		if (isset($_SESSION['dicehand'])) {
			$this->hand = $_SESSION['dicehand'];
		} else {
			$string = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
			$this->hand = new CDiceHand(1);
			$_SESSION['dicehand'] = $this->hand;
		}
		
	}
	
	/**
	 * Init the round.
	 *@return string about init
	 */
	public function InitRound() {
		$this->hand->SetSumRound(0);
		$this->hand->SetSumTotal(0);
		return "<p>Ett nytt spel har startats. Värdena är nollställda.</p>";
	}
	
	/**
	 * Roll a dice
	 *@return string with information about your roll.
	 */
	public function Roll() {
		$string = $this->hand->Roll();
		return $string;
	}
	
	
	/**
	 * Stop the round and save points.
	 *@return string with message including you total points and if you won.
	 */
	public function Stop() {
		$sumTotal = $this->hand->Calculate();
		$string = $this->Won($sumTotal);
		return $string;
	}
	
	/**
	 * Decide if you have won.
	 *@param int the total points you have accumulated
	 *@return string with result
	 */
	public function Won($sumTotal) {
		if($sumTotal >= 100) {
			$string = "<p>GRATTIS du har vunnit!</p>";
			$string .= "<p>Din totala poäng är {$sumTotal}</p>";
		} else {
			$string = "<p>Din totala poäng är {$sumTotal}</p>";
		}
		return $string;
	}
	
}