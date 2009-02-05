<?php
/** Persian (فارسی)
 *
 * @ingroup Language
 * @file
 *
 * @author Behdarvandyani
 * @author Huji
 * @author Ladsgroup
 * @author Mardetanha
 * @author Meisam
 * @author Meno25
 * @author Roozbeh Pournader <roozbeh at gmail.com>
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'مدیا',
	NS_SPECIAL          => 'ویژه',
	NS_MAIN	            => '',
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
	'تصویر' => NS_FILE,
	'بحث_تصویر' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'تغییرمسیرهای_دوتایی' ),
	'BrokenRedirects'           => array( 'تغییرمسیرهای_خراب' ),
	'Disambiguations'           => array( 'ابهام‌زدایی' ),
	'Userlogin'                 => array( 'ورود_به_سیستم' ),
	'Userlogout'                => array( 'خروج_از_سیستم' ),
	'CreateAccount'             => array( 'ایجاد_حساب_کاربری' ),
	'Preferences'               => array( 'ترجیحات' ),
	'Watchlist'                 => array( 'فهرست_پی‌گیری' ),
	'Recentchanges'             => array( 'تغییرات_اخیر' ),
	'Upload'                    => array( 'بارگذاری_پرونده' ),
	'Listfiles'                 => array( 'فهرست_پرونده‌ها', 'فهرست_تصاویر' ),
	'Newimages'                 => array( 'تصاویر_جدید' ),
	'Listusers'                 => array( 'فهرست_کاربران' ),
	'Listgrouprights'           => array( 'اختیارات_گروه‌های_کاربری' ),
	'Statistics'                => array( 'آمار' ),
	'Randompage'                => array( 'صفحه_تصادفی' ),
	'Lonelypages'               => array( 'صفحه‌های_یتیم' ),
	'Uncategorizedpages'        => array( 'صفحه‌های_رده‌بندی_نشده' ),
	'Uncategorizedcategories'   => array( 'رده‌های_رده‌بندی_نشده' ),
	'Uncategorizedimages'       => array( 'تصویرهای_رده‌بندی_‌نشده' ),
	'Uncategorizedtemplates'    => array( 'الگوهای_رده‌بندی_نشده' ),
	'Unusedcategories'          => array( 'رده‌های_استفاده_نشده' ),
	'Unusedimages'              => array( 'تصاویر_استفاده_نشده' ),
	'Wantedpages'               => array( 'صفحه‌های_مورد_نیاز' ),
	'Wantedcategories'          => array( 'رده‌های_مورد_نیاز' ),
	'Wantedfiles'               => array( 'پرونده‌های_مورد_نیاز' ),
	'Wantedtemplates'           => array( 'الگوهای_مورد_نیاز' ),
	'Mostlinked'                => array( 'بیشترین_پیوند' ),
	'Mostlinkedcategories'      => array( 'رده_با_بیشترین_پیوند' ),
	'Mostlinkedtemplates'       => array( 'الگو_با_بیشترین_پیوند' ),
	'Mostcategories'            => array( 'بیشترین_رده' ),
	'Mostimages'                => array( 'بیشترین_تصویر' ),
	'Mostrevisions'             => array( 'بیشترین_نسخه' ),
	'Fewestrevisions'           => array( 'کمترین_نسخه' ),
	'Shortpages'                => array( 'صفحه‌های_کوتاه' ),
	'Longpages'                 => array( 'صفحه‌های_بلند' ),
	'Newpages'                  => array( 'صفحه‌های_تازه' ),
	'Ancientpages'              => array( 'صفحه‌های_قدیمی' ),
	'Deadendpages'              => array( 'صفحه‌های_بن‌بست' ),
	'Protectedpages'            => array( 'صفحه‌های_حفاظت_شده' ),
	'Protectedtitles'           => array( 'عنوان‌های_حفاظت_شده' ),
	'Allpages'                  => array( 'تمام_صفحه‌ها' ),
	'Prefixindex'               => array( 'نمایه_پیشوندی' ),
	'Ipblocklist'               => array( 'فهرست_بستن_نشانی_اینترنتی' ),
	'Specialpages'              => array( 'صفحه‌های_ویژه' ),
	'Contributions'             => array( 'مشارکت‌ها' ),
	'Emailuser'                 => array( 'نامه_به_کاربر' ),
	'Confirmemail'              => array( 'تایید_پست_الکترونیکی' ),
	'Whatlinkshere'             => array( 'پیوند_به_این_صفحه' ),
	'Recentchangeslinked'       => array( 'تغییرات_مرتبط' ),
	'Movepage'                  => array( 'انتقال_صفحه' ),
	'Blockme'                   => array( 'بستن_من' ),
	'Booksources'               => array( 'منابع_کتاب' ),
	'Categories'                => array( 'رده‌ها' ),
	'Export'                    => array( 'برون‌_بری_صفحه' ),
	'Version'                   => array( 'نسخه' ),
	'Allmessages'               => array( 'تمام_پیغام‌ها' ),
	'Log'                       => array( 'سیاهه‌ها' ),
	'Blockip'                   => array( 'بستن_نشانی_اینترنتی' ),
	'Undelete'                  => array( 'احیای_صفحهٔ_حذف‌شده' ),
	'Import'                    => array( 'درون_ریزی_صفحه' ),
	'Lockdb'                    => array( 'قفل_کردن_پایگاه_داده' ),
	'Unlockdb'                  => array( 'باز_کردن_پایگاه_داده' ),
	'Userrights'                => array( 'اختیارات_کاربر' ),
	'MIMEsearch'                => array( 'جستجوی_MIME' ),
	'FileDuplicateSearch'       => array( 'جستجوی_پرونده_تکراری' ),
	'Unwatchedpages'            => array( 'صفحه‌های_پی‌گیری_نشده' ),
	'Listredirects'             => array( 'فهرست_تغییرمسیرها' ),
	'Revisiondelete'            => array( 'حذف_نسخه' ),
	'Unusedtemplates'           => array( 'الگوهای_استفاده_نشده' ),
	'Randomredirect'            => array( 'تغییرمسیر_تصادفی' ),
	'Mypage'                    => array( 'صفحه_من' ),
	'Mytalk'                    => array( 'بحث_من' ),
	'Mycontributions'           => array( 'مشارکت‌های_من' ),
	'Listadmins'                => array( 'فهرست_مدیران' ),
	'Listbots'                  => array( 'فهرست_ربات‌ها' ),
	'Popularpages'              => array( 'صفحه‌های_محبوب' ),
	'Search'                    => array( 'جستجو' ),
	'Resetpass'                 => array( 'از_نو_کردن_گذرواژه' ),
	'Withoutinterwiki'          => array( 'بدون_میان‌ویکی' ),
	'MergeHistory'              => array( 'ادغام_تاریخچه' ),
	'Filepath'                  => array( 'مسیر_پرونده' ),
	'Invalidateemail'           => array( 'باطل_کردن_پست_الکترونیکی' ),
	'Blankpage'                 => array( 'صفحه_خالی' ),
	'LinkSearch'                => array( 'جستجوی_پیوند' ),
	'DeletedContributions'      => array( 'مشارکت‌های_حذف_شده' ),
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
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
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

$magicWords = array(
	'redirect'              => array( '0', '#تغییرمسیر', '#REDIRECT' ),
	'notoc'                 => array( '0', '__بی‌فهرست__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__بی‌نگارخانه__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__بافهرست__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__فهرست__', '__TOC__' ),
	'noeditsection'         => array( '0', '__بی‌بخش__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__بی‌عنوان__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'ماه', 'ماه‌کنونی', 'ماه_کنونی', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'نام‌ماه', 'نام_ماه', 'نام‌ماه‌کنونی', 'نام_ماه_کنونی', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'نام‌ماه‌اضافه', 'نام_ماه_اضافه', 'نام‌ماه‌کنونی‌اضافه', 'نام_ماه_کنونی_اضافه', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'مخفف‌نام‌ماه', 'مخفف_نام_ماه', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'روز', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'روز۲', 'روز_۲', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'نام‌روز', 'نام_روز', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'سال', 'سال‌کنونی', 'سال_کنونی', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'زمان', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ساعت', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'ماه‌محلی', 'ماه_محلی', 'LOCALMONTH' ),
	'localmonthname'        => array( '1', 'نام‌ماه‌محلی', 'نام_ماه_محلی', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'نام‌ماه‌محلی‌اضافه', 'نام_ماه_محلی_اضافه', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'مخفف‌ماه‌محلی', 'مخفف_ماه_محلی', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'روزمحلی', 'روز_محلی', 'LOCALDAY' ),
	'localday2'             => array( '1', 'روزمحلی۲', 'روز_محلی_۲', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'نام‌روزمحلی', 'نام_روز_محلی', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'سال‌محلی', 'سال_محلی', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'زمان‌محلی', 'زمان_محلی', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ساعت‌محلی', 'ساعت_محلی', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'تعدادصفحه‌ها', 'تعداد_صفحه‌ها', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'تعدادمقاله‌ها', 'تعداد_مقاله‌ها', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'تعدادپرونده‌ها', 'تعداد_پرونده‌ها', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'تعدادکاربران', 'تعداد_کاربران', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'تعدادویرایش‌ها', 'تعداد_ویرایش‌ها', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'تعدادبازدید', 'تعداد_بازدید', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'نام‌صفحه', 'نام_صفحه', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'نشانی‌صفحه', 'نشانی_صفحه', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'فضای‌نام', 'فضای_نام', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'نشانی‌فضای‌نام', 'نشانی_فضای_نام', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'فضای‌بحث', 'فضای_بحث', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'نشانی‌بحث', 'نشانی_بحث', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'فضای‌موضوع', 'فضای‌مقاله', 'فضای_موضوع', 'فضای_مقاله', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'نشانی‌فضای‌موضوع', 'نشانی‌فضای‌مقاله', 'نشانی_فضای_موضوع', 'نشانی_فضای_مقاله', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'نام‌کامل‌صفحه', 'نام_کامل_صفحه', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'نشانی‌کامل‌صفحه', 'نشانی_کامل_صفحه', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'نام‌زیرصفحه', 'نام_زیرصفحه', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'نشانی‌زیرصفحه', 'نشانی_زیرصفحه', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'نام‌صفحه‌مبنا', 'نام_صفحه_مبنا', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'نشانی‌صفحه‌مبنا', 'نشانی_صفحه‌مبنا', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'نام‌صفحه‌بحث', 'نام_صفحه_بحث', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'نشانی‌صفحه‌بحث', 'نشانی_صفحه_بحث', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'نام‌صفحه‌موضوع', 'نام‌صفحه‌مقاله', 'نام_صفحه_موضوع', 'نام_صفحه_مقاله', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'نشانی‌صفحه‌موضوع', 'نشانی‌صفحه‌مقاله', 'نشانی_صفحه_موضوع', 'نشانی_صفحه_مقاله', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'پیغام:', 'پ:', 'MSG:' ),
	'subst'                 => array( '0', 'جایگزین:', 'جا:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'پیغام‌بی‌بسط:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'بندانگشتی', 'انگشتدان', 'انگشتی', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'بندانگشتی=$1', 'انگشتدانهای=$1', 'انگشتی=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'راست', 'right' ),
	'img_left'              => array( '1', 'چپ', 'left' ),
	'img_none'              => array( '1', 'هیچ', 'none' ),
	'img_width'             => array( '1', '$1پیکسل', '$1px' ),
	'img_center'            => array( '1', 'وسط', 'center', 'centre' ),
	'img_framed'            => array( '1', 'قاب', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'بی‌قاب', 'بیقاب', 'frameless' ),
	'img_page'              => array( '1', 'صفحه=$1', 'صفحه $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'ایستاده', 'ایستاده=$1', 'ایستاده $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'حاشیه', 'border' ),
	'img_baseline'          => array( '1', 'همکف', 'baseline' ),
	'img_sub'               => array( '1', 'زیر', 'sub' ),
	'img_super'             => array( '1', 'زبر', 'super', 'sup' ),
	'img_top'               => array( '1', 'بالا', 'top' ),
	'img_text_top'          => array( '1', 'متن-بالا', 'text-top' ),
	'img_middle'            => array( '1', 'میانه', 'middle' ),
	'img_bottom'            => array( '1', 'پایین', 'bottom' ),
	'img_text_bottom'       => array( '1', 'متن-پایین', 'text-bottom' ),
	'img_link'              => array( '1', 'پیوند=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'جایگزین=$1', 'alt=$1' ),
	'int'                   => array( '0', 'محتوى:', 'INT:' ),
	'sitename'              => array( '1', 'نام‌وبگاه', 'نام_وبگاه', 'SITENAME' ),
	'ns'                    => array( '0', 'فن:', 'NS:' ),
	'localurl'              => array( '0', 'نشانی:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'نشانی‌نشانی:', 'LOCALURLE:' ),
	'server'                => array( '0', 'کارگذار', 'SERVER' ),
	'servername'            => array( '0', 'نام‌کارگذار', 'نام_کارگذار', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'مسیر', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'دستورزبان:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__عنوان‌تبدیل‌نشده__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__محتواتبدیل‌نشده__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'هفته', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'روزهفته', 'روز_هفته', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'هفته‌محلی', 'هفته_محلی', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'روزهفته‌محلی', 'روز_هفته_محلی', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'نسخه', 'شماره‌نسخه', 'شماره_نسخه', 'REVISIONID' ),
	'revisionday'           => array( '1', 'روزنسخه', 'روز_نسخه', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'روزنسخه۲', 'روز_نسخه۲', 'روز_نسخه_۲', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'ماه‌نسخه', 'ماه_نسخه', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'سال‌نسخه', 'سال_نسخه', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'نشان‌زمان‌نسخه', 'نشان_زمان_نسخه', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'جمع:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'نشانی‌کامل:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'نشانی‌نشانی‌کامل:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'ابتداکوچک:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'ابتدابزرگ:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'ک:', 'LC:' ),
	'uc'                    => array( '0', 'ب:', 'UC:' ),
	'raw'                   => array( '0', 'خام:', 'RAW:' ),
	'displaytitle'          => array( '1', 'عنوان‌ظاهری', 'عنوان_ظاهری', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'ع', 'R' ),
	'newsectionlink'        => array( '1', '__بخش‌جدید__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'نسخه‌کنونی', 'نسخه_کنونی', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'کدنشانی:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'کدلنگر:', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'نشان‌زمان‌کنونی', 'نشان_زمان_کنونی', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'نشان‌زمان‌محلی', 'نشان_زمان_محلی', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'علامت‌جهت', 'علامت_جهت', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#زبان:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'زبان‌محتوا', 'زبان_محتوا', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'صفحه‌درفضای‌نام', 'صفحه_در_فضای_نام', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'تعدادمدیران', 'تعداد_مدیران', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'آرایش‌عدد', 'آرایش_عدد', 'FORMATNUM' ),
	'padleft'               => array( '0', 'لبه‌چپ', 'لبه_چپ', 'PADLEFT' ),
	'padright'              => array( '0', 'لبه‌راست', 'لبه_راست', 'PADRIGHT' ),
	'special'               => array( '0', 'ویژه', 'special' ),
	'defaultsort'           => array( '1', 'ترتیب', 'ترتیب‌پیش‌فرض', 'ترتیب_پیش_فرض', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'مسیرپرونده:', 'مسیر_پرونده', 'FILEPATH:' ),
	'tag'                   => array( '0', 'برچسب', 'tag' ),
	'hiddencat'             => array( '1', '__رده‌پنهان__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'صفحه‌دررده', 'صفحه_در_رده', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'اندازه‌صفحه', 'اندازه_صفحه', 'PAGESIZE' ),
	'index'                 => array( '1', '__نمایه__', '__INDEX__' ),
	'noindex'               => array( '1', '__بی‌نمایه__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'تعداددرگروه', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__تغییرمسیرثابت__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'سطح‌حغاطت', 'سطح_حفاظت', 'PROTECTIONLEVEL' ),
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
'tog-underline'               => 'زیر پیوندها خط کشیده شود',
'tog-highlightbroken'         => 'قالب‌بندی پیوندهای ناقص <a href="" class="new">به این شکل</a> (امکان دیگر: به این شکل<a href="" class="internal">؟</a>).',
'tog-justify'                 => 'تمام‌چین‌کردن بندها',
'tog-hideminor'               => 'نشان ندادن تغییرات جزئی در فهرست تغییرات اخیر',
'tog-hidepatrolled'           => 'نهفتن ویرایش‌های گشت خورده در تغییرات اخیر',
'tog-newpageshidepatrolled'   => 'نهفتن صفحه‌های گشت خورده از فهرست صفحه‌های تازه',
'tog-extendwatchlist'         => 'گسترش فهرست پی‌گیری‌ها برای نشان‌دادن همهٔ تغییرات مربوط.',
'tog-usenewrc'                => 'تغییرات اخیر گسترش‌یافته (برای هر مرورگری نیست)',
'tog-numberheadings'          => 'شماره‌گذاری خودکار عناوین',
'tog-showtoolbar'             => 'نمایش نوار ابزار جعبهٔ ویرایش',
'tog-editondblclick'          => 'ویرایش صفحه‌ها با دوکلیک (جاوااسکریپت)',
'tog-editsection'             => 'به کار انداختن ویرایش قسمت‌ها از طریق پیوندهای [ویرایش]',
'tog-editsectiononrightclick' => 'به کار انداختن ویرایش قسمت‌ها با کلیک راست<br />روی عناوین قسمت‌ها (جاوااسکریپت)',
'tog-showtoc'                 => 'نمایش فهرست مندرجات<br />(برای مقاله‌های با بیش از ۳ سرفصل)',
'tog-rememberpassword'        => 'گذرواژه برای نشست‌های بعدی بماند',
'tog-editwidth'               => 'عرض جعبهٔ ویرایش کامل باشد',
'tog-watchcreations'          => 'افزودن صفحه‌های ایجادشده توسط من به فهرست پی‌گیری‌ها.',
'tog-watchdefault'            => 'افزودن صفحه‌هایی که ویرایش می‌کنم به فهرست پی‌گیری‌ها',
'tog-watchmoves'              => 'افزودن صفحه‌هایی که منتقل می‌کنم به فهرست پی‌گیری‌ها',
'tog-watchdeletion'           => 'افزودن صفحه‌هایی که حذف می‌کنم به فهرست پی‌گیری‌های من',
'tog-minordefault'            => 'پیش‌فرض همهٔ ویرایش‌ها «جزئی» باشد',
'tog-previewontop'            => 'نمایش پیش‌نمایش قبل از جعبهٔ ویرایش و نه پس از آن',
'tog-previewonfirst'          => 'پیش‌نمایش هنگام اولین ویرایش',
'tog-nocache'                 => 'از کار انداختن حافظهٔ نهانی صفحه‌ها',
'tog-enotifwatchlistpages'    => 'اگر صفحه‌ای که پی‌گیری می‌کنم تغییر کرد به من ایمیل بزن.',
'tog-enotifusertalkpages'     => 'هنگامی که در صفحهٔ بحث کاربری‌ام تغییری صورت می‌گیرد به من ایمیل بزن.',
'tog-enotifminoredits'        => 'برای تغییرات جزئی در صفحه‌ها هم به من ایمیل بزن.',
'tog-enotifrevealaddr'        => 'نشانی پست الکترونیکی من در نامه‌های اطلاع‌رسانی قید شود',
'tog-shownumberswatching'     => 'نشان‌دادن شمار کاربران پی‌گیری‌کننده',
'tog-fancysig'                => 'امضای خام (بدون درج خودکار پیوند)',
'tog-externaleditor'          => 'به‌طور پیش‌فرض از ویرایشگر خارجی استفاده شود',
'tog-externaldiff'            => 'استفاده از تفاوت‌گیر (diff) خارجی به‌طور پیش‌فرض.',
'tog-showjumplinks'           => 'نمایش پیوندهای پرشی در فهرست مندرجات',
'tog-uselivepreview'          => 'استفاده از پیش‌نمایش زنده (جاوااسکریپت) (آزمایشی)',
'tog-forceeditsummary'        => 'هنگامی که خلاصهٔ ویرایش ننوشته‌ام به من اطلاع بده',
'tog-watchlisthideown'        => 'مخفی‌کردن ویرایش‌های من در فهرست پی‌گیری‌ها',
'tog-watchlisthidebots'       => 'مخفی‌کردن ویرایش‌های ربات‌ها در فهرست پی‌گیری‌ها',
'tog-watchlisthideminor'      => 'نهفتن ویرایش‌های جزئی از فهرست پی‌گیری‌های من',
'tog-watchlisthideliu'        => 'ویرایش‌های کاربران وارد شده به سیستم را از فهرست پی‌گیری‌های من پنهان کن',
'tog-watchlisthideanons'      => 'ویرایش‌های کاربران ناشناس را از فهرست پی‌گیری‌های من پنهان کن',
'tog-watchlisthidepatrolled'  => 'نهفتن ویرایش‌های گشت خورده از فهرست پیگیری',
'tog-nolangconversion'        => 'غیرفعال کردن تبدیل زبان‌ها',
'tog-ccmeonemails'            => 'فرستادن رونوشت نامه‌های الکترونیکی که به دیگران ارسال می‌کنم به خودم.',
'tog-diffonly'                => 'محتوای صفحه، زیر تفاوت نمایش داده نشود',
'tog-showhiddencats'          => 'رده‌های پنهان را نمایش بده',
'tog-norollbackdiff'          => 'بعد از واگردانی تفاوت را نشان نده',

'underline-always'  => 'همیشه',
'underline-never'   => 'هرگز',
'underline-default' => 'پیش‌فرض مرورگر',

# Dates
'sunday'        => 'یک‌شنبه',
'monday'        => 'دوشنبه',
'tuesday'       => 'سه‌شنبه',
'wednesday'     => 'چهارشنبه',
'thursday'      => 'پنجشنبه',
'friday'        => 'جمعه',
'saturday'      => 'شنبه',
'sun'           => 'یکشنبه',
'mon'           => 'دوشنبه',
'tue'           => 'سه‌شنبه',
'wed'           => 'چهارشنبه',
'thu'           => 'پنجشنبه',
'fri'           => 'جمعه',
'sat'           => 'شنبه',
'january'       => 'ژانویه',
'february'      => 'فوریه',
'march'         => 'مارس',
'april'         => 'آوریل',
'may_long'      => 'مه',
'june'          => 'ژوئن',
'july'          => 'ژوئیه',
'august'        => 'اوت',
'september'     => 'سپتامبر',
'october'       => 'اکتبر',
'november'      => 'نوامبر',
'december'      => 'دسامبر',
'january-gen'   => 'ژانویهٔ',
'february-gen'  => 'فوریهٔ',
'march-gen'     => 'مارس',
'april-gen'     => 'آوریل',
'may-gen'       => 'مهٔ',
'june-gen'      => 'ژوئن',
'july-gen'      => 'ژوئیهٔ',
'august-gen'    => 'اوت',
'september-gen' => 'سپتامبر',
'october-gen'   => 'اکتبر',
'november-gen'  => 'نوامبر',
'december-gen'  => 'دسامبر',
'jan'           => 'ژانویه',
'feb'           => 'فوریه',
'mar'           => 'مارس',
'apr'           => 'آوریل',
'may'           => 'مه',
'jun'           => 'ژوئن',
'jul'           => 'ژوئیه',
'aug'           => 'اوت',
'sep'           => 'سپتامبر',
'oct'           => 'اکتبر',
'nov'           => 'نوامبر',
'dec'           => 'دسامبر',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|رده‌های صفحه|رده‌های صفحه}}',
'category_header'                => 'مقاله‌های ردهٔ «$1»',
'subcategories'                  => 'زیررده‌ها',
'category-media-header'          => 'پرونده‌های ردهٔ «$1»',
'category-empty'                 => 'این رده شامل هیچ صفحه یا پرونده‌ای نمی‌شود.',
'hidden-categories'              => '{{PLURAL:$1|ردهٔ پنهان|رده‌های پنهان}}',
'hidden-category-category'       => 'رده‌های پنهان', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|این رده تنها حاوی زیرردهٔ زیر است.|{{PLURAL:$1|این زیررده|این $1 زیررده}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 زیررده است.}}',
'category-subcat-count-limited'  => 'این رده شامل {{PLURAL:$1|یک زیررده|$1 زیررده}} زیر می‌باشد.',
'category-article-count'         => '{{PLURAL:$2|این رده تنها حاوی صفحهٔ زیر است.|{{PLURAL:$1|این صفحه|این $1 صفحه}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 صفحه است.}}',
'category-article-count-limited' => '{{PLURAL:$1|صفحهٔ|$1 صفحهٔ}} زیر در ردهٔ فعلی قرار دارند.',
'category-file-count'            => '{{PLURAL:$2|این رده تنها حاوی پروندهٔ زیر است.|{{PLURAL:$1|این پرونده|این $1 پرونده}} در این رده قرار {{PLURAL:$1|دارد|دارند}}؛ این رده در کل حاوی $2 پرونده است.}}',
'category-file-count-limited'    => '{{PLURAL:$1|پروندهٔ|$1 پروندهٔ}} زیر در ردهٔ فعلی قرار دارند.',
'listingcontinuesabbrev'         => '(ادامه)',

'mainpagetext'      => 'نرم‌افزار ویکی با موفقیت نصب شد.',
'mainpagedocfooter' => 'از [http://meta.wikimedia.org/wiki/Help:Contents راهنمای کاربران]
برای استفاده از نرم‌افزار ویکی کمک بگیرید.

== آغاز به کار ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings تنظیم پیکربندی]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki پرسش‌های متداول]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce فهرست رایانامه نسخه‌های مدیاویکی]',

'about'          => 'درباره',
'article'        => 'صفحهٔ محتوا',
'newwindow'      => '(در پنجرهٔ جدید باز می‌شود)',
'cancel'         => 'لغو',
'qbfind'         => 'یافتن',
'qbbrowse'       => 'مرور',
'qbedit'         => 'ویرایش',
'qbpageoptions'  => 'این صفحه',
'qbpageinfo'     => 'بافت',
'qbmyoptions'    => 'صفحه‌های من',
'qbspecialpages' => 'صفحه‌های ویژه',
'moredotdotdot'  => 'بیشتر...',
'mypage'         => 'صفحهٔ من',
'mytalk'         => 'بحث من',
'anontalk'       => 'بحث برای این IP',
'navigation'     => 'گشتن',
'and'            => '&#32;و',

# Metadata in edit box
'metadata_help' => 'متاداده:',

'errorpagetitle'    => 'خطا',
'returnto'          => 'بازگشت به $1.',
'tagline'           => 'از {{SITENAME}}',
'help'              => 'راهنما',
'search'            => 'جستجو',
'searchbutton'      => 'جستجو کن',
'go'                => 'برو',
'searcharticle'     => 'برو',
'history'           => 'تاریخچهٔ صفحه',
'history_short'     => 'تاریخچه',
'updatedmarker'     => 'به‌روزشده از پس از آخرین باری که سرزده‌ام.',
'info_short'        => 'اطلاعات',
'printableversion'  => 'نسخهٔ قابل چاپ',
'permalink'         => 'پیوند دائمی',
'print'             => 'چاپ',
'edit'              => 'ويرايش',
'create'            => 'ایجاد',
'editthispage'      => 'اين صفحه را ويرايش كنيد',
'create-this-page'  => 'این صفحه را ایجاد کنید',
'delete'            => 'حذف',
'deletethispage'    => 'حذف این صفحه',
'undelete_short'    => 'احیای {{PLURAL:$1|یک ویرایش|$1 ویرایش}}',
'protect'           => 'محافظت',
'protect_change'    => 'تغییر',
'protectthispage'   => 'محافظت از این صفحه',
'unprotect'         => 'به‌درآوردن از محافظت',
'unprotectthispage' => 'از محافظت در آوردن این صفحه',
'newpage'           => 'صفحهٔ جدید',
'talkpage'          => 'بحث دربارهٔ این صفحه',
'talkpagelinktext'  => 'بحث',
'specialpage'       => 'صفحهٔ ویژه',
'personaltools'     => 'ابزارهای شخصی',
'postcomment'       => 'بخش جدید',
'articlepage'       => 'نمایش مقاله',
'talk'              => 'بحث',
'views'             => 'بازدیدها',
'toolbox'           => 'جعبه‌ابزار',
'userpage'          => 'نمایش صفحهٔ کاربر',
'projectpage'       => 'دیدن صفحهٔ پروژه',
'imagepage'         => 'نمایش صفحهٔ پرونده',
'mediawikipage'     => 'نمایش صفحهٔ پیغام',
'templatepage'      => 'نمایش صفحهٔ الگو',
'viewhelppage'      => 'نمایش صفحهٔ راهنما',
'categorypage'      => 'نمایش صفحهٔ رده',
'viewtalkpage'      => 'نمایش صفحهٔ بحث',
'otherlanguages'    => 'زبان‌های دیگر',
'redirectedfrom'    => '(تغییر مسیر از $1)',
'redirectpagesub'   => 'صفحهٔ تغییر مسیر',
'lastmodifiedat'    => 'این صفحه آخرین بار در $2، $1 تغییر یافته‌است.', # $1 date, $2 time
'viewcount'         => 'این صفحه {{PLURAL:$1|یک|$1}} بار دیده شده است.',
'protectedpage'     => 'صفحهٔ محافظت‌شده',
'jumpto'            => 'پرش به:',
'jumptonavigation'  => 'ناوبری',
'jumptosearch'      => 'جستجو',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'دربارهٔ {{SITENAME}}',
'aboutpage'            => 'Project:درباره',
'copyright'            => 'محتوا تحت اجازه‌نامهٔ $1 در دسترس است.',
'copyrightpagename'    => 'حق تکثیر {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:حق_تکثیر',
'currentevents'        => 'وقایع کنونی',
'currentevents-url'    => 'Project:وقایع کنونی',
'disclaimers'          => 'تکذیب‌نامه‌ها',
'disclaimerpage'       => 'Project:تکذیب‌نامهٔ عمومی',
'edithelp'             => 'راهنمای ویرایش کردن',
'edithelppage'         => 'Help:چگونه صفحه‌ها را ویرایش کنیم',
'faq'                  => 'سوال‌های متداول',
'faqpage'              => 'Project:سوال‌های متداول',
'helppage'             => 'Help:راهنما',
'mainpage'             => 'صفحهٔ اصلی',
'mainpage-description' => 'صفحهٔ اصلی',
'policy-url'           => 'Project:سیاست‌ها',
'portal'               => 'ورودی کاربران',
'portal-url'           => 'Project:ورودی کاربران',
'privacy'              => 'سیاست حفظ اسرار',
'privacypage'          => 'Project:سیاست_حفظ_اسرار',

'badaccess'        => 'خطای دسترسی',
'badaccess-group0' => 'شما اجازهٔ اجرای عمل درخواسته را ندارید.',
'badaccess-groups' => 'عملی که درخواست کرده‌اید منحصر به کاربران {{PLURAL:$2|این گروه|این گروه‌ها}} است: $1.',

'versionrequired'     => 'نسخهٔ $1 از نرم‌افزار مدیاویکی لازم است',
'versionrequiredtext' => 'برای دیدن این صفحه به نسخهٔ $1 از نرم‌افزار مدیاویکی نیاز دارید. برای اطلاع از نسخهٔ نرم‌افزار نصب شده در این ویکی به [[Special:Version|این صفحه]] مراجعه کنید.',

'ok'                      => 'باشد',
'retrievedfrom'           => 'برگرفته از «$1»',
'youhavenewmessages'      => '$1 دارید ($2).',
'newmessageslink'         => 'پیامهای جدیدی',
'newmessagesdifflink'     => 'تفاوت با نسخهٔ پیش از آخر',
'youhavenewmessagesmulti' => 'پیغامهای جدیدی در $1 دارید.',
'editsection'             => 'ویرایش',
'editold'                 => 'ویرایش',
'viewsourceold'           => 'مشاهدهٔ منبع',
'editlink'                => 'ویرایش',
'viewsourcelink'          => 'نمایش مبدأ',
'editsectionhint'         => 'ویرایش بخش: $1',
'toc'                     => 'فهرست مندرجات',
'showtoc'                 => 'نمایش داده شود',
'hidetoc'                 => 'مخفی شود',
'thisisdeleted'           => 'نمایش یا احیای $1؟',
'viewdeleted'             => 'نمایش $1؟',
'restorelink'             => '{{PLURAL:$1|$1|$1}} ویرایش حذف‌شده',
'feedlinks'               => 'خبرخوان:',
'feed-invalid'            => 'اشکال در آبونمان خبرخوان',
'feed-unavailable'        => 'خبرخوان‌ها قابل استفاده نیستند',
'site-rss-feed'           => 'خبرخوان RSS برای $1',
'site-atom-feed'          => 'خبرخوان Atom برای $1',
'page-rss-feed'           => 'خبرخوان RSS برای «$1»',
'page-atom-feed'          => 'خبرخوان Atom برای «$1»',
'red-link-title'          => '$1 (صفحه وجود ندارد)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'مقاله',
'nstab-user'      => 'صفحهٔ کاربر',
'nstab-media'     => 'رسانه',
'nstab-special'   => 'صفحهٔ ویژه',
'nstab-project'   => 'صفحهٔ پروژه',
'nstab-image'     => 'تصویر',
'nstab-mediawiki' => 'پیغام',
'nstab-template'  => 'الگو',
'nstab-help'      => 'راهنما',
'nstab-category'  => 'رده',

# Main script and global functions
'nosuchaction'      => 'چنین عملی وجود ندارد',
'nosuchactiontext'  => 'ویکی عمل مشخص شده در URL را نمی‌شناسد',
'nosuchspecialpage' => 'چنین صفحهٔ ویژه‌ای وجود ندارد',
'nospecialpagetext' => 'شما صفحهٔ ویژه‌ای را درخواست کرده‌اید که ویکی نمی‌شناسد.',

# General errors
'error'                => 'خطا',
'databaseerror'        => 'خطای پایگاه داده',
'dberrortext'          => 'اشکالی در دستور فرستاده شده به پایگاه داده رخ داد.
علت این مساله می‌توانید ایرادی در نرم‌افزار مدیاویکی باشد.
آخرین دستوری که برای پایگاه داده فرستاد شد این بود:
<div dir="ltr"><blockquote><tt>$1</tt></blockquote></div>
این دستور از درون عملگر <span dir=ltr><tt>$2</tt></span> فرستاده شد
پایگاه داده این خطا را باز گرداند:
<div dir="ltr"><tt>$3: $4</tt></div>',
'dberrortextcl'        => 'اشکالی در دستور فرستاده شده به پایگاه داده رخ داد.
علت این مساله می‌توانید ایرادی در نرم‌افزار مدیاویکی باشد.
آخرین دستوری که برای پایگاه داده فرستاد شد این بود:
<div dir="ltr">$1</div>
این دستور از درون عملگر <span dir=ltr>$2</span> فرستاده شد
پایگاه داده این خطا را باز گرداند:
<div dir="ltr">$3: $4</div>',
'noconnect'            => 'شرمنده! ویکی ایراد فنی دارد و نمی‌تواند با کارگزار پایگاه داده تماس بگیرد.<br />
$1',
'nodb'                 => 'نمی‌توان پایگاه دادهٔ $1 را انتخاب کرد',
'cachederror'          => 'در زیر یک نسخهٔ بایگانی‌شدهٔ صفحهٔ درخواستی می‌آید، و ممکن است به‌روز نباشد.',
'laggedslavemode'      => 'هشدار: صفحه ممکن است به‌روزرسانی‌های اخیر را شامل نگردد.',
'readonly'             => 'پایگاه داده قفل شد',
'enterlockreason'      => 'دلیلی برای قفل کردن ذکر کنید، و تقریبی از زمانی که قفل برداشته خواهد شد در آن بیاورید',
'readonlytext'         => 'پایگاه داده در حال حاضر در برابر تغییرات و ایجاد مداخل قفل شده است. احتمالاً علت آن بهینه‌سازی و رسیدگی‌های معمول است که بعد از انجام آن وضع به حالت عادی باز خواهد گشت. توضیح مدیری که آن را قفل کرده است بدین شرح است: 
<p>$1',
'missing-article'      => 'پایگاه داده متن صفحه‌ای به نام «$1» $2 را که باید می‌یافت، نیافت.

این مشکل معمولاً بر اثر ادامه دادن پیوندهای تاریخ‌گذشتهٔ تفاوت یا تاریخچهٔ صفحه‌هایی رخ می‌دهد که حذف شده‌اند.

اگر مورد شما این نیست، ممکن است اشکالی در نرم‌افزار پیدا کرده باشید.
لطفاً این مسئله را، با ذکر URL، به یکی از مدیران گزارش کنید.',
'missingarticle-rev'   => '(نسخهٔ شماره: $1)',
'missingarticle-diff'  => '(تفاوت: $1، $2)',
'readonly_lag'         => 'پایگاه داده به طور خودکار قفل شده‌است تا نسخه‌های پشتیبان با نسخهٔ اصلی هماهنگ شوند.',
'internalerror'        => 'خطای داخلی',
'internalerror_info'   => 'خطای داخلی: $1',
'filecopyerror'        => 'نتوانستم از پروندهٔ «$1» روی «$2» نسخه‌برداری کنم.',
'filerenameerror'      => 'نتوانستم پروندهٔ «$1» را به «$2» تغییر نام دهم.',
'filedeleteerror'      => 'نتوانستم پروندهٔ «$1» را حذف کنم',
'directorycreateerror' => 'امکان ایجاد پوشه $1 وجود ندارد.',
'filenotfound'         => 'پروندهٔ «$1» یافت نشد.',
'fileexistserror'      => 'امکان نوشتن روی پرونده $1 وجود ندارد: پرونده از قبل وجود دارد.',
'unexpected'           => 'مقدار غیرمنتظره: «$1»=«$2».',
'formerror'            => 'خطا: نمی‌توان فرم را فرستاد',
'badarticleerror'      => 'نمی‌توان این عمل را بر این صفحه انجام داد.',
'cannotdelete'         => 'نشد صفحه یا تصویر مشخص‌شده را حذف کرد. (ممکن است قبلاً کس دیگری آن را حذف کرده باشد.)',
'badtitle'             => 'عنوان بد',
'badtitletext'         => 'عنوان درخواستی نامعتبر، خالی، یا عنوانی بین زبانی یا بین‌ویکی‌ای با پیوند نادرست بود.',
'perfcached'           => 'داده‌های زیر از حافظهٔ موقت فراخوانی شده‌اند و ممکن است کاملاً به‌روز نباشند:',
'perfcachedts'         => 'داده‌های زیر از حافظهٔ موقت فراخوانی شده‌اند و آخرین به‌روزرسانی $1 است',
'querypage-no-updates' => 'امکان به روز رسانی این صفحه فعلاً غیرفعال شده‌است.',
'wrong_wfQuery_params' => 'پارامترهای wfQuery() نادرست است<br />
تابع: $1<br />
پرس‌وجو: $2',
'viewsource'           => 'نمایش مبدأ',
'viewsourcefor'        => 'برای $1',
'actionthrottled'      => 'جلوی عمل شما گرفته شد',
'actionthrottledtext'  => 'به منظور جلوگیری از هرزنگاری، شما اجازه ندارید که چنین عملی را بیش از چند بار در یک مدت زمان کوتاه انجام بدهید.
لطفاً پس از چند دقیقه دوباره تلاش کنید.',
'protectedpagetext'    => 'این صفحه برای جلوگیری از ویرایش قفل شده‌است.',
'viewsourcetext'       => 'می‌توانید متن مبدأ این صفحه را مشاهده کنید یا از آن نسخه بردارید',
'protectedinterface'   => 'این صفحه ارائه‌دهندهٔ متنی برای رابط کاربر این نرم‌افزار است و به منظور پیشگیری از خرابکاری قفل شده‌است.',
'editinginterface'     => "'''هشدار:''' شما صفحه‌ای را ویرایش می‌کنید که شامل  متنی به‌کاررفته در رابط کاربر این نرم‌افزار است. تغییر این صفحه منجر به تغییر ظاهر رابط کاربر این نرم‌افزار برای دیگر کاربران خواهد شد.",
'sqlhidden'            => '(دستور SQL مخفی‌شده)',
'cascadeprotected'     => 'این صفحه در مقابل ویرایش محافظت شده‌است برای اینکه در {{PLURAL:$1|صفحهٔ|صفحه‌های}} محافظت‌شدهٔ زیر که گزینهٔ «آبشاری» در {{PLURAL:$1|آن|آنها}} انتخاب شده‌است، قرار گرفته‌است:
$2',
'namespaceprotected'   => "شما اجازهٔ ویرایش صفحه‌های فضای نام '''$1''' را ندارید.",
'customcssjsprotected' => 'شما اجازهٔ ویرایش این صفحه را ندارید، چرا که حاوی تنظیم‌های شخصی یک کاربر دیگر است.',
'ns-specialprotected'  => 'صفحه‌های فضای نام {{ns:special}} غیر قابل ویرایش هستند.',
'titleprotected'       => 'از ایجاد صفحه‌ای با این عنوان توسط [[User:$1|$1]] جلوگیری شده‌است. دلیل ذکر شده از این قرار است: <i>$2</i>.',

# Virus scanner
'virus-badscanner'     => 'تنظیمات بد: پویشگر ویروس ناشناخته: <i>$1</i>',
'virus-scanfailed'     => 'پویش ناموفق (کد $1)',
'virus-unknownscanner' => 'ضدویروس ناشناخته:',

# Login and logout pages
'logouttitle'                => 'خروج کاربر از سیستم',
'logouttext'                 => 'اکنون از سیستم خارج شدید. شما می‌توانید به استفادهٔ گمنام از {{SITENAME}} ادامه دهید، یا می‌توانید با همین کاربر یا کاربر دیگری به سیستم وارد شوید. توجه کنید که تا زمانی که cache مرورگرتان را پاک کنید،
بعضی صفحه‌ها ممکن است به شکلی نمایش یابند که انگار هنوز وارد سیستم هستید.',
'welcomecreation'            => '<h2>$1، خوش آمدید!</h2><p>حساب شما
ایجاد شد.
فراموش نکنید که ترجیحات {{SITENAME}} خود را
تنظیم کنید.',
'loginpagetitle'             => 'ورود کاربر به سیستم',
'yourname'                   => 'نام کاربری شما',
'yourpassword'               => 'گذرواژهٔ شما',
'yourpasswordagain'          => 'گذرواژه را دوباره وارد کنید',
'remembermypassword'         => 'گذرواژه را به یاد بسپار.',
'yourdomainname'             => 'دامنهٔ شما',
'externaldberror'            => 'خطایی در ارتباط با پایگاه داده رخ داده‌است یا این که شما اجازه به روز رسانی حساب بیرونی خود را ندارید.',
'login'                      => 'ورود به سیستم',
'nav-login-createaccount'    => 'ورود به سیستم / ایجاد حساب کاربری',
'loginprompt'                => 'برای ورود به {{SITENAME}} باید کوکی‌ها را فعال کنید.',
'userlogin'                  => 'ورود به سیستم / ایجاد حساب کاربری',
'logout'                     => 'خروج از سیستم',
'userlogout'                 => 'خروج از سیستم',
'notloggedin'                => 'به سیستم وارد نشده‌اید',
'nologin'                    => 'نام کاربری ندارید؟ $1.',
'nologinlink'                => 'یک حساب جدید بسازید',
'createaccount'              => 'ایجاد حساب جدید',
'gotaccount'                 => 'حساب کاربری دارید؟ $1.',
'gotaccountlink'             => 'وارد شوید',
'createaccountmail'          => 'با پست الکترونیکی',
'badretype'                  => 'گذرواژه‌هایی که وارد کرده‌اید یکسان نیستند.',
'userexists'                 => 'نام کاربری‌ای که وارد کردید قبلاً استفاده شده‌است.
لطفاً یک نام دیگر انتخاب کنید.',
'youremail'                  => 'پست الکترونیکی شما*',
'username'                   => 'نام کاربری:',
'uid'                        => 'شمارهٔ کاربری:',
'prefs-memberingroups'       => 'عضویت در {{PLURAL:$1|گروه|گروه‌ها}}:',
'yourrealname'               => 'نام واقعی:',
'yourlanguage'               => 'زبان:',
'yourvariant'                => 'گویش:',
'yournick'                   => 'امضا:',
'badsig'                     => 'امضای خام غیرمجاز؛ لطفاً برچسب‌های HTML را بررسی کنید.',
'badsiglength'               => 'امضای شما بیش از اندازه طولانی است.
امضا باید کمتر از $1 {{PLURAL:$1|نویسه|نویسه}} طول داشته باشد.',
'yourgender'                 => 'جنسیت:',
'gender-unknown'             => 'مشخص نشده',
'gender-male'                => 'مذکر',
'gender-female'              => 'مونث',
'email'                      => 'پست الکترونیکی',
'prefs-help-realname'        => '*نام واقعی (اختیاری): اگر تصمیم به ذکر آن بگیرید هنگام ارجاع به آثارتان و انتساب آنها به شما از نام واقعی‌تان استفاده خواهد شد.',
'loginerror'                 => 'خطا در ورود به سیستم',
'prefs-help-email'           => '* نشانی پست الکترونیکی اختیاری است اما ارسال یک گذرواژه جدید در صورتی که گذرواژه خود را فراموش کردید ممکن می‌سازد.
شما هم‌چنین می‌توانید انتخاب کنید که کاربران از طریق صفحهٔ کاربری یا صفحهٔ بحث کاربری، بدون فاش شدن هویت‌‌ و نشانی واقعی پست الکترونیک‌تان، با شما تماس بگیرند.',
'prefs-help-email-required'  => 'نشانی پست الکترونیکی الزامی است.',
'nocookiesnew'               => 'حساب کاربری ایجاد شد، اما شما وارد سامانه نشدید. {{SITENAME}} برای ورود کاربران به سامانه از cookie استفاده می‌کند. شما cookieها را از کار انداخته‌اید. لطفاً cookieها را به کار بیندازید، و سپس با نام کاربری و گذرواژهٔ جدیدتان به سیستم وارد شوید.',
'nocookieslogin'             => '{{SITENAME}} برای ورود کاربران به سیستم از cookie استفاده می‌کند. شما cookieها را از کار
انداخته‌اید. لطفاً cookieها را به کار بیندازید و دوباره تلاش کنید.',
'noname'                     => 'شما نام کاربری معتبری مشخص نکرده‌اید.',
'loginsuccesstitle'          => 'ورود موفقیت‌آمیز به سیستم',
'loginsuccess'               => 'شما اکنون با نام «$1» به {{SITENAME}} وارد شده‌اید.',
'nosuchuser'                 => 'کاربری با نام «$1» وجود ندارد.
نام‌های کاربری به بزرگی و کوچکی حروف حساس هستند.
املای نام را بررسی کنید، یا [[Special:UserLogin/signup|یک حساب کاربری جدید بسازید]].',
'nosuchusershort'            => "هیچ کاربری با نام ''<nowiki>$1</nowiki>'' وجود ندارد. املایتان را وارسی کنید.",
'nouserspecified'            => 'باید یک نام کاربری مشخص کنید.',
'wrongpassword'              => 'گذرواژه‌ای که وارد کردید نادرست است. لطفاً دوباره تلاش کنید.',
'wrongpasswordempty'         => 'گذرواژه‌ای که وارد کرده‌اید، خالی است. خواهشمندیم دوباره تلاش کنید.',
'passwordtooshort'           => 'گذرواژه‌ای که وارد کرده‌اید خیلی کوتاه است.
گذرواژه باید دست‌کم {{PLURAL:$1|$1 حرف|$1 حرف}} داشته باشد.',
'mailmypassword'             => 'گذرواژهٔ جدید فرستاده شود',
'passwordremindertitle'      => 'یادآور گذرواژهٔ {{SITENAME}}',
'passwordremindertext'       => 'یک نفر (احتمالاً خود شما، با نشانی اینترنتی $1) گذرواژهٔ جدیدی برای  حساب کاربری‌ شما در {{SITENAME}} درخواست کرده‌است ($4). یک گذرواژهٔ موقت برای کاربر «$2» ساخته شده و برابر با «$3» قرار داده شده‌است. اگر هدف شما همین بوده‌است، شما باید اکنون به سیستم وارد شوید و گذرواژهٔ جدیدی برگزینید. گذرواژهٔ موقت شما ظرف {{PLURAL:$5|یک روز|$5 روز}} باطل می‌شود.

اگر کس دیگری این درخواست را کرده‌است یا اینکه شما گذرواژهٔ پیشین خود را به یاد آورده‌اید و دیگر تمایل به تغییر آن ندارید، می‌توانید این پیغام را نادیده بگیرید و همان گذرواژهٔ پیشین را به کار برید.',
'noemail'                    => 'هیچ نشانی پست الکترونیکی‌ای برای کاربر «$1» ثبت نشده است.',
'passwordsent'               => 'یک گذرواژهٔ جدید به نشانی الکترونیکی ثبت شده برای کاربر «$1» فرستاده شد.
لطفاً پس از دریافت آن دوباره به سامانه وارد شوید.',
'blocked-mailpassword'       => 'نشانی اینترنتی شما از ویرایش بازداشته شده‌است و از این رو به منظور جلوگیری از سوءاستفاده اجازهٔ بهره‌گیری از قابلیت بازیافت گذرواژه را ندارد.',
'eauthentsent'               => 'یک نامهٔ الکترونیکی برای تایید نشانی پست الکترونیکی به نشانی مورنظر ارسال شد. قبل از اینکه نامهٔ دیگری قابل ارسال به این نشانی باشد، باید دستورهای که در آن نامه آمده است را جهت تأیید این مساله که این نشانی متعلق به شماست، اجرا کنید.',
'throttled-mailpassword'     => 'یک یادآور گذرواژه در $1 {{PLURAL:$1|ساعت|ساعت}} گذشته برای شما فرستاده شده‌است.
برای جلوگیری از سوءاستفاده هر  $1 ساعت تنها یک یادآوری فرستاده می‌شود.',
'mailerror'                  => 'خطا در فرستادن نامهٔ الکترونیکی : $1',
'acct_creation_throttle_hit' => 'بازدیدکنندگان این ویکی که از نشانی اینترنتی شما استفاده می‌کنند در روز گذشته {{PLURAL:$1|یک حساب کاربری|$1 حساب کاربری}} ساخته‌اند، که بیشترین تعداد مجاز در آن بازهٔ زمانی است.
به همین خاطر، بازدیدکنندگانی که از این نشانی اینترنتی استفاده می‌کنند نمی‌توانند در حال حاضر حساب جدیدی بسازند.',
'emailauthenticated'         => 'نشانی پست الکترونیکی شما در $2 ساعت $3 تصدیق شد.',
'emailnotauthenticated'      => 'نشانی پست الکترونیکی شما <strong>هنوز تصدیق نشده است.</strong> هیچ نامهٔ الکترونیکی‌ای برای هر یک از ویژگی‌های زیر ارسال نخواهد شد.',
'noemailprefs'               => 'برای راه‌اندازی این قابلیت‌ها یک نشانی پست الکترونیکی مشخص کنید.',
'emailconfirmlink'           => 'نشانی پست الکترونیکی خود را تأیید کنید',
'invalidemailaddress'        => 'نشانی پست الکترونیکی وارد شده قابل قبول نیست، چرا که دارای ساختار نامعتبری است.
لطفاً نشانی‌ای با ساختار صحیح وارد کنید و یا بخش مربوط را خالی بگذارید.',
'accountcreated'             => 'حساب ایجاد شد.',
'accountcreatedtext'         => 'حساب کاربری $1 ایجاد شده‌است.',
'createaccount-title'        => 'ایجاد حساب کاربری در {{SITENAME}}',
'createaccount-text'         => 'یک نفر برای $2 یک حساب کاربری در {{SITENAME}} ایجاد کرده‌است ($4).
گذرواژهٔ «$2« چنین است: $3

شما باید وارد سیستم شوید تا گذرواژهٔ خود را تغییر بدهید.

اگر این حساب اشتباهی ساخته شده است، این پیغام را نادیده بگیرید.',
'login-throttled'            => 'شما به تازگی چندین بار تلاش کرده‌اید که گذرواژهٔ این حساب کاربری را وارد کنید. لطفاً پیش از آن که دوباره تلاش کنید، صبر کنید.',
'loginlanguagelabel'         => 'زبان: $1',

# Password reset dialog
'resetpass'                 => 'تغییر گذرواژه',
'resetpass_announce'        => 'شما با کد موقتی پست‌شده وارد شده‌اید. برای انجام فرایند ورود به سیستم باید گذروازهٔ جدیدی وارد کنید:',
'resetpass_text'            => '<!-- اینجا متن اضافه کنید -->',
'resetpass_header'          => 'تغییر گذرواژهٔ حساب کاربری',
'oldpassword'               => 'گذرواژهٔ پیشین',
'newpassword'               => 'گذرواژهٔ جدید',
'retypenew'                 => 'گذرواژهٔ جدید را دوباره وارد کنید',
'resetpass_submit'          => 'تنظیم گذرواژه و ورود به سیستم',
'resetpass_success'         => 'گذرواژهٔ شما با موفقیت تغییر داده شد. در حال وارد کردن شما به سیستم....',
'resetpass_bad_temporary'   => 'گذرواژهٔ موقت نامعتبر است. احتمالاً پیشتر گذرواژهٔ خود را با موفقیت تغییر داده‌اید یا گذرواژهٔ موقت جدیدی درخواسته‌اید.',
'resetpass_forbidden'       => 'نمی‌توان گذرواژه‌ها را تغییر داد',
'resetpass-no-info'         => 'برای دسترسی مستقیم به این صفحه شما باید به سیستم وارد شده باشید.',
'resetpass-submit-loggedin' => 'تغییر گذرواژه',
'resetpass-wrong-oldpass'   => 'گذرواژهٔ موقت یا اخیر نامعتبر. 
ممکن است که شما همینک گذرواژه‌تان را با موفقیت تغییر داده باشید یا درخواست یک گذرواژهٔ موقت جدید کرده باشید.',
'resetpass-temp-password'   => 'گذرواژهٔ موقت:',

# Edit page toolbar
'bold_sample'     => 'متن پررنگ',
'bold_tip'        => 'متن پُررنگ',
'italic_sample'   => 'متن ایتالیک',
'italic_tip'      => 'متن ایتالیک',
'link_sample'     => 'عنوان پیوند',
'link_tip'        => 'پیوند داخلی',
'extlink_sample'  => 'http://www.example.com عنوان پیوند',
'extlink_tip'     => 'پیوند به بیرون (پیشوند http:// را فراموش نکنید)',
'headline_sample' => 'متن عنوان',
'headline_tip'    => 'عنوان سطح ۲',
'math_sample'     => 'درج فرمول در اینجا',
'math_tip'        => 'فرمول ریاضی (LaTeX)',
'nowiki_sample'   => 'اینجا متن قالب‌بندی‌نشده وارد شود',
'nowiki_tip'      => 'نادیده گرفتن قالب‌بندی ویکی',
'image_sample'    => 'مثال.jpg',
'image_tip'       => 'تصویر داخل متن',
'media_sample'    => 'مثال.ogg',
'media_tip'       => 'پیوند پروندهٔ رسانه',
'sig_tip'         => 'امضای شما و برچسب زمان',
'hr_tip'          => 'خط افقی (در کاربرد آن امساک کنید)',

# Edit pages
'summary'                          => 'خلاصه:',
'subject'                          => 'موضوع/عنوان:',
'minoredit'                        => 'این ویرایش جزئی است',
'watchthis'                        => 'پی‌گیری این صفحه',
'savearticle'                      => 'صفحه ذخیره شود',
'preview'                          => 'پیش‌نمایش',
'showpreview'                      => 'پیش‌نمایش',
'showlivepreview'                  => 'پیش‌نمایش زنده',
'showdiff'                         => 'نمایش تغییرات',
'anoneditwarning'                  => 'شما با نام کاربری وارد نشده‌اید. نشانی IP شما در تاریخچهٔ ویرایش‌های این صفحه ثبت خواهد شد.',
'missingsummary'                   => "'''یادآوری:''' شما خلاصهٔ ویرایش ننوشته‌اید. اگر دوباره ''ذخیره'' را کلیک کنید ویرایشتان بدون خلاصه ذخیره خواهد شد.",
'missingcommenttext'               => 'لطفاً توضیحی در زیر بیفزایید.',
'missingcommentheader'             => "یادآوری: '''شما موضوع/عنوان این یادداشت را مشخص نکرده‌اید.''' اگر دوباره دکمهٔ ذخیره را فشار دهید ویرایش شما بدون آن ذخیره خواهد شد.",
'summary-preview'                  => 'پیش‌نمایش خلاصه:',
'subject-preview'                  => 'پیش‌نمایش موضوع/عنوان:',
'blockedtitle'                     => 'کاربر بسته شد.',
'blockedtext'                      => "<big>'''دسترسی نام کاربری یا نشانی اینترنتی شما بسته شده است.'''</big>

این کار توسط $1 انجام شده‌است.
دلیل داده‌شده این است: $2''

* شروع قطع دسترسی: $8
* زمان پایان این قطع دسترسی: $6
* کاربری که قطع دسترسی‌اش مد نظر بوده: $7

شما می‌توانید با $1 یا یکی از [[{{MediaWiki:Grouppage-sysop}}|مدیران]] تماس بگیرید و در این باره صحبت کنید.

توجه کنید که شما نمی‌توانید از امکان «فرستادن پست الکترونیکی به این کاربر» استفاده کنید مگر اینکه نشانی پست الکترونیکی معتبری در [[Special:Preferences|ترجیحات کاربری]]‌تان ثبت کرده باشید.

نشانی IP شما $3 و شماره قطع دسترسی شما $5 است. لطفاً این شماره‌ها را در کلیهٔ پرس‌وجوهایتان ذکر کنید.

شما می‌توانید با $1 یا یکی دیگر از [[{{MediaWiki:Grouppage-sysop}}|مدیران]] تماس بگیرید، تا در مورد این قطع دسترسی صحبت کنید.
توجه کنید که برای ارسال پست الکترونیکی در ویکی، باید پست الکترونیکی خود را از طریق صفحهٔ [[Special:Preferences|تنظیمات]] فعال کرده باشید، و نیز، باید امکان استفاده از این ویژگی برای شما قطع نباشد.
نشانی اینترنتی فعلی شما $3 است و شماره قطع دسترسی $5 است.
لطفاً این شماره را در هر درخواستی که در این باره مطرح می‌کنید قید کنید.",
'autoblockedtext'                  => "دسترسی نشانی اینترنتی (IP) شما قطع شده‌است، چرا که این نشانی اینترنتی توسط یک کاربر استفاده می‌شده که دسترسی او توسط $1 قطع گردیده‌است.
علت ذکر شده چنین است:

:''$2''

* شروع قطع دسترسی: $8
* پایان قطع دسترسی: $6
* کاربری که قطع دسترسی‌اش مد نظر بوده: $7

شما می‌توانید با $1 یا یکی دیگر از [[{{MediaWiki:Grouppage-sysop}}|مدیران]] تماس بگیرید، تا در مورد این قطع دسترسی صحبت کنید.

توجه کنید که برای ارسال پست الکترونیکی در ویکی، باید پست الکترونیکی خود را از طریق صفحهٔ [[Special:Preferences|تنظیمات]] فعال کرده باشید، و نیز، باید امکان استفاده از این ویژگی برای شما قطع نباشد.

نشانی اینترنتی فعلی شما $3 است و شماره قطع دسترسی $5 است.
لطفاً این شماره را در هر درخواستی که در این باره مطرح می‌کنید قید کنید.",
'blockednoreason'                  => 'دلیلی مشخص نشده‌است',
'blockedoriginalsource'            => "متن مبدأ '''$1''' در زیر نمایش داده شده است:",
'blockededitsource'                => "متن '''ویرایش‌های شما''' در '''$1''' در زیر نشان داده شده‌است:",
'whitelistedittitle'               => 'برای ویرایش باید به سیستم وارد شوید.',
'whitelistedittext'                => 'برای ویرایش مقاله‌ها باید به سیستم شوید: $1.',
'confirmedittitle'                 => 'تأیید نشانی پست الکترونیکی، برای ویرایش، لازم است.',
'confirmedittext'                  => 'شما باید، پیش از ویرایش صفحه‌ها، نشانی پست الکترونیکی خود را مشخص و تأیید کنید. لطفاً از طریق [[Special:Preferences|ترجیحات کاربر]] این کار را صورت دهید.',
'nosuchsectiontitle'               => 'چنین بخشی وجود ندارد',
'nosuchsectiontext'                => 'شما تلاش کرده‌اید یک بخش در صفحه را ویرایش کنید که وجود ندارد. از آن‌جایی که بخش $1 وجود ندارد، ذخیره ویرایش شما نیز ممکن نیست.',
'loginreqtitle'                    => 'ورود به سیستم لازم است',
'loginreqlink'                     => 'ورود به سیستم',
'loginreqpagetext'                 => 'برای دیدن صفحه‌های دیگر باید $1 کنید.',
'accmailtitle'                     => 'گذرواژه فرستاده شد.',
'accmailtext'                      => "یک گذرواژهٔ تصادفی ساخته شده برای [[User talk:$1|$1]] برای $2 ارسال شد.

گذرواژهٔ این حساب کاربری تازه، پس از ورود به سیستم از طریق ''[[Special:ChangePassword|تغییر گذرواژه]]'' قابل تغییر است.",
'newarticle'                       => '(جدید)',
'newarticletext'                   => 'شما پیوندی را دنبال کرده‌اید و به صفحه‌ای رسیده‌اید که هنوز وجود ندارد.
برای ایجاد صفحه، در مستطیل زیر شروع به تایپ کنید (برای اطلاعات بیشتر به [[{{ns:project}}:راهنما|صفحهٔ راهنما]] مراجعه کنید).
اگر به اشتباه اینجا آمده‌اید، دکمهٔ «بازگشت» مرورگرتان را بزنید.',
'anontalkpagetext'                 => "---- ''این صفحه‌ی بحث برای کاربر گمنامی است که هنوز حسابی درست نکرده است یا از آن استفاده نمی‌کند.
بنابراین برای شناسایی‌اش مجبوریم از نشانی IP عددی استفاده کنیم.
چنین نشانی‌های IPای ممکن است توسط چندین کاربر به شکل مشترک استفاده شود.
اگر شما کاربر گمنامی هستید و تصور می‌کنید اظهار نظرات نامربوط به شما صورت گرفته است،
لطفاً برای پیشگیری از اشتباه گرفته شدن با کاربران گمنام دیگر در آیند [[Special:UserLogin|حسابی ایجاد کنید یا به سیستم وارد شوید]].''",
'noarticletext'                    => 'در حال حاضر این صفحه متنی ندارد، شما می‌توانید [[Special:Search/{{PAGENAME}}|عنوان این صفحه را در صفحه‌های دیگر جستجو کنید]] یا [{{fullurl:{{FULLPAGENAME}}|action=edit}} این صفحه را ویرایش کنید].',
'userpage-userdoesnotexist'        => 'حساب کاربر «$1» ثبت نشده است. اطمینان حاصلی کنید که می‌خواهید این صفحه را ایجاد یا ویرایش کنید.',
'clearyourcache'                   => "'''نکته:''' پس از ذخیره‌سازی ممکن است برای دیدن تغییرات نیاز باشد که حافظهٔ نهانی مرورگر خود را خالی کنید. '''موزیلا / فایرفاکس / Safari:'''  کلید '''Shift''' را نگه‌دارید و روی دکمهٔ '''Reload''' کلیک کنید، یا کلید‌های '''Ctrl-Shift-R''' را با هم فشار دهید (در رایانه‌های اپل مکینتاش کلید‌های '''Cmd-Shift-R''')؛ '''اینترنت اکسپلورر:''' کلید '''Ctrl''' نگه‌دارید و روی دکمهٔ '''Refresh''' کلیک‌ کنید، یا کلید‌های '''Ctrl-F5''' را با هم فشار دهید؛ '''Konqueror:''' روی دکمهٔ '''Reload''' کلیک کنید و یا کلید '''F5''' را فشار دهید؛ '''اُپرا:''' کاربران اُپرا ممکن است لازم باشد که بطور کامل حافظهٔ نهانی مرورگر را در منوی ''Tools&rarr;Preferences'' خالی کنند.",
'usercssjsyoucanpreview'           => "<strong>نکته:</strong> قبل از این که فایل CSS یا JS خود را ذخیره کنید، با استفاده از دکمه '''پیش‌نمایش''' می‌توان آن را آزمایش کنید.",
'usercsspreview'                   => "'''فراموش نکنید که سی‌اس‌اس کاربریتان فقط پیش‌نمایش یافته‌است و هنوز ذخیره نشده‌است!'''",
'userjspreview'                    => "'''فراموش مکنید که شما فقط دارید جاوااسکریپت کاربریتان را امتحان می‌کنید/پیش‌نمایشش را می‌بینید. هنوز ذخیره نشده‌است!'''",
'userinvalidcssjstitle'            => "'''هشدار:''' پوسته‌ای به نام «$1» وجود ندارد.
به یاد داشته باشید که صفحه‌های شخصی &#8206;.css و &#8206;.js باید عنوانی با حروف کوچک داشته باشند؛ نمونه: {{ns:user}}:فو/monobook.css در مقابل {{ns:user}}:فو/Monobook.css.",
'updated'                          => '(به‌روز شد)',
'note'                             => '<strong>نکته:</strong>',
'previewnote'                      => '<strong>توجه کنید که این فقط پیش‌نمایش است، و ذخیره نشده است!</strong>',
'previewconflict'                  => 'این پیش‌نمایش منعکس‌کنندهٔ متن ناحیهٔ ویرایش متن بالایی است،
به شکلی که اگر بخواهید متن را ذخیره کنید نشان داده خواهد شد.',
'session_fail_preview'             => '<strong>شرمنده! به دلیل از دست رفتن اطلاعات نشست کاربری، نمی‌توانیم ویرایش شما را پردازش کنیم.
لطفاً دوباره سعی کنید.
در صورتی که باز هم با همین پیام مواجه شدید، از سیستم [[Special:UserLogout|خارج شوید]] و مجدداً وارد شوید.</strong>',
'session_fail_preview_html'        => "<strong>متاسفانه امکان ثبت ویرایش شما به خاطر از دست رفتن اطلاعات نشست کاربری وجود ندارد.</strong>

''با توجه به این که در {{SITENAME}} امکان درج اچ‌تی‌ام‌ال خام فعال است، پیش‌نمایش صفحه پنهان شده تا امکان حملات مبتنی بر جاوااسکریپت وجود نداشته باشد.''

<strong>اگر مطمئن هستید که این پیش‌نمایش یک ویرایش مجاز است، آن را تکرار کنید.
اگر تکرار پیش‌نمایش نتیجه نداد، از سیستم [[Special:UserLogout|خارج شوید]] و دوباره وارد شوید.</strong>",
'token_suffix_mismatch'            => "'''ویرایش شما ذخیره نشد، زیرا مرورگر شما نویسه‌های نقطه‌گذاری را از هم پاشیده‌است. 
ویرایش شما ذخیره نشد تا از خراب شدن متن صفحه جلوگیری شود. 
گاهی این اشکال زمانی پیش می‌آید که شما از یک برنامه تحت وب حدواسط (web-based proxy) استفاده کنید.'''",
'editing'                          => 'در حال ویرایش $1',
'editingsection'                   => 'در حال ویرایش $1 (بخش)',
'editingcomment'                   => 'در حال ویرایش $1 (بخش جدید)',
'editconflict'                     => 'تعارض ویرایشی: $1',
'explainconflict'                  => 'از وقتی شما ویرایش این صفحه را آغاز کرده‌اید شخص دیگری آن را تغییر داده است.
ناحیهٔ متنی بالایی شامل متن صفحه به شکل فعلی آن است.
تغییرات شما در ناحیهٔ متنی پایینی نشان داده شده است.
شما باید تغییراتتان را با متن فعلی ترکیب کنید.
وقتی «ذخیرهٔ صفحه» را فشار دهید، <b>فقط</b> متن ناحیهٔ متنی بالایی ذخیره خواهد شد.<br />',
'yourtext'                         => 'متن شما',
'storedversion'                    => 'نسخهٔ ضبط‌شده',
'nonunicodebrowser'                => "'''هشدار: مرورگر شما با استانداردهای یونیکد سازگار نیست.''' کاراکترهای غیر ASCII به صورت اعداد در مبنای شانزده به شما نشان داده می‌شوند.",
'editingold'                       => '<strong>هشدار:
شما دارید نسخهٔ قدیمی‌ای از این صفحه را ویرایش می‌کنید.
اگر ذخیره‌اش کنید، هر تغییری که پس از این نسخه انجام شده از بین خواهد رفت.</strong>',
'yourdiff'                         => 'تفاوت‌ها',
'copyrightwarning'                 => 'لطفاً توجه داشته باشید که فرض می‌شود کلیهٔ مشارکت‌های شما با {{SITENAME}} تحت «$2» منتشر می‌شوند (برای جزئیات بیشتر به $1 مراجعه کنید). اگر نمی‌خواهید نوشته‌هایتان بی‌رحمانه ویرایش شده و به دلخواه توزیع شود، اینجا نفرستیدشان.<br />
همینطور شما دارید به ما قول می‌دهید که خودتان این را نوشته‌اید، یا آن را از یک منبع آزاد با مالکیت عمومی یا مشابه آن برداشته‌اید. <strong>کارهای دارای حق انحصاری تکثیر (copyright) را بی‌اجازه نفرستید!</strong>',
'copyrightwarning2'                => 'لطفاً توجه داشته باشید که فرض می‌شود کلیهٔ مشارکت‌های شما با {{SITENAME}} تحت «اجازه‌نامهٔ مستندات آزاد گنو» منتشر می‌شوند (برای جزئیات بیشتر به $1 مراجعه کنید). اگر نمی‌خواهید نوشته‌هایتان بی‌رحمانه ویرایش شده و به دلخواه توزیع شود، اینجا نفرستیدشان.<br />
همینطور شما دارید به ما قول می‌دهید که خودتان این را نوشته‌اید، یا آن را از یک منبع آزاد با مالکیت عمومی یا مشابه آن برداشته‌اید. <strong>کارهای دارای حق انحصاری تکثیر (copyright) را بی‌اجازه نفرستید!</strong>',
'longpagewarning'                  => '<strong>هشدار: این صفحه $1 کیلوبایت طول دارد؛
بعضی مرورگرها ممکن با ویرایش صفحه‌های نزدیک به ۳۲ کیلوبایت یا طولانی‌تر از آن مشکلاتی داشته باشند.
لطفاً دربارهٔ شکستن این صفحه به قسمت‌های کوچکتر فکر کنید.</strong>',
'longpageerror'                    => '<strong>خطا: متنی که ارسال کرده‌اید $1 کیلوبایت طول دارد. این مقدار از مقدار بیشینهٔ $2 کیلوبایت بیشتر است. نمی‌توان ذخیره‌اش کرد.</strong>',
'readonlywarning'                  => '<strong>هشدار: پایگاه داده برای نگهداری قفل شده است، به همین خاطر نمی‌توانید ویرایش‌هایتان را همین الآن ذخیره کنید.
اگر می‌خواهید متن را در یک پروندهٔ متنی ببرید و بچسبانید و برای آینده ذخیره‌اش کنید.

مدیری که پایگاه داده را قفل کرد این توضیح را ارائه کرد: $1</strong>',
'protectedpagewarning'             => 'هشدار: این صفحه قفل شده است تا فقط کاربران با امتیاز مدیر (یا بالاتر) بتوانند ویرایشش کنند.',
'semiprotectedpagewarning'         => "'''توجه:''' این صفحه قفل شده‌است تا تنها کاربران ثبت‌نام‌کرده قادر به ویرایش آن‌ باشند.",
'cascadeprotectedwarning'          => "'''هشدار:''' این صفحه به علت قرارگرفتن در {{PLURAL:$1|صفحهٔ|صفحه‌های}} آبشاری-محافظت‌شدهٔ زیر قفل شده‌است تا فقط مدیران بتوانند ویرایشش کنند.",
'titleprotectedwarning'            => '<strong>هشدار: این صفحه قفل شده‌است به شکلی که فقط برخی کاربران می‌توانند آن را ایجاد کنند.</strong>',
'templatesused'                    => 'الگوهای استفاده شده در این صفحه:',
'templatesusedpreview'             => 'الگوهای استفاده شده در این پیش‌نمایش:',
'templatesusedsection'             => 'الگوهای استفاده‌شده در این بخش:',
'template-protected'               => '(حفاظت‌شده)',
'template-semiprotected'           => '(نیمه حفاظت‌شده)',
'hiddencategories'                 => 'این صفحه در {{PLURAL:$1|یک ردهٔ پنهان|$1 ردهٔ پنهان}} قرار دارد:',
'edittools'                        => '<!-- متن این قسمت زیر صفحه‌های ویرایش و بارگذاری نشان داده می‌شود -->',
'nocreatetitle'                    => 'ایجاد صفحه محدود شده‌است.',
'nocreatetext'                     => 'این وبگاه قابلیت ایجاد صفحه‌های جدید را محدود کرده‌است. می‌توانید بازگردید و صفحه‌ای موجود را ویرایش کنید یا اینکه  [[Special:UserLogin|به سیستم وارد شوید یا حساب کاربری ایجاد کنید]].',
'nocreate-loggedin'                => 'شما اجازهٔ ایجاد صفحه‌های جدید ندارید.',
'permissionserrors'                => 'خطای سطح دسترسی',
'permissionserrorstext'            => 'شما اجازهٔ انجام این کار را به {{PLURAL:$1|دلیل|دلایل}} زیر ندارید:',
'permissionserrorstext-withaction' => 'شما اجازهٔ $2 را به {{PLURAL:$1|دلیل|دلایل}} رو به رو ندارید:',
'recreate-deleted-warn'            => "'''هشدار: شما در حال ایجاد کردن دوبارهٔ صفحه‌ای هستید که قبلاً حذف شده‌است.''' در نظر داشته باشید که آیا ادامهٔ ویرایش این صفحه کار مناسبی هست یا نه. سیاههٔ حذف مربوط به این صفحه برای راحتی در ادامه آمده‌است:",
'deleted-notice'                   => 'این صفحه حذف شده‌است. سیاههٔ حذف این صفحه در زیر آمده‌است.',
'deletelog-fulllog'                => 'مشاهدهٔ سیاههٔ کامل',
'edit-hook-aborted'                => 'ویرایش توسط قلاب لغو شد.
توضیحی در این مورد داده نشد.',
'edit-gone-missing'                => 'امکان به روز کردن صفحه وجود ندارد.
به نظرمیرسد که صفحه حذف شده باشد.',
'edit-conflict'                    => 'تعارض ویرایشی.',
'edit-no-change'                   => 'ویرایش شما نادیده گرفته شد، زیرا تغییری در متن داده نشده بود.',
'edit-already-exists'              => 'امکان ساختن صفحه جدید وجود ندارد.
این صفحه از قبل وجود دارد.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'هشدار: این صفحه حاوی تعدادی زیادی فراخوانی دستورهای تجزیه‌گر است.

تعداد آن‌ها باید کمتر از $2 {{PLURAL:$2|فراخوانی|فراخوانی}} باشد، و اینک {{PLURAL:$1|$1 فراخوانی|$1 فراخوانی}} است.',
'expensive-parserfunction-category'       => 'صفحه‌هایی که حاوی تعداد زیادی فراخوانی دستورهای تجزیه‌گر هستند',
'post-expand-template-inclusion-warning'  => 'هشدار: الگو بیش از اندازه بزرگ است.
برخی الگوها ممکن است شامل نشوند.',
'post-expand-template-inclusion-category' => 'صفحه‌هایی که در آن‌ها تعداد الگوهای به کار رفته بیش از اندازه است',
'post-expand-template-argument-warning'   => 'هشدار: این صفحه شامل دست کم یک پارامتر الگو است که بیش از اندازه بزرگ است.
این پارامترها نادیده گرفته شدند.',
'post-expand-template-argument-category'  => 'صفحه‌های دارای الگوهایی با پارامترهای نادیده گرفته شده',
'parser-template-loop-warning'            => 'حلقه در الگو پیدا شد: [[$1]]',
'parser-template-recursion-depth-warning' => 'محدودیت عمق بازگشت الگو رد شد ($1)',

# "Undo" feature
'undo-success' => 'این ویرایش خنثی شده‌است. لطفاً پس از کسب اطمینان تغییرات نشان‌دا‌ده‌شده را ذخیره کنید.',
'undo-failure' => 'به علت تعارض با ویرایش‌های میانی نشد این ویرایش را خنثی کرد.',
'undo-norev'   => 'این ویرایش را نمی‌توان خنثی کرد چون وجود ندارد یا حذف شده‌است.',
'undo-summary' => 'خنثی‌سازی ویرایش $1 توسط [[Special:Contributions/$2|$2]] ([[User talk:$2|بحث]])',

# Account creation failure
'cantcreateaccounttitle' => 'نمی‌توان حساب باز کرد.',
'cantcreateaccount-text' => "امكان ساختن حساب کاربری از این این نشانی اینترنتی ('''$1''') توسط [[User:$3|$3]] سلب شده است.

دلیل ارائه شده چنین بوده است: $2",

# History pages
'viewpagelogs'           => 'نمایش سیاهه‌های مربوط به این صفحه',
'nohistory'              => 'این صفحه تاریخچهٔ ویرایش ندارد.',
'currentrev'             => 'نسخهٔ فعلی',
'currentrev-asof'        => 'نسخهٔ کنونی تا $1',
'revisionasof'           => 'نسخهٔ $1',
'revision-info'          => 'ُویرایش در تاریخ $1 توسط $2', # Additionally available: $3: revision id
'previousrevision'       => '→ نسخهٔ قدیمی‌تر',
'nextrevision'           => 'نسخهٔ جدیدتر←',
'currentrevisionlink'    => 'نمایش نسخهٔ فعلی',
'cur'                    => 'فعلی',
'next'                   => 'بعدی',
'last'                   => 'قبلی',
'page_first'             => 'نخست',
'page_last'              => 'واپسین',
'histlegend'             => 'شرح: (فعلی) = تفاوت با نسخهٔ فعلی،
(قبلی) = تفاوت با نسخهٔ قبلی، جز = ویرایش جزئی',
'history-fieldset-title' => 'مرور تاریخچه',
'deletedrev'             => '[حذف‌شده]',
'histfirst'              => 'قدیمی‌ترین',
'histlast'               => 'جدیدترین',
'historysize'            => '({{PLURAL:$1|۱ بایت|$1 بایت}})',
'historyempty'           => '(خالی)',

# Revision feed
'history-feed-title'          => 'تاریخچهٔ ویرایش‌ها',
'history-feed-description'    => 'تاریخچهٔ ویرایشهای صفحه در ویکی',
'history-feed-item-nocomment' => '$1 در $2', # user at time
'history-feed-empty'          => 'صفحهٔ درخواسته وجود ندارد. ممکن است که از ویکی حذف  یا اینکه نامش تغییر داده شده باشد. 
[[Special:Search|جستجوی]] صفحه‌های جدید مرتبطِ موجود در این ویکی را هم بیازمایید. شاید افاقه کرد.',

# Revision deletion
'rev-deleted-comment'            => '(توضیحات پاک شد)',
'rev-deleted-user'               => '(نام کاربری حذف شده‌است)',
'rev-deleted-event'              => '(مورد پاک شد)',
'rev-deleted-text-permission'    => 'این ویرایش از این صفحه از تاریخچه عمومی این صفحه حذف شده‌است. اطلاعات مرتبط با آن را احتمالاً در [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] می‌توانید ببینید.',
'rev-deleted-text-view'          => 'این ویرایش از این صفحه از تاریخچه عمومی این صفحه حذف شده‌است. شما به عنوان مدیر این وبگاه می‌توانید آن را ببینید. اطلاعات مرتبط با آن را احتمالاً در [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} سیاههٔ حذف] می‌توانید ببینید.',
'rev-delundel'                   => 'نمایش/نهفتن',
'revisiondelete'                 => 'حذف/احیای نسخه‌ها',
'revdelete-nooldid-title'        => 'هیچ نسخه‌ای انتخاب نشده‌است',
'revdelete-nooldid-text'         => 'نسخه یا نسخه‌هایی از صفحهٔ مورد نظر را که می‌خواهید تحت تاثیر قرار بگیرند انتخاب نکرده‌اید.',
'revdelete-nologtype-title'      => 'نوع سیاهه مشخص نشده‌است',
'revdelete-nologtype-text'       => 'شما هیچ نوع سیاهه‌ای را برای این کار مشخص نکردید.',
'revdelete-toomanytargets-title' => 'اهداف بیش از اندازه',
'revdelete-toomanytargets-text'  => 'انواع سیاهه‌هایی که برای این کار مشخص کردید بیش از اندازه است.',
'revdelete-nologid-title'        => 'مدخل غیر مجاز در سیاهه',
'revdelete-nologid-text'         => 'شما یا یک رخداد در سیاههٔ هدف مشخص نکردید یا موردی را مشخص کردید که وجود ندارد.',
'revdelete-selected'             => "'''{{PLURAL:$2|نسخهٔ|نسخه‌های}} انتخاب شده از '''$1:''''''",
'logdelete-selected'             => "'''{{PLURAL:$1|مورد|موارد}} انتخاب شده از سیاهه:'''",
'revdelete-text'                 => 'نسخه‌ها و موارد حذف شده کماکان از طریق تارخچه و سیاهه‌ها قابل مشاهده هستند، اما بخش‌هایی از محتوای آن‌ها توسط عموم قابل مشاهده نخواهد بود. سایر مدیران این ویکی هنوز می‌توانند این محتوای پنهان را ببینند و حتی موارد حذف شده را احیا کنند، مگر آن که محدودیت‌های دیگری اعمال گردد.',
'revdelete-legend'               => 'تنظیم محدودیت‌های نسخه:',
'revdelete-hide-text'            => 'نهفتن متن نسخه',
'revdelete-hide-name'            => 'نهفتن عمل و هدف',
'revdelete-hide-comment'         => 'نهفتن توضیح ویرایش',
'revdelete-hide-user'            => 'نام کاربری/نشانی IP ویراستار مخفی شود',
'revdelete-hide-restricted'      => 'اعمال این تغییرات بر مدیران به همراه دیگران',
'revdelete-suppress'             => 'از دسترسی مدیران به داده نیز مانند سایر کاربران جلوگیری به عمل آید.',
'revdelete-hide-image'           => 'نهفتن محتویات پرونده',
'revdelete-unsuppress'           => 'خاتمهٔ محدودیت‌ها در مورد نسخه‌های انتخاب شده',
'revdelete-log'                  => 'توضیح سیاهه:',
'revdelete-submit'               => 'اِعمال بر نسخهٔ انتخاب‌شده',
'revdelete-logentry'             => 'تغییر پیدایی نسخه در [[$1]]',
'logdelete-logentry'             => 'تغییر پیدایی مورد در [[$1]]',
'revdelete-success'              => 'تغییر پیدایی نسخه با موفقیت انجام شد.',
'logdelete-success'              => 'تغییر پیدایی مورد با موفقیت انجام شد.',
'revdel-restore'                 => 'تغییر پیدایی',
'pagehist'                       => 'تاریخچهٔ صفحه',
'deletedhist'                    => 'تاریخچهٔ حذف شده',
'revdelete-content'              => 'محتوا',
'revdelete-summary'              => 'خلاصه ویرایش',
'revdelete-uname'                => 'نام کاربر',
'revdelete-restricted'           => 'مدیران را محدود کرد',
'revdelete-unrestricted'         => 'محدودیت مدیران را لغو کرد',
'revdelete-hid'                  => '$1 را پنهان کرد',
'revdelete-unhid'                => '$1 را از حالت پنهان در آورد',
'revdelete-log-message'          => '$1 برای $2 {{PLURAL:$2|نسخه|نسخه}}',
'logdelete-log-message'          => '$1 برای $2 {{PLURAL:$2|رخداد|رخداد}}',

# Suppression log
'suppressionlog'     => 'سیاههٔ فرونشانی',
'suppressionlogtext' => 'در زیر فهرستی از آخرین حذف‌ها و قطع دسترسی‌هایی که حاوی محتوایی هستند که از مدیران پنهان شده‌اند را می‌بینید. 
برای مشاهدهٔ فهرستی از قطع دسترسی‌های فعال [[Special:IPBlockList|فهرست قطع‌دسترسی‌ها]] را ببینید.',

# History merging
'mergehistory'                     => 'ادغام تاریخچه صفحه‌ها',
'mergehistory-header'              => "این صفحه به شما این امکان را می‌دهد که نسخه‌های تاریخچهٔ یک مقاله را با یک مقاله دیگر ادغام کنید.
اطمینان حاصل کنید که این تغییر به توالی زمانی ویرایش‌ها لطمه نخواهد زد.

'''دست کم نسخه فعلی صفحه مبدا باید باقی بماند.'''",
'mergehistory-box'                 => 'ادغام نسخه‌های دو صفحه:',
'mergehistory-from'                => 'صفحه مبدا:',
'mergehistory-into'                => 'صفحه مقصد:',
'mergehistory-list'                => 'تاریخچه قابل ادغام',
'mergehistory-merge'               => 'این نسخه‌های [[:$1]] قابل ادغام با [[:$2]] هستند.
از ستون دکمه‌های رادیویی استفاده کنید تا نسخه‌هایی که تا قبل از یک زمان مشخص ایجاد شده‌اند را انتخاب کنید..
توجه کنید که کلیک روی پیوندها باعث پاک شدن تنظیماتی که تا آن لحظه اعمال کرده‌اید می‌شود.',
'mergehistory-go'                  => 'نمایش تاریخچه قابل ادغام',
'mergehistory-submit'              => 'ادغام نسخه‌ها',
'mergehistory-empty'               => 'هیچ‌یک از نسخه‌ها قابل ادغام نیستند',
'mergehistory-success'             => '$3 نسخه از [[:$1]]  با موفقیت در [[:$2]] ادغام {{PLURAL:$3|شد|شدند}}.',
'mergehistory-fail'                => 'ادغام تاریخچه ممکن نیست، لطفاً گزینه‌های صفحه و زمان را بازبینی کنید.',
'mergehistory-no-source'           => 'صفحهٔ مبدا $1 وجود ندارد.',
'mergehistory-no-destination'      => 'صفحهٔ مقصد $1 وجود ندارد.',
'mergehistory-invalid-source'      => 'صفحهٔ مبدا باید عنوان قابل قبولی داشته باشد.',
'mergehistory-invalid-destination' => 'صفحهٔ مقصد باید عنوان قابل قبولی داشته باشد.',
'mergehistory-autocomment'         => '[[:$1]] را در [[:$2]] ادغام کرد',
'mergehistory-comment'             => '[[:$1]] را در [[:$2]] ادغام کرد: $3',
'mergehistory-same-destination'    => 'صفحهٔ مبدا و مقصد نمی‌تواند یکی باشد',

# Merge log
'mergelog'           => 'سیاهه ادغام',
'pagemerge-logentry' => '[[$1]] در [[$2]] ادغام شد (نسخه‌های تا $3)',
'revertmerge'        => 'واگردانی ادغام',
'mergelogpagetext'   => 'در زیر سیاهه آخرین موارد ادغام تاریخچه یک صفحه در صفحه‌ای دیگر را می‌بینید.',

# Diffs
'history-title'           => 'تاریخچه ویرایش‌های «$1»',
'difference'              => '(تفاوت بین نسخه‌ها)',
'lineno'                  => 'سطر $1:',
'compareselectedversions' => 'مقایسهٔ نسخه‌های انتخاب‌شده',
'visualcomparison'        => 'مقایسهٔ دیداری',
'wikicodecomparison'      => 'مقایسهٔ کد ویکی',
'editundo'                => 'خنثی‌سازی',
'diff-multi'              => '({{PLURAL:$1||$1&nbsp;}}ویرایش میانی نشان داده نشده‌است.)',
'diff-movedto'            => 'به $1 منتقل شد',
'diff-styleadded'         => 'سبک $1 افزوده شد',
'diff-added'              => '$1 افزوده شد',
'diff-changedto'          => 'به $1 تغییر یافت',
'diff-movedoutof'         => 'از $1 خارج شد',
'diff-styleremoved'       => 'سبک $1 برداشته شد',
'diff-removed'            => '$1 برداشته شد',
'diff-changedfrom'        => 'از $1 تغییر یافت',
'diff-src'                => 'منبع',
'diff-withdestination'    => 'با مقصد $1',
'diff-with'               => '&#32;با $1 $2',
'diff-with-final'         => '&#32;و $1 $2',
'diff-width'              => 'پهنا',
'diff-height'             => 'بلندی',
'diff-p'                  => "یک '''بند'''",
'diff-blockquote'         => "یک '''نقل قول'''",
'diff-h1'                 => "یک '''عنوان (سطح ۱)'''",
'diff-h2'                 => "یک '''عنوان (سطح ۲)'''",
'diff-h3'                 => "یک '''عنوان (سطح ۳)'''",
'diff-h4'                 => "یک '''عنوان (سطح ۴)'''",
'diff-h5'                 => "یک '''عنوان (سطح ۵)'''",
'diff-pre'                => "یک '''قطعهٔ از پیش قالب‌بندی شده",
'diff-div'                => "یک '''بخش'''",
'diff-ul'                 => "یک '''فهرست غیر ترتیبی'''",
'diff-ol'                 => "یک '''فهرست ترتیبی'''",
'diff-li'                 => "یک '''مورد فهرست'''",
'diff-table'              => "یک '''جدول'''",
'diff-tbody'              => "یک '''محتوای جدول'''",
'diff-tr'                 => "یک '''ردیف'''",
'diff-td'                 => "یک '''خانه'''",
'diff-th'                 => "یک '''عنوان'''",
'diff-br'                 => "یک '''شکستگی'''",
'diff-hr'                 => "یک '''خط افقی'''",
'diff-code'               => "یک '''قطعه کد رایانه'''",
'diff-dl'                 => "یک '''فهرست تعاریف'''",
'diff-dt'                 => "یک '''عبارت تعریف'''",
'diff-dd'                 => "یک '''تعریف'''",
'diff-input'              => "یک '''ورودی'''",
'diff-form'               => "یک '''فرم'''",
'diff-img'                => "یک '''تصویر'''",
'diff-span'               => "یک '''گستره'''",
'diff-a'                  => "یک '''پیوند'''",
'diff-i'                  => "'''کج'''",
'diff-b'                  => "'''پررنگ'''",
'diff-strong'             => "'''قوی'''",
'diff-em'                 => "'''موکد'''",
'diff-font'               => "'''قلم'''",
'diff-big'                => "'''بزرگ'''",
'diff-del'                => "'''حذف شده'''",
'diff-tt'                 => "'''با پهنای ثابت'''",
'diff-sub'                => "'''بالانویس'''",
'diff-sup'                => "'''پایین‌نویس'''",
'diff-strike'             => "'''خط‌خورده'''",

# Search results
'searchresults'                    => 'نتایج جستجو',
'searchresults-title'              => 'نتایج جستجو برای «$1»',
'searchresulttext'                 => 'برای اطلاعات بیشتر دربارهٔ جستجوی {{SITENAME}}، به [[{{ns:project}}:جستجو کردن|جستجوی {{SITENAME}}]] مراجعه کنید.',
'searchsubtitle'                   => '«[[:$1]]» را جُستید',
'searchsubtitleinvalid'            => 'برای پرس‌وجوی «$1»',
'noexactmatch'                     => "'''صفحه‌ای با عنوان دقیق \"\$1\" وجود ندارد.''' شما می‌توانید این صفحه را [[:\$1|ایجاد کنید]].",
'noexactmatch-nocreate'            => "'''صفحه‌ای با عنوان «$1» وجود ندارد.'''",
'toomanymatches'                   => 'تعداد موارد مطابق خیلی زیاد بود، لطفاً درخواست دیگری را امتحان کنید',
'titlematches'                     => 'عنوان مقاله تطبیق می‌کند',
'notitlematches'                   => 'عنوان هیچ مقاله‌ای نمی‌خورد',
'textmatches'                      => 'متن مقاله تطبیق می‌کند',
'notextmatches'                    => 'متن هیچ مقاله‌ای نمی‌خورد',
'prevn'                            => '$1تای قبلی',
'nextn'                            => '$1تای بعدی',
'viewprevnext'                     => 'نمایش ($1) ($2) ($3).',
'searchmenu-legend'                => 'گزینه‌های جستجو',
'searchmenu-exists'                => "* صفحهٔ '''[[$1]]'''",
'searchmenu-new'                   => "'''صفحهٔ «[[:$1]]» را روی این ویکی بسازید!'''",
'searchhelp-url'                   => 'Help:راهنما',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|مرور صفحه‌های با این پیشوند]]',
'searchprofile-articles'           => 'صفحه‌های محتوایی',
'searchprofile-articles-and-proj'  => 'صفحه‌های محتوایی و پروژه',
'searchprofile-project'            => 'صفحه‌های پروژه',
'searchprofile-images'             => 'پرونده‌ها',
'searchprofile-everything'         => 'همه‌چیز',
'searchprofile-advanced'           => 'پیشرفته',
'searchprofile-articles-tooltip'   => 'جستجو در $1',
'searchprofile-project-tooltip'    => 'جستجو در $1',
'searchprofile-images-tooltip'     => 'جستجو برای پرونده‌ها',
'searchprofile-everything-tooltip' => 'جستجوی تمام محتوا (شامل صفحه‌های بحث)',
'searchprofile-advanced-tooltip'   => 'جستجو در فضاهای نام دلخواه',
'prefs-search-nsdefault'           => 'جستجو با استفاده از پیش‌فرض‌ها:',
'prefs-search-nscustom'            => 'جستجو در فضاهای نام دلخواه:',
'search-result-size'               => '$1 ({{PLURAL:$2|یک کلمه|$2 کلمه}})',
'search-result-score'              => 'ارتباط: $1%',
'search-redirect'                  => '(تغییر مسیر $1)',
'search-section'                   => '(بخض $1)',
'search-suggest'                   => 'آیا منظورتان این بود: $1',
'search-interwiki-caption'         => 'پروژه‌های خواهر',
'search-interwiki-default'         => '$1 :نتیجه',
'search-interwiki-more'            => '(بیشتر)',
'search-mwsuggest-enabled'         => 'با پیشنهاد',
'search-mwsuggest-disabled'        => 'هیچ پیشنهادی نیست',
'search-relatedarticle'            => 'مرتبط',
'mwsuggest-disable'                => 'پیشنهادهای مبتنی بر AJAX را غیر فعال کن',
'searchrelated'                    => 'مرتبط',
'searchall'                        => 'همه',
'showingresults'                   => "نمایش {{PLURAL:$1|'''1''' نتیجه|'''$1''' نتیجه}} در پایین، آغاز از #'''$2'''.",
'showingresultsnum'                => "نمایش '''$3''' {{PLURAL:$3|نتیجه|نتیجه}} در پایین، آغاز از #'''$2'''.",
'showingresultstotal'              => "نمایش {{PLURAL:$4|نتیجهٔ '''$1''' از '''$3'''|نتیجه‌های '''$1 - $2''' از '''$3'''}}",
'nonefound'                        => "'''نکته''': فقط برخی از فضاهای نام به طور پیش‌فرض جستجو می‌شوند.
از پیشوندی نظیر ''همه:'' برای جستجوی خود استفاده کنید تا تمام محتوا (شامل صفحه‌های بحث، الگوها و غیره) را جستجو کنید، یا نام فضای نام دلخواه را به عنوان پیشوند به کار ببرید.",
'search-nonefound'                 => 'نتیجه‌ای منطبق با درخواست پیدا نشد.',
'powersearch'                      => 'جستجوی پیشرفته',
'powersearch-legend'               => 'جستجوی پیشرفته',
'powersearch-ns'                   => 'جستجو در فضاهای نام:',
'powersearch-redir'                => 'تغییرمسیرها فهرست شوند',
'powersearch-field'                => 'جستجو برای',
'search-external'                  => 'جستجوی خارجی',
'searchdisabled'                   => 'با عرض شرمندگی، جستجوی کل متن موقتاً از کار انداخته شده است. می‌توانید از جستجوی Google در پایین استفاده کنید. نتایج حاصل از جستجو با این روش ممکن است به‌روز نباشند.',

# Preferences page
'preferences'               => 'ترجیحات',
'mypreferences'             => 'ترجیحات من',
'prefs-edits'               => 'تعداد ویرایش‌ها',
'prefsnologin'              => 'به سیستم وارد نشده‌اید',
'prefsnologintext'          => 'برای تنظیم ترجیحات کاربر باید <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} به سیستم وارد شوید]</span>.',
'prefsreset'                => 'ترجیحات از حافظه میزان شد.',
'qbsettings'                => 'تنظیمات نوار سریع',
'qbsettings-none'           => 'نباشد',
'qbsettings-fixedleft'      => 'ثابت چپ',
'qbsettings-fixedright'     => 'ثابت راست',
'qbsettings-floatingleft'   => 'شناور چپ',
'qbsettings-floatingright'  => 'شناور راست',
'changepassword'            => 'تغییر گذرواژه',
'skin'                      => 'پوسته',
'skin-preview'              => 'پیش‌نمایش',
'math'                      => 'نمایش ریاضیات',
'dateformat'                => 'قالب تاریخ',
'datedefault'               => 'بدون ترجیح',
'datetime'                  => 'تاریخ و زمان',
'math_failure'              => 'شکست در تجزیه',
'math_unknown_error'        => 'خطای ناشناخته',
'math_unknown_function'     => 'تابع ناشناختهٔ',
'math_lexing_error'         => 'خطای lexing',
'math_syntax_error'         => 'خطای نحوی',
'math_image_error'          => 'تبدیل به PNG شکست خورد',
'math_bad_tmpdir'           => 'امکان ایجاد یا نوشتن اطلاعات در پوشه موقت (temp) ریاضی وجود ندارد.',
'math_bad_output'           => 'امکان ایجاد یا نوشتن اطلاعات در پوشه خروجی (output) ریاضی وجود ندارد.',
'math_notexvc'              => 'برنامهٔ اجرایی texvc موجود نیست. برای اطلاعات بیشتر به <span dir=ltr>math/README</span> مراجعه کنید.',
'prefs-personal'            => 'داده‌های کاربر',
'prefs-rc'                  => 'تغییرات اخیر',
'prefs-watchlist'           => 'فهرست پی‌گیری‌ها',
'prefs-watchlist-days'      => 'تعداد روزهایی که باید در فهرست پی‌گیری‌ها نمایش داده شود:',
'prefs-watchlist-days-max'  => '(حداکثر ۷ روز)',
'prefs-watchlist-edits'     => 'تعداد ویرایشهای نشان‌داده در فهرست پی‌گیری‌های گسترش‌یافته:',
'prefs-watchlist-edits-max' => '(حداکثر تعداد: ۱۰۰۰)',
'prefs-misc'                => 'تنظیمات متفرقه',
'prefs-resetpass'           => 'تغییر گذرواژه',
'saveprefs'                 => 'ذخیرهٔ ترجیحات',
'resetprefs'                => 'صفر کردن ترجیحات',
'restoreprefs'              => 'باز گرداندن تمام تنظیمات پیش‌فرض',
'textboxsize'               => 'ابعاد جعبهٔ متن',
'prefs-edit-boxsize'        => 'اندازهٔ پنجرهٔ ویرایش.',
'rows'                      => 'تعداد سطرها',
'columns'                   => 'تعداد ستون‌ها',
'searchresultshead'         => 'تنظیمات نتیجهٔ جستجو',
'resultsperpage'            => 'تعداد نتایج در هر صفحه',
'contextlines'              => 'تعداد سطرها در هر نتیجه',
'contextchars'              => 'تعداد نویسه‌های اطراف در سطر',
'stub-threshold'            => 'آستانهٔ ویرایش پیوندهای <a href="#" class="stub">ناقص</a>:',
'recentchangesdays'         => 'تعداد روزهای نمایش داده‌شده در تغییرات اخیر:',
'recentchangesdays-max'     => '(حداکثر $1 {{PLURAL:$1|روز|روز}})',
'recentchangescount'        => 'تعداد عناوین در تغییرات اخیر، تاریخچهٔ صفحه‌ها، و سیاهه‌ها، به طور پیش‌فرض:',
'savedprefs'                => 'ترجیحات شما ذخیره شد.',
'timezonelegend'            => 'منطقهٔ زمانی',
'timezonetext'              => 'تفاوت تعداد ساعت زمان محلی‌تان با زمان خادم (وقت گرینیچ) را وارد کنید.',
'localtime'                 => 'زمان محلی:',
'timezoneselect'            => 'منطقه زمانی:',
'timezoneuseserverdefault'  => 'استفاده از پیش‌فرض کارگزار',
'timezoneuseoffset'         => 'دیگر (اختلاف را مشخص کنید)',
'timezoneoffset'            => 'اختلاف¹:',
'servertime'                => 'زمان کارگزار:',
'guesstimezone'             => 'از مرورگر گرفته شود',
'allowemail'                => 'امکان دریافت پست الکترونیکی از دیگر کاربران',
'prefs-searchoptions'       => 'گزینه‌های جستجو',
'prefs-namespaces'          => 'فضاهای نام',
'defaultns'                 => 'به طور پیش‌فرض در این فضاهای نام جستجو شود:',
'default'                   => 'پیش‌فرض',
'files'                     => 'پرونده‌ها',
'prefs-custom-css'          => 'سی‌اس‌اس شخصی',
'prefs-custom-js'           => 'جاوااسکریپت شخصی',

# User rights
'userrights'                  => 'مدیریت اختیارات کاربر', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'مدیریت گروه‌های کاربری',
'userrights-user-editname'    => 'یک نام کاربری وارد کنید:',
'editusergroup'               => 'ویرایش گروه‌های کاربری',
'editinguser'                 => "تغییر اختیارات کاربری برای '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'ویرایش گروه‌های کاربری',
'saveusergroups'              => 'ثبت گروه‌های کاربری',
'userrights-groupsmember'     => 'عضو:',
'userrights-groups-help'      => 'شما می‌توانید گروه‌هایی که کاربر در آن قرار دارد را تغییر دهید.
یک جعبه علامت خورده به این معنی است که کاربر در آن گروه قرار دارد.
یک جعبه خالی به این معنی است که کاربر در آن گروه قرار ندارد.',
'userrights-reason'           => 'علت تغییر:',
'userrights-no-interwiki'     => 'شما اجازه تغییر اختیارات کاربران دیگر ویکی‌ها را ندارید.',
'userrights-nodatabase'       => 'پایگاه داده $1 وجود ندارد یا محلی نیست.',
'userrights-nologin'          => 'شما باید با یک حساب کاربری دارای اختیار مدیریتی [[Special:UserLogin|به سیستم وارد شوید]] تا بتوانید اختیارات کاربران را تعیین کنید.',
'userrights-notallowed'       => 'حساب کاربری شما اجازه تغییر اختیارات کاربری را ندارد.',
'userrights-changeable-col'   => 'گروه‌هایی که می‌توانید تغییر دهید',
'userrights-unchangeable-col' => 'گروه‌هایی که نمی‌توانید تغییر دهید',

# Groups
'group'               => 'گروه:',
'group-user'          => 'کاربرها',
'group-autoconfirmed' => 'کاربران تاییدشده',
'group-bot'           => 'ربات‌ها',
'group-sysop'         => 'مدیران',
'group-bureaucrat'    => 'دیوان‌سالاران',
'group-suppress'      => 'ناظران',
'group-all'           => '(همه)',

'group-user-member'          => 'کاربر',
'group-autoconfirmed-member' => 'کاربر تاییدشده',
'group-bot-member'           => 'ربات',
'group-sysop-member'         => 'مدیر',
'group-bureaucrat-member'    => 'دیوان‌سالار',
'group-suppress-member'      => 'ناظر',

'grouppage-user'          => '{{ns:project}}:کاربران',
'grouppage-autoconfirmed' => '{{ns:project}}:کاربران تاییدشده',
'grouppage-bot'           => '{{ns:project}}:ربات‌ها',
'grouppage-sysop'         => '{{ns:project}}:مدیران',
'grouppage-bureaucrat'    => '{{ns:project}}:دیوان‌سالارها',
'grouppage-suppress'      => '{{ns:project}}:نظارت',

# Rights
'right-read'                 => 'خواندن صفحه',
'right-edit'                 => 'ویرایش صفحه',
'right-createpage'           => 'ایجاد صفحه (در مورد صفحه‌های غیر بحث)',
'right-createtalk'           => 'ایجاد صفحهٔ بحث',
'right-createaccount'        => 'ایجاد حساب کاربری',
'right-minoredit'            => 'علامت زدن ویرایش‌ها به صورت جزئی',
'right-move'                 => 'انتقال صفحه',
'right-move-subpages'        => 'انتقال صفحه‌ها به همراه زیر‌صفحه‌هایشان',
'right-move-rootuserpages'   => 'انتقال صفحه‌های کاربری سرشاخه',
'right-movefile'             => 'انتقال پرونده‌ها',
'right-suppressredirect'     => 'انتقال صفحه بدون ایجاد تغییر مسیر از نام قبلی',
'right-upload'               => 'بارگذاری پرونده',
'right-reupload'             => 'بارگذاری مجدد پرونده‌ای که از قبل وجود دارد',
'right-reupload-own'         => 'بارگذاری مجدد پرونده‌ای که پیش از این توسط همان کاربر بارگذاری شده‌است',
'right-reupload-shared'      => 'باطل ساختن پرونده‌های مشترک به صورت محلی',
'right-upload_by_url'        => 'بارگذاری پرونده از یک نشانی اینترنتی (URL)',
'right-purge'                => 'خالی کردن میانگیر صفحه بدون مشاهدهٔ صفحهٔ تایید',
'right-autoconfirmed'        => 'ویرایش صفحه‌های نیمه حفاظت‌شده',
'right-bot'                  => 'تلقی‌شده به عنوان یک فرآیند خودکار',
'right-nominornewtalk'       => 'ویرایش جزئی صفحه‌های بحث به شکلی که باعث اعلان پیغام جدید نشود',
'right-apihighlimits'        => 'استفاده از حداکثر محدودیت API',
'right-writeapi'             => 'استفاده از API مربوط به نوشتن',
'right-delete'               => 'حذف صفحه',
'right-bigdelete'            => 'حذف صفحه‌هایی که تاریخچهٔ بزرگی دارند',
'right-deleterevision'       => 'حذف و احیای نسخه‌های خاصی از صفحه',
'right-deletedhistory'       => 'مشاهدهٔ موارد حذف شده از تاریخچه، بدون دیدن متن آن‌ها',
'right-browsearchive'        => 'جستجوی صفحه‌های حذف شده',
'right-undelete'             => 'احیای صفحهٔ حذف شده',
'right-suppressrevision'     => 'بازبینی و احیای ویرایش‌هایی که از مدیران پنهان شده‌اند',
'right-suppressionlog'       => 'مشاهدهٔ سیاهه‌های خصوصی',
'right-block'                => 'قطع دسترسی ویرایشی دیگر کاربران',
'right-blockemail'           => 'قطع دسترسی دیگر کاربران برای ارسال پست الکترونیکی',
'right-hideuser'             => 'قطع دسترسی کاربر و مخفی کردن آن از دید عموم',
'right-ipblock-exempt'       => 'تاثیر نپذیرفتن از قطع دسترسی‌های IP، خودکار یا فاصله‌ای',
'right-proxyunbannable'      => 'تاثیر نپذیرفتن از قطع دسترسی خودکار پروکسی‌ها',
'right-protect'              => 'تغییر میزان حفاظت صفحه‌ها و ویرایش صفحه‌های حفاظت شده',
'right-editprotected'        => 'ویرایش صفحه‌های حفاظت شده (به شرط نبود حفاظت آبشاری)',
'right-editinterface'        => 'ویرایش رابط کاربری',
'right-editusercssjs'        => 'ویرایش صفحه‌های CSS و JS دیگر کاربرها',
'right-rollback'             => 'واگردانی سریع ویرایش‌های آخرین کاربری که یک صفحه را ویرایش کرده‌است',
'right-markbotedits'         => 'علامت زدن ویرایش‌های واگردانی شده به عنوان ویرایش ربات',
'right-noratelimit'          => 'تاثیر ناپذیر از محدودیت سرعت',
'right-import'               => 'وارد کردن صفحه از ویکی‌های دیگر',
'right-importupload'         => 'وارد کردن صفحه از طریق بارگذاری پرونده',
'right-patrol'               => 'علامت زدن ویرایش‌های گشت خورده',
'right-autopatrol'           => 'علامت زدن خودکار ویرایش‌ها به عنوان گشت خورده',
'right-patrolmarks'          => 'مشاهدهٔ علامت گشت تغییرات اخیر',
'right-unwatchedpages'       => 'مشاهدهٔ فهرست صفحه‌هایی که پیگیری نمی‌شوند',
'right-trackback'            => 'ثبت یک بازتاب',
'right-mergehistory'         => 'ادغام تاریخچهٔ صفحه‌ها',
'right-userrights'           => 'ویرایش تمام اختیارات کاربرها',
'right-userrights-interwiki' => 'ویرایش اختیارات کاربرهای ویکی‌های دیگر',
'right-siteadmin'            => 'قفل کردن و باز کردن پایگاه داده',

# User rights log
'rightslog'      => 'سیاههٔ اختیارات کاربر',
'rightslogtext'  => 'این سیاههٔ تغییرات اختیارات کاربر است.',
'rightslogentry' => 'عضویت $1 از گروه $2 به $3 تغییر داده شد.',
'rightsnone'     => '(هیچ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'خواندن این صفحه',
'action-edit'                 => 'ویرایش این صفحه',
'action-createpage'           => 'ایجاد صفحه',
'action-createtalk'           => 'ایجاد صفحه‌های بحث',
'action-createaccount'        => 'ایجاد این حساب کاربری',
'action-minoredit'            => 'علامت زدن این ویرایش به عنوان جزئی',
'action-move'                 => 'انتقال این صفحه',
'action-move-subpages'        => 'انتقال این صفحه و زیرصفحه‌های آن',
'action-move-rootuserpages'   => 'انتقال صفحه‌های کاربری سرشاخه',
'action-movefile'             => 'این پرونده را انتقال بده',
'action-upload'               => 'بارگذاری این پرونده',
'action-reupload'             => 'نوشتن روی این پرونده موجود',
'action-reupload-shared'      => 'باطل کردن این پرونده روی یک مخزن مشترک',
'action-upload_by_url'        => 'بارگذاری این پرونده از یک نشانی اینترنتی',
'action-writeapi'             => 'استفاده از API نوشتن',
'action-delete'               => 'حذف این صفحه',
'action-deleterevision'       => 'حذف این نسخه',
'action-deletedhistory'       => 'مشاهدهٔ تاریخچهٔ حذف شدهٔ این صفحه',
'action-browsearchive'        => 'جستجوی صفحه‌های حذف شده',
'action-undelete'             => 'احیای این صفحه',
'action-suppressrevision'     => 'مشاهده و احیای ویرایش‌های حذف شده',
'action-suppressionlog'       => 'مشاهدهٔ این سیاههٔ خصوصی',
'action-block'                => 'قطع دسترسی این کاربر برای ویرایش',
'action-protect'              => 'تغییر سطح محافظت از این صفحه',
'action-import'               => 'وارد کردن این صفحه از یک ویکی دیگر',
'action-importupload'         => 'وارد کردن این صفحه از طریق بارگذاری پرونده',
'action-patrol'               => 'علامت زدن ویرایش دیگران به عنوان گشت خورده',
'action-autopatrol'           => 'علامت زدن ویرایش خودتان به عنوان گشت خورده',
'action-unwatchedpages'       => 'مشاهدهٔ صفحه‌های پی‌گیری نشده',
'action-trackback'            => 'ثبت یک بازتاب',
'action-mergehistory'         => 'ادغام تاریخچهٔ این صفحه',
'action-userrights'           => 'ویرایش همهٔ اختیارات کاربری',
'action-userrights-interwiki' => 'ویرایش اختیارات کاربری کاربران یک ویکی دیگر',
'action-siteadmin'            => 'قفل کردن و باز کردن پایگاه داده',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|تغییر|تغییر}}',
'recentchanges'                     => 'تغییرات اخیر',
'recentchanges-legend'              => 'گزینه‌های تغییرات اخیر',
'recentchangestext'                 => 'آخرین تغییرات ویکی را در این صفحه پی‌گیری کنید.',
'recentchanges-feed-description'    => 'ردیابی آخرین تغییرات این ویکی در این خورد.',
'rcnote'                            => "در زیر {{PLURAL:$1|'''۱''' تغییر|آخرین '''$1''' تغییر}} در آخرین {{PLURAL:$2|روز|'''$2''' روز}} را، تا $5، $4 می‌بینید.",
'rcnotefrom'                        => 'در زیر تغییرات از تاریخ <b>$2</b> آمده‌اند (تا <b>$1</b> مورد نشان داده می‌شود).',
'rclistfrom'                        => 'نمایش تغییرات جدید با شروع از $1',
'rcshowhideminor'                   => 'ویرایش‌های جزئی $1',
'rcshowhidebots'                    => 'ربات‌ها $1',
'rcshowhideliu'                     => 'کاربران ثبت‌نام‌کرده $1',
'rcshowhideanons'                   => 'کاربران ناشناس $1',
'rcshowhidepatr'                    => 'ویرایش‌های گشت‌خورده $1',
'rcshowhidemine'                    => 'ویرایش‌های من $1',
'rclinks'                           => 'نمایش آخرین $1 تغییر در $2 روز اخیر؛ $3',
'diff'                              => 'تفاوت',
'hist'                              => 'تاریخچه',
'hide'                              => 'مخفی شود',
'show'                              => 'نمایش',
'minoreditletter'                   => 'جز',
'newpageletter'                     => 'جد',
'boteditletter'                     => 'ر',
'sectionlink'                       => '←',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|کاربر|کاربر}} پی‌گیری‌کننده]',
'rc_categories'                     => 'محدود به رده‌ها بشود (بین رده‌ها نویسهٔ | را قرار دهید)',
'rc_categories_any'                 => 'هر کدام',
'newsectionsummary'                 => '/* $1 */ بخش تازه',
'rc-enhanced-expand'                => 'نمایش جزئیات (نیازمند جاوااسکریپت)',
'rc-enhanced-hide'                  => 'نفتن جزئیات',

