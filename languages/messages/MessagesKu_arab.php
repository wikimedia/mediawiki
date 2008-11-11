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

$skinNames = array(
'standard'    => 'كلاسیك',
'nostalgia'   => 'قاوه‌یی',
'cologneblue' => 'شین',
'monobook'    => 'مۆنۆ',
'myskin'      => 'پێستی خۆم',
'chick'       => 'جوجه‌',
'simple'      => 'ساده‌'
);

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
'tog-showtoolbar'             => 'تووڵامرازی ده‌ستكاری نیشان بده‌ -سكریپتی جاڤا',
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
'january'       => 'كانونی دووه‌م',
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
'pagecategories'        => '$1 هاوپۆله‌كان',
'category_header'       => 'په‌ڕه‌ی هاوپۆلی "$1" de',
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
'postcomment'       => 'لێدوان بنێره‌',
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
'copyrightpage'        => '{{ns:project}}:مافەکانی لەبەرگرتنەوە',
'currentevents-url'    => 'Project:ڕووداوە بەردەوامەکان',
'edithelp'             => 'ڕێنمایی بۆ دەستکاری کردن',
'edithelppage'         => 'Help:دەستکاری کردن',
'faq'                  => 'پرسیاری هه‌میشه‌ دووباره‌(پهد(',
'mainpage'             => 'ده‌ستپێک',
'mainpage-description' => 'ده‌ستپێک',
'portal'               => 'دەروازەی بەکارھێنەران',
'portal-url'           => 'Project: دەروازەی بەکارھێنەران',
'privacy'              => 'سیاسەتی پاراستنی داتاکان',

'versionrequired'     => 'پێویستیت به‌ وه‌شانی $1 ـی‌ ویكیمیدیایه‌',
'versionrequiredtext' => 'پێویستیت به‌ وه‌شانی $1 ـێ ویكیمیدیا هه‌یه‌ بۆ به‌كاربردنی ئه‌م په‌ڕه‌یه‌
. ته‌ماشای [[Special:Version|وه‌شانی]] بكه‌.',

'ok'                      => 'باشه‌',
'retrievedfrom'           => 'له‌ لایه‌ن "$1" گه‌ڕاوه‌ته‌وه‌.',
'newmessageslink'         => 'په‌یامێكی نوێ',
'newmessagesdifflink'     => 'دوا گۆڕانكارییه‌كان',
'youhavenewmessagesmulti' => 'په‌یامێكی نوێت هه‌یه‌ له‌ $1.',
'editsection'             => 'ده‌ستكاری',
'editsectionhint'         => 'ده‌ستكاریی به‌شی: $1',
'toc'                     => 'ناوەڕۆک',
'showtoc'                 => 'نیشاندان',
'hidetoc'                 => 'شاردنەوە',
'thisisdeleted'           => '؟$1 نیشانی بده‌ یا بیگه‌ڕێنه‌ره‌وه‌',
'viewdeleted'             => '$1 نیشان بده‌؟',
'restorelink'             => '{{PLURAL:$1|ده‌ستكاریی سڕدراوه‌كه‌ بكه‌|$1 ده‌ستكارییان بكه‌}}',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'وتار',
'nstab-user'      => 'پەڕەی بەکارھێنەر',
'nstab-media'     => 'میدیا',
'nstab-special'   => 'تایبه‌ت',
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
'nav-login-createaccount' => 'دروست کردنی ھەژمار/چوونە ژورەوە',
'userlogin'               => 'دروست کردنی ھەژمار/چوونە ژورەوە',
'logout'                  => 'ده‌رچوون',
'userlogout'              => 'دەرچوون',
'notloggedin'             => 'له‌ ژووره‌وه‌ نیت',
'nologin'                 => 'ناتوانیت بچییه‌ ژووره‌وه‌؟ $1.',
'nologinlink'             => 'ببه‌ به‌ ئه‌ندام',
'createaccount'           => 'هه‌ژماری نوێ',
'gotaccount'              => 'خاوه‌نی هه‌ژماری خۆتی؟ $1.',
'gotaccountlink'          => 'چوونه‌ ژووره‌وه‌',
'createaccountmail'       => 'به‌ پۆستی ئه‌لیكترۆنی',
'badretype'               => 'وشه‌ نهێنییه‌كان له‌یه‌ك ناچن',
'userexists'              => 'ئه‌و ناوه‌ی تۆ داوته‌ پێشتر كه‌سێكی دیكه‌ به‌كاری بردووه‌',
'youremail'               => 'پۆستی ئه‌لیكترۆنی خۆت*',
'username'                => 'ناوی به‌كارهێنه‌ر:',
'uid'                     => 'ژماره‌ی خۆت ID:',
'yourrealname'            => 'ناوی ڕاستی خۆت*',
'yourlanguage'            => 'زمان',
'yourvariant'             => 'ڕه‌گه‌ز',
'yournick'                => 'نازناو',
'badsig'                  => 'ئیمزاكه‌ هه‌ڵه‌یه‌، ته‌ماشای كۆدی HTML بكه‌‌',
'emailauthenticated'      => 'پۆستی ئه‌لیكترۆنی ناسراو: $1.',
'emailconfirmlink'        => 'پۆستی ئه‌لیكترۆنی خۆت بنووسه‌',
'accountcreated'          => 'هه‌ژماره‌كه‌ سه‌ركه‌وتووانه‌ دروست كرا',
'loginlanguagelabel'      => 'زمان: $1',

