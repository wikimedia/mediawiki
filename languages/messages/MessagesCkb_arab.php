<?php
/** Sorani (Arabic script) (‫کوردی (عەرەبی)‬)
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
 * @author Marmzok
 * @author رزگار
 */

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
'tog-underline'               => 'ھێڵ ھێنان بەژێر بەستەرەکان:',
'tog-highlightbroken'         => 'بەستەرە شکاوەکان <a href="" class="new">بەم جۆرە</a> بڕازێنەوە (یان: بەەم جۆرە<a href="" class="internal">؟</a>)',
'tog-justify'                 => 'پەرەگرافەکان پڕاوپر نیشان بدە',
'tog-hideminor'               => 'دەستکارییە بچوکەکان بشارەوە لە دوا گۆڕانکارییەکاندا',
'tog-extendwatchlist'         => 'لیستی چاودێڕی درێژکەرەوە بۆ نیشان دانی ھەموو گۆڕانکارییەکان، نەک تەنھا دوایینەکان.',
'tog-usenewrc'                => 'دوا گۆڕانکارییە پەرە پێدراوەکان بەکار ببە (پێویستی بە جاڤاسکریپتە)',
'tog-numberheadings'          => 'ژمارەکردنی خۆکاری سەردێڕەکان',
'tog-showtoolbar'             => 'شریتی ئامرازەکانی دەستکاری نیشان بدە (JavaScript پێویستە)',
'tog-editondblclick'          => 'دەستکاریی پەڕە بە دووکلیک لەسەر دەق (JavaScript پێویستە)',
'tog-editsection'             => 'ڕێگە بدە بۆ دەستکاری کردنی بەشەکان لە ڕێگه‌ی به‌سته‌رەکانی [دەستکاری]',
'tog-editsectiononrightclick' => 'ڕێگە بدە بۆ دەستکاری کردنی بەشەکان لە ڕێگەی کلیکی ڕاست کردن لەسەر سەردێڕی بەشەکان (JavaScript پێویستە)',
'tog-showtoc'                 => 'پێرستی ناوەڕۆۆک نیشان بدە (بۆ ئەو پەڕانە کە زیاتر لە ٣ سەردێڕیان تێدایە)',
'tog-rememberpassword'        => 'چوونەژوورەوەم بەبیربهێنەوە لەسەر ئەم کۆمپیوتەرە',
'tog-editwidth'               => 'چوراچێوە دەستکاری پان کەرەوە تا سەرانسەری شاشەکە پڕ کاتەوە',
'tog-watchcreations'          => 'ئەو پەڕانە کە من دروستم کردوون زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchdefault'            => 'ئەو پەڕانە کە من دەستکاریم کردوون زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchmoves'              => 'ئەو پەڕانە کە من گواستومنەتەوە زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchdeletion'           => 'ئەو پەڕانە کە‌ من سڕیومنەتەوە زیاد بکە‌ بە لیستی چاودێڕییەکەم',
'tog-minordefault'            => 'ھەموو دەستکارییەکان بە ورد نیشان بکە لە حاڵەتی دیفاڵت',
'tog-previewontop'            => 'پێشبینین بەرلە چوارچێوەی دەستکاری نیشان بدە‌',
'tog-previewonfirst'          => 'لە یەکەم دەستکاری دا پێشبینین نیشان بدە',
'tog-nocache'                 => 'کەش کردنی (cach) پەڕەکان لە کار بخە',
'tog-enotifwatchlistpages'    => 'ئەگەر پەڕەیەکی لە لیستی چاودێڕییەکانم گۆڕدرا ئیمەیلم بۆ بنێرە',
'tog-enotifusertalkpages'     => 'ئەگەر پەڕەی وتووێژەکەم گۆڕدرا ئیمەیلم بۆ بنێرە',
'tog-enotifminoredits'        => 'بۆ گۆڕانکارییە بچووکەکانی پەڕەکانیش ئیمەیلم بۆ بنێرە',
'tog-enotifrevealaddr'        => 'ئەدرەسی ئیمەیلەکەم لە ئیمەیلە ئاگاداریدەرەکان دا نیشان بدە',
'tog-shownumberswatching'     => 'ژمارەی بەکارھێنەرە چاودێڕەکان نیشان بدە',
'tog-fancysig'                => 'وەک دەقی ویکی ئیمزا بەرچاو خە (بێ بەستەری خۆکار بۆ پەڕەی بەکارھێنەر)',
'tog-externaleditor'          => 'دەستکاریکەری دەرەکی بە کار بێنە لە حاڵەتی دیفاڵتدا (تەنھا بۆ شارەزایان، تەنزیماتی تایبەتی پێویستە لە سەر کۆمپیوتەرەکەت)',
'tog-showjumplinks'           => 'ڕێگە بدە بۆ بەستەرەکانی «{{int:jumpto}}»',
'tog-uselivepreview'          => 'لە پێشبینینی زیندوو کەڵک وەرگرە (جاڤاسکریپت) (تاقی‌‌کاری‌)',
'tog-forceeditsummary'        => 'ئەگەر پوختەی دەستکاریم نەنووسی پێم بڵێ',
'tog-watchlisthideown'        => 'دەستکارییەکانم بشارەوە لە لیستی چاودێری',
'tog-watchlisthidebots'       => 'دەستکارییەکانی بات بشارەوە لە لیستی چاودێری',
'tog-watchlisthideminor'      => 'دەستکارییە بچووکەکان لە لیستی چاودێڕی دا بشارەوە',
'tog-watchlisthideliu'        => 'دەستکارییەکانی ئەو بەکارهێنەرانەی لە ژوورەوەن بشارەوە لە لیستی چاودێری',
'tog-watchlisthideanons'      => 'دەستکارییەکانی بەکارهێنەرانی نەناسراو بشارەوە لە لیستی چاودێری',
'tog-nolangconversion'        => 'وتووێژی هه‌مه‌چه‌شن ناچالاك بكه‌',
'tog-ccmeonemails'            => 'کۆپییەکانی ئەو ئیمەیلانە کە بۆ بەکارھێنەرانی ترم ناردووە بۆ خۆشم بنێرە',
'tog-diffonly'                => 'ناوەڕۆکی پەڕە لەژێر جیاوازییەکان نیشان مەدە',
'tog-showhiddencats'          => 'ھاوپۆلە شاراوەکان نیشان بدە',
'tog-norollbackdiff'          => 'لە دوای گەڕاندنەوە جیاوازی نیشان مەدە',

'underline-always'  => 'ھەمیشە',
'underline-never'   => 'ھەرگیز',
'underline-default' => 'دیفاڵتی وێبگەڕەکە',

# Dates
'sunday'        => 'یەکشەممە',
'monday'        => 'دووشەممە',
'tuesday'       => 'سێشەممە',
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
'april'         => 'نیسان',
'may_long'      => 'ئایار',
'june'          => 'حوزەیران',
'july'          => 'تەمموز',
'august'        => 'ئاب',
'september'     => 'ئەیلوول',
'october'       => 'تشرینی یەکەم',
'november'      => 'تشرینی دووەم',
'december'      => 'کانونی یەکەم',
'january-gen'   => 'كانونی دووه‌می',
'february-gen'  => 'شوباتی',
'march-gen'     => 'مارتی',
'april-gen'     => 'نیسانی',
'may-gen'       => 'مایسی',
'june-gen'      => 'حوزه‌یرانی',
'july-gen'      => 'ته‌مموزی',
'august-gen'    => 'ئابی',
'september-gen' => 'ئه‌لیلولی',
'october-gen'   => 'تشرینی یه‌كه‌می',
'november-gen'  => 'تشرینی دووه‌می',
'december-gen'  => 'كانونی یه‌كه‌می',
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
'subcategories'            => 'ژێرھاوپۆلەکان',
'category-media-header'    => 'میدیا له‌ هاوپۆلی "$1" دا',
'category-empty'           => "''ئەم ھاوپۆلە ھەنووکە ھیچ پەڕە یان پەڕگەیەک لە خۆ ناگرێت.‌''",
'hidden-categories'        => '{{PLURAL:$1|ھاوپۆلی شاراوە|ھاوپۆلی شاراوە}}',
'hidden-category-category' => 'هاوپۆلە شاردراوەکان',
'category-subcat-count'    => '{{PLURAL:$2|ئەم ھاوپۆلە تەنھا ژێرھاوپۆلی خوارەوەی تێدایە.| ئەم ھاوپۆلییە ئەم 
{{PLURAL:$1|ژێرھاوپۆلەی|$1 ژێرھاوپۆلانەی}} , تێدایە لە کۆی $2 دا.}}',
'category-article-count'   => '{{PLURAL:$2|ئەم ھاوپۆلییە تەنھا پەڕەی خوارەوەی تێدایە.|{{PLURAL:$1| پەڕەی خوارەوە لەم ھاوپۆلییەدایە|$1 پەڕەی خوارەوە لەم ھاوپۆلییەدان}}، لە کۆی $2 پەڕە دا.}}',
'listingcontinuesabbrev'   => 'درێژە',

'mainpagetext'      => "<big>'''ویكیمیدیا به‌سه‌ركه‌وتووی دامه‌زرا.'''</big>",
'mainpagedocfooter' => 'بكه‌ [http://meta.wikimedia.org/wiki/Help:ناوه‌ڕۆكی چۆنێتی به‌كارهێنان] بۆ وه‌ده‌ست هێنانی زانیاریی له‌سه‌ر چۆنێتی كارگێڕی نه‌رمه‌كاڵای ویكی، سه‌ردانی.

== ده‌ست به‌ كاركردن ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings لیسته‌ی هه‌ڵبژاردنه‌كان و ڕێكخستنه‌كان]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce لیستی وه‌شانه‌كانی ویكیمیدیا]',

'about'         => 'سه‌باره‌ت',
'article'       => 'بابەت',
'newwindow'     => '(لە پەڕەیەکی نوێ دەکرێتەوە)',
'cancel'        => 'ھەڵوەشاندنەوە',
'moredotdotdot' => 'زیاتر',
'mypage'        => 'په‌ڕه‌ی من',
'mytalk'        => 'په‌ڕه‌ی گفتوگۆی من',
'anontalk'      => 'گفتوگۆ بۆ ئه‌م ئای‌پی‌ یه‌',
'navigation'    => 'ڕێدۆزی',
'and'           => '&#32;و',

# Cologne Blue skin
'qbfind'         => 'دۆزه‌ر',
'qbbrowse'       => 'بگه‌ڕێ',
'qbedit'         => 'دەستکاری',
'qbpageoptions'  => 'ئەم پەڕەیە',
'qbpageinfo'     => 'زانیاریی په‌ڕه‌',
'qbmyoptions'    => 'پەڕەکانم',
'qbspecialpages' => 'پەڕە تایبەتەکان',
'faq'            => 'پرسیار و وەڵام (FAQ)',
'faqpage'        => 'Project:پرسیار و وەڵام',

# Vector skin
'vector-action-addsection'   => 'زیادکردنی بابەت',
'vector-action-delete'       => 'سڕینەوە',
'vector-action-move'         => 'گواستنەوە',
'vector-action-protect'      => 'پاراستن',
'vector-action-undelete'     => 'گەڕاندنەوەی سڕین',
'vector-action-unprotect'    => 'نەپاراستن',
'vector-namespace-category'  => 'پۆل',
'vector-namespace-help'      => 'پەڕەی یارمەتی',
'vector-namespace-image'     => 'پەڕگە',
'vector-namespace-main'      => 'پەڕە',
'vector-namespace-mediawiki' => 'پەیام',
'vector-namespace-project'   => 'پەڕەی پرۆژە',
'vector-namespace-special'   => 'پەڕەی تایبەت',
'vector-namespace-talk'      => 'لێدوان',
'vector-namespace-template'  => 'قاڵب',
'vector-namespace-user'      => 'پەڕەی بەکارھێنەر',
'vector-view-create'         => 'دروست‌کردن',
'vector-view-edit'           => 'دەستکاری',
'vector-view-history'        => 'بینینی مێژوو',
'vector-view-view'           => 'خوێندنەوە',
'vector-view-viewsource'     => 'بینینی سەرچاوە',
'actions'                    => 'کردارەکان',
'namespaces'                 => 'بۆشایی‌ناوەکان',
'variants'                   => 'شێوەزارەکان',

# Metadata in edit box
'metadata_help' => 'دراوه‌ی مێتا:',

