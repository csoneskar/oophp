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

//Get parameters
$url = isset($_GET['url']) ? $_GET['url'] : null;
//$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

$dbParams = $kormir['database'];
$page = new CPage($dbParams);
$pageArray = $page->GetPage($url, $menu);

// Prepare content and store it all in variables in the Kormir container.
$kormir['title'] = $pageArray["title"];
$kormir['debug'] = $db->Dump();

$kormir['main'] = $pageArray["page"];
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
