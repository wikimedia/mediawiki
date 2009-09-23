<?php
/** Tatar (Cyrillic) (Татарча/Tatarça (Cyrillic))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Himiq Dzyu
 * @author KhayR
 * @author Rinatus
 * @author Yildiz
 * @author Ерней
 */

$fallback = 'ru';

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = array(
        'mdy time' => 'H:i',
        'mdy date' => 'M j, Y',
        'mdy both' => 'H:i, M j, Y',
        'dmy time' => 'H:i',
        'dmy date' => 'j. M Y',
        'dmy both' => 'j. M Y, H:i',
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
	NS_TALK             => 'Фикер алышу',
	NS_USER             => 'Кулланучы',
	NS_USER_TALK        => 'Кулланучы бәхәсе',
	NS_PROJECT_TALK     => '$1 бәхәсе',
	NS_FILE             => 'Рәсем',
	NS_FILE_TALK        => 'Рәсем бәхәсе',
	NS_MEDIAWIKI        => 'Медиа_Вики',
	NS_MEDIAWIKI_TALK   => 'Медиа_Вики бәхәсе',
	NS_TEMPLATE         => 'Үрнәк',
	NS_TEMPLATE_TALK    => 'Шаблон бәхәсе',
	NS_HELP             => 'Ярдәм',
	NS_HELP_TALK        => 'Ярдәм бәхәсе',
	NS_CATEGORY         => 'Төркем',
	NS_CATEGORY_TALK    => 'Төркем бәхәсе',
);

$namespaceAliases = array(
	'Служебная'                          => NS_SPECIAL,
	'Обсуждение'                         => NS_TALK,
	'Участница'                          => NS_USER,
	'Обсуждение участницы'               => NS_USER_TALK,
	'Участник'                           => NS_USER,
	'Обсуждение_участника'               => NS_USER_TALK,
	'Обсуждение_{{GRAMMAR:genitive|$1}}' => NS_PROJECT_TALK,
	'Изображение'                        => NS_FILE,
	'Обсуждение_изображения'             => NS_FILE_TALK,
	'Файл'                               => NS_FILE,
	'Обсуждение_файла'                   => NS_FILE_TALK,
	'Обсуждение_MediaWiki'               => NS_MEDIAWIKI_TALK,
	'Шаблон'                             => NS_TEMPLATE,
	'Обсуждение_шаблона'                 => NS_TEMPLATE_TALK,
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
	'notoc'                 => array( '0', '__ETYUQ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ETTIQ__', '__ОБЯЗ_ОГЛ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ET__', '__ОГЛ__', '__TOC__' ),
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
	'subst'                 => array( '0', 'TÖPÇEK:', 'ПОДСТ:', 'SUBST:' ),
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
'tog-underline'               => 'Сылтамаларның астына сызарга:',
'tog-highlightbroken'         => 'Төзелмәгән сылтамаларны <a href="" class="new">шушылай</a> (юкса болай - <a href="" class="internal">?</a>) күрсәтергә.',
'tog-justify'                 => 'Текстны киңлек буенча тигезләргә',
'tog-hideminor'               => 'Соңгы үзгәртүләр исемлегендә әһәмиятсезләрен яшерергә',
'tog-hidepatrolled'           => 'Яңа үзгәрешләр исемлегеннән тикшерелгән үзгәрешләрне яшерергә',
'tog-newpageshidepatrolled'   => 'Яңа битләр исемлегеннән тикшерелгән битләрне яшерергә',
'tog-extendwatchlist'         => 'Соңгыларын гына түгел, ә барлык үзгәртүләрне эченә алган, киңәйтелгән күзәтү исемлеге',
'tog-usenewrc'                => 'Яхшыртылган соңгы үзгәртүләр исемлеген кулланырга (JavaScript кирәк)',
'tog-numberheadings'          => 'Атамаларны автомат рәвештә номерларга',
'tog-showtoolbar'             => 'Үзгәртү вакытында коралларның өске панелен күрсәтергә (JavaScript)',
'tog-editondblclick'          => 'Битләргә ике чирттерү белән үзгәртү битен ачарга (JavaScript)',
'tog-editsection'             => 'Һәр бүлектә «үзгәртү» сылтамасын күрсәтергә',
'tog-editsectiononrightclick' => 'Бүлек исеменә тычканның уң чирттермә се белән төрткәч үзгәртү битен ачарга (JavaScript)',
'tog-showtoc'                 => 'Эчтәлекне күрсәтергә (3 тән күбрәк башламлы битләрдә)',
'tog-rememberpassword'        => 'Хисап язмамны бу компьютерда хәтерләргә',
'tog-editwidth'               => 'Үзгәртү урыны күзәтүче тәрәзәсенең тулы киңлегендә',
'tog-watchcreations'          => 'Төзегән битләремне күзәтү исемлегемә өстәргә',
'tog-watchdefault'            => 'Үзгәрткән битләремне күзәтү исемлегемә өстәргә',
'tog-watchmoves'              => 'Исемнәрен үзгәрткән битләремне күзәтү исемлегемә өстәргә',
'tog-watchdeletion'           => 'Бетерергән битләремне күзәтү исемлегемгә өстәү',
'tog-minordefault'            => 'Барлык үзгәртүләрне килешү буенча әһәмиятсез дип билгеләргә',
'tog-previewontop'            => 'Үзгәртү тәрәзәсеннән өстәрәк мәкаләне алдан карау өлкәсен күрсәтергә',
'tog-previewonfirst'          => 'Үзгәртү битенә күчкәндә башта алдан карау битен күрсәтергә',
'tog-nocache'                 => 'Битләр кэшлауны тыярга',
'tog-enotifwatchlistpages'    => 'Күзәтү исемлегемдәге бит үзгәртелү турында электрон почтага хәбәр җибәрергә',
'tog-enotifusertalkpages'     => 'Фикер алышу битем үзгәртелү турында электрон почтага хәбәр җибәрергә',
'tog-enotifminoredits'        => 'Аз әһәмиятле үзгәрешләр турында да электрон почтага хәбәр җибәрергә',
'tog-enotifrevealaddr'        => 'Хәбәрләрдә e-mail адресымны күрсәтергә',
'tog-shownumberswatching'     => 'Битне күзәтү исемлекләренә өстәгән кулланучылар санын күрсәтергә',
'tog-oldsig'                  => 'Кулланылучы имзаны алдан карау:',
'tog-fancysig'                => 'Имзаның шәхси вики-билгеләмәсе (автоматик сылтамасыз)',
'tog-externaleditor'          => 'Тышкы редактор кулланырга (компьютер махсус көйләнгән булу зарур)',
'tog-externaldiff'            => 'Тышкы версия чагыштыру программасын кулланырга (компьютер махсус көйләнгән булу зарур)',
'tog-showjumplinks'           => '«күчәргә» ярдәмче сылтамаларын ялгарга',
'tog-uselivepreview'          => 'Тиз карап алу кулланырга (JavaScript, эксперименталь)',
'tog-forceeditsummary'        => 'Үзгәртүләрне тасвирлау юлы тутырылмаган булса, кисәтергә',
'tog-watchlisthideown'        => 'Күзәтү исемлегеннән  үзгәртүләремне яшерергә',
'tog-watchlisthidebots'       => 'Күзәтү исемлегеннән бот үзгәртүләрен яшерергә',
'tog-watchlisthideminor'      => 'Күзәтү исемлегеннән аз үзгәртүләрне яшерергә',
'tog-watchlisthideliu'        => 'Күзәтү исемлегеннән авторизация үткән кулланучыларның үзгәртүләрен яшерергә',
'tog-watchlisthideanons'      => 'Күзәтү исемлегеннән аноним кулланучыларның үзгәртүләрен яшерергә',
'tog-watchlisthidepatrolled'  => 'Күзәтү исемлегеннән тикшерелгән үзгәрешләрне яшерергә',
'tog-nolangconversion'        => 'Язу системаларының үзгәртүен сүндерү',
'tog-ccmeonemails'            => 'Башка кулланучыларга җибәргән хатларымның копияләрен миңа да җибәрергә',
'tog-diffonly'                => 'Версия чагыштыру астында бит эчтәлеген күрсәтмәскә',
'tog-showhiddencats'          => 'Яшерен төркемнәрне күрсәтергә',
'tog-norollbackdiff'          => 'Кире кайтару ясагач версияләр аермасын күрсәтмәскә',

'underline-always'  => 'Һәрвакыт',
'underline-never'   => 'Бервакытта да',
'underline-default' => 'Браузер көйләнмәләрен кулланырга',

# Font style option in Special:Preferences
'editfont-style'     => 'Үзгәртү өлкәсендәге шрифт тибы:',
'editfont-default'   => 'Шрифт браузер көйләнмәләреннән',
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
'january'       => 'Гыйнвар',
'february'      => 'Февраль',
'march'         => 'Март',
'april'         => 'Апрель',
'may_long'      => 'Май',
'june'          => 'Июнь',
'july'          => 'Июль',
'august'        => 'Август',
'september'     => 'Сентябрь',
'october'       => 'Октябрь',
'november'      => 'Ноябрь',
'december'      => 'Декабрь',
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

'mainpagetext'      => '<big>«MediaWiki» уңышлы куелды.</big>',
'mainpagedocfooter' => "Бу вики турында мәгълүматны [http://meta.wikimedia.org/wiki/Ярдәм:Эчтәлек биредә] табып була.

== Кайбер файдалы ресурслар ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Көйләнмәләр исемлеге (инг.)];
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki турында еш бирелгән сораулар һәм җаваплар (инг.)];
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki'ның яңа версияләре турында хәбәрләр яздырып алу].",

