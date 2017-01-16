<?php
/** Karachay-Balkar (къарачай-малкъар)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Don Alessandro
 * @author GerardM
 * @author Iltever
 * @author Kaganer
 * @author Reedy
 * @author Къарачайлы
 */

$fallback = 'ru';

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Къуллукъ',
	NS_TALK             => 'Сюзюу',
	NS_USER             => 'Къошулуучу',
	NS_USER_TALK        => 'Къошулуучуну_сюзюу',
	NS_PROJECT_TALK     => '$1_сюзюу',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файлны_сюзюу',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-ни_сюзюу',
	NS_TEMPLATE         => 'Шаблон',
	NS_TEMPLATE_TALK    => 'Шаблонну_сюзюу',
	NS_HELP             => 'Болушлукъ',
	NS_HELP_TALK        => 'Болушлукъну_сюзюу',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категорияны_сюзюу',
];

// Remove Russian aliases
$namespaceGenderAliases = [];

$specialPageAliases = [
	'Activeusers'               => [ 'Тири_къошулуучула' ],
	'Allmessages'               => [ 'Системаны_билдириулери' ],
	'Allpages'                  => [ 'Бютеу_бетле' ],
	'Blankpage'                 => [ 'Бош_бет' ],
	'Block'                     => [ 'Блок_эт' ],
	'Booksources'               => [ 'Китабланы_къайнакълары' ],
	'BrokenRedirects'           => [ 'Джыртылгъан_редиректле' ],
	'Categories'                => [ 'Категорияла' ],
	'ChangeEmail'               => [ 'E-mail’ни_ауушдур' ],
	'ChangePassword'            => [ 'Паролну_ауушдур' ],
	'ComparePages'              => [ 'Бетлени_тенглешдириу' ],
	'Confirmemail'              => [ 'E-mail’ни_тюзлюгюн_бегит' ],
	'Contributions'             => [ 'Къошум' ],
	'CreateAccount'             => [ 'Тергеу_джазыуну_къура', 'Къошулуучуну_къура', 'Регистрация_эт' ],
	'Deadendpages'              => [ 'Чыкъмазча_бетле' ],
	'DeletedContributions'      => [ 'Кетерилген_къошум' ],
	'DoubleRedirects'           => [ 'Экили_редирект' ],
	'EditWatchlist'             => [ 'Кёздеги_тизмени_тюрлендир' ],
	'Emailuser'                 => [ 'Къошулуучугъа_джазма', 'Джазма_ий' ],
	'Export'                    => [ 'Экспорт', 'Къотарыу' ],
	'FileDuplicateSearch'       => [ 'Файлланы_дубликатларын_излеу' ],
	'Filepath'                  => [ 'Файлгъа_джол' ],
	'Import'                    => [ 'Импорт' ],
	'BlockList'                 => [ 'Блок_этиулени_тизмеси', 'Блок_этиуле' ],
	'LinkSearch'                => [ 'Джибериуле_излеу' ],
	'Listadmins'                => [ 'Администраторланы_тизмеси' ],
	'Listbots'                  => [ 'Ботланы_тизмеси' ],
	'Listfiles'                 => [ 'Файлланы_тизмеси', 'Суратланы_тизмеси' ],
	'Listgrouprights'           => [ 'Къошулуучу_къауумланы_хакълары', 'Къауумланы_хакъларыны_тизмеси' ],
	'Listredirects'             => [ 'Редиректлени_тизмеси' ],
	'Listusers'                 => [ 'Къошулуучуланы_тизмеси' ],
	'Lockdb'                    => [ 'Билгиле_базаны_блок_эт' ],
	'Log'                       => [ 'Журналла', 'Журнал' ],
	'Lonelypages'               => [ 'Изоляция_этилген_бетле' ],
	'Longpages'                 => [ 'Узун_бетле' ],
	'MergeHistory'              => [ 'Тарихлени_бирикдириу' ],
	'MIMEsearch'                => [ 'MIME’ге_кёре_излеу' ],
	'Mostimages'                => [ 'Эм_кёб_хайырланнган_файлла' ],
	'Movepage'                  => [ 'Бетни_атын_тюрлендириу', 'Атны_тюрлендириу', 'Атны_тюрлендир' ],
	'Mycontributions'           => [ 'Мени_къошумум' ],
	'MyLanguage'                => [ 'Мени_тилим' ],
	'Mypage'                    => [ 'Мени_бетим' ],
	'Mytalk'                    => [ 'Мени_сюзюуюм' ],
	'Myuploads'                 => [ 'Мени_джюклегенлерим' ],
	'Newimages'                 => [ 'Джангы_файлла' ],
	'Newpages'                  => [ 'Джангы_бетле' ],
	'PasswordReset'             => [ 'Паролну_ийиу' ],
	'PermanentLink'             => [ 'Дайым_джибериу' ],
	'Preferences'               => [ 'Джарашдырыула' ],
	'Protectedpages'            => [ 'Джакъланнган_бетле' ],
	'Protectedtitles'           => [ 'Джакъланнган_атла' ],
	'Randompage'                => [ 'Эсде_болмагъан_бет', 'Эсде_болмагъан' ],
	'Recentchanges'             => [ 'Ахыр_тюрлениуле' ],
	'Recentchangeslinked'       => [ 'Байламлы_тюрлениуле' ],
	'Revisiondelete'            => [ 'Кетерилген_тюрлениуле' ],
	'Search'                    => [ 'Излеу' ],
	'Shortpages'                => [ 'Къысха_бетле' ],
	'Specialpages'              => [ 'Энчи_бетле' ],
	'Statistics'                => [ 'Статистика' ],
	'Tags'                      => [ 'Белгиле' ],
	'Unblock'                   => [ 'Блокну_алыу' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#джибериу', '#редирект', '#перенаправление', '#перенапр', '#REDIRECT' ],
	'notoc'                     => [ '0', '__БАШЛАСЫЗ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '_ГАЛЛЕРЕЯСЫЗ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ],
];
