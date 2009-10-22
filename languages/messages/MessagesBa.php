<?php
/** Bashkir (Башҡорт)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Timming
 * @author Рустам Нурыев
 */

$fallback = 'ru';

$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ярҙамсы',
	NS_TALK             => 'Фекер_алышыу',
	NS_USER             => 'Ҡатнашыусы',
	NS_USER_TALK        => 'Ҡатнашыусы_м-н_фекер_алышыу',
	NS_PROJECT_TALK     => '$1_б-са_фекер_алышыу',
	NS_FILE             => 'Рәсем',
	NS_FILE_TALK        => 'Рәсем_б-са_фекер_алышыу',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_б-са_фекер_алышыу',
	NS_TEMPLATE         => 'Ҡалып',
	NS_TEMPLATE_TALK    => 'Ҡалып_б-са_фекер_алышыу',
	NS_HELP             => 'Белешмә',
	NS_HELP_TALK        => 'Белешмә_б-са_фекер_алышыу',
	NS_CATEGORY         => 'Категория',
	NS_CATEGORY_TALK    => 'Категория_б-са_фекер_алышыу',
);

$linkTrail = '/^((?:[a-z]|а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я|ә|ө|ү|ғ|ҡ|ң|ҙ|ҫ|һ|“|»)+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'       => 'Һылтанмалар аҫтына һыҙыу:',
'tog-highlightbroken' => 'Бәйләнешһеҙ һылтамаларҙы <a href="" class="new">ошолай</a> күрһәтергә (юҡһа былай<a href="" class="internal">?</a>).',
'tog-justify'         => 'Һөйләмдәр теҙмәһен бит киңлегенә тигеҙләргә',
'tog-hideminor'       => 'Әһәмиәте ҙур булмаған төҙәтеүҙәрҙе һуңғы үҙгәртеүҙәр исемлегендә күрһәтмәҫкә',
'tog-extendwatchlist' => 'Барлыҡ үҙгәртеүҙәрҙе үҙ эсенә алған, киңәйтелгән күҙәтеү исемлеге',
'tog-usenewrc'        => 'Һуңғы үҙгәртеүҙәрҙәрҙең сифатлыраҡ исемлеге (JavaScript)',
'tog-watchcreations'  => 'Мин төҙөгән биттәрҙе күҙәтеү исемлегенә яҙырға',

'underline-always' => 'Һәрваҡыт',

# Dates
'sunday'        => 'Йәкшәмбе',
'monday'        => 'Дүшәмбе',
'tuesday'       => 'Шишәмбе',
'wednesday'     => 'Шаршамбы',
'thursday'      => 'Кесеаҙна',
'friday'        => 'Йома',
'saturday'      => 'Шәмбе',
'sun'           => 'Йәкшәмбе',
'mon'           => 'Дүшәмбе',
'tue'           => 'Шишәмбе',
'wed'           => 'Шаршамбы',
'thu'           => 'Кесеаҙна',
'fri'           => 'Йома',
'sat'           => 'Шәмбе',
'january'       => 'Ғинуар (Һыуығай)',
'february'      => 'Февраль (Шаҡай)',
'march'         => 'Март (Буранай)',
'april'         => 'Апрель (Алағарай)',
'may_long'      => 'Май (Һабанай)',
'june'          => 'Июнь (Һөтай)',
'july'          => 'Июль (Майай)',
'august'        => 'Август (Урағай)',
'september'     => 'Сентябрь (Һарысай)',
'october'       => 'Октябрь (Ҡарасай)',
'november'      => 'Ноябрь (Ҡырпағай)',
'december'      => 'Декабрь (Аҡъюлай)',
'january-gen'   => 'Ғинуар (Һыуығай)',
'february-gen'  => 'Февраль (Шаҡай)',
'march-gen'     => 'Март (Буранай)',
'april-gen'     => 'Апрель (Алағарай)',
'may-gen'       => 'Май (Һабанай)',
'june-gen'      => 'Июнь (Һөтай)',
'july-gen'      => 'Июль (Майай)',
'august-gen'    => 'Август (Урағай)',
'september-gen' => 'Сентябрь (Һарысай)',
'october-gen'   => 'Октябрь (Ҡарасай)',
'november-gen'  => 'Ноябрь (Ҡырпағай)',
'december-gen'  => 'Декабрь (Аҡъюлай)',
'jan'           => 'Ғинуар (Һыуығай)',
'feb'           => 'Февраль (Шаҡай)',
'mar'           => 'Март (Буранай)',
'apr'           => 'Апрель (Алағарай)',
'may'           => 'Май (Һабанай)',
'jun'           => 'Июнь (Һөтай)',
'jul'           => 'Июль (Майай)',
'aug'           => 'Август (Урағай)',
'sep'           => 'Сентябрь (Һарысай)',
'oct'           => 'Октябрь (Ҡарасай)',
'nov'           => 'Ноябрь (Ҡырпағай)',
'dec'           => 'Декабрь (Аҡъюлай)',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Төркөм|Төркөмдәр}}',

