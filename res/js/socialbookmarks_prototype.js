// Send ajax request if a bookmark was clicked
function bookmark( data ) {
  var url = 'index.php?eID=tx_spsocialbookmarks_pi1&data=' + data;
  new Ajax.Request(url, {method : 'post'});
}

// Replace ###TITLE### with page title
Event.observe(window, 'load', function() {
  var aLinks    = $$('div.tx-spsocialbookmarks-pi1 a');
  var sTitle    = encodeURIComponent(document.title);
  var sOldLink  = '';
  var sNewLink  = '';

  for (i = 0; i < aLinks.length; i++) {
    sOldLink = aLinks[i].readAttribute('href');
    sNewLink = sOldLink.split('###TITLE###').join(sTitle);
    aLinks[i].setAttribute('href',sNewLink);
  }
});