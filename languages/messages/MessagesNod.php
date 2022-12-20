<?php
/** Northern Thai (ᨣᩤᩴᨾᩮᩬᩥᨦ)
 *
 * @file
 * @ingroup Languages
 *
 * @author Noktonissian
 */

$namespaceNames = [
	NS_MEDIA            => 'ᩈᩨ᩵',
	NS_SPECIAL          => 'ᨻᩥᩔᩮ',
	NS_TALK             => 'ᩋᩪ᩶ᨧᩣ',
	NS_USER             => 'ᨹᩪ᩶ᨩᩲ᩶',
	NS_USER_TALK        => 'ᩋᩪ᩶ᨠᩢ᩠ᨷᨹᩪ᩶ᨩᩲ᩶',
	NS_PROJECT_TALK     => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦ$1',
	NS_FILE             => 'ᨼᩱᩃ᩺',
	NS_FILE_TALK        => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦᨼᩱᩃ᩺',
	NS_MEDIAWIKI        => 'ᨾᩦᨯ᩠ᨿᩮᩅᩥᨠᩥ',
	NS_MEDIAWIKI_TALK   => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦᨾᩦᨯ᩠ᨿᩮᩅᩥᨠᩥ',
	NS_TEMPLATE         => 'ᨣᩮᩢ᩶ᩣᨷᩯ᩠ᨷ',
	NS_TEMPLATE_TALK    => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦᨣᩮᩢ᩶ᩣᨷᩯ᩠ᨷ',
	NS_HELP             => 'ᩅᩥᨵᩦᨩᩲ᩶',
	NS_HELP_TALK        => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦᩅᩥᨵᩦᨩᩲ᩶',
	NS_CATEGORY         => 'ᩉ᩠ᨾ᩠ᩅᨯᩉ᩠ᨾᩪ᩵',
	NS_CATEGORY_TALK    => 'ᩋᩪ᩶ᨧᩣᩁᩮᩬᩥ᩵ᨦᩉ᩠ᨾ᩠ᩅᨯᩉ᩠ᨾᩪ᩵',
];

$digitTransformTable = [
	'0' => '᪀', # U+1A80
	'1' => '᪁', # U+1A81
	'2' => '᪂', # U+1A82
	'3' => '᪃', # U+1A83
	'4' => '᪄', # U+1A84
	'5' => '᪅', # U+1A85
	'6' => '᪆', # U+1A86
	'7' => '᪇', # U+1A87
	'8' => '᪈', # U+1A88
	'9' => '᪉', # U+1A89
];

$datePreferences = [
	'default',
	'thai',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'thai time' => 'H:i',
	'thai date' => 'j F xkY',
	'thai both' => 'H:i, j F xkY',

	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'H:i, F j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'H:i, j F Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];
