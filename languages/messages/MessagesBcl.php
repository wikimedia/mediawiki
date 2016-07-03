<?php
/** Bikol Central (Bikol Central)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
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
];

$specialPageAliases = [
	'Search'                    => [ 'Hanapon' ],
	'Upload'                    => [ 'Ikarga' ],
];

$magicWords = [
	'currentmonth'              => [ '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ],
	'currentday'                => [ '1', 'ALDAWNGONYAN', 'CURRENTDAY' ],
	'currentyear'               => [ '1', 'TAONNGONYAN', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'PANAHONNGONYAN', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ORASNGONYAN', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ],
	'localday'                  => [ '1', 'LOKALALDAW', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALALDAW2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'LOKALTAON', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'LOKALPANAHON', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'LOKALORAS', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'NGARANKANPAHINA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'NGARANESPASYO', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'OLAYESPASYO', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ],
	'fullpagename'              => [ '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ],
	'talkpagename'              => [ '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ],
	'msg'                       => [ '0', 'MSH', 'MSG:' ],
	'img_right'                 => [ '1', 'too', 'right' ],
	'img_left'                  => [ '1', 'wala', 'left' ],
	'img_none'                  => [ '1', 'mayò', 'none' ],
	'img_center'                => [ '1', 'sentro', 'tangâ', 'center', 'centre' ],
	'img_framed'                => [ '1', 'nakakawadro', 'kwadro', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'daing kwadro', 'frameless' ],
	'img_page'                  => [ '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ],
	'localurl'                  => [ '0', 'LOKALURL', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALURLE', 'LOCALURLE:' ],
	'currentweek'               => [ '1', 'SEMANANGONYAN', 'CURRENTWEEK' ],
	'localweek'                 => [ '1', 'LOKALSEMANA', 'LOCALWEEK' ],
	'plural'                    => [ '0', 'DAKOL:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'TODONGURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'TODONGURLE:', 'FULLURLE:' ],
	'language'                  => [ '0', '#TATARAMON', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'numberofadmins'            => [ '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ],
	'padleft'                   => [ '0', 'PADWALA', 'PADLEFT' ],
	'padright'                  => [ '0', 'PADTOO', 'PADRIGHT' ],
	'filepath'                  => [ '0', 'FILEDALAN', 'FILEPATH:' ],
	'hiddencat'                 => [ '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'PAHINASOKOL', 'PAGESIZE' ],
];

