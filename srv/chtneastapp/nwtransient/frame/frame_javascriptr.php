<?php


class javascriptr {

    function globalscripts ( $rqst ) {
  session_start();
  $sid = session_id();
  $tt = treeTop;
  $dtaTree = dataPath;
  $eMod = encryptModulus;
  $eExpo = encryptExponent;
  $si = serverIdent;
  $sp = apikey;
  $rtnThis = <<<GLOBJAVA

    var byId = function( id ) { return document.getElementById( id ); };
    var treeTop = "{$tt}";
    var dtaPath = "{$dtaTree}";
    var si = "{$si}";
    var sp = "{$sp}";

    var httpage = getXMLHTTPRequest();
    function getXMLHTTPRequest() {
      try {
        req = new XMLHttpRequest();
      } catch(err1) {
        try {
	  req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(err2) {
          try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
          } catch(err3) {
            req = false;
          }
        }
      }
      return req;
    }

    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
      if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
        byId('universalTopBarHolder').style.background = "rgba(255,255,255,1)";
        byId('universalTopBarHolder').style.borderBottom = "1px solid rgba(0,32,113,.3)";
      } else {
        byId('universalTopBarHolder').style.background = "rgba(255,255,255,.4)"; 
        byId('universalTopBarHolder').style.borderBottom = "1px solid rgba(0,32,113,0)";
      }
    }

GLOBJAVA;
  return $rtnThis;
}        
    
    
    
}
