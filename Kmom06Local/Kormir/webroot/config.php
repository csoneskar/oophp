<?php
/**
 * Config-file for Kormir. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Kormir paths.
 *
 */
define('KORMIR_INSTALL_PATH', __DIR__ . '/../');
define('KORMIR_THEME_PATH', KORMIR_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(KORMIR_INSTALL_PATH . '/src/bootstrap.php');
 
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();
 
 
/**
 * Create the Kormir variable.
 *
 */
$kormir = array();
 
 
/**
 * Site wide settings.
 *
 */
$kormir['lang']         = 'sv';
$kormir['title_append'] = ' | Kormir en webbtemplate';

$kormir['header'] = <<<EOD
EOD;

$kormir['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Cecilia Soneskär | <a href='https://github.com/csoneskar/oophp'>Projektet i GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;

$kormir['byline'] = <<<EOD
<footer class="byline">
    <p>Cecilia är en hundtokig fotograf med många projekt, i trädgården, framför datorn och i arbetslivet. Just nu försöker hon ro i land tre högskolekurser på distans samtidigt som hon jobbar heltid.
	Inte alltid helt lätt att få tiden att räcka till.</p>
</footer>
EOD;

/**
 * Theme related settings.
 *
 */
// $kormir['stylesheet'] = 'css/style.css';		// Use if only one style is needed
$kormir['stylesheets']	= array('css/mystyle.css', 'css/diceCss.css', 'css/gallery.css', 'css/figure.css', 'css/breadcrumb.css');			// Add more styles if needed
$kormir['favicon']    	= 'favicon.ico';


/**
 * The menu
 *
 */
//$kormir['menu'] = null; // To skip the menu
$kormir['menu'] 
= array(
		'callback' => 'modifyNavbar',
		'items' => array(
					'me'  => array('text'=>'Me',  'url'=>'me.php', 'class'=>null),
					'report'  => array('text'=>'Report',  'url'=>'report.php', 'class'=>null),
					'roll' => array('text'=>'Roll dice', 'url'=>'start.php', 'class'=>null),
					'movie' => array('text'=>'Movie Database', 'url'=>'DB/moviedb.php', 'class'=>null),
					'blog' => array('text'=>'Blog Database', 'url'=>'blog/view.php', 'class'=>null),
					'source' => array('text'=>'Source', 'url'=>'source.php', 'class'=>null),
		),
	);


/**
 * Settings for JavaScript.
 *
 */
$kormir['modernizr'] = 'js/modernizr.js';

//$kormir['jquery'] = '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js';
$kormir['jquery'] = null; // To disable jQuery

$kormir['javascript_include'] = array();
//$kormir['javascript_include'] = array('js/slideshow.js'); // To add extra javascript files


/**
 * Google analytics.
 *
 */
//$kormir['google_analytics'] = 'UA-22093351-1'; // Change to your own ID.
$kormir['google_analytics'] = null;		// Google analytics turned off