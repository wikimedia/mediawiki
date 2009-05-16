<?php
/** ‫كوردي (عەرەبی)‬ (‫كوردي (عەرەبی)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aras Noori
 * @author Arastein
 * @author Asoxor
 * @author Cyrus abdi
 */

$fallback = 'ku-latn';


$digitTransformTable = array(
	'0' => '٠', # &#x0660;
	'1' => '١', # &#x0661;
	'2' => '٢', # &#x0662;
	'3' => '٣', # &#x0663;
	'4' => '٤', # &#x0664;
	'5' => '٥', # &#x0665;
	'6' => '٦', # &#x0666;
	'7' => '٧', # &#x0667;
	'8' => '٨', # &#x0668;
	'9' => '٩', # &#x0669;
	'.' => '٫', # &#x066b; wrong table ?
	',' => '٬', # &#x066c;
);

$messages = array(
# User preference toggles
'tog-underline'               => 'هێڵ بە ژێر بەستەرەکاندا بهێنە:',
'tog-highlightbroken'         => 'شێوازدانان بگۆڕه‌',
'tog-justify'                 => 'كۆپله‌كه‌ ڕێك بكه‌',
'tog-hideminor'               => 'دەستکارییە بچوکەکان بشارەوە لە دوا گۆڕانکارییەکاندا',
'tog-extendwatchlist'         => 'لیستی ته‌ماشاكردن درێژبكه‌ره‌وه‌ تاكوو هه‌موو گۆڕانكارییه‌كان به‌رچاوت بكه‌وێ',
'tog-usenewrc'                => 'دوا گۆڕانکارییەکان پەرە پێبدە (پێویستی بە جاڤاسکریپتە)',
'tog-numberheadings'          => 'ژماره‌ی سه‌رتا خۆكارانه‌ دابنێ',
'tog-showtoolbar'             => 'شریتی ئامرازەکان نیشان بدە (JavaScript)',
'tog-editondblclick'          => 'ده‌ستكاریی په‌ڕه‌كه‌ بكه‌ به‌ دووكرته‌ لێكردنی (سكریپتی جاڤا)',
'tog-editsection'             => 'ده‌ستكاریی كردنی به‌ش چالاك بكه‌ له‌ڕێگه‌ی به‌سته‌ری [ده‌ستكاریی] یه‌وه‌',
'tog-editsectiononrightclick' => 'ده‌ستكاریی كردنی به‌ش چالاك بكه‌ به‌هۆی كرته‌ كردن له‌ ناونیشانی به‌شه‌كه‌ (سكریپتی جاڤا)',
'tog-showtoc'                 => 'ناوه‌ڕۆك نیشان بده‌ (بۆ ئه‌و په‌ڕانه‌ی كه‌ زیاتر له‌ ٣ به‌شه‌وتار یان هه‌یه‌)',
'tog-rememberpassword'        => 'چوونەژوورەوەم بەبیربهێنەوە لەسەر ئەم کۆمپیوتەرە',
'tog-editwidth'               => 'سندوقی نووسینه‌كه‌ گه‌وره‌ترین درێژی هه‌یه‌',
'tog-watchcreations'          => 'ئه‌و په‌ڕانه‌ زیاد بكه‌ كه‌ من دروستم كردوون‌ له‌ لیسته‌ی ته‌ماشاكردندا',
'tog-watchdefault'            => 'ئه‌و په‌ڕانه‌ زیاد بكه‌ كه‌ من ده‌ستكارییم كردوون له‌ لیسته‌ی ته‌ماشاكردندا‌',
'tog-watchmoves'              => 'ئه‌و په‌ڕانه‌ زیاد بكه‌ كه‌ بردومنه‌ته‌ لیستی ته‌ماشاكردنه‌وه‌',
'tog-watchdeletion'           => 'ئه‌و په‌ڕانه‌ زیاد بكه‌ كه‌ من سڕیومنه‌ته‌وه‌ له‌ لیستی ته‌ماشاكردندا',
'tog-minordefault'            => 'هه‌موو ئه‌و ورده‌كارییانه‌ی ده‌ستكاریی ده‌كرێن وه‌كوو پێوانه‌یی نیشانیان بكه‌',
'tog-previewontop'            => 'پێش ده‌ستكاریی كردن ته‌ماشایه‌كی بكه‌',
'tog-previewonfirst'          => 'له‌گه‌ڵ یه‌كه‌م ده‌ستكارییدا پێشبینین بكه‌',
'tog-nocache'                 => 'كاش كردن ناچالاك بكه‌',
'tog-enotifwatchlistpages'    => 'به‌ پۆستی ئه‌لیكترۆنی ئاگادارم بكه‌ره‌وه‌ گه‌ر ئه‌و په‌ڕه‌یه‌ی من چاوی لێده‌كه‌م گۆڕانكاریی به‌سه‌ردا هات',
'tog-enotifusertalkpages'     => 'پۆستی ئه‌لیكترۆنیم بۆ بنێره‌ گه‌ر په‌ڕه‌ی به‌كارهێنانی وتووێژ گۆڕانكاریی به‌سه‌ردا هات‌',
'tog-enotifminoredits'        => 'له‌ گۆڕانكارییه‌ ورده‌كانیش ئاگادارم بكه‌ره‌وه‌ له‌ ڕێگه‌ی پۆستی ئه‌لیكترۆنییه‌وه',
'tog-shownumberswatching'     => 'ژماره‌ی چاولێكه‌ران نیسان بده‌',
'tog-fancysig'                => 'ئیمزای خام (جگە لە بەستەری خۆکار بۆ پەڕەی بەکارھێنەر)',
'tog-externaleditor'          => 'ده‌س',
'tog-showjumplinks'           => 'ڕێگه‌پێدانی بازدان بۆ به‌سته‌ره‌كان چالاك بكه‌',
'tog-watchlisthideown'        => 'دەستکارییەکانم بشارەوە لە لیستی چاودێری',
'tog-watchlisthidebots'       => 'دەستکارییەکانی بۆت بشارەوە لە لیستی چاودێری',
'tog-watchlisthideminor'      => 'ورده‌ ده‌ستكارییه‌كان له‌ لیسته‌ی ته‌ماشاكردندا بشاره‌وه‌',
'tog-watchlisthideliu'        => 'دەستکارییەکانی ئەو بەکارهێنەرانەی لە ژوورەوەن بشارەوە لە لیستی چاودێری',
'tog-watchlisthideanons'      => 'دەستکارییەکانی بەکارهێنەرانی نەناسراو بشارەوە لە لیستی چاودێری',
'tog-nolangconversion'        => 'وتووێژی هه‌مه‌چه‌شن ناچالاك بكه‌',
'tog-ccmeonemails'            => 'له‌به‌رگیراوه‌م بۆ بنێره‌ له‌و پۆستی ئه‌لیكترۆنییانه‌ی كه‌ بۆ به‌كارهێنه‌رانی دیكه‌ ناردوومه‌',
'tog-diffonly'                => 'په‌ڕه‌یه‌ك ئه‌م جیاوازییانه‌ی خواره‌وه‌ی له‌خۆ گرتبێت نیشانی مه‌ده‌‌',
'tog-showhiddencats'          => 'هاوپۆلە شاردراوکان پیشان بدە',

'underline-always'  => 'هه‌میشه‌',
'underline-never'   => 'هیچ كات',
'underline-default' => 'نمایشكه‌ری پێوانه‌یی',

# Dates
'sunday'        => 'یه‌كشه‌ممه‌',
'monday'        => 'دووشه‌ممه‌',
'tuesday'       => 'سێشه‌ممه‌',
'wednesday'     => 'چوارشه‌ممه‌',
'thursday'      => 'پێنجشه‌ممه‌',
'friday'        => 'هه‌ینی',
'saturday'      => 'شه‌ممه‌',
'sun'           => 'یەکشەممە',
'mon'           => 'دووشەممە',
'tue'           => 'سێشەممە',
'wed'           => 'چوارشەممە',
'thu'           => 'پێنجشەممە',
'fri'           => 'ھەینی',
'sat'           => 'شه‌ممه‌',
'january'       => 'کانوونی دووەم',
'february'      => 'شوبات',
'march'         => 'ئازار',
'april'         => 'ئاپریل',
'may_long'      => 'ئایار',
'june'          => 'حوزەیران',
'july'          => 'تەمموز',
'august'        => 'ئاب',
'september'     => 'ئەیلوول',
'october'       => 'تشرینی یەکەم',
'november'      => 'تشرینی دووەم',
'december'      => 'کانونی یەکەم',
'january-gen'   => 'كانونی دووه‌م',
'february-gen'  => 'شوبات',
'march-gen'     => 'مارت',
'april-gen'     => 'نیسان',
'may-gen'       => 'مایس',
'june-gen'      => 'حوزه‌یران',
'july-gen'      => 'ته‌مموز',
'august-gen'    => 'ئاب',
'september-gen' => 'ئه‌لیلول',
'october-gen'   => 'تشرینی یه‌كه‌م',
'november-gen'  => 'تشرینی دووه‌م',
'december-gen'  => 'كانونی یه‌كه‌م',
'jan'           => 'كا١',
'feb'           => 'شوب',
'mar'           => 'مارت',
'apr'           => 'ئب',
'may'           => 'مای',
'jun'           => 'حزن',
'jul'           => 'ته‌م',
'aug'           => 'ئاب',
'sep'           => 'ئه‌ی',
'oct'           => 'ت١',
'nov'           => 'ت٢',
'dec'           => 'كا١',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|ھاوپۆل|ھاوپۆلەکان}}',
'category_header'          => 'پەڕە ھاوپۆلەکانی "$1"',
'subcategories'            => 'به‌شه‌هاوپۆله‌كان',
'category-media-header'    => 'میدیا له‌ هاوپۆلی "$1" دا',
'category-empty'           => "''ئه‌م هاوپۆله‌ هه‌نووكه‌ هیچ له‌خۆ ناگرێت - به‌تاڵه‌''",
'hidden-category-category' => 'هاوپۆلە شاردراوەکان', # Name of the category where hidden categories will be listed

