<?php
/** Chinese (Taiwan) (‪中文(臺灣)‬)
 *
 * @file
 * @ingroup Languages
 *
 * @author Alexsh
 * @author Andrew971218
 * @author BobChao
 * @author Ianbu
 * @author Jidanni
 * @author Mark85296341
 * @author Pbdragonwang
 * @author PhiLiP
 * @author Roc michael
 * @author Shizhao
 * @author Urhixidur
 * @author Winston Sung
 * @author Wong128hk
 * @author Zerng07
 * @author לערי ריינהארט
 */

$fallback = 'zh-hant, zh-hk, zh, zh-hans';

$datePreferences = [
	'default',
	'minguo',
	'minguo shorttext',
	'minguo text',
	'minguo fulltext',
	'CNS 7648',
	'CNS 7648 compact',
	'ISO 8601',
];

$defaultDateFormat = 'zh';

$dateFormats = [
	'zh time'                => 'H:i',
	'zh date'                => 'Y年n月j日 (l)',
	'zh both'                => 'Y年n月j日 (D) H:i',

	'minguo time'            => 'H:i',
	'minguo date'            => 'xoY年n月j日 (l)',
	'minguo both'            => 'xoY年n月j日 (D) H:i',

	'minguo shorttext time'  => 'H:i',
	'minguo shorttext date'  => '民xoY年n月j日 (l)',
	'minguo shorttext both'  => '民xoY年n月j日 (D) H:i',

	'minguo text time'       => 'H:i',
	'minguo text date'       => '民國xoY年n月j日 (l)',
	'minguo text both'       => '民國xoY年n月j日 (D) H:i',

	'minguo fulltext time'   => 'H:i',
	'minguo fulltext date'   => '中華民國xoY年n月j日 (l)',
	'minguo fulltext both'   => '中華民國xoY年n月j日 (D) H:i',

	'CNS 7648 time'          => 'H:i',
	'CNS 7648 date'          => '"R.O.C." xoY-m-d (l)',
	'CNS 7648 both'          => '"R.O.C." xoY-m-d (D) H:i',

	'CNS 7648 compact time'  => 'H:i',
	'CNS 7648 compact date'  => '"ROC" xoY-m-d (l)',
	'CNS 7648 compact both'  => '"ROC" xoY-m-d (D) H:i',
];
