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
	array("id" => "create", "heading" => "Skapa"),
	array("id" => "movie_logout", "heading" => "Logga ut"),
);


//Creating an object of the aside menu class
$aside = new CAside($p);
$menu = $aside->printMenu("Gör ett val", $vmenu);

// Parameters for pagination
$hits; //How many rows to display per page
$page; //Which is the current page to display, use this to calculate the offset value
$max; //Max pages in the table: SELECT COUNT(id) AS rows FROM VMovie
$min; //Startpage, usually 0 or 1, what you feel is convienient


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
$movie = new CMovieSearch($searchArray);

// Check that parameters are valid
in_array($orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
in_array($order, array('asc', 'desc')) or die('Check: Not valid sort order');
is_numeric($hits) or die('Check: Hits must be numeric.');
is_numeric($page) or die('Check: Page must be numeric.');

// Get max pages from table, for navigation
$sql = "SELECT COUNT(id) AS rows FROM VMovie";
$count = $db->ExecuteSelectQueryAndFetchAll($sql);

// Get maximal pages
$max = ceil($count[0]->rows / $hits); 


// Get all genres that are active
$sql = '
  SELECT DISTINCT G.name
  FROM Genre AS G
    INNER JOIN Movie2Genre AS M2G
      ON G.id = M2G.idGenre
';
$res = $db->ExecuteSelectQueryAndFetchAll($sql);

$genres = null;
foreach($res as $val) {
	if($val->name == $genre) {
		$genres .= "$val->name";
	}
	else {
		$genres .= "<a href='" . getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
	}
}


// Prepare the query based on incoming arguments
$sqlOrig = '
  SELECT 
    M.*,
    GROUP_CONCAT(G.name) AS genre
  FROM Movie AS M
    LEFT OUTER JOIN Movie2Genre AS M2G
      ON M.id = M2G.idMovie
    INNER JOIN Genre AS G
      ON M2G.idGenre = G.id
';
$where    = null;
$groupby  = ' GROUP BY M.id';
$limit    = null;
$sort     = " ORDER BY $orderby $order";
$params   = array();

// Search for title
if($title) {
	$where .= ' AND title LIKE ?';
	//$sql = "SELECT * FROM VMovie WHERE title LIKE ?;";
	//$params = array($title,); 
	$params[] = $title;
} 
//Search for year
/*elseif($year1 && $year2) {
  $sql = "SELECT * FROM VMovie WHERE year >= ? AND year <= ?;";
  $params = array(
    $year1,
    $year2,
  );  
} */
if($year1) {
	//$sql = "SELECT * FROM VMovie WHERE year >= ?;";
	//$params = array($year1,);  
	$where .= ' AND year >= ?';
	$params[] = $year1;
} 
if($year2) {
	//$sql = "SELECT * FROM VMovie WHERE year <= ?;";
	//$params = array($year2,); 
	$where .= ' AND year <= ?';
	$params[] = $year2;
} 

//Search by genre
if($genre) {
/*  $sql = '
    SELECT 
      M.*,
      G.name AS genre
    FROM Movie AS M
      LEFT OUTER JOIN Movie2Genre AS M2G
        ON M.id = M2G.idMovie
      INNER JOIN Genre AS G
        ON M2G.idGenre = G.id
    WHERE G.name = ?
    ;
  ';
  $params = array(
    $genre,
  );  */
	$where .= ' AND G.name = ?';
	$params[] = $genre;
}

//Pagination
if($hits && $page) {
	$limit = " LIMIT $hits OFFSET " . (($page -1) * $hits);
}


//Complete the sql statement
$where = $where ? " WHERE 1 {$where}" : null;
$sql = $sqlOrig . $where . $groupby . $sort . $limit;
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);



/*
else {
	// prepare SQL to show all
	// Do SELECT from a table with prepared statement
	$sql = "SELECT * FROM VMovie ORDER BY $orderby $order LIMIT $hits OFFSET " . (($page -1)*$hits);
	$params = null;
}
$res = $db->ExecuteSelectQueryAndFetchAll($sql, $params);		//Fetch the result
*/

/**
 * Use the current querystring as base, modify it according to $options and return the modified querystring.
 *
 * @param array $options to set/change
 * @param string $prepend this to the resulting query string
 * @return string with an updated querystring
 */
function getQueryString($options, $prepend='?') {
	// parse the query string into array
	$query = array();
	parse_str($_SERVER['QUERY_STRING'], $query);
	
	//modify the existing query string with new options
	$query = array_merge($query, $options);
	
	//return the modified querystring
	return $prepend . http_build_query($query);
}

