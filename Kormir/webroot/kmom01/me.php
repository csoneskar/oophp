<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Om Mig";
 
 //Header in config file
 
$kormir['main'] = <<<EOD
<div id="content">       
	<h1>Lite om mig själv</h1>
	<article class="right">
		<p>Hejsan!</p> 
		<p>Jag heter Cecilia Soneskär. 
		Jag läste Programvaruteknik 97-01.</p>
		<figure class="left top">
			<img src="../img/me.jpg" alt="Bild på Cecilia Soneskär">
			<figcaption>
				<p>Bild på Cecilia Soneskär</p>
			</figcaption>
		</figure>
		<p>Sedan 2001 har jag jobbat med test, projektledning och framförallt testledning i olika konstellationer. 
		Just nu jobbar jag heltid på Fujitsu men vill ha lite mer utmaning, så jag tänkte att jag kunde fräscha upp mina programmeringskunskaper, som inte varit använda sedan skolan, genom denhär kursen.
		En annan drivkraft för genomförandet av kursen är att jag har en dröm om att bygga ihop en avancerad, webbaserad databas över växter i min trädgård. Jag hoppas här kunna få tillräckliga med tekniska kunskaper för att ro det i land.
		Konstigt nog finns det inga färdiga (kommerciella eller open source) databaser för detta redan.</p>
		<p>Jag bor strax utanför Karlskrona och mina intressen är trädgård, hundar och fotografering.</p>
		{$kormir['byline']}
	</article>	  
</div>
EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);