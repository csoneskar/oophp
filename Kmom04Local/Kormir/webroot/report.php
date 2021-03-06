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
		<h2>oophp03: SQL och databasen MySQL</h2>
			<p>I och med att jag läst PT så har jag varit i kontakt med databaser tidigare och databasspråket är inget nytt. Jag har mest jobbat med MySQL, 
			men även stött på andra databaser som tex <a href="http://en.wikipedia.org/wiki/Apache_Cassandra">Apache Cassandra</a>. Jag har inte direkt programmerat med databaser utan mera använt dem för att hämta ut data.</p>
			<p>Jag har inte jobbat så mycket mot skolans driftmiljö ännu utan bara kopplat upp och sett att det fungerar. Övningsuppgifterna gjorde jag på min egen utvecklingsmiljö.
			Jag hade lite problem i början med rättigheten att få lägga till en databas, men med lite hjälp från kursforumet så löste det sig. Jag trivs bäst i att jobba i den text-baserade 
			klienten men saknar emellanåt översikten som man lättare får i en GUI baserad klient.</p>
			<p>Övningsuppgifterna gick bra och jag tycker de var lagom svåra. Dock är det lite svårt att veta om man gör rätt när det inte finns något 'facit', men jag fick iaf ut rätt resultat.
			Mot slutet så blev det lite krångligt att hålla reda på allt och med en helg i mellan så var det svårt att bilda sig en uppfattning om hur databasen såg ut och vilka vyer etc som fanns.
			Här hade det varit bra med en grafisk modell! Tror jag ska göra det framöver då det känns som det snabbt blir väldigt komplext.</p>
			<p>Utöver detta så ska det bli intressant att se vad nästa steg blir, hur man praktiskt binder ihop PHP med SQL. Det känns som att SQL och databaser är väldigt kraftfulla och det killar i fingrarna
			att göra något eget projekt, men tyvärr så finns inte tiden när jag jobbar heltid och samtidigt pluggar :(</p>
	</article>
	<article class="right">
		<h2>oophp02: Objektorienterad programmering i PHP</h2>
			<p>Grunderna för objektorientering och klasser, konstruktor, funktioner och arv kunde jag sedan tidigare studier av Java, men det
			behövdes verkligen en repetition av syntaxen och de finare detaljerna. Jag jobbade igenom oophp20-guiden grundligt och la lite extra tid här för att verkligen förstå allting.
			Även om jag kan grunderna och har en förståelse för konceptet med objektorientering så har jag svårt att avgöra när jag ska göra en ny klass, vilka klasser som behövs, 
			när det bör vara ett arv och vilka funktioner jag bör stoppa i vilken klass. Antagligen skulle en erfaren programmerare tycka att min kod är aningens rörig, 
			men här har jag iaf gjort ett seriöst försök att strukturera upp tankarna runt uppgiften.</p>
			<p>Jag tänkte först göra båda uppgifterna, men extrauppgifterna på tärningsspelet tog längre tid än jag trodde de skulle göra, så i slutet blev det bara tärningsuppgiften som är gjord.</p>
			<p>Jag utgick från övningsuppgifterna och byggde vidare på CDiceHand klassen. Mina klassfiler ligger under src i Kormir. Jag la till kontroller för om tärningskastet var en etta eller inte. Jag inser nu när jag skriver detta att
			de kontrollerna kanske borde placeras i min CGame klass som har hand om de andra spelreglerna och använda mig av get och set funktioner istället.</p>
			<p>Som sagt, jag har en CGame klass som har hand om själva spelet. Det är denna som anropas från mina sidkontroller. CGame har hand om spelarna, hur många de är och ifall någon av dem är en datorspelare.
			Jag har i uppgiften satt antalet spelare till max 2, men koden är byggd för att det ska gå att bygga ut relativt lätt då antalet spelare är en parameter in till CGame. Det som är hårdkodat är att
			när man spelar mot datorn så är det bara du och datorn som spelar.</p>
			<p>Jag har upptäckt att jag har väldigt lätt att snurra in mig i djupa if-else satser, kanske inget fel i sig men det blir knepigt att följa koden. Jag har försökt bryta upp detta lite genom att
			flytta funktionalitet till funktioner istället. Det blir iaf lite mer läsbart och inte samma kod på flera ställen. Överhuvudtaget så verkar jag krångla till saker för när jag sedan kommer på hur man ska göra så
			upplever jag det ibland som väldigt lätt (Varför tänkte jag inte så från början!). Jag hade bland annat stora problem att greppa hur jag skulle göra med dator-spelaren för att få den att automatisk 
			slå tärningen vid olika tillfällen, och provade allt från komplicerade if-else satser, arv och egen klass, men landade till slut på att bara lägga den funktionaliteten i en egen funktion.</p>
			<p>Jag gjorde en liten klass, ASide, för att hantera sidomenyn där man väljer vilket typ av spel man ska starta. Återanvändning från intro kursen i php, men objektiferad.</p>
			<p>Trots att det tagit många timmar och kanske inte är så bra strukturerat så är jag ändå väldigt nöjd över mitt program. Man kan ju faktiskt spela tärningspelet och funktionerna för extrauppgifterna är med.
			Man kan väl säga att jag har löst uppgiften på mitt egna vis :)</p>
	</article>
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