/**
 * Create links for hits per page.
 *
 * @param array $hits a list of hits-options to display.
 * @return string as a link to this page.
 */
function getHitsPerPage($hits) {
	$nav = "Träffar per sida: ";
	foreach($hits AS $val) {
		$nav .= "<a href='" . getQueryString(array('hits' => $val)) . "'>$val</a>";
	}
	return $nav;
}	

/**
 * Create navigation among pages
 *
 * @param integer $hits per page
 * @param integer $page current page
 * @param integer $max number of pages
 * @param integer $min is the first page number, usually 0 or 1
 * @return string as a link to this page
 */
function getPageNavigation ($hits, $page, $max, $min=1) {
	$nav  = "<a href='" . getQueryString(array('page' => $min)) . "'>&lt;&lt;</a>";
	$nav .= "<a href='" . getQueryString(array('page' => ($page > $min ? $page - 1 : $min) )) . "'>&lt;</a>";
	
	for($i=$min; $i<=$max; $i++) {
		$nav .= "<a href='" . getQueryString(array('page' => $i)) . "'>$i</a>";
	}
	
	$nav .= "<a href='" . getQueryString(array('page' => ($page < $max ? $page + 1 : $max) )) . "'>&gt;</a>";
	$nav .= "<a href='" . getQueryString(array('page' => $max)) . "'>&gt;&gt;</a>";
	return $nav;
}	

/**
 * Function to create links for sorting
 *
 * @param string $column the name of the database column to sort
 * @return string with links to order by column
 */ 
 function orderby($column) {
	 return "<span class='orderby'><a href='?orderby={$column}&order=asc'>&darr;</i></a><a href='?orderby={$column}&order=desc'>&uarr;</a></span>";
 }


// Put results into a HTML-table
$tr = "<tr><th>Rad</th><th>Id " . orderby('id') . "</th><th>Bild</th><th>Titel " . orderby('title') . "</th><th>År " . orderby('year') . "</th><th>Genre</th><th>Editera</th><th>Radera</th></tr>";	//Adds the headings
foreach($res AS $key => $val) {
	//Adds the rows one by one	
	$tr .= "<tr><td>{$key}</td><td>{$val->id}</td><td><img width='80' height='40' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->year}</td><td>{$val->genre}</td><td><a href='movie_edit.php?id={$val->id}'>Editera</a></td><td><a href='movie_delete.php?id={$val->id}'>Radera</a></td></tr>";
	}

$title = htmlentities($title);
$paramsPrint = htmlentities(print_r($params, 1));
$hitsPerPage = getHitsPerPage(array(2, 4, 8));
$navigatePage = getPageNavigation($hits, $page, $max);
$sqlDebug = $db->Dump();

/*
		<form>
			<fieldset>
				<legend>Sök</legend>
				<p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$title}'/></label></p>
				<p><label>Skapad mellan åren: 
					<input type='text' name='year1' value='{$year1}'/>
					- 
					<input type='text' name='year2' value='{$year2}'/>
				</label></p>
				<p><input type='submit' name='submit' value='Sök'/></p>
				<p><label>Välj genre:</label><br/>{$genres}</p>
				<p><a href='?'>Visa alla</a></p>
			</fieldset>
		</form>
*/


$kormir['main'] = <<<EOD
<div id="content">   
	{$menu}
	<h1>FILMER</h1>
	<article class="right">
		<form>
		  <fieldset>
			  <legend>Sök</legend>
			  <input type=hidden name=genre value='{$genre}'/>
			  <input type=hidden name=hits value='{$hits}'/>
			  <input type=hidden name=page value='1'/>
			  <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$title}'/></label></p>
			  <p><label>Välj genre:</label> {$genres}</p>
			  <p><label>Skapad mellan åren: 
				  <input type='text' name='year1' value='{$year1}'/></label>
				  - 
				  <label><input type='text' name='year2' value='{$year2}'/></label>
				
			  </p>
			  <p><input type='submit' name='submit' value='Sök'/></p>
			  <p><a href='?'>Visa alla</a></p>
		  </fieldset>
		</form>
		<p>Resultatet från SQL-frågan:</p>
		<p><code>{$sql}</code></p>
		<p><pre>{$paramsPrint}</pre></p>
		<div class='dbtable'>
			<div class='rows'>{$hitsPerPage}</div>
			<table>
				{$tr}
			</table>	
			<div class='pages'>{$navigatePage}</div>
			<p>$sqlDebug</p>
		</div>
	</article>
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
