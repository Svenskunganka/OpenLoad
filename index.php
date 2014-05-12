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
	$template = strtolower(substr($_GET['template'], 0, strpos($_GET['template'], "#"))); // We got template=template#steamid#map#ip, so let's filter #steamid#map#ip out.
	if(array_search($template, $templates) === false) {
		$template = array_rand($templates); // Someone specified a template that doesn't exist! So let's give them a random one!
	}
	include "templates/".$templates[$template]."/index.html";
}

?>