<?php
/** Bikol Central (Bikol Central)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Search'                    => [ 'Hanapon' ],
	'Upload'                    => [ 'Ikarga' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'contentlanguage'           => [ '1', 'TATARAMONKANLAOG', 'TATARAMONLAOG', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'ALDAWNGONYAN', 'CURRENTDAY' ],
	'currenthour'               => [ '1', 'ORASNGONYAN', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'BULANNGONYAN', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'NGARANBULANNGONYAN', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'PANAHONNGONYAN', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'SEMANANGONYAN', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'TAONNGONYAN', 'CURRENTYEAR' ],
	'filepath'                  => [ '0', 'FILEDALAN', 'FILEPATH:' ],
	'fullpagename'              => [ '1', 'TODONGNGARANKANPAHINA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'KAGNGARANKANTODONGNGARANKANPAHINA', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'TODONGURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'TODONGURLE:', 'FULLURLE:' ],
	'hiddencat'                 => [ '1', '__NAKATAGONGKAT__', '__HIDDENCAT__' ],
	'img_center'                => [ '1', 'sentro', 'tangâ', 'center', 'centre' ],
	'img_framed'                => [ '1', 'nakakawadro', 'kwadro', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'daing kwadro', 'frameless' ],
	'img_left'                  => [ '1', 'wala', 'left' ],
	'img_none'                  => [ '1', 'mayò', 'none' ],
	'img_page'                  => [ '1', 'pahina=$1', 'pahina $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'too', 'right' ],
	'language'                  => [ '0', '#TATARAMON', '#LANGUAGE:' ],
	'localday'                  => [ '1', 'LOKALALDAW', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'LOKALALDAW2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'NGARANLOKALALDAW', 'LOCALDAYNAME' ],
	'localhour'                 => [ '1', 'LOKALORAS', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'LOKALBULAN', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'NGARANLOKALBULAN', 'LOCALMONTHNAME' ],
	'localtime'                 => [ '1', 'LOKALPANAHON', 'LOCALTIME' ],
	'localurl'                  => [ '0', 'LOKALURL', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'LOKALURLE', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'LOKALSEMANA', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'LOKALTAON', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MSH', 'MSG:' ],
	'namespace'                 => [ '1', 'NGARANESPASYO', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'KAGNGARANESPASYO', 'NAMESPACEE' ],
	'numberofadmins'            => [ '1', 'NUMEROKANTAGAMATO', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NUMEROKANARTIKULO', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NUMEROKANLIGWAT', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NUMEROKANDOKUMENTO', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NUMEROKANPAHINA', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NUMEROKANPARAGAMIT', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'PADWALA', 'PADLEFT' ],
	'padright'                  => [ '0', 'PADTOO', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'NGARANKANPAHINA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'KAGNGARANKANPAHINA', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'PAHINASAKATEGORYA', 'PAHINASAKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'PAHINASOKOL', 'PAGESIZE' ],
	'plural'                    => [ '0', 'DAKOL:', 'PLURAL:' ],
	'talkpagename'              => [ '1', 'NGARANKANPAHINANINOLAY', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'KAGNGARANKANPAHINANINOLAY', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'OLAYESPASYO', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'KAGOLAYESPASYO', 'TALKSPACEE' ],
];