'mainpagetext'      => "<big>'''ویكیمیدیا به‌سه‌ركه‌وتووی دامه‌زرا.'''</big>",
'mainpagedocfooter' => 'بكه‌ [http://meta.wikimedia.org/wiki/Help:ناوه‌ڕۆكی چۆنێتی به‌كارهێنان] بۆ وه‌ده‌ست هێنانی زانیاریی له‌سه‌ر چۆنێتی كارگێڕی نه‌رمه‌كاڵای ویكی، سه‌ردانی.

== ده‌ست به‌ كاركردن ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings لیسته‌ی هه‌ڵبژاردنه‌كان و ڕێكخستنه‌كان]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce لیستی وه‌شانه‌كانی ویكیمیدیا]',

'about'          => 'سه‌باره‌ت',
'article'        => 'بابه‌ت',
'newwindow'      => '(لە پەڕەیەکی نوێ دەکرێتەوە)',
'cancel'         => 'ھەڵوەشاندنەوە',
'qbfind'         => 'دۆزه‌ر',
'qbbrowse'       => 'بگه‌ڕێ',
'qbedit'         => 'دەستكاری',
'qbpageoptions'  => 'ئه‌م په‌ڕه‌یه‌',
'qbpageinfo'     => 'زانیاریی په‌ڕه‌',
'qbmyoptions'    => 'په‌ڕه‌كانی من',
'qbspecialpages' => 'په‌ڕه‌تایبه‌ته‌كان',
'moredotdotdot'  => 'زیاتر',
'mypage'         => 'په‌ڕه‌ی من',
'mytalk'         => 'په‌ڕه‌ی گفتوگۆی من',
'anontalk'       => 'گفتوگۆ بۆ ئه‌م ئای‌پی‌ یه‌',
'navigation'     => 'ڕێدۆزی',
'and'            => '&#32;و',

# Metadata in edit box
'metadata_help' => 'دراوه‌ی مێتا:',

'errorpagetitle'    => 'هه‌ڵه‌',
'returnto'          => 'بگه‌ڕێوه‌ بۆ $1.',
'tagline'           => 'له‌ {{SITENAME}}',
'help'              => 'ڕێنمایی',
'search'            => 'گەڕان',
'searchbutton'      => 'بگەڕێ',
'go'                => 'ده‌ی',
'searcharticle'     => 'بڕۆ',
'history'           => 'مێژووی په‌ڕه‌',
'history_short'     => 'مێژووی نووسین',
'updatedmarker'     => 'گۆڕانكارییه‌كان پاش دوا سه‌ردانی من',
'info_short'        => 'زانیاری',
'printableversion'  => 'وەشانی ئامادەی چاپ',
'permalink'         => 'بەسته‌ری ھەمیشەیی',
'print'             => 'چاپ',
'edit'              => 'دەستكاری',
'create'            => 'دروستکردن',
'editthispage'      => 'ده‌ستكاری ئه‌م په‌ڕه‌یه‌ بكه‌',
'create-this-page'  => 'ئەم پەڕە دروست بکە',
'delete'            => 'سڕینه‌وه‌',
'deletethispage'    => 'سڕینه‌وه‌ی ئه‌م په‌ڕه‌یه‌',
'undelete_short'    => 'به‌جێ بهێنه‌ {{PLURAL:$1|سڕاوه‌|$1 سڕاوه‌كان}}هێنانه‌وه‌ی',
'protect'           => 'پاراستن',
'protect_change'    => 'گۆڕین',
'protectthispage'   => 'ئه‌م په‌ڕه‌یه‌ بپارێزه‌',
'unprotect'         => 'مه‌پارێزه‌',
'unprotectthispage' => 'ئه‌م په‌ڕه‌یه‌ مه‌پارێزه‌',
'newpage'           => 'په‌ڕه‌یه‌كی نوێ',
'talkpage'          => 'گفتوگۆ له‌سه‌ر ئه‌م په‌ڕه‌یه بكه‌',
'talkpagelinktext'  => 'وتە',
'specialpage'       => 'په‌ڕه‌ی تایبه‌ت',
'personaltools'     => 'ئامڕازە تاکەکەسییەکان',
'postcomment'       => 'بەشی نوێ',
'articlepage'       => 'ناوه‌ڕۆكی بابه‌ت ببینه‌',
'talk'              => 'گفتوگۆ',
'views'             => 'بینینەکان',
'toolbox'           => 'ئامرازدان',
'userpage'          => 'په‌ڕه‌ی به‌كارهێنه‌ر نیشانبده‌',
'projectpage'       => 'په‌ڕه‌ی پرۆژه‌ نیشانبده‌',
'imagepage'         => 'په‌ڕه‌ی وێنه‌ نیشانبده‌',
'mediawikipage'     => 'په‌ڕه‌ی په‌یام نیشانبده‌',
'templatepage'      => 'په‌ڕه‌ی قاڵب نیشانبده‌',
'viewhelppage'      => 'په‌ڕه‌ی یارمه‌تی نیشانبده‌',
'categorypage'      => 'په‌ڕه‌ی هاوپۆل نیشانبده‌',
'viewtalkpage'      => 'په‌ڕه‌ی گفتوگۆ ببینه‌',
'otherlanguages'    => 'به‌ زمانه‌كانی دیكه‌',
'redirectedfrom'    => '(ڕه‌وانه‌كراوه‌ له‌ $1)',
'redirectpagesub'   => 'په‌ڕه‌ ڕه‌وانه‌بكه‌',
'lastmodifiedat'    => 'ئه‌م په‌ڕه‌یه‌ دواجار نوێكراوه‌ته‌وه‌ له‌ $2, $1', # $1 date, $2 time
'viewcount'         => 'ئه‌م په‌ڕه‌یه‌ ده‌ستكاریی كراوه‌ {{PLURAL:$1|یه‌كجار|$1 جار}}',
'protectedpage'     => 'په‌ڕه‌یه‌كی پارێزراو',
'jumpto'            => 'باز بدە بۆ:',
'jumptonavigation'  => 'ڕێدۆزی',
'jumptosearch'      => 'گه‌ڕان',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'دەربارەی {{SITENAME}}',
'aboutpage'            => 'Project:دەربارە',
'copyright'            => 'ناوەڕۆک ئامادەیە لە ژێر $1.',
'copyrightpage'        => '{{ns:project}}:مافەکانی لەبەرگرتنەوە',
'currentevents'        => 'ڕووداوە هەنووکەییەکان',
'currentevents-url'    => 'Project:ڕووداوە بەردەوامەکان',
'disclaimers'          => 'بەرپرس‌نەبوونییەکان',
'edithelp'             => 'ڕێنمایی بۆ دەستکاریکردن',
'edithelppage'         => 'Help:دەستکاریکردن',
'faq'                  => 'پرسیار و وەڵام (FAQ)',
'faqpage'              => 'Project:پرسیار و وەڵام',
'helppage'             => 'Help:رێنمایییەکان',
'mainpage'             => 'دەستپێک',
'mainpage-description' => 'ده‌ستپێک',
'portal'               => 'دەروازەی بەکارھێنەران',
'portal-url'           => 'Project: دەروازەی بەکارھێنەران',
'privacy'              => 'سیاسەتی پاراستنی داتاکان',
'privacypage'          => 'Project:پاراستنی زانیارییەکان',

'badaccess' => 'ھەڵە لە بە دەست ھێنان',

'versionrequired'     => 'پێویستیت به‌ وه‌شانی $1 ـی‌ ویكیمیدیایه‌',
'versionrequiredtext' => 'پێویستیت به‌ وه‌شانی $1 ـێ ویكیمیدیا هه‌یه‌ بۆ به‌كاربردنی ئه‌م په‌ڕه‌یه‌
. ته‌ماشای [[Special:Version|وه‌شانی]] بكه‌.',

'ok'                      => 'باشه‌',
'retrievedfrom'           => 'له‌ لایه‌ن "$1" گه‌ڕاوه‌ته‌وه‌.',
'youhavenewmessages'      => '$1ت ھەیە ($2).',
'newmessageslink'         => 'په‌یامێكی نوێ',
'newmessagesdifflink'     => 'دوا گۆڕانكارییه‌كان',
'youhavenewmessagesmulti' => 'په‌یامێكی نوێت هه‌یه‌ له‌ $1.',
'editsection'             => 'ده‌ستكاری',
'editold'                 => 'دەستکاری',
'viewsourceold'           => 'بینینی سەرچاوە',
'editlink'                => 'دەستکاری',
'viewsourcelink'          => 'بینینی سەرچاوە',
'editsectionhint'         => 'دەستكاری به‌شی: $1',
'toc'                     => 'ناوەڕۆک',
'showtoc'                 => 'نیشاندان',
'hidetoc'                 => 'شاردنەوە',
'thisisdeleted'           => '؟$1 نیشانی بده‌ یا بیگه‌ڕێنه‌ره‌وه‌',
'viewdeleted'             => '$1 نیشان بده‌؟',
'restorelink'             => '{{PLURAL:$1|گۆڕانکاریی سڕاو|$1 یەک گۆڕانکاریی سڕاو}}',
'site-rss-feed'           => 'RSS FEED ـی $1',
'site-atom-feed'          => 'Atom Feed ـی $1',
'page-rss-feed'           => 'RSS Feed ـی "$1"',
'page-atom-feed'          => 'Atom Feed ـی "$1"',
'red-link-title'          => '$1 (پەڕە بوونی نییە)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'پەڕە',
'nstab-user'      => 'پەڕەی بەکارھێنەر',
'nstab-media'     => 'میدیا',
'nstab-special'   => 'په‌ڕه‌ی تایبه‌ت',
'nstab-project'   => 'په‌ڕه‌ی پرۆژه‌',
'nstab-image'     => 'وێنه‌',
'nstab-mediawiki' => 'په‌یام',
'nstab-template'  => 'قاڵب',
'nstab-help'      => 'یارمه‌تی',
'nstab-category'  => 'هاوپۆل',

# Main script and global functions
'nosuchaction'      => 'كرداری به‌م شێوه‌یه‌ نییه‌',
'nosuchactiontext'  => 'ئەو چالاکییەی لە لایەن بەستەرەوە دیاریکراوە ناتەواوە.
لەوانەیە بە هەڵە بەستەرەکەت نووسیبێت، یان بەستەرێکی هەڵەی بە دواوە بێت.
لەوانەیە ئەمە نیشانەی هەڵەیەک بێت لەو نەرمەکاڵایەی کە بەکاردێت لە لایەن {{SITENAME}}.',
'nosuchspecialpage' => 'په‌ڕه‌ی تایبه‌تی له‌و شێوه‌یه‌ نییه‌',
'nospecialpagetext' => "<big>''په‌ڕه‌یكی تایبه‌ت ده‌خوازیت كه‌ بوونی نییه‌'''</big>

