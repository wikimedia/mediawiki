<?php
/** Traditional Gan script (贛語（繁體）)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'gan, gan-hans, zh-hant, zh, zh-hans';

$namespaceNames = [
	NS_MEDIA            => '媒體',
	NS_SPECIAL          => '特別',
	NS_TALK             => '談詑',
	NS_USER             => '用戶',
	NS_USER_TALK        => '用戶・談詑',
	NS_PROJECT_TALK     => '$1・談詑',
	NS_FILE             => '檔案',
	NS_FILE_TALK        => '檔案・談詑',
	NS_MEDIAWIKI_TALK   => 'MediaWiki・談詑',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板・談詑',
	NS_HELP             => '幫助',
	NS_HELP_TALK        => '幫助・談詑',
	NS_CATEGORY         => '分類',
	NS_CATEGORY_TALK    => '分類・談詑',
];

$namespaceAliases = [
	'媒體' => NS_MEDIA,
	'特別' => NS_SPECIAL,
	'談詑' => NS_TALK,
	'用戶' => NS_USER,
	'用戶・談詑' => NS_USER_TALK,
	'用戶談詑' => NS_USER_TALK,
	'$1・談詑' => NS_PROJECT_TALK,
	'$1談詑' => NS_PROJECT_TALK,
	'$1_談詑' => NS_PROJECT_TALK,
	'檔案' => NS_FILE,
	'文檔' => NS_FILE,
	'檔案・談詑' => NS_FILE_TALK,
	'文檔・談詑' => NS_FILE_TALK,
	'檔案談詑' => NS_FILE_TALK,
	'文檔談詑' => NS_FILE_TALK,
	'MediaWiki・談詑' => NS_MEDIAWIKI_TALK,
	'MediaWiki談詑' => NS_MEDIAWIKI_TALK,
	'模板' => NS_TEMPLATE,
	'模板・談詑' => NS_TEMPLATE_TALK,
	'模板談詑' => NS_TEMPLATE_TALK,
	'幫助' => NS_HELP,
	'幫助・談詑' => NS_HELP_TALK,
	'幫助談詑' => NS_HELP_TALK,
	'分類' => NS_CATEGORY,
	'分類・談詑' => NS_CATEGORY_TALK,
	'分類談詑' => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Ancientpages'              => [ '老早嗰頁面' ],
	'BrokenRedirects'           => [ '壞吥嗰重定向頁' ],
	'CreateAccount'             => [ '新建隻帳戶' ],
	'Fewestrevisions'           => [ '最少改動嗰頁面' ],
	'Longpages'                 => [ '莽文章' ],
	'Mostcategories'            => [ '最多分類嗰頁面' ],
	'Mostimages'                => [ '拕連得最多嗰檔案' ],
	'Mostlinked'                => [ '拕連得最多嗰頁面' ],
	'Mostlinkedcategories'      => [ '拕連得最多嗰分類' ],
	'Mostlinkedtemplates'       => [ '拕連得最多嗰模板' ],
	'Mostrevisions'             => [ '最多改動嗰頁面' ],
	'Newpages'                  => [ '全新嗰頁面' ],
	'Preferences'               => [ '個人設定' ],
	'Recentchanges'             => [ '最晏嗰改動' ],
	'Shortpages'                => [ '細文章' ],
	'Uncategorizedcategories'   => [ '冇歸類嗰分類' ],
	'Uncategorizedimages'       => [ '冇歸類嗰檔案' ],
	'Uncategorizedpages'        => [ '冇歸類嗰頁面' ],
	'Uncategorizedtemplates'    => [ '冇歸類嗰模板' ],
	'Unusedcategories'          => [ '冇用嗰分類' ],
	'Unusedimages'              => [ '冇用嗰檔案' ],
	'Watchlist'                 => [ '監視列表' ],
];
