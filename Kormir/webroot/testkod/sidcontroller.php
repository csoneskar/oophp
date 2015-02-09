<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Hello World";
 
$kormir['header'] = <<<EOD
<img class='sitelogo' src='img/kormir.png' alt='Kormir Logo'/>
<span class='sitetitle'>Kormir webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;
 
$kormir['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur Kormir ser ut och fungerar.</p>
EOD;
 
$kormir['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Cecilia Soneskär | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);