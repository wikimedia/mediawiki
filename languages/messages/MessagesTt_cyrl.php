<?php
/** Tatar (Cyrillic script) (Татарча)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ajdar
 * @author Bulatbulat
 * @author Don Alessandro
 * @author Haqmar
 * @author Himiq Dzyu
 * @author KhayR
 * @author Marat Vildanov
 * @author Reedy
 * @author Rinatus
 * @author Timming
 * @author Yildiz
 * @author Zahidulla
 * @author Ерней
 * @author Ильнар
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
	'Mypage'                    => array( 'Сәхифәм' ),
	'Mytalk'                    => array( 'Бәхәсем' ),
	'Newimages'                 => array( 'Яңа_файл' ),
	'Newpages'                  => array( 'Яңа_бит' ),
	'Popularpages'              => array( 'Популяр_битләр' ),
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
	'Uncategorizedcategories'   => array( 'Үзләштерелмәгән__бүлекләр' ),
	'Uncategorizedimages'       => array( 'Үзләштерелмәгән_файллар' ),
	'Uncategorizedpages'        => array( 'Үзләштерелмәгән_битләр' ),
	'Uncategorizedtemplates'    => array( 'Үзләштерелмәгән__үрнәкләр' ),
	'Unusedcategories'          => array( 'Кулланылмаган_төркемнәр' ),
	'Unusedimages'              => array( 'Кулланылмаучы__файллар' ),
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
	'redirect'              => array( '0', '#ЮНӘЛТҮ', '#перенаправление', '#перенапр', '#REDIRECT' ),
	'notoc'                 => array( '0', '__БАШЛЫКЮК__', '__БЕЗ_ОГЛАВЛЕНИЯ__', '__БЕЗ_ОГЛ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ETTIQ__', '__ОБЯЗ_ОГЛ__', '__ОБЯЗАТЕЛЬНОЕ_ОГЛАВЛЕНИЕ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ЭЧТЕЛЕК__', '__ОГЛАВЛЕНИЕ__', '__ОГЛ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__БҮЛЕКҮЗГӘРТҮЮК__', '__БЕЗ_РЕДАКТИРОВАНИЯ_РАЗДЕЛА__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'АГЫМДАГЫ_АЙ', 'АГЫМДАГЫ_АЙ2', 'ТЕКУЩИЙ_МЕСЯЦ', 'ТЕКУЩИЙ_МЕСЯЦ_2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'АГЫМДАГЫ_АЙ_ИСЕМЕ_GEN', 'НАЗВАНИЕ_ТЕКУЩЕГО_МЕСЯЦА_РОД', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'АГЫМДАГЫ_КӨН', 'ТЕКУЩИЙ_ДЕНЬ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'АГЫМДАГЫ_КӨН2', 'ТЕКУЩИЙ_ДЕНЬ_2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'АГЫМДАГЫ_КӨН_ИСЕМЕ', 'НАЗВАНИЕ_ТЕКУЩЕГО_ДНЯ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'АГЫМДАГЫ_ЕЛ', 'ТЕКУЩИЙ_ГОД', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'АГЫМДАГЫ_ВАКЫТ', 'ТЕКУЩЕЕ_ВРЕМЯ', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'МӘКАЛӘ_САНЫ', 'КОЛИЧЕСТВО_СТАТЕЙ', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'БИТ_ИСЕМЕ', 'НАЗВАНИЕ_СТРАНИЦЫ', 'PAGENAME' ),
	'namespace'             => array( '1', 'ИСЕМНӘР_МӘЙДАНЫ', 'ПРОСТРАНСТВО_ИМЁН', 'NAMESPACE' ),
	'msg'                   => array( '0', 'ХӘБӘР', 'СООБЩЕНИЕ:', 'СООБЩ:', 'MSG:' ),
	'subst'                 => array( '0', 'TÖPÇEK:', 'ПОДСТ:', 'ПОДСТАНОВКА:', 'SUBST:' ),
	'img_right'             => array( '1', 'уңда', 'справа', 'right' ),
	'img_left'              => array( '1', 'сулда', 'слева', 'left' ),
	'img_none'              => array( '1', 'юк', 'без', 'none' ),
	'img_width'             => array( '1', '$1пкс', '$1px' ),
	'img_center'            => array( '1', 'үзәк', 'центр', 'center', 'centre' ),
	'int'                   => array( '0', 'ЭЧКЕ:', 'ВНУТР:', 'INT:' ),
	'sitename'              => array( '1', 'СӘХИФӘ_ИСЕМЕ', 'НАЗВАНИЕ_САЙТА', 'SITENAME' ),
	'ns'                    => array( '0', 'İA:', 'ПИ:', 'NS:' ),
	'localurl'              => array( '0', 'URINLIURL:', 'ЛОКАЛЬНЫЙ_АДРЕС:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URINLIURLE:', 'ЛОКАЛЬНЫЙ_АДРЕС_2:', 'LOCALURLE:' ),
	'language'              => array( '0', '#ТЕЛ:', '#ЯЗЫК:', '#LANGUAGE:' ),
	'special'               => array( '0', 'махсус', 'служебная', 'special' ),
	'tag'                   => array( '0', 'тамга', 'метка', 'тег', 'тэг', 'tag' ),
	'noindex'               => array( '1', '__ИНДЕКССЫЗ__', '__БЕЗ_ИНДЕКСА__', '__NOINDEX__' ),
);

$linkTrail = '/^([a-zабвгдеёжзийклмнопрстуфхцчшщъыьэюяӘәӨөҮүҖҗҢңҺһ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Сылтамаларның астына сызу:',
'tog-highlightbroken'         => 'Төзелмәгән сылтамалар <a href="" class="new">шушылай</a> (юкса <a href="" class="internal">болай</a>) күрсәтелсен',
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
'tog-rememberpassword'        => 'Хисап язмамны бу браузерда саклансын (иң күп $1 {{PLURAL:$1|көн|көн|көн}}гә кадәр)',
'tog-watchcreations'          => 'Төзегән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchdefault'            => 'Үзгәрткән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchmoves'              => 'Күчергән битләрем күзәтү исемлегемә өстәлсен',
'tog-watchdeletion'           => 'Бетерелгән битләремне күзәтү исемлегемгә өстәү',
'tog-minordefault'            => 'Барлык үзгәртүләрне килешү буенча кече дип билгеләнсен',
'tog-previewontop'            => 'Үзгәртү тәрәзәсеннән өстәрәк битне алдан карау өлкәсен күрсәтелсен',
'tog-previewonfirst'          => 'Үзгәртү битенә күчкәндә башта алдан карау бите күрсәтелсен',
'tog-nocache'                 => 'Битләр кэшлауны тыелсын',
'tog-enotifwatchlistpages'    => 'Күзәтү исемлегемдәге бит үзгәртелү турында электрон почтага хәбәр җибәрелсен',
'tog-enotifusertalkpages'     => 'Бәхәс битем үзгәртелү турында электрон почтага хәбәр җибәрелсен',
'tog-enotifminoredits'        => 'Кече үзгәртүләр турында да электрон почтага хәбәр җибәрелсен',
'tog-enotifrevealaddr'        => 'Хәбәрләрдә e-mail адресым күрсәтелсен',
'tog-shownumberswatching'     => 'Битне күзәтү исемлекләренә өстәгән кулланучылар санын күрсәтелсен',
'tog-oldsig'                  => 'Хәзерге имза:',
'tog-fancysig'                => 'Имзаның шәхси вики-билгеләмәсе (автоматик сылтамасыз)',
'tog-externaleditor'          => 'Тышкы редактор куллану (бары тик белгечләргә генә һәм санак махсус көйләнгән булу зарур; [//www.mediawiki.org/wiki/Manual:External_editors тулырак...])',
'tog-externaldiff'            => 'Тышкы версия чагыштыру программасын куллану (бары тик белгечләр өчен һшм санак махсус көйләнгән булу зарур; [//www.mediawiki.org/wiki/Manual:External_editors тулырак...])',
'tog-showjumplinks'           => '«Күчү» ярдәмче сылтамалары ялгансын',
'tog-uselivepreview'          => 'Тиз карап алу кулланылсын (JavaScript, эксперименталь)',
'tog-forceeditsummary'        => 'Үзгәртүләрне тасвирлау юлы тутырылмаган булса, кисәтү',
'tog-watchlisthideown'        => 'Минем үзгәртүләрем күзәтү исемлегеннән яшерелсен',
'tog-watchlisthidebots'       => 'Бот үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideminor'      => 'Кече үзгәртүләр күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideliu'        => 'Авторизацияне узган кулланучыларның үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthideanons'      => 'Аноним кулланучыларның үзгәртүләре күзәтү исемлегеннән яшерелсен',
'tog-watchlisthidepatrolled'  => 'Тикшерелгән үзгәртүләр күзәтү исемлегеннән яшерелсен',
'tog-nolangconversion'        => 'Язу системаларының үзгәртүен сүндерү',
'tog-ccmeonemails'            => 'Башка кулланучыларга җибәргән хатларымның копияләре миңа да җибәрелсен',
'tog-diffonly'                => 'Юрама чагыштыру астында бит эчтәлеге күрсәтелмәсен',
'tog-showhiddencats'          => 'Яшерен төркемнәр күрсәтелсен',
'tog-norollbackdiff'          => 'Кире кайтару ясагач юрамалар аермасы күрсәтелмәсен',

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
'broken-file-category'           => 'Эшләми торган файл сылтамаларлы битләр',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'         => 'Тасвирлама',
'article'       => 'Мәкалә',
'newwindow'     => '(яңа тәрәзәдә ачыла)',
'cancel'        => 'Баш тарту',
'moredotdotdot' => 'Дәвамы…',
'mypage'        => 'Шәхси битем',
'mytalk'        => 'Бәхәсем',
'anontalk'      => 'Бу IP адресы өчен бәхәс бите',
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
'vector-action-addsection'       => 'Яңа тема өстәү',
'vector-action-delete'           => 'Бетерү',
'vector-action-move'             => 'Күчерү',
'vector-action-protect'          => 'Яклау',
'vector-action-undelete'         => 'Кайтару',
'vector-action-unprotect'        => 'Яклауны үзгәртү',
'vector-simplesearch-preference' => 'Эзләү өчен киңәйтелгән ярдәм хәбәрләрен күрсәтү («Векторлы» бизәлеше өчен генә кулланылыа)',
'vector-view-create'             => 'Төзү',
'vector-view-edit'               => 'Үзгәртү',
'vector-view-history'            => 'Тарихын карау',
'vector-view-view'               => 'Уку',
'vector-view-viewsource'         => 'Чыганагын карау',
'actions'                        => 'Хәрәкәт',
'namespaces'                     => 'Исемнәр мәйданы',
'variants'                       => 'Төрләр',

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
'printableversion'  => 'Бастыру версиясе',
'permalink'         => 'Даими сылтама',
'print'             => 'Бастыру',
'view'              => 'Карау',
'edit'              => 'Үзгәртү',
'create'            => 'Төзү',
'editthispage'      => 'Бу битне үзгәртү',
'create-this-page'  => 'Бу битне төзү',
'delete'            => 'Бетерү',
'deletethispage'    => 'Бу битне бетерү',
'undelete_short'    => '$1 {{PLURAL:$1|үзгәртмәне}} торгызу',
'viewdeleted_short' => '{{PLURAL:$1|1 бетерелгән үзгәртүне|$1 бетерелгән үзгәртүне}} карау',
'protect'           => 'Яклау',
'protect_change'    => 'үзгәртү',
'protectthispage'   => 'Бу битне яклау',
'unprotect'         => 'Яклауны үзгәртү',
'unprotectthispage' => 'Бу битнең яклауын үзгәртү',
'newpage'           => 'Яңа бит',
'talkpage'          => 'Бит турында фикер алышу',
'talkpagelinktext'  => 'Бәхәс',
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
'jumpto'            => 'Моңа күчү:',
'jumptonavigation'  => 'навигация',
'jumptosearch'      => 'эзләү',
'view-pool-error'   => 'Гафу итегез, хәзерге вакытта серверлар буш түгел.
Бу битне карарга теләүчеләр артык күп.
Бу биткә соңрак керүегез сорала.

$1',
'pool-timeout'      => 'Кысылуның  вакыты узды',
'pool-queuefull'    => 'Сорауларны саклау  бите тулы',
'pool-errorunknown' => 'Билгесез  хата',

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
'badaccess-groups' => 'Соралган гамәлне $1 {{PLURAL:$2|төркеменең|төркеменең}} кулланучылары гына башкара ала.',

'versionrequired'     => 'MediaWikiның $1 версиясе таләп ителә',
'versionrequiredtext' => 'Бу бит белән эшләү өчен MediaWikiның $1 версиясе кирәк. [[Special:Version|Кулланылучы программа версиясе турында мәгълүмат битен]] кара.',

'ok'                      => 'OK',
'pagetitle'               => '$1 — {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Чыганагы — "$1"',
'youhavenewmessages'      => 'Сездә $1 бар ($2).',
'newmessageslink'         => 'яңа хәбәрләр',
'newmessagesdifflink'     => 'бәхәс битегезнең соңгы үзгәртүе',
'youhavenewmessagesmulti' => 'Сезгә монда яңа хәбәрләр бар: $1',
'editsection'             => 'үзгәртү',
'editsection-brackets'    => '[$1]',
'editold'                 => 'үзгәртү',
'viewsourceold'           => 'башлангыч кодны карау',
'editlink'                => 'үзгәртү',
'viewsourcelink'          => 'башлангыч кодны карау',
'editsectionhint'         => '$1 бүлеген үзгәртү',
'toc'                     => 'Эчтәлек',
'showtoc'                 => 'күрсәтү',
'hidetoc'                 => 'яшерү',
'collapsible-collapse'    => 'Төрү',
'collapsible-expand'      => 'Ачу',
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
'feed-atom'               => 'Atom-тасмасы',
'feed-rss'                => 'RSS-тасмасы',
'red-link-title'          => '$1 (мондый бит юк)',
'sort-descending'         => 'Кимү буенча урнаштыру',
'sort-ascending'          => 'Арту буенча урнаштыру',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Бит',
'nstab-user'      => 'Кулланучы бите',
'nstab-media'     => 'Мультимедиа',
'nstab-special'   => 'Махсус бит',
'nstab-project'   => 'Проект бите',
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
Зинһар өчен, URLны күрсәтеп, бу турыда [[Special:ListUsers/sysop|идарәчегә]] хәбәр итегез.',
'missingarticle-rev'   => '(юрама № $1)',
'missingarticle-diff'  => '(аерма: $1, $2)',
'readonly_lag'         => 'Мәгълүматлар базасы, өстәмә сервер төп сервер белән синхронизацияләшкәнче, үзгәрүләрдән автомат рәвештә ябылды.',
'internalerror'        => 'Эчке хата',
'internalerror_info'   => 'Эчке хата: $1',
'fileappenderrorread'  => 'Кушу вакытында «$1» укып булмады.',
'fileappenderror'      => '"$1" һәм "$2" не кушып булмады.',
'filecopyerror'        => '«$2» файлына «$1» файлының копиясен ясап булмый.',
'filerenameerror'      => '«$1» файлының исемен «$2» исеменә алыштырып булмый.',
'filedeleteerror'      => '«$1» файлын бетереп булмый.',
'directorycreateerror' => '«$1» директориясен ясап булмый.',
'filenotfound'         => '«$1» файлын табып булмый.',
'fileexistserror'      => '«$1» файлына яздырып булмый: ул инде бар.',
'unexpected'           => 'Көтелмәгән кыймәт: «$1»=«$2».',
'formerror'            => 'Хата: форма мәгълүматларын тапшырып булмый',
'badarticleerror'      => 'Бу биттә мондый гамәл башкарып булмый.',
'cannotdelete'         => '«$1» исемле битне яки файлны бетереп булмый. Аны бүтән кулланучы бетергән булырга мөмкин.',
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
'editinginterface'     => "'''Игътибар:''' Сез MediaWiki системасының интерфейс битен үзгәртәсез. Бу башка кулланучыларга да тәэсир итәчәк. Тәрҗемә өчен [//translatewiki.net/wiki/Main_Page?setlang=tt-cyrl translatewiki.net] локализацияләү проектын кулланыгыз.",
'sqlhidden'            => '(SQL-сорау яшерелгән)',
'cascadeprotected'     => 'Бу бит үзгәртүләрдән сакланган, чөнки ул каскадлы саклау кабул ителгән {{PLURAL:$1|биткә|битләргә}} өстәлгән:
$2',
'namespaceprotected'   => "'''$1''' исем киңлегендәге битләрне үзгәртү өчен сезнең рөхсәтегез юк.",
'customcssprotected'   => 'Сез бу CSS-сәхифәне үзгәртә алмыйсыз, чөнки монда башка кулланучының шәхси көйләнмәләре саклана',
'customjsprotected'    => 'Сез бу JavaScript-сәхифәне үзгәртә алмыйсыз, чөнк монда башка кулланучының шәхси көйләнмәләре саклана',
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
'remembermypassword'         => 'Хисап язмамны бу браузерда саклансын (иң күп $1 {{PLURAL:$1|көн|көн|көн}}гә кадәр)',
'securelogin-stick-https'    => 'Керүдән соң HTTPS буенча тоташтыруны дәвам итәргә',
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
'createaccount'              => 'Яңа кулланучы теркәү',
'gotaccount'                 => "Сез инде теркәлдегезме? '''$1'''.",
'gotaccountlink'             => 'Керү',
'userlogin-resetlink'        => 'Серсүзегезне оныттыгызмы?',
'createaccountmail'          => 'электрон почта аша',
'createaccountreason'        => 'Сәбәп:',
'badretype'                  => 'Кертелгән серсүзләр бер үк түгел.',
'userexists'                 => 'Кертелгән исем кулланыла.
Зинһар, башка исем сайлагыз.',
'loginerror'                 => 'Керү хатасы',
'createaccounterror'         => 'Хисап язмасын төзеп булмый: $1',
'nocookiesnew'               => 'Кулланучы теркәлгән, ләкин үз хисап язмасы белән кермәгән. {{SITENAME}} кулланучыны тану өчен «cookies» куллана. Сездә «cookies» тыелган. Зинһар, башта аларны рөхсәт итегез, аннан исем һәм серсүз белән керегез.',
'nocookieslogin'             => '{{SITENAME}} кулланучыны тану өчен «cookies» куллана. Сез аларны сүндергәнсез. Зинһар, аларны кабызып, яңадан керегез.',
'noname'                     => 'Сез кулланучы исемегезне күрсәтергә тиешсез.',
'loginsuccesstitle'          => 'Керү уңышлы үтте',
'loginsuccess'               => "'''Сез {{SITENAME}} проектына $1 исеме белән кердегез.'''",
'nosuchuser'                 => '$1 исемле кулланучы юк.
Кулланучы исеменең дөреслеге регистрга бәйле.
Язылышыгызны тикшерегез яки [[Special:UserLogin/signup|яңа хисап язмасы төзегез]].',
'nosuchusershort'            => '$1 исемле кулланучы юк. Язылышыгызны тикшерегез.',
'nouserspecified'            => 'Сез теркәү исмегезне күрсәтергә тиешсез.',
'login-userblocked'          => 'Бу кулланучы тыелды. Керү тыелган.',
'wrongpassword'              => 'Язылган серсүз дөрес түгел. Тагын бер тапкыр сынагыз.',
'wrongpasswordempty'         => 'Серсүз юлы буш булырга тиеш түгел.',
'passwordtooshort'           => 'Сезсүз $1 {{PLURAL:$1|символдан}} торырга тиеш.',
'password-name-match'        => 'Кертелгән серсүз кулланучы исеменнән аерылырга тиеш.',
'password-login-forbidden'   => 'Бу кулланучы исемен һәм серсүзне куллану тыелган',
'mailmypassword'             => 'Электрон почтага яңа серсүз җибәрү',
'passwordremindertitle'      => '{{SITENAME}} кулланучысына вакытлы серсүз тапшыру',
'passwordremindertext'       => 'Кемдер (бәлки, сездер, IP адресы: $1) {{SITENAME}} ($4) өчен яңа серсүз соратты. $2 өчен яңа серсүз: $3. Әгәр бу сез булсагыз, системага керегез һәм серсүзне алмаштырыгыз. Яңа серсүз $5 {{PLURAL:$5|көн}} гамәлдә булачак.

Әгәр сез серсүзне алмаштыруны сорамаган булсагыз яки, оныткан очракта, исегезгә төшергән булсагыз, бу хәбәргә игътибар бирмичә, иске серсүзегезне куллануны дәвам итегез.',
'noemail'                    => '$1 исемле кулланучы өчен электрон почта адресы язылмаган.',
'noemailcreate'              => 'Сез дөрес e-mail адресы күрсәтергә тиеш',
'passwordsent'               => 'Яңа серсүз $1 исемле кулланучының электрон почта адресына җибәрелде.

Зинһар, серсүзне алгач, системага яңадан керегез.',
'blocked-mailpassword'       => 'Сезнең IP адресыгыз белән битләр үзгәртеп һәм серсүзне яңартып булмый.',
'eauthentsent'               => 'Адрес үзгәртүне дәлилләү өчен аңа махсус хат җибәрелде. Хатта язылганнарны үтәвегез сорала.',
'throttled-mailpassword'     => 'Серсүзне электрон почтага җибәрү гамәлен сез {{PLURAL:$1|соңгы $1 сәгать}} эчендә кулландыгыз инде. Бу гамәлне явызларча куллануны кисәтү максатыннан аны $1 {{PLURAL:$1|сәгать}} аралыгында бер генә тапкыр башкарып була.',
'mailerror'                  => 'Хат җибәрү хатасы: $1',
'acct_creation_throttle_hit' => 'Сезнең IP адресыннан бу тәүлек эчендә {{PLURAL:$1|$1 хисап язмасы}} төзелде инде. Шунлыктан бу гамәл сезнең өчен вакытлыча ябык.',
'emailauthenticated'         => 'Электрон почта адресыгыз расланды: $3, $2.',
'emailnotauthenticated'      => 'Электрон почта адресыгыз әле дәлилләнмәгән, шуңа викиның электрон почта белән эшләү гамәлләре сүндерелде.',
'noemailprefs'               => 'Электрон почта адресыгыз күрсәтелмәгән, шуңа викиның электрон почта белән эшләү гамәлләре сүндерелгән.',
'emailconfirmlink'           => 'Электрон почта адресыгызны дәлилләгез.',
'invalidemailaddress'        => 'Электрон почта адресы кабул ителә алмый, чөнки ул дөрес форматка туры килми. Зинһар, дөрес адрес кертегез яки юлны буш калдырыгыз.',
'accountcreated'             => 'Хисап язмасы төзелде',
'accountcreatedtext'         => '$1 исемле кулланучы өчен хисап язмасы төзелде.',
'createaccount-title'        => '{{SITENAME}}: теркәлү',
'createaccount-text'         => 'Кемдер, электрон почта адресыгызны күрсәтеп, {{SITENAME}} ($4) проектында «$3» серсүзе белән «$2» исемле хисап язмасы теркәде. Сез керергә һәм серсүзегезне үзгәртергә тиеш.

Хисап язмасы төзү хата булса, бу хатны онытыгыз.',
'usernamehasherror'          => 'Кулланучы исемендә "#" символы була алмый',
'login-throttled'            => 'Сез артык күп тапкыр керергә тырыштыгыз.
Яңадан кабатлаганчы бераз көтүегез сорала.',
'loginlanguagelabel'         => 'Тел: $1',
'suspicious-userlogout'      => 'Сезнең эшчәнлекне бетерү соравыгыз кире кагылды, чөнки ул ялгыш браузер яисә кэшлаучы прокси аша җибәрелергэ мөмкин.',

# E-mail sending
'php-mail-error-unknown' => 'PHP mail() функциясендә билгесез хата',

# Change password dialog
'resetpass'                 => 'Серсүзне үзгәртү',
'resetpass_announce'        => 'Сез электрон почта аша вакытлыча бирелгән серсүз ярдәмендә кердегез. Системага керүне төгәлләү өчен яңа серсүз төзегез.',
'resetpass_text'            => '<!-- Монда текст өстәгез -->',
'resetpass_header'          => 'Хисап язмасы серсүзен үзгәртү',
'oldpassword'               => 'Иске серсүз:',
'newpassword'               => 'Яңа серсүз:',
'retypenew'                 => 'Яңа серсүзне кабатлагыз:',
'resetpass_submit'          => 'Серсүз куеп керү',
'resetpass_success'         => 'Сезнең серсүз уңышлы үзгәртелде! Системага керү башкарыла...',
'resetpass_forbidden'       => 'Серсүз үзгәртелә алмый',
'resetpass-no-info'         => 'Бу битне карау өчен сез системага үз хисап язмагыз ярдәмендә керергә тиеш.',
'resetpass-submit-loggedin' => 'Серсүзне үзгәртү',
'resetpass-submit-cancel'   => 'Кире кагу',
'resetpass-wrong-oldpass'   => 'Ялгыш серсүз.
Сез серсүзегезне үзгәрткән яисә яңа вакытлы серсүз сораткан булырга мөмкинсез.',
'resetpass-temp-password'   => 'Вакытлы серсүз:',

# Special:PasswordReset
'passwordreset'              => 'Серсүзне бетерү',
'passwordreset-text'         => 'Сезнең хисап язмасының параметрлары турында хат алыр өчен, түбәндәгеләрне тутырыгыз',
'passwordreset-legend'       => 'Серсүзне яңадан кую',
'passwordreset-username'     => 'Кулланучы исеме:',
'passwordreset-domain'       => 'Домен:',
'passwordreset-email'        => 'E-mail адресы',
'passwordreset-emailelement' => 'Кулланучы исеме: $1
Вакытлыча серсүз: $2',

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
'nowiki_sample'   => 'Форматланмаган текстны монда өстәгез',
'nowiki_tip'      => 'Вики-форматлауны исәпкә алмау',
'image_sample'    => 'Мисал.jpg',
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
'anoneditwarning'                  => "'''Игътибар''': Сез системага кермәгәнсез. IP адресыгыз бу битнең тарихына язылачак.",
'anonpreviewwarning'               => "''Сез системада теркәлмәдегез.Сезнең тарафтан эшләнгән барлык үзгәртүләр дә сезнең IP-юлламагызны саклауга китерә.''",
'missingsummary'                   => "'''Искәртү.''' Сез үзгәртүгә кыскача тасвирлау язмадыгыз. Сез «Битне саклау» төймәсенә тагын бер тапкыр бассагыз, үзгәртүләр тасвирламасыз сакланачак.",
'missingcommenttext'               => 'Аска тасвирлама язуыгыз сорала.',
'missingcommentheader'             => "''Искәртү:''' Сез тасвирламага исем бирмәдегез.
«{{int:savearticle}}» төймәсенә кабат бассагыз, үзгәртүләр исемсез язылачак.",
'summary-preview'                  => 'Тасвирламаны алдан карау:',
'subject-preview'                  => 'Башисемне алдан карау:',
'blockedtitle'                     => 'Кулланучы тыелды',
'blockedtext'                      => "'''Сезнең хисап язмагыз яки IP адресыгыз тыелган.'''

Тыючы идарәче: $1.
Күрсәтелгән сәбәп: ''$2''.

* Тыю башланган вакыт: $8
* Тыю ахыры: $6
* Тыелулар саны: $7

Сез $1 яки башка [[{{MediaWiki:Grouppage-sysop}}|идарәчегә]] тыю буенча сорауларыгызны җибәрә аласыз.
Исегездә тотыгыз: әгәр сез теркәлмәгән һәм электрон почта адресыгызны дәлилләмәгән булсагыз ([[Special:Preferences|дәлилләү өчен шәхси көйләүләр монда]]), идарәчегә хат җибәрә алмыйсыз. Шулай ук тыю вакытында сезнең хат җибәрү мөмкинлегегезне чикләгән булырга да мөмкиннәр.
Сезнең IP адресы — $3, тыю идентификаторы — #$5.
Хатларда бу мәгълүматны күрсәтергә онытмагыз.",
'autoblockedtext'                  => "Сезнең IP адресыгыз, аның тыелган кулланучы тарафыннан кулланылуы сәбәпле, автомат рәвештә тыелды.
Ул кулланучыны тыючы идарәче: $1. Күрсәтелгән сәбәп:

:''$2''

* Тыю башланган вакыт: $8
* Тыю ахыры: $6
* Тыелулар саны: $7

Сез $1 яки башка [[{{MediaWiki:Grouppage-sysop}}|идарәчегә]] тыю буенча сорауларыгызны җибәрә аласыз.
Исегездә тотыгыз: әгәр сез теркәлмәгән һәм электрон почта адресыгызны дәлилләмәгән булсагыз ([[Special:Preferences|дәлилләү өчен шәхси көйләүләр монда]]), идарәчегә хат җибәрә алмыйсыз. Шулай ук тыю вакытында сезнең хат җибәрү мөмкинлегегезне чикләгән булырга да мөмкиннәр.
Сезнең IP адресы — $3, тыю идентификаторы — #$5.
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
Аны тану өчен IP адресы файдаланыла.
Әгәр сез аноним кулланучы һәм сезгә юлланмаган хәбәрләр алдым дип саныйсыз икән (бер IP адресы күп кулланучы өчен булырга мөмкин), башка мондый аңлашылмаучанлыклар килеп чыкмасын өчен [[Special:UserLogin|системага керегез]] яисә [[Special:UserLogin/signup|теркәлегез]].''",
'noarticletext'                    => "Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]],
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} көндәлекләрдәге язмаларны] таба
яки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} шушындый исемле яңа бит төзи]'''</span> аласыз.",
'noarticletext-nopermission'       => 'Хәзерге вакытта бу биттә текст юк.
Сез [[Special:Search/{{PAGENAME}}|бу исем кергән башка мәкаләләрне]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} көндәлекләрдәге язмаларны] таба аласыз.</span>',
'userpage-userdoesnotexist'        => '«<nowiki>$1</nowiki>» исемле хисап язмасы юк. Сез чынлап та бу битне ясарга яисә үзгәртергә телисезме?',
'userpage-userdoesnotexist-view'   => '"$1" исемле хисап язмасы юк.',
'blocked-notice-logextract'        => 'Бу кулланучы хәзергә тыелды.
Түбәндә тыю көндәлегенең соңгы язу бирелгән:',
'clearyourcache'                   => "'''Искәрмә:''' Сез саклаган үзгәртүләр кулланышка керсен өчен браузерыгызның кешын чистартырга туры киләчәк. 
* '''Firefox/Safari''': Shift төймшсенә баскан килеш җиһазлар тасмасында ''Яңарту (Обновить)'' язуына басыгыз, яисә ''Ctrl-F5'' яки  ''Ctrl-R'' (Mac өчен ''Command-R'') төймәләренә басыгыз
* '''Google Chrome.'''  ''Ctrl-Shift-R'' (Mac өчен ''Command-Shift-R'' ) төймәләренә басыгыз
* '''Internet Explorer.''' ''Ctrl''  төймәсенә баскан килеш  ''Яңарту (Обновить)'' язуына, яисә ''Ctrl-F5'' басыгыз
* '''Konqueror.''' ''Яңарту (Обновить)'' язуына, яисә ''F5'' басыгыз
* '''Opera.''' Менюдан кеш чистартуны сайлагыз: ''Җиһазлар (Инструменты) → Көйләнмәләр (Настройки)''",
'usercssyoucanpreview'             => "'''Ярдәм:''' \"{{int:showpreview}} төймәсенә басып, яңа CSS-файлны тикшереп була.",
'userjsyoucanpreview'              => "'''Ярдәм:''' \"{{int:showpreview}}\" төймәсенә басып, яңа JS-файлны тикшереп була.",
'usercsspreview'                   => "'''Бу бары тик CSS-файлны алдан карау гына, ул әле сакланмаган!'''",
'userjspreview'                    => "'''Бу бары тик JavaScript файлын алдан карау гына, ул әле сакланмаган!'''",
'sitecsspreview'                   => "'''онытмагыз, бу бары тик CSS-файлны алдан карау гына.'''
'''Ул әле сакланмаган!'''",
'sitejspreview'                    => "'''Бу бары тик JavaScript файлын алдан карау гына.'''
'''Ул әле сакланмаган!'''",
'userinvalidcssjstitle'            => "'''Игътибар:''' \"\$1\" бизәү темасы табылмады. Кулланучының .css һәм .js битләре исемнәре бары тик кечкенә (юл) хәрефләрдән генә торырга тиеш икәнен онытмагыз. Мисалга: {{ns:user}}:Foo/vector.css, ә {{ns:user}}:Foo/Vector.css түгел!",
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
'explainconflict'                  => 'Сез бу битне төзәткән вакытта кемдер аңа үзгәрешләр кертте.
Өстәге тәрәзәдә Сез хәзерге текстны күрәсез.
Астагы тәрәзәдә Сезнең вариант урнашкан.
Эшләгән үзгәртүләрегезне астагы тәрәзәдән өстәгенә күчерегез.
«{{int:savearticle}}» төймәсенә баскач өстәге битнең тексты сакланаячак.',
'yourtext'                         => 'Сезнең текст',
'storedversion'                    => 'Сакланган юрама',
'nonunicodebrowser'                => "'''Кисәтү: Сезнең браузер Юникод кодлавын танымый.'''
Үзгәртү вакытында ASCII булмаган символлар махсус уналтылы кодларга алыштырылачак.",
'editingold'                       => "'''Кисәтү: Сез битнең искергән юрамасын үзгәртәсез.'''
Саклау төймәсенә баскан очракта яңа юрамалардагы үзгәртүләр югалачак.",
'yourdiff'                         => 'Аермалар',
'copyrightwarning'                 => "Бөтен өстәмәләр һәм үзгәртүләр $2 (карагыз: $1) лицензиясе шартларында башкарыла дип санала.
Әгәр аларның ирекле таратылуын һәм үзгәртелүен теләмәсәгез, монда өстәмәвегез сорала.<br />
Сез өстәмәләрнең авторы булырга яисә мәгълүматның ирекле чыганаклардан алынуын күрсәтергә тиеш.<br />
'''МАХСУС РӨХСӘТТӘН БАШКА АВТОРЛЫК ХОКУКЫ БУЕНЧА САКЛАНУЧЫ МӘГЪЛҮМАТЛАР УРНАШТЫРМАГЫЗ!'''",
'copyrightwarning2'                => "Сезнең үзгәртүләр башка кулланучылар тарафыннан үзгәртелә яисә бетерелә ала.
Әгәр аларның үзгәртелүен теләмәсәгез, монда өстәмәвегез сорала.<br />
Сез өстәмәләрнең авторы булырга яисә мәгълүматның ирекле чыганаклардан алынуын күрсәтергә тиеш (карагыз: $1).
'''МАХСУС РӨХСӘТТӘН БАШКА АВТОРЛЫК ХОКУКЫ БУЕНЧА САКЛАНУЧЫ МӘГЪЛҮМАТЛАР УРНАШТЫРМАГЫЗ!'''",
'longpageerror'                    => "'''ХАТА: сакланучы текст зурлыгы - $1 килобайт, бу $2 килобайт чигеннән күбрәк. Бит саклана алмый.'''",
'readonlywarning'                  => "'''Кисәтү: мәгълүматлар базасында техник эшләр башкарыла, сезнең үзгәртүләр хәзер үк саклана алмый.
Текст югалмасын өчен аны компьютерыгызга саклап тора аласыз.'''

Идарәче күрсәткән сәбәп: $1",
'protectedpagewarning'             => "'''Кисәтү: сез бу битне үзгәртә алмыйсыз, бу хокукка идарәчеләр гына ия.'''
Түбәндә көндәлекнең  соңгы язуы бирелгән:",
'semiprotectedpagewarning'         => "'''Кисәтү:''' бу бит якланган. Аны теркәлгән кулланучылар гына үзгәртә ала.
Аста бу битне күзәтү көндәлеге бирелгән:",
'cascadeprotectedwarning'          => "'''Кисәтү:''' Бу битне идарәчеләр гына үзгәртә ала. Сәбәбе: ул {{PLURAL:$1|каскадлы яклау исемлегенә кертелгән}}:",
'titleprotectedwarning'            => "'''Кисәтү: Мондый исемле бит якланган, аны үзгәртү өчен [[Special:ListGroupRights|тиешле хокукка]] ия булу зарур.'''
Аста күзәтү көндәлегендәге соңгы язма бирелгән:",
'templatesused'                    => 'Бу биттә кулланылган {{PLURAL:$1|үрнәк|үрнәкләр}}:',
'templatesusedpreview'             => 'Алдан каралучы биттә кулланылган {{PLURAL:$1|үрнәк|үрнәкләр}}:',
'templatesusedsection'             => 'Бу бүлектә кулланылган {{PLURAL:$1|үрнәк|үрнәкләр}}:',
'template-protected'               => '(якланган)',
'template-semiprotected'           => '(өлешчә якланган)',
'hiddencategories'                 => 'Бу бит $1 {{PLURAL:$1|яшерен төркемгә}} керә:',
'nocreatetitle'                    => 'Битләр төзү чикләнгән',
'nocreatetext'                     => '{{SITENAME}}: сайтта яңа битләр төзү чикләнгән.
Сез артка кайтып, төзелгән битне үзгәртә аласыз. [[Special:UserLogin|Керергә яисә теркәлергә]] тәгъдим ителә.',
'nocreate-loggedin'                => 'Сезгә яңа битләр төзү хокукы бирелмәгән.',
'sectioneditnotsupported-title'    => 'Бүлекләрне үзгәртү рөхсәт ителми.',
'sectioneditnotsupported-text'     => 'Бу биттә бүлекләрне үзгәртү рөхсәт ителми.',
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
Ул бетерелгән булырга мөмкин.',
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
'language-converter-depth-warning'        => 'Телләрне үзгәртүләре артык югарыга киткән ($1)',

# "Undo" feature
'undo-success' => 'Үзгәртүдән баш тартып була.
Юрамалараны чагыштыруны карагыз һәм, үзгәртүләр Сез теләгәнчә булса, битне саклагыз.',
'undo-failure' => 'Аралыктагы үзгәртүләр туры килмәү сәбәпле, үзгәртүдән баш тартып булмый.',
'undo-norev'   => 'Үзгәртү юк яисә ул бетерелгән, шуңа аннан баш тартып булмый.',
'undo-summary' => '[[Special:Contributions/$2|$2]] кулланучысының ([[User talk:$2|бәхәс]]) $1 үзгәртүеннән баш тарту',

# Account creation failure
'cantcreateaccounttitle' => 'Хисап язмасын төзеп булмый',
'cantcreateaccount-text' => "Бу IP адресыннан (<b>$1</b>) хисап язмалары төзү тыела. Тыючы: [[User:$3|$3]].

$3 күрсәткән сәбәп: ''$2''",

# History pages
'viewpagelogs'           => 'Бу битнең көндәлекләрен карау',
'nohistory'              => 'Бу битнең үзгәртүләр тарихы юк.',
'currentrev'             => 'Хәзерге юрама',
'currentrev-asof'        => 'Хәзерге юрама, $1',
'revisionasof'           => '$1 юрамасы',
'revision-info'          => 'Юрама: $1; $2',
'previousrevision'       => '← Алдагы юрама',
'nextrevision'           => 'Чираттагы юрама →',
'currentrevisionlink'    => 'Хәзерге юрама',
'cur'                    => 'хәзерге',
'next'                   => 'киләсе',
'last'                   => 'бая.',
'page_first'             => 'беренче',
'page_last'              => 'соңгы',
'histlegend'             => "Аңлатмалар: '''({{int:cur}})''' = хәзерге юрамадан аерымлыклар, '''({{int:last}})''' = баягы юрамадан аерымлыклар, '''{{int:minoreditletter}}''' = кече үзгәртүләр.",
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
Ул бетерелгән яисә бүтән урынга күчерелгән (башка исем алган) булырга мөмкин.
[[Special:Search|Эзләтеп]] карагыз.',

# Revision deletion
'rev-deleted-comment'         => '(үзгәртүләрнең тасвиры бетерелгән)',
'rev-deleted-user'            => '(автор исеме бетерелгән)',
'rev-deleted-event'           => '(язма бетерелгән)',
'rev-deleted-user-contribs'   => '[кулланучының исеме яки  IP-юлламасы бетерелгән  — үзгәртү кертем битеннән яшерелгән]',
'rev-deleted-text-permission' => "Битнең бу юрамасы '''бетерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Бетерүләр көндәлегендә] аңлатмалар калдырылган булырга мөмкин.",
'rev-deleted-text-unhide'     => "Битнең бу юрамасы '''бетерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Бетерүләр көндәлегендә]  аңлатмалар калдырылган булырга мөмкин.
Теләгегез булса сез [$1 бирелгән юраманы карый аласыз].",
'rev-suppressed-text-unhide'  => "Битнең бу юрамасы '''яшерелгән'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Яшерүләр көндәлегендә] аңлатмалар бирелгән булырга мөмкин.
Теләгегез булса сез  [$1 бирелгән юраманы карый аласыз].",
'rev-deleted-text-view'       => "Битнең бу юрамасы '''бетерелгән'''.
Сез аны карый аласыз. [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Бетерүләр көндәлегендә] аңлатмалар бирелгән булырга мөмкин.",
'rev-suppressed-text-view'    => "Битнең бу юрамасы '''яшерелгән'''.
Сез аны карый аласыз. [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Яшерүләр көндәлегендә] аңлатмалар бирелгән булырга мөмкин.",
'rev-deleted-no-diff'         => "Сез юрамалар арасындагы аермаларны карый алмыйсыз. Сәбәбе: кайсыдыр юрама '''бетерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Бетерүләр көндәлегендә] тулырак мәгълүмат табып була.",
'rev-suppressed-no-diff'      => "Сез юрамалар  арасындагы үзгәртүләрне карый алмыйсыз, чөнки аларның берсе '''бетерелгән'''.",
'rev-deleted-unhide-diff'     => "Битнең кайсыдыр юрамасы '''бетерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Бетерүләр көндәлегендә] тулырак мәгълүмат табып була.
Теләгегез булса сез  [$1 бирелгән юраманы карый аласыз]",
'rev-suppressed-unhide-diff'  => "Битнең кайсыдыр юрамасы '''яшерелгән'''.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} Яшерүләр көндәлегендә] тулырак мәгълүмат табып була.
Теләгегез булса сез  [$1 яшерелгән юраманы карый аласыз]",
'rev-deleted-diff-view'       => "Бу юрамалар чагыштыруының бер юрамасы '''бетерелгән'''.
Сез  чагыштыруны карый аласыз, [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} бетерүләр көндәлегендә] тулырак мәгълүмат бирелгән булырга мөмкин.",
'rev-suppressed-diff-view'    => "Бу юрамалар чагыштыруының бер юрамасы '''яшерелгән'''.
Сез чагыштыруны карый аласыз, [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} яшерүләр көндәлегендә] тулырак мәгълүмат бирелгән булырга мөмкин.",
'rev-delundel'                => 'күрсәтү/яшерү',
'rev-showdeleted'             => 'күрсәтү',
'revisiondelete'              => 'Битнең юрамасын бетерү / кайтару',
'revdelete-nooldid-title'     => 'Ахыргы юрама билгеләнмәгән',
'revdelete-nooldid-text'      => 'Бу функцияне башкару өчен сез ахыргы юраманы (яки юрамаларны) билгеләмәдегез.',
'revdelete-nologtype-title'   => 'Көндәлек тибы билгеләнмәгән',
'revdelete-nologtype-text'    => 'Гамәл башкарылырга тиешле көндәлек төрен билгеләргә оныттыгыз.',
'revdelete-nologid-title'     => 'Көндәлектәге язма хаталы',
'revdelete-no-file'           => 'Бу файл бар түгел',
'revdelete-show-file-submit'  => 'Әйе',
'revdelete-selected'          => "'''[[:$1]] битенең {{PLURAL:$2|Сайланган юрама|сайланган юрамалары}}:'''",
'logdelete-selected'          => "'''Журналның {{PLURAL:$1|Сайланган язма|сайланган язмалары}} :'''",
'revdelete-legend'            => 'Чикләүләр урнаштыр:',
'revdelete-hide-text'         => 'Битнең бу юрамасы текстын яшер',
'revdelete-hide-image'        => 'Файл эчендәгеләрне качыр',
'revdelete-hide-name'         => 'Гамәлне һәм объектны яшерү',
'revdelete-hide-user'         => 'Үзгәртүченең исемен/IP адресын яшер',
'revdelete-radio-same'        => '(үзгәртмәү)',
'revdelete-radio-set'         => 'Әйе',
'revdelete-radio-unset'       => 'Юк',
'revdel-restore'              => 'күренүчәнлекне үзгәртү',
'revdel-restore-deleted'      => 'бетерелгән юрамалар',
'revdel-restore-visible'      => 'күрсәтелгән юрамалар',
'pagehist'                    => 'битнең тарихы',
'deletedhist'                 => 'Бетерүләр тарихы',
'revdelete-content'           => 'эчтәлек',
'revdelete-summary'           => 'үзгәртүләр тасвирламасы',
'revdelete-uname'             => 'кулланучы исеме',
'revdelete-restricted'        => 'чикләүләр идарәчеләргә дә кулланыла',
'revdelete-hid'               => ' $1 яшерелгән',
'revdelete-unhid'             => '$1 ачылган',
'revdelete-otherreason'       => 'Башка/өстәмә сәбәп:',
'revdelete-reasonotherlist'   => 'Башка сәбәп',
'revdelete-edit-reasonlist'   => 'Сәбәпләр исемлеген үзгәртү',
'revdelete-offender'          => 'Әлеге юрамалы битнең авторы:',

# Suppression log
'suppressionlog' => 'Яшерү көндәлеге',

# History merging
'mergehistory'        => 'Үзгәртүләр тарихын берләштерү',
'mergehistory-box'    => 'Ике битнең үзгәртүләр тарихын берләштерергә:',
'mergehistory-from'   => 'Чыганак:',
'mergehistory-into'   => 'Төп бит:',
'mergehistory-reason' => 'Сәбәп:',

# Merge log
'mergelog'    => 'Берләштерүләр көндәлеге',
'revertmerge' => 'Бүлү',

# Diffs
'history-title'            => '$1 битенең үзгәртү тарихы',
'difference'               => '(Юрамалар арасында аерма)',
'lineno'                   => '$1 юл:',
'compareselectedversions'  => 'Сайланган юрамаларны чагыштыру',
'showhideselectedversions' => 'Сайланган юрамаларны күрсәтү/яшерү',
'editundo'                 => 'үткәрмәү',

# Search results
'searchresults'                    => 'Эзләү нәтиҗәләре',
'searchresults-title'              => '«$1» өчен эзләү нәтиҗәләре',
'searchresulttext'                 => 'Проектның сәхифәләрендә эзләү турында тулырак мәгълумат алыр өчен [[{{MediaWiki:Helppage}}|өстәмә мәгълумат]] битенә керегез.',
'searchsubtitle'                   => '«[[:$1]]» өчен эзләү ([[Special:Prefixindex/$1|«$1» дан башлый барлык битләр]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» га сылтый барлык битләр]])',
'searchsubtitleinvalid'            => '"$1" таләбе буенча',
'notitlematches'                   => 'Битнең исемнәрендә туры килүләр юк',
'notextmatches'                    => 'Тиңдәш текстлы битләр юк',
'prevn'                            => 'алдагы {{PLURAL:$1|$1}}',
'nextn'                            => 'чираттагы {{PLURAL:$1|$1}}',
'shown-title'                      => 'Сәхифәдә $1 язма күрсәтергә',
'viewprevnext'                     => 'Күрсәтелүе: ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Эзләү көйләнмәләре',
'searchmenu-exists'                => "'''Бу вики-проекта «[[:$1]]» исемле бит бар инде'''",
'searchmenu-new'                   => "'''«[[:$1]]»  исемле яңа бит ясау'''",
'searchhelp-url'                   => 'Help:Эчтәлек',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Бу префикслы битләрне күрсәтү]]',
'searchprofile-articles'           => 'Төп битләр',
'searchprofile-project'            => 'Ярдәм һәм проектлар бите',
'searchprofile-images'             => 'Мультимедиа',
'searchprofile-everything'         => 'Һәркайда',
'searchprofile-advanced'           => 'Киңәйтелгән',
'searchprofile-articles-tooltip'   => '$1 дә эзләү',
'searchprofile-project-tooltip'    => '$1 дә эзләү',
'searchprofile-images-tooltip'     => 'Файллар эзләү',
'searchprofile-everything-tooltip' => 'Барлык битләрдә дә эзләү',
'searchprofile-advanced-tooltip'   => 'Бирелгән исемнәр мәйданында эзләү',
'search-result-size'               => '$1 ({{PLURAL:$2|$2 сүз}})',
'search-result-score'              => 'Релевантлыгы: $1 %',
'search-redirect'                  => '(юнәлтү $1)',
'search-section'                   => '($1 бүлеге)',
'search-suggest'                   => 'Бәлки, сез моны эзлисез: $1',
'search-interwiki-caption'         => 'Тугандаш проектлар',
'search-interwiki-default'         => '$1 нәтиҗә:',
'search-interwiki-more'            => '(тагын)',
'search-mwsuggest-enabled'         => 'киңәшләр белән',
'search-mwsuggest-disabled'        => 'киңәшсез',
'search-relatedarticle'            => 'Бәйләнгән',
'mwsuggest-disable'                => 'AJAX-ярдәмне ябу',
'searcheverything-enable'          => 'Барлык исемнәр мәйданында эзләү',
'searchrelated'                    => 'бәйләнгән',
'searchall'                        => 'барлык',
'showingresults'                   => "Аста № '''$2''' {{PLURAL:$1|башлап}} '''$1''' {{PLURAL:$1|результат}} күрсәтелгән.",
'showingresultsnum'                => "Аста № '''$2''' {{PLURAL:$3| башлап}} '''$3''' {{PLURAL:$3|результат}} күрсәтелгән.",
'showingresultsheader'             => "'''$4''' өчен {{PLURAL:$5|Результат '''$1''' сеннән '''$3'''|Результатлар '''$1 — $2''' сеннән  '''$3'''}}",
'nonefound'                        => "'''Искәрмә'''. Килешү буенча эзләү кайбер исем аланнарында гына эшли.
Барлык аланнарда (бәхәс битләре, үрнәкләр, һ.б.) эзләү өчен ''all'' сүзен сайлагыз, яисә кирәкле исем аланын сайлагыз.",
'search-nonefound'                 => 'Сорауга туры килгән җаваплар табылмады.',
'powersearch'                      => 'Өстәмә эзләү',
'powersearch-legend'               => 'Өстәмә эзләү',
'powersearch-ns'                   => 'исемнәрендә эзләү',
'powersearch-redir'                => 'Юнәлтүләр күрсәтелсен',
'powersearch-field'                => 'Эзләү',
'powersearch-togglelabel'          => 'Кире кагыу:',
'powersearch-toggleall'            => 'Барысы',
'powersearch-togglenone'           => 'Бирни дә юк',
'search-external'                  => 'Тышкы эзләү',

# Quickbar
'qbsettings'               => 'Күчешләр аслыгы',
'qbsettings-none'          => 'Күрсәтмәү',
'qbsettings-fixedleft'     => 'Сулда күчерелмәс',
'qbsettings-fixedright'    => 'Уңда күчерелмәс',
'qbsettings-floatingleft'  => 'Сулда йөзмә',
'qbsettings-floatingright' => 'Уңда йөзмә',

# Preferences page
'preferences'                   => 'Көйләнмәләр',
'mypreferences'                 => 'Көйләнмәләрем',
'prefs-edits'                   => 'Үзгәртүләр исәбе:',
'prefsnologin'                  => 'Кермәгәнсез',
'prefsnologintext'              => 'Кулланучы көйләнмәләрене үзгәртү өчен, сез <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} керергә]</span> тиешсез.',
'changepassword'                => 'Серсүзне үзгәртү',
'prefs-skin'                    => 'Күренеш',
'skin-preview'                  => 'Алдан карау',
'datedefault'                   => 'Баштагы көйләнмәләр',
'prefs-beta'                    => 'Бета-мөмкинчелекләр',
'prefs-datetime'                => 'Дата һәм вакыт',
'prefs-labs'                    => 'Сынаулы мөмкинчелекләр',
'prefs-personal'                => 'Шәхси мәгълүматлар',
'prefs-rc'                      => 'Соңгы үзгәртүләр',
'prefs-watchlist'               => 'Күзәтү исемлеге',
'prefs-watchlist-days'          => 'Күзәтү исемлегендә күрсәтелгән көн саны:',
'prefs-watchlist-days-max'      => '7 көннән артык түгел',
'prefs-watchlist-edits'         => 'Киңәйтелгән күзәтү исемлегендә үзгәртүләрнең иң югары исәбе:',
'prefs-watchlist-edits-max'     => 'Максимум сан: 1000',
'prefs-watchlist-token'         => 'Күзәтү исемлеге токены:',
'prefs-misc'                    => 'Башка көйләнмәләр',
'prefs-resetpass'               => 'Серсүзне үзгәртү',
'prefs-email'                   => 'E-mail көйләүләре',
'prefs-rendering'               => 'Күренеш',
'saveprefs'                     => 'Саклау',
'resetprefs'                    => 'Сакланмаган үзгәртүләрне бетерү',
'restoreprefs'                  => 'Баштагы көйләнмәләрне кире кайтару',
'prefs-editing'                 => 'Үзгәртү',
'prefs-edit-boxsize'            => 'Үзгәртү тәрәзәсенең зурлыгы',
'rows'                          => 'Юллар:',
'columns'                       => 'Баганалар:',
'searchresultshead'             => 'Эзләү',
'resultsperpage'                => 'Бер биткә туры килгән табылдыклар:',
'stub-threshold'                => '<a href="#" class="stub">Ясалма сылтамаларның</a> бизәлеше буенча чикләүләр (байтларда):',
'stub-threshold-disabled'       => 'Ябылган',
'recentchangesdays'             => 'Соңгы үзгәртүләрне күрсәтүче көннәр саны:',
'recentchangesdays-max'         => '( $1 {{PLURAL:$1|көннән}} дә артык булмаска тиеш)',
'recentchangescount'            => 'Төп буларак кулланучы үзгәртүләр саны:',
'prefs-help-recentchangescount' => 'Үз өченә үзгәртүләрне, битләрнең тарихын һәм язлу көндәлеген дә кертә.',
'prefs-help-watchlist-token'    => 'Әлеге юлны серсүз белән тутыру сезнең күзәтү исемлегегезнең RSS-тасмасын барлыкка китерәчәк. Мондагы серсүзне белүче һәрбер кеше сезнең күзәтү исемлегегезне карый ала, шуңа күрә автоматик рәвештә ясалган серсүзне кулланыгыз: $1',
'savedprefs'                    => 'Көйләнмәләрегез сакланды.',
'timezonelegend'                => 'Сәгать поясы:',
'localtime'                     => 'Җирле вакыт',
'timezoneuseserverdefault'      => 'Сервернең көйләнмәләре кулланылсын ($1)',
'timezoneuseoffset'             => 'Башка (күчерелүне күрсәтегез)',
'timezoneoffset'                => 'Күчерелү¹:',
'servertime'                    => 'Серверның вакыты:',
'guesstimezone'                 => 'Браузердан тутыру',
'timezoneregion-africa'         => 'Африка',
'timezoneregion-america'        => 'Америка',
'timezoneregion-antarctica'     => 'Антарктика',
'timezoneregion-arctic'         => 'Арктика',
'timezoneregion-asia'           => 'Азия',
'timezoneregion-atlantic'       => 'Атлантик океан',
'timezoneregion-australia'      => 'Австралия',
'timezoneregion-europe'         => 'Аурупа',
'timezoneregion-indian'         => 'Һинд океаны',
'timezoneregion-pacific'        => 'Тын океан',
'allowemail'                    => 'Башка кулланучылардан хатлар алырга рөхсәт ителсен',
'prefs-searchoptions'           => 'Эзләү көйләнмәләре',
'prefs-namespaces'              => 'Исемнәр мәйданы',
'defaultns'                     => 'Алайса менә бу исемнәр мәйданында эзләү',
'default'                       => 'килешү буенча',
'prefs-files'                   => 'Файллар',
'prefs-custom-css'              => 'Үземнең CSS',
'prefs-custom-js'               => 'Үземнең JS',
'prefs-common-css-js'           => 'Барлык бизәлешләр өчен гомуми CSS/JS:',
'prefs-reset-intro'             => 'Бу бит сезнең көйләнмәләрегезне бетерү өчен кулланыла. Бу эшне башкару нәтиҗәсендә сез яңадан үз көйләнмәләрне яңадан кайтара алмыйсыз.',
'prefs-emailconfirm-label'      => 'E-mail раслау',
'prefs-textboxsize'             => 'Үзгәртү тәрәзәсенең зурлыгы',
'youremail'                     => 'Электрон почта:',
'username'                      => 'Кулланучы исеме:',
'uid'                           => 'Кулланучының идентификаторы:',
'prefs-memberingroups'          => 'Төркем {{PLURAL:$1|әгъзасы}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Теркәлү вакыты:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'Чын исем:',
'yourlanguage'                  => 'Тел:',
'yourvariant'                   => 'Эчтәлекнең тел варианты:',
'yournick'                      => 'Яңа имзагыз:',
'prefs-help-signature'          => 'Бәхәслек битләрендә сезнең язмаларыгызны калдыру «<nowiki>~~~~</nowiki>» тамгалары куелу нәтиҗәсендә булырга тиеш.',
'badsig'                        => 'Имза дөрес түгел. HTML теглары тикшерегез.',
'badsiglength'                  => 'Имзагыз бигрәк озын.
Ул $1 {{PLURAL:$1|хәрефтән}} күбрәк булырга тиеш түгел.',
'yourgender'                    => 'Җенес:',
'gender-unknown'                => 'билгесез',
'gender-male'                   => 'Ир',
'gender-female'                 => 'Хатын',
'prefs-help-gender'             => 'Мәҗбүри түгел: Ул бары тик кайбер хатларда гына күренәчәк һәм бу мәгълүмат барлык кулланучыларга да билгеле булачак.',
'email'                         => 'Электрон почта',
'prefs-help-realname'           => 'Чын исемегез (кирәкми): аны күрсәтсәгез, ул битне үзгәртүче күрсәтү өчен файдалаячак.',
'prefs-help-email'              => 'Электрон почта адресын күрсәтү мәҗбүри түгел, ләкин әгәрдә сез үзегезнең серсүзне онытсагыз бу сезгә аны яңадан кайтарырга ярдәм итәчәк.',
'prefs-help-email-others'       => 'Ул шулай ук сезгә башка кулланучылар белән аралашырга ярдәм итчәк, шул ук вакытта сезнең почтагызның юлламасы күрсәтелмәячәк.',
'prefs-help-email-required'     => 'Электрон почта адресы кирәк.',
'prefs-info'                    => 'Гомуми мәгълүмат',
'prefs-i18n'                    => 'Интернационализация',
'prefs-signature'               => 'Имза',
'prefs-dateformat'              => 'Вакытың форматы',
'prefs-timeoffset'              => 'Вакыт билгеләнеше',
'prefs-advancedediting'         => 'Киңәйтелгән көйләүләр',
'prefs-advancedrc'              => 'Киңәйтелгән көйләүләр',
'prefs-advancedrendering'       => 'Киңәйтелгән көйләүләр',
'prefs-advancedsearchoptions'   => 'Киңәйтелгән көйләүләр',
'prefs-advancedwatchlist'       => 'Киңәйтелгән көйләүләр',
'prefs-displayrc'               => 'Күрсәтү көйләнмәләре',
'prefs-displaysearchoptions'    => 'Күрсәтү көйләнмәләре',
'prefs-displaywatchlist'        => 'Күрсәтү көйләнмәләре',
'prefs-diffs'                   => 'Юрамалар аермасы',

# User rights
'userrights'                     => 'Кулланучы хокуклары белән идарә итү',
'userrights-lookup-user'         => 'Кулланучы төркемнәре белән идарә итү',
'userrights-user-editname'       => 'Кулланучының исемен кертегез:',
'editusergroup'                  => 'Кулланучының төркемнәрен алмаштыру',
'editinguser'                    => "'''[[User:$1|$1]]''' кулланучысының хокукларын үзгәртү ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'Кулланучының төркемнәрен алмаштыру',
'saveusergroups'                 => 'Кулланучы төркемнәрен саклау',
'userrights-groupsmember'        => 'Әгъза:',
'userrights-groupsmember-auto'   => 'Билгесез әгъза:',
'userrights-groups-help'         => 'Сез бу кулланучының хокукларын үзгәртә алмыйсыз.
*Әгәр дә кулланучы исеме янда тамга торса, димәк бу кулланучы бирелгән төркемнең әгъзасы.
*Әгәр дә кулланучы исеме янда тамга тормаса, димәк бу кулланучы бирелгән төркемнең әгъзасы түгел.
*"*" тамгасы торса сез бу кулланучыны бу төркемнән бетерә алмыйсыз.',
'userrights-reason'              => 'Сәбәп:',
'userrights-no-interwiki'        => 'Сезнең башка викиларда кулланучыларның хокукларын үзгәртергә хокукларыгыз юк.',
'userrights-nodatabase'          => 'Бирелгән $1 базасы юк яисә  локаль булып тормый.',
'userrights-changeable-col'      => 'Сезнең тарафтан үзгәртә ала торган төркемнәр',
'userrights-unchangeable-col'    => 'Сезнең тарафтан үзгәртә алмый торган төркемнәр',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Төркем:',
'group-user'          => 'Кулланучылар',
'group-autoconfirmed' => 'Авторасланган кулланучы',
'group-bot'           => 'Ботлар',
'group-sysop'         => 'Идарәчеләр',
'group-bureaucrat'    => 'Бюрократлар',
'group-suppress'      => 'Тикшерүчеләр',
'group-all'           => '(барлык)',

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
'right-read'          => 'Битләрне карау',
'right-edit'          => 'Битләрне үзгәртү',
'right-createpage'    => 'битләр ясау (бәхәс булмаганнарын)',
'right-createtalk'    => 'бәхәс битен ясау',
'right-createaccount' => 'яңа кулланучы битен ясау',
'right-move'          => 'Битләрне күчерү',
'right-movefile'      => 'файлларның исемен алмаштыру',
'right-upload'        => 'файлларны йөкләү',
'right-delete'        => 'битләрне бетерү',
'right-editinterface' => 'Кулланучы интерфейсын үзгәртү',

# User rights log
'rightslog'      => 'Кулланучының хокуклары көндәлеге',
'rightslogentry' => '$1 кулланучысын $2 группасыннан $3 группасына күчерде',
'rightsnone'     => '(юк)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'бу битне үзгәртергә',
'action-createpage' => 'битләрне язырга',
'action-createtalk' => 'бәхәс битен ясарга',
'action-move'       => 'бу битне күчерерге',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|үзгәртү|үзгәртү}}',
'recentchanges'                     => 'Соңгы үзгәртүләр',
'recentchanges-legend'              => 'Соңгы үзгәртүләр көйләүләре',
'recentchangestext'                 => 'Бу биттә {{grammar:genitive|{{SITENAME}}}} проектының соңгы үзгәртүләре күрсәтелә.',
'recentchanges-feed-description'    => 'Бу агымда соңгы үзгәртүләрне күзәтү.',
'recentchanges-label-newpage'       => 'Бу үзгәртү белән яңа бит төзелде',
'recentchanges-label-minor'         => 'Бу кече үзгәртү',
'recentchanges-label-bot'           => 'Бу үзгәртү бот белән эшләнгән иде',
'recentchanges-label-unpatrolled'   => 'Үзгәртүне әлегә тикшермәгәннәр',
'rcnote'                            => 'Аста $4 $5 вакытынна соңгы {{PLURAL:$2|1|$2}} көн эчендә булган соңгы {{PLURAL:$1|1|$1}} үзгәртмә күрсәтелә:',
'rcnotefrom'                        => "Астарак '''$2''' башлап ('''$1''' кадәр) үзгәртүләр күрсәтелгән.",
'rclistfrom'                        => '$1 башлап яңа үзгәртүләрне күрсәт',
'rcshowhideminor'                   => 'кече үзгәртүләрне $1',
'rcshowhidebots'                    => 'ботларны $1',
'rcshowhideliu'                     => 'кергән кулланучыларны $1',
'rcshowhideanons'                   => 'кермәгән кулланучыларны $1',
'rcshowhidepatr'                    => 'тикшерелгән үзгәртүләрне $1',
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
'rc_categories'                     => 'Төркемнәрдә генә тора («|» бүлүче)',
'rc_categories_any'                 => 'Һәрбер',
'newsectionsummary'                 => '/* $1 */ яңа бүлек',
'rc-enhanced-expand'                => 'Ваклыкларны күрсәтү (JavaScript кирәк)',
'rc-enhanced-hide'                  => 'Ваклыкларны яшерү',

