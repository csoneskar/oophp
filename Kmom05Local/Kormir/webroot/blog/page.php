<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
include(__DIR__.'/filter.php');


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
	array("id" => "movie_login", "heading" => "Logga in"),
	array("id" => "movie_logout", "heading" => "Logga ut"),
	array("id" => "status", "heading" => "Inloggad status"),
	array("id" => "create", "heading" => "Skapa"),
	
);


//Creating an object of the aside menu class
$aside = new CAside($p);
$menu = $aside->printMenu("Gör ett val", $vmenu);

//Connect to db
$db = new CDatabase($kormir['database']);

//Create a Filter object for text filtering
$filter = new CTextFilter();

//Get parameters
$url = isset($_GET['url']) ? $_GET['url'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

// Get content
$sql = "
SELECT *
FROM Content
WHERE
  type = 'page' AND
  url = ? AND
  published <= NOW();
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($url));

if(isset($res[0])) {
  $post = $res[0];
}
else {
  die('Misslyckades: det finns inget innehåll.');
}

// Sanitize content before using it.
$title  = htmlentities($post->title, null, 'UTF-8');
$data   = $filter->doFilter(htmlentities($post->data, null, 'UTF-8'), $post->filter);


// Prepare content and store it all in variables in the Kormir container.
$kormir['title'] = $title;
$kormir['debug'] = $db->Dump();

$editLink = $acronym ? "<a href='edit.php?id={$c->id}'>Uppdatera sidan</a>" : null;

$kormir['main'] = <<<EOD
<div id="content">   
	{$menu}
	<article class="right">
		<h1>{$title}</h1>
	{$data}
		{$editLink}
	</article>
</div>
EOD;
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
