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

$dateFormats = array(
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
);

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$specialPageAliases = array(
	'Activeusers'               => array( 'Актив_кулланучылар' ),
	'Allmessages'               => array( 'Барлык_хатлар' ),
	'Allpages'                  => array( 'Барлык_битләр' ),
	'Ancientpages'              => array( 'Иске_битләр' ),
	'Booksources'               => array( 'Китап_чыганаклары' ),
	'BrokenRedirects'           => array( 'Өзелгән_күчеш' ),
	'Categories'                => array( 'Төркемнәр' ),
	'Confirmemail'              => array( 'Электрон_почтаны_раслау' ),
	'Contributions'             => array( 'Кертемнәр' ),
	'CreateAccount'             => array( 'Теркәлү' ),
	'DoubleRedirects'           => array( 'Икеле_күчеш' ),
	'Emailuser'                 => array( 'Кулланучының_E-mail\'лы' ),
	'Export'                    => array( 'Экспорт' ),
	'Fewestrevisions'           => array( 'Кечкенә_үзгәртүләр' ),
	'Import'                    => array( 'Импорт' ),
	'Listadmins'                => array( 'Идарәчеләр_исемлеге' ),
	'Listbots'                  => array( 'Ботлар_исемлеге' ),
	'Listfiles'                 => array( 'Файллар_исемлеге' ),
	'Listgrouprights'           => array( 'Төркемнәр_исемлеге' ),
	'Listusers'                 => array( 'Кулланучылар_исемлеге' ),
	'Log'                       => array( 'Көндәлек' ),
	'Longpages'                 => array( 'Озын_битләр' ),
	'Mostcategories'            => array( 'Зур_төркемнәр' ),
	'Mostrevisions'             => array( 'Зур_үзгәртүләр' ),
	'Movepage'                  => array( 'Битне_күчерү' ),
	'MyLanguage'                => array( 'Телем' ),
	'Mypage'                    => array( 'Сәхифәм' ),
	'Mytalk'                    => array( 'Бәхәсем' ),
	'Newimages'                 => array( 'Яңа_файл' ),
	'Newpages'                  => array( 'Яңа_бит' ),
	'Preferences'               => array( 'Көйләнмәләр' ),
	'Protectedpages'            => array( 'Якланган_битләр' ),
	'Protectedtitles'           => array( 'Якланган_башлыклар' ),
	'Randompage'                => array( 'Очраклы_мәкалә' ),
	'Recentchanges'             => array( 'Соңгы_үзгәртүләр' ),
	'Recentchangeslinked'       => array( 'Бәйләнгән_соңгы_үзгәртүләр' ),
	'Search'                    => array( 'Эзләү' ),
	'Shortpages'                => array( 'Кыска_битләр' ),
	'Specialpages'              => array( 'Махсус_битләр' ),
	'Statistics'                => array( 'Статистика' ),
	'Tags'                      => array( 'Теглар' ),
	'Uncategorizedcategories'   => array( 'Үзләштерелмәгән_бүлекләр' ),
	'Uncategorizedimages'       => array( 'Үзләштерелмәгән_файллар' ),
	'Uncategorizedpages'        => array( 'Үзләштерелмәгән_битләр' ),
	'Uncategorizedtemplates'    => array( 'Үзләштерелмәгән_үрнәкләр' ),
	'Unusedcategories'          => array( 'Кулланылмаган_төркемнәр' ),
	'Unusedimages'              => array( 'Кулланылмаучы_файллар' ),
	'Upload'                    => array( 'Йөкләү' ),
	'Userlogin'                 => array( 'Кулланучы_исеме' ),
	'Userlogout'                => array( 'Чыгу' ),
	'Version'                   => array( 'Юрама' ),
	'Wantedcategories'          => array( 'Мондый_бүлек_юк' ),
	'Wantedfiles'               => array( 'Мондый_файл_юк' ),
	'Wantedpages'               => array( 'Мондый_бит_юк' ),
	'Wantedtemplates'           => array( 'Мондый_үрнәк_юк' ),
	'Watchlist'                 => array( 'Күзәтү_исемлеге' ),
	'Whatlinkshere'             => array( 'Биткә_юнәлтүче_сылтамалар' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ЮНӘЛТҮ', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                     => array( '0', '__БАШЛЫКЮК__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'forcetoc'                  => array( '0', '__ETTIQ__', '__ОБЯЗ_ОГЛ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ЭЧТЕЛЕК__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'             => array( '0', '__БҮЛЕКҮЗГӘРТҮЮК__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'АГЫМДАГЫ_АЙ', 'АГЫМДАГЫ_АЙ2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ_GEN', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentday'                => array( '1', 'АГЫМДАГЫ_КӨН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'АГЫМДАГЫ_КӨН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'АГЫМДАГЫ_КӨН_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'АГЫМДАГЫ_ЕЛ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'АГЫМДАГЫ_ВАКЫТ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'numberofarticles'          => array( '1', 'МӘКАЛӘ_САНЫ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'pagename'                  => array( '1', 'БИТ_ИСЕМЕ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'namespace'                 => array( '1', 'ИСЕМНӘР_МӘЙДАНЫ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'msg'                       => array( '0', 'ХӘБӘР', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                     => array( '0', 'TÖPÇEK:', 'ПОДСТ:', 'ПОДСТАНОВКА:', 'SUBST:' ),
	'img_right'                 => array( '1', 'уңда', 'справа', 'right' ),
	'img_left'                  => array( '1', 'сулда', 'слева', 'left' ),
	'img_none'                  => array( '1', 'юк', 'без', 'none' ),
	'img_width'                 => array( '1', '$1пкс', '$1px' ),
	'img_center'                => array( '1', 'үзәк', 'центр', 'center', 'centre' ),
	'int'                       => array( '0', 'ЭЧКЕ:', 'ВНУТР:', 'INT:' ),
	'sitename'                  => array( '1', 'СӘХИФӘ_ИСЕМЕ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                        => array( '0', 'İA:', 'ПИ:', 'NS:' ),
	'localurl'                  => array( '0', 'URINLIURL:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'URINLIURLE:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'language'                  => array( '0', '#ТЕЛ:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'                   => array( '0', 'махсус', 'служебная', 'special' ),
	'tag'                       => array( '0', 'тамга', 'метка', 'тег', 'тэг', 'tag' ),
	'noindex'                   => array( '1', '__ИНДЕКССЫЗ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӘәӨөҮүҖҗҢңҺһ]+)(.*)$/sDu';