# Recent changes linked
'recentchangeslinked'          => 'تغییرات مرتبط',
'recentchangeslinked-title'    => 'تغییرهای مرتبط با $1',
'recentchangeslinked-backlink' => '→ $1',
'recentchangeslinked-noresult' => 'در بازهٔ ‌زمانی داده‌شده تغییری در صفحه‌های پیوندداده رخ نداده‌است.',
'recentchangeslinked-summary'  => "این صفحهٔ ویژه تغییرات اخیر در صفحه‌های پیوند داده شده به این صفحه را نشان می‌دهد.
صفحه‌های که در فهرست پی‌گیریهای شما باشند به صورت '''سیاه''' نشان داده می‌شوند.",
'recentchangeslinked-page'     => 'نام صفحه:',
'recentchangeslinked-to'       => 'تغییرات صفحه‌های که به صفحه مورد نظر پیوند‌ دارند را نمایش بده',

# Upload
'upload'                      => 'بارگذاری پرونده',
'uploadbtn'                   => 'بارگذاری پرونده',
'reupload'                    => 'بارگذاری دوباره',
'reuploaddesc'                => 'بازگشت به فرم بارگذاری',
'uploadnologin'               => 'به سیستم وارد نشده‌اید',
'uploadnologintext'           => 'برای بار کردن پرونده‌ها باید [[Special:UserLogin|وارد سیستم شوید]].',
'upload_directory_missing'    => 'شاخهٔ بارگذاری ($1) وجود ندارد و قابل ایجاد نیست.',
'upload_directory_read_only'  => 'شاخهٔ بارگذاری ($1) از طرف کارگزار وب قابل نوشتن نیست.',
'uploaderror'                 => 'خطا در بار کردن',
'uploadtext'                  => "از فرم زیر برای بارگذاری کردن پرونده‌های جدید استفاده کنید. برای دیدن پرونده‌هایی که قبلاً بارگذاری شده‌اند به 
[[Special:FileList|فهرست پرونده‌ها]] بروید. بارگذاری و حذف پرونده‌ها هم‌چنین در [[Special:Log/upload|سیاههٔ بارگذاری‌ها]] ثبت می‌شود.

