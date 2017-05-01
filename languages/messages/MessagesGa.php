<?php
/** Irish (Gaeilge)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$magicWords = [
	'redirect'                  => [ '0', '#athsheoladh', '#REDIRECT' ],
	'notoc'                     => [ '0', '__GANCÁ__', '__NOTOC__' ],
	'forcetoc'                  => [ '0', '__CÁGACHUAIR__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__CÁ__', '__TOC__' ],
	'noeditsection'             => [ '0', '__GANMHÍRATHRÚ__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MÍLÁITHREACH', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'AINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'GINAINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'GIORRÚNAMÍOSALÁITHREAÍ', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'LÁLÁITHREACH', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'AINMANLAELÁITHRIGH', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'BLIAINLÁITHREACH', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'AMLÁITHREACH', 'CURRENTTIME' ],
	'numberofarticles'          => [ '1', 'LÍONNANALT', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'LÍONNAGCOMHAD', 'NUMBEROFFILES' ],
	'pagename'                  => [ '1', 'AINMANLGH', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'AINMANLGHB', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'AINMSPÁS', 'NAMESPACE' ],
	'msg'                       => [ '0', 'TCHT:', 'MSG:' ],
	'subst'                     => [ '0', 'IONAD:', 'SUBST:' ],
	'msgnw'                     => [ '0', 'TCHTFS:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'mion', 'mionsamhail', 'thumb', 'thumbnail' ],
	'img_right'                 => [ '1', 'deas', 'right' ],
	'img_left'                  => [ '1', 'clé', 'left' ],
	'img_none'                  => [ '1', 'faic', 'none' ],
	'img_center'                => [ '1', 'lár', 'center', 'centre' ],
	'img_framed'                => [ '1', 'fráma', 'frámaithe', 'frame', 'framed', 'enframed' ],
	'int'                       => [ '0', 'INMH:', 'INT:' ],
	'sitename'                  => [ '1', 'AINMANTSUÍMH', 'SITENAME' ],
	'ns'                        => [ '0', 'AS:', 'NS:' ],
	'localurl'                  => [ '0', 'URLÁITIÚIL', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'URLÁITIÚILB', 'LOCALURLE:' ],
	'server'                    => [ '0', 'FREASTALAÍ', 'SERVER' ],
	'servername'                => [ '0', 'AINMANFHREASTALAÍ', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'SCRIPTCHOSÁN', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'GRAMADACH:', 'GRAMMAR:' ],
	'notitleconvert'            => [ '0', '__GANTIONTÚNADTEIDEAL__', '__GANTT__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__GANTIONTÚNANÁBHAIR__', '__GANTA__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'SEACHTAINLÁITHREACH', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'LÁLÁITHREACHNAS', 'CURRENTDOW' ],
	'revisionid'                => [ '1', 'IDANLEASAITHE', 'REVISIONID' ],
];

$namespaceNames = [
	NS_MEDIA            => 'Meán',
	NS_SPECIAL          => 'Speisialta',
	NS_TALK             => 'Plé',
	NS_USER             => 'Úsáideoir',
	NS_USER_TALK        => 'Plé_úsáideora',
	NS_PROJECT_TALK     => 'Plé_{{grammar:genitive|$1}}',
	NS_FILE             => 'Íomhá',
	NS_FILE_TALK        => 'Plé_íomhá',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Plé_MediaWiki',
	NS_TEMPLATE         => 'Teimpléad',
	NS_TEMPLATE_TALK    => 'Plé_teimpléid',
	NS_HELP             => 'Cabhair',
	NS_HELP_TALK        => 'Plé_cabhrach',
	NS_CATEGORY         => 'Catagóir',
	NS_CATEGORY_TALK    => 'Plé_catagóire',
];

$namespaceAliases = [
	'Plé_í­omhá' => NS_FILE_TALK,
	'Múnla' => NS_TEMPLATE,
	'Plé_múnla' => NS_TEMPLATE_TALK,
	'Rang' => NS_CATEGORY
];

