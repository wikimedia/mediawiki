<?php
/** Gilaki (گیلکی)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'fa';

$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'مديا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'گب',
	NS_USER             => 'کارگير',
	NS_USER_TALK        => 'کارگيرˇ_گب',
	NS_PROJECT_TALK     => 'مدي_$1',
	NS_FILE             => 'فاىل',
	NS_FILE_TALK        => 'فاىلˇ_گب',
	NS_MEDIAWIKI        => 'مدياويکي',
	NS_MEDIAWIKI_TALK   => 'مدياويکي_گب',
	NS_TEMPLATE         => 'قالب',
	NS_TEMPLATE_TALK    => 'قالبˇ_گب',
	NS_HELP             => 'رانما',
	NS_HELP_TALK        => 'رانما_گب',
	NS_CATEGORY         => 'جرگه',
	NS_CATEGORY_TALK    => 'جرگه_گب',
];

$namespaceAliases = [
	// Aliases from old Persian (fa) namespace names
	'ویژه' => NS_SPECIAL,
	'بحث' => NS_TALK,
	'کاربر' => NS_USER,
	'بحث_کاربر' => NS_USER_TALK,
	'بحث_$1' => NS_PROJECT_TALK,
	'پرونده' => NS_FILE,
	'بحث_پرونده' => NS_FILE_TALK,
	'بحث_مدیاویکی' => NS_MEDIAWIKI_TALK,
	'الگو' => NS_TEMPLATE,
	'بحث_الگو' => NS_TEMPLATE_TALK,
	'راهنما' => NS_HELP,
	'بحث_راهنما' => NS_HELP_TALK,
	'رده' => NS_CATEGORY,
	'بحث_رده' => NS_CATEGORY_TALK,
];
