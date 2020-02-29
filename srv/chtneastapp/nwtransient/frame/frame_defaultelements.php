<?php

class mastercontroldevices { 

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

    function mastertopmenu ( $whichpage , $rqststr )  { 
      $at = genAppFiles; 
      $tt = treeTop;
      $ott = ownerTreeTop;
      $at = genAppFiles;
      $chtnlogo = base64file("{$at}/publicobj/graphics/chtn_trans.png", "chtntoplogo", "img", true);
      //$r = json_encode( $rqststr );
      //UNDERLINE $WHICHPAGE

      $rtnthis = <<<TOPMENU
<div id=universalTopBarHolder>

  <div id=topAppBar>
   <div id=applicationListing></div>
  </div>

  <div id=menuItems>
        <a href="{$tt}" class="logoholder">{$chtnlogo}</a>
        <a href="{$ott}" class="menuLink" target="_new">CHTN Eastern</a>     
        <a href="{$tt}/new-search" class="menuLink" target="_new">New Search</a>     
        <a href="{$tt}/how-to-use-tidal" class="menuLink" target="_new">Using TIDAL</a>     
        <a href="{$tt}/contact-us" class="menuLink">Contact Us</a>     
  </div>

  <div id=menuSidePanel align=right>&nbsp;</div>

</div>
TOPMENU;
  
      return $rtnthis;
    }

}

class defaultpageelements {

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

function modalbackbuilder ( $whichpage ) { 
$rtnthis = <<<RTNTHIS
<div id=universalbacker></div>
RTNTHIS;
return $rtnthis;
} 

function faviconBldr($whichpage) { 
  $at = genAppFiles;
  $favi = base64file("{$at}/publicobj/graphics/icons/chtnblue.ico", "favicon", "favicon", true);
  return $favi;
}

function pagetabs($whichpage, $rqststr) { 
  $dp = dataPath;  
  switch($whichpage) { 
    case 'root':
      $thisTab = "Transient Inventory Search :: Cooperative Human Tissue Network";
      break;
//    case 'blog':
//        if ( trim( $rqststr[2] ) === "" ) {
//          $thisTab = "Blog Listing [Zack von Menchhofen - The Choirmaster]";
//        } else {
//          $titleDta = json_decode( callrestapi_anon ( "GET" , $dp . "/blog-title/{$rqststr[2]}"), true); 
//          if ( (int)$titleDta['RESPONSECODE'] === 200 ) { 
//            $thisTab = "{$titleDta['DATA']['blogtitle']} [Zack von Menchhofen - The Choirmaster]";
//          } else {
//            $thisTab = "Blog Entry [Zack von Menchhofen - The Choirmaster]";
//          } 
//        }
//    break;    
    default:        
      $thisTab = "Transient Inventory Search :: Cooperative Human Tissue Network";
    break; 
  }
  return $thisTab;
}

}