بعد از این که پرونده‌ای را بارگذاری کردید، به این سه شکل می‌توانید آن را در صفحه‌ها استفاده کنید:

*'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''',
*'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>'''
*'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>'''",
'upload-permitted'            => 'انواع مجاز پرونده‌ها: $1.',
'upload-preferred'            => 'انواع ترجیح‌داده شده پرونده‌ها: $1.',
'upload-prohibited'           => 'انواع غیر مجاز پرونده‌ها: $1.',
'uploadlog'                   => 'سیاهه‌ی بارکردن‌ها',
'uploadlogpage'               => 'سیاهه‌ی_بارکردن‌ها',
'uploadlogpagetext'           => 'فهرست زیر فهرستی از آخرین بارکردن‌های پرونده‌های است.
همه‌ی زمان‌های نشان‌داده‌شده زمان خادم هستند (وقت گرینیچ).',
'filename'                    => 'نام پرونده',
'filedesc'                    => 'خلاصه',
'fileuploadsummary'           => 'خلاصه:',
'filereuploadsummary'         => 'تغییرات پرونده:',
'filestatus'                  => 'وضعیت حق تکثیر:',
'filesource'                  => 'منبع:',
'uploadedfiles'               => 'پرونده‌های بارشده',
'ignorewarning'               => 'چشم‌پوشی از هشدار و ذخیرهٔ پرونده.',
'ignorewarnings'              => 'چشم‌پوشی از همهٔ هشدارها',
'minlength1'                  => 'اسم پرونده دست کم باید یک حرف باشد.',
'illegalfilename'             => 'نام پرونده «$1» نویسه‌هایی را شامل می‌شود که در نام صفحه‌ها مجاز نیستند. لطفاً نام پرونده را تغییر دهید و آن را دوباره بار کنید.',
'badfilename'                 => 'نام پرونده به «$1» تغییر کرد.',
'filetype-badmime'            => 'پرونده‌هایی که نوع MIME آن‌ها $1 باشد برای بارگزاری مجاز نیستند.',
'filetype-bad-ie-mime'        => 'این پرونده را نمی‌توانید بارگذاری کنید زیرا اینترنت اکسپلورر آن را به عنوان «$1» تشخیص می‌دهد، که یک نوع پروندهٔ غیر مجاز و احتمالاً خطرناک است.',
'filetype-unwanted-type'      => "&lrm;'''\".\$1\"''' یک نوع پرونده ناخواسته است.
{{PLURAL:\$3|نوع پرونده ترجیح داده شده|انواع پرونده ترجیح داد شده}} از این قرار است: \$2 .",
'filetype-banned-type'        => "&lrm;'''\".\$1\"''' یک نوع پرونده غیرمجاز است.
{{PLURAL:\$3|نوع پرونده مجاز|انواع پرونده مجاز}} از این قرار است: \$2 .",
'filetype-missing'            => 'پرونده پسوند ندارد (مانند &lrm;«.jpg»&lrm;).',
'large-file'                  => 'توصیه می‌شود که پرونده‌ها بزرگتر از $1 نباشند: این پرونده $2 است.',
'largefileserver'             => 'این پرونده از اندازه‌ای که در پیکربندی خادم بعنوان سقف اندازهٔ پرونده درنظر گرفته‌ شده‌است، بزرگ‌تر است.',
'emptyfile'                   => 'پروندهٔ بارشده خالی بنظر می‌رسد. این مساله ممکن است به دلیل خطای تایپی در نام پرونده رخ داده باشد. لطفاً تأیید کنید که می‌خواهید این پرونده را با همین شرایط بار کنید.',
'fileexists'                  => 'در حال حاضر، پرونده‌ای با همین نام موجود است. اگر مطمئن نیستید که می‌خواهید آن پرونده را تغییر دهید، لطفاً <strong><tt>$1</tt></strong> را بررسی کنید.',
'filepageexists'              => 'یک صفحه (و نه تصویر) با این نام وجود دارد، اگر مطمئن نیستید که می‌خواهید آن را تغییر دهید لطفاً <strong><tt>$1</tt></strong> را بررسی کنید.',
'fileexists-extension'        => "پرونده‌ای با نام مشابه وجود دارد.<br />
نام پرونده‌ای که بارگزاری کردید این بود:'''<tt>$1</tt>'''<br />
نام پرونده‌ای که از قبل موجود است این است:'''<tt>$2</tt>'''<br />
لطفاً یک نام دیگر انتخاب کنید.",
'fileexists-thumb'            => "<center>'''تصویر از قبل موجود است'''</center>",
'fileexists-thumbnail-yes'    => 'به نظر می‌رسد که این پرونده، یک تصویر بندانگشتی (thumbnail) از تصویر بزرگتر اصلی باشد. لطفاً پروندهٔ با نام $1 را نگاه کنید. اگر پروندهٔ فعلی، تصویر کوچک‌شدهٔ آن پرونده است، نیازی به بارگذاری تصویر کوچکتر نیست.',
'file-thumbnail-no'           => 'نام پرونده با <strong><tt>$1</tt></strong> آغاز می‌شود.
به نظر می‌رسد که این پرونده، یک تصویر بندانگشتی <i>(thumbnail)</i> از تصویر بزرگتر اصلی باشد.
اگر تصویر با اندازهٔ اصلی را دارید، آن را بارگذاری کنید؛ در غیر این صورت، نام پرونده را تغییر دهید.',
'fileexists-forbidden'        => 'در حال حاضر، پرونده‌ای به همین نام وجود دارد، و قابل رونویسی نیست.
اگر هم‌چنان می‌خواهید که پروندهٔ خود را بارگذاری کنید، لطفاً برگردید و نام دیگری برگزینید.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'در حال‌ حاضر، پرونده‌ای با همین نام در انبارهٔ مشترک پرونده‌ها وجود دارد.
اگر هنوز می‌خواهید پرونده خود را بار کنید، لطفاً برگردید و پروندهٔ موردنظر خود را با نام دیگری بار کنید. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'به نظر می‌رسد این پرونده نسخه‌ای تکراری از {{PLURAL:$1|پروندهٔ|پرونده‌های}} زیر باشد:',
'file-deleted-duplicate'      => 'یک پرونده نظیر این پرونده ([[$1]]) قبلاً حذف شده‌است. شما باید تاریخچهٔ حذف آن پرونده را قبل از بارگذاری مجدد آن ببینید.',
'successfulupload'            => 'بار کردن با موفقیت انجام شد',
'uploadwarning'               => 'هشدار بار کردن',
'savefile'                    => 'ذخیره‌ی پرونده',
'uploadedimage'               => '«[[$1]]» بار شد',
'overwroteimage'              => 'نسخه جدیدی از  «[[$1]]» را بارگذاری کرد.',
'uploaddisabled'              => 'شرمنده، بار کردن از کار افتاده است.',
'uploaddisabledtext'          => 'امکان بارگذاری پرونده غیرفعال شده‌است.',
'php-uploaddisabledtext'      => 'بارگذاری پرونده‌های پی‌اچ‌پی غیر فعال است. لطفاً تنظیمات file_uploads را بررسی کنید.',
'uploadscripted'              => 'این صفحه حاوی اسکریپت یا کدی اچ‌تی‌ام‌ال است که ممکن است به‌نادرست توسط مرورگر وب تفسیر شود.',
'uploadcorrupt'               => 'این پرونده خراب است و یا پسوندی اشتباه دارد. لطفاً پرونده را بررسی نمایید و آن را دوباره بار کنید.',
'uploadvirus'                 => 'این پرونده ویروس دارد! جزئیات : $1',
'sourcefilename'              => 'نام پروندهٔ اصلی:',
'destfilename'                => 'نام پروندهٔ مقصد:',
'upload-maxfilesize'          => 'حداکثر اندازهٔ پرونده: $1',
'watchthisupload'             => 'پی‌گیری این صفحه',
'filewasdeleted'              => 'پرونده‌ای با همین نام پیشتر بارگذاری و پس از آن پاک شده‌است. بهتر است پیش از بارگذاری مجدد نگاهی به $1 بیندازید.',
'upload-wasdeleted'           => "'''هشدار: شما در حال بارگذاری پرونده‌ای هستید که پیش از این حذف شده است.'''

