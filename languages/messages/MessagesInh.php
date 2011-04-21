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
'tog-underline'               => '↓ Iинкаш белгалде:',
'tog-highlightbroken'         => 'Йоaцаж йоa ссылкаш <a href="" class="new">ер тайпа</a> хьокха (е ер тайпа<a href="" class="internal">?</a>).',
'tog-justify'                 => '↓ ФарагIап хьанийсъе',
'tog-hideminor'               => 'ЛоархIаме йоацаж йоа хувцамаш спискаех ма хьокха',
'tog-extendwatchlist'         => 'Со хьежачана спискаех лоархIаме йоалаж йоа хувцамаш хьокха',
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
'january'       => '↓ Нажгамсхой',
'february'      => '↓ Саькур',
'march'         => '↓ Мутхьол',
'april'         => '↓ Тушоли',
'may_long'      => '↓ Бекарг',
'june'          => '↓ КIимарс',
'july'          => '↓ Аьтинг',
'august'        => '↓ Мангал',
'september'     => '↓ Моажол',
'october'       => '↓ Тов',
'november'      => '↓ Лайчил',
'december'      => '↓ Чантар',
'january-gen'   => '↓ Нажгамсхой',
'february-gen'  => '↓ Саькур',
'march-gen'     => '↓ Мутхьол',
'april-gen'     => '↓ Тушоли',
'may-gen'       => '↓ Бекарг',
'june-gen'      => '↓ КIимарс',
'july-gen'      => '↓ Аьтинг',
'august-gen'    => '↓ Мангал',
'september-gen' => '↓ Моажол',
'october-gen'   => '↓ Тов',
'november-gen'  => '↓ Лайчил',
'december-gen'  => '↓ Чантар',
'jan'           => '↓ Нажг.',
'feb'           => '↓ Саьк.',
'mar'           => '↓ Мутхь.',
'apr'           => '↓ Туш.',
'may'           => '↓ Бек.',
'jun'           => '↓ КIим.',
'jul'           => '↓ Аьт.',
'aug'           => '↓ Манг.',
'sep'           => '↓ Моаж.',
'oct'           => '↓ Тов.',
'nov'           => '↓ Лайч.',
'dec'           => '↓ Чант.',

# Categories related messages
'pagecategories'           => '↓ {{PLURAL:$1|Цатег|Цатегаш}}',
'category_header'          => '↓ "$1" Цатег тIа оагIувнаш',
'subcategories'            => '↓ Чурацатегаш',
'category-media-header'    => '↓ "$1" Цатег тIа паьлаш',
'category-empty'           => "↓ ''Укх цатегчоа цхьаккха оагIувнаш е паьлаш яц.''",
'hidden-categories'        => '↓ {{PLURAL:$1|Къайла цатег|Къайла цатегаш}}',
'hidden-category-category' => '↓ Къайла цатегаш',

'about'         => '↓ Куц',
'article'       => '↓ Йоазув',
'newwindow'     => '↓ (керда корага хьадела)',
'cancel'        => '↓ Юхавал',
'moredotdotdot' => '↓ ДIахо',
'mypage'        => '↓ Са оагIув',
'mytalk'        => '↓ Са дувцама оагIув',
'anontalk'      => '↓ Укх IP зIы дувцам',
'navigation'    => '↓ Никътахкар',
'and'           => '↓ &#32;кха',

# Cologne Blue skin
'qbfind'         => '↓ Лахар',
'qbbrowse'       => '↓ БIаргтасса',
'qbedit'         => '↓ Хувца',
'qbpageoptions'  => '↓ ОагIува оттамаш',
'qbpageinfo'     => '↓ ОагIува тохканче',
'qbmyoptions'    => '↓ Са оттамаш',
'qbspecialpages' => '↓ ЛаьрххIа оагIувнаш',
'faq'            => '↓ Каст-каста хаттараш',
'faqpage'        => '↓ Project:Каст-каста хаттараш',