'about'      => 'Тасуирлау',
'article'    => 'Мәҡәлә',
'newwindow'  => '(яңы биттә)',
'cancel'     => 'Бөтөрөргә',
'mypage'     => 'Шәхси бит',
'mytalk'     => 'Минең менән фекер алышыу',
'navigation' => 'Төп йүнәлештәр',
'and'        => '&#32;һәм',

# Cologne Blue skin
'qbfind'         => 'Эҙләү',
'qbmyoptions'    => 'Көйләү',
'qbspecialpages' => 'Махсус биттәр',

'errorpagetitle'   => 'Хата',
'returnto'         => '$1 битенә ҡайтыу.',
'tagline'          => '{{SITENAME}} проектынан',
'help'             => 'Белешмә',
'search'           => 'Эҙләү',
'searchbutton'     => 'Табыу',
'go'               => 'Күсеү',
'searcharticle'    => 'Күсеү',
'history'          => 'Тарих',
'history_short'    => 'Тарих',
'info_short'       => 'Мәғлүмәт',
'printableversion' => 'Ҡағыҙға баҫыу өлгөһө',
'permalink'        => 'Даими һылтау',
'edit'             => 'Үҙгәртеү',
'editthispage'     => 'Был мәҡәләне үҙгәртергә',
'delete'           => 'Юҡ  итергә',
'protect'          => 'Һаҡларға',
'talkpage'         => 'Фекер алышыу',
'talkpagelinktext' => 'Фекер алышыу',
'specialpage'      => 'Ярҙамсы бит',
'personaltools'    => 'Шәхси ҡоралдар',
'articlepage'      => 'Мәҡәләне ҡарап сығырға',
'talk'             => 'Фекер алышыу',
'views'            => 'Ҡарауҙар',
'toolbox'          => 'Ҡоралдар',
'otherlanguages'   => 'Башҡа телдәрҙә',
'lastmodifiedat'   => 'Был биттең һуңғы тапҡыр үҙгәртелеү ваҡыты: $2, $1 .',
'jumpto'           => 'Унда күсергә:',
'jumptonavigation' => 'төп йүнәлештәр',
'jumptosearch'     => 'эҙләү',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{grammar:genitive|{{SITENAME}}}}-ның тасуирламаһы',
'aboutpage'            => 'Project:Тасуирлама',
'copyright'            => '$1 ярашлы эстәлеге менән һәр кем файҙалана ала.',
'currentevents'        => 'Ағымдағы ваҡиғалар',
'currentevents-url'    => 'Project:Ағымдағы ваҡиғалар',
'disclaimers'          => 'Яуаплылыҡтан баш тартыу',
'disclaimerpage'       => 'Project:Яуаплылыҡтан баш тартыу',
'edithelp'             => 'Мөхәрирләү белешмәһе',
'mainpage'             => 'Баш бит',
'mainpage-description' => 'Баш бит',
'portal'               => 'Берләшмә',
'portal-url'           => 'Project:Берләшмә ҡоро',
'privacy'              => 'Сер һаҡлау сәйәсәте',
'privacypage'          => 'Project:Сер һаҡлау сәйәсәте',

