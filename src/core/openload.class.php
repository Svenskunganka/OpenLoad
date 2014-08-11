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
	 * Points to the PDO Oject
	 *
	 * @var $pdo Object
	 */
	protected $pdo;

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
	 * Holds the cache directory
	 */
	const CACHE_DIR = "../../cache/";

	/**
	 * Sets parameters
	 * @param object  $sq            Points to the SourceQuery object
	 * @param integer $communityid   Holds the Steam64 ID
	 * @param integer $steam_api_key Holds the Steam API key set in the config.inc.php file
	 * @param string  $mapname       The current map name
	 * @param object  $pdo       Points to the PDO connection object
	 * @param array   $methods       Holds the methods array.
	 */
	public function __construct($sq, $communityid, $steam_api_key, $mapname, $pdo = false, $methods = false) {
		if($pdo) { $this->pdo = $pdo; }
		if($methods) { $this->methods = $methods; }
		$this->sq = $sq;
		$this->steam_api_key = $steam_api_key;
		$this->communityid = $communityid;
		$this->mapname = $mapname;
	}

	/**
	 * Structures the entire result set and returns it as an array.
	 * @return array
	 */
	public function make() {
		$array = array();
		$this->make_ids();
		$array['steamid'] = $this->steamid;
		$array['players'] = $this->get_cur_srv_ply();
		$cache = $this->get_cache();
		if($cache == false) {
			$res = $this->fetch_steam_data();
			$array['playername'] = $res['playername'];
			$array['avatar'] = $res['avatar'];
			$this->write_cache($array['playername'], $array['avatar']);
		}
		else {
			$array = array_merge($array, $cache);
		}
		$array['mapimage'] = $this->get_map_icon();
		if($this->pdo) {
			print_r($this->methods);
			foreach($this->methods as $method => $bool) {
				if($bool) { $array[$method] = $this->fetch_wallet($method); }
			}
		}
		return $array;
	}

	public function cache() {
		$this->make_ids();
		$res = $this->fetch_steam_data();
		$this->write_cache($res['playername'], $res['avatar']);
	}

	/**
	 * Fetches the servers current amount of players.
	 * @return integer
	 */
	public function get_cur_srv_ply() {
		$players = $this->sq->GetPlayers();
		return count($players);
	}

	/**
	 * Calculates SteamID and UniqueID out of CommunityID.
	 * @return none
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
	 * @param $type String
	 * @return integer || boolean
	 */
	public function fetch_wallet($type) {
		if($type == "darkrp") {
			$query = "SELECT `wallet` FROM `darkrp_player` WHERE `uid`=:uid LIMIT 1";
		}
		elseif ($type == "pointshop") {
			$query = "SELECT `points` FROM `pointshop_data` WHERE `uniqueid`=:uid LIMIT 1";
		}
		try {
			$stmt = $this->pdo->prepare($query);
			$stmt->execute(array(":uid" => $this->uniqueid));
			$result = $stmt->fetchColumn();
			$stmt->closeCursor();
			return $result;
		}
		catch (PDOException $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Fetches player name and avatar through Steam API
	 *
	 * @return array
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
	 * @return string
	 */
	public function get_map_icon() {
		if(ini_get("allow_url_fopen") == 1) {
			$buildurl = "http://image.www.gametracker.com/images/maps/160x120/garrysmod/$this->mapname.jpg";
			$headers = get_headers($buildurl);
			$headers = substr($headers[0], 9, 3);
			if($headers != "404") {
				if(file_get_contents($buildurl) === false) {
					return "https://raw.githubusercontent.com/Svenskunganka/OpenLoad/master/templates/strapquery/img/unknown_map.jpg";
				}
				else {
					return $buildurl;
				}
			}
			else {
				return "https://raw.githubusercontent.com/Svenskunganka/OpenLoad/master/templates/strapquery/img/unknown_map.jpg";
			}
		}
		else {
			return "https://raw.githubusercontent.com/Svenskunganka/OpenLoad/master/templates/strapquery/img/unknown_map.jpg";
		}
	}

	/**
	 * Gets player Steam info from cache if it exists.
	 * @return array || boolean
	 */
	public function get_cache() {
		$cache_file = self::CACHE_DIR.$this->communityid;
		if(file_exists($cache_file)) {
				$contents = file_get_contents(self::CACHE_DIR.$this->communityid);
				$contents = explode(PHP_EOL, $contents);
				$array['playername'] = $contents[0];
				$array['avatar'] = $contents[1];
				return $array;
		}
		else {
			return false;
		}
	}

	/**
	 * Writes Steam info cache to file
	 * @param  string $playername Steam profile name of player
	 * @param  string $avatar     Steam Avatar URL
	 * @return boolean
	 */
	public function write_cache($playername, $avatar) {
		$cache_file = self::CACHE_DIR.$this->communityid;
		$contents = $playername.PHP_EOL.$avatar;
		if(file_put_contents($cache_file, $contents) === false) {
			return false;
		}
		else {
			return true;
		}
	}
}
?>