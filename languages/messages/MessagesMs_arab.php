<?php
/** Malay (Arabic Jawi script; بهاس ملايو)
 *
 * @file
 * @ingroup Languages
 *
 * Based on MessagesMs.php, see the credits there
 * @author Tofeiku
 * @author Amire80
 */

$fallback = 'ms';

$rtl = true;

$defaultDateFormat = 'dmy';

$namespaceNames = [
	NS_MEDIA            => 'ميديا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'ڤربينچڠن',
	NS_USER             => 'ڤڠݢونا',
	NS_USER_TALK        => 'ڤربينچڠن_ڤڠݢونا',
	NS_PROJECT_TALK     => 'ڤربينچڠن_$1',
	NS_FILE             => 'فاءيل',
	NS_FILE_TALK        => 'ڤربينچڠن_فاءيل',
	NS_MEDIAWIKI        => 'ميدياويکي',
	NS_MEDIAWIKI_TALK   => 'ڤربينچڠن_ميدياويکي',
	NS_TEMPLATE         => 'تمڤلت',
	NS_TEMPLATE_TALK    => 'ڤربينچڠن_تمڤلت',
	NS_HELP             => 'بنتوان',
	NS_HELP_TALK        => 'ڤربينچڠن_بنتوان',
	NS_CATEGORY         => 'کاتݢوري',
	NS_CATEGORY_TALK    => 'ڤربينچڠن_کاتݢوري',
];

# The linkTrail is like in Arabic, with the addition of
# the six special Jawi letters and the Persian Kaf:
# چ ڠ ڤ ک ݢ ڽ ۏ
$arabicCombiningDiacritics =
	'\\x{0610}-\\x{061A}' .
	'\\x{064B}-\\x{065F}' .
	'\\x{0670}' .
	'\\x{06D6}-\\x{06DC}' .
	'\\x{06DF}-\\x{06E4}' .
	'\\x{06E7}' .
	'\\x{06E8}' .
	'\\x{06EA}-\\x{06ED}';
$linkTrail = '/^([a-zء-ي' . $arabicCombiningDiacritics . 'چڠڤکݢڽۏ]+)(.*)$/sDu';
unset( $arabicCombiningDiacritics );
