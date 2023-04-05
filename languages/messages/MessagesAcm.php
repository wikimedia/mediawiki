<?php
/** Iraqi (Mesopotamian) Arabic (عراقي)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'ar';

$rtl = true;

$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'hijri',
	'ISO 8601',
	'jMY',
];

$defaultDateFormat = 'dmy or mdy';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'xg j، Y', # Arabic comma
	'mdy both' => 'H:i، xg j، Y', # Arabic comma

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i، j xg Y', # Arabic comma

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i، Y xg j', # Arabic comma

	'hijri time' => 'H:i',
	'hijri date' => 'xmj xmF xmY',
	'hijri both' => 'H:i، xmj xmF xmY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',

	'jMY time' => 'H:i',
	'jMY date' => 'j M Y',
	'jMY both' => 'H:i، j M Y', # Arabic comma
];

$digitTransformTable = [
	'0' => '٠', # U+0660
	'1' => '١', # U+0661
	'2' => '٢', # U+0662
	'3' => '٣', # U+0663
	'4' => '٤', # U+0664
	'5' => '٥', # U+0665
	'6' => '٦', # U+0666
	'7' => '٧', # U+0667
	'8' => '٨', # U+0668
	'9' => '٩', # U+0669
];

$separatorTransformTable = [
	'.' => '٫', # U+066B
	',' => '٬', # U+066C
];

$digitGroupingPattern = "#,##0.###";

$linkPrefixExtension = true;

/**
 * $arabicCombiningDiacritics, $linkTrail, and
 * $linkPrefixCharset are mostly copied from MessagesAr.php,
 * with the addition of the letter چ
 */
// The prefix set also needs to include diacritics, as these can be added
// to letters, but keep them as letters.
// These are from the "Extend" group in Unicode:
// https://www.unicode.org/Public/13.0.0/ucd/auxiliary/WordBreakProperty.txt
$arabicCombiningDiacritics =
	'\\x{0610}-\\x{061A}' .
	'\\x{064B}-\\x{065F}' .
	'\\x{0670}' .
	'\\x{06D6}-\\x{06DC}' .
	'\\x{06DF}-\\x{06E4}' .
	'\\x{06E7}' .
	'\\x{06E8}' .
	'\\x{06EA}-\\x{06ED}';

$linkTrail = '/^([a-zء-يچ' . $arabicCombiningDiacritics . ']+)(.*)$/sDu';
$linkPrefixCharset = 'a-zA-Zء-يچ' . $arabicCombiningDiacritics;
unset( $arabicCombiningDiacritics );
