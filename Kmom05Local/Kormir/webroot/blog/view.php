<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Blog Database";
 
//Header in config file


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


/**
 * Create a link to the content, based on its type.
 *
 * @param object $content to link to.
 * @return string with url to display content.
 */
function getUrlToContent($content) {
  switch($content->type) {
    case 'page': return "page.php?url={$content->url}"; break;
    case 'post': return "blog.php?slug={$content->slug}"; break;
    default: return null; break;
  }
}



// Get all content
$sql = '
  SELECT *, (published <= NOW()) AS available
  FROM Content;
';
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

// Put results into a list
$items = null;
foreach($res AS $key => $val) {
  $items .= "<li>{$val->type} (" . (!$val->available ? 'inte ' : null) . "publicerad): " . htmlentities($val->title, null, 'UTF-8') . " (<a href='edit.php?id={$val->id}'>editera</a> <a href='" . getUrlToContent($val) . "'>visa</a>)</li>\n";
}

$kormir['debug'] = $db->Dump();

$kormir['main'] = <<<EOD
<div id="content">   
	{$menu}
	<h1>Visa allt innehåll</h1>
	<article class="right">
		<p>Här är en lista på allt innehåll i databasen.</p>
		<ul>
		{$items}
		</ul>
		<p><a href='blog.php'>Visa alla bloggposter.</a></p>
		<div id="debug">
			{$kormir['debug']}
		</div>
	</article>
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
