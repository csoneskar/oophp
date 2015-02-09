<?php 
/**
 * This is a Kormir pagecontroller.
 *
 */
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Redovisning";
 
//Header in config file
 
$kormir['main'] = <<<EOD
<div id="content">   
	<h1>Redovisning av kursmomenten</h1>
	<article class="right">
		<h2>oophp01: Programmering i PHP</h2>
			<p>Det var bra att gå igenom guiden för att komma igång med PHP eftersom det blev en trevlig upprepning på det jag lärt mig
			i HTML/PHP/CSS kursen. Det mest basic sitter nu, men det är bra att repetera och här fanns en bra sammanfattning och även 
			kurslitteraturen gav en upprepning.</p>
			<p>Utvecklingsmiljön är Windows, Chrome (och Firefox), Filezilla och numera även Putty.</p>
			<p>Det var knepigt att förstås texten om Anax. Jag fick läsa den ett par gånger innan jag förstod att det var
			ett ramverk, vad det skulle användas till och att det var gjort av MOS för oss studenter. Men nu har jag greppat de grundläggande
			tankarna bakom Anax och skapat min egen kopia - <strong>Kormir</strong> (gudinna av ordning och sanning i 
			<a href="http://www.guildwars2.com">Guild Wars</a>). Det är en exakt kopia av Anax, då jag inte vågat göra några ändringar. 
			Jag la även till javascript stödet i koden, för det känns som det kan behövas, om inte annat i senare kurser. Men det är för tillfället avstängt.</p>
			<p>Jag har kopierat min me-sida från Kmom kursen och lagt in den som en sidkontroller i Kormir. Jag kopierade även in mitt CSS.
			Däremot hade jag lite problem med hur jag ska göra med header och footer, som i HTML/PHP/CSS kursen låg i egna filer som inkluderades. Detta var ju
			smidigt och efter att ha tjuvkikat på MOS kod såg jag hur han använt i config-filen.</p>
			<p>En annan sak som gjorde mig förvirrad var hur MOS hade placerat sin uppgift utanför själva Anax foldern. Jag trodde vi hade ramverket för att samla koden där inne?
			Jag låter min kod ligga kvar så för tillfället, men det kanske visar sig att den måste flyttas sen, men då förstår jag inte riktigt vitsen med alla extra foldrar.
			De gör ju att det blir svårare att hitta vilken fil som innehåller vilken kod... men jag antar att det är en vanesak.</p>
			<p>Menyn höll jag på att krångla med ett bra tag, övningsexemplet gick lätt och jag förstod efter ett tag hur det var uppbyggt. Först så fastnade jag på
			callback funktionen som jag aldrig sett förut, emn lite letande i manualen och googlande förklarade hur den används. Men jag tror problemen har gett mig större
			insikt i hur både callback och multidimensionella arrayer fungerar, även om jag känner mig långt ifrån säker på dem.</p>
			<p>Dump funktionen är inlagd i bootstrap filen och användes för att felsöka meny arrayen. CSource är inlagt under src i Kormir.</p>
			<p>GIT och GITHub är installerat på de två datorner jag jobbar från samt på mitt studentkonto och jag är glad jag fick lära mig detta. Dels för att det används
			professionellt på många platser, tex Ericsson men också för att jag nu, äntligen, slipper kompiera filer till dropbox för att flytta dem mellan datorer.
			Ska ta och titta lite närmare på merge när behovet uppstår, men just nu njuter jag av att använda push och pull.</p>
			
	</article>
</div>

EOD;
 
//Footer in config file
 
 
// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);