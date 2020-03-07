<?php

class dataposters { 

  public $responseCode = 400;
  public $rtnData = "";

function __construct() { 
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 
    $this->rtnData = $args[0];    
    if (trim($args[0]) === "") { 
    } else { 
      $request = explode("/", $args[0]); 
      if (trim($request[2]) === "") { 
        $this->responseCode = 400; 
        $this->rtnData = json_encode(array("MESSAGE" => "DATA NAME MISSING " . json_encode($request),"ITEMSFOUND" => 0, "DATA" => array()    ));
      } else { 
        $dp = new datadoers();  
        if (method_exists($dp, $request[2])) { 
          $funcName = trim($request[2]); 
          $dataReturned = $dp->$funcName($args[0], $args[1]); 
          $this->responseCode = $dataReturned['statusCode']; 
          $this->rtnData = json_encode($dataReturned['data']);
        } else { 
          $this->responseCode = 404; 
          $this->rtnData = json_encode(array("MESSAGE" => "END-POINT FUNCTION NOT FOUND: {$request[3]}","ITEMSFOUND" => 0, "DATA" => ""));
        }
      }
    }
}

}

class datadoers {

   function runtransientrequest ( $request, $passdata ) { 
     $responseCode = 400;
     $rows = array();
     $msgArr = array(); 
     $errorInd = 0;
     $itemsfound = 0;
     require(serverkeys . "/sspdo.zck");
     session_start(); 
     $sessid = session_id();      
     $pdta = json_decode($passdata, true); 
     $at = genAppFiles;
 
     $rqstSQL = "SELECT date_format( rqston, '%m/%d/%Y %H:%i') as rqston, speccat, site, diagnosis, rqststr FROM tidal.searchrequest where srchrqstid = :rqstid"; 
     $rqstRS = $conn->prepare ( $rqstSQL );
     $rqstRS->execute( array( ':rqstid' => $pdta['requestedurl'] ));

     if ( $rqstRS->rowCount() < 1 ) { 
     } else {
       $rqst = $rqstRS->fetch(PDO::FETCH_ASSOC); 
       $serviceListSQL = "SELECT identifiercode FROM tidal.sys_registeredservices where activeind = 1";
       $serviceListRS = $conn->prepare( $serviceListSQL );
       $serviceListRS->execute();
       while ( $r = $serviceListRS->fetch(PDO::FETCH_ASSOC)) { 
         $ts = new transientservices( $r['identifiercode'], $rqst['rqststr'] );
         if ( $ts->responseCode === 200 ) {
             $rtn[ $r['identifiercode'] ] = $ts->rtnData; 
         }
       } 
       $totalFound = 0;
 
       

       foreach ( $rtn as $key => $val ) { 
         $itemline = json_decode( $val, true );
         $totalFound += (int)$itemline['ITEMSFOUND'];
         $rtnTbl =  bldDisplayTableFromReturn ( $key, $itemline );
       }

       $itemsfound = $totalFound;
 
       $a = json_decode( $rqst['rqststr'] , true );
       $prep = json_decode( preg_replace('/checkbox\-/','',$a['preparation']));
       foreach ( $prep as $pv ) {
         $p .= " {$pv}";
       }
       $p = trim($p);


       $dta = "

   <div id=buttonHolder>
    <div class=ctlBtn onclick=\"action_selectall();\"><div><i class=\"material-icons\">done_all</i></div><div class=ctlBtnText>Select All</div></div>
    <div class=ctlBtn onclick=\"action_selectnone();\"><div><i class=\"material-icons\">select_all</i></div><div class=ctlBtnText>Select None</div></div>
    <div class=ctlBtn onclick=\"action_makerequest();\"><div><i class=\"material-icons\">menu_open</i></div><div class=ctlBtnText id=btnRequester>Request</div></div>
   </div>

             <div id=criteriaDisplay>

                <div id=instructionDisplay>
                  <b>Instructions</b>: Instructions on using this screen go here ...
                </div> 

               <div class=grider align=left>
                 <div class=\"critElemHold rqstdate\"> 
                   <div class=critElemLabel>Query Requested On</div>
                   <div class=critElemData>{$rqst['rqston']}</div>
                 </div> 
                 
                 <div class=critElemHold> 
                   <div class=critElemLabel><span class=smlFont>Criteria Parameter</span><br>Specimen Category</div>
                   <div class=critElemData>{$rqst['speccat']}</div>
                 </div> 

                 <div class=critElemHold> 
                   <div class=critElemLabel><span class=smlFont>Criteria Parameter</span><br>Site</div>
                   <div class=critElemData>{$rqst['site']}</div>
                 </div> 

                 <div class=critElemHold> 
                   <div class=critElemLabel><span class=smlFont>Criteria Parameter</span><br>Diagnosis</div>
                   <div class=critElemData>{$rqst['diagnosis']}</div>
                 </div>     

                 <div class=critElemHold> 
                   <div class=critElemLabel><span class=smlFont>Criteria Parameter</span><br>Preparations</div>
                   <div class=critElemData>{$p}</div>
                 </div>    

                 <div class=itemsFoundLine>Items Found: {$totalFound}</div>

               </div>
             </div>  
            {$rtnTbl}";

       $responseCode = 200;
     }

     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;        
   }