'retrievedfrom'   => 'Сығанағы — «$1»',
'editsection'     => 'үҙгәртергә',
'editlink'        => 'үҙгәртергә',
'editsectionhint' => '$1 бүлеген үҙгәртеү',
'toc'             => 'Эстәлеге',
'showtoc'         => 'күрһәтергә',
'hidetoc'         => 'йәшерергә',
'site-rss-feed'   => '$1 — RSS таҫмаһы',
'site-atom-feed'  => '$1 — Atom таҫмаһы',
'red-link-title'  => '$1 (был бит юк)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Мәҡәлә',
'nstab-user'      => 'Ҡатнашыусы',
'nstab-special'   => 'Махсус бит',
'nstab-mediawiki' => 'MediaWiki белдереүе',

# General errors
'error'           => 'Хата',
'badarticleerror' => 'Был биттә ундай ғәмәл үтәргә ярамай',
'badtitle'        => 'Ярамаған исем',

# Login and logout pages
'yourname'                => 'Ҡатнашыусы исеме',
'yourpassword'            => 'Һеҙҙең пароль',
'yourpasswordagain'       => 'Парольде ҡабаттан яҙыу',
'remembermypassword'      => 'Парольде хәтерҙә ҡалдырырға',
'yourdomainname'          => 'Һеҙҙең домен',
'login'                   => 'Танышыу йәки теркәлеү',
'nav-login-createaccount' => 'Танышыу йәки теркәлеү',
'userlogin'               => 'Танышыу йәки теркәлеү',
'logout'                  => 'Тамамлау',
'userlogout'              => 'Тамамлау',
'nologin'                 => "Һеҙ әле теркәлмәгәнме? '''$1'''.",
'nologinlink'             => 'Иҫәп яҙыуын булдырырға',
'createaccount'           => 'Яңы ҡатнашыусыны теркәү',
'gotaccount'              => "Әгәр Һеҙ теркәлеү үткән булһағыҙ? '''$1'''.",
'gotaccountlink'          => 'Үҙегеҙ менән таныштырығыҙ',
'createaccountmail'       => 'эл. почта буйынса',
'loginsuccesstitle'       => 'Танышыу уңышлы үтте',
'loginsuccess'            => 'Хәҙер һеҙ $1 исеме менән эшләйһегеҙ.',
'wrongpassword'           => 'Һеҙ ҡулланған пароль ҡабул ителмәй. Яңынан яҙып ҡарағыҙ.',
'mailmypassword'          => 'Яңы пароль ебәрергә',

# Edit pages
'summary'        => 'Үҙгәртеүҙең ҡыҫҡаса тасуирламаһы:',
'minoredit'      => 'Әҙ генә үҙгәрештәр',
'watchthis'      => 'Был битте күҙәтеүҙәр исемлегенә индерергә',
'savearticle'    => 'Яҙҙырып ҡуйырға',
'preview'        => 'Ҡарап сығыу',
'showpreview'    => 'Ҡарап сығырға',
'showdiff'       => 'Индерелгән үҙгәрештәр',
'previewnote'    => "'''Ҡарап сығыу өлгөһө, әлегә үҙгәрештәр яҙҙырылмаған!'''",
'editing'        => 'Мөхәрирләү  $1',
'editingsection' => 'Мөхәрирләү  $1 (секция)',
'editingcomment' => 'Мөхәрирләү $1 (комментарий)',
'yourtext'       => 'Һеҙҙең текст',
'yourdiff'       => 'Айырмалыҡтар',

# Diffs
'lineno'   => '$1 юл:',
'editundo' => 'кире алыу',

