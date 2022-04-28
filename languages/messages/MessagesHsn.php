<?php
/** Xiang Chinese (湘语)
 *
 * @file
 * @ingroup Languages
 *
 * @author Amir E. Aharoni
 * @author Winston Sung
 */

$fallback = 'zh-cn, zh-hans, zh, zh-hant';

$linkTrail = '/^()(.*)$/sD';

// Copied from Chinese (Simplified)
$datePreferences = [
	'default',
	'ISO 8601',
];
$defaultDateFormat = 'zh';
$dateFormats = [
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
];

// Copied from Chinese (Simplified)
$bookstoreList = [
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'https://www.barnesandnoble.com/w/?ean=$1',
	'亚马逊' => 'https://www.amazon.com/exec/obidos/ISBN=$1',
	'亚马逊中国' => 'https://www.amazon.cn/s?i=stripbooks&k=$1',
	'当当网' => 'https://search.dangdang.com/?key=$1',
	'博客来书店' => 'https://search.books.com.tw/search/query/key/$1',
	'三民书店' => 'https://www.sanmin.com.tw/Search/Index/?ct=ISBN&qu=$1',
];
