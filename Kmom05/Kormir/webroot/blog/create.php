<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Editera ett inlägg";


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
 
 
//Connect to db
$db = new CDatabase($kormir['database']);
$dbParams = $kormir['database'];
$content = new CContent($dbParams);

$output = null;

/*
Jag gör de tester jag kan göra och via strip_tags() rensar jag bort om användaren försöker skicka in HTML-kod. 
Det finns olika taktiker att hantera formulärvärden som skall sparas i databasen. 
Man kan spara dem exakt som användaren skriver in dem och sedan hantera med htmlentities() när de skrivs ut, 
eller så kan man göra som jag gör nu, att rensa bort eventuell skadlig kod innan man sparar i databasen. 
Båda två är rätt, det gäller bara att vara konsistent när man valt taktik.
*/

// Get parameters 
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$url  = isset($_POST['url']) ? strip_tags($_POST['url']) : null;
$data  = isset($_POST['data']) ? $_POST['data'] : array();
$type  = isset($_POST['type']) ? strip_tags($_POST['type']) : null;
$filter  = isset($_POST['filter']) ? strip_tags($_POST['filter']) : null;
$published  = isset($_POST['published']) ? strip_tags($_POST['published']) : null;
$create   = isset($_POST['create'])  ? true : false;

$slug = slugify($title);

$user = new CUser($dbParams);
$acronym =  $user->GetAcronym();
 
// Check that incoming parameters are valid
isset($acronym) or die('Check: You must <a href="login.php">login</a> to edit.');


// Check if form was submitted
if($create) {
	$params = array($title, $slug, $url, $data, $type, $filter, $published);
	$output = $content->CreateContent($params);
}


$kormir['debug'] = $db->Dump();
 
$kormir['main'] = <<<EOD
<div id="content">  
	{$menu}
	<h1>Skapa ny</h1>
	<article class="right">
		<form method="post">
			<p><label>Titel: </label><input type="text" name="title" value=''></p>
			<p><label>URL:  </label><input type="text" name="url" value=''></p>
			<p><label>Text:  </label><br><textarea name="data"></textarea></p>
			<p><label>Typ: <select name="type">
				<option value = "page">Page</option>
				<option value = "post">Post</option>
			</select></p>
			<p><label>Filter: <br>
			<select multiple name="filter">
				<option value = "bbcode">bbcode2html</option>
				<option value = "link">makeClickable</option>
				<option value = "nl2br">nl2br</option>
				<option value = "markdown">markdown</option>
				<option value = "shortcode">shortcode</option>
				<option value = "figure">figure</option>
			</select></p>
			<p><label>Publiceringsdatum:  </label><input type="text" name="published" value=''><br>
			yyyy-mm-dd hh:mm:ss</p>
			<p class="submit"><input type="submit" name="create" value="Skapa ny"><input type="reset" value="Återställ"></p>
		</form>
		<a href="view.php">Visa alla</a>
		<p>$output</p>
		<div id="debug">
			{$kormir['debug']}
		</div>
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);