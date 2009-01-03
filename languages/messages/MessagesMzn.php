<?php
/** Mazanderani (مَزِروني)
 *
 * @ingroup Language
 * @file
 *
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
'qbedit'         => 'دچی ین',
'qbspecialpages' => 'شا ولگ ئون',
'mypage'         => 'مه ولگ',
'mytalk'         => 'مه گپ',

'search'           => 'چرخه تو',
'searcharticle'    => 'بور',
'history'          => 'ولگ ره کوهنه جورهکارده ئون',
'history_short'    => 'چه‌كوت / تاریخ',
'permalink'        => 'بموندنه لینک',
'edit'             => 'دچی یه‌ن',
'editthispage'     => 'ای ولگ ره دچی یه‌ن',
'talkpagelinktext' => 'گپ',
'specialpage'      => 'شا ولگ',
'talk'             => 'گپ',
'viewtalkpage'     => 'گپ ئون ره نشون هدائن',
'otherlanguages'   => 'دیگه زیوون ئون',
'lastmodifiedat'   => 'ای ولگ ره پایانی جور هکاردن ره بنه وخت ره وند بونه:
$2، $1', # $1 date, $2 time

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'mainpage' => 'گت ولگ',

'ok'                      => 'خا',
'newmessageslink'         => 'ترنه پیغوم ئون',
'youhavenewmessagesmulti' => 'شه مه وسه ترنه پیغوم بی یه موئه ای جه $1',
'editsection'             => 'دچی ین',
'editold'                 => 'دچی ین',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'    => 'کارور گپ',
'nstab-special' => 'شا',

# General errors
'viewsource'    => 'منبع نشون هدائن',
'viewsourcefor' => '$1 ره وسه',

# Login and logout pages
'welcomecreation'   => '<h2>$1، خش بیه موئی!</h2><p>شه مه حساب وا بایه! یاد نکاندنین که شه خواستنی ئون ره {{SITENAME}} ده رست هاکنین.',
'loginprompt'       => '{{SITENAME}} ره دله ئه نه ن ونه cookie ئون فعال بوئه.',
'userlogin'         => 'سیستم ره دله بوردن',
'userlogout'        => 'سیستم ره در بی ئومن',
'notloggedin'       => 'سیستم ره دله نی یه موئین',
'createaccount'     => 'ترنه حساب وا هکاردن',
'createaccountmail' => 'Email ره همرا',
'youremail'         => 'شه مه Email *',
'yourrealname'      => 'شیمه راستین ره نوم :',
'yourlanguage'      => 'زیوون:',
'nouserspecified'   => 'شما ونه اتا کارور نوم هادی.',

# Edit pages
'whitelistedittitle' => 'جور هکاردن ره وسه ونه سیستم ره دله ئه نین',
'newarticle'         => '(ترنه)',
'editing'            => 'جور هکاردن => $1',

# Search results
'powersearch' => 'سرک بنک',

# Preferences page
'prefsnologin' => 'سیستم ره ديله نی یه مویین',

# User rights
'userrights-user-editname' => 'کارور نوم ره بنویش هاکنین',

# Recent changes
'recentchanges'   => 'ترنه جور هکارده ئون',
'rclinks'         => 'نشون هدائن  $1 پایانی دچی-ینون، $2 هیسا رز ره دیله؛ $3',
'diff'            => 'هیسا',
'hist'            => 'چه‌كوت',
'show'            => 'نشون هاده',
'minoreditletter' => 'پچک',
'newpageletter'   => 'نه',

# Recent changes linked
'recentchangeslinked' => 'وند هدار دچی-ینان',

# Upload
'upload' => 'فایل بار بی یلدن',

# Special:ListFiles
'listfiles' => 'هارشی ئون ره لیست',

'disambiguations' => 'گجگجی بایری ولگ ئون',

# Miscellaneous special pages
'popularpages'      => 'خاسگار هدار ولگ ئون',
'wantedpages'       => 'ولگ ئون ری که خامبی',
'shortpages'        => 'پیس ولگ ئون',
'longpages'         => 'بیلند ولگ ئون',
'listusers'         => 'کارور ئون ره لیست',
'newpages-username' => 'کارور نوم:',
'ancientpages'      => 'كوهنه ولگ ئون',

# Special:Log
'specialloguserlabel' => 'کارور:',

# Special:AllPages
'allarticles' => 'همه ولگ ئون',

# Special:Categories
'categories' => 'دسته ئون',

# Watchlist
'watchlist'    => 'مه هارش ئون ره لیست',
'watchnologin' => 'سیستم ره دله نی ئه موئین',
'watch'        => 'هارش',

'created' => 'وا بایه',

# Contributions
'contributions' => 'کارور ره جور هکارده ئون',
'mycontris'     => 'مه جور هکاردئون',

# What links here
'whatlinkshere' => 'ایجه ره که جه لینک هدائه؟',

# Block/unblock
'ipbsubmit'    => 'ای کارور دابس باوه',
'contribslink' => 'جور هکارده ئون',

# Move page
'movepage-moved'          => "<big>'''ای «$1» ولگ، «$2» ره بورده.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movetalk'                => '«گپ» ولگ هم، اگه بونه، بوره.',
'delete_and_move_confirm' => 'اره، پاک هاکن وه ره',

# Thumbnails
'thumbnail-more' => 'گت باوه',

# Special:Import
'import-interwiki-submit' => 'بیاردن',

# Special:NewFiles
'ilsubmit' => 'سرک بنک',

# Multipage image navigation
'imgmultigo' => 'بور!',

# Special:SpecialPages
'specialpages' => 'شا ولگ ئون',

);
