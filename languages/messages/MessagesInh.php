<?php
/** Ingush (ГІалгІай Ğalğaj)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Sapral Mikail
 * @author Tagir
 */

$fallback = 'ru';

$messages = array(
# User preference toggles
'tog-underline'               => 'Iинкаш белгалде:',
'tog-highlightbroken'         => 'Йоаца Iинкаш хьахокха <a href="" class="new">ишта</a> (е вешта<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Яздам оагIува шоралца хьанийсъе',
'tog-hideminor'               => 'ЗIамига хувцамаш керда дIахувцама дагаршкахь къайлаяккха',
'tog-hidepatrolled'           => 'Керда хувцама дагарчеча дIанийсъя хувцамаш къайлаяккха',
'tog-newpageshidepatrolled'   => 'Керда оагIувни дагарчеча дIанийсъя хувцамаш къайлаяккха',
'tog-extendwatchlist'         => 'Шеръя теркама дагарче, массайола хувцамаш чулоацаш',
'tog-usenewrc'                => 'Тоая керда хувцама дагаршкара пайда эца (JavaScript эша)',
'tog-numberheadings'          => 'Кертера-деша таьрахь ший лоIамаца оттайе',
'tog-showtoolbar'             => 'ГIалатнийcдара юкъе лакхера гIирсий гартакх хьахокха (JavaScript)',
'tog-editondblclick'          => 'Шозза цлицкацa oагIув хувца (JavaScript)',
'tog-editsection'             => 'ХIара дакъа "хувца" яха Iинк хьахокха',
'tog-editsectiononrightclick' => 'Декъам хувца кертмугIа аьтта цлицка я (JavaScript)',
'tog-showtoc'                 => 'Кортанче хьокха (кхьаннена дукхагIа кертмугIанаш йoлa оагIувна)',
'tog-rememberpassword'        => '(укх $1 {{PLURAL:$1|ден|деношкахь}}) мара са чувалара/чуялара дагалоаца дезаш дац',
'tog-watchcreations'          => 'Аз яь йола оагIувнаш теркама дагарчеча чуяккха',
'tog-watchdefault'            => 'Аз хийца йола оагIувнаш теркама дагарчеча чуяккха',
'tog-watchmoves'              => 'Аз цIи хийца йола оагIувнаш теркама дагарчеча чуяккха',
'tog-watchdeletion'           => 'Аз дIаяккха йола оагIувнаш теркама дагарчеча чуяккха',
'tog-minordefault'            => 'Теркамза хувцамаш лоархIамза белгалде',
'tog-previewontop'            => 'ГIалатнийсдара кора хьалхе бIаргтассам оттае',
'tog-previewonfirst'          => 'ГIалатнийсдаре дехьавоалаш/йоалаш бIаргтассам хьахьокха',
'tog-nocache'                 => 'Укхазара оагIувнаший лочкъараш дIадоаде',
'tog-enotifwatchlistpages'    => 'ОагIувнаший хувцамахь теркама дагарчера лаьца, д-фоштаца хоам бе',
'tog-enotifusertalkpages'     => 'Са дувцама оагIув тIа хувцамаш хилача, д-фоштаца хоам бе',
'tog-enotifminoredits'        => 'Геттара зIамига хувцамаш хилача, д-фоштаца хоам бе',
'tog-enotifrevealaddr'        => 'ЗIы хоамаш тIа са фоштмоттиг хьахьокха',
'tog-shownumberswatching'     => 'Ший теркама дагарченгахь оагIув чулаьца бола дакъалаьцархой таьрах хьахьокха',
'tog-fancysig'                => 'Ший кулга яздара вики-хоамбаккхам (ший лоIаме Iинка йоацаш)',
'tog-externaleditor'          => 'Арена гIалатнийсдарца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархара ший-тайпара оттам эша; [http://www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-externaldiff'            => 'Арена бIасакхосса болхоагIувца болх бе (ший болх ховш болачара мара мегаш дац, хьамлоархара ший-тайпара оттам эша; [http://www.mediawiki.org/wiki/Manual:External_editors хьажа эша])',
'tog-showjumplinks'           => '"Дехьадала" яха новкъостала Iинк хьахьокха',
'tog-uselivepreview'          => 'Сиха бIарахьажар (JavaScript) (Экспериментально)',
'tog-forceeditsummary'        => 'Хоам бе, хувцамий лоацам белгал даь деци',
'tog-watchlisthideown'        => 'Са хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthidebots'       => 'БIатий хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideminor'      => 'Са зIамига хувцамаш теркама дагарчера къайладаккха',
'tog-watchlisthideliu'        => 'Чубаьнна дакъалаьцархой хувцамаш теркама дагaрчеча къайлаяккха',
'tog-watchlisthideanons'      => 'ЦIи йоаца дакъалаьцархой хувцамаш теркама дагрчеча къайлаяккха',
'tog-watchlisthidepatrolled'  => 'Теркама дагарчера дIанийсъя хувцамаш къайлаяккха',
'tog-ccmeonemails'            => 'Аз дакъалаьцархошоа дахта каьхаташ са д-фошта тIа хьатIадайта',
'tog-diffonly'                => 'Диста къал йоалаж йола оагIувна дакъа ма хьокха',
'tog-showhiddencats'          => 'Къайла цатегаш хьахьокха',

'underline-always'  => 'Массаза',
'underline-never'   => 'ЦIаккха',
'underline-default' => 'МазбIарглокхарий оттамаш хайрамбе',

# Dates
'sunday'        => 'КIиранди',
'monday'        => 'Оршот',
'tuesday'       => 'Шинара',
'wednesday'     => 'Кхаьра',
'thursday'      => 'Ера',
'friday'        => 'ПIаьраска',
'saturday'      => 'Шоатта',
'sun'           => 'КIи',
'mon'           => 'Орш',
'tue'           => 'Шин',
'wed'           => 'Кха',
'thu'           => 'Ера',
'fri'           => 'ПIаь',
'sat'           => 'Шоа',
'january'       => 'Нажгамсхой',
'february'      => 'Саькур',
'march'         => 'Мутхьол',
'april'         => 'Тушоли',
'may_long'      => 'Бекарг',
'june'          => 'КIимарс',
'july'          => 'Аьтинг',
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
'june-gen'      => 'КIимарс бетт',
'july-gen'      => 'Аьтинг бетт',
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
'jun'           => 'КIим.',
'jul'           => 'Аьт.',
'aug'           => 'Манг.',
'sep'           => 'Моаж.',
'oct'           => 'Тов.',
'nov'           => 'Лайч.',
'dec'           => 'Чант.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Цатег|Цатегаш}}',
'category_header'                => '"$1" Цатегчу оагIувнаш',
'subcategories'                  => 'Чурацатегаш',
'category-media-header'          => '"$1" Цатегчу паьлаш',
'category-empty'                 => "''Укх цатегчоа цхьаккха оагIувнаш е паьлаш яц.''",
'hidden-categories'              => '{{PLURAL:$1|Къайла цатег|Къайла цатегаш}}',
'hidden-category-category'       => 'Къайла цатегаш',
'category-subcat-count'          => '{{PLURAL:$2|Йола цатег тIехьара бухцатег чулоаца.|{{PLURAL:$1|$1 бухцатег хьахекха я|$1 бухцатегаш хьахекха я}} $2 йолачара.}}',
'category-subcat-count-limited'  => 'Укх цатегий {{PLURAL:$1|$1 кIалцатег|$1 кIалцатегаш}}.',
'category-article-count'         => '{{PLURAL:$2|Йола цатег цхьа оагIув мара чулоацац.|{{PLURAL:$1|$1 оагIув хьахекха я|$1 оагIувнаш хьахекха я}} укх цатега $2 йолачарахь.}}',
'category-article-count-limited' => 'Укх цатегчоахь {{PLURAL:$1|$1 оагIув|$1 оагIувнаш|}}.',
'category-file-count'            => '{{PLURAL:$2|Укх цатего ца паьла мара чулоацац.|{{PLURAL:$1|$1 паьла хьахьекха я|$1 паьлаш хьахьекха я}} укх цатегий $2 долачаьрахь.}}',
'category-file-count-limited'    => 'Укх цатегчоахь {{PLURAL:$1|$1 паьл|$1 паьлаш}}.',
'listingcontinuesabbrev'         => 'дIахо',
'noindex-category'               => 'Моттигза оагIувнаш',

'about'         => 'Лоацам',
'article'       => 'Йоазув',
'newwindow'     => '(керда кора)',
'cancel'        => 'Юхавал',
'moredotdotdot' => 'ДIахо',
'mypage'        => 'Са оагIув',
'mytalk'        => 'Са дувцама оагIув',
'anontalk'      => 'Укх IP-моттига дувцам',
'navigation'    => 'Никътахкар',
'and'           => '&#32;кха',

# Cologne Blue skin
'qbfind'         => 'Лахар',
'qbbrowse'       => 'БIаргтасса',
'qbedit'         => 'Хувца',
'qbpageoptions'  => 'ОагIува оттамаш',
'qbpageinfo'     => 'ОагIува тохкам',
'qbmyoptions'    => 'Са оттамаш',
'qbspecialpages' => 'ГIулакхий оагIувнаш',
'faq'            => 'Каст-каста хаттараш',
'faqpage'        => 'Project:Каст-каста хаттараш',

# Vector skin
'vector-action-addsection'       => 'БIагал тIатоха',
'vector-action-delete'           => 'ДIадаккха',
'vector-action-move'             => 'ЦIи хувца',
'vector-action-protect'          => 'Лораде',
'vector-action-undelete'         => 'Юхаоттаде',
'vector-action-unprotect'        => 'Лорам тIерaбаккха',
'vector-simplesearch-preference' => 'Яьржа лахарий довзамаш чуяккха (Vector skin only)',
'vector-view-create'             => 'Кхоллам',
'vector-view-edit'               => 'Хувцар',
'vector-view-history'            => 'Искар',
'vector-view-view'               => 'Дешам',
'vector-view-viewsource'         => 'ЗIембаккхама бIаргтассам',
'actions'                        => 'Дулархам',
'namespaces'                     => 'ЦIерий аренаш',
'variants'                       => 'Дошаламаш',

'errorpagetitle'    => 'ГIалат',
'returnto'          => '$1 оагIув тIа юхавалар',
'tagline'           => 'Кечал укхазара я {{SITENAME}}',
'help'              => 'Новкъoстал',
'search'            => 'Тохкам',
'searchbutton'      => 'Хьалаха',
'go'                => 'Дехьавала',
'searcharticle'     => 'Дехьавала',
'history'           => 'искар',
'history_short'     => 'Искар',
'updatedmarker'     => 'Со ханача денца хувцамаш хиннaй',
'info_short'        => 'Лоаца маIандар',
'printableversion'  => 'Каьхати зарба бIаса',
'permalink'         => 'Даим латта Iинк',
'print'             => 'Каьхат арадаккха',
'edit'              => 'Хувца',
'create'            => 'Хьаде',
'editthispage'      => 'Ер оагIув хувца',
'create-this-page'  => 'Ep oагIув хьае',
'delete'            => 'ДIадаккха',
'deletethispage'    => 'Ер оагIув дIаяккха',
'undelete_short'    => 'Меттаоттае {{PLURAL:$1|хувцам|$1 хувцамаш}}',
'protect'           => 'Лораде',
'protect_change'    => 'хувца',
'protectthispage'   => 'Лорае ер оагIув',
'unprotect'         => 'Лорам тIерaбаккха',
'unprotectthispage' => 'Лорам тIерабаккха',
'newpage'           => 'Керда оагIув',
'talkpage'          => 'Укх оагIув тIа дувцам бе',
'talkpagelinktext'  => 'дувцам',
'specialpage'       => 'ГIулакха оагIув',
'personaltools'     => 'Са гIирсаш',
'postcomment'       => 'Керда декъам',
'articlepage'       => 'Йоазув тIа бIаргтасса',
'talk'              => 'Дувцам',
'views'             => 'БIаргтассараш',
'toolbox'           => 'ГIирсаш',
'userpage'          => 'Дакъалаьцачунна оагIуве бIаргтасса',
'projectpage'       => 'Хьахьоадайтама оагIуве бIаргтасса',
'imagepage'         => 'Паьла оагIув тIа бIаргтасса',
'mediawikipage'     => 'Xоама оагIув хьахокха',
'templatepage'      => 'ЧIабла оагIув тIа бIаргтасса',
'viewhelppage'      => 'ГIо деха',
'categorypage'      => 'Цатега оагIув тIа бIаргтасса',
'viewtalkpage'      => 'Дувцамага бIаргтасса',
'otherlanguages'    => 'Кхыча меттаxь',
'redirectedfrom'    => '($1 тIера хьадейта да)',
'redirectpagesub'   => 'дIа-хьа дайта оагIув',
'lastmodifiedat'    => 'Укх оагIув тIехьара  хувцам: $2, $1.',
'viewcount'         => 'Укх оагIув тIа бIаргтасса хиннад {{PLURAL:$1|цкъа|$1 шозза}}.',
'protectedpage'     => 'Лорама оагIув',
'jumpto'            => 'Укхаза дехьавала:',
'jumptonavigation'  => 'никътахкар',
'jumptosearch'      => 'леха',
'pool-queuefull'    => 'Хаттарий цIа хьалдизад',
'pool-errorunknown' => 'Довзаш доаца гIалат',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Лоацам {{SITENAME}}',
'aboutpage'            => 'Project:Лоацам',
'copyright'            => '$1 чулоацамаца такхоачаш да.',
'copyrightpage'        => '{{ns:project}}:Яздаьчунна бокъо',
'currentevents'        => 'ХIанзара хоамаш',
'currentevents-url'    => 'Project:ХIанзара хоамаш',
'disclaimers'          => 'Бокъонахь юхавалаp',
'disclaimerpage'       => 'Project:Бокъонахь юхавалаp',
'edithelp'             => 'Хувцама новкъостал',
'edithelppage'         => 'Help:ГIалатнийсдара новкъoстал',
'helppage'             => 'Help:Чулоацам',
'mainpage'             => 'Кертера оагIув',
'mainpage-description' => 'Кертера оагIув',
'policy-url'           => 'Project:Бокъонаш',
'portal'               => 'Гулламхой ков',
'portal-url'           => 'Project:Гулламхой ков',
'privacy'              => 'Паьлабокъо',
'privacypage'          => 'Project:Паьлабокъо',

'badaccess'        => 'Чуваларa гIалат',
'badaccess-group0' => 'Оаш хьадийха дулархам шунна де йийшяц.',
'badaccess-groups' => 'Дахта кхоачашдар {{PLURAL:$2|тоабачара|тоабашкара}} $1 дакъалаьцархой мара де бокъо яц.',

'versionrequired'     => '$1 MediaWiki доржам эша',
'versionrequiredtext' => 'Укх оагIув бeлха MediaWiki доржамаш эша $1. Хьажа [[Special:Version|version page]].',

'ok'                      => 'ХIаа',
'retrievedfrom'           => '"$1" ГIувам',
'youhavenewmessages'      => 'Оаш $1 ($2) дIайийцад',
'newmessageslink'         => 'керда хоамаш',
'newmessagesdifflink'     => 'тIехьара хувцамаш',
'youhavenewmessagesmulti' => 'Оаш $1чу керда хоамаш дIайийцад',
'editsection'             => 'хувца',
'editold'                 => 'хувца',
'viewsourceold'           => 'xьадоагIа къайладоагIа тIа бIаргтасса',
'editlink'                => 'хувца',
'viewsourcelink'          => 'xьадоагIа къайладоагIа тIа бIаргтасса',
'editsectionhint'         => 'Декъам хувца: $1',
'toc'                     => 'Чулоацам',
'showtoc'                 => 'хьахокха',
'hidetoc'                 => 'къайладаккха',
'thisisdeleted'           => '$1 бIаргтасса е юхаметтаоттаде?',
'viewdeleted'             => '$1 бIаргтасса?',
'restorelink'             => '{{PLURAL:$1|дIаяккха хувцам|$1 дIаяккха хувцамаш}}',
'feedlinks'               => 'Цу тайпара:',
'site-rss-feed'           => '$1 RSS мугI',
'site-atom-feed'          => '$1 Atom мугI',
'page-rss-feed'           => '"$1" RSS мугI',
'page-atom-feed'          => '"$1" Atom мугI',
'red-link-title'          => '$1 (укх тайпара оагIув яц)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Йоазув',
'nstab-user'      => 'Дакъалаьцархо',
'nstab-media'     => 'Медифаг',
'nstab-special'   => 'ГIулакха оагIув',
'nstab-project'   => 'Хьахьоадайтамахь лаьца',
'nstab-image'     => 'Паьл',
'nstab-mediawiki' => 'Хоам',
'nstab-template'  => 'Куцкеп',
'nstab-help'      => 'Новкъoстал',
'nstab-category'  => 'Цатег',

# Main script and global functions
'nosuchaction'      => 'Цу тайпара дулархам бац',
'nosuchspecialpage' => 'Изза мо гIооагIув яц',

# General errors
'error'              => 'ГIалат',
'missing-article'    => 'Кораде дезаш хинна оагIувни яздам корадаьдац «$1» $2.

Из мо гIалат нийсалуш хула, саг тишъенна Iинкаца, дадаьккха дола оагIувни хувца искара тIа чувала гIиртача.

Наггахь санна из иштта деци, шоана гIирса Iалаш деча гIалат корая хила мега.
Дехар да, [[Special:ListUsers/sysop|мазакулгалхочоа]] хоам бе, URL хьахокхаш.',
'missingarticle-rev' => '(бIаргоагIув № $1)',
'internalerror'      => 'Чура гIалат',
'internalerror_info' => 'Чура гIалат: $1',
'badtitle'           => 'Мегаш йоаца цIи',
'badtitletext'       => 'Дехаш дола оагIувни цIи, нийса яц, яьсса я е меттаюкъара е викиюкъара цIи харцахь я. ЦIера юкъе мегаш доаца харакъаш нийсадена хила мегаш да.',
'viewsource'         => 'Тахкам',

# Login and logout pages
'yourname'                => 'Дакъалаьцархочунна цIи:',
'yourpassword'            => 'КъайладоагIа:',
'yourpasswordagain'       => 'КъайладоагIа юха Iоязаде:',
'remembermypassword'      => '(укх $1 {{PLURAL:$1|ден|деношкахь}}) мара са чувалара/чуялара дагалоаца дезаш дац',
'yourdomainname'          => 'Шун цIеноагIув:',
'login'                   => 'Чувала/яла',
'nav-login-createaccount' => 'ЦIи яккха/Ший oагIув ела',
'loginprompt'             => 'Укх болхaоагIуваца доттагIал лаца, шун "cookies" йийла хьалдеза.',
'userlogin'               => 'ЦIи яккха/ОагIув ела',
'userloginnocreate'       => 'Чувала/яла',
'logout'                  => 'Аравала/яла',
'userlogout'              => 'Аравала/яла',
'notloggedin'             => 'Оаш шоай цIи хьааьннадац',
'nologin'                 => "Теркама йоазув яц? '''$1'''.",
'nologinlink'             => 'Лархама йоазув де',
'createaccount'           => 'Керда дакъалаьцархо кхолла',
'gotaccount'              => "Укхаза дIаязабенна дий шо? '''$1'''.",
'gotaccountlink'          => 'Чувала/яла',
'createaccountmail'       => 'Д-фоштаца',
'createaccountreason'     => 'Бахьан:',
'mailmypassword'          => 'Керда къайладоагIа хьаэца',
'loginlanguagelabel'      => 'Мотт: $1',

# Password reset dialog
'oldpassword'               => 'Къаьна къайладоагI:',
'newpassword'               => 'Керда къайладоагI:',
'retypenew'                 => 'Керда къайладоагIа юха Iоязаде:',
'resetpass-submit-loggedin' => 'КъайладогIа дIахувца',
'resetpass-submit-cancel'   => 'Юхавал/ялa',

# Edit page toolbar
'bold_sample'     => 'Сома яздам',
'bold_tip'        => 'Сома яздам',
'italic_sample'   => 'Кулга яздам',
'italic_tip'      => 'Кулга яздам',
'link_sample'     => 'Iинка кертмугI',
'link_tip'        => 'ЧураIинк',
'extlink_sample'  => 'Iинка кертдош http://www.example.com',
'extlink_tip'     => 'Арена Iинка (http:// тамагIахь дийца ма ле)',
'headline_sample' => 'KертмугIa яздама',
'headline_tip'    => '2-гIа кертмугIa лагIа',
'nowiki_sample'   => 'Укхаза кийчаде дезаш доаца яздам оттаде',
'nowiki_tip'      => 'Вики-бIасоттамахь теркам ма бе',
'image_tip'       => 'Чуяккха паьла',
'media_tip'       => 'Паьла Iинк',
'sig_tip'         => 'Шун кулгаяздар а, хIанзара ха а',
'hr_tip'          => 'Мухала мугI (могаш тайпара къеззига хайраде)',

# Edit pages
'summary'                          => 'Хувцамий белгалдер',
'subject'                          => 'БIагал/кертмугI:',
'minoredit'                        => 'ЗIамига хувцам',
'watchthis'                        => 'Укх оагIува теркам бе',
'savearticle'                      => 'ОагIув хьаязде',
'preview'                          => 'Хьалхе бIаргтассар',
'showpreview'                      => 'Хьалхе бIаргтaссар',
'showlivepreview'                  => 'Сиха бIаргтассар',
'showdiff'                         => 'Даь хувцамаш',
'anoneditwarning'                  => 'Зем хила! Шо кха чудаьннадац. Шун IP-моттиг укх хийца оагIув искаречу дIаяздаь хургъе.',
'summary-preview'                  => 'Лоацам ба:',
'subject-preview'                  => 'Кортяздам:',
'blockedtitle'                     => 'Дакъалаьцархо чIега бела ва/я',
'blockednoreason'                  => 'бахьан доацаш да',
'loginreqlink'                     => 'чувала/яла',
'accmailtitle'                     => 'КъайладоагI дIадахатад',
'newarticle'                       => '(Kерда)',
'newarticletext'                   => 'Шо йоаца оагIув тIа Iинкаца дехьадаьннад.
Из хьае, кIалхагIа доала корачу яздам очуязаде (кхета хала дале [[{{MediaWiki:Helppage}}|новкъостала оагIув тIа]] бIаргтасса).
Цаховш укхаза нийсадена дале, юхавала/яла яха тоIобама тIа пIелга тоIобе.',
'noarticletext'                    => "У сахьате укх оагIув тIа яздам доацаш да.
[[Special:Search/{{PAGENAME}}|цу тайпара цIи дувцам кораде]] кхыдола йоазувашкахь йийша я шун, вешта
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} тара дола таптарий йоазо карае], е
'''[{{fullurl:{{FULLPAGENAME}}|action=edit}} изза мо цIи йоалаш оагIув ела]'''</span>.",
'noarticletext-nopermission'       => 'Укх сахьате укх оагIув тIа яздам дац.
Шун йийшая, кхыдола йоазувнашкахь [[Special:Search/{{PAGENAME}}|дола цIерий хаттам корае]] е <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} нийсамий тептара йоазувнаш корае].</span>',
'note'                             => "'''ХIамоалар:'''",
'previewnote'                      => "'''Хьалхе бIаргтассам мара бац, яздам кха яздаь дац!'''",
'editing'                          => 'ГIалатнийсдар: $1',
'editingsection'                   => 'ГIалатнийсдар $1 (оагIувдакъа)',
'editingcomment'                   => 'ГIалатнийсдар $1 (керда декъам)',
'editconflict'                     => 'ГIалатнийсдара кховсам: $1',
'yourtext'                         => 'Хьа яздам',
'copyrightwarning'                 => "Теркам бе, $2 ($1 хьажа) бокъонаца лорадеш, тIахьежама кIала уллаш, оаш мел чуяккхаш дола хоамаш, яздамаш долга.
Наггахь санна шоай яздамаш пурам доацаш мала волашву саго хувца е кхы дола моттиге яздердолаш, безам беци, укхаз Iочуцаяздеча, дикаьгIа да.<br />
Оаш дош лу, даь дола хувцама да волга/йолга, е оаш пурам долаш Iочуяздеш да кхычера меттигара шоай яздамаш/хоамаш.<br />
'''ЯЗДАРХОЙ БОКЪОЦА ЛОРАДЕШ ДОЛА ХIАМАШ, ЦАРА ПУРАМ ДОАЦАШ, IОЧУМАЯЗАДЕ!'''",
'templatesused'                    => 'Укх бIаргоагIувни оагIув тIа лелая {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'templatesusedpreview'             => 'Хьалхе бIаргтассама оагIув тIа леладеш дола {{PLURAL:$1|Куцкеп|Куцкепаш}}:',
'template-protected'               => ' (лорам лаьца бa)',
'template-semiprotected'           => '(дакъа-лорам)',
'hiddencategories'                 => 'Ер оагIув укх {{PLURAL:$1|къайла цатегаца|къайла цатегашца}} дакъа лоаца:',
'permissionserrorstext-withaction' => '$2 де бокъо яц {{PLURAL:$1|из бахьан долаш|из бахьанаш долаш}}:',
'recreate-moveddeleted-warn'       => "'''Зем бе! Шо хьалххе дайоаккхаш хина оагIув хьае гIарта.'''

