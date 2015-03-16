<?php
/**
 * Theme related functions. 
 *
 */
 
/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null wether the favicon is defined or not.
 */
function get_title($title) {
  global $kormir;
  return $title . (isset($kormir['title_append']) ? $kormir['title_append'] : null);
}

/**
 * Function to lookup if the give url is the same as the current url
 *
 * @return true if the urls is the same
 */
function modifyNavbar($url) {
	if(basename(getCurrentUrl()) == $url) {
		return true; 
	}
}

/**
 * Create a navigation bar / menu for the site.
 *
 * @param array $menu for the navigation bar.
 * @param string $class wich class to attach to <nav>
 * @return string as the html for the menu.
 */
function generateMenu($menu, $class) {
    $html = "<nav class='$class'>\n";
    foreach($menu['items'] as $item) {
		$selected = call_user_func($menu['callback'], $item['url']) ? "class='selected' " : null;
		$html .= "<a href='{$item['url']}' {$selected}>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
  } 