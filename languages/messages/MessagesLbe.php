<?php
/**
  * Lak language (лакку маз)
  *
  * @addtogroup Language
  */

$fallback = 'ru';

$separatorTransformTable = array(
	',' => "\xc2\xa0", # nbsp
	'.' => ','
);

$fallback8bitEncoding = 'windows-1251';
$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Къуллугъирал_лажин',
	NS_MAIN             => '',
	NS_TALK             => 'Ихтилат',
	NS_USER             => 'Гьуртту_хьума',
	NS_USER_TALK        => 'Гьуртту_хьуминнал_ихтилат', 
	#NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1лиясса_ихтилат',
	NS_IMAGE            => 'Сурат',
	NS_IMAGE_TALK       => 'Суратраясса_ихтилат',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWikiлиясса_ихтилат',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблондалиясса_ихтилат',
	NS_HELP             => 'Кумаг',
	NS_HELP_TALK        => 'Кумаграясса_ихтилат',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категориялиясса_ихтилат',
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӀ1“»]+)(.*)$/sDu';

$messages = array(
'article' => 'Тарих',

'history'          => 'Макьала',
'history_short'    => 'Макьала',
'edit'             => 'Дакьин дуван',
'talkpagelinktext' => 'Ихтилат',
'talk'             => 'Ихтилат',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'     => 'Тарих',
'nstab-user'     => 'Гьуртту хьума',
'nstab-special'  => 'Къуллугъирал лажин',
'nstab-template' => 'Шаблон',
'nstab-category' => 'Категория',

);
