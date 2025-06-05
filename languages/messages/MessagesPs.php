<?php
/** Pashto (پښتو)
 *
 * @file
 * @ingroup Languages
 *
 * @author Ahmed-Najib-Biabani-Ibrahimkhel
 * @author Kaganer
 */

$rtl = true;

$digitTransformTable = [
	'0' => '۰', # U+06F0
	'1' => '۱', # U+06F1
	'2' => '۲', # U+06F2
	'3' => '۳', # U+06F3
	'4' => '۴', # U+06F4
	'5' => '۵', # U+06F5
	'6' => '۶', # U+06F6
	'7' => '۷', # U+06F7
	'8' => '۸', # U+06F8
	'9' => '۹', # U+06F9
];

$separatorTransformTable = [
	'.' => '٫', # U+066B
	',' => '٬', # U+066C
];

$numberingSystem = 'arabext';

// Use Gregorian calendar, where appropriate, override ps browser locale
$jsDateFormats = [
	'mdy date' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'mdy both' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'mdy pretty' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'dmy date' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'dmy both' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'dmy pretty' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'ymd date' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'ymd both' => [ 'options' => [ 'calendar' => 'gregory' ] ],
	'ymd pretty' => [ 'options' => [ 'calendar' => 'gregory' ] ],
];

$namespaceNames = [
	NS_MEDIA            => 'رسنۍ',
	NS_SPECIAL          => 'ځانگړی',
	NS_TALK             => 'خبرې_اترې',
	NS_USER             => 'کارن',
	NS_USER_TALK        => 'د_کارن_خبرې_اترې',
	NS_PROJECT_TALK     => 'د_$1_خبرې_اترې',
	NS_FILE             => 'دوتنه',
	NS_FILE_TALK        => 'د_دوتنې_خبرې_اترې',
	NS_MEDIAWIKI        => 'ميډياويکي',
	NS_MEDIAWIKI_TALK   => 'د_ميډياويکي_خبرې_اترې',
	NS_TEMPLATE         => 'کينډۍ',
	NS_TEMPLATE_TALK    => 'د_کينډۍ_خبرې_اترې',
	NS_HELP             => 'لارښود',
	NS_HELP_TALK        => 'د_لارښود_خبرې_اترې',
	NS_CATEGORY         => 'وېشنيزه',
	NS_CATEGORY_TALK    => 'د_وېشنيزې_خبرې_اترې',
];

