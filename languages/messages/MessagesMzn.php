<?php
/** Mazanderani (مَزِروني)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ali1986
 * @author Spacebirdy
 */

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$fallback = 'fa';

$messages = array(
# Dates
'january'   => 'جانویه',
'february'  => 'فه‌وریه',
'march'     => 'مارچ',
'april'     => 'ئه‌وریل',
'may_long'  => 'مه‌ی',
'june'      => 'جوئه‌ن',
'july'      => 'جولای',
'august'    => 'ئوگوست',
'september' => 'سه‌پته‌مبر',
'october'   => 'ئوکتوبر',
'november'  => 'نووه‌مبر',
'december'  => 'ده‌سه‌مبر',
'jan'       => 'جانویه',
'feb'       => 'فه‌وریه',
'mar'       => 'مارچ',
'apr'       => 'ئه‌وریل',
'may'       => 'مه‌ی',
'jun'       => 'جون',
'jul'       => 'جولای',
'aug'       => 'ئوگوست',
'sep'       => 'سه‌پته‌مبر',
'oct'       => 'ئوکتوبر',
'nov'       => 'نووه‌مبه‌ر',
'dec'       => 'ده‌سه‌مبر',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|رج|رج‌ئون}}',
'subcategories'  => 'جیر رج‌ئون',

'newwindow'      => 'ته‌رنه‌ روجین ده‌له‌ وا بونه',
'cancel'         => 'ول هـه‌کـارده‌ن',
'qbedit'         => 'دچی ین',
'qbspecialpages' => 'شا ولگ ئون',
'mypage'         => 'مه ولگ',
'mytalk'         => 'مه گپ',
'navigation'     => 'چـأرخـه‌سـه‌ن',

'errorpagetitle'   => 'شه‌ت!',
'tagline'          => '{{SITENAME}} جه',
'help'             => 'رانه‌مایی',
'search'           => 'چَرخه تو',
'searchbutton'     => 'چَرخه‌تو',
'searcharticle'    => 'بور',
'history'          => 'ولـگ ره چـه‌کـوت',
'history_short'    => 'چه‌كوت / تاریخ',
'printableversion' => 'په‌رینت ده‌لـماج',
'permalink'        => 'بموندنه لینک',
'edit'             => 'دَچیه‌ن',
'create'           => 'بأئیته‌ن',
'editthispage'     => 'ای ولگ ره دَچیه‌ن',
'delete'           => 'وربـأئـیـتـه‌ن',
'newpage'          => 'نـه ولـگ',
'talkpage'         => 'گـب، ای ولـگ بـه‌تـیـم پـألـی',
'talkpagelinktext' => 'گپ',
'specialpage'      => 'شا ولگ',
'personaltools'    => 'مه‌شه ابزار',
'talk'             => 'گپ',
'toolbox'          => 'ابزار جا',
'viewtalkpage'     => 'گپ ئون ره نشون هدائن',
'otherlanguages'   => 'دیگه زیوون‌ئون',
'redirectedfrom'   => '(به‌مونه   $1   جه)',
'lastmodifiedat'   => 'ای ولگ ره پایانی جور هکاردن ره بنه وخت ره وند بونه:
$2، $1', # $1 date, $2 time
'jumpto'           => 'کـأپـتـه تـا:',
'jumptonavigation' => 'چـأرخـه‌سـه‌ن',
'jumptosearch'     => 'چرخه‌تو',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'   => '{{SITENAME}} ده‌له‌واره',
'aboutpage'   => 'په‌روجه:ده‌له‌واره',
'copyright'   => 'ای ولـگ ره بأنـویـشـتـه‌ئون  $1  ره جـیـر شـه‌مـه دسـت دأره‌نـه.',
'disclaimers' => 'خواهان فه‌رو نیشته‌نه‌ن',
'mainpage'    => 'گت ولگ',
'privacy'     => 'کاری رول',

