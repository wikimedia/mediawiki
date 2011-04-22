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
'tog-extendwatchlist'         => 'Шеръя теркама дагарче, массайола хувцамаш чулоацаш',
'tog-usenewrc'                => 'Ериг йоалаж йоа керда хувцамаш (JavaScript)',
'tog-numberheadings'          => 'Кертера-деша таьрахь автоматически оттайе',
'tog-showtoolbar'             => 'Хувцамаш еж йоа панель инструментов хьокха (JavaScript)',
'tog-editondblclick'          => 'ОагIув хувца шозза клик йича (JavaScript)',
'tog-editsection'             => 'ХIара дакъа "хувца" ссылк хьахьокха',
'tog-editsectiononrightclick' => 'Дакъа хувца дакъа-цIерах аьтта клик йича (JavaScript)',
'tog-showtoc'                 => 'Оглавление хьокха (цу оагIувна кхьаннена дукхагIа дакъа йеле)',
'tog-rememberpassword'        => 'У компьютеретъ се цIи дагалаца (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Аз хьаеж йоа оагIонаш со хьежача спискаех тIатоха',
'tog-watchdefault'            => 'Аз нийсъйеж йоа оагIонаш со хьежача спискаех тIатоха',
'tog-watchmoves'              => 'Аз цIи хийца оагIонаш со хьежача спискаех тIатоха',
'tog-watchdeletion'           => 'Аз дIаяькха оагIонаш со хьежача спискаех тIатоха',
'tog-minordefault'            => 'Ериг еж йоа хувцамаш лоархIаме йоацаж сен белгалде',
'tog-previewontop'            => 'Хувцамаш еж бIарахьажа хьалха',
'tog-previewonfirst'          => 'Эггара хьалха хувцамаш еж бIарахьажа хьалха',
'tog-nocache'                 => 'ОагIувна кеш е дехка',
'tog-enotifwatchlistpages'    => 'Э-майл хьадайта суна со хьежача оагIув хийцача',
'tog-enotifusertalkpages'     => 'Э-майл хьадайта суна аз къамял деж оагIув хийцача',
'tog-enotifminoredits'        => 'Э-майл хьадайта суна лоархIаме йоацаж йоа хувцамаш йиеча',
'tog-enotifrevealaddr'        => 'Хьокха са э-майл',
'tog-shownumberswatching'     => 'Хьокха масса сег хьежаш ба',
'tog-fancysig'                => 'Йоалача бесса кулг яздар (автоматически ссылк йоацаж)',
'tog-externaleditor'          => 'Ший йоала редактор харжа',
'tog-externaldiff'            => 'Диста де ший йоалла програм харжа',
'tog-showjumplinks'           => '"Дехьадала" ссылк хьахьокха',
'tog-uselivepreview'          => 'Сиха бIарахьажар (JavaScript) (Экспериментально)',
'tog-forceeditsummary'        => 'Хоам бе суна хувцамаш малагIа ер списка йеце',
'tog-watchlisthideown'        => 'Аз йа хувцамаш со хьежача спискаех ма хьокха',
'tog-watchlisthidebots'       => "Бот'ыз йа хувцамаш со хьежача спискаех ма хьокха",
'tog-watchlisthideminor'      => 'ЛоархIаме йоацаж йоа хувцамаш со хьежача спискаех ма хьокха',
'tog-ccmeonemails'            => 'Вуужаште аз дIадьахта э-майл суна а хьадайта',
'tog-diffonly'                => 'Диста къал йоалаж йоа оагIувна дакъа ма хьаокха',

