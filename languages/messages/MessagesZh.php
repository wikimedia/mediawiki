<?php
/** Chinese (中文)
 *
 * @ingroup Language
 * @file
 *
 * @author Philip
 * @author Wong128hk
 */

# Stub message file for converter code "zh"

$fallback = 'zh-hans';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk'
);

$namespaceAliases = array(
	'媒体'	=> NS_MEDIA,
	'媒體'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'对话'  => NS_TALK,
	'對話'  => NS_TALK,
	'讨论'	=> NS_TALK,
	'討論'	=> NS_TALK,
	'用户'  => NS_USER,
	'用戶'  => NS_USER,
	'用户对话' => NS_USER_TALK,
	'用戶對話' => NS_USER_TALK,
	'用户讨论' => NS_USER_TALK,
	'用戶討論' => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	#'{{SITENAME}}_对话' => NS_PROJECT_TALK
	#"{{SITENAME}}_對話" => NS_PROJECT_TALK
	'图像' => NS_FILE,
	'圖像' => NS_FILE,
	'档案' => NS_FILE,
	'檔案' => NS_FILE,
	'文件' => NS_FILE,
	'图像对话' => NS_FILE_TALK,
	'圖像對話' => NS_FILE_TALK,
	'图像讨论' => NS_FILE_TALK,
	'圖像討論' => NS_FILE_TALK,
	'档案对话' => NS_FILE_TALK,
	'檔案對話' => NS_FILE_TALK,
	'档案讨论' => NS_FILE_TALK,
	'檔案討論' => NS_FILE_TALK,
	'文件对话' => NS_FILE_TALK,
	'文件對話' => NS_FILE_TALK,
	'文件讨论' => NS_FILE_TALK,
	'文件討論' => NS_FILE_TALK,
	'模板'	=> NS_TEMPLATE,
	'样板'  => NS_TEMPLATE,
	'樣板'  => NS_TEMPLATE,
	'模板对话' => NS_TEMPLATE_TALK,
	'模板對話' => NS_TEMPLATE_TALK,
	'模板讨论' => NS_TEMPLATE_TALK,
	'模板討論' => NS_TEMPLATE_TALK,
	'样板对话' => NS_TEMPLATE_TALK,
	'樣板對話' => NS_TEMPLATE_TALK,
	'样板讨论' => NS_TEMPLATE_TALK,
	'樣板討論' => NS_TEMPLATE_TALK,
	'帮助'	=> NS_HELP,
	'幫助'  => NS_HELP,
	'帮助对话' => NS_HELP_TALK,
	'幫助對話' => NS_HELP_TALK,
	'帮助讨论' => NS_HELP_TALK,
	'幫助討論' => NS_HELP_TALK,
	'分类'	=> NS_CATEGORY,
	'分類'  => NS_CATEGORY,
	'分类对话'=> NS_CATEGORY_TALK,
	'分類對話' => NS_CATEGORY_TALK,
	'分类讨论'=> NS_CATEGORY_TALK,
	'分類討論' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-norollbackdiff' => '進行回退後略過差異比較',

# Move page
'move-redirect-suppressed' => '已禁止重新定向',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '简体',
'variantname-zh-hant' => '繁體',
'variantname-zh-cn'   => '大陆',
'variantname-zh-tw'   => '台灣',
'variantname-zh-hk'   => '香港',
'variantname-zh-mo'   => '澳門',
'variantname-zh-sg'   => '新加坡',
'variantname-zh-my'   => '大马',
'variantname-zh'      => '中文原文',

);
