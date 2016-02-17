<?php
/** Abkhazian (Аҧсшәа)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Амедиа',
	NS_SPECIAL          => 'Цастәи',
	NS_TALK             => 'Ахцәажәара',
	NS_USER             => 'Алахәыла',
	NS_USER_TALK        => 'Алахәыла_ахцәажәара',
	NS_PROJECT_TALK     => '$1_ахцәажәара',
	NS_FILE             => 'Афаил',
	NS_FILE_TALK        => 'Афаил_ахцәажәара',
	NS_MEDIAWIKI        => 'Амедиавики',
	NS_MEDIAWIKI_TALK   => 'Амедиавики_ахцәажәара',
	NS_TEMPLATE         => 'Ашаблон',
	NS_TEMPLATE_TALK    => 'Ашаблон_ахцәажәара',
	NS_HELP             => 'Ацхыраара',
	NS_HELP_TALK        => 'Ацхыраара_ахцәажәара',
	NS_CATEGORY         => 'Акатегориа',
	NS_CATEGORY_TALK    => 'Акатегориа_ахцәажәара',
];

$namespaceAliases = [
	'Иалахә'             => NS_USER,

	// Backward compat. Fallbacks from 'ru'.
	'Медиа'                => NS_MEDIA,
	'Служебная'            => NS_SPECIAL,
	'Обсуждение'           => NS_TALK,
	'Участник'             => NS_USER,
	'Обсуждение_участника' => NS_USER_TALK,
	'Обсуждение_$1'        => NS_PROJECT_TALK,
	'Файл'                 => NS_FILE,
	'Обсуждение_файла'     => NS_FILE_TALK,
	'MediaWiki'            => NS_MEDIAWIKI,
	'Обсуждение_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Шаблон'               => NS_TEMPLATE,
	'Обсуждение_шаблона'   => NS_TEMPLATE_TALK,
	'Справка'              => NS_HELP,
	'Обсуждение_справки'   => NS_HELP_TALK,
	'Категория'            => NS_CATEGORY,
	'Обсуждение_категории' => NS_CATEGORY_TALK
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Categories'                => [ 'Акатегориақәа' ],
	'Mycontributions'           => [ 'Архиарақәа' ],
	'Mypage'                    => [ 'Садаҟьа' ],
	'Mytalk'                    => [ 'Сахцәажәара' ],
	'Newimages'                 => [ 'АфаилқәаҾыц' ],
	'Newpages'                  => [ 'АдаҟьақәаҾыц' ],
	'Randompage'                => [ 'Машәырлатәи' ],
	'Recentchanges'             => [ 'АрҽеираҾыцқәа' ],
	'Search'                    => [ 'Аҧшаара' ],
	'Specialpages'              => [ 'ЦастәиАдаҟьақәа' ],
	'Upload'                    => [ 'Аҭагалара' ],
];

$magicWords = [
	'language'                  => [ '0', '#АБЫЗШӘА:', '#ЯЗЫК:', '#LANGUAGE:' ],
	'special'                   => [ '0', 'цастәи', 'служебная', 'special' ],
	'index'                     => [ '1', '__АИНДЕКС__', '__ИНДЕКС__', '__INDEX__' ],
];

