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

    function transientrequestmakerconfirm ( $request, $passdata ) { 
      $responseCode = 400;
      $rows = array();
      $msgArr = array(); 
      $errorInd = 0;
      $itemsfound = 0;
      require(serverkeys . "/sspdo.zck");
      $pdta = json_decode($passdata, true); 

//{"rqstname":"Zack von Menchhofen","rqstphn":"2159903771","rqsteml":"zackvm.zv@gmail.com","rqstcopy":"1","rqstinst":"University of Pennsylvania/School of Medicine","rqstinv":"INV3000","rqstnotyetinv":"0","rqstnotes":"These are notes about this request","rqstid":"mfCoO5VzcNHi6de"}   
      ( !array_key_exists( 'rqstname', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTNAME IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstphn', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTPHN IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqsteml', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTEML IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstinst', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTINST IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstcopy', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTCOPY IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstinv', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTINV IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstnotyetinv', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTNOTYETINV IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstnotes', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTNOTES IS MISSING FROM ARRAY.  FATAL ERROR")) : "";
      ( !array_key_exists( 'rqstid', $pdta ) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "RQSTNOTES IS MISSING FROM ARRAY.  FATAL ERROR")) : "";

      if ( $errorInd === 0 ) {   
        ( trim($pdta['rqstname']) === "" || trim($pdta['rqstname']) === "" || trim($pdta['rqsteml']) === "" || trim($pdta['rqstinst']) === "" || trim($pdta['rqstid']) === "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "All Fields marked with a astericks (*) are required.  Try again.")) : "";        
        if ( $errorInd === 0 ) { 
          ( !filter_var($pdta['rqsteml'], FILTER_VALIDATE_EMAIL) ) ? (list( $errorInd, $msgArr[] ) = array(1 , "The entered email doesn't seem to be valid.  Try again.")) : "";
          if ( $errorInd === 0 ) { 
            $updSQL = "update tidal.requestlist set rqstname = :rqstname, rqstphn = :rqstphn, rqsteml = :rqsteml, copyme = :copyme, rqstinstitution = :rqstinstitution, investigatorcode = :rqstinvestigatorcode, notyetinvind = :rqstnotyet, rqstnotes = :rqstnotes, rqston = now() where requestlistid = :rqstid";
            $updRS = $conn->prepare( $updSQL );
            $updRS->execute( array(
               ':rqstname' => trim($pdta['rqstname'])
              ,':rqstphn' => trim($pdta['rqstphn'])
              ,':rqsteml' => trim($pdta['rqsteml'])
              ,':copyme' => (int)$pdta['rqstcopy']
              ,':rqstinstitution' => trim($pdta['rqstinst'])
              ,':rqstinvestigatorcode' => trim($pdta['rqstinv'])
              ,':rqstnotyet' => (int)$pdta['rqstnotyetinv']
              ,':rqstnotes' => trim($pdta['rqstnotes'])
              ,':rqstid' => $pdta['rqstid']
            ));

            //CREATE EMAIL 
            //TODO:  MAKE THIS DYNAMIC AND MAKE IT DEPENDENT ON THE DIVISION OF THE BIOSAMPLES SELECTED
            //TODO:  MAKE A SEPERATE FUNCTION
            $at = genAppFiles; 
            $emaillist[] = "zacheryv@pennmedicine.upenn.edu";
            //$emaillist[] = "dfitzsim@pennmedicine.upenn.edu";
            if ( (int)$pdta['rqstcopy'] === 1 ) { $emaillist[] = $pdta['rqsteml']; } 
            $refid = strtoupper(generateRandomString(4)); 
            $sbj = "CHTN TRANSIENT INVENTORY REQUEST ({$refid})";
            $chtnimg = base64file("{$at}/publicobj/graphics/chtn-microscope-white.png","CHTNLogo","png",true);
            $emlNote = preg_replace('/\n/','<br>', preg_replace('/\n\n/','<p>',$pdta['rqstnotes'])); 

            $detSQL = "select sampleid, sampledefinition from tidal.requestlistdetails where rqstlistid = :rqstid and dspind = 1"; 
            $detRS = $conn->prepare( $detSQL ); 
            $detRS->execute(array(':rqstid' => $pdta['rqstid']));

            if ( $detRS->rowCount() > 0 ) { 
              $detTbl = "<table width=100%><tr><td colspan=5 style=\"font-family: arial; font-size: 12pt; font-weight: bold;\">Biosample(s) Requested: " . $detRS->rowCount() . "</td></tr><tr>";
              $cellCntr = 0;
              while ( $d = $detRS->fetch(PDO::FETCH_ASSOC)) {
                if ( $cellCntr === 4 ) {
                  $detTbl .= "</tr><tr>";
                  $cellCntr = 0;
                }

                $det = json_decode( $d['sampledefinition'], true);

                $dxd = trim($det['spc']);
                $dxd .= ( trim($dxd) !== "" ) ? " :: " . $det['ste'] : $det['ste'];
                $dxd .= ( trim($dxd) !== "" ) ? " :: " . $det['dxd'] : $det['dxd'];
                  
                $smpid = preg_replace('/^EST\-/','',$d['sampleid']);

                $detTbl .= <<<DETTBL
<td style="border: 1px solid rgba(0,32,133,1); "> 
  <table>
    <tr><td style="font-family: arial; font-size: 12pt; color: rgba(0,32,113,1); font-weight: bold;">Biosample ID</td></tr>
    <tr><td style="font-family: arial; font-size: 14pt; color: rgba(0,32,113,1);"> {$smpid} </td></tr>
    <tr><td style="font-family: arial; font-size: 12pt; color: rgba(0,32,113,1); font-weight: bold;">Preparation</td></tr>
    <tr><td style="font-family: arial; font-size: 14pt; color: rgba(0,32,113,1);"> {$det['prp']} </td></tr>
    <tr><td style="font-family: arial; font-size: 12pt; color: rgba(0,32,113,1); font-weight: bold;">Designation</td></tr>
    <tr><td style="font-family: arial; font-size: 14pt; color: rgba(0,32,113,1);"> {$dxd} </td></tr>
  </table> 
</td>
DETTBL;
                $cellCntr++;    
              }
              $detTbl .= "</tr></table>";
            }


            $emlbody = <<<EMAILBODY
<table width=100% style="border: 1px solid rgba(0,0,0,1);">
  <tr><td>
    <table width=100% cellpadding=0 cellspacing=0 style="background: rgba(0,32,113,1);">
      <tr><td style="padding: 5px 0 5px 6px;">{$chtnimg}</td>
      <td align=right style="color: rgba(255,255,255,1); padding: 5px 6px 5px 0; font-family: arial; font-size: 14pt;" ><b>Cooperative Human Tissue Network (CHTN)</b><br>Eastern Division<br>Perelman School of Medicine<br>University of Pennsylvania<br>3400 Spruce Street, 565 Dulles<br>Philadelphia, PA 19104<br>(215) 662-4570&nbsp;&nbsp;|&nbsp;&nbsp;chtnmail@uphs.upenn.edu     </td></tr>
    </table>

  </td></tr>

  <tr><td>
   <table width=100%><tr><td style="text-align: center; font-family: arial; font-size: 14pt; font-weight: bold; color: rgba(0,32,113,1); padding: 8px 0 10px 0; width: 100%;">DO NOT RESPOND THIS EMAIL IS UNMONITORED.</td></tr></table>
  </td></tr>

  <tr><td>
   <table width=100%><tr><td style="text-align: center; font-family: arial; font-size: 17pt; font-weight: bold; color: rgba(0,32,113,1); padding: 8px 0 0 0; border-bottom: 2px solid rgba(0,32,113,1); width: 100%;">CHTN Transient Inventory Request</td></tr></table>
  </td></tr>

  <tr><td>
    <center>
    <table>
     <tr>
       <td style="padding: 8px 10px;" valign=top><div style="font-size: 12pt; font-style: italic; color: rgba(0,32,113,1);font-family: arial;border-bottom: 1px solid rgba(0,32,113,1);">Requestor</div><div style="font-family: arial; font-size: 14pt; ">{$pdta['rqstname']}&nbsp;</div><div style="font-family: arial; font-size: 12pt; ">{$pdta['rqstinst']}&nbsp;</div></td> 
       <td style="padding: 8px 10px;" valign=top><div style="font-size: 12pt; font-style: italic; color: rgba(0,32,113,1);font-family:arial;border-bottom: 1px solid rgba(0,32,113,1);">Contact Phone</div><div style="font-family: arial; font-size: 14pt; ">{$pdta['rqstphn']}&nbsp;</div><div style="font-family: arial; font-size: 12pt; ">&nbsp;</div></td> 
       <td style="padding: 8px 10px;" valign=top><div style="font-size: 12pt; font-style: italic; color: rgba(0,32,113,1);font-family:arial;border-bottom: 1px solid rgba(0,32,113,1);">Contact Email</div><div style="font-family: arial; font-size: 14pt; ">{$pdta['rqsteml']}&nbsp;</div><div style="font-family: arial; font-size: 12pt; ">&nbsp;</div></td> 
       <td style="padding: 8px 10px;" valign=top><div style="font-size: 12pt; font-style: italic; color: rgba(0,32,113,1);font-family:arial;border-bottom: 1px solid rgba(0,32,113,1);">Investigator Code</div><div style="font-family: arial; font-size: 14pt; ">{$pdta['rqstinv']}&nbsp;</div><div style="font-family: arial; font-size: 12pt; ">&nbsp;</div></td> 
       <td style="padding: 8px 10px;" valign=top><div style="font-size: 12pt; font-style: italic; color: rgba(0,32,113,1);font-family:arial;border-bottom: 1px solid rgba(0,32,113,1);">Notes</div><div style="font-family: arial; font-size: 14pt; ">{$emlNote}&nbsp;</div></td> 
     </tr>
    </table>

  </td></tr>

  <tr><td>
   <table width=100%><tr><td style="text-align: center; font-family: arial; font-size: 17pt; font-weight: bold; color: rgba(0,32,113,1); padding: 8px 0 0 0; border-bottom: 2px solid rgba(0,32,113,1); width: 100%;">Biosamples Requested</td></tr></table>
  </td></tr>

  <tr><td>
    {$detTbl}
  </td></tr>

</table>

EMAILBODY;

            $emailSQL = "insert into serverControls.emailthis(towhoaddressarray, sbjtline, msgbody, htmlind, wheninput, bywho) values(:towhoaddressarray, :sbjtline, :msgbody, 1, now(), 'TRANSIENT-INVENTORY-APP')";
            $emailRS = $conn->prepare($emailSQL);
            $emailRS->execute(array(
                ':towhoaddressarray' => json_encode( $emaillist ), ':sbjtline' => $sbj, ':msgbody' => $emlbody
            ));

            $responseCode = 200;
          }
        }
      } 
      $msg = $msgArr;
      $rows['statusCode'] = $responseCode; 
      $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
      return $rows;               
    }

    function transientrequestremovebsitem ( $request, $passdata ) { 
     $responseCode = 400;
     $rows = array();
     $msgArr = array(); 
     $errorInd = 0;
     $itemsfound = 0;
     require(serverkeys . "/sspdo.zck");
     session_start(); 
     $sessid = session_id();      
     $pdta = json_decode($passdata, true); 
//{"requestedurl":"mfCoO5VzcNHi6de","bsitmid":"EST-79771T001","selectorind":0}

     $updSQL = "update tidal.requestlistdetails set dspind = :dspind where rqstlistid = :requestedurl and sampleid = :bsitemid";
     $updRS = $conn->prepare( $updSQL );
     $updRS->execute( array(':requestedurl' => $pdta['requestedurl'], ':bsitemid' => $pdta['bsitmid'], ':dspind' => $pdta['selectorind'] ));

     $dta = $updRS->rowCount();
     if ( $updRS->rowCount() > 0 ) { 
         $responseCode = 200;
     }

     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;               
    } 

   function buildtransientrequest ( $request, $passdata ) {
     $responseCode = 400;
     $rows = array();
     $msgArr = array(); 
     $errorInd = 0;
     $itemsfound = 0;
     require(serverkeys . "/sspdo.zck");
     $pdta = json_decode($passdata, true); 
     $at = genAppFiles;
     $tt = treeTop;
       
          //TODO:  Data Checks
        $paraSQL = "SELECT requestlistid, ifnull(rqston,'') as rqston FROM tidal.requestlist where requestlistid = :rlistID";
        $paraRS = $conn->prepare( $paraSQL );
        $paraRS->execute( array( ':rlistID' => $pdta['requestedurl'])); 

        ( $paraRS->rowCount() <> 1 ) ?  (list( $errorInd, $msgArr[] ) = array(1 , "ERROR!  NO REQUEST IDENTIFIER FOUND")) : "" ;


        if ( $errorInd === 0 ) {
         
            $para = $paraRS->fetch(PDO::FETCH_ASSOC);
            ( trim($para['rqston']) !== "" ) ? (list( $errorInd, $msgArr[] ) = array(1 , "The specified request id has already been submitted. <p><a href=\"{$tt}/new-search\">Click here to perform a new search</a> ")) : "" ;
                        
            
         if ( $errorInd === 0 ) {
           $detSQL = "SELECT sampleid, sampledefinition FROM tidal.requestlistdetails where rqstlistid = :rqstID"; 
           $detRS = $conn->prepare ( $detSQL ); 
           $detRS->execute(array(':rqstID' => $pdta['requestedurl'])); 
           $det  = $detRS->fetchAll( PDO::FETCH_ASSOC);
         
           foreach ( $det as $dk => $dv ) { 
             $itmB = json_decode( $dv['sampledefinition'], true);
             $dxd = "";             
             $dxd .= ( trim($itmB['spc']) !== "" ) ? "{$itmB['spc']}" : "";
             $dxd .= ( trim($itmB['ste']) !== "" ) ? " :: {$itmB['ste']}" : "";
             $dxd .= ( trim($itmB['dxd']) !== "" ) ? " :: {$itmB['dxd']}" : "";                         
             $rqstList .= "<div class=bsitem>"; 
                $rqstList .= "<div class=chkrHld><label class=chkBoxDsp><input type=\"checkbox\" checked=\"checked\" onchange=\"bsitemselector('{$pdta['requestedurl']}','{$dv['sampleid']}',this.checked);\"><span class=\"checkmark\"></span></label></div> <div class=dxddsp> <div class=dxddsplabel>Diagnosis Designation</div><div class=dxd>{$dxd}</div></div>"; 
                $rqstList .= "<div class=dxddsp><div class=dxddsplabel>Preparation</div><div class=dxd>{$itmB['prp']}</div></div>"; 
                $rqstList .= "<div class=bsitemid>{$dv['sampleid']}</div>";                          
             $rqstList .= "</div>";
           }
           $thisyear = date('Y');  
           $rtnPage = <<<RTNPAGE
<div id=requestForm>

    <div id=rFormSide>
      <div  class=introHead id=instructions>Fill out this form and a research specialist will be in contact with you about these biosamples.  All requested biosamples must match a valid and approved CHTN protocol request.  If you do not have one then the research specialist will assist you with that as well.  All biosamples requested are subject to verified availability.</div>  
      <div id=lineOne>

         <div class=dataElemHold>
            <div class=dataElemLbl>Your Name *</div>
            <div class=dataElem><input type=text id=fldYourName></div>
         </div>       

         <div class=dataElemHold>
            <div class=dataElemLbl>Your Phone Number *</div>
            <div class=dataElem><input type=text id=fldYourPhone></div>
         </div>              

         <div class=dataElemHold>
            <div class=dataElemLbl>Your Email *</div>
            <div class=dataElem><input type=text id=fldYourEmail></div>
            <div id=copymediv> <input type=text id=fldCopyMe value=1> <label class=chkBoxDsp id=copymelbl>Copy Me!&nbsp<input id=copymechkbox onchange=" byId('fldCopyMe').value = ( this.checked ) ? 1 : 0;" type="checkbox" checked="checked"><span class="checkmark"></span></label> </div>
         </div>          
       
     </div>

     <div id=linetwo>

         <div class=dataElemHold>
            <div class=dataElemLbl>Your Institution *</div>
            <div class=dataElem><input type=text id=fldYourInstitution></div>
         </div>              

         <div class=dataElemHold>
            <div class=dataElemLbl>Your CHTN Investigator ID</div>
            <div class=dataElem><input type=text id=fldYourInvestid></div>
            <div id=notyetdiv> <input type=text id=fldNotYetInv value=0> <label class=chkBoxDsp id=NINVEST>Not a CHTN investigator (Yet)&nbsp<input id=notyetchkbox onchange=" byId('fldNotYetInv').value = ( this.checked ) ? 1 : 0;"  type="checkbox"><span class="checkmark"></span></label> </div>
         </div>              

     </div>

     <div id=linethree>

         <div class=dataElemHold>
            <div class=dataElemLbl>Include this note with my request</div>
            <div class=dataElem><textarea id=fldYourNotes style="resize: none; box-sizing: border-box; font-family: Roboto; font-size: 1.8vh;color: rgba(0,32,113,1); padding: .7vh .3vw; border: 1px solid rgba(0,32,113,1); width: 53vw; height: 15vh; "></textarea></div>
         </div>              

     </div>

     <div id=linefour><center>

       <div id=btnSubmit class=zckBtn onclick="wrapuprequest();">Send Request</div>

     </div>

    </div>                              

    <div class=introHead>Biosamples Selected</div>
    <div id=rListSide>
       {$rqstList}        
    </div>


</div>               
   <div id=copyrightdsp> &#9400; Copyright Code and Content - CHTN Eastern Division/Perelman School of Medicine, University of Pennsylvania 2007-{$thisyear} </div>                              
RTNPAGE;
           $dta = $rtnPage;
           $itemsfound = 1;
           $responseCode = 200;
       }
         
        }
     
     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;               
   } 

   function transientrequestrequest ( $request, $passdata ) { 
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
//{"EBdd3bNH1KQFyYu":{"EST-87206T001":{"spc":"MALIGNANT","ste":"THYROID","dxd":"","prp":"LN-2 (Snap Frozen)"}

     //TODO:  Data Checks


     if ( $errorInd === 0 ) {

       foreach ( $pdta as $k => $v ) { 
         $comreq = $k; 
         $rqstid = generateRandomString(15);
         $rSQL = "insert into tidal.requestlist ( requestlistid, srchrqstid, rqstdate) values(:rqstid,:srchid, now())";
         $rRS = $conn->prepare( $rSQL );
         $rRS->execute( array( ':rqstid' => $rqstid, ':srchid' => $comreq ));
         $dSQL = "insert into tidal.requestlistdetails ( rqstlistid, sampleid, sampledefinition ) values ( :rqstid, :itmid, :sampledef )";
         $dRS = $conn->prepare( $dSQL );
         foreach ( $pdta[ $comreq ] as $crk => $crv ) { 
           $dRS->execute( array( ':rqstid' => $rqstid, ':itmid' => $crk, ':sampledef' => json_encode( $crv ) )); 
         }
       }

       $dta = $rqstid;
       $responseCode = 200;
     }

     $msg = $msgArr;
     $rows['statusCode'] = $responseCode; 
     $rows['data'] = array( 'RESPONSECODE' => $responseCode, 'MESSAGE' => $msg, 'ITEMSFOUND' => $itemsfound, 'DATA' => $dta);
     return $rows;        
   }

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
    <div class=transientItem data-selected='false' data-spc="{$value['specimencategory']}"  data-ste="{$st}" data-dxd="{$dx}" data-prp="{$value['preparation']}" id="{$division}-{$value['bgs']}" onclick="selectThisSample(this.id);">

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


