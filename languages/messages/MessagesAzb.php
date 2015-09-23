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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'مدیا‌ویکی' => NS_MEDIAWIKI,
	'مدیا‌ویکی_دانیشیغی' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'چالیشقان_ایشلدن‌لر' ),
	'Allmessages'               => array( 'بوتون_مئساژلار' ),
	'Allpages'                  => array( 'بوتون_صفحه‌لر' ),
	'Ancientpages'              => array( 'اسکی_صفحه‌لر' ),
	'Badtitle'                  => array( 'پیس_آد' ),
	'Blankpage'                 => array( 'بوش_صفحه' ),
	'ChangePassword'            => array( 'رمزی_دَییش' ),
	'CreateAccount'             => array( 'حساب_یارات' ),
	'Mycontributions'           => array( 'چالیشمالاریم' ),
	'Mypage'                    => array( 'صفحه‌م' ),
	'Mytalk'                    => array( 'دانیشیغیم' ),
	'Myuploads'                 => array( 'یوکله‌دیکلریم' ),
	'Newimages'                 => array( 'یئنی_فایل‌لار' ),
	'Newpages'                  => array( 'یئنی_صفحه‌لر' ),
	'PasswordReset'             => array( 'رمز_دَییشمه‌' ),
	'Randompage'                => array( 'راست‌گله' ),
	'Recentchanges'             => array( 'سون_دَییشیکلر' ),
	'Search'                    => array( 'آختار' ),
	'Shortpages'                => array( 'قیسسا_صفحه‌لر' ),
	'Specialpages'              => array( 'اؤزل_صفحه‌لر' ),
	'Statistics'                => array( 'آمار' ),
	'Unusedcategories'          => array( 'ایشلنممیش_بؤلمه‌لر' ),
	'Unusedimages'              => array( 'ایشلنممیش_فایل‌لار' ),
	'Unusedtemplates'           => array( 'ایشلنممیش_شابلونلار' ),
	'Unwatchedpages'            => array( 'باخیلمامیش_صحیفه‌لر' ),
	'Upload'                    => array( 'یوکله' ),
	'Version'                   => array( 'نوسخه' ),
	'Watchlist'                 => array( 'ایزله‌دیکلر' ),
);

$magicWords = array(
	'numberofpages'             => array( '1', 'صفحه‌لر_ساییسی', 'تعدادصفحه‌ها', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'مقاله‌لر_ساییسی', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'قایل‌لار_ساییسی', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'یشلدن‌لر_ساییسی', 'تعدادکاربران', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'چالیشقان_ایشلدن‌لر', 'کاربران‌فعال', 'کاربران_فعال', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'دَییشدیرمه_ساییسی', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'صفحه‌نین_آدی', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ),
	'img_right'                 => array( '1', 'ساغ', 'راست', 'right' ),
	'img_left'                  => array( '1', 'سول', 'چپ', 'left' ),
	'img_none'                  => array( '1', 'هئچ', 'هیچ', 'none' ),
	'img_framed'                => array( '1', 'قابیق', 'قاب', 'framed', 'enframed', 'frame' ),
);

