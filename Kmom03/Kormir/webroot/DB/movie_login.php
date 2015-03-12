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
 
//Connect to db
$db = new CDatabase($kormir['database']);
 
 
// Check if user is authenticated.
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
 
if($acronym) {
  $output = "Du är inloggad som: $acronym ({$_SESSION['user']->name})";
}
else {
  $output = "Du är INTE inloggad.";
}

$resultatet = '<p>Inget skrivet</p>';

// Check if user and password is okey
if(isset($_POST['login'])) {
  $sql = "SELECT acronym, name FROM User WHERE acronym = ? AND password = md5(concat(?, salt))";
  $params=array($_POST['acronym'], $_POST['password']);
  $res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
  echo 'Hej' . $res;
  $resultatet = $db->Dump();
  if(isset($res[0])) {
    $_SESSION['user'] = $res[0];
  }
  header('Location: movie_login.php');
}

 
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
			  <p><a href='movie_logout.php'>Logout</a></p>
			  <output><b>{$output}</b></output>
		  </fieldset>
		</form>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);