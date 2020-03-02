<?php

class stylesheets {

  public $color_white = "255,255,255";
  public $color_black = "0,0,0";
  public $color_dark = "48,57,71";  //#303947
  public $color_dark1 = "0,32,113";
  public $color_highlight = "100,149,237";
  public $color_offwhite = "224, 224, 224";
  public $color_grey = "160,160,160";

  public $color_goldenrod = "156,118,22";
  public $color_lamber = "255,248,225";
  public $color_bred = "237, 35, 0";
  public $color_darkgreen = "0, 112, 13";
  public $color_zackgrey = "48,57,71";  //#303947 

  //Z-INDEX:  0-30 - Base - Level // BUTTON BAR 50-70 // 100 Black wait screen // 100+ dialogs above wait screen 

  function globalstyles( $rqst   ) {

      $pgfinelandscape = "{$rqst}finelandscape";
      $pgfineportrait = "{$rqst}coarseportrait";
      $pgcoarseportrait = "{$rqst}coarseportrait";
      $finelandscape = ( method_exists( __CLASS__, $pgfinelandscape) ) ? $this->$pgfinelandscape() : "";
      $fineportrait = ( method_exists( __CLASS__, $pgfineportrait) ) ? $this->$pgfineportrait() : "";
      $coarseportrait = ( method_exists( __CLASS__, $pgcoarseportrait) ) ? $this->$pgcoarseportrait() : "";

    $rtnthis = <<<STYLESHEET

/* DEFAULT LAYOUT ... ({$rqst})    */
@import url('https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed|Bowlby+One+SC');
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');

#universalbacker { position: relative; top: 0; left: 0;  z-index: 100; background: rgba({$this->color_zackgrey},.8); height: 100vh; width: 100vw; display: none; } 
html { margin: 0; box-sizing: border-box; min-height: 100%; } 
body { margin: 0; font-family: Roboto;  box-sizing: border-box;  min-height: 100%; margin: 0; margin: 0; position: relative; }
* { box-sizing: border-box; } 



/*   TOUCH SCREEN   */
@media (pointer: coarse) {

  @media (orientation: portrait) {
    #universalTopBarHolder { width: 100vw; position: fixed; top: 0; left: 0; display: grid; grid-template-rows: 4vh 14vh 1vh; background: rgba({$this->color_white},1); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }
    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: 1vh; padding: 0 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: none;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: none; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { display: none; } 
    
    #chtntoplogo { height: 9vh; width: auto; } 

    #universalTopBarHolder #menuItems {display: grid; grid-template-rows: 8vh auto; grid-template-columns: repeat( 4, 1fr);  }
    #universalTopBarHolder #menuItems .logoholder { grid-row: 1; grid-column: span 4; padding: 1vh 42vw 0 42vw;  } 
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 1.6vh; color: rgba({$this->color_dark1},1); padding: 4vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .hideThisLink { display: none; }  

    #mainPageDiv { position: relative; z-index: 2;  }

    {$coarseportrait} 

  }

  @media (orientation: landscape) {
  
  }

}

/*   MOUSE DRIVEN    */
@media (pointer: fine) {
  
  @media (orientation: landscape) {
    #universalTopBarHolder { width: 100vw; height: 13vh; position: fixed; top: 0; left: 0; display: grid; grid-template-columns: repeat(2, 1fr); background: rgba({$this->color_white},.4); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }

    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: .5vh; padding: 0 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: none;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: none; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { display: none; } 

    #chtntoplogo { height: 7vh; width: auto; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; } 
    #universalTopBarHolder #menuItems { grid-row: 2; margin: 0 6vw 1vh 6vw; display: grid; grid: 6vh / auto-flow;  -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; }
    #universalTopBarHolder #menuItems .logoholder { width: 4vw; }  
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 2.1vh; color: rgba({$this->color_dark1},1); padding: 3vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .menuLink:hover { color: rgba({$this->color_highlight},1); }  

    #universalTopBarHolder #menuSidePanel { margin: 1vh 6vw 1vh 9vw; } 

    #mainPageDiv { position: relative; z-index: 2;  }

    {$finelandscape} 
  }

  @media (orientation: portrait) { 
    #universalTopBarHolder { width: 100vw; position: fixed; top: 0; left: 0; display: grid; grid-template-rows: 4vh 14vh 1vh; background: rgba({$this->color_white},1); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }
    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: 2.2vh; padding: .1vh 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: grid; grid-template-columns: 1vw 1vw 2vw; margin: 0 0 0 90vw;  }
    #universalTopBarHolder #topAppBar #applicationListing { display: none;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: none; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { display: none; } 
    
    #chtntoplogo { height: 9vh; width: auto; } 

    #universalTopBarHolder #menuItems {display: grid; grid-template-rows: 8vh auto; grid-template-columns: repeat( 4, 1fr);  }
    #universalTopBarHolder #menuItems .logoholder { grid-row: 1; grid-column: span 4; padding: 1vh 42vw 0 42vw;  } 
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 1.6vh; color: rgba({$this->color_dark1},1); padding: 4vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .menuLink:hover { color: rgba({$this->color_highlight},1); }  
    #universalTopBarHolder #menuItems .hideThisLink { display: none; }  

    #mainPageDiv { position: relative; z-index: 2;  }

    {$fineportrait} 

  }

}

STYLESHEET;
    return $rtnthis;
  }

  function rootcoarseportrait() { 
    $at = genAppFiles; 

    $rtnthis = <<<RTNTHIS

    body { background: rgba({$this->color_white},1); } 
    
    #introText { margin: 25vh 6vw 3vh 6vw; }
    #introText #headline { font-family: 'Bowlby One SC'; font-size: 3vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0;    }  
    #introText #maintext { font-family: Roboto; font-size: 1.8vh;  color: rgba({$this->color_dark1},1); line-height: 1.8em; text-align: justify; padding: 0 0 10vh 0; } 


    #copyrightdsp { width: 100%; text-align: center; font-family: 'Roboto Condensed'; font-size: 1.3vh; color: rgba({$this->color_dark},1); background: rgba({$this->color_white},1); position: relative; z-index: 2; padding: 0 8vw 2vh 8vw; } 

    #pgeFooter { background: rgba({$this->color_dark1},1); display: grid; position: relative; z-index: 2; box-sizing: border-box; }
    #pgeFooter #allMasterLinks { margin: 3vh 0 3vh 6vw; display: grid; grid-template-columns: repeat( 1, 85vw); grid-gap: .2vw; }
    #pgeFooter #allMasterLinks a { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto Condensed'; font-size: 1.4vh; color: rgba({$this->color_white},1); border: 1px solid rgba({$this->color_highlight},1); background: rgba({$this->color_dark},1); padding: .5vh 0 .5vh .3vw; }

    #CHTNSSLink { display: none; } 

    #pgeFooter #allMasterLogos { margin: 3vh 6vw 0 6vw; }
    #SOMALogo { width: 50vw; height: auto; padding: 0 0vw 0 10vw; }  
    #NCILogo { width: 80vw; height: auto; margin-top: 2vh; margin-bottom: 5vh; }  

RTNTHIS;

    return $rtnthis;
  }

  function rootfineportrait() { 
    $at = genAppFiles; 

    $rtnthis = <<<RTNTHIS

    body { background: rgba({$this->color_white},1); } 
    #swirlddsp { display: none;   }

    #introText { margin: 25vh 6vw 3vh 6vw; }
    #introText #headline { font-family: 'Bowlby One SC'; font-size: 3vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0;    }  
    #introText #maintext { font-family: Roboto; font-size: 1.8vh;  color: rgba({$this->color_dark1},1); line-height: 1.8em; text-align: justify; padding: 0 0 10vh 0; } 

    #copyrightdsp { width: 100%; text-align: center; font-family: 'Roboto Condensed'; font-size: 1.3vh; color: rgba({$this->color_dark},1); background: rgba({$this->color_white},1); position: relative; z-index: 2; padding: 0 8vw 2vh 8vw; } 

    #pgeFooter { background: rgba({$this->color_dark1},1); display: grid; position: relative; z-index: 2; box-sizing: border-box; }
    #pgeFooter #allMasterLinks { margin: 3vh 0 3vh 6vw; display: grid; grid-template-columns: repeat( 1, 85vw); grid-gap: .2vw; }
    #pgeFooter #allMasterLinks a { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto Condensed'; font-size: 1.4vh; color: rgba({$this->color_white},1); border: 1px solid rgba({$this->color_highlight},1); background: rgba({$this->color_dark},1); padding: .5vh 0 .5vh .3vw; }
    #pgeFooter #allMasterLinks a:hover { color: rgba({$this->color_dark1},1); background: rgba({$this->color_highlight},1);   }

    #CHTNSSLink { display: none; } 

    #pgeFooter #allMasterLogos { margin: 3vh 6vw 0 6vw; }
    #SOMALogo { width: 50vw; height: auto; padding: 0 0vw 0 10vw; }  
    #NCILogo { width: 80vw; height: auto; margin-top: 2vh; margin-bottom: 5vh; }  


RTNTHIS;

    return $rtnthis;
  }

  function rootfinelandscape() { 
    $at = genAppFiles; 
    $chtnmicroscope = base64file("{$at}/publicobj/graphics/bgmicrobig.png","background","bgurl",true);

    $rtnthis = <<<RTNTHIS
    body { background: rgba({$this->color_white},1); } 
    #swirlddsp { width: 100%; height: 100%; position: fixed; top: 0; left: 0; z-index: 1; background-repeat: no-repeat; background-attachment: fixed; background: {$chtnmicroscope} no-repeat bottom right; background-size: 68vh; background-position: right 8vw bottom 5vh;  }
    #introText { margin: 25vh 35vw 3vh 4vw; }
    #introText #headline { font-family: 'Bowlby One SC'; font-size: 3vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0;    }  
    #introText #maintext { font-family: Roboto; font-size: 2vh;  color: rgba({$this->color_dark1},1); line-height: 1.9em; text-align: justify; padding: 0 0 18vh 0; } 
    #copyrightdsp { width: 100%; text-align: center; font-family: 'Roboto Condensed'; font-size: 1.1vh; color: rgba({$this->color_dark},1); background: rgba({$this->color_white},0); position: relative; z-index: 2; padding: 0 0 2vh 0; } 
    #pgeFooter { background: rgba({$this->color_dark1},1); display: grid; grid-template-columns: 69vw 29vw; position: relative; z-index: 2; box-sizing: border-box; }
    #pgeFooter #allMasterLinks { margin: 3vh 0 3vh 6vw; display: grid; grid-template-columns: repeat( 5, 12vw); grid-gap: .2vw; }
    #pgeFooter #allMasterLinks a { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto Condensed'; font-size: 1.4vh; color: rgba({$this->color_white},1); border: 1px solid rgba({$this->color_highlight},1); background: rgba({$this->color_dark},1); padding: .5vh 0 .5vh .3vw;  -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;}
    #pgeFooter #allMasterLinks a:hover { color: rgba({$this->color_dark1},1); background: rgba({$this->color_highlight},1);   }
    #pgeFooter #allMasterLogos { margin: 3vh 4vw 0 0; }
    #SOMALogo { width: 13vw; height: auto; }  
    #NCILogo { width: 15vw; height: auto; margin-top: 2vh; }  
RTNTHIS;

    return $rtnthis;
  }

  
  function searchresultsfinelandscape() { 
        require(serverkeys . "/sspdo.zck");
        $newrqst = explode("/",str_replace("-","", $_SERVER['REQUEST_URI']));     
        $paraSQL = "SELECT srchrqstid, rqston, rqststr FROM webcapture.nwtransient_searchrequest where srchrqstid = :srchrqstid";
        $paraRS = $conn->prepare( $paraSQL );
        $paraRS->execute( array( ':srchrqstid' => $newrqst[2])); 
        
        if ( $paraRS->rowCount() < 1 ) {  //BAD REQUEST ID
        } else {
    $rtnthis = <<<RTNTHIS
  
           
#waiterDialog {    } 
   
            
RTNTHIS;
        }
    return $rtnthis;
}
  
  
  function newsearchfinelandscape() { 

    $rtnthis = <<<RTNTHIS
    #nwSrchScreenHolder { margin: 14vh 1vw 0 1vw; display: grid; grid-template-columns: 68vw 29vw; grid-gap: .3vw; }
    #criteriaSide {  } 

    #newSearchInstructions { font-size: 2vh; padding: 2vh 0 3vh 0;   } 

    input {  box-sizing: border-box; font-family: Roboto; font-size: 1.8vh;color: rgba({$this->color_zackgrey},1); padding: .7vh .3vw; border: 1px solid rgba({$this->color_dark1},1); text-transform: uppercase; }
    input:focus, input:active {background: rgba({$this->color_lamber},.5); border: 1px solid rgba({$this->color_dblue},.5);  outline: none;  }

    .checkboxThree { width: 5vw; height: 3vh; background: rgba( {$this->color_lamber}, 1 ); margin: 0; border-radius: 2px; position: relative; border: 1px solid rgba({$this->color_zackgrey},1); }
    .checkboxThree:before { content: 'Yes'; position: absolute; top: .7vh; left: .5vw; height: .1vh; color: rgba({$this->color_darkgreen},1 ); font-family: 'Roboto'; font-size: 1.4vh; }
    .checkboxThree:after  { content: 'No'; position: absolute; top: .7vh; left: 3.2vw; height: .1vh; color: rgba({$this->color_bred},1); font-family: 'Roboto'; font-size: 1.4vh; }
    .checkboxThree label  { display: block; width: 2vw; height: 1.7vh; border-radius: 50px; transition: all .5s ease; cursor: pointer; position: absolute; top: .7vh; z-index: 1; left: .5vw; background: rgba( {$this->color_zackgrey}, 1  ); }
    .checkboxThree input[type=checkbox]:checked + label { left: 2.3vw; background: rgba( {$this->color_darkgreen}, 1 ); }
    .checkboxThree .checkboxThreeInput { visibility: hidden; }

    #criteriaInstructions { padding: 0 0 3vh 0; font-family: Roboto; font-size: 1.8vh;   } 

    #criteriaLineOne { display: grid; grid-template-columns: 11vw 25vw 32vw; grid-gap: .2vw;      }
    #criteriaLineOne #sectiontitleone { grid-column: span 3; padding: 0 0 0 0; width: 67vw; font-family: 'Roboto'; font-size: 3vh; color: rgba({$this->color_highlight},1); border-bottom: 1px solid rgba({$this->color_highlight},1);  }
    #criteriaLineOne #sugestionsOnDiv { grid-column: span 3; margin-bottom: 2vh; display: none;} 
    #criteriaLineTwo { width: 33vw; margin: 0 0 0 0; }
    #criteriaLineTwo #sectiontitletwo {  padding: 5vh 0 0 0; width: 67vw; font-family: 'Roboto'; font-size: 3vh; color: rgba({$this->color_highlight},1); border-bottom: 1px solid rgba({$this->color_highlight},1); margin-bottom: 2vh;} 
    #criteriaLineButtonBar { padding: 6vh 30vw; } 

    .zckBtn { display: block; border: 1px solid rgba({$this->color_dark1},1); width: 5vw; text-align: center; padding: .8vh .5vw; background: rgba({$this->color_dark1},1); color: rgba({$this->color_white},1); font-family: 'Roboto'; font-size: 1.4vh; font-weight: bold; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; }
    .zckBtn:hover { cursor: pointer; background: rgba({$this->color_highlight},1); }

    .critDataElement { position: relative; } 
    .suggestionBox { position: absolute; height: 13vh; overflow: auto; padding: .2vh .2vw; border: 1px solid rgba({$this->color_dark1},1); width: 100%; background: rgba({$this->color_white},1); margin-top: 2px; display: none; z-index: 5 } 

    .prepoptions { display: grid; grid-template-columns: repeat( 6, 5vw); grid-gap: .8vw;  } 
    .chkBoxHolder { width: 7vw; }
    .cbhMargin { margin: 0 3vw 0 0; display: grid; grid-template-columns: 8vw 5vw; grid-gap: .1vw; }
    .chkBoxLbl { font-family: Roboto; font-size: 1.5vh; color: rgba({$this->color_dark1},1); font-weight: bold; padding: .3vh 0;   }  

    select { background-color: rgba({$this->color_white},1); color: rgba({$this->color_dark1},1); padding: .6vh .3vw; width: 20vw; border: none; font-size: 1.8vh;  -webkit-appearance: button; appearance: button; outline: none; border: 1px solid rgba({$this->color_dark1},1); }
    .sbox::before { font-family: 'Roboto Condensed'; position: absolute; top: 0; right: 0; width: 20%; height: 100%; text-align: center; font-size: 1.2vh; line-height: 45px; color: rgba(255, 255, 255, 0.5); background-color: rgba(255, 255, 255, 0.1); pointer-events: none; }
    .sbox:hover::before { color: rgba(255, 255, 255, 0.6); background-color: rgba(255, 255, 255, 0.2); }
    .sbox select option { padding: .5vh .3vw; }  

    .critDataLabel { font-family: 'Roboto'; font-size: 1.5vh; font-weight: bold; text-decoration: none; color: rgba({$this->color_dark1},1);    } 

    #fldCritSpcCat { width: 11vw;  }
    #fldCritSite { width: 25vw;  }
    #suggestionfldCritSite { width: 25vw; } 
    #fldCritDX { width: 30vw; } 
    #suggestfldCritDX { width: 30vw; } 

    .vocabularyCount { font-size: 1.1vh; text-align: right; }
    .vocabularyDsp { font-size: 1.4vh; color: rgba({$this->color_dark1},1); padding: 8px 5px;   }
    .vocabularyDsp:nth-child(odd) { background: rgba({$this->color_grey},.2); }




    #credentialsSide { border-left: 1px solid #000; }
 

RTNTHIS;
    return $rtnthis;
  }



}

/*
 *   public $color_zackgrey = "48,57,71";  //#303947
  public $color_darkblue = "0,32,113";
  public $color_darkblue1 = "31, 53, 110";
  public $color_greyblue = "101,108,163";
  public $color_lightamber = "255,248,225"; 
  public $color_cornflowerblue = "100,149,237";
  public $color_lightblue = "84,114,211";
  public $color_deepred = "206,3,0";
  public $color_neongreen = "57,255,20";
  public $color_goldenrod = "156,118,22";
  public $color_darkgrey = "145,145,145";
  public $color_darkgreen = "0, 112, 13";

  public $color_zack_offwhite = "240,240,240";
  public $color_dark = "6, 30, 92";
  public $color_medium = "38, 75, 145";
  public $color_dark_contrast = "168, 161, 71";
  public $color_accent = "120, 172, 255";
  public $color_light = "240, 244, 255";
  public $color_menchhoffenred = "165, 16, 20";

  public $color_highlight = "219, 232, 255";
  public $color_green = "0, 138, 46";

  public $color_zackdarkblue = "0,32,113";

 */

