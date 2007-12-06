<?php
/**
  * Kazakh (Қазақша)
  *
  * @addtogroup Language
  */

$fallback = 'kk-kz';

$linkTrail = '/^([a-zäçéğıïñöşüýа-яёәғіқңөұүһاٵبۆگعدەجزيكقلمنڭوٶپرستۋۇٷفحھچشىٸʺʹ“»]+)(.*)$/sDu';

$messages = array(
'linkprefix' => '/^(.*?)([a-zäçéğıïñöşüýа-яёәіңғүұқөһA-ZÄÇÉĞİÏÑÖŞÜÝА-ЯЁӘІҢҒҮҰҚӨҺاٵبۆگعدەجزيكقلمنڭوٶپرستۋۇٷفحھچشىٸʺʹ«„]+)$/sDu',

# Stylesheets
'common.css'   => ' /*
  * Мындағы CSS барлық безендіру мәнеріндерде қолданылады
  * Mındağı CSS barlıq bezendirw mänerinderde qoldanıladı
  * مىنداعى CSS بارلىق بەزەندٸرۋ مٵنەرٸندەردە قولدانىلادى
  */',
'monobook.css' => ' /*
  * Мындағы CSS «Дара кітап» (monobook) безендіру мәнерін пайдаланушыларға ықпал етеді
  * Mındağı CSS «Dara kitap» (monobook) bezendirw mänerin paýdalanwşılarğa ıqpal etedi
  * مىنداعى CSS «دارا كٸتاپ» (monobook) بەزەندٸرۋ مٵنەرٸن پايدالانۋشىلارعا ىقپال ەتەدٸ
  */',

# Scripts
'common.js'   => ' /*
  * Мындағы JavaScript әрқайсы бет қаралғанда барлық пайдаланушыларға жүктеледі.
  * Mındağı JavaScript ärqaýsı bet qaralğanda barlıq paýdalanwşılarğa jükteledi.
  * مىنداعى JavaScript ٵرقايسى بەت قارالعاندا بارلىق پايدالانۋشىلارعا جٷكتەلەدٸ.
  */
/* Workaround for language variants */
var languagevariant;
var direction; 
switch(wgUserLanguage){
    case "kk": 
         languagevariant = "kk";
         direction = "ltr";
         break;
    case "kk-kz": 
         languagevariant = "kk-Cyrl";
         direction = "ltr";
         break;
    case "kk-tr": 
         languagevariant = "kk-Latn";
         direction = "ltr";
         break;
    case "kk-cn": 
         // workaround for RTL ([[bugzilla:6756]]) and for [[bugzilla:02020]] & [[bugzilla:04295]]
         languagevariant = "kk-Arab";
         direction = "rtl";
         document.getElementsByTagName("body").className = "rtl";
         switch(skin){
             case "monobook":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
                 document.write(\'<style type="text/css">body{font-size: 75%; letter-spacing: 0.001em;} h3{font-size:110%;} h4 {font-size:100%;} h5{font-size:90%;} html > body div#content ol{clear: left;} ol{margin-left:2.4em; margin-right:2.4em;} ul{margin-left:1.5em; margin-right:1.5em;} .editsection{margin-right:5px; margin-left:0;}  #column-one{padding-top:0; margin-top:0;} #p-navigation{padding-top:0; margin-top:160px;} #catlinks{width:100%;} #userloginForm{float: right !important;} .pBody{-moz-border-radius-topleft: 0.5em; -moz-border-radius-topright: 0em !important;} .portlet h5{clear:right;}</style>\');
             break;
             case "chick":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
                 document.write(\'<style type="text/css">body{font-size: 75%; letter-spacing: 0.001em;} h3{font-size:110%;} h4 {font-size:100%;} h5{font-size:90%;} html > body div#content ol{clear: left;} ol{margin-left:2.4em; margin-right:2.4em;} ul{margin-left:1.5em; margin-right:1.5em;} .editsection{margin-right:5px; margin-left:0;} #column-one{clear:left !important; text-align:right; padding-top:0; margin-top:0;} #p-personal {float:right !important; text-align:right;} #userloginForm{float: right !important;} .pBody{-moz-border-radius-topleft: 0.5em; -moz-border-radius-topright: 0em !important;} .portlet h5{clear:right;}</style>\');
             break;
             case "simple":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
                 document.write(\'<style type="text/css">body{font-size: 75%; letter-spacing: 0.001em;} h3{font-size:110%;} h4 {font-size:100%;} h5{font-size:90%;} html > body div#content ol{clear: left;} ol{margin-left:2.4em; margin-right:2.4em;} ul{margin-left:1.5em; margin-right:1.5em;} .editsection{margin-right:5px; margin-left:0;} #column-one{float:right !important; margin-right: 0 !important; text-align:right; padding-top:0; margin-top:0;} #p-cactions, #p-personal {float:right !important; text-align:right;} #userloginForm{float: right !important;} .pBody{-moz-border-radius-topleft: 0.5em; -moz-border-radius-topright: 0em !important;} .portlet h5{clear:right;}</style>\');
             break;
             case "myskin":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/monobook/rtl.css">\');
                 document.write(\'<style type="text/css">body{font-size: 75%; letter-spacing: 0.001em;} h3{font-size:110%;} h4 {font-size:100%;} h5{font-size:90%;} html > body div#content ol{clear: left;} ol{margin-left:2.4em; margin-right:2.4em;} ul{margin-left:1.5em; margin-right:1.5em;} .editsection{margin-right:5px; margin-left:0;} #column-one{clear:left !important; text-align:right; padding-top:0; margin-top:0;} #userloginForm{float: right !important;} .pBody{-moz-border-radius-topleft: 0.5em; -moz-border-radius-topright: 0em !important;} .portlet h5{clear:right;}</style>\');
             break;
             case "cologneblue":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/quickbar-right.css">\');
                 document.write(\'<style type="text/css">#article {float: left !important; margin-left: 0 !important; margin-right:140px !important;} #quickbar {clear:left;}<style>\');
             break;
             case "nostalgia":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
                 document.write(\'<style type="text/css">#topbar a img {float: left !important;}<style>\');
             break;
             case "standard":
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/common_rtl.css">\');
                 document.write(\'<link rel="stylesheet" type="text/css" href="\'+stylepath+\'/common/quickbar-right.css">\');
                 document.write(\'<style type="text/css">#article {float: left !important; margin-left: 0 !important; margin-right:140px !important;} #quickbar {clear:left;} .bottom {text-align:right;}<style>\');
             break;
             default: 
         }
         document.write(\'<style type="text/css">div#shared-image-desc {direction: ltr;} input#wpUploadFile, input#wpDestFile, input#wpLicense {float: right;} .editsection {float: left !important;} .infobox {float: left !important; clear:left; } div.floatleft, table.floatleft {float:right !important; margin-left:0.5em !important; margin-right:0 !important; } div.floatright, table.floatright {clear:left; float:left !important; margin-left:0 !important; margin-right:0.5em !important;}</style>\');
         break;
     default: 
         languagevariant = "kk";
         direction = "ltr";
}

var htmlE=document.documentElement;
htmlE.setAttribute("lang",languagevariant);
htmlE.setAttribute("xml:lang",languagevariant);
htmlE.setAttribute("dir",direction);',
'monobook.js' => ' /*
  * Тыйылған; орнына [[{{ns:mediawiki}}:common.js]] қолданыңыз
  * Tıýılğan; ornına [[{{ns:mediawiki}}:common.js]] qoldanıñız
  * تىيىلعان; ورنىنا [[{{ns:mediawiki}}:common.js]] قولدانىڭىز 
  */',

# Variants for Kazakh language
'variantname-kk-tr' => 'Latın',
'variantname-kk-kz' => 'Кирил',
'variantname-kk-cn' => 'توتە',
'variantname-kk'    => 'disable',

);
