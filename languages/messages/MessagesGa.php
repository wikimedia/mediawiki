<?php
/** Irish (Gaeilge)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$magicWords = array(
	'redirect'                  => array( '0', '#athsheoladh', '#REDIRECT' ),
	'notoc'                     => array( '0', '__GANCÁ__', '__NOTOC__' ),
	'forcetoc'                  => array( '0', '__CÁGACHUAIR__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__CÁ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__GANMHÍRATHRÚ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MÍLÁITHREACH', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'AINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'GINAINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'GIORRÚNAMÍOSALÁITHREAÍ', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'LÁLÁITHREACH', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'AINMANLAELÁITHRIGH', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'BLIAINLÁITHREACH', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AMLÁITHREACH', 'CURRENTTIME' ),
	'numberofarticles'          => array( '1', 'LÍONNANALT', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'LÍONNAGCOMHAD', 'NUMBEROFFILES' ),
	'pagename'                  => array( '1', 'AINMANLGH', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'AINMANLGHB', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'AINMSPÁS', 'NAMESPACE' ),
	'msg'                       => array( '0', 'TCHT:', 'MSG:' ),
	'subst'                     => array( '0', 'IONAD:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'TCHTFS:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'mionsamhail', 'mion', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'deas', 'right' ),
	'img_left'                  => array( '1', 'clé', 'left' ),
	'img_none'                  => array( '1', 'faic', 'none' ),
	'img_center'                => array( '1', 'lár', 'center', 'centre' ),
	'img_framed'                => array( '1', 'fráma', 'frámaithe', 'framed', 'enframed', 'frame' ),
	'int'                       => array( '0', 'INMH:', 'INT:' ),
	'sitename'                  => array( '1', 'AINMANTSUÍMH', 'SITENAME' ),
	'ns'                        => array( '0', 'AS:', 'NS:' ),
	'localurl'                  => array( '0', 'URLÁITIÚIL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'URLÁITIÚILB', 'LOCALURLE:' ),
	'server'                    => array( '0', 'FREASTALAÍ', 'SERVER' ),
	'servername'                => array( '0', 'AINMANFHREASTALAÍ', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SCRIPTCHOSÁN', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'GRAMADACH:', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__GANTIONTÚNADTEIDEAL__', '__GANTT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__GANTIONTÚNANÁBHAIR__', '__GANTA__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'SEACHTAINLÁITHREACH', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'LÁLÁITHREACHNAS', 'CURRENTDOW' ),
	'revisionid'                => array( '1', 'IDANLEASAITHE', 'REVISIONID' ),
);

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Plé_í­omhá' => NS_FILE_TALK,
	'Múnla' => NS_TEMPLATE,
	'Plé_múnla' => NS_TEMPLATE_TALK,
	'Rang' => NS_CATEGORY
);