# Search results
'searchresults'             => 'Эҙләү һөҙөмтәләре',
'searchresults-title'       => '«$1» өсөн эҙләү һөҙөмтәләре',
'searchsubtitle'            => '«[[:$1]]» өсөн эҙләү ([[Special:Prefixindex/$1|«$1» ҙән башлап барлык биттәр]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|«$1» ға һылтанған барлык биттәр]])',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3) ҡарарға',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 һүҙ|$2 һүҙҙәр}})',
'search-mwsuggest-enabled'  => 'кәңәштәр менән',
'search-mwsuggest-disabled' => 'кәңәшһеҙ',

# Preferences page
'preferences'      => 'Көйләүҙәр',
'youremail'        => 'Электрон почта *',
'yourrealname'     => 'Һеҙҙең ысын исемегеҙ (*)',
'yourlanguage'     => 'Тышҡы күренештә ҡулланылған тел:',
'yourvariant'      => 'Тел төрө',
'yournick'         => 'Һеҙҙең уйҙырма исемегеҙ/ҡушаматығыҙ (имза өсөн):',
'prefs-help-email' => '* Электрон почта (күрһәтмәһәң дә була) башҡа ҡатнашыусылар менән туры бәйләнешкә инергә мөмкинселек бирә.',

# User rights
'editinguser' => "Мөхәрирләү  '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",

# Groups
'group-sysop' => 'Хәкимдәр',
'group-all'   => '(бөтә)',

'grouppage-sysop' => '{{ns:project}}:Хәкимдәр',

# Recent changes
'recentchanges'     => 'Һуңғы үҙгәртеүҙәр',
'recentchangestext' => '{{grammar:genitive|{{SITENAME}}}}. биттәрендә индерелгән һуңғы үҙгәртеүҙәр исемлеге',
'hist'              => 'тарих',
'minoreditletter'   => 'ә',
'newpageletter'     => 'Я',
'boteditletter'     => 'б',

# Recent changes linked
'recentchangeslinked'         => 'Бәйле үҙгәртеүҙәр',
'recentchangeslinked-feed'    => 'Бәйле үҙгәртеүҙәр',
'recentchangeslinked-toolbox' => 'Бәйле үҙгәртеүҙәр',

# Upload
'upload' => 'Файл күсереү',

# Special:ListFiles
'listfiles_user' => 'Ҡатнашыусы',

# MIME search
'mimesearch' => 'MIME буйынса эҙләү',

# Unwatched pages
'unwatchedpages' => 'Бер кем дә күҙәтмәгән биттәр',

# Random page
'randompage' => 'Осраҡлы мәҡәлә',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|байт}}',
'listusers'         => 'Ҡатнашыусылар исемлеге',
'newpages-username' => 'Ҡатнашыусы:',
'ancientpages'      => 'Иң иҫке мәҡәләләр',
'move'              => 'Яңы исем биреү',

# Special:Log
'specialloguserlabel' => 'Ҡатнашыусы:',
'log'                 => 'Журналдар',

# Special:AllPages
'allpages'          => 'Бөтә биттәр',
'alphaindexline'    => '$1 алып $2 тиклем',
'allpagesfrom'      => 'Ошондай хәрефтәрҙән башланған биттәрҙе күрһәтергә:',
'allarticles'       => 'Бөтә мәҡәләләр',
'allinnamespace'    => 'Бөтә биттәр (Исемдәре «$1» арауығында)',
'allnotinnamespace' => 'Бөтә биттәр («$1» исемдәр арауығынан башҡа)',
'allpagesprev'      => 'Алдағы',
'allpagesnext'      => 'Киләһе',
'allpagessubmit'    => 'Үтәргә',

# Special:DeletedContributions
'deletedcontributions'       => 'Ҡулланыусыларҙың юйылған өлөшө',
'deletedcontributions-title' => 'Ҡулланыусыларҙың юйылған өлөшө',

# E-mail user
'emailuser'    => 'Ҡатнашыусыға хат',
'emailfrom'    => 'Кемдән',
'emailto'      => 'Кемгә:',
'emailmessage' => 'Хәбәр',

