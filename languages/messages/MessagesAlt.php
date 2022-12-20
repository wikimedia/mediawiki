<?php
/** Southern Altay (тÿштÿк алтай тил)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Аҥылу',
	NS_TALK             => 'Шӱӱжӱ',
	NS_USER             => 'Туружаачы',
	NS_USER_TALK        => 'Туружаачыны_шӱӱжери',
	NS_PROJECT_TALK     => '$1_шӱӱжери',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлды_шӱӱжери',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-ни_шӱӱжери',
	NS_TEMPLATE         => 'Ӱлекер',
	NS_TEMPLATE_TALK    => 'Ӱлекерди_шӱӱжери',
	NS_HELP             => 'Болуш',
	NS_HELP_TALK        => 'Болушты_шӱӱжери',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категорияны_шӱӱжери',
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяјҥӧӱ]+)(.*)$/sDu';