'about'         => 'Тасвирлама',
'article'       => 'Эчтәлек бите',
'newwindow'     => '(яңа тәрәзәдә ачыла)',
'cancel'        => 'Баш тартырга',
'moredotdotdot' => 'Дәвамы…',
'mypage'        => 'Шәхси битем',
'mytalk'        => 'Фикер алышу битем',
'anontalk'      => 'Бу IP-адрес өчен фикер алышу',
'navigation'    => 'Күчү',
'and'           => '&#32;һәм',

# Cologne Blue skin
'qbfind'         => 'Эзләү',
'qbbrowse'       => 'Карарга',
'qbedit'         => 'Үзгәртергә',
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
'vector-namespace-talk'      => 'Фикер алышу',
'vector-namespace-template'  => 'Үрнәк',
'vector-namespace-user'      => 'Кулланучы бите',
'vector-view-create'         => 'Төзү',
'vector-view-edit'           => 'Үзгәртергә',
'vector-view-history'        => 'Тарихын карау',
'vector-view-view'           => 'Уку',
'vector-view-viewsource'     => 'Чыганакны карарга',
'actions'                    => 'Хәрәкәт',
'namespaces'                 => 'Исемнәр мәйданы',
'variants'                   => 'Төрләр',

# Metadata in edit box
'metadata_help' => 'Мета-мәгълүматлар:',

'errorpagetitle'    => 'Хата',
'returnto'          => '$1 битенә кайту.',
'tagline'           => '{{SITENAME}} проектыннан',
'help'              => 'Ярдәм',
'search'            => 'Эзләү',
'searchbutton'      => 'Эзләргә',
'go'                => 'Күчәргә',
'searcharticle'     => 'Күчәргә',
'history'           => 'Битнең тарихы',
'history_short'     => 'Тарихы',
'updatedmarker'     => 'соңгы керүемнән соң яңартылган',
'info_short'        => 'Мәгълүмат',
'printableversion'  => 'Бастыру версиясе',
'permalink'         => 'Даими сылтама',
'print'             => 'Бастыру',
'edit'              => 'Үзгәртү',
'create'            => 'Төзү',
'editthispage'      => 'Бу битне үзгәртергә',
'create-this-page'  => 'Бу битне төзергә',
'delete'            => 'Сыздыру',
'deletethispage'    => 'Бу битне сыздырырга',
'undelete_short'    => '$1 {{PLURAL:$1|үзгәртмәне}} торгызырга',
'protect'           => 'Якларга',
'protect_change'    => 'үзгәртергә',
'protectthispage'   => 'Бу битне якларга',
'unprotect'         => 'Яклауны бетерергә',
'unprotectthispage' => 'Бу битнең яклавын бетерергә',
'newpage'           => 'Яңа бит',
'talkpage'          => 'Бит турында фикер алышырга',
'talkpagelinktext'  => 'Фикер алышу',
'specialpage'       => 'Махсус бит',
'personaltools'     => 'Шәхси кораллар',
'postcomment'       => 'Яңа бүлек',
'articlepage'       => 'Мәкаләне карарга',
'talk'              => 'Фикер алышу',
'views'             => 'Караулар',
'toolbox'           => 'Кораллар',
'userpage'          => 'Кулланучы битен карарга',
'projectpage'       => 'Проект битен карарга',
'imagepage'         => 'Файл битене карарга',
'mediawikipage'     => 'Хәбәр битен карарга',
'templatepage'      => 'Үрнәк битен карарга',
'viewhelppage'      => 'Ярдәм битен карарга',
'categorypage'      => 'Төркем битен карарга',
'viewtalkpage'      => 'Фикер алышу битен карарга',
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
'youhavenewmessages'      => 'Сездә $1 ($2) бар.',
'newmessageslink'         => 'яңа хәбәрләр',
'newmessagesdifflink'     => 'соңгы үзгәртү',
'youhavenewmessagesmulti' => 'Сезгә монда яңа хәбәрләр бар: $1',
'editsection'             => 'үзгәртергә',
'editold'                 => 'үзгәртергә',
'viewsourceold'           => 'башлангыч кодны карарга',
'editlink'                => 'үзгәртергә',
'viewsourcelink'          => 'башлангыч кодны карарга',
'editsectionhint'         => '$1 бүлеген үзгәртергә',
'toc'                     => 'Эчтәлек',
'showtoc'                 => 'күрсәтергә',
'hidetoc'                 => 'яшерергә',
'thisisdeleted'           => 'Карау/торгызу: $1',
'viewdeleted'             => '$1 караргамы?',
'restorelink'             => '{{PLURAL:$1|$1 бетерелгән төзәтмә}}',
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
'nstab-template'  => 'Өлге',
'nstab-help'      => 'Ярдәм',
'nstab-category'  => 'Төркем',