شما باید بیندیشید که آیا بارگذاری مجدد پرونده مناسب است یا خیر.
سیاهه حذف مربوط به این پرونده در زیر آمده است:",
'filename-bad-prefix'         => "نام پرونده‌ای که بارگذاری می‌کنید با '''$1''' آغاز می‌شود که یک پیشوند مخصوص تصاویر ثبت شده توسط دوربین‌های دیجیتال است.
لطفاً نامی بهتر برای پرونده برگزینید.",

'upload-proto-error'      => 'قرارداد نادرست',
'upload-proto-error-text' => 'بارگذاری از دوردست به نشانی‌هایی که با <code dir=ltr>http://</code> یا <code dir=ltr>ftp://</code> آغاز شوند نیاز دارد.',
'upload-file-error'       => 'خطای داخلی',
'upload-file-error-text'  => 'هنگام تلاش برای ایجاد یک پروندهٔ  موقت در کارگزار یک خطای داخلی رخ داد. لطفاً با مدیر سیستم تماس بگیرید.',
'upload-misc-error'       => 'خطایی نامعلوم در بارگذاری',
'upload-misc-error-text'  => 'هنگام بارگذاری، خطایی نامعلوم رخ داد. لطفاً اطمینان حاصل کنید که نشانی (URL) معتبر و قابل دسترسی است و بعد دوباره تلاش کنید. اگر مشکل همچنان برقرار بود با یکی از مدیران سیستم تماس بگیرید.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'دسترسی به URL ممکن نشد.',
'upload-curl-error6-text'  => 'URL داده شده قابل دسترسی نیست. لطفاً درستی آن و اینکه وب‌گاه برقرار است را بازرسی کنید.',
'upload-curl-error28'      => 'زمان بارگذاری سر آمد.',
'upload-curl-error28-text' => 'این وب‌گاه بیش از اندازه در پاسخ تعلل کرد. لطفاً بررسی کنید که آیا وب‌گاه فعال و برخط است یا نه. سپس لختی درنگ کنید و دوباره تلاش نمایید. شاید بد نباشد که در زمان خلوت‌تری دوباره تلاش کنید.',

