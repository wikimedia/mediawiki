<?php
/** Min Nan Chinese (Bân-lâm-gú)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hiong3-eng5
 * @author Ianbu
 * @author Kaihsu
 */

$fallback = 'cdo, zh-hant';

$namespaceNames = array(
	NS_MEDIA            => 'Mûi-thé',
	NS_SPECIAL          => 'Tek-pia̍t',
	NS_TALK             => 'Thó-lūn',
	NS_USER             => 'Iōng-chiá',
	NS_USER_TALK        => 'Iōng-chiá_thó-lūn',
	NS_PROJECT_TALK     => '$1_thó-lūn',
	NS_FILE             => 'tóng-àn',
	NS_FILE_TALK        => 'tóng-àn_thó-lūn',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_thó-lūn',
	NS_TEMPLATE         => 'Pang-bô͘',
	NS_TEMPLATE_TALK    => 'Pang-bô͘_thó-lūn',
	NS_HELP             => 'Pang-chān',
	NS_HELP_TALK        => 'Pang-chān_thó-lūn',
	NS_CATEGORY         => 'Lūi-pia̍t',
	NS_CATEGORY_TALK    => 'Lūi-pia̍t_thó-lūn',
);

$namespaceAliases = array(
	'媒體' => NS_MEDIA,
	'特殊' => NS_SPECIAL,
	'討論' => NS_TALK,
	'用戶' => NS_USER,
	'用戶討論' => NS_USER_TALK,
	'$1討論' => NS_PROJECT_TALK,
	'文件' => NS_FILE,
	'文件討論' => NS_FILE_TALK,
	'媒體維基' => NS_MEDIAWIKI,
	'媒體維基討論' => NS_MEDIAWIKI_TALK,
	'模板' => NS_TEMPLATE,
	'模板討論' => NS_TEMPLATE_TALK,
	'幫助' => NS_HELP,
	'幫助討論' => NS_HELP_TALK,
	'分類' => NS_CATEGORY,
	'分類討論' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Recentchangeslinked'       => array( 'Siong-koan_ê_kái-piàn' ),
	'Specialpages'              => array( 'Te̍k-sû_ia̍h' ),
	'Upload'                    => array( 'Kā_tóng-àn_chiūⁿ-bāng' ),
	'Whatlinkshere'             => array( 'Tó-ūi_liân_kàu_chia' ),
);

$datePreferences = array(
	'default',
	'ISO 8601',
);

$defaultDateFormat = 'nan';

$dateFormats = array(
	'nan time' => 'H:i',
	'nan date' => 'Y-"nî" n-"goe̍h" j-"ji̍t" (l)',
	'nan both' => 'Y-"nî" n-"goe̍h" j-"ji̍t" (D) H:i',
);
