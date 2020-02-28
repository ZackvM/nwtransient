<?php

class stylesheets {

  public $color_white = "255,255,255";
  public $color_black = "0,0,0";
  public $color_dark = "48,57,71";  //#303947
  public $color_dark1 = "0,32,113";
  public $color_highlight = "100,149,237";
  public $color_offwhite = "224, 224, 224";



  public $color_goldenrod = "156,118,22";

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

#universalbacker { position: fixed; top: 0; left: 0;  z-index: 100; background: rgba({$this->color_zackgrey},.8); height: 100vh; width: 100vw; display: none; } 
html { margin: 0; box-sizing: border-box; min-height: 100%; } 
body { margin: 0; font-family: Roboto;  box-sizing: border-box;  min-height: 100%; margin: 0; margin: 0; position: relative; }
* { box-sizing: border-box; } 

/*   TOUCH SCREEN   */
@media (pointer: coarse) {

  @media (orientation: portrait) {
    #universalTopBarHolder { width: 100vw; position: fixed; top: 0; left: 0; display: grid; grid-template-rows: 4vh 14vh 1vh; background: rgba({$this->color_white},1); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }
    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: 4.2vh; padding: 0 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: grid; grid-template-columns: 1vw 1vw 2vw; margin: 0 0 0 82vw;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: inline-block; text-decoration: none; outline: none; color: rgba({$this->color_white},1); -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; padding: .2vh 0 0 0; }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide i { font-size: 4vh; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { display: none; } 
    
    #chtntoplogo { height: 9vh; width: auto; } 

    #universalTopBarHolder #menuItems {display: grid; grid-template-rows: 8vh auto; grid-template-columns: repeat( 4, 1fr);  }
    #universalTopBarHolder #menuItems .logoholder { grid-row: 1; grid-column: span 4; padding: 1vh 42vw 0 42vw;  } 
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 1.6vh; color: rgba({$this->color_dark1},1); padding: 4vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .hideThisLink { display: none; }  

    {$coarseportrait} 


  }

  @media (orientation: landscape) {
  
  }

}

/*   MOUSE DRIVEN    */
@media (pointer: fine) {
  
  @media (orientation: landscape) {
    #universalTopBarHolder { width: 100vw; height: 13vh; position: fixed; top: 0; left: 0; display: grid; grid-template-columns: repeat(2, 1fr); background: rgba({$this->color_white},.4); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }

    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: 3.3vh; padding: 0 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: grid; grid-template-columns: 13vw 12vw 2vw; margin: 0 0 0 67vw;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: inline-block; text-decoration: none; outline: none; color: rgba({$this->color_white},1); -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; padding: .2vh 1vw 0 0; }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide:hover { color: rgba({$this->color_highlight},1);  }  
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide i { font-size: 2.8vh; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { text-decoration: none; outline: none; color: rgba({$this->color_white},1); font-family: 'Roboto Condensed'; font-size: 1.6vh; padding: .5vh 0 0 0; text-align: center; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; } 
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide:hover { color: rgba({$this->color_highlight},1); }  

    #chtntoplogo { height: 7vh; width: auto; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; } 
    #universalTopBarHolder #menuItems { grid-row: 2; margin: 0 10vw 1vh 6vw; display: grid; grid: 6vh / auto-flow;  -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; }
    #universalTopBarHolder #menuItems .logoholder { width: 4vw; }  
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 1.6vh; color: rgba({$this->color_dark1},1); padding: 3vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .menuLink:hover { color: rgba({$this->color_highlight},1); }  

    #universalTopBarHolder #menuSidePanel { margin: 1vh 6vw 1vh 9vw; } 

    #mainPageDiv { z-index: 5; position: relative; }

    {$finelandscape} 
  }

  @media (orientation: portrait) { 
    #universalTopBarHolder { width: 100vw; position: fixed; top: 0; left: 0; display: grid; grid-template-rows: 4vh 14vh 1vh; background: rgba({$this->color_white},1); z-index: 50; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; border-bottom: 1px solid rgba({$this->color_dark},0); }
    #universalTopBarHolder #topAppBar { grid-column: span 2; background: rgba({$this->color_dark},1); height: 2.2vh; padding: .1vh 2vw;  box-sizing: border-box;}
    #universalTopBarHolder #topAppBar #applicationListing { display: grid; grid-template-columns: 1vw 1vw 2vw; margin: 0 0 0 90vw;  }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide { display: inline-block; text-decoration: none; outline: none; color: rgba({$this->color_white},1); -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s; padding: .2vh 0 0 0; }
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide:hover { color: rgba({$this->color_highlight},1);  }  
    #universalTopBarHolder #topAppBar #applicationListing .menuLinkSide i { font-size: 2vh; }
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide { display: none; } 
    #universalTopBarHolder #topAppBar #applicationListing .appLinkSide:hover { color: rgba({$this->color_highlight},1); }  
    
    #chtntoplogo { height: 9vh; width: auto; } 

    #universalTopBarHolder #menuItems {display: grid; grid-template-rows: 8vh auto; grid-template-columns: repeat( 4, 1fr);  }
    #universalTopBarHolder #menuItems .logoholder { grid-row: 1; grid-column: span 4; padding: 1vh 42vw 0 42vw;  } 
    #universalTopBarHolder #menuItems .menuLink { display: inline-block; text-decoration: none; outline: none; font-family: 'Roboto'; font-size: 1.6vh; color: rgba({$this->color_dark1},1); padding: 4vh 0 0 0; text-align: center; text-transform: uppercase; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #universalTopBarHolder #menuItems .menuLink:hover { color: rgba({$this->color_highlight},1); }  
    #universalTopBarHolder #menuItems .hideThisLink { display: none; }  

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
    #swirlddsp { display: none;   }

    #chtnIntroHold { width: 100%; height: 59vh; position: relative; z-index: 2;  }
    #chtnIntroHold #chtnIntroText {  width: 88vw; margin: 29vh 6vw 0 6vw; }
    #chtnIntroHold #chtnIntroText .headerLine { font-family: 'Bowlby One SC'; font-size: 3.8vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0 ; text-align: center; }  
    #chtnIntroHold #chtnIntroText .headerSubLine { font-family: 'Roboto Condensed'; font-size: 3vh; color: rgba({$this->color_dark1},1); padding: 1vh 3vw 5vh 3vw; text-align: center;  } 
    #chtnIntroHold #chtnIntroText .readMoreBtnHold { text-align: center; padding: 8vh 0; }
    #chtnIntroHold #chtnIntroText .readMoreBtnHold a { text-decoration: none; outline: none; display: inline-block; width: 35vw; border: 1px solid rgba({$this->color_dark1},1); background: rgba({$this->color_dark},1); color: rgba({$this->color_white},1); box-sizing: border-box; padding: 1vh 0; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }


    #moreLinks { width: 100%; background: rgba({$this->color_dark1},1); padding: 0 22vw 3vh 22vw; display: grid; grid-template-columns: repeat( 1, 50vw); grid-gap: .2vw; -webkit-box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35); box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35);  position: relative; z-index: 2; }

    #moreLinks a { outline: none; text-decoration: none; } 
    #moreLinks .moreLinkLink { height: 27vh; margin: 2vh 0 2vh 0;   }
    #moreLinks .borderright { border-right: none;  }   
    #moreLinks .moreLinkLink .moreLinkIconHolder {  margin: 5vh 17vw 0 17vw; border-radius: 50%; height: 9vh; width: 18vw; text-align: center; padding: 1.8vh 0;   background: rgba({$this->color_goldenrod},1);  } 
    #moreLinks .moreLinkLink .moreLinkIconHolder .material-icons { font-size: 5vh; color: rgba({$this->color_white},1);  }
    #moreLinks .moreLinkLink .moreLinkTitle { font-family: 'Roboto Condensed'; font-size: 2vh; font-weight: bold; color: rgba({$this->color_white},1); text-transform: uppercase; text-align: center; padding: 3vh 0 1vh 0;  } 
    #moreLinks .moreLinkLink .moreLinkText { font-family: 'Roboto'; font-size: 1.8vh; color: rgba({$this->color_white},1); text-align: center; padding: 1vh 4vw 2vh 4vw; line-height: 1.5em;   }


    #aboutWriteup { background: rgba({$this->color_white},1); padding: 9vh 8vw 5vh 8vw; position: relative; z-index: 2; }
    #aboutWriteup .divlines { height: .3vh; width: 100%; background: rgba({$this->color_dark1},1); margin-top: 1vh; margin-bottom: 1vh; } 
    #aboutWriteup .headerText { font-family: Roboto; font-size: 1.7vh; text-align: justify; line-height: 1.6em; color: rgba({$this->color_dark},1); padding: 2vh 6vw; }  
    #aboutWriteup #abtTitle { font-family: 'Roboto'; font-size: 3vh; font-weight: bold; color: rgba({$this->color_dark},1); text-align: center; padding: 2vh 0 2vh 0; white-space: nowrap; } 
    #aboutWriteup .twoColumn { font-family: 'Roboto'; font-size: 2vh; text-align: justify; line-height: 3vh; }

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

    #chtnIntroHold { width: 100%; height: 59vh; position: relative; z-index: 2;  }
    #chtnIntroHold #chtnIntroText {  width: 88vw; margin: 29vh 6vw 0 6vw; }
    #chtnIntroHold #chtnIntroText .headerLine { font-family: 'Bowlby One SC'; font-size: 3.8vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0 ; text-align: center; }  
    #chtnIntroHold #chtnIntroText .headerSubLine { font-family: 'Roboto Condensed'; font-size: 3vh; color: rgba({$this->color_dark1},1); padding: 1vh 3vw 5vh 3vw; text-align: center;  } 
    #chtnIntroHold #chtnIntroText .readMoreBtnHold { text-align: center; padding: 8vh 0; }
    #chtnIntroHold #chtnIntroText .readMoreBtnHold a { text-decoration: none; outline: none; display: inline-block; width: 35vw; border: 1px solid rgba({$this->color_dark1},1); background: rgba({$this->color_dark},1); color: rgba({$this->color_white},1); box-sizing: border-box; padding: 1vh 0; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #chtnIntroHold #chtnIntroText .readMoreBtnHold a:hover { color: rgba({$this->color_dark1},1); background: rgba({$this->color_highlight},1); }  


    #moreLinks { width: 100%; background: rgba({$this->color_dark1},1); padding: 0 22vw 3vh 22vw; display: grid; grid-template-columns: repeat( 1, 50vw); grid-gap: .2vw; -webkit-box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35); box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35);  position: relative; z-index: 2; }

    #moreLinks a { outline: none; text-decoration: none; } 
    #moreLinks .moreLinkLink { height: 27vh; margin: 2vh 0 2vh 0;   }
    #moreLinks .borderright { border-right: none;  }   
    #moreLinks .moreLinkLink .moreLinkIconHolder {  margin: 5vh 17vw 0 17vw; border-radius: 50%; height: 9vh; width: 18vw; text-align: center; padding: 1.8vh 0;   background: rgba({$this->color_goldenrod},1);  } 
    #moreLinks .moreLinkLink .moreLinkIconHolder .material-icons { font-size: 5vh; color: rgba({$this->color_white},1);  }
    #moreLinks .moreLinkLink .moreLinkTitle { font-family: 'Roboto Condensed'; font-size: 2vh; font-weight: bold; color: rgba({$this->color_white},1); text-transform: uppercase; text-align: center; padding: 3vh 0 1vh 0;  } 
    #moreLinks .moreLinkLink .moreLinkText { font-family: 'Roboto'; font-size: 1.8vh; color: rgba({$this->color_white},1); text-align: center; padding: 1vh 4vw 2vh 4vw; line-height: 1.5em;   }


    #aboutWriteup { background: rgba({$this->color_white},1); padding: 9vh 8vw 5vh 8vw; position: relative; z-index: 2; }
    #aboutWriteup .divlines { height: .3vh; width: 100%; background: rgba({$this->color_dark1},1); margin-top: 1vh; margin-bottom: 1vh; } 
    #aboutWriteup .headerText { font-family: Roboto; font-size: 1.7vh; text-align: justify; line-height: 1.6em; color: rgba({$this->color_dark},1); padding: 2vh 6vw; }  
    #aboutWriteup #abtTitle { font-family: 'Roboto'; font-size: 3vh; font-weight: bold; color: rgba({$this->color_dark},1); text-align: center; padding: 2vh 0 2vh 0; white-space: nowrap; } 
    #aboutWriteup .twoColumn { font-family: 'Roboto'; font-size: 2vh; text-align: justify; line-height: 3vh; }

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

    #chtnIntroHold { width: 100%; height: 66vh; position: relative; z-index: 2;  }
    #chtnIntroHold #chtnIntroText {  width: 57vw; margin: 29vh 0 0 6vw; }
    #chtnIntroHold #chtnIntroText .headerLine { font-family: 'Bowlby One SC'; font-size: 5vh; color: rgba({$this->color_dark1},1); padding: 0 0 3vh 0 ; text-align: center; }  
    #chtnIntroHold #chtnIntroText .headerSubLine { font-family: 'Roboto Condensed'; font-size: 4vh; color: rgba({$this->color_dark1},1); padding: 1vh 3vw 5vh 3vw; text-align: center;  } 
    #chtnIntroHold #chtnIntroText .readMoreBtnHold { text-align: center; padding: 8vh 0; }
    #chtnIntroHold #chtnIntroText .readMoreBtnHold a { text-decoration: none; outline: none; display: inline-block; width: 9vw; border: 1px solid rgba({$this->color_dark1},1); background: rgba({$this->color_dark},1); color: rgba({$this->color_white},1); box-sizing: border-box; padding: 1vh 0; -webkit-transition-duration: 0.5s; transition-duration: 0.5s; transition: 0.5s;  }
    #chtnIntroHold #chtnIntroText .readMoreBtnHold a:hover { color: rgba({$this->color_dark1},1); background: rgba({$this->color_highlight},1); }  

    #moreLinks { background: rgba({$this->color_dark1},1); padding: 0 14vw 3vh 18vw; display: grid; grid-template-columns: repeat( 3, 20vw); grid-gap: .2vw; -webkit-box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35); box-shadow: 5px 7px 34px 12px rgba(0,0,0,0.35);  position: relative; z-index: 2; }
    #moreLinks a { outline: none; text-decoration: none; } 
    #moreLinks .moreLinkLink { height: 27vh; margin: 5vh 0 5vh 0;   }
    #moreLinks .borderright { border-right: 1px solid rgba({$this->color_white},1);  }   
    #moreLinks .moreLinkLink .moreLinkIconHolder {  margin: 5vh 7.5vw 0 7.5vw; border-radius: 50%; height: 9vh; width: 5vw; text-align: center; padding: 1.8vh 0;   background: rgba({$this->color_goldenrod},1);  } 
    #moreLinks .moreLinkLink .moreLinkIconHolder .material-icons { font-size: 5vh; color: rgba({$this->color_white},1);  }
    #moreLinks .moreLinkLink .moreLinkTitle { font-family: 'Roboto Condensed'; font-size: 2vh; font-weight: bold; color: rgba({$this->color_white},1); text-transform: uppercase; text-align: center; padding: 3vh 0 1vh 0;  } 
    #moreLinks .moreLinkLink .moreLinkText { font-family: 'Roboto'; font-size: 1.8vh; color: rgba({$this->color_white},1); text-align: center; padding: 1vh 4vw 2vh 4vw; line-height: 1.5em;   }

    #aboutWriteup { background: rgba({$this->color_white},1); padding: 9vh 12vw 5vh 12vw; position: relative; z-index: 2; }
    #aboutWriteup .divlines { height: .3vh; width: 100%; background: rgba({$this->color_dark1},1); margin-top: 1vh; margin-bottom: 1vh; } 
    #aboutWriteup .headerText { font-family: Roboto; font-size: 1.7vh; text-align: justify; line-height: 1.6em; color: rgba({$this->color_dark},1); padding: 2vh 6vw; }  
    #aboutWriteup #abtTitle { font-family: 'Roboto'; font-size: 4vh; font-weight: bold; color: rgba({$this->color_dark},1); text-align: center; padding: 2vh 0 2vh 0; white-space: nowrap; } 
    #aboutWriteup .twoColumn { font-family: 'Roboto'; font-size: 2vh; text-align: justify; line-height: 3vh; -webkit-column-count: 2;-moz-column-count: 2; column-count: 2; -webkit-column-gap: 3vw;-moz-column-gap: 3vw; column-gap: 3vw; -webkit-column-rule-style: solid; -moz-column-rule-style: solid; column-rule-style: solid;-webkit-column-rule-width: 1px; -moz-column-rule-width: 1px; column-rule-width: 1px;-webkit-column-rule-color: rgba(48,57,71,1) ; -moz-column-rule-color: rgba(48,57,71,1) ; column-rule-color: rgba(48,57,71,1) ; }  

    #copyrightdsp { width: 100%; text-align: center; font-family: 'Roboto Condensed'; font-size: 1.1vh; color: rgba({$this->color_dark},1); background: rgba({$this->color_white},1); position: relative; z-index: 2; padding: 0 0 2vh 0; } 
    
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

