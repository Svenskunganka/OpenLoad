<?php
/***
 * Database connection file.
 * Do not edit unless you know what you're doing!
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

if($db_data) {
	$mysqli = new Mysqli($db_host, $db_user, $db_pass, $db_database, $db_port);
	if ($mysqli->connect_errno) {
		$errors[] = 'Could not connect to MySQL Database. Error ('.$mysqli->connect_errno.') '.$mysqli->connect_error;
		$mysqli = false; // Let's continiue without DB connection, but throw an error.
	}
}
?>