<?php
/** Moroccan Arabic, Darija (الدارجة)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ar';

$rtl = true;

// (T18469) Override Eastern Arabic numberals, use Western
$digitTransformTable = [
	'0' => '0',
	'1' => '1',
	'2' => '2',
	'3' => '3',
	'4' => '4',
	'5' => '5',
	'6' => '6',
	'7' => '7',
	'8' => '8',
	'9' => '9',
];

$separatorTransformTable = [
	'.' => '.',
	',' => ',',
];
