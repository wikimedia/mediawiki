<?php
/** Serbo-Croatian (Cyrillic script) (српскохрватски (ћирилица))
 *
 * @file
 * @ingroup Languages
 *
 * @author Aca
 */

$fallback = 'sr-cyrl, sr-ec, sh, sh-latn';

$datePreferences = [
	'default',
	'dmy sh-cyrl',
	'ISO 8601',
];

$defaultDateFormat = 'dmy sh-cyrl';

$dateFormats = [
	'dmy sh-cyrl time' => 'H:i',
	'dmy sh-cyrl date' => 'j. xg Y.',
	'dmy sh-cyrl monthonly' => 'xg Y.',
	'dmy sh-cyrl both' => 'j. xg Y. у H:i',
	'dmy sh-cyrl pretty' => 'j. xg',
];
