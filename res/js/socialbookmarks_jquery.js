// Send ajax request if a bookmark was clicked
function bookmark( data ) {
  $.post('index.php?eID=tx_spsocialbookmarks_pi1', {
    data: data
  });
}

// Replace ###TITLE### with page title
jQuery(document).ready(function($) {
  var sTitle = encodeURIComponent(document.title);
  $('div.tx-spsocialbookmarks-pi1 a').each(function() {
    var sNewLink = $(this).attr('href').split('###TITLE###').join(sTitle);
    $(this).attr('href', sNewLink);
  });
});