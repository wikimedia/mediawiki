<?php
/** Crimean Turkish (Latin script) (qırımtatarca (Latin)‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback8bitEncoding = 'windows-1254';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Mahsus',
	NS_TALK             => 'Muzakere',
	NS_USER             => 'Qullanıcı',
	NS_USER_TALK        => 'Qullanıcı_muzakeresi',
	NS_PROJECT_TALK     => '$1_muzakeresi',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_muzakeresi',
	NS_MEDIAWIKI        => 'MediaViki',
	NS_MEDIAWIKI_TALK   => 'MediaViki_muzakeresi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_muzakeresi',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_muzakeresi',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_muzakeresi',
];

$namespaceAliases = [
	# Aliases to Cyrillic (crh-cyrl) namespaces
	"Медиа"                  => NS_MEDIA,
	"Махсус"                 => NS_SPECIAL,
	"Музакере"               => NS_TALK,
	"Къулланыджы"            => NS_USER,
	"Къулланыджы_музакереси" => NS_USER_TALK,
	"$1_музакереси"          => NS_PROJECT_TALK,
	"Ресим"                  => NS_FILE,
	"Ресим_музакереси"       => NS_FILE_TALK,
	"Resim"                  => NS_FILE,
	"Resim_muzakeresi"       => NS_FILE_TALK,
	"МедиаВики"              => NS_MEDIAWIKI,
	"МедиаВики_музакереси"   => NS_MEDIAWIKI_TALK,
	'Шаблон'                 => NS_TEMPLATE,
	'Шаблон_музакереси'      => NS_TEMPLATE_TALK,
	'Ярдым'                  => NS_HELP,
	'Разговор_о_помоћи'      => NS_HELP_TALK,
	'Категория'              => NS_CATEGORY,
	'Категория_музакереси'   => NS_CATEGORY_TALK
];

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
	'mdy date' => 'F j Y "s."',
	'mdy both' => 'H:i, F j Y "s."',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y "s."',
	'dmy both' => 'H:i, j F Y "s."',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "s." xg j',
	'ymd both' => 'H:i, Y "s." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY.xnm.xnd',
	'ISO 8601 both' => 'xnY.xnm.xnd"T"xnH:xni:xns',
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zâçğıñöşüа-яё“»]+)(.*)$/sDu';
$linkPrefixCharset = 'a-zâçğıñöşüA-ZÂÇĞİÑÖŞÜa-яёА-ЯЁ«„';
