<?php 
/**
 * This is a PHP skript to process images using PHP GD.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
//include(__DIR__.'/config.php'); 
include(__DIR__ . '/../../' . '/src/image/CImage.php');
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Bildbehandling";
 
 
//$file = null;
$image = new CImage();


$kormir['main'] = null;
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);