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
       //{"RESPONSECODE":200,"MESSAGE":[],"ITEMSFOUND":0,"DATA":{"rqston":"03\/03\/2020 17:12","speccat":"MALIGNANT","site":"THYROID","diagnosis":"CARCINOMA","rqststr":"{\"specimencategory\":\"MALIGNANT\",\"site\":\"thyroid\",\"diagnosis\":\"Carcinoma\",\"preparations\":\"[\\\"checkbox-PB\\\",\\\"checkbox-FROZEN\\\"]\"}"}}  
       $rqst = $rqstRS->fetch(PDO::FETCH_ASSOC); 
       $serviceListSQL = "SELECT identifiercode FROM tidal.sys_registeredservices where activeind = 1";
       $serviceListRS = $conn->prepare( $serviceListSQL );
       $serviceListRS->execute();

       while ( $r = $serviceListRS->fetch(PDO::FETCH_ASSOC)) { 
         $ts = new transientservices( $r['identifiercode'], $rqst['rqststr'] );
         if ( $ts->responseCode === 200 ) {
             $dta['sampledata'][ $r['identifiercode'] ] = $ts->rtnData; 
         }
       } 

       $dta[] = $rqst;
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
     $rqstCaptureSQL = "insert into tidal.searchrequest (srchrqstid, rqston, rqstby_user, rqstby_phpid, rqststr,speccat, site, diagnosis) values(:srchrqstid, now(), :rqstby_user, :rqstby_phpid, :rqststr, :speccat, :site, :diagnosis)"; 
     $rqstCaptureRS = $conn->prepare( $rqstCaptureSQL );
     $srchrqst = generateRandomString(15);
     if ( isset ( $_SESSION['loggedon']) ) { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => 'USER GOES HERE', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata, ':speccat' => strtoupper(trim($pdta['specimenCategory'])), ':site' => strtoupper(trim($pdta['site'])), ':diagnosis' => strtoupper(trim($pdta['diagnosis']))  ));
     } else { 
         $rqstCaptureRS->execute(array( ':srchrqstid' => $srchrqst, ':rqstby_user' => '', ':rqstby_phpid' => $sessid, ':rqststr' => $passdata, ':speccat' => strtoupper(trim($pdta['specimenCategory'])), ':site' => strtoupper(trim($pdta['site'])), ':diagnosis' => strtoupper(trim($pdta['diagnosis'])) ));
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



class transientservices { 

  public $responseCode = 400;
  public $rtnData = "";
  private $servicestub = ""; 
  private $username = ""; 


  function __construct() { 
      
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 
    
    if ( $nbrofargs <> 2 ) {
    } else {
      if ( self::checkServiceExists ( $args[0] ) ) {
        $this->rtnData = $args[1] ;    
        //{"specimencategory":"MALIGNANT","site":"thyroid","diagnosis":"carcinoma","preparation":"[\"checkbox-PB\",\"checkbox-FROZEN\"]"}
        //curl -X POST -k -H 'Authorization: Basic Y2h0bmVhc3Q6V1dwUVZYaHlWWGNySzNsSE5tSnBWblJTVVZKTk5FTjNZM0JrUkU5b1dTc3ZlR3Q1VHpkcE9XWm5OM2hGYldFd01uZFNOR3gwTVROVk9WTklOemRIZGc9PQ==' -i 'https://dev.chtneast.org/data-services/data-doers/transient-bank-search' --data '{"requester":"EST","requestedDataPage":0,"requestedSite":"THYROID","requestedDiagnosis":"CARCINOMA","requestedCategory":"MALIGNANT","requestedPreparation":["PB"]}'



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