# Main script and global functions
'nosuchaction'      => 'Мондый гамәл юк',
'nosuchactiontext'  => 'URLда күрсәтелгән гамәл хаталы.
Сез URLны хаталы җыйган яисә хаталы сылтамадан күчкән булырга мөмкинсез.
Бу шулай ук {{SITENAME}} проектындагы хата сәбәпле дә булырга мөмкин.',
'nosuchspecialpage' => 'Мондый махсус бит юк',
'nospecialpagetext' => "<big>'''Сез сорый торган махсус бит юк.'''</big>

Махсус битләр исемлеген карагыз: [[Special:SpecialPages|{{int:specialpages}}]].",

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
'cannotdelete'         => 'Бу битне яки файлны сыздырып булмый. Ул инде сыздырылган булырга мөмкин.',
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
'remembermypassword'         => 'Теркәү исемемне бу компьютерда онытмаска',
'yourdomainname'             => 'Сезнең доменыгыз:',
'externaldberror'            => 'Тышкы мәгълүмат базасы ярдәмендә аутентификация үткәндә хата чыкты, яисә тышкы хисап язмагызга үзгәрешләр кертү хокукыгыз юк.',
'login'                      => 'Керергә',
'nav-login-createaccount'    => 'Керергә / хисап язмасы төзергә',
'loginprompt'                => '{{SITENAME}} проектына керү өчен «cookies» рөхсәт ителгән булырга тиеш.',
'userlogin'                  => 'Керергә',
'logout'                     => 'Чыгарга',
'userlogout'                 => 'Чыгарга',
'notloggedin'                => 'Сез хисап язмагызга кермәгәнсез',
'nologin'                    => 'Кулланучы исемең юкмы? $1',
'nologinlink'                => 'Хисап язмасы төзегез',
'createaccount'              => 'Яңа кулланучы теркәргә',
'gotaccount'                 => 'Сез инде теркәлдегезме? $1.',
'gotaccountlink'             => 'Керергә',
'createaccountmail'          => 'электрон почта белән',
'badretype'                  => 'Кертелгән серсүзләр бер үк түгел.',
'userexists'                 => 'Кертелгән исем кулланыла.
Зинһар, башка исем сайлагыз.',
'loginerror'                 => 'Керү хатасы',
'nocookiesnew'               => 'Катнашучы теркәлгән, ләкин үз хисап язмасы белән кермәгән. {{SITENAME}} катнашучыны тану өчен «cookies» куллана. Сездә «cookies» тыелган. Зинһар, башта аларны рөхсәт итегез, аннан исем һәм серсүз белән керегез.',
'nocookieslogin'             => '{{SITENAME}} катнашучыны тану өчен «cookies» куллана. Сез аларны сүндергәнсез. Зинһар, аларны кабызып, яңадан керегез.',
'noname'                     => 'Сез теркәү исемегезне күрсәтергә тиешсез.',
'loginsuccesstitle'          => 'Керү уңышлы үтте',
'loginsuccess'               => "'''Сез {{SITENAME}} проектына $1 исеме белән кердегез.'''",
'nosuchuser'                 => '$1 исемле кулланучы юк.
Кулланучы исеменең дөреслеге регистрга бәйле.
Язылышыгызны тикшерегез яки [[Special:UserLogin/signup|яңа хисап язмасы төзегез]].',
'nosuchusershort'            => '<nowiki>$1</nowiki> исемле кулланучы юк. Язылышыгызны тикшерегез.',
'nouserspecified'            => 'Сез теркәү исмегезне күрсәтергә тиешсез.',
'wrongpassword'              => 'Язылган серсүз дөрес түгел. Тагын бер тапкыр сынагыз.',
'wrongpasswordempty'         => 'Серсүз юлы буш булырга тиеш түгел.',
'passwordtooshort'           => 'Сезсүз $1 {{PLURAL:$1|символдан}} торырга тиеш.',
'password-name-match'        => 'Кертелгән серсүз кулланучы исеменнән аерылырга тиеш.',
'mailmypassword'             => 'Электрон почтага яңа серсүз җибәрергә',
'passwordremindertitle'      => '{{SITENAME}} кулланучысына вакытлы серсүз тапшыру',
'passwordremindertext'       => 'Кемдер (бәлки, сездер, IP-адресы: $1) {{SITENAME}} ($4) өчен яңа серсүз соратты. $2 өчен яңа серсүз: $3. Әгәр бу сез булсагыз, системага керегез һәм серсүзне алмаштырыгыз. Яңа серсүз $5 {{PLURAL:$5|көн}} гамәлдә булачак.

Әгәр сез серсүзне алмаштыруны сорамаган булсагыз яки, оныткан очракта, исегезгә төшергән булсагыз, бу хәбәргә игътибар бирмичә, иске серсүзегезне куллануны дәвам итегез.',
'noemail'                    => '$1 исемле кулланучы өчен электрон почта адресы язылмаган.',
'passwordsent'               => 'Яңа серсүз $1 исемле катнашучының электрон почта адресына җибәрелде.

Зинһар, серсүзне алгач, системага яңадан керегез.',
'blocked-mailpassword'       => 'Сезнең IP-адресыгыз белән мәкаләләр үзгәртеп һәм серсүзне яңартып булмый.',
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
'createaccount-title'        => '{{SITENAME}}: хисап язмасы төзү',
'createaccount-text'         => 'Кемдер, электрон почта адресыгызны күрсәтеп, {{SITENAME}} ($4) проектында «$3» серсүзе белән «$2» исемле хисап язмасы төзеде. Сез керергә һәм серсүзегезне үзгәртергә тиеш.

Хисап язмасы төзү хата булса, бу хатны онытыгыз.',
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
'minoredit'                        => 'Әһәмиятсез үзгәртү',
'watchthis'                        => 'Бу битне күзәтергә',
'savearticle'                      => 'Битне сакларга',
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
'blockedtext'                      => "<big>'''Сезнең хисап язмагыз яки IP-адресыгыз бикләнгән.'''</big>

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
'nosuchsectiontitle'               => 'Мондый бүлек юк',
'nosuchsectiontext'                => '$1 исемле асбит юк. Сезнең үзгәртүләрне саклап булмый.',
'loginreqtitle'                    => 'Керү кирәк',
'loginreqlink'                     => 'керергә',
'loginreqpagetext'                 => 'Сез башка битләр карау өчен $1 тиеш.',
'accmailtitle'                     => 'Серсүз җибәрелде.',
'accmailtext'                      => "[[User talk:$1|$1]] кулланучысы өчен төзелгән серсүз $2 адресына җибәрелде.

