<?php
/** Simplified Gan script (赣语（简体）)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'gan, gan-hant, zh-hans, zh, zh-hant';

$namespaceNames = [
	NS_MEDIA            => '媒体',
	NS_SPECIAL          => '特别',
	NS_TALK             => '谈詑',
	NS_USER             => '用户',
	NS_USER_TALK        => '用户・谈詑',
	NS_PROJECT_TALK     => '$1・谈詑',
	NS_FILE             => '文件',
	NS_FILE_TALK        => '文件・谈詑',
	NS_MEDIAWIKI_TALK   => 'MediaWiki・谈詑',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板・谈詑',
	NS_HELP             => '帮助',
	NS_HELP_TALK        => '帮助・谈詑',
	NS_CATEGORY         => '分类',
	NS_CATEGORY_TALK    => '分类・谈詑',
];

$namespaceAliases = [
	'媒体' => NS_MEDIA,
	'特别' => NS_SPECIAL,
	'谈詑' => NS_TALK,
	'用户' => NS_USER,
	'用户・谈詑' => NS_USER_TALK,
	'用户谈詑' => NS_USER_TALK,
	'$1・谈詑' => NS_PROJECT_TALK,
	'$1谈詑' => NS_PROJECT_TALK,
	'文件' => NS_FILE,
	'文件・谈詑' => NS_FILE_TALK,
	'文件谈詑' => NS_FILE_TALK,
	'MediaWiki・谈詑' => NS_MEDIAWIKI_TALK,
	'MediaWiki谈詑' => NS_MEDIAWIKI_TALK,
	'模板' => NS_TEMPLATE,
	'模板・谈詑' => NS_TEMPLATE_TALK,
	'模板谈詑' => NS_TEMPLATE_TALK,
	'帮助' => NS_HELP,
	'帮助・谈詑' => NS_HELP_TALK,
	'帮助谈詑' => NS_HELP_TALK,
	'分类' => NS_CATEGORY,
	'分类・谈詑' => NS_CATEGORY_TALK,
	'分类谈詑' => NS_CATEGORY_TALK,
];
