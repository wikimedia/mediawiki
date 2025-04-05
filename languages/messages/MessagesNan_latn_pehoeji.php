<?php
/** Minnan (Pe̍h-ōe-jī) (Bân-lâm-gí (Pe̍h-ōe-jī))
 *
 * @file
 * @ingroup Languages
 *
 * @author Hiong3-eng5
 * @author Ianbu
 * @author Kaihsu
 * @author Winston Sung
 */

$fallback = 'nan-latn, nan-latn-tailo, nan, nan-hant, cdo-hant, zh-hant, zh, zh-hans';

$namespaceNames = [
	NS_MEDIA            => 'Mûi-thé',
	NS_SPECIAL          => 'Tek-pia̍t',
	NS_TALK             => 'Thó-lūn',
	NS_USER             => 'Iōng-chiá',
	NS_USER_TALK        => 'Iōng-chiá_thó-lūn',
	NS_PROJECT_TALK     => '$1_thó-lūn',
	NS_FILE             => 'Tóng-àn',
	NS_FILE_TALK        => 'Tóng-àn_thó-lūn',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_thó-lūn',
	NS_TEMPLATE         => 'Pang-bô͘',
	NS_TEMPLATE_TALK    => 'Pang-bô͘_thó-lūn',
	NS_HELP             => 'Soat-bêng',
	NS_HELP_TALK        => 'Soat-bêng_thó-lūn',
	NS_CATEGORY         => 'Lūi',
	NS_CATEGORY_TALK    => 'Lūi_thó-lūn',
];

$namespaceAliases = [
	'Pang-chān' => NS_HELP,
	'Pang-chān_thó-lūn' => NS_HELP_TALK,
	'Lūi-pia̍t' => NS_CATEGORY,
	'Lūi-pia̍t_thó-lūn' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Recentchangeslinked'       => [ 'Siong-koan_ê_kái-piàn' ],
	'Specialpages'              => [ 'Te̍k-sû-ia̍h', 'Te̍k-sû_ia̍h' ],
	'Upload'                    => [ 'Kā_tóng-àn_chiūⁿ-bāng' ],
	'Whatlinkshere'             => [ 'Tó-ūi_liân_kàu_chia' ],
];

$datePreferences = [
	'default',
	'ISO 8601',
];

$defaultDateFormat = 'nan-latn-pehoeji';

$dateFormats = [
	'nan-latn-pehoeji time' => 'H:i',
	'nan-latn-pehoeji date' => 'Y "nî" n "goe̍h" j "ji̍t" (l)',
	'nan-latn-pehoeji monthonly' => 'Y "nî" n "goe̍h"',
	'nan-latn-pehoeji both' => 'Y "nî" n "goe̍h" j "ji̍t" (l) H:i',
	'nan-latn-pehoeji pretty' => 'n "goe̍h" j "ji̍t"',
];
