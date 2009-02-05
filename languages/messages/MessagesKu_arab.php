<?php
/** ‫كوردي (عەرەبی)‬ (‫كوردي (عەرەبی)‬)
 *
 * @ingroup Language
 * @file
 *
 * @author Aras Noori
 * @author Asoxor
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
'tog-underline'               => 'هێڵ به‌ژێر به‌سته‌ر دا بهێنه‌ :',
'tog-highlightbroken'         => 'شێوازدانان بگۆڕه‌',
'tog-justify'                 => 'كۆپله‌كه‌ ڕێك بكه‌',
'tog-hideminor'               => 'دوا گۆڕانكارییه‌ گچكه‌كان ون بكه‌',
'tog-extendwatchlist'         => 'لیستی ته‌ماشاكردن درێژبكه‌ره‌وه‌ تاكوو هه‌موو گۆڕانكارییه‌كان به‌رچاوت بكه‌وێ',
'tog-usenewrc'                => 'دوا گۆڕانكارییه‌كان چاك بكه‌ (سكریپتی جاڤا)',
'tog-numberheadings'          => 'ژماره‌ی سه‌رتا خۆكارانه‌ دابنێ',
'tog-showtoolbar'             => 'شریتی ئامرازەکان نیشان بدە (JavaScript)',
'tog-editondblclick'          => 'ده‌ستكاریی په‌ڕه‌كه‌ بكه‌ به‌ دووكرته‌ لێكردنی (سكریپتی جاڤا)',
'tog-editsection'             => 'ده‌ستكاریی كردنی به‌ش چالاك بكه‌ له‌ڕێگه‌ی به‌سته‌ری [ده‌ستكاریی] یه‌وه‌',
'tog-editsectiononrightclick' => 'ده‌ستكاریی كردنی به‌ش چالاك بكه‌ به‌هۆی كرته‌ كردن له‌ ناونیشانی به‌شه‌كه‌ (سكریپتی جاڤا)',
'tog-showtoc'                 => 'ناوه‌ڕۆك نیشان بده‌ (بۆ ئه‌و په‌ڕانه‌ی كه‌ زیاتر له‌ ٣ به‌شه‌وتار یان هه‌یه‌)',
'tog-rememberpassword'        => 'زانیاریی چوونه‌ناوه‌وه‌م له‌م كۆمپیوته‌ره‌دا پاشه‌كه‌وت بكه‌',
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
'tog-watchlisthideminor'      => 'ورده‌ ده‌ستكارییه‌كان له‌ لیسته‌ی ته‌ماشاكردندا بشاره‌وه‌',
'tog-nolangconversion'        => 'وتووێژی هه‌مه‌چه‌شن ناچالاك بكه‌',
'tog-ccmeonemails'            => 'له‌به‌رگیراوه‌م بۆ بنێره‌ له‌و پۆستی ئه‌لیكترۆنییانه‌ی كه‌ بۆ به‌كارهێنه‌رانی دیكه‌ ناردوومه‌',
'tog-diffonly'                => 'په‌ڕه‌یه‌ك ئه‌م جیاوازییانه‌ی خواره‌وه‌ی له‌خۆ گرتبێت نیشانی مه‌ده‌‌',

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
'january'       => 'ژانڤیە',
'february'      => 'شوبات',
'march'         => 'مارت',
'april'         => 'نیسان',
'may_long'      => 'مایس',
'june'          => 'حوزه‌یران',
'july'          => 'ته‌مموز',
'august'        => 'ئاب',
'september'     => 'ئه‌یلول',
'october'       => 'تشرینی یه‌كه‌م',
'november'      => 'تشرینی دووه‌م',
'december'      => 'كانونی یه‌كه‌م',
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
'pagecategories'        => '{{PLURAL:$1|ھاوپۆل|ھاوپۆلەکان}}',
'category_header'       => 'پەڕە ھاوپۆلەکانی "$1"',
'subcategories'         => 'به‌شه‌هاوپۆله‌كان',
'category-media-header' => 'میدیا له‌ هاوپۆلی "$1" دا',
'category-empty'        => "''ئه‌م هاوپۆله‌ هه‌نووكه‌ هیچ له‌خۆ ناگرێت - به‌تاڵه‌''",

'mainpagetext'      => "<big>'''ویكیمیدیا به‌سه‌ركه‌وتووی دامه‌زرا.'''</big>",
'mainpagedocfooter' => 'بكه‌ [http://meta.wikimedia.org/wiki/Help:ناوه‌ڕۆكی چۆنێتی به‌كارهێنان] بۆ وه‌ده‌ست هێنانی زانیاریی له‌سه‌ر چۆنێتی كارگێڕی نه‌رمه‌كاڵای ویكی، سه‌ردانی.

== ده‌ست به‌ كاركردن ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings لیسته‌ی هه‌ڵبژاردنه‌كان و ڕێكخستنه‌كان]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce لیستی وه‌شانه‌كانی ویكیمیدیا]',

'about'          => 'سه‌باره‌ت',
'article'        => 'بابه‌ت',
'newwindow'      => '(لە پەڕەیەکی نوێ دەکرێتەوە)',
'cancel'         => 'ھەڵوەشاندن',
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
'navigation'     => 'نمایشكردن',
'and'            => '&#32;و',

# Metadata in edit box
'metadata_help' => 'دراوه‌ی مێتا:',

'errorpagetitle'    => 'هه‌ڵه‌',
'returnto'          => 'بگه‌ڕێوه‌ بۆ $1.',
'tagline'           => 'له‌ {{SITENAME}}',
'help'              => 'ڕێنمایی',
'search'            => 'گەڕان',
'searchbutton'      => 'بگەڕە',
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
'create'            => 'دروست کردن',
'editthispage'      => 'ده‌ستكاری ئه‌م په‌ڕه‌یه‌ بكه‌',
'create-this-page'  => 'ئەم پەڕە دروست بکە',
'delete'            => 'سڕینه‌وه‌',
'deletethispage'    => 'سڕینه‌وه‌ی ئه‌م په‌ڕه‌یه‌',
'undelete_short'    => 'به‌جێ بهێنه‌ {{PLURAL:$1|سڕاوه‌|$1 سڕاوه‌كان}}هێنانه‌وه‌ی',
'protect'           => 'پاراستن',
'protect_change'    => ' پاراستنەکە بگۆڕە',
'protectthispage'   => 'ئه‌م په‌ڕه‌یه‌ بپارێزه‌',
'unprotect'         => 'مه‌پارێزه‌',
'unprotectthispage' => 'ئه‌م په‌ڕه‌یه‌ مه‌پارێزه‌',
'newpage'           => 'په‌ڕه‌یه‌كی نوێ',
'talkpage'          => 'گفتوگۆ له‌سه‌ر ئه‌م په‌ڕه‌یه بكه‌',
'talkpagelinktext'  => 'وتووێژ',
'specialpage'       => 'په‌ڕه‌ی تایبه‌ت',
'personaltools'     => 'ئامرازی تایبه‌تی',
'postcomment'       => 'بەشی نوێ',
'articlepage'       => 'ناوه‌ڕۆكی بابه‌ت ببینه‌',
'talk'              => 'قسەوباس',
'views'             => 'بینین',
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
'jumpto'            => 'باز بده‌ بۆ:',
'jumptonavigation'  => 'ڕوانگە',
'jumptosearch'      => 'گه‌ڕان',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'دەربارەی {{SITENAME}}',
'aboutpage'            => 'Project:دەربارە',
'copyrightpage'        => '{{ns:project}}:مافەکانی لەبەرگرتنەوە',
'currentevents-url'    => 'Project:ڕووداوە بەردەوامەکان',
'disclaimers'          => 'بەرپرس‌نەبوونییەکان',
'edithelp'             => 'ڕێنمایی بۆ دەستکاری کردن',
'edithelppage'         => 'Help:دەستکاری کردن',
'faq'                  => 'پرسیار و وەڵام (FAQ)',
'faqpage'              => 'Project:پرسیار و وەڵام',
'helppage'             => 'Help:رێنمایییەکان',
'mainpage'             => 'ده‌ستپێک',
'mainpage-description' => 'ده‌ستپێک',
'portal'               => 'دەروازەی بەکارھێنەران',
'portal-url'           => 'Project: دەروازەی بەکارھێنەران',
'privacy'              => 'سیاسەتی پاراستنی داتاکان',

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
'editsectionhint'         => 'ده‌ستكاریی به‌شی: $1',
'toc'                     => 'ناوەڕۆک',
'showtoc'                 => 'نیشاندان',
'hidetoc'                 => 'شاردنەوە',
'thisisdeleted'           => '؟$1 نیشانی بده‌ یا بیگه‌ڕێنه‌ره‌وه‌',
'viewdeleted'             => '$1 نیشان بده‌؟',
'restorelink'             => '{{PLURAL:$1|گۆڕانکاریی سڕاو|$1 یەک گۆڕانکاریی سڕاو}}',
'red-link-title'          => '$1  (ھێشتا نەنووسراوە)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'په‌ڕه‌',
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
'nosuchactiontext'  => 'ئه‌و كرده‌یه‌ی كه‌ له‌ URL دا هاتووه‌ نه‌ناسراوه‌',
'nosuchspecialpage' => 'په‌ڕه‌ی تایبه‌تی له‌و شێوه‌یه‌ نییه‌',
'nospecialpagetext' => "<big>''په‌ڕه‌یكی تایبه‌ت ده‌خوازیت كه‌ بوونی نییه‌'''</big>

لیستی په‌ڕه‌ تایبه‌تییه‌كان له‌ [[Special:SpecialPages|لیسته‌ی په‌ڕه‌ تایبه‌ته‌كان]] ده‌توانرێت ببینرێت.",

# General errors
'error'              => 'هه‌ڵه‌',
'databaseerror'      => 'هه‌ڵه‌ له‌ بنكه‌دراوه‌دا هه‌یه‌',
'readonly_lag'       => 'بنكه‌دراوه‌كه‌ به‌شێوه‌ی خۆكار به‌ندكراوه‌، له‌كاتێكدا بنكه‌دراوه‌ی ڕاژه‌كاره‌كه‌ ڕۆڵی له‌خۆگرتن ده‌گێڕێت',
'internalerror'      => 'هه‌ڵه‌یه‌كی ناوخۆیی ڕویدا',
'internalerror_info' => 'هه‌ڵه‌ی ناوخۆیی: $1',
'filecopyerror'      => 'په‌ڕگه‌ی „$1“ ڕوونوس نابێت بۆ „$2“ .',
'filerenameerror'    => 'ناوی په‌ڕگه‌ی "$1" نه‌گۆڕدرا بۆ "$2".',
'filedeleteerror'    => 'بسڕدرێته‌وه‌"$1" نه‌توانرا په‌ڕگه‌ی',
'filenotfound'       => 'په‌ڕگه‌ی "$1" نه‌دۆزرایه‌وه‌',
'badtitle'           => 'ناونیشانێكی بێ كه‌ڵك',
'viewsource'         => 'سه‌رچاوه‌ ببینه‌',
'viewsourcefor'      => 'بۆ $1',

# Login and logout pages
'welcomecreation'         => '== خۆش هاتیت, $1! ==

هه‌ژماری تایبه‌تی تۆ سه‌ركه‌وتووانه‌ دروست كرا، له‌بیرت نه‌چێت گۆڕانكاری له {{SITENAME}} تایبه‌ت به‌خۆت دا بكه‌.',
'loginpagetitle'          => 'ناوی چوونه‌ ژووره‌وه‌',
'yourname'                => 'ناوی به‌كارهێنه‌وه‌',
'yourpassword'            => 'تێپه‌ڕه‌وشه‌',
'yourpasswordagain'       => 'تێپه‌ڕه‌وشه‌ دووباره‌',
'remembermypassword'      => 'زانیاریی چوونه‌ ژووره‌وه‌م له‌سه‌ر ئه‌م كۆمپیوته‌ره‌ پاشه‌كه‌وت بكه‌',
'yourdomainname'          => 'ناوی دۆمه‌ینی خۆت',
'login'                   => 'تێکەوە (login)',
'nav-login-createaccount' => 'دروست کردنی ھەژمار/چوونە ژورەوە',
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
'mailmypassword'          => 'تێپەڕوشەیەکی نوێ بنێرە بۆ E-mailەکەم',
'passwordremindertitle'   => 'تێپەڕوشەیەکی نوێی کاتی بۆ  {{SITENAME}}',
'noemail'                 => 'ھیچ ئەدرەسێکی e-mail تۆمار نەکراوە بۆ بەکارھێنەر  "$1" .',
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
'summary'                => 'پوختە:',
'subject'                => 'بابه‌ت / سه‌روتار:',
'minoredit'              => 'ئەم گۆڕانکاری‌یە بچووکە',
'watchthis'              => 'چاودێڕی ئه‌م په‌ڕه‌یه‌ بكه‌',
'savearticle'            => 'په‌ڕه‌كه‌ پاشه‌كه‌وت بكه‌',
'preview'                => 'پێشبینین',
'showpreview'            => 'پێشبینینی پەڕە',
'showlivepreview'        => 'پێشبینینی ڕاسته‌وخۆ',
'showdiff'               => 'گۆڕانكارییه‌كان نیشانبده‌',
'anoneditwarning'        => "'''وشیار بە:''' نەچوویتەتە ژوورەوە.
ئەدرەسی ئەکەت لە مێژووی ئەم پەڕە دا تۆمار دەکرێ.",
'missingsummary'         => "'''وە بیر خستنەوە:''' پوختەیەکت نەنووسیوە بۆ چۆنیەتی گۆڕانکارییەکەت.
ئەگەر جارێکی تر پاشکەوت کردن لێبدەی، بێ پوختە تۆمار دەکرێ.",
'missingcommenttext'     => 'تکایە لە خوارەوە شرۆڤەیەک بنووسە.',
'newarticletext'         => "بە دوای بەستەری پەڕەیەک کەوتووی کە ھێشتا دروست نەکراوە. <br /> بۆ دروست کردنی پەڕەکە، لە چوارچێوەکەی خوارەوە دەست کە بە تایپ کردن. (بۆ زانیاری زورتر[[یارمەتی|{{MediaWiki:Helppage}}]] ببینە). <br />  ئەگەر بە ھەڵەوە ھاتویتە ئەگرە، لە سەر دوگمەی '''back'''ی وێبگەڕەکەت کلیک کە.",
'editing'                => 'گۆڕاندنی: "$1"',
'editingsection'         => 'گۆڕاندنی: $1 (بەش)',
'editingcomment'         => 'گۆڕاندنی $1 (بەشی  نوێ)',
'copyrightwarning'       => "تکایە ئاگادار بن کە ھەموو بەشدارییەک بۆ  {{SITENAME}} وا فەرز ئەکرێت کە لە ژێر «$2» بڵاو دەبێتەوە (بۆ ئاگاداری زۆرتر $1 سەیر کە). ئەگەر ناتەوێ نوسراوەکەت بێ‌ڕەحمانە دەستکاری بکرێت و  بە دڵخواز دیسان بڵاو ببێتەوە، لەگرە پێشکەشی مەکە. ھەروەھا بەڵین ئەدەی کە خۆت ئەمەت نووسیوە، یان لە سەرچاوەیەکی بە دەسەڵاتی گشتی ''(public domain)'' یان سەرچاوەیەکی ھاوتا لەبەرت‌گرتوەتەو.
<strong>«بەرھەمێک کە مافی لەبەرگرتنەوەی پارێزراوە، بێ ئیجازە  بڵاو مەکەرەوە.»</strong>",
'templatesused'          => 'ئەو قاڵبانە کە لەم پەڕەیەدا بە کارھێنراون:',
'templatesusedpreview'   => 'ئەو قاڵبانە کە لەم پێشبینینەدا بە کارھێنراون:',
'templatesusedsection'   => 'ئەو قاڵبانە کە لەم بەشەدا بە کارھێنراون:',
'template-protected'     => '(پارێزراو)',
'template-semiprotected' => '(نیوەپارێزراو)',

# History pages
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

# Diffs
'difference'              => '(جیاوازی نێوان پیاچوونەوەکان)',
'lineno'                  => 'ھێڵی  $1:',
'compareselectedversions' => 'ھەڵسەنگاندنی وەشانە ھەڵبژاردراوەکان',

# Search results
'noexactmatch'             => "'''پەڕەیەک بە ناوی  \"\$1\"ەوە نیە.'''
دەتوانی ئەم پەڕە [[:\$1|دروست بکەیت]].",
'search-interwiki-caption' => 'پرۆژە خوشکەکان',
'powersearch'              => 'بە ھێز بگەڕە',

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

# Recent changes
'recentchanges'   => 'دوایین گۆڕانکارییەکان',
'rcnote'          => "لە خوارەوەدا {{PLURAL:$1|'''۱''' گۆڕانکاری |دوایین '''$1''' گۆڕانکارییەکان}} لە دوایین {{PLURAL:$2|ڕۆژ|'''$2''' ڕۆژەوە}} ، تا $5، $4 دەبینن.",
'rclistfrom'      => 'گۆڕانکارییە نوێکان کە لە $1ەوە دەست پێدەکەن نیشان بدە.',
'rcshowhideminor' => 'گۆڕانکارییە بچووکەکان $1',
'rcshowhidebots'  => 'ڕۆبۆتەکان $1',
'rcshowhideliu'   => 'بەکارھێنەرە لە ژوورەکان $1',
'rcshowhideanons' => 'بەکارھێنەرە نەناسراوەکان $1',
'rcshowhidepatr'  => 'گۆرانکارییە کۆنترۆڵکراوەکان $1',
'rcshowhidemine'  => 'گۆڕانکارییەکانی من $1',
'rclinks'         => 'دوایین $1 گۆڕانکارییەکانی دوایین $2 ڕۆژی <br />$3',
'diff'            => 'جیاوازی',
'hist'            => 'مێژوو',
'hide'            => 'بشارەوە',
'show'            => 'نیشان بدە',
'minoreditletter' => 'ب',
'newpageletter'   => 'ن',
'boteditletter'   => 'ڕ',

# Recent changes linked
'recentchangeslinked'         => 'گۆڕانکارییە پەیوەندی‌دارەکان',
'recentchangeslinked-summary' => "Ev rûpela taybetî guherandinên dawî ji rûpelên lînkkirî nîşandide.
ئەو پەڕانە کە لە [[Special:Watchlist|لیستی چاودێڕییەکانت]]دان '''ئەستوورن'''",
'recentchangeslinked-page'    => 'ناوی پەڕە:',

# Upload
'upload'             => 'وێنەیەک بار بکە',
'uploadbtn'          => 'پەڕگە بار بکە',
'reupload'           => 'دیسان بار بکە',
'sourcefilename'     => 'ناوی پەڕگەی سەرچاوە:',
'upload-maxfilesize' => 'ئەو پەری قەبارەی فایل: $1',

# Special:ListFiles
'imgfile'        => 'پەڕگە',
'listfiles'      => 'لیستی پەرگەکان',
'listfiles_date' => 'ڕێکەوت',
'listfiles_name' => 'ناو',
'listfiles_user' => 'بەکارھێنەر',

# File description page
'filehist'   => 'مێژووی پەڕگە',
'imagelinks' => 'بەستەرەکانی پەڕگە',

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
'shortpages'              => 'پەڕە کورتەکان',
'longpages'               => 'پەڕە دڕێژەکان',
'newpages'                => 'پەڕە نوێکان',
'ancientpages'            => 'کۆنترین پەڕەکان',
'move'                    => 'ناوی ئەم پەڕە بگۆڕە',
'pager-newer-n'           => '{{PLURAL:$1|نوێتر 1|نوێتر $1}}',
'pager-older-n'           => '{{PLURAL:$1|کۆنتر 1|کۆنتر $1}}',

# Book sources
'booksources-go' => 'بڕۆ',

# Special:Log
'log' => 'ڕەشنووسەکان',

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

# Special:Log/newusers
'newuserlog-create-entry' => 'بەکارھێنەری نوێ',

# Special:ListGroupRights
'listgrouprights'         => 'مافەکانی گرووپە بەکارھێنەرییەکان',
'listgrouprights-members' => '(لیستی ئەندامەکان)',

# E-mail user
'emailuser'       => 'بۆ ئەم بەکارھێنەرە E-Mail بنێرە',
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
'deletepage'      => 'پەڕە بسڕەوەو',
'confirm'         => 'پشتدار بکەرەوە',
'excontent'       => "ناوەڕۆک ئەمە بو: '$1'",
'excontentauthor' => "ناوەڕۆک ئەمە بو: '$1'(و تەنھا بەشداریکەر  '[[Special:Contributions/$2|$2]]' بوو)",
'exbeforeblank'   => "ناوەڕۆک بەر لە بەتاڵ کردنەوە ئەمە بوو: '$1'",
'exblank'         => 'پەڕە خاڵی بوو',
'delete-confirm'  => 'سڕینەوەی "$1"',
'delete-legend'   => 'سڕینەوە',
'historywarning'  => 'ئاگاداری: ئەم پەڕە کە ئەتەوێ بیسڕیتەوە مێژووی ھەیە',

# Rollback
'rollbacklink' => 'گەڕاندنەوە',

# Namespace form on various pages
'namespace'      => 'بۆشاییی ناو',
'invert'         => 'ھەڵبژاردەکان پێچەوانە بکە',
'blanknamespace' => '(سەرەکی)',

# Contributions
'contributions'       => 'بەشدارییەکانی بەکارھێنەر',
'contributions-title' => 'بەشدارییەکانی بەکارھێنەر $1',
'mycontris'           => 'بەشدارییەکانی من',
'contribsub2'         => 'بۆ$1 ($2)',
'month'               => 'لە مانگی (و پێشترەوە):',
'year'                => 'لە ساڵی (و پێشترەوە):',

'sp-contributions-newbies'     => 'تەنھا بەشدارییەکانی بەکارھێنەرە تازەکان نیشان بدە',
'sp-contributions-newbies-sub' => 'لە بەکارھێنەرە تازەکانەوە',
'sp-contributions-search'      => 'گەڕین بۆ بەشدارییەکان',
'sp-contributions-username'    => 'ئەدرەسی IP یان بەکارھێنەر:',
'sp-contributions-submit'      => 'بگەڕە',

# What links here
'whatlinkshere'       => 'بەسراوەکان بە ئێرەوە',
'whatlinkshere-links' => '← بەستەرەکان',

# Block/unblock
'ipboptions'     => '2 کاتژمێر:2 hours,1 ڕۆژ:1 day,3 ڕۆژ:3 days,1 ھەفتە:1 week,2 ھەفتە:2 weeks,1 مانگ:1 month,3 مانگ:3 months,6 مانگ:6 months,1 ساڵ:1 year,بێ سنوور:infinite', # display1:time1,display2:time2,...
'ipbotheroption' => 'دیکە',
'blocklink'      => 'بەربەستن',
'contribslink'   => 'بەشداری',

# Move page
'movedto'            => 'بوو بە',
'movetalk'           => 'پەڕەی گوفتوگۆکەشی بگۆزەرەوە',
'move-subpages'      => 'ھەموو ژێرپەڕەکانیشی (بە ئەندازەی $1) بگۆزەرەوە، ئەگەر بیبێت',
'move-talk-subpages' => 'ھەموو ژێرپەڕەکانی (بە ئەندارەی $1) پەڕەی گوفتوگۆکەشی بگۆزەرەوە، ئەگەر بیبێت',
'movereason'         => 'بە ھۆی:',

# Thumbnails
'thumbnail-more' => 'گەورە کردنەوە',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'پەڕە شەخسییەکەت',
'tooltip-n-mainpage'              => 'دیتنی دەستپێک',
'tooltip-n-portal'                => 'زانیاری لە سەر {{SITENAME}}، ئێوە چی ئەتوانن بکەن، ھەر شتێک لە کوێ پەیدا دەبێ',
'tooltip-n-recentchanges'         => 'لیستی دوایین گۆڕانکارییەکان لەم ویکییەدا',
'tooltip-n-randompage'            => 'پەڕەیەک بە ھەڵکەوت نیشان بدە',
'tooltip-n-help'                  => 'شوێنێک بۆ پرسیارەکان.',
'tooltip-t-whatlinkshere'         => 'لیستی ھەموو پەڕەیەک کە بەگرەوە گرێ دراون.',
'tooltip-t-recentchangeslinked'   => 'دوایین گۆڕانکارییەکان لەو پەڕانە کە بەگرەوە گرێ دراون',
'tooltip-feed-rss'                => 'RSS بۆ ئەم گۆڕانکارییەکانی ئەم پەڕە',
'tooltip-feed-atom'               => 'Atom feed بۆ ئەم گۆڕانکارییەکانی ئەم پەڕە',
'tooltip-t-contributions'         => 'بینینی بەشدارییەکانی ئەم بەکارھێنەرە',
'tooltip-t-emailuser'             => 'ئیمەیلێک بنێرە بۆ ئەم بەکارھێنەرە',
'tooltip-t-upload'                => 'پەڕگەیەک (فایل) بار بکە',
'tooltip-t-specialpages'          => 'لیستی ھەموو پەڕە تایبەتەکان',
'tooltip-t-print'                 => 'وەشانی ئامادە بۆ چاپی ئەم پەڕە',
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

# Media information
'thumbsize'      => 'قەبارەی Thumbnail:',
'show-big-image' => 'گەورە کردنەوە',

# Special:NewFiles
'newimages' => 'پێشانگای پەڕگە نوێکان',

# Variants for Kurdish language
'variantname-ku-arab' => 'ئەلفوبێی عەرەبی',
'variantname-ku-latn' => 'ئەلفوبێی لاتینی',

# Metadata
'metadata-help'   => 'ئەم پەڕگە زانیاری زێدەی ھەیە، کە لەوە دەچێت کامێرا یان ھێماگر (scanner) خستبێتیە سەری. ئەگەر پەڕگەکە لە حاڵەتی سەرەتاییەکەیەوە دەستکاری کرابێ، شایەد بڕێ لە بڕگەکان بە تەواوی زانیارەکانی وێنە گۆڕدراوەکە نیشان نەدەن.',
'metadata-fields' => 'ئەو کێڵگە EXIFانە لەم پەیامە بە ڕیز کراون، کاتێک خشتەی metadata کۆ کراوەش بێ ھەر نیشان ئەدرێت. کێڵگەکانی تر تا خشتەکە باز نەکرێ، شاراوەن.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ھەموو',
'imagelistall'     => 'ھەموو',
'watchlistall2'    => 'ھەموو',
'namespacesall'    => 'ھەموو',
'monthsall'        => 'ھەموویان',

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
