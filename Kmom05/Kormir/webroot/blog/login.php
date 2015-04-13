<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Logga in till databasen";
 
//Header in config file
 
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