'errorpagetitle'    => 'هه‌ڵه‌',
'returnto'          => 'بگه‌ڕێوه‌ بۆ $1.',
'tagline'           => 'لە {{SITENAME}}ـەوە',
'help'              => 'ڕێنمایی',
'search'            => 'گەڕان',
'searchbutton'      => 'بگەڕێ',
'go'                => 'بڕۆ',
'searcharticle'     => 'بڕۆ',
'history'           => 'مێژووی پەڕە',
'history_short'     => 'مێژووی پەڕە',
'updatedmarker'     => 'لە دوای دواسەردانم نوێکراوەتەوە',
'info_short'        => 'زانیاری',
'printableversion'  => 'وەشانی ئامادەی چاپ',
'permalink'         => 'بەستەری ھەمیشەیی',
'print'             => 'چاپ',
'edit'              => 'دەستکاری',
'create'            => 'دروستکردن',
'editthispage'      => 'ده‌ستکاری ئەم پەڕەیە بکە‌',
'create-this-page'  => 'ئەم پەڕە دروست بکە',
'delete'            => 'سڕینەوە',
'deletethispage'    => 'سڕینه‌وه‌ی ئه‌م په‌ڕه‌یه‌',
'undelete_short'    => '{{PLURAL:$1|یەک گۆڕانکاریی|$1 گۆڕانکاریی}} سڕاوە بەجێبھێنەرەوە',
'protect'           => 'پاراستن',
'protect_change'    => 'گۆڕین',
'protectthispage'   => 'ئه‌م په‌ڕه‌یه‌ بپارێزه‌',
'unprotect'         => 'مه‌پارێزه‌',
'unprotectthispage' => 'ئه‌م په‌ڕه‌یه‌ مه‌پارێزه‌',
'newpage'           => 'په‌ڕه‌یه‌كی نوێ',
'talkpage'          => 'باس لەسەر ئەم پەڕە بکە‌',
'talkpagelinktext'  => 'وتە',
'specialpage'       => 'په‌ڕه‌ی تایبه‌ت',
'personaltools'     => 'ئامڕازە تاکەکەسییەکان',
'postcomment'       => 'بەشی نوێ',
'articlepage'       => 'ناوه‌ڕۆكی بابه‌ت ببینه‌',
'talk'              => 'لێدوان',
'views'             => 'بینینەکان',
'toolbox'           => 'ئامرازدان',
'userpage'          => 'په‌ڕه‌ی به‌كارهێنه‌ر نیشانبده‌',
'projectpage'       => 'په‌ڕه‌ی پرۆژه‌ نیشانبده‌',
'imagepage'         => 'پەڕەی پەڕگە نیشان بدە',
'mediawikipage'     => 'په‌ڕه‌ی په‌یام نیشانبده‌',
'templatepage'      => 'په‌ڕه‌ی قاڵب نیشانبده‌',
'viewhelppage'      => 'په‌ڕه‌ی یارمه‌تی نیشانبده‌',
'categorypage'      => 'په‌ڕه‌ی هاوپۆل نیشانبده‌',
'viewtalkpage'      => 'په‌ڕه‌ی گفتوگۆ ببینه‌',
'otherlanguages'    => 'بە زمانەکانی تر',
'redirectedfrom'    => '(ڕەوانەکراوە لە $1 ەوە)',
'redirectpagesub'   => 'پەڕەی ڕەوانەکردن',
'lastmodifiedat'    => 'ئەم پەڕەیە دواجار لە $2ی $1 نوێکراوەتەوە.',
'viewcount'         => 'ئەم پەڕەیە {{PLURAL:$1|یەکجار|$1 جار}} بینراوە.',
'protectedpage'     => 'پەڕەی پارێزراو',
'jumpto'            => 'باز بدە بۆ:',
'jumptonavigation'  => 'ڕێدۆزی',
'jumptosearch'      => 'گەڕان',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'دەربارەی {{SITENAME}}',
'aboutpage'            => 'Project:دەربارە',
'copyright'            => 'ناوەڕۆک ئامادەیە لە ژێر $1.',
'copyrightpagename'    => '{{SITENAME}} مافی لەبەرگرتنەوە',
'copyrightpage'        => '{{ns:project}}:مافەکانی لەبەرگرتنەوە',
'currentevents'        => 'ڕووداوە هەنووکەییەکان',
'currentevents-url'    => 'Project:ڕووداوە بەردەوامەکان',
'disclaimers'          => 'بەرپرس‌نەبوونییەکان',
'disclaimerpage'       => 'Project:بەرپرس‌نەبوون',
'edithelp'             => 'ڕێنمایی بۆ دەستکاریکردن',
'edithelppage'         => 'Help:دەستکاریکردن',
'helppage'             => 'Help:رێنمایییەکان',
'mainpage'             => 'دەستپێک',
'mainpage-description' => 'ده‌ستپێک',
'policy-url'           => 'Project: سیاسەت',
'portal'               => 'دەروازەی بەکارھێنەران',
'portal-url'           => 'Project: دەروازەی بەکارھێنەران',
'privacy'              => 'سیاسەتی پاراستنی داتاکان',
'privacypage'          => 'Project:پاراستنی زانیارییەکان',

'badaccess'        => 'ھەڵە لە بە دەست ھێنان',
'badaccess-group0' => 'ڕێگەت پێ نەدراوە بۆ بەجێهێنای ئەو ئەنجامە وا داخوازیت کردووه.',
'badaccess-groups' => 'ئەو ئەنجامەی وا داخوازیت کردووه مەحدود کراوە بۆ بەکارهێنەرانی {{PLURAL:$2|دەستەی|یەکێک لە دەستەکانی}}: $1',

'versionrequired'     => 'وەشانی $1ی‌ میدیاویکی پێویستە',
'versionrequiredtext' => 'پێویستیت بە وەشانی $1ی ویکیمیدیا ھەیە بۆ بەکاربردنی ئەم پەڕەیە.
تەماشای [[Special:Version|پەڕەی وەشان]] بکە.',

'ok'                      => 'باشه‌',
'retrievedfrom'           => 'وەرگیراو لە «$1»',
'youhavenewmessages'      => '$1ت ھەیە ($2).',
'newmessageslink'         => 'پەیامە نوێکان',
'newmessagesdifflink'     => 'دوا گۆڕانکارییەکان',
'youhavenewmessagesmulti' => 'لە $1 دا پەیامی نوێت ھەیە',
'editsection'             => 'دەستکاری',
'editold'                 => 'دەستکاری',
'viewsourceold'           => 'بینینی سەرچاوە',
'editlink'                => 'دەستکاری',
'viewsourcelink'          => 'بینینی سەرچاوە',
'editsectionhint'         => 'دەستکاری کردنی بەشی: $1',
'toc'                     => 'ناوەڕۆک',
'showtoc'                 => 'نیشاندان',
'hidetoc'                 => 'شاردنەوە',
'thisisdeleted'           => '؟$1 نیشانی بده‌ یا بیگه‌ڕێنه‌ره‌وه‌',
'viewdeleted'             => '$1 نیشان بده‌؟',
'restorelink'             => '{{PLURAL:$1|گۆڕانکاریی سڕاو|$1 یەک گۆڕانکاریی سڕاو}}',
'feedlinks'               => 'خۆراک:',
'feed-invalid'            => 'ئەندام بوونی ئەو جۆرە خۆراکە نەناسراوە.',
'site-rss-feed'           => 'فیدی RSS بۆ $1',
'site-atom-feed'          => 'فیدی Atom بۆ $1',
'page-rss-feed'           => 'RSS Feed ـی "$1"',
'page-atom-feed'          => 'Atom Feed ـی "$1"',
'red-link-title'          => '$1 (پەڕە بوونی نییە)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'پەڕە',
'nstab-user'      => 'پەڕەی بەکارھێنەر',
'nstab-media'     => 'میدیا',
'nstab-special'   => 'پەڕەی تایبەت',
'nstab-project'   => 'پەڕەی پرۆژە',
'nstab-image'     => 'پەڕگە',
'nstab-mediawiki' => 'پەیام',
'nstab-template'  => 'قاڵب',
'nstab-help'      => 'پەڕەی یارمەتی',
'nstab-category'  => 'ھاوپۆل',

# Main script and global functions
'nosuchaction'      => 'كرداری به‌م شێوه‌یه‌ نییه‌',
'nosuchactiontext'  => 'ئەو چالاکییەی لە لایەن بەستەرەوە دیاریکراوە ناتەواوە.
لەوانەیە بە هەڵە بەستەرەکەت نووسیبێت، یان بەستەرێکی هەڵەی بە دواوە بێت.
لەوانەیە ئەمە نیشانەی هەڵەیەک بێت لەو نەرمەکاڵایەی کە بەکاردێت لە لایەن {{SITENAME}}.',
'nosuchspecialpage' => 'په‌ڕه‌ی تایبه‌تی له‌و شێوه‌یه‌ نییه‌',
'nospecialpagetext' => "<big>'''پەڕەیەکی تایبەت دەخوازیت کە بوونی نیە.'''</big>

لیستێکی پەڕە تایبەتە دروستەکان لە [[Special:SpecialPages|{{int:specialpages}}]] لە بەردەست‌دایە.",

# General errors
'error'                => 'هه‌ڵه‌',
'databaseerror'        => 'هه‌ڵه‌ له‌ بنكه‌دراوه‌دا هه‌یه‌',
'dberrortext'          => 'هەڵەیەکی ڕستەکار داواکاری بنکە‌دراو ڕووی‌داوە.
لەوانەیە ئەوە نیشاندەری کەلێنێک لە نەرمەواڵەکەدا بێت.
دوایین تێکۆشان بۆ داواکاری بنکەی‌دراو:
<blockquote><tt>$1</tt></blockquote>.
دەگەڵ کرداری "<tt>$2</tt>".
مای‌ئێس‌کیو‌ئێڵ ئەو هەڵەی گەڕانەتەوە: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'هەڵەیەکی ڕستەکار داواکاری بنکە‌دراو ڕووی‌داوە.
دوایین تێکۆشان بۆ داواکاری بنکەی‌دراو:
"$1"
دەگەڵ کرداری "$2" .
مای‌ئێس‌کیو‌ئێڵ ئەو هەڵەی گەڕانەتەوە: "$3: $4"',
'laggedslavemode'      => 'ئاگاداری: لەوانەیە لاپەڕەکە نوێکردنەکان لە بەر نەگرێت.',
'readonly'             => 'بنکەدراوە داخراوە',
'enterlockreason'      => 'هۆیەک بۆ قوفڵ‌کردنەکە بنووسە کە  تێیدا کاتی کردنەوەی قۆفڵەکە باس کرابێت',
'readonlytext'         => 'بنکەدراوەکە لەم کاتەدا  لەبەر چاکسازی ئاسایی بۆ نوسینی نوێ و دەستکاری قوفڵ کراوه. دوای ئەوە ئەگرێتەوە بۆ ئاستی خۆی.

