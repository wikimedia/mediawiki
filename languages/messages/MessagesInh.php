<?php
/** Ingush (ГӀалгӀай)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amire80
 * @author Reedy
 * @author Sapral Mikail
 * @author Tagir
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'               => 'Ӏинкаш белгалде:',
'tog-highlightbroken'         => 'Йоаца Ӏинкаш хьахьокха <a href="" class="new">иштта</a> (е вештта<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Яздам оагӀува шоралца хьанийсде',
'tog-hideminor'               => 'ЗӀамига хувцамаш керда хувцаман дагарленашках къайлаяккха',
'tog-hidepatrolled'           => 'Керда хувцаман дагарленач дӀанийсаяь хувцамаш къайладаккха',
'tog-newpageshidepatrolled'   => 'Керда оагӀувна дагарленач дӀанийсаяь хувцамаш къайладаккха',
'tog-extendwatchlist'         => 'Шераяь теркама дагарле, массадола хувцамаш чулоацаш',
'tog-usenewrc'                => 'Керда хувцами теркама дагарлеи хувцамаш тоабъе (JavaScript)',
'tog-numberheadings'          => 'Корталенашт аланза таьрахьал де',
'tog-showtoolbar'             => 'ГӀалатнийcдара юкъе лакхера гӀорсан гартакх хьахьокха (JavaScript)',
'tog-editondblclick'          => 'Шозза цлицакацa oагӀув хувца (JavaScript)',
'tog-editsection'             => 'ХӀара дакъа "хувца" яха Ӏинк хьахьокха',
'tog-editsectiononrightclick' => 'Декъам хувца кертмугӀа аьтта цлицака я (JavaScript)',
'tog-showtoc'                 => 'Кортанче хьокха (кхьаннена дукхагӀа кертмугӀанаш йoлa оагӀувна)',
'tog-rememberpassword'        => '(укх $1 {{PLURAL:$1|ден|деношк}}) мара са чувалара/ялара дагалоаца дезаш дац',
'tog-watchcreations'          => 'Аз яь йола оагӀувнаш теркама дагарле йолач чуяьккха',
'tog-watchdefault'            => 'Аз хийца йола оагӀувнаш теркама дагарле йолач чуяьккха',
'tog-watchmoves'              => 'Аз цӀи хийца йола оагӀувнаш теркама дагарле йолач чуяьккха',
'tog-watchdeletion'           => 'Аз дӀаяьккха йола оагӀувнаш теркама дагарле йолач чуяьккха',
'tog-minordefault'            => 'Теркамза хувцамаш лоархӀамза белгалде',
'tog-previewontop'            => 'ГӀалатнийсдара кора хьалхе бӀаргтассам оттае',
'tog-previewonfirst'          => 'ГӀалатнийсдаре дехьавоалаш/йоалаш бӀаргтассам хьахьокха',
'tog-nocache'                 => 'Укхазара оагӀувнаший лочкъараш дӀадоаде',
'tog-enotifwatchlistpages'    => 'ОагӀувний хувцамахи теркама дагарленахи лаьца, д-хоамнец хоам бе',
'tog-enotifusertalkpages'     => 'Са дувцама оагӀув тӀа хувцамаш хилача, д-хоамнец хоам бе',
'tog-enotifminoredits'        => 'Геттара зӀамига хувцамаш хилача, д-хоамнец хоам бе',
'tog-enotifrevealaddr'        => 'ЗӀы хоамаш тӀа са хоамни моттиг хьахьокха',
'tog-shownumberswatching'     => 'Ший теркама дагарленгах оагӀув чулаьца бола дакъалаьцархой таьрах хьахьокха',
'tog-oldsig'                  => 'Дола кулгайоазув:',
'tog-fancysig'                => 'Ший кулга яздара массахоамбаккхам (ший лоӀаме Ӏинка йоацаш)',
'tog-externaleditor'          => 'Арена гӀалатнийсдарца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархIара ший-тайпара оттам эша; [//www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-externaldiff'            => 'Арена бӀасакхосса болхоагӀувца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархIара ший-тайпара оттам эша; [//www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-showjumplinks'           => '"Дехьадала" яха новкъостала Ӏинк хьахьокха',
'tog-uselivepreview'          => 'Сиха бӀарахьажар (JavaScript) (Экспериментально)',
'tog-forceeditsummary'        => 'Хоам бе, хувцамий лоацам белгал даь деце',
'tog-watchlisthideown'        => 'Са хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthidebots'       => 'БӀатий хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideminor'      => 'Са зӀамига хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideliu'        => 'Чубаьнна дакъалаьцархой хувцамаш теркама дагaрчеча къайлаяьккха',
'tog-watchlisthideanons'      => 'ЦӀи йоаца дакъалаьцархой хувцамаш теркама дагрчеча къайлаяьккха',
'tog-watchlisthidepatrolled'  => 'Теркама дагарчера дӀанийсъя хувцамаш къайлаяьккха',
'tog-ccmeonemails'            => 'Аз дакъалаьцархошоа дахта каьхаташ са д-хоамни тӀа хьатӀадайта',
'tog-diffonly'                => 'Диста кIал йоалаж йола оагӀувна дакъа ма хьокха',
'tog-showhiddencats'          => 'Къайла катагаш хьахьокха',

'underline-always'  => 'Массаза',
'underline-never'   => 'ЦӀаккха',
'underline-default' => 'МазабӀарглокхарий оттамаш хайрамбе',

# Font style option in Special:Preferences
'editfont-style' => 'ТIеракуц хувца',

# Dates
'sunday'        => 'КӀиранди',
'monday'        => 'Оршот',
'tuesday'       => 'Шинара',
'wednesday'     => 'Кхаьра',
'thursday'      => 'Ера',
'friday'        => 'ПӀаьраска',
'saturday'      => 'Шоатта',
'sun'           => 'КӀи',
'mon'           => 'Ор',
'tue'           => 'Ши',
'wed'           => 'Кха',
'thu'           => 'Ер',
'fri'           => 'ПӀаь',
'sat'           => 'Шоа',
'january'       => 'Нажгамсхой',
'february'      => 'Саькур',
'march'         => 'Мутхьол',
'april'         => 'Тушоли',
'may_long'      => 'Бекарг',
'june'          => 'Аьтинг',
'july'          => 'КӀимарс',
'august'        => 'Мангал',
'september'     => 'Моажол',
'october'       => 'Тов',
'november'      => 'Лайчил',
'december'      => 'Чантар',
'january-gen'   => 'Нажгамсхой бетт',
'february-gen'  => 'Саькур бетт',
'march-gen'     => 'Мутхьол бетт',
'april-gen'     => 'Тушоли бетт',
'may-gen'       => 'Бекарг бетт',
'june-gen'      => 'Аьтинг бетт',
'july-gen'      => 'КӀимарс бетт',
'august-gen'    => 'Мангал бетт',
'september-gen' => 'Моажол бетт',
'october-gen'   => 'Тов бетт',
'november-gen'  => 'Лайчил бетт',
'december-gen'  => 'Чантар бетт',
'jan'           => 'Нажг.',
'feb'           => 'Саьк.',
'mar'           => 'Мутхь.',
'apr'           => 'Tуш.',
'may'           => 'Бек.',
'jun'           => 'Аьт.',
'jul'           => 'КӀим.',
'aug'           => 'Манг.',
'sep'           => 'Моаж.',
'oct'           => 'Тов.',
'nov'           => 'Лайч.',
'dec'           => 'Чант.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Катаг|Катагаш}}',
'category_header'                => '"$1" Катагачар оагӀувнаш',
'subcategories'                  => 'Чуракатагаш',
'category-media-header'          => '"$1" Катагачар паьлаш',
'category-empty'                 => "''Укх катагчоа цхьаккха оагӀувнаш е паьлаш яц.''",
'hidden-categories'              => '{{PLURAL:$1|Къайла катаг|Къайла катагаш}}',
'hidden-category-category'       => 'Къайла катагаш',
'category-subcat-count'          => '{{PLURAL:$2|Йола катаг тӀехьара бухкатаг чулоаца.|{{PLURAL:$1|$1 бухкатаг хьахьекха я|$1 бухкатагаш хьахьекха я}} $2 йолачара.}}',
'category-subcat-count-limited'  => 'Укх катагий {{PLURAL:$1|$1 кӀалкатаг|$1 кӀалкатагаш}}.',
'category-article-count'         => '{{PLURAL:$2|Йола цатег цхьа оагӀув мара чулоацац.|{{PLURAL:$1|$1 оагӀув хьахекха я|$1 оагӀувнаш хьахекха я}} укх цатега $2 йолачарах.}}',
'category-article-count-limited' => 'Укх катагач {{PLURAL:$1|$1 оагӀув|$1 оагӀувнаш|}}.',
'category-file-count'            => '{{PLURAL:$2|Укх цатего ца паьла мара чулоацац.|{{PLURAL:$1|$1 паьла хьахьекха я|$1 паьлаш хьахьекха я}} укх цатегий $2 долачаьрахь.}}',
'category-file-count-limited'    => 'Укх катагач {{PLURAL:$1|$1 паьл|$1 паьлаш}}.',
'listingcontinuesabbrev'         => 'дӀахо',
'index-category'                 => 'ДIахьожаман оагӀувнаш',
'noindex-category'               => 'ДIахьожаманза оагӀувнаш',
'broken-file-category'           => 'Болхбеш йоаца паьла Ӏинкашца оагӀувнаш',

'about'         => 'Лоацам',
'article'       => 'Йоазув',
'newwindow'     => '(керда кора)',
'cancel'        => 'Юхавалa/ялa',
'moredotdotdot' => 'ДӀахо',
'mypage'        => 'Са оагӀув',
'mytalk'        => 'Са дувцама оагӀув',
'anontalk'      => 'Укх IP-моттига дувцам',
'navigation'    => 'Никътохкарг',
'and'           => '&#32;кхы',

# Cologne Blue skin
'qbfind'         => 'Лахар',
'qbbrowse'       => 'БӀаргтасса',
'qbedit'         => 'Хувца',
'qbpageoptions'  => 'ОагӀува оттамаш',
'qbpageinfo'     => 'ОагӀува тохкам',
'qbmyoptions'    => 'Са оттамаш',
'qbspecialpages' => 'ГӀулакхий оагӀувнаш',
'faq'            => 'Каст-каста хаттараш',
'faqpage'        => 'Project:Каст-каста хаттараш',

# Vector skin
'vector-action-addsection'       => 'БӀагал тӀатоха',
'vector-action-delete'           => 'ДӀадаккха',
'vector-action-move'             => 'ЦӀи хувца',
'vector-action-protect'          => 'Лораде',
'vector-action-undelete'         => 'Юхаоттаде',
'vector-action-unprotect'        => 'Лорам хувца',
'vector-simplesearch-preference' => 'Яьржа лахарий довзамаш чуяьккха (Vector skin only)',
'vector-view-create'             => 'Кхолларле',
'vector-view-edit'               => 'Хувцам',
'vector-view-history'            => 'Искар',
'vector-view-view'               => 'Дешар',
'vector-view-viewsource'         => 'Зембакхама бӀаргтассам',
'actions'                        => 'ДулархIамаш',
'namespaces'                     => 'ЦӀерий аренаш',
'variants'                       => 'Доштайпарленаш',

'errorpagetitle'    => 'ГӀалат',
'returnto'          => '$1 оагӀув тӀа юхавалар/ялар',
'tagline'           => 'Кечал укхазара я {{SITENAME}}',
'help'              => 'Куцтохкам',
'search'            => 'Лахаp',
'searchbutton'      => 'Хьалаха',
'go'                => 'Дехьавала',
'searcharticle'     => 'Дехьавала',
'history'           => 'искар',
'history_short'     => 'Искар',
'updatedmarker'     => 'Со ханача денца хувцамаш хиннaд',
'printableversion'  => 'Каьхатзарбане доржам',
'permalink'         => 'Даим латта Ӏинк',
'print'             => 'Каьхат арадаккха',
'view'              => 'БӀаргтассам',
'edit'              => 'Хувца',
'create'            => 'Хьаде',
'editthispage'      => 'Ер оагӀув хувца',
'create-this-page'  => 'Ep oагӀув хьае',
'delete'            => 'ДӀадаккха',
'deletethispage'    => 'Ер оагӀув дӀаяьккха',
'undelete_short'    => 'Меттаоттае {{PLURAL:$1|хувцам|$1 хувцамаш}}',
'viewdeleted_short' => 'БӀаргтасса {{PLURAL:$1|дӀадаьккха хувцам тӀа|$1 дӀадаьккха хувцамаш тӀа}}',
'protect'           => 'Лораде',
'protect_change'    => 'хувца',
'protectthispage'   => 'Лорае ер оагӀув',
'unprotect'         => 'Лорам хувца',
'unprotectthispage' => 'Лорам хувца',
'newpage'           => 'Керда оагӀув',
'talkpage'          => 'Укх оагӀув тӀа дувцам бе',
'talkpagelinktext'  => 'дувцам',
'specialpage'       => 'ГӀулакха оагӀув',
'personaltools'     => 'Са гӀорсаш',
'postcomment'       => 'Керда декъам',
'articlepage'       => 'Йоазув тӀа бӀаргтасса',
'talk'              => 'Дувцам',
'views'             => 'БӀаргтассамаш',
'toolbox'           => 'ГӀорсаш',
'userpage'          => 'Дакъалаьцачунна оагӀуве бӀаргтасса',
'projectpage'       => 'Хьахьоадайтама оагӀуве бӀаргтасса',
'imagepage'         => 'Паьла оагӀув тӀа бӀаргтасса',
'mediawikipage'     => 'Xоаман оагӀув хьахьокха',
'templatepage'      => 'ЧӀабала оагӀув тӀа бӀаргтасса',
'viewhelppage'      => 'Куцтохкам беха',
'categorypage'      => 'Катага оагӀув тӀа бӀаргтасса',
'viewtalkpage'      => 'Дувцамага бӀаргтасса',
'otherlanguages'    => 'Кхыча меттал',
'redirectedfrom'    => '($1 тӀера хьадейта да)',
'redirectpagesub'   => 'ДӀа-хьа дайта оагӀув',
'lastmodifiedat'    => 'Укх оагӀув тӀехьара  хувцам: $2, $1.',
'viewcount'         => 'Укх оагӀув тӀа бӀаргтасса хиннад {{PLURAL:$1|цхьазза|$1 шозза}}.',
'protectedpage'     => 'Лорама оагӀув',
'jumpto'            => 'Укхаза дехьавала/яла:',
'jumptonavigation'  => 'никътохкарг',
'jumptosearch'      => 'леха',
'pool-timeout'      => 'ЧIегатохара сабаран ха чакхаяьннай',
'pool-queuefull'    => 'Хаттарий цӀа хьалдизад',
'pool-errorunknown' => 'Довзаш доаца гӀалат',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Лоацам {{SITENAME}}',
'aboutpage'            => 'Project:Лоацам',
'copyright'            => '$1 чулоацамаца тIакхоачаш да.',
'copyrightpage'        => '{{ns:project}}:Яздаьчунна бокъо',
'currentevents'        => 'ХӀанзара хоамаш',
'currentevents-url'    => 'Project:ХӀанзара хоамаш',
'disclaimers'          => 'Бокъонах юхавалаp',
'disclaimerpage'       => 'Project:Бокъонах юхавалаp',
'edithelp'             => 'Хувцама куцтохкам',
'edithelppage'         => 'Help:ГӀалатнийсдар',
'helppage'             => 'Help:Чулоацам',
'mainpage'             => 'Кертера оагӀув',
'mainpage-description' => 'Кертера оагӀув',
'policy-url'           => 'Project:Бокъонаш',
'portal'               => 'Гулламков',
'portal-url'           => 'Project:Гулламков',
'privacy'              => 'Паьлбокъо',
'privacypage'          => 'Project:Паьлбокъо',

'badaccess'        => 'Чуваларa гӀалат',
'badaccess-group0' => 'Оаш хьадийха дулархIам шун де йишяц.',
'badaccess-groups' => 'Дахта кхоачашдар {{PLURAL:$2|тоабачара|тоабашкара}} $1 дакъалаьцархой мара де бокъо яц.',

'versionrequired'     => '$1 MediaWiki доржам эша',
'versionrequiredtext' => 'Укх оагӀув бeлха MediaWiki доржамаш эша $1. Хьажа [[Special:Version|version page]].',

'ok'                      => 'ХӀаа',
'retrievedfrom'           => '"$1" ГӀувам',
'youhavenewmessages'      => 'Оаш $1 ($2) дӀайийцад',
'newmessageslink'         => 'керда хоамаш',
'newmessagesdifflink'     => 'тӀехьара хувцамаш',
'youhavenewmessagesmulti' => 'Оаш $1чу керда хоамаш дӀайийцад',
'editsection'             => 'хувца',
'editold'                 => 'хувца',
'viewsourceold'           => 'xьайоагӀа къайлорг тӀа бӀаргтасса',
'editlink'                => 'хувца',
'viewsourcelink'          => 'xьайоагӀа къайлорг тӀа бӀаргтасса',
'editsectionhint'         => 'Декъам хувца: $1',
'toc'                     => 'Чулоацам',
'showtoc'                 => 'хьахьокха',
'hidetoc'                 => 'къайладаккха',
'collapsible-collapse'    => 'чудерзаде',
'collapsible-expand'      => 'хьадоаржаде',
'thisisdeleted'           => '$1 бӀаргтасса е юхаметтаоттаде?',
'viewdeleted'             => '$1 бӀаргтасса?',
'restorelink'             => '{{PLURAL:$1|дӀаяьккха хувцам|$1 дӀаяьккха хувцамаш}}',
'feedlinks'               => 'Цу тайпара:',
'site-rss-feed'           => '$1 RSS мугӀ',
'site-atom-feed'          => '$1 Atom мугӀ',
'page-rss-feed'           => '"$1" RSS мугӀ',
'page-atom-feed'          => '"$1" Atom мугӀ',
'red-link-title'          => '$1 (укх тайпара оагӀув яц)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Йоазув',
'nstab-user'      => 'Дакъалаьцархо',
'nstab-media'     => 'Медифаг',
'nstab-special'   => 'ГӀулакха оагӀув',
'nstab-project'   => 'Хьахоадайтамах лаьца',
'nstab-image'     => 'Паьл',
'nstab-mediawiki' => 'Хоам',
'nstab-template'  => 'ЧIабал',
'nstab-help'      => 'Куцтохкам',
'nstab-category'  => 'Катаг',

# Main script and global functions
'nosuchaction'      => 'Цу тайпара дулархIам бац',
'nosuchspecialpage' => 'Изза мо гӀон оагӀув яц',

# General errors
'error'              => 'ГӀалат',
'missing-article'    => 'Кораде дезаш хинна оагӀувни яздам корадаьдац «$1» $2.

Из мо гӀалат нийсалуш хула, саг тишъенна Ӏинкаца, д|адаьккха дола оагӀувни хувца искара тӀа чувала гӀертача.

Наггахь санна из иштта децe, шоана гӀорса Ӏалаш деча гӀалат кораяь хила мега.
Дехар да, [[Special:ListUsers/sysop|мазакулгалхочоа]] хоам бе, URL хьахьокхаш.',
'missingarticle-rev' => '(бӀаргоагӀув № $1)',
'internalerror'      => 'Чура гӀалат',
'internalerror_info' => 'Чура гӀалат: $1',
'cannotdelete-title' => 'ОагIув дIаяккха йиш яц "$1"',
'badtitle'           => 'Мегаш йоаца цӀи',
'badtitletext'       => 'Дехаш дола оагӀувни цӀи, нийса яц, яьсса я е меттаюкъара е массаюкъара цӀи харцахь я. ЦӀера юкъе мегаш доаца харакъаш нийсаденна хила мегаш да.',
'viewsource'         => 'БIаргтассам',
'actionthrottled'    => 'Сихален овзамал',
'protectedpagetext'  => 'Хувцаман белхаш долаш ер оагIув къайла я.',

# Virus scanner
'virus-unknownscanner' => 'довзашдоаца мазаундохьалург:',

# Login and logout pages
'yourname'                => 'Дакъалаьцархочунна цӀи:',
'yourpassword'            => 'КъайладIоагӀа:',
'yourpasswordagain'       => 'КъайладIоагӀа юха Ӏоязаде:',
'remembermypassword'      => '(укх $1 {{PLURAL:$1|ден|деношкахь}}) мара са чувалара/чуялара дагалоаца дезаш дац',
'yourdomainname'          => 'Шун цӀеноагӀув:',
'login'                   => 'Чувала/яла',
'nav-login-createaccount' => 'ЦӀи яьккха/Ший oагӀув ела',
'loginprompt'             => 'Укх белхацхьоагIоца доттагӀал лаца, шун "cookies" йийла хьалдеза.',
'userlogin'               => 'ЦӀи яьккха/ОагӀув ела',
'userloginnocreate'       => 'Чувала/яла',
'logout'                  => 'Аравала/яла',
'userlogout'              => 'Аравала/яла',
'notloggedin'             => 'Оаш шоай цӀи хьааьннадац',
'nologin'                 => "Леламе дIаяздар дац? '''$1'''.",
'nologinlink'             => 'Леламе дIаяздар кхолла',
'createaccount'           => 'Керда дакъалаьцархо кхолла',
'gotaccount'              => "Укхаза дӀаязабенна дий шо? '''$1'''.",
'gotaccountlink'          => 'Чувала/яла',
'userlogin-resetlink'     => 'Чувала/яла цӀии дIоагӀаи дийцаденнадий?',
'createaccountmail'       => 'КъайладIоагIа д-хоамнец хьадайта',
'createaccountreason'     => 'Бахьан:',
'badretype'               => 'Оаша яьккха дIоагIий цIераш шоайл таралуш яц.',
'loginerror'              => 'Дакъалаьцархочун цIи нийса яц',
'mailmypassword'          => 'Керда къайладIоагӀа хьаэца',
'mailerror'               => 'Хоам дIабохьийташ гIалат даьннад: $1',
'emailconfirmlink'        => 'Доаржален хоамни хьожадорг дIачIоагIаде',
'loginlanguagelabel'      => 'Мотт: $1',

# Change password dialog
'resetpass'                 => 'КъайладIоагIа дIахувцар',
'oldpassword'               => 'Къаьна къайладIоагӀа:',
'newpassword'               => 'Керда къайладIоагӀа:',
'retypenew'                 => 'Керда къайладIоагӀа юха Ӏоязаде:',
'resetpass-submit-loggedin' => 'КъайладIоагӀа дӀахувца',
'resetpass-submit-cancel'   => 'Юхавал/ялa',

# Special:PasswordReset
'passwordreset-username' => 'Дакъалаьцархочунна цӀи:',
'passwordreset-email'    => 'Д-хоамни моттиг:',

# Edit page toolbar
'bold_sample'     => 'Сома яздам',
'bold_tip'        => 'Сома яздам',
'italic_sample'   => 'Кулга яздам',
'italic_tip'      => 'Кулга яздам',
'link_sample'     => 'Ӏинка кортале',
'link_tip'        => 'ЧураӀинк',
'extlink_sample'  => 'Ӏинка кортале http://www.example.com',
'extlink_tip'     => 'Арен Ӏинка (http:// тамагӀах дийца ма ле)',
'headline_sample' => 'Кортален яздам',
'headline_tip'    => '2-гӀа лагӀарлен кортале',
'nowiki_sample'   => 'Укхаза кийчаде дезаш доаца яздам оттаде',
'nowiki_tip'      => 'Масса-бустамлорг теркамза дита',
'image_tip'       => 'Чуяьккха паьла',
'media_tip'       => 'Паьла Ӏинк',
'sig_tip'         => 'Шун кулгаяздар а, хӀанзара ха а',
'hr_tip'          => 'Мухала мугӀ (могаш тайпара к|еззига хайраде)',

# Edit pages
'summary'                          => 'Хувцамий белгалдер',
'subject'                          => 'БӀагал/кортале:',
'minoredit'                        => 'ЗӀамига хувцам',
'watchthis'                        => 'Укх оагӀува теркам бе',
'savearticle'                      => 'ОагӀув хьаязъе',
'preview'                          => 'Хьалхе бӀаргтассар',
'showpreview'                      => 'Хьалхе бӀаргтaссам',
'showlivepreview'                  => 'Сиха бӀаргтассар',
'showdiff'                         => 'Даь хувцамаш',
'anoneditwarning'                  => 'Зем хила! Шо кхы чудаьннадац. Шун IP-моттиг укх хийца оагӀув искаречу дӀаяздаь хургья.',
'summary-preview'                  => 'Лоацам ба:',
'subject-preview'                  => 'Кортале хургья:',
'blockedtitle'                     => 'Дакъалаьцархо чӀега бела ва/я',
'blockednoreason'                  => 'бахьан доацаш да',
'loginreqlink'                     => 'чувала/яла',
'loginreqpagetext'                 => 'Кхыйола оагӀувнашка хьожаргдолаш, оаш $1 де деза.',
'accmailtitle'                     => 'КъайладIоагӀа дӀадахьийтад',
'newarticle'                       => '(Kерда)',
'newarticletext'                   => 'Шо йоаца оагӀув тӀа Ӏинкаца дехьадаьннад.
Из хьае, кӀалхагӀа доала корачу яздам очуязаде (кхета хала дале [[{{MediaWiki:Helppage}}|новкъостала оагӀув тӀа]] бӀаргтасса).
Цаховш укхаза нийсадена дале, юхавала/яла яха тоӀобама тӀа пӀелга тоӀобе.',
'noarticletext'                    => "ХIанз укх оагӀув тӀа яздам доацаш да.
[[Special:Search/{{PAGENAME}}|цу тайпара цӀи дувцам кораде]] кхыдола йоазувашках йийша я шун, вешта
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тара дола таптарий йоазув карае], е
'''[{{fullurl:{{FULLPAGENAME}}|action=edit}} изза мо цӀи йолаш оагӀув ела]'''</span>.",
'noarticletext-nopermission'       => 'Укх сахьате укх оагӀув тӀа яздам дац.
Шун йийшая, кхыдола йоазувнашкахь [[Special:Search/{{PAGENAME}}|дола цӀерий хаттам корае]] е <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} нийсамий тептара йоазувнаш корае].</span>',
'note'                             => "'''ХӀамоалар:'''",
'previewnote'                      => "'''Хьалхе б|аргтассам мара бац.'''
Яздам кхы яздаь дац!",
'editing'                          => 'ГӀалатнийсдар: $1',
'editingsection'                   => 'ГIалатнийсдар $1 (оагӀувдакъа)',
'editingcomment'                   => 'ГӀалатнийсдар $1 (керда декъам)',
'editconflict'                     => 'ГӀалатнийсдара къовсам: $1',
'yourtext'                         => 'Хьа яздам',
'copyrightwarning'                 => "Теркам бе, $2 ($1 хьажа) бокъонаца лорадеш, тӀахьежама кӀала уллаш, оаш мел чуяккхаш дола хоамаш, яздамаш долга.
Наггахь санна шоай яздамаш пурам доацаш мала волашву саго хувца е кхы дола моттиге яздердолаш, безам беци, укхаз Ӏочуцаяздеча, дикаьгӀа да.<br />
Оаш дош лу, даь дола хувцама да волга/йолга, е оаш пурам долаш Ӏочуяздеш да кхычера меттигара шоай яздамаш/хоамаш.
'''Яздархой бокъоца лорадеш дола хӀамаш, цара пурам доацаш, Ӏочумаязаде!'''",
'templatesused'                    => 'Укх бӀаргоагӀувни оагӀув тӀа лелаяь {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'templatesusedpreview'             => 'Хьалхе бӀаргтассама оагӀув тӀа леладеш дола {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'template-protected'               => '(лорам лаьца)',
'template-semiprotected'           => '(дакъа-лорам)',
'hiddencategories'                 => 'Ер оагӀув укх {{PLURAL:$1|къайла цатегаца|къайла цатегашца}} дакъа лоаца:',
'permissionserrorstext-withaction' => '$2 де бокъо яц {{PLURAL:$1|из бахьан долаш|из бахьанаш долаш}}:',
'recreate-moveddeleted-warn'       => "'''Зем бе! Шо хьалххе дIайоаккхаш хинна оагӀув хьае гӀерта.'''

Хьажа, бокъонцахь езаш йолга.
КӀалхагIа укх оагӀуви дӀадаккхами цӀи хувцами тептараш хьекха да.",
'moveddeleted-notice'              => 'Ер оагӀув дӀаяьккха хиннай.
Новкъостала, кӀалха дӀадаккхама а хувцама а тептарашкера нийсама йоазувнаш хьахьекха я.',
'log-fulllog'                      => 'Деррига таптара бӀаргтасса',
'edit-conflict'                    => 'Хувцамий къовсам.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Зембаккхам: жамIан чIабалаш чулоаца дустам геттара доккха да.
Цхьадола чIабалаш чулоацалургдац.',
'post-expand-template-inclusion-category' => 'Чулоаца чIабала мегаш дола дустам дукхалена тӀехьайоала оагӀувнаш',
'post-expand-template-argument-warning'   => 'Зем бе! Ер оагӀув цаӀ куцкепа |аьлдош мара чулоацац, юхадастара сел доккха дустам йолаш.
Цу тайпара |аьлдешаш ӀокӀаладаькха да.',
'post-expand-template-argument-category'  => 'Куцкепий теркамза |аьлдешаш чулоаца оагӀувнаш',

# History pages
'viewpagelogs'           => 'Укх оагӀува тептараш хьокха',
'currentrev-asof'        => '$1 тӀа эггара тӀехьара доржам',
'revisionasof'           => '$1 доржам',
'revision-info'          => '$1; $2 хувцам',
'previousrevision'       => '← Xьалхйоаг|араш',
'nextrevision'           => 'ТIехьайоагIараш →',
'currentrevisionlink'    => 'Дола доржам',
'cur'                    => 'хӀанз.',
'next'                   => 'тӀехь.',
'last'                   => 'хьалх.',
'page_first'             => 'хьалхара',
'page_last'              => 'тӀехьара',
'histlegend'             => "Кхетам: (хӀанз.) = хӀанза йолачунна бӀаргоагӀувни хьакъоастам ба; (хьалх.) = хьалха хинначунна бӀаргоагӀувни хьакъоастам ба; '''зӀ''' = зӀамига хьахувцам ба.",
'history-fieldset-title' => 'Искара бӀаргтасса',
'history-show-deleted'   => 'ДӀадаьккхараш мара',
'histfirst'              => 'къаьнараш',
'histlast'               => 'ха яннараш',
'historyempty'           => '(даьсса)',

# Revision feed
'history-feed-title'          => 'Хувцамий искар',
'history-feed-description'    => 'Укх оагӀуви вики тӀа хувцамий искар',
'history-feed-item-nocomment' => '$1гӀара $2гӀачу',

# Revision deletion
'rev-delundel'               => 'хьахьокха/къайлаяьккха',
'rev-showdeleted'            => 'хьахьокха',
'revdelete-show-file-submit' => 'XӀаа',
'revdelete-radio-set'        => 'XӀаа',
'revdelete-radio-unset'      => 'A',
'revdelete-log'              => 'Бахьан',
'revdel-restore'             => 'Кустгойтам хувца',
'revdel-restore-deleted'     => 'дӀадаьккха доржамаш',
'revdel-restore-visible'     => 'бӀаргагушдола доржамаш',
'pagehist'                   => 'ОагӀува искар',
'deletedhist'                => 'ДӀадаккхамий искар',
'revdelete-reasonotherlist'  => 'Кхыдола бахьан',

# History merging
'mergehistory-list'   => 'ВIашагIатоха хувцамий искар',
'mergehistory-go'     => 'ВIашагIатоха хувцамаш хьахьокха',
'mergehistory-submit' => 'Хувцамаш вIашагIатоха',
'mergehistory-empty'  => 'ВIашагIатохара хувцамаш кораяь яц.',
'mergehistory-reason' => 'Бахьан:',

# Merge log
'revertmerge' => 'Декъа',

# Diffs
'history-title'           => '"$1" — хувцамий искар',
'difference'              => '(Доржамашкахь юкъера къоастамаш)',
'lineno'                  => 'МугI $1:',
'compareselectedversions' => 'Хьаржа доржамаша тарона тIа хьажа',
'editundo'                => 'юхавала/яла',
'diff-multi'              => '({{PLURAL:$1|$1 юкъара доржам хьахьекха дац|$1 юкъара доржамаш хьахьекха дац}} {{PLURAL:$2|$2 дакъалаьцархочунна|$2 дакъалаьцархоший}})',

# Search results
'searchresults'                    => 'Тохкама гIулакхахилар',
'searchresults-title'              => '"$1" тохка',
'searchresulttext'                 => 'Хьахьоадайтама оагIувнаш тIа тохкамах лаьца лоаца маIандар эца [[{{MediaWiki:Helppage}}|новкъостала декъамага]] хьажа.',
'searchsubtitle'                   => 'Хоаттамах лаьца «[[:$1]]» ([[Special:Prefixindex/$1|цу цIерах ювалу оагIувнаш]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|цу цIерах Iинкаш еш йола]])',
'searchsubtitleinvalid'            => "'''$1''' хаттара",
'notitlematches'                   => 'ОагIувни цIераш вIашагIа кхеташ яц',
'notextmatches'                    => 'ОагIувнаша яздамий вIашагIакхетараш дац',
'prevn'                            => '{{PLURAL:$1|хьалхйоаг|ар $1|хьалхйоаг|араш $1|хьалхйоаг|араш $1}}',
'nextn'                            => '{{PLURAL:$1|тlехьайоагlар $1|тlехьайоагlараш $1|тlехьайоагlараш $1}}',
'prevn-title'                      => '{{PLURAL:$1|$1 хьалхара йоазув|$1 хьалхара йоазувнаш}}',
'nextn-title'                      => '{{PLURAL:$1|$1 тIехьара йоазув|$1 тIехьара йоазувнаш}}',
'shown-title'                      => 'Укх оагIувни $1 {{PLURAL:$1|йоазув|йоазувнаш}} хьахьокха',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) хьажа',
'searchmenu-exists'                => "'''Укх масса-хьахьоадайтамач ер оаг|ув \"[[:\$1]]\" я'''",
'searchmenu-new'                   => "'''Укх \"[[:\$1]]\" масса-хьахоадайтамач оагIув хьае!'''",
'searchhelp-url'                   => 'Help:Чулоацам',
'searchprofile-articles'           => 'Гомлен оагIувнаш',
'searchprofile-project'            => 'Дагарлеи хьахоадайтами оагIувнаш',
'searchprofile-images'             => 'Медифаг',
'searchprofile-everything'         => 'Массана',
'searchprofile-advanced'           => 'Шера я',
'searchprofile-articles-tooltip'   => '$1чу лахар',
'searchprofile-project-tooltip'    => '$1чу лахар',
'searchprofile-images-tooltip'     => 'Паьлий лахар',
'searchprofile-everything-tooltip' => 'Массадола оагIувний лахар (дувцама оагIувнаш чулоацаш)',
'searchprofile-advanced-tooltip'   => 'Iочуязаяь цIераренашках лаха',
'search-result-size'               => ' $1 ({{PLURAL:$2|1 дош|$2 дешаш}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 дакъа|$1 дакъаш}} ({{PLURAL:$2|$2 кIалцатег|$2 кIалцатегаш}}, {{PLURAL:$3|$3 паьла|$3 паьлий|$3 паьлий}})',
'search-redirect'                  => '($1 дехьачуяьккхар)',
'search-section'                   => ' (дакъа $1)',
'search-suggest'                   => 'Iа лохар из хила мега: $1',
'search-interwiki-caption'         => 'Гаргалон хьахьоадайтамаш',
'search-interwiki-default'         => '$1 толамчаш:',
'search-interwiki-more'            => '(кха)',
'search-mwsuggest-enabled'         => ' Хьехамашца',
'search-mwsuggest-disabled'        => ' Хьехамаш боацаш',
'search-relatedarticle'            => 'шоайл дола',
'searchrelated'                    => 'гаргара',
'searchall'                        => 'деррига',
'showingresultsheader'             => "{{PLURAL:$5|'''$1''' толамче укх '''$3''' долачарах|'''$1 — $2''' толамчаш укх '''$3''' долачарах}} '''$4'''а",
'nonefound'                        => "'''Зем лаца.''' Цхьа дола цIера аренаш мара лахалац.
''all:'' яха тIаоттарга пайдабе, массадола цIеран аренашкахь (дакъалаьцархой дуцамаш а, куцкепаш а, кхы дара а чулоацаш), е деза цIера аренаш Iочуязаде.",
'search-nonefound'                 => 'ДIахаттама нийсамаш корадаьдац.',
'powersearch'                      => ' Доккха тахкар',
'powersearch-legend'               => ' Доккха тахкар',
'powersearch-ns'                   => ' ЦIерий аренашкахь лахар',
'powersearch-redir'                => 'ДIа-хьа оагIувнаш гойта',
'powersearch-field'                => 'Лахар',
'powersearch-toggleall'            => 'Деррига',
'powersearch-togglenone'           => 'Цхьаккха',

# Quickbar
'qbsettings-none' => 'Цхьаккха',

# Preferences page
'preferences'               => 'Оттамаш',
'mypreferences'             => 'Оттамаш',
'prefsnologin'              => 'Шо чудаьнна дац',
'changepassword'            => 'КъайладIоaгIа дIахувцар',
'prefs-skin'                => 'БIагала куц',
'skin-preview'              => 'Хьажа',
'prefs-datetime'            => 'Таьрахьи сахьати',
'prefs-personal'            => 'Хьа хьай далам',
'prefs-rc'                  => 'Керда хувцамаш',
'prefs-watchlist'           => 'Теркама дагарче',
'prefs-watchlist-days'      => 'Ден дукхал',
'prefs-resetpass'           => 'КъайладIоагIа хувца',
'prefs-rendering'           => 'ТIера бIаса',
'saveprefs'                 => 'Дита',
'prefs-editing'             => 'ГIалатнийсдар',
'searchresultshead'         => 'Лахаp',
'timezonelegend'            => 'Сахьати юкъ:',
'localtime'                 => 'Вола/Йола моттиги ха:',
'timezoneregion-africa'     => 'Аьприк',
'timezoneregion-america'    => 'Iаьмрик',
'timezoneregion-antarctica' => 'Энтарцит',
'timezoneregion-arctic'     => 'Эрцит',
'timezoneregion-asia'       => 'Iаьзик',
'timezoneregion-atlantic'   => 'Iаьтланта форд',
'timezoneregion-australia'  => 'Устралик',
'timezoneregion-europe'     => 'Аьроп',
'timezoneregion-indian'     => 'ХIинда форд',
'timezoneregion-pacific'    => 'Тийна форд',
'prefs-searchoptions'       => 'Тохкама оттамаш',
'prefs-files'               => 'Паьлаш',
'youremail'                 => 'Д-хоамни:',
'username'                  => 'Дакъалаьцархочунна цIи:',
'yourrealname'              => 'Шун цIи:',
'yourlanguage'              => 'Мотт:',
'gender-male'               => 'МаIа',
'gender-female'             => 'Кхал',
'email'                     => 'Д-хоамни',
'prefs-help-email'          => 'Д-хоамни моттиг ала эшаш дац, амма новкъа даца, наггахь санна къайладIоагIа шоана дийцалой, цу тIа хьатIадайтаргда.',
'prefs-help-email-others'   => 'Кхыбола дакъалаьцархоша шоаца бувзам я йийшхургья шун оагIува тIа гIолла, д-хоамни хьаела ца езаш.',
'prefs-signature'           => 'Кулгяздар',

# User rights
'userrights-user-editname'    => 'Дакъалаьцархочунна цIи Iоязаде',
'editusergroup'               => 'Дакъалаьцархочунна тоабаш хувца',
'saveusergroups'              => 'Дакъалаьцархочунна тоабаш дита',
'userrights-groupsmember'     => 'Тоабий дакъалаьцархо:',
'userrights-reason'           => 'Бахьан:',
'userrights-changeable-col'   => 'Оаш хувца мегаш йола тоабаш',
'userrights-unchangeable-col' => 'Оаш хувца мегаш йоаца тоабаш',

# Groups
'group'       => 'Тоаб:',
'group-user'  => 'Дакъалаьцархой',
'group-bot'   => 'БIаташ',
'group-sysop' => 'Мазакулгалхой',
'group-all'   => '(деррига)',

'group-user-member'  => '{{GENDER:$1|дакъалаьцархо|дакъалаьцархо}}',
'group-bot-member'   => '{{GENDER:$1|бIат}}',
'group-sysop-member' => '{{GENDER:$1|мазакулгалхо}}',

'grouppage-user'  => '{{ns:project}}:Дакъалаьцархой',
'grouppage-bot'   => '{{ns:project}}:БIаташ',
'grouppage-sysop' => '{{ns:project}}:Мазакулгалхой',

# Rights
'right-read'       => 'ОагIувнаш деша',
'right-edit'       => 'ОагIувнаш хувца',
'right-createtalk' => 'дувцама оагIувний хьакхоллам',
'right-move'       => 'ОагIувний цIи хувца',
'right-movefile'   => 'Паьлий цIи хувца',

# User rights log
'rightslog'  => 'Дакъалаьцархочунна бокъона тептар',
'rightsnone' => '(а)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'Укх оагIуви дешам',
'action-edit' => 'Ер оагIув хувца',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|хувцам|хувцамаш}}',
'recentchanges'                   => 'Керда хувцамаш',
'recentchanges-legend'            => 'Керда хувцамий оттамаш',
'recentchangestext'               => 'КIалхагIа лоарамий доаламе тIехьара оагIувний хувцамаш дIаязадаь да{{grammar:genitive|{{SITENAME}}}}.',
'recentchanges-feed-description'  => 'Укх ларамца тIехьара массахувцамашт теркам бе.',
'recentchanges-label-newpage'     => 'Укх хувцамаца керда оагIув даь хиннад',
'recentchanges-label-minor'       => 'ЗIамига хувцам я',
'recentchanges-label-bot'         => 'Ер хувцам бIатаца яь е',
'recentchanges-label-unpatrolled' => 'Ер хувцам ший моттиге кхы дIадехьаяьккхаяц.',
'rcnote'                          => "{{PLURAL:$1|Тlехьара '''$1''' хувцам|Тlехьара '''$1''' хувцамаш}} дола '''$2''' {{PLURAL:$2|ден|деношкахь}}, укх сахьате $5 $4.",
'rcnotefrom'                      => 'КIалхагIа хувцамаш хьахьекха я <strong>$2</strong> денза (<strong>$1</strong> кхачалца).',
'rclistfrom'                      => '$1 тIара хувцамаш хьахьокха',
'rcshowhideminor'                 => 'зIамига хувцамаш $1',
'rcshowhidebots'                  => '$1 шабелхалой',
'rcshowhideliu'                   => 'Чубаьнначара дакъалаьцархочий $1',
'rcshowhideanons'                 => 'цIияьккханза дакъалаьцархой $1',
'rcshowhidepatr'                  => '$1 теркам даь хувцамаш',
'rcshowhidemine'                  => '$1 сай хувцамаш',
'rclinks'                         => '$2 динах<br />$3 $1 хинна тIехьара хувцамаш хьахьокха',
'diff'                            => 'кхы.',
'hist'                            => 'искар',
'hide'                            => 'Къайлдаккха',
'show'                            => 'Хьахьокха',
'minoreditletter'                 => 'м',
'newpageletter'                   => 'Н',
'boteditletter'                   => 'б',
'rc_categories_any'               => 'МоллагIа а',
'rc-enhanced-expand'              => 'Ма дарра чулоацамаш хьахьокха (JavaScriptаца)',
'rc-enhanced-hide'                => 'Ма дарра чулоацамаш къайладаккха',

# Recent changes linked
'recentchangeslinked'          => 'Гаргалон хувцамаш',
'recentchangeslinked-feed'     => 'Гаргалон хувцамаш',
'recentchangeslinked-toolbox'  => 'Гаргалон хувцамаш',
'recentchangeslinked-title'    => '$1ца хьалаьца хувцамаш',
'recentchangeslinked-noresult' => 'Укх заманашка гаргарон оагIувнаш тIа хувцамаш хиннаяц.',
'recentchangeslinked-summary'  => "Ер, Iинк яь йола оагIув (е укх цатегачу чуйоагIараш), дукха ха йоацаш хьийца оагIувнашкий дагарле я.
[[Special:Watchlist|Шун теркама дагарленашках]] чуйоагIа оагIувнаш '''белгалаяь я'''.",
'recentchangeslinked-page'     => 'ОагIува цIи',
'recentchangeslinked-to'       => 'ОагIувнаш тIа хувцамаш хьахьокха, хьахьекха йола оагIув тIа Iинкаш еш йола.',

# Upload
'upload'            => 'Паьл чуяьккха',
'uploadbtn'         => 'Паьл чуяьккха',
'uploadlogpage'     => 'Чуяьккхамий тептар',
'filedesc'          => 'Лоаца лоацам',
'fileuploadsummary' => 'Лоаца лоацам:',
'uploadedimage'     => '"[[$1]]" чуяьккхай',

'license'        => 'ЦIийяздар',
'license-header' => 'ЦIийяздар',

# Special:ListFiles
'imgfile'               => 'паьл',
'listfiles'             => 'Паьлий дагарче',
'listfiles_date'        => 'Денха',
'listfiles_name'        => 'Паьла цIи',
'listfiles_user'        => 'Дакъалаьцархо',
'listfiles_size'        => 'Дустам',
'listfiles_description' => 'Лоацам',
'listfiles_count'       => 'Доржамаш',

# File description page
'file-anchor-link'          => 'Паьл',
'filehist'                  => 'Паьла искар',
'filehist-help'             => 'Хьалхе паьла мишта хиннай хьожаpгволаш/йолаш, дентаьрах/сахьата тIа пIелга тIообе.',
'filehist-revert'           => 'юхаяьккха',
'filehist-current'          => 'xIанзара',
'filehist-datetime'         => 'Дентаьрах/Ха',
'filehist-thumb'            => 'ЗIамигасуртанче',
'filehist-thumbtext'        => '$1 доржаме зIамигсуртанчoa',
'filehist-user'             => 'Дакъалаьцархо',
'filehist-dimensions'       => 'ХIамана дустам',
'filehist-filesize'         => 'Паьла юстарал',
'filehist-comment'          => 'ХIамоалар',
'filehist-missing'          => 'Паьла йоацаш я',
'imagelinks'                => 'Паьлий пайда эца',
'linkstoimage'              => '{{PLURAL:$1|ТIехьайоагIа $1 оагIув Iинк ду|ТIехьайоагIа $1 оагIувнаш Iинкаш ду}} укх паьла тIа:',
'nolinkstoimage'            => 'Йола паьла тIа  Iинк ю оагIувнаш дац',
'sharedupload'              => 'Ер паьла $1чера я, кхыча хьахьоадайтамча хьахайраде йийшайолаш я.',
'sharedupload-desc-here'    => 'Ер паьл $1чара я, кхыдола хьахьоадайтамача хайрамбе йийш йолаш да.
Цун [$2 лоацама оагIувца] лоаца маIандар кIалхагIа латта.',
'uploadnewversion-linktext' => 'Укх паьлий керда бIаса чуяьккха',

# File reversion
'filerevert-comment' => 'Бахьан:',

# File deletion
'filedelete-comment'          => 'Бахьан:',
'filedelete-submit'           => 'ДIадаккха',
'filedelete-reason-otherlist' => 'Кхыдола бахьан',

# MIME search
'download' => 'хьачуяьккха',

# Unwatched pages
'unwatchedpages' => 'Теркамза оагIувнаш',

# Random page
'randompage' => 'Дагадоаца йоазув',

# Statistics
'statistics'       => 'Дагара куц',
'statistics-pages' => 'ОагIувнаш',

'disambiguationspage' => 'Template: ЦаI маIандоацар',

'brokenredirects-edit'   => 'хувца',
'brokenredirects-delete' => 'дIадаккха',

'withoutinterwiki-submit' => 'Хьахьокха',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|бIат|бIаташ}}',
'nmembers'      => '$1 {{PLURAL:$1|дакъалаьцархо|дакъалаьцархой}}',
'prefixindex'   => 'ОагIувнаший хьалхера цIи хьагойтар',
'shortpages'    => 'Лоаца оагIувнаш',
'longpages'     => 'Доккхий оагIувнаш',
'usercreated'   => '{{GENDER:$3|Чуваьннав|Чуяьннай}} $1  $2',
'newpages'      => 'Керда оагIувнаш',
'move'          => 'ЦIи хувца',
'movethispage'  => 'Укх оагIува цIи хувца',
'pager-newer-n' => '{{PLURAL:$1|кердагIа дара|кердагIа дараш|кердагIа долачаьрахь}} $1',
'pager-older-n' => '{{PLURAL:$1|къаьнара дара|къаьнара дараш|къаьнара долaчаьрахь}} $1',

# Book sources
'booksources'               => 'Китабий гIувам',
'booksources-search-legend' => 'Китаба лоаца маIандара тохкам',
'booksources-go'            => 'Лаха',

# Special:Log
'log' => 'Тептараш',

# Special:AllPages
'allpages'       => 'Еррига оагIувнаш',
'alphaindexline' => '$1гIара $2гIачу',
'prevpage'       => '($1) хьалхара оагIув',
'allpagesfrom'   => 'Цу тайпара ювлаж йола оагIувнаш белгал е:',
'allpagesto'     => 'Укх оагIувнаш тIа бIарга дита:',
'allarticles'    => 'Еррига оагIувнаш',
'allpagessubmit' => 'Кхоачашде',

# Special:Categories
'categories' => 'Цатегаш',

# Special:LinkSearch
'linksearch'      => 'Т|ера|инкаш лахар',
'linksearch-ok'   => 'Лаха',
'linksearch-line' => '$1 тIа Iинк $2 юкъера',

# Special:Log/newusers
'newuserlogpage' => 'Дакъалаьцархоший дIаязбeнна таптар',

# Special:ListGroupRights
'listgrouprights-members' => '(тоабий дагарче)',

# E-mail user
'emailuser' => 'Дакъалаьцархочоа д-хоамни:',

# Watchlist
'watchlist'         => 'Теркама дагарче',
'mywatchlist'       => 'Теркама дагарле',
'watchlistfor2'     => '$1 $2 царна',
'addedwatchtext'    => '"[[:$1]]" оагIув, шун [[Special:Watchlist|теркама дагаршкахь]] чуяккха я. 
Техьара мел йола укх оагIувни хувцамаш цу дагаршкахь хоам беш хургья. Вешта [[Special:RecentChanges|керда хувцама дагаршкаехь]] сома къоалмаца хьакъоастлуш хургья.',
'removedwatchtext'  => '"[[:$1]]" оагIув, шун [[Special:Watchlist|теркама дарагчера]] дIаяккха хиннай.',
'watch'             => 'Тохкам бе',
'watchthispage'     => 'Укх оагIува теркам бе',
'unwatch'           => 'Лора ма де',
'watchlist-details' => 'Шун теркама дагарченгахь йола  $1 {{PLURAL:$1|оагIув|оагIувнаш}}, дувцама оагIувнаш ца лоархIаш.',
'wlshowlast'        => 'Тlехьара $1 сахьаташ $2 денош $3 хьахьокха',
'watchlist-options' => 'Зем баккха дагарена хувцамаш',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Тохкам беча оагIув тIа тIадаккха',
'unwatching' => 'Тохкам беча оагIув тIера дIадаккха',

# Delete
'deletepage'            => 'ОагIув дIаяьккха',
'confirmdeletetext'     => 'Оаш оагIувни (е сурти) барча дIадаккхар хьайийхай кха еррига хувцамий искар долама ковчера. 
Дехар да, жоп дала, шоай из бокъонцахь де безам болаш да, шоай даьчоахь хургдолчоахь кхеташ долга, [[{{MediaWiki:Policy-url}}]] декъамачу Iоязадаь дола адаташ ца из деш долга.',
'actioncomplete'        => 'ДулархIам баьб',
'actionfailed'          => 'Оттам даьдац',
'deletedtext'           => '"$1" дIаяьккха хиннай.
ТIехьара дIадаьккха дагарчена хьожаргволаш/хьожаргьйолаш, $2 хьажа.',
'dellogpage'            => 'ДIадаккхара тептар',
'deletecomment'         => 'Бахьан:',
'deleteotherreason'     => 'Кхыдола бахьан/тIатохар:',
'deletereasonotherlist' => 'Кхыдола бахьан',

# Rollback
'rollbacklink' => 'юхаяьккха',

# Protect
'protectlogpage'              => 'Лорама тептар',
'protectedarticle'            => '"[[$1]]" оагIув лорам деж я',
'modifiedarticleprotection'   => '"[[$1]]" оагIувни лорама лагIа хувцаяьннай',
'protectcomment'              => 'Бахьан:',
'protectexpiry'               => 'Кхоачалуш латта:',
'protect_expiry_invalid'      => 'Чакхабоала лорама харца ха',
'protect_expiry_old'          => 'Чакхайоала ха - яха зама я.',
'protect-text'                => "'''$1''' укхаз шоана шоай оагIув лорамлагIа хувца a бIаргтасса a йийш хургья.",
'protect-locked-access'       => "Шун лархIама йоазуви нидза кхоачаш бац оагIувни лорама лагIа хувца. '''$1''' оагIувни дIаоттамаш:",
'protect-cascadeon'           => '{{PLURAL:$1|КIалхахь хьагойташ йола оагIувчу|КIалхахь хьагойташ йола оагIувнашчу}} ер оагIув чуяккха халарахь, лорам Iоттая я, хурхала лорам Iоттая я. Укх оагIувни лорама лагIа хувца йийш йолаш я, амма хурхала лорам хувцлургдац.',
'protect-default'             => 'Лорамза',
'protect-fallback'            => '"$1" пурам эша',
'protect-level-autoconfirmed' => 'Керда а, дакъалаьцабоацачаьрахь а лораде',
'protect-level-sysop'         => 'Мазакулгалхо мара чувала бокъо яц',
'protect-summary-cascade'     => 'хурхала',
'protect-expiring'            => 'чакхайоала $1 (UTC)',
'protect-cascade'             => 'Укх оагIувач чуяьккха оагIуваш лорае (хурхала лорам)',
'protect-cantedit'            => 'Шун укх оагIувни лорама лагIа хувца мегаш дац, гIалатнийсдара шун бокъо йоацандаь.',
'restriction-type'            => 'Бокъонаш:',
'restriction-level'           => 'Чувоала лагIа:',

# Restrictions (nouns)
'restriction-edit'   => 'ГIалатнийсдар',
'restriction-move'   => 'ЦIи хувцаp',
'restriction-create' => 'Кхоллам',
'restriction-upload' => 'Чудаккхар',

# Undelete
'undeletelink'     => 'БIаргтасса/юхаметтаоттаде',
'undeleteviewlink' => 'бIаргтасса',

# Namespace form on various pages
'namespace'      => 'ЦIерий аренаш',
'invert'         => 'Хьаржар юхадаккха',
'blanknamespace' => '(Корта)',

# Contributions
'contributions'       => 'Дакъалаьцархочунна къахьегам',
'contributions-title' => '$1 дакъалаьцархочунна къахьегам',
'mycontris'           => 'Са къахьегам',
'contribsub2'         => '$1 ($2) баь болх',
'uctop'               => '(тIехьара)',
'month'               => 'Цхьа бутт хьалхагIа (кхы хьалхагIа)',
'year'                => 'Цхьа шу хьалхагIа (кхы хьалхагIа):',

'sp-contributions-newbies'  => 'Керда даязья йоазоначера мара баь бола къахьегам ма хьокха',
'sp-contributions-blocklog' => 'чIегаш',
'sp-contributions-uploads'  => 'чуяьккхамаш',
'sp-contributions-logs'     => 'тептараш',
'sp-contributions-talk'     => 'дувцам',
'sp-contributions-search'   => 'Къахьегама лахар',
'sp-contributions-username' => 'IP-моттиг е цIи:',
'sp-contributions-toponly'  => 'ТIехьара доржамаш лоархаш дола хувцамаш мара ма хьокха',
'sp-contributions-submit'   => 'Хьалаха',

# What links here
'whatlinkshere'            => 'Iинкаш укхаза',
'whatlinkshere-title'      => '"$1" тIа Iинкаш еш йола оагIувнаш',
'whatlinkshere-page'       => 'ОагIув',
'linkshere'                => "ТIехьара оагIувнаш '''[[:$1]]''' тIа Iинкаш ю:",
'nolinkshere'              => "'''[[:$1]]''' оагIув тIа, кхыдола оагIувашкара Iинкаш йоацаш я",
'isredirect'               => 'дIа-хьа оагIув',
'istemplate'               => 'чудаккхар',
'isimage'                  => 'паьла Iинк',
'whatlinkshere-prev'       => '{{PLURAL:$1|хьалхайоагIа|хьалхайоагIараш}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|тIехьайоагIа|тIехьайоагIараш}} $1',
'whatlinkshere-links'      => '← Iинкаш',
'whatlinkshere-hideredirs' => '$1 дIа-хьа чуяьккхамаш',
'whatlinkshere-hidetrans'  => '$1 чуяьккхамаш',
'whatlinkshere-hidelinks'  => '$1 Iинкаш',
'whatlinkshere-hideimages' => '$1 суртIинкаш',
'whatlinkshere-filters'    => 'ЦIенъераш',

# Block/unblock
'blockip'                  => 'Дакъалаьцархочунна чIега бола',
'ipboptions'               => '2 сахьат:2 hours,1 ди:1 day,3 ди:3 days,1 кIира:1 week,2 кIира:2 weeks,1 бутт:1 month,3 бутт:3 months,6 бутт:6 months,1 шу:1 year,сиха ца луш:infinite',
'ipblocklist'              => 'ЧIега бела дакъалаьцархой',
'blocklink'                => 'чIегa тоха',
'unblocklink'              => 'чIега баста',
'change-blocklink'         => 'ЧIегатохар хувца',
'contribslink'             => 'къахьегам',
'blocklogpage'             => 'ЧIегаш тoха таптар',
'blocklogentry'            => '[[$1]] чIега белаб,  $2 $3 ха ялалца',
'unblocklogentry'          => '$1 юха яста я',
'block-log-flags-nocreate' => 'ЛархIамий дагарчена цIи яьккхар пурам янза я.',
'blockme'                  => 'ЧIега бола сона',
'proxyblocksuccess'        => 'Хьадаьд.',

# Move page
'move-page-legend' => 'ОагIува цIи хувца',
'movepagetext'     => "КIалхара кепаца болхабеча, оаш оагIувни цIи хувцаргья, цунна хувцамий тептар кхыйола меттиге дIачудоаккхаш.
КIаьнара цIерахь керда цIерий дIачудаккхам хургда.
КIаьнара цIера тIа даь дола дIачудаккхамаш, шун ший лоIамахь кердадаккха йийш хургья.
Из оаш ца дой, дехар да, [[Special:DoubleRedirects|шолха]] кхы [[Special:BrokenRedirects|вIашагIаяккха дIачудаккхамий]] кардоламахь хьажа.
Оаш жоп лу, шоай чуяккха йола Iинкаш, даим болхбеш хургдолга.

Зем бахка, оагIувни цIи хувцалургьяц, изза мо цIи йолаш оагIув хилача. 
Йолаш йола оагIув хувца йийш яц, амма хийца йола оагIув юха хьахувца йийш я. 

'''Хоамхайтар'''

ЦIи хувцар, йовзаш йола оагIувнаший, доккха а цаьхха а хувцамшка дIатIадала мегаш да.
Дехар да, оаш дIахо болх белаьхь, хургдола хIама кхеташ долга, кхеталаш.",
'movepagetalktext' => "ТIатеха дувцама оагIув, ший лоIамахь цIи хувлургья, '''ер дага а доацар, доаца:'''

*Изза мо цIи йолаш яьсса дувцама оагIув я е
*Оаш кIалхахь белгало даьдац.

Из иштта дале, кулги новкъосталца оагIувнаш вIашагIатоха  е дIадехьаяккха деза шун.",
'movearticle'      => 'ОагIува цIи хувца',
'newtitle'         => 'Керда цIи',
'move-watch'       => 'Ер оагIув теркама дагаршкахь чуяккха',
'movepagebtn'      => 'ОагIува цIи хувца',
'pagemovedsub'     => 'ОагIув керд цIи тилла я',
'movepage-moved'   => '\'\'\'"$1" оагув "$2" хьийца я\'\'\'',
'articleexists'    => 'Изза мо цIи йола оагIув, йолаш я е оаш тила цIи мегаш яц.
Дехар да, кхыйола цIи хьаржа.',
'talkexists'       => "'''ОагIувни цIи хьийца хиннай, амма дувцама оагIувни цIи хувца мегаш яц, изза мо цIи йолаш оагIув йоландаь. Дехар да, кулга новкъосталца цхьанна вIашагIатоха уш.'''",
'movedto'          => 'керда цIи тилла я',
'movetalk'         => 'МаIан чулоаца дувцама оагIувни цIи хувца',
'movelogpage'      => 'Хувцама тептар',
'movereason'       => 'Бахьан',
'revertmove'       => 'юхаяьккха',

# Export
'export' => 'ОагIувий эхфортам',

# Namespace 8 related
'allmessagesname'           => 'ЦIи',
'allmessagesdefault'        => 'Сатийна улла яздам',
'allmessages-filter-all'    => 'Дерригаш',
'allmessages-language'      => 'Мотт:',
'allmessages-filter-submit' => 'Дехьавала/яла',

# Thumbnails
'thumbnail-more'  => 'Хьадоккхаде',
'thumbnail_error' => 'ЗIамигасуртанчий кхеллама гIалат: $1',

# Special:Import
'import-upload-filename' => 'ПаьлацIи:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Дакъалаьцархочунна оагIув',
'tooltip-pt-mytalk'               => 'Шун дувцамий оагIув',
'tooltip-pt-preferences'          => 'Шун оттамаш',
'tooltip-pt-watchlist'            => 'ОоагIувна дагарле, шо бIаргалокхаш йола',
'tooltip-pt-mycontris'            => 'Шун хувцамаш',
'tooltip-pt-login'                => 'Укхаза хьай цIи аьле чувала/яла йиша я, амма чуцаваьлача/ялача хIама а дац',
'tooltip-pt-logout'               => 'Аравала/яла',
'tooltip-ca-talk'                 => 'ОагIувна чулоацаме дувцам',
'tooltip-ca-edit'                 => 'Ер оагIув хувца йишйолаш я. Дехар да, Iалаш елаьх, хьалхе бIаргтассама оагIув тIа бIаргтасса.',
'tooltip-ca-addsection'           => 'Керда декъам хьаде',
'tooltip-ca-viewsource'           => 'Ер оагIув хувцамах лораяь я, амма шун цунна гIувамага хьажа бокъо я.',
'tooltip-ca-history'              => 'Укх оагIувни хувцама таптар',
'tooltip-ca-protect'              => 'Eр оагIув лорае',
'tooltip-ca-delete'               => 'Ер оагIув дIаяькха',
'tooltip-ca-move'                 => 'Укх оагIува цIи хувца',
'tooltip-ca-watch'                => 'Ер оагIув теркам беча каьхата тIа тIаяьккха',
'tooltip-ca-unwatch'              => 'Ер оагIув теркам беча каьхата тIара дIаяькха',
'tooltip-search'                  => 'Цу тайпара дош лаха {{SITENAME}}',
'tooltip-search-go'               => 'Изза мо цӀи йолаш оагӀув тӀa дехьавала',
'tooltip-search-fulltext'         => 'Изза мо яздам долаш оагӀувнаш лаха',
'tooltip-p-logo'                  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage'              => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage-description'  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-portal'                => 'Хьахьоадайтамахь лаьца, хьа де йишдар, фу а мичча а йоала',
'tooltip-n-currentevents'         => 'ХIанзара хоаман дагарле',
'tooltip-n-recentchanges'         => 'ТӀехьара хувцамий дагарче',
'tooltip-n-randompage'            => 'Бе йоаца оагӀув ела',
'tooltip-n-help'                  => 'Новкъостала моттиг',
'tooltip-t-whatlinkshere'         => 'Массайола оагIувий дагарле, укх оагIув тIа Iинкаш луш йола',
'tooltip-t-recentchangeslinked'   => 'ОагIувнаш тIа тIехьара хувцамаш, укх оагIувнера Iинк яь йола',
'tooltip-feed-rss'                => 'Укх оагIувна RSSчу гойтар',
'tooltip-feed-atom'               => 'Укх оаг|увна Atomчу гойтар',
'tooltip-t-contributions'         => 'Укх дакъалаьцархочу хьийца йола оагIувнаш хьахьокха',
'tooltip-t-emailuser'             => 'Укх дакъалаьцархочоа зIы яхьийта',
'tooltip-t-upload'                => 'Паьлаш чуяьккха',
'tooltip-t-specialpages'          => 'ГIулакха оагIувний дагарчe',
'tooltip-t-print'                 => 'Укх оаугIувна каьхатзарбане доржам',
'tooltip-t-permalink'             => 'Укх оагIув доржама даим латта Iинк',
'tooltip-ca-nstab-main'           => 'Йоазува чулоацам',
'tooltip-ca-nstab-user'           => 'Дакъалаьцархочунна ший оагIув',
'tooltip-ca-nstab-special'        => 'Ер гIулакха оагIув я, из хувца хьо бокъо йолаш вац/яц.',
'tooltip-ca-nstab-project'        => 'Хьахьоадайтама оагIув',
'tooltip-ca-nstab-image'          => 'Паьла оагIув',
'tooltip-ca-nstab-template'       => 'ЧIабала оагIув',
'tooltip-ca-nstab-category'       => 'Цатега оагIув',
'tooltip-minoredit'               => 'Ер хувцар башха доаца санна белгалде',
'tooltip-save'                    => 'Хувцамаш кходе',
'tooltip-preview'                 => 'ОагIув тIа хьалхе бIаргтассар, дехар да, оагIув дIаязъелаьх, цун теркам бе.',
'tooltip-diff'                    => 'Яздам тIа яь йола хувцамаш хьахьокха',
'tooltip-compareselectedversions' => 'Укх оагIувни шин доржамаш тIа юкъера хувцамаш зе.',
'tooltip-watch'                   => 'Ер оагIув теркам беча каьхата тIа яькха',
'tooltip-rollback'                => 'ГIалaтнийсадаро тIехьара яь йола хувцамаш, пIелг тоIобе дIаяьккха.',
'tooltip-undo'                    => 'Баь хувцам дIабаьккхe, бIаргатассам хьахьокха, кара дале, дIаяьккха бахьан Iочуязаде моттигаца.',
'tooltip-summary'                 => 'Лоаца чулоацам Iочуязаде',

# Browsing diffs
'previousdiff' => '← Хьалхара хувцам',
'nextdiff'     => 'ТIайоагIа хувцам',

# Media information
'file-info-size' => '$1 × $2 фихсам, паьла дустам: $3, MIME-тайп: $4',
'file-nohires'   => 'Укхал доккхагIа доржам дац',
'svg-long-desc'  => 'SVG-паьл, $1 × $2 фихелашца, паьла дустам: $3',
'show-big-image' => 'Хьадоккхадаь сурт',

# Special:NewFiles
'noimages' => 'Суртaш бIаргагуш дац.',
'ilsubmit' => 'Лаха',

# Bad image list
'bad_image_list' => 'Бустам цу тайпара хила беза:

Дагарлен хьаракъаш мара лоарх|аш хургьяц (укх тамагIалгацa * дувлашду мугIараш).
МугIарен хьалхара Iинк, сурт Iоттае пурам доаца Iинка, хила еза. 
Цу мугIара тIехьайоагIа Iинкаш, арадаккхар мо лоарх|аш хургья, вешта аьлча, йоазувашка чуIоттаде мегаш дола сурт санна ларх|а мега.',

# Metadata
'metadata'          => 'МетахIамаш',
'metadata-help'     => 'Паьлас чулоаца, кхыдола хIамаш, таьрахьа суртдоакхаргца е тIагIолладоакхаргца чудакхаш дола. Хьаяь паьл, гIалатахь мукъадаькха хинна дале, хьахьокхаш дола сурт, деррига хIамаш чулоацаргдац.',
'metadata-expand'   => 'Кхыдола хIамаш хьахьокха',
'metadata-collapse' => 'Кхыдола хIамаш къайладаккха',
'metadata-fields'   => 'Укх дагарченгахь дагaрадаь метахIамаша суртий мугIаш, сурт оагIув тIа хьахьекха хургья, чуерзaяь метахIамашийца. Вож мугIанаш ха йоалаш къайла хургья.
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
'exif-imagewidth'       => 'Шерал',
'exif-imagelength'      => 'Лакхал',
'exif-imagedescription' => 'Сурта цIи',
'exif-artist'           => 'Яздархо',
'exif-colorspace'       => 'Басара аре',
'exif-pixelydimension'  => 'Сурта шерал',
'exif-pixelxdimension'  => 'Сурта лакхал',
'exif-writer'           => 'Яздама да',
'exif-languagecode'     => 'Мотт',
'exif-iimcategory'      => 'Цатег',

'exif-scenecapturetype-1' => 'ЛаьттабIаса',
'exif-scenecapturetype-2' => 'Сурт',

'exif-iimcategory-edu' => 'Дешар',
'exif-iimcategory-evn' => 'Арен буне',
'exif-iimcategory-hth' => 'Могар',
'exif-iimcategory-hum' => 'Адамий искараш',
'exif-iimcategory-rel' => 'Дини тешари',
'exif-iimcategory-sci' => 'Iилмаи кулгболхи',
'exif-iimcategory-soi' => 'Сагий хаттараш',
'exif-iimcategory-spo' => 'Нидзоамал',
'exif-iimcategory-war' => 'ТIемаш, кховсамаши латтараши',
'exif-iimcategory-wea' => 'Хаоттам',

# External editor support
'edit-externally'      => 'Йола болхоагIувца паьла гIалатах мукъаяьккха',
'edit-externally-help' => '(ма даррачунга хьажа [//www.mediawiki.org/wiki/Manual:External_editors хьаоттама кулгалхо])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'деррига',
'namespacesall' => 'деррига',
'monthsall'     => 'деррига',
'limitall'      => 'деррига',

# action=purge
'confirm_purge_button' => 'ХIаа',

# Multipage image navigation
'imgmultigo'   => 'Дехьавала/яла!',
'imgmultigoto' => '$1 оагIув тIа дехьавала',

# Table pager
'table_pager_limit_submit' => 'Кхоачашде',

# Watchlist editing tools
'watchlisttools-view' => 'Дагарчера оагIувнаш тIа хувцамаш',
'watchlisttools-edit' => 'Дагарче хьажа/хувца',
'watchlisttools-raw'  => 'Яздам мо хувца',

# Core parser functions
'duplicate-defaultsort' => 'Зем бе. Сатийна дIа-хьа хьоржама доагI "$2" хьалхара сатийна дIа-хьа хьоржама доагI "$1" хьахьоржа.',

# Special:Version
'version'                  => 'Доржам',
'version-specialpages'     => 'ГIулакхий оагIувнаш',
'version-version'          => '(Доржам $1)',
'version-software-version' => 'Доржам',

# Special:FilePath
'filepath'        => 'Паьлачу никъ',
'filepath-page'   => 'Паьл:',
'filepath-submit' => 'Дехьавала/яла',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'ПаьлацIи:',
'fileduplicatesearch-submit'   => 'Лаха',

# Special:SpecialPages
'specialpages'                 => 'ГIулакхий оагIувнаш',
'specialpages-group-users'     => 'Дакъалаьцархой, цара бокъо',
'specialpages-group-pages'     => 'ОагIувний дагарченаш',
'specialpages-group-pagetools' => 'ОагIувнаша гIирсаш',

# External image whitelist
'external_image_whitelist' => '#Ер мугI ший долаш тайпара дита<pre>
#Каст-каста оаламаш укхаза дIаязаде(юкъе дола дакъа //)
#арара суртий URLца дIанийсалургда уш.
#Пайдан дола, сурташ мо хьахьекха хургья, дахIодараш, сурта тIа Iинкаш мо хуpгья хьахьекха.
#Укх # тамагIалгаца дIадувлаш дола мугIанаш, оалам мо лоархаш да.
#МугIанаш яздaтакха каьда да

#Каст-каста оаламаш укх мугIа лакхе дIаязаде. Из мугI ший долаш тайпара дита</pre>',

# Special:Tags
'tag-filter'           => '[[Special:Tags|Йоазоний]] цIенаярг:',
'tag-filter-submit'    => 'ЦIенъе',
'tags-title'           => 'Йоазонаш',
'tags-tag'             => 'Йоазон цIи',
'tags-hitcount-header' => 'Белгалаяь хувцамаш',
'tags-edit'            => 'хувца',
'tags-hitcount'        => '$1 {{PLURAL:$1|хувцам|хувцамаш}}',

# Special:ComparePages
'compare-page1' => '1. ОагIув',
'compare-page2' => '2. ОагIув',
'compare-rev1'  => '1. Доржам',
'compare-rev2'  => '2. Доржам',

# Database error messages
'dberr-header' => 'Укх массано халонаш ловш латта',

# HTML forms
'htmlform-submit'              => 'ДIадахьийта',
'htmlform-reset'               => 'Хувцамаш юхадаккха',
'htmlform-selectorother-other' => 'Кхыдола',

);
