<?php
/** Welsh (Cymraeg)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arbennig',
	NS_TALK             => 'Sgwrs',
	NS_USER             => 'Defnyddiwr',
	NS_USER_TALK        => 'Sgwrs_Defnyddiwr',
	NS_PROJECT_TALK     => 'Sgwrs_$1',
	NS_FILE             => 'Delwedd',
	NS_FILE_TALK        => 'Sgwrs_Delwedd',
	NS_MEDIAWIKI        => 'MediaWici',
	NS_MEDIAWIKI_TALK   => 'Sgwrs_MediaWici',
	NS_TEMPLATE         => 'Nodyn',
	NS_TEMPLATE_TALK    => 'Sgwrs_Nodyn',
	NS_HELP             => 'Cymorth',
	NS_HELP_TALK        => 'Sgwrs_Cymorth',
	NS_CATEGORY         => 'Categori',
	NS_CATEGORY_TALK    => 'Sgwrs_Categori',
);

$magicWords = array(
	'redirect'                  => array( '0', '#ail-cyfeirio', '#ailgyfeirio', '#REDIRECT' ),
	'notoc'                     => array( '0', '__DIMTAFLENCYNNWYS__', '__DIMRHESTRGYNNWYS__', '__DIMRHG__', '__NOTOC__' ),
	'noeditsection'             => array( '0', '__DIMADRANGOLYGU__', '__DIMGOLYGUADRAN__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MISCYFOES', 'MISCYFREDOL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'ENWMISCYFOES', 'ENWMISCYFREDOL', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'GENENWMISCYFOES', 'CURRENTMONTHNAMEGEN' ),
	'currentday'                => array( '1', 'DYDDIADCYFOES', 'DYDDCYFREDOL', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'ENWDYDDCYFOES', 'ENWDYDDCYFREDOL', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'FLWYDDYNCYFOES', 'BLWYDDYNGYFREDOL', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AMSERCYFOES', 'AMSERCYFREDOL', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'AWRGYFREDOL', 'CURRENTHOUR' ),
	'numberofarticles'          => array( '1', 'NIFEROERTHYGLAU', 'NIFERYRERTHYGLAU', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NIFERYFFEILIAU', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NIFERYDEFNYDDWYR', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'NIFERYGOLYGIADAU', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'ENWTUDALEN', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ENWTUDALENE', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'PARTH', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NAMESPACE', 'PARTHE', 'NAMESPACEE' ),
	'fullpagename'              => array( '1', 'ENWLLAWNTUDALEN', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'ENWLLAWNTUDALENE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ENWISDUDALEN', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ENWISDUDALENE', 'SUBPAGENAMEE' ),
	'talkpagename'              => array( '1', 'ENWTUDALENSGWRS', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'ENWTUDALENSGWRSE', 'TALKPAGENAMEE' ),
	'img_thumbnail'             => array( '1', 'ewin_bawd', 'bawd', 'mân-lun', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'mân-lun=$1', 'bawd=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'de', 'right' ),
	'img_left'                  => array( '1', 'chwith', 'left' ),
	'img_none'                  => array( '1', 'dim', 'none' ),
	'img_center'                => array( '1', 'canol', 'center', 'centre' ),
	'img_page'                  => array( '1', 'tudalen=$1', 'tudalen_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'unionsyth', 'unionsyth=$1', 'unionsyth_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_sub'                   => array( '1', 'is', 'sub' ),
	'img_super'                 => array( '1', 'uwch', 'super', 'sup' ),
	'img_top'                   => array( '1', 'brig', 'top' ),
	'img_bottom'                => array( '1', 'gwaelod', 'godre', 'bottom' ),
	'server'                    => array( '0', 'GWEINYDD', 'SERVER' ),
	'servername'                => array( '0', 'ENW\'RGWEINYDD', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'GRAMMAR', 'GRAMADEG', 'GRAMMAR:' ),
	'currentweek'               => array( '1', 'WYTHNOSGYFREDOL', 'CURRENTWEEK' ),
	'revisionid'                => array( '1', 'IDYGOLYGIAD', 'REVISIONID' ),
	'revisionday'               => array( '1', 'DIWRNODYGOLYGIAD', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'DIWRNODYGOLYGIAD2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'MISYGOLYGIAD', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'BLWYDDYNYGOLYGIAD', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'STAMPAMSERYGOLYGIAD', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'LLUOSOG:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URLLLAWN:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'URLLLAWNE:', 'FULLURLE:' ),
	'newsectionlink'            => array( '1', '_NEWSECTIONLINK_', '_CYSWLLTADRANNEWYDD_', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'GOLYGIADCYFREDOL', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'STAMPAMSERCYFREDOL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'STAMPAMSERLLEOL', 'LOCALTIMESTAMP' ),
	'language'                  => array( '0', '#IAITH:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'IAITHYCYNNWYS', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'TUDALENNAUYNYPARTH:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'NIFERYGWEINYDDWYR', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FFORMATIORHIF', 'FORMATNUM' ),
	'special'                   => array( '0', 'arbennig', 'special' ),
	'hiddencat'                 => array( '1', '_HIDDENCAT_', '_CATCUDD_', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'TUDALENNAUYNYCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'MAINTTUD', 'PAGESIZE' ),
);

$defaultDateFormat = 'dmy';

$bookstoreList = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "http://www.amazon.co.uk/exec/obidos/ISBN=$1"
);

$linkTrail = "/^([àáâèéêìíîïòóôûŵŷa-z]+)(.*)$/sDu";

