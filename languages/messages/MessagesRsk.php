<?php
/** Pannonian Rusyn (руски)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Keresturec
 */

$fallback = 'sr-ec';

$namespaceNames = [
	NS_MEDIA            => 'Медий',
	NS_SPECIAL          => 'Окреме',
	NS_TALK             => 'Розгварка',
	NS_USER             => 'Хаснователь',
	NS_USER_TALK        => 'Розгварка_зоз_хасновательом',
	NS_PROJECT_TALK     => 'Розгварка_о_{{GRAMMAR:locative|$1}}',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Розгварка_о_файлу',
	NS_MEDIAWIKI        => 'МедияВики',
	NS_MEDIAWIKI_TALK   => 'Розгварка_о_МедияВикию',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Розгварка_о_шаблону',
	NS_HELP             => 'Помоц',
	NS_HELP_TALK        => 'Розгварка_о_помоци',
	NS_CATEGORY         => 'Катеґория',
	NS_CATEGORY_TALK    => 'Розгварка_о_катеґориї',
];

$namespaceGenderAliases = [
	NS_USER => [
		'male' => 'Хаснователь',
		'female' => 'Хаснователька'
	],
	NS_USER_TALK => [
		'male' => 'Розгварка_зоз_хасновательом',
		'female' => 'Розгварка_зоз_хасновательку'
	],
];

$linkTrail = '/^([a-zабвгґдеєжзиїйклмнопрстуфхцчшщюяь]+)(.*)$/sDu';