Сайтка кергәч сез ''[[Special:ChangePassword|серсүзегезне үзгәртә аласыз]]''.",
'newarticle'                       => '(Яңа)',
'newarticletext'                   => "Сез әлегә язылмаган биткә кердегез.
Яңа бит ясау өчен астагы тәрәзәдә мәкалә текстын җыегыз ([[{{MediaWiki:Helppage}}|ярдәм битен]] карый аласыз).
Әгәр сез бу биткә ялгышлык белән эләккән булсагыз, браузерыгызның '''артка''' төймәсенә басыгыз.",
'anontalkpagetext'                 => "----''Бу фикер алышу бите системада теркәлмәгән яисә үз исеме белән кермәгән кулланучыныкы.
Аны тану өчен IP-адрес файдаланыла.
Әгәр сез аноним кулланучы һәм сезгә юлланмаган хәбәрләр алдым дип саныйсыз икән (бер IP-адрес күп кулланучы өчен булырга мөмкин), башка мондый аңлашылмаучанлыклар килеп чыкмасын өчен [[Special:UserLogin|системага керегез]] яисә [[Special:UserLogin/signup|теркәлегез]].''",
'noarticletext'                    => "Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]], 
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} журналлардагы язмаларны] таба 
яки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} шушындый исемле яңа бит төзи]''' аласыз.",
'noarticletext-nopermission'       => 'Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]], 
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} журналлардагы язмаларны] таба аласыз.',
'userpage-userdoesnotexist'        => '«$1» исемле хисап язмасы юк. Сез чынлап та бу битне ясарга яисә үзгәртергә телисезме?',
'userpage-userdoesnotexist-view'   => '"$1" исемле хисап язмасы юк.',
'clearyourcache'                   => "'''Искәрмә:''' Битне саклаганнан соң төзәтмәләр күренсен өчен браузерыгызның кэшын чистартыгыз.
Моны '''Mozilla / Firefox''': ''Ctrl+Shift+R'', '''Safari''': ''Cmd+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Konqueror''': ''F5'', '''Opera''': ''Tools→Preferences'' аша эшләп була.",
'usercssyoucanpreview'             => "'''Ярдәм:''' \"Алдан карау\" төймәсенә басып, яңа CSS-файлны тикшереп була.",
'userjsyoucanpreview'              => "'''Ярдәм:''' \"Алдан карау\" төймәсенә басып, яңа JS-файлны тикшереп була.",
'usercsspreview'                   => "'''Бу бары тик CSS-файлны алдан карау гына, ул әле сакланмаган!'''",
'userjspreview'                    => "'''Бу бары тик JavaScript файлын алдан карау гына, ул әле сакланмаган!'''",
'userinvalidcssjstitle'            => "'''Игътибар:''' \"\$1\" бизәү темасы табылмады. Кулланучының .css һәм .js битләре исемнәре бары тик кечкенә (юл) хәрефләрдән генә торырга тиеш икәнен онытмагыз. Мисалга: {{ns:user}}:Foo/monobook.css, ә {{ns:user}}:Foo/Monobook.css түгел!",
'updated'                          => '(Яңартылды)',
'note'                             => "'''Искәрмә:'''",
'previewnote'                      => "'''Бу фәкать алдан карау гына, төзәтмәләр әле сакланмаган!'''",
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
'editing'                          => 'Үзгәртү: $1',
'editingsection'                   => '$1 үзгәртүе (бүлек)',
'editingcomment'                   => '$1 үзгәртүе (яңа бүлек)',
'editconflict'                     => 'Үзгәртү конфликты: $1',
'explainconflict'                  => 'Сез бу битне төзәткән вакытта кемдер аңа үзгәрешләр кертте. Өстәге тәрәзәдә Сез хәзерге текстны күрәсез. Астагы тәрәзәдә Сезнең вариант урнашкан. Эшләгән төзәтмәләрегезне астагы тәрәзәдән өстәгенә күчерегез. «Битне сакларга» төймәсенә баскач өстәге битнең тексты сакланаячак.',
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
'protectedpagewarning'             => "'''Кисәтү: сез бу битне үзгәртә алмыйсыз, бу хокукка администраторлар гына ия.'''",
'semiprotectedpagewarning'         => "'''Кисәтү:''' бу бит якланган. Аны теркәлгән кулланучылар гына үзгәртә ала.",
'cascadeprotectedwarning'          => "'''Кисәтү:''' Бу битне администраторлар гына үзгәртә ала. Сәбәбе: ул {{PLURAL:$1|каскадлы яклау исемлегенә кертелгән}}:",
'titleprotectedwarning'            => "'''Кисәтү: Мондый исемле бит якланган, аны үзгәртү өчен [[Special:ListGroupRights|тиешле хокукка]] ия булу зарур.'''",
'templatesused'                    => 'Бу биттә кулланылган өлгеләр:',
'templatesusedpreview'             => 'Алдан каралучы биттә кулланылган өлгеләр:',
'templatesusedsection'             => 'Бу бүлектә кулланылган өлгеләр:',
'template-protected'               => '(якланган)',
'template-semiprotected'           => '(өлешчә якланган)',
'hiddencategories'                 => 'Бу бит $1 {{PLURAL:$1|яшерен төркемгә}} керә:',
'nocreatetitle'                    => 'Битләр төзү чикләнгән',
'nocreatetext'                     => '{{SITENAME}}: сайтта яңа битләр төзү чикләнгән.
Сез артка кайтып, төзелгән битне үзгәртә аласыз. [[Special:UserLogin|Керергә яисә теркәлергә]] тәгъдим ителә.',
'nocreate-loggedin'                => 'Сезгә яңа битләр төзү хокукы бирелмәгән.',
'permissionserrors'                => 'Керү хокукы хаталары',
'permissionserrorstext'            => 'Түбәндәге {{PLURAL:$1|сәбәп|сәбәпләр}} аркасында сез бу гамәлне башкара алмыйсыз:',
'permissionserrorstext-withaction' => '$2 гамәлен башкара алмыйсыз. {{PLURAL:$1|Сәбәбе|Сәбәпләре}}:',
'recreate-moveddeleted-warn'       => "'''Игътибар: Сез сыздырылган бит урнына яңа бит төземәкче буласыз.'''

Бу сезгә чыннан да кирәкме?
Түбәндә битнең сыздыру һәм күчерү журналы китерелә:",
'moveddeleted-notice'              => 'Бу бит сыздырылган иде.
Түбәндә сыздырылу һәм күчерелү журналы китерелә.',
'log-fulllog'                      => 'Журналны тулысынча карарга',
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
'post-expand-template-inclusion-warning'  => "'''Игътибар:''' кулланучы өлгеләре артык зур.
Кайберләре кабызылмаячак.",
'post-expand-template-inclusion-category' => 'Рөхсәт ителгән күләмнән артык булган өлгеле битләр',

# Account creation failure
'cantcreateaccounttitle' => 'Хисап язмасыны төзергә мөмкинлек юк',