ئەو بەڕێوبەرەی کە قوفڵی کردووه ئەم ڕوون‌کردنەوەی نووسیوە : $1',
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
'fileexistserror'      => 'ناتوانی لەسەر پەڕگەی "$1" بنووسیت: ئەو پەڕگەیە هەیە.',
'unexpected'           => 'نرخی چاوەڕوان نەکراو: "$1"="$2" .',
'formerror'            => 'هەڵە: فورمەکە نانێردرێت.',
'badarticleerror'      => 'ئەو ئاماژە لەم لاپەڕەدا پێک‌نایە.',
'cannotdelete'         => 'نەتوانرا پەڕە یان پەڕگەی دیاریکراو بسڕدرێتەوە.
لەوانەیە پێشتر لە لایەن کەسێکی ترەوە سڕدرابێتەوە.',
'badtitle'             => 'ناونیشانی خراپ',
'badtitletext'         => 'سەرناوی ئەو لاپەڕەی کە دەتەوێت پووچە، بەتاڵە، یان سەرناوێکی نێوان-زمانی یان نێوانی-ویکییە کە بە شێوەیەکی ھەڵە لکێندراوە.
ڕەنگە یەک یان چەند کاراکتێری تێدا بێت کە ناشێت لە نێو سەرناوەکان دا بەکار بھێنرێت.',
'perfcached'           => 'ئەم داتای خوارەوە پاشەکەوتی کەشە وە ناکرێ تازەی بکەیەوە.',
'perfcachedts'         => 'ئەم داتای خوارەوە کەش کراوە، و دوایین جار لە $1 تازە کراوەتەوە',
'querypage-no-updates' => 'تازەکردنەوەی ئەم لاپەڕە لە حاڵی ئێستا دا ناچالاک کراو.
داتای ئەم شوێنە بەم زووانە تازە ناکرێتەوە.',
'wrong_wfQuery_params' => 'پارامێتری ھەڵە بۆ wfQuery()<br />
کردار: $1<br />
داواکاری: $2',
'viewsource'           => 'بینینی سەرچاوە',
'viewsourcefor'        => 'بۆ $1',
'actionthrottled'      => 'چالاکی پێشی پێ گیرا',
'actionthrottledtext'  => 'بە مەبەستی پێشگریی لە سپەم، ڕێگە نادرێت تۆ لە ماوەیەکی کورت دا لە سەر یەک ئەمە زۆر جار ئەنجام بدەی، وە ئیستا تۆ لە ڕادە بەدەرت کردووە.
تکایە پاش چەند خولەک دووبارە تاقی بکەوە.',
'protectedpagetext'    => 'بە مەبەستی پێشگریی لە دەستکاریی، ئەم لاپەڕە قوفڵ کراوە.',
'viewsourcetext'       => 'تۆ دەتوانی سەرچاوەی ئەم لاپەڕە ببینی و کۆپی بکەی:',
'protectedinterface'   => 'ئەم لاپەڕە دەقی ڕوواڵەتی نەرم‌ئامێرەکە نیشان ئەدات، وە بۆ پێشگریی لە خراپکاریی قوفڵ کراوە.',
'editinginterface'     => "'''ئاگاداریی:''' تۆ خەریکی دەستکاریی لاپەڕەیەکی کە بۆ دابینکردنی دەقی ڕوواڵەتی نەرم‌ئامێر بە کار دەھێنرێت.
گۆڕانکاریی لە ئەم لاپەڕە کاریگەر دەبێت لە سەر ڕواڵەتی لاپەڕەکانی بەکارھێنەرانی دیکە.
بۆ وەرگێڕان تکایە [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] بەکار بھێنە، واتە پرۆژەی خۆجێیی‌کردنی مێدیاویکی.",
'sqlhidden'            => '(داواکاریی SQL شاراوەیە)',
'cascadeprotected'     => 'ئەم لاپەڕە پارێزراوە لە دەستکاریی، چونکا خراوەتە سەر ڕیزی ئەم {{PLURAL:$1|لاپەڕانه‌، کە}} که‌ به‌ هه‌ڵکردنی بژارده‌ی داڕژان هه‌ڵکراوه‌:
$2',
'namespaceprotected'   => "تۆ ناتوانی لاپەڕەکانی ناو نەیمسپەیسی '''$1''' بگۆڕی.",
'customcssjsprotected' => 'تۆ ناتوانی ئەم لاپەڕە دەستکاریی بکەی، چونکا ڕێکراوە تاکەکەسییەکانی بەکارھێنەرێکی دیکەی تێدایە.',
'ns-specialprotected'  => 'تۆ ناتوانی لاپەڕە تایبەتەکان دەستکاریی بکەی.',
'titleprotected'       => 'ئەم سەرناوە پارێزراوە لە دروستکران لە لایەن [[User:$1|$1]].
ھۆکاری ئەمە بریتیە لە "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "پێکەربەندیی نابەجێ: ڤایرس سکەنێری نەناسراو: ''$1''",
'virus-scanfailed'     => 'سکەن ئەنجام نەدرا(کۆد $1)',
'virus-unknownscanner' => 'دژەڤایرس نەناسراوە:',

# Login and logout pages
'logouttext'                 => "'''ئێستا تۆ لە ئەکانتەکەت ھاتوویتە دەرەوە.'''

تۆ دەتوانی درێژە بدەی بە بەرکارھێنانی {{SITENAME}} به‌ شێوه‌ی بێناو، یان ده‌توانی [[Special:UserLogin|دیسانه‌وه‌ بچیته‌وه‌ نێو ئه‌کانته‌که‌ت]] به‌ همان ناوی به‌کارهێنه‌ره‌وه‌ یان به‌ ناوی به‌کارهێنه‌رێکی جیاوازه‌وه‌.
ئاگادار به‌ که‌ سه‌ره‌ڕای چوونه‌ده‌ره‌وه‌ی تۆ له‌ ئه‌کانته‌که‌ت هه‌ندێک له‌ لاپه‌ڕه‌کان هه‌ر به‌ شێوه‌یه‌ک نیشان ئه‌درێن که‌ گوایه‌ تۆ هێشتا له‌ نێو ئه‌کانته‌که‌ت دای. ئه‌مه‌ به‌رده‌وام ده‌بێت هه‌تا کاتێک که‌ تۆ که‌شی وێبگه‌ڕه‌که‌ت ده‌سڕیته‌وه‌.",
'welcomecreation'            => '== خۆش هاتیت, $1! ==

ئەکانتی تایبه‌تی تۆ سه‌ركه‌وتووانه‌ دروست كرا، له‌بیرت نه‌چێت گۆڕانكاری له {{SITENAME}} تایبه‌ت به‌خۆت دا بكه‌.',
'yourname'                   => 'ناوی بەکارھێنەر:',
'yourpassword'               => 'تێپه‌ڕه‌وشه‌:',
'yourpasswordagain'          => 'تێپه‌ڕه‌وشه‌ دووباره:‌',
'remembermypassword'         => 'زانیاریی چوونە ژوورەوەم لەسەر ئەم کۆمپیوتەرە پاشەکەوت بکە‌',
'yourdomainname'             => 'ناوی دۆمه‌ینی خۆت',
'externaldberror'            => 'یان هەڵەی ڕێگەپێدانی بنکەدراو هەیە یان ڕێگات پێ نادرێت بۆ نوێ کردنی هەژماری دەرەکیت.',
'login'                      => 'تێکەوە (login)',
'nav-login-createaccount'    => 'چوونەژوورەوە / دروستکردنی هەژمار',
'loginprompt'                => 'بۆ چونەژوورەوه لە {{SITENAME}}، ئەشێ ڕێگە بدەی بە کووکی‌یەکان.',
'userlogin'                  => 'دروست کردنی ھەژمار/چوونە ژورەوە',
'logout'                     => 'ده‌رچوون',
'userlogout'                 => 'دەرچوون',
'notloggedin'                => 'له‌ ژووره‌وه‌ نیت',
'nologin'                    => 'ھەژمارت نییە؟  $1.',
'nologinlink'                => 'ببه‌ به‌ ئه‌ندام',
'createaccount'              => 'ھەژمار دروست بکە',
'gotaccount'                 => 'خاوه‌نی هه‌ژماری خۆتی؟ $1.',
'gotaccountlink'             => 'چوونه‌ ژووره‌وه‌',
'createaccountmail'          => 'بە ئیمەیل',
'badretype'                  => 'تێپەڕوشەکان لەیەک ناچن.',
'userexists'                 => 'ئەو ناوەی تۆ داوتە پێشتر کەسێکی دیکە بەکاری بردووە.
ناوێکی دیکە ھەڵبژێرە.',
'loginerror'                 => 'ھەڵە لە چوونە ژوورەوەدا',
'nocookiesnew'               => 'هەژماری بەکارهێنەر درووست‌کرا، بەڵام بە سەرکەوتوویی نەچوویتەوە ژوورەوە.
{{SITENAME}} بۆ چوونەوە ژووری بەکارهێنەر لە شەکرۆکە کەڵک وەر دەگرێت.
تۆ بەکار‌هێنانی شەکرۆکەت لەکارخستە.
تکایە شەکرۆکە کارا بکە و بە ناو و وشەی تێپەڕبوونی بەکارهێنەر بچۆ ژوورەوە.',
'nocookieslogin'             => '{{SITENAME}} بۆ چوونەژوورەوە لە کووکی‌یەکان کەڵک وەرئەگرێت.
ڕێگەت نەداوە بە کووکی‌یەکان.
ڕێگەیان پێ بدەو و دیسان تێبکۆشە.',
'noname'                     => 'ناوی بەکارهێنەری گۆنجاوت دابین‌ نەکردووه.',
'loginsuccesstitle'          => 'سرەکەوتی بۆ چوونە ژوورەوە!',
'loginsuccess'               => "'''ئێستا لە {{SITENAME}} چوویتەوە ژوورەوە  وەک \"\$1\".'''",
'nosuchuser'                 => 'بەکارھێنەرێک بە ناوی «$1» نیە.
ناوی بەکارھێنەر بە گەورە و بچووک بوونی پیتەکان ھەستیارە.
ڕێنووسەکەت چاولێکەرەوە، یان [[Special:UserLogin/signup|ھەژمارێکی نوێ دروست بکە]].',
'nosuchusershort'            => 'بەکارهێنەر بە ناوی "<nowiki>$1</nowiki>" نیە.
چاو لە ڕێنووسەکە بکە.',
'nouserspecified'            => 'دەبێ ناوی بەکارهێنەر دابین‌ بکەی.',
'wrongpassword'              => 'تێپەڕوشەی ھەڵە. 
تکایە دووبارە تێبکۆشە.',
'wrongpasswordempty'         => 'تێپەڕەوشەی لێدراو بەتاڵبوو.
تکایە هەوڵ بدەوە.',
'passwordtooshort'           => 'تێپەڕوشەکەت زۆر کورتە.
لانی کەم دەبێ {{PLURAL:$1|١ پیت|$1 پیت}} بێت.',
'mailmypassword'             => 'تێپەڕوشەیەکی نوێ بنێرە بۆ E-mailەکەم',
'passwordremindertitle'      => 'تێپەڕوشەیەکی نوێی کاتی بۆ  {{SITENAME}}',
'passwordremindertext'       => 'کەسێک (شایەد تۆ، لە ئەدرەسی ئای‌پیی $1) داخوازی تێپەڕوشەیەکی نوێی کردووە بۆ {{SITENAME}} ($4).
تێپەڕوشەیەکی کاتی بۆ بەکارهێنەر «$2» درووست‌کراوە و  وەک «$3» دانراوه. ئەگەر داواکاری تۆ بووە، دەبێ هەر ئێستا بڕۆیتە ژوورەوە و تێپەڕوشەیەکی نوێ هەڵبژێریت. تێپەڕوشەی کاتی لە $5 ڕۆژ دا ماوەی‌ بەسەر دەچێت.

ئەگەر کەسێکی دی ئەو داخوازیەی کردە یان وشە نهێنیەکەت بیر هاتەوە 
و چی تر پێویستت بە گۆڕانی نەبوو، ئەم پەیامە بەتاڵ‌کەوە و لە وشە نهێنیە کۆنەکەت کەڵک وەربگرە.',
'noemail'                    => 'ھیچ ئەدرەسێکی e-mail تۆمار نەکراوە بۆ بەکارھێنەر  "$1" .',
'passwordsent'               => 'وشەی نهێنی نوێ بۆ ئەدرەسی ئی‌مێڵغ تۆمار کراو بۆ "$1"، ناردرا.
تکایە دوای وەرگرتنی دووبارە بچۆ ژوورەوە.',
'blocked-mailpassword'       => 'ئادرەسی ئای‌پی تۆ بۆ دەستکاری کردن بەستراوه بۆیە بۆ بەرگری لە بەکارهێنانی نابەجێ ئەنجامی گەڕانەوەی تێپەڕوشە ڕیگە نەدراوە.',
'throttled-mailpassword'     => 'بیرهێنەرەوەیەکی وشەی نهێنی پێش ئەمە لە {{PLURAL:$1|کاتژمێر}}ی ڕابردوودا ناردراوە.
بۆ بەرگری لە بەکارهێنانی خراپ، تاکە یەک بیرهێنەرەوەی وشەی نهێنی هەر {{PLURAL:$1|کاتژمێر}} دەنێردرێت.',
'mailerror'                  => 'هەڵە ڕوویدا لە ناردنی ئیمەیل: $1',
'acct_creation_throttle_hit' => 'بینەرانی ویکی بەکەڵک وەرگرتن لەم ئای‌پی ئەدرەسەی تۆ لە ڕۆژانی ڕابردوودا، دەستیان کردە بە درووست‌کردنی {{PLURAL:$1|هەژمارە}}، کە زۆرینە ڕیگەپێدان لە یەک ماوە‌دایە.
وەک ئەنجامی ئەو ڕووداوە، ئەو بینەرانی لەم ئای‌پی ئەدرەسە کەڵک وەر دەگرن لەم کاتەدا ناتوانن هەژماری دیکە درووست‌بکەن.',
'emailauthenticated'         => 'ئیمەیلەکەت بە ڕاست ناسرا لە $3ی $2 دا',
'emailnotauthenticated'      => 'ئیمەیلەکەت ھێشتا نەناسراوە.
ھیچ ئیمەیلێک بۆ ئەم بابەتانەی خوارەوە نانێردرێت.',
'noemailprefs'               => 'بۆ کەوتنە کاری ئەو تایبەتمەندیانە، لە هەڵبژاردەکانت ئەدرەسەکی ئی‌مێڵ دابین بکە.',
'emailconfirmlink'           => 'ئیمەیلەکەت پشت‌ڕاست بکەرەوە',
'invalidemailaddress'        => 'ئەو ئەدرەسی ئی‌مێڵە لەبەر ئەوەی بە شێوازێکی نەناسراوە، پەسند نەکرا.
تکایە ئەدرەسێک بە شێوازی ناسراو بنووسە یان ئەو خانەیە بەتاڵ بهێڵەوە.',
'accountcreated'             => 'ھەژمار دروست کرا',
'accountcreatedtext'         => 'هەژماری بەکارهێنەر بۆ $1 درووست‌کرا.',
'createaccount-title'        => 'درووست‌کردنی هەژمارە بۆ {{SITENAME}}',
'createaccount-text'         => 'کەسێک هەژمارەیەکی بۆ ئی‌مێڵ ئەدرەسەکی تۆ لەسەر {{SITENAME}} ($4) بەناوی "$2"، بە وشەی نهێنی "$3".
ئێستا دەبێ بڕۆیتە ژوورەوە و وشەی نهێنی بگۆڕیت.

