<?php
/** Udmurt (Удмурт)
 *
 * @addtogroup Language
 *
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
'createaccount' => 'выль вики-авторлэн регистрациез',
'edit' => 'тупатыны',
'hist' => 'история',
'history' => 'Бамлэн историез',
'history_short' => 'история',
'login' => 'Википедие пырон',
'mycontris' => 'мынам статьяосы',
'mytalk' => 'викиавтор сярысь вераськон',
'nstab-user' => 'викиавтор',
'preferences' => 'настройкаос',
);