# Recent changes linked
'recentchangeslinked'          => 'Бәйләнешле үзгәртүләр',
'recentchangeslinked-feed'     => 'Бәйләнешле үзгәртүләр',
'recentchangeslinked-toolbox'  => 'Бәйләнешле үзгәртүләр',
'recentchangeslinked-title'    => '"$1" битенә бәйләнешле үзгәртүләр',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Күрсәтелгән вакытта сылташкан битләрнең үзгәртелмәләре юк иде.',
'recentchangeslinked-summary'  => "Бу күрсәтелгән бит белән сылталган (йә күрсәтелгән төркемгә керткән) битләрнең үзгәртелмәләре исемлеге.
[[Special:Watchlist|Күзәтү исемлегегезгә]] керә торган битләр '''калын'''.",
'recentchangeslinked-page'     => 'Битнең исеме:',
'recentchangeslinked-to'       => 'Моның урынына бу биткә бәйле булган битләрдәге үзгәртүләрне күрсәтү',

# Upload
'upload'                     => 'Файлны йөкләү',
'uploadbtn'                  => 'Файлны йөкләү',
'reuploaddesc'               => 'Файлны йөкләүгә кире кату',
'upload-tryagain'            => 'Яңартылган файлны җибәрү',
'uploadnologin'              => 'Сез хисап язмагызга кермәгәнсез',
'uploadnologintext'          => 'Файлны йөкләү өчен сез бу биткә [[Special:UserLogin|керергә]] тиешсез.',
'upload_directory_missing'   => '$1 Йөкләнү директориясе юк',
'upload_directory_read_only' => 'Моңа Сезнең хокукларыгыз юк һәм веб-сервер $1 папкасыны йөкли алмый.',
'uploaderror'                => 'Файлны йөкләүдә хата',
'upload-recreate-warning'    => "'''Игътибар: Мондый исемле файл бетерелгән яки исеме алмаштырылган '''",
'uploadtext'                 => "Бу форманы кулланып серверга файллар йөкли аласыз. Элегрәк йөкләнелгән файлларны карау өчен [[Special:FileList|йөкләнелгән файллар исемлегенә]] мәрәҗәгать итегез. Шулай ук ул [[Special:Log/upload|йөкләнмәләр исемлегенә]] һәм [[Special:Log/delete|бетерелгән файллар]] исемлегенә дә языла.

