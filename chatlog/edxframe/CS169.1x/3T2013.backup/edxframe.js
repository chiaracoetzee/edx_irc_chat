var chatChannels = "#cs1691x";

$("#chatframe").hide();
$("#chatframe").html(
['<p><span id="loadingchat">Loading chat...</span>',
 '<iframe id="chatiframe"',
 '       style="position: absolute; top: -9999em; visibility: hidden;"',
 '       onload="showDelayed(5,this,\'loadingchat\');"',
 '       src="https://cs1692x.moocforums.org:9001/"',
 '       height="300" width="100%"></iframe></p>'
].join('\n')
);

function getContentInContainer(matchClass) {
    var elems = document.getElementsByTagName('*'), i;
    for (i in elems) {
        if((' ' + elems[i].className + ' ').indexOf(' ' + matchClass + ' ')
                > -1) {
            return elems[i].textContent;
        }
    }
}

function getUsername() {
  return getContentInContainer("user-link").replace("Dashboard for:", "").replace(/^\s+|\s+$/g, '');
}

function updateChatUrl() {
  username = getUsername();
  document.getElementById('chatiframe').src +=
      '?channels=' + encodeURIComponent(chatChannels) +
      '&nick=' + encodeURI(username);
}

if (document.getElementById('chatiframe')) {
  init();
} else {
  window.onload = init;
}

function init(){
  updateChatUrl();

  // Based on http://www.jquery4u.com/json/jsonp-examples/
  $.ajax({
      url: 'https://cs1692x.moocforums.org/chatlog/edxframe/CS169.1x/3T2013/checkconsent/?username=' + getUsername(),
      dataType: 'jsonp',
      type: 'GET',
      async: false,
      contentType: "application/json",
      jsonpCallback: 'jsonCallback',
      success: function (json) {
	  if (json.chatconsented) {
              $("#chatframe").show();
	  } else {
              $('#chatiframe').attr('src', 'about:blank');
              window.frames["chatiframe"].location.reload();
          }
      }
  });
}

function showDelayed(delaySeconds, element, elementHide) {
  setTimeout(function(){
    element.style.position='static';
    element.style.visibility='visible';
    document.getElementById(elementHide).style.display='none';
  }, 1000*delaySeconds);
}