'license'            => 'اجازه‌نامه:',
'nolicense'          => 'هیچیک انتخاب نشده‌است.',
'license-nopreview'  => '(پیش‌نمایش وجود ندارد)',
'upload_source_url'  => '(یک نشانی اینترنتی معتبر و قابل دسترسی برای عموم)',
'upload_source_file' => '(پرونده‌ای در رایانهٔ شما)',

# Special:ListFiles
'listfiles-summary'     => 'این صفحهٔ ویژه تمام پرونده‌های بارگذاری شده را نمایش می‌دهد.
به طور پیش‌فرض آخرین پرونده‌های بارگذاری شده در بالای فهرست نمایش می‌یابند.
یک کلیک روی عنوان ستون ترتیب را تغییر می‌دهد.',
'listfiles_search_for'  => 'جستجو به دنبال نام پرونده چندرسانه‌ای:',
'imgfile'               => 'پرونده',
'listfiles'             => 'فهرست پرونده‌ها',
'listfiles_date'        => 'تاریخ',
'listfiles_name'        => 'نام',
'listfiles_user'        => 'کاربر',
'listfiles_size'        => 'اندازه',
'listfiles_description' => 'توضیح',
'listfiles_count'       => 'نسخه‌ها',

# File description page
'filehist'                       => 'تاریخچه پرونده',
'filehist-help'                  => 'روی تاریخ‌ها کلیک کنید تا نسخهٔ مربوط را ببینید.',
'filehist-deleteall'             => 'حذف همه',
'filehist-deleteone'             => 'حذف این مورد',
'filehist-revert'                => 'واگردانی',
'filehist-current'               => 'نسخهٔ فعلی',
'filehist-datetime'              => 'تاریخ',
'filehist-thumb'                 => 'بند انگشتی',
'filehist-thumbtext'             => 'تصویر بند انگشتی از نسخه تا $1',
'filehist-nothumb'               => 'فاقد بند انگشتی',
'filehist-user'                  => 'کاربر',
'filehist-dimensions'            => 'ابعاد',
'filehist-filesize'              => 'اندازه پرونده',
'filehist-comment'               => 'توضیح',
'imagelinks'                     => 'پیوندهای پرونده',
'linkstoimage'                   => '{{PLURAL:$1|صفحهٔ|صفحه‌های}} زیر به این تصویر پیوند {{PLURAL:$1|دارد|دارند}}:',
'linkstoimage-more'              => 'بیش از صفحه به این پرونده پیوند {{PLURAL:$1|می‌دهد|می‌دهند}}.
فهرست زیر تنها {{PLURAL:$1|اولین پیوند|اولین $1 پیوند}} به این صفحه را نشان می‌دهد.
[[Special:WhatLinksHere/$2|قهرست کامل]] نیز موجود است.',
'nolinkstoimage'                 => 'هیچ صفحه‌ای به این تصویر پیوند ندارد.',
'morelinkstoimage'               => '[[Special:WhatLinksHere/$1|پیوندهای دیگر]] به این پرونده را ببینید.',
'redirectstofile'                => '{{PLURAL:$1|پروندهٔ|پرونده‌های}} زیر به این صفحه تغییر مسیر {{PLURAL:$1|می‌دهد|می‌دهند}}:',
'duplicatesoffile'               => '{{PLURAL:$1|پروندهٔ|پرونده‌های}} زیر نسخهٔ تکراری این پرونده {{PLURAL:$1|است|هستند}}:',
'sharedupload'                   => 'این پرونده یک بارگذاری اشتراکی است و ممکن است از طریق دیگر پروژه‌ها هم قابل دسترسی باشد.',
'shareduploadwiki'               => 'لطفاً برای اطلاعات بیشتر $1 را ببینید.',
'shareduploadwiki-desc'          => 'توضیحات در مورد $1 آن در زیر نشان داده شده‌است.',
'shareduploadwiki-linktext'      => 'صفحهٔ توصیف پرونده',
'shareduploadduplicate'          => 'این پرونده نسخه‌ای تکراری از $1 است که در انبار مشترک وجود دارد.',
'shareduploadduplicate-linktext' => 'پرونده‌ای دیگر',
'shareduploadconflict'           => 'این پرونده هم‌نام با $1 است که در انبار مشترک وجود دارد.',
'shareduploadconflict-linktext'  => 'پرونده‌ای دیگر',
'noimage'                        => 'هیچ پرونده‌ای با این نام موجود نیست، اما شما می‌توانید $1',
'noimage-linktext'               => 'آن را بار کنید',
'uploadnewversion-linktext'      => 'بارکردن نسخهٔ جدیدی از پرونده',
'imagepage-searchdupe'           => 'جستجو برای پرونده‌های تکراری',

# File reversion
'filerevert'                => 'واگردانی $1',
'filerevert-backlink'       => '→ $1',
'filerevert-legend'         => 'واگردانی پرونده',
'filerevert-intro'          => "شما در حال واگردانی '''[[Media:$1|$1]]''' به [نسخهٔ $4 مورخ $2، $3] هستید.",
'filerevert-comment'        => 'توضیح',
'filerevert-defaultcomment' => 'واگردانی به نسخهٔ $1، $2',
'filerevert-submit'         => 'برو',
'filerevert-success'        => "''[[Media:$1|$1]]''' به [نسخهٔ $4 مورخ $2، $3] واگردانده شد.",
'filerevert-badversion'     => 'نسخهٔ قدیمی‌تری از این پرونده وجود نداشت.',

# File deletion
'filedelete'                  => 'حذف $1',
'filedelete-backlink'         => '→ $1',
'filedelete-legend'           => 'حذف پرونده',
'filedelete-intro'            => "شما در حال حذف کردن پروندهٔ '''[[Media:$1|$1]]''' به همراه تمام تاریخچه‌اش هستید.",
'filedelete-intro-old'        => '<span class="plainlinks">شما در حال حذف نسخه \'\'\'[[Media:$1|$1]]\'\'\' به تاریخ [$4 $3، $2] هستید.</span>',
'filedelete-comment'          => 'توضیح:',
'filedelete-submit'           => 'حذف',
'filedelete-success'          => "'''$1''' حذف شد.",
'filedelete-success-old'      => "نسخهٔ '''[[Media:$1|$1]]''' به تاریخ $3، $2 حذف شد.",
'filedelete-nofile'           => "'''$1''' وجود ندارد.",
'filedelete-nofile-old'       => "نسخه بایگانی شده‌ای از '''$1''' با مشخصات داده شده، وجود ندارد..",
'filedelete-otherreason'      => 'دلیل دیگر/اضافی:',
'filedelete-reason-otherlist' => 'دیگر',
'filedelete-reason-dropdown'  => '
*دلایل متداول حذف
** نقض حق تکثیر
** پرونده تکراری',
'filedelete-edit-reasonlist'  => 'ویرایش فهرست دلایل',

# MIME search
'mimesearch'         => 'جستجوی بر اساس MIME',
'mimesearch-summary' => 'با کمک این صفحه شما می‌توانید پرونده‌هایی که یک نوع MIME به خصوص دارند را پیدا کنید. باید اطلاعات MIME را به صورت contenttype/subtype وارد کنید، نظیر <tt>image/jpeg</tt>.',
'mimetype'           => 'نوع MIME:',
'download'           => 'بارگیری',

# Unwatched pages
'unwatchedpages' => 'صفحه‌های پی‌گیری‌نشده',

# List redirects
'listredirects' => 'فهرست صفحه‌های تغییرمسیر',

# Unused templates
'unusedtemplates'     => 'الگوهای استفاده‌نشده',
'unusedtemplatestext' => 'این صفحه همهٔ صفحه‌هایی در فضای نام {{ns:template}} را که در هیچ صفحه‌ای به کار نرفته‌اند، فهرست می‌کند.
به یاد داشته باشید که پیش از پاک‌کردن این صفحه‌ها پیوندهای دیگر به آنها را هم وارسی کنید.',
'unusedtemplateswlh'  => 'پیوندهای دیگر',

# Random page
'randompage'         => 'صفحهٔ تصادفی',
'randompage-nopages' => 'هیچ صفحه‌ای در فضای نام «$1» موجود نیست.',

# Random redirect
'randomredirect'         => 'تغییرمسیر تصادفی',
'randomredirect-nopages' => 'هیج صفحهٔ تغییر مسیری در فضای نام «$1» موجود نیست.',

# Statistics
'statistics'                   => 'آمار',
'statistics-header-pages'      => 'آمار صفحه‌ها',
'statistics-header-edits'      => 'آمار ویرایشات',
'statistics-header-views'      => 'آمار بازدیدها',
'statistics-header-users'      => 'آمار کاربران',
'statistics-articles'          => 'صفحه محتویات',
'statistics-pages'             => 'صفحه‌ها',
'statistics-pages-desc'        => 'تمام صفحه‌های این ویکی، از جمله صفحه‌های بحث، تغییر مسیر و غیره',
'statistics-files'             => 'پرونده‌های بارگذاری شده',
'statistics-edits'             => 'از زمانی که نرم‌افزار ارتقا یافته‌است تعداد ویرایشات بر روی {{SITENAME}} این تعداد بوده است:',
'statistics-edits-average'     => 'متوسط ویرایش‌ها بر روی صفحات',
'statistics-views-total'       => 'مجموع بازدیدها',
'statistics-views-peredit'     => 'تعداد بازدید به ازای هر ویرایش',
'statistics-jobqueue'          => 'طول [http://www.mediawiki.org/wiki/Manual:Job_queue صف کار]',
'statistics-users'             => '[[ویژه:ListUsers|کاربران]] ثبت‌نام کرده',
'statistics-users-active'      => 'کاربران فعال',
'statistics-users-active-desc' => 'کاربرانی که در {{PLURAL:$1|روز|$1 روز}} قبل فعالیتی انجام داده‌اند',
'statistics-mostpopular'       => 'صفحه‌هایی که بیشترین تعداد بازدیدکننده را داشته‌اند',

'disambiguations'      => 'صفحه‌های ابهام‌زدایی',
'disambiguationspage'  => 'Template:ابهام‌زدایی',
'disambiguations-text' => "
صفحه‌های زیر به یک '''صفحهٔ ابهام‌زدایی''' پیوند دارند.
این صفحه‌ها باید به جای آن به یک عنوان مناسب پیوند داشته باشند.<br />
یک صفحه در صورتی که از الگویی که در [[MediaWiki:Disambiguationspage]] پیوند شده باشد در آن استفاده شده باشد به عنوان یک صفحهٔ ابهام‌زدایی در نظر گرفته می‌شود.",

'doubleredirects'            => 'تغییرمسیرهای دوتایی',
'doubleredirectstext'        => 'هر سطر دربردارندهٔ پیوندهایی به تغییر مسیر اول و دوم و همچنین خط نخست متن تغییر مسیر دوم است. معمولاً صفحهٔ مقصد واقعی که نخستین تغییرمسیر باید به آن باشد به این گونه مشخص می‌شود.',
'double-redirect-fixed-move' => '[[$1]] انتقال داده شده‌است، و در حال حاضر تغییر مسیری به [[$2]] است',
'double-redirect-fixer'      => 'تعمیرکار تغییر مسیرها',

'brokenredirects'        => 'تغییرمسیرهای خراب',
'brokenredirectstext'    => 'تغییرمسیرهای زیر به یک صفحهٔ ناموجود پیوند دارند:',
'brokenredirects-edit'   => '(ویرایش)',
'brokenredirects-delete' => '(حذف)',

'withoutinterwiki'         => 'صفحه‌های بدون پیوند میان‌ویکی',
'withoutinterwiki-summary' => 'این صفحه‌ها پیوندی به صفحه‌ای به زبان دیگر نمی‌دارند:',
'withoutinterwiki-legend'  => 'پیشوند',
'withoutinterwiki-submit'  => 'نمایش',

'fewestrevisions' => 'مقاله‌های دارای کمترین شمار ویرایش',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بایت|بایت}}',
'ncategories'             => '$1 {{PLURAL:$1|رده|رده}}',
'nlinks'                  => '$1 {{PLURAL:$1|پیوند|پیوند}}',
'nmembers'                => '$1 {{PLURAL:$1|عضو|عضو}}',
'nrevisions'              => '$1 {{PLURAL:$1|ویرایش|ویرایش}}',
'nviews'                  => '$1 {{PLURAL:$1|نمایش|نمایش}}',
'specialpage-empty'       => 'این صفحه تُهی‌است.',
'lonelypages'             => 'صفحه‌های یتیم',
'lonelypagestext'         => 'به صفحه‌های زیر از هیچ صفحهٔ دیگری در {{SITENAME}} پیوند داده نشده‌است و در هیچ صفحهٔ دیگری گنجانده نشده‌اند.',
'uncategorizedpages'      => 'صفحه‌های رده‌بندی نشده',
'uncategorizedcategories' => 'رده‌های رده‌بندی نشده',
'uncategorizedimages'     => 'تصویرهای رده‌بندی‌نشده',
'uncategorizedtemplates'  => 'الگوهای رده‌بندی نشده',
'unusedcategories'        => 'رده‌های بی‌استفاده',
'unusedimages'            => 'تصویرهای استفاده‌نشده',
'popularpages'            => 'صفحه‌های محبوب',
'wantedcategories'        => 'رده‌های مورد نیاز',
'wantedpages'             => 'صفحه‌های مورد نیاز',
'wantedfiles'             => 'پرونده‌های مورد نیاز',
'wantedtemplates'         => 'الگوهای مورد نیاز',
'mostlinked'              => 'صفحه‌هایی که بیشتر از همه به آنها پیوند شده است',
'mostlinkedcategories'    => 'رده‌هایی که بیشتر از همه به آنها پیوند شده است',
'mostlinkedtemplates'     => 'الگوهایی که بیشتر از همه به آنها پیوند شده است',
'mostcategories'          => 'مقاله‌هایی که بیشترین تعداد رده را دارند',
'mostimages'              => 'تصاویری که بیشتر از همه به آنها پیوند شده است',
'mostrevisions'           => 'مقاله‌هایی که بیشتر از بقیه ویرایش شده‌اند',
'prefixindex'             => 'تمام صفحه‌ها با پیشوند',
'shortpages'              => 'صفحه‌های کوتاه',
'longpages'               => 'صفحه‌های بلند',
'deadendpages'            => 'صفحه‌های بن‌بست',
'deadendpagestext'        => 'صفحه‌های زیر به هیچ صفحهٔ دیگر در این ویکی پیوند ندارند.',
'protectedpages'          => 'صفحه‌های حفاظت‌شده',
'protectedpages-indef'    => 'فقط حفاظت‌های بی‌پایان',
'protectedpages-cascade'  => 'فقط محافظت‌های آبشاری',
'protectedpagestext'      => 'صفحه‌های زیر در برابر ویرایش یا انتقال حفاظت شده‌اند:',
'protectedpagesempty'     => 'در حال حاضر هیچ‌صفحه‌ای محافظت نشده‌است.',
'protectedtitles'         => 'عنوان‌های محافظت شده',
'protectedtitlestext'     => 'عنوان‌های زیر از ایجاد محافظت شده‌اند',
'protectedtitlesempty'    => 'در حال حاضر هیچ عنوانی با این پارامترها محافظت نشده‌است.',
'listusers'               => 'فهرست کاربران',
'listusers-editsonly'     => 'فقط کاربرانی که ویرایش دارند را نشان بده',
'listusers-creationsort'  => 'مرتب کردن بر اساس تاریخ ایجاد',
'usereditcount'           => '$1 {{PLURAL:$1|ویرایش|ویرایش}}',
'usercreated'             => 'ایجاد شده در $1 ساعت $2',
'newpages'                => 'صفحه‌های جدید',
'newpages-username'       => 'نام کاربری:',
'ancientpages'            => 'قدیمی‌ترین صفحه‌ها',
'move'                    => 'انتقال',
'movethispage'            => 'انتقال این صفحه',
'unusedimagestext'        => '<p>لطفاً توجه کنید که وبگاه‌های دیگر از جمله {{SITENAME}}های بین‌المللی ممکن است با URL مستقیم به تصاویر پیوند داشته باشند، و نتیجتاً با وجود استفادهٔ فعال
اینجا فهرست شده باشند.',
'unusedcategoriestext'    => 'این رده‌ها وجود دارند ولی هیچ مقاله یا ردهٔ دیگری از آنها استفاده نمی‌کند.',
'notargettitle'           => 'مقصدی نیست',
'notargettext'            => 'شما صفحهٔ یا کاربر مقصدی برای انجام این عمل روی آن مشخص نکرده‌اید.',
'nopagetitle'             => 'چنین صفحه‌ای وجود ندارد',
'nopagetext'              => 'صفحهٔ هدفی که شما مشخص کردید وجود ندارد.',
'pager-newer-n'           => '{{PLURAL:$1|یک مورد جدیدتر|$1 مورد جدیدتر}}',
'pager-older-n'           => '{{PLURAL:$1|یک مورد قدیمی‌تر|$1 مورد قدیمی‌تر}}',
'suppress'                => 'نظارت',

# Book sources
'booksources'               => 'منابع کتاب',
'booksources-search-legend' => 'جستجوی منابع کتاب',
'booksources-isbn'          => 'شابک:',
'booksources-go'            => 'برو',
'booksources-text'          => 'در زیر فهرستی از پیوندها به وبگاههای دیگر که کتاب‌های نو و دست‌دوم می‌فروشند آمده‌است. همچنین ممکن است اطلاعات بیشتری راجع به کتاب مورد نظر شما بدارند.',
'booksources-invalid-isbn'  => 'شابک داده شده مجاز به نظر نمی‌رسد؛ از جهت اشکالات هنگام کپی کردن از منبع اصلی بررسی کنید.',

# Special:Log
'specialloguserlabel'  => 'کاربر:',
'speciallogtitlelabel' => 'عنوان:',
'log'                  => 'سیاهه‌ها',
'all-logs-page'        => 'تمام سياهه‌ها',
'alllogstext'          => 'نمایش یک‌جای تمام سیاهه‌های موجود در {{SITENAME}}.
می‌توانید با انتخاب نوع سیاهه، نام کاربری (حساس به کوچکی و بزرگی حروف) و صفحه‌های تغییریافته (حساس به بزرگی و کوچکی حروف)، نمایش را محدودتر سازید.',
'logempty'             => 'مورد منطبق با منظور شما در سیاهه یافت نشد.',
'log-title-wildcard'   => 'صفحه‌هایی را جستجو کن که عنوانشان با این عبارت آغاز می‌شود',

# Special:AllPages
'allpages'          => 'همهٔ صفحه‌ها',
'alphaindexline'    => '$1 تا $2',
'nextpage'          => 'صفحهٔ بعد ($1)',
'prevpage'          => 'صفحهٔ قبلی ($1)',
'allpagesfrom'      => 'نمایش صفحه‌ها با شروع از:',
'allpagesto'        => 'نمایش صفحه‌ها با پایان در:',
'allarticles'       => 'همهٔ مقاله‌ها',
'allinnamespace'    => 'همهٔ صفحه‌ها (فضای نام $1)',
'allnotinnamespace' => 'همهٔ صفحه‌ها (که در فضای نام $1 است)',
'allpagesprev'      => 'قبلی',
'allpagesnext'      => 'بعدی',
'allpagessubmit'    => 'برو',
'allpagesprefix'    => 'نمایش صفحه‌های دارای پیشوند:',
'allpagesbadtitle'  => 'عنوان صفحهٔ داده‌شده نامعتبر است یا اینکه دارای پیشوندی بین‌زبانی یا بین‌ویکی‌ای است. ممکن است نویسه‌هایی بدارد که نمی‌توان از آنها در عنوان صفحه‌ها استفاده کرد.',
'allpages-bad-ns'   => '{{SITENAME}} دارای فضای نام «$1» نیست.',

# Special:Categories
'categories'                    => 'رده‌های صفحه',
'categoriespagetext'            => 'رده‌های زیر حاوی صفحه یا پرونده هستند.
[[Special:UnusedCategories|رده‌های استفاده نشده]] در این‌جا نمایش داده نشده‌اند.
هم‌چنین [[Special:WantedCategories|رده‌های مورد نیاز]] را ببینید.',
'categoriesfrom'                => 'نمایش رده‌ها با شروع از:',
'special-categories-sort-count' => 'مرتب کردن بر اساس تعداد',
'special-categories-sort-abc'   => 'مرتب کردن الفبایی',

# Special:DeletedContributions
'deletedcontributions'       => 'مشارکت‌های حذف شده',
'deletedcontributions-title' => 'مشارکت‌های حذف شده',

# Special:LinkSearch
'linksearch'       => 'پیوندهای بیرونی',
'linksearch-pat'   => 'جستجوی الگو:',
'linksearch-ns'    => 'فضای نام:',
'linksearch-ok'    => 'جستجو',
'linksearch-text'  => 'نشانه‌هایی مانند "*.wikipedia.org" را می‌توان استفاده کرد.<br />پروتکل‌های پشتیبانی‌شده: <tt>$1</tt>',
'linksearch-line'  => '$1 از $2 پیوند دارد',
'linksearch-error' => 'نشانه‌ها فقط در ابتدای نام میزبان اینترنتی می‌توانند استفاده شوند.',

# Special:ListUsers
'listusersfrom'      => 'نمایش کاربران با شروع از:',
'listusers-submit'   => 'نمایش',
'listusers-noresult' => 'هیچ کاربری یافت نشد.',

# Special:Log/newusers
'newuserlogpage'              => 'سیاههٔ ایجاد کاربر',
'newuserlogpagetext'          => 'این سیاهه‌ای از نامهای کاربریِ تازه‌ساخته‌شده است',
'newuserlog-byemail'          => 'گذرواژه با پست الکترونیکی ارسال شد',
'newuserlog-create-entry'     => 'کاربر جدید',
'newuserlog-create2-entry'    => 'حساب کاربری $1 را ایجاد کرد',
'newuserlog-autocreate-entry' => 'حساب به طور خودکار ساخته شد',

# Special:ListGroupRights
'listgrouprights'                 => 'اختیارات گروه‌های کاربری',
'listgrouprights-summary'         => 'فهرست زیر شامل گروه‌های کاربری تعریف شده در این ویکی و اختیارات داده شده به آن‌ها است.
اطلاعات بیشتر در مورد هر یک از اختیارات را در [[{{MediaWiki:Listgrouprights-helppage}}]] بیابید.',
'listgrouprights-group'           => 'گروه',
'listgrouprights-rights'          => 'دسترسی‌ها',
'listgrouprights-helppage'        => 'Help:دسترسی‌های گروهی',
'listgrouprights-members'         => '(فهرست اعضا)',
'listgrouprights-addgroup'        => 'می‌تواند این {{PLURAL:$2|گروه|گروه‌ها}} را اضافه کند: $1',
'listgrouprights-removegroup'     => 'می‌تواند این {{PLURAL:$2|گروه|گروه‌ها}} را حذف کند: $1',
'listgrouprights-addgroup-all'    => 'می‌تواند تمام گروه‌ها را اضافه کند',
'listgrouprights-removegroup-all' => 'می‌تواند تمام گروه‌ها را حذف کند',

# E-mail user
'mailnologin'      => 'نشانی‌ای از فرستنده وجود ندارد.',
'mailnologintext'  => 'برای فرستادن پست الکترونیکی به کاربران دیگر باید [[Special:UserLogin|به سیستم وارد شوید]]
و نشانی پست الکترونیکی معتبری در [[Special:Preferences|ترجیحات]]
خود داشته باشید.',
'emailuser'        => 'پست الکترونیکی به این کاربر',
'emailpage'        => 'پست الکترونیکی به کاربر',
'emailpagetext'    => 'شما می‌توانید از فرم زیر برای ارسال یک نامه الکترونیکی به این کاربر استفاده کنید.
نشانی پست الکترونیکی‌ای که در [[Special:Preferences|ترجیحات کاربریتان]] وارد کرده‌اید در نشانی فرستنده (From) نامه خواهد آمد، تا گیرنده بتواند پاسخ دهد.',
'usermailererror'  => 'پست الکترونیکی دچار خطا شد:',
'defemailsubject'  => 'پست الکترونیکی {{SITENAME}}',
'noemailtitle'     => 'نشانی پست الکترونیکی موجود نیست',
'noemailtext'      => 'این کاربر نشانی پست الکترونیکی معتبری مشخص نکرده است،
یا تصمیم گرفته از کاربران دیگر پست الکترونیکی دریافت نکند.',
'nowikiemailtitle' => 'اجازهٔ ارسال نامهٔ الکترونیکی داده نشده‌است',
'nowikiemailtext'  => 'این کاربر انتخاب کرده که از دیگر کاربران نامهٔ الکترونیکی دریافت نکند.',
'email-legend'     => 'ارسال نامه الکترونیکی به یک کاربر دیگر {{SITENAME}}',
'emailfrom'        => 'از:',
'emailto'          => 'به:',
'emailsubject'     => 'عنوان:',
'emailmessage'     => 'پیغام:',
'emailsend'        => 'فرستاده شود',
'emailccme'        => 'رونوشت پیغام را برایم بفرست.',
'emailccsubject'   => 'رونوشت پیغام شما به $1: $2',
'emailsent'        => 'پست الکترونیکی فرستاده شد',
'emailsenttext'    => 'پیغام پست الکترونیکی شما فرستاده شد.',
'emailuserfooter'  => 'این نامهٔ الکترونیکی با استفاده از ویژگی «پست الکترونیکی به کاربر» {{SITENAME}} توسط $1 به $2 فرستاده شد.',

# Watchlist
'watchlist'            => 'فهرست پی‌گیری‌های من',
'mywatchlist'          => 'پی‌گیری‌های من',
'watchlistfor'         => "(برای '''$1''')",
'nowatchlist'          => 'در فهرست پی‌گیریهای شما هیچ موردی نیست.',
'watchlistanontext'    => 'برای مشاهده و ویرایش فهرست پی‌گیری‌های خود از $1 استفاده کنید.',
'watchnologin'         => 'به سیستم وارد نشده‌اید',
'watchnologintext'     => 'برای تغییر فهرست پی‌گیری‌هایتان باید [[Special:UserLogin|به سیستم وارد شوید]].',
'addedwatch'           => 'به فهرست پی‌گیری‌ها اضافه شد',
'addedwatchtext'       => "صفحهٔ «<nowiki>$1</nowiki>» به [[Special:Watchlist|فهرست پی‌گیری‌های]] شما اضافه شد.
تغییرات این صفحه و صفحهٔ بحث متناظرش در آینده در اینجا فهرست خواهد شد. به‌علاوه، این صفحه، برای واضح‌تر دیده شدن در [[Special:RecentChanges|فهرست تغییرات اخیر]] به شکل <b>سیاه</b> خواهد آمد.

اگر بعداً می‌خواستید این صفحه از فهرست پی‌گیریهایتان برداشته شود، روی «'''توقف پی‌گیری'''» در بالای صفحه کلیک کنید.",
'removedwatch'         => 'از فهرست پی‌گیری‌ها برداشته شد',
'removedwatchtext'     => 'صفحهٔ «<nowiki>$1</nowiki>» از فهرست پی‌گیری‌های شما برداشته شد',
'watch'                => 'پی‌گیری',
'watchthispage'        => 'پی‌گیری این صفحه',
'unwatch'              => 'توقف پی‌گیری',
'unwatchthispage'      => 'توقف پی‌گیری این صفحه',
'notanarticle'         => 'مقاله نیست',
'notvisiblerev'        => 'این نسخه حذف شده‌است',
'watchnochange'        => 'هیچ یک از موارد پی‌گیری‌شدهم توسط شما در دورهٔ زمانی نمایش‌یافته ویرایش نشده است.',
'watchlist-details'    => 'بدون احتساب صفحه‌های بحث، {{PLURAL:$1|$1 صفحه|$1 صفحه}} در فهرست پی‌گیری‌های شما قرار {{PLURAL:$1|دارند|دارد}}.',
'wlheader-enotif'      => '*اطلاع‌رسانی ایمیلی امکان‌پذیر است.',
'wlheader-showupdated' => "*صفحه‌هایی که پس از آخرین سرزدنتان به آنها تغییر کرده‌اند '''پررنگ''' نشان داده شده‌اند.",
'watchmethod-recent'   => 'بررسی ویرایش‌های اخیر برای صفحه‌های پی‌گیری‌شده',
'watchmethod-list'     => 'بررسی صفحه‌های پی‌گیری‌شده برای ویرایش‌های اخیر',
'watchlistcontains'    => 'فهرست پی‌گیری‌های شما حاوی $1 {{PLURAL:$1|صفحه|صفحه}} است.',
'iteminvalidname'      => 'مشکل با مورد «$1»، نام نامعتبر است...',
'wlnote'               => 'در زیر آخرین $1 تغییر در $2 ساعت آخر {{PLURAL:$1|آمده‌است|آمده‌اند}}.',
'wlshowlast'           => 'نمایش آخرین $1 ساعت $2 روز $3',
'watchlist-options'    => 'گزینه‌های پیگیری',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'پی‌گیری...',
'unwatching' => 'توقف پی‌گیری...',

'enotif_mailer'                => 'پست الکترونیکی اطلاع‌رسانی {{SITENAME}}',
'enotif_reset'                 => 'علامت‌گذاری همهٔ صفحه‌ها به عنوان بازدید شده',
'enotif_newpagetext'           => 'این یک صفحهٔ تازه‌است.',
'enotif_impersonal_salutation' => 'کاربر {{SITENAME}}',
'changed'                      => 'تغییر یافته',
'created'                      => 'ایجاد شد',
'enotif_subject'               => 'صفحهٔ «$PAGETITLE» {{SITENAME}} به دست $PAGEEDITOR $CHANGEDORCREATED‌است.',
'enotif_lastvisited'           => 'برای دیدن همهٔ تغییرات از آخرین باری که سر زده‌اید $1 را ببینید.',
'enotif_lastdiff'              => 'برای نمایش این تغییر $1 را ببینید.',
'enotif_anon_editor'           => 'کاربر ناشناس $1',
'enotif_body'                  => '$WATCHINGUSERNAME گرامی،

