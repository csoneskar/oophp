<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Editera databas";
 
 //Header in config file
 
//Connect to db
$db = new CDatabase($kormir['database']);
$myKormir = $kormir['database'];

// Get parameters 
//$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : null;
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$create = isset($_POST['create'])  ? true : false;
 

$user = new CUser($myKormir);
$acronym =  $user->GetAcronym(); 
// Check that incoming parameters are valid
isset($acronym) or die('Check: You must login to edit.');

// Check if form was submitted
$output = null;
if($create) {
  $sql = 'INSERT INTO Movie (title) VALUES (?)';
  $db->ExecuteQuery($sql, array($title));
  $db->SaveDebug();
  header('Location: movie_edit.php?id=' . $db->LastInsertId());
  exit;
}

 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Lägg till en film</h1>
	<article class="right">
		<form method="post">
			<p><label>Titel: </label><input type="text" name="title" value=''></p>
			<p class="submit"><input type="submit" name="create" value="Skapa"><input type="reset" value="Återställ"></p>
		</form>
		<p>{$output}</p>
		<a href="connect.php">Visa alla</a>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);