<?php
/** South Azerbaijani (تۆرکجه)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Arjanizary
 * @author E THP
 * @author Koroğlu
 * @author Mjbmr
 */

$fallback = 'fa';
$rtl = true;

$namespaceNames = [
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'اؤزل',
	NS_TALK             => 'دانیشیق',
	NS_USER             => 'ایشلدن',
	NS_USER_TALK        => 'ایشلدن_دانیشیغی',
	NS_PROJECT_TALK     => '$1_دانیشیغی',
	NS_FILE             => 'فایل',
	NS_FILE_TALK        => 'فایل_دانیشیغی',
	NS_MEDIAWIKI        => 'مدیاویکی',
	NS_MEDIAWIKI_TALK   => 'مدیاویکی_دانیشیغی',
	NS_TEMPLATE         => 'شابلون',
	NS_TEMPLATE_TALK    => 'شابلون_دانیشیغی',
	NS_HELP             => 'کؤمک',
	NS_HELP_TALK        => 'کؤمک_دانیشیغی',
	NS_CATEGORY         => 'بؤلمه',
	NS_CATEGORY_TALK    => 'بؤلمه_دانیشیغی',
];

$namespaceAliases = [
	'مدیا‌ویکی' => NS_MEDIAWIKI,
	'مدیا‌ویکی_دانیشیغی' => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'چالیشقان_ایشلدن‌لر' ],
	'Allmessages'               => [ 'بوتون_مئساژلار' ],
	'Allpages'                  => [ 'بوتون_صفحه‌لر' ],
	'Ancientpages'              => [ 'اسکی_صفحه‌لر' ],
	'Badtitle'                  => [ 'پیس_آد' ],
	'Blankpage'                 => [ 'بوش_صفحه' ],
	'ChangePassword'            => [ 'رمزی_دَییش' ],
	'CreateAccount'             => [ 'حساب_یارات' ],
	'Mycontributions'           => [ 'چالیشمالاریم' ],
	'Mypage'                    => [ 'صفحه‌م' ],
	'Mytalk'                    => [ 'دانیشیغیم' ],
	'Myuploads'                 => [ 'یوکله‌دیکلریم' ],
	'Newimages'                 => [ 'یئنی_فایل‌لار' ],
	'Newpages'                  => [ 'یئنی_صفحه‌لر' ],
	'PasswordReset'             => [ 'رمز_دَییشمه‌' ],
	'Randompage'                => [ 'راست‌گله' ],
	'Recentchanges'             => [ 'سون_دَییشیکلر' ],
	'Search'                    => [ 'آختار' ],
	'Shortpages'                => [ 'قیسسا_صفحه‌لر' ],
	'Specialpages'              => [ 'اؤزل_صفحه‌لر' ],
	'Statistics'                => [ 'آمار' ],
	'Unusedcategories'          => [ 'ایشلنممیش_بؤلمه‌لر' ],
	'Unusedimages'              => [ 'ایشلنممیش_فایل‌لار' ],
	'Unusedtemplates'           => [ 'ایشلنممیش_شابلونلار' ],
	'Unwatchedpages'            => [ 'باخیلمامیش_صحیفه‌لر' ],
	'Upload'                    => [ 'یوکله' ],
	'Version'                   => [ 'نوسخه' ],
	'Watchlist'                 => [ 'ایزله‌دیکلر' ],
];

$magicWords = [
	'numberofpages'             => [ '1', 'صفحه‌لر_ساییسی', 'تعدادصفحه‌ها', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'مقاله‌لر_ساییسی', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'قایل‌لار_ساییسی', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'یشلدن‌لر_ساییسی', 'تعدادکاربران', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'چالیشقان_ایشلدن‌لر', 'کاربران‌فعال', 'کاربران_فعال', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'دَییشدیرمه_ساییسی', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'صفحه‌نین_آدی', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ],
	'img_right'                 => [ '1', 'ساغ', 'راست', 'right' ],
	'img_left'                  => [ '1', 'سول', 'چپ', 'left' ],
	'img_none'                  => [ '1', 'هئچ', 'هیچ', 'none' ],
	'img_framed'                => [ '1', 'قابیق', 'قاب', 'frame', 'framed', 'enframed' ],
];
