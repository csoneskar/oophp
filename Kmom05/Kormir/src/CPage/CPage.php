<?php
class CPage {

	/**
	 * Memebers
	 */
	 private $filter = null;
	 private $content = null;

	
	/**
	 * Constructor 
	 *
	 * @param 
	 *
	 */
	public function __construct($dbParams) {
		//Create a Filter object for text filtering
		$this->filter = new CTextFilter();
		
		$this->content = new CContent($dbParams);
	}
	
	public function getPage($url, $menu) {
		$res = $this->content->GetPage($url);
		$post = $res[0];
		
		$acronym = isset($_SESSION['user']) ? $_SESSION['user']->acronym : null;
		$editLink = $acronym ? "<a href='edit.php?id={$post->id}'>Uppdatera sidan</a>" : null;
		
		// Sanitize content before using it.
		$title  = htmlentities($post->title, null, 'UTF-8');
		$data   = $this->filter->doFilter(htmlentities($post->data, null, 'UTF-8'), $post->filter);
		$page = <<<EOD
		<div id="content">   
			{$menu}
			<article class="right">
				<h1>{$title}</h1>
				{$data}
				{$editLink}
			</article>
		</div>
EOD;
		$pageArray = array("title" => $title, "page" => $page,);
		return $pageArray;
	}

}