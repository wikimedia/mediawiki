<?php
/** Hill Mari (кырык мары)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amdf
 * @author Andrijko Z.
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Спецӹлӹштӓш',
	NS_TALK             => 'Кӓнгӓшӹмӓш',
	NS_USER             => 'Сирӹшӹ',
	NS_USER_TALK        => 'Сирӹшӹм_кӓнгӓшӹмӓш',
	NS_PROJECT_TALK     => '$1_кӓнгӓшӹмӓш',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлым_кӓнгӓшӹмӓш',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-м_кӓнгӓшӹмӓш',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблоным_кӓнгӓшӹмӓш',
	NS_HELP             => 'Палшык',
	NS_HELP_TALK        => 'Палшыкым_кӓнгӓшӹмӓш',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категорим_кӓнгӓшӹмӓш',
);

$namespaceAliases = array(
	'Сирӹшӹн_кӓнгӓшӹмӓшӹжӹ' => NS_USER_TALK,
	'Файл_кӓнгӓшӹмӓш'       => NS_FILE_TALK,
	'MediaWiki_кӓнгӓшӹмӓш'  => NS_MEDIAWIKI_TALK,
	'Шаблон_кӓнгӓшӹмӓш'     => NS_TEMPLATE_TALK,
	'Палшыкын_кӓнгӓшӹмӓш'   => NS_HELP_TALK,
	'Категори_кӓнгӓшӹмӓш'   => NS_CATEGORY_TALK,
);

// Remove Russian aliases
$namespaceGenderAliases = array();

