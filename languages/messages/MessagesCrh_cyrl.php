<?php
/** Crimean Tatar (Cyrillic script) (къырымтатарджа (Кирилл)‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$fallback8bitEncoding = 'windows-1251';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Махсус',
	NS_TALK             => 'Музакере',
	NS_USER             => 'Къулланыджы',
	NS_USER_TALK        => 'Къулланыджы_музакереси',
	NS_PROJECT_TALK     => '$1_музакереси',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_музакереси',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_музакереси',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_музакереси',
	NS_HELP             => 'Ярдым',
	NS_HELP_TALK        => 'Ярдым_музакереси',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_музакереси',
];

$namespaceAliases = [
	# Aliases of Latin (crh-latn) namespaces
	"Media"                 => NS_MEDIA,
	"Mahsus"                => NS_SPECIAL,
	"Muzakere"              => NS_TALK,
	"Qullanıcı"             => NS_USER,
	"Qullanıcı_muzakeresi"  => NS_USER_TALK,
	"$1_muzakeresi"         => NS_PROJECT_TALK,
	"Resim"                 => NS_FILE,
	"Resim_muzakeresi"      => NS_FILE_TALK,
	"Ресим"                 => NS_FILE,
	"Ресим_музакереси"      => NS_FILE_TALK,
	"MediaViki"             => NS_MEDIAWIKI,
	"MediaViki_muzakeresi"  => NS_MEDIAWIKI_TALK,
	'Şablon'                => NS_TEMPLATE,
	'Şablon_muzakeresi'     => NS_TEMPLATE_TALK,
	'Yardım'                => NS_HELP,
	'Yardım_muzakeresi'     => NS_HELP_TALK,
	'Kategoriya'            => NS_CATEGORY,
	'Kategoriya_muzakeresi' => NS_CATEGORY_TALK
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
	'ISO 8601',
];

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'F j Y "с."',
	'mdy both' => 'H:i, F j Y "с."',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y "с."',
	'dmy both' => 'H:i, j F Y "с."',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "с." xg j',
	'ymd both' => 'H:i, Y "с." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY.xnm.xnd',
	'ISO 8601 both' => 'xnY.xnm.xnd"T"xnH:xni:xns',
];

$separatorTransformTable = [ ','  => '.', '.' => ',' ];
$linkTrail = '/^([a-zâçğıñöşüа-яё“»]+)(.*)$/sDu';
$linkPrefixCharset = 'a-zâçğıñöşüA-ZÂÇĞİÑÖŞÜa-яёА-ЯЁ«„';
