<!-- Footer -->
<div id="footer">
	<hr>
	<footer id="bottom">
		<div id="verktyg">
			<h2>Verktyg</h2>
				<a href="http://validator.w3.org/check/referer">HTML5</a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS3</a>
				<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">Unicorn</a>
				<a href="http://validator.w3.org/i18n-checker/check?uri=<?php echo getCurrentUrl(); ?>" target="_blank">i18nChecker</a>
				<a href="http://validator.w3.org/checklink?uri=<?php echo getCurrentUrl(); ?>" target="_blank">LinkChecker</a>
		</div>
		<div id="manualer">
			<h2>Manualer</h2>
				<a href="http://www.w3.org/2009/cheatsheet/">Cheatsheet</a>
				<a href="http://dev.w3.org/html5/spec/">HTML5</a> 
				<a href="http://www.w3.org/TR/CSS2/">CSS2</a> 
				<a href="http://www.w3.org/Style/CSS/current-work#CSS3">CSS3</a> 
				<a href="http://php.net/manual/en/index.php">PHP</a> 
		</div>
		<div id="tid">
			<?php if(isset($pageTimeGeneration)) : ?>
				<p>Page generated in <?php echo round(microtime(true)-$pageTimeGeneration, 5); ?> seconds</p>
			<?php endif; ?>
		</div>
	</footer>
</div>  
</body>               
</html>
