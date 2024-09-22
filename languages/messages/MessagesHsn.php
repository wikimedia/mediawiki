<?php
/** Xiang (湘語)
 *
 * @file
 * @ingroup Languages
 *
 * @author Amir E. Aharoni
 * @author Winston Sung
 */

$fallback = 'zh-hant, zh, zh-hans';

$linkTrail = '/^()(.*)$/sD';

$datePreferences = [
	'default',
	'ISO 8601',
];

$defaultDateFormat = 'zh';

$dateFormats = [
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日（D）',
	'zh both' => 'Y年n月j日（D）H:i',
];
