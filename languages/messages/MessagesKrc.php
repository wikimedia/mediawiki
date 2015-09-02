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

$namespaceNames = array(
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
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Activeusers'               => array( 'Тири_къошулуучула' ),
	'Allmessages'               => array( 'Системаны_билдириулери' ),
	'Allpages'                  => array( 'Бютеу_бетле' ),
	'Blankpage'                 => array( 'Бош_бет' ),
	'Block'                     => array( 'Блок_эт' ),
	'Booksources'               => array( 'Китабланы_къайнакълары' ),
	'BrokenRedirects'           => array( 'Джыртылгъан_редиректле' ),
	'Categories'                => array( 'Категорияла' ),
	'ChangeEmail'               => array( 'E-mail’ни_ауушдур' ),
	'ChangePassword'            => array( 'Паролну_ауушдур' ),
	'ComparePages'              => array( 'Бетлени_тенглешдириу' ),
	'Confirmemail'              => array( 'E-mail’ни_тюзлюгюн_бегит' ),
	'Contributions'             => array( 'Къошум' ),
	'CreateAccount'             => array( 'Тергеу_джазыуну_къура', 'Къошулуучуну_къура', 'Регистрация_эт' ),
	'Deadendpages'              => array( 'Чыкъмазча_бетле' ),
	'DeletedContributions'      => array( 'Кетерилген_къошум' ),
	'DoubleRedirects'           => array( 'Экили_редирект' ),
	'EditWatchlist'             => array( 'Кёздеги_тизмени_тюрлендир' ),
	'Emailuser'                 => array( 'Къошулуучугъа_джазма', 'Джазма_ий' ),
	'Export'                    => array( 'Экспорт', 'Къотарыу' ),
	'FileDuplicateSearch'       => array( 'Файлланы_дубликатларын_излеу' ),
	'Filepath'                  => array( 'Файлгъа_джол' ),
	'Import'                    => array( 'Импорт' ),
	'BlockList'                 => array( 'Блок_этиулени_тизмеси', 'Блок_этиуле' ),
	'LinkSearch'                => array( 'Джибериуле_излеу' ),
	'Listadmins'                => array( 'Администраторланы_тизмеси' ),
	'Listbots'                  => array( 'Ботланы_тизмеси' ),
	'Listfiles'                 => array( 'Файлланы_тизмеси', 'Суратланы_тизмеси' ),
	'Listgrouprights'           => array( 'Къошулуучу_къауумланы_хакълары', 'Къауумланы_хакъларыны_тизмеси' ),
	'Listredirects'             => array( 'Редиректлени_тизмеси' ),
	'Listusers'                 => array( 'Къошулуучуланы_тизмеси' ),
	'Lockdb'                    => array( 'Билгиле_базаны_блок_эт' ),
	'Log'                       => array( 'Журналла', 'Журнал' ),
	'Lonelypages'               => array( 'Изоляция_этилген_бетле' ),
	'Longpages'                 => array( 'Узун_бетле' ),
	'MergeHistory'              => array( 'Тарихлени_бирикдириу' ),
	'MIMEsearch'                => array( 'MIME’ге_кёре_излеу' ),
	'Mostimages'                => array( 'Эм_кёб_хайырланнган_файлла' ),
	'Movepage'                  => array( 'Бетни_атын_тюрлендириу', 'Атны_тюрлендириу', 'Атны_тюрлендир' ),
	'Mycontributions'           => array( 'Мени_къошумум' ),
	'MyLanguage'                => array( 'Мени_тилим' ),
	'Mypage'                    => array( 'Мени_бетим' ),
	'Mytalk'                    => array( 'Мени_сюзюуюм' ),
	'Myuploads'                 => array( 'Мени_джюклегенлерим' ),
	'Newimages'                 => array( 'Джангы_файлла' ),
	'Newpages'                  => array( 'Джангы_бетле' ),
	'PasswordReset'             => array( 'Паролну_ийиу' ),
	'PermanentLink'             => array( 'Дайым_джибериу' ),
	'Preferences'               => array( 'Джарашдырыула' ),
	'Protectedpages'            => array( 'Джакъланнган_бетле' ),
	'Protectedtitles'           => array( 'Джакъланнган_атла' ),
	'Randompage'                => array( 'Эсде_болмагъан_бет', 'Эсде_болмагъан' ),
	'Recentchanges'             => array( 'Ахыр_тюрлениуле' ),
	'Recentchangeslinked'       => array( 'Байламлы_тюрлениуле' ),
	'Revisiondelete'            => array( 'Кетерилген_тюрлениуле' ),
	'Search'                    => array( 'Излеу' ),
	'Shortpages'                => array( 'Къысха_бетле' ),
	'Specialpages'              => array( 'Энчи_бетле' ),
	'Statistics'                => array( 'Статистика' ),
	'Tags'                      => array( 'Белгиле' ),
	'Unblock'                   => array( 'Блокну_алыу' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#джибериу', '#редирект', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БАШЛАСЫЗ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '_ГАЛЛЕРЕЯСЫЗ__', '__БЕЗ_ГАЛЕРЕИ__', '__NOGALLERY__' ),
);

