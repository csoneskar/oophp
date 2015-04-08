<?php
class CBlog {

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
	
	public function getPost($slug, $menu) {
		$res = $this->content->GetBlogContent($slug);
		
		$page = <<<EOD
		<div id="content">  
		{$menu}
EOD;
		if(isset($res[0])) {
		  foreach($res as $post) {
			$title  = htmlentities($post->title, null, 'UTF-8');
			$data   = $this->filter->doFilter(htmlentities($post->data, null, 'UTF-8'), $post->filter);
		 
			$page .= <<<EOD
			  <section>
			  <article  class="right">
			  <header>
			  <h1><a href='blog.php?slug={$post->slug}'>{$title}</a></h1>
			  </header>
			 
			  {$data}
			 
			  <footer>
			  </footer
			  </article>
			  </section>
EOD;
		  }
		$page .= "</div>";
		}
		else if($slug) {
		  $page = "Det fanns inte en sådan bloggpost.";
		}
		else {
		  $page = "Det fanns inga bloggposter.";
		}
		return $page;
	}
}