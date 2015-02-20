// This is the Javascript file for use with the SourceTrak API logic and class files.
// Install this on your server, and load it in the head section of your web site
// This file will persist or create a SourceTrak session using an AJAX post to the sourceTrakAPI.php file
// This code can be initiated by a javascript call in the head (if sets loaded are consistent across the site) or locally on the page.
// Each SourceTrak set should trigger an API call.  (ie. if there are 4 locations, there should be 4 api calls)
// Each phone number should be assigned its own html div to be associated with the Ajax response.


function ajaxLoaders(){
  $('.loaders').show();
}


//This function builds the url and initiates triggers AJAX POST
function sourcetrakAJAX(session, setID)
{
  if (session == null){
    var url = "/sourceTrakAPI.php?"+

    "&set_id="+setID+

    // YOU MUST include base URI and referer
    "&referer="+document.referrer+
    "&baseURI="+encodeURIComponent(window.location.href);
  } else {
    var url = "/sourceTrakAPI.php?"+

    "&log_id="+session+
    "&set_id="+setID+

    // YOU MUST include base URI and referer
    "&referer="+document.referrer+
    "&baseURI="+encodeURIComponent(window.location.href);
  }

  // Jquery AJAX call
    response = $.ajax({
      type: 'GET',
      async: true,
      url: url,
      dataType: 'json',
      success: function(data) {
          LogID = data.root.logXML;
          ResponseDiv = '#'+setID;
          ResponseDivFooter = '#'+setID+'footer';
          $(ResponseDiv).html(data.root.phone);
          $(ResponseDivFooter).html(data.root.phone);
          $('.numbers').show();
          $('.loaders').hide();
          if (session == null){
          //Set a cookie for each log ID in the response
              setCookie(setID, LogID, 1);
          }
        } //end success function
    }); //end Ajax call
} //end function



//When the website loads this function checks to see if we already have log id cookies
//If not we do SourceTrak api calls and set the cookies

 function checkCookie(setID)
{
  var session=getCookie(setID);

  if (session != null) {
      sourcetrakAJAX(session, setID);
  } else if (session == null) {
      //Do the API calls without any log ids
      setCookie("referrer", document.referrer, 24);
      setCookie("baseURI", window.location.href, 24);
      sourcetrakAJAX(null, setID);
  }
}



function getCookie(c_name)
{
  var i,x,y,ARRcookies=document.cookie.split(";");
  for (i=0;i<ARRcookies.length;i++){
    x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
    y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
    x=x.replace(/^\s+|\s+$/g,"");
    if (x==c_name){
      return unescape(y);
    }
 }
}



function setCookie(c_name,value,exhours)
{
  var exdate=new Date();
  exdate.setHours(exdate.getHours() + exhours);
  var c_value=escape(value) + ((exhours==null) ? "" : "; expires="+exdate.toUTCString());
  document.cookie=c_name + "=" + c_value;
}
