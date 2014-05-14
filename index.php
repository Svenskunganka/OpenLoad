<?php
/***
 * Handler
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

if(!empty($_GET['template'])) {
	include 'src/config/template.list.php';
  $template = $_GET['template'];
	if(array_search($template, $templates) === false) {
		$template = array_rand($templates); // Someone specified a template that doesn't exist! So let's give them a random one!
	}
	include "templates/".$templates[$template]."/index.html";
}

?>