'underline-always'  => 'Массаза',
'underline-never'   => 'ЦIаккха',
'underline-default' => 'Браузер настройкаш хьаэца',

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
'pagecategories'           => '{{PLURAL:$1|Цатег|Цатегаш}}',
'category_header'          => '"$1" Цатегчу оагIувнаш',
'subcategories'            => 'Чурацатегаш',
'category-media-header'    => '"$1" Цатегчу паьлаш',
'category-empty'           => "''Укх цатегчоа цхьаккха оагIувнаш е паьлаш яц.''",
'hidden-categories'        => '{{PLURAL:$1|Къайла цатег|Къайла цатегаш}}',
'hidden-category-category' => 'Къайла цатегаш',
'listingcontinuesabbrev'   => 'дIахо',

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
'info_short'        => 'ХIаммаIандар',
'printableversion'  => 'Каьхат арадaккха бIаса',
'permalink'         => 'Даим латта Iинк',
'print'             => 'Каьхат арадаккха',
'view'              => 'БIаргтассар',
'edit'              => 'Хувца',
'create'            => 'Хьаде',
'editthispage'      => 'Ер оагIув хувца',
'create-this-page'  => 'Ep oагIув хьае',
'delete'            => 'ДIадаккха',
'deletethispage'    => 'Ер оагIув дIаяккха',
'undelete_short'    => 'Меттаоттае {{PLURAL:$1|хувцам|$1 хувцамаш}}',
'viewdeleted_short' => '
БIаргтасса {{PLURAL:$1|дIадаьккха хувцам тIа|$1 дIадаьккха хувцамаш тIа}}',
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

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Лоацам {{SITENAME}}',
'aboutpage'            => 'Project:Лоацам',
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
'editsectionhint'         => ' Тоабдакъа хувца: $1',
'toc'                     => 'Чулоацам',
'showtoc'                 => 'хьахокха',
'hidetoc'                 => 'къайладаккха',
'collapsible-collapse'    => 'чудерзаде',
'collapsible-expand'      => 'хьадоаржаде',
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
'nosuchaction' => 'Цу тайпара дулархам бац',

# General errors
'viewsource' => 'Тахкам',

# Login and logout pages
'yourname'                => 'Дакъалаьцархочунна цIи:',
'yourpassword'            => 'КъайладоагIа:',
'yourpasswordagain'       => 'КъайладоагIа юха Iоязаде:',
'login'                   => 'Чувала',
'nav-login-createaccount' => 'ЦIи яккха/Ший oагIув ела',
'userlogin'               => 'ЦIи яккха/ОагIув ела',
'userloginnocreate'       => 'Чувала',
'logout'                  => 'Аравала',
'userlogout'              => 'Аравала',
'notloggedin'             => 'Оаш шоай цIи хьааьннадац',
'gotaccountlink'          => 'Чувала',
'mailmypassword'          => 'Керда къайладоагIа хьаэца',

# Edit page toolbar
'link_sample'     => 'Iинка кертмугI',
'link_tip'        => 'ЧураIинк',
'headline_sample' => 'KертмугIa яздама',
'headline_tip'    => '2-гIа кертмугIa лагIа',
'nowiki_sample'   => 'Укхаза кийчаде дезаш доаца яздам оттаде',
'image_tip'       => 'Чуяккха паьла',
'media_tip'       => 'Паьла Iинк',
'sig_tip'         => 'Шун кулгаяздар а, хIанзара ха а',
'hr_tip'          => 'Мухала мугI (могаш тайпара къеззига хайраде)',

# Edit pages
'summary'            => 'Хувцамий белгалдер',
'subject'            => 'БIагал/кертмугI:',
'minoredit'          => 'ЗIамига хувцам',
'watchthis'          => 'Укх оагIува теркам бе',
'savearticle'        => 'ОагIув хьаязде',
'preview'            => 'Хьалхе бIаргтассар',
'showpreview'        => 'Хьалхе бIаргтaссар',
'showlivepreview'    => 'Сиха бIаргтассар',
'showdiff'           => 'Даь хувцамаш',
'anoneditwarning'    => 'Зем хила! Шо кха чудаьннадац. Шун IP-моттиг укх хийца оагIув искаречу дIаяздаь хургъе.',
'newarticle'         => '(Kерда)',
'note'               => "'''ХIамоалар:'''",
'editing'            => 'ГIалатнийсдар: $1',
'yourtext'           => 'Хьа яздам',
'template-protected' => ' (лорам лаьца бa)',

