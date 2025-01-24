<?php
/** Mindong (Latin script) (Mìng-dĕ̤ng-ngṳ̄ (Bàng-uâ-cê))
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'cdo, cdo-hant, nan-hant, zh-hant, zh, zh-hans';

$namespaceNames = [
	NS_MEDIA            => 'Muòi-tā̤',
	NS_SPECIAL          => 'Dĕk-sṳ̀',
	NS_TALK             => 'Tō̤-lâung',
	NS_USER             => 'Ê̤ṳng-hô',
	NS_USER_TALK        => 'Ê̤ṳng-hô_tō̤-lâung',
	NS_PROJECT_TALK     => '$1_tō̤-lâung',
	NS_FILE             => 'Ùng-giông',
	NS_FILE_TALK        => 'Ùng-giông_tō̤-lâung',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_tō̤-lâung',
	NS_TEMPLATE         => 'Muò-bēng',
	NS_TEMPLATE_TALK    => 'Muò-bēng_tō̤-lâung',
	NS_HELP             => 'Siók-mìng',
	NS_HELP_TALK        => 'Siók-mìng_tō̤-lâung',
	NS_CATEGORY         => 'Hŭng-lôi',
	NS_CATEGORY_TALK    => 'Hŭng-lôi_tō̤-lâung',
];

$namespaceAliases = [
	'Muòi-tā̤' => NS_MEDIA,
	'Dĕk-sṳ̀' => NS_SPECIAL,
	'Tō̤-lâung' => NS_TALK,
	'Ê̤ṳng-hô' => NS_USER,
	'Ê̤ṳng-hô_tō̤-lâung' => NS_USER_TALK,
	'$1_tō̤-lâung' => NS_PROJECT_TALK,
	'Ùng-giông' => NS_FILE,
	'Ùng-giông_tō̤-lâung' => NS_FILE_TALK,
	'MediaWiki_tō̤-lâung' => NS_MEDIAWIKI_TALK,
	'Muò-bēng' => NS_TEMPLATE,
	'Muò-bēng_tō̤-lâung' => NS_TEMPLATE_TALK,
	'Siók-mìng' => NS_HELP,
	'Bŏng-cô' => NS_HELP,
	'Siók-mìng_tō̤-lâung' => NS_HELP_TALK,
	'Bŏng-cô_tō̤-lâung' => NS_HELP_TALK,
	'Hŭng-lôi' => NS_CATEGORY,
	'Hŭng-lôi_tō̤-lâung' => NS_CATEGORY_TALK,
];

$datePreferences = [
	'default',
	'ISO 8601',
];

$defaultDateFormat = 'cdo-latn';

$dateFormats = [
	'cdo-latn time' => 'H:i',
	'cdo-latn date' => 'Y "nièng" n "nguŏk" j "hô̤" (l)',
	'cdo-latn monthonly' => 'Y "nièng" n "nguŏk"',
	'cdo-latn both' => 'Y "nièng" n "nguŏk" j "hô̤" (l) H:i',
	'cdo-latn pretty' => 'n "nguŏk" j "hô̤"',
];