# Vector skin
'vector-action-addsection' => '↓ Дувцане тIатоха',
'vector-action-delete'     => '↓ ДIадаккха',
'vector-action-move'       => '↓ ЦIи хувца',
'vector-action-protect'    => '↓ Лораде',
'vector-action-undelete'   => '↓ Юхаяккха',
'vector-action-unprotect'  => '↓ Лорадер тIердаккха',
'vector-view-create'       => '↓ Хьадер',
'vector-view-edit'         => '↓ Хувца',
'vector-view-history'      => '↓ Искар',
'vector-view-view'         => '↓ Дешам',
'actions'                  => '↓ ХIам дер',
'namespaces'               => '↓ ЦIерий аренаш',

'errorpagetitle'    => '↓ ГIалат',
'returnto'          => '↓ $1 оагIув тIа юхавала',
'tagline'           => '↓ Кечала укхазара да {{SITENAME}}',
'help'              => '↓ Новкъoстал',
'search'            => '↓ Леха',
'searchbutton'      => '↓ Хьалаха',
'go'                => '↓ Дехьавала',
'searcharticle'     => '↓ Дехьавала',
'history'           => '↓ ОагIува искар',
'history_short'     => '↓ Искар',
'updatedmarker'     => 'Со ханача денца хувцамаш хьаний',
'info_short'        => '↓ ХIаммаIандар',
'printableversion'  => '↓ Каьхат арадоккха бIаса',
'permalink'         => '↓ Даима латта Iинк',
'print'             => '↓ Каьхат арадаккха',
'view'              => '↓ БIаргтассар',
'edit'              => '↓ Хувца',
'create'            => '↓ Хьаде',
'editthispage'      => '↓ Ер оагIув хувца',
'create-this-page'  => '↓ ОагIув хьаде',
'delete'            => '↓ ДIадаккха',
'deletethispage'    => '↓ Ер оагIув дIадаккха',
'undelete_short'    => '↓ Меттаоттаде {{PLURAL:$1|хувцам|$1 хувцамаш}}',
'viewdeleted_short' => '
↓ БIаргтасса {{PLURAL:$1|дIадаьккха хувцам тIа|$1 дIадаьккха хувцамаш тIа}}',
'protect'           => '↓ Лораде',
'protect_change'    => '↓ хувца',
'protectthispage'   => 'Лораде ер оагIув',
'unprotect'         => 'Ма лораде кхы',
'unprotectthispage' => 'Ма лораде кхы ер оагIув',
'newpage'           => 'Керда оагIув',
'talkpage'          => 'Ер оагIув йуца',
'talkpagelinktext'  => '↓ Дувцам',
'specialpage'       => '↓ ГIулакха оагIув',
'personaltools'     => '↓ Са гIирсаш',
'postcomment'       => '↓ Керда мугI',
'articlepage'       => '↓ Йоазув тIа бIаргтасса',
'talk'              => '↓ Дувцам',
'views'             => '↓ БIаргтассараш',
'toolbox'           => '↓ ГIирсаш',
'userpage'          => '↓ Дакъалаьцачунна оагIуве бIаргтасса',
'projectpage'       => '↓ Хьахьоадайтама оагIуве бIаргтасса',
'imagepage'         => '↓ Паьла оагIув тIа бIаргтасса',
'mediawikipage'     => 'У хоама оагIув хьажа',
'templatepage'      => 'Шаблона оагIув хьажа',
'viewhelppage'      => '↓ ГIо деха',
'categorypage'      => '↓ Цатега оагIуве бIаргтасса',
'viewtalkpage'      => '↓ Дувцама бIаргтасса',
'otherlanguages'    => 'Вокхо меттала',
'redirectedfrom'    => '↓ ($1 тIера хьадейта да)',
'redirectpagesub'   => '↓ дIа-хьа дайта оагIув',
'lastmodifiedat'    => '↓ Укх оагIуве тIехьара  хувцам: $2, $1.',
'viewcount'         => '↓ Укх оагIув тIа бIаргтасса хиннад {{PLURAL:$1|цкъа|$1 шозза}}.',
'protectedpage'     => '↓ Лорама оагIув',
'jumpto'            => '↓ Укхаза дехьавала:',
'jumptonavigation'  => '↓ никътахкар',
'jumptosearch'      => 'леха',
'pool-queuefull'    => '↓ Хаттара цIа хьалдизад',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '↓ Лоацам {{SITENAME}}',
'aboutpage'            => '↓ Project:Лоацам',
'copyrightpage'        => '↓ {{ns:project}}:Яздаьчунна бокъо',
'currentevents'        => '↓ ХIанзара хоамаш',
'currentevents-url'    => '↓ Project:ХIанзара хоамаш',
'disclaimers'          => '↓ Бокъонах юхавала',
'disclaimerpage'       => '↓ Project:Бокъонах юхавала',
'edithelp'             => '↓ Хувцама новкъостал',
'edithelppage'         => '↓ Help:Хувцама новкъoстал',
'helppage'             => 'Help:Хьехар',
'mainpage'             => '↓ Кертера оагIув',
'mainpage-description' => 'Кертера оагIув',
'policy-url'           => 'Project:Бокъонаш',
'portal'               => 'Гулламхой ков',
'portal-url'           => '↓ Project:Гулламхой ков',
'privacy'              => 'Паьлабокъо',
'privacypage'          => '↓ Project:Паьлабокъо',

