<?php
/** Chinese (Hong Kong) (‪中文(香港)‬)
 *
 * @file
 * @ingroup Languages
 *
 * @author Hamish
 * @author Horacewai2
 * @author Kayau
 * @author Mark85296341
 * @author PhiLiP
 * @author Shizhao
 * @author Waihorace
 * @author Winston Sung
 * @author Wong128hk
 * @author Yukiseaside
 * @author Yuyu
 */

$fallback = 'zh-hant, zh-tw, zh, zh-hans';

$fallback8bitEncoding = 'Big5-HKSCS';

$namespaceNames = [
	NS_USER             => '用戶',
	NS_USER_TALK        => '用戶討論',
];

$namespaceAliases = [
	"用戶" => NS_USER,
	"用戶討論" => NS_USER_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'ComparePages'              => [ '頁面比較' ],
	'Unblock'                   => [ '解除封禁' ],
];

$datePreferences = [
	'default',
	'ISO 8601',
	'HK dmy',
];

$defaultDateFormat = 'zh';

$dateFormats = [
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',

	'HK dmy time' => 'H:i',
	'HK dmy date' => 'd-m-Y',
	'HK dmy both' => 'd-m-Y H:i',
];