لیستی په‌ڕه‌ تایبه‌تییه‌كان له‌ [[Special:SpecialPages|لیسته‌ی په‌ڕه‌ تایبه‌ته‌كان]] ده‌توانرێت ببینرێت.",

# General errors
'error'                => 'هه‌ڵه‌',
'databaseerror'        => 'هه‌ڵه‌ له‌ بنكه‌دراوه‌دا هه‌یه‌',
'readonly'             => 'بنکەدراوە داخراوە',
'missing-article'      => 'دانەگە (دەیتابەیس) نەیتوانی دەقی لاپەڕەیەک بدۆزێتەوە کە دەبوا بیدۆزایەتوە، بەناوی "$1" $2 .

This is usually caused by following an outdated diff or history link to a page that has been deleted.

ئەگەر وا نەبێت، ئەوا ڕەنگە گرفتێکت لەم نەرمامێرە دا ھەبێت، کە تۆ پێت زانیوە..
تکایە ئەم بە یەکێک لە ئەندامانی [[Special:ListUsers/sysop|administrator]] ڕاپۆرت بدە، و ناونیشانی URLـەکەی پێ بدە.',
'missingarticle-rev'   => '(پیاچوونەوە#: $1)',
'readonly_lag'         => 'بنكه‌دراوه‌كه‌ به‌شێوه‌ی خۆكار به‌ندكراوه‌، له‌كاتێكدا بنكه‌دراوه‌ی ڕاژه‌كاره‌كه‌ ڕۆڵی له‌خۆگرتن ده‌گێڕێت',
'internalerror'        => 'هه‌ڵه‌یه‌كی ناوخۆیی ڕویدا',
'internalerror_info'   => 'هه‌ڵه‌ی ناوخۆیی: $1',
'filecopyerror'        => 'په‌ڕگه‌ی „$1“ ڕوونوس نابێت بۆ „$2“ .',
'filerenameerror'      => 'ناوی په‌ڕگه‌ی "$1" نه‌گۆڕدرا بۆ "$2".',
'filedeleteerror'      => 'بسڕدرێته‌وه‌"$1" نه‌توانرا په‌ڕگه‌ی',
'directorycreateerror' => 'نەتوانرا بوخچەی "$1"دروست بکرێت.',
'filenotfound'         => 'په‌ڕگه‌ی "$1" نه‌دۆزرایه‌وه‌',
'cannotdelete'         => 'نەتوانرا پەڕە یان پەڕگەی دیاریکراو بسڕدرێتەوە.
لەوانەیە پێشتر لە لایەن کەسێکی ترەوە سڕدرابێتەوە.',
'badtitle'             => 'ناونیشانێكی بێ كه‌ڵك',
'viewsource'           => 'سه‌رچاوه‌ ببینه‌',
'viewsourcefor'        => 'بۆ $1',

# Login and logout pages
'logouttitle'             => 'دەرچوونی بەکارهێنەر',
'welcomecreation'         => '== خۆش هاتیت, $1! ==

هه‌ژماری تایبه‌تی تۆ سه‌ركه‌وتووانه‌ دروست كرا، له‌بیرت نه‌چێت گۆڕانكاری له {{SITENAME}} تایبه‌ت به‌خۆت دا بكه‌.',
'loginpagetitle'          => 'ناوی چوونه‌ ژووره‌وه‌',
'yourname'                => 'ناوی به‌كارهێنه‌وه‌',
'yourpassword'            => 'تێپه‌ڕه‌وشه‌',
'yourpasswordagain'       => 'تێپه‌ڕه‌وشه‌ دووباره‌',
'remembermypassword'      => 'زانیاریی چوونه‌ ژووره‌وه‌م له‌سه‌ر ئه‌م كۆمپیوته‌ره‌ پاشه‌كه‌وت بكه‌',
'yourdomainname'          => 'ناوی دۆمه‌ینی خۆت',
'login'                   => 'تێکەوە (login)',
'nav-login-createaccount' => 'چوونەژوورەوە / دروستکردنی هەژمار',
'userlogin'               => 'دروست کردنی ھەژمار/چوونە ژورەوە',
'logout'                  => 'ده‌رچوون',
'userlogout'              => 'دەرچوون',
'notloggedin'             => 'له‌ ژووره‌وه‌ نیت',
'nologin'                 => 'ھەژمارت نییە؟  $1.',
'nologinlink'             => 'ببه‌ به‌ ئه‌ندام',
'createaccount'           => 'ھەژمار دروست بکە',
'gotaccount'              => 'خاوه‌نی هه‌ژماری خۆتی؟ $1.',
'gotaccountlink'          => 'چوونه‌ ژووره‌وه‌',
'createaccountmail'       => 'به‌ پۆستی ئه‌لیكترۆنی',
'badretype'               => 'وشه‌ نهێنییه‌كان له‌یه‌ك ناچن',
'userexists'              => 'ئەو ناوەی تۆ داوتە پێشتر کەسێکی دیکە بەکاری بردووە.
ناوێکی دیکە ھەڵبژێرە.',
'youremail'               => 'E-mail:',
'username'                => 'ناوی به‌كارهێنه‌ر:',
'uid'                     => 'ژماره‌ی خۆت ID:',
'prefs-memberingroups'    => 'ئەندامی {{PLURAL:$1|گرووپی|گرووپەکانی}}:',
'yourrealname'            => 'ناوی ڕاستی:',
'yourlanguage'            => 'زمان',
'yourvariant'             => 'زاراوە:',
'yournick'                => 'نازناو',
'badsig'                  => 'ئیمزاكه‌ هه‌ڵه‌یه‌، ته‌ماشای كۆدی HTML بكه‌‌',
'yourgender'              => 'جنس:',
'gender-unknown'          => 'ئاشکرا نەکراو',
'gender-male'             => 'پیاو',
'gender-female'           => 'ژن',
'email'                   => 'E-mail',
'prefs-help-realname'     => 'ناوی ڕاستی دڵخوازە.
ئەگەر پێت خۆش بێت بیدەی، زۆرتر ڕاتدەکێشێت بۆ کارەکانت.',
'loginerror'              => 'ھەڵە لە چوونە ژوورەوەدا',
'prefs-help-email'        => 'ئەدرەسی e-mail دڵخوازە.
‏بەڵام ئەگەر تێپەڕوشەکەت لە بیر چوو، لە ڕێگەی e-mailەوە تێپەڕوشەیەکی نوێت بۆ دەنێردرێتەوە. ھەروەھا بە بەکارھێنەرانی دیکەش لە رێگەی e-mailەوە دەتوانن پەیوەندیت لەگەڵ گرن ئەگەر تۆ حەز بکەیت.',
'loginsuccesstitle'       => 'سرەکەوتی بۆ چوونە ژوورەوە!',
'wrongpassword'           => 'تێپەڕوشەی ھەڵە. 
تکایە دووبارە تێبکۆشە.',
'wrongpasswordempty'      => 'تێپەڕەوشەی لێدراو بەتاڵبوو.
تکایە هەوڵ بدەوە.',
'mailmypassword'          => 'تێپەڕوشەیەکی نوێ بنێرە بۆ E-mailەکەم',
'passwordremindertitle'   => 'تێپەڕوشەیەکی نوێی کاتی بۆ  {{SITENAME}}',
'noemail'                 => 'ھیچ ئەدرەسێکی e-mail تۆمار نەکراوە بۆ بەکارھێنەر  "$1" .',
'mailerror'               => 'هەڵە ڕوویدا لە ناردنی ئیمەیل: $1',
'emailauthenticated'      => 'ئیمەیلەکەت بە ڕاست ناسرا لە $3ی $2 دا',
'emailnotauthenticated'   => 'ئیمەیلەکەت ھێشتا نەناسراوە.
ھیچ ئیمەیلێک بۆ ئەم بابەتانەی خوارەوە نانێردرێت.',
'emailconfirmlink'        => 'پۆستی ئه‌لیكترۆنی خۆت بنووسه‌',
'accountcreated'          => 'هه‌ژماره‌كه‌ سه‌ركه‌وتووانه‌ دروست كرا',
'loginlanguagelabel'      => 'زمان: $1',

# Password reset dialog
'resetpass'                 => 'گۆڕینی تێپەڕوشە',
'resetpass_text'            => '<!-- تێپه‌ڕه‌وشه‌ی هه‌ژماره‌كه‌ سفر بكه‌ره‌وه‌ -->',
'resetpass_header'          => 'تێپەڕوشەی ھەژمار بەتاڵ بکە',
'oldpassword'               => 'تێپەڕوشەی پێشو:',
'newpassword'               => 'تێپەڕوشەی نوێ:',
'retypenew'                 => 'تێپەڕوشەی نوێ دوبارە بنووسەوە:',
'resetpass_submit'          => 'تێپەڕوشە رێکخە و بچۆ ژوورەوە',
'resetpass_success'         => 'تێپەروشەکەت بە سەرکەوتوویی گۆڕدرا. ئێستا چوونە ژوورەوەت...',
'resetpass_bad_temporary'   => 'تێپەڕوشەی کاتی ھەڵەیە.
وا دیارە تێپەڕوشەکەت بە سەرکەوتوویی گۆڕدراوە یان داوای تێپەڕوشەیەکی نوێت کردووە.',
'resetpass_forbidden'       => 'تێپەڕوشەکە ناگۆڕدرێت',
'resetpass-no-info'         => 'بۆ گەیشتنی راستەوخۆ بەم پەڕە ئەشێ بچیتە ژوورەوە.',
'resetpass-submit-loggedin' => 'گۆڕینی تێپەڕوشە',
'resetpass-wrong-oldpass'   => 'تێپەڕوشەی ھەنووکەیی یان تێپەڕوشەی کاتی ھەڵەیە.
وا دیارە تێپەڕوشەکەت بە سەرکەوتوویی گۆڕدراوە یان داوای تێپەڕوشەیەکی نوێت کردووە.',
'resetpass-temp-password'   => 'تێپەڕوشەی کاتی:',

