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
//        $dp = new $request[2](); 

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
     $rqstCaptureSQL = "insert into webcapture.nwtransient_searchrequest (srchrqstid, rqston, rqstby_user, rqstby_phpid, rqststr) values(:srchrqstid, now(), :rqstby_user, :rqstby_phpid, :rqststr)"; 
     $rqstCaptureRS = $conn->prepare( $rqstCaptureSQL );
     $srchrqst = generateRandomString(15);
     if ( isset ( $_SESSION['loggedon']) ) { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => 'USER GOES HERE', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata  ));
     } else { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => '', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata  ));
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