صفحهٔ «$PAGETITLE» {{SITENAME}} در $PAGEEDITDATE به دست $PAGEEDITOR $CHANGEDORCREATED است. برای دیدن نسخهٔ کنونی به $PAGETITLE_URL بروید.

$NEWPAGE

توضیح ویراستار: $PAGESUMMARY $PAGEMINOREDIT

تماس با ویراستار:
نامه: $PAGEEDITOR_EMAIL
ویکی: $PAGEEDITOR_WIKI

تا هنگامی که به صفحه سر نزده‌اید، در صورت رخ‌دادنِ احتمالیِ تغییراتِ بیشتر، اعلانیه‌ای برای شما فرستاده نخواهد شد.
شما همچنین می‌توانید در صفحهٔ پی‌گیری‌های خود پرچم‌های مربوط به آگاهی‌رسانی پستی را صفر کنید.

خاکسار شما،

سامانهٔ آگاهی‌رسانی  {{SITENAME}}.

--
برای تغییر تنظیمات فهرست پی‌گیری‌هایتان به {{fullurl:{{ns:special}}:Watchlist/edit}} بروید.

بازخورد و کمک بیشتر:
{{fullurl:{{ns:help}}:Contents}}',

# Delete
'deletepage'             => 'حذف صفحه',
'confirm'                => 'تأیید',
'excontent'              => "محتوای صفحه این بود: '$1'",
'excontentauthor'        => "محتویات صفحه این بود: '$1' (و تنها مشارکت‌کننده '$2' بود)",
'exbeforeblank'          => "محتوای صفحه قبل از خالی‌کردن '$1' بود.",
'exblank'                => 'صفحه خالی بود',
'delete-confirm'         => 'حذف «$1»',
'delete-backlink'        => '→ $1',
'delete-legend'          => 'حذف',
'historywarning'         => 'هشدار! صفحه‌ای که دارید حذف می‌کنید، تاریخچه‌ای دارد:',
'confirmdeletetext'      => 'شما در حال حذف کردن یک صفحه یا تصویر از پایگاه‌ داده همراه با تمام تاریخچهٔ آن هستید. لطفاً این عمل را تأیید کنید و اطمینان حاصل کنید که عواقب این کار را می‌دانید و این عمل را مطابق با [[{{MediaWiki:Policy-url}}|سیاست‌ها]] انجام می‌دهید.',
'actioncomplete'         => 'عمل انجام شد.',
'deletedtext'            => '«<nowiki>$1</nowiki>» حذف شده است.
برای سابقهٔ حذف‌های اخیر به $2 مراجعه کنید.',
'deletedarticle'         => '«$1» حذف شد',
'suppressedarticle'      => '«[[$1]]» را فرونشاند',
'dellogpage'             => 'سیاههٔ_حذف',
'dellogpagetext'         => 'فهرست زیر فهرستی از آخرین حذف‌هاست.
همهٔ زمان‌های نشان‌داده‌شده زمان خادم (وقت گرینویچ) است.',
'deletionlog'            => 'سیاههٔ حذف',
'reverted'               => 'به نسخهٔ قدیمی‌تر واگردانده شد.',
'deletecomment'          => 'توضیح حذف:',
'deleteotherreason'      => 'دلیل دیگر/اضافی:',
'deletereasonotherlist'  => 'دیگر',
'deletereason-dropdown'  => '
*دلایل متداول حذف
** درخواست کاربر
** نقض حق تکثیر
** خرابکاری',
'delete-edit-reasonlist' => 'ویرایش فهرست دلایل',
'delete-toobig'          => 'این صفحه تاریخچهٔ ویرایشی بزرگی دارد، که شامل بیش از $1 {{PLURAL:$1|نسخه|نسخه}} است.
به منظور جلوگیری از خرابکاری احتمالی حذف این گونه صفحه‌ها در {{SITENAME}} محدود شده‌است.',
'delete-warning-toobig'  => 'این صفحه تاریخچهٔ ویرایشی بزرگی دارد، که شامل بیش از $1 {{PLURAL:$1|نسخه|نسخه}} است.
حذف آن ممکن است که عملکرد پایگاه دادهٔ {{SITENAME}} را مختل کند;
با احتیاط ادامه دهید.',

# Rollback
'rollback'         => 'واگردانی ویرایش‌ها',
'rollback_short'   => 'واگرد',
'rollbacklink'     => 'واگردانی',
'rollbackfailed'   => 'واگردانی نشد',
'cantrollback'     => 'نمی‌توان ویرایش را واگرداند. آخرین مشارکت‌کننده تنها مؤلف این مقاله است.',
'alreadyrolled'    => 'واگردانی آخرین ویرایش [[$1]] بوسیلهٔ [[User:$2|$2]] ([[User talk:$2|بحث]]) ممکن نیست؛ پیش از این شخص دیگری مقاله را ویرایش یا واگردانی کرده است.

آخرین ویرایش توسط [[User:$3|$3]] ([[User talk:$3|بحث]]).',
'editcomment'      => 'خلاصهٔ ویرایش این بود: «<i>$1</i>».', # only shown if there is an edit comment
'revertpage'       => 'ویرایش $2 واگردانده شد به آخرین تغییری که  $1 انجام داده بود', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => 'ویرایش‌های $1 واگردانی شد؛ صفحه به ویرایش $2 برگردانده شد.',
'sessionfailure'   => 'بنظر می‌رسد مشکلی در مورد نشست کاربری شما وجود دارد؛ عمل درخواست شده بعنوان اقدام پیشگیرانه در برابر ربوده‌شدن اطلاعات نشست کاربری، لغو شد. لطفاً دکمهٔ «بازگشت» را در مرورگر خود بفشارید و صفحه‌ای که از آن به اینجا رسیده‌اید را مجدداً فراخوانی کنید، سپس مجدداً سعی کنید.',

# Protect
'protectlogpage'              => 'سیاههٔ_محافظت',
'protectlogtext'              => 'در زیر فهرست قفل کردن‌ها/ازقفل‌درآوردن‌های صفحه‌ها آمده است.
برای اطلاعات بیشتر به [[{{ns:project}}:سیاست حفاظت از صفحه‌ها]] مراجعه کنید.',
'protectedarticle'            => '«[[$1]]» را محافظت کرد',
'modifiedarticleprotection'   => 'وضعیت محافظت «[[$1]]» را تغییر داد',
'unprotectedarticle'          => '[[$1]] را از محافظت به در آورد',
'movedarticleprotection'      => 'تنظیمات محافظت را از «[[$2]]» به «[[$1]]» منتقل کرد',
'protect-title'               => 'تغییر وضعیت محافظت «$1»',
'prot_1movedto2'              => '$1 به $2 منتقل شد',
'protect-legend'              => 'تأیید حفاظت',
'protectcomment'              => 'دلیل محافظت',
'protectexpiry'               => 'زمان سرآمدن:',
'protect_expiry_invalid'      => 'زمان سرآمدن نامعتبر است.',
'protect_expiry_old'          => 'زمان سرآمدن در گذشته‌است.',
'protect-unchain'             => 'بازکردن اجازهٔ انتقال',
'protect-text'                => 'می‌توانید سطح حفاظت صفحهٔ [[$1]] را ببینید و آن را تغییر دهید.
لطفاً اطمینان حاصل کنید که [[{{ns:project}}:سیاست حفاظت از صفحه‌ها|سیاست حفاظت از صفحه‌ها]] را رعایت می‌کنید.',
'protect-locked-blocked'      => 'شما در مدتی که دسترسی‌تان قطع است نمی‌توانید سطح حفاظت صفحه‌ها را تغییر دهید. تنظیمات فعلی صفحهٔ $1 به این قرار است:',
'protect-locked-dblock'       => 'به دلیل قفل شدن پایگاه داده، امکان تغییر سطح حفاظت صفحه وجود ندارد. تنظیمات فعلی صفحهٔ $1 به این قرار است:',
'protect-locked-access'       => 'حساب کاربری شما اجازهٔ تغییر سطح حفاظت صفحه را ندارد. تنظیمات فعلی صفحهٔ $1 به این قرار است:',
'protect-cascadeon'           => 'این صفحه  در حال حاضر محافظت شده‌است زیرا در {{PLURAL:$1|صفحهٔ|صفحه‌های}} زیر که گزینهٔ محافظت آبشاری {{PLURAL:$1|آن|آن‌ها}} فعال است، 
شما می‌توانید سطح محافظت این صفحه را تغییر بدهید اما این کار تاثیری بر محافظت آبشاری صفحه نخواهد گذاشت.',
'protect-default'             => 'همهٔ کاربرها',
'protect-fallback'            => 'سطح دسترسی $1 لازم است.',
'protect-level-autoconfirmed' => 'بستن کاربران جدید و ثبت‌نام نکرده',
'protect-level-sysop'         => 'فقط مدیران',
'protect-summary-cascade'     => 'آبشاری',
'protect-expiring'            => 'زمان سرآمدن $1 (UTC)',
'protect-expiry-indefinite'   => 'بی‌پایان',
'protect-cascade'             => 'محافظت آبشاری - از همهٔ صفحه‌هایی که در این صفحه آمده‌اند نیز محافظت می‌شود.',
'protect-cantedit'            => 'شما نمی‌تواند وضعیت حفاظت این صفحه را تغییر دهید، چون اجازه ویرایش آن را ندارید.',
'protect-othertime'           => 'زمانی دیگر:',
'protect-othertime-op'        => 'زمانی دیگر',
'protect-existing-expiry'     => 'زمان انقضای موجود: $2، $3',
'protect-otherreason'         => 'دلیل دیگر/اضافی:',
'protect-otherreason-op'      => 'دلیل دیگر/اضافی',
'protect-dropdown'            => '*دلایل متداول محافظت
** خرابکاری گسترده
** هرزنگاری گسترده
** جنگ ویرایشی غیر سازنده
** صفحهٔ پر بازدید',
'protect-edit-reasonlist'     => 'ویرایش دلایل محافظت',
'protect-expiry-options'      => '۲ ساعت:2 hours,۱ روز:1 day,۳ روز:3 days,۱ هفته:1 week,۲ هفته:2 weeks,۱ ماه:1 month,۳ ماه:3 months,۶ ماه:6 months,۱ سال:1 year,بی‌پایان:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'دسترسی',
'restriction-level'           => 'سطح محدودیت',
'minimum-size'                => 'حداقل اندازه',
'maximum-size'                => 'حداکثر اندازه',
'pagesize'                    => '(بایت)',

# Restrictions (nouns)
'restriction-edit'   => 'ویرایش',
'restriction-move'   => 'انتقال',
'restriction-create' => 'ایجاد',
'restriction-upload' => 'بارگذاری',

# Restriction levels
'restriction-level-sysop'         => 'کامل‌حفاظت‌شده',
'restriction-level-autoconfirmed' => 'نیمه‌حفاظت‌شده',
'restriction-level-all'           => 'هر سطحی',

# Undelete
'undelete'                     => 'احیای صفحهٔ حذف‌شده',
'undeletepage'                 => 'نمایش و احیای صفحه‌های حذف‌شده',
'undeletepagetitle'            => "'''آن چه در ادامه می‌آید شامل نسخه‌های حذف شدهٔ [[:$1]] است'''.",
'viewdeletedpage'              => 'نمایش صفحه‌های حذف‌شده',
'undeletepagetext'             => '{{PLURAL:$1|صفحهٔ زیر حدف شده|صفحه‌های زیر حذف شده‌اند}} ولی هنوز در بایگانی {{PLURAL:$1|هست|هستند}} و {{PLURAL:$1|می‌تواند احیا شود|می‌توانند احیا شوند}}.
این بایگانی ممکن است هر چند وقت تمیز شود.',
'undelete-fieldset-title'      => 'احیای نسخه‌ها',
'undeleteextrahelp'            => "برای احیای تمام صفحه، همهٔ جعبه‌ها را خالی رها کرده و دکمهٔ '''''احیا''''' را کلیک کنید.
برای انجام احیای انتخابی، جعبه‌های متناظر با نسخه‌های مورد نظر برای احیا را علامت زده و دکمهٔ '''''احیا''''' را کلیک کنید.
کلیک کردن روی دکمهٔ '''''از نو''''' محتویات بخش «توضیح» را پاک و تمام جعبه‌ها را خالی می‌کند.",
'undeleterevisions'            => '$1 نسخه بایگانی {{PLURAL:$1|شده‌است|شده‌اند}}',
'undeletehistory'              => 'اگر این صفحه را احیا کنید، همهٔ نسخه‌های آن در تاریخچه احیا خواهند شد.
اگر صفحهٔ جدیدی با نام یکسان از زمان حذف ایجاد شده باشد، نسخه‌های احیاشده در تاریخچهٔ قبلی خواهند آمد.',
'undeleterevdel'               => 'احیا صفحه‌های در حالتی که باعث حذف شدن بخشی از آخرین نسخهٔ صفحه بشود مقدور نیست.
در این حالت شما باید چند نسخهٔ اخیر صفحه را نیز احیا کنید.
نسخه‌هایی از پرونده‌ها که شما اجازه دیدنش را نداشته باشید قابل احیا نخواهند بود.',
'undeletehistorynoadmin'       => 'این مقاله حذف شده‌است. دلیل حذف این مقاله به همراه مشخصات کاربرانی که قبل از حذف این صفحه را ویرایش کرده‌اند، در خلاصهٔ زیر آمده‌است. متن واقعی این ویرایش‌های حذف شده فقط در دسترس مدیران است.',
'undelete-revision'            => 'حذف نسخهٔ $1 (به تاریخ $4 ساعت $5) توسط $3:',
'undeleterevision-missing'     => 'نسخه نامعتبر یا مفقود است. ممکن است پیوندتان نادرست باشد یا اینکه نسخه از بایگانی حذف یا بازیابی شده باشد .',
'undelete-nodiff'              => 'نسخهٔ قدیمی‌تری یافت نشد.',
'undeletebtn'                  => 'احیا شود!',
'undeletelink'                 => 'احیا',
'undeletereset'                => 'از نو',
'undeleteinvert'               => 'وارونه کردن انتخاب',
'undeletecomment'              => 'توضیح:',
'undeletedarticle'             => '«$1» احیا {{PLURAL:$1|شد|شدند}}',
'undeletedrevisions'           => '$1 نسخه احیا {{PLURAL:$1|شد|شدند}}',
'undeletedrevisions-files'     => '$1 نسخه و $2 پرونده احیا {{PLURAL:$1|شد|شدند}}.',
'undeletedfiles'               => '$1 پرونده احیا {{PLURAL:$1|شد|شدند}}.',
'cannotundelete'               => 'نشد احیا کرد. ممکن است کس دیگری پیشتر این صفحه را احیا کرده باشد.',
'undeletedpage'                => "<big> '''$1 احیا شد.'''</big>
برای دیدن سیاههٔ حذفها و احیاهای اخیر به  [[Special:Log/delete|سیاههٔ حذف]] رجوع کنید.",
'undelete-header'              => 'برای دیدن صفحه‌های حذف‌شدهٔ اخیر [[Special:Log/delete|سیاههٔ حذف]] را ببینید.',
'undelete-search-box'          => 'جستجوی صفحه‌های حذف‌شده.',
'undelete-search-prefix'       => 'نمایش صفحه‌ها با شروع از:',
'undelete-search-submit'       => 'برو',
'undelete-no-results'          => 'هیچ صفحهٔ منطبقی در بایگانی حذف‌شده‌ها یافت نشد.',
'undelete-filename-mismatch'   => 'امکان احیای نسخهٔ $1 وجود ندارد: نام پرونده مطابقت نمی‌کند.',
'undelete-bad-store-key'       => 'امکان احیای نسخهٔ $1 وجود ندارد: پرونده قبل از حذف از دست رفته بود.',
'undelete-cleanup-error'       => 'خطا در حذف تاریخچهٔ استفاده نشدهٔ $1',
'undelete-missing-filearchive' => 'امکان احیای تارخچهٔ شمارهٔ $1 وجود ندارد زیرا اطلاعات در پایگاه داده وجود ندارد.',
'undelete-error-short'         => 'خطا در احیای پرونده: $1',
'undelete-error-long'          => 'در زمان احیای پرونده خطا رخ داد:

$1',
'undelete-show-file-confirm'   => 'آیا مطمئن هستید که می‌خواهید یک نسخهٔ حذف شده از پرونده "<nowiki>$1</nowiki>" از $2 ساعت $3 را ببینید؟',
'undelete-show-file-submit'    => 'آری',

# Namespace form on various pages
'namespace'      => 'فضای نام:',
'invert'         => 'انتخاب برعکس شود',
'blanknamespace' => '(اصلی)',

# Contributions
'contributions'       => 'مشارکت‌ها',
'contributions-title' => 'مشارکت‌های کاربری $1',
'mycontris'           => 'مشارکت‌های من',
'contribsub2'         => 'برای $1 ($2)',
'nocontribs'          => 'هیچ تغییری با این مشخصات یافت نشد.',
'uctop'               => ' (بالا)',
'month'               => 'در این ماه (و قبل از آن)',
'year'                => 'در این سال (و قبل از آن)',

'sp-contributions-newbies'       => 'فقط مشارکت‌های تازه‌واردان نمایش داده شود',
'sp-contributions-newbies-sub'   => 'برای تازه‌کاران',
'sp-contributions-newbies-title' => 'مشارکت‌های کاربری برای حساب‌های تازه‌کار',
'sp-contributions-blocklog'      => 'سیاههٔ بسته‌شدن‌ها',
'sp-contributions-search'        => 'جستجوی مشارکت‌ها',
'sp-contributions-username'      => 'نشانی اینترنتی یا نام کاربری:',
'sp-contributions-submit'        => 'جستجو',

# What links here
'whatlinkshere'            => 'پیوندهای به این صفحه',
'whatlinkshere-title'      => 'صفحه‌هایی که به «$1» پیوند دارند',
'whatlinkshere-page'       => 'صفحه:',
'linkshere'                => "صفحه‌های زیر به '''[[:$1]]''' پیوند دارند:",
'nolinkshere'              => "هیچ صفحه‌ای به '''[[:$1]]''' پیوند ندارد.",
'nolinkshere-ns'           => "هیچ صفحه‌ای از فضای نام انتخاب شده به '''[[:$1]]''' پیوند ندارد.",
'isredirect'               => 'صفحهٔ تغییر مسیر',
'istemplate'               => 'استفاده‌شده در صفحه',
'isimage'                  => 'پیوند به تصویر',
'whatlinkshere-prev'       => '{{PLURAL:$1|قبلی|$1 مورد قبلی}}',
'whatlinkshere-next'       => '{{PLURAL:$1|بعدی|$1 مورد بعدی}}',
'whatlinkshere-links'      => '← پیوندها',
'whatlinkshere-hideredirs' => '$1 تغییرمسیر',
'whatlinkshere-hidetrans'  => '$1 تراگنجانش',
'whatlinkshere-hidelinks'  => '$1 پیوند',
'whatlinkshere-hideimages' => '$1 پیوند به تصویر',
'whatlinkshere-filters'    => 'صافی‌ها',

# Block/unblock
'blockip'                         => 'بستن کاربر',
'blockip-legend'                  => 'بستن کاربر',
'blockiptext'                     => 'از فرم زیر برای بستن دسترسی ویرایش یک نشانی IP یا نام کاربری مشخص استفاده کنید. این کار فقط فقط باید برای جلوگیری از خرابکاری انجام شود، و بر اساس [[{{ns:project}}:قطع دسترسی|سیاست قطع دسترسی]]. دلیل مشخص این کار را در زیر ذکر کنید (مثلاً با ذکر صفحه‌های به‌خصوصی که تخریب شده‌اند).',
'ipaddress'                       => 'نشانی IP/نام کاربر',
'ipadressorusername'              => 'نشانی IP یا نام کاربری',
'ipbexpiry'                       => 'خاتمه',
'ipbreason'                       => 'دلیل',
'ipbreasonotherlist'              => 'دیگر دلایل',
'ipbreason-dropdown'              => '**وارد کردن اطلاعات نادرست
**پاک کردن اطلاعات مفید از صفحه‌ها
**هرزنگاری از طریق درج مکرر پیوند به وب‌گاه‌ها
**درج چرندیات یا نوشته‌های بی‌معنا در صفحه‌ها
**تهدید یا ارعاب دیگر کاربرها
**سوء استفاده از چند حساب کاربری زاپاس
**نام کاربری نامناسب
**نقض مکرر حق تکثیر',
'ipbanononly'                     => 'فقط بستن کاربران گمنام',
'ipbcreateaccount'                => 'جلوگیری از ایجاد حساب',
'ipbemailban'                     => 'جلوگیری از ارسال پست الکترونیکی',
'ipbenableautoblock'              => 'بستن  خودکار آخرین نشانی اینترنتی استفاده شده توسط کاربر و نشانی‌های دیگری که از آن‌ها برای ویرایش تلاش می‌کند',
'ipbsubmit'                       => 'این کاربر بسته شود',
'ipbother'                        => 'زمانی دیگر',
'ipboptions'                      => '۲ ساعت:2 hours,۱ روز:1 day,۳ روز:3 days,۱ هفته:1 week,۲ هفته:2 weeks,۱ ماه:1 month,۳ ماه:3 months,۶ ماه:6 months,۱ سال:1 year,بی‌پایان:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'دیگر',
'ipbotherreason'                  => 'دلیل دیگر/اضافی:',
'ipbhidename'                     => 'نهفتن نام کاربری/نشانی اینترنتی از سیاههٔ بسته‌شدن‌ها، فهرست بسته‌شدگان کنونی و فهرست کاربران.',
'ipbwatchuser'                    => 'پیگیری صفحهٔ کاربری و بحث این کاربر',
'ipballowusertalk'                => 'به این کاربر اجازه بده در مدت قطع دسترسی صفحهٔ بحث خودش را ویرایش کند.',
'ipb-change-block'                => 'بستن دوبارهٔ کاربر با این تنظیم‌ها',
'badipaddress'                    => 'کاربری با این نام وجود ندارد.',
'blockipsuccesssub'               => 'بستن با موفقیت انجام شد',
'blockipsuccesstext'              => '«$1» بسته شده است.
<br />برای بررسی بسته‌شدن‌ها، به [[Special:IPBlockList|فهرست نشانی‌های IP و نام‌های کاربری بسته‌شده]] مراجعه کنید.',
'ipb-edit-dropdown'               => 'دلایل قطع دسترسی',
'ipb-unblock-addr'                => 'بازکردن $1',
'ipb-unblock'                     => 'بازکردن نام کاربری یا نشانی اینترنتی',
'ipb-blocklist-addr'              => 'بستن‌های موجود برای $1',
'ipb-blocklist'                   => 'دیدنِ بَست‌های موجود',
'ipb-blocklist-contribs'          => 'مشارکت‌های $1',
'unblockip'                       => 'باز کردن کاربر',
'unblockiptext'                   => 'برای باز گرداندن دسترسی نوشتن به یک نشانی IP یا نام کاربری بسته‌شده
از فرم زیر استفاده کنید.',
'ipusubmit'                       => 'باز کردن دسترسی',
'unblocked'                       => 'دسترسی [[User:$1|$1]] دوباره برقرار شد.',
'unblocked-id'                    => 'قطع دسترسی شماره $1 خاتمه یافت',
'ipblocklist'                     => 'نشانی‌های اینترنتی و نام‌های کاربری بسته‌شده',
'ipblocklist-legend'              => 'جستجوی کاربر بسته شده',
'ipblocklist-username'            => 'نام کاربری یا نشانی IP',
'ipblocklist-sh-userblocks'       => '$1 قطع دسترسی کاربر',
'ipblocklist-sh-tempblocks'       => '$1 قطع دسترسی موقت',
'ipblocklist-sh-addressblocks'    => '$1 قطع دسترسی نشانی اینترنتی',
'ipblocklist-submit'              => 'جستجو',
'blocklistline'                   => '$1، $2 $3 را بست ($4)',
'infiniteblock'                   => 'بی‌پایان',
'expiringblock'                   => 'در $1 به پایان می‌رسد',
'anononlyblock'                   => 'فقط کاربران گمنام',
'noautoblockblock'                => 'بستن خودکار غیرفعال است.',
'createaccountblock'              => 'امکان ایجاد حساب مسدود است.',
'emailblock'                      => 'پست الکتروینکی مسدود شد',
'blocklist-nousertalk'            => 'نمی تواند صفحهٔ بحث خود را ویرایش کند',
'ipblocklist-empty'               => 'فهرست بسته‌شدن‌ها خالی‌است.',
'ipblocklist-no-results'          => 'دسترسی حساب کاربری یا نشانی اینترنتی مورد نظر قطع نیست.',
'blocklink'                       => 'بسته شود',
'unblocklink'                     => 'باز شود',
'change-blocklink'                => 'تغییر قطع دسترسی',
'contribslink'                    => 'مشارکت‌ها',
'autoblocker'                     => 'به طور خودکار بسته شد چون IP شما و «$1» یکی است. دلیل «$2».',
'blocklogpage'                    => 'سیاههٔ_بسته‌شدن‌ها',
'blocklog-fulllog'                => 'سیاه بستن کامل',
'blocklogentry'                   => '$1 را برای $2 بست $3',
'reblock-logentry'                => 'تنظیم‌های قطع دسترسی [[$1]] را تغییر داد به پایان قطع دسترسی در $2 ساعت $3',
'blocklogtext'                    => 'این سیاهه‌ای از اعمال بستن و باز کردن کاربرها است. نشانی‌های IPی که به طور
خودکار بسته شده‌اند فهرست نشده‌اند. برای فهرست محرومیت‌ها و بسته‌شدن‌های عملیاتی در لحظهٔ حاضر،
به [[Special:IPBlockList|فهرست IPهای بسته]] مراجعه کنید.',
'unblocklogentry'                 => '«$1» باز شد',
'block-log-flags-anononly'        => 'فقط کاربران گمنام',
'block-log-flags-nocreate'        => 'قابلیت ایجاد حساب غیرفعال شد.',
'block-log-flags-noautoblock'     => 'قطع دسترسی خودکار غیرفعال شد',
'block-log-flags-noemail'         => 'پست الکترونیکی مسدود شد',
'block-log-flags-nousertalk'      => 'صفحهٔ بحث خود را نمی‌تواند ویرایش کند',
'block-log-flags-angry-autoblock' => 'قطع دسترسی خودکار پیشرفته فعال شد',
'range_block_disabled'            => 'قابلیت بستن گستره‌ای مدیران سلب  شده‌است.',
'ipb_expiry_invalid'              => 'زمان خاتمه نامعتبر.',
'ipb_expiry_temp'                 => 'قطع دسترسی کاربرهای پهنان باید همیشگی باشد.',
'ipb_already_blocked'             => '«$1» همین الان هم بسته‌است.',
'ipb-needreblock'                 => '== قطع دسترسی از قبل ==
دسترسی $1 از قبل بسته است. آیا می‌خواهید تنظیم‌های آن را تغییر دهید؟',
'ipb_cant_unblock'                => 'خطا: آی‌دی بسته‌شدن $1 یافت نشد. ممکن است پیشتر باز شده باشد.',
'ipb_blocked_as_range'            => 'Error: نشانی اینترنتی $1 به شکل مستقیم بسته نشده‌است و نمی‌تواند باز شود. این نشانی به همراه بازه  $2, بسته شده که قابل باز شدن است.',
'ip_range_invalid'                => 'گسترهٔ نشانی اینترنتی (IP) نامعتبر است.',
'blockme'                         => 'دسترسی مرا قطع کن',
'proxyblocker'                    => 'پروکسی‌بَند',
'proxyblocker-disabled'           => 'این عملکرد غیرفعال شده‌است.',
'proxyblockreason'                => 'نشانیIP شما بسته شده است چون یک پیشکار (proxy) باز است. لطفاً با تأمین‌کنندهٔ اینترنت خود تماس بگیرید و آنها را از این مشکل امنیتی جدی آگاه کنید.',
'proxyblocksuccess'               => 'انجام شد.',
'sorbsreason'                     => 'نشانی اینترنتی (IP) شما توسط DNSBL به عنوان یک پروکسی باز گزارش شده‌است.',
'sorbs_create_account_reason'     => 'نشانی اینترنتی (IP) شما توسط DNSBL به عنوان یک پروکسی باز گزارش شده‌است. شما اجازهٔ ساختن حساب کاربری ندارید.',
'cant-block-while-blocked'        => 'در مدتی که دسترسی شما بسته است نمی‌توانید دسترسی کاربران دیگر را قطع کنید.',

# Developer tools
'lockdb'              => 'قفل کردن پایگاه داده',
'unlockdb'            => 'از قفل در آوردن پایگاه داده',
'lockdbtext'          => 'قفل کردن پایگاه داده امکان ویرایش صفحه‌ها، تغییر تنظیمات، ویرایش پی‌گیری‌ها، و سایر تغییراتی را که نیازمند تغییری در پایگاه داده است، از همهٔ کاربران سلب می‌کند.
لطفاً تایید کنید که دقیقاً این کار را می‌خواهید انجام دهید، و در اولین فرصت پایگاه داده را از حالت قفل شده خارج خواهید کرد.',
'unlockdbtext'        => 'از قفل در آوردن پایگاه داده به تمامی کاربران اجازه می‌دهد که توانایی ویرایش صفحه‌ها، تغییر تنظیمات، تغییر پی‌گیری‌ها و هر تغییر دیگری که نیازمند تغییر در پایگاه داده باشد را دوباره به دست بیاورند.
لطفاً تایید کنید که همین کار را می‌خواهید انجام دهید.',
'lockconfirm'         => 'بله، من جداً می‌خواهم پایگاه داده را قفل کنم.',
'unlockconfirm'       => 'بله، من جداً می‌خواهم پایگاه داده را از قفل در آورم.',
'lockbtn'             => 'قفل کردن پایگاه داده',
'unlockbtn'           => 'از قفل درآوردن پایگاه داده',
'locknoconfirm'       => 'شما در جعبهٔ تأیید تیک نزدید',
'lockdbsuccesssub'    => 'قفل کردن پایگاه داده با موفقیت انجام شد',
'unlockdbsuccesssub'  => 'قفل پایگاه داده برداشته شد',
'lockdbsuccesstext'   => 'پایگاه داده قفل شد.
<br />فراموش نکنید که پس از اتمام نگهداری قفل را بردارید.',
'unlockdbsuccesstext' => 'پایگاه داده از قفل در آمد.',
'lockfilenotwritable' => 'قفل پایگاه داده نوشتنی نیست. برای این که بتوانید پایگاه داده را قفل یا باز کنید، باید این پرونده نوشتنی باشد.',
'databasenotlocked'   => 'پایگاه داده قفل نیست.',

