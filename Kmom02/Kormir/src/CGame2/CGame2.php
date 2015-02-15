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
	private $playerName;
	private $computerPlayer;
	private $didSomebodyWin;
	
	
	/**
	 * Constructor - Creates a new hand
	 * If playing against computer hand[1] aka player 2 is the computer
	 * @param numPlayer int, how many players will play
	 * @param computerPlayer int, set to 1 if playing against computer
	 */
	public function __construct($numPlayers, $computerPlayer) {
		$this->numPlayers = $numPlayers;
		$this->currentPlayer = 0;
		$this->computerPlayer = $computerPlayer;
		$this->didSomebodyWin = 0;
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
		$string = "<p>Ett nytt spel har startats. Värdena är nollställda.</p>";
		return $string;
	}
	
	/**
	 * Roll a dice
	 *@return string with information about your roll.
	 */
	public function Roll($player) {
		$playAgain = 0;
		if ($player > $this->numPlayers) {
			$string = "<p>Detta spelet är bara för en spelare</p>";
		}
		else {
			$this->currentPlayer = $player;
			if ($this->computerPlayer == 1 && $this->currentPlayer == 1) {
				//It is the computers turn
				$string = $this->ComputerRoll();
			} else {
				//It is the players turn
				$string = $this->players[$this->currentPlayer]->Roll();
				if($this->numPlayers > 1 && ($this->players[$this->currentPlayer]->GetRoll() == 1)) {
					$string .= "<p>Nu är det motståndarens tur att kasta tärningen.</p>";
					$this->currentPlayer = 1;
					if ($this->computerPlayer == 1 && $this->currentPlayer == 1) {
						$string .= $this->ComputerRoll();
					}
				}
				else {
					$string .= "<p>Det är din tur igen.</p>";
				}
			}
		}
		return $string;
	}
	
	public function ComputerRoll() {
		if($this->didSomebodyWin == 1) {
			$string = null;
			return $string;
			break;
		}
		$string = "<p>Datorn kastar tärningen: </p>";
		$string .= $this->players[$this->currentPlayer]->Roll();
		if($this->numPlayers > 1 && ($this->players[$this->currentPlayer]->GetRoll() == 1)) {
			$string .= "<p>Nu är det spelarens tur att kasta tärningen.</p>";
		}
		else {
			$playAgain = rand(0,2);
			while($playAgain == 1) { //If random get 1 roll again
			$string .= "<p>Datorn slår igen</p>";
			$string .= $this->players[$this->currentPlayer]->Roll();
				if($this->numPlayers > 1 && ($this->players[$this->currentPlayer]->GetRoll() == 1)) {
					$string .= "<p>Nu är det spelarens tur att kasta tärningen.</p>";
					return $string;
					break;
				} else {
					$playAgain = rand(0,2);
				}	
			}
			$string .= "<p>Datorn stannar, det är spelarens tur att kasta tärningen</p><br />";
			$string .= $this->Stop();
		}
		return $string;
	}
	
	
	/**
	 * Stop the round and save points.
	 *@return string with message including you total points and if you won.
	 */
	public function Stop() {
		$sumTotal = $this->players[$this->currentPlayer]->Calculate();
		$string = $this->Won($sumTotal);
		if($this->currentPlayer == 0 && $this->computerPlayer == 1) {
			$this->currentPlayer = 1;
			$string .= $this->ComputerRoll();
		}
		return $string;
	}
	
	/**
	 * Decide if you have won.
	 *@param int the total points you have accumulated
	 *@return string with result
	 */
	public function Won($sumTotal) {
		$this->playerName = 1;
		$who = $this->currentPlayer + 1;
		if($sumTotal >= 100) {
			$this->didSomebodyWin = 1;
			$this->GetPlayersTotalScore();
			$string = "<p>GRATTIS spelare {$who} har vunnit!</p>";
			$string .= "<hr />";
			$string .= "<p>Gällande ställning: <br />";
			for ($i=0; $i < $this->numPlayers; $i++) {
				$this->playerName += $i;
				$string .= "Spelare {$this->playerName}: {$this->playersTotalScore[$i]} poäng <br />";
			}
			$string .= "</p>";
		} else {
			$string = "<p>Spelare {$who}'s totala poäng är {$sumTotal}</p>";
			$this->GetPlayersTotalScore();
			$string .= "<hr />";
			$string .= "<p>Gällande ställning: <br />";
			for ($i=0; $i < $this->numPlayers; $i++) {
				$this->playerName += $i;
				$string .= "Spelare {$this->playerName}: {$this->playersTotalScore[$i]} poäng <br />";
			}
			$string .= "</p>";
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