# Password reset dialog
'resetpass_text'   => '<!-- تێپه‌ڕه‌وشه‌ی هه‌ژماره‌كه‌ سفر بكه‌ره‌وه‌ -->',
'resetpass_header' => 'تێپه‌ڕه‌وشه‌ سفر بكه‌ره‌وه‌',

# Edit page toolbar
'bold_sample'    => 'ده‌قی ئه‌ستوور',
'bold_tip'       => 'ده‌قی ئه‌ستوور',
'italic_sample'  => 'دەقی لار',
'italic_tip'     => 'دەقی لار',
'link_sample'    => 'نێوی بەستەر',
'link_tip'       => 'به‌سته‌رێكی ناوخۆیی',
'extlink_sample' => 'http://www.example.com سەردێڕی بەستەر',
'extlink_tip'    => 'به‌سته‌ری ده‌ره‌كی ( ده‌ست پێ ده‌كاتhttp:// سه‌ره‌تاكه‌ی به‌ )',
'headline_tip'   => 'سەردێڕی ئاست ۲',
'math_sample'    => 'لەگرە فۆرموول بخەسەر',
'math_tip'       => ' فۆرموولی بیرکاریی (LaTeX)',
'nowiki_sample'  => 'لەگەرە دەقی نەڕازراو تێ‌بخە',
'nowiki_tip'     => 'لەبەرچاو نەگرتنی دارشتنەکانی ویکی',
'image_tip'      => 'وێنەی نێو دەق',
'media_tip'      => 'لینکی پەڕگە',
'sig_tip'        => 'ئیمزاكه‌ت به‌ مۆری ڕێكه‌وته‌وه‌',
'hr_tip'         => 'هێڵی ئاسۆیی (ده‌گمه‌ن به‌كاری بهێنه‌)',

# Edit pages
'summary'                => 'پوختە',
'subject'                => 'بابه‌ت / سه‌روتار',
'minoredit'              => 'ئەم گۆڕانکاری‌یە بچووکە',
'watchthis'              => 'چاودێڕی ئه‌م په‌ڕه‌یه‌ بكه‌',
'savearticle'            => 'په‌ڕه‌كه‌ پاشه‌كه‌وت بكه‌',
'preview'                => 'پێشبینین',
'showpreview'            => 'پێشبینینی پەڕە',
'showlivepreview'        => 'پێشبینینی ڕاسته‌وخۆ',
'showdiff'               => 'گۆڕانكارییه‌كان نیشانبده‌',
'newarticletext'         => "بە دوای بەستەری پەڕەیەک کەوتووی کە ھێشتا دروست نەکراوە. <br /> بۆ دروست کردنی پەڕەکە، لە چوارچێوەکەی خوارەوە دەست کە بە تایپ کردن. (بۆ زانیاری زورتر[[یارمەتی|{{MediaWiki:Helppage}}]] ببینە). <br />  ئەگەر بە ھەڵەوە ھاتویتە ئەگرە، لە سەر دوگمەی '''back'''ی وێبگەڕەکەت کلیک کە.",
'copyrightwarning'       => "تکایە ئاگادار بن کە ھەموو بەشدارییەک بۆ  {{SITENAME}} وا فەرز ئەکرێت کە لە ژێر «$2» بڵاو دەبنەوە(بۆ ئاگاداری زۆرتر $1 سەیر کە). ئەگەر ناتەوێ  نوسراوەکەت بێ‌ڕەحمانە دەستکاری بکرێت و  بە دڵخواز دیسان بڵاو ببێتەوە، لەگرە پێشکەشی مەکە. <br />\\n ھەروەھا بەڵین ئەدەی کە خۆت ئەمەت نووسیوە، یان لە سەرچاوەیەکی بە دەسەڵاتی گشتی ''(public domain)'' یان سەرچاوەیەکی ھاوتا لەبەرت‌گرتوەتەو.
<strong>«بەرھەمێک کە مافی لەبەرگرتنەوەی پارێزراوە، بێ ئیجازە  بڵاو مەکەرەوە.»</strong>",
'template-semiprotected' => '(نیوەپارێزراو)',

