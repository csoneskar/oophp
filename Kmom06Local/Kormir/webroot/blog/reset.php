<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 

$output = null;
$dbParams = $kormir['database'];
$content = new CContent($dbParams);

if(isset($_POST['restore']) || isset($_GET['restore'])) {
/*
  // Use on Unix/Unix/Mac
  //$cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";

  // Use on Windows, remove password if its empty
  //$cmd = "$mysql -h{$host} -u{$login} -p{$password} < $sql 2>&1";
  $cmd = "$mysql -h{$host} -u{$login} < $sql";

  $res = exec($cmd);
  $output = "<p>Databasen är återställd via kommandot<br/><code>{$cmd}</code></p><p>{$res}</p>";
*/  
  $output = $content->InitDB();
}


// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Återställ databasen till ursprungligt skick";

$kormir['main'] = <<<EOD
<div id="content">   
	<h1>{$kormir['title']}</h1>
	<article class="right">
		<form method=post>
		<input type=submit name=restore value='Återställ databasen'/>
		<output>
			{$output} <br>
			<a href = "view.php">Tillbaka</a>
		</output>
		</form>	
	</article>
</div>
EOD;

// Finally, leave it all to the rendering phase of Anax.
include(KORMIR_THEME_PATH);