# History pages
'viewpagelogs'           => 'Укх оагIува тептараш хокха',
'revisionasof'           => '$1 доржам',
'previousrevision'       => '← Xьалхарча',
'nextrevision'           => 'TIадоагIа →',
'currentrevisionlink'    => 'Дола доржам',
'cur'                    => 'хIанз.',
'next'                   => 'тIехь.',
'last'                   => 'хьалх.',
'page_first'             => 'хьалхара',
'page_last'              => 'тIехьара',
'history-fieldset-title' => 'Искара бIартасса',
'histfirst'              => 'къаьнараш',
'histlast'               => 'ха яннараш',

# Revision deletion
'rev-delundel'               => 'хьахокха/къайлаяккха',
'rev-showdeleted'            => 'хьахокха',
'revdelete-show-file-submit' => 'XIаа',
'revdelete-radio-set'        => 'XIаа',
'revdelete-radio-unset'      => 'A',
'revdelete-log'              => 'Бахьан',
'revdel-restore'             => ' БIасанче хувца',
'pagehist'                   => 'ОагIува искар',
'revdelete-uname'            => 'дакъалаьцархочунна цIи',

# Merge log
'revertmerge' => ' Декъа',

# Diffs
'lineno'                  => 'МугI $1:',
'compareselectedversions' => 'Хьаржа доржамаша тарона тIа хьажа',
'editundo'                => 'белгалдаккха',

# Search results
'searchresults'             => 'Тахкама гIулакххилар',
'searchresults-title'       => '"$1" тахка',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3) хьажа',
'searchhelp-url'            => 'Help:Чулоацам',
'search-result-size'        => ' $1 ({{PLURAL:$2|1 дош|$2 дешаш}})',
'search-section'            => ' (дакъа $1)',
'search-interwiki-caption'  => 'Гаргалона хьахьоадайтамаш',
'search-interwiki-default'  => '$1 толамчаш:',
'search-interwiki-more'     => '(кха)',
'search-mwsuggest-enabled'  => ' Хьехамашца',
'search-mwsuggest-disabled' => ' Хьехамаш боацаш',
'powersearch'               => ' Доккха тахкар',
'powersearch-legend'        => ' Доккха тахкар',
'powersearch-ns'            => ' ЦIерий аренашкахь лахар',
'powersearch-field'         => 'Лахар',

# Preferences page
'preferences'   => 'Оттамаш',
'mypreferences' => 'Оттамаш',
'skin-preview'  => 'Хьажа',

# Groups
'group-sysop' => 'Мазакулгалхой',

# User rights log
'rightslog' => 'Дакъалаьцархочунна бокъона тептар',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Ер оагIув хувца',

# Recent changes
'nchanges'             => '$1 {{PLURAL:$1|хувцам|хувцамаш}}',
'recentchanges'        => 'Керда хувцамаш',
'recentchanges-legend' => 'Керда хувцамий оттамаш',
'rclistfrom'           => '$1 тIара хувцамаш хьахокха',
'rcshowhideminor'      => 'зIамига хувцамаш $1',
'rcshowhideliu'        => 'Чубаьнначара дакъалаьцархочий $1',
'rcshowhideanons'      => 'цIияьккханза дакъалаьцархой $1',
'rcshowhidemine'       => '$1 сай хувцамаш',
'diff'                 => 'кхы.',
'hist'                 => 'искар',
'hide'                 => 'Къайладаккха',
'show'                 => 'Хьахокха',
'minoreditletter'      => 'м',
'newpageletter'        => 'Н',
'boteditletter'        => 'б',

# Recent changes linked
'recentchangeslinked'      => 'Гаргалона хувцамаш',
'recentchangeslinked-page' => 'ОагIува цIи',

# Upload
'upload' => 'Паьл чуяккха',

