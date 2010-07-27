<?php
/** Tatar (Cyrillic) (Татарча/Tatarça (Cyrillic))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Don Alessandro
 * @author Haqmar
 * @author Himiq Dzyu
 * @author KhayR
 * @author Rinatus
 * @author Timming
 * @author Yildiz
 * @author Ерней
 * @author Рашат Якупов
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
	NS_TEMPLATE         => 'Үрнәк',
	NS_TEMPLATE_TALK    => 'Үрнәк_бәхәсе',
	NS_HELP             => 'Ярдәм',
	NS_HELP_TALK        => 'Ярдәм_бәхәсе',
	NS_CATEGORY         => 'Төркем',
	NS_CATEGORY_TALK    => 'Төркем_бәхәсе',
);

$namespaceAliases = array(
	'Служебная'                          => NS_SPECIAL,
	'Обсуждение'                         => NS_TALK,
	'Фикер алышу'                        => NS_TALK,
	'Участница'                          => NS_USER,
	'Обсуждение участницы'               => NS_USER_TALK,
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
	'Шаблон'                             => NS_TEMPLATE,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
	'Шаблон_бәхәсе'                      => NS_TEMPLATE_TALK,
	'Справка'                            => NS_HELP,
	'Обсуждение_справки'                 => NS_HELP_TALK,
	'Категория'                          => NS_CATEGORY,
	'Обсуждение_категории'               => NS_CATEGORY_TALK,

	// tt-latn namespace names
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

$magicWords = array(
	'redirect'              => array( '0', '#yünältü', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ETYUQ__', '__БЕЗ_ОГЛ__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ETTIQ__', '__ОБЯЗ_ОГЛ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ET__', '__ОГЛ__', '__ОГЛАВЛЕНИЕ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BÜLEMTÖZÄTÜYUQ__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'AĞIMDAĞI_AY', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'AĞIMDAĞI_AY_İSEME', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'AĞIMDAĞI_AY_İSEME_GEN', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'AĞIMDAĞI_KÖN', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'AĞIMDAĞI_KÖN_İSEME', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'AĞIMDAĞI_YIL', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AĞIMDAĞI_WAQIT', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'MÄQÄLÄ_SANI', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'BİTİSEME', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'namespace'             => array( '1', 'İSEMARA', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'subst'                 => array( '0', 'TÖPÇEK:', 'ПОДСТ:', 'ПОДСТАНОВКА:', 'SUBST:' ),
	'img_right'             => array( '1', 'uñda', 'справа', 'right' ),
	'img_left'              => array( '1', 'sulda', 'слева', 'left' ),
	'img_none'              => array( '1', 'yuq', 'без', 'none' ),
	'int'                   => array( '0', 'EÇKE:', 'ВНУТР:', 'INT:' ),
	'sitename'              => array( '1', 'SÄXİFÄİSEME', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                    => array( '0', 'İA:', 'ПИ:', 'NS:' ),
	'localurl'              => array( '0', 'URINLIURL:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URINLIURLE:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Сылтамаларның астына сызу:',
'tog-highlightbroken'         => 'Төзелмәгән сылтамаларны <a href="" class="new">шушылай</a> (юкса болай - <a href="" class="internal">?</a>) күрсәтергә.',
'tog-justify'                 => 'Текст киңлек буенча тигезләнсен',
'tog-hideminor'               => 'Соңгы үзгәртүләр исемлегендә кече үзгәртүләр яшерелсен',
'tog-hidepatrolled'           => 'Тикшерелгән үзгәртүләр яңа үзгәртүләр исемлегеннән яшерелсен.',
'tog-newpageshidepatrolled'   => 'Тикшерелгән битләр яңа битләр исемлегеннән яшерелсен',
'tog-extendwatchlist'         => 'Соңгыларын гына түгел, ә барлык үзгәртүләрне эченә алган, киңәйтелгән күзәтү исемлеге',
'tog-usenewrc'                => 'Яхшыртылган соңгы үзгәртүләр исемлеге кулланылсын (JavaScript кирәк)',
'tog-numberheadings'          => 'Атамалар автомат рәвештә номерлансын',
'tog-showtoolbar'             => 'Үзгәртү вакытында коралларның өске панеле күрсәтелсен (JavaScript кирәк)',
'tog-editondblclick'          => 'Битләргә ике чирттерү белән үзгәртү бите ачылсын (JavaScript кирәк)',
'tog-editsection'             => 'Һәр бүлектә «үзгәртү» сылтамасы күрсәтелсен',
'tog-editsectiononrightclick' => 'Бүлек исеменә тычканның уң чирттермәсе белән төрткәч үзгәртү бите ачылсын (JavaScript кирәк)',
'tog-showtoc'                 => 'Эчтәлек күрсәтелсен (3 тән күбрәк башламлы битләрдә)',
'tog-rememberpassword'        => 'Хисап язмам бу компьютерда хәтерләнсен',
'tog-editwidth'               => 'Үзгәртү урыны күзәтүче тәрәзәсенең тулы киңлегендә',
'tog-watchcreations'          => 'Төзегән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchdefault'            => 'Үзгәрткән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchmoves'              => 'Күчергән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchdeletion'           => 'Бетерелгән битләремне күзәтү исемлегемгә өстәү',
'tog-minordefault'            => 'Барлык үзгәртүләрне килешү буенча кече дип билгеләнсен',
'tog-previewontop'            => 'Үзгәртү тәрәзәсеннән өстәрәк битне алдан карау өлкәсен күрсәтелсен',
'tog-previewonfirst'          => 'Үзгәртү битенә күчкәндә башта алдан карау бите күрсәтелсен',
'tog-nocache'                 => 'Битләр кэшлауны тыярга',
'tog-enotifwatchlistpages'    => 'Күзәтү исемлегемдәге бит үзгәртелү турында электрон почтага хәбәр җибәрелсен',
'tog-enotifusertalkpages'     => 'Бәхәс битем үзгәртелү турында электрон почтага хәбәр җибәрелсен',
'tog-enotifminoredits'        => 'Кече үзгәртүләр турында да электрон почтага хәбәр җибәрелсен',
'tog-enotifrevealaddr'        => 'Хәбәрләрдә e-mail адресым күрсәтелсен',
'tog-shownumberswatching'     => 'Битне күзәтү исемлекләренә өстәгән кулланучылар санын күрсәтелсен',
'tog-oldsig'                  => 'Хәзерге имзаны алдан карау:',
'tog-fancysig'                => 'Имзаның шәхси вики-билгеләмәсе (автоматик сылтамасыз)',
'tog-externaleditor'          => 'Тышкы редактор кулланырга (компьютер махсус көйләнгән булу зарур)',
'tog-externaldiff'            => 'Тышкы версия чагыштыру программасын кулланырга (компьютер махсус көйләнгән булу зарур)',
'tog-showjumplinks'           => '«Күчү» ярдәмче сылтамалары ялгансын',
'tog-uselivepreview'          => 'Тиз карап алу кулланылсын (JavaScript, эксперименталь)',
'tog-forceeditsummary'        => 'Үзгәртүләрне тасвирлау юлы тутырылмаган булса, кисәтергә',
'tog-watchlisthideown'        => 'Минем үзгәртүләрем күзәтү исемлегеннән яшерелсен',
'tog-watchlisthidebots'       => 'Бот үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideminor'      => 'Кече үзгәртүләр күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideliu'        => 'Авторизацияне узган кулланучыларның үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideanons'      => 'Аноним кулланучыларның үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthidepatrolled'  => 'Тикшерелгән үзгәртүләр күзәтү исемлегеннән яшерелсен',
'tog-nolangconversion'        => 'Язу системаларының үзгәртүен сүндерү',
'tog-ccmeonemails'            => 'Башка кулланучыларга җибәргән хатларымның копияләре миңа да җибәрелсен',
'tog-diffonly'                => 'Версия чагыштыру астында бит эчтәлеге күрсәтелмәсен',
'tog-showhiddencats'          => 'Яшерен төркемнәр күрсәтелсен',
'tog-norollbackdiff'          => 'Кире кайтару ясагач версияләр аермасы күрсәтелмәсен',

'underline-always'  => 'Һәрвакыт',
'underline-never'   => 'Бервакытта да',
'underline-default' => 'Браузер көйләнмәләре кулланылсын',

# Font style option in Special:Preferences
'editfont-style'     => 'Үзгәртү өлкәсендәге шрифт тибы:',
'editfont-default'   => 'Браузер көйләнмәләреннән булсын',
'editfont-monospace' => 'Киңәйтелгән шрифт',
'editfont-sansserif' => 'Киртексез шрифт',
'editfont-serif'     => 'Киртекле шрифт',

# Dates
'sunday'        => 'Якшәмбе',
'monday'        => 'Дүшәмбе',
'tuesday'       => 'Сишәмбе',
'wednesday'     => 'Чәршәмбе',
'thursday'      => 'Пәнҗешәмбе',
'friday'        => 'Җомга',
'saturday'      => 'Шимбә',
'sun'           => 'Якш',
'mon'           => 'Дүш',
'tue'           => 'Сиш',
'wed'           => 'Чәр',
'thu'           => 'Пән',
'fri'           => 'Җом',
'sat'           => 'Шим',
'january'       => 'гыйнвар',
'february'      => 'февраль',
'march'         => 'март',
'april'         => 'апрель',
'may_long'      => 'май',
'june'          => 'июнь',
'july'          => 'июль',
'august'        => 'август',
'september'     => 'сентябрь',
'october'       => 'октябрь',
'november'      => 'ноябрь',
'december'      => 'декабрь',
'january-gen'   => 'гыйнвар',
'february-gen'  => 'февраль',
'march-gen'     => 'март',
'april-gen'     => 'апрель',
'may-gen'       => 'май',
'june-gen'      => 'июнь',
'july-gen'      => 'июль',
'august-gen'    => 'август',
'september-gen' => 'сентябрь',
'october-gen'   => 'октябрь',
'november-gen'  => 'ноябрь',
'december-gen'  => 'декабрь',
'jan'           => 'гый',
'feb'           => 'фев',
'mar'           => 'мар',
'apr'           => 'апр',
'may'           => 'май',
'jun'           => 'июн',
'jul'           => 'июл',
'aug'           => 'авг',
'sep'           => 'сен',
'oct'           => 'окт',
'nov'           => 'ноя',
'dec'           => 'дек',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Төркем|Төркемнәр}}',
'category_header'                => '«$1» төркемендәге битләр',
'subcategories'                  => 'Төркемчәләр',
'category-media-header'          => '«$1» төркемендәге файллар',
'category-empty'                 => "''Бу төркем әлегә буш.''",
'hidden-categories'              => '{{PLURAL:$1|Яшерен төркем|Яшерен төркемнәр}}',
'hidden-category-category'       => 'Яшерен төркемнәр',
'category-subcat-count'          => '{{PLURAL:$2|Бу төркемдә түбәндәге төркемчә генә бар.|$2 төркемчәдән {{PLURAL:$1|$1 төркемчә күрсәтелгән}}.}}',
'category-subcat-count-limited'  => 'Бу төркемдә {{PLURAL:$1|$1 төркемчә}} бар.',
'category-article-count'         => '{{PLURAL:$2|Бу төркемдә бер генә бит бар.|Төркемдәге $2 битнең {{PLURAL:$1|$1 бите күрсәтелгән}}.}}',
'category-article-count-limited' => 'Бу төркемдә {{PLURAL:$1|$1 бит}} бар.',
'category-file-count'            => '{{PLURAL:$2|Бу төркемдә бер генә файл бар.|Төркемдәге $2 файлның {{PLURAL:$1|$1 файлы күрсәтелгән}}.}}',
'category-file-count-limited'    => 'Бу төркемдә {{PLURAL:$1|$1 файл}} бар.',
'listingcontinuesabbrev'         => 'дәвамы',
'index-category'                 => 'Индексланган битләр',
'noindex-category'               => 'Индексланмаган битләр',

'mainpagetext'      => '«MediaWiki» уңышлы куелды.',
'mainpagedocfooter' => "Бу вики турында мәгълүматны [http://meta.wikimedia.org/wiki/Ярдәм:Эчтәлек биредә] табып була.

== Кайбер файдалы ресурслар ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Көйләнмәләр исемлеге (инг.)];
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki турында еш бирелгән сораулар һәм җаваплар (инг.)];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki'ның яңа версияләре турында хәбәрләр яздырып алу].",

'about'         => 'Тасвирлама',
'article'       => 'Эчтәлек бите',
'newwindow'     => '(яңа тәрәзәдә ачыла)',
'cancel'        => 'Баш тарту',
'moredotdotdot' => 'Дәвамы…',
'mypage'        => 'Шәхси битем',
'mytalk'        => 'Бәхәсем',
'anontalk'      => 'Бу IP-адрес өчен бәхәс бите',
'navigation'    => 'Күчү',
'and'           => '&#32;һәм',

# Cologne Blue skin
'qbfind'         => 'Эзләү',
'qbbrowse'       => 'Карау',
'qbedit'         => 'Үзгәртү',
'qbpageoptions'  => 'Бу бит',
'qbpageinfo'     => 'Бит турында мәгълүматлар',
'qbmyoptions'    => 'Битләрем',
'qbspecialpages' => 'Махсус битләр',
'faq'            => 'ЕБС',
'faqpage'        => 'Project:ЕБС',

# Vector skin
'vector-action-addsection'   => 'Яңа тема өстәү',
'vector-action-delete'       => 'Бетерү',
'vector-action-move'         => 'Күчерү',
'vector-action-protect'      => 'Яклау',
'vector-action-undelete'     => 'Кайтару',
'vector-action-unprotect'    => 'Яклауны бетерү',
'vector-namespace-category'  => 'Төркем',
'vector-namespace-help'      => 'Ярдәм бите',
'vector-namespace-image'     => 'Файл',
'vector-namespace-main'      => 'Бит',
'vector-namespace-media'     => 'Медиа-бит',
'vector-namespace-mediawiki' => 'Хәбәр',
'vector-namespace-project'   => 'Проект бите',
'vector-namespace-special'   => 'Махсус бит',
'vector-namespace-talk'      => 'Бәхәс',
'vector-namespace-template'  => 'Үрнәк',
'vector-namespace-user'      => 'Кулланучы бите',
'vector-view-create'         => 'Төзү',
'vector-view-edit'           => 'Үзгәртү',
'vector-view-history'        => 'Тарихын карау',
'vector-view-view'           => 'Уку',
'vector-view-viewsource'     => 'Чыганакны карарга',
'actions'                    => 'Хәрәкәт',
'namespaces'                 => 'Исемнәр мәйданы',
'variants'                   => 'Төрләр',

'errorpagetitle'    => 'Хата',
'returnto'          => '$1 битенә кайту.',
'tagline'           => '{{SITENAME}} проектыннан',
'help'              => 'Ярдәм',
'search'            => 'Эзләү',
'searchbutton'      => 'Эзләү',
'go'                => 'Күчү',
'searcharticle'     => 'Күчү',
'history'           => 'Битнең тарихы',
'history_short'     => 'Тарих',
'updatedmarker'     => 'соңгы керүемнән соң яңартылган',
'info_short'        => 'Мәгълүмат',
'printableversion'  => 'Бастыру версиясе',
'permalink'         => 'Даими сылтама',
'print'             => 'Бастыру',
'edit'              => 'Үзгәртү',
'create'            => 'Төзү',
'editthispage'      => 'Бу битне үзгәртү',
'create-this-page'  => 'Бу битне төзү',
'delete'            => 'Бетерү',
'deletethispage'    => 'Бу битне бетерү',
'undelete_short'    => '$1 {{PLURAL:$1|үзгәртмәне}} торгызырга',
'protect'           => 'Яклау',
'protect_change'    => 'үзгәртү',
'protectthispage'   => 'Бу битне яклау',
'unprotect'         => 'Яклауны бетерү',
'unprotectthispage' => 'Бу битнең яклауын бетерү',
'newpage'           => 'Яңа бит',
'talkpage'          => 'Бит турында фикер алышу',
'talkpagelinktext'  => 'бәхәс',
'specialpage'       => 'Махсус бит',
'personaltools'     => 'Шәхси кораллар',
'postcomment'       => 'Яңа бүлек',
'articlepage'       => 'Мәкаләне карау',
'talk'              => 'Бәхәс',
'views'             => 'Караулар',
'toolbox'           => 'Кораллар',
'userpage'          => 'Кулланучы битен карау',
'projectpage'       => 'Проект битен карау',
'imagepage'         => 'Файл битен карау',
'mediawikipage'     => 'Хәбәр битен карау',
'templatepage'      => 'Үрнәк битен карау',
'viewhelppage'      => 'Ярдәм битен карау',
'categorypage'      => 'Төркем битен карау',
'viewtalkpage'      => 'Бәхәс битен карау',
'otherlanguages'    => 'Башка телләрдә',
'redirectedfrom'    => '($1 битеннән юнәлтелде)',
'redirectpagesub'   => 'Башка биткә юнәлтү бите',
'lastmodifiedat'    => 'Бу битне соңгы үзгәртү: $2, $1.',
'viewcount'         => 'Бу биткә $1 {{PLURAL:$1|тапкыр}} мөрәҗәгать иттеләр.',
'protectedpage'     => 'Якланган бит',
'jumpto'            => 'Күчәргә:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'эзләү',
'view-pool-error'   => 'Гафу итегез, хәзерге вакытта серверлар буш түгел.
Бу битне карарга теләүчеләр артык күп.
Бу биткә соңарак керүегез сорала.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} турында',
'aboutpage'            => 'Project:Тасвирлама',
'copyright'            => 'Мәгълүмат $1 буенча таратыла.',
'copyrightpage'        => '{{ns:project}}:Авторлык хокуклары',
'currentevents'        => 'Хәзерге вакыйгалар',
'currentevents-url'    => 'Project:Хәзерге вакыйгалар',
'disclaimers'          => 'Җаваплылыктан баш тарту',
'disclaimerpage'       => 'Project:Җаваплылыктан баш тарту',
'edithelp'             => 'Үзгәртү буенча ярдәм',
'edithelppage'         => 'Help:Үзгәртү',
'helppage'             => 'Help:Эчтәлек',
'mainpage'             => 'Баш бит',
'mainpage-description' => 'Баш бит',
'policy-url'           => 'Project:Кагыйдәләр',
'portal'               => 'Җәмгыять үзәге',
'portal-url'           => 'Project:Җәмгыять үзәге',
'privacy'              => 'Яшеренлек сәясәте',
'privacypage'          => 'Project:Яшеренлек сәясәте',

'badaccess'        => 'Керү хатасы',
'badaccess-group0' => 'Сез сораган гамәлне башкара алмыйсыз.',
'badaccess-groups' => 'Соралган гамәлне $1 {{PLURAL:$2|төркеменең|төркемнәренең}} кулланучылары гына башкара ала.',

'versionrequired'     => 'MediaWikiның $1 версиясе таләп ителә',
'versionrequiredtext' => 'Бу бит белән эшләү өчен MediaWikiның $1 версиясе кирәк. [[Special:Version|Кулланылучы программа версиясе турында мәгълүмат битен]] кара.',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'retrievedfrom'           => 'Чыганагы — "$1"',
'youhavenewmessages'      => 'Сездә $1 бар ($2).',
'newmessageslink'         => 'яңа хәбәрләр',
'newmessagesdifflink'     => 'бәхәс битегезнең соңгы үзгәртүе',
'youhavenewmessagesmulti' => 'Сезгә монда яңа хәбәрләр бар: $1',
'editsection'             => 'үзгәртү',
'editold'                 => 'үзгәртү',
'viewsourceold'           => 'башлангыч кодны карарга',
'editlink'                => 'үзгәртү',
'viewsourcelink'          => 'башлангыч кодны карау',
'editsectionhint'         => '$1 бүлеген үзгәртү',
'toc'                     => 'Эчтәлек',
'showtoc'                 => 'күрсәтү',
'hidetoc'                 => 'яшерү',
'thisisdeleted'           => '$1 карарга яки торгызырга телисезме?',
'viewdeleted'             => '$1 карарга телисезме?',
'restorelink'             => '{{PLURAL:$1|1 бетерелгән үзгәртүне|$1 бетерелгән үзгәртүне}}',
'feedlinks'               => 'Шушылай:',
'feed-invalid'            => 'Язылу каналы тибы ялгыш',
'feed-unavailable'        => 'Синдикация тасмасы ябык',
'site-rss-feed'           => '$1 — RSS тасмасы',
'site-atom-feed'          => '$1 — Atom тасмасы',
'page-rss-feed'           => '«$1» — RSS тасмасы',
'page-atom-feed'          => '«$1» — Atom тасмасы',
'red-link-title'          => '$1 (мондый бит юк)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Бит',
'nstab-user'      => 'Кулланучы бите',
'nstab-media'     => 'Мультимедиа',
'nstab-special'   => 'Махсус бит',
'nstab-project'   => 'Проект турында',
'nstab-image'     => 'Файл',
'nstab-mediawiki' => 'Хәбәр',
'nstab-template'  => 'Үрнәк',
'nstab-help'      => 'Ярдәм',
'nstab-category'  => 'Төркем',

# Main script and global functions
'nosuchaction'      => 'Мондый гамәл юк',
'nosuchactiontext'  => 'URLда күрсәтелгән гамәл хаталы.
Сез URLны хаталы җыйган яисә хаталы сылтамадан күчкән булырга мөмкинсез.
Бу шулай ук {{SITENAME}} проектындагы хата сәбәпле дә булырга мөмкин.',
'nosuchspecialpage' => 'Мондый махсус бит юк',
'nospecialpagetext' => '<strong>Сез сорый торган махсус бит юк.</strong>

Махсус битләр исемлеген карагыз: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Хата',
'databaseerror'        => 'Мәгълүматлар базасында хата',
'dberrortext'          => 'Мәгълүматлар базасына җибәрелгән сорауда синтаксик хата табылды.
Программада хата булырга мөмкин.
Мәгълүматлар базасына җибәрелгән соңгы сорау:
<blockquote><tt>$1</tt></blockquote>
<tt>«$2»</tt> функциясеннән.
База <tt>«$3: $4»</tt> хатасын кайтарды.',
'dberrortextcl'        => 'Мәгълүматлар базасына җибәрелгән сорауда синтаксик хата табылды.
Мәгълүматлар базасына җибәрелгән соңгы сорау:
"$1"
«$2» функциясеннән.
База «$3: $4» хатасын кайтарды.',
'laggedslavemode'      => 'Игътибар: биттә соңгы яңартулар күрсәтелмәгән булырга мөмкин.',
'readonly'             => 'Мәгълүматлар базасына язу ябылган',
'enterlockreason'      => 'Ябылу сәбәбен һәм вакытын күрсәтегез.',
'readonlytext'         => 'Мәгълүмат базасы хәзерге вакытта яңа битләр ясаудан һәм башка үзгәртүләрдән ябылган. Бу планлаштырылган хезмәт күрсәтү сәбәпле булырга мөмкин.
Ябучы оператор түбәндәге аңлатманы калдырган:
$1',
'missing-article'      => 'Мәгълүматлар базасында «$1» $2 битенең соралган тексты табылмады.

Бу, гадәттә, искергән сылтама буенча бетерелгән битнең үзгәртү тарихына күчкәндә килеп чыга.

Әгәр хата монда түгел икән, сез программада хата тапкан булырга мөмкинсез.
Зинһар өчен, URLны күрсәтеп, бу турыда [[Special:ListUsers/sysop|администраторга]] хәбәр итегез.',
'missingarticle-rev'   => '(версия № $1)',
'missingarticle-diff'  => '(аерма: $1, $2)',
'readonly_lag'         => 'Мәгълүматлар базасы, өстәмә сервер төп сервер белән синхронизацияләшкәнче, үзгәрүләрдән автомат рәвештә ябылды.',
'internalerror'        => 'Эчке хата',
'internalerror_info'   => 'Эчке хата: $1',
'fileappenderrorread'  => 'Кушу вакытында «$1» укып булмады.',
'fileappenderror'      => '"$1" һәм "$2" не кушып булмады.',
'filecopyerror'        => '«$2» файлына «$1» файлының копиясен ясап булмый.',
'filerenameerror'      => '«$1» файлының исемен «$2» исеменә алыштырып булмый.',
'filedeleteerror'      => '«$1» файлын сыздырып булмый.',
'directorycreateerror' => '«$1» директориясен ясап булмый.',
'filenotfound'         => '«$1» файлын табып булмый.',
'fileexistserror'      => '«$1» файлына яздырып булмый: ул инде бар.',
'unexpected'           => 'Көтелмәгән кыйммәт: «$1»=«$2».',
'formerror'            => 'Хата: форма мәгълүматларын тапшырып булмый',
'badarticleerror'      => 'Бу биттә мондый гамәл башкарып булмый.',
'cannotdelete'         => '«$1» исемле битне яки файлны сыздырып булмый. Аны бүтән кулланучы сыздырган булырга мөмкин.',
'badtitle'             => 'Яраксыз исем',
'badtitletext'         => 'Битнең соралган исеме дөрес түгел, буш яисә телъара яки интервики исеме дөрес күрсәтелмәгән. Исемдә тыелган символлар кулланылган булырга мөмкин.',
'perfcached'           => 'Бу мәгълүматлар кэштан алынган, аларда соңгы үзгәртүләр булмаска мөмкин.',
'perfcachedts'         => 'Бу мәгълүматлар кэштан алынган, ул соңгы тапкыр $1 яңартылды.',
'querypage-no-updates' => 'Хәзер бу битне яңартып булмый. Монда күрсәтелгән мәгълүматлар кабул ителмәячәк.',
'wrong_wfQuery_params' => 'wfQuery() функция өчен ярамаган параметрлар<br />
Функция: $1<br />
Сорау: $2',
'viewsource'           => 'Карау',
'viewsourcefor'        => '«$1» бите',
'actionthrottled'      => 'Тизлек киметелгән',
'actionthrottledtext'  => 'Спамга каршы көрәш өчен аз вакыт эчендә бу гамәлне еш куллану тыелган. Зинһар, соңарак кабатлагыз.',
'protectedpagetext'    => 'Бу бит үзгәртү өчен ябык.',
'viewsourcetext'       => 'Сез бу битнең башлангыч текстын карый һәм күчерә аласыз:',
'protectedinterface'   => 'Бу биттә программа интерфейсы хәбәрләре бар. Вандализмга каршы көрәш сәбәпле, бу битне үзгәртү тыела.',
'editinginterface'     => "'''Игътибар:''' Сез MediaWiki системасының интерфейс битен үзгәртәсез. Бу башка кулланучыларга да тәэсир итәчәк. Тәрҗемә өчен [http://translatewiki.net/wiki/Main_Page?setlang=tt-cyrl translatewiki.net] локализацияләү проектын кулланыгыз.",
'sqlhidden'            => '(SQL-сорау яшерелгән)',
'cascadeprotected'     => 'Бу бит үзгәртүләрдән сакланган, чөнки ул каскадлы саклау кабул ителгән {{PLURAL:$1|биткә|битләргә}} өстәлгән:
$2',
'namespaceprotected'   => "'''$1''' исем киңлегендәге битләрне үзгәртү өчен сезнең рөхсәтегез юк.",
'customcssjsprotected' => 'Сез бу битне үзгәртә алмыйсыз, чөнки анда башка кулланычының көйләнмәләре бар.',
'ns-specialprotected'  => 'Махсус битләрне үзгәртеп булмый.',
'titleprotected'       => "Бу исем белән бит ясау [[User:$1|$1]] тарафыннан тыелган.
Ул күрсәткән сәбәп: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Көйләү хатасы. Билгесез вируслар сканеры: ''$1''",
'virus-scanfailed'     => 'сканерлау хатасы ($1 коды)',
'virus-unknownscanner' => 'билгесез антивирус:',

# Login and logout pages
'logouttext'                 => "'''Сез хисап язмагыздан чыктыгыз.'''

Сез {{SITENAME}} проектында аноним рәвештә кала яисә шул ук яки башка исем белән яңадан [[Special:UserLogin|керә]] аласыз.
Кайбер битләр Сез кергән кебек күрсәтелергә мөмкин. Моны бетерү өчен браузер кэшын чистартыгыз.",
'welcomecreation'            => '== Рәхим итегез, $1! ==
Сез теркәлдегез.
Сайтның шәхси [[Special:Preferences|көйләнмәләрен]] карарга онытмагыз.',
'yourname'                   => 'Кулланучы исеме:',
'yourpassword'               => 'Серсүз:',
'yourpasswordagain'          => 'Серсүзне кабат кертү:',
'remembermypassword'         => 'Кулланучы исемемне бу компьютерда онытмаска',
'yourdomainname'             => 'Сезнең доменыгыз:',
'externaldberror'            => 'Тышкы мәгълүмат базасы ярдәмендә аутентификация үткәндә хата чыкты, яисә тышкы хисап язмагызга үзгәрешләр кертү хокукыгыз юк.',
'login'                      => 'Керү',
'nav-login-createaccount'    => 'Керү / теркәлү',
'loginprompt'                => '{{SITENAME}} проектына керү өчен «cookies» рөхсәт ителгән булырга тиеш.',
'userlogin'                  => 'Керү / теркәлү',
'userloginnocreate'          => 'Керү',
'logout'                     => 'Чыгу',
'userlogout'                 => 'Чыгу',
'notloggedin'                => 'Сез хисап язмагызга кермәгәнсез',
'nologin'                    => "Кулланучы исемең юкмы? '''$1'''",
'nologinlink'                => 'Хисап язмасы төзегез',
'createaccount'              => 'Яңа кулланучы теркәргә',
'gotaccount'                 => "Сез инде теркәлдегезме? '''$1'''.",
'gotaccountlink'             => 'Керү',
'createaccountmail'          => 'электрон почта белән',
'badretype'                  => 'Кертелгән серсүзләр бер үк түгел.',
'userexists'                 => 'Кертелгән исем кулланыла.
Зинһар, башка исем сайлагыз.',
'loginerror'                 => 'Керү хатасы',
'createaccounterror'         => 'Хисап язмасын төзеп булмый: $1',
'nocookiesnew'               => 'Катнашучы теркәлгән, ләкин үз хисап язмасы белән кермәгән. {{SITENAME}} катнашучыны тану өчен «cookies» куллана. Сездә «cookies» тыелган. Зинһар, башта аларны рөхсәт итегез, аннан исем һәм серсүз белән керегез.',
'nocookieslogin'             => '{{SITENAME}} катнашучыны тану өчен «cookies» куллана. Сез аларны сүндергәнсез. Зинһар, аларны кабызып, яңадан керегез.',
'noname'                     => 'Сез кулланучы исемегезне күрсәтергә тиешсез.',
'loginsuccesstitle'          => 'Керү уңышлы үтте',
'loginsuccess'               => "'''Сез {{SITENAME}} проектына $1 исеме белән кердегез.'''",
'nosuchuser'                 => '$1 исемле кулланучы юк.
Кулланучы исеменең дөреслеге регистрга бәйле.
Язылышыгызны тикшерегез яки [[Special:UserLogin/signup|яңа хисап язмасы төзегез]].',
'nosuchusershort'            => '<nowiki>$1</nowiki> исемле кулланучы юк. Язылышыгызны тикшерегез.',
'nouserspecified'            => 'Сез теркәү исмегезне күрсәтергә тиешсез.',
'login-userblocked'          => 'Бу катнашучы тыелды. Керү тыелган.',
'wrongpassword'              => 'Язылган серсүз дөрес түгел. Тагын бер тапкыр сынагыз.',
'wrongpasswordempty'         => 'Серсүз юлы буш булырга тиеш түгел.',
'passwordtooshort'           => 'Сезсүз $1 {{PLURAL:$1|символдан}} торырга тиеш.',
'password-name-match'        => 'Кертелгән серсүз кулланучы исеменнән аерылырга тиеш.',
'mailmypassword'             => 'Электрон почтага яңа серсүз җибәрү',
'passwordremindertitle'      => '{{SITENAME}} кулланучысына вакытлы серсүз тапшыру',
'passwordremindertext'       => 'Кемдер (бәлки, сездер, IP-адресы: $1) {{SITENAME}} ($4) өчен яңа серсүз соратты. $2 өчен яңа серсүз: $3. Әгәр бу сез булсагыз, системага керегез һәм серсүзне алмаштырыгыз. Яңа серсүз $5 {{PLURAL:$5|көн}} гамәлдә булачак.

Әгәр сез серсүзне алмаштыруны сорамаган булсагыз яки, оныткан очракта, исегезгә төшергән булсагыз, бу хәбәргә игътибар бирмичә, иске серсүзегезне куллануны дәвам итегез.',
'noemail'                    => '$1 исемле кулланучы өчен электрон почта адресы язылмаган.',
'noemailcreate'              => 'Сез дөрес e-mail адрес күрсәтергә тиеш',
'passwordsent'               => 'Яңа серсүз $1 исемле катнашучының электрон почта адресына җибәрелде.

Зинһар, серсүзне алгач, системага яңадан керегез.',
'blocked-mailpassword'       => 'Сезнең IP-адресыгыз белән битләр үзгәртеп һәм серсүзне яңартып булмый.',
'eauthentsent'               => 'Адрес үзгәртүне дәлилләү өчен аңа махсус хат җибәрелде. Хатта язылганнарны үтәвегез сорала.',
'throttled-mailpassword'     => 'Серсүзне электрон почтага җибәрү гамәлен сез {{PLURAL:$1|соңгы $1 сәгать}} эчендә кулландыгыз инде. Бу гамәлне явызларча куллануны кисәтү максатыннан аны $1 {{PLURAL:$1|сәгать}} аралыгында бер генә тапкыр башкарып була.',
'mailerror'                  => 'Хат җибәрү хатасы: $1',
'acct_creation_throttle_hit' => 'Сезнең IP-адрестан бу тәүлек эчендә {{PLURAL:$1|$1 хисап язмасы}} төзелде инде. Шунлыктан бу гамәл сезнең өчен вакытлыча ябык.',
'emailauthenticated'         => 'Электрон почта адресыгыз расланды: $3, $2.',
'emailnotauthenticated'      => 'Электрон почта адресыгыз әле дәлилләнмәгән, шуңа викиның электрон почта белән эшләү гамәлләре сүндерелде.',
'noemailprefs'               => 'Электрон почта адресыгыз күрсәтелмәгән, шуңа викиның электрон почта белән эшләү гамәлләре сүндерелгән.',
'emailconfirmlink'           => 'Электрон почта адресыгызны дәлилләгез.',
'invalidemailaddress'        => 'Элктрон почта адресы кабул ителә алмый, чөнки ул дөрес форматка туры килми. Зинһар, дөрес адрес кертегез яки юлны буш калдырыгыз.',
'accountcreated'             => 'Хисап язмасы төзелде',
'accountcreatedtext'         => '$1 исемле кулланучы өчен хисап язмасы төзелде.',
'createaccount-title'        => '{{SITENAME}}: теркәлү',
'createaccount-text'         => 'Кемдер, электрон почта адресыгызны күрсәтеп, {{SITENAME}} ($4) проектында «$3» серсүзе белән «$2» исемле хисап язмасы теркәде. Сез керергә һәм серсүзегезне үзгәртергә тиеш.

Хисап язмасы төзү хата булса, бу хатны онытыгыз.',
'usernamehasherror'          => 'Кулланучы исемендә "#" символы була алмый',
'login-throttled'            => 'Сез артык күп тапкыр керергә тырыштыгыз.
Яңадан кабатлаганчы бераз көтүегез сорала.',
'loginlanguagelabel'         => 'Тел: $1',

# Password reset dialog
'resetpass'                 => 'Серсүзне үзгәртү',
'resetpass_announce'        => 'Сез электрон почта аша вакытлыча бирелгән серсүз ярдәмендә кердегез. Системага керүне төгәлләү өчен яңа серсүз төзегез.',
'resetpass_text'            => '<!-- Монда текст өстәгез -->',
'resetpass_header'          => 'Хисап язмасы серсүзен үзгәртү',
'oldpassword'               => 'Иске серсүз:',
'newpassword'               => 'Яңа серсүз:',
'retypenew'                 => 'Яңа серсүзне кабатлагыз:',
'resetpass_submit'          => 'Серсүз куярга һәм керергә',
'resetpass_success'         => 'Сезнең серсүз уңышлы үзгәртелде! Системага керү башкарыла...',
'resetpass_forbidden'       => 'Серсүз үзгәртелә алмый',
'resetpass-no-info'         => 'Бу битне карау өчен сез системага үз хисап язмагыз ярдәмендә керергә тиеш.',
'resetpass-submit-loggedin' => 'Серсүзне үзгәртергә',
'resetpass-submit-cancel'   => 'Кире кагу',
'resetpass-wrong-oldpass'   => 'Ялгыш серсүз.
Сез серсүзегезне үзгәрткән яисә яңа вакытлы серсүз сораткан булырга мөмкинсез.',
'resetpass-temp-password'   => 'Вакытлы серсүз:',

# Edit page toolbar
'bold_sample'     => 'Калын язылыш',
'bold_tip'        => 'Калын язылыш',
'italic_sample'   => 'Курсив язылыш',
'italic_tip'      => 'Курсив язылыш',
'link_sample'     => 'Сылтама исеме',
'link_tip'        => 'Эчке сылтама',
'extlink_sample'  => 'http://www.example.com сылтама исеме',
'extlink_tip'     => 'Тышкы сылтама (http:// алкушымчасы турында онытмагыз)',
'headline_sample' => 'Башисем',
'headline_tip'    => '2 нче дәрәҗәле исем',
'math_sample'     => 'Формуланы монда өстәгез',
'math_tip'        => 'Математик формула (LaTeX форматы)',
'nowiki_sample'   => 'Форматланмаган текстны монда өстәгез',
'nowiki_tip'      => 'Вики-форматлауны исәпкә алмаска',
'image_tip'       => 'Куелган файл',
'media_tip'       => 'Медиа-файлга сылтама',
'sig_tip'         => 'Имза һәм вакыт',
'hr_tip'          => 'Горизонталь сызык (еш кулланмагыз)',

# Edit pages
'summary'                          => 'Үзгәртүләр тасвирламасы:',
'subject'                          => 'Тема/башисем:',
'minoredit'                        => 'Бу кече үзгәртү',
'watchthis'                        => 'Бу битне күзәтү',
'savearticle'                      => 'Битне саклау',
'preview'                          => 'Алдан карау',
'showpreview'                      => 'Алдан карау',
'showlivepreview'                  => 'Тиз алдан карау',
'showdiff'                         => 'Кертелгән үзгәртүләр',
'anoneditwarning'                  => "'''Игътибар''': Сез системага кермәгәнсез. IP-адресыгыз бу битнең тарихына язылачак.",
'missingsummary'                   => "'''Искәртү.''' Сез үзгәртүгә кыскача тасвирлау язмадыгыз. Сез «Битне сакларга» төймәсенә тагын бер тапкыр бассагыз, үзгәртүләр тасвирламасыз сакланачак.",
'missingcommenttext'               => 'Аска тасвирлама язуыгыз сорала.',
'missingcommentheader'             => "'''Искәртү:''' Сез тасвирламага исем бирмәдегез.
«Битне сакларга» төймәсенә кабат бассагыз, үзгәртүләр исемсез язылачак.",
'summary-preview'                  => 'Тасвирламаны алдан карау:',
'subject-preview'                  => 'Башисемне алдан карау:',
'blockedtitle'                     => 'Кулланучы бикләнгән',
'blockedtext'                      => "'''Сезнең хисап язмагыз яки IP-адресыгыз бикләнгән.'''

Бикләүче администратор: $1.
Күрсәтелгән сәбәп: ''$2''.

* Бикләү башланган вакыт: $8
* Бикләү ахыры: $6
* Бикләнүләр саны: $7

Сез $1 яки башка [[{{MediaWiki:Grouppage-sysop}}|администраторга]] бикләү буенча сорауларыгызны җибәрә аласыз.
Исегездә тотыгыз: әгәр сез теркәлмәгән һәм электрон почта адресыгызны дәлилләмәгән булсагыз ([[Special:Preferences|дәлилләү өчен шәхси көйләүләр монда]]), администраторга хат җибәрә алмыйсыз. Шулай ук бикләү вакытында сезнең хат җибәрү мөмкинлегегезне чикләгән булырга да мөмкиннәр.
Сезнең IP-адрес — $3, бикләү идентификаторы — #$5.
Хатларда бу мәгълүматны күрсәтергә онытмагыз.",
'autoblockedtext'                  => "Сезнең IP-адресыгыз, аның бикләнгән кулланучы тарафыннан кулланылуы сәбәпле, автомат рәвештә бикләнде.
Ул кулланучыны бикләүче администратор: $1. Күрсәтелгән сәбәп: 

:''$2''

* Бикләү башланган вакыт: $8
* Бикләү ахыры: $6
* Бикләнүләр саны: $7

Сез $1 яки башка [[{{MediaWiki:Grouppage-sysop}}|администраторга]] бикләү буенча сорауларыгызны җибәрә аласыз.
Исегездә тотыгыз: әгәр сез теркәлмәгән һәм электрон почта адресыгызны дәлилләмәгән булсагыз ([[Special:Preferences|дәлилләү өчен шәхси көйләүләр монда]]), администраторга хат җибәрә алмыйсыз. Шулай ук бикләү вакытында сезнең хат җибәрү мөмкинлегегезне чикләгән булырга да мөмкиннәр.
Сезнең IP-адрес — $3, бикләү идентификаторы — #$5.
Хатларда бу мәгълүматны күрсәтергә онытмагыз.",
'blockednoreason'                  => 'сәбәп күрсәтелмәгән',
'blockedoriginalsource'            => "Аста '''$1''' битенең тексты күрсәтелгән.",
'blockededitsource'                => "Аста '''$1''' битенең '''сез үзгәрткән''' тексты күрсәтелгән.",
'whitelistedittitle'               => 'Үзгәртү өчен үз исемегез белән керергә кирәк',
'whitelistedittext'                => 'Сез битләрне үзгәртү өчен $1 тиеш.',
'confirmedittext'                  => 'Битләрне үзгәртү алдыннан сез электрон почта адресыгызны дәлилләргә тиеш.
Сез моны [[Special:Preferences|көйләүләр битендә]] башкара аласыз.',
'nosuchsectiontitle'               => 'Мондый бүлекне табып булмый.',
'nosuchsectiontext'                => 'Сез булмаган бүлекне төзәтергә телисез.
Сез бу сәхифәне караганда ул бетерелә алды.',
'loginreqtitle'                    => 'Керү кирәк',
'loginreqlink'                     => 'керү',
'loginreqpagetext'                 => 'Сез башка битләр карау өчен $1 тиеш.',
'accmailtitle'                     => 'Серсүз җибәрелде.',
'accmailtext'                      => "[[User talk:$1|$1]] кулланучысы өчен төзелгән серсүз $2 адресына җибәрелде.

Сайтка кергәч сез ''[[Special:ChangePassword|серсүзегезне үзгәртә аласыз]]''.",
'newarticle'                       => '(Яңа)',
'newarticletext'                   => "Сез әлегә язылмаган биткә кердегез.
Яңа бит ясау өчен астагы тәрәзәдә мәкалә текстын җыегыз ([[{{MediaWiki:Helppage}}|ярдәм битен]] карый аласыз).
Әгәр сез бу биткә ялгышлык белән эләккән булсагыз, браузерыгызның '''артка''' төймәсенә басыгыз.",
'anontalkpagetext'                 => "----''Бу бәхәс бите системада теркәлмәгән яисә үз исеме белән кермәгән кулланучыныкы.
Аны тану өчен IP-адрес файдаланыла.
Әгәр сез аноним кулланучы һәм сезгә юлланмаган хәбәрләр алдым дип саныйсыз икән (бер IP-адрес күп кулланучы өчен булырга мөмкин), башка мондый аңлашылмаучанлыклар килеп чыкмасын өчен [[Special:UserLogin|системага керегез]] яисә [[Special:UserLogin/signup|теркәлегез]].''",
'noarticletext'                    => "Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]], 
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} көндәлекләрдәге язмаларны] таба 
яки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} шушындый исемле яңа бит төзи]''' аласыз.",
'noarticletext-nopermission'       => 'Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]], 
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} көндәлекләрдәге язмаларны] таба аласыз.</span>',
'userpage-userdoesnotexist'        => '«$1» исемле хисап язмасы юк. Сез чынлап та бу битне ясарга яисә үзгәртергә телисезме?',
'userpage-userdoesnotexist-view'   => '"$1" исемле хисап язмасы юк.',
'blocked-notice-logextract'        => 'Бу катнашучы хәзергә кысылды.
Түбәндә кысу көндәлекнең соңгы язу бирелгән:',
'clearyourcache'                   => "'''Искәрмә:''' Битне саклаганнан соң үзгәртүләр күренсен өчен браузерыгызның кэшын чистартыгыз.
Моны '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''Safari''': ''Cmd+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Konqueror''': ''F5'', '''Opera''': ''Tools→Preferences'' аша эшләп була.",
'usercssyoucanpreview'             => "'''Ярдәм:''' \"Алдан карау\" төймәсенә басып, яңа CSS-файлны тикшереп була.",
'userjsyoucanpreview'              => "'''Ярдәм:''' \"Алдан карау\" төймәсенә басып, яңа JS-файлны тикшереп була.",
'usercsspreview'                   => "'''Бу бары тик CSS-файлны алдан карау гына, ул әле сакланмаган!'''",
'userjspreview'                    => "'''Бу бары тик JavaScript файлын алдан карау гына, ул әле сакланмаган!'''",
'userinvalidcssjstitle'            => "'''Игътибар:''' \"\$1\" бизәү темасы табылмады. Кулланучының .css һәм .js битләре исемнәре бары тик кечкенә (юл) хәрефләрдән генә торырга тиеш икәнен онытмагыз. Мисалга: {{ns:user}}:Foo/monobook.css, ә {{ns:user}}:Foo/Monobook.css түгел!",
'updated'                          => '(Яңартылды)',
'note'                             => "'''Искәрмә:'''",
'previewnote'                      => "'''Бу фәкать алдан карау гына, үзгәртүләрегез әле сакланмаган!'''",
'previewconflict'                  => 'Әлеге алдан карау битендә сакланачак текстның ничек күренәчәге күрсәтелә.',
'session_fail_preview'             => "'''Кызганычка, сезнең сессия идентификаторыгыз югалды. Нәтиҗәдә сервер үзгәртүләрегезне кабул итә алмый.
Тагын бер тапкыр кабатлавыгыз сорала.
Бу хата тагын кабатланса, [[Special:UserLogout|чыгыгыз]] һәм яңадан керегез.'''",
'session_fail_preview_html'        => "'''Кызганычка, сезнең сессия турында мәгълүматлар югалды. Нәтиҗәдә сервер үзгәртүләрегезне кабул итә алмый.'''

''{{SITENAME}} чиста HTML кулланырга рөхсәт итә, ә бу үз чиратында JavaScript-атакалар оештыру өчен кулланылырга мөмкин. Шул сәбәпле сезнең өчен алдан карау мөмкинлеге ябык.''

'''Әгәр сез үзгәртүне яхшы ният белән башкарасыз икән, тагын бер тапкыр кабатлап карагыз. Хата кабатланса, сайттан [[Special:UserLogout|чыгыгыз]] һәм яңадан керегез.'''",
'token_suffix_mismatch'            => "'''Сезнең үзгәртү кабул ителмәде.'''
Сәбәбе: браузерыгыз үзгәртү өлкәсендәге пунктуацияне дөрес күрсәтми, нәтиҗәдә текст бозылырга мөмкин.
Мондый хаталар аноним web-проксилар кулланганда килеп чыгарга мөмкин.",
'editing'                          => '«$1» битен үзгәртү',
'editingsection'                   => '«$1» битендә бүлек үзгәртүе',
'editingcomment'                   => '«$1» битен үзгәртү (яңа бүлек)',
'editconflict'                     => 'Үзгәртү конфликты: $1',
'explainconflict'                  => 'Сез бу битне төзәткән вакытта кемдер аңа үзгәрешләр кертте. Өстәге тәрәзәдә Сез хәзерге текстны күрәсез. Астагы тәрәзәдә Сезнең вариант урнашкан. Эшләгән үзгәртүләрегезне астагы тәрәзәдән өстәгенә күчерегез. «Битне саклау» төймәсенә баскач өстәге битнең тексты сакланаячак.',
'yourtext'                         => 'Сезнең текст',
'storedversion'                    => 'Сакланган версия',
'nonunicodebrowser'                => "'''Кисәтү: Сезнең браузер Юникод кодировкасын танымый.'''
Үзгәртү вакытында ASCII булмаган символлар махсус уналтылы кодларга алыштырылачак.",
'editingold'                       => "'''Кисәтү: Сез битнең искергән версиясен үзгәртәсез.'''
Саклау төймәсенә баскан очракта яңа версияләрдәге үзгәртүләр югалачак.",
'yourdiff'                         => 'Аермалар',
'copyrightwarning'                 => "Бөтен өстәмәләр һәм үзгәртүләр $2 (карагыз: $1) лицензиясе шартларында башкарыла дип санала.
Әгәр аларның ирекле таратылуын һәм үзгәртелүен теләмәсәгез, монда өстәмәвегез сорала.<br />
Сез өстәмәләрнең авторы булырга яисә мәгълүматның ирекле чыганаклардан алынуын күрсәтергә тиеш.<br />
'''МАХСУС РӨХСӘТТӘН БАШКА АВТОРЛЫК ХОКУКЫ БУЕНЧА САКЛАНУЧЫ МӘГЪЛҮМАТЛАР УРНАШТЫРМАГЫЗ!'''",
'copyrightwarning2'                => "Сезнең үзгәртүләр башка кулланучылар тарафыннан үзгәртелә яисә сыздырыла ала.
Әгәр аларның үзгәртелүен теләмәсәгез, монда өстәмәвегез сорала.<br />
Сез өстәмәләрнең авторы булырга яисә мәгълүматның ирекле чыганаклардан алынуын күрсәтергә тиеш (карагыз: $1).
'''МАХСУС РӨХСӘТТӘН БАШКА АВТОРЛЫК ХОКУКЫ БУЕНЧА САКЛАНУЧЫ МӘГЪЛҮМАТЛАР УРНАШТЫРМАГЫЗ!'''",
'longpagewarning'                  => "'''Кисәтү:''' Бу битнең зурлыгы - $1 килобайт.
32 Кб яисә аннан зуррак битләр кайбер браузерларда ялгыш күренергә мөмкин.
Текстны берничә өлешкә бүләргә тәгъдим ителә.",
'longpageerror'                    => "'''ХАТА: сакланучы текст зурлыгы - $1 килобайт, бу $2 килобайт чигеннән күбрәк. Бит саклана алмый.'''",
'readonlywarning'                  => "'''Кисәтү: мәгълүматлар базасында техник эшләр башкарыла, сезнең үзгәртүләр хәзер үк саклана алмый.
Текст югалмасын өчен аны компьютерыгызга саклап тора аласыз.'''

Администратор күрсәткән сәбәп: $1",
'protectedpagewarning'             => "'''Кисәтү: сез бу битне үзгәртә алмыйсыз, бу хокукка администраторлар гына ия.'''
Түбәндә көндәлекнең  соңгы язуы бирелгән:",
'semiprotectedpagewarning'         => "'''Кисәтү:''' бу бит якланган. Аны теркәлгән кулланучылар гына үзгәртә ала.",
'cascadeprotectedwarning'          => "'''Кисәтү:''' Бу битне администраторлар гына үзгәртә ала. Сәбәбе: ул {{PLURAL:$1|каскадлы яклау исемлегенә кертелгән}}:",
'titleprotectedwarning'            => "'''Кисәтү: Мондый исемле бит якланган, аны үзгәртү өчен [[Special:ListGroupRights|тиешле хокукка]] ия булу зарур.'''",
'templatesused'                    => 'Бу биттә кулланылган {{PLURAL:$1|шаблон|шаблоннар}}:',
'templatesusedpreview'             => 'Алдан каралучы биттә кулланылган {{PLURAL:$1|үрнәк|үрнәкләр}}:',
'templatesusedsection'             => 'Бу бүлектә кулланылган {{PLURAL:$1|үрнәк|үрнәкләр}}:',
'template-protected'               => '(якланган)',
'template-semiprotected'           => '(өлешчә якланган)',
'hiddencategories'                 => 'Бу бит $1 {{PLURAL:$1|яшерен төркемгә}} керә:',
'nocreatetitle'                    => 'Битләр төзү чикләнгән',
'nocreatetext'                     => '{{SITENAME}}: сайтта яңа битләр төзү чикләнгән.
Сез артка кайтып, төзелгән битне үзгәртә аласыз. [[Special:UserLogin|Керергә яисә теркәлергә]] тәгъдим ителә.',
'nocreate-loggedin'                => 'Сезгә яңа битләр төзү хокукы бирелмәгән.',
'sectioneditnotsupported-title'    => 'Бүлекләрне төзәтү рөхсәт ителмәгән.',
'sectioneditnotsupported-text'     => 'Бу сәхифәдә бүлекләрне төзәтү рөхсәт ителмәгән.',
'permissionserrors'                => 'Керү хокукы хаталары',
'permissionserrorstext'            => 'Түбәндәге {{PLURAL:$1|сәбәп|сәбәпләр}} аркасында сез бу гамәлне башкара алмыйсыз:',
'permissionserrorstext-withaction' => '$2 гамәлен башкара алмыйсыз. {{PLURAL:$1|Сәбәбе|Сәбәпләре}}:',
'recreate-moveddeleted-warn'       => "'''Игътибар: Сез бетерелгән бит урынына яңа бит ясамакчы буласыз.'''

Сезгә чыннан да бу битне яңадан ясау кирәкме?
Түбәндә битнең бетерү һәм күчерү көндәлеге китерелә:",
'moveddeleted-notice'              => 'Бу бит бетерелгән иде.
Түбәндә бетерелү һәм күчерелү көндәлекне китерелә.',
'log-fulllog'                      => 'Көндәлекне тулысынча карау',
'edit-hook-aborted'                => 'Үзгәртү махсус процедура тарафыннан кире кагыла.
Сәбәпләре китерелми.',
'edit-gone-missing'                => 'Битне яңартып булмый.
Ул сыздырылган булырга мөмкин.',
'edit-conflict'                    => 'Үзгәртүләр конфликты.',
'edit-no-change'                   => 'Текстта үзгәешләр ясалмау сәбәпле, сезнең үзгәртү кире кагыла.',
'edit-already-exists'              => 'Яңа бит төзеп булмый.
Ул инде бар.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Игътибар:''' бу биттә хәтерне еш кулланучы функцияләр артык күп.

Чикләү: $2 {{PLURAL:$2|куллану}}, бу очракта {{PLURAL:$1|$1 тапкыр}} башкарырга рөхсәт ителә.",
'expensive-parserfunction-category'       => 'Хәтерне еш кулланучы функцияләр күп булган битләр',
'post-expand-template-inclusion-warning'  => "'''Игътибар:''' Кулланылучы үрнәкләр артык зур.
Кайберләре кабызылмаячак.",
'post-expand-template-inclusion-category' => 'Рөхсәт ителгән күләмнән артык булган үрнәкле битләр',
'post-expand-template-argument-warning'   => "'''Игътибар:''' Бу бит ачу өчен зур булган кимендә бер үрнәк аргументына ия.
Мондый аргументлар төшереп калдырылды.",
'post-expand-template-argument-category'  => 'Төшереп калдырылган үрнәк аргументлы битләр',
'parser-template-loop-warning'            => 'Үрнәкләрдә йомык сылтама табылды: [[$1]]',
'parser-template-recursion-depth-warning' => '($1) үрнәген рекурсия итеп куллану чиге рөхсәт ителгәннән артып киткән',

# "Undo" feature
'undo-success' => 'Үзгәртүдән баш тартып була.
Версияләрне чагыштыруны карагыз һәм, үзгәртүләр Сез теләгәнчә булса, битне саклагыз.',
'undo-failure' => 'Аралыктагы үзгәртүләр туры килмәү сәбәпле, үзгәртүдән баш тартып булмый.',
'undo-norev'   => 'Үзгәртү юк яисә ул сыздырылган, шуңа аннан баш тартып булмый.',
'undo-summary' => '[[Special:Contributions/$2|$2]] кулланучысының ([[User talk:$2|бәхәс]]) $1 үзгәртүеннән баш тарту',

# Account creation failure
'cantcreateaccounttitle' => 'Хисап язмасын төзеп булмый',
'cantcreateaccount-text' => "Бу IP-адрестан (<b>$1</b>) хисап язмалары төзү тыела. Тыючы: [[User:$3|$3]].

$3 күрсәткән сәбәп: ''$2''",

# History pages
'viewpagelogs'           => 'Бу битнең көндәлекләрен карау',
'nohistory'              => 'Бу битнең үзгәртүләр тарихы юк.',
'currentrev'             => 'Хәзерге версия',
'currentrev-asof'        => 'Хәзерге версия, $1',
'revisionasof'           => '$1 версиясе',
'revision-info'          => 'Версия: $1; $2',
'previousrevision'       => '← Алдагы юрама',
'nextrevision'           => 'Чираттагы юрама →',
'currentrevisionlink'    => 'Хәзерге версия',
'cur'                    => 'хәзерге',
'next'                   => 'киләсе',
'last'                   => 'бая.',
'page_first'             => 'беренче',
'page_last'              => 'соңгы',
'histlegend'             => "Аңлатмалар: '''({{int:cur}})''' = хәзерге версиядән аерымлыклар, '''({{int:last}})''' = баягы версиядән аерымлыклар, '''{{int:minoreditletter}}''' = кече үзгәртүләр.",
'history-fieldset-title' => 'Тарихын карау',
'history-show-deleted'   => 'Бары тик бетерү',
'histfirst'              => 'Элеккеге',
'histlast'               => 'Соңгы',
'historysize'            => '($1 {{PLURAL:$1|байт}})',
'historyempty'           => '(буш)',

# Revision feed
'history-feed-title'          => 'Үзгәртүләр тарихы',
'history-feed-description'    => 'Бу битнең викидагы үзгәртүләр тарихы',
'history-feed-item-nocomment' => '$1, $2',
'history-feed-empty'          => 'Соратылган бит юк.
Ул сыздырылган яисә бүтән урынга күчерелгән (башка исем алган) булырга мөмкин.
[[Special:Search|Эзләтеп]] карагыз.',

# Revision deletion
'rev-deleted-comment'         => '(фикер сыздырылган)',
'rev-deleted-user'            => '(автор исеме сыздырылган)',
'rev-deleted-event'           => '(язма бетерелгән)',
'rev-deleted-text-permission' => "Битнең бу версиясе '''сыздырылган'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Сыздырулар журналында] аңлатмалар калдырылган булырга мөмкин.",
'rev-deleted-text-unhide'     => "Битнең бу версиясе '''сыздырылган'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Сыздырулар журналында]  аңлатмалар калдырылган булырга мөмкин.
Сез администратор булу сәбәпле, [$1 бирелгән версияне карый аласыз].",
'rev-suppressed-text-unhide'  => "Битнең бу версиясе '''яшерелгән'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Яшерүләр журналында] аңлатмалар бирелгән булырга мөмкин.
Сез администратор булу сәбәпле, [$1 бирелгән версияне карый аласыз].",
'rev-deleted-text-view'       => "Битнең бу версиясе '''сыздырылган'''.
Сез администратор булу сәбәпле, аны карый аласыз. [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Сыздырулар журналында] аңлатмалар бирелгән булырга мөмкин.",
'rev-suppressed-text-view'    => "Битнең бу версиясе '''яшерелгән'''.
Сез администратор булу сәбәпле, аны карый аласыз. [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Яшерүләр журналында] аңлатмалар бирелгән булырга мөмкин.",
'rev-deleted-no-diff'         => "Сез версияләр арасындагы аермаларны карый алмыйсыз. Сәбәбе: кайсыдыр версия '''сыздырылган'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Сыздырулар журналында] тулырак мәгълүмат табып була.",
'rev-deleted-unhide-diff'     => "Битнең кайсыдыр версиясе '''сыздырылган'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Сыздырулар журналында] тулырак мәгълүмат табып була.
Сез администратор булу сәбәпле, [$1 бирелгән версияне карый аласыз]",
'rev-delundel'                => 'күрсәтү/яшерү',
'rev-showdeleted'             => 'күрсәтү',
'revisiondelete'              => 'Битнең версиясен сыздырырга / кайтарырга',
'revdelete-nooldid-title'     => 'Ахыргы версия билгеләнмәгән',
'revdelete-nooldid-text'      => 'Бу функцияне башкару өчен сез ахыргы версияне (яки версияләрне) билгеләмәдегез.',
'revdelete-nologtype-title'   => 'Көндәлек тибы билгеләнмәгән',
'revdelete-nologtype-text'    => 'Гамәл башкарылырга тиешле ңурнал тибын билгеләргә оныттыгыз.',
'revdelete-nologid-title'     => 'Көндәлектәге язма хаталы',
'revdelete-show-file-submit'  => 'Әйе',
'revdelete-legend'            => 'Чикләүләр урнаштыр:',
'revdelete-hide-text'         => 'Битнең бу версиясе текстын яшер',
'revdelete-radio-set'         => 'Әйе',
'revdelete-radio-unset'       => 'Юк',
'revdel-restore'              => 'күренүчәнлекне үзгәртергә',
'pagehist'                    => 'битнең тарихы',
'deletedhist'                 => 'Бетерүләр тарихы',
'revdelete-content'           => 'контент',
'revdelete-uname'             => 'кулланучы исеме',

# History merging
'mergehistory-from'   => 'Чыганак:',
'mergehistory-reason' => 'Сәбәп:',

# Merge log
'mergelog'    => 'Берләштерүләр көндәлеге',
'revertmerge' => 'Бүләргә',

# Diffs
'history-title'           => '$1 битенең үзгәртү тарихы',
'difference'              => '(Юрамалар арасында аерма)',
'lineno'                  => '$1 юл:',
'compareselectedversions' => 'Сайланган юрамаларны чагыштыру',
'editundo'                => 'үткәрмәү',

# Search results
'searchresults'                => 'Эзләү нәтиҗәләре',
'searchresults-title'          => '«$1» өчен эзләү нәтиҗәләре',
'searchresulttext'             => 'Проектның сәхифәләрендә эзләү турында тулырак мәгълумат алыр өчен [[{{MediaWiki:Helppage}}|өстәмә мәгълумат]] битенә керегез.',
'searchsubtitle'               => '«[[:$1]]» өчен эзләү ([[Special:Prefixindex/$1|«$1» дан башлый барлык битләр]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» га сылтый барлык битләр]])',
'searchsubtitleinvalid'        => '"$1" таләбе буенча',
'notitlematches'               => 'Битнең исемнәрендә туры килүләр юк',
'notextmatches'                => 'Тиңдәш текстлы битләр юк',
'prevn'                        => 'алдагы {{PLURAL:$1|$1}}',
'nextn'                        => 'чираттагы {{PLURAL:$1|$1}}',
'viewprevnext'                 => 'Күрсәтелүе: ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'               => 'Help:Эчтәлек',
'searchprofile-images'         => 'Мультимедиа',
'searchprofile-everything'     => 'Һәркайда',
'searchprofile-advanced'       => 'Киңәйтелгән',
'searchprofile-images-tooltip' => 'Файллар эзләү',
'search-result-size'           => '$1 ({{PLURAL:$2|$2 сүз}})',
'search-redirect'              => '(юнәлтү $1)',
'search-section'               => '($1 бүлеге)',
'search-suggest'               => 'Бәлки, сез моны эзлисез: $1',
'search-interwiki-caption'     => 'Тугандаш проектлар',
'search-interwiki-default'     => '$1 нәтиҗә:',
'search-interwiki-more'        => '(тагын)',
'search-mwsuggest-enabled'     => 'киңәшләр белән',
'search-mwsuggest-disabled'    => 'киңәшсез',
'search-relatedarticle'        => 'Бәйләнгән',
'searchall'                    => 'барлык',
'nonefound'                    => "'''Искәрмә'''. Килешү буенча эзләү кайбер исем аланнарында гына эшли.
Барлык аланнарда (фикер алышу битләре, үрнәкләр, һ.б.) эзләү өчен ''all'' сүзен сайлагыз, яисә кирәкле исем аланын сайлагыз.",
'powersearch'                  => 'Өстәмә эзләү',
'powersearch-legend'           => 'Өстәмә эзләү',
'powersearch-ns'               => 'исемнәрендә эзләү',
'powersearch-redir'            => 'Юнәлтүләр күрсәтелсен',
'powersearch-field'            => 'Эзләү',
'powersearch-toggleall'        => 'Барысы',

# Quickbar
'qbsettings'      => 'Күчешләр аслыгы',
'qbsettings-none' => 'Күрсәтмәскә',

# Preferences page
'preferences'                 => 'Көйләнмәләр',
'mypreferences'               => 'Көйләнмәләрем',
'prefs-edits'                 => 'Үзгәртүләр исәбе:',
'prefsnologin'                => 'Кермәгәнсез',
'prefsnologintext'            => 'Кулланучы көйләнмәләрене үзгәртү өчен, сез <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} керергә]</span> тиешсез.',
'changepassword'              => 'Серсүзне алыштырырга',
'prefs-skin'                  => 'Күренеш',
'skin-preview'                => 'Алдан карау',
'prefs-math'                  => 'Формулалар',
'prefs-datetime'              => 'Дата һәм вакыт',
'prefs-personal'              => 'Шәхси мәгълүматлар',
'prefs-rc'                    => 'Соңгы үзгәртүләр',
'prefs-watchlist'             => 'Күзәтү исемлеге',
'prefs-watchlist-days'        => 'Күзәтү исемлегендә ничә көн буена үзгәртүләрне күрсәтергә:',
'prefs-watchlist-edits'       => 'Киңәйтелгән күзәтү исемлегендә үзгәртүләрнең иң югары исәбе:',
'prefs-misc'                  => 'Башка көйләнмәләр',
'prefs-resetpass'             => 'Серсүзне үзгәртү',
'saveprefs'                   => 'Саклау',
'resetprefs'                  => 'Сакланмаган үзгәртүләрне бетерү',
'prefs-editing'               => 'Үзгәртү',
'rows'                        => 'Юллар:',
'columns'                     => 'Баганалар:',
'searchresultshead'           => 'Эзләү',
'savedprefs'                  => 'Көйләнмәләрегез сакланды.',
'servertime'                  => 'Серверның вакыты:',
'timezoneregion-africa'       => 'Африка',
'timezoneregion-america'      => 'Америка',
'timezoneregion-antarctica'   => 'Антарктика',
'timezoneregion-arctic'       => 'Арктика',
'timezoneregion-asia'         => 'Азия',
'timezoneregion-atlantic'     => 'Атлантик океан',
'timezoneregion-australia'    => 'Австралия',
'timezoneregion-europe'       => 'Аурупа',
'timezoneregion-indian'       => 'Һиндстан океаны',
'timezoneregion-pacific'      => 'Тын океан',
'default'                     => 'килешү буенча',
'prefs-files'                 => 'Файллар',
'youremail'                   => 'Электрон почта:',
'username'                    => 'Кулланучы исеме:',
'uid'                         => 'Кулланучының идентификаторы:',
'yourrealname'                => 'Чын исем:',
'yourlanguage'                => 'Тел:',
'yournick'                    => 'Яңа имзагыз:',
'badsig'                      => 'Имза дөрес түгел. HTML-теглар тикшерегез.',
'badsiglength'                => 'Имзагыз бигрәк озын.
Ул $1 {{PLURAL:$1|хәрефтән}} күбрәк булырга тиеш түгел.',
'yourgender'                  => 'Җенес:',
'gender-male'                 => 'Ир',
'gender-female'               => 'Хатын',
'email'                       => 'Электрон почта',
'prefs-help-realname'         => 'Чын исемегез (кирәкми): аны күрсәтсәгез, ул битне үзгәртүче күрсәтү өчен файдалаячак.',
'prefs-help-email-required'   => 'Электрон почта адресы кирәк.',
'prefs-signature'             => 'Имза',
'prefs-advancedediting'       => 'Киңәйтелгән көйләүләр',
'prefs-advancedrc'            => 'Киңәйтелгән көйләүләр',
'prefs-advancedrendering'     => 'Киңәйтелгән көйләүләр',
'prefs-advancedsearchoptions' => 'Киңәйтелгән көйләүләр',
'prefs-advancedwatchlist'     => 'Киңәйтелгән көйләүләр',

# User rights
'userrights-groupsmember' => 'Әгъза:',

# Groups
'group-bot'        => 'Ботлар',
'group-sysop'      => 'Идарәчеләр',
'group-bureaucrat' => 'Бюрократлар',
'group-suppress'   => 'Тикшерүчеләр',
'group-all'        => '(барлык)',

'group-user-member'          => 'Кулланучы',
'group-autoconfirmed-member' => 'Авторасланган кулланучы',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Идарәче',
'group-bureaucrat-member'    => 'Бюрократ',
'group-suppress-member'      => 'Тикшерүче',

'grouppage-user'          => '{{ns:project}}:Кулланучылар',
'grouppage-autoconfirmed' => '{{ns:project}}:Авторасланган кулланучылар',
'grouppage-bot'           => '{{ns:project}}:Ботлар',
'grouppage-sysop'         => '{{ns:project}}:Идарәчеләр',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократлар',
'grouppage-suppress'      => '{{ns:project}}:Тикшерүчеләр',

# Rights
'right-read'          => 'сәхифәләрне карау',
'right-edit'          => 'сәхифәләрне төзәтү',
'right-move'          => 'сәхифәләрне күчерү',
'right-editinterface' => 'кулланучының интерыейсын үзгәртү',

# User rights log
'rightslog'      => 'Кулланучының хокуклары көндәлеге',
'rightslogentry' => '$1 кулланучысын $2 группасыннан $3 группасына күчерде',
'rightsnone'     => '(юк)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'бу битне үзгәртергә',
'action-createpage' => 'сәхифәләрне язырга',
'action-move'       => 'бу битне күчерү',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|үзгәртү|үзгәртү}}',
'recentchanges'                     => 'Соңгы үзгәртүләр',
'recentchanges-legend'              => 'Соңгы үзгәртүләр көйләүләре',
'recentchangestext'                 => 'Бу биттә {{grammar:genitive|{{SITENAME}}}} проектының соңгы үзгәртүләре күрсәтелә.',
'recentchanges-feed-description'    => 'Бу агымда соңгы үзгәртүләрне күзәтү.',
'recentchanges-legend-newpage'      => '$1 — яңа бит',
'recentchanges-label-newpage'       => 'Бу үзгәртү белән яңа бит төзелде',
'recentchanges-legend-minor'        => '$1 — кече үзгәртү',
'recentchanges-legend-bot'          => '$1 — ботның үзгәртүе',
'rcnote'                            => 'Аста $4 $5 вакытынна соңгы {{PLURAL:$2|1|$2}} көн эчендә булган соңгы {{PLURAL:$1|1|$1}} үзгәртмә күрсәтелә:',
'rcnotefrom'                        => "Астарак '''$2''' башлап ('''$1''' кадәр) үзгәртүләр күрсәтелгән.",
'rclistfrom'                        => '$1 башлап яңа үзгәртүләрне күрсәт',
'rcshowhideminor'                   => 'кече үзгәртүләрне $1',
'rcshowhidebots'                    => 'ботларны $1',
'rcshowhideliu'                     => 'кергән кулланучыларны $1',
'rcshowhideanons'                   => 'кермәгән кулланучыларны $1',
'rcshowhidepatr'                    => 'тикшерергән үзгәртүләрне $1',
'rcshowhidemine'                    => 'минем үзгәртүләремне $1',
'rclinks'                           => 'Соңгы $2 көн эчендә соңгы $1 үзгәртүне күрсәт<br />$3',
'diff'                              => 'аерма',
'hist'                              => 'тарих',
'hide'                              => 'яшер',
'show'                              => 'күрсәт',
'minoreditletter'                   => 'к',
'newpageletter'                     => 'Я',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|күзәтеп тора кулланучы}}]',
'rc_categories_any'                 => 'Һәрбер',
'newsectionsummary'                 => '/* $1 */ яңа бүлек',
'rc-enhanced-expand'                => 'Ваклыкларны күрсәтү (JavaScript кирәк)',
'rc-enhanced-hide'                  => 'Ваклыкларны яшерү',

