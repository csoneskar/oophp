<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Vem är inloggad?";
 
//Header in config file
 
$myKormir = $kormir['database'];
$user = new CUser($myKormir);
 
$user->IsAuthenticated();
$output = $user->GetOutput();
 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Status</h1>
	<article class="right">
		<form method=post>
		  <fieldset>
			  <output><b>{$output}</b></output>
			  <p><a href="view.php">Tillbaka</a></p>
		  </fieldset>
		</form>
	</article>	  
</div>
EOD;
 

// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);