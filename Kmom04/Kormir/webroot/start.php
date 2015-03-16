<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Tärningsspelet 100";
 
//Header in config file

$menu = null; 

// Check if the url contains a querystring with a page-part.
$p = null;
if(isset($_GET["p"])) 
{
	$p = $_GET["p"];
}

// is the page known?
$file = null;
switch ($p) {
	case "alone":
		$file 	= "test3.php";
	break;
	case "friend":
		$file 	= "test4.php";
	break;
	case "computer":
		$file 	= "test5.php";
	break;
}

//Telling what links to put in menu
$vmenu = array (
	array("id" => "alone", "heading" => "Spela själv"),
	array("id" => "friend", "heading" => "Spela med en vän"),
	array("id" => "computer", "heading" => "Spela mot datorn")
);


//Creating an object of the aside menu class
$aside = new CAside($p);
$menu = $aside->printMenu("Hur vill du spela?", $vmenu);


$kormir['main'] = <<<EOD
<div id="content">   
	
	{$menu}

	<h1>Kasta en tärning och kom först till 100</h1>
	<article class="right">
		<p>Det gäller att samla ihop poäng för att komma först till 100. I varje omgång kastar en spelare tärning tills hon väljer att stanna och 
		spara poängen eller tills det dyker upp en 1:a och hon förlorar alla poäng som samlats in i rundan.</p>
	</article>	  
</div>
EOD;



//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);
?>