# Move page
'move-page'                    => 'انتقال $1',
'move-page-legend'             => 'انتقال صفحه',
'movepagetext'                 => "با استفاده از فرم زیر نام صفحه تغییر خواهد کرد، و تمام تاریخچه‌اش به نام جدید منتقل خواهد شد.
عنوان قدیمی تبدیل به یک صفحهٔ تغییر مسیر به عنوان جدید خواهد شد.
پیوندهای که به عنوان صفحهٔ قدیمی وجود دارند، تغییر نخواهند کرد؛ حتماً تغییر مسیرهای دوتایی یا خراب را بررسی کنید.
'''شما''' مسئول اطمینان از این هستید که پیوندها هنوز به همان‌جایی که قرار است بروند.

توجه کنید که اگر از قبل صفحه‌ای در عنوان جدید وجود داشته باشد صفحه منتقل '''نخواهد شد'''،
مگر این که صفحه خالی یا تغییر مسیر باشد و تاریخچهٔ ویرایشی نداشته باشد.
این یعنی اگر اشتباه کردید می‌توانید صفحه را به همان جایی که از آن منتقل شده بود برگردانید، و این که نمی‌توانید روی صفحه‌ها موجود بنویسید.

'''هشدار!'''
انتقال صفحه‌ها به نام جدید ممکن است تغییر اساسی و غیرمنتظره‌ای برای صفحه‌های محبوب باشد؛
لطفاً مطمئن شوید که قبل از انتقال دادن صفحه، عواقب این کار را درک می‌کنید.",
'movepagetalktext'             => "صفحهٔ بحث مربوط، اگر وجود داشته باشد، بطور خودکار همراه با مقالهٔ اصلی منتقل خواهد شد '''مگر اینکه''' :
* در حال انتقال صفحه از این فضای نام به فضای نام دیگری باشید،
* یک صفحهٔ بحث غیرخالی تحت این نام جدید وجود داشته باشد، یا
* جعبهٔ زیر را تیک نزده باشید.

در این حالات، باید صفحه را بطور دستی انتقال داده و یا محتویات دو صفحه را با ویرایش ادغام کنید.",
'movearticle'                  => 'انتقال صفحه',
'movenologin'                  => 'به سیستم وارد نشده‌اید',
'movenologintext'              => 'برای انتقال صفحه‌ها باید کاربر ثبت‌شده بوده و
[[Special:UserLogin|به سیستم وارد شوید]].',
'movenotallowed'               => 'شما اجازهٔ انتقال دادن صفحه‌ها را ندارید.',
'movenotallowedfile'           => 'شما اجازهٔ انتقال پرونده‌ها را ندارید.',
'cant-move-user-page'          => 'شما اجازه ندارید صفحه‌های کاربری سرشاخه را انتقال دهید.',
'cant-move-to-user-page'       => 'شما اجازه ندارید که یک صحفه را به یک صفحهٔ کاربر انتقال دهید (به استثنای زیر صفحه‌های کاربری).',
'newtitle'                     => 'به عنوان جدید',
'move-watch'                   => 'پی‌گیری این صفحه',
'movepagebtn'                  => 'صفحه منتقل شود',
'pagemovedsub'                 => 'انتقال با موفقیت انجام شد',
'movepage-moved'               => "<big>'''«$1» به «$2» منتقل شد'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => 'یک تغییر مسیر ایجاد شد.',
'movepage-moved-noredirect'    => 'از ایجاد تغییر مسیر ممانعت شد.',
'articleexists'                => 'صفحه‌ای با این نام از قبل وجود دارد، یا نامی که انتخاب کرده‌اید معتبر نیست.
لطفاً نام دیگری انتخاب کنید.',
'cantmove-titleprotected'      => 'شما نمی‌توانید صفحه را به این نشانی انتقال دهید، چرا که عنوان جدید در برابر ایجاد محافظت شده‌است',
'talkexists'                   => 'صفحه با موفقیت منتقل شد، ولی صفحهٔ بحث را، به این دلیل که صفحهٔ بحثی در عنوان جدید
وجود دارد، نمی‌توان منتقل کرد. لطفاً آنها را دستی ترکیب کنید.',
'movedto'                      => 'منتقل شد به',
'movetalk'                     => 'صفحهٔ بحث هم منتقل شود',
'move-subpages'                => 'تمام زیرصفحه‌ها (تا $1 عدد) در صورت وجود منتقل شوند',
'move-talk-subpages'           => 'تمام زیرصفحه‌های صفحهٔ بحث (تا $1 صفحه) در صورت وجود منتقل شوند',
'movepage-page-exists'         => 'صفحهٔ $1 از قبل وجود دارد و نمی‌تواند به طور خودکار جایگزین شود.',
'movepage-page-moved'          => 'صفحهٔ $1 به $2 انتقال یافت.',
'movepage-page-unmoved'        => 'صفحهٔ $1 را نمی‌توان به $2 انتقال داد.',
'movepage-max-pages'           => 'حداکثر تعداد صفحه‌های ممکن ($1 {{PLURAL:$1|صفحه|صفحه}}) که می‌توان انتقال داد منتقل شدند و صفحه‌های دیگر را نمی‌توان به طور خودکار منتقل کرد.',
'1movedto2'                    => '$1 به $2 منتقل شد',
'1movedto2_redir'              => '$1 به $2 که قبلاً صفحهٔ تغییر مسیر بود، منتقل شد',
'move-redirect-suppressed'     => 'تغییر مسیر فرونشانده شد',
'movelogpage'                  => 'سیاههٔ انتقال',
'movelogpagetext'              => 'در زیر فهرستی از صفحه‌های منتقل شده آمده است.',
'movereason'                   => 'دلیل انتقال',
'revertmove'                   => 'واگردانی',
'delete_and_move'              => 'حذف و انتقال',
'delete_and_move_text'         => '== نیاز به حذف ==

مقاله‌ٔ مقصد «[[:$1]]» وجود دارد. آیا می‌خواهید آن را حذف کنید تا انتقال ممکن شود؟',
'delete_and_move_confirm'      => 'بله، صفحه حذف شود',
'delete_and_move_reason'       => 'حذف برای ممکن‌شدن انتقال',
'selfmove'                     => 'عناوین صفحهٔ مبداء و مقصد یکی است؛ انتقال صفحه به خودش ممکن نیست.',
'immobile-source-namespace'    => 'امکان انتقال صفحه‌ها در فضای نام «$1» را ندارد',
'immobile-target-namespace'    => 'امکان انتقال صفحه‌ها به فضای نام «$1» را ندارد',
'immobile-target-namespace-iw' => 'پیوند میان‌ویکی هدفی مجاز برای انتقال صفحه نیست.',
'immobile-source-page'         => 'این صفحه قابل انتقال نیست.',
'immobile-target-page'         => 'امکان انتقال به این عنوان مقصد وجود ندارد.',
'imagenocrossnamespace'        => 'امکان انتقال تصویر به فضای نام غیر تصویر وجود ندارد',
'imagetypemismatch'            => 'پسوند پرونده جدید با نوع آن سازگار نیست',
'imageinvalidfilename'         => 'نام پروندهٔ هدف غیر مجاز است',
'fix-double-redirects'         => 'به روز کردن تمامی تغییر مسیرهایی که به مقالهٔ اصلی اشاره می‌کنند',
'move-leave-redirect'          => 'بر جا گذاشتن یک تغییر مسیر',

# Export
'export'            => 'برون‌بری صفحه‌ها',
'exporttext'        => 'شما می‌توانید متن و تاریخچهٔ ویرایش یک صفحهٔ مشخص یا مجموعه‌ای از صفحه‌ها را به شکل پوشیده در XML برون‌بری کنید؛
این اطلاعات را می‌توان در ویکیِ دیگری که نرم‌افزار «مدیاویکی» را اجرا می‌کند درون‌ریزی کرد، تبدیل کرد، یا برای سرگرمی شخصی نگه داشت.',
'exportcuronly'     => 'فقط نسخهٔ فعلی بیاید، نه کل تاریخچه',
'exportnohistory'   => "----
'''توجه:''' امکان برون‌بری تارخچهٔ کامل صفحه‌ها از طریق این صفحه به دلایل اجرایی از کار انداخته شده‌است.",
'export-submit'     => 'برون‌بری',
'export-addcattext' => 'افزودن صفحه‌ها از رده:',
'export-addcat'     => 'افزودن',
'export-download'   => 'پیشنهاد ذخیره به صورت پرونده',
'export-templates'  => 'شامل شدن الگوها',

# Namespace 8 related
'allmessages'               => 'همهٔ پیغام‌ها',
'allmessagesname'           => 'نام',
'allmessagesdefault'        => 'متن پیش‌فرض',
'allmessagescurrent'        => 'متن کنونی',
'allmessagestext'           => 'این فهرستی از همهٔ پیغام‌های موجود در فضای نام مدیاویکی است.',
'allmessagesnotsupportedDB' => "نمی‌توان از '''{{ns:special}}:همهٔ پیغام‌ها''' استفاده کرد چود '''\$wgUseDatabaseMessages''' خاموش شده است.",
'allmessagesfilter'         => 'صافی نام پیغام:',
'allmessagesmodified'       => 'تنها نمایش تغییریافته‌ها',

# Thumbnails
'thumbnail-more'           => 'بزرگ شود',
'filemissing'              => 'پرونده وجود ندارد',
'thumbnail_error'          => 'خطا در ایجاد انگشت‌دانه: $1',
'djvu_page_error'          => 'صفحهٔ DjVu خارج از حدود مجاز',
'djvu_no_xml'              => 'امکان پیدا کردن پروندهٔ XML برای استفادهٔ DjVu وجود نداشت.',
'thumbnail_invalid_params' => 'پارامترهای غیر مجاز در تصویر انگشتدانه‌ای (thumbnail)',
'thumbnail_dest_directory' => 'اشکال در ایجاد پوشهٔ مقصد',

# Special:Import
'import'                     => 'درون‌ریزی صفحه‌ها',
'importinterwiki'            => 'درون‌ریزی تراویکیانه',
'import-interwiki-text'      => 'یک ویکی و یک نام صفحه را انتخاب کنید تا اطلاعات از آن درون‌ریزی شود. تاریخ نسخه‌ها و نام ویرایش‌کنندگان ثابت خواهد ماند. اطلاعات مربوط به درون‌ریزی صفحه‌ها در [[Special:Log/import|سیاههٔ درون‌ریزی‌ها]] درج خواهد شد.',
'import-interwiki-source'    => 'ویکی/صفحهٔ منبع:',
'import-interwiki-history'   => 'تمام نسخه‌های تاریخچهٔ این صفحه انتقال داده شود',
'import-interwiki-submit'    => 'درون‌ریزی شود',
'import-interwiki-namespace' => 'فضای نام مقصد:',
'import-upload-filename'     => 'نام پرونده:',
'import-comment'             => 'توضیح',
'importtext'                 => 'لطفاً پرونده را از منبع ویکی با کمک ابزار Special:Export برون‌بری کنید، روی دستگاه‌تان ذخیره کنید و این‌جا بارگذاری نمایید.',
'importstart'                => 'در حال درون‌ریزی صفحه‌ها...',
'import-revision-count'      => '$1 {{PLURAL:$1|ویرایش|ویرایش}}',
'importnopages'              => 'صفحه‌ای برای درون‌ریزی نیست.',
'importfailed'               => 'درون‌ریزی صفحه‌ها شکست خورد: $1',
'importunknownsource'        => 'نوع مأخذ درون‌ریزی معلوم نیست',
'importcantopen'             => 'پروندهٔ درون‌ریزی صفحه‌ها باز نشد',
'importbadinterwiki'         => 'پیوند میان‌ویکی نادرست',
'importnotext'               => 'صفحه خالی یا بدون متن',
'importsuccess'              => 'درون‌ریزی با موفقیت انجام شد!',
'importhistoryconflict'      => 'نسخه‌های ناسازگار از تاریخچهٔ این صفحه وجود دارد. (احتمالاً این صفحه پیش از این درون‌ریزی شده است)',
'importnosources'            => 'هیچ منبعی برای درون‌ریزی اطلاعات از ویکی دیگر تعریف نشده‌است',
'importnofile'               => 'هیچ پرونده‌ای برای درون‌ریزی بارگذاری نشده‌است',
'importuploaderrorsize'      => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد. اندازهٔ پرونده بیشتر از حداکثر اندازهٔ مجاز است.',
'importuploaderrorpartial'   => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد. پرونده به طور ناقص بارگذاری شده‌است.',
'importuploaderrortemp'      => 'در بارگذاری پروندهٔ درون‌ریزی، اشکال رخ داد. پوشهٔ موقت پیدا نشد.',
'import-parse-failure'       => 'خطا در تجزیهٔ XML بارگذاری‌شده',
'import-noarticle'           => 'صفحه‌ای برای بارگذاری وجود ندارد!',
'import-nonewrevisions'      => 'تمام نسخه‌ها قبلاً بارگذاری شده‌اند.',
'xml-error-string'           => '$1 در سطر $2، ستون $3 (بایت $4): $5',
'import-upload'              => 'بارگذاری داده XML',
'import-token-mismatch'      => 'از دست رفتن اطلاعات نشست کاربری. لطفاً دوباره امتحان کنید.',
'import-invalid-interwiki'   => 'از ویکی مشخص شده نمی‌توان درون‌ریزی انجام داد.',

# Import log
'importlogpage'                    => 'سیاههٔ درون‌ریزی‌ها',
'importlogpagetext'                => 'درون‌ریزی صفحه‌ها به همراه تارخچهٔ ویرایش آن‌ها از ویکی‌های دیگر',
'import-logentry-upload'           => '[[$1]] را از طریق بارگذاری پرونده درون‌ریزی کرد',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|نسخه|نسخه}}',
'import-logentry-interwiki'        => '$1 تراویکی شد',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|نسخه|نسخه}} از $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'صفحهٔ کاربری شما',
'tooltip-pt-anonuserpage'         => 'صفحهٔ کاربری نشانی اینترنتی‌ای که با آن ویرایش می‌کنید.',
'tooltip-pt-mytalk'               => 'صفحهٔ بحث شما',
'tooltip-pt-anontalk'             => 'بحث پیرامون ویرایش‌های این نشانی اینترنتی',
'tooltip-pt-preferences'          => 'ترجیحات من',
'tooltip-pt-watchlist'            => 'فهرست صفحه‌هایی که شما تغییرات آن‌ها را پی‌گیری می‌کنید',
'tooltip-pt-mycontris'            => 'فهرست مشارکت‌های شما',
'tooltip-pt-login'                => 'توصیه می‌شود که به سیستم وارد شوید لیکن اجباری نیست.',
'tooltip-pt-anonlogin'            => 'هرآینه توصیه می‌شود که به سیستم وارد شوید لیکن اجباری نیست.',
'tooltip-pt-logout'               => 'خروج از سیستم',
'tooltip-ca-talk'                 => 'گفتگو پیرامون صفحهٔ محتوا',
'tooltip-ca-edit'                 => 'شما می‌توانید این صفحه را ویرایش کنید. لطفاً پیش از ذخیره از دکمهٔ پیش‌نمایش استفاده کنید.',
'tooltip-ca-addsection'           => 'یک بخش جدید ایجاد کنید',
'tooltip-ca-viewsource'           => 'این صفحه محافظت‌شده است. می‌توانید متن مبدأ آن را مشاهده کنید.',
'tooltip-ca-history'              => 'نسخه‌های پیشین این صفحه',
'tooltip-ca-protect'              => 'محافظت از این صفحه',
'tooltip-ca-delete'               => 'حذف این صفحه',
'tooltip-ca-undelete'             => 'بازگرداندن نسخه‌های صفحهٔ حذف‌شده',
'tooltip-ca-move'                 => 'انتقال این صفحه',
'tooltip-ca-watch'                => 'اضافه کردن این صفحه به فهرست پی‌گیری‌های شما',
'tooltip-ca-unwatch'              => 'حذف این صفحه از فهرست پی‌گیری‌های شما',
'tooltip-search'                  => 'جستجو در {{SITENAME}}',
'tooltip-search-go'               => 'در صورت امکان به صفحه‌ای با همین نام برو',
'tooltip-search-fulltext'         => 'این عبارت را در صفحه‌ها جستجو کن',
'tooltip-p-logo'                  => 'صفحهٔ اصلی',
'tooltip-n-mainpage'              => 'بازدید از صفحهٔ اصلی',
'tooltip-n-portal'                => 'پیرامون پروژه، چه‌ها توانید کرد و کجا توانید یافت',
'tooltip-n-currentevents'         => 'یافتن اطلاعات پیشزمینه پیرامون وقایع کنونی',
'tooltip-n-recentchanges'         => 'فهرست تغییرات اخیر در ویکی.',
'tooltip-n-randompage'            => 'آوردن یک صفحهٔ تصادفی',
'tooltip-n-help'                  => 'مکانی برای دریافتن.',
'tooltip-t-whatlinkshere'         => 'فهرست تمام صفحه‌هایی که به این صفحه پیوند می‌دهند',
'tooltip-t-recentchangeslinked'   => 'تغییرات اخیر در صفحه‌هایی که این صفحه به آن‌ها پیوند دارد',
'tooltip-feed-rss'                => 'خبرنامه RSS برای این صفحه',
'tooltip-feed-atom'               => 'خبرنامهٔ Atom برای این صفحه',
'tooltip-t-contributions'         => 'مشاهدهٔ فهرست مشارکت‌های این کاربر',
'tooltip-t-emailuser'             => 'ارسال پست الکترونیکی به ای کاربر',
'tooltip-t-upload'                => 'بارگذاری تصاویر و پرونده‌های دیگر',
'tooltip-t-specialpages'          => 'فهرست تمام صفحه‌های ویژه',
'tooltip-t-print'                 => 'نسخهٔ قابل چاپ این صفحه',
'tooltip-t-permalink'             => 'پیوند دائمی به این نسخه از این صفحه',
'tooltip-ca-nstab-main'           => 'دیدن صفحهٔ محتویات',
'tooltip-ca-nstab-user'           => 'نمایش صفحهٔ کاربر',
'tooltip-ca-nstab-media'          => 'دیدن صفحهٔ مدیا',
'tooltip-ca-nstab-special'        => 'این یک صفحهٔ ویژه است، نمی‌توانید خود صفحه را ویرایش کنید',
'tooltip-ca-nstab-project'        => 'نمایش صفحهٔ پروژه',
'tooltip-ca-nstab-image'          => 'دیدن صفحهٔ تصویر',
'tooltip-ca-nstab-mediawiki'      => 'نمایش پیغام سیستم',
'tooltip-ca-nstab-template'       => 'نمایش الگو',
'tooltip-ca-nstab-help'           => 'دیدن صفحهٔ راهنما',
'tooltip-ca-nstab-category'       => 'دیدن صفحهٔ رده',
'tooltip-minoredit'               => 'یک علامت جزئی به این ویرایش اضافه کن',
'tooltip-save'                    => 'تغییرات خود را ذخیره کنید',
'tooltip-preview'                 => 'پیش‌نمایش تغییرات شما، لطفاً قبل از ذخیره‌سازی صفحه از این کلید استفاده کنید.',
'tooltip-diff'                    => 'نمایش تغییراتی که شما در متن داده‌اید.',
'tooltip-compareselectedversions' => 'دیدن تفاوت‌های موجود بین دو نسخهٔ انتخاب شده این صفحه.',
'tooltip-watch'                   => 'این صفحه را به فهرست پی‌گیری‌های خود بیافزایید.',
'tooltip-recreate'                => 'ایجاد دوبارهٔ صفحه صرف نظر از حذف شدن قبلی آن',
'tooltip-upload'                  => 'شروع بارگذاری',
'tooltip-rollback'                => '«واگردانی» ویرایش(های) آخرین ویرایش‌کننده در این صحفه را با یک کلیک باز می‌گرداند.',
'tooltip-undo'                    => '«خنثی‌سازی» این ویرایش را خنثی می‌کند و فرم ویرایش را در حالت پیش‌نمایش باز می‌کند تا امکان افزودن دلیلی در خلاصه ویرایش را بدهد.',

# Stylesheets
'common.css'   => '/* دستورات این بخش همهٔ کاربران را تحت تاثیر قرار می‌دهند. */',
'monobook.css' => '/* دستورات این بخش کاربرانی را که از پوستهٔ مونوبوک استفاده کنند تحت تاثیر قرار می‌دهند. */',

# Metadata
'nodublincore'      => 'فراداه Dublin Core RDF برای این کارگذار غیر فعال شده‌است.',
'nocreativecommons' => 'متاداده RDF کرییتیو کامنز برای این کارگزار از کار انداخته شده است.',
'notacceptable'     => 'کارگذار این ویکی از ارسال داده به شکلی که برنامهٔ شما بتواند نمایش بدهد، عاجز است.',

# Attribution
'anonymous'        => '{{PLURAL:$1|کاربر|کاربران}} گمنام {{SITENAME}}',
'siteuser'         => '$1، کاربر {{SITENAME}}',
'lastmodifiedatby' => 'این صفحه آخرین بار در $2، $1 به دست $3 تغییر یافته‌است.', # $1 date, $2 time, $3 user
'othercontribs'    => 'بر اساس اثری از $1',
'others'           => 'دیگران',
'siteusers'        => '$1، {{PLURAL:$2|کاربر|کاربران}} {{SITENAME}}',
'creditspage'      => 'اعتبارات این صفحه',
'nocredits'        => 'اطلاعات سازندگان این صفحه موجود نیست.',

# Spam protection
'spamprotectiontitle' => 'فیلتر هرزنگاری‌ها',
'spamprotectiontext'  => 'از ذخیره کردن صفحه توسط صافی هرزنگاری‌ها جلوگیری شد. 
معمولاً این اتفاق زمانی می‌افتد که متن جدید صفحه، حاوی پیوندی به یک نشانی وب خارجی باشد.',
'spamprotectionmatch' => 'متن زیر چیزی‌است که فیلتر هرزه‌نگاری ما را به کارانداخت: $1',
'spambot_username'    => 'هرزه‌تمیزکارِ مدیاویکی',
'spam_reverting'      => 'واگردانی به آخرین نسخه‌ای که پیوندی به $1 ندارد.',
'spam_blanking'       => 'تمام نسخه‌ها حاوی پیوند به $1 بود، در حال خالی کردن',

# Info page
'infosubtitle'   => 'اطلاعات در مورد صفحه',
'numedits'       => 'تعداد ویرایش‌ها (ی نوشتار): $1',
'numtalkedits'   => 'تعداد ویرایش‌ها (صفحهٔ بحث): $1',
'numwatchers'    => 'شمار پی‌گیری‌کنندگان: $1',
'numauthors'     => 'شمار نویسندگان متمایز (مقاله): $1',
'numtalkauthors' => 'تعداد مؤلفان مختلف (صفحهٔ بحث): $1',

# Skin names
'skinname-standard'    => 'کلاسیک',
'skinname-nostalgia'   => 'نوستالژی',
'skinname-cologneblue' => 'آبی کلون',
'skinname-monobook'    => 'مونوبوک',
'skinname-myskin'      => 'پوستهٔ من',
'skinname-chick'       => 'شیک',
'skinname-simple'      => 'ساده',
'skinname-modern'      => 'مدرن',

# Math options
'mw_math_png'    => 'همیشه PNG کشیده شود',
'mw_math_simple' => 'اگر خیلی ساده بود HTML وگرنه PNG',
'mw_math_html'   => 'اگر ممکن بود HTML وگرنه PNG',
'mw_math_source' => 'در قالب TeX باقی بماند (برای مرورگرهای متنی)',
'mw_math_modern' => 'توصیه برای مرورگرهای امروزی',
'mw_math_mathml' => 'استفاده از MathML در صورت امکان (آزمایشی)',

# Patrolling
'markaspatrolleddiff'                 => 'برچسب گشت بزن',
'markaspatrolledtext'                 => 'به این صفحه برچسب گشت بزن',
'markedaspatrolled'                   => 'برچسب گشت زده شد',
'markedaspatrolledtext'               => 'به نسخهٔ مورد نظر برچسب گشت زده شد.',
'rcpatroldisabled'                    => 'گشت تغییرات اخیر غیر فعال است',
'rcpatroldisabledtext'                => 'امکان گشت تغییرات اخیر در حال حاضر غیر فعال است.',
'markedaspatrollederror'              => 'برچسب گشت زده نشد',
'markedaspatrollederrortext'          => 'باید یک نسخه را مشخص کنید تا برچسب گشت بخورد.',
'markedaspatrollederror-noautopatrol' => 'شما نمی‌توانید به تغییرات انجام شده توسط خودتان برچسب گشت بزنید.',

# Patrol log
'patrol-log-page'      => 'سیاههٔ گشت',
'patrol-log-header'    => 'این سیاهه‌ای از ویرایش‌های گشت‌خورده است.',
'patrol-log-line'      => 'به $1 از $2 برچسب گشت زد $3',
'patrol-log-auto'      => '(خودکار)',
'patrol-log-diff'      => 'نسخه $1',
'log-show-hide-patrol' => 'سیاههٔ گشت $1',

# Image deletion
'deletedrevision'                 => '$1 نسخهٔ حذف شدهٔ قدیمی',
'filedeleteerror-short'           => 'خطا در حذف پرونده: $1',
'filedeleteerror-long'            => 'در زمان حذف پرونده خطا رخ داد:

$1',
'filedelete-missing'              => 'پروندهٔ $1 قابل حذف نیست چون پرونده‌ای به این نام وجود ندارد.',
'filedelete-old-unregistered'     => 'نسخهٔ پروندهٔ $1 در پایگاه داده وجود ندارد.',
'filedelete-current-unregistered' => 'پرونده‌ای با نام $1 در پایگاه داده موجود نیست.',
'filedelete-archive-read-only'    => 'امکان نوشتن در پوشهٔ تاریخچهٔ $1 وجود ندارد.',

# Browsing diffs
'previousdiff' => '→ تفاوت قدیمی‌تر',
'nextdiff'     => 'تفاوت جدیدتر ←',

# Visual comparison
'visual-comparison' => 'مقایسهٔ دیداری',

# Media information
'mediawarning'         => "'''هشدار''': این پرونده ممکن است حاوی کدهای مخربی باشد که با اجرای آن رایانهٔ شما آسیب ببیند.",
'imagemaxsize'         => 'محدودکردن ابعاد تصویر نمایش‌داده‌شده در صفحهٔ توصیف تصویر، به ابعاد:',
'thumbsize'            => 'اندازهٔ Thumbnail:',
'widthheight'          => '$1 در $2',
'widthheightpage'      => '$1×$2، $3 {{PLURAL:$3|صفحه|صفحه}}',
'file-info'            => 'اندازهٔ پرونده: $1، نوع  MIME $2',
'file-info-size'       => '(<span dir=ltr>$1 X $2</span> پیکسل، اندازهٔ پرونده: $3، نوع MIME پرونده: $4)',
'file-nohires'         => '<small>تفکیک‌پذیری بالاتری در دسترس نیست.</small>',
'svg-long-desc'        => '(پرونده SVG، با ابعاد $1 × $2 پیکسل، اندازه پرونده: $3)',
'show-big-image'       => 'تصویر با تفکیک‌پذیری بالاتر',
'show-big-image-thumb' => '<small>اندازهٔ این پیش‌نمایش: &#8206;$1 × $2 پیکسل</small>',

# Special:NewFiles
'newimages'             => 'گالری پرونده‌های جدید',
'imagelisttext'         => 'در زیر فهرست $1 {{PLURAL:$1|تصویری|تصویری}} که $2 مرتب شده است آمده است.',
'newimages-summary'     => 'این صفحهٔ ویژه آخرین پرونده‌های بارگذاری شده را نمایش می‌دهد',
'newimages-legend'      => 'پالودن',
'newimages-label'       => 'نام پرونده (یا قسمتی از آن):',
'showhidebots'          => '(ویرایش رُبات‌ها $1)',
'noimages'              => 'چیزی برای دیدن نیست.',
'ilsubmit'              => 'جستجو',
'bydate'                => 'از روی تاریخ',
'sp-newimages-showfrom' => 'نشان‌دادن تصویرهای جدید از $2، $1 به بعد',

# Bad image list
'bad_image_list' => 'اطلاعات را باید به این شکل وارد کنید:

فقط سطرهایی که با * شروع شوند در نظر گرفته می‌شوند. اولین پیوند در هر سطر، باید پیوندی به یک تصویر بد باشد. 
پیوندهایی بعدی در همان سطر، به عنوان موارد استثنا در نظر گرفته می‌شوند.',

