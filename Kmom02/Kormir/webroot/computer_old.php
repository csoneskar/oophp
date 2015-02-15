<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Tärningspelet 100";
 
//Header in config file

$string = null; 
$numberOfPlayers = 2;
$computer = 1;		//Playing agains computer if set to 1
 
if(isset($_GET['destroy'])) {
	//Unset all of the session variables.
	$_SESSION = array();
	
	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
	  $params = session_get_cookie_params();
	  setcookie(session_name(), '', time() - 42000,
		  $params["path"], $params["domain"],
		  $params["secure"], $params["httponly"]
	  );	
	}

	//Finally, destroy the session.
	session_destroy();
	echo "Sessionen raderas, <a href='start.php'>Tillbaka till startsidan</a>";
	exit;
}

//Get the arguments from the query string
$roll1 = isset($_GET['roll1']) ? true : false;
$roll2 = isset($_GET['roll2']) ? true : false;
$init = isset($_GET['init']) ? true : false;
$stop = isset($_GET['stop']) ? true : false;

// Create the object or get it from the session
if (isset($_SESSION['game'])) {
	$game = $_SESSION['game'];
} else {
	$string = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
	$game = new CGame3($numberOfPlayers, $computer);	//Playing with one human player and one computer
	$_SESSION['game'] = $game;
}

//Roll the dices
if($roll1) {
	$string = $game->Roll(0);
	if ($string === -1) {		//Change player since 'human' rolled 1!
		$string = $game->Roll(1);
	}
		echo "Fortfarande i ettan";
}
else if($roll2) {
	echo "början av tvåan";
	$string = $game->Roll(1);
	echo "<p>{$string}</p>";
	if ($string === -1) {		//Datorn kastar en gång till!
		echo "Startar computerRoll";
		$string = $game->ComputerRoll(1);
	}
}
else if($init) {
	$string = $game->InitRound();
}
else if($stop) {
	$string = $game->Stop();
}


$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Spela träningsspelet 100 med en vän</h1>
	<article class="right">
		<p>Spelare 1 börjar med att kasta tärningen och fortsätter tills han vill spara sina poäng eller får en etta och förlorar alla poäng i denna rundan. Nästa gång så kastar spelare två sin tärning. Turas om så tills någon når 100 poäng.</p>
		<p>Ni kan påbörja en ny runda genom att välja 'Starta ny runda' eller gå tillbaka till starten genom att välja 'Avsluta spelet'.</p>
		<p><a href='?roll1'> Spelare 1 gör ett nytt kast </a>|<a href='?roll2'> Spelare 2 gör ett nytt kast </a>|<a href='?stop'> Stanna och spara poängen </a></p><p><a href='?init'>Starta en ny runda </a>| <a href='?destroy'> Avsluta spelet </a></p>
		<hr />
		<p><strong>{$string}</strong></p>
		
	</article>	  
</div>
EOD;



//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
?>
