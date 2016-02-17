<?php
/** лакку (лакку)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amire80
 */

$fallback = 'ru';

$separatorTransformTable = [
	',' => "\xc2\xa0", # nbsp
	'.' => ','
];

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Къуллугъирал_лажин',
	NS_TALK             => 'Ихтилат',
	NS_USER             => 'Гьуртту_хьума',
	NS_USER_TALK        => 'Гьуртту_хьуминнал_ихтилат',
	NS_PROJECT_TALK     => '$1лиясса_ихтилат',
	NS_FILE             => 'Сурат',
	NS_FILE_TALK        => 'Суратраясса_ихтилат',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWikiлиясса_ихтилат',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблондалиясса_ихтилат',
	NS_HELP             => 'Кумаг',
	NS_HELP_TALK        => 'Кумаграясса_ихтилат',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категориялиясса_ихтилат',
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӀ1“»]+)(.*)$/sDu';