# Edit page toolbar
'bold_sample'     => 'ده‌قی ئه‌ستوور',
'bold_tip'        => 'ده‌قی ئه‌ستوور',
'italic_sample'   => 'دەقی لار',
'italic_tip'      => 'دەقی لار',
'link_sample'     => 'نێوی بەستەر',
'link_tip'        => 'به‌سته‌رێكی ناوخۆیی',
'extlink_sample'  => 'http://www.example.com سەردێڕی بەستەر',
'extlink_tip'     => 'به‌سته‌ری ده‌ره‌كی ( ده‌ست پێ ده‌كاتhttp:// سه‌ره‌تاكه‌ی به‌ )',
'headline_sample' => 'دەقی سەردێڕ',
'headline_tip'    => 'سەردێڕی ئاست ۲',
'math_sample'     => 'لەگرە فۆرموول بخەسەر',
'math_tip'        => ' فۆرموولی بیرکاریی (LaTeX)',
'nowiki_sample'   => 'لەگەرە دەقی نەڕازراو تێ‌بخە',
'nowiki_tip'      => 'لەبەرچاو نەگرتنی دارشتنەکانی ویکی',
'image_sample'    => 'نموونە.jpg',
'image_tip'       => 'وێنەی نێو دەق',
'media_sample'    => 'نموونە.ogg',
'media_tip'       => 'لینکی پەڕگە',
'sig_tip'         => 'ئیمزاکەت بە مۆری ڕێکەوتەوە',
'hr_tip'          => 'هێڵی ئاسۆیی (ده‌گمه‌ن به‌كاری بهێنه‌)',

# Edit pages
'summary'                          => 'پوختە:',
'subject'                          => 'بابه‌ت / سه‌روتار:',
'minoredit'                        => 'ئەم گۆڕانکاری‌یە بچووکە',
'watchthis'                        => 'چاودێڕی ئه‌م په‌ڕه‌یه‌ بكه‌',
'savearticle'                      => 'پاشەکەوتکردنی پەرە',
'preview'                          => 'پێشبینین',
'showpreview'                      => 'پێشبینینی پەڕە',
'showlivepreview'                  => 'پێشبینینی ڕاسته‌وخۆ',
'showdiff'                         => 'گۆڕانكارییه‌كان نیشانبده‌',
'anoneditwarning'                  => "'''وشیار بە:''' نەچوویتەتە ژوورەوە.
ئەدرەسی ئەکەت لە مێژووی ئەم پەڕە دا تۆمار دەکرێ.",
'missingsummary'                   => "'''وە بیر خستنەوە:''' پوختەیەکت نەنووسیوە بۆ چۆنیەتی گۆڕانکارییەکەت.
ئەگەر جارێکی تر پاشکەوت کردن لێبدەی، بێ پوختە تۆمار دەکرێ.",
'missingcommenttext'               => 'تکایە لە خوارەوە شرۆڤەیەک بنووسە.',
'summary-preview'                  => 'پێشبینینی کورتە:',
'newarticle'                       => '(نوێ)',
'newarticletext'                   => "بە دوای بەستەری پەڕەیەک کەوتووی کە ھێشتا دروست نەکراوە. <br /> بۆ دروست کردنی پەڕەکە، لە چوارچێوەکەی خوارەوە دەست کە بە تایپ کردن. (بۆ زانیاری زورتر[[یارمەتی|{{MediaWiki:Helppage}}]] ببینە). <br />  ئەگەر بە ھەڵەوە ھاتویتە ئەگرە، لە سەر دوگمەی '''back'''ی وێبگەڕەکەت کلیک کە.",
'previewnote'                      => "'''لە بیرت بێت کە ئەمە تەنها پێشبینینە.
گۆڕانکارییەکانت تا ئێستا پاشەکەوت نەکراون!'''",
'editing'                          => 'دەستکاریکردنی $1',
'editingsection'                   => 'گۆڕاندنی: $1 (بەش)',
'editingcomment'                   => 'گۆڕاندنی $1 (بەشی  نوێ)',
'copyrightwarning'                 => "تکایە ئاگادار بن کە ھەموو بەشدارییەک بۆ  {{SITENAME}} وا فەرز ئەکرێت کە لە ژێر «$2» بڵاو دەبێتەوە (بۆ ئاگاداری زۆرتر $1 سەیر کە). ئەگەر ناتەوێ نوسراوەکەت بێ‌ڕەحمانە دەستکاری بکرێت و  بە دڵخواز دیسان بڵاو ببێتەوە، لەگرە پێشکەشی مەکە. ھەروەھا بەڵین ئەدەی کە خۆت ئەمەت نووسیوە، یان لە سەرچاوەیەکی بە دەسەڵاتی گشتی ''(public domain)'' یان سەرچاوەیەکی ھاوتا لەبەرت‌گرتوەتەو.
'''«بەرھەمێک کە مافی لەبەرگرتنەوەی پارێزراوە، بێ ئیجازە  بڵاو مەکەرەوە.»'''",
'templatesused'                    => 'ئەو قاڵبانە کە لەم پەڕەیەدا بە کارھێنراون:',
'templatesusedpreview'             => 'ئەو قاڵبانە کە لەم پێشبینینەدا بە کارھێنراون:',
'templatesusedsection'             => 'ئەو قاڵبانە کە لەم بەشەدا بە کارھێنراون:',
'template-protected'               => '(پارێزراو)',
'template-semiprotected'           => '(نیوەپارێزراو)',
'permissionserrorstext-withaction' => 'دەسەڵاتت نییە بۆ $2 لەبەر ئەم {{PLURAL:$1|هۆکارە|هۆکارانە}}ی خوارەوە:',
'deleted-notice'                   => 'ئەم پەڕەیە سڕدراوەتەوە.
لۆگی سڕینەوە بۆ پەڕەکە لە خوارەوە دابینکراوە.',

# History pages
'viewpagelogs'           => 'لۆگەکانی ئەم پەڕەیە ببینە',
'revisionasof'           => 'وەک بینینەوەی $1',
'previousrevision'       => '←پیاچوونەوەی کۆنتر',
'nextrevision'           => 'پیاچوونەوەی نوێتر→',
'currentrevisionlink'    => 'پیاچوونەوەی ئێستا',
'cur'                    => 'ئێستا',
'last'                   => 'پێشوو',
'histlegend'             => 'وەشانەکان بۆ ھەڵسەنگاندن دیاری بکە و ئەم دوگمەی خوارەوە لێبدە. <br />
ڕێنمایی:
(ئێستا) = جیاوازی لەگەڵ وەشانی ئێستا،
(پێشوو) =جیاوازی لەگەڵ وەشانی پێشوو،
ب = گۆڕانکاریی بچووک',
'history-fieldset-title' => 'گەشتی مێژوو',
'deletedrev'             => '[سڕاو]',
'histfirst'              => 'کۆنترین',
'histlast'               => 'نوێترین',
'historysize'            => '({{PLURAL:$1|1 بایت|$1 بایت}})',
'historyempty'           => '(پووچ)',

# Revision feed
'history-feed-title'          => 'مێژووی پیاچوونەوە',
'history-feed-description'    => 'مێژووی پیاچوونەوە بۆ ئەم پەڕە لە ویکییەکە',
'history-feed-item-nocomment' => '$1 لە $2', # user at time

# Revision deletion
'rev-delundel'   => 'پیشاندان/شاردنەوە',
'revdel-restore' => 'چۆنیەتی دەرکەوتن بگۆڕە',

# Merge log
'revertmerge' => 'لەیەک جیاکردنەوە',

# Diffs
'history-title'           => 'مێژووی پیاچوونەوەکانی "$1"',
'difference'              => '(جیاوازی نێوان پیاچوونەوەکان)',
'lineno'                  => 'ھێڵی  $1:',
'compareselectedversions' => 'ھەڵسەنگاندنی وەشانە ھەڵبژاردراوەکان',
'editundo'                => 'پاشگەزبوونەوە',

# Search results
'searchresults'             => 'ئەنجامەکانی گەڕان',
'searchresults-title'       => 'ئەنجامەکانی گەڕان بۆ "$1"',
'searchresulttext'          => 'بۆ زانیاری زیاتر دەربارەی گەڕان {{SITENAME}} ، بڕوانە لە  [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'گەڕایت بۆ \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|هەموو ئەو پەڕانەی دەستپێدەکەن بە "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|هەموو ئەو پەڕانەی بەستەرکراون بۆ "$1"]])',
'searchsubtitleinvalid'     => "گەڕایت بۆ '''$1'''",
'noexactmatch'              => "'''پەڕەیەک بە ناوی  \"\$1\"ەوە نیە.'''
دەتوانی ئەم پەڕە [[:\$1|دروست بکەیت]].",
'noexactmatch-nocreate'     => "'''هیچ پەڕەیەک نییە بە ناونیشانی \"\$1\".'''",
'notitlematches'            => 'لە نێو سەردێڕەکان نەبینرا',
'notextmatches'             => 'لە دەقی نووسراوەکان دا نەبینرا',
'prevn'                     => '$1ی پێشوو',
'nextn'                     => '$1ی دواتر',
'viewprevnext'              => '($1) ($2) ($3) ببینە',
'search-result-size'        => '$1 ({{PLURAL:$2|1 وشە|$2 وشە}})',
'search-redirect'           => '(ئاڵوگۆڕ $1)',
'search-section'            => '(بەشی $1)',
'search-suggest'            => 'ئایا مەبەستت ئەمە بوو: $1',
'search-interwiki-caption'  => 'پرۆژە خوشکەکان',
'search-interwiki-default'  => '$1 ئەنجام:',
'search-interwiki-more'     => '(زیاتر)',
'search-mwsuggest-enabled'  => 'بە پێشنیارەکانەوە',
'search-mwsuggest-disabled' => 'بێ پێشنیار',
'showingresults'            => "لە خوارەوە {{PLURAL:$1|'''1''' ئەنجام|'''$1''' ئەنجام}} ئەبینن کە بە #'''$2'''ەوە دەست پێ‌ئەکات .",
'showingresultsnum'         => "لە خوارەوە {{PLURAL:$1|'''1''' ئەنجام|'''$1''' ئەنجام}} ئەبینن کە بە #'''$2'''ەوە دەست پێ‌ئەکات .",
'showingresultstotal'       => "نیشاندان لە خوارەوە{{PLURAL:$4|result '''$1''' of '''$3'''|ئاکامەکان '''$1 - $2''' of '''$3'''}}",
'powersearch'               => 'بە ھێز بگەڕە',
'powersearch-legend'        => 'گەڕانی پێشکەوتوو',
'powersearch-ns'            => 'لە namespace بگەڕە:',
'powersearch-redir'         => 'گواستنەوەکانی لیست',
'powersearch-field'         => 'گەڕان بۆ',