Файлны мәкаләгә йөкләү өчен Сез менә бу үрнәкләрне куллана аласыз:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Рәсем.jpg]]</nowiki></tt>''' файлның тулы юрамасын кую өчен;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Räsem.png|200px|thumb|left|тасвирламасы]]</nowiki></tt>'''  200 пиксельга кадәр киңлектәге  һәм текстның сул ягында, тасвирламасы белән;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''биттә файлны сүрәтләмичә, бары тик сылтамасын гына кую.",
'upload-permitted'           => 'Рөхсәт ителгән файл төрләре:$1',
'upload-preferred'           => 'Мөмкин булган файл төрләре:$1',
'upload-prohibited'          => 'Тыелган файл төрләре:$1',
'uploadlog'                  => 'Йөкләү көндәлеге',
'uploadlogpage'              => 'Йөкләү көндәлеге',
'uploadlogpagetext'          => 'Аста яңа йөкләнелгән файллар исемлеге бирелә.
Шулай ук [[Special:NewFiles|яңа файллар галлереясын]] карагыз',
'filename'                   => 'Файл исеме',
'filedesc'                   => 'Кыска тасвирлама',
'fileuploadsummary'          => 'Үзгәртүләр тасвирламасы:',
'filereuploadsummary'        => 'Файлдагы үзгәртүләр:',
'filestatus'                 => 'Тарату хокуклары:',
'filesource'                 => 'Чыганагы:',
'uploadedfiles'              => 'Йөкләнелгән файллар',
'ignorewarning'              => 'Белдерүне кире кагу һәм файлны саклау',
'ignorewarnings'             => 'Белдерүне кире кагу',
'minlength1'                 => 'Файлның исеме бер генә хәрефтән булса да торырга тиеш.',
'illegalfilename'            => 'файлның исеме  «$1»  куллануга ярамаган символлардан тора. Зинһар, файлның исемен алыштырыгыз һәм яңадан куеп карагыз.',
'badfilename'                => 'Файлның исеме $1 исеменә үзгәртелде.',
'filetype-mime-mismatch'     => 'Файлның кинәйтелмәсе «.$1» аның MIME-төренә туры килми ($2).',
'filetype-badmime'           => 'MIME-төре «$1» булган файллар, йөкләнмәячәк.',
'filetype-bad-ie-mime'       => 'Файлны йөкләргә мөмкин түгел, чөнки Internet Explorer аны «$1» дип кабул итәчәк.',
'filetype-unwanted-type'     => "'''\".\$1\"''' — тыелган файл төре.
{{PLURAL:\$3|Мөмкин булган файл төре булып|Мөмкин булган файл төре:}} \$2.",
'filetype-banned-type'       => '\'\'\'".$1"\'\'\' — {{PLURAL:$4|тыелган файл төре|тыелган файллар төре}}.
{{PLURAL:$3|Киңәйтелгән файл төре булып|Киңәйтелгән  файл төрләре:}} $2.',
'filetype-missing'           => "Файлның киңәйтелмәсе юк ''(мәсәлән,«.jpg»)''.",
'empty-file'                 => 'Сезнең тарафтан җибәрелгән файл буш.',
'file-too-large'             => 'Сезнең тарафтан җибәрелгән файл артык зур.',
'filename-tooshort'          => 'Файлның исеме артык кыска.',
'filetype-banned'            => 'Бу файл төре тыелган.',
'verification-error'         => 'Бу файл әлегә тикшерү узмаган.',
'illegal-filename'           => 'Мондый файл исеменә рөхсәт юк',
'savefile'                   => 'Файлны саклау',
'uploadedimage'              => '«[[$1]]» йөкләнгән',
'overwroteimage'             => '«[[$1]]» файлының яңа юрамасы йөкләнелде',
'uploaddisabled'             => 'Йөкләү тыелган',
'copyuploaddisabled'         => 'URL адресы буенча йөкләү ябылган.',
'uploadfromurl-queued'       => 'Сезнең йөкләвегез чиратка куелды.',
'uploaddisabledtext'         => 'Файлларны йөкләү ябылган.',
'upload-source'              => 'Файлның чыганагы',
'sourcefilename'             => 'Файлның чыганагы:',
'sourceurl'                  => 'Чыганакның URL адресы:',
'destfilename'               => 'Файлның яңа исеме:',
'upload-maxfilesize'         => 'Файлның максималь зурлыгы: $1',
'upload-description'         => 'Файлның тасвирламасы',
'upload-options'             => 'Йөкләү параметрлары',
'watchthisupload'            => 'Бу файлны күзәтү',
'filewasdeleted'             => 'Мондый исемле файл бетерелгән булган инде. Зинһар,яңадан йөкләү алдыннан $1 карагыз',
'filename-bad-prefix'        => "Файлның исеме '''«$1»''' дип башлана. Зинһар, файлны тасвирлаучы исем бирегез.",
'filename-prefix-blacklist'  => ' #<!-- ничек бар шулай калдырыгыз --> <pre>
# Синтаксис төбәндәгечә:
#   *  «#» дип башланган барлык нәрсә дә комментарий дип аталачак
#   * Һәрбер буш рәт — файлның исеменең префиксы, цифрлы камера бирүче исем
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # кайсыбер кәрәзле телефоннар
IMG # барлык
JD # Jenoptik
MGP # Pentax
PICT # төрле
 #</pre> <!-- ничек бар шулай калдырыгыз -->',