'ok'                      => 'خا',
'retrievedfrom'           => '"$1" جـه بأئـیـتـه بـأیـه',
'youhavenewmessages'      => 'شه‌ما اتا $1 دارنـه‌نـی ($2).',
'newmessageslink'         => 'تـه‌رنـه پـه‌یـخـوم‌ئـون',
'newmessagesdifflink'     => 'پایانی ده‌گارده‌سه‌ن',
'youhavenewmessagesmulti' => 'شه مه وسه ترنه پیغوم بی یه موئه ای جه $1',
'editsection'             => 'دَچیه‌ن',
'editold'                 => 'دچیه‌ن',
'editlink'                => 'دأچیه‌ن',
'viewsourcelink'          => 'چه‌شـمـه بأویـنه‌ن',
'editsectionhint'         => 'جـور هـه‌کـارده‌ن تـیـکـه: $1',
'toc'                     => 'بـه‌تـیـم',
'showtoc'                 => 'نه‌شون  هـاده',
'hidetoc'                 => 'فه‌رو  بـور',
'site-rss-feed'           => '$1 ره  آراس‌اس خه‌راک',
'site-atom-feed'          => '$1 ره اتم خه‌راک',
'page-rss-feed'           => '"$1" RSS خه‌راک',
'page-atom-feed'          => '"$1" Atom خه‌راک',
'red-link-title'          => '$1 (ای ولـگ دأنـیـه)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'ولـگ',
'nstab-user'     => 'کارور گپ',
'nstab-special'  => 'شا ولـگ',
'nstab-image'    => 'فایل',
'nstab-template' => 'شابلون',

# General errors
'viewsource'    => 'چـه‌شـمـه بـأویـنـه‌ن',
'viewsourcefor' => '$1 ره وسه',

# Login and logout pages
'welcomecreation'         => '<h2>$1، خش بیه موئی!</h2><p>شه مه حساب وا بایه! یاد نکاندنین که شه خواستنی ئون ره {{SITENAME}} ده رست هاکنین.',
'yourname'                => 'کاروری‌نوم:',
'yourpassword'            => 'پـاس‌واجـه',
'remembermypassword'      => 'مـه کاروری نوم ئو پـاس‌واجه ره، ای کـامـپـیـوتـه‌ر ده‌لـه وه‌سـه، شـه یـاد بیـه‌ل',
'login'                   => 'ده‌لـه بـوری',
'nav-login-createaccount' => 'ده‌له‌بوری / اکانت بأئیته‌ن',
'loginprompt'             => '{{SITENAME}} ره دله ئه نه ن ونه cookie ئون فعال بوئه.',
'userlogin'               => 'ده‌له‌بوری / اکانت بأئیته‌ن',
'userlogout'              => 'دأیابـوری',
'notloggedin'             => 'سیستم ره دله نی یه موئین',
'nologinlink'             => 'اتا اکانت بأئیته‌ن',
'createaccount'           => 'ترنه حساب وا هکاردن',
'createaccountmail'       => 'Email ره همرا',
'youremail'               => 'شه مه Email *',
'yourrealname'            => 'شیمه راستین ره نوم :',
'yourlanguage'            => 'زیوون:',
'nouserspecified'         => 'شما ونه اتا کارور نوم هادی.',

# Edit page toolbar
'bold_sample'    => 'کأفتال ته‌کست',
'bold_tip'       => 'کأفتال ته‌کست',
'italic_sample'  => 'کج ته‌کست',
'italic_tip'     => 'کج ته‌کـست',
'link_sample'    => 'لینک سرنوم',
'link_tip'       => 'درونی لینک',
'extlink_sample' => 'http://www.example.com لینک ره نوم',
'extlink_tip'    => 'دأیـا لـیـنـک',
'math_tip'       => 'ریاضی فورمول',
'media_tip'      => 'فایل لینک',

