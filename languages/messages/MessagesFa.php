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

$namespaceNames = [
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
];

$namespaceAliases = [
	'رسانه' => NS_MEDIA,
	'رسانه‌ای' => NS_MEDIA,
	'تصویر' => NS_FILE,
	'بحث_تصویر' => NS_FILE_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'کاربران_فعال' ],
	'Allmessages'               => [ 'تمام_پیغام‌ها' ],
	'AllMyUploads'              => [ 'همهٔ_بارگذاری‌های_من', 'همه_بارگذاری‌های_من' ],
	'Allpages'                  => [ 'تمام_صفحه‌ها' ],
	'ApiHelp'                   => [ 'راهنمای_ای‌پی‌آی' ],
	'Ancientpages'              => [ 'صفحه‌های_قدیمی' ],
	'Badtitle'                  => [ 'عنوان_بد' ],
	'Blankpage'                 => [ 'صفحهٔ_خالی', 'صفحه_خالی' ],
	'Block'                     => [ 'بستن_نشانی_آی‌پی' ],
	'Booksources'               => [ 'منابع_کتاب' ],
	'BrokenRedirects'           => [ 'تغییرمسیرهای_خراب' ],
	'Categories'                => [ 'رده‌ها' ],
	'ChangeEmail'               => [ 'تغییر_ایمیل', 'تغییر_رایانامه' ],
	'ChangePassword'            => [ 'از_نو_کردن_گذرواژه' ],
	'ComparePages'              => [ 'مقایسهٔ_صفحات' ],
	'Confirmemail'              => [ 'تأیید_ایمیل', 'تأیید_رایانامه' ],
	'Contributions'             => [ 'مشارکت‌ها' ],
	'CreateAccount'             => [ 'ایجاد_حساب_کاربری' ],
	'Deadendpages'              => [ 'صفحه‌های_بن‌بست' ],
	'DeletedContributions'      => [ 'مشارکت‌های_حذف_شده' ],
	'Diff'                      => [ 'تفاوت' ],
	'DoubleRedirects'           => [ 'تغییرمسیرهای_دوتایی' ],
	'EditWatchlist'             => [ 'ویرایش_فهرست_پی‌گیری‌ها' ],
	'Emailuser'                 => [ 'ایمیل_به_کاربر', 'نامه_به_کاربر' ],
	'ExpandTemplates'           => [ 'گسترش_الگوها' ],
	'Export'                    => [ 'برون‌بری_صفحه' ],
	'Fewestrevisions'           => [ 'کمترین_نسخه' ],
	'FileDuplicateSearch'       => [ 'جستجوی_پروندهٔ_تکراری' ],
	'Filepath'                  => [ 'مسیر_پرونده' ],
	'Import'                    => [ 'درون‌ریزی_صفحه' ],
	'Invalidateemail'           => [ 'باطل‌کردن_ایمیل', 'باطل‌کردن_رایانامه' ],
	'JavaScriptTest'            => [ 'تست_جاوااسکریپت' ],
	'BlockList'                 => [ 'فهرست_بسته‌شده‌ها', 'فهرست_بستن_نشانی_آی‌پی' ],
	'LinkSearch'                => [ 'جستجوی_پیوند' ],
	'Listadmins'                => [ 'فهرست_مدیران' ],
	'Listbots'                  => [ 'فهرست_ربات‌ها' ],
	'Listfiles'                 => [ 'فهرست_پرونده‌ها', 'فهرست_تصاویر' ],
	'Listgrouprights'           => [ 'اختیارات_گروه‌های_کاربری' ],
	'Listredirects'             => [ 'فهرست_تغییرمسیرها' ],
	'ListDuplicatedFiles'       => [ 'فهرست_پرونده‌های_تکراری' ],
	'Listusers'                 => [ 'فهرست_کاربران' ],
	'Lockdb'                    => [ 'قفل‌کردن_پایگاه_داده‌ها' ],
	'Log'                       => [ 'سیاهه‌ها' ],
	'Lonelypages'               => [ 'صفحه‌های_یتیم' ],
	'Longpages'                 => [ 'صفحه‌های_بلند' ],
	'MediaStatistics'           => [ 'آمار_رسانه‌ها' ],
	'MergeHistory'              => [ 'ادغام_تاریخچه' ],
	'MIMEsearch'                => [ 'جستجوی_MIME' ],
	'Mostcategories'            => [ 'بیشترین_رده' ],
	'Mostimages'                => [ 'بیشترین_تصویر' ],
	'Mostinterwikis'            => [ 'بیشترین_میان‌ویکی' ],
	'Mostlinked'                => [ 'بیشترین_پیوند' ],
	'Mostlinkedcategories'      => [ 'رده_با_بیشترین_پیوند' ],
	'Mostlinkedtemplates'       => [ 'الگو_با_بیشترین_پیوند' ],
	'Mostrevisions'             => [ 'بیشترین_نسخه' ],
	'Movepage'                  => [ 'انتقال_صفحه' ],
	'Mycontributions'           => [ 'مشارکت‌های_من' ],
	'MyLanguage'                => [ 'زبان‌های_من' ],
	'Mypage'                    => [ 'صفحهٔ_من', 'صفحه_من' ],
	'Mytalk'                    => [ 'بحث_من' ],
	'Myuploads'                 => [ 'بارگذاری‌های_من' ],
	'Newimages'                 => [ 'تصاویر_جدید' ],
	'Newpages'                  => [ 'صفحه‌های_تازه' ],
	'PagesWithProp'             => [ 'صفحه‌های_با_خاصیت' ],
	'PageLanguage'              => [ 'زبان_صفحه' ],
	'PasswordReset'             => [ 'بازنشاندن_گذرواژه' ],
	'PermanentLink'             => [ 'پیوند_دائمی' ],
	'Preferences'               => [ 'ترجیحات' ],
	'Prefixindex'               => [ 'نمایه_پیشوندی' ],
	'Protectedpages'            => [ 'صفحه‌های_محافظت‌شده' ],
	'Protectedtitles'           => [ 'عنوان‌های_محافظت‌شده' ],
	'Randompage'                => [ 'صفحهٔ_تصادفی' ],
	'RandomInCategory'          => [ 'تصادفی_در_رده' ],
	'Randomredirect'            => [ 'تغییرمسیر_تصادفی' ],
	'Randomrootpage'            => [ 'صفحهٔ_پایهٔ_تصادفی' ],
	'Recentchanges'             => [ 'تغییرات_اخیر' ],
	'Recentchangeslinked'       => [ 'تغییرات_مرتبط' ],
	'Redirect'                  => [ 'تغییرمسیر' ],
	'ResetTokens'               => [ 'بازنشانی_نشانه‌ها' ],
	'Revisiondelete'            => [ 'حذف_نسخه' ],
	'RunJobs'                   => [ 'اجرای_کارها' ],
	'Search'                    => [ 'جستجو' ],
	'Shortpages'                => [ 'صفحه‌های_کوتاه' ],
	'Specialpages'              => [ 'صفحه‌های_ویژه' ],
	'Statistics'                => [ 'آمار' ],
	'Tags'                      => [ 'برچسب‌ها' ],
	'TrackingCategories'        => [ 'رده‌های_ردیابی' ],
	'Unblock'                   => [ 'باز_کردن' ],
	'Uncategorizedcategories'   => [ 'رده‌های_رده‌بندی‌نشده' ],
	'Uncategorizedimages'       => [ 'تصویرهای_رده‌بندی‌نشده' ],
	'Uncategorizedpages'        => [ 'صفحه‌های_رده‌بندی‌نشده' ],
	'Uncategorizedtemplates'    => [ 'الگوهای_رده‌بندی‌نشده' ],
	'Undelete'                  => [ 'احیای_صفحهٔ_حذف‌شده' ],
	'Unlockdb'                  => [ 'قفل‌گشایی_پایگاه_داده‌ها' ],
	'Unusedcategories'          => [ 'رده‌های_استفاده_نشده' ],
	'Unusedimages'              => [ 'تصاویر_استفاده_نشده' ],
	'Unusedtemplates'           => [ 'الگوهای_استفاده_نشده' ],
	'Unwatchedpages'            => [ 'صفحه‌های_پی‌گیری_نشده' ],
	'Upload'                    => [ 'بارگذاری_پرونده' ],
	'UploadStash'               => [ 'بارگذاری_انبوه' ],
	'Userlogin'                 => [ 'ورود_به_سامانه' ],
	'Userlogout'                => [ 'خروج_از_سامانه' ],
	'Userrights'                => [ 'اختیارات_کاربر' ],
	'Version'                   => [ 'نسخه' ],
	'Wantedcategories'          => [ 'رده‌های_مورد_نیاز' ],
	'Wantedfiles'               => [ 'پرونده‌های_مورد_نیاز' ],
	'Wantedpages'               => [ 'صفحه‌های_مورد_نیاز' ],
	'Wantedtemplates'           => [ 'الگوهای_مورد_نیاز' ],
	'Watchlist'                 => [ 'فهرست_پی‌گیری' ],
	'Whatlinkshere'             => [ 'پیوند_به_این_صفحه' ],
	'Withoutinterwiki'          => [ 'بدون_میان‌ویکی' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#تغییر_مسیر', '#تغییرمسیر', '#REDIRECT' ],
	'notoc'                     => [ '0', '__بی‌فهرست__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__بی‌نگارخانه__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__بافهرست__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__فهرست__', '__TOC__' ],
	'noeditsection'             => [ '0', '__بی‌بخش__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'ماه‌کنونی۲', 'ماه_کنونی۲', 'CURRENTMONTH', 'CURRENTMONTH2' ],
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
	'localtime'                 => [ '1', 'زمان‌محلی', 'زمان_محلی', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'تعدادصفحه‌ها', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'تعدادکاربران', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'کاربران‌فعال', 'کاربران_فعال', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'نام‌صفحه‌کد', 'نام_صفحه_کد', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'فضای‌نام‌کد', 'فضای_نام_کد', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'شماره_فضای_نام', 'شماره‌فضای‌نام', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'فضای‌بحث‌کد', 'فضای_بحث_کد', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'فضای‌موضوع‌کد', 'فضای‌مقاله‌کد', 'فضای_موضوع_کد', 'فضای_مقاله_کد', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'نام‌کامل‌صفحه', 'نام_کامل_صفحه', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'نام‌کامل‌صفحه‌کد', 'نام_کامل_صفحه_کد', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'نام‌زیرصفحه', 'نام_زیرصفحه', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'نام‌زیرصفحه‌کد', 'نام_زیرصفحه_کد', 'SUBPAGENAMEE' ],
	'rootpagename'              => [ '1', 'نام_صفحه_ریشه', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', 'نام_صفحه_ریشه_ای', 'ROOTPAGENAMEE' ],
	'basepagename'              => [ '1', 'نام‌صفحه‌مبنا', 'نام_صفحه_مبنا', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'نام‌صفحه‌مبناکد', 'نام_صفحه_مبنا_کد', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'نام‌صفحه‌بحث', 'نام_صفحه_بحث', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'نام‌صفحه‌بحث‌کد', 'نام_صفحه_بحث_کد', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'نام‌صفحه‌موضوع', 'نام‌صفحه‌مقاله', 'نام_صفحه_موضوع', 'نام_صفحه_مقاله', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'نام‌صفحه‌موضوع‌کد', 'نام‌صفحه‌مقاله‌کد', 'نام_صفحه_موضوع_کد', 'نام_صفحه_مقاله_کد', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'پیغام:', 'پ:', 'MSG:' ],
	'subst'                     => [ '0', 'جایگزین:', 'جا:', 'SUBST:' ],
	'safesubst'                 => [ '0', 'جایگزین_امن:', 'جام:', 'SAFESUBST:' ],
	'msgnw'                     => [ '0', 'پیغام‌بی‌بسط:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'بندانگشتی', 'انگشتی', 'انگشتدان', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'بندانگشتی=$1', 'انگشتدان=$1', 'انگشتی=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'راست', 'right' ],
	'img_left'                  => [ '1', 'چپ', 'left' ],
	'img_none'                  => [ '1', 'هیچ', 'none' ],
	'img_width'                 => [ '1', '$1پیکسل', '$1px' ],
	'img_center'                => [ '1', 'وسط', 'center', 'centre' ],
	'img_framed'                => [ '1', 'قاب', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'بی‌قاب', 'بیقاب', 'بی_قاب', 'frameless' ],
	'img_lang'                  => [ '1', 'زبان=$1', 'lang=$1' ],
	'img_page'                  => [ '1', 'صفحه=$1', 'صفحه_$1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'ایستاده', 'ایستاده=$1', 'ایستاده_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'حاشیه', 'border' ],
	'img_baseline'              => [ '1', 'همکف', 'baseline' ],
	'img_sub'                   => [ '1', 'زیر', 'sub' ],
	'img_super'                 => [ '1', 'زبر', 'super', 'sup' ],
	'img_top'                   => [ '1', 'بالا', 'top' ],
	'img_text_top'              => [ '1', 'متن-بالا', 'text-top' ],
	'img_middle'                => [ '1', 'میانه', 'middle' ],
	'img_bottom'                => [ '1', 'پایین', 'bottom' ],
	'img_text_bottom'           => [ '1', 'متن-پایین', 'text-bottom' ],
	'img_link'                  => [ '1', 'پیوند=$1', 'link=$1' ],
	'img_alt'                   => [ '1', 'جایگزین=$1', 'alt=$1' ],
	'img_class'                 => [ '1', 'کلاس=$1', 'class=$1' ],
	'int'                       => [ '0', 'ترجمه:', 'INT:' ],
	'sitename'                  => [ '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ],
	'ns'                        => [ '0', 'فن:', 'NS:' ],
	'nse'                       => [ '0', 'فنک:', 'NSE:' ],
	'localurl'                  => [ '0', 'نشانی:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'نشانی‌کد:', 'نشانی_کد:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', 'مسیرمقاله', 'مسیر_مقاله', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', 'شناسه_صفحه', 'PAGEID' ],
	'server'                    => [ '0', 'سرور', 'کارساز', 'SERVER' ],
	'servername'                => [ '0', 'نام‌کارساز', 'نام_کارساز', 'نام‌سرور', 'نام_سرور', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'مسیرسند', 'مسیر_سند', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'مسیرسبک', 'مسیر_سبک', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'دستورزبان:', 'دستور_زبان:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'جنسیت:', 'جنس:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__عنوان‌تبدیل‌نشده__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__محتواتبدیل‌نشده__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'هفته', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'روزهفته', 'روز_هفته', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'هفته‌محلی', 'هفته_محلی', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'روزهفته‌محلی', 'روز_هفته_محلی', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'نسخه', 'شماره‌نسخه', 'شماره_نسخه', 'REVISIONID' ],
	'revisionday'               => [ '1', 'روزنسخه', 'روز_نسخه', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'روزنسخه۲', 'روز_نسخه۲', 'روز_نسخه_۲', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'ماه‌نسخه', 'ماه_نسخه', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'ماه‌نسخه۱', 'ماه_نسخه_۱', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'سال‌نسخه', 'سال_نسخه', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'زمان‌یونیکسی‌نسخه', 'زمان‌نسخه', 'زمان_یونیکسی_نسخه', 'زمان_نسخه', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'کاربرنسخه', 'کاربر_نسخه', 'REVISIONUSER' ],
	'revisionsize'              => [ '1', 'اندازهٔ‌نسخه', 'اندازهٔ_نسخه', 'REVISIONSIZE' ],
	'plural'                    => [ '0', 'جمع:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'نشانی‌کامل:', 'نشانی_کامل:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'نشانی‌کامل‌کد:', 'نشانی_کامل_کد:', 'FULLURLE:' ],
	'canonicalurl'              => [ '0', 'نشانی_استاندارد:', 'نشانی‌استاندارد:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'نشانی_استاندارد_کد:', 'نشانی‌استانداردکد:', 'CANONICALURLE:' ],
	'lcfirst'                   => [ '0', 'ابتداکوچک:', 'ابتدا_کوچک:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'ابتدابزرگ:', 'ابتدا_بزرگ:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'ک:', 'LC:' ],
	'uc'                        => [ '0', 'ب:', 'UC:' ],
	'raw'                       => [ '0', 'خام:', 'RAW:' ],
	'displaytitle'              => [ '1', 'عنوان‌ظاهری', 'عنوان_ظاهری', 'DISPLAYTITLE' ],
	'rawsuffix'                 => [ '1', 'ن', 'R' ],
	'nocommafysuffix'           => [ '0', 'جداکننده‌خیر', 'NOSEP' ],
	'newsectionlink'            => [ '1', '__بخش‌جدید__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__بی‌پیوندبخش__', '__بی‌پیوند‌بخش‌جدید__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'نسخه‌کنونی', 'نسخه_کنونی', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'کدنشانی:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'کدلنگر:', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'زمان‌یونیکسی', 'زمان_یونیکسی', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'زمان‌یونیکسی‌محلی', 'زمان_یونیکسی_محلی', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'علامت‌جهت', 'علامت_جهت', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#زبان:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'زبان‌محتوا', 'زبان_محتوا', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'صفحه‌درفضای‌نام:', 'صفحه_در_فضای_نام:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'تعدادمدیران', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'آرایش‌عدد', 'آرایش_عدد', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'لبه‌چپ', 'لبه_چپ', 'PADLEFT' ],
	'padright'                  => [ '0', 'لبه‌راست', 'لبه_راست', 'PADRIGHT' ],
	'special'                   => [ '0', 'ویژه', 'special' ],
	'speciale'                  => [ '0', 'ویژه_ای', 'speciale' ],
	'defaultsort'               => [ '1', 'ترتیب:', 'ترتیب‌پیش‌فرض:', 'ترتیب_پیش_فرض:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'مسیرپرونده:', 'مسیر_پرونده:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'برچسب', 'tag' ],
	'hiddencat'                 => [ '1', '__رده‌پنهان__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'صفحه‌دررده', 'صفحه_در_رده', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'اندازه‌صفحه', 'اندازه_صفحه', 'PAGESIZE' ],
	'index'                     => [ '1', '__نمایه__', '__INDEX__' ],
	'noindex'                   => [ '1', '__بی‌نمایه__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'تعداددرگروه', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__تغییرمسیرثابت__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'سطح‌حفاطت', 'سطح_حفاظت', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'آرایش‌تاریخ', 'آرایش_تاریخ', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', 'مسیر', 'PATH' ],
	'url_wiki'                  => [ '0', 'ویکی', 'WIKI' ],
	'url_query'                 => [ '0', 'دستور', 'QUERY' ],
	'defaultsort_noerror'       => [ '0', 'بدون‌خطا', 'بدون_خطا', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', 'جایگزین‌نکن', 'جایگزین_نکن', 'noreplace' ],
	'pagesincategory_all'       => [ '0', 'همه', 'all' ],
	'pagesincategory_pages'     => [ '0', 'صفحات', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'زیررده‌ها', 'subcats' ],
	'pagesincategory_files'     => [ '0', 'پرونده‌ها', 'files' ],
];

$digitTransformTable = [
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
];

/**
 * A list of date format preference keys which can be selected in user
 * preferences. New preference keys can be added, provided they are supported
 * by the language class's timeanddate(). Only the 5 keys listed below are
 * supported by the wikitext converter (DateFormatter.php).
 *
 * The special key "default" is an alias for either dmy or mdy depending on
 * $wgAmericanDates
 */
$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'persian',
	'hebrew',
	'ISO 8601',
];

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
$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

/**
 * These are formats for dates generated by MediaWiki (as opposed to the wikitext
 * DateFormatter). Documentation for the format string can be found in
 * Language.php, search for sprintfDate.
 *
 * This array is automatically inherited by all subclasses. Individual keys can be
 * overridden.
 */
$dateFormats = [
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
];

# Harakat are intentionally not included in the linkTrail. Their addition should
# take place after enough tests.
$linkTrail = "/^([ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیآأئؤة‌]+)(.*)$/sDu";

$imageFiles = [
	'button-bold'     => 'fa/button_bold.png',
	'button-italic'   => 'fa/button_italic.png',
	'button-link'     => 'fa/button_link.png',
	'button-headline' => 'fa/button_headline.png',
	'button-nowiki'   => 'fa/button_nowiki.png',
];