'upload-success-subj'        => 'Йөкләү әйбәт үтте',

# HTTP errors
'http-read-error' => 'HTTP укуда хата.',

'license'            => 'Лицензиясе:',
'license-header'     => 'Лицензиясе',
'nolicense'          => 'Юк',
'license-nopreview'  => '(Алдан карау мөмкин түгел)',
'upload_source_file' => '(сезнең санактагы файл)',

# Special:ListFiles
'imgfile'               => 'файл',
'listfiles'             => 'Сүрәтләр исемлеге',
'listfiles_thumb'       => 'Миниатюра',
'listfiles_date'        => 'Вакыт',
'listfiles_name'        => 'Ат',
'listfiles_user'        => 'Кулланучы',
'listfiles_size'        => 'Үлчәм',
'listfiles_description' => 'Тасвир',
'listfiles_count'       => 'Юрамалар',

# File description page
'file-anchor-link'          => 'Файл',
'filehist'                  => 'Файлның тарихы',
'filehist-help'             => 'Датага/сәгатькә, шул вакытта битнең нинди булганлыгын күрү өчен басыгыз.',
'filehist-deleteall'        => 'Барысын да юк ит',
'filehist-deleteone'        => 'бетерү',
'filehist-revert'           => 'кайтару',
'filehist-current'          => 'хәзерге',
'filehist-datetime'         => 'Дата/вакыт',
'filehist-thumb'            => 'Миниатюра',
'filehist-thumbtext'        => '$1 көнне булган версиянең эскизы',
'filehist-nothumb'          => 'Миниатюрасы юк',
'filehist-user'             => 'Кулланучы',
'filehist-dimensions'       => 'Зурлык',
'filehist-filesize'         => 'Файлның зурлыгы',
'filehist-comment'          => 'Искәрмә',
'filehist-missing'          => 'Файл табылмады',
'imagelinks'                => 'Файлны куллану',
'linkstoimage'              => 'Бу файлга әлеге {{PLURAL:$1|бит|$1 бит}} сылтый:',
'nolinkstoimage'            => 'Бу файлга сылтаган битләр юк.',
'duplicatesoffile'          => '{{PLURAL:$1|Әлеге $1 файл }} астагы файлның күчерелмәсе булып тора ([[Special:FileDuplicateSearch/$2|тулырак]]):',
'sharedupload'              => "Бу файл $1'дан һәм башка проектларда кулланырга мөмкин.",
'sharedupload-desc-here'    => "Бу файл $1'дан һәм башка проектларда кулланырга мөмкин. Файл турында [$2 мәгълүмат ] аста бирелгән.",
'filepage-nofile'           => 'Мондый исемле файл юк.',
'filepage-nofile-link'      => 'Мондый исемле файл  юк. Сез аны [$1 йөкли аласыз].',
'uploadnewversion-linktext' => 'Бу файлның яңа юрамасын йөкләү',
'shared-repo-from'          => '$1 дән',
'shared-repo'               => 'гомуми саклагыч',

