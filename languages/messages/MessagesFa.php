<?php
/** Persian (فارسی)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alnokta
 * @author Americophile
 * @author Armandaneshjoo
 * @author Asoxor
 * @author Baqeri
 * @author Behdarvandyani
 * @author Ebraminio
 * @author Huji
 * @author Ibrahim
 * @author Ladsgroup
 * @author Leyth
 * @author Mardetanha
 * @author Mehdi
 * @author Mehran
 * @author MehranVB
 * @author Meisam
 * @author Meno25
 * @author Mjbmr
 * @author Mormegil
 * @author Omnia
 * @author Pouyana
 * @author Reza1615
 * @author Roozbeh Pournader <roozbeh at gmail.com>
 * @author Sahim
 * @author Surena
 * @author Wayiran
 * @author Zack90
 * @author ZxxZxxZ
 * @author לערי ריינהארט
 * @author محک
 */

$namespaceNames = array(
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'ویژه',
	NS_MAIN             => '',
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
	'Allpages'                  => array( 'تمام_صفحه‌ها' ),
	'Ancientpages'              => array( 'صفحه‌های_قدیمی' ),
	'Badtitle'                  => array( 'عنوان_بد' ),
	'Blankpage'                 => array( 'صفحه_خالی' ),
	'Block'                     => array( 'بستن_نشانی_آی‌پی' ),
	'Blockme'                   => array( 'بستن_من' ),
	'Booksources'               => array( 'منابع_کتاب' ),
	'BrokenRedirects'           => array( 'تغییرمسیرهای_خراب' ),
	'Categories'                => array( 'رده‌ها' ),
	'ChangeEmail'               => array( 'تغییر_رایانامه' ),
	'ChangePassword'            => array( 'از_نو_کردن_گذرواژه' ),
	'ComparePages'              => array( 'مقایسه_صفحات' ),
	'Confirmemail'              => array( 'تایید_رایانامه' ),
	'Contributions'             => array( 'مشارکت‌ها' ),
	'CreateAccount'             => array( 'ایجاد_حساب_کاربری' ),
	'Deadendpages'              => array( 'صفحه‌های_بن‌بست' ),
	'DeletedContributions'      => array( 'مشارکت‌های_حذف_شده' ),
	'Disambiguations'           => array( 'ابهام‌زدایی' ),
	'DoubleRedirects'           => array( 'تغییرمسیرهای_دوتایی' ),
	'EditWatchlist'             => array( 'ویرایش_پی‌گیری‌ها' ),
	'Emailuser'                 => array( 'نامه_به_کاربر' ),
	'Export'                    => array( 'برون_بری_صفحه' ),
	'Fewestrevisions'           => array( 'کمترین_نسخه' ),
	'FileDuplicateSearch'       => array( 'جستجوی_پرونده_تکراری' ),
	'Filepath'                  => array( 'مسیر_پرونده' ),
	'Import'                    => array( 'درون_ریزی_صفحه' ),
	'Invalidateemail'           => array( 'باطل_کردن_رایانامه' ),
	'BlockList'                 => array( 'فهرست_بستن_نشانی_آی‌پی' ),
	'LinkSearch'                => array( 'جستجوی_پیوند' ),
	'Listadmins'                => array( 'فهرست_مدیران' ),
	'Listbots'                  => array( 'فهرست_ربات‌ها' ),
	'Listfiles'                 => array( 'فهرست_پرونده‌ها', 'فهرست_تصاویر' ),
	'Listgrouprights'           => array( 'اختیارات_گروه‌های_کاربری' ),
	'Listredirects'             => array( 'فهرست_تغییرمسیرها' ),
	'Listusers'                 => array( 'فهرست_کاربران' ),
	'Lockdb'                    => array( 'قفل_کردن_پایگاه_داده' ),
	'Log'                       => array( 'سیاهه‌ها' ),
	'Lonelypages'               => array( 'صفحه‌های_یتیم' ),
	'Longpages'                 => array( 'صفحه‌های_بلند' ),
	'MergeHistory'              => array( 'ادغام_تاریخچه' ),
	'MIMEsearch'                => array( 'جستجوی_MIME' ),
	'Mostcategories'            => array( 'بیشترین_رده' ),
	'Mostimages'                => array( 'بیشترین_تصویر' ),
	'Mostlinked'                => array( 'بیشترین_پیوند' ),
	'Mostlinkedcategories'      => array( 'رده_با_بیشترین_پیوند' ),
	'Mostlinkedtemplates'       => array( 'الگو_با_بیشترین_پیوند' ),
	'Mostrevisions'             => array( 'بیشترین_نسخه' ),
	'Movepage'                  => array( 'انتقال_صفحه' ),
	'Mycontributions'           => array( 'مشارکت‌های_من' ),
	'Mypage'                    => array( 'صفحه_من' ),
	'Mytalk'                    => array( 'بحث_من' ),
	'Myuploads'                 => array( 'بارگذاری‌های_من' ),
	'Newimages'                 => array( 'تصاویر_جدید' ),
	'Newpages'                  => array( 'صفحه‌های_تازه' ),
	'PasswordReset'             => array( 'بازنشاندن_گذرواژه' ),
	'PermanentLink'             => array( 'پیوند_دائمی' ),
	'Popularpages'              => array( 'صفحه‌های_محبوب' ),
	'Preferences'               => array( 'ترجیحات' ),
	'Prefixindex'               => array( 'نمایه_پیشوندی' ),
	'Protectedpages'            => array( 'صفحه‌های_محافظت_شده' ),
	'Protectedtitles'           => array( 'عنوان‌های_محافظت_شده' ),
	'Randompage'                => array( 'صفحه_تصادفی' ),
	'Randomredirect'            => array( 'تغییرمسیر_تصادفی' ),
	'Recentchanges'             => array( 'تغییرات_اخیر' ),
	'Recentchangeslinked'       => array( 'تغییرات_مرتبط' ),
	'Revisiondelete'            => array( 'حذف_نسخه' ),
	'RevisionMove'              => array( 'انتقال_نسخه' ),
	'Search'                    => array( 'جستجو' ),
	'Shortpages'                => array( 'صفحه‌های_کوتاه' ),
	'Specialpages'              => array( 'صفحه‌های_ویژه' ),
	'Statistics'                => array( 'آمار' ),
	'Tags'                      => array( 'برچسب‌ها' ),
	'Unblock'                   => array( 'باز_کردن' ),
	'Uncategorizedcategories'   => array( 'رده‌های_رده‌بندی_نشده' ),
	'Uncategorizedimages'       => array( 'تصویرهای_رده‌بندی_‌نشده' ),
	'Uncategorizedpages'        => array( 'صفحه‌های_رده‌بندی_نشده' ),
	'Uncategorizedtemplates'    => array( 'الگوهای_رده‌بندی_نشده' ),
	'Undelete'                  => array( 'احیای_صفحهٔ_حذف‌شده' ),
	'Unlockdb'                  => array( 'باز_کردن_پایگاه_داده' ),
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

$fallback8bitEncoding = 'windows-1256';

$rtl = true;


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

$magicWords = array(
	'redirect'                  => array( '0', '#تغییر_مسیر', '#تغییرمسیر', '#REDIRECT' ),
	'notoc'                     => array( '0', '__بی‌فهرست__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__بی‌نگارخانه__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__بافهرست__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__فهرست__', '__TOC__' ),
	'noeditsection'             => array( '0', '__بی‌بخش__', '__NOEDITSECTION__' ),
	'noheader'                  => array( '0', '__بی‌عنوان__', '__NOHEADER__' ),
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
	'numberofviews'             => array( '1', 'تعدادبازدید', 'NUMBEROFVIEWS' ),
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
	'int'                       => array( '0', 'ترجمه:', 'INT:' ),
	'sitename'                  => array( '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ),
	'ns'                        => array( '0', 'فن:', 'NS:' ),
	'nse'                       => array( '0', 'فنک:', 'NSE:' ),
	'localurl'                  => array( '0', 'نشانی:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'نشانی‌کد:', 'نشانی_کد:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'مسیرمقاله', 'مسیر_مقاله', 'ARTICLEPATH' ),
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
	'plural'                    => array( '0', 'جمع:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'نشانی‌کامل:', 'نشانی_کامل:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'نشانی‌کامل‌کد:', 'نشانی_کامل_کد:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'نشانی_استاندارد:', 'نشانی‌استاندارد:', 'CANONICALURL:' ),
	'lcfirst'                   => array( '0', 'ابتداکوچک:', 'ابتدا_کوچک:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ابتدابزرگ:', 'ابتدا_بزرگ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'ک:', 'LC:' ),
	'uc'                        => array( '0', 'ب:', 'UC:' ),
	'raw'                       => array( '0', 'خام:', 'RAW:' ),
	'displaytitle'              => array( '1', 'عنوان‌ظاهری', 'عنوان_ظاهری', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'ن', 'R' ),
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

$messages = array(
# User preference toggles
'tog-underline' => 'خط کشیدن زیر پیوندها:',
'tog-justify' => 'بندها تمام‌چین نمایش یابند',
'tog-hideminor' => 'تغییرات جزئی از فهرست تغییرات اخیر پنهان شوند',
'tog-hidepatrolled' => 'ویرایش‌های گشت‌خورده از فهرست تغییرات اخیر پنهان شوند',
'tog-newpageshidepatrolled' => 'صفحه‌های نهگبانی‌شده از فهرست صفحه‌های تازه پنهان شوند',
'tog-extendwatchlist' => 'گسترش فهرست پی‌گیری‌ها برای نمایش همهٔ تغییرات، نه فقط آخرین‌ها',
'tog-usenewrc' => 'گروه‌بندی تغییرات بر پایه صفحه در تغییرات اخیر و فهرست پیگیری‌ها (نیازمند جاوااسکریپت)',
'tog-numberheadings' => 'شماره‌گذاری خودکار عنوان‌ها',
'tog-showtoolbar' => 'نوار ابزار جعبهٔ ویرایش نمایش یابد (نیازمند جاوااسکریپت)',
'tog-editondblclick' => 'ویرایش صفحه‌ها با دوکلیک (نیازمند جاوااسکریپت)',
'tog-editsection' => 'ویرایش بخش‌ها از طریق پیوندهای [ویرایش] فعال باشد',
'tog-editsectiononrightclick' => 'امکان ویرایش بخش‌ها با کلیک راست روی عنوان‌های بخش (نیازمند جاوااسکریپت)',
'tog-showtoc' => 'فهرست مندرجات نمایش یابد (برای صفحه‌های دارای بیش از ۳ عنوان)',
'tog-rememberpassword' => 'گذرواژهٔ من (حداکثر $1 {{PLURAL:$1|روز|روز}}) در این مرورگر به خاطر سپرده شود',
'tog-watchcreations' => 'صفحه‌هایی که می‌سازم و پرونده‌هایی که بارگذاری می‌کنم به فهرست پی‌گیری‌هایم افزوده شود',
'tog-watchdefault' => 'صفحه‌ها و پرونده‌هایی که ویرایش می‌کنم به فهرست پی‌گیری‌هایم افزوده شود',
'tog-watchmoves' => 'صفحه‌ها و پرونده‌هایی که منتقل می‌کنم به فهرست پی‌گیری‌هایم افزوده شود',
'tog-watchdeletion' => 'صفحه‌ها و پرونده‌هایی که حذف می‌کنم به فهرست پی‌گیری‌هایم افزوده شود',
'tog-minordefault' => 'همهٔ ویرایش‌ها به طور پیش‌فرض به عنوان «جزئی» علامت بخورد',
'tog-previewontop' => 'پیش‌نمایش قبل از جعبهٔ ویرایش نمایش یابد',
'tog-previewonfirst' => 'پیش‌نمایش هنگام اولین ویرایش نمایش یابد',
'tog-nocache' => 'حافظهٔ نهانی مرورگر از کار انداخته شود',
'tog-enotifwatchlistpages' => 'اگر صفحه یا پرونده‌ای از فهرست پی‌گیری‌هایم ویرایش شد به من نامه‌ای فرستاده شود',
'tog-enotifusertalkpages' => 'هنگامی که در صفحهٔ بحث کاربری‌ام تغییری صورت می‌گیرد به من نامه‌ای فرستاده شود',
'tog-enotifminoredits' => 'برای تغییرات جزئی در صفحه‌ها و پرونده‌ها هم به من نامه‌ای فرستاده شود',
'tog-enotifrevealaddr' => 'نشانی رایانامهٔ من در رایانامه‌های اطلاع‌رسانی نمایش یابد',
'tog-shownumberswatching' => 'شمار کاربران پی‌گیری‌کننده نمایش یابد',
'tog-oldsig' => 'امضای کنونی:',
'tog-fancysig' => 'امضا به صورت ویکی‌متن در نظر گرفته شود (بدون درج خودکار پیوند)',
'tog-externaleditor' => 'استفاده از ویرایشگر خارجی به‌طور پیش‌فرض (فقط برای کاربران حرفه‌ای؛ نیازمند تنظیمات ویژه در رایانهٔ شما است. [//www.mediawiki.org/wiki/Manual:External_editors اطلاعات بیشتر].)',
'tog-externaldiff' => 'استفاده از تفاوت‌گیر (diff) خارجی به‌طور پیش‌فرض (فقط برای کاربران حرفه‌ای؛ نیازمند تنظیمات ویژه در رایانهٔ شما است. [//www.mediawiki.org/wiki/Manual:External_editors اطلاعات بیشتر].)',
'tog-showjumplinks' => 'پیوندهای دسترسی‌پذیری «پرش به» فعال باشد',
'tog-uselivepreview' => 'استفاده از پیش‌نمایش زنده (نیازمند جاوااسکریپت) (آزمایشی)',
'tog-forceeditsummary' => 'هنگامی که خلاصهٔ ویرایش ننوشته‌ام به من اطلاع داده شود',
'tog-watchlisthideown' => 'ویرایش‌های خودم در فهرست پی‌گیری‌ها پنهان شود',
'tog-watchlisthidebots' => 'ویرایش‌های ربات‌ها در فهرست پی‌گیری‌ها پنهان شود',
'tog-watchlisthideminor' => 'ویرایش‌های جزئی در فهرست پی‌گیری‌ها پنهان شود',
'tog-watchlisthideliu' => 'ویرایش‌های کاربران وارد شده به سامانه در فهرست پی‌گیری‌ها پنهان شود',
'tog-watchlisthideanons' => 'ویرایش‌های کاربران ناشناس در فهرست پی‌گیری‌های من پنهان شود',
'tog-watchlisthidepatrolled' => 'ویرایش‌های گشت‌خورده در فهرست پی‌گیری‌ها پنهان شود',
'tog-ccmeonemails' => 'رونوشتی از نامه‌ای که به دیگران ارسال می‌کنم برای خودم هم فرستاده شود',
'tog-diffonly' => 'محتوای صفحه، زیر تفاوت نمایش داده نشود',
'tog-showhiddencats' => 'رده‌های پنهان نمایش داده شود',
'tog-noconvertlink' => 'تبدیل عنوان پیوند غیرفعال شود',
'tog-norollbackdiff' => 'بعد از واگردانی تفاوت نشان داده نشود',

'underline-always' => 'همیشه',
'underline-never' => 'هرگز',
'underline-default' => 'پوسته یا مرورگر پیش‌فرض',

# Font style option in Special:Preferences
'editfont-style' => 'سبک قلم جعبهٔ ویرایش:',
'editfont-default' => 'پیش‌فرض مرورگر',
'editfont-monospace' => 'قلم با فاصلهٔ ثابت',
'editfont-sansserif' => 'قلم بدون گوشه',
'editfont-serif' => 'قلم گوشه‌دار',

# Dates
'sunday' => 'یکشنبه',
'monday' => 'دوشنبه',
'tuesday' => 'سه‌شنبه',
'wednesday' => 'چهارشنبه',
'thursday' => 'پنجشنبه',
'friday' => 'جمعه',
'saturday' => 'شنبه',
'sun' => 'یکشنبه',
'mon' => 'دوشنبه',
'tue' => 'سه‌شنبه',
'wed' => 'چهارشنبه',
'thu' => 'پنجشنبه',
'fri' => 'جمعه',
'sat' => 'شنبه',
'january' => 'ژانویه',
'february' => 'فوریه',
'march' => 'مارس',
'april' => 'آوریل',
'may_long' => 'مه',
'june' => 'ژوئن',
'july' => 'ژوئیه',
'august' => 'اوت',
'september' => 'سپتامبر',
'october' => 'اکتبر',
'november' => 'نوامبر',
'december' => 'دسامبر',
'january-gen' => 'ژانویهٔ',
'february-gen' => 'فوریهٔ',
'march-gen' => 'مارس',
'april-gen' => 'آوریل',
'may-gen' => 'مهٔ',
'june-gen' => 'ژوئن',
'july-gen' => 'ژوئیهٔ',
'august-gen' => 'اوت',
'september-gen' => 'سپتامبر',
'october-gen' => 'اکتبر',
'november-gen' => 'نوامبر',
'december-gen' => 'دسامبر',
'jan' => 'ژانویه',
'feb' => 'فوریه',
'mar' => 'مارس',
'apr' => 'آوریل',
'may' => 'مه',
'jun' => 'ژوئن',
'jul' => 'ژوئیه',
'aug' => 'اوت',
'sep' => 'سپتامبر',
'oct' => 'اکتبر',
'nov' => 'نوامبر',
'dec' => 'دسامبر',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|رده|رده‌ها}}',
'category_header' => 'صفحه‌های ردهٔ «$1»',
'subcategories' => 'زیررده‌ها',
'category-media-header' => 'پرونده‌های ردهٔ «$1»',
'category-empty' => "''این رده در حال حاضر حاوی هیچ صفحه یا پرونده‌ای نیست.''",
'hidden-categories' => '{{PLURAL:$1|ردهٔ پنهان|رده‌های پنهان}}',
'hidden-category-category' => 'رده‌های پنهان',
'category-subcat-count' => '{{PLURAL:$2|این رده تنها حاوی زیرردهٔ زیر است.|{{PLURAL:$1|این زیررده|این $1 زیررده}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 زیررده است.}}',
'category-subcat-count-limited' => 'این رده شامل {{PLURAL:$1|یک|$1}} زیرردهٔ زیر است.',
'category-article-count' => '{{PLURAL:$2|این رده فقط دارای صفحهٔ زیر است.|{{PLURAL:$1|این صفحه|این $1 صفحه}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 صفحه است.}}',
'category-article-count-limited' => '{{PLURAL:$1|صفحهٔ|$1 صفحهٔ}} زیر در ردهٔ فعلی قرار دارند.',
'category-file-count' => '{{PLURAL:$2|این رده تنها حاوی پروندهٔ زیر است.|{{PLURAL:$1|این پرونده|این $1 پرونده}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 پرونده است.}}',
'category-file-count-limited' => '{{PLURAL:$1|پروندهٔ|$1 پروندهٔ}} زیر در ردهٔ فعلی قرار دارند.',
'listingcontinuesabbrev' => '(ادامه)',
'index-category' => 'صفحه‌های نمایه‌شده',
'noindex-category' => 'صفحه‌های نمایه‌نشده',
'broken-file-category' => 'صفحه‌های دارای پیوند خراب به پرونده',

'about' => 'درباره',
'article' => 'صفحهٔ محتوایی',
'newwindow' => '(در پنجرهٔ جدید باز می‌شود)',
'cancel' => 'لغو',
'moredotdotdot' => 'بیشتر...',
'mypage' => 'صفحه',
'mytalk' => 'بحث',
'anontalk' => 'بحث برای این آی‌پی',
'navigation' => 'گشتن',
'and' => '&#32;و',

# Cologne Blue skin
'qbfind' => 'یافتن',
'qbbrowse' => 'مرور',
'qbedit' => 'ویرایش',
'qbpageoptions' => 'این صفحه',
'qbpageinfo' => 'محتوا',
'qbmyoptions' => 'صفحه‌های من',
'qbspecialpages' => 'صفحه‌های ویژه',
'faq' => 'پرسش‌های متداول',
'faqpage' => 'Project:پرسش‌های متداول',

# Vector skin
'vector-action-addsection' => 'افزودن بخش',
'vector-action-delete' => 'حذف',
'vector-action-move' => 'انتقال',
'vector-action-protect' => 'محافظت',
'vector-action-undelete' => 'احیا',
'vector-action-unprotect' => 'تغییر سطح حفاظت',
'vector-simplesearch-preference' => 'فعال کردن جستجوی ساده (فقط در پوستهٔ برداری)',
'vector-view-create' => 'ایجاد',
'vector-view-edit' => 'ویرایش',
'vector-view-history' => 'نمایش تاریخچه',
'vector-view-view' => 'خواندن',
'vector-view-viewsource' => 'نمایش مبدأ',
'actions' => 'عملکردها',
'namespaces' => 'فضاهای نام',
'variants' => 'گویش‌ها',

'errorpagetitle' => 'خطا',
'returnto' => 'بازگشت به $1.',
'tagline' => 'از {{SITENAME}}',
'help' => 'راهنما',
'search' => 'جستجو',
'searchbutton' => 'جستجو',
'go' => 'برو',
'searcharticle' => 'برو',
'history' => 'تاریخچهٔ صفحه',
'history_short' => 'تاریخچه',
'updatedmarker' => 'به‌روزشده از آخرین باری که سرزده‌ام',
'printableversion' => 'نسخهٔ قابل چاپ',
'permalink' => 'پیوند پایدار',
'print' => 'چاپ',
'view' => 'نمایش',
'edit' => 'ویرایش',
'create' => 'ایجاد',
'editthispage' => 'ویرایش این صفحه',
'create-this-page' => 'ایجاد این صفحه',
'delete' => 'حذف',
'deletethispage' => 'حذف این صفحه',
'undelete_short' => 'احیای {{PLURAL:$1|یک ویرایش|$1 ویرایش}}',
'viewdeleted_short' => 'نمایش {{PLURAL:$1|یک ویرایش حذف‌شده|$1 ویرایش حذف‌شده}}',
'protect' => 'محافظت',
'protect_change' => 'تغییر',
'protectthispage' => 'محافظت از این صفحه',
'unprotect' => 'تغییر سطح محافظت',
'unprotectthispage' => 'تغییر سطح محافظت این صفحه',
'newpage' => 'صفحهٔ جدید',
'talkpage' => 'بحث دربارهٔ این صفحه',
'talkpagelinktext' => 'بحث',
'specialpage' => 'صفحهٔ ویژه',
'personaltools' => 'ابزارهای شخصی',
'postcomment' => 'بخش جدید',
'articlepage' => 'نمایش مقاله',
'talk' => 'بحث',
'views' => 'بازدیدها',
'toolbox' => 'جعبه‌ابزار',
'userpage' => 'نمایش صفحهٔ کاربر',
'projectpage' => 'دیدن صفحهٔ پروژه',
'imagepage' => 'نمایش صفحهٔ پرونده',
'mediawikipage' => 'نمایش صفحهٔ پیغام',
'templatepage' => 'نمایش صفحهٔ الگو',
'viewhelppage' => 'نمایش صفحهٔ راهنما',
'categorypage' => 'نمایش صفحهٔ رده',
'viewtalkpage' => 'نمایش صفحهٔ بحث',
'otherlanguages' => 'به زبان‌های دیگر',
'redirectedfrom' => '(تغییرمسیر از $1)',
'redirectpagesub' => 'صفحهٔ تغییرمسیر',
'lastmodifiedat' => 'این صفحه آخرین‌بار در $1 ساعت $2 تغییر یافته‌است.',
'viewcount' => 'از این صفحه {{PLURAL:$1|یک‌بار|$1بار}} بازدید شده‌است.',
'protectedpage' => 'صفحهٔ محافظت‌شده',
'jumpto' => 'پرش به:',
'jumptonavigation' => 'ناوبری',
'jumptosearch' => 'جستجو',
'view-pool-error' => 'متأسفانه سرورها در حال حاضر دچار بار اضافی هستند.
تعداد زیادی از کاربران دارند تلاش می‌کنند که این صفحه را ببینند.
لطفاً قبل از تلاش دوباره برای دیدن این صفحه مدتی صبر کنید.

$1',
'pool-timeout' => 'اتمام مهلت انتظار برای قفل',
'pool-queuefull' => 'صف مخزن پر است',
'pool-errorunknown' => 'خطای ناشناخته',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'دربارهٔ {{SITENAME}}',
'aboutpage' => 'Project:درباره',
'copyright' => 'محتوا تحت اجازه‌نامهٔ $1 در دسترس است.',
'copyrightpage' => '{{ns:project}}:حق تکثیر',
'currentevents' => 'رویدادهای کنونی',
'currentevents-url' => 'Project:رویدادهای کنونی',
'disclaimers' => 'تکذیب‌نامه‌ها',
'disclaimerpage' => 'Project:تکذیب‌نامهٔ عمومی',
'edithelp' => 'راهنمای ویرایش‌کردن',
'edithelppage' => 'Help:ویرایش',
'helppage' => 'Help:محتوا',
'mainpage' => 'صفحهٔ اصلی',
'mainpage-description' => 'صفحهٔ اصلی',
'policy-url' => 'Project:سیاست‌ها',
'portal' => 'ورودی کاربران',
'portal-url' => 'Project:ورودی کاربران',
'privacy' => 'سیاست محرمانگی',
'privacypage' => 'Project:سیاست محرمانگی',

'badaccess' => 'خطای دسترسی',
'badaccess-group0' => 'شما اجازهٔ اجرای عملی را که درخواست کرده‌اید ندارید.',
'badaccess-groups' => 'عملی که درخواست کرده‌اید منحصر به کاربران {{PLURAL:$2|این گروه|این گروه‌ها}} است: $1.',

'versionrequired' => 'نسخهٔ $1 از نرم‌افزار مدیاویکی لازم است',
'versionrequiredtext' => 'برای دیدن این صفحه به نسخهٔ $1 از نرم‌افزار مدیاویکی نیاز دارید.
به [[Special:Version|این صفحه]] مراجعه کنید.',

'ok' => 'تأیید',
'backlinksubtitle' => '← $1',
'retrievedfrom' => 'برگرفته از «$1»',
'youhavenewmessages' => '$1 دارید ($2).',
'newmessageslink' => 'پیام‌های جدید',
'newmessagesdifflink' => 'آخرین تغییر',
'youhavenewmessagesfromusers' => 'شما از {{PLURAL:$3|یک کاربر دیگر|$3  کاربر}} $1 دارید ($2).',
'youhavenewmessagesmanyusers' => 'شما از تعدادی کاربر $1 دارید ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|پیام جدید}}',
'newmessagesdifflinkplural' => '{{formatnum:$1}} {{PLURAL:$1|تغییر|تغییر}} اخیر',
'youhavenewmessagesmulti' => 'پیام‌های جدیدی در $1 دارید.',
'editsection' => 'ویرایش',
'editold' => 'ویرایش',
'viewsourceold' => 'نمایش مبدأ',
'editlink' => 'ویرایش',
'viewsourcelink' => 'نمایش مبدأ',
'editsectionhint' => 'ویرایش بخش: $1',
'toc' => 'محتویات',
'showtoc' => 'نمایش',
'hidetoc' => 'نهفتن',
'collapsible-collapse' => 'نهفتن',
'collapsible-expand' => 'گسترش',
'thisisdeleted' => 'نمایش یا احیای $1؟',
'viewdeleted' => 'نمایش $1؟',
'restorelink' => '{{PLURAL:$1|یک|$1}} ویرایش حذف‌شده',
'feedlinks' => 'خبرخوان:',
'feed-invalid' => 'نوع خوراک خبرخوان مجاز نیست.',
'feed-unavailable' => 'خوراک‌های خبرخوان در دسترس نیستند',
'site-rss-feed' => 'خوراک آراس‌اس برای $1',
'site-atom-feed' => 'خوراک اتم برای $1',
'page-rss-feed' => 'خوراک آراس‌اس برای «$1»',
'page-atom-feed' => 'خوراک اتم برای «$1»',
'feed-atom' => 'اتم',
'feed-rss' => 'آراس‌اس',
'red-link-title' => '$1 (صفحه وجود ندارد)',
'sort-descending' => 'مرتب‌سازی نزولی',
'sort-ascending' => 'مرتب‌سازی صعودی',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'صفحه',
'nstab-user' => 'صفحهٔ کاربر',
'nstab-media' => 'رسانه',
'nstab-special' => 'صفحهٔ ویژه',
'nstab-project' => 'صفحهٔ پروژه',
'nstab-image' => 'پرونده',
'nstab-mediawiki' => 'پیغام',
'nstab-template' => 'الگو',
'nstab-help' => 'صفحهٔ راهنما',
'nstab-category' => 'رده',

# Main script and global functions
'nosuchaction' => 'چنین عملی وجود ندارد',
'nosuchactiontext' => 'عمل مشخص‌شده در نشانی اینترنتی غیرمجاز است.
ممکن است نشانی اینترنتی را اشتباه وارد کرده باشید یا پیوند مشکل‌داری را دنبال کرده باشید.
همچنین ممکن است ایرادی در نرم‌افزار استفاده‌شده در {{SITENAME}} وجود داشته باشد.',
'nosuchspecialpage' => 'چنین صفحهٔ ویژه‌ای وجود ندارد',
'nospecialpagetext' => '<strong>شما یک صفحهٔ ویژهٔ غیرمجاز را درخواست کرده‌اید.</strong>

فهرستی از صفحه‌های ویژهٔ مجاز در [[Special:SpecialPages|{{int:specialpages}}]] وجود دارد.',

# General errors
'error' => 'خطا',
'databaseerror' => 'خطای پایگاه داده',
'dberrortext' => 'اشکال نحوی در درخواست فرستاده شده به پایگاه داده رخ داد.
دلیل این مشکل می‌تواند ایرادی در نرم‌افزار باشد.
آخرین درخواست که برای پایگاه داده فرستاد شد این بود:
<blockquote class="mw-content-ltr"><code>$1</code></blockquote>
این درخواست از درون عملگر «<span class="mw-content-ltr"><code>$2</code></span>» فرستاده شد.
پایگاه داده این خطا را بازگرداند:
<div class="mw-content-ltr"><samp>$3: $4</samp></div>',
'dberrortextcl' => 'اشکال نحوی در درخواست فرستاده شده به پایگاه داده رخ داد.
آخرین درخواستی که برای پایگاه داده فرستاد شد این بود:
<div class="mw-content-ltr">$1</div>
این درخواست از درون عملگر «<span class="mw-content-ltr">$2</span>» فرستاده شد.
پایگاه داده این خطا را بازگرداند:
<div class="mw-content-ltr">$3: $4</div>',
'laggedslavemode' => "'''هشدار:''' صفحه ممکن است به‌روزرسانی‌های اخیر را شامل نشود.",
'readonly' => 'پایگاه داده قفل شد',
'enterlockreason' => 'دلیلی برای قفل کردن ذکر کنید، که حاوی تقریبی از زمانی باشد که قفل برداشته خواهد شد',
'readonlytext' => 'پایگاه داده در حال حاضر در برابر تغییرات و ایجاد صفحه‌ها قفل شده‌است؛ این وضعیت احتمالاً به خاطر بهینه‌سازی و رسیدگی‌های معمول است که پس از آن وضع به حالت عادی بازخواهد گشت.

مدیری که آن را قفل کرده این توضیح را ارائه کرده‌است: $1',
'missing-article' => 'پایگاه داده متن صفحهٔ با نام «$1» $2 را که باید پیدا می‌کرد نیافت.

این مشکل معمولاً به علت دنبال‌کردن یک پیوند تفاوت تاریخ‌گذشته یا تاریخچهٔ صفحه‌ای که حذف شده‌است، رخ می‌دهد.

در غیر این صورت ممکن است اشکالی در نرم‌افزار پیدا کرده باشد.
لطفاً این مشکل را با ذکر نشانی اینترنتی به یکی از [[Special:ListUsers/sysop|مدیران]] گزارش دهید.',
'missingarticle-rev' => '(شمارهٔ نسخه: $1)',
'missingarticle-diff' => '(تفاوت: $1، $2)',
'readonly_lag' => 'پایگاه داده به طور خودکار قفل شده‌است تا نسخه‌های پشتیبان با نسخهٔ اصلی هماهنگ شوند',
'internalerror' => 'خطای داخلی',
'internalerror_info' => 'خطای داخلی: $1',
'fileappenderrorread' => 'در طی الحاق امکان خواندن «$1» وجود نداشت.',
'fileappenderror' => 'نشد «$1» را به «$2» الحاق کرد.',
'filecopyerror' => 'نشد از پروندهٔ «$1» روی «$2» نسخه‌برداری شود.',
'filerenameerror' => 'نشد پروندهٔ «$1» به «$2» تغییر نام یابد.',
'filedeleteerror' => 'نشد پروندهٔ «$1» حذف شود.',
'directorycreateerror' => 'نشد مسیر $1 را ایجاد کرد.',
'filenotfound' => 'پروندهٔ «$1» یافت نشد.',
'fileexistserror' => 'امکان نوشتن روی پرونده $1 وجود ندارد: پرونده از قبل موجود است.',
'unexpected' => 'مقدار غیرمنتظره: «$1»=«$2».',
'formerror' => 'خطا: نمی‌توان فرم را فرستاد.',
'badarticleerror' => 'نمی‌توان این عمل را بر این صفحه انجام داد.',
'cannotdelete' => 'امکان حذف صفحه یا تصویر «$1» وجود ندارد.
ممکن است قبلاً فرد دیگری آن را حذف کرده باشد.',
'cannotdelete-title' => 'نمی‌توان صفحهٔ «$1» را حذف کرد',
'delete-hook-aborted' => 'حذف توسط قلاب لغو شد.
توضیحی در این مورد داده نشد.',
'badtitle' => 'عنوان بد',
'badtitletext' => 'عنوان درخواستی نامعتبر، خالی، یا عنوانی میان‌زبانی یا میان‌ویکی‌ای با پیوند نادرست بود.
ممکن است حاوی یک یا چند نویسه باشد که نمی‌توانند در عنوان‌ها استفاده شوند.',
'perfcached' => 'داده‌های زیر از حافظهٔ نهانی فراخوانی شده‌اند و ممکن است کاملاً به‌روز نباشند. حداکثر {{PLURAL:$1|یک نتیجه| $1 نتیجه}} در حافظهٔ نهانی قابل دسترس است.',
'perfcachedts' => 'داده‌های زیر از حافظهٔ نهانی فراخوانی شده‌اند و آخرین بار در $1 به‌روزرسانی شدند. حداکثر {{PLURAL:$4|یک نتیجه|$4 نتیجه}} در حافظهٔ نهانی قابل دسترس است.',
'querypage-no-updates' => 'امکان به‌روزرسانی این صفحه فعلاً غیرفعال شده‌است.
اطلاعات این صفحه ممکن است به‌روز نباشد.',
'wrong_wfQuery_params' => 'پارامترهای wfQuery()‎ نادرست است<br />
تابع: $1<br />
پرس‌وجو: $2',
'viewsource' => 'نمایش مبدأ',
'viewsource-title' => 'نمایش مبدأ برای $1',
'actionthrottled' => 'جلوی عمل شما گرفته شد',
'actionthrottledtext' => 'به منظور جلوگیری از انتشار اسپم، اجازه ندارید که چنین عملی را بیش از چند بار در یک مدت زمان کوتاه انجام بدهید.
لطفاً پس از چند دقیقه دوباره تلاش کنید.',
'protectedpagetext' => 'این صفحه برای جلوگیری از ویرایش یا فعالیت دیگر محافظت شده‌است.',
'viewsourcetext' => 'می‌توانید متن مبدأ این صفحه را مشاهده کنید یا از آن نسخه بردارید:',
'viewyourtext' => "می‌توانید کد مبدأ '''ویرایش‌هایتان''' در این صفحه را ببینید و کپی کنید:",
'protectedinterface' => 'این صفحه ارائه‌دهندهٔ متنی برای واسط کاربر این نرم‌افزار در این ویکی است و به منظور پیشگیری از خرابکاری محافظت شده‌است.
برای افزودن یا تغییر دادن ترجمه برای همهٔ ویکی‌ها، لطفاً از [//translatewiki.net/ translatewiki.net]، پروژهٔ محلی‌سازی مدیاویکی، استفاده کنید.',
'editinginterface' => "'''هشدار:''' صفحه‌ای که ویرایش می‌کنید شامل متنی است که در واسط کاربر این نرم‌افزار به کار رفته‌است.
تغییر این صفحه منجر به تغییر ظاهر واسط کاربر این نرم‌افزار برای دیگر کاربران خواهد شد.
برای افزودن یا تغییر دادن ترجمه برای همهٔ ویکی‌ها، لطفاً از [//translatewiki.net/ translatewiki.net]، پروژهٔ محلی‌سازی مدیاویکی، استفاده کنید.",
'sqlhidden' => '(دستور اس‌کیوال پنهان شده)',
'cascadeprotected' => 'این صفحه در مقابل ویرایش محافظت شده‌است چون در {{PLURAL:$1|صفحهٔ|صفحه‌های}} محافظت‌شدهٔ زیر که گزینهٔ «آبشاری» در {{PLURAL:$1|آن|آن‌ها}} انتخاب شده قرار گرفته‌است:
$2',
'namespaceprotected' => "شما اجازهٔ ویرایش صفحه‌های فضای نام '''$1''' را ندارید.",
'customcssprotected' => 'شما اجازهٔ ویرایش این صفحهٔ سی‌اس‌اس را ندارید، زیرا حاوی تنظیم‌های شخصی یک کاربر دیگر است.',
'customjsprotected' => 'شما اجازهٔ ویرایش این صفحهٔ جاوااسکریپت را ندارید، زیرا حاوی تنظیم‌های شخصی یک کاربر دیگر است.',
'ns-specialprotected' => 'صفحه‌های ویژه غیر قابل ویرایش هستند.',
'titleprotected' => "این عنوان توسط [[User:$1|$1]] در برابر ایجاد محافظت شده‌است.
دلیل ارائه‌شده این است: «''$2''».",
'filereadonlyerror' => 'تغییر پرونده «$1» ممکن نیست چون مخزن پرونده «$2» در حالت فقط خواندنی قرار دارد.

مدیری که آن را قفل کرده چنین توضیحی را ذکر کرده:  «$3».',
'invalidtitle-knownnamespace' => 'عنوان نامعتبر با فضای نام «$2» و متن «$3»',
'invalidtitle-unknownnamespace' => 'عنوان نامعتبر با فضای نام ناشناختهٔ شمارهٔ $1 و متن «$2»',
'exception-nologin' => 'به سامانه وارد نشده‌اید',
'exception-nologin-text' => 'دسترسی به این صفحه یا انجام این عمل در این ویکی نیازمند وارد شدن به سیستم  است.',

# Virus scanner
'virus-badscanner' => "پیکربندی بد: پویشگر ویروس ناشناخته: ''$1''",
'virus-scanfailed' => 'پویش ناموفق (کد $1)',
'virus-unknownscanner' => 'ضدویروس ناشناخته:',

# Login and logout pages
'logouttext' => "'''هم‌اکنون از سامانه خارج شدید.'''

شما می‌توانید به استفادهٔ گمنام از {{SITENAME}} ادامه دهید، یا با همین حساب کاربری یا حسابی دیگر [[Special:UserLogin|به سامانه وارد شوید]].
توجه کنید که تا زمانی که میانگیر مرورگرتان را پاک نکنید، بعضی صفحه‌ها ممکن است به گونه‌ای نمایش یابند که گویی هنوز از سامانه خارج نشده‌اید.",
'welcomecreation' => '==$1، خوش آمدید!==
حساب شما ایجاد شد.
فراموش نکنید که [[Special:Preferences|ترجیحات {{SITENAME}}]] را برای خود تغییر دهید.',
'yourname' => 'نام کاربری:',
'yourpassword' => 'گذرواژه:',
'yourpasswordagain' => 'تکرار گذرواژه:',
'remembermypassword' => 'گذرواژه را (تا حداکثر $1 {{PLURAL:$1|روز|روز}}) در این رایانه به خاطر بسپار',
'securelogin-stick-https' => 'پس از ورود به سامانه به HTTPS متصل بمان',
'yourdomainname' => 'دامنهٔ شما:',
'password-change-forbidden' => 'شما نمی‌توانید گذرواژه‌ها را در این ویکی تغییر دهید.',
'externaldberror' => 'خطایی در ارتباط با پایگاه داده رخ داده‌است یا اینکه شما اجازهٔ به‌روزرسانی حساب خارجی خود را ندارید.',
'login' => 'ورود به سامانه',
'nav-login-createaccount' => 'ورود به سامانه / ایجاد حساب کاربری',
'loginprompt' => 'برای ورود به {{SITENAME}} باید کوکی‌ها را فعال کنید.',
'userlogin' => 'ورود به سامانه / ایجاد حساب کاربری',
'userloginnocreate' => 'ورود به سامانه',
'logout' => 'خروج از سامانه',
'userlogout' => 'خروج از سامانه',
'notloggedin' => 'به سامانه وارد نشده‌اید',
'nologin' => 'حساب کاربری ندارید؟ $1.',
'nologinlink' => 'یک حساب کاربری جدید بسازید',
'createaccount' => 'ایجاد حساب کاربری',
'gotaccount' => 'حساب کاربری دارید؟ $1.',
'gotaccountlink' => 'به سامانه وارد شوید',
'userlogin-resetlink' => 'جزئیات ورود را فراموش کرده‌اید؟',
'createaccountmail' => 'با رایانامه',
'createaccountreason' => 'دلیل:',
'badretype' => 'گذرواژه‌هایی که وارد کرده‌اید یکسان نیستند.',
'userexists' => 'نام کاربری‌ای که وارد کردید قبلاً استفاده شده‌است.
لطفاً یک نام دیگر انتخاب کنید.',
'loginerror' => 'خطا در ورود به سامانه',
'createaccounterror' => 'امکان ساختن این حساب وجود ندارد: $1',
'nocookiesnew' => 'حساب کاربری ایجاد شد، اما شما وارد سامانه نشدید.
{{SITENAME}} برای ورود کاربران به سامانه از کوکی استفاده می‌کند.
شما کوکی‌ها را از کار انداخته‌اید.
لطفاً کوکی‌ها را به کار بیندازید، و سپس با نام کاربری و گذرواژهٔ جدیدتان به سامانه وارد شوید.',
'nocookieslogin' => '{{SITENAME}} برای ورود کاربران به سامانه از کوکی‌ها استفاده می‌کند.
شما کوکی‌ها را از کار انداخته‌اید.
لطفاً کوکی‌ها را به کار بیندازید و دوباره امتحان کنید.',
'nocookiesfornew' => 'حساب کاربری ساخته نشد، زیرا نتوانستیم منبع آن را تأیید کنیم.
مطمئن شوید که کوکی‌ها فعال هستند، آن‌گاه صفحه را از نو بارگیری کنید و دوباره امتحان کنید.',
'noname' => 'شما نام کاربری معتبری مشخص نکرده‌اید.',
'loginsuccesstitle' => 'ورود موفقیت‌آمیز به سامانه',
'loginsuccess' => "'''شما اکنون با نام «$1» به {{SITENAME}} وارد شده‌اید.'''",
'nosuchuser' => 'کاربری با نام «$1» وجود ندارد.
نام کاربری به بزرگی و کوچکی حروف حساس است.
املای نام را بررسی کنید، یا [[Special:UserLogin/signup|یک حساب کاربری جدید بسازید]].',
'nosuchusershort' => "هیچ کاربری با نام ''$1'' وجود ندارد.
املایتان را وارسی کنید.",
'nouserspecified' => 'باید یک نام کاربری مشخص کنید.',
'login-userblocked' => 'این کاربر بسته شده‌است. ورود به سامانه مجاز نیست.',
'wrongpassword' => 'گذرواژه‌ای که وارد کردید نادرست است.
لطفاً دوباره امتحان کنید.',
'wrongpasswordempty' => 'گذرواژه‌ای که وارد کرده‌اید، خالی است.
لطفاً دوباره تلاش کنید.',
'passwordtooshort' => 'گذرواژه باید دست‌کم {{PLURAL:$1|۱ حرف|$1 حرف}} داشته باشد.',
'password-name-match' => 'گذرواژهٔ شما باید با نام کاربری شما تفاوت داشته باشد.',
'password-login-forbidden' => 'استفاده از این نام کاربری و گذرواژه ممنوع است.',
'mailmypassword' => 'گذرواژهٔ جدید با رایانامه فرستاده شود',
'passwordremindertitle' => 'یادآور گذرواژهٔ {{SITENAME}}',
'passwordremindertext' => 'یک نفر (احتمالاً خود شما، با نشانی آی‌پی $1) گذرواژهٔ جدیدی برای حساب کاربری شما در {{SITENAME}} درخواست کرده‌است ($4). 
یک گذرواژهٔ موقت برای کاربر «$2» ساخته شده و برابر با «$3» قرار داده شده‌است.
اگر هدفتان همین بوده‌است، اکنون باید وارد سامانه شوید و گذرواژهٔ جدیدی برگزینید.
گذرواژهٔ موقت شما ظرف {{PLURAL:$5|یک روز|$5 روز}} باطل می‌شود.

اگر کس دیگری این درخواست را کرده‌است یا اینکه شما گذرواژهٔ پیشین خود را به یاد آورده‌اید و دیگر تمایلی به تغییر آن ندارید، می‌توانید این پیغام را نادیده بگیرید و همان گذرواژهٔ پیشین را به کار برید.',
'noemail' => 'هیچ نشانی رایانامه‌ای برای کاربر «$1» ثبت نشده‌است.',
'noemailcreate' => 'باید یک نشانی رایانامه معتبر وارد کنید',
'passwordsent' => 'گذرواژه‌ای جدید به نشانی رایانامه ثبت‌شده برای «$1» فرستاده شد.
لطفاً پس از دریافت آن دوباره به سامانه وارد شوید.',
'blocked-mailpassword' => 'نشانی آی‌پی شما از ویرایش بازداشته شده‌است و از این رو به منظور جلوگیری از سوءاستفاده اجازهٔ بهره‌گیری از قابلیت بازیابی گذرواژه را ندارد.',
'eauthentsent' => 'یک نامه برای تأیید نشانی رایانامه به نشانی موردنظر ارسال شد.
قبل از اینکه نامهٔ دیگری قابل ارسال به این نشانی باشد، باید دستورهایی که در آن نامه آمده است را جهت تأیید این مساله که این نشانی متعلق به شماست، اجرا کنید.',
'throttled-mailpassword' => 'یک یادآور گذرواژه در $1 {{PLURAL:$1|ساعت|ساعت}} گذشته برای شما فرستاده شده‌است.
برای جلوگیری از سوءاستفاده هر $1 {{PLURAL:$1|ساعت|ساعت}} تنها یک یادآوری فرستاده می‌شود.',
'mailerror' => 'خطا در فرستادن رایانامه: $1',
'acct_creation_throttle_hit' => 'بازدیدکنندگان این ویکی که از نشانی آی‌پی شما استفاده می‌کنند در روز گذشته {{PLURAL:$1|یک حساب کاربری|$1 حساب کاربری}} ساخته‌اند، که بیشترین تعداد مجاز در آن بازهٔ زمانی است.
به همین خاطر، بازدیدکنندگانی که از این نشانی آی‌پی استفاده می‌کنند نمی‌توانند در حال حاضر حساب جدیدی بسازند.',
'emailauthenticated' => 'نشانی رایانامه شما در $2 ساعت $3 تصدیق شد.',
'emailnotauthenticated' => 'نشانی رایانامه شما هنوز تصدیق نشده‌است.
برای هیچ‌یک از ویژگی‌های زیر رایانامه ارسال نخواهد شد.',
'noemailprefs' => 'برای راه‌اندازی این قابلیت‌ها یک نشانی رایانامه مشخص کنید.',
'emailconfirmlink' => 'تأیید نشانی رایانامه',
'invalidemailaddress' => 'نشانی واردشدهٔ رایانامه قابل‌قبول نیست، چرا که دارای ساختار نامعتبری است.
لطفاً نشانی‌ای با ساختار صحیح وارد کنید و یا بخش مربوط را خالی بگذارید.',
'cannotchangeemail' => 'نشانی‌های رایانامهٔ حساب کاربری در این ویکی قابل تغییر نیست.',
'emaildisabled' => 'این وب‌گاه قادر به ارسال رایانامه نیست.',
'accountcreated' => 'حساب کاربری ایجاد شد',
'accountcreatedtext' => 'حساب کاربری $1 ایجاد شده‌است.',
'createaccount-title' => 'ایجاد حساب کاربری در {{SITENAME}}',
'createaccount-text' => 'یک نفر برای رایانامه شما یک حساب کاربری در {{SITENAME}} با نام «$2» ایجاد کرده‌است ($4)، که گذرواژهٔ آن چنین است: $3
شما باید به سامانه وارد شوید تا گذرواژهٔ خود را تغییر بدهید.

اگر این حساب اشتباهی ساخته شده است، این پیغام را نادیده بگیرید.',
'usernamehasherror' => 'نام کاربری نمی‌تواند شامل نویسه‌های درهم باشد',
'login-throttled' => 'شما چندین‌بار برای ورود به سامانه تلاش کرده‌اید.
لطفاً پیش از آنکه دوباره تلاش کنید کمی صبر کنید.',
'login-abort-generic' => 'ورود شما به سیستم ناموفق بود - خاتمهٔ ناگهانی داده شد',
'loginlanguagelabel' => 'زبان: $1',
'suspicious-userlogout' => 'درخواست شما برای خروج از سامانه رد شد زیرا به نظر می‌رسد که این درخواست توسط یک مرورگر معیوب یا پروکسی میانگیر ارسال شده باشد.',

# E-mail sending
'php-mail-error-unknown' => 'خطای ناشناخته در تابع  mail()‎ پی‌اچ‌پی',
'user-mail-no-addy' => 'تلاش برای ارسال نامه بدون یک آدرس رایانامه.',

# Change password dialog
'resetpass' => 'تغییر گذرواژه',
'resetpass_announce' => 'شما با کد موقتی ارسال شده وارد شده‌اید.
برای انجام فرایند ورود به سامانه باید گذروازهٔ جدیدی وارد کنید:',
'resetpass_text' => '<!-- اینجا متن اضافه کنید -->',
'resetpass_header' => 'تغییر گذرواژهٔ حساب کاربری',
'oldpassword' => 'گذرواژهٔ پیشین:',
'newpassword' => 'گذرواژهٔ جدید:',
'retypenew' => 'گذرواژهٔ جدید را دوباره وارد کنید',
'resetpass_submit' => 'تنظیم گذرواژه و ورود به سامانه',
'resetpass_success' => 'گذرواژهٔ شما با موفقیت تغییر داده شد!
در حال وارد کردن شما به سامانه...',
'resetpass_forbidden' => 'نمی‌توان گذرواژه‌ها را تغییر داد',
'resetpass-no-info' => 'برای دسترسی مستقیم به این صفحه شما باید به سامانه وارد شده باشید.',
'resetpass-submit-loggedin' => 'تغییر گذرواژه',
'resetpass-submit-cancel' => 'لغو',
'resetpass-wrong-oldpass' => 'گذرواژهٔ موقت یا اخیر نامعتبر.
ممکن است که شما همینک گذرواژه‌تان را با موفقیت تغییر داده باشید یا درخواست یک گذرواژهٔ موقت جدید کرده باشید.',
'resetpass-temp-password' => 'گذرواژهٔ موقت:',

# Special:PasswordReset
'passwordreset' => 'بازنشانی گذرواژه',
'passwordreset-text' => 'این فرم را برای دریافت نامهٔ یادآور جزئیات حسابتان کامل کنید.',
'passwordreset-legend' => 'بازنشانی گذرواژه',
'passwordreset-disabled' => 'بازنشانی گذرواژه در این ویکی غیرفعال شده است.',
'passwordreset-pretext' => '{{PLURAL:$1||یکی از قطعه‌های داده را در زیر وارد کنید}}',
'passwordreset-username' => 'نام کاربری:',
'passwordreset-domain' => 'دامنه:',
'passwordreset-capture' => 'رایانامهٔ نهایی نشان داده شود؟',
'passwordreset-capture-help' => 'اگر این گزینه را علامت بزنید رایانامهٔ (حاوی گذرواژهٔ موقت) به شما نشان داده خواهد شد و برای کاربر نیز فرستاده خواهد شد.',
'passwordreset-email' => 'نشانی رایانامه:',
'passwordreset-emailtitle' => 'جزئیات حساب در {{SITENAME}}',
'passwordreset-emailtext-ip' => 'شخصی (احتمالاً شما، با نشانی آی‌پی $1) درخواست یادآوری جزئیات حساب کاربریتان در {{SITENAME}} ($4) را کرده‌است. {{PLURAL:$3|حساب|حساب‌های}} کاربری زیر با این رایانشانی مرتبط هستند:

$2

{{PLURAL:$3|این گذرواژهٔ موقت|این گذرواژه‌های موقت}} پس از {{PLURAL:$5|یک روز|$5 روز}} باطل خواهند شد.
شما باید اکنون وارد سایت شوید و گذرواژه‌ای جدید برگزینید. اگر فکر می‌کنید شخص دیگری این درخواست را داده‌است یا اگر گذرواژهٔ اصلی‌تان را به یاد آوردید و دیگر نمی‌خواهید آن را تغییر دهید، می‌توانید این پیغام را نادیده بگیرید و به استفاده از گذرواژهٔ قبلی‌تان ادامه دهید.',
'passwordreset-emailtext-user' => 'کاربر $1 از {{SITENAME}} درخواست یادآور جزئیات حساب شما را برای {{SITENAME}}
($4) کرده است. {{PLURAL:$3|حساب|حساب‌های}} کاربری زیر با این رایانشانی مرتبط است:

$2

{{PLURAL:$3|این گذرواژهٔ موقت|این گذرواژه‌های موقت}} تا {{PLURAL:$5|یک روز|$5 روز}} باطل می‌شود.
شما باید هم‌اکنون وارد شده و یک گذرواژهٔ جدید برگزینید. اگر شخص دیگری این درخواست را داده است، یا اگر گذرواژهٔ اصلی‌تان را به خاطر آوردید، و دیگر نمی‌خواهید که آن را تغییر دهید، می‌توانید این پیغام را نادیده بگیرید و به استفاده از گذرواژهٔ قبلی‌تان ادامه دهید.',
'passwordreset-emailelement' => 'نام کاربری: $1
گذرواژهٔ موقت: $2',
'passwordreset-emailsent' => 'یک نامهٔ یادآور فرستاده شد.',
'passwordreset-emailsent-capture' => 'رایانامهٔ یادآور فرستاده شد، که به شرح زیر است.',
'passwordreset-emailerror-capture' => 'رایانامهٔ یادآور همانطور که در زیر مشاهده می‌فرمایید ایجاد شد ولی ارسال آن به کاربر موفقیت‌آمیز نبود: $1',

# Special:ChangeEmail
'changeemail' => 'تغییر نشانی رایانامه',
'changeemail-header' => 'تغییر نشانی رایانامهٔ حساب کاربری',
'changeemail-text' => 'این فرم را تکمیل کنید تا آدرس رایانامه‌تان تغییر یابد. برای این که این تغییر را تأیید کنید لازم است گذرواژهٔ خود را وارد کنید.',
'changeemail-no-info' => 'برای دسترسی مستقیم به این صفحه شما باید به سامانه وارد شده باشید.',
'changeemail-oldemail' => 'نشانی رایانامهٔ کنونی:',
'changeemail-newemail' => 'نشانی رایانامهٔ جدید:',
'changeemail-none' => '(هیچ)',
'changeemail-submit' => 'تغییر رایانامه',
'changeemail-cancel' => 'انصراف',

# Edit page toolbar
'bold_sample' => 'متن پررنگ',
'bold_tip' => 'متن پررنگ',
'italic_sample' => 'متن مورب',
'italic_tip' => 'متن مورب',
'link_sample' => 'عنوان پیوند',
'link_tip' => 'پیوند درونی',
'extlink_sample' => 'http://www.example.com عنوان پیوند',
'extlink_tip' => 'پیوند به بیرون (پیشوند http://‎ را فراموش نکنید)',
'headline_sample' => 'متن عنوان',
'headline_tip' => 'عنوان سطح ۲',
'nowiki_sample' => 'متن قالب‌بندی‌نشده اینجا وارد شود',
'nowiki_tip' => 'نادیده‌گرفتن قالب‌بندی ویکی',
'image_sample' => 'Example.jpg',
'image_tip' => 'تصویر داخل متن',
'media_sample' => 'Example.ogg',
'media_tip' => 'پیوند پرونده',
'sig_tip' => 'امضای شما و برچسب زمان',
'hr_tip' => 'خط افقی (از آن کم استفاده کنید)',

# Edit pages
'summary' => 'خلاصه:',
'subject' => 'موضوع/عنوان:',
'minoredit' => 'این ویرایش جزئی‌است',
'watchthis' => 'پی‌گیری این صفحه',
'savearticle' => 'صفحه ذخیره شود',
'preview' => 'پیش‌نمایش',
'showpreview' => 'پیش‌نمایش',
'showlivepreview' => 'پیش‌نمایش زنده',
'showdiff' => 'نمایش تغییرات',
'anoneditwarning' => "'''هشدار:''' شما به سامانه وارد نشده‌اید.
نشانی آی‌پی شما در تاریخچهٔ ویرایش این صفحه ثبت خواهد شد.",
'anonpreviewwarning' => "''شما به سامانه وارد نشده‌اید. ذخیره کردن باعث می‌شود که نشانی آی‌پی شما در تاریخچهٔ این صفحه ثبت گردد.''",
'missingsummary' => "'''یادآوری:''' شما خلاصهٔ ویرایش ننوشته‌اید.
اگر دوباره دکمهٔ «{{int:savearticle}}» را فشار دهید ویرایش شما بدون آن ذخیره خواهد شد.",
'missingcommenttext' => 'لطفاً توضیحی در زیر بیفزایید.',
'missingcommentheader' => "'''یادآوری:''' شما موضوع/عنوان این یادداشت را مشخص نکرده‌اید.
اگر دوباره دکمهٔ «{{int:savearticle}}» را فشار دهید ویرایش شما بدون آن ذخیره خواهد شد.",
'summary-preview' => 'پیش‌نمایش خلاصه:',
'subject-preview' => 'پیش‌نمایش موضوع/عنوان:',
'blockedtitle' => 'کاربر بسته شده‌است',
'blockedtext' => "'''دسترسی حساب کاربری یا نشانی آی‌پی شما بسته شده‌است.'''

این قطع دسترسی توسط $1 انجام شده‌است.
دلیل ارائه‌شده چنین است: $2''

* شروع قطع دسترسی: $8
* پایان قطع دسترسی: $6
* کاربری هدف قطع دسترسی: $7

شما می‌توانید با $1 یا  [[{{MediaWiki:Grouppage-sysop}}|مدیری]] دیگر تماس بگیرید و در این باره صحبت کنید.
توجه کنید که شما نمی‌توانید از ویژگی «فرستادن رایانامه به این کاربر» استفاده کنید مگر آنکه نشانی رایانامه معتبری در [[Special:Preferences|ترجیحات کاربری]] خودتان ثبت کرده باشید و نیز باید امکان استفاده از این ویژگی برای شما قطع نشده باشد.
نشانی آی‌پی فعلی شما $3 و شمارهٔ قطع دسترسی شما $5 است.
لطفاً تمامی جزئیات فوق را در کلیهٔ درخواست‌هایی که در این باره مطرح می‌کنید ذکر کنید.",
'autoblockedtext' => "دسترسی نشانی آی‌پی شما قطع شده‌است، زیرا این نشانی آی‌پی توسط کاربر دیگری استفاده شده که دسترسی او توسط $1 قطع شده‌است.
دلیل ارائه‌شده چنین است:

:''$2''

* شروع قطع دسترسی: $8
* پایان قطع دسترسی: $6
* کاربری هدف قطع دسترسی: $7

شما می‌توانید با $1 یا  [[{{MediaWiki:Grouppage-sysop}}|مدیری]] دیگر تماس بگیرید و در این باره صحبت کنید.
توجه کنید که شما نمی‌توانید از ویژگی «فرستادن رایانامه به این کاربر» استفاده کنید مگر آنکه نشانی رایانامه معتبری در [[Special:Preferences|ترجیحات کاربری]] خودتان ثبت کرده باشید و نیز باید امکان استفاده از این ویژگی برای شما قطع نشده باشد.
نشانی آی‌پی فعلی شما $3 و شمارهٔ قطع دسترسی شما $5 است.
لطفاً تمامی جزئیات فوق را در کلیهٔ درخواست‌هایی که در این باره مطرح می‌کنید ذکر کنید.",
'blockednoreason' => 'دلیلی مشخص نشده‌است',
'whitelistedittext' => 'برای ویرایش مقاله‌ها باید $1.',
'confirmedittext' => 'شما باید، پیش از ویرایش صفحه‌ها، نشانی رایانامهٔ خود را مشخص و تأیید کنید. لطفاً از طریق [[Special:Preferences|ترجیحات کاربر]] این کار را صورت دهید.',
'nosuchsectiontitle' => 'چنین بخشی پیدا نشد',
'nosuchsectiontext' => 'شما تلاش کرده‌اید یک بخش در صفحه را ویرایش کنید که وجود ندارد.
ممکن است در مدتی که شما صفحه را مشاهده می‌کردید این بخش جا به جا یا حذف شده باشد.',
'loginreqtitle' => 'ورود به سامانه لازم است',
'loginreqlink' => 'به سامانه وارد شوید',
'loginreqpagetext' => 'برای دیدن صفحه‌های دیگر باید $1.',
'accmailtitle' => 'گذرواژه فرستاده شد.',
'accmailtext' => "یک گذرواژهٔ تصادفی ساخته شده برای [[User talk:$1|$1]] برای $2 ارسال شد.

گذرواژهٔ این حساب کاربری تازه، پس از ورود به سامانه از طریق ''[[Special:ChangePassword|تغییر گذرواژه]]'' قابل تغییر است.",
'newarticle' => '(جدید)',
'newarticletext' => 'شما پیوندی را دنبال کرده‌اید و به صفحه‌ای رسیده‌اید که هنوز وجود ندارد.
برای ایجاد صفحه، در مستطیل زیر شروع به نوشتن کنید (برای اطلاعات بیشتر به [[{{MediaWiki:Helppage}}|صفحهٔ راهنما]] مراجعه کنید).
اگر به اشتباه اینجا آمده‌اید، دکمهٔ «بازگشت» مرورگرتان را بزنید.',
'anontalkpagetext' => "----''این صفحهٔ بحث برای کاربر گمنامی است که هنوز حسابی درست نکرده است یا از آن استفاده نمی‌کند.
بنا بر این برای شناسایی‌اش مجبوریم از نشانی آی‌پی عددی استفاده کنیم.
چنین نشانی‌های آی‌پی ممکن است توسط چندین کاربر به شکل مشترک استفاده شود.
اگر شما کاربر گمنامی هستید و تصور می‌کنید اظهار نظرات نامربوط به شما صورت گرفته است، لطفاً برای پیشگیری از اشتباه گرفته شدن با کاربران گمنام دیگر در آینده [[Special:UserLogin/signup|حسابی ایجاد کنید]] یا [[Special:UserLogin|به سامانه وارد شوید]].''",
'noarticletext' => 'این صفحه هم‌اکنون دارای هیچ متنی نیست.
شما می‌توانید در صفحه‌های دیگر [[Special:Search/{{PAGENAME}}|عنوان این صفحه را جستجو کنید]]،
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} سیاهه‌های مرتبط را جستجو کنید]،
یا [{{fullurl:{{FULLPAGENAME}}|action=edit}} این صفحه را ویرایش کنید]</span>.',
'noarticletext-nopermission' => 'این صفحه هم‌اکنون متنی ندارد.
شما می‌توانید در دیگر صفحه‌ها [[Special:Search/{{PAGENAME}}|این عنوان را جستجو کنید]]،
یا <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} سیاهه‌های مرتبط را بگردید]</span> ولی شما اجازه ایجاد این صفحه را ندارید.',
'missing-revision' => 'ویرایش #$1 از صفحهٔ "{{PAGENAME}}" موجود نیست.

معمولاً در اثر پیوند به تاریخچهٔ به‌روز نشدهٔ صفحهٔ حذف شده است.
می‌توانید جزئیات بیشتر را در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] بیابید.',
'userpage-userdoesnotexist' => 'حساب کاربر «<nowiki>$1</nowiki>» ثبت نشده‌است.
لطفاً مطمئن شوید که می‌خواهید این صفحه را ایجاد یا ویرایش کنید.',
'userpage-userdoesnotexist-view' => 'حساب کاربری «$1» ثبت نشده‌است.',
'blocked-notice-logextract' => 'دسترسی این کاربر در حال حاضر بسته است.
آخرین مورد سیاهه قطع دسترسی در زیر آمده‌است:',
'clearyourcache' => "'''نکته:''' پس از ذخیره‌کردن ممکن است برای دیدن تغییرات نیاز باشد که حافظهٔ نهانی مرورگر خود را پاک کنید.
*'''فایرفاکس / سافاری:'''  کلید ''Shift'' را نگه دارید و روی دکمهٔ ''Reload'' کلیک کنید، یا کلید‌های ''Ctrl-F5'' یا ''Ctrl-R'' را با هم فشار دهید (در رایانه‌های اپل مکینتاش کلید‌های ''⌘-R'')
*'''گوگل کروم:'''کلیدهای ''Ctrl+Shift+R'' را با هم فشار دهید. (در رایانه‌های اپل مکینتاش کلید‌های ''⌘-Shift-R'')
*'''اینترنت اکسپلورر:''' کلید ''Ctrl'' را نگه‌دارید و روی دکمهٔ ''Refresh'' کلیک کنید، یا کلید‌های ''Ctrl-F5'' را با هم فشار دهید
*'''اپرا:''' حافظهٔ نهانی مرورگر را از طریق منوی ''Tools &rarr; Preferences'' پاک کنید",
'usercssyoucanpreview' => "'''نکته:''' پیش از ذخیره‌کردن فایل سی‌اس‌اس خود، با دکمهٔ '''{{int:showpreview}}''' آن را آزمایش کنید.",
'userjsyoucanpreview' => "'''نکته:''' پیش از ذخیره‌کردن فایل جاوااسکریپت خود، با دکمهٔ '''{{int:showpreview}}''' آن را آزمایش کنید.",
'usercsspreview' => "'''فراموش مکنید که شما فقط دارید پیش‌نمایش سی‌اس‌اس کاربری‌تان را می‌بینید.'''
'''این سی‌اس‌اس هنوز ذخیره نشده‌است!'''",
'userjspreview' => "'''به یاد داشته باشید که شما فقط دارید جاوااسکریپت کاربری‌تان را امتحان می‌کنید/پیش‌نمایش آن را می‌بینید.'''
'''این جاوااسکریپت هنوز ذخیره نشده‌است!'''",
'sitecsspreview' => "'''به یاد داشته باشید که شما فقط دارید پیش‌نمایش این سی‌اس‌اس را می‌بینید.'''
'''این سی‌اس‌اس هنوز ذخیره نشده‌است!'''",
'sitejspreview' => "'''به یاد داشته باشید که شما فقط دارید پیش‌نمایش این جاوااسکریپت را می‌بینید.'''
'''این جاوااسکریپت هنوز ذخیره نشده‌است!'''",
'userinvalidcssjstitle' => "'''هشدار:''' پوسته‌ای به نام «$1» وجود ندارد.
به یاد داشته باشید که صفحه‌های شخصی ‎.css و ‎.js باید عنوانی با حروف کوچک داشته باشند؛ نمونه: {{ns:user}}:فو/vector.css در مقابل {{ns:user}}:فو/Vector.css.",
'updated' => '(به‌روز شد)',
'note' => "'''نکته:'''",
'previewnote' => "'''به یاد داشته باشید که این فقط پیش‌نمایش است.'''
تغییرات شما هنوز ذخیره نشده‌است!",
'continue-editing' => 'رفتن به قسمت ویرایش',
'previewconflict' => 'این پیش‌نمایش منعکس‌کنندهٔ متن ناحیهٔ ویرایش متن بالایی است، به شکلی که اگر متن را ذخیره کنید نمایش خواهد یافت.',
'session_fail_preview' => "'''شرمنده! به علت از دست رفتن اطلاعات نشست کاربری نمی‌توانیم ویرایش شما را پردازش کنیم.'''
لطفاً دوباره سعی کنید.
اگر دوباره به همین پیام برخوردید از سامانه [[Special:UserLogout|خارج شوید]] و دوباره وارد شوید.",
'session_fail_preview_html' => "'''متاسفانه امکان ثبت ویرایش شما به خاطر از دست رفتن اطلاعات نشست کاربری وجود ندارد.'''

''با توجه به این که در {{SITENAME}} امکان درج اچ‌تی‌ام‌ال خام فعال است، پیش‌نمایش صفحه پنهان شده تا امکان حملات مبتنی بر جاوااسکریپت وجود نداشته باشد.''

'''اگر مطمئن هستید که این پیش‌نمایش یک ویرایش مجاز است، آن را تکرار کنید.'''
اگر تکرار پیش‌نمایش نتیجه نداد، از سامانه [[Special:UserLogout|خارج شوید]] و دوباره وارد شوید.",
'token_suffix_mismatch' => "'''ویرایش شما ذخیره نشد، زیرا مرورگر شما نویسه‌های نقطه‌گذاری را در کد امنیتی ویرایش از هم پاشیده‌است.'''
ویرایش شما مردود شد تا از خراب شدن متن صفحه جلوگیری شود.
گاهی این اشکال زمانی پیش می‌آید که شما از یک پروکسی تحت وب استفاده کنید.",
'edit_form_incomplete' => "'''بعضی قسمت‌های فرم ویرایش به سرور نرسیدند؛ اطمینان حاصل کنید که ویرایش‌های شما کامل است و دوباره تلاش کنید.'''",
'editing' => 'در حال ویرایش $1',
'creating' => 'ایجاد $1',
'editingsection' => 'در حال ویرایش $1 (بخش)',
'editingcomment' => 'در حال ویرایش $1 (بخش جدید)',
'editconflict' => 'تعارض ویرایشی: $1',
'explainconflict' => "از وقتی ویرایش این صفحه را آغاز کرده‌اید شخص دیگری آن را تغییر داده‌است.
ناحیهٔ متنی بالایی شامل متن صفحه به شکل کنونی آن است.
تغییرات شما در ناحیهٔ متنی پایینی نشان داده شده‌است.
شما باید تغییراتتان را با متن کنونی ترکیب کنید.
با فشردن دکمهٔ «{{int:savearticle}}» '''فقط''' متن ناحیهٔ متنی بالایی ذخیره خواهد شد.",
'yourtext' => 'متن شما',
'storedversion' => 'نسخهٔ ذخیره شده',
'nonunicodebrowser' => "'''هشدار: مرورگر شما با استانداردهای یونیکد سازگار نیست.'''
راه حلی به کار گرفته شده تا شما بتوانید صفحه‌ها را با امنیت ویرایش کنید: کاراکترهای غیر ASCII به صورت کدهایی در مبنای شانزده به شما نشان داده می‌شوند.",
'editingold' => "'''هشدار: شما در حال ویرایش نسخه‌ای قدیمی از این صفحه هستید.'''
اگر ذخیره‌اش کنید، هر تغییری که پس از این نسخه انجام شده‌است از بین خواهد رفت.",
'yourdiff' => 'تفاوت‌ها',
'copyrightwarning' => "لطفاً توجه داشته باشید که فرض می‌شود کلیهٔ مشارکت‌های شما با {{SITENAME}} تحت «$2» منتشر می‌شوند (برای جزئیات بیشتر به $1 مراجعه کنید).
اگر نمی‌خواهید نوشته‌هایتان بی‌رحمانه ویرایش شده و به دلخواه توزیع شود، اینجا نفرستیدشان.<br />
همچنین شما دارید به ما قول می‌دهید که خودتان این را نوشته‌اید، یا آن را از یک منبع آزاد با مالکیت عمومی یا مشابه آن برداشته‌اید.
'''کارهای دارای حق تکثیر (copyright) را بی‌اجازه نفرستید!'''",
'copyrightwarning2' => "لطفاً توجه داشته باشید که فرض می‌شود کلیهٔ مشارکت‌های شما با {{SITENAME}} ممکن است توسط دیگر مشارکت‌کنندگان ویرایش شوند، تغییر یابند یا حذف شوند.
اگر نمی‌خواهید نوشته‌هایتان بی‌رحمانه ویرایش شود، اینجا نفرستیدشان.<br />
همچنین شما دارید به ما قول می‌دهید که خودتان این را نوشته‌اید، یا آن را از یک منبع آزاد با مالکیت عمومی یا مشابه آن برداشته‌اید (برای جزئیات بیشتر به $1 مراجعه کنید).
'''کارهای دارای حق تکثیر (copyright) را بی‌اجازه نفرستید!'''",
'longpageerror' => "'''خطا: متنی که ارسال کرده‌اید {{PULAR:$1|یک کیلوبایت|$1 کیلوبایت}} طول دارد. این مقدار از مقدار بیشینهٔ {{PLURAL:$2|یک کیلوبایت|$2 کیلوبایت}} بیشتر است.'''
نمی‌توان آن را ذخیره کرد.",
'readonlywarning' => "'''هشدار: پایگاه داده برای نگهداری قفل شده‌است، به همین علت هم‌اکنون نمی‌توانید ویرایش‌هایتان را ذخیره کنید.'''
اگر می‌خواهید متن را در یک پروندهٔ متنی کپی کنید و برای آینده ذخیره‌اش کنید.

مدیری که آن را قفل کرده این توضیح را ارائه کرده‌است: $1",
'protectedpagewarning' => "'''هشدار: این صفحه قفل شده است تا فقط کاربران با امتیاز مدیر بتوانند ویرایشش کنند.'''
آخرین موارد سیاهه در زیر آمده است:",
'semiprotectedpagewarning' => "'''توجه:''' این صفحه قفل شده‌است تا تنها کاربران ثبت‌نام‌کرده قادر به ویرایش آن باشند.
آخرین موارد سیاهه در زیر آمده‌است:",
'cascadeprotectedwarning' => "'''هشدار:''' این صفحه به علت قرارگرفتن در {{PLURAL:$1|صفحهٔ|صفحه‌های}} آبشاری-محافظت‌شدهٔ زیر قفل شده‌است تا فقط مدیران بتوانند ویرایشش کنند.",
'titleprotectedwarning' => "'''هشدار: این صفحه به شکلی قفل شده‌است که برای ایجاد آن [[Special:ListGroupRights|اختیارات خاصی]] لازم است.'''
آخرین موارد سیاهه در زیر آمده است:",
'templatesused' => '{{PLURAL:$1|الگوی|الگوهای}} به‌کاررفته در این صفحه:',
'templatesusedpreview' => '{{PLURAL:$1|الگوی|الگوهای}} استفاده شده در این پیش‌نمایش:',
'templatesusedsection' => '{{PLURAL:$1|الگوی|الگوهای}} استفاده شده در این بخش:',
'template-protected' => '(حفاظت‌شده)',
'template-semiprotected' => '(نیمه‌حفاظت‌شده)',
'hiddencategories' => 'این صفحه در {{PLURAL:$1|یک ردهٔ پنهان|$1 ردهٔ پنهان}} قرار دارد:',
'edittools' => '<!-- متن این قسمت زیر صفحه‌های ویرایش و بارگذاری نشان داده می‌شود -->',
'nocreatetitle' => 'ایجاد صفحه محدود شده‌است',
'nocreatetext' => '{{SITENAME}} قابلیت ایجاد صفحه‌های جدید را محدود کرده‌است.
می‌توانید بازگردید و صفحه‌ای موجود را ویرایش کنید یا اینکه  [[Special:UserLogin|به سامانه وارد شوید یا حساب کاربری ایجاد کنید]].',
'nocreate-loggedin' => 'شما اجازهٔ ایجاد صفحه‌های جدید را ندارید.',
'sectioneditnotsupported-title' => 'ویرایش بخش‌ها پشتیبانی نمی‌شود',
'sectioneditnotsupported-text' => 'این صفحه از ویرایش بخش‌ها پشتیبانی نمی‌کند.',
'permissionserrors' => 'خطای سطح دسترسی',
'permissionserrorstext' => 'شما اجازهٔ انجام این کار را به این {{PLURAL:$1|دلیل|دلایل}} ندارید:',
'permissionserrorstext-withaction' => 'شما اجازهٔ $2 را به این {{PLURAL:$1|دلیل|دلایل}} ندارید:',
'recreate-moveddeleted-warn' => "'''هشدار: شما در حال ایجاد صفحه‌ای هستید که قبلاً حذف شده‌است.'''

در نظر داشته باشید که آیا ادامهٔ ویرایش این صفحه کار درستی‌است یا نه.
سیاههٔ حذف و انتقال این صفحه در زیر نشان داده شده‌است:",
'moveddeleted-notice' => 'این صفحه حذف شده‌است.
در زیر سیاههٔ حذف و انتقال این صفحه نمایش داده شده‌است.',
'log-fulllog' => 'مشاهدهٔ سیاههٔ کامل',
'edit-hook-aborted' => 'ویرایش توسط قلاب لغو شد.
توضیحی در این مورد داده نشد.',
'edit-gone-missing' => 'امکان به‌روز کردن صفحه وجود ندارد.
به نظرمی‌رسد که صفحه حذف شده باشد.',
'edit-conflict' => 'تعارض ویرایشی.',
'edit-no-change' => 'ویرایش شما نادیده گرفته شد، زیرا تغییری در متن داده نشده بود.',
'edit-already-exists' => 'امکان ساختن صفحهٔ جدید وجود ندارد.
این صفحه از قبل وجود داشته‌است.',
'defaultmessagetext' => 'متن پیش‌فرض پیغام',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''هشدار:''' این صفحه حاوی تعدادی زیادی فراخوانی دستورهای تجزیه‌گر است.

تعداد آن‌ها باید کمتر از $2 {{PLURAL:$2|فراخوانی|فراخوانی}} باشد، و اینک {{PLURAL:$1|$1 فراخوانی|$1 فراخوانی}} است.",
'expensive-parserfunction-category' => 'صفحه‌هایی که حاوی تعداد زیادی فراخوانی سنگین دستورهای تجزیه‌گر هستند',
'post-expand-template-inclusion-warning' => 'هشدار: الگو بیش از اندازه بزرگ است.
برخی الگوها ممکن است شامل نشوند.',
'post-expand-template-inclusion-category' => 'صفحه‌هایی که تعداد الگوهای به‌کاررفته در آن‌ها بیش از اندازه است',
'post-expand-template-argument-warning' => "'''هشدار:''' این صفحه شامل دست کم یک پارامتر الگو است که بیش از اندازه بزرگ است.
این پارامترها نادیده گرفته شدند.",
'post-expand-template-argument-category' => 'صفحه‌های حاوی الگوهایی با پارامترهای نادیده‌گرفته‌شده',
'parser-template-loop-warning' => 'حلقه در الگو پیدا شد: [[$1]]',
'parser-template-recursion-depth-warning' => 'محدودیت عمق بازگشت الگو رد شد ($1)',
'language-converter-depth-warning' => 'محدودیت عمق مبدل زبانی رد شد ($1)',
'node-count-exceeded-category' => 'صفحه‌هایی که از حداکثر تعداد گره تجاوز کرده‌اند',
'node-count-exceeded-warning' => 'صفحه از حداکثر تعداد گره تجاوز کرد',
'expansion-depth-exceeded-category' => 'صفحه‌هایی که از حداکثر عمق بسط دادن تجاوز کرده‌اند',
'expansion-depth-exceeded-warning' => 'صفحه حداکثر عمق بسط دادن تجاوز کرد',
'parser-unstrip-loop-warning' => 'حلقه در دستور unstrip پیدا شد',
'parser-unstrip-recursion-limit' => 'از حداکثر ارجاع در دستور unstrip تجاوز شد ($1)',
'converter-manual-rule-error' => 'خطا در قوانین مبدل دستی زبان',

# "Undo" feature
'undo-success' => 'این ویرایش را می‌توان خنثی کرد.
لطفاً تفاوت زیر را بررسی کنید تا تأیید کنید که این چیزی است که می‌خواهید انجام دهید، سپس تغییرات زیر را ذخیره کنید تا خنثی‌سازی ویرایش را به پایان ببرید.',
'undo-failure' => 'به علت تعارض با ویرایش‌های میانی، این ویرایش را نمی‌توان خنثی کرد.',
'undo-norev' => 'این ویرایش را نمی‌توان خنثی کرد چون وجود ندارد یا حذف شده‌است.',
'undo-summary' => 'خنثی‌سازی ویرایش $1 توسط [[Special:Contributions/$2|$2]] ([[User talk:$2|بحث]])',

# Account creation failure
'cantcreateaccounttitle' => 'نمی‌توان حساب باز کرد',
'cantcreateaccount-text' => "امكان ساختن حساب کاربری از این این نشانی آی‌پی ('''$1''') توسط [[User:$3|$3]] سلب شده است.

دلیل ارائه شده توسط $3 چنین است: $2",

# History pages
'viewpagelogs' => 'نمایش سیاهه‌های این صفحه',
'nohistory' => 'این صفحه تاریخچهٔ ویرایش ندارد.',
'currentrev' => 'نسخهٔ فعلی',
'currentrev-asof' => 'نسخهٔ کنونی تا $1',
'revisionasof' => 'نسخهٔ $1',
'revision-info' => 'نسخهٔ تاریخ $1 توسط $2',
'previousrevision' => '→ نسخهٔ قدیمی‌تر',
'nextrevision' => 'نسخهٔ جدیدتر ←',
'currentrevisionlink' => 'نمایش نسخهٔ فعلی',
'cur' => 'فعلی',
'next' => 'بعدی',
'last' => 'قبلی',
'page_first' => 'نخست',
'page_last' => 'واپسین',
'histlegend' => "انتخاب تفاوت: دکمه‌های گرد کنار ویرایش‌هایی که می‌خواهید با هم مقایسه کنید را علامت بزنید و دکمهٔ Enter را بزنید یا دکمهٔ پایین را فشار دهید.<br />
اختصارات: '''({{int:cur}})''' = تفاوت با نسخهٔ فعلی، '''({{int:last}})''' = تفاوت با نسخهٔ قبلی، '''({{int:minoreditletter}})''' = ویرایش جزئی.",
'history-fieldset-title' => 'مرور تاریخچه',
'history-show-deleted' => 'فقط حذف‌شده',
'histfirst' => 'قدیمی‌ترین',
'histlast' => 'جدیدترین',
'historysize' => '({{PLURAL:$1|۱ بایت|$1 بایت}})',
'historyempty' => '(خالی)',

# Revision feed
'history-feed-title' => 'تاریخچهٔ ویرایش‌ها',
'history-feed-description' => 'تاریخچهٔ ویرایش‌های این صفحه در ویکی',
'history-feed-item-nocomment' => '$1 در $2',
'history-feed-empty' => 'صفحهٔ درخواست شده وجود ندارد.
ممکن است که از ویکی حذف یا اینکه نامش تغییر داده شده باشد.
صفحه‌های جدید را برای موارد مرتبط در این ویکی [[Special:Search|جستجو کنید]].',

# Revision deletion
'rev-deleted-comment' => '(خلاصه ویرایش حذف شد)',
'rev-deleted-user' => '(نام کاربری حذف شد)',
'rev-deleted-event' => '(مورد از سیاهه پاک شده)',
'rev-deleted-user-contribs' => '[نام کاربری یا نشانی آی‌پی حذف شده - ویرایش مخفی شده در مشارکت‌ها]',
'rev-deleted-text-permission' => "این ویرایش از این صفحه '''حذف شده‌است'''.
ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.",
'rev-deleted-text-unhide' => "این ویرایش از این صفحه '''حذف شده‌است'''.
ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.
شما کماکان می‌توانید در صورت تمایل [$1 این نسخه را ببینید].",
'rev-suppressed-text-unhide' => "این ویرایش از این صفحه '''فرونشانده شده‌است'''.
ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سیاههٔ فرونشانی] موجود باشد.
شما کماکان می‌توانید در صورت تمایل [$1 این نسخه را ببینید].",
'rev-deleted-text-view' => "این ویرایش از این صفحه '''حذف شده‌است'''.
شما می‌توانید آن را ببینید؛ ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.",
'rev-suppressed-text-view' => "این ویرایش از این صفحه '''فرونشانی شده‌است'''.
شما می‌توانید آن را ببینید؛ ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سیاههٔ فرونشانی] موجود باشد.",
'rev-deleted-no-diff' => "شما نمی‌توانید این تفاوت را مشاهده کنید زیرا یکی از دو نسخه '''حذف شده‌است'''.
ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.",
'rev-suppressed-no-diff' => "شما نمی‌توانید این تفاوت را مشاهده کنید زیرا یکی از نسخه‌ها '''حذف شده‌است'''.",
'rev-deleted-unhide-diff' => "یکی از دو نسخهٔ این تفاوت '''حذف شده‌است'''.
ممکن است اطلاعات مرتبط با آن در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.
شما کماکان می‌توانید در صورت تمایل [$1 این تفاوت را ببینید].",
'rev-suppressed-unhide-diff' => "یکی از نسخه‌های این تفاوت '''فرونشانی شده‌است'''.
ممکن است جزئیاتی در [{{fullurl:{{#Special:Log}}/suppress|page=سیاههٔ فرونشانی{{FULLPAGENAMEE}}}}] موجود باشد.
شما کماکان می‌توانید در صورت تمایل [$1 این تفاوت را ببینید].",
'rev-deleted-diff-view' => "یکی از نسخه‌های این تفاوت '''حذف شده‌است'''.
شما می‌توانید این تفاوت را ببینید؛ ممکن است جزئیاتی در [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] موجود باشد.",
'rev-suppressed-diff-view' => "یکی از نسخه‌های این تفاوت '''فرونشانی شده‌است'''.
شما می‌توانید این تفاوت را ببینید؛ ممکن است جزئیاتی در [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سیاههٔ فرونشانی] موجود باشد.",
'rev-delundel' => 'نمایش/نهفتن',
'rev-showdeleted' => 'نمایش',
'revisiondelete' => 'حذف/احیای نسخه‌ها',
'revdelete-nooldid-title' => 'نسخه هدف غیرمجاز',
'revdelete-nooldid-text' => 'شما نسخه‌های هدف را برای انجام این عمل مشخص نکرده‌اید یا این نسخه‌ها وجود ندارند، یا این که شما می‌خواهید آخرین نسخه را پنهان کنید.',
'revdelete-nologtype-title' => 'نوع سیاهه مشخص نشده‌است',
'revdelete-nologtype-text' => 'شما هیچ نوع سیاهه‌ای را برای این کار مشخص نکردید.',
'revdelete-nologid-title' => 'مورد غیرمجاز در سیاهه',
'revdelete-nologid-text' => 'شما یا رویدادی را در سیاههٔ هدف مشخص نکردید یا موردی را مشخص کردید که وجود ندارد.',
'revdelete-no-file' => 'پروندهٔ مشخص شده وجود ندارد.',
'revdelete-show-file-confirm' => 'آیا مطمئن هستید که می‌خواهید یک نسخهٔ حذف شده از پروندهٔ «<nowiki>$1</nowiki>» مورخ $2 ساعت $3 را ببینید؟',
'revdelete-show-file-submit' => 'بله',
'revdelete-selected' => "'''{{PLURAL:$2|نسخهٔ|نسخه‌های}} انتخاب شده از [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|مورد|موارد}} انتخاب شده از سیاهه:'''",
'revdelete-text' => "'''نسخه‌ها و موارد حذف شده کماکان از طریق تاریخچهٔ صفحه و سیاهه‌ها قابل مشاهده هستند، اما بخش‌هایی از محتوای آن‌ها توسط عموم قابل مشاهده نخواهد بود.'''
سایر مدیران {{SITENAME}} هنوز می‌توانند این محتوای پنهان را ببینند و از همین طریق موارد حذف شده را احیا کنند، مگر آن که محدودیت‌های دیگری اعمال گردد.",
'revdelete-confirm' => 'لطفاً تأیید کنید که می‌خواهید این کار را انجام دهید، عواقب آن را درک می‌کنید و این کار را طبق [[{{MediaWiki:Policy-url}}|سیاست‌ها]] انجام می‌دهید.',
'revdelete-suppress-text' => "فرونشانی باید '''تنها''' برای موارد زیر استفاده شود:
* اطلاعات به طور بالقوه افتراآمیز
* اطلاعات نامناسب شخصی
*: ''نشانی منزل، شماره تلفن، شماره تامین اجتماعی و غیره.''",
'revdelete-legend' => 'تنظیم محدودیت‌های پیدایی',
'revdelete-hide-text' => 'نهفتن متن نسخه',
'revdelete-hide-image' => 'نهفتن محتویات پرونده',
'revdelete-hide-name' => 'نهفتن عمل و هدف',
'revdelete-hide-comment' => 'نهفتن توضیح ویرایش',
'revdelete-hide-user' => 'نام کاربری/نشانی آی‌پی ویراستار پنهان شود',
'revdelete-hide-restricted' => 'فرونشانی اطلاعات برای مدیران به همراه دیگران',
'revdelete-radio-same' => '(بدون تغییر)',
'revdelete-radio-set' => 'بله',
'revdelete-radio-unset' => 'خیر',
'revdelete-suppress' => 'از دسترسی مدیران به داده نیز مانند سایر کاربران جلوگیری به عمل آید.',
'revdelete-unsuppress' => 'خاتمهٔ محدودیت‌ها در مورد نسخه‌های انتخاب شده',
'revdelete-log' => 'دلیل:',
'revdelete-submit' => 'اعمال بر {{PLURAL:$1|نسخهٔ|نسخه‌های}} انتخاب شده',
'revdelete-success' => "'''پیدایی نسخه با موفقیت به روز شد.'''",
'revdelete-failure' => "'''پیدایی نسخه‌ها قابل به روز کردن نیست:'''
$1",
'logdelete-success' => 'تغییر پیدایی مورد با موفقیت انجام شد.',
'logdelete-failure' => "'''پیدایی سیاهه‌ها قابل تنظیم نیست:'''
$1",
'revdel-restore' => 'تغییر پیدایی',
'revdel-restore-deleted' => 'نسخه‌های حذف‌شده',
'revdel-restore-visible' => 'نسخه‌های پیدا',
'pagehist' => 'تاریخچهٔ صفحه',
'deletedhist' => 'تاریخچهٔ حذف‌شده',
'revdelete-hide-current' => 'خطا در پنهان کردن مورد مورخ $2 ساعت $1: این نسخه، نسخهٔ اخیر می‌باشد و قابل پنهان کردن نیست.',
'revdelete-show-no-access' => 'خطا در پنهان کردن مورد مورخ $2 ساعت $1: این نسخه علامت «محدودیت» دارد و شما به آن دسترسی ندارید.',
'revdelete-modify-no-access' => 'خطا در پنهان کردن مورد مورخ $2 ساعت $1: این نسخه علامت «محدودیت» دارد و شما به آن دسترسی ندارید.',
'revdelete-modify-missing' => 'خطا در پنهان کردن مورد شمارهٔ $1: این نسخه در پایگاه داده وجود ندارد!',
'revdelete-no-change' => "'''هشدار:''' مورد مورخ $2 ساعت $1 از قبل تنظیمات پیدایی درخواست شده را دارا بود.",
'revdelete-concurrent-change' => 'خطا در پنهان کردن مورد مورخ $2 ساعت $1: به نظر می‌رسد که در مدتی که شما برای تغییر وضعیت آن تلاش می‌کردید وضعیت آن توسط فرد دیگری تغییر یافته است.
لطفاً سیاهه‌ها را بررسی کنید.',
'revdelete-only-restricted' => 'خطا در پنهان کردن مورد مورخ $2 ساعت $1: شما نمی‌توانید موارد را از دید مدیران پنهان کنید مگر آن که یکی دیگر از گزینه‌های پنهان‌سازی را نیز انتخاب کنید.',
'revdelete-reason-dropdown' => '*دلایل متداول حذف
** نقض حق تکثیر
** اظهار نظر یا اطلاعات فردی نامناسب
** نام کاربری نامناسب
** اطلاعات به طور بالقوه افتراآمیز',
'revdelete-otherreason' => 'دلیل دیگر/اضافی:',
'revdelete-reasonotherlist' => 'دلیل دیگر',
'revdelete-edit-reasonlist' => 'ویرایش دلایل حذف',
'revdelete-offender' => 'نویسنده نسخه:',

# Suppression log
'suppressionlog' => 'سیاههٔ فرونشانی',
'suppressionlogtext' => 'در زیر فهرستی از آخرین حذف‌ها و قطع دسترسی‌هایی که حاوی محتوایی هستند که از مدیران پنهان شده‌اند را می‌بینید.
برای مشاهدهٔ فهرستی از قطع دسترسی‌های فعال [[Special:BlockList|فهرست بسته‌شده‌ها]] را ببینید.',

# History merging
'mergehistory' => 'ادغام تاریخچه صفحه‌ها',
'mergehistory-header' => 'این صفحه به شما این امکان را می‌دهد که نسخه‌های تاریخچهٔ یک مقاله را با یک مقاله دیگر ادغام کنید.
اطمینان حاصل کنید که این تغییر به توالی زمانی ویرایش‌ها لطمه نخواهد زد.',
'mergehistory-box' => 'ادغام نسخه‌های دو صفحه:',
'mergehistory-from' => 'صفحهٔ مبدأ:',
'mergehistory-into' => 'صفحهٔ مقصد:',
'mergehistory-list' => 'تاریخچهٔ قابل ادغام',
'mergehistory-merge' => 'نسخه‌های زیر از [[:$1]] قابل ادغام با [[:$2]] هستند.
از ستون دکمه‌های رادیویی استفاده کنید تا نسخه‌هایی را که تا قبل از زمانی مشخص ایجاد شده‌اند انتخاب کنید.
توجه کنید که کلیک روی پیوندها سبب بازگشت ستون به حالت اولیه می‌شود.',
'mergehistory-go' => 'نمایش تاریخچه قابل ادغام',
'mergehistory-submit' => 'ادغام نسخه‌ها',
'mergehistory-empty' => 'هیچ‌یک از نسخه‌ها قابل ادغام نیستند.',
'mergehistory-success' => '$3 نسخه از [[:$1]]  با موفقیت در [[:$2]] ادغام {{PLURAL:$3|شد|شدند}}.',
'mergehistory-fail' => 'ادغام تاریخچه ممکن نیست، لطفاً گزینه‌های صفحه و زمان را بازبینی کنید.',
'mergehistory-no-source' => 'صفحهٔ مبدأ $1 وجود ندارد.',
'mergehistory-no-destination' => 'صفحهٔ مقصد $1 وجود ندارد.',
'mergehistory-invalid-source' => 'صفحهٔ مبدأ باید عنوانی معتبر داشته باشد.',
'mergehistory-invalid-destination' => 'صفحهٔ مقصد باید عنوانی معتبر داشته باشد.',
'mergehistory-autocomment' => '[[:$1]] را در [[:$2]] ادغام کرد',
'mergehistory-comment' => '[[:$1]] را در [[:$2]] ادغام کرد: $3',
'mergehistory-same-destination' => 'صفحهٔ مبدأ و مقصد نمی‌تواند یکی باشد',
'mergehistory-reason' => 'دلیل:',

# Merge log
'mergelog' => 'سیاهه ادغام',
'pagemerge-logentry' => '[[$1]] در [[$2]] ادغام شد (نسخه‌های تا $3)',
'revertmerge' => 'واگردانی ادغام',
'mergelogpagetext' => 'در زیر سیاهه آخرین موارد ادغام تاریخچه یک صفحه در صفحه‌ای دیگر را می‌بینید.',

# Diffs
'history-title' => '$1: تاریخچهٔ ویرایش‌ها',
'difference-title' => '$1: تفاوت بین نسخه‌ها',
'difference-title-multipage' => '$1 و $2: تفاوت بین صفحه‌ها',
'difference-multipage' => '(تفاوت بین صفحه‌ها)',
'lineno' => 'سطر $1:',
'compareselectedversions' => 'مقایسهٔ نسخه‌های انتخاب‌شده',
'showhideselectedversions' => 'نمایش/نهفتن نسخه‌های انتخاب شده',
'editundo' => 'خنثی‌سازی',
'diff-multi' => '({{PLURAL:$1|یک|$1}} ویرایش میانی توسط {{PLURAL:$2|یک|$2}} کاربر نشان داده نشده‌است)',
'diff-multi-manyusers' => '({{PLURAL:$1|یک|$1}} ویرایش میانی توسط بیش از {{PLURAL:$2|یک|$2}} کاربر نشان داده نشده‌است)',
'difference-missing-revision' => '{{PLURAL:$2|یک ویرایش|$2 ویرایش}}  از تفاوت نسخه‌ها ($1) {{PLURAL:$2|یافت|یافت}}  نشد.

معمولاً در اثر پیوند به تاریخچهٔ به‌روز نشدهٔ صفحهٔ حذف شده است.
می‌توانید جزئیات بیشتر را در [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] بیابید.',

# Search results
'searchresults' => 'نتایج جستجو',
'searchresults-title' => 'نتایج جستجو برای «$1»',
'searchresulttext' => 'برای اطلاعات بیشتر دربارهٔ جستجوی {{SITENAME}}، به [[{{MediaWiki:Helppage}}|{{int:help}}]] مراجعه کنید.',
'searchsubtitle' => "شما '''[[:$1]]''' را جستجو کردید ([[Special:Prefixindex/$1|صفحه‌هایی که با «$1» شروع می‌شوند]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|صفحه‌هایی که به «$1» پیوند دارند]])",
'searchsubtitleinvalid' => 'برای پرس‌وجوی «$1»',
'toomanymatches' => 'تعداد موارد مطابق خیلی زیاد بود، لطفاً درخواست دیگری را امتحان کنید',
'titlematches' => 'تطبیق عنوان مقاله',
'notitlematches' => 'عنوان هیچ مقاله‌ای مطابقت ندارد',
'textmatches' => 'تطبیق متن مقاله',
'notextmatches' => 'متن هیچ مقاله‌ای مطابقت ندارد',
'prevn' => '{{PLURAL:$1|$1}}تای قبلی',
'nextn' => '{{PLURAL:$1|$1}}تای بعدی',
'prevn-title' => '$1 {{PLURAL:$1|نتیجهٔ|نتیجهٔ}} قبلی',
'nextn-title' => '$1 {{PLURAL:$1|نتیجهٔ|نتیجهٔ}} بعدی',
'shown-title' => 'نمایش $1 {{PLURAL:$1|نتیجه|نتیجه}} در هر صفحه',
'viewprevnext' => 'نمایش ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'گزینه‌های جستجو',
'searchmenu-exists' => "'''صفحه‌ای با عنوان \"[[:\$1]]\" در این ویکی وجود دارد.'''",
'searchmenu-new' => "'''صفحهٔ «[[:$1]]» را در این ویکی بسازید!'''",
'searchhelp-url' => 'Help:محتوا',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|مرور صفحه‌های با این پیشوند]]',
'searchprofile-articles' => 'صفحه‌های محتوایی',
'searchprofile-project' => 'صفحه‌های راهنما و پروژه',
'searchprofile-images' => 'چندرسانه‌ای',
'searchprofile-everything' => 'همه‌چیز',
'searchprofile-advanced' => 'پیشرفته',
'searchprofile-articles-tooltip' => 'جستجو در $1',
'searchprofile-project-tooltip' => 'جستجو در $1',
'searchprofile-images-tooltip' => 'جستجو برای پرونده‌ها',
'searchprofile-everything-tooltip' => 'جستجوی تمام محتوا (شامل صفحه‌های بحث)',
'searchprofile-advanced-tooltip' => 'جستجو در فضاهای نام دلخواه',
'search-result-size' => '$1 ({{PLURAL:$2|یک واژه|$2 واژه}})',
'search-result-category-size' => '{{PLURAL:$1|یک عضو|$1 عضو}} ({{PLURAL:$2|یک زیررده|$2 زیررده}}، {{PLURAL:$3|یک پرونده|$3 پرونده}})',
'search-result-score' => 'ارتباط: $1٪',
'search-redirect' => '(تغییرمسیر $1)',
'search-section' => '(بخش $1)',
'search-suggest' => 'آیا منظورتان این بود: $1',
'search-interwiki-caption' => 'پروژه‌های خواهر',
'search-interwiki-default' => '$1 نتیجه:',
'search-interwiki-more' => '(بیشتر)',
'search-relatedarticle' => 'مرتبط',
'mwsuggest-disable' => 'پیشنهادهای مبتنی بر AJAX را غیرفعال کن',
'searcheverything-enable' => 'جستجو در تمام فضاهای نام',
'searchrelated' => 'مرتبط',
'searchall' => 'همه',
'showingresults' => "نمایش حداکثر {{PLURAL:$1|'''۱''' نتیجه|'''$1''' نتیجه}} در پایین، آغاز از شماره '''$2'''.",
'showingresultsnum' => "نمایش حداکثر '''$3''' {{PLURAL:$3|نتیجه|نتیجه}} در پایین، آغاز از شماره '''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|نتیجهٔ '''$1''' از '''$3'''|نتایج '''$1 تا $2''' از '''$3'''}} برای '''$4'''",
'nonefound' => "'''نکته''': تنها بعضی از فضاهای نام به طور پیش‌فرض جستجو می‌شوند.
برای جستجوی تمام فضاهای نام (شامل صفحه‌های بحث، الگوها و غیره) به عبارت جستجوی خود پیشوند ''all:‎'' را بیفزایید، یا نام فضای نام دلخواه را به عنوان پیشوند استفاده کنید.",
'search-nonefound' => 'نتیجه‌ای منطبق با درخواست پیدا نشد.',
'powersearch' => 'جستجوی پیشرفته',
'powersearch-legend' => 'جستجوی پیشرفته',
'powersearch-ns' => 'جستجو در فضاهای نام:',
'powersearch-redir' => 'فهرست‌کردن تغییرمسیرها',
'powersearch-field' => 'جستجو برای',
'powersearch-togglelabel' => 'بررسی:',
'powersearch-toggleall' => 'همه',
'powersearch-togglenone' => 'هیچ‌کدام',
'search-external' => 'جستجوی خارجی',
'searchdisabled' => 'جستجو در {{SITENAME}} فعال نیست.
موقتاً می‌توانید از جستجوی Google استفاده کنید.
توجه کنید که نتایج حاصل از جستجو با آن روش ممکن است به‌روز نباشند.',

# Quickbar
'qbsettings' => 'نوار سریع',
'qbsettings-none' => 'نباشد',
'qbsettings-fixedleft' => 'ثابت چپ',
'qbsettings-fixedright' => 'ثابت راست',
'qbsettings-floatingleft' => 'شناور چپ',
'qbsettings-floatingright' => 'شناور راست',
'qbsettings-directionality' => 'ثابت، بسته به جهت نگارش زبان شما',

# Preferences page
'preferences' => 'ترجیحات',
'mypreferences' => 'ترجیحات',
'prefs-edits' => 'تعداد ویرایش‌ها:',
'prefsnologin' => 'به سامانه وارد نشده‌اید',
'prefsnologintext' => 'برای تنظیم ترجیحات کاربر باید <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} به سامانه وارد شوید]</span>.',
'changepassword' => 'تغییر گذرواژه',
'prefs-skin' => 'پوسته',
'skin-preview' => 'پیش‌نمایش',
'datedefault' => 'بدون ترجیح',
'prefs-beta' => 'ویژگی های بتا',
'prefs-datetime' => 'تاریخ و زمان',
'prefs-labs' => 'گزینه‌های آزمایشی',
'prefs-user-pages' => 'صفحه‌های کاربری',
'prefs-personal' => 'داده‌های کاربر',
'prefs-rc' => 'تغییرات اخیر',
'prefs-watchlist' => 'فهرست پی‌گیری‌ها',
'prefs-watchlist-days' => 'تعداد روزهایی که باید در فهرست پی‌گیری‌ها نمایش داده شود:',
'prefs-watchlist-days-max' => 'حداکثر $1 {{PLURAL:$1|روز}}',
'prefs-watchlist-edits' => 'تعداد ویرایش‌های نشان‌داده‌شده در فهرست پی‌گیری‌های گسترش‌یافته:',
'prefs-watchlist-edits-max' => 'حداکثر تعداد: ۱۰۰۰',
'prefs-watchlist-token' => 'رمز فهرست پی‌گیری:',
'prefs-misc' => 'متفرقه',
'prefs-resetpass' => 'تغییر گذرواژه',
'prefs-changeemail' => 'تغییر رایانامه',
'prefs-setemail' => 'تنظیم نشانی رایانامه',
'prefs-email' => 'گزینه‌های رایانامه',
'prefs-rendering' => 'نمایش صفحه',
'saveprefs' => 'ذخیره',
'resetprefs' => 'صفرکردن ترجیحات',
'restoreprefs' => 'برگرداندن تمام تنظیمات پیش‌فرض',
'prefs-editing' => 'در حال ویرایش',
'prefs-edit-boxsize' => 'اندازهٔ پنجرهٔ ویرایش.',
'rows' => 'تعداد سطرها:',
'columns' => 'تعداد ستون‌ها:',
'searchresultshead' => 'جستجو',
'resultsperpage' => 'تعداد نتایج در هر صفحه:',
'stub-threshold' => 'آستانهٔ ویرایش پیوندهای <a href="#" class="stub">ناقص</a> (بایت):',
'stub-threshold-disabled' => 'غیرفعال',
'recentchangesdays' => 'تعداد روزهای نمایش داده‌شده در تغییرات اخیر:',
'recentchangesdays-max' => 'حداکثر $1 {{PLURAL:$1|روز}}',
'recentchangescount' => 'تعداد پیش‌فرض ویرایش‌های نمایش یافته:',
'prefs-help-recentchangescount' => 'این گزینه شامل تغییرات اخیر، تاریخچهٔ صفحه‌ها و سیاهه‌ها می‌شود.',
'prefs-help-watchlist-token' => 'پرکردن این بخش با یک کلید رمز سبب ایجاد یک خوراک آراس‌اس برای فهرست پی‌گیری شما می‌شود.
هر کس که این کلید را بداند می‌تواند فهرست پی‌گیری شما را بخواند، پس مقداری ایمن انتخاب کنید.
مقدار تصادفی که برای شما ایجاد شده‌است: $1',
'savedprefs' => 'ترجیحات شما ذخیره شد.',
'timezonelegend' => 'منطقهٔ زمانی:',
'localtime' => 'زمان محلی:',
'timezoneuseserverdefault' => 'استفاده از پیش‌فرض ویکی ($1)',
'timezoneuseoffset' => 'دیگر (اختلاف را مشخص کنید)',
'timezoneoffset' => 'اختلاف¹:',
'servertime' => 'زمان سرور:',
'guesstimezone' => 'از مرورگر گرفته شود',
'timezoneregion-africa' => 'آفریقا',
'timezoneregion-america' => 'آمریکا',
'timezoneregion-antarctica' => 'قطب جنوبی',
'timezoneregion-arctic' => 'قطب شمالی',
'timezoneregion-asia' => 'آسیا',
'timezoneregion-atlantic' => 'اقیانوس اطلس',
'timezoneregion-australia' => 'استرالیا',
'timezoneregion-europe' => 'اروپا',
'timezoneregion-indian' => 'اقیانوس هند',
'timezoneregion-pacific' => 'اقیانوس آرام',
'allowemail' => 'امکان دریافت رایانامه از دیگر کاربران',
'prefs-searchoptions' => 'جستجو',
'prefs-namespaces' => 'فضاهای نام',
'defaultns' => 'در غیر این صورت جستجو در این فضاهای نام:',
'default' => 'پیش‌فرض',
'prefs-files' => 'پرونده‌ها',
'prefs-custom-css' => 'سی‌اس‌اس شخصی',
'prefs-custom-js' => 'جاوااسکریپت شخصی',
'prefs-common-css-js' => 'سی‌اس‌اس/جاوااسکریپت مشترک برای تمام پوسته‌ها:',
'prefs-reset-intro' => 'شما می‌توانید از این صفحه برای بازگرداندن تنظیمات خود به پیش‌فرض تارنما استفاده کنید.
این کار بازگشت‌ناپذیر است.',
'prefs-emailconfirm-label' => 'تأیید رایانامه:',
'prefs-textboxsize' => 'اندازهٔ جعبهٔ ویرایش',
'youremail' => 'رایانامه:',
'username' => 'نام کاربری:',
'uid' => 'شناسهٔ کاربر:',
'prefs-memberingroups' => 'عضو این {{PLURAL:$1|گروه|گروه‌ها}}:',
'prefs-registration' => 'زمان ثبت‌نام:',
'yourrealname' => 'نام واقعی:',
'yourlanguage' => 'زبان:',
'yourvariant' => 'گویش زبان محتوا:',
'prefs-help-variant' => 'گویش انتخابی شما برای نمایش محتوای صفحه‌ها در این ویکی.',
'yournick' => 'امضای جدید:',
'prefs-help-signature' => 'نظرهای نوشته شده در صفحهٔ بحث باید با «<nowiki>~~~~</nowiki>» امضا شوند؛ این علامت به طور خودکار به امضای شما و مهر تاریخ تبدیل خواهد شد.',
'badsig' => 'امضای خام غیرمجاز.
لطفاً برچسب‌های اچ‌تی‌ام‌ال را بررسی کنید.',
'badsiglength' => 'امضای شما بیش از اندازه طولانی است.
امضا باید کمتر از $1 {{PLURAL:$1|نویسه}} طول داشته باشد.',
'yourgender' => 'جنسیت:',
'gender-unknown' => 'مشخص‌نشده',
'gender-male' => 'مرد',
'gender-female' => 'زن',
'prefs-help-gender' => 'اختیاری: برای خطاب‌شدن با جنسیت درست توسط نرم‌افزار به کار می‌رود.
این اطلاعات عمومی خواهد بود.',
'email' => 'رایانامه',
'prefs-help-realname' => 'نام واقعی اختیاری است.
اگر آن را وارد کنید هنگام ارجاع به آثارتان و انتساب آن‌ها به شما از نام واقعی‌تان استفاده خواهد شد.',
'prefs-help-email' => 'نشانی رایانامه اختیاری‌است، اما فرستادن گذرواژه‌ای جدید را اگر گذرواژهٔ خود را فراموش کنید ممکن می‌کند.',
'prefs-help-email-others' => 'شما همچنین می‌توانید انتخاب کنید که کاربران بتوانند از طریق پیوندی در صفحهٔ کاربری یا صفحهٔ بحث کاربری‌تان به شما رایانامه بفرستند.
نشانی رایانامه شما زمانی که دیگران با شما تماس بگیرند فاش نمی‌شود.',
'prefs-help-email-required' => 'نشانی رایانامه الزامی‌است.',
'prefs-info' => 'اطلاعات اولیه',
'prefs-i18n' => 'بین‌المللی‌سازی',
'prefs-signature' => 'امضا',
'prefs-dateformat' => 'آرایش تاریخ',
'prefs-timeoffset' => 'فاصلهٔ زمانی',
'prefs-advancedediting' => 'گزینه‌های پیشرفته',
'prefs-advancedrc' => 'گزینه‌های پیشرفته',
'prefs-advancedrendering' => 'گزینه‌های پیشرفته',
'prefs-advancedsearchoptions' => 'گزینه‌های پیشرفته',
'prefs-advancedwatchlist' => 'گزینه‌های پیشرفته',
'prefs-displayrc' => 'گزینه‌های نمایش',
'prefs-displaysearchoptions' => 'گزینه‌های نمایش',
'prefs-displaywatchlist' => 'گزینه‌های نمایش',
'prefs-diffs' => 'تفاوت‌ها',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'نشانی رایانامه معتبر به نظر می رسد',
'email-address-validity-invalid' => 'نشانی رایانامهٔ معتبر وارد کنید',

# User rights
'userrights' => 'مدیریت اختیارات کاربر',
'userrights-lookup-user' => 'مدیریت گروه‌های کاربری',
'userrights-user-editname' => 'یک نام کاربری وارد کنید:',
'editusergroup' => 'ویرایش گروه‌های کاربری',
'editinguser' => "تغییر اختیارات کاربری کاربر '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'ویرایش گروه‌های کاربری',
'saveusergroups' => 'ثبت گروه‌های کاربری',
'userrights-groupsmember' => 'عضو:',
'userrights-groupsmember-auto' => 'عضو ضمنی:',
'userrights-groups-help' => 'شما می‌توانید گروه‌هایی را که کاربر در آن قرار دارد تغییر دهید:
* جعبهٔ علامت‌خورده نشانهٔ بودن کاربر در آن گروه است.
* جعبهٔ خالی نشانهٔ نبودن کاربر در آن گروه است.
* علامت * به این معنی‌است که اگر آن گروه را بیفزایید نمی‌توانید بعداً برش دارید، و برعکس.',
'userrights-reason' => 'دلیل:',
'userrights-no-interwiki' => 'شما اجازهٔ تغییر اختیارات کاربران دیگر ویکی‌ها را ندارید.',
'userrights-nodatabase' => 'پایگاه دادهٔ $1 وجود ندارد یا محلی نیست.',
'userrights-nologin' => 'شما باید با یک حساب کاربری مدیر [[Special:UserLogin|به سامانه وارد شوید]] تا بتوانید اختیارات کاربران را تعیین کنید.',
'userrights-notallowed' => 'حساب کاربری شما اجازه افزودن یا حذف کردن اختیارات کاربری را ندارد.',
'userrights-changeable-col' => 'گروه‌هایی که می‌توانید تغییر دهید',
'userrights-unchangeable-col' => 'گروه‌هایی که نمی‌توانید تغییر دهید',

# Groups
'group' => 'گروه:',
'group-user' => 'کاربران',
'group-autoconfirmed' => 'کاربران تاییدشده',
'group-bot' => 'ربات‌ها',
'group-sysop' => 'مدیران',
'group-bureaucrat' => 'دیوان‌سالاران',
'group-suppress' => 'ناظران',
'group-all' => '(همه)',

'group-user-member' => '{{GENDER:$1|کاربر}}',
'group-autoconfirmed-member' => '{{GENDER:$1|کاربر تاییدشده}}',
'group-bot-member' => 'ربات',
'group-sysop-member' => '{{GENDER:$1|مدیر}}',
'group-bureaucrat-member' => '{{GENDER:$1|دیوانسالار}}',
'group-suppress-member' => '{{GENDER:$1|نظارت}}',

'grouppage-user' => '{{ns:project}}:کاربران',
'grouppage-autoconfirmed' => '{{ns:project}}:کاربران تأییدشده',
'grouppage-bot' => '{{ns:project}}:ربات‌ها',
'grouppage-sysop' => '{{ns:project}}:مدیران',
'grouppage-bureaucrat' => '{{ns:project}}:دیوان‌سالاران',
'grouppage-suppress' => '{{ns:project}}:نظارت',

# Rights
'right-read' => 'خواندن صفحه',
'right-edit' => 'ویرایش صفحه',
'right-createpage' => 'ایجاد صفحه (در مورد صفحه‌های غیر بحث)',
'right-createtalk' => 'ایجاد صفحه‌های بحث',
'right-createaccount' => 'ایجاد حساب‌های کاربری',
'right-minoredit' => 'علامت‌زدن ویرایش‌ها به عنوان جزئی',
'right-move' => 'انتقال صفحه',
'right-move-subpages' => 'انتقال صفحه‌ها به همراه زیر‌صفحه‌هایشان',
'right-move-rootuserpages' => 'انتقال صفحه‌های کاربری سرشاخه',
'right-movefile' => 'انتقال پرونده‌ها',
'right-suppressredirect' => 'انتقال صفحه بدون ایجاد تغییرمسیر از نام قبلی',
'right-upload' => 'بارگذاری پرونده',
'right-reupload' => 'بارگذاری دوبارهٔ پرونده‌ای که از قبل وجود دارد',
'right-reupload-own' => 'بارگذاری دوبارهٔ پرونده‌ای که پیش از این توسط همان کاربر بارگذاری شده‌است',
'right-reupload-shared' => 'باطل‌کردن محلی پرونده‌های مشترک',
'right-upload_by_url' => 'بارگذاری پرونده از یک نشانی اینترنتی',
'right-purge' => 'پاک‌کردن میانگیر صفحه بدون مشاهدهٔ صفحهٔ تأیید',
'right-autoconfirmed' => 'ویرایش صفحه‌های نیمه‌محافظت‌شده',
'right-bot' => 'تلقی‌شده به عنوان یک فرآیند خودکار',
'right-nominornewtalk' => 'ویرایش جزئی صفحه‌های بحث به شکلی که باعث اعلان پیغام جدید نشود',
'right-apihighlimits' => 'سقف بالاتر استفاده از API',
'right-writeapi' => 'استفاده از API مربوط به نوشتن',
'right-delete' => 'حذف صفحه‌ها',
'right-bigdelete' => 'حذف صفحه‌های دارای تاریخچهٔ بزرگ',
'right-deletelogentry' => 'حذف و احیای مدخل‌های خاصی از سیاهه',
'right-deleterevision' => 'حذف و احیای نسخه‌های خاصی از صفحه',
'right-deletedhistory' => 'مشاهدهٔ موارد حذف‌شده از تاریخچه، بدون دیدن متن آن‌ها',
'right-deletedtext' => 'مشاهدهٔ متن حذف‌شده و تغییرات بین نسخه‌های حذف‌شده',
'right-browsearchive' => 'جستجوی صفحه‌های حذف‌شده',
'right-undelete' => 'احیای صفحه‌ها',
'right-suppressrevision' => 'بازبینی و احیای ویرایش‌هایی که از مدیران پنهان شده‌اند',
'right-suppressionlog' => 'مشاهدهٔ سیاهه‌های خصوصی',
'right-block' => 'قطع دسترسی ویرایشی دیگر کاربران',
'right-blockemail' => 'قطع دسترسی دیگر کاربران برای ارسال رایانامه',
'right-hideuser' => 'قطع دسترسی کاربر و پنهان کردن آن از دید عموم',
'right-ipblock-exempt' => 'تاثیر نپذیرفتن از قطع دسترسی‌های آی‌پی، خودکار یا فاصله‌ای',
'right-proxyunbannable' => 'تاثیر نپذیرفتن از قطع دسترسی خودکار پروکسی‌ها',
'right-unblockself' => 'دسترسی خود را باز کنند',
'right-protect' => 'تغییر میزان محافظت صفحه‌ها و ویرایش صفحه‌های محافظت شده',
'right-editprotected' => 'ویرایش صفحه‌های محافظت شده (به شرط نبود محافظت آبشاری)',
'right-editinterface' => 'ویرایش واسط کاربری',
'right-editusercssjs' => 'ویرایش صفحه‌های CSS و JS دیگر کاربرها',
'right-editusercss' => 'ویرایش صفحه‌های CSS دیگر کاربرها',
'right-edituserjs' => 'ویرایش صفحه‌های JS دیگر کاربرها',
'right-rollback' => 'واگردانی سریع ویرایش‌های آخرین کاربری که یک صفحه را ویرایش کرده‌است',
'right-markbotedits' => 'علامت زدن ویرایش‌های واگردانی شده به عنوان ویرایش ربات',
'right-noratelimit' => 'تاثیر نپذیرفتن از محدودیت سرعت',
'right-import' => 'وارد کردن صفحه از ویکی‌های دیگر',
'right-importupload' => 'وارد کردن صفحه از طریق بارگذاری پرونده',
'right-patrol' => 'گشت زدن به ویرایش‌های دیگران',
'right-autopatrol' => 'گشن زدن خودکار به ویرایش‌های خودش',
'right-patrolmarks' => 'مشاهدهٔ برچسب گشت تغییرات اخیر',
'right-unwatchedpages' => 'مشاهدهٔ فهرست صفحه‌هایی که پیگیری نمی‌شوند',
'right-mergehistory' => 'ادغام تاریخچهٔ صفحه‌ها',
'right-userrights' => 'ویرایش تمام اختیارات کاربرها',
'right-userrights-interwiki' => 'ویرایش اختیارات کاربرهای ویکی‌های دیگر',
'right-siteadmin' => 'قفل کردن و باز کردن پایگاه داده',
'right-override-export-depth' => 'برون‌بری صفحه‌ها شامل صفحه‌های پیوند شده تا عمق ۵',
'right-sendemail' => 'ارسال رایانامه به دیگر کاربران',
'right-passwordreset' => 'مشاهدهٔ نامه‌های تنظیم مجدد گذرواژه',

# User rights log
'rightslog' => 'سیاههٔ اختیارات کاربر',
'rightslogtext' => 'این سیاههٔ تغییرات اختیارات کاربر است.',
'rightslogentry' => 'عضویت $1 را از گروه $2 به $3 تغییر داد',
'rightslogentry-autopromote' => 'به طور خودکار از $2 به $3 ارتقا یافت',
'rightsnone' => '(هیچ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'خواندن این صفحه',
'action-edit' => 'ویرایش این صفحه',
'action-createpage' => 'ایجاد صفحه',
'action-createtalk' => 'ایجاد صفحه‌های بحث',
'action-createaccount' => 'ایجاد این حساب کاربری',
'action-minoredit' => 'علامت زدن این ویرایش به عنوان جزئی',
'action-move' => 'انتقال این صفحه',
'action-move-subpages' => 'انتقال این صفحه و زیرصفحه‌های آن',
'action-move-rootuserpages' => 'انتقال صفحه‌های کاربری سرشاخه',
'action-movefile' => 'این پرونده را انتقال بده',
'action-upload' => 'بارگذاری این پرونده',
'action-reupload' => 'نوشتن روی این پرونده موجود',
'action-reupload-shared' => 'باطل کردن این پرونده روی یک مخزن مشترک',
'action-upload_by_url' => 'بارگذاری این پرونده از یک نشانی اینترنتی',
'action-writeapi' => 'استفاده از API نوشتن',
'action-delete' => 'حذف این صفحه',
'action-deleterevision' => 'حذف این نسخه',
'action-deletedhistory' => 'مشاهدهٔ تاریخچهٔ حذف شدهٔ این صفحه',
'action-browsearchive' => 'جستجوی صفحه‌های حذف شده',
'action-undelete' => 'احیای این صفحه',
'action-suppressrevision' => 'مشاهده و احیای ویرایش‌های حذف شده',
'action-suppressionlog' => 'مشاهدهٔ این سیاههٔ خصوصی',
'action-block' => 'قطع دسترسی این کاربر از ویرایش کردن',
'action-protect' => 'تغییر سطح محافظت این صفحه',
'action-rollback' => 'واگردانی سریع ویرایش‌های آخرین کاربری که یک صفحه را ویرایش کرده‌است',
'action-import' => 'وارد کردن این صفحه از یک ویکی دیگر',
'action-importupload' => 'وارد کردن این صفحه از طریق بارگذاری پرونده',
'action-patrol' => 'گشت زدن ویرایش دیگران',
'action-autopatrol' => 'گشت زدن ویرایش خودتان',
'action-unwatchedpages' => 'مشاهدهٔ صفحه‌های پی‌گیری نشده',
'action-mergehistory' => 'ادغام تاریخچهٔ این صفحه',
'action-userrights' => 'ویرایش همهٔ اختیارات کاربری',
'action-userrights-interwiki' => 'ویرایش اختیارات کاربری کاربران یک ویکی دیگر',
'action-siteadmin' => 'قفل کردن و باز کردن پایگاه داده',
'action-sendemail' => 'ارسال ایمیل',

# Recent changes
'nchanges' => '$1 تغییر',
'recentchanges' => 'تغییرات اخیر',
'recentchanges-legend' => 'گزینه‌های تغییرات اخیر',
'recentchanges-summary' => 'آخرین تغییرات ویکی را در این صفحه پی‌گیری کنید.',
'recentchanges-feed-description' => 'آخرین تغییرات ویکی را در این خوراک پی‌گیری کنید.',
'recentchanges-label-newpage' => 'این ویرایش صفحه‌ای جدید ایجاد کرد',
'recentchanges-label-minor' => 'این ویرایش جزئی‌است',
'recentchanges-label-bot' => 'این ویرایش را یک ربات انجام داده‌است',
'recentchanges-label-unpatrolled' => 'این ویرایش هنوز گشت‌زنی نشده‌است',
'rcnote' => "در زیر {{PLURAL:$1|'''۱''' تغییر|آخرین '''$1''' تغییر}} در آخرین {{PLURAL:$2|روز|'''$2''' روز}} را، تا $4 ساعت $5 می‌بینید.",
'rcnotefrom' => 'در زیر تغییرات از تاریخ <b>$2</b> آمده‌اند (تا <b>$1</b> مورد نشان داده می‌شود).',
'rclistfrom' => 'نمایش تغییرات جدید با شروع از $1',
'rcshowhideminor' => '$1 ویرایش‌های جزئی',
'rcshowhidebots' => '$1 ربات‌ها',
'rcshowhideliu' => '$1 کاربران ثبت‌نام‌کرده',
'rcshowhideanons' => '$1 کاربران ناشناس',
'rcshowhidepatr' => '$1 ویرایش‌های گشت‌خورده',
'rcshowhidemine' => '$1 ویرایش‌های من',
'rclinks' => 'نمایش آخرین $1 تغییر در $2 روز اخیر<br />$3',
'diff' => 'تفاوت',
'hist' => 'تاریخچه',
'hide' => 'نهفتن',
'show' => 'نمایش',
'minoreditletter' => 'جز',
'newpageletter' => 'نو',
'boteditletter' => 'ر',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|کاربر|کاربر}} پی‌گیری‌کننده]',
'rc_categories' => 'محدود به این رده‌ها (رده‌ها را با «|» جدا کنید)',
'rc_categories_any' => 'هر کدام',
'rc-change-size-new' => '$1 {{PLURAL:$1|بایت}} پس از تغییر',
'newsectionsummary' => '/* $1 */ بخش جدید',
'rc-enhanced-expand' => 'نمایش جزئیات (نیازمند جاوااسکریپت)',
'rc-enhanced-hide' => 'نهفتن جزئیات',
'rc-old-title' => 'ایجادشده با عنوان اصلی «$1»',

# Recent changes linked
'recentchangeslinked' => 'تغییرات مرتبط',
'recentchangeslinked-feed' => 'تغییرات مرتبط',
'recentchangeslinked-toolbox' => 'تغییرات مرتبط',
'recentchangeslinked-title' => 'تغییرات مرتبط با $1',
'recentchangeslinked-noresult' => 'در بازهٔ زمانی داده‌شده تغییری در صفحه‌های پیوندداده رخ نداده‌است.',
'recentchangeslinked-summary' => "در زیر فهرستی از تغییرات اخیر صفحه‌های پیوند داده شده از این صفحه (یا اعضای رده مورد نظر) را می‌بینید.
صفحه‌هایی که در [[Special:Watchlist|فهرست پی‌گیری‌هایتان]] باشند به صورت '''پررنگ''' نشان داده می‌شوند.",
'recentchangeslinked-page' => 'نام صفحه:',
'recentchangeslinked-to' => 'نمایش تغییرات صفحه‌هایی که به صفحهٔ داده‌شده پیوند دارند',

# Upload
'upload' => 'بارگذاری پرونده',
'uploadbtn' => 'بارگذاری پرونده',
'reuploaddesc' => 'بازگشت به فرم بارگذاری',
'upload-tryagain' => 'ارسال توضیحات تغییر یافته پرونده',
'uploadnologin' => 'به سامانه وارد نشده‌اید',
'uploadnologintext' => 'برای بارگذاری پرونده‌ها باید [[Special:UserLogin|به سامانه وارد شوید]].',
'upload_directory_missing' => 'شاخهٔ بارگذاری ($1) وجود ندارد و قابل ایجاد نیست.',
'upload_directory_read_only' => 'شاخهٔ بارگذاری ($1) از طرف سرور وب قابل نوشتن نیست.',
'uploaderror' => 'خطای بارگذاری',
'upload-recreate-warning' => "'''هشدار: پرونده‌ای با این نام حذف یا منتقل شده است.'''

برای راحتی، سیاههٔ حذف و انتقال برای این صفحه در زیر آمده است:",
'uploadtext' => "از فرم زیر برای بارگذاری کردن پرونده‌های جدید استفاده کنید.
برای دیدن پرونده‌هایی که قبلاً بارگذاری شده‌اند به [[Special:FileList|فهرست پرونده‌ها]] بروید. بارگذاری نیز مجدد در [[Special:Log/upload|سیاههٔ بارگذاری‌ها]] و حذف پرونده‌ها در [[Special:Log/delete|deletion log]] ثبت می‌شود.

بعد از این که پرونده‌ای را بارگذاری کردید، به این سه شکل می‌توانید آن را در صفحه‌ها استفاده کنید:
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' برای استفاده از نسخه کامل پرونده
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' برای استفاده از یک نسخه ۲۰۰ پیکسلی از پرونده درون یک جعبه در سمت چپ متن که عبارت alt text در آن به عنوان توضیح استفاده شده
*'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' برای ایجاد یک پیونده مستقیم به پرونده بدون نمایش پرونده",
'upload-permitted' => 'انواع مجاز پرونده‌ها: $1.',
'upload-preferred' => 'انواع ترجیح‌داده شده پرونده‌ها: $1.',
'upload-prohibited' => 'انواع غیرمجاز پرونده‌ها: $1.',
'uploadlog' => 'سیاههٔ بارگذاری‌ها',
'uploadlogpage' => 'سیاههٔ بارگذاری‌ها',
'uploadlogpagetext' => 'فهرست زیر فهرستی از آخرین بارگذاری پرونده‌ها است.
برای مرور دیداری [[Special:NewFiles|نگارخانهٔ پرونده‌های جدید]] را ببینید.',
'filename' => 'نام پرونده',
'filedesc' => 'خلاصه',
'fileuploadsummary' => 'خلاصه:',
'filereuploadsummary' => 'تغییرات پرونده:',
'filestatus' => 'وضعیت حق تکثیر:',
'filesource' => 'منبع:',
'uploadedfiles' => 'پرونده‌های بارشده',
'ignorewarning' => 'چشم‌پوشی از هشدار و ذخیرهٔ پرونده.',
'ignorewarnings' => 'چشم‌پوشی از همهٔ هشدارها',
'minlength1' => 'نام پرونده دست کم باید یک حرف باشد.',
'illegalfilename' => 'نام پرونده «$1» نویسه‌هایی را شامل می‌شود که در نام صفحه‌ها مجاز نیستند.
لطفاً نام پرونده را تغییر دهید و آن را دوباره بارگذاری کنید.',
'filename-toolong' => 'نام پرونده نباید از ۲۴۰ بایت طولانی‌تر باشد.',
'badfilename' => 'نام پرونده به «$1» تغییر کرد.',
'filetype-mime-mismatch' => 'پسوند پرونده «$1.‎» با نوع MIME آن ($2) مطابقت ندارد.',
'filetype-badmime' => 'پرونده‌هایی که نوع MIME آن‌ها $1 باشد برای بارگذاری مجاز نیستند.',
'filetype-bad-ie-mime' => 'این پرونده را نمی‌توانید بارگذاری کنید زیرا اینترنت اکسپلورر آن را به عنوان «$1» تشخیص می‌دهد، که یک نوع پروندهٔ غیرمجاز و احتمالاً خطرناک است.',
'filetype-unwanted-type' => "'''«‎.‎$1»''' یک نوع پرونده ناخواسته است.
{{PLURAL:$3|نوع پرونده ترجیح داده شده|انواع پرونده ترجیح داده شده}} از این قرار است: $2 .",
'filetype-banned-type' => '&lrm;\'\'\'".$1"\'\'\' {{PLURAL:$4|یک نوع پرونده غیرمجاز است|انواعی پرونده غیرمجاز هستند}}.
{{PLURAL:$3|نوع پرونده مجاز|انواع پرونده مجاز}} از این قرار است: $2 .',
'filetype-missing' => 'این پرونده پسوند (مثلاً «‎.jpg») ندارد.',
'empty-file' => 'پرونده‌ای که ارسال کردید خالی بود.',
'file-too-large' => 'پرونده‌ای که ارسال کردید بیش از اندازه بزرگ بود.',
'filename-tooshort' => 'نام پرونده بیش از اندازه کوتاه است.',
'filetype-banned' => 'این نوع پرونده ممنوع است.',
'verification-error' => 'پرونده از آزمون تأیید پرونده گذر نکرد.',
'hookaborted' => 'تغییری که می‌خواستید ایجاد کنید توسط یک قلاب افزونه خاتمه ناگهانی داده شد.',
'illegal-filename' => 'نام پرونده مجاز نیست.',
'overwrite' => 'نوشتن روی یک پرونده موجود مجاز نیست.',
'unknown-error' => 'خطای ناشناخته‌ای رخ داد.',
'tmp-create-error' => 'امکان ساخت پرونده موقت وجود نداشت.',
'tmp-write-error' => 'خطا در نوشتن پرونده موقت.',
'large-file' => 'توصیه می‌شود که پرونده‌ها بزرگتر از $1 نباشند؛
اندازهٔ این پرونده $2 است.',
'largefileserver' => 'این پرونده از اندازه‌ای که سرور پیکربندی شده تا بپذیرد بزرگتر است.',
'emptyfile' => 'پروندهٔ بارگذاری‌شده خالی به نظر می‌رسد.
این مشکل ممکن است به علت خطای تایپی در نام پرونده باشد.
لطفاً تأیید کنید که می‌خواهید این پرونده را با همین شرایط بارگذاری کنید.',
'windows-nonascii-filename' => 'این ویکی از نام پرونده با نویسه‌های خاص پشتیبانی نمی‌کند.',
'fileexists' => 'پرونده‌ای با همین نام از قبل موجود است، اگر مطمئن نیستید که می‌خواهید آن پرونده را تغییر دهید، لطفاً <strong>[[:$1]]</strong> را بررسی کنید.
[[$1|thumb]]',
'filepageexists' => 'صفحهٔ توضیح برای این پرونده از قبل در <strong>[[:$1]]</strong> ایجاد شده‌است، اما پرونده‌ای با این نام وجود ندارد.
خلاصه‌ای که وارد می‌کنید در صفحهٔ توضیح نمایش نخواهد یافت.
برای آن که خلاصه شما نمایش یابد، باید آن را به صورت دستی ویرایش کنید.
[[$1|thumb]]',
'fileexists-extension' => 'پرونده‌ای با نام مشابه وجود دارد: [[$2|thumb]]
* نام پرونده‌ای که بارگذاری کردید این بود:<strong>[[:$1]]</strong>
* نام پرونده‌ای که از قبل موجود است این است:<strong>[[:$2]]</strong>
لطفاً یک نام دیگر انتخاب کنید.',
'fileexists-thumbnail-yes' => "به نظر می‌رسد که این پرونده، یک تصویر کوچک شده (''بندانگشتی'' یا ''thumbnail'') باشد.
[[$1|thumb]]
لطفاً پروندهٔ <strong>[[:$1]]</strong> را بررسی کنید.
اگر پرونده‌ای که بررسی کردید، همین تصویر در اندازهٔ اصلی‌اش است، نیازی به بارگذاری یک نسخهٔ بندانگشتی اضافه نیست.",
'file-thumbnail-no' => "نام پرونده با <strong>$1</strong> آغاز می‌شود.
به نظر می‌رسد که این پرونده، یک تصویر ''بندانگشتی'' ''(thumbnail)'' از تصویر بزرگتر اصلی باشد.
اگر تصویر با اندازهٔ اصلی را دارید، آن را بارگذاری کنید؛ در غیر این صورت، نام پرونده را تغییر دهید.",
'fileexists-forbidden' => 'در حال حاضر، پرونده‌ای به همین نام وجود دارد، و قابل رونویسی نیست.
اگر هم‌چنان می‌خواهید که پروندهٔ خود را بارگذاری کنید، لطفاً برگردید و نام دیگری استفاده کنید.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'در حال حاضر، پرونده‌ای با همین نام در انبارهٔ مشترک پرونده‌ها وجود دارد.
اگر هنوز می‌خواهید پرونده خود را بار کنید، لطفاً برگردید و پروندهٔ موردنظر خود را با نام دیگری بار کنید.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'به نظر می‌رسد این پرونده نسخه‌ای تکراری از {{PLURAL:$1|پروندهٔ|پرونده‌های}} زیر باشد:',
'file-deleted-duplicate' => 'یک پرونده نظیر این پرونده ([[:$1]]) قبلاً حذف شده‌است.
شما باید تاریخچهٔ حذف آن پرونده را قبل از بارگذاری مجدد آن ببینید.',
'uploadwarning' => 'هشدار بارگذاری',
'uploadwarning-text' => 'لطفاً توضیحات پرونده را در زیر تغییر دهید و دوباره تلاش کنید.',
'savefile' => 'ذخیرهٔ پرونده',
'uploadedimage' => '«[[$1]]» را بارگذاری کرد',
'overwroteimage' => 'نسخه جدیدی از  «[[$1]]» را بارگذاری کرد',
'uploaddisabled' => 'بارگذاری غیرفعال است.',
'copyuploaddisabled' => 'بارگذاری از طریق نشانی اینترنتی غیرفعال است.',
'uploadfromurl-queued' => 'بارگذاری شما به صف اضافه شد.',
'uploaddisabledtext' => 'امکان بارگذاری پرونده غیرفعال است.',
'php-uploaddisabledtext' => 'بارگذاری پرونده‌های پی‌اچ‌پی غیرفعال است.
لطفاً تنظیمات file_uploads را بررسی کنید.',
'uploadscripted' => 'این صفحه حاوی کد اچ‌تی‌ام‌ال یا اسکریپتی است که ممکن است به‌نادرست توسط مرورگر وب تفسیر شود.',
'uploadvirus' => 'این پرونده ویروس دارد!
جزئیات : $1',
'uploadjava' => 'این پرونده یک پرونده زیپ است که حاوی پرونده‌ای از نوع ‎‎.class جاوا است.
بارگذاری پرونده‌های جاوا مجاز نیست، چرا که ممکن است اجازه دور زدن محدودیت‌های امنیتی را بدهند.',
'upload-source' => 'پرونده منبع',
'sourcefilename' => 'نام پروندهٔ اصلی:',
'sourceurl' => 'نشانی منبع:',
'destfilename' => 'نام پروندهٔ مقصد:',
'upload-maxfilesize' => 'حداکثر اندازهٔ پرونده: $1',
'upload-description' => 'توضیحات پرونده',
'upload-options' => 'گزینه‌های بارگذاری',
'watchthisupload' => 'پی‌گیری این پرونده',
'filewasdeleted' => 'پرونده‌ای با همین نام پیشتر بارگذاری و پس از آن پاک شده‌است.
شما باید $1 را قبل از بارگذاری مجدد آن ببینید.',
'filename-bad-prefix' => "نام پرونده‌ای که بارگذاری می‌کنید با '''$1''' آغاز می‌شود که یک پیشوند مخصوص تصاویر ثبت شده توسط دوربین‌های دیجیتال است.
لطفاً نامی بهتر برای پرونده برگزینید.",
'upload-success-subj' => 'بارگذاری با موفقیت انجام شد',
'upload-success-msg' => 'بارگذاری شما از [$2] موفق بود. این پرونده در اینجا قابل دسترسی است: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'مشکل در بارگذاری',
'upload-failure-msg' => 'مشکلی در بارگذاری شما از [$2] وجود داشت:

$1',
'upload-warning-subj' => 'هشدار بارگذاری',
'upload-warning-msg' => 'فرم بارگذاری مشکلی داشت [$2]. شما می‌توانید به [[Special:Upload/stash/$1|فرم بارگذاری]] بازگردید تا این اشکال را رفع کنید.',

'upload-proto-error' => 'پروتکل نادرست',
'upload-proto-error-text' => 'بارگذاری از دوردست به نشانی‌هایی که با <code dir=ltr>http://</code> یا <code dir=ltr>ftp://</code> آغاز شوند نیاز دارد.',
'upload-file-error' => 'خطای داخلی',
'upload-file-error-text' => 'هنگام تلاش برای ایجاد یک پروندهٔ  موقت در سرور یک خطای داخلی رخ داد.
لطفاً با یک [[Special:ListUsers/sysop|مدیر]] تماس بگیرید.',
'upload-misc-error' => 'خطای نامعلوم در بارگذاری',
'upload-misc-error-text' => 'هنگام بارگذاری، خطایی نامعلوم رخ داد.
لطفاً اطمینان حاصل کنید که نشانی اینترنتی معتبر و قابل دسترسی است و بعد دوباره تلاش کنید.
اگر مشکل همچنان برقرار بود با یکی از [[Special:ListUsers/sysop|مدیران]] تماس بگیرید.',
'upload-too-many-redirects' => 'نشانی اینترتی حاوی تعداد بیش از اندازه‌ای تغییرمسیر است',
'upload-unknown-size' => 'اندازهٔ نامشخص',
'upload-http-error' => 'یک خطای اچ‌تی‌تی‌پی رخ داد: $1',
'upload-copy-upload-invalid-domain' => 'بارگذاری کپی پرونده‌ها از این دامنه امکان‌پذیر نیست.',

# File backend
'backend-fail-stream' => 'نمی‌توان پروندهٔ $1 را ارسال کرد.',
'backend-fail-backup' => 'نمی‌توان نسخهٔ پشتیبان برای پروندهٔ $1 ایجاد کرد.',
'backend-fail-notexists' => 'پروندهٔ $1 وجود ندارد.',
'backend-fail-hashes' => 'دریافت هش‌های پرونده برای مقایسه ناموفق بود.',
'backend-fail-notsame' => 'پروندهٔ غیریکسانی در $1 وجود دارد.',
'backend-fail-invalidpath' => '$1 مسیر ذخیره‌سازی معتبری نیست.',
'backend-fail-delete' => 'نمی‌توان پروندهٔ $1 را حذف کرد.',
'backend-fail-alreadyexists' => 'پروندهٔ $1 از قبل وجود داشت.',
'backend-fail-store' => 'نمی‌توان پروندهٔ $1 را در $2 ذخیره کرد.',
'backend-fail-copy' => 'نمی‌توان پروندهٔ $1 را به $2 کپی کرد.',
'backend-fail-move' => 'نمی‌توان پروندهٔ $1 را به $2 منتقل کرد.',
'backend-fail-opentemp' => 'نمی‌توان پروندهٔ موقتی را باز کرد.',
'backend-fail-writetemp' => 'امکان نوشتن بر روی پروندهٔ موقتی وجود ندارد.',
'backend-fail-closetemp' => 'نمی‌توان پروندهٔ موقتی را بست.',
'backend-fail-read' => 'نمی‌توان پروندهٔ $1 را خواند.',
'backend-fail-create' => 'نمی‌توان بر روی پروندهٔ $1 اطلاعات نوشت.',
'backend-fail-maxsize' => 'نمی‌توان بر روی پروندهٔ $1 اطلاعات نوشت چون بزرگتر از {{PLURAL:$2|یک بایت|$2 بایت}} است.',
'backend-fail-readonly' => 'پشتیبان «$1» درحال حاضر در وضیت فقط خواندنی است. دلیل ارائه شده چنین است: «$2»',
'backend-fail-synced' => 'پرونده «$1» در پشتیبان‌های ذخیره داخلی در وضعیتی ناپایدار قرار دارد',
'backend-fail-connect' => 'ارتباط با پشیبان ذخیره «$1» برقرار نشد.',
'backend-fail-internal' => 'خطایی نامعلوم در پشتیبان ذخیره «$1» رخ داد.',
'backend-fail-contenttype' => 'تعیین نوع محتوای پرونده برای ذخیره در «$1» ناموفق بود.',
'backend-fail-batchsize' => 'دسته‌ای مشتمل بر $1 {{PLURAL:$1|عملکرد|عملکرد}} پرونده به پشتیبان ذخیره داده شد؛ حداکثر مجاز $2 {{PLURAL:$2|عملکرد|عملکرد}} است.',
'backend-fail-usable' => 'امکان خواندن یا نوشتن پروندهٔ $1 وجود نداشت چرا که سطح دسترسی کافی نیست یا شاخه/محفظهٔ مورد نظر وجود ندارد.',

# File journal errors
'filejournal-fail-dbconnect' => 'امکان وصل شدن به پایگاه داده دفترخانه برای پشتیبان ذخیره‌سازی «$1» وجود نداشت.',
'filejournal-fail-dbquery' => 'امکان به روز کردن پایگاه داده دفترخانه برای پشتیبان ذخیره‌سازی «$1» وجود نداشت.',

# Lock manager
'lockmanager-notlocked' => 'نمی‌توان قفل «$1» را گشود؛ چون قفل نشده‌است.',
'lockmanager-fail-closelock' => 'امکان بستن پرونده قفل شده "$1" وجود ندارد.',
'lockmanager-fail-deletelock' => 'امکان حذف پرونده قفل شده "$1" وجود ندارد.',
'lockmanager-fail-acquirelock' => 'نمی‌توان قفل «$1» را کسب کرد.',
'lockmanager-fail-openlock' => 'امکان باز کردن پرونده قفل شده "$1" وجود ندارد.',
'lockmanager-fail-releaselock' => 'نمی‌توان قفل «$1» را گشود.',
'lockmanager-fail-db-bucket' => 'امکان ارتباط با تعداد کافی پایگاه داده قفل‌ها در محفظه $1 وجود نداشت.',
'lockmanager-fail-db-release' => 'بازکردن قفل‌های پایگاه دادهٔ $1 ممکن نیست.',
'lockmanager-fail-svr-acquire' => 'امکان گرفتن قفل‌های سرور $1 وجود ندارد.',
'lockmanager-fail-svr-release' => 'امکان باز کردن قفل‌های سرور $1 وجود ندارد.',

# ZipDirectoryReader
'zip-file-open-error' => 'در هنگام باز کردن پرونده زیپ برای بررسی محتوای آن خطایی رخ داد.',
'zip-wrong-format' => 'پرونده مشخص شده یک پرونده زیپ نیست.',
'zip-bad' => 'پرونده زیپ خراب یا غیر قابل خواندن است.
نمی‌توان محتوای آن را از نظر امنیت به درستی بررسی کرد.',
'zip-unsupported' => 'پرونده زیپ از ویژگی‌هایی استفاده می‌کند که توسط مدیاویکی پشتیبانی نمی‌شوند.
نمی‌توان محتوای آن را از نظر امنیت به درستی بررسی کرد.',

# Special:UploadStash
'uploadstash' => 'انبار بارگذاری',
'uploadstash-summary' => 'این صفحه دسترسی به پرونده‌هایی که بارگذاری شده‌اند (یا در حال بارگذاری هستند) اما هنوز در ویکی منتشر نشده‌اند را فراهم می‌کند. این پرونده‌ها توسط هیچ کاربری به جز کسی که آن‌ها را بارگذاری کرده قابل دیدن نیستند.',
'uploadstash-clear' => 'پاک کردن پرونده‌های انبارشده',
'uploadstash-nofiles' => 'شما هیچ پروندهٔ انبارشده‌ای ندارید.',
'uploadstash-badtoken' => 'انجام این اقدام ناموفق بود، احتمالاً به این دلیل که اعتبار ویرایش شما به اتمام رسیده است. دوباره امتحان کنید.',
'uploadstash-errclear' => 'پاک کردن پرونده‌ها ناموفق بود.',
'uploadstash-refresh' => 'تازه کردن فهرست پرونده‌ها',
'invalid-chunk-offset' => 'جابجایی نامعتبر قطعه',

# img_auth script messages
'img-auth-accessdenied' => 'منع دسترسی',
'img-auth-nopathinfo' => 'PATH_INFO موجود نیست.
سرور شما برای ردکردن این مقدار تنظیم نشده‌است.
ممکن است مبتنی بر سی‌جی‌آی باشد و از img_auth پشتیبانی نکند.
https://www.mediawiki.org/wiki/Manual:Image_Authorization را ببینید.',
'img-auth-notindir' => 'مسیر درخواست شده در شاخهٔ بارگذاری تنظیم‌شده قرار ندارد.',
'img-auth-badtitle' => 'امکان ایجاد یک عنوان مجاز از «$1» وجود ندارد.',
'img-auth-nologinnWL' => 'شما به سامانه وارد نشده‌اید و «$1» در فهرست سفید قرار ندارد.',
'img-auth-nofile' => 'پرونده «$1» وجود ندارد.',
'img-auth-isdir' => 'شما تلاش کرده‌اید به شاخهٔ «$1» دسترسی پیدا کنید.
تنها دسترسی به پرونده مجاز است.',
'img-auth-streaming' => 'در حال جاری ساختن «$1».',
'img-auth-public' => 'عملکرد img_auth.php برونداد پرونده‌ها از یک ویکی خصوصی است.
این ویکی به عنوان یک ویکی عمومی تنظیم شده‌است.
برای امنیت بهینه، img_auth.php غیرفعال است.',
'img-auth-noread' => 'کاربر دسترسی خواندن «$1» را ندارد.',
'img-auth-bad-query-string' => 'آدرس اینترنتی شامل یک رشتهٔ نامعتبر درخواست است.',

# HTTP errors
'http-invalid-url' => 'نشانی نامعتبر: $1',
'http-invalid-scheme' => 'نشانی‌های اینترنتی با طرح «$1» پشتیبانی نمی‌شوند.',
'http-request-error' => 'درخواست اچ‌تی‌تی‌پی به علت خطایی ناشناخته، ناموفق بود.',
'http-read-error' => 'خطای خواندن اچ‌تی‌تی‌پی.',
'http-timed-out' => 'مهلت درخواست اچ‌تی‌تی‌پی به سر رسید.',
'http-curl-error' => 'خطا در آوردن نشانی اینترنتی: $1',
'http-host-unreachable' => 'دسترسی به نشانی اینترنتی ممکن نشد.',
'http-bad-status' => 'در حین درخواست اچ‌تی‌تی‌پی خطایی رخ داد: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'دسترسی به نشانی اینترنتی ممکن نشد',
'upload-curl-error6-text' => 'نشانی اینترنتی داده شده قابل دسترسی نیست.
لطفاً درستی آن و اینکه تارنما برقرار است را بررسی کنید.',
'upload-curl-error28' => 'مهلت بارگذاری به سر رسید',
'upload-curl-error28-text' => 'این تارنما بیش از اندازه در پاسخ تعلل کرد.
لطفاً بررسی کنید که آیا تارنما فعال و برخط است یا نه، سپس لختی درنگ کنید و دوباره تلاش نمایید.
شاید بد نباشد که در زمان خلوت‌تری دوباره تلاش کنید.',

'license' => 'اجازه‌نامه:',
'license-header' => 'اجازه‌نامه',
'nolicense' => 'هیچ کدام انتخاب نشده‌است',
'license-nopreview' => '(پیش‌نمایش وجود ندارد)',
'upload_source_url' => '(یک نشانی اینترنتی معتبر و قابل دسترسی برای عموم)',
'upload_source_file' => '(پرونده‌ای در رایانهٔ شما)',

# Special:ListFiles
'listfiles-summary' => 'این صفحهٔ ویژه تمام پرونده‌های بارگذاری شده را نمایش می‌دهد.
در صورت پالایش بر اساس کاربر، تنها پرونده‌هایی که آخرین نسخه‌شان توسط کاربر موردنظر بارگذاری شده باشد نمایش می‌یابند.',
'listfiles_search_for' => 'جستجو به دنبال نام پرونده چندرسانه‌ای:',
'imgfile' => 'پرونده',
'listfiles' => 'فهرست پرونده‌ها',
'listfiles_thumb' => 'بندانگشتی',
'listfiles_date' => 'تاریخ',
'listfiles_name' => 'نام',
'listfiles_user' => 'کاربر',
'listfiles_size' => 'اندازه',
'listfiles_description' => 'توضیح',
'listfiles_count' => 'نسخه‌ها',

# File description page
'file-anchor-link' => 'پرونده',
'filehist' => 'تاریخچهٔ پرونده',
'filehist-help' => 'روی تاریخ/زمان‌ها کلیک کنید تا نسخهٔ مربوط به آن هنگام را ببینید.',
'filehist-deleteall' => 'حذف همه',
'filehist-deleteone' => 'حذف این مورد',
'filehist-revert' => 'واگردانی',
'filehist-current' => 'نسخهٔ کنونی',
'filehist-datetime' => 'تاریخ/ساعت',
'filehist-thumb' => 'بندانگشتی',
'filehist-thumbtext' => 'تصویر بندانگشتی از نسخهٔ مورخ $1',
'filehist-nothumb' => 'فاقد بندانگشتی',
'filehist-user' => 'کاربر',
'filehist-dimensions' => 'ابعاد',
'filehist-filesize' => 'اندازهٔ پرونده',
'filehist-comment' => 'توضیح',
'filehist-missing' => 'پروندهٔ ناموجود',
'imagelinks' => 'به‌کاررفتن پرونده',
'linkstoimage' => '{{PLURAL:$1|صفحهٔ|صفحه‌های}} زیر به این تصویر پیوند {{PLURAL:$1|دارد|دارند}}:',
'linkstoimage-more' => 'بیش از $1 صفحه به این پرونده پیوند {{PLURAL:$1|دارد|دارند}}.
فهرست زیر تنها {{PLURAL:$1|اولین پیوند|اولین $1 پیوند}} به این صفحه را نشان می‌دهد.
[[Special:WhatLinksHere/$2|فهرست کامل]] نیز موجود است.',
'nolinkstoimage' => 'این پرونده در هیچ صفحه‌ای به کار نرفته‌است.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|پیوندهای دیگر]] به این پرونده را ببینید.',
'linkstoimage-redirect' => '$1 (تغییرمسیر پرونده) $2',
'duplicatesoffile' => '{{PLURAL:$1|پروندهٔ|پرونده‌های}} زیر نسخهٔ تکراری این پرونده {{PLURAL:$1|است|هستند}} ([[Special:FileDuplicateSearch/$2|اطلاعات بیشتر]]):',
'sharedupload' => 'این پرونده در $1 قرار دارد و ممکن است در دیگر پروژه‌ها هم استفاده شود.',
'sharedupload-desc-there' => 'این پرونده در $1 قرار دارد و ممکن است در دیگر پروژه‌ها هم استفاده شود.
برای اطلاعات بیشتر لطفاً [$2 صفحهٔ توضیحات پرونده] را ببینید.',
'sharedupload-desc-here' => 'این پرونده در $1 قرار دارد و ممکن است در پروژه‌های دیگر هم استفاده شود.
توضیحات موجود در [$2 صفحهٔ توضیحات پرونده در آنجا]، در زیر نشان داده شده‌است.',
'sharedupload-desc-edit' => 'این پرونده از $1 است و می‌تواند توسط پروژه‌های دیگر هم استفاده شود.
اگر خواستید می‌توانید توضیحات پرونده را از [$2 صفحهٔ توضیحاتش] در آنجا ویرایش کنید.',
'sharedupload-desc-create' => 'این پرونده از $1 است و می‌تواند توسط پروژه‌های دیگر هم استفاده شود.
اگر خواستید می‌توانید توضیحات پرونده را از [$2 صفحهٔ توضیحاتش] در آنجا ویرایش کنید.',
'filepage-nofile' => 'پرونده‌ای با این نام وجود ندارد.',
'filepage-nofile-link' => 'پرونده‌ای با این نام وجود ندارد، اما شما می‌توانید آن را [$1 بارگذاری کنید].',
'uploadnewversion-linktext' => 'بارگذاری نسخهٔ جدیدی از پرونده',
'shared-repo-from' => 'از $1',
'shared-repo' => 'یک مخزن مشترک',
'shared-repo-name-wikimediacommons' => 'ویکی‌انبار',
'upload-disallowed-here' => 'متاسفانه شما نمی توانید این پرونده را بازنویس کنید.',

# File reversion
'filerevert' => 'واگردانی $1',
'filerevert-legend' => 'واگردانی پرونده',
'filerevert-intro' => "شما در حال واگردانی '''[[Media:$1|$1]]''' به [$4 نسخهٔ مورخ $2 ساعت $3] هستید.",
'filerevert-comment' => 'دلیل:',
'filerevert-defaultcomment' => 'واگردانی به نسخهٔ $1 ساعت $2',
'filerevert-submit' => 'برو',
'filerevert-success' => "''[[Media:$1|$1]]''' به [$4 نسخهٔ مورخ $2 ساعت $3] واگردانده شد.",
'filerevert-badversion' => 'نسخهٔ قدیمی‌تری از این پرونده وجود نداشت.',

# File deletion
'filedelete' => 'حذف $1',
'filedelete-legend' => 'حذف پرونده',
'filedelete-intro' => "شما در حال حذف کردن پروندهٔ '''[[Media:$1|$1]]''' به همراه تمام تاریخچه‌اش هستید.",
'filedelete-intro-old' => "شما در حال حذف نسخه '''[[Media:$1|$1]]''' مورخ [$4 $2 ساعت $3] هستید.",
'filedelete-comment' => 'دلیل:',
'filedelete-submit' => 'حذف',
'filedelete-success' => "'''$1''' حذف شد.",
'filedelete-success-old' => "نسخهٔ '''[[Media:$1|$1]]''' مورخ $2 ساعت $3 حذف شد.",
'filedelete-nofile' => "'''$1''' وجود ندارد.",
'filedelete-nofile-old' => "نسخهٔ بایگانی‌شده‌ای از '''$1''' با مشخصات داده شده، وجود ندارد.",
'filedelete-otherreason' => 'دلیل دیگر/اضافی:',
'filedelete-reason-otherlist' => 'دلیل دیگر',
'filedelete-reason-dropdown' => '*دلایل متداول حذف
** نقض حق تکثیر
** پروندهٔ تکراری',
'filedelete-edit-reasonlist' => 'ویرایش دلایل حذف',
'filedelete-maintenance' => 'حذف و احیای پرونده‌ها در مدت نگهداری به طور موقت غیرفعال است.',
'filedelete-maintenance-title' => 'نمی‌تواند پرونده را حذف کند',

# MIME search
'mimesearch' => 'جستجوی بر اساس MIME',
'mimesearch-summary' => 'با کمک این صفحه شما می‌توانید پرونده‌هایی که یک نوع MIME به خصوص دارند را پیدا کنید.
ورودی: به صورت contenttype/subtype ، نظیر <code>image/jpeg</code>.',
'mimetype' => 'نوع MIME:',
'download' => 'بارگیری',

# Unwatched pages
'unwatchedpages' => 'صفحه‌های پی‌گیری‌نشده',

# List redirects
'listredirects' => 'فهرست صفحه‌های تغییرمسیر',

# Unused templates
'unusedtemplates' => 'الگوهای استفاده‌نشده',
'unusedtemplatestext' => 'این صفحه همهٔ صفحه‌هایی در فضای نام {{ns:template}} را که در هیچ صفحه‌ای به کار نرفته‌اند، فهرست می‌کند.
به یاد داشته باشید که پیش از پاک‌کردن این صفحه‌ها پیوندهای دیگر به آنها را هم وارسی کنید.',
'unusedtemplateswlh' => 'پیوندهای دیگر',

# Random page
'randompage' => 'مقالهٔ تصادفی',
'randompage-nopages' => 'هیچ صفحه‌ای در این {{PLURAL:$2|فضای نام|فضاهای نام}} موجود نیست: $1.',

# Random redirect
'randomredirect' => 'تغییرمسیر تصادفی',
'randomredirect-nopages' => 'هیج صفحهٔ تغییرمسیری در فضای نام «$1» موجود نیست.',

# Statistics
'statistics' => 'آمار',
'statistics-header-pages' => 'آمار صفحه‌ها',
'statistics-header-edits' => 'آمار ویرایش‌ها',
'statistics-header-views' => 'آمار بازدیدها',
'statistics-header-users' => 'آمار کاربران',
'statistics-header-hooks' => 'آمارهای دیگر',
'statistics-articles' => 'صفحه‌های محتوایی',
'statistics-pages' => 'صفحه‌ها',
'statistics-pages-desc' => 'تمام صفحه‌های این ویکی، از جمله صفحه‌های بحث، تغییرمسیر و غیره',
'statistics-files' => 'پرونده‌های بارگذاری شده',
'statistics-edits' => 'ویرایش صفحه‌ها از هنگامی که {{SITENAME}} راه‌اندازی شده',
'statistics-edits-average' => 'متوسط ویرایش‌ها به ازای هر صفحه',
'statistics-views-total' => 'مجموع بازدیدها',
'statistics-views-total-desc' => 'بازدید صفحه‌های ناموجود و صفحه‌های ویژه شامل نشده‌است',
'statistics-views-peredit' => 'تعداد بازدید به ازای هر ویرایش',
'statistics-users' => '[[Special:ListUsers|کاربران]] ثبت‌نام کرده',
'statistics-users-active' => 'کاربران فعال',
'statistics-users-active-desc' => 'کاربرانی که در {{PLURAL:$1|روز|$1 روز}} قبل فعالیتی انجام داده‌اند',
'statistics-mostpopular' => 'صفحه‌هایی که بیشترین تعداد بازدیدکننده را داشته‌اند',

'disambiguations' => 'صفحه‌های دارای پیوند به صفحه‌های ابهام‌زدایی',
'disambiguationspage' => 'Template:ابهام‌زدایی',
'disambiguations-text' => "صفحه‌های زیر حاوی حداقل یک پیوند به یک '''صفحهٔ ابهام‌زدایی''' هستند.
این صفحه‌ها شاید در عوض به موضوعات مرتبط پیوند داده شوند.<br />
یک صفحه هنگامی صفحهٔ ابهام‌زدایی در نظر گرفته می‌شود که در آن از الگویی که به [[MediaWiki:Disambiguationspage]] پیوند دارد استفاده شده باشد.",

'doubleredirects' => 'تغییرمسیرهای دوتایی',
'doubleredirectstext' => 'این صفحه فهرستی از صفحه‌های تغییرمسیری را ارائه می‌کند که به صفحهٔ تغییرمسیر دیگری اشاره می‌کنند.
هر سطر دربردارندهٔ پیوندهایی به تغییرمسیر اول و دوم و همچنین مقصد تغییرمسیر دوم است، که معمولاً صفحهٔ مقصد واقعی است و نخستین تغییرمسیر باید به آن اشاره کند.
موارد <del>خط خورده</del> درست شده‌اند.',
'double-redirect-fixed-move' => '[[$1]] انتقال داده شده‌است، و در حال حاضر تغییرمسیری به [[$2]] است',
'double-redirect-fixed-maintenance' => 'رفع تغییرمسیر دوتایی از [[$1]] به [[$2]].',
'double-redirect-fixer' => 'تعمیرکار تغییرمسیرها',

'brokenredirects' => 'تغییرمسیرهای خراب',
'brokenredirectstext' => 'تغییرمسیرهای زیر به یک صفحهٔ ناموجود پیوند دارند:',
'brokenredirects-edit' => 'ویرایش',
'brokenredirects-delete' => 'حذف',

'withoutinterwiki' => 'صفحه‌های بدون پیوند میان‌ویکی',
'withoutinterwiki-summary' => 'این صفحه‌ها پیوندی به صفحه‌ای به زبان دیگر نمی‌دارند:',
'withoutinterwiki-legend' => 'پیشوند',
'withoutinterwiki-submit' => 'نمایش',

'fewestrevisions' => 'مقاله‌های دارای کمترین شمار ویرایش',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بایت|بایت}}',
'ncategories' => '$1 {{PLURAL:$1|رده|رده}}',
'ninterwikis' => '$1 {{PLURAL:$1|میان‌ویکی|میان‌ویکی}}',
'nlinks' => '$1 {{PLURAL:$1|پیوند|پیوند}}',
'nmembers' => '$1 {{PLURAL:$1|عضو|عضو}}',
'nrevisions' => '$1 {{PLURAL:$1|نسخه|نسخه}}',
'nviews' => '$1 {{PLURAL:$1|بازدید|بازدید}}',
'nimagelinks' => 'مورد استفاده در $1 {{PLURAL:$1|صفحه|صفحه}}',
'ntransclusions' => 'در $1 {{PLURAL:$1|صفحه|صفحه}} استفاده شده‌است',
'specialpage-empty' => 'نتیجه‌ای برای این گزارش وجود ندارد.',
'lonelypages' => 'صفحه‌های یتیم',
'lonelypagestext' => 'به صفحه‌های زیر از هیچ صفحهٔ دیگری در {{SITENAME}} پیوند داده نشده‌است و در هیچ صفحهٔ دیگری گنجانده نشده‌اند.',
'uncategorizedpages' => 'صفحه‌های رده‌بندی‌نشده',
'uncategorizedcategories' => 'رده‌های رده‌بندی‌نشده',
'uncategorizedimages' => 'پرونده‌های رده‌بندی‌نشده',
'uncategorizedtemplates' => 'الگوهای رده‌بندی‌نشده',
'unusedcategories' => 'رده‌های استفاده‌نشده',
'unusedimages' => 'پرونده‌های استفاده‌نشده',
'popularpages' => 'صفحه‌های محبوب',
'wantedcategories' => 'رده‌های مورد نیاز',
'wantedpages' => 'صفحه‌های مورد نیاز',
'wantedpages-badtitle' => 'عنوان غیرمجاز در مجموعهٔ نتایج: $1',
'wantedfiles' => 'پرونده‌های مورد نیاز',
'wantedfiletext-cat' => 'پرونده‌های زیر استفاده می‌شوند اما موجود نیستند. همچنین ممکن است پرونده‌های مخازن خارجی با وجود موجود بودن در اینجا فهرست شوند. هرگونه رتبه مثبت کاذب <del>خط خواهد خورد.</del> علاوه بر این، صفحاتی که پرونده‌هایی ناموجود را در خود جای داده‌اند در [[:$1]] فهرست شده‌اند.',
'wantedfiletext-nocat' => 'پرونده‌های زیر استفاده می‌شوند اما موجود نیستند. همچنین ممکن است پرونده‌های مخازن خارجی با وجود موجود بودن در اینجا فهرست شوند. هرگونه رتبه مثبت کاذب <del>خط خواهد خورد.</del>',
'wantedtemplates' => 'الگوهای مورد نیاز',
'mostlinked' => 'صفحه‌هایی که بیشتر از همه به آن‌ها پیوند داده شده‌است',
'mostlinkedcategories' => 'رده‌هایی که بیشتر از همه به آن‌ها پیوند داده شده‌است',
'mostlinkedtemplates' => 'الگوهایی که بیشتر از همه به آن‌ها پیوند داده شده‌است',
'mostcategories' => 'صفحه‌های دارای بیشترین رده',
'mostimages' => 'پرونده‌هایی که بیشتر از همه به آن‌ها پیوند داده شده‌است',
'mostinterwikis' => 'صفحه‌های دارای بیشترین میان‌ویکی',
'mostrevisions' => 'صفحه‌های دارای بیشترین نسخه',
'prefixindex' => 'تمام صفحه‌ها با پیشوند',
'prefixindex-namespace' => 'همهٔ صفحه‌های دارای پیشوند (فضای‌نام $1)',
'shortpages' => 'صفحه‌های کوتاه',
'longpages' => 'صفحه‌های بلند',
'deadendpages' => 'صفحه‌های بن‌بست',
'deadendpagestext' => 'صفحه‌های زیر به هیچ صفحهٔ دیگری در {{SITENAME}} پیوند ندارند.',
'protectedpages' => 'صفحه‌های محافظت‌شده',
'protectedpages-indef' => 'فقط محافظت‌های بی‌پایان',
'protectedpages-cascade' => 'فقط محافظت‌های آبشاری',
'protectedpagestext' => 'صفحه‌های زیر در برابر ویرایش یا انتقال محافظت شده‌اند:',
'protectedpagesempty' => 'در حال حاضر هیچ‌صفحه‌ای محافظت نشده‌است.',
'protectedtitles' => 'عنوان‌های محافظت‌شده',
'protectedtitlestext' => 'عنوان‌های زیر از ایجاد محافظت شده‌اند',
'protectedtitlesempty' => 'در حال حاضر هیچ عنوانی با این پارامترها محافظت نشده‌است.',
'listusers' => 'فهرست کاربران',
'listusers-editsonly' => 'فقط کاربرانی که ویرایش دارند را نشان بده',
'listusers-creationsort' => 'مرتب کردن بر اساس تاریخ ایجاد',
'usereditcount' => '$1 {{PLURAL:$1|ویرایش|ویرایش}}',
'usercreated' => '{{GENDER:$3|ایجادشده}} در تاریخ $1 در ساعت $2',
'newpages' => 'صفحه‌های تازه',
'newpages-username' => 'نام کاربری:',
'ancientpages' => 'قدیمی‌ترین صفحه‌ها',
'move' => 'انتقال',
'movethispage' => 'انتقال این صفحه',
'unusedimagestext' => 'پرونده‌های زیر موجودند اما در هیچ صفحه‌ای به کار نرفته‌اند.
لطفاً توجه داشته باشید که دیگر وبگاه‌ها ممکن است با یک نشانی اینترنتی مستقیم به یک پرونده پیوند دهند، و با وجود این که در استفادهٔ فعال هستند در این جا فهرست شوند.',
'unusedcategoriestext' => 'این رده‌ها وجود دارند ولی هیچ مقاله یا ردهٔ دیگری از آنها استفاده نمی‌کند.',
'notargettitle' => 'مقصدی نیست',
'notargettext' => 'شما صفحهٔ یا کاربر مقصدی برای انجام این عمل روی آن مشخص نکرده‌اید.',
'nopagetitle' => 'چنین صفحه‌ای وجود ندارد',
'nopagetext' => 'صفحهٔ هدفی که شما مشخص کردید وجود ندارد.',
'pager-newer-n' => '{{PLURAL:$1|یک مورد جدیدتر|$1 مورد جدیدتر}}',
'pager-older-n' => '{{PLURAL:$1|یک مورد قدیمی‌تر|$1 مورد قدیمی‌تر}}',
'suppress' => 'نظارت',
'querypage-disabled' => 'این صفحه ویژه به دلایل عملکردی غیرفعال شده‌است.',

# Book sources
'booksources' => 'منابع کتاب',
'booksources-search-legend' => 'جستجوی منابع کتاب',
'booksources-isbn' => 'شابک:',
'booksources-go' => 'برو',
'booksources-text' => 'در زیر فهرستی از پیوندها به وبگاه‌های دیگر آمده‌است که کتاب‌های نو و دست دوم می‌فروشند، و همچنین ممکن است اطلاعات بیشتری راجع به کتاب مورد نظر شما داشته باشند:',
'booksources-invalid-isbn' => 'شابک داده شده مجاز به نظر نمی‌رسد؛ از جهت اشکالات هنگام کپی کردن از منبع اصلی بررسی کنید.',

# Special:Log
'specialloguserlabel' => 'مجری:',
'speciallogtitlelabel' => 'هدف (عنوان یا کاربر):',
'log' => 'سیاهه‌ها',
'all-logs-page' => 'تمام سیاهه‌های عمومی',
'alllogstext' => 'نمایش یک‌جای تمام سیاهه‌های موجود در {{SITENAME}}.
می‌توانید با انتخاب نوع سیاهه، نام کاربری (حساس به کوچکی و بزرگی حروف) و صفحه‌های تغییریافته (حساس به بزرگی و کوچکی حروف)، نمایش را محدودتر سازید.',
'logempty' => 'مورد منطبق با منظور شما در سیاهه یافت نشد.',
'log-title-wildcard' => 'صفحه‌هایی را جستجو کن که عنوانشان با این عبارت آغاز می‌شود',
'showhideselectedlogentries' => 'نمایش/نهفتن موارد انتخابی در سیاهه',

# Special:AllPages
'allpages' => 'همهٔ صفحه‌ها',
'alphaindexline' => '$1 تا $2',
'nextpage' => 'صفحهٔ بعد ($1)',
'prevpage' => 'صفحهٔ قبلی ($1)',
'allpagesfrom' => 'نمایش صفحه‌ها با شروع از:',
'allpagesto' => 'نمایش صفحه‌ها با پایان در:',
'allarticles' => 'همهٔ صفحه‌ها',
'allinnamespace' => 'همهٔ صفحه‌ها (فضای نام $1)',
'allnotinnamespace' => 'همهٔ صفحه‌ها (که در فضای نام $1 است)',
'allpagesprev' => 'قبلی',
'allpagesnext' => 'بعدی',
'allpagessubmit' => 'برو',
'allpagesprefix' => 'نمایش صفحه‌های دارای پیشوند:',
'allpagesbadtitle' => 'عنوان صفحهٔ داده‌شده نامعتبر است یا اینکه دارای پیشوندی بین‌زبانی یا بین‌ویکی‌ای است. ممکن است نویسه‌هایی بدارد که نمی‌توان از آنها در عنوان صفحه‌ها استفاده کرد.',
'allpages-bad-ns' => '{{SITENAME}} دارای فضای نام «$1» نیست.',
'allpages-hide-redirects' => 'پنهان‌کردن تغییرمسیرها',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'شما در حال مشاهدهٔ نسخه‌ای از این صفحه که در میانگیر قرار دارد هستید که ممکن است برای $1 قبل باشد.',
'cachedspecial-viewing-cached-ts' => 'شما در حال مشاهدهٔ نسخه‌ای از این صفحه که در میانگیر قرار دارد هستید، و این نسخه ممکن است کاملاً واقعی نباشد.',
'cachedspecial-refresh-now' => 'مشاهده آخرین.',

# Special:Categories
'categories' => 'رده‌ها',
'categoriespagetext' => '{{PLURAL:$1|ردهٔ|رده‌های}} زیر دارای صفحه‌ها یا پرونده‌هایی {{PLURAL:$1|است|هستند}}.
[[Special:UnusedCategories|رده‌های استفاده‌نشده]] در اینجا نمایش داده نشده‌اند.
همچنین [[Special:WantedCategories|رده‌های مورد نیاز]] را ببینید.',
'categoriesfrom' => 'نمایش رده‌ها با شروع از:',
'special-categories-sort-count' => 'مرتب کردن بر اساس تعداد',
'special-categories-sort-abc' => 'مرتب کردن الفبایی',

# Special:DeletedContributions
'deletedcontributions' => 'مشارکت‌های حذف‌شده',
'deletedcontributions-title' => 'مشارکت‌های حذف‌شده',
'sp-deletedcontributions-contribs' => 'مشارکت‌ها',

# Special:LinkSearch
'linksearch' => 'جستجوی پیوندهای بیرونی',
'linksearch-pat' => 'جستجوی الگو:',
'linksearch-ns' => 'فضای نام:',
'linksearch-ok' => 'جستجو',
'linksearch-text' => 'نشانه‌هایی مانند «‎*.wikipedia.org» را می‌توان استفاده کرد.
حداقل یک دامنه سطح بالا ، به عنوان مثال "*.org" نیاز دارد.<br />
پروتکل‌های پشتیبانی‌شده: <code>$1</code> (پیش‌فرض برای http:// در صورت مشخص نشدن پروتکل تنظیم شده‌است)',
'linksearch-line' => '$1 از $2 پیوند دارد',
'linksearch-error' => 'نشانه‌ها فقط در ابتدای نام میزبان اینترنتی می‌توانند استفاده شوند.',

# Special:ListUsers
'listusersfrom' => 'نمایش کاربران با شروع از:',
'listusers-submit' => 'نمایش',
'listusers-noresult' => 'هیچ کاربری یافت نشد.',
'listusers-blocked' => '(بسته شده)',

# Special:ActiveUsers
'activeusers' => 'فهرست کاربران فعال',
'activeusers-intro' => 'در زیر فهرستی از کاربرانی را می‌بینید که در $1 {{PLURAL:$1|روز|روز}} گذشته فعالیتی داشته‌اند.',
'activeusers-count' => '$1 {{PLURAL:$1|ویرایش|ویرایش}} در {{PLURAL:$3|روز|$3 روز}} اخیر',
'activeusers-from' => 'نمایش کاربران با آغاز از:',
'activeusers-hidebots' => 'نهفتن ربات‌ها',
'activeusers-hidesysops' => 'نهفتن مدیران',
'activeusers-noresult' => 'کاربری پیدا نشد.',

# Special:Log/newusers
'newuserlogpage' => 'سیاههٔ ایجاد کاربر',
'newuserlogpagetext' => 'این سیاهه‌ای از نام‌های کاربری تازه‌ساخته‌شده است.',

# Special:ListGroupRights
'listgrouprights' => 'اختیارات گروه‌های کاربری',
'listgrouprights-summary' => 'فهرست زیر شامل گروه‌های کاربری تعریف شده در این ویکی و اختیارات داده شده به آن‌ها است.
اطلاعات بیشتر در مورد هر یک از اختیارات را در [[{{MediaWiki:Listgrouprights-helppage}}]] بیابید.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">اختیارات داده شده</span>
* <span class="listgrouprights-revoked">اختیارات گرفته شده</span>',
'listgrouprights-group' => 'گروه',
'listgrouprights-rights' => 'دسترسی‌ها',
'listgrouprights-helppage' => 'Help:دسترسی‌های گروهی',
'listgrouprights-members' => '(فهرست اعضا)',
'listgrouprights-addgroup' => 'می‌تواند این {{PLURAL:$2|گروه|گروه‌ها}} را اضافه کند: $1',
'listgrouprights-removegroup' => 'می‌تواند این {{PLURAL:$2|گروه|گروه‌ها}} را حذف کند: $1',
'listgrouprights-addgroup-all' => 'می‌تواند تمام گروه‌ها را اضافه کند',
'listgrouprights-removegroup-all' => 'می‌تواند تمام گروه‌ها را حذف کند',
'listgrouprights-addgroup-self' => 'می‌تواند حساب خود را به این {{PLURAL:$2|گروه|گروه‌ها}} اضافه کند: $1',
'listgrouprights-removegroup-self' => 'می‌تواند حساب خود را از این {{PLURAL:$2|گروه|گروه‌ها}} حذف کند: $1',
'listgrouprights-addgroup-self-all' => 'می‌تواند حساب خود را به تمام گروه‌ها اضافه کند',
'listgrouprights-removegroup-self-all' => 'می‌تواند حساب خود را از تمام گروه‌ها حذف کند',

# E-mail user
'mailnologin' => 'نشانی‌ای از فرستنده موجود نیست',
'mailnologintext' => 'برای فرستادن رایانامه به کاربران دیگر باید [[Special:UserLogin|به سامانه وارد شوید]] و نشانی رایانامهٔ معتبری در [[Special:Preferences|ترجیحات]] خود داشته باشید.',
'emailuser' => 'فرستادن نامه به این کاربر',
'emailuser-title-target' => 'ایمیل این {{GENDER:$1| کاربر}}',
'emailuser-title-notarget' => 'رایانامه به کاربر',
'emailpage' => 'رایانامه به کاربر',
'emailpagetext' => 'شما می‌توانید از فرم زیر برای ارسال یک رایانامه به این {{GENDER:$1|کاربر}} استفاده کنید.
نشانی رایانامه‌ای که در [[Special:Preferences|ترجیحات کاربریتان]] وارد کرده‌اید در نشانی فرستنده (From) نامه خواهد آمد، تا گیرنده بتواند پاسخ دهد.',
'usermailererror' => 'رایانامه دچار خطا شد:',
'defemailsubject' => 'رایانامه {{SITENAME}} از طرف کاربر «$1»',
'usermaildisabled' => 'رایانامهٔ کاربر غیرقعال است',
'usermaildisabledtext' => 'شما در این ویکی نمی‌توانید به دیگر کاربران رایانامه بفرستید',
'noemailtitle' => 'نشانی رایانامه موجود نیست',
'noemailtext' => 'این کاربر نشانی رایانامهٔ معتبری مشخص نکرده است،',
'nowikiemailtitle' => 'اجازهٔ ارسال رایانامه داده نشده‌است',
'nowikiemailtext' => 'این کاربر انتخاب کرده که از دیگر کاربران رایانامه دریافت نکند.',
'emailnotarget' => 'نام کاربری ناموجود یا نامعتبر برای گیرنده.',
'emailtarget' => 'نام کاربری دریافت‌کننده را وارد کنید',
'emailusername' => 'نام کاربری:',
'emailusernamesubmit' => 'ارسال',
'email-legend' => 'ارسال یک نامه به کاربر دیگر {{SITENAME}}',
'emailfrom' => 'از:',
'emailto' => 'به:',
'emailsubject' => 'عنوان:',
'emailmessage' => 'پیغام:',
'emailsend' => 'بفرست',
'emailccme' => 'رونوشت پیغام را برایم بفرست.',
'emailccsubject' => 'رونوشت پیغام شما به $1: $2',
'emailsent' => 'رایانامه فرستاده شد',
'emailsenttext' => 'پیغام رایانامه شما فرستاده شد.',
'emailuserfooter' => 'این رایانامه با استفاده از ویژگی «فرستادن نامه به این کاربر» {{SITENAME}} توسط $1 به $2 فرستاده شد.',

# User Messenger
'usermessage-summary' => 'گذاشتن پیغام سامانه.',
'usermessage-editor' => 'پیغام رسان سامانه',

# Watchlist
'watchlist' => 'فهرست پی‌گیری‌های من',
'mywatchlist' => 'فهرست پی‌گیری‌ها',
'watchlistfor2' => 'برای $1 $2',
'nowatchlist' => 'در فهرست پی‌گیری‌های شما هیچ موردی نیست.',
'watchlistanontext' => 'برای مشاهده و ویرایش فهرست پی‌گیری‌های خود از $1 استفاده کنید.',
'watchnologin' => 'به سامانه وارد نشده‌اید',
'watchnologintext' => 'برای تغییر فهرست پی‌گیری‌هایتان باید [[Special:UserLogin|به سامانه وارد شوید]].',
'addwatch' => 'افزودن به فهرست پی‌گیری',
'addedwatchtext' => 'صفحهٔ «[[:$1]]» به [[Special:Watchlist|فهرست پی‌گیری‌های]] شما اضافه شد.
تغییرات این صفحه و صفحهٔ بحث متناظرش در آینده در اینجا فهرست خواهد شد.',
'removewatch' => 'حذف از فهرست پی‌گیری',
'removedwatchtext' => 'صفحهٔ «[[:$1]]» از [[Special:Watchlist|فهرست پی‌گیری‌های شما]] برداشته شد.',
'watch' => 'پی‌گیری',
'watchthispage' => 'پی‌گیری این صفحه',
'unwatch' => 'توقف پی‌گیری',
'unwatchthispage' => 'توقف پی‌گیری',
'notanarticle' => 'صفحه محتوایی نیست',
'notvisiblerev' => 'آخرین نسخه توسط کاربری دیگر حذف شده‌است',
'watchnochange' => 'هیچ یک از موارد در حال پی‌گیری شما در دورهٔ زمانی نمایش‌یافته ویرایش نشده است.',
'watchlist-details' => 'بدون احتساب صفحه‌های بحث، {{PLURAL:$1|$1 صفحه|$1 صفحه}} در فهرست پی‌گیری‌های شما قرار {{PLURAL:$1|دارد|دارند}}.',
'wlheader-enotif' => '*اطلاع‌رسانی از طریق رایانامه امکان‌پذیر است.',
'wlheader-showupdated' => "*صفحه‌هایی که پس از آخرین سرزدنتان به آنها تغییر کرده‌اند '''پررنگ''' نشان داده شده‌اند.",
'watchmethod-recent' => 'بررسی ویرایش‌های اخیر برای صفحه‌های مورد پی‌گیری',
'watchmethod-list' => 'بررسی صفحه‌های مورد پی‌گیری برای ویرایش‌های اخیر',
'watchlistcontains' => 'فهرست پی‌گیری‌های شما حاوی $1 {{PLURAL:$1|صفحه|صفحه}} است.',
'iteminvalidname' => 'مشکل با مورد «$1»، نام نامعتبر است...',
'wlnote' => 'در زیر {{PLURAL:$1|تغییری|$1 تغییری}} که در {{PLURAL:$2|ساعت|$2 ساعت}} گذشته انجام شده موجود است، تاریخ آخرین بازیابی: $3، $4',
'wlshowlast' => 'نمایش آخرین $1 ساعت $2 روز $3',
'watchlist-options' => 'گزینه‌های پیگیری',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'پی‌گیری...',
'unwatching' => 'توقف پی‌گیری...',
'watcherrortext' => 'ایرادی در هنگام عوض کردن تنظیمات فهرست پیگیرتان برای «$1» رخ داد.',

'enotif_mailer' => 'رایانامهٔ اطلاع‌رسانی {{SITENAME}}',
'enotif_reset' => 'علامت‌گذاری همهٔ صفحه‌ها به عنوان بازدید شده',
'enotif_newpagetext' => 'این یک صفحهٔ تازه‌است.',
'enotif_impersonal_salutation' => 'کاربر {{SITENAME}}',
'changed' => 'تغییر یافته',
'created' => 'ایجاد شده',
'enotif_subject' => 'صفحهٔ «$PAGETITLE» در {{SITENAME}} به دست $PAGEEDITOR $CHANGEDORCREATED است.',
'enotif_lastvisited' => 'برای دیدن همهٔ تغییرات از آخرین باری که سر زده‌اید $1 را ببینید.',
'enotif_lastdiff' => 'برای نمایش این تغییر $1 را ببینید.',
'enotif_anon_editor' => 'کاربر ناشناس $1',
'enotif_body' => '$WATCHINGUSERNAME گرامی،

صفحهٔ «$PAGETITLE» در {{SITENAME}} در $PAGEEDITDATE به‌دست $PAGEEDITOR $CHANGEDORCREATED است. برای دیدن نسخهٔ کنونی $PAGETITLE_URL را ببینید.

$NEWPAGE

توضیح ویراستار: $PAGESUMMARY $PAGEMINOREDIT

تماس با ویراستار:
نامه: $PAGEEDITOR_EMAIL
ویکی: $PAGEEDITOR_WIKI

تا هنگامی که به صفحه سر نزده‌اید، در صورت رخ‌دادنِ احتمالیِ تغییراتِ بیشتر، اعلانیه‌ای برای شما فرستاده نخواهد شد.
شما همچنین می‌توانید در صفحهٔ پی‌گیری‌های خود پرچم‌های مربوط به آگاهی‌رسانی را صفر کنید.

دوستدار شما، سامانهٔ آگاهی‌رسانی {{SITENAME}}

--
برای تغییر تنظیمات فهرست آگاهی‌رسانی رایانامه‌ای به {{canonicalurl:{{#special:EditWatchlist}}}} بروید.

برای تغییر تنظیمات فهرست پی‌گیری‌هایتان به {{canonicalurl:{{#special:EditWatchlist}}}} بروید.

برای حذف صفحه از فهرست پی‌گیری‌هایتان به $UNWATCHURL بروید.

بازخورد و کمک بیشتر:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'حذف صفحه',
'confirm' => 'تأیید',
'excontent' => 'محتوای صفحه این بود: «$1»',
'excontentauthor' => 'محتوای صفحه این بود: «$1» (و تنها مشارکت‌کننده «[[Special:Contributions/$2|$2]]» بود)',
'exbeforeblank' => 'محتوای صفحه قبل از خالی‌کردن این بود: «$1»',
'exblank' => 'صفحه خالی بود',
'delete-confirm' => 'حذف «$1»',
'delete-legend' => 'حذف',
'historywarning' => "'''هشدار!''' صفحه‌ای که قصد دارید حذف کنید تاریخچه‌ای با حدود $1 {{PLURAL:$1|نسخه|نسخه}} دارد:",
'confirmdeletetext' => 'شما در حال حذف کردن یک صفحه یا تصویر از پایگاه داده‌ها همراه با تمام تاریخچهٔ آن هستید.
لطفاً این عمل را تأیید کنید و اطمینان حاصل کنید که عواقب این کار را می‌دانید و این عمل را مطابق با [[{{MediaWiki:Policy-url}}|سیاست‌ها]] انجام می‌دهید.',
'actioncomplete' => 'عمل انجام شد',
'actionfailed' => 'عمل ناموفق بود',
'deletedtext' => '«$1» حذف شد.
برای سابقهٔ حذف‌های اخیر به $2 مراجعه کنید.',
'dellogpage' => 'سیاههٔ حذف',
'dellogpagetext' => 'فهرست زیر فهرستی از آخرین حذف‌هاست.
همهٔ زمان‌های نشان‌داده‌شده زمان خادم (وقت گرینویچ) است.',
'deletionlog' => 'سیاههٔ حذف',
'reverted' => 'به نسخهٔ قدیمی‌تر واگردانده شد',
'deletecomment' => 'دلیل:',
'deleteotherreason' => 'دلیل دیگر/اضافی:',
'deletereasonotherlist' => 'دلیل دیگر',
'deletereason-dropdown' => '*دلایل متداول حذف
** درخواست کاربر
** نقض حق تکثیر
** خرابکاری',
'delete-edit-reasonlist' => 'ویرایش دلایل حذف',
'delete-toobig' => 'این صفحه تاریخچهٔ ویرایشی بزرگی دارد، که شامل بیش از $1 {{PLURAL:$1|نسخه|نسخه}} است.
به منظور جلوگیری از اختلال ناخواسته در {{SITENAME}} حذف این گونه صفحه‌ها محدود شده‌است.',
'delete-warning-toobig' => 'این صفحه تاریخچهٔ ویرایشی بزرگی دارد، که شامل بیش از $1 {{PLURAL:$1|نسخه|نسخه}} است.
حذف آن ممکن است که عملکرد پایگاه دادهٔ {{SITENAME}} را مختل کند;
با احتیاط ادامه دهید.',

# Rollback
'rollback' => 'واگردانی ویرایش‌ها',
'rollback_short' => 'واگردانی',
'rollbacklink' => 'واگردانی',
'rollbacklinkcount' => 'واگردانی $1 ویرایش',
'rollbacklinkcount-morethan' => 'واگردانی بیش از $1 ویرایش',
'rollbackfailed' => 'واگردانی نشد',
'cantrollback' => 'نمی‌توان ویرایش را واگرداند؛
آخرین مشارکت‌کننده تنها مؤلف این مقاله است.',
'alreadyrolled' => 'واگردانی آخرین ویرایش [[:$1]] توسط [[User:$2|$2]] ([[User talk:$2|بحث]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ممکن نیست؛
پیش از این شخص دیگری مقاله را ویرایش یا واگردانی کرده‌است.

آخرین ویرایش توسط [[User:$3|$3]] ([[User talk:$3|بحث]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) انجام شده‌است.',
'editcomment' => "خلاصهٔ ویرایش این بود: «''$1''».",
'revertpage' => 'ویرایش [[Special:Contributions/$2|$2]] ([[User talk:$2|بحث]]) به آخرین تغییری که [[User:$1|$1]] انجام داده بود واگردانده شد',
'revertpage-nouser' => 'ویرایش‌های انجام‌شده توسط (نام کاربری حذف شده‌است) به آخرین ویرایش [[User:$1|$1]] واگردانی شد.',
'rollback-success' => 'ویرایش‌های $1 واگردانی شد؛
صفحه به آخرین ویرایش $2 برگردانده شد.',

# Edit tokens
'sessionfailure-title' => 'خطای نشست کاربری',
'sessionfailure' => 'به نظر می‌رسد مشکلی در مورد نشست کاربری شما وجود دارد؛
عمل درخواست شده در اقدامی پیشگیرانه در برابر ربوده‌شدن اطلاعات نشست کاربری، لغو شد.
لطفاً دکمهٔ «بازگشت» را در مرورگر خود بفشارید و صفحه‌ای که از آن به اینجا رسیده‌اید را دوباره فراخوانی کنید، سپس مجدداً سعی کنید.',

# Protect
'protectlogpage' => 'سیاههٔ محافظت',
'protectlogtext' => 'در زیر فهرستی از تغییرات سطح محافظت صفحه‌ها آمده‌است.
[[Special:ProtectedPages|فهرست صفحه‌های محافظت‌شده]] را برای دیدن فهرست محافظت‌های مؤثر صفحه‌ها ببینید.',
'protectedarticle' => '«[[$1]]» را محافظت کرد',
'modifiedarticleprotection' => 'وضعیت محافظت «[[$1]]» را تغییر داد',
'unprotectedarticle' => 'صفحهٔ «[[$1]]» را از محافظت بیرون آورد',
'movedarticleprotection' => 'تنظیمات محافظت را از «[[$2]]» به «[[$1]]» منتقل کرد',
'protect-title' => 'تغییر وضعیت محافظت «$1»',
'protect-title-notallowed' => 'مشاهده سطح حفاظت  " $1 "',
'prot_1movedto2' => '[[$1]] به [[$2]] منتقل شد',
'protect-badnamespace-title' => 'فضای نام بدون محافظت',
'protect-badnamespace-text' => 'صفحه‌های موجود در این فضای نام، نمی‌توانند محافظت شوند.',
'protect-legend' => 'تأیید محافظت',
'protectcomment' => 'دلیل:',
'protectexpiry' => 'زمان سرآمدن:',
'protect_expiry_invalid' => 'زمان سرآمدن نامعتبر است.',
'protect_expiry_old' => 'زمان سرآمدن در گذشته‌است.',
'protect-unchain-permissions' => 'باز کردن سایر گزینه‌های محافظت',
'protect-text' => "شما می‌توانید سطح محافظت صفحهٔ '''$1''' را ببینید و از اینجا آن را تغییر دهید.",
'protect-locked-blocked' => "شما در مدتی که دسترسی‌تان قطع است نمی‌توانید سطح محافظت صفحه‌ها را تغییر دهید.
تنظیمات فعلی صفحهٔ '''$1''' از این قرار است:",
'protect-locked-dblock' => "به دلیل قفل شدن پایگاه داده، امکان تغییر سطح محافظت صفحه وجود ندارد.
تنظیمات فعلی صفحهٔ '''$1''' به این قرار است:",
'protect-locked-access' => "حساب کاربری شما اجازهٔ تغییر سطح محافظت صفحه را ندارد.
تنظیمات فعلی صفحهٔ '''$1''' به این قرار است:",
'protect-cascadeon' => 'این صفحه  در حال حاضر محافظت شده‌است زیرا در {{PLURAL:$1|صفحهٔ|صفحه‌های}} زیر که گزینهٔ محافظت آبشاری {{PLURAL:$1|آن|آن‌ها}} فعال است، گنجانده شده است.
شما می‌توانید سطح محافظت این صفحه را تغییر بدهید اما این کار تاثیری بر محافظت آبشاری صفحه نخواهد گذاشت.',
'protect-default' => 'همهٔ کاربرها',
'protect-fallback' => 'فقط به کاربرهایی که دسترسی «$1» دارند، اجازه داده می‌شود',
'protect-level-autoconfirmed' => 'فقط به کاربرهای تائیدشده اجازه بده',
'protect-level-sysop' => 'فقط مدیران',
'protect-summary-cascade' => 'آبشاری',
'protect-expiring' => 'زمان سرآمدن $1 (UTC)',
'protect-expiring-local' => 'منقضی $1',
'protect-expiry-indefinite' => 'بی‌پایان',
'protect-cascade' => 'محافظت آبشاری - از همهٔ صفحه‌هایی که در این صفحه آمده‌اند نیز محافظت می‌شود.',
'protect-cantedit' => 'شما نمی‌تواند وضعیت محافظت این صفحه را تغییر دهید، چون اجازه ویرایش آن را ندارید.',
'protect-othertime' => 'زمانی دیگر:',
'protect-othertime-op' => 'زمانی دیگر',
'protect-existing-expiry' => 'زمان انقضای موجود: $2، $3',
'protect-otherreason' => 'دلیل دیگر/اضافی:',
'protect-otherreason-op' => 'دلیل دیگر',
'protect-dropdown' => '*دلایل متداول محافظت
** خرابکاری گسترده
** هرزنگاری گسترده
** جنگ ویرایشی غیر سازنده
** صفحهٔ پر بازدید',
'protect-edit-reasonlist' => 'ویرایش دلایل محافظت',
'protect-expiry-options' => '۱ ساعت:1 hour,۱ روز:1 day,۱ هفته:1 week,۲ هفته:2 weeks,۱ ماه:1 month,۳ ماه:3 months,۶ ماه:6 months,۱ سال:1 year,بی‌پایان:infinite',
'restriction-type' => 'دسترسی:',
'restriction-level' => 'سطح محدودیت:',
'minimum-size' => 'حداقل اندازه',
'maximum-size' => 'حداکثر اندازه:',
'pagesize' => '(بایت)',

# Restrictions (nouns)
'restriction-edit' => 'ویرایش',
'restriction-move' => 'انتقال',
'restriction-create' => 'ایجاد',
'restriction-upload' => 'بارگذاری',

# Restriction levels
'restriction-level-sysop' => 'کاملاً محافظت‌شده',
'restriction-level-autoconfirmed' => 'نیمه‌حفاظت‌شده',
'restriction-level-all' => 'هر سطحی',

# Undelete
'undelete' => 'احیای صفحهٔ حذف‌شده',
'undeletepage' => 'نمایش و احیای صفحه‌های حذف‌شده',
'undeletepagetitle' => "'''آن چه در ادامه می‌آید شامل نسخه‌های حذف شدهٔ [[:$1|$1]] است'''.",
'viewdeletedpage' => 'نمایش صفحه‌های حذف‌شده',
'undeletepagetext' => '{{PLURAL:$1|صفحهٔ زیر حدف شده|صفحه‌های زیر حذف شده‌اند}} ولی هنوز در بایگانی {{PLURAL:$1|هست|هستند}} و {{PLURAL:$1|می‌تواند احیا شود|می‌توانند احیا شوند}}.
این بایگانی ممکن است هر چند وقت تمیز شود.',
'undelete-fieldset-title' => 'احیای نسخه‌ها',
'undeleteextrahelp' => "برای احیای تمام تاریخچهٔ صفحه، همهٔ جعبه‌ها را خالی رها کرده و دکمهٔ '''''{{int:undeletebtn}}''''' را کلیک کنید.
برای انجام احیای انتخابی، جعبه‌های متناظر با نسخه‌های مورد نظر برای احیا را علامت زده و دکمهٔ '''''{{int:undeletebtn}}''''' را کلیک کنید.",
'undeleterevisions' => '$1 نسخه بایگانی {{PLURAL:$1|شده‌است|شده‌اند}}',
'undeletehistory' => 'اگر این صفحه را احیا کنید، همهٔ نسخه‌های آن در تاریخچه احیا خواهند شد.
اگر صفحهٔ جدیدی با نام یکسان از زمان حذف ایجاد شده باشد، نسخه‌های احیاشده در تاریخچهٔ قبلی خواهند آمد.',
'undeleterevdel' => 'احیای صفحه‌های در حالتی که باعث حذف شدن بخشی از آخرین نسخهٔ صفحه یا پرونده بشود مقدور نیست.
در این حالت شما باید جدیدترین نسخهٔ حذف شده را نیز احیا کنید.',
'undeletehistorynoadmin' => 'این مقاله حذف شده‌است.
دلیل حذف این مقاله به همراه مشخصات کاربرانی که قبل از حذف این صفحه را ویرایش کرده‌اند، در خلاصهٔ زیر آمده‌است.
متن واقعی این ویرایش‌های حذف شده فقط در دسترس مدیران است.',
'undelete-revision' => 'نسخهٔ حذف شدهٔ $1 (به تاریخ $4 ساعت $5) توسط $3:',
'undeleterevision-missing' => 'نسخه نامعتبر یا مفقود است.
ممکن است پیوندتان نادرست باشد یا اینکه نسخه از بایگانی حذف یا بازیابی شده باشد .',
'undelete-nodiff' => 'نسخهٔ قدیمی‌تری یافت نشد.',
'undeletebtn' => 'احیا',
'undeletelink' => 'نمایش/احیا',
'undeleteviewlink' => 'نمایش',
'undeletereset' => 'از نو',
'undeleteinvert' => 'وارونه کردن انتخاب',
'undeletecomment' => 'دلیل:',
'undeletedrevisions' => '$1 نسخه احیا {{PLURAL:$1|شد|شدند}}',
'undeletedrevisions-files' => '$1 نسخه و $2 پرونده احیا {{PLURAL:$1|شد|شدند}}.',
'undeletedfiles' => '$1 پرونده احیا {{PLURAL:$1|شد|شدند}}.',
'cannotundelete' => 'احیا ناموفق بود؛
ممکن است کس دیگری پیشتر این صفحه را احیا کرده باشد.',
'undeletedpage' => "'''$1 احیا شد'''

برای دیدن سیاههٔ حذف‌ها و احیاهای اخیر به  [[Special:Log/delete|سیاههٔ حذف]] رجوع کنید.",
'undelete-header' => 'برای دیدن صفحه‌های حذف‌شدهٔ اخیر [[Special:Log/delete|سیاههٔ حذف]] را ببینید.',
'undelete-search-title' => 'جستجوی صفحه‌های حذف شده',
'undelete-search-box' => 'جستجوی صفحه‌های حذف‌شده.',
'undelete-search-prefix' => 'نمایش صفحه‌ها با شروع از:',
'undelete-search-submit' => 'برو',
'undelete-no-results' => 'هیچ صفحهٔ منطبقی در بایگانی حذف‌شده‌ها یافت نشد.',
'undelete-filename-mismatch' => 'امکان احیای نسخهٔ $1 وجود ندارد: نام پرونده مطابقت نمی‌کند.',
'undelete-bad-store-key' => 'امکان احیای نسخهٔ $1 وجود ندارد: پرونده قبل از حذف از دست رفته بود.',
'undelete-cleanup-error' => 'خطا در حذف تاریخچهٔ استفاده نشدهٔ «$1».',
'undelete-missing-filearchive' => 'امکان احیای تاریخچهٔ شمارهٔ $1 وجود ندارد زیرا اطلاعات در پایگاه داده وجود ندارد.
ممکن است پیشتر احیا شده باشد.',
'undelete-error' => 'خطا صفحه غیرقابل حذف',
'undelete-error-short' => 'خطا در احیای پرونده: $1',
'undelete-error-long' => 'در زمان احیای پرونده خطا رخ داد:

$1',
'undelete-show-file-confirm' => 'آیا مطمئن هستید که می‌خواهید یک نسخهٔ حذف شده از پرونده "<nowiki>$1</nowiki>" مورخ $2 ساعت $3 را ببینید؟',
'undelete-show-file-submit' => 'بله',

# Namespace form on various pages
'namespace' => 'فضای نام:',
'invert' => 'انتخاب برعکس شود',
'tooltip-invert' => 'این جعبه را علامت بزنید تا تغییرات صفحه‌های داخل فضای نام انتخاب شده (و دیگر فضاهای نام علامت زده شده) پنهان شوند',
'namespace_association' => 'فضای نام مرتبط',
'tooltip-namespace_association' => 'این جعبه را علامت بزنید تا فضای نام بحث یا موضوع مرتبط با فضای نام انتخاب شده هم شامل شود',
'blanknamespace' => '(اصلی)',

# Contributions
'contributions' => 'مشارکت‌های کاربری',
'contributions-title' => 'مشارکت‌های کاربری $1',
'mycontris' => 'مشارکت‌ها',
'contribsub2' => 'برای $1 ($2)',
'nocontribs' => 'هیچ تغییری با این مشخصات یافت نشد.',
'uctop' => ' (بالا)',
'month' => 'در این ماه (و پیش از آن):',
'year' => 'در این سال (و پیش از آن):',

'sp-contributions-newbies' => 'فقط مشارکت‌های تازه‌کاران نمایش داده شود',
'sp-contributions-newbies-sub' => 'برای تازه‌کاران',
'sp-contributions-newbies-title' => 'مشارکت‌های کاربری برای حساب‌های تازه‌کار',
'sp-contributions-blocklog' => 'سیاههٔ بسته‌شدن‌ها',
'sp-contributions-deleted' => 'مشارکت‌های حذف‌شدهٔ کاربر',
'sp-contributions-uploads' => 'بارگذاری‌ها',
'sp-contributions-logs' => 'سیاهه‌ها',
'sp-contributions-talk' => 'بحث',
'sp-contributions-userrights' => 'مدیریت اختیارات کاربر',
'sp-contributions-blocked-notice' => 'این کاربر در حال حاضر بسته شده‌است.
آخرین سیاههٔ بسته شدن در زیر آمده‌است:',
'sp-contributions-blocked-notice-anon' => 'این نشانی آی‌پی در حال حاضر بسته است.
آخرین سیاههٔ بسته شدن در زیر آمده‌است:',
'sp-contributions-search' => 'جستجوی مشارکت‌ها',
'sp-contributions-username' => 'نشانی آی‌پی یا نام کاربری:',
'sp-contributions-toponly' => 'فقط ویرایش‌هایی که آخرین نسخه‌اند نمایش داده شود',
'sp-contributions-submit' => 'جستجو',

# What links here
'whatlinkshere' => 'پیوندها به این صفحه',
'whatlinkshere-title' => 'صفحه‌هایی که به «$1» پیوند دارند',
'whatlinkshere-page' => 'صفحه:',
'linkshere' => "صفحه‌های زیر به '''[[:$1]]''' پیوند دارند:",
'nolinkshere' => "هیچ صفحه‌ای به '''[[:$1]]''' پیوند ندارد.",
'nolinkshere-ns' => "هیچ صفحه‌ای از فضای نام انتخاب شده به '''[[:$1]]''' پیوند ندارد.",
'isredirect' => 'صفحهٔ تغییرمسیر',
'istemplate' => 'تراگنجانش‌ها',
'isimage' => 'پیوند به پرونده',
'whatlinkshere-prev' => '{{PLURAL:$1|قبلی|$1 مورد قبلی}}',
'whatlinkshere-next' => '{{PLURAL:$1|بعدی|$1 مورد بعدی}}',
'whatlinkshere-links' => '→ پیوندها',
'whatlinkshere-hideredirs' => '$1 تغییرمسیر',
'whatlinkshere-hidetrans' => '$1 تراگنجانش‌ها',
'whatlinkshere-hidelinks' => '$1 پیوند',
'whatlinkshere-hideimages' => '$1 پیوندهای پرونده',
'whatlinkshere-filters' => 'پالایه‌ها',

# Block/unblock
'autoblockid' => 'شناسه قطع دسترسی خودکار #$1',
'block' => 'بستن کاربر',
'unblock' => 'بازکردن کاربر',
'blockip' => 'بستن کاربر',
'blockip-title' => 'بستن کاربر',
'blockip-legend' => 'بستن کاربر',
'blockiptext' => 'از فرم زیر برای بستن دسترسی ویرایش یک نشانی آی‌پی یا نام کاربری مشخص استفاده کنید.
این کار فقط فقط باید برای جلوگیری از خرابکاری و بر اساس [[{{MediaWiki:Policy-url}}|سیاست قطع دسترسی]] انجام شود.
دلیل مشخص این کار را در زیر ذکر کنید (مثلاً با ذکر صفحه‌های به‌خصوصی که مورد خرابکاری واقع شده‌اند).',
'ipadressorusername' => 'نشانی آی‌پی یا نام کاربری:',
'ipbexpiry' => 'زمان سرآمدن:',
'ipbreason' => 'دلیل:',
'ipbreasonotherlist' => 'دلیل دیگر',
'ipbreason-dropdown' => '*دلایل متداول قطع دسترسی
**وارد کردن اطلاعات نادرست
**پاک کردن اطلاعات مفید از صفحه‌ها
**هرزنگاری از طریق درج مکرر پیوند به وب‌گاه‌ها
**درج چرندیات یا نوشته‌های بی‌معنا در صفحه‌ها
**تهدید یا ارعاب دیگر کاربران
**سوء استفاده از چند حساب کاربری
**نام کاربری نامناسب',
'ipb-hardblock' => 'جلوگیری از ویرایش کردن کاربران ثبت نام کرده از طریق این نشانی آی‌پی',
'ipbcreateaccount' => 'جلوگیری از ایجاد حساب',
'ipbemailban' => 'جلوگیری از ارسال رایانامه',
'ipbenableautoblock' => 'بستن  خودکار آخرین نشانی آی‌پی استفاده شده توسط کاربر و نشانی‌های دیگری که از آن‌ها برای ویرایش تلاش می‌کند',
'ipbsubmit' => 'این کاربر بسته شود',
'ipbother' => 'زمانی دیگر',
'ipboptions' => '۲ ساعت:2 hours,۱ روز:1 day,۳ روز:3 days,۱ هفته:1 week,۲ هفته:2 weeks,۱ ماه:1 month,۳ ماه:3 months,۶ ماه:6 months,۱ سال:1 year,بی‌پایان:infinite',
'ipbotheroption' => 'دیگر',
'ipbotherreason' => 'دلیل دیگر/اضافی:',
'ipbhidename' => 'نهفتن نام کاربری از ویرایش‌ها و فهرست‌ها',
'ipbwatchuser' => 'پیگیری صفحهٔ کاربری و بحث این کاربر',
'ipb-disableusertalk' => 'جلوگیری از ویرایشی صفحهً بحث توسط خود کاربر در زمانی که بسته است',
'ipb-change-block' => 'بستن دوبارهٔ کاربر با این تنظیم‌ها',
'ipb-confirm' => 'تأیید بستن',
'badipaddress' => 'نشانی آی‌پی غیر مجاز',
'blockipsuccesssub' => 'بستن با موفقیت انجام شد',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] بسته شد.<br />
برای بررسی بسته‌شده‌ها [[Special:BlockList|فهرست بسته‌شده‌ها]] را ببینید.',
'ipb-blockingself' => 'شما در حال بستن خودتان هستید!  آیا مطمئن هستید که می‌خواهید چنین کاری انجام دهید؟',
'ipb-confirmhideuser' => 'شما در حال بستن یک کاربر هستید که «پنهان‌سازی کاربر» برایش فعال شد‌ه‌است. این کار نام کاربر را از همهٔ فهرست‌ها و سیاهه‌ها مخفی می‌کند. آیا مطمئن هستید که می‌خواهید آن را انجام دهید؟',
'ipb-edit-dropdown' => 'ویرایش دلایل قطع‌دسترسی',
'ipb-unblock-addr' => 'باز کردن $1',
'ipb-unblock' => 'باز کردن نام کاربری یا نشانی آی‌پی',
'ipb-blocklist' => 'دیدن قطع دسترسی‌های موجود',
'ipb-blocklist-contribs' => 'مشارکت‌های $1',
'unblockip' => 'باز کردن کاربر',
'unblockiptext' => 'برای بازگرداندن دسترسی نوشتن به یک نشانی آی‌پی یا نام کاربری بسته‌شده از فرم زیر استفاده کنید.',
'ipusubmit' => 'باز کردن دسترسی',
'unblocked' => 'دسترسی [[User:$1|$1]] دوباره برقرار شد',
'unblocked-range' => '$1 باز شد',
'unblocked-id' => 'قطع دسترسی شماره $1 خاتمه یافت',
'blocklist' => 'کاربران بسته‌شده',
'ipblocklist' => 'کاربران بسته‌شده',
'ipblocklist-legend' => 'جستجوی کاربر بسته شده',
'blocklist-userblocks' => 'پنهان‌کردن بسته‌شدن‌های حساب',
'blocklist-tempblocks' => 'پنهان‌کردن بستن‌های موقت',
'blocklist-addressblocks' => 'پنهان‌کردن تک آی‌پی‌های بسته شده',
'blocklist-rangeblocks' => 'پنهان کردنی قطع دسترسی بازه‌ها',
'blocklist-timestamp' => 'برچسب زمان',
'blocklist-target' => 'هدف',
'blocklist-expiry' => 'زمان سرآمدن',
'blocklist-by' => 'مدیر قطع دسترسی کننده',
'blocklist-params' => 'پارامترهای بستن',
'blocklist-reason' => 'دلیل',
'ipblocklist-submit' => 'جستجو',
'ipblocklist-localblock' => 'قطع دسترسی محلی',
'ipblocklist-otherblocks' => 'سایر {{PLURAL:$1|بستن‌ها|بستن‌ها}}',
'infiniteblock' => 'بی‌پایان',
'expiringblock' => 'در $1 ساعت $2 به پایان می‌رسد',
'anononlyblock' => 'فقط کاربران گمنام',
'noautoblockblock' => 'بستن خودکار غیرفعال است',
'createaccountblock' => 'امکان ایجاد حساب مسدود است',
'emailblock' => 'رایانامه مسدود شد',
'blocklist-nousertalk' => 'نمی تواند صفحهٔ بحث خود را ویرایش کند',
'ipblocklist-empty' => 'فهرست بسته‌شدن‌ها خالی‌است.',
'ipblocklist-no-results' => 'دسترسی حساب کاربری یا نشانی آی‌پی مورد نظر قطع نیست.',
'blocklink' => 'بستن',
'unblocklink' => 'باز شود',
'change-blocklink' => 'تغییر قطع دسترسی',
'contribslink' => 'مشارکت‌ها',
'emaillink' => 'ارسال رایانامه',
'autoblocker' => 'به طور خودکار بسته شد چون آی‌پی شما به تازگی توسط کاربر «[[User:$1|$1]]» استفاده شده‌است.
دلیل قطع دسترسی $1 چنین است: «$2»',
'blocklogpage' => 'سیاههٔ بسته‌شدن‌ها',
'blocklog-showlog' => 'دسترسی این کاربر در گذشته بسته شده‌است.
سیاههٔ قطع دسترسی در زیر نمایش یافته است:',
'blocklog-showsuppresslog' => 'دسترسی این کاربر قبلاً بسته شده و این کاربر پنهان شده‌است.
سیاههٔ قطع دسترسی در زیر نمایش یافته است:',
'blocklogentry' => '«[[$1]]» را تا $2 بست $3',
'reblock-logentry' => 'تنظیمات قطع دسترسی [[$1]] را تغییر داد به پایان قطع دسترسی در $2 $3',
'blocklogtext' => 'این سیاهه‌ای از بستن و باز کردن کاربرها است.
نشانی‌های آی‌پی که به طور خودکار بسته شده‌اند فهرست نشده‌اند.
برای فهرست محرومیت‌ها و بسته‌شدن‌های حال حاضر به [[Special:BlockList|فهرست بسته‌شده‌ها]] مراجعه کنید.',
'unblocklogentry' => '$1 را باز کرد',
'block-log-flags-anononly' => 'فقط کاربران گمنام',
'block-log-flags-nocreate' => 'قابلیت ایجاد حساب غیرفعال شد',
'block-log-flags-noautoblock' => 'قطع دسترسی خودکار غیرفعال شد',
'block-log-flags-noemail' => 'رایانامه مسدود شد',
'block-log-flags-nousertalk' => 'صفحهٔ بحث خود را نمی‌تواند ویرایش کند',
'block-log-flags-angry-autoblock' => 'قطع دسترسی خودکار پیشرفته فعال شد',
'block-log-flags-hiddenname' => 'نام کاربری پنهان',
'range_block_disabled' => 'بستن یک بازه توسط مدیران غیر فعال است.',
'ipb_expiry_invalid' => 'زمان سرآمدن نامعتبر.',
'ipb_expiry_temp' => 'قطع دسترسی کاربرهای پهنان باید همیشگی باشد.',
'ipb_hide_invalid' => 'ناتوان از فرونشاندن این حساب؛ شاید ویرایش‌های زیادی دارد.',
'ipb_already_blocked' => '«$1» همین الان هم بسته‌است',
'ipb-needreblock' => 'دسترسی $1 از قبل بسته است. آیا می‌خواهید تنظیمات آن را تغییر دهید؟',
'ipb-otherblocks-header' => 'سایر {{PLURAL:$1|قطع دسترسی‌ها|قطع دسترسی‌ها}}',
'unblock-hideuser' => '‫به این خاطر که حساب کاربری این کاربر مخفی شده‌است شما نمی‌توانید آن را باز کنید.‬',
'ipb_cant_unblock' => 'خطا: شناسه بسته‌شدن $1 یافت نشد. ممکن است پیشتر باز شده باشد.',
'ipb_blocked_as_range' => 'خطا: نشانی آی‌پی $1 به شکل مستقیم بسته نشده‌است و نمی‌تواند باز شود.
این نشانی به همراه بازه $2 بسته شده که قابل باز شدن است.',
'ip_range_invalid' => 'بازهٔ آی‌پی نامعتبر.',
'ip_range_toolarge' => 'قطع دسترسی بازه‌های بزرگتر از /$1 مجاز نیست.',
'blockme' => 'دسترسی مرا قطع کن',
'proxyblocker' => 'مسدود کننده پروکسی',
'proxyblocker-disabled' => 'این عملکرد غیرفعال شده‌است.',
'proxyblockreason' => 'نشانی آی‌پی شما بسته شده است چون متعلق به یک پروکسی باز است.
لطفاً با ارائه دهندهً خدمات اینترنت خود یا پشتیبانی فنی تماس بگیرید و آنها را از این مشکل امنیتی جدی آگاه کنید.',
'proxyblocksuccess' => 'انجام شد.',
'sorbsreason' => 'نشانی آی‌پی شما توسط DNSBL مورد استفاده {{SITENAME}} به عنوان یک پروکسی باز گزارش شده‌است.',
'sorbs_create_account_reason' => 'نشانی آی‌پی شما توسط DNSBL مورد استفاده {{SITENAME}} به عنوان یک پروکسی باز گزارش شده‌است.
شما اجازهٔ ساختن حساب کاربری ندارید.',
'cant-block-while-blocked' => 'در مدتی که دسترسی شما بسته است نمی‌توانید دسترسی کاربران دیگر را قطع کنید.',
'cant-see-hidden-user' => 'کاربری که می‌خواهید ببندید قبلاً بسته شده و پنهان گردیده است. چون شما دسترسی پنهان کردن کاربران را ندارید، نمی‌توانید قطع دسترسی کاربر را ببینید یا ویرایش کنید.',
'ipbblocked' => 'شما نمی‌توانید دسترسی دیگر کاربران را ببندید یا باز کنید زیرا دسترسی خودتان بسته است.',
'ipbnounblockself' => 'شما مجاز به باز کردن دسترسی خود نیستید.',

# Developer tools
'lockdb' => 'قفل کردن پایگاه داده',
'unlockdb' => 'از قفل در آوردن پایگاه داده',
'lockdbtext' => 'قفل کردن پایگاه داده امکان ویرایش صفحه‌ها، تغییر تنظیمات، ویرایش پی‌گیری‌ها، و سایر تغییراتی را که نیازمند تغییری در پایگاه داده است، از همهٔ کاربران سلب می‌کند.
لطفاً تأیید کنید که همین کار را می‌خواهید انجام دهید، و در اولین فرصت پایگاه داده را از حالت قفل شده خارج خواهید کرد.',
'unlockdbtext' => 'از قفل در آوردن پایگاه داده به تمامی کاربران اجازه می‌دهد که توانایی ویرایش صفحه‌ها، تغییر تنظیمات، تغییر پی‌گیری‌ها و هر تغییر دیگری که نیازمند تغییر در پایگاه داده باشد را دوباره به دست بیاورند.
لطفاً تأیید کنید که همین کار را می‌خواهید انجام دهید.',
'lockconfirm' => 'بله، من جداً می‌خواهم پایگاه داده را قفل کنم.',
'unlockconfirm' => 'بله، من جداً می‌خواهم پایگاه داده را از قفل در آورم.',
'lockbtn' => 'قفل کردن پایگاه داده',
'unlockbtn' => 'از قفل درآوردن پایگاه داده',
'locknoconfirm' => 'شما در جعبهٔ تأیید تیک نزدید',
'lockdbsuccesssub' => 'قفل کردن پایگاه داده با موفقیت انجام شد',
'unlockdbsuccesssub' => 'قفل پایگاه داده برداشته شد',
'lockdbsuccesstext' => 'پایگاه داده قفل شد.
<br />فراموش نکنید که پس از اتمام نگهداری قفل را بردارید.',
'unlockdbsuccesstext' => 'پایگاه داده از قفل در آمد.',
'lockfilenotwritable' => 'قفل پایگاه داده نوشتنی نیست. برای این که بتوانید پایگاه داده را قفل یا باز کنید، باید این پرونده نوشتنی باشد.',
'databasenotlocked' => 'پایگاه داده قفل نیست.',
'lockedbyandtime' => '(به وسیلهٔ $1 در $2 ساعت $3)',

# Move page
'move-page' => 'انتقال $1',
'move-page-legend' => 'انتقال صفحه',
'movepagetext' => "با استفاده از فرم زیر نام صفحه تغییر خواهد کرد، و تمام تاریخچه‌اش به نام جدید منتقل خواهد شد.
عنوان قدیمی تبدیل به یک صفحهٔ تغییرمسیر به عنوان جدید خواهد شد.
شما می‌توانید تغییرمسیرهایی که به عنوان اصلی اشاره دارند را به صورت خودکار به‌روزرسانی کنید.
پیوندهای که به عنوان صفحهٔ قدیمی وجود دارند، تغییر نخواهند کرد؛ حتماً تغییرمسیرهای [[Special:DoubleRedirects|دوتایی]] یا [[Special:BrokenRedirects|خراب]] را بررسی کنید.
'''شما''' مسئول اطمینان از این هستید که پیوندها هنوز به همان‌جایی که قرار است بروند.

توجه کنید که اگر از قبل صفحه‌ای در عنوان جدید وجود داشته باشد صفحه منتقل '''نخواهد شد'''،
مگر این که صفحه خالی یا تغییرمسیر باشد و تاریخچهٔ ویرایشی نداشته باشد.
این یعنی اگر اشتباه کردید می‌توانید صفحه را به همان جایی که از آن منتقل شده بود برگردانید، و این که نمی‌توانید روی صفحه‌ها موجود بنویسید.

'''هشدار!'''
انتقال صفحه‌ها به نام جدید ممکن است تغییر اساسی و غیرمنتظره‌ای برای صفحه‌های محبوب باشد؛
لطفاً مطمئن شوید که قبل از انتقال دادن صفحه، عواقب این کار را درک می‌کنید.",
'movepagetext-noredirectfixer' => "استفاده از فرم زیر سبب تغییر نام یک صفحه و انتقال تمام تاریخچهٔ آن به نام جدید می‌شود.
عنوان پیشین تغییرمسیری به عنوان جدید خواهد شد.
به خاطر داشته باشید که [[Special:DoubleRedirects|تغییرمسیرهای دوتایی]] یا [[Special:BrokenRedirects|تغییرمسیرهای خراب]] را بررسی کنید.
شما مسئولید که مطمئن شوید پیوندها به جایی اشاره می‌کنند که قرار است بروند.

توجه کنید که اگر صفحه‌ای تحت عنوان جدید از قبل موجود باشد، انتقال انجام '''نخواهد شد'''، مگر اینکه صفحه خالی و یا تغییرمسیر باشد و تاریخچهٔ ویرایشی دیگری نداشته باشد.
این یعنی اگر صفحه را به نامی اشتباه منتقل کردید می‌توانید این تغییر را واگردانی کنید، اما نمی‌توانید به صفحه‌ای که از قبل موجود است انتقال دهید.

'''هشدار!'''
انتقال صفحه‌های پربیننده ممکن است عملی غیرمنتظره باشد؛
لطفاً پیش از انتقال مطمئن شوید از نتیجهٔ کار آگاهید.",
'movepagetalktext' => "صفحهٔ بحث مربوط، اگر وجود داشته باشد، بطور خودکار همراه با مقالهٔ اصلی منتقل خواهد شد '''مگر اینکه''' :
* در حال انتقال صفحه از این فضای نام به فضای نام دیگری باشید،
* یک صفحهٔ بحث غیرخالی تحت این نام جدید وجود داشته باشد، یا
* جعبهٔ زیر را تیک نزده باشید.

در این حالات، باید صفحه را بطور دستی انتقال داده و یا محتویات دو صفحه را با ویرایش ادغام کنید.",
'movearticle' => 'انتقال صفحه:',
'moveuserpage-warning' => "'''هشدار:''' شما در حال انتقال دادن یک صفحهٔ کاربر هستید. توجه داشته باشید که تنها صفحه منتقل می‌شود و نام کاربر تغییر '''نمی‌یابد'''.",
'movenologin' => 'به سامانه وارد نشده‌اید',
'movenologintext' => 'برای انتقال صفحه‌ها باید کاربر ثبت‌شده بوده و [[Special:UserLogin|به سامانه وارد شوید]].',
'movenotallowed' => 'شما اجازهٔ انتقال دادن صفحه‌ها را ندارید.',
'movenotallowedfile' => 'شما اجازهٔ انتقال پرونده‌ها را ندارید.',
'cant-move-user-page' => 'شما اجازه ندارید صفحه‌های کاربری سرشاخه را انتقال دهید.',
'cant-move-to-user-page' => 'شما اجازه ندارید که یک صفحه را به یک صفحهٔ کاربر انتقال دهید (به استثنای زیر صفحه‌های کاربری).',
'newtitle' => 'به عنوان جدید',
'move-watch' => 'پی‌گیری این صفحه',
'movepagebtn' => 'صفحه منتقل شود',
'pagemovedsub' => 'انتقال با موفقیت انجام شد',
'movepage-moved' => "'''«$1» به «$2» منتقل شد'''",
'movepage-moved-redirect' => 'یک تغییرمسیر ایجاد شد.',
'movepage-moved-noredirect' => 'از ایجاد تغییرمسیر ممانعت شد.',
'articleexists' => 'صفحه‌ای با این نام از قبل وجود دارد، یا نامی که انتخاب کرده‌اید معتبر نیست.
لطفاً نام دیگری انتخاب کنید.',
'cantmove-titleprotected' => 'شما نمی‌توانید صفحه را به این نشانی انتقال دهید، چرا که عنوان جدید در برابر ایجاد محافظت شده‌است',
'talkexists' => "'''خود صفحه با موفقیت منتقل شد، ولی صفحهٔ بحث منتقل نشد چون صفحهٔ بحثی از قبل در عنوان جدید وجود دارد.
لطفاً آن‌ها را دستی ادغام کنید.'''",
'movedto' => 'منتقل شد به',
'movetalk' => 'صفحهٔ بحث هم منتقل شود',
'move-subpages' => 'انتقال زیرصفحه‌ها (تا $1 صفحه)',
'move-talk-subpages' => 'انتقال زیرصفحه‌های صفحهٔ بحث (تا $1 صفحه)',
'movepage-page-exists' => 'صفحهٔ $1 از قبل وجود دارد و نمی‌تواند به طور خودکار جایگزین شود.',
'movepage-page-moved' => 'صفحهٔ $1 به $2 انتقال یافت.',
'movepage-page-unmoved' => 'صفحهٔ $1 را نمی‌توان به $2 انتقال داد.',
'movepage-max-pages' => 'حداکثر تعداد صفحه‌های ممکن ($1 {{PLURAL:$1|صفحه|صفحه}}) که می‌توان انتقال داد منتقل شدند و صفحه‌های دیگر را نمی‌توان به طور خودکار منتقل کرد.',
'movelogpage' => 'سیاههٔ انتقال',
'movelogpagetext' => 'در زیر فهرستی از انتقال صفحه‌ها آمده است.',
'movesubpage' => '{{PLURAL:$1|زیرصفحه|زیرصفحه‌ها}}',
'movesubpagetext' => 'این صفحه $1 زیرصفحه دارد که در زیر نمایش {{PLURAL:$1|یافته‌است|یافته‌اند}}.',
'movenosubpage' => 'این صفحه هیچ زیرصفحه‌ای ندارد.',
'movereason' => 'دلیل:',
'revertmove' => 'واگردانی',
'delete_and_move' => 'حذف و انتقال',
'delete_and_move_text' => '== نیاز به حذف ==

مقالهٔ مقصد «[[:$1]]» وجود دارد. آیا می‌خواهید آن را حذف کنید تا انتقال ممکن شود؟',
'delete_and_move_confirm' => 'بله، صفحه حذف شود',
'delete_and_move_reason' => 'حذف برای ممکن‌شدن انتقال  «[[$1]]»',
'selfmove' => 'عنوان‌های صفحهٔ مبدأ و مقصد یکی است؛
انتقال صفحه به خودش ممکن نیست.',
'immobile-source-namespace' => 'امکان انتقال صفحه‌ها در فضای نام «$1» وجود ندارد',
'immobile-target-namespace' => 'امکان انتقال صفحه‌ها به فضای نام «$1» وجود ندارد',
'immobile-target-namespace-iw' => 'پیوند میان‌ویکی هدفی مجاز برای انتقال صفحه نیست.',
'immobile-source-page' => 'این صفحه قابل انتقال نیست.',
'immobile-target-page' => 'امکان انتقال به این عنوان مقصد وجود ندارد.',
'imagenocrossnamespace' => 'امکان انتقال تصویر به فضای نام غیر پرونده وجود ندارد',
'nonfile-cannot-move-to-file' => 'امکان انتقال محتوای غیر پرونده به فضای نام پرونده وجود ندارد',
'imagetypemismatch' => 'پسوند پرونده جدید با نوع آن سازگار نیست',
'imageinvalidfilename' => 'نام پروندهٔ هدف غیرمجاز است',
'fix-double-redirects' => 'به روز کردن تمامی تغییرمسیرهایی که به مقالهٔ اصلی اشاره می‌کنند',
'move-leave-redirect' => 'بر جا گذاشتن یک تغییرمسیر',
'protectedpagemovewarning' => "'''هشدار:''' این صفحه قفل شده‌است به طوری که تنها کاربران با دسترسی مدیریت می‌توانند آن را انتقال دهند.
آخرین موارد سیاهه در زیر آمده است:",
'semiprotectedpagemovewarning' => "'''تذکر:''' این صفحه قفل شده‌است به طوری که تنها کاربران ثبت نام کرده می‌توانند آن را انتقال دهند.
آخرین موارد سیاهه در زیر آمده است:",
'move-over-sharedrepo' => '== پرونده موجود است ==
[[:$1]] در یک مخزن مشترک وجود دارد. انتقال یک پرونده به این نام باعث باطل شدن پرونده مشترک خواهد شد.',
'file-exists-sharedrepo' => 'نام پرونده انتخاب شده از قبل در یک مخزن مشترک استفاده شده‌است.
لطفاً یک نام دیگر برگزینید.',

# Export
'export' => 'برون‌بری صفحه‌ها',
'exporttext' => 'شما می‌توانید متن و تاریخچهٔ ویرایش یک صفحهٔ مشخص یا مجموعه‌ای از صفحه‌ها را به شکل پوشیده در اکس‌ام‌ال برون‌بری کنید.
این اطلاعات را می‌توان در ویکی دیگری که نرم‌افزار «مدیاویکی» را اجرا می‌کند از طریق [[Special:Import|صفحهٔ درون‌ریزی]] وارد کرد.

برای برون‌بری صفحه‌ها، عنوان آن‌ها را در جعبهٔ زیر وارد کنید (در هر سطر فقط یک عنوان) و مشخص کنید که آیا نسخهٔ اخیر صفحه را به همراه نسخه‌های قدیمی‌تر و تاریخچهٔ صفحه می‌خواهید، یا تنها نسخهٔ اخیر صفحه و اطلاعات آخرین ویرایش را می‌خواهید.

در حالت دوم، شما می‌توانید از یک پیوند استفاده کنید، مثلاً [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] برای صفحهٔ «[[{{MediaWiki:Mainpage}}]]».',
'exportall' => 'برون‌بری همهٔ صفحه‌ها',
'exportcuronly' => 'فقط نسخهٔ فعلی شامل شود، نه کل تاریخچه',
'exportnohistory' => "----
'''توجه:''' امکان برون‌بری تاریخچهٔ کامل صفحه‌ها از طریق این صفحه به دلایل اجرایی از کار انداخته شده‌است.",
'exportlistauthors' => 'محتوی لیست کامل مشارکت‌کنندگان هر صفحه',
'export-submit' => 'برون‌بری',
'export-addcattext' => 'افزودن صفحه‌ها از رده:',
'export-addcat' => 'افزودن',
'export-addnstext' => 'افزودن صفحه‌ها از فضای نام:',
'export-addns' => 'افزودن',
'export-download' => 'ذخیره به صورت پرونده',
'export-templates' => 'شامل شدن الگوها',
'export-pagelinks' => 'شامل شدن صفحه‌های پیوند شده تا این عمق:',

# Namespace 8 related
'allmessages' => 'پیغام‌های سامانه',
'allmessagesname' => 'نام',
'allmessagesdefault' => 'متن پیش‌فرض پیغام',
'allmessagescurrent' => 'متن کنونی پیغام',
'allmessagestext' => 'این فهرستی از پیغام‌های سامانه‌ای موجود در فضای نام مدیاویکی است.
چنانچه مایل به مشارکت در محلی‌سازی مدیاویکی هستید لطفاً [//www.mediawiki.org/wiki/Localisation محلی‌سازی مدیاویکی] و [//translatewiki.net translatewiki.net] را ببینید.',
'allmessagesnotsupportedDB' => "نمی‌توان از '''{{ns:special}}:همهٔ پیغام‌ها''' استفاده کرد چود '''&lrm;\$wgUseDatabaseMessages''' خاموش شده است.",
'allmessages-filter-legend' => 'پالایه',
'allmessages-filter' => 'پالودن بر اساس وضعیت شخصی‌سازی:',
'allmessages-filter-unmodified' => 'تغییر نیافته',
'allmessages-filter-all' => 'همه',
'allmessages-filter-modified' => 'تغییر یافته',
'allmessages-prefix' => 'پالودن بر اساس پسوند:',
'allmessages-language' => 'زبان:',
'allmessages-filter-submit' => 'برو',

# Thumbnails
'thumbnail-more' => 'بزرگ‌کردن',
'filemissing' => 'پرونده وجود ندارد',
'thumbnail_error' => 'خطا در ایجاد بندانگشتی: $1',
'djvu_page_error' => 'صفحهٔ DjVu خارج از حدود مجاز',
'djvu_no_xml' => 'امکان پیدا کردن پروندهٔ XML برای استفادهٔ DjVu وجود نداشت.',
'thumbnail-temp-create' => 'نمی‌توان پروندهٔ بندانگشتی موقت را ساخت',
'thumbnail-dest-create' => 'نمی‌توان تصویر بندانگشتی را در مقصد ذخیره کرد',
'thumbnail_invalid_params' => 'پارامترهای غیرمجاز در تصویر بندانگشتی (thumbnail)',
'thumbnail_dest_directory' => 'اشکال در ایجاد پوشهٔ مقصد',
'thumbnail_image-type' => 'تصویر از نوع پشتیبانی نشده',
'thumbnail_gd-library' => 'تنظیمات ناقص کتابخانهٔ GD: عملکرد $1 وجود ندارد',
'thumbnail_image-missing' => 'پرونده به نظر گم شده‌است: $1',

# Special:Import
'import' => 'درون‌ریزی صفحه‌ها',
'importinterwiki' => 'درون‌ریزی تراویکی',
'import-interwiki-text' => 'یک ویکی و یک نام صفحه را انتخاب کنید تا اطلاعات از آن درون‌ریزی شود.
تاریخ نسخه‌ها و نام ویرایش‌کنندگان ثابت خواهد ماند.
اطلاعات مربوط به درون‌ریزی صفحه‌ها در [[Special:Log/import|سیاههٔ درون‌ریزی‌ها]] درج خواهد شد.',
'import-interwiki-source' => 'ویکی/صفحهٔ منبع:',
'import-interwiki-history' => 'تمام نسخه‌های تاریخچهٔ این صفحه انتقال داده شود',
'import-interwiki-templates' => 'تمام الگوها را شامل شود',
'import-interwiki-submit' => 'درون‌ریزی شود',
'import-interwiki-namespace' => 'فضای نام مقصد:',
'import-interwiki-rootpage' => 'مقصد صفحه ٔ مبنا (اختیاری):',
'import-upload-filename' => 'نام پرونده:',
'import-comment' => 'توضیح:',
'importtext' => 'لطفاً پرونده را از ویکی منبع با کمک [[Special:Export|ابزار برون‌بری]] دریافت کنید.
سپس آن را روی دستگاه‌تان ذخیره کنید و اینجا بارگذاری نمایید.',
'importstart' => 'در حال درون‌ریزی صفحه‌ها...',
'import-revision-count' => '$1 {{PLURAL:$1|ویرایش|ویرایش}}',
'importnopages' => 'صفحه‌ای برای درون‌ریزی نیست.',
'imported-log-entries' => '$1 {{PLURAL:$1|مورد سیاهه|مورد سیاهه}} درون ریزی شد.',
'importfailed' => 'درون‌ریزی صفحه‌ها شکست خورد: <nowiki>$1</nowiki>',
'importunknownsource' => 'نوع مأخذ درون‌ریزی معلوم نیست',
'importcantopen' => 'پروندهٔ درون‌ریزی صفحه‌ها باز نشد',
'importbadinterwiki' => 'پیوند میان‌ویکی نادرست',
'importnotext' => 'صفحه خالی یا بدون متن',
'importsuccess' => 'درون‌ریزی با موفقیت انجام شد!',
'importhistoryconflict' => 'نسخه‌های ناسازگار از تاریخچهٔ این صفحه وجود دارد (احتمالاً این صفحه پیش از این درون‌ریزی شده است)',
'importnosources' => 'هیچ منبعی برای درون‌ریزی اطلاعات از ویکی دیگر تعریف نشده‌است.',
'importnofile' => 'هیچ پرونده‌ای برای درون‌ریزی بارگذاری نشده‌است.',
'importuploaderrorsize' => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد.
اندازهٔ پرونده بیشتر از حداکثر اندازهٔ مجاز است.',
'importuploaderrorpartial' => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد. پرونده به طور ناقص بارگذاری شده‌است.',
'importuploaderrortemp' => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد.
پوشهٔ موقت پیدا نشد.',
'import-parse-failure' => 'خطا در تجزیهٔ اکس‌ام‌ال بارگذاری‌شده',
'import-noarticle' => 'صفحه‌ای برای بارگذاری وجود ندارد!',
'import-nonewrevisions' => 'تمام نسخه‌ها قبلاً بارگذاری شده‌اند.',
'xml-error-string' => '$1 در سطر $2، ستون $3 (بایت $4): $5',
'import-upload' => 'بارگذاری داده اکس‌ام‌ال',
'import-token-mismatch' => 'از دست رفتن اطلاعات نشست کاربری. لطفاً دوباره امتحان کنید.',
'import-invalid-interwiki' => 'از ویکی مشخص شده نمی‌توان درون‌ریزی انجام داد.',
'import-error-edit' => 'صفحهٔ «$1» وارد نمی‌شود، چون شما مجاز به ویرایش آن نیستید.',
'import-error-create' => 'صفحهٔ «$1» وارد نمی‌شود، چون شما مجاز به ایجاد آن نیستید.',
'import-error-interwiki' => 'صفحه «$1» وارد نشد. چون نام آن برای پیوند خارجی (interwiki) رزرو شده‌است.',
'import-error-special' => 'صفحه «$1» درون‌ریزی نشد، چرا که متعلق به فضای نام غیرمجاز است.',
'import-error-invalid' => 'صفحه "$1" به دلیل نامعتبر بودن نامش وارد نمی‌شود.',
'import-options-wrong' => '{{PLURAL:$2|جزئیات|جزئیات}} اشتباه: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'با توجه به ریشه صفحه عنوان نامعتبر است.',
'import-rootpage-nosubpage' => 'فضای نام  "$1" صفحهٔ مبنا اجازهٔ زیرصفحه نمی‌دهد.',

# Import log
'importlogpage' => 'سیاههٔ درون‌ریزی‌ها',
'importlogpagetext' => 'درون‌ریزی صفحه‌ها به همراه تاریخچهٔ ویرایش آن‌ها از ویکی‌های دیگر.',
'import-logentry-upload' => '[[$1]] را از طریق بارگذاری پرونده درون‌ریزی کرد',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|نسخه|نسخه}}',
'import-logentry-interwiki' => '$1 را تراویکی کرد',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|نسخه|نسخه}} از $2',

# JavaScriptTest
'javascripttest' => 'آزمایش جاوا اسکریپت',
'javascripttest-disabled' => 'این عملکرد در این ویکی فعال نشده‌است.',
'javascripttest-title' => 'در حال اجرای آزمایش‌های $1',
'javascripttest-pagetext-noframework' => 'این صفحه برای اجرای آزمایش‌های جاوا اسکریپت کنار گذاشته شده‌است.',
'javascripttest-pagetext-unknownframework' => 'چارچوب آزمایشی ناشناخته «$1».',
'javascripttest-pagetext-frameworks' => 'لطفاً یکی از فریم‌ورک‌های آزمایشی زیر را انتخاب کنید: $1',
'javascripttest-pagetext-skins' => 'پوسته‌ای را برای اجرای آزمایش‌ها انتخاب کنید:',
'javascripttest-qunit-intro' => '[$1 مستندات آزمایش] را در mediawiki.org ببینید.',
'javascripttest-qunit-heading' => 'مجموعه آزمایش QUnit جاوااسکریپت برای مدیاویکی',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'صفحهٔ کاربری شما',
'tooltip-pt-anonuserpage' => 'صفحهٔ کاربری نشانی آی‌پی‌ای که با آن ویرایش می‌کنید',
'tooltip-pt-mytalk' => 'صفحهٔ بحث شما',
'tooltip-pt-anontalk' => 'بحث پیرامون ویرایش‌های این نشانی آی‌پی',
'tooltip-pt-preferences' => 'ترجیحات من',
'tooltip-pt-watchlist' => 'فهرست صفحه‌هایی که شما تغییرات آن‌ها را پی‌گیری می‌کنید',
'tooltip-pt-mycontris' => 'فهرست مشارکت‌های شما',
'tooltip-pt-login' => 'توصیه می‌شود که به سامانه وارد شوید، گرچه اجباری نیست',
'tooltip-pt-anonlogin' => 'توصیه می‌شود که به سامانه وارد شوید، لیکن اجباری نیست',
'tooltip-pt-logout' => 'خروج از سامانه',
'tooltip-ca-talk' => 'گفتگو پیرامون محتوای صفحه',
'tooltip-ca-edit' => 'شما می‌توانید این صفحه را ویرایش کنید. لطفاً پیش از ذخیره از دکمهٔ پیش‌نمایش استفاده کنید.',
'tooltip-ca-addsection' => 'بخشی جدید ایجاد کنید',
'tooltip-ca-viewsource' => 'این صفحه محافظت‌شده‌است.
می‌توانید متن مبدأ آن را ببینید',
'tooltip-ca-history' => 'نسخه‌های پیشین این صفحه',
'tooltip-ca-protect' => 'محافظت از این صفحه',
'tooltip-ca-unprotect' => 'تغییر محافظت این صفحه',
'tooltip-ca-delete' => 'حذف این صفحه',
'tooltip-ca-undelete' => 'بازگرداندن نسخه‌های صفحهٔ حذف‌شده',
'tooltip-ca-move' => 'انتقال این صفحه',
'tooltip-ca-watch' => 'افزودن این صفحه به فهرست پی‌گیری‌هایتان',
'tooltip-ca-unwatch' => 'حذف این صفحه از فهرست پی‌گیری‌هایتان',
'tooltip-search' => 'جستجو در {{SITENAME}}',
'tooltip-search-go' => 'در صورت امکان به صفحه‌ای با همین نام برو',
'tooltip-search-fulltext' => 'جستجوی این عبارت در صفحه‌ها',
'tooltip-p-logo' => 'مشاهدهٔ صفحهٔ اصلی',
'tooltip-n-mainpage' => 'مشاهدهٔ صفحهٔ اصلی',
'tooltip-n-mainpage-description' => 'مشاهدهٔ صفحهٔ اصلی',
'tooltip-n-portal' => 'پیرامون پروژه، آنچه می‌توانید انجام دهید و اینکه چه چیز را کجا پیدا کنید',
'tooltip-n-currentevents' => 'یافتن اطلاعات پیش‌زمینه پیرامون رویدادهای کنونی',
'tooltip-n-recentchanges' => 'فهرستی از تغییرات اخیر ویکی',
'tooltip-n-randompage' => 'آوردن یک صفحهٔ تصادفی',
'tooltip-n-help' => 'مکانی برای دریافتن',
'tooltip-t-whatlinkshere' => 'فهرست همهٔ صفحه‌هایی که به این صفحه پیوند می‌دهند',
'tooltip-t-recentchangeslinked' => 'تغییرات اخیر صفحه‌هایی که این صفحه به آن‌ها پیوند دارد',
'tooltip-feed-rss' => 'خبرنامه آراس‌اس برای این صفحه',
'tooltip-feed-atom' => 'خبرنامهٔ اتم برای این صفحه',
'tooltip-t-contributions' => 'فهرست مشارکت‌های این کاربر',
'tooltip-t-emailuser' => 'فرستادن رایانامه به این کاربر',
'tooltip-t-upload' => 'بارگذاری تصاویر و پرونده‌های دیگر',
'tooltip-t-specialpages' => 'فهرستی از همهٔ صفحه‌های ویژه',
'tooltip-t-print' => 'نسخهٔ قابل چاپ این صفحه',
'tooltip-t-permalink' => 'پیوند پایدار به این نسخه از صفحه',
'tooltip-ca-nstab-main' => 'دیدن صفحهٔ محتویات',
'tooltip-ca-nstab-user' => 'نمایش صفحهٔ کاربر',
'tooltip-ca-nstab-media' => 'دیدن صفحهٔ مدیا',
'tooltip-ca-nstab-special' => 'این یک صفحهٔ ویژه است، نمی‌توانید خود صفحه را ویرایش کنید',
'tooltip-ca-nstab-project' => 'نمایش صفحهٔ پروژه',
'tooltip-ca-nstab-image' => 'دیدن صفحهٔ پرونده',
'tooltip-ca-nstab-mediawiki' => 'نمایش پیغام سامانه',
'tooltip-ca-nstab-template' => 'نمایش الگو',
'tooltip-ca-nstab-help' => 'دیدن صفحهٔ راهنما',
'tooltip-ca-nstab-category' => 'دیدن صفحهٔ رده',
'tooltip-minoredit' => 'این ویرایش را ویرایش جزئی نشانه‌گذاری کن',
'tooltip-save' => 'تغییرات خود را ذخیره کنید',
'tooltip-preview' => 'پیش‌نمایش تغییرات شما، لطفاً قبل از ذخیره‌کردن صفحه از این کلید استفاده کنید.',
'tooltip-diff' => 'نمایش تغییراتی که شما در متن داده‌اید.',
'tooltip-compareselectedversions' => 'دیدن تفاوت‌های دو نسخهٔ انتخاب‌شده از این صفحه',
'tooltip-watch' => 'این صفحه را به فهرست پی‌گیری‌هایتان بیفزایید.',
'tooltip-watchlistedit-normal-submit' => 'حذف عنوان‌ها',
'tooltip-watchlistedit-raw-submit' => 'بروزرسانی پی‌گیری‌ها',
'tooltip-recreate' => 'ایجاد دوبارهٔ صفحه صرف نظر از حذف شدن قبلی آن',
'tooltip-upload' => 'شروع بارگذاری',
'tooltip-rollback' => '«واگردانی» ویرایش(های) آخرین ویرایش‌کنندهٔ این صفحه را با یک کلیک بازمی‌گرداند.',
'tooltip-undo' => '«خنثی‌سازی» این ویرایش را خنثی می‌کند و جعبهٔ ویرایش را در حالت پیش‌نمایش باز می‌کند تا افزودن دلیل در خلاصهٔ ویرایش ممکن شود.',
'tooltip-preferences-save' => 'ذخیره کردن ترجیحات',
'tooltip-summary' => 'خلاصه‌ای وارد کنید',

# Stylesheets
'common.css' => '/* دستورات این بخش همهٔ کاربران را تحت تاثیر قرار می‌دهند. */',
'monobook.css' => '/* دستورات این بخش کاربرانی را که از پوستهٔ مونوبوک استفاده کنند تحت تاثیر قرار می‌دهند. */',

# Metadata
'notacceptable' => 'کارگذار این ویکی از ارسال داده به شکلی که برنامهٔ شما بتواند نمایش بدهد، عاجز است.',

# Attribution
'anonymous' => '{{PLURAL:$1|کاربر|کاربران}} گمنام {{SITENAME}}',
'siteuser' => '$1، کاربر {{SITENAME}}',
'anonuser' => '$1 کاربر ناشناس {{SITENAME}}',
'lastmodifiedatby' => 'این صفحه آخرین بار در $2، $1 به دست $3 تغییر یافته‌است.',
'othercontribs' => 'بر اساس اثری از $1',
'others' => 'دیگران',
'siteusers' => '$1، {{PLURAL:$2|کاربر|کاربران}} {{SITENAME}}',
'anonusers' => '$1 {{PLURAL:$2|کاربر|کاربران}} ناشناس {{SITENAME}}',
'creditspage' => 'اعتبارات این صفحه',
'nocredits' => 'اطلاعات سازندگان این صفحه موجود نیست.',

# Spam protection
'spamprotectiontitle' => 'پالایهٔ هرزنگاری‌ها',
'spamprotectiontext' => 'از ذخیره کردن صفحه توسط پالایهٔ هرزنگاری‌ها جلوگیری شد.
معمولاً این اتفاق زمانی می‌افتد که متن جدید صفحه، حاوی پیوندی به یک نشانی وب باشد که در فهرست سیاه قرار دارد.',
'spamprotectionmatch' => 'متن زیر چیزی‌است که پالایهٔ هرزه‌نگاری ما را به کارانداخت: $1',
'spambot_username' => 'هرزه‌تمیزکارِ مدیاویکی',
'spam_reverting' => 'واگردانی به آخرین نسخه‌ای که پیوندی به $1 ندارد.',
'spam_blanking' => 'تمام نسخه‌ها حاوی پیوند به $1 بود، در حال خالی کردن',
'spam_deleting' => 'تمام نسخه‌ها حاوی پیوند به $1 بود، در حال حذف',

# Info page
'pageinfo-title' => 'اطلاعات در مورد «$1»',
'pageinfo-not-current' => 'متاسفانه تهیه اطلاعات ویرایش‌های قدیمی غیرممکن است.',
'pageinfo-header-basic' => 'اطلاعات اولیه',
'pageinfo-header-edits' => 'ویرایش تاریخچه',
'pageinfo-header-restrictions' => 'حفاظت از صفحه',
'pageinfo-header-properties' => 'ويژگيهای صفحه',
'pageinfo-display-title' => 'نمایش عنوان',
'pageinfo-default-sort' => 'کلید مرتب‌سازی پیش‌فرض',
'pageinfo-length' => 'حجم صفحه  (بایت)',
'pageinfo-article-id' => 'شناسهٔ صفحه',
'pageinfo-robot-policy' => 'وضعیت موتور جستجو',
'pageinfo-robot-index' => 'فهرست‌پذیر',
'pageinfo-robot-noindex' => 'عدم فهرست‌پذیری',
'pageinfo-views' => 'شمار بازدیدها',
'pageinfo-watchers' => 'شمار پی‌گیری‌کنندگان صفحه',
'pageinfo-redirects-name' => 'تغییرمسیرها به این صفحه',
'pageinfo-subpages-name' => 'زیرصفحه‌های این صفحه',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|تغییرمسیر|تغییرمسیر}}; $3 {{PLURAL:$3|غیرتغییرمسیر|غیرتغییرمسیر}})',
'pageinfo-firstuser' => 'به‌وجود آورندهٔ صفحه',
'pageinfo-firsttime' => 'زمان ایجاد صفحه',
'pageinfo-lastuser' => 'آخرین ویرایشگر',
'pageinfo-lasttime' => 'تاریخ آخرین ویرایش',
'pageinfo-edits' => 'شمار کلی ویرایش‌ها',
'pageinfo-authors' => 'تعداد کلی نویسندگان یکتا',
'pageinfo-recent-edits' => 'شماره ویرایش‌های اخیر (در $1 گذشته)',
'pageinfo-recent-authors' => 'تعداد نویسندگان یکتای اخیر',
'pageinfo-magic-words' => '{{PLURAL:$1|حرف|حروف}} جادویی ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1| ردهٔ|ردهٔ}} پنهان ( $1 )',
'pageinfo-templates' => '{{PLURAL:$1|الگو|الگو}} استفاده‌شده ($1)',

# Skin names
'skinname-standard' => 'کلاسیک',
'skinname-nostalgia' => 'نوستالژی',
'skinname-cologneblue' => 'آبی کلن',
'skinname-monobook' => 'مونوبوک',
'skinname-myskin' => 'پوستهٔ من',
'skinname-chick' => 'شیک',
'skinname-simple' => 'ساده',
'skinname-modern' => 'مدرن',
'skinname-vector' => 'برداری',

# Patrolling
'markaspatrolleddiff' => 'برچسب گشت بزن',
'markaspatrolledtext' => 'به این صفحه برچسب گشت بزن',
'markedaspatrolled' => 'برچسب گشت زده شد',
'markedaspatrolledtext' => 'به نسخهٔ انتخاب شده از [[:$1]] برچسب گشت زده شد.',
'rcpatroldisabled' => 'گشت‌زنی تغییرات اخیر غیرفعال است',
'rcpatroldisabledtext' => 'امکان گشت‌زنی تغییرات اخیر در حال حاضر غیرفعال است.',
'markedaspatrollederror' => 'برچسب گشت زده نشد',
'markedaspatrollederrortext' => 'باید یک نسخه را مشخص کنید تا برچسب گشت بخورد.',
'markedaspatrollederror-noautopatrol' => 'شما نمی‌توانید به تغییرات انجام شده توسط خودتان برچسب گشت بزنید.',

# Patrol log
'patrol-log-page' => 'سیاههٔ گشت',
'patrol-log-header' => 'این سیاهه‌ای از ویرایش‌های گشت‌خورده است.',
'log-show-hide-patrol' => 'سیاههٔ گشت‌زنی $1',

# Image deletion
'deletedrevision' => '$1 نسخهٔ حذف شدهٔ قدیمی',
'filedeleteerror-short' => 'خطا در حذف پرونده: $1',
'filedeleteerror-long' => 'در زمان حذف پرونده خطا رخ داد:

$1',
'filedelete-missing' => 'پروندهٔ $1 قابل حذف نیست چون پرونده‌ای به این نام وجود ندارد.',
'filedelete-old-unregistered' => 'نسخهٔ پروندهٔ $1 در پایگاه داده وجود ندارد.',
'filedelete-current-unregistered' => 'پرونده‌ای با نام $1 در پایگاه داده موجود نیست.',
'filedelete-archive-read-only' => 'امکان نوشتن در پوشهٔ تاریخچهٔ $1 وجود ندارد.',

# Browsing diffs
'previousdiff' => '→ تفاوت قدیمی‌تر',
'nextdiff' => 'تفاوت جدیدتر ←',

# Media information
'mediawarning' => "'''هشدار''': این پرونده ممکن است حاوی کدهای مخرب باشد.
با اجرای آن رایانهٔ شما ممکن است آسیب ببیند.",
'imagemaxsize' => "محدودیت ابعاد تصویر:<br />''(برای صفحه‌های توصیف پرونده)''",
'thumbsize' => 'اندازهٔ Thumbnail:',
'widthheight' => '$1 در $2',
'widthheightpage' => '$1×$2، $3 {{PLURAL:$3|صفحه|صفحه}}',
'file-info' => 'اندازهٔ پرونده: $1، نوع  MIME $2',
'file-info-size' => '<span dir="ltr">$1 × $2</span> پیکسل، اندازهٔ پرونده: $3، نوع MIME پرونده: $4',
'file-info-size-pages' => '<span style="direction:ltr">$1 × $2</span> نقطه، حجم پرونده: $3، نوع MIME پرونده: $4، $5 صفحه',
'file-nohires' => 'تفکیک‌پذیری بالاتری در دسترس نیست.',
'svg-long-desc' => 'پروندهٔ اس‌وی‌جی، با ابعاد <span dir="ltr">$1 × $2</span> پیکسل، اندازهٔ پرونده: $3',
'svg-long-desc-animated' => 'پروندهٔ اس‌وی‌جی متحرک، با ابعاد <span dir="ltr">$1 × $2</span> پیکسل، اندازهٔ پرونده: $3',
'show-big-image' => 'تصویر با تفکیک‌پذیری بالاتر',
'show-big-image-preview' => 'اندازهٔ این پیش‌نمایش: $1.',
'show-big-image-other' => '{{PLURAL:$2|کیفیت|کیفیت‌های}} دیگر: $1.',
'show-big-image-size' => '<span dir="ltr">$1 × $2</span> پیکسل',
'file-info-gif-looped' => 'چرخش‌دار',
'file-info-gif-frames' => '$1 {{PLURAL:$1|قاب|قاب}}',
'file-info-png-looped' => 'چرخش‌دار',
'file-info-png-repeat' => '$1 {{PLURAL:$1|بار|بار}} پخش شد',
'file-info-png-frames' => '$1 {{PLURAL:$1|قاب|قاب}}',
'file-no-thumb-animation' => "'''توجه: به علت مسائل فنی پیش‌نمایش پرونده به صورت متحرک نمایش داده نمی‌شود.'''",
'file-no-thumb-animation-gif' => "'''توجه:به علت مسائل فنی پیش‌نمایش پرونده‌های GIF مانند این پرونده، به صورت متحرک نمایش داده نمی‌شود.'''",

# Special:NewFiles
'newimages' => 'نگارخانهٔ پرونده‌های جدید',
'imagelisttext' => 'در زیر فهرست $1 {{PLURAL:$1|تصویری|تصویری}} که $2 مرتب شده است آمده است.',
'newimages-summary' => 'این صفحهٔ ویژه آخرین پرونده‌های بارگذاری شده را نمایش می‌دهد',
'newimages-legend' => 'پالودن',
'newimages-label' => 'نام پرونده (یا قسمتی از آن):',
'showhidebots' => '($1 ربات‌ها)',
'noimages' => 'چیزی برای دیدن نیست.',
'ilsubmit' => 'جستجو',
'bydate' => 'از روی تاریخ',
'sp-newimages-showfrom' => 'نشان‌دادن تصویرهای جدید از $2، $1 به بعد',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1ثانیه| $1  ثانیه}}',
'minutes' => '{{PLURAL: $1|دقیقه|دقیقه}}',
'hours' => '{{PLURAL: $1|ساعت|ساعت}}',
'days' => '{{PLURAL: $1|روز|روز}}',
'ago' => '$1 پیش',

# Bad image list
'bad_image_list' => 'اطلاعات را باید اینگونه وارد کنید:

فقط موارد درون فهرست (سطرهایی که با * شروع می‌شوند) در نظر گرفته می‌شوند.
نخستین پیوند هر سطر باید پیوندی به یک پروندهٔ معیوب باشد.
پیوندهایی بعدی در همان سطر استثنا در نظر گرفته می‌شوند.',

# Metadata
'metadata' => 'فراداده',
'metadata-help' => 'این پرونده حاوی اطلاعات اضافه‌ای‌است که احتمالاً دوربین دیجیتال یا پویشگری که در ایجاد یا دیجیتالی‌کردن آن به کار رفته آن را افزوده‌است. اگر پرونده از وضعیت ابتدایی‌اش تغییر داده شده باشد آنگاه ممکن است شرح و تفصیلات موجود اطلاعات تصویر را تماماً بازتاب ندهد.',
'metadata-expand' => 'نمایش جزئیات تفصیلی',
'metadata-collapse' => 'نهفتن جزئیات تفصیلی',
'metadata-fields' => 'فرادادهٔ تصویر نشان داده شده در این پیغام وقتی جدول فراداده‌های تصویر جمع شده باشد هم نمایش داده می‌شود. بقیهٔ موارد تنها زمانی نشان داده می‌شوند که جدول یاد شده باز شود.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => 'عرض',
'exif-imagelength' => 'طول',
'exif-bitspersample' => 'نقطه در هر جزء',
'exif-compression' => 'شِمای فشرده‌سازی',
'exif-photometricinterpretation' => 'ترکیب نقاط',
'exif-orientation' => 'جهت',
'exif-samplesperpixel' => 'تعداد اجزا',
'exif-planarconfiguration' => 'آرایش داده‌ها',
'exif-ycbcrsubsampling' => 'نسبت زیرنمونهٔ Y به C',
'exif-ycbcrpositioning' => 'موقعیت Y و C',
'exif-xresolution' => 'تفکیک‌پذیری افقی',
'exif-yresolution' => 'تفکیک‌پذیری عمودی',
'exif-stripoffsets' => 'جایگاه داده‌های تصویر',
'exif-rowsperstrip' => 'تعداد ردیف‌ها در هر نوار',
'exif-stripbytecounts' => 'بایت در هر نوار فشرده',
'exif-jpeginterchangeformat' => 'جابه‌جایی نسبت به JPEG SOI',
'exif-jpeginterchangeformatlength' => 'بایت دادهٔ JPEG',
'exif-whitepoint' => 'رنگینگی نقطهٔ سفید',
'exif-primarychromaticities' => 'رنگ‌پذیری اولویت‌ها',
'exif-ycbcrcoefficients' => 'ضرایب ماتریس تبدیل فضای رنگی',
'exif-referenceblackwhite' => 'جفت مقادیر مرجع سیاه و سفید',
'exif-datetime' => 'تاریخ و زمان تغییر پرونده',
'exif-imagedescription' => 'عنوان تصویر',
'exif-make' => 'شرکت سازندهٔ دوربین',
'exif-model' => 'مدل دوربین',
'exif-software' => 'نرم‌افزار استفاده‌شده',
'exif-artist' => 'تصویربردار/هنرمند',
'exif-copyright' => 'دارندهٔ حق تکثیر',
'exif-exifversion' => 'نسخهٔ exif',
'exif-flashpixversion' => 'نسخهٔ پشتیبانی‌شدهٔ Flashpix',
'exif-colorspace' => 'فضای رنگی',
'exif-componentsconfiguration' => 'معنی هر یک از مؤلفه‌ها',
'exif-compressedbitsperpixel' => 'حالت فشرده‌سازی تصویر',
'exif-pixelydimension' => 'پهنای تصویر',
'exif-pixelxdimension' => 'بلندی تصویر',
'exif-usercomment' => 'توضیحات کاربر',
'exif-relatedsoundfile' => 'پروندهٔ صوتی مربوط',
'exif-datetimeoriginal' => 'تاریخ و زمان تولید داده‌ها',
'exif-datetimedigitized' => 'تاریخ و زمان دیجیتالی‌شدن',
'exif-subsectime' => 'کسر ثانیهٔ تاریخ و زمان',
'exif-subsectimeoriginal' => 'کسر ثانیهٔ زمان اصلی',
'exif-subsectimedigitized' => 'کسر ثانیهٔ زمان دیجیتال',
'exif-exposuretime' => 'زمان نوردهی',
'exif-exposuretime-format' => '$1 ثانیه ($2)',
'exif-fnumber' => 'ضریب اف',
'exif-exposureprogram' => 'برنامهٔ نوردهی',
'exif-spectralsensitivity' => 'حساسیت طیفی',
'exif-isospeedratings' => 'درجه‌بندی سرعت ایزو',
'exif-shutterspeedvalue' => 'سرعت آپکس شاتر',
'exif-aperturevalue' => 'اندازهٔ آپکس دیافراگم',
'exif-brightnessvalue' => 'روشنایی آپکس',
'exif-exposurebiasvalue' => 'خطای نوردهی',
'exif-maxaperturevalue' => 'حداکثر گشادگی زمین',
'exif-subjectdistance' => 'فاصلهٔ سوژه',
'exif-meteringmode' => 'حالت سنجش نور',
'exif-lightsource' => 'منبع نور',
'exif-flash' => 'فلاش',
'exif-focallength' => 'فاصلهٔ کانونی عدسی',
'exif-focallength-format' => '$1 میلی‌متر',
'exif-subjectarea' => 'مساحت جسم',
'exif-flashenergy' => 'قدرت فلاش',
'exif-focalplanexresolution' => 'تفکیک‌پذیری X صفحهٔ کانونی',
'exif-focalplaneyresolution' => 'تفکیک‌پذیری Y صفحهٔ کانونی',
'exif-focalplaneresolutionunit' => 'واحد تفکیک‌پذیری صفحهٔ کانونی',
'exif-subjectlocation' => 'مکان سوژه',
'exif-exposureindex' => 'شاخص نوردهی',
'exif-sensingmethod' => 'روش حسگری',
'exif-filesource' => 'منبع پرونده',
'exif-scenetype' => 'نوع صحنه',
'exif-customrendered' => 'ظهور عکس سفارشی',
'exif-exposuremode' => 'حالت نوردهی',
'exif-whitebalance' => 'تعادل رنگ سفید (white balance)',
'exif-digitalzoomratio' => 'نسبت زوم دیجیتال',
'exif-focallengthin35mmfilm' => 'فاصلهٔ کانونی برای فیلم ۳۵ میلی‌متری',
'exif-scenecapturetype' => 'نوع ضبط صحنه',
'exif-gaincontrol' => 'تنظیم صحنه',
'exif-contrast' => 'کنتراست',
'exif-saturation' => 'غلظت رنگ',
'exif-sharpness' => 'وضوح',
'exif-devicesettingdescription' => 'شرح تنظیمات دستگاه',
'exif-subjectdistancerange' => 'محدودهٔ فاصلهٔ سوژه',
'exif-imageuniqueid' => 'شناسهٔ یکتای تصویر',
'exif-gpsversionid' => 'نسخهٔ برچسب جی‌پی‌اس',
'exif-gpslatituderef' => 'عرض جغرافیایی شمالی یا جنوبی',
'exif-gpslatitude' => 'عرض جغرافیایی',
'exif-gpslongituderef' => 'طول جغرافیایی شرقی یا غربی',
'exif-gpslongitude' => 'طول جغرافیایی',
'exif-gpsaltituderef' => 'نقطهٔ مرجع ارتفاع',
'exif-gpsaltitude' => 'ارتفاع',
'exif-gpstimestamp' => 'زمان جی‌پی‌اس (ساعت اتمی)',
'exif-gpssatellites' => 'ماهواره‌های استفاده‌شده برای اندازه‌گیری',
'exif-gpsstatus' => 'وضعیت گیرنده',
'exif-gpsmeasuremode' => 'حالت اندازه‌گیری',
'exif-gpsdop' => 'دقت اندازه‌گیری',
'exif-gpsspeedref' => 'یکای سرعت',
'exif-gpsspeed' => 'سرعت گیرندهٔ جی‌پی‌اس',
'exif-gpstrackref' => 'مرجع برای جهت حرکت',
'exif-gpstrack' => 'جهت حرکت',
'exif-gpsimgdirectionref' => 'مرجع برای جهت تصویر',
'exif-gpsimgdirection' => 'جهت تصویر',
'exif-gpsmapdatum' => 'اطلاعات نقشه‌برداری ژئودزیک',
'exif-gpsdestlatituderef' => 'مرجع برای عرض جغرافیایی مقصد',
'exif-gpsdestlatitude' => 'عرض جغرافیایی مقصد',
'exif-gpsdestlongituderef' => 'مرجع برای طول جغرافیایی مقصد',
'exif-gpsdestlongitude' => 'طول جغرافیایی مقصد',
'exif-gpsdestbearingref' => 'مرجع برای جهت مقصد',
'exif-gpsdestbearing' => 'جهت مقصد',
'exif-gpsdestdistanceref' => 'مرجع برای فاصله تا مقصد',
'exif-gpsdestdistance' => 'فاصله تا مقصد',
'exif-gpsprocessingmethod' => 'نام روش پردازش GPS',
'exif-gpsareainformation' => 'نام ناحیهٔ جی‌پی‌اس',
'exif-gpsdatestamp' => 'تاریخ جی‌پی‌اس',
'exif-gpsdifferential' => 'تصحیح جزئی جی‌پی‌اس',
'exif-jpegfilecomment' => 'توضیحات پرونده JPEG',
'exif-keywords' => 'واژه‌های کلیدی',
'exif-worldregioncreated' => 'منطقه‌ای از جهان که تصویر در آن گرفته شده',
'exif-countrycreated' => 'کشوری که تصویر در آن گرفته شده',
'exif-countrycodecreated' => 'کد کشوری که تصویر در آن گرفته شده',
'exif-provinceorstatecreated' => 'استان یا ایالتی که تصویر در آن گرفته شده',
'exif-citycreated' => 'شهری که تصویر در آن گرفته شده',
'exif-sublocationcreated' => 'بخشی از شهر که تصویر در آن گرفته شده',
'exif-worldregiondest' => 'منقطه جهان نمایش داده شده',
'exif-countrydest' => 'کشور نمایش داده شده',
'exif-countrycodedest' => 'کد کشور نمایش داده شده',
'exif-provinceorstatedest' => 'استان یا ایالت نمایش داده شده',
'exif-citydest' => 'شهر نمایش داده شده',
'exif-sublocationdest' => 'بخش شهر نمایش داده شده',
'exif-objectname' => 'عنوان کوتاه',
'exif-specialinstructions' => 'دستورالعمل‌های ویژه',
'exif-headline' => 'عنوان',
'exif-credit' => 'صاحب امتیاز/ارائه کننده',
'exif-source' => 'منبع',
'exif-editstatus' => 'وضعیت تحریریه تصویر',
'exif-urgency' => 'فوریت',
'exif-fixtureidentifier' => 'نام ستون نشریه',
'exif-locationdest' => 'محل به تصویر کشیده شده',
'exif-locationdestcode' => 'کد محل به تصویر کشیده شده',
'exif-objectcycle' => 'زمان روز که این رسانه برای آن در نظر گرفته شده',
'exif-contact' => 'اطلاعات تماس',
'exif-writer' => 'نویسنده',
'exif-languagecode' => 'زبان',
'exif-iimversion' => 'نسخه IIM',
'exif-iimcategory' => 'رده',
'exif-iimsupplementalcategory' => 'رده‌های تکمیلی',
'exif-datetimeexpires' => 'استفاده تا تاریخ',
'exif-datetimereleased' => 'منتشر شده در',
'exif-originaltransmissionref' => 'کد محل انتقال اصلی',
'exif-identifier' => 'شناسه',
'exif-lens' => 'لنز مورد استفاده',
'exif-serialnumber' => 'شماره سریال دوربین',
'exif-cameraownername' => 'صاحب دوربین',
'exif-label' => 'برچسب',
'exif-datetimemetadata' => 'تاریخ آخرین تغییر فراداده',
'exif-nickname' => 'نام غیررسمی تصویر',
'exif-rating' => 'امتیاز (از 5)',
'exif-rightscertificate' => 'گواهینامه مدیریت حقوق',
'exif-copyrighted' => 'وضعیت حق تکثیر',
'exif-copyrightowner' => 'دارندهٔ حق تکثیر',
'exif-usageterms' => 'شرایط استفاده',
'exif-webstatement' => 'نسخه برخط اعلامیه حق تکثیر',
'exif-originaldocumentid' => 'شناسهٔ یکتای سند اصلی',
'exif-licenseurl' => 'نشانی اینترنتی برای مجوز حق تکثیر',
'exif-morepermissionsurl' => 'اطلاعات مجوزهای جایگزین',
'exif-attributionurl' => 'در زمان استفاده مجدد، لطفاً پیوند دهید به',
'exif-preferredattributionname' => 'در زمان استفاده مجدد، لطفاً اعتبار دهید به',
'exif-pngfilecomment' => 'توضیحات پرونده PNG',
'exif-disclaimer' => 'تکذیب‌نامه',
'exif-contentwarning' => 'هشدار محتوا',
'exif-giffilecomment' => 'توضیحات پرونده GIF',
'exif-intellectualgenre' => 'نوع مورد',
'exif-subjectnewscode' => 'کد موضوع',
'exif-scenecode' => 'IPTC کد صحنه',
'exif-event' => 'رویداد به تصویر کشیده شده',
'exif-organisationinimage' => 'سازمان به تصویر کشیده شده',
'exif-personinimage' => 'فرد به تصویر کشیده شده',
'exif-originalimageheight' => 'بلندی تصویر قبل از برش دادن',
'exif-originalimagewidth' => 'پهنای تصویر قبل از برش دادن',

# EXIF attributes
'exif-compression-1' => 'غیرفشرده',
'exif-compression-2' => 'رمزگذاری سی‌سی‌آی‌تی‌تی گروه ۳ یک بعدی به روش هافمن تغییریافته روی طول',
'exif-compression-3' => 'رمزگذاری نمابر سی‌سی‌آی‌تی‌تی گروه ۳',
'exif-compression-4' => 'رمزگذاری نمابر سی‌سی‌آی‌تی‌تی گروه ۴',

'exif-copyrighted-true' => 'دارای حق تکثیر',
'exif-copyrighted-false' => 'مالکیت عمومی',

'exif-unknowndate' => 'تاریخ نامعلوم',

'exif-orientation-1' => 'عادی',
'exif-orientation-2' => 'افقی پشت و روشده',
'exif-orientation-3' => '۱۸۰ درجه چرخیده',
'exif-orientation-4' => 'عمودی پشت و روشده',
'exif-orientation-5' => '۹۰° پادساعتگرد چرخیده و عمودی پشت و رو شده',
'exif-orientation-6' => '۹۰° پادساعتگرد چرخیده',
'exif-orientation-7' => '۹۰° ساعتگرد چرخیده و عمودی پشت و رو شده',
'exif-orientation-8' => '۹۰° ساعتگرد چرخیده',

'exif-planarconfiguration-1' => 'قالب فربه',
'exif-planarconfiguration-2' => 'قالب دووجهی',

'exif-xyresolution-i' => '$1 نقطه در اینچ',
'exif-xyresolution-c' => '$1 نقطه در سانتی‌متر',

'exif-colorspace-65535' => 'تنظیم‌نشده',

'exif-componentsconfiguration-0' => 'وجود ندارد',

'exif-exposureprogram-0' => 'تعریف‌نشده',
'exif-exposureprogram-1' => 'دستی',
'exif-exposureprogram-2' => 'برنامهٔ عادی',
'exif-exposureprogram-3' => 'اولویت دیافراگم',
'exif-exposureprogram-4' => 'اولویت شاتر',
'exif-exposureprogram-5' => 'برنامه خلاق (با گرایش به سمت عمق میدان)',
'exif-exposureprogram-6' => 'برنامه پرجنبش (با گرایش به سمت سرعت بیشتر شاتر)',
'exif-exposureprogram-7' => 'حالت پرتره (برای عکس‌های نزدیک که پس‌زمینه خارج از فاصلهٔ کانونی است)',
'exif-exposureprogram-8' => 'حالت منظره (برای عکس‌های منظره که تمرکز روی پس‌زمینه است)',

'exif-subjectdistance-value' => '$1 متر',

'exif-meteringmode-0' => 'نامعلوم',
'exif-meteringmode-1' => 'میانگین',
'exif-meteringmode-2' => 'میانگین با مرکز سنگین',
'exif-meteringmode-3' => 'تک‌نقطه‌ای',
'exif-meteringmode-4' => 'چندنقطه‌ای',
'exif-meteringmode-5' => 'طرح‌دار',
'exif-meteringmode-6' => 'جزئی',
'exif-meteringmode-255' => 'غیره',

'exif-lightsource-0' => 'نامعلوم',
'exif-lightsource-1' => 'روشنایی روز',
'exif-lightsource-2' => 'فلورسانت',
'exif-lightsource-3' => 'تنگستن (نور بدون گرما)',
'exif-lightsource-4' => 'فلاش',
'exif-lightsource-9' => 'هوای خوب',
'exif-lightsource-10' => 'آسمان ابری',
'exif-lightsource-11' => 'سایه',
'exif-lightsource-12' => 'مهتابی در روز (D 5700 – 7100K)',
'exif-lightsource-13' => 'مهتابی سفید در روز (N 4600 – 5400K)',
'exif-lightsource-14' => 'مهتابی سفید خنک (W 3900 – 4500K)',
'exif-lightsource-15' => 'مهتابی سفید (WW 3200 – 3700K)',
'exif-lightsource-17' => 'نور استاندارد A',
'exif-lightsource-18' => 'نور استاندارد B',
'exif-lightsource-19' => 'نور استاندارد C',
'exif-lightsource-24' => 'لامپ تنگستن کارخانه ISO',
'exif-lightsource-255' => 'سایر',

# Flash modes
'exif-flash-fired-0' => 'فلاش زده نشد',
'exif-flash-fired-1' => 'با زدن فلاش',
'exif-flash-return-0' => 'فاقد عملکرد کشف نور انعکاسی',
'exif-flash-return-2' => 'نور انعکاسی کشف نشد',
'exif-flash-return-3' => 'نور انعکاسی کشف شد',
'exif-flash-mode-1' => 'فلاش زدن اجباری',
'exif-flash-mode-2' => 'جلوگیری اجباری از فلاش زدن',
'exif-flash-mode-3' => 'حالت خودکار',
'exif-flash-function-1' => 'فاقد عملکرد فلاش',
'exif-flash-redeye-1' => 'حالت اصلاح سرخی چشم‌ها',

'exif-focalplaneresolutionunit-2' => 'اینچ',

'exif-sensingmethod-1' => 'تعریف‌نشده',
'exif-sensingmethod-2' => 'حسگر ناحیهٔ رنگی یک تراشه‌ای',
'exif-sensingmethod-3' => 'حسگر ناحیهٔ رنگی دو تراشه‌ای',
'exif-sensingmethod-4' => 'حسگر ناحیهٔ رنگی سه تراشه‌ای',
'exif-sensingmethod-5' => 'حسگر ناحیه‌ای ترتیبی رنگ‌ها',
'exif-sensingmethod-7' => 'حسگر سه‌خطی',
'exif-sensingmethod-8' => 'حسگر خطی ترتیبی رنگ‌ها',

'exif-filesource-3' => 'دوربین عکاسی دیجیتال',

'exif-scenetype-1' => 'تصویر مستقیماً عکاسی شده',

'exif-customrendered-0' => 'ظهور عادی',
'exif-customrendered-1' => 'ظهور سفارشی',

'exif-exposuremode-0' => 'نوردهی خودکار',
'exif-exposuremode-1' => 'نوردهی دستی',
'exif-exposuremode-2' => 'قاب‌بندی خودکار (Auto bracket)',

'exif-whitebalance-0' => 'تنظیم خودکار تعادل رنگ سفید (white balance)',
'exif-whitebalance-1' => 'تنظیم دستی تعادل رنگ سفید (white balance)',

'exif-scenecapturetype-0' => 'استاندارد',
'exif-scenecapturetype-1' => 'چشم‌انداز',
'exif-scenecapturetype-2' => 'پرتره',
'exif-scenecapturetype-3' => 'شبانه',

'exif-gaincontrol-0' => 'هیچ',
'exif-gaincontrol-1' => 'افزایش حداقل دریافتی',
'exif-gaincontrol-2' => 'افزایش حداکثر دریافتی',
'exif-gaincontrol-3' => 'کاهش حداقل دریافتی',
'exif-gaincontrol-4' => 'کاهش حداکثر دریافتی',

'exif-contrast-0' => 'عادی',
'exif-contrast-1' => 'نرم',
'exif-contrast-2' => 'زبر',

'exif-saturation-0' => 'عادی',
'exif-saturation-1' => 'رنگ‌های رقیق شده',
'exif-saturation-2' => 'رنگ‌های تغلیظ شده',

'exif-sharpness-0' => 'عادی',
'exif-sharpness-1' => 'نرم',
'exif-sharpness-2' => 'زبر',

'exif-subjectdistancerange-0' => 'نامعلوم',
'exif-subjectdistancerange-1' => 'ماکرو',
'exif-subjectdistancerange-2' => 'نمای نزدیک',
'exif-subjectdistancerange-3' => 'نمای دور',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'عرض جغرافیایی شمالی',
'exif-gpslatitude-s' => 'عرض جغرافیایی جنوبی',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'طول جغرافیایی شرقی',
'exif-gpslongitude-w' => 'طول جغرافیایی غربی',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|متر|متر}} بالاتر از سطح دریا',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|متر|متر}} پایین‌تر از سطح دریا',

'exif-gpsstatus-a' => 'در حال اندازه‌گیری',
'exif-gpsstatus-v' => 'مقایسه‌پذیری اندازه‌گیری',

'exif-gpsmeasuremode-2' => 'اندازه‌گیری دوبعدی',
'exif-gpsmeasuremode-3' => 'اندازه‌گیری سه‌بعدی',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کیلومتر بر ساعت',
'exif-gpsspeed-m' => 'مایل بر ساعت',
'exif-gpsspeed-n' => 'گره',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'کیلومتر',
'exif-gpsdestdistance-m' => 'مایل',
'exif-gpsdestdistance-n' => 'مایل دریایی',

'exif-gpsdop-excellent' => 'عالی ($1)',
'exif-gpsdop-good' => 'خوب ($1)',
'exif-gpsdop-moderate' => 'متوسط ($1)',
'exif-gpsdop-fair' => 'نه چندان خوب ($1)',
'exif-gpsdop-poor' => 'ضعیف ($1)',

'exif-objectcycle-a' => 'تنها صبح',
'exif-objectcycle-p' => 'تنها عصر',
'exif-objectcycle-b' => 'صبح و عصر',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'جهت درست',
'exif-gpsdirection-m' => 'جهت مغناطیسی',

'exif-ycbcrpositioning-1' => 'وسط‌چین‌شده',
'exif-ycbcrpositioning-2' => 'اشتراکی',

'exif-dc-contributor' => 'مشارکت‌کنندگان',
'exif-dc-coverage' => 'محدوده مکانی و یا زمانی رسانه',
'exif-dc-date' => 'تاریخ (ها)',
'exif-dc-publisher' => 'ناشر',
'exif-dc-relation' => 'رسانه‌های مرتبط',
'exif-dc-rights' => 'حقوق',
'exif-dc-source' => 'رسانه منبع',
'exif-dc-type' => 'نوع رسانه',

'exif-rating-rejected' => 'رد شده',

'exif-isospeedratings-overflow' => 'بزرگتر از ۶۵۵۳۵',

'exif-iimcategory-ace' => 'هنر، فرهنگ و سرگرمی',
'exif-iimcategory-clj' => 'جنایت و قانون',
'exif-iimcategory-dis' => 'بلایا و حوادث',
'exif-iimcategory-fin' => 'اقتصاد و تجارت',
'exif-iimcategory-edu' => 'آموزش',
'exif-iimcategory-evn' => 'محیط زیست',
'exif-iimcategory-hth' => 'سلامت',
'exif-iimcategory-hum' => 'علاقه بشر',
'exif-iimcategory-lab' => 'کار',
'exif-iimcategory-lif' => 'شیوه زندگی و اوقات فراغت',
'exif-iimcategory-pol' => 'سیاست',
'exif-iimcategory-rel' => 'مذهب و اعتقاد',
'exif-iimcategory-sci' => 'علم و فناوری',
'exif-iimcategory-soi' => 'مسائل اجتماعی',
'exif-iimcategory-spo' => 'ورزش',
'exif-iimcategory-war' => 'جنگ ، درگیری و ناآرامی',
'exif-iimcategory-wea' => 'آب و هوا',

'exif-urgency-normal' => 'عادی ($1)',
'exif-urgency-low' => 'کم ($1)',
'exif-urgency-high' => 'زیاد ($1)',
'exif-urgency-other' => 'اولویت تعریف شده توسط کاربر ($1)',

# External editor support
'edit-externally' => 'ویرایش این پرونده با یک ویرایشگر بیرونی',
'edit-externally-help' => '(برای اطلاعات بیشتر [//www.mediawiki.org/wiki/Manual:External_editors دستورالعمل تنظیم] را ببینید)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'همه',
'namespacesall' => 'همه',
'monthsall' => 'همهٔ ماه‌ها',
'limitall' => 'همه',

# E-mail address confirmation
'confirmemail' => 'تأیید نشانی رایانامه',
'confirmemail_noemail' => 'شما در صفحهٔ [[Special:Preferences|ترجیحات کاربری]] خود نشانی رایانامه معتبری وارد نکرده‌اید.',
'confirmemail_text' => 'این ویکی شما را ملزم به تأیید اعتبار رایانامه خود، پیش از استفاده از خدمات رایانامه در اینجا می‌کند. دکمهٔ زیرین را فعال کنید تا نامهٔ تأییدی به نشانی رایانامهٔ شما فرستاده شود. این نامه دربردارندهٔ پیوندی خواهد بود که حاوی یک کد است. پیوند را در مرورگر خود بار کنید (اجرا) کنید تا اعتبار نشانی رایانامهٔ شما تایید شود.',
'confirmemail_pending' => 'یک کد تأییدی پیشتر برای شما به صورت نامه فرستاده شده‌است. اگر همین اواخر حساب خود را باز کرده‌اید شاید بد نباشد که پیش از درخواست یک کد جدید چند دقیقه درنگ کنید تا شاید نامهٔ قبلی برسد.',
'confirmemail_send' => 'پُست‌کردن یک کد تأیید',
'confirmemail_sent' => 'یک نامهٔ تأییدی فرستاده شد.',
'confirmemail_oncreate' => 'یک کد تأییدی به نشانی رایانامهٔ شما فرستاده شد.
برای واردشدن به سامانه نیازی به این کد نیست، ولی برای راه‌اندازی امکانات وابسته به رایانامه در این ویکی به آن نیاز خواهید داشت.',
'confirmemail_sendfailed' => 'فرستادن رایانامهٔ تأییدی ممکن نشد.
نشانی رایانامه را از نظر وجود نویسه‌های نامعتبر بررسی کنید.

پاسخ سامانه ارسال رایانامه: $1',
'confirmemail_invalid' => 'کد تأیید نامعتبر است. ممکن است که منقضی شده باشد.',
'confirmemail_needlogin' => 'برای تأیید نشانی رایانامه‌تان نیاز به $1 دارید.',
'confirmemail_success' => 'نشانی رایانامهٔ شما تأیید شده‌است.

هم‌اینک می‌توانید [[Special:UserLogin|به سامانه وارد شوید]] و از ویکی لذت ببرید.',
'confirmemail_loggedin' => 'نشانی رایانامهٔ شما تأیید شد.',
'confirmemail_error' => 'هنگام ذخیرهٔ تأیید شما به مشکلی برخورده شد.',
'confirmemail_subject' => 'تأیید نشانی رایانامهٔ شما {{SITENAME}}',
'confirmemail_body' => 'یک نفر، احتمالاً خود شما، از نشانی آی‌پی $1 حساب کاربری‌ای با نام «$2» و این نشانی رایانامه در {{SITENAME}} ایجاد کرده‌است.

برای تأیید این که این حساب واقعاً متعلق به شماست و نیز برای فعال‌سازی امکانات رایانامه {{SITENAME}} پیوند زیر را در مرورگر اینترنت خود باز کنید:

$3

اگر شما این حساب کاربری را ثبت *نکرده‌اید*، لطفاً پیوند زیر را
دنبال کنید تا تأیید نشانی رایانامه لغو شود:

$5

این کدِ تأیید در تاریخ $4 منقضی خواهد شد.
</div>',
'confirmemail_body_changed' => 'یک نفر، احتمالاً خود شما، از نشانی آی‌پی $1 نشانی رایانامه حساب «$2» در {{SITENAME}} را تغییر داده‌است.

برای تأیید این که این حساب واقعاً به شما تعلق دارد و فعال کردن دوبارهٔ ویژگی رایانامه در {{SITENAME}}، پیوند زیر را در مرورگرتان باز کنید:

$3

اگر این حساب متعلق به شما نیست، پیوند زیر را دنبال کنید تا تغییر رایانامه را لغو کنید:

$5

این تأییدیه در $4 منقضی می‌گردد.',
'confirmemail_body_set' => 'یک نفر، احتمالاً خود شما، از نشانی آی‌پی $1 نشانی رایانامه حساب «$2» در {{SITENAME}} را به این نشانی تغییر داده‌است.

برای تأیید این که این حساب واقعاً به شما تعلق دارد و فعال کردن دوبارهٔ ویژگی رایانامه در {{SITENAME}}، پیوند زیر را در مرورگرتان باز کنید:

$3

اگر این حساب متعلق به شما نیست، پیوند زیر را دنبال کنید تا تغییر رایانامه را لغو کنید:

$5

این تأییدیه در $4 منقضی می‌گردد.',
'confirmemail_invalidated' => 'تأیید نشانی رایانامه لغو شد',
'invalidateemail' => 'لغو کردن تأیید نشانی رایانامه',

# Scary transclusion
'scarytranscludedisabled' => '[تراگنجانش بین‌ویکیانه فعال نیست]',
'scarytranscludefailed' => '[فراخوانی الگو برای $1 میسر نشد]',
'scarytranscludetoolong' => '[نشانی اینترنتی مورد نظر (URL) بیش از اندازه بلند بود]',

# Delete conflict
'deletedwhileediting' => "'''هشدار''': این صفحه پس از اینکه شما آغاز به ویرایش آن کرده‌اید، حذف شده است!",
'confirmrecreate' => "کاربر [[User:$1|$1]] ([[User talk:$1|بحث]]) این مقاله را پس از اینکه شما آغاز به ویرایش آن نموده‌اید به دلیل زیر حذف کرده است :
: ''$2''
لطفاً تأیید کنید که مجدداً می‌خواهید این مقاله را بسازید.",
'confirmrecreate-noreason' => 'کاربر [[User:$1|$1]] ([[User talk:$1|بحث]]) این صفحه را پس از شروع ویرایش‌تان پاک کرده‌است.  لطفاً تأیید کنید که شما واقعاً می‌خواهید آن را دوباره ایجاد کنید.',
'recreate' => 'بازایجاد',

# action=purge
'confirm_purge_button' => 'تأیید',
'confirm-purge-top' => 'پاک کردن نسخهٔ حافظهٔ نهانی (Cache) این صفحه را تأیید می‌کنید؟',
'confirm-purge-bottom' => 'خالی کردن میانگیر یک صفحه باعث می‌شود که آخرین نسخهٔ آن نمایش یابد.',

# action=watch/unwatch
'confirm-watch-button' => 'تأیید',
'confirm-watch-top' => 'این صفحه به فهرست پی‌گیری‌های شما افزوده شود؟',
'confirm-unwatch-button' => 'تأیید',
'confirm-unwatch-top' => 'این صفحه از فهرست پی‌گیری‌های شما حذف شود؟',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator' => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '&rarr; صفحهٔ پیشین',
'imgmultipagenext' => 'صفحهٔ بعد &larr;',
'imgmultigo' => 'برو!',
'imgmultigoto' => 'رفتن به صفحهٔ $1',

# Table pager
'ascending_abbrev' => 'صعودی',
'descending_abbrev' => 'نزولی',
'table_pager_next' => 'صفحهٔ بعدی',
'table_pager_prev' => 'صفحه قبل',
'table_pager_first' => 'صفحهٔ نخست',
'table_pager_last' => 'صفحهٔ آخر',
'table_pager_limit' => 'نمایش $1 مورد در هر صفحه',
'table_pager_limit_label' => 'تعداد موارد در هر صفحه:',
'table_pager_limit_submit' => 'برو',
'table_pager_empty' => 'هیچ نتیجه',

# Auto-summaries
'autosumm-blank' => 'صفحه را خالی کرد',
'autosumm-replace' => "جایگزینی صفحه با '$1'",
'autoredircomment' => 'تغییرمسیر به [[$1]]',
'autosumm-new' => 'صفحه‌ای جدید حاوی «$1» ایجاد کرد',

# Size units
'size-bytes' => '$1 بایت',
'size-kilobytes' => '$1 کیلوبایت',
'size-megabytes' => '$1 مگابایت',
'size-gigabytes' => '$1 گیگابایت',

# Live preview
'livepreview-loading' => 'در حال بارگیری…',
'livepreview-ready' => 'بارشدن… آماده!',
'livepreview-failed' => 'پیش‌نمایش زنده به مشکل برخورد! لطفاً از پیش‌نمایش عادی استفاده کنید',
'livepreview-error' => 'ارتباط به مشکل برخورد: $1 "$2" از پیش‌نمایش عادی استفاده کنید.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'ممکن است تغییرات تازه‌تر از $1 {{PLURAL:$1|ثانیه|ثانیه}} در این فهرست نشان داده نشوند.',
'lag-warn-high' => 'ممکن است، به خاطر پس‌افتادگی زیاد سرور پایگاه داده، تغییرات تازه‌تر از $1 {{PLURAL:$1|ثانیه|ثانیه}} در این فهرست نشان داده نشده باشند.',

# Watchlist editor
'watchlistedit-numitems' => 'فهرست پی‌گیری‌های شما شامل {{PLURAL:$1|$1 صفحه|$1 صفحه}} به جز صفحه‌های بحث است.',
'watchlistedit-noitems' => 'فهرست پی‌گیری‌های شما خالی است.',
'watchlistedit-normal-title' => 'ویرایش فهرست پی‌گیری‌ها',
'watchlistedit-normal-legend' => 'حذف عنوان‌ها از فهرست پی‌گیری‌ها',
'watchlistedit-normal-explain' => 'عنوان‌های موجود در فهرست پیگیری شما در زیر نشان داده شده‌اند.
برای حذف هر عنوان جعبهٔ کنار آن را علامت بزنید و دکمهٔ «{{int:Watchlistedit-normal-submit}}» را بفشارید.
شما همچنین می‌توانید [[Special:EditWatchlist/raw|فهرست خام را ویرایش کنید]].',
'watchlistedit-normal-submit' => 'حذف عنوان‌ها',
'watchlistedit-normal-done' => '$1 عنوان از فهرست پی‌گیری‌های شما حذف {{PLURAL:$1|شد|شدند}}:',
'watchlistedit-raw-title' => 'ویرایش فهرست خام پی‌گیری‌ها',
'watchlistedit-raw-legend' => 'ویرایش فهرست خام پی‌گیری‌ها',
'watchlistedit-raw-explain' => 'عنوان‌های موجود در فهرست پی‌گیری‌های شما در زیر نشان داده شده‌اند، و شما می‌توانید مواردی را حذف یا اضافه کنید؛ هر مورد در یک سطر جداگانه باید قرار بگیرد.
در پایان، دکمهٔ «{{int:Watchlistedit-raw-submit}}» را بفشارید.
توجه کنید که شما می‌توانید از [[Special:EditWatchlist|ویرایشگر استاندارد فهرست پی‌گیری‌ها]] هم استفاده کنید.',
'watchlistedit-raw-titles' => 'عنوان‌ها:',
'watchlistedit-raw-submit' => 'به روز رساندن پی‌گیری‌ها',
'watchlistedit-raw-done' => 'فهرست پی‌گیری‌های شما به روز شد.',
'watchlistedit-raw-added' => '$1 عنوان به فهرست پی‌گیری‌ها اضافه {{PLURAL:$1|شد|شدند}}:',
'watchlistedit-raw-removed' => '$1 عنوان حذف {{PLURAL:$1|شد|شدند}}:',

# Watchlist editing tools
'watchlisttools-view' => 'فهرست پی‌گیری‌ها',
'watchlisttools-edit' => 'مشاهده و ویرایش فهرست پی‌گیری‌ها',
'watchlisttools-raw' => 'ویرایش فهرست خام پی‌گیری‌ها',

# Iranian month names
'iranian-calendar-m1' => 'فروردین',
'iranian-calendar-m2' => 'اردیبهشت',
'iranian-calendar-m3' => 'خرداد',
'iranian-calendar-m4' => 'تیر',
'iranian-calendar-m5' => 'مرداد',
'iranian-calendar-m6' => 'شهریور',
'iranian-calendar-m7' => 'مهر',
'iranian-calendar-m8' => 'آبان',
'iranian-calendar-m9' => 'آذر',
'iranian-calendar-m10' => 'دی',
'iranian-calendar-m11' => 'بهمن',
'iranian-calendar-m12' => 'اسفند',

# Hijri month names
'hijri-calendar-m1' => 'محرّم',
'hijri-calendar-m2' => 'صفر',
'hijri-calendar-m3' => 'ربیع‌الاول',
'hijri-calendar-m4' => 'ربیع‌الثانی',
'hijri-calendar-m5' => 'جمادی‌الاول',
'hijri-calendar-m6' => 'جمادی‌الثانی',
'hijri-calendar-m7' => 'رجب',
'hijri-calendar-m8' => 'شعبان',
'hijri-calendar-m9' => 'رمضان',
'hijri-calendar-m10' => 'شوّال',
'hijri-calendar-m11' => 'ذی‌القعده',
'hijri-calendar-m12' => 'ذی‌الحجّه',

# Hebrew month names
'hebrew-calendar-m1' => 'تشری',
'hebrew-calendar-m2' => 'حشوان',
'hebrew-calendar-m3' => 'کسلو',
'hebrew-calendar-m4' => 'طوت',
'hebrew-calendar-m5' => 'شباط',
'hebrew-calendar-m6' => 'آذار',
'hebrew-calendar-m6a' => 'آذار',
'hebrew-calendar-m6b' => 'واذار',
'hebrew-calendar-m7' => 'نیسان',
'hebrew-calendar-m8' => 'ایار',
'hebrew-calendar-m9' => 'سیوان',
'hebrew-calendar-m10' => 'تموز',
'hebrew-calendar-m11' => 'آب',
'hebrew-calendar-m12' => 'ایلول',
'hebrew-calendar-m1-gen' => 'تشری',
'hebrew-calendar-m2-gen' => 'حشوان',
'hebrew-calendar-m3-gen' => 'کسلو',
'hebrew-calendar-m4-gen' => 'طوت',
'hebrew-calendar-m5-gen' => 'شباط',
'hebrew-calendar-m6-gen' => 'آذار',
'hebrew-calendar-m6a-gen' => 'آذار',
'hebrew-calendar-m6b-gen' => 'واذار',
'hebrew-calendar-m7-gen' => 'نیسان',
'hebrew-calendar-m8-gen' => 'ایار',
'hebrew-calendar-m9-gen' => 'سیوان',
'hebrew-calendar-m10-gen' => 'تموز',
'hebrew-calendar-m11-gen' => 'آب',
'hebrew-calendar-m12-gen' => 'ایلول',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|بحث]])',

# Core parser functions
'unknown_extension_tag' => 'برچسب ناشناختهٔ افزونه «$1»',
'duplicate-defaultsort' => 'هشدار: ترتیب پیش‌فرض «$2» ترتیب پیش‌فرض قبلی «$1» را باطل می‌کند.',

# Special:Version
'version' => 'نسخه',
'version-extensions' => 'افزونه‌های نصب‌شده',
'version-specialpages' => 'صفحه‌های ویژه',
'version-parserhooks' => 'قلاب‌های تجزیه‌گر',
'version-variables' => 'متغیرها',
'version-antispam' => 'جلوگیری از هرزنامه',
'version-skins' => 'پوسته‌ها',
'version-other' => 'غیره',
'version-mediahandlers' => 'به‌دست‌گیرنده‌های رسانه‌ها',
'version-hooks' => 'قلاب‌ها',
'version-extension-functions' => 'عملگرهای افزونه',
'version-parser-extensiontags' => 'برچسب‌های افزونه تجزیه‌گر',
'version-parser-function-hooks' => 'قلاب‌های عملگر تجزیه‌گر',
'version-hook-name' => 'نام قلاب',
'version-hook-subscribedby' => 'وارد شده توسط',
'version-version' => '(نسخه $1)',
'version-svn-revision' => '(&رلم;r$2)',
'version-license' => 'اجازه‌نامه',
'version-poweredby-credits' => "این ویکی توسط '''[//www.mediawiki.org/ مدیاویکی]''' پشتیبانی می‌شود، کلیهٔ حقوق محفوظ است © 2001-$1 $2.",
'version-poweredby-others' => 'دیگران',
'version-license-info' => 'مدیاویکی نرم‌افزاری رایگان است؛ می‌توانید آن را تحت شرایط مجوز عمومی همگانی گنو که توسط بنیاد نرم‌افزار رایگان منتشر شده‌است، بازنشر کنید؛ یا نسخهٔ ۲ از این مجوز، یا (بنا به اختیار) نسخه‌های بعدی.

مدیاویکی به این امید که مفید واقع شود منتشر شده‌است، ولی بدون هیچ‌گونه ضمانتی؛ بدون ضمانت ضمنی که تجاری یا برای کار خاصی مناسب باشد. برای اطلاعات بیشتر مجوز گنو جی‌پی‌ال را مشاهده کنید.

شما باید [{{SERVER}}{{SCRIPTPATH}}/COPYING یک نسخه از مجوز عمومی همگانی گنو] را همراه این برنامه دریافت کرده باشید؛ در غیر این صورت بنویسید برای Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA یا آن را [//www.gnu.org/licenses/old-licenses/gpl-2.0.html به صورت برخط بخوانید].',
'version-software' => 'نسخهٔ نصب‌شده',
'version-software-product' => 'محصول',
'version-software-version' => 'نسخه',
'version-entrypoints' => 'نشانی اینترنتی محل ورود',
'version-entrypoints-header-entrypoint' => 'نقطه ورود',
'version-entrypoints-header-url' => 'نشانی اینترنتی',
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath مسیر مقاله]',
'version-entrypoints-scriptpath' => '[https://www.mediawiki.org/wiki/Manual:$wgScriptPath مسیر اسکریپت]',

# Special:FilePath
'filepath' => 'مسیر پرونده',
'filepath-page' => 'پرونده:',
'filepath-submit' => 'برو',
'filepath-summary' => 'این صفحهٔ ویژه نشانی کامل برای یک پرونده را نشان می‌دهد. تصاویر با کیفیت وضوح کامل نشان داده می‌شوند، سایر انواع پرونده با برنامه مخصوص به خودشان باز می‌شوند.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'جستجو برای پرونده‌های تکراری',
'fileduplicatesearch-summary' => 'جستجو برای پرونده‌های تکراری بر اساس مقدار درهم‌شدهٔ آن‌ها صورت می‌گیرد.',
'fileduplicatesearch-legend' => 'جستجوی موارد تکراری',
'fileduplicatesearch-filename' => 'نام پرونده:',
'fileduplicatesearch-submit' => 'جستجو',
'fileduplicatesearch-info' => '<span dir="ltr">$1 × $2</span> پیکسل<br />اندازهٔ پرونده: $3<br />نوع MIME: $4',
'fileduplicatesearch-result-1' => 'پروندهٔ «$1» مورد تکراری ندارد.',
'fileduplicatesearch-result-n' => 'پروندهٔ «$1» دارای {{PLURAL:$2|یک مورد تکراری|$2 مورد تکراری}} است.',
'fileduplicatesearch-noresults' => 'پرونده‌ای با نام «$1» یافت نشد.',

# Special:SpecialPages
'specialpages' => 'صفحه‌های ویژه',
'specialpages-note' => '----
* صفحه‌های ویژهٔ عادی.
* <strong class="mw-specialpagerestricted">صفحه‌های ویژهٔ محدودشده.</strong>',
'specialpages-group-maintenance' => 'گزارش‌های نگهداری',
'specialpages-group-other' => 'صفحه‌های ویژهٔ دیگر',
'specialpages-group-login' => 'ورود / ثبت نام',
'specialpages-group-changes' => 'تغییرات اخیر و سیاهه‌ها',
'specialpages-group-media' => 'گزارش بارگذاری رسانه‌ها',
'specialpages-group-users' => 'کاربرها و دسترسی‌ها',
'specialpages-group-highuse' => 'صفحه‌های پربازدید',
'specialpages-group-pages' => 'فهرست‌های صفحه‌ها',
'specialpages-group-pagetools' => 'ابزارهای صفحه‌ها',
'specialpages-group-wiki' => 'اطلاعات و ابزارهای ویکی',
'specialpages-group-redirects' => 'صفحه‌های ویژهٔ تغییرمسیر دهنده',
'specialpages-group-spam' => 'ابزارهای هرزنگاری',

# Special:BlankPage
'blankpage' => 'صفحهٔ خالی',
'intentionallyblankpage' => 'این صفحه به طور عمدی خالی گذاشته شده است.',

# External image whitelist
'external_image_whitelist' => ' #این سطر را همان‌گونه که هست رها کنید<pre>
#عبارت‌های باقاعده (regex) را در زیر قرار دهید (فقط بخشی که بین // قرار می‌گیرد)
#آن‌ها با نشانی اینترنتی تصاویر خارجی پیوند داده شده تطبیق داده می‌شوند
#مواردی که مطابق باشند به صورت تصویر نمایش می‌یابند، و در غیر این صورت تنها یک پیوند به تصویر نمایش می‌یابد
#سطرهایی که با # آغاز شوند به عنوان توضیحات در نظر گرفته می‌شوند
#این سطرها به کوچکی و بزرگی حروف حساس هستند

#عبارت‌های باقاعده (regex)  را زیر این سطر قرار دهید. این سطر را همان‌گونه که هست رها کنید</pre>',

# Special:Tags
'tags' => 'برچسب‌های تغییر مجاز',
'tag-filter' => 'پالایش [[Special:Tags|برچسب‌ها]]:',
'tag-filter-submit' => 'پالایه',
'tags-title' => 'برچسب‌ها',
'tags-intro' => 'این صفحه فهرستی‌است از برچسب‌هایی که نرم‌افزار با آن‌ها ویرایش‌ها را علامت‌گذری می‌کند، به همراه معانی آن‌ها.',
'tags-tag' => 'نام برچسب',
'tags-display-header' => 'نمایش در فهرست‌های تغییرات',
'tags-description-header' => 'توضیح کامل معنی',
'tags-hitcount-header' => 'تغییرهای برچسب‌دار',
'tags-edit' => 'ویرایش',
'tags-hitcount' => '$1 {{PLURAL:$1|تغییر|تغییر}}',

# Special:ComparePages
'comparepages' => 'مقایسهٔ صفحه‌ها',
'compare-selector' => 'مقایسهٔ نسخه‌های صفحه‌ها',
'compare-page1' => 'صفحهٔ ۱',
'compare-page2' => 'صفحهٔ ۲',
'compare-rev1' => 'نسخهٔ ۱',
'compare-rev2' => 'نسخهٔ ۲',
'compare-submit' => 'مقایسه',
'compare-invalid-title' => 'عنوان تعیین‌شده نامعتبر است.',
'compare-title-not-exists' => 'عنوان مشخص شده وجود ندارد.',
'compare-revision-not-exists' => 'پالایهٔ مشخص شده وجود ندارد.',

# Database error messages
'dberr-header' => 'این ویکی یک ایراد دارد',
'dberr-problems' => 'شرمنده!
این تارنما از مشکلات فنی رنج می‌برد.',
'dberr-again' => 'چند دقیقه صبر کند و دوباره صفحه را بارگیری کنید.',
'dberr-info' => '(امکان برقراری ارتباط با کارساز پایگاه داده وجود ندارد: $1)',
'dberr-usegoogle' => 'شما در این مدت می‌توانید با استفاده از گوگل جستجو کنید.',
'dberr-outofdate' => 'توجه کنید که نمایه‌های آن‌ها از محتوای ما ممکن است به روز نباشد.',
'dberr-cachederror' => 'آن‌چه در ادامه می‌آید یک کپی از صفحهٔ درخواست شده است که در کاشه قرار دارد، و ممکن است به روز نباشد.',

# HTML forms
'htmlform-invalid-input' => 'بخشی از ورودی شما مشکل دارد',
'htmlform-select-badoption' => 'مقدار وارد شده یک گزینهٔ قابل قبول نیست.',
'htmlform-int-invalid' => 'مقداری که وارد کردید یک عدد صحیح نیست.',
'htmlform-float-invalid' => 'مقداری که وارد کردید یک عدد نیست.',
'htmlform-int-toolow' => 'مقداری که وارد کردید کمتر از $1 است',
'htmlform-int-toohigh' => 'مقداری که وارد کردید بیشتر از $1 است',
'htmlform-required' => 'این مقدار مورد نیاز است',
'htmlform-submit' => 'ارسال',
'htmlform-reset' => 'خنثی کردن تغییرات',
'htmlform-selectorother-other' => 'دیگر',

# SQLite database support
'sqlite-has-fts' => '$1 با پشتیبانی از جستجو در متن کامل',
'sqlite-no-fts' => '$1 بدون پشتیبانی از جستجو در متن کامل',

# New logging system
'logentry-delete-delete' => '$1 $3 را حذف کرد',
'logentry-delete-restore' => '$1 $3 را احیا کرد',
'logentry-delete-event' => '$1 پیدایی {{PLURAL:$5|یک مورد سیاهه|$5 مورد سیاهه}} را در $3 تغییر داد: $4',
'logentry-delete-revision' => '$1 پیدایی {{PLURAL:$5|یک نسخه|$5 نسخه}} صفحه $3 را تغییر داد: $4',
'logentry-delete-event-legacy' => '$1 پیدایی موارد سیاهه را در $3 تغییر داد',
'logentry-delete-revision-legacy' => '$1 پیدایی نسخه‌های $3 را تغییر داد',
'logentry-suppress-delete' => '$1 $3 را فرونشانی کرد',
'logentry-suppress-event' => '$1 پیدایی {{PLURAL:$5|یک مورد سیاهه|$5 مورد سیاهه}} را در $3 مخفیانه تغییر داد: $4',
'logentry-suppress-revision' => '$1 پیدایی {{PLURAL:$5|یک نسخه|$5 نسخه}} صفحه $3 را مخفیانه تغییر داد: $4',
'logentry-suppress-event-legacy' => '$1 پیدایی موارد سیاهه را در $3 مخفیانه تغییر داد',
'logentry-suppress-revision-legacy' => '$1 پیدایی نسخه‌های $3 را مخفیانه تغییر داد',
'revdelete-content-hid' => 'محتوا را پنهان کرد',
'revdelete-summary-hid' => 'خلاصه ویرایش را پنهان کرد',
'revdelete-uname-hid' => 'نام کاربری را پنهان کرد',
'revdelete-content-unhid' => 'محتوا را آشکار کرد',
'revdelete-summary-unhid' => 'خلاصه ویرایش را آشکار کرد',
'revdelete-uname-unhid' => 'نام کاربری را آشکار کرد',
'revdelete-restricted' => 'مدیران را محدود کرد',
'revdelete-unrestricted' => 'محدودیت مدیران را لغو کرد',
'logentry-move-move' => '$1 صفحهٔ $3 را به $4 منتقل کرد',
'logentry-move-move-noredirect' => '$1 صفحهٔ $3 را بدون برجای‌گذاشتن تغییرمسیر به $4 منتقل کرد',
'logentry-move-move_redir' => '$1 صفحهٔ $3 را به $4 که تغییرمسیر بود منتقل کرد',
'logentry-move-move_redir-noredirect' => '$1 صفحهٔ $3 را بدون برجای‌گذاشتن تغییرمسیر به $4 که تغییرمسیر بود منتقل کرد',
'logentry-patrol-patrol' => '$1 نسخه $4 صفحه $3 را به عنوان گشت خورده علامت زد',
'logentry-patrol-patrol-auto' => '$1 نسخه $4 صفحه $3 را به طور خودکار به عنوان گشت خورده علامت زد',
'logentry-newusers-newusers' => 'حساب کاربری $1 ایجاد شد',
'logentry-newusers-create' => 'حساب کاربری $1 ایجاد شد',
'logentry-newusers-create2' => 'حساب کاربری $3 توسط $1 ایجاد شد',
'logentry-newusers-autocreate' => 'حساب $1  به شکل خودکار ساخته شد',
'newuserlog-byemail' => 'گذرواژه بوسیله رایانامه ارسال شد',

# Feedback
'feedback-bugornote' => 'اگر آماده‌اید تا مشکلی فنی را با جزئیاتش شرح دهید لطفاً [$1 یک ایراد گزارش دهید]. در غیر این صورت می‌توانید از فرم سادهٔ زیر استفاده کنید. نظر شما به همراه نام کاربری و مرورگرتان به صفحهٔ «[$2 $3]» افزوده خواهد شد.',
'feedback-subject' => 'موضوع:',
'feedback-message' => 'پیغام:',
'feedback-cancel' => 'لغو',
'feedback-submit' => 'ارسال بازخورد',
'feedback-adding' => 'افزودن بازخورد به صفحه...',
'feedback-error1' => 'خطا: پاسخ‌های ناشناخته از رابط برنامه‌نویسی نرم‌افزار',
'feedback-error2' => 'خطا: شکست در ویرایش',
'feedback-error3' => 'خطا: عدم پاسخ از رابط برنامه‌نویسی نرم‌افزار',
'feedback-thanks' => 'سپاس! بازخورد شما در صفحهٔ «[$1 $2]» ثبت شد.',
'feedback-close' => 'انجام شد',
'feedback-bugcheck' => 'عالی‌است! فقط بررسی کنید که از [$1 ایرادهای شناخته‌شده] نباشد.',
'feedback-bugnew' => 'بررسی کردم. ایرادی جدید را گزارش بده',

# Search suggestions
'searchsuggest-search' => 'جستجو',
'searchsuggest-containing' => 'صفحه‌های دربردارنده...',

# API errors
'api-error-badaccess-groups' => 'شما اجازهٔ بارگذاری پرونده‌ها را در این ویکی ندارید.',
'api-error-badtoken' => 'خطای داخلی: کد امنیتی اشتباه (Bad token).',
'api-error-copyuploaddisabled' => 'بارگذاری با استفاده از نشانی اینترنتی در این کارساز غیرفعال است.',
'api-error-duplicate' => '{{PLURAL:$1|[$2 پروندهٔ دیگری]|[$2 چند پروندهٔ دیگر]}} در تارنما با محتوای یکسان وجود داشت.',
'api-error-duplicate-archive' => '{{PLURAL:$1|[$2 پروندهٔ دیگری]|[$2 چند پروندهٔ دیگر]}} در تارنما با محتوای یکسان وجود داشت، ولی حذف {{PLURAL:$1|شده است|شده‌اند}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|پروندهٔ|پرونده‌های}} تکراری که در حال حاضر حذف شده‌اند',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|پرونده|پرونده‌های}} تکراری',
'api-error-empty-file' => 'پرونده‌ای که شما ارسال کردید خالی بود.',
'api-error-emptypage' => 'ایجاد صفحه‌های خالی مجاز نیست.',
'api-error-fetchfileerror' => 'خطای داخلی: در هنگام گرفتن پرونده، یک چیزی درست پیش نرفت.',
'api-error-fileexists-forbidden' => 'یک پرونده با نام "$1" موجود است و امکان بازنویسی نیست.',
'api-error-fileexists-shared-forbidden' => 'یک پرونده با نام "$1" در انبار اشتراک پرونده موجود است و امکان بازنویسی نیست.',
'api-error-file-too-large' => 'پرونده‌ای که شما ارسال کردید بیش از اندازه بزرگ بود.',
'api-error-filename-tooshort' => 'نام پرونده بیش از اندازه کوتاه است.',
'api-error-filetype-banned' => 'این نوع پرونده ممنوع است.',
'api-error-filetype-banned-type' => '&lrm;$1 {{PLURAL:$4|یک نوع پرونده غیرمجاز است|انواعی پرونده غیرمجاز هستند}}. {{PLURAL:$3|نوع پرونده مجاز|انواع پرونده مجاز}} از این قرار است: $2 .',
'api-error-filetype-missing' => 'پرونده فرمت ندارد.',
'api-error-hookaborted' => 'اصلاحیه‌ای که شما سعی در ایجاد آن بودید توسط افزونه‌ای به دام افتاد.',
'api-error-http' => 'خطای داخلی: قادر به اتصال به سرور نیست.',
'api-error-illegal-filename' => 'نام پرونده مجاز نیست.',
'api-error-internal-error' => 'خطای داخلی: با پردازش بارگذاری شما در ویکی، یک چیز اشتباه پیش رفت.',
'api-error-invalid-file-key' => 'خطای داخلی: پرونده در حافظهٔ موقت موجود نیست.',
'api-error-missingparam' => 'خطای داخلی: پارامترهای ناموجود در درخواست.',
'api-error-missingresult' => 'خطای داخلی: نمی‌توان فهمید کپی‌برداری موفق بوده‌است یا نه.',
'api-error-mustbeloggedin' => 'برای بارگذاری پرونده‌ها شما باید به سامانه وارد شوید.',
'api-error-mustbeposted' => 'خطای داخلی: درخواست باید از روش POST HTTP ارسال گردد.',
'api-error-noimageinfo' => 'بارگذاری موفق بود، ولی کارساز هیچ اطلاعاتی دربارهٔ پرونده به ما نداد.',
'api-error-nomodule' => 'خطای داخلی: هیچ ماژول بارگذاری تنظیم نشده‌است.',
'api-error-ok-but-empty' => 'خطای داخلی : پاسخی از سرور دریافت نشد.',
'api-error-overwrite' => 'جای نوشتن یک پرونده موجود مجاز نیست.',
'api-error-stashfailed' => 'خطای داخلی: کارساز نمی‌تواند پرونده موقت را ذخیره کند.',
'api-error-timeout' => 'کارساز در زمان انتظار هیچ پاسخی نداد.',
'api-error-unclassified' => 'یک خطای ناشناخته رخ داد.',
'api-error-unknown-code' => 'خطای ناشناخته: " $1 "',
'api-error-unknown-error' => 'خطای داخلی: در زمانی که شما در حال تلاش برای بارگذاری پروندهٔ‌تان بودید، یک چیز اشتباه پیش رفت.',
'api-error-unknown-warning' => 'اخطار ناشناخته: $1',
'api-error-unknownerror' => 'خطای ناشناخته: «$1».',
'api-error-uploaddisabled' => 'بارگذاری در این ویکی غیرفعال است.',
'api-error-verification-error' => 'ممکن است پرونده آسیب دیده باشد، یا دارای پسوند نادرست باشد.',

# Durations
'duration-seconds' => '$1 ثانیه',
'duration-minutes' => '$1 دقیقه',
'duration-hours' => '$1 ساعت',
'duration-days' => '$1 روز',
'duration-weeks' => '$1 هفته',
'duration-years' => '$1 سال',
'duration-decades' => '$1 دهه',
'duration-centuries' => '$1 قرن',
'duration-millennia' => '{{PLURAL:$1|هزار سال |$1 هزار سال}}',

);