'badaccess'        => '↓ Чувалар гIалата',
'badaccess-group0' => 'Хьо де воалара хьюна де пурам дац',
'badaccess-groups' => 'Хьо де воалара $1 группаш юкъе бол чар ма де йиша яц',

'versionrequired'     => '$1 MediaWiki верси йиза',
'versionrequiredtext' => '$1 MediaWiki верси йиза ер оагIув хьажа. [[Special:Version|version page]] хьажа.',

'ok'                      => 'ОК',
'retrievedfrom'           => '↓ "$1" ГIувам',
'youhavenewmessages'      => '↓ Оаш $1 ($2) йийцад',
'newmessageslink'         => '↓ керда хоамаш',
'newmessagesdifflink'     => '↓ тIехьара хувцамаш',
'youhavenewmessagesmulti' => '↓ Оаш керда хоамаш йийцад $1чу',
'editsection'             => '↓ хувца',
'editold'                 => '↓ хувца',
'editlink'                => '↓ хувца',
'editsectionhint'         => '↓  Цекдакъа хувца: $1',
'toc'                     => '↓ ОагIува тохканче',
'showtoc'                 => '↓ хьахокха',
'hidetoc'                 => '↓ къайладаккха',
'collapsible-collapse'    => '↓ дIадерзаде',
'collapsible-expand'      => '↓ хьадоаржаде',
'thisisdeleted'           => '↓ $1 бIаргтасса е юхаметтаоттаде?',
'viewdeleted'             => '↓ $1 бIаргтасса?',
'feedlinks'               => '↓ Цу тайпара:',
'site-rss-feed'           => '↓ $1 RSS мугI',
'site-atom-feed'          => '↓ $1 Atom мугI',
'page-rss-feed'           => '↓ "$1" RSS мугI',
'page-atom-feed'          => '↓ "$1" Atom мугI',
'red-link-title'          => '↓ $1 (укх тайпара оагIув яц)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Йоазув',
'nstab-user'      => '↓ Дакъалаьцархо',
'nstab-media'     => '↓ Паьлашой оагIув',
'nstab-special'   => '↓ ГIулакха оагIув',
'nstab-project'   => '↓ Хьахьоадайтамахь лаьца',
'nstab-image'     => 'Паьл',
'nstab-mediawiki' => '↓ Хоам',
'nstab-help'      => '↓ Новкъoстал',
'nstab-category'  => '↓ Цатег',

# Main script and global functions
'nosuchaction' => '↓ Цу тайпара болхаме дац',