$namespaceAliases = [
	'ځﺎﻧګړی' => NS_SPECIAL,
	'کارونکی' => NS_USER,
	'د_کارونکي_خبرې_اترې' => NS_USER_TALK,
	'انځور' => NS_FILE,
	'د_انځور_خبرې_اترې' => NS_FILE_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'ټول-پيغامونه' ],
	'Allpages'                  => [ 'ټول_مخونه' ],
	'Ancientpages'              => [ 'لرغوني_مخونه' ],
	'Blankpage'                 => [ 'تش_مخ' ],
	'Block'                     => [ 'بنديز،_د_آی_پي_بنديز،_بنديز_لګېدلی_کارن_Block' ],
	'BlockList'                 => [ 'د_بنديزلړليک' ],
	'Booksources'               => [ 'د_کتاب_سرچينې' ],
	'Categories'                => [ 'وېشنيزې' ],
	'ChangePassword'            => [ 'پټنوم_بدلول،_پټنوم_بيا_پر_ځای_کول،_د_بيا_پر_ځای_کولو_پاسپورټ' ],
	'Contributions'             => [ 'ونډې' ],
	'CreateAccount'             => [ 'کارن-حساب_جوړول' ],
	'DeletedContributions'      => [ 'ړنګې_شوي_ونډې' ],
	'Export'                    => [ 'صادرول' ],
	'LinkSearch'                => [ 'د_تړنې_پلټنه' ],
	'Listfiles'                 => [ 'د_انځورونو_لړليک' ],
	'Listusers'                 => [ 'د_کارنانو_لړليک' ],
	'Log'                       => [ 'يادښتونه،_يادښت' ],
	'Lonelypages'               => [ 'يتيم_مخونه' ],
	'Longpages'                 => [ 'اوږده_مخونه' ],
	'Mycontributions'           => [ 'زماونډې' ],
	'Mypage'                    => [ 'زما_پاڼه' ],
	'Mytalk'                    => [ 'زما_خبرې_اترې' ],
	'Newimages'                 => [ 'نوي_انځورونه' ],
	'Newpages'                  => [ 'نوي_مخونه' ],
	'Preferences'               => [ 'غوره_توبونه' ],
	'Prefixindex'               => [ 'د_مختاړيو_ليکلړ' ],
	'Protectedpages'            => [ 'ژغورلي_مخونه' ],
	'Protectedtitles'           => [ 'ژغورلي_سرليکونه' ],
	'Randompage'                => [ 'ناټاکلی،_ناټاکلی_مخ' ],
	'Recentchanges'             => [ 'اوسني_بدلونونه' ],
	'Renameuser'                => [ 'دکارونکي_نوم_بدلون' ],
	'Search'                    => [ 'پلټنه' ],
	'Shortpages'                => [ 'لنډ_مخونه' ],
	'Specialpages'              => [ 'ځانګړي_مخونه' ],
	'Statistics'                => [ 'شمار' ],
	'Unblock'                   => [ 'بنديز_لرې_کول' ],
	'Uncategorizedcategories'   => [ 'ناوېشلې_وېشنيزې' ],
	'Uncategorizedimages'       => [ 'ناوېشلي_انځورونه،_ناوېشلې_دوتنې' ],
	'Uncategorizedpages'        => [ 'ناوېشلي_مخونه' ],
	'Uncategorizedtemplates'    => [ 'ناوېشلې_کينډۍ' ],
	'Undelete'                  => [ 'ناړنګول' ],
	'Unusedcategories'          => [ 'ناکارېدلي_وېشنيزې' ],
	'Unusedimages'              => [ 'ناکارېدلې_دوتنې' ],
	'Unusedtemplates'           => [ 'ناکارېدلې_کينډۍ' ],
	'Unwatchedpages'            => [ 'ناکتلي_مخونه' ],
	'Upload'                    => [ 'پورته_کول' ],
	'Userlogin'                 => [ 'ننوتل' ],
	'Userlogout'                => [ 'وتل' ],
	'Version'                   => [ 'بڼه' ],
	'Wantedcategories'          => [ 'غوښتلې_وېشنيزې' ],
	'Wantedfiles'               => [ 'غوښتلې_دوتنې' ],
	'Wantedtemplates'           => [ 'غوښتلې_کينډۍ' ],
	'Watchlist'                 => [ 'کتنلړ' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'currentday'                => [ '1', 'نن', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'نن۲', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'دننۍورځې_نوم', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'داوونۍورځ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'دم_ګړۍ', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'روانه_مياشت', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'دروانې_مياشت_لنډون', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'دروانې_مياشت_نوم', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'داوخت', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'روانه_اوونۍ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'سږکال', 'CURRENTYEAR' ],
	'forcetoc'                  => [ '0', '__نيوليکداره__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'دمخ_بشپړنوم', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'دمخ_بشپړنوم_نښه', 'FULLPAGENAMEE' ],
	'grammar'                   => [ '0', 'ګرامر:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__پټه_وېشنيزه__', '__HIDDENCAT__' ],
	'img_center'                => [ '1', 'مېنځ،_center', 'center', 'centre' ],
	'img_left'                  => [ '1', 'کيڼ', 'left' ],
	'img_none'                  => [ '1', 'هېڅ', 'none' ],
	'img_right'                 => [ '1', 'ښي', 'right' ],
	'img_thumbnail'             => [ '1', 'بټنوک', 'thumb', 'thumbnail' ],
	'index'                     => [ '1', '__ليکلړ__', '__INDEX__' ],
	'language'                  => [ '0', '#ژبه', '#LANGUAGE' ],
	'localday'                  => [ '1', 'سيمه_يزه_ورځ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'سيمه_يزه_ورځ۲', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'دسيمه_يزې_ورځ_نوم', 'LOCALDAYNAME' ],
	'localhour'                 => [ '1', 'سيمه_يزه_ګړۍ', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'سيمه_يزه_مياشت', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthabbrev'          => [ '1', 'دسيمه_يزې_مياشت_لنډون', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'دسيمه_يزې_مياشت_نوم', 'LOCALMONTHNAME' ],
	'localtime'                 => [ '1', 'سيمه_يزوخت', 'LOCALTIME' ],
	'localweek'                 => [ '1', 'سيمه_يزه_اوونۍ', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'سيمه_يزکال', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'پیغام:', 'پ:', 'MSG:' ],
	'namespace'                 => [ '1', 'نوم_تشيال', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'د_نوم_تشيال_نښه', 'NAMESPACEE' ],
	'noeditsection'             => [ '0', '__بی‌برخې__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__بی‌نندارتونه__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__بې_ليکلړ__', '__NOINDEX__' ],
	'notoc'                     => [ '0', '__بی‌نيولک__', '__NOTOC__' ],
	'numberofarticles'          => [ '1', 'دليکنوشمېر', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ددوتنوشمېر', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'دمخونوشمېر', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'دکارونکوشمېر', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'دمخ_نوم', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'دمخ_نښه', 'PAGENAMEE' ],
	'pagesize'                  => [ '1', 'مخکچه', 'PAGESIZE' ],
	'plural'                    => [ '0', 'جمع:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'ژغورکچه', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#مخګرځ', '#REDIRECT' ],
	'server'                    => [ '0', 'پالنګر', 'SERVER' ],
	'servername'                => [ '0', 'دپالنګر_نوم', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'دوېبځي_نوم', 'SITENAME' ],
	'special'                   => [ '0', 'ځانګړی', 'special' ],
	'subjectspace'              => [ '1', 'دسکالوتشيال', 'دليکنې_تشيال', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'دسکالوتشيال_نښه', 'دليکنې_تشيال_نښه', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'talkspace'                 => [ '1', 'دخبرواترو_تشيال', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'دخبرواترو_تشيال_نښه', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__نيوليک__', '__TOC__' ],
];