# Recent changes linked
'recentchangeslinked'          => 'Бәйләнешле үзгәртүләр',
'recentchangeslinked-feed'     => 'Бәйләнешле үзгәртүләр',
'recentchangeslinked-toolbox'  => 'Бәйләнешле үзгәртүәләр',
'recentchangeslinked-title'    => '"$1" битенә бәйләнешле үзгәртүләр',
'recentchangeslinked-noresult' => 'Күрсәтелгән вакытта сылташкан битләрнең үзгәртелмәләре юк иде.',
'recentchangeslinked-summary'  => "Бу күрсәтелгән бит белән сылталган (йә күрсәтелгән төркемгә керткән) битләрнең үзгәртелмәләре исемлеге.
[[Special:Watchlist|Күзәтү исемлегегезгә]] керә торган битләр '''калын'''.",
'recentchangeslinked-page'     => 'Битнең исеме:',
'recentchangeslinked-to'       => 'Моның урынына бу биткә бәйле булган битләрдәге үзгәртүләрне күрсәтү',

# Upload
'upload'        => 'Файлны йөкләү',
'uploadbtn'     => 'Файлны йөкләү',
'uploadlogpage' => 'Йөкләү көндәлеге',
'uploadedimage' => '«[[$1]]» йөкләнгән',

