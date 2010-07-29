<?php
/** Udmurt (Удмурт)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaganer
 * @author ОйЛ
 * @author לערי ריינהארט
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Панель',
	NS_TALK             => 'Вераськон',
	NS_USER             => 'Викиавтор',
	NS_USER_TALK        => 'Викиавтор_сярысь_вераськон',
	NS_PROJECT_TALK     => '$1_сярысь_вераськон',
	NS_FILE             => 'Суред',
	NS_FILE_TALK        => 'Суред_сярысь_вераськон',
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
$separatorTransformTable = array( ',' => ' ', '.' => ',' );

$messages = array(
'linkprefix' => '/^(.*?)(„|«)$/sDu',

'article' => 'Статья',
'mypage'  => 'Ас бам',
'mytalk'  => 'викиавтор сярысь вераськон',

# Cologne Blue skin
'qbspecialpages' => 'Ваньмыз панельёс',

'help'             => 'Валэктонъёс',
'history'          => 'Бамлэн историез',
'history_short'    => 'история',
'printableversion' => 'Печатламон версия',
'permalink'        => 'Ӵапак та версиезлы линк',
'edit'             => 'тупатыны',
'delete'           => 'Быдтыны',
'protect'          => 'Утьыны',
'talk'             => 'Вераськон',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'Выль иворъёс',
'currentevents-url'    => 'Project:Выль иворъёс',
'helppage'             => 'Help:Валэктон',
'mainpage'             => 'Кутскон бам',
'mainpage-description' => 'Кутскон бам',
'portal'               => 'Сообщество',
'portal-url'           => 'Project:Портал сообщества',

'editsection' => 'тупатыны',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => 'викиавтор',
'nstab-mediawiki' => 'Ивортон',

# General errors
'viewsource' => 'Кодзэ учкыны',

# Login and logout pages
'login'                   => 'Википедие пырон',
'nav-login-createaccount' => 'Нимдэс вераны / Регистрациез ортчытыны',
'userlogin'               => 'Регистрациез ортчытыны яке Википедие пырыны',
'logout'                  => 'Кошкыны',
'userlogout'              => 'Кошкыны',
'createaccount'           => 'выль вики-авторлэн регистрациез',

# Edit pages
'summary'       => 'Мар но малы тупатэмын? (вакчияк):',
'minoredit'     => 'Ичи воштон',
'noarticletext' => "В настоящий момент текст на данной странице отсутствует.
Вы можете [[Special:Search/{{PAGENAME}}|найти упоминание данного названия]] на других страницах,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} найти соответствующие записи журналов],
или '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} создать страницу с таким названием]'''</span>.",

# Search results
'searchresulttext' => 'Для получения более подробной информации о поиске на страницах проекта, см. [[{{MediaWiki:Helppage}}|справочный раздел]].',
'searchhelp-url'   => 'Help:Валэктон',

# Preferences page
'preferences'     => 'настройкаос',
'mypreferences'   => 'Настройкаос',
'prefs-watchlist' => 'Чаклан список',

# Recent changes
'recentchanges' => 'Выль тупатонъёс',
'hist'          => 'история',

# Recent changes linked
'recentchangeslinked'         => 'Герӟаськем тупатонъёс',
'recentchangeslinked-feed'    => 'Герӟаськем тупатонъёс',
'recentchangeslinked-toolbox' => 'Герӟаськем тупатонъёс',

# Upload
'upload' => 'Файл поныны',

# File description page
'sharedupload' => 'Этот файл из $1 и может использоваться в других проектах.',

# Random page
'randompage' => 'Олокыӵе статья',

# Miscellaneous special pages
'move' => 'Мукет интые выжтыны',

# E-mail user
'emailmessage' => 'Ивортон:',

# Watchlist
'watchlist'   => 'Чаклано статьяос',
'mywatchlist' => 'Чаклан список',
'watch'       => 'Чаклано',
'unwatch'     => 'Чакламысь дугдыны',

# Contributions
'mycontris' => 'мынам статьяосы',

# What links here
'whatlinkshere' => 'Татчы линкъёс',

# Move page
'movearticle'     => 'Статьяез мукет интые выжтыны',
'move-watch'      => 'Та бамез чаклан списоке пыртыны',
'delete_and_move' => 'Быдтыны но мукет интые выжтыны',

# Namespace 8 related
'allmessagesname' => 'Ивортон',

# Special:SpecialPages
'specialpages' => 'Ваньмыз панельёс',

);
