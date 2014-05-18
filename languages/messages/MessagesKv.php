<?php
/** Komi (коми)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Comp1089
 * @author Yufereff
 * @author ОйЛ
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_SPECIAL          => 'Отсасян',
	NS_TALK             => 'Сёрнитанiн',
	NS_USER             => 'Пырысь',
	NS_USER_TALK        => 'Пырыськӧд_сёрнитанiн',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_донъялӧм',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_донъялӧм',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_донъялӧм',
);

$namespaceAliases = array(
	// Backward compat. Fallbacks from 'ru'.
	'Медиа'                              => NS_MEDIA,
	'Служебная'                          => NS_SPECIAL,
	'Обсуждение'                         => NS_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Файл'                               => NS_FILE,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Шаблон'                             => NS_TEMPLATE,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK
);

