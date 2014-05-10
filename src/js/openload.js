/***
 * Core Javascript.
 *
 * Project: OpenLoad
 * Author: Svenskunganka
 * Website: http://svenskunganka.com
 * Contact: http://facepunch.com/member.php?u=445369
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 ***/

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
      
      /**
       * Now we got an array that looks like this:
       * dataArray 
       * Exists: ['mapname'] ['steamid'] ['players'] ['gamemode'] ['servername'] ['playername'] ['avatar']
       * May Exist: ['darkrp'] ['pointshop'] (Depends if the user has enabled MySQL)
       */

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