# History pages
'viewpagelogs'           => 'Бу бит өчен журналлар карарга',
'currentrev'             => 'Хәзерге версия',
'revisionasof'           => 'Версия $1',
'revision-info'          => 'Версия: $1; $2',
'previousrevision'       => '← Алдагы төзәтмәләр',
'nextrevision'           => 'Чираттагы төзәтмәләр →',
'currentrevisionlink'    => 'Хәзерге версия',
'cur'                    => 'хәзерге',
'next'                   => 'киләсе',
'last'                   => 'бая.',
'page_first'             => 'беренче',
'page_last'              => 'соңгы',
'history-fieldset-title' => 'Тарихын карарга',
'histfirst'              => 'Элеккеге',
'histlast'               => 'Соңгы',

# Revision deletion
'rev-deleted-comment' => '(искәрмә бетергән)',
'rev-deleted-user'    => '(авторның исеме бетерергән)',
'rev-deleted-event'   => '(язма бетерергән)',
'rev-delundel'        => 'күрсәтергә/яшерергә',
'revdel-restore'      => 'күренүчәнлекне үзгәртергә',
'pagehist'            => 'битнең тарихы',
'revdelete-uname'     => 'кулланучы исеме',

# Merge log
'revertmerge' => 'Бүләргә',

# Diffs
'history-title'           => '$1 — төзәтү тарихы',
'difference'              => '(Төзәтмәләр арасында аермалар)',
'lineno'                  => '$1 юл:',
'compareselectedversions' => 'Сайланган юрамаларны чагыштырырга',
'editundo'                => 'үткәрмәү',
'diff-span'               => "'''span'''",
'diff-a'                  => "'''сылтама'''",
'diff-i'                  => "'''авышлы'''",
'diff-b'                  => "'''калын язылыш'''",

# Search results
'searchresults'             => 'Эзләү нәтиҗәләре',
'searchresults-title'       => '«$1» өчен эзләү нәтиҗәләре',
'searchsubtitle'            => '«[[:$1]]» өчен эзләү ([[Special:Prefixindex/$1|«$1» дан башлый барлык битләр]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» га сылтый барлык битләр]])',
'noexactmatch'              => "'''«$1» атлы битне әле юк.'''
Аны [[:$1|төзергә]] мөмкин.",
'notitlematches'            => 'Битнең исемнәрендә туры килүләр юк',
'prevn'                     => 'алдагы {{PLURAL:$1|$1}}',
'nextn'                     => 'чираттагы {{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3) карарга',
'searchhelp-url'            => 'Help:Эчтәлек',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 сүз}})',
'search-redirect'           => '($1 җибәрүлеге)',
'search-section'            => '($1 бүлеге)',
'search-interwiki-caption'  => 'Тугандаш проектлар',
'search-interwiki-default'  => '$1 нәтиҗә:',
'search-mwsuggest-enabled'  => 'киңәшләр белән',
'search-mwsuggest-disabled' => 'киңәшсез',
'searchall'                 => 'барлык',
'powersearch'               => 'Өстәмә эзләү',
'powersearch-legend'        => 'Өстәмә эзләү',
'powersearch-redir'         => 'Җибәрүлекләрне чыгарырга',

# Quickbar
'qbsettings'      => 'Күчешләр аслыгы',
'qbsettings-none' => 'Күрсәтмәскә',

# Preferences page
'preferences'               => 'Көйләнмәләр',
'mypreferences'             => 'Көйләнмәләрем',
'prefs-edits'               => 'Үзгәртүләр исәбе:',
'prefsnologin'              => 'Кермәгәнсез',
'prefsnologintext'          => 'Кулланучы көйләнмәләрене үзгәртү өчен, сез [[Special:UserLogin|керергә]] тиешсез.',
'changepassword'            => 'Серсүзне алыштырырга',
'prefs-skin'                => 'Күренеш',
'skin-preview'              => 'Алдан карау',
'prefs-math'                => 'Формулалар',
'prefs-datetime'            => 'Дата һәм вакыт',
'prefs-personal'            => 'Шәхси мәгълүматлар',
'prefs-rc'                  => 'Баягы төзәтмәләр',
'prefs-watchlist'           => 'Күзәтү исемлеге',
'prefs-watchlist-days'      => 'Күзәтү исемлегендә ничә көн буена үзгәртүләрне күрсәтергә:',
'prefs-watchlist-edits'     => 'Яхшыртырган исемлегендә төзәтмәләрнең иң югары исәбе:',
'prefs-misc'                => 'Башка көйләнмәләр',
'saveprefs'                 => 'Саклау',
'resetprefs'                => 'Сакланмаган төзәтмәләрне бетерү',
'prefs-editing'             => 'Үзгәртү',
'rows'                      => 'Юллар:',
'columns'                   => 'Баганалар:',
'servertime'                => 'Серверның вакыты',
'default'                   => 'килешү буенча',
'prefs-files'               => 'Файллар',
'youremail'                 => 'Электрон почта:',
'username'                  => 'Теркәү исеме:',
'uid'                       => 'Кулланучының идентификаторы:',
'yourrealname'              => 'Чын исем:',
'yourlanguage'              => 'Тел:',
'yournick'                  => 'Имза өчен тахалус:',
'badsig'                    => 'Имза дөрес түгел. HTML-теглар тикшерегез.',
'badsiglength'              => 'Имза өчен тахалус бигрәк озын.
Ул $1 {{PLURAL:$1|хәрефтән}} күбрәк түгел булырга тиеш.',
'email'                     => 'Электрон почта',
'prefs-help-realname'       => 'Чын исемегез (кирәкми): аны күрсәтсәгез, ул битне үзгәртүче күрсәтү өчен файдалаячак.',
'prefs-help-email-required' => 'Электрон почта адресы кирәк.',

# User rights
'userrights-groupsmember' => 'Әгъза:',

# Groups
'group-bot'        => 'Ботлар',
'group-sysop'      => 'Идарәчеләр',
'group-bureaucrat' => 'Бюрократлар',
'group-suppress'   => 'Тикшерүчеләр',
'group-all'        => '(барлык)',

'group-autoconfirmed-member' => 'Авторасланган кулланучы',
'group-bot-member'           => 'Бот',
'group-sysop-member'         => 'Идарәче',
'group-bureaucrat-member'    => 'Бюрократ',
'group-suppress-member'      => 'Тикшерүче',

'grouppage-autoconfirmed' => '{{ns:project}}:Авторасланган кулланучылар',
'grouppage-bot'           => '{{ns:project}}:Ботлар',
'grouppage-sysop'         => '{{ns:project}}:Идарәчеләр',
'grouppage-bureaucrat'    => '{{ns:project}}:Бюрократлар',
'grouppage-suppress'      => '{{ns:project}}:Тикшерүчеләр',

