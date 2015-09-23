<?php
/** Persian (فارسی)
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
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'ویژه',
	NS_TALK             => 'بحث',
	NS_USER             => 'کاربر',
	NS_USER_TALK        => 'بحث_کاربر',
	NS_PROJECT_TALK     => 'بحث_$1',
	NS_FILE             => 'پرونده',
	NS_FILE_TALK        => 'بحث_پرونده',
	NS_MEDIAWIKI        => 'مدیاویکی',
	NS_MEDIAWIKI_TALK   => 'بحث_مدیاویکی',
	NS_TEMPLATE         => 'الگو',
	NS_TEMPLATE_TALK    => 'بحث_الگو',
	NS_HELP             => 'راهنما',
	NS_HELP_TALK        => 'بحث_راهنما',
	NS_CATEGORY         => 'رده',
	NS_CATEGORY_TALK    => 'بحث_رده',
);

$namespaceAliases = array(
	'رسانه' => NS_MEDIA,
	'رسانه‌ای' => NS_MEDIA,
	'تصویر' => NS_FILE,
	'بحث_تصویر' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'کاربران_فعال' ),
	'Allmessages'               => array( 'تمام_پیغام‌ها' ),
	'AllMyUploads'              => array( 'همهٔ_بارگذاری‌های_من', 'همه_بارگذاری‌های_من' ),
	'Allpages'                  => array( 'تمام_صفحه‌ها' ),
	'ApiHelp'                   => array( 'راهنمای_ای‌پی‌آی' ),
	'Ancientpages'              => array( 'صفحه‌های_قدیمی' ),
	'Badtitle'                  => array( 'عنوان_بد' ),
	'Blankpage'                 => array( 'صفحهٔ_خالی', 'صفحه_خالی' ),
	'Block'                     => array( 'بستن_نشانی_آی‌پی' ),
	'Booksources'               => array( 'منابع_کتاب' ),
	'BrokenRedirects'           => array( 'تغییرمسیرهای_خراب' ),
	'Categories'                => array( 'رده‌ها' ),
	'ChangeEmail'               => array( 'تغییر_ایمیل', 'تغییر_رایانامه' ),
	'ChangePassword'            => array( 'از_نو_کردن_گذرواژه' ),
	'ComparePages'              => array( 'مقایسهٔ_صفحات' ),
	'Confirmemail'              => array( 'تأیید_ایمیل', 'تأیید_رایانامه' ),
	'Contributions'             => array( 'مشارکت‌ها' ),
	'CreateAccount'             => array( 'ایجاد_حساب_کاربری' ),
	'Deadendpages'              => array( 'صفحه‌های_بن‌بست' ),
	'DeletedContributions'      => array( 'مشارکت‌های_حذف_شده' ),
	'Diff'                      => array( 'تفاوت' ),
	'DoubleRedirects'           => array( 'تغییرمسیرهای_دوتایی' ),
	'EditWatchlist'             => array( 'ویرایش_فهرست_پی‌گیری‌ها' ),
	'Emailuser'                 => array( 'ایمیل_به_کاربر', 'نامه_به_کاربر' ),
	'ExpandTemplates'           => array( 'گسترش_الگوها' ),
	'Export'                    => array( 'برون‌بری_صفحه' ),
	'Fewestrevisions'           => array( 'کمترین_نسخه' ),
	'FileDuplicateSearch'       => array( 'جستجوی_پروندهٔ_تکراری' ),
	'Filepath'                  => array( 'مسیر_پرونده' ),
	'Import'                    => array( 'درون‌ریزی_صفحه' ),
	'Invalidateemail'           => array( 'باطل‌کردن_ایمیل', 'باطل‌کردن_رایانامه' ),
	'JavaScriptTest'            => array( 'تست_جاوااسکریپت' ),
	'BlockList'                 => array( 'فهرست_بسته‌شده‌ها', 'فهرست_بستن_نشانی_آی‌پی' ),
	'LinkSearch'                => array( 'جستجوی_پیوند' ),
	'Listadmins'                => array( 'فهرست_مدیران' ),
	'Listbots'                  => array( 'فهرست_ربات‌ها' ),
	'Listfiles'                 => array( 'فهرست_پرونده‌ها', 'فهرست_تصاویر' ),
	'Listgrouprights'           => array( 'اختیارات_گروه‌های_کاربری' ),
	'Listredirects'             => array( 'فهرست_تغییرمسیرها' ),
	'ListDuplicatedFiles'       => array( 'فهرست_پرونده‌های_تکراری' ),
	'Listusers'                 => array( 'فهرست_کاربران' ),
	'Lockdb'                    => array( 'قفل‌کردن_پایگاه_داده‌ها' ),
	'Log'                       => array( 'سیاهه‌ها' ),
	'Lonelypages'               => array( 'صفحه‌های_یتیم' ),
	'Longpages'                 => array( 'صفحه‌های_بلند' ),
	'MediaStatistics'           => array( 'آمار_رسانه‌ها' ),
	'MergeHistory'              => array( 'ادغام_تاریخچه' ),
	'MIMEsearch'                => array( 'جستجوی_MIME' ),
	'Mostcategories'            => array( 'بیشترین_رده' ),
	'Mostimages'                => array( 'بیشترین_تصویر' ),
	'Mostinterwikis'            => array( 'بیشترین_میان‌ویکی' ),
	'Mostlinked'                => array( 'بیشترین_پیوند' ),
	'Mostlinkedcategories'      => array( 'رده_با_بیشترین_پیوند' ),
	'Mostlinkedtemplates'       => array( 'الگو_با_بیشترین_پیوند' ),
	'Mostrevisions'             => array( 'بیشترین_نسخه' ),
	'Movepage'                  => array( 'انتقال_صفحه' ),
	'Mycontributions'           => array( 'مشارکت‌های_من' ),
	'MyLanguage'                => array( 'زبان‌های_من' ),
	'Mypage'                    => array( 'صفحهٔ_من', 'صفحه_من' ),
	'Mytalk'                    => array( 'بحث_من' ),
	'Myuploads'                 => array( 'بارگذاری‌های_من' ),
	'Newimages'                 => array( 'تصاویر_جدید' ),
	'Newpages'                  => array( 'صفحه‌های_تازه' ),
	'PagesWithProp'             => array( 'صفحه‌های_با_خاصیت' ),
	'PageLanguage'              => array( 'زبان_صفحه' ),
	'PasswordReset'             => array( 'بازنشاندن_گذرواژه' ),
	'PermanentLink'             => array( 'پیوند_دائمی' ),
	'Preferences'               => array( 'ترجیحات' ),
	'Prefixindex'               => array( 'نمایه_پیشوندی' ),
	'Protectedpages'            => array( 'صفحه‌های_محافظت‌شده' ),
	'Protectedtitles'           => array( 'عنوان‌های_محافظت‌شده' ),
	'Randompage'                => array( 'صفحهٔ_تصادفی' ),
	'RandomInCategory'          => array( 'تصادفی_در_رده' ),
	'Randomredirect'            => array( 'تغییرمسیر_تصادفی' ),
	'Recentchanges'             => array( 'تغییرات_اخیر' ),
	'Recentchangeslinked'       => array( 'تغییرات_مرتبط' ),
	'Redirect'                  => array( 'تغییرمسیر' ),
	'ResetTokens'               => array( 'بازنشانی_نشانه‌ها' ),
	'Revisiondelete'            => array( 'حذف_نسخه' ),
	'RunJobs'                   => array( 'اجرای_کارها' ),
	'Search'                    => array( 'جستجو' ),
	'Shortpages'                => array( 'صفحه‌های_کوتاه' ),
	'Specialpages'              => array( 'صفحه‌های_ویژه' ),
	'Statistics'                => array( 'آمار' ),
	'Tags'                      => array( 'برچسب‌ها' ),
	'TrackingCategories'        => array( 'رده‌های_ردیابی' ),
	'Unblock'                   => array( 'باز_کردن' ),
	'Uncategorizedcategories'   => array( 'رده‌های_رده‌بندی‌نشده' ),
	'Uncategorizedimages'       => array( 'تصویرهای_رده‌بندی‌نشده' ),
	'Uncategorizedpages'        => array( 'صفحه‌های_رده‌بندی‌نشده' ),
	'Uncategorizedtemplates'    => array( 'الگوهای_رده‌بندی‌نشده' ),
	'Undelete'                  => array( 'احیای_صفحهٔ_حذف‌شده' ),
	'Unlockdb'                  => array( 'قفل‌گشایی_پایگاه_داده‌ها' ),
	'Unusedcategories'          => array( 'رده‌های_استفاده_نشده' ),
	'Unusedimages'              => array( 'تصاویر_استفاده_نشده' ),
	'Unusedtemplates'           => array( 'الگوهای_استفاده_نشده' ),
	'Unwatchedpages'            => array( 'صفحه‌های_پی‌گیری_نشده' ),
	'Upload'                    => array( 'بارگذاری_پرونده' ),
	'UploadStash'               => array( 'بارگذاری_انبوه' ),
	'Userlogin'                 => array( 'ورود_به_سامانه' ),
	'Userlogout'                => array( 'خروج_از_سامانه' ),
	'Userrights'                => array( 'اختیارات_کاربر' ),
	'Version'                   => array( 'نسخه' ),
	'Wantedcategories'          => array( 'رده‌های_مورد_نیاز' ),
	'Wantedfiles'               => array( 'پرونده‌های_مورد_نیاز' ),
	'Wantedpages'               => array( 'صفحه‌های_مورد_نیاز' ),
	'Wantedtemplates'           => array( 'الگوهای_مورد_نیاز' ),
	'Watchlist'                 => array( 'فهرست_پی‌گیری' ),
	'Whatlinkshere'             => array( 'پیوند_به_این_صفحه' ),
	'Withoutinterwiki'          => array( 'بدون_میان‌ویکی' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#تغییر_مسیر', '#تغییرمسیر', '#REDIRECT' ),
	'notoc'                     => array( '0', '__بی‌فهرست__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__بی‌نگارخانه__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__بافهرست__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__فهرست__', '__TOC__' ),
	'noeditsection'             => array( '0', '__بی‌بخش__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'ماه‌کنونی۲', 'ماه_کنونی۲', 'CURRENTMONTH', 'CURRENTMONTH2' ),
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
	'localtime'                 => array( '1', 'زمان‌محلی', 'زمان_محلی', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'تعدادصفحه‌ها', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'تعدادکاربران', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'کاربران‌فعال', 'کاربران_فعال', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'نام‌صفحه‌کد', 'نام_صفحه_کد', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'فضای‌نام‌کد', 'فضای_نام_کد', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'شماره_فضای_نام', 'شماره‌فضای‌نام', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'فضای‌بحث‌کد', 'فضای_بحث_کد', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'فضای‌موضوع‌کد', 'فضای‌مقاله‌کد', 'فضای_موضوع_کد', 'فضای_مقاله_کد', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'نام‌کامل‌صفحه', 'نام_کامل_صفحه', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'نام‌کامل‌صفحه‌کد', 'نام_کامل_صفحه_کد', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'نام‌زیرصفحه', 'نام_زیرصفحه', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'نام‌زیرصفحه‌کد', 'نام_زیرصفحه_کد', 'SUBPAGENAMEE' ),
	'rootpagename'              => array( '1', 'نام_صفحه_ریشه', 'ROOTPAGENAME' ),
	'rootpagenamee'             => array( '1', 'نام_صفحه_ریشه_ای', 'ROOTPAGENAMEE' ),
	'basepagename'              => array( '1', 'نام‌صفحه‌مبنا', 'نام_صفحه_مبنا', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'نام‌صفحه‌مبناکد', 'نام_صفحه_مبنا_کد', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'نام‌صفحه‌بحث', 'نام_صفحه_بحث', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'نام‌صفحه‌بحث‌کد', 'نام_صفحه_بحث_کد', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'نام‌صفحه‌موضوع', 'نام‌صفحه‌مقاله', 'نام_صفحه_موضوع', 'نام_صفحه_مقاله', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'نام‌صفحه‌موضوع‌کد', 'نام‌صفحه‌مقاله‌کد', 'نام_صفحه_موضوع_کد', 'نام_صفحه_مقاله_کد', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'پیغام:', 'پ:', 'MSG:' ),
	'subst'                     => array( '0', 'جایگزین:', 'جا:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'جایگزین_امن:', 'جام:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'پیغام‌بی‌بسط:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'بندانگشتی', 'انگشتدان', 'انگشتی', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'بندانگشتی=$1', 'انگشتدان=$1', 'انگشتی=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'راست', 'right' ),
	'img_left'                  => array( '1', 'چپ', 'left' ),
	'img_none'                  => array( '1', 'هیچ', 'none' ),
	'img_width'                 => array( '1', '$1پیکسل', '$1px' ),
	'img_center'                => array( '1', 'وسط', 'center', 'centre' ),
	'img_framed'                => array( '1', 'قاب', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'بی‌قاب', 'بیقاب', 'بی_قاب', 'frameless' ),
	'img_lang'                  => array( '1', 'زبان=$1', 'lang=$1' ),
	'img_page'                  => array( '1', 'صفحه=$1', 'صفحه_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'ایستاده', 'ایستاده=$1', 'ایستاده_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'حاشیه', 'border' ),
	'img_baseline'              => array( '1', 'همکف', 'baseline' ),
	'img_sub'                   => array( '1', 'زیر', 'sub' ),
	'img_super'                 => array( '1', 'زبر', 'super', 'sup' ),
	'img_top'                   => array( '1', 'بالا', 'top' ),
	'img_text_top'              => array( '1', 'متن-بالا', 'text-top' ),
	'img_middle'                => array( '1', 'میانه', 'middle' ),
	'img_bottom'                => array( '1', 'پایین', 'bottom' ),
	'img_text_bottom'           => array( '1', 'متن-پایین', 'text-bottom' ),
	'img_link'                  => array( '1', 'پیوند=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'جایگزین=$1', 'alt=$1' ),
	'img_class'                 => array( '1', 'کلاس=$1', 'class=$1' ),
	'int'                       => array( '0', 'ترجمه:', 'INT:' ),
	'sitename'                  => array( '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ),
	'ns'                        => array( '0', 'فن:', 'NS:' ),
	'nse'                       => array( '0', 'فنک:', 'NSE:' ),
	'localurl'                  => array( '0', 'نشانی:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'نشانی‌کد:', 'نشانی_کد:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'مسیرمقاله', 'مسیر_مقاله', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'شناسه_صفحه', 'PAGEID' ),
	'server'                    => array( '0', 'سرور', 'کارساز', 'SERVER' ),
	'servername'                => array( '0', 'نام‌کارساز', 'نام_کارساز', 'نام‌سرور', 'نام_سرور', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'مسیرسند', 'مسیر_سند', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'مسیرسبک', 'مسیر_سبک', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'دستورزبان:', 'دستور_زبان:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'جنسیت:', 'جنس:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__عنوان‌تبدیل‌نشده__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__محتواتبدیل‌نشده__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'هفته', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'روزهفته', 'روز_هفته', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'هفته‌محلی', 'هفته_محلی', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'روزهفته‌محلی', 'روز_هفته_محلی', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'نسخه', 'شماره‌نسخه', 'شماره_نسخه', 'REVISIONID' ),
	'revisionday'               => array( '1', 'روزنسخه', 'روز_نسخه', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'روزنسخه۲', 'روز_نسخه۲', 'روز_نسخه_۲', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'ماه‌نسخه', 'ماه_نسخه', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'ماه‌نسخه۱', 'ماه_نسخه_۱', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'سال‌نسخه', 'سال_نسخه', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'زمان‌یونیکسی‌نسخه', 'زمان‌نسخه', 'زمان_یونیکسی_نسخه', 'زمان_نسخه', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'کاربرنسخه', 'کاربر_نسخه', 'REVISIONUSER' ),
	'revisionsize'              => array( '1', 'اندازهٔ‌نسخه', 'اندازهٔ_نسخه', 'REVISIONSIZE' ),
	'plural'                    => array( '0', 'جمع:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'نشانی‌کامل:', 'نشانی_کامل:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'نشانی‌کامل‌کد:', 'نشانی_کامل_کد:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'نشانی_استاندارد:', 'نشانی‌استاندارد:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', 'نشانی_استاندارد_کد:', 'نشانی‌استانداردکد:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', 'ابتداکوچک:', 'ابتدا_کوچک:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ابتدابزرگ:', 'ابتدا_بزرگ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'ک:', 'LC:' ),
	'uc'                        => array( '0', 'ب:', 'UC:' ),
	'raw'                       => array( '0', 'خام:', 'RAW:' ),
	'displaytitle'              => array( '1', 'عنوان‌ظاهری', 'عنوان_ظاهری', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'ن', 'R' ),
	'nocommafysuffix'           => array( '0', 'جداکننده‌خیر', 'NOSEP' ),
	'newsectionlink'            => array( '1', '__بخش‌جدید__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__بی‌پیوندبخش__', '__بی‌پیوند‌بخش‌جدید__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'نسخه‌کنونی', 'نسخه_کنونی', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'کدنشانی:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'کدلنگر:', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'زمان‌یونیکسی', 'زمان_یونیکسی', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'زمان‌یونیکسی‌محلی', 'زمان_یونیکسی_محلی', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'علامت‌جهت', 'علامت_جهت', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#زبان:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'زبان‌محتوا', 'زبان_محتوا', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'صفحه‌درفضای‌نام:', 'صفحه_در_فضای_نام:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'تعدادمدیران', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'آرایش‌عدد', 'آرایش_عدد', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'لبه‌چپ', 'لبه_چپ', 'PADLEFT' ),
	'padright'                  => array( '0', 'لبه‌راست', 'لبه_راست', 'PADRIGHT' ),
	'special'                   => array( '0', 'ویژه', 'special' ),
	'speciale'                  => array( '0', 'ویژه_ای', 'speciale' ),
	'defaultsort'               => array( '1', 'ترتیب:', 'ترتیب‌پیش‌فرض:', 'ترتیب_پیش_فرض:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'مسیرپرونده:', 'مسیر_پرونده:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'برچسب', 'tag' ),
	'hiddencat'                 => array( '1', '__رده‌پنهان__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'صفحه‌دررده', 'صفحه_در_رده', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'اندازه‌صفحه', 'اندازه_صفحه', 'PAGESIZE' ),
	'index'                     => array( '1', '__نمایه__', '__INDEX__' ),
	'noindex'                   => array( '1', '__بی‌نمایه__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'تعداددرگروه', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__تغییرمسیرثابت__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'سطح‌حفاطت', 'سطح_حفاظت', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'آرایش‌تاریخ', 'آرایش_تاریخ', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'مسیر', 'PATH' ),
	'url_wiki'                  => array( '0', 'ویکی', 'WIKI' ),
	'url_query'                 => array( '0', 'دستور', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', 'بدون‌خطا', 'بدون_خطا', 'noerror' ),
	'defaultsort_noreplace'     => array( '0', 'جایگزین‌نکن', 'جایگزین_نکن', 'noreplace' ),
	'pagesincategory_all'       => array( '0', 'همه', 'all' ),
	'pagesincategory_pages'     => array( '0', 'صفحات', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'زیررده‌ها', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'پرونده‌ها', 'files' ),
);

$digitTransformTable = array(
	'0' => '۰', # &#x06f0;
	'1' => '۱', # &#x06f1;
	'2' => '۲', # &#x06f2;
	'3' => '۳', # &#x06f3;
	'4' => '۴', # &#x06f4;
	'5' => '۵', # &#x06f5;
	'6' => '۶', # &#x06f6;
	'7' => '۷', # &#x06f7;
	'8' => '۸', # &#x06f8;
	'9' => '۹', # &#x06f9;
	'%' => '٪', # &#x066a;
	'.' => '٫', # &#x066b; wrong table?
	',' => '٬', # &#x066c;
);

/**
 * A list of date format preference keys which can be selected in user
 * preferences. New preference keys can be added, provided they are supported
 * by the language class's timeanddate(). Only the 5 keys listed below are
 * supported by the wikitext converter (DateFormatter.php).
 *
 * The special key "default" is an alias for either dmy or mdy depending on
 * $wgAmericanDates
 */
