<?php







public function Search {
	
	
}



public function CreateSearchField {
	
	$string = <<<EOD
		<form>
		  <fieldset>
			  <legend>Sök</legend>
			  <input type=hidden name=genre value='{$genre}'/>
			  <input type=hidden name=hits value='{$hits}'/>
			  <input type=hidden name=page value='1'/>
			  <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$title}'/></label></p>
			  <p><label>Välj genre:</label> {$genres}</p>
			  <p><label>Skapad mellan åren: 
				  <input type='text' name='year1' value='{$year1}'/></label>
				  - 
				  <label><input type='text' name='year2' value='{$year2}'/></label>
				
			  </p>
			  <p><input type='submit' name='submit' value='Sök'/></p>
			  <p><a href='?'>Visa alla</a></p>
		  </fieldset>
		</form>
EDO;
	
	return $string;
}