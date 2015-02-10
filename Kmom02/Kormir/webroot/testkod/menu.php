<?php
// Include the essential config-file which also creates the $kormir variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Kormir container.
$kormir['title'] = "Meny";


// Function to set a menu item to selected if it is the selected page 
// This is a callback function
// @param array with menulinks
function modifyNavbar($items) {
  $ref = isset($_GET['p']) && isset($items[$_GET['p']]) ? $_GET['p'] : null;
  if($ref) {
    $items[$ref]['class'] .= 'selected'; 
  }
  return $items;
}


//Array with menu items
$menu = array(
  'callback' => 'modifyNavbar',
  'items' => array(
    'me'  => array('text'=>'Me',  'url'=>'?p=me', 'class'=>null),
    'report'  => array('text'=>'Report',  'url'=>'?p=report', 'class'=>null),
    'source' => array('text'=>'Source', 'url'=>'?p=source', 'class'=>null),
  ),
);


/*************************************
 * A class dedicated to create menues. 
 *
 * @param array with menulinks
 * @param css-style class
 * @return HTML code to show the menu.
 */

class CNavigation {
  public static function GenerateMenu($menu, $class) {
    if(isset($menu['callback'])) {
		// Calls the callback given by the first parameter and passes the remaining parameters as arguments.
		$items = call_user_func($menu['callback'], $menu['items']);
    }
    $html = "<nav class='$class'>\n";
    foreach($items as $item) {
      $html .= "<a href='{$item['url']}' class='{$item['class']}'>{$item['text']}</a>\n";
    }
    $html .= "</nav>\n";
    return $html;
  }
 };

$kormir['main'] = CNavigation::GenerateMenu($menu, "navbar");

// Finally, leave it all to the rendering phase of Kormir.
include(KORMIR_THEME_PATH);