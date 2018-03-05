<?php
/** Eastern Mari (олык марий)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amdf
 * @author Azim
 * @author Jose77
 * @author Kaganer
 * @author Lifeway
 * @author Shirayuki
 * @author Сай
 * @author Санюн Вадик
 */

$fallback = 'mrj, ru';

$namespaceNames = [
	NS_SPECIAL          => 'Лӱмын_ыштыме',
	NS_TALK             => 'Каҥашымаш',
	NS_USER             => 'Пайдаланыше',
	NS_USER_TALK        => 'Пайдаланышын_каҥашымашыже',
	NS_PROJECT_TALK     => '$1ын_каҥашымашыже',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_шотышто_каҥашымаш',
	NS_TEMPLATE         => 'Кышкар',
	NS_TEMPLATE_TALK    => 'Кышкар_шотышто_каҥашымаш',
	NS_HELP             => 'Полшык',
	NS_HELP_TALK        => 'Полшык_шотышто_каҥашымаш',
	NS_CATEGORY         => 'Категорий',
	NS_CATEGORY_TALK    => 'Категорий_шотышто_каҥашымаш',
];

$namespaceAliases = [
	// Fallbacks for all 'ru' namespace aliases
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
	'Обсуждение_категории'               => NS_CATEGORY_TALK,

	// Namspace changes
	'Пайдаланышын_каҥашымаш'    => NS_USER_TALK,
	'$1ын_каҥашымаш'            => NS_PROJECT_TALK,
	'Файлын_каҥашымаш'          => NS_FILE_TALK,
	'Ямдылык'                   => NS_TEMPLATE,
	'Ямдылык_шотышто_каҥашымаш' => NS_TEMPLATE_TALK,
	'Ямдылыкын_каҥашымаш'       => NS_TEMPLATE_TALK,
	'Полшыкын_каҥашымаш'        => NS_HELP_TALK,
	'Категорийын_каҥашымаш'     => NS_CATEGORY_TALK,
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Blankpage'                 => [ 'Пуста_лаштык' ],
	'BrokenRedirects'           => [ 'Кӱрылтшӧ_вес_вере_колтымаш-влак' ],
	'Categories'                => [ 'Категорий-влак' ],
	'ComparePages'              => [ 'Лаштык-влакым_тергымаш' ],
	'Emailuser'                 => [ 'Пайдаланышылан_серышым_колташ' ],
	'Longpages'                 => [ 'Кужу_лаштык-влак' ],
	'Preferences'               => [ 'Келыштарымаш' ],
	'Recentchanges'             => [ 'Пытартыш_тӧрлатымаш-влак' ],
	'Search'                    => [ 'Кычалмаш' ],
	'Statistics'                => [ 'Иктешлымаш' ],
	'Watchlist'                 => [ 'Эскерымаш_лӱмер' ],
];

$magicWords = [
	'img_right'                 => [ '1', 'справа', 'пурла', 'right' ],
	'img_left'                  => [ '1', 'шола', 'слева', 'left' ],
	'img_border'                => [ '1', 'чек', 'граница', 'border' ],
	'img_sub'                   => [ '1', 'йымалне', 'под', 'sub' ],
	'img_super'                 => [ '1', 'ӱмбалне', 'над', 'super', 'sup' ],
	'img_top'                   => [ '1', 'кӱшычын', 'сверху', 'top' ],
	'img_middle'                => [ '1', 'покшелне', 'посередине', 'middle' ],
	'img_bottom'                => [ '1', 'ӱлычын', 'снизу', 'bottom' ],
	'sitename'                  => [ '1', 'САЙТЛӰМ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ],
];
