<?php
/** Moroccan Arabic, Darija (الدارجة)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'ar';

$rtl = true;

$namespaceNames = [
	NS_MEDIA => 'ميديا',
	NS_SPECIAL => 'خاص',
	NS_TALK => 'مداكرة',
	NS_USER => 'خدايمي',
	NS_USER_TALK => 'لمداكرة_د_لخدايمي',
	NS_PROJECT_TALK => 'لمداكرة_د_$1',
	NS_FILE => 'فيشي',
	NS_FILE_TALK => 'لمداكرة_د_لفيشي',
	NS_MEDIAWIKI => 'ميدياويكي',
	NS_MEDIAWIKI_TALK => 'لمداكرة_د_ميدياويكي',
	NS_TEMPLATE => 'موضيل',
	NS_TEMPLATE_TALK => 'لمداكرة_د_لموضيل',
	NS_HELP => 'معاونة',
	NS_HELP_TALK => 'لمداكرة_د_لمعاونة',
	NS_CATEGORY => 'تصنيف',
	NS_CATEGORY_TALK => 'لمداكرة_د_تصنيف',
];

$namespaceAliases = [
	'نقاش' => NS_TALK,
	'مستخدم' => NS_USER,
	'نقاش_المستخدم' => NS_USER_TALK,
	'نقاش_$1' => NS_PROJECT_TALK,
	'ملف' => NS_FILE,
	'نقاش_الملف' => NS_FILE_TALK,
	'نقاش_ميدياويكي' => NS_MEDIAWIKI_TALK,
	'قالب' => NS_TEMPLATE,
	'نقاش_القالب' => NS_TEMPLATE_TALK,
	'مساعدة' => NS_HELP,
	'نقاش_المساعدة' => NS_HELP_TALK,
	'نقاش_التصنيف' => NS_CATEGORY_TALK,
];

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
