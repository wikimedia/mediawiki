<?php
/** Sorani Kurdish (کوردی)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$rtl = true;
$fallback8bitEncoding = 'windows-1256';

$namespaceNames = [
	NS_MEDIA            => 'میدیا',
	NS_SPECIAL          => 'تایبەت',
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
];

$namespaceAliases = [
	'لێدوان'            => NS_TALK,
	'قسەی_بەکارھێنەر'   => NS_USER_TALK,
	'لێدوانی_پەڕگە'     => NS_FILE_TALK,
	'لێدوانی_میدیاویکی' => NS_MEDIAWIKI_TALK,
	'قاڵب'              => NS_TEMPLATE,
	'لێدوانی_قاڵب'      => NS_TEMPLATE_TALK,
	'لێدوانی_داڕێژە'    => NS_TEMPLATE_TALK,
	'لێدوانی_یارمەتی'   => NS_HELP_TALK,
	'لێدوانی_پۆل'       => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'بەکارھێنەرە_چالاکەکان' ],
	'Allmessages'               => [ 'ھەموو_پەیامەکان' ],
	'Allpages'                  => [ 'ھەموو_پەڕەکان' ],
	'Ancientpages'              => [ 'پەڕە_کۆنەکان' ],
	'Blankpage'                 => [ 'پەڕەی_واڵا' ],
	'Booksources'               => [ 'سەرچاوەکانی_کتێب' ],
	'BrokenRedirects'           => [ 'ڕەوانکەرە_شکاوەکان' ],
	'Categories'                => [ 'پۆلەکان' ],
	'ChangePassword'            => [ 'تێپەڕوشەگۆڕان،ڕێکخستنەوەی_تێپەڕوشە' ],
	'Confirmemail'              => [ 'بڕواکردن_ئیمەیل' ],
	'Contributions'             => [ 'بەشدارییەکان' ],
	'CreateAccount'             => [ 'دروستکردنی_ھەژمار' ],
	'Deadendpages'              => [ 'پەڕە_بەربەستراوەکان' ],
	'DoubleRedirects'           => [ 'ڕەوانکەرە_دووپاتکراوەکان' ],
	'Emailuser'                 => [ 'ئیمەیل_بەکارھێنەر' ],
	'Export'                    => [ 'ھەناردن' ],
	'Fewestrevisions'           => [ 'کەمترین_پێداچوونەوەکان' ],
	'Import'                    => [ 'ھاوردن' ],
	'Listadmins'                => [ 'لیستی_بەڕێوبەران' ],
	'Listbots'                  => [ 'لیستی_بۆتەکان' ],
	'Listfiles'                 => [ 'لیستی_پەڕگەکان' ],
	'Listusers'                 => [ 'لیستی_بەکارھێنەران' ],
	'Log'                       => [ 'لۆگ' ],
	'Lonelypages'               => [ 'پەڕە_تاکەکان،_پەڕە_ھەتیوکراوەکان' ],
	'Longpages'                 => [ 'پەڕە_درێژەکان' ],
	'MergeHistory'              => [ 'کردنەیەکی_مێژوو' ],
	'Mostcategories'            => [ 'زیاترین_پۆلەکان' ],
	'Mostimages'                => [ 'پەڕگەکانی_زیاترین_بەستەردراون،_زیاترین_پەڕگەکان،_زیاترین_وێنەکان' ],
	'Mostlinked'                => [ 'پەڕەکانی_زیاترین_بەستەردراون،_زیاترین_بەستەردراون' ],
	'Mostlinkedcategories'      => [ 'پۆلەکانی_زیاترین_بەستەردراون،_پۆلەکانی_زیاترین_بەکارھێنراون' ],
	'Mostlinkedtemplates'       => [ 'داڕێژەکانی_زیاترین_بەستەردراون،_داڕێژەکانی_زیاترین_بەکارھێنراون' ],
	'Mostrevisions'             => [ 'زیاترین_پێداچوونەوەکان' ],
	'Movepage'                  => [ 'گواستنەوەی_پەڕە' ],
	'Mycontributions'           => [ 'بەشدارییەکانم' ],
	'Mypage'                    => [ 'پەڕەکەم' ],
	'Mytalk'                    => [ 'لێدوانەکەم' ],
	'Newimages'                 => [ 'پەڕگە_نوێکان' ],
	'Newpages'                  => [ 'پەڕە_نوێکان' ],
	'Preferences'               => [ 'ھەڵبژاردەکان' ],
	'Protectedpages'            => [ 'پەڕە_پارێزراوەکان' ],
	'Protectedtitles'           => [ 'بابەتە_پارێزراوەکان' ],
	'Randompage'                => [ 'ھەڵکەوت،پەڕەی_بە_ھەرمەکی' ],
	'Recentchanges'             => [ 'دوایین_گۆڕانکارییەکان' ],
	'Search'                    => [ 'گەڕان' ],
	'Shortpages'                => [ 'پەڕە‌_کورتەکان' ],
	'Specialpages'              => [ 'پەڕە_تایبەتەکان' ],
	'Statistics'                => [ 'ئامارەکان' ],
	'Unblock'                   => [ 'کردنەوە' ],
	'Uncategorizedcategories'   => [ 'پۆلە_پۆلێننەکراوەکان' ],
	'Uncategorizedimages'       => [ 'پەڕگە_پۆلێننەکراوەکان،_وێنە_پۆلێننەکراوەکان' ],
	'Uncategorizedpages'        => [ 'پەڕە_پۆلێننەکراوەکان' ],
	'Uncategorizedtemplates'    => [ 'داڕێژە_پۆلێننەکراوەکان' ],
	'Unusedcategories'          => [ 'پۆلە_بەکارنەھێنراوەکان' ],
	'Unusedimages'              => [ 'پەڕگە_بەکارنەھێنراوەکان،_وێنە_بەکارنەھێنراوەکان' ],
	'Upload'                    => [ 'بارکردن' ],
	'Userlogin'                 => [ 'چوونەژوورەوەی_بەکارھێنەر' ],
	'Version'                   => [ 'وەشان' ],
	'Wantedcategories'          => [ 'پۆلە_پێویستەکان' ],
	'Wantedfiles'               => [ 'پەڕگە_پێویستەکان' ],
	'Wantedpages'               => [ 'پەڕە_پێویستەکان،_بەستەرە_شکاوەکان' ],
	'Wantedtemplates'           => [ 'داڕێژە_پێویستەکان' ],
	'Watchlist'                 => [ 'لیستی_چاودێری' ],
	'Whatlinkshere'             => [ 'چی_بەستەری_داوە_بێرە' ],
];

$magicWords = [
	'img_thumbnail'             => [ '1', 'وێنۆک', 'thumb', 'thumbnail' ],
	'img_right'                 => [ '1', 'ڕاست', 'right' ],
	'img_left'                  => [ '1', 'چەپ', 'left' ],
	'img_width'                 => [ '1', '$1پیکسڵ', '$1px' ],
	'img_center'                => [ '1', 'ناوەڕاست', 'center', 'centre' ],
	'img_framed'                => [ '1', 'چوارچێوە', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'بێچوارچێوە', 'frameless' ],
	'img_border'                => [ '1', 'سنوور', 'border' ],
];

$digitTransformTable = [
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
];

$separatorTransformTable = [
	'.' => '٫', # &#x066b;
	',' => '٬', # &#x066c;
];

$datePreferences = [
	'default',
	'dmy',
	'ymd',
	'persian',
	'hijri',
];

$defaultDateFormat = 'dmy';

$datePreferenceMigrationMap = [
	'default',
	'dmy', // migrate users off mdy - not present in this language
	'dmy',
	'ymd'
];

$dateFormats = [
	# Please be cautious not to delete the invisible RLM from the beginning of the strings.
	'dmy time' => '‏H:i',
	'dmy date' => '‏jی xg Y',
	'dmy both' => '‏H:i، jی xg Y',

	'ymd time' => '‏H:i',
	'ymd date' => '‏Y/n/j',
	'ymd both' => '‏H:i، Y/n/j',

	'persian time' => '‏H:i',
	'persian date' => '‏xijی xiFی xiY',
	'persian both' => '‏H:i، ‏xijی xiFی xiY',

	'hijri time' => '‏H:i',
	'hijri date' => '‏xmjی xmFی xmY',
	'hijri both' => '‏H:i، xmjی xmFی xmY',
];

$linkTrail = "/^([ئابپتجچحخدرڕزژسشعغفڤقکگلڵمنوۆهھەیێ‌]+)(.*)$/sDu";
