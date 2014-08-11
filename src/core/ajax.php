<?php
/***
 * AJAX file. This is where are the AJAX calls goes.
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

// Getting the required libraries and classes.
require '../config/config.inc.php';
require 'db.php';
require '../lib/SourceQuery/SourceQuery.class.php';
require 'openload.class.php';

if(!empty($_POST['args'])) {
	$args = json_decode($_POST['args'], 1);
	$communityid = $args['steamid'];
	$sid = (!empty($_GET['sid']) ? $_GET['sid'] : 1);
	$mapname = $args['mapname'];
	if(!empty($_POST['cache']) && $_POST['cache'] == "true") {
		$ol = new OpenLoad($sq, $communityid, $steam_api_key, $mapname);
		$ol->cache();
	}
	else {
		$sq = new SourceQuery();
		$sq->Connect($servers[$sid]["ip"], $servers[$sid]["port"]);
		if($db_data && $pdo) {
			$ol = new OpenLoad($sq, $communityid, $steam_api_key, $mapname, $pdo, $db_types);
			$ret = $ol->make();
			$pdo = null;
		}
		else {
			$ol = new OpenLoad($sq, $communityid, $steam_api_key, $mapname);
			$ret = $ol->make();
		}
		$sq->Disconnect();
		$ret['ip'] = $servers[$sid]["ip"].':'.$servers[$sid]["port"];
		echo json_encode($ret); // Output JSON data.
	}
}
?>