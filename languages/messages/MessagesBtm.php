<?php
/** Mandailing (Batak Mandailing)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'id';

$separatorTransformTable = [ ',' => '.', '.' => ',' ];

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Husus',
	NS_TALK             => 'Obar',
	NS_USER             => 'Pamake',
	NS_USER_TALK        => 'Obar_pamake',
	NS_PROJECT_TALK     => 'Obar_$1',
	NS_FILE             => 'Borkas',
	NS_FILE_TALK        => 'Obar_borkas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Obar_MediaWiki',
	NS_TEMPLATE         => 'Templat',
	NS_TEMPLATE_TALK    => 'Obar_templat',
	NS_HELP             => 'Tolongi',
	NS_HELP_TALK        => 'Obar_tolongi',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Obar_kategori',
];

$datePreferences = [
	'default',
	'dmy',
	'ymd',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'dmy time' => 'H.i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H.i',

	'ymd time' => 'H.i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j H.i',
];
