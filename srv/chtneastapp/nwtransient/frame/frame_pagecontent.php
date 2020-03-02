<?php

class pagecontent {

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

    function contactus ( $rqst ) {
      $tt = treeTop;      
      $at = genAppFiles;
      $cb = new bldcontent();  
      //$pageData = $cb->newsearch( $rqst );
$pge = <<<PAGECONTENT
<div id=mainPageDiv>
  {$pageData} 
</div>
PAGECONTENT;
      return $pge;
    }

    function howtousetidal ( $rqst ) {
      $tt = treeTop;      
      $at = genAppFiles;
      $cb = new bldcontent();  
      //$pageData = $cb->newsearch( $rqst );
$pge = <<<PAGECONTENT
<div id=mainPageDiv>
  {$pageData} 
</div>
PAGECONTENT;
      return $pge;


    }

    function newsearch ( $rqst ) { 
      $tt = treeTop;      
      $at = genAppFiles;
      $cb = new bldcontent();  
      $pageData = $cb->newsearch( $rqst );
$pge = <<<PAGECONTENT
<div id=mainPageDiv>
  {$pageData} 
</div>
PAGECONTENT;
      return $pge;
    }

    function root ( $rqst ) { 
      $tt = treeTop;      
      $at = genAppFiles;
      $cb = new bldcontent();  
      $pageData = $cb->root( $rqst );
$pge = <<<PAGECONTENT
<div id=mainPageDiv>
  {$pageData} 
</div>
<div id=swirlddsp></div>
PAGECONTENT;
      return $pge;
    } 
 
    function generateHeader( $whichpage) {
      $tt = treeTop;      
      $at = genAppFiles;
      //$jsscript =  base64file( "{$at}/extlibs/Barrett.js" , "", "js", true);
      //$jsscript .= "\n" . base64file( "{$at}/extlibs/BigInt.js" , "", "js");
      //$jsscript .= "\n" . base64file( "{$at}/extlibs/RSA.js" , "", "js");
      //$jsscript .= "\n" . base64file( "{$at}/publicobj/extjslib/tea.js" , "", "js");
      if ( strtolower($whichpage) === 'wx') { 
        $includethis = <<<INCLUDETHIS
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
INCLUDETHIS;
      } else { 
        $includethis = "";
      }
      $rtnThis = <<<STANDARDHEAD
<!-- <META http-equiv="refresh" content="0;URL={$tt}"> //-->
<!-- CHTNEAST.ORG IDENTIFICATION: {$tt}/{$whichpage} //-->

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="refresh" content="28800">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
{$includethis}
<script lang=javascript>
var chtneastorgidentification = '{$tt}/{$whichpage}';
</script>
{$jsscript}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
STANDARDHEAD;
      return $rtnThis;
    }        
    
    
}
