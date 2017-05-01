<?php
/** Chuvash (Чӑвашла)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ятарлă',
	NS_TALK             => 'Сӳтсе_явасси',
	NS_USER             => 'Хутшăнакан',
	NS_USER_TALK        => 'Хутшăнаканăн_канашлу_страници',
	NS_PROJECT_TALK     => '$1_сӳтсе_явмалли',
	NS_FILE             => 'Ӳкерчĕк',
	NS_FILE_TALK        => 'Ӳкерчĕке_сӳтсе_явмалли',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сӳтсе_явмалли',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблона_сӳтсе_явмалли',
	NS_HELP             => 'Пулăшу',
	NS_HELP_TALK        => 'Пулăшăва_сӳтсе_явмалли',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорине_сӳтсе_явмалли',
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$linkPrefixExtension = true;
$linkTrail = '/^([a-zа-яĕçăӳ"»]+)(.*)$/sDu';
$linkPrefixCharset = 'a-zA-Z"\\x{80}-\\x{10ffff}';
