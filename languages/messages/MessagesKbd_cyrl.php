<?php
/** Адыгэбзэ (Адыгэбзэ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

# $fallback = 'ru'; // bug 27785

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Служебная',
	NS_TALK             => 'Тепсэлъэхьыгъуэ',
	NS_USER             => 'ЦӀыхухэт',
	NS_USER_TALK        => 'ЦӀыхухэт_тепсэлъэхьыгъуэ',
	NS_PROJECT_TALK     => '$1_тепсэлъэхьыгъуэ',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_тепсэлъэхьыгъуэ',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_тепсэлъэхьыгъуэ',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_тепсэлъэхьыгъуэ',
	NS_HELP             => 'ДэӀэпыкъуэгъуэ',
	NS_HELP_TALK        => 'ДэӀэпыкъуэгъуэ_тепсэлъэхьыгъуэ',
	NS_CATEGORY         => 'Категориэ',
	NS_CATEGORY_TALK    => 'Категориэ_тепсэлъэхьыгъуэ',
);

$namespaceAliases = array(
	# Russian namespaces
	'Обсуждение'                         => NS_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,
);

// Remove Russian gender aliases
$namespaceGenderAliases = array();

