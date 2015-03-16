<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Logga in till filmdatabasen";
 
//Header in config file


$output = "Du är fortfarande inloggad";
$myKormir = $kormir['database'];
$user = new CUser($myKormir);

// Logout the user
if(isset($_POST['logout'])) {
	$output = $user->logout();
  header('Location: status.php');
}


$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Logga ut</h1>
	<article class="right">
		<form method=post>
		  <fieldset>
			  <legend>Logga ut</legend>
			  <p><input type='submit' name='logout' value='Logout'/></p>
			  <p><a href='movie_login.php'>Login</a></p>
			  <output><b>{$output}</b></output>
		  </fieldset>
		</form>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);