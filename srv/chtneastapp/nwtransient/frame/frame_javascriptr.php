<?php


class javascriptr {

  private $teststring = "";
  private $regid = "";
  private $regcode = "XXX";

  function __construct() {
    session_start();   
    $reguser = registerServerIdent();
    $this->teststring = "ZACK WAS HERE IN THE TEST STRING";
    $this->regid = $reguser['u'];
    $this->regcode = $reguser['i']; 
  }

    function globalscripts ( $rqst ) {
  session_start();
  $sid = session_id();
  $tt = treeTop;
  $dtaTree = dataPath;
  $eMod = encryptModulus;
  $eExpo = encryptExponent;
  //LOCAL USER CREDENTIALS BUILT HERE
  $regUsr = $this->regid;  
  $regCode = $this->regcode;
  
  $rtnThis = <<<GLOBJAVA

    var byId = function( id ) { return document.getElementById( id ); };
    var treeTop = "{$tt}";
    var dtaPath = "{$dtaTree}";
    var regu = "{$regUsr}";
    var regi = "{$regCode}";

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
    
    function universalAJAX(methd, url, passedDataJSON, callbackfunc, dspBacker) { 
      if (dspBacker === 1) { 
        byId('universalbacker').style.display = 'block';
      }
      var rtn = new Object();
      var grandurl = dtaPath+url;
      httpage.open(methd, grandurl, true); 
      httpage.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regi));
      httpage.onreadystatechange = function() { 
      if (httpage.readyState === 4) { 
        rtn['responseCode'] = httpage.status;
        rtn['responseText'] = httpage.responseText;
        if (parseInt(dspBacker) < 2) { 
          byId('universalbacker').style.display = 'none';
        }
        callbackfunc(rtn);
      }
     };
     httpage.send(passedDataJSON);
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


function searchresults ( $rqst ) { 
  $dp = dataPath;
  $tt = treeTop;
  $rtnThis = "";
  if ( $rqst[2] !== "") {  
        require(serverkeys . "/sspdo.zck");      
        $newrqst = explode("/",str_replace("-","", $_SERVER['REQUEST_URI']));     
        $paraSQL = "SELECT srchrqstid, rqston, rqststr FROM tidal.searchrequest where srchrqstid = :srchrqstid";
        $paraRS = $conn->prepare( $paraSQL );
        $paraRS->execute( array( ':srchrqstid' => $newrqst[2])); 
        
        if ( $paraRS->rowCount() > 0 ) {        
          $rtnThis = <<<GLOBJAVA

   document.addEventListener('DOMContentLoaded', function() {
       

        runThisRequest ( '{$newrqst[2]}'  ).then (function (fulfilled) {         

            console.log ( fulfilled );      

         })
         .catch(function (error) {

            console.log(error.message);
         });          


    });                  
    
var runThisRequest = function ( whichurl ) { 
  return new Promise(function(resolve, reject) {


    var obj = new Object(); 
    obj['requestedurl'] = whichurl;
    var passdta = JSON.stringify(obj);         
    console.log ( passdta );

    httpage.open("POST","{$dp}/run-transient-request", true);    
    httpage.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regi));
    httpage.onreadystatechange = function() { 
      if (httpage.readyState === 4) {
         if ( parseInt(httpage.status) === 200 ) { 
           var dta = JSON.parse( httpage.responseText );  
           resolve( dta['DATA'] );
         } else { 
           reject(Error("It broke! "+httpage.status+" --- "  ));                  
         }
     }
    };
    httpage.send ( passdta );

  });
}
                  
GLOBJAVA;
        }
  }
  return $rtnThis;    
}

function newsearch ( $rqst ) {
  $dp = dataPath;
  $tt = treeTop;

  $rtnThis = <<<GLOBJAVA

document.addEventListener('DOMContentLoaded', function() {

  byId('btnSubmit').addEventListener ( 'click', () =>  {
    submitTidalRequest();
  });


  var el = document.querySelectorAll(".criteriaInputField");
  el.forEach((inputs) => {
   inputs.addEventListener('keyup', () => {
   if ( byId('vocabSuggest') ) { 
     if ( byId('vocabSuggest').checked ) {     
     if ( byId('suggest'+inputs.id) ) { 
       if ( inputs.value.length < 3 ) { 
         //close suggestion box
         byId('suggest'+inputs.id).innerHTML = "";
         byId('suggest'+inputs.id).style.display = "none";   
       } else { 
         //make suggestion promise
         suggestSomething ( inputs.id ).then (function (fulfilled) {         
            byId('suggest'+inputs.id).innerHTML = fulfilled;
            byId('suggest'+inputs.id).style.display = 'block';
          })
          .catch(function (error) {
            byId('suggest'+inputs.id).innerHTML = '<div class=errordspmsg>No Suggestions ...</div>';
            byId('suggest'+inputs.id).style.display = 'block';
            //console.log(error.message);
          }); 
       }
     }
    }
   }
   });
   
   inputs.addEventListener( 'blur', () => {
     if ( byId('suggest'+inputs.id) ) { 
       byId('suggest'+inputs.id).innerHTML = '';
       byId('suggest'+inputs.id).style.display = 'none';
     }
   });
  });

}, false);

function submitTidalRequest() { 

 byId('universalbacker').style.display = 'block';
 var preparations = []; 
  var chk = document.querySelectorAll(".checkboxThreeInput");
  chk.forEach((chkbox) => {
    if (chkbox.checked) {
      preparations.push(chkbox.id);
    }
  });

  var obj = new Object(); 
  obj['specimencategory'] = byId('fldCritSpcCat').value;
  obj['site'] = byId('fldCritSite').value;
  obj['diagnosis'] = byId('fldCritDX').value;
  obj['preparation'] = JSON.stringify(preparations);
  var passdta = JSON.stringify(obj);         
  console.log( obj );
  var mlURL = "/commit-tidal-request";
  universalAJAX("POST",mlURL,passdta,answerSubmitTidalRequest,2);          
}
          
function answerSubmitTidalRequest ( rtnData ) { 
   if (parseInt(rtnData['responseCode']) !== 200) { 
     var msgs = JSON.parse(rtnData['responseText']);
     var dspMsg = ""; 
     msgs['MESSAGE'].forEach(function(element) { 
       dspMsg += "\\n - "+element;
     });
     //ERROR MESSAGE HERE
     alert("ERROR:\\n"+dspMsg);
   } else {
     var rsp = JSON.parse(rtnData['responseText']); 
     window.location.href = "{$tt}/search-results/"+rsp['DATA']; 
   }                  
}          

var suggestSomething = function ( whichcriteria ) { 
  return new Promise(function(resolve, reject) {
    var obj = new Object(); 

    obj['requestingcriteria'] = whichcriteria;
    obj['speccat'] = byId('fldCritSpcCat').value.trim();
    obj['site'] = byId('fldCritSite').value.trim();
    obj['dx'] = byId('fldCritDX').value.trim();

    var passdta = JSON.stringify(obj);         

    httpage.open("POST","{$dp}/suggest-dxdesignation", true)    
    httpage.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regi));

    httpage.onreadystatechange = function() { 
      if (httpage.readyState === 4) {
         if ( parseInt(httpage.status) === 200 ) { 
           var dta = JSON.parse( httpage.responseText );  
           resolve( dta['DATA']  );
        } else { 
          reject(Error("It broke! "+httpage.responseText ));
        }
      }
    };
    httpage.send ( passdta );
  });
}

GLOBJAVA;
  return $rtnThis;
}

    
    
}
