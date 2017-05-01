<?php
/** Welsh (Cymraeg)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
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
];

$magicWords = [
	'redirect'                  => [ '0', '#ail-cyfeirio', '#ailgyfeirio', '#REDIRECT' ],
	'notoc'                     => [ '0', '__DIMTAFLENCYNNWYS__', '__DIMRHESTRGYNNWYS__', '__DIMRHG__', '__NOTOC__' ],
	'noeditsection'             => [ '0', '__DIMADRANGOLYGU__', '__DIMGOLYGUADRAN__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MISCYFOES', 'MISCYFREDOL', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'ENWMISCYFOES', 'ENWMISCYFREDOL', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'GENENWMISCYFOES', 'CURRENTMONTHNAMEGEN' ],
	'currentday'                => [ '1', 'DYDDIADCYFOES', 'DYDDCYFREDOL', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'ENWDYDDCYFOES', 'ENWDYDDCYFREDOL', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'FLWYDDYNCYFOES', 'BLWYDDYNGYFREDOL', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'AMSERCYFOES', 'AMSERCYFREDOL', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'AWRGYFREDOL', 'CURRENTHOUR' ],
	'numberofarticles'          => [ '1', 'NIFEROERTHYGLAU', 'NIFERYRERTHYGLAU', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'NIFERYFFEILIAU', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NIFERYDEFNYDDWYR', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'NIFERYGOLYGIADAU', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'ENWTUDALEN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ENWTUDALENE', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'PARTH', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NAMESPACE', 'PARTHE', 'NAMESPACEE' ],
	'fullpagename'              => [ '1', 'ENWLLAWNTUDALEN', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ENWLLAWNTUDALENE', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'ENWISDUDALEN', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ENWISDUDALENE', 'SUBPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ENWTUDALENSGWRS', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ENWTUDALENSGWRSE', 'TALKPAGENAMEE' ],
	'img_thumbnail'             => [ '1', 'bawd', 'ewin_bawd', 'mân-lun', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'mân-lun=$1', 'bawd=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'de', 'right' ],
	'img_left'                  => [ '1', 'chwith', 'left' ],
	'img_none'                  => [ '1', 'dim', 'none' ],
	'img_center'                => [ '1', 'canol', 'center', 'centre' ],
	'img_page'                  => [ '1', 'tudalen=$1', 'tudalen_$1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'unionsyth', 'unionsyth=$1', 'unionsyth_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_sub'                   => [ '1', 'is', 'sub' ],
	'img_super'                 => [ '1', 'uwch', 'super', 'sup' ],
	'img_top'                   => [ '1', 'brig', 'top' ],
	'img_bottom'                => [ '1', 'gwaelod', 'godre', 'bottom' ],
	'server'                    => [ '0', 'GWEINYDD', 'SERVER' ],
	'servername'                => [ '0', 'ENW\'RGWEINYDD', 'SERVERNAME' ],
	'grammar'                   => [ '0', 'GRAMMAR', 'GRAMADEG', 'GRAMMAR:' ],
	'currentweek'               => [ '1', 'WYTHNOSGYFREDOL', 'CURRENTWEEK' ],
	'revisionid'                => [ '1', 'IDYGOLYGIAD', 'REVISIONID' ],
	'revisionday'               => [ '1', 'DIWRNODYGOLYGIAD', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DIWRNODYGOLYGIAD2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'MISYGOLYGIAD', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'BLWYDDYNYGOLYGIAD', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'STAMPAMSERYGOLYGIAD', 'REVISIONTIMESTAMP' ],
	'plural'                    => [ '0', 'LLUOSOG:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'URLLLAWN:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLLLAWNE:', 'FULLURLE:' ],
	'newsectionlink'            => [ '1', '_NEWSECTIONLINK_', '_CYSWLLTADRANNEWYDD_', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'GOLYGIADCYFREDOL', 'CURRENTVERSION' ],
	'currenttimestamp'          => [ '1', 'STAMPAMSERCYFREDOL', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'STAMPAMSERLLEOL', 'LOCALTIMESTAMP' ],
	'language'                  => [ '0', '#IAITH:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'IAITHYCYNNWYS', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'TUDALENNAUYNYPARTH:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'NIFERYGWEINYDDWYR', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'FFORMATIORHIF', 'FORMATNUM' ],
	'special'                   => [ '0', 'arbennig', 'special' ],
	'hiddencat'                 => [ '1', '_HIDDENCAT_', '_CATCUDD_', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'TUDALENNAUYNYCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'MAINTTUD', 'PAGESIZE' ],
];

$defaultDateFormat = 'dmy';

$bookstoreList = [
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "https://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "https://www.amazon.co.uk/exec/obidos/ISBN=$1"
];

$linkTrail = "/^([àáâèéêìíîïòóôûŵŷa-z]+)(.*)$/sDu";
