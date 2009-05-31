<?php
/** Лакку (Лакку)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = array(
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
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӀ1“»]+)(.*)$/sDu';

$messages = array(
'article' => 'Тарих',

# Cologne Blue skin
'qbedit' => 'Дакьин дуван',

'history'          => 'Макьала',
'history_short'    => 'Макьала',
'edit'             => 'Дакьин дуван',
'talkpagelinktext' => 'Ихтилат',
'talk'             => 'Ихтилат',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'Нанисса ишру',
'currentevents-url'    => 'Project:Нанисса ишру',
'mainpage'             => 'Агьаммур лажин',
'mainpage-description' => 'Агьаммур лажин',

'editsection' => 'дакьин дуван',
'editold'     => 'дакьин дуван',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Тарих',
'nstab-user'     => 'Гьуртту хьума',
'nstab-special'  => 'Къуллугъирал лажин',
'nstab-image'    => 'Сурат',
'nstab-template' => 'Шаблон',
'nstab-category' => 'Категория',

# Recent changes
'recentchanges' => 'Махъсса дахханашивурту',

# File description page
'file-anchor-link' => 'Сурат',
'filehist-user'    => 'Гьурттучув',

# Miscellaneous special pages
'move' => 'ЦIа даххана дан',

# Special:Log
'specialloguserlabel' => 'Гьурттучув:',

'sp-contributions-talk' => 'Ихтилат',

);