# Login and logout pages
'yourname'                => '↓ Дакъалаьцархочунна цIи:',
'yourpassword'            => '↓ КъайладоагIа:',
'yourpasswordagain'       => '↓ КъайладоагIа юхаала:',
'login'                   => '↓ Чувала',
'nav-login-createaccount' => '↓ ЦIи яккха/ОагIув ела',
'userlogin'               => '↓ ЦIи яккха/ОагIув ела',
'userloginnocreate'       => '↓ Чувала',
'logout'                  => '↓ Аравала',
'userlogout'              => '↓ Аравала',
'notloggedin'             => '↓ Оаш шоай цIи хьааьннадац',
'gotaccountlink'          => '↓ Чувала',

# Edit page toolbar
'link_sample'     => '↓ Iинка кертмугI',
'link_tip'        => '↓ ЧураIинк',
'headline_sample' => '↓ Яздама кертмугI',
'headline_tip'    => '↓ ЛагIа 2-гIа кертмугI',
'nowiki_sample'   => '↓ Укхаза кийчаде дезаш доаца яздам оттаде',
'media_tip'       => '↓ ХIам-паьлечу Iинк',
'sig_tip'         => '↓ Шун кулгаяздар а, хIанзара ха а',
'hr_tip'          => '↓ Мухала мугI (могаш тайпара къеззига хайраде)',

# Edit pages
'summary'            => 'Хувцамий белгалдер',
'subject'            => '↓ Дувцане/кертмугI:',
'minoredit'          => '↓ ЗIамига хувцам',
'watchthis'          => '↓ Укх оагIува теркам бе',
'savearticle'        => 'ОагIув хьаязде',
'preview'            => '↓ Хьалхе бIаргтассар',
'showpreview'        => 'Хьалхе бIаргтaссар',
'showlivepreview'    => '↓ Сиха бIаргтассар',
'showdiff'           => 'Даь хувцамаш',
'newarticle'         => '↓ (Kерда)',
'yourtext'           => '↓ Хьа яздам',
'template-protected' => '↓  (лораме лаьца)',

# History pages
'viewpagelogs' => '↓ Укх оагIува тептараш хокха',
'cur'          => 'хIанз.',
'next'         => '↓ тIехь.',
'last'         => 'хьалх.',
'page_first'   => '↓ хьалхара',
'page_last'    => '↓ тIехьара',
'histfirst'    => '↓ къаьнараш',
'histlast'     => '↓ хаяннараш',

# Revision deletion
'rev-delundel'               => '↓ хьахокха/къайлаяккха',
'rev-showdeleted'            => '↓ хьахокха',
'revdelete-show-file-submit' => '↓ XIаа',
'revdelete-radio-set'        => '↓ XIаа',
'revdelete-radio-unset'      => '↓ A',
'revdelete-log'              => '↓ Бахьан',
'revdel-restore'             => '↓  БIасанче хувца',
'pagehist'                   => '↓ ОагIува искар',
'revdelete-uname'            => '↓ дакъалаьцархочунна цIи',

# Merge log
'revertmerge' => '↓  Декъа',

# Diffs
'editundo' => '↓ белгалдаккха',

# Search results
'searchresults'             => '↓ Тахкама гIулакххилар',
'searchresults-title'       => '↓ "$1" тахка',
'searchhelp-url'            => 'Help:Хьехар',
'search-result-size'        => '↓  $1 ({{PLURAL:$2|1 дош|$2 дешаш}})',
'search-section'            => '↓  (дакъа $1)',
'search-interwiki-caption'  => '↓ Гаргалона хьахьоадайтамаш',
'search-interwiki-more'     => '↓ (кха)',
'search-mwsuggest-enabled'  => '↓  Хьехамашца',
'search-mwsuggest-disabled' => '↓  Хьехамаш боацаш',
'powersearch'               => '↓  Доккха тахкар',
'powersearch-legend'        => '↓  Доккха тахкар',
'powersearch-ns'            => '↓  ЦIерий аренашкахь лахар',
'powersearch-field'         => 'Лахар',

# Preferences page
'mypreferences' => 'Оттамаш',
'skin-preview'  => 'Хьажа',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => '↓ Ер оагIув хувца',

# Recent changes
'recentchanges' => '↓Керда хувцамаш',
'diff'          => 'кхы.',
'hist'          => 'искар',
'hide'          => 'Къайладаккха',
'show'          => '↓ Хьахокха',

