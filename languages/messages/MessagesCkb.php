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

$namespaceNames = array(
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
	'ChangePassword'            => array( 'تێپەڕوشەگۆڕان،ڕێکخستنەوەی_تێپەڕوشە' ),
	'Confirmemail'              => array( 'بڕواکردن_ئیمەیل' ),
	'Contributions'             => array( 'بەشدارییەکان' ),
	'CreateAccount'             => array( 'دروستکردنی_ھەژمار' ),
	'Deadendpages'              => array( 'پەڕە_بەربەستراوەکان' ),
	'DoubleRedirects'           => array( 'ڕەوانکەرە_دووپاتکراوەکان' ),
	'Emailuser'                 => array( 'ئیمەیل_بەکارھێنەر' ),
	'Export'                    => array( 'ھەناردن' ),
	'Fewestrevisions'           => array( 'کەمترین_پێداچوونەوەکان' ),
	'Import'                    => array( 'ھاوردن' ),
	'Listadmins'                => array( 'لیستی_بەڕێوبەران' ),
	'Listbots'                  => array( 'لیستی_بۆتەکان' ),
	'Listfiles'                 => array( 'لیستی_پەڕگەکان' ),
	'Listusers'                 => array( 'لیستی_بەکارھێنەران' ),
	'Log'                       => array( 'لۆگ' ),
	'Lonelypages'               => array( 'پەڕە_تاکەکان،_پەڕە_ھەتیوکراوەکان' ),
	'Longpages'                 => array( 'پەڕە_درێژەکان' ),
	'MergeHistory'              => array( 'کردنەیەکی_مێژوو' ),
	'Mostcategories'            => array( 'زیاترین_پۆلەکان' ),
	'Mostimages'                => array( 'پەڕگەکانی_زیاترین_بەستەردراون،_زیاترین_پەڕگەکان،_زیاترین_وێنەکان' ),
	'Mostlinked'                => array( 'پەڕەکانی_زیاترین_بەستەردراون،_زیاترین_بەستەردراون' ),
	'Mostlinkedcategories'      => array( 'پۆلەکانی_زیاترین_بەستەردراون،_پۆلەکانی_زیاترین_بەکارھێنراون' ),
	'Mostlinkedtemplates'       => array( 'داڕێژەکانی_زیاترین_بەستەردراون،_داڕێژەکانی_زیاترین_بەکارھێنراون' ),
	'Mostrevisions'             => array( 'زیاترین_پێداچوونەوەکان' ),
	'Movepage'                  => array( 'گواستنەوەی_پەڕە' ),
	'Mycontributions'           => array( 'بەشدارییەکانم' ),
	'Mypage'                    => array( 'پەڕەکەم' ),
	'Mytalk'                    => array( 'لێدوانەکەم' ),
	'Newimages'                 => array( 'پەڕگە_نوێکان' ),
	'Newpages'                  => array( 'پەڕە_نوێکان' ),
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

$datePreferences = array(
	'default',
	'dmy',
	'ymd',
	'persian',
	'hijri',
);

$defaultDateFormat = 'dmy';

$datePreferenceMigrationMap = array(
	'default',
	'dmy', // migrate users off mdy - not present in this language
	'dmy',
	'ymd'
);

$dateFormats = array(
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
);

$linkTrail = "/^([ئابپتجچحخدرڕزژسشعغفڤقکگلڵمنوۆهھەیێ‌]+)(.*)$/sDu";