# File reversion
'filerevert'         => '$1 юрамасына кире кайту',
'filerevert-legend'  => 'Файлның иске юрамасын кире кайтару',
'filerevert-comment' => 'Сәбәп:',
'filerevert-submit'  => 'Кире кайтару',

# File deletion
'filedelete'                  => '$1 —  бетерү',
'filedelete-legend'           => 'Файлны бетерү',
'filedelete-comment'          => 'Сәбәп:',
'filedelete-submit'           => 'Бетерү',
'filedelete-reason-otherlist' => 'Башка сәбәп',

# MIME search
'mimesearch' => 'MIME эзләү',
'mimetype'   => 'MIME-тип:',
'download'   => 'йөкләү',

# Unwatched pages
'unwatchedpages' => 'Беркемдә күзәтмәүче  битләр',

# List redirects
'listredirects' => 'Юнәлтүләр исемлеге',

# Unused templates
'unusedtemplates' => 'Кулланылмаган үрнәкләр',

# Random page
'randompage' => 'Очраклы бит',

# Random redirect
'randomredirect' => 'Очраклы биткә күчү',

# Statistics
'statistics'                   => 'Хисапнамә',
'statistics-header-pages'      => 'Битләр хисапнамәсе',
'statistics-header-edits'      => 'Үзгәртүләр хисапнамәсе',
'statistics-header-views'      => 'Караулар хисапнамәсе',
'statistics-header-users'      => 'Кулланучылар буенча хисапнамә',
'statistics-header-hooks'      => 'Башка хисапнамәләр',
'statistics-articles'          => 'Мәкаләләр саны',
'statistics-pages'             => 'Битләр саны',
'statistics-pages-desc'        => 'Барлык вики, бәхәс, күчерү һәм башка битләрне дә истә тотып.',
'statistics-files'             => 'Йөкләнелгән файллар',
'statistics-edits'             => '{{grammar:genitive|{{SITENAME}}}} проекты ачылганнан бирле булган барлык үзгәртүләр исәбе',
'statistics-edits-average'     => 'Бер биткә уртача үзгәртүләр исәбе',
'statistics-views-total'       => 'Барлык каралган битләр',
'statistics-views-peredit'     => 'Үзгәртүләргә карау',
'statistics-users'             => 'Теркәлгән [[Special:ListUsers|кулланучылар]]',
'statistics-users-active'      => 'Актив кулланучылар',
'statistics-users-active-desc' => '{{PLURAL:$1|$1 көн }} өчендә нинди дә булса үзгәртүләр керткән кулланучылар',
'statistics-mostpopular'       => 'Иң күп каралучы битләр',

'disambiguations'     => 'Күп мәгънәле сүзләр турында битләр',
'disambiguationspage' => 'Template:disambig',

'doubleredirects' => 'Икеләтә юнәлтүләр',

'brokenredirects'        => 'Бәйләнешсез юнәлтүләр',
'brokenredirectstext'    => 'Бу юнәлтүләр булмаган битләргә сылтыйлар:',
'brokenredirects-edit'   => 'үзгәртү',
'brokenredirects-delete' => 'бетерү',

'withoutinterwiki'        => 'Телләрара сылтамасыз битләр',
'withoutinterwiki-legend' => 'Өстәлмә',
'withoutinterwiki-submit' => 'Күрсәтү',

