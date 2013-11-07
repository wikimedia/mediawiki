<?php
/** Sorani Kurdish (کوردی)
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
 * @author Calak
 * @author Cyrus abdi
 * @author Diyar se
 * @author Haval
 * @author Marmzok
 * @author Muhammed taha
 * @author رزگار
 */

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'میدیا',
	NS_SPECIAL          => 'تایبەت',
	NS_MAIN             => '',
	NS_TALK             => 'وتووێژ',
	NS_USER             => 'بەکارھێنەر',
	NS_USER_TALK        => 'لێدوانی_بەکارھێنەر',
	NS_PROJECT_TALK     => 'لێدوانی_$1',
	NS_FILE             => 'پەڕگە',
	NS_FILE_TALK        => 'وتووێژی_پەڕگە',
	NS_MEDIAWIKI        => 'میدیاویکی',
	NS_MEDIAWIKI_TALK   => 'وتووێژی_میدیاویکی',
	NS_TEMPLATE         => 'داڕێژە',
	NS_TEMPLATE_TALK    => 'وتووێژی_داڕێژە',
	NS_HELP             => 'یارمەتی',
	NS_HELP_TALK        => 'وتووێژی_یارمەتی',
	NS_CATEGORY         => 'پۆل',
	NS_CATEGORY_TALK    => 'وتووێژی_پۆل',
);

$namespaceAliases = array(
	'لێدوان'            => NS_TALK,
	'قسەی_بەکارھێنەر'   => NS_USER_TALK,
	'لێدوانی_پەڕگە'     => NS_FILE_TALK,
	'لێدوانی_میدیاویکی' => NS_MEDIAWIKI_TALK,
	'قاڵب'              => NS_TEMPLATE,
	'لێدوانی_قاڵب'      => NS_TEMPLATE_TALK,
	'لێدوانی_داڕێژە'    => NS_TEMPLATE_TALK,
	'لێدوانی_یارمەتی'   => NS_HELP_TALK,
	'لێدوانی_پۆل'       => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'بەکارھێنەرە_چالاکەکان' ),
	'Allmessages'               => array( 'ھەموو_پەیامەکان' ),
	'Allpages'                  => array( 'ھەموو_پەڕەکان' ),
	'Ancientpages'              => array( 'پەڕە_کۆنەکان' ),
	'Blankpage'                 => array( 'پەڕەی_واڵا' ),
	'Booksources'               => array( 'سەرچاوەکانی_کتێب' ),
	'BrokenRedirects'           => array( 'ڕەوانکەرە_شکاوەکان' ),
	'Categories'                => array( 'پۆلەکان' ),
	'ChangePassword'            => array( 'تێپەڕوشەگۆڕان،_تێپەڕەوشە_ڕێکخستنەوە' ),
	'Confirmemail'              => array( 'بڕواکردن_ئیمەیل' ),
	'Contributions'             => array( 'بەشدارییەکان' ),
	'CreateAccount'             => array( 'دروستکردنی_ھەژمار' ),
	'Deadendpages'              => array( 'پەڕە_بەربەستراوەکان' ),
	'DoubleRedirects'           => array( 'ڕەوانکەرە_دووپاتکراوەکان' ),
	'Emailuser'                 => array( 'ئیمەیل_بەکارھێنەر' ),
	'Export'                    => array( 'ھەناردن' ),
	'Fewestrevisions'           => array( 'کەمترین__پێداچوونەوەکان' ),
	'Import'                    => array( 'ھاوردن' ),
	'Listadmins'                => array( 'لیستی_بەڕێوبەران' ),
	'Listbots'                  => array( 'لیستی_بۆتەکان' ),
	'Listfiles'                 => array( 'لیستی_پەڕگەکان' ),
	'Listusers'                 => array( 'لیستی_بەکارھێنەران' ),
	'Log'                       => array( 'لۆگ' ),
	'Lonelypages'               => array( 'پەڕە_تاکەکان،_پەڕە_ھەتیوکراوەکان' ),
	'Longpages'                 => array( 'پەڕە_درێژەکان' ),
	'Mostcategories'            => array( 'زیاترین_پۆلەکان' ),
	'Mostimages'                => array( 'پەڕگەکانی_زیاترین_بەستەردراون،_زیاترین_پەڕگەکان،_زیاترین_وێنەکان' ),
	'Mostlinked'                => array( 'پەڕەکانی_زیاترین_بەستەردراون،_زیاترین_بەستەردراون' ),
	'Mostlinkedcategories'      => array( 'پۆلەکانی_زیاترین_بەستەردراون،_پۆلەکانی_زیاترین_بەکارھێنراون' ),
	'Mostlinkedtemplates'       => array( 'داڕێژەکانی_زیاترین_بەستەردراون،_داڕێژەکانی_زیاترین_بەکارھێنراون' ),
	'Mostrevisions'             => array( 'زیاترین_پێداچوونەوەکان' ),
	'Movepage'                  => array( 'پەڕە_گواستنەوە' ),
	'Mycontributions'           => array( 'بەشدارییەکانم' ),
	'Mypage'                    => array( 'پەڕەکەم' ),
	'Mytalk'                    => array( 'لێدوانەکانم' ),
	'Newimages'                 => array( 'پەڕگە_نوێکان' ),
	'Newpages'                  => array( 'پەڕە_نوێکان' ),
	'Popularpages'              => array( 'پەڕە_ناودارەکان' ),
	'Preferences'               => array( 'ھەڵبژاردەکان' ),
	'Protectedpages'            => array( 'پەڕە_پارێزراوەکان' ),
	'Protectedtitles'           => array( 'بابەتە_پارێزراوەکان' ),
	'Randompage'                => array( 'ھەڵکەوت،پەڕەی_بە_ھەرمەکی' ),
	'Recentchanges'             => array( 'دوایین_گۆڕانکارییەکان' ),
	'Search'                    => array( 'گەڕان' ),
	'Shortpages'                => array( 'پەڕە‌_کورتەکان' ),
	'Specialpages'              => array( 'پەڕە_تایبەتەکان' ),
	'Statistics'                => array( 'ئامارەکان' ),
	'Unblock'                   => array( 'کردنەوە' ),
	'Uncategorizedcategories'   => array( 'پۆلە_پۆلێننەکراوەکان' ),
	'Uncategorizedimages'       => array( 'پەڕگە_پۆلێننەکراوەکان،_وێنە_پۆلێننەکراوەکان' ),
	'Uncategorizedpages'        => array( 'پەڕە_پۆلێننەکراوەکان' ),
	'Uncategorizedtemplates'    => array( 'داڕێژە_پۆلێننەکراوەکان' ),
	'Unusedcategories'          => array( 'پۆلە_بەکارنەھێنراوەکان' ),
	'Unusedimages'              => array( 'پەڕگە_بەکارنەھێنراوەکان،_وێنە_بەکارنەھێنراوەکان' ),
	'Upload'                    => array( 'بارکردن' ),
	'Userlogin'                 => array( 'چوونەژوورەوەی_بەکارھێنەر' ),
	'Version'                   => array( 'وەشان' ),
	'Wantedcategories'          => array( 'پۆلە_پێویستەکان' ),
	'Wantedfiles'               => array( 'پەڕگە_پێویستەکان' ),
	'Wantedpages'               => array( 'پەڕە_پێویستەکان،_بەستەرە_شکاوەکان' ),
	'Wantedtemplates'           => array( 'داڕێژە_پێویستەکان' ),
	'Watchlist'                 => array( 'لیستی_چاودێری' ),
	'Whatlinkshere'             => array( 'چی_بەستەری_داوە_بێرە' ),
);

$magicWords = array(
	'img_thumbnail'             => array( '1', 'وێنۆک', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'ڕاست', 'right' ),
	'img_left'                  => array( '1', 'چەپ', 'left' ),
	'img_width'                 => array( '1', '$1پیکسڵ', '$1px' ),
	'img_center'                => array( '1', 'ناوەڕاست', 'center', 'centre' ),
	'img_framed'                => array( '1', 'چوارچێوە', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'بێچوارچێوە', 'frameless' ),
	'img_border'                => array( '1', 'سنوور', 'border' ),
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
'tog-underline' => 'ھێڵ ھێنان بەژێر بەستەرەکان:',
'tog-justify' => 'پەرەگرافەکان پڕاوپر نیشان بدە',
'tog-hideminor' => 'دەستکارییە بچووکەکان لە دوایین گۆڕانکارییەکاندا بشارەوە',
'tog-hidepatrolled' => 'لە دوایین گۆڕانکارییەکاندا دەستکارییە پاس دراوەکان بشارەوە',
'tog-newpageshidepatrolled' => 'لە پێرستی پەڕە نوێکاندا پەڕە پاس دراوەکان بشارەوە',
'tog-extendwatchlist' => 'لیستی چاودێری درێژبکەرەوە بۆ نیشان دانی ھەموو گۆڕانکارییەکان، نەک تەنھا دوایینەکان.',
'tog-usenewrc' => 'گۆڕانکارییەکان لە دوایین گۆڕانکارییەکان و لیستی چاودێریدا بە پێی پەڕە پۆلێن بکە (پێویستی بە جاڤاسکریپتە)',
'tog-numberheadings' => 'ژمارەکردنی خۆگەڕی سەردێڕەکان',
'tog-showtoolbar' => 'شریتی ئامرازەکانی دەستکاری نیشان بدە (JavaScript پێویستە)',
'tog-editondblclick' => 'دەستکاریی پەڕە بە دووکلیک لەسەر دەق (JavaScript پێویستە)',
'tog-editsection' => 'ڕێگە بدە بۆ دەستکاری کردنی بەشەکان لە ڕێگەی بەستەرەکانی [دەستکاری]',
'tog-editsectiononrightclick' => 'ڕێگە بدە بۆ دەستکاری کردنی بەشەکان لە ڕێگەی کلیکی ڕاست کردن لەسەر سەردێڕی بەشەکان (JavaScript پێویستە)',
'tog-showtoc' => 'پێرستی ناوەرۆک نیشان بدە (بۆ ئەو پەڕانە کە زیاتر لە ٣ سەردێڕیان تێدایە)',
'tog-rememberpassword' => 'چوونە ژوورەوەم لەسەر ئەم وێبگەڕە پاشەکەوت بکە (ئەو پەڕی $1 {{PLURAL:$1|ڕۆژ|ڕۆژ}}ە)',
'tog-watchcreations' => 'ئەو پەڕانەی من دروستم کردوون و ئەو پەڕگانە من بارم کردوون زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchdefault' => 'ئەو پەڕانە  و ئەو پەڕگانە من دەستکاریان دەکەم زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchmoves' => 'ئەو پەڕانە و ئەو پەڕگانە کە من گواستومنەتەوە زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-watchdeletion' => 'ئەو پەڕانە و ئەو پەڕگانە من سڕیومنەتەوە زیاد بکە بە لیستی چاودێڕییەکەم',
'tog-minordefault' => 'ھەموو دەستکارییەکان بە ورد نیشان بکە لە حاڵەتی دیفاڵت',
'tog-previewontop' => 'پێشبینین بەرلە چوارچێوەی دەستکاری نیشان بدە‌',
'tog-previewonfirst' => 'لە یەکەم دەستکاری دا پێشبینین نیشان بدە',
'tog-nocache' => 'کاشکردنی پەڕەکانی وێبگەڕەکە لەکاربخە',
'tog-enotifwatchlistpages' => 'ئەگەر پەڕە یان پەڕگەیەک لە پێرستی چاودێیییەکانمدا گۆڕدرا ئیمەیلم بۆ بنێرە',
'tog-enotifusertalkpages' => 'ئەگەر پەڕەی لێدوانەکەم گۆڕدرا ئیمەیلم بۆ بنێرە',
'tog-enotifminoredits' => 'بۆ گۆڕانکارییە بچووکەکانی پەڕەکان و پەڕگەکانیش ئیمەیلم بۆ بنێرە',
'tog-enotifrevealaddr' => 'ئەدرەسی ئیمەیلەکەم لە ئیمەیلە ئاگاداریدەرەکان دا نیشان بدە',
'tog-shownumberswatching' => 'ژمارەی بەکارھێنەرە چاودێڕەکان نیشان بدە',
'tog-oldsig' => 'واژووی ئێستا:',
'tog-fancysig' => 'وەکوو ویکیدەق واژووەکە لەبەر چاو بگرە (بێ بەستەرێکی خۆگەڕ)',
'tog-uselivepreview' => 'لە پێشبینینی زیندوو کەڵک وەرگرە (جاڤاسکریپت پێویستە) (تاقیکاری‌)',
'tog-forceeditsummary' => 'ئەگەر کورتەی دەستکاریم نەنووسی پێم بڵێ',
'tog-watchlisthideown' => 'دەستکارییەکانم بشارەوە لە پێرستی چاودێری',
'tog-watchlisthidebots' => 'دەستکارییەکانی بات بشارەوە لە لیستی چاودێری',
'tog-watchlisthideminor' => 'دەستکارییە بچووکەکان لە لیستی چاودێریدا بشارەوە',
'tog-watchlisthideliu' => 'دەستکارییەکانی ئەو بەکارهێنەرانەی لە ژوورەوەن بشارەوە لە لیستی چاودێری',
'tog-watchlisthideanons' => 'دەستکارییەکانی بەکارهێنەرانی نەناسراو بشارەوە لە لیستی چاودێری',
'tog-watchlisthidepatrolled' => 'لە پێرستی چاودێرییەکاندا دەستکارییە پاس دراوەکان بشارەوە',
'tog-ccmeonemails' => 'کۆپییەک لەو ئیمەیلانە کە بۆ بەکارھێنەرانی تر دەنێرم بۆ خۆشم بنێرە',
'tog-diffonly' => 'ناوەرۆکی پەڕە لە ژێرەوەی جیاوازییەکاندا نیشان مەدە',
'tog-showhiddencats' => 'پۆلە شاردراوەکان نیشان بدە',
'tog-noconvertlink' => 'لەکارخستنی ئاڵوگۆڕی سەرناوی بەستەر',
'tog-norollbackdiff' => 'لە دوای گەڕاندنەوە جیاوازی نیشان مەدە',
'tog-useeditwarning' => 'ھۆشیارم بکەوە کاتێک لە پەڕەیەکی دەستکاری بە گۆڕانکاریی پاشەکەوت‌نەکراو دەردەچم',

'underline-always' => 'ھەمیشە',
'underline-never' => 'قەت',
'underline-default' => 'پێستە یان دیفاڵتی وێبگەڕەکە',

# Font style option in Special:Preferences
'editfont-style' => 'شێوازی جۆرەپیتی بەشی دەستکاری:',
'editfont-default' => 'بنچینەی وێبگەڕ',
'editfont-monospace' => 'جۆرەپیتی تاکەبۆشایی (Monospaced)',
'editfont-sansserif' => 'جۆرەپیتی Sans-serif',
'editfont-serif' => 'جۆرەپیتی Serif',

# Dates
'sunday' => 'یەکشەممە',
'monday' => 'دووشەممە',
'tuesday' => 'سێشەممە',
'wednesday' => 'چوارشەممە',
'thursday' => 'پێنجشەممە',
'friday' => 'ھەینی',
'saturday' => 'شەممە',
'sun' => 'یەکشەممە',
'mon' => 'دووشەممە',
'tue' => 'سێشەممە',
'wed' => 'چوارشەممە',
'thu' => 'پێنجشەممە',
'fri' => 'ھەینی',
'sat' => 'شەممە',
'january' => 'کانوونی دووەم',
'february' => 'شوبات',
'march' => 'ئازار',
'april' => 'نیسان',
'may_long' => 'ئایار',
'june' => 'حوزەیران',
'july' => 'تەممووز',
'august' => 'ئاب',
'september' => 'ئەیلوول',
'october' => 'تشرینی یەکەم',
'november' => 'تشرینی دووەم',
'december' => 'کانوونی یەکەم',
'january-gen' => 'کانوونی دووەمی',
'february-gen' => 'شوباتی',
'march-gen' => 'ئازاری',
'april-gen' => 'نیسانی',
'may-gen' => 'ئایاری',
'june-gen' => 'حوزەیرانی',
'july-gen' => 'تەممووزی',
'august-gen' => 'ئابی',
'september-gen' => 'ئەیلوولی',
'october-gen' => 'تشرینی یەکەمی',
'november-gen' => 'تشرینی دووەمی',
'december-gen' => 'کانوونی یەکەمی',
'jan' => 'کانوونی دووەم',
'feb' => 'شوبات',
'mar' => 'ئازار',
'apr' => 'نیسان',
'may' => 'ئایار',
'jun' => 'حوزەیران',
'jul' => 'تەممووز',
'aug' => 'ئاب',
'sep' => 'ئەیلوول',
'oct' => 'تشرینی یەکەم',
'nov' => 'تشرینی دووەم',
'dec' => 'کانوونی یەکەم',
'january-date' => '$1ی کانوونی دووەم',
'february-date' => '$1ی شوبات',
'march-date' => '$1ی ئازار',
'april-date' => '$1ی نیسان',
'may-date' => '$1ی ئایار',
'june-date' => '$1ی حوزەیران',
'july-date' => '$1ی تەممووز',
'august-date' => '$1ی ئاب',
'september-date' => '$1ی ئەیلوول',
'october-date' => '$1ی تشرینی یەکەم',
'november-date' => '$1ی تشرینی دووەم',
'december-date' => '$1ی کانوونی یەکەم',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|پۆل|پۆلەکان}}',
'category_header' => 'پەڕەکانی پۆلی «$1»',
'subcategories' => 'ژێرپۆلەکان',
'category-media-header' => 'میدیای پۆلی «$1»',
'category-empty' => "''ئەم پۆلە ھەنووکە ھیچ پەڕە یان پەڕگەیەک لە خۆ ناگرێت.‌''",
'hidden-categories' => '{{PLURAL:$1|پۆلی شاراوە|پۆلی شاراوە}}',
'hidden-category-category' => 'پۆلە شاردراوەکان',
'category-subcat-count' => '{{PLURAL:$2|ئەم پۆلە تەنھا ژێرپۆلی خوارەوەی تێدایە.| ئەم پۆلە ئەم {{PLURAL:$1|ژێرپۆلەی|$1 ژێرپۆلانەی}} خوارەوەی تێدایە، لە کۆی سەرجەم $2 دانە.}}',
'category-subcat-count-limited' => 'ئەم هاوپۆلە {{PLURAL:$1|ژێرهاوپۆلی}} لەخۆ گرتووە.',
'category-article-count' => '{{PLURAL:$2|ئەم پۆلە تەنھا ئەم پەڕەی لەخۆگرتووە.|{{PLURAL:$1|پەڕە|$1 پەڕە}} لەم پۆلەدا، لە سەرجەم $2 پەڕە.}}',
'category-article-count-limited' => 'ئەم {{PLURAL:$1|لاپەڕە|$1 لاپەڕانە}}، لەم هاوپۆلەدان.',
'category-file-count' => '{{PLURAL:$2|ئەم هاوپۆلە تەنها ئەم پەڕگەی لەخۆ گرتووە.|ئەم‌ {{PLURAL:$1|پەڕگەیە}} کە بەشێکە لە هەموو $2پەڕگەی ئەم هاوپۆلە‌ دەیبینی.}}',
'category-file-count-limited' => 'ئەم {{PLURAL:$1|پەڕگە|پەڕگانە}} لەم هاوپۆلەدایە.',
'listingcontinuesabbrev' => '(درێژە)',
'index-category' => 'پەڕە پێرستەکراوەکان',
'noindex-category' => 'پەڕە پێرستنەکراوەکان',
'broken-file-category' => 'ئەو پەڕانەی بەستەری پەڕگەکانیان شکاوە',
'categoryviewer-pagedlinks' => '($1) ($2)',

'about' => 'سەبارەت',
'article' => 'بابەت',
'newwindow' => '(لە پەڕەیەکی نوێدا دەکرێتەوە)',
'cancel' => 'ھەڵیوەشێنەوە',
'moredotdotdot' => 'زیاتر',
'morenotlisted' => 'درێژەی پێرست...',
'mypage' => 'پەڕه‌',
'mytalk' => 'لێدوان',
'anontalk' => 'وتووێژ بۆ ئەم ئای‌پی یە',
'navigation' => 'ڕێدۆزی',
'and' => '&#32;و',

# Cologne Blue skin
'qbfind' => 'بدۆزەرەوە',
'qbbrowse' => 'بگه‌ڕێ',
'qbedit' => 'دەستکاری',
'qbpageoptions' => 'ئەم پەڕەیە',
'qbmyoptions' => 'پەڕەکانم',
'qbspecialpages' => 'پەڕە تایبەتەکان',
'faq' => 'پرسیار و وەڵام (FAQ)',
'faqpage' => 'Project:پرسیار و وەڵام',

# Vector skin
'vector-action-addsection' => 'بابەت دابنێ',
'vector-action-delete' => 'بیسڕەوە',
'vector-action-move' => 'بیگوازەوە',
'vector-action-protect' => 'بیپارێزە',
'vector-action-undelete' => 'سڕینەوە بگەڕێنەوە',
'vector-action-unprotect' => 'پاراستنی بگۆڕە',
'vector-simplesearch-preference' => 'گەڕانی ساکار چالاک بکە (تەنیا بۆ پێستەی ڤێکتۆر)',
'vector-view-create' => 'دروستکردن',
'vector-view-edit' => 'دەستکاریی بکە',
'vector-view-history' => 'مێژووەکەی ببینە',
'vector-view-view' => 'بیخوێنەوە',
'vector-view-viewsource' => 'سەرچاوەکەی ببینە',
'actions' => 'کردەوەکان',
'namespaces' => 'شوێنناوەکان',
'variants' => 'شێوەزارەکان',

'navigation-heading' => 'مێنۆی ڕێدۆزی',
'errorpagetitle' => 'ھەڵە',
'returnto' => 'بگەڕێوە بۆ $1.',
'tagline' => 'لە {{SITENAME}}',
'help' => 'یارمەتی',
'search' => 'گەڕان',
'searchbutton' => 'بگەڕێ',
'go' => 'بڕۆ',
'searcharticle' => 'بڕۆ',
'history' => 'مێژووی پەڕە',
'history_short' => 'مێژووی پەڕە',
'updatedmarker' => 'لە دوایین سەردانمدا نوێ کراوەتەوە',
'printableversion' => 'وەشانی ئامادەی چاپ',
'permalink' => 'بەستەری ھەمیشەیی',
'print' => 'چاپ',
'view' => 'بینین',
'edit' => 'دەستکاری',
'create' => 'دروستکردن',
'editthispage' => 'دەستکاری ئەم پەڕەیە بکە‌',
'create-this-page' => 'ئەم پەڕە دروست بکە',
'delete' => 'سڕینەوە',
'deletethispage' => 'سڕینه‌وه‌ی ئه‌م په‌ڕه‌یه‌',
'undeletethispage' => 'ئەم پەڕەیە بھێنەوە',
'undelete_short' => '{{PLURAL:$1|یەک گۆڕانکاریی|$1 گۆڕانکاریی}} سڕاوە بەجێبھێنەرەوە',
'viewdeleted_short' => '{{PLURAL:$1|یەک گۆڕانکاریی سڕاو|$1 گۆڕانکاریی سڕاو}} ببینە',
'protect' => 'پاراستن',
'protect_change' => 'گۆڕین',
'protectthispage' => 'ئه‌م په‌ڕه‌یه‌ بپارێزه‌',
'unprotect' => 'پاراستنی بگۆڕە',
'unprotectthispage' => 'پاراستنی ئەم پەڕەیە بگۆڕە',
'newpage' => 'پەڕەی نوێ',
'talkpage' => 'باس لەسەر ئەم پەڕە بکە‌',
'talkpagelinktext' => 'لێدوان',
'specialpage' => 'په‌ڕه‌ی تایبه‌ت',
'personaltools' => 'ئامڕازە تاکەکەسییەکان',
'postcomment' => 'بەشی نوێ',
'articlepage' => 'پەڕەی ناوەرۆک ببینە',
'talk' => 'وتووێژ',
'views' => 'بینینەکان',
'toolbox' => 'ئامرازدان',
'userpage' => 'بینینی پەڕەی بەکارھێنەر',
'projectpage' => 'په‌ڕه‌ی پرۆژه‌ نیشانبده‌',
'imagepage' => 'پەڕەی پەڕگە نیشان بدە',
'mediawikipage' => 'په‌ڕه‌ی په‌یام نیشانبده‌',
'templatepage' => 'په‌ڕه‌ی داڕێژە ببینە‌',
'viewhelppage' => 'په‌ڕه‌ی یارمه‌تی نیشانبده‌',
'categorypage' => 'په‌ڕه‌ی هاوپۆل نیشانبده‌',
'viewtalkpage' => 'بینینی لێدوان',
'otherlanguages' => 'بە زمانەکانی تر',
'redirectedfrom' => '(ڕەوانەکراوە لە $1ەوە)',
'redirectpagesub' => 'پەڕەی ڕەوانەکەر',
'lastmodifiedat' => 'ئەم پەڕەیە دواجار لە $2ی $1 نوێ کراوەتەوە.',
'viewcount' => 'ئەم پەڕەیە {{PLURAL:$1|یەکجار|$1 جار}} بینراوە.',
'protectedpage' => 'پەڕەی پارێزراو',
'jumpto' => 'باز بدە بۆ:',
'jumptonavigation' => 'ڕێدۆزی',
'jumptosearch' => 'گەڕان',
'view-pool-error' => 'ببورە، لەم کاتەدا ڕاژەکارەکان زیادەباریان لە سەرە.
ژمارەیەکی زۆر لە بەکارھێنەران ھاوکات ھەوڵی دیتنی ئەم پەڕەیان داوە.
تکایە پێش ھەوڵی دووبارە بۆ دیتنی ئەم پەڕە، نەختێک بوەستە.

$1',
'pool-errorunknown' => 'هەڵەی نەزانراو',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'سەبارەت بە {{SITENAME}}',
'aboutpage' => 'Project:سەبارەت',
'copyright' => 'ناوەرۆک لە ژێر $1 لەبەردەستدایە.',
'copyrightpage' => '{{ns:project}}:مافەکانی لەبەرگرتنەوە',
'currentevents' => 'ڕووداوە ھەنووکەییەکان',
'currentevents-url' => 'Project:ڕووداوە بەردەوامەکان',
'disclaimers' => 'نابەرپرسییەکان',
'disclaimerpage' => 'Project:بەرپرسنەبوون',
'edithelp' => 'ڕێنوێنیی دەستکاریکردن',
'helppage' => 'Help:ناوەرۆک',
'mainpage' => 'دەستپێک',
'mainpage-description' => 'دەستپێک',
'policy-url' => 'Project: سیاسەت',
'portal' => 'دەروازەی بەکارھێنەران',
'portal-url' => 'Project: دەروازەی بەکارھێنەران',
'privacy' => 'سیاسەتی تایبەتێتی',
'privacypage' => 'Project:پاراستنی زانیارییەکان',

'badaccess' => 'ھەڵە لە بە دەست ھێنان',
'badaccess-group0' => 'ڕێگەت پێ نەدراوە بۆ بەجێهێنای ئەو ئەنجامە وا داخوازیت کردووه.',
'badaccess-groups' => 'ئەو کردەوەیەی داوات کردووه تایبەتە بۆ بەکارھێنەرانی {{PLURAL:$2|گرووپی|گرووپەکانی}}: $1.',

'versionrequired' => 'وەشانی $1ی‌ میدیاویکی پێویستە',
'versionrequiredtext' => 'پێویستیت بە وەشانی $1ی ویکیمیدیا ھەیە بۆ بەکاربردنی ئەم پەڕەیە.
تەماشای [[Special:Version|پەڕەی وەشان]] بکە.',

'ok' => 'باشه‌',
'pagetitle' => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle' => '→ $1',
'retrievedfrom' => 'وەرگیراو لە «$1»',
'youhavenewmessages' => '$1ت ھەیە ($2).',
'newmessageslink' => 'پەیامی نوێ',
'newmessagesdifflink' => 'دوایین گۆڕانکاری',
'youhavenewmessagesfromusers' => 'لە {{PLURAL:$3|بەکارھێنەرێک|$3 بەکارھێنەران}} $1ت ھەیە ($2).',
'youhavenewmessagesmanyusers' => '$1ت  لە ژمارەیەک بەکارھێنەر ھەیە ( $2 ).',
'newmessageslinkplural' => '{{PLURAL:$1|پەیامێکی نوێ|پەیامی نوێ}}',
'newmessagesdifflinkplural' => 'دوایین {{PLURAL:$1|گۆڕانکاری|گۆڕانکارییەکان}}',
'youhavenewmessagesmulti' => 'لە $1 دا پەیامی نوێت ھەیە',
'editsection' => 'دەستکاری',
'editold' => 'دەستکاری',
'viewsourceold' => 'سەرچاوەکەی ببینە',
'editlink' => 'دەستکاری',
'viewsourcelink' => 'سەرچاوەکەی ببینە',
'editsectionhint' => 'دەستکاری کردنی بەشی: $1',
'toc' => 'پێرست',
'showtoc' => 'نیشانیبدە',
'hidetoc' => 'بیشارەوە',
'collapsible-collapse' => 'کۆیبکەوە',
'collapsible-expand' => 'بڵاویبکەوە',
'thisisdeleted' => '$1 نیشان بدە یا بھێنەوە؟',
'viewdeleted' => '$1 نیشان بده‌؟',
'restorelink' => '{{PLURAL:$1|یەک گۆڕانکاریی سڕاو|$1 گۆڕانکاریی سڕاو}}',
'feedlinks' => 'خۆراک:',
'feed-invalid' => 'ئەندام بوونی ئەو جۆرە خۆراکە نەناسراوە.',
'feed-unavailable' => 'پەیوەندی فییدەکان ئامادەی کەڵک وەرگرتن نیە',
'site-rss-feed' => 'فیدی RSS بۆ $1',
'site-atom-feed' => 'فیدی Atom بۆ $1',
'page-rss-feed' => 'فیدی RSS بۆ «$1»',
'page-atom-feed' => 'فیدی Atom بۆ «$1»',
'feed-atom' => 'ئەتۆم',
'red-link-title' => '$1 (پەڕە بوونی نییە)',
'sort-descending' => 'ڕیزکردنی بەرەوە ژێر',
'sort-ascending' => 'ڕیزکردنی بەرەوە ژوور',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'پەڕە',
'nstab-user' => 'پەڕەی بەکارھێنەر',
'nstab-media' => 'میدیا',
'nstab-special' => 'پەڕەی تایبەت',
'nstab-project' => 'پەڕەی پرۆژە',
'nstab-image' => 'پەڕگە',
'nstab-mediawiki' => 'پەیام',
'nstab-template' => 'داڕێژە',
'nstab-help' => 'پەڕەی یارمەتی',
'nstab-category' => 'پۆل',

# Main script and global functions
'nosuchaction' => 'کردارێک بەم شێوە نییە',
'nosuchactiontext' => 'ئەو چالاکییەی لە لایەن بەستەرەوە دیاریکراوە ناتەواوە.
لەوانەیە بە هەڵە بەستەرەکەت نووسیبێت، یان بەستەرێکی هەڵەی بە دواوە بێت.
لەوانەیە ئەمە نیشانەی هەڵەیەک بێت لەو نەرمەکاڵایەی کە بەکاردێت لە لایەن {{SITENAME}}.',
'nosuchspecialpage' => 'په‌ڕه‌ی تایبه‌تی له‌و شێوه‌یه‌ نییه‌',
'nospecialpagetext' => '<strong>پەڕەیەکی تایبەت دەخوازیت کە بوونی نیە.</strong>

لیستێکی پەڕە تایبەتە دروستەکان لە [[Special:SpecialPages|{{int:specialpages}}]] لە بەردەست‌دایە.',

# General errors
'error' => 'هه‌ڵه‌',
'databaseerror' => 'ھەڵەی بنکەدراوه',
'laggedslavemode' => 'ئاگاداری: لەوانەیە لاپەڕەکە نوێکردنەکان لە بەر نەگرێت.',
'readonly' => 'بنکەدراوە داخراوە',
'enterlockreason' => 'هۆیەک بۆ قوفڵ‌کردنەکە بنووسە کە  تێیدا کاتی کردنەوەی قۆفڵەکە باس کرابێت',
'readonlytext' => 'بنکەدراوەکە لەم کاتەدا  لەبەر چاکسازی ئاسایی بۆ نوسینی نوێ و دەستکاری قوفڵ کراوه. دوای ئەوە ئەگرێتەوە بۆ ئاستی خۆی.

ئەو بەڕێوبەرەی کە قوفڵی کردووه ئەم ڕوون‌کردنەوەی نووسیوە : $1',
'missing-article' => 'داتابەیسەکە نەیتوانی دەقی پەڕەیەک بەناوی «$1» $2  بدۆزێتەوە کە دەبوا بیدۆزیبایەتەوە.

ئەمە زیاتر لە بەدواچوونی بەستەری جیاوازی یان مێژووی کۆنی پەڕەیەکی سڕدراو ڕوودەدات.

ئەگەر وا نەبێت، ئەوا ڕەنگە گرفتێکت لەم نەرمامێرەدا دۆزیبێتەوە.
تکایە ئەمە بە ئاماژەدان بە ناونیشانی URLـەکەیەوە بە [[Special:ListUsers/sysop|بەڕێوبەرێک]] ڕاپۆرت بدە.',
'missingarticle-rev' => '(پیاچوونەوە#: $1)',
'missingarticle-diff' => '(جیاوازی: $1، $2)',
'readonly_lag' => 'بنكه‌دراوه‌كه‌ به‌شێوه‌ی خۆكار به‌ندكراوه‌، له‌كاتێكدا بنكه‌دراوه‌ی ڕاژه‌كاره‌كه‌ ڕۆڵی له‌خۆگرتن ده‌گێڕێت',
'internalerror' => 'ھەڵەی ناوخۆیی',
'internalerror_info' => 'هه‌ڵه‌ی ناوخۆیی: $1',
'fileappenderror' => 'نه‌تواندرا "$1" بخرێته‌سه‌ر "$2".',
'filecopyerror' => 'نەکرا پەڕگەی «$1» کۆپی بکرێت بۆ «$2».',
'filerenameerror' => 'ناوی په‌ڕگه‌ی "$1" نه‌گۆڕدرا بۆ "$2".',
'filedeleteerror' => 'نەکرا پەڕگەی «$1» بسڕدرێتەوە.',
'directorycreateerror' => 'نەتوانرا بوخچەی "$1"دروست بکرێت.',
'filenotfound' => 'په‌ڕگه‌ی "$1" نه‌دۆزرایه‌وه‌',
'fileexistserror' => 'ناتوانی لەسەر پەڕگەی "$1" بنووسیت: ئەو پەڕگەیە هەیە.',
'unexpected' => 'نرخی چاوەڕوان نەکراو: "$1"="$2" .',
'formerror' => 'هەڵە: فورمەکە نانێردرێت.',
'badarticleerror' => 'ئەو ئاماژە لەم لاپەڕەدا پێک‌نایە.',
'cannotdelete' => 'نەتوانرا پەڕە یان پەڕگەی «$1» بسڕدرێتەوە.
لەوانەیە پێشتر لە لایەن کەسێکی ترەوە سڕابێتەوە.',
'cannotdelete-title' => 'ناکرێ پەڕەی «$1» بسڕدرێتەوە',
'delete-hook-aborted' => 'سڕینەوە لە لایەن قولاپەوە ھەڵوەشێنرایەوە.
ھۆکارەکەی لەبەر دەست نییە.',
'badtitle' => 'ناونیشانی خراپ',
'badtitletext' => 'سەرناوی پەڕەی داواکراو بەتاڵە، واڵایە یان سەرناوێکی نێوان-زمانی یان نێوانی-ویکییە کە بە شێوەیەکی ھەڵە بەستەری بۆ دراوە.
ڕەنگە یەک یان چەند کاراکتەری تێدا بێت کە ناکرێت لە سەرناوەکاندا بەکار بھێنرێت.',
'perfcached' => 'داتای خوارەوە پاشەکەوتکراوەیە و لەوانەیە بەڕۆژنەکرابێتەوە. لانی زۆر {{PLURAL:$1|یەک ئەنجام|$1 ئەنجام}} لە cacheدا لەبەردەستدایە.',
'perfcachedts' => 'داتای خوارەوە cacheکراوە و دوایین جار لە $1 نوێ کراوەتەوە. لە cacheدا لانی زۆر {{PLURAL:$4|یەک ئەنجام|$4 ئەنجام}} لەبەردەستە.',
'querypage-no-updates' => 'تازەکردنەوەکان بۆ ئەم پەڕە لە حاڵی ئێستادا ناچالاک کراوەتەوە.
داتای ئێرە دەسبەجێ تازە ناکرێتەوە.',
'wrong_wfQuery_params' => 'پارامێتری ھەڵە بۆ wfQuery()<br />
کردار: $1<br />
داواکاری: $2',
'viewsource' => 'سەرچاوەکەی ببینە',
'viewsource-title' => 'سەرچاوەی $1 ببینە',
'actionthrottled' => 'چالاکی پێشی پێ گیرا',
'actionthrottledtext' => 'بە مەبەستی پێشگریی لە سپەم، ڕێگە نادرێت تۆ لە ماوەیەکی کورت دا لە سەر یەک ئەمە زۆر جار ئەنجام بدەی، وە ئیستا تۆ لە ڕادە بەدەرت کردووە.
تکایە پاش چەند خولەک دووبارە تاقی بکەوە.',
'protectedpagetext' => 'بۆ بەرگری لە دەستکاریکردن یان چالاکییەکانی تر ئەم پەڕەیە پارێزراوە.',
'viewsourcetext' => 'دەتوانی سەرچاوەی ئەم پەڕە ببینی و کۆپیی بکەی:',
'viewyourtext' => "دەتوانی ژێدەری '''دەستکارییەکەت''' لەم پەڕەیەدا ببینی و کۆپی بکەی:",
'protectedinterface' => 'ئەم پەڕەیە دەقی ڕواڵەتی نەرمامێری ئەم ویکییە نیشان دەدات و بۆ بەرگری لە خراپکاری پارێزراوە.
بۆ زیادکردن یان گۆڕینی وەرگێڕانەکان بۆ ھەموو ویکییەکان، تکایە لە [//translatewiki.net/ translatewiki.net]، پرۆژەی ناوچەیی کردنی میدیاویکی کەڵک وەربگرە.',
'editinginterface' => "'''ئاگاداری:''' تۆ خەریکی دەستکاریی پەڕەیەکی کە بۆ دابینکردنی دەقی ڕواڵەتی نەرمامێر بە کار دەھێنرێت.
گۆڕانکاریی  ئەم پەڕەیە کاریگەر دەبێت لە سەر ڕواڵەتی پەڕەکانی بەکارھێنەرانی تر لەم ویکییەدا.
بۆ زیادکردن یان گۆڕینی وەرگێڕانەکان بۆ ھەموو ویکییەکان، تکایە لە [//translatewiki.net/ translatewiki.net]، پرۆژەی ناوچەیی کردنی میدیاویکی کەڵک وەربگرە.",
'cascadeprotected' => 'ئەم لاپەڕە پارێزراوە لە دەستکاریی، چونکا خراوەتە سەر ڕیزی ئەم {{PLURAL:$1|لاپەڕانه‌، کە}} که‌ به‌ هه‌ڵکردنی بژارده‌ی داڕژان هه‌ڵکراوه‌:
$2',
'namespaceprotected' => "تۆ ناتوانی لاپەڕەکانی ناو نەیمسپەیسی '''$1''' بگۆڕی.",
'ns-specialprotected' => 'تۆ ناتوانی لاپەڕە تایبەتەکان دەستکاریی بکەی.',
'titleprotected' => 'ئەم سەرناوە پارێزراوە لە دروستکران لە لایەن [[User:$1|$1]].
ھۆکاری ئەمە بریتیە لە "\'\'$2\'\'".',
'exception-nologin' => 'لەژوورەوە نیت',

# Virus scanner
'virus-badscanner' => "پێکەربەندیی نابەجێ: ڤایرس سکەنێری نەناسراو: ''$1''",
'virus-scanfailed' => 'سکەن ئەنجام نەدرا(کۆد $1)',
'virus-unknownscanner' => 'دژەڤایرس نەناسراوە:',

# Login and logout pages
'logouttext' => "'''ئێستا تۆ لە ھەژمارەکەت ھاتوویتە دەرەوە.'''

دەتوانی بە شێوەی بێناو درێژە بدەی بە بەرکارھێنانی {{SITENAME}}، یان دەتوانی <span class='plainlinks'>[$1 دیسانەوە بچیتەوە ژوورەوە]</span> ھەر بەو ناوە یان بە ناوی بەکارھێنەرییەکی جیاوازەوە.
ئاگادار بە کە ھەتا کاتێک کە کەشی وێبگەڕەکەت دەسڕیتەوە، سەرەڕای چوونەدەرەوەی تۆ ھەندێک لە پەڕەکان ھەر بە شێوەیەک نیشان دەدرێن کە گوایە تۆ ھێشتا لە ژوورەوەیت.",
'welcomeuser' => 'بەخێربێیت، $1!',
'welcomecreation-msg' => 'ھەژمارەکەت دروست کرا.
لە بیرت نەچێت [[Special:Preferences|ھەڵبژاردەکانی {{SITENAME}}]]ت بگۆڕی.',
'yourname' => 'ناوی بەکارھێنەری:',
'userlogin-yourname' => 'ناوی بەکارھێنەر',
'userlogin-yourname-ph' => 'ناوی بەکارھێنەریت بنووسە',
'createacct-another-username-ph' => 'ناوی بەکارھێنەریت بنووسە',
'yourpassword' => 'تێپەڕوشە:',
'userlogin-yourpassword' => 'تێپەڕوشە',
'userlogin-yourpassword-ph' => 'تێپەڕوشەکەت بنووسە',
'createacct-yourpassword-ph' => 'تێپەروشەیەک بنووسە',
'yourpasswordagain' => 'دیسان تێپەڕوشەکە بنووسەوە:',
'createacct-yourpasswordagain' => 'تێپەروشە پشتڕاست بکەرەوە',
'createacct-yourpasswordagain-ph' => 'تێپەروشە دیسان بنووسەوە',
'remembermypassword' => 'چوونە ژوورەوەم لەسەر ئەم کۆمپیوتەرە پاشەکەوت بکە (ئەو پەڕی $1 {{PLURAL:$1|ڕۆژ}}ە)',
'userlogin-remembermypassword' => 'چوونەژوورەوەکەم ڕابگرە',
'userlogin-signwithsecure' => 'پەیوەندیی دڵنیا بەکاربھێنە',
'yourdomainname' => 'دۆمەینەکەت:',
'password-change-forbidden' => 'ناتوانیت تێپەڕوشەکانت لەم ویکییەدا بگۆڕیت.',
'externaldberror' => 'یان هەڵەی ڕێگەپێدانی بنکەدراو هەیە یان ڕێگات پێ نادرێت بۆ نوێ کردنی هەژماری دەرەکیت.',
'login' => 'بچۆ ژوورەوە',
'nav-login-createaccount' => 'بچۆ ژوورەوە / ھەژمار دروست بکە',
'loginprompt' => 'بۆ چوونەژوورەوە بۆ {{SITENAME}} دەبێ کوکییەکان چالاک بکەیت.',
'userlogin' => 'بچۆ ژوورەوە / ھەژمار دروست بکە',
'userloginnocreate' => 'بچۆ ژوورەوە',
'logout' => 'بچۆ دەرەوە',
'userlogout' => 'بچۆ دەرەوە',
'notloggedin' => 'لە ژوورەوە نیت',
'userlogin-noaccount' => 'ھەژمارت نییە؟',
'userlogin-joinproject' => 'ڕەگەڵ {{SITENAME}} کەوە',
'nologin' => 'ھەژمارت نییە؟  $1.',
'nologinlink' => 'ھەژمارێک دروست بکە',
'createaccount' => 'ھەژمار دروست بکە',
'gotaccount' => 'ھەژمارت ھەیە لێرە؟ $1.',
'gotaccountlink' => 'بچۆ ژوورەوە',
'userlogin-resetlink' => 'وردەکارییەکانی چوونەژوورەوەتت لە بیر کردووە؟',
'userlogin-resetpassword-link' => 'تێپەڕوشە ڕیسێت بکەوە',
'helplogin-url' => 'Help:چوونەژوورەوە',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|یارمەتی بۆ چوونەژوورەوە]]',
'createacct-join' => 'زانیارییەکەت لە ژێرەوە بنووسە.',
'createacct-emailrequired' => 'ناونیشانی ئیمەیل',
'createacct-emailoptional' => 'ناونیشانی ئیمەیل (دڵخوازانە)',
'createacct-email-ph' => 'ناونیشانی ئیمەیلەکەت بنووسە',
'createaccountmail' => 'تێپەڕوشەیەکی ڕەمەکیی کاتی بەکاربھێنە و بینێرە بۆ ناونیشانی ئیمەیلی دیاری‌کراوی ژێرەوە',
'createacct-realname' => 'ناوی ڕاستی (دڵخوازانە)',
'createaccountreason' => 'هۆکار:',
'createacct-reason' => 'ھۆکار',
'createacct-reason-ph' => 'بۆ ھەژمارێکی تر دروست دەکەی',
'createacct-captcha' => 'تاوتوێی ئاسایشی',
'createacct-imgcaptcha-ph' => 'دەقەکەی لە ژێرەوە دەیبینی بینووسە',
'createacct-submit' => 'ھەژمارەکەت دروست بکە',
'createacct-benefit-heading' => '{{SITENAME}} لە لایەن کەسانێک وەکوو خۆت دروست کراوە.',
'createacct-benefit-body1' => '{{PLURAL:$1|دەستکاری}}',
'createacct-benefit-body2' => '{{PLURAL:$1|پەڕە}}',
'createacct-benefit-body3' => 'دوایین {{PLURAL:$1|بەشداربوو|بەشداربووان}}',
'badretype' => 'تێپەڕوشەکان لەیەک ناچن.',
'userexists' => 'ئەو ناوەی تۆ داوتە پێشتر بەکارھێنراوە.
ناوێکی دیکە ھەڵبژێرە.',
'loginerror' => 'ھەڵەی چوونەژوورەوە',
'createacct-error' => 'ھەڵە لە دروستکردنی ھەژمار',
'createaccounterror' => 'ناتوانیت هەژماری بەکارهێنەر دروست بکەیت: $1',
'nocookiesnew' => 'ھەژماری بەکارھێنەر دروست‌کرا، بەڵام بە سەرکەوتوویی نەچوویتەوە ژوورەوە.
{{SITENAME}} بۆ چوونەوە ژوورەوەی بەکارھێنەر لە شەکرۆکە کەڵک وەردەگرێت.
تۆ شەکرۆکەکەت لەکارخستووە.
تکایە شەکرۆکەکە کارا بکە و پاشان بە ناوی بەکارھێنەر و تێپەڕوشە بچۆ ژوورەوە.',
'nocookieslogin' => '{{SITENAME}} بۆ چوونەژوورەوە لە کووکی‌یەکان کەڵک وەرئەگرێت.
ڕێگەت نەداوە بە کووکی‌یەکان.
ڕێگەیان پێ بدەو و دیسان تێبکۆشە.',
'nocookiesfornew' => 'ھەژماری بەکارھێنەری دروست نەکرا، چون ناتوانین سەرچاوەکەی پشتڕاست بکەینەوە.
دڵنیا بە کوکییەکانت چالاک کردووە، پەڕەکە بار بکەوە و دیسان ھەوڵ بدە.',
'noname' => 'ناوی بەکارهێنەرییەکی گۆنجاوت دیاری نەکردووه.',
'loginsuccesstitle' => 'سەرکەوتی بۆ چوونە ژوورەوە',
'loginsuccess' => "'''ئێستا بە ناوی «$1»ەوە لە {{SITENAME}} چوویتەتەژوورەوە.'''",
'nosuchuser' => 'بەکارھێنەرێک بە ناوی «$1» نیە.
ناوی بەکارھێنەر بە گەورە و بچووک بوونی پیتەکان ھەستیارە.
ڕێنووسەکەت چاولێکەرەوە، یان [[Special:UserLogin/signup|ھەژمارێکی نوێ دروست بکە]].',
'nosuchusershort' => 'بەکارهێنەر بە ناوی "$1" نیە.
چاو لە ڕێنووسەکە بکە.',
'nouserspecified' => 'دەبێ ناوی بەکارهێنەر دابین‌ بکەی.',
'wrongpassword' => 'تێپەڕوشەی ھەڵە.
تکایە دووبارە تێبکۆشە.',
'wrongpasswordempty' => 'تێپەڕەوشەی لێدراو بەتاڵبوو.
تکایە هەوڵ بدەوە.',
'passwordtooshort' => 'تێپەڕوشەکەت لانی کەم دەبێ {{PLURAL:$1|١ پیت|$1 پیت}} بێت.',
'password-name-match' => 'تێپەڕوشەکەت ئەبێ جیاواز بێت لە ناوی بەکارهێنەریت.',
'mailmypassword' => 'تێپەڕوشەیەکی نوێ بنێرە بۆ ئیمەیلەکەم',
'passwordremindertitle' => 'تێپەڕوشەیەکی نوێی کاتی بۆ  {{SITENAME}}',
'passwordremindertext' => 'کەسێک (لەوانەیە خۆت، لە ئای‌پی ئەدرەسی $1) داوای تێپەڕوشەیەکی نوێی کردووە بۆ {{SITENAME}} ($4). تێپەڕوشەیەکی کاتی بۆ بەکارهێنەر «$2» دروستکراو و وەک «$3» دانراوه. ئەگەر ئەمە داخوازی تۆ بووە، پێویستت بەوەیە ئێستا بچیتە ژوورەوە و تێپەڕوشەیەکی نوێ هەڵبژێریت. ماوەی‌ تێپەڕوشە کاتییەکەت لە {{PLURAL:$5|یەک ڕۆژدا|$5 ڕۆژدا}} بەسەردەچێت.

ئەگەر کەسێکی تر ئەم داوایەی کردووە یان تێپەڕوشەکەت هاتووەتەوە بیرت و ئیتر پێویستت بە گۆڕانی نییە، دەتوانی گوێ بەم پەیامە نەدەیت و لە تێپەڕوشە کۆنەکەت کەڵک وەربگری.',
'noemail' => 'ھیچ ئەدرەسێکی ئیمەیل تۆمار نەکراوە بۆ بەکارھێنەر « $1 ».',
'noemailcreate' => 'دەبێ ناونیشانێکی دروستی ئیمەیل بنووسی',
'passwordsent' => 'تێپەڕوشەیەکی نوێ ناردرا بۆ ئەدرەسی ئیمەیلی تۆمارکراوی «$1».
تکایە دوای وەرگرتنی دیسان بچۆ ژوورەوە.',
'blocked-mailpassword' => 'ئادرەسی ئای‌پی تۆ بۆ دەستکاری کردن بەستراوه بۆیە بۆ بەرگری لە بەکارهێنانی نابەجێ ئەنجامی گەڕانەوەی تێپەڕوشە ڕیگە نەدراوە.',
'eauthentsent' => 'ئی‌مەیلێکی بڕواپێکردن ناردرا بۆ ئەدرەسی ئی‌مەیلی پاڵێوراو. <br />
پێش ئەوەی ئی‌مەیلی‌تر بنێردرێ بۆ ئەم هەژمارە، بۆ ئەوەی بڕوات پێ‌بکرێ کە ئەو هەژمارە بەڕاستی هین تۆیە، دەبێ ڕێنوماییەکانی ناو ئەو ئی‌مەیلە هەنگاو بە هەنگاو ئەنجام بدەیت.',
'throttled-mailpassword' => 'بیرهێنەرەوەیەکی وشەی نهێنی پێش ئەمە لە {{PLURAL:$1|کاتژمێر}}ی ڕابردوودا ناردراوە.
بۆ بەرگری لە بەکارهێنانی خراپ، تاکە یەک بیرهێنەرەوەی وشەی نهێنی هەر {{PLURAL:$1|کاتژمێر}} دەنێردرێت.',
'mailerror' => 'هەڵە ڕوویدا لە ناردنی ئیمەیل: $1',
'acct_creation_throttle_hit' => 'بینەرانی ویکی بەکەڵک وەرگرتن لەم ئای‌پی ئەدرەسەی تۆ لە ڕۆژانی ڕابردوودا، دەستیان کردە بە درووست‌کردنی {{PLURAL:$1|هەژمارە}}، کە زۆرینە ڕیگەپێدان لە یەک ماوە‌دایە.
وەک ئەنجامی ئەو ڕووداوە، ئەو بینەرانی لەم ئای‌پی ئەدرەسە کەڵک وەر دەگرن لەم کاتەدا ناتوانن هەژماری دیکە درووست‌بکەن.',
'emailauthenticated' => 'ئیمەیلەکەت بە ڕاست ناسرا لە $3ی $2 دا',
'emailnotauthenticated' => 'ئیمەیلەکەت ھێشتا نەناسراوە.
ھیچ ئیمەیلێک بۆ ئەم بابەتانەی خوارەوە نانێردرێت.',
'noemailprefs' => 'بۆ کەوتنە کاری ئەو تایبەتمەندیانە، لە هەڵبژاردەکانت ئەدرەسەکی ئی‌مێڵ دابین بکە.',
'emailconfirmlink' => 'ئیمەیلەکەت پشت‌ڕاست بکەرەوە',
'invalidemailaddress' => 'ناونیشانی ئیمەیل پەسند نەکرا، چون لەوە دەچێت شێوازێکی نادروستی ھەبێت.
تکایە ناونیشانێک بە شێوازی دروست بنووسە یان ئەو بەشە واڵا بھێڵەوە.',
'emaildisabled' => 'ئەم ماڵپەڕە ناتوانێ ئیمەیل بنێرێ.',
'accountcreated' => 'ھەژمار دروست کرا',
'accountcreatedtext' => 'هەژماری بەکارهێنەری [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|لێدوان]]) دروست کراوە.',
'createaccount-title' => 'درووست‌کردنی هەژمارە بۆ {{SITENAME}}',
'createaccount-text' => 'کەسێک هەژمارەیەکی بۆ ئی‌مێڵ ئەدرەسەکی تۆ لەسەر {{SITENAME}} ($4) بەناوی "$2"، بە وشەی نهێنی "$3".
ئێستا دەبێ بڕۆیتە ژوورەوە و وشەی نهێنی بگۆڕیت.

ئەگەر ئەو هەژمارە بە هەڵە درووست‌کراوە، ئەم برووسکە لە بەرچاو مەگرە.',
'login-throttled' => 'ژمارەیەکی زۆر هەوڵت داوە بۆ چوونە ژوورەوە.
تکایە پێش هەوڵی دووبارە، نەختێک بوەستە.',
'loginlanguagelabel' => 'زمان: $1',

# Change password dialog
'resetpass' => 'گۆڕینی تێپەڕوشە',
'resetpass_announce' => 'بە کۆدی کاتیی ئیمەیل‌کراو ھاتوویتە ژوورەوە.
بۆ دوایی ھاتنی چوونە ژوورەوە، ئەشێ تێپەڕوشەیەکی نوێ ھەڵبژێری لێرە:',
'resetpass_text' => '<!-- تێپه‌ڕه‌وشه‌ی هه‌ژماره‌كه‌ سفر بكه‌ره‌وه‌ -->',
'resetpass_header' => 'گۆڕینی تێپەڕوشەی ھەژمار',
'oldpassword' => 'تێپەڕوشەی پێشو:',
'newpassword' => 'تێپەڕوشەی نوێ:',
'retypenew' => 'تێپەڕوشەی نوێ دوبارە بنووسەوە:',
'resetpass_submit' => 'تێپەڕوشە رێکخە و بچۆ ژوورەوە',
'changepassword-success' => 'تێپەروشەکەت بە سەرکەوتوویی گۆڕدرا. ئێستا چوونە ژوورەوەت...',
'resetpass_forbidden' => 'تێپەڕوشەکە ناگۆڕدرێت',
'resetpass-no-info' => 'بۆ گەیشتنی راستەوخۆ بەم پەڕە ئەشێ بچیتە ژوورەوە.',
'resetpass-submit-loggedin' => 'تێپەڕوشە بگۆڕە',
'resetpass-submit-cancel' => 'ھەڵوەشاندنەوە',
'resetpass-wrong-oldpass' => 'تێپەڕوشەی ھەنووکەیی یان تێپەڕوشەی کاتی ھەڵەیە.
وا دیارە تێپەڕوشەکەت بە سەرکەوتوویی گۆڕدراوە یان داوای تێپەڕوشەیەکی نوێت کردووە.',
'resetpass-temp-password' => 'تێپەڕوشەی کاتی:',

# Special:PasswordReset
'passwordreset' => 'دووبارە ڕێکخستنەوەی تێپەڕوشە',
'passwordreset-legend' => 'دووبارە ڕێکخستنەوەی تێپەڕوشە',
'passwordreset-username' => 'ناوی بەکارھێنەری:',
'passwordreset-domain' => 'پاوان:',
'passwordreset-capture' => 'بینینی ئیمەیڵی ئەنجام؟',
'passwordreset-email' => 'ئەدرەسی ئیمەیڵ:',
'passwordreset-emailtitle' => 'وردەکارییەکانی ھەژمار لە {{SITENAME}}',
'passwordreset-emailtext-ip' => '‫کەسێک (لەوانەیە خۆت، بە ناونیشانی ئایپیی $1) داوای ڕیسێتکردنەوەی تێپەڕوشەکەت لە {{SITENAME}}دا ($4) کردووە. {{PLURAL:$3|ھەژماری بەکارھێنەریی ژێرەوە پەیوەندیی ھەیە|ھەژمارە بەکارھێنەرییەکانی ژێرەوە پەیوەندییان ھەیە}} بەم ناونیشانەی ئیمەیلەوە:

$2

{{PLURAL:$3|ئەم تێپەڕوشە کاتییە|ئەم تێپەڕوشە کاتییانە}} لە {{PLURAL:$5|ڕۆژێک|$5 ڕۆژ}}دا بەسەردەچێت.
دەبێ بچیتە ژوورەوە و ھەر ئێستا تێپەڕوشەیەکی نوێ ھەڵبژێریت. ئەگەر کەسێکی تر ئەم داواکارییەی کردووە،
یان ئەگەر تێپەڕوشە سەرەتاییەکەت ھاتووەتەوە بیرت و ئیتر ناتەوێ بیگۆڕی، دەتوانی گوێ بەم پەیامە نەدەیت و ھەر لە تێپەڕوشە کۆنەکەت کەڵک وەربگریت.',
'passwordreset-emailtext-user' => '‫بەکارھێنەر $1 لە {{SITENAME}} ڕیسێتکردنەوەی تێپەڕوشەکەت لە {{SITENAME}}دا ($4) کردووە. {{PLURAL:$3|ھەژماری بەکارھێنەریی ژێرەوە پەیوەندیی ھەیە|ھەژمارە بەکارھێنەرییەکانی ژێرەوە پەیوەندییان ھەیە}} بەم ناونیشانەی ئیمەیلەوە:

$2

{{PLURAL:$3|ئەم تێپەڕوشە کاتییە|ئەم تێپەڕوشە کاتییانە}} لە {{PLURAL:$5|ڕۆژێک|$5 ڕۆژ}}دا بەسەردەچێت.
دەبێ بچیتە ژوورەوە و ھەر ئێستا تێپەڕوشەیەکی نوێ ھەڵبژێریت. ئەگەر کەسێکی تر ئەم داواکارییەی کردووە، یان ئەگەر تێپەڕوشە سەرەتاییەکەت ھاتووەتەوە بیرت و ئیتر ناتەوێ بیگۆڕی، 
دەتوانی گوێ بەم پەیامە نەدەیت و ھەر لە تێپەڕوشە کۆنەکەت کەڵک وەربگریت.',
'passwordreset-emailelement' => 'ناوی بەکارھێنەری: $1
تێپەڕوشەی کاتی: $2',
'passwordreset-emailsent' => 'ئیمەیلێکی ڕیسێتکردنەوەی تێپەڕوشە نێردرا.',
'passwordreset-emailsent-capture' => 'ئیمەیلێکی ڕیسێتکردنەوەی تێپەڕوشە نێردرا، کە لە ژێرەوە نیشان دراوە.',
'passwordreset-emailerror-capture' => 'ئیمەیلێکی ڕیسێتکردنەوەی تێپەڕوشە نێردرا، کە لە ژێرەوە نیشان دراوە، بەڵام ناردنەکەی بۆ {{GENDER:$2|بەکارھێنەر}} سەرکەوتوو نەبوو: $1',

# Special:ChangeEmail
'changeemail' => 'ئەدرەسی ئیمەیڵ بگۆڕە',
'changeemail-header' => 'ئەدرەسی ئیمەیلی ھەژمار بگۆڕە',
'changeemail-no-info' => 'بۆ گەیشتنی راستەوخۆ بەم پەڕە دەبێت بچیتە ژوورەوە.',
'changeemail-oldemail' => 'ئەدرەسی ئیمەیڵی ئێستا:',
'changeemail-newemail' => 'ئەدرەسی ئیمەیڵی نوێ:',
'changeemail-none' => '(ھیچ)',
'changeemail-password' => 'تێپەڕوشەکەت لە {{SITENAME}}:',
'changeemail-submit' => 'ئەمەیڵ بگۆڕە',
'changeemail-cancel' => 'ھەڵیوەشێنەوە',

# Edit page toolbar
'bold_sample' => 'دەقی ئەستوور',
'bold_tip' => 'دەقی ئەستوور',
'italic_sample' => 'دەقی لار',
'italic_tip' => 'دەقی لار',
'link_sample' => 'نێوی بەستەر',
'link_tip' => 'بەستەری ناوخۆ',
'extlink_sample' => 'http://www.example.com سەردێڕی بەستەر',
'extlink_tip' => 'بەستەری دەرەکی (لەبیرت بێ نووسینی پێشگری http:// )',
'headline_sample' => 'دەقی سەردێڕ',
'headline_tip' => 'سەردێڕی ئاست ۲',
'nowiki_sample' => 'لەگەرە دەقی نەڕازراو تێ‌بخە',
'nowiki_tip' => 'لەبەرچاو نەگرتنی دارشتنەکانی ویکی',
'image_sample' => 'نموونە.jpg',
'image_tip' => 'وێنەی نێو دەق',
'media_sample' => 'نموونە.ogg',
'media_tip' => 'لینکی پەڕگە',
'sig_tip' => 'ئیمزاکەت بە مۆری ڕێکەوتەوە',
'hr_tip' => 'هێڵی ئاسۆیی (دەگمەن بەکاری بێنە)',

# Edit pages
'summary' => 'کورتەی دەستکاری:',
'subject' => 'بابەت/سەردێڕ:',
'minoredit' => 'ئەمە دەستکارییەکی بچووکە',
'watchthis' => 'ئەم پەڕەیە بخە ژێر چاودێری',
'savearticle' => 'پەڕەکە پاشەکەوت بکە',
'preview' => 'پێشبینین',
'showpreview' => 'پێشبینین نیشان بدە',
'showlivepreview' => 'پێشبینینی ڕاسته‌وخۆ',
'showdiff' => 'گۆڕانکارییەکان نیشان بدە',
'anoneditwarning' => "'''ھۆشیار بە:''' نەچوویتە ژوورەوە.
ناونیشانی IPی تۆ لە مێژووی دەستکارییەکانی ئەم پەڕەیەدا تۆماردەکرێت.",
'anonpreviewwarning' => '«نەڕۆشتوویتە ژوورەوە. پاشەکەوتکردن، ئەدرەسی IPەکەت لە مێژووی دەستکاریی ئەم پەڕە تۆمار دەکات.»',
'missingsummary' => "'''وە بیر خستنەوە:''' پوختەیەکت نەنووسیوە بۆ چۆنیەتی گۆڕانکارییەکەت.
ئەگەر جارێکی تر پاشکەوت کردن لێبدەی، بێ پوختە تۆمار دەکرێ.",
'missingcommenttext' => 'تکایە لە خوارەوە شرۆڤەیەک بنووسە.',
'missingcommentheader' => "'''بیرهێنانەوە:''' بۆ ئەم بۆچوونەت سەردێڕ\\بابەت ڕاچاو نەکردووە.
ئەگەر دیسان «{{int:savearticle}}» لێبدەی، دەستکاریەکەت بێ سەردێڕ یان بابەت پاشەکەوت دەبێ.",
'summary-preview' => 'پێشبینینی کورتە:',
'subject-preview' => 'پێشبینینی بابەت/سەردێڕ:',
'blockedtitle' => 'به‌کار هینه‌ر له‌کار خراوه',
'blockedtext' => "'''ناوی بەکارهێنەری یان ئای‌پی ئەدرەسی تۆ بەربەست‌ کراوە.'''

بەربەست لە لایەن $1 کراوە.
هۆکاری بەربەست کردن ''$2''ە.

* دەستپێکی بەربەست‌کران: $8
* کۆتایی هاتنی بەربەست‌کران: $6
* بابەتی بەربەست: $7

بۆ وتووێژ سەبارەت بە بەربەست‌کرانەکە دەبێ پەیوەندی بکەی بە $1 یان یەکێ دی لە [[{{MediaWiki:Grouppage-sysop}}|بەڕێوبەران]].
لە بیرت بێ تاکوو ئیمەیل ئەدرەسێکی بڕوا پێ‌کراو لە [[Special:Preferences|ھەڵبژاردەکانی بەکارھێنەر]] ڕاچاو نەکەی، نابێت لە هەلی «ئیمەیل ناردن بۆ ئەم بەکارهێنەرە» کەڵک وەر بگری؛ کەڵک وەرگرتن لەوە بەربەست نەکراوە بۆت.

ئای‌پی ئەدرەسی ئێستای تۆ $3 و پێناسەی بەربەست‌کراو #$5.
تکایە لە هەر پرس و داواکاریەکت‌دا هەموو وردەکاریەکانی سەرەوە بگونجێنە.",
'autoblockedtext' => 'ئای‌پی ئەدرەسی تۆ بە شێوەی خۆکار بەربەست کراوە چونکە لە لایەن بەکارهێنەرێکی دی کەڵکی لێ وەرگیراوە کە لە لایەن $1 بەربەست کراوە.<br />
ئەمە هۆکارەکەیەتی:<br /><br />
:\'\'$2\'\'<br /><br />
* دەستپێکی بەربەست‌کران: $8<br />
* کۆتایی هاتنی بەربەست‌کران: $6<br />
* بابەتی بەربەست: $7<br /><br />

دەبێ پەیوەندی بکەی بە $1 یان یەکێ دی لە [[{{MediaWiki:Grouppage-sysop}}|بەڕێوبەران]] بۆ وتووێژ سەبارەت بە بەربەست‌کرانەکە.<br /><br />

لە بیرت بێ تاکوو ئەمەیل ئەدرەسێکی بڕوا پێ‌کراو لە [[Special:Preferences|ھەڵبژاردەکانی بەکارھێنەر]] ڕاچاو نەکەی، نابێت لە هەلی "ئی‌مەیل ناردن بۆ ئەم بەکارهێنەرە" کەڵک وەر بگری؛ کەڵک وەرگرتن لەوە بەربەست نەکراوە بۆت.<br /><br />

ئای‌پی ئەدرەسی ئێستای تۆ $3 و پەێناسەی بەربەست‌کراو #$5.<br />
تکایە لە هەر پرس و داواکاریەکت‌دا هەموو وردەکاریەکانی سەرەوە بگونجێنە.',
'blockednoreason' => 'هیچ هۆکارێک نەدراوە',
'whitelistedittext' => 'بۆ دەستکاریی پەڕەکان دەبێ $1.',
'confirmedittext' => 'پێویستە پێش هەرجۆرە دەستکاریەکی لاپەڕەکان ئەدرەسی ئیمەیلت ڕاچاو کردبێت .<br />
تکایە لە [[Special:Preferences|ھەڵبژاردەکانی بەکارھێنەر]] ئی‌مەیلەکەت دانێ و بڕواپێکراوی بکە.',
'nosuchsectiontitle' => 'بەش نەدۆزرایەوە',
'nosuchsectiontext' => 'هەوڵی دەستکاریکردنی بەشێکت داوە کە بوونی نیە.
لەوانەیە لەو کاتە خەریکی بینینی پەڕە بوویت گۆزرابێتەوە یان سڕابێتەوە.',
'loginreqtitle' => 'پێویستە بچییە ژوورەوە',
'loginreqlink' => 'بچییە ژوورەوە',
'loginreqpagetext' => 'بۆ دیتنی لاپەڕەکانی دیکە دەبێ $1 .',
'accmailtitle' => 'وشه‌ی نهێنی ناردرا.',
'accmailtext' => "تێپەڕوشەیەکی هەرەمەکی درووست‌کراو بۆ [[User talk:$1|$1]] ناردرا بۆ $2 .

کاتێ چوویتە ‌ژوورەوە، لە ''[[Special:ChangePassword|گۆڕینی تێپەڕوشە]]'' دەتوانی وشەی تێپەڕبوون بۆ ئەم هەژمارە نوێیە بگۆڕی.",
'newarticle' => '(نوێ)',
'newarticletext' => "بە دوای بەستەری پەڕەیەک کەوتووی کە ھێشتا دروست نەکراوە.
بۆ دروست کردنی پەڕەکە، لە چوارچێوەکەی خوارەوە دەست بکە بە تایپ کردن. (بۆ زانیاری زورتر
[[{{MediaWiki:Helppage}}|یارمەتی]] ببینە).
ئەگەر بە ھەڵەوە ھاتویتە ئێرە، لە سەر دوگمەی '''back'''ی وێبگەڕەکەت کلیک بکە.",
'anontalkpagetext' => "----''ئەمە لاپەڕەی وتووێژە بۆ بەکارهێنەرێکی نەناسراوە کە هێشتا هەژمارەی درووست‌نەکردووه یان کەڵکی‌ لێ وەرناگرێ .
لەبەر ئەوە مەجبوورین ئای‌پی ئەدرەسەکی ژمارەیی بۆ ناساندنی بەکار بێنین.
ئای‌پی ئەدرەسی وا لەوانەیه لە لایەن چەندین بەکارهێنەروە بەکاربێت.
ئەگەر تۆ بەکارهێنەرێکی نەناسراوی و هەست ئەکەی ئەم لێدوانە پەیوەندی بە تۆوە نیە تکایە [[Special:UserLogin/signup|ھەژمارێکی نوێ دروست بکە]] یان [[Special:UserLogin|بچۆ ژوورەوە]] لەبەر ئەوەی لەداهاتوودا دەگەڵ بەکارهێنەرانی‌ نەناسراوی دی تێکەڵ نەکرێیت. ''",
'noarticletext' => 'ھەنووکە ھیچ دەقێک لەم پەڕەیەدا نییە.
دەتوانی بۆ ئەم ناوە لە [[Special:Search/{{PAGENAME}}|پەڕەکانی تردا بگەڕێی]]، <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} لە لۆگەکاندا بگەڕێی]، یان [{{fullurl:{{FULLPAGENAME}}|action=edit}} ئەم پەڕەیە دەستکاری بکەیت]</span>.',
'noarticletext-nopermission' => 'ھەنووکە ھیچ دەقێک لەم پەڕەیەدا نییە.
دەتوانی لە پەڕەکانی تردا [[Special:Search/{{PAGENAME}}|بۆ ئەم ناوە بگەڕێی]]، یان <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} لە لۆگە پەیوەندیدارەکاندا بگەڕێی]</span>، بەڵام ناتوانی ئەم پەڕەیە دروست بکەی.',
'userpage-userdoesnotexist' => 'هەژماری بەکارهێنەری "<nowiki>$1</nowiki>" تۆمار نەکراوە.<br />
گەر دەتەوێ ئەم لاپەڕە درووست‌کەی یان دەستکاری بکەی تکایە تاقی‌بکەوە .',
'userpage-userdoesnotexist-view' => 'ھەژماری بەکارھێنەریی «$1» تۆمار نەکراوە.',
'blocked-notice-logextract' => 'ھەنووکە ئەم بەکارھێنەرە بەربەست کراوە.
دوایین بابەتی لۆگی بەربەستن لە ژێرەوە ھاتووە:',
'clearyourcache' => "تێبینی:''' لە دوای پاشەکەوت کردن، لەوانەیە  بۆ بینینی گۆڕانکارییەکان پێویست بێ cacheی وێبگەڕەکەت پاکبکەیتەوە.
* '''Firefox / Safari:''' دوگمەی ''Shift'' بگرە کاتێک لەسەر ''Reload''دا کرتە دەکەی، یان ھەرکام لە ''Ctrl-F5'' یان ''Ctrl-R'' لێبدە (''⌘-R'' لەسەر Mac دا)
* '''Google Chrome:''' دوگمەکانی ''Ctrl-Shift-R'' لێبدە  (''⌘-Shift-R'' لەسەر Mac دا)
* '''Internet Explorer:''' دوگمەی ''Ctrl'' بگرە کاتێک لەسەر  ''Refresh''دا کرتە دەکەی، یان ''Ctrl-F5'' لێبدە
* '''Opera:''' لە ڕێگەی ''Tools → Preferences'' ەوە cacheەکە بسڕەوە.",
'usercssyoucanpreview' => "'''سەرچەشن:''' «{{int:showpreview}}» بەکاربێنە بۆ تاقی‌کردنەوەی CSS نوێ‌کەت، پێش پاشەکەوت‌کردن.",
'userjsyoucanpreview' => "'''سەرچەشن:''' «{{int:showpreview}}» بەکاربێنە بۆ تاقی‌کردنەوەی جاڤاسکریپتە نوێ‌کەت، پێش پاشەکەوت‌کردن.",
'usercsspreview' => "'''له‌یادت بێ که‌ ئێسته‌ ته‌نها پێشبینینی CSS به‌کارهێنه‌ریه‌که‌ت ده‌که‌ی.'''
'''هێشتا پاشه‌که‌وت نه‌بووه !'''",
'userjspreview' => "'''لەیادت بێ کە ئێستە تەنها پێشبینین\\تاقی‌کردنەوەی جاڤاسکریپتی بەکارهێنەریەکەت دەکەی.'''
'''هێشتا پاشەکەوت نەبووه !'''",
'sitecsspreview' => "'''له‌یادت بێ که‌ ئێسته‌ ته‌نها پێشبینینی ئەم CSS ده‌که‌ی.'''
'''هێشتا پاشه‌که‌وت نه‌کراوە !'''",
'sitejspreview' => "'''لە بیرت نەچێت ئەمە تەنیا پێشبینینی ئەم کۆدەی جاڤاسکریپتە.'''
'''گۆڕانکارییەکانت ھێشتا پاشەکەوت نەکراون!'''",
'userinvalidcssjstitle' => "'''ئاگادارکردنەوە:''' پێست نیە بۆ \"\$1\".
لەیادت بێ کە لاپەڕەکانی‌ .css و .js لە بابەت بە پیتی بچووک کەڵک وەر ئەگرن. وەک {{ns:user}}:Foo/vector.css نە وەک {{ns:user}}:Foo/Vector.css .",
'updated' => '(نوێ‌کراوە)',
'note' => "'''تێبینی:'''",
'previewnote' => "'''لە بیرت نەچێت ئەمە تەنیا پێشبینینە.'''
گۆڕانکارییەکانت ھێشتا پاشەکەوت نەکراون!",
'continue-editing' => 'چوونە سەر بەشی دەستکاریکردن',
'previewconflict' => 'ئەم پێشبینینە بە تۆ نیشان ئەدات ئەو دەقەی لە شوێنی دەستکاری سەرەوە داتناوە چۆن بەرچاو ئەکەوێت ئەگەر پاشەکەوتی بکەیت.',
'session_fail_preview' => "'''ببوورە! ناتوانین دەستکارییەکەت پێواژۆ بکەین بە ھۆی لەدەستدانی session data.'''
تکایە دیسان ھەوڵبدەوە.
ئەگەر ھێشتا کار ناکات، [[Special:UserLogout|چوونەدەرەوە]] و گەڕانەوەژوورەوە تاقی بکەوە.",
'session_fail_preview_html' => "'''ببوورە! ناتوانین دەستکارییەکەت پێواژۆ بکەین بە ھۆی لەدەستدانی session data.'''

''لەبەر ئەوەی {{SITENAME}} ڕێگەی داوە بە raw HTML، بۆ بەرگری بەرامبەر بە هێرشەکانی جاڤاسکریپت، پێشبینین شاردراوەتەوە.''

'''ئەگەر ئەمە ھەوڵێکی دەستکاریکردنی ڕەوایە، تکایە دیسان ھەوڵبدەوە.'''
ئەگەر ھێشتا کار ناکات، [[Special:UserLogout|چوونە دەرەوە]] گەڕانەوەژوورەوە تاقی بکەوە.",
'token_suffix_mismatch' => "'''دەستکاریەکەت پەسەند نەکرا لەبەر ئەوەی ڕاژەخواز یان وێبگەڕەکەت نووسەکانی خاڵبەندی لەیەک پچڕاندوە.'''<br />
دەستکاریەکەت بۆ بەرگری لە تێکەڵ‌بوونی دەقی لاپەڕەکە وەر نەگیرا.<br />
ئەمە بڕێ‌جار کاتێ ڕوو ئەدات کە لە خزمەتی پرۆکسی سەر وێب کەڵک وەر بگریت.",
'editing' => 'دەستکاریکردنی $1',
'creating' => 'دروستکردنی $1',
'editingsection' => 'دەستکاریکردنی: $1 (بەش)',
'editingcomment' => 'دەستکاریکردنی $1 (بەشی  نوێ)',
'editconflict' => 'کێشەی دەستکاری: $1',
'explainconflict' => "کەسێکی تر ئەم پەڕەیە گۆڕیوە لەو کاتەوە تۆ دەستکاریکردنیت دەستپێکردووە.
بەشی سەرەوەی دەق، شێوازی ئێستای پەڕەکە لە خۆ ئەگرێت.
گۆڕانکاریەکانی تۆش لە بەشی خوارەوەی دەق نیشان‌دراوە.
دەبێ گۆڕانکاریەکانی خۆت لەگەڵ ئێستەی پەڕەکەدا تێکەڵبکەی.
'''تەنیا''' ئەو دەقەی بەشی سەرەوە پاشەکەوت دەبێت، کاتێ «{{int:savearticle}}\" لێدەدەی.",
'yourtext' => 'دەقی تۆ',
'storedversion' => 'پیاچوونەوەی ھەڵگیراو',
'nonunicodebrowser' => "'''ئاگاداری:  وێبگەڕەکەت لە یوونی‌کۆد پاڵپشتی ناکات .'''<br />
پرۆسەی چارەسەرکردن لە کاردایە بۆ ئەوەی ڕیگەت پێ بدا بە پاراوی دەستکاری لاپەڕەکان بکەیت: ئەو پیتانەی وا ASCII نین لە چوارچێوەی دەستکاری‌کردن‌دا وەک کۆدی ژمارە‌شازدەیی(hexadecimal) نیشان ئەدرێن.",
'editingold' => "'''ئاگاداری:  تۆ خەریکی دەستکاری‌ پێداچوونەوەیەکی کات‌بەسەرچووی ئەم لاپەڕەی.'''<br />
ئەگەر پاشەکەوتی بکەیت، هەموو گۆڕانکاریەکانی پێش ئەم پێداچوونەوە لەدەست ئەڕوا.",
'yourdiff' => 'جیاوازیەکان',
'copyrightwarning' => "تکایە ئاگادار بە کە هەموو بەشدارییەکان لە {{SITENAME}} وا فەرز دەکرێت کە لە ژێر  $2دا بڵاودەبنەوە (سەیری $1 بکە بۆ وردەکاریەکان).
ئەگەر ناتەوێ نووسراوەکانت بە بێبەزەیی دەستکاری بکرێن و بە دڵخواز دابەشبکرێنەوە، مەینێرە بۆ ئێرە.<br />
ھەروەھا تۆ بەڵێنمان پێدەدەی کە خۆت ئەمەت نووسیوە یان لە پاوانێکی گشتی (public domain) یان سەرچاوە ئازادەکانی وەک ئەو وەرتگرتووە.
'''ئەو کارانە کە مافی لەبەرگرتنەوەیان پارێزراوەکان بە بێ وەرگرتنی ئیجازە مەنێرە!'''",
'copyrightwarning2' => "ئاگادار بە کە هەموو بەشدارییەکان لە {{SITENAME}} لەوانەیە بەدەستی بەشداربووانی دیکەوە دەستکاری بکرێن، بگۆڕدرێن یا بسڕێنەوە.
ئەگەر ناتەوێ نووسراوەکانت بێبەزەیی دەستکاری بکرێن، ھەر مەینێرە بۆ ئێرە.<br />
ھەروەھا تۆ بەڵێنمان پێدەدەی کە خۆت ئەمەت نووسیوە یان لە پاوانێکی گشتی (public domain) یان سەرچاوە ئازادەکانی وەک ئەو وەرتگرتووە (سەیری $1 بکە بۆ وردەکاریەکان).
'''ئەو کارانە کە مافی لەبەرگرتنەوەیان پارێزراوەکان بە بێ وەرگرتنی ئیجازە مەنێرە!'''",
'longpageerror' => "'''ھەڵە: ئەو دەقە تۆ ناردووتە {{PLURAL:$1|یەک کیلۆبایت|$1 کیلۆبایت}} درێژە، کە درێژترە لە زۆرینەی {{PLURAL:$2|یەک کیلۆبایت|$2 کیلۆبایت}}.'''
ئەمە پاشەکەوت ناکرێت.",
'readonlywarning' => "'''ئاگاداری: بنکەدراوە بۆ چاکردنەوە داخراوە، بۆیە ئێستا ناتوانی دەستکاریەکانت پاشەکەوت بکەیت.'''<br />
باشتر وایە دەقەکە cut و paste بکەیتە ناو پەڕگەیەکی دەق و پاشەکەوتی بکەی بۆ دوایی.<br /><br />
ئەو بەڕێوبەرەی کە دایخستوە، ئەم هۆکارەی بەردەست خستووە: $1",
'protectedpagewarning' => "'''وشیار بە: ئەم پەڕە پارێزراوە بۆ ئەوی تەنیا ئەو بەکارھێنەرانە کە مافەکانی بەڕێوەبەرایەتییان ھەیە بتوانن دەستکاریی بکەن.'''
دوایین لۆگ بۆ ژێدەر لە خوارەوەدا ھاتووە:",
'semiprotectedpagewarning' => "'''ئاگاداری:''' ئەم پەڕە داخراوە بۆ ئەوی تەنھا بەکارھێنەرە تۆمارکراوەکان بتوانن دەستکاریی بکەن.
دوایین لۆگ بۆ ژێدەر لە خوارەوەدا ھاتووە:",
'cascadeprotectedwarning' => "'''ئاگاداری:''' ئەم لاپەڕە داخراوە بۆیە تەنها ئەو کەسانەی مافی بەڕێوبەرایەتی‌یان هەیە ئەتوانن دەستکاری بکەن، چۆنکا ئەمە {{PLURAL:$1|لاپه‌ڕه‌|لاپه‌ڕانه‌}} لە زنجیرەی پارێزراوەکانی لە خۆ گرتووە‌:",
'titleprotectedwarning' => "'''ئاگاداری: ئەم پەڕە داخراوە، بۆئەوەی بۆ درووست‌کردنی [[Special:ListGroupRights|مافە تایبەتەکانت]] پێویستن.'''
بۆ چاوانە دوایین لۆگ لە خوارەوەدا ھاتووە:",
'templatesused' => 'ئەو {{PLURAL:$1|داڕێژە کە لەم پەڕەیەدا بە کارھێنراوە|داڕێژانە کە لەم پەڕەیەدا بە کارھێنراون}}:',
'templatesusedpreview' => 'ئەو {{PLURAL:$1|داڕێژە کە لەم پێشبینینەدا بە کارھێنراوە|داڕێژانە کە لەم پێشبینینەدا بە کارھێنراون}}:',
'templatesusedsection' => 'ئەو {{PLURAL:$1|داڕێژە|داڕێژانە}} کە لەم بەشەدا بە کارھێنراون:',
'template-protected' => '(پارێزراو)',
'template-semiprotected' => '(نیوەپارێزراو)',
'hiddencategories' => 'ئەم پەڕە ئەندامێکی {{PLURAL:$1|١ پۆلی شاراوەیە|$1 پۆلی شاراوەیە}}:',
'edittools' => '<!-- دەقی ئێرە لە ژێری فۆرمی دەستکاری و بارکردندا نیشان دەدرێت. -->',
'nocreatetext' => '{{SITENAME}} توانای درووست‌کردنی لاپەڕە نوێکانی داخستووە.<br />
ئەتوانی بگەڕێتەوە دواوە و یەکێک لەو لاپەڕانەی وا هەن دەستکاری بکەیت ، یان [[Special:UserLogin|بچۆ ژوورەوە یان هەژمارێک درووست‌بکە]]',
'nocreate-loggedin' => 'ئیجازەی دروست کردنی پەڕەی نوێت نیە.',
'sectioneditnotsupported-title' => 'بەش دەستکاریکردنی پشتیوانی ناکرێ',
'sectioneditnotsupported-text' => 'دەستکاریکردنی بەش لە پەڕەدا پشتیوانی ناکرێ.',
'permissionserrors' => 'ھەڵەی ئیجازەکان',
'permissionserrorstext' => 'مافی ئەنجامی ئەوەت نیە لەبەر ئەم {{PLURAL:$1|هۆکار|هۆکارانە}} :',
'permissionserrorstext-withaction' => 'دەسەڵاتت نییە بۆ $2، لەبەر ئەم {{PLURAL:$1|ھۆکارە|ھۆکارانە}}ی خوارەوە:',
'recreate-moveddeleted-warn' => "'''ھۆشیار بە: خەریکی پەڕەیەک دروست‌ دەکەیتەوە کە لە پێشدا سڕاوەتەوە.'''

ئەمە لەبەر چاو بگرە کە دەستکاریکردنی ئەم پەڕەیە بەقازانجە یان نا.
لۆگی سڕینەوە و گواستنەوەی ئەم پەڕەیە بۆ سانایی لێرەدا ھاتووە:",
'moveddeleted-notice' => 'ئەم پەڕەیە سڕاوەتەوە.
لۆگی سڕینەوە و گواستنەوە بۆ پەڕەکە لە ژێرەوە دابین کراوە.',
'log-fulllog' => 'دیتنی لۆگی تەواو',
'edit-hook-aborted' => 'دەستکاری لە لایەن قولاپەوە ھەڵوەشێنرایەوە.
ھۆکارەکەی لەبەر دەست نییە.',
'edit-gone-missing' => 'توانای نوێ‌کردنەوەی لاپەڕەکە نیە.<br />
لەوە دەچی سڕدرابێتەوه.‌',
'edit-conflict' => 'کێشەی دەستکاری.',
'edit-no-change' => 'دەستکاریەکەت بەرچاو نەخرا، لەبەر ئەوەی هیچ گۆڕانکارییەکت لەسەر دەقەکە نەکردووە.',
'postedit-confirmation' => 'دەستکارییەکەت پاشەکەوت کرا.',
'edit-already-exists' => 'تواناییی دروستکردنی پەڕەی نوێ نییە.
ئەمە پەڕەیە پێشتر هەبووە.',
'defaultmessagetext' => 'دەقی پەیامی هەمیشەیی',
'invalid-content-data' => 'دراوەی ناوەرۆکی نادروست',
'editwarning-warning' => 'بەجێ‌هێشتنی ئەم لاپەڕەیە دەبێتە هۆی لە‌دەست چوونی هەموو ئەو گۆڕانکاریانەی کردووتە.',

# Content models
'content-model-wikitext' => 'ویکیدەق',
'content-model-text' => 'دەقی ساکار',
'content-model-javascript' => 'جاڤاسکریپت',
'content-model-css' => 'سی ئێس ئێس',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''ئاگاداری:''' ئەم لاپەڕە ژمارەیەکی زۆر بانگ‌کەری فەنکشێنی لێک‌کەرەوەی لەخۆ گرتوو.<br /><br />
ئەوە دەبێ کەمتر بێت لە $2 {{PLURAL:$2|بانگ‌کەردن|بانگ‌کەردن}} کە ئێستا {{PLURAL:$1|بانگ‌کردنی|بانگ‌کردنی}} تێدایە.",
'expensive-parserfunction-category' => 'ئەو لاپەڕانەی  ژمارەیەکی زۆر بانگ‌کەری فەنکشێنی لێک‌کەرەوەیان لەخۆ گرتووە.',
'post-expand-template-inclusion-warning' => "'''ئاگاداری:''' قەبارەی داڕێژەکە زۆر گەورەیە.
لەوانەیە ھەندێک لە داڕێژەکان لەخۆنەگرێتەوە.",
'post-expand-template-inclusion-category' => 'ئەو لاپەڕانەی وا داڕێژە تیێدا قەبارەی تێپەڕیوە',
'post-expand-template-argument-warning' => "'''ئاگاداری:''' ئەم لاپەڕە لانیکەم یەک بەڵگەی داڕێژە لە خۆ گرتوو کە قەبارەی کردنەوەی زۆر گەورەیە.<br />
ئەم بەڵگە بەکار نەخراوە.",
'post-expand-template-argument-category' => 'ئەو لاپەڕانەی بەڵگەی داڕێژەی بەکار نەخراوی لەخۆ گرتووە',
'parser-template-loop-warning' => 'ئەڵقەی داڕێژە دۆزرایەوە: [[$1]]',
'parser-template-recursion-depth-warning' => 'سنووری قووڵی گەڕانەوەی داڕێژە تێپەڕیوە ($1)',

# "Undo" feature
'undo-success' => 'دەکرێ دەستکاریەکە پووچەڵبکرێتەوە.
تکایە چاو لەو هەڵسەنگاندنەی خوارەوە بکە تا دڵنیا بیت ئەمە ئەوەیە کە‌ دەتویست بیکەی و دواتر گۆڕانکارییەکانی خوارەوە پاشەکەوت بکە بۆ تەواوکردنی پووچەڵکردنەوەکە.',
'undo-failure' => 'لەبەر کێشەی دەست‌تێ‌وەردان، ناتوانی دەستکاریەکە ئەنجام‌نەدراو بکەیت.',
'undo-norev' => 'ناتوانی دەستکاریەکە ئەنجام‌نەدراو بکەی لەبەر ئەوەی بوونی نیە یا سڕدراوەتەوە.',
'undo-summary' => 'گەڕاندنەوەی پێداچوونەوەی $1 لە لایەن [[Special:Contributions/$2|$2]] ([[User talk:$2|وتووێژ]])',

# Account creation failure
'cantcreateaccounttitle' => 'ناتوانرێت هەژمار دروست بکرێت',
'cantcreateaccount-text' => 'درووست‌کردنی هەژمارە بۆ ناونیشانی ئای‌پی (\'\'\'$1\'\'\') لە لایەن [[User:$3|$3]] داخراوە.<br /><br />
$3 هۆکاری "$2" خستوەتەڕوو',

# History pages
'viewpagelogs' => 'لۆگەکانی ئەم پەڕەیە ببینە',
'nohistory' => 'هیچ مێژوویەکی دەستکاری نییە بۆ ئەم پەڕەیە.',
'currentrev' => 'دوایین پیاچوونەوە',
'currentrev-asof' => 'دوایین پێداچوونەوەی $1',
'revisionasof' => 'وەک پیاچوونەوەی $1',
'revision-info' => 'پێداچوونەوی $1 لە لایەن $2',
'previousrevision' => '→پیاچوونەوەی کۆنتر',
'nextrevision' => 'پیاچوونەوەی نوێتر←',
'currentrevisionlink' => 'پیاچوونەوەی ئێستا',
'cur' => 'ئێستا',
'next' => 'پاش',
'last' => 'پێشوو',
'page_first' => 'یەکەمین',
'page_last' => 'دوایین',
'histlegend' => "ھەڵبژاردنی جیاوازی: پیاچوونەوەکان بۆ ھەڵسەنگاندن دیاری بکە و ئینتەر یان دوگمەکەی خوارەوە لێبدە.<br />
ڕێنوێنی: '''({{int:cur}})''' = جیاوازی لەگەڵ دوایین پیاچوونەوە، '''({{int:last}})''' = جیاوازی لەگەڵ پیاچوونەوەی پێشووی، '''{{int:minoreditletter}}''' = دەستکاریی بچووک.",
'history-fieldset-title' => 'گەشتی مێژوو',
'history-show-deleted' => 'تەنیا سڕاوەکان',
'histfirst' => 'کۆنترین',
'histlast' => 'نوێترین',
'historysize' => '({{PLURAL:$1|1 بایت|$1 بایت}})',
'historyempty' => '(پووچ)',

# Revision feed
'history-feed-title' => 'مێژووی پێداچوونەوەکان',
'history-feed-description' => 'مێژووی پیاچوونەوە بۆ ئەم پەڕە لە ویکییەکە',
'history-feed-item-nocomment' => '$1 لە $2',
'history-feed-empty' => 'لاپەڕەی داخوازی‌کراو بوونی نیە.<br />
لەوانەیە لەسەر ویکی سڕدرابێتەوە یان ناوی گۆڕدرابێت.<br />
بۆ لاپەڕەی وەک ئەوە هەوڵی [[Special:Search|گەڕان لەسەر ویکی]] بدە.',

# Revision deletion
'rev-deleted-comment' => '(کورتەی دەستکاری سڕایەوە)',
'rev-deleted-user' => '(ناوی بەکارهێنەر سڕایەوە)',
'rev-deleted-event' => '(لۆگی کردەوە سڕایەوە)',
'rev-deleted-text-permission' => "ئەم پێداچوونەوەیە لەم پەڕەیە '''سڕدراوەتەوە'''.
وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی سڕینەوە]دا دەست دەکەوێت.",
'rev-deleted-text-unhide' => "ئەم پێداچوونەوەیە لەم پەڕەیە '''سڕدراوەتەوە'''.
وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی سڕینەوە]دا دەست دەکەوێت.
ھێشتا ئەگەر بتەوێ دەتوانی [$1 ئەم پێداچوونەوەیە ببینی].",
'rev-suppressed-text-unhide' => "ئەم پێداچوونەوەیە لەم پەڕەیە '''بەرگری لێ‌کراوە'''.
وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی بەرگری]دا دەست دەکەوێت.
ھێشتا ئەگەر بتەوێ دەتوانی [$1 ئەم پێداچوونەوەیە ببینی].",
'rev-deleted-text-view' => "ئەم پێداچوونەوەیە لەم پەڕەیە '''سڕدراوەتەوە'''.
ئێستا دەتوانی بیبینی؛ وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی سڕینەوە]دا دەست دەکەوێت.",
'rev-suppressed-text-view' => "ئەم پێداچوونەوەیە لەم پەڕەیە '''بەرگری لێ‌کراوە'''.
ئێستا دەتوانی بیبینی؛ وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی بەرگری]دا دەست دەکەوێت.",
'rev-deleted-no-diff' => "ناتوانی ئەم جیاوازیە ببینی لەبەر ئەوەی یەکێک لە پێداچوونەوەکان '''سڕدراوەتەوه'''‌.<br />
لەوانەیە وردەکاری سەبارەت بەوە لێرەدا دەست کەوێ : [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی بەرگری].",
'rev-suppressed-no-diff' => "ناتوانی ئەم چیاوازییە ببینی چون یەکێک لە پێداچوونەوەکان '''سڕدراوەتەوە'''.",
'rev-deleted-unhide-diff' => "یەکێک لە پێداچوونەوەکانی ئەم جیاوازیە '''سڕدراوەتەوه'''.
وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی سڕینەوە]دا دەست دەکەوێت.
ھێشتا ئەگەر بتەوێ دەتوانی [$1 ئەم جیاوازییە ببینی].",
'rev-suppressed-unhide-diff' => "یەکێک لە پێداچوونەوەکانی ئەم جیاوازیە '''سڕدراوەتەوه'''.
وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی بەرگری]دا دەست دەکەوێت.
ھێشتا ئەگەر بتەوێ دەتوانی [$1 ئەم جیاوازییە ببینی].",
'rev-deleted-diff-view' => "یەکێک لە پێداچوونەوەکانی ئەم جیاوازییە  '''سڕدراوەتەوە'''.
ئێستا دەتوانی بیبینی؛ وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی سڕینەوە]دا دەست دەکەوێت.",
'rev-suppressed-diff-view' => "یەکێک لە پێداچوونەوەکانی ئەم جیاوازییە '''بەرگری لێ‌کراوە'''.
ئێستا دەتوانی بیبینی؛ وردەکاری سەبارەت بەوە لە [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} لۆگی بەرگری]دا دەست دەکەوێت.",
'rev-delundel' => 'نیشان بدە/بشارەوە',
'rev-showdeleted' => 'نیشان بدە',
'revisiondelete' => 'سڕینەوە/ھێنانەوەی پێداچوونەوەکان',
'revdelete-nooldid-title' => 'مەبەستی پێداچوونەوەی نادیار',
'revdelete-nooldid-text' => 'پێداچوەنەوەی مەبەستت ڕاچاو نەکردە بۆ ئەنجامی ئەم فەنکشێنە یان ئەو پێداچوونەوەی ڕاچاوت کردە بوونی نیە، یا خەریکی هەوڵی داشاردنی پێداچوونەوهی ئێستا‌ ئەدەی.',
'revdelete-nologtype-title' => 'جۆری لۆگ ڕاچاو نەکراوە',
'revdelete-nologtype-text' => 'جۆری لۆگت ڕاچاو نەکردە بۆ خستنە‌کاری ئەم کردەوە.',
'revdelete-nologid-title' => 'پێ‌دراوەی لۆگی نەناسراو',
'revdelete-nologid-text' => 'بۆ ئەنجامی ئەم فەنکشێنە یا لۆگی ڕووداوی مەبەستت ڕاچاو نەکردووە یان ئەو پێ‌دراوەی ڕاچاوت کردە بوونی نیە.',
'revdelete-no-file' => 'ئەو پەڕگەی ڕاچاوت کردووە بوونی نیە.',
'revdelete-show-file-confirm' => 'ئایا دڵنیایت دەتەوێ پێداچوونەوەی سڕاوەی پەڕگەی "<nowiki>$1</nowiki>" لە $2، لە $3دا ببیینی؟',
'revdelete-show-file-submit' => 'بەڵێ',
'revdelete-selected' => "'''{{PLURAL:$2|پێداچوونەوەی هەڵبژێراوی|پێداچوونەوەکانی هەڵبژێراوی}} [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|لۆگی ڕووداوەی هەڵبژێراو|لۆگی ڕووداوە هەڵبژێراوەکان}}:'''",
'revdelete-text' => "'''پێداچوون و ڕووداوە سڕاوەکان هێشتا لە لاپەڕەی مێژوو و لۆگەکان دەست دەکەون، بەڵام ناوەڕۆکی ھێندێکیان بەرچاوی گشتیی ناکەون.'''<br />
بەڕێوبەرانی دیکە لە {{SITENAME}}دا، هێشتا دەتوانن دەستکارییە شاراوەکان ببینن و لە ڕێگەی ھەر ئەم فۆڕمەوە بیانگەڕێننەوە، مەگەر ئەوەی بەربەستی دیکە داندرابێت.",
'revdelete-confirm' => 'تکایە بەڵێن بدە کە دەتەوێ ئەوە بکەی و لە ئەنجامەکانی ئەوە ئاگاداریت و بە پێی [[{{MediaWiki:Policy-url}}|سیاسەتنامە]] ئەنجامی ئەدەی.',
'revdelete-suppress-text' => "بەرگری دەبێ '''تەنها''' بۆ ئەم بابەتانە بەکاربهێندرێت:<br />
* سووکایەتیکردن بە کەسایەتییەک<br />
* بڵاوکردنەوەی زانیاریی تاکەکەسی نەگونجاو<br />
*: '' ناونیشانی ماڵ یا ژمارە تەلەفۆن و وەک ئەمانە.''<br />",
'revdelete-legend' => 'سنووردارکردنی دەرکەوتن',
'revdelete-hide-text' => 'شاردنەوەی دەقی پێداچوونەوە',
'revdelete-hide-image' => 'ناوەڕۆکی پەڕگە بشارەوە',
'revdelete-hide-name' => 'داشاردنی مەبەست و کردەوە',
'revdelete-hide-comment' => 'شاردنەوەی کورتەی دەستکاری',
'revdelete-hide-user' => 'شاردنەوەی ناوی بەکارھێنەری/ئای-‌پی دەستکاریکەر',
'revdelete-hide-restricted' => 'بەرگری دراوە لە بەڕێوبەران هەر وەک ئەوانی دیکە',
'revdelete-radio-same' => '(مەیگۆڕە)',
'revdelete-radio-set' => 'بەڵێ',
'revdelete-radio-unset' => 'نا',
'revdelete-suppress' => 'بەرگری دراوە لە بەڕێوبەران هەر وەک ئەوانی دیکە',
'revdelete-unsuppress' => 'لابردنی بەربەستەکان لە سەر پێداچوونەوە گەڕێندراوەکان',
'revdelete-log' => 'هۆکار:',
'revdelete-submit' => 'خستنەکار بۆ سەر پێداچوونەوە {{PLURAL:$1|ھەڵبژێردراوەکە|ھەڵبژێردراوەکان}}',
'revdelete-success' => "'''چۆنیەتی بیندرانی پێداچوونەوە بە سەرکەوتوویی نوێکراوە.'''",
'revdelete-failure' => "'''ناکرێ دەرکەوتنی پێداچوونەوە نوێبکرێتەوە:'''
$1",
'logdelete-success' => "'''بیندرانی لۆگ‌ بە سەرکەوتوویی داندرا.'''",
'logdelete-failure' => "'''بیندرانی لۆگ داناندرێت:'''
$1",
'revdel-restore' => 'چۆنیەتی دەرکەوتن بگۆڕە',
'revdel-restore-deleted' => 'پێداچوونەوە سڕاوەکان',
'revdel-restore-visible' => 'پێداچوونەوە دەرکەوتووەکان',
'pagehist' => 'مێژووی پەڕە',
'deletedhist' => 'مێژوو بسڕەوە',
'revdelete-hide-current' => 'هەڵە لە شاردنەوەی بابەتی ڕیکەوتی $1، کات $2: ئەم پێداچوونەوە ئێستا لەکاردایە.
ناکرێ داشاردرێت.',
'revdelete-show-no-access' => 'هەڵە لە نیشان‌دانی بابەتی ڕیکەوتی $1، کات $2: ئەم بابەتە وەک "بەرگیراو"‌ نیشانکراوە.
دەسەڵاتی دەستپێگەیشتنی ئەوەت نیە.',
'revdelete-modify-no-access' => 'هەڵە لە چاکسازی بابەتی ڕیکەوتی $1، کات $2: ئەم بابەتە وەک "بەرگیراو"‌ نیشان‌ کراوە.
دەسەڵاتی دەستپێگەیشتنی ئەوەت نیە.',
'revdelete-modify-missing' => 'هەڵە لە چاکسازی بابەت خاوەن پێناسەی $1: لە بنکەدراو ون بووە !',
'revdelete-no-change' => "'''ئاگاداری:''' بابەتی ڕێکەوتی $2، کات $1، لە پێش‌دا خاوەن داخوازی هەلبژاردەکانی بیندرانە.",
'revdelete-concurrent-change' => 'هەڵە لە چاکسازی بابەتی ڕێکەوتی $2 کات $1: لەوانەیە کاتێ تۆ هەوڵی چاکسازیت ئەدا، کەسێکی‌تر دۆخەکەی گۆڕابێت.
تکایە چاو لە لۆگەکەی بکە.',
'revdelete-only-restricted' => 'ھەڵە لە شاردنەوەی بابەتی ڕێکەوتی $2ی $1: ناتوانی لە بینینی بابەتەکان لە لایەن بەڕێوبەرانەوە بەرگری بکەیت، مەگەر یەکێکی تر لە ھەڵبژاردەکانی بەرچاوکەوتن ھەڵبژێریت.',
'revdelete-reason-dropdown' => '*ھۆکارە باوەکانی سڕینەوە
** لادان لە مافی لەبەرگرتنەوە
** بۆچوون یان زانیاریی تاکەکەسیی نەشیاو
** ناوی بەکارھێنەریی نەشیاو
** زانیارییەک کە دەتوانێ بوختاناوی بێت',
'revdelete-otherreason' => 'ھۆکاری تر/زیاتر:',
'revdelete-reasonotherlist' => 'ھۆکاری تر',
'revdelete-edit-reasonlist' => 'دەستکاریی ھۆکارەکانی سڕینەوە',
'revdelete-offender' => 'نووسەری پیاچوونەوە:',

# Suppression log
'suppressionlog' => 'لۆگی بەرگری‌کردن',
'suppressionlogtext' => 'خوارەوە لیستێکی سڕینەوەکان و بەربەستنەکانە کە ناوەرۆکێکی شاراوە لە بەڕێوبەرانیان ھەیە.
سەیری [[Special:BlockList|لیستی بەربەستن]] بکە بۆ لیستی ئەو بەرگری و بەربەستنانە ئێستا لەکاردان.',

# History merging
'mergehistory' => 'یەک‌خستنی مێژووەکانی لاپەڕە',
'mergehistory-header' => 'ئەم لاپەڕە دەسەڵاتی ئەوەت پێ‌دەدا پێداچوونەوەکانی مێژووی لاپەڕەیەکی مەبەستت بخەیتە سەر لاپەڕەیەکی نوێ.
ئەرخەیان ببە ئەم گۆڕان‌کاریە لاپەڕە مێژوویەکە بەردەوام دەهێڵێتەوە.',
'mergehistory-box' => 'سەر یەک‌خستنی پێداچوونەوەکانی دوو لاپەڕە:',
'mergehistory-from' => 'سەرچاوەی پەڕە',
'mergehistory-into' => 'پەڕەی مەبەست:',
'mergehistory-list' => 'ئەو مێژووی لاپەڕانە وا توانای سەر یەک‌خستنیان هەیە',
'mergehistory-merge' => 'ئەم پێداچوونەوانەی [[:$1]] دەتواندرێ بخرێتە سەر [[:$2]].
دەتوانی لە ستوونی دوکمە ڕادیۆیەکە بۆ تەنها خستنە‌سەر پێداچوونەوەکانی ڕێکەوتێکی تایبەت یا پێش ئەوە کەڵک وەر بگریت.
لەیادت بێت کە بەکارهێنانی بەستەرەکانی ڕێن‌نیشاندەر، ستوونەکە وەک ئەوەڵ لێ‌دەکاتەوە.',
'mergehistory-go' => 'نیشان‌دانی ئەو دەستکاریانە وا توانای خستنەسەر یەکیان هەیە',
'mergehistory-submit' => 'خستنەسەریەکی پێداچوونەوەکان',
'mergehistory-empty' => 'ناتواندرێت هیچ یەک لە پێداچوونەوەکان بخرێتە ‌سەریەک.',
'mergehistory-success' => '$3 {{PLURAL:$3|پێداچوونەوەی|پێداچوونەوەی}} [[:$1]] بە سەرکەوتوویەوە خرایە سەر [[:$2]].',
'mergehistory-fail' => 'سەریەک خستنی مێژوو پێک‌نایەت، تکایە دیسان دیاریکەرەکانی لاپەڕە و کات چاو لێ بکەوە.',
'mergehistory-no-source' => 'لاپەڕەی سەرچاوەی $1 بوونی نیە.',
'mergehistory-no-destination' => 'لاپەڕەی مەبەستی $1 بوونی نیە.',
'mergehistory-invalid-source' => 'لاپەڕەی سەرچاوە دەبێ سەردێڕێکی گونجاو بێت.',
'mergehistory-invalid-destination' => 'لاپەڕەی مەبەست دەبێ سەردێڕێکی گونجاو بێت.',
'mergehistory-autocomment' => '[[:$1]] خرایە سەر [[:$2]]',
'mergehistory-comment' => '[[:$1]] خرایە سەر [[:$2]]: $3',
'mergehistory-same-destination' => 'لاپەڕەی سەرچاوە و مەبەست نابێ یەکێک بن.',
'mergehistory-reason' => 'هۆکار:',

# Merge log
'mergelog' => 'لۆگی کردنەیەک',
'pagemerge-logentry' => '[[$1]] خرایە سەر [[$2]] (پێداچوونەوەکان تا $3)',
'revertmerge' => 'لەیەک جیاکردنەوە',
'mergelogpagetext' => 'لە خوارەوە دوایین مێژووی‌لاپەڕە خستنە سەر لاپەڕەیەکی‌تر، دەبینی.',

# Diffs
'history-title' => 'مێژووی پێداچوونەوەکانی «$1»',
'difference-title' => 'جیاوازیی نێوان پێداچوونەوەکانی «$1»',
'difference-title-multipage' => 'جیاوازیی نێوان پەڕەی «$1» و «$2»',
'difference-multipage' => '(جیاوازی نێوان پەڕەکان)',
'lineno' => 'ھێڵی  $1:',
'compareselectedversions' => 'پیاچوونەوە ھەڵبژێردراوەکان ھەڵسەنگێنە',
'showhideselectedversions' => 'پیاچوونەوە ھەڵبژێردراوەکان نیشانبدە/بشارەوە',
'editundo' => 'پووچەڵکردنەوە',
'diff-multi' => '({{PLURAL:$1|پیاچوونەوەیەکی نێوانی|$1 پیاچوونەوەی نێوانی}}ی {{PLURAL:$2|بەکارھێنەرێک|$2 بەکارھێنەر}} نیشان نەدراوە)',

# Search results
'searchresults' => 'ئاکامەکانی گەڕان',
'searchresults-title' => 'ئاکامەکانی گەڕان بۆ «$1»',
'searchresulttext' => 'بۆ زانیاری زیاتر دەربارەی گەڕان {{SITENAME}} ، بڕوانە لە  [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "گەڕایت بۆ '''[[:$1]]''' ([[Special:Prefixindex/$1|ھەموو ئەو پەڕانەی بە «$1»ەوە دەستپێدەکەن]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ھەموو ئەو پەڕانەی بەستەریان ھەیە بۆ «$1»]])",
'searchsubtitleinvalid' => "گەڕایت بۆ '''$1'''",
'toomanymatches' => 'هاوتای ئەوەی داوات کرد، زۆر هەیە. تکایە داوای‌تر تاقی بکەوە.',
'titlematches' => 'سەردێڕی پەڕە پێی ئەخوا',
'notitlematches' => 'لە نێو سەردێڕەکان نەبینرا',
'textmatches' => 'هاوتاکانی دەقی لاپەڕە',
'notextmatches' => 'لە دەقی نووسراوەکان دا نەبینرا',
'prevn' => '{{PLURAL:$1|$1}}ی پێشوو',
'nextn' => '{{PLURAL:$1|$1}}ی دواتر',
'prevn-title' => '$1 {{PLURAL:$1|ئەنجامی|ئەنجامی}} پێشو',
'nextn-title' => '$1 {{PLURAL:$1|ئەنجامی|ئەنجامی}} دواتر',
'shown-title' => 'لە هەر پەڕەیەک $1 {{PLURAL:$1|ئەنجام|ئەنجام}} نیشان‌ بدە',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) ببینە',
'searchmenu-legend' => 'ھەڵبژاردەکانی گەڕان',
'searchmenu-exists' => "'''پەڕەیەک بە ناوی «[[:$1]]» لەم ویکییەدا ھەیە.'''",
'searchmenu-new' => "'''لەم ویکییەدا پەڕەی « [[:$1]] » دروست بکە!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|گەڕیان لە پەڕەکانی بەم پێشگرەوە]]',
'searchprofile-articles' => 'پەڕە بە ناوەڕۆکەکان',
'searchprofile-project' => 'پەڕەکانی یارمەتی و پڕۆژە',
'searchprofile-images' => 'ڕەنگاڵە',
'searchprofile-everything' => 'ھەموو شتێک',
'searchprofile-advanced' => 'پێشکەوتوو',
'searchprofile-articles-tooltip' => 'بگەڕێ لە $1',
'searchprofile-project-tooltip' => 'بگەڕێ لە $1',
'searchprofile-images-tooltip' => 'بگەڕێ بۆ پەڕگەکان',
'searchprofile-everything-tooltip' => 'لە ھەموو ناوەرۆکێکدا بگەڕێ (تەنانەت پەڕەکانی وتووێژیش)',
'searchprofile-advanced-tooltip' => 'گەڕان لەناو بۆشایی‌ناوە دڵخوازەکان',
'search-result-size' => '$1 ({{PLURAL:$2|یەک وشە|$2 وشە}})',
'search-result-category-size' => '{{PLURAL:$1|١ ئەندام|$1 ئەندام}} ({{PLURAL:$2|١ ژێرپۆل|$2 ژێرپۆل}}, {{PLURAL:$3|١ پەڕگە|$3 پەڕگە}})',
'search-result-score' => 'پەیوەندی: $1%',
'search-redirect' => '(ڕەوانەکەر $1)',
'search-section' => '(بەشی $1)',
'search-suggest' => 'ئایا مەبەستت ئەمە بوو: $1',
'search-interwiki-caption' => 'پرۆژە خوشکەکان',
'search-interwiki-default' => '$1 ئەنجام:',
'search-interwiki-more' => '(زیاتر)',
'search-relatedarticle' => 'پەیوەست',
'mwsuggest-disable' => 'پێشنیارەکانی گەڕان ناچالاک بکە',
'searcheverything-enable' => 'لە ھەموو بۆشاییی ناوەکاندا بگەڕێ',
'searchrelated' => 'پەیوەست',
'searchall' => 'ھەموو',
'showingresults' => "لە خوارەوە {{PLURAL:$1|'''یەک''' ئەنجام|'''$1''' ئەنجام}} نیشان دراوە، بە دەست پێ کردن لە ژمارەی '''$2'''ەوە.",
'showingresultsnum' => "لە خوارەوە {{PLURAL:$3|'''١''' ئەنجام|'''$3''' ئەنجام}} دەبینن کە لە ئەنجامی ژمارە '''$2'''ەوە دەست{{PLURAL:$3|پێدەکات|پێدەکەن}}",
'showingresultsheader' => "{{PLURAL:$5|ئەنجامی '''$1''' لە '''$3'''|ئەنجامەکانی '''$1 - $2''' لە '''$3'''}} بۆ '''$4'''",
'nonefound' => "'''تێبینی''': لە حاڵەتی بنچینەیی تەنھا لە ھەندێک لە بۆشایی‌ناوەکان گەڕان دەکرێت.
وشەی ''all:'' بکە بە پێشگری پرسەکە بۆ گەڕان لە نێو ھەموو کەرستەکان (پەڕەکانی وتووێژ، داڕێژەکان و هتد)، یان بۆشایی‌ناوێکی دڵخواز وەک پێشگر بەکار بێنە.",
'search-nonefound' => 'ھیچ ئاکامێک کە بە داواکارییەکەت بخوا نەدۆزرایەوە.',
'powersearch' => 'پێشکەوتوو بگەڕێ',
'powersearch-legend' => 'گەڕانی پێشکەوتوو',
'powersearch-ns' => 'گەڕان لە بۆشایی‌ناوەکانی:',
'powersearch-redir' => 'ڕەوانەکراوەکان لیست بکرێن',
'powersearch-field' => 'گەڕان بۆ',
'powersearch-togglelabel' => 'پشکنینی:',
'powersearch-toggleall' => 'ھەموو',
'powersearch-togglenone' => 'ھیچیان',
'search-external' => 'گەڕانی دەرەکی',
'searchdisabled' => 'گەڕانی {{SITENAME}} ئێستە کار ناکات.
دەتوانی بۆ ئێستا لە گەڕانی گووگڵ کەڵک وەرگری.
لەیادت بێت لەوانەیە پێرستەکانیان بۆ گەڕانی ناو {{SITENAME}}، کات‌بەسەرچوو بێت.',

# Preferences page
'preferences' => 'ھەڵبژاردەکان',
'mypreferences' => 'ھەڵبژاردەکان',
'prefs-edits' => 'ژمارەی گۆڕانکارییەکان:',
'prefsnologin' => 'لەژوورەوە نیت',
'prefsnologintext' => 'بۆ دانانی هەڵبژاردەکانی بەکارهێنەر دەبێ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} بچیتە ژوورەوە]</span>.',
'changepassword' => 'تێپەڕوشە بگۆڕە',
'prefs-skin' => 'پێستە',
'skin-preview' => 'پێش بینین',
'datedefault' => 'ھەڵنەبژێردراو',
'prefs-beta' => 'کەرەسەکانی بیتا',
'prefs-datetime' => 'کات و ڕێکەوت',
'prefs-labs' => 'کەرەسەکانی تاقیگەکان',
'prefs-user-pages' => 'پەڕە بەکارھێنەرییەکان',
'prefs-personal' => 'پرۆفایلی بەکارھێنەر',
'prefs-rc' => 'دوایین گۆڕانکارییەکان',
'prefs-watchlist' => 'لیستی چاودێری',
'prefs-watchlist-days' => 'ژمارەی ڕۆژەکان بۆ نیشاندان لە لیستی چاودێری:',
'prefs-watchlist-days-max' => 'ئەوپەڕی $1 {{PLURAL:$1|ڕۆژە|ڕۆژە}}',
'prefs-watchlist-edits' => 'ئەوپەڕی ژمارەی گۆڕانکارییەکان بۆ نیشاندان لە لیستی چاودێریی پەرەپێدراو:',
'prefs-watchlist-edits-max' => 'ئەوپەڕی ژمارە: ١٠٠٠',
'prefs-watchlist-token' => 'ڕەمزی لیستی چاودێری:',
'prefs-misc' => 'جۆراوجۆر',
'prefs-resetpass' => 'تێپەڕوشە بگۆڕە',
'prefs-changeemail' => 'ئەدرەسی ئیمەیل بگۆڕە',
'prefs-setemail' => 'ناونیشانێکی ئیمەیل دیاری بکە',
'prefs-email' => 'ھەڵبژاردەکانی ئیمەیل',
'prefs-rendering' => 'ڕواڵەت',
'saveprefs' => 'پاشەکەوت',
'resetprefs' => 'گۆڕانکارییە پاشەکەوت نەکراوەکان پاک بکەرەوە',
'restoreprefs' => 'ھەموو ڕێکخستنەکان ببەرەوە بۆ باری بنچینەیی',
'prefs-editing' => 'دەستکاریکردن',
'rows' => 'ڕیزەکان:',
'columns' => 'ستوونەکان:',
'searchresultshead' => 'گەڕان',
'resultsperpage' => 'ژمارەی ئەنجامەکان لە ھەر پەڕەیەک:',
'stub-threshold' => 'سنوور بۆ شێوازی <a href="#" class="stub">بەستەری کۆڵکە</a> (بایت):',
'stub-threshold-disabled' => 'ناچالاک',
'recentchangesdays' => 'ژمارە ڕۆژە نیشاندراوەکان لە دوایین گۆڕانکارییەکان:',
'recentchangesdays-max' => '(ئەوپەڕی $1 {{PLURAL:$1|ڕۆژە|ڕۆژە}})',
'recentchangescount' => 'ژمارەی گۆڕانکارییەکان کە نیشان ئەدرێن لە حاڵەتی دیفاڵت:',
'prefs-help-recentchangescount' => 'ئەمە دوایین گۆڕانکارییەکان، مێژووی پەڕەکان و لۆگەکانیش لەبەردەگرێت.',
'savedprefs' => 'ھەڵبژاردەکانت پاشەکەوت کران',
'timezonelegend' => 'ناوچەکات:',
'localtime' => 'کاتی ناوچەیی:',
'timezoneuseserverdefault' => 'دیفاڵتی ویکی بەکاربێنە ($1)',
'timezoneuseoffset' => 'دیکە (ناتەواویەکان دیاری بکە)',
'timezoneoffset' => 'جیاوازی¹:',
'servertime' => 'کاتی ڕاژەکار:',
'guesstimezone' => 'لە وێبگەڕەکە بیگرە',
'timezoneregion-africa' => 'ئافریقا',
'timezoneregion-america' => 'ئەمریکا',
'timezoneregion-antarctica' => 'ئانتارکتیکا',
'timezoneregion-arctic' => 'ئارکتیک',
'timezoneregion-asia' => 'ئاسیا',
'timezoneregion-atlantic' => 'ئوقیانووسی ئاتلانتیک',
'timezoneregion-australia' => 'ئۆسترالیا',
'timezoneregion-europe' => 'ئەورووپا',
'timezoneregion-indian' => 'ئوقیانووسی ھیند',
'timezoneregion-pacific' => 'ئۆقیانووسی ئارام',
'allowemail' => 'ڕێگە بدە بە بەکارھێنەرانی تر کە ئیمەیلم بۆ بنێرن',
'prefs-searchoptions' => 'گەڕان',
'prefs-namespaces' => 'بۆشایی‌ناوەکان',
'defaultns' => 'دەنا لەم بۆشاییی ناوانەدا بگەڕێ:',
'default' => 'بنچینەیی',
'prefs-files' => 'پەڕگەکان',
'prefs-custom-css' => 'CSSی دڵخواز',
'prefs-custom-js' => 'JSی دڵخواز',
'prefs-common-css-js' => 'سی‌ئێس‌ئێس/جاڤاسکریپتی ھاوبەش بۆ گشت پێستەکان:',
'prefs-reset-intro' => 'دەتوانی لەم لاپەڕە بۆ گەڕانەوەی هەڵبژاردەکانت بۆ بنچینەیی ماڵپەر کەڵک وەرگریت.
گەر ئەوە بکەی ئیتر گۆڕانەکەت ناگەڕێتەوە.',
'prefs-emailconfirm-label' => 'پشتڕاست کردنەوەی ئیمەیل:',
'youremail' => 'ئیمەیل:',
'username' => '{{GENDER:$1|ناوی به‌کارھێنەر}}:',
'uid' => 'پێناسەی {{GENDER:$1|به‌کارھێنەر}}:',
'prefs-memberingroups' => '{{GENDER:$2|ئەندامی}} {{PLURAL:$1|گرووپی|گرووپەکانی}}:',
'prefs-registration' => 'کاتی خۆتۆمارکردن:',
'yourrealname' => 'ناوی ڕاستی:',
'yourlanguage' => 'زمان',
'yourvariant' => 'شێوەزاری زمانی ناوەرۆک:',
'yournick' => 'واژووی نوێ:',
'prefs-help-signature' => 'بۆچوونەکان لە لاپەڕەکانی وتووێژدا دەبێ بە "<nowiki>~~~~</nowiki>" دیاری بکرێن، کە دواتر خۆکار دەگۆڕێ بە واژۆکەت و مۆری کاتی.',
'badsig' => 'ئیمزاكه‌ هه‌ڵه‌یه‌، ته‌ماشای كۆدی HTML بكه‌‌',
'badsiglength' => 'واژووەکەت زۆر درێژە.
واژوو نابێ لە $1 {{PLURAL:$1|نووسە}} درێژتر بێت.',
'yourgender' => 'زایەند:',
'gender-unknown' => 'ئاشکرا نەکراو',
'gender-male' => 'پیاو',
'gender-female' => 'ژن',
'prefs-help-gender' => 'دڵخواز: بۆ بانگ کردنی دروست بە دەستی نەرمامێر.
ئەم زانیارییە گشتی ئەبێ.',
'email' => 'ئیمەیل',
'prefs-help-realname' => 'ناوی ڕاستی دڵخوازە.
ئەگەر پێت خۆش بێت بیدەی، زۆرتر ڕاتدەکێشێت بۆ کارەکانت.',
'prefs-help-email' => 'دانانی ناونیشانی ئیمەیل دڵخوازانەیە، بەڵام ئەگەر تێپەڕوشەکەت لەیادکرد، بۆ نوێ‌کردنەوەی تێپەڕوشە پێویست دەبێت.',
'prefs-help-email-others' => 'ھەروەھا دەتوانی ھەڵبژێری کە بەکارھێنەرانی دیکە لە ڕێگەی پەڕەی بەکارھێنەرییەکەت یان لێدوانەکەت بێ ئاشکراکردنی کەسایەتیت پێوەندیت لەگەڵ بگرن.',
'prefs-help-email-required' => 'ناونیشانی ئیمەیل پێویستە.',
'prefs-info' => 'زانیاریی سەرەتایی',
'prefs-i18n' => 'نێونەتەویی کردن',
'prefs-signature' => 'واژوو',
'prefs-dateformat' => 'ڕازاندنەوەی ڕێکەوت',
'prefs-timeoffset' => 'قەرەبووکەری کات',
'prefs-advancedediting' => 'ھەڵبژاردە گشتییەکان',
'prefs-advancedrc' => 'ھەڵبژاردەکانی پێشکەوتوو',
'prefs-advancedrendering' => 'هەڵبژاردە پێشکەوتووەکان',
'prefs-advancedsearchoptions' => 'هەڵبژاردە پێشکەوتووەکان',
'prefs-advancedwatchlist' => 'هەڵبژاردە پێشکەوتووەکان',
'prefs-displayrc' => 'ھەڵبژاردەکانی نیشاندان',
'prefs-displaysearchoptions' => 'ھەڵبژاردەکانی نیشاندان',
'prefs-displaywatchlist' => 'ھەڵبژاردەکانی نیشاندان',
'prefs-diffs' => 'جیاوازییەکان',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'ناونیشانی ئیمەیل دروست وە بەر چاو دێت',
'email-address-validity-invalid' => 'ناونیشانێکی دروستی ئیمەیل بنووسە',

# User rights
'userrights' => 'بەڕێوەبردنی مافەکانی بەکارھێنەر',
'userrights-lookup-user' => 'بەڕێوەبردنی گرووپەکانی بەکارھێنەر',
'userrights-user-editname' => 'ناوی بەکارهێنەرێک بنووسە:',
'editusergroup' => 'گرووپەکانی بەکارھێنەر دەستکاری بکە',
'editinguser' => "گۆڕینی مافەکانی بەکارهێنەر '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'دەستکاریی گرووپەکانی بەکارهێنەر',
'saveusergroups' => 'گرووپەکانی بەکارھێنەر پاشەکەوت بکە',
'userrights-groupsmember' => 'ئەندامە لە:',
'userrights-groupsmember-auto' => 'ئەندامی ناڕاشکاوە لە:',
'userrights-groups-help' => 'دەتوانی ئەو گرووپانەی ئەم بەکار‌هێنەرە تێیدایە ئاڵوگۆڕ بکەی:
* چوارچێوەی نیشان‌کراو یانی بەکارھێنەر لەو گرووپەدا هەیە.
* چوارچێوەی نیشان‌نەکراو یانی بەکارھێنەر لەو گرووپەدا نییە.
* ئەستێرەیەک (*) نیشان دەدا ناتوانی ئەو گرووپەی جارێکی تر زیادت کردووە لای‌بەری، و بە پێچەوانە.',
'userrights-reason' => 'هۆکار:',
'userrights-no-interwiki' => 'دەسەڵاتی گۆڕینی مافەکانی بەکارهێنەر لە ویکی‌یەکانی دیکەت نیە.',
'userrights-nodatabase' => 'بنکەدراوی $1 بوونی نیە یا لەم شوێنە نیە.',
'userrights-nologin' => 'بۆ دانانی مافەکانی بەکارهێنەر دەبێ بە هەژماری بەڕێوبەری [[Special:UserLogin|بچیتە ژووروە]].',
'userrights-notallowed' => 'ھەژمارەکەی تۆ دەسەڵاتی دانان یان لابردنی مافەکانی بەکارھێنەری نییە.',
'userrights-changeable-col' => 'ئەو گرووپانەی دەتوانی بیگۆڕی',
'userrights-unchangeable-col' => 'ئەو گرووپانەی ناتوانی بیگۆڕی',

# Groups
'group' => 'گرووپ:',
'group-user' => 'بەکارهێنەران',
'group-autoconfirmed' => 'بەکارھێنەرانی پەسندکراوی خۆگەڕ',
'group-bot' => 'بۆتەکان',
'group-sysop' => 'بەڕێوبەران',
'group-bureaucrat' => 'بیوروکراتەکان',
'group-suppress' => 'چاودێرەکان',
'group-all' => '(ھەموو)',

'group-user-member' => '{{GENDER:$1|بەکارھێنەر}}',
'group-autoconfirmed-member' => '{{GENDER:$1|بەکارھێنەرانی پەسندکراوی خۆگەڕ}}',
'group-bot-member' => 'بۆت',
'group-sysop-member' => '{{GENDER:$1|بەڕێوبەر}}',
'group-bureaucrat-member' => '{{GENDER:$1|بیوروکرات}}',
'group-suppress-member' => '{{GENDER:$1|چاودێر}}',

'grouppage-user' => '{{ns:project}}:بەکارھێنەران',
'grouppage-autoconfirmed' => '{{ns:project}}:بەکارھێنەرانی پەسندکراوی خۆگەڕ',
'grouppage-bot' => '{{ns:project}}:بۆت',
'grouppage-sysop' => '{{ns:project}}:بەڕێوبەران',
'grouppage-bureaucrat' => '{{ns:project}}:بیوروکراتەکان',
'grouppage-suppress' => '{{ns:project}}:چاودێر',

# Rights
'right-read' => 'خوێندنەوەی پەڕەکان',
'right-edit' => 'دەستکاریی پەڕەکان',
'right-createpage' => 'دروستکردنی پەڕەکان (کە پەڕەی وتووێژ نین)',
'right-createtalk' => 'دروستکردنی پەڕەکانی وتووێژ',
'right-createaccount' => 'دروستکردنی ھەژماری بەکارھێنەریی نوێ',
'right-minoredit' => 'نیشانکردنی دەستکارییەکان وەک بچووک',
'right-move' => 'گواستنەوەی پەڕەکان',
'right-move-subpages' => 'گواستنەوەی پەڕەکان لەگەڵ ژێرپەڕەکانی',
'right-move-rootuserpages' => 'گواستنەوەی پەڕە بنەڕەتییەکانی بەکارھێنەر',
'right-movefile' => 'گواستنەوەی پەڕگەکان',
'right-suppressredirect' => 'دروست‌ نەکردنی ڕەوانەکەر لە پەڕەی سەرچاوەوە کاتی گواستنەوەی پەڕەکان',
'right-upload' => 'بارکردنی پەڕگەکان',
'right-reupload' => 'بارکردنەوە لەسەر ئەو پەڕگانەی وا هەن',
'right-reupload-own' => 'بارکردنەوە لەسەر ئەو پەڕگانەی وا هەن و خۆی باری کردووە',
'right-reupload-shared' => 'بارکردنی خۆماڵیی ئەو پەڕگانەی وا ھەن لەسەر خەزێنەی ھاوبەش',
'right-upload_by_url' => 'بارکردنی پەڕگەکان لە ناونیشانێکی ئینتەرنێتی',
'right-purge' => 'واڵاکردنی کەشی پێگە بۆ پەڕەیەک بەبێ پشتڕاستکردنەوە',
'right-autoconfirmed' => 'کاریگەری وەرنەگرتن لە سنوورەکانی خێراییی ئایپی',
'right-bot' => 'هەڵسوکەوت وەک پرۆسەیەکی خۆگەڕ',
'right-nominornewtalk' => 'دەستکاریی بچووکی پەڕەی وتووێژ جۆرێک نەبێتە ھۆی دروستبوونی پەیامی نوێ',
'right-apihighlimits' => 'بەکارھێنانی سنوورەکانی بەرزتر بۆ داواکارییەکانی API',
'right-writeapi' => 'بەکارھێنانی API بۆ نووسین',
'right-delete' => 'سڕینەوەی پەڕەکان',
'right-bigdelete' => 'سڕینەوەی پەڕەکان بە مێژووی گەورە',
'right-deletelogentry' => 'سڕینەوە و ھێنانەوەی بابەتەکانی لۆگێکی دیاریکراو',
'right-deleterevision' => 'سڕینەوە و هێنانەوەی پێداچوونەوەیەکی دیاریکراوی پەڕەکان',
'right-deletedhistory' => 'دیتنی بابەتە سڕاوەکانی مێژوو بەبێ دیتنی دەقە سڕاوەکەیان',
'right-deletedtext' => 'دیتنی دەقە سڕاوەکان و گۆڕانکارییەکانی نێوان پێداچوونەوە سڕاوەکان',
'right-browsearchive' => 'گەڕانی پەڕە سڕاوەکان',
'right-undelete' => 'ھێنانەوەی پەڕەیەک',
'right-suppressrevision' => 'بەسەرداچوونەوە و ھێنانەوەی پێداچوونەوە شاردراوەکان لە بەڕێوبەران',
'right-suppressionlog' => 'دیتنی لۆگە نھێنییەکان',
'right-block' => 'بەربەستنی بەکارھێنەرانی تر لە دەستکاریکردن',
'right-blockemail' => 'بەربەستنی بەکارھێنەرێک لە ناردنی ئیمەیل',
'right-hideuser' => 'بەربەستنی ناوێکی بەکارهێنەری، شاردنەوەی لەبەر چاوی ھەمووان',
'right-ipblock-exempt' => 'لادان لە بەربەستنەکانی ئایپی، بەربەستنە خۆگەڕەکان و بەربەستنەکانی زنجیرە',
'right-proxyunbannable' => 'لادان لە بەربەستنە خۆگەڕەکانی پرۆکسییەکان',
'right-unblockself' => 'کردنەوەی خۆیان',
'right-protect' => 'گۆڕینی ئاستەکانی پاراستن و دەستکاریی پەڕە پارێزراوە تاڤگەیییەکان',
'right-editprotected' => 'دەستکاریی پەڕە پارێزراوەکانی وەک «{{int:protect-level-sysop}}»',
'right-editsemiprotected' => 'دەستکاریی پەڕە پارێزراوەکانی وەک «{{int:protect-level-autoconfirmed}}»',
'right-editinterface' => 'دەستکاریی ڕووکاری بەکارھێنەر',
'right-editusercssjs' => 'دەستکاریی پەڕگەکانی جاڤاسکریپت و CSSی بەکارھێنەرانی تر',
'right-editusercss' => 'دەستکاریی پەڕگەکانی CSSی بەکارھێنەرانی تر',
'right-edituserjs' => 'دەستکاریی پەڕگەکانی جاڤاسکریپتی بەکارھێنەرانی تر',
'right-editmyusercss' => 'دەستکاریی پەڕگەکانی CSSی بەکارھێنەریی خۆی',
'right-editmyuserjs' => 'دەستکاریی پەڕگەکانی جاڤاسکریپتی بەکارھێنەریی خۆی',
'right-viewmywatchlist' => 'دیتنی پێرستی چاودێریی خۆی',
'right-editmywatchlist' => 'دەستکاریی پێرستی چاودێریی خۆی. تکایە لەبەر چاو بگرە ھەندێک کردەوە ھێشتا پەڕەکان زیاد دەکا تەنانەت بەبێ ئەم مافە.',
'right-viewmyprivateinfo' => 'دیتنی دراوە تایبەتییەکانی خۆی (وەک ناونیشانی ئیمەیل، ناوی ڕاستی)',
'right-editmyprivateinfo' => 'دەستکاریی دراوە تایبەتییەکانی خۆی (وەک ناونیشانی ئیمەیل، ناوی ڕاستی)',
'right-editmyoptions' => 'دەستکاریی ھەڵبژاردەکانی خۆی',
'right-rollback' => 'گەڕاندنەوەی خێرای دەستکاریەکانی دوایین بەکارھێنەر کە پەڕەیەکی دیاریکراوی دەستکاری کردووە',
'right-markbotedits' => 'نیشانکردنی دەستکارییە گەڕێنراوەکان وەک دەستکارییەکانی بۆت',
'right-noratelimit' => 'کاریگەری وەرنەگرتن لە سنوورەکانی خێرایی',
'right-import' => 'ھاوردنی پەڕەکان لە ویکییەکانی تر',
'right-importupload' => 'ھاوردنی پەڕەکان بە بارکردنی پەڕگە',
'right-patrol' => 'نیشانکردنی دەستکاریەکانی کەسانی تر وەک پاس دراو',
'right-autopatrol' => 'نیشانکردنی خۆگەڕی دەستکارییەکانی خۆی وەک پاس دراو',
'right-patrolmarks' => 'دیتنی نیشانەکانی پاسدان لە دوایین گۆڕانکارییەکاندا',
'right-unwatchedpages' => 'دیتنی پێرستێک لە پەڕە چاودێری نەکراوەکان',
'right-mergehistory' => 'میژووی پەڕەکان بکە یەک',
'right-userrights' => 'دەستکاری مافەکانی هەموو بەکارهێنەران',
'right-userrights-interwiki' => 'دەستکاری مافەکانی بەکارهێنەریی بەکارهێنەران لە ویکی‌یەکانی دیکە‌دا',
'right-siteadmin' => 'داخستن و کردنەوەی بنکەدراو',
'right-override-export-depth' => 'هەناردنی لاپەڕەکان کە لاپەڕەکانی بەستەر پێ‌دراو تا قووڵایی 5 لەخۆ بگرێت',
'right-sendemail' => 'ناردنی ئیمەیل بۆ بەکارھێنەرانی تر',

# Special:Log/newusers
'newuserlogpage' => 'لۆگی دروستکردنی بەکارھێنەر',
'newuserlogpagetext' => 'ئەمە لۆگێکی دروستکردنی بەکارھێنەرە.',

# User rights log
'rightslog' => 'لۆگی مافەکانی بەکارھێنەر',
'rightslogtext' => 'ئەمە لۆگی دەستکاری مافەکانی بەکار‌هێنەرە.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'خوێندنەوەی ئەم پەڕە',
'action-edit' => 'دەستکاریی ئەم پەڕەیە',
'action-createpage' => 'درووست‌کردنی لاپەڕە',
'action-createtalk' => 'درووست‌کردنی لەپەڕەکانی وتووێژ',
'action-createaccount' => 'درووست‌کردنی هەژمارەی ئەم بەکارهێنەرە',
'action-minoredit' => 'نیشان‌کردنی ئەم دەستکاریە وەک بچووک',
'action-move' => 'گواستنەوەی ئەم پەڕە',
'action-move-subpages' => 'گواستنەوەی ئەم پەڕەیە و ژێرپەڕەکانی',
'action-move-rootuserpages' => 'گواستنەوەی بنەرەتی لاپەڕەکانی بەکارهێنەر',
'action-movefile' => 'ئەم پەڕگەیە بگوازەوە',
'action-upload' => 'ئەم پەڕەیە بار بکە',
'action-reupload' => 'سەرنووسینی ئەم پەڕگە وا هەیە',
'action-reupload-shared' => 'بەتاڵ‌کردنی ئەم پەڕگە لە‌سەر شوێنێکی هاوبەش',
'action-upload_by_url' => 'ئەم پەرگەیە لە ناونیشانێکی ئینتەرنێتی بار بکە',
'action-writeapi' => 'کەڵک وەر گرتن لە نووسینی API',
'action-delete' => 'ئەم پەڕەیە بسڕەوە',
'action-deleterevision' => 'سڕینی ئەم پێداچوونەوە',
'action-deletedhistory' => 'دیتنی مێژووی سڕاوەی ئەم لاپەڕە',
'action-browsearchive' => 'گەران لە نێو لاپەڕە سڕاوەکان',
'action-undelete' => 'گەڕانەوەی ئەم لاپەڕە',
'action-suppressrevision' => 'چاوپێداخشان و هاردنوەی ئەم لاپەڕە شاراوە',
'action-suppressionlog' => 'دیتنی ئەم لۆگە ئەهلیە',
'action-block' => 'بەربەست کردنی ئەم بەکارهێنەرە بۆ دەستکاری‌کردن',
'action-protect' => 'گۆڕانی ئاستی پارێزراوی بۆ ئەم لاپەڕە',
'action-rollback' => 'گەڕاندنەوەی خێرای دەستکاریەکانی دوایین بەکارھێنەر کە پەڕەیەکی دیاریکراوی دەستکاری کردووە',
'action-import' => 'هێنانەناوەی ئەم لاپەڕە لە ویکی‌یەکی دیکە',
'action-importupload' => 'هێنانەناوەی ئەم لاپەڕە لە پەڕگەیەکی بارکراو',
'action-patrol' => 'نیشانکردنی دەستکاریەکانی کەسانی تر وەک پاس دراو',
'action-autopatrol' => 'دەستکارییەکانت وەک پاس دراو نیشان بکرێ',
'action-unwatchedpages' => 'دیتنی پێرستێک لە پەڕە چاودێری نەکراوەکان',
'action-mergehistory' => 'میژووی پەڕەکان بکە یەک',
'action-userrights' => 'دەستکاریی مافەکانی ھەموو بەکارھێنەران',
'action-userrights-interwiki' => 'دەستکاری مافەکانی بەکارهێنەریی بەکارهێنەران لە ویکی‌یەکانی دیکە‌دا',
'action-siteadmin' => 'داخستن یا کردنەوەی بنکەدراو',
'action-sendemail' => 'ناردنی ئیمەیلەکان',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|گۆڕانکاری}}',
'recentchanges' => 'دوایین گۆڕانکارییەکان',
'recentchanges-legend' => 'ھەڵبژاردەکانی دوایین گۆڕانکارییەکان',
'recentchanges-summary' => 'لەم پەڕەیەدا شوێنی دوایین گۆڕانکارییەکانی ویکی بکەوە.',
'recentchanges-feed-description' => 'دوای دوایین گۆڕانکارییەکانی ئەم ویکیە بکەوە لەم «فید»ەوە.',
'recentchanges-label-newpage' => 'ئەم دەستکارییە لاپەڕەیەکی نوێی دروستکرد',
'recentchanges-label-minor' => 'ئەمە دەستکارییەکی بچووکە',
'recentchanges-label-bot' => 'ئەم دەستکاریە لە لایەن بۆتەوە پێک هاتووە',
'recentchanges-label-unpatrolled' => 'ئەم دەستکارییە ھێشتا پاس نەدراوە',
'rcnote' => "لە خوارەوەدا {{PLURAL:$1|'''۱''' گۆڕانکاری |دوایین '''$1''' گۆڕانکارییەکان}} لە دوایین {{PLURAL:$2|ڕۆژ|'''$2''' ڕۆژەوە}} ، تا $5، $4 دەبینن.",
'rcnotefrom' => "ئەوی‌ خوارەوە گۆڕانکارییەکانە لە '''$2'''ەوە (ھەتا '''$1''' نیشاندراو).",
'rclistfrom' => 'گۆڕانکارییە نوێکان نیشان بدە بە دەستپێکردن لە $1',
'rcshowhideminor' => 'دەستکارییە بچووکەکان $1',
'rcshowhidebots' => 'بۆتەکان $1',
'rcshowhideliu' => 'بەکارھێنەرە تۆمارکراوەکان $1',
'rcshowhideanons' => 'بەکارھێنەرە نەناسراوەکان $1',
'rcshowhidepatr' => 'گۆرانکارییە پاس دراوەکان $1',
'rcshowhidemine' => 'دەستکارییەکانم $1',
'rclinks' => 'دوایین $1 گۆڕانکاریی $2 ڕۆژی ڕابردوو نیشان بدە<br />$3',
'diff' => 'جیاوازی',
'hist' => 'مێژوو',
'hide' => 'بشارەوە',
'show' => 'نیشان بدە',
'minoreditletter' => 'ب',
'newpageletter' => 'ن',
'boteditletter' => '.بۆت',
'number_of_watching_users_pageview' => '[$1 چاودێر لەسەر {{PLURAL:$1|بەکارھێنەر}}]',
'rc_categories' => 'بەرتەسک‌کردنەوە بە هاوپۆلەکان (به «|» جیای بکەوە‌)',
'rc_categories_any' => 'هەرکام',
'rc-change-size-new' => '$1 {{PLURAL:$1|بایت}} پاش گۆڕانکاری',
'newsectionsummary' => '/* $1 */ بەشی نوێ',
'rc-enhanced-expand' => 'وردەکارییەکان پیشان بدە (پێویستی بە جاڤاسکریپتە)',
'rc-enhanced-hide' => 'وردەکارییەکان بشارەوە',
'rc-old-title' => 'بە ناوی سەرەکیی «$1» دروست کراوە',

# Recent changes linked
'recentchangeslinked' => 'گۆڕانکارییە پەیوەندیدارەکان',
'recentchangeslinked-feed' => 'گۆڕانکارییە پەیوەندیدارەکان',
'recentchangeslinked-toolbox' => 'گۆڕانکارییە پەیوەندیدارەکان',
'recentchangeslinked-title' => 'گۆڕانکارییە پەیوەندیدارەکان بە "$1" ـەوە',
'recentchangeslinked-summary' => "ئەمە لیستێکی گۆڕانکارییەکانی ئەم دوایییانەی ئەو پەڕانەیە کە بەستەریان ھەیە لە پەڕەیەکی دیاریکراو (یان بۆ ئەندامەکانی پۆلێکی دیاریکراو)
پەڕەکانی [[Special:Watchlist|لیستی چاودێرییەکەت]] '''ئەستوورن'''.",
'recentchangeslinked-page' => 'ناوی پەڕە:',
'recentchangeslinked-to' => 'بەجێگەی ئەوە گۆڕانکارییەکانی ئەو پەڕانە نیشانبدە کە بەستەریان ھەیە بۆ پەڕەی دیاریکراو',

# Upload
'upload' => 'پەڕگەیەک بار بکە',
'uploadbtn' => 'پەڕگە بار بکە',
'reuploaddesc' => 'هەڵوەشانەوەی بارکردن و گەڕانەوە بۆ فۆرمی بارکردن',
'upload-tryagain' => 'پێناسەی گۆڕدراوی پەڕگە بنێرە',
'uploadnologin' => 'لەژوورەوە نیت',
'uploadnologintext' => 'بۆ بارکردنی پەڕگەکان دەبێ $1.',
'upload_directory_missing' => 'لقی بارکردن ($1) ون بووە و ڕاژەکاری‌وێب بۆی درووست ناکرێت.',
'upload_directory_read_only' => 'ڕاژەکاری‌وێب دەسەڵاتی نووسینی سەر لقی بارکردنی ($1) نیە.',
'uploaderror' => 'ھەڵە لە بارکردن دا',
'upload-recreate-warning' => "'''ھۆشیار بە: پەرگەیەک بەو ناوەوە سڕاوەتەوە یان گوێزاوەتەوە.'''

لۆگی سڕینەوە یان گواستنەوەی ئەم پەڕە لێرە لەبەردەستدایە:",
'uploadtext' => "فۆرمی خوارەوە بەکاربێنە بۆ بارکردنی پەڕگەکان.
بۆ بینینی و گەڕان لەو پەڕگانەی پێشتر بار کراون، بڕۆ بۆ [[Special:FileList|لیستی پەڕگە بارکراوەکان]]، ھەروەھا [[Special:Log/upload|ڕەشنووسی بارکردنەکان]] و [[Special:Log/delete|ڕەشنووسی سڕینەوەکان]].

بۆ بەکارھێنانی پەڕگەیەک لە پەڕەیەکدا، بەستەرێک بە یەکێک لەم شێوازانەی خوارەوە بە کار بێنە:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' بۆ بەکارهێنانی وەشانی تەواوی پەڕگە
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|دەقی جێگر]]</nowiki></code>''' بۆ بەکارهێنانی نمایشێکی بە پانتایی ٢٠٠ پیکسەڵ لە چوارچێوەیەک لە لای چەپەوە بە «دەقی جێگر» وەک شرۆڤە
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' بۆ بەستەرپێدان بە پەڕگەکە بێ نیشاندانی خودی پەڕگەکە",
'upload-permitted' => 'جۆرە پەڕگە ڕێگەپێدراوەکان: $1.',
'upload-preferred' => 'جۆرە پەڕگانەی بە باشتر دەزانرێن: $1.',
'upload-prohibited' => 'جۆرە پەڕگانەی قەدەغە کراون: $1.',
'uploadlog' => 'لۆگی بارکردن',
'uploadlogpage' => 'لۆگی بارکردن',
'uploadlogpagetext' => 'لەخوارەوە لیستی دوایین بارکراوەکان دەبینی.
بۆ ئەوەی چاوێکیان لێ بکەی، [[Special:NewFiles|گالەری پەڕگە نوێکان]] ببینە.',
'filename' => 'ناوی پەڕگە',
'filedesc' => 'کورتە',
'fileuploadsummary' => 'کورتە:',
'filereuploadsummary' => 'گۆرانکارییەکانی پەڕگە:',
'filestatus' => 'بارودۆخی مافی لەبەرگرتنەوە:',
'filesource' => 'سەرچاوە:',
'uploadedfiles' => 'پەڕگە بارکراوەکان',
'ignorewarning' => 'چاوپۆشان لە ئاگادارییەکان و پاشەکەوت کردن بە هەر شێوەیەک',
'ignorewarnings' => 'گوێ مەدە بە ئاگادارییەکان',
'minlength1' => 'ناوی پەڕگەکان دەبێ لانیکەم یەک پیت ببێت.',
'illegalfilename' => 'ناوی‌پەڕگەی "$1" پیتێکی تێدایە کە ڕێگەنەدراوە بۆ سەردێڕی لاپەڕە بەکاربێت.
تکایە ناوی پەڕگەکە بگۆڕە و دیسان باری بکەوە.',
'filename-toolong' => 'ناوی پەڕگە ناتوانێ لە ٢٤٠ بایت درێژتر بێت.',
'badfilename' => 'ناوی پەڕگە بە "$1" گۆڕا .',
'filetype-badmime' => 'ڕێگە نەدراوە پەڕگەی "$1" جۆری MIME بار بکرێت.',
'filetype-bad-ie-mime' => 'ناتوانین ئەم پەڕگە باربکەین لەبەر ئەوەی وێبگەڕی Internet Explore ئەوە وەک "$1" دەناسێت کە ڕێگەنەدراوەیە و جۆرە پەڕگەیەکی مەترسی‌دارە.',
'filetype-unwanted-type' => "'''\".\$1\"''' جۆرە پەڕگەی نەخوازراوە.
\$2، ئەو جۆرە {{PLURAL:\$3|پەڕگەیە|پەڕگانەیە}} وا بە باش‌ دازاندرێت.",
'filetype-banned-type' => "'''«.$1»''' {{PLURAL:$4|جۆرە پەڕگەی ڕێگە پێ‌نەدراوە‌|جۆرە پەڕگە ڕێگە پێ‌نەدراوە‌کانن}}.
$2، ئەو جۆرە {{PLURAL:$3|پەڕگەیە کە ڕێگەی|پەڕگانەیە کە ڕێگەیان}} پێ‌دراوە.",
'filetype-missing' => 'پەڕگەکە پاشگری نییە (وەک ".jpg").',
'empty-file' => 'ئەو پەڕگەیە کە ناردووتە ڤاڵا بوو.',
'file-too-large' => 'ئەو پەڕگەیە ناردووتە زۆر گەورەیە.',
'filename-tooshort' => 'ناوی پەڕگە زۆر کورتە.',
'filetype-banned' => 'ئەم جۆرە پەڕگەیە قەدەغەیە.',
'illegal-filename' => 'ناوی پەڕگە رێگەپێ‌نەدراوە.',
'unknown-error' => 'ھەڵەیەکی نەزانراو ڕوویداوە.',
'large-file' => 'پێشنیار دەکرێت قەبارەی پەڕگەکان زیاتر لە $1 نەبێت؛
قەبارەی ئەم پەڕگە $2.',
'largefileserver' => 'ئەم پەڕگە گەورەتر لەوەیە کە ڕاژەکار ڕێگەدەدات.',
'emptyfile' => 'ئەو پەڕگەیەی بارت کردووە لەوە دەچێ واڵا بێت.
لەوانەیە بە ھۆی هەڵەیەک لە تایپی ناوی پەڕگەکە بێت.
تکایە تاوتوێی بکە ئەگەر بە ڕاستی دەتەوێ ئەم پەڕگەیە بار بکەی.',
'fileexists' => 'پەڕگەیەک هەر بەو ناوە‌ لە پێش‌دا هەیە، تکایە گەر ئەرخەیان نیت بۆ گۆڕینی، چاوێک لە <strong>[[:$1]]</strong> بکە.
[[$1|thumb]]',
'filepageexists' => 'پەڕەی ناساندن بۆ ئەم پەڕگە پێشتر لە <strong>[[:$1]]</strong> درووستکراوە، بەڵام پەڕگەیەک بەو ناوەوە ئێستا نادۆزرێتەوە.
ئەو پوختەی کە نووسیوتە لە پەڕەی ناساندن بەرچاو ناکەوێت.
گەر دەتەوێ پوختەکەت بەرچاو کەوێت دەبێ خۆت دەستی دەستکاری بکەی.
[[$1|thumb]]',
'fileexists-extension' => 'پەڕگەیەک هەر بەو ناوە هەیە: [[$2|thumb]]
* ناوی ئەو پەڕگەی باری ئەکەی:<strong>[[:$1]]</strong>
* ناوی ئەو پەڕگەی ئێستا هەیە:<strong>[[:$2]]</strong>
تکایە ناوێکی دیکە هەڵبژێرە.',
'fileexists-thumbnail-yes' => "لەوە دەچێ ئەم پەڕگە وێنەیەکی بچووک‌کراوە بێت ''(هێما)''. [[$1|thumb]]
تکایە چاو لە پەڕگەی <strong>[[:$1]]</strong> بکه.‌
گەر ئەوەی چاوت لێ‌کرد قەبارەی ڕەسەنی هەر ئەو وێنەیە، پێویست ناکات دیسان هێماکەی باربکەی.",
'file-thumbnail-no' => "دەستپێکی ناوی ئەم پەڕگە ئەوەیە: <strong>$1</strong>
لەوە دەچێ ئەم پەڕگە وێنەیەکی بچووک‌کراوە بێت ''(هێما)''.
گەر ئەو وێنەت لە قەبارەی ڕەسەنی‌ خۆی‌دا هەیه،‌ تکایە ئەوە بار بکه،‌ دەنا ناوی پەڕگەکە بگۆڕە.",
'fileexists-forbidden' => 'پەڕگەیەک بەو ناوە لە پێش‌دا هەیە و سەرنووسین ناکرێت.
گەر هێشتا دەتەوێ پەڕگەکەت باربکەی، تکایە بگەڕێ دواوە و ناوێکی نوێ بەکاربهێنە.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'پەڕگەیەک بەو ناوە لە پێش‌دا لە شوێنی پەڕگە هاوبەشەکان هەیه.
گەر هێشتا دەتەوێ پەڕگەکەت باربکەی، تکایە بگەڕێ دواوە و ناوێکی نوێ بەکاربهێنە.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'ئەم پەڕگەیە لێ‌گرتنەوەی ئەم {{PLURAL:$1|پەڕگەیە|پەڕگانەیە}}:',
'file-deleted-duplicate' => 'ئەم پەڕگەیە ڕێک وەک ئەم پەڕگە ([[:$1]]) دەچێت کە لەم دواییانەدا سڕاوەتەوە.
پێش دەست‌پێ‌کردنی دیسان بارکردنەوەی، تکایە چاو لە مێژووی سڕینەوەی ئەو پەڕگە بکە.',
'uploadwarning' => 'ئاگادارییەکانی بارکردن',
'savefile' => 'پەڕگە پاشەکەوت بکە',
'uploadedimage' => '«[[$1]]»ی بارکرد',
'overwroteimage' => 'وەشانێکی نوێی «[[$1]]» بار کرد',
'uploaddisabled' => 'بارکردن قەدەخە کراوە',
'uploaddisabledtext' => 'بارکردنی پەڕگەکان لە کار خستراوە.',
'php-uploaddisabledtext' => 'بارکردنی پەڕگەکان لە PHPدا لە کار خستراوە.
تکایە چاو لە هەڵبژاردەکانی بارکردنی_پەڕگەکان بکە.',
'uploadscripted' => 'ئەم پەڕگە HTML یان کۆدی سکریپتی لەخۆگرتووە کە لەوانەیە ببێتە هۆی هەڵە تێگەیشتنی هێندێ وێبگەڕەکان.',
'uploadvirus' => 'ئەم پەڕگە ڤایرۆسی هەیە! وردەکاری: $1',
'upload-source' => 'پەڕگەی سەرچاوە',
'sourcefilename' => 'ناوی پەڕگەی سەرچاوە:',
'sourceurl' => 'URLی سەرچاوە:',
'destfilename' => 'ناوی مەبەست:',
'upload-maxfilesize' => 'ئەوپەڕی قەبارەی پەڕگە: $1',
'upload-description' => 'پێناسەی پەڕگە',
'upload-options' => 'ھەڵبژاردەکانی  بارکردن',
'watchthisupload' => 'ئەم پەڕگەیە بخە ژێر چاودێری',
'filewasdeleted' => 'پەڕگەیەک بەم ناوە لەم دواییانەدا بارکرا و بە خێرایی سڕایەوە.
باشتر وایە پێش هەوڵی دووبارە بۆ بارکردن سەرنجی $1 بدەی.',
'filename-bad-prefix' => "دەستپێکی ناوی ئەو پەڕگەی باری دەکەی '''\"\$1\"'''، کە ناوێکی ناسێنەر نیە؛ ئەو جۆرە ناوە زۆربەی کات کامێرا دیجیتاڵەکان خۆکار بەکاری‌دەبەن.
تکایە ناوێک هەڵبژێرە کە زانیاریی زیاتر بدات سەبارەت بە پەڕگەکەت.",
'upload-success-subj' => 'بارکردنی سەرکەوتوو',
'upload-success-msg' => 'بارکردنی [$2] سەرکەوتووانە جێبەجێکرا. لێرە لەبەردەستدایە: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'کێشەی بارکردن',
'upload-failure-msg' => 'کێشەیەک لەگەڵ بارکردنی [$2]دا ھەبوو:

$1',
'upload-warning-subj' => 'ئاگاداریی بارکردن',

'upload-proto-error' => 'پرۆتۆکۆڵی هەڵە',
'upload-proto-error-text' => 'بۆ بارکردن لە تۆڕ، URL دەبێ بە <code>http://</code>  یان <code>ftp://</code> دەست‌پێ‌بکات.',
'upload-file-error' => 'ھەڵەی ناوخۆیی',
'upload-file-error-text' => 'کێشەیەکی ناوخۆ ڕووی‌دا وەختێ هەوڵی درووست‌کردنی پەڕگەی کاتی ئەدرا لە سەر ڕاژەکار.
تکایە پەیوەندی بکە بە [[Special:ListUsers/sysop|بەڕێوبەر]].',
'upload-misc-error' => 'هەڵەیەکی نەناسراوی بارکردن',
'upload-misc-error-text' => 'هەڵەیەکی نەناسراو لە کاتی بارکردن ڕووی‌دا.
تکایە لە درووست‌بوون و دەست‌پێ گەیشتنی URL ئەرخەیان ببە و دیسان تاقی‌بکەوە.
گەر کێشەکە هەر بەردەوام بوو پەیوەندی بکە بە [[Special:ListUsers/sysop|بەڕێوبەر]].',
'upload-too-many-redirects' => 'URL ڕەوانەکەری زۆری لەخۆ گرتووە',
'upload-unknown-size' => 'قەبارەی نادیار',
'upload-http-error' => 'هەڵەیەکی HTTP ڕووئ داوە: $1',

# File backend
'backend-fail-stream' => 'نەکرا پەڕگەی $1 بنێردرێت.',
'backend-fail-notexists' => 'پەڕگەی $1 بوونی نییە.',
'backend-fail-delete' => 'نەکرا پەڕگەی $1 بسڕدرێتەوە.',
'backend-fail-alreadyexists' => 'پەڕگەی «$1» ھەر ئێستا ھەیە.',
'backend-fail-copy' => 'نەکرا پەڕگەی $1 کۆپی بکرێت بۆ $2.',
'backend-fail-move' => 'نەکرا پەڕگەی $1 بگوازرێتەوە بۆ $2.',
'backend-fail-read' => 'نەکرا پەڕگەی $1 بخوێنرێتەوە.',
'backend-fail-create' => 'نەکرا پەڕگەی $1 بنووسرێت',

# Special:UploadStash
'uploadstash' => 'ئەمباری بارکردن',
'uploadstash-errclear' => 'سڕینەوەی پەڕگەکان سەرکەوتوو نەبوو.',
'uploadstash-refresh' => 'نوێکردنەوەی پێرستی پەڕگەکان',

# img_auth script messages
'img-auth-accessdenied' => 'تێپه‌ربوون ره‌تکرایه‌وه‌',
'img-auth-nofile' => 'فایلی "$1" بوونی نیه‌',
'img-auth-isdir' => 'هه‌وڵ ده‌ده‌ی بۆ کردنه‌وه‌ی بوخچه‌ی "$1" له‌ کاتێکدا ته‌نیا کردنه‌وه‌ی فایل رێپێدراوه‌',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'توانای دەست‌پێ‌گەیشتنی URL نیە',
'upload-curl-error6-text' => 'ئەو URL کە ڕاچاوت کردووە توانای دەست‌پێ‌گەیشتنی نییە.
تکایە دیسان سەرنجی بدەوە کە URL درووست‌ نووسراوە و ماڵپەڕەکە بەردەوام کار دەکات.',
'upload-curl-error28' => 'کات‌بەسەرچوونی بارکردن',
'upload-curl-error28-text' => 'ئەو ماڵپەرە کاتی زۆری خایاند بۆ وەڵام دانەوە.
تکایە ئەرخەیان بە کە ماڵپەڕ بەردەوامە لە کارکردن، نەختێک ڕاوەستە و دیسان تاقی کەوە.
لەوانەیە لە کاتێ کە کەمتر سەرقاڵ بێت تاقی بکەیتەوە باشتر بێت.',

'license' => 'مۆڵەتنامە:',
'license-header' => 'مۆڵەتنامە',
'nolicense' => 'ھیچ ھەڵنەبژێردراوە',
'license-nopreview' => '(پێشبینین ئامادەی کەڵک وەرگرتن نییە)',
'upload_source_url' => ' (URLـی بەکار، بۆ دەست‌پێگەیشتنی  گشتی)',
'upload_source_file' => ' (پەڕگەیەک لەسەر کۆمپیوتەرەکەت)',

# Special:ListFiles
'listfiles-summary' => 'ئەم پەڕە تایبەتە هەموو پەڕگە بارکراوەکانت پێ نیشان دەدات.
لە کاتی پاڵاوتن بۆ بەکارھێنەرێکی تایبەت، تەنیا ئەو پەڕگانە کە بەکارھێنەرەکە دوایین وەشانیانی بارکردبێت نیشان دەدرێن.',
'listfiles_search_for' => 'بگەڕێ بۆ ناوی میدیای:',
'imgfile' => 'پەڕگە',
'listfiles' => 'پێرستی پەڕگەکان',
'listfiles_thumb' => 'وێنۆک',
'listfiles_date' => 'ڕێکەوت',
'listfiles_name' => 'ناو',
'listfiles_user' => 'بەکارھێنەر',
'listfiles_size' => 'قەبارە',
'listfiles_description' => 'وەسف',
'listfiles_count' => 'وەشانەکان',

# File description page
'file-anchor-link' => 'پەڕگە',
'filehist' => 'مێژووی پەڕگە',
'filehist-help' => 'کرتە بکە لەسەر یەکێک لە ڕێکەوت/کاتەکان بۆ بینینی پەڕگەکە بەو شێوەی لەو کاتەدا بووە.',
'filehist-deleteall' => 'هەمووی بسڕەوە',
'filehist-deleteone' => 'سڕینەوە',
'filehist-revert' => 'پێچەوانەکردنەوە',
'filehist-current' => 'هەنووکە',
'filehist-datetime' => 'ڕێکەوت/کات',
'filehist-thumb' => 'ھێما',
'filehist-thumbtext' => 'ھێما بۆ وەشانی  $1',
'filehist-nothumb' => 'هێما نییه',
'filehist-user' => 'بەکارھێنەر',
'filehist-dimensions' => 'ئەندازە',
'filehist-filesize' => 'قەبارەی پەڕگە',
'filehist-comment' => 'تێبینی',
'filehist-missing' => 'ون‌بوونی پەڕه',
'imagelinks' => 'بەکارھێنانی پەڕگە',
'linkstoimage' => 'لەم {{PLURAL:$1|پەڕەی خوارەوە بەستەر دراوە|$1 پەڕەی خوارەوە بەستەر دراوە}} بۆ ئەم پەڕگە:',
'linkstoimage-more' => 'زیاتر لە $1 {{PLURAL:$1|بەستەری لاپەڕە|بەستەری لاپەڕە}} بۆ ئەم پەڕگه.
ئەم لیستە {{PLURAL:$1|یەکەم لاپەڕەی بەستەرە|یەکەم لاپەڕە $1 بەستەرە}} بۆ تەنها یەم پەڕگە.
هەروا [[Special:WhatLinksHere/$2|لیستی تەواو]] ئامادەی کەڵک وەرگرتنە.',
'nolinkstoimage' => '‌لاپەڕەیەک نەدۆزرایەوە کە بەستەری هەبێ بۆ ئەم پەڕگە.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|بەستەری زیاتر]] ببینە بۆ ئەم پەڕگە.',
'linkstoimage-redirect' => '$1 (ڕەوانەکەری پەڕگە) $2',
'duplicatesoffile' => 'ئەم {{PLURAL:$1|پەڕگە دووبارەکرنەوەیەکی|پەڕگانە دووبارەکردنەوەی}} ئەم پەڕگەن ([[Special:FileDuplicateSearch/$2|وردەکاری زیاتر]]):',
'sharedupload' => 'ئەم پەڕگە لە $1ەوەیە و لەوە دەچێ لە پرۆژەکانی دیکەش بەکار ببرێت.',
'sharedupload-desc-there' => 'ئەم پەڕگە لە $1ەوەیە و لەوە دەچێ لە پرۆژەکانی دیکەش بەکار ببرێت.
تکایە بۆ زانیاریی زیاتر چاو بکە لە [$2 لاپەڕەی ناساندنی پەڕگە].',
'sharedupload-desc-here' => 'ئەم پەڕگە لە $1ەوەیە و لەوانەیە لە پرۆژەکانی دیکەش بەکار ھاتبێت.
پێناسەکەی لەسەر [$2 پەڕەی وەسفی پەڕگەکە] لە خوارەوە نیشان دراوە.',
'filepage-nofile' => 'پەڕگەیەک بەم ناوە نیە.',
'filepage-nofile-link' => 'پەڕگەیەک بەم ناوە نیە بەڵام دەتوانی [$1 باری بکەی].',
'uploadnewversion-linktext' => 'وەشانێکی نوێی ئەم پەڕگەیە بار بکە',
'shared-repo-from' => 'لە لایەن $1',
'shared-repo' => 'شوێنێکی هاوبەشی',
'upload-disallowed-here' => 'ناتوانی وەشانێکی نوێی ئەم پەڕگەیە بار بکەی.',

# File reversion
'filerevert' => 'پێچەوانەکردنەوەی $1',
'filerevert-legend' => 'پێچەوانەکردنەوەی پەڕگە',
'filerevert-intro' => "خەریکی پەڕگەی '''[[Media:$1|$1]]''' دەگەڕینیتەوە بۆ [$4 وەشانی $3، $2].",
'filerevert-comment' => 'هۆکار:',
'filerevert-defaultcomment' => 'گەڕێندراوە بۆ وەشانی $2، $1',
'filerevert-submit' => 'گەڕاندنەوە',
'filerevert-success' => "'''[[Media:$1|$1]]''' گەڕێندراوەتەوە بۆ [$4 وەشانی $3، $2].",
'filerevert-badversion' => 'وەشانێکی پێشووی ئەم  پەڕگە بەو کاتە ڕاچاوکراوه ‌نەدۆزرایەوە.',

# File deletion
'filedelete' => 'سڕینەوەی $1',
'filedelete-legend' => 'سڕینەوەی پەڕگە',
'filedelete-intro' => "خەریکی پەڕگەی '''[[Media:$1|$1]]''' دەگەڵ هەموو مێژووی دەسڕیتەوە.",
'filedelete-intro-old' => "خەریکی وەشانی [$4 $3، $2] لە '''[[Media:$1|$1]]''' دەسڕیتەوە.",
'filedelete-comment' => 'هۆکار:',
'filedelete-submit' => 'بسڕەوە',
'filedelete-success' => "'''$1''' سڕاوەتەوە.",
'filedelete-success-old' => "وەشانی $3، $2 لە '''[[Media:$1|$1]]''' سڕاوەتەوە.",
'filedelete-nofile' => "'''$1''' بوونی نییە.",
'filedelete-nofile-old' => "وەشانێکی ئەرشیڤ‌کراوی '''$1''' بەو تایبەتمەندییە دیاری‌کراوانە نییە.",
'filedelete-otherreason' => 'ھۆکاری تر/زیاتر:',
'filedelete-reason-otherlist' => 'ھۆکاری تر',
'filedelete-reason-dropdown' => '*هوکارە هاوبەشەکانی سڕینەوە
**لادان لە مافەکانی بڵاوکردنەوە
***پەڕگەی دووبارەکراوە',
'filedelete-edit-reasonlist' => 'دەستکاری هۆکارەکانی سڕینەوە',
'filedelete-maintenance-title' => 'ناتوانیت پەڕگە بسڕیتەوە',

# MIME search
'mimesearch' => 'گەڕانی MIME',
'mimesearch-summary' => 'ئەم لاپەڕە پاڵێوتنی هەیە بۆ جۆرەکانی MIME.
ناودراو: جۆرەی ناوەڕۆک\\ژێرجۆرە، وەک <code>image/jpeg</code>.',
'mimetype' => 'جۆرەی MIME:',
'download' => 'داگرتن',

# Unwatched pages
'unwatchedpages' => 'پەڕە چاودێری‌نەکراوەکان',

# List redirects
'listredirects' => 'پێرستی ڕەوانەکەرەکان',

# Unused templates
'unusedtemplates' => 'داڕێژە بەکارنەھێنراوەکان',
'unusedtemplatestext' => 'ئەم پەڕە هەموو پەڕەکانی بۆشاییی ناوی {{ns:template}} بە لیست دەکات کە لە پەڕەی تردا بەکارنەھێنراون.
لە بیری نەکەی پێش سڕینەوەیان پشکنینی بەستەرەکانی تر بۆ داڕێژەکان بکەی.',
'unusedtemplateswlh' => 'بەستەرەکانی تر',

# Random page
'randompage' => 'پەڕەیەک بە هەڵکەوت',
'randompage-nopages' => 'هیچ لاپەڕەیەک لەم {{PLURAL:$2|ناوبۆشاییەدا|ناوبۆشاییانەدا}} نیە: $1.',

# Random redirect
'randomredirect' => 'ڕەوانەکەری ھەرمەکی',
'randomredirect-nopages' => 'لە ناوبۆشایی "$1" هیچ ڕەوانکەرێک نییە.',

# Statistics
'statistics' => 'ئامارەکان',
'statistics-header-pages' => 'ئامارەکانی پەڕەکان',
'statistics-header-edits' => 'ئامارەکانی گۆڕانکارییەکان',
'statistics-header-views' => 'ئامارەکانی سەردانەکان',
'statistics-header-users' => 'ئامارەکانی بەکارھێنەران',
'statistics-header-hooks' => 'ئامارەکانی دیکە',
'statistics-articles' => 'پەڕە بە ناوەڕۆکەکان',
'statistics-pages' => 'پەڕەکان',
'statistics-pages-desc' => 'گشت پەڕەکانی ویکی، بە لەخۆگرتنی پەڕەکانی وتووێژ، ڕەوانەکراوەکان و ھتد.',
'statistics-files' => 'پەڕگە بارکراوەکان',
'statistics-edits' => 'دەستکارییەکانی پەڕەکان لە کاتی دامەزراندنی {{SITENAME}}ەوە',
'statistics-edits-average' => 'نێونجی ژمارەی دەستکارییەکان لە پەڕەیەک دا',
'statistics-views-total' => 'دیتنی هەموو',
'statistics-views-peredit' => 'دیتنی هەر دەستکارییەک',
'statistics-users' => '[[Special:ListUsers|بەکارھێنەر]]ە تۆمارکراوەکان',
'statistics-users-active' => 'ئەندامە چالاکەکان',
'statistics-users-active-desc' => 'ئەو بەکارھێنەرانە کە لە {{PLURAL:$1|ڕۆژ|$1 ڕۆژ}}ی ڕابردوودا کارێکیان جێبەجێ کردبێت.',
'statistics-mostpopular' => 'زۆرترین لاپەڕە بینراوەکان',

'pageswithprop' => 'پەڕەکان بە تایبەتمەندیی پەڕە',
'pageswithprop-legend' => 'پەڕەکان بە تایبەتمەندیی پەڕە',
'pageswithprop-text' => 'ئەم پەڕەیە ئەو پەڕانەی تایبەتمەندییەکی پەرەیەکی دیاریکراو بەکاردەھێنن پێرست دەکا.',
'pageswithprop-prop' => 'ناوی تایبەتمەندی:',
'pageswithprop-submit' => 'بڕۆ',

'doubleredirects' => 'دووجار ڕەوانەکراوەکان',
'doubleredirectstext' => 'ئەم پەڕە لیستی ئەو پەڕانەیە کە ڕەوانەکراون بۆ پەڕەیەکی ڕەوانەکراوی دیکە.
هەر ڕیزێک، بەستەرەکانی ڕەوانەکردنەوەی یەکەم و دووەم و ھەروەھا ئامانجی ڕەوانەکراوی دووەمی تێدایە کە حاڵەتی ئاساییدا مەبەستی «ڕاستی»ی ڕەوانەکراوی یەکەمیش دەبێ بۆ ئەوێ بێت.
ئەوانەی <del>هێڵیان بەسەردا کێشراوە</del> چارەسەر کراون.',
'double-redirect-fixed-move' => '[[$1]] گوێسترایەوە.
ئێستا ڕەوانکەرە بۆ [[$2]].',
'double-redirect-fixer' => 'چارەسەرکەری ڕەوانکەر',

'brokenredirects' => 'ڕەوانەکەرە شکاوەکان',
'brokenredirectstext' => 'ئەم ڕەوانەکراوانە بەستەرن بۆ ئەو پەڕانە کە بوونیان نییە:',
'brokenredirects-edit' => 'دەستکاری',
'brokenredirects-delete' => 'سڕینەوە',

'withoutinterwiki' => 'پەڕەکان بەبێ بەستەرەکانی زمان',
'withoutinterwiki-summary' => 'ئەم پەڕانە بەستەریان بۆ وەشانەکانی زمانەکانی تر نیە.',
'withoutinterwiki-legend' => 'پێشگر',
'withoutinterwiki-submit' => 'پیشاندان',

'fewestrevisions' => 'پەڕەکان بە کەمترین پێداچوونەوەکان',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بایت|بایت}}',
'ncategories' => '$1 {{PLURAL:$1|ھاوپۆل|ھاوپۆل}}',
'ninterwikis' => '$1 {{PLURAL:$1|نێوانویکی}}',
'nlinks' => '$1 {{PLURAL:$1|بەستەر|بەستەر}}',
'nmembers' => '$1 {{PLURAL:$1|ئەندام|ئەندام}}',
'nrevisions' => '$1 {{PLURAL:$1|پیاچوونەوە|پیاچوونەوە}}',
'nviews' => '$1 جار {{PLURAL:$1|بینراو|بینراو}}',
'specialpage-empty' => 'ئەنجامێک بۆ ئەم ڕاپۆرتە نییە.',
'lonelypages' => 'پەڕە ھەتیوەکان',
'lonelypagestext' => 'پەڕەکانی خوارەوە لە پەڕەکانی تری {{SITENAME}}ەوە لینکیان بۆ نەدراوە و نەھێنراونەتە نێو ھیچ پەڕەیەکی تر.',
'uncategorizedpages' => 'پەڕە پۆلێن نەکراوەکان',
'uncategorizedcategories' => 'پۆلە پۆلێن نەکراوەکان',
'uncategorizedimages' => 'پەڕگە پۆلێن نەکراوەکان',
'uncategorizedtemplates' => 'داڕێژە پۆلێن نەکراوەکان',
'unusedcategories' => 'پۆلە بەکارنەھێنراوەکان',
'unusedimages' => 'پەڕگە بەکارنەھێنراوەکان',
'popularpages' => 'پەڕە مەحبووبەکان',
'wantedcategories' => 'پۆلە داواکراوەکان',
'wantedpages' => 'پەڕە داواکراوەکان',
'wantedpages-badtitle' => 'سەردێڕی نەگونجاو لە سەرجەمی ئەنجامەکان: $1',
'wantedfiles' => 'پەڕگە داواکراوەکان',
'wantedfiletext-cat' => 'پەڕگەکانی خوارەوە بەکارھێنراون بەڵام بوونیان نییە. پەڕگەکانی ئەمارەکانی دەرەوە لەوانەیە لەم لیستەدا بن ئەگەرچی بوونیان ھەیە. ھەر باشییەکی درۆی وەھا ھێڵی بەسەردا دەکێشرێ. ھەروەھا، ئەو پەڕانە کە پەڕگەیەکیان بەکارھێناوە کە بوونی نییە لە [[:$1]]دا بە ڕیز کراون.',
'wantedfiletext-nocat' => 'پەڕگەکانی خوارەوە بەکارھێنراون بەڵام بوونیان نییە. پەڕگەکانی ئەمارەکانی دەرەوە لەوانەیە لەم لیستەدا بن ئەگەرچی بوونیان ھەیە. ھەر باشییەکی درۆی وەھا <del>ھێڵی بەسەردا دەکێشرێ</del>.',
'wantedtemplates' => 'داڕێژە داواکراوەکان',
'mostlinked' => 'پەڕەکان بە زۆرترین بەستەری پێدراو',
'mostlinkedcategories' => 'پۆلەکان بە زۆرترین بەستەری پێدراو',
'mostlinkedtemplates' => 'داڕێژەکان بە زۆرترین بەستەری پێدراو',
'mostcategories' => 'پەڕەکان بە زۆرترین پۆل',
'mostimages' => 'پەڕگەکان بە زۆرترین بەستەری پێدراو',
'mostinterwikis' => 'پەڕەکان بە زۆرترین نێوانویکی',
'mostrevisions' => 'پەڕەکان بە زۆرترین پێداچوونەوەکان',
'prefixindex' => 'ھەموو پەڕەکان بە پێشگرەوە',
'prefixindex-namespace' => 'هەموو پەڕەکان بەپێشگری (بۆشایی ناوی $1)',
'shortpages' => 'پەڕە کورتەکان',
'longpages' => 'پەڕە درێژەکان',
'deadendpages' => 'پەڕە بنبەستەکان',
'deadendpagestext' => 'ئەم پەڕانەی خوارەوە پەیوەندییان لەگەڵ پەڕەکانی تری {{SITENAME}} نییە.',
'protectedpages' => 'پەڕە پارێزراوەکان',
'protectedpages-indef' => 'تەنیا پاراستنە بێسنوورەکان',
'protectedpages-cascade' => 'تەنیا پاراستنە زنجیرییەکان',
'protectedpagestext' => 'ئەم لاپەڕانە لە گواستنەوە و دەستکاری‌کردن پارێزراون',
'protectedpagesempty' => 'هیچ لاپەڕەیک ئێستا بەم دیاریکراوانە نەپارێزراوە.',
'protectedtitles' => 'سەرناوە پارێزراوەکان',
'protectedtitlestext' => 'ئەم سەردێڕانە لە درووست‌کردن پارێزراون',
'protectedtitlesempty' => 'ھیچ سەرناوێک بەم سنوورانەوە ئێستا نەپارێزراوە.',
'listusers' => 'پێرستی بەکارھێنەران',
'listusers-editsonly' => 'تەنیا ئەو بەکارھێنەرانە نیشان بدە کە دەستکارییان کردووە',
'listusers-creationsort' => 'ڕیزکردن بە پێی ڕێکەوتی دروستکردن',
'usereditcount' => '$1 {{PLURAL:$1|دەستکاری|دەستکاری}}',
'usercreated' => 'لە $1، $2 {{GENDER:$3|دروست کراوە}}',
'newpages' => 'پەڕە نوێکان',
'newpages-username' => 'ناوی بەکارھێنەر:',
'ancientpages' => 'کۆنترین پەڕەکان',
'move' => 'گواستنەوە',
'movethispage' => 'ئەم پەڕەیە بگوازەوە',
'unusedimagestext' => 'ئەم پەڕگانەی خوارەوە بوونیان ھەیە بەڵام لە ھیچ پەڕەیەکدا بەکارنەھێنراون.
تکایە ئاگادار بە لەوانەیە ماڵپەڕەکانی تر بە URLـی سەرڕاست بەستەرییان دابێت بۆ پەڕگەیەک و لەوانەیە ھێشتا لەم لیستەدا بێت ئەگەرچی لە بەکارھێنانی چالاکدایە.',
'unusedcategoriestext' => 'ئەم پەڕەی پۆلانە ھەن، ئەگەرچی ھیچ پەڕە یان پۆلێکی تر کەڵکیان لێ وەرناگرێ.',
'notargettitle' => 'بێ مەبەست',
'notargettext' => 'لاپەڕە یان بەکارهێنەرێکت دیاری نەکردوو تاکەە ئەو فەنکشێنە لەسەر بهێنیتە کار.',
'nopagetitle' => 'چاودێری',
'nopagetext' => 'لاپەڕەی مەبەست وا ڕاچاوت کردووە بوونی نییە.',
'pager-newer-n' => '{{PLURAL:$1|یەکێکی نوێتر|$1ی نوێتر}}',
'pager-older-n' => '{{PLURAL:$1|یەکێک کۆنتر|$1ی کۆنتر}}',
'suppress' => 'چاودێری',

# Book sources
'booksources' => 'سەرچاوەکانی کتێب',
'booksources-search-legend' => 'بۆ سەرچاوەی کتێب بگەڕێ',
'booksources-go' => 'بڕۆ',
'booksources-text' => 'لە خوارەوە لیستێک لە بەستەر بۆ ماڵپەڕهایەک کە کتێبی نوێ و بەکارهێنراو دەفرۆشێت و لەوانەیە لەوێ زانیاریی زیاترت دەست‌کەوێت سەبارەت بەو کتێبانەی لە دووی دەگەڕیت:',
'booksources-invalid-isbn' => 'ISBN دراو لەوە ناچی بەکار بێت، سەرنج بدە لە کاتی کۆپی کردن لە سەرچاوە تووشی هەڵە نوبوبێت.',

# Special:Log
'specialloguserlabel' => 'بەجێھێنەر:',
'speciallogtitlelabel' => 'مەبەست (سەرناو یان بەکارھێنەر):',
'log' => 'لۆگەکان',
'all-logs-page' => 'ھەموو لۆگە گشتییەکان',
'alllogstext' => 'نیشاندانی تێکڕای هەموو لۆگە بەردەستەکانی {{SITENAME}}.
دەتوانی بە ھەڵبژاردنی جۆرە لۆگێک، ناوی بەکارھێنەرەکە (ھەستیار بە گەورە و بچووکی پیتەکان) یان پەڕە کارتێکراوەکە (ھەستیار بە گەورە و بچووکی پیتەکان)
بینینەکە سنووردار بکەیتەوە.',
'logempty' => 'هیچ بابەتێکی هاوتا لە لۆگەکاندا نەدۆزرایەوە.',
'log-title-wildcard' => 'گەڕانی ئەو سەرناوانە بەم دەقەوە دەست پێدەکەن',
'showhideselectedlogentries' => 'بابەتەکانی ھەڵبژێردراوی لۆگ نیشان بدە/بشارەوە',

# Special:AllPages
'allpages' => 'ھەموو پەڕەکان',
'alphaindexline' => '$1 تا $2',
'nextpage' => 'پەڕەی پاشەوە ($1)',
'prevpage' => 'پەڕەی پێشەوە ($1)',
'allpagesfrom' => 'نیشاندانی پەڕەکان بە دەستپێکردن لە:',
'allpagesto' => 'نیشاندانی پەڕەکان بە دوایی ھاتن بە:',
'allarticles' => 'ھەموو پەڕەکان',
'allinnamespace' => 'ھەموو پەڕەکان (بۆشایی-ناوی $1)',
'allnotinnamespace' => 'ھەموو پەڕەکان (ئەوانەی لە بۆشایی-ناوی $1دا نین)',
'allpagesprev' => 'پێش',
'allpagesnext' => 'پاش',
'allpagessubmit' => 'بڕۆ',
'allpagesprefix' => 'نیشاندانی پەڕەکان بە پێشگری:',
'allpagesbadtitle' => 'سەردێڕی لاپەڕە گونجاو نەبوو یان پێشگڕێکی بەینی‌زمانی یان بەینی‌ویکی هەبوو.
لەوانەیە یەک یان زیاتر پیتی نەگونجاو بۆ سەردێڕی لەخۆ گرتبێ.',
'allpages-bad-ns' => '{{SITENAME}} ناوبۆشایی نیە "$1".',
'allpages-hide-redirects' => 'ڕەوانەکەرەکان بشارەوە',

# SpecialCachedPage
'cachedspecial-refresh-now' => 'دواترین پیشانبدە',

# Special:Categories
'categories' => 'پۆلەكان',
'categoriespagetext' => 'ئەم {{PLURAL:$1|پۆلە پەڕە یان پەڕگەی|پۆلانە پەڕە یان پەڕگەیان}} لەخۆگرتە.
[[Special:UnusedCategories|پۆلە بەکارنەھێنراوەکان]] لێرەدا نیشان نەدراون.
[[Special:WantedCategories|پۆلە خوازراوەکان]]یش ببینە.',
'categoriesfrom' => 'نیشاندانی پۆلەکان بە دستپێکردن لە:',
'special-categories-sort-count' => 'ڕیز کردن بە پێی ژمارە',
'special-categories-sort-abc' => 'ڕیزکردن بە پێی ئەلفوبێ',

# Special:DeletedContributions
'deletedcontributions' => 'بەشدارییە سڕاوەکان',
'deletedcontributions-title' => 'بەشدارییە سڕاوەکانی بەکارھێنەر',
'sp-deletedcontributions-contribs' => 'بەشدارییەکان',

# Special:LinkSearch
'linksearch' => 'گەڕانی بەستەرە دەرەکییەکان',
'linksearch-pat' => 'گەڕان بۆ نواندن:',
'linksearch-ns' => 'بۆشاییی ناو:',
'linksearch-ok' => 'گەڕان',
'linksearch-text' => 'Wildcardی وەک "*.wikipedia.org" بەکاردێت.
لانی کەم پێویستی بە پاوانێکی ئاست-بان ھەیە، بۆ نموونە «*.org» .<br />
پرۆتۆکۆلە پشتیوانی لێکراوەکان: <code>$1</code> (ھیچ کام لەمانە بە گەڕانەکەت زێدە مەکە).',
'linksearch-line' => '$1 بەستەرپێ‌دراو لە $2',

# Special:ListUsers
'listusersfrom' => 'نیشاندانی بەکارھێنەران بە دەستپێکردن لە:',
'listusers-submit' => 'نیشانیبدە',
'listusers-noresult' => 'ھیچ بەکارھێنەرێک نەدۆزرایەوە.',
'listusers-blocked' => '(بەربەست کراوە)',

# Special:ActiveUsers
'activeusers' => 'پێرستی بەکارھێنەرە چالاکەکان',
'activeusers-intro' => 'ئەمە لیستێکی ئەو بەکارھێنەرانەیە کە لە  $1 {{PLURAL:$1|ڕۆژ|ڕۆژ}}ی ڕابردوودا بە جۆرێک چالاکییەکیان ھەبووە.',
'activeusers-count' => '$1 {{PLURAL:$1|کردەوە}} لە دوایین {{PLURAL:$3|ڕۆژ|$3 ڕۆژ}}دا',
'activeusers-from' => 'نیشاندانی بەکارھێنەران بە دەستپێکردن لە:',
'activeusers-hidebots' => 'بۆتەکان بشارەوە',
'activeusers-hidesysops' => 'بەڕێوبەران بشارەوە',
'activeusers-noresult' => 'هیچ بەکارهێنەرێک نەدۆزرایەوە',

# Special:ListGroupRights
'listgrouprights' => 'مافەکانی گرووپی بەکارھێنەر',
'listgrouprights-summary' => 'ئەمە لیستێکە لە گرووپەکانی بەکارهێنەر لەسەر ئەم ویکی‌یە، دەگەڵ مافەکانی دەست‌پێ‌گەیشتنی هاوپەیوەندیان.
لێرەدا لەوانەیە [[{{MediaWiki:Listgrouprights-helppage}}|زانیاری زیاترت]] دەست‌کەوێت سەبارەت بە مافە تاکەکەسیەکان.',
'listgrouprights-key' => 'تێبینی:
* <span class="listgrouprights-granted">مافی دراوە</span>
* <span class="listgrouprights-revoked">مافی سەنراوە</span>',
'listgrouprights-group' => 'گرووپ',
'listgrouprights-rights' => 'مافەکان',
'listgrouprights-helppage' => 'Help:مافەکانی گرووپ',
'listgrouprights-members' => '(پێرستی ئەندامەکان)',
'listgrouprights-addgroup' => 'زیادکردنی {{PLURAL:$2|گرووپ|گرووپ}}: $1',
'listgrouprights-removegroup' => 'لابردنی {{PLURAL:$2|گرووپ|گرووپ}}: $1',
'listgrouprights-addgroup-all' => 'زیادکردنی هەموو گرووپەکان',
'listgrouprights-removegroup-all' => 'لابردنی هەموو گرووپەکان',
'listgrouprights-addgroup-self' => 'زیادکردنی {{PLURAL:$2|گرووپ|گرووپەکان}} بۆ سەر ھەژماری خۆی: $1',
'listgrouprights-removegroup-self' => 'لابردنی {{PLURAL:$2|گرووپ|گرووپەکان}} لە سەر ھەژماری خۆی: $1',
'listgrouprights-addgroup-self-all' => 'زیادکردنی ھەموو گرووپەکان بۆ سەر ھەژماری خۆی',
'listgrouprights-removegroup-self-all' => 'لابردنی هەموو گرووپەکان له‌ سه‌ر هه‌ژماری خۆ',

# Email user
'mailnologin' => 'ناونیشان بۆ ناردن نییه‌',
'mailnologintext' => 'ده‌بێ له‌ [[Special:UserLogin|ژووره‌وه‌]] بیت و ناونیشانێکی بڕواپێ‌کراوی ئی‌مه‌یلت له‌ ناو [[Special:Preferences|هه‌ڵبژارده‌کان]] دیاری کردبێت تا بتوانی ئی‌مه‌یل بنێریت بۆ به‌کارهێنه‌رانی دیکه‌.',
'emailuser' => 'ئیمەیل بنێرە بۆ ئەم بەکارھێنەرە',
'emailuser-title-target' => 'ئیمەیلی ئەم {{GENDER:$1|بەکارھێنەر}}ە',
'emailuser-title-notarget' => 'ئیمەیل بۆ بەکارھێنەر',
'emailpage' => 'ئیمەیل بۆ بەکارھێنەر',
'emailpagetext' => 'دەتوانی لەم فۆرمەی ژێرەوە بۆ ناردنی ئیمەیلێک بۆ ئەم {{GENDER:$1|بەکارھێنەر}}ە کەڵک وەربگریت.
ئەو ناونیشانە ئیمەیلە لە [[Special:Preferences|ھەڵبژاردەکانی بەکارھێنەر‌یتدا]] نووسیوتە، لە ناونیشانی «لەلایەن»ی (From) ئیمەیلەکەدا نیشان دەدرێت، کە وایە بەکارھێنەری وەرگر دەتوانێ ڕاستەوخۆ وەڵامت بداتەوە.',
'defemailsubject' => 'ئیمەیلی {{SITENAME}} لە بەکارھێنەر «$1»ەوە',
'usermaildisabled' => 'ئیمەیڵی بەکارهێنەر لەکاردانیە',
'noemailtitle' => 'هیچ ناونیشانێکی ئی‌مەیل نییە',
'noemailtext' => 'ئەم بەکارهێنەرە ناونێشانێکی بڕوا پێکراوی ئی‌مەیلی دانەناوە.',
'nowikiemailtitle' => 'ڕێگە بۆ ئی‌مەیل نەدراوە',
'nowikiemailtext' => 'ئەم بەکارهێنەرە تایبەتمەندیی وەرنەگرتنی ئی‌مەیل لە بەکارهێنەرانی دیکەی هەلبژاردووە.',
'emailtarget' => 'ناوی بەکارھێنەریی وەرگر بنووسە',
'emailusername' => 'ناوی به‌كارھێنه‌ر:',
'emailusernamesubmit' => 'بینێرە',
'email-legend' => 'ناردنی ئیمەیلێک بۆ بەکارھێنەرێکی تری {{SITENAME}}',
'emailfrom' => 'لە:',
'emailto' => 'بۆ:',
'emailsubject' => 'بابەت:',
'emailmessage' => 'پەیام:',
'emailsend' => 'بینێرە',
'emailccme' => 'کۆپییەک لە پەیامەکە بنێرە بۆ ئیمەیلەکەم.',
'emailccsubject' => 'کۆپیی نامەکەت بۆ $1: $2',
'emailsent' => 'نامەکەت ناردرا',
'emailsenttext' => 'نامەکەت ناردرا',
'emailuserfooter' => 'ئەم ئیمەیلە لە $1ەوە ناردرا بۆ $2 بە "Email user" لە {{SITENAME}}ەوە.',

# User Messenger
'usermessage-summary' => 'بەجێھێشتنی پەیامی سیستەم',
'usermessage-editor' => 'پەیامنێری سیستەم',

# Watchlist
'watchlist' => 'پێرستی چاودێری',
'mywatchlist' => 'پێرستی چاودێری',
'watchlistfor2' => 'بۆ $1 $2',
'nowatchlist' => 'لە لیستی چاودێڕییەکانتدا ھیچ نیە.',
'watchlistanontext' => 'بۆ دیتن و دەستکاریی بابەتەکانی  ناو پێرستی چاودێرییەکەتدا دەبێ $1.',
'watchnologin' => 'لە ژوورەوە نیت.',
'watchnologintext' => 'دەبی لە [[Special:UserLogin|ژوورەوە]] بیت بۆ ئەوەی بتوانی گۆڕانکاری بکەیت لە لیستی چاودێریەکەت‌دا.',
'addwatch' => 'بیخە سەر لیستی چاودێری',
'addedwatchtext' => 'پەڕەی «[[:$1]]» خرایە ژێر [[Special:Watchlist|پێرستی چاودێری]]یەکەت.
گۆڕانکارییەکانی داھاتووی ئەم پەڕەیە و پەڕەی وتووێژەکەی، لەوێدا پێرست دەکرێت.',
'removewatch' => 'لەلیستی چاودێری لایبە',
'removedwatchtext' => 'پەڕەی «[[:$1]]» لە [[Special:Watchlist|لیستی چاودێریەکەت]] لابرا.',
'watch' => 'چاودێری بکە',
'watchthispage' => 'ئەم پەڕەیە بخە ژێر چاودێری',
'unwatch' => 'لابردنی چاودێری',
'unwatchthispage' => 'ئیتر چاودێری مەکە',
'notanarticle' => 'پەڕەی بێ ناوەڕۆک',
'notvisiblerev' => 'پیاچوونەوە سڕاوەتەوە',
'watchlist-details' => '{{PLURAL:$1|$1 پەڕە|$1 پەڕە}} لە لیستی چاودێریەکەتدایە، بێجگە پەڕەکانی لێدوان.',
'wlheader-enotif' => 'ئاگاداری بە ئیمەیل چالاکە.',
'wlheader-showupdated' => "‏ئەو پەڕانە کە لە پاش دواین سەردانت دەستکاری کراون بە '''ئەستوور''' نیشان دراون",
'watchmethod-recent' => 'سەرنج‌دانی دوایین دەستکاریەکان بۆ لاپەڕە چاودێری‌کراوەکان',
'watchmethod-list' => 'سەرنج‌دانی لاپەڕە چاودێری‌کراوەکان بۆ دوایین دەستکاریەکان',
'watchlistcontains' => 'لیستی چاودێڕییەکانت $1 {{PLURAL:$1|پەڕە|پەڕە}}ی تێدایە.',
'iteminvalidname' => "ھەڵە لەگەڵ بابەتی '$1'، ناوی نادروست...",
'wlnote' => "خوارەوە {{PLURAL:$1|دوایین گۆڕانکارییە|دوایین '''$1''' گۆڕانکارییە}} لە دوایین {{PLURAL:$2|کاتژمێر|'''$2''' کاتژمێر}}دا ھەتا $4 لە $3.",
'wlshowlast' => 'دوایین $1 کاتژمێر $2 ڕۆژی $3 نیشان بدە',
'watchlist-options' => 'ھەڵبژاردەکانی لیستی چاودێری',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'چاودێری...',
'unwatching' => 'لابردنی چاودێری...',

'enotif_mailer' => 'نامەی ڕاگەیاندنی {{SITENAME}}',
'enotif_reset' => 'ھەموو پەڕەکان وەک بینراو دیاری بکە',
'enotif_impersonal_salutation' => 'بەکارهێنەری 	{{SITENAME}}',
'enotif_subject_deleted' => 'پەڕەی {{SITENAME}} $1 بە دەستی {{gender:$2|$2}} سڕایەوە.',
'enotif_subject_created' => 'پەڕەی {{SITENAME}} $1 بە دەستی {{gender:$2|$2}} دروست کرا.',
'enotif_subject_moved' => 'پەڕەی {{SITENAME}} $1 بە دەستی {{gender:$2|$2}} گوازرایەوە.',
'enotif_subject_restored' => 'پەڕەی {{SITENAME}} $1 بە دەستی {{gender:$2|$2}} ھێنرایەوە.',
'enotif_subject_changed' => 'پەڕەی {{SITENAME}} $1 بە دەستی {{gender:$2|$2}} گۆڕا.',
'enotif_body_intro_deleted' => 'پەڕەی {{SITENAME}} $1 لە $PAGEEDITDATE بە دەستی {{gender:$2|$2}} سڕایەوە، بڕوانە $3.',
'enotif_body_intro_created' => 'پەڕەی {{SITENAME}} $1 لە $PAGEEDITDATE بە دەستی {{gender:$2|$2}} دروست کرا، بۆ پێداچوونەی ھەنووکە بڕوانە $3.',
'enotif_body_intro_moved' => 'پەڕەی {{SITENAME}} $1 لە $PAGEEDITDATE بە دەستی {{gender:$2|$2}} گوازرایەوە، بۆ پێداچوونەی ھەنووکە بڕوانە $3.',
'enotif_body_intro_restored' => 'پەڕەی {{SITENAME}} $1 لە $PAGEEDITDATE بە دەستی {{gender:$2|$2}} ھێنرایەوە، بۆ پێداچوونەی ھەنووکە بڕوانە $3.',
'enotif_body_intro_changed' => 'پەڕەی {{SITENAME}} $1 لە $PAGEEDITDATE بە دەستی {{gender:$2|$2}} گۆڕا، بۆ پێداچوونەی ھەنووکە بڕوانە $3.',
'enotif_lastvisited' => 'بۆ بینینی ھەموو گۆرانکارییەکانی پاش دوایین سەردانت $1 ببینە.',
'enotif_lastdiff' => 'بۆ بینینی ئەم گۆڕانکارییە $1 ببینە.',
'enotif_anon_editor' => 'بەکارھێنەری نەناسراو $1',
'enotif_body' => '‫$WATCHINGUSERNAMEی بەڕێز،

$PAGEINTRO $NEWPAG

کورتەی دەستکارییەکەی: $PAGESUMMARY $PAGEMINOREDIT

پەیوەندی لەگەڵ دەستکاریکەر:
نامە: $PAGEEDITOR_EMAIL
ویکی: $PAGEEDITOR_WIKI

تا سەردانی ئەم پەڕەیە نەکەیت، گۆڕانکارییەکانی داھاتووی پەڕەکەت پێ ڕاناگەیێندرێت. هەروەھا دەتوانی ئاڵاکانی ڕاگەیاندن لە پەڕەی چاودێرییەکەتدا لە سەرەتاوە ڕێک بخەیتەوە.

بە سپاسەوە، سیستەمی ڕاگەیاندنی {{SITENAME}}

--
بۆ گۆڕینی رێکخستنەکانی ڕاگەیاندن بە ئیمەیل، بڕوانە
{{canonicalurl:{{#special:Preferences}}}}

بۆ گۆڕینی ڕێکخستنەکانی پێرستی چاودێرییەکەت، بڕوانە
{{canonicalurl:{{#special:EditWatchlist}}}}

بۆ سڕینەوەی پەڕەکە لە پێرستی چاودێرییەکەت، بڕوانە
$UNWATCHURL

کاردانەوە و یارمەتیی زۆرتر:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'دروستکرا',
'changed' => 'گۆڕدرا',

# Delete
'deletepage' => 'پەڕە بسڕەوە',
'confirm' => 'پشتدار بکەرەوە',
'excontent' => 'ناوەرۆک ئەمە بوو: «$1»',
'excontentauthor' => 'ناوەرۆک ئەمە بوو: «$1» (و تەنیا بەشداربوو «[[Special:Contributions/$2|$2]]» بوو)',
'exbeforeblank' => 'ناوەرۆک بەر لە واڵاکردنەوە ئەمە بوو: «$1»',
'exblank' => 'پەڕە واڵا بوو',
'delete-confirm' => 'سڕینەوەی «$1»',
'delete-legend' => 'بیسڕەوە',
'historywarning' => "'''وشیار بە:''' پەڕەیەک کە دەتەوێ بیسڕیتەوە مێژوویەکی ھەیە بە نزیکەی $1 {{PLURAL:$1|پێداچوونەوە|پێداچوونەوە}}وە:",
'confirmdeletetext' => 'تۆ خەریکی پەڕەیەک بە ھەموو مێژووەکەیەوە دەسڕیتەو.
تکایە پشتڕاستی بکەوە کە دەتەوێت ئەم کارە بکەی، لە ئاکامەکەی تێدەگەی، و ئەم کارە بە پێی [[{{MediaWiki:Policy-url}}|سیاسەتنامە]] ئەنجام دەدەی.',
'actioncomplete' => 'کردەوە بە ئاکام گەییشت',
'actionfailed' => 'کردارەکە سەرنەکەوت',
'deletedtext' => '«$1»  سڕایەوە.
سەیری $2 بکە بۆ تۆمارێکی دوایین سڕینەوەکان.',
'dellogpage' => 'لۆگی سڕینەوە',
'dellogpagetext' => 'ئەوەی خوارەوە لیستێكە لە دوایین سڕینەوەکان',
'deletionlog' => 'لۆگی سڕینەوە',
'reverted' => 'گەڕێندراوە بۆ پێداچوونەوەی پێشووتر',
'deletecomment' => 'ھۆکار:',
'deleteotherreason' => 'ھۆکاری تر/زیاتر:',
'deletereasonotherlist' => 'ھۆکاری تر',
'deletereason-dropdown' => '* ھۆکاری سڕینەوە
** داواکاریی نووسەر
** تێکدانی مافی لەبەرگرتنەوە
** خراپکاری',
'delete-edit-reasonlist' => 'دەستکاری کردنی ھۆکارەکانی سڕینەوە',
'delete-toobig' => 'ئەم لاپەڕە مێژوویەکی دەستکاری زۆر گەورەی هەیە، زیاتر لە $1 {{PLURAL:$1|پێداچوونەوە|پێداچوونەوە}}.
بۆ بەرگری لە خراپ‌بوونی چاوەڕوان نەکراوی {{SITENAME}}، سڕینەوەی لاپەڕەی وا بەربەست‌کراوە.',
'delete-warning-toobig' => 'ئەم لاپەڕە مێژوویەکی دەستکاری زۆر گەورەی هەیە، زیاتر لە $1 {{PLURAL:$1|پێداچوونەوە|پێداچوونەوە}}.
سڕینەوی ئەوە لە وانەیە کارەکانی بنکەدراوی {{SITENAME}} تووشی کێشە بکات؛
دوورنواڕانە جێ‌بەجێی بکە.',

# Rollback
'rollback' => 'گەڕاندنەوەی دەستکارییەکان',
'rollback_short' => 'گەڕاندنەوە',
'rollbacklink' => 'گەڕاندنەوە',
'rollbacklinkcount' => 'گەڕاندنەوەی $1 {{PLURAL:$1|دەستکاری}}',
'rollbacklinkcount-morethan' => 'گەڕاندنەوەی زۆرتر لە $1 {{PLURAL:$1|دەستکاری}}',
'rollbackfailed' => 'گەڕاندنەوە سەرکەوتوو نەبوو',
'cantrollback' => 'دەستکاریەکان ناگەڕێندرێتەوە؛
دوایین هاوبەش تەنها ڕێکخەری ئەم لاپەڕەیە.',
'alreadyrolled' => 'دوایین گۆڕانکاریەکانی لەسەر [[:$1]] لە لایەن [[User:$2|$2]] ناگەڕێندرێنەوە ([[User talk:$2|وتووێژ]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]])؛ کەسێکی دیکە لە پێش‌دا دەستکاری کردووە یان گەڕاندوویەتەوە.

دوایین دەستکاری ئەم لاپەڕە [[User:$3|$3]] کردوویە ([[User talk:$3|وتووێژ]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "پوختەی دەستکاری \"''\$1''\" بوو.",
'revertpage' => 'گەڕاندنەوەی دەستکارییەکانی [[Special:Contributions/$2|$2]] ([[User talk:$2|لێدوان]]) بۆ دوایین پێداچوونەوەی [[User:$1|$1]]',
'revertpage-nouser' => 'دەستکارییەکانی (ناوی بەکارھێنەر سڕاوەتەوە) بۆ دوایین پێداچوونەوەی [[User:$1|$1]] گەڕێنراوە.',
'rollback-success' => 'دەستکارییەکانی $1 وەرگێرایەوە؛<br />
گۆڕدرا بۆ دوایین پێداچوونەوەی $2.',

# Edit tokens
'sessionfailure' => 'لەوەدەچی کێشەیەک لە دانیشتنی چوونەژوورەوەت (login session)دا ببێت.
ئەم کردەوە هەڵوەشێندرایەوە بۆ بەرگری لە دزینی دراوەکانی دانیشتن.
تکایە بگەڕێوە بۆ پەڕەی پێشوو و نوێی بکەوە، ئینجا دیسان تاقیی بکەوە.',

# Protect
'protectlogpage' => 'لۆگی پاراستن',
'protectlogtext' => 'لە ژێرەوە پێرستێک لە گۆڕانکارییەکانی پەڕە پارێزراوەکان دەبینی.
بۆ پێرستی ئەو پەڕانەی ئێستا پاراستنیان لە ئارادایە بڕوانە [[Special:ProtectedPages|پێرستی پەڕە پارێزراوەکان]].',
'protectedarticle' => '«[[$1]]»ی پاراست',
'modifiedarticleprotection' => 'ئاستی پاراستنی «[[$1]]»ی گۆڕی',
'unprotectedarticle' => 'پاراستنی لەسەر «[[$1]]» لابرد',
'movedarticleprotection' => 'ڕێککارییەکانی پاراستن لە  «[[$2]]» گوازرایەوە بۆ «[[$1]]»',
'protect-title' => 'گۆڕینی ئاستی پاراستنی "$1"',
'protect-title-notallowed' => 'دیتنی ئاستی پاراستنی «$1»',
'prot_1movedto2' => '[[$1]] گوازرایەوە بۆ [[$2]]',
'protect-legend' => 'پاراستن تەیید بکە',
'protectcomment' => 'ھۆکار:',
'protectexpiry' => 'ھەتا:',
'protect_expiry_invalid' => 'کاتی بەسەرچوون نادروستە:',
'protect_expiry_old' => 'کاتی بەسەرچوون ڕابردووە',
'protect-text' => "دەتوانی لێرەوە ئاستی پاراستنی پەڕەی '''$1''' ببینی و بیشیگۆڕی.",
'protect-locked-blocked' => "ناتوانیت ئاستی پاراستن بگۆڕیت کاتێ بەستراوە.
ئەوە هەڵبژاردەکانی ئێستای ڕێکخستنی بۆ لاپەڕە '''$1''':",
'protect-locked-dblock' => "ئاستەکانی پاراستن ناگۆڕدرێن کاتێ بنکەدراوی چالاک داخرابێت.
ئەوە هەڵبژاردەکانی ئێستای ڕێکخستنی بۆ لاپەڕە '''$1''':",
'protect-locked-access' => "ھەژمارەکەت ڕێگەی ئەوەی پێ نەدراوە کە بتوانێت ئاستی پاراستنی پەڕە بگۆڕێت.
ڕێککارییەکانی ئێستای پەڕەی '''$1''' ئەمەتە:",
'protect-cascadeon' => 'ھەنووکە ئەم پەڕە پارێزراوە بۆ ئەوەی کە لە نێو ئەم {{PLURAL:$1|پەڕە کە پاراستنی تاڤگەییی|پەڕانە کە پاراستنی تاڤگەیییان}} بۆ چالاککراوە، ھێنراوە.
دەتوانی ئاستی پاراستنی ئەم پەڕە بگۆڕی، بەڵام ھیچ کاریگەرییەکی نابێت لە سەر پاراستنی تاڤگەیی',
'protect-default' => 'بە ھەموو بەکارھێنەران ڕێگە بدە',
'protect-fallback' => 'تەنیا بە بەکارھێنەران بە مافی «$1» ڕێگە بدە',
'protect-level-autoconfirmed' => 'تەنیا بە بەکارھێنەرانی پەسندکراو ڕێگە بدە',
'protect-level-sysop' => 'تەنیا بەڕێوەبەران',
'protect-summary-cascade' => 'تاڤگەیی',
'protect-expiring' => 'بەسەردەچێ لە ڕێکەوتی $1 (UTC)',
'protect-expiring-local' => 'بە سەر دەچێ لە $1',
'protect-expiry-indefinite' => 'بێسنوور',
'protect-cascade' => 'پەڕەکانی نێو ئەم پەڕە بپارێزە (پاراستنی تاڤگەیی)',
'protect-cantedit' => 'ناتوانی ئاستی پاراستنی ئەم پەڕە بگۆڕی، چونکوو تۆ ئیجازەی ئەم کارەت نیە.',
'protect-othertime' => 'کاتی دیکە:',
'protect-othertime-op' => 'کاتی دیکە',
'protect-existing-expiry' => 'ئەم کاتی بەسەرچوونی ماوە کە هەیە: $3، $2',
'protect-otherreason' => 'ھۆکاری تر/زیاتر:',
'protect-otherreason-op' => 'ھۆکاری تر',
'protect-dropdown' => '*ھۆکارە باوەکانی پاراستن
** خراپکاریی لەڕادەبەدەر
** سپامی لەڕادەبەدەر
** شەڕە دەستکاریی بێ‌سوود
** پەڕەی زۆربینەردار',
'protect-edit-reasonlist' => 'دەستکاری کردنی ھۆکارەکانی پاراستن',
'protect-expiry-options' => '١ کاتژمێر:1 hour,١ ڕۆژ:1 day,١ ھەفتە:1 week,٢ ھەفتە:2 weeks,١ مانگ:1 month,٣ مانگ:3 months,٦ مانگ:6 months,١ ساڵ:1 year,بی‌بڕانەوە:infinite',
'restriction-type' => 'ئیزن:',
'restriction-level' => 'ئاستی سنووردارکردن:',
'minimum-size' => 'کەمترین قەبارە',
'maximum-size' => 'زۆرترین قەبارە:',
'pagesize' => '(بایت)',

# Restrictions (nouns)
'restriction-edit' => 'دەستکاری',
'restriction-move' => 'گواستنەوە',
'restriction-create' => 'دروستکردن',
'restriction-upload' => 'بارکردن',

# Restriction levels
'restriction-level-sysop' => 'تەواو پارێزراو',
'restriction-level-autoconfirmed' => 'نیوەپارێزراو - ئاستی ١',
'restriction-level-all' => 'هەر ئاستێک',

# Undelete
'undelete' => 'پەڕە سڕاوەکان ببینە',
'undeletepage' => 'پەڕە سڕاوەکان ببینە و بھێنەوە',
'undeletepagetitle' => "'''ئەمە تێکەڵ‌کراوەی پێداچوونەوە سڕدراوەکانی [[:$1|$1]]'''.",
'viewdeletedpage' => 'پەڕە سڕاوەکان ببینە',
'undeletepagetext' => 'ئەم {{PLURAL:$1|سڕاوەتەوە|$1 لاپەڕە سڕاونەتەوە}} بەڵام لەبەر ئەوەی لە ئەرشیڤ‌دا هەن هێشتا دەتوانی بیانهێنیتەوە.
ئەرشیڤ چەن‌وەخت جارێ لە کاتی دیاری‌کراودا خاوێن‌دەکرێتەوە.',
'undelete-fieldset-title' => 'هێنانەوەی پێداچوونەوەکان',
'undeleteextrahelp' => "بۆ ھێنانەوەی گشت مێژووی پەڕەکە، ھەموو چوارچێوەکانی نیشانکردن ھەڵنەبژێردراو بھێڵەوە و لە سەر '''''{{int:undeletebtn}}''''' کرتە بکە.
بۆ ھێنانەوەی ھەڵبژێردراو، چوارچێوەی بەرامبەر بەو پێداچوونەویەی دەتەوێ بیھێنیتەوە، نیشان بکە و لە سەر '''''{{int:undeletebtn}}''''' کرتە بکە.",
'undeleterevisions' => '$1 {{PLURAL:$1|پێداچوونەوە|پێداچوونەوە}} ئەرشیڤ‌کرا',
'undeletehistory' => 'ئەگەر پەڕەیەک بھێنیتەوە، ھەموو پێداچوونەوەکان دەگەڕێنەوە بۆ مێژووی پەڕە.
ئەگەر لە کاتی سڕانەوەی پەڕەکەوە، پەڕەیەک هەر بەو ناوەوە دروست کرابێت، پێداچوونەوە گەرێنراوەکان لە مێژووی پێشووەکەدا دەدرەکەوێت.',
'undeletehistorynoadmin' => 'ئەم لاپەڕە سڕاوەتەوە.
لەو پۆختەی لە خوارەوە دەیبینی، هۆکاری سڕینەوە و هەروا وردەکاریەکان سەبارە بەو کەسەی پێش سڕینەوە دەستکاری لاپەڕەکەی کردووە، دەست‌دەکەوێ.
دەقی ڕاستی ئەم پێداچوونەوە سڕاوانە تەنها بۆ بەڕێوبەران دەست‌پێ‌گەیشتنی هەیە.',
'undelete-revision' => 'پێداچوونەوەی سڕاوەی $1 (لە $4،  $5) لەلایەن $3:',
'undeleterevision-missing' => 'پێداچوونەوەی نادیار یا نەناسراو.
لەوانەیە خەریکی لە بەستەرێکی خراپ کەڵک وەر ئەگری ئا لەوانەیە پێداچوونەوەکە لە ئەرشیڤ لابرابێت.',
'undelete-nodiff' => 'هیچ پێداچوونەوەیەکی پێشو نەدۆزرایەوە.',
'undeletebtn' => 'هێنانەوە',
'undeletelink' => 'ببینە/بھێنەوە',
'undeleteviewlink' => 'دیتن',
'undeletereset' => 'بردنەوە نووک',
'undeleteinvert' => 'ھەڵبژاردەکان پێچەوانە بکە',
'undeletecomment' => 'هۆکار:',
'undeletedrevisions' => '{{PLURAL:$1|1 پێداچوونەوە|$1 پێداچوونەوە}} هێنرایەوە',
'undeletedrevisions-files' => '{{PLURAL:$1|1 پێداچوونەوە|$1 پێداچوونەوە}} و {{PLURAL:$2|1 پەڕگە|$2 پەڕگە}} هێنرایەوە',
'undeletedfiles' => '{{PLURAL:$1|1 پەڕگە|$1 پەڕگە}} هێنرایەوه',
'cannotundelete' => 'ھێنانەوە سەرکەوتوو نەبوو:
$1',
'undeletedpage' => "'''$1 هێنراوەتەوە'''

بۆ دیتنی پێشینەی دوایین سڕینەوەکان و هێنانەوەکان سەرنجی [[Special:Log/delete|لۆگی سڕینەوە]] بدە.",
'undelete-header' => 'بۆ دیتنی ئەو لاپەڕانەی لەم داییانەدا سڕاونەتەوە چاو لە [[Special:Log/delete|لۆگی سڕینەوە]] بکە.',
'undelete-search-title' => 'گەڕان بۆ لاپەڕە سڕاوەکان',
'undelete-search-box' => 'گەڕان بۆ لاپەڕە سڕاوەکان',
'undelete-search-prefix' => 'نیشان‌دانی ئەو لاپەڕانەی دەستپێکیان ئەمەیە:',
'undelete-search-submit' => 'گەڕان',
'undelete-no-results' => 'لە ئەرشیڤی سڕاوەکانی لاپەڕەیەکی هاوتا نەدۆزرایەوە.',
'undelete-cleanup-error' => 'هەڵە لە سڕینەوەی ئەرشیڤی بەکەڵک نەهاتووی پەڕگە "$1".',
'undelete-missing-filearchive' => 'ناکرێ ئەرشیڤی پەڕگە بە پێناسەی $1 بهێنیتەوە لەبەر ئەوەی لە ناو بنکەی دراوە‌دا نییە.
لەوانەیە لە‌پێش‌دا هێنرابێتەوە.',
'undelete-error-short' => 'هەڵە لە گەڕاندنەوەی سڕینەوەی پەڕگە: $1',
'undelete-error-long' => 'هەڵەیەک لە کاتی گەڕاندنەوەی سڕینەوەی پەڕگە ڕووی‌دا:

$1',
'undelete-show-file-confirm' => 'ئایا ئەرخەیانی کە دەتەوێ پێداچوونەوە سراوەکەی پەڕگەی "<nowiki>$1</nowiki>" لە $2 لە $3 ببینی؟',
'undelete-show-file-submit' => 'بەڵێ',

# Namespace form on various pages
'namespace' => 'بۆشاییی ناو:',
'invert' => 'ھەڵبژاردەکان پێچەوانە بکە',
'namespace_association' => 'بۆشاییی ناوی پەیوەندیدار',
'blanknamespace' => '(سەرەکی)',

# Contributions
'contributions' => 'بەشدارییەکانی {{GENDER:$1|بەکارھێنەر}}',
'contributions-title' => 'بەشدارییەکانی بەکارھێنەر $1',
'mycontris' => 'بەشدارییەکان',
'contribsub2' => 'بۆ $1 ($2)',
'nocontribs' => 'هیچ گۆڕانکاریەکی هاوتای ئەم پێوەرانە نودۆزرایەوە',
'uctop' => '(ھەنووکە)',
'month' => 'لە مانگی (و پێشترەوە):',
'year' => 'لە ساڵی (و پێشترەوە):',

'sp-contributions-newbies' => 'تەنھا بەشدارییەکانی بەکارھێنەرە تازەکان نیشان بدە',
'sp-contributions-newbies-sub' => 'لە بەکارھێنەرە تازەکانەوە',
'sp-contributions-newbies-title' => 'هاوبەشیەکانی بەکارهێنەر بۆ هەژمارە نوێکان',
'sp-contributions-blocklog' => 'لۆگی بەربەستن',
'sp-contributions-deleted' => 'بەشدارییە سڕاوەکان',
'sp-contributions-uploads' => 'بارکردنەکان',
'sp-contributions-logs' => 'لۆگەکان',
'sp-contributions-talk' => 'لێدوان',
'sp-contributions-userrights' => 'بەڕێوبەرایەتی مافەکانی بەکارهێنەر',
'sp-contributions-blocked-notice' => 'ھەنووکە ئەم بەکارھێنەرە بەربەست کراوە.
دوایین بابەتی لۆگی بەربەستن لە ژێرەوە ھاتووە:',
'sp-contributions-blocked-notice-anon' => 'ھەنووکە ئەم ناونیشانەی IPیە بەربەست کراوە.
دوایین بابەتی لۆگی بەربەستن لە ژێرەوە ھاتووە:',
'sp-contributions-search' => 'گەڕان بۆ بەشدارییەکان',
'sp-contributions-username' => 'ناونیشانی ئایپی یان ناوی‌ بەکارھێنەر:',
'sp-contributions-toponly' => 'تەنیا ئەو دەستکارییانە نیشانبدە کە دوایین پیاچوونەوەن',
'sp-contributions-submit' => 'بگەڕێ',

# What links here
'whatlinkshere' => 'بەسراوەکان بە ئێرەوە',
'whatlinkshere-title' => 'ئەو پەڕانەی بەستەریان ھەیە بۆ «$1»',
'whatlinkshere-page' => 'پەڕە:',
'linkshere' => "ئەم پەڕانە بەستەریان ھەیە بۆ '''[[:$1]]''':",
'nolinkshere' => "هیچ لاپەڕەیەک بەستەری نەداوە بە '''[[:$1]]'''.",
'nolinkshere-ns' => "هیچ لاپەڕەیەک بەستەری نەداوە بە '''[[:$1]]''' لە بۆشایی‌ناوی هەڵبژێردراو.",
'isredirect' => 'پەڕەی ڕەوانەکەر',
'istemplate' => 'بەکارھێنراو',
'isimage' => 'بەستەری پەڕگە',
'whatlinkshere-prev' => '{{PLURAL:$1|پێشتر|$1ی پێشتر}}',
'whatlinkshere-next' => '{{PLURAL:$1|دیکە|$1ی تر}}',
'whatlinkshere-links' => '← بەستەرەکان',
'whatlinkshere-hideredirs' => 'ڕەوانەکەرەکان $1',
'whatlinkshere-hidetrans' => '$1 ھێنانەناوەوەکان',
'whatlinkshere-hidelinks' => '$1 بەستەر',
'whatlinkshere-hideimages' => '$1 بەستەرەکانی پەڕگە',
'whatlinkshere-filters' => 'پاڵێوکەکان',

# Block/unblock
'block' => 'بەربەستکردنی بەکارھێنەر',
'unblock' => 'لە بەربەست‌دەرهێنانی بەکارهێنەر',
'blockip' => 'بەربەستنی بەکارھێنەر',
'blockip-title' => 'بەربەستکردنی بەکارهێنەر',
'blockip-legend' => 'بەربەست‌کردنی بەکارهێنەر',
'blockiptext' => 'لەم فۆرمەی خوارەوە دەتوانی بۆ بەربەست‌کردنی دەست‌پێ‌گەیشتنی نووسین لە ناونیشانێکی ئای‌پی تایبەت یا ناوی بەکارهێنەریەک، کەڵک وەرگریت.
ئەمە تەنها دەبێ بۆ بەرگری لە خراپکاری بەکاربێت و ڕێکەوتنی هەبێ دەگەڵ [[{{MediaWiki:Policy-url}}|سیاسەتەکان]].
لە خوارەوە هۆکارێک بە ڕوونی بنووسە (بۆ نموونە بە وردی ئەو لاپەڕانە و خراپکاری تێدا کراوە وەک، وەک بەڵگە، بنووسە).',
'ipadressorusername' => 'ناونیشانی ئایپی یان ناوی‌ بەکارھێنەر:',
'ipbexpiry' => 'بەسەرچوون:',
'ipbreason' => 'هۆکار:',
'ipbreasonotherlist' => 'هۆکاری تر',
'ipbreason-dropdown' => '*ھۆکارە ھاوبەشەکانی بەربستن
**دانانی زانیاریی ھەڵە
**لابردنی ناوەرۆکی پەڕەکان
**بەستەر بۆ پەڕەی دەرەکی نەگونجاو
**نووسینی قسەی بێ‌مانا و بێ‌سوود
**ھەڵسوکەوت یان وتاری ھاندەر بۆ توندوتیژی
**بەکارھێنانی چەند ھەژمار پێکەوە
**ناوی بەکارھێنەریی نەگونجاو',
'ipb-hardblock' => 'بەرگری بەکارھێنەرانی تۆمارکراو بکە لە دەستکاریکردن لە ڕێگەی ناونیشانی ئەم IPیەوە',
'ipbcreateaccount' => 'بەرگری بکە لە دروستکردنی ھەژمار',
'ipbemailban' => 'بەرگری بکە لە ئیمەیل ناردنی بەکارھێنەر',
'ipbenableautoblock' => 'بە شێوەی خۆگەڕ دوایین ناونیشانی‌ ئای‌پی وا ئەم بەکار‌هێنەرە کەڵکی لێ‌وەرگرتووە و ئەو ئای‌پی‌یانەی تر وا لەوێوە هەوڵی دەستکاری دەدات بەربەست بکە',
'ipbsubmit' => 'بەربەستکردنی ئەم بەکارھێنەرە',
'ipbother' => 'کاتی‌ دیکە:',
'ipboptions' => '٢ کاتژمێر:2 hours,١ ڕۆژ:1 day,٣ ڕۆژ:3 days,١ ھەفتە:1 week,٢ ھەفتە:2 weeks,١ مانگ:1 month,٣ مانگ:3 months,٦ مانگ:6 months,١ ساڵ:1 year,بێ‌سنوور:infinite',
'ipbotheroption' => 'دیکە',
'ipbotherreason' => 'ھۆکاری تر/زیاتر:',
'ipbhidename' => 'شاردنەوەی ناوی‌ بەکارهێنەر لە دەستکاری و لیستەکان',
'ipbwatchuser' => 'پەڕەکانی بەکارھێنەر و لێدوانی ئەم بەکارهێنەرە بخە ژێر چاودێری',
'ipb-disableusertalk' => 'بەرگری ئەم بەکارھێنەرە بکە لە دستکاریکردنی پەڕەی لێدوانەکەی کاتێک بەربەست کراوە',
'ipb-change-block' => 'دیسان بەربەست‌کردنەوەی ئەم بەکارهێنەرە بەم هەڵبژاردانە',
'badipaddress' => 'ناونیشانی ئای‌پی نەگونجاو',
'blockipsuccesssub' => 'بەربەست کردن سەرکەوتوو بوو',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] بەربەست کرا.<br />
بڕوانە [[Special:BlockList|پێرستی بەربەستن]] بۆ بەسەرداچوونەوەی بەربەستنەکان.',
'ipb-edit-dropdown' => 'دەستکاری هۆکارەکانی بەربەست',
'ipb-unblock-addr' => 'لە بەربەست‌دەرهێنانی $1',
'ipb-unblock' => 'لە بەربەست‌دەرهێنانی ناوی بەکارهێنەریەک یا ناونیشانێکی ئای‌پی',
'ipb-blocklist' => 'دیتنی ئەو بەربەستانەی وا هەیە',
'ipb-blocklist-contribs' => 'بەشدارییەکانی $1',
'unblockip' => 'لە بەربەست‌دەرهێنانی بەکارهێنەر',
'unblockiptext' => 'بۆ گەڕاندنەوەی دەست‌پی‌گەیشتنی نووسین بۆ ئەو دوایین ئای‌پی یان بەکارهێنەری بەربەست کراوە، لەو فۆرمەی خوارەوە کەڵک وەرگرە.',
'ipusubmit' => 'لابردنی ئەم بەربەستە',
'unblocked' => '[[User:$1|$1]] لە بەربەست دەرهێنرا',
'unblocked-id' => 'بەربەستی $1 لابرا',
'blocklist' => 'بەکارھێنەر بەربەستکراوەکان',
'ipblocklist' => 'بەکارھێنەرە بەربەستکراوەکان',
'ipblocklist-legend' => 'دۆزینەوەی بەکارهێنەرێکی بەربەست‌کراو',
'blocklist-userblocks' => 'ھەژمارە بەربەستکراوەکان بشارەوە',
'blocklist-target' => 'مەبەست',
'blocklist-expiry' => 'ھەتا:',
'blocklist-by' => 'بەڕێوەبەری بەربەستکەر',
'blocklist-params' => 'پارامەترەکانی بەربەستن',
'blocklist-reason' => 'ھۆکار',
'ipblocklist-submit' => 'گەڕان',
'ipblocklist-localblock' => 'بەرەبەستنی خۆماڵی',
'ipblocklist-otherblocks' => '{{PLURAL:$1|بەربەستنەکانی}} تر',
'infiniteblock' => 'بێکۆتایی',
'expiringblock' => 'لە $2، $1 ماوەی بەسەر دەچێ',
'anononlyblock' => 'تەنیا بەکارھێنەرە بێناوەکان',
'noautoblockblock' => 'بەربەستنی خۆگەڕ ناچالاکە',
'createaccountblock' => 'دروستکردنی ھەژمار ناچالاکە',
'emailblock' => 'ئیمەیل ناچالاکە',
'blocklist-nousertalk' => 'دەستکاریکردنی پەڕەی وتووێژی خۆی ناچالاکە',
'ipblocklist-empty' => 'لیستی بەربەستەکان بەتاڵە',
'ipblocklist-no-results' => 'ئای‌پی ئەدرەس یان ناوی‌ بەکارهێنەری داواکراو بەربەست نەکراوە.',
'blocklink' => 'بەربەستن',
'unblocklink' => 'بەربەستن لابە',
'change-blocklink' => 'بەربەستن بگۆڕە',
'contribslink' => 'بەشدارییەکان',
'emaillink' => 'ناردنی ئیمەیل',
'autoblocker' => 'خۆکار بەربەست‌کراوە لەبەر ئەوەی ناونیشانی ئای‌پی تۆ لەم دواییانەدا لە لایەن "[[User:$1|$1]]" بەکار هاتووە.
هۆکاری بەربەست‌کرانی $1 ئەمەیە: "$2"',
'blocklogpage' => 'لۆگی بەربەستن',
'blocklog-showlog' => 'ئەم بەکارھێنەرە پێشتر بربەست کراوە.
لۆگی بەربەستن لە ژێرەوە ھاتووە:',
'blocklogentry' => '[[$1]]ی بۆ ماوەی $2 بەربەست کرد $3',
'reblock-logentry' => 'دۆخی بەربەستنی [[$1]]  گۆڕدرا بۆ ماوەی $2 $3',
'blocklogtext' => 'ئەمە لۆگێکی کردەوەکانی بەربەستن یان لابردنی بەربەستنی بەکارھێنەرە.
ئەو ئایپی ئەدرەسانە خۆکارانە بەربستکراون بە ڕیز نەکراون.
سەیری [[Special:BlockList|لیستی بەربەستن]] بکە بۆ بینینی ئەو بەرگری و بەربەستنانە ئێستا لە بەرکاردان.',
'unblocklogentry' => '$1ی کردەوە',
'block-log-flags-anononly' => 'تەنیا بەکارھێنەرە بێناوەکان',
'block-log-flags-nocreate' => 'دروستکردنی ھەژمار ناچالاک کرا',
'block-log-flags-noautoblock' => 'بەربەستنی خۆگەڕ ناچالاک کرا',
'block-log-flags-noemail' => 'ئیمەیل ناچالاک کرا',
'block-log-flags-nousertalk' => 'دەستکاریکردنی پەڕەی وتووێژی خۆی ناچالاک کرا',
'block-log-flags-angry-autoblock' => 'بەربەستکردنی خۆگەڕی پێشکەوتوو چالاک کرا',
'block-log-flags-hiddenname' => 'ناوی بەکارھێنەری شاراوە',
'range_block_disabled' => 'تایبەتمەندی بەڕێوەبەر بۆ بەربەست‌کردنی زنجیرە لە کارخستراوە.',
'ipb_expiry_invalid' => 'کاتی بەسەرچوونی نەگونجاو.',
'ipb_expiry_temp' => 'بەربەستی ناوی‌بەکارهێنەرە شاراوەکان دەبێ پایەدار بێت.',
'ipb_hide_invalid' => 'بەرگری لەم هەژمارە ناکرێت، لەوانەیە دەستکاری زۆری هەبێت.',
'ipb_already_blocked' => '"$1" لە پێش‌دا بەربەست‌‌کراوە',
'ipb-needreblock' => '"$1" لە پێش‌دا بەربەست‌‌کراوە.
ئایا دەتەو‌ێ هەڵبژاردەکانی بگۆڕیت؟',
'ipb-otherblocks-header' => '{{PLURAL:$1|بەربەستنەکانی}} تر',
'ipb_cant_unblock' => 'پێناسەی بەربەست‌کردنی $1 نەدۆزرایەوە.
لەوانەیە لە بەربەستی لابرابێت.',
'ipb_blocked_as_range' => 'هەڵە: ئای‌پی $1 ڕاستەوخۆ بەربەست نەکراوە بۆیە ناکڕێت لە بەربەست لای‌ بەیت.
ئەوە وەک بەشێک لە زنجیرە ئای‌پیی $2 بەربەست کراوە و هەر بەو شێوە دەکرێ لە بەربەست دەرچێ.',
'ip_range_invalid' => 'زنجیرە ئای‌پی نەگونجاو.',
'proxyblocker' => 'بەربەست‌کەری پرۆکسی',
'proxyblockreason' => 'ناونیشانی ئای‌پی تۆ بەربەست‌کراوە لەبەر ئەوەی پرۆکسیەکی کراوەیە.
تکایە پەیوەندی بکە بە دابینکەری خزمەتی ئینتەرنەتی خۆت یان پاڵپشتی تەکنیکی و ئاگادریان کەوە لەو کێشە ئەمنیە گرینگە.',
'sorbsreason' => 'ناونیشانی ئای‌پی تۆ لە DNSBLدا کە {{SITENAME}} کەڵکی لێ‌وەر دەگرێ، وەک پرۆکسیەکی کراوە لیست کراوە.',
'sorbs_create_account_reason' => 'ناونیشانی ئای‌پی تۆ لە DNSBLدا کە {{SITENAME}} کەڵکی لێ‌وەر دەگرێ، وەک پرۆکسیەکی کراوە لیست کراوە.
بۆیە ناتوانی هەژمارە درووست‌بکەی.',
'cant-block-while-blocked' => 'کاتێ خۆت بەربەست‌کراوی، ناتوانی بەکارهێنەرانی دیکە بەربەست بکەی.',
'ipbblocked' => 'ناتوانی بەکارھێنەرانی تر بەربەست بکەی یان بکەیەوە، چون خۆت بەربەست کراوی.',

# Developer tools
'lockdb' => 'داخستنی بنکەدراوە',
'unlockdb' => 'کردنەوەی بنکەدراوە',
'lockdbtext' => 'داخستنی بنکەدراوە ئەبێتە هۆی ڕاگرتنی توانای هەموو بەکارهێنەران بۆ دەستکاری لاپەڕەکان، گۆڕانی هەڵبژاردەکانیان، دەستکاری لیستی چاودێرییەکانیان و هەموو ئەموو ئەو شتانە وا پێویستی بە گۆرانکاری لە بنکەدراوە هەیە.
تکایە ئەرخەیان بە ئەمە هەر ئەوەیە کە دەتەوێ بیکەی و دوای چاکسازیەکەت لەیادت بێ کە بنکەدراوەکە بکەیتەوە.',
'unlockdbtext' => 'کردنەوەی بنکەدراوە ئەبێتە هۆی گەڕاندنەوەی توانای هەموو بەکارهێنەران بۆ دەستکاری لاپەڕەکان، گۆڕانی هەڵبژاردەکانیان، دەستکاری لیستی چاودێرییەکانیان و هەموو ئەموو ئەو شتانە وا پێویستی بە گۆرانکاری لە بنکەدراوە هەیە.
تکایە ئەرخەیان بە ئەمە هەر ئەوەیە کە دەتەوێ بیکەی.',
'lockconfirm' => 'بەڵێ، ئەرخەیانم دەمەوێ بنکەدراو داخەم.',
'unlockconfirm' => 'بەڵێ، ئەرخەیانم دەمەوێ بنکەدراو بکەمەوە.',
'lockbtn' => 'داخستنی بنکەدراو',
'unlockbtn' => 'کردنەوەی بنکەدراو',
'locknoconfirm' => 'چوارچێوەی ئەرخەیانیت نیشان‌ نەکرد.',
'lockdbsuccesssub' => 'داخستنی بنکەدراو بەسەرکەوتوویی جێبەجێ کرا',
'unlockdbsuccesssub' => 'بنکەدراو کرایەوە',
'lockdbsuccesstext' => 'بنکەدراو داخرا.<br />
لەیادت بێ دوای تەواوبوونی چاکسازی [[Special:UnlockDB|بنکەدراو بکەیتەوە]].',
'unlockdbsuccesstext' => 'بنکەدراو کرایەوە.',
'lockfilenotwritable' => 'پەڕگەی داخستنی بنکەدراو سەرنووس ناکرێت.
بۆ کردنەوە یا داخستنی بنکەدراو، پێویستە ڕاژەکار بتوانێ ئەو پەڕگە سەرنووس بکات.',
'databasenotlocked' => 'بنکەدراو دانەخراوە.',

# Move page
'move-page' => '$1 بگوازەوە',
'move-page-legend' => 'گواستنەوەی پەڕە',
'movepagetext' => "بەکارھێنانی ئەم فۆرمەی خوارەوە ناوی پەڕەیەک دەگۆڕێت، بە گواستنەوەی ھەموو مێژووەکەی بۆ ناوی نوێ.
ناوە کۆنەکە دەبێتە پەڕەیەکی ئاڕاستەکردنەوە بۆ ناوە نوێکە.
دەتوانی ئاڕاستەکان بۆ پەڕەی سەرەکی بەشێوەی خۆکار نوێ بکەیتەوە.
دڵنیا بە کە [[Special:DoubleRedirects|دووجار ڕەوانەکراوەکان]] یان [[Special:BrokenRedirects|ڕەوانەکراوە شکاوەکان]] تاقی بکەیتەوە.
تۆ بەرپرسیاری لەوەی کە دڵنیا ببیتەوە بەستەرەکان ھەر پێوەندییان ھەیە بەو شوێنە کە چاوەڕوان دەکرێت.

دەبێت بزانی کە پەڕەکە '''ناگوازرێتەوە''' ئەگەر پێشتر پەڕەیەک بە ناوە نوێکەوە ھەبێت، مەگەر ئەوەی کە پەڕەکە واڵا یان ڕەوانەکراوەیەک بێت و ھیچ مێژووی گۆڕاندنی پێشووی نەبێت.
ئەمە بەو واتایە کە ئەگەر ھەڵەیەک بکەی دەتوانی ناوی پەڕەکە دیسانەوە بگۆڕی بۆ ناوی پێشووی، و ناتوانی بیخەیتە جێگەی پەڕەیەک کە ھەنووکە ھەیە.

'''ھۆشیار بە!'''
ئەمە دەتوانێت گۆڕانێکی زۆر نابەجێ و چاوەڕێنەکراو بێت بۆ پەڕەیەکی بەناوبانگ؛
تکایە پێش گۆڕینی ناو باش بیر لە ئاکامەکەی بکەوە.",
'movepagetext-noredirectfixer' => "بەکارھێنانی ئەم فۆرمەی خوارەوە ناوی پەڕەیەک دەگۆڕێت، بە گواستنەوەی ھەموو مێژووەکەی بۆ ناوی نوێ.
ناوە کۆنەکە دەبێتە پەڕەیەکی ڕەوانەکردنەوە بۆ ناوە نوێکە.
دڵنیا بە کە [[Special:DoubleRedirects|دووجار ڕەوانەکراوەکان]] یان [[Special:BrokenRedirects|ڕەوانەکراوە شکاوەکان]] تاقی بکەیتەوە.
تۆ بەرپرسیاری لەوەی کە دڵنیا ببیتەوە بەستەرەکان ھەر پێوەندییان ھەیە بەو شوێنە کە چاوەڕوان دەکرێت.

دەبێت بزانی کە پەڕەکە '''ناگوازرێتەوە''' ئەگەر پێشتر پەڕەیەک بە ناوە نوێکەوە ھەبێت، مەگەر ئەوەی کە پەڕەکە واڵا یان ڕەوانەکراوەیەک بێت و ھیچ مێژووی گۆڕاندنی پێشووی نەبێت.
ئەمە بەو واتایە کە ئەگەر ھەڵەیەک بکەی دەتوانی ناوی پەڕەکە دیسانەوە بگۆڕی بۆ ناوی پێشووی، و ناتوانی بیخەیتە جێگەی پەڕەیەک کە ھەنووکە ھەیە.

'''ھۆشیار بە!'''
ئەمە دەتوانێت گۆڕانێکی زۆر نابەجێ و چاوەڕێنەکراو بێت بۆ پەڕەیەکی بەناوبانگ؛
تکایە پێش گۆڕینی ناو باش بیر لە ئاکامەکەی بکەوە.",
'movepagetalktext' => "پەڕەی وتووێژی پەیوەندیداری بە شێوەی خۆکار لەگەڵیدا دەگوازرێتەوە، '''مەگەر:'''
* پەڕەیەکی وتووێژی ناواڵا پێشتر ھەبێت لە ژێر ناوە نوێکەدا، یان
* ئەو چوارچێوەی خوارەوە لێنەدراو بکەی.

لەو حاڵەتەدا، ئەگەر بتەوێت بیگوازیتەوە ناچار دەبیت بە شێوەی دەستی بیگوازیتەوە یان تێکەڵیان بکەی.",
'movearticle' => 'ئەم پەڕەیە بگوازەوە:',
'movenologin' => 'نەچوویتەتە ژوورەوە',
'movenologintext' => 'بۆ گواستنەوەی پەڕەیەک، ئەشێ ببی بە ئەندام و [[Special:UserLogin|لە ژوورەوە]] بیت.',
'movenotallowed' => 'ڕێگەت پێ‌نەدراوە بۆ گواستنەوەی لاپەڕەکان.',
'movenotallowedfile' => 'ڕێگەت پێ‌نەدراوە بۆ گواستنەوەی پەڕگەکان.',
'cant-move-user-page' => 'ڕێگەت پێ‌نەدراوە بۆ گواستنەوەی لاپەڕەکانی بەکارهێنەر (جیاواز لە ژێرلاپەڕەکان).',
'cant-move-to-user-page' => 'ڕێگەت پێ‌نەدراوە بۆ گواستنەوەی لاپەڕەیەک بۆ لاپەڕەی بەکارهێنەر (غەیری بۆ ژێرلاپەڕەی بەکارهێنەر).',
'newtitle' => 'بۆ ناوی نوێی:',
'move-watch' => 'ئەم پەڕەیە بخە ژێر چاودێری',
'movepagebtn' => 'ئەم پەڕەیە بگوازەوە',
'pagemovedsub' => 'گواستنەوە بە سەرکەوتوویی جێبەجێ کرا',
'movepage-moved' => "'''«$1» گوازرایەوە بۆ «$2»'''",
'movepage-moved-redirect' => 'ڕەوانەکەرێک دروست کرا.',
'movepage-moved-noredirect' => 'لە دانانی ڕەوانەکەر بەرگری کرا.',
'articleexists' => 'پەڕەیەک بەم ناوە ھەیە یان ئەو ناوەی تۆ ھەڵتبژاردووە ڕێگەی پێنەدراوە.
تکایە ناوێکی دیکە ھەڵبژێرە.',
'cantmove-titleprotected' => 'ناتوانی لاپەڕەیەک بگوێزیتەوە بۆ ئەم شوێنە، لەبەر ئەوەی سەردێڕی نوێ لە درووست‌کردن پارێزراوە.',
'talkexists' => "'''خودی پەڕەکە بە سەرکەوتوویی گوازرایەوە، بەڵام پەڕەی وتووێژەکەی ناگوازرێتەوە چونکو پێشتر بە سەردێرە نوێکەوە، یەکێک ھەیە.
تکایە بە دەستی تێکەڵیان بکە.'''",
'movedto' => 'گواسترایەوە بۆ',
'movetalk' => 'پەڕەی وتووێژی پەیوەندیدار بگوازەوە',
'move-subpages' => 'ژێرپەڕەکانی بگوازەوە (ھەتا $1 پەڕە)',
'move-talk-subpages' => 'ژێرپەڕەکانی پەڕەی وتووێژ بگوازەوە (ھەتا $1 پەڕە)',
'movepage-page-exists' => 'پەڕەی $1 هەیە و ناتوانرێت خۆکار بخرێتە جێی.',
'movepage-page-moved' => 'پەڕەی $1 گۆزرایەوە بۆ $2.',
'movepage-page-unmoved' => 'ناکرێ پەڕەی $1 بگوێزرێتەوە بۆ $2.',
'movepage-max-pages' => 'زۆرینەی ژمارەی $1 {{PLURAL:$1|لاپەڕە|لاپەڕە}} گوێستراوەتەوە و لەوە زیاتر خۆکار ناگوێسترێتەوە.',
'movelogpage' => 'لۆگی گواستنەوە',
'movelogpagetext' => 'لە خوارەوەدا لیستی ھەموو پەڕە گواستنەوەکان دەبینن.',
'movesubpage' => '{{PLURAL:$1|ژێرپەڕە|ژێرپەڕە}}',
'movesubpagetext' => 'ئەم لاپەڕە $1 {{PLURAL:$1|ژێرلاپەڕەی‌|ژێرلاپەڕەی}} هەیە کە لەخوارە نیشان دراوە.',
'movenosubpage' => 'ئەم پەڕەیە ھیچ ژێرپەڕەیەکی نییە.',
'movereason' => 'ھۆکار:',
'revertmove' => 'پێچەوانەکردنەوە',
'delete_and_move' => 'بیسڕەوە و بیگوازەوە',
'delete_and_move_text' => '== پێویستییەکانی سڕینەوە ==
لاپەڕەی مەبەست "[[:$1]]" لە پێش‌دا هەیە.
ئایا دەتەوێ ئەوە بسڕیتەوە تا ڕێگە بۆ گواستنەوەی بکەیتەوە؟',
'delete_and_move_confirm' => 'بەڵێ، پەڕەکە بسڕەوه',
'delete_and_move_reason' => 'سڕایەوە بۆ کردنەوەی ڕیگە بۆ گواستنەوە لە «[[$1]]»ەوە',
'selfmove' => 'سەردێڕەکانی سەرچاوە و مەبەست یەکێکن؛
ناکرێ لاپەڕەیەک بۆ سەر خۆی‌ بگوازرێتەوە.',
'immobile-source-namespace' => 'گواستنەوەی لاپەڕە لە بۆشایی‌ناو "$1" ناکرێت.',
'immobile-target-namespace' => 'گواستنەوەی لاپەڕە بۆناو بۆشایی‌ناو "$1" ناکرێت.',
'immobile-target-namespace-iw' => 'بەستەرێکی نێوان‌ویکی ئامانجێکی گونجاو نیە بۆ گواستنەوەی لاپەڕە.',
'immobile-source-page' => 'ئەمە لاپەڕە بۆ گواستنەوە نابێت.',
'immobile-target-page' => 'بۆ ئەم سەردێڕی ئامانجە جێگۆڕ ناکرێ.',
'imagenocrossnamespace' => 'گواستنەوەی پەڕگە بۆ بۆشایی‌نوێکی غەیری پەڕگە گونجاو نیە.',
'nonfile-cannot-move-to-file' => 'گواستنەوەی پەڕگە بۆ بۆشایی‌نوێکی غەیری پەڕگە گونجاو نیە.',
'imagetypemismatch' => 'پاشگری ئەو پەڕگە نوێیە هاوتای جۆری پەڕگەکە نیە.',
'imageinvalidfilename' => 'ناوی پەڕگەی ئامانج گونجاو نیە',
'fix-double-redirects' => 'نوێ‌کەردنەوەی هەموو ڕەوانکەرەکان وا ئاماژە بە سەردێڕە سەرەکیەکە دەکەن',
'move-leave-redirect' => 'لە پاشەوە ڕەوانەکەرێک بھێڵەوە',
'protectedpagemovewarning' => "'''ھۆشیار بە: ئەم پەڕە پارێزراوە بۆ ئەوی تەنیا ئەو بەکارھێنەرانە کە مافەکانی بەڕێوەبەرایەتییان ھەیە بتوانن بیگوازنەوە.'''
دوایین لۆگ بۆ ژێدەر لە خوارەوەدا ھاتووە:",
'semiprotectedpagemovewarning' => "'''ئاگاداری:''' ئەم پەڕە پارێزراوە بۆ ئەوی تەنھا بەکارھێنەرە تۆمارکراوەکان بتوانن بیگوازنەوە.
دوایین لۆگ بۆ ژێدەر لە خوارەوەدا ھاتووە:",

# Export
'export' => 'ھەناردنی پەڕەکان',
'exporttext' => 'دەتوانی دەق و مێژووی دەستکاری لاپەڕەیەکی تایبەت یان دەستە لاپەڕەیەک بۆ ناو پەڕگەیەکی XML هەناردن بکەیت.
دواتر بە کەڵک‌وەرگرتن لە [[Special:Import|لاپەڕەی هێنانەناوە]] لە مێدیاویکی‌دا، دەتوانی بیهێنیتە ناو ویکی‌یەکانی دیکە.

بۆ هەناردنی لاپەڕەکان، سەردێڕەکان لە چوارچێوەی دەقی خوارەوە بنووسە، هەر هێڵێک یەک سەردێڕ. هەروا هەڵبژێرە ئایا پێداچوونەوەی ئێستا و هەموو پێداچوونەوە کۆنەکانت دەوێ یان هەر پێداچوونەوەی ئێستا و زانیاریی سەبارەت بە دوایین دەستکاری.

لە بابەتی دواتر هەروەها دەتوانی لە بەستەرێک کەڵک وەرگریت، بۆ نموونە [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] بۆ لەپەڕەی "[[{{MediaWiki:Mainpage}}]]".',
'exportall' => 'ھەموو پەڕەکان ھەناردە بکە',
'exportcuronly' => 'تەنها پێداچوونەوەی ئێستا لەخۆ بگرێت نەک هەموو مێژوو',
'exportnohistory' => "----
'''ئاگاداربە: '''ھەناردنی ھەموو مێژووی پەڕەکان لەم فۆرمەوە لەبەر ھۆکاری ڕێخستن، داخراوە.",
'export-submit' => 'هەناردن',
'export-addcattext' => 'پەڕەکان زێدەبکە لە پۆلی:',
'export-addcat' => 'زیاد بکە',
'export-addnstext' => 'پەڕەکان زێدەبکە لە بۆشایی‌ناوی:',
'export-addns' => 'زێدەبکە',
'export-download' => 'وەک پەڕگە پاشەکەوتی بکە',
'export-templates' => 'داڕێژەکانیش لە خۆگرێت',
'export-pagelinks' => 'لەخۆگرتنی لاپەڕەکانی بەستەر پێ‌دراو هەتا قووڵایی:',

# Namespace 8 related
'allmessages' => 'پەیامەکانی سیستەم',
'allmessagesname' => 'ناو',
'allmessagesdefault' => 'دەقی بنەڕەتی',
'allmessagescurrent' => 'دەقی ھەنووکە',
'allmessagestext' => 'ئەمە لیستێکە لە پەیامەکانی بەردەست لە بۆشایی‌ناوی میدیاویکی.
تکایە سەردانی [//www.mediawiki.org/wiki/Localisation ناوچەیی‌کردنی میدیاویکی] و [//translatewiki.net translatewiki.net] بکە ئەگەر دەتەوێ لە ناوچەیی‌کردنی میدیاویکی بە گشتی بەشداری بکەیت.',
'allmessagesnotsupportedDB' => "ئەم لاپەڕە ناتوانی بەکاربێت لەبەر ئەوەی '''\$wgUseDatabaseMessages''' لەکار خستراوە.",
'allmessages-filter-legend' => 'پاڵێو',
'allmessages-filter-unmodified' => 'نەگۆڕدراو',
'allmessages-filter-all' => 'هەموو',
'allmessages-filter-modified' => 'گۆڕدراو',
'allmessages-prefix' => 'پاڵێو بە پێشگر:',
'allmessages-language' => 'زمان:',
'allmessages-filter-submit' => 'بڕۆ',

# Thumbnails
'thumbnail-more' => 'گەورە کردنەوە',
'filemissing' => 'ون‌بوونی پەڕگە',
'thumbnail_error' => 'هەڵە کاتی درووست‌کردنی هێما: $1',
'djvu_page_error' => 'لاپەڕەی DjVu لەدەرۆی ڕیز',
'djvu_no_xml' => 'XML بۆ پەڕگەی DjVu ناکێشرێتەوە',
'thumbnail_invalid_params' => 'دیاریکەری نەگونجاوی هێما',
'thumbnail_dest_directory' => 'پێرستی مەبەست درووست‌ناکرێت',
'thumbnail_image-type' => 'جۆرەی وێنە پاڵپشت نەکراوە',
'thumbnail_gd-library' => 'شێوەپێدانی‌ ناتەواوی ژێدەرگەی GD: ون‌بوونی فەنکشێن $1',
'thumbnail_image-missing' => 'وا دیارە پەڕگە بزر بووبێت: $1',

# Special:Import
'import' => 'ھاوردنی پەڕەکان',
'importinterwiki' => 'ھاوردنی ناووویکی',
'import-interwiki-text' => 'بۆ ھاوردن، ویکییەک و سەردێڕێکی پەڕە ھەڵبژێرە.
ڕێکەوتەکانی پێداچوونەوە و ناوی دەستکاریکەرەکان دەپارێزرێت.
هەموو کردەوەکانی ھاوردنی ناوویکی لە [[Special:Log/import|لۆگی ھاوردن]]دا تۆمار دەکرێت.',
'import-interwiki-source' => 'ویکی/پەڕەی سەرچاوە:',
'import-interwiki-history' => 'هەموو مێژووی پێداچوونەوەکانی ئەم پەڕەیە کۆپی بکە',
'import-interwiki-templates' => 'ھەموو داڕێژەکان لەخۆبگرێتەوە',
'import-interwiki-submit' => 'هاوردە بکە',
'import-interwiki-namespace' => 'بۆشاییی ناوی مەبەست:',
'import-interwiki-rootpage' => 'پەڕەی بنەڕەتیی مەبەست (دڵخوازانە):',
'import-upload-filename' => 'ناوی پەڕگە‌:',
'import-comment' => 'بۆچوون:',
'importtext' => 'تکایە پەڕگەکە لە ویکی سەرچاوەوە بە کەڵک وەرگرتن لە [[Special:Export|ئامێری ھەناردن]] ھەناردە بکە.
لەسەر کۆمپیۆتەرەکەت پاشەکەوتی بکە و لێرە باری بكە.',
'importstart' => 'ھاوردنی پەڕەکان...',
'import-revision-count' => '$1 {{PLURAL:$1|پێداچوونەوە}}',
'importnopages' => 'ھیچ پەڕەیەک بۆ ھاوردن نییە.',
'imported-log-entries' => '$1 {{PLURAL:$1|بابەتی لۆگ}} ھاوردە کرا.',
'importfailed' => 'ھاوردن سەرکەوتوو نەبوو: <nowiki>$1</nowiki>',
'importunknownsource' => 'جۆری سەرچاوەی هاوردن نەناسراوە',
'importcantopen' => 'پەڕگەی ھاوردن ناکرێتەوە',
'importbadinterwiki' => 'بەستەری نێوانویکیی خراپ',
'importnotext' => 'واڵا یان بێ‌دەق',
'importsuccess' => 'ھاوردن تەواو بوو!',
'importhistoryconflict' => 'کێشە لەو مێژووی پێداچوونەوانە وا هەیە (لەوانەیە ئەم پەڕەیە پێشتر ھاوردە کرابێ)',
'importnosources' => 'ھیچ سەرچاوەیەکی ھاوردنی ناوویکی دیاری نەکراوە و بارکردنی ڕاستەوخۆی مێژوو ناچالاکە.',
'importnofile' => 'ھیچ پەڕگەیەکی ھاوردن بار نەکراوە.',
'importuploaderrorsize' => 'بارکردنی پەڕگەی ھاوردن سەرکەوتوو نەبوو.
پەڕگەکە لەو قەبارەیەی بۆ بارکردن ڕێگەدراوە گەورەترە.',
'importuploaderrorpartial' => 'بارکردنی پەڕگەی ھاوردن سەرکەوتوو نەبوو.
تەنیا بەشێک لە پەڕگەکە بار کرا.',
'importuploaderrortemp' => 'بارکردنی پەڕگەی ھاوردن سەرکەوتوو نەبوو.
بوخچەیەکی کاتی بزر بووە.',
'import-parse-failure' => 'سەرنەکەوتن لە شیکردنەوەی ھاوردنی XML',
'import-noarticle' => 'ھیچ پەڕەیەک بۆ ھاوردن نییە!',
'import-nonewrevisions' => 'ھەموو پێداچوونەوەکان پێشتر ھاوردە کراون.',
'xml-error-string' => '$1 لە دێڕی $2، ستوونی $3 (بایت $4): $5',
'import-upload' => 'بارکردنی دراوەی XML',
'import-token-mismatch' => 'لەدەستدانی دراوەکانی کۆڕ.
تکایە دیسان تاقی بکەوە.',
'import-invalid-interwiki' => 'لە ویکی‌ دیاریکراو ھاوردن ناکرێ.',
'import-error-edit' => 'پەڕەی «$1» ھاوردە ناکرێ، چون ناتوانی ئەم پەڕەیە دەستکاری بکەی.',
'import-error-create' => 'پەڕەی «$1» ھاوردە ناکرێ، چون ناتوانی ئەم پەڕەیە دروست بکەی.',
'import-error-interwiki' => 'پەڕەی «$1» ھاوردە ناکرێ چون ناوەکەی بۆ بەستەری دەرەکیی (interwiki) گیراوەتەوە.',
'import-error-special' => 'پەڕەی «$1» ھاوردە ناکرێ چون لە بۆشاییی ناوی نەگونجاودایە.',
'import-error-invalid' => 'پەڕەی «$1» ھاوردە ناکرێ چون ناوەکەی نادروستە.',

# Import log
'importlogpage' => 'لۆگی ھاوردن',
'importlogpagetext' => 'ھاوردنی پەڕەکان لەگەڵ مێژووی دەستکاری لە ویکییەکانی ترەوە.',
'import-logentry-upload' => '[[$1]]ی بە بارکردنی پەڕگە ھاورد',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|پێداچوونەوە}}',
'import-logentry-interwiki' => '$1ی ناوویکی کرد',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|پێداچوونەوە}} لە $2',

# JavaScriptTest
'javascripttest' => 'تاقیکردنەوەی جاڤاسکریپت',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'پەڕەی بەکارھێنەرییەکەت',
'tooltip-pt-anonuserpage' => 'پەڕەی بەکارھێنەری بۆ ئای‌پی یەکە کە بەناویەوە خەریکی دەستکاری کردنی',
'tooltip-pt-mytalk' => 'پەڕەی لێدوانەکەت',
'tooltip-pt-anontalk' => 'لێدوان لەسەر دەستکارییەکان لەم ئایپی ئەدرەسەوە',
'tooltip-pt-preferences' => 'هەڵبژاردەکانت',
'tooltip-pt-watchlist' => 'پێرستی ئەو پەڕانە کە چاودێریی گۆڕانکارییەکانیانی دەکەی',
'tooltip-pt-mycontris' => 'پێرستی بەشدارییەکانت',
'tooltip-pt-login' => 'پێشنیارت پێدەکرێ بچیتە ژوورەوە؛ ھەرچەندە زۆرت لێناکرێ',
'tooltip-pt-anonlogin' => 'پێشنیار دەکەین بڕۆیتەژوورەوە، ئەگەرچی ئەوە زۆرەملیی نیە',
'tooltip-pt-logout' => 'دەرچوون',
'tooltip-ca-talk' => 'لێدوان دەربارەی ناوەڕۆکی پەرە',
'tooltip-ca-edit' => 'دەتوانی دەستکاریی ئەم پەڕەیە بکەیت. تکایە پێش پاشەکەوتکردن دوگمەی پێشبینین بەکاربێنە.',
'tooltip-ca-addsection' => 'بەشێکی نوێ دەست پێ بکە',
'tooltip-ca-viewsource' => 'ئەم پەڕەیە پارێزراوە.
ئەتوانی سەرچاوەکەی ببینیت',
'tooltip-ca-history' => 'وەشانەکانی پێشووی ئەم پەڕەیە',
'tooltip-ca-protect' => 'ئەم پەڕەیە بپارێزە',
'tooltip-ca-unprotect' => 'پاراستنی ئەم پەڕەیە بگۆڕە',
'tooltip-ca-delete' => 'ئەم پەڕەیە بسڕەوە',
'tooltip-ca-undelete' => 'هێنانەوەی دەستکاریەکانی پیش سڕینەوە وا لەسەر ئەم لاپەڕە ڕووی‌داوە',
'tooltip-ca-move' => 'ئەم پەڕەیە بگوازەوە',
'tooltip-ca-watch' => 'ئەم پەڕە بخە سەر لیستی چاودێریت',
'tooltip-ca-unwatch' => 'ئەم پەڕە لە لیستی چاودێریت لابە',
'tooltip-search' => 'لە {{SITENAME}} بگەڕێ',
'tooltip-search-go' => 'بڕۆ بۆ پەڕەیەک کە بە تەواوی ئەم ناوەی ھەیە ئەگەر بببێت',
'tooltip-search-fulltext' => 'لە پەڕەکاندا بگەڕێ بۆ ئەم دەقە',
'tooltip-p-logo' => 'بینینی پەڕەی دەستپێک',
'tooltip-n-mainpage' => 'سەردانی پەڕەی سەرەکی بکە',
'tooltip-n-mainpage-description' => 'سەردانی پەڕەی سەرەکی بکە',
'tooltip-n-portal' => 'سەبارەت بە پڕۆژەکە، چی دەتوانی بکەیت، لە کوێ شتەکان بدۆزیتەوە',
'tooltip-n-currentevents' => 'زانیاری پێشینە بەدەست بھێنە دەربارەی بۆنە ھەنووکەییەکان',
'tooltip-n-recentchanges' => 'لیستی دوایین گۆڕانکارییەکان لەم ویکییەدا',
'tooltip-n-randompage' => 'پەڕەیەک بە هەڵکەوت نیشان بدە',
'tooltip-n-help' => 'شوێنی تێگەیشتن',
'tooltip-t-whatlinkshere' => 'پێرستی ھەموو پەڕەکانی ویکی کە بەستەر دراون بۆ ئێرە',
'tooltip-t-recentchangeslinked' => 'دوایین گۆڕانکارییەکان لەو پەڕانە کە بەگرەوە گرێ دراون',
'tooltip-feed-rss' => 'RSS feed بۆ ئەم پەڕە',
'tooltip-feed-atom' => 'Atom feed بۆ ئەم پەڕە',
'tooltip-t-contributions' => 'لیستی بەشدارییەکانی ئەم بەکارھێنەرە ببینە',
'tooltip-t-emailuser' => 'ئیمەیلێک بنێرە بۆ ئەم بەکارھێنەرە',
'tooltip-t-upload' => 'پەڕگە بار بکە',
'tooltip-t-specialpages' => 'پێرستی ھەموو پەڕە تایبەتەکان',
'tooltip-t-print' => 'وەشانی چاپی ئەم پەڕەیە',
'tooltip-t-permalink' => 'گرێدەری ھەمیشەیی بۆ ئەم وەشانەی ئەم پەڕەیە',
'tooltip-ca-nstab-main' => 'بینینی پەڕەی ناوەڕۆک',
'tooltip-ca-nstab-user' => 'پەڕەی بەکارھێنەر تەماشا بکە',
'tooltip-ca-nstab-media' => 'پەڕەی میدیا چاو لێ بکە',
'tooltip-ca-nstab-special' => 'ئەمە پەڕەیەکی تایبەتە، ناتوانی خودی ئەم پەڕە دەستکاری بکەیت',
'tooltip-ca-nstab-project' => 'بینینی پەڕەی پرۆژە',
'tooltip-ca-nstab-image' => 'بینینی پەڕەی پەڕگە',
'tooltip-ca-nstab-mediawiki' => 'بینینی پەیامی سیستەم',
'tooltip-ca-nstab-template' => 'بینینی قاڵبەکە',
'tooltip-ca-nstab-help' => 'بینینی پەڕەی رێنمایی',
'tooltip-ca-nstab-category' => 'پەڕەی پۆلەکە ببینە',
'tooltip-minoredit' => 'ئەمە وەک گۆڕانکارییەکی بچووک دیاری بکە',
'tooltip-save' => 'گۆڕانکارییەکانی خۆت پاشکەوت بکە',
'tooltip-preview' => 'پێش بینینی گۆڕانکارییەکان، تکایە پێش پاشکەوت کردن ئەمە بەکار بھێنە',
'tooltip-diff' => 'نیشان دانی گۆڕانکارییەکانت لە دەقەکەدا',
'tooltip-compareselectedversions' => 'جیاوازییەکانی دوو وەشانە دیاریکراوەی ئەم پەڕە ببینە.',
'tooltip-watch' => 'ئەم پەڕە بخە سەر لیستی چاودێریت',
'tooltip-watchlistedit-normal-submit' => 'ناونیشانەکان لاببە',
'tooltip-watchlistedit-raw-submit' => 'نوێکردنەوەی لیستی چاودێری',
'tooltip-recreate' => 'درووست‌کردنەوەی لاپەڕە ئەگەرچی سڕاوەتەوە',
'tooltip-upload' => 'دەستپێکردنی بارکردن',
'tooltip-rollback' => '«گەڕاندنەوە» بە یەک کرتە گۆڕانکاریی/گۆڕانکارییەکانی ئەم پەڕەیە دەگەڕێنێتەوە بۆ دوایین بەشداربوو',
'tooltip-undo' => '«پووچەڵکردنەوە» ئەم گۆڕانکارییە دەگەڕێنێتەوە و فۆرمی دەستکاریکردن لە شێوەی پێشبینیندا دەکاتەوە. بەم جۆرە دەکرێ ھۆکارێک لە کورتەی دەستکاریدا بنووسرێ.',
'tooltip-preferences-save' => 'هەڵبژاردنەکانت بپارێزە',
'tooltip-summary' => 'پوختەیەکی کورتی تێبخە',

# Metadata
'notacceptable' => 'ڕاژەکاری ویکی ناتوانێت داتا بەوشێوەی بۆ ڕاژەخوازی تۆ بخوێندرێتەوە، ئامادە بکات.',

# Attribution
'anonymous' => '{{PLURAL:$1|بەکارهێنەری|بەکارهێنەرانی}} نەناسراوی {{SITENAME}}',
'siteuser' => 'بەکارھێنەری {{SITENAME}}، $1',
'anonuser' => '$1، بەکارھێنەری نامۆی {{SITENAME}}',
'lastmodifiedatby' => 'ئەم پەڕە دواجار لە $2ی $1 بە دەستی $3 گۆڕدراوە.',
'othercontribs' => 'لەسەر بنەمای کاری $1.',
'others' => 'ئەوانی دیکە',
'siteusers' => '{{PLURAL:$2|بەکارهێنەری|بەکارهێنەرانی}} {{SITENAME}} $1',
'anonusers' => '{{PLURAL:$2|بەکارھێنەر|بەکارھێنەر}}ی نامۆی {{SITENAME}} $1',
'creditspage' => 'بایەخەکانی لاپەڕە',
'nocredits' => 'هیچ زانیارییەکی بایەخ لەبەردەست‌دا نیە بۆ ئەم لاپەڕە.',

# Spam protection
'spamprotectiontitle' => 'پاڵێوی پاراستن لە سپام',
'spamprotectiontext' => 'ئەو لاپەڕەی دەتویست پاشەکەوتی بکەی، بە پاڵێوی سپام بەربەست‌کرا
لەوانەیە هۆکاری ئەوە بەستەرەک بووە بۆ ماڵپەڕەکی دەرەکی کە لەناو ڕەش‌لیست‌دایە.',
'spamprotectionmatch' => 'ئەم دەقە ئەوەیە کە پاڵێوی سپامەکە دەبزوێنێ: $1',
'spambot_username' => 'خاوێنکردنەوەی سپامی میدیاویکی',
'spam_reverting' => 'گەڕانەوە بۆ دوایین پێداچوونەوە کە بەستەری لەخۆگرتووە بۆ $1',

# Info page
'pageinfo-title' => 'زانیاری بۆ «$1»',
'pageinfo-header-basic' => 'زانیاریی سەرەتایی',
'pageinfo-header-edits' => 'مێژووی دەستکاری',
'pageinfo-header-restrictions' => 'پاراستنی پەڕە',
'pageinfo-header-properties' => 'تایبەتمەندییەکانی پەڕە',
'pageinfo-display-title' => 'ناونیشان نیشانبدە',
'pageinfo-default-sort' => 'کلیلی ڕیزکردنی بەرگریمانە',
'pageinfo-length' => 'قەبارەی پەڕە (بایت)',
'pageinfo-article-id' => 'زنجیرەی پەڕە',
'pageinfo-language' => 'زمانی ناوەرۆکی پەڕە',
'pageinfo-robot-policy' => 'چۆنێتیی مۆتۆڕی گەڕان',
'pageinfo-robot-index' => 'شیاو بۆ پێرستکردن',
'pageinfo-robot-noindex' => 'نەشیاو بۆ پێرستکردن',
'pageinfo-views' => 'ژمارەی بینینەکان',
'pageinfo-watchers' => 'ژمارەی چاودێرانی پەڕە',
'pageinfo-few-watchers' => 'کەمتر لە $1 {{PLURAL:$1|چاودێر}}',
'pageinfo-redirects-name' => 'ڕەوانەکەرەکان بۆ ئەم پەڕەیە',
'pageinfo-subpages-name' => 'ژێرپەڕەکانی ئەم پەڕەیە',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|ڕەوانەکەر}}; $3 {{PLURAL:$3|ڕەوانەنەکەر}})',
'pageinfo-firstuser' => 'دروستکەری پەڕە',
'pageinfo-firsttime' => 'ڕێکەوتی دروستکردنی پەڕە',
'pageinfo-lastuser' => 'دوایین دەستکاریکەر',
'pageinfo-lasttime' => 'ڕێکەوتی دوایین دەستکاری',
'pageinfo-edits' => 'ژمارەی سەرجەمی دەستکارییەکان',
'pageinfo-authors' => 'ژمارەی نووسەرە جیاوازەکان',
'pageinfo-recent-edits' => 'ژمارەی دوایین دەستکارییەکان (لە $1ی ڕابردوودا)',
'pageinfo-recent-authors' => 'ژمارەی دوایین نووسەرە جیاوازەکان',
'pageinfo-templates' => 'داڕێژە{{PLURAL:$1|ی بەکارگیراو| بەکارگیراوەکان}} ($1)',
'pageinfo-toolboxlink' => 'زانیاریی پەڕە',
'pageinfo-redirectsto-info' => 'زانیاری',
'pageinfo-contentpage' => 'ھەژمارکراو وەک پەڕەی بەناوەرۆک',
'pageinfo-contentpage-yes' => 'بەڵێ',
'pageinfo-protect-cascading-yes' => 'بەڵێ',
'pageinfo-category-info' => 'زانیاریی پۆل',
'pageinfo-category-pages' => 'ژمارەی پەڕەکان',
'pageinfo-category-subcats' => 'ژمارەی ژێرپەڕەکان',
'pageinfo-category-files' => 'ژمارەی پەڕگەکان',

# Skin names
'skinname-cologneblue' => 'شینی کۆلۆن',
'skinname-monobook' => 'مۆنۆ',
'skinname-modern' => 'مۆدێڕن',
'skinname-vector' => 'ڤێکتۆر',

# Patrolling
'markaspatrolleddiff' => 'وەک پاس دراو نیشان بکە',
'markaspatrolledtext' => 'ئەم پەڕەیە وەک پاس دراو نیشان بکە',
'markedaspatrolled' => 'وەک پاس دراو نیشان کرا',
'markedaspatrolledtext' => 'پێداچوونەوەی هەڵبژێردراوی [[:$1]] وەک پاس دراو نیشان کرا.',
'rcpatroldisabled' => 'پاسدەریی دوایین گۆڕانکاریەکان ناچالاک کرا',
'rcpatroldisabledtext' => 'تایبەتمەندیی پاسدەریی دوایین گۆڕانکارییەکان ئێستا ناچالاک کراوە.',
'markedaspatrollederror' => 'وه‌ک پاس دراو نیشان نەکرا',
'markedaspatrollederrortext' => 'دەبێ پێداچوونەوەیەک دەستنیشان بکەی ھەتا وەک پاس دراو نیشان بکرێ.',
'markedaspatrollederror-noautopatrol' => 'ناتوانی گۆڕانکارییەکانی خۆت وەک پاس دراو نیشان بکەی.',
'markedaspatrollednotify' => 'ئەم گۆڕانکارییە لەسەر $1 وەک پاس دراو نیشان کرا.',
'markedaspatrollederrornotify' => 'نیشانکردن وەک پاس دراو سەرکەوتوو نەبوو.',

# Patrol log
'patrol-log-page' => 'لۆگی پاسدەری',
'patrol-log-header' => 'ئەمە لۆگێکی پێداچوونەوە پاس دراوەکانە.',
'log-show-hide-patrol' => 'لۆگی پاسدەری $1',

# Image deletion
'deletedrevision' => 'پێداچوونەوەی کۆنی سڕاوە $1',
'filedeleteerror-short' => 'هەڵە لە سڕینەوەی پەڕگە: $1',
'filedeleteerror-long' => 'کاتی سڕینەوەی ئەم پەڕگەی ڕووبەڕووی کێشە بووینەوە:

$1',
'filedelete-missing' => 'فایلی "$1"  ناتوانرێت بسردرێته‌وه‌ ،له‌به‌ر ئه‌وه‌ی بونی نیه‌',

# Browsing diffs
'previousdiff' => '→ گۆڕانکاریی کۆنتر',
'nextdiff' => 'گۆڕانکاریی نوێتر ←',

# Media information
'imagemaxsize' => "سنووری قەبارەی وێنە:<br />''(بۆ پەڕەکانی وەسفی پەڕگە)''",
'thumbsize' => 'قەبارەی وێنۆک:',
'widthheight' => '$1 لە $2',
'widthheightpage' => '$1 × $2، $3 {{PLURAL:$3|پەڕە|پەڕە}}',
'file-info' => 'قه‌باره‌: $1, جۆر: $2',
'file-info-size' => '$1 × $2 پیکسێل، قەبارەی پەڕگە: $3، جۆری MIME: $4',
'file-nohires' => 'رەزۆلوشنی سەرتر لەمە لە بەردەست دا نیە.',
'svg-long-desc' => 'پەڕگەی SVG، بە ناو $1 × $2 پیکسەڵ، قەبارەی پەڕگە: $3',
'svg-long-error' => 'پەڕگەی SVGی نادروست: $1',
'show-big-image' => 'گەورەکردنەوە',
'show-big-image-preview' => 'قەبارەی ئەم پێشبینینە: $1.',
'show-big-image-other' => '{{PLURAL:$2|ڕەزەلووشنی|ڕەزەلووشنەکانی}} تر: $1.',
'show-big-image-size' => '$1 لە $2 پیکسەڵ',

# Special:NewFiles
'newimages' => 'پێشانگای پەڕگە نوێکان',
'imagelisttext' => "خوارەوە لیستێکیی '''$1''' {{PLURAL:$1|پەڕگە|پەڕگە}}یە کە $2 بەڕیزکراون.",
'newimages-summary' => 'ئەم پەڕە تایبەتە دوایین پەڕگە بارکراوەکان نیشان دەدات.',
'newimages-legend' => 'پاڵاوتن',
'newimages-label' => 'ناوی پەڕگە (یان بەشێکیی):',
'showhidebots' => '(بۆتەکان $1)',
'noimages' => 'هیچ بۆ بینین نییە.',
'ilsubmit' => 'بگەڕێ',
'bydate' => 'بەپێی ڕێکەوت',
'sp-newimages-showfrom' => 'پەڕگە نوێکان نیشان بدە بە دەستپێکردن لە $2ی $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1، $2 لە $3',
'seconds-abbrev' => '$1چ',
'minutes-abbrev' => '$1خ',
'hours-abbrev' => '$1ک',
'days-abbrev' => '$1ڕ',
'seconds' => '{{PLURAL:$1|$1 چرکە}}',
'minutes' => '{{PLURAL:$1|$1 خولەک}}',
'hours' => '{{PLURAL:$1|$1 کاتژمێر}}',
'days' => '{{PLURAL:$1|$1 ڕۆژ}}',
'weeks' => '{{PLURAL:$1|$1 حەفتە}}',
'months' => '{{PLURAL:$1|$1 مانگ}}',
'years' => '{{PLURAL: $1|$1 ساڵ}}',
'ago' => '$1 لەمە پێش',
'just-now' => 'ھەرئێستا',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|کاتژمێر}} لەمه پێش',
'minutes-ago' => '$1 {{PLURAL:$1|خولەک}} لەمە پێش',
'seconds-ago' => '$1 {{PLURAL:$1|چرکە}} لەمە پێش',
'monday-at' => 'دووشەممە $1',
'tuesday-at' => 'سێشەممە $1',
'wednesday-at' => 'چوارشەممە $1',
'thursday-at' => 'پێنجشەممە $1',
'friday-at' => 'ھەینی $1',
'saturday-at' => 'شەممە $1',
'sunday-at' => 'یەکشەممە $1',
'yesterday-at' => 'دوێنێ $1',

# Bad image list
'bad_image_list' => 'فۆرمات بەم شێوەی خوارەوەیە:

تەنھا ئەو بابەتانەی کە کە لیست کراون (واتە ئەو دێڕانەی بە * دەست پێ دەکەن) لێک ئەدرێتەوە.
یەکەم بەستەر لە سەر دێڕێک دەبێت بەستەری فایلێکی خراپ بێت.
ھەموو بەستەرەکانی دوای ئەو کە لەسەر ھەمان دێڕن وەکوو نائاسایی دێتە ھەژمار، واتە ئەو لاپەڕانەی کە ڕەنگە تێدا فایل بە شێوەی ئینلاین بێت',

# Variants for Kurdish language
'variantname-ku-arab' => 'ئەلفوبێی عەرەبی',
'variantname-ku-latn' => 'ئەلفوبێی لاتینی',

# Metadata
'metadata' => 'دراوی مێتا',
'metadata-help' => 'ئەم پەڕگە زانیاری زێدەی ھەیە، کە لەوە دەچێت کامێرا یان ھێماگر (scanner) خستبێتیە سەری. ئەگەر پەڕگەکە لە حاڵەتی سەرەتاییەکەیەوە دەستکاری کرابێ، شایەد بڕێ لە بڕگەکان بە تەواوی زانیارەکانی وێنە گۆڕدراوەکە نیشان نەدەن.',
'metadata-expand' => 'وردەکارییە درێژکراوەکان پیشان بدە',
'metadata-collapse' => 'وردەکارییە درێژکراوەکان بشارەوە',
'metadata-fields' => 'کێڵگەکانی میتاداتای وێنە کە لەم پەیامەدا بەڕیزکراون کاتێک خشتەی میتاداتا کۆکراوەش بێت لە پەڕەی وێنەدا نیشان دەدرێن.
کێڵگەکانی تر لە حاڵەتی بنەڕەتیدا شاراوەن.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'پانی',
'exif-imagelength' => 'بەرزی',
'exif-ycbcrpositioning' => 'شوێنی Y و C',
'exif-datetime' => 'ڕێکەوتی و کاتی گۆڕانی پەڕگە',
'exif-imagedescription' => 'ناونیشانی وێنە',
'exif-model' => 'جۆری کامێرا',
'exif-software' => 'نەرمەواڵەی بەکارهاتوو',
'exif-artist' => 'نووسەر',
'exif-exifversion' => 'وەشانی exif',
'exif-colorspace' => 'بۆشایی ره‌نگ',
'exif-pixelydimension' => 'پانی وێنە',
'exif-pixelxdimension' => 'بەرزی وێنە',
'exif-usercomment' => 'بۆچوونەکانی بەکارهێنەر',
'exif-relatedsoundfile' => 'فایلی ده‌نگی لێکچوو',
'exif-exposuretime-format' => '$1 چرکە ($2)',
'exif-fnumber' => 'ڕێژەی ئێف',
'exif-lightsource' => 'سەرچاوەی ڕووناکی',
'exif-flash' => 'فلاش',
'exif-subjectlocation' => 'شوێنی ناسکار',
'exif-filesource' => 'سەرچاوەی پەڕگە',
'exif-saturation' => 'تێربوون',
'exif-gpslatitude' => 'پانی',
'exif-gpslongitude' => 'درێژی',
'exif-gpsaltitude' => 'بەرزایی',
'exif-gpstimestamp' => 'کاتی GPS (سەعاتی ئەتۆمی)',
'exif-gpssatellites' => 'سەتەلایتەکانی بەکارگیراو بۆ پێوان',
'exif-gpsmeasuremode' => 'جۆری پێوان',
'exif-gpsdop' => 'وردی پێوان',
'exif-gpsspeedref' => 'یەکەی خێرایی',
'exif-gpsspeed' => 'خێرایی وەرگری GPS',
'exif-gpstrack' => 'ئاڕاستەی جوڵان',
'exif-gpsimgdirection' => 'ئاڕاستەی وێنە',
'exif-gpsareainformation' => 'ناوی ناوچەی GPS',
'exif-gpsdatestamp' => 'ڕێکەوتی GPS',
'exif-jpegfilecomment' => 'تێبینیی پەڕگەی JPEG',
'exif-worldregioncreated' => 'ناوچەی جیھانێک کە وێنەکە تێیدا گیراوە',
'exif-countrycreated' => 'وڵاتێک کە وێنەکە تێیدا گیراوە',
'exif-citycreated' => 'شارێک کە وێنەکە تێیدا گیراوە',
'exif-worldregiondest' => 'ناوچەی جیھانی نیشان دراو',
'exif-countrydest' => 'وڵاتی نیشان دراو',
'exif-countrycodedest' => 'کۆدی وڵاتی نیشان دراو',
'exif-provinceorstatedest' => 'پارێزگا یان ویلایەتی نیشان دراو',
'exif-citydest' => 'شاری نیشان دراو',
'exif-objectname' => 'سەردێری کورت',
'exif-specialinstructions' => 'ڕیسای کاری تایبەت',
'exif-headline' => 'سەردێر',
'exif-source' => 'سەرچاوە',
'exif-contact' => 'زانیاری پەیوەندیکردن',
'exif-writer' => 'نووسەر',
'exif-languagecode' => 'زمان',
'exif-iimversion' => 'وەشانی IIM',
'exif-iimcategory' => 'پۆل',
'exif-lens' => 'لێنزی بەکارگیراو',
'exif-serialnumber' => 'ژمارە زنجیرەی کامێرا',
'exif-cameraownername' => 'خاوەنی کامێرا',
'exif-rating' => 'تازیاری (لە ٥)',
'exif-copyrighted' => 'ڕەوشی مافی لەبەرگرتنەوە',
'exif-pngfilecomment' => 'تێبینیی پەڕگەی PNG',
'exif-contentwarning' => 'ھۆشداری ناوەرۆک',
'exif-giffilecomment' => 'تێبینیی پەڕگەی GIF',
'exif-intellectualgenre' => 'جۆری بابەت',
'exif-subjectnewscode' => 'کۆدی بابەت',

# Make & model, can be wikified in order to link to the camera and model name
'exif-subjectnewscode-value' => '$2 ($1)',

# Exif attributes
'exif-compression-1' => 'نەپەستێنراو',

'exif-copyrighted-true' => 'خاوەنی مافی بڵاوکردنەوە',
'exif-copyrighted-false' => 'پاوانی گشتی',

'exif-unknowndate' => 'ڕێکەوتی نەزانراو',

'exif-orientation-1' => 'ئاسایی',
'exif-orientation-2' => 'ئاسۆیی هەڵگێڕدراوەتەوە',
'exif-orientation-3' => '١٨٠° سوڕاوەتەوە',
'exif-orientation-4' => 'ستوونی هەڵگێڕدراوەتەوە',

'exif-componentsconfiguration-0' => 'بوونی نییە',

'exif-exposureprogram-1' => 'دەستکار',
'exif-exposureprogram-2' => 'بەرنامەی ئاسایی',

'exif-subjectdistance-value' => '$1 مەتر',

'exif-meteringmode-0' => 'نەزانراو',
'exif-meteringmode-1' => 'تێکڕا',
'exif-meteringmode-5' => 'شێوە',
'exif-meteringmode-6' => 'بەش بەش',
'exif-meteringmode-255' => 'هیتر',

'exif-lightsource-0' => 'نەزانراو',
'exif-lightsource-1' => 'ڕووناکی ڕۆژ',
'exif-lightsource-2' => 'فلۆرسەنت',
'exif-lightsource-3' => 'تانگەستەن',
'exif-lightsource-4' => 'فلاش',
'exif-lightsource-9' => 'ئاسمانی ڕوون',
'exif-lightsource-10' => 'ئاسمانی هەوری',
'exif-lightsource-11' => 'سێبەر',
'exif-lightsource-12' => 'فلۆرسەنتی ڕووناکیی‌ڕۆژ (D 5700 – 7100K)',
'exif-lightsource-13' => 'فلۆرسەنتی سپیی ڕۆژ (N 4600 – 5400K)',
'exif-lightsource-14' => 'فلۆرسەنتی سپیی فێنک (W 3900 – 4500K)',
'exif-lightsource-15' => 'فلۆرسەنتی سپی (WW 3200 – 3700K)',
'exif-lightsource-17' => 'ڕووناکی ستانداردی A',
'exif-lightsource-18' => 'ڕووناکی ستانداردی B',
'exif-lightsource-19' => 'ڕووناکی ستانداردی C',
'exif-lightsource-24' => 'ISOـی تانگەستەنی ستۆدیۆ',
'exif-lightsource-255' => 'سەرچاوەی دیکە ڕووناکی',

# Flash modes
'exif-flash-fired-0' => 'فلاش کاری نەکرد',
'exif-flash-fired-1' => 'فلاش کاری کرد',
'exif-flash-return-0' => 'فەنکشێنی بینینەوەی گەڕانەوەی ڕووناکی فلاش نیە',
'exif-flash-return-2' => 'گەڕانەوەی ڕووناکی فلاش نەبینرایەوە',
'exif-flash-return-3' => 'گەڕانەوەی ڕووناکی فلاش بینرایەوە',
'exif-flash-mode-1' => 'کارکردنی ناچاریی فلاش',
'exif-flash-mode-2' => 'بەرگری ناچاری لە کارکردنی فلاش',
'exif-flash-mode-3' => 'شێوازی خۆکار',
'exif-flash-function-1' => 'فەنکشێنی فلاش نیە',
'exif-flash-redeye-1' => 'شێوازی کەم‌کردنەوەی سوور-چاو',

'exif-focalplaneresolutionunit-2' => 'ئینج',

'exif-sensingmethod-1' => 'دیاری نەکراو',
'exif-sensingmethod-2' => 'یەک چیپی هەستەوەری بەشی ڕەنگ',
'exif-sensingmethod-3' => 'دوو چیپی هەستەوەری بەشی ڕەنگ',
'exif-sensingmethod-4' => 'سێ چیپی هەستەوەری بەشی ڕەنگ',
'exif-sensingmethod-5' => 'هەستەوەری بەشی ڕەنگی زنجیری',
'exif-sensingmethod-7' => 'هەستەوەری سێ‌هێڵی',
'exif-sensingmethod-8' => 'هەستەوەری هێڵی ڕەنگی زنجیری',

'exif-scenetype-1' => 'وێنەیەکی ڕاستەوخۆ وێنەگیراو',

'exif-customrendered-0' => 'پرۆسەی ئاسایی',
'exif-customrendered-1' => 'پرۆسەی دڵخواز',

'exif-exposuremode-0' => 'بەرچاو خستنی خۆکار',
'exif-exposuremode-1' => 'بەرچاو خستنی دەستی',
'exif-exposuremode-2' => 'زنجیرە گرتنی خۆکار',

'exif-whitebalance-0' => 'خۆکار یەکسان‌کردنی سپیایی',
'exif-whitebalance-1' => 'دەستی یەکسان‌کردنی سپیایی',

'exif-scenecapturetype-0' => 'ستاندارد',
'exif-scenecapturetype-1' => 'دیمەن',
'exif-scenecapturetype-2' => 'پۆرترە',
'exif-scenecapturetype-3' => 'وێنەی شەو',

'exif-gaincontrol-0' => 'هیچ',

'exif-contrast-0' => 'ئاسایی',
'exif-contrast-1' => 'نەرم',
'exif-contrast-2' => 'ڕەق',

'exif-saturation-0' => 'ئاسایی',
'exif-saturation-1' => 'تێرکردنی کەم',
'exif-saturation-2' => 'تێرکردنی زۆر',

'exif-sharpness-0' => 'ئاسایی',
'exif-sharpness-1' => 'نەرم',
'exif-sharpness-2' => 'ڕەق',

'exif-subjectdistancerange-0' => 'نەزانراو',
'exif-subjectdistancerange-1' => 'گەورە',
'exif-subjectdistancerange-2' => 'دیمەنی نزیک',
'exif-subjectdistancerange-3' => 'دیمەنی دوور',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'پانیی جوگرافیایی باکوور',
'exif-gpslatitude-s' => 'پانیی جوگرافیایی باشوور',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'پانیی جوگرافیایی ڕۆژهەڵات',
'exif-gpslongitude-w' => 'پانیی جوگرافیایی ڕۆژئاوا',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|مەتر}} بەرزتر لە ئاستی زەریا',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|مەتر}} نزمتر لە ئاستی زەریا',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کیلۆمەتر هەر کاتژمێر',
'exif-gpsspeed-m' => 'مایل هەر کاتژمێر',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'کیلۆمەتر',
'exif-gpsdestdistance-m' => 'میل',
'exif-gpsdestdistance-n' => 'میکی دەریایی',

'exif-gpsdop-good' => 'چاک ($1)',

'exif-objectcycle-a' => 'تەنیا بەیانان',
'exif-objectcycle-p' => 'تەنیا ئێواران',
'exif-objectcycle-b' => 'بەیانان و ئێواران',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'لای دروست',

'exif-dc-contributor' => 'بەشداربووان',
'exif-dc-date' => 'ڕۆژ(ەکان)',
'exif-dc-publisher' => 'بڵاوکار',
'exif-dc-relation' => 'میدیای پەیوەندیدار',
'exif-dc-rights' => 'مافەکان',
'exif-dc-source' => 'سەرچاوەی میدیا',
'exif-dc-type' => 'جۆری میدیا',

'exif-rating-rejected' => 'ڕەت کراوە',

'exif-isospeedratings-overflow' => 'گەورەتر لە ٦٥٥٣٥',

'exif-iimcategory-ace' => 'ھونەر، چاند و تاوژین',
'exif-iimcategory-fin' => 'ئابووری و بازرگانی',
'exif-iimcategory-edu' => 'فێرکاری',
'exif-iimcategory-evn' => 'ژینگە',
'exif-iimcategory-hth' => 'تەندروستی',
'exif-iimcategory-lab' => 'کار',
'exif-iimcategory-pol' => 'سیاسەت',
'exif-iimcategory-rel' => 'ئایین و باوەڕ',
'exif-iimcategory-sci' => 'زانست و تەکنۆلۆژیا',
'exif-iimcategory-soi' => 'بابەتە کۆمەڵایەتییەکان',
'exif-iimcategory-spo' => 'وەرزشەکان',
'exif-iimcategory-wea' => 'کەش و ھەوا',

'exif-urgency-normal' => 'ئاسایی ($1)',
'exif-urgency-low' => 'کەم ($1)',
'exif-urgency-high' => 'زۆر ($1)',

# External editor support
'edit-externally' => 'دەستکاریی ئەم پەڕەیە بکە بە بەکارھێنانی پڕۆگرامێکی دەرەکی',
'edit-externally-help' => '(بۆ زانیاریی زیاتر سەیری [//www.mediawiki.org/wiki/Manual:External_editors  ڕێنماییەکانی دامەزراندن] بکە)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ھەموو',
'namespacesall' => 'ھەموو',
'monthsall' => 'ھەموو',
'limitall' => 'ھەموو',

# Email address confirmation
'confirmemail' => 'بڕواپێکردنی ناونیشانی ئیمەیل',
'confirmemail_noemail' => 'لە [[Special:Preferences|هەڵبژاردەکانی بەکارهێنەر]] ناونیشانی ئی‌مەیلی گونجاوت دیاری نەکردووە.',
'confirmemail_text' => '{{SITENAME}} بە پێویستی دەزانێ پێش کەڵک وەرگرتن لە تایبەتمەندیەکانی ئی‌مەیل، ناونیشانی ئی‌مەیلی خۆت ڕاچاو بکەیت.
ئەو دوکمەی خوارەوە چالاک بکە تاکوو ئی‌مەیلێکی بڕوا پێ‌کردن بنێردرێت بۆ ناونیشانی ئی‌مەیلەکەت.
ئەو ئی‌مەیلە بەستەرەکی تێدایە؛ لە وێبگەڕەکەت ئەو بەستەرە ببینە تاکوو ناونیشانی ئی‌مەیلەکەت بڕوادار بێت.',
'confirmemail_pending' => 'کۆدی بڕواپێ‌کردن لە پێش‌دا ئی‌مەیل کراوە بۆت.
ئەگەر بە تازەیی هەژمارەت درووست‌کردووە، لەوانەیە باشتربێت چەن خۆلکێک بوەستی بۆ گەیشتنی ئەو ئەمەیلی، پێش دیسان داواکردنەوەی کۆدی نوێ.',
'confirmemail_send' => 'ئی‌مەیل‌کردنی کۆدی بڕواپێ‌کردن',
'confirmemail_sent' => 'ئی‌مەیلی بڕواپێ‌کردن ناردرا.',
'confirmemail_oncreate' => 'کۆدی بڕواپێ‌کردنی ناردرا بۆ ناونیشانی ئی‌مەیلت.
پێویست نیە بڕۆیتە ژوورەوە، تەنها پێویستە پێش کەڵک وەرگرتن لە تایبەتمەندیەکان ئی‌مەیلیی ویکی ئەوە جێبەجێ بکەیت.',
'confirmemail_sendfailed' => '{{SITENAME}} ناتوانێ ئی‌مەیلی برواکردن بنێرێت بۆ تۆ.
تکایە ئەرخەیان بە هەموو پیتەکانی ناونیشانەکەت گونجاوە.

مەیلکەر ئەوەی گەڕاندەوە: $1',
'confirmemail_invalid' => 'کۆدی بڕواپێ‌کردنی نەگونجاو.
لەوانەیە ئەو کۆدە ماوەی بەسەر چووبێت.',
'confirmemail_needlogin' => 'بۆ بڕواپێکردنی ناونیشانی ئیمەیلەکەت دەبێ $1.',
'confirmemail_success' => 'ناونیشانی ئی‌میلەکەت بڕوای‌پێ‌کرا.
ئێستە دەتوانی [[Special:UserLogin|بڕۆیتە ژوورەوە]] و لە ویکی کەڵک بگری.',
'confirmemail_loggedin' => 'ئێستا بڕواکراوە بە ئیمەیلەکەت.',
'confirmemail_error' => 'کێشەیەک هەیە لە پاشەکەوت‌کردنی بڕواپێ‌کردنی تۆدا.',
'confirmemail_subject' => 'بڕوا پێ‌کردنی ناونیشانی ئی‌مەیلی {{SITENAME}}',
'confirmemail_body' => 'کەسێک، لەوانەیە خۆت، لە ناونیشانی ئای‌پی $1،
لە {{SITENAME}} بەم ناونیشانی ئی‌مەیلە، هەژمارەیەکی تۆمارکردووە "$2" .

بۆ ئەوەی بڕا بکرێت کە ئەم هەژمارە لە ڕاستیدا بۆتۆیە و بۆ چالاک‌کردنی تایبەتمەندیەکانی ئی‌مەیل لە {{SITENAME}}دا، ئەو بەستەرەی خوارەوە لە وێبگەڕەکەت‌دا بکەوە:

$3

ئەگەر تۆ ئەو هەژمارەت تۆمار *نەکردووە*، بۆ هەڵوەشاندنەوەی بڕوا‌پێ‌کردنی ناونیشانی ئی‌مەیل بڕۆ بۆ ئەم بەستەرە:

$5

ئەم کۆدی بڕواپێ‌کردنە لە $4 ماوەی بەسەردێت.',
'confirmemail_body_changed' => 'کەسێک، لەوانەیە خۆت، لە ئای‌پی ئەدرەسی $1،
ئەدرەسی ئەیمەیلی ھەژماری «$2» لە {{SITENAME}}دا گۆڕاوە بۆ ئەم ئەدرەسە.

بۆ ئەوەی بڕوا بکرێت کە ئەم ھەژمارە لە ڕاستیدا بۆتۆیە و بۆ چالاککردنەوەی تایبەتمەندیەکانی ئیمەیل لە {{SITENAME}}دا، ئەم بەستەرەی خوارەوە لە وێبگەڕەکەتدا بکەوە:

$3

ئەگەر ھەژمارە ھی تۆ *نییە*، بۆ هەڵوەشاندنەوەی بڕوا‌پێکردنی ئەدرەسی ئیمەیل بەدوای ئەم بەستەرە بکەوە:

$5

ئەم کۆدی بڕواپێکردنە لە $4 ماوەی بەسەردێت.',
'confirmemail_body_set' => 'کەسێک، لەوانەیە خۆت، لە ئای‌پی ئەدرەسی $1،
ئەدرەسی ئەیمەیلی ھەژماری «$2» لە {{SITENAME}}دا کردووە بەم ئەدرەسە.

بۆ ئەوەی بڕوا بکرێت کە ئەم ھەژمارە لە ڕاستیدا بۆتۆیە و بۆ چالاککردنەوەی تایبەتمەندیەکانی ئیمەیل لە {{SITENAME}}دا، ئەم بەستەرەی خوارەوە لە وێبگەڕەکەتدا بکەوە:

$3

ئەگەر ھەژمارە ھی تۆ *نییە*، بۆ هەڵوەشاندنەوەی بڕوا‌پێکردنی ئەدرەسی ئیمەیل بەدوای ئەم بەستەرە بکەوە:

$5

ئەم کۆدی بڕواپێکردنە لە $4 ماوەی بەسەردێت.',
'confirmemail_invalidated' => 'بڕواپی‌کردنی ناونیشانی ئی‌مەیل هەڵوەشێندراوە',
'invalidateemail' => 'هەڵوەشاندنەوەی بڕواپێ‌کردنی ئی‌مەیل',

# Scary transclusion
'scarytranscludetoolong' => '[URL زۆر درێژە]',

# Delete conflict
'deletedwhileediting' => "'''ھۆشیار بە''': ئەم پەڕە دوای ئەوە تۆ دەستکاریکردنیت دەستپێکرد سڕاوەتەوە!",
'confirmrecreate-noreason' => 'بەکارھێنەر [[User:$1|$1]] ([[User talk:$1|talk]]) پەڕەکەی سڕییەوە پاش ئەوەی تۆ دەستکاریکردنی پەڕەکەت دەستپێکرد. تکایە پشتڕاستی بکەوە کە بە ڕاستی دەتەوێ دیسان ئەم پەڕە دروست بکەیتەوە.',
'recreate' => 'درووست‌کردنەوە',

# action=purge
'confirm_purge_button' => 'باشە',

# action=watch/unwatch
'confirm-watch-button' => 'باشە',
'confirm-watch-top' => 'زێدەکردنی ئەم پەڕە بە لیستی چاودێریت؟',
'confirm-unwatch-button' => 'باشه‌',
'confirm-unwatch-top' => 'ئەم پەڕە لە لیستی چاودێریت لاببرێت؟',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator' => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '← پەڕەی پێشوو',
'imgmultipagenext' => 'پەڕەی داهاتوو →',
'imgmultigo' => 'بڕۆ!',
'imgmultigoto' => 'بڕۆ بۆ پەڕەی $1',

# Table pager
'ascending_abbrev' => 'بەرەوە ژوور',
'descending_abbrev' => 'بەرەوە ژێر',
'table_pager_next' => 'پەڕەی داهاتوو',
'table_pager_prev' => 'پەڕەی پێشوو',
'table_pager_first' => 'پەرەی یەکەم',
'table_pager_last' => 'دوا پەڕە',
'table_pager_limit' => '$1 بابەت نیشانبدە لە هەر پەڕەیەک',
'table_pager_limit_label' => 'بابەت لە ھەر پەڕەیەکدا:',
'table_pager_limit_submit' => 'بڕۆ',
'table_pager_empty' => 'هیچ ئەنجامێک نییە',

# Auto-summaries
'autosumm-blank' => 'پەڕەکەی واڵا کردەوە',
'autosumm-replace' => '«$1»ی لە جێی ناوەرۆک دانا',
'autoredircomment' => 'ڕەوانە کرا بۆ [[$1]]',
'autosumm-new' => 'پەڕەی دروستکرد بە «$1»ەوە',

# Size units
'size-bytes' => '$1 بایت',
'size-kilobytes' => '$1 کیلۆبایت',
'size-megabytes' => '$1 مێگابایت',
'size-gigabytes' => '$1 گیگابایت',
'size-terabytes' => '$1 تێرابایت',
'size-petabytes' => '$1 پێبی‌بایت',

# Live preview
'livepreview-loading' => 'باركردن‌...',
'livepreview-ready' => 'بارکردن... ئامادە!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'گۆڕانکاریەکانی نوێ‌تر لە $1 {{PLURAL:$1|چرکە|چرکە}} لەوانەیە لەم لیستەدا نیشان نەدرێن.',
'lag-warn-high' => 'لەبەر زۆر دواکەوتنی ڕاژەکاری بنکەدراو، گۆڕانکاریەکانی نوێ‌تر لە $1 {{PLURAL:$1|چرکە|چرکە}} لەوانەیە لەم لیستەدا نیشان نەدرێن.',

# Watchlist editor
'watchlistedit-numitems' => 'بێجگە لە پەڕەی وتووێژەکان، لیستی چاودێڕییەکانت {{PLURAL:$1|1 بابەت|$1 بابەت}}ی تێدایە،',
'watchlistedit-noitems' => 'لیستی چاودێڕییەکانت ھیچ بابەتێکی تێدا نییە.',
'watchlistedit-normal-title' => 'دە‌ستکاری لیستی چاودێری',
'watchlistedit-normal-legend' => 'لابردنی سەردێڕەکان لە لیستی چاودێری',
'watchlistedit-normal-explain' => 'سەردێڕی بڕگەکانی لیستی چاودێریەکەت لە خوارەوە نیشان‌دراون.
بۆ لابردنی هەرکام، چوارچێوەی بەرامبەری نیشان بکە و کرتە بکە سەر {{int:Watchlistedit-normal-submit}} بۆ لابردنی سەردێڕەکان
ھەروەھا دەتوانی [[Special:EditWatchlist/raw|دەستکاری لیستی خاو]] بکەیت.',
'watchlistedit-normal-submit' => 'ناونیشانەکان لاببە',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 سەردێڕ |$1 سەردێڕ}} لە لیستی چاودێریت سڕایەوە:',
'watchlistedit-raw-title' => 'دەستکاری لیستی خاوی چاودێری',
'watchlistedit-raw-legend' => 'دەستکاری لیستی خاوی چاودێری',
'watchlistedit-raw-explain' => 'سەردێڕی بەڕگەکانی لیستی چاودێریەکەت لە خوارەوە نیشان‌دراون و دەتوانی بە زیادکردن و لابردن دەستکاری بکەیت؛
هەر هێڵێک، سەردێڕێک.
کاتێ تەواوت‌کرد، لەسەر «{{int:Watchlistedit-raw-submit}}» کرتە بکە.
هەروا دەتوانی لە [[Special:EditWatchlist|دەستکاریکەری ستاندارد]] کەڵک‌وەرگریت.',
'watchlistedit-raw-titles' => 'ناونیشانەکان:',
'watchlistedit-raw-submit' => 'نوێکردنەوەی لیستی چاودێری',
'watchlistedit-raw-done' => 'لیستی چاودێریەکەت نوێ‌کرایەوە',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 سەردێڕ|$1 سەردێڕ}} زیادکرا:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 سەردێڕ|$1 سەردێڕ}} لابرا:',

# Watchlist editing tools
'watchlisttools-view' => 'گۆڕانکارییە پەیوەندیدارەکان ببینە',
'watchlisttools-edit' => 'لیستی چاودێری ببینە و دەستکاری بکە',
'watchlisttools-raw' => 'لیستی خاوی چاودێری دەستکاری بکە',

# Iranian month names
'iranian-calendar-m1' => 'خاکەلێوە',
'iranian-calendar-m2' => 'گوڵان',
'iranian-calendar-m3' => 'جۆزەردان',
'iranian-calendar-m4' => 'پووشپەڕ',
'iranian-calendar-m5' => 'گەلاوێژ',
'iranian-calendar-m6' => 'خەرمانان',
'iranian-calendar-m7' => 'ڕەزبەر',
'iranian-calendar-m8' => 'خەزەڵوەر',
'iranian-calendar-m9' => 'سەرماوەز',
'iranian-calendar-m10' => 'بەفرانبار',
'iranian-calendar-m11' => 'ڕێبەندان',
'iranian-calendar-m12' => 'ڕەشەمە',

# Hijri month names
'hijri-calendar-m1' => 'موحەڕەم',
'hijri-calendar-m2' => 'سەفەر',
'hijri-calendar-m3' => 'ڕەبیعەلئەووەڵ',
'hijri-calendar-m4' => 'ڕەبیعەلئاخیر',
'hijri-calendar-m5' => 'جومادەلئوولا',
'hijri-calendar-m6' => 'جومادەلئاخیر',
'hijri-calendar-m7' => 'ڕەجەب',
'hijri-calendar-m8' => 'شەعبان',
'hijri-calendar-m9' => 'ڕەمەزان',
'hijri-calendar-m10' => 'شەووال',
'hijri-calendar-m11' => 'زولقەعدە',
'hijri-calendar-m12' => 'زولحەججە',

# Hebrew month names
'hebrew-calendar-m7-gen' => 'نیسان',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|لێدوان]])',
'timezone-utc' => 'UTC',

# Core parser functions
'unknown_extension_tag' => 'تاگی درێژکراوەی نەناسراو "$1"',
'duplicate-defaultsort' => "'''ئاگاداری''' کلیلی پۆلێنکردنی \"\$2'' چووەتە شوێنی کلیلی پۆلێنکردنی  \"\$1\"",

# Special:Version
'version' => 'وەشان',
'version-extensions' => 'پێوەکراوە دامەزراوەکان',
'version-specialpages' => 'پەڕە تایبەتەکان',
'version-parserhooks' => 'قولاپە لێککەرەکان',
'version-variables' => 'گۆڕاوەکان',
'version-skins' => 'پێستەکان',
'version-other' => 'Other',
'version-mediahandlers' => 'Media handlers',
'version-hooks' => 'قولاپەکان',
'version-parser-extensiontags' => 'Parser extension tags',
'version-parser-function-hooks' => 'Parser function hooks',
'version-hook-name' => 'ناوی قولاپ',
'version-hook-subscribedby' => 'بەشداربوو لە لایەن',
'version-version' => '(وەشانی $1)',
'version-license' => 'مۆڵەت',
'version-poweredby-others' => 'دیکە',
'version-software' => 'نەرمەکاڵای دامەزراو',
'version-software-product' => 'بەرهەم',
'version-software-version' => 'وەشان',
'version-entrypoints-header-url' => 'ناونیشانی ئینتەرنێتی',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'گەڕان بۆ پەڕگە دووپات کراوەکان',
'fileduplicatesearch-summary' => 'گەڕان بۆ پەڕگە دووبارەکراوەکان لەسەر بنەمای نرخی hash.',
'fileduplicatesearch-legend' => 'گەڕان بۆ دووبارەکردنێک',
'fileduplicatesearch-filename' => 'ناوی پەرگە:',
'fileduplicatesearch-submit' => 'گەڕان',
'fileduplicatesearch-info' => '$1 × $2 پیکسەل<br />قەبارەی پەڕگە: $3<br />MIME جۆری: $4',
'fileduplicatesearch-result-1' => 'پەڕگەی "$1" دووپاتکراوەیەکی کوتوموتی نییە.',
'fileduplicatesearch-result-n' => 'پەڕگەی «$1» {{PLURAL:$2|١ دووپاتکراوەی کوتوموتی|$2 دووپاتکراوەی کوتوموتی}} ھەیە.',
'fileduplicatesearch-noresults' => 'پەڕگەیەک بە ناوی «$1» نەدۆزرایەوە.',

# Special:SpecialPages
'specialpages' => 'پەڕە تایبەتەکان',
'specialpages-note' => '----
* پەڕە تایبەتە ئاسایییەکان.
* <span class="mw-specialpagerestricted">پەڕە تایبەتە بەرگری‌لێکراوەکان.</span>',
'specialpages-group-maintenance' => 'ڕاپۆرتەکانی چاکسازی',
'specialpages-group-other' => 'پەڕە تایبەتەکانی دیکە',
'specialpages-group-login' => 'چوونەژوورەوە / دروستکردنی ھەژمار',
'specialpages-group-changes' => 'دوایین گۆڕانکارییەکان و لۆگەکان',
'specialpages-group-media' => 'ڕاپۆرتەکان و بارکردنەکانی میدیا',
'specialpages-group-users' => 'بەکارھێنەران و مافەکان',
'specialpages-group-highuse' => 'پەڕە زۆر بەکار ھێنراوەکان',
'specialpages-group-pages' => 'پێرستەکانی پەڕەکان',
'specialpages-group-pagetools' => 'ئامرازەکانی پەڕە',
'specialpages-group-wiki' => 'دراوەکان و ئامرازەکان',
'specialpages-group-redirects' => 'پەڕە تایبەتەکانی رەوانکردنەوە',
'specialpages-group-spam' => 'ئامرازەکانی سپەم',

# Special:BlankPage
'blankpage' => 'پەڕەی واڵا',
'intentionallyblankpage' => 'ئەم پەڕەیە لەقەست واڵا ھێڵراوەتەوە.',

# External image whitelist
'external_image_whitelist' => ' #ئەم دێڕ ھەر بەم جۆرە کە ھەیە بەجێبێڵە<pre>
#کەرتەکانی regular expression (تەنیا ئە بەشە کە لە نێوان // دا دێت) لە خوارەوە دابنێ
#These will be matched with the URLs of external (hotlinked) images
#Those that match will be displayed as images, otherwise only a link to the image will be shown
#ئەو دێڕانە بە # دەست پێدەکەن وەک شرۆڤە (comments) مامەڵەیان لەگەڵ دەکرێ
#بە گەورە و بچووکی پیتەکان ھەستیارە (case-insensitive)

#گشت کەرتەکانی regex لە سەرەوەی ئەم دێرەدا دابنێ. ئەم دێڕ ھەر بەم جۆرە کە ھەیە بەجێبێڵە</pre>',

# Special:Tags
'tags' => 'گۆڕانکاری گونجاوی تاگەکان',
'tag-filter' => 'پاڵێوی [[Special:Tags|تاگ]]:',
'tag-filter-submit' => 'پاڵاوتن',
'tags-title' => 'تاگەکان',
'tags-intro' => 'ئەم لاپەڕە ئەو تاگانەی لیست دەکات کە لەوانەیە نەرمامێر دەستکاریەکی بۆ نیشان بکات و مەبەستی نیشان بدات.',
'tags-tag' => 'ناوی تاگ',
'tags-display-header' => 'دیمەن لەسەر لیستەکانی گۆڕان',
'tags-description-header' => 'پێناسەی تەواوی مەبەست',
'tags-hitcount-header' => 'گۆڕانکاریە تاگ‌کراوەکان',
'tags-edit' => 'دەستکاری',
'tags-hitcount' => '$1 {{PLURAL:$1|گۆڕان|گۆڕانکاری}}',

# Special:ComparePages
'comparepages' => 'پەڕەکان ھەڵسەنگێنە',
'compare-selector' => 'پیاچوونەوەکانی پەڕە ھەڵسەنگێنە',
'compare-page1' => 'پەڕەی ١',
'compare-page2' => 'پەڕەی ٢',
'compare-rev1' => 'پێداچوونەوەی ١',
'compare-rev2' => 'پێداچوونەوەی ٢',
'compare-submit' => 'ھەڵسەنگاندن',
'compare-invalid-title' => 'ئەم سەردێڕە دەستنیشانت کردووە نادروستە.',

# Database error messages
'dberr-header' => 'ئەم ویکی‌یە کێشەی هەیە',
'dberr-problems' => 'ببورە! ئەم ماڵپەڕە ئێستا خەریک ئەزموونێکی کێشەی تەکنیکیە.',
'dberr-again' => 'چەن خولک ڕاوەستە و نوێی بکەوە.',
'dberr-info' => '(پەیوەندی لەگەڵ ڕاژەکاری بنکەدراو پێکنایەت: $1)',
'dberr-usegoogle' => 'دەتوانی هاوکات هەوڵی گەڕان بە گووگڵ بدەیت.',
'dberr-outofdate' => 'لەیادت بێ لەوانەیە پێرستەکەیان سەبارەت نە ناوەڕۆک ئەم ماڵپەڕە ماوە بەسەرچوو بێت.',
'dberr-cachederror' => 'ئەمە ڕوونووسێکی کاش‌کراوی لاپەڕەی داواکراوە و لەوانەیە بەڕۆژ نەبێت.',

# HTML forms
'htmlform-invalid-input' => 'هێندێ کێشە هەیە لە بڕێک لە ناودراوەکانت',
'htmlform-select-badoption' => 'ئەو نرخەی دیاریت‌کردووە هەڵبژاردەیەکی گونجاو نیە.',
'htmlform-int-invalid' => 'ئەو نرخەی دیاریت‌کردووه ژمارەیەکی تەواو نیە.',
'htmlform-float-invalid' => 'ئەو نرخەی دیاریت‌کردووه ژمارە نیە.',
'htmlform-int-toolow' => 'ئەو نرخەی دیاریت‌کردووه کەمترە لە ئەمپەڕی $1.',
'htmlform-int-toohigh' => 'ئەو نرخەی دیاریت‌کردووه زیاترە لە ئەوپەڕی $1.',
'htmlform-submit' => 'ناردن',
'htmlform-reset' => 'پووچەڵکردنەوەی دەستکارییەکان',
'htmlform-selectorother-other' => 'دیکە',

# New logging system
'logentry-delete-delete' => '$1 پەڕەی $3ی {{GENDER:$2|سڕییەوە}}',
'logentry-delete-restore' => '$1 پەڕەی $3ی {{GENDER:$2|ھێنایەوە}}',
'logentry-delete-revision' => '$1 دەرکەوتنی {{PLURAL:$5|پێداچوونەوەیەکی|$5 پێداچوونەوەی}} پەڕەی $3ی {{GENDER:$2|گۆڕیی}}: $4',
'logentry-suppress-delete' => '$1 پەڕەی $3 {{GENDER:$2|بەرگری کرد}}.',
'revdelete-content-hid' => 'ناوەرۆک شاردراوە',
'revdelete-summary-hid' => 'کورتەی دەستکاری شاردراوە',
'revdelete-uname-hid' => 'ناوی بەکارهێنەری شاراوە',
'revdelete-content-unhid' => 'ناوەرۆک نیشان درا',
'revdelete-summary-unhid' => 'کورتەی دەستکاری نیشان درا',
'revdelete-uname-unhid' => 'ناوی بەکارهێنەری نیشان درا',
'revdelete-restricted' => 'ئەو سنووری بەرگریانەی خستراوەتە سەر بەڕێوبەران',
'revdelete-unrestricted' => 'ئەو سنووری بەرگریانەی لابردراوە لە سەر بەڕێوبەران',
'logentry-move-move' => '$1 پەڕەی $3ی {{GENDER:$2|گواستەوە}} بۆ $4',
'logentry-move-move-noredirect' => '$1 پەڕەی $3ی بەبێ بەجێھشتنی ڕەوانەکەرێک {{GENDER:$2|گواستەوە}} بۆ $4',
'logentry-move-move_redir' => '$1 پەڕەی $3 {{GENDER:$2|گواستەوە}} بۆ $4 کە پێشتر ڕەوانەکەر بوو',
'logentry-move-move_redir-noredirect' => '$1 پەڕەی $3ی بەبێ بەجێھشتنی ڕەوانەکەرێک {{GENDER:$2|گواستەوە}} بۆ $4 کە پێشتر ڕەوانەکەر بوو',
'logentry-patrol-patrol' => '$1 پێداچوونەوەی $4ی پەڕەی $3 وەک پاس دراو {{GENDER:$2|نیشان کرد}}',
'logentry-patrol-patrol-auto' => '$1 بە شێوەی خۆگەڕ پێداچوونەوەی $4ی پەڕەی $3 وەک پاس دراو {{GENDER:$2|نیشان کرد}}',
'logentry-newusers-newusers' => 'ھەژماری بەکارھێنەریی $1 {{GENDER:$2|دروست کرا}}',
'logentry-newusers-create' => 'ھەژماری بەکارھێنەریی $1 {{GENDER:$2|دروست کرا}}',
'logentry-newusers-create2' => 'ھەژماری بەکارھێنەریی $3 لە لایەن $1 {{GENDER:$2|دروست کرا}}',
'logentry-newusers-autocreate' => 'ھەژماری بەکارھێنەریی $1 بە شێوەی خۆگەڕ {{GENDER:$2|دروست کرا}}',
'logentry-rights-rights' => '$1 ئەندامێتیی $3ی لە $4 بۆ $5 {{GENDER:$2|گۆڕی}}',
'rightsnone' => '(ھیچ)',

# Feedback
'feedback-subject' => 'بابەت:',
'feedback-message' => 'پەیام:',
'feedback-cancel' => 'ھەڵیوەشێنەوە',
'feedback-submit' => 'تێبینییەکان بنێرە',
'feedback-close' => 'ئەنجام درا',

# Search suggestions
'searchsuggest-search' => 'گەڕان',
'searchsuggest-containing' => 'بە لەبەرگرتنەوەی ...',

# API errors
'api-error-empty-file' => 'ئەو پەڕگەیە کە ناردووتە واڵا بوو.',
'api-error-file-too-large' => 'ئەو پەڕگەیە ناردووتە زۆر گەورەیە.',
'api-error-filename-tooshort' => 'ناوی پەڕگەکە زۆر کورتە.',
'api-error-filetype-banned' => 'ئەم جۆرە پەڕگەیە قەدەغەیە.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|جۆرە پەڕگەیەکی ڕێگەپێدراو نییە|جۆرە پەڕگە ڕێگەپێدراوەکان نین}}. {{PLURAL:$3|جۆرە پەڕگەی ڕێگەپێدراو ئەمەیە|جۆرە پەڕگەکانی ڕێگەپێدراو ئەمانەن}}:  $2.',
'api-error-illegal-filename' => 'ناوی پەڕگە رێگەپێ‌نەدراوە.',
'api-error-unclassified' => 'ھەڵەیەکی نەزانراو ڕوویداوە.',
'api-error-unknown-code' => 'ھەڵەی نەزانراو: «$1».',
'api-error-unknownerror' => 'ھەڵەی نەزانراو: «$1».',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|چرکە|چرکە}}',
'duration-minutes' => '$1 {{PLURAL:$1|خولەک|خولەک}}',
'duration-hours' => '$1 {{PLURAL:$1|کاتژمێر|کاتژمێر}}',
'duration-days' => '$1 {{PLURAL:$1|ڕۆژ|ڕۆژ}}',
'duration-weeks' => '$1 {{PLURAL:$1|ھەفتە|ھەفتە}}',
'duration-years' => '$1 {{PLURAL:$1|ساڵ|ساڵ}}',
'duration-decades' => '$1 {{PLURAL:$1|دەیە|دەیە}}',
'duration-centuries' => '$1 {{PLURAL:$1|سەدە|سەدە}}',
'duration-millennia' => '$1 {{PLURAL:$1|ھەزارە|ھەزارە}}',

);