Хьажа, бокъонцахь езаш йолга.
КIалхагIа укх оагIуви дIадаккхамeи цIи хувцамeи тептараш хьекха да.",
'moveddeleted-notice'              => 'Ер оагIув дIаяккха хиннай.
Новкъостала, кIалха дIадаккхама а хувцама а тептарашкера нийсама йоазувнаш хьахьекха я.',
'log-fulllog'                      => 'Деррига таптара бIаргтасса',
'edit-conflict'                    => 'Хувцамий кховсам.',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Зембакхам: дагара куцкепаш чулоаца дустам геттара доккха да.
Цхьадола куцкепаш чулоацалургдац.',
'post-expand-template-inclusion-category' => 'Чулоаца куцкепий мегаш дола дустам дукхалена тIехьайоала оагIувнаш',
'post-expand-template-argument-warning'   => 'Зем бе! Ер оагIув цаI куцкепа аьладош мара чулоацац, юхадастара сел доккха дустам йолаш.
Цу тайпара аьладешаш IокIаладаьккха да.',
'post-expand-template-argument-category'  => 'Куцкепий теркамза аьладешаш чулоаца оагIувнаш',

# History pages
'viewpagelogs'           => 'Укх оагIува тептараш хокха',
'currentrev-asof'        => '$1 тIа эггара тIехьара доржам',
'revisionasof'           => '$1 доржам',
'revision-info'          => '$1; $2 хувцам',
'previousrevision'       => '← Xьалхарча',
'nextrevision'           => 'TIадоагIа →',
'currentrevisionlink'    => 'Дола доржам',
'cur'                    => 'хIанз.',
'next'                   => 'тIехь.',
'last'                   => 'хьалх.',
'page_first'             => 'хьалхара',
'page_last'              => 'тIехьара',
'histlegend'             => "Кхетам: (хIанз.) = хIанза йолачунна бIаргоагIувни хьакъоастам ба; (хьалх.) = хьалха хинначунна бIаргоагIувни хьакъоастам ба; '''зI''' = зIамига хьахувцам ба.",
'history-fieldset-title' => 'Искара бIаргтасса',
'history-show-deleted'   => 'ДIадаьккхараш мара',
'histfirst'              => 'къаьнараш',
'histlast'               => 'ха яннараш',

