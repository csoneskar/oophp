<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Ta bort ett inlägg";
 
//Header in config file
 
//Connect to db
$db = new CDatabase($kormir['database']);
$dbParams = $kormir['database'];
$content = new CContent($dbParams);

$output = null;
$title = null;

/*
Jag gör de tester jag kan göra och via strip_tags() rensar jag bort om användaren försöker skicka in HTML-kod. 
Det finns olika taktiker att hantera formulärvärden som skall sparas i databasen. 
Man kan spara dem exakt som användaren skriver in dem och sedan hantera med htmlentities() när de skrivs ut, 
eller så kan man göra som jag gör nu, att rensa bort eventuell skadlig kod innan man sparar i databasen. 
Båda två är rätt, det gäller bara att vara konsistent när man valt taktik.
*/

// Get parameters 
$id     = isset($_GET['id']) ? strip_tags($_GET['id']) : null;
$delete   = isset($_POST['delete'])  ? true : false;

$user = new CUser($dbParams);
$acronym =  $user->GetAcronym();
 
// Check that incoming parameters are valid
isset($acronym) or die('Check: You must <a href="login.php">login</a> to edit.');
is_numeric($id) or die('Check: Id must be numeric.');



// Check if form was submitted
if($delete) {
	$params = array($id);
	$output = $content->DeleteContent($params);		//Printing the result if not able to remove.
}

$post = $content->GetContent($id);
$title  = htmlentities($post->title, null, 'UTF-8');

$kormir['debug'] = $db->Dump();
 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Ta bort innehåll</h1>
	<article class="right">
		<form method="post">
			<input type='hidden' name='id' value='{$id}'/>
			<p>Vill du ta bort följande innehåll?</p>
			<p><label>Titel: </label><input type="text" name="title" value='{$title}'></p>
			<p class="submit"><input type="submit" name="delete" value="Ta bort"></p>
		</form>
		<a href="view.php">Visa alla</a>
		<p>$output</p>
		<div id="debug">
			{$kormir['debug']}
		</div>
	</article>	  
</div>
EOD;
 

 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);