# Metadata
'metadata'          => 'متاداده',
'metadata-help'     => 'این پرونده حاوی اطلاعات اضافه‌ای است که احتمالاً توسط دوربین دیجیتالی‌ یا پویشگری که در ایجاد یا دیجیتالی‌کردن آن به کار رفته‌است، افزوده شده‌است. اگر پرونده از وضعیت ابتدایی‌اش تغییر داده شده باشد آنگاه ممکن است شرح و تفصیلات موجود اطلاعات عکس را تماماً بازتاب ندهد.',
'metadata-expand'   => 'نمایش جزئیات تفصیلی',
'metadata-collapse' => 'نهفتن جزئیات تفصیلی',
'metadata-fields'   => 'فرداده EXIF نشان داده شده در این پیغام وقتی جدول فراداده‌های تصویر جمع شده باشد هم نمایش داده می‌شوند.
بقیهٔ موارد تنها زمانی نشان داده می‌شوند که جدول یادشده باز شود.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'عرض',
'exif-imagelength'                 => 'طول',
'exif-bitspersample'               => 'نقطه در هر جزء',
'exif-compression'                 => 'شِمای فشرده‌سازی',
'exif-photometricinterpretation'   => 'ترکیب نقاط',
'exif-orientation'                 => 'جهت',
'exif-samplesperpixel'             => 'تعداد اجزا',
'exif-planarconfiguration'         => 'آرایش داده‌ها',
'exif-ycbcrsubsampling'            => 'نسبت زیرنمونهٔ Y به C',
'exif-ycbcrpositioning'            => 'موقعیت Y و C',
'exif-xresolution'                 => 'تفکیک‌پذیری افقی',
'exif-yresolution'                 => 'تفکیک‌پذیری عمودی',
'exif-resolutionunit'              => 'واحد تفکیک‌پذیری X و Y',
'exif-stripoffsets'                => 'جایگاه داده‌های تصویر',
'exif-rowsperstrip'                => 'تعداد ردیف‌ها در هر نوار',
'exif-stripbytecounts'             => 'بایت در هر نوار فشرده',
'exif-jpeginterchangeformat'       => 'جابه‌جایی نسبت به JPEG SOI',
'exif-jpeginterchangeformatlength' => 'بایت دادهٔ JPEG',
'exif-transferfunction'            => 'تابع تبدیل',
'exif-whitepoint'                  => 'رنگینگی نقطهٔ سفید',
'exif-primarychromaticities'       => 'رنگ‌پذیری اولویت‌ها',
'exif-ycbcrcoefficients'           => 'ضرایب ماتریس تبدیل فضای رنگی',
'exif-referenceblackwhite'         => 'جفت مقادیر مرجع سیاه و سفید',
'exif-datetime'                    => 'تاریخ و زمان تغییر پرونده',
'exif-imagedescription'            => 'عنوان تصویر',
'exif-make'                        => 'شرکت سازندهٔ دوربین',
'exif-model'                       => 'مدل دوربین',
'exif-software'                    => 'نرم‌افزار استفاده‌شده',
'exif-artist'                      => 'عکاس/هنرمند',
'exif-copyright'                   => 'دارندهٔ حق تکثیر',
'exif-exifversion'                 => 'نسخهٔ exif',
'exif-flashpixversion'             => 'نسخهٔ پشتیبانی‌شدهٔ Flashpix',
'exif-colorspace'                  => 'فضای رنگی',
'exif-componentsconfiguration'     => 'معنی هر یک از مؤلفه‌ها',
'exif-compressedbitsperpixel'      => 'حالت فشرده‌سازی تصویر',
'exif-pixelydimension'             => 'عرض تصویر معتبر',
'exif-pixelxdimension'             => 'طول تصویر معتبر',
'exif-makernote'                   => 'تذکرات شرکت سازنده',
'exif-usercomment'                 => 'توضیحات کاربر',
'exif-relatedsoundfile'            => 'پروندهٔ صوتی مربوط',
'exif-datetimeoriginal'            => 'تاریخ و زمان تولید داده‌ها',
'exif-datetimedigitized'           => 'تاریخ و زمان دیجیتالی شدن',
'exif-subsectime'                  => 'کسر ثانیهٔ تاریخ و زمان',
'exif-subsectimeoriginal'          => 'کسر ثانیهٔ زمان اصلی',
'exif-subsectimedigitized'         => 'کسر ثانیهٔ زمان دیجیتال',
'exif-exposuretime'                => 'زمان نوردهی',
'exif-exposuretime-format'         => '$1 ثانیه ($2)',
'exif-fnumber'                     => 'ضریب F',
'exif-exposureprogram'             => 'برنامهٔ نوردهی',
'exif-spectralsensitivity'         => 'حساسیت طیفی',
'exif-isospeedratings'             => 'درجه‌بندی سرعت ایزو',
'exif-oecf'                        => 'عامل تبدیل نوری‌-الکترونیک',
'exif-shutterspeedvalue'           => 'سرعت شاتر',
'exif-aperturevalue'               => 'اندازه دیافراگم',
'exif-brightnessvalue'             => 'روشنی',
'exif-exposurebiasvalue'           => 'خطای نوردهی',
'exif-maxaperturevalue'            => 'حداکثر گشادگی زمین',
'exif-subjectdistance'             => 'فاصلهٔ سوژه',
'exif-meteringmode'                => 'حالت سنجش فاصله',
'exif-lightsource'                 => 'منبع نور',
'exif-flash'                       => 'فلاش',
'exif-focallength'                 => 'فاصلهٔ کانونی عدسی',
'exif-focallength-format'          => '$1 میلی‌متر',
'exif-subjectarea'                 => 'مساحت جسم',
'exif-flashenergy'                 => 'قدرت فلاش',
'exif-spatialfrequencyresponse'    => 'پاسخ بسامد فاصله‌ای',
'exif-focalplanexresolution'       => 'تفکیک‌پذیری X صفحهٔ کانونی',
'exif-focalplaneyresolution'       => 'تفکیک‌پذیری Y صفحهٔ کانونی',
'exif-focalplaneresolutionunit'    => 'واحد تفکیک‌پذیری صفحهٔ کانونی',
'exif-subjectlocation'             => 'مکان سوژه',
'exif-exposureindex'               => 'شاخص نوردهی',
'exif-sensingmethod'               => 'روش حسگری',
'exif-filesource'                  => 'منبع پرونده',
'exif-scenetype'                   => 'نوع صحنه',
'exif-cfapattern'                  => 'الگوی CFA',
'exif-customrendered'              => 'پردازش تصویر',
'exif-exposuremode'                => 'حالت نوردهی',
'exif-whitebalance'                => 'تعادل رنگ سفید (white balance)',
'exif-digitalzoomratio'            => 'نسبت زوم دیجیتال',
'exif-focallengthin35mmfilm'       => 'فاصلهٔ کانونی برای فیلم ۳۵ میلی‌متری',
'exif-scenecapturetype'            => 'نوع ضبط صحنه',
'exif-gaincontrol'                 => 'تنظیم صحنه',
'exif-contrast'                    => 'کنتراست',
'exif-saturation'                  => 'غلظت رنگ',
'exif-sharpness'                   => 'وضوح',
'exif-devicesettingdescription'    => 'شرح تنظیمات دستگاه',
'exif-subjectdistancerange'        => 'محدودهٔ فاصلهٔ سوژه',
'exif-imageuniqueid'               => 'شناسهٔ یکتای تصویر',
'exif-gpsversionid'                => 'نسخهٔ برچسب جی‌پی‌اس',
'exif-gpslatituderef'              => 'عرض جغرافیایی شمالی یا جنوبی',
'exif-gpslatitude'                 => 'عرض جغرافیایی',
'exif-gpslongituderef'             => 'طول جغرافیایی شرقی یا غربی',
'exif-gpslongitude'                => 'طول جغرافیایی',
'exif-gpsaltituderef'              => 'نقطهٔ مرجع ارتفاع',
'exif-gpsaltitude'                 => 'ارتفاع',
'exif-gpstimestamp'                => 'زمان جی‌پی‌اس (ساعت اتمی)',
'exif-gpssatellites'               => 'ماهواره‌های استفاده‌شده برای اندازه‌گیری',
'exif-gpsstatus'                   => 'وضعیت گیرنده',
'exif-gpsmeasuremode'              => 'حالت اندازه‌گیری',
'exif-gpsdop'                      => 'دقت اندازه‌گیری',
'exif-gpsspeedref'                 => 'یکای سرعت',
'exif-gpsspeed'                    => 'سرعت گیرندهٔ جی‌پی‌اس',
'exif-gpstrackref'                 => 'مرجع برای جهت حرکت',
'exif-gpstrack'                    => 'جهت حرکت',
'exif-gpsimgdirectionref'          => 'مرجع برای جهت تصویر',
'exif-gpsimgdirection'             => 'جهت تصویر',
'exif-gpsmapdatum'                 => 'اطلاعات نقشه‌برداری ژئودزیک',
'exif-gpsdestlatituderef'          => 'مرجع برای عرض جغرافیایی مقصد',
'exif-gpsdestlatitude'             => 'عرض جغرافیایی مقصد',
'exif-gpsdestlongituderef'         => 'مرجع برای طول جغرافیایی مقصد',
'exif-gpsdestlongitude'            => 'طول جغرافیایی مقصد',
'exif-gpsdestbearingref'           => 'مرجع برای جهت مقصد',
'exif-gpsdestbearing'              => 'جهت مقصد',
'exif-gpsdestdistanceref'          => 'مرجع برای فاصله تا مقصد',
'exif-gpsdestdistance'             => 'فاصله تا مقصد',
'exif-gpsprocessingmethod'         => 'نام روش پردازش GPS',
'exif-gpsareainformation'          => 'نام ناحیهٔ جی‌پی‌اس',
'exif-gpsdatestamp'                => 'تاریخ جی‌پی‌اس',
'exif-gpsdifferential'             => 'تصحیح جزئی جی‌پی‌اس',

# EXIF attributes
'exif-compression-1' => 'غیرفشرده',

'exif-unknowndate' => 'تاریخ نامعلوم',

'exif-orientation-1' => 'عادی', # 0th row: top; 0th column: left
'exif-orientation-2' => 'افقی پشت و روشده', # 0th row: top; 0th column: right
'exif-orientation-3' => '۱۸۰ درجه چرخیده', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'عمودی پشت و روشده', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '۹۰° پادساعتگرد چرخیده و عمودی پشت و رو شده', # 0th row: left; 0th column: top
'exif-orientation-6' => '۹۰° ساعتگرد چرخیده', # 0th row: right; 0th column: top
'exif-orientation-7' => '۹۰° ساعتگرد چرخیده و عمودی پشت و رو شده', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '۹۰° پادساعتگرد چرخیده', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'قالب فربه',
'exif-planarconfiguration-2' => 'قالب دووجهی',

'exif-xyresolution-i' => '$1 نقطه در اینچ',
'exif-xyresolution-c' => '$1 نقطه در سانتی‌متر',

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

'exif-meteringmode-0'   => 'نامعلوم',
'exif-meteringmode-1'   => 'میانگین',
'exif-meteringmode-2'   => 'میانگین با مرکز سنگین',
'exif-meteringmode-3'   => 'تک‌نقطه‌ای',
'exif-meteringmode-4'   => 'چندنقطه‌ای',
'exif-meteringmode-5'   => 'طرح‌دار',
'exif-meteringmode-6'   => 'جزئی',
'exif-meteringmode-255' => 'غیره',

'exif-lightsource-0'   => 'نامعلوم',
'exif-lightsource-1'   => 'روشنایی روز',
'exif-lightsource-2'   => 'فلورسانت',
'exif-lightsource-3'   => 'تنگستن (نور بدون گرما)',
'exif-lightsource-4'   => 'فلاش',
'exif-lightsource-9'   => 'هوای خوب',
'exif-lightsource-10'  => 'آسمان ابری',
'exif-lightsource-11'  => 'سایه',
'exif-lightsource-12'  => 'مهتابی در روز (D 5700 – 7100K)',
'exif-lightsource-13'  => 'مهتابی سفید در روز (N 4600 – 5400K)',
'exif-lightsource-14'  => 'مهتابی سفید خنک (W 3900 – 4500K)',
'exif-lightsource-15'  => 'مهتابی سفید (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'نور استاندارد A',
'exif-lightsource-18'  => 'نور استاندارد B',
'exif-lightsource-19'  => 'نور استاندارد C',
'exif-lightsource-24'  => 'لامپ تنگستن کارخانه ISO',
'exif-lightsource-255' => 'سایر',

# Flash modes
'exif-flash-fired-0'    => 'بدون زدن فلاش',
'exif-flash-fired-1'    => 'با زدن فلاش',
'exif-flash-return-0'   => 'فاقد عملکرد کشف نور انعکاسی',
'exif-flash-return-2'   => 'نور انعکاسی کشف نشد',
'exif-flash-return-3'   => 'نور انعکاسی کشف شد',
'exif-flash-mode-1'     => 'فلاش زدن جبرانی',
'exif-flash-mode-2'     => 'فلاش نزدن جبرانی',
'exif-flash-mode-3'     => 'حالت خودکار',
'exif-flash-function-1' => 'فاقد عملکرد فلاش',
'exif-flash-redeye-1'   => 'حالت اصلاح سرخی چشم‌ها',

'exif-focalplaneresolutionunit-2' => 'اینچ',

'exif-sensingmethod-1' => 'تعریف نشده',
'exif-sensingmethod-2' => 'حسگر ناحیهٔ رنگی یک تراشه‌ای',
'exif-sensingmethod-3' => 'حسگر ناحیهٔ رنگی دو تراشه‌ای',
'exif-sensingmethod-4' => 'حسگر ناحیهٔ رنگی سه تراشه‌ای',
'exif-sensingmethod-5' => 'حسگر ناحیه‌ای ترتیبی رنگ‌ها',
'exif-sensingmethod-7' => 'حسگر سه‌خطی',
'exif-sensingmethod-8' => 'حسگر خطی ترتیبی رنگ‌ها',

'exif-scenetype-1' => 'تصویر مستقیماً عکاسی شده',

'exif-customrendered-0' => 'پردازش عادی',
'exif-customrendered-1' => 'پردازش سفارشی',

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

'exif-gpsstatus-a' => 'در حال اندازه‌گیری',
'exif-gpsstatus-v' => 'مقایسه‌پذیری اندازه‌گیری',

'exif-gpsmeasuremode-2' => 'اندازه‌گیری دوبعدی',
'exif-gpsmeasuremode-3' => 'اندازه‌گیری سه‌بعدی',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'کیلومتر بر ساعت',
'exif-gpsspeed-m' => 'مایل بر ساعت',
'exif-gpsspeed-n' => 'گره',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'جهت درست',
'exif-gpsdirection-m' => 'جهت مغناطیسی',

# External editor support
'edit-externally'      => 'ویرایش این پرونده با استفاده از ویرایشگر خارجی',
'edit-externally-help' => '(برای اطلاعات بیشتر [http://www.mediawiki.org/wiki/Manual:External_editors دستورالعمل تنظیم] را ببینید)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'همه',
'imagelistall'     => 'همه',
'watchlistall2'    => 'همه',
'namespacesall'    => 'همه',
'monthsall'        => 'همهٔ ماه‌ها',

# E-mail address confirmation
'confirmemail'             => 'تأیید نشانی پست الکترونیکی',
'confirmemail_noemail'     => 'شما در صفحهٔ [[Special:Preferences|ترجیحات کاربری]] خود نشانی پست الکترونیکی معتبری وارد نکرده‌اید.',
'confirmemail_text'        => 'این ویکی شما را ملزم به تأیید اعتبار پست الکترونیکی خود، پیش از استفاده از خدمات پست الکترونیکی اینجا می‌کند. دکمهٔ زیرین را فعال کنید تا نامهٔ تأییدی به نشانی شما فرستاده شود. این نامه دربردارندهٔ پیوندی خواهد بود که حاوی یک کد است. پیوند را در مرورگر خود بار کنید (اجرا) کنید تا اعتبار نشانی پست الکترونیکی شما مسجل شود.',
'confirmemail_pending'     => 'یک کد تأییدی پیشتر برای شما به صورت الکترونیکی فرستاده شده‌است. اگر همین اواخر حساب خود را باز کرده‌اید شاید بد نباشد که پیش از درخواست یک کد جدید چند دقیقه درنگ کنید تا شاید نامهٔ قبلی برسد.',
'confirmemail_send'        => 'پُست‌کردن یک کد تأیید',
'confirmemail_sent'        => 'نامهٔ الکترونیکی تأییدی فرستاده شد.',
'confirmemail_oncreate'    => 'یک کد تأییدی به نشانی پست الکترونیکی شما فرستاده شد. برای واردشدن به سیستم نیازی به این کد نیست، ولی برای راه‌اندازی امکانات وابسته به پست الکترونیکی در این ویکی به آن نیاز خواهید داشت.',
'confirmemail_sendfailed'  => 'فرستادن پست الکترونیکی تأییدی ممکن نشد.
نشانی پست الکترونیکی را از نظر وجود نویسه‌های نامعتبر وارسی کنید.

پاسخ سامانه ارسال پست الکترونیکی: $1',
'confirmemail_invalid'     => 'کد تأیید نامعتبر است. ممکن است که منقضی شده باشد.',
'confirmemail_needlogin'   => 'برای تأیید نشانی پست الکترونیکتان نیاز به $1 دارید.',
'confirmemail_success'     => 'نشانی پست الکترونیکی شما تأیید شده‌است. همکنون می‌توانید به سیستم وارد شده و از ویکی لذت ببرید.',
'confirmemail_loggedin'    => 'نشانی پست الکترونیکی شما تأیید شد.',
'confirmemail_error'       => 'هنگام ذخیرهٔ تأیید شما به مشکلی برخورده شد.',
'confirmemail_subject'     => 'تأیید نشانی پست الکترونیکی {{SITENAME}}',
'confirmemail_body'        => 'Someone, probably you from IP address $1, has registered an
account "$2" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If you did *not* register the account, follow this link
to cancel the e-mail address confirmation:

$5

If this is *not* you, don\'t follow the link. This confirmation code
will expire at $4.

---------

یک نفر، احتمالاً خود شما، از نشانی اینترنتی $1 حساب کاربری‌ای با نام «$2» و این نشانی پست‌الکترونیکی در {{SITENAME}} ایجاد کرده‌است.

برای تأیید اینکه این حساب واقعاً متعلق به شماست و نیز برای فعال‌سازی امکانات ایمیلیِ {{SITENAME}} پیوند را در مرورگر اینترنت خود باز کنید:

$3

اگر شما این حساب کاربری را ثبت *نکرده‌اید*، لطفاً پیوند زیر را
دنبال کنید تا تایید نشانی پست الکترونیکی لغو شود:

$5

اگر این شما *نیستید* لطفاً پیوند را دنبال مکنید. این کدِ تأیید در تاریخ $4 منقضی خواهد شد.
</div>',
'confirmemail_invalidated' => 'تایید نشانی پست الکترونیکی لغو شد',
'invalidateemail'          => 'لغو کردن تایید نشانی پست الکترونیکی',

# Scary transclusion
'scarytranscludedisabled' => '[تراگنجانش بین‌ویکیانه فعال نیست]',
'scarytranscludefailed'   => '[فراخوانی الگو برای $1 میسر نشد]',
'scarytranscludetoolong'  => '[نشانی اینترنتی مورد نظر (URL) بیش از اندازه بلند بود]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
بازتاب این صفحه در وب‌نوشت‌ها:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 حذف])',
'trackbacklink'     => 'بازتاب',
'trackbackdeleteok' => 'بازتاب صفحه با موفقیت حذف شد.',

# Delete conflict
'deletedwhileediting' => "'''هشدار''': این صفحه پس از اینکه شما آغاز به ویرایش آن کرده‌اید، حذف شده است!",
'confirmrecreate'     => "کاربر [[User:$1|$1]] ([[User talk:$1|بحث]]) این مقاله را پس از اینکه شما آغاز به ویرایش آن نموده‌اید به دلیل زیر حذف کرده است :
: ''$2''
لطفاً تأیید کنید که مجدداً می‌خواهید این مقاله را بسازید.",
'recreate'            => 'بازایجاد',

# action=purge
'confirm_purge_button' => 'تأیید',
'confirm-purge-top'    => 'پاک کردن نسخهٔ حافظهٔ نهانی (Cache) این صفحه را تأیید می‌کنید؟',
'confirm-purge-bottom' => 'خالی کردن میانگیر یک صفحه باعث می‌شود که آخرین نسخهٔ آن نمایش یابد.',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator'     => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '&rarr; صفحهٔ پیشین',
'imgmultipagenext' => 'صفحهٔ بعد &larr;',
'imgmultigo'       => 'برو!',
'imgmultigoto'     => 'رفتن به صفحهٔ $1',

# Table pager
'ascending_abbrev'         => 'صعودی',
'descending_abbrev'        => 'نزولی',
'table_pager_next'         => 'صفحهٔ بعدی',
'table_pager_prev'         => 'صفحه قبل',
'table_pager_first'        => 'صفحهٔ نخست',
'table_pager_last'         => 'صفحهٔ آخر',
'table_pager_limit'        => 'نمایش $1 مورد در هر صفحه',
'table_pager_limit_submit' => 'برو',
'table_pager_empty'        => 'هیچ نتیجه',

# Auto-summaries
'autosumm-blank'   => 'صفحه را خالی کرد',
'autosumm-replace' => "جایگزینی صفحه با '$1'",
'autoredircomment' => 'تغییر مسیر به [[$1]]',
'autosumm-new'     => "صفحه‌ای جدید با '$1' ایجاد کرد",

# Size units
'size-bytes'     => '$1 بایت',
'size-kilobytes' => '$1 کیلوبایت',
'size-megabytes' => '$1 مگابایت',
'size-gigabytes' => '$1 گیگابایت',

# Live preview
'livepreview-loading' => 'در حال بارشدن…',
'livepreview-ready'   => 'بارشدن… آماده!',
'livepreview-failed'  => 'پیش‌نمایش زنده به مشکل برخورد! لطفاً از پیش‌نمایش عادی استفاده کنید',
'livepreview-error'   => 'ارتباط به مشکل برخورد: $1 "$2" از پیش‌نمایش عادی استفاده کنید.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'ممکن است تغییرات تازه‌تر از $1 {{PLURAL:$1|ثانیه|ثانیه}} در این فهرست نشان داده نشوند.',
'lag-warn-high'   => 'ممکن است ، به خاطر پس‌افتادگی زیاد کارگزار دادگان، تغییرات تازه‌تر از $1 {{PLURAL:$1|ثانیه|ثانیه}}، در این فهرست نشان داده نشده باشند.',

# Watchlist editor
'watchlistedit-numitems'       => 'فهرست پی‌گیری‌های شما شامل {{PLURAL:$1|$1 صفحه|$1 صفحه}} به جز صفحه‌های بحث است.',
'watchlistedit-noitems'        => 'فهرست پی‌گیری‌های شما خالی است.',
'watchlistedit-normal-title'   => 'ویرایش فهرست پی‌گیری‌ها',
'watchlistedit-normal-legend'  => 'حذف عنوان‌ها از فهرست پی‌گیری‌ها',
'watchlistedit-normal-explain' => 'عنوان‌های موجود در فهرست پیگیری شما در زیر نشان داده شده‌اند.
برای حذف یک عنوان جعبهٔ کنار آن را علامت بزنید و دکمهٔ حذف عنوان‌ها را بزنید.
شما هم‌چنین می‌توانید [[Special:Watchlist/raw|فهرست خام را ویرایش کنید]].',
'watchlistedit-normal-submit'  => 'حذف عنوان‌ها',
'watchlistedit-normal-done'    => '$1 عنوان از فهرست پی‌گیری‌های شما حذف {{PLURAL:$1|شد|شدند}}:',
'watchlistedit-raw-title'      => 'ویرایش فهرست خام پی‌گیری‌ها',
'watchlistedit-raw-legend'     => 'ویرایش فهرست خام پی‌گیری‌ها',
'watchlistedit-raw-explain'    => 'عنوان‌های موجود در فهرست پی‌گیری‌های شما در زیر نشان داده شده‌اند، و شما می‌توانید مواردی را حذف یا اضافه کنید. 
هر مورد در یک سطر جداگانه باید قرار بگیرد. در پایان، دکمهٔ «به روز رساندن پی‌گیری‌ها» را بفشارید. 
توجه کنید که شما می‌توانید از [[Special:Watchlist/edit|ویرایشگر استاندارد فهرست پی‌گیری‌ها]] هم استفاده کنید.',
'watchlistedit-raw-titles'     => 'عنوان‌ها:',
'watchlistedit-raw-submit'     => 'به روز رساندن پی‌گیری‌ها',
'watchlistedit-raw-done'       => 'فهرست پی‌گیری‌های شما به روز شد.',
'watchlistedit-raw-added'      => '$1 عنوان به فهرست پی‌گیری‌ها اضافه {{PLURAL:$1|شد|شدند}}:',
'watchlistedit-raw-removed'    => '$1 عنوان حذف {{PLURAL:$1|شد|شدند}}:',

# Watchlist editing tools
'watchlisttools-view' => 'فهرست پی‌گیری‌ها',
'watchlisttools-edit' => 'مشاهده و ویرایش فهرست پی‌گیری‌ها',
'watchlisttools-raw'  => 'ویرایش فهرست خام پی‌گیری‌ها',

# Iranian month names
'iranian-calendar-m1'  => 'فروردین',
'iranian-calendar-m2'  => 'اردیبهشت',
'iranian-calendar-m3'  => 'خرداد',
'iranian-calendar-m4'  => 'تیر',
'iranian-calendar-m5'  => 'مرداد',
'iranian-calendar-m6'  => 'شهریور',
'iranian-calendar-m7'  => 'مهر',
'iranian-calendar-m8'  => 'آبان',
'iranian-calendar-m9'  => 'آذر',
'iranian-calendar-m10' => 'دی',
'iranian-calendar-m11' => 'بهمن',
'iranian-calendar-m12' => 'اسفند',

# Hebrew month names
'hebrew-calendar-m1'      => 'تشری',
'hebrew-calendar-m2'      => 'حشوان',
'hebrew-calendar-m3'      => 'کسلو',
'hebrew-calendar-m4'      => 'طوت',
'hebrew-calendar-m5'      => 'شباط',
'hebrew-calendar-m6'      => 'آذار',
'hebrew-calendar-m6a'     => 'آذار',
'hebrew-calendar-m6b'     => 'واذار',
'hebrew-calendar-m7'      => 'نیسان',
'hebrew-calendar-m8'      => 'ایار',
'hebrew-calendar-m9'      => 'سیوان',
'hebrew-calendar-m10'     => 'تموز',
'hebrew-calendar-m11'     => 'آب',
'hebrew-calendar-m12'     => 'ایلول',
'hebrew-calendar-m1-gen'  => 'تشری',
'hebrew-calendar-m2-gen'  => 'حشوان',
'hebrew-calendar-m3-gen'  => 'کسلو',
'hebrew-calendar-m4-gen'  => 'طوت',
'hebrew-calendar-m5-gen'  => 'شباط',
'hebrew-calendar-m6-gen'  => 'آذار',
'hebrew-calendar-m6a-gen' => 'آذار',
'hebrew-calendar-m6b-gen' => 'واذار',
'hebrew-calendar-m7-gen'  => 'نیسان',
'hebrew-calendar-m8-gen'  => 'ایار',
'hebrew-calendar-m9-gen'  => 'سیوان',
'hebrew-calendar-m10-gen' => 'تموز',
'hebrew-calendar-m11-gen' => 'آب',
'hebrew-calendar-m12-gen' => 'ایلول',

# Core parser functions
'unknown_extension_tag' => 'برچسب ناشناختهٔ افزونه «$1»',
'duplicate-defaultsort' => 'هشدار: ترتیب پیش فرض «$2» ترتیب پیش فرض قبلی «$1» را باطل می‌سازد.',

# Special:Version
'version'                          => 'نسخه', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'افزونه‌های نصب شده',
'version-specialpages'             => 'صفحه‌های ویژه',
'version-parserhooks'              => 'قلاب‌های تجزیه‌گر',
'version-variables'                => 'متغیرها',
'version-other'                    => 'غیره',
'version-mediahandlers'            => 'به‌دست‌گیرنده‌های رسانه‌ها',
'version-hooks'                    => 'قلاب‌ها',
'version-extension-functions'      => 'عملگرهای افزونه',
'version-parser-extensiontags'     => 'برچسب‌های افزونه تجزیه‌گر',
'version-parser-function-hooks'    => 'قلاب‌های عملگر تجزیه‌گر',
'version-skin-extension-functions' => 'عملگرهای افزونه‌های پوسته',
'version-hook-name'                => 'نام قلاب',
'version-hook-subscribedby'        => 'وارد شده توسط',
'version-version'                  => 'نسخه',
'version-license'                  => 'اجازه‌نامه',
'version-software'                 => 'نسخهٔ نصب‌شده',
'version-software-product'         => 'محصول',
'version-software-version'         => 'نسخه',

# Special:FilePath
'filepath'         => 'مسیر پرونده',
'filepath-page'    => 'پرونده:',
'filepath-submit'  => 'مسیر',
'filepath-summary' => 'این صفحهٔ ویژه نشانی کامل برای یک پرونده را نشان می‌دهد. تصاویر با کیفیت وضوح کامل نشان داده می‌شوند، سایر انواع پرونده با برنامه مخصوص به خودشان باز می‌شوند.

نشانی پرونده را بدون پیشوند «{{ns:file}}:» وارد کنید.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'جستجو برای پرونده‌های تکراری',
'fileduplicatesearch-summary'  => 'جستجو برای پرونده‌های تکراری بر اساس مقدار درهم‌شدهٔ آن‌ها صورت می‌گیرد.

نام پرونده را بدون پیشوند «{{ns:file}}:» وارد کنید.',
'fileduplicatesearch-legend'   => 'جستجوی موارد تکراری',
'fileduplicatesearch-filename' => 'نام پرونده:',
'fileduplicatesearch-submit'   => 'جستجو',
'fileduplicatesearch-info'     => '$1 Ã— $2 پیکسل<br />اندازه پرونده: $3<br />نوع MIME: $4',
'fileduplicatesearch-result-1' => 'پروندهٔ «$1» مورد تکراری ندارد.',
'fileduplicatesearch-result-n' => 'پروندهٔ «$1» دارای {{PLURAL:$2|یک مورد تکراری|$2 مورد تکراری}} است.',

# Special:SpecialPages
'specialpages'                   => 'صفحه‌های ویژه',
'specialpages-note'              => '<hr />
* دسترسی به صفحه‌های ویژهٔ <span class="mw-specialpagerestricted">رنگی</span> محدود شده‌است.',
'specialpages-group-maintenance' => 'گزارش‌های نگهداری',
'specialpages-group-other'       => 'صفحه‌های ویژهٔ دیگر',
'specialpages-group-login'       => 'ورود / ثبت نام',
'specialpages-group-changes'     => 'تغییرات اخیر و سیاهه‌ها',
'specialpages-group-media'       => 'گزارش بارگذاری رسانه‌ها',
'specialpages-group-users'       => 'کاربرها و دسترسی‌ها',
'specialpages-group-highuse'     => 'صفحه‌های پربازدید',
'specialpages-group-pages'       => 'فهرست‌های صفحه‌ها',
'specialpages-group-pagetools'   => 'ابزارهای صفحه‌ها',
'specialpages-group-wiki'        => 'اطلاعات و ابزارهای ویکی',
'specialpages-group-redirects'   => 'صفحه‌های ویژهٔ تغییر مسیر دهنده',
'specialpages-group-spam'        => 'ابزارهای مرتبط با هرزنگاری',

# Special:BlankPage
'blankpage'              => 'صفحهٔ خالی',
'intentionallyblankpage' => 'این صفحه به طور عمدی خالی گذاشته شده و برای محک زدن سیستم و غیره به کار می‌رود.',

# External image whitelist
'external_image_whitelist' => ' #این سطر را همان‌گونه که هست رها کنید<pre>
#قطعات regular expression را در زیر قرار دهید (تنها قسمتی که بین // قرار می‌گیرد)
#آن‌ها با نشانی اینترنتی تصاویر خارجی پیوند داده شده تطبیق داده می‌شوند
#مواردی که مطابقت نشان دهند به صورت تصویر نمایش می‌یابند، و در غیر این صورت تنها یک پیوند به تصویر نمایش می‌یابد
#سطرهایی که با # آغاز شوند به عنوان توضیحات در نظر گرفته می‌شوند

#قطعات regex را زیر این سطر قرار دهید. این سطر را همان‌گونه که هست رها کنید</pre>',

# Special:Tags
'tags'                    => 'برچسب‌های تغییر مجاز',
'tag-filter'              => 'پالایهٔ [[Special:Tags|برچسب‌ها]]:',
'tag-filter-submit'       => 'پالایه',
'tags-title'              => 'برچسب‌ها',
'tags-intro'              => 'این صفحه برچسب‌هایی را که نرم‌افزار ممکن است ویرایش‌ها را توسط آن‌ها علامت گذاری کند، به همراه معنای آن‌ها فهرست می‌کند.',
'tags-tag'                => 'نام داخلی برچسب',
'tags-display-header'     => 'نمایش در فهرست‌های تغییرات',
'tags-description-header' => 'توضیح کامل معنی',
'tags-hitcount-header'    => 'تغییرهای برچسب‌دار',
'tags-edit'               => 'ویرایش',
'tags-hitcount'           => '$1 {{PLURAL:$1|تغییر|تغییر}}',

# Database error messages
'dberr-header'      => 'این ویکی یک ایراد دارد',
'dberr-problems'    => 'شرمنده! این وبگاه از مشکلات فنی رنج می‌برد.',
'dberr-again'       => 'چند دقیقه صبر کند و دوباره صفحه را بارگیری کنید.',
'dberr-info'        => '(امکان برقراری ارتباط با کارساز پایگاه داده وجود ندارد: $1)',
'dberr-usegoogle'   => 'در این مدت می‌توانید با استفاده از گوگل جستجو کنید.',
'dberr-outofdate'   => 'توجه کنید که نمایه‌های آن‌ها از محتوای ما ممکن است به روز نباشد.',
'dberr-cachederror' => 'آن‌چه در ادامه می‌آید یک کپی از صفحهٔ درخواست شده است که در کاشه قرار دارد، و ممکن است به روز نباشد.',

);