# File description page
'file-anchor-link'    => 'Паьл',
'filehist'            => 'Паьла искар',
'filehist-current'    => 'xIанзара',
'filehist-datetime'   => 'Дентаьрахь/Ха',
'filehist-thumb'      => 'ЗIамигасуртанче',
'filehist-user'       => 'Дакъалаьцархо',
'filehist-dimensions' => 'ХIамана дустам',
'filehist-filesize'   => 'Паьла юстарал',
'filehist-comment'    => 'ХIамоалар',
'filehist-missing'    => 'Паьла йоацаш я',
'imagelinks'          => 'Паьла Iинкаш',
'sharedupload'        => 'Ер паьла $1чера я, кхыча хьахьоадайтамча хьахайраде йийшайолаш я.',

# File reversion
'filerevert-comment' => 'Бахьан:',

# File deletion
'filedelete-comment' => 'Бахьан:',
'filedelete-submit'  => 'ДIадаккха',

# MIME search
'download' => 'хьачуяккха',

# Unwatched pages
'unwatchedpages' => 'Теркамза оагIувнаш',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|бIат|бIаташ}}',
'nmembers'     => '$1 {{PLURAL:$1|дакъалаьцархо|дакъалаьцархой}}',
'newpages'     => 'Керда оагIувнаш',
'move'         => 'ЦIи хувца',
'movethispage' => 'Укх оагIува цIи хувца',

# Book sources
'booksources'               => 'Китабий гIувам',
'booksources-search-legend' => 'Китаба хIаммаIандара тахкам',
'booksources-go'            => 'Лаха',

# Special:Log
'log' => 'Тептараш',

# Special:AllPages
'allpages'       => 'Еррига оагIувнаш',
'allarticles'    => 'Еррига оагIувнаш',
'allpagessubmit' => 'Кхоачашде',

# Special:Categories
'categories' => 'Цатегаш',

# Special:LinkSearch
'linksearch'    => 'ЧураIинкаш',
'linksearch-ok' => 'Лаха',

# E-mail user
'emailuser' => 'Дакъалаьцархочоа E-mail',

# Watchlist
'watchlist'     => 'Теркама дагарче',
'mywatchlist'   => 'Теркама дагарче',
'addedwatch'    => 'Теркама оагIув тIа дIатIаяккха я',
'removedwatch'  => 'Теркама дагарчера дIаяккха я',
'watch'         => 'Тохкам бе',
'watchthispage' => 'Укх оагIува теркам бе',
'unwatch'       => ' Лора ма де',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Тохкам беча оагIув тIа даккха',
'unwatching' => 'Тохкам беча оагIув тIера дIадаккха',

# Delete
'deletepage'            => 'ОагIув дIаяккха',
'actioncomplete'        => 'Дулархам баьб',
'deletedarticle'        => ' "[[$1]]" дIадаьккхад',
'dellogpage'            => 'ДIадаккхара тептар',
'deletecomment'         => 'Бахьан:',
'deletereasonotherlist' => 'Кхыдола бахьан',

# Protect
'protectlogpage'              => 'Лорама тептар',
'protectcomment'              => 'Бахьан:',
'protectexpiry'               => 'Кхоачалуш латта:',
'protect_expiry_invalid'      => 'Чаккхабоала лорама харца ха',
'protect_expiry_old'          => 'Чаккхайоала ха - яха зама я.',
'protect-text'                => "'''<nowiki>$1</nowiki>''' укхаз шоана шоай оагIув лорамлагIа хувца a бIаргтасса a йийш хургья.",
'protect-default'             => 'Лорамза',
'protect-fallback'            => '"$1" пурам эша',
'protect-level-autoconfirmed' => 'Керда а, дакъалаьцабоацачаьрахь а лораде',
'protect-level-sysop'         => 'Мазакулгалхо мара чувала бокъо яц',
'protect-summary-cascade'     => 'хурхала',
'protect-expiring'            => 'чаккхайоала',
'protect-cascade'             => 'Укх оагIувче чуяккха оагIуваш лорае (хурхала лорам)',
'restriction-type'            => 'Бокъонаш:',
'restriction-level'           => 'Чувоала лагIа:',

# Restrictions (nouns)
'restriction-edit'   => 'ГIалатнийсдар',
'restriction-move'   => 'ЦIи хувцаp',
'restriction-create' => 'Кхоллам',
'restriction-upload' => 'Чудаккхар',