# Recent changes linked
'recentchangeslinked-page' => '↓ ОагIува цIи',

# File description page
'file-anchor-link'    => '↓ Паьл',
'filehist'            => '↓ Паьла искар',
'filehist-current'    => 'ХIанзара',
'filehist-datetime'   => 'Дентаьрахь/Ха',
'filehist-thumb'      => 'ЗIамигасуртанче',
'filehist-user'       => 'Дакъалаьцархо',
'filehist-dimensions' => 'ХIамана дустам',
'filehist-filesize'   => '↓ Паьла юстарал',
'filehist-comment'    => 'ХIамоалар',
'filehist-missing'    => '↓ Паьла йоацаш я',
'imagelinks'          => 'Паьла Iинкаш',

# File reversion
'filerevert-comment' => '↓ Бахьан:',

# File deletion
'filedelete-comment' => '↓ Бахьан:',
'filedelete-submit'  => '↓ ДIадаккха',

# MIME search
'download' => '↓ хьачуяккха',

# Unwatched pages
'unwatchedpages' => '↓ Теркамза оагIувнаш',

# Miscellaneous special pages
'nbytes'       => '↓ $1 {{PLURAL:$1|бIат|бIаташ}}',
'newpages'     => '↓ Керда оагIувнаш',
'move'         => '↓ ЦIи хувца',
'movethispage' => '↓ Укх оагIува цIи хувца',

# Book sources
'booksources-go' => '↓ Лаха',

# Special:Log
'log' => '↓ Тептараш',

# Special:AllPages
'allarticles'    => '↓ Еррига оагIувнаш',
'allpagessubmit' => '↓ Кхоачашде',

# Special:Categories
'categories' => '↓ Цатегаш',

# Special:LinkSearch
'linksearch-ok' => '↓ Лаха',

# Watchlist
'watchlist'     => '↓ Теркама дагарче',
'mywatchlist'   => 'Теркам дер',
'addedwatch'    => '↓ Теркама оагIув тIа дIатIаяккха я',
'watch'         => 'Тохкам бе',
'watchthispage' => '↓ Укх оагIува теркам бе',
'unwatch'       => '↓  Лора ма де',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Тохкам беча оагIув тIа даккха',
'unwatching' => '↓ Тохкам беча оагIув тIера дIадаккха',

# Delete
'deletepage'            => '↓ ОагIув дIаяккха',
'deletedarticle'        => '↓  "[[$1]]" дIадаьккхад',
'deletecomment'         => '↓ Бахьан:',
'deletereasonotherlist' => '↓ Кхыдола бахьан',

# Protect
'protectcomment'      => '↓ Бахьан:',
'protectexpiry'       => '↓ Кхоачалуш латта:',
'protect-default'     => '↓ Лорамза',
'protect-level-sysop' => '↓ Мазакулгалхо мара чувала бокъо яц',
'restriction-type'    => '↓ Бокъонаш:',
'restriction-level'   => '↓ Чувоала лагIа:',

# Undelete
'undeletelink' => '↓  БIаргтасса/юхаметтаоттаде',

# Namespace form on various pages
'namespace'      => '↓ ЦIерий аренаш',
'blanknamespace' => '↓ (Корта)',

# Contributions
'contributions' => '↓ Дакъалаьцархочунна къахьегам',
'mycontris'     => 'Са къахьегам',

'sp-contributions-talk'   => '↓ дувцам',
'sp-contributions-submit' => '↓ Хьалаха',

# What links here
'whatlinkshere'       => '↓ Iинкаш укхаза',
'whatlinkshere-page'  => '↓ ОагIув',
'isimage'             => '↓ Сурта Iинк',
'whatlinkshere-links' => '↓ ← Iинкаш',

# Block/unblock
'blockip'      => '↓ Дакъалаьцархочунна чIегабола',
'ipblocklist'  => '↓ ЧIегабела дакъалаьцархой',
'contribslink' => '↓  къахьегам',

