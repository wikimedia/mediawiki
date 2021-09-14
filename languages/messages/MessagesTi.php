<?php
/** Tigrinya
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'ሜድያ',
	NS_SPECIAL          => 'ፍሉይ',
	NS_TALK             => 'ምይይጥ',
	NS_USER             => 'ተጠቃሚ',
	NS_USER_TALK        => 'ምይይጥ_ተጠቃሚ',
	NS_PROJECT_TALK     => '$1_ምይይጥ',
	NS_FILE             => 'ፋይል',
	NS_FILE_TALK        => 'ምይይጥ_ፋይል',
	NS_MEDIAWIKI        => 'ሜድያዊኪ',
	NS_MEDIAWIKI_TALK   => 'ምይይጥ_ሜድያዊኪ',
	NS_TEMPLATE         => 'ሞደል',
	NS_TEMPLATE_TALK    => 'ምይይጥ_ሞደል',
	NS_HELP             => 'ሓገዝ',
	NS_HELP_TALK        => 'ምይይጥ_ሓገዝ',
	NS_CATEGORY         => 'መደብ',
	NS_CATEGORY_TALK    => 'ምይይጥ_መደብ',
];

$namespaceAliases = [ // Kept former namespaces for backwards compatibility - T263840
	'ተጠቃሚ_ምይይጥ' => NS_USER_TALK,
	'ፋይል_ምይይጥ' => NS_FILE_TALK,
	'ሜዲያዊኪ' => NS_MEDIAWIKI,
	'ሜዲያዊኪ_ምይይጥ' => NS_MEDIAWIKI_TALK,
	'ሞደል_ምይይጥ' => NS_TEMPLATE_TALK,
	'ሓገዝ_ምይይጥ' => NS_HELP_TALK,
	'መደብ_ምይይጥ' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'ተጠቃሚ', 'female' => 'ተጠቃሚት' ],
	NS_USER_TALK => [ 'male' => 'ምይይጥ_ተጠቃሚ', 'female' => 'ምይይጥ_ተጠቃሚት' ],
];
