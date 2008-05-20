<?php
/** Udmurt (Удмурт)
 *
 * @ingroup Language
 * @file
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Панель',
	NS_MAIN             => '',
	NS_TALK             => 'Вераськон',
	NS_USER             => 'Викиавтор',
	NS_USER_TALK        => 'Викиавтор_сярысь_вераськон',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_сярысь_вераськон',
	NS_IMAGE            => 'Суред',
	NS_IMAGE_TALK       => 'Суред_сярысь_вераськон',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_сярысь_вераськон',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблон_сярысь_вераськон',
	NS_HELP             => 'Валэктон',
	NS_HELP_TALK        => 'Валэктон_сярысь_вераськон',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_сярысь_вераськон',
);

$linkTrail = '/^([a-zа-яёӝӟӥӧӵ“»]+)(.*)$/sDu';
$fallback8bitEncoding = 'windows-1251';
$separatorTransformTable = array(',' => ' ', '.' => ',' );

$messages = array(
'linkprefix' => '/^(.*?)(„|«)$/sDu',

'article' => 'Статья',
'mytalk'  => 'викиавтор сярысь вераськон',

'history'       => 'Бамлэн историез',
'history_short' => 'история',
'edit'          => 'тупатыны',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user' => 'викиавтор',

# Login and logout pages
'login'         => 'Википедие пырон',
'createaccount' => 'выль вики-авторлэн регистрациез',

# Preferences page
'preferences' => 'настройкаос',

# Recent changes
'hist' => 'история',

# Contributions
'mycontris' => 'мынам статьяосы',

);