# Special:ListFiles
'listfiles'             => 'Сүрәтләр исемлеге',
'listfiles_date'        => 'Вакыт',
'listfiles_name'        => 'Ат',
'listfiles_user'        => 'Кулланучы',
'listfiles_size'        => 'Үлчәм',
'listfiles_description' => 'Тасвир',

# File description page
'file-anchor-link'          => 'Файл',
'filehist'                  => 'Файлның тарихы',
'filehist-help'             => 'Датага/сәгатькә басыгыз, шул вакытта бит нинди булды дип карау өчен.',
'filehist-deleteone'        => 'бетерү',
'filehist-revert'           => 'кайтарырга',
'filehist-current'          => 'хәзерге',
'filehist-datetime'         => 'Дата/вакыт',
'filehist-thumb'            => 'Эскиз',
'filehist-thumbtext'        => '$1 көнне булган версиянең эскизы',
'filehist-user'             => 'Кулланучы',
'filehist-dimensions'       => 'Зурлык',
'filehist-filesize'         => 'Файлның зурлыгы',
'filehist-comment'          => 'Искәрмә',
'imagelinks'                => 'Файлга сылтамалар',
'linkstoimage'              => 'Бу файлга киләчәк {{PLURAL:$1|бит|$1 бит}} сылтый:',
'nolinkstoimage'            => 'Бу файлга сылтаган битләр юк.',
'sharedupload'              => "Бу файл $1'дан һәм башка проектларда кулланырга мөмкин.",
'uploadnewversion-linktext' => 'Бу файлның яңа версиясен йөкләү',