# Edit pages
'summary'            => 'چه‌کیده:',
'minoredit'          => 'اینتا هاسه اتا په‌چوک دچیه‌ن',
'watchthis'          => 'ای ولگ ره ده‌مـبـال هـه‌کـارده‌ن',
'savearticle'        => 'جا دأکه‌ته‌ن ولگ',
'preview'            => 'پیش نه‌مایه‌ش',
'showpreview'        => 'پیش‌هاره‌شا نه‌شون هـه‌دائه‌ن',
'whitelistedittitle' => 'جور هکاردن ره وسه ونه سیستم ره دله ئه نین',
'newarticle'         => '(ترنه)',
'previewnote'        => "'''شه‌مه یاد بوئه که اینتا اتا پیش‌نه‌مایه‌ش هأسه.'''
 شه‌مه ده‌گه‌ره‌سه‌ن‌ئون جانأکه‌فته که وه‌نه، جادأکه‌فته‌ن تگمه ره بأزه‌نین!",
'editing'            => 'دچیه‌ن => $1',
'editingsection'     => 'دچیه‌ن $1 (تیکه)',

# History pages
'revisionasof'     => 'دأچـیـه‌نی کـه  $1  ده‌لـه جـا دأکـه‌تـه',
'previousrevision' => '→ پـیـشـیـن ده‌گه‌ره‌سه‌ن',
'cur'              => 'ئه‌سا',
'last'             => 'چه‌کوت',

# Diffs
'lineno'   => 'بند  $1:',
'editundo' => 'واچیه‌ن',

# Search results
'search-result-size'    => '$1 ({{PLURAL:$2|1 واجه|$2 واجه}})',
'search-suggest'        => 'شه‌ما اینتا ره نه‌خواسته‌نی؟ $1',
'search-interwiki-more' => '(ویشته‌ر)',
'powersearch'           => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',
'powersearch-legend'    => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',

# Preferences page
'mypreferences' => 'مه خواسته‌نی‌ئون',
'prefsnologin'  => 'سیستم ره ديله نی یه مویین',

# User rights
'userrights-user-editname' => 'کارور نوم ره بنویش هاکنین',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ای ولگ ره دَچیه‌ن',

# Recent changes
'recentchanges'   => 'نـه ده‌گـه‌ره‌سـه‌ئون',
'rclinks'         => 'نشون هدائن  $1 پایانی دچی-ینون، $2 هیسا رز ره دیله؛ $3',
'diff'            => 'ئه‌سا',
'hist'            => 'چه‌كوت',
'hide'            => 'فـه‌رو بـأبـه‌ردن',
'show'            => 'نه‌شون هاده',
'minoreditletter' => 'پچک',
'newpageletter'   => 'نه',
'boteditletter'   => 'ربوت',

# Recent changes linked
'recentchangeslinked' => 'واری دچیه‌ن‌ئون',

# Upload
'upload'        => 'باربیه‌شته‌ن فایل',
'uploadlogpage' => 'باربیه‌شته‌ن گوزاره‌ش',

# Special:ListFiles
'listfiles' => 'هارشی ئون ره لیست',

# File description page
'filehist'          => 'فایل چه‌کوت',
'filehist-current'  => 'ئه‌سا',
'filehist-datetime' => 'تاریخ/زمون',
'filehist-user'     => 'کارور',
'imagelinks'        => 'لینک‌ئون',

# Statistics
'statistics' => 'آمار',

'disambiguations' => 'گجگجی بایری ولگ ئون',

# Miscellaneous special pages
'nbytes'            => '$1 بایت',
'popularpages'      => 'خاسگار هدار ولگ ئون',
'wantedpages'       => 'ولگ ئون ری که خامبی',
'shortpages'        => 'پیس ولگ ئون',
'longpages'         => 'بیلند ولگ ئون',
'listusers'         => 'کارور ئون ره لیست',
'newpages'          => 'نـه به‌ساجه ولـگ‌ئون',
'newpages-username' => 'کارور نوم:',
'ancientpages'      => 'كوهنه ولگ ئون',

