<?php
/** Bashkir (башҡортса)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AiseluRB
 * @author Alfiya55
 * @author Assele
 * @author Comp1089
 * @author Haqmar
 * @author Kaganer
 * @author Matma Rex
 * @author Reedy
 * @author Roustammr
 * @author Sagan
 * @author Timming
 * @author Рустам Нурыев
 * @author ҒатаУлла
 * @author Ҡамыр Батыр
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Махсус',
	NS_TALK             => 'Фекерләшеү',
	NS_USER             => 'Ҡатнашыусы',
	NS_USER_TALK        => 'Ҡатнашыусы_менән_һөйләшеү',
	NS_PROJECT_TALK     => '$1_буйынса_фекерләшеү',
	NS_FILE             => 'Файл',
	NS_FILE_TALK        => 'Файл_буйынса_фекерләшеү',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_буйынса_фекерләшеү',
	NS_TEMPLATE         => 'Ҡалып',
	NS_TEMPLATE_TALK    => 'Ҡалып_буйынса_фекерләшеү',
	NS_HELP             => 'Белешмә',
	NS_HELP_TALK        => 'Белешмә_буйынса_фекерләшеү',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_буйынса_фекерләшеү',
);

$namespaceAliases = array(
	'Ярҙамсы'                     => NS_SPECIAL,
	'Фекер_алышыу'                => NS_TALK,
	'Ҡатнашыусы_м-н_фекер_алышыу' => NS_USER_TALK,
	'$1_б-са_фекер_алышыу'        => NS_PROJECT_TALK,
	'Рәсем'                       => NS_FILE,
	'Рәсем_буйынса_фекерләшеү'    => NS_FILE_TALK,
	'Рәсем_б-са_фекер_алышыу'     => NS_FILE_TALK,
	'MediaWiki_б-са_фекер_алышыу' => NS_MEDIAWIKI_TALK,
	'Ҡалып_б-са_фекер_алышыу'     => NS_TEMPLATE_TALK,
	'Белешмә_б-са_фекер_алышыу'   => NS_HELP_TALK,
	'Төркөм'                      => NS_CATEGORY,
	'Төркөм_буйынса_фекерләшеү'   => NS_CATEGORY_TALK,
	'Категория_б-са_фекер_алышыу' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ӘүҙемҠатнашыусылар', 'АктивҠатнашыусылар' ),
	'Allmessages'               => array( 'Система_хәбәрҙәре' ),
	'Allpages'                  => array( 'Барлыҡ_битәр' ),
	'Blankpage'                 => array( 'Буш_бит' ),
	'Block'                     => array( 'Блоклау' ),
	'Blockme'                   => array( 'Мине_блокла' ),
	'Booksources'               => array( 'Китап_сығанаҡтары' ),
	'BrokenRedirects'           => array( 'Өҙөлгән_йүнәлтеүҙәр' ),
	'Categories'                => array( 'Категориялар' ),
	'ChangeEmail'               => array( 'Email-ды_алыштырыу' ),
	'ChangePassword'            => array( 'Паролде_алыштырыу' ),
	'ComparePages'              => array( 'Биттәрҙе_сағыштырыу' ),
	'Confirmemail'              => array( 'Email-ды_раҫлау' ),
	'Contributions'             => array( 'Өлөштәр' ),
	'CreateAccount'             => array( 'Иҫәп_яҙыуы_яһау' ),
	'Deadendpages'              => array( 'Көрсөк_биттәр' ),
	'DeletedContributions'      => array( 'Юйылған_өлөш' ),
	'Disambiguations'           => array( 'Күп_мәғәнәлелек' ),
	'DoubleRedirects'           => array( 'Икеле_йүнәлтеүҙәр' ),
	'EditWatchlist'             => array( 'Күҙәтеү_исемлеген_мөхәррирләү' ),
	'Emailuser'                 => array( 'Ҡатнашыусыға_хат' ),
	'Export'                    => array( 'Экспорт' ),
	'FileDuplicateSearch'       => array( 'Файлдың_дубликаттарын_эҙләү' ),
	'Filepath'                  => array( 'Файл_юлы' ),
	'Import'                    => array( 'Импорт' ),
	'BlockList'                 => array( 'Блоклауҙар_исемлеге' ),
	'LinkSearch'                => array( 'Һылтанмалар_эҙләү' ),
	'Listadmins'                => array( 'Хакимдар_исемлеге' ),
	'Listbots'                  => array( 'Боттар_исемлеге' ),
	'Listfiles'                 => array( 'Файлдар_исемлеге' ),
	'Listgrouprights'           => array( 'Ҡатнашыусы_төркөмдәре_хоҡуҡтары' ),
	'Listredirects'             => array( 'Йүнәлтеүҙәр_исемлеге' ),
	'Listusers'                 => array( 'Ҡатнашыусылар_исемлеге' ),
	'Log'                       => array( 'Журналдар' ),
	'Lonelypages'               => array( 'Етем_биттәр' ),
	'Longpages'                 => array( 'Оҙон_биттәр' ),
	'MergeHistory'              => array( 'Тарихтарҙы_берләштереү' ),
	'Mostimages'                => array( 'Йыш_ҡулланылған_файлдар' ),
	'Movepage'                  => array( 'Бит_исемен_үҙгәртеү' ),
	'Mycontributions'           => array( 'Өлөшөм' ),
	'Mypage'                    => array( 'Битем' ),
	'Mytalk'                    => array( 'Әңгәмә_битем' ),
	'Myuploads'                 => array( 'Тейәүҙәрем' ),
	'Newimages'                 => array( 'Яңы_файлдар' ),
	'Newpages'                  => array( 'Яңы_биттәр' ),
	'PasswordReset'             => array( 'Паролде_яңыртыу' ),
	'PermanentLink'             => array( 'Даими_һылтанма' ),
	'Popularpages'              => array( 'Популяр_биттәр' ),
	'Preferences'               => array( 'Көйләүҙәр' ),
	'Protectedpages'            => array( 'Һаҡланған_биттәр' ),
	'Protectedtitles'           => array( 'Һаҡланған_исемдәр' ),
	'Randompage'                => array( 'Осраҡлы_мәҡәлә' ),
	'Recentchanges'             => array( 'Һуңғы_үҙгәртеүҙәр' ),
	'Recentchangeslinked'       => array( 'Бәйле_үҙгәртеүҙәр' ),
	'Revisiondelete'            => array( 'Төҙәтеүҙе_юйыу' ),
	'Search'                    => array( 'Эҙләү' ),
	'Shortpages'                => array( 'Ҡыҫҡа_биттәр' ),
	'Specialpages'              => array( 'Махсус_биттәр' ),
	'Tags'                      => array( 'Билдәләр' ),
	'Unblock'                   => array( 'Блокты_сисеү' ),
	'Uncategorizedcategories'   => array( 'Категорияланмаған_категориялар' ),
	'Uncategorizedimages'       => array( 'Категорияланмаған_файлдар' ),
	'Uncategorizedpages'        => array( 'Категорияланмаған_биттәр' ),
	'Uncategorizedtemplates'    => array( 'Категорияланмаған_ҡалыптар' ),
	'Undelete'                  => array( 'Тергеҙеү' ),
	'Unusedcategories'          => array( 'Ҡулланылмаған_категориялар' ),
	'Unusedimages'              => array( 'Ҡулланылмаған_файлдар' ),
	'Unusedtemplates'           => array( 'Ҡулланылмаған_ҡалыптар' ),
	'Upload'                    => array( 'Тейәү' ),
	'UploadStash'               => array( 'Йәшерен_тейәү' ),
	'Userlogin'                 => array( 'Танылыу' ),
	'Userlogout'                => array( 'Ултырышты_тамамлау' ),
	'Userrights'                => array( 'Хоҡуҡтарҙы_идаралау' ),
	'Wantedcategories'          => array( 'Кәрәкле_категориялар' ),
	'Wantedfiles'               => array( 'Кәрәкле_файлдар' ),
	'Wantedpages'               => array( 'Кәрәкле_биттәр' ),
	'Wantedtemplates'           => array( 'Кәрәкле_ҡалыптар' ),
	'Watchlist'                 => array( 'Күҙәтеү_исемлеге' ),
	'Whatlinkshere'             => array( 'Бында_һылтанмалар' ),
	'Withoutinterwiki'          => array( 'Интервикиһыҙ' ),
);

// Remove Russian aliases
$namespaceGenderAliases = array();

$linkTrail = '/^((?:[a-z]|а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|ә|ө|ү|ғ|ҡ|ң|ҙ|ҫ|һ|“|»)+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Һылтанмалар аҫтына һыҙыу:',
'tog-justify' => 'Һөйләмдәр теҙмәһен бит киңлегенә тигеҙләргә',
'tog-hideminor' => 'Әһәмиәте ҙур булмаған төҙәтеүҙәрҙе һуңғы үҙгәртеүҙәр исемлегендә күрһәтмәҫкә',
'tog-hidepatrolled' => 'Һуңғы үҙгәртеүҙәр исемлегендә тикшерелгән үҙгәртеүҙәрҙе йәшер',
'tog-newpageshidepatrolled' => 'Яңы биттәр исемлегендә тикшерелгән үҙгәртеүҙәрҙе йәшер',
'tog-extendwatchlist' => 'Барлыҡ үҙгәртеүҙәрҙе үҙ эсенә алған, киңәйтелгән күҙәтеү исемлеге',
'tog-usenewrc' => 'Һуңғы төҙәтеүҙәр һәм күҙәтеү исемлегендәге үҙгәрештәрҙе төркөмдәргә бүлергә',
'tog-numberheadings' => 'Башисемдәрҙе автоматик рәүештә номерлаe',
'tog-showtoolbar' => 'Мөхәррирләгән ваҡытта өҫкө ҡоралдар панелен күрһәтергә (JavaScript кәрәк)',
'tog-editondblclick' => 'Биттәрҙе ике сиртеү менән мөхәррирләргә',
'tog-editsection' => 'Һәр бүлек өсөн «үҙгәртеү» һылтанмаһын күрһәтергә',
'tog-editsectiononrightclick' => 'Бүлектәрҙе исемдәренә төрткөнөң уң яғына сиртеп үҙгәртергә',
'tog-showtoc' => 'Эстәлек күрһәтелһен (3-тән күп башлығы булған биттәрҙә)',
'tog-rememberpassword' => 'Был браузерҙа (иң күбендә $1 {{PLURAL:$1|көнгә}}) иҫәп яҙыуым хәтерләнһен',
'tog-watchcreations' => 'Мин төҙөгән биттәрҙе һәм күсергән файлдарҙы күҙәтеү исемлегенә өҫтәргә',
'tog-watchdefault' => 'Мин үҙгәрткән биттәр һәм файлдар аңлатмаһын күҙәтеү исемлегенә өҫтәргә',
'tog-watchmoves' => 'Мин исемен үҙгәрткән биттәрҙе һәм файлдарҙы күҙәтеү исемлегенә өҫтәргә',
'tog-watchdeletion' => 'Мин юйған биттәрҙе һәм файлдарҙы күҙәтеү исемлегенә өҫтәргә',
'tog-minordefault' => 'Бөтә үҙгәртеүҙәрҙе, ғәҙәттә, әҙ үҙгәреш тип билдәләргә',
'tog-previewontop' => 'Алдан байҡау тәҙрәһен мөхәррирләү битенең өҫтөнә ҡуйырға',
'tog-previewonfirst' => 'Мөхәррирләүгә күскәндә алдан ҡарау күрһәтелһен',
'tog-nocache' => 'Браузерҙа биттәрҙе кэшлауҙы тыйырға',
'tog-enotifwatchlistpages' => 'Күҙәтеү исемлегендәге биттәрҙең һәм файлдарҙың үҙгәрештәре тураһында электрон почта аша хәбәр итергә',
'tog-enotifusertalkpages' => 'Шәхси фекер алышыу битем үҙгәртелеү тураһында электрон почта аша белдерергә',
'tog-enotifminoredits' => 'Биттәрҙең һәм файлдарҙың аҙ гынә үҙгәрештәре тураһында ла электрон почта аша хәбер итергә',
'tog-enotifrevealaddr' => 'Белдереү хәбәрҙәрендә почта адресым күрһәтелһен',
'tog-shownumberswatching' => 'Битте күҙәтеү исемлегенә өҫтәгән ҡулланыусылар һанын күрһәтергә',
'tog-oldsig' => 'Хәҙерге имза:',
'tog-fancysig' => 'Имзаның үҙ вики-тамғаһы (автоматик һылтанмаһыҙ)',
'tog-uselivepreview' => 'Тиҙ ҡарап алыуҙы ҡулланырға (JavaScript, эксперименталь)',
'tog-forceeditsummary' => 'Төҙәтеүҙе тасуирлау юлы тултырылмаһа, мине киҫәт',
'tog-watchlisthideown' => 'Үҙгәртеүҙеремде күҙәтеү исемлегенән йәшерергә',
'tog-watchlisthidebots' => 'Боттар  үҙгәртеүҙәрен күҙәтеү исемлегенән йәшерергә',
'tog-watchlisthideminor' => 'Әҙ үҙгәрештәрҙе күҙәтеү исемлегенән йәшерергә',
'tog-watchlisthideliu' => 'Танылған ҡулланыусыларҙың үҙгәртеүҙәрен күҙәтеү исемлегенән йәшерергә',
'tog-watchlisthideanons' => 'Аноним ҡулланыусыларҙың үҙгәртеүҙерен күҙәтеү исемлегенән йәшерергә',
'tog-watchlisthidepatrolled' => 'Тикшерелгән үҙгәртеүҙәрҙе күҙәтеү исемлегенән йәшерергә',
'tog-ccmeonemails' => 'Башҡа ҡулланыусыларға ебәргән хаттарымдың күсермәләрен үҙемә лә ебәрергә',
'tog-diffonly' => 'Версия сағыштырыу аҫтында бит эстәлеге күрһәтелмәһен',
'tog-showhiddencats' => 'Йәшерен категорияларҙы күрһәтергә',
'tog-norollbackdiff' => 'Кире ҡайтарыуҙан һуң версия айырмалары күрһәтелмәһен',
'tog-useeditwarning' => 'Мөхәррирләү битенән үҙгәртеүҙәрҙе һаҡламайынса сыҡҡан ваҡытта мине киҫәтергә',
'tog-prefershttps' => 'Системаға танытылғандан һуң һәр ваҡыт һаҡланыулы тоташыу ҡулланырға',

'underline-always' => 'Һәр ваҡыт',
'underline-never' => 'Бер ҡасан да',
'underline-default' => 'Браузер көйләүҙәрен ҡулланырға',

# Font style option in Special:Preferences
'editfont-style' => 'Үҙгәртеү тәҙрәһендәге шрифт төрө:',
'editfont-default' => 'Браузер көйләүҙәре ҡулланылһын',
'editfont-monospace' => 'Тиң киңлекле шрифт',
'editfont-sansserif' => 'Киртекһеҙ шрифт',
'editfont-serif' => 'Киртекле шрифт',

# Dates
'sunday' => 'Йәкшәмбе',
'monday' => 'Дүшәмбе',
'tuesday' => 'Шишәмбе',
'wednesday' => 'Шаршамбы',
'thursday' => 'Кесаҙна',
'friday' => 'Йома',
'saturday' => 'Шәмбе',
'sun' => 'Йш',
'mon' => 'Дш',
'tue' => 'Шш',
'wed' => 'Шр',
'thu' => 'Кс',
'fri' => 'Йм',
'sat' => 'Шб',
'january' => 'ғинуар',
'february' => 'февраль',
'march' => 'март',
'april' => 'апрель',
'may_long' => 'май (һабанай)',
'june' => 'июнь',
'july' => 'июль',
'august' => 'август',
'september' => 'сентябрь',
'october' => 'октябрь',
'november' => 'ноябрь',
'december' => 'декабрь',
'january-gen' => 'ғинуар',
'february-gen' => 'февраль',
'march-gen' => 'март',
'april-gen' => 'апрель',
'may-gen' => 'май',
'june-gen' => 'июнь',
'july-gen' => 'июль',
'august-gen' => 'август',
'september-gen' => 'сентябрь',
'october-gen' => 'октябрь',
'november-gen' => 'ноябрь',
'december-gen' => 'декабрь',
'jan' => 'ғин',
'feb' => 'фев',
'mar' => 'мар',
'apr' => 'апр',
'may' => 'май',
'jun' => 'июн',
'jul' => 'июл',
'aug' => 'авг',
'sep' => 'сен',
'oct' => 'окт',
'nov' => 'ноя',
'dec' => 'дек',
'january-date' => 'Ғинуар $1',
'february-date' => 'Февраль $1',
'march-date' => 'Март $1',
'april-date' => 'Апрель $1',
'may-date' => 'Май $1',
'june-date' => 'Июнь $1',
'july-date' => 'Июль $1',
'august-date' => 'Август $1',
'september-date' => 'Сентябрь $1',
'october-date' => 'Октябрь $1',
'november-date' => 'Ноябрь $1',
'december-date' => 'Сентябрь $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Категория|Категория}}',
'category_header' => '«$1» категорияһындағы биттәр',
'subcategories' => 'Эске категориялар',
'category-media-header' => '«$1» категорияһындағы файлдар',
'category-empty' => '"Был категория әлегә буш."',
'hidden-categories' => '{{PLURAL:$1|Йәшерен категория|Йәшерен категориялар}}',
'hidden-category-category' => 'Йәшерен категориялар',
'category-subcat-count' => '{{PLURAL:$2|Был категорияла тик киләһе эске категория ғына бар.|$2 эске категорияның $1 эске категорияһы күрһәтелгән.}}',
'category-subcat-count-limited' => 'Был категорияла {{PLURAL:$1|$1 эске категория}} бар.',
'category-article-count' => '{{PLURAL:$2|Был категорияла бер генә бит бар.|Категориялағы $2 биттең $1 бите күрһәтелгән.}}',
'category-article-count-limited' => 'Был категорияла {{PLURAL:$1|$1 бит}} бар.',
'category-file-count' => '{{PLURAL:$2|Был категорияла бер генә файл бар.|Категориялағы $2 файлдың {{PLURAL:$1|$1 файлы күрһәтелгән}}.}}',
'category-file-count-limited' => 'Бу категорияла {{PLURAL:$1|$1 файл}} бар.',
'listingcontinuesabbrev' => '(дауамы)',
'index-category' => 'Индексланған биттәр',
'noindex-category' => 'Индексланмаған биттәр',
'broken-file-category' => 'Файлға һылтанмалары эшләмәгән биттәр',

'about' => 'Тасуирлау',
'article' => 'Мәҡәлә',
'newwindow' => '(яңы биттә)',
'cancel' => 'Бөтөрөргә',
'moredotdotdot' => 'Дауамы...',
'morenotlisted' => 'Был исемлек тулы түгел',
'mypage' => 'Бит',
'mytalk' => 'Әңгәмә',
'anontalk' => 'Был IP-адресының фекер алышыу бите',
'navigation' => 'Төп йүнәлештәр',
'and' => '&#32;һәм',

# Cologne Blue skin
'qbfind' => 'Эҙләү',
'qbbrowse' => 'Байҡарға',
'qbedit' => 'Үҙгәртергә',
'qbpageoptions' => 'Был бит',
'qbmyoptions' => 'Биттәрем',
'qbspecialpages' => 'Махсус биттәр',
'faq' => 'ЙБҺ',
'faqpage' => 'Project:ЙБҺ',

# Vector skin
'vector-action-addsection' => 'Тема өҫтәргә',
'vector-action-delete' => 'Юйырға',
'vector-action-move' => 'Исемен үҙгәртергә',
'vector-action-protect' => 'Һаҡларға',
'vector-action-undelete' => 'Тергеҙергә',
'vector-action-unprotect' => 'Һаҡлауҙы үҙгәртергә',
'vector-simplesearch-preference' => 'Ябайлаштырылған эҙләү тәҡдимдәрен ҡулланырға ("Векторлы" күренеш өсөн генә)',
'vector-view-create' => 'Яһау',
'vector-view-edit' => 'Үҙгәртергә',
'vector-view-history' => 'Тарихты ҡарау',
'vector-view-view' => 'Уҡыу',
'vector-view-viewsource' => 'Сығанаҡты ҡарарға',
'actions' => 'Хәрәкәт',
'namespaces' => 'Исем арауыҡтары',
'variants' => 'Варианттар',

'navigation-heading' => 'Навигация',
'errorpagetitle' => 'Хата',
'returnto' => '$1 битенә ҡайтыу.',
'tagline' => '{{SITENAME}} проектынан',
'help' => 'Белешмә',
'search' => 'Эҙләү',
'searchbutton' => 'Табыу',
'go' => 'Күсеү',
'searcharticle' => 'Күсеү',
'history' => 'Тарих',
'history_short' => 'Тарих',
'updatedmarker' => 'һуңғы кереүемдән һуң яңыртылған',
'printableversion' => 'Баҫтырыу өлгөһө',
'permalink' => 'Даими һылтанма',
'print' => 'Баҫыу',
'view' => 'Ҡарау',
'edit' => 'Үҙгәртеү',
'create' => 'Яһарға',
'editthispage' => 'Был мәҡәләне үҙгәртергә',
'create-this-page' => 'Был битте яһарға',
'delete' => 'Юҡ  итергә',
'deletethispage' => 'Был битте юйырға',
'undeletethispage' => 'Юйылған был битте ҡабат тергеҙеү',
'undelete_short' => '$1 {{PLURAL:$1|үҙгәртеүҙе}} тергеҙергә',
'viewdeleted_short' => '{{PLURAL:$1|1 юйылған үҙгәртеүҙе|$1 юйылған үҙгәртеүҙе}} ҡарау',
'protect' => 'Һаҡларға',
'protect_change' => 'үҙгәртергә',
'protectthispage' => 'Был битте һаҡларға',
'unprotect' => 'Һаҡлауҙы үҙгәртергә',
'unprotectthispage' => 'Был биттең һаҡлауын үҙгәртергә',
'newpage' => 'Яңы бит',
'talkpage' => 'Фекер алышыу',
'talkpagelinktext' => 'әңг.',
'specialpage' => 'Ярҙамсы бит',
'personaltools' => 'Шәхси ҡоралдар',
'postcomment' => 'Яңы бүлек',
'articlepage' => 'Мәҡәләне ҡарап сығырға',
'talk' => 'Әңгәмә',
'views' => 'Ҡарауҙар',
'toolbox' => 'Ҡоралдар',
'userpage' => 'Ҡулланыусы битен ҡарарға',
'projectpage' => 'Проект битен ҡарарға',
'imagepage' => 'Файл битен ҡарарға',
'mediawikipage' => 'Хәбәрҙәр битен ҡарарға',
'templatepage' => 'Ҡалып битен ҡарарға',
'viewhelppage' => 'Ярҙам битен ҡарарға',
'categorypage' => 'Категория битен ҡарарға',
'viewtalkpage' => 'Фекер алышыу битен ҡарарға',
'otherlanguages' => 'Башҡа телдәрҙә',
'redirectedfrom' => '($1 битенән йүнәлтелде)',
'redirectpagesub' => 'Йүнәлтеү бите',
'lastmodifiedat' => 'Был биттең һуңғы тапҡыр үҙгәртелеү ваҡыты: $2, $1 .',
'viewcount' => 'Был биткә $1 {{PLURAL:$1|тапҡыр}} мөрәжәғәт иттеләр.',
'protectedpage' => 'Һаҡланған бит',
'jumpto' => 'Унда күсергә:',
'jumptonavigation' => 'төп йүнәлештәр',
'jumptosearch' => 'эҙләү',
'view-pool-error' => 'Ғәфү итегеҙ, хәҙерге ваҡытта серверҙар артыҡ тейәлгән.
Был битте ҡарарға теләүселәр бик күп.
Зинһар был биткә һуңырак кереп ҡарағыҙ.

$1',
'pool-timeout' => 'Блоклауҙы көтөү ваҡыты үтте',
'pool-queuefull' => 'Һорауҙар сираты тулы',
'pool-errorunknown' => 'Билдәһеҙ хата',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} тураһында',
'aboutpage' => 'Project:Тасуирлама',
'copyright' => '$1 лицензияһына ярашлы, эстәлеге менән һәр кем файҙалана ала (башҡаһы күрһәтелмәһә)',
'copyrightpage' => '{{ns:project}}:Авторлыҡ хоҡуҡтары',
'currentevents' => 'Ағымдағы ваҡиғалар',
'currentevents-url' => 'Project:Ағымдағы ваҡиғалар',
'disclaimers' => 'Яуаплылыҡтан баш тартыу',
'disclaimerpage' => 'Project:Яуаплылыҡтан баш тартыу',
'edithelp' => 'Төҙәтеү белешмәһе',
'helppage' => 'Help:Белешмә',
'mainpage' => 'Баш бит',
'mainpage-description' => 'Баш бит',
'policy-url' => 'Project:Ҡағиҙәләр',
'portal' => 'Берләшмә',
'portal-url' => 'Project:Берләшмә ҡоро',
'privacy' => 'Сер һаҡлау сәйәсәте',
'privacypage' => 'Project:Сер һаҡлау сәйәсәте',

'badaccess' => 'Кереү хатаһы',
'badaccess-group0' => 'Һоратылған ғәмәлде үтәй алмайһығыҙ.',
'badaccess-groups' => 'Һоратылған ғәмәлде киләһе {{PLURAL:$2|төркөм|төркөмдәр}} ҡулланыусылары ғына башҡара ала: $1.',

'versionrequired' => 'MediaWiki-ның $1 версияһы кәрәкле',
'versionrequiredtext' => 'Был бит менән эшләү өсөн MediaWiki-ның $1 версияһы кәрәк. [[Special:Version|Ҡулланылған версия тураһында мәғлүмәт битен]] ҡара.',

'ok' => 'Тамам',
'pagetitle' => '{{SITENAME}} проектынан',
'retrievedfrom' => 'Сығанағы — «$1»',
'youhavenewmessages' => 'Яңы $1 бар ($2).',
'newmessageslink' => 'яңы хәбәр',
'newmessagesdifflink' => 'һуңғы үҙгәртеү',
'youhavenewmessagesfromusers' => 'Һеҙгә {{PLURAL:$3|башҡа ҡатнашыусынан|$3 ҡатнашыусынан}} $1 бар ($2).',
'youhavenewmessagesmanyusers' => 'Һеҙгә күп ҡатнашыусынан $1 бар ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|яңы хәбәр|яңы хәбәр}}',
'newmessagesdifflinkplural' => 'һуңғы {{PLURAL:$1|үҙгәртеү|үҙгәртеү}}',
'youhavenewmessagesmulti' => 'Һеҙгә яңы хәбәрҙәр бар: $1',
'editsection' => 'үҙгәртергә',
'editold' => 'төҙәтеү',
'viewsourceold' => 'сығанаҡ кодты ҡарарға',
'editlink' => 'үҙгәртергә',
'viewsourcelink' => 'сығанаҡ кодты ҡарарға',
'editsectionhint' => '$1 бүлеген үҙгәртеү',
'toc' => 'Эстәлеге',
'showtoc' => 'күрһәтергә',
'hidetoc' => 'йәшерергә',
'collapsible-collapse' => 'төрөргә',
'collapsible-expand' => 'асырға',
'thisisdeleted' => 'Ҡарарғамы йәки тергеҙергәме? — $1',
'viewdeleted' => '$1 ҡарарғамы?',
'restorelink' => '{{PLURAL:$1|1 юйылған үҙгәртеүҙе|$1 юйылған үҙгәртеүҙе}}',
'feedlinks' => 'Таҫма:',
'feed-invalid' => 'Хаталы таҫма тибы.',
'feed-unavailable' => 'Синдикация таҫмаларына ирешеп булмай',
'site-rss-feed' => '$1 — RSS таҫмаһы',
'site-atom-feed' => '$1 — Atom таҫмаһы',
'page-rss-feed' => '«$1» — RSS-таҫма',
'page-atom-feed' => '$1» — Atom-таҫма',
'red-link-title' => '$1 (был бит юҡ)',
'sort-descending' => 'Кәмей барыу буйынса тәртипләштереү',
'sort-ascending' => 'Ҙурая барыу буйынса тәртипләштереү',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Мәҡәлә',
'nstab-user' => 'Ҡатнашыусы',
'nstab-media' => 'Мультимедиа',
'nstab-special' => 'Махсус бит',
'nstab-project' => 'Проект бите',
'nstab-image' => 'Файл',
'nstab-mediawiki' => 'MediaWiki белдереүе',
'nstab-template' => 'Ҡалып',
'nstab-help' => 'Ярҙам',
'nstab-category' => 'Категория',

# Main script and global functions
'nosuchaction' => 'Бындай ғәмәл юҡ',
'nosuchactiontext' => 'URL-ла күрһәтелгән ғәмәл хаталы.
Һеҙ URL-ны хаталы кереткәнһегеҙ йәки хаталы һылтанма буйынса күскәнһегеҙ.
Был шулай уҡ {{SITENAME}} проектындағы хата сәбәпле  лә булырға мөмкин.',
'nosuchspecialpage' => 'Бындай махсус бит юҡ',
'nospecialpagetext' => '<strong>Һеҙ һоратҡан ярҙамсы бит юҡ.</strong>

Хәҙер ғәмәлдә булған ярҙамсы биттәр исемлеге: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Хата',
'databaseerror' => 'Мәғлүмәттәр базаһы хатаһы',
'databaseerror-text' => 'Бирелмәләр базаһында хата киткән.
Был программа тәьминәтендә хата барлығы күрһәткесе булырға мөмкин.',
'databaseerror-textcl' => 'Бирелмәләр базаһында хата бар.',
'databaseerror-query' => 'Һоратыу: $1',
'databaseerror-function' => 'Функция:$1',
'databaseerror-error' => 'Хата: $1',
'laggedslavemode' => "'''Иғтибар:''' биттә һуңғы үҙгәртеүҙәр күрһәтелмәгән булырға мөмкин.",
'readonly' => 'Мәғлүмәттәр базаһы бикләнгән',
'enterlockreason' => 'Ябылыу сәбәбен һәм ваҡытын белдерегеҙ.',
'readonlytext' => 'Яңы мәҡәләләр өҫтәү һәм мәғлүмәттәр базаһындағы башҡа үҙгәртеүҙәр хәҙер ябылған. Был планлы хеҙмәтләндереү сәбәпле булыуы мөмкин, аҙаҡтан нормаль хәлгә ҡайтасаҡ.

Ябыусы хаким ҡалдырған аңлатма:
$1',
'missing-article' => 'Мәғлүмәттәр базаһында «$1» $2 битенең һоралған тексты табылманы.

Был, ғәҙәттә, иҫкергән һылтанма буйынса юйылған биттең  үҙгәртеү тарихына күскәндә килеп сыға.

Әгәр хатаның сәбәбе ул булмаһа, тимәк һеҙ программала хата тапҡанһығыҙ.
Был турала зинһар URL-ды күрһәтеп, [[Special:ListUsers/sysop|хәкимгә]] белдерегеҙ.',
'missingarticle-rev' => '(версия № $1)',
'missingarticle-diff' => '(айырма: $1, $2)',
'readonly_lag' => 'Өҫтәмә сервер төп сервер менән синхронлашҡанға тиклем мәғлүмәттәр базаһы автоматик рәүештә үҙгәрештәргә ҡаршы ябылған.',
'internalerror' => 'Эске хата',
'internalerror_info' => 'Эске хата: $1',
'fileappenderrorread' => 'Өҫтәү ваҡытында «$1» файлын уҡып булманы.',
'fileappenderror' => '"$1"ҙе "$2"гә ҡушып булманы.',
'filecopyerror' => '«$2» файлына «$1» файлының күсермәһен яһап булмай.',
'filerenameerror' => '«$1» файлының исемен «$2» исеменә алмаштырып булмай.',
'filedeleteerror' => '«$1» файлын юйып булмай.',
'directorycreateerror' => '«$1» директорияһын яһап булмай.',
'filenotfound' => '«$1» файлын табып булмай.',
'fileexistserror' => '«$1» файлына яҙып булмай: файл былай ҙа бар.',
'unexpected' => 'Көтөлмәгән ҡиммәт: «$1»=«$2».',
'formerror' => 'Хата: форма мәғлүмәттәрен ебәреп булмай',
'badarticleerror' => 'Был биттә ундай ғәмәл үтәргә ярамай',
'cannotdelete' => '«$1» исемле битте йәки файлды юйып булмай.
Уны башҡа ҡулланыусы юйған булыуы мөмкин.',
'cannotdelete-title' => '"$1" битен юйып булмай',
'delete-hook-aborted' => 'Үҙгәртеүҙе махсус-процедура кире ҡаҡты.
Өҫтәмә аңлатма килтерелмәй.',
'no-null-revision' => '«$1» бите өсөн яңы нулле төҙәтеү яһап булманы',
'badtitle' => 'Ярамаған исем',
'badtitletext' => 'Биттең һоратылған исеме дөрөҫ түгел, буш йәки телдәр араһы йәки интервики исеме яңылыш күрһәтелгән. Исемдә тыйылған символдар булыуы ла мөмкин.',
'perfcached' => 'Был мәғлүмәттәр кэштан алынған, уларҙа һуңғы үҙгәртеүҙәр булмаҫҡа мөмкин. Кэшта иң күбе {{PLURAL:$1|язма}} һаҡлана.',
'perfcachedts' => 'Был мәғлүмәттәр кэштан алынған, ул һуңғы тапҡыр $1 яңыртылды.  Кэшта иң күбе  {{PLURAL:$4|язма}} һаҡлана',
'querypage-no-updates' => 'Был битте яңыртыу хәҙер тыйылған.
Бында күрһәтелгән мәғлүмәттәр яңыртылмаясаҡ.',
'wrong_wfQuery_params' => 'wfQuery() функцияһы өсөн рөхсәт ителмәгән параметрҙар<br />
Функция: $1<br />
Һоратыу: $2',
'viewsource' => 'Сығанаҡты ҡарау',
'viewsource-title' => '$1 битенең сығанаҡ текстын ҡарарға',
'actionthrottled' => 'Тиҙлек сикләнгән',
'actionthrottledtext' => 'Спам менән көрәшеү өсөн, был ғәмәлде ҡыҫҡа ваҡыт эсендә күп тапҡыр ҡабатлауға сикләү ҡуйылған. Зинһар, бер нисә минуттан яңынан ҡабатлап ҡарағыҙ.',
'protectedpagetext' => 'Был бит мөхәррирләү өсөн ябыҡ.',
'viewsourcetext' => 'Һеҙ был биттең сығанаҡ текстын ҡарай һәм күсермәһен ала алаһығыҙ:',
'viewyourtext' => "Был биттәге '''үҙгәртеүҙәрегеҙҙең''' сығанаҡ текстын ҡарай һәм күсермәһен ала алаһығыҙ:",
'protectedinterface' => 'Был биттә программаның интерфейс хәбәре бар. Вандализм осраҡтарын булдырмау өсөн, был битте үҙгәртеү тыйыла.
Был хәбәрҙең тәржемәһен өҫтәү йәки үҙгәртеү өсөн, зинһар, MediaWiki проектының [//translatewiki.net/ translatewiki.net] локалләштереү сайтын ҡулланығыҙ.',
'editinginterface' => "'''Иғтибар.''' Һеҙ программаның арайөҙ тексты булған битте мөхәррирләйһегеҙ.
Уны үҙгәртеү, башҡа ҡулланыусыларҙын арайөҙ күренешен үҙгәртәсәктер.
Тәржемә өсөн [//translatewiki.net/wiki/Main_Page?setlang=ba translatewiki.net] адресын, MediaWiki-ны локалләштереү проектын ҡулланыу яҡшыраҡ буласаҡтыр.",
'cascadeprotected' => 'Был бит үҙгәртеүҙәрҙән һаҡланған, сөнки ул эҙмә-эҙлекле һаҡлау ҡуйылған {{PLURAL:$1|биткә|биттәргә}} керә:
$2',
'namespaceprotected' => '«$1» исем арауығындағы биттәрҙе мөхәррирләү өсөн хоҡуҡтарығыҙ юҡ.',
'customcssprotected' => 'Был CSS-битте үҙгәртеү хоҡуғығыҙ юҡ, сөнки унда башҡа ҡулланыусының шәхси көйләүҙәре бар.',
'customjsprotected' => 'Был JavaScript-битте үҙгәртеү хоҡуғығыҙ юҡ, сөнки унда башҡа ҡулланыусының шәхси көйләүҙәре бар.',
'mycustomcssprotected' => 'Биттең был CSS-ын  мөхәррирләргә хоҡуғығыҙ юҡ.',
'mycustomjsprotected' => 'Был биттәге  JavaScript-ты мөхәррирләргә хоҡуғығыҙ юҡ.',
'myprivateinfoprotected' => 'Һеҙгә шәхси мәғлүмәттәрегеҙҙе үҙгәртергә рөхсәт юҡ',
'mypreferencesprotected' => 'Һеҙҙең көйләүҙәрегеҙҙе мөхәррирләргә хоҡуғығыҙ юҡ.',
'ns-specialprotected' => '«{{ns:special}}» исем арауығындағы биттәрҙе үҙгәртеп булмай.',
'titleprotected' => "Был исем менән бит яһау [[User:$1|$1]] тарафынан тыйылған.
Белдерелгән сәбәп: ''$2''.",
'filereadonlyerror' => "«$1» файлын үҙгәртеп булмай, сөнки «$2» һаҡлағысы «уҡыу өсөн генә» тәртибендә.

Был сикләүҙе индергән хаким биргән аңлатма:«''$3''».",
'invalidtitle-knownnamespace' => '"$2" исем арауығы һәм "$3"  тексты исем өсөн ярамай',
'invalidtitle-unknownnamespace' => '"$2" тексты һәм "$1" арауыҡ өсөн билдәһеҙ номерлы исем ярамай',
'exception-nologin' => 'Танылмағанһығыҙ',
'exception-nologin-text' => 'Был битте ҡарар йәки һоратылған ғәмәлде башҡарыр өсөн системала танылыу кәрәк.',

# Virus scanner
'virus-badscanner' => "Көйләү хатаһы: Билдәһеҙ вирустар сканеры: ''$1''",
'virus-scanfailed' => 'сканлау хатаһы ($1 коды)',
'virus-unknownscanner' => 'беленмәгән антивирус:',

# Login and logout pages
'logouttext' => "'''Һеҙ эш сеансын тамамланығыҙ.'''

Ҡайһы бер биттәр һеҙ системаға танылмаған кеүек күренеүен дауам итер. Быны бөтөрөү өсөн браузер кэшын таҙартығыҙ.",
'welcomeuser' => 'Рәхим итегеҙ $1!',
'welcomecreation-msg' => 'Иҫәп яҙыуығыҙ яһалды.
Шәхси [[Special:Preferences|{{SITENAME}} көйләүҙәрен]] үҙегеҙгә уңайлы итеп үҙгәртергә онотмағыҙ.',
'yourname' => 'Ҡатнашыусы исеме',
'userlogin-yourname' => 'Ҡулланыусы исеме',
'userlogin-yourname-ph' => 'Иҫәп яҙмағыҙҙың исемен яҙығыҙ',
'createacct-another-username-ph' => 'Иҫәп яҙмағыҙҙың исемен яҙығыҙ',
'yourpassword' => 'Серһүҙ',
'userlogin-yourpassword' => 'Серһүҙ',
'userlogin-yourpassword-ph' => 'Яңы серһүҙҙе яҙығыҙ',
'createacct-yourpassword-ph' => 'Серһүҙҙе яҙығыҙ',
'yourpasswordagain' => 'Серһүҙҙе ҡабаттан яҙыу',
'createacct-yourpasswordagain' => 'Серһүҙҙе раҫлағыҙ',
'createacct-yourpasswordagain-ph' => 'Серһүҙҙе тағы бер тапҡыр яҙығыҙ',
'remembermypassword' => 'Был браузерҙа (иң күбендә $1 {{PLURAL:$1|көнгә}}) иҫәп яҙыуым хәтерләнһен',
'userlogin-remembermypassword' => 'Системала ҡалырға',
'userlogin-signwithsecure' => 'Һаҡланыулы тоташыу',
'yourdomainname' => 'Һеҙҙең домен',
'password-change-forbidden' => 'Был викила серһүҙегеҙҙе үҙгәртә алмайһығыҙ.',
'externaldberror' => 'Тышҡы мәғлүмәт базаһы менән танылғанда хата барлыҡҡа килде йәки тышҡы үҙ көйләүҙәрегеҙҙе үҙгәртер өсөн хоҡуҡтарығыҙ етәрле түгел.',
'login' => 'Танышыу йәки теркәлеү',
'nav-login-createaccount' => 'Танышыу йәки теркәлеү',
'loginprompt' => '{{SITENAME}} проектына кереү өсөн «cookies» рөхсәт ителгән булырға тейеш.',
'userlogin' => 'Танылыу йәки теркәлеү',
'userloginnocreate' => 'Танылыу',
'logout' => 'Тамамлау',
'userlogout' => 'Тамамлау',
'notloggedin' => 'Танылмағанһығыҙ',
'userlogin-noaccount' => 'Иҫәп яҙмағыҙ юҡмы?',
'userlogin-joinproject' => 'Проектҡа ҡушылырға',
'nologin' => "Һеҙ теркәлмәгәнһегеҙме әле? '''$1'''.",
'nologinlink' => 'Иҫәп яҙыуын булдырырға',
'createaccount' => 'Яңы ҡатнашыусыны теркәү',
'gotaccount' => "Әгәр Һеҙ теркәлеү үткән булһағыҙ? '''$1'''.",
'gotaccountlink' => 'Үҙегеҙ менән таныштырығыҙ',
'userlogin-resetlink' => 'Танылыу мәғлүмәттәрен оноттоғоҙмо?',
'userlogin-resetpassword-link' => 'Серһүҙҙе ҡабул итмәү',
'helplogin-url' => 'Help:Системаға танылыу',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Help with logging in]]Системаға инеүҙә ярҙам',
'userlogin-loggedin' => ' Һеҙ {{GENDER:$1|$1}} булараҡ индегеҙ инде. Башҡа файҙаланыусы булып инер өсөн аҫтағы ҡалыпты ҡулланығыҙ.',
'userlogin-createanother' => 'Башҡа иҫәп яҙмаһын булдырырға',
'createacct-join' => 'Аҫта мәғлүмәттәрегеҙҙе яҙығыҙ.',
'createacct-another-join' => 'Аҫта яңы иҫәп яҙмағыҙҙың мәғлүмәттәрен яҙығыҙ.',
'createacct-emailrequired' => 'Электрон почта адресы',
'createacct-emailoptional' => 'Электрон почта адресы (мотлаҡ түгел)',
'createacct-email-ph' => 'Электрон почта адресығыҙҙы яҙығыҙ',
'createacct-another-email-ph' => 'Электрон почта адресығыҙҙы яҙығыҙ',
'createaccountmail' => 'Осраҡлы рәүештә хасил ителгән ваҡытлыса серһүҙҙе файҙаланырға һәм уны миңә ошо электрон почтаһы адресына ебәрергә',
'createacct-realname' => 'Ысын исемегеҙ (мотлаҡ түгел)',
'createaccountreason' => 'Сәбәп:',
'createacct-reason' => 'Сәбәп',
'createacct-reason-ph' => 'Икенсе иҫәп яҙмаһы һеҙгә ни өсөн кәрәк?',
'createacct-captcha' => 'Һаҡлылыҡты тикшереү',
'createacct-imgcaptcha-ph' => 'Өҫтәге тексты индерегеҙ',
'createacct-submit' => 'Иҫәп яҙмаһын булдырырға',
'createacct-another-submit' => 'Тағы бер иҫәп яҙмаһын булдырырға',
'createacct-benefit-heading' => '{{SITENAME}} һеҙҙең кеүек үк кешеләр тарафынан булдырылған',
'createacct-benefit-body1' => '{{PLURAL:$1|үҙгәртеү}}',
'createacct-benefit-body2' => '{{PLURAL:$1|мәҡәлә|мәҡәлә|мәҡәләнең}}',
'createacct-benefit-body3' => 'һуңғы ваҡытта {{PLURAL:$1|ҡатнашыусы|}}',
'badretype' => 'Һеҙ кереткән серһүҙҙәр тап килмәй.',
'userexists' => 'Керетелгән исем ҡулланыла инде.
Зинһар, башҡа исем һайлағыҙ.',
'loginerror' => 'Танылыу хатаһы',
'createacct-error' => 'Иҫәп яҙмаһын булдырғандағы хата',
'createaccounterror' => 'Иҫәп яҙыуын яһап булмай: $1',
'nocookiesnew' => 'Иҫәп яҙыуы яһалды, ләкин һеҙ танылмағанһығыҙ. {{SITENAME}} ҡатнашыусыны таныу өсөн «cookies» ҡуллана. Һеҙҙә «cookies» тыйылған. Зинһар, уларға рөхсәт бирегеҙ, шунан яңынан ҡатнашыусы исеме һәм серһүҙ менән танылығыҙ.',
'nocookieslogin' => '{{SITENAME}} ҡатнашыусыны таныу өсөн «cookies» ҡуллана. Һеҙҙә «cookies» тыйылған. Зинһар, уға рөхсәт бирегеҙ һәм яңынан керегеҙ.',
'nocookiesfornew' => 'Иҫәп яҙмаһы булдырылманы, сөнки уның сығанағын тикшереү мөмкин түгел.
"Сookies" эшләй икәнлеген тикшерегеҙ, битте яңыртығыҙ  һәм яңынан ҡабатлап ҡарағыҙ.',
'noname' => 'Ғәмәлдә булған ҡатнашыусы исемен керетмәнегеҙ.',
'loginsuccesstitle' => 'Танышыу уңышлы үтте',
'loginsuccess' => 'Хәҙер һеҙ $1 исеме менән эшләйһегеҙ.',
'nosuchuser' => '$1 исемле ҡулланыусы юҡ.
Ҡулланыусы исеме хәреф регистрына һиҙгер.
Исемде тикшерегеҙ йәки [[Special:UserLogin/signup|яңы иҫәп яҙыуы асығыҙ]].',
'nosuchusershort' => '$1 исемле ҡулланыусы юҡ. Исемде тикшерегеҙ.',
'nouserspecified' => 'Һеҙ ҡатнашыусы исемен күрһәтергә тейеш.',
'login-userblocked' => 'Был ҡатнашыусыға рөхсәт юҡ.  Исеме тыйылған.',
'wrongpassword' => 'Һеҙ ҡулланған серһүҙ ҡабул ителмәй. Яңынан яҙып ҡарағыҙ.',
'wrongpasswordempty' => 'Зинһар, буш булмаған серһүҙ керетегеҙ.',
'passwordtooshort' => 'Серһүҙ кәмендә $1 {{PLURAL:$1|символдан}} торорға тейеш.',
'password-name-match' => 'Керетелгән серһүҙ ҡулланыусы исеменән айырылырға тейеш.',
'password-login-forbidden' => 'Был ҡатнашыусы исемен һәм серһүҙҙе ҡулланыу тыйылған',
'mailmypassword' => 'Яңы серһүҙ ебәрергә',
'passwordremindertitle' => '{{SITENAME}} өсөн яңы ваҡытлыса серһүҙ',
'passwordremindertext' => 'Кемдер (бәлки, һеҙ, IP-адресы: $1) {{SITENAME}} ($4) өсөн яңы серһүҙ һоратты. $2 ҡатнашыусыһы өсөн ваҡытлыса яңы серһүҙ яһалды: $3. Әгәр был һеҙ булһағыҙ, системага керегеҙ һәм серһүҙ алмаштырығыҙ. Яңы серһүҙ $5 {{PLURAL:$5|көн}} ғәмәлдә буласаҡ.

Әгәр һеҙ серһүҙҙе алмаштырыуҙы һоратмаған йәки онотоп кире иҫләгән булһағыҙ һәм үҙгәртергә теләмәһәгеҙ, был хәбәргә иғтибар итмәгеҙ һәм элекке серһүҙегеҙҙе ҡулланыуығыҙҙы дауам итегеҙ.',
'noemail' => '$1 исемле ҡулланыусы өсөн электрон почта адресы белдерелмәгән.',
'noemailcreate' => 'Дөрөҫ электрон почта адресы күрһәтеү кәрәк',
'passwordsent' => 'Яңы серһүҙ $1 исемле ҡатнашыусының электрон почта адресына ебәрелде.

Зинһар, серһүҙҙе алғас, системаға яңынан керегеҙ.',
'blocked-mailpassword' => 'Һеҙҙең IP-адресығыҙҙан мөхәррирләү тыйылған, шул сәбәпле серһүҙ тергеҙеү ғәмәле лә блокланған.',
'eauthentsent' => 'Күрһәтелгән электрон почта адресына адресты үҙгәртеүҙе раҫлауығыҙ өсөн хат ебәрелде. Хатта, был адрес һеҙҙеке булғанын раҫлау өсөн ниндәй ғәмәлдәрҙе үтәү кәрәкле икәне тураһында мәғлүмәт бар.',
'throttled-mailpassword' => 'Серһүҙҙе иҫләтеү ғәмәле {{PLURAL:$1|һуңғы $1 сәғәт}} эсенде ҡулланылды инде.
Насар ниәтле ҡулланыуҙарға ҡаршы, Серһүҙ иҫләтеү ғәмәлен {{PLURAL:$1|сәғәт|$1 сәғәт}} эсендә бер тапҡыр ғына ҡулланырға була.',
'mailerror' => 'Хат ебәреү хатаһы: $1',
'acct_creation_throttle_hit' => 'Һеҙҙең IP-адрестан бер тәүлек эсендә {{PLURAL:$1|$1 иҫәп яҙыуы}} яһалды инде, был һан был ваҡыт аралығы өсөн максимум һан. Шул сәбәпле, был IP-адресына эйә ҡулланыусылар, хәҙерге ваҡытта яңы иҫәп яҙыуы яһай алмайҙар.',
'emailauthenticated' => 'Электрон почта адресығыҙ раҫланды: $3, $2.',
'emailnotauthenticated' => 'Электрон почта адресығыҙ раҫланмаған әле. Киләһе ғәмәлдәр өсөн электрон почта эшләмәйәсәк.',
'noemailprefs' => 'Электрон почта адресығыҙ күрһәтелмәгән, шул сәбәпле викиның электрон почта функциялары ябыҡ.',
'emailconfirmlink' => 'Электрон почта адресығыҙҙы раҫлағыҙ',
'invalidemailaddress' => 'Электрон почта адресы ҡабул ителә алмай, сөнки ул форматка тап килмәй.
Зинһар, дөрөҫ адрес керетегеҙ йәки юлды буш ҡалдырығыҙ.',
'cannotchangeemail' => 'Иҫәп яҙыуы электрон почта адрестарын был викила үҙгәртеп булмай.',
'emaildisabled' => 'Был сайт электрон почта хәберҙәрен ебәрә алмай',
'accountcreated' => 'Иҫәп яҙыуы яһалды',
'accountcreatedtext' => '[[{{ns:User}}:$1|$1]]([[{{ns:User talk}}:$1|msj]])   өсөн иҫәп яҙмаһы булдырылды.',
'createaccount-title' => '{{SITENAME}}: теркәлеү',
'createaccount-text' => 'Кемдер, электрон почта адресығыҙҙы күрһәтеп, {{SITENAME}} ($4) проектында «$3» пароле менән «$2» исемле иҫәп яҙыуы теркәне. Һеҙҙең кереүегеҙ һәм серһүҙегеҙҙе алмаштырыуығыҙ кәрәк.

Иҫәп яҙыуы яңылыш яһалһа, был хатҡа иғтибар итмәгеҙ.',
'usernamehasherror' => 'Ҡулланыусы исемендә "#" символы була алмай',
'login-throttled' => 'Һеҙ системаға ҡат-ҡат танылырға тырыштығыҙ.
Тағы бер танылырҙан алда, зинһар, $1 көтөгөҙ.',
'login-abort-generic' => 'Танылыу уңышһыҙ тамамланды',
'loginlanguagelabel' => 'Тел: $1',
'suspicious-userlogout' => 'Һеҙҙең сеансты тамамлау тураһында һорауығыҙ кире ҡағылды, сөнки ул төҙөк булмаған браузер йәки кэшлаусы прокси тарафынан ебәрелгән һорауға оҡшаған.',
'createacct-another-realname-tip' => 'Ысын исемегеҙ (мотлаҡ түгел).
Уны яҙып ҡуйһағыҙ, ул биткә кем төҙәтеү индергәнен күрһәтеү өсөн ҡулланыласаҡ.',

# Email sending
'php-mail-error-unknown' => 'PHP-ның mail() функцияһында билдәһеҙ хата',
'user-mail-no-addy' => 'Электрон почта адресы булмайынса электрон хәбәр ебәреп ҡараны',
'user-mail-no-body' => 'Буш йә мәғәнәһеҙ йөкмәткеле ҡыҫҡа электрон хат ебәрергә тырышҡан.',

# Change password dialog
'resetpass' => 'Серһүҙҙе үҙгәртеү',
'resetpass_announce' => 'Һеҙ системала электрон почта аша алынған ваҡытлыса серһүҙ менән танылдығыҙ. Системаға кереүҙә тамалау өсөн яңы серһүҙ булдырығыҙ.',
'resetpass_header' => 'Иҫәп яҙыуы серһүҙен үҙгәртеү',
'oldpassword' => 'Иҫке серһүҙ:',
'newpassword' => 'Яңы серһүҙ:',
'retypenew' => 'Серһүҙҙе яңынан керетегеҙ:',
'resetpass_submit' => 'Серһүҙ ҡуйырға һәм танышырға',
'changepassword-success' => 'Серһүҙегеҙ уңышлы үҙгәртелде!',
'resetpass_forbidden' => 'Серһүҙҙе үҙгәртеп булмай',
'resetpass-no-info' => 'Был битте туранан ҡарау өсөн һеҙгә системала танылырға кәрәк.',
'resetpass-submit-loggedin' => 'Серһүҙҙе үҙгәртергә',
'resetpass-submit-cancel' => 'Бөтөрөргә',
'resetpass-wrong-oldpass' => 'Хаталы ваҡытлыса йәки ағымдағы серһүҙ.
Һеҙ, бәлки, серһүҙегеҙҙе алмаштырғанһығыҙ йәки яңы серһүҙ һоратҡанһығыҙ.',
'resetpass-temp-password' => 'Ваҡытлыса серһүҙ',
'resetpass-abort-generic' => 'Серһүҙҙе үҙгәртеү киңәйеү тарафынан өҙөлдө.',

# Special:PasswordReset
'passwordreset' => 'Серһүҙҙе ташлатыу',
'passwordreset-text-one' => 'Серһүҙегеҙҙе ташлар өсөн ош ҡалыпты тултырығыҙ.',
'passwordreset-text-many' => '{{PLURAL:$1|Серһүҙҙе ташлар өсөн яландарҙың береһен тултырығыҙ.}}',
'passwordreset-legend' => 'Серһүҙҙе ташлатыу',
'passwordreset-disabled' => 'Был викила серһүҙҙе ташлатыу ғәмәлдә түгел',
'passwordreset-emaildisabled' => 'Был викиҙа электрон почта функцияһы һүндерелгән.',
'passwordreset-username' => 'Ҡулланыусы исеме:',
'passwordreset-domain' => 'Домен:',
'passwordreset-capture' => 'Хәбәрҙең һуңғы хәлен ҡарарғамы?',
'passwordreset-capture-help' => 'Әгәр был билдәне ҡуйһағыҙ, ҡулланыусыға ебәрелгән ваҡытлыса серһүҙ һеҙгә күрһәтеләсәк.',
'passwordreset-email' => 'Электрон почта адресы:',
'passwordreset-emailtitle' => '{{SITENAME}} иҫәп яҙыуы мәғлүмәттәре',
'passwordreset-emailtext-ip' => 'Берәү (бәлки һәҙ, $1 IP-адресынан ) {{SITENAME}} ($4) проектындағы иҫәп яҙыуығыҙҙы хәтерләтеүҙе һоратты.
Киләһе ҡулланыусы {{PLURAL:$3|иҫәп яҙыуы|иҫәп яҙыуҙары}} был электрон почта адресы менән бәйле:

$2

Был ваҡытлыса {{PLURAL:$3|серһүҙ|серһүҙҙәр}} {{PLURAL:$5|$5 көн}} ғәмәлдә буласаҡ.
Һеҙ системала танылырға һәм яңы серһүҙ һайларға тейешһегеҙ.
Әгәр, һеҙ быны һоратмаған булһағыҙ йәки элекке серһүҙегеҙҙе киренән иҫләһәгеҙ һәм уны үҙгәртергә теләмәһәгеҙ, был хатҡа иғтибар итмәгеҙ һәм элекке серһүҙегеҙҙе ҡулланыуҙы дауам итегеҙ.',
'passwordreset-emailtext-user' => '{{SITENAME}} проектындағы $1 ҡулланыусыһы {{SITENAME}} ($4) проектындағы иҫәп яҙыуығыҙҙы хәтерләтеүҙе һоратты. Киләһе ҡулланыусы {{PLURAL:$3|иҫәп яҙыуы|иҫәп яҙыуҙары}} был электрон почта адресы менән бәйле:

$2

Был ваҡытлыса {{PLURAL:$3|серһүҙ|серһүҙҙәр}} {{PLURAL:$5|$5 көн}} ғәмәлдә буласаҡ.
Һеҙ системала танылырға һәм яңы серһүҙ һайларға тейешһегеҙ.
Әгәр, һеҙ быны һоратмаған булһағыҙ йәки элекке серһүҙегеҙҙе киренән иҫләһәгеҙ һәм уны үҙгәртергә теләмәһәгеҙ, был хатҡа иғтибар итмәгеҙ һәм элекке серһүҙеҙҙе ҡулланыуҙы дауам итегеҙ.',
'passwordreset-emailelement' => 'Ҡулланыусы исеме: $1
Ваҡытлыса серһүҙ: $2',
'passwordreset-emailsent' => 'Серһүҙҙе ташлау тураһындағы мәғлүмәт менән электрон почта аша хат ебәрелде.',
'passwordreset-emailsent-capture' => 'Серһүҙҙе ташлау тураһындағы мәғлүмәт менән электрон хат ебәрелде, уның тексы түбәндә бирелә:',
'passwordreset-emailerror-capture' => 'Серһүҙҙе ташлау тураһында хәбәр итеүсе электрон хат булдырылғайны, ләкин уны  {{GENDER:$2|kullanıcıya}} түбәндәге сәбәп арҡаһында ебәреп булманы: $1',

# Special:ChangeEmail
'changeemail' => 'Электрон почта адресын үҙгәртергә',
'changeemail-header' => 'Электрон почта адресын үҙгәртеү',
'changeemail-text' => 'Электрон почта адресығыҙҙы үҙгәртеү өсөн түбәндәге форманы тултырығыҙ. Үҙгәртеүҙәрҙе раҫлау өсөн серһүҙегеҙҙе керетеү кәрәк буласаҡ.',
'changeemail-no-info' => 'Был биткә туранан ирешеү өсөн һеҙгә системала танылыу кәрәк.',
'changeemail-oldemail' => 'Хәҙерге электрон почта адресы:',
'changeemail-newemail' => 'Яңы электрон почта адресы:',
'changeemail-none' => '(юҡ)',
'changeemail-password' => '{{SITENAME}} прокты өсөн серһүҙегеҙ:',
'changeemail-submit' => 'Адресты үҙгәртергә',
'changeemail-cancel' => 'Кире алырға',

# Special:ResetTokens
'resettokens' => 'Токендарҙы ташларға',
'resettokens-text' => 'Иҫәп яҙмағыҙ менән бәйләнгән ҡайһы бер шәхси мәғлүмәттәрегеҙгә инеүгә юл асыусы токендарҙы ташлай алаһығыҙ.

Яңылыштан уларҙы берәйһе менән уртаҡлашҡан  йәки аккаунтығыҙ ваттырылған осраҡта быны эшләү мотлаҡ.',
'resettokens-no-tokens' => 'Ташлар өсөн токендар юҡ.',
'resettokens-legend' => 'Токендарҙы ташларға',
'resettokens-tokens' => 'Токендар:',
'resettokens-token-label' => '$1 (ағымдағы мәғәнә: $2)',
'resettokens-watchlist-token' => ' [[Special:Watchlist|күҙәтеүҙәрегеҙ исемлегендә биттәрҙең үҙгәрештәре]] веб-каналы өсөн токен(Atom/RSS)',
'resettokens-done' => 'Токендар ташланды.',
'resettokens-resetbutton' => 'Һайланған токендарҙы ташларға',

# Edit page toolbar
'bold_sample' => 'Ҡалын яҙылыш',
'bold_tip' => 'Ҡалын яҙылыш',
'italic_sample' => 'Курсив яҙылыш',
'italic_tip' => 'Курсив яҙылыш',
'link_sample' => 'Һылтанма исеме',
'link_tip' => 'Эске һылтанма',
'extlink_sample' => 'http://www.example.com һылтанма исеме',
'extlink_tip' => 'Тышҡы һылтанма (http:// префиксын онотмағыҙ)',
'headline_sample' => 'Исем',
'headline_tip' => '2-се дәрәжәле исем',
'nowiki_sample' => 'Бында форматланмаған тексты өҫтәгеҙ.',
'nowiki_tip' => 'Вики-форматлауға иғтибар итмәҫкә',
'image_tip' => 'Индерелгән файл',
'media_tip' => 'Файл һылтанмаһы',
'sig_tip' => 'Имзағыҙ һәм ваҡыт',
'hr_tip' => 'Горизонталь һыҙыҡ (бик йыш ҡулланмағыҙ)',

# Edit pages
'summary' => 'Үҙгәртеү аңлатмаһы:',
'subject' => 'Тема/исем:',
'minoredit' => 'Әҙ генә үҙгәрештәр',
'watchthis' => 'Күҙәтеү исемлегенә',
'savearticle' => 'Яҙҙырып ҡуйырға',
'preview' => 'Ҡарап сығыу',
'showpreview' => 'Ҡарап сығырға',
'showlivepreview' => 'Тиҙ алдан байҡау',
'showdiff' => 'Индерелгән үҙгәрештәр',
'anoneditwarning' => "'''Иғтибар''': Һеҙ танылмағанһығыҙ. IP-адресығыҙ был биттең үҙгәртеүҙәр тарихына яҙыласаҡ.",
'anonpreviewwarning' => "''Һеҙ танылмағанһығыҙ. Яҙҙырыу ваҡытында IP-адресығыҙ был биттең үҙгәртеүҙәр тарихына яҙыласаҡ.''",
'missingsummary' => "'''Иҫкәртеү.''' Һеҙ үҙгәртеүҙергә ҡыҫҡа тасуирлама яҙманығыҙ. Ҡабаттан «Битте һаҡларға» төймәһенә баҫһағыҙ, үҙгәртеүҙәрегеҙ тасуирламаһыҙ һаҡланасаҡ.",
'missingcommenttext' => 'Зинһар, аҫҡа үҙ тасуирламағыҙҙы керетегеҙ.',
'missingcommentheader' => "'''Иҫкәртеү:''' Һеҙ был комментарий өсөн тема/исем яҙманығыҙ.
«{{int:savearticle}}» төймәһенә ҡабат баҫыу менән үҙгәртеүҙерегеҙ исемһеҙ яҙыласаҡ.",
'summary-preview' => 'Буласаҡ тасуирлама:',
'subject-preview' => 'Тема/башлыҡты алдан ҡарау:',
'blockedtitle' => 'Ҡулланыусы блокланған',
'blockedtext' => "'''Иҫәп яҙыуығыҙ йәки IP-адресығыҙ блокланған.'''

Блоклаусы хаким: $1.
Белдерелгән сәбәп: ''$2''.

* Блоклау башланған ваҡыт: $8
* Блоклау  аҙағы: $6
* Блоклауҙар һаны: $7

Һеҙ $1 йәки башҡа [[{{MediaWiki:Grouppage-sysop}}|хакимгә]] блоклау буйынса һорауҙарығыҙҙы ебәрә алаһығыҙ.
Иҫегеҙҙе тотоғоҙ: әгәр һеҙ теркәлмәгән һәм электрон почта адресығыҙҙы раҫламаған булһағыҙ ([[Special:Preferences|көйләүҙәрем битендә]]), хакимгә хат ебәрә алмайһығыҙ. Шулай ук блоклау ваҡытында һеҙҙең хат ебәреү мөмкинлегегеҙ сикләгән булырға ла мөмкин.
Һеҙҙең IP-адрес — $3, блоклау идентификаторы — #$5.
Хаттарҙа был мәғлүмәттәрҙе күрһәтергә онотмағыҙ.",
'autoblockedtext' => "Һеҙҙең IP-адресығыҙ автоматик рәүештә блокланған. Сәбәбе, был адрес элек блокланған ҡулланыусыларҙың береһе тарафынан ҡулланылған. Блоклаусы хаким ($1) киләһе сәбәпте белдергән:

:«$2»

Блоклаусы хаким: $1.
Белдерелгән сәбәп: ''$2''.

* Блоклау башланған ваҡыт: $8
* Блоклау  аҙағы: $6
* Блоклауҙар һаны: $7

Һеҙ $1 йәки башҡа [[{{MediaWiki:Grouppage-sysop}}|хакимгә]] блоклау буйынса һорауҙарығыҙҙы ебәрә алаһығыҙ.
Иҫегеҙҙе тотоғоҙ: әгәр һеҙ теркәлмәгән һәм электрон почта адресығыҙҙы раҫламаған булһағыҙ ([[Special:Preferences|көйләүҙәрем битендә]]), хакимгә хат ебәрә алмайһығыҙ. Шулай ук блоклау ваҡытында һеҙҙең хат ебәреү мөмкинлегегеҙ сикләгән булырға ла мөмкин.
Һеҙҙең IP-адрес — $3, блоклау идентификаторы — #$5.
Хаттарҙа был мәғлүмәттәрҙе күрһәтергә онотмағыҙ.",
'blockednoreason' => 'сәбәп белдерелмәгән',
'whitelistedittext' => 'Биттәрҙә үҙгәртеү өсөн $1 кәрәк.',
'confirmedittext' => 'Биттәрҙе үҙгәртерҙән алда электрон почта адресығыҙҙы раҫларға тейешһегеҙ.
Быны [[Special:Preferences|көйләүҙәр битендә]] эшләй алаһығыҙ.',
'nosuchsectiontitle' => 'Бүлекте табып булмай',
'nosuchsectiontext' => 'Һеҙ булмаған бүлекте үҙгәртергә тырышаһығыҙ.
Һеҙ мөхәррирләгәнсе уны моғайын күсергәндәр йәки юйҙырғандар.',
'loginreqtitle' => 'Танылыу кәрәк',
'loginreqlink' => 'танылыу',
'loginreqpagetext' => 'Башҡа биттәрҙе ҡарау өсөн $1 кәрәк.',
'accmailtitle' => 'Серһүҙ ебәрелде.',
'accmailtext' => "[[User talk:$1|$1]] өсөн осраҡлы яһалған серһүҙ $2 адресына ебәрелде.

Танылғандан һуң был иҫәп яҙмаһы өсөн серһүҙҙе ''[[Special:ChangePassword|серһүҙҙе үҙгәртеү өсөн махсус биттә үҙгәртә алаһығыҙ]]''.",
'newarticle' => '(Яңы)',
'newarticletext' => "Һеҙ һылтанма буйынса әлегә яһалмаған биткә күстегеҙ.
Яңы бит яһар өсөн аҫтағы тәҙрәгә текст керетегеҙ (тулыраҡ мәғлүмәт өсөн [[{{MediaWiki:Helppage}}|ярҙам битен]] ҡарағыҙ).
Әгәр был биткә яңылыш килеп эләккән булһағыҙ, браузерығыҙҙың '''артҡа''' төймәһенә баҫығыҙ.",
'anontalkpagetext' => "----''Был фекер алышыу бите, иҫәп яҙыуы булдырмаған йәки уны ҡулланмаған аноним ҡатнашыусының бите.
Шуның өсөн ҡулланыусыны таныу өсөн IP-адресы ҡулланыла.
Әгәр һеҙ аноним ҡулланыусы булһағыҙ һәм һеҙгә ебәрелмәгән хәбәрҙәр алдым тиһәгеҙ (бер IP-адрес күп ҡулланыусы өсөн булырға мөмкин) һәм башҡа бындай аңлашылмаусанлыҡтар килеп сыҡмаһын өсөн, зинар, [[Special:UserLogin|системаға керегеҙ]] йәки [[Special:UserLogin/signup|теркәлегеҙ]].''",
'noarticletext' => "Хәҙерге ваҡытта был биттә текст юҡ.
Һеҙ [[Special:Search/{{PAGENAME}}|был исемде башҡа биттәрҙә эҙләй]],
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тап килгән журнал яҙмаларын таба]
йәки '''[{{fullurl:{{FULLPAGENAME}}|action=edit}} бындай исемле яңы бит яһай]'''</span> алаһығыҙ.",
'noarticletext-nopermission' => 'Хәҙерге ваҡытта был биттә текст юҡ.
Һеҙ башҡа биттәрҙә [[Special:Search/{{PAGENAME}}|был исемде]] йәки
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} журналдағы яҙмаларҙы] эҙләй алаһығыҙ, тик һеҙҙең бит яһау хоҡуғығыҙ юҡ.</span>',
'missing-revision' => '"{{PAGENAME}}" исемле биттең $1 номерлы өлгөһө юҡ.

Был хәл, ғәҙәттә, юйылған биткә яһалған һылтанманын ваҡыты үтеүенән барлыҡҡа килә.
Тулыраҡ мәғлүмәт өсөн ҡарағыҙ: [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} юйыу яҙмалары].',
'userpage-userdoesnotexist' => '«<nowiki>$1</nowiki>» иҫәп яҙыуы юҡ. Һеҙ бындай бит яһарға йәки битте үҙгәртергә теләһәгеҙ яңынан тикшерегеҙ.',
'userpage-userdoesnotexist-view' => '«$1» исемле иҫәп яҙыуы юҡ.',
'blocked-notice-logextract' => 'Хәҙергә был ҡатнашыусы ябылған. Һуңғы ҡулланыусы ябыу яҙмаһы:',
'clearyourcache' => "'''Иҫкәрмә:''' Битте һаҡлағандан һуң үҙгәртеүҙерегеҙ күренһен өсөн, браузерығыҙҙың кэшын таҙартығыҙ.
* '''Firefox / Safari:''' ''Shift'' төймәһенә баҫып, ебәрмәйенсә, ''Яңыртырға'' төймәһенә баҫығыҙ, йәки ''Ctrl-F5'' йә ''Ctrl-R'' (Mac-та ''⌘-R'') төймәләренә баҫығыҙ
* '''Google Chrome:''' ''Ctrl-Shift-R'' төймәһенә баҫығыҙ (Mac-та ''⌘-Shift-R'')
* '''Internet Explorer:''' ''Ctrl'' төймәһенә баҫып, ебәрмәйенсә, ''Яңыртырға'' төймәһенә баҫығыҙ, йәки ''Ctrl-F5'' төймәһенә баҫығыҙ
* '''Opera:''' ''Ҡоралдар → Көйләүҙәр' менюһында кеш таҙартыуҙы һайлағыҙ",
'usercssyoucanpreview' => "'''Кәңәш:''' Яңы CSS-файлды һаҡларҙан алда тикшерер өсөн \"{{int:showpreview}}\" төймәһенә баҫығыҙ.",
'userjsyoucanpreview' => "'''Кәңәш:''' Яңы JS-файлды һаҡларҙан алда тикшерер өсөн \"{{int:showpreview}}\" төймәһенә баҫығыҙ.",
'usercsspreview' => "'''Был бары тик CSS файлды алдан ҡарау ғына, ул әле һаҡланмаған!'''",
'userjspreview' => "'''Был бары тик JavaScript файлын алдан ҡарау ғына, ул әле һаҡланмаған!'''",
'sitecsspreview' => "'''Һеҙ CSS файлын алдан ҡарайһығыҙ ғына икәнен иҫегеҙҙә тотоғоҙ.'''
'''Ул әле һаҡланмаған!'''",
'sitejspreview' => "'''Һеҙ JavaScript кодын алдан ҡарайһығыҙ ғына икәнен иҫегеҙҙә тотоғоҙ.'''
'''Ул әле һаҡланмаған!'''",
'userinvalidcssjstitle' => "'''Иғтибар:''' \"\$1\" биҙәү темаһы табылманы. Иҫтә тотоғоҙ, .css һәм .js ҡулланыусы биттәренең исемдәре тик бәләкәй хәрефтәрҙән генә торорға тейеш. Мәҫәлән: {{ns:user}}:Foo/vector.css, ә {{ns:user}}:Foo/Vector.css түгел!",
'updated' => '(Яңыртылды)',
'note' => "'''Иҫкәрмә:'''",
'previewnote' => "'''Ҡарап сығыу өлгөһө, әлегә үҙгәрештәр яҙҙырылмаған!'''
Һеҙҙең үҙгәртеүҙәр әле яҙылмаған!",
'continue-editing' => 'Мөхәррирләү өлкәһенә күсергә',
'previewconflict' => 'Әлеге алдан ҡарау, мөхәррирләү тәҙрәһенең өҫтөндә, һаҡланғандан һуң текстың нисек күренәсәген күрһәтә.',
'session_fail_preview' => "'''Ҡыҙғанысҡа ҡаршы, һеҙҙең ултырыш идентификаторығыҙ юғалды. Һөҙөмтәлә үҙгәртеүҙәрегеҙ ҡабул ителмәйәсәк.
Зинһар, тағы бер тапҡыр ҡабатлағыҙ.
Әгәр был хата ҡабатланһа, [[Special:UserLogout|ултырышты тамамлағыҙ]] һәм яңынан керегеҙ.'''",
'session_fail_preview_html' => "'''Ҡыҙғанысҡа ҡаршы, һеҙҙең ултырыш мәғлүмәттәрегеҙ юғалды. Һөҙөмтәлә, сервер үҙгәрештерегеҙҙе ҡабул итә алмай.'''

''{{SITENAME}} тик таҙа HTML ҡулланыуҙы ғына рөхсәт итә; алдан ҡарау, JavaScript-атакаларҙан һаҡланыу маҡсаты менән ябылған.''

'''Әгәр һеҙ үҙгәртеүҙе яҡшы ниәт менән башҡараһағыҙ икән, тағы бер тапҡыр ҡабатлап ҡарағыҙ. Хата ҡабатланһа, сайттан [[Special:UserLogout|сығығыҙ]] һәм яңынан керегеҙ.'''",
'token_suffix_mismatch' => "'''Һеҙҙең үҙгәртеү ҡабул ителмәне, сөнки һеҙҙең программа мөхәррирләү тәҙрәһендә тыныш билдәләрен дөрөҫ эшкәртмәй.'''
Мәҡәлә текстын боҙолоуҙан һаҡлау өсөн үҙгәртеүегеҙ кире алынды.
Бындай хәлдәр хаталы аноним web-проксилар ҡулланғанда килеп сығырға мөмкин.",
'edit_form_incomplete' => "'''Мөхәррирләү формаһының ҡайһы өлөштәре серверға барып етмәне. Төҙәтеүҙәрегеҙҙе яҡшы итеп тикшерегеҙ һәм яңынан ҡабатлағыҙ.'''",
'editing' => 'Мөхәррирләү  $1',
'creating' => 'Төҙөү $1',
'editingsection' => 'Мөхәррирләү  $1 (секция)',
'editingcomment' => '$1 мөхәррирләнә (яңы бүлек)',
'editconflict' => 'Мөхәррирләү конфликты: $1',
'explainconflict' => 'Һеҙ был битте мөхәррирләгән ваҡытта кемдер яңы үҙгәрештәр керетте.
Мөхәррирләү тәҙрәһенең өҫкө өлөшөндә биттең ағымдағы текстын күрәһегеҙ.
Аҫта, һеҙҙең вариант күрһәтелгән. Кереткән үҙгәрештерегеҙҙе аҫҡы тәҙрәнән өҫкә күсерегеҙ.
«{{int:savearticle}}» төймәһенә баҫҡас өҫтәге тәҙрәнең тексты һаҡланасаҡ.',
'yourtext' => 'Һеҙҙең текст',
'storedversion' => 'Һаҡланған версия',
'nonunicodebrowser' => "'''КИҪӘТЕҮ: Һеҙҙең браузер Юникод кодировкаһын танымай.'''
Мәҡәләләрҙе мөхәррирләгән ваҡытта ASCII булмаған символдар махсус уналтылы кодтарға әйләндереләсәк.",
'editingold' => "'''Киҫәтеү: Һеҙ биттең иҫкергән версияһын үҙгәртәһегеҙ.'''
Һаҡлауҙан һуң яңы версияла эшләнгән үҙгәртеүҙәр юғаласаҡ.",
'yourdiff' => 'Айырмалыҡтар',
'copyrightwarning' => "Иғтибар, {{SITENAME}} сайтындағы бөтә өҫтәмәләр һәм үҙгәртеүҙәр $2 (ҡарағыҙ: $1) лицензияһы шарттары менән сығарылған тип иҫәпләнә. Әгәр текстарығыҙҙың ирекле рәүештә таратылыуын һәм төҙәтелеүен теләмәһәгеҙ, уларҙы бында өҫтәмәүегеҙ һорала.<br />
Шулай уҡ, керетелгән үҙгәртеүҙәрҙең авторы булыуығыҙҙы йәки уларҙы эстәлеге ирекле рәүештә таратылырға һәм үҙгәртелергә рөхсәт ителгән сығанаҡтан алыуығыҙҙы раҫлайһығыҙ.<br />
'''РӨХСӘТ АЛМАЙЫНСА АВТОРЛЫҠ ХОҠУҠТАРЫ МЕНӘН ҺАҠЛАНҒАН МАТЕРИАЛДАР ҠУЙМАҒЫҘ!!!'''",
'copyrightwarning2' => "Иғтибар, һеҙ кереткән өҫтәмәләр башҡа ҡатнашыусылар тарафынан үҙгәртелергә йәки юйылырға мөмкин.
Әгәр кемдең дә булһа текстарығыҙҙы үҙгәртеүен теләмәһәгеҙ, уларҙы бында ҡуймағыҙ.<br />
Шулай уҡ, кереткән өҫтәмәләрҙең авторы булыуығыҙҙы йәки уларҙы, эстәлеге ирекле рәүештә таратылырға һәм үҙгәртелергә рөхсәт ителгән сығанаҡтан алыуығыҙҙы раҫлайһығыҙ (ҡарағыҙ: $1).
'''РӨХСӘТҺЕҘ, АВТОРЛЫҠ ХОҠУҠТАРЫ МЕНӘН ҺАҠЛАНҒАН МАТЕРИАЛДАР ҠУЙМАҒЫҘ!'''",
'longpageerror' => "'''ХАТА: һаҡланасаҡ текст күләме $1 килобайт, был иһә рөхсәт ителгән {{PLURAL:$1|$1 килобайттан|$2 килобайттан}} күп. Битте һаҡлап булмай.'''",
'readonlywarning' => "'''КИҪӘТЕҮ: Техник хеҙмәтләндереү сәбәпле мәғлүмәттәр базаһы блокланған, шунлыҡтан үҙгәртеүҙәрегеҙҙе хәҙер һаҡлай алмайһығыҙ.'''
Тексты аҙаҡтан ҡулланыу өсөн файлда һаҡлап тора алаһығыҙ.

Хаким белдергән сәбәп: $1",
'protectedpagewarning' => "'''КИҪӘТЕҮ: Һеҙ был битте үҙгәртә алмайһығыҙ, был хоҡуҡҡа хакимдәр генә эйә.'''
Белешмә өсөн түбәндә һуңғы үҙгәртеү тураһында мәғлүмәт бирелә:",
'semiprotectedpagewarning' => "'''Киҫәтеү:''' был бит һаҡланған. Уны теркәлгән ҡулланыусылар ғына үҙгәртә ала.
Белешмә өсөн түбәндә һуңғы үҙгәртеү тураһында мәғлүмәт бирелә:",
'cascadeprotectedwarning' => "'''КИҪӘТЕҮ:''' Был битте тик хакимдәр генә үҙгәртә ала, сөнки ул эҙмә-эҙлекле һаҡлау ҡуйылған {{PLURAL:$1|киләһе биткә|киләһе биттәргә}} керә:",
'titleprotectedwarning' => "'''Киҫәтеү: Бындый исемле бит һаҡланған, уны үҙгәртеү өсөн [[Special:ListGroupRights|тейешле хоҡуҡҡа]] эйә булыу кәрәк.'''
Белешмә өсөн түбәндә һуңғы үҙгәртеү тураһында мәғлүмәт бирелә:",
'templatesused' => 'Был биттә ҡулланылған {{PLURAL:$1|ҡалып|ҡалыптар}}:',
'templatesusedpreview' => 'Алдан ҡаралған биттә ҡулланылған {{PLURAL:$1|ҡалып|ҡалыптар}}:',
'templatesusedsection' => 'Был бүлектә ҡулланылған {{PLURAL:$1|ҡалып|ҡалыптар}}:',
'template-protected' => '(һаҡланған)',
'template-semiprotected' => '(өлөшләтә һаҡланған)',
'hiddencategories' => 'Был бит $1 {{PLURAL:$1|йәшерен категорияға}} керә:',
'nocreatetext' => '{{SITENAME}}, яңы бит яһауҙы рөхсәт итмәгән.
Һеҙ кире ҡайта һәм булған битте мөхәррирләй, [[Special:UserLogin|системала таныла йәки яңы иҫәп яҙыуы яһай]] алаһығыҙ.',
'nocreate-loggedin' => 'Яңы биттәр яһау хоҡуғығыҙ юҡ.',
'sectioneditnotsupported-title' => 'Бүлектәрҙә мөхәррирләү терәкләнмәй',
'sectioneditnotsupported-text' => 'Был биттә бүлектәрҙе мөхәррирләү терәкләнмәй.',
'permissionserrors' => 'Инеү хоҡуғы хатаһы',
'permissionserrorstext' => 'Түбәндәге {{PLURAL:$1|сәбәп|сәбәптәр}} буйынса һеҙҙең был ғәмәлде үтәү хоҡуғығыҙ юҡ:',
'permissionserrorstext-withaction' => "«'''$2'''» ғәмәлен башҡара алмайһығыҙ. {{PLURAL:$1|Сәбәбе|Сәбәптәре}}:",
'recreate-moveddeleted-warn' => "'''Иғтибар: Һеҙ, элек юйылған битте яңынан яһарға теләйһегеҙ.'''

Һеҙгә был битте яңынан яһау кәрәклеген яңынан уйлап ҡарағыҙ.
Түбәндә биттең юйыу һәм исем үҙгәртеү яҙмалары килтерелә:",
'moveddeleted-notice' => 'Был бит юйылған.
Белешмә өсөн киләһе юйыу һәм исем үҙгәртеү яҙмалары килтерелә.',
'log-fulllog' => 'Бар яҙмаларҙы ҡарарға',
'edit-hook-aborted' => 'Үҙгәртеүҙе ҡармаҡ-процедура кире ҡаҡты.
Өҫтәмә аңлатма килтерелмәй.',
'edit-gone-missing' => 'Битте яңыртып булмай.
Бәлки ул юйылғандыр.',
'edit-conflict' => 'Төҙәтеүҙәр конфликты',
'edit-no-change' => 'Текста үҙгәртеүҙер булмау сәбәпле үҙгәртеүегеҙгә иғтибар ителмәне.',
'postedit-confirmation' => 'Үҙгәртеүегеҙ һаҡланды.',
'edit-already-exists' => 'Яңы бит яһап булмай.
Ул былай ҙа бар.',
'defaultmessagetext' => 'Алдан билдәләнгән яҙма',
'content-failed-to-parse' => '$2 эстәлеге $1 төрөнә тура килмәй: $3.',
'invalid-content-data' => 'Ярамаған мәғлүмәт',
'content-not-allowed-here' => '"$1" эстәлеге [[$2]] бит өсөн ярамай',
'editwarning-warning' => 'Икенсе биткә күсеү һеҙ индергән үҙгәрештәрҙең юғалыуына килтереүе мөмкин.
Әгәр системала танылыу үтһәгеҙ, көйләүҙәрегеҙ битенең "Мөхәррирләү" бүлегендә был киҫәтеүҙе һүндерә алаһығыҙ.',

# Content models
'content-model-wikitext' => 'викияҙма',
'content-model-text' => 'ғәҙәти яҙма',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Иғтибар:''' был биттә хәтерҙе күп ҡулланыусы функциялар ныҡ күп.

Ҡуйылған сикләү: $2 {{PLURAL:$2|ҡулланыу}}, был осраҡта {{PLURAL:$1|$1 тапҡыр}} башҡарырға рөхсәт ителә.",
'expensive-parserfunction-category' => 'Хәтерҙе күп ҡулланыусы функциялары күп булған биттәр',
'post-expand-template-inclusion-warning' => "'''Иғтибар:''' Өҫтәлгән ҡалыптар күләме бик ҙур.
Ҡайһы бер ҡалыптар өҫтәлмәйәсәк.",
'post-expand-template-inclusion-category' => 'Рөхсәт ителгән күләмдән күп ҡалып ҡушылған биттәр',
'post-expand-template-argument-warning' => "'''Иғтибар:''' Был бит, асыу өсөн бик ҙур күләмле кәмендә бер ҡалып аргументына эйә.
Бындай аргументтар төшөрөп ҡалдырылды.",
'post-expand-template-argument-category' => 'Төшөрөп ҡалдырылған ҡалып аргументтары булған биттәр',
'parser-template-loop-warning' => 'Төйөн табылған ҡалыптар: [[$1]]',
'parser-template-recursion-depth-warning' => '($1) ҡалыбын рекурсия итеп ҡулланыу тәрәнлеге рөхсәт ителгәндән артып киткән',
'language-converter-depth-warning' => 'Телдәрҙе үҙгәртеү тәрәнлегенең сиге үткән ($1)',
'node-count-exceeded-category' => 'Төйөндәр һаны артҡан биттәр',
'node-count-exceeded-warning' => 'Биттә төйөндәр һаны артып киткән',
'expansion-depth-exceeded-category' => 'Асылыу тәрәнлеге артып киткән биттәр',
'expansion-depth-exceeded-warning' => 'Биттә һалыныу тәрәнлеге сиге үтеп кителгән',
'parser-unstrip-loop-warning' => 'Ябылмаған pre табылды',
'parser-unstrip-recursion-limit' => '($1) рекурсия сиге үтеп кителгән',
'converter-manual-rule-error' => 'Тел әйлендереү ҡағиҙәһендә хата табылды',

# "Undo" feature
'undo-success' => 'Был үҙгәртеүҙе кире алып була. Зинһар, улар һеҙҙе ҡыҙыҡһындырған үҙгәртеүҙәр булыуынан шикләнмәҫ өсөн версияларҙы сағыштырыуҙы ҡарағыҙ һәм үҙгәртеүҙәрҙе ғәмәлғә керетер өсөн «Битте һаҡларға» төймәһенә баҫығыҙ.',
'undo-failure' => 'Ара үҙгәртеүҙәр тура килмәү сәбәпле төҙәтеүҙе кире алып булмай.',
'undo-norev' => 'Үҙгәртеүҙе кире алып булмай, сөнки юҡ йәки юйылған.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ҡулланыусыһының ([[User talk:$2|фекер алышыу]]) $1 үҙгәртеүенән баш тартыу',
'undo-summary-username-hidden' => 'Исеме йәшерелгән ҡатнашыусының төҙәтеүен  $1 кире ҡағыу',

# Account creation failure
'cantcreateaccounttitle' => 'Иҫәп яҙыуын яһап булмай',
'cantcreateaccount-text' => "Был IP-адрестан (<b>$1</b>) иҫәп яҙыуҙары булдырыу [[User:$3|$3]] тарафынан тыйылған.

$3 белдергән сәбәп: ''$2''",

# History pages
'viewpagelogs' => 'Был биттең яҙмаларын ҡарарға',
'nohistory' => 'Был биттең үҙгәртеүҙәр тарихы юҡ.',
'currentrev' => 'Ағымдағы версия',
'currentrev-asof' => '$1, ағымдағы версия',
'revisionasof' => '$1 версияһы',
'revision-info' => 'Версия: $1; $2',
'previousrevision' => '← Алдағы',
'nextrevision' => 'Киләһе →',
'currentrevisionlink' => 'Ағымдағы версия',
'cur' => 'хәҙ.',
'next' => 'киләһе',
'last' => 'алд.',
'page_first' => 'беренсе',
'page_last' => 'аҙаҡҡы',
'histlegend' => "Айырма һайлау: сағыштырырға теләгән 2 версияны һайлап Enter-ға йәки биттең аҫҡы өлөшөндәге төймәгә баҫығыҙ.<br />
Аңлатмалар: '''({{int:cur}})''' — хәҙерге версиянан айырма, '''({{int:last}})''' — алдағы версиянан айырма, '''{{int:minoreditletter}}''' — әҙ үҙгәреш яһалған.",
'history-fieldset-title' => 'Тарихты ҡарарға',
'history-show-deleted' => 'Юйылғандар ғына',
'histfirst' => 'Иң иҫкеләр',
'histlast' => 'Иң һуңғылар',
'historysize' => '($1 {{PLURAL:$1|байт}})',
'historyempty' => '(буш)',

# Revision feed
'history-feed-title' => 'Үҙгәртеүҙәр тарихы',
'history-feed-description' => 'Викилағы был биттең үҙгәртеүҙәр тарихы',
'history-feed-item-nocomment' => '$1, $2',
'history-feed-empty' => 'Һоратылған бит юҡ.
Ул бит юйылған йәки исеме үҙгәртелгән булыуы мөмкин.
Викила оҡшаш биттәрҙе [[Special:Search|эҙләп ҡарағыҙ]].',

# Revision deletion
'rev-deleted-comment' => '(мөхәррирләү тасуирламаһы юйылған)',
'rev-deleted-user' => '(автор исеме юйылған)',
'rev-deleted-event' => '(яҙма юйылған)',
'rev-deleted-user-contribs' => '[ҡулланыусы исеме йәки IP-адрес юйылған — төҙәтеү өлөш битенән йәшерелде]',
'rev-deleted-text-permission' => "Биттең был өлгөһө '''юйылған'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Юйыу яҙмалары журналында] аңлатмалар ҡалдырылыуы мөмкин.",
'rev-deleted-text-unhide' => "Биттең был версияһы '''юйылған'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Юйыу яҙмаларында] аңлатмалар ҡалдырылыуы мөмкин.
Теләгегеҙ булһа [был версияны $1] ҡарай алаһығыҙ.",
'rev-suppressed-text-unhide' => "Биттең был версияһы '''Йәшерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Йәшереү яҙмаларында] аңлатмалар ҡалдырылыуы мөмкин.
Теләгегеҙ булһа [был версияны $1] ҡарай алаһығыҙ.",
'rev-deleted-text-view' => "Биттең был версияһы '''юйылған'''.
Һеҙ уны [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Юйыу яҙмаларында] ҡарай алаһығыҙ .",
'rev-suppressed-text-view' => "Биттең был версияһы '''йәшерелгән'''.
Һеҙ уны [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} йәшереү яҙмаларында] ҡарай алаһығыҙ .",
'rev-deleted-no-diff' => "Һеҙ версиялар араһындағы айырманы ҡарай алмайһығыҙ, сөнки бит версияларының береһе '''юйылған'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Юйыу яҙмаларында] аңлатмалар ҡалдырылыуы мөмкин.",
'rev-suppressed-no-diff' => "Һеҙ был версияляр араһындағы айырманы ҡарай алмайһығыҙ, сөнки уларҙың береһе '''юйылған'''.",
'rev-deleted-unhide-diff' => "Биттең версияларының береһе '''юйылған'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Юйыу яҙмаларында] тулыраҡ мәғлүмәт таба алаһығыҙ.
Теләгегеҙ булһа [$1 версиялар айырмаһын] ҡарай алаһығыҙ.",
'rev-suppressed-unhide-diff' => "Биттең версияларының береһе '''йәшерелгән'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} Йәшереү яҙмаларында] тулыраҡ мәғлүмәт таба алаһығыҙ.
Теләгегеҙ булһа [$1 версиялар айырмаһын] ҡарай алаһығыҙ.",
'rev-deleted-diff-view' => "Был сағыштырыу версияларының береһе '''юйылған'''.
Һеҙ был сағыштырыуҙы [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} юйыу яҙмаларында] ҡарай алаһығыҙ .",
'rev-suppressed-diff-view' => "Был сағыштырыу версияларының береһе '''йәшерелгән'''.
Һеҙ был сағыштырыуҙы [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} йәшереү яҙмаларында] ҡарай алаһығыҙ .",
'rev-delundel' => 'күрһәт/йәшер',
'rev-showdeleted' => 'күрһәтергә',
'revisiondelete' => 'Бит версияларын юйырға/тергеҙергә',
'revdelete-nooldid-title' => 'Маҡсат версия билдәләнмәгән',
'revdelete-nooldid-text' => 'Был функцияны үтәү өсөн һеҙ маҡсат версияны (йәки версияларҙы) билдәләмәнегеҙ. Билдәләнгән версия юҡ йәки версияны йәшерергә тырышаһығыҙ.',
'revdelete-nologtype-title' => 'Яҙма тибы билдәләнмәгән',
'revdelete-nologtype-text' => 'Ғәмәл үтәләсәк яҙма тибын билдәләмәгәнһегеҙ.',
'revdelete-nologid-title' => 'Яҙмалағы яҙыу хаталы',
'revdelete-nologid-text' => 'Ғәмәлде үтәү өсөн яҙманың маҡсат яҙыуын күрһәтмәнегеҙ йәки күрһәтелгән яҙыу юҡ.',
'revdelete-no-file' => 'Күрһәтелгән файл юҡ.',
'revdelete-show-file-confirm' => '$2, $3 ваҡытлы «<nowiki>$1</nowiki> файлының юйылған версияһын ҡарарға теләүегеҙҙе раҫлайһығыҙмы?',
'revdelete-show-file-submit' => 'Эйе',
'revdelete-selected' => "'''[[:$1]] битенең {{PLURAL:$2|һайланған версияһы|һайланған версиялары}}:'''",
'logdelete-selected' => "'''Яҙманың {{PLURAL:$1|һайланған яҙыуы|һайланған яҙыуҙары}}:'''",
'revdelete-text' => "'''Биттәрҙең юйылған версиялары һәм ваҡиғалар, бит тарихында һәм яҙмаларҙа күрһәтеләсәк, ләкин уларҙың эстәлектәренең бер өлөшө ябай ҡулланыусыларға асыҡ булмаясаҡ.'''
{{SITENAME}} проектының хакимдәре йәшерен эстәлеккә керә һәм өҫтәмә сикләүҙәр ҡуйылған осраҡтарҙан тыш, ошо уҡ арайөҙ аша тергеҙә аласаҡтар.",
'revdelete-confirm' => 'Зинһар, был ғәмәлде үтәргә теләүегеҙҙе, буласаҡ һөҙөмтәләрҙә аңлауығыҙҙы, [[{{MediaWiki:Policy-url}}|ҡағиҙәләр]] буйынса эшләүегеҙҙе раҫлағыҙ.',
'revdelete-suppress-text' => "Йәшереү '''тик''' киләһе осраҡтарҙа ғына башҡарыла:

* Уңай булмаған шәхси мәғлүмәт
* ''өй адресы, телефон номерҙары, паспорт номеры һ.б.''",
'revdelete-legend' => 'Күренеш сикләүҙәрен көйләргә:',
'revdelete-hide-text' => 'Биттең был версияһының текстын йәшерергә',
'revdelete-hide-image' => 'Файл эстәлеген йәшерергә',
'revdelete-hide-name' => 'Ғәмәлде һәм маҡсатын йәшерергә',
'revdelete-hide-comment' => 'Үҙгәртеү тасуирламаларын йәшерергә',
'revdelete-hide-user' => 'Мөхәррирләүсенең исемен/IP-адресын йәшерергә',
'revdelete-hide-restricted' => 'Мәғлүмәттәрҙе хакимдәрҙән дә йәшерергә',
'revdelete-radio-same' => '(үҙгәртмәҫкә)',
'revdelete-radio-set' => 'Эйе',
'revdelete-radio-unset' => 'Юҡ',
'revdelete-suppress' => 'Мәғлүмәттәрҙе шулай уҡ хакимдәрҙән дә йәшерергә',
'revdelete-unsuppress' => 'Тергеҙелгән версияларҙан бар сикләүҙәрҙе алырға',
'revdelete-log' => 'Сәбәп:',
'revdelete-submit' => 'Һайланған {{PLURAL:$1|версия|версиялар}} өсөн ҡулланырға',
'revdelete-success' => "'''Версия күренеүсәнлеге уңышлы үҙгәртелде.'''",
'revdelete-failure' => "'''Версия күренеүсәнлеген үҙгәртеп булмай:'''
$1",
'logdelete-success' => "'''Яҙма күренеүсәнлеге үҙгәртелде.'''",
'logdelete-failure' => "'''Яҙма күренеүсәнлеге көйләнмәгән:'''
$1",
'revdel-restore' => 'Күренеүсәнлекте үҙгәртергә',
'revdel-restore-deleted' => 'юйылған өлгөләр',
'revdel-restore-visible' => 'ҡара алған өлгөләр',
'pagehist' => 'Бит тарихы',
'deletedhist' => 'Юйылған тарих',
'revdelete-hide-current' => '$2, $1 ваҡытлы яҙманы йәшереүҙә хата.
Уны йәшереп булмай.',
'revdelete-show-no-access' => '$2, $1 ваҡытлы яҙманы асыуҙа хата: был яҙма «сикләнгән» тип билдәләнгән.
Уға ирешеү хоҡуғығыҙ юҡ.',
'revdelete-modify-no-access' => '$2, $1 ваҡытлы яҙманы үҙгәртеүҙә хата: был яҙма «сикләнгән» тип билдәләнгән.
Уға ирешеү хоҡуғығыҙ юҡ.',
'revdelete-modify-missing' => 'ID $1 яҙмаһын үҙгәртеүҙә хата: ул мәғлүмәттәр базаһында юҡ!',
'revdelete-no-change' => "'''Иғтибар:'''  $2 $1 ваҡытлы яҙыу, һоратылған күренеүсәнлек көйләүҙәренә эйә.",
'revdelete-concurrent-change' => '$2, $1 ваҡытлы яҙманы үҙгәртеүҙә хата: һеҙ уны үҙгәртергә тырышҡан ваҡытта уның статусын башҡа берәү үҙгәрткән.
Зинһар, яҙмаларҙы ҡарағыҙ.',
'revdelete-only-restricted' => '$2, $1 ваҡытлы яҙманы йәшереүҙә хата: башҡа йәшереү көйләүҙәренең береһен һайламайынса яҙманы хакимдәрҙән йәшерә алмайһығыҙ.',
'revdelete-reason-dropdown' => '* Ғәҙәттәге юйыу сәбәптәре
** Авторлыҡ хоҡуҡтарын боҙоу
** Урынһыҙ комментарий йәки шәхси мәғлүмәт
** Урынһыҙ ҡулланыусы исеме
** Ялған булыуы ихтимал мәғлүмәт',
'revdelete-otherreason' => 'Башҡа/өҫтәмә сәбәп:',
'revdelete-reasonotherlist' => 'Башҡа сәбәп',
'revdelete-edit-reasonlist' => 'Сәбәптәр исемлеген мөхәррирләргә',
'revdelete-offender' => 'Бит версияһы авторы:',

# Suppression log
'suppressionlog' => 'Йәшереү яҙмалары',
'suppressionlogtext' => 'Түбәндә, администраторҙарҙан йәшерелгән материалдар булған һуңғы юйыуҙыр һәм блоклауҙар исемлеге килтерелгән.
Ағымдағы блоклауҙарҙы күрер өсөн [[Special:BlockList|блоклауҙар исемлеген]] ҡарағыҙ.',

# History merging
'mergehistory' => 'Үҙгәртеүҙәр тарихын берләштерергә',
'mergehistory-header' => 'Был бит, ике биттең үҙгәртеүҙәр тарихын берләштереү мөмкинлеген бирә.
Берләштереүҙең үҙгәртеүҙәр тарихын боҙмауын тикшерегеҙ.',
'mergehistory-box' => 'Ике биттең үҙгәртеүҙәр тарихын берләштерергә',
'mergehistory-from' => 'Сығанаҡ бит:',
'mergehistory-into' => 'Маҡсат бит:',
'mergehistory-list' => 'Берләштереп булған үҙгәртеүҙәр тарихы',
'mergehistory-merge' => '[[:$1]] версияларын [[:$2]] менән берләштерерге була. Тик һайланған төҙәтеүҙәр аралығын берләштерер өсөн түңәрәк төймәләрҙе ҡулланығыҙ. Навигация һылтанмаларын ҡулланғанда мәғлүмәттәрҙең юғаласағын онотмағыҙ.',
'mergehistory-go' => 'Берләштереп булған үҙгәртеүҙәрҙе күрһәтергә',
'mergehistory-submit' => 'Версияларҙы берләштер',
'mergehistory-empty' => 'Һис бер версияны берләштереп булмай.',
'mergehistory-success' => '[[:$1]] битенең $3 {{PLURAL:$3|үҙгәртеүе}} уңышлы [[:$2]] менән берләштерелде.',
'mergehistory-fail' => 'Бит тарихтарын берләштереп булманы, зирһар, бит һәм ваҡыт параметрҙарын яңынан тикшерегеҙ.',
'mergehistory-no-source' => 'Сығанаҡ бит «$1» юҡ.',
'mergehistory-no-destination' => 'Маҡсат бит «$1» юҡ.',
'mergehistory-invalid-source' => 'Сығанаҡ биттең исеме дөрөҫ булырға тейеш.',
'mergehistory-invalid-destination' => 'Маҡсат биттең исеме дөрөҫ булырға тейеш',
'mergehistory-autocomment' => '[[:$1]] [[:$2]] менән берләштерелде',
'mergehistory-comment' => '[[:$1]] [[:$2]] менән берләштерелде: $3',
'mergehistory-same-destination' => 'Сығанаҡ һәм маҡсат бит бер иш булырға тейеш түгел',
'mergehistory-reason' => 'Сәбәп:',

# Merge log
'mergelog' => 'Берләштереүҙәр яҙмаһы',
'pagemerge-logentry' => '[[$1]] менән [[$2]] берлештерелде ($3 тиклемге версиялар)',
'revertmerge' => 'Бүлергә',
'mergelogpagetext' => 'Түбәндә һуңғы биттәр үҙгәртеү тарихтарын берләштереүҙәр исемлеге килтерелгән.',

# Diffs
'history-title' => '$1 битенең үҙгәртеү тарихы',
'difference-title' => '$1 — версиялар араһындағы айырма',
'difference-title-multipage' => '«$1» һәм «$2» биттәре араһындағы айырма',
'difference-multipage' => '(Биттәр араһындағы айырма)',
'lineno' => '$1 юл:',
'compareselectedversions' => 'Һайланған версияларҙы сағыштырыу',
'showhideselectedversions' => 'Һайланған версияларҙы күрһәтергә/йәшерергә',
'editundo' => 'кире алыу',
'diff-empty' => '(айырмалар юҡ)',
'diff-multi' => '({{PLURAL:$2|$2 ҡатнашыусының}} {{PLURAL:$1|ваҡытлы версияһы}} күрһәтелмәгән)',
'diff-multi-manyusers' => '(Кәмендә {{PLURAL:$2|$2 ҡатнашыусының}} {{PLURAL:$1|ваҡытлы версияһы}} күрһәтелмәгән)',
'difference-missing-revision' => '$1 айырмаһының {{PLURAL:$2|бер өлгөһө|$2 өлгөһө}} табылманы.

Был хәл, ғәҙәттә, юйылған биткә яһалған айырма һылтанмаһының ваҡыты үтеүенән барлыҡҡа килә.
Тулыраҡ мәғлүмәт өсөн ҡарағыҙ: [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} юйыу яҙмалары].',

# Search results
'searchresults' => 'Эҙләү һөҙөмтәләре',
'searchresults-title' => '«$1» өсөн эҙләү һөҙөмтәләре',
'searchresulttext' => '{{SITENAME}} биттәрендә эҙләү тураһында тулыраҡ мәғлүмәт өсөн ҡарағыҙ: [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => '«[[:$1]]» өсөн эҙләү ([[Special:Prefixindex/$1|«$1» ҙән башлап барлык биттәр]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» ға һылтанған барлык биттәр]])',
'searchsubtitleinvalid' => '«$1» һоратыуы буйынса',
'toomanymatches' => 'Бигерәк күп тап килеүҙәр табылды, зинһар, башҡа һорау яҙып ҡарағыҙ',
'titlematches' => 'Бит исемдәрендә тап килеүҙәр',
'notitlematches' => 'Бит исемдәрендә тап килеүҙәр юҡ',
'textmatches' => 'Бит эстәлегендә тап килеүҙәр',
'notextmatches' => 'Тап килгән бит табылманы',
'prevn' => 'алдағы {{PLURAL:$1|$1}}',
'nextn' => 'киләһе {{PLURAL:$1|$1}}',
'prevn-title' => 'Һуңғы $1 {{PLURAL:$1|һөҙөмтә|һөҙөмтә}}',
'nextn-title' => 'Тәүге $1 {{PLURAL:$1|һөҙөмтә|һөҙөмтә}}',
'shown-title' => 'Бер биттә $1 {{PLURAL:$1|һөҙөмтә|һөҙөмтә}} күрһәт',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) ҡарарға',
'searchmenu-legend' => 'Эҙләү көйләүҙәре',
'searchmenu-exists' => "'''Был вики-проектта «[[:$1]]» бите бар'''",
'searchmenu-new' => "'''Был википроектта \"[[:\$1]]\" бите булдырырға.'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Был префикслы биттәрҙе күрһәтергә]]',
'searchprofile-articles' => 'Эстәлек биттәре',
'searchprofile-project' => 'Ярҙамсы һәм проект биттәре',
'searchprofile-images' => 'Мультимедиа',
'searchprofile-everything' => 'Барыһы',
'searchprofile-advanced' => 'Киңәйтелгән',
'searchprofile-articles-tooltip' => '$1 эсендә эҙлә',
'searchprofile-project-tooltip' => '$1 эсендә эҙлә',
'searchprofile-images-tooltip' => 'Файлдар эҙләү',
'searchprofile-everything-tooltip' => 'Барлыҡ биттәрҙә эҙләү (фекерләшеү биттәрендә лә)',
'searchprofile-advanced-tooltip' => 'Махсус исем арауыҡтарында эҙләргә',
'search-result-size' => '$1 ({{PLURAL:$2|$2 һүҙ|$2 һүҙ}})',
'search-result-category-size' => '{{PLURAL:$1|$1 ағза}} ({{PLURAL:$2|$2 эске категория}}, {{PLURAL:$3|$3 файл}})',
'search-result-score' => 'Тап килеүсәнлек: $1%',
'search-redirect' => '(йүнәлтеү $1)',
'search-section' => '($1 бүлеге)',
'search-suggest' => 'Бәлки, ошоно эҙләйһегеҙҙер: $1',
'search-interwiki-caption' => 'Туғандаш проекттар',
'search-interwiki-default' => '$1 һөҙөмтә:',
'search-interwiki-more' => '(тағы)',
'search-relatedarticle' => 'Ҡағылышлы',
'mwsuggest-disable' => 'Эҙләү өйрәтмәләрен һүндерергә',
'searcheverything-enable' => 'Бар исем арауыҡтарында эҙләргә',
'searchrelated' => 'ҡағылышлы',
'searchall' => 'барыһы',
'showingresults' => 'Түбәндә №&nbsp;<strong>$2</strong> һөҙөмтәнән башлап <strong>$1</strong> {{PLURAL:$1|һөҙөмтә}} күрһәтелгән.',
'showingresultsnum' => 'Түбәндә №&nbsp;<strong>$2</strong> һөҙөмтәнән башлап <strong>$3</strong> {{PLURAL:$3|һөҙөмтә}} күрһәтелгән.',
'showingresultsheader' => "'''$4''' өсөн '''$3''' һөҙөмтәнән {{PLURAL:$5|'''$1''' һөҙөмтә|'''$1 - $2''' арауығындағы һөҙөмтәләр}}",
'nonefound' => "'''Иҫкәрмә'''. Ғәҙәттә эҙләү бөтә исем арауыҡтарында үтәлмәй. Бөтә исем арауыҡтарында (фекер алышыу биттәре, ҡалыптар, һ.б.) эҙләү өсөн һүҙ башына ''all:'' өҫтәгеҙ йәки кәрәкле исем арауыҡтарын һайлағыҙ.",
'search-nonefound' => 'Был һорауға яуап биреүсе һөҙөмтәләр табылманы.',
'powersearch' => 'Киңәйтелгән эҙләү',
'powersearch-legend' => 'Киңәйтелгән эҙләү',
'powersearch-ns' => 'Исем аралыҡтарында эҙләү:',
'powersearch-redir' => 'Йүнәлтеүҙәрҙе күрһәтергә',
'powersearch-field' => 'Эҙлә:',
'powersearch-togglelabel' => 'Һайла:',
'powersearch-toggleall' => 'Барыһы',
'powersearch-togglenone' => 'Һис бере',
'search-external' => 'Тышҡы эҙләү',
'searchdisabled' => '{{SITENAME}} эҙләүе ябыҡ.
Хәҙергә эҙләүҙе Google менән үтәй алаһығыҙ.
Тик унда {{SITENAME}} өсөн индекслауҙың иҫке булыуы мөмкинлеген онотмағыҙ.',
'search-error' => 'Эҙләүҙә хата китте: $1',

# Preferences page
'preferences' => 'Көйләүҙәр',
'mypreferences' => 'Көйләүҙәр',
'prefs-edits' => 'Төҙәтеүҙәр һаны:',
'prefsnologin' => 'Танылмағанһығыҙ',
'prefsnologintext' => 'Ҡатнашыусы көйләүҙәрен үҙгәртеү өсөн, һеҙ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}}танылырға]</span> тейешһегеҙ.',
'changepassword' => 'Серһүҙҙе үҙгәртергә',
'prefs-skin' => 'Күренеш',
'skin-preview' => 'Алдан байҡау',
'datedefault' => 'Ғәҙәттәге',
'prefs-beta' => 'Бета версияһы мөмкинлектәре',
'prefs-datetime' => 'Көн һәм ваҡыт',
'prefs-labs' => 'Һынау өсөн мөмкинлектәр',
'prefs-user-pages' => 'Ҡатнашыусы бите',
'prefs-personal' => 'Шәхси мәғлүмәттәр',
'prefs-rc' => 'Һуңғы үҙгәртеүҙәр',
'prefs-watchlist' => 'Күҙәтеү исемлеге',
'prefs-watchlist-days' => 'Күҙәтеү исемлегендә нисә көн керетелгән үҙгәртеүҙәрҙе күрһәтергә:',
'prefs-watchlist-days-max' => 'Максимум $1 {{PLURAL:$1|көн|көн}}',
'prefs-watchlist-edits' => 'Киңәйтелгән күҙәтеү исемлегендә күрһәтелә торған үҙгәртеүҙәр һанының сиге:',
'prefs-watchlist-edits-max' => 'Иң күбе: 1000',
'prefs-watchlist-token' => 'Күҙәтеү исемлеге токены:',
'prefs-misc' => 'Башҡа көйләүҙәр',
'prefs-resetpass' => 'Серһүҙҙе үҙгәртергә',
'prefs-changeemail' => 'Электрон почта адресын үҙгәртергә',
'prefs-setemail' => 'Электрон почта адресын көйләү',
'prefs-email' => 'Электрон почта көйләүҙәре',
'prefs-rendering' => 'Күренеш',
'saveprefs' => 'Һаҡларға',
'resetprefs' => 'Һаҡланмаған үҙгәрештерҙе таҙартырға',
'restoreprefs' => 'Алдан ҡуйылған көйләүҙәрҙе тергеҙергә',
'prefs-editing' => 'Мөхәррирләү',
'rows' => 'Юлдар:',
'columns' => 'Бағаналар:',
'searchresultshead' => 'Эҙләү',
'resultsperpage' => 'Биттә табылған яҙыуҙар',
'stub-threshold' => '<a href="#" class="stub">Материалдарға һылтанмалар </a> форматлау сиге (байттарҙа)',
'stub-threshold-disabled' => 'Һүндерелгән',
'recentchangesdays' => 'Күҙәтеү исемлегендә күренгән көндәр һаны:',
'recentchangesdays-max' => 'Иң күбендә $1 {{PLURAL:$1|көн}}',
'recentchangescount' => 'Ғәҙәттә күрһәтелгән үҙгәртеүҙәр һаны:',
'prefs-help-recentchangescount' => 'Һуңғы үҙгәртеүҙәрҙе, биттәр тарихын, журналдарҙы үҙ эсенә ала.',
'prefs-help-watchlist-token2' => 'Был - күҙәтеүҙәрегеҙ исемлегенең веб-каналы өсөн йәшерен асҡыс.
Уны белеүселәр күҙәтеүҙәрегеҙ исемлеген уҡый аласаҡ, шуға уны бер кемгә лә әйтмәгеҙ. [[Special:ResetTokens|Уны ташларға теләһәгеҙ, ошонда баҫығыҙ]].',
'savedprefs' => 'Һеҙҙең көйләүҙәрегеҙ һаҡланды.',
'timezonelegend' => 'Ваҡыт бүлкәте:',
'localtime' => 'Урындағы ваҡыт:',
'timezoneuseserverdefault' => 'Сервер көйләүҙәрен ҡулланырға $1',
'timezoneuseoffset' => 'Башҡа (шылыуҙы керетегеҙ)',
'timezoneoffset' => 'Шылыу:',
'servertime' => 'Сервер ваҡыты',
'guesstimezone' => 'Браузерҙан алырға',
'timezoneregion-africa' => 'Африка',
'timezoneregion-america' => 'Америка',
'timezoneregion-antarctica' => 'Антарктика',
'timezoneregion-arctic' => 'Арктика',
'timezoneregion-asia' => 'Азия',
'timezoneregion-atlantic' => 'Атлантик океан',
'timezoneregion-australia' => 'Австралия',
'timezoneregion-europe' => 'Европа',
'timezoneregion-indian' => 'Һинд океаны',
'timezoneregion-pacific' => 'Тымыҡ океан',
'allowemail' => 'Башҡа ҡулланыусыларҙан электрон хат алыуҙы рөхсәт итергә',
'prefs-searchoptions' => 'Эҙләү',
'prefs-namespaces' => 'Исем арауыҡтары',
'defaultns' => 'Юғиһә киләһе исем арауыҡтарында эҙләргә:',
'default' => 'ғәҙәттәге',
'prefs-files' => 'Файлдар',
'prefs-custom-css' => 'Үҙ CSS',
'prefs-custom-js' => 'Үҙ JS',
'prefs-common-css-js' => 'Бөтә күренештәр өсөн дөйөм CSS/JS:',
'prefs-reset-intro' => 'Был битте, көйләүҙәрегеҙҙе ғәҙәттәге көйләүҙәргә ташлатыу өсөн ҡулланып була.
Раҫлағандан һуң ғәмәлде кире ҡайтарып булмаясаҡ.',
'prefs-emailconfirm-label' => 'Электрон почтаны раҫлау:',
'youremail' => 'Электрон почта *',
'username' => '{{GENDER:$1|Ҡулланыусы исеме}}:',
'uid' => '{{GENDER:$1|Ҡатнашыусы}} номеры:',
'prefs-memberingroups' => '{{PLURAL:$1|төркөм}} {{GENDER:$2|ағзаһы}}:',
'prefs-registration' => 'Теркәлеү ваҡыты:',
'yourrealname' => 'Һеҙҙең ысын исемегеҙ (*)',
'yourlanguage' => 'Тышҡы күренештә ҡулланылған тел:',
'yourvariant' => 'Эстәлектең тел варианты:',
'prefs-help-variant' => 'Вики биттәренең эстәлеген күрһәтеү өсөн өҫтөнлөк бирелгән тел йәки орфография.',
'yournick' => 'Һеҙҙең уйҙырма исемегеҙ/ҡушаматығыҙ (имза өсөн):',
'prefs-help-signature' => 'Әңгәмә биттәрендәге хәбәрҙәрегеҙ һеҙҙең имзағыҙға һәм ваҡытҡа әйләнәсәк "<nowiki>~~~~</nowiki>" тамғаларын өҫтәү юлы менән имзаланырға тейеш.',
'badsig' => 'Хаталы имза. HTML-тегдарҙың дөрөҫлөгөн тикшерегеҙ.',
'badsiglength' => 'Бигерәк оҙон имза. Имза оҙонлоғо $1 {{PLURAL:$1|символдан}} артыҡ булмаҫҡа тейеш.',
'yourgender' => 'Ҡайһы тасуирлама һеҙгә ҡулайыраҡ?',
'gender-unknown' => 'Күрһәткем килмәй',
'gender-male' => 'Ул вики биттәрен мөхәррирләй',
'gender-female' => 'Ул вики биттәрен мөхәррирләй',
'prefs-help-gender' => 'Был көйләүҙе ҡуйыу мотлаҡ түгел.
Программа тәьминәте был мәғлүмәтте һеҙгә грамматика йәһәтенән дөрөҫ мөрәжәғәт итеү өсөн ҡулланасаҡ. 
Был мәғлүмәт бөтәһенә лә күренәсәк.',
'email' => 'Электрон почта',
'prefs-help-realname' => 'Ысын исемегеҙ (теләк буйынса).
Әгәр уны күрһәтһәгеҙ, битте кемдең төҙәткәнен күрһәткәндә ҡулланыласаҡ.',
'prefs-help-email' => 'Электрон почта (теләк буйынса). Күрһәтелгән булһа, ғәмәлдә булған серһүҙегеҙҙе онотҡан осраҡта адресығыҙға яңы серһүҙ ебәреләсәк.
Шулай уҡ башҡа ҡатнашыусылар менән үҙ битегеҙ аша, электрон почтағыҙҙың адресын күрһәтмәйенсә, тура бәйләнешкә инергә мөмкинлек бирә.',
'prefs-help-email-others' => 'Ул шулай уҡ башҡа ҡулланыусыларға, шәхси битегеҙҙәге һылтанма аша, һеҙҙән менән бәйләнешкә инергә рөхсәт бирәсәк.
Һеҙҙең почта адресығыҙ уларға күрһәтелмәйәсәк.',
'prefs-help-email-required' => 'Электрон почта адресы кәрәк.',
'prefs-info' => 'Төп мәғлүмәттәр',
'prefs-i18n' => 'Интернационалләштереү',
'prefs-signature' => 'Имза',
'prefs-dateformat' => 'Ваҡыт форматы',
'prefs-timeoffset' => 'Ваҡыт күсереү:',
'prefs-advancedediting' => 'Дөйөм көйләүҙәр',
'prefs-editor' => 'мөхәррир',
'prefs-preview' => 'алдан байҡау',
'prefs-advancedrc' => 'Киңәйтелгән көйләүҙәр',
'prefs-advancedrendering' => 'Киңәйтелгән көйләүҙәр',
'prefs-advancedsearchoptions' => 'Киңәйтелгән көйләүҙәр',
'prefs-advancedwatchlist' => 'Киңәйтелгән көйләүҙәр',
'prefs-displayrc' => 'Күренеш көйләүҙәре',
'prefs-displaysearchoptions' => 'Күренеш көйләүҙәре',
'prefs-displaywatchlist' => 'Күренеш көйләүҙәре',
'prefs-diffs' => 'Айырмалар',
'prefs-help-prefershttps' => 'Был көйләү системаға киләһе танылыуҙан һуң ҡулланыласаҡ.',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'E-mail адрес дөрөҫ булғанға оҡшаған',
'email-address-validity-invalid' => 'Дөрөҫ e-mail адресын керетегеҙ',

# User rights
'userrights' => 'Ҡулланыусы хоҡуҡтарын идаралау',
'userrights-lookup-user' => 'Ҡулланыусы төркөмдәрен идаралау',
'userrights-user-editname' => 'Ҡулланыусы исемен керетерегеҙ:',
'editusergroup' => 'Ҡулланыусы төркөмдәрен идараларға',
'editinguser' => "Хоҡуҡтары үҙгәртелгән ҡулланыусы '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Ҡулланыусы төркөмдәрен идараларға',
'saveusergroups' => 'Ҡулланыусы төркөмдәрен һаҡларға',
'userrights-groupsmember' => 'Ағза булған төркөмдәр:',
'userrights-groupsmember-auto' => 'Йәшерен ағза булған төркөмдәр:',
'userrights-groups-help' => 'Был ҡулланыусы кергән төркөмдәрҙе үҙгәртә алаһығыҙ.
* Әгәр төркөм исеме эргәһендә билдә булһа, ҡулланыусы төркөмгә кергән була.
* Әгәр билдә булмаһа, ҡулланыусы ул төркөмгә кермәй тимәк.
* * билдәһе, әгәр төркөмдән ҡулланыусыны юйһағыҙ кире ҡуя алмаясағығыҙҙы аңлата һәм киреһенсә.',
'userrights-reason' => 'Сәбәп:',
'userrights-no-interwiki' => 'Һеҙҙең башҡа вики-проекттарҙа ҡатнашыусыларҙың хоҡуҡтарын үҙгәртергә хоҡуҡтарығыҙ юҡ.',
'userrights-nodatabase' => '$1 базаһы юҡ йәки урындағы (локаль) база түгел.',
'userrights-nologin' => 'Ҡатнашыусыларҙың хоҡуҡтарын билдәләр өсөн, һеҙ хаким хоҡуҡтары менән [[Special:UserLogin|танылырға]] тейешһегеҙ.',
'userrights-notallowed' => 'Һеҙгә ҡатнашыусыларҙың хоҡуҡтарын өҫтәргә йәки юҡ итергә рөхсәт ителмәгән.',
'userrights-changeable-col' => 'Һеҙ үҙгәртә алған төркөмдәр',
'userrights-unchangeable-col' => 'Һеҙ үҙгәртә алмаған төркөмдәр',
'userrights-conflict' => 'Ҡатнашыусының хоҡуҡтарын үҙгәртеү яраманы! Зинһар, үҙгәрештәрҙе тикшерегеҙ һәм яңынан индерегеҙ.',
'userrights-removed-self' => 'Һеҙ үҙ хоҡуҡтарығыҙҙы уңышлы юҡ иттегеҙ. Шулай итеп, был биткә башҡаса инә алмаясаҡһығыҙ.',

# Groups
'group' => 'Төркөм:',
'group-user' => 'Ҡулланыусылар',
'group-autoconfirmed' => 'Автоматик раҫланған ҡулланыусылар',
'group-bot' => 'Боттар',
'group-sysop' => 'Хакимдәр',
'group-bureaucrat' => 'Бюрократтар',
'group-suppress' => 'Тикшереүселәр',
'group-all' => '(бөтә)',

'group-user-member' => '{{GENDER:$1|ҡулланыусы}}',
'group-autoconfirmed-member' => '{{GENDER:$1|Автоматик раҫланған ҡулланыусы}}',
'group-bot-member' => '{{GENDER:$1|бот}}',
'group-sysop-member' => '{{GENDER:$1|администратор}}',
'group-bureaucrat-member' => '{{GENDER:$1|бей}}',
'group-suppress-member' => '{{GENDER:$1|күҙәтеүсе}}',

'grouppage-user' => '{{ns:project}}:Ҡулланыусылар',
'grouppage-autoconfirmed' => '{{ns:project}}:Автоматик раҫланған ҡулланыусылар',
'grouppage-bot' => '{{ns:project}}:Боттар',
'grouppage-sysop' => '{{ns:project}}:Хәкимдәр',
'grouppage-bureaucrat' => '{{ns:project}}:Бюрократтар',
'grouppage-suppress' => '{{ns:project}}:Тикшереүселәр',

# Rights
'right-read' => 'Биттәрҙе ҡарау',
'right-edit' => 'Биттәрҙә мөхәррирләү',
'right-createpage' => 'Биттәр булдырыу (фекер алышыу биттәре түгел)',
'right-createtalk' => 'Фекер алышыу битен яһау',
'right-createaccount' => 'Ҡатнашыусыларҙың яңы иҫәп яҙыуҙарын булдырыу',
'right-minoredit' => 'Үҙгәртеүҙәрҙе "Әҙ үҙгәрештәр" тип билдәләү',
'right-move' => 'Биттәрҙең исемен үҙгәртеү',
'right-move-subpages' => 'Ҡушымталары менән бергә биттәрҙең исемен алыштырыу',
'right-move-rootuserpages' => 'Ҡулланыусыларҙың төп биттәренең исемен үҙгәртеү',
'right-movefile' => 'файл исемдәрен үҙгәртеү',
'right-suppressredirect' => 'Биттәрҙең исемен үҙгәрткән ваҡытта сығанаҡ биттән йүнәлтмә булдырылмай',
'right-upload' => 'Файл тейәү',
'right-reupload' => 'Булған файлдың өҫтөнә яҙыу',
'right-reupload-own' => 'Үҙе күсергән файлды яңынан яҙҙырыу',
'right-reupload-shared' => 'Дөйөм һаҡлағыстағы файлды урындағы (локаль) файл менән алыштырыу',
'right-upload_by_url' => 'Файлдарҙы URL адрестан күсереү',
'right-purge' => 'Биттәрҙең кэшын раҫлауһыҙ юйыу',
'right-autoconfirmed' => 'IP-адресҡа тиҙлек сикләүе юҡ',
'right-bot' => 'Үҙенән-үҙе башҡарыла торған эш тип иҫәпләнеү',
'right-nominornewtalk' => 'Фекер алышыу битендә кереткән әҙ үҙгәрештәр яңы хәбәр тураһында белдереү булдырмай',
'right-apihighlimits' => 'API-һорауҙарҙы башҡарыуға сикләүҙәр аҙыраҡ',
'right-writeapi' => 'Яҙҙырыу өсөн API ҡулланыу',
'right-delete' => 'Биттәрҙе юйырға',
'right-bigdelete' => 'Тарихы оҙон булған биттәрҙе юйыу',
'right-deletelogentry' => 'Журналдың билдәле яҙмаларын юйыу һәм тергеҙеү.',
'right-deleterevision' => 'Биттәрҙең күрһәтелгән өлгөләрен юйыу һәм тергеҙеү',
'right-deletedhistory' => 'Биттәрҙең юйылған тарих яҙмаларын текстһыҙ ҡарау',
'right-deletedtext' => 'Биттең юйылған өлгөләре араһындағы юйылған текстты һәм үҙгәртеүҙәрҙе ҡарау',
'right-browsearchive' => 'Юйылған биттәрҙе эҙләү',
'right-undelete' => 'Юйылған биттәрҙе кире ҡайтарыу',
'right-suppressrevision' => 'Биттәрҙең хакимдәрҙән йәшерелгән өлгөләрен ҡарау һәм тергеҙеү',
'right-suppressionlog' => 'Шәхси журналдарҙы ҡарау',
'right-block' => 'Башҡа ҡатнашыусыларға мөхәррирләүҙе тыйыу',
'right-blockemail' => 'Электрон почтаға хат ебәреүҙе тыйыу',
'right-hideuser' => 'Ҡатнашыусы исемен тыйыу һәм йәшереү',
'right-ipblock-exempt' => 'IP адрестарҙы бикләүҙе, авто-бикләүҙәрҙе, арауыҡтарҙы бикләүҙе урап үтеү',
'right-proxyunbannable' => 'Прокси серверҙарҙы авто-бикләүҙе урап үтеү',
'right-unblockself' => 'Үҙ бигеңде асырға',
'right-protect' => 'Биттәрҙең һаҡланыу кимәлен үҙгәртеү һәм баҫҡыслап һаҡланған биттәрҙе төҙәтеү',
'right-editprotected' => '"{{int:protect-level-sysop}}" булараҡ һаҡланған биттәрҙе төҙәтеү',
'right-editsemiprotected' => '"{{int:protect-level-autoconfirmed}}" булараҡ һаҡланған биттәрҙе төҙәтеү',
'right-editinterface' => 'Ҡулланыусы интерфейсын үҙгәртеү',
'right-editusercssjs' => 'Башҡа ҡатнашыусыларҙың CSS һәм JS файлдарын мөхәррирләү',
'right-editusercss' => 'Башҡа ҡатнашыусыларҙың CSS файлдарын мөхәррирләү',
'right-edituserjs' => 'Башҡа ҡатнашыусыларҙың JS файлдарын мөхәррирләү',
'right-editmyusercss' => 'Файҙаланыусының CSS файлдарын мөхәррирләү',
'right-editmyuserjs' => 'Үҙеңдең файҙаланыуҙағы JavaScript-файлдарын мөхәррирләргә',
'right-viewmywatchlist' => 'Үҙеңдең күҙәтеү исемлеген ҡарарға',
'right-editmywatchlist' => 'Үҙеңдең күҙәтеү исемлеген мөхәррирләргә.
Ҡайһы бер ғәмәлдәрҙең, быға хоҡуғы булмаһа ла, биттәр өҫтәүенә иғтибар итегеҙ.',
'right-viewmyprivateinfo' => 'Үҙеңдең шәхси мәғлүмәттәреңде (мәҫәлән, электрон почта адресын, ысын исемеңде) байҡау',
'right-editmyprivateinfo' => 'Үҙ шәхси мәғлүмәттәреңде (мәҫәлән, электрон почта адресын, ысын исемеңде) төҙәтеү',
'right-editmyoptions' => 'Үҙ өҫтөнлөктәреңде мөхәррирләргә',
'right-rollback' => 'Ниндәйҙер битте мөхәррирләгән һуңғы ҡатнашыусының үҙгәртеүҙәрен тиҙ кире алыу',
'right-markbotedits' => 'Кире алынған үҙгәртеүҙәрҙе бот үҙгәртеүе тип билдәләү',
'right-noratelimit' => 'Тиҙлек сикләнмәгән',
'right-import' => 'Башҡа вики-проекттарҙан биттәрҙе күсереү',
'right-importupload' => 'Файл күсереү аша биттәрҙе тейәү',
'right-patrol' => 'Үҙгәртеүҙәрҙе тикшерелгән тип билдәләү',
'right-autopatrol' => 'Үҙгәртеүҙәр үҙҙәренән-үҙҙәре тикшерелгән тип билдәләнә',
'right-patrolmarks' => 'Һуңғы үҙгәртеүҙәрҙә тикшереү билдәләрен ҡарау',
'right-unwatchedpages' => 'Күҙәтелмәгән биттәр исемлеген ҡарау',
'right-mergehistory' => 'Биттәр тарихын берләштереү',
'right-userrights' => 'Барлыҡ ҡатнашыусыларҙың хоҡуҡтарын үҙгәртеү',
'right-userrights-interwiki' => 'Ҡатнашыусыларҙың башҡа Вики-сайттарҙағы хоҡуҡтарын үҙгәртеү',
'right-siteadmin' => 'Мәғлүмәттәр базаһын асыу һәм ябыу',
'right-override-export-depth' => '5-се тәрәнлеккә тиклем бәйле биттәре менән бергә биттәрҙе сығарыу',
'right-sendemail' => 'Башҡа ҡатнашыусыларға электрон почта аша хат ебәреү',
'right-passwordreset' => 'Серһүҙҙе яңыртыу осраҡтарын ҡарау',

# Special:Log/newusers
'newuserlogpage' => 'Яңы ҡулланыусы яҙмалары',
'newuserlogpagetext' => 'Яңы теркәлгән ҡатнашыусылар яҙмалары журналы.',

# User rights log
'rightslog' => 'Ҡулланыусының хоҡуҡтары көндәлеге',
'rightslogtext' => 'Был — ҡулланыусы хоҡуҡтары үҙгәрештәре яҙмалары журналы',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'Был битте уҡыу',
'action-edit' => 'Был битте үҙгәртеү',
'action-createpage' => 'Яңы бит яһау',
'action-createtalk' => 'Фекер алышыу битен яһау',
'action-createaccount' => 'Был ҡулланыусы иҫәп яҙыуын яһау',
'action-minoredit' => 'Был төҙәтеүҙе әҙ үҙгәреш тип билдәләү',
'action-move' => 'Был биттең исемен үҙгәртеү',
'action-move-subpages' => 'Был биттең һәм эске биттәренең исемен үҙгәртеү',
'action-move-rootuserpages' => 'Ҡулланыусыларҙың төп биттәренең исемен үҙгәртеү',
'action-movefile' => 'Был файлдың исемен үҙгәртеү',
'action-upload' => 'Был файлды тейәү',
'action-reupload' => 'Булған файлдың өҫтөнә яҙыу',
'action-reupload-shared' => 'Дөйөм һаҡлағыстағы был файлды алыштырыу',
'action-upload_by_url' => 'Был файлды URL адрестан күсереү',
'action-writeapi' => 'Яҙҙырыу өсөн API ҡулланыу',
'action-delete' => 'Был битте юйыу',
'action-deleterevision' => 'Биттең был өлгөһөн юйыу',
'action-deletedhistory' => 'Был биттең юйҙырыуҙар тарихын ҡарау',
'action-browsearchive' => 'Юйылған биттәрҙе эҙләү',
'action-undelete' => 'Юйылған был битте ҡабат тергеҙеү',
'action-suppressrevision' => 'Был йәшерелгән өлгөнө ҡарау һәм тергеҙеү',
'action-suppressionlog' => 'Был шәхси журналды ҡарау',
'action-block' => 'Был ҡатнашыусыға мөхәррирләүҙе тыйыу',
'action-protect' => 'Был биттең һаҡланыу дәрәжәһен үҙгәртеү',
'action-rollback' => 'битте мөхәррирләгән һуңғы ҡатнашыусының үҙгәртеүҙәрен тиҙ кире алыу',
'action-import' => 'башҡа вики-проекттан биттәрҙе күсереү',
'action-importupload' => 'тейәлгән файлдан биттәрҙе күсереү',
'action-patrol' => 'Башҡаларҙың үҙгәртеүҙәрен тикшерелгән тип билдәләү',
'action-autopatrol' => 'Үҙ үҙгәртеүҙәрен тикшерелгән тип билдәләү',
'action-unwatchedpages' => 'Күҙәтелмәгән биттәр исемлеген ҡарау',
'action-mergehistory' => 'Был биттең тарихын берләштереү',
'action-userrights' => 'Ҡатнашыусының барлыҡ хоҡуҡтарын үҙгәртеү',
'action-userrights-interwiki' => 'Ҡатнашыусыларҙың башҡа Викиларҙағы хоҡуҡтарын үҙгәртеү',
'action-siteadmin' => 'Мәғлүмәттәр базаһын асыу һәм ябыу',
'action-sendemail' => 'электрон хат ебәреү',
'action-editmywatchlist' => 'һеҙҙең күҙәтеүҙәр исемелеген мөхәррирләү',
'action-viewmywatchlist' => 'һеҙҙең күҙәтеүҙәр исемлеген байҡау',
'action-viewmyprivateinfo' => 'һеҙҙең шәхси мәғлүмәтте байҡау',
'action-editmyprivateinfo' => 'һеҙҙең шәхси мәғлүмәтте мөхәррирләү',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|үҙгәртеү|үҙгәртеү}}',
'enhancedrc-since-last-visit' => '$1 {{PLURAL:$1|һеҙҙең һуңғы визит}}',
'enhancedrc-history' => 'тарих',
'recentchanges' => 'Һуңғы үҙгәртеүҙәр',
'recentchanges-legend' => 'Һуңғы үҙгәртеүҙәр көйләүҙәре',
'recentchanges-summary' => 'Төрлө биттәрҙә эшләнгән һуңғы үҙгәртеүҙәр исемлеге',
'recentchanges-noresult' => 'Был осорҙа тейешле шарттарға тап килгән үҙгәрештәр юҡ.',
'recentchanges-feed-description' => 'Был таҫмалағы һуңғы үҙгәртеүҙәрҙе күҙәтеп барырға',
'recentchanges-label-newpage' => 'Был үҙгәртеү яңы бит яһаны',
'recentchanges-label-minor' => 'Был әҙ үҙгәреш',
'recentchanges-label-bot' => 'Был төҙәтеү бот тарафынан башҡарылды',
'recentchanges-label-unpatrolled' => 'Был төҙәтеү ҡаралмаған әле',
'rcnote' => 'Аҫта $4 $5 тиклем эшләнгән, һуңғы {{PLURAL:$2|1|$2}} көн эсендәге һуңғы {{PLURAL:$1|1|$1}} үҙгәртеү күрһәтелгән.',
'rcnotefrom' => "Түбәндә '''$2''' башлап ('''$1''' тиклем) үҙгәртеүҙәр күрһәтелгән.",
'rclistfrom' => '$1 башлап яңы үҙгәртеүҙәрҙе күрһәт.',
'rcshowhideminor' => 'бәләкәй төҙәтеүҙәрҙе $1',
'rcshowhidebots' => 'боттарҙы $1',
'rcshowhideliu' => 'танылған ҡулланыусыларҙы $1',
'rcshowhideanons' => 'танылмаған ҡулланыусыларҙы $1',
'rcshowhidepatr' => '$1 — ҡаралған төҙәтеүҙәр',
'rcshowhidemine' => 'минең үҙгәртеүҙәремде $1',
'rclinks' => 'Һуңғы $2 көн эсендәге һуңғы $1 үҙгәртеүҙе күрһәтергә<br />$3',
'diff' => 'айыр.',
'hist' => 'тарих',
'hide' => 'йәшер',
'show' => 'күрһәт',
'minoreditletter' => 'ә',
'newpageletter' => 'Я',
'boteditletter' => 'б',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ҡатнашыусы}} күҙәтә]',
'rc_categories' => 'Ошо категорияларҙан ғына («|» менән айырырға)',
'rc_categories_any' => 'Һәр',
'rc-change-size-new' => 'Үҙгәртештән һуң $1 {{PLURAL:$1|байт|байт}}',
'newsectionsummary' => '/* $1 */ яңы бүлек',
'rc-enhanced-expand' => 'Ваҡ-төйәгенә тиклем күрһәтергә',
'rc-enhanced-hide' => 'Ваҡлыҡтарҙы йәшерергә',
'rc-old-title' => 'төп нөхсә исеме "$1"',

# Recent changes linked
'recentchangeslinked' => 'Бәйле үҙгәртеүҙәр',
'recentchangeslinked-feed' => 'Бәйле үҙгәртеүҙәр',
'recentchangeslinked-toolbox' => 'Бәйле үҙгәртеүҙәр',
'recentchangeslinked-title' => '"$1" битенә бәйле үҙгәртеүҙәр',
'recentchangeslinked-summary' => "Был күрһәтелгән бит һылтанма яһаған (йәки күрһәтелгән категорияға кергән) һуңғы үҙгәртеүҙәр исемлеге.
[[Special:Watchlist|Күҙәтеү исемлегегеҙгә]] керә торған биттәр '''ҡалын''' итеп күрһәтелгән.",
'recentchangeslinked-page' => 'Бит исеме:',
'recentchangeslinked-to' => 'Киреһенсә, был биткә һылтанма яһаған биттәрҙәге үҙгәртеүҙәрҙе күрһәтергә',

# Upload
'upload' => 'Файл тейәү',
'uploadbtn' => 'Файлды тейәргә',
'reuploaddesc' => 'Тейәү формаһына кире ҡайтырға',
'upload-tryagain' => 'Файлдың үҙгәртелгән  тасуирламаһын ебәрергә',
'uploadnologin' => 'Танылмағанһығыҙ',
'uploadnologintext' => 'Серверға файлдар тейәү өсөн һеҙ тейешһегеҙ $1',
'upload_directory_missing' => 'Тейәү өсөн директория ($1) юҡ йәки веб-сервер уны булдыра алмай.',
'upload_directory_read_only' => 'Тейәү өсөн директорияға ($1) веб-сервер яҙҙыра алмай.',
'uploaderror' => 'Тейәү хатаһы',
'upload-recreate-warning' => "'''Иғтибар. Бындай исемле файл юйылған йәки күсерелгән. '''
Был биттең юйыуҙары һәм күсереүҙәре яҙмалары журналы түбәндә килтерелгән:",
'uploadtext' => "Файл тейәү өсөн түбәндәге форманы ҡулланығыҙ.
Элек тейәлгән файлдарҙы байҡар өсөн [[Special:FileList|тейәлгән файлдар исемлеген]] ҡарағыҙ. Файл тейәүҙәр шулай уҡ [[Special:Log/upload|тейәү яҙмаларына]], юйыуҙар иһә [[Special:Log/delete|юйыу яҙмаларына]] яҙылып баралар.

Файлды мәҡәләгә өҫтәү өсөн киләһе юлдарҙы ҡуллана алаһығыҙ:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' — файлдың тулы өлгөһөн ҡуйыр өсөн;
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|тасуирлама]]</nowiki></code>''' — файлдың киңлек буйынса 200 нөктәгә тиклем бәләкәсәйтелгән, һулға тигеҙләнгән һәм аҫтында тасуирламаһы булған өлгөһөн ҡуйыр өсөн;
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' — эстәлеген биттә күрһәтмәйенсә файлға һылтанма ҡуйыу өсөн.",
'upload-permitted' => 'Рөхсәт ителгән файл типтары: $1.',
'upload-preferred' => 'Уңайлы файл типтары: $1.',
'upload-prohibited' => 'Тыйылған файл типтары: $1.',
'uploadlog' => 'тейәүҙәр яҙмаһы',
'uploadlogpage' => 'Тейәү яҙмалары',
'uploadlogpagetext' => 'Түбәндә, һуңғы файл тейәүҙәр исемлеге күрһәтелгән.
Шулай уҡ [[Special:NewFiles|яңы файлдар галереяһын]] ҡарағыҙ; һуңғы тейәүҙәр ентекле рәүештә күрһәтелгән.',
'filename' => 'Файл исеме',
'filedesc' => 'Ҡыҫҡа аңлатма',
'fileuploadsummary' => 'Ҡыҫҡа аңлатма:',
'filereuploadsummary' => 'Файлдағы үҙгәртеүҙәр:',
'filestatus' => 'Авторлыҡ хоҡуғы торошо:',
'filesource' => 'Сығанаҡ:',
'uploadedfiles' => 'Тейәлгән файлдар',
'ignorewarning' => 'Киҫәтеүҙәрҙе иғтибарға алмаҫҡа һәм барыбер файлды һаҡларға',
'ignorewarnings' => 'Бөтә иҫкәрмәләргә иғтибар итмәҫкә',
'minlength1' => 'Файлдың исеме кәмендә бер хәрефтән торорға тейеш.',
'illegalfilename' => '«$1» файлы исемендә рөхсәт ителмәгән символдар бар.
Зинһар файл исемен үҙгәртегеҙ һәм яңынан тейәп ҡарағыҙ.',
'filename-toolong' => 'Файл исемдәре 240 байтты үтергә тейеш түгел.',
'badfilename' => 'Файлдың исеме $1 исеменә үҙгәртелде.',
'filetype-mime-mismatch' => 'Файлдың «.$1» киңәйеүе файлдың ($2) MIME-төрөнә  тап килмәй.',
'filetype-badmime' => 'MIME-төрө «$1» булған файлдарҙы тейәп булмай.',
'filetype-bad-ie-mime' => 'Был файлды тейәп булмай, сөнки Internet Explorer уны "$1", йәғни рөхсәт ителмәгән һәм хәүефле файл төрө тип билдәләйәсәк.',
'filetype-unwanted-type' => "'''\".\$1\"''' — теләнмәгән файл тибы.
{{PLURAL:\$3|Уңайлы файл тибы|Уңайлы файл типтары:}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' — {{PLURAL:$4|тыйылған файл төрө|тыйылған файл төрҙәре}}.
{{PLURAL:$3|Файлдың рөхсәт ителгән төрө|Файлдың рөхсәт ителгән төрҙәре:}} $2.',
'filetype-missing' => 'Файлдың киңәйтелмәһе юҡ (мәҫәлән, ".jpg").',
'empty-file' => 'Һеҙ ебәргән файл буш.',
'file-too-large' => 'Һеҙ ебәргән файл бигерәк ҙур.',
'filename-tooshort' => 'Файлдың исеме бигерәк ҡыҫҡа.',
'filetype-banned' => 'Был файл төрө рөхсәт ителмәй.',
'verification-error' => 'Был файл тикшереү үтмәгән.',
'hookaborted' => 'Һеҙ керетергә теләгән үҙгәртеүҙәр киңәйтелмә эшкәртеү ҡоралы тарафынан кире алынды.',
'illegal-filename' => 'Ярамаған файл исеме.',
'overwrite' => 'Булған файлды алыштырыу мөмкин түгел.',
'unknown-error' => 'Билдәһеҙ хата.',
'tmp-create-error' => 'Ваҡытлы файл булдырыу мөмкин түгел.',
'tmp-write-error' => 'Ваҡытлы файлға яҙҙырыу хатаһы.',
'large-file' => 'Дәүмәле $1 байттан артмаған файлдар ҡулланырға кәңәш ителә (был файлдың дәүмәле $2 байт тәшкил итә).',
'largefileserver' => 'Был файлдың дәүмәле рөхсәт ителгәндән ҙурыраҡ.',
'emptyfile' => 'Һеҙ тейәгән файл буш булырға тейеш.
Был файлдың исемен кереткән ваҡытта ебәрелгән хата арҡаһында булыуы мөмкин.
Зинһар, һеҙ ысынлап та был файлды теләргә теләйһегеҙме икәнен тикшерегеҙ.',
'windows-nonascii-filename' => 'Был вики махсус символ булған файл исемдәрен терәкләмәй.',
'fileexists' => 'Бындай исемле файл бар инде, зинһар, уны алыштырырға теләүегеҙҙә шикләнһәгеҙ,  <strong>[[:$1]]</strong>тикшерегеҙ.
[[$1|thumb]]',
'filepageexists' => 'Был файлдың тасуирламаһы бите булдырылған инде: <strong>[[:$1]]</strong>, әммә бындай исемле файл юҡ.
Керетелгән тасуирлама файлдың тасуирламаһы битендә сыҡмаясаҡ.
Яңы тасуирлама өҫтәр өсөн, һеҙгә уны ҡул менән үҙгәртергә тура киләсәк.
[[$1|thumb]]',
'fileexists-extension' => 'Оҡшаш исемле файл бар: [[$2|thumb]]
* Тейәлгән файлдың исеме: <strong>[[:$1]]</strong>
* Булған файлдың исеме: <strong>[[:$2]]</strong>
Зинһар, башҡа исем һайлағыҙ.',
'fileexists-thumbnail-yes' => "Файл бәләкәйтелгән өлгө ''(шартлы рәсем)'' булырға тейеш.
[[$1|thumb]]
Зинһар, <strong>[[:$1]]</strong> файлын тикшерегеҙ.
Әгәр ул ошо уҡ файлдың төп өлгөһө булһа, уның бәләкәйтелгән өлгөһөн айырым тейәүҙең кәрәге юҡ.",
'file-thumbnail-no' => "Файлдың исеме <strong>$1</strong> менән башлана.
Бәлки, ул рәсемдең бәләкәйтелгән өлгөһөлөр ''(шартлы рәсем)''.
Әгәр һеҙҙә был рәсемдең ҙур өлгөһө булһа, зинһар, уны керетегеҙ йәки файлдың исемен үҙгәртегеҙ.",
'fileexists-forbidden' => 'Бындай исемле файл бар инде һәм ул үҙгәртелә алмай.
Әгәр һеҙ шулай ҙа был файлды тейәргә теләһәгеҙ, зинһар, кире ҡайтығыҙ һәм уны икенсе исем аҫтында тейәгеҙ.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Бындай исемле файл дөйөм файл һаҡлағыста бар инде.
Әгәр һеҙ шулай ҙа был файлды тейәргә теләһәгеҙ, зинһар, кире ҡайтығыҙ һәм яңы исем һайлағыҙ.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Был файл түбәндәге {{PLURAL:$1|файл|файлдар}} менән тап килә:',
'file-deleted-duplicate' => 'Оҡшаш файл ([[:$1]]) юйылғайны инде. Уны ҡайтанан тейәр алдынан, зинһар, файлды юйыу тарихын ҡарағыҙ.',
'uploadwarning' => 'Киҫәтеү',
'uploadwarning-text' => 'Зинһар, түбәндәге файл тасуирламаһын үҙгәртегеҙ һәм яңынан ҡабатлап ҡарағыҙ.',
'savefile' => 'Һөҙгөстө яҙҙырып ҡуйырға',
'uploadedimage' => '«[[$1]]» тейәлгән',
'overwroteimage' => '"[[$1]]" файлының яңы өлгөһө тейәлде',
'uploaddisabled' => 'Тейәү рөхсәт ителмәй',
'copyuploaddisabled' => 'URL адрес аша тейәү рөхсәт ителмәй.',
'uploadfromurl-queued' => 'Һеҙҙең тейәүегеҙ сиратҡа ҡуйылды.',
'uploaddisabledtext' => 'Файлдар тейәү рөхсәт ителмәй.',
'php-uploaddisabledtext' => 'Файлдар тейәү PHP көйләүҙәрендә рөхсәт ителмәй. Зинһар, file_uploads көйләүен тикшерегеҙ.',
'uploadscripted' => 'Файлда булған HTML-кодты йәки скриптты браузер дөрөҫ эшкәртмәүе мөмкин.',
'uploadvirus' => 'Файлда вирус бар!
Тулыраҡ мәғлүмәт: $1',
'uploadjava' => 'Был, эсендә Java .class файлы булған ZIP-архив.
Именлек өсөн Java-файлдарын тейәү тыйылған.',
'upload-source' => 'Сығанаҡ файл',
'sourcefilename' => 'Файлдың сығанаҡ исеме:',
'sourceurl' => 'Сығанаҡ URL:',
'destfilename' => 'Файлдың яңы исеме:',
'upload-maxfilesize' => 'Файлдың максимум күләме: $1',
'upload-description' => 'Файл тасуирламаһы',
'upload-options' => 'Тейәү көйләүҙәре',
'watchthisupload' => 'Файлды күҙәтергә',
'filewasdeleted' => 'Бындай исемле файл бығаса булған һәм юйылған. Зинһар, ҡабаттан тейәр алдынан $1 битен ҡарағыҙ.',
'filename-bad-prefix' => "Тейәлә торған файлдың исеме ''«$1»''' менән башлана һәм ул цифрлы камераларҙа файлдарға уҙенән-үҙе бирелә торған исемгә оҡшаған.
Зинһар, файлды яҡшыраҡ тасуирлаған исем һайлағыҙ.",
'upload-success-subj' => 'Файл тейәү уңышлы тамамланды',
'upload-success-msg' => 'Һеҙҙең [$2] адресынан тейәүегеҙ уңышлы тамаланды. Файлды ошонда ҡарай алаһығыҙ: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Тейәү уңышлы түгел',
'upload-failure-msg' => '[$2] адресынан тейәгән ваҡытта ҡыйынлыҡтар тыуҙы:
$1',
'upload-warning-subj' => 'Тейәү ваҡытында киҫәтеү',
'upload-warning-msg' => '[$2] адресынан тейәгән ваҡытта ҡыйынлыҡтар тыуҙы. Хатаны төҙәтеү өсөн [[Special:Upload/stash/$1|файл тейәү формаһына]] кире ҡайта алаһығыҙ.',

'upload-proto-error' => 'Протокол дөрөҫ түгел',
'upload-proto-error-text' => 'Алыҫтан тейәү өсөн <code>http://</code> йәки <code>ftp://</code> менән башланған адрес кәрәк.',
'upload-file-error' => 'Эске хата',
'upload-file-error-text' => 'Серверҙа ваҡытлы файл булдырған ваҡытта эске хата сыҡты.
Зинһар, [[Special:ListUsers/sysop|хәкимгә]] мөрәжәғәт итегеҙ.',
'upload-misc-error' => 'Билдәһеҙ тейәү хатаһы',
'upload-misc-error-text' => 'Файл тейәгәндә билдәһеҙ хата килеп сыҡты.
Зинһар, URL адрестың дөрөҫлөгөн тикшерегеҙ һәм яңынан ҡабатлап ҡарағыҙ.
Әгәр хата шул килеш ҡалһа, [[Special:ListUsers/sysop|хәкимгә]] мөрәжәғәт итегеҙ.',
'upload-too-many-redirects' => 'URL бигерәк күп йүнәлтмәләр яһай.',
'upload-unknown-size' => 'Билдәһеҙ дәүмәл',
'upload-http-error' => 'HTTP хата килеп сыҡты: $1',
'upload-copy-upload-invalid-domain' => 'Был доменға ҡараған сайттарҙан файл күсереү асыҡ түгел',

# File backend
'backend-fail-stream' => '$1 файлын трансляциялап булмай.',
'backend-fail-backup' => '$1 файлының резерв күсермәһен эшләп булмай.',
'backend-fail-notexists' => '$1 файлы юҡ.',
'backend-fail-hashes' => 'Сағыштырыу өҫөн кәрәкле файл хэштарын алып булманы.',
'backend-fail-notsame' => 'Бер үҡ булмаған файл  $1 бар инде.',
'backend-fail-invalidpath' => '$1 яраҡлы һаҡлау юлы түгел.',
'backend-fail-delete' => '«$1» файлын юйып булмай.',
'backend-fail-describe' => '"$1" файлының метамәғлүмәттәрен үҙгәртеп булманы.',
'backend-fail-alreadyexists' => '$1 файлы бар инде.',
'backend-fail-store' => '$1 файлын $2 адресында һаҡлап булманы.',
'backend-fail-copy' => 'Файлдың күсермәһен $1 адресынан $2 адресына яһап булманы.',
'backend-fail-move' => 'Файлды $1 адресынан $2 адресына күсереп булманы.',
'backend-fail-opentemp' => 'Ваҡытлы файлды асып булмай.',
'backend-fail-writetemp' => 'Ваҡытлы файлға яҙып булмай.',
'backend-fail-closetemp' => 'Ваҡытлы файлды ябып булмай.',
'backend-fail-read' => '«$1» файлын уҡып булмай.',
'backend-fail-create' => '«$1» файлын яҙып булмай.',
'backend-fail-maxsize' => '$1 файлын яҙып булманы, сөнки уның күләме {{PLURAL:$2|$2 байттан|$2 байттан}} күп.',
'backend-fail-readonly' => '$1 һаҡлағысы әлегә уҡыу өсөн генә асыҡ. Сәбәбе: $2',
'backend-fail-synced' => '$1 файлы эске һаҡлағыста ярашһыҙ хәлдә тора.',
'backend-fail-connect' => '"$1" һаҡлағысы менән бәйләнеш яһап булманы.',
'backend-fail-internal' => '$1 һаҡлағысында билдәһеҙ хата килеп сыҡты',
'backend-fail-contenttype' => 'Файлды $1 адресына һаҡлар өсөн уның эстәлеге төрөн билдәләп булманы.',
'backend-fail-batchsize' => 'Һаҡлағыс $1 {{PLURAL:$1|файл операцияһынан|файл операцияһынан}} бер блок алды, сикләү һаны: $2 {{PLURAL:$1|операция|операция}}.',
'backend-fail-usable' => 'Хоҡуҡтар етмәгәнлектән йәки кәрәкле папкалар булмағанлыҡтан $1 файлын уҡып йәки яҙып булманы.',

# File journal errors
'filejournal-fail-dbconnect' => '"$1" мәғлүмәт базаһы журналына тоташып булманы.',
'filejournal-fail-dbquery' => '«$1» мәғлүмәт базаһын һаҡлағын журналды яңыртып булманы.',

# Lock manager
'lockmanager-notlocked' => '" $1 " асҡысының биген сисеп булмай; ул бикле түгел.',
'lockmanager-fail-closelock' => '"$1" асҡысының бикләү файлын ябып булманы.',
'lockmanager-fail-deletelock' => '"$1" асҡысының бикләү файлын юйып булманы.',
'lockmanager-fail-acquirelock' => '"$1" асҡысын бикләп булманы.',
'lockmanager-fail-openlock' => '"$1" асҡысының бикләү файлын асып булманы.',
'lockmanager-fail-releaselock' => '"$1" асҡысының биген асып булманы.',
'lockmanager-fail-db-bucket' => '$1 сегментында етәрле күләмдә бикләү базаһы менән бәйләнеп булманы.',
'lockmanager-fail-db-release' => '$1 мәғлүмәттәр базаһы биген сисеп булманы.',
'lockmanager-fail-svr-acquire' => '$1 серверындағы биктәрҙе алып булманы.',
'lockmanager-fail-svr-release' => '$1 серверы биктәрен сисеп булманы.',

# ZipDirectoryReader
'zip-file-open-error' => 'Архивты тикшереү өсөн файлды асҡан ваҡытта хата барлыҡҡа килде.',
'zip-wrong-format' => 'Күрһәтелгән файл ZIP файл түгел.',
'zip-bad' => 'ZIP файл боҙолған йәки уны уҡып булмай.
Уны кәрәкле рәүештә тикшереп булмай.',
'zip-unsupported' => 'Был ZIP файл, MediaWiki терәкләмәгән мөмкинлектәрҙе ҡуллана.
Уны кәрәкле рәүештә тикшереп булмай.',

# Special:UploadStash
'uploadstash' => 'Йәшерен тейәү',
'uploadstash-summary' => 'Был бит тейәлгән (йәки тейәү барышында булған), әммә викила әлегә баҫтырып сығарылмаған файлдарҙы ҡарау мөмкинлеген бирә. Был файлдар уны тейәгән ҡатнашыусынан башҡа бер кемгә лә күренмәй.',
'uploadstash-clear' => 'Йәшерен файлдарҙы таҙартырға',
'uploadstash-nofiles' => 'Һеҙҙең йәшерен файлдарығыҙ юҡ.',
'uploadstash-badtoken' => 'Был ғәмәлде башҡарып булманы, һеҙҙең төҙәтеү яҙмағыҙ ғәмәлдән сыҡҡан булыуы ихтимал. Яңынан ҡабатлап ҡарағыҙ.',
'uploadstash-errclear' => 'Файлдарҙы таҙартып булманы.',
'uploadstash-refresh' => 'Файлдар исемлеген яңыртырға',
'invalid-chunk-offset' => 'Ҡабул ителмәгән фрагмент шылыуы',

# img_auth script messages
'img-auth-accessdenied' => 'Керергә рөхсәт ителмәй',
'img-auth-nopathinfo' => 'PATH_INFO юҡ.
Һеҙҙең сервер был мәғлүмәтте ебәреү өсөн көйләнмәгән.
Уның CGI нигеҙендә эшләүе һәм img_auth ҡулланмауы мөмкин.
Тулыраҡ мәғлүмәт: https://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-notindir' => 'Һоралған юл көйләнгән тейәүҙәр директорияһына ҡарамай.',
'img-auth-badtitle' => '"$1" исеменән дөрөҫ исем төҙөп булмай.',
'img-auth-nologinnWL' => 'Һеҙ танылыу үтмәнегеҙ, "$1" аҡ исемлеккә кермәй.',
'img-auth-nofile' => '"$1" файлы юҡ.',
'img-auth-isdir' => 'Һеҙ "$1" директорияһына керергә тырышаһығыҙ.
Файлдарҙы асырға ғына рөхсәт бар.',
'img-auth-streaming' => '"$1" файлын эҙмә-эҙлекле тапшырыу.',
'img-auth-public' => 'img_auth.php файлдарҙы ябыҡ викинан сығарыу өсөн тәғәйенләнгән.
Был вики асыҡ тип көйләнгән.
Хәүефһеҙлек маҡсаттарында img_auth.php һүндерелгән.',
'img-auth-noread' => 'Ҡатнашыусыға "$1" файлын уҡыу рөхсәт ителмәй.',
'img-auth-bad-query-string' => 'URL-адрестағы һоратыу юлы хаталы',

# HTTP errors
'http-invalid-url' => 'URL адрес дөрөҫ түгел: $1',
'http-invalid-scheme' => '"$1" схемалы URL адрестары ҡулланылмай.',
'http-request-error' => 'HTTP-һорау билдәһеҙ хата арҡаһында эшкәртелмәне.',
'http-read-error' => 'HTTP уҡыу хатаһы.',
'http-timed-out' => 'HTTP-һорауҙы көтөү ваҡыты үтте.',
'http-curl-error' => 'URL адрес буйынса мөрәжәғәт итеү хатаһы: $1',
'http-bad-status' => 'HTTP-һорауҙы эшкәрткән ваҡытта ҡыйынлыҡтар тыуҙы: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL адресҡа мөрәжәғәт итеү мөмкин түгел.',
'upload-curl-error6-text' => 'URL адрес буйынса мөрәжәғәт итеү мөмкин түгел.
Зинһар, URL адрестың дөрөҫлөгөн һәм сайттың эшләүен тикшерегеҙ.',
'upload-curl-error28' => 'Көтөү ваҡыты үтте',
'upload-curl-error28-text' => '
Сайт бигерәк оҙаҡ яуап бирмәй.
Зинһар, сайттың эшләүен тикшерегеҙ һәм, бер аҙ көткәндән һуң, яңынан ҡабатлап ҡарағыҙ.
Бәлки, һеҙгә сайт бушыраҡ саҡта ҡабатлап ҡарарға кәрәктер.',

'license' => 'Рөхсәтнамә:',
'license-header' => 'Рөхсәтнәмә',
'nolicense' => 'Бер нимә лә һайланмаған',
'license-nopreview' => '(Ҡарап сығыу мөмкин түгел)',
'upload_source_url' => '(Дөрөҫ, дөйөм ҡулланыу өсөн асыҡ URL-адрес)',
'upload_source_file' => '(һеҙҙең компьютерҙағы файл)',

# Special:ListFiles
'listfiles-summary' => 'Был ярҙамсы бит бөтә тейәлгән файлдарҙы күрһәтә.',
'listfiles_search_for' => 'Файл исеме буйынса эҙләү:',
'imgfile' => 'файл',
'listfiles' => 'Файлдар исемлеге',
'listfiles_thumb' => 'Шартлы рәсем',
'listfiles_date' => 'Көнө',
'listfiles_name' => 'Исем',
'listfiles_user' => 'Ҡатнашыусы',
'listfiles_size' => 'Күләм',
'listfiles_description' => 'Тасуирлау',
'listfiles_count' => 'Версиялар',
'listfiles-show-all' => 'Рәсемдәрҙең иҫке версияларын индерергә',
'listfiles-latestversion' => 'Ағымдағы версия',
'listfiles-latestversion-yes' => 'Эйе',
'listfiles-latestversion-no' => 'Юҡ',

# File description page
'file-anchor-link' => 'Файл',
'filehist' => 'Файл тарихы',
'filehist-help' => 'Файлдың ниндәй хәлдә булғанын ҡарар өсөн Көн/Сәғәткә баҫығыҙ',
'filehist-deleteall' => 'барыһын да юйырға',
'filehist-deleteone' => 'юйырға',
'filehist-revert' => 'кире алырға',
'filehist-current' => 'хәҙ.',
'filehist-datetime' => 'Көн/ваҡыт',
'filehist-thumb' => 'Бәләкәй рәсем',
'filehist-thumbtext' => '$1 өлгөһөнөң шартлы рәсеме',
'filehist-nothumb' => 'Бәләкәй рәсем юҡ',
'filehist-user' => 'Ҡулланыусы',
'filehist-dimensions' => 'Дәүмәл',
'filehist-filesize' => 'Файл күләме',
'filehist-comment' => 'Иҫкәрмә',
'filehist-missing' => 'Файл юҡ',
'imagelinks' => 'Файл ҡулланыу',
'linkstoimage' => 'Был файлға {{PLURAL:$1|бит|$1 бит}} һылтана:',
'linkstoimage-more' => 'Был файлға кәмендә $1 {{PLURAL:$1|бит}} һылтанма яһай.
Түбәндәге исемлектә был файлға $1 {{PLURAL:$1|һылтанма}} ғына килтерелгән.
Шулай уҡ [[Special:WhatLinksHere/$2|тулы исемлекте]] ҡарарға мөмкин.',
'nolinkstoimage' => 'Был файлға һылтанма яһаған бит юҡ.',
'morelinkstoimage' => 'Был файлға [[Special:WhatLinksHere/$1|башҡа һылтанмаларҙы]] ҡарарға.',
'linkstoimage-redirect' => '$1 (файл-йүнәлтеү) $2',
'duplicatesoffile' => 'Түбәндәге {{PLURAL:$1|файл|файлдар}} был файл менән тап килә ([[Special:FileDuplicateSearch/$2|тулыраҡ мәғлүмәт]])',
'sharedupload' => 'Был файл $1 базаһынан һәм башҡа проектарҙа ҡулланылырға мөмкин.',
'sharedupload-desc-there' => 'Был файл $1 базаһынан һәм башҡа проекттарҙа ҡулланыла ала.
Тулыраҡ мәғлүмәтте [$2 файл тасуирламаһы битендә] ҡарарға мөмкин.',
'sharedupload-desc-here' => 'Был файл $1 базаһынан һәм башҡа проекттарҙа ҡулланыла ала.
[$2 файл тасуирламаһы битенән] тулыраҡ мәғлүмәт түбәндә килтерелгән.',
'sharedupload-desc-edit' => 'Файл килгән урын: $1. Был файл башҡа сайттарҙа ҡулланылырға мөмкин.
Тасуиламаһын [$2 кәрәкле биттә] үҙгәртергә була.',
'sharedupload-desc-create' => 'Файл килгән урын: $1. Был файл башҡа сайттарҙа ҡулланылырға мөмкин.
Тасуиламаһын [$2 кәрәкле биттә] үҙгәртергә була.',
'filepage-nofile' => 'Бындай исемле файл юҡ.',
'filepage-nofile-link' => 'Бындай исемле файл юҡ. Һеҙ уны [$1 тейәй алаһығыҙ].',
'uploadnewversion-linktext' => 'Был файлдың яңы версияһын тейәргә',
'shared-repo-from' => '$1 базаһынан',
'shared-repo' => 'дөйөм һаҡлағыс',
'upload-disallowed-here' => 'Һеҙ был файлды ҡабаттан яҙҙыра алмайһығыҙ.',

# File reversion
'filerevert' => '$1 өлгөһөнә ҡайтыу',
'filerevert-legend' => 'Файлдың элекке өлгөһөнә ҡайтыу',
'filerevert-intro' => "Һеҙ '''[[Media:$1|$1]]''' файлын [$2 $3 булдырылған $4 өлгөһөнә] ҡайтараһығыҙ.",
'filerevert-comment' => 'Сәбәп:',
'filerevert-defaultcomment' => '$1 $2 өлгөһөнә ҡайтыу',
'filerevert-submit' => 'Кире алырға',
'filerevert-success' => "'''[[Media:$1|$1]]''' [$2 $3 булдырылған $4 өлгөһөнә] ҡайтарылды.",
'filerevert-badversion' => 'Файлдың күрһәтелгән ваҡыт билдәһе менән алдағы урындағы өлгөһө юҡ.',

# File deletion
'filedelete' => '$1 юйырға',
'filedelete-legend' => 'Файлды юйырға',
'filedelete-intro' => "Һеҙ '''[[Media:$1|$1]]''' файлын бөтә тарихы менән бергә юйырға йыйынаһығыҙ.",
'filedelete-intro-old' => "Һеҙ '''[[Media:$1|$1]]''' файлының [$4 $2 $3] өлгөһөн юяһығыҙ.",
'filedelete-comment' => 'Сәбәп:',
'filedelete-submit' => 'Юйырға',
'filedelete-success' => "'''$1''' юйылды.",
'filedelete-success-old' => "'''[[Media:$1|$1]]''' файлының $2 $3 өлгөһө юйылды.",
'filedelete-nofile' => "'''$1''' файлы юҡ.",
'filedelete-nofile-old' => "'''$1''' файлының күрһәтелгән атрибуттар менән архив өлгөһө юҡ.",
'filedelete-otherreason' => 'Башҡа/өҫтәмә сәбәп:',
'filedelete-reason-otherlist' => 'Башҡа сәбәп',
'filedelete-reason-dropdown' => '*Киң таралған юйыу сәбәптәре: 
** авторлыҡ хоҡуҡтарын боҙоу
** икенсе файл менән тап килгән файл',
'filedelete-edit-reasonlist' => 'Сәбәптәр исемлеген мөхәррирләргә',
'filedelete-maintenance' => 'Файлдарҙы юйыу һәм тергеҙеү техник эштәр ваҡытында ваҡытлыса һундерелгән.',
'filedelete-maintenance-title' => 'Файлды юйып булмай',

# MIME search
'mimesearch' => 'MIME буйынса эҙләү',
'mimesearch-summary' => 'Был бит файлдарҙы MIME-төрҙәре аша һайларға мөмкинлек бирә.
Эҙләү форматы: эстәлек_төрө/икенсе_быуын_төрө, мәҫәлән, <code>image/jpeg</code>',
'mimetype' => 'MIME-төр:',
'download' => 'күсереп яҙырға',

# Unwatched pages
'unwatchedpages' => 'Бер кем дә күҙәтмәгән биттәр',

# List redirects
'listredirects' => 'Йүнәлтеүҙәр исемлеге',

# Unused templates
'unusedtemplates' => 'Ҡулланылмаған ҡалыптар',
'unusedtemplatestext' => 'Был биттә {{ns:template}} исемдәр арауығының бөтә башҡа биттәргә индерелмәгән биттәре исемлеге килтерелгән.
Ҡалыпты юйыр алдынан, уға башҡа һылтанмалар юҡлығын тикшерергә онотмағыҙ.',
'unusedtemplateswlh' => 'Башҡа һылтанмалар',

# Random page
'randompage' => 'Осраҡлы мәҡәлә',
'randompage-nopages' => 'Түбәндәге {{PLURAL:$2|исемдәр арауығында|исемдәр арауыҡтарында}} биттәр юҡ: $1.',

# Random page in category
'randomincategory' => 'Категориялағы осраҡлы бит',
'randomincategory-invalidcategory' => '$1 тигән категория юҡ.',
'randomincategory-nopages' => '[[:Category:$1|$1]] категорияһында биттәр юҡ.',
'randomincategory-selectcategory' => '$1 $2 категорияһынан осраҡлы биткә күсергә.',
'randomincategory-selectcategory-submit' => 'Күсергә',

# Random redirect
'randomredirect' => 'Осраҡлы биткә күсеү',
'randomredirect-nopages' => '"$1" исемдәр арауығында йүнәлтеүҙәр юҡ.',

# Statistics
'statistics' => 'Статистика',
'statistics-header-pages' => 'Бит статистикаһы',
'statistics-header-edits' => 'Мөхәррирләү статистикаһы',
'statistics-header-views' => 'Ҡарау статистикаһы',
'statistics-header-users' => 'Ҡулланыусы статистикаһы',
'statistics-header-hooks' => 'Башҡа статистика',
'statistics-articles' => 'Мәҡәләләр',
'statistics-pages' => 'Биттәр',
'statistics-pages-desc' => 'Вики проекттағы бөтә биттәр, фекер алышыу биттәрен, йүнәлтеүҙәрҙе һ.б. үҙ эсенә ала.',
'statistics-files' => 'Рәсем йәки тауыш эстәлекле күсереп яҙылған файлдар',
'statistics-edits' => '{{SITENAME}} проекты булдырылған ваҡыттан башлап үҙгәртеүҙәр һаны',
'statistics-edits-average' => 'Уртаса бер биткә тура килгән төҙәтеүҙәр һаны',
'statistics-views-total' => 'Ҡарап сығыуҙар',
'statistics-views-total-desc' => 'Булмаған биттәрҙе һәм махсус биттәрҙе ҡарап сығыуҙарҙы үҙ эсенә алмай',
'statistics-views-peredit' => 'Бер үҙгәртеүгә ҡарап сығыуҙар',
'statistics-users' => 'Теркәлгән [[Special:ListUsers|ҡатнашыусылар]]',
'statistics-users-active' => 'Әүҙем ҡатнашыусылар',
'statistics-users-active-desc' => 'Һуңғы {{PLURAL:$1|көндә|$1 көндә}} ниндәйҙер эшмәкәрлек башҡарған ҡатнашыусылар',
'statistics-mostpopular' => 'Иң күп ҡаралған биттәр',

'pageswithprop' => 'Үҙенсәлектәре ҡайтанан билдәләнгән биттәр',
'pageswithprop-legend' => 'Үҙенсәлектәре ҡайтанан билдәләнгән биттәр',
'pageswithprop-text' => 'Бында айырым үҙенсәлектәре ҡулдан яңыртып билдәләнгән биттәр һанала.',
'pageswithprop-prop' => 'Үҙенсәлектең атамаһы:',
'pageswithprop-submit' => 'Табырға',
'pageswithprop-prophidden-long' => 'Текст үҙенсәлегенең оҙон мәғәнәһе йәшерелгән ($1)',
'pageswithprop-prophidden-binary' => 'ике тармаҡлы үҙенсәлектең мәғәнәһе йәшерелгән ($1)',

'doubleredirects' => 'Икеле йүнәлтеүҙәр',
'doubleredirectstext' => 'Был биттә икенсе йүнәлтеү биттәренә йүнәлткән биттәр исемлеге килтерелгән.
Һәр юл беренсе һәм икенсе йүнәлтеүгә һылтанманан, шулай уҡ икенсе һылтанма йүнәлткән һәм беренсе йүнәлтмә һылтанма яһарға тейеш булған биттән  тора.
<del>Һыҙылған</del> яҙыуҙар төҙәтелгән.',
'double-redirect-fixed-move' => '[[$1]] битенең исеме үҙгәртелгән.
Хәҙер ул [[$2]] битенә йүнәлтелгән.',
'double-redirect-fixed-maintenance' => 'Икеле йүнәлтеүҙе ([[$1]] - [[$2]]) төҙәтеү.',
'double-redirect-fixer' => 'Йүнәлтеүҙәрҙе төҙәтеүсе',

'brokenredirects' => 'Өҙөлгән йүнәлтеүҙәр',
'brokenredirectstext' => 'Түбәндәге йүнәлтеүҙәр булмаған биттәргә һылтанма яһай:',
'brokenredirects-edit' => 'төҙәтеү',
'brokenredirects-delete' => 'юйырға',

'withoutinterwiki' => 'Телдәр араһы һылтанмаһы булмаған биттәр',
'withoutinterwiki-summary' => 'Түбәндәге биттәр башҡа телдәрҙәге өлгөләргә һылтанма яһамай.',
'withoutinterwiki-legend' => 'Ҡушылма',
'withoutinterwiki-submit' => 'Күрһәтергә',

'fewestrevisions' => 'Иң әҙ үҙгәртелгән биттәр',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|байт}}',
'ncategories' => '$1 {{PLURAL:$1|Категория|Категория}}',
'ninterwikis' => '$1 {{PLURAL:$1|интервики-һылтанма}}',
'nlinks' => '$1 {{PLURAL:$1|һылтанма}}',
'nmembers' => '$1 {{PLURAL:$1|объект}}',
'nrevisions' => '$1 {{PLURAL:$1|өлгө|өлгө}}',
'nviews' => '$1 {{PLURAL:$1|ҡарап сығыу}}',
'nimagelinks' => '$1 {{PLURAL:$1|биттә}} ҡулланыла',
'ntransclusions' => '$1 {{PLURAL:$1|биттә}} ҡулланыла',
'specialpage-empty' => 'Был һорау өсөн һөҙөмтәләр юҡ.',
'lonelypages' => 'Етем биттәр',
'lonelypagestext' => 'Түбәндәге биттәргә {{SITENAME}} проектының башҡа биттәренән һылтанмалар юҡ һәм улар башҡа биттәргә индерелмәгән.',
'uncategorizedpages' => 'Категорияланмаған биттәр',
'uncategorizedcategories' => 'Категорияланмаған категориялар',
'uncategorizedimages' => 'Категорияланмаған файлдар',
'uncategorizedtemplates' => 'Категорияланмаған ҡалыптар',
'unusedcategories' => 'Ҡулланылмаған категориялар',
'unusedimages' => 'Ҡулланылмаған файлдар',
'popularpages' => 'Популяр биттәр',
'wantedcategories' => 'Кәрәкле категориялар',
'wantedpages' => 'Кәрәкле биттәр',
'wantedpages-badtitle' => 'Һорау һөҙөмтәләрендә дөрөҫ булмаған исем: $1',
'wantedfiles' => 'Кәрәкле файлдар',
'wantedfiletext-cat' => 'Киләһе файлдарҙы улар булмаған хәлдә ҡулланырға тырышыла. Тыш һаҡлағыстарҙа булған файлдар был исемлеккә яңылыш эләгеүе мөмкин. Бындай хаталы белдереүҙәр <del>һыҙыҡ</del> менән күрһәтеләсәк. Шулай уҡ, булмаған файлдарҙы алған биттәр киләһе исемлектә күрһәтелгән: [[:$1]]',
'wantedfiletext-nocat' => 'Киләһе файлдарҙы улар булмаған хәлдә ҡулланырға тырышыла. Тыш һаҡлағыстарҙа булған файлдар был исемлеккә яңылыш эләгеүе мөмкин. Бындай хаталы белдереүҙәр <del>һыҙыҡ</del> менән күрһәтеләсәк.',
'wantedtemplates' => 'Кәрәкле ҡалыптар',
'mostlinked' => 'Иң күп һылтанма яһалған биттәр',
'mostlinkedcategories' => 'Иң күп һылтанма яһалған категориялар',
'mostlinkedtemplates' => 'Иң күп һылтанма яһалған ҡалыптар',
'mostcategories' => 'Күп категорияларға кертелгән биттәр',
'mostimages' => 'Иң күп һылтанма яһалған рәсемдәр',
'mostinterwikis' => 'Иң күп интервики-һылтанмалы биттәр',
'mostrevisions' => 'Иң күп үҙгәртеү яһалған биттәр',
'prefixindex' => 'Исемдәре башында ҡушымта торған биттәр',
'prefixindex-namespace' => 'Префикслы бар биттәр ( $1 исемдәр арауығы)',
'prefixindex-strip' => 'Һөҙөмтәләр исемлегендә префиксты йәшерергә',
'shortpages' => 'Ҡыҫҡа биттәр',
'longpages' => 'Оҙон биттәр',
'deadendpages' => 'Көрсөк биттәр',
'deadendpagestext' => 'Түбәндәге биттәр {{SITENAME}} проектының башҡа биттәренә һылтанма яһамай.',
'protectedpages' => 'Һаҡланған биттәр',
'protectedpages-indef' => 'Сикләнмәгән һаҡлауҙар ғына',
'protectedpages-cascade' => 'Эҙмә-эҙлекле һаҡлауҙар ғына',
'protectedpagestext' => 'Түбәндәге биттәр исемен үҙгәртеүҙән йәки мөхәррирләүҙән һаҡланған.',
'protectedpagesempty' => 'Әлеге ваҡытта күрһәтелгән шарттар менән һаҡланған биттәр юҡ.',
'protectedtitles' => 'Тыйылған исемдәр',
'protectedtitlestext' => 'Түбәндәге исемдәрҙе ҡулланыу рөхсәт ителмәй.',
'protectedtitlesempty' => 'Әлеге ваҡытта күрһәтелгән шарттар менән һаҡланған исемдәр юҡ.',
'listusers' => 'Ҡатнашыусылар исемлеге',
'listusers-editsonly' => 'Кәмендә бер үҙгәртеү индергән ҡатнашыусыларҙы ғына күрһәтергә',
'listusers-creationsort' => 'Булдырыу көнө буйынса тәртипкә килтерергә',
'listusers-desc' => 'Кәмеү буйынса айырырға',
'usereditcount' => '$1 {{PLURAL:$1|үҙгәртеү}}',
'usercreated' => '$3 ҡулланыусыһының теркәлеү ваҡыты: $1 $2',
'newpages' => 'Яңы биттәр',
'newpages-username' => 'Ҡатнашыусы:',
'ancientpages' => 'Иң иҫке мәҡәләләр',
'move' => 'Яңы исем биреү',
'movethispage' => 'Биттең исемен үҙгәртергә',
'unusedimagestext' => 'Түбәндәге файлдар бер биттә лә ҡулланылмай.
Зинһар, икенсе веб-сайттар был файлға уның URL адресы аша һылтана ала һәм, шулай итеп, файл был исемлеккә кергән хәлдә лә ҡулланыла ала, икәнен иҫәпкә алығыҙ.',
'unusedcategoriestext' => 'Түбәндәге категорияларҙы башҡа биттәр йәки категориялар ҡулланмай.',
'notargettitle' => 'Кәрәкле бит исеме күрһәтелмәгән',
'notargettext' => 'Һеҙ был ғәмәл өсөн кәрәкле битте йәки ҡатнашыусыны күрһәтмәгәнһегеҙ.',
'nopagetitle' => 'Бындай бит юҡ',
'nopagetext' => 'Һеҙ күрһәткән бит юҡ.',
'pager-newer-n' => '{{PLURAL:$1|1 яңыраҡ|$1 яңыраҡ}}',
'pager-older-n' => '{{PLURAL:$1|1 иҫкерәк|$1 иҫкерәк}}',
'suppress' => 'Йәшереү',
'querypage-disabled' => 'Был махсус бит һөҙөмтәлелекте арттырыу өсөн ябылған.',

# Book sources
'booksources' => 'Китап сығанаҡтары',
'booksources-search-legend' => 'Китап сығанаҡтарын эҙлә',
'booksources-go' => 'Эҙлә',
'booksources-text' => 'Түбәндәге исемлектә — китаптар һатыу менән шөғөлләнеүсе сайттарға һәм китапханаларҙың эҙләү системаларына һылтанмалар, һәм уларҙа һеҙ эҙләгән китаптар тураһында өҫтәмә мәғлүмәт булыуы мөмкин.',
'booksources-invalid-isbn' => 'Күрһәтелгән ISBN номерҙа хата булырға тейеш. Зинһар, номерҙы сығанаҡтан дөрөҫ күсереүегеҙҙе тикшерегеҙ.',

# Special:Log
'specialloguserlabel' => 'Башҡарыусы:',
'speciallogtitlelabel' => 'Маҡсат (исем йәки ҡулланыусы):',
'log' => 'Журналдар',
'all-logs-page' => 'Барлыҡ асыҡ журналдар',
'alllogstext' => '{{SITENAME}} проектының дөйөм яҙмалар журналы исемлеге. Һеҙ һөҙөмтәләрҙе журнал төрө буйынса, ҡатаншыусы исеме буйынса (ҙур/бәләкәй хәрефкә һиҙгер) йәки ҡағылған бит исеме буйынса (шулай уҡ ҙур/бәләкәй хәрефкә һиҙгер) һайлап ала алаһығыҙ.',
'logempty' => 'Журнал яҙмаларында һайланған юлдар юҡ.',
'log-title-wildcard' => 'Керетелгән хәрефтәр менән башланған исемдәрҙе табырға',
'showhideselectedlogentries' => 'Журналдың һайланған яҙмаларын күрһәтергә/йәшерергә.',

# Special:AllPages
'allpages' => 'Бөтә биттәр',
'alphaindexline' => '$1 алып $2 тиклем',
'nextpage' => 'Киләһе бит ($1)',
'prevpage' => 'Алдағы бит ($1)',
'allpagesfrom' => 'Ошондай хәрефтәрҙән башланған биттәрҙе күрһәтергә:',
'allpagesto' => 'Ошоға бөткән биттәрҙе күрһәтергә:',
'allarticles' => 'Барлыҡ мәҡәләләр',
'allinnamespace' => 'Бөтә биттәр (Исемдәре «$1» арауығында)',
'allnotinnamespace' => 'Бөтә биттәр («$1» исемдәр арауығынан башҡа)',
'allpagesprev' => 'Алдағы',
'allpagesnext' => 'Киләһе',
'allpagessubmit' => 'Үтәргә',
'allpagesprefix' => 'Ошо хәрефтәр менән башланған биттәрҙе күрһәтергә:',
'allpagesbadtitle' => 'Күрһәтелгән бит исеме дөрөҫ түгел йәки телдәр араһы йәки интервики ҡушымтаһы менән башлана.
Исемдә тыйылған символдар булыуы ла мөмкин.',
'allpages-bad-ns' => '{{SITENAME}} проектында "$1" исемдәр арауығы юҡ.',
'allpages-hide-redirects' => 'Йүнәлтеүҙәрҙе йәшерергә',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Һеҙ биттең кэшланған өлгөһөн ҡарайһығыҙ. Уның $1 элек яңыртылыуы мөмкин.',
'cachedspecial-viewing-cached-ts' => 'Һеҙ биттең кэшланған өлгөһөн ҡарайһығыҙ. Уның хәҙерге өлгөнән бик ныҡ айырылыуы мөмкин.',
'cachedspecial-refresh-now' => 'Һуңғы версияны ҡарарға',

# Special:Categories
'categories' => 'Категориялар',
'categoriespagetext' => 'Түбәндәге {{PLURAL:$1|категорияла|категорияларҙа}} биттәр йәки файлдар бар.
[[Special:UnusedCategories|Ҡулланылмаған категориялар]] бында күрһәтелмәгән.
Шулай уҡ [[Special:WantedCategories|кәрәкле категориялар исемлеген]] ҡарағыҙ.',
'categoriesfrom' => 'Ошондай хәрефтәрҙән башланған категорияларҙы күрһәтергә:',
'special-categories-sort-count' => 'күләме буйынса тәртипкә килтерергә',
'special-categories-sort-abc' => 'алфавит буйынса тәртипкә килтерергә',

# Special:DeletedContributions
'deletedcontributions' => 'Ҡулланыусыларҙың юйылған өлөшө',
'deletedcontributions-title' => 'Ҡулланыусыларҙың юйылған өлөшө',
'sp-deletedcontributions-contribs' => 'башҡарған эштәр',

# Special:LinkSearch
'linksearch' => 'Тышҡы һылтанмалар эҙләү',
'linksearch-pat' => 'Эҙләү өсөн ҡалып',
'linksearch-ns' => 'Исемдәр арауығы:',
'linksearch-ok' => 'Эҙләү',
'linksearch-text' => '<code>*.wikipedia.org</code> һымаҡ төркөм билдәләрен ҡулланырға була.
Кәмендә өҫкө кимәл домен кәрәк, мәҫәлән, <code>*.org</code><br />
Мөмкин булған{{PLURAL:$2|протокол|протоколдар}}: <code>$1</code> (башҡа протокол өҫтәлмәһә, алдан бирелгәне индерелә http://).',
'linksearch-line' => '$1 адресына $2 битенән һылтанма яһалған',
'linksearch-error' => 'Төркөм билдәләре URL адрестың башында ғына ҡулланыла ала.',

# Special:ListUsers
'listusersfrom' => 'Ошондай хәрефтәрҙән башланған ҡатнашыусыларҙы күрһәтергә:',
'listusers-submit' => 'Күрһәтергә',
'listusers-noresult' => 'Ҡатнашыусылар табылманы',
'listusers-blocked' => '(бикләнгән)',

# Special:ActiveUsers
'activeusers' => 'Әүҙем ҡатнашыусылар исемлеге',
'activeusers-intro' => 'Был — һуңғы $1 {{PLURAL:$1|көн}} эсендә ниҙер башҡарған ҡатнашыусылар исемлеге.',
'activeusers-count' => 'һуңғы $3 {{PLURAL:$3|көн}} эсендәге һуңғы көндә $1 {{PLURAL:$1|үҙгәртеү}}',
'activeusers-from' => 'Ошондай хәрефтәрҙән башланған ҡатнашыусыларҙы күрһәтергә:',
'activeusers-hidebots' => 'Боттарҙы йәшерергә',
'activeusers-hidesysops' => 'Хакимдәрҙе йәшерергә',
'activeusers-noresult' => 'Ҡатнашыусылар табылманы',

# Special:ListGroupRights
'listgrouprights' => 'Ҡатнашыусылар төркөмө хоҡуҡтары',
'listgrouprights-summary' => 'Түбәндә был вики-проектта билдәләнгән ҡатнашыусы төркөмдәре килтерелгән һәм уларҙың хоҡуҡтары күрһәтелгән.
Шәхси хоҡуҡтар тураһында [[{{MediaWiki:Listgrouprights-helppage}}|өҫтәмә мәғлүмәт]] булыуы мөмкин.',
'listgrouprights-key' => 'Легенда:
* <span class="listgrouprights-granted">Бирелгән хоҡуҡтар</span>
* <span class="listgrouprights-revoked">Алынған хоҡуҡтар</span>',
'listgrouprights-group' => 'Төркөм',
'listgrouprights-rights' => 'Хоҡуҡтар',
'listgrouprights-helppage' => 'Help:Төркөмдәр хоҡуҡтары',
'listgrouprights-members' => '(ағзалар исемлеге)',
'listgrouprights-addgroup' => '$1 {{PLURAL:$2|төркөмөнә|төркөмдәренә}} өҫтәү',
'listgrouprights-removegroup' => '$1 {{PLURAL:$2|төркөмөнән|төркөмдәренән}} сығарыу',
'listgrouprights-addgroup-all' => 'Бөтә төркөмдәргә өҫтәү',
'listgrouprights-removegroup-all' => 'Бөтә төркөмдәрҙән сығарыу',
'listgrouprights-addgroup-self' => 'Үҙенең иҫәп яҙмаһына $1 {{PLURAL:$2|төркөмөн|төркөмдәрен}} өҫтәү',
'listgrouprights-removegroup-self' => 'Үҙенең иҫәп яҙмаһынан $1 {{PLURAL:$2|төркөмөн|төркөмдәрен}} юйыу',
'listgrouprights-addgroup-self-all' => 'Үҙенең иҫәп яҙмаһына бөтә төркөмдәрҙе өҫтәү',
'listgrouprights-removegroup-self-all' => 'Үҙенең иҫәп яҙмаһынан бөтә төркөмдәрҙе юйыу',

# Email user
'mailnologin' => 'Хат ебәреү өсөн адрес юҡ',
'mailnologintext' => 'Башҡа ҡатнашыусыларға хат ебәреү өсөн, һеҙ [[Special:UserLogin|танылырға]] һәм [[Special:Preferences|көйләүҙәрегеҙҙә]] ысын электрон адрес почтаһы кереткән булырға тейешһегеҙ.',
'emailuser' => 'Ҡатнашыусыға хат',
'emailuser-title-target' => '{{GENDER:$1|Ҡатнашыусыға}} хат яҙыу',
'emailuser-title-notarget' => 'Ҡатнашыусыға хат',
'emailpage' => 'Ҡатнашыусыға хат',
'emailpagetext' => 'Был {{GENDER:$1|ҡатнашыусы}} электрон почта аша хат ебәреү өсөн, һеҙ түбәндәге форманы ҡуллана алаһығыҙ.
Яуап өсөн адрес булараҡ һеҙ [[Special:Preferences|көйләүҙәрегеҙҙә]] күрһәткән электрон почта адресы күрһәтеләсәк, шулай итеп, хатты алыусы һеҙгә тура яуап ебәрә аласаҡ.',
'usermailererror' => 'Хат ебәргән ваҡытта хата килеп сыҡты:',
'defemailsubject' => '{{SITENAME}} — $1 ҡулланыусыһынан хат',
'usermaildisabled' => 'Ҡатнашыусының электрон почтаһы һүндерелгән',
'usermaildisabledtext' => 'Һеҙ был вики-проекттың башҡа ҡатнашыусыларына электрон хат ебәрә алмайһығыҙ',
'noemailtitle' => 'Электрон почта адресы юҡ',
'noemailtext' => 'Был ҡатнашыусы дөрөҫ электрон почта адресы күрһәтмәгән',
'nowikiemailtitle' => 'Электрон хат ебәреү өсөн рөхсәт юҡ',
'nowikiemailtext' => 'Был ҡатнашыусы башҡа ҡатнашыусыларҙан электрон хат алырға теләмәүен күрһәткән.',
'emailnotarget' => 'Алыусының булмаған йәки хаталы исеме.',
'emailtarget' => 'Алыусының ҡулланыусы исемен яҙығыҙ',
'emailusername' => 'Ҡулланыусы исеме:',
'emailusernamesubmit' => 'Ебәрергә',
'email-legend' => '{{SITENAME}} проектының башҡа ҡатнашыусыһына электрон хат ебәрергә',
'emailfrom' => 'Кемдән:',
'emailto' => 'Кемгә:',
'emailsubject' => 'Тема:',
'emailmessage' => 'Хәбәр:',
'emailsend' => 'Ебәреү',
'emailccme' => 'Хаттың күсермәһен миңә ебәрергә',
'emailccsubject' => '$1 өсөн хатығыҙҙың күсермәһе: $2',
'emailsent' => 'Хат ебәрелде',
'emailsenttext' => 'Һеҙҙең электрон хатығыҙ ебәрелде.',
'emailuserfooter' => 'Был электрон хат $1 ҡатнашыусыһынан $2 ҡатнашыусыһына {{SITENAME}} проектының "Ҡатнашыусыға хат" формаһы аша ебәрелде.',

# User Messenger
'usermessage-summary' => 'Система хәбәрен ҡалдырырға.',
'usermessage-editor' => 'Система хәбәрсеһе',

# Watchlist
'watchlist' => 'Күҙәтеү исемлеге',
'mywatchlist' => 'Күҙәтеү исемлеге',
'watchlistfor2' => '$1 $2 өсөн',
'nowatchlist' => 'Һеҙҙең күҙәтеү исемлегегеҙ буш.',
'watchlistanontext' => 'Күҙәтеү исемлеген ҡарау йәки мөхәррирләү өсөн $1 кәрәк.',
'watchnologin' => 'Үҙегеҙҙе танытырға кәрәк',
'watchnologintext' => 'Күҙәтеү исемлегегеҙҙе мөхәррирләү өсөн, һеҙгә [[Special:UserLogin|танылырға]] кәрәк.',
'addwatch' => 'Күҙәтеү исемлегенә өҫтәргә',
'addedwatchtext' => '"[[:$1]]" бите [[Special:Watchlist|күҙәтеү исемлегегеҙгә]] өҫтәлде.
Был биттә һәм уның фекер алышыу битендә буласаҡ бар үҙгәртеүҙәр ундағы исемлектә күрһәтеләсәк.',
'removewatch' => 'Күҙәтеү исемлегенән сығарырға',
'removedwatchtext' => '«[[:$1]]» бите [[Special:Watchlist|күҙәтеү исемлегегеҙҙән]] сығарылды.',
'watch' => 'Күҙәтергә',
'watchthispage' => 'Был битте күҙәтергә',
'unwatch' => 'Күҙәтмәҫкә',
'unwatchthispage' => 'Күҙәтеүҙе туҡтатырға',
'notanarticle' => 'Мәҡәлә түгел',
'notvisiblerev' => 'Башҡа ҡатнашыусы тарафынан керетелгән һуңғы өлгө юйылған',
'watchlist-details' => 'Һеҙҙең күҙәтеү исемлегегеҙҙә, фекерләшеү биттәрен һанамағанда, {{PLURAL:$1|$1 бит}} бар.',
'wlheader-enotif' => 'Электрон почта аша белдереү индерелгән.',
'wlheader-showupdated' => "Һеҙҙең аҙаҡҡы кереүегеҙҙән һуң үҙгәргән биттәр '''ҡалын''' шрифт менән күрһәтелгән.",
'watchmethod-recent' => 'күҙәтелгән биттәр өсөн аҙаҡҡы үҙгәртеүҙәрҙе ҡарау',
'watchmethod-list' => 'аҙаҡҡы үҙгәртеүҙәр өсөн күҙәтелгән биттәрҙе ҡарау',
'watchlistcontains' => 'Һеҙҙең күҙәтеү исемлегендә $1 {{PLURAL:$1|бит|бит}}бар.',
'iteminvalidname' => '«$1» менән ҡыйынлыҡтар, исеме дөрөҫ түгел...',
'wlnote' => "Түбәндә $3 $4 ваҡытына тиклем аҙаҡҡы {{PLURAL:$2|сәғәт|'''$2''' сәғәт}} эсендә эшләнгән {{PLURAL:$1|үҙгәртеү|'''$1''' үҙгәртеү}} күрһәтелгән.",
'wlshowlast' => 'Һуңғы $1 сәғәт $2 көн өсөн күрһәт $3',
'watchlist-options' => 'Күҙәтеү исемлеге көйләүҙәре',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Күҙәтеү исемлегенә өҫтәү...',
'unwatching' => 'Күҙәтеү исемлегенән сығарыу...',
'watcherrortext' => 'Күҙәтеү көйләүҙәрегеҙҙе «$1» өсөн үҙгәрткән ваҡытта хата барлыҡҡа килде.',

'enotif_mailer' => '{{SITENAME}} проектының белдереү хеҙмәте',
'enotif_reset' => 'Бөтә биттәрҙе ҡаралған тип билдәләргә',
'enotif_impersonal_salutation' => '{{SITENAME}} проектының ҡатнашыусыһы',
'enotif_subject_deleted' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан юйылды',
'enotif_subject_created' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан яһалды',
'enotif_subject_moved' => '{{SITENAME}} проектының $1 битенең {{gender:$2|$2}} исеме үҙгәртелде',
'enotif_subject_restored' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан тергеҙелде',
'enotif_subject_changed' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан үҙгәртелде',
'enotif_body_intro_deleted' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан юйылды. Ваҡыты: $PAGEEDITDATE. Ҡарағыҙ: $3.',
'enotif_body_intro_created' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан яһалды. Ваҡыты: $PAGEEDITDATE. Ҡарағыҙ: $3.',
'enotif_body_intro_moved' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан күсерелде. Ваҡыты: $PAGEEDITDATE. Ҡарағыҙ: $3.',
'enotif_body_intro_restored' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан тергеҙелде. Ваҡыты: $PAGEEDITDATE. Ҡарағыҙ: $3.',
'enotif_body_intro_changed' => '{{SITENAME}} проектының $1 исемле бите {{gender:$2|$2}} тарафынан үҙгәртелде. Ваҡыты: $PAGEEDITDATE. Ҡарағыҙ: $3.',
'enotif_lastvisited' => 'Һеҙҙең аҙаҡҡы кереүегеҙҙән һуңғы үҙгәртеүҙәрҙе ҡарау өсөн, $1 ҡарағыҙ.',
'enotif_lastdiff' => 'Был үҙгәртеүҙе ҡарау өсөн, $1 ҡарағыҙ.',
'enotif_anon_editor' => 'танылмаған ҡатнашыусы $1',
'enotif_body' => 'Хөрмәтле $WATCHINGUSERNAME,

$PAGEINTRO $NEWPAGE

Мөхәррирләү аңлатмаһы: $PAGESUMMARY $PAGEMINOREDIT

Үҙгәртеүсе менән бәйләнеш өсөн:
Эл. почта адресы: $PAGEEDITOR_EMAIL
Вики бите: $PAGEEDITOR_WIKI

Әгәр һеҙ был битте ҡарамаһағыҙ, бынан һуң буласаҡ үҙгәртеүҙәр тураһында белдереү алмаясаҡһығыҙ. 
Һеҙ шулай уҡ күҙәтеү исемлегегеҙҙәге бар биттәр өсөн белдереү көйләүен һүндерә алаһығыҙ.

{{SITENAME}}  проектының белдереү системаһы

--
Электрон почта белдереүҙәрен көйләү өсөн:
{{canonicalurl:{{#special:Preferences}}}}

Күҙәтеү исемлеге көйләүҙәрен үҙгәртер өсөн:
{{canonicalurl:{{#special:EditWatchlist}}}}

Битте һеҙҙең күҙәтеү исемлегенән юйыр өсөн:
$UNWATCHURL

Кире бәйләнеш һәм ярҙам:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'булдырылды',
'changed' => 'үҙгәртелгән',

# Delete
'deletepage' => 'Битте юйырға',
'confirm' => 'Раҫларға',
'excontent' => 'эстәлеге: "$1"',
'excontentauthor' => 'эстәлеге: "$1" (һәм берҙән-бер авторы "[[Special:Contributions/$2|$2]]" ине)',
'exbeforeblank' => 'юйыуға тиклемге эсләлеге: "$1"',
'exblank' => 'Биттә бер нимә юҡ',
'delete-confirm' => '$1 — юйырға',
'delete-legend' => 'Юйырға',
'historywarning' => "'''Киҫәтеү:''' һеҙ юйырға йыйынған биттең тарихында яҡынса $1 {{PLURAL:$1|өлгө}} бар:",
'confirmdeletetext' => 'Һеҙ был биттең (йәки рәсемдең) һәм уның мәғлүмәттәр базаһындағы үҙгәртеүҙәр тарихының тулыһынса юйылыуын һоранығыҙ.
Зинһар, быны эшләргә теләгәнегеҙҙе, үҙ хәрәкәттәрегеҙҙең һөҙөмтәләрен аңлағанығыҙҙы һәм [[{{MediaWiki:Policy-url}}]] бүлегендә белдереп кителгән ҡағиҙәләр буйынса эшләгәнегеҙҙе раҫлағыҙ.',
'actioncomplete' => 'Ғәмәл үтәлде',
'actionfailed' => 'Ғәмәл үтәлмәне',
'deletedtext' => '«$1» юйылды.
Юйылған һуңғы биттәрҙе ҡарар өсөн: $2.',
'dellogpage' => 'Юйыуҙар журналы',
'dellogpagetext' => 'Түбәндә һуңғы юйыуҙар яҙмалары журналы килтерелгән.',
'deletionlog' => 'Юйыуҙар журналы',
'reverted' => 'Элекке өлгөһөнә ҡайтарылған',
'deletecomment' => 'Сәбәп:',
'deleteotherreason' => 'Башҡа/өҫтәмә сәбәп:',
'deletereasonotherlist' => 'Башҡа сәбәп',
'deletereason-dropdown' => '* Ғәҙәттәге юйыу сәбәптәре
**спам
**емереү
**авторлыҡ хоҡуҡтарын боҙоу
**автор үтенесе буйынса
**эшләмәгән ҡайтанан йүнәлтеү',
'delete-edit-reasonlist' => 'Сәбәптәр исемлеген мөхәррирләргә',
'delete-toobig' => 'Был биттең үҙгәртеүҙәр тарихы бик оҙон, $1 {{PLURAL:$1|өлгөнән}} күберәк.
{{SITENAME}} проектының эшмәкәрлеге боҙолмауы маҡсатында бындай биттәрҙе юйыу тыйылған.',
'delete-warning-toobig' => 'Был биттең үҙгәртеүҙәр тарихы бик оҙон, $1 {{PLURAL:$1|өлгөнән}} күберәк.
Битте юйыу {{SITENAME}} проектының эшмәкәрлеге боҙолоуына килтереүе мөмкин, һаҡлыҡ менән эш итегеҙ.',

# Rollback
'rollback' => 'Үҙгәртеүҙәрҙе кире ҡайтарырға',
'rollback_short' => 'Кире ҡайтарырға',
'rollbacklink' => 'кире',
'rollbacklinkcount' => '$1 {{PLURAL:$1|төҙәтеүҙе|төҙәтеүҙе}} кире алырға',
'rollbacklinkcount-morethan' => '$1 {{PLURAL:$1|төҙәтеүҙән|төҙәтеүҙән}} күберәк кире алырға',
'rollbackfailed' => 'Кире ҡайтарырғанда барлыҡҡа килгән хата',
'cantrollback' => 'Үҙгәртеүҙәрҙе кире алыу мөмкин түгел. Битте һуңғы үҙгәртеүсе ҡатнашыусы уның берҙән-бер авторы булып тора.',
'alreadyrolled' => '[[User:$2|$2]] ([[User talk:$2|фекер алышыу]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]])  кереткән [[:$1]] һуңғы үҙгәртеүҙәрҙе кире алыу мөмкин түгел; башҡа ҡатнашыусы был битте мөхәррирләгән йәки үҙгәртеүҙәрҙе кире алған инде.

Һуңғы үҙгәртеүҙәрҙе [[User:$3|$3]] ([[User talk:$3| фекер алышыу]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) кереткән.',
'editcomment' => "Үҙгәртеүҙең тасуирламаһы \"''\$1''\" ине.",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|фекер алышыу]]) уҙгәртеүҙәре [[User:$1|$1]] өлгөһөнә ҡайтарылды',
'revertpage-nouser' => '(Ҡатнашыусының исеме йәшерелгән) үҙгәртеүҙәре {{GENDER:$1|[[User:$1|$1]]}}өлгөһөнә ҡайтарылды',
'rollback-success' => '$1 уҙгәртеүҙәре кире алдынды;
$2 өлгөһөнә ҡайтыу.',

# Edit tokens
'sessionfailure-title' => 'Сеанс хатаһы',
'sessionfailure' => 'Хәҙерге сеанста хаталар килеп сыҡҡан, булырға тейеш;
"сеансты баҫып алыу"ға юл ҡуймау өсөн был ғәмәл үтәлмәне.
Алдағы биткә кире  ҡайтығыҙ, битте яңыртығыҙ һәм яңынан ҡабатлап ҡарағыҙ.',

# Protect
'protectlogpage' => 'Һаҡлау яҙмалары',
'protectlogtext' => 'Түбәндә битте һаҡлауҙы үҙгәртеү яҙмалары килтерелгән.
Һеҙ шулай уҡ хәҙерге ваҡытта [[Special:ProtectedPages|һаҡланған биттәр исемлеген]] ҡарай алаһығыҙ.',
'protectedarticle' => '«[[$1]]» битен һаҡлаған',
'modifiedarticleprotection' => '"[[$1]]" битенең һаҡлау дәрәжәһен үҙгәрткән',
'unprotectedarticle' => '«[[$1]]» битенән һаҡлау алынды',
'movedarticleprotection' => 'һаҡлау көйләүҙәрен «[[$2]]» битенән «[[$1]]» битенә күсергән',
'protect-title' => '"$1" битенең һаҡлау дәрәжәһен үҙгәртеү',
'protect-title-notallowed' => '"$1" битенең һаҡлау дәрәжәһен байҡау',
'prot_1movedto2' => '[[$1]] битенең исемен [[$2]] тип үҙгәрткән',
'protect-badnamespace-title' => 'Һаҡланмаған исемдәр арауығы',
'protect-badnamespace-text' => 'Был исемдәр арауығындағы биттәрҙе һаҡлап булмай.',
'protect-norestrictiontypes-text' => 'Был бит һаҡлана алмай, сөнки уға сикләүҙәрҙең рөхсәт ителгән төрҙәре юҡ.',
'protect-norestrictiontypes-title' => 'Һаҡланмаған бит',
'protect-legend' => 'Битте һаҡлауҙы раҫлау',
'protectcomment' => 'Сәбәп:',
'protectexpiry' => 'Тамамлана:',
'protect_expiry_invalid' => 'Һаҡлауҙың тамамланыу ваҡыты дөрөҫ түгел.',
'protect_expiry_old' => 'Һаҡлауҙың тамамланыу ваҡыты үткән көнгә ҡуйылған.',
'protect-unchain-permissions' => 'Өҫтәмә һаҡлау шарттарын асырға',
'protect-text' => "Бында һеҙ '''$1''' битенең һаҡлау дәрәжәһен ҡарай һәм үҙгәртә алаһығыҙ.",
'protect-locked-blocked' => "Һеҙҙең исәп яҙмағыҙ бикләнгән ваҡытта һеҙ биттең һаҡлау дәрәжәһен үҙгәртә алмайһығыҙ.
'''$1''' битенең хәҙерге һаҡлау көйләүҙәре:",
'protect-locked-dblock' => "Һаҡлау дәрәжәһе үҙгәртелә алмай, сөнки төп мәғлүмәттәр базаһы ваҡытлыса бикле.
'''$1''' битенең хәҙерге һаҡлау көйләүҙәре:",
'protect-locked-access' => "Биттең һаҡлау дәрәжеһен үҙгәртер өсөн иҫәп яҙыуығыҙҙың хоҡуҡтары етәрле түгел. '''$1''' битенең хәҙерге һаҡлау көйләүҙәре:",
'protect-cascadeon' => 'Был бит һаҡланған, сөнки ул эҙмә-эҙлекле һаҡлау ҡуйылған {{PLURAL:$1|биткә|биттәргә}} керә. Һеҙ был биттең һаҡлау дәрәжәһен үҙгәртә алаһығыҙ, ләкин был эҙмә-эҙлекле һаҡлауға йоғонто яһамаясаҡ.',
'protect-default' => 'Бар ҡулланыусыларға рөхсәт бирергә',
'protect-fallback' => '«$1» хоҡуҡлы ҡатнашыусыларға ғына рөхсәте ителгән',
'protect-level-autoconfirmed' => 'Үҙенән-үҙе раҫланған ҡатнашыусыларға ғына рөхсәт ителгән',
'protect-level-sysop' => 'Хакимдәр генә',
'protect-summary-cascade' => 'эҙмә-эҙлекле',
'protect-expiring' => '$1 бөтә (UTC)',
'protect-expiring-local' => '$1 тамамлана',
'protect-expiry-indefinite' => 'сикһеҙ',
'protect-cascade' => 'Был биткә кергән биттәрҙе һаҡларға (эҙмә-эҙлекле һаҡлау)',
'protect-cantedit' => 'Һеҙ был биттең һаҡлау дәрәжәһен үҙгәртә алмайһығыҙ, сөнки уны үҙгәртеү хоҡуғығыҙ юҡ.',
'protect-othertime' => 'Башҡа ваҡыт:',
'protect-othertime-op' => 'башҡа ваҡыт',
'protect-existing-expiry' => 'Хәҙерге тамамланыу ваҡыты: $2 $3',
'protect-otherreason' => 'Башҡа/өҫтәмә сәбәп:',
'protect-otherreason-op' => 'Башҡа сәбәп',
'protect-dropdown' => '*Ғәҙәттәге һаҡлау сәбәптәре:
** Үтә ныҡлы вандаллыҡ
** Үтә ныҡлы спам
** Файҙаһыҙ үҙгәртеүҙәр ярышы
** Киң танылған бит',
'protect-edit-reasonlist' => 'Сәбәптәр исемлеген мөхәррирләргә',
'protect-expiry-options' => '1 сәғәт:1 hour,1 көн:1 day,1 аҙна:1 week,2 аҙна:2 weeks,1 ай:1 month,3 ай:3 months,6 ай:6 months,1 йыл:1 year,сикләүһеҙ:infinite',
'restriction-type' => 'Хоҡуҡ:',
'restriction-level' => 'Ирешеү дәрәжәһе:',
'minimum-size' => 'Минимум күләм',
'maximum-size' => 'Максимум күләм:',
'pagesize' => '(байт)',

# Restrictions (nouns)
'restriction-edit' => 'Мөхәррирләү',
'restriction-move' => 'Исемен үҙгәртеү',
'restriction-create' => 'Яһау',
'restriction-upload' => 'Тейәү',

# Restriction levels
'restriction-level-sysop' => 'тулы һаҡлау',
'restriction-level-autoconfirmed' => 'өлөшләтә һаҡлау',
'restriction-level-all' => 'барлыҡ кимәлдәр',

# Undelete
'undelete' => 'Юйылған биттәрҙе ҡарау',
'undeletepage' => 'Юйылған биттәрҙе ҡарау һәм тергеҙеү',
'undeletepagetitle' => "'''Түбәндә [[:$1|$1]] битенең юйылған өлгөләре килтерелгән'''.",
'viewdeletedpage' => 'Юйылған биттәрҙе ҡарау',
'undeletepagetext' => 'Түбәндәге {{PLURAL:$1|бит|$1 бит}} юйылған, әммә һаман архивта һаҡлана һәм тергеҙелә ала.
Архив ваҡыты менән таҙартыла ала.',
'undelete-fieldset-title' => 'Өлгөләрҙе тергеҙергә',
'undeleteextrahelp' => "Биттең тарихын тулыһынса тергеҙер өсөн, бөтә өлгөләрҙе лә һайланмаған килеш ҡалдырығыҙ һәм '''''{{int:undeletebtn}}''''' төймәһенә баҫығыҙ.
Ҡайһы бер өлгөләрҙе генә тергеҙер өсөн, кәрәкле өлгөләрҙе һайлағыҙ һәм '''''{{int:undeletebtn}}''''' төймәһенә баҫығыҙ.",
'undeleterevisions' => '$1 {{PLURAL:$1|өлгө|өлгө}} архивта һаҡланған',
'undeletehistory' => 'Битте тергеҙгәндә уны үҙгәртеү тарихы ла тергеҙелә.
Әгәр бит юйылғандан һуң шундай уҡ исемле бит булдырылған булһа, тергеҙелгән өлгөләр яңы өлгөләр алдынан ҡуйыласаҡ.',
'undeleterevdel' => 'Әгәр тергеҙеү биттең йәки файлдың аҙаҡҡы өлгөһө өлөшләтә юйылыуына килтерһә, был терегеҙеү башҡарылмаясаҡ.
Бындай ваҡытта һеҙ һуңғы юйылған өлгөләрҙе һайлауҙы алырға йәки күрһәтергә тейешһегеҙ.',
'undeletehistorynoadmin' => 'Мәҡәлә юйылған.
Түбәндә юйыу сәбәптәре һәм мәҡәләне юйғанға тиклем мөхәррирләүсе ҡатнашыусылар исемлеге килтерелгән. 
Юйылған мәҡәләне хакимдәр генә ҡарай ала.',
'undelete-revision' => '$1 битенең $3 ҡатнашыусыһының ($4 $5 мөхәррирләгән) юйылған өлгөһө:',
'undeleterevision-missing' => 'Был өлгө дөрөҫ түгел йәки бөтөнләй юҡ.
Һеҙ дөрөҫ булмаған һылтанма аша кергәнһегеҙ йәки был өлгө архивтан юйылған, булырға тейеш.',
'undelete-nodiff' => 'Алдағы өлгө табылманы.',
'undeletebtn' => 'Тергеҙергә',
'undeletelink' => 'ҡарарға/тергеҙергә',
'undeleteviewlink' => 'ҡарарға',
'undeletereset' => 'Юҡ итергә',
'undeleteinvert' => 'Һайланғандарҙы әйләндерергә',
'undeletecomment' => 'Сәбәп:',
'undeletedrevisions' => '$1 {{PLURAL:$1|өлгө}} тергеҙелде',
'undeletedrevisions-files' => '{{PLURAL:$1|өлгө}} һәм {{PLURAL:$2|файл}} тергеҙелде',
'undeletedfiles' => '{{PLURAL:$1|файл}} тергеҙелде',
'cannotundelete' => 'Юйыуҙы кире алып булманы:
$1',
'undeletedpage' => "'''$1 бите тергеҙелде'''

Һуңғы юйыуҙарҙы һәм тергеҙеүҙәрҙе ҡарау өсөн, [[Special:Log/delete|юйыу яҙмалары журналын]] ҡарағыҙ.",
'undelete-header' => 'Һуңғы юйылған биттәрҙе [[Special:Log/delete|юйыу яҙмалары журналында]] ҡарағыҙ.',
'undelete-search-title' => 'Юйылған биттәрҙе эҙләү',
'undelete-search-box' => 'Юйылған биттәрҙе эҙләү',
'undelete-search-prefix' => 'Ошолай башланған биттәрҙе күрһәтергә:',
'undelete-search-submit' => 'Эҙләү',
'undelete-no-results' => 'Юйыу яҙмалары архивында кәрәкле биттәр юҡ.',
'undelete-filename-mismatch' => '$1 ваҡыт билдәһе менән файл өлгөһөн тергеҙеү мөмкин түгел: файл исеме тап килмәй',
'undelete-bad-store-key' => '$1 ваҡыт билдәһе менән файл өлгөһөн тергеҙеү мөмкин түгел: файл юйылыуға тиклем булмаған',
'undelete-cleanup-error' => 'Ҡулланылмаған "$1" архив файлын юйыу хатаһы.',
'undelete-missing-filearchive' => 'Архив индикаторы $1 булған файлды тергеҙеү мөмкин түгел, сөнки ул мәғлүмәттәр базаһында юҡ.
Файл бығаса тергеҙелгән, булыуы мөмкин.',
'undelete-error' => 'Битте тергеҙеү хатаһы',
'undelete-error-short' => 'Файлды тергеҙеү хатаһы: $1',
'undelete-error-long' => 'Файлды тергеҙеү ваҡытында хаталар килеп сыҡты:

$1',
'undelete-show-file-confirm' => '"<nowiki>$1</nowiki>" файлының юйылған $2 $3 өлгөһөн ҡарарға теләүегеҙҙе раҫлайһығыҙмы?',
'undelete-show-file-submit' => 'Эйе',

# Namespace form on various pages
'namespace' => 'Исемдәр арауығы:',
'invert' => 'Һайланғандарҙы әйләндерергә',
'tooltip-invert' => 'Һайланған исемдәр арауығындағы (һәм бәйле исемдәр арауығындағы, әгәр күрһәтелһә) биттәрҙәге үҙгәртеүҙәрҙе йәшерер өсөн был билдәне ҡуйығыҙ.',
'namespace_association' => 'Бәйле арауыҡ',
'tooltip-namespace_association' => 'Һайланған исемдәр арауығы менән бәйле әңгәмә(йәки тема) исем арауыҡтарын ҡушыр өсөн был билдәне ҡуйығыҙ.',
'blanknamespace' => '(Төп)',

# Contributions
'contributions' => '{{GENDER:$1|Ҡатнашыусы}} өлөшө',
'contributions-title' => '$1 исемле ҡулланыусының кереткән өлөшө',
'mycontris' => 'Өлөш',
'contribsub2' => '{{GENDER:$3|$1}} өлөшө ($2)',
'nocontribs' => 'Күрһәтелгән шарттарға яуап биргән үҙгәртеүҙәр табылманы.',
'uctop' => '(ағымдағы)',
'month' => 'Айҙан башлап (һәм элегерәк):',
'year' => 'Йылдан башлап (һәм элегерәк):',

'sp-contributions-newbies' => 'Яңы иҫәп яҙмалары кереткән өлөштө генә күрһәтергә',
'sp-contributions-newbies-sub' => 'Яңы иҫәп яҙмалары өсөн',
'sp-contributions-newbies-title' => 'Яңы иҫәп яҙмалары өсөн ҡатнашыусы өлөшө',
'sp-contributions-blocklog' => 'блоклау яҙмалары',
'sp-contributions-deleted' => 'ҡулланыусының юйылған өлөшө',
'sp-contributions-uploads' => 'тейәүҙәр',
'sp-contributions-logs' => 'журналдар',
'sp-contributions-talk' => 'фекерләшеү',
'sp-contributions-userrights' => 'ҡатнашыусы хоҡуҡтарын идаралау',
'sp-contributions-blocked-notice' => 'Әлеге ваҡытта был ҡатнашыусы бикле.
Түбәндә бикләү яҙмаларынан һуңғы ҡатнашыусыны бикләү яҙмаһы килтерелгән:',
'sp-contributions-blocked-notice-anon' => 'Әлеге ваҡытта был IP адрес бикле.
Түбәндә бикләү яҙмаларынан һуңғы адресты бикләү яҙмаһы килтерелгән:',
'sp-contributions-search' => 'Өлөштәрҙе эҙләү',
'sp-contributions-username' => 'Ҡулланыусының IP-адресы йәки исеме:',
'sp-contributions-toponly' => 'Һуңғы өлгөләрҙе генә күрһәтергә',
'sp-contributions-submit' => 'Эҙлә',

# What links here
'whatlinkshere' => 'Бында һылтанмалар',
'whatlinkshere-title' => '«$1» битенә һылтанған биттәр',
'whatlinkshere-page' => 'Бит:',
'linkshere' => "'''[[:$1]]''' битенә киләһе биттәр һылтана:",
'nolinkshere' => "'''[[:$1]]''' битенә бер бит тә һылтанмай.",
'nolinkshere-ns' => "'''[[:$1]]''' битенә һайланған исемдәр арауығынан бер бит тә һылтанмай.",
'isredirect' => 'йүнәлтеү бите',
'istemplate' => 'ҡушылған',
'isimage' => 'файл һылтанмаһы',
'whatlinkshere-prev' => '{{PLURAL:$1|алдағы|алдағы $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|киләһе|киләһе $1}}',
'whatlinkshere-links' => '← һылтанмалар',
'whatlinkshere-hideredirs' => 'Йүнәлтеүҙәрҙе $1',
'whatlinkshere-hidetrans' => 'Ҡушылғандарҙы $1',
'whatlinkshere-hidelinks' => 'Һылтанмаларҙы $1',
'whatlinkshere-hideimages' => 'файл һылтанмаларын $1',
'whatlinkshere-filters' => 'Һайлау',

# Block/unblock
'autoblockid' => 'Автобикләү #$1',
'block' => 'Ҡатнашыусыны бикләү',
'unblock' => 'Бикләнгән ҡатнашыусыны азат итеү',
'blockip' => 'Ҡатнашыусыны бикләү',
'blockip-title' => 'Ҡатнашыусыны бикләү',
'blockip-legend' => 'Ҡатнашыусыны бикләү',
'blockiptext' => 'Билдәләнгән IP адрестан яҙыу мөмкинлеген бикләү өсөн, түбәндәге форманы ҡулланығыҙ.
Был бары тик вандаллыҡҡа юл ҡуймау өсөн генә һәм [[{{MediaWiki:Policy-url}}|ҡағиҙәләр]] буйынса ғына эшләнергә тейеш.
Түбәндә бикләү сәбәбен күрһәтегеҙ (мәҫәлән, вандаллыҡ эҙҙәре булған бер нисә биттең цитатаһын килтерегеҙ).',
'ipadressorusername' => 'Ҡатнашыусының IP-адресы йәки исеме:',
'ipbexpiry' => 'Тамамлана:',
'ipbreason' => 'Сәбәп:',
'ipbreasonotherlist' => 'Башҡа сәбәп',
'ipbreason-dropdown' => '*Ғәҙәттәге бикләү сәбәптәре 
** Ялған мәғлүмәт өҫтәү
** Биттәрҙең эстәлеген юйыу
** Тышҡы сайттарға һылтанмалар спамлау
** Биттәргә мәғәнәһеҙ яҙмалар (ҡый) өҫтәү 
** Ҡатнашыусыларға янау (эҙәрлекләү)
** Бер нисә иҫәп яҙмаларын артығы менән булдырыу
** Килешһеҙ иҫәп яҙмаһы',
'ipb-hardblock' => 'Был IP-адрестан танылған ҡулланыусыларға мөхәррирләүҙе тыйырға',
'ipbcreateaccount' => 'Яңы иҫәп яҙыуҙарын булдырыуҙы тыйыу',
'ipbemailban' => 'Электрон почтаға хат ебәреүҙе тыйыу',
'ipbenableautoblock' => 'Был ҡатнашыусы ҡулланған һуңғы IP адрестарҙы һәм артабан үҙгәртеү өсөн ҡулланрға тырышҡан IP адрестарҙы бикләргә.',
'ipbsubmit' => 'Был ҡатнашыусыны тыйырға',
'ipbother' => 'Башҡа ваҡыт:',
'ipboptions' => '2 сәғәт:2 hours,1 көн:1 day,3 көн:3 days,1 аҙна:1 week,2 аҙна:2 weeks,1 ай:1 month,3ай:3 months,6 ай:6 months,1 йыл:1 year,сикләнмәгән:infinite',
'ipbotheroption' => 'башҡа',
'ipbotherreason' => 'Башҡа/өҫтәмә сәбәп:',
'ipbhidename' => 'Ҡатнашыусының исемен үҙгәртеүҙәрҙә һәм исемлектәрҙә йәшерергә',
'ipbwatchuser' => 'Ҡатнашыусының битен һәм фекер алышыу битен күҙәтеүҙәр исемлегенә өҫтәргә',
'ipb-disableusertalk' => 'Бикләү ваҡытында был ҡулланыусыны үҙ фекер алышыу битен мөхәррирләүҙән тыйырға',
'ipb-change-block' => 'Ҡатнашыусыны ошо көйләүҙәр менән яңынан бикләргә',
'ipb-confirm' => 'Бикләүҙе раҫларға',
'badipaddress' => 'IP адрес дөрөҫ түгел',
'blockipsuccesssub' => 'Бикләү уңышлы башҡарылды',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] бикләнде.<br />
Биктәрҙе күреү өсөн [[Special:BlockList|бикләнгән IP адрестарҙы]] ҡарағыҙ.',
'ipb-blockingself' => 'Һеҙ үҙегеҙҙе бикләргә теләйһегеҙ! Быны эшләүҙе раҫлайһығыҙмы?',
'ipb-confirmhideuser' => '"Ҡулланыусыны йәшер" ғәмәлдә саҡта ҡулланыусыны блокларға теләйһегеҙ. Уның исеме исемлектәрҙә һәм журналдарҙа күренмәйәсәк. Быны эшләргә теләүегеҙҙе раҫлайһығыҙмы?',
'ipb-edit-dropdown' => 'Бикләү сәбәптәрен мөхәррирләргә',
'ipb-unblock-addr' => '$1 биген алырға',
'ipb-unblock' => 'Ҡатнашыусының йәки IP адрестың биген алырға',
'ipb-blocklist' => 'Булған бикләүҙәрҙе күрһәтергә',
'ipb-blocklist-contribs' => '$1 ҡатнашыусыһының кереткән өлөшө',
'unblockip' => 'Ҡатнашыусының биген алырға',
'unblockiptext' => 'Бикләнгән IP адрестан йәки иҫәп яҙмаһынан яҙыу мөмкинлеген тергеҙеү өсөн, түбәндәге форманы ҡулланығыҙ.',
'ipusubmit' => 'Был бикте алырға',
'unblocked' => '[[User:$1|$1]] бикләнгән',
'unblocked-range' => '$1 биге сиселде',
'unblocked-id' => '$1 биге алынған',
'blocklist' => 'Тыйылған ҡатнашыусылар',
'ipblocklist' => 'Тыйылған ҡатнашыусылар',
'ipblocklist-legend' => 'Бикләнгән ҡатнашыусыны эҙләү',
'blocklist-userblocks' => 'Иҫәп яҙыуҙарын бикләүҙе йәшер',
'blocklist-tempblocks' => 'Ваҡытлыса бикләүҙәрҙе йәшер',
'blocklist-addressblocks' => 'Айырым IP бикләүҙәрҙе йәшер',
'blocklist-rangeblocks' => 'Диапазон бикләүҙәрен йәшер',
'blocklist-timestamp' => 'Ваҡыт',
'blocklist-target' => 'Маҡсат',
'blocklist-expiry' => 'Тамамлана',
'blocklist-by' => 'Бикләүсе администратор',
'blocklist-params' => 'Бикләү параметрҙары',
'blocklist-reason' => 'Сәбәп',
'ipblocklist-submit' => 'Эҙләү',
'ipblocklist-localblock' => 'Урындағы (локаль) бикләү',
'ipblocklist-otherblocks' => 'Башҡа {{PLURAL:$1|бикләү|бикләүҙәр}}',
'infiniteblock' => 'сикһеҙ',
'expiringblock' => '$1 $2 тамамлана',
'anononlyblock' => 'танылмағандарҙы ғына',
'noautoblockblock' => 'авто бикләү һүндерелгән',
'createaccountblock' => 'иҫәп яҙыуҙарын булдырыу бикләнгән',
'emailblock' => 'электрон хат ебәреү тыйылған',
'blocklist-nousertalk' => 'үҙенең фекер алышыу битен мөхәррирләй алмай',
'ipblocklist-empty' => 'Бикләү исемлеге буш.',
'ipblocklist-no-results' => 'Күрһәтелгән IP адрес йәки ҡатнашыусы исеме бикләнмәгән.',
'blocklink' => 'яб.',
'unblocklink' => 'Тыйыуҙы кире алырға',
'change-blocklink' => 'блоклауҙы үҙгәртергә',
'contribslink' => 'өл.',
'emaillink' => 'электрон хат ебәрергә',
'autoblocker' => 'Һеҙҙең IP адресығыҙ [[User:$1|$1]] яңыраҡ ҡулланған адрес менән тап килеүе арҡаһында бикләнде.
$1 ҡатнашыусыһын бикләү сәбәбе: "$2"',
'blocklogpage' => 'Блоклау яҙмалары',
'blocklog-showlog' => 'Был ҡатнашыусы бикләнгән ине инде.
Түбәндә белешмә өсөн бикләү яҙмалары журналы килтерелгән:',
'blocklog-showsuppresslog' => 'Был ҡатнашыусы бикләнгән һәм йәшерелгән ине инде.
Түбәндә белешмә өсөн йәшереү яҙмалары журналы килтерелгән:',
'blocklogentry' => '[[$1]] бикләгән, тамамланыу ваҡыты: $2 $3',
'reblock-logentry' => '[[$1]] ҡатнашыусыһының бикләү көйләүҙәрен үҙгәрткән, тамамланыу ваҡыты — $2 $3',
'blocklogtext' => 'Ҡатнашыусыларҙы бикләү һәм бикте алыу журналы.
Автоматик бикләнгән IP адрестар бында күрһәтелмәй.
[[Special:BlockList|Ғәмәлдәге тыйыуҙырҙы һәм бикләүҙәрҙе]] ҡарай алаһығыҙ.',
'unblocklogentry' => '$1 ҡулланыусыһының блокланыу ваҡыты тамамланды',
'block-log-flags-anononly' => 'танылмаған ҡатнашыусылар ғына',
'block-log-flags-nocreate' => 'иҫәп яҙыуҙарын теркәү тыйылған',
'block-log-flags-noautoblock' => 'авто бикләү һүндерелгән',
'block-log-flags-noemail' => 'электрон хат ебәреү тыйылған',
'block-log-flags-nousertalk' => 'үҙенең фекер алышыу битен мөхәррирләй алмай',
'block-log-flags-angry-autoblock' => 'киңәйтелгән авто бикләү һайланған',
'block-log-flags-hiddenname' => 'ҡатнашыусы исеме йәшерелгән',
'range_block_disabled' => 'Хакимдәргә бикләү арауыҡтарын булдырыу тыйылған.',
'ipb_expiry_invalid' => 'Тамамланыу ваҡыты дөрөҫ түгел.',
'ipb_expiry_temp' => 'Бикләү ваҡытында ҡатнашыусы исеме йәшерелһә, бикләү ваҡыты сикһеҙ булырға тейеш.',
'ipb_hide_invalid' => 'Иҫәп яҙмаһын йәшереү мөмкин түгел, ул бигерәк күп үҙгәртеүҙәр яһаған, булырға тейеш.',
'ipb_already_blocked' => '"$1" бикләнгән инде.',
'ipb-needreblock' => '$1 бикләнгән инде.
Бикләү көйләүҙәрен үҙгәртергә теләйһегеҙме?',
'ipb-otherblocks-header' => 'Башҡа {{PLURAL:$1|бикләү|бикләүҙәр}}',
'unblock-hideuser' => 'Һеҙ был ҡулланыусының биген ала алмайһығыҙ, сөнки ҡулланыусы исеме йәшерелгән.',
'ipb_cant_unblock' => 'Хата: Идентификаторы $1 булған бикләү табылманы.
Ул бик алынған, булырға тейеш.',
'ipb_blocked_as_range' => 'Хата: $1 IP адресы бикләнгән һәм туранан-тура биген алып булмай.
Әммә ул $2 бикләү арауығына керә һәм был арауыҡтың биге алына ала.',
'ip_range_invalid' => 'IP адрестар арауығы дөрөҫ түгел.',
'ip_range_toolarge' => '/$1 арауығынан ҙурыраҡ адрестар арауығын бикләү рөхсәт ителмәй.',
'proxyblocker' => 'Проксины бикләү',
'proxyblockreason' => 'Һеҙҙең IP адресығыҙ бикләнгән, сөнки ул — асыҡ прокси.
Зинһар, Интернет менән тәъмин итеүсегеҙгә йәки ярҙам хеҙмәтенә мөрәжәғәт итегеҙ һәм уларға был едти хәүефһеҙлек хатаһы тураһында хәбәр итегеҙ.',
'sorbsreason' => 'Һеҙҙең IP адресығыҙ {{SITENAME}} проекты ҡулланған DNSBL исемлегендә асыҡ прокси тип иҫәпләнә.',
'sorbs_create_account_reason' => 'Һеҙҙең IP адресығыҙ {{SITENAME}} проекты ҡулланған DNSBL исемлегендә асыҡ прокси тип иҫәпләнә.
Һеҙ иҫәп яҙмаһы булдыра алмайһығыҙ.',
'xffblockreason' => 'X-Forwarded-For атамаһы эсенә ингән һәм һеҙҙекеме, һеҙ ҡулланған прокси-серверҙыҡымы булған IP-адрес бикләнде. Бикләүҙең тәүсәбәбе ошо ине: $1',
'cant-block-while-blocked' => 'Үҙегеҙ бикләнгән ваҡытта һеҙ башҡа ҡатнашыусыларҙы бикләй алмайһығыҙ.',
'cant-see-hidden-user' => 'Һеҙ бикләргә тырышҡан ҡатнашыусы әлеге ваҡытта бикләнгән һәм йәшерелгән.
Ҡатнашыусыларҙы йәшереү хоҡуғығыҙ булмағанға күрә, һеҙ был бикләүҙе ҡарай йәки үҙгәртә алмайһығыҙ.',
'ipbblocked' => 'Үҙегеҙ бикләнгән ваҡытта һеҙ башҡа ҡатнашыусыларҙы бикләй йәки бикте ала алмайһығыҙ.',
'ipbnounblockself' => 'Һеҙ үҙегеҙҙән бикте ала алмайһығыҙ.',

# Developer tools
'lockdb' => 'Мәғлүмәттәр базаһын бикләргә',
'unlockdb' => 'Мәғлүмәттәр базаһынан бикте алырға',
'lockdbtext' => 'Мәғлүмәттәр базаһын бикләү бөтә ҡатнашыусылар өсөн биттәрҙе мөхәррирләү, көйләүҙәрҙе үҙгәртеү, күҙәтеү исемлеген үҙгәртеү һәм башҡа мәғлүмәттәр базаһын үҙгәртеүҙе талап иткән ғәмәдәрҙе башҡарыу мөмкинлеген туҡтатасаҡ.
Зинһар, һеҙ нәҡ ошоно эшләргә теләүегеҙҙе һәм, мәғлүмәттәр базаһын хеҙмәтләндереүҙе тамамлағас та, бикте аласағығыҙҙы раҫлағыҙ.',
'unlockdbtext' => 'Мәғлүмәттәр базаһының биген асыу бөтә ҡатнашыусылар өсөн биттәрҙе мөхәррирләү, көйләүҙәрҙе үҙгәртеү, күҙәтеү исемлеген үҙгәртеү һәм башҡа мәғлүмәттәр базаһын үҙгәртеүҙе талап иткән ғәмәдәрҙе башҡарыу мөмкинлеген тергеҙәсәк.
Зинһар, һеҙ нәҡ ошоно эшләргә теләүегеҙҙе раҫлағыҙ.',
'lockconfirm' => 'Эйе, мин ысынлап та мәғлүмәттәр базаһын бикләргә теләйем.',
'unlockconfirm' => 'Эйе, мин ысынлап та мәғлүмәттәр базаһының биген асырға теләйем.',
'lockbtn' => 'Мәғлүмәттәр базаһын бикләргә',
'unlockbtn' => 'Мәғлүмәттәр базаһынан бикте алырға',
'locknoconfirm' => 'Һеҙ раҫлау юлында билдә ҡуймағанһығыҙ.',
'lockdbsuccesssub' => 'Мәғлүмәттәр базаһы уңышлы бикләнде',
'unlockdbsuccesssub' => 'Мәғлүмәттәр базаһының биге уңышлы алынды',
'lockdbsuccesstext' => 'Мәғлүмәттәр базаһы бикләнде. <br />
Хеҙмәтләндереү тамамланғас та [[Special:UnlockDB|бикте алырға]] онотмағыҙ.',
'unlockdbsuccesstext' => 'Мәғлүмәттәр базаһының биге алынды.',
'lockfilenotwritable' => 'Мәғлүмәттәр базаһын бикләү файлына яҙыуға рөхсәт юҡ.
Мәғлүмәттәр базаһын бикләү һәм бикте алыу өсөн, веб-серверҙың был файлға яҙырға рөхсәте булырға тейеш.',
'databasenotlocked' => 'Мәғлүмәттәр базаһы бикләнмәгән.',
'lockedbyandtime' => '($1 $2 $3)',

# Move page
'move-page' => '$1 — исемен үҙгәртеү',
'move-page-legend' => 'Биттең исемен үҙгәртеү',
'movepagetext' => "Аҫтағы ҡалыпты ҡулланып, биттең исемен үҙгәртә һәм уның үҙгәртеүҙәр журналын яңы урынға күсерә алаһығыҙ.
Биттең элекке исеме яңы биткә йүнәлтеү булып ҡаласаҡ.
Һеҙ элекке исемгә булған йүнәлтеүҙәрҙе автоматик рәүештә яңы исемгә күсерә алаһығыҙ.
Әгәр быны эшләмәһәгеҙ, [[Special:DoubleRedirects|икеле]] һәм [[Special:BrokenRedirects|өҙөлгән йүнәлтеүҙәр]] барлығын тикшерегеҙ.
Һылтанмаларҙың кәрәкле урынға күрһәтеүен дауам итеүе өсөн һеҙ яуаплы.

Иғтибар итегеҙ: әгәр яңы һайланған исемдәге тағы бер бит бар икән, биттең исеме '''үҙгәртелмәйәсәк'''; ул бит йүнәлтеүсе  йәки буш булһа һәм төҙәтеүҙәр тарихына эйә булмаһа ғына,  был мөмкин.
Тимәк, биттең исемен яңылыш үҙгәртһәгеҙ, битте элекке исеменә кире ҡайтара алаһығыҙ, ләкин булған битте юя алмайһығыҙ.

'''Иҫкәртеү!'''
\"Популяр\" биттәрҙең исемен үҙгәртеү күләмле һәм көтөлмәгән һөҙөмтәләргә килтерергә мөмкин.
Дауам итерҙән алда, ихтимал булған һөҙөмтәләрҙе аңлауығыҙға ышанығыҙ.",
'movepagetext-noredirectfixer' => "Аҫтағы форманы ҡулланыу биттең исемен үҙгәртә һәм уның үҙгәртеүҙәр яҙмаһын яңы урынға күсерә.
Биттең элекке исеме яңы биткә йүнәлтеү булып ҡаласаҡ.
Һеҙ элекке исемгә булған йүнәлтеүҙәрҙе автоматик рәүештә яңы исемгә күсерә алаһығыз.
Әгәр быны эшләмәһәгеҙ, [[Special:DoubleRedirects|икеле]] һәм [[Special:BrokenRedirects|өҙөлгән йүнәлтеүҙәрҙе]] тикшерегеҙ.
Һылтанмаларҙың кәрәкле урынға күрһәтеүҙәренең дауам итеүе өсөн һеҙ яуаплы.

Иғтибар итегеҙ, әгәр яңы исемле бит бар икән, биттең исеме '''үҙгәртелмәйәсәк'''; элекке бит йүнәлтеү, буш һәм үҙгәртеү тарихына эйә булмаған осраҡтарҙан башҡа.
Был шуны аңлата: бит исемен яңылыш үҙгәртһәгеҙ, битте кире ҡайтара алаһығыҙ, ләкин булған битте юя алмайһығыҙ.

'''Иғтибар!'''
Популяр биттәрҙең исемен үҙгәртеү көтмәгән һөҙөмтәләргә килтерүе мөмкин.
Дауам итерҙән алда, бөтә буласаҡ һөҙөмтәләрҙе аңлауығыҙҙы уйлағыҙ.",
'movepagetalktext' => "Фекер алышыу битенең исеме лә үҙгәртеләсәк, '''киләһе осраҡтарҙан тыш''':
*Бындай исемле фекер алышыу бите бар, йәки
*Аҫтағы юлды билдәләмәгәнһегеҙ.

Бындай осраҡтарҙа, кәрәкле булһа, биттәрҙе үҙегеҙҙең күсереүегеҙ йәки исемен үҙгәртеүегеҙ кәрәк буласаҡ.",
'movearticle' => 'Биттең исемен үҙгәртергә',
'moveuserpage-warning' => "'''Иғтибар:''' Һеҙ ҡатнашыусы битенең исемен үҙгәртергә йыйынаһығыҙ. Зинһар, биттең генә исеме үҙгәрәсәк, ҡатнашыусы исеме ''үҙгәрмәйәсәк'', икәнен күҙ үңында тотоғоҙ.",
'movenologin' => 'Үҙегеҙҙе танытырға кәрәк',
'movenologintext' => 'Биттең исемен үҙгәртеү өсөн, һеҙ [[Special:UserLogin|танылырға]] тейешһегеҙ.',
'movenotallowed' => 'Һеҙҙең бит исемен үҙгәртергә хоҡуғығыҙ юҡ',
'movenotallowedfile' => 'Һеҙҙең файл исемен үҙгәртергә хоҡуғығыҙ юҡ',
'cant-move-user-page' => 'Һеҙҙең ҡатнашыусы битенең исемен үҙгәртергә хоҡуғығыҙ юҡ',
'cant-move-to-user-page' => 'Һеҙҙең битте ҡатнашыусы бите итеп үҙгәртергә хоҡуғығыҙ юҡ (ҡатнашыусы биттәренән тыш).',
'newtitle' => 'Яңы исем',
'move-watch' => 'Был битте күҙәтеү исемлегенә өҫтәргә',
'movepagebtn' => 'Биттең исемен үҙгәртергә',
'pagemovedsub' => 'Бит исеме үҙгәртелде',
'movepage-moved' => "'''«$1» битенең яңы исеме: «$2»'''",
'movepage-moved-redirect' => 'Йүнәлтеү булдырылды.',
'movepage-moved-noredirect' => 'Йүнәлтеү булдырыу тыйылды.',
'articleexists' => 'Бындай исемле бит бар йәки һеҙ белдергән исем рөхсәт ителмәй.
Зинһар, башҡа исем һайлағыҙ.',
'cantmove-titleprotected' => 'Биттең исемен үҙгәртеү мөмкин түгел, сөнки яңы исем тыйылған исемдәр исемлегенә керә.',
'talkexists' => "'''Бит исеме үҙгәртелде, ләкин фекер алышыу битенең исемен үҙгәртеп булмай, сөнки ундай исемле бит бар инде. Зинһар, уларҙы үҙегеҙ берләштерегеҙ.'''",
'movedto' => 'яңы исеме',
'movetalk' => 'Бәйле фекер алышыу бите исемен үҙгәртергә',
'move-subpages' => 'Биткә кергән биттәрҙе күсереү ($1 битенә тиклем)',
'move-talk-subpages' => 'Фекер алышыу битенә кергән биттәрҙе күсереү ($1 битенә тиклем)',
'movepage-page-exists' => '$1 бите бар инде һәм уның өҫтөнә автоматик рәүештә яҙҙырыу мөмкин түгел.',
'movepage-page-moved' => '$1 битенең исеме $2 тип үҙгәртелде.',
'movepage-page-unmoved' => '$1 битенең исеме $2 тип үҙгәртелә алмай.',
'movepage-max-pages' => '$1 {{PLURAL:$1|биттең}} исеме үҙгәртелде, бынан күберәк биттең исемен автоматик рәүештә үҙгәртеү мөмкин түгел.',
'movelogpage' => 'Исем үҙгәртеү яҙмалары',
'movelogpagetext' => 'Түбәндә — исемдәре үҙгәртелгән биттәр.',
'movesubpage' => '{{PLURAL:$1|кергән бит}}',
'movesubpagetext' => 'Был биткә түбәндә килтерелгән $1 {{PLURAL:$1|бит}} кергән.',
'movenosubpage' => 'Был биткә бер бит тә кермәгән.',
'movereason' => 'Сәбәп:',
'revertmove' => 'кирегә',
'delete_and_move' => 'Юйырға һәм исемен үҙгәртергә',
'delete_and_move_text' => '==Юйыу талап ителә==
[[:$1|«$1»]] исемле бит бар инде. Исем үҙгәртеүҙе дауам итеү өсөн, уны юйырға теләйһегеҙме?',
'delete_and_move_confirm' => 'Эйе, битте юйырға',
'delete_and_move_reason' => 'Исем үҙгәртеүҙе дауам итеү өсөн юйылды «[[$1]]»',
'selfmove' => 'Хәҙерге һәм яңы исемдәр тап килә. Исем үҙгәртеү мөмкин түгел.',
'immobile-source-namespace' => '"$1" исемдәр арауығындағы биттәрҙең исемен үҙгәртеү мөмкин түгел.',
'immobile-target-namespace' => 'Биттәрҙе "$1" исемдәр арауығына күсереү мөмкин түгел.',
'immobile-target-namespace-iw' => 'Интервики һылтанмаһы яңы исем булараҡ ҡулланыла алмай.',
'immobile-source-page' => 'Был биттең исемен үҙгәртеү мөмкин түгел.',
'immobile-target-page' => 'Биткә был исемде биреү мөмкин түгел.',
'bad-target-model' => 'Тап килмәгән мәғлүмәттәр моделе. $1, $2 итеп үҙгәртелмәне.',
'imagenocrossnamespace' => 'Файлға башҡа исемдәр арауығындағы исемде биреү мөмкин түгел.',
'nonfile-cannot-move-to-file' => 'Файл булмаған есемгә файл исемдәре арауығындағы исемде биреү мөмкин түгел.',
'imagetypemismatch' => 'Яңы файл киңәйтеүе уның төрө менән тап килмәй',
'imageinvalidfilename' => 'Файл исеме дөрөҫ түгел',
'fix-double-redirects' => 'Элекке исемгә һылтанған йүнәлтеүҙәрҙе төҙәтергә',
'move-leave-redirect' => 'Йүнәлтеүҙе ҡалдырырға',
'protectedpagemovewarning' => "'''Киҫәтеү: ''' Һеҙ был биттең исемен үҙгәртә алмайһығыҙ, был хоҡуҡҡа хакимдәр генә эйә. 
Белешмә өсөн түбәндә журналдағы һуңғы яҙма килтерелгән:",
'semiprotectedpagemovewarning' => "'''Киҫәтеү: ''' Был бит һаҡланған. Һеҙ уның исемен үҙгәртә алмайһығыҙ, был хоҡуҡҡа хакимдәр генә эйә. 
Белешмә өсөн түбәндә журналдағы һуңғы яҙма килтерелгән:",
'move-over-sharedrepo' => '[[:$1]] файлы дөйөм һаҡлағыста бар. Файлдың исемен бындай исемгә үҙгәртеү дөйөм һаҡлағыстағы файлдың өҫтөнә яҙҙырылыуына килтерәсәк.',
'file-exists-sharedrepo' => 'Һайланған файл исеме дөйөм һаҡлағыста ҡулланыла инде. 
Зинһар, икенсе исем  һайлағыҙ.',

# Export
'export' => 'Биттәрҙе сығарыу',
'exporttext' => 'Һеҙ кәрәкле биттең йәки биттәр йыйынтығының эстәлеген йәки үҙгәртеү тарихын  XML файлына сығара алаһығыҙ. Ул артабан MediaWiki нигеҙендә эшләгән башҡа вики проектҡа [[Special:Import|индерелә ала]].

Биттәрҙе сығарыу өсөн, түбәндәге мөхәррирләү формаһына бер юлға бер бит исеме керетегеҙ һәм биттең хәҙерге өлгөһө менән бергә бөтә элекке өлгөләрҙе һәм үҙгәртеү тарихын сығарырға теләйһегеҙме, әллә хәҙерге өлгөһөн һәм һуңғы үҙгәртеү тураһында мәғлүмәтте сығарырға теләйһегеҙме икәнен һайлағыҙ.

Һуңғы осраҡта һеҙ шулай уҡ һылтанма ҡуллана алаһығыҙ, мәҫәлән, [[{{MediaWiki:Mainpage}}]] бите өсөн [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] һылтанмаһын ҡулланырға мөмкин.',
'exportall' => 'Бөтә биттәрҙе лә экспортлау',
'exportcuronly' => 'Хәҙерге өлгөнө генә индерергә, үҙгәртеүҙәр тарихын индермәҫкә',
'exportnohistory' => "'''Иҫкәрмә:''' биттәрҙең тулы үҙгәртеү тарихын сығарыу етештереүсәнлек ҡыйынлыҡтары арҡаһында һүндерелгән.",
'exportlistauthors' => 'Һәр бер бит өсөн өлөш кереткәндәр исемлеген ҡушырға',
'export-submit' => 'Сығарырға',
'export-addcattext' => 'Ошо категориянан биттәрҙе өҫтәргә:',
'export-addcat' => 'Өҫтәргә',
'export-addnstext' => 'Ошо исемдәр арауығынан биттәрҙе өҫтәргә:',
'export-addns' => 'Өҫтәргә',
'export-download' => 'Файлды һаҡларға',
'export-templates' => 'Ҡалыптарҙы индерергә',
'export-pagelinks' => 'Бәйле биттәрҙе ошо тәрәнлек менән индерергә:',

# Namespace 8 related
'allmessages' => 'Система хәбәрҙәре',
'allmessagesname' => 'Хәбәр',
'allmessagesdefault' => 'Ғәҙәттәге яҙма',
'allmessagescurrent' => 'Хәҙерге яҙма',
'allmessagestext' => 'Түбәндә MediaWiki исемдәр арауығында ҡулланылған система хәбәрҙәре исемлеге килтерелгән.
Әгәр MediaWiki программаһын дөйөм локалләштереү эшенә үҙ өлөшөгөҙҙө керетергә теләһәгеҙ, [//www.mediawiki.org/wiki/Localisation MediaWiki программаһын локалләштереү] битен һәм [//translatewiki.net translatewiki.net] проектын ҡарап сығығыҙ.',
'allmessagesnotsupportedDB' => "Был бит ҡулланыла алмай, сөнки '''\$wgUseDatabaseMessages''' мөмкинлеге һүндерелгән.",
'allmessages-filter-legend' => 'Һайлау',
'allmessages-filter' => 'Үҙгәртеү торошо буйынса һайлау:',
'allmessages-filter-unmodified' => 'Үҙгәртелмәгәндәр',
'allmessages-filter-all' => 'Барыһы',
'allmessages-filter-modified' => 'Үҙгәртелгәндәр',
'allmessages-prefix' => 'Ҡушылмаһы буйынса һайлау:',
'allmessages-language' => 'Тел:',
'allmessages-filter-submit' => 'Күсергә',

# Thumbnails
'thumbnail-more' => 'Ҙурайтырға',
'filemissing' => 'Файл юҡ',
'thumbnail_error' => 'Шартлы рәсем булдырыу хатаһы: $1',
'thumbnail_error_remote' => '$1 хата тураһында хәбәр итә:
$2',
'djvu_page_error' => 'DjVu битенең һаны биттәр һанынан ашҡан',
'djvu_no_xml' => 'DjVu файлы өсөн XML сығарып булмай',
'thumbnail-temp-create' => 'Эскиздың ваҡытлыса файлын яһап булмай',
'thumbnail-dest-create' => 'Маҡсат урында эскизды һаҡлап булмай',
'thumbnail_invalid_params' => 'Шартлы рәсем шарттары дөрөҫ түгел',
'thumbnail_dest_directory' => 'Кәрәкле директорияны булдырып булмай',
'thumbnail_image-type' => 'Был рәсем төрө ҡулланылмай',
'thumbnail_gd-library' => 'GD йыйынтығының төҙөлөшө тулы түгел, $1 функцияһы юҡ',
'thumbnail_image-missing' => '$1 файлы юҡ, булырға тейеш',

# Special:Import
'import' => 'Биттәрҙе тейәү',
'importinterwiki' => 'Вики проекттар-ара индереү',
'import-interwiki-text' => 'Вики проектты һәм тейәлә торған биттең исемен күрһәтегеҙ.
Үҙгәртеү ваҡыттары һәм автор исемдәре һаҡланасаҡ.
Бөтә вики проекттары-ара тейәүҙәр [[Special:Log/import|тейәү яҙмалары журналында]] теркәлә.',
'import-interwiki-source' => 'Сығанаҡ вики проект/бит:',
'import-interwiki-history' => 'Был биттең бөтә үҙгәртеү тарихын яҙҙырырға',
'import-interwiki-templates' => 'Бөтә ҡалыптарҙы индерергә',
'import-interwiki-submit' => 'Тейәргә',
'import-interwiki-namespace' => 'Кәрәкле исемдәр арауығы:',
'import-interwiki-rootpage' => 'Төп бит (мотлаҡ түгел):',
'import-upload-filename' => 'Файл исеме:',
'import-comment' => 'Иҫкәрмә:',
'importtext' => 'Зинһар, файлды сығанаҡ викинан [[Special:Export|махсус ҡорал]] ярҙамында сығарығыҙ. Артабан уны компьютерығыҙға һаҡлағыҙ һәм бында тейәгеҙ.',
'importstart' => 'Биттәрҙе тейәү...',
'import-revision-count' => '$1 {{PLURAL:$1|өлгө|өлгө}}',
'importnopages' => 'Тейәү өсөн биттәр юҡ.',
'imported-log-entries' => 'Журналдан $1 {{PLURAL:$1|яҙма}} тейәлде.',
'importfailed' => 'Тейәү хатаһы: <nowiki>$1</nowiki>',
'importunknownsource' => 'Сығанаҡ биттең төрө билдәһеҙ',
'importcantopen' => 'Тейәлә торған битте асып булмай',
'importbadinterwiki' => 'Интервики һылтанма дөрөҫ түгел',
'importnotext' => 'Буш йәки текст юҡ',
'importsuccess' => 'Тейәү тамамланды!',
'importhistoryconflict' => 'Өлгөләр араһында ҡаршылыҡтар бар (был бит бығаса тейәлгән булыуы мөмкин)',
'importnosources' => 'Вики проекттар-ара тейәү өсөн сығанаҡ биттәр билдәләнмәгән һәм үҙгәртеүҙәр тарихын тура тейәү һүндерелгән.',
'importnofile' => 'Файл тейәлмәгән.',
'importuploaderrorsize' => 'Файл тейәү хатаһы.
Файлдың дәүмәле рөхсәт ителгән дәүмәлдән ҙурыраҡ.',
'importuploaderrorpartial' => 'Файл тейәү хатаһы.
Файл өлөшләтә генә тейәлгән.',
'importuploaderrortemp' => 'Файл тейәү хатаһы.
Ваҡытлы директория юҡ.',
'import-parse-failure' => 'Тейәү ваҡытында XML уҡыу хатаһы',
'import-noarticle' => 'Тейәү өсөн биттәр юҡ!',
'import-nonewrevisions' => 'Бөтә өлгөләр бығаса тейәлгән булған.',
'xml-error-string' => '$2 юлда, $3 урында ($4 байт) $1: $5',
'import-upload' => 'XML-мәғлүмәт тейәргә',
'import-token-mismatch' => 'Сессия мәғлүмәттәре юғалған.
Зинһар, тағы ҡабатлап ҡарағыҙ.',
'import-invalid-interwiki' => 'Күрһәтелгән вики проекттан тейәү мөмкин түгел.',
'import-error-edit' => '«$1» битен импортлап булманы, сөнки һеҙгә ул битте мөхәррирләү тыйылған.',
'import-error-create' => '«$1» битен импортлап булманы, сөнки һеҙгә ул битте яһау тыйылған.',
'import-error-interwiki' => '«$1» бите импортҡа сығарылманы, сөнки уның исеме тышҡы һылианма  (интервики)өсөн резервланған.',
'import-error-special' => ' «$1» бите импортҡа сығарылманы, сөнки ул биттәр яһау мөмкин булмаған исемдәр арауығына ҡарай.',
'import-error-invalid' => '"$1" бите яраҡһыҙ исеме өсөн импортланманы.',
'import-error-unserialize' => '«$1» битенең $2 өлгөһөн структуралаштырып (десериаялап) булмай. $4 форматында серияланған $3 эстәлегенең моделеның был өлгөлә ҡулланылыуы тураһында хәбәр алынды.',
'import-options-wrong' => 'Хаталы {{PLURAL:$2|опция|опциялар}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Төп биттең күрһәтелгән исеме яңылыш.',
'import-rootpage-nosubpage' => 'Төп биттең "$1" исемдәр арауығы эске биттәргә рөхсәт бирмәй.',

# Import log
'importlogpage' => 'Тейәү яҙмалары журналы',
'importlogpagetext' => 'Хакимдәр тарафынан башҡа вики проекттарҙан биттәрҙе һәм уларҙың үҙгәртеүҙәр тарихын тейәү.',
'import-logentry-upload' => '[[$1]] битен файлдан тейәгән',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|өлгө|өлгө}}',
'import-logentry-interwiki' => '$1 битен вики проекттары-ара тейәгән',
'import-logentry-interwiki-detail' => '$2 өлгөнән $1 {{PLURAL:$1|өлгө|өлгө}}',

# JavaScriptTest
'javascripttest' => '
JavaScript тикшереү',
'javascripttest-title' => '$1 тикшеренеү үткәрелә',
'javascripttest-pagetext-noframework' => 'Был бит JavaScript тикшеренеүҙәре үткәреү өсөн  резервланған.',
'javascripttest-pagetext-unknownframework' => 'Билдәһеҙ тикшеренеүҙәр мөхитнамәһе "$1".',
'javascripttest-pagetext-frameworks' => 'Зинһар өсөн киләһе тикшеренеүҙәр мөхитнамәһенең береһен һайлап алығыҙ: $1',
'javascripttest-pagetext-skins' => 'Һынауҙы башлау өсөн тышса һыйлағыҙ.',
'javascripttest-qunit-intro' => 'mediawiki.org адресы буйынса ҡарағыҙ [$1 тест үткәреү документацияһы].',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit өсөн һынауҙар йыйлмаһы.',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Ҡулланыусы битегеҙ',
'tooltip-pt-anonuserpage' => 'IP адресығыҙ өсөн ҡатнашыусы бите',
'tooltip-pt-mytalk' => 'Фекерләшеү битегеҙ',
'tooltip-pt-anontalk' => 'IP адресығыҙ өсөн фекер алышыу бите',
'tooltip-pt-preferences' => 'Көйләүҙәрегеҙ',
'tooltip-pt-watchlist' => 'Һеҙ күҙәткән биттәр исемлеге',
'tooltip-pt-mycontris' => 'Кереткән өлөшөгөҙ',
'tooltip-pt-login' => 'Бында теркәлеү үтергә була, әммә был эш мәжбүри түгел.',
'tooltip-pt-anonlogin' => 'Бында танылыу үтергә була, әммә был эш мәжбүри түгел.',
'tooltip-pt-logout' => 'Сығырға',
'tooltip-ca-talk' => 'Биттең эстәлеге тураһында фекерләшеү',
'tooltip-ca-edit' => 'Һеҙ был битте үҙгәртә алаһығыҙ. Зинһар, яҙып ҡуйыр алдынан ҡарап сығығыҙ',
'tooltip-ca-addsection' => 'Яңы бүлек эшләргә',
'tooltip-ca-viewsource' => 'Был бит үҙгәртеүҙән һаҡланған.
Тик сығанаҡ текстын ғына ҡарай һәм күсереп ала алаһығыҙ.',
'tooltip-ca-history' => 'Биттең төҙәтеүҙәр исемлеге',
'tooltip-ca-protect' => 'Был битте һаҡларға',
'tooltip-ca-unprotect' => 'Был биттең һаҡлауын үҙгәртергә',
'tooltip-ca-delete' => 'Был битте юйырға',
'tooltip-ca-undelete' => 'Юйыр алдынан биттә башҡарылған үҙгәртеүҙәрҙе тергеҙергә',
'tooltip-ca-move' => 'Битте күсерергә',
'tooltip-ca-watch' => 'Был битте күҙәтеү исемлегенә өҫтәргә',
'tooltip-ca-unwatch' => 'Был битте күҙәтеү исемлегемдән сығарырға',
'tooltip-search' => 'Эҙләргә {{SITENAME}}',
'tooltip-search-go' => 'Нәҡ ошондай исеме булған биткә күсергә',
'tooltip-search-fulltext' => 'Ошондай эстәлекле биттәрҙе табырға',
'tooltip-p-logo' => 'Баш биткә күсергә',
'tooltip-n-mainpage' => 'Баш биткә күсергә',
'tooltip-n-mainpage-description' => 'Баш биткә күсеү',
'tooltip-n-portal' => 'Проект буйынса һеҙ нимә эшләй алаһығыҙ һәм ҡайҙа нимә барлығы тураһында белешмә',
'tooltip-n-currentevents' => 'Ағымдағы ваҡиғалар исемлеге',
'tooltip-n-recentchanges' => 'Һуңғы үҙгәртеүҙәр исемлеге',
'tooltip-n-randompage' => 'Осраҡлы битте ҡарарға',
'tooltip-n-help' => '«{{SITENAME}}» буйынса белешмә',
'tooltip-t-whatlinkshere' => 'Был биткә һылтанған барлык биттәрҙең исемлеге',
'tooltip-t-recentchangeslinked' => 'Был биттән һылтанған биттәрҙә һуңғы үҙгәртеүҙәр',
'tooltip-feed-rss' => 'Был бит өсөн RSS-таҫма',
'tooltip-feed-atom' => 'Был бит өсөн Atom-таҫма',
'tooltip-t-contributions' => 'Был ҡулланыусының кереткән өлөшөн ҡарарға',
'tooltip-t-emailuser' => 'Был ҡулланыусыға хат ебәрергә',
'tooltip-t-upload' => 'Рәсем йәки тауыш эстәлекле файлдарҙы тейәргә',
'tooltip-t-specialpages' => 'Барлыҡ махсус биттәр исемлеге',
'tooltip-t-print' => 'Был биттең ҡағыҙға баҫыу өлгөһө',
'tooltip-t-permalink' => 'Биттең был өлгөһөнә даими һылтанма',
'tooltip-ca-nstab-main' => 'Мәҡәләнең эстәлеге',
'tooltip-ca-nstab-user' => 'Ҡулланыусының шәхси бите',
'tooltip-ca-nstab-media' => 'Медиа-файл бите',
'tooltip-ca-nstab-special' => 'Был махсус бит, уны үҙгәртеп булмай',
'tooltip-ca-nstab-project' => 'Проект битен күрһәт',
'tooltip-ca-nstab-image' => 'Файл бите',
'tooltip-ca-nstab-mediawiki' => 'Система хәбәре бите',
'tooltip-ca-nstab-template' => 'Ҡалып бите',
'tooltip-ca-nstab-help' => 'Ярҙам бите',
'tooltip-ca-nstab-category' => 'Категория бите',
'tooltip-minoredit' => 'Текст әҙ үҙгәртелгән тип билдәләргә',
'tooltip-save' => 'Үҙгәртеүҙәрҙе һаҡларға',
'tooltip-preview' => 'Алдан байҡау, һаҡлауҙан алда уны ҡулланып үҙгәртеүҙәрегеҙҙе ҡарап сығығыҙ!',
'tooltip-diff' => 'Сығанаҡ текстҡа ҡарата эшләгән үҙгәртеүҙәрҙе күрһәтергә.',
'tooltip-compareselectedversions' => 'Был биттең һайланған ике өлгөһө араһындағы айырманы ҡарарға',
'tooltip-watch' => 'Битте күҙәтеү исемлегемә өҫтәргә',
'tooltip-watchlistedit-normal-submit' => 'Биттәрҙе юйырға',
'tooltip-watchlistedit-raw-submit' => 'Күҙәтеү исемлеген яңыртырға',
'tooltip-recreate' => 'Битте юйылған булыуына ҡарамаҫтан тергеҙергә',
'tooltip-upload' => 'Күсерә башларға',
'tooltip-rollback' => 'Бер баҫыу менән аҙаҡҡы мөхәррирләүсенең үҙгәртеүҙәрен кире ала.',
'tooltip-undo' => '"Кире ал" төҙәтеүҙе кире ала һәм төҙәтеү формаһын "алдан байҡау"ҙа күрһәтә. Һәм кире алыуҙың сәбәбен белдерергә була.',
'tooltip-preferences-save' => 'Көйләүҙәрҙе һаҡларға',
'tooltip-summary' => 'Ҡыҫҡаса тасуирлама керетегеҙ',

# Metadata
'notacceptable' => 'Вики-сервер мәғлүмәтте һеҙҙең браузер уҡый алырлыҡ форматта ҡайтара алмай.<br />
The wiki server cannot provide data in a format your client can read.',

# Attribution
'anonymous' => '{{SITENAME}} проектының танылмаған {{PLURAL:$1|ҡатнашыусыһы|ҡатнашыусылары}}',
'siteuser' => '{{SITENAME}} проектының ҡатнашыусыһы $1',
'anonuser' => '{{SITENAME}} проектының танылмаған ҡатнашыусыһы $1',
'lastmodifiedatby' => 'Был бит һуңғы тапҡыр $1 $2 $3 тарафынан үҙгәртелгән.',
'othercontribs' => 'Мөхәррирләүҙә ҡатнаштылар: $1.',
'others' => 'башҡалар',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|ҡатнашыусы|ҡатнашыусылары}} $1',
'anonusers' => '{{SITENAME}} проектының танылмаған {{PLURAL:$2|ҡатнашыусыһы|ҡатнашыусылары}} $1',
'creditspage' => 'Рәхмәт белдереү',
'nocredits' => 'Был мәҡәләне мөхәррирләүҙә ҡатнашыусылар исемлеге юҡ.',

# Spam protection
'spamprotectiontitle' => 'Спамдан һаҡлау',
'spamprotectiontext' => 'Һеҙ һаҡларға теләгән бит спамдан һаҡлау ҡоралы тарафынан бикләнгән.
Ҡара исемлеккә керетелгән тышҡы сайтҡа һылтанма быға сәбәпсе булыуы мөмкин.',
'spamprotectionmatch' => 'Түбәндәге һылтанма спамдан һаҡлау ҡоралының эшләүенә килтерҙе: $1',
'spambot_username' => 'Спамдан таҙартыусы',
'spam_reverting' => '$1 һылтанмаһыҙ һуңғы өлгөгә ҡайтарыу',
'spam_blanking' => 'Бөтә өлгөләрҙә лә $1 һылтанмаһы бар, таҙартыу',
'spam_deleting' => 'Бөтә өлгөләрҙә лә $1 һылтанма бар, таҙартыу бара',

# Info page
'pageinfo-title' => '«$1» буйынса мәғлүмәт',
'pageinfo-not-current' => 'Ғәфү итегеҙ, был мәғлүмәтте иҫке версиялар өсөн күрһәтеп булмай.',
'pageinfo-header-basic' => 'Төп мәғлүмәт',
'pageinfo-header-edits' => 'Үҙгәртеүҙәр тарихы',
'pageinfo-header-restrictions' => 'Бите һаҡлау',
'pageinfo-header-properties' => 'Биттең үҙенсәлектәре',
'pageinfo-display-title' => 'Күренгән исем',
'pageinfo-default-sort' => 'Ғәҙәттәге сортлау асҡысы',
'pageinfo-length' => 'Бит оҙонлоғо (байттарҙа)',
'pageinfo-article-id' => 'Бит идентификаторы',
'pageinfo-language' => 'Бит эстәлегенең теле',
'pageinfo-robot-policy' => 'Эҙләү роботтары тарафынан индексацияланыу',
'pageinfo-robot-index' => 'Рөхсәт ителгән',
'pageinfo-robot-noindex' => 'Рөхсәт ителмәй',
'pageinfo-views' => 'Ҡарау һаны',
'pageinfo-watchers' => 'Битте күҙәтеүселәр һаны',
'pageinfo-few-watchers' => 'Күп тигәндә $1 {{PLURAL:$1|күҙәтеүсе}}',
'pageinfo-redirects-name' => 'Был биткә йүнәлтеүҙәр һаны',
'pageinfo-subpages-name' => 'Был биттең эске биттәре',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|йүнәлтеү}}; $3 {{PLURAL:$3|ябай}})',
'pageinfo-firstuser' => 'Битте яһаусы',
'pageinfo-firsttime' => 'Битте яһау датаһы',
'pageinfo-lastuser' => 'Һуңғы мөхәррирләүсе',
'pageinfo-lasttime' => 'Һуңғы мөхәррирләү датаһы',
'pageinfo-edits' => 'Дөйөм төҙәтеү һаны',
'pageinfo-authors' => 'Төрлө авторҙар һаны',
'pageinfo-recent-edits' => 'Һуңғы ваҡыттағы төҙәтеүҙәр ($1 эсендә)',
'pageinfo-recent-authors' => 'Төрлө авторҙарҙың һуңғы һаны',
'pageinfo-magic-words' => 'Тылсымлы {{PLURAL:$1|һүҙ|һүҙҙәр}} ($1)',
'pageinfo-hidden-categories' => 'Йәшерен {{PLURAL:$1|категория|категориялар}} ($1)',
'pageinfo-templates' => 'Ҡулланылған {{PLURAL:$1|ҡалып|ҡалыптар}} ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Индерелгән биттәр}} ($1)',
'pageinfo-toolboxlink' => 'Бит мәғлүмәттәре',
'pageinfo-redirectsto' => 'Йүнәлтеү',
'pageinfo-redirectsto-info' => 'мәғлүмәт',
'pageinfo-contentpage' => 'Эстәлек бите тип иҫәпләнә',
'pageinfo-contentpage-yes' => 'Эйе',
'pageinfo-protect-cascading' => 'Бынан башлап һикәлтәле һаҡлау',
'pageinfo-protect-cascading-yes' => 'Эйе',
'pageinfo-protect-cascading-from' => 'Бынан башлап һикәлтәле һаҡлау',
'pageinfo-category-info' => 'Категория тураһында мәғлүмәт',
'pageinfo-category-pages' => 'Биттәр һаны',
'pageinfo-category-subcats' => 'Категория бүлемдәре һаны',
'pageinfo-category-files' => 'Файлдар һаны',

# Skin names
'skinname-cologneblue' => 'Кёльн һағышы',
'skinname-modern' => 'Заманса',
'skinname-vector' => 'Векторлы',

# Patrolling
'markaspatrolleddiff' => 'Тикшерелгән, тип билдәләргә',
'markaspatrolledtext' => 'Бил битте тикшерелгән, тип билдәләргә',
'markedaspatrolled' => 'Тикшерелгән тип билдәнгән',
'markedaspatrolledtext' => '[[:$1]] битенең һайланған өлгөһө тикшерелгән тип билдәләнгән.',
'rcpatroldisabled' => 'Һуңғы үҙгәртеүҙәрҙе тикшереү рөхсәт ителмәй',
'rcpatroldisabledtext' => 'Һуңғы үҙгәртеүҙәрҙе тикшереү мөмкинлеге әлеге ваҡытта һүндерелгән.',
'markedaspatrollederror' => 'Тикшерелгән тип билдәләп булмай',
'markedaspatrollederrortext' => 'Һеҙ тикшерелгән тип билдәләнәсәк биттең өлгөһөн күрһәтергә тейешһегеҙ.',
'markedaspatrollederror-noautopatrol' => 'Һеҙгә үҙегеҙҙең үҙгәртеүҙәрегеҙҙе тикшерелгән тип билдәләргә рөхсәт ителмәй.',
'markedaspatrollednotify' => '$1 битендәге үҙгәртеү патрулләнгән тип билдәләнде.',
'markedaspatrollederrornotify' => 'Патрулләнгән тип билдәләү уңышһыҙ тамамланды.',

# Patrol log
'patrol-log-page' => 'Тикшереү яҙмалары журналы',
'patrol-log-header' => 'Был — тикшерелгән өлгөләр яҙмалары журналы.',
'log-show-hide-patrol' => 'тикшереү яҙмалары журналын $1',

# Image deletion
'deletedrevision' => 'Иҫке $1 өлгөһө юйылды',
'filedeleteerror-short' => 'Файлды юйыу хатаһы: $1',
'filedeleteerror-long' => 'Файлды юйыу ваҡытында хаталар килеп сыҡты:

$1',
'filedelete-missing' => '"$1" файлын юйып булмай, сөнки ул юҡ.',
'filedelete-old-unregistered' => 'Күрһәтелгән "$1" файл өлгөһө мәғлүмәттәр базаһында юҡ.',
'filedelete-current-unregistered' => 'Күрһәтелгән "$1" файлы мәғлүмәттәр базаһында юҡ.',
'filedelete-archive-read-only' => '"$1" архив директорияһына веб-сервер яҙҙыра алмай.',

# Browsing diffs
'previousdiff' => '← Алдағы төҙәтеү',
'nextdiff' => 'Киләһе үҙгәртеү →',

# Media information
'mediawarning' => "'''Иғтибар''': был төр файлда зыян килтереүсе программа коды булыуы мөмкин.
Уны башҡарған саҡта һеҙҙең системағыҙға хәүеф янауы мөмкин.",
'imagemaxsize' => "Рәсем дәүмәле өсөн сик: <br />''(файл тасуирламаһы биттәре өсөн)''",
'thumbsize' => 'Шартлы рәсем дәүмәле:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|бит}}',
'file-info' => 'файлдың дәүмәле: $1, MIME төрө: $2',
'file-info-size' => '$1 × $2 нөктә, файлдың дәүмәле: $3, MIME төрө: $4',
'file-info-size-pages' => '$1 × $2 пиксель, файл күләме: $3, MIME төр: $4, $5 {{PLURAL:$5|бит}}',
'file-nohires' => 'Юғары асыҡлыҡтағы өлгө юҡ.',
'svg-long-desc' => 'SVG файлы, номиналь $1 × $2 нөктә, файлдың дәүмәле: $3',
'svg-long-desc-animated' => 'Анимациялы SVG файлы, номиналь $1 × $2 нөктә, файлдың дәүмәле: $3',
'svg-long-error' => 'Яңылыш SVG файл: $1',
'show-big-image' => 'Тулы асыҡлыҡ',
'show-big-image-preview' => 'Байҡау ваҡытындағы күләм: $1.',
'show-big-image-other' => '{{PLURAL:$2|Башҡа сиселеш|Башҡа сиселештәр}}: $1.',
'show-big-image-size' => '$1 × $2 пиксель',
'file-info-gif-looped' => 'әйләнешле',
'file-info-gif-frames' => '$1 {{PLURAL:$1|фрейм}}',
'file-info-png-looped' => 'әйләнешле',
'file-info-png-repeat' => '$1 {{PLURAL:$1|тапҡыр}} уйнала',
'file-info-png-frames' => '$1 {{PLURAL:$1|фрейм}}',
'file-no-thumb-animation' => "
'''Иғтибар: Техник сикләүҙәр арҡаһында, был файлдың бәләкәй рәсемдәре анимацияланмаясаҡ.'''",
'file-no-thumb-animation-gif' => "'''Иғтибар: Техник сикләүҙәр арҡаһында, бының һымаҡ юғары асыҡлыҡтағы GIF рәсемдәрҙең бәләкәй рәсемдәре анимацияланмаясаҡ.'''",

# Special:NewFiles
'newimages' => 'Яңы файлдар йыйылмаһы',
'imagelisttext' => "Түбәндә — '''$1''' {{PLURAL:$1|файлдан}} торған һәм $2 тәртипкә килтерелгән исемлек.",
'newimages-summary' => 'Был махсус бит һуңғы тейәлгән файлдарҙы күрһәтә.',
'newimages-legend' => 'Һайлау',
'newimages-label' => 'Файл исеме (йәки өлөшө):',
'showhidebots' => '(боттарҙы $1)',
'noimages' => 'Рәсемдәр юҡ.',
'ilsubmit' => 'Эҙләү',
'bydate' => 'булдырыу көнө буйынса',
'sp-newimages-showfrom' => '$1 $2 ваҡытынан башлап яңы файлдарҙы күрһәтергә',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 секунд|$1 секунд}}',
'minutes' => '{{PLURAL:$1|$1 минут|$1 минут}}',
'hours' => '{{PLURAL:$1|$1 сәғәт|$1 сәғәт}}',
'days' => '{{PLURAL:$1|$1 көн|$1 көн}}',
'weeks' => '{{PLURAL:$1|$1 аҙна|$1 аҙна|}}',
'months' => '{{PLURAL:$1|$1 ай}}',
'years' => '{{PLURAL:$1|$1 йыл}}',
'ago' => '$1 элек',
'just-now' => 'яңы ғына',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|сәғәт}} элек',
'minutes-ago' => '$1 {{PLURAL:$1|минут}} элек',
'seconds-ago' => '$1 {{PLURAL:$1|секунд}} элек',
'monday-at' => 'дүшәмбе $1',
'tuesday-at' => 'шишәмбе $1',
'wednesday-at' => 'шаршамбы $1',
'thursday-at' => 'кесе йома $1',
'friday-at' => 'йома $1',
'saturday-at' => 'шәмбе $1',
'sunday-at' => 'йәкшәмбе $1',
'yesterday-at' => 'Кисә $1',

# Bad image list
'bad_image_list' => 'Формат киләһе рәүештә булырға тейеш:

Тик исемлек элементтары ғына һаналасаҡ (* символы менән башланған юлдар).
Юлдың беренсе һылтанмаһы, ҡуйылырға рөхсәт ителмәгән рәсемгә һылтанма булырға тейеш.
Шул уҡ юлдағы башҡа һылтанмалар ҡағиҙәнән тыш, йәғни рәсем ҡуйырға рөхсәт ителгән бит тип һаналасаҡтар.',

# Metadata
'metadata' => 'Мета мәғлүмәттәр',
'metadata-help' => 'Файл, ғәҙәттә һанлы камералар йәки сканерҙар өҫтәгән мәғлүмәттәргә эйә. Әгәр файл яһалғандан һуң төҙәтелгән булһа, ҡайһы бер параметрҙар ағымдағы рәсем менән тап килмәҫкә мөмкин.',
'metadata-expand' => 'Өҫтәмә мәғлүмәттәрҙе күрһәт',
'metadata-collapse' => 'Өҫтәмә мәғлүмәттәрҙе йәшер',
'metadata-fields' => 'Был исемлектә һанап кителгән мета мәғлүмәт юлдары рәсем битендә күрһәтеләсәктәр, ҡалғандары иһә төрөлгән буласаҡ.
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

# Exif tags
'exif-imagewidth' => 'Киңлек',
'exif-imagelength' => 'Бейеклек',
'exif-bitspersample' => 'Төҫтәрҙең тәрәнлеге',
'exif-compression' => 'Ҡыҫыу ысулы',
'exif-photometricinterpretation' => 'Төҫтәр төҙөлөшө',
'exif-orientation' => 'Йүнәлеше',
'exif-samplesperpixel' => 'Төҫ өлөштәренең һаны',
'exif-planarconfiguration' => 'Мәғлүмәттең ойошторолоуы ҡағиҙәһе',
'exif-ycbcrsubsampling' => 'Y һәм C өлөштәренең нисбәте',
'exif-ycbcrpositioning' => 'Y һәм C өлөштәренең тәртибе',
'exif-xresolution' => 'X күсәре буйынса асыҡлыҡ',
'exif-yresolution' => 'Y күсәре буйынса асыҡлыҡ',
'exif-stripoffsets' => 'Рәсем мәғлүмәте урынлашыуы',
'exif-rowsperstrip' => 'Бер бүлектә юлдар һаны',
'exif-stripbytecounts' => 'Ҡыҫылған бүлектә байттар һаны',
'exif-jpeginterchangeformat' => 'JPEG SOI бүлегенең башланған урыны',
'exif-jpeginterchangeformatlength' => 'JPEG мәғлүмәте дәүмәле, байттарҙа',
'exif-whitepoint' => 'Аҡ нөктәнең төҫлөлөгө',
'exif-primarychromaticities' => 'Төп төҫтәрҙең төҫлөлөгө',
'exif-ycbcrcoefficients' => 'Төҫтәр киңлеген үҙгәртеү коэффициенты',
'exif-referenceblackwhite' => 'Аҡ һәм ҡара төҫтәрҙең урынлашыуы',
'exif-datetime' => 'Файлды үҙгәртеү көнө һәм ваҡыты',
'exif-imagedescription' => 'Рәсемдең исеме',
'exif-make' => 'Камераны етештереүсе',
'exif-model' => 'Камераның төрө',
'exif-software' => 'Ҡулланылған программа',
'exif-artist' => 'Автор',
'exif-copyright' => 'Авторлыҡ хоҡуғы эйәһе',
'exif-exifversion' => 'Exif өлгөһө',
'exif-flashpixversion' => 'Ҡулланылған Flashpix өлгөһө',
'exif-colorspace' => 'Төҫтәр киңлеге',
'exif-componentsconfiguration' => 'Төҫ өлөштәренең төҙөлөшө',
'exif-compressedbitsperpixel' => 'Рәсемде ҡыҫыу ысулы',
'exif-pixelydimension' => 'Рәсем киңлеге',
'exif-pixelxdimension' => 'Рәсем бейеклеге',
'exif-usercomment' => 'Ҡулланыусы иҫкәрмәһе',
'exif-relatedsoundfile' => 'Бәйле аудио файл',
'exif-datetimeoriginal' => 'Төп көнө һәм ваҡыты',
'exif-datetimedigitized' => 'Һанлаштырыу көнө һәм ваҡыты',
'exif-subsectime' => 'Файл үҙгәреү ваҡытының секунд бүлкәттәре',
'exif-subsectimeoriginal' => 'Төп ваҡыттың секунд бүлкәттәре',
'exif-subsectimedigitized' => 'Һанлаштырыу ваҡытының секунд бүлкәттәре',
'exif-exposuretime' => 'Экспозиция ваҡыты',
'exif-exposuretime-format' => '$1 сек. ($2)',
'exif-fnumber' => 'Диафрагма һаны',
'exif-exposureprogram' => 'Экспозиция режимы',
'exif-spectralsensitivity' => 'Спектраль һиҙгерлек',
'exif-isospeedratings' => 'ISO буйынса яҡтыға һиҙгерлек',
'exif-shutterspeedvalue' => 'APEX затвор тиҙлеге',
'exif-aperturevalue' => 'APEX диафрагма',
'exif-brightnessvalue' => 'APEX баҙыҡлыҡ',
'exif-exposurebiasvalue' => 'Экспозиция компенсацияһы',
'exif-maxaperturevalue' => 'Иң ҙур диафрагма һаны',
'exif-subjectdistance' => 'Есемдең йыраҡлығы',
'exif-meteringmode' => 'Экспозиция үлсәү ысулы',
'exif-lightsource' => 'Яҡтылыҡ сығанағы',
'exif-flash' => 'Балҡыш (вспышка)',
'exif-focallength' => 'Фокус аралығы',
'exif-subjectarea' => 'Есемдең урынлашыу майҙаны',
'exif-flashenergy' => 'Балҡыш (вспышка) ҡеүәте',
'exif-focalplanexresolution' => 'Фокус яҫылығының Х күсәре буйынса асыҡлығы',
'exif-focalplaneyresolution' => 'Фокус яҫылығының Y күсәре буйынса асыҡлығы',
'exif-focalplaneresolutionunit' => 'Фокус яҫылығы асыҡлығының үлсәү берәмеге',
'exif-subjectlocation' => 'Есемдең урынлашыуы',
'exif-exposureindex' => 'Экспозиция индексы',
'exif-sensingmethod' => 'Сенсор төрө',
'exif-filesource' => 'Файл сығанағы',
'exif-scenetype' => 'Сәхнә төрө',
'exif-customrendered' => 'Рәсемде өҫтәмә эшкәртеү',
'exif-exposuremode' => 'Экспозиция төрө',
'exif-whitebalance' => 'Аҡ төҫ тигеҙләнеше',
'exif-digitalzoomratio' => 'Һанлы ҙурайтыу (зум) нисбәте',
'exif-focallengthin35mmfilm' => '35 мм. таҫмала фокус йыраҡлығы',
'exif-scenecapturetype' => 'Төшөргәндә сәхнә төрө',
'exif-gaincontrol' => 'Сәхнәне көйләү',
'exif-contrast' => 'Ҡаршылыҡ (контраст)',
'exif-saturation' => 'Ҡуйылыҡ',
'exif-sharpness' => 'Киҫкенлек',
'exif-devicesettingdescription' => 'Ҡоролманың көйләүҙәре тасуирламаһы',
'exif-subjectdistancerange' => 'Есемгә тиклем ара',
'exif-imageuniqueid' => 'Рәсемдең билдәһе (ID)',
'exif-gpsversionid' => 'GPS бүлеге өлгөһө',
'exif-gpslatituderef' => 'Киңлек индексы',
'exif-gpslatitude' => 'Киңлек',
'exif-gpslongituderef' => 'Оҙонлоҡ индексы',
'exif-gpslongitude' => 'Оҙонлоҡ',
'exif-gpsaltituderef' => 'Бейеклек индексы',
'exif-gpsaltitude' => 'Бейеклек',
'exif-gpstimestamp' => 'GPS ваҡыты (UTC буйынса)',
'exif-gpssatellites' => 'Ҡулланылған юлдаштар',
'exif-gpsstatus' => 'Мәғлүмәт алғыс торошо',
'exif-gpsmeasuremode' => 'Үлсәү ысулы',
'exif-gpsdop' => 'Үлсәү дөрөҫлөгө',
'exif-gpsspeedref' => 'Тиҙлек берәмеге',
'exif-gpsspeed' => 'GPS мәғлүмәт алғысының тиҙлеге',
'exif-gpstrackref' => 'Хәрәкәт йүнәлешен билдәләү ысулы',
'exif-gpstrack' => 'Хәрәкәт йүнәлеше',
'exif-gpsimgdirectionref' => 'Рәсем йүнәлешен билдәләү ысулы',
'exif-gpsimgdirection' => 'Рәсем йүнәлеше',
'exif-gpsmapdatum' => 'Ҡулланылған геодезик координаталар системаһы',
'exif-gpsdestlatituderef' => 'Есемдең оҙонлоҡ индексы',
'exif-gpsdestlatitude' => 'Есемдең оҙонлоғо',
'exif-gpsdestlongituderef' => 'Есемдең киңлек индексы',
'exif-gpsdestlongitude' => 'Есемдең киңлеге',
'exif-gpsdestbearingref' => 'Есемдең пеленгын билдәләү ысулы',
'exif-gpsdestbearing' => 'Есемдең пеленгы',
'exif-gpsdestdistanceref' => 'Есемдең йыраҡлығын билдәләү ысулы',
'exif-gpsdestdistance' => 'Есемдең йыраҡлығы',
'exif-gpsprocessingmethod' => 'GPS исемен билдәләү ысулы',
'exif-gpsareainformation' => 'GPS өлкәһенең исеме',
'exif-gpsdatestamp' => 'GPS ваҡыты',
'exif-gpsdifferential' => 'GPS мәғлүмәтте дифференциаль төҙәтеү',
'exif-jpegfilecomment' => 'JPEG файл өсөн иҫкәрмә',
'exif-keywords' => 'Асҡыс һүҙҙәр',
'exif-worldregioncreated' => 'Фотография эшләнгән донъя регионы',
'exif-countrycreated' => 'Фотография эшләнгән ил',
'exif-countrycodecreated' => 'Фотография эшләнгән ил коды',
'exif-provinceorstatecreated' => 'Фотография эшләнгән өлкә, провинция йәки штат',
'exif-citycreated' => 'Фотография эшләнгән ҡала',
'exif-sublocationcreated' => 'Фотография эшләнгән ҡала районы',
'exif-worldregiondest' => 'Донъяның күрһәтелгән регионы',
'exif-countrydest' => 'Күрһәтелгән ил',
'exif-countrycodedest' => 'Күрһәтелгән ил коды',
'exif-provinceorstatedest' => 'Күрһәтелгән өлкә, провинция йәки штат',
'exif-citydest' => 'Күрһәтелгән ҡала',
'exif-sublocationdest' => 'Ҡаланың күрһәтелгән районы',
'exif-objectname' => 'Ҡыҫҡа исем',
'exif-specialinstructions' => 'Махсус күрһәтмәләр',
'exif-headline' => 'Исем',
'exif-credit' => 'Һүрәтте тәьмин итеүсе',
'exif-source' => 'Сығанаҡ',
'exif-editstatus' => 'Рәсемдең мөхәррирләү торошо',
'exif-urgency' => 'Ашығыслыҡ',
'exif-fixtureidentifier' => 'Бағана исеме',
'exif-locationdest' => 'Күрһәтелгән урын',
'exif-locationdestcode' => 'Күрһәтелгән урын коды',
'exif-objectcycle' => 'Рәсем өсөн тәғәйенләнгән тәүлек ваҡыты',
'exif-contact' => 'Бәйләнеш мәғлүмәттәре',
'exif-writer' => 'Автор',
'exif-languagecode' => 'Тел',
'exif-iimversion' => 'IIM версияһы',
'exif-iimcategory' => 'Категория',
'exif-iimsupplementalcategory' => 'Өҫтәмә категориялар',
'exif-datetimeexpires' => 'Ошонан һуң ҡулланмаҫҡа:',
'exif-datetimereleased' => 'Сығарылыу ваҡыты',
'exif-originaltransmissionref' => 'Сығанаҡ ебәреү урыны коды',
'exif-identifier' => 'Идентификатор',
'exif-lens' => 'Ҡулланылған объектив',
'exif-serialnumber' => 'Камераның серия номеры',
'exif-cameraownername' => 'Камера эйәһе',
'exif-label' => 'Билдәләү',
'exif-datetimemetadata' => 'Метамәғлүмәттәрҙе һуңғы үҙгәртеү ваҡыты',
'exif-nickname' => 'Рәсемдең формаль булмаған исеме',
'exif-rating' => 'Баһа (5-тән)',
'exif-rightscertificate' => 'Хоҡуҡтарҙы идаралау сертфикаты',
'exif-copyrighted' => 'Авторлыҡ хоҡуғы торошо',
'exif-copyrightowner' => 'Авторлыҡ хоҡуғы эйәһе',
'exif-usageterms' => 'Ҡулланыу шарттары',
'exif-webstatement' => 'Интернеттағы авторлыҡ хоҡуҡтары тураһындағы белдереү',
'exif-originaldocumentid' => 'Сығанаҡ документтың уникаль идентификаторы',
'exif-licenseurl' => 'Авторлыҡ рөхсәтнәмәһенең URL',
'exif-morepermissionsurl' => 'Альтернатив рөхсәтнәмә мәғлүмәттәре',
'exif-attributionurl' => 'Был эште ҡулланғанда, зинһар, ошонда һылтанма яһағыҙ',
'exif-preferredattributionname' => 'Был эште ҡулланғанда, зинһар, ошоларҙы белдерегеҙ',
'exif-pngfilecomment' => 'PNG файл өсөн иҫкәрмә',
'exif-disclaimer' => 'Яуаплылыҡтан баш тартыу',
'exif-contentwarning' => 'Эстәлек тураһында киҫәтеү',
'exif-giffilecomment' => 'GIF файл өсөн иҫкәрмә',
'exif-intellectualgenre' => 'Объект төрө',
'exif-subjectnewscode' => 'Тема коды',
'exif-scenecode' => 'IPTC сцена коды',
'exif-event' => 'Һүрәтләнгән ваҡиға',
'exif-organisationinimage' => 'Һүрәтләнгән организация',
'exif-personinimage' => 'Һүрәтләнгән кеше',
'exif-originalimageheight' => 'Кадрлауға тиклемге рәсем бейеклеге',
'exif-originalimagewidth' => 'Кадрлауға тиклемге рәсем киңлеге',

# Exif attributes
'exif-compression-1' => 'Ҡыҫылмаған',
'exif-compression-2' => 'CCITT Group 3, Хаффман сериялары оҙонлоҡтарын кодлауҙың 1 үлсәмле модификацияһы',
'exif-compression-3' => 'CCITT Group 3, факслы кодлау',
'exif-compression-4' => 'CCITT Group 4, факслы кодлау',

'exif-copyrighted-true' => 'Авторлыҡ хоҡуҡтары менән һаҡлана',
'exif-copyrighted-false' => 'Авторлыҡ-хоҡуҡи статус индерелмәгән',

'exif-unknowndate' => 'Билдәһеҙ көн',

'exif-orientation-1' => 'Ғәҙәти',
'exif-orientation-2' => 'X күсәре буйынса сағылдырылған',
'exif-orientation-3' => '180° әйләндерелгән',
'exif-orientation-4' => 'Y күсәре буйынса сағылдырылған',
'exif-orientation-5' => 'Сәғәт телдәренә ҡаршы 90° әйләндерелгән һәм Y күсәре буйынса сағылдырылған',
'exif-orientation-6' => 'Сәғәт телдәренә ҡаршы 90° әйләндерелгән',
'exif-orientation-7' => 'Сәғәт телдәре буйынса 90° әйләндерелгән һәм Y күсәре буйынса сағылдырылған',
'exif-orientation-8' => 'Сәғәт телдәре буйынса 90° әйләндерелгән',

'exif-planarconfiguration-1' => '«chunky» форматы',
'exif-planarconfiguration-2' => '«planar» форматы',

'exif-colorspace-65535' => 'Калибрацияһыҙ',

'exif-componentsconfiguration-0' => 'юҡ',

'exif-exposureprogram-0' => 'Билдәһеҙ',
'exif-exposureprogram-1' => 'Ҡул режимы',
'exif-exposureprogram-2' => 'Программа режимы (ғәҙәти)',
'exif-exposureprogram-3' => 'Диафрагма өҫтөнлөгө',
'exif-exposureprogram-4' => 'Затвор тиҙлеге өҫтөнлөгө',
'exif-exposureprogram-5' => 'Ижад режимы (кәрәкле киҫкенлек тәрәнлеге нигеҙендә)',
'exif-exposureprogram-6' => 'Спорт режимы (юғары затвор тиҙлеге нигеҙендә)',
'exif-exposureprogram-7' => 'Портрет режимы (яҡындан төшөрөү өсөн, артҡы фон фокуста түгел)',
'exif-exposureprogram-8' => 'Пейзаж режимы (пейзаждарҙы төшөрөү өсөн, артҡы фон фокуста)',

'exif-subjectdistance-value' => '$1 метр',

'exif-meteringmode-0' => 'Билдәһеҙ',
'exif-meteringmode-1' => 'Уртаса',
'exif-meteringmode-2' => 'Үҙәге әһәмиәтле',
'exif-meteringmode-3' => 'Нөктәле',
'exif-meteringmode-4' => 'Күп нөктәле',
'exif-meteringmode-5' => 'Ҡалып нигеҙендә',
'exif-meteringmode-6' => 'Өлөшләтә',
'exif-meteringmode-255' => 'Икенсе',

'exif-lightsource-0' => 'Билдәһеҙ',
'exif-lightsource-1' => 'Көн яҡтылығы',
'exif-lightsource-2' => 'Көн яҡтылығы лампаһы',
'exif-lightsource-3' => 'Ҡыҙҙырыу лампаһы',
'exif-lightsource-4' => 'Балҡыш (вспышка)',
'exif-lightsource-9' => 'Асыҡ көн торошо',
'exif-lightsource-10' => 'Болотло көн торошо',
'exif-lightsource-11' => 'Күләгә',
'exif-lightsource-12' => 'Көн яҡтылығы лампаһы (D 5700 – 7100K)',
'exif-lightsource-13' => 'Көн яҡтылығы лампаһы (N 4600 – 5400K)',
'exif-lightsource-14' => 'Көн яҡтылығы лампаһы (W 3900 – 4500K)',
'exif-lightsource-15' => 'Көн яҡтылығы лампаһы (WW 3200 – 3700K)',
'exif-lightsource-17' => 'A ғәҙәти яҡтылыҡ сығанағы',
'exif-lightsource-18' => 'B ғәҙәти яҡтылыҡ сығанағы',
'exif-lightsource-19' => 'C ғәҙәти яҡтылыҡ сығанағы',
'exif-lightsource-24' => 'ISO студия лампаһы',
'exif-lightsource-255' => 'Башҡа яҡтылыҡ сығанағы',

# Flash modes
'exif-flash-fired-0' => 'Балҡыш (вспышка) эшләмәне',
'exif-flash-fired-1' => 'Балҡыш (вспышка) эшләмәне',
'exif-flash-return-0' => 'алдан балҡыш режимы юҡ',
'exif-flash-return-2' => 'алдан балҡыштан сағылған яҡтылыҡ булманы',
'exif-flash-return-3' => 'алдан балҡыштан сағылған яҡтылыҡ булды',
'exif-flash-mode-1' => 'мәжбүри балҡыш импульсы',
'exif-flash-mode-2' => 'мәжбүри балҡышты баҫтырыу',
'exif-flash-mode-3' => 'автоматик режим',
'exif-flash-function-1' => 'Балҡыш юҡ',
'exif-flash-redeye-1' => 'ҡыҙыл күҙҙәр тәҫьирен юҡ итеү режимы',

'exif-focalplaneresolutionunit-2' => 'дюйм',

'exif-sensingmethod-1' => 'Билдәһеҙ',
'exif-sensingmethod-2' => 'Бер кристаллы төҫлө матрицалы сенсор',
'exif-sensingmethod-3' => 'Ике кристаллы төҫлө матрицалы сенсор',
'exif-sensingmethod-4' => 'Өс кристаллы төҫлө матрицалы сенсор',
'exif-sensingmethod-5' => 'Эҙмә-эҙлекле төҫлө матрицалы сенсор',
'exif-sensingmethod-7' => 'Өс төҫлө һыҙма сенсор',
'exif-sensingmethod-8' => 'Эҙмә-эҙлекле төҫлө һыҙма сенсор',

'exif-filesource-3' => 'Һанлы фотоаппарат',

'exif-scenetype-1' => 'Туранан-тура төшөрөлгән һүрәт',

'exif-customrendered-0' => 'Ғәҙәти',
'exif-customrendered-1' => 'Өҫтәмә эшкәртелгән',

'exif-exposuremode-0' => 'Автоматик экспозиция',
'exif-exposuremode-1' => 'Ҡул менән көйләнгән экспозиция',
'exif-exposuremode-2' => 'Автоматик тотҡос',

'exif-whitebalance-0' => 'Автоматик аҡ төҫ тигеҙләнеше',
'exif-whitebalance-1' => 'Ҡул менән көйләнгән аҡ төҫ тигеҙләнеше',

'exif-scenecapturetype-0' => 'Ғәҙәти',
'exif-scenecapturetype-1' => 'Тәбиғәт күренеше',
'exif-scenecapturetype-2' => 'Портрет',
'exif-scenecapturetype-3' => 'Төнгө сәхнә',

'exif-gaincontrol-0' => 'Юҡ',
'exif-gaincontrol-1' => 'Аҙға ҙурайтыу',
'exif-gaincontrol-2' => 'Күпкә ҙурайтыу',
'exif-gaincontrol-3' => 'Аҙға кәметеү',
'exif-gaincontrol-4' => 'Күпкә кәметеү',

'exif-contrast-0' => 'Ғәҙәти',
'exif-contrast-1' => 'Йомшаҡ',
'exif-contrast-2' => 'Ныҡ',

'exif-saturation-0' => 'Ғәҙәти',
'exif-saturation-1' => 'Түбән ҡуйылыҡ',
'exif-saturation-2' => 'Юғары ҡуйылыҡ',

'exif-sharpness-0' => 'Ғәҙәти',
'exif-sharpness-1' => 'Йомшаҡ',
'exif-sharpness-2' => 'Ныҡ',

'exif-subjectdistancerange-0' => 'Билдәһеҙ',
'exif-subjectdistancerange-1' => 'Макро',
'exif-subjectdistancerange-2' => 'Яҡындан төшөрөү',
'exif-subjectdistancerange-3' => 'Йыраҡтан төшөрөү',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Төньяҡ киңлек',
'exif-gpslatitude-s' => 'Көньяҡ киңлек',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Көнсығыш оҙонлоҡ',
'exif-gpslongitude-w' => 'Көнбайыш оҙонлоҡ',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => 'Дингеҙ кимәленән $1 {{PLURAL:$1|метр}} бейек',
'exif-gpsaltitude-below-sealevel' => 'Дингеҙ кимәленән $1 {{PLURAL:$1|метр}} түбән',

'exif-gpsstatus-a' => 'Үлсәү бара',
'exif-gpsstatus-v' => 'Үлсәү мәғлүмәттәре тапшырыла ала',

'exif-gpsmeasuremode-2' => '2 күсәр буйынса үлсәү',
'exif-gpsmeasuremode-3' => '3 күсәр буйынса үлсәү',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'км/сәғ',
'exif-gpsspeed-m' => 'миль/сәғ',
'exif-gpsspeed-n' => 'Узел',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Километр',
'exif-gpsdestdistance-m' => 'Миль',
'exif-gpsdestdistance-n' => 'Диңгеҙ миле',

'exif-gpsdop-excellent' => 'Бик шәп ($1)',
'exif-gpsdop-good' => 'Һәйбәт ($1)',
'exif-gpsdop-moderate' => 'Уртаса ($1)',
'exif-gpsdop-fair' => 'Уртасанан насар ($1)',
'exif-gpsdop-poor' => 'Насар ($1)',

'exif-objectcycle-a' => 'Иртәнсәк кенә',
'exif-objectcycle-p' => 'Кис кенә',
'exif-objectcycle-b' => 'Иртән һәм кис',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Ысын йүнәлеш',
'exif-gpsdirection-m' => 'Магнитлы йүнәлеш',

'exif-ycbcrpositioning-1' => 'Урталанған',
'exif-ycbcrpositioning-2' => 'CO-sited',

'exif-dc-contributor' => 'Өлөш индереүселәр',
'exif-dc-coverage' => 'Медианың арауыҡ йәки ваҡыт солғауы',
'exif-dc-date' => 'Дата(лар)',
'exif-dc-publisher' => 'Нәшриәт',
'exif-dc-relation' => 'Бәйле медиа',
'exif-dc-rights' => 'Хоҡуҡтар',
'exif-dc-source' => 'Сығанаҡ медиа',
'exif-dc-type' => 'Медиа төрө',

'exif-rating-rejected' => 'Кире ҡағылды',

'exif-isospeedratings-overflow' => '65535-тән күп',

'exif-iimcategory-ace' => 'Сәнғәт, мәҙәниәт һәм күңел асыу',
'exif-iimcategory-clj' => 'Енәйәт һәм хоҡуҡ',
'exif-iimcategory-dis' => 'Һәләкәттәр һәм авариялар',
'exif-iimcategory-fin' => 'Экономика һәм бизнес',
'exif-iimcategory-edu' => 'Мәғариф',
'exif-iimcategory-evn' => 'Тирә-яҡ',
'exif-iimcategory-hth' => 'Һаулыҡ',
'exif-iimcategory-hum' => 'Кеше ҡыҙыҡһыныуы',
'exif-iimcategory-lab' => 'Хеҙмәт',
'exif-iimcategory-lif' => 'Йәшәү рәүеше һәм ял',
'exif-iimcategory-pol' => 'Сәйәсәт',
'exif-iimcategory-rel' => 'Дин һәм иман',
'exif-iimcategory-sci' => 'Фән һәм техника',
'exif-iimcategory-soi' => 'Социаль мәсьәләләр',
'exif-iimcategory-spo' => 'Спорт',
'exif-iimcategory-war' => 'Һуғыштар, конфликттар һәм тәртипһеҙлектәр',
'exif-iimcategory-wea' => 'Һауа торошо',

'exif-urgency-normal' => 'Ғәҙәти ($1)',
'exif-urgency-low' => 'Түбән ($1)',
'exif-urgency-high' => 'Юғары ($1)',
'exif-urgency-other' => 'Ҡулланыусы билдәләгән өҫтөнлөк ($1)',

# External editor support
'edit-externally' => 'Был файлды тышҡы программа ҡулланып мөхәррирләргә',
'edit-externally-help' => '(Тулыраҡ мәғлүмәт өсөн металағы [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] (инглизсә) битен ҡарағыҙ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'бөтә',
'namespacesall' => 'бөтә',
'monthsall' => 'бөтә',
'limitall' => 'бөтә',

# Email address confirmation
'confirmemail' => 'Электрон почта адресын раҫлау',
'confirmemail_noemail' => 'Һеҙҙең [[Special:Preferences|көйләүҙәрегеҙҙә]] дөрөҫ электрон почта адресы юҡ.',
'confirmemail_text' => '{{SITENAME}} проекты электрон почта мөмкинлектәрен ҡулланыр алдынан электрон почта адресының раҫланыуын талап итә.
Электрон адресты раҫлау хаты һеҙҙең почтағыҙға ебәрелһен өсөн, түбәндәге төймәгә баҫығыҙ.
Хатта махсус биткә һылтанма буласаҡ, был һылтанманы браузерығыҙҙа асҡандан һуң, һеҙҙең электрон почта адресығыҙ раҫланған, тип һаналасаҡ.',
'confirmemail_pending' => 'Электрон почта адресын раҫлау хаты һеҙгә ебәрелгән ине инде.
Әгәр һеҙ иҫәп яҙмаһын яңыраҡ булдырһағыҙ, һеҙгә был хатты яңынан һорар алдынан, хат килгәнсе, бер нисә минут көтөргә кәрәк.',
'confirmemail_send' => 'Электрон почта адресын раҫлау хатын ебәрергә',
'confirmemail_sent' => 'Электрон почта адресын раҫлау хаты ебәрелде.',
'confirmemail_oncreate' => 'Электрон почта адресын раҫлау хаты һеҙ күрһәткән адрес буйынса ебәрелде.
Хатта күрһәтелгән һылтанма системала танылыу талап итмәй, ләкин элетрон почта мөмкинлектәрен вики проектта ҡулланыр өсөн, һеҙгә танылырға кәрәк.',
'confirmemail_sendfailed' => '{{SITENAME}} электрон почта адресын раҫлау хатын ебәрә алмай.
Зинһар, адресығыҙҙың дөрөҫлөгөн тикшерегеҙ.
Почта хеҙмәтенең яуабы: $1',
'confirmemail_invalid' => 'Раҫлау коды дөрөҫ түгел йәки уның ҡүлланыу ваҡыты үткән.',
'confirmemail_needlogin' => 'Электрон почта адресын раҫлау өсөн $1 кәрәк.',
'confirmemail_success' => 'Һеҙҙең электрон почта адресығыҙ раҫланды.
Хәҙер һеҙ [[Special:UserLogin|танылыу үтеп]], вики проект менән ҡуллана алаһығыҙ.',
'confirmemail_loggedin' => 'Һеҙҙең электрон почта адресығыҙ раҫланды.',
'confirmemail_error' => 'Электрон почта адресын раҫлаған ваҡытта хата килеп сыҡты.',
'confirmemail_subject' => '{{SITENAME}} электрон почта адресын раҫлау',
'confirmemail_body' => 'Кемдер, бәлки һеҙҙер, $1 IP адресынан {{SITENAME}} проектында 
ошо электрон почта адресы менән "$2" иҫәп яҙмаһын теркәгән.

Был иҫәп яҙмаһы ысынлап та һеҙҙеке икәнен раҫлау өсөн һәм {{SITENAME}} проектында электрон почта 
мөмкинлектәрен тоҡандырыу өсөн, браузерығыҙҙа түбәндәге һылтанманы асығыҙ:

$3

Әгәр һеҙ иҫәп яҙмаһын *булдырмағанһығыҙ* икән, электрон почта 
адресын раҫлауҙы үткәрмәү өсөн түбәндәге һылтанманы асығыҙ:

$5

Был раҫлау коды $4 ғәмәлдән сыға.',
'confirmemail_body_changed' => 'Кемдер, бәлки һеҙҙер, $1 IP адресынан 
{{SITENAME}}  проектында "$2" иҫәп яҙмаһының электрон почта адресын ошо адресҡа үҙгәрткән.

Был иҫәп яҙмаһы ысынлап та һеҙҙеке икәнен раҫлау өсөн һәм
{{SITENAME}} проектында электрон почта мөмкинлектәрен яңынан тоҡандырыу өсөн, браузерығыҙҙа түбәндәге һылтанманы асығыҙ:

$3

Әгәр һеҙ иҫәп яҙмаһын *булдырмағанһығыҙ* икән,
электрон почта адресын раҫлауҙы үткәрмәү өсөн түбәндәге һылтанманы асығыҙ:

$5

Был раҫлау коды $4 ғәмәлдән сыға.',
'confirmemail_body_set' => 'Кемдер (бәлки, һеҙҙер) $1 IP-адресынан 
{{SITENAME}}  проектында "$2" иҫәп яҙмаһының электрон почта адресы итеп ошо адресты күрһәткән.

Был иҫәп яҙмаһы ысынлап та һеҙҙеке икәнен раҫлау һәм {{SITENAME}} сайтынан хат ебәреү мөмкинлектәрен яңынан тоҡандырыу өсөн, браузерығыҙҙа түбәндәге һылтанманы асығыҙ:

$3

Әгәр иҫәп яҙмаһы һеҙҙеке *түгел* икән,
электрон почта адресын раҫлауҙы үткәрмәү өсөн түбәндәге һылтанманы асығыҙ:

$5

Был раҫлау коды $4 ғәмәлдән сыға.',
'confirmemail_invalidated' => 'Электрон почта адресын раҫлау туҡтатылды',
'invalidateemail' => 'Электрон почта адресын раҫлауҙы туҡтатыу',

# Scary transclusion
'scarytranscludedisabled' => '[Интервики индереү мөмкинлеге һүндерелгән]',
'scarytranscludefailed' => '[$1 ҡалыбына мөрәжәғәт итеү хатаһы]',
'scarytranscludefailed-httpstatus' => '[$1 өсөн ҡалып алып булманы: HTTP $2]',
'scarytranscludetoolong' => '[URL адрес бигерәк оҙон]',

# Delete conflict
'deletedwhileediting' => "'''Иғтибар''': Был бит һеҙ мөхәррирләй башлар алдынан юйылған ине!",
'confirmrecreate' => "[[User:$1|$1]] ([[User talk:$1|фекер алышыу]]) был битте һеҙ мөхәррирләй башлағандан һуң юйған, сәбәбе:
: ''$2''
Зинһар, был битте ысынлап та яңынан булдырырға теләүегеҙҙе раҫлағыҙ.",
'confirmrecreate-noreason' => '[[User:$1|$1]] ([[User talk:$1|фекер алышыу]]) һеҙ был битте мөхәррирләй башлағандан һуң юйған. Зинһар, был битте ысынлап та яңынан яһарға теләүегеҙҙе раҫлағыҙ.',
'recreate' => 'Яңынан булдырырға',

# action=purge
'confirm_purge_button' => 'Тамам',
'confirm-purge-top' => 'Был биттең кэшын таҙартырғамы?',
'confirm-purge-bottom' => 'Биттең кэшы таҙартылғандан һун, уның һуңғы өлгөһө күрһәтеләсәк.',

# action=watch/unwatch
'confirm-watch-button' => 'Тамам',
'confirm-watch-top' => 'Был битте күҙәтеү исемлегенә өҫтәргәме?',
'confirm-unwatch-button' => 'Тамам',
'confirm-unwatch-top' => 'Был битте күҙәтеү исемлегенән сығарырғамы?',

# Multipage image navigation
'imgmultipageprev' => '← алдағы бит',
'imgmultipagenext' => 'киләһе бит →',
'imgmultigo' => 'Күсеү!',
'imgmultigoto' => '$1 биткә күсеү',

# Table pager
'ascending_abbrev' => 'үҫеүгә табан',
'descending_abbrev' => 'кәмеүгә табан',
'table_pager_next' => 'Киләһе бит',
'table_pager_prev' => 'Алдағы бит',
'table_pager_first' => 'Беренсе бит',
'table_pager_last' => 'Һуңғы бит',
'table_pager_limit' => 'Бер биткә $1 есем күрһәтергә',
'table_pager_limit_label' => 'Бер биттә есемдәр һаны:',
'table_pager_limit_submit' => 'Күсеү',
'table_pager_empty' => 'Табылманы',

# Auto-summaries
'autosumm-blank' => 'Биттең эстәлеге юйылған',
'autosumm-replace' => 'Биттең эстәлеге "$1" менән алыштырылған',
'autoredircomment' => '[[$1]] битенә йүнәлтелгән',
'autosumm-new' => '"$1" исемле яңы бит булдырылған',

# Live preview
'livepreview-loading' => 'Сығарыу...',
'livepreview-ready' => 'Сығарыу... Әҙер!',
'livepreview-failed' => 'Тиҙ ҡарап сығыу ваҡытында хата килеп сыҡты.
Ғәҙәти ҡарап сығыуҙы ҡулланып ҡарағыҙ.',
'livepreview-error' => 'Бәйләнеш булдырып булманы: $1 "$2".
Ғәҙәти ҡарап сығыуҙы ҡулланып ҡарағыҙ.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|секундтан}} кәмерәк ваҡыт элек эшләнгән үҙгәртеүҙәр был исемлектә күрһәтелә алмай.',
'lag-warn-high' => 'Мәғлүмәттәр базаларын синхронлаштырыуҙың күпкә артта ҡалыуы сәбәпле, $1 {{PLURAL:$1|секундтан}} кәмерәк ваҡыт элек эшләнгән үҙгәртеүҙәр был исемлектә күрһәтелә алмай.',

# Watchlist editor
'watchlistedit-numitems' => 'Һеҙҙең күҙәтеү исемлегегеҙҙә фекер алышыу биттәрен иҫәпләмәгәндә - {{PLURAL:$1|$1 бит|$1 бит}} бар.',
'watchlistedit-noitems' => 'Һеҙҙең күҙәтеү исемлегегеҙҙә бер бит тә юҡ.',
'watchlistedit-normal-title' => 'Күҙәтеү исемлеген мөхәррирләү',
'watchlistedit-normal-legend' => 'Күҙәтеү исемлегенән биттәрҙе юйыу',
'watchlistedit-normal-explain' => 'Түбәндә һеҙҙең күҙәтеү исемлегендә булған биттәр күрһәтелгән.
Биттәрҙе юйыу өсөн, кәрәкле юлдарҙы һайлағыҙ һәм «{{int:Watchlistedit-normal-submit}}» төймәһенә баҫығыҙ.
Һеҙ шулай уҡ [[Special:EditWatchlist/raw|исемлекте текст рәүешендә үҙгәртә]] алаһығыҙ.',
'watchlistedit-normal-submit' => 'Биттәрҙе юйырға',
'watchlistedit-normal-done' => '{{PLURAL:$1|$1 бит|$1 бит}} һеҙҙең күҙәтеү исемлегенән юйылды:',
'watchlistedit-raw-title' => '«Сей» күҙәтеү исемлеген мөхәррирләү',
'watchlistedit-raw-legend' => '«Сей» күҙәтеү исемлеген мөхәррирләү',
'watchlistedit-raw-explain' => 'Түбәндә һеҙҙең күҙәтеү исемлегендә булған биттәр күрһәтелгән. Һеҙ был исемлекте, юлдар өҫтәп йәки юйып, үҙгәртә алаһығыҙ; бер юлға - бер исем.
Үҙгәртеүҙе тамамлағас, «{{int:Watchlistedit-raw-submit}}» төймәһенә баҫығыҙ.
Һеҙ шулай уҡ ғәҙәттәге [[Special:EditWatchlist|күҙәтеү исемен мөхәррирләү битен]] ҡулланана алаһығыҙ.',
'watchlistedit-raw-titles' => 'Яҙмалар:',
'watchlistedit-raw-submit' => 'Исемлекте яңыртырға',
'watchlistedit-raw-done' => 'Һеҙҙең күҙәтеү исемлеге яңырҙы.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 яҙма|$1 яҙма}} өҫтәлде:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 яҙма|$1 яҙма}} юйылды:',

# Watchlist editing tools
'watchlisttools-view' => 'Исемлектәге биттәрҙәге үҙгәрештәр',
'watchlisttools-edit' => 'Күҙәтеү исемлеген ҡарарға/төҙәтергә',
'watchlisttools-raw' => 'Текст һымаҡ үҙгәртеү',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|әңгәмә]])',

# Core parser functions
'unknown_extension_tag' => 'Билдәһеҙ "$1" киңәйтеү тегы',
'duplicate-defaultsort' => '\'\'\'Иҫкәртеү:\'\'\' "$2" ғәҙәттәге тәпртипкә килтереү асҡысы элекке "$1" ғәҙәттәге тәртипкә килтереү асҡысын үҙгәртә.',

# Special:Version
'version' => 'MediaWiki өлгөһө',
'version-extensions' => 'Ҡуйылған киңәйтеүҙәр',
'version-specialpages' => 'Махсус биттәр',
'version-parserhooks' => 'Уҡыу ҡоралдары',
'version-variables' => 'Үҙгәреүсән дәүмәлдәр',
'version-antispam' => 'Спамға ҡаршы ҡорал',
'version-skins' => 'Күренештәр',
'version-other' => 'Башҡалар',
'version-mediahandlers' => 'Медиа эшкәртеүсе ҡоралдар',
'version-hooks' => 'Эләктереп алыусылар',
'version-parser-extensiontags' => 'Уҡыу ҡоралдары киңәйтеүҙәре тегтары',
'version-parser-function-hooks' => 'Уҡыу ҡоралдары функцияларын эләктереп алыусылар',
'version-hook-name' => 'Эләктереп алыусы исеме',
'version-hook-subscribedby' => 'Яҙҙырылған',
'version-version' => '($1 өлгөһө)',
'version-license' => 'Рөхсәтнамә',
'version-poweredby-credits' => "Был вики проект '''[//www.mediawiki.org/ MediaWiki]''' нигеҙендә эшләй, copyright © 2001-$1 $2.",
'version-poweredby-others' => 'башҡалар',
'version-poweredby-translators' => 'translatewiki.net тәржемәселәре',
'version-credits-summary' => '[[Special:Version|MediaWiki]] үҫешенә өлөш индергәндәре өсөн киләһе ҡатнашыусыларға рәхмәт әйтәбеҙ.',
'version-license-info' => 'MediaWiki — ирекле программа, һеҙ уны Ирекле программалар фонды тарафынан баҫтырылған GNU General Public License рөхсәтнамәһенә ярашлы тарата һәм/йәки үҙгәртә алаһығыҙ (рөхсәтнамәнең йә исенсе өлгөһө, йә унан һуңғы өлгөләре).

MediaWiki файҙалы булыр, тигән өмөттә, ләкин БЕР НИДӘЙ ҘӘ ЯУАПЛЫЛЫҠ ЙӨКЛӘМӘҺЕҘ, хатта фараз ителгән ҺАТЫУ ӨСӨН ЯРАҠЛЫЛЫҠ йәки БИЛДӘЛӘНГӘН МАҠСАТ ӨСӨН ЯРАҠЛЫТЫҠ тураһында яуаплылыҡ йөкләмәһеҙ таратыла. Ентекле мәғлүмәт алыр өсөн, GNU General Public License рөхсәтнамәһе тураһында уҡығыҙ.

Был программа менән ҡуша һеҙ [{{SERVER}}{{SCRIPTPATH}}/COPYING GNU General Public License рөхсәтнамәһенең күсермәһен] алырға тейеш инегеҙ, әгәр юҡ икән, Ирекле программалар фондына 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA адресы буйынса яҙығыҙ, йәки рөхсәтнамәнең [//www.gnu.org/licenses/old-licenses/gpl-2.0.html онлайн өлгөһөн] уҡығыҙ.',
'version-software' => 'Ҡуйылған программалар',
'version-software-product' => 'Продукт',
'version-software-version' => 'Өлгөһө',
'version-entrypoints' => 'Инеш өсөн URL адрестар',
'version-entrypoints-header-entrypoint' => 'Инеш урыны',
'version-entrypoints-header-url' => 'URL',

# Special:Redirect
'redirect' => 'Файлдан, файҙаланыусынан йә версияның тиңләштереүсеһенән артабан йүнәлтеү',
'redirect-legend' => 'Файлға йәки биткә йүнәлтеү',
'redirect-summary' => 'Был махсус бит файлға (файлдың исеменән), биткә (версияның тиңләштереүсеһенән) йәки ҡатнашыусының битенә (ҡатнашыусының һанлы тиңләштереүсеһенән) йүнәлтә.',
'redirect-submit' => 'Күсергә',
'redirect-lookup' => 'Эҙләү',
'redirect-value' => 'Мәғәнәһе:',
'redirect-user' => 'Ҡатнашыусының тиңләштереүсеһе',
'redirect-revision' => 'Биттең версияһы',
'redirect-file' => 'Файлдың исеме',
'redirect-not-exists' => 'Мәғәнәһе табылманы',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Бер иш файлдарҙы эҙләү',
'fileduplicatesearch-summary' => 'Бер иш файлдарҙы хэш-кодтары буйынса эҙләү.',
'fileduplicatesearch-legend' => 'Бер иш файлдарҙы эҙләү',
'fileduplicatesearch-filename' => 'Файл исеме:',
'fileduplicatesearch-submit' => 'Эҙләү',
'fileduplicatesearch-info' => '$1 × $2 пиксел<br />Файлдың дәүмәле: $3<br />MIME төрө: $4',
'fileduplicatesearch-result-1' => '"$1" файлы менән тап килеүсе файлдар юҡ.',
'fileduplicatesearch-result-n' => '"$1" файлы менән $2 {{PLURAL:$2|файл}} тап килә.',
'fileduplicatesearch-noresults' => '"$1" исемле файл табылманы',

# Special:SpecialPages
'specialpages' => 'Махсус биттәр',
'specialpages-note' => '----
* Ябай махсус биттәр.
* <span class="mw-specialpagerestricted">Сикле махсус биттәр.</span>
* <span class="mw-specialpagecached">Кешланған махсус биттәр (иҫкергән булыуы мөмкин).</span>',
'specialpages-group-maintenance' => 'Техник хеҙмәтләндереү хисапламалары',
'specialpages-group-other' => 'Башҡа махсус биттәр',
'specialpages-group-login' => 'Танышыу йәки теркәлеү',
'specialpages-group-changes' => 'Һуңғы үҙгәртеүҙәр һәм журналдар',
'specialpages-group-media' => 'Медиа хисапламалары һәм тейәүҙәр',
'specialpages-group-users' => 'Ҡатнашыусылар һәм хоҡуҡтар',
'specialpages-group-highuse' => 'Йыш ҡулланылған биттәр',
'specialpages-group-pages' => 'Биттәр исемлеге',
'specialpages-group-pagetools' => 'Биттәр өсөн ҡоралдар',
'specialpages-group-wiki' => 'Мәғлүмәттәр һәм ҡоралдар',
'specialpages-group-redirects' => 'Йүнәлтеүсе махсус биттәр',
'specialpages-group-spam' => 'Спамға ҡаршы ҡоралдар',

# Special:BlankPage
'blankpage' => 'Буш бит',
'intentionallyblankpage' => 'Был бит аңлы рәүештә буш ҡалдырылған.',

# External image whitelist
'external_image_whitelist' => '#Был юлды нисек бар, шулай ҡалдырығыҙ<pre>
#Бында регуляр аңлатма өлөштәрен ҡуйығыҙ(// араһында булған өлөштәрен)
#Улар тышҡы рәсемдәрҙең URL адрестары менән сағыштырыласаҡ.
#Яраҡлылары рәсем рәүешендә күрһәтеләсәк, ҡалғандары рәсемгә һылтанма рәүешендә күрһәтеләсәк.
# # менән башланған юлдар иҫкәрмә тип иҫәпләнә.
#Юлдар ҙур/бәләкәй хәрефкә һиҙгер

# Регуляр аңлатма өлөштәрен ошо юл өҫтөнә ҡуйығыҙ. Был юлды нисек бар, шулай ҡалдырығыҙ.</pre>',

# Special:Tags
'tags' => 'Ҡулланылған үҙгәртеү билдәләре',
'tag-filter' => '[[Special:Tags|Билдәләрҙе]] һайлау:',
'tag-filter-submit' => 'Һайлау',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Тамғалар}}]]: $2)',
'tags-title' => 'Билдәләр',
'tags-intro' => 'Был биттә программа үҙгәртеүҙәрҙе билдәләү өсөн ҡулланған билдәләр һәм уларҙың мәғәнәләре исемлеге килтерелгән.',
'tags-tag' => 'Билдә исеме',
'tags-display-header' => 'Үҙгәртеүҙәр исемлегендә күрһәтеү',
'tags-description-header' => 'Мәғәнәһенең тулы тасуирламаһы',
'tags-active-header' => 'Әүҙемме?',
'tags-hitcount-header' => 'Билдәләнгән үҙгәртеүҙәр',
'tags-active-yes' => 'Эйе',
'tags-active-no' => 'Юҡ',
'tags-edit' => 'үҙгәртергә',
'tags-hitcount' => '$1 {{PLURAL:$1|үҙгәртеү|үҙгәртеү}}',

# Special:ComparePages
'comparepages' => 'Биттәрҙе сағыштырыу',
'compare-selector' => 'Биттәрҙең өлгөләрен сағыштырыу',
'compare-page1' => 'Беренсе бит',
'compare-page2' => 'Икенсе бит',
'compare-rev1' => 'Беренсе өлгө',
'compare-rev2' => 'Икенсе өлгө',
'compare-submit' => 'Сағыштырырға',
'compare-invalid-title' => 'Керетелгән исем дөрөҫ түгел.',
'compare-title-not-exists' => 'Һеҙ күрһәткән исем юҡ.',
'compare-revision-not-exists' => 'Һеҙ күрһәткән версия юҡ.',

# Database error messages
'dberr-header' => 'Был вики проектта ҡыйынлыҡтар бар',
'dberr-problems' => 'Ғәфү итегеҙ! Был сайтта техник ҡыйынлыҡтар тыуҙы.',
'dberr-again' => 'Битте бер нисә минуттан яңыртып ҡарағыҙ.',
'dberr-info' => '(Мәғлүмәттәр базаһы серверы менән тоташтырылып булмай: $1)',
'dberr-info-hidden' => '(Мәғлүмәт базаларының серверына тоташып булмай)',
'dberr-usegoogle' => 'Әлегә һеҙ Google ярҙамында эҙләп ҡарай алһығыҙ.',
'dberr-outofdate' => 'Әммә уның индекстары иҫекргән булыуы мөмкинлеген күҙ уңында тотоғоҙ.',
'dberr-cachederror' => 'Түбәндә һоралған биттең кэшта һаҡланған өлгөһө күрһәтелгән, унда аҙаҡҡы үҙгәртеүҙәр булмауы мөмкин.',

# HTML forms
'htmlform-invalid-input' => 'Һеҙ кереткән мәғлүмәттең ниндәйҙер өлөшө ҡыйынлыҡтар тыуҙыра',
'htmlform-select-badoption' => 'Һеҙ керетҡән мәғәнә дөрөҫ түгел',
'htmlform-int-invalid' => 'Һеҙ бөтөн һан керетмәгәнһегеҙ.',
'htmlform-float-invalid' => 'Һеҙ һан керетмәгәнһегеҙ.',
'htmlform-int-toolow' => 'Һеҙ кереткән мәғәнә аҫҡы сиктән түбәнерәк — $1',
'htmlform-int-toohigh' => 'Һеҙ кереткән мәғәнә өҫкө сиктән юғарыраҡ — $1',
'htmlform-required' => 'Был мәғәнә билдәләнгән булырға тейеш',
'htmlform-submit' => 'Ебәрергә',
'htmlform-reset' => 'Үҙгәртеүҙәрҙе кире алырға',
'htmlform-selectorother-other' => 'Башҡа',
'htmlform-no' => 'Юҡ',
'htmlform-yes' => 'Эйе',
'htmlform-chosen-placeholder' => 'Вариант һайлағыҙ',

# SQLite database support
'sqlite-has-fts' => '$1, тулы текст буйынса эҙләү мөмкинлеге менән',
'sqlite-no-fts' => '$1, тулы текст буйынса эҙләү мөмкинлекһеҙ',

# New logging system
'logentry-delete-delete' => '$1 $3 битен {{GENDER:$2|юйҙы}}',
'logentry-delete-restore' => '$1 $3 битен {{GENDER:$2|тергеҙҙе}}',
'logentry-delete-event' => '$1 журналдағы {{PLURAL:$5|яҙманы}} $3: $4 {{GENDER:$2|үҙгәртте}}',
'logentry-delete-revision' => '$1 {{PLURAL:$5|$5 версияның}} күренеүсәнлеген   $3: $4 битендә {{GENDER:$2|үҙгәртте}}',
'logentry-delete-event-legacy' => '$1  $3 журналы яҙмаларының күренеүсәнлеген {{GENDER:$2|үҙгәртте}}',
'logentry-delete-revision-legacy' => '$1  $3 битендә версияларҙың күренеүсәнлеген {{GENDER:$2|үҙгәртте}}',
'logentry-suppress-delete' => '$1 $3 битен {{GENDER:$2|баҫырылдырҙы}}',
'logentry-suppress-event' => '$1 журналдағы {{PLURAL:$5|$5 яҙманың}} күренеүсәнлеген $3 битендә йәшерен үҙгәртте: $4',
'logentry-suppress-revision' => '$1 {{PLURAL:$5|$5 версияның}} күренеүсәнлеген $3 битендә йәшерен үҙгәртте: $4',
'logentry-suppress-event-legacy' => '$1  журнал яҙмаларының күренеүсәнлеген йәшерен  {{GENDER:$2|үҙгәртте}}$3',
'logentry-suppress-revision-legacy' => '$1 $3 битендә версияларҙың күренеүсәнлеген йәшерен {{GENDER:$2|}}',
'revdelete-content-hid' => 'эстәлек йәшерелгән',
'revdelete-summary-hid' => 'төҙәтеү аңлатмаһы йәшерелде',
'revdelete-uname-hid' => 'ҡатнашыусы исеме йәшерелгән',
'revdelete-content-unhid' => 'эстәлек күрһәтелде',
'revdelete-summary-unhid' => 'төҙәтеү аңлатмаһы асылды',
'revdelete-uname-unhid' => 'ҡатнашыусы исеме күрһәтелде',
'revdelete-restricted' => 'хакимдәргә ҡаршы ҡулланылған сикләүҙәр',
'revdelete-unrestricted' => 'хакимдәрҙән алынған сикләүҙәр',
'logentry-move-move' => '$1  $3 битенең исемен {{GENDER:$2| үҙгәртте}}. Яңы исеме: $4',
'logentry-move-move-noredirect' => '$1 $3 битенең исемен йүнәлтеү ҡуймайынса {{GENDER:$2|үҙгәртте}}. Яңы исеме: $4',
'logentry-move-move_redir' => '$1 $3 битенең исемен йүнәлтеү өҫтөнән {{GENDER:$2|үҙгәртте}}. Яңы исеме: $4',
'logentry-move-move_redir-noredirect' => '$1 $3 битенең исемен йүнәлтеү ҡуймайынса һәм йүнәлтеү өҫтөнән {{GENDER:$2|үҙгәртте}}. Яңы исеме: $4',
'logentry-patrol-patrol' => '$1 $3 битенең $4 версияһын {{GENDER:$2|тикшерҙе}}.',
'logentry-patrol-patrol-auto' => '$1 $3 битенең $4 версияһын автоматик рәүештә {{GENDER:$2|тикшерҙе}}.',
'logentry-newusers-newusers' => ' {{GENDER:$2|ҡатнашыусы}} $1 иҫәп яҙмаһы булдырҙы',
'logentry-newusers-create' => '{{GENDER:$2|ҡатнашыусы}} $1 иҫәп яҙмаһы булдырҙы.',
'logentry-newusers-create2' => '$1 {{GENDER:$2|ҡатнашыусы}} $3 иҫәп яҙмаһын булдырҙы',
'logentry-newusers-byemail' => '$1 {{GENDER:$2|}} $3 иҫәп яҙмаһын булдырҙы һәм серһүҙ электрон почта аша ебәрелде',
'logentry-newusers-autocreate' => 'Автоматик рәүештә {{GENDER:$2| ҡатнашыусының}} $1 иҫәп яҙмаһы яһалды',
'logentry-rights-rights' => '$1  $3 файҙаланыусының төркөмдәрҙәге ағзалығын $4 урынына $5 тип {{GENDER:$2|үҙгәртте}}',
'logentry-rights-rights-legacy' => '$1  $3 өсөн төркөмдәрҙәге ағзалыҡты {{GENDER:$2|үҙгәртте}}',
'logentry-rights-autopromote' => '$1 {{GENDER:$2|}} автоматик рәүештә {{GENDER:$2|}} $4 урынына $5 ителде.',
'rightsnone' => '(юҡ)',

# Feedback
'feedback-bugornote' => 'Әгәр Һеҙ техник проблеманы ентекле рәүештә аңлатырға теләһәгеҙ, зинһар, [$1 хата тураһында белдерегеҙ].
Башҡа осраҡта, ошо ябай форманы ҡуллана алаһығыҙ. Комментарийығыҙ «[$3 $2]» битенә ҡулланыусы исемегеҙ һәм браузер мәғлүмәте менән өҫтәләсәк.',
'feedback-subject' => 'Тема:',
'feedback-message' => 'Хәбәр:',
'feedback-cancel' => 'Кире алырға',
'feedback-submit' => 'Кире белдереү ебәрергә',
'feedback-adding' => 'Биткә кире белдереү өҫтәлә',
'feedback-error1' => 'Хата: API-нан беленмәгән хата',
'feedback-error2' => 'Хата: Мөхәррирләү хатаһы',
'feedback-error3' => 'Хата: API-нан яуап юҡ',
'feedback-thanks' => 'Рәхмәт! Һеҙҙең фекерегеҙ «[$2 $1]» битенә өҫтәлде.',
'feedback-close' => 'Әҙер',
'feedback-bugcheck' => 'Шәп! Тик [$1 билдәле хаталар] исемлегендә оҡшаш белдереүҙең булмауына иғтибар итегеҙ.',
'feedback-bugnew' => 'Тикшерҙем. Яңы хата тураһында белдерергә',

# Search suggestions
'searchsuggest-search' => 'Эҙләү',
'searchsuggest-containing' => 'эстәлегендә...',

# API errors
'api-error-badaccess-groups' => 'Һеҙгә был викиға файлдар күсереү рөхсәт ителмәй',
'api-error-badtoken' => 'Эске хата: дөрөҫ булмаған токен',
'api-error-copyuploaddisabled' => 'Был серверҙа URL адрес буйынса йөкләү өҙөлгән',
'api-error-duplicate' => 'Бындай эстәлекле {{PLURAL:$1|[$2 файл]}}  бар.',
'api-error-duplicate-archive' => 'Сайтта бындай эстәлекле {{PLURAL:$1|[$2 башҡа файл]}} бар ине инде, ләкин {{PLURAL:$1|ул юйылды|улар юйылды}}',
'api-error-duplicate-archive-popup-title' => 'Элек юйылған {{PLURAL:$1|файлдың|файлдарҙың}} дубликаты',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|файлы|файлдары}} дубликаты.',
'api-error-empty-file' => 'Һеҙ ебәргән файл буш.',
'api-error-emptypage' => 'Яңы буш биттәр яһау тыйыла.',
'api-error-fetchfileerror' => 'Эске хата: файлды күсергән ваҡытта хата китте',
'api-error-fileexists-forbidden' => '«$1» исемле файл бар һәм өҫтөнә яҙып булмай.',
'api-error-fileexists-shared-forbidden' => '«$1» исемле файл уртаҡ файлдар һаҡлағысында бар һәм өҫтөнә яҙып булмай.',
'api-error-file-too-large' => 'Һеҙ ебәргән файл үтә ҙур.',
'api-error-filename-tooshort' => 'Файл исеме бик ҡыҫҡа.',
'api-error-filetype-banned' => 'Был файл төрө тыйылған.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|тыйылған файл төрө|тыйылған файл төрҙәре}}. Рөхсәт ителгән {{PLURAL:$3|файл төрө|файл төрҙәре}} $2.',
'api-error-filetype-missing' => 'Был файлдың ҡушымтаһы юҡ',
'api-error-hookaborted' => 'Һеҙ эшләргә теләгән үҙгәртеүҙәр ҡушымтаны тикшереүсе тарафынан өҙөлдө',
'api-error-http' => 'Эске хата: серверға бәйләнеп булмай.',
'api-error-illegal-filename' => 'Рөхсәт ителмәгән файл исеме.',
'api-error-internal-error' => 'Эске хата: һеҙ викиға йөкләгәнде тикшергән ваҡытта хата китте',
'api-error-invalid-file-key' => 'Эске хата: ваҡытлыса һаҡлағыста файл табылманы',
'api-error-missingparam' => 'Эске хата: мөрәжәғеттең параматрҙары юҡ.',
'api-error-missingresult' => 'Эске хата: күсереү уңышлы булыуын билдәләп булманы.',
'api-error-mustbeloggedin' => 'Файлдарҙы йөкмәтеү өсөн һеҙ сисемаға танышырға тейешһегеҙ.',
'api-error-mustbeposted' => 'Эске хата: мөрәжәғәт HTTP POST адресын талап итә.',
'api-error-noimageinfo' => 'Йөкләү уңышлы тамамланды, әммә сервер файл тураһында бер ниндәйҙә мәғлүмәт бирмәне.',
'api-error-nomodule' => 'Эске хата: тейәү модуле көйләнмәгән.',
'api-error-ok-but-empty' => 'Эске хата: серверҙан яуап юҡ.',
'api-error-overwrite' => 'Булған файлды алыштырыу рөхсәт ителмәй.',
'api-error-stashfailed' => 'Эске хата: сервер ваҡытлыса файлды һаҡлай алманы.',
'api-error-publishfailed' => 'Эске хата: сервер ваҡытлыса файлды һаҡлай алманы.',
'api-error-timeout' => 'Көтөлгән ваҡыт эсендә сервер яуып бирмәне.',
'api-error-unclassified' => 'Билдәһеҙ хата барлыҡҡа килде.',
'api-error-unknown-code' => 'Билдәһеҙ хата: «$1»',
'api-error-unknown-error' => 'Эске хата: файлды йөкләгәндә ниндәйҙер хата китте.',
'api-error-unknown-warning' => 'Билдәһеҙ белдереү: "$1".',
'api-error-unknownerror' => 'Билдәһеҙ хата: «$1»',
'api-error-uploaddisabled' => 'Был викила файл тейәү мөмкинлеге ябылған.',
'api-error-verification-error' => 'Был файл боҙолған, йәки дөрөҫ булмаған ҡушымтаһы бар.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|секунд|секунд}}',
'duration-minutes' => '$1 {{PLURAL:$1|минут|минут}}',
'duration-hours' => '$1 {{PLURAL:$1|сәғәт|сәғәт}}',
'duration-days' => '$1 {{PLURAL:$1|көн|көн}}',
'duration-weeks' => '$1 {{PLURAL:$1|аҙна|аҙналар|аҙна}}',
'duration-years' => '$1 {{PLURAL:$1|йыл|йылдар}}',
'duration-decades' => '$1 {{PLURAL:$1|ун көнлөк|ун көнлөктәр}}',
'duration-centuries' => '$1 {{PLURAL:$1|быуат|быуаттар}}',
'duration-millennia' => '$1 {{PLURAL:$1|меңйыллыҡ|меңйыллыҡтар}}',

# Image rotation
'rotate-comment' => 'Рәсем сәғәт йөрөшө буйынса $1{{PLURAL:$1|}} градусҡа боролдо',

# Limit report
'limitreport-title' => 'Анализатор мәғлүмәттәре:',
'limitreport-cputime' => 'Процессорҙың ваҡытын ҡулланыу',
'limitreport-cputime-value' => '$1 {{PLURAL:$1|секунд}}',
'limitreport-walltime' => 'Ғәмәлдәге ваҡыт режимында ҡулланыу',
'limitreport-walltime-value' => '$1 {{PLURAL:$1|секунд}}',
'limitreport-ppvisitednodes' => 'Процессор инеп ҡараған төйөндәр һаны',
'limitreport-ppgeneratednodes' => 'Процессор эшләп сығарған төйөндәр һаны',
'limitreport-postexpandincludesize' => 'Асылған өлөштәр һаны',
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|байт}}',
'limitreport-templateargumentsize' => 'Ҡалып аргументының үлсәмдәре',
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|байт}}',
'limitreport-expansiondepth' => 'Киңәйеүҙең иң ҙур тәрәнлеге',
'limitreport-expensivefunctioncount' => 'Анализаторҙың "ҡиммәтле" функцияларының һаны',

);
