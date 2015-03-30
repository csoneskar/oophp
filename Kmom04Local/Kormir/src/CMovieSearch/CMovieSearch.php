<?php
/**
 * Used to search in database.
 *
 */
class CMovieSearch {
	
	/**
	 * Memebers
	 */
	private $title = null;
	private $year1 = null;
	private $year2 = null;
	private $genre = null;
	private $hits = null;
	private $page = null;
	private $orderby = null;
	private $order = null;
	private $db = null;
	private $sql = null;
	private $params = null;
	private $genres = null;
	
	
	public function __construct($db, $searchArray, $hits, $page, $orderby, $order) {
		$this->title = $searchArray['title'];
		$this->year1 = $searchArray['year1'];
		$this->year2 = $searchArray['year2'];
		$this->genre = $searchArray['genre'];
		
		$this->hits = $hits;
		$this->page = $page;
		$this->orderby = $orderby;
		$this->order = $order;
		$this->db = $db;
	}

	
	public function GetGenres() {
		// Get all genres that are active
		$sql = '
		  SELECT DISTINCT G.name
		  FROM Genre AS G
			INNER JOIN Movie2Genre AS M2G
			  ON G.id = M2G.idGenre
		';
		$res = $this->db->ExecuteSelectQueryAndFetchAll($sql);

		$this->genres = null;
		foreach($res as $val) {
			if($val->name == $this->genre) {
				$this->genres .= "$val->name";
			}
			else {
				$this->genres .= "<a href='" . $this->db->getQueryString(array('genre' => $val->name)) . "'>{$val->name}</a> ";
			}
		}
	}
	
	public function GetMovies() {
		$res = $this->db->ExecuteSelectQueryAndFetchAll("SELECT * FROM VMovie");
		return $res;
	}
	
	public function GetSQLRes() {
		// Prepare the query based on incoming arguments
		$sqlOrig = '
		  SELECT 
			M.*,
			GROUP_CONCAT(G.name) AS genre
		  FROM Movie AS M
			LEFT OUTER JOIN Movie2Genre AS M2G
			  ON M.id = M2G.idMovie
			INNER JOIN Genre AS G
			  ON M2G.idGenre = G.id
		';
		$where    = null;
		$groupby  = ' GROUP BY M.id';
		$limit    = null;
		$sort     = " ORDER BY $this->orderby $this->order";
		$this->params   = array();

		// Search for title
		if($this->title) {
			$where .= ' AND title LIKE ?';
			$this->params[] = $this->title;
		} 

		if($this->year1) { 
			$where .= ' AND year >= ?';
			$this->params[] = $this->year1;
		} 
		
		if($this->year2) {
			$where .= ' AND year <= ?';
			$this->params[] = $this->year2;
		} 

		//Search by genre
		if($this->genre) {
			$where .= ' AND G.name = ?';
			$this->params[] = $this->genre;
		}

		//Pagination
		if($this->hits && $this->page) {
			$limit = " LIMIT $this->hits OFFSET " . (($this->page -1) * $this->hits);
		}


		//Complete the sql statement
		$where = $where ? " WHERE 1 {$where}" : null;
		$this->sql = $sqlOrig . $where . $groupby . $sort . $limit;
		$res = $this->db->ExecuteSelectQueryAndFetchAll($this->sql, $this->params);
		
		return $res;
	}
	
	public function GetSQL() {
		return $this->sql;
	}
	
	public function GetParams() {
			return $this->params;
	}
	
	
	public function CreateSearchField() {
		$string = "
			<form>
			  <fieldset>
				  <legend>Sök</legend>
				  <input type=hidden name=genre value='{$this->genre}'/>
				  <input type=hidden name=hits value='{$this->hits}'/>
				  <input type=hidden name=page value='1'/>
				  <p><label>Titel (delsträng, använd % som *): <input type='search' name='title' value='{$this->title}'/></label></p>
				  <p><label>Välj genre:</label> {$this->genres}</p>
				  <p><label>Skapad mellan åren: 
					  <input type='text' name='year1' value='{$this->year1}'/></label>
					  - 
					  <label><input type='text' name='year2' value='{$this->year2}'/></label>
					
				  </p>
				  <p><input type='submit' name='submit' value='Sök'/></p>
				  <p><a href='?'>Visa alla</a></p>
			  </fieldset>
			</form> ";
		
		return $string;
	}

}