# Move page
'movearticle'  => 'ОагIува цIи хувца',
'newtitle'     => '↓ Керда цIи',
'movepagebtn'  => '↓ ОагIува цIи хувца',
'pagemovedsub' => '↓  ОагIув керд цIи тилла я',
'movedto'      => '↓ керда цIи тилла я',
'movelogpage'  => '↓ Хувцама тептар',
'movereason'   => '↓ Бахьан',

# Namespace 8 related
'allmessages-filter-all'    => '↓ Дерригаш',
'allmessages-language'      => '↓ Мотт:',
'allmessages-filter-submit' => '↓ Дехьавала',

# Thumbnails
'thumbnail-more' => 'Хьадоккхаде',

# Special:Import
'import-upload-filename' => '↓ ПаьлацIи:',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Дакъалаьцархочунна оагIув',
'tooltip-pt-mytalk'              => 'Шун дувцамий оагIув',
'tooltip-pt-preferences'         => '↓  Шун оттамаш',
'tooltip-pt-mycontris'           => 'Шун хувцамаш',
'tooltip-pt-login'               => '↓ Укхаза хьай цIи аьле чувала йийша я, амма чуцаваьлача хIамма а дац',
'tooltip-pt-logout'              => '↓ Аравала',
'tooltip-ca-talk'                => '↓ ОагIува чулоацамий дувцам',
'tooltip-ca-addsection'          => '↓ Керда декъам хьаде',
'tooltip-ca-move'                => '↓ Укх оагIува цIи хувца',
'tooltip-ca-watch'               => 'Ер оагIув теркам беча каьхата тIа яккха',
'tooltip-search'                 => '↓  Цу тайпара дош лаха {{SITENAME}}',
'tooltip-search-go'              => '↓  Изза мо цIи йолаш оагIуве дехьавала',
'tooltip-search-fulltext'        => '↓  Изза мо яздам долаш оагIувнаш лаха',
'tooltip-n-mainpage'             => '↓ Кертера оагIув тIа дехьавала',
'tooltip-n-mainpage-description' => '↓ Кертера оагIув тIа дехьавала',
'tooltip-n-portal'               => '↓ Хьахьоадайтамахь лаьца, хьа де йийшадар, фа а мичча  а йоала',
'tooltip-n-recentchanges'        => '↓  ТIехьара хувцамий дагарче',
'tooltip-n-randompage'           => '↓  Бе доаца оагIув ела',
'tooltip-n-help'                 => '↓ Новкъостала моттиг',
'tooltip-t-whatlinkshere'        => '↓ Массайола оагIувий дагарче, укх оагIув тIа Iинкаш луш йола',
'tooltip-feed-rss'               => '↓ Укх оагIувна RSSчу гойтар',
'tooltip-t-specialpages'         => '↓ ГIулакха оагIувний дагарчe',
'tooltip-ca-nstab-image'         => 'Паьла оагIув',
'tooltip-save'                   => '↓ Хувцамаш кходе',

# Special:NewFiles
'noimages' => '↓ Суртaш бIаргагуш дац.',
'ilsubmit' => '↓ Лаха',

# EXIF tags
'exif-artist'       => '↓ Яздархо',
'exif-writer'       => '↓ Яздама да',
'exif-languagecode' => '↓ Мотт',
'exif-iimcategory'  => '↓ Цатег',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '↓ деррига',
'imagelistall'     => '↓ деррига',
'watchlistall2'    => '↓ деррига',
'namespacesall'    => '↓ деррига',
'monthsall'        => '↓ деррига',
'limitall'         => '↓ деррига',

# Multipage image navigation
'imgmultigo'   => '↓ Дехьавала!',
'imgmultigoto' => '↓ $1 оагIув тIа дехьавала',

# Table pager
'table_pager_limit_submit' => '↓ Кхоачашде',

# Special:FilePath
'filepath'        => '↓ Паьлачу никъ',
'filepath-page'   => '↓ Паьл:',
'filepath-submit' => '↓ Дехьавала',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => '↓ ПаьлацIи:',

# Special:SpecialPages
'specialpages' => '↓ ГIулакхий оагIувнаш',

);
