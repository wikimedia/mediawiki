<?php
/** Tatar (Cyrillic script) (татарча)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ajdar
 * @author Bulatbulat
 * @author Don Alessandro
 * @author Haqmar
 * @author Himiq Dzyu
 * @author Ilnur efende
 * @author KhayR
 * @author MF-Warburg
 * @author Marat Vildanov
 * @author Marat-avgust
 * @author Reedy
 * @author Rinatus
 * @author Timming
 * @author Yildiz
 * @author Zahidulla
 * @author Ерней
 * @author Ильнар
 * @author Рашат Якупов
 * @author Умар
 */

$fallback = 'ru';

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y, H:i',
	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

$namespaceNames = [
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Махсус',
	NS_TALK             => 'Бәхәс',
	NS_USER             => 'Кулланучы',
	NS_USER_TALK        => 'Кулланучы_бәхәсе',
	NS_PROJECT_TALK     => '$1_бәхәсе',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_бәхәсе',
	NS_MEDIAWIKI        => 'МедиаВики',
	NS_MEDIAWIKI_TALK   => 'МедиаВики_бәхәсе',
	NS_TEMPLATE         => 'Калып',
	NS_TEMPLATE_TALK    => 'Калып_бәхәсе',
	NS_HELP             => 'Ярдәм',
	NS_HELP_TALK        => 'Ярдәм_бәхәсе',
	NS_CATEGORY         => 'Төркем',
	NS_CATEGORY_TALK    => 'Төркем_бәхәсе',
];

$namespaceAliases = [
	'Служебная'                          => NS_SPECIAL,
	'Обсуждение'                         => NS_TALK,
	'Фикер_алышу'                        => NS_TALK,
	'Участница'                          => NS_USER,
	'Обсуждение_участницы'               => NS_USER_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Изображение'                        => NS_FILE,
	'Обсуждение_изображения'             => NS_FILE_TALK,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Рәсем'                              => NS_FILE,
	'Рәсем_бәхәсе'                       => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Медиа_Вики'                         => NS_MEDIAWIKI,
	'Медиа_Вики_бәхәсе'                  => NS_MEDIAWIKI_TALK,
	'Үрнәк'                              => NS_TEMPLATE,
	'Үрнәк_бәхәсе'                       => NS_TEMPLATE_TALK,
	'Шаблон'                             => NS_TEMPLATE,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Шаблон_бәхәсе'                      => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,

	// 'tt-latn' namespace names.
	'Maxsus'           => NS_SPECIAL,
	'Bäxäs'            => NS_TALK,
	'Äğzä'             => NS_USER,
	'Äğzä_bäxäse'      => NS_USER_TALK,
	'$1_bäxäse'        => NS_PROJECT_TALK,
	'Räsem'            => NS_FILE,
	'Räsem_bäxäse'     => NS_FILE_TALK,
	'MediaWiki_bäxäse' => NS_MEDIAWIKI_TALK,
	'Ürnäk'            => NS_TEMPLATE,
	'Ürnäk_bäxäse'     => NS_TEMPLATE_TALK,
	'Yärdäm'           => NS_HELP,
	'Yärdäm_bäxäse'    => NS_HELP_TALK,
	'Törkem'           => NS_CATEGORY,
	'Törkem_bäxäse'    => NS_CATEGORY_TALK,
];

