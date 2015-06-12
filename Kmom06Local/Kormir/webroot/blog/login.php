<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Logga in till databasen";
 
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
 
// Check if user is authenticated.
//$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
$password = null;
$acronym = null;
 

 // Check if user and password is okey
if(isset($_POST['login'])) {
  $password = $_POST['password'];
  $acronym = $_POST['acronym'];
  $user->Login($acronym, $password);
}


$user->IsAuthenticated();
$output = $user->GetOutput();
 
$kormir['main'] = <<<EOD
<div id="content">      
	{$menu}
	<h1>Logga in</h1>
	<article class="right">
		<form method=post>
		  <fieldset>
			  <legend>Login</legend>
			  <p><em>Du kan logga in med doe:doe eller admin:admin.</em></p>
			  <p><label>Användare: </label><input type="text" name="acronym" value="" placeholder="acronym"></p>
			  <p><label>Lösenord: </label><input type="password" name="password" value"" placeholder="Password"></p>
			  <p><input type='submit' name='login' value='Login'/></p>
			  <p><a href='logout.php'>Logout</a></p>
			  <p><a href='view.php'>Visa alla</a></p>
			  <output><b>{$output}</b></output>
		  </fieldset>
		</form>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);