$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'persian',
	'hebrew',
	'ISO 8601',
);

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy or mdy';

/**
 * Associative array mapping old numeric date formats, which may still be
 * stored in user preferences, to the new string formats.
 */
$datePreferenceMigrationMap = array(
	'default',
	'mdy',
	'dmy',
	'ymd'
);

/**
 * These are formats for dates generated by MediaWiki (as opposed to the wikitext
 * DateFormatter). Documentation for the format string can be found in
 * Language.php, search for sprintfDate.
 *
 * This array is automatically inherited by all subclasses. Individual keys can be
 * overridden.
 */
$dateFormats = array(
	# Please be cautious not to delete the invisible RLM from the beginning of the strings.
	'mdy time' => '‏H:i',
	'mdy date' => '‏n/j/Y میلادی',
	'mdy both' => '‏n/j/Y میلادی، ساعت H:i',

	'dmy time' => '‏H:i',
	'dmy date' => '‏j xg Y',
	'dmy both' => '‏j xg Y، ساعت H:i',

	'ymd time' => '‏H:i',
	'ymd date' => '‏Y/n/j میلادی',
	'ymd both' => '‏Y/n/j میلادی، ساعت H:i',

	'persian time' => '‏H:i',
	'persian date' => '‏xij xiF xiY',
	'persian both' => '‏xij xiF xiY، ساعت H:i',

	'hebrew time' => '‏H:i',
	'hebrew date' => '‏xij xjF xjY',
	'hebrew both' => '‏H:i, xij xjF xjY',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

# Harakat are intentionally not included in the linkTrail. Their addition should
# take place after enough tests.
$linkTrail = "/^([ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیآأئؤة‌]+)(.*)$/sDu";

$imageFiles = array(
	'button-bold'     => 'fa/button_bold.png',
	'button-italic'   => 'fa/button_italic.png',
	'button-link'     => 'fa/button_link.png',
	'button-headline' => 'fa/button_headline.png',
	'button-nowiki'   => 'fa/button_nowiki.png',
);

