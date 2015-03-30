<?php
/**
 * This is a Kormir pagecontroller.
 *
 */

 // Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php');

// Prepare the content
$markdown = <<<EOD

Detta är ett exempel på markdown
=================================

En länk till [Markdowns hemsida](http://daringfireball.net/projects/markdown/).

1. Ordered list 
2. Ordered list again

> This should be a blockquote.

EOD;

$both = <<<EOD

Detta är ett exempel på markdown 
=================================

En länk till http://daringfireball.net/projects/markdown/.

1. Ordered list 
2. Ordered list again

> This should be a blockquote.
EOD;

$html_bb = <<<EOD
<p>Detta är exempel på [b]BBCode[/b]
[url]http://sv.wikipedia.org[/url]</p>
EOD;

$linktext = <<<EOD
<p>Detta är exempel på makeclickable http://www.aftonbladet.se</p>
EOD;

$newlinetext = <<<EOD
<p>Detta är en rad \n Detta är andra raden \r Och här har vi tredje raden \n\r och så lite mer text.</p>
EOD;

$figure = 'FIGURE src="img/movie/pokemon.jpg" caption="Me" alt="Bild på mig" nolink="nolink"';

// Filter the content
$filter = new CTextFilter();
$html_bb = $filter->bbcode2html($html_bb);
$linktext = $filter->makeClickable($linktext);
$newlinetext = $filter->nl2br($newlinetext);
$markdown1 = $filter->markdown($markdown);
$markdown2 = $filter->doFilter($markdown, "markdown");
$both = $filter->doFilter($both, "markdown,link");
$figure = $filter->doFilter($figure, "figure");

// Do it and store it all in variables in the kormir container.
$kormir['title'] = "Filter text";
$kormir['main'] = $html_bb . $linktext . $newlinetext . $markdown1 . $markdown2 . $both . $figure;

// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);