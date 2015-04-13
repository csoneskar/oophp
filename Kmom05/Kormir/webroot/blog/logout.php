<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Logga ut";
 

// Check if the url contains a querystring with a page-part.
$p = null;
if(isset($_GET["p"])) 
{
	$p = $_GET["p"];
}

//Telling what links to put in menu
$vmenu = array (
	array("id" => "reset", "heading" => "Nollställ DB"),
	array("id" => "view", "heading" => "Visa alla"),
	array("id" => "login", "heading" => "Logga in"),
	array("id" => "logout", "heading" => "Logga ut"),
	array("id" => "status", "heading" => "Inloggad status"),
	array("id" => "create", "heading" => "Skapa"),
	
);


//Creating an object of the aside menu class
$aside = new CAside($p);
$menu = $aside->printMenu("Gör ett val", $vmenu); 
 


$myKormir = $kormir['database'];
$user = new CUser($myKormir);

$user->IsAuthenticated();
$output = $user->GetOutput();

// Logout the user
if(isset($_POST['logout'])) {
	$output = $user->logout();
  header('Location: view.php');
}


$kormir['main'] = <<<EOD
<div id="content">   
	{$menu}
	<h1>Logga ut</h1>
	<article class="right">
		<form method=post>
		  <fieldset>
			  <legend>Logga ut</legend>
			  <p><input type='submit' name='logout' value='Logout'/></p>
			  <p><a href='login.php'>Login</a></p>
			  <output><b>{$output}</b></output>
		  </fieldset>
		</form>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);