# MIME search
'mimesearch' => 'MIME эзләү',

# List redirects
'listredirects' => 'Юнәлтүләр исемлеге',

# Unused templates
'unusedtemplates' => 'Кулланылмаган үрнәкләр',

# Random page
'randompage' => 'Очраклы бит',

# Random redirect
'randomredirect' => 'Очраклы биткә күчү',

# Statistics
'statistics' => 'Статистика',

'disambiguations' => 'Күп мәгънәле сүзләр турында битләр',

'doubleredirects' => 'Икеләтә юнәлтүләр',

'brokenredirects' => 'Бәйләнешсез юнәлтүләр',

'withoutinterwiki'        => 'Телләрара сылтамасыз битләр',
'withoutinterwiki-submit' => 'Күрсәтү',

'fewestrevisions' => 'Аз үзгәртүләр белән битләр',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт}}',
'nlinks'                  => '$1 {{PLURAL:$1|сылтама}}',
'nmembers'                => '$1 {{PLURAL:$1|әгъза}}',
'lonelypages'             => 'Үксез битләр',
'uncategorizedpages'      => 'Төркемләнмәгән битләр',
'uncategorizedcategories' => 'Төркемләнмәгән төркемнәр',
'uncategorizedimages'     => 'Төркемләнмәгән сүрәтләр',
'uncategorizedtemplates'  => 'Төркемләнмәгән үрнәкләр',
'unusedcategories'        => 'Кулланмаган төркемнәр',
'unusedimages'            => 'Кулланмаган сүрәтләр',
'wantedcategories'        => 'Зарур төркемнәр',
'wantedpages'             => 'Зарур битләр',
'mostlinked'              => 'Күп үзенә сылтамалы битләр',
'mostlinkedcategories'    => 'Күп үзенә сылтамалы төркемнәр',
'mostlinkedtemplates'     => 'Иң күп кулланылган үрнәкләр',
'mostcategories'          => 'Күп төркемләргә кертелгән битләр',
'mostimages'              => 'Иң кулланган сүрәтләр',
'mostrevisions'           => 'Күп үзгәртүләр белән битләр',
'prefixindex'             => 'Барлык алкушымча белән битләр',
'shortpages'              => 'Кыска битләр',
'longpages'               => 'Озын битләр',
'deadendpages'            => 'Тупик битләре',
'protectedpages'          => 'Якланган битләр',
'listusers'               => 'Кулланучылар исемлеге',
'newpages'                => 'Яңа битләр',
'ancientpages'            => 'Иң иске битләр',
'move'                    => 'Күчерү',
'movethispage'            => 'Бу битне күчерү',
'pager-newer-n'           => '{{PLURAL:$1|1 яңарак|$1 яңарак}}',
'pager-older-n'           => '{{PLURAL:$1|1 искерәк|$1 искерәк}}',

