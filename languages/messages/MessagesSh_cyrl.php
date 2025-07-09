<?php
/** Serbo-Croatian (Cyrillic script) (српскохрватски (ћирилица))
 *
 * @file
 * @ingroup Languages
 *
 * @author Aca
 */

/**
 * Fallback prioritizes language codes in the following order:
 *
 * 1. Cyrillic-script Ekavian codes
 * 2. Latin-script Ekavian codes
 * 3. Latin-script Ijekavian codes
 *
 * This order aligns with T399126.
 */
$fallback = 'sr-cyrl, sr-ec, sr-latn, sr-el, sh, sh-latn, bs, hr';

$namespaceNames = [
	NS_SPECIAL          => 'Посебно',
	NS_TALK             => 'Разговор',
	NS_USER             => 'Корисник',
	NS_USER_TALK        => 'Разговор_с_корисником',
	NS_PROJECT_TALK     => 'Разговор_о_$1',
	NS_FILE             => 'Датотека',
	NS_FILE_TALK        => 'Разговор_о_датотеци',
	NS_MEDIAWIKI        => 'Медијавики',
	NS_MEDIAWIKI_TALK   => 'Разговор_о_Медијавикију',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Разговор_о_шаблону',
	NS_HELP             => 'Помоћ',
	NS_HELP_TALK        => 'Разговор_о_помоћи',
	NS_CATEGORY         => 'Категорија',
	NS_CATEGORY_TALK    => 'Разговор_о_категорији',
];

$namespaceAliases = [
	'Разговор_са_корисником' => NS_USER_TALK,
	'Медијавики_разговор' => NS_MEDIAWIKI_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Корисник', 'female' => 'Корисница' ],
	NS_USER_TALK => [ 'male' => 'Разговор_с_корисником', 'female' => 'Разговор_с_корисницом' ],
];

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