# Preferences page
'preferences'              => 'ھەڵبژاردەکان',
'mypreferences'            => 'ھەڵبژاردەکانی من',
'prefs-edits'              => 'ژمارەی گۆڕانکارییەکان:',
'changepassword'           => 'تێپەڕوشە بگۆڕە',
'skin'                     => 'پێستە',
'skin-preview'             => 'پێش بینین',
'math'                     => 'بیرکاری',
'dateformat'               => 'ڕازاندەوەی ڕێکەوت',
'datedefault'              => 'ھەڵنەبژێردراو',
'datetime'                 => 'کات و ڕێکەوت',
'prefs-personal'           => 'پرۆفایلی بەکارھێنەر',
'prefs-rc'                 => 'دوایین گۆڕانکارییەکان',
'prefs-watchlist'          => 'لیستی چاودێڕییەکان',
'prefs-watchlist-days'     => 'ژمارە ڕۆژە نیشاندراوەکان لە لیستی چاودێڕییەکان:',
'prefs-watchlist-days-max' => '(ئه‌وپه‌ڕی 7 ڕۆژە)',
'prefs-misc'               => 'جۆراوجۆر',
'prefs-resetpass'          => 'تێپەڕوشە بگۆڕە',
'saveprefs'                => 'پاشکەوت',
'resetprefs'               => 'گۆڕانکارییە پاشکەوت نەکراوەکان پاک بکەرەوە',
'restoreprefs'             => 'ھەموو تەنزیمەکان ببەرەوە بۆ حاڵەتی بنچینەیی',
'textboxsize'              => 'دەستکاری کردن',
'prefs-edit-boxsize'       => 'قەبارەی پەنجەرەی گۆڕانکاری.',
'rows'                     => 'ڕێز:',
'columns'                  => 'ستوون:',
'searchresultshead'        => 'گەڕان',
'timezonelegend'           => 'کاتی ھەرێمی',
'timezonetext'             => '¹ ژمارە ئەو کاتژمێرانە کە کاتی ھەرێمیت لەگەڵ کاتی server (UTC)، ئیختیلافی ھەیە.',
'localtime'                => 'کاتی ناوچەیی:',
'timezoneoffset'           => 'جیاوازی¹:',
'servertime'               => 'کاتی server:',
'guesstimezone'            => 'لە وێبگەڕەکە browser بیگرە',
'allowemail'               => 'لە بەکارھێنەرانی دیکەوە e-mail قەبووڵ دەکەم',
'prefs-searchoptions'      => 'ھەڵبژاردەکانی گەڕان',
'prefs-namespaces'         => 'بۆشایییەکانی ناو',
'defaultns'                => 'لە حاڵەتی بنەڕەت لەم بۆشایی ناوانەدا بگەڕە:',
'default'                  => 'بنچینەیی',
'files'                    => 'پەڕگەکان',
'prefs-custom-css'         => 'CSSی دڵخواز',
'prefs-custom-js'          => 'JSی دڵخواز',

# Groups
'group-sysop'      => 'بەڕێوبەران',
'group-bureaucrat' => 'بورووکراتەکان',

'group-user-member' => 'بەکارھێنەر',

'grouppage-user'  => '{{ns:project}}:بەکارھێنەران',
'grouppage-sysop' => '{{ns:project}}:بەڕێوبەران',

# User rights log
'rightslog' => 'لۆگی مافەکانی بەکارهێنەر',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'دەستکاری ئەم پەڕەیە بکە',
'action-move' => 'گواستنەوەی ئەم پەڕە',

# Recent changes
'nchanges'             => '$1 {{PLURAL:$1|گۆڕانکاری|گۆڕانکاری}}',
'recentchanges'        => 'دوایین گۆڕانکارییەکان',
'recentchanges-legend' => 'هەڵبژاردنەکانی دوا گۆڕانکارییەکان',
'rcnote'               => "لە خوارەوەدا {{PLURAL:$1|'''۱''' گۆڕانکاری |دوایین '''$1''' گۆڕانکارییەکان}} لە دوایین {{PLURAL:$2|ڕۆژ|'''$2''' ڕۆژەوە}} ، تا $5، $4 دەبینن.",
'rclistfrom'           => 'گۆڕانکارییە نوێکان کە لە $1ەوە دەست پێدەکەن نیشان بدە.',
'rcshowhideminor'      => '$1 دەستکارییە بچووکەکان',
'rcshowhidebots'       => 'ڕۆبۆتەکان $1',
'rcshowhideliu'        => 'بەکارھێنەرە لە ژوورەکان $1',
'rcshowhideanons'      => 'بەکارھێنەرە نەناسراوەکان $1',
'rcshowhidepatr'       => 'گۆرانکارییە کۆنترۆڵکراوەکان $1',
'rcshowhidemine'       => '$1 دەستکارییەکانم',
'rclinks'              => 'دوایین $1 گۆڕانکارییەکانی دوایین $2 ڕۆژی <br />$3',
'diff'                 => 'جیاوازی',
'hist'                 => 'مێژوو',
'hide'                 => 'بشارەوە',
'show'                 => 'نیشان بدە',
'minoreditletter'      => 'ب',
'newpageletter'        => 'ن',
'boteditletter'        => 'ڕ',
'rc-enhanced-expand'   => 'وردەکارییەکان پیشان بدە (پێویستی بە جاڤاسکریپتە)',
'rc-enhanced-hide'     => 'وردەکارییەکان بشارەوە',

# Recent changes linked
'recentchangeslinked'         => 'گۆڕانکارییە پەیوەندی‌دارەکان',
'recentchangeslinked-title'   => 'گۆڕانکارییە پەیوەندیدارەکان بە "$1" ـەوە',
'recentchangeslinked-summary' => "Ev rûpela taybetî guherandinên dawî ji rûpelên lînkkirî nîşandide.
ئەو پەڕانە کە لە [[Special:Watchlist|لیستی چاودێڕییەکانت]]دان '''ئەستوورن'''",
'recentchangeslinked-page'    => 'ناوی پەڕە:',

# Upload
'upload'              => 'پەڕگەیەک بار بکە',
'uploadbtn'           => 'پەڕگە بار بکە',
'reupload'            => 'دیسان بار بکە',
'uploadtext'          => "فۆرمی خوارەوە بەکاربێنن بۆ بارکردنی پەڕگە.
بۆ بینینی ئەو پەڕگانە کە پێشتر بار کراون بڕۆ بۆ [[Special:FileList|لیستی پەڕگە بارکراوەکان]]، ھەروەھا
[[Special:Log/upload|ڕەشنووسی بارکردنەکان]] و [[Special:Log/delete|رەشنووسی سڕینەوەکان]].

