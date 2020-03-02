<?php

class bldcontent {

    private $serveruser;
    private $serverapi;

    function __construct() { 
      require( genAppFiles . "/dataconn/serverid.zck");
      $this->serveruser = $serverid;
      $this->serverapi = $serverpw;
    }

    function newsearch ( $rqst ) { 
      $tt = treeTop;
      $ott = ownerTreeTop;
      $thisyear = date('Y');
      $at = genAppFiles;
      $dp = dataPath;

      $spccatdta = json_decode( callrestapi("GET","{$dp}/global-menu/vocabulary-specimen-category","","","") , true);
      $spccatmnu = "<div class=sbox><select id=fldCritSpcCat><option value=\"\"> - </option>";
      foreach ( $spccatdta['DATA'] as $k => $v ) { 
        $spccatmnu .= "<option value=\"{$v['codevalue']}\">{$v['menuvalue']}</option>";
      }
      $spccatmnu .= "</select></div>";

      $prepmdta = json_decode( callrestapi("GET","{$dp}/global-menu/vocabulary-preparation-methods","","","") , true);
      foreach ( $prepmdta['DATA'] as $k => $v ) { 
        $prepmmnu .= "<div class=chkBoxHolder><div class=chkBoxLbl>{$v['menuvalue']}</div><div class=\"checkboxThree\"><input type=\"checkbox\" class=\"checkboxThreeInput\" id=\"checkbox-{$v['codevalue']}\" /><label for=\"checkbox-{$v['codevalue']}\"></label></div></div>";
      }

      $rtnthis = <<<PGERTN
<div id=nwSrchScreenHolder>
   <div id=criteriaSide>

     <div id=newSearchInstructions>Instructions: Fill out the form below then click 'Submit'.  For a more indepth tutorial, click the 'Using Tidal' menu option above or click a field label. </div>

     <div id=criteriaLineOne>

       <div class=critDataLabel id=sectiontitleone>Designation</div>
       <div id=sugestionsOnDiv> <div class="chkBoxHolder cbhMargin"><div class=chkBoxLbl>Make Vocabulary Suggestions</div><div class="checkboxThree"><input type="checkbox" class="checkboxThreeInput" id="vocabSuggest" /><label for="vocabSuggest"></label></div></div> </div>       

       <div class=critElement>
         <a href="{$tt}/how-to-use-tidal#field-specimen-category" class=critDataLabel>Specimen Category</a>
         <div class=critDataElement>{$spccatmnu}</div>
       </div>

       <div class=critElement>
         <a href="{$tt}/how-to-use-tidal#field-site" class=critDataLabel>Site (Organ/Anatomic)</a>
         <div class=critDataElement><input type=text id=fldCritSite class=criteriaInputField><div class=suggestionBox id=suggestfldCritSite> </div></div>
       </div>
 
       <div class=critElement>
         <a href="{$tt}/how-to-use-tidal#field-diagnosis" class=critDataLabel>Diagnosis</a>
         <div class=critDataElement><input type=text id=fldCritDX class=criteriaInputField><div class=suggestionBox id=suggestfldCritDX> </div></div>
       </div>

    </div>

     <div id=criteriaLineTwo>

       <div class=critElement>
         <div class=critDataLabel id=sectiontitletwo>Preparation</div>
         <div class="critDataElement prepoptions">{$prepmmnu}</div>
       </div>
    
     </div>

    <div id=criteriaLineButtonBar>
      <div id=btnSubmit class=zckBtn>Submit</div>
    </div>


   </div>
   <div id=credentialsSide>
    DID YOU LOGIN?
   </div>
<div>
PGERTN;
      return $rtnthis;
    }

    function root ( $rqst ) { 
      $tt = treeTop;
      $ott = ownerTreeTop;
      $thisyear = date('Y');
      $at = genAppFiles; 
      $upenn = base64file("{$at}/publicobj/graphics/psom_logo_white.png","SOMALogo","png",true);
      $nci = base64file("{$at}/publicobj/graphics/nci-logo-full.png","NCILogo","png",true);

      $rtnthis = <<<PGERTN
<div id=introText>

  <div id=headline>CHTN's Transient Inventory Data Access Launchpad (TIDAL)</div>
  <div id=maintext>Thank you for using the Cooperative Human Tissue Network's (CHTN) <b>Transient Inventory Data Access Launchpad (TIDAL)</b> Application at the Eastern Division. The CHTN is a prospective procurement service that assists the scientific community in obtaining biosamples for research. Even though all projects utilizing the CHTN must be prospective procurement in nature, all CHTN divisions have transient inventory on hand. This tool allows CHTN Investigators to conduct searches on the transient inventories held at CHTN locations through the CHTN's federated database.  To receive biosamples from the CHTN you must have an active, accepted protocol with a CHTN division. If you are not already a CHTN Investigator, you can apply to the CHTN by downloading the <a href="https://www.chtn.org/d/chtn-application.pdf" target="_new">application here</a> or by contacting the CHTN (440) 477-5952.
<p>After conducting a TIDAL search, you can request biosample(s) in which you are interested (You must have an active protocol and the biosamples must be verified for use before receipt).  
</div>

</div>  

<div id=copyrightdsp> &#9400; Copyright Code and Content - CHTN Eastern Division/Perelman School of Medicine, University of Pennsylvania 2007-{$thisyear} </div>

<div id=pgeFooter>
  <div id=allMasterLinks>
   <a href="{$ott}" target="_new">CHTNEastern</a>
   <a href="https://scienceserver.chtneast.org" target="_new">CHTNEastern ScienceServer</a>
   <a href="https://transient.chtneast.org" target="_new">CHTNEastern Transient Inventory Search</a>
   <a href="{$ott}" target="_new">CHTNEastern Services</a>
   <a href="{$ott}" target="_new">Pay Processing Fee Invoice</a>
   <a href="{$ott}" target="_new">Contact CHTNEastern</a>
   <a href="{$ott}" target="_new">Papers, Publications &amp; Talks</a>
   <a href="{$ott}" target="_new">Meet the Staff</a>
   <a href="{$tt}" target="_new">CHTNMid-Atlantic</a>
   <a href="{$tt}" target="_new">CHTNMid-Western</a>
   <a href="{$tt}" target="_new">CHTNPediatric</a>
   <a href="{$tt}" target="_new">CHTNSouthern</a>
   <a href="{$tt}" target="_new">CHTNWestern</a>
   <a href="{$tt}" target="_new">CHTNNetwork</a>
   <a href="{$tt}" target="_new">CHTNTwitter</a>
   <a href="{$tt}" target="_new">Download CHTN Application</a>
   <a href="{$tt}" target="_new">National Cancer Institute (NCI)</a>
   <a href="{$tt}" target="_new">Perelman School of Medicine / University of Pennsylvania</a>
   <a href="{$tt}" target="_new">Pathology Feasibility Review Panel (PFRP) / University of Pennsylvania</a>

  </div>
  <div id=allMasterLogos align=right>
   <div>{$upenn}</div>
   <div>{$nci}</div>
  </div>
</div>

PGERTN;
      return $rtnthis;
    }    
     
}