'fewestrevisions' => 'Аз үзгәртүләр белән битләр',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|байт}}',
'ncategories'             => '$1 {{PLURAL:$1|төркем}}',
'nlinks'                  => '$1 {{PLURAL:$1|сылтама}}',
'nmembers'                => '$1 {{PLURAL:$1|әгъза}}',
'lonelypages'             => 'Үксез битләр',
'uncategorizedpages'      => 'Төркемләнмәгән битләр',
'uncategorizedcategories' => 'Төркемләнмәгән төркемнәр',
'uncategorizedimages'     => 'Төркемләнмәгән сүрәтләр',
'uncategorizedtemplates'  => 'Төркемләнмәгән үрнәкләр',
'unusedcategories'        => 'Кулланмаган төркемнәр',
'unusedimages'            => 'Кулланмаган сүрәтләр',
'popularpages'            => 'Популяр битләр',
'wantedcategories'        => 'Зарур төркемнәр',
'wantedpages'             => 'Зарур битләр',
'wantedfiles'             => 'Кирәкле файллар',
'wantedtemplates'         => 'Кирәкле үрнәкләр',
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
'protectedtitles'         => 'Тыелган исемнәр',
'listusers'               => 'Кулланучылар исемлеге',
'newpages'                => 'Яңа битләр',
'newpages-username'       => 'Кулланучы:',
'ancientpages'            => 'Иң иске битләр',
'move'                    => 'Күчерү',
'movethispage'            => 'Бу битне күчерү',
'nopagetitle'             => 'Мондый бит юк',
'nopagetext'              => 'Күрсәтелгән бит юк.',
'pager-newer-n'           => '{{PLURAL:$1|1 яңарак|$1 яңарак}}',
'pager-older-n'           => '{{PLURAL:$1|1 искерәк|$1 искерәк}}',
'suppress'                => 'Яшерү',

# Book sources
'booksources'               => 'Китап чыганаклары',
'booksources-search-legend' => 'Китап чыганакларыны эзләү',
'booksources-go'            => 'Башкару',
'booksources-text'          => 'Әлеге биттә күрсәтелгән сылтамалар ярәмендә сезнең кызыксындырган китап буенча өстәмә мәгълүматлар табарга мөмкин. Болар интернет-кибетләр һәм китапханә җыентыгында эзләүче системалар.',
'booksources-invalid-isbn'  => 'Бирелгән ISBN саны бәлки хаталдыр. Зинһар, бирелгән саннарны яңадан тикшерегез.',

# Special:Log
'specialloguserlabel'  => 'Кулланучы:',
'speciallogtitlelabel' => 'Башлам:',
'log'                  => 'Көндәлекләр',
'all-logs-page'        => 'Барлык көндәлекләр',
'alllogstext'          => '{{SITENAME}} сәхифәсенең гомуми көндәлекләре исемлеге.
Сез нәтиҗәләрне көндәлек төре, кулланучы исеме (хәреф зурлыгын истә тотыгыз) яки куззаллаган бит (шулай ук хәреф зурлыгын истә тотыгыз) буенча тәртипкә салырга мөмкин.',
'logempty'             => 'Кирәкле язмалар көндәлектә юк.',

# Special:AllPages
'allpages'          => 'Барлык битләр',
'alphaindexline'    => '$1 битеннән $2 битенә кадәр',
'nextpage'          => 'Алдагы бит ($1)',
'prevpage'          => 'Алдагы бит ($1)',
'allpagesfrom'      => 'Моңа башланучы битләрне чыгару:',
'allpagesto'        => 'Монда чыгаруны туктату:',
'allarticles'       => 'Барлык битләр',
'allinnamespace'    => '«$1» исемнәр мәйданындагы барлык битләр',
'allnotinnamespace' => 'Барлык битләр («$1» исемнәр мәйданы исәпкә алынмады)',
'allpagesprev'      => 'Алдагы',
'allpagesnext'      => 'Киләсе',
'allpagessubmit'    => 'Башкару',
'allpagesprefix'    => 'Алкушымчалы битләрне күрсәтү:',

# Special:Categories
'categories'                    => 'Төркемнәр',
'categoriespagetext'            => '{{PLURAL:$1|Әлеге төркем үз өченә|Әлеге төркемнәр  үз өченә}}   битләрне һәм медиа-файлларны ала.
Аста [[Special:UnusedCategories|кулланылмаган төркемнәр]] кәрсәтелгән.
Шулай ук  [[Special:WantedCategories|кирәкле төркемнәр исемлегендә]] карагыз.',
'special-categories-sort-count' => 'исәп буенча тәртипләү',
'special-categories-sort-abc'   => 'әлифба буенча тәртипләү',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'кертем',

# Special:LinkSearch
'linksearch'      => 'Тышкы сылтамалар',
'linksearch-pat'  => 'Эзләү өчен үрнәк:',
'linksearch-ns'   => 'Исемнәр мәйданы:',
'linksearch-ok'   => 'Эзләү',
'linksearch-line' => '$2 мәкаләсеннән $1 мәкаләгә сылтама',

# Special:ListUsers
'listusers-submit'   => 'Күрсәтү',
'listusers-noresult' => 'Кулланучыларны табылмады.',
'listusers-blocked'  => '(тыелган)',

# Special:ActiveUsers
'activeusers'            => 'Актив кулланучылар исемлеге',
'activeusers-hidebots'   => 'Ботларны яшер',
'activeusers-hidesysops' => 'Идарәчеләрне яшер',
'activeusers-noresult'   => 'Кулланучылар табылмады.',

# Special:Log/newusers
'newuserlogpage'          => 'Кулланучыларны теркәү көндәлеге',
'newuserlogpagetext'      => 'Яңа теркәлгән кулланучылар исемлеге',
'newuserlog-byemail'      => 'серсүз электрон почта аша җибәрелде',
'newuserlog-create-entry' => 'Яңа кулланучы',

# Special:ListGroupRights
'listgrouprights'          => 'Кулланучы төркемнәренең хокуклары',
'listgrouprights-group'    => 'Төркем',
'listgrouprights-rights'   => 'Хокуклар',
'listgrouprights-helppage' => 'Help:Төркемнәрнең хокуклары',
'listgrouprights-members'  => '(төркем исемлеге)',

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
'emailccme'       => 'Миңа хәбәрнең күчермәсене җибәрелсен.',
'emailccsubject'  => '$1 өчен хәбәрегезнең күчермәсе: $2',
'emailsent'       => 'Хат җибәрелгән',
'emailsenttext'   => 'E-mail хатыгыз җиберелде.',

# Watchlist
'watchlist'         => 'Күзәтү исемлегем',
'mywatchlist'       => 'Күзәтү исемлегем',
'nowatchlist'       => 'Күзәтү исемлегегездә битләр юк.',
'watchnologin'      => 'Кермәдегез',
'watchnologintext'  => 'Күзәтү исемлегегезне үзгәртү өчен сез [[Special:UserLogin|керергә]] тиешсез.',
'addedwatchtext'    => "\"[[:\$1]]\" бите [[Special:Watchlist|күзәтү исемлегегезгә]] өстәлде.
Бу биттә һәм аның бәхәслегендә барлык булачак үзгәртүләр шунда күрсәтелер, һәм, [[Special:RecentChanges|соңгы үзгәртүләр]] исемлегендә бу битне җиңелрәк табу өчен, ул '''калын мәтен''' белән күрсәтелер.",
'removedwatchtext'  => '«[[:$1]]» бите [[Special:Watchlist|сезнең күзәтү исемлегеннән]] бетерелде.',
'watch'             => 'Күзәтү',
'watchthispage'     => 'Бу битне күзәтү',
'unwatch'           => 'Күзәтмәү',
'unwatchthispage'   => 'Күзәтүне туктат',
'notanarticle'      => 'Мәкалә түгел',
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
'enotif_body'                  => 'Хөрмәтле $WATCHINGUSERNAME,

«{{SITENAME}}» проектының «$PAGETITLE» бите  $PAGEEDITOR  тарафыннан  $PAGEEDITDATE  көнне  $CHANGEDORCREATED. Битне карар өчен $PAGETITLE_URL  буенча узыгыз.

$NEWPAGE

Үзгәртүнең кыска эчтәлеге: $PAGESUMMARY $PAGEMINOREDIT

Үзгәртүчегә язу:
эл. почта $PAGEEDITOR_EMAIL
вики $PAGEEDITOR_WIKI

Бу биткә кермәсәгез, аның башка үзгәртүләре турында хат җибәрелмәячәк. Шулай ук сез күзәтү исемлегегездә булган битләр өчен хәбәр бирү флагын алып куя аласыз.

             {{grammar:genitive|{{SITENAME}}}} хәбәр бирү системасы

