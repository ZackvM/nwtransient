<?php

class objgetter { 

  public $responseCode = 503;
  public $rtnData = "";

  function __construct() { 
    $args = func_get_args(); 
    $nbrofargs = func_num_args(); 

    if (trim($args[0]) === "") { 
    } else { 
      $request = explode("/", $args[0]); 
      if (trim($request[2]) === "") { 
        $this->responseCode = 400; 
        $this->rtnData = json_encode(array("MESSAGE" => "DATA NAME MISSING","ITEMSFOUND" => 0, "DATA" => ""));
      } else {  
        $obj = new objlisting(); 
        if (method_exists($obj, $request[2])) { 
          $funcName = trim($request[2]); 
          //FIRST ARGUMENT IN FUNCTION BELOW IS USUALLY A RECORD IDENTIFIER 
          $dataReturned = $obj->$funcName("{$request[3]}",$args[0]); 
          $this->responseCode = $dataReturned['statusCode']; 
          $this->rtnData = json_encode($dataReturned['data']);
        } else { 
          $this->responseCode = 404; 
          $this->rtnData = json_encode(array("MESSAGE" => "END-POINT FUNCTION NOT FOUND: {$request[2]}","ITEMSFOUND" => 0, "DATA" => ""));
        }
     }
    }
  }

}

class objlisting { 
   
  function globalmenu($request) {
    $rows = array(); 
    $gMenu = $request;
    //TO LOAD ALL METHODS IN A CLASS INTO AN ARRAY USE get_class_methods
    $gm = new globalMenus(); 
    if (method_exists($gm,$gMenu)) { 
      $SQL = $gm->$gMenu($rParts[3]);
      if (trim($SQL) !== "") {
        //RUN SQL - RETURN RESULTS
        require(serverkeys . "/sspdo.zck");
        $r = $conn->prepare($SQL); 
        $r->execute(); 
        $itemsFound = $r->rowCount();
        while ($rs = $r->fetch(PDO::FETCH_ASSOC)) { 
          $data[] = $rs;
        }
        $rows['statusCode'] = 200;
        $rows['data'] = array('MESSAGE' => '', 'ITEMSFOUND' => $itemsFound, 'DATA' => $data);
      } else { 
        $rows['statusCode'] = 503;
        $rows['data'] = array('MESSAGE' => 'NO SQL RETURNED', 'ITEMSFOUND' => 0,  'DATA' => '');
      }
    } else {
      $rows['statusCode'] = 404; 
      $rows['data'] = array('MESSAGE' => 'MENU NOT FOUND', 'ITEMSFOUND' => 0, 'DATA' => $request);
    }
    return $rows;
  }



}

class globalMenus {
    
  function vocabularyspecimencategory() { 
    return "SELECT distinct trim(ifnull(specimencategory,'')) as codevalue, trim(ifnull(specimencategory,'')) as menuvalue, 0 as useasdefault, trim(ifnull(specimencategory,'')) as lookupvalue FROM four.sys_master_menu_vocabulary where trim(ifnull(specimenCategory,'')) <> '' order by codevalue";
  }

  function vocabularypreparationmethods() {
    return "SELECT ucase(ifnull(mnu.menuvalue,'')) as codevalue, ifnull(mnu.dspvalue,'') as menuvalue, ifnull(mnu.useasdefault,0) as useasdefault, ucase(ifnull(mnu.menuvalue,'')) as lookupvalue FROM four.sys_master_menus mnu where mnu.menu = 'PREPMETHOD' and additionalInformation = 1 and mnu.dspInd = 1 order by mnu.dsporder";
    }



}




