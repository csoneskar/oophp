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


/*
Jag gör de tester jag kan göra och via strip_tags() rensar jag bort om användaren försöker skicka in HTML-kod. 
Det finns olika taktiker att hantera formulärvärden som skall sparas i databasen. 
Man kan spara dem exakt som användaren skriver in dem och sedan hantera med htmlentities() när de skrivs ut, 
eller så kan man göra som jag gör nu, att rensa bort eventuell skadlig kod innan man sparar i databasen. 
Båda två är rätt, det gäller bara att vara konsistent när man valt taktik.
*/

// Get parameters 
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? strip_tags($_POST['title']) : null;
$year   = isset($_POST['year'])  ? strip_tags($_POST['year'])  : null;
$image  = isset($_POST['image']) ? strip_tags($_POST['image']) : null;
$genre  = isset($_POST['genre']) ? $_POST['genre'] : array();
$delete   = isset($_POST['delete'])  ? true : false;
 
 
$user = new CUser($myKormir);
$acronym =  $user->GetAcronym();
 
// Check that incoming parameters are valid
isset($acronym) or die('Check: You must <a href="movie_login.php">login</a> to edit.');
is_numeric($id) or die('Check: Id must be numeric.');
is_array($genre) or die('Check: Genre must be array.');


// Check if form was submitted
$output = null;
if($delete) {
 
  $sql = 'DELETE FROM Movie2Genre WHERE idMovie = ?';
  $db->ExecuteQuery($sql, array($id));
  $db->SaveDebug("Det raderades " . $db->RowCount() . " rader från databasen.");
 
  $sql = 'DELETE FROM Movie WHERE id = ? LIMIT 1';
  $db->ExecuteQuery($sql, array($id));
  $db->SaveDebug("Det raderades " . $db->RowCount() . " rader från databasen.");
 
  header('Location: moviedb.php');
}


// Select information on the movie 
$sql = 'SELECT * FROM Movie WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
 
if(isset($res[0])) {
  $movie = $res[0];
}
else {
  die('Failed: There is no movie with that id');
}

 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Radera Film</h1>
	<article class="right">
		<form method="post">
			<input type='hidden' name='id' value='{$id}'/>
			<p><label>Vill du radera filmen {$movie->title}?</p>
			<p class="submit"><input type="submit" name="delete" value="Radera"></p>
		</form>
		<p>{$output}</p>
		<a href="connect.php">Visa alla</a>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);