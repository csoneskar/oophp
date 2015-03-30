<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Editera ett inlägg";
 
//Header in config file
 
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
$id     = isset($_POST['id'])    ? strip_tags($_POST['id']) : (isset($_GET['id']) ? strip_tags($_GET['id']) : null);
$title  = isset($_POST['title']) ? $_POST['title'] : null;
$slug   = isset($_POST['slug'])  ? strip_tags($_POST['slug'])  : null;
$url  = isset($_POST['url']) ? strip_tags($_POST['url']) : null;
$data  = isset($_POST['data']) ? $_POST['data'] : array();
$type  = isset($_POST['type']) ? strip_tags($_POST['type']) : null;
$filter  = isset($_POST['filter']) ? strip_tags($_POST['filter']) : null;
$published  = isset($_POST['published']) ? strip_tags($_POST['published']) : null;
$save   = isset($_POST['save'])  ? true : false;

//$user = new CUser($myKormir);
//$acronym =  $user->GetAcronym();
 
// Check that incoming parameters are valid
//isset($acronym) or die('Check: You must <a href="movie_login.php">login</a> to edit.');
is_numeric($id) or die('Check: Id must be numeric.');



// Check if form was submitted
if($save) {
	/*
	$sql = '
	UPDATE Content SET
	  title = ?,
	  slug 	= ?,
	  url 	= ?,
	  data 	= ?,
	  type 	= ?,
	  filter 	= ?,
	  published 	= ?,
	  updated 	= NOW()
	WHERE 
	  id = ?
	';
	$url = empty($url) ? null : $url;	//Check if URL is empty, if so set to NULL
	$params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
	$res = $db->ExecuteQuery($sql, $params);
	$db->SaveDebug();
	if($res) {
		$output = 'Informationen sparades.';
	}
	else {
		$output = 'Informationen sparades EJ.<br><pre>' . print_r($db->ErrorInfo(), 1) . '</pre>';
	}
	*/
	$params = array($title, $slug, $url, $data, $type, $filter, $published, $id);
	$output = $content->EditContent($params);
}


// Select information on the post 
/*
$sql = 'SELECT * FROM Content WHERE id = ?';
$params = array($id);
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);
 
if(isset($res[0])) {
  $post = $res[0];
}
else {
  die('Failed: There is no post with that id');
}
*/
$post = $content->GetContent($id);

$url    = htmlentities($post->url, null, 'UTF-8');
$type   = htmlentities($post->type, null, 'UTF-8');
$title  = htmlentities($post->title, null, 'UTF-8');
$data   = htmlentities($post->data, null, 'UTF-8');



$kormir['debug'] = $db->Dump();
 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Uppdatera innehåll</h1>
	<article class="right">
		<form method="post">
			<input type='hidden' name='id' value='{$id}'/>
			<p><label>Titel: </label><input type="text" name="title" value='{$title}'></p>
			<p><label>Slug: 	 </label><input type="text" name="slug" value='{$post->slug}'></p>
			<p><label>URL:  </label><input type="text" name="url" value='{$url}'></p>
			<p><label>Text:  </label><br><textarea name="data">{$data}</textarea></p>
			<p><label>Typ:  </label><input type="text" name="type" value='{$type}'></p>
			<p><label>Filter:  </label><input type="text" name="filter" value='{$post->filter}'></p>
			<p><label>Publiceringsdatum:  </label><input type="text" name="published" value='{$post->published}'></p>
			<p class="submit"><input type="submit" name="save" value="Uppdatera"><input type="reset" value="Återställ"></p>
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