# Book sources
'booksources'               => 'Китап чыганаклары',
'booksources-search-legend' => 'Китап чыганакларыны эзләү',
'booksources-go'            => 'Башкару',

# Special:Log
'specialloguserlabel'  => 'Кулланучы:',
'speciallogtitlelabel' => 'Башлам:',
'log'                  => 'Көндәлекләр',
'all-logs-page'        => 'Барлык журналлар',

# Special:AllPages
'allpages'       => 'Барлык битләр',
'alphaindexline' => '$1 битеннән $2 битенә кадәр',
'nextpage'       => 'Алдагы бит ($1)',
'prevpage'       => 'Алдагы бит ($1)',
'allpagesfrom'   => 'Моңа башланучы битләрне чыгарырга:',
'allpagesto'     => 'Монда чыгаруны туктатырга:',
'allarticles'    => 'Барлык битләр',
'allpagesprev'   => 'Элекке',
'allpagesnext'   => 'Киләсе',
'allpagessubmit' => 'Башкару',
'allpagesprefix' => 'Алкушымчалы битләрне күрсәтергә:',

# Special:Categories
'categories'                    => 'Төркемнәр',
'categoriespagetext'            => 'Викидә бу категорияләре бар.',
'special-categories-sort-count' => 'исәп буенча тәртипләү',
'special-categories-sort-abc'   => 'әлифба буенча тәртипләү',