# User rights log
'rightslog'  => 'Кулланучының хокуклары журналы',
'rightsnone' => '(юк)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'бу битне узгәртергә',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|төзәтмә|төзәтмә}}',
'recentchanges'                     => 'Соңгы төзәтмәләр',
'rcnote'                            => "Соңгы '''$1''' үзгәртмә '''$2''' көндә, сәгатьтә $5 $4.",
'rcnotefrom'                        => "Астарак '''$2''' башлап ('''$1''' кадәр) төзәтмәләр күрсәтелгән.",
'rclistfrom'                        => '$1 башлап яңа төзәтмәләрне күрсәтергә',
'rcshowhideminor'                   => '$1 әһәмиятсез үзгәртүләр',
'rcshowhidebots'                    => '$1 бот',
'rcshowhideliu'                     => '$1 кергән кулланучы',
'rcshowhideanons'                   => '$1 кермәгән кулланучы',
'rcshowhidepatr'                    => '$1 тикшерергән үзгәртү',
'rcshowhidemine'                    => '$1 минем үзгәртү',
'rclinks'                           => 'Соңгы $2 көн эчендә соңгы $1 төзәтмәне күрсәтергә<br />$3',
'diff'                              => 'аерма.',
'hist'                              => 'тарих',
'hide'                              => 'Яшерергә',
'show'                              => 'Күрсәтергә',
'minoreditletter'                   => 'ә',
'newpageletter'                     => 'Я',
'boteditletter'                     => 'б',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|күзәтеп тора кулланучы}}]',
'rc_categories_any'                 => 'Һәрбер',

# Recent changes linked
'recentchangeslinked'          => 'Бәйләнешле төзәтмәләр',
'recentchangeslinked-feed'     => 'Бәйләнешле төзәтмәләр',
'recentchangeslinked-toolbox'  => 'Бәйләнешле төзәтмәләр',
'recentchangeslinked-title'    => '"$1" битенә бәйләнешле төзәтмәләр',
'recentchangeslinked-noresult' => 'Күрсәтелгән вакытта сылташкан битләрнең үзгәртелмәләре юк иде.',
'recentchangeslinked-summary'  => "Бу күрсәтелгән бит белән сылталган (йә күрсәтелгән төркемгә керткән) битләрнең үзгәртелмәләре исемлеге.
[[Special:Watchlist|Күзәтү исемлегегезгә]] керә торган битләр '''калын'''.",
'recentchangeslinked-page'     => 'Битнең исеме:',

# Upload
'upload'        => 'Файлны йөкләргә',
'uploadbtn'     => 'Файлны йөкләргә',
'uploadlogpage' => 'Йөкләү журналы',
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
'filehist-current'          => 'агымдагы',
'filehist-datetime'         => 'Дата/вакыт',
'filehist-user'             => 'Кулланучы',
'filehist-dimensions'       => 'Зурлык',
'filehist-filesize'         => 'Файлның зурлыгы',
'filehist-comment'          => 'Искәрмә',
'imagelinks'                => 'Файлга сылтамалар',
'linkstoimage'              => 'Бу файлга киләчәк {{PLURAL:$1|бит|$1 бит}} сылтый:',
'nolinkstoimage'            => 'Бу файлга сылтаган битләр юк.',
'uploadnewversion-linktext' => 'Бу файлның яңа версиясене йөкләргә',

# MIME search
'mimesearch' => 'MIME эзләү',

# List redirects
'listredirects' => 'Җибәрүлек исемлеге',

# Unused templates
'unusedtemplates' => 'Кулланмаган өлгеләр',

# Random page
'randompage' => 'Очраклы бит',

# Random redirect
'randomredirect' => 'Очраклы биткә күчү',

# Statistics
'statistics' => 'Статистика',

'disambiguations' => 'Күп мәгънәле сүзләр турында битләр',

'doubleredirects' => 'Икеләтә җибәрүлекләр',

'brokenredirects' => 'Бәйләнешсез җибәрүлек',

'withoutinterwiki'        => 'Телләрара сылтамасыз битләр',
'withoutinterwiki-submit' => 'Күрсәтергә',

'fewestrevisions' => 'Аз үзгәртүләр белән битләр',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт}}',
'nlinks'                  => '$1 {{PLURAL:$1|сылтама}}',
'nmembers'                => '$1 {{PLURAL:$1|әгъза}}',
'lonelypages'             => 'Үксез битләр',
'uncategorizedpages'      => 'Төркемләнмәгән битләр',
'uncategorizedcategories' => 'Төркемләнмәгән төркемнәр',
'uncategorizedimages'     => 'Төркемләнмәгән сүрәтләр',
'uncategorizedtemplates'  => 'Төркемләнмәгән өлгеләр',
'unusedcategories'        => 'Кулланмаган төркемнәр',
'unusedimages'            => 'Кулланмаган сүрәтләр',
'wantedcategories'        => 'Зарур төркемнәр',
'wantedpages'             => 'Зарур битләр',
'mostlinked'              => 'Күп үзенә сылтамалы битләр',
'mostlinkedcategories'    => 'Күп үзенә сылтамалы төркемнәр',
'mostlinkedtemplates'     => 'Иң кулланган өлгеләр',
'mostcategories'          => 'Күп төркемләргә кертелгән битләр',
'mostimages'              => 'Иң кулланган сүрәтләр',
'mostrevisions'           => 'Күп үзгәртүләр белән битләр',
'prefixindex'             => 'Барлык алкушымча белән битләр',
'shortpages'              => 'Кыска мәкаләләр',
'longpages'               => 'Озын битләр',
'deadendpages'            => 'Тупик битләре',
'protectedpages'          => 'Якланган битләр',
'listusers'               => 'Кулланучылар исемлеге',
'newpages'                => 'Яңа битләр',
'ancientpages'            => 'Баягы төзәтмәләр белән битләр',
'move'                    => 'Күчерергә',
'movethispage'            => 'Бу битне күчерергә',

# Book sources
'booksources'               => 'Китап чыганаклары',
'booksources-search-legend' => 'Китап чыганакларыны эзләү',
'booksources-go'            => 'Башкару',

# Special:Log
'specialloguserlabel'  => 'Кулланучы:',
'speciallogtitlelabel' => 'Башлам:',
'log'                  => 'Журналлар',
'all-logs-page'        => 'Барлык журналлар',

# Special:AllPages
'allpages'       => 'Барлык битләр',
'alphaindexline' => '$1 дан $2 гача',
'nextpage'       => 'Чираттагы бит ($1)',
'prevpage'       => 'Алдагы бит ($1)',
'allpagesfrom'   => 'Моңа башланучы битләрне чыгарырга:',
'allpagesto'     => 'Монда чыгаруны туктатырга:',
'allarticles'    => 'Барлык мәкаләләр',
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
'linksearch-ok' => 'Эзләргә',

# Special:ListUsers
'listusers-submit'   => 'Күрсәтергә',
'listusers-noresult' => 'Кулланучыларны табылмады.',

# Special:ActiveUsers
'activeusers-noresult' => 'Катнашучылар табылмады.',

# Special:Log/newusers
'newuserlog-create-entry' => 'Яңа катнашучы',

# Special:ListGroupRights
'listgrouprights-members' => '(группа исемлеге)',

# E-mail user
'emailuser'       => 'Бу кулланучыга хат',
'emailpage'       => 'Кулланучыга хат җибәрергә',
'defemailsubject' => '{{SITENAME}}: хат',
'noemailtitle'    => 'Электрон почта адресы юк',
'emailfrom'       => 'Кемдән',
'emailto'         => 'Кемгә',
'emailsubject'    => 'Тема',
'emailmessage'    => 'Хәбәр',
'emailsend'       => 'Җибәрергә',
'emailccme'       => 'Миңа хәбәрнең күчермәсене җибәрергә.',
'emailccsubject'  => '$1 өчен хәбәрегезнең күчермәсе: $2',
'emailsent'       => 'Хат җибәрелгән',