// Remove Russian aliases
$namespaceGenderAliases = [];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'Актив_кулланучылар' ],
	'Allmessages'               => [ 'Барлык_хатлар' ],
	'Allpages'                  => [ 'Барлык_битләр' ],
	'Ancientpages'              => [ 'Иске_битләр' ],
	'Booksources'               => [ 'Китап_чыганаклары' ],
	'BrokenRedirects'           => [ 'Өзелгән_күчеш' ],
	'Categories'                => [ 'Төркемнәр' ],
	'Confirmemail'              => [ 'Электрон_почтаны_раслау' ],
	'Contributions'             => [ 'Кертемнәр' ],
	'CreateAccount'             => [ 'Теркәлү' ],
	'DoubleRedirects'           => [ 'Икеле_күчеш' ],
	'Emailuser'                 => [ 'Кулланучының_E-mail\'лы' ],
	'Export'                    => [ 'Экспорт' ],
	'Fewestrevisions'           => [ 'Кечкенә_үзгәртүләр' ],
	'Import'                    => [ 'Импорт' ],
	'Listadmins'                => [ 'Идарәчеләр_исемлеге' ],
	'Listbots'                  => [ 'Ботлар_исемлеге' ],
	'Listfiles'                 => [ 'Файллар_исемлеге' ],
	'Listgrouprights'           => [ 'Төркемнәр_исемлеге' ],
	'Listusers'                 => [ 'Кулланучылар_исемлеге' ],
	'Log'                       => [ 'Көндәлек' ],
	'Longpages'                 => [ 'Озын_битләр' ],
	'Mostcategories'            => [ 'Зур_төркемнәр' ],
	'Mostrevisions'             => [ 'Зур_үзгәртүләр' ],
	'Movepage'                  => [ 'Битне_күчерү' ],
	'MyLanguage'                => [ 'Телем' ],
	'Mypage'                    => [ 'Сәхифәм' ],
	'Mytalk'                    => [ 'Бәхәсем' ],
	'Newimages'                 => [ 'Яңа_файл' ],
	'Newpages'                  => [ 'Яңа_бит' ],
	'Preferences'               => [ 'Көйләнмәләр' ],
	'Protectedpages'            => [ 'Якланган_битләр' ],
	'Protectedtitles'           => [ 'Якланган_башлыклар' ],
	'Randompage'                => [ 'Очраклы_мәкалә' ],
	'Recentchanges'             => [ 'Соңгы_үзгәртүләр' ],
	'Recentchangeslinked'       => [ 'Бәйләнгән_соңгы_үзгәртүләр' ],
	'Search'                    => [ 'Эзләү' ],
	'Shortpages'                => [ 'Кыска_битләр' ],
	'Specialpages'              => [ 'Махсус_битләр' ],
	'Statistics'                => [ 'Статистика' ],
	'Tags'                      => [ 'Теглар' ],
	'Uncategorizedcategories'   => [ 'Үзләштерелмәгән_бүлекләр' ],
	'Uncategorizedimages'       => [ 'Үзләштерелмәгән_файллар' ],
	'Uncategorizedpages'        => [ 'Үзләштерелмәгән_битләр' ],
	'Uncategorizedtemplates'    => [ 'Үзләштерелмәгән_үрнәкләр' ],
	'Unusedcategories'          => [ 'Кулланылмаган_төркемнәр' ],
	'Unusedimages'              => [ 'Кулланылмаучы_файллар' ],
	'Upload'                    => [ 'Йөкләү' ],
	'Userlogin'                 => [ 'Кулланучы_исеме' ],
	'Userlogout'                => [ 'Чыгу' ],
	'Version'                   => [ 'Юрама' ],
	'Wantedcategories'          => [ 'Мондый_бүлек_юк' ],
	'Wantedfiles'               => [ 'Мондый_файл_юк' ],
	'Wantedpages'               => [ 'Мондый_бит_юк' ],
	'Wantedtemplates'           => [ 'Мондый_үрнәк_юк' ],
	'Watchlist'                 => [ 'Күзәтү_исемлеге' ],
	'Whatlinkshere'             => [ 'Биткә_юнәлтүче_сылтамалар' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'АГЫМДАГЫ_КӨН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'АГЫМДАГЫ_КӨН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'АГЫМДАГЫ_КӨН_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ],
	'currentmonth'              => [ '1', 'АГЫМДАГЫ_АЙ', 'АГЫМДАГЫ_АЙ2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ_GEN', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'АГЫМДАГЫ_ВАКЫТ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ],
	'currentyear'               => [ '1', 'АГЫМДАГЫ_ЕЛ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__ETTIQ__', '__ОБЯЗ_ОГЛ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__FORCETOC__' ],
	'img_center'                => [ '1', 'үзәк', 'центр', 'center', 'centre' ],
	'img_left'                  => [ '1', 'сулда', 'слева', 'left' ],
	'img_none'                  => [ '1', 'юк', 'без', 'none' ],
	'img_right'                 => [ '1', 'уңда', 'справа', 'right' ],
	'img_width'                 => [ '1', '$1пкс', '$1px' ],
	'int'                       => [ '0', 'ЭЧКЕ:', 'ВНУТР:', 'INT:' ],
	'language'                  => [ '0', '#ТЕЛ:', '#ЯЗЫК:', '#LANGUAGE:' ],
	'localurl'                  => [ '0', 'URINLIURL:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'URINLIURLE:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ],
	'msg'                       => [ '0', 'ХӘБӘР', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ],
	'namespace'                 => [ '1', 'ИСЕМНӘР_МӘЙДАНЫ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ],
	'noeditsection'             => [ '0', '__БҮЛЕКҮЗГӘРТҮЮК__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ],
	'noindex'                   => [ '1', '__ИНДЕКССЫЗ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ],
	'notoc'                     => [ '0', '__БАШЛЫКЮК__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ],
	'ns'                        => [ '0', 'İA:', 'ПИ:', 'NS:' ],
	'numberofarticles'          => [ '1', 'МӘКАЛӘ_САНЫ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ],
	'pagename'                  => [ '1', 'БИТ_ИСЕМЕ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ],
	'redirect'                  => [ '0', '#ЮНӘЛТҮ', '#перенаправление', '#перенапр', '#REDIRECT' ],
	'sitename'                  => [ '1', 'СӘХИФӘ_ИСЕМЕ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ],
	'special'                   => [ '0', 'махсус', 'служебная', 'special' ],
	'subst'                     => [ '0', 'TÖPÇEK:', 'ПОДСТ:', 'ПОДСТАНОВКА:', 'SUBST:' ],
	'tag'                       => [ '0', 'тамга', 'метка', 'тег', 'тэг', 'tag' ],
	'toc'                       => [ '0', '__ЭЧТЕЛЕК__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ],
];

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӘәӨөҮүҖҗҢңҺһ]+)(.*)$/sDu';
