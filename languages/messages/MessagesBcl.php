<?php
/** Bikol Central (Bikol Central)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medio',
	NS_SPECIAL          => 'Espesyal',
	NS_TALK             => 'Olay',
	NS_USER             => 'Paragamit',
	NS_USER_TALK        => 'Olay_kan_paragamit',
	NS_PROJECT_TALK     => 'Olay_sa_$1',
	NS_FILE             => 'Ladawan',
	NS_FILE_TALK        => 'Olay_sa_ladawan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Olay_sa_MediaWiki',
	NS_TEMPLATE         => 'Plantilya',
	NS_TEMPLATE_TALK    => 'Olay_sa_plantilya',
	NS_HELP             => 'Tabang',
	NS_HELP_TALK        => 'Olay_sa_tabang',
	NS_CATEGORY         => 'Kategorya',
	NS_CATEGORY_TALK    => 'Olay_sa_kategorya',
);

$specialPageAliases = array(
	'Search'                    => array( 'Hanapon' ),
	'Upload'                    => array( 'Ikarga' ),
);

$magicWords = array(
	'currentmonth'              => array( '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'ALDAWNGONYAN', 'CURRENTDAY' ),
	'currentyear'               => array( '1', 'TAONNGONYAN', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'PANAHONNGONYAN', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ORASNGONYAN', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ),
	'localday'                  => array( '1', 'LOKALALDAW', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'LOKALALDAW2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'LOKALTAON', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'LOKALPANAHON', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'LOKALORAS', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'NGARANKANPAHINA', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NGARANESPASYO', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'OLAYESPASYO', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ),
	'fullpagename'              => array( '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ),
	'msg'                       => array( '0', 'MSH', 'MSG:' ),
	'img_right'                 => array( '1', 'too', 'right' ),
	'img_left'                  => array( '1', 'wala', 'left' ),
	'img_none'                  => array( '1', 'mayò', 'none' ),
	'img_center'                => array( '1', 'sentro', 'tangâ', 'center', 'centre' ),
	'img_framed'                => array( '1', 'nakakawadro', 'kwadro', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'daing kwadro', 'frameless' ),
	'img_page'                  => array( '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ),
	'localurl'                  => array( '0', 'LOKALURL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALURLE', 'LOCALURLE:' ),
	'currentweek'               => array( '1', 'SEMANANGONYAN', 'CURRENTWEEK' ),
	'localweek'                 => array( '1', 'LOKALSEMANA', 'LOCALWEEK' ),
	'plural'                    => array( '0', 'DAKOL:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'TODONGURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'TODONGURLE:', 'FULLURLE:' ),
	'language'                  => array( '0', '#TATARAMON', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'numberofadmins'            => array( '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ),
	'padleft'                   => array( '0', 'PADWALA', 'PADLEFT' ),
	'padright'                  => array( '0', 'PADTOO', 'PADRIGHT' ),
	'filepath'                  => array( '0', 'FILEDALAN', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'PAHINASOKOL', 'PAGESIZE' ),
);

