<?php
/** Khakas (хакас)
 *
 * @file
 * @ingroup Languages
 *
 * @author AlexandrL714
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ӧнетін',
	NS_TALK             => 'Ӱзӱріг',
	NS_USER             => 'Араласчы',
	NS_USER_TALK        => 'Араласчының_ӱзӱрии',
	NS_PROJECT_TALK     => '$1_ӱзӱрии',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлның_ӱзӱрии',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_ӱзӱрии',
	NS_TEMPLATE         => 'Халып',
	NS_TEMPLATE_TALK    => 'Халыптың_ӱзӱрии',
	NS_HELP             => 'Полызығ',
	NS_HELP_TALK        => 'Полызығның_ӱзӱрии',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категорияның_ӱзӱрии',
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяҒғІіӦӧӰӱӋӌҶҷ]+)(.*)$/sDu';