بۆ بەکارھێنانی پەڕگەیەک لە پەڕەیەک دا، بەستەرێک بە یەکێک لەم شۆوازانەی خوارەوە بە کار بێنن:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' 
to use the full version of the file
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>'''
to use a 200 pixel wide rendition in a box in the left margin with 'alt text' as description
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''
for directly linking to the file without displaying the file",
'upload-permitted'    => 'نەوعە پەڕگە قەبووڵ کراوەکان: $1.',
'uploadlogpage'       => 'لۆگ باربکە',
'filename'            => 'ناوی پەڕگە',
'filedesc'            => 'پوختە',
'fileuploadsummary'   => 'پوختە:',
'filereuploadsummary' => 'گۆرانکارییەکانی پەڕگە:',
'filestatus'          => 'بارودۆخی مافی لەبەرگرتنەوە:',
'filesource'          => 'سەرچاوە:',
'ignorewarnings'      => 'گوێ مەدە بە ئاگادارییەکان',
'uploadwarning'       => 'ئاگادارییەکانی بارکردن',
'savefile'            => 'پەڕگە پاشەکەوت بکە',
'uploadedimage'       => '"[[$1]]" بار کراو',
'overwroteimage'      => 'وەشانێ نوێی "[[$1]]" بار کرا',
'uploaddisabled'      => 'بارکردن قەدەخە کراوە',
'sourcefilename'      => 'ناوی پەڕگەی سەرچاوە:',
'destfilename'        => 'ناوی مەبەست:',
'upload-maxfilesize'  => 'ئەو پەری قەبارەی فایل: $1',
'watchthisupload'     => 'چاودێڕی ئەم پەڕە بکە',

# Special:ListFiles
'imgfile'        => 'پەڕگە',
'listfiles'      => 'لیستی پەرگەکان',
'listfiles_date' => 'ڕێکەوت',
'listfiles_name' => 'ناو',
'listfiles_user' => 'بەکارھێنەر',

# File description page
'filehist'                  => 'مێژووی پەڕگە',
'filehist-current'          => 'هەنووکە',
'filehist-datetime'         => 'ڕێکەوت/کات',
'filehist-user'             => 'بەکارهێنەر',
'filehist-dimensions'       => 'دوورییەکان',
'filehist-comment'          => 'لێدوان',
'imagelinks'                => 'بەستەرەکانی پەڕگە',
'shareduploadwiki-linktext' => 'پەڕەی پەسنی پەڕگە',
'uploadnewversion-linktext' => 'وەشانێکی نوێی ئەم پەڕەیە بار بکە',

# Unused templates
'unusedtemplates' => 'قاڵبە بە کار نەھێراوەکان',

# Random page
'randompage' => 'پەڕەیەک بە ھەرەمەکی',

# Statistics
'statistics'              => 'ئامارەکان',
'statistics-header-pages' => 'ئامارەکانی پەڕەکان',
'statistics-header-edits' => 'ئامارەکانی گۆڕانکارییەکان',
'statistics-header-views' => 'ئامارەکانی سەردانەکان',
'statistics-header-users' => 'ئامارەکانی بەکارھێنەران',
'statistics-articles'     => 'پەڕە بە ناوەڕۆکەکان',
'statistics-pages'        => 'پەڕەکان',

'disambiguations' => 'پەڕەکانی جوداکردنەوە (لێڵی لابەر)',

'withoutinterwiki' => 'پەڕەکان کە بەستەرەکانی زمانیان نییە',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بایت|بایت}}',
'ncategories'             => '$1 {{PLURAL:$1|ھاوپۆل|ھاوپۆل}}',
'nlinks'                  => '$1 {{PLURAL:$1|بەستەر|بەستەر}}',
'nmembers'                => '$1 {{PLURAL:$1|ئەندام|ئەندام}}',
'nrevisions'              => '$1 {{PLURAL:$1|پیاچوونەوە|پیاچوونەوە}}',
'nviews'                  => '$1 جار {{PLURAL:$1|بینراو|بینراو}}',
'uncategorizedpages'      => 'پەڕە بێ ھاوپۆلەکان',
'uncategorizedcategories' => 'ھاوپۆلە ھاوپۆلدارنەکراوەکان',
'uncategorizedimages'     => 'پەڕگە بێ ھاوپۆلەکان',
'uncategorizedtemplates'  => 'قاڵبە بێ ھاوپۆلەکان',
'unusedcategories'        => 'ھاوپۆلە بەکارنەھێنراوەکان',
'unusedimages'            => 'پەڕگە بەکارنەھێنراوەکان',
'popularpages'            => 'پەڕە مەحبووبەکان',
'wantedcategories'        => 'ھاوپۆلە داواکراوەکان',
'wantedpages'             => 'پەڕە داواکراوەکان',
'wantedfiles'             => 'پەڕگە داواکراوەکان',
'wantedtemplates'         => 'قاڵبە داواکراوەکان',
'mostcategories'          => 'پەڕەکان بە زۆرترین ھاوپۆلەوە',
'prefixindex'             => 'هەموو پەڕەکان بە prefix ـەوە',
'shortpages'              => 'پەڕە کورتەکان',
'longpages'               => 'پەڕە دڕێژەکان',
'newpages'                => 'پەڕە نوێکان',
'ancientpages'            => 'کۆنترین پەڕەکان',
'move'                    => 'ناوی ئەم پەڕە بگۆڕە',
'movethispage'            => 'ئەم پەڕەیە بگوازەوە',
'pager-newer-n'           => '{{PLURAL:$1|نوێتر 1|نوێتر $1}}',
'pager-older-n'           => '{{PLURAL:$1|کۆنتر 1|کۆنتر $1}}',

# Book sources
'booksources'               => 'سەرچاوەکانی کتێب',
'booksources-search-legend' => 'بۆ سەرچاوەی کتێب بگەڕێ',
'booksources-go'            => 'بڕۆ',

# Special:Log
'log' => 'لۆگەکان',

# Special:AllPages
'allpages'       => 'ھەموو پەڕەکان',
'alphaindexline' => '$1 تا $2',
'nextpage'       => 'پەڕەی پاشەوە ($1)',
'prevpage'       => 'پەڕەی پێشەوە ($1)',
'allpagesfrom'   => 'بینینی پەڕەکان بە دەست پێ کردن لە:',
'allarticles'    => 'ھەمووی وتارەکان',
'allpagesprev'   => 'پێش',
'allpagesnext'   => 'پاش',
'allpagessubmit' => 'بڕۆ',

# Special:Categories
'categories' => 'هاوپۆله‌كان',

# Special:DeletedContributions
'deletedcontributions' => 'بەشدارییە بەکارھێنەریە سڕاوەکان',

# Special:LinkSearch
'linksearch' => 'بەستەرە دەرەکییەکان',

# Special:Log/newusers
'newuserlogpage'          => 'لۆگی دروست کردنی بەکارھێنەر',
'newuserlog-create-entry' => 'بەکارھێنەری نوێ',

# Special:ListGroupRights
'listgrouprights'         => 'مافەکانی گرووپە بەکارھێنەرییەکان',
'listgrouprights-members' => '(لیستی ئەندامەکان)',

# E-mail user
'emailuser'       => 'بۆ ئەم بەکارھێنەرە E-Mail بنێرە',
'emailpage'       => 'ئیمەیل بۆ بەکارھێنەر',
'defemailsubject' => 'ئیمەیلی {{SITENAME}}',
'emailfrom'       => 'لە:',
'emailto'         => 'بۆ:',
'emailsubject'    => 'بابەت:',
'emailmessage'    => 'نامە:',
'emailsend'       => 'بینێرە',
'emailccme'       => 'کۆپییەک لە نامەکە بنێرە بۆ ئیمەیلەکەم.',
'emailccsubject'  => 'کۆپیی نامەکەت بۆ $1: $2',
'emailsent'       => 'نامەکەت ناردرا',
'emailsenttext'   => 'نامەکەت ناردرا',
'emailuserfooter' => 'ئەم ئیمەیلە لە $1ەوە ناردرا بۆ $2 بە "Email user" لە {{SITENAME}}ەوە.',

# Watchlist
'watchlist'            => 'لیستی چاودێڕییەکانی من',
'mywatchlist'          => 'لیستی چاودێڕی‌یەکانم',
'watchlistfor'         => "(بۆ '''$1''')",
'nowatchlist'          => 'لە لیستی چاودێڕییەکانتدا ھیچ نیە.',
'watchnologin'         => 'لە ژوورەوە نیت.',
'addedwatch'           => 'بە لیستی چاودێڕییەکانت زێدە کرا',
'removedwatch'         => 'لە لیستی چاودێڕییەکانت لابرا',
'watch'                => 'چاودێڕی بکە',
'watchthispage'        => 'چاودێڕیی ئەم پەڕە بکە',
'unwatch'              => 'لابردنی چاودێڕی',
'unwatchthispage'      => 'ئیتر چاودێڕی مەکە',
'notanarticle'         => 'پەڕەی بێ ناوەڕۆک',
'notvisiblerev'        => 'پیاچوونەوە سڕاوەتەوە',
'watchnochange'        => 'لە کاتی دیاری کراو دا، بابەتە چاودێڕی کراوەکانت، دەستکاری نەکراون',
'watchlist-details'    => '* {{PLURAL:$1|پەڕە tê|$1 پەڕە}} لە چاودێڕیەکانت، پەڕەی وتووێژەکان حسێب ناکەن',
'wlheader-enotif'      => '* ئەکرێ بە E-mail ئاگاداری بدەی',
'wlheader-showupdated' => "* ‏ئەو پەڕانە کە لە پاش دواین سەردانت دەستکاری کراون بە '''ئەستوور''' نیشان دراون",
'watchlistcontains'    => 'لیستی چاودێڕییەکانت $1 {{PLURAL:$1|پەڕە|پەڕە}}ی تێدایە.',
'iteminvalidname'      => "ھەڵە لەگەڵ بابەتی '$1'، ناوی نادروست...",
'wlnote'               => "خوارەوە {{PLURAL:$1|دوایین گۆڕانکاریە|دوایین '''$1''' گۆڕانکارییەکانن}} لە دواین  {{PLURAL:$2|کاتژمێر|'''$2''' کاتژمێر}} دا.",
'wlshowlast'           => 'نیشان دانی دوایین $1 کاتژمێری، $2 ڕۆژ لە $3',
'watchlist-options'    => 'ھەڵبژاردەکانی لیستی چاودێڕییەکان',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'چاودێڕی...',
'unwatching' => 'لابردنی چاودێڕی...',

'enotif_reset' => 'ھەمووی پەڕەکان وەک بینراو دیاری بکە',

# Delete
'deletepage'            => 'پەڕە بسڕەوەو',
'confirm'               => 'پشتدار بکەرەوە',
'excontent'             => "ناوەڕۆک ئەمە بو: '$1'",
'excontentauthor'       => "ناوەڕۆک ئەمە بو: '$1'(و تەنھا بەشداریکەر  '[[Special:Contributions/$2|$2]]' بوو)",
'exbeforeblank'         => "ناوەڕۆک بەر لە بەتاڵ کردنەوە ئەمە بوو: '$1'",
'exblank'               => 'پەڕە خاڵی بوو',
'delete-confirm'        => 'سڕینەوەی "$1"',
'delete-legend'         => 'سڕینەوە',
'historywarning'        => 'ئاگاداری: ئەم پەڕە کە ئەتەوێ بیسڕیتەوە مێژووی ھەیە',
'actioncomplete'        => 'چالاکی دوایی هاو.',
'deletedarticle'        => '"[[$1]]" سڕدرایەوە',
'dellogpage'            => 'لۆگی سڕینەوە',
'deletecomment'         => 'ھۆکاری سڕینەوە:',
'deleteotherreason'     => 'ھۆکاری دیکە:',
'deletereasonotherlist' => 'ھۆکاری دیکە',
'deletereason-dropdown' => '* ھۆکاری سڕینەوە
** داواکاریی نووسەر
** تێکدانی مافی لەبەرگرتنەوە
** خراپکاری',

# Rollback
'rollbacklink' => 'گەڕاندنەوە',

# Protect
'protectlogpage'              => 'لۆگی پاراستن',
'protectedarticle'            => 'پارێزراو[[$1]]',
'modifiedarticleprotection'   => 'ئاستی پاراستنی "[[$1]]"ی گۆڕا',
'unprotectedarticle'          => '"[[$1]]" لە حاڵی ئێستا دا نەپازراوە',
'movedarticleprotection'      => 'ڕێککارییەکانی پاراستن لە  "[[$2]]" گوازرایەوە بۆ "[[$1]]"',
'protect-title'               => 'ئاستی پاراستنی "$1" بگۆڕە',
'prot_1movedto2'              => '[[$1]] گوازراوەتەوە بۆ [[$2]]',
'protect-backlink'            => '← $1',
'protect-legend'              => 'پاراستن تەیید بکە',
'protectcomment'              => 'ھۆکاری پاراستن:',
'protectexpiry'               => 'ھەتا:',
'protect_expiry_invalid'      => 'کاتی بەسەرچوون نادروستە:',
'protect_expiry_old'          => 'کاتی بەسەرچوون ڕابردووە',
'protect-unchain'             => 'ئاستەنگی ئیزنی گواستنەوە لا ببە',
'protect-text'                => "تۆ دەتوانی لێرە ئاستی پاراستنی لاپەڕەکە ببینی وە بیگۆڕی '''<nowiki>$1</nowiki>''' .",
'protect-locked-access'       => "ئەکانتەکەی تۆ ڕێگەی ئەوەی پێ نەدراوە کە بتوانێت ئاستی پاراستنی لاپەڕە بگۆڕێت.
ڕێککارییەکانی ئێستای لاپەڕەکە لێرەدایە '''$1''':",
'protect-cascadeon'           => 'ئەم لاپەڕە لە حاڵی ئێستا دا پارێزراوە چونکا لە نێو ئەم {{PLURAL:$1|لاپەڕ(ان)ە دایە کە }} حاڵەتی پاراستنی تاڤگەیی ئەو(ان) ھەڵکراوە

تۆ دەتوانی ئاستی پاراستنی ئەم لاپەڕە بگۆڕی، بەڵام ئەم گۆڕانە ھیچ کاریگەر نابێت لە سەر پاراستنی تاڤگەیی',
'protect-default'             => 'بە ھەموو بەکارھێنەران ڕێگە بدە',
'protect-fallback'            => 'پێویستی بە ئیزنی "$1" ھەیە',
'protect-level-autoconfirmed' => 'بەکارھێنەرانی نوێ و تۆمارنەکراو ئاستەنگ بکە',
'protect-level-sysop'         => 'تەنھا بەڕێوەبەران (admînan)',
'protect-summary-cascade'     => 'تاڤگەیی',
'protect-expiring'            => 'لەم بەروارە بەسەر دەچێت $1 (UTC)',
'protect-cascade'             => 'لاپەڕەکانی نێو ئەم لاپەتە بپارێزە (پاراستنی تاڤگەیی)',
'protect-cantedit'            => 'ئێمە ناتوانین ئاستی پاراستنی ئەم لاپەڕە بگۆڕین، چونکا تۆ ئیجازەی گۆڕینت نیە.',
'restriction-type'            => 'ئیزن:',
'restriction-level'           => 'ئاستی سنووردارکردن:',

# Undelete
'undeletelink'     => 'بینین/گێڕاندنەوە',
'undeletedarticle' => '"[[$1]]" گێڕدرایەوە',

# Namespace form on various pages
'namespace'      => 'بۆشاییی ناو',
'invert'         => 'ھەڵبژاردەکان پێچەوانە بکە',
'blanknamespace' => '(سەرەکی)',

# Contributions
'contributions'       => 'بەشدارییەکانی بەکارھێنەر',
'contributions-title' => 'بەشدارییەکانی بەکارھێنەر $1',
'mycontris'           => 'بەشدارییەکانی من',
'contribsub2'         => 'بۆ$1 ($2)',
'uctop'               => '(لوتکە)',
'month'               => 'لە مانگی (و پێشترەوە):',
'year'                => 'لە ساڵی (و پێشترەوە):',

'sp-contributions-newbies'     => 'تەنھا بەشدارییەکانی بەکارھێنەرە تازەکان نیشان بدە',
'sp-contributions-newbies-sub' => 'لە بەکارھێنەرە تازەکانەوە',
'sp-contributions-blocklog'    => 'لۆگی بەربەستن',
'sp-contributions-search'      => 'گەڕین بۆ بەشدارییەکان',
'sp-contributions-username'    => 'ئەدرەسی IP یان بەکارھێنەر:',
'sp-contributions-submit'      => 'بگەڕە',

# What links here
'whatlinkshere'            => 'بەسراوەکان بە ئێرەوە',
'whatlinkshere-title'      => 'ئەو پەڕانەی بەستەرکراون بۆ "$1"',
'whatlinkshere-page'       => 'پەڕە:',
'isredirect'               => 'پەڕە ئاڕاستە بکە',
'isimage'                  => 'بەستەری وێنە',
'whatlinkshere-links'      => '← بەستەرەکان',
'whatlinkshere-hideredirs' => '$1 ئاڕاستەکراو هەیە',
'whatlinkshere-hidelinks'  => '$1 بەستەر',
'whatlinkshere-filters'    => 'پاڵێوەرەکان',

# Block/unblock
'blockip'                  => 'بەکارھێنەر ئاستەنگ بکە',
'ipboptions'               => '2 کاتژمێر:2 hours,1 ڕۆژ:1 day,3 ڕۆژ:3 days,1 ھەفتە:1 week,2 ھەفتە:2 weeks,1 مانگ:1 month,3 مانگ:3 months,6 مانگ:6 months,1 ساڵ:1 year,بێ سنوور:infinite', # display1:time1,display2:time2,...
'ipbotheroption'           => 'دیکە',
'blocklink'                => 'بەربەستن',
'unblocklink'              => 'لابردنی ئاستەنگ',
'change-blocklink'         => 'گۆڕاندنی ئاستەنگ',
'contribslink'             => 'بەشداری',
'blocklogpage'             => 'لۆگی بلۆککردن',
'block-log-flags-nocreate' => 'دروستکردنی هەژمار ناچالاککراوە',

# Move page
'movepagetext'        => "لە ڕێگەی ئەم فۆرمەی خوارەوە ناوی پەڕە دەگۆڕدرێت، وە ھەموو مێژووەکەی دەگوازێتەوە بۆ ناوی نوێ.
ئەگەر لە بەشی گەڕان ناوە کۆنەکەی پێ بدەی بە شێوەی خۆکار پەڕەکە دەگوازرێتەوە بۆ ناوە نوێکە .
تۆ دەتوانی ئەو بەستەری ئاڵوگۆرانە بگۆڕی کە بەشێوەی خۆکار دەچێنە سەر لاپەڕەی ئەسڵی
ئەگەر ناتەوێت ئەم کارە بکەی، دڵنیا بە کە [[Special:DoubleRedirects|دوبلەکان]]   یان [[Special:BrokenRedirects|شکاوەکان]] تاقی بکەیتەوە.
تۆ دەتوانی ئەو بەستەری ئاڵوگۆرانە تازە بکەیتەوە کە بەشێوەی خۆکار دەچێنە سەر لاپەڕەی ئەسڵی
ئەگەر ناتەوێت ئەم کارە بکەی، دڵنیا بە کە [[Special:DoubleRedirects|دوبلەکان]]   یان [[Special:BrokenRedirects|شکاوەکان]] تاقی بکەیتەوە.
تۆ بەرپرسیاری دڵنیا ببیتەوە لەوەی کە بەستەرەکان دەچنە سەر خاڵێک کە چاوەروان دەکرێت بچنە ئەوێ.

دەبێت بزانی کە ئەگەر پێشتر لاپەڕەیەک بەم ناوە ھەبێت لاپەڕەکە ناگوازرێتەوە، مەگەر ئەوەی کە لاپەڕەکە بەتاڵ بێت یان ئاڵوگۆر بێت وە ھیچ مێژووی گۆڕاندنی پێشووی نەبێت.

ئەمە بەو واتایە کە ئەگەر ھەڵەیەک بکەی دەتوانی ناوی لاپەڕەکە دیسانەوە بگۆڕی بۆ ناوی پێشووی، وە ناتوانی بیخەیە جێگەی پەڕەیەک کە ھەنووکە ھەیە.

'''ئاگاداریی'''
ئەمە دەتوانێت گۆڕانێکی زۆر نابەجێ و چاوەڕوانەکراو بێت لە لاپەڕەیەکی ناسراو؛
تکایە پێش گۆڕینی ناو باش بیر لە ئاکامەکەی بکەوە.",
'movearticle'         => 'ئەم لاپەڕە بگوازەوە:',
'newtitle'            => 'بۆ ناوێکی نوێ:',
'move-watch'          => 'ئەم لاپەڕە چاودێری بکە',
'movepagebtn'         => 'ئەم لاپەڕە بگوازەوە',
'pagemovedsub'        => 'گواستنەوە بە سەرکەوتوویی جێبەجێ کرا',
'movepage-moved'      => '<big>\'\'\'"$1" گوازراوەتەوە بۆ "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'       => 'لاپەڕەیەک بەم ناوە ھەیە، یان ئەو ناوەی تۆ ھەڵتبژاردووە بایەخدار نیە.
تکایە ناوێکی دیکە ھەڵبژێرە',
'movedto'             => 'بوو بە',
'movetalk'            => 'پەڕەی گوفتوگۆکەشی بگۆزەرەوە',
'move-subpages'       => 'ھەموو ژێرپەڕەکانیشی (بە ئەندازەی $1) بگۆزەرەوە، ئەگەر بیبێت',
'move-talk-subpages'  => 'ھەموو ژێرپەڕەکانی (بە ئەندارەی $1) پەڕەی گوفتوگۆکەشی بگۆزەرەوە، ئەگەر بیبێت',
'1movedto2_redir'     => 'بە ڕەوانکردنەوە، $1 ڕۆیشتە جێگەی $2',
'movelogpage'         => 'لۆگ بگوازەوە',
'movereason'          => 'بە ھۆی:',
'revertmove'          => 'پێچەوانەکردنەوە',
'move-leave-redirect' => 'ڕەوانکردنەوەیەک دابنە بۆ پەڕە نوێکە',

# Export
'export' => 'پەڕەکان هەناردە بکە',

# Thumbnails
'thumbnail-more' => 'گەورە کردنەوە',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'پەڕە شەخسییەکەت',
'tooltip-pt-mytalk'               => 'پەڕەی وتوبێژی تۆ',
'tooltip-pt-preferences'          => 'بژاردەکانت',
'tooltip-pt-watchlist'            => 'لیستی ئەو لاپەرانی کە چاودێری گۆڕانکارییەکانیان دەکەی',
'tooltip-pt-mycontris'            => 'لیستی بەشدارییەکانت',
'tooltip-pt-login'                => 'هاندەدرێیت کە بچیتە ژوورەوە؛ هەرچەندە، پێویست نییە',
'tooltip-pt-logout'               => 'دەرچوون',
'tooltip-ca-talk'                 => 'گفتوگۆ دەربارەی ناوەڕۆکی پەرە',
'tooltip-ca-edit'                 => 'ئەتوانی دەستکاری ئەم پەڕەیە بکەیت.
تکایە دوگمەی پێشبینین بەکارببە پێش پاشەکەوتکردن.',
'tooltip-ca-addsection'           => 'بەشێکی نوێ دەست پێ بکە',
'tooltip-ca-viewsource'           => 'ئەم پەڕەیە پارێزراوە.
ئەتوانی سەرچاوەکەی ببینیت',
'tooltip-ca-history'              => 'وەشانەکانی پێشووی ئەم پەڕەیە',
'tooltip-ca-protect'              => 'ئەم پەڕەیە بپارێزە',
'tooltip-ca-delete'               => 'ئەم پەڕەیە بسڕەوە',
'tooltip-ca-move'                 => 'ئەم پەڕەیە بگوازەوە',
'tooltip-ca-watch'                => 'ئەم پەڕە بخە سەر لیستی چاودێریت',
'tooltip-search'                  => 'لە {{SITENAME}} بگەڕێ',
'tooltip-search-go'               => 'بڕۆ بۆ پەڕەیەک کە بە تەواوەتی ئەم ناوەی تیادایە ئەگەر هەبێت',
'tooltip-search-fulltext'         => 'لە پەڕەکاندا بگەڕێ بۆ ئەم دەقە',
'tooltip-n-mainpage'              => 'بینینی پەڕەی دەستپێک',
'tooltip-n-portal'                => 'دەربارەی پڕۆژەکە، چی ئەتوانی بکەیت، لە کوێ شتەکان بدۆزیتەوە',
'tooltip-n-currentevents'         => 'زانیاری پێشینە بەدەست بھێنە دەربارەی بۆنە ھەنووکەییەکان',
'tooltip-n-recentchanges'         => 'لیستی دوایین گۆڕانکارییەکان لەم ویکییەدا',
'tooltip-n-randompage'            => 'پەڕەیەکی ڕەمەکی پیشان بدە',
'tooltip-n-help'                  => 'شوێنێک بۆ دۆزینەوەی',
'tooltip-t-whatlinkshere'         => 'لیستی هەموو ئەو پەڕانەی ویکی کە بەستەرکراون بۆ ئێرە',
'tooltip-t-recentchangeslinked'   => 'دوایین گۆڕانکارییەکان لەو پەڕانە کە بەگرەوە گرێ دراون',
'tooltip-feed-rss'                => 'RSS بۆ ئەم گۆڕانکارییەکانی ئەم پەڕە',
'tooltip-feed-atom'               => 'Atom feed بۆ ئەم گۆڕانکارییەکانی ئەم پەڕە',
'tooltip-t-contributions'         => 'بینینی بەشدارییەکانی ئەم بەکارھێنەرە',
'tooltip-t-emailuser'             => 'ئیمەیلێک بنێرە بۆ ئەم بەکارھێنەرە',
'tooltip-t-upload'                => 'پەڕگەیەک (فایل) بار بکە',
'tooltip-t-specialpages'          => 'لیستی ھەموو پەڕە تایبەتەکان',
'tooltip-t-print'                 => 'وەشانی چاپی ئەم پەڕەیە',
'tooltip-t-permalink'             => 'گرێدەری ھەمیشەیی بۆ ئەم وەشنەی ئەم پەڕەیە',
'tooltip-ca-nstab-main'           => 'بینینی پەڕەی ناوەڕۆک',
'tooltip-ca-nstab-user'           => 'پەڕەی بەکارھێنەر تەماشا بکە',
'tooltip-ca-nstab-media'          => 'پەڕەی میدیا چاو لێ بکە',
'tooltip-ca-nstab-special'        => 'ئەمە پەڕەیەکی تایبەتە، ناتوانی ئەم پەڕە خۆی دەستکاری بکەیت',
'tooltip-ca-nstab-project'        => 'بینینی پەڕەی پرۆژە',
'tooltip-ca-nstab-image'          => 'بینینی پەڕەی پەڕگە',
'tooltip-ca-nstab-mediawiki'      => 'بینینی پەیامی سیستەم',
'tooltip-ca-nstab-template'       => 'بینینی شابلۆنەکە',
'tooltip-ca-nstab-help'           => 'بینینی پەڕەی رێنمایی',
'tooltip-ca-nstab-category'       => 'بینینی پەڕەی ھاوپۆلەکان',
'tooltip-minoredit'               => 'ئەمە وەک گۆڕانکارییەکی بچووک دیاری بکە',
'tooltip-save'                    => 'گۆڕانکارییەکانی خۆت پاشکەوت بکە',
'tooltip-preview'                 => 'پێش بینینی گۆڕانکارییەکان، تکایە پێش پاشکەوت کردن ئەمە بەکار بھێنە',
'tooltip-compareselectedversions' => 'جیاوازییەکانی دوو وەشانە دیاریکراوەی ئەم پەڕە ببینە.',

# Skin names
'skinname-standard'    => 'کلاسیک',
'skinname-nostalgia'   => 'خەریبی',
'skinname-cologneblue' => 'شینی کۆلۆن',
'skinname-monobook'    => 'مۆنۆ',
'skinname-myskin'      => 'پێستی خۆم',
'skinname-chick'       => 'جوجه‌',
'skinname-simple'      => 'ساده‌',
'skinname-modern'      => 'مۆدێڕن',

# Math options
'mw_math_png'    => 'ھەموو جارێک وەک PNG نیشان بدە',
'mw_math_simple' => 'HTML ئەگەر ساکار بێت, ئەگەرنا PNG',
'mw_math_html'   => 'ئەگەر بکرێ بە HTML ، ئەگەرنا بە PNG',

# Browsing diffs
'previousdiff' => '← دەستکاری کۆنتر',
'nextdiff'     => 'دەستکاری نوێتر →',

# Media information
'thumbsize'            => 'قەبارەی Thumbnail:',
'svg-long-desc'        => '(پەڕگەی SVG، بە ناو $1 × $2 خاڵ، قەبارەی پەڕگە: $3)',
'show-big-image'       => 'گەورە کردنەوە',
'show-big-image-thumb' => '<small>قەبارەی ئەم پێشبینینە: $1 × $2 خاڵە</small>',

# Special:NewFiles
'newimages' => 'پێشانگای پەڕگە نوێکان',

# Bad image list
'bad_image_list' => 'فۆرمات بەم شێوەی خوارەوەیە:

تەنھا ئەو بابەتانەی کە کە لیست کراون (واتە ئەو دێڕانەی بە * دەست پێ دەکەن) لێک ئەدرێتەوە.
یەکەم بەستەر لە سەر دێڕێک دەبێت بەستەری فایلێکی خراپ بێت.
ھەموو بەستەرەکانی دوای ئەو کە لەسەر ھەمان دێڕن وەکوو نائاسایی دێتە ھەژمار، واتە ئەو لاپەڕانەی کە ڕەنگە تێدا فایل بە شێوەی ئینلاین بێت',

# Variants for Kurdish language
'variantname-ku-arab' => 'ئەلفوبێی عەرەبی',
'variantname-ku-latn' => 'ئەلفوبێی لاتینی',

# Metadata
'metadata-help'     => 'ئەم پەڕگە زانیاری زێدەی ھەیە، کە لەوە دەچێت کامێرا یان ھێماگر (scanner) خستبێتیە سەری. ئەگەر پەڕگەکە لە حاڵەتی سەرەتاییەکەیەوە دەستکاری کرابێ، شایەد بڕێ لە بڕگەکان بە تەواوی زانیارەکانی وێنە گۆڕدراوەکە نیشان نەدەن.',
'metadata-expand'   => 'وردەکارییە درێژکراوەکان پیشان بدە',
'metadata-collapse' => 'وردەکارییە درێژکراوەکان بشارەوە',
'metadata-fields'   => 'ئەو کێڵگە EXIFانە لەم پەیامە بە ڕیز کراون، کاتێک خشتەی metadata کۆ کراوەش بێ ھەر نیشان ئەدرێت. کێڵگەکانی تر تا خشتەکە باز نەکرێ، شاراوەن.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally' => 'دەستکاری ئەم پەڕەیە بکە بە بەکارهێنانی پڕۆگرامێکی دەرەکی',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ھەموو',
'imagelistall'     => 'ھەموو',
'watchlistall2'    => 'ھەموو',
'namespacesall'    => 'ھەموو',
'monthsall'        => 'هەموو',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator'     => '،&#32;',

# Live preview
'livepreview-loading' => 'له‌باركردنایه‌ ...',
'livepreview-ready'   => 'ئاماده‌یه‌',

# Watchlist editor
'watchlistedit-numitems' => 'بێجگە لە پەڕەی وتووێژەکان، لیستی چاودێڕییەکانت {{PLURAL:$1|1 بابەت|$1 بابەت}}ی تێدایە،',
'watchlistedit-noitems'  => 'لیستی چاودێڕییەکانت ھیچ بابەتێکی تێدا نییە.',

# Watchlist editing tools
'watchlisttools-view' => 'بینینی گۆڕانکارییە پەیوەندی‌دارەکان',
'watchlisttools-edit' => 'بینین و دەستکاری کردنی لیستی چاودێڕییەکان',
'watchlisttools-raw'  => 'دەستکاری کردنی لیستی خامی چاودێڕییەکان',

# Iranian month names
'iranian-calendar-m1' => 'خاکەلێوە',
'iranian-calendar-m2' => 'گوڵان',
'iranian-calendar-m3' => 'جۆزەردان',
'iranian-calendar-m4' => 'پووشپەڕ',
'iranian-calendar-m5' => 'گەلاوێژ',
'iranian-calendar-m6' => 'خەرمانان',

# Special:Version
'version' => 'وەشان', # Not used as normal message but as header for the special page itself

# Special:FilePath
'filepath'        => 'ڕێڕەوی پەڕگە',
'filepath-page'   => 'پەڕگە:',
'filepath-submit' => 'ڕێڕەو',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'گەڕان بۆ پەڕگە دووپات کراوەکان',

# Special:SpecialPages
'specialpages'               => 'لاپەڕە تایبەتەکان',
'specialpages-group-other'   => 'پەڕە تایبەتەکانی دیکە',
'specialpages-group-login'   => 'چوونە ژوورەوە/ناونووسین',
'specialpages-group-changes' => 'دوایین گۆڕانکارییەکان و ڕەشنووسەکان',
'specialpages-group-media'   => 'گوزارشتەکان و بارکردنەکانی مێدیا',
'specialpages-group-users'   => 'بەکارھێنەران و مافەمان',
'specialpages-group-pages'   => 'لیستی پەڕەکان',

);