# Special:LinkSearch
'linksearch'    => 'Тышкы сылтамалар',
'linksearch-ok' => 'Эзләргә',

# Special:ListUsers
'listusers-submit'   => 'Күрсәтергә',
'listusers-noresult' => 'Кулланучыларны табылмады.',

# Special:ActiveUsers
'activeusers-noresult' => 'Катнашучылар табылмады.',

# Special:Log/newusers
'newuserlogpage'          => 'Кулланучыларны теркәү көндәлеге',
'newuserlog-create-entry' => 'Яңа кулланучы',

# Special:ListGroupRights
'listgrouprights-members' => '(группа исемлеге)',

# E-mail user
'emailuser'       => 'Бу кулланучыга хат',
'emailpage'       => 'Кулланучыга хат җибәрү',
'defemailsubject' => '{{SITENAME}}: хат',
'noemailtitle'    => 'Электрон почта адресы юк',
'emailfrom'       => 'Кемнән:',
'emailto'         => 'Кемгә:',
'emailsubject'    => 'Тема:',
'emailmessage'    => 'Хәбәр:',
'emailsend'       => 'Җибәрү',
'emailccme'       => 'Миңа хәбәрнең күчермәсене җибәрергә.',
'emailccsubject'  => '$1 өчен хәбәрегезнең күчермәсе: $2',
'emailsent'       => 'Хат җибәрелгән',