# Revision feed
'history-feed-title'          => 'Хувцамий искар',
'history-feed-description'    => 'Укх оагIуви вики тIа хувцамий искар',
'history-feed-item-nocomment' => '$1гIара $2гIачу',

# Revision deletion
'rev-delundel'               => 'хьахокха/къайлаяккха',
'rev-showdeleted'            => 'хьахокха',
'revdelete-show-file-submit' => 'XIаа',
'revdelete-radio-set'        => 'XIаа',
'revdelete-radio-unset'      => 'A',
'revdelete-log'              => 'Бахьан',
'revdelete-logentry'         => '[[$1]] доржама оагIувни бIасанче хийцай',
'revdel-restore'             => ' БIасанче хувца',
'revdel-restore-deleted'     => 'дIадаьккха доржамаш',
'revdel-restore-visible'     => 'бIаргагушдола доржамаш',
'pagehist'                   => 'ОагIува искар',
'deletedhist'                => 'ДIадакхамий искар',
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
'searchmenu-exists'                => "'''Укх вики-хьахьоадайтама чу ер оагув \"[[:\$1]]\" я'''",
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
'timezoneregion-africa'     => 'Эпарке',
'timezoneregion-america'    => 'Iамрике',
'timezoneregion-antarctica' => 'Энтарцит',
'timezoneregion-arctic'     => 'Эрцит',
'timezoneregion-asia'       => 'Iазике',
'timezoneregion-atlantic'   => 'Iатлантицфорд',
'timezoneregion-australia'  => 'Устралике',
'timezoneregion-europe'     => 'Эврофаьге',
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
'show'                            => 'Хьахокха',
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
'imagelinks'                => 'Паьла Iинкаш',
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
'statistics-pages' => 'ОагIувний',

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
'addedwatch'        => 'Теркама оагIув тIа дIатIаяккха я',
'addedwatchtext'    => '"[[:$1]]" оагIув, шун [[Special:Watchlist|теркама дагаршкахь]] чуяккха я. 
Техьара мел йола укх оагIувни хувцамаш цу дагаршкахь хоам беш хургья. Вешта [[Special:RecentChanges|керда хувцама дагаршкаехь]] сома къоалмаца хьакъоастлуш хургья.',
'removedwatch'      => 'Теркама дагарчера дIаяккха я',
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
'deletedtext'           => '"<nowiki>$1</nowiki>" дIаяккха хиннай.
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
'protect-text'                => "'''<nowiki>$1</nowiki>''' укхаз шоана шоай оагIув лорамлагIа хувца a бIаргтасса a йийш хургья.",
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
'isimage'                  => 'Сурта Iинк',
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
'tooltip-search'                  => ' Цу тайпара дош лаха {{SITENAME}}',
'tooltip-search-go'               => ' Изза мо цIи йолаш оагIув тIa дехьавала',
'tooltip-search-fulltext'         => ' Изза мо яздам долаш оагIувнаш лаха',
'tooltip-p-logo'                  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage'              => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage-description'  => 'Кертера оагIув тIа дехьавала',
'tooltip-n-portal'                => 'Хьахьоадайтамахь лаьца, хьа де йийшдар, фа а мичча а йоала',
'tooltip-n-currentevents'         => 'ХIанзара хоамий дагарче',
'tooltip-n-recentchanges'         => ' ТIехьара хувцамий дагарче',
'tooltip-n-randompage'            => ' Бе йоаца оагIув ела',
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

# EXIF tags
'exif-imagewidth'       => 'Шерал',
'exif-imagelength'      => 'Лакхал',
'exif-imagedescription' => 'Сурти цIи',
'exif-artist'           => 'Яздархо',
'exif-colorspace'       => 'Басара аре',
'exif-pixelydimension'  => 'Сурти шерал',
'exif-pixelxdimension'  => 'Сурти лакхал',

'exif-scenecapturetype-1' => 'ЛаьттабIаса',
'exif-scenecapturetype-2' => 'Сурт',

# External editor support
'edit-externally'      => 'Йола болхоагIувца паьла гIалатахь мукъаяккха',
'edit-externally-help' => '(ма даррачунга хьажа [http://www.mediawiki.org/wiki/Manual:External_editors хьаоттама кулгалхо])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'деррига',
'imagelistall'     => 'деррига',
'watchlistall2'    => 'деррига',
'namespacesall'    => 'деррига',
'monthsall'        => 'деррига',
'limitall'         => 'деррига',

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
