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

function newsearch ( $rqst ) {
  $dp = dataPath;

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
  obj['specimenCategory'] = byId('fldCritSpcCat').value;
  obj['site'] = byId('fldCritSite').value;
  obj['diagnosis'] = byId('fldCritDX').value;
  obj['preparations'] = JSON.stringify(preparations);
  var passdta = JSON.stringify(obj);         

  console.log( obj );


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
