<?php
/** Mazanderani (مازِرونی)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ali1986
 * @author Ebraminio
 * @author Firuz
 * @author Mehdi
 * @author Mjbmr
 * @author Parthava (on mzn.wikipedia.org)
 * @author Spacebirdy
 * @author محک
 */

$fallback = 'fa';

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'شا',
	NS_MAIN             => '',
	NS_TALK             => 'گپ',
	NS_USER             => 'کارور',
	NS_USER_TALK        => 'کارور_گپ',
	NS_PROJECT_TALK     => '$1_گپ',
	NS_FILE             => 'پرونده',
	NS_FILE_TALK        => 'پرونده_گپ',
	NS_MEDIAWIKI        => 'مدیاویکی',
	NS_MEDIAWIKI_TALK   => 'مدیاویکی_گپ',
	NS_TEMPLATE         => 'شابلون',
	NS_TEMPLATE_TALK    => 'شابلون_گپ',
	NS_HELP             => 'رانما',
	NS_HELP_TALK        => 'رانما_گپ',
	NS_CATEGORY         => 'رج',
	NS_CATEGORY_TALK    => 'رج_گپ',
);

$namespaceAliases = array(
	'مه‌دیا'         => NS_MEDIA,
	'مدیا'          => NS_MEDIA,
	'ویژه'          => NS_SPECIAL,
	'بحث'           => NS_TALK,
	'کاربر'         => NS_USER,
	'بحث_کاربر'     => NS_USER_TALK,
	'بحث_$1'        => NS_PROJECT_TALK,
	'تصویر'         => NS_FILE,
	'پرونده'        => NS_FILE,
	'بحث_تصویر'     => NS_FILE_TALK,
	'بحث_پرونده'    => NS_FILE_TALK,
	'مدیاویکی'      => NS_MEDIAWIKI,
	'مه‌دیا ویکی'    => NS_MEDIAWIKI,
	'مه‌دیاویکی'     => NS_MEDIAWIKI,
	'مه‌دیاویکی_گپ'  => NS_MEDIAWIKI_TALK,
	'بحث_مدیاویکی'  => NS_MEDIAWIKI_TALK,
	'مه‌دیا ویکی گپ' => NS_MEDIAWIKI_TALK,
	'الگو'          => NS_TEMPLATE,
	'بحث_الگو'      => NS_TEMPLATE_TALK,
	'راهنما'        => NS_HELP,
	'رانه‌ما'        => NS_HELP,
	'رانه‌مائه_گپ'   => NS_HELP_TALK,
	'بحث_راهنما'    => NS_HELP_TALK,
	'رانه‌مای گپ'    => NS_HELP_TALK,
	'رده'           => NS_CATEGORY,
	'بحث_رده'       => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'                  => array( '0', '#بور', '#تغییرمسیر', '#تغییر_مسیر', '#REDIRECT' ),
	'notoc'                     => array( '0', '__بی‌فهرست__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__بی‌نگارخنه__', '__بی‌نگارخانه__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__بافهرست__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__فهرست__', '__TOC__' ),
	'noeditsection'             => array( '0', '__بی‌بخش__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'ماه‌کنونی۲', 'ماه_اسایی۲', 'ماه_کنونی۲', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'ماه۱', 'ماه‌کنونی۱', 'ماه_کنونی۱', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'نام‌ماه', 'نام_ماه', 'نام‌ماه‌کنونی', 'نام_ماه_کنونی', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'نام‌ماه‌اضافه', 'نام_ماه_اضافه', 'نام‌ماه‌کنونی‌اضافه', 'نام_ماه_کنونی_اضافه', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'مخفف‌نام‌ماه', 'مخفف_نام_ماه', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'روز', 'روزکنونی', 'روز_کنونی', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'روز۲', 'روز_۲', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'نام‌روز', 'نام_روز', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'سال', 'سال‌کنونی', 'سال_کنونی', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'زمان‌کنونی', 'زمان_کنونی', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ساعت', 'ساعت‌کنونی', 'ساعت_کنونی', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'ماه‌محلی', 'ماه_محلی', 'ماه‌محلی۲', 'ماه_محلی۲', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'ماه‌محلی۱', 'ماه_محلی۱', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'نام‌ماه‌محلی', 'نام_ماه_محلی', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'نام‌ماه‌محلی‌اضافه', 'نام_ماه_محلی_اضافه', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'مخفف‌ماه‌محلی', 'مخفف_ماه_محلی', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'روزمحلی', 'روز_محلی', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'روزمحلی۲', 'روز_محلی_۲', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'نام‌روزمحلی', 'نام_روز_محلی', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'سال‌محلی', 'سال_محلی', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'زمون_محلی', 'زمان_محلی', 'زمان‌محلی', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'تعدادصفحه‌ها', 'تعداد_صفحه‌ها', 'ولگ‌ئون_نمره', 'وألگ‌ئون_نومره', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'تعدادکارورون', 'تعدادکاربران', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'کارورون_فعال', 'کاربران_فعال', 'کاربران‌فعال', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'تعداددچی‌یه‌ئون', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'تعدادبازدید', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'نام‌صفحه‌کد', 'نام_صفحه_کد', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'فضای‌نام‌کد', 'فضای_نام_کد', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'فضای‌گپ', 'فضای_گپ', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'فضای‌گپ_کد', 'فضای_گپ_کد', 'فضای‌بحث‌کد', 'فضای_بحث_کد', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'int'                       => array( '0', 'ترجمه:', 'INT:' ),
	'sitename'                  => array( '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ),
	'ns'                        => array( '0', 'فن:', 'NS:' ),
	'nse'                       => array( '0', 'فنک:', 'NSE:' ),
	'localurl'                  => array( '0', 'نشونی:', 'نشانی:', 'LOCALURL:' ),
	'grammar'                   => array( '0', 'دستور_زبون:', 'دستور_زوون:', 'دستورزبان:', 'دستور_زبان:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'جنسیت:', 'جنس:', 'GENDER:' ),
);

