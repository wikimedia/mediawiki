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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

// Remove Russian gender aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Activeusers'               => [ 'Эдэбхитэй_хэрэглэгшэд' ],
	'Allmessages'               => [ 'Бүхы_зурбас' ],
	'Allpages'                  => [ 'Соохи_бүхы_хуудаһан' ],
	'Ancientpages'              => [ 'Хуушарһан_хуудаһан' ],
	'Categories'                => [ 'Категоринууд' ],
	'ComparePages'              => [ 'Хуудаһа_харисуулха' ],
	'Confirmemail'              => [ 'Сахим_хаяг_баталха' ],
	'CreateAccount'             => [ 'Данса_үүсхэхэ' ],
	'MyLanguage'                => [ 'Минии_хэлэн' ],
	'Mypage'                    => [ 'Минии_хуудаһан' ],
	'Mytalk'                    => [ 'Минии_хэлэлсэл' ],
	'Myuploads'                 => [ 'Минии_ашаалһан_зүйл' ],
	'Newpages'                  => [ 'Шэнэ_хуудаһан' ],
	'Protectedpages'            => [ 'Хамгаалалтатай_хуудаһан' ],
	'Protectedtitles'           => [ 'Хамгаалалтатай_гаршаг' ],
	'Recentchanges'             => [ 'Сайтдахи_хубилалтанууд' ],
	'Upload'                    => [ 'Ашаалха' ],
	'Userlogin'                 => [ 'Нэбтэрхэ' ],
	'Userlogout'                => [ 'Гараха' ],
];

