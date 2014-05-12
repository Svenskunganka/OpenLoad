<?php
/***
 * Configurations file. Edit it to your likings.
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/
	
/**
 * General Configuration
 */
$steam_api_key = "KEYGOESHERE"; // Get a Steam API Key here: http://steamcommunity.com/dev/apikey
$server_ip = "123.456.78.9"; // Your server IP-Address
$server_port = 27015; // Your server port

/**
 * MySQL Database configuration.
 * If your server has DarkRP or Pointshop installed USING MySQL over pdata/SQLite, you can use this to fetch players' DarkRP Money and/or Pointshop Points.
 * If you only use either Pointshop or DarkRP, you have to specify that in the configuration below.
 */
$db_data = false; // Enable MySQL support? If false, ignore everything under this line.
if($db_data) {
	$db_host = "localhost"; // The IP to the MySQL database.
	$db_user = "user"; // The user with SELECT permissions to the database.
	$db_pass = "pass"; // The users' password.
	$db_port = 3306; // If your MySQL server listens on a different port, change this. Default is 3306
	$db_database = "database"; // The name of your database.
	$db_types = array(
		"darkrp" => true, // true = enabled
		"pointshop" => true // false = disabled
		);
}
?>