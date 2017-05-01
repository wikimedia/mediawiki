<?php
/** Mazanderani (مازِرونی)
 *
 * To improve a translation please visit https://translatewiki.net
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

$namespaceNames = [
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'شا',
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
];

$namespaceAliases = [
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
	'مه‌دیا_ویکی'    => NS_MEDIAWIKI,
	'مه‌دیاویکی'     => NS_MEDIAWIKI,
	'مه‌دیاویکی_گپ'  => NS_MEDIAWIKI_TALK,
	'بحث_مدیاویکی'  => NS_MEDIAWIKI_TALK,
	'مه‌دیا_ویکی_گپ' => NS_MEDIAWIKI_TALK,
	'الگو'          => NS_TEMPLATE,
	'بحث_الگو'      => NS_TEMPLATE_TALK,
	'راهنما'        => NS_HELP,
	'رانه‌ما'        => NS_HELP,
	'رانه‌مائه_گپ'   => NS_HELP_TALK,
	'بحث_راهنما'    => NS_HELP_TALK,
	'رانه‌مای_گپ'    => NS_HELP_TALK,
	'رده'           => NS_CATEGORY,
	'بحث_رده'       => NS_CATEGORY_TALK,
];

$magicWords = [
	'redirect'                  => [ '0', '#بور', '#تغییرمسیر', '#تغییر_مسیر', '#REDIRECT' ],
	'notoc'                     => [ '0', '__بی‌فهرست__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__بی‌نگارخنه__', '__بی‌نگارخانه__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__بافهرست__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__فهرست__', '__TOC__' ],
	'noeditsection'             => [ '0', '__بی‌بخش__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'ماه‌کنونی۲', 'ماه_اسایی۲', 'ماه_کنونی۲', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ماه۱', 'ماه‌کنونی۱', 'ماه_کنونی۱', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'نام‌ماه', 'نام_ماه', 'نام‌ماه‌کنونی', 'نام_ماه_کنونی', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'نام‌ماه‌اضافه', 'نام_ماه_اضافه', 'نام‌ماه‌کنونی‌اضافه', 'نام_ماه_کنونی_اضافه', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'مخفف‌نام‌ماه', 'مخفف_نام_ماه', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'روز', 'روزکنونی', 'روز_کنونی', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'روز۲', 'روز_۲', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'نام‌روز', 'نام_روز', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'سال', 'سال‌کنونی', 'سال_کنونی', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'زمان‌کنونی', 'زمان_کنونی', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ساعت', 'ساعت‌کنونی', 'ساعت_کنونی', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'ماه‌محلی', 'ماه_محلی', 'ماه‌محلی۲', 'ماه_محلی۲', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'ماه‌محلی۱', 'ماه_محلی۱', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'نام‌ماه‌محلی', 'نام_ماه_محلی', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'نام‌ماه‌محلی‌اضافه', 'نام_ماه_محلی_اضافه', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'مخفف‌ماه‌محلی', 'مخفف_ماه_محلی', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'روزمحلی', 'روز_محلی', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'روزمحلی۲', 'روز_محلی_۲', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'نام‌روزمحلی', 'نام_روز_محلی', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'سال‌محلی', 'سال_محلی', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'زمون_محلی', 'زمان_محلی', 'زمان‌محلی', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'تعدادصفحه‌ها', 'تعداد_صفحه‌ها', 'ولگ‌ئون_نمره', 'وألگ‌ئون_نومره', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'تعدادکارورون', 'تعدادکاربران', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'کارورون_فعال', 'کاربران_فعال', 'کاربران‌فعال', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'تعداددچی‌یه‌ئون', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'نام‌صفحه‌کد', 'نام_صفحه_کد', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'فضای‌نام‌کد', 'فضای_نام_کد', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'فضای‌گپ', 'فضای_گپ', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'فضای‌گپ_کد', 'فضای_گپ_کد', 'فضای‌بحث‌کد', 'فضای_بحث_کد', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'int'                       => [ '0', 'ترجمه:', 'INT:' ],
	'sitename'                  => [ '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ],
	'ns'                        => [ '0', 'فن:', 'NS:' ],
	'nse'                       => [ '0', 'فنک:', 'NSE:' ],
	'localurl'                  => [ '0', 'نشونی:', 'نشانی:', 'LOCALURL:' ],
	'grammar'                   => [ '0', 'دستور_زبون:', 'دستور_زوون:', 'دستورزبان:', 'دستور_زبان:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'جنسیت:', 'جنس:', 'GENDER:' ],
];