ئەگەر ئەو هەژمارە بە هەڵە درووست‌کراوە، ئەم برووسکە لە بەرچاو مەگرە.',
'loginlanguagelabel'         => 'زمان: $1',

# Password reset dialog
'resetpass'                 => 'گۆڕینی تێپەڕوشە',
'resetpass_text'            => '<!-- تێپه‌ڕه‌وشه‌ی هه‌ژماره‌كه‌ سفر بكه‌ره‌وه‌ -->',
'resetpass_header'          => 'تێپەڕوشەی ھەژمار بەتاڵ بکە',
'oldpassword'               => 'تێپەڕوشەی پێشو:',
'newpassword'               => 'تێپەڕوشەی نوێ:',
'retypenew'                 => 'تێپەڕوشەی نوێ دوبارە بنووسەوە:',
'resetpass_submit'          => 'تێپەڕوشە رێکخە و بچۆ ژوورەوە',
'resetpass_success'         => 'تێپەروشەکەت بە سەرکەوتوویی گۆڕدرا. ئێستا چوونە ژوورەوەت...',
'resetpass_forbidden'       => 'تێپەڕوشەکە ناگۆڕدرێت',
'resetpass-no-info'         => 'بۆ گەیشتنی راستەوخۆ بەم پەڕە ئەشێ بچیتە ژوورەوە.',
'resetpass-submit-loggedin' => 'گۆڕینی تێپەڕوشە',
'resetpass-wrong-oldpass'   => 'تێپەڕوشەی ھەنووکەیی یان تێپەڕوشەی کاتی ھەڵەیە.
وا دیارە تێپەڕوشەکەت بە سەرکەوتوویی گۆڕدراوە یان داوای تێپەڕوشەیەکی نوێت کردووە.',
'resetpass-temp-password'   => 'تێپەڕوشەی کاتی:',

# Edit page toolbar
'bold_sample'     => 'دەقی ئەستوور',
'bold_tip'        => 'دەقی ئەستوور',
'italic_sample'   => 'دەقی لار',
'italic_tip'      => 'دەقی لار',
'link_sample'     => 'نێوی بەستەر',
'link_tip'        => 'بەستەری ناوخۆ',
'extlink_sample'  => 'http://www.example.com سەردێڕی بەستەر',
'extlink_tip'     => 'بەستەری دەرەکی (لەبیرت بێ نووسینی پێشگری http:// )',
'headline_sample' => 'دەقی سەردێڕ',
'headline_tip'    => 'سەردێڕی ئاست ۲',
'math_sample'     => 'لەگرە فۆرموول بخەسەر',
'math_tip'        => 'فۆرموولی بیرکاری (LaTeX)',
'nowiki_sample'   => 'لەگەرە دەقی نەڕازراو تێ‌بخە',
'nowiki_tip'      => 'لەبەرچاو نەگرتنی دارشتنەکانی ویکی',
'image_sample'    => 'نموونە.jpg',
'image_tip'       => 'وێنەی نێو دەق',
'media_sample'    => 'نموونە.ogg',
'media_tip'       => 'لینکی پەڕگە',
'sig_tip'         => 'ئیمزاکەت بە مۆری ڕێکەوتەوە',
'hr_tip'          => 'هێڵی ئاسۆیی (دەگمەن بەکاری بێنە)',

# Edit pages
'summary'                          => 'پوختە:',
'subject'                          => 'بابه‌ت / سه‌روتار:',
'minoredit'                        => 'ئەم گۆڕانکاری‌یە بچووکە',
'watchthis'                        => 'چاودێڕی ئەم پەڕەیە بکە',
'savearticle'                      => 'پاشەکەوتکردنی پەرە',
'preview'                          => 'پێشبینین',
'showpreview'                      => 'پێشبینینی پەڕە',
'showlivepreview'                  => 'پێشبینینی ڕاسته‌وخۆ',
'showdiff'                         => 'گۆڕانکارییەکان نیشان بدە',
'anoneditwarning'                  => "'''وشیار بە:''' نەچوویتەتە ژوورەوە.
ئەدرەسی ئەکەت لە مێژووی ئەم پەڕە دا تۆمار دەکرێ.",
'missingsummary'                   => "'''وە بیر خستنەوە:''' پوختەیەکت نەنووسیوە بۆ چۆنیەتی گۆڕانکارییەکەت.
ئەگەر جارێکی تر پاشکەوت کردن لێبدەی، بێ پوختە تۆمار دەکرێ.",
'missingcommenttext'               => 'تکایە لە خوارەوە شرۆڤەیەک بنووسە.',
'summary-preview'                  => 'پێشبینینی کورتە:',
'blockedtitle'                     => 'به‌کار هینه‌ر له‌کار خراوه',
'blockednoreason'                  => 'هیچ هۆکارێک نەدراوە',
'blockedoriginalsource'            => "سەرچاوەی '''$1''' لەخوارەوە پیشاندراوە:",
'whitelistedittitle'               => 'بۆ دەسکاری پێویسته بەشدار بیت',
'nosuchsectiontitle'               => 'هیچ بەشێک نییە',
'loginreqtitle'                    => 'پێویستە بچیه ژور',
'loginreqlink'                     => 'چونه‌ژور',
'accmailtitle'                     => 'وشه‌ی نهێنی ناردرا.',
'newarticle'                       => '(نوێ)',
'newarticletext'                   => "بە دوای بەستەری پەڕەیەک کەوتووی کە ھێشتا دروست نەکراوە. <br /> بۆ دروست کردنی پەڕەکە، لە چوارچێوەکەی خوارەوە دەست کە بە تایپ کردن. (بۆ زانیاری زورتر[[یارمەتی|{{MediaWiki:Helppage}}]] ببینە). <br />  ئەگەر بە ھەڵەوە ھاتویتە ئەگرە، لە سەر دوگمەی '''back'''ی وێبگەڕەکەت کلیک کە.",
'noarticletext'                    => 'ھەنووکە لەم پەڕەدا ھیچ دەقێک نیە.
دەتوانی بۆ ئەم ناوە لە [[Special:Search/{{PAGENAME}}|پەڕەکانی تر دا بگەڕێی]] ،<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} لە لۆگەکاندا بگەڕی ],
یان [{{fullurl:{{FULLPAGENAME}}|action=edit}}  ئەم پەڕە دەستکاری بکەیت. ]</span>.',
'note'                             => "'''تێبینی:'''",
'previewnote'                      => "'''لە بیرت بێت کە ئەمە تەنها پێشبینینە.
گۆڕانکارییەکانت تا ئێستا پاشەکەوت نەکراون!'''",
'editing'                          => 'دەستکاریکردنی $1',
'editingsection'                   => 'گۆڕاندنی: $1 (بەش)',
'editingcomment'                   => 'گۆڕاندنی $1 (بەشی  نوێ)',
'yourtext'                         => 'نوسراوی تۆ',
'copyrightwarning'                 => "تکایە ئاگادار بن کە ھەموو بەشدارییەک بۆ  {{SITENAME}} وا فەرز ئەکرێت کە لە ژێر «$2» بڵاو دەبێتەوە (بۆ ئاگاداری زۆرتر $1 سەیر کە). ئەگەر ناتەوێ نوسراوەکەت بێ‌ڕەحمانە دەستکاری بکرێت و  بە دڵخواز دیسان بڵاو ببێتەوە، لەگرە پێشکەشی مەکە. ھەروەھا بەڵین ئەدەی کە خۆت ئەمەت نووسیوە، یان لە سەرچاوەیەکی بە دەسەڵاتی گشتی ''(public domain)'' یان سەرچاوەیەکی ھاوتا لەبەرت‌گرتوەتەو.
'''«بەرھەمێک کە مافی لەبەرگرتنەوەی پارێزراوە، بێ ئیجازە  بڵاو مەکەرەوە.»'''",
'templatesused'                    => 'ئەو قاڵبانە کە لەم پەڕەیەدا بە کارھێنراون:',
'templatesusedpreview'             => 'ئەو قاڵبانە کە لەم پێشبینینەدا بە کارھێنراون:',
'templatesusedsection'             => 'ئەو قاڵبانە کە لەم بەشەدا بە کارھێنراون:',
'template-protected'               => '(پارێزراو)',
'template-semiprotected'           => '(نیوەپارێزراو)',
'hiddencategories'                 => 'This page is a member of 
ئەم پەڕە ئەندامێکی {{PLURAL:$1|١ ھاوپۆلی شاراوەیە|$1 ھاوپۆلی شاراوەیە}}:',
'permissionserrorstext-withaction' => 'دەسەڵاتت نییە بۆ $2 لەبەر ئەم {{PLURAL:$1|هۆکارە|هۆکارانە}}ی خوارەوە:',
'moveddeleted-notice'              => 'ئەم پەڕەیە سڕاوەتەوە.
لۆگی سڕینەوە و گواستنەوە بۆ پەڕەکە لە خوارەوە دابینکراوە.',

# Account creation failure
'cantcreateaccounttitle' => 'ناتوانرێت هەژمار دروست بکرێت',

# History pages
'viewpagelogs'           => 'لۆگەکانی ئەم پەڕەیە ببینە',
'nohistory'              => 'هیچ مێژوویەکی دەستکاری نییە بۆ ئەم پەڕەیە.',
'currentrev-asof'        => 'بینینەوی ھەنووکە تا $1',
'revisionasof'           => 'وەک بینینەوەی $1',
'previousrevision'       => '←پیاچوونەوەی کۆنتر',
'nextrevision'           => 'پیاچوونەوەی نوێتر→',
'currentrevisionlink'    => 'پیاچوونەوەی ئێستا',
'cur'                    => 'ئێستا',
'next'                   => 'پاش',
'last'                   => 'پێشوو',
'page_first'             => 'یەکەمین',
'page_last'              => 'دوایین',
'histlegend'             => 'وەشانەکان بۆ ھەڵسەنگاندن دیاری بکە و ئەم دوگمەی خوارەوە لێبدە. <br />
ڕێنمایی:
(ئێستا) = جیاوازی لەگەڵ وەشانی ئێستا،
(پێشوو) =جیاوازی لەگەڵ وەشانی پێشوو،
ب = گۆڕانکاریی بچووک',
'history-fieldset-title' => 'گەشتی مێژوو',
'histfirst'              => 'کۆنترین',
'histlast'               => 'نوێترین',
'historysize'            => '({{PLURAL:$1|1 بایت|$1 بایت}})',
'historyempty'           => '(پووچ)',

# Revision feed
'history-feed-title'          => 'مێژووی پیاچوونەوە',
'history-feed-description'    => 'مێژووی پیاچوونەوە بۆ ئەم پەڕە لە ویکییەکە',
'history-feed-item-nocomment' => '$1 لە $2',

# Revision deletion
'rev-delundel'         => 'پیشاندان/شاردنەوە',
'revdelete-hide-image' => 'ناوەڕۆکی پەڕگە بشارەوە',
'revdel-restore'       => 'چۆنیەتی دەرکەوتن بگۆڕە',
'pagehist'             => 'مێژووی پەڕە',
'deletedhist'          => 'مێژوو بسڕەوە',
'revdelete-content'    => 'ناوەڕۆک',
'revdelete-summary'    => 'دەستکاری کورتە بکە',
'revdelete-uname'      => 'ناوی بەکارهێنەر',

# History merging
'mergehistory-from'   => 'سەرچاوەی پەڕە',
'mergehistory-into'   => 'پەڕەی مەبەست:',
'mergehistory-reason' => 'هۆکار:',

# Merge log
'mergelog'    => 'تۆمار بلکێنە',
'revertmerge' => 'لەیەک جیاکردنەوە',

# Diffs
'history-title'           => 'مێژووی پیاچوونەوەکانی "$1"',
'difference'              => '(جیاوازی نێوان پیاچوونەوەکان)',
'lineno'                  => 'ھێڵی  $1:',
'compareselectedversions' => 'ھەڵسەنگاندنی وەشانە ھەڵبژاردراوەکان',
'editundo'                => 'پاشگەزبوونەوە',
'diff-movedto'            => 'گوێزرایەوە بۆ $1',
'diff-styleadded'         => '$1 ڕووخسار زیاد کرا',
'diff-added'              => '$1 زیاد کرا',
'diff-changedto'          => 'گۆڕدرا بۆ $1',
'diff-styleremoved'       => '$1 ڕووخسار لابرا',
'diff-removed'            => '$1 لابرا',
'diff-changedfrom'        => 'گۆڕدرا لە $1 ـەوە',
'diff-src'                => 'سەرچاوە',
'diff-width'              => 'پانی',
'diff-height'             => 'بەرزی',
'diff-p'                  => "'''پەرەگراف'''ێک",
'diff-table'              => "'''خشتە'''یەک",
'diff-tr'                 => "'''ئاسۆ'''یەک",
'diff-td'                 => "'''خانە'''یەک",
'diff-th'                 => "'''سەرپەڕە'''یەک",

# Search results
'searchresults'                    => 'ئەنجامەکانی گەڕان',
'searchresults-title'              => 'ئەنجامەکانی گەڕان بۆ "$1"',
'searchresulttext'                 => 'بۆ زانیاری زیاتر دەربارەی گەڕان {{SITENAME}} ، بڕوانە لە  [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'گەڕایت بۆ \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|هەموو ئەو پەڕانەی دەستپێدەکەن بە "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|هەموو ئەو پەڕانەی بەستەرکراون بۆ "$1"]])',
'searchsubtitleinvalid'            => "گەڕایت بۆ '''$1'''",
'noexactmatch'                     => "'''پەڕەیەک بە ناوی  \"\$1\"ەوە نیە.'''
دەتوانی ئەم پەڕە [[:\$1|دروست بکەیت]].",
'noexactmatch-nocreate'            => "'''هیچ پەڕەیەک نییە بە ناونیشانی \"\$1\".'''",
'titlematches'                     => 'سەردێڕی پەڕە پێی ئەخوا',
'notitlematches'                   => 'لە نێو سەردێڕەکان نەبینرا',
'notextmatches'                    => 'لە دەقی نووسراوەکان دا نەبینرا',
'prevn'                            => '{{PLURAL:$1|$1}}ی پێشوو',
'nextn'                            => '{{PLURAL:$1|$1}}ی دواتر',
'viewprevnext'                     => '($1) ($2) ($3) ببینە',
'searchmenu-legend'                => 'ھەڵبژاردەکانی گەڕان',
'searchmenu-new'                   => "'''پەڕەی «[[:$1]]» لەم ویکییە دروست بکە!'''",
'searchhelp-url'                   => 'Help:پێرست',
'searchprofile-articles'           => 'پەڕە بە ناوەڕۆکەکان',
'searchprofile-project'            => 'پەڕەکانی یارمەتی و پڕۆژە',
'searchprofile-images'             => 'ڕەنگاڵە',
'searchprofile-everything'         => 'ھەموو شتێک',
'searchprofile-advanced'           => 'پێشکەوتوو',
'searchprofile-articles-tooltip'   => 'بگەڕێ لە $1',
'searchprofile-project-tooltip'    => 'بگەڕێ لە $1',
'searchprofile-images-tooltip'     => 'بگەڕێ بۆ پەڕگەکان',
'searchprofile-everything-tooltip' => 'لە هەموو ناوەڕۆک بگەڕێ (تەنانەت پەڕەی وتەکانیش)',
'search-result-size'               => '$1 ({{PLURAL:$2|١ وشە|$2 وشە}})',
'search-result-score'              => 'پەیوەندی: $1%',
'search-redirect'                  => '(ئاڵوگۆڕ $1)',
'search-section'                   => '(بەشی $1)',
'search-suggest'                   => 'ئایا مەبەستت ئەمە بوو: $1',
'search-interwiki-caption'         => 'پرۆژە خوشکەکان',
'search-interwiki-default'         => '$1 ئەنجام:',
'search-interwiki-more'            => '(زیاتر)',
'search-mwsuggest-enabled'         => 'بە پێشنیارەکانەوە',
'search-mwsuggest-disabled'        => 'بێ پێشنیار',
'search-relatedarticle'            => 'پەیوەست',
'mwsuggest-disable'                => 'پێشنیارەکانی AJAX نیشان مەدە',
'searcheverything-enable'          => 'لە ھەموو بۆشایی‌‌ناوەکان دا بگەڕە',
'searchrelated'                    => 'پەیوەست',
'searchall'                        => 'هەموو',
'showingresults'                   => "لە خوارەوە {{PLURAL:$1|'''1''' ئەنجام|'''$1''' ئەنجام}} ئەبینن کە بە #'''$2'''ەوە دەست پێ‌ئەکات .",
'showingresultsnum'                => "لە خوارەوە {{PLURAL:$3|'''١''' ئەنجام|'''$3''' ئەنجام}} دەبینن کە بە #'''$2'''ەوە دەست{{PLURAL:$3|پێدەکات|پێدەکەن}}",
'showingresultstotal'              => "نیشاندان لە خوارەوە{{PLURAL:$4|result '''$1''' of '''$3'''|ئاکامەکان '''$1 - $2''' of '''$3'''}}",
'showingresultsheader'             => "{{PLURAL:$5|ئەنجامی '''$1''' لە '''$3'''|ئەنجامەکانی '''$1 - $2''' لە '''$3'''}} بۆ '''$4'''",
'nonefound'                        => "'''تێبینی''': گەڕان بە شێوەی دیفاڵت تەنها لە هەندێک لە بۆشایی‌ناوە‌کان دەکرێ‌.
پێشگری ''all:'' بەکاربێنە بۆ گەڕان له‌ نێو هەموو بابەتەکان (لەوانە لاپه‌ڕه‌کانی وتووێژ، قاڵبەکان، و هتد)، یان بۆشایی‌ناوێکی دڵخواز وەک پێشگر بەکار بێنە.",
'search-nonefound'                 => 'ھیچ ئەنجامێک کە کە بە داواکارییەکەت بخوا نەدۆزرایەوە.',
'powersearch'                      => 'بە ھێز بگەڕە',
'powersearch-legend'               => 'گەڕانی پێشکەوتوو',
'powersearch-ns'                   => 'گەڕان لە بۆشایی‌ناوەکانی:',
'powersearch-redir'                => 'ڕەوانەکراوەکان لیست بکرێن',
'powersearch-field'                => 'گەڕان بۆ',
'powersearch-togglelabel'          => 'پشکنینی:',
'powersearch-toggleall'            => 'ھەموو',
'powersearch-togglenone'           => 'ھیچیان',
'search-external'                  => 'گەڕانی دەرەکی',

