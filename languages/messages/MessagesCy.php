<?php
/** Welsh (Cymraeg)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'contentlanguage'           => [ '1', 'IAITHYCYNNWYS', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'DYDDIADCYFOES', 'DYDDCYFREDOL', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'ENWDYDDCYFOES', 'ENWDYDDCYFREDOL', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'AWRGYFREDOL', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MISCYFOES', 'MISCYFREDOL', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'ENWMISCYFOES', 'ENWMISCYFREDOL', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'GENENWMISCYFOES', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'AMSERCYFOES', 'AMSERCYFREDOL', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'STAMPAMSERCYFREDOL', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'GOLYGIADCYFREDOL', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'WYTHNOSGYFREDOL', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'FLWYDDYNCYFOES', 'BLWYDDYNGYFREDOL', 'CURRENTYEAR' ],
	'formatnum'                 => [ '0', 'FFORMATIORHIF', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'ENWLLAWNTUDALEN', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'ENWLLAWNTUDALENE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLLLAWN:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLLLAWNE:', 'FULLURLE:' ],
	'grammar'                   => [ '0', 'GRAMMAR', 'GRAMADEG', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '_HIDDENCAT_', '_CATCUDD_', '__HIDDENCAT__' ],
	'img_bottom'                => [ '1', 'gwaelod', 'godre', 'bottom' ],
	'img_center'                => [ '1', 'canol', 'center', 'centre' ],
	'img_left'                  => [ '1', 'chwith', 'left' ],
	'img_manualthumb'           => [ '1', 'mân-lun=$1', 'bawd=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_none'                  => [ '1', 'dim', 'none' ],
	'img_page'                  => [ '1', 'tudalen=$1', 'tudalen_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'de', 'right' ],
	'img_sub'                   => [ '1', 'is', 'sub' ],
	'img_super'                 => [ '1', 'uwch', 'super', 'sup' ],
	'img_thumbnail'             => [ '1', 'bawd', 'ewin_bawd', 'mân-lun', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'brig', 'top' ],
	'img_upright'               => [ '1', 'unionsyth', 'unionsyth=$1', 'unionsyth_$1', 'upright', 'upright=$1', 'upright $1' ],
	'language'                  => [ '0', '#IAITH', '#LANGUAGE' ],
	'localtimestamp'            => [ '1', 'STAMPAMSERLLEOL', 'LOCALTIMESTAMP' ],
	'namespace'                 => [ '1', 'PARTH', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NAMESPACE', 'PARTHE', 'NAMESPACEE' ],
	'newsectionlink'            => [ '1', '_NEWSECTIONLINK_', '_CYSWLLTADRANNEWYDD_', '__NEWSECTIONLINK__' ],
	'noeditsection'             => [ '0', '__DIMADRANGOLYGU__', '__DIMGOLYGUADRAN__', '__NOEDITSECTION__' ],
	'notoc'                     => [ '0', '__DIMTAFLENCYNNWYS__', '__DIMRHESTRGYNNWYS__', '__DIMRHG__', '__NOTOC__' ],
	'numberofadmins'            => [ '1', 'NIFERYGWEINYDDWYR', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NIFEROERTHYGLAU', 'NIFERYRERTHYGLAU', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NIFERYGOLYGIADAU', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NIFERYFFEILIAU', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NIFERYDEFNYDDWYR', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'ENWTUDALEN', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'ENWTUDALENE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'TUDALENNAUYNYCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesinnamespace'          => [ '1', 'TUDALENNAUYNYPARTH:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'MAINTTUD', 'PAGESIZE' ],
	'plural'                    => [ '0', 'LLUOSOG:', 'PLURAL:' ],
	'redirect'                  => [ '0', '#ail-cyfeirio', '#ailgyfeirio', '#REDIRECT' ],
	'revisionday'               => [ '1', 'DIWRNODYGOLYGIAD', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DIWRNODYGOLYGIAD2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'IDYGOLYGIAD', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'MISYGOLYGIAD', 'REVISIONMONTH' ],
	'revisiontimestamp'         => [ '1', 'STAMPAMSERYGOLYGIAD', 'REVISIONTIMESTAMP' ],
	'revisionyear'              => [ '1', 'BLWYDDYNYGOLYGIAD', 'REVISIONYEAR' ],
	'server'                    => [ '0', 'GWEINYDD', 'SERVER' ],
	'servername'                => [ '0', 'ENW\'RGWEINYDD', 'SERVERNAME' ],
	'special'                   => [ '0', 'arbennig', 'special' ],
	'subpagename'               => [ '1', 'ENWISDUDALEN', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ENWISDUDALENE', 'SUBPAGENAMEE' ],
	'talkpagename'              => [ '1', 'ENWTUDALENSGWRS', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'ENWTUDALENSGWRSE', 'TALKPAGENAMEE' ],
];

$defaultDateFormat = 'dmy';

$bookstoreList = [
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"Barnes & Noble" => "http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "https://www.amazon.com/exec/obidos/ISBN=$1",
	"Amazon.co.uk" => "https://www.amazon.co.uk/exec/obidos/ISBN=$1"
];

$linkTrail = "/^([àáâèéêìíîïòóôûŵŷa-z]+)(.*)$/sDu";
