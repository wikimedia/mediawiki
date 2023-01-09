<?php
/** Minnan (Traditional Han script) (閩南語（傳統漢字）)
 *
 * @file
 * @ingroup Languages
 *
 * @author Winston Sung
 */

$fallback = 'nan, nan-latn-pehoeji, nan-latn-tailo, cdo, zh-hant, zh, zh-hans';

$namespaceNames = [
	NS_MEDIA            => '媒體',
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

$datePreferences = [
	'default',
	'ISO 8601',
];

$defaultDateFormat = 'nan-hant';

$dateFormats = [
	'nan-hant time' => 'H:i',
	'nan-hant date' => 'Y"年"n"月"j"日"（l）',
	'nan-hant both' => 'Y"年"n"月"j"日"（D）H:i',
];