# Quickbar
'qbsettings-none' => 'هیچ',

# Preferences page
'preferences'                   => 'ھەڵبژاردەکان',
'mypreferences'                 => 'ھەڵبژاردەکانی من',
'prefs-edits'                   => 'ژمارەی گۆڕانکارییەکان:',
'prefsnologin'                  => 'لەژوورەوە نیت',
'changepassword'                => 'تێپەڕوشە بگۆڕە',
'prefs-skin'                    => 'پێستە',
'skin-preview'                  => 'پێش بینین',
'prefs-math'                    => 'بیرکاری',
'datedefault'                   => 'ھەڵنەبژێردراو',
'prefs-datetime'                => 'کات و ڕێکەوت',
'prefs-personal'                => 'پرۆفایلی بەکارھێنەر',
'prefs-rc'                      => 'دوایین گۆڕانکارییەکان',
'prefs-watchlist'               => 'لیستی چاودێڕییەکان',
'prefs-watchlist-days'          => 'ژمارەی ڕۆژە نیشاندراوەکان لە لیستی چاودێڕییەکان:',
'prefs-watchlist-days-max'      => '(ئه‌وپه‌ڕی ٧ ڕۆژە)',
'prefs-watchlist-edits'         => 'ئەوپەڕی ژمارەی گۆڕانکارییەکان بۆ نیشان دان لە لیستی پەرە پێدراوی چاودێڕی:',
'prefs-watchlist-edits-max'     => '(ئەوپەڕی ژمارە: ١٠٠٠)',
'prefs-misc'                    => 'جۆراوجۆر',
'prefs-resetpass'               => 'تێپەڕوشە بگۆڕە',
'prefs-email'                   => 'ھەڵبژاردەکانی ئیمەیل',
'prefs-rendering'               => 'ڕواڵەت',
'saveprefs'                     => 'پاشەکەوت',
'resetprefs'                    => 'گۆڕانکارییە پاشەکەوت نەکراوەکان پاک بکەرەوە',
'restoreprefs'                  => 'ھەموو تەنزیمەکان ببەرەوە بۆ حاڵەتی بنچینەیی',
'prefs-editing'                 => 'دەستکاری کردن',
'prefs-edit-boxsize'            => 'قەبارەی پەنجەرەی گۆڕانکاری.',
'rows'                          => 'ڕیزەکان:',
'columns'                       => 'ستوونەکان:',
'searchresultshead'             => 'گەڕان',
'resultsperpage'                => 'ژمارەی ئەنجامەکان لە ھەر پەڕەیەک:',
'contextlines'                  => 'ژمارەی دێڕەکانی ھەر ئەنجام:',
'contextchars'                  => 'ژمارەی پیتەکانی ھەر دێڕ:',
'recentchangesdays'             => 'ژمارە ڕۆژە نیشاندراوەکان لە دوایین گۆڕانکارییەکان:',
'recentchangesdays-max'         => '(ئەوپەڕی $1 {{PLURAL:$1|ڕۆژە|ڕۆژە}})',
'recentchangescount'            => 'ژمارەی گۆڕانکارییەکان کە نیشان ئەدرێن لە حاڵەتی دیفاڵت:',
'prefs-help-recentchangescount' => 'ئەمە دوایین گۆڕانکارییەکان، مێژووی پەڕەکان و لۆگەکانیش لەبەردەگرێت.',
'savedprefs'                    => 'ھەڵبژاردەکانت پاشەکەوت کران',
'timezonelegend'                => 'ناوچەکات:',
'localtime'                     => 'کاتی ناوچەیی:',
'timezoneoffset'                => 'جیاوازی¹:',
'servertime'                    => 'کاتی server:',
'guesstimezone'                 => 'لە وێبگەڕەکە بیگرە',
'timezoneregion-africa'         => 'ئەفریقا',
'timezoneregion-america'        => 'ئەمریکا',
'timezoneregion-asia'           => 'ئاسیا',
'timezoneregion-atlantic'       => 'زەریای ئەتڵەسی',
'timezoneregion-australia'      => 'ئوستڕاڵیا',
'timezoneregion-europe'         => 'ئەورووپا',
'timezoneregion-indian'         => 'زەریای هندی',
'timezoneregion-pacific'        => 'زەریای هێمن',
'allowemail'                    => 'ڕێگە بدە بە بەکارھێنەرانی تر کە ئیمەیلم بۆ بنێرن',
'prefs-searchoptions'           => 'ھەڵبژاردەکانی گەڕان',
'prefs-namespaces'              => 'بۆشایی‌ناوەکان',
'defaultns'                     => 'ئەگینا لەم بۆشایی ناوانەدا بگەڕە:',
'default'                       => 'بنچینەیی',
'prefs-files'                   => 'پەڕگەکان',
'prefs-custom-css'              => 'CSSی دڵخواز',
'prefs-custom-js'               => 'JSی دڵخواز',
'prefs-emailconfirm-label'      => 'پشتڕاست کردنەوەی ئیمەیل:',
'prefs-textboxsize'             => 'قەبارەی پەنجەرەی گۆڕانکاری',
'youremail'                     => 'E-mail:',
'username'                      => 'ناوی به‌كارهێنه‌ر:',
'uid'                           => 'ژمارەی بەکارھێنەر:',
'prefs-memberingroups'          => 'ئەندامی {{PLURAL:$1|گرووپی|گرووپەکانی}}:',
'prefs-registration'            => 'کاتی خۆتۆمارکردن:',
'yourrealname'                  => 'ناوی ڕاستی:',
'yourlanguage'                  => 'زمان',
'yourvariant'                   => 'زاراوە:',
'yournick'                      => 'نازناو',
'badsig'                        => 'ئیمزاكه‌ هه‌ڵه‌یه‌، ته‌ماشای كۆدی HTML بكه‌‌',
'yourgender'                    => 'جنس:',
'gender-unknown'                => 'ئاشکرا نەکراو',
'gender-male'                   => 'پیاو',
'gender-female'                 => 'ژن',
'prefs-help-gender'             => 'دڵخواز: بۆ بانگ کردنی دروست بە دەستی نەرمامێر. 
ئەم زانیارییە گشتی ئەبێ.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'ناوی ڕاستی دڵخوازە.
ئەگەر پێت خۆش بێت بیدەی، زۆرتر ڕاتدەکێشێت بۆ کارەکانت.',
'prefs-help-email'              => 'ئەدرەسی e-mail دڵخوازە.
‏بەڵام ئەگەر تێپەڕوشەکەت لە بیر چوو، لە ڕێگەی e-mailەوە تێپەڕوشەیەکی نوێت بۆ دەنێردرێتەوە. ھەروەھا بە بەکارھێنەرانی دیکەش لە رێگەی e-mailەوە دەتوانن پەیوەندیت لەگەڵ گرن ئەگەر تۆ حەز بکەیت.',
'prefs-help-email-required'     => 'ناونیشانی ئیمەیل پێویستە.',
'prefs-info'                    => 'زانیاریی سەرەتایی',
'prefs-i18n'                    => 'نێونەتەویی کردن',
'prefs-signature'               => 'واژۆ',
'prefs-dateformat'              => 'ڕازاندنەوەی ڕێکەوت',
'prefs-advancedediting'         => 'ھەڵبژاردەکانی پێشکەوتوو',
'prefs-advancedrc'              => 'ھەڵبژاردەکانی پێشکەوتوو',
'prefs-diffs'                   => 'جیاوازییەکان',

