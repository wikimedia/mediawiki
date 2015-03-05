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

$fallback = 'ru';

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Blankpage'                 => array( 'Пуста_лаштык' ),
	'BrokenRedirects'           => array( 'Кӱрылтшӧ_вес_вере_колтымаш-влак' ),
	'Categories'                => array( 'Категорий-влак' ),
	'ComparePages'              => array( 'Лаштык-влакым_тергымаш' ),
	'Emailuser'                 => array( 'Пайдаланышылан_серышым_колташ' ),
	'Longpages'                 => array( 'Кужу_лаштык-влак' ),
	'Preferences'               => array( 'Келыштарымаш' ),
	'Recentchanges'             => array( 'Пытартыш_тӧрлатымаш-влак' ),
	'Search'                    => array( 'Кычалмаш' ),
	'Statistics'                => array( 'Иктешлымаш' ),
	'Watchlist'                 => array( 'Эскерымаш_лӱмер' ),
);

$magicWords = array(
	'img_right'                 => array( '1', 'пурла', 'справа', 'right' ),
	'img_left'                  => array( '1', 'шола', 'слева', 'left' ),
	'img_border'                => array( '1', 'чек', 'граница', 'border' ),
	'img_sub'                   => array( '1', 'йымалне', 'под', 'sub' ),
	'img_super'                 => array( '1', 'ӱмбалне', 'над', 'super', 'sup' ),
	'img_top'                   => array( '1', 'кӱшычын', 'сверху', 'top' ),
	'img_middle'                => array( '1', 'покшелне', 'посередине', 'middle' ),
	'img_bottom'                => array( '1', 'ӱлычын', 'снизу', 'bottom' ),
	'sitename'                  => array( '1', 'САЙТЛӰМ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
);