# Watchlist
'watchlist'        => 'Күзәтү исемлегем',
'mywatchlist'      => 'Күзәтү исемлегем',
'watchlistfor'     => "('''$1''' кулланучы өчен)",
'addedwatch'       => 'Күзәтү исемлегенә өстәгән',
'removedwatch'     => 'Күзәтү исемлегеннән бетерелгән',
'removedwatchtext' => '«[[:$1]]» бите [[Special:Watchlist|сезнең күзәтү исемлегездән]] бетерергән.',
'watch'            => 'Күзәтергә',
'watchthispage'    => 'Бу битне күзәтергә',
'unwatch'          => 'Күзәтмәскә',
'wlshowlast'       => 'Баягы $1 сәгать $2 көн эчендә яки $3ны күрсәтергә',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Күзәтү исемлегемә өстәүе…',
'unwatching' => 'Күзәтү исемлегемнән чыгаруы…',

'enotif_newpagetext'           => 'Бу яңа бит.',
'enotif_impersonal_salutation' => '{{SITENAME}} кулланучы',
'changed'                      => 'үзгәртергән',
'created'                      => 'төзергән',

# Delete
'deletepage'            => 'Битне бетерергә',
'confirm'               => 'Расларга',
'excontent'             => 'эчтәлек: «$1»',
'exblank'               => 'бит буш иде',
'delete-confirm'        => '«$1» бетерүе',
'delete-legend'         => 'Бетерү',
'historywarning'        => 'Кисәтү: сез бетерергә теләгән биттә үзгәртү тарихы бар:',
'actioncomplete'        => 'Гамәл башкарган',
'deletedtext'           => '«<nowiki>$1</nowiki>» бетерергән инде.<br />
Соңгы бетерүләр карау өчен, $2 кара.',
'deletedarticle'        => '«[[$1]]» бетерергән',
'dellogpage'            => 'Бетерү исемлеге',
'deletionlog'           => 'бетерү журналы',
'deletecomment'         => 'Бетерү сәбәбе:',
'deleteotherreason'     => 'Башка/өстәмә сәбәп:',
'deletereasonotherlist' => 'Башка сәбәп',

# Rollback
'rollbacklink' => 'кире кайтару',

# Protect
'protectlogpage'              => 'Яклану журналы',
'protectedarticle'            => '«[[$1]]» якланган',
'unprotectedarticle'          => '«[[$1]]» инде якланмаган',
'prot_1movedto2'              => '«[[$1]]» бите «[[$2]]» биткә күчерергән',
'protectcomment'              => 'Искәрмә:',
'protectexpiry'               => 'Бетә:',
'protect-unchain'             => 'Битнең күчерү рөхсәте ачарга',
'protect-text'                => "Биредә сез '''<nowiki>$1</nowiki>''' бите өчен яклау дәрәҗәсене карый һәм үзгәрә аласыз.",
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
'undelete'                  => 'Бетерергән битләрне карарга',
'undeletepage'              => 'Бетерергән битләрне карау һәм торгызу',
'viewdeletedpage'           => 'Бетерергән битләрне карарга',
'undeletebtn'               => 'Торгызырга',
'undeletelink'              => 'карарга/торгызырга',
'undeleteviewlink'          => 'карарга',
'undeletereset'             => 'Ташлатырга',
'undeletecomment'           => 'Искәрмә:',
'undeletedarticle'          => '«[[$1]]» торгызырган',
'undelete-search-submit'    => 'Эзләргә',
'undelete-error-long'       => 'Файлны торгызу вакытында хаталар чыкты:',
'undelete-show-file-submit' => 'Әйе',

# Namespace form on various pages
'namespace'      => 'Исемнәр мәйданы:',
'invert'         => 'Сайланганны әйләнергә',
'blanknamespace' => '(Төп)',

# Contributions
'contributions' => 'Кулланучының кертеме',
'mycontris'     => 'Кертемем',
'uctop'         => '(ахыргы)',
'month'         => 'Айдан башлап (һәм элегрәк):',
'year'          => 'Елдан башлап (һәм элегрәк):',

'sp-contributions-newbies-sub' => 'Яңа хисап язмалары өчен',
'sp-contributions-blocklog'    => 'Кысу журналы',
'sp-contributions-talk'        => 'фикер алышу',
'sp-contributions-search'      => 'Кертемне эзләү',
'sp-contributions-username'    => 'Кулланучының IP адресы яки исеме:',
'sp-contributions-submit'      => 'Эзләргә',

# What links here
'whatlinkshere'           => 'Бирегә нәрсә сылтый',
'whatlinkshere-title'     => '$1 битенә сылтый торган битләр',
'whatlinkshere-page'      => 'Бит:',
'linkshere'               => "'''[[:$1]]''' биткә чираттагы битләр сылтый:",
'nolinkshere'             => "'''[[:$1]]''' битенә башка битләр сылтамыйлар.",
'isredirect'              => 'җибәрү өчен бит',
'isimage'                 => 'рәсем өчен сылтама',
'whatlinkshere-prev'      => '{{PLURAL:$1|алдагы|алдагы $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|чираттагы|чираттагы $1}}',
'whatlinkshere-links'     => '← сылтамалар',
'whatlinkshere-hidelinks' => '$1 сылтамалар',
'whatlinkshere-filters'   => 'Фильтрлар',

# Block/unblock
'blockip'                 => 'Кулланучыны кысарга',
'ipaddress'               => 'IP-адрес:',
'ipadressorusername'      => 'IP-адрес яки кулланучы исеме:',
'ipbother'                => 'Башка вакыт:',
'ipboptions'              => '2 сәгать:2 hours,1 көн:1 day,3 көн:3 days,1 атна:1 week,2 атна:2 weeks,1 ай:1 month,3ай:3 months,6 ай:6 months,1 ел:1 year,чикләнмәгән:infinite',
'ipblocklist'             => 'Кысылган IP-адреслар һәм кулланучы исемләр',
'ipblocklist-username'    => 'кулланучы исеме яки IP-адрес:',
'blocklink'               => 'кысарга',
'unblocklink'             => 'кысмаска',
'change-blocklink'        => 'блоклауны үзгәртергә',
'contribslink'            => 'кертем',
'blocklogpage'            => 'Тыю көндәлеге',
'block-log-flags-noemail' => 'хат җибәрү тыелган',
'proxyblocksuccess'       => 'Эшләнде',
'sorbsreason'             => 'Сезнең IP-адресыгыз DNSBLда ачык прокси дип санала.',

# Developer tools
'unlockbtn' => 'Мәгълүматлар базасына язу мөмкинлеген кайтарырга',

