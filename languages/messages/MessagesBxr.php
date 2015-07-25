<?php
/** буряад (буряад)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Меди',
	NS_SPECIAL          => 'Тусхай',
	NS_TALK             => 'Хэлэлсэхэ',
	NS_USER             => 'Хэрэглэгшэ',
	NS_USER_TALK        => 'Хэрэглэгшые_хэлэлсэхэ',
	NS_PROJECT_TALK     => '$1_тухай_хэлэлсэхэ',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_хэлэлсэхэ',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_хэлэлсэхэ',
	NS_TEMPLATE         => 'Загбар',
	NS_TEMPLATE_TALK    => 'Загбар_хэлэлсэхэ',
	NS_HELP             => 'Туһаламжа',
	NS_HELP_TALK        => 'Туһаламжа_хэлэлсэл',
	NS_CATEGORY         => 'Категори',
	NS_CATEGORY_TALK    => 'Категори_хэлэлсэхэ',
);

$namespaceAliases = array(
	# Russian namespaces
	'Обсуждение'                         => NS_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,
);

// Remove Russian gender aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Activeusers'               => array( 'Эдэбхитэй_хэрэглэгшэд' ),
	'Allmessages'               => array( 'Бүхы_зурбас' ),
	'Allpages'                  => array( 'Соохи_бүхы_хуудаһан' ),
	'Ancientpages'              => array( 'Хуушарһан_хуудаһан' ),
	'Categories'                => array( 'Категоринууд' ),
	'ComparePages'              => array( 'Хуудаһа_харисуулха' ),
	'Confirmemail'              => array( 'Сахим_хаяг_баталха' ),
	'CreateAccount'             => array( 'Данса_үүсхэхэ' ),
	'MyLanguage'                => array( 'Минии_хэлэн' ),
	'Mypage'                    => array( 'Минии_хуудаһан' ),
	'Mytalk'                    => array( 'Минии_хэлэлсэл' ),
	'Myuploads'                 => array( 'Минии_ашаалһан_зүйл' ),
	'Newpages'                  => array( 'Шэнэ_хуудаһан' ),
	'Protectedpages'            => array( 'Хамгаалалтатай_хуудаһан' ),
	'Protectedtitles'           => array( 'Хамгаалалтатай_гаршаг' ),
	'Recentchanges'             => array( 'Сайтдахи_хубилалтанууд' ),
	'Upload'                    => array( 'Ашаалха' ),
	'Userlogin'                 => array( 'Нэбтэрхэ' ),
	'Userlogout'                => array( 'Гараха' ),
);