# Watchlist
'watchlist'         => 'Күзәтү исемлегем',
'mywatchlist'       => 'Күзәтү исемлегем',
'watchlistfor'      => "('''$1''' өчен)",
'addedwatch'        => 'Күзәтү исемлегенә өстәгән',
'addedwatchtext'    => "\"[[:\$1]]\" бите [[Special:Watchlist|күзәтү исемлегегезгә]] өстәлде.
Бу биттә һәм аның бәхәслегендә барлык булачак үзгәртүләр шунда күрсәтелер, һәм, [[Special:RecentChanges|соңгы үзгәртүләр]] исемлегендә бу битне җиңелрәк табу өчен, ул '''калын мәтен''' белән күрсәтелер.",
'removedwatch'      => 'Күзәтү исемлегеннән бетерелгән',
'removedwatchtext'  => '«[[:$1]]» бите [[Special:Watchlist|сезнең күзәтү исемлегеннән]] бетерелде.',
'watch'             => 'Күзәтү',
'watchthispage'     => 'Бу битне күзәтү',
'unwatch'           => 'Күзәтмәскә',
'watchlist-details' => 'Күзәтү исемлегегездә, бәхәс битләрен санамыйча, {{PLURAL:$1|$1 бит|$1 бит}} бар.',
'wlshowlast'        => 'Баягы $1 сәгать $2 көн эчендә яки $3ны күрсәт',
'watchlist-options' => 'Күзәтү исемлеге көйләүләре',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Күзәтү исемлегемә өстәүе…',
'unwatching' => 'Күзәтү исемлегемнән чыгаруы…',

'enotif_newpagetext'           => 'Бу яңа бит.',
'enotif_impersonal_salutation' => '{{SITENAME}} кулланучы',
'changed'                      => 'үзгәртелде',
'created'                      => 'төзергән',
'enotif_subject'               => '{{SITENAME}} проектының $PAGETITLE бите $PAGEEDITOR тарафыннан $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Соңгы керүегездән соң булган барлык үзгәртүләрне күрер өчен, бу сылтама аша узыгыз: $1',
'enotif_body'                  => '$WATCHINGUSERNAME,

«{{SITENAME}}» проектының «$PAGETITLE» бите $PAGEEDITOR тарафыннан $PAGEEDITDATE көнне $CHANGEDORCREATED. Битне карар өчен $PAGETITLE_URL буенча узыгыз.

$NEWPAGE

Үзгәртүнең кыска эчтәлеге: $PAGESUMMARY $PAGEMINOREDIT

Үзгәртүчегә язу:
эл. почта $PAGEEDITOR_EMAIL
вики $PAGEEDITOR_WIKI

Бу биткә кермәсәгез, аның башка үзгәртүләре турында хат җибәрелмәячәк. Шулай ук сез күзәтү исемлегегездә булган битләр өчен хәбәр бирү флагын алып куя аласыз.

             {{SITENAME}} хәбәр бирү системасы

