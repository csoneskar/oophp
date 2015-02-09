<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Hämtar titeln från respektive sida via variabel -->
	<title><?php echo $title; ?></title>
	
	<!-- links to external stylesheets -->
	<?php if(isset($_SESSION['stylesheet'])): ?>
		<link rel="stylesheet" href="style/<?php echo $_SESSION['stylesheet']; ?>">        
	<?php else: ?>
		<link rel="stylesheet" href="style/mystyle.css" title="My general stylesheet">
		<link rel="alternate stylesheet" href="style/debug.css" title="Debug stylesheet">
	<?php endif; ?>
	
	<!-- some more fonts -->
	<link href='http://fonts.googleapis.com/css?family=Raleway:500' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
	
	<!-- display image as favicon -->
	<link rel="shortcut icon" href="img/favicon_dbwebb.png">
 
	<!-- Each page can set $pageStyle to create an internal stylesheet -->
	<?php if(isset($pageStyle)) : ?>
		<style type="text/css">
			<?php echo $pageStyle; ?>
		</style>
	<?php endif; ?>
 
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<!-- The body id helps with highlighting current menu choice -->
<body<?php if(isset($pageId)) echo " id='$pageId' "; ?>>

<!-- Above header -->
<header id="above">
	
	<!-- Login länk -->
	<?php echo userLoginMenu(); ?>

	<!-- Above länkar -->
	<nav class="related">
		<a href="../kmom01/me.php">Kmom01</a>
		<a href="../kmom02/me.php">Kmom02</a>
		<a href="../kmom03/me.php">Kmom03</a>
		<a href="../kmom04/me.php">Kmom04</a>
		<a href="../kmom05/me.php">Kmom05</a>
		<a href="../kmom06/me.php">Kmom06</a>
	</nav>

</header>

<!-- Header -->
<header id="top">
    <img src="img/logo.png" alt="htmlphp logo" width="300" height="70">

<!-- Main Navigeringsmeny -->
	<nav class="navmenu">
		<a id="me-"		href="me.php">Me</a>
		<a id="report-"	href="report.php">Redovisning</a>
		<a id="test-"	href="test.php">Testprogram</a>
		<a id="style-"	href="style.php">Style me</a>
		<a id="blokket-"	href="blokket.php">Blokket</a>
		<a id="blokket2-"	href="blokket2.php">Blokket 2</a>
		<a id="source-"	href="viewsource.php">Källkod</a>
	</nav>
</header>