# Undelete
'undeletelink' => ' БIаргтасса/юхаметтаоттаде',

# Namespace form on various pages
'namespace'      => 'ЦIерий аренаш',
'blanknamespace' => '(Корта)',

# Contributions
'contributions'       => 'Дакъалаьцархочунна къахьегам',
'contributions-title' => '$1 дакъалаьцархочунна къахьегам',
'mycontris'           => 'Са къахьегам',
'uctop'               => '(тIехьара)',

'sp-contributions-blocklog' => 'чIегаш',
'sp-contributions-talk'     => 'дувцам',
'sp-contributions-search'   => 'Къахьегама лахар',
'sp-contributions-username' => 'IP-моттиг е цIи:',
'sp-contributions-submit'   => 'Хьалаха',

# What links here
'whatlinkshere'           => 'Iинкаш укхаза',
'whatlinkshere-page'      => 'ОагIув',
'isredirect'              => 'дIа-хьа оагIув',
'istemplate'              => 'чудаккхар',
'isimage'                 => 'Сурта Iинк',
'whatlinkshere-links'     => '← Iинкаш',
'whatlinkshere-hidelinks' => '$1 Iинкаш',
'whatlinkshere-filters'   => 'ЦIенъераш',

# Block/unblock
'blockip'           => 'Дакъалаьцархочунна чIега бола',
'ipblocklist'       => 'ЧIега бела дакъалаьцархой',
'blocklink'         => 'чIегa тоха',
'unblocklink'       => 'чIега баста',
'change-blocklink'  => 'ЧIегатохар хувца',
'contribslink'      => ' къахьегам',
'blocklogpage'      => 'ЧIегаш тoха таптар',
'blockme'           => 'ЧIега бола сона',
'proxyblocksuccess' => 'Хьадаьд.',

# Move page
'move-page-legend' => 'ОагIува цIи хувца',
'movearticle'      => 'ОагIува цIи хувца',
'newtitle'         => 'Керда цIи',
'movepagebtn'      => 'ОагIува цIи хувца',
'pagemovedsub'     => ' ОагIув керд цIи тилла я',
'articleexists'    => 'Изза мо цIи йола оагIув, йолаш я е оаш тила цIи мегаш яц.
Дехар да, кхыйола цIи хьаржа.',
'movedto'          => 'керда цIи тилла я',
'movelogpage'      => 'Хувцама тептар',
'movereason'       => 'Бахьан',

# Export
'export' => 'ОагIувий эхфортам',

# Namespace 8 related
'allmessagesname'           => 'ЦIи',
'allmessages-filter-all'    => 'Дерригаш',
'allmessages-language'      => 'Мотт:',
'allmessages-filter-submit' => 'Дехьавала',

# Thumbnails
'thumbnail-more' => 'Хьадоккхаде',

