<?php
/** Bengali (বাংলা)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'মিডিয়া',
	NS_SPECIAL          => 'বিশেষ',
	NS_TALK             => 'আলাপ',
	NS_USER             => 'ব্যবহারকারী',
	NS_USER_TALK        => 'ব্যবহারকারী_আলাপ',
	NS_PROJECT_TALK     => '$1_আলোচনা',
	NS_FILE             => 'চিত্র',
	NS_FILE_TALK        => 'চিত্র_আলোচনা',
	NS_MEDIAWIKI        => 'মিডিয়াউইকি',
	NS_MEDIAWIKI_TALK   => 'মিডিয়াউইকি_আলোচনা',
	NS_TEMPLATE         => 'টেমপ্লেট',
	NS_TEMPLATE_TALK    => 'টেমপ্লেট_আলোচনা',
	NS_HELP             => 'সাহায্য',
	NS_HELP_TALK        => 'সাহায্য_আলোচনা',
	NS_CATEGORY         => 'বিষয়শ্রেণী',
	NS_CATEGORY_TALK    => 'বিষয়শ্রেণী_আলোচনা',
];

$namespaceAliases = [
	'$1_আলাপ' => NS_PROJECT_TALK,
	'চিত্র_আলাপ' => NS_FILE_TALK,
	'MediaWiki_আলাপ' => NS_FILE_TALK,
];

$datePreferences = false;
$digitTransformTable = [
	'0' => '০',
	'1' => '১',
	'2' => '২',
	'3' => '৩',
	'4' => '৪',
	'5' => '৫',
	'6' => '৬',
	'7' => '৭',
	'8' => '৮',
	'9' => '৯'
];

$digitGroupingPattern = "##,##,###";