   function committidalrequest ( $request, $passdata ) { 
     $responseCode = 400;
     $rows = array();
     $msgArr = array(); 
     $errorInd = 0;
     $itemsfound = 0;
     require(serverkeys . "/sspdo.zck");
     session_start(); 
     $sessid = session_id();      
     $pdta = json_decode($passdata, true); 
     $at = genAppFiles;
     //TODO:  DATA CHECKS
     $rqstCaptureSQL = "insert into tidal.searchrequest (srchrqstid, rqston, rqstby_user, rqstby_phpid, rqststr, speccat, site, diagnosis) values(:srchrqstid, now(), :rqstby_user, :rqstby_phpid, :rqststr, :speccat, :site, :diagnosis)"; 
     $rqstCaptureRS = $conn->prepare( $rqstCaptureSQL );
     $srchrqst = generateRandomString(15);
     if ( isset ( $_SESSION['loggedon']) ) { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => 'USER GOES HERE', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata, ':speccat' => strtoupper(trim($pdta['specimencategory'])), ':site' => strtoupper(trim($pdta['site'])), ':diagnosis' => strtoupper(trim($pdta['diagnosis']))  ));
     } else { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => '', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata, ':speccat' => strtoupper(trim($pdta['specimencategory'])), ':site' => strtoupper(trim($pdta['site'])), ':diagnosis' => strtoupper(trim($pdta['diagnosis'])) ));
     }
     $dta = $srchrqst;
     $responseCode = 200;
     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;        
    }

   function suggestdxdesignation ( $request, $passdata ) { 
     $responseCode = 400;
     $rows = array();
     $msgArr = array(); 
     $errorInd = 0;
     $itemsfound = 0;
     require(serverkeys . "/sspdo.zck");
     session_start(); 
     $sessid = session_id();      
     $pdta = json_decode($passdata, true); 
     $at = genAppFiles;
     //{"requestingcriteria":"fldCritSite","speccat":"","site":"thy","dx":""}

     if ( trim($pdta['requestingcriteria']) !== "" ) {

       switch ( trim($pdta['requestingcriteria']) ) {
         case 'fldCritSite':
         
           if ( trim($pdta['site']) !== "" ) { 
             $paralist = array(); 
             $baseSQL = "SELECT distinct ifnull(anatomicsite,'') as site FROM masterrecord.ut_procure_biosample where  ifnull(anatomicSite,'') <> '' and anatomicSite like :sitesite order by 1";
             $baseRS = $conn->prepare( $baseSQL );
             $baseRS->execute(array( ':sitesite' => trim($pdta['site']) . '%'));
             $itemsfound = $baseRS->rowCount();
             $dta = "<div class=vocabularyCount>Terms found: {$itemsfound}</div>";
             while ( $r = $baseRS->fetch(PDO::FETCH_ASSOC)) { 
                $dta .= "<div class=vocabularyDsp>{$r['site']}</div>";
             }
             if ( (int)$itemsfound > 0 ) { 
                 $responseCode = 200;
             }
           }
           break;
         case 'fldCritDX':
           if ( trim($pdta['dx']) !== "" ) { 
             $paralist = array(); 
             $baseSQL = "SELECT distinct ifnull(diagnosis,'') as dx FROM masterrecord.ut_procure_biosample where  ifnull(diagnosis,'') <> '' and diagnosis like :sitesite order by 1";
             $baseRS = $conn->prepare( $baseSQL );
             $baseRS->execute(array( ':sitesite' => trim($pdta['dx']) . '%'));
             $itemsfound = $baseRS->rowCount();
             $dta = "<div class=vocabularyCount>Terms found: {$itemsfound}</div>";
             while ( $r = $baseRS->fetch(PDO::FETCH_ASSOC)) { 
                $dta .= "<div class=vocabularyDsp>{$r['dx']}</div>";
             }
             if ( (int)$itemsfound > 0 ) { 
                 $responseCode = 200;
             }
           }
           break;
       }

     }
     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;
   }

}


