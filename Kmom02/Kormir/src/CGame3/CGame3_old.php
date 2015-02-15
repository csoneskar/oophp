<?php
/**
 * A game.
 *
 */
class CGame3 {
	
	//private $hand;
	private $numPlayers;
	private $players;
	private $currentPlayer;
	private $playersTotalScore;
	private $playerName;
	private $computer;
	private $message;
	
	
	/**
	 * Constructor - Creates a new hand
	 * 
	 * @param int number of players, boolean computer
	 */
	public function __construct($numPlayers, $computer) {
		$this->numPlayers = $numPlayers;
		$this->currentPlayer = 1;
		$this->computer = $computer;
		$this->message = null;
		// Create the object or get it from the session
		if (isset($_SESSION['dicehand'])) {
			$i = 0;
			while($i <= $numPlayers) {
				$this->players[] = $_SESSION['dicehand'];
				$i++;
			}
		} else {
			$this->message = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
			if(($this->computer === 1)) {
				$this->players[0] = new CDiceHand(1);
				$this->players[1] = new CDiceHand(1); //Computer
			} else {
				for($i=0; $i < $numPlayers; $i++) {
					$this->players[] = new CDiceHand(1);
				}
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
		$this->currentPlayer = 1;
		$this->message = null;
		return "<p>Ett nytt spel har startats. Värdena är nollställda.</p>";
	}
	
	/**
	 * Roll a dice
	 *@return string with information about your roll.
	 */
	public function Roll($player) {
		if ($player > $this->numPlayers) {
			$this->message = "<p>Detta spelet är bara för en spelare</p>";
		}
		else {
			$this->currentPlayer = $player;
			$this->message = $this->players[$this->currentPlayer]->Roll();
			if($this->numPlayers > 1 && ($this->players[$this->currentPlayer]->GetRoll() == 1)) {	//The face of the roll is 1. Change turn
				$this->message .= "<p>Nu är det motståndarens tur att kasta tärningen.</p>";
				echo "{$this->currentPlayer} slog en nolla";
				if ($this->currentPlayer == 0 && $this->computer == 1) {		//Human rolled a '1', changing to computer
					echo "Chaning player";
					//$this->currentPlayer = 1;
					//$this->message .= $this->Roll($this->currentPlayer);
					return -1;
					break;
				} else if($this->currentPlayer == 1 && $this->computer == 1) {	//If it was the computer that rolled 1, stop.
					$this->message .= $this->Stop();
					$this->message .= "Nu är det spelarens tur";
				}
			}
			else {
				$this->message .= "<p>Det är spelare {$this->currentPlayer} tur igen.</p>";
				if ($this->currentPlayer == 1 && $this->computer == 1) {		//If computer player, save or roll again
					echo "breaking up 2";
					return $this->ComputerRoll($this->currentPlayer);
					//return -1;
					echo "efter return";
					break;
					/*if (rand(0,1) == 1) {
						$this->message .= "<p>Slår igen</p>";
						$this->message .= $this->Roll($this->currentPlayer);
					} else { //Detta skriv inte ut, men det exekveras
						$this->message .= "<p>Väljer att stanna</p>";
						$this->message .= $this->Stop();
					}*/
				}
			}
		}
		echo $this->currentPlayer;
		return $this->message;
		$this->message = null;
	}
	
	public function GetMessage() {
		return $this->message;
	}
	
	public function ComputerRoll($computer) {
		if (rand(0,1) == 1) {
			echo "<p>Datorn slår igen</p>";
			$this->message = "<p>Datorn slår igen</p>";
			$this->message .= $this->Roll($computer);
		} else { //Detta skrivs inte ut, men det exekveras
			$this->message = "<p>Väljer att stanna</p>";
			echo "<p>Väljer att stanna</p>";
			$this->message .= $this->Stop();
		}
		return $this->message;
	}
	
	/**
	 * Stop the round and save points.
	 *@return string with message including you total points and if you won.
	 */
	public function Stop() {
		$sumTotal = $this->players[$this->currentPlayer]->Calculate();
		$this->message = $this->Won($sumTotal);
		if($this->currentPlayer == 1) {		//The computer has chosen to stop
			$this->message .= "Datorn valde att stanna, det är din tur";
		}
		else {
			$this->Roll(1);
		}
		return $this->message;
	}
	
	/**
	 * Decide if you have won.
	 *@param int the total points you have accumulated
	 *@return string with result
	 */
	public function Won($sumTotal) {
		$this->playerName = 1;
		if($sumTotal >= 100) {
			$who = $this->currentPlayer + 1;
			$this->GetPlayersTotalScore();
			$this->message = "<p>GRATTIS spelare {$who} har vunnit!</p>";
			$this->message .= "<p>Gällande ställning: ";
			for ($i=0; $i < $this->numPlayers; $i++) {
				$this->playerName += $i;
				$this->message .= "Spelare {$this->playerName}: {$this->playersTotalScore[$i]} poäng</p>";
			}
		} else {
			$this->message = "<p>Din totala poäng är {$sumTotal}</p>";
			$this->GetPlayersTotalScore();
			$this->message .= "<p>Gällande ställning: ";
			for ($i=0; $i < $this->numPlayers; $i++) {
				$this->playerName += $i;
				$this->message .= "Spelare {$this->playerName}: {$this->playersTotalScore[$i]} poäng</p>";
			}
			$this->message .= "</p>";
		}
		return $this->message;
	}
	
	public function GetPlayersTotalScore() {
		$i = 0;
		while($i < $this->numPlayers) {
			$this->playersTotalScore[$i] = $this->players[$i]->GetGreaterTotal();
			$i++;
		}
	}
	
}