# User rights
'userrights'               => 'بەڕێوەبردنی مافەکانی بەکارهێنەران',
'userrights-lookup-user'   => 'گرووپی بەکارهێنەران بەڕێوە ببە',
'userrights-user-editname' => 'ناوێکی بەکارهێنەر بنووسە',
'editusergroup'            => 'دەستکاری گرووپی بەکارهێنەران بکە',
'userrights-editusergroup' => 'دەستکاری گرووپی بەکارهێنەران بکە',
'saveusergroups'           => 'گرووپی بەکارهێنەران پاشەکەوت بکە',
'userrights-groupsmember'  => 'ئەندامە لە:',
'userrights-reason'        => 'هۆکاری گۆڕین:',

# Groups
'group'            => 'گرووپ:',
'group-user'       => 'بەکارهێنەران',
'group-bot'        => 'ڕۆبۆتەکان',
'group-sysop'      => 'بەڕێوبەران',
'group-bureaucrat' => 'بورووکراتەکان',
'group-all'        => '(هەموو)',

'group-user-member'  => 'بەکارھێنەر',
'group-bot-member'   => 'ڕۆبۆت',
'group-sysop-member' => 'بەڕێوەبەر',

'grouppage-user'  => '{{ns:project}}:بەکارھێنەران',
'grouppage-sysop' => '{{ns:project}}:بەڕێوبەران',

# Rights
'right-read'          => 'پەڕەکان بخوێنەوە',
'right-edit'          => 'دەستکاری پەڕەکان بکە',
'right-createtalk'    => 'پەڕەی گفتوگۆ دروست بکە',
'right-createaccount' => 'هەژماری نوێتی بەکارهێنەر دروست بکە',
'right-minoredit'     => 'دەستکارییەکان وەک بچوک دیاری بکە',
'right-move'          => 'پەڕەکان بگوێزەوە',
'right-movefile'      => 'پەڕگەکان بگوێزەوە',

# User rights log
'rightslog' => 'لۆگی مافەکانی بەکارهێنەر',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'دەستکاری ئەم پەڕەیە بکە',
'action-move'     => 'گواستنەوەی ئەم پەڕە',
'action-movefile' => 'ئەم پەڕگەیە بگوێزەوە',
'action-upload'   => 'ئەم پەڕەیە بار بکە',
'action-delete'   => 'ئەم پەڕەیە بسڕەوە',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|گۆڕانکاری|گۆڕانکاری}}',
'recentchanges'                  => 'دوایین گۆڕانکارییەکان',
'recentchanges-legend'           => 'هەڵبژاردنەکانی دوا گۆڕانکارییەکان',
'recentchanges-feed-description' => 'دوای دوایین گۆڕانکارییەکانی ئەم ویکیە بکەوە لەم feedەوە.',
'rcnote'                         => "لە خوارەوەدا {{PLURAL:$1|'''۱''' گۆڕانکاری |دوایین '''$1''' گۆڕانکارییەکان}} لە دوایین {{PLURAL:$2|ڕۆژ|'''$2''' ڕۆژەوە}} ، تا $5، $4 دەبینن.",
'rclistfrom'                     => 'گۆڕانکارییە نوێکان کە لە $1ەوە دەست پێدەکەن نیشان بدە.',
'rcshowhideminor'                => 'دەستکارییە بچووکەکان $1',
'rcshowhidebots'                 => 'بات $1',
'rcshowhideliu'                  => 'بەکارھێنەرە لە ژوورەکان $1',
'rcshowhideanons'                => 'بەکارھێنەرە نەناسراوەکان $1',
'rcshowhidepatr'                 => 'گۆرانکارییە کۆنترۆڵکراوەکان $1',
'rcshowhidemine'                 => 'دەستکارییەکانی من $1',
'rclinks'                        => 'دوایین $1 گۆڕانکارییەکانی دوایین $2 ڕۆژی <br />$3',
'diff'                           => 'جیاوازی',
'hist'                           => 'مێژوو',
'hide'                           => 'بشارەوە',
'show'                           => 'نیشان بدە',
'minoreditletter'                => 'ب',
'newpageletter'                  => 'ن',
'boteditletter'                  => 'بات',
'rc_categories_any'              => 'هەرکام',
'newsectionsummary'              => '/* $1 */ بەشی نوێ',
'rc-enhanced-expand'             => 'وردەکارییەکان پیشان بدە (پێویستی بە جاڤاسکریپتە)',
'rc-enhanced-hide'               => 'وردەکارییەکان بشارەوە',

# Recent changes linked
'recentchangeslinked'         => 'گۆڕانکارییە پەیوەندی‌دارەکان',
'recentchangeslinked-feed'    => 'گۆڕانکارییە پەیوەندی‌دارەکان',
'recentchangeslinked-toolbox' => 'گۆڕانکارییە پەیوەندی‌دارەکان',
'recentchangeslinked-title'   => 'گۆڕانکارییە پەیوەندیدارەکان بە "$1" ـەوە',
'recentchangeslinked-summary' => "Ev rûpela taybetî guherandinên dawî ji rûpelên lînkkirî nîşandide.
ئەو پەڕانە کە لە [[Special:Watchlist|لیستی چاودێڕییەکانت]]دان '''ئەستوورن'''",
'recentchangeslinked-page'    => 'ناوی پەڕە:',
'recentchangeslinked-to'      => 'نیشاندانی گۆڕانکارییەکانی ئەو پەڕگانە کە لینک دراون بەم پەڕگەوە',

# Upload
'upload'              => 'پەڕگەیەک بار بکە',
'uploadbtn'           => 'پەڕگە بار بکە',
'reupload'            => 'دیسان بار بکە',
'uploadnologin'       => 'لەژوورەوە نیت',
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
'uploadedfiles'       => 'پەڕگە بارکراوەکان',
'ignorewarnings'      => 'گوێ مەدە بە ئاگادارییەکان',
'uploadwarning'       => 'ئاگادارییەکانی بارکردن',
'savefile'            => 'پەڕگە پاشەکەوت بکە',
'uploadedimage'       => '"[[$1]]" بار کراو',
'overwroteimage'      => 'وەشانێ نوێی "[[$1]]" بار کرا',
'uploaddisabled'      => 'بارکردن قەدەخە کراوە',
'sourcefilename'      => 'ناوی پەڕگەی سەرچاوە:',
'destfilename'        => 'ناوی مەبەست:',
'upload-maxfilesize'  => 'ئەو پەری قەبارەی فایل: $1',
'watchthisupload'     => 'چاودێڕیی ئەم پەڕگە بکە',

'nolicense' => 'هیچ‌کام هەڵنەبژاردراوە',

# Special:ListFiles
'imgfile'               => 'پەڕگە',
'listfiles'             => 'لیستی پەرگەکان',
'listfiles_date'        => 'ڕێکەوت',
'listfiles_name'        => 'ناو',
'listfiles_user'        => 'بەکارھێنەر',
'listfiles_size'        => 'قەبارە',
'listfiles_description' => 'پەسن',
'listfiles_count'       => 'وەشانەکان',

# File description page
'file-anchor-link'          => 'پەڕگە',
'filehist'                  => 'مێژووی پەڕگە',
'filehist-help'             => 'بە کلیک کردن لەسەر بەروار/کات پەڕگە بەو شێوازە کە لەو کاتەدا بووە نیشان ئەدرێت.',
'filehist-deleteall'        => 'هەمووی بسڕەوە',
'filehist-current'          => 'هەنووکە',
'filehist-datetime'         => 'ڕێکەوت/کات',
'filehist-thumb'            => 'ھێما',
'filehist-thumbtext'        => 'ھێما بۆ وەشانی  $1',
'filehist-user'             => 'بەکارهێنەر',
'filehist-dimensions'       => 'دوورییەکان',
'filehist-filesize'         => 'قەبارەی پەڕگە',
'filehist-comment'          => 'لێدوان',
'imagelinks'                => 'بەستەرەکانی پەڕگە',
'linkstoimage'              => 'لەم {{PLURAL:$1|پەڕەی خوارەوە بەستەر دراوە|$1 پەڕەی خوارەوە بەستەر دراوە}} بۆ ئەم پەڕگە:',
'sharedupload'              => 'ئەم پەڕگە لە $1ەوەیە و لەوە دەچێ لە پرۆژەکانی دیکەش بەکار ببرێت.',
'uploadnewversion-linktext' => 'وەشانێکی نوێی ئەم پەڕەیە بار بکە',

# File deletion
'filedelete-submit' => 'بسڕەوە',

# MIME search
'download' => 'داگرتن',

# List redirects
'listredirects' => 'لیستی ئاڕاستەکراوەکان',

# Unused templates
'unusedtemplates'    => 'قاڵبە بە کار نەھێراوەکان',
'unusedtemplateswlh' => 'بەستەرەکانی تر',

# Random page
'randompage' => 'پەڕەیەک بە ھەرەمەکی',

# Statistics
'statistics'                   => 'ئامارەکان',
'statistics-header-pages'      => 'ئامارەکانی پەڕەکان',
'statistics-header-edits'      => 'ئامارەکانی گۆڕانکارییەکان',
'statistics-header-views'      => 'ئامارەکانی سەردانەکان',
'statistics-header-users'      => 'ئامارەکانی بەکارھێنەران',
'statistics-articles'          => 'پەڕە بە ناوەڕۆکەکان',
'statistics-pages'             => 'پەڕەکان',
'statistics-files'             => 'پەڕگە بارکراوەکان',
'statistics-users-active'      => 'ئەندامە چالاکەکان',
'statistics-users-active-desc' => 'ئەو بەکارھێنەرانە کە لە دوایین {{PLURAL:$1|ڕۆژ|$1 ڕۆژ}}دا کارێکیان جێبەجێ کربێت.',

'disambiguations' => 'پەڕەکانی جوداکردنەوە',

'brokenredirects-edit'   => 'دەستکاری',
'brokenredirects-delete' => 'سڕینەوە',

'withoutinterwiki'        => 'پەڕەکان کە بەستەرەکانی زمانیان نییە',
'withoutinterwiki-legend' => 'پێشگر',
'withoutinterwiki-submit' => 'پیشاندان',

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
'prefixindex'             => 'ھەموو پەڕەکان بە prefix ەوە',
'shortpages'              => 'پەڕە کورتەکان',
'longpages'               => 'پەڕە دڕێژەکان',
'newpages'                => 'پەڕە نوێکان',
'newpages-username'       => 'ناوی بەکارھێنەر:',
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
'specialloguserlabel'  => 'بەکارھێنەر:',
'speciallogtitlelabel' => 'ناونیشان:',
'log'                  => 'لۆگەکان',

# Special:AllPages
'allpages'       => 'ھەموو پەڕەکان',
'alphaindexline' => '$1 تا $2',
'nextpage'       => 'پەڕەی پاشەوە ($1)',
'prevpage'       => 'پەڕەی پێشەوە ($1)',
'allpagesfrom'   => 'بینینی پەڕەکان بە دەست پێ کردن لە:',
'allpagesto'     => 'بینینی پەڕەکان بە دوایی ھاتن بە:',
'allarticles'    => 'ھەمووی وتارەکان',
'allpagesprev'   => 'پێش',
'allpagesnext'   => 'پاش',
'allpagessubmit' => 'بڕۆ',

# Special:Categories
'categories' => 'ھاوپۆلەكان',

# Special:DeletedContributions
'deletedcontributions' => 'بەشدارییە بەکارھێنەریە سڕاوەکان',

# Special:LinkSearch
'linksearch'    => 'بەستەرە دەرەکییەکان',
'linksearch-ok' => 'گەڕان',

# Special:Log/newusers
'newuserlogpage'          => 'لۆگی دروست کردنی بەکارھێنەر',
'newuserlog-create-entry' => 'بەکارھێنەری نوێ',

