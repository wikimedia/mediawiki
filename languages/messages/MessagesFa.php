<?php
/** Persian (فارسی)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'کاربران_فعال' ],
	'Allmessages'               => [ 'تمام_پیغام‌ها' ],
	'AllMyUploads'              => [ 'همهٔ_بارگذاری‌های_من', 'همه_بارگذاری‌های_من' ],
	'Allpages'                  => [ 'تمام_صفحه‌ها' ],
	'Ancientpages'              => [ 'صفحه‌های_قدیمی' ],
	'ApiHelp'                   => [ 'راهنمای_ای‌پی‌آی' ],
	'ApiSandbox'                => [ 'آزمایش_رابط_برنامه‌نویسی' ],
	'AuthenticationPopupSuccess' => [ 'پاپ‌آپ_موفقیت_اصالت‌سنجی' ],
	'AutoblockList'             => [ 'فهرست_بستن‌های_خودکار', 'فهرست_قطع‌دسترسی‌های_خودکار' ],
	'Badtitle'                  => [ 'عنوان_بد' ],
	'Blankpage'                 => [ 'صفحهٔ_خالی', 'صفحه_خالی' ],
	'Block'                     => [ 'بستن_نشانی_آی‌پی' ],
	'BlockList'                 => [ 'فهرست_بستن‌ها', 'فهرست_قطع_دسترسی‌ها' ],
	'Booksources'               => [ 'منابع_کتاب' ],
	'BotPasswords'              => [ 'گذرواژه‌های_ربات' ],
	'BrokenRedirects'           => [ 'تغییرمسیرهای_خراب' ],
	'Categories'                => [ 'رده‌ها' ],
	'ChangeContentModel'        => [ 'تغییر_مدل_محتوا' ],
	'ChangeCredentials'         => [ 'تغییر_اعتبارنامه‌ها' ],
	'ChangeEmail'               => [ 'تغییر_ایمیل', 'تغییر_رایانامه' ],
	'ChangePassword'            => [ 'از_نو_کردن_گذرواژه' ],
	'ComparePages'              => [ 'مقایسهٔ_صفحات' ],
	'Confirmemail'              => [ 'تأیید_ایمیل', 'تأیید_رایانامه' ],
	'Contribute'                => [ 'مشارکت' ],
	'Contributions'             => [ 'مشارکت‌ها' ],
	'CreateAccount'             => [ 'ایجاد_حساب_کاربری' ],
	'Deadendpages'              => [ 'صفحه‌های_بن‌بست' ],
	'DeletedContributions'      => [ 'مشارکت‌های_حذف_شده' ],
	'DeletePage'                => [ 'حذف_صفحه', 'حذف' ],
	'Diff'                      => [ 'تفاوت' ],
	'DoubleRedirects'           => [ 'تغییرمسیرهای_دوتایی' ],
	'EditPage'                  => [ 'ویرایش_صفحه', 'ویرایش' ],
	'EditRecovery'              => [ 'بازیابی_ویرایش' ],
	'EditTags'                  => [ 'ویرایش_برچسب‌ها' ],
	'EditWatchlist'             => [ 'ویرایش_فهرست_پی‌گیری‌ها' ],
	'Emailuser'                 => [ 'ایمیل_به_کاربر', 'نامه_به_کاربر' ],
	'ExpandTemplates'           => [ 'گسترش_الگوها' ],
	'Export'                    => [ 'برون‌بری_صفحه' ],
	'Fewestrevisions'           => [ 'کمترین_نسخه' ],
	'FileDuplicateSearch'       => [ 'جستجوی_پروندهٔ_تکراری' ],
	'Filepath'                  => [ 'مسیر_پرونده' ],
	'GoToInterwiki'             => [ 'برو_به_میان‌ویکی', 'میان‌ویکی' ],
	'Import'                    => [ 'درون‌ریزی_صفحه' ],
	'Interwiki'                 => [ 'میان‌ویکی' ],
	'Invalidateemail'           => [ 'باطل‌کردن_ایمیل', 'باطل‌کردن_رایانامه' ],
	'JavaScriptTest'            => [ 'تست_جاوااسکریپت' ],
	'LinkAccounts'              => [ 'اتصال_حساب‌ها' ],
	'LinkSearch'                => [ 'جستجوی_پیوند' ],
	'Listadmins'                => [ 'فهرست_مدیران' ],
	'Listbots'                  => [ 'فهرست_ربات‌ها' ],
	'ListDuplicatedFiles'       => [ 'فهرست_پرونده‌های_تکراری' ],
	'Listfiles'                 => [ 'فهرست_پرونده‌ها', 'فهرست_تصاویر' ],
	'Listgrants'                => [ 'فهرست_دسترسی‌های_اعطاشدنی' ],
	'Listgrouprights'           => [ 'اختیارات_گروه‌های_کاربری' ],
	'Listredirects'             => [ 'فهرست_تغییرمسیرها' ],
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
	'Mute'                      => [ 'ساکت' ],
	'Mycontributions'           => [ 'مشارکت‌های_من' ],
	'MyLanguage'                => [ 'زبان‌های_من' ],
	'Mylog'                     => [ 'سیاههٔ_من' ],
	'Mypage'                    => [ 'صفحهٔ_من', 'صفحه_من' ],
	'Mytalk'                    => [ 'بحث_من' ],
	'Myuploads'                 => [ 'بارگذاری‌های_من' ],
	'Newimages'                 => [ 'تصاویر_جدید' ],
	'Newpages'                  => [ 'صفحه‌های_تازه' ],
	'NewSection'                => [ 'بخش_جدید' ],
	'PageData'                  => [ 'داده_صفحه' ],
	'PageHistory'               => [ 'تاریخچه_صفحه', 'تاریخچه' ],
	'PageInfo'                  => [ 'اطلاعات_صفحه', 'اطلاعات' ],
	'PageLanguage'              => [ 'زبان_صفحه' ],
	'PagesWithProp'             => [ 'صفحه‌های_با_خاصیت' ],
	'PasswordPolicies'          => [ 'سیاست‌های_گذرواژه' ],
	'PasswordReset'             => [ 'بازنشاندن_گذرواژه' ],
	'PermanentLink'             => [ 'پیوند_دائمی' ],
	'Preferences'               => [ 'ترجیحات' ],
	'Prefixindex'               => [ 'نمایه_پیشوندی' ],
	'Protectedpages'            => [ 'صفحه‌های_محافظت‌شده' ],
	'Protectedtitles'           => [ 'عنوان‌های_محافظت‌شده' ],
	'ProtectPage'               => [ 'محافظت_صفحه', 'محافظت' ],
	'Purge'                     => [ 'پاکسازی' ],
	'RandomInCategory'          => [ 'تصادفی_در_رده' ],
	'Randompage'                => [ 'صفحهٔ_تصادفی' ],
	'Randomredirect'            => [ 'تغییرمسیر_تصادفی' ],
	'Randomrootpage'            => [ 'صفحهٔ_پایهٔ_تصادفی' ],
	'Recentchanges'             => [ 'تغییرات_اخیر' ],
	'Recentchangeslinked'       => [ 'تغییرات_مرتبط' ],
	'Redirect'                  => [ 'تغییرمسیر' ],
	'RemoveCredentials'         => [ 'حذف_اعتبارنامه‌ها' ],
	'Renameuser'                => [ 'تغییر_نام_کاربر' ],
	'ResetTokens'               => [ 'بازنشانی_نشانه‌ها' ],
	'Revisiondelete'            => [ 'حذف_نسخه' ],
	'RunJobs'                   => [ 'اجرای_کارها' ],
	'Search'                    => [ 'جستجو' ],
	'Shortpages'                => [ 'صفحه‌های_کوتاه' ],
	'Specialpages'              => [ 'صفحه‌های_ویژه' ],
	'Statistics'                => [ 'آمار' ],
	'Tags'                      => [ 'برچسب‌ها' ],
	'TalkPage'                  => [ 'صفحهٔ_بحث' ],
	'TrackingCategories'        => [ 'رده‌های_ردیابی' ],
	'Unblock'                   => [ 'باز_کردن' ],
	'Uncategorizedcategories'   => [ 'رده‌های_رده‌بندی‌نشده' ],
	'Uncategorizedimages'       => [ 'تصویرهای_رده‌بندی‌نشده' ],
	'Uncategorizedpages'        => [ 'صفحه‌های_رده‌بندی‌نشده' ],
	'Uncategorizedtemplates'    => [ 'الگوهای_رده‌بندی‌نشده' ],
	'Undelete'                  => [ 'احیای_صفحهٔ_حذف‌شده' ],
	'UnlinkAccounts'            => [ 'جداکردن_حساب‌ها' ],
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'کدلنگر:', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'مسیرمقاله', 'مسیر_مقاله', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'نام‌صفحه‌مبنا', 'نام_صفحه_مبنا', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'نام‌صفحه‌مبناکد', 'نام_صفحه_مبنا_کد', 'BASEPAGENAMEE' ],
	'canonicalurl'              => [ '0', 'نشانی_استاندارد:', 'نشانی‌استاندارد:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'نشانی_استاندارد_کد:', 'نشانی‌استانداردکد:', 'CANONICALURLE:' ],
	'contentlanguage'           => [ '1', 'زبان‌محتوا', 'زبان_محتوا', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'روز', 'روزکنونی', 'روز_کنونی', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'روز۲', 'روز_۲', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'نام‌روز', 'نام_روز', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'روزهفته', 'روز_هفته', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'ساعت', 'ساعت‌کنونی', 'ساعت_کنونی', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'ماه‌کنونی۲', 'ماه_کنونی۲', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'ماه۱', 'ماه‌کنونی۱', 'ماه_کنونی۱', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'مخفف‌نام‌ماه', 'مخفف_نام_ماه', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'نام‌ماه', 'نام_ماه', 'نام‌ماه‌کنونی', 'نام_ماه_کنونی', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'نام‌ماه‌اضافه', 'نام_ماه_اضافه', 'نام‌ماه‌کنونی‌اضافه', 'نام_ماه_کنونی_اضافه', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'زمان‌کنونی', 'زمان_کنونی', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'زمان‌یونیکسی', 'زمان_یونیکسی', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'نسخه‌کنونی', 'نسخه_کنونی', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'هفته', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'سال', 'سال‌کنونی', 'سال_کنونی', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'ترتیب:', 'ترتیب‌پیش‌فرض:', 'ترتیب_پیش_فرض:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'       => [ '0', 'بدون‌خطا', 'بدون_خطا', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', 'جایگزین‌نکن', 'جایگزین_نکن', 'noreplace' ],
	'directionmark'             => [ '1', 'علامت‌جهت', 'علامت_جهت', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'عنوان‌ظاهری', 'عنوان_ظاهری', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'مسیرپرونده:', 'مسیر_پرونده:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__بافهرست__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'آرایش‌تاریخ', 'آرایش_تاریخ', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'آرایش‌عدد', 'آرایش_عدد', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'نام‌کامل‌صفحه', 'نام_کامل_صفحه', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'نام‌کامل‌صفحه‌کد', 'نام_کامل_صفحه_کد', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'نشانی‌کامل:', 'نشانی_کامل:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'نشانی‌کامل‌کد:', 'نشانی_کامل_کد:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'جنسیت:', 'جنس:', 'GENDER:' ],
	'grammar'                   => [ '0', 'دستورزبان:', 'دستور_زبان:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__رده‌پنهان__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'جایگزین=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', 'همکف', 'baseline' ],
	'img_border'                => [ '1', 'حاشیه', 'border' ],
	'img_bottom'                => [ '1', 'پایین', 'bottom' ],
	'img_center'                => [ '1', 'وسط', 'center', 'centre' ],
	'img_class'                 => [ '1', 'کلاس=$1', 'class=$1' ],
	'img_framed'                => [ '1', 'قاب', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'بی‌قاب', 'بیقاب', 'بی_قاب', 'frameless' ],
	'img_lang'                  => [ '1', 'زبان=$1', 'lang=$1' ],
	'img_left'                  => [ '1', 'چپ', 'left' ],
	'img_link'                  => [ '1', 'پیوند=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'بندانگشتی=$1', 'انگشتدان=$1', 'انگشتی=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'میانه', 'middle' ],
	'img_none'                  => [ '1', 'هیچ', 'none' ],
	'img_page'                  => [ '1', 'صفحه=$1', 'صفحه_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'راست', 'right' ],
	'img_sub'                   => [ '1', 'زیر', 'sub' ],
	'img_super'                 => [ '1', 'زبر', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'متن-پایین', 'text-bottom' ],
	'img_text_top'              => [ '1', 'متن-بالا', 'text-top' ],
	'img_thumbnail'             => [ '1', 'بندانگشتی', 'انگشتی', 'انگشتدان', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'بالا', 'top' ],
	'img_upright'               => [ '1', 'ایستاده', 'ایستاده=$1', 'ایستاده_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1پیکسل', '$1px' ],
	'index'                     => [ '1', '__نمایه__', '__INDEX__' ],
	'int'                       => [ '0', 'ترجمه:', 'INT:' ],
	'language'                  => [ '0', '#زبان', '#LANGUAGE' ],
	'lc'                        => [ '0', 'ک:', 'LC:' ],
	'lcfirst'                   => [ '0', 'ابتداکوچک:', 'ابتدا_کوچک:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'روزمحلی', 'روز_محلی', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'روزمحلی۲', 'روز_محلی_۲', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'نام‌روزمحلی', 'نام_روز_محلی', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'روزهفته‌محلی', 'روز_هفته_محلی', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'ماه‌محلی', 'ماه_محلی', 'ماه‌محلی۲', 'ماه_محلی۲', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'ماه‌محلی۱', 'ماه_محلی۱', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'مخفف‌ماه‌محلی', 'مخفف_ماه_محلی', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'نام‌ماه‌محلی', 'نام_ماه_محلی', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'نام‌ماه‌محلی‌اضافه', 'نام_ماه_محلی_اضافه', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'زمان‌محلی', 'زمان_محلی', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'زمان‌یونیکسی‌محلی', 'زمان_یونیکسی_محلی', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'نشانی:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'نشانی‌کد:', 'نشانی_کد:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'هفته‌محلی', 'هفته_محلی', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'سال‌محلی', 'سال_محلی', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'پیغام:', 'پ:', 'MSG:' ],
	'msgnw'                     => [ '0', 'پیغام‌بی‌بسط:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'فضای‌نام‌کد', 'فضای_نام_کد', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'شماره_فضای_نام', 'شماره‌فضای‌نام', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__بخش‌جدید__', '__NEWSECTIONLINK__' ],
	'nocommafysuffix'           => [ '0', 'جداکننده‌خیر', 'NOSEP' ],
	'nocontentconvert'          => [ '0', '__محتواتبدیل‌نشده__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__بی‌بخش__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__بی‌نگارخانه__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__بی‌نمایه__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__بی‌پیوندبخش__', '__بی‌پیوند‌بخش‌جدید__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__عنوان‌تبدیل‌نشده__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__بی‌فهرست__', '__NOTOC__' ],
	'ns'                        => [ '0', 'فن:', 'NS:' ],
	'nse'                       => [ '0', 'فنک:', 'NSE:' ],
	'numberingroup'             => [ '1', 'تعداددرگروه', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'کاربران‌فعال', 'کاربران_فعال', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'تعدادمدیران', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'تعدادمقاله‌ها', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'تعدادویرایش‌ها', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'تعدادپرونده‌ها', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'تعدادصفحه‌ها', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'تعدادکاربران', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'لبه‌چپ', 'لبه_چپ', 'PADLEFT' ],
	'padright'                  => [ '0', 'لبه‌راست', 'لبه_راست', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'شناسه_صفحه', 'PAGEID' ],
	'pagename'                  => [ '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'نام‌صفحه‌کد', 'نام_صفحه_کد', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'صفحه‌دررده', 'صفحه_در_رده', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'همه', 'all' ],
	'pagesincategory_files'     => [ '0', 'پرونده‌ها', 'files' ],
	'pagesincategory_pages'     => [ '0', 'صفحات', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'زیررده‌ها', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'صفحه‌درفضای‌نام:', 'صفحه_در_فضای_نام:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'اندازه‌صفحه', 'اندازه_صفحه', 'PAGESIZE' ],
	'plural'                    => [ '0', 'جمع:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'سطح‌حفاطت', 'سطح_حفاظت', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'خام:', 'RAW:' ],
	'rawsuffix'                 => [ '1', 'ن', 'R' ],
	'redirect'                  => [ '0', '#تغییر_مسیر', '#تغییرمسیر', '#REDIRECT' ],
	'revisionday'               => [ '1', 'روزنسخه', 'روز_نسخه', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'روزنسخه۲', 'روز_نسخه۲', 'روز_نسخه_۲', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'نسخه', 'شماره‌نسخه', 'شماره_نسخه', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'ماه‌نسخه', 'ماه_نسخه', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'ماه‌نسخه۱', 'ماه_نسخه_۱', 'REVISIONMONTH1' ],
	'revisionsize'              => [ '1', 'اندازهٔ‌نسخه', 'اندازهٔ_نسخه', 'REVISIONSIZE' ],
	'revisiontimestamp'         => [ '1', 'زمان‌یونیکسی‌نسخه', 'زمان‌نسخه', 'زمان_یونیکسی_نسخه', 'زمان_نسخه', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'کاربرنسخه', 'کاربر_نسخه', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'سال‌نسخه', 'سال_نسخه', 'REVISIONYEAR' ],
	'rootpagename'              => [ '1', 'نام_صفحه_ریشه', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', 'نام_صفحه_ریشه_ای', 'ROOTPAGENAMEE' ],
	'safesubst'                 => [ '0', 'جایگزین_امن:', 'جام:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'مسیرسند', 'مسیر_سند', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'سرور', 'کارساز', 'SERVER' ],
	'servername'                => [ '0', 'نام‌کارساز', 'نام_کارساز', 'نام‌سرور', 'نام_سرور', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ],
	'special'                   => [ '0', 'ویژه', 'special' ],
	'speciale'                  => [ '0', 'ویژه_ای', 'speciale' ],
	'staticredirect'            => [ '1', '__تغییرمسیرثابت__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'مسیرسبک', 'مسیر_سبک', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'نام‌صفحه‌موضوع', 'نام‌صفحه‌مقاله', 'نام_صفحه_موضوع', 'نام_صفحه_مقاله', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'نام‌صفحه‌موضوع‌کد', 'نام‌صفحه‌مقاله‌کد', 'نام_صفحه_موضوع_کد', 'نام_صفحه_مقاله_کد', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'فضای‌موضوع‌کد', 'فضای‌مقاله‌کد', 'فضای_موضوع_کد', 'فضای_مقاله_کد', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'نام‌زیرصفحه', 'نام_زیرصفحه', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'نام‌زیرصفحه‌کد', 'نام_زیرصفحه_کد', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'جایگزین:', 'جا:', 'SUBST:' ],
	'tag'                       => [ '0', 'برچسب', 'tag' ],
	'talkpagename'              => [ '1', 'نام‌صفحه‌بحث', 'نام_صفحه_بحث', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'نام‌صفحه‌بحث‌کد', 'نام_صفحه_بحث_کد', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'فضای‌بحث‌کد', 'فضای_بحث_کد', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__فهرست__', '__TOC__' ],
	'uc'                        => [ '0', 'ب:', 'UC:' ],
	'ucfirst'                   => [ '0', 'ابتدابزرگ:', 'ابتدا_بزرگ:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'کدنشانی:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'مسیر', 'PATH' ],
	'url_query'                 => [ '0', 'دستور', 'QUERY' ],
	'url_wiki'                  => [ '0', 'ویکی', 'WIKI' ],
];

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
	'%' => '٪', # U+066A
];

$separatorTransformTable = [
	'.' => '٫', # U+066B
	',' => '٬', # U+066C
];

$numberingSystem = 'arabext';

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
	'hijri',
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
	'mdy time' => 'H:i',
	'mdy date' => 'n/j/Y میلادی',
	'mdy both' => 'n/j/Y میلادی، ساعت H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'j xg Y، ساعت H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y/n/j میلادی',
	'ymd both' => 'Y/n/j میلادی، ساعت H:i',

	'persian time' => 'H:i',
	'persian date' => 'xij xiF xiY',
	'persian both' => 'xij xiF xiY، ساعت H:i',

	'hijri time' => 'H:i',
	'hijri date' => 'xmj xmF xmY',
	'hijri both' => 'xmj xmF xmY، ساعت H:i',

	'hebrew time' => 'H:i',
	'hebrew date' => 'xij xjF xjY',
	'hebrew both' => 'xij xjF xjY، ساعت H:i',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

// Use Gregorian calendar, where appropriate, override fa browser locale
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

# Harakat are intentionally not included in the linkTrail. Their addition should
# take place after enough tests.
$linkTrail = "/^([ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهیآأئؤة‌]+)(.*)$/sDu";
