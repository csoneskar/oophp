<?php
/**
 * A game.
 *
 */
class CGame2 {
	
	//private $hand;
	private $numPlayers;
	private $players;
	private $currentPlayer;
	private $playersTotalScore;
	
	
	/**
	 * Constructor - Creates a new hand
	 * 
	 */
	public function __construct($numPlayers) {
		$this->numPlayers = $numPlayers;
		$this->currentPlayer = 1;
		// Create the object or get it from the session
		if (isset($_SESSION['dicehand'])) {
			$i = 0;
			while($i <= $numPlayers) {
				$this->players[] = $_SESSION['dicehand'];
				$i++;
			}
		} else {
			$string = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
			for($i=0; $i < $numPlayers; $i++) {
				$this->players[] = new CDiceHand(1);
			}
			$_SESSION['dicehand'] = $this->players;
		}
		
	}
	
	/**
	 * Init the round.
	 *@return string about init
	 */
	public function InitRound() {
		for($i=0; $i < $this->numPlayers; $i++) {
			$this->players[$i]->SetSumRound(0);
			$this->players[$i]->SetSumTotal(0);
		}
		$this->currentPlayer = 0;
		return "<p>Ett nytt spel har startats. Värdena är nollställda.</p>";
	}
	
	/**
	 * Roll a dice
	 *@return string with information about your roll.
	 */
	public function Roll($player) {
		$this->currentPlayer = $player;
		$string = $this->players[$this->currentPlayer]->Roll();
		return $string;
	}
	
	
	/**
	 * Stop the round and save points.
	 *@return string with message including you total points and if you won.
	 */
	public function Stop() {
		$sumTotal = $this->players[$this->currentPlayer]->Calculate();
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
			$who = $this->currentPlayer + 1;
			$this->GetPlayersTotalScore();
			$string = "<p>GRATTIS spelare {$who} har vunnit!</p>";
			$string .= "<p>Gällande ställning: Spelare 1:{$this->playersTotalScore[0]}, Spelare 2:{$this->playersTotalScore[1]}</p>";
		} else {
			$string = "<p>Din totala poäng är {$sumTotal}</p>";
			$this->GetPlayersTotalScore();
			$string .= "<p>Gällande ställning: Spelare 1:{$this->playersTotalScore[0]}, Spelare 2:{$this->playersTotalScore[1]}</p>";
		}
		return $string;
	}
	
	public function GetPlayersTotalScore() {
		$i = 0;
		while($i < $this->numPlayers) {
			$this->playersTotalScore[$i] = $this->players[$i]->GetGreaterTotal();
			$i++;
		}
	}
	
}