# Watchlist
'watchlist'    => 'Күҙәтеү исемлеге',
'mywatchlist'  => 'Күҙәтеү исемлеге',
'watchnologin' => 'Үҙегеҙҙе танытырға кәрәк',
'addedwatch'   => 'Күҙәтеү исемлегенә өҫтәлде',
'watch'        => 'Күҙәтергә',
'unwatch'      => 'Күҙәтмәҫкә',
'notanarticle' => 'Мәҡәлә түгел',

'enotif_newpagetext' => 'Был яңы бит.',
'changed'            => 'үҙгәртелгән',

# Delete
'actioncomplete' => 'Ғәмәл үтәлде',

# Protect
'protectcomment' => 'Сәбәп:',

# Namespace form on various pages
'namespace'      => 'Исемдәр арауығы:',
'blanknamespace' => '(Төп)',

# Contributions
'contributions' => 'Ҡатнашыусы өлөшө',
'mycontris'     => 'ҡылған эштәр',

'sp-contributions-deleted' => 'Ҡулланыусыларҙың юйылған өлөшө',

# What links here
'whatlinkshere' => 'Бында һылтанмалар',

# Block/unblock
'blockip'      => 'Ҡатнашыусыны ябыу',
'blocklink'    => 'ябып ҡуйырға',
'contribslink' => 'индергән өлөш',

# Namespace 8 related
'allmessagesname' => 'Хәбәр',

# Tooltip help for the actions
'tooltip-pt-login'               => 'Бында теркәлеү үтергә була, әмма был эш мәҗбүри түгел.',
'tooltip-ca-talk'                => 'Биттең эстәлеге тураһында фекер алышу',
'tooltip-ca-edit'                => 'Һеҙ был битте үҙгәртә алаһығыҙ. Зинһар, яҙып ҡуйыр алдынан ҡарап сығығыҙ',
'tooltip-ca-history'             => 'Биттең төҙәтеүҙәр исемлеге',
'tooltip-search'                 => 'Эҙләргә {{SITENAME}}',
'tooltip-search-go'              => 'Нәҡ ошондай исеме булған биткә күсергә',
'tooltip-search-fulltext'        => 'Ошондай эстәлекле биттәрҙе табырға',
'tooltip-n-mainpage'             => 'Баш биткә күсергә',
'tooltip-n-mainpage-description' => 'Баш биткә күсеү',
'tooltip-n-portal'               => 'Проект тураһында, һеҙ нимә эшләй алауығыҙ һәм нимә ҡайҙа булыуы тураһында',
'tooltip-n-recentchanges'        => 'Һуңғы үҙгәртеүҙәр исемлеге',
'tooltip-n-randompage'           => 'Осраҡлы битте ҡарарға',
'tooltip-n-help'                 => '«{{SITENAME}}» буйынса белешмә',
'tooltip-t-whatlinkshere'        => 'Был биткә һылталанған барлык биттәрҙең исемлеге',
'tooltip-t-recentchangeslinked'  => 'Был биттән һылталанған биттәрҙә һуңғы үҙгәртүҙәр',
'tooltip-t-upload'               => 'Рәсем йәки тауыш эстәлекле файлдарҙы күсереп яҙырға',
'tooltip-t-specialpages'         => 'Барлыҡ махсус биттәр исемлеге',
'tooltip-t-print'                => 'Был биттең ҡағыҙға баҫыу өлгөһө',
'tooltip-t-permalink'            => 'Биттең был өлгөһөнә даими һылтанма',
'tooltip-ca-nstab-main'          => 'Мәҡәләнең эстәлеге',
'tooltip-ca-nstab-special'       => 'Был махсус бит, уны үҙгәртеп булмай',

# Attribution
'siteuser'  => '{{grammar:genitive|{{SITENAME}}}} - ла ҡатнашыусы $1',
'siteusers' => '{{grammar:genitive|{{SITENAME}}}} - ла ҡатнашыусы (-лар) $1',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'бөтә',
'imagelistall'     => 'бөтә',
'watchlistall2'    => 'бөтә',
'namespacesall'    => 'бөтә',

# Special:SpecialPages
'specialpages' => 'Махсус биттәр',

);
