<?php
/** Bashkir (Башҡорт)
  *
  * @addtogroup Language
  */

$fallback = 'ru';


$namespaceNames = array(
	NS_MEDIA            => 'Медиа',
	NS_SPECIAL          => 'Ярҙамсы',
	NS_MAIN             => '',
	NS_TALK             => 'Фекер_алышыу',
	NS_USER             => 'Ҡатнашыусы',
	NS_USER_TALK        => 'Ҡатнашыусы_м-н_фекер_алышыу', 
	#NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_б-са_фекер_алышыу',
	NS_IMAGE            => 'Рәсем',
	NS_IMAGE_TALK       => 'Рәсем_б-са_фекер_алышыу',
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
'mainpage' => 'Баш бит',

'portal'          => 'Берләшмә',
'portal-url'      => '{{ns:project}}:Берләшмә ҡоро',
'about'           => 'Тасуирлау',
'aboutsite'       => '{{grammar:genitive|{{SITENAME}}}}-ның тасуирламаһы',
'aboutpage'       => '{{ns:project}}:Тасуирлама',
'article'         => 'Мәҡәлә',
'help'            => 'Белешмә',
'sitesupport'     => 'Ярҙам итеү',
'sitesupport-url' => '{{ns:project}}:Эскерһеҙ ярҙам',
'edithelp'        => 'Мөхәрирләү белешмәһе',
'newwindow'       => '(яңы биттә)',
'cancel'          => 'Бөтөрөргә',
'qbfind'          => 'Эҙләү',
'qbmyoptions'     => 'Көйләү',
'qbspecialpages'  => 'Махсус биттәр',
'mypage'          => 'Шәхси бит',
'mytalk'          => 'Минең менән фекер алышыу',
'navigation'      => 'Төп йүнәлештәр',

'currentevents'     => 'Ағымдағы ваҡиғалар',
'currentevents-url' => 'Ағымдағы ваҡиғалар',

'disclaimers'      => 'Яуаплылыҡтан баш тартыу',
'disclaimerpage'   => 'Project:Яуаплылыҡтан баш тартыу',
'privacy'          => 'Сер һаҡлау сәйәсәте',
'errorpagetitle'   => 'Хата',
'returnto'         => '$1 битенә ҡайтыу.',
'search'           => 'Эҙләү',
'searchbutton'     => 'Табыу',
'go'               => 'Күсеү',
'searcharticle'    => 'Күсеү',
'history'          => 'Тарих',
'history_short'    => 'Тарих',
'info_short'       => 'Мәғлүмәт',
'printableversion' => 'Ҡағыҙға баҫыу өлгөһө',
'permalink'        => 'Даими һылтау',
'edit'             => 'Үҙгәртергә',
'editthispage'     => 'Был мәҡәләне үҙгәртергә',
'delete'           => 'Юҡ  итергә',
'protect'          => 'Һаҡларға',
'talkpage'         => 'Фекер алышыу',
'specialpage'      => 'Ярҙамсы бит',
'articlepage'      => 'Мәҡәләне ҡарап сығырға',
'talk'             => 'Фекер алышыу',
'toolbox'          => 'Ярҙамсы йүнәлештәр',
'otherlanguages'   => 'Башҡа телдәрҙә',
'lastmodifiedat'   => 'Был биттең һуңғы тапҡыр үҙгәртелеү ваҡыты: $2, $1 .', # $1 date, $2 time
'copyright'        => '<p> $1 ярашлы эстәлеге менән һәр кем файҙалана ала.',
'jumpto'           => 'Унда күсергә:',
'jumptosearch'     => 'эҙләү',

'editsection' => 'үҙгәртергә',
'toc'         => 'Эстәлеге',
'showtoc'     => 'күрһәтергә',
'hidetoc'     => 'йәшерергә',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Мәҡәлә',
'nstab-user'      => 'Ҡатнашыусы',
'nstab-special'   => 'Ярҙамсы бит',
'nstab-mediawiki' => 'MediaWiki белдереүе',

# General errors
'error'           => 'Хата',
'badarticleerror' => 'Был биттә ундай ғәмәл үтәргә ярамай',
'badtitle'        => 'Ярамаған исем',

# Login and logout pages
'loginpagetitle'     => 'Танышыу йәки теркәлеү',
'yourname'           => 'Ҡатнашыусы исеме',
'yourpassword'       => 'Һеҙҙең пароль',
'yourpasswordagain'  => 'Парольде ҡабаттан яҙыу',
'remembermypassword' => 'Парольде хәтерҙә ҡалдырырға',
'yourdomainname'     => 'Һеҙҙең домен',
'login'              => 'Танышыу йәки теркәлеү',
'userlogin'          => 'Танышыу йәки теркәлеү',
'logout'             => 'Тамамлау',
'userlogout'         => 'Тамамлау',
'nologin'            => 'Һеҙ әле теркәлмәгәнме? $1.',
'nologinlink'        => 'Иҫәп яҙыуын булдырырға',
'createaccount'      => 'Яңы ҡатнашыусыны теркәү',
'gotaccount'         => 'Әгәр Һеҙ теркәлеү үткән булһағыҙ? $1.',
'gotaccountlink'     => 'Үҙегеҙ менән таныштырығыҙ',
'createaccountmail'  => 'эл. почта буйынса',
'youremail'          => 'Электрон почта *',
'yourrealname'       => 'Һеҙҙең ысын исемегеҙ (*)',
'yourlanguage'       => 'Тышҡы күренештә ҡулланылған тел:',
'yourvariant'        => 'Тел төрө',
'yournick'           => 'Һеҙҙең уйҙырма исемегеҙ/ҡушаматығыҙ (имза өсөн):',
'prefs-help-email'   => '* Электрон почта (күрһәтмәһәң дә була) башҡа ҡатнашыусылар менән туры бәйләнешкә инергә мөмкинселек бирә.',
'loginsuccesstitle'  => 'Танышыу уңышлы үтте',
'loginsuccess'       => 'Хәҙер һеҙ $1 исеме менән эшләйһегеҙ.',
'wrongpassword'      => 'Һеҙ ҡулланған пароль ҡабул ителмәй. Яңынан яҙып ҡарағыҙ.',
'mailmypassword'     => 'Яңы пароль ебәрергә',

# Edit pages
'summary'        => 'Үҙгәртеүҙең ҡыҫҡаса тасуирламаһы',
'minoredit'      => 'Әҙ генә үҙгәрештәр',
'watchthis'      => 'Был битте күҙәтеүҙәр исемлегенә индерергә',
'savearticle'    => 'Яҙҙырып ҡуйырға',
'preview'        => 'Ҡарап сығыу',
'showpreview'    => 'Ҡарап сығырға',
'showdiff'       => 'Индерелгән үҙгәрештәр',
'previewnote'    => 'Ҡарап сығыу өлгөһө, әлегә үҙгәрештәр яҙҙырылмаған!',
'editing'        => 'Мөхәрирләү  $1',
'editinguser'    => 'Мөхәрирләү  $1',
'editingsection' => 'Мөхәрирләү  $1 (секция)',
'editingcomment' => 'Мөхәрирләү $1 (комментарий)',
'yourtext'       => 'Һеҙҙең текст',
'yourdiff'       => 'Айырмалыҡтар',

# Search results
'badquery'       => 'Һорау дөрөҫ төҙөлмәгән',
'badquerytext'   => 'Һорауығыҙҙы үтәп булмай. Моғайын, Һеҙ өс хәрефтән ҡыҫҡараҡ һүҙ эҙләйһегеҙҙер, йәки һүҙегеҙҙә хата барҙыр. Һорауығыҙҙы яңынан төҙөп ҡарағыҙ әле.',
'blanknamespace' => 'Мәҡәләләр',

# Preferences page
'preferences' => 'Көйләүҙәр',

# Groups
'group-all' => '(бөтә)',

# Recent changes
'recentchanges'     => 'Һуңғы үҙгәртеүҙәр',
'recentchangestext' => '{{grammar:genitive|{{SITENAME}}}}. биттәрендә индерелгән һуңғы үҙгәртеүҙәр исемлеге',

# Image list
'imagelist_user' => 'Ҡатнашыусы',

# MIME search
'mimesearch' => 'MIME буйынса эҙләү',

# Unwatched pages
'unwatchedpages' => 'Бер кем дә күҙәтмәгән биттәр',

# Statistics
'userstatstext' => "Бөтәһе '''$1''' ҡатнашыусы теркәлгән, шуларҙан '''$2''' ($4 %) хәким бурыстарын үтәй.",

# Miscellaneous special pages
'allpages'            => 'Бөтә биттәр',
'randompage'          => 'Осраҡлы мәҡәлә',
'listusers'           => 'Ҡатнашыусылар исемлеге',
'specialpages'        => 'Махсус биттәр',
'spheading'           => 'Ярҙамсы биттәр',
'recentchangeslinked' => 'Бәйле үҙгәртеүҙәр',
'newpages-username'   => 'Ҡатнашыусы:',
'ancientpages'        => 'Иң иҫке мәҡәләләр',
'move'                => 'Яңы исем биреү',

'alphaindexline' => '$1 алып $2 тиклем',

# Special:Allpages
'allpagesfrom'      => 'Ошондай хәрефтәрҙән башланған биттәрҙе күрһәтергә:',
'allarticles'       => 'Бөтә мәҡәләләр',
'allinnamespace'    => 'Бөтә биттәр (Исемдәре «$1» арауығында)',
'allnotinnamespace' => 'Бөтә биттәр («$1» исемдәр арауығынан башҡа)',
'allpagesprev'      => 'Алдағы',
'allpagesnext'      => 'Киләһе',
'allpagessubmit'    => 'Үтәргә',

# E-mail user
'emailuser'    => 'Ҡатнашыусыға хат',
'emailfrom'    => 'Кемдән',
'emailto'      => 'Кемгә',
'emailmessage' => 'Хәбәр',

# Watchlist
'watchlist'    => 'Күҙәтеү исемлеге',
'mywatchlist'    => 'Күҙәтеү исемлеге',
'watchnologin' => 'Үҙегеҙҙе танытырға кәрәк',
'addedwatch'   => 'Күҙәтеү исемлегенә өҫтәлде',
'watch'        => 'Күҙәтергә',
'unwatch'      => 'Күҙәтмәҫкә',
'notanarticle' => 'Мәҡәлә түгел',

'enotif_newpagetext' => 'Был яңы бит.',
'changed'            => 'үҙгәртелгән',

# Delete/protect/revert
'actioncomplete' => 'Ғәмәл үтәлде',

# Namespace form on various pages
'namespace' => 'Исемдәр арауығы:',

# Contributions
'contributions' => 'Ҡатнашыусы өлөшө',
'mycontris'     => 'ҡылған эштәр',

# What links here
'whatlinkshere' => 'Бында һылтанмалар',

# Block/unblock
'blockip' => 'Ҡатнашыусыны ябыу',

# Namespace 8 related
'allmessagesname' => 'Хәбәр',

# Attribution
'siteuser'  => '{{grammar:genitive|{{SITENAME}}}} - ла ҡатнашыусы $1',
'and'       => 'һәм',
'siteusers' => '{{grammar:genitive|{{SITENAME}}}} - ла ҡатнашыусы (-лар) $1',

# Labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Ҡатнашыусы:',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'бөтә',
'imagelistall'     => 'бөтә',
'watchlistall1'    => 'бөтә',
'watchlistall2'    => 'бөтә',
'namespacesall'    => 'бөтә',

);

?>