# Special:Import
'import-upload-filename' => 'ПаьлацIи:',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Дакъалаьцархочунна оагIув',
'tooltip-pt-mytalk'              => 'Шун дувцамий оагIув',
'tooltip-pt-preferences'         => ' Шун оттамаш',
'tooltip-pt-mycontris'           => 'Шун хувцамаш',
'tooltip-pt-login'               => 'Укхаза хьай цIи аьле чувала йийша я, амма чуцаваьлача хIамма а дац',
'tooltip-pt-logout'              => 'Аравала',
'tooltip-ca-talk'                => 'ОагIува чулоацамий дувцам',
'tooltip-ca-edit'                => 'Ер оагIув хувца йийшйолаш я. Дехар да, Iалаш елаьхь, хьалхе бIаргтассама оагIув тIа бIаргтасса.',
'tooltip-ca-addsection'          => 'Керда декъам хьаде',
'tooltip-ca-protect'             => 'Eр оагIув лорае',
'tooltip-ca-delete'              => 'Ер оагIув дIаяккха',
'tooltip-ca-move'                => 'Укх оагIува цIи хувца',
'tooltip-ca-watch'               => 'Ер оагIув теркам беча каьхата тIа яккха',
'tooltip-ca-unwatch'             => 'Ер оагIув теркам беча каьхата тIара дIаяккха',
'tooltip-search'                 => ' Цу тайпара дош лаха {{SITENAME}}',
'tooltip-search-go'              => ' Изза мо цIи йолаш оагIув тIa дехьавала',
'tooltip-search-fulltext'        => ' Изза мо яздам долаш оагIувнаш лаха',
'tooltip-n-mainpage'             => 'Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage-description' => 'Кертера оагIув тIа дехьавала',
'tooltip-n-portal'               => 'Хьахьоадайтамахь лаьца, хьа де йийшдар, фа а мичча а йоала',
'tooltip-n-recentchanges'        => ' ТIехьара хувцамий дагарче',
'tooltip-n-randompage'           => ' Бе йоаца оагIув ела',
'tooltip-n-help'                 => 'Новкъостала моттиг',
'tooltip-t-whatlinkshere'        => 'Массайола оагIувий дагарче, укх оагIув тIа Iинкаш луш йола',
'tooltip-feed-rss'               => 'Укх оагIувна RSSчу гойтар',
'tooltip-feed-atom'              => 'Укх оагувна Atomчу гойтар',
'tooltip-t-contributions'        => 'Укх дакъалаьцархочу хьийца йола оагIувнаш хьахокха',
'tooltip-t-emailuser'            => 'Укх дакъалаьцархочоа зIы яхьийта',
'tooltip-t-upload'               => 'Паьлаш чуяккха',
'tooltip-t-specialpages'         => 'ГIулакха оагIувний дагарчe',
'tooltip-t-permalink'            => 'Укх оагIув доржама даим латта Iинк',
'tooltip-ca-nstab-main'          => 'Йоазува чулоацам',
'tooltip-ca-nstab-user'          => 'Дакъалаьцархочунна ший оагIув',
'tooltip-ca-nstab-special'       => 'Ер гIулакха оагIув я, из хувца хьо бокъо йолаш вац/яц.',
'tooltip-ca-nstab-project'       => 'Хьахьоадайтама оагIув',
'tooltip-ca-nstab-image'         => 'Паьла оагIув',
'tooltip-ca-nstab-template'      => 'Куцкепа оагIув',
'tooltip-ca-nstab-category'      => 'Цатега оагIув',
'tooltip-minoredit'              => 'Ер хувцар башха доаца санна белгалде',
'tooltip-save'                   => 'Хувцамаш кходе',
'tooltip-watch'                  => 'Ер оагIув теркам беча каьхата тIа яккха',

# Browsing diffs
'previousdiff' => 'Хьалхара хувцам',
'nextdiff'     => 'ТIайоагIа хувцам',

# Special:NewFiles
'noimages' => 'Суртaш бIаргагуш дац.',
'ilsubmit' => 'Лаха',

# EXIF tags
'exif-artist'       => 'Яздархо',
'exif-writer'       => 'Яздама да',
'exif-languagecode' => 'Мотт',
'exif-iimcategory'  => 'Цатег',

'exif-scenecapturetype-1' => 'ЛаьттабIаса',
'exif-scenecapturetype-2' => 'Сурт',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'деррига',
'imagelistall'     => 'деррига',
'watchlistall2'    => 'деррига',
'namespacesall'    => 'деррига',
'monthsall'        => 'деррига',
'limitall'         => 'деррига',

# Multipage image navigation
'imgmultigo'   => 'Дехьавала!',
'imgmultigoto' => '$1 оагIув тIа дехьавала',

# Table pager
'table_pager_limit_submit' => 'Кхоачашде',

# Special:Version
'version'              => 'Доржам',
'version-specialpages' => 'ГIулакхий оагIувнаш',

# Special:FilePath
'filepath'        => 'Паьлачу никъ',
'filepath-page'   => 'Паьл:',
'filepath-submit' => 'Дехьавала',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'ПаьлацIи:',

# Special:SpecialPages
'specialpages' => 'ГIулакхий оагIувнаш',

# HTML forms
'htmlform-submit'              => 'ДIадахийта',
'htmlform-selectorother-other' => 'Кхыдола',

);