function bldDisplayTableFromReturn ( $division, $rawData ) {
  //https://dev.chtneast.org/print-obj/pathology-report/VUZJVWJnSHM5WVJOemdsQkloRnNxUT09
  //{\"RESPONSECODE\":200,\"MESSAGE\":[],\"ITEMSFOUND\":88,\"DATA\":[{\"bgs\":\"33454A1002\",\"specimencategory\":\"MALIGNANT\",\"site\":\"THYROID\",\"diagnosis\":\"CARCINOMA | ANAPLASTIC\",\"preparation\":\"FFPE\",\"hourspost\":0,\"metric\":\"0.11 Grams\",\"prselector\":\"9ZIYHIJN\"}  

$thisyear = date('Y');

  $rtnthis = <<<RTNTHIS

<div id=itemHolder>
  <div id=itemHeader>
    <div class=headerDsp>Specimen Category</div>
    <div class=headerDsp>Site | Sub-Site</div>
    <div class=headerDsp>Diagnosis | Modifier</div>
    <div class=headerDsp>Preparation</div>
    <div class=headerDsp>Metric</div>
    <div class=headerDsp><center>Pst Exc</div>
    <div class=headerDsp>Age | Race | Sex</div>
    <div class=headerDsp>Pathology Report</div>
    <div class=headerDsp>Identifier</div>
  </div>

RTNTHIS;

  $cntr = 0;
  foreach ( $rawData['DATA'] as $key => $value ) {
    if ( trim($value['prselector']) !== "" ) { 
      //TODO: MAKE DYNAMIC PER INSTITUTION DIVISION 
      $plink = cryptservice ( $value['prselector'], 'e' );
      $pradd = "<div class=itemElementHold><div class=itemElementLabel>Pathology Report</div><div class=itemElementData ><a href=\"https://dev.chtneast.org/print-obj/pathology-report/{$plink}\" target=\"_blank\">Pathology Report</a></div></div>";
    } else { 
      $pradd = "<div class=itemElementHold><div class=itemElementLabel>Pathology Report</div><div class=itemElementData >&nbsp;</div></div>";
    }     

    $smplid = strtoupper( trim( $value['bgs'] ));
    $dx = ( trim($value['diagnosis']) !== "" ) ? preg_replace( '/^\|\s/', "", strtoupper(trim($value['diagnosis'])) )  : "";
    $st = ( trim($value['site']) !== "" ) ? preg_replace( '/^\|\s/', "", strtoupper(trim($value['site'])) )  : "";

  $rtnthis .= <<<RTNTHIS
    <div class=transientItem data-selected='false' id="{$division}-{$value['bgs']}" onclick="selectThisSample(this.id);">

      <div class=itemElementHold >
        <div class=itemElementLabel>Specimen Category</div>
        <div class=itemElementData>{$value['specimencategory']}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Site</div>
        <div class=itemElementData>{$st}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Diagnosis</div>
        <div class=itemElementData>{$dx}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Preparation</div>
        <div class=itemElementData>{$value['preparation']}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Specimen Metric</div>
        <div class=itemElementData>{$value['metric']}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Hours Post-Excision</div>
        <div class=itemElementData>{$value['hourspost']}&nbsp;</div>
      </div>
      <div class=itemElementHold>
        <div class=itemElementLabel>Age/Race/Sex</div>
        <div class=itemElementData>{$value['ars']}&nbsp;</div>
      </div>
      {$pradd}
      <div class=itemElementHold>
        <div class=itemElementLabel>Identifier</div>
        <div class="identsml">{$smplid}&nbsp;</div>
      </div>
    </div>   

RTNTHIS;
  }
 $rtnthis .= "</div><div id=copyrightdsp> &#9400; Copyright Code and Content - CHTN Eastern Division/Perelman School of Medicine, University of Pennsylvania 2007-{$thisyear} </div>";

  return $rtnthis; 
}


class transientservices { 

  public $responseCode = 400;
  public $rtnData = "";

  function __construct() { 
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 
    if ( $nbrofargs <> 2 ) {
    } else {
      if ( self::checkServiceExists ( $args[0] ) ) {
        $a = json_decode( $args[1] , true );
        $payload['requestedCategory'] =  $a['specimencategory'];
        $payload['requestedSite'] =  $a['site'];
        $payload['requestedDiagnosis'] =  $a['diagnosis'];
        $payload['reuqester'] = 'EST';
        $payload['requestedDataPage'] = 0;
        $prep = json_decode( preg_replace('/checkbox\-/','',$a['preparation']));
        $p = array();
        foreach ( $prep as $pv ) {
          $p[] = $pv;
        }
        $payload['requestedPreparation'] =  $p;
        //TODO:  MAKE THIS INFORMATION PULL FROM THE DATABASE SO THAT IT IS DYNAMIC AND EXPANDABLE TO OTHER SERVICES
        $si = serverIdent;
        $sp = serverpw;
        $method = "POST";
        $url = "https://dev.chtneast.org/data-services/data-doers/transient-bank-search";
        $this->rtnData = callrestapi($method, $url, $si, $sp, json_encode( $payload ));
        $this->responseCode = 200; 
      }
    }
  }

  function checkServiceExists( $whichservice ) {
    $status = false;
    require(serverkeys . "/sspdo.zck");
    $serviceListSQL = "SELECT identifiercode FROM tidal.sys_registeredservices where activeind = 1 and identifiercode = :idcode";
    $serviceListRS = $conn->prepare( $serviceListSQL );
    $serviceListRS->execute(array( ':idcode' => $whichservice ) );
    if ( $serviceListRS->rowCount() > 0 ) {
      $status = true;
    } 
    return $status;
  }

}


