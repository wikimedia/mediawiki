<?php
/** Tatar (Latin script) (tatarça)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Albert Fazlî
 * @author Don Alessandro
 * @author KhayR
 * @author Reedy
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_TALK             => 'Bäxäs',
	NS_USER             => 'Qullanuçı',
	NS_USER_TALK        => 'Qullanuçı_bäxäse',
	NS_PROJECT_TALK     => '$1_bäxäse',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_bäxäse',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_bäxäse',
	NS_TEMPLATE         => 'Ürnäk',
	NS_TEMPLATE_TALK    => 'Ürnäk_bäxäse',
	NS_HELP             => 'Yärdäm',
	NS_HELP_TALK        => 'Yärdäm_bäxäse',
	NS_CATEGORY         => 'Törkem',
	NS_CATEGORY_TALK    => 'Törkem_bäxäse',
);

$namespaceAliases = array(
	'Äğzä'             => NS_USER,
	'Äğzä_bäxäse'      => NS_USER_TALK,
	'Räsem'            => NS_FILE,
	'Räsem_bäxäse'     => NS_FILE_TALK,
);

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y, H:i',
	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$magicWords = array(
	'redirect'                  => array( '0', '#YÜNÄLTÜ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ETYUQ__', '__NOTOC__' ),
	'forcetoc'                  => array( '0', '__ETTIQ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ET__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BÜLEMTÖZÄTÜYUQ__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'AĞIMDAĞI_AY', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'AĞIMDAĞI_AY_İSEME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'AĞIMDAĞI_AY_İSEME_GEN', 'CURRENTMONTHNAMEGEN' ),
	'currentday'                => array( '1', 'AĞIMDAĞI_KÖN', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'AĞIMDAĞI_KÖN_İSEME', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'AĞIMDAĞI_YIL', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AĞIMDAĞI_WAQIT', 'CURRENTTIME' ),
	'numberofarticles'          => array( '1', 'MÄQÄLÄ_SANI', 'NUMBEROFARTICLES' ),
	'pagename'                  => array( '1', 'BİTİSEME', 'PAGENAME' ),
	'namespace'                 => array( '1', 'İSEMARA', 'NAMESPACE' ),
	'subst'                     => array( '0', 'TÖPÇEK:', 'SUBST:' ),
	'img_right'                 => array( '1', 'uñda', 'right' ),
	'img_left'                  => array( '1', 'sulda', 'left' ),
	'img_none'                  => array( '1', 'yuq', 'none' ),
	'int'                       => array( '0', 'EÇKE:', 'INT:' ),
	'sitename'                  => array( '1', 'SÄXİFÄİSEME', 'SITENAME' ),
	'ns'                        => array( '0', 'İA:', 'NS:' ),
	'localurl'                  => array( '0', 'URINLIURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'URINLIURLE:', 'LOCALURLE:' ),
);

$fallback8bitEncoding = "windows-1254";

$linkTrail = '/^([a-zäçğıñöşü“»]+)(.*)$/sDu';

