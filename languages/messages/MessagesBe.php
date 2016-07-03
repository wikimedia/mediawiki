<?php
/** Belarusian (беларуская)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'Мультымедыя',
	NS_SPECIAL          => 'Адмысловае',
	NS_TALK             => 'Размовы',
	NS_USER             => 'Удзельнік',
	NS_USER_TALK        => 'Размовы_з_удзельнікам',
	NS_PROJECT_TALK     => 'Размовы_пра_{{GRAMMAR:вінавальны|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Размовы_пра_файл',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Размовы_пра_MediaWiki',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Размовы_пра_шаблон',
	NS_HELP             => 'Даведка',
	NS_HELP_TALK        => 'Размовы_пра_даведку',
	NS_CATEGORY         => 'Катэгорыя',
	NS_CATEGORY_TALK    => 'Размовы_пра_катэгорыю',
];

$namespaceAliases = [
	'$1_размовы' => NS_PROJECT_TALK,
	'Выява' => NS_FILE,
	'Размовы_пра_выяву' => NS_FILE_TALK,
];

$magicWords = [
	'img_thumbnail'             => [ '1', 'міні', 'мініяцюра', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'міні=$1', 'мініяцюра=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'справа', 'right' ],
	'img_left'                  => [ '1', 'злева', 'left' ],
	'img_none'                  => [ '1', 'няма', 'none' ],
	'img_width'                 => [ '1', '$1пкс', '$1px' ],
	'img_center'                => [ '1', 'цэнтр', 'center', 'centre' ],
	'img_framed'                => [ '1', 'безрамкі', 'frame', 'framed', 'enframed' ],
];

$bookstoreList = [
	'OZ.by' => 'http://oz.by/search.phtml?what=books&isbn=$1',
	'Amazon.com' => 'http://www.amazon.com/exec/obidos/ISBN=$1'
];

$datePreferences = [
	'default',
	'dmy',
	'ISO 8601',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
];

# Per discussion on https://translatewiki.net/wiki/Thread:Support/Customization_of number format
$separatorTransformTable = [
	',' => "\xc2\xa0", # nbsp
	'.' => ','
];

$linkTrail = '/^([абвгґджзеёжзійклмнопрстуўфхцчшыьэюяćčłńśšŭźža-z]+)(.*)$/sDu';