# Special:ListGroupRights
'listgrouprights'         => 'مافەکانی گرووپە بەکارھێنەرییەکان',
'listgrouprights-group'   => 'گرووپ',
'listgrouprights-rights'  => 'مافەکان',
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
'addedwatchtext'       => 'پەڕەی "[[:$1]]" خرایە سەر [[Special:Watchlist|لیستی جاودێڕی]]ەکەت.
گۆڕانکارییەکانی داھاتووی ئەم پەڕە و پەڕەی وتووێژەکەی، لەوێدا بە ڕیز دەکرێ و پەڕەکە لە [[Special:RecentChanges|لیستی دواییین گۆڕانکارییەکان]] دا ئەستوور  کراو دەردەکەوێت بۆ ئەوەی ئاسانتر دەسکەوێت.',
'removedwatch'         => 'لە لیستی چاودێڕییەکانت لابرا',
'removedwatchtext'     => 'پەڕەی "[[:$1]]" لە [[Special:Watchlist|لیستی چاودێڕیەکانت]] لابرا.',
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
'changed'      => 'گۆڕدراو',
'created'      => 'دروستکراو',
'enotif_body'  => '
بەڕێز $WATCHINGUSERNAME،
پەڕەی $PAGETITLE لە {{SITENAME}} دا لە ڕێکەوتی $PAGEEDITDATE بە دەستی $PAGEEDITOR $CHANGEDORCREATED کراوە، سەردانی $PAGETITLE_URL بکە بۆ وەشانی ھەنووکەی ئەو پەڕە.

$NEWPAGE

پوختەی دەستکارییەکەی: $PAGESUMMARY $PAGEMINOREDIT

پەیوەندی لەگەڵ دەستکاریکەر: 
نامە: $PAGEEDITOR_EMAIL
ویکی: $PAGEEDITOR_WIKI

تا سەردانی ئەو پەڕە نەکەی، ئەگەر گۆڕانکارییەکی تری تێدا ڕووی دا خەبەر پێ نادرێ.
You could also reset the notification flags for all your watched pages on your watchlist.


             بە سوپاسەوە، سیستەمی ڕاگەیاندنی {{SITENAME}}
--
بۆ گۆڕینی تەنزیماتی لیستی چاودێڕییەکانت، سەربدە لە
{{fullurl:{{ns:special}}:Watchlist/edit}}

رێنوێنیی زۆرتر و دەربڕینی بیروڕا:
{{fullurl:{{MediaWiki:Helppage}}}}',

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
'confirmdeletetext'     => 'تۆ خەریکی پەڕەیەک بە هەموو مێژووەکەیەوە دەسڕیتەو.
تکایە دووپاتی بکەوە کە دەتەوێت ئەم کارە بکەی، لە ئاکامەکەی تێدەگەی، و ئەم کارە بە پێی [[{{MediaWiki:Policy-url}}|سیاسەتنامە]] ئەنجام ئەدەی.',
'actioncomplete'        => 'چالاکی دوایی ھاو.',
'deletedtext'           => '"<nowiki>$1</nowiki>"  سڕایەوە.
سەیری $2 بکە بۆ تۆمارێکی دوایین سڕینەوەکان.',
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
'protect-locked-access'       => "ھەژمارەکەت ڕێگەی ئەوەی پێ نەدراوە کە بتوانێت ئاستی پاراستنی پەڕە بگۆڕێت.
ڕێککارییەکانی ئێستای پەڕەی '''$1''' ئەمەتە:",
'protect-cascadeon'           => 'ئەم لاپەڕە لە حاڵی ئێستا دا پارێزراوە چونکا لە نێو ئەم {{PLURAL:$1|لاپەڕ(ان)ە دایە کە }} حاڵەتی پاراستنی تاڤگەیی ئەو(ان) ھەڵکراوە

تۆ دەتوانی ئاستی پاراستنی ئەم لاپەڕە بگۆڕی، بەڵام ئەم گۆڕانە ھیچ کاریگەر نابێت لە سەر پاراستنی تاڤگەیی',
'protect-default'             => 'بە ھەموو بەکارھێنەران ڕێگە بدە',
'protect-fallback'            => 'پێویستی بە ئیزنی «$1» ھەیە',
'protect-level-autoconfirmed' => 'بەکارھێنەرانی نوێ و تۆمارنەکراو ئاستەنگ بکە',
'protect-level-sysop'         => 'تەنھا بەڕێوەبەران',
'protect-summary-cascade'     => 'تاڤگەیی',
'protect-expiring'            => 'لەم بەروارە بەسەر دەچێت $1 (UTC)',
'protect-cascade'             => 'لاپەڕەکانی نێو ئەم لاپەتە بپارێزە (پاراستنی تاڤگەیی)',
'protect-cantedit'            => 'ناتوانی ئاستی پاراستنی ئەم پەڕە بگۆڕی، چونکوو تۆ ئیجازەی ئەم کارەت نیە.',
'restriction-type'            => 'ئیزن:',
'restriction-level'           => 'ئاستی سنووردارکردن:',
'minimum-size'                => 'کەمترین قەبارە',
'maximum-size'                => 'زۆرترین قەبارە:',

# Restrictions (nouns)
'restriction-edit'   => 'دەستکاری',
'restriction-move'   => 'گواستنەوە',
'restriction-create' => 'دروستکردن',
'restriction-upload' => 'بارکردن',

# Restriction levels
'restriction-level-all' => 'هەر ئاستێک',

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
'uctop'               => '(سەر)',
'month'               => 'لە مانگی (و پێشترەوە):',
'year'                => 'لە ساڵی (و پێشترەوە):',

'sp-contributions-newbies'     => 'تەنھا بەشدارییەکانی بەکارھێنەرە تازەکان نیشان بدە',
'sp-contributions-newbies-sub' => 'لە بەکارھێنەرە تازەکانەوە',
'sp-contributions-blocklog'    => 'لۆگی بەربەستن',
'sp-contributions-deleted'     => 'بەشدارییە سڕاوەکان',
'sp-contributions-logs'        => 'تۆمارەکان',
'sp-contributions-talk'        => 'وتە',
'sp-contributions-search'      => 'گەڕین بۆ بەشدارییەکان',
'sp-contributions-username'    => 'ئەدرەسی IP یان بەکارھێنەر:',
'sp-contributions-submit'      => 'بگەڕە',

# What links here
'whatlinkshere'            => 'بەسراوەکان بە ئێرەوە',
'whatlinkshere-title'      => 'ئەو پەڕانەی بەستەرکراون بۆ "$1"',
'whatlinkshere-page'       => 'پەڕە:',
'linkshere'                => "ئەم پەڕانە بەستەریان ھەیە بۆ '''[[:$1]]''':",
'isredirect'               => 'پەڕە ئاڕاستە بکە',
'istemplate'               => 'بەکارھێنراو',
'isimage'                  => 'بەستەری وێنە',
'whatlinkshere-prev'       => '{{PLURAL:$1|پێشتر|$1 ی پێشتر}}',
'whatlinkshere-next'       => '{{PLURAL:$1|دیکە|$1 ی دیکە}}',
'whatlinkshere-links'      => '← بەستەرەکان',
'whatlinkshere-hideredirs' => '$1 ئاڕاستەکراو هەیە',
'whatlinkshere-hidetrans'  => 'ترانسکلوژنه‌کانی $1',
'whatlinkshere-hidelinks'  => '$1 بەستەر',
'whatlinkshere-filters'    => 'پاڵێوەرەکان',

# Block/unblock
'blockip'                  => 'بەکارھێنەر ئاستەنگ بکە',
'ipaddress'                => 'ناونیشانی IP:',
'ipbexpiry'                => 'بەسەرچوون:',
'ipbreason'                => 'هۆکار:',
'ipbreasonotherlist'       => 'هۆکاری تر',
'ipboptions'               => '2 کاتژمێر:2 hours,1 ڕۆژ:1 day,3 ڕۆژ:3 days,1 ھەفتە:1 week,2 ھەفتە:2 weeks,1 مانگ:1 month,3 مانگ:3 months,6 مانگ:6 months,1 ساڵ:1 year,بێ سنوور:infinite',
'ipbotheroption'           => 'دیکە',
'ipblocklist'              => 'لیستی بەکارھێنەر و IP ئەدرەسە بەربەستراوەکان',
'blocklink'                => 'بەربەستن',
'unblocklink'              => 'لابردنی ئاستەنگ',
'change-blocklink'         => 'گۆڕاندنی ئاستەنگ',
'contribslink'             => 'بەشداری',
'blocklogpage'             => 'لۆگی بلۆککردن',
'blocklogentry'            => '[[$1]] ئاستەنگ کرا بۆ ماوەی $2 $3',
'reblock-logentry'         => 'دۆخی ئاستەنگ کردنی [[$1]]  بۆ گۆڕدرا بۆ ماوەی $2 $3',
'unblocklogentry'          => 'بەربەستنی "$1" بەتاڵ کرا',
'block-log-flags-nocreate' => 'دروستکردنی هەژمار ناچالاککراوە',

# Move page
'move-page'             => '$1 بگوێزەوە',
'move-page-legend'      => 'پەرە بگوێزەوە',
'movepagetext'          => "لە ڕێگەی ئەم فۆرمەی خوارەوە ناوی پەڕە دەگۆڕدرێت، وە ھەموو مێژووەکەی دەگوازێتەوە بۆ ناوی نوێ.
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
'movepagetalktext'      => "لاپه‌ڕه‌ی وتوبێژی پێوه‌ندیدار به‌ شێوی خۆکار له‌گه‌ڵی ده‌گوازرێته‌وه‌ '''مه‌گه‌ر:'''
* لاپه‌ڕه‌یه‌کی وتوبێژی تێدانووسراو له‌ ژێر ناوه‌ نوێکی هه‌بێت، یان
* تۆ ئه‌م بۆکسه‌ی خواره‌وه‌ لێ نه‌ده‌ی