# Special:Log
'specialloguserlabel' => 'کارور:',

# Special:AllPages
'alphaindexline' => '$1 تا  $2',
'prevpage'       => 'پـیـشـیـن ولـگ ($1)',
'allarticles'    => 'همه ولگ ئون',
'allpagessubmit' => 'بور',

# Special:Categories
'categories' => 'دسته ئون',

# Special:LinkSearch
'linksearch' => 'دأیا لـیـنـک‌ئون',

# Special:ListGroupRights
'listgrouprights-members' => '(کارورئون ره لیست)',

# E-mail user
'emailuser' => 'ای-نـومـه ای کـارور وه‌سه',

# Watchlist
'watchlist'     => 'مـه ده‌مـبـالـه‌ئون ره لـیـسـت',
'mywatchlist'   => 'مه ده‌مـبـال‌هه‌کاردن لیست',
'watchnologin'  => 'سیستم ره دله نی ئه موئین',
'watch'         => 'ده‌مبال هاکه‌ردن',
'watchthispage' => 'ای ولـگ ره ده‌مـبـال هـه‌کـارده‌ن',
'unwatch'       => 'ده‌مـبـال نه‌کـارده‌ن',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ده‌مـبـال هـه‌کـاردن...',
'unwatching' => 'ده‌مـبـال نـه‌کـاردن...',

'created' => 'وا بایه',

# Delete
'deletepage'     => 'ولـگ وربـأئـیـتـه‌ن',
'deletedarticle' => 'وربـأئـیـتـه بأیه "[[$1]]"',
'dellogpage'     => 'وربأئیته‌نه‌ئون گوزارش',

# Rollback
'rollbacklink' => 'واچیه‌ن',

# Namespace form on various pages
'namespace'      => 'نوم‌جا:',
'blanknamespace' => '(مار)',

# Contributions
'contributions'       => 'کارور کایه‌رئون',
'contributions-title' => 'کارور کایه‌رئون $1 وه‌سه',
'mycontris'           => 'مه کایه‌رئون',
'uctop'               => '(سه‌ر)',

'sp-contributions-username' => 'IP نـه‌شـونـی یا کـاروری‌نوم',
'sp-contributions-submit'   => 'چـرخـه‌تـو',

# What links here
'whatlinkshere'       => 'ایجه ره که‌جه لینک هه‌دائه؟',
'whatlinkshere-links' => '← لـیـنـک‌ئون',

# Block/unblock
'blockip'      => 'کارور دأبه‌سته‌ن',
'ipbsubmit'    => 'ای کارور دابس باوه',
'ipblocklist'  => 'IP نـه‌شـونـی‌ئون ئو کـارورنـوم‌ئونی کـه دأبـه‌سـتـوونـه',
'blocklink'    => 'دابه‌سته‌ن کارور',
'unblocklink'  => 'وا هـه‌کـارده‌ن',
'contribslink' => 'کایه‌رئون',

# Move page
'newtitle'                => 'ته‌رنـه نـوم:',
'movepage-moved'          => "<big>'''ای «$1» ولگ،  بورده «$2» ره.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movetalk'                => '«گپ» ولگ هم، اگه بونه، بوره.',
'revertmove'              => 'واچیه‌ن',
'delete_and_move_confirm' => 'اره، پاک هاکن وه ره',

# Export
'export' => 'دأیابیاردن ولـگ‌ئون',

# Thumbnails
'thumbnail-more' => 'گت بأوه',

# Special:Import
'import-interwiki-submit' => 'بیاردن',