--
Күзәтү исемлеге көйләүләрен үзгәртү:
{{fullurl:{{#special:Watchlist}}/edit}}

Элемтә һәм ярдәм:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Битне бетерү',
'confirm'               => 'Расларга',
'excontent'             => 'эчтәлек: «$1»',
'exblank'               => 'бит буш иде',
'delete-confirm'        => '«$1» бетерүе',
'delete-legend'         => 'Бетерү',
'historywarning'        => 'Кисәтү: сез бетерергә теләгән биттә үзгәртү тарихы бар:',
'confirmdeletetext'     => 'Сез бу битнең (яки рәсемнең) тулысынча бетерелүен сорадыгыз.
Зинһар, моны чыннан да эшләргә теләгәнегезне, моның нәтиҗәләрен аңлаганыгызны һәм [[{{MediaWiki:Policy-url}}]] бүлегендәге кагыйдәләр буенча эшләгәнегезне раслагыз.',
'actioncomplete'        => 'Гамәл башкарган',
'deletedtext'           => '«<nowiki>$1</nowiki>» бетерелгән инде.<br />
Соңгы бетерелгән битләрне күрер өчен, $2 карагыз.',
'deletedarticle'        => '«[[$1]]» бетерелде',
'dellogpage'            => 'Бетерү көндәлеге',
'deletionlog'           => 'бетерү көндәлеге',
'deletecomment'         => 'Сәбәп:',
'deleteotherreason'     => 'Башка/өстәмә сәбәп:',
'deletereasonotherlist' => 'Башка сәбәп',

# Rollback
'rollbacklink' => 'кире кайтару',

# Protect
'protectlogpage'              => 'Яклану көндәлеге',
'protectedarticle'            => '«[[$1]]» якланган',
'modifiedarticleprotection'   => '"[[$1]]" бите өчен яклау дәрәҗәсе үзгәртелде',
'unprotectedarticle'          => '«[[$1]]» инде якланмаган',
'prot_1movedto2'              => '«[[$1]]» бите «[[$2]]» битенә күчерелде',
'protectcomment'              => 'Сәбәп:',
'protectexpiry'               => 'Бетә:',
'protect_expiry_invalid'      => 'Яклау бетү вакыты дөрес түгел.',
'protect_expiry_old'          => 'Яклау бетү көне узган көнгә куелган.',
'protect-text'                => "Биредә сез '''<nowiki>$1</nowiki>''' бите өчен яклау дәрәҗәсене карый һәм үзгәрә аласыз.",
'protect-locked-access'       => "Хисап язмагызга битләрнең яклау дәрәҗәсен үзгәртү өчен хак җитми. '''$1''' битенең хәзерге көйләүләре:",
'protect-cascadeon'           => 'Бу бит якланган, чөнки ул әлеге каскадлы яклаулы {{PLURAL:$1|биткә|битләргә}} керә. Сез бу битнең яклау дәрәҗәсен үзгәртә аласыз, әмма каскадлы яклау үзгәрмәячәк.',
'protect-default'             => 'Яклаусыз',
'protect-fallback'            => '«$1»нең рөхсәте кирәк',
'protect-level-autoconfirmed' => 'Яңа һәм теркәлмәгән кулланучыларны кысарга',
'protect-level-sysop'         => 'Идарәчеләр генә',
'protect-summary-cascade'     => 'каскадлы',
'protect-expiring'            => '$1 үтә (UTC)',
'protect-cascade'             => 'Бу биткә кергән битләрне якларга (каскадлы яклау)',
'protect-cantedit'            => 'Сез бу битнең яклау дәрәҗәсене үзгәрә алмыйсыз, чөнки сездә аны үзгәртергә рөхсәтегез юк.',
'protect-expiry-options'      => '1 сәгать:1 hour,1 көн:1 day,1 атна:1 week,2 атна:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 ел:1 year,вакытсыз:infinite',
'restriction-type'            => 'Рөхсәт:',
'restriction-level'           => 'Мөмкинлек дәрәҗәсе:',
'minimum-size'                => 'Иң кечкенә зурлык',
'maximum-size'                => 'Иң югары зурлык:',
'pagesize'                    => '(байт)',

# Restrictions (nouns)
'restriction-edit'   => 'Үзгәртү',
'restriction-move'   => 'Күчерү',
'restriction-create' => 'Төзү',

# Restriction levels
'restriction-level-sysop'         => 'тулы яклау',
'restriction-level-autoconfirmed' => 'өлешчә яклау',
'restriction-level-all'           => 'барлык дәрәҗәләр',

# Undelete
'undelete'                  => 'Бетерелгән битләрне карау',
'undeletepage'              => 'Бетерелгән битләрне карау һәм торгызу',
'viewdeletedpage'           => 'Бетерелгән битләрне карарга',
'undeletehistory'           => 'Бу битне торгызсагыз, аның үзгәртү тарихы да тулысынча торгызылачак.
Бетерелүдән соң шундый ук исемле бит төзелгән булса, торгызылган үзгәртүләр яңа үзгәртүләр алдына куелачак.',
'undeletebtn'               => 'Торгызырга',
'undeletelink'              => 'карарга/торгызырга',
'undeleteviewlink'          => 'карарга',
'undeletereset'             => 'Ташлатырга',
'undeletecomment'           => 'Искәрмә:',
'undeletedarticle'          => '«[[$1]]» торгызылды',
'undelete-search-submit'    => 'Эзләргә',
'undelete-error-long'       => 'Файлны торгызу вакытында хаталар чыкты:

$1',
'undelete-show-file-submit' => 'Әйе',

# Namespace form on various pages
'namespace'      => 'Исемнәр мәйданы:',
'invert'         => 'Киресен сайлау',
'blanknamespace' => '(Төп)',

# Contributions
'contributions'       => 'Кулланучының кертеме',
'contributions-title' => '$1 исемле кулланучының кертеме',
'mycontris'           => 'Кертемем',
'contribsub2'         => '$1 ($2) өчен',
'uctop'               => '(ахыргы)',
'month'               => 'Айдан башлап (һәм элегрәк):',
'year'                => 'Елдан башлап (һәм элегрәк):',

'sp-contributions-newbies'     => 'Яңа хисап язмаларыннан ясалган кертемне генә карау',
'sp-contributions-newbies-sub' => 'Яңа хисап язмалары өчен',
'sp-contributions-blocklog'    => 'Кысу көндәлеге',
'sp-contributions-talk'        => 'бәхәс',
'sp-contributions-search'      => 'Кертемне эзләү',
'sp-contributions-username'    => 'Кулланучының IP адресы яки исеме:',
'sp-contributions-submit'      => 'Эзләргә',

# What links here
'whatlinkshere'            => 'Бирегә нәрсә сылтый',
'whatlinkshere-title'      => '$1 битенә сылтый торган битләр',
'whatlinkshere-page'       => 'Бит:',
'linkshere'                => "'''[[:$1]]''' биткә чираттагы битләр сылтый:",
'nolinkshere'              => "'''[[:$1]]''' битенә башка битләр сылтамыйлар.",
'isredirect'               => 'юнәлтү бите',
'istemplate'               => 'кертүләр',
'isimage'                  => 'рәсем өчен сылтама',
'whatlinkshere-prev'       => '{{PLURAL:$1|алдагы|алдагы $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|чираттагы|чираттагы $1}}',
'whatlinkshere-links'      => '← сылтамалар',
'whatlinkshere-hideredirs' => 'Юнәлтүләрне $1',
'whatlinkshere-hidetrans'  => '$1 кертү',
'whatlinkshere-hidelinks'  => '$1 сылтамалар',
'whatlinkshere-filters'    => 'Фильтрлар',

# Block/unblock
'blockip'                  => 'Кулланучыны кысарга',
'ipaddress'                => 'IP-адрес:',
'ipadressorusername'       => 'IP-адрес яки кулланучы исеме:',
'ipbexpiry'                => 'Бетә:',
'ipbreason'                => 'Сәбәп:',
'ipbreasonotherlist'       => 'Башка сәбәп',
'ipbother'                 => 'Башка вакыт:',
'ipboptions'               => '2 сәгать:2 hours,1 көн:1 day,3 көн:3 days,1 атна:1 week,2 атна:2 weeks,1 ай:1 month,3ай:3 months,6 ай:6 months,1 ел:1 year,чикләнмәгән:infinite',
'ipblocklist'              => 'Кысылган IP-адреслар һәм кулланучы исемләр',
'ipblocklist-username'     => 'кулланучы исеме яки IP-адрес:',
'blocklink'                => 'кысарга',
'unblocklink'              => 'кысмаска',
'change-blocklink'         => 'блоклауны үзгәртергә',
'contribslink'             => 'кертем',
'blocklogpage'             => 'Тыю көндәлеге',
'blocklogentry'            => '[[$1]] $2 вакытка блокланды $3',
'unblocklogentry'          => '$1 кулланучысының блоклану вакыты бетте',
'block-log-flags-nocreate' => 'яңа хисап язмасы теркәү тыелган',
'block-log-flags-noemail'  => 'хат җибәрү тыелган',
'proxyblocksuccess'        => 'Эшләнде',
'sorbsreason'              => 'Сезнең IP-адресыгыз DNSBLда ачык прокси дип санала.',

# Developer tools
'unlockbtn' => 'Мәгълүматлар базасына язу мөмкинлеген кайтарырга',

# Move page
'move-page-legend'          => 'Битне күчерү',
'movepagetext'              => "Астагы форманы куллану битнең исемен алыштырып, аның барлык тарихын яңа исемле биткә күчерер.
Иске исемле бит яңа исемле биткә юнәлтү булып калыр.
Сез иске исемгә юнәлтүләрне автоматик рәвештә яңа исемгә күчерә аласыз.
Әгәр моны эшләмәсәгез, [[Special:DoubleRedirects|икеле]] һәм [[Special:BrokenRedirects|өзелгән юнәлтүләрне]] тикшерегез.
Сез барлык сылтамаларның кирәкле җиргә сылтавына җаваплы.

Күздә тотыгыз: әгәр яңа исем урынында бит булса инде, һәм ул буш яки юнәлтү түгел исә, бит '''күчерелмәячәк'''. 
Бу шуны аңлата: сез ялгышып күчерсәгез, битне кайтара аласыз, әмма инде булган битне бетерә алмыйсыз.

'''Игътибар!'''
Популяр битләрне күчерү зур һәм көтелмәгән нәтиҗәләргә китерә ала.
Дәвам иткәнче, барлык нәтиҗәләрне аңлавыгызны тагын бер кат уйлагыз.",
'movepagetalktext'          => "Бу битнең бәхәс бите дә күчереләчәк, '''бу очраклардан тыш''':
*Андый исемле буш булмаган бәхәс бите бар инде, яисә
*Сез астагы флажокны куймагансыз.

Бу очракларда сезгә битләрне үз кулыгыз белән күчерергә яки кушарга туры килер.",
'movearticle'               => 'Битне күчерү:',
'movenotallowed'            => 'Сездә мәкаләләрне күчерү хокуклары юк.',
'newtitle'                  => 'Яңа башлам:',
'move-watch'                => 'Бу битне күзәтергә',
'movepagebtn'               => 'Битне күчерү',
'pagemovedsub'              => 'Бит күчерелде',
'movepage-moved'            => "'''«$1» бите «$2» битенә күчерелде'''",
'movepage-moved-redirect'   => 'Юнәлтү ясалды.',
'movepage-moved-noredirect' => 'Юнәлтүне ясау тыелды',
'articleexists'             => 'Мондый исемле бит бар инде, яисә мондый исем рөхсәт ителми.
Зинһар башка исем сайлагыз.',
'talkexists'                => "'''Битнен үзе күчерелде, әмма бәхәс бите күчерелми калды, чөнки шундый исемле бит бар инде. Зинһар, аларны үзегез кушыгыз.'''",
'movedto'                   => 'күчерелгән:',
'movetalk'                  => 'Бәйләнешле бәхәс битен күчерү',
'1movedto2'                 => '«[[$1]]» бите «[[$2]]» битенә күчерелде',
'1movedto2_redir'           => '[[$1]] бите [[$2]] битенә юнәлтү өстеннән күчте',
'move-redirect-suppressed'  => 'юнәлтү тыелды',
'movelogpage'               => 'Күчерү көндәлеге',
'movereason'                => 'Сәбәп:',
'revertmove'                => 'кире кайту',
'delete_and_move_reason'    => 'Күчерүне мөмкин итәр өчен бетерелде',
'move-leave-redirect'       => 'Юнәлтүне калдырырга',

# Export
'export'       => 'Битләрне чыгаруы',
'export-addns' => 'Өстәргә',

# Namespace 8 related
'allmessages'     => 'Система хәбәрләре',
'allmessagesname' => 'Исем',

# Thumbnails
'thumbnail-more'  => 'Зурайтырга',
'thumbnail_error' => 'Кечкенә сүрәт төзүе хатасы: $1',

# Import log
'importlogpage' => 'Кертү көндәлеге',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Кулланучы битегез',
'tooltip-pt-mytalk'               => 'Бәхәс битегез',
'tooltip-pt-preferences'          => 'Көйләнмәләрегез',
'tooltip-pt-watchlist'            => 'Сез күзәтелгән төзәтмәле битләр исемлеге',
'tooltip-pt-mycontris'            => 'Сезнең кертеменгезне исемлеге',
'tooltip-pt-login'                => 'Сез хисап язмасы төзи алыр идегез, әмма бу мәҗбүри түгел.',
'tooltip-pt-logout'               => 'Чыгy',
'tooltip-ca-talk'                 => 'Битнең эчтәлеге турында бәхәс',
'tooltip-ca-edit'                 => 'Сез бу бит үзгәртә аласыз. Зинһар, саклаганчы карап алуны кулланыгыз.',
'tooltip-ca-addsection'           => 'Яңа бүлекне башларга',
'tooltip-ca-viewsource'           => 'Бу бит үзгәртүдән якланган. Сез аның чыганак текстын гына карый аласыз.',
'tooltip-ca-history'              => 'Битнең төзәтмәләр исемлеге',
'tooltip-ca-protect'              => 'Бу битне якларга',
'tooltip-ca-delete'               => 'Бу битне бетерергә',
'tooltip-ca-move'                 => 'Бу битне күчерү',
'tooltip-ca-watch'                => 'Бу битне сезнең күзәтү исемлегезгә өстәргә',
'tooltip-ca-unwatch'              => 'Бу битне сезнең күзәтү исемлегездә бетерергә',
'tooltip-search'                  => 'Эзләргә {{SITENAME}}',
'tooltip-search-go'               => 'Нәк шундый исеме белән биткә күчәргә',
'tooltip-search-fulltext'         => 'Бу текст белән битләрне табарга',
'tooltip-p-logo'                  => 'Баш бит',
'tooltip-n-mainpage'              => 'Баш битне кереп чыгу',
'tooltip-n-mainpage-description'  => 'Баш биткә күчү',
'tooltip-n-portal'                => 'Проект турында, сез нәрсә итә аласыз һәм нәрсә кайда була дип турында.',
'tooltip-n-currentevents'         => 'Агымдагы вакыйгалар турында мәгълүматны табарга',
'tooltip-n-recentchanges'         => 'Соңгы үзгәртүләр исемлеге',
'tooltip-n-randompage'            => 'Очраклы битне карау',
'tooltip-n-help'                  => '«{{SITENAME}}» проектының белешмәлек',
'tooltip-t-whatlinkshere'         => 'Бирегә сылтаган барлык битләрнең исемлеге',
'tooltip-t-recentchangeslinked'   => 'Бу биттән сылтаган битләрдә ахыргы үзгәртүләр',
'tooltip-feed-rss'                => 'Бу бит өчен RSS трансляциясе',
'tooltip-feed-atom'               => 'Бу бит өчен Atom трансляциясе',
'tooltip-t-contributions'         => 'Кулланучының кертеме исемлегене карарга',
'tooltip-t-emailuser'             => 'Бу кулланучыга хат җибәрү',
'tooltip-t-upload'                => 'Файлларны йөкләү',
'tooltip-t-specialpages'          => 'Барлык махсус битләр исемлеге',
'tooltip-t-print'                 => 'Бу битнең бастыру версиясе',
'tooltip-t-permalink'             => 'Битнең бу юрамасыга даими сылтама',
'tooltip-ca-nstab-main'           => 'Мәкаләнең эчтәлеге',
'tooltip-ca-nstab-user'           => 'Кулланучының битене карарга',
'tooltip-ca-nstab-special'        => 'Бу махсус бит, сез аны үзгәртү алмыйсыз',
'tooltip-ca-nstab-project'        => 'Проектның битене карарга',
'tooltip-ca-nstab-image'          => 'Сүрәтнең битене карарга',
'tooltip-ca-nstab-template'       => 'Үрнәкне караy',
'tooltip-ca-nstab-help'           => 'Ярдәм битен карау',
'tooltip-ca-nstab-category'       => 'Төркем битен карау',
'tooltip-minoredit'               => 'Бу үзгәртүне кече дип билгелү',
'tooltip-save'                    => 'Үзгәртүләрегезне саклау',
'tooltip-preview'                 => 'Сезнең үзгәртүләрегезнең алдан каравы, саклаудан кадәр моны кулланыгыз әле!',
'tooltip-diff'                    => 'Сезнең үзгәртмәләрегезне күрсәтү.',
'tooltip-compareselectedversions' => 'Бу битнең сайланган ике версиясе арасында аерманы карарга',
'tooltip-watch'                   => 'Бу битне күзәтү исемлегемә өстәргә',
'tooltip-rollback'                => "\"Кире кайтару\" соңгы кулланучының бу биттә ясаган '''барлык''' үзгәртүләрен бетерә.",
'tooltip-undo'                    => 'Бу үзгәртүне алдан карап үткәрмәү. Шулай ук үткәрмәүнең сәбәбен язып була.',

# Math errors
'math_unknown_error' => 'беленмәгән хата',

# Patrolling
'markaspatrolledtext'   => 'Бу мәкаләне тикшерелгән дип тамгаларга',
'markedaspatrolled'     => 'Тикшерелгән дип тамгаланды',
'markedaspatrolledtext' => 'Сайланган версия тикшерелгән дип тамгаланды.',

# Browsing diffs
'previousdiff' => '← Алдагы үзгәртү',
'nextdiff'     => 'Чираттагы үзгәртү →',

# Media information
'file-info-size'       => '($1 × $2 нокта, файлның зурлыгы: $3, MIME тибы: $4)',
'file-nohires'         => '<small>Югары ачыклык белән юрама юк.</small>',
'svg-long-desc'        => '(SVG файлы, шартлы $1 × $2 нокта, файлның зурлыгы: $3)',
'show-big-image'       => 'Тулы ачыклык',
'show-big-image-thumb' => '<small>Алдан карау зурлыгы: $1 × $2 нокта</small>',

# Special:NewFiles
'newimages'        => 'Яңа сүрәтләр җыелмасы',
'newimages-legend' => 'Фильтр',

# Bad image list
'bad_image_list' => 'Киләчәк рәвеш кирәк:

Исемлек кисәкләре генә (* символыннан башланучы юллар) саналырлар.
Юлның беренче сылтамасы куйма өчен тыелган рәсемгә сылтама булырга тиеш.
Шул ук юлның киләчәк сылтамалары чыгармалар, рәсемгә тыелмаган битләре, саналырлар.',

# Metadata
'metadata'          => 'Мета мәгълүматлар',
'metadata-help'     => 'Бу файлда гадәттә санлы камера яки сканер тарафыннан өстәлгән мәгълүмат бар. Әгәр бу файл төзү вакытыннан соң үзгәртелгән булса, аның кайбер параметрлары дөрес булмаска мөмкин.',
'metadata-expand'   => 'Өстәмә мәгълүматларны күрсәтү',
'metadata-collapse' => 'Өстәмә мәгълүматларны яшерү',
'metadata-fields'   => 'Бу исемлеккә кергән метабирелмәләр кырлары рәсем битендә күрсәтелер, калганнары исә килешү буенча яшерелер.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-brightnessvalue' => 'Яктылык',

# External editor support
'edit-externally'      => 'Бу файлны тышкы кушымтаны кулланып үзгәртү',
'edit-externally-help' => '(тулырак мәгълүмат өчен [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] битен карагыз)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Барлык',
'watchlistall2'    => 'барлык',
'namespacesall'    => 'барлык',
'monthsall'        => 'барлык',

# Multipage image navigation
'imgmultipagenext' => 'алдагы бит →',

# Table pager
'table_pager_next' => 'Киләсе бит',

# Auto-summaries
'autoredircomment' => '[[$1]] битенә юнәлтү',
'autosumm-new'     => 'Яңа бит: «$1»',

# Watchlist editing tools
'watchlisttools-view' => 'Соңгы үзгәртүләрне күрсәтү',
'watchlisttools-edit' => 'Күзәтү исемлегене карау һәм үзгәртү',
'watchlisttools-raw'  => 'Текст сыман үзгәртү',

# Special:Version
'version'                  => 'Юрама',
'version-other'            => 'Башкалар',
'version-software-version' => 'Версия',

# Special:FilePath
'filepath-page' => 'Файл:',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Эзләү',

# Special:SpecialPages
'specialpages'            => 'Махсус битләр',
'specialpages-group-spam' => 'Спамга каршы кораллар',

# Special:Tags
'tags-edit' => 'үзгәртү',

# Database error messages
'dberr-problems' => 'Гафу итегез! Сайтта техник кыенлыклар чыкты.',

# HTML forms
'htmlform-submit'              => 'Җибәрү',
'htmlform-reset'               => 'Үзгәртүләрне кире кайтару',
'htmlform-selectorother-other' => 'Башка',

);