--
Хәбәр итүләр көйләүләрен үзгәртү:
{{canonicalurl:{{#special:Preferences}}}}

Күзәтү исемлеге көйләүләрен үзгәртү:
{{canonicalurl:{{#special:EditWatchlist}}}}

Битне сезнең күзәтү исемлегездән бетерү:
$UNWATCHURL

Элемтә һәм ярдәм:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Битне бетерү',
'confirm'                => 'Раслау',
'excontent'              => 'эчтәлек: «$1»',
'excontentauthor'        => 'эчтәлеге: "$1" (бердәнбер үзгәртүче "[[Special:Contributions/$2|$2]]" иде)',
'exbeforeblank'          => 'чистартуга кадәр булган эчтәлек: «$1»',
'exblank'                => 'бит буш иде',
'delete-confirm'         => '«$1» бетерү',
'delete-legend'          => 'Бетерү',
'historywarning'         => "'''Кисәтү''': сез бетерергә теләгән биттә үзгәртү тарихы бар, ул $1дән {{PLURAL:$1|юрамалар}}:",
'confirmdeletetext'      => 'Сез бу битнең (яки рәсемнең) тулысынча бетерелүен сорадыгыз.
Зинһар, моны чыннан да эшләргә теләгәнегезне, моның нәтиҗәләрен аңлаганыгызны һәм [[{{MediaWiki:Policy-url}}]] бүлегендәге кагыйдәләр буенча эшләгәнегезне раслагыз.',
'actioncomplete'         => 'Гамәл башкарган',
'actionfailed'           => 'Эш башкарылмаган',
'deletedtext'            => '«$1» бетерелгән инде.<br />
Соңгы бетерелгән битләрне күрер өчен, $2 карагыз.',
'deletedarticle'         => '«[[$1]]» бетерелде',
'suppressedarticle'      => '«[[$1]]» күрсәтелмәде',
'dellogpage'             => 'Бетерү көндәлеге',
'deletionlog'            => 'бетерү көндәлеге',
'deletecomment'          => 'Сәбәп:',
'deleteotherreason'      => 'Башка/өстәмә сәбәп:',
'deletereasonotherlist'  => 'Башка сәбәп',
'deletereason-dropdown'  => '* Бетерүнең сәбәпләре
** вандаллык
** автор соравы буенча
** автор хокукларын бозу',
'delete-edit-reasonlist' => 'Сәбәпләр исемлеген үзгәртү',

# Rollback
'rollback_short' => 'Кире кайтару',
'rollbacklink'   => 'кире кайтару',
'editcomment'    => "Үзгәртү өчен тасвир: \"''\$1''\".",
'revertpage'     => '[[Special:Contributions/$2|$2]] үзгәртүләре ([[User talk:$2|бәхәс]])  [[User:$1|$1]] юрамасына кадәр кире кайтарылды',

# Protect
'protectlogpage'              => 'Яклану көндәлеге',
'protectedarticle'            => '«[[$1]]» якланган',
'modifiedarticleprotection'   => '"[[$1]]" бите өчен яклау дәрәҗәсе үзгәртелде',
'unprotectedarticle'          => '«[[$1]]» битеннән яклау алынды',
'movedarticleprotection'      => 'яклау көйләнмәләрен «[[$2]]» битеннән «[[$1]]» битенә күчерде',
'protect-title'               => '«$1» өчен яклау дәрәҗәсен билгеләү',
'prot_1movedto2'              => '«[[$1]]» бите «[[$2]]» битенә күчерелде',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Битне яклау турында раслагыз',
'protectcomment'              => 'Сәбәп:',
'protectexpiry'               => 'Бетә:',
'protect_expiry_invalid'      => 'Яклау бетү вакыты дөрес түгел.',
'protect_expiry_old'          => 'Яклау бетү көне узган көнгә куелган.',
'protect-unchain-permissions' => 'Өстәмә яклау чараларын ачу',
'protect-text'                => "Биредә сез '''$1''' бите өчен яклау дәрәҗәсене карый һәм үзгәрә аласыз.",
'protect-locked-access'       => "Хисап язмагызга битләрнең яклау дәрәҗәсен үзгәртү өчен хак җитми. '''$1''' битенең хәзерге көйләүләре:",
'protect-cascadeon'           => 'Бу бит якланган, чөнки ул әлеге каскадлы яклаулы {{PLURAL:$1|биткә|битләргә}} керә. Сез бу битнең яклау дәрәҗәсен үзгәртә аласыз, әмма каскадлы яклау үзгәрмәячәк.',
'protect-default'             => 'Яклаусыз',
'protect-fallback'            => '«$1»нең рөхсәте кирәк',
'protect-level-autoconfirmed' => 'Яңа һәм теркәлмәгән кулланучыларны кысу',
'protect-level-sysop'         => 'Идарәчеләр генә',
'protect-summary-cascade'     => 'каскадлы',
'protect-expiring'            => '$1 үтә (UTC)',
'protect-expiry-indefinite'   => 'Вакыт чикләнмәгән',
'protect-cascade'             => 'Бу биткә кергән битләрне яклау (каскадлы яклау)',
'protect-cantedit'            => 'Сез бу битнең яклау дәрәҗәсене үзгәрә алмыйсыз, чөнки сездә аны үзгәртергә рөхсәтегез юк.',
'protect-othertime'           => 'Башка вакыт:',
'protect-othertime-op'        => 'башка вакыт',
'protect-otherreason-op'      => 'Башка сәбәп',
'protect-dropdown'            => '* Гади яклау сәбәпләре
** вандаллык
** зур спам
** кирәксез үзгәртүләр саны
** популяр бит',
'protect-edit-reasonlist'     => 'Сәбәпләр исемлеген үзгәртү',
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
'restriction-upload' => 'Йөкләү',

# Restriction levels
'restriction-level-sysop'         => 'тулы яклау',
'restriction-level-autoconfirmed' => 'өлешчә яклау',
'restriction-level-all'           => 'барлык дәрәҗәләр',

# Undelete
'undelete'                  => 'Бетерелгән битләрне карау',
'undeletepage'              => 'Бетерелгән битләрне карау һәм торгызу',
'viewdeletedpage'           => 'Бетерелгән битләрне карау',
'undelete-fieldset-title'   => 'Юрамаларны кайтару',
'undeletehistory'           => 'Бу битне торгызсагыз, аның үзгәртү тарихы да тулысынча торгызылачак.
Бетерелүдән соң шундый ук исемле бит төзелгән булса, торгызылган үзгәртүләр яңа үзгәртүләр алдына куелачак.',
'undeletebtn'               => 'Торгызу',
'undeletelink'              => 'карау/торгызу',
'undeleteviewlink'          => 'карау',
'undeletereset'             => 'Ташлату',
'undeleteinvert'            => 'Киресен сайлау',
'undeletecomment'           => 'Сәбәп:',
'undeletedarticle'          => '«[[$1]]» торгызылды',
'undeletedrevisions'        => '{{PLURAL:$1|1 үзгәртү|$1 үзгәртү}} торгызылды',
'undelete-search-submit'    => 'Эзләү',
'undelete-error-long'       => 'Файлны торгызу вакытында хаталар чыкты:

$1',
'undelete-show-file-submit' => 'Әйе',

# Namespace form on various pages
'namespace'                     => 'Исемнәр мәйданы:',
'invert'                        => 'Киресен сайлау',
'namespace_association'         => 'Бәйле тирәлек',
'tooltip-namespace_association' => 'Сайланган бәйле исемнәр тирәлегенә караган мәкаләләр исемлеген кабызу өчен элеге урынга тамганы куегыз',
'blanknamespace'                => '(Төп)',

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
'sp-contributions-blocklog'    => 'тыю көндәлеге',
'sp-contributions-uploads'     => 'йөкләүләр',
'sp-contributions-logs'        => 'көндәлекләр',
'sp-contributions-talk'        => 'бәхәс',
'sp-contributions-search'      => 'Кертемне эзләү',
'sp-contributions-username'    => 'Кулланучының IP адресы яки исеме:',
'sp-contributions-submit'      => 'Эзләргә',

# What links here
'whatlinkshere'            => 'Бирегә нәрсә сылтый',
'whatlinkshere-title'      => '$1 битенә сылтый торган битләр',
'whatlinkshere-page'       => 'Бит:',
'linkshere'                => "'''[[:$1]]''' битенә чираттагы битләр сылтый:",
'nolinkshere'              => "'''[[:$1]]''' битенә башка битләр сылтамыйлар.",
'isredirect'               => 'юнәлтү бите',
'istemplate'               => 'кертүләр',
'isimage'                  => 'файл сылтамасы',
'whatlinkshere-prev'       => '{{PLURAL:$1|алдагы|алдагы $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|чираттагы|чираттагы $1}}',
'whatlinkshere-links'      => '← сылтамалар',
'whatlinkshere-hideredirs' => 'юнәлтүләрне $1',
'whatlinkshere-hidetrans'  => 'кертүләрне $1',
'whatlinkshere-hidelinks'  => 'сылтамаларны $1',
'whatlinkshere-hideimages' => 'рәсем сылтамаларын $1',
'whatlinkshere-filters'    => 'Фильтрлар',

# Block/unblock
'blockip'                    => 'Кулланучыны тыю',
'blockip-title'              => 'Кулланучыны тыю',
'blockip-legend'             => 'Кулланучыны тыю',
'ipadressorusername'         => 'IP адресы яки кулланучы исеме:',
'ipbexpiry'                  => 'Бетә:',
'ipbreason'                  => 'Сәбәп:',
'ipbreasonotherlist'         => 'Башка сәбәп',
'ipbreason-dropdown'         => '* Кысуның гадәттәге сәбәпләре
** Ялган мәгълүмат кертү
** Битләрнең эчтәлеген сөртү
** Тышкы сайтларга спам-сылтамалар
** Мәгънәсез текст/чүп өстәү
** Кулланучыларны эзәрлекләү/янаулар
** Берничә хисап язмасы белән исәпләшмәү
** Кулланучы исеменең яраксыз булуы',
'ipbenableautoblock'         => 'Кулланучы кулланган IP адресларын автоматик рәвештә тыю',
'ipbsubmit'                  => 'Бу кулланучыны тыю',
'ipbother'                   => 'Башка вакыт:',
'ipboptions'                 => '2 сәгать:2 hours,1 көн:1 day,3 көн:3 days,1 атна:1 week,2 атна:2 weeks,1 ай:1 month,3ай:3 months,6 ай:6 months,1 ел:1 year,чикләнмәгән:infinite',
'ipbotheroption'             => 'башка',
'badipaddress'               => 'Ялгыш IP адресы',
'blockipsuccesssub'          => 'Тыю башкарылган',
'ipb-unblock-addr'           => '$1 кулланучысын тыюдан азат итү',
'ipb-unblock'                => 'Кулланучы яки IP адресы тыюдан азат итү',
'unblockip'                  => 'Кулланучыны тыюдан азат итү',
'ipusubmit'                  => 'Бу тыюны туктату',
'ipblocklist'                => 'Тыелган кулланучылар',
'ipblocklist-submit'         => 'Эзләү',
'infiniteblock'              => 'билгеле бер вакытсыз',
'blocklink'                  => 'тыю',
'unblocklink'                => 'тыюдан азат итү',
'change-blocklink'           => 'тыюны үзгәртү',
'contribslink'               => 'кертем',
'blocklogpage'               => 'Тыю көндәлеге',
'blocklogentry'              => '[[$1]] $2 вакытка тыелды $3',
'unblocklogentry'            => '$1 кулланучысының тыелу вакыты бетте',
'block-log-flags-nocreate'   => 'яңа хисап язмасы теркәү тыелган',
'block-log-flags-noemail'    => 'хат җибәрү тыелган',
'block-log-flags-hiddenname' => 'кулланучының исеме яшерелгән',
'proxyblocker'               => 'Прокси тыю',
'proxyblocksuccess'          => 'Эшләнде',
'sorbsreason'                => 'Сезнең IP адресыгыз DNSBLда ачык прокси дип санала.',

# Developer tools
'unlockbtn' => 'Мәгълүматлар базасына язу мөмкинлеген кайтару',

# Move page
'move-page'                 => '$1 — исемен алмаштыру',
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
'movenologin'               => 'Кермәдегез',
'movenotallowed'            => 'Сездә мәкаләләрне күчерү хокуклары юк.',
'newtitle'                  => 'Яңа башлам:',
'move-watch'                => 'Бу битне күзәтү',
'movepagebtn'               => 'Битне күчерү',
'pagemovedsub'              => 'Бит күчерелде',
'movepage-moved'            => "'''«$1» бите «$2» битенә күчерелде'''",
'movepage-moved-redirect'   => 'Юнәлтү ясалды.',
'movepage-moved-noredirect' => 'Юнәлтүне ясау тыелды',
'articleexists'             => 'Мондый исемле бит бар инде, яисә мондый исем рөхсәт ителми.
Зинһар башка исем сайлагыз.',
'talkexists'                => "'''Битнең үзе күчерелде, әмма бәхәс бите күчерелми калды, чөнки шундый исемле бит бар инде. Зинһар, аларны үзегез кушыгыз.'''",
'movedto'                   => 'күчерелгән:',
'movetalk'                  => 'Бәйләнешле бәхәс битен күчерү',
'1movedto2'                 => '«[[$1]]» бите «[[$2]]» битенә күчерелде',
'1movedto2_redir'           => '«[[$1]]» бите «[[$2]]» битенә юнәлтү өстеннән күчте',
'move-redirect-suppressed'  => 'юнәлтү тыелды',
'movelogpage'               => 'Күчерү көндәлеге',
'movereason'                => 'Сәбәп:',
'revertmove'                => 'кире кайту',
'delete_and_move'           => 'Бетерү һәм исемен алмаштыру',
'delete_and_move_reason'    => 'Күчерүне мөмкин итәр өчен бетерелде',
'move-leave-redirect'       => 'Юнәлтү калдырылсын',

# Export
'export'            => 'Битләрне чыгаруы',
'export-submit'     => 'Экспортлау',
'export-addcattext' => 'Бу төркемнән битләр өстәү:',
'export-addcat'     => 'Өстәү',
'export-addns'      => 'Өстәү',
'export-download'   => 'Файл буларак саклау',

# Namespace 8 related
'allmessages'                   => 'Система хәбәрләре',
'allmessagesname'               => 'Исем',
'allmessagesdefault'            => 'Баштан ук куелган текс',
'allmessagestext'               => 'Бу исемлек MediaWiki исемнәр мәйданында булган система хәбәрләренең исемлеге.
Гомуми MediaWiki локализациясендә катнашырга теләсәгез, зинһар [//www.mediawiki.org/wiki/Localisation MediaWiki Локализациясе] һәм [//translatewiki.net translatewiki.net] сәхифәләрне кулланыгыз.',
'allmessages-filter-legend'     => 'Фильтр',
'allmessages-filter-unmodified' => 'Үзгәртелмәгән',
'allmessages-filter-all'        => 'Барысы',
'allmessages-filter-modified'   => 'Үзгәртелгән',
'allmessages-language'          => 'Тел:',
'allmessages-filter-submit'     => 'Күчү',

# Thumbnails
'thumbnail-more'  => 'Зурайту',
'filemissing'     => 'Файл табылмады',
'thumbnail_error' => 'Кечкенә сүрәт төзүе хатасы: $1',

# Special:Import
'import'                     => 'Битләр кертү',
'importinterwiki'            => 'Викиара кертү',
'import-interwiki-text'      => 'Викины һәм кертелүче битнең исемен языгыз.
Үзгәртүләр вакыты һәм аның авторлары сакланачак.
Бөтен викиара күчерүләр [[Special:Log/import|махсус журналда]] сакланачак.',
'import-interwiki-source'    => 'Вики-чыганак/бит:',
'import-interwiki-history'   => 'Бу битнең барлык үзгәртү тарихын күчермәләү',
'import-interwiki-templates' => 'Барлык үрнәкләрне кертү',
'import-interwiki-submit'    => 'Импортлау',
'import-interwiki-namespace' => 'Исемнәр тирәлеге:',
'import-upload-filename'     => 'Файл исеме:',
'import-comment'             => 'Искәрмә:',
'importtext'                 => 'Зинһар өчен, битне күчерү өчен [[Special:Export|махсус корал]] кулланыгыз. Файлны дискка саклагыз, аннан соң монда йөкләгез.',
'importstart'                => 'Битләрне импортлау...',
'import-revision-count'      => '$1 {{PLURAL:$1|юрама|юрама|юрама}}',
'importnopages'              => 'Импортлау өчен битләр юк.',
'importnotext'               => 'Буш яки текст юк',

# Import log
'importlogpage'             => 'Кертү көндәлеге',
'import-logentry-interwiki' => '«$1» — викиара  импортлау',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Кулланучы битегез',
'tooltip-pt-mytalk'               => 'Бәхәс битегез',
'tooltip-pt-preferences'          => 'Көйләнмәләрегез',
'tooltip-pt-watchlist'            => 'Сез күзәтелгән төзәтмәле битләр исемлеге',
'tooltip-pt-mycontris'            => 'Сезнең кертеменгезне исемлеге',
'tooltip-pt-login'                => 'Сез хисап язмасы төзи алыр идегез, әмма бу мәҗбүри түгел.',
'tooltip-pt-logout'               => 'Чыгу',
'tooltip-ca-talk'                 => 'Битнең эчтәлеге турында бәхәс',
'tooltip-ca-edit'                 => 'Сез бу бит үзгәртә аласыз. Зинһар, саклаганчы карап алуны кулланыгыз.',
'tooltip-ca-addsection'           => 'Яңа бүлек башлау',
'tooltip-ca-viewsource'           => 'Бу бит үзгәртүдән якланган. Сез аның чыганак текстын гына карый аласыз.',
'tooltip-ca-history'              => 'Битнең төзәтмәләр исемлеге',
'tooltip-ca-protect'              => 'Бу битне яклау',
'tooltip-ca-delete'               => 'Бу битне бетерү',
'tooltip-ca-move'                 => 'Бу битне күчерү',
'tooltip-ca-watch'                => 'Бу битне сезнең күзәтү исемлегезгә өстәү',
'tooltip-ca-unwatch'              => 'Бу битне сезнең күзәтү исемлегездә бетерү',
'tooltip-search'                  => '{{SITENAME}} эчендә эзләү',
'tooltip-search-go'               => 'Нәк шундый исеме белән биткә күчәрү',
'tooltip-search-fulltext'         => 'Бу текст белән битләрне табу',
'tooltip-p-logo'                  => 'Баш бит',
'tooltip-n-mainpage'              => 'Баш битне кереп чыгу',
'tooltip-n-mainpage-description'  => 'Баш биткә күчү',
'tooltip-n-portal'                => 'Проект турында, сез нәрсә итә аласыз һәм нәрсә кайда була дип турында.',
'tooltip-n-currentevents'         => 'Агымдагы вакыйгалар турында мәгълүматны табу',
'tooltip-n-recentchanges'         => 'Соңгы үзгәртүләр исемлеге',
'tooltip-n-randompage'            => 'Очраклы битне карау',
'tooltip-n-help'                  => '«{{SITENAME}}» проектының белешмәлек',
'tooltip-t-whatlinkshere'         => 'Бирегә сылтаган барлык битләрнең исемлеге',
'tooltip-t-recentchangeslinked'   => 'Бу биттән сылтаган битләрдә ахыргы үзгәртүләр',
'tooltip-feed-rss'                => 'Бу бит өчен RSS трансляциясе',
'tooltip-feed-atom'               => 'Бу бит өчен Atom трансляциясе',
'tooltip-t-contributions'         => 'Кулланучы кертеменең исемлегене карау',
'tooltip-t-emailuser'             => 'Бу кулланучыга хат җибәрү',
'tooltip-t-upload'                => 'Файлларны йөкләү',
'tooltip-t-specialpages'          => 'Барлык махсус битләр исемлеге',
'tooltip-t-print'                 => 'Бу битнең бастыру версиясе',
'tooltip-t-permalink'             => 'Битнең бу юрамасына даими сылтама',
'tooltip-ca-nstab-main'           => 'Мәкаләнең эчтәлеге',
'tooltip-ca-nstab-user'           => 'Кулланучының шәхси бите',
'tooltip-ca-nstab-media'          => 'Медиа-файл',
'tooltip-ca-nstab-special'        => 'Бу махсус бит, сез аны үзгәртү алмыйсыз',
'tooltip-ca-nstab-project'        => 'Проектның бите',
'tooltip-ca-nstab-image'          => 'Сүрәтнең бите',
'tooltip-ca-nstab-mediawiki'      => 'MediaWiki - хат бите',
'tooltip-ca-nstab-template'       => 'Үрнәк бите',
'tooltip-ca-nstab-help'           => 'Ярдәм битен карау',
'tooltip-ca-nstab-category'       => 'Төркем битен карау',
'tooltip-minoredit'               => 'Бу үзгәртүне кече дип билгелү',
'tooltip-save'                    => 'Үзгәртүләрегезне саклау',
'tooltip-preview'                 => 'Сезнең үзгәртүләрегезнең алдан каравы, саклаудан кадәр моны кулланыгыз әле!',
'tooltip-diff'                    => 'Сезнең үзгәртүләрегезне күрсәтү.',
'tooltip-compareselectedversions' => 'Бу битнең сайланган ике юрамасы арасында аерманы карау',
'tooltip-watch'                   => 'Бу битне күзәтү исемлегемә өстәү',
'tooltip-recreate'                => 'Бу битне кире кайтару',
'tooltip-upload'                  => 'Йөкләүне башлау',
'tooltip-rollback'                => "\"Кире кайтару\" соңгы кулланучының бу биттә ясаган '''барлык''' үзгәртүләрен бетерә.",
'tooltip-undo'                    => 'Бу үзгәртүне алдан карап үткәрмәү. Шулай ук үткәрмәүнең сәбәбен язып була.',
'tooltip-preferences-save'        => 'Көйләнмәләрегезне саклау',
'tooltip-summary'                 => 'Кыска исемен кертү',

# Stylesheets
'common.css' => '/*  Монда урнаштырылган CSS башкаларында да урнашачак */',

# Attribution
'anonymous'     => '{{SITENAME}} сайтының аноним {{PLURAL:$1|кулланучысы|кулланучылары}}',
'siteuser'      => '{{SITENAME}} кулланучысы $1',
'othercontribs' => '«$1» эшенә нигезләнә.',
'siteusers'     => '{{SITENAME}} {{PLURAL:$2|кулланучысы|кулланучылары}} $1',
'creditspage'   => 'Рәхмәтләр',

# Spam protection
'spamprotectiontitle' => 'Спам фильтры',

# Skin names
'skinname-standard'    => 'Классик',
'skinname-nostalgia'   => 'Искә алу',
'skinname-cologneblue' => 'Зәңгәр сагыш',
'skinname-monobook'    => 'Китап',
'skinname-myskin'      => 'Үзем',
'skinname-chick'       => 'Чеби',
'skinname-simple'      => 'Гади',
'skinname-modern'      => 'Замана',
'skinname-vector'      => 'Сызымлы',

# Patrolling
'markaspatrolledtext'   => 'Бу мәкаләне тикшерелгән дип тамгалау',
'markedaspatrolled'     => 'Тикшерелгән дип тамгаланды',
'markedaspatrolledtext' => 'Сайланган [[:$1]] мәкаләсенең әлеге юрамасы тикшерелгән дип тамгаланды.',

# Patrol log
'patrol-log-page'      => 'Тикшерү көндәлеге',
'patrol-log-header'    => 'Бу тикшерелгән битләрнең көндәлеге.',
'patrol-log-line'      => '$2 $3 битеннән $1ны тикшерде',
'patrol-log-auto'      => '(автоматик рәвештә)',
'patrol-log-diff'      => '$1 юрама',
'log-show-hide-patrol' => '$1 тикшерү көндәлеге',

# Image deletion
'deletedrevision'       => '$1 битенең иске юрамасы бетерелде',
'filedeleteerror-short' => 'Файлны бетерү хатасы: $1',
'filedeleteerror-long'  => 'Файлны бетерү вакытында хаталар чыкты:

$1',
'filedelete-missing'    => '«$1» исемле файлны бетерергә мөмкин түгел, чөнки ул юк.',

# Browsing diffs
'previousdiff' => '← Алдагы үзгәртү',
'nextdiff'     => 'Чираттагы үзгәртү →',

# Media information
'imagemaxsize'    => "Рәсемнең зурлыгына чикләүләр:<br />''(тасвирлау бите өчен)''",
'thumbsize'       => 'Рәсемнең кечерәйтелгән юрамасы өчен:',
'widthheight'     => '$1 × $2',
'widthheightpage' => '$1 × $2, $3{{PLURAL:$1|бит|битләр}}',
'file-info'       => 'файл зурлыгы: $1, MIME-тип: $2',
'file-info-size'  => '$1 × $2 нокта, файлның зурлыгы: $3, MIME тибы: $4',
'file-nohires'    => '<small>Югары ачыклык белән юрама юк.</small>',
'svg-long-desc'   => 'SVG файлы, шартлы $1 × $2 нокта, файлның зурлыгы: $3',
'show-big-image'  => 'Тулы ачыклык',

# Special:NewFiles
'newimages'        => 'Яңа сүрәтләр җыелмасы',
'newimages-legend' => 'Фильтр',
'showhidebots'     => '($1 бот)',
'ilsubmit'         => 'Эзләү',

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
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'               => 'Киңлек',
'exif-imagelength'              => 'Биеклек',
'exif-imagedescription'         => 'Рәсемнең исеме',
'exif-make'                     => 'Камераның җитештерүчесе',
'exif-model'                    => 'Камераның төре',
'exif-software'                 => 'Программалы тәэмин ителеш',
'exif-artist'                   => 'Автор',
'exif-copyright'                => 'Автор хокуклары хуҗасы',
'exif-exifversion'              => 'Exif версиясе',
'exif-flashpixversion'          => 'FlashPix юрамасын тәэмин итү',
'exif-colorspace'               => 'Төсләр тирәлеге',
'exif-componentsconfiguration'  => 'Төсләр төзелешенең конфигурациясе',
'exif-compressedbitsperpixel'   => 'Кысылудан соң төснең тирәнлеге',
'exif-pixelydimension'          => 'Рәсемнең киңлеге',
'exif-pixelxdimension'          => 'Рәсемнең биеклеге',
'exif-usercomment'              => 'Өстәмә җавап',
'exif-relatedsoundfile'         => 'Тавыш файлы җавабы',
'exif-datetimeoriginal'         => 'Чын вакыты',
'exif-datetimedigitized'        => 'Санлаштыру вакыты',
'exif-subsectime'               => 'Файлны үзгәртүнең өлешле секунд вакыты',
'exif-subsectimeoriginal'       => 'Чын ясалу вакытының өлеш секунды',
'exif-subsectimedigitized'      => 'Санлаштыру вакытының өлеш секунды',
'exif-exposuretime'             => 'Экспозиция вакыты',
'exif-exposuretime-format'      => '$1 с ($2)',
'exif-fnumber'                  => 'Диафрагманың саны',
'exif-fnumber-format'           => 'f/$1',
'exif-exposureprogram'          => 'Экспозиция программасы',
'exif-spectralsensitivity'      => 'Спектраль сизүчәнлек',
'exif-isospeedratings'          => 'ISO яктылык сизүчәнлеге',
'exif-shutterspeedvalue'        => 'APEX саклау',
'exif-aperturevalue'            => 'APEX диафрагма',
'exif-brightnessvalue'          => 'APEX яктылык',
'exif-exposurebiasvalue'        => 'Экспозиция компенсациясе',
'exif-maxaperturevalue'         => 'Диафрагманың минималь саны',
'exif-subjectdistance'          => 'Җисемгә кадәр ераклык',
'exif-meteringmode'             => 'Экспозицияне үлчәү режимы',
'exif-lightsource'              => 'Яктылык чыганагы',
'exif-flash'                    => 'Яктылык статусы',
'exif-focallength'              => 'Фокус ераклыгы',
'exif-focallength-format'       => '$1 мм',
'exif-subjectarea'              => 'Төшерү җисеменең урнашуы һәм мәйданы',
'exif-flashenergy'              => 'Яктылык энергиясе',
'exif-focalplanexresolution'    => 'X фокаль яссылык киңәйтелүе',
'exif-focalplaneyresolution'    => 'Y фокаль яссылык киңәйтелүе',
'exif-focalplaneresolutionunit' => 'Фокаль яссылык киңәйтелүен исәпләү берәмлеге',
'exif-subjectlocation'          => 'Җисемнең сул якка карата торышы',
'exif-exposureindex'            => 'Экспозиция саны',
'exif-sensingmethod'            => 'Сенсор төре',
'exif-filesource'               => 'Файлның чыганагы',
'exif-scenetype'                => 'Тирәлекнең төре',
'exif-customrendered'           => 'Өстәмә үзгәртү',
'exif-exposuremode'             => 'Экспозиция сайлау режимы',
'exif-whitebalance'             => 'Ак төснең балансы',
'exif-digitalzoomratio'         => 'Санлы зурайту коэффициенты',
'exif-focallengthin35mmfilm'    => 'Эквивалентлы фокус ераклыгы (35 мм тасма өчен)',
'exif-scenecapturetype'         => 'Төшерү вакытындагы тирәлек төре',
'exif-gaincontrol'              => 'Яктылыкны арттыру',
'exif-contrast'                 => 'Караңгылык',
'exif-saturation'               => 'Төрлелеге',
'exif-sharpness'                => 'Ачыклыгы',
'exif-devicesettingdescription' => 'Камераның көйләүләр тасвирламасы',
'exif-subjectdistancerange'     => 'Төшерү җисеменә кадәр ераклык',
'exif-imageuniqueid'            => 'Рәсемнең саны (ID)',
'exif-gpsversionid'             => 'GPS мәгълүматы блогының версиясе',
'exif-gpslatituderef'           => 'Киңлек индексы',
'exif-gpslatitude'              => 'Киңлек',
'exif-gpslongituderef'          => 'Озынлык индексы',
'exif-gpslongitude'             => 'Озынлык',
'exif-gpsaltituderef'           => 'Биеклек индексы',
'exif-gpsaltitude'              => 'Биеклек',
'exif-gpstimestamp'             => 'UTC буенча вакыт',
'exif-gpssatellites'            => 'Кулланылган иярченнәр тасвирламасы',
'exif-gpsstatus'                => 'Алгычның статусы һәм төшерү вакыты',
'exif-gpsmeasuremode'           => 'Урнашуны билгеләү ысулы',
'exif-gpsdop'                   => 'Билгеләүнең дөреслеге',
'exif-gpsspeedref'              => 'Тизлекне исәпләү берәмлеге',
'exif-gpsspeed'                 => 'Хәрәкәт тизлеге',
'exif-gpsdatestamp'             => 'Дата',

'exif-orientation-1' => 'Нормаль',
'exif-orientation-3' => '180° ка борылган',

'exif-meteringmode-0'   => 'Билгесез',
'exif-meteringmode-3'   => 'Нокталы',
'exif-meteringmode-4'   => 'Мультинокталы',
'exif-meteringmode-255' => 'Башка',

'exif-lightsource-0'  => 'Билгесез',
'exif-lightsource-4'  => 'Яктылык',
'exif-lightsource-9'  => 'Яхшы һава торышы',
'exif-lightsource-11' => 'Күләгә',

'exif-sensingmethod-1' => 'Билгесез',

'exif-scenecapturetype-0' => 'Стандарт',
'exif-scenecapturetype-1' => 'Ландшафт',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Төнге төшерү',

'exif-gaincontrol-0' => 'Юк',
'exif-gaincontrol-1' => 'Аз зурайту',
'exif-gaincontrol-2' => 'Күпләп зурайту',
'exif-gaincontrol-3' => 'Аз кечерәйтү',
'exif-gaincontrol-4' => 'Күпләп кечерәйтү',

'exif-contrast-0' => 'Нормаль',
'exif-contrast-1' => 'Аз гына күтәрү',
'exif-contrast-2' => 'Күп иттереп күтәрү',

'exif-saturation-0' => 'Нормаль',
'exif-saturation-1' => 'Аз гына туендырылу',
'exif-saturation-2' => 'Күп иттереп туендырылу',

'exif-sharpness-0' => 'Нормаль',
'exif-sharpness-1' => 'Аз гына күтәрү',
'exif-sharpness-2' => 'Күп иттереп күтәрү',

'exif-subjectdistancerange-0' => 'Билгесез',
'exif-subjectdistancerange-1' => 'Макротөшерү',
'exif-subjectdistancerange-2' => 'Якыннан төшерү',
'exif-subjectdistancerange-3' => 'Ерактан төшерү',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'төньяк киңлек',
'exif-gpslatitude-s' => 'көньяк киңлек',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'көнчыгыш озынлык',
'exif-gpslongitude-w' => 'көнбатыш озынлык',

'exif-gpsstatus-a' => 'Үлчәү тәмамланмаган',
'exif-gpsstatus-v' => 'Мәгълүматларны җибәрүгә әзер',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'км/сәг',
'exif-gpsspeed-m' => 'миля/сәг',

# External editor support
'edit-externally'      => 'Бу файлны тышкы кушымтаны кулланып үзгәртү',
'edit-externally-help' => '(тулырак мәгълүмат өчен [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] битен карагыз)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'барлык',
'namespacesall' => 'барлык',
'monthsall'     => 'барлык',
'limitall'      => 'барлык',

# Delete conflict
'recreate' => 'Яңадан ясау',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Бу битнең кэшы чистартылсынмы?',
'confirm-purge-bottom' => 'Кэшны чистартудан соң аның соңгы юрамасы күрсәтеләчәк.',

# Multipage image navigation
'imgmultipageprev' => '← алдагы бит',
'imgmultipagenext' => 'алдагы бит →',
'imgmultigo'       => 'Күчү!',
'imgmultigoto'     => '$1 битенә күчү',

# Table pager
'ascending_abbrev'         => 'үсү',
'descending_abbrev'        => 'кимү',
'table_pager_next'         => 'Киләсе бит',
'table_pager_prev'         => 'Алдагы бит',
'table_pager_first'        => 'Беренче бит',
'table_pager_last'         => 'Ахыргы бит',
'table_pager_limit_submit' => 'Башкару',
'table_pager_empty'        => 'Нәтиҗә юк',

# Auto-summaries
'autoredircomment' => '[[$1]] битенә юнәлтү',
'autosumm-new'     => 'Яңа бит: «$1»',

# Live preview
'livepreview-loading' => 'Йөкләү...',
'livepreview-ready'   => 'Йөкләү... Әзер!',

# Watchlist editor
'watchlistedit-raw-titles' => 'Язмалар:',

# Watchlist editing tools
'watchlisttools-view' => 'Соңгы үзгәртүләрне күрсәтү',
'watchlisttools-edit' => 'Күзәтү исемлегене карау һәм үзгәртү',
'watchlisttools-raw'  => 'Текст сыман үзгәртү',

# Hijri month names
'hijri-calendar-m1' => 'Мөхәррәм',
'hijri-calendar-m7' => 'Раҗәб',
'hijri-calendar-m9' => 'Рамазан',

# Special:Version
'version'                   => 'Юрама',
'version-extensions'        => 'Куелган киңәйтүләр',
'version-specialpages'      => 'Махсус битләр',
'version-other'             => 'Башка',
'version-hook-subscribedby' => 'Түбәндәгеләргә язылган:',
'version-license'           => 'Лицензия',
'version-software'          => 'Урнаштырылган программа белән тәэмин ителешне',
'version-software-product'  => 'Продукт',
'version-software-version'  => 'Версия',

# Special:FilePath
'filepath'        => 'Файлга юл',
'filepath-page'   => 'Файл:',
'filepath-submit' => 'Күчү',

# Special:FileDuplicateSearch
'fileduplicatesearch'        => 'Бер үк файлларны эзләү',
'fileduplicatesearch-submit' => 'Эзләү',

# Special:SpecialPages
'specialpages'                   => 'Махсус битләр',
'specialpages-note'              => '----
* Гади махсус битләр.
* <strong class="mw-specialpagerestricted">Чикләнелгән махсус битләр.</strong>
* <span class="mw-specialpagecached">Кешланган махсус битләр.</span>',
'specialpages-group-maintenance' => 'Техник карау хисапнамәсе',
'specialpages-group-other'       => 'Башка махсус битләр',
'specialpages-group-login'       => 'Керү / теркәлү',
'specialpages-group-changes'     => 'Соңгы үзгәртүләр',
'specialpages-group-media'       => 'Йөкләү һәм медиа-файллар хисапнамәсе',
'specialpages-group-users'       => 'Кулланучылар һәм аларның хокуклары',
'specialpages-group-highuse'     => 'Еш кулланылучы битләр',
'specialpages-group-pages'       => 'Битләр исемлеге',
'specialpages-group-pagetools'   => 'Бит өчен җиһазлар',
'specialpages-group-wiki'        => 'Вики-мәгълүмат һәм җиһазлар',
'specialpages-group-redirects'   => 'Күчерелүче махсус битләр',
'specialpages-group-spam'        => 'Спамга каршы кораллар',

# Special:BlankPage
'blankpage'              => 'Буш бит',
'intentionallyblankpage' => 'Бу бит махсус буш калдырылган',

# Special:Tags
'tags'              => 'Гамәлдә булучы үзгәртүләр билгеләре',
'tag-filter'        => '[[Special:Tags|Tag]] фильтры:',
'tag-filter-submit' => 'Фильтрлау',
'tags-title'        => 'Теглар',
'tags-intro'        => 'Әлеге сәхифәдә төзәтүләрне билгеләгән, программа тәэмин итә торган теглар исемлеге һәм шул тегларның аңламнары китерелгән.',
'tags-tag'          => 'Тег исеме',
'tags-edit'         => 'үзгәртү',

# Special:ComparePages
'comparepages'     => 'Битләрне чагыштыру',
'compare-selector' => 'Битләрнең юрамаларын чагыштыру',
'compare-page1'    => 'Беренче сәхифә',
'compare-page2'    => 'Икенче сәхифә',
'compare-rev1'     => 'Беренче юрама',
'compare-rev2'     => 'Икенче юрама',
'compare-submit'   => 'Чагыштыр',

# Database error messages
'dberr-header'   => 'Бу вики авырлык кичерә',
'dberr-problems' => 'Гафу итегез! Сайтта техник кыенлыклар чыкты.',
'dberr-again'    => 'Сәхифәне берничә минуттан соң яңартып карагыз.',
'dberr-info'     => '(Мәгълүматлар базасы серверы белән тоташырга мөмкин түгел: $1)',

# HTML forms
'htmlform-submit'              => 'Җибәрү',
'htmlform-reset'               => 'Үзгәртүләрне кире кайтару',
'htmlform-selectorother-other' => 'Башка',

);
