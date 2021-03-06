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
$numberOfPlayers = 1;
$computerPlayer = 0;	//We are not playing against the computer
 
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
$init = isset($_GET['init']) ? true : false;
$stop = isset($_GET['stop']) ? true : false;

// Create the object or get it from the session
if (isset($_SESSION['game'])) {
	$game = $_SESSION['game'];
} else {
	$string = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
	$game = new CGame($numberOfPlayers, $computerPlayer);
	$_SESSION['game'] = $game;
}

//Roll the dices
if($roll1) {
	$string = $game->Roll(0);
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
		<p>Kasta träningen så många gånger du vill. Får du en etta så nollställs de poäng du spelat ihop i denna rundan. Du kan spara undan poäng genom att klicka på 'Spara poäng' närsom under spelets gång.</p>
		<p>Du kan påbörja en ny runda genom att välja 'Starta ny runda' eller gå tillbaka till starten genom att välja 'Avsluta spelet'.</p>
		<p><a href='?roll1'> Kasta tärningen </a>|<a href='?stop'> Spara poäng </a></p><p><a href='?init'>Starta en ny runda </a>| <a href='?destroy'> Avsluta spelet </a></p>
		<hr>
		{$string}
		
	</article>	  
</div>
EOD;



//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
?>
