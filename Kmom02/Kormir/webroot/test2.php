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
	echo "Sessionen raderas, <a href='?'>Starta om spelet</a>";
	exit;
}

//Get the arguments from the query string
$roll = isset($_GET['roll']) ? true : false;
$init = isset($_GET['init']) ? true : false;
$stop = isset($_GET['stop']) ? true : false;

// Create the object or get it from the session
if (isset($_SESSION['dicehand'])) {
	$hand = $_SESSION['dicehand'];
} else {
	$string = "<p><i>Objektet finns inte i sessionen, skapar nytt objekt och lagrar det i sessionen</i></p>";
	$hand = new CDiceHand(1);
	$_SESSION['dicehand'] = $hand;
}

//Roll the dices
if($roll) {
	$string = $hand->Roll();
}
else if($init) {
	$string = $hand->InitRound();
}
else if($stop) {
	$string = $hand->Stop();
}

$html = $hand->GetRollAsImageList();

$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Kasta en tärning och kom först till 100</h1>
	<article class="right">
		<p>Det gäller att samla ihop poäng för att komma först till 100. I varje omgång kastar en spelare tärning tills hon väljer att stanna och 
		spara poängen eller tills det dyker upp en 1:a och hon förlorar alla poäng som samlats in i rundan.</p>
		<p><a href='?init'>Starta en ny runda</a>.</p>
		<p><a href='?roll'>Gör ett nytt kast</a>.</p>
		<p><a href='?stop'>Stanna och spara poängen</a>.</p>
		<p><a href='?destroy'>Avsluta spelet (Förstör sessionen)</a>.</p>

		{$html}
		{$string}
		
	</article>	  
</div>
EOD;



//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
?>
