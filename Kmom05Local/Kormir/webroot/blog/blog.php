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
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;

// Get content
$slugSql = $slug ? 'slug = ?' : '1';
$sql = "
SELECT *
FROM Content
WHERE
  type = 'post' AND
  $slugSql AND
  published <= NOW()
ORDER BY updated DESC
;
";
$res = $db->ExecuteSelectQueryAndFetchAll($sql, array($slug));


// Prepare content and store it all in variables in the Kormir container.
$kormir['title'] = "Bloggen";
$kormir['main'] = <<<EOD
	<div id="content">  
	{$menu}
EOD;
if(isset($res[0])) {
  foreach($res as $post) {
    $title  = htmlentities($post->title, null, 'UTF-8');
    $data   = $filter->doFilter(htmlentities($post->data, null, 'UTF-8'), $post->filter);
 
    $kormir['main'] .= <<<EOD
	  <section>
	  <article  class="right">
	  <header>
	  <h1><a href='blog.php?slug={$post->slug}'>{$title}</a></h1>
	  </header>
	 
	  {$data}
	 
	  <footer>
	  </footer
	  </article>
	  </section>
EOD;
  }
  $kormir['main'] .= "</div>";
}
else if($slug) {
  $kormir['main'] = "Det fanns inte en sådan bloggpost.";
}
else {
  $kormir['main'] = "Det fanns inga bloggposter.";
}
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
