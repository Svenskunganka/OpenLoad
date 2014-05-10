<?php
/***
 * OpenLoad's Class.
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

class OpenLoad {

	/**
	 * Points to the Mysqli connection
	 *
	 * @var $mysqli Object
	 */
	protected $mysqli;

	/**
	 * Points to the Source Query object
	 *
	 * @var $sq Object
	 */
	private $sq;

	/**
	 * Identification variables.
	 *
	 * @var $communityid Integer
	 * @var $uniqueid Integer
	 * @var $steamid String Public
	 */
	private $communityid;
	private $uniqueid;
	public $steamid;

	/**
	 * Hold the Steam API key.
	 *
	 * @var $steam_api_key String
	 */
	private $steam_api_key;

	/**
	 * Holds information on what types of MySQL data to fetch.
	 *
	 * @var $methods Array
	 */
	private $methods;

	/**
	 * Holds the map name.
	 *
	 * @var $mapname String
	 */
	private $mapname;

	/**
	 * Simple OOP
	 *
	 * @param $db_data Boolean
	 * @param $mysqli Object / Boolean
	 */
	public function __construct($sq, $communityid, $steam_api_key, $mapname, $mysqli = false, $methods = false) {
		if($mysqli) { $this->mysqli = $mysqli; }
		if($methods) { $this->methods = $methods; }
		$this->sq = $sq;
		$this->steam_api_key = $steam_api_key;
		$this->communityid = $communityid;
		$this->mapname = $mapname;
	}

	/**
	 * Structures the entire result set and returns it as an array.
	 *
	 * @return $array Array
	 */
	public function make() {
		$array = array();
		$this->make_ids();
		$array['steamid'] = $this->steamid;
		$array['players'] = $this->get_cur_srv_ply();
		$res = $this->fetch_steam_data();
		$array['playername'] = $res['playername'];
		$array['avatar'] = $res['avatar'];
		$array['mapname'] = $this->mapname;
		$array['mapimage'] = $this->get_map_icon();
		if($this->mysqli) {
			foreach($this->methods as $method => $bool) {
				if($bool) { $array[$method] = $this->fetch_wallet($method); }
			}
		}
		return $array;
	}

	/**
	 * Fetches the servers current amount of players.
	 *
	 * @return Integer
	 */
	public function get_cur_srv_ply() {
		return count($this->sq->GetPlayers());
	}

	/**
	 * Calculates SteamID and UniqueID out of CommunityID.
	 */
	public function make_ids() {
		$authserver = bcsub( $this->communityid, '76561197960265728' ) & 1;
  	$authid = ( bcsub( $this->communityid, '76561197960265728' ) - $authserver ) / 2;
  	$steamid = "STEAM_0:$authserver:$authid";
  	$this->steamid = $steamid;
  	$this->uniqueid = sprintf("%u\n", crc32("gm_".$this->steamid."_gm"));
	}

	/**
	 * Fetches player wallets for both DarkRP and Pointshop
	 *
	 * @param $type String
	 *
	 * @return $wallet Integer
	 * @return NULL (On failure)
	 */
	public function fetch_wallet($type) {
		if($type == "darkrp") {
			$query = "SELECT `wallet` FROM `darkrp_player` WHERE uid=? LIMIT 1";
		}
		elseif ($type == "pointshop") {
			$query = "SELECT `points` FROM `pointshop_data` WHERE uniqueid=? LIMIT 1";
		}
		$stmt = $this->mysqli->stmt_init();
		if($stmt->prepare($query)) {
			$stmt->bind_param("i", $this->uniqueid);
			$stmt->execute();
			$stmt->bind_result($wallet);
			$stmt->fetch();
			$stmt->close();
			return $wallet;
		}
		else {
			$stmt->close();
			return "NULL";
		}
	}

	/**
	 * Fetches player name and avatar through Steam API
	 *
	 * @return $array Array
	 */
	public function fetch_steam_data() {
		$apiurl = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$this->steam_api_key&steamids=$this->communityid";
		$res = json_decode(file_get_contents($apiurl), true);
		$array['playername'] = $res["response"]["players"][0]["personaname"];
  	$array['avatar'] = $res["response"]["players"][0]["avatarfull"];
  	return $array;
	}

	/**
	 * Gets a map icon from GameTrackers maps database.
	 *
	 * @return $buildurl String
	 */
	public function get_map_icon() {
		$buildurl = "http://image.www.gametracker.com/images/maps/160x120/garrysmod/$this->mapname.jpg";
		if(file_get_contents($buildurl) === true) {
			return $buildurl;
		}
		else {
			return "img/unknown_map.jpg";
		}
	}
}

?>