له‌و حاڵه‌تانه‌دا، ئه‌گه‌ر بته‌وێت بیگوازیته‌وه‌ ناچار ده‌بیت به‌ شێوه‌ی ده‌ستی بیگوازیته‌وه‌ یان هه‌ردوکیان تێکه‌ڵ بکه‌ی.",
'movearticle'           => 'ئەم پەڕە بگوازەوە:',
'movenologin'           => 'نەچوویتەتە ژوورەوە',
'movenologintext'       => 'بۆ گواستنەوەی پەڕەیەک، ئەشێ ببی بە ئەندام و [[Special:UserLogin|لە ژوورەوە]] بیت.',
'newtitle'              => 'بۆ ناوی نوێی:',
'move-watch'            => 'ئەم پەڕە چاودێری بکە',
'movepagebtn'           => 'ئەم پەڕە بگوازەوە',
'pagemovedsub'          => 'گواستنەوە بە سەرکەوتوویی جێبەجێ کرا',
'movepage-moved'        => '<big>\'\'\'"$1" گوازراوەتەوە بۆ "$2"\'\'\'</big>',
'articleexists'         => 'لاپەڕەیەک بەم ناوە ھەیە، یان ئەو ناوەی تۆ ھەڵتبژاردووە بایەخدار نیە.
تکایە ناوێکی دیکە ھەڵبژێرە',
'talkexists'            => "'''خودی پەڕەکە گۆزرایەوە بەڵام پەڕەی ووتووێژەکەی ناگۆزرێتەوە چونکو بەو ناوەوە یەکێک ھەیە 
تکایە بە بە دەستی خۆتان ئەو دووانە تێکەڵ کەن.'''",
'movedto'               => 'بوو بە',
'movetalk'              => 'پەڕەی گوفتوگۆکەشی بگۆزەرەوە',
'move-subpages'         => 'ھەموو ژێرپەڕەکانیشی (بە ئەندازەی $1) بگۆزەرەوە، ئەگەر بیبێت',
'move-talk-subpages'    => 'ھەموو ژێرپەڕەکانی (بە ئەندارەی $1) پەڕەی گوفتوگۆکەشی بگۆزەرەوە، ئەگەر بیبێت',
'movepage-page-exists'  => 'پەڕەی $1 هەیە و ناتوانرێت خۆکار بخرێتە جێی.',
'movepage-page-moved'   => 'پەڕەی $1 گۆزرایەوە بۆ $2.',
'movepage-page-unmoved' => 'ناکرێ پەڕەی $1 بگوێزرێتەوە بۆ $2.',
'1movedto2'             => '[[$1]] گۆزرایەوە بۆ [[$2]]',
'1movedto2_redir'       => 'بە ڕەوانکردنەوە، [[$1]] گۆزرایەوە بۆ [[$2]]',
'movelogpage'           => 'لۆگی گواستنەوە',
'movelogpagetext'       => 'لە خوارەوەدا لیستی ھەموو پەڕە گواستنەوەکان دەبینن.',
'movereason'            => 'بە ھۆی:',
'revertmove'            => 'پێچەوانەکردنەوە',
'delete_and_move'       => 'بیسڕەوە و بیگوازەوە',
'move-leave-redirect'   => 'ڕەوانکردنەوەیەک دابنە بۆ پەڕە نوێکە',

# Export
'export' => 'ھەناردنی پەڕەکان',

# Namespace 8 related
'allmessages'        => 'پەیامەکانی سیستەم',
'allmessagesname'    => 'ناو',
'allmessagesdefault' => 'دەقی بنەڕەتی',
'allmessagescurrent' => 'دەقی ھەنووکە',

# Thumbnails
'thumbnail-more' => 'گەورە کردنەوە',

# Special:Import
'import-interwiki-submit' => 'هاوردن',

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
'tooltip-ca-unwatch'              => 'ئەم پەڕە لە لیستی چاودێڕییەکەت لابە',
'tooltip-search'                  => 'لە {{SITENAME}} بگەڕێ',
'tooltip-search-go'               => 'بڕۆ بۆ پەڕەیەک کە بە تەواوەتی ئەم ناوەی تیادایە ئەگەر هەبێت',
'tooltip-search-fulltext'         => 'لە پەڕەکاندا بگەڕێ بۆ ئەم دەقە',
'tooltip-n-mainpage'              => 'بینینی پەڕەی دەستپێک',
'tooltip-n-portal'                => 'دەربارەی پڕۆژەکە، چی ئەتوانی بکەیت، لە کوێ شتەکان بدۆزیتەوە',
'tooltip-n-currentevents'         => 'زانیاری پێشینە بەدەست بھێنە دەربارەی بۆنە ھەنووکەییەکان',
'tooltip-n-recentchanges'         => 'لیستی دوایین گۆڕانکارییەکان لەم ویکییەدا',
'tooltip-n-randompage'            => 'پەڕەیەکی ڕەمەکی نیشان بدە',
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
'tooltip-t-permalink'             => 'گرێدەری ھەمیشەیی بۆ ئەم وەشانەی ئەم پەڕەیە',
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
'tooltip-diff'                    => 'نیشان دانی گۆڕانکارییەکانت لە دەقەکەدا',
'tooltip-compareselectedversions' => 'جیاوازییەکانی دوو وەشانە دیاریکراوەی ئەم پەڕە ببینە.',
'tooltip-watch'                   => 'ئەم پەڕە بخەرە سەر لیستی چاودێڕیەکەت',
'tooltip-rollback'                => "''گەڕاندنەوە'' بە یەک کلیک گۆڕانکاری (گۆڕانکارییەکانی) ئەم پەڕە ئەباتەوە بۆ ھی دواین بەشدار",
'tooltip-undo'                    => '"پاشگەزبوونەوە"  گۆڕانکارییەکان دەگەڕەنێتەوە و فۆرمی دەستکاری کردن لە حاڵەتی پێشبینین دەکاتەوە. بەم شێوە دەکرێ ھۆکارێک لە بەشی پوختە دا بنووسرێت.',

# Attribution
'lastmodifiedatby' => 'ئەم پەڕە دواجار لە $2ی $1 بە دەستی $3 گۆڕدراوە.',

# Info page
'numedits' => 'ژمارەی دەستکارییەکان (پەڕە): $1',

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
'mw_math_source' => 'وەک TeX بمێنێتەوە (بۆ وێبگەڕە دەقی‌یەکان)',
'mw_math_modern' => 'بۆ وێبگەڕە مۆدێڕنەکان باشترە',

# Math errors
'math_unknown_error'    => 'هەڵەیەکی نەزانراو',
'math_unknown_function' => 'فەرمانێکی نەناسراو',
'math_syntax_error'     => 'ڕستەکار هەڵەیە',

# Browsing diffs
'previousdiff' => '← دەستکاری کۆنتر',
'nextdiff'     => 'دەستکاری نوێتر →',

# Media information
'thumbsize'            => 'قەبارەی Thumbnail:',
'file-info-size'       => '($1 × $2 پیکسێل، قەبارەی پەڕگە: $3، جۆری MIME: $4)',
'file-nohires'         => '<small>رەزۆلوشنی بانتر لە بەردەست دا نیە.</small>',
'svg-long-desc'        => '(پەڕگەی SVG، بە ناو $1 × $2 خاڵ، قەبارەی پەڕگە: $3)',
'show-big-image'       => 'گەورە کردنەوە',
'show-big-image-thumb' => '<small>قەبارەی ئەم پێشبینینە: $1 × $2 خاڵە</small>',

# Special:NewFiles
'newimages'        => 'پێشانگای پەڕگە نوێکان',
'newimages-legend' => 'پاڵاوتن',
'ilsubmit'         => 'گەڕان',
'bydate'           => 'بەپێی ڕێکەوت',

# Bad image list
'bad_image_list' => 'فۆرمات بەم شێوەی خوارەوەیە:

تەنھا ئەو بابەتانەی کە کە لیست کراون (واتە ئەو دێڕانەی بە * دەست پێ دەکەن) لێک ئەدرێتەوە.
یەکەم بەستەر لە سەر دێڕێک دەبێت بەستەری فایلێکی خراپ بێت.
ھەموو بەستەرەکانی دوای ئەو کە لەسەر ھەمان دێڕن وەکوو نائاسایی دێتە ھەژمار، واتە ئەو لاپەڕانەی کە ڕەنگە تێدا فایل بە شێوەی ئینلاین بێت',

# Variants for Kurdish language
'variantname-ku-arab' => 'ئەلفوبێی عەرەبی',
'variantname-ku-latn' => 'ئەلفوبێی لاتینی',

# Metadata
'metadata'          => 'دراوی مێتا',
'metadata-help'     => 'ئەم پەڕگە زانیاری زێدەی ھەیە، کە لەوە دەچێت کامێرا یان ھێماگر (scanner) خستبێتیە سەری. ئەگەر پەڕگەکە لە حاڵەتی سەرەتاییەکەیەوە دەستکاری کرابێ، شایەد بڕێ لە بڕگەکان بە تەواوی زانیارەکانی وێنە گۆڕدراوەکە نیشان نەدەن.',
'metadata-expand'   => 'وردەکارییە درێژکراوەکان پیشان بدە',
'metadata-collapse' => 'وردەکارییە درێژکراوەکان بشارەوە',
'metadata-fields'   => 'ئەو کێڵگە EXIFانە لەم پەیامە بە ڕیز کراون، کاتێک خشتەی metadata کۆ کراوەش بێ ھەر نیشان ئەدرێت. کێڵگەکانی تر تا خشتەکە باز نەکرێ، شاراوەن.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'       => 'پانی',
'exif-imagelength'      => 'بەرزی',
'exif-imagedescription' => 'ناونیشانی وێنە',
'exif-model'            => 'جۆری کامێرا',
'exif-artist'           => 'نووسەر',
'exif-filesource'       => 'سەرچاوەی پەڕگە',
'exif-saturation'       => 'تێربوون',
'exif-gpslatitude'      => 'پانی',
'exif-gpslongitude'     => 'درێژی',
'exif-gpsmeasuremode'   => 'جۆری پێوان',
'exif-gpsdop'           => 'وردی پێوان',
'exif-gpsspeedref'      => 'یەکەی خێرایی',
'exif-gpsspeed'         => 'خێرایی وەرگری GPS',
'exif-gpstrack'         => 'ئاڕاستەی جوڵان',
'exif-gpsimgdirection'  => 'ئاڕاستەی وێنە',
'exif-gpsdatestamp'     => 'ڕێکەوتی GPS',

# EXIF attributes
'exif-compression-1' => 'نەپەستێنراو',

'exif-unknowndate' => 'ڕێکەوتی نەزانراو',

'exif-orientation-1' => 'ئاسایی',
'exif-orientation-2' => 'ئاسۆیی هەڵگێڕدراوەتەوە',
'exif-orientation-3' => '١٨٠° سوڕاوەتەوە',
'exif-orientation-4' => 'ستوونی هەڵگێڕدراوەتەوە',

'exif-componentsconfiguration-0' => 'بوونی نییە',

'exif-exposureprogram-1' => 'دەستکار',

'exif-meteringmode-0'   => 'نەزانراو',
'exif-meteringmode-1'   => 'تێکڕا',
'exif-meteringmode-5'   => 'لەوحە',
'exif-meteringmode-6'   => 'بەش بەش',
'exif-meteringmode-255' => 'هیتر',

'exif-lightsource-0' => 'نەزانراو',
'exif-lightsource-4' => 'فلاش',

'exif-focalplaneresolutionunit-2' => 'ئینج',

'exif-sensingmethod-1' => 'دیاری نەکراو',

'exif-scenecapturetype-0' => 'ستاندارد',

'exif-gaincontrol-0' => 'هیچ',

'exif-contrast-0' => 'ئاسایی',
'exif-contrast-1' => 'نەرم',
'exif-contrast-2' => 'ڕەق',

'exif-saturation-0' => 'ئاسایی',

'exif-sharpness-0' => 'ئاسایی',
'exif-sharpness-1' => 'نەرم',
'exif-sharpness-2' => 'ڕەق',

'exif-subjectdistancerange-0' => 'نەزانراو',
'exif-subjectdistancerange-1' => 'گەورە',

# External editor support
'edit-externally'      => 'دەستکاری ئەم پەڕەیە بکە بە بەکارهێنانی پڕۆگرامێکی دەرەکی',
'edit-externally-help' => '(بۆ زانیاریی زیاتر سەیری [http://www.mediawiki.org/wiki/Manual:External_editors  ڕێنماییەکانی دامەزراندن] بکە)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ھەموو',
'imagelistall'     => 'ھەموو',
'watchlistall2'    => 'ھەموو',
'namespacesall'    => 'ھەموو',
'monthsall'        => 'ھەموو',

# action=purge
'confirm_purge_button' => 'باشە',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator'     => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '← پەڕەی پێشوو',
'imgmultipagenext' => 'پەڕەی داهاتوو →',
'imgmultigo'       => 'بڕۆ!',
'imgmultigoto'     => 'بڕۆ بۆ پەڕەی $1',

# Table pager
'table_pager_next'         => 'پەڕەی داهاتوو',
'table_pager_prev'         => 'پەڕەی پێشوو',
'table_pager_first'        => 'پەرەی یەکەم',
'table_pager_last'         => 'دوا پەڕە',
'table_pager_limit'        => '$1 دانە پیشان بدە بۆ هەر پەڕەیەک',
'table_pager_limit_submit' => 'بڕۆ',
'table_pager_empty'        => 'هیچ ئەنجامێک نییە',

# Live preview
'livepreview-loading' => 'له‌باركردنایه‌ ...',
'livepreview-ready'   => 'ئاماده‌یه‌',

# Watchlist editor
'watchlistedit-numitems'      => 'بێجگە لە پەڕەی وتووێژەکان، لیستی چاودێڕییەکانت {{PLURAL:$1|1 بابەت|$1 بابەت}}ی تێدایە،',
'watchlistedit-noitems'       => 'لیستی چاودێڕییەکانت ھیچ بابەتێکی تێدا نییە.',
'watchlistedit-normal-submit' => 'ناونیشانەکان لاببە',
'watchlistedit-raw-titles'    => 'ناونیشانەکان:',

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
'version'                  => 'وەشان',
'version-extensions'       => 'پێوەکراوە دامەزراوەکان',
'version-specialpages'     => 'پەڕە تایبەتەکان',
'version-variables'        => 'گۆڕاوەکان',
'version-other'            => 'هیتر',
'version-license'          => 'مۆڵەت',
'version-software'         => 'نەرمەکاڵای دامەزراو',
'version-software-product' => 'بەرهەم',
'version-software-version' => 'وەشان',

# Special:FilePath
'filepath'        => 'ڕێڕەوی پەڕگە',
'filepath-page'   => 'پەڕگە:',
'filepath-submit' => 'ڕێڕەو',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'گەڕان بۆ پەڕگە دووپات کراوەکان',
'fileduplicatesearch-filename' => 'ناوی پەرگە:',
'fileduplicatesearch-submit'   => 'گەڕان',

# Special:SpecialPages
'specialpages'                 => 'پەڕە تایبەتەکان',
'specialpages-group-other'     => 'پەڕە تایبەتەکانی دیکە',
'specialpages-group-login'     => 'چوونە ژوورەوە/ناونووسین',
'specialpages-group-changes'   => 'دوایین گۆڕانکارییەکان و ڕەشنووسەکان',
'specialpages-group-media'     => 'گوزارشتەکان و بارکردنەکانی مێدیا',
'specialpages-group-users'     => 'بەکارھێنەران و مافەکان',
'specialpages-group-highuse'   => 'پەڕە زۆر بەکار ھێنراوەکان',
'specialpages-group-pages'     => 'لیستەکانی پەڕەکان',
'specialpages-group-pagetools' => 'ئامرازەکانی پەڕە',
'specialpages-group-wiki'      => 'داتا و ئامرازەکانی ویکی',
'specialpages-group-redirects' => 'پەڕە تایبەتەکانی رەوانکردنەوە',
'specialpages-group-spam'      => 'ئامرازەکانی سپەم',

# Special:BlankPage
'blankpage' => 'پەڕەی واڵا',

# Special:Tags
'tag-filter-submit' => 'پاڵاوتن',
'tags-title'        => 'تاگەکان',
'tags-edit'         => 'دەستکاری',

# HTML forms
'htmlform-reset' => 'گەڕانەوەی گۆڕانکاری',

);
