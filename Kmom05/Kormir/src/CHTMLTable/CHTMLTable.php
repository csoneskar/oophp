<?php
/**
 * Used to represent database as HTML table.
 * Responsible for sorting and pagination.
 */
class CHTMLTable {
	
	/**
	 * Memebers
	 */
	// Parameters for pagination
	private $hits; //How many rows to display per page
	private $page; //Which is the current page to display, use this to calculate the offset value
	private $max; //Max pages in the table: SELECT COUNT(id) AS rows FROM VMovie
	private $min; //Startpage, usually 0 or 1, what you feel is convienient
	private $orderby;
	private $order;
	private $db;	//Connection to database
	private $movie;	//Instance of movieSearch class
	
	
	public function __construct(CDatabase $db, $movie, $orderby, $order, $hits, $page) {
		$this->hits = $hits;
		$this->page = $page;
		$this->orderby = $orderby;
		$this->order = $order;
		
		// Check that parameters are valid
		in_array($orderby, array('id', 'title', 'year')) or die('Check: Not valid column.');
		in_array($order, array('asc', 'desc')) or die('Check: Not valid sort order');
		is_numeric($hits) or die('Check: Hits must be numeric.');
		is_numeric($page) or die('Check: Page must be numeric.');
		
		$this->db = $db;
		$this->movie = $movie;
		
		$this->max = $this->GetMax();
	}
	
	
	public function GetHits() {
		return $this->hits;
	}
	
	public function GetPage() {
		return $this->page;
	}
	
	/**
	 * Get max pages from table, for navigation
	 * @return int max number of pages
	 */
	public function GetMax() {
		$sql = "SELECT COUNT(id) AS rows FROM VMovie";
		$count = $this->db->ExecuteSelectQueryAndFetchAll($sql);
		$this->max = ceil($count[0]->rows / $this->hits);
		return $this->max;
	}
	
	
	/**
	 * Create links for hits per page.
	 *
	 * @param array $hits a list of hits-options to display.
	 * @return string as a link to this page.
	 */
	public function getHitsPerPage($hits) {
		$nav = "Träffar per sida: ";
		foreach($hits AS $val) {
			$nav .= "<a href='" . $this->db->getQueryString(array('hits' => $val)) . "'>$val</a>";
		}
		return $nav;
	}	
	

	/**
	 * Create navigation among pages
	 *
	 * @param integer $hits per page
	 * @param integer $page current page
	 * @param integer $max number of pages
	 * @param integer $min is the first page number, usually 0 or 1
	 * @return string as a link to this page
	 */
	function getPageNavigation($min=1) {
		
	  $nav  = ($this->page != $min) ? "<a href='" . $this->db->getQueryString(array('page' => $min)) . "'>&lt;&lt;</a> " : '&lt;&lt; ';
	  $nav .= ($this->page > $min) ? "<a href='" . $this->db->getQueryString(array('page' => ($this->page > $min ? $this->page - 1 : $min) )) . "'>&lt;</a> " : '&lt; ';
	  for($i=$min; $i<=$this->max; $i++) {
		if($this->page == $i) {
		  $nav .= "$i ";
		}
		else {
		  $nav .= "<a href='" . $this->db->getQueryString(array('page' => $i)) . "'>$i</a> ";
		}
	  }

	  $nav .= ($this->page < $this->max) ? "<a href='" . $this->db->getQueryString(array('page' => ($this->page < $this->max ? $this->page + 1 : $this->max) )) . "'>&gt;</a> " : '&gt; ';
	  $nav .= ($this->page != $this->max) ? "<a href='" . $this->db->getQueryString(array('page' => $this->max)) . "'>&gt;&gt;</a> " : '&gt;&gt; ';
	  return $nav;
	}
	
	
	/**
	 * Function to create links for sorting
	 *
	 * @param string $column the name of the database column to sort
	 * @return string with links to order by column
	 */ 
	 function orderby($column) {
		 //return "<span class='orderby'><a href='?orderby={$column}&order=asc'>&darr;</i></a><a href='?orderby={$column}&order=desc'>&uarr;</a></span>";
		 $nav  = "<a href='" . $this->db->getQueryString(array('orderby'=>$column, 'order'=>'asc')) . "'>&darr;</a>";
		 $nav .= "<a href='" . $this->db->getQueryString(array('orderby'=>$column, 'order'=>'desc')) . "'>&uarr;</a>";
		 return "<span class='orderby'>" . $nav . "</span>";
	 }
	

	/**
	 * 	Put results into a HTML-table
	 *	@return string HTML table representation
	 */
	public function PrintTable() {
		$res = $this->movie->GetSQLRes();
		$table = "<tr><th>Rad</th><th>Id " . $this->orderby('id') . "</th><th>Bild</th><th>Titel " . $this->orderby('title') . "</th><th>År " . $this->orderby('year') . "</th><th>Genre</th><th>Editera</th><th>Radera</th></tr>";	//Adds the headings
			foreach($res AS $key => $val) {
				//Adds the rows one by one	
				$table .= "<tr><td>{$key}</td><td>{$val->id}</td><td><img width='80' height='40' src='{$val->image}' alt='{$val->title}' /></td><td>{$val->title}</td><td>{$val->year}</td><td>{$val->genre}</td><td><a href='movie_edit.php?id={$val->id}'>Editera</a></td><td><a href='movie_delete.php?id={$val->id}'>Radera</a></td></tr>";
			}	
			
		$hitsPerPage = $this->getHitsPerPage(array(2, 4, 8));
		$navigatePage = $this->getPageNavigation();
			
		$string = "
			<div class='dbtable'>
				<div class='rows'>{$hitsPerPage}</div>
					<table>
						{$table}
					</table>	
				<div class='pages'>{$navigatePage}</div>
			</div>
		
		";	
			
			
			
		return $string;
	}
	
}