# Tooltip help for the actions
'tooltip-pt-userpage'      => 'مه کاروری ولگ',
'tooltip-pt-mytalk'        => 'مه گب ولگ',
'tooltip-pt-preferences'   => 'مه خواسته‌نی‌ئون',
'tooltip-pt-watchlist'     => 'لیست ولـگ‌ئونی که وه‌شون ره دچیه‌ن‌ئون وه‌سه ده‌مـبـال که‌نده‌نی',
'tooltip-pt-mycontris'     => 'مه کایه‌رئون ره لیست',
'tooltip-pt-login'         => 'شه‌ما به‌ته‌ر هاسه که سیستم ده‌له بیه‌ئی، هرچن زوری نیه',
'tooltip-pt-logout'        => 'سیستم جه دأیابـوری',
'tooltip-ca-talk'          => 'ولـگ ده‌له‌واره گب بأزوئه‌ن',
'tooltip-ca-edit'          => 'شه‌ما به‌تونده‌نی ای ولـگ ره دأچیه‌نی.
خوائه‌ش که‌مبی  پیش‌نه‌مایه‌ش  تگمه ره ته‌له‌مبار پیش بأزه‌نین.',
'tooltip-ca-viewsource'    => 'ای ولگ ره نه‌تونده‌نی دچیه‌نی.
شه‌ما به‌تونده‌نی وه‌نه چه‌شمه ره بأوینی.',
'tooltip-ca-delete'        => 'ای ولـگ ره وربـأئـیـتـه‌ن',
'tooltip-search'           => '{{SITENAME}} ره چرخه‌تو',
'tooltip-search-go'        => 'بـور اتـا ولـگـی کـه وه‌نـه نـوم هـأمـیـنـتـا بـوئـه',
'tooltip-search-fulltext'  => 'ولـگ‌ئـون ره ایـنـتـا تـه‌کـسـت وه‌سـه چـأرخ بـأزه‌ن',
'tooltip-n-mainpage'       => 'گت ولگ ره هاره‌شائه‌ن',
'tooltip-n-portal'         => 'په‌روجه ده‌له‌واره، چه‌شی به‌توده‌نی هاکه‌نی ئو که‌جه چیزئون ره بأره‌سی',
'tooltip-n-currentevents'  => 'تازه چی‌ئون ده‌له‌واره دونه‌سه‌ن',
'tooltip-n-recentchanges'  => 'ای ویکی ده‌له، ئه‌سا دچیه‌نون ره لیست',
'tooltip-n-randompage'     => 'اتا شانسی ولـگ بیارده‌ن',
'tooltip-n-help'           => 'اتا جا که...',
'tooltip-t-whatlinkshere'  => 'هأمو ولـگ‌ئونی که ایجه ره لینک هه‌دانه',
'tooltip-feed-rss'         => 'RSS خه‌راک ای ولـگ وه‌سه',
'tooltip-feed-atom'        => 'Atom خه‌راک ای ولـگ وه‌سه',
'tooltip-t-emailuser'      => 'ای کـارور ره اتا ئه‌له‌کته‌رونیکی-نـومـه راهـی هـه‌کـارده‌ن',
'tooltip-t-upload'         => 'باربیه‌شته‌ن فایل‌ئون',
'tooltip-t-specialpages'   => 'هأمو شا ولـگ‌ئون ره لیست',
'tooltip-t-print'          => 'پـه‌ریـنـت هـه‌کـارده‌نـی ولـگ ده‌گـه‌ره‌سـه‌ن',
'tooltip-ca-nstab-user'    => 'کاروری ولـگ بأویـنه‌ن',
'tooltip-ca-nstab-special' => 'اینتا اتا شـا ولـگ هأسه که شه‌ما نه‌تونده‌نی وه‌نه به‌تیم ره دأچیه‌نی',

# Browsing diffs
'previousdiff' => 'کوهنه‌ته‌ر دچیه‌ن ←',
'nextdiff'     => 'ته‌رنه دأچیه‌ن ←',

# Special:NewFiles
'ilsubmit' => 'سرک بنک',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'هأمه',

# Multipage image navigation
'imgmultigo' => 'بور!',

# Special:SpecialPages
'specialpages' => 'شـا ولـگ‌ئون',

);
