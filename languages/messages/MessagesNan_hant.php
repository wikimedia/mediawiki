<?php
/** Minnan (Traditional Han script) (閩南語（傳統漢字）)
 *
 * @file
 * @ingroup Languages
 *
 * @author Hiong3-eng5
 * @author Ianbu
 * @author Kaihsu
 * @author Winston Sung
 */

$fallback = 'nan, nan-latn-pehoeji, nan-latn-tailo, cdo-hant, zh-hant, zh, zh-hans';

$namespaceNames = [
	NS_MEDIA            => '媒體',
	NS_SPECIAL          => '特殊',
	NS_TALK             => '討論',
	NS_USER             => '用者',
	NS_USER_TALK        => '用者討論',
	NS_PROJECT_TALK     => '$1討論',
	NS_FILE             => '檔案',
	NS_FILE_TALK        => '檔案討論',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki討論',
	NS_TEMPLATE         => '枋模',
	NS_TEMPLATE_TALK    => '枋模討論',
	NS_HELP             => '說明',
	NS_HELP_TALK        => '說明討論',
	NS_CATEGORY         => '類',
	NS_CATEGORY_TALK    => '類討論',
];

$namespaceAliases = [
	'用戶' => NS_USER,
	'用戶討論' => NS_USER_TALK,
	'文件' => NS_FILE,
	'文件討論' => NS_FILE_TALK,
	'媒體維基' => NS_MEDIAWIKI,
	'媒體維基討論' => NS_MEDIAWIKI_TALK,
	'模板' => NS_TEMPLATE,
	'模板討論' => NS_TEMPLATE_TALK,
	'幫助' => NS_HELP,
	'幫助討論' => NS_HELP_TALK,
	'分類' => NS_CATEGORY,
	'分類討論' => NS_CATEGORY_TALK,
];

$datePreferences = [
	'default',
	'ISO 8601',
];

$defaultDateFormat = 'nan-hant';

$dateFormats = [
	'nan-hant time' => 'H:i',
	'nan-hant date' => 'Y年n月j日（l）',
	'nan-hant monthonly' => 'Y年n月',
	'nan-hant both' => 'Y年n月j日（l）H:i',
	'nan-hant pretty' => 'n月j日',
];
