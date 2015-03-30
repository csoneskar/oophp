<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Movie Database";

//Connect to db
$db = new CDatabase($kormir['database']);
 
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
	array("id" => "movie_login", "heading" => "Logga in"),
	array("id" => "movie_logout", "heading" => "Logga ut"),
	array("id" => "status", "heading" => "Inloggad status"),
	array("id" => "create", "heading" => "Skapa"),
	
);


//Creating an object of the aside menu class
$aside = new CAside($p);
$menu = $aside->printMenu("Gör ett val", $vmenu);




// Get parameters for sorting
$title = isset($_GET['title']) ? $_GET['title'] : null;
$year1 = isset($_GET['year1']) && !empty($_GET['year1']) ? $_GET['year1'] : null;
$year2 = isset($_GET['year2']) && !empty($_GET['year2']) ? $_GET['year2'] : null;
$genre = isset($_GET['genre']) ? $_GET['genre'] : null;
$orderby = isset($_GET['orderby']) ? strtolower($_GET['orderby']) : 'id';
$order = isset($_GET['order']) ? strtolower($_GET['order']) : 'asc';
$hits = isset($_GET['hits']) ? $_GET['hits'] : 8;
$page = isset($_GET['page']) ? $_GET['page'] : 1;



$searchArray = array("title"=>$title, "year1"=>$year1, "year2"=>$year2, "genre"=>$genre);
$movie = new CMovieSearch($db, $searchArray, $hits, $page, $orderby, $order);
$htmlTable = new CHTMLTable($db, $movie, $orderby, $order, $hits, $page);
$hits = $htmlTable->GetHits();
$page = $htmlTable->GetPage();

 

$title = htmlentities($title);
$params = $movie->GetParams();
$paramsPrint = htmlentities(print_r($params, 1));
$sqlDebug = $db->Dump();
$table = $htmlTable->PrintTable();
$genres = $movie->GetGenres();
$sql = $movie->GetSQL();
$searchField = $movie->CreateSearchField();


/*
		<div class='dbtable'>
			<div class='rows'>{$hitsPerPage}</div>
			<table>
				{$table}
			</table>	
			<div class='pages'>{$navigatePage}</div>
		</div>
*/



$kormir['main'] = <<<EOD
<div id="content">   
	{$menu}
	<h1>FILMER</h1>
	<article class="right">
		{$searchField}
		<p>Resultatet från SQL-frågan:</p>
		<p><code>{$sql}</code></p>
		<p><pre>{$paramsPrint}</pre></p>
			{$table}
		<p>{$sqlDebug}</p>
	</article>
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
