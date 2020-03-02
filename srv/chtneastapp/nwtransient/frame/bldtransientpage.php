<?php

require('frame_javascriptr.php');
require('frame_stylesheets.php');
require('frame_defaultelements.php');
require('frame_pagecontent.php');
require('bldcontent.php');


class pagebuilder { 

  public $statusCode = 404;		
  public $pagetitle = "";
  public $pagetitleicon = "";
  public $headr = ""; 
  public $stylr = "";
  public $scriptrs = "";
  public $bodycontent = "";
  public $pagecontrols = "/* PAGE CONTROLS */";
  public $menucontent = "/* PAGE CONTROLS */";

  //PAGE NAME MUST BE REGISTERED IN THIS ARRAY - COULD DO A METHOD SEARCH - BUT I LIKE THE CONTROL OF NOT ALLOWING A PAGE THAT IS NOT READY FOR DISPLAY
  //ADD ALL PAGES TO SECURITYEXCEPTION
  private $registeredPages    = array('root','newsearch','howtousetidal','contactus','searchresults');  
  //THE SECURITY EXCPETIONS ARE THOSE PAGES THAT DON'T REQUIRE USER RIGHTS TO ACCESS
  private $securityExceptions = array('root','newsearch','howtousetidal','contactus','searchresults');

function __construct() { 		  
  $args = func_get_args();   
  if (trim($args[0]) === "") {	  		
  } else {
    if (in_array($args[0], $this->registeredPages)) {
      $pageElements = self::getPageElements($args[0],$args[1]);	  
      $this->statusCode = $pageElements['statuscode'];
      $this->pagetitle = $pageElements['tabtitle'];
      $this->pagetitleicon = $pageElements['tabicon'];
      $this->headr = $pageElements['headr'];
      $this->stylr = $pageElements['styleline'];
      $this->scriptrs = $pageElements['scripts'];
      $this->bodycontent = $pageElements['bodycontent'];
      $this->pagecontrols = $pageElements['controlbars'];
      $this->menucontent = $pageElements['menu'];
    } else { 
      $this->statusCode = 404;
    }     
  }     
}   

function getPageElements($whichpage, $rqststr) { 
  session_start();  
  $ss = new stylesheets(); 
  $js = new javascriptr();
  $oe = new defaultpageelements();
  $pc = new pagecontent();
  $ct = new mastercontroldevices();
  $elArr = array();
  
  //HEADER - TAB - ICON ---------------------------------------------
    $elArr['tabtitle']     =   (method_exists($oe,'pagetabs') ? $oe->pagetabs ( $whichpage, $rqststr ) : "");
    $elArr['tabicon']      =   (method_exists($oe,'faviconBldr') ? $oe->faviconBldr($whichpage) : "");
    $elArr['headr']        =   (method_exists($pc,'generateHeader') ? $pc->generateHeader($whichpage) : "");

  //STYLESHEETS ---------------------------------------------------
    $elArr['styleline']    =   (method_exists($ss,'globalstyles') ? $ss->globalstyles( $whichpage ) : "");
    //GET FROM FUNCTION IN STYLE 
    //$elArr['styleline']   .=   (method_exists($ss, $whichpage) ? $ss->$whichpage() : "");

  //JAVASCRIPT COMPONENTS -------------------------------------------
    $elArr['scripts']      =   (method_exists($js,'globalscripts') ? $js->globalscripts() : "");
    $elArr['scripts']     .=   (method_exists($js,$whichpage) ? $js->$whichpage( $rqststr ) : "");

    $elArr['controlbars']  =   (method_exists($ct,'mastercontrols') ? $ct->mastercontrols( $whichpage ) : "" );
    $elArr['menu']         =   (method_exists($ct,'mastertopmenu') ? $ct->mastertopmenu( $whichpage, $rqststr ) : "" );
    $elArr['menu']        .=   (method_exists($oe,'modalbackbuilder') ? $oe->modalbackbuilder( $whichpage, $rqststr ) : "" );

  //PAGE CONTENT ELEMENTS  ------------------------------------
  //MAKE SURE USER IS ALLOWED ACCESS TO THE PAGE 
  $allowPage = 0;
  if (in_array($whichpage, $this->securityExceptions)) {
    $allowPage = 1;
  } else {      
//      foreach ($usrmetrics->allowedmodules as $modval) { 
//          $allowPage =  ($whichpage === str_replace("-","",$modval[2])) ? 1 : $allowPage; 
//          foreach ($modval[3] as $allowPageLst) {
//              $allowPage = ($whichpage === str_replace("-","",$allowPageLst['pagesource'])) ? 1 : $allowPage; 
//          }
//      }
  } 
 
 if ($allowPage === 1) { 
    $elArr['bodycontent'] = (method_exists($pc,$whichpage) ? $pc->$whichpage( $rqststr ) : "");  
    $elArr['bodycontent'] .= (method_exists($oe,'modalbackbuilder') ? $oe->modalbackbuilder($whichpage) : "");
 } else { 
   $elArr['bodycontent'] =  "<h1>USER NOT ALLOWED ACCESS TO THIS MODULE PAGE OR PAGE NOT FOUND ({$whichpage})";
 }
//END PAGE ELEMENTS ---------------------------

//RETURN STATUS - GOOD ---------------------------------------------------------------
  $elArr['statuscode'] = 200;
  return $elArr;
}

}


