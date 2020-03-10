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
       
        runThisRequest ( '{$newrqst[2]}'  )
          .then (function (fulfilled) {         
            if ( parseInt(fulfilled['ITEMSFOUND']) > 0 ) {
              //DISPLAY DATA
              byId('waiterDialog').style.display = 'none';
              byId('errorDialog').style.display = 'none';
              byId('displayBSData').innerHTML = fulfilled['DATA'];
              byId('displayBSData').style.display = 'block';  
            } else {
              //DISPLAY NOT FOUND
              byId('waiterDialog').style.display = 'none';
              byId('errorDialog').style.display = 'block';
              byId('displayBSData').style.display = 'none';  
            }
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
        httpage.open("POST","{$dp}/run-transient-request", true);    
        httpage.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regi));
        httpage.onreadystatechange = function() { 
          if (httpage.readyState === 4) {
            if ( parseInt(httpage.status) === 200 ) { 
              var dta = JSON.parse( httpage.responseText );  
              resolve( dta );
            } else { 
              reject(Error("It broke!"));                  
            }
          }
        };
        httpage.send ( passdta );
      });
    }


    var thismany = 0;
    function selectThisSample ( id ) { 

      if ( byId(id).dataset.selected === 'false' ) { 
        byId(id).dataset.selected = 'true';
        thismany++;
      } else { 
        byId(id).dataset.selected = 'false';
        thismany--;
      }
      updateRequester();
    }

    function updateRequester() { 
      byId('btnRequester').innerHTML = ( thismany > 0 ) ? "Request ("+thismany+")" : "Request"; 
    }

    function action_selectall() { 
      var itmlst = document.querySelectorAll(".transientItem");
      thismany = 0;
      itmlst.forEach((itm) => {
        byId( itm.id ).dataset.selected = 'true';
        thismany++;
      });
      updateRequester();
    }

    function action_selectnone() { 
      var itmlst = document.querySelectorAll(".transientItem");
      itmlst.forEach((itm) => {
        byId( itm.id ).dataset.selected = 'false';
      });
      thismany = 0;
      updateRequester();
    }

    function action_makerequest() { 
      var itmlst = document.querySelectorAll(".transientItem");
      var howmany = 0;
      itmlst.forEach((itm) => {
        if ( byId( itm.id ).dataset.selected === 'true' ) {
          howmany++;
        }
      });
      if ( howmany < 1 ) { 
        alert('You haven\'t selected any biosamples ... ');
      } else {
        var hld = new Object();
        var obj = new Object(); 
        var url = window.location.pathname.split("/");
        itmlst.forEach((itm) => {
          if ( byId( itm.id ).dataset.selected === 'true' ) {
            var def = new Object(); 
            def['spc']    = itm.dataset.spc; 
            def['ste']    = itm.dataset.ste; 
            def['dxd']    = itm.dataset.dxd; 
            def['prp']    = itm.dataset.prp;
            hld[ itm.id ]  =  def ; 
          }
        });
        obj[ url[2] ] = hld;
        var passdta = JSON.stringify(obj);         
        var mlURL = "/transient-request-request";
        universalAJAX("POST",mlURL,passdta,answerMakeRequest,2);          
      }
    }

    function answerMakeRequest ( rtnData ) { 
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
        window.location.href = "{$tt}/define-request/"+rsp['DATA']; 
      } 
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


  if (  byId('fldCritSpcCat') ) { 
    byId('fldCritSpcCat').value = "";
  }
  if ( byId('fldCritSite') ) { 
    byId('fldCritSite').value = "";
  }
  if ( byId('fldCritDX') ) {
    byId('fldCritDX').value = "";
  }

  var chk = document.querySelectorAll(".checkboxThreeInput");
  chk.forEach((chkbox) => {
    chkbox.checked = false;
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
  //console.log( obj );
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

function definerequest ( $rqst ) { 
  $dp = dataPath;
  $tt = treeTop;
  $rtnThis = "";
  
  $newrqst = explode("/",str_replace("-","", $_SERVER['REQUEST_URI']));     
  $rtnThis = <<<GLOBJAVA

   document.addEventListener('DOMContentLoaded', function() {
       
        makeThisRequest ( '{$newrqst[2]}'  )
          .then (function (fulfilled) {         
            if ( parseInt(fulfilled['ITEMSFOUND']) > 0 ) {
              //DISPLAY DATA
              byId('waiterDialog').style.display = 'none';
              byId('displayBSData').innerHTML = fulfilled['DATA'];
              byId('displayBSData').style.display = 'block';  
            } else {
              //DISPLAY NOT FOUND
              byId('waiterDialog').style.display = 'none';
              byId('displayBSData').style.display = 'none';  
            }
         })
         .catch(function (error) {
            console.log(error.message);
         });          

    });                  
    
    var makeThisRequest = function ( whichurl ) { 
      return new Promise(function(resolve, reject) {
        var obj = new Object(); 
        obj['requestedurl'] = whichurl;
        var passdta = JSON.stringify(obj);         
        httpage.open("POST","{$dp}/build-transient-request", true);    
        httpage.setRequestHeader("Authorization","Basic " + btoa(regu+":"+regi));
        httpage.onreadystatechange = function() { 
          if (httpage.readyState === 4) {
            if ( parseInt(httpage.status) === 200 ) { 
              var dta = JSON.parse( httpage.responseText );  
              resolve( dta );
            } else { 
              reject(Error("It broke!"));                  
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
