/***
 * Core Javascript.
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

function GameDetails(servername, serverurl, mapname, maxplayers, steamid, gamemode) {
	var gamemodes = {}
	gamemodes['terrortown'] = "TTT";
	gamemodes['sandbox'] = "Sandbox";
	gamemodes['darkrp'] = "DarkRP";
	gamemodes['murder'] = "Murder";
	gamemodes['cinema'] = "Cinema";
	gamemodes['prop_hunt'] = "Prop Hunt";
	gamemodes['deathrun'] = "Deathrun";
	gamemodes['jailbreak'] = "Jailbreak";

	// Updating parameters that does not require back-end interaction.
	$('#servername').html(servername);
	$('#mapname').html(mapname);
	$('#maxplayers').html(maxplayers);
	if(gamemode in gamemodes) {
		gamemode = gamemodes[gamemode];
	}
	$('#gamemode').html(gamemode);

	// Getting URL parameter
	var sid = getURLParameter('sid');

	// Packing parameters into JSON data
	json = {};
	json['mapname'] = mapname;
	json['steamid'] = steamid;
	json = JSON.stringify(json);

	// Launching AJAX call...
	$.ajax({
		type: "POST",
		url: "src/core/ajax.php",
		data: { args: json, sid: sid },
		cache: false,
		beforeSend: function() {
			// Checking if ol_init function is defined, and executes if so.
			if(typeof ol_init == 'function') { ol_init(); }
		},
		success: function(response) {
			console.log(response);
			response = jQuery.parseJSON(response);
			$.each(response, function(index, value) {
				$('#'+index).html(value);
			});
			$('#mapimage').attr('src', response['mapimage']);
			$('#avatar').attr('src', response['avatar']);
			$.ajax({
				type: "POST",
				url: "src/core/ajax.php",
				data: {args: json, cache: "true"},
				cache: false,
			});
		},
		complete: function() {
			// Checking if ol_end function is defined, and executes if so.
			if(typeof ol_end == 'function') { ol_end(); }
		}
	});
	if(typeof ol_GameDetails == 'function') {
		ol_GameDetails(servername, serverurl, mapname, maxplayers, steamid, gamemode);
	}
}

function SetStatusChanged(status) {
	$('#status').html(status);
	if(typeof ol_SetStatusChanged == 'function') { ol_SetStatusChanged(status); }
}

function SetFilesNeeded(needed) {
	$('#filestotal').html(needed);
	$('#filesneeded').html(needed);
	if(typeof ol_SetFilesTotal == 'function') { ol_SetFilesTotal(needed); }
}

function DownloadingFile(fileName) {
	$('#file').html(fileName);
	var needed = $('#filesneeded').html();
	needed = needed - 1;
	$('#filesneeded').html(needed);
	if(typeof ol_SetFilesNeeded == 'function') { ol_SetFilesNeeded(needed); }
}

function getURLParameter(param) {
	return decodeURIComponent((new RegExp('[?|&]' + param + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||1;
}