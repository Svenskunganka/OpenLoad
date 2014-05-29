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

  // Packing parameters into JSON data
  json = {};
  json['mapname'] = mapname;
  json['steamid'] = steamid;
  json = JSON.stringify(json);

  // Launching AJAX call...
  $.ajax({
    type: "POST",
    url: "src/core/ajax.php",
    data: "args="+json,
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

/*
$(document).ready(function() {
  var url = $(location).attr('href'); // Get parameters index.php?template=sometemplate#steamid#map#ip
  var hash = url.substring(url.indexOf("#")+1); // This strips away index.php?template=sometemplate#, so we now have "steamid#map#ip", which we will pass to PHP using AJAX.
  $.ajax({
    type: "POST",
    url: "src/core/ajax.php",
    data: "args="+hash,
    cache: false,
    success: function(response) {
      console.log(response);
      var dataArray = jQuery.parseJSON(response); // Now we have a nice, clean array with the content we need!

      // Let's replace some HTML!
      $.each(dataArray, function(index, value) {
        $('#'+index).html(value); // Simple replace, since the array indexes has the same names as the ID's
      });
      // We can't use .html on img tag, unfortunately.
      $('#mapimage').attr('src', dataArray['mapimage']);
      $('#avatar').attr('src', dataArray['avatar']);
      $('#load-wrapper').empty();
      $('#slide-wrapper').slideDown(2000);
    }
  });
});
*/