# Move page
'movearticle'    => 'Битне күчерергә:',
'newtitle'       => 'Яңа башлам:',
'move-watch'     => 'Бу битне күзәтергә',
'movepagebtn'    => 'Битне күчерергә',
'pagemovedsub'   => 'Бит күчерергән',
'movepage-moved' => "<big>'''«$1» бит «$2» биткә күчкән'''</big>",
'movedto'        => 'күчерергән:',
'movetalk'       => 'Бәйләнешле фикер алышу битне күчерергә',
'1movedto2'      => '«[[$1]]» бите «[[$2]]» биткә күчерергән',
'movelogpage'    => 'Күчерү журналы',
'movereason'     => 'Сәбәп:',
'revertmove'     => 'кире кайту',

# Export
'export' => 'Битләрне чыгаруы',

# Namespace 8 related
'allmessages' => 'Система хәбәрләре',

# Thumbnails
'thumbnail-more'  => 'Зурайтырга',
'thumbnail_error' => 'Кечкенә сүрәт төзүе хатасы: $1',

# Import log
'importlogpage' => 'Кертү журналы',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Сезнең кулланучы битем',
'tooltip-pt-mytalk'               => 'Сезнең фикер алышу бите',
'tooltip-pt-preferences'          => 'Минем көйләнмәләрем',
'tooltip-pt-watchlist'            => 'Сез күзәтелгән төзәтмәле битләр исемлеге',
'tooltip-pt-mycontris'            => 'Сезнең кертеменгезне исемлеге',
'tooltip-pt-login'                => 'Сез хисап язмасы төзи алыр идегез, әмма бу мәҗбүри түгел.',
'tooltip-pt-logout'               => 'Чыгарга',
'tooltip-ca-talk'                 => 'Битнең эчтәлеге турында фикер алышу',
'tooltip-ca-edit'                 => 'Сез бу бит үзгәртә аласыз. Зинһар, саклаганчы карап алуны кулланыгыз.',
'tooltip-ca-addsection'           => 'Яңа бүлекне башларга',
'tooltip-ca-history'              => 'Битнең төзәтмәләр исемлеге',
'tooltip-ca-protect'              => 'Бу битне якларга',
'tooltip-ca-delete'               => 'Бу битне бетерергә',
'tooltip-ca-move'                 => 'Бу битне күчерергә',
'tooltip-ca-watch'                => 'Бу битне сезнең күзәтү исемлегезгә өстәргә',
'tooltip-ca-unwatch'              => 'Бу битне сезнең күзәтү исемлегездә бетерергә',
'tooltip-search'                  => 'Эзләргә {{SITENAME}}',
'tooltip-search-go'               => 'Нәк шундый исеме белән биткә күчәргә',
'tooltip-search-fulltext'         => 'Бу текст белән битләрне табарга',
'tooltip-p-logo'                  => 'Баш бит',
'tooltip-n-mainpage'              => 'Баш битне кереп чыгарга',
'tooltip-n-portal'                => 'Проект турында, сез нәрсә итә аласыз һәм нәрсә кайда була дип турында.',
'tooltip-n-currentevents'         => 'Агымдагы вакыйгалар турында мәгълүматны табарга',
'tooltip-n-recentchanges'         => 'Соңгы төзәтмәләр исемлеге',
'tooltip-n-randompage'            => 'Очраклы битне карарга',
'tooltip-n-help'                  => '«{{SITENAME}}» проектының белешмәлек',
'tooltip-t-whatlinkshere'         => 'Бирегә сылтаган барлык битләрнең исемлеге',
'tooltip-t-recentchangeslinked'   => 'Бу биттән сылтаган битләрдә ахыргы үзгәртүләр',
'tooltip-t-contributions'         => 'Кулланучының кертеме исемлегене карарга',
'tooltip-t-emailuser'             => 'Бу кулланучыга хат җибәрергә',
'tooltip-t-upload'                => 'Файлларны йөкләргә',
'tooltip-t-specialpages'          => 'Барлык махсус битләр исемлеге',
'tooltip-t-print'                 => 'Бу битнең бастыру версиясе',
'tooltip-t-permalink'             => 'Битнең бу юрамасыга даими сылтама',
'tooltip-ca-nstab-main'           => 'Мәкаләнең эчтәлеге',
'tooltip-ca-nstab-user'           => 'Кулланучының битене карарга',
'tooltip-ca-nstab-special'        => 'Бу махсус бит, сез аны үзгәртү алмыйсыз',
'tooltip-ca-nstab-project'        => 'Проектның битене карарга',
'tooltip-ca-nstab-image'          => 'Сүрәтнең битене карарга',
'tooltip-ca-nstab-template'       => 'Өлгене карарга',
'tooltip-ca-nstab-help'           => 'Белешмәнең битене карарга',
'tooltip-ca-nstab-category'       => 'Төркемнең битене карарга',
'tooltip-minoredit'               => 'Бу үзгәртүне әһәмиятсез булып билгеләргә',
'tooltip-save'                    => 'Сезнең төзетмәләрегезне сакларга',
'tooltip-preview'                 => 'Сезнең төзәтмәләрегезнең алдан карауы, саклаудан кадәр кулланыгыз әле!',
'tooltip-diff'                    => 'Сезнең үзгәртмәләрегезне күрсәтү.',
'tooltip-compareselectedversions' => 'Бу битнең сайланган ике версиясе арасында аерманы карарга',
'tooltip-watch'                   => 'Бу битне күзәтү исемлегемә өстәргә',

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
'metadata-expand'   => 'Өстәмә мәгълүматларны күрсәтергә',
'metadata-collapse' => 'Өстәмә мәгълүматларны яшерергә',

# EXIF tags
'exif-brightnessvalue' => 'Яктылык',

# External editor support
'edit-externally' => 'Бу файлны тышкы кушымтаны кулланып үзгәртергә',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'барлык',
'namespacesall' => 'барлык',
'monthsall'     => 'барлык',

# Table pager
'table_pager_next' => 'Киләсе бит',

# Watchlist editing tools
'watchlisttools-edit' => 'Күзәтү исемлегене карау һәм үзгәртү',

# Special:Version
'version'                  => 'Юрама',
'version-other'            => 'Башкалар',
'version-software-version' => 'Версия',

# Special:FilePath
'filepath-page' => 'Файл:',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Эзләргә',

# Special:SpecialPages
'specialpages'            => 'Махсус битләр',
'specialpages-group-spam' => 'Спамга каршы кораллар',

# Special:Tags
'tags-edit' => 'үзгәртергә',

# Database error messages
'dberr-problems' => 'Гафу итегез! Сайтта техник кыенлыклар чыкты.',

# HTML forms
'htmlform-submit'              => 'Җибәрергә',
'htmlform-reset'               => 'Үзгәртүләрне кире кайтарырга',
'htmlform-selectorother-other' => 'Башка',

# Add categories per AJAX
'ajax-add-category'          => 'Бүлек өстәргә',
'ajax-add-category-submit'   => 'Өстәргә',
'ajax-confirm-save'          => 'Сакларга',
'ajax-error-title'           => 'Хата',
'ajax-error-dismiss'         => 'ОК',
'ajax-remove-category-error' => 'Бу бүлекне алып ташлап булмады.
Гадәттә шаблон аша өстәлгән бүлекләрдә шушындый хаталар чыга.',

);
