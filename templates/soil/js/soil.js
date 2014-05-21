/***
 * Soil's Custom Javascript
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

var files_total;
var files_needed;

function ol_SetFilesTotal(filestotal) {
	files_total = filestotal;
}

function ol_SetFilesNeeded(needed) {
	files_needed = needed;
	math = ((files_total-files_needed)/files_total)*100;
	percentage = Math.floor(math);
	$('#progress').attr("aria-valuenow", percentage);
	$('#progress').css("width", percentage+"%");
	$('#status').html("Downloading... "+percentage+"%");
}

function ol_SetStatusChanged(status) {
	if(status == "Sending client info...") {
		$('#progress').attr("aria-valuenow", 100);
		$('#progress').css("width", "100%");
		$('#file').html("Have fun!");
		$('#filesneeded').html(0);
	}
}

$(document).keypress(function(e) {
		var music = $('#bg_music');
		if(music.prop('paused')) {
			music[0].play();
		}
		else {
			music[0].pause();
		}
});

$(document).ready(function() {
	$('#bg_music').prop("volume", 0.2);
});

function ol_end() {
	var spinner_enabled = $('#spinner').css("display");
	if(spinner_enabled != "none") {
		$('#spinner').fadeOut('slow', function() {
			$('#avatar').css('display', 'block').hide().fadeIn('slow');
		});
	}	
}