# History pages
'revisionasof' => 'وەک بینینەوەی $1',
'cur'          => 'ئێستا',
'last'         => 'پێشوو',
'histlegend'   => 'وەشانەکان بۆ ھەڵسەنگاندن دیاری بکە و ئەم دوگمەی خوارەوە لێبدە. <br />
ڕێنمایی:
(ئێستا) = جیاوازی لەگەڵ وەشانی ئێستا،
(پێشوو) =جیاوازی لەگەڵ وەشانی پێشوو،
ب = گۆڕانکاریی بچووک',
'histfirst'    => 'کۆنترین',
'histlast'     => 'نوێترین',

# Diffs
'difference'              => '(جیاوازی نێوان پیاچوونەوەکان)',
'lineno'                  => 'ھێڵی  $1:',
'compareselectedversions' => 'ھەڵسەنگاندنی وەشانە ھەڵبژاردراوەکان',

# Preferences page
'mypreferences' => 'ھەڵبژاردەکانی من',
'skin-preview'  => 'پێش بینین',

# Recent changes
'rcnote'          => "لە خوارەوەدا {{PLURAL:$1|'''۱''' گۆڕانکاری |دوایین '''$1''' گۆڕانکارییەکان}} لە دوایین {{PLURAL:$2|ڕۆژ|'''$2''' ڕۆژەوە}} ، تا $5، $4 دەبینن.",
'diff'            => 'جیاوازی',
'hist'            => 'مێژوو',
'hide'            => 'شاردنەوە',
'minoreditletter' => 'ور',
'newpageletter'   => 'ن',
'boteditletter'   => 'ڕ',

# Recent changes linked
'recentchangeslinked' => 'گۆڕانکارییە پەیوەندی‌دارەکان',

# Upload
'upload' => 'وێنەیەک بار بکە',

# Image description page
'filehist'   => 'مێژووی پەڕگە',
'imagelinks' => 'بەستەرەکان',

# Random page
'randompage' => 'پەڕەیەک بە ھەرەمەکی',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بایت|بایت}}',
'move'   => 'ناوی ئەم پەڕە بگۆڕە',

# Special:Categories
'categories' => 'هاوپۆله‌كان',

# Watchlist
'mywatchlist' => 'لیستی چاودێڕی‌یەکانم',
'watch'       => 'چاودێڕی بکە',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'چاودێڕی...',

# Rollback
'rollbacklink' => 'گەڕاندنەوە',

# Namespace form on various pages
'blanknamespace' => '(سەرەکی)',

# Contributions
'mycontris' => 'بەشدارییەکانی من',

# What links here
'whatlinkshere'       => 'بەسراوەکان بە ئێرەوە',
'whatlinkshere-links' => '← بەستەرەکان',

# Block/unblock
'blocklink' => 'بەربەستن',

# Thumbnails
'thumbnail-more' => 'گەورە کردنەوە',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'په‌ڕه‌ی تایبه‌تی خۆم',
'tooltip-ca-nstab-user'           => 'پەڕەی بەکارھێنەر تەماشا بکە',
'tooltip-compareselectedversions' => 'جیاوازییەکانی دوو وەشانە دیاریکراوەی ئەم پەڕە ببینە.',

# Metadata
'metadata-help'   => 'ئەم پەڕگە زانیاری زێدەی ھەیە، کە لەوە دەچێت کامێرا یان ھێماگر (scanner) خستبێتیە سەری. ئەگەر پەڕگەکە لە حاڵەتی سەرەتاییەکەیەوە دەستکاری کرابێ، شایەد بڕێ لە بڕگەکان بە تەواوی زانیارەکانی وێنە گۆڕدراوەکە نیشان نەدەن.',
'metadata-fields' => 'ئەو کێڵگە EXIFانە لەم پەیامە بە ڕیز کراون، کاتێک خشتەی metadata کۆ کراوەش بێ ھەر نیشان ئەدرێت. کێڵگەکانی تر تا خشتەکە باز نەکرێ، شاراوەن.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator'     => '،&#32;',

# Live preview
'livepreview-loading' => 'له‌باركردنایه‌ ...',
'livepreview-ready'   => 'ئاماده‌یه‌',

# Watchlist editor
'watchlistedit-noitems' => 'لیستی ته‌ماشاكردنی خۆت به‌تاڵه‌',

# Special:SpecialPages
'specialpages' => 'لاپەڕە تایبەتەکان',

);
