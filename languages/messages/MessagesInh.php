<?php
/** Ingush (ГІалгІай Ğalğaj)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amire80
 * @author Sapral Mikail
 * @author Tagir
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'               => 'Ӏинкаш белгалде:',
'tog-highlightbroken'         => 'Йоаца Ӏинкаш хьахокха <a href="" class="new">ишта</a> (е вешта<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Яздам оагӀува шоралца хьанийсъе',
'tog-hideminor'               => 'ЗӀамига хувцамаш керда дӀахувцама дагаршкахь къайлаяккха',
'tog-hidepatrolled'           => 'Керда хувцама дагарчеча дӀанийсъя хувцамаш къайлаяккха',
'tog-newpageshidepatrolled'   => 'Керда оагӀувни дагарчеча дӀанийсъя хувцамаш къайлаяккха',
'tog-extendwatchlist'         => 'Шеръя теркама дагарче, массайола хувцамаш чулоацаш',
'tog-usenewrc'                => 'Тоая керда хувцама дагаршкара пайда эца (JavaScript эша)',
'tog-numberheadings'          => 'Кертера-деша таьрахь ший лоӀамаца оттайе',
'tog-showtoolbar'             => 'ГӀалатнийcдара юкъе лакхера гӀирсий гартакх хьахокха (JavaScript)',
'tog-editondblclick'          => 'Шозза цлицкацa oагӀув хувца (JavaScript)',
'tog-editsection'             => 'ХӀара дакъа "хувца" яха Ӏинк хьахокха',
'tog-editsectiononrightclick' => 'Декъам хувца кертмугӀа аьтта цлицка я (JavaScript)',
'tog-showtoc'                 => 'Кортанче хьокха (кхьаннена дукхагӀа кертмугӀанаш йoлa оагӀувна)',
'tog-rememberpassword'        => '(укх $1 {{PLURAL:$1|ден|деношкахь}}) мара са чувалара/чуялара дагалоаца дезаш дац',
'tog-watchcreations'          => 'Аз яь йола оагӀувнаш теркама дагарчеча чуяккха',
'tog-watchdefault'            => 'Аз хийца йола оагӀувнаш теркама дагарчеча чуяккха',
'tog-watchmoves'              => 'Аз цӀи хийца йола оагӀувнаш теркама дагарчеча чуяккха',
'tog-watchdeletion'           => 'Аз дӀаяккха йола оагӀувнаш теркама дагарчеча чуяккха',
'tog-minordefault'            => 'Теркамза хувцамаш лоархӀамза белгалде',
'tog-previewontop'            => 'ГӀалатнийсдара кора хьалхе бӀаргтассам оттае',
'tog-previewonfirst'          => 'ГӀалатнийсдаре дехьавоалаш/йоалаш бӀаргтассам хьахьокха',
'tog-nocache'                 => 'Укхазара оагӀувнаший лочкъараш дӀадоаде',
'tog-enotifwatchlistpages'    => 'ОагӀувнаший хувцамахь теркама дагарчера лаьца, д-фоштаца хоам бе',
'tog-enotifusertalkpages'     => 'Са дувцама оагӀув тӀа хувцамаш хилача, д-фоштаца хоам бе',
'tog-enotifminoredits'        => 'Геттара зӀамига хувцамаш хилача, д-фоштаца хоам бе',
'tog-enotifrevealaddr'        => 'ЗӀы хоамаш тӀа са фоштмоттиг хьахьокха',
'tog-shownumberswatching'     => 'Ший теркама дагарченгахь оагӀув чулаьца бола дакъалаьцархой таьрах хьахьокха',
'tog-fancysig'                => 'Ший кулга яздара вики-хоамбаккхам (ший лоӀаме Ӏинка йоацаш)',
'tog-externaleditor'          => 'Арена гӀалатнийсдарца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархара ший-тайпара оттам эша; [//www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-externaldiff'            => 'Арена бӀасакхосса болхоагӀувца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархара ший-тайпара оттам эша; [//www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-showjumplinks'           => '"Дехьадала" яха новкъостала Ӏинк хьахьокха',
'tog-uselivepreview'          => 'Сиха бӀарахьажар (JavaScript) (Экспериментально)',
'tog-forceeditsummary'        => 'Хоам бе, хувцамий лоацам белгал даь деци',
'tog-watchlisthideown'        => 'Са хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthidebots'       => 'БӀатий хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideminor'      => 'Са зӀамига хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideliu'        => 'Чубаьнна дакъалаьцархой хувцамаш теркама дагaрчеча къайлаяккха',
'tog-watchlisthideanons'      => 'ЦӀи йоаца дакъалаьцархой хувцамаш теркама дагрчеча къайлаяккха',
'tog-watchlisthidepatrolled'  => 'Теркама дагарчера дӀанийсъя хувцамаш къайлаяккха',
'tog-ccmeonemails'            => 'Аз дакъалаьцархошоа дахта каьхаташ са д-фошта тӀа хьатӀадайта',
'tog-diffonly'                => 'Диста къал йоалаж йола оагӀувна дакъа ма хьокха',
'tog-showhiddencats'          => 'Къайла цатегаш хьахьокха',

'underline-always'  => 'Массаза',
'underline-never'   => 'ЦӀаккха',
'underline-default' => 'МазбӀарглокхарий оттамаш хайрамбе',

# Dates
'sunday'        => 'КӀиранди',
'monday'        => 'Оршот',
'tuesday'       => 'Шинара',
'wednesday'     => 'Кхаьра',
'thursday'      => 'Ера',
'friday'        => 'ПӀаьраска',
'saturday'      => 'Шоатта',
'sun'           => 'КӀи',
'mon'           => 'Орш',
'tue'           => 'Шин',
'wed'           => 'Кха',
'thu'           => 'Ера',
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
'pagecategories'                 => '{{PLURAL:$1|Цатег|Цатегаш}}',
'category_header'                => '"$1" Цатегчу оагӀувнаш',
'subcategories'                  => 'Чурацатегаш',
'category-media-header'          => '"$1" Цатегчу паьлаш',
'category-empty'                 => "''Укх цатегчоа цхьаккха оагӀувнаш е паьлаш яц.''",
'hidden-categories'              => '{{PLURAL:$1|Къайла цатег|Къайла цатегаш}}',
'hidden-category-category'       => 'Къайла цатегаш',
'category-subcat-count'          => '{{PLURAL:$2|Йола цатег тӀехьара бухцатег чулоаца.|{{PLURAL:$1|$1 бухцатег хьахекха я|$1 бухцатегаш хьахекха я}} $2 йолачара.}}',
'category-subcat-count-limited'  => 'Укх цатегий {{PLURAL:$1|$1 кӀалцатег|$1 кӀалцатегаш}}.',
'category-article-count'         => '{{PLURAL:$2|Йола цатег цхьа оагӀув мара чулоацац.|{{PLURAL:$1|$1 оагӀув хьахекха я|$1 оагӀувнаш хьахекха я}} укх цатега $2 йолачарахь.}}',
'category-article-count-limited' => 'Укх цатегчоахь {{PLURAL:$1|$1 оагӀув|$1 оагӀувнаш|}}.',
'category-file-count'            => '{{PLURAL:$2|Укх цатего ца паьла мара чулоацац.|{{PLURAL:$1|$1 паьла хьахьекха я|$1 паьлаш хьахьекха я}} укх цатегий $2 долачаьрахь.}}',
'category-file-count-limited'    => 'Укх цатегчоахь {{PLURAL:$1|$1 паьл|$1 паьлаш}}.',
'listingcontinuesabbrev'         => 'дӀахо',
'noindex-category'               => 'Моттигза оагӀувнаш',
'broken-file-category'           => 'Болхбеш йоаца паьла Ӏинкашца оагӀувнаш',

'about'         => 'Лоацам',
'article'       => 'Йоазув',
'newwindow'     => '(керда кора)',
'cancel'        => 'Юхавал',
'moredotdotdot' => 'ДӀахо',
'mypage'        => 'Са оагӀув',
'mytalk'        => 'Са дувцама оагӀув',
'anontalk'      => 'Укх IP-моттига дувцам',
'navigation'    => 'Никътахкар',
'and'           => '&#32;кха',

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
'vector-simplesearch-preference' => 'Яьржа лахарий довзамаш чуяккха (Vector skin only)',
'vector-view-create'             => 'Кхоллам',
'vector-view-edit'               => 'Хувцар',
'vector-view-history'            => 'Искар',
'vector-view-view'               => 'Дешам',
'vector-view-viewsource'         => 'ЗӀембаккхама бӀаргтассам',
'actions'                        => 'Дулархам',
'namespaces'                     => 'ЦӀерий аренаш',
'variants'                       => 'Дошаламаш',

'errorpagetitle'    => 'ГӀалат',
'returnto'          => '$1 оагӀув тӀа юхавалар',
'tagline'           => 'Кечал укхазара я {{SITENAME}}',
'help'              => 'Новкъoстал',
'search'            => 'Тохкам',
'searchbutton'      => 'Хьалаха',
'go'                => 'Дехьавала',
'searcharticle'     => 'Дехьавала',
'history'           => 'искар',
'history_short'     => 'Искар',
'updatedmarker'     => 'Со ханача денца хувцамаш хиннaй',
'printableversion'  => 'Каьхати зарба бӀаса',
'permalink'         => 'Даим латта Ӏинк',
'print'             => 'Каьхат арадаккха',
'view'              => 'БӀаргтассар',
'edit'              => 'Хувца',
'create'            => 'Хьаде',
'editthispage'      => 'Ер оагӀув хувца',
'create-this-page'  => 'Ep oагӀув хьае',
'delete'            => 'ДӀадаккха',
'deletethispage'    => 'Ер оагӀув дӀаяккха',
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
'personaltools'     => 'Са гӀирсаш',
'postcomment'       => 'Керда декъам',
'articlepage'       => 'Йоазув тӀа бӀаргтасса',
'talk'              => 'Дувцам',
'views'             => 'БӀаргтассараш',
'toolbox'           => 'ГӀирсаш',
'userpage'          => 'Дакъалаьцачунна оагӀуве бӀаргтасса',
'projectpage'       => 'Хьахьоадайтама оагӀуве бӀаргтасса',
'imagepage'         => 'Паьла оагӀув тӀа бӀаргтасса',
'mediawikipage'     => 'Xоама оагӀув хьахокха',
'templatepage'      => 'ЧӀабла оагӀув тӀа бӀаргтасса',
'viewhelppage'      => 'ГӀо деха',
'categorypage'      => 'Цатега оагӀув тӀа бӀаргтасса',
'viewtalkpage'      => 'Дувцамага бӀаргтасса',
'otherlanguages'    => 'Кхыча меттаxь',
'redirectedfrom'    => '($1 тӀера хьадейта да)',
'redirectpagesub'   => 'ДӀа-хьа дайта оагӀув',
'lastmodifiedat'    => 'Укх оагӀув тӀехьара  хувцам: $2, $1.',
'viewcount'         => 'Укх оагӀув тӀа бӀаргтасса хиннад {{PLURAL:$1|цкъа|$1 шозза}}.',
'protectedpage'     => 'Лорама оагӀув',
'jumpto'            => 'Укхаза дехьавала:',
'jumptonavigation'  => 'никътахкар',
'jumptosearch'      => 'леха',
'pool-queuefull'    => 'Хаттарий цӀа хьалдизад',
'pool-errorunknown' => 'Довзаш доаца гӀалат',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Лоацам {{SITENAME}}',
'aboutpage'            => 'Project:Лоацам',
'copyright'            => '$1 чулоацамаца такхоачаш да.',
'copyrightpage'        => '{{ns:project}}:Яздаьчунна бокъо',
'currentevents'        => 'ХӀанзара хоамаш',
'currentevents-url'    => 'Project:ХӀанзара хоамаш',
'disclaimers'          => 'Бокъонахь юхавалаp',
'disclaimerpage'       => 'Project:Бокъонахь юхавалаp',
'edithelp'             => 'Хувцама новкъостал',
'edithelppage'         => 'Help:ГӀалатнийсдара новкъoстал',
'helppage'             => 'Help:Чулоацам',
'mainpage'             => 'Кертера оагӀув',
'mainpage-description' => 'Кертера оагӀув',
'policy-url'           => 'Project:Бокъонаш',
'portal'               => 'Гулламхой ков',
'portal-url'           => 'Project:Гулламхой ков',
'privacy'              => 'Паьлабокъо',
'privacypage'          => 'Project:Паьлабокъо',

'badaccess'        => 'Чуваларa гӀалат',
'badaccess-group0' => 'Оаш хьадийха дулархам шунна де йийшяц.',
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
'viewsourceold'           => 'xьадоагӀа къайладоагӀа тӀа бӀаргтасса',
'editlink'                => 'хувца',
'viewsourcelink'          => 'xьадоагӀа къайладоагӀа тӀа бӀаргтасса',
'editsectionhint'         => 'Декъам хувца: $1',
'toc'                     => 'Чулоацам',
'showtoc'                 => 'хьахокха',
'hidetoc'                 => 'къайладаккха',
'collapsible-collapse'    => 'чудерзаде',
'collapsible-expand'      => 'хьадоаржаде',
'thisisdeleted'           => '$1 бӀаргтасса е юхаметтаоттаде?',
'viewdeleted'             => '$1 бӀаргтасса?',
'restorelink'             => '{{PLURAL:$1|дӀаяккха хувцам|$1 дӀаяккха хувцамаш}}',
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
'nstab-project'   => 'Хьахьоадайтамахь лаьца',
'nstab-image'     => 'Паьл',
'nstab-mediawiki' => 'Хоам',
'nstab-template'  => 'Куцкеп',
'nstab-help'      => 'Новкъoстал',
'nstab-category'  => 'Цатег',

# Main script and global functions
'nosuchaction'      => 'Цу тайпара дулархам бац',
'nosuchspecialpage' => 'Изза мо гӀооагӀув яц',

# General errors
'error'              => 'ГӀалат',
'missing-article'    => 'Кораде дезаш хинна оагӀувни яздам корадаьдац «$1» $2.

Из мо гӀалат нийсалуш хула, саг тишъенна Ӏинкаца, дадаьккха дола оагӀувни хувца искара тӀа чувала гӀиртача.

Наггахь санна из иштта деци, шоана гӀирса Ӏалаш деча гӀалат корая хила мега.
Дехар да, [[Special:ListUsers/sysop|мазакулгалхочоа]] хоам бе, URL хьахокхаш.',
'missingarticle-rev' => '(бӀаргоагӀув № $1)',
'internalerror'      => 'Чура гӀалат',
'internalerror_info' => 'Чура гӀалат: $1',
'badtitle'           => 'Мегаш йоаца цӀи',
'badtitletext'       => 'Дехаш дола оагӀувни цӀи, нийса яц, яьсса я е меттаюкъара е викиюкъара цӀи харцахь я. ЦӀера юкъе мегаш доаца харакъаш нийсадена хила мегаш да.',
'viewsource'         => 'Тахкам',

# Login and logout pages
'yourname'                => 'Дакъалаьцархочунна цӀи:',
'yourpassword'            => 'КъайладоагӀа:',
'yourpasswordagain'       => 'КъайладоагӀа юха Ӏоязаде:',
'remembermypassword'      => '(укх $1 {{PLURAL:$1|ден|деношкахь}}) мара са чувалара/чуялара дагалоаца дезаш дац',
'yourdomainname'          => 'Шун цӀеноагӀув:',
'login'                   => 'Чувала/яла',
'nav-login-createaccount' => 'ЦӀи яккха/Ший oагӀув ела',
'loginprompt'             => 'Укх болхaоагӀуваца доттагӀал лаца, шун "cookies" йийла хьалдеза.',
'userlogin'               => 'ЦӀи яккха/ОагӀув ела',
'userloginnocreate'       => 'Чувала/яла',
'logout'                  => 'Аравала/яла',
'userlogout'              => 'Аравала/яла',
'notloggedin'             => 'Оаш шоай цӀи хьааьннадац',
'nologin'                 => "Теркама йоазув яц? '''$1'''.",
'nologinlink'             => 'Лархама йоазув де',
'createaccount'           => 'Керда дакъалаьцархо кхолла',
'gotaccount'              => "Укхаза дӀаязабенна дий шо? '''$1'''.",
'gotaccountlink'          => 'Чувала/яла',
'userlogin-resetlink'     => 'Чувала/яла цӀеи доагӀеи дийцаденнадий?',
'createaccountmail'       => 'Д-фоштаца',
'createaccountreason'     => 'Бахьан:',
'mailmypassword'          => 'Керда къайладоагӀа хьаэца',
'loginlanguagelabel'      => 'Мотт: $1',

# Change password dialog
'oldpassword'               => 'Къаьна къайладоагӀ:',
'newpassword'               => 'Керда къайладоагӀ:',
'retypenew'                 => 'Керда къайладоагӀа юха Ӏоязаде:',
'resetpass-submit-loggedin' => 'КъайладогӀа дӀахувца',
'resetpass-submit-cancel'   => 'Юхавал/ялa',

# Special:PasswordReset
'passwordreset-username' => 'Дакъалаьцархочунна цӀи:',
'passwordreset-email'    => 'Д-фошта моттиг:',

# Edit page toolbar
'bold_sample'     => 'Сома яздам',
'bold_tip'        => 'Сома яздам',
'italic_sample'   => 'Кулга яздам',
'italic_tip'      => 'Кулга яздам',
'link_sample'     => 'Ӏинка кертмугӀ',
'link_tip'        => 'ЧураӀинк',
'extlink_sample'  => 'Ӏинка кертдош http://www.example.com',
'extlink_tip'     => 'Арена Ӏинка (http:// тамагӀахь дийца ма ле)',
'headline_sample' => 'KертмугӀa яздама',
'headline_tip'    => '2-гӀа кертмугӀa лагӀа',
'nowiki_sample'   => 'Укхаза кийчаде дезаш доаца яздам оттаде',
'nowiki_tip'      => 'Вики-бӀасоттамахь теркам ма бе',
'image_tip'       => 'Чуяккха паьла',
'media_tip'       => 'Паьла Ӏинк',
'sig_tip'         => 'Шун кулгаяздар а, хӀанзара ха а',
'hr_tip'          => 'Мухала мугӀ (могаш тайпара къеззига хайраде)',

# Edit pages
'summary'                          => 'Хувцамий белгалдер',
'subject'                          => 'БӀагал/кертмугӀ:',
'minoredit'                        => 'ЗӀамига хувцам',
'watchthis'                        => 'Укх оагӀува теркам бе',
'savearticle'                      => 'ОагӀув хьаязде',
'preview'                          => 'Хьалхе бӀаргтассар',
'showpreview'                      => 'Хьалхе бӀаргтaссар',
'showlivepreview'                  => 'Сиха бӀаргтассар',
'showdiff'                         => 'Даь хувцамаш',
'anoneditwarning'                  => 'Зем хила! Шо кха чудаьннадац. Шун IP-моттиг укх хийца оагӀув искаречу дӀаяздаь хургъе.',
'summary-preview'                  => 'Лоацам ба:',
'subject-preview'                  => 'Кортяздам:',
'blockedtitle'                     => 'Дакъалаьцархо чӀега бела ва/я',
'blockednoreason'                  => 'бахьан доацаш да',
'loginreqlink'                     => 'чувала/яла',
'loginreqpagetext'                 => 'Кхыйола оагӀувнашка хьожаргдолаш, оаш $1 де деза.',
'accmailtitle'                     => 'КъайладоагӀ дӀадахатад',
'newarticle'                       => '(Kерда)',
'newarticletext'                   => 'Шо йоаца оагӀув тӀа Ӏинкаца дехьадаьннад.
Из хьае, кӀалхагӀа доала корачу яздам очуязаде (кхета хала дале [[{{MediaWiki:Helppage}}|новкъостала оагӀув тӀа]] бӀаргтасса).
Цаховш укхаза нийсадена дале, юхавала/яла яха тоӀобама тӀа пӀелга тоӀобе.',
'noarticletext'                    => "У сахьате укх оагӀув тӀа яздам доацаш да.
[[Special:Search/{{PAGENAME}}|цу тайпара цӀи дувцам кораде]] кхыдола йоазувашкахь йийша я шун, вешта
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тара дола таптарий йоазо карае], е
'''[{{fullurl:{{FULLPAGENAME}}|action=edit}} изза мо цӀи йоалаш оагӀув ела]'''</span>.",
'noarticletext-nopermission'       => 'Укх сахьате укх оагӀув тӀа яздам дац.
Шун йийшая, кхыдола йоазувнашкахь [[Special:Search/{{PAGENAME}}|дола цӀерий хаттам корае]] е <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} нийсамий тептара йоазувнаш корае].</span>',
'note'                             => "'''ХӀамоалар:'''",
'previewnote'                      => "'''Хьалхе бӀаргтассам мара бац, яздам кха яздаь дац!'''",
'editing'                          => 'ГӀалатнийсдар: $1',
'editingsection'                   => 'ГIалатнийсдар $1 (оагӀувдакъа)',
'editingcomment'                   => 'ГӀалатнийсдар $1 (керда декъам)',
'editconflict'                     => 'ГӀалатнийсдара кховсам: $1',
'yourtext'                         => 'Хьа яздам',
'copyrightwarning'                 => "Теркам бе, $2 ($1 хьажа) бокъонаца лорадеш, тӀахьежама кӀала уллаш, оаш мел чуяккхаш дола хоамаш, яздамаш долга.
Наггахь санна шоай яздамаш пурам доацаш мала волашву саго хувца е кхы дола моттиге яздердолаш, безам беци, укхаз Ӏочуцаяздеча, дикаьгӀа да.<br />
Оаш дош лу, даь дола хувцама да волга/йолга, е оаш пурам долаш Ӏочуяздеш да кхычера меттигара шоай яздамаш/хоамаш.
'''Яздархой бокъоца лорадеш дола хӀамаш, цара пурам доацаш, Ӏочумаязаде!'''",
'templatesused'                    => 'Укх бӀаргоагӀувни оагӀув тӀа лелая {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'templatesusedpreview'             => 'Хьалхе бӀаргтассама оагӀув тӀа леладеш дола {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'template-protected'               => ' (лорам лаьца бa)',
'template-semiprotected'           => '(дакъа-лорам)',
'hiddencategories'                 => 'Ер оагӀув укх {{PLURAL:$1|къайла цатегаца|къайла цатегашца}} дакъа лоаца:',
'permissionserrorstext-withaction' => '$2 де бокъо яц {{PLURAL:$1|из бахьан долаш|из бахьанаш долаш}}:',
'recreate-moveddeleted-warn'       => "'''Зем бе! Шо хьалххе дайоаккхаш хина оагӀув хьае гӀарта.'''

Хьажа, бокъонцахь езаш йолга.
КӀалхагIа укх оагӀуви дӀадаккхамeи цӀи хувцамeи тептараш хьекха да.",
'moveddeleted-notice'              => 'Ер оагӀув дӀаяккха хиннай.
Новкъостала, кӀалха дӀадаккхама а хувцама а тептарашкера нийсама йоазувнаш хьахьекха я.',
'log-fulllog'                      => 'Деррига таптара бӀаргтасса',
'edit-conflict'                    => 'Хувцамий кховсам.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Зембакхам: дагара куцкепаш чулоаца дустам геттара доккха да.
Цхьадола куцкепаш чулоацалургдац.',
'post-expand-template-inclusion-category' => 'Чулоаца куцкепий мегаш дола дустам дукхалена тӀехьайоала оагӀувнаш',
'post-expand-template-argument-warning'   => 'Зем бе! Ер оагӀув цаӀ куцкепа аьладош мара чулоацац, юхадастара сел доккха дустам йолаш.
Цу тайпара аьладешаш ӀокӀаладаьккха да.',
'post-expand-template-argument-category'  => 'Куцкепий теркамза аьладешаш чулоаца оагӀувнаш',

# History pages
'viewpagelogs'           => 'Укх оагӀува тептараш хокха',
'currentrev-asof'        => '$1 тӀа эггара тӀехьара доржам',
'revisionasof'           => '$1 доржам',
'revision-info'          => '$1; $2 хувцам',
'previousrevision'       => '← Xьалхарча',
'nextrevision'           => 'TӀадоагӀа →',
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

# Revision feed
'history-feed-title'          => 'Хувцамий искар',
'history-feed-description'    => 'Укх оагӀуви вики тӀа хувцамий искар',
'history-feed-item-nocomment' => '$1гӀара $2гӀачу',

# Revision deletion
'rev-delundel'               => 'хьахокха/къайлаяккха',
'rev-showdeleted'            => 'хьахокха',
'revdelete-show-file-submit' => 'XӀаа',
'revdelete-radio-set'        => 'XӀаа',
'revdelete-radio-unset'      => 'A',
'revdelete-log'              => 'Бахьан',
'revdelete-logentry'         => '[[$1]] доржама оагӀувни бӀасанче хийцай',
'revdel-restore'             => 'БӀасанче хувца',
'revdel-restore-deleted'     => 'дӀадаьккха доржамаш',
'revdel-restore-visible'     => 'бӀаргагушдола доржамаш',
'pagehist'                   => 'ОагӀува искар',
'deletedhist'                => 'ДӀадакхамий искар',
'revdelete-content'          => 'чулоацаро',
'revdelete-summary'          => 'хувцамий лоацам',
'revdelete-uname'            => 'дакъалаьцархочунна цIи',
'revdelete-hid'              => 'къайла я $1',
'revdelete-log-message'      => '$1ара $2чунна  {{PLURAL:$2|доржама|доржамий}}',

# History merging
'mergehistory-list'   => 'ВIашагIатоха хувцамий искар',
'mergehistory-go'     => 'ВIашагIатоха хувцамаш хьахьокха',
'mergehistory-submit' => 'Хувцамаш вIашагIатоха',
'mergehistory-empty'  => 'ВIашагIатохара хувцамаш кораяяц',
'mergehistory-reason' => 'Бахьан:',

# Merge log
'revertmerge' => ' Декъа',

# Diffs
'history-title'           => '"$1" хувцамий искар',
'difference'              => '(Доржамашкахь юкъера къоастамаш)',
'lineno'                  => 'МугI $1:',
'compareselectedversions' => 'Хьаржа доржамаша тарона тIа хьажа',
'editundo'                => 'юхавала/яла',
'diff-multi'              => '({{PLURAL:$1|$1 юкъара доржам хьахьекха дац|$1 юкъара доржамаш хьахьекха дац}} {{PLURAL:$2|$2 дакъалаьцархочунна|$2 дакъалаьцархоший}})',

# Search results
'searchresults'                    => 'Тахкама гIулакххилар',
'searchresults-title'              => '"$1" тахка',
'searchresulttext'                 => 'Хьахьоадайтама оагIувнаш тIа тахкамахь лаьца лоаца маIандар эца [[{{MediaWiki:Helppage}}|новкъостала декъамага]] хьажа.',
'searchsubtitle'                   => 'Хоаттамахь лаьца «[[:$1]]» ([[Special:Prefixindex/$1|цу цIерахь ювалу оагIувнаш]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|цу цIерахь Iинкаш еж йола]])',
'searchsubtitleinvalid'            => "'''$1''' хаттара",
'notitlematches'                   => 'ОагIувни цIераш вIашагIа кхеташ яц',
'notextmatches'                    => 'ОагIувнаша ядамий вIашагIакхетараш дац',
'prevn'                            => '{{PLURAL:$1|хьалхарча $1|хьалхарчаш $1|хьалхарчаш $1}}',
'nextn'                            => '{{PLURAL:$1|тlехьайоагlар $1|тlехьайоагlараш $1|тlехьайоагlараш $1}}',
'prevn-title'                      => '{{PLURAL:$1|$1 хьалхара йоазув|$1 хьалхара йоазувнаш}}',
'nextn-title'                      => '{{PLURAL:$1|$1 тIехьара йоазув|$1 тIехьара йоазувнаш}}',
'shown-title'                      => 'Укх оагIувни $1 {{PLURAL:$1|йоазув|йоазувнаш}} хьахокха',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) хьажа',
'searchmenu-exists'                => "'''Укх вIикIи-хьахьоадайтама чу ер оагув \"[[:\$1]]\" я'''",
'searchmenu-new'                   => "'''Укх \"[[:\$1]]\" вики-хьахьоадайтамчоахь оагIув де!'''",
'searchhelp-url'                   => 'Help:Чулоацам',
'searchprofile-articles'           => 'ЛардоагIувнаш',
'searchprofile-project'            => 'Дагарчеи хьахьоадайтамеи оагIувнаш',
'searchprofile-images'             => 'Медифаг',
'searchprofile-everything'         => 'Массана',
'searchprofile-advanced'           => 'Шера я',
'searchprofile-articles-tooltip'   => '$1чу лахар',
'searchprofile-project-tooltip'    => '$1чу лахар',
'searchprofile-images-tooltip'     => 'Паьлий лахар',
'searchprofile-everything-tooltip' => 'Массадола оагIувний лахар (дувцама оагIувнаш чулоацаш)',
'searchprofile-advanced-tooltip'   => 'Iочуязья цIераренашкахь лаха',
'search-result-size'               => ' $1 ({{PLURAL:$2|1 дош|$2 дешаш}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 дакъа|$1 дакъаш}} ({{PLURAL:$2|$2 кIалцатег|$2 кIалцатегаш}}, {{PLURAL:$3|$3 паьла|$3 паьлий|$3 паьлий}})',
'search-redirect'                  => '($1 дехьачуяккхар)',
'search-section'                   => ' (дакъа $1)',
'search-suggest'                   => 'Iа лохар из хила мега: $1',
'search-interwiki-caption'         => 'Гаргалона хьахьоадайтамаш',
'search-interwiki-default'         => '$1 толамчаш:',
'search-interwiki-more'            => '(кха)',
'search-mwsuggest-enabled'         => ' Хьехамашца',
'search-mwsuggest-disabled'        => ' Хьехамаш боацаш',
'searchrelated'                    => 'гаргара',
'searchall'                        => 'деррига',
'showingresultsheader'             => "{{PLURAL:$5|'''$1''' толамче укх '''$3''' долачарахь|'''$1 — $2''' толамчаш укх '''$3''' долачарахь}} '''$4'''а",
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
'changepassword'            => 'КъайладоaгIа дIахувцар',
'prefs-skin'                => 'БIагала куц',
'skin-preview'              => 'Хьажа',
'prefs-datetime'            => 'Таьрахьеи сахьатеи',
'prefs-personal'            => 'Хьа хьай далам',
'prefs-rc'                  => 'Керда хувцамаш',
'prefs-watchlist'           => 'Теркама дагарче',
'prefs-watchlist-days'      => 'Ден дукхал',
'prefs-resetpass'           => 'КъайладогIа хувца',
'prefs-rendering'           => 'ТIера бIаса',
'saveprefs'                 => 'Дита',
'prefs-editing'             => 'ГIалатнийсдар',
'searchresultshead'         => 'Лахаp',
'timezonelegend'            => 'Сахьати юкъ:',
'localtime'                 => 'Вола/Йола моттиги ха:',
'timezoneregion-africa'     => 'Эприк',
'timezoneregion-america'    => 'Iаьмрик',
'timezoneregion-antarctica' => 'Энтарцит',
'timezoneregion-arctic'     => 'Эрцит',
'timezoneregion-asia'       => 'Iаьзик',
'timezoneregion-atlantic'   => 'Iатлантицфорд',
'timezoneregion-australia'  => 'Устралик',
'timezoneregion-europe'     => 'Аьроп',
'timezoneregion-indian'     => 'ХIинда форд',
'timezoneregion-pacific'    => 'Тийна форд',
'prefs-searchoptions'       => 'Тахкама оттамаш',
'prefs-files'               => 'Паьлаш',
'youremail'                 => 'Д-фошт:',
'username'                  => 'Дакъалаьцархочунна цIи:',
'yourrealname'              => 'Шун цIи:',
'yourlanguage'              => 'Мотт:',
'gender-male'               => 'МаIа',
'gender-female'             => 'Кхал',
'email'                     => 'Д-фошт',
'prefs-help-email'          => 'Д-фоштий моттиг ала эшаш дац, амма новка даца, наггах санна къайладоагIа шоан дийцалой, цу тIа хьатIадайтаргда.',
'prefs-help-email-others'   => 'Кхыбола дакъалаьцархоша шоаца бувзам я йийшхургья шун оагIува тIа гIолла, д-фошт хьаела ца езаш.',
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

'group-user-member'  => 'дакъалаьцархо',
'group-bot-member'   => 'бIат',
'group-sysop-member' => 'мазакулгалхо',

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
'recentchanges-feed-description'  => 'Укх ларамца тIехьара викихувцамашт теркам бе.',
'recentchanges-label-newpage'     => 'Укх хувцамаца керда оагIув даь хиннад',
'recentchanges-label-minor'       => 'ЗIамига хувцам я',
'recentchanges-label-bot'         => 'Ер хувцам бIатаца я е',
'recentchanges-label-unpatrolled' => 'Ер хувцам ший моттиге кха дIадехьаяккхаяц.',
'rcnote'                          => "{{PLURAL:$1|Тlехьара '''$1''' хувцам|Тlехьара '''$1''' хувцамаш}} дола '''$2''' {{PLURAL:$2|ден|деношкахь}}, укх сахьате $5 $4.",
'rcnotefrom'                      => 'КIалхагIа хувцамаш хьахьекха я <strong>$2</strong> денза (<strong>$1</strong> кхачалца).',
'rclistfrom'                      => '$1 тIара хувцамаш хьахокха',
'rcshowhideminor'                 => 'зIамига хувцамаш $1',
'rcshowhidebots'                  => '$1 шабелхалой',
'rcshowhideliu'                   => 'Чубаьнначара дакъалаьцархочий $1',
'rcshowhideanons'                 => 'цIияьккханза дакъалаьцархой $1',
'rcshowhidepatr'                  => '$1 теркам даь хувцамаш',
'rcshowhidemine'                  => '$1 сай хувцамаш',
'rclinks'                         => '$2 динахь<br />$3 $1 хинна тIехьара хувцамаш хьахокха',
'diff'                            => 'кхы.',
'hist'                            => 'искар',
'hide'                            => 'Къайладаккха',
'show'                            => 'Хьахьокха',
'minoreditletter'                 => 'м',
'newpageletter'                   => 'Н',
'boteditletter'                   => 'б',
'rc-enhanced-expand'              => 'Ма дарра чулоацамаш хьахокха (JavaScriptаца)',
'rc-enhanced-hide'                => 'Ма дарра чулоацамаш къайладаккха',

# Recent changes linked
'recentchangeslinked'          => 'Гаргалона хувцамаш',
'recentchangeslinked-toolbox'  => 'Гаргалона хувцамаш',
'recentchangeslinked-title'    => '$1ца хьалаьца хувцамаш',
'recentchangeslinked-noresult' => 'Укх заманашка гаргарон оагIувнаш тIа хувцамаш хиннаяц.',
'recentchangeslinked-summary'  => "Ер, Iинк я йола оагIув (е укх цатегачу чуйоагIараш), дукха ха йоацаш хьийца оагIувнашки дагарче я.
[[Special:Watchlist|Шун теркама дагаршкахь]] чуйоагIа оагIувнаш '''белгалъя я'''.",
'recentchangeslinked-page'     => 'ОагIува цIи',
'recentchangeslinked-to'       => 'ОагIувнаш тIа хувцамаш хьахокха, хьахекха йола оагIув тIа Iинкаш еш йола.',

# Upload
'upload'            => 'Паьл чуяккха',
'uploadlogpage'     => 'Чуяккхамий тептар',
'filedesc'          => 'Лоаца лоацам',
'fileuploadsummary' => 'Лоаца лоацам:',
'uploadedimage'     => '"[[$1]]" чуяккхай',

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
'filehist-help'             => 'Хьалхе паьла мишта хиннай хьожаpгволаш/хьожаpгйолаш, дентаьрах/сахьата тIа пIелга тIообе.',
'filehist-revert'           => 'юхаяккха',
'filehist-current'          => 'xIанзара',
'filehist-datetime'         => 'Дентаьрахь/Ха',
'filehist-thumb'            => 'ЗIамигасуртанче',
'filehist-thumbtext'        => '$1 доржаме зIамигсуртанчoa',
'filehist-user'             => 'Дакъалаьцархо',
'filehist-dimensions'       => 'ХIамана дустам',
'filehist-filesize'         => 'Паьла юстарал',
'filehist-comment'          => 'ХIамоалар',
'filehist-missing'          => 'Паьла йоацаш я',
'imagelinks'                => 'Паьлий пайда эца',
'linkstoimage'              => '{{PLURAL:$1|ТIехьайоагIа $1 оагIув Iинк ду|ТIехьайоагIа $1 оагIувнаш Iинкаш ду}} укх паьла тIа:',
'nolinkstoimage'            => 'Йола паьлат  Iинк ю оагIувнаш дац',
'sharedupload'              => 'Ер паьла $1чера я, кхыча хьахьоадайтамча хьахайраде йийшайолаш я.',
'sharedupload-desc-here'    => 'Ер паьл $1чара я, кхыдола хьахьоадайтамача хайрамбе йийш йолаш да.
Цунна [$2 лоацама оагIувца] лоаца маIандар кIалхагIа латта.',
'uploadnewversion-linktext' => 'Укх паьлий керда бIаса чуяккха',

# File reversion
'filerevert-comment' => 'Бахьан:',

# File deletion
'filedelete-comment'          => 'Бахьан:',
'filedelete-submit'           => 'ДIадаккха',
'filedelete-reason-otherlist' => 'Кхыдола бахьан',

# MIME search
'download' => 'хьачуяккха',

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

'withoutinterwiki-submit' => 'Хьахокха',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|бIат|бIаташ}}',
'nmembers'      => '$1 {{PLURAL:$1|дакъалаьцархо|дакъалаьцархой}}',
'prefixindex'   => 'ОагIувнаший хьалхера цIи хьагойтар',
'shortpages'    => 'Лоаца оагIувнаш',
'longpages'     => 'Доккхий оагIувнаш',
'usercreated'   => '$1ара $2чуча даь да',
'newpages'      => 'Керда оагIувнаш',
'move'          => 'ЦIи хувца',
'movethispage'  => 'Укх оагIува цIи хувца',
'pager-newer-n' => '{{PLURAL:$1|кердагIа дара|кердагIа дараш|кердагIа долачаьрахь}} $1',
'pager-older-n' => '{{PLURAL:$1|къаьнара дара|къаьнара дараш|къаьнара долaчаьрахь}} $1',

# Book sources
'booksources'               => 'Китабий гIувам',
'booksources-search-legend' => 'Китаба лоаца маIандара тахкам',
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
'linksearch'      => 'ЧураIинкаш',
'linksearch-ok'   => 'Лаха',
'linksearch-line' => '$1 тIа Iинк $2 юкъера',

# Special:Log/newusers
'newuserlogpage'          => 'Дакъалаьцархоший дIаязбeнна таптар',
'newuserlog-create-entry' => 'Керда дакъалаьцархо',

# Special:ListGroupRights
'listgrouprights-members' => '(тоабий дагарче)',

# E-mail user
'emailuser' => 'Дакъалаьцархочоа д-фошт:',

# Watchlist
'watchlist'         => 'Теркама дагарче',
'mywatchlist'       => 'Теркама дагарче',
'watchlistfor2'     => '$1 $2 царна',
'addedwatchtext'    => '"[[:$1]]" оагIув, шун [[Special:Watchlist|теркама дагаршкахь]] чуяккха я. 
Техьара мел йола укх оагIувни хувцамаш цу дагаршкахь хоам беш хургья. Вешта [[Special:RecentChanges|керда хувцама дагаршкаехь]] сома къоалмаца хьакъоастлуш хургья.',
'removedwatchtext'  => '"[[:$1]]" оагIув, шун [[Special:Watchlist|теркама дарагчера]] дIаяккха хиннай.',
'watch'             => 'Тохкам бе',
'watchthispage'     => 'Укх оагIува теркам бе',
'unwatch'           => 'Лора ма де',
'watchlist-details' => 'Шун теркама дагарченгахь йола  $1 {{PLURAL:$1|оагIув|оагIувнаш}}, дувцама оагIувнаш ца лоархаш.',
'wlshowlast'        => 'Тlехьара $1 сахьаташ $2 денош $3 хьахокха',
'watchlist-options' => 'Зем баккха дагарена хувцамаш',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Тохкам беча оагIув тIа тIадаккха',
'unwatching' => 'Тохкам беча оагIув тIера дIадаккха',

# Delete
'deletepage'            => 'ОагIув дIаяккха',
'confirmdeletetext'     => 'Оаш оагIувни (е сурти) барча дIадаккхар хьайийхай кха еррига хувцамий искар долама ковчера. 
Дехар да, жоп дала, шоай из бокъонцахь де безам болаш да, шоай даьчоахь хургдолчоахь кхеташ долга, [[{{MediaWiki:Policy-url}}]] декъамачу Iоязадаь дола адаташ ца из деш долга.',
'actioncomplete'        => 'Дулархам баьб',
'actionfailed'          => 'Оттам даьдац',
'deletedtext'           => '"$1" дIаяккха хиннай.
ТIехьара дIадаьккха дагарчена хьожаргволаш/хьожаргьйолаш, $2 хьажа.',
'deletedarticle'        => ' "[[$1]]" дIадаьккхад',
'dellogpage'            => 'ДIадаккхара тептар',
'deletecomment'         => 'Бахьан:',
'deleteotherreason'     => 'Кхыдола бахьан/тIатохар:',
'deletereasonotherlist' => 'Кхыдола бахьан',

# Rollback
'rollbacklink' => 'юхаяккха',

# Protect
'protectlogpage'              => 'Лорама тептар',
'protectedarticle'            => '"[[$1]]" оагIув лорам деж я',
'modifiedarticleprotection'   => '"[[$1]]" оагIувни лорама лагIа хувцаеннай',
'protectcomment'              => 'Бахьан:',
'protectexpiry'               => 'Кхоачалуш латта:',
'protect_expiry_invalid'      => 'Чаккхабоала лорама харца ха',
'protect_expiry_old'          => 'Чаккхайоала ха - яха зама я.',
'protect-text'                => "'''$1''' укхаз шоана шоай оагIув лорамлагIа хувца a бIаргтасса a йийш хургья.",
'protect-locked-access'       => "Шун лархама йоазуви нидза кхоачаш бац оагIувни лорама лагIа хувца. '''$1''' оагIувни дIаоттамаш:",
'protect-cascadeon'           => '{{PLURAL:$1|КIалхахь хьагойташ йола оагIувчу|КIалхахь хьагойташ йола оагIувнашчу}} ер оагIув чуяккха халарахь, лорам Iоттая я, хурхала лорам Iоттая я. Укх оагIувни лорама лагIа хувца йийш йолаш я, амма хурхала лорам хувцлургдац.',
'protect-default'             => 'Лорамза',
'protect-fallback'            => '"$1" пурам эша',
'protect-level-autoconfirmed' => 'Керда а, дакъалаьцабоацачаьрахь а лораде',
'protect-level-sysop'         => 'Мазакулгалхо мара чувала бокъо яц',
'protect-summary-cascade'     => 'хурхала',
'protect-expiring'            => 'чаккхайоала',
'protect-cascade'             => 'Укх оагIувче чуяккха оагIуваш лорае (хурхала лорам)',
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
'undeletedarticle' => '"[[$1]]" юха оттая я',

# Namespace form on various pages
'namespace'      => 'ЦIерий аренаш',
'invert'         => 'Харжар юхадаккха',
'blanknamespace' => '(Корта)',

# Contributions
'contributions'       => 'Дакъалаьцархочунна къахьегам',
'contributions-title' => '$1 дакъалаьцархочунна къахьегам',
'mycontris'           => 'Са къахьегам',
'contribsub2'         => '$1 ($2) баь болх',
'uctop'               => '(тIехьара)',
'month'               => 'Цхьа бутт хьалхагIа (кха хьалхагIа)',
'year'                => 'Цхьа шу хьалхагIа (кха хьалхагIа):',

'sp-contributions-newbies'  => 'Керда даязья йоазоначера мара баь бола къахьегам ма хокха',
'sp-contributions-blocklog' => 'чIегаш',
'sp-contributions-uploads'  => 'чуяккхамаш',
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
'whatlinkshere-prev'       => '{{PLURAL:$1|хьахайоагIа|хьалхайоагIараш}} $1',
'whatlinkshere-next'       => '{{PLURAL:$1|тIехьайоагIа|тIехьайоагIараш}} $1',
'whatlinkshere-links'      => '← Iинкаш',
'whatlinkshere-hideredirs' => '$1 дIа-хьа чуяккхамаш',
'whatlinkshere-hidetrans'  => '$1 чуяккхамаш',
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
'block-log-flags-nocreate' => 'Лархамий дагарчена цIи яккхар пурам янза я.',
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
'1movedto2'        => '[[$1]] цIи цу тIа [[$2]] хийцай',
'1movedto2_redir'  => '[[$1]] цIи хийцай [[$2]] дIа-хьа оагIува тIа гIолла',
'movelogpage'      => 'Хувцама тептар',
'movereason'       => 'Бахьан',
'revertmove'       => 'юхаяккха',

# Export
'export' => 'ОагIувий эхфортам',

# Namespace 8 related
'allmessagesname'           => 'ЦIи',
'allmessagesdefault'        => 'Сатийна улла яздам',
'allmessages-filter-all'    => 'Дерригаш',
'allmessages-language'      => 'Мотт:',
'allmessages-filter-submit' => 'Дехьавала',

# Thumbnails
'thumbnail-more'  => 'Хьадоккхаде',
'thumbnail_error' => 'ЗIамигасуртанчий кхеллама гIалат: $1',

# Special:Import
'import-upload-filename' => 'ПаьлацIи:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Дакъалаьцархочунна оагIув',
'tooltip-pt-mytalk'               => 'Шун дувцамий оагIув',
'tooltip-pt-preferences'          => ' Шун оттамаш',
'tooltip-pt-watchlist'            => 'Оаш хувцамаш тIа бIарглакха оагIувнаша дагарче',
'tooltip-pt-mycontris'            => 'Шун хувцамаш',
'tooltip-pt-login'                => 'Укхаза хьай цIи аьле чувала/чуяла йийша я, амма чуцаваьлача/чуцаялача хIамма а дац',
'tooltip-pt-logout'               => 'Аравала/яла',
'tooltip-ca-talk'                 => 'ОагIува чулоацамий дувцам',
'tooltip-ca-edit'                 => 'Ер оагIув хувца йийшйолаш я. Дехар да, Iалаш елаьхь, хьалхе бIаргтассама оагIув тIа бIаргтасса.',
'tooltip-ca-addsection'           => 'Керда декъам хьаде',
'tooltip-ca-viewsource'           => 'Ер оагIув хувцамахь лорая е, амма шун цунна гIувамага хьажа бокъо я.',
'tooltip-ca-history'              => 'Укх оагIувни хувцама таптар',
'tooltip-ca-protect'              => 'Eр оагIув лорае',
'tooltip-ca-delete'               => 'Ер оагIув дIаяккха',
'tooltip-ca-move'                 => 'Укх оагIува цIи хувца',
'tooltip-ca-watch'                => 'Ер оагIув теркам беча каьхата тIа тIаяккха',
'tooltip-ca-unwatch'              => 'Ер оагIув теркам беча каьхата тIара дIаяккха',
'tooltip-search'                  => 'Цу тайпара дош лаха {{SITENAME}}',
'tooltip-search-go'               => 'Изза мо цӀи йолаш оагӀув тӀa дехьавала',
'tooltip-search-fulltext'         => 'Изза мо яздам долаш оагӀувнаш лаха',
'tooltip-p-logo'                  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage'              => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage-description'  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-portal'                => 'Хьахьоадайтамахь лаьца, хьа де йийшдар, фа а мичча а йоала',
'tooltip-n-currentevents'         => 'ХIанзара хоамий дагарче',
'tooltip-n-recentchanges'         => 'ТӀехьара хувцамий дагарче',
'tooltip-n-randompage'            => 'Бе йоаца оагӀув ела',
'tooltip-n-help'                  => 'Новкъостала моттиг',
'tooltip-t-whatlinkshere'         => 'Массайола оагIувий дагарче, укх оагIув тIа Iинкаш луш йола',
'tooltip-t-recentchangeslinked'   => 'ОагIувнаш тIа тIехьара хувцамаш, укх оагIувнера Iинк я йола',
'tooltip-feed-rss'                => 'Укх оагIувна RSSчу гойтар',
'tooltip-feed-atom'               => 'Укх оагувна Atomчу гойтар',
'tooltip-t-contributions'         => 'Укх дакъалаьцархочу хьийца йола оагIувнаш хьахокха',
'tooltip-t-emailuser'             => 'Укх дакъалаьцархочоа зIы яхьийта',
'tooltip-t-upload'                => 'Паьлаш чуяккха',
'tooltip-t-specialpages'          => 'ГIулакха оагIувний дагарчe',
'tooltip-t-print'                 => 'Укх зарба оаугIувни дагарче',
'tooltip-t-permalink'             => 'Укх оагIув доржама даим латта Iинк',
'tooltip-ca-nstab-main'           => 'Йоазува чулоацам',
'tooltip-ca-nstab-user'           => 'Дакъалаьцархочунна ший оагIув',
'tooltip-ca-nstab-special'        => 'Ер гIулакха оагIув я, из хувца хьо бокъо йолаш вац/яц.',
'tooltip-ca-nstab-project'        => 'Хьахьоадайтама оагIув',
'tooltip-ca-nstab-image'          => 'Паьла оагIув',
'tooltip-ca-nstab-template'       => 'Куцкепа оагIув',
'tooltip-ca-nstab-category'       => 'Цатега оагIув',
'tooltip-minoredit'               => 'Ер хувцар башха доаца санна белгалде',
'tooltip-save'                    => 'Хувцамаш кходе',
'tooltip-preview'                 => 'ОагIув тIа хьалхе бIаргтассар, дехар да, оагIув дIаязъелаьхь, цунна теркам бе.',
'tooltip-diff'                    => 'Яздам тIа я йола хувцамаш хьахокха',
'tooltip-compareselectedversions' => 'Укх оагIувни шин доржамаш тIа юкъера хувцамаш зе.',
'tooltip-watch'                   => 'Ер оагIув теркам беча каьхата тIа яккха',
'tooltip-rollback'                => 'ГIалaтанийсадаро тIехьара я йола хувцамаш, пIелг тоIоби дIаяккха.',
'tooltip-undo'                    => 'Я хувцам дIаяккхи, бIаргатассар хьахокха, кара дале, дIаяккха бахьан Iочуязаде моттигаца.',
'tooltip-summary'                 => 'Лоаца чулоацам Iочуязаде',

# Patrol log
'patrol-log-line' => '$1 долачаьрахь $2 $3 хьажав/хьажай',
'patrol-log-diff' => '$1 доржам',

# Browsing diffs
'previousdiff' => '← Хьалхара хувцам',
'nextdiff'     => 'ТIайоагIа хувцам',

# Media information
'file-info-size' => '$1 × $2 фихсам, паьла дустам: $3, MIME-тайп: $4',
'file-nohires'   => '<small>Укхал доккхагIа доржам дац</small>',
'svg-long-desc'  => 'SVG-паьл, $1 × $2 фихелашца, паьла дустам: $3',
'show-big-image' => 'Хьадоккхадаь сурт',

# Special:NewFiles
'noimages' => 'Суртaш бIаргагуш дац.',
'ilsubmit' => 'Лаха',

# Bad image list
'bad_image_list' => 'Бустам цу тайпара хила беза:

Дагарена хьаракъаш мара лоархаш хургьяц (укх тамагIалгацa * дувлашду мугIараш).
МугIарена хьалхара Iинк, сурт Iоттае пурам доаца Iинка, хила еза. 
Цу мугIар тIа тIехьайоагIа Iинкаш, арадаккхар мо лоархаш хургья, вешта аьлча, йоазувашка чуIоттаде мегаш дола сурт санна ларха мега.',

# Metadata
'metadata'          => 'МетахIамаш',
'metadata-help'     => 'Паьлас чулоаца, кхыдола хIамаш, таьрахьа суртдоаккхаргца е тIагIолладоаккхаргца чудаккхаш дола. Хьая паьл, гIалатахь мукъадаьккха хинна дале, хьахокхаш дола сурт, деррига хIамаш чулоацаргдац.',
'metadata-expand'   => 'Кхыдола хIамаш хьахокха',
'metadata-collapse' => 'Кхыдола хIамаш къайладаккха',
'metadata-fields'   => 'Укх дагарченгахь дагaрадаь метахIамаша суртий мугIаш, сурт оагIув тIа хьахекха хургья, чуерзая метахIамашийца. Вож мугIанаш ха йоалаш къайла хургья.
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
'exif-imagedescription' => 'Сурти цIи',
'exif-artist'           => 'Яздархо',
'exif-colorspace'       => 'Басара аре',
'exif-pixelydimension'  => 'Сурти шерал',
'exif-pixelxdimension'  => 'Сурти лакхал',
'exif-writer'           => 'Яздама да',
'exif-languagecode'     => 'Мотт',
'exif-iimcategory'      => 'Цатег',

'exif-scenecapturetype-1' => 'ЛаьттабIаса',
'exif-scenecapturetype-2' => 'Сурт',

'exif-iimcategory-edu' => 'Дешар',
'exif-iimcategory-evn' => 'Арена буне',
'exif-iimcategory-hth' => 'Могар',
'exif-iimcategory-hum' => 'Адамий искараш',
'exif-iimcategory-rel' => 'Динеи тешареи',
'exif-iimcategory-sci' => 'Iилмеи кулгболхеи',
'exif-iimcategory-soi' => 'Сагий хаттараш',
'exif-iimcategory-spo' => 'Нидзоамал',
'exif-iimcategory-war' => 'ТIомаш, кховсамашеи латтарашеи',
'exif-iimcategory-wea' => 'Хаоттам',

# External editor support
'edit-externally'      => 'Йола болхоагIувца паьла гIалатахь мукъаяккха',
'edit-externally-help' => '(ма даррачунга хьажа [//www.mediawiki.org/wiki/Manual:External_editors хьаоттама кулгалхо])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'деррига',
'namespacesall' => 'деррига',
'monthsall'     => 'деррига',
'limitall'      => 'деррига',

# action=purge
'confirm_purge_button' => 'ХIаа',

# Multipage image navigation
'imgmultigo'   => 'Дехьавала!',
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
'filepath-submit' => 'Дехьавала',

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

#Каст-каста оаламаш укх мугIа лакхе дIаязаде. Из мугI ший долаш тайпара дита<pre>',

# Special:Tags
'tag-filter'    => '[[Special:Tags|Йоазоний]] цIенаярг:',
'tags-title'    => 'Йоазонаш',
'tags-tag'      => 'Йоазон цIи',
'tags-edit'     => 'хувца',
'tags-hitcount' => '$1 {{PLURAL:$1|хувцам|хувцамаш}}',

# Special:ComparePages
'compare-page1' => '1. ОагIув',
'compare-page2' => '2. ОагIув',
'compare-rev1'  => '1. Доржам',
'compare-rev2'  => '2. Доржам',

# Database error messages
'dberr-header' => 'Укх викис халонаш ловш латта',

# HTML forms
'htmlform-submit'              => 'ДIадахийта',
'htmlform-selectorother-other' => 'Кхыдола',

);
