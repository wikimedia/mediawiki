<?php
/** Gan (贛語)
 *
 * @ingroup Language
 * @file
 *
 * @author Philip <philip.npc@gmail.com>
 */

$fallback = 'gan-hant';

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

$messages = array(
/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-gan-hans' => 'disable',
Variants for Chinese language
*/
'variantname-gan-hans' => '简体',
'variantname-gan-hant' => '繁體',
'variantname-gan'      => '贛語原文',
);