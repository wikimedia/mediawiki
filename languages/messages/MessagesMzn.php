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

$messages = array(
# User preference toggles
'tog-underline' => 'پیوندون زیر خط دکشی بواشه',
'tog-justify' => 'بندون تموم چین هاکرد‌ن',
'tog-hideminor' => 'نشون‌ندائن کچیک تغییرات تازه دگاردسته‌ئون دله',
'tog-hidepatrolled' => 'جا بدائن دچی‌یه‌ئون پس بخارد تازه دگاردسته‌ئون ِدله',
'tog-newpageshidepatrolled' => 'قایم هکردن گشت بخرد ولگون نو ولگون فهرست جا',
'tog-extendwatchlist' => 'گت تر هکردن دمبال هکرده‌ئون فهرست تموم دگارسه‌ئون سر، و نا فقط آخرین  موردون',
'tog-usenewrc' => 'استفاده از تازه دگاردسته‌ئون گت‌تر بَیی (نیازمند جاوااسکریپت)',
'tog-numberheadings' => 'شماره بشتن خدکار عناوین',
'tog-showtoolbar' => 'دچی‌ین جعبه نوار ابزار ره سِراق هدائن',
'tog-editondblclick' => 'دچی ین ولگون با دتا کلیک (نیازمند جاوااسکریپت)',
'tog-editsection' => 'به کار دمبدائن تیکه‌ئون دچی‌ین از طریق پیوندون [دچی‌ین]',
'tog-editsectiononrightclick' => 'به کار دمبدائن دچی‌ین قسمت‌ئون با راست کیلیک<br />عناوین قسمت‌ئون ِرو (جاوااسکریپت)',
'tog-showtoc' => 'نیمایش محتوا<br />(برای مقاله‌ئون با بیشته از ۳ سرفصل)',
'tog-rememberpassword' => 'مه رمز ره (تا حداکثر $1 {{PLURAL:$1|روز|روز}}) این مرورگر دله یاد دار',
'tog-watchcreations' => 'ایضافه بین صفحه‌ئونی که من دِرِس هاکردمه به پیگیری‌ئون ِرج.',
'tog-watchdefault' => 'اضافه هاکردن صفحه‌هایی که چیمبه به منه پیگری ِرج',
'tog-watchmoves' => 'صفحه‌ئونی که کشمبه ره منه پِگیری ِرج دله بنویس',
'tog-watchdeletion' => 'اضافه هاکردن صفحه‌هایی که پاک کامبه به منه پیگری ِرج',
'tog-minordefault' => 'همه صفحه‌ئون دچیه ره جزئی پیش‌گامون دار',
'tog-previewontop' => 'نمایش پیش‌نمایش قبل از دچی‌ین ِجعبه(نا قبل از وه).',
'tog-previewonfirst' => 'پیش نیمایش زمون اولین دچی‌ین',
'tog-nocache' => 'حافظهٔ نهونی مرورگر از کار دمبداء بوو',
'tog-enotifwatchlistpages' => 'اگه منه پگری‌ئون ره تغییر هدانه مسّه ایمیل بزن',
'tog-enotifusertalkpages' => 'هر گادر منه کاروری صفخه‌ی گپ دله ات چی بنویشنه مه سّه ایمیل بزن',
'tog-enotifminoredits' => 'هرگادر صحه ها دله اتا خورد چی ره عوض هکردنه مه وسّه ایمیل بزن',
'tog-enotifrevealaddr' => 'منه ایمیل نامه ئون ایطیلاع رسونی دله دواشه',
'tog-shownumberswatching' => 'دمبالکرونِ سِراق هدائن',
'tog-oldsig' => 'پیش نیمایش ایمضای موجود:',
'tog-fancysig' => 'ایمضا ره ویکی متن نظر بیرین (بدون لینک هایتن)',
'tog-uselivepreview' => 'ایستیفاده از پیش نیمایش زنده (جاوا اسکریپ) (جدیده)',
'tog-forceeditsummary' => 'زمونی که خولاصه دچی‌ین ره ننویشتمه مه ره بائو',
'tog-watchlisthideown' => 'دپوشنی‌ین کارای من پیگریای ِفهرست دله',
'tog-watchlisthidebots' => 'دپوشنی‌ین کارای روبات‌ئون منه پیگیرایای ِفهرست دله',
'tog-watchlisthideminor' => 'خورد عوض بیی ها ره منه پیگیری ِرج دله نشون ندده',
'tog-watchlisthideliu' => 'کارای کارورنی که حیساب دارنه ره دپوشِن',
'tog-watchlisthideanons' => 'کارای کارورونی که حیساب ندارنه ره منه پیگری ِرج دله دپوشن.',
'tog-watchlisthidepatrolled' => 'دپوشنی‎ین دچیه‌ئون گشت بخارد منه پیگری ِفهرست دله جه',
'tog-ccmeonemails' => 'برسنی‌ین رونوشت نامه‌ئونی که به کارورون رسنمبه مه وسه هم برسنی‌یه بواشه.',
'tog-diffonly' => 'محتوای صفحه ، تفاوت بِن نیمایش هدائه نواشه.',
'tog-showhiddencats' => 'دپوشونیه رج‌ئون ره نشون هاده',
'tog-norollbackdiff' => 'بعد واگردونی تفاوت ره نشون نده',

'underline-always' => 'همیشه مازرونی',
'underline-never' => 'دکل',
'underline-default' => 'پیش‌فرض مرورگر',

# Font style option in Special:Preferences
'editfont-style' => 'دچی ین جعبه قلم سبک:',
'editfont-default' => 'پیش فرض مرورگر',
'editfont-monospace' => 'فونت Monospaced',
'editfont-sansserif' => 'فونت Sans-serif',
'editfont-serif' => 'فونت Serif',

# Dates
'sunday' => 'یه‌شنبه',
'monday' => 'دِشنبه',
'tuesday' => 'سه‌شنبه',
'wednesday' => 'چارشنبه',
'thursday' => 'پنج‌شنبه',
'friday' => 'جومه',
'saturday' => 'شنبه',
'sun' => 'یه‌شنبه',
'mon' => 'دِشنبه',
'tue' => 'سه‌شنبه',
'wed' => 'چارشنبه',
'thu' => 'پنجشنبه',
'fri' => 'جـومه',
'sat' => 'شمبه',
'january' => 'ژانویه',
'february' => 'فوریه',
'march' => 'مارس',
'april' => 'آوریل',
'may_long' => 'مه',
'june' => 'ژوئن',
'july' => 'ژوئیه',
'august' => 'ئـوگـه‌سـت',
'september' => 'سـه‌پـتـه‌مـبـر',
'october' => 'اکتبر',
'november' => 'نـووه‌مـبـر',
'december' => 'دسامبر',
'january-gen' => 'ژانویه',
'february-gen' => 'فوریه',
'march-gen' => 'مـارس',
'april-gen' => 'آوریـل',
'may-gen' => 'مه',
'june-gen' => 'جـون',
'july-gen' => 'ژوئیه',
'august-gen' => 'ئوگـه‌سـت',
'september-gen' => 'سـه‌پـتـه‌مـبـر',
'october-gen' => 'اکتبر',
'november-gen' => 'نـووه‌مـبـر',
'december-gen' => 'دسامبر',
'jan' => 'جانویه',
'feb' => 'فه‌وریه',
'mar' => 'مارچ',
'apr' => 'آوریل',
'may' => 'مه',
'jun' => 'ژوئن',
'jul' => 'جولای',
'aug' => 'ئوگوست',
'sep' => 'سه‌پته‌مبر',
'oct' => 'ئوکتوبر',
'nov' => 'نووه‌مبر',
'dec' => 'دسامبر',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|رج|رج‌ئون}}',
'category_header' => '"$1" ره دله صفحه‌ئون',
'subcategories' => 'جیر رج‌ئون',
'category-media-header' => '«$1» رج پرونده‌ئون',
'category-empty' => 'ای رج ره ده‌له ئه‌سا هیچی دأنیه',
'hidden-categories' => '{{PLURAL:$1|خف رج|خف رجون}}',
'hidden-category-category' => 'خف رجون',
'category-subcat-count' => '{{PLURAL:$2|اینتا رج همینتا زیر رج ره داینه.|{{PLURAL:$1|اینتا زیررج|اینتا $1 زیررج}} اینتا رج ره {{PLURAL:$1|داینه|دایننه}}؛ اینتا رج در کل حاوی $2 زیررج هسه.}}',
'category-subcat-count-limited' => 'اینتا رج {{PLURAL:$1|اتا زیر رج|$1 زبررج}} ره شامل بونه.',
'category-article-count' => '{{PLURAL:$2|این رج همینتا صفحه ره دانّه.|ای  {{PLURAL:$1صفحه|صفحه|$1 ئون}}، $2 جه اینجه دَرنه.}}',
'category-article-count-limited' => '{{PLURAL:$1|صفحهٔ|$1 صفحهٔ}} که این بن درنه اینتا رج دله قرار هایتنه.',
'category-file-count' => '{{PLURAL:$2|این رج دله فقط همینتا عکس دره.|{{PLURAL:$1|این اتا پرونده|این $1تا پرونده}} این رج دله {{PLURAL:$1|دره|درنه}}؛ این رج کلاً $2تا پرونده دانّه.}}',
'category-file-count-limited' => '{{PLURAL:$1|پرونده|$1 پرونده}} این رج دله درنه.',
'listingcontinuesabbrev' => '(دمباله)',
'index-category' => 'صفحه‌ئون نمایه بَیی',
'noindex-category' => 'صفحه‌ئون نمایه نَیی',
'broken-file-category' => 'صفحه‌ئونی که اتا عکس اسا وشون سر دنی‌یه',

'about' => 'درباره',
'article' => 'صفحه‌ی بنویشته‌ئون',
'newwindow' => '(ته‌رنه‌ روجین ده‌له‌ وا بونه)',
'cancel' => 'ول هاکردن',
'moredotdotdot' => 'ویـشـتـه...',
'mypage' => 'مه صفحه',
'mytalk' => 'مه گپ',
'anontalk' => 'گپ بزوئن اینتا آی‌پی وسّه',
'navigation' => 'بگردستن',
'and' => '&#32;و',

# Cologne Blue skin
'qbfind' => 'پیدا هکردن',
'qbbrowse' => 'چأرخه‌سه‌ن',
'qbedit' => 'دچی‌ین',
'qbpageoptions' => 'این صفحه',
'qbmyoptions' => 'مه صفحه‌ئون',
'qbspecialpages' => 'شا صفحه‌ئون',
'faq' => 'معمولی سوالا',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'ترنه گپ بزوئن',
'vector-action-delete' => 'پاک هاکردن',
'vector-action-move' => 'دکش هاکردن',
'vector-action-protect' => 'زلفن بزوئن',
'vector-action-undelete' => 'دباره بنویشته بیّن',
'vector-action-unprotect' => 'زلفن عوض هاکردن',
'vector-simplesearch-preference' => 'فعال هاکردن پیشنهادون بگردستن پیشرفته (فقط پوسته برداری دله)',
'vector-view-create' => 'بساتن',
'vector-view-edit' => 'دچی‌ین',
'vector-view-history' => 'تاریخچه ره بَدی‌ین',
'vector-view-view' => 'بخوندستن',
'vector-view-viewsource' => 'ونه منبع ره هارشائن',
'actions' => 'عملکاردون',
'namespaces' => 'ایسم فضائون',
'variants' => 'گویش‌ئون',

'errorpagetitle' => 'خطا!',
'returnto' => 'بردگستن تا $1',
'tagline' => '{{SITENAME}} جه',
'help' => 'راهنما',
'search' => 'بگردستن',
'searchbutton' => 'چرخ‌هایی',
'go' => 'بـور',
'searcharticle' => 'بور',
'history' => 'صفحه‌ی تاریخچه',
'history_short' => 'تاریخچه',
'updatedmarker' => 'عوض بَیی پس از آخرین بار که بی‌یمومه',
'printableversion' => 'پرینت‌هاکردنی صفحه',
'permalink' => 'بموندستنی لینک',
'print' => 'پرینت',
'view' => 'نمایش',
'edit' => 'دچی‌ین',
'create' => 'بساتن',
'editthispage' => 'این صفحه ره دچی‌ین',
'create-this-page' => 'این صفحه ره شِما بساجین',
'delete' => 'پاک هاکردن',
'deletethispage' => 'این صفحه ره پاک هاکردن',
'undelete_short' => 'احیای {{PLURAL:$1|ات دچی‌یه|$1 دچی‌یه}}',
'viewdeleted_short' => 'نمایش {{PLURAL:$1|اتا دچی‌یه حذف بَیی|$1 دچی‌یه حذف بَیی}}',
'protect' => 'زلفن بزوئن',
'protect_change' => 'دگاردنی‌ین',
'protectthispage' => 'این صفحه ره زلفن بزن',
'unprotect' => 'زلفن عوض هاکردن',
'unprotectthispage' => 'این زلفن ره عوض بدل هاکن',
'newpage' => 'نو صفحه',
'talkpage' => 'این صفحه درباره گپ بَزوئِن',
'talkpagelinktext' => 'گپ',
'specialpage' => 'شا صفحه',
'personaltools' => 'مه‌شه ابزار',
'postcomment' => 'نو تیکه',
'articlepage' => 'نمایش صفحه',
'talk' => 'گپ',
'views' => 'هارشی‌ئون',
'toolbox' => 'اَبزارِ جا',
'userpage' => 'کارور صفحه ره سِراق هدائن',
'projectpage' => 'بدی‌ین پروژه‌ی ِصفحه',
'imagepage' => 'بدی‌ین ِعکس ِصفحه',
'mediawikipage' => 'پیغوم ره بدی‌ین',
'templatepage' => 'بدی‌ین شابلون',
'viewhelppage' => 'راهنما صفحه هارشی‌ین',
'categorypage' => 'بدی‌ین رج',
'viewtalkpage' => 'گپون ره سِراق هدائن',
'otherlanguages' => 'بقیه زوون‌ئون',
'redirectedfrom' => '($1 جه بموئه)',
'redirectpagesub' => 'گجگی‌بَیتـِن',
'lastmodifiedat' => 'این صفحه ره آخرین بار این گادر دچینه:
$2، $1',
'viewcount' => 'این صفحه {{PLURAL:$1|ات|$1}} بار بدی‌یه بیّه',
'protectedpage' => 'صفحه محافظت‌بَیی',
'jumpto' => 'کپّل بیّن به:',
'jumptonavigation' => 'بگردستن',
'jumptosearch' => 'بخوندستن',
'pool-queuefull' => 'مخزن ِصف پر بیّه',
'pool-errorunknown' => 'خطای ناشناخته',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}} درباره',
'aboutpage' => 'Project:درباره',
'copyright' => 'این صفحه ره بَنوشته‌ئون  $1  زیر شِمه دسـت دَرنه.',
'copyrightpage' => '{{ns:project}}:کـوپـی‌راسـت‌ئون',
'currentevents' => 'اسایی دکته‌ئون',
'currentevents-url' => 'Project:اسایی دکته‌ئون',
'disclaimers' => 'تکذیب‌نومه‌ئون',
'disclaimerpage' => 'Project:تکذیب‌نومه',
'edithelp' => 'دچی‌ین رانما',
'helppage' => 'Help:راهنما',
'mainpage' => 'گت صفحه',
'mainpage-description' => 'گت صفحه',
'policy-url' => 'Project:سیاستون',
'portal' => 'کارورون ِلوش',
'portal-url' => 'Project:کارورون لوش',
'privacy' => 'سیاست حفظ اسرار',
'privacypage' => 'Project:سیاست_حفظ_اسرار',

'badaccess' => 'نتوندی هچی ره هارشی',
'badaccess-group0' => 'شما این کار ره نتونی هاکنین.',
'badaccess-groups' => 'عملی که بخاستنی منحصر به کارورون {{PLURAL:$2|این گروه|این گروه‌ئون}} هسته: $1.',

'versionrequired' => 'نوسخهٔ $1 نرم‌افزار مدیاویکی جه لازم هسّه',
'versionrequiredtext' => 'این صفحه‌ی بدی‌ین وسّه به نسخهٔ $1 نرم‌افزار مدیاویکی جه نیاز دارنی.
به [[Special:Version|این صفحه]] بورین.',

'ok' => 'خا',
'retrievedfrom' => '"$1" جه بیته بیّه',
'youhavenewmessages' => 'شما اتا $1 دانّی ($2).',
'newmessageslink' => 'ترنه پیغوم‌ئون',
'newmessagesdifflink' => 'پایانی دچی‌یه',
'youhavenewmessagesfromusers' => 'شِما {{PLURAL:$3| کارور دیگه| $3  کارور}} $1 دارنی ($2).',
'youhavenewmessagesmanyusers' => 'شما ات‌سری کارور جه $1 دارنی ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|ترنه پیغوم|ترنه پیغوم}}',
'newmessagesdifflinkplural' => '{{formatnum:$1}} {{PLURAL:$1|ترنه دچی‌یه|ترنه دچی‌یه‌ئون}}',
'youhavenewmessagesmulti' => 'شِمه وسّه $1 دله، ترنه پیغوم برسی‌یه.',
'editsection' => 'دچی‌ین',
'editold' => 'دچی‌ین',
'viewsourceold' => 'منبع ره هارشائن',
'editlink' => 'دچی‌ین',
'viewsourcelink' => 'منبع بدی‌ین',
'editsectionhint' => 'تیکه: $1 ره دچی‌ین',
'toc' => 'دله',
'showtoc' => 'سِراق هاده',
'hidetoc' => 'فرو بور',
'collapsible-collapse' => 'دوستن',
'collapsible-expand' => 'گت هاکردن',
'thisisdeleted' => 'نیمایش یا دِباره دربیاردنِ $1؟',
'viewdeleted' => 'نمایش $1؟',
'restorelink' => '{{PLURAL:$1|$1|$1}} دچی‌ین پاک بیّه',
'feedlinks' => 'خَوِرخون:',
'feed-invalid' => 'خراب بیّن آبونمان ِخَوِرخون',
'feed-unavailable' => 'خَوِرخونا قابل ایستیفاده نینه',
'site-rss-feed' => '$1 ره  آراس‌اس خه‌راک',
'site-atom-feed' => '$1 ره اتم خه‌راک',
'page-rss-feed' => '"$1" RSS خه‌راک',
'page-atom-feed' => '"$1" Atom خه‌راک',
'red-link-title' => '$1 (این صفحه دَنی‌یه)',
'sort-descending' => 'مرتب‌ساجی نزولی',
'sort-ascending' => 'مرتب‌ساجی صعودی',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'صفحه',
'nstab-user' => 'کارور صفحه',
'nstab-media' => 'رسانه',
'nstab-special' => 'شاء صفحه',
'nstab-project' => 'پروژه',
'nstab-image' => 'فایل',
'nstab-mediawiki' => 'پیغوم',
'nstab-template' => 'شابلون',
'nstab-help' => 'راهنما',
'nstab-category' => 'رج',

# Main script and global functions
'nosuchaction' => 'نونه اینتی هاکردن',
'nosuchactiontext' => 'اینتا کار اینترنتی ِنشونی دله غیرموجازه
شما احتمالا اینترنتی آدرس ره ایشتباه بنویشنی یا لینک ایشتبا ره کلیک هاکردنی
همینتی ممکن هسته ایرادی {{SITENAME}} دله داره.',
'nosuchspecialpage' => 'اینتا شاء صفحه وجود ندانّه',
'nospecialpagetext' => '<strong>شما اتا غیرموجاز صفحه ره بخاسنی.</strong>

اینان شاء صفحه‌ئون هستنه: [[Special:SpecialPages|{{int:specialpages}}]]',

# General errors
'error' => 'خِطا',
'databaseerror' => 'خطای داده‌ئون پایگا',
'laggedslavemode' => "'''هشدار:''' صفحه ممکن هسته که جدید ِبه‌روزرسانی‌ئون ره شامل نواشه.",
'readonly' => 'پایگاه داده زلفن بزه‎بیّه',
'enterlockreason' => 'دلیلی زلفین بزوئن وسّه بارین، که حاوی تقریبی از زمونی بائه که زلفین بَیته وانه',
'readonlytext' => 'داده پایگاه هنتا دچی‌ین و صفحه‌ئون بساتن وسّه زلفن بَیی‌ئه؛ این وضعیت احتمالاً معمولی رسیدگیون وسّه هسته که این کارون په، عادی وانه.

مدیری که اینان ره زلفن هاکرده این توضیح ره شمه وسّه بنویشته: $1',
'missing-article' => 'داده پایگاه صفحه «$1» $2 بنویشته ره که وسّه پیدا هاکرد بوو، پیدا نکارده.

این مشکل معمولاً اون گادِر پیش انه که شمه بخاستی چیون اتا قدیمی یا حذف بَیی تاریخچه‌ی تفاوت بوئن.

اگه غیر این هسته، احتمالاً نرم‌افزار دله موشکل پیدا بیّه.
این مشکل ره اینترنتی نشونی همراه اتا [[Special:ListUsers/sysop|مدیر]] وسّه برسِنین.',
'missingarticle-rev' => '(نسخه‌ی شماره: $1)',
'missingarticle-diff' => '(فرق: $1، $2)',
'readonly_lag' => 'پایگاه داده به طور خودکار زلفین بزه‌بیّه تا پشتیبون ِنسخه‌ئون با اصلی نسخه هماهنگ بواشِن',
'internalerror' => 'خطای دله‌یی',
'internalerror_info' => 'خطای دله‌یی: $1',
'fileappenderrorread' => 'طی پست امکان بخوندستن «$1» وجود نداشته.',
'fileappenderror' => 'نیّه «$1» ره به «$2» پُست هاکرده.',
'filecopyerror' => 'نیّه پروندهٔ «$1» جه روی «$2» نسخه‌برداری بواشه.',
'filerenameerror' => 'نیّه پروندهٔ «$1» به «$2» تغییرنوم پیداهاکنه.',
'filedeleteerror' => 'نیّه پروندهٔ «$1» پاک بواشه.',
'directorycreateerror' => 'امکان بساتن پوشه $1 وجود نداشته.',
'filenotfound' => 'پروندهٔ «$1» پیدانیّه.',
'fileexistserror' => 'امکان بنویشتن روی پرونده $1 وجود ندانّه: پرونده از قبل وجود داشته.',
'unexpected' => 'مقدار غیرمنتظره: «$1»=«$2».',
'formerror' => 'خطا: ننشنه فرم ره برسنی‌ین',
'badarticleerror' => 'ننشنه این کار ره این صفحه دله هاکردن.',
'cannotdelete' => 'صفحه یا تصویر «$1» ره ننشنه پاک هاکردن.
ممکنه قبلاً فرد دیگری وه ره پاک هاکردبوئه.',
'cannotdelete-title' => 'نشنه «$1» ره پاک هاکردِن',
'delete-hook-aborted' => 'قلاب نتونده حذف هاکنه.
اینتا وسّه دلیل ننویشتنه.',
'badtitle' => 'نخاشِ عنوان',
'badtitletext' => 'بخاستی عنوان نامعتبر، خالی یا میون‌زوونی عنوان یا میون‌ویکیی غلط لینک جه بی‌یه.
ممکنه ونه دله اتا یا چنتا چی بنویش بو که نَونه عنوان دله بئه.',
'perfcached' => 'این چیون ثبت بَیی حافظه جه انّه و ممکنه آپدیت نَوائن. حداکثر {{PLURAL:$1|اتا نتیجه|$1تا نتیجه}} قدیمی حافظه دله دره.',
'querypage-no-updates' => 'این صفحه فعلاً نَونه آپدیت بَواشه.
همینسه ونه دله بنویشته‌ئون شاید قدیمی بائن.',
'wrong_wfQuery_params' => 'پارامترون wfQuery()‎ غلطه<br />
تابع: $1<br />
پرس‌وجو: $2',
'viewsource' => 'منبع ره بدی‌ین',
'viewsource-title' => '$1 مبدأ ره سِراق هدائِن',
'actionthrottled' => 'شمه پیش ره بیتنه',
'protectedpagetext' => 'این صفحه دچی‌ین وسّه زلفین بزه بیّه.',
'viewsourcetext' => 'بتونّی متن مبدأ این صفحه ره هارشین یا ونجه نسخه بَیرین:',
'viewyourtext' => "بتونّی '''شه بنویشته چیون''' مبدأ ره این صفحه دله هارشین و کپی هاکنین:",
'protectedinterface' => 'این صفحه ارائه‌دهندهٔ متنی واسط کارور این نرم‌افزار هسته و به منظور پیشگیری از خرابکاری زلفین بزه‌بیّه.',

# Login and logout pages
'yourname' => 'شمه کاروری‌نوم:',
'yourpassword' => 'شمه پسورد',
'yourpasswordagain' => 'پسورد ره دِباره بنویس',
'remembermypassword' => 'مه رمز ره (تا حداکثر $1 {{PLURAL:$1|روز|روز}}) این مرورگر سر یاد نکان',
'yourdomainname' => 'شمه کاروری نوم',
'login' => 'دله بوردن',
'nav-login-createaccount' => 'دله بوردن / عضو بیّن',
'loginprompt' => '{{SITENAME}} ره ده‌لـه بیـه‌موئـه‌ن وه‌سه، وه‌نـه cookieئون  کـارسأر بـوئـه‌ن.',
'userlogin' => 'دله بموئن / عضو بیّن',
'userloginnocreate' => 'دله بموئن',
'logout' => 'دربوردن',
'userlogout' => 'دربوردن',
'notloggedin' => 'سیستم ره دله نیه مونی',
'nologin' => 'عضو نی؟ $1.',
'nologinlink' => 'عضو بواشین',
'createaccount' => 'ترنه حساب وا هکاردن',
'gotaccount' => 'عضو هسنی؟ $1.',
'gotaccountlink' => 'بورین دله',
'userlogin-resetlink' => 'دله بموئن ِجزئیات ره یاد هاکردی؟',
'createaccountmail' => 'ایمیل جه',
'createaccountreason' => 'دلیل:',
'badretype' => 'دِتا پسوردی که بنویشتی اتجور نینه',
'userexists' => 'کاروری نومی که بخاستنی وجود داشته.
خواهشأ ات نوم دیگه انتخاب هاکنین.',
'loginerror' => 'دله نشی‌یه',
'nocookiesnew' => 'حساب کاروری بساته بیّه، اما شِما دله نشینی.
{{SITENAME}} ورود کارورون به سامانه از کوکی استفاده کانده.
شِما کوکی‌ئون ره کار جه دمبدایی.
لطفاً کوکی‌ئون ره به کار دمبده، و سپس با اسم کاروری و پسورد جدید برو دله.',
'nocookieslogin' => '‏{{SITENAME}} کوکی‌ئون ره کارورون دله بوردن سر کار زنّه. شِما جا خاهش دارمی که وشون ره کار بی‌یلین و دباره سعی هاکنین.‎‎',
'loginsuccess' => 'شِما إسا با اسم «$1» به {{SITENAME}} دله بمونی.',
'nouserspecified' => 'شِما ونه أتا کارور نوم مشخص هاکنی.',
'mailmypassword' => 'اتا نو پسورد بساز و برسِن',
'accountcreated' => 'کاروری نوم دِرِس بیّه',
'accountcreatedtext' => 'کاروری نوم، $1 بساته بیّه.',

# Change password dialog
'newpassword' => 'نو پسورد:',

# Special:PasswordReset
'passwordreset-username' => 'کاروری نوم:',
'passwordreset-domain' => 'دامنه:',
'passwordreset-capture' => 'گت ایمیل سِراق هدائه بَواشه؟',

# Special:ChangeEmail
'changeemail-oldemail' => 'اساء ایمیل:',
'changeemail-newemail' => 'ترنه ایمیل آدرس:',
'changeemail-none' => '(هچّی)',
'changeemail-submit' => 'ایمیل ره عوض هاکردن',
'changeemail-cancel' => 'ول هاکردن',

# Edit page toolbar
'bold_sample' => 'ضخیم',
'bold_tip' => 'ضخیم',
'italic_sample' => 'کژ',
'italic_tip' => 'کژ',
'link_sample' => 'لینک ِسرنوم',
'link_tip' => 'درونی لینک',
'extlink_sample' => 'http://www.example.com مثال',
'extlink_tip' => 'بیرون بگردستن (پیشوند http://‎ ره یادنکانین)',
'headline_sample' => 'متن عنوان',
'headline_tip' => 'عنوان بند ۲',
'nowiki_sample' => 'شه بی فورمت بنویشته ره اینجه دکانین',
'nowiki_tip' => 'فورمت سر چش ره کوریک بَیره',
'image_tip' => 'بنویشته‌ی دله‌ی عکس',
'media_tip' => 'فایل ِلینک',
'sig_tip' => 'شمه امضا و ونه په‌ی ِتاریخ',
'hr_tip' => 'افقی خط (ونه کمته کار بکشین)',

# Edit pages
'summary' => 'کار ِگزارش:',
'subject' => 'موضوع یا عنوان:',
'minoredit' => 'اینتا دچی‌یه خله جزئی بی‌یه',
'watchthis' => 'این صفحه ره دمبال هاکردن',
'savearticle' => 'جادکتن ِصفحه',
'preview' => 'پیش‌پیش سِراق هدائن',
'showpreview' => 'پیش‌پیش سِراق هدائن',
'showlivepreview' => 'آنلاین پیش‌پیش سِراق هدائن',
'showdiff' => 'تغییرات ِسراق هدائن',
'anoneditwarning' => "'''هشدار:''' شِما هنتا عضو نَیینی.
شمه آی‌پی آدرِس تاریخچه دله موندنه.",
'anonpreviewwarning' => "''شما هنتا عضو نَیینی، اگه اتچی بنویسین، شمه آی‌پی ِلینگِ‌رج اینجه موندنه.''",
'missingcommenttext' => 'ات‌چی اینجه بنویسین که شمه توضیح بوو',
'summary-preview' => 'خلاصه‌ی پیش‌پیش سِراق هدائن:',
'subject-preview' => 'موضوع/عنوان ِپیش‌پیش سِراق هدائن:',
'blockedtitle' => 'کارور دَوسته بیّه',
'blockedtext' => "'''شمه آی پی دوسته بیّه.'''

این کار ره $1 انجام هدائه.
اینت وسه که ته این کار ره هکردی: $2''

* شروع دوسته بین: $8
* زمون پایان این دوسته گی: $6
* کاوری که خاستمی ونه آی پی ره دوندیم: $7

شما بتونی با $1 با اتا از [[{{MediaWiki:Grouppage-sysop}}|مدیر|مدیرا]] تماس بیرین و ونجه گپ بزنین.

 شمه یاد دواشه که اگه شه ایمیل ره ننوشت بائین نتونی مدیرا وسه ایمیل بزنین اگه ایمیل ره ننوشنی ترجیحات دله بنویسین[[Special:Preferences|اینجه ایمیل ره بنویس]]
نشونی آی‌پی شما $3 و شماره قطع دسترسی شما $5 هسته. حتما این دِتا شوماره ره گپ بزوئن دله به کار بورین.",
'blockednoreason' => 'معلوم نی‌یه چچی وسه اینتی بیّه!',
'loginreqtitle' => 'ونه سامانه دله بئین',
'loginreqlink' => 'دله بموئن',
'loginreqpagetext' => 'بقیه‌ی صفحه‌ئون ِبدی‌ین وسّه، ونه $1.',
'accmailtitle' => 'پسورد ره برسِنیمی.',
'accmailtext' => "اتا تصادفی پسور بساته بیّه [[User talk:$1|$1]] وسّه $2 سَر برسِنی‌یه بیّه.

این ترنه کاروری حساب ِپسور، سامانه دله بموئن په، ''[[Special:ChangePassword|ات‌تی]]'' بتونده عوض بوو.",
'newarticle' => '(ترنه)',
'blocked-notice-logextract' => 'دسترسی اینتا کارور الآن دوستوئه.
آخرین مورد سیاهه قطع دسترسی زیر بموئه:',
'previewnote' => 'شِمه یاد بوئه که اینتا اتا پیش‌نمایِش هسه. 
 شِمه دگاردسته‌ئون جانـَکِته که و‌نه، ونه اِسا ذخیره‌بیّـِن دوکمه ره بَزنین!',
'editing' => 'درحال  $1 ره دچی‌ین',
'editingsection' => 'دچی‌ین $1 (تیکه)',
'editingcomment' => '$1 دچی‌ین(نو تیکه)',
'yourtext' => 'شمه بنویشته',
'copyrightwarning' => 'خـاهش بونه شمه یاد دواشه که همه چیزایی که {{SITENAME}} دله وانه، تحت $2 حیساب وونه. (ویشتر بخوندستن وسه $1 ره هارشین)<br />
اگه نخانّی شمه بنویشته‌ئون اینجه دس بزه یا ات نفر دیگه شمه بنویشته ره کوپی نکانه، اصلأ شه بنویشته ره اینجه نی‌یلین.',
'templatesused' => '{{PLURAL:$1|شابلون|شابلونای}} استفاده بَیی این صفحه دله:',
'templatesusedpreview' => '{{PLURAL:$1|شابلون|شابلونای}} استفاده بَیی این پیش‌نمایش دله:',
'permissionserrorstext-withaction' => 'ته اجازهٔ $2 ره به {{PLURAL:$1|دلیل|دلایل}} رو به رو ندانی:',
'recreate-moveddeleted-warn' => "'''هشدار: ته دری اتا صفحه ره نویسنی که قبلا پاک بیّه.'''

شه فکر هاکن که اینتا کار که دری کانده درسته یا نا؟
اینجه توندی پاک بیی صفحه ره هارشی:",
'moveddeleted-notice' => 'اینتا صفحه پاک بی بی‌یه
اینجه بتوندی قبلی صفحه که پاک بیّه ره هارشی',
'log-fulllog' => 'بدی‌ین سیاهه کامل',
'edit-gone-missing' => '.شما نتوندی صفحه ره دباره هارشی
احتمالا صفحه پاک بیه.',
'edit-conflict' => 'دِ نفر با هم درنه نویسنه.
اتا ته هستی.',

# History pages
'revisionasof' => 'دچی‌یه‌ئونی که $1 جا دکتنه',
'previousrevision' => '→ پیشی دگاردسته‌ئون',
'cur' => 'إسا',
'last' => 'تاریخچه',
'histfirst' => 'کـوهـنـه تـریـن',
'histlast' => 'نـو تـریـن',
'historysize' => '({{PLURAL:$1|۱ بایت|$1 بایت}})',
'historyempty' => '(خالی)',

# Revision deletion
'rev-delundel' => 'دیار بیّن/فرو بوردن',
'revdelete-text' => "'''نسخه‌ئون و حذف بئی موارد ره بشنه سیاهه جا و صفحه تاریخچه دله بدین، ولی اتی قسمت از وشونه محتواره بقیه کارورون نتوننه بوینن.'''
{{SITENAME}} بقیه مدیرون بتوننه اینتا پنهون بئیه محتوا ره هارشن و وشونه حذف بئیون ره احیاء هاکنن، مگر اینکه اتی سری محدودیت ونه رو اعمال بئی باشه.",
'revdel-restore' => 'دیاری تغییر هدائن',

# History merging
'mergehistory' => 'صفحه‌ئون تاریخچه ره اتا هاکردن',

# Merge log
'revertmerge' => 'سِوا هاکردن',

# Diffs
'lineno' => 'بند  $1:',
'editundo' => 'واچی‌ین',

# Search results
'searchresults' => 'بچرخستن ِجوابون',
'searchsubtitle' => "شما '''[[:$1]]''' دمبال بگردستنی ([[Special:Prefixindex/$1|صفحه‌ئونی که با «$1» شروع وانّه]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|صفحه‌ئونی که به «$1» لینک هدانه]])",
'notitlematches' => 'هیچ صفحه‌یی شمه گپ واری نیّه',
'prevn' => 'پـیـشـیـن {{PLURAL:$1|$1}}',
'nextn' => 'تا پَس‌تر {{PLURAL:$1|$1}}',
'viewprevnext' => 'هارشائن ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new' => "'''« [[:$1]] » ِ صفحه ره این ویکی دله بساجین!'''",
'search-result-size' => '$1 ({{PLURAL:$2|1 واجه|$2 واجه}})',
'search-redirect' => '(بَرسی‌یه $1 جه)',
'search-section' => '(تیکه $1)',
'search-suggest' => 'شما اینتا ره نخاسنی: $1',
'search-interwiki-caption' => 'خاخر پروژه‌ئون',
'search-interwiki-more' => '(ویشتر)',
'powersearch' => 'ململ بَیی دور هایتن',
'powersearch-legend' => 'ململ بَیی دور هایتن',
'powersearch-ns' => 'بچرخستن اینان دله:',
'powersearch-redir' => '',
'powersearch-field' => 'دور هایتن اینتا وسه:',

# Preferences page
'mypreferences' => 'مه خاستنی‌ئون',
'prefs-edits' => 'تعداد دچی‌یه‌ئون:',
'prefsnologin' => 'سیستم دله نمویی',
'prefs-rc' => 'تازه دگاردسته‌ئون',
'youremail' => 'شه مه Email:',
'username' => 'کاروری نوم:',
'uid' => 'کاروری إشماره:',
'yourrealname' => 'شیمه راستین ره نوم :',
'yourlanguage' => 'زوون:',
'badsig' => 'ایمضا بی اعتبار هسه. html کودون ره أی هارشین.',
'email' => 'رایانومه',
'prefs-help-realname' => 'اصلی نوم اختیاری هسه. اگه شه‌ما بنویسین شمه کارون ونه جا ثبت بونه.',
'prefs-help-email' => 'ایمیل اختیاری هسته. ولی اگه شِما شه پاسورد ره یاد بکارد‌نی نو پاسورد ره شِمسه ایمیل کامبی. شِما همینتی توندی بی‌یلین که دیگه کارورون شمه وسّه کاروری صفحه و کاروری گپ جه ایمیل بَزنن بی اونکه شِمه ایمیل معلوم بَواشه.',
'prefs-help-email-required' => 'ایمیل نشونی لازم هسه.',

# User rights
'userrights-user-editname' => 'اتا کاروری نوم وارد هاکنین:',

# Groups
'group-sysop' => 'مدیرون',
'group-all' => '(همه)',

'grouppage-sysop' => '{{ns:project}}:مدیرون',

# User rights log
'rightslog' => 'سیاهه اختیارای کاروری',
'rightslogtext' => 'اینتا سیاهه تغییرای اختیارای کاروری هسته.',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'این صفحه ره دچی‌ین',
'action-createtalk' => 'دِرِس هاکردن اتا صفحه که ونه دله بنشنه گپ بزوئن',
'action-createaccount' => 'درِس هکردن این حساب کاروری',
'action-minoredit' => 'علامت بزوئن اینتا دچی‌ین به عونوان جوزئی',
'action-move' => 'دکشی‌ین اینتا صفحه',
'action-move-subpages' => 'دکشی‌ین اینتا صفحه و ونه زیر رج‌ئون',
'action-move-rootuserpages' => 'دکشی‌ین صفحه‌ئون کاروری سرچله',

# Recent changes
'recentchanges' => 'تازه دگاردسته‌ئون',
'recentchanges-legend' => 'تازه دگاردسته‌ئون گوزینه‌ها',
'recentchanges-summary' => 'ویکی تازه دگاردسته‌ئون ره اینتا صفحه دله دمبال هاکنین.',
'recentchanges-label-newpage' => 'اینتا ویرایش اته نو صفحه ایجاد هاکرده',
'recentchanges-label-minor' => 'اینتا ویرایش خله جزئی بیه',
'recentchanges-label-bot' => 'اینتا ویرایش‌ره اته ربات انجام هدائه',
'rcnote' => "اینجه {{PLURAL:$1|دگاردسته‌یی|'''$1''' دگاردسته‌ئونی}} که $4، $5 جه، '''$2''' روز پیش‌تر دچی‎یه بینه ره اشنّی",
'rclistfrom' => 'نِمایش تازه‌دگاردسته‌ئون با شروع از $1',
'rcshowhideminor' => 'پچیک دچی‌یه‌ئون $1',
'rcshowhidebots' => 'ربوت‌ئون $1',
'rcshowhideliu' => 'ثبت‌نوم هاکرده کارورون $1',
'rcshowhideanons' => 'ناشناس ِکارورون $1',
'rcshowhidepatr' => 'گشت‌بخارد ِدچی‌یه‌ئون $1',
'rcshowhidemine' => 'مه دچی‌یه‌ئون $1',
'rclinks' => 'نـِشون هـِدائن  $1 پایانی دَچی‌‌یه‌ئون، $2 اِسـا روز ره دلـه؛ $3',
'diff' => 'فرق',
'hist' => 'تاریخچه',
'hide' => 'پنهون هاکن',
'show' => 'نـِشـون هـاده',
'minoreditletter' => 'جز',
'newpageletter' => 'نو',
'boteditletter' => 'ربات',
'newsectionsummary' => '/* $1 */ نو تیکه',

# Recent changes linked
'recentchangeslinked' => 'واری دأچیـه‌ن‌ئون',
'recentchangeslinked-feed' => 'واری دچی‌یه‌ئون',
'recentchangeslinked-toolbox' => 'واری دچی‌یه‌ئون',
'recentchangeslinked-page' => 'صفحه ایسم:',

# Upload
'upload' => 'باربی‌یشتن فـایـل',
'uploadbtn' => 'باربی‌یشتن فایل',
'uploadtext' => "فرم زیر جه باربی‌یشتن نو پرونده‌ئون وسّه استفاده هاکنین.
بدی‌ین پرونده‌ئونی که قبلاً باربی‌یشته بَینه به [[Special:FileList|فهرست پرونده‌ها]] بورین. باربی‌یشتن مجدد [[Special:Log/upload|سیاههٔ بارگذاری‌ها]] و حذف پرونده‌ئون [[Special:Log/delete|deletion log]] دله ثبت وانه.

بعد از این که پرونده‌یی ره باربی‌یشتنی، به این سه شکل بنشنه وه ره صفحه‌ئون دله بی‌یشتن:

*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' استفاده از نسخه کامل پرونده وسّه
*'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' استفاده از اتا نسخه ۲۰۰ پیکسلی از پرونده که اتا جعبه سمت چپ متن دله دره و عبارت alt text ونه دله به عنوان توضیح استفاده بیّه وسّه
*'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' بساتن اتا پیوند مستقیم به پرونده بدون نمایش پرونده",
'uploadlogpage' => 'باربی‌یشتن گزارش',
'uploadedimage' => '"[[$1]]" ره باربی‌یشته',

# Special:ListFiles
'imgfile' => 'فایل',
'listfiles' => 'هارشی ئون ره لیست',
'listfiles_name' => 'نـوم',
'listfiles_user' => 'کارور',
'listfiles_size' => 'قایده',

# File description page
'file-anchor-link' => 'فایل',
'filehist' => 'فایل تاریخچه',
'filehist-current' => 'إسا',
'filehist-datetime' => 'تاریخ/زمون',
'filehist-thumb' => 'انگوس گتی',
'filehist-user' => 'کارور',
'filehist-comment' => 'هارشا',
'imagelinks' => 'لینک‌ئون',
'linkstoimage' => 'این {{PLURAL:$1|صفحه|$1 صفحه‌ئون}} لینک هِدانه این فایل ره:',

# Random page
'randompage' => 'شانسی صفحه',

# Statistics
'statistics' => 'آمار',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|بایت|بایت}}',
'specialpage-empty' => 'این صفحه دله هچّی دَنی‌یه',
'unusedcategories' => 'کـار نـأزو رج‌ئون',
'unusedimages' => 'کـار نأزو فایل‌ئون',
'popularpages' => 'خاسگار هدار صفحه‌ئون',
'wantedpages' => 'صفحه‌ئونی که خامبی',
'prefixindex' => 'تموم صفحه‌ئون پیشوند دار',
'shortpages' => 'پچیک صفحه‌ئون',
'longpages' => 'بِلند صفحه‌ئون',
'listusers' => 'کارورون ِلیست',
'newpages' => 'نو بساته صفحه‌ئون',
'newpages-username' => 'کارور نوم:',
'ancientpages' => 'كوهنه صفحه‌ئون',
'move' => 'دکش هاکردن',
'pager-newer-n' => '{{PLURAL:$1|أتـا نـه‌ته‌ر|$1 تـا نـه‌ته‌ر}}',
'pager-older-n' => '{{PLURAL:$1|أتـا کـوهـنـه‌ته‌ر|$1 تـا کوهـنـه‌ته‌ر}}',

# Book sources
'booksources-search-legend' => 'بگردستن منابع کتاب',
'booksources-go' => 'بـور',
'booksources-text' => 'زیر فهرستی از لینکا به وبگاه‌ئون دیگه دره که کتاب‌ئون نو و دست دوم روشنّه و ممکنه اطلاعات ویشتری راجع به کتاب مورد نظر دارِن:',

# Special:Log
'specialloguserlabel' => 'کارور:',

# Special:AllPages
'allpages' => 'همه صفحه‌ئون',
'alphaindexline' => '$1 تا  $2',
'prevpage' => 'پیشین صفحه ($1)',
'allarticles' => 'همه صفحه‌ئون',
'allpagessubmit' => 'بـور',

# Special:Categories
'categories' => 'رج‌ئون',

# Special:LinkSearch
'linksearch' => 'دأیا لـیـنـک‌ئون',

# Special:ListGroupRights
'listgrouprights-members' => '(کارورون لیست)',

# Email user
'mailnologintext' => 'برای برسنی‌ین پوست الکترونیکی به کارورون دیگه ونه [[Special:UserLogin|بورین سامانه دله]] و نشونی پوست الکترونیکی معتبری تو [[Special:Preferences|ترجیحات]] خادت ره داشته بایی.',
'emailuser' => 'این کارور وسّه ایمیل بَرسِن',
'emailpage' => 'ئـی-مه‌یـل ای کـارور وه‌سه',

# Watchlist
'watchlist' => 'مه دمبال‌هاکرده‌‌ئون ِلیست',
'mywatchlist' => 'مه دمبال‌هاکرده‌‌ئون ِلیست',
'watchnologin' => 'سیستم ره دله نی ئه موئین',
'addedwatchtext' => "«[[:$1]]» شمه [[Special:Watchlist|دمبال هاکردئون لیست]] دله اضافه بیه.
اینتا صفحه دگاردسته‌ئون و ونه گپ آینده دله اینتا لیست دله شمه وسه فهرست بوننه؛ یان شه بماند، اینتا صفحه، [[Special:RecentChanges|تازه دگاردسته‌ئون]] فهرست دله شمه وسه '''پررنگ‌تر''' نمایش هدا بونه تا وره راحت تر بوینین.",
'watch' => 'دمبال هاکردن',
'watchthispage' => 'این صفحه ره دِمبال هاکارد‌ن',
'unwatch' => 'ده‌مـبـال نـه‌کـارده‌ن',
'unwatchthispage' => 'دیگه این صفحه ره دمبال نکاردن',
'watchlist-details' => 'بدون حیساب گپ ولگ‌ئون، {{PLURAL:$1|$1 صفحه|$1 صفحه}} شمه دمبال‌هاکردنی‌ئون میون قرار {{PLURAL:$1|دارنه|دانه}}.',
'wlheader-enotif' => '*تونی ایمیل جه مطلع بواشین.',
'wlheader-showupdated' => "*صفحه‌ئونی که بعد از آخرین سربزوئنتون عوض بینه '''پر رنگ''' نشون هدائه بیّه.",
'wlnote' => "ایجه {{PLURAL:$1|پایانی دأچیه‌ن|پایانی '''$1''' دأچیه‌ن‌ئونی}} هأسه که ای $2 ساعت ده‌له دأکه‌ته.",
'watchlist-options' => 'دمبال هاکردن گوزینه‌ها',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ده‌مـبـال هـه‌کـارده‌ن...',
'unwatching' => 'ده‌مـبـال نـه‌کـارده‌ن...',

'enotif_lastvisited' => 'بدی‌ین همه‌ی تغییرات از آخرین باری که سر بزونی وسّه $1 ره هارشین.',
'enotif_lastdiff' => 'هارشائن این تغییر وسّه $1 ره بزنین.',
'enotif_anon_editor' => 'نشناسی‌یه کارور $1',
'created' => 'بساته بیّه',

# Delete
'deletepage' => 'صفحه پاک هاکردن',
'excontent' => 'صفحه محتوا وِ بیه: «$1»',
'excontentauthor' => 'صفحه محتوا وِ بیه: «$1» (فقط «[[Special:Contributions/$2|$2]]» وِنه کایر بیه)',
'exbeforeblank' => 'قبل اینکه صفحه محتوا خالی بوه ونه محتوا وِ بیه: «$1»',
'dellogpage' => 'وه ره بییته‌ئون گوزارش',

# Rollback
'rollback' => 'دچی‌یه‌ئون ره واچی‌ین',
'rollback_short' => 'واچی‌ین',
'rollbacklink' => 'واچی‌ین',
'revertpage' => '"چـیـزونی که [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) دأچـیـه ده‌گـه‌ره‌س بـأیـه هـأمونـتـایی که [[User:$1|$1]] ای وألگ ده‌لـه، پـایـانی بـار هـه‌کـارده"',
'revertpage-nouser' => '"چیزونی که (ونـه کـاروری نـوم پـاک بَیّه) دچی‌یه دگـاردسته بیّه همونتایی که [[User:$1|$1]] آخرسری دچی‌ین دلـه هاکرده"',
'rollback-success' => 'چیزونی که $1 دچی‌ین دگاردسته بیّه همونتایی که $2 آخرسری دچی‌ین دلـه هاکرده',

# Protect
'protectedarticle' => '«[[$1]]» ره محافظت هاکرده',
'modifiedarticleprotection' => '«[[$1]]» محافظت تنظیمات ره تغییر هدائه',
'movedarticleprotection' => '«[[$2]]» محافظت تنظیمات ره «[[$1]]» رو منتقل هاکرده',
'protect-expiry-indefinite' => 'بی‌پایون',
'protect-cantedit' => 'شما نتوننی اینتا صفحه محافظت وضعیت ره تغییر هادین، شما اجازه این کار ره ندایننی.',

# Restrictions (nouns)
'restriction-edit' => 'دچی‌ین',
'restriction-upload' => 'باربی‌یشتن',

# Undelete
'undeletelink' => 'بـأویـنـه‌ن / ده‌واره جـا بـیـه‌شـتـه‌ن',

# Namespace form on various pages
'namespace' => 'نوم‌جا:',
'invert' => 'برعکس انتخاب هاکن',
'blanknamespace' => '(مـار)',

# Contributions
'contributions' => 'کارور کایری‌ئون',
'contributions-title' => '$1 کایری‌ئون',
'mycontris' => 'مه کایری‌ئون',
'contribsub2' => '$1 ($2) وه‌سه',
'uctop' => '(سر)',

'sp-contributions-newbies' => 'نـه وا بـأیـه ئـه‌کـانـت‌ئون دأچـیـه‌ن‌ئون ره نـه‌شـون هـاده',
'sp-contributions-talk' => 'گپ',
'sp-contributions-username' => 'IP نـه‌شـونـی یا کـاروری‌نوم',
'sp-contributions-submit' => 'چـأرخـه‌تـو',

# What links here
'whatlinkshere' => 'لینک‌ئون ِاینتا صفحه',
'whatlinkshere-title' => 'وألـگ‌ئونی که "$1" ره لـیـنک هه‌دانه',
'whatlinkshere-page' => 'صفحه:',
'linkshere' => "اینان صفحه‌ئون به '''[[:$1]]''' لینک هدانه:",
'whatlinkshere-prev' => '{{PLURAL:$1|پـیـشـیـن|$1 تـای پـیـشـیـن}}',
'whatlinkshere-next' => '{{PLURAL:$1|پَس|$1 تا پَس‌تر}}',
'whatlinkshere-links' => '← لـیـنـک‌ئون',

# Block/unblock
'blockip' => 'کارور ره دَوستن',
'blockip-title' => 'کارور ره دَوستن',
'blockip-legend' => 'کارور ره دَوستن',
'ipbsubmit' => 'ای کارور دأبه‌س بأوه',
'ipboptions' => '۲ ساعت:2 hours,۱ روز:1 day,۳ روز:3 days,۱ هفته:1 week,۲ هفته:2 weeks,۱ ماه:1 month,۳ ماه:3 months,۶ ماه:6 months,۱ سال:1 year,بی‌پایون:infinite',
'ipblocklist' => 'IP نـه‌شـونـی‌ئون ئو کـارورنـوم‌ئونی کـه دأبـه‌سـتـوونـه',
'infiniteblock' => 'بی‌پایون',
'expiringblock' => '$1 دله، ساعت $2 دِرِس وونه',
'blocklink' => 'دَوستن',
'unblocklink' => 'وا هـه‌کـارده‌ن',
'change-blocklink' => 'قطع دسترسی تغییر هدائن',
'contribslink' => 'کایری‌ئون',
'blocklogentry' => '[[$1]] دَوسته بیّه و ونه دَوسته بی‌ین گادِر $2 تا $3 هسته',

# Move page
'newtitle' => 'ترنه نوم:',
'movepage-moved' => "'''ای «$1» ولـگ،  بورده «$2» ره.'''",
'movetalk' => '«گپ» صفحه هم، اگه وانه، بوره.',
'revertmove' => 'واچـیـه‌ن',
'delete_and_move_confirm' => 'أره، پاک هاکه‌ن وه ره',

# Export
'export' => 'دأیابأبه‌رده‌ن ولـگ‌ئون',
'exporttext' => 'شما بتونّی متن و تاریخچهٔ دچی‌یه بیّن اتا صفحهٔ مشخص یا یتـّا مجموعه‌ از صفحه‌ها ره به شکل دپوشنی‌یه اکس‌ام‌ال دله بریم دربَورین.

این اطلاعات ره بنشنه اتا ویکی دیگه دله که نرم‌افزار «مدیاویکی» ره اجرا کانده از طریق [[Special:Import|صفحهٔ دله‌دشنی‌ین]] وارد هاکردن.

بریم‌دربَوردن صفحه‌ها وسّه، وشون عنوان ره این جعبه دله دشنین (هر سطر فقط اتا عنوان) و معلوم هاکنین که تازه دگاردسته‌ئون صفحه ره همراه نسخه‌ئون قدیمی‌تر و تاریخچهٔ صفحه خوندنّی، یا تازه دگاردسته‌ئون صفحه و اطلاعات آخرین دچی‌یه ره، تیناری اشنّی.

دومین حالت سَره، شما بتونّی اتا لینک جه استفاده هاکنین، مثلاً [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] صفحهٔ «[[{{MediaWiki:Mainpage}}]]» وسّه.',

# Namespace 8 related
'allmessages-filter-all' => 'همه',

# Thumbnails
'thumbnail-more' => 'گت بوو',

# Special:Import
'import-interwiki-submit' => 'بیاردن',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'مه کاروری صفحه',
'tooltip-pt-mytalk' => 'مه گپ صفحه',
'tooltip-pt-preferences' => 'مه خواسته‌نی‌ئون',
'tooltip-pt-watchlist' => 'لیست صفحه‌ئونی که شِما وشون ِدچی‌یه بیّن ره اشنی',
'tooltip-pt-mycontris' => 'مه کایری‌ئون لیست',
'tooltip-pt-login' => 'شه‌ما به‌ته‌ر هـأسـه که سـیـسـتـه‌م ده‌لـه بـیـه‌ئی، هـرچـأن زوری نـیـه',
'tooltip-pt-logout' => 'سیستم جه دأیابـوری',
'tooltip-ca-talk' => 'این صفحه خَوری گپ بَزوئن',
'tooltip-ca-edit' => 'شِما بتوندی این صفحه ره دَچینی.',
'tooltip-ca-addsection' => 'أتـا نـه گـب را دأکـه‌تـه‌ن',
'tooltip-ca-viewsource' => 'این صفحه ره نتوندی دَچینی.
شِما بِتوندی ونه منبع ره هارشی.',
'tooltip-ca-history' => 'کهنه دگاردسته‌ئونی که این صفحه دله دکته',
'tooltip-ca-delete' => 'این صفحه ره پاک هاکردن',
'tooltip-ca-watch' => 'این صفحه ره شه دمبال‌هاکردن لیست دله بی‌یشتن',
'tooltip-search' => '{{SITENAME}} ره چـأرخـه‌تـو',
'tooltip-search-go' => 'بـور اتـا ولـگـی کـه وه‌نـه نـوم هـأمـیـنـتـا بـوئـه',
'tooltip-search-fulltext' => 'ولـگ‌ئـون ره ایـنـتـا تـه‌کـسـت وه‌سـه چـأرخ بـأزوئـه‌ن',
'tooltip-p-logo' => 'گَت صفحه ره بَدی‌ین',
'tooltip-n-mainpage' => 'گت صفحه ره بدی‌ین',
'tooltip-n-mainpage-description' => 'گَت ِصفحه ره هارشائن',
'tooltip-n-portal' => 'اینجه بتونّی بقیه جه کومک بَیرین یا سؤال هاکنین',
'tooltip-n-currentevents' => 'تازه چی‌ئون درباره بدونستن',
'tooltip-n-recentchanges' => 'تازه دچی‌یه‌ئون ره لیست',
'tooltip-n-randompage' => 'اتت شانسی صفحه بَدی‌ین',
'tooltip-n-help' => 'أتـا جـا کـه...',
'tooltip-t-whatlinkshere' => 'هأمو ولـگ‌ئونی که ایجه ره لینک هه‌دانه',
'tooltip-t-recentchangeslinked' => 'اسایی دگاردسته‌ئون صفحه‌ئونی دله، که این صفحه جه لینک دارنه',
'tooltip-feed-rss' => 'RSS خوراک این صفحه وسّه',
'tooltip-feed-atom' => 'Atom خوراک این صفحه وسّه',
'tooltip-t-emailuser' => 'ای کـارور ره اتـا ئـه‌لـه‌کـتـه‌رونـیـکـی‌نـومـه راهـی هـه‌کـارده‌ن',
'tooltip-t-upload' => 'بـاربـیـه‌شـتـه‌ن فـایـل‌ئون',
'tooltip-t-specialpages' => 'همه شا صفحه‌ئون ِلیسـت',
'tooltip-t-print' => 'پِرینت هـاکاردن صفحه دگاردسته',
'tooltip-t-permalink' => 'موندستنی لینک این صفحه ره اینتا محتوا وسّه',
'tooltip-ca-nstab-main' => 'بدی‌ین ِصفحه',
'tooltip-ca-nstab-user' => 'کاروری صفحه ره بَدی‌ین',
'tooltip-ca-nstab-media' => 'مدیا صفحه هارشی‌ین',
'tooltip-ca-nstab-special' => 'اینتا اتا شا صفحه هسته که شِما نتوندی وه ره دچینی',
'tooltip-ca-nstab-image' => 'عکس ِصفحه ره بدی‌ین',
'tooltip-ca-nstab-template' => 'شـابـلـون بـأویـنـه‌ن',
'tooltip-preview' => 'شـه ده‌گـه‌ره‌سـه‌ئون ره پـیـشـاپـیـش بـأویـنـه‌ن،
 خـا‌هـه‌ش بـونـه، شـه کـارئون ره جـا دأکـه‌تـه‌ن پـیـش، ای ره کـار بـأزه‌نـی.',

# Attribution
'siteusers' => '$1، {{PLURAL:$2|کارور|کارورون}} {{SITENAME}}',

# Browsing diffs
'previousdiff' => 'کوهنه‌تر دچی‌ین ←',
'nextdiff' => 'ته‌رنه دأچیه‌ن ←',

# Media information
'thumbsize' => 'أنـگـوسـی گأتی:',
'file-info-size' => '$1 × $2 پـیـکـسه‌ل, فـایـل گـأتـی: $3, MIME مـونـد: $4',
'file-info-png-frames' => '$1 {{PLURAL:$1|قاب|قاب}}',

# Special:NewFiles
'newimages' => 'گالری نو عکس‌ئون',
'imagelisttext' => 'فهرست بن $1 {{PLURAL:$1|عکسی|عکسی}} که $2 مرتب بیی‌یه بموئه.',
'newimages-summary' => 'این صفحه شا آخرین عکس‌ئون بار بی‌یشته ره نیمایش دنه',
'newimages-label' => 'ایسم عکس (یا ات تیکه که ونه شه):',
'showhidebots' => '(دچی‌یه‌ن روباتا $1)',
'noimages' => 'هچی دنی‌یه که هارشی.',
'ilsubmit' => 'بگردستن',
'bydate' => 'تاریخ رو جه',
'sp-newimages-showfrom' => 'نشون‌هدائن عکسای نو $2، $1 جه به بعد',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2×$3',

# Exif tags
'exif-gpsareainformation' => 'جی پی اس ناحیه نوم',
'exif-gpsdatestamp' => 'جی پی اس روز',
'exif-gpsdifferential' => 'جی پی اس په‌چه‌ک درس هأکه‌ردن',

# Exif attributes
'exif-compression-1' => 'فه‌شورده نئی',

'exif-unknowndate' => 'نه‌شناسی روز',

'exif-orientation-1' => 'معمولی',
'exif-orientation-3' => '180 درجه چرخ بزوئن',
'exif-orientation-4' => 'عمودی په‌شت ئو روبئی',

# External editor support
'edit-externally' => 'ای فـایـل ره، أتـا دأیـا بـه‌رنـومـه هـه‌مـرا، دأچـیـه‌نـیـن',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'همه',
'namespacesall' => 'همه',
'monthsall' => 'همه ماه‌ئون',

# Email address confirmation
'confirmemail_body_changed' => 'ات نفر، احتمالاً خاد شمِا، از نشونی آی‌پی $1 نشونی پوست ایلکتورونیک حیساب «$2» {{SITENAME}} ره تغییر هدائه.

برای تایید این که این حیساب واقعاً شمه شه و فعال هکردن دبارهٔ ویژگی پوست ایلکتورونیک {{SITENAME}}، پیوند زیر دله ره شه مرورگر دله وا هکنین:

$3

اگه این حساب شه مه نی‌یه، پیوند زیر ره دنبال هکنین تا تغییر پوست ایلیکتورونیک ره لغو هکنین:

$5

این تایید یه در $4 منقضی وانه.',

# Multipage image navigation
'imgmultigo' => 'بور!',

# Auto-summaries
'autosumm-blank' => 'صفحه ره اسپه هاکرده',
'autosumm-replace' => "صفحه ره اینتا جه عوض هاکرد: '$1'",
'autoredircomment' => 'به [[$1]] مسیر ره تغییر هدائه',

# Special:Version
'version-specialpages' => 'شا صفحه‌ئون',

# Special:SpecialPages
'specialpages' => 'شا صفحه‌ئون',
'specialpages-group-maintenance' => 'چله‌بندی صفحه‌ئون',

# New logging system
'logentry-move-move_redir-noredirect' => '$1 ، $3 ره بدون اینکه مسیر تغییری درس بوه به $4 که مسیر تغییر بیه منتقل هاکرده',
'logentry-newusers-newusers' => '$1  بساتن اتا حساب کاروری',
'logentry-newusers-create' => '$1  بساتن اتا حساب کاروری',
'rightsnone' => '(هچّی)',

# Feedback
'feedback-subject' => 'موضوع:',
'feedback-message' => 'پیغوم:',
'feedback-cancel' => 'ول هاکردن',
'feedback-submit' => 'ارسال پیشنهادات و انتقادات',
'feedback-adding' => 'بی‌یشتن پیشنهادات و انتقادات...',
'feedback-error1' => 'خطا: جواب‌ئون نشناسی‌یه API جه',
'feedback-error2' => 'خطا: شکست دچی‌ین سر',
'feedback-error3' => 'خطا: جواب ندائن API',

# API errors
'api-error-badaccess-groups' => 'شما اجازهٔ باربی‌یشتن پرونده‌ها ره این ویکی دله ندارنی.',
'api-error-badtoken' => 'خطای داخلی: کد امنیتی اشتبائه (Bad token).',
'api-error-copyuploaddisabled' => 'باربی‌یشتن با استفاده از نشونی اینترنتی این کارساز دله غیرفعاله.',
'api-error-duplicate' => '{{PLURAL:$1|[$2 پروندهٔ دیگه‌یی]|[$2 چن پروندهٔ دیگه]}} وب‌گاه دله با محتوای ات‌تی دیی‌یه.',
'api-error-duplicate-archive' => '{{PLURAL:$1|[$2 پروندهٔ دیگه‌یی]|[$2 چن پروندهٔ دیگه]}} وب‌گاه دله با محتوای اتجور وجود داشته، ولی حذف {{PLURAL:$1|بیی‌یه|بیی‌نه}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|پروندهٔ|پرونده‌ئون}} تکراری که اسا حذف بیی‌نه',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|پرونده|پرونده‌ئون}} تکراری',
'api-error-empty-file' => 'پرونده‌ای که شما برسنینی خالی بی‌یه.',
'api-error-fetchfileerror' => 'خطای داخلی: زمون بییتن پرونده، اتا چی درست پیش نشی‌یه.',
'api-error-file-too-large' => 'پرونده‌ای که شما برسنینی خله خله گت بی‌یه.',
'api-error-filename-tooshort' => 'پرونده ایسم خله خله پچیکه.',
'api-error-filetype-banned' => 'این نوع پرونده ممنوعه.',
'api-error-filetype-missing' => 'پرونده فرمت ندانّه.',
'api-error-hookaborted' => 'اصلاحیه‌ای که شما خاسنی وه ره بساجین، افزونه وه ره تله دمبدائه.',
'api-error-http' => 'خطای داخلی: نتومبی به به سرور اتصال هادیم',
'api-error-illegal-filename' => 'پرونده ایسم مجاز نی‌یه.',
'api-error-internal-error' => 'خطای داخلی: با پردازش باربی‌یشتن شما ویکی سر، ات چی اشتباه پیش بورده.',
'api-error-invalid-file-key' => 'خطای داخلی: پرونده حافظهٔ موقت سر دنی‌ئه.',
'api-error-missingparam' => 'خطای داخلی: پارامترون درخاست ره ندارمی.',
'api-error-missingresult' => 'خطای داخلی: نتونّی بفهمین کپی‌رایت موفق بی‌یه یا نا.',
'api-error-mustbeloggedin' => 'باربی‌یشتن پرونده‌ها وسّه شما ونه کاروری ایسم جا دَواشین.',
'api-error-mustbeposted' => 'خطای داخلی: درخواست ونه روش POST HTTP جا ارسال بوو.',
'api-error-noimageinfo' => 'باربی‌یشتن موفق بی‌یه، ولی کارساز هیچ اطلاعاتی دربارهٔ پرونده اما ره ندائه.',
'api-error-nomodule' => 'خطای داخلی: ماژول باربی‌یشتن تنظیم نیی‌ئه.',
'api-error-ok-but-empty' => 'خطای داخلی : سرور جه جواب نموئه.',
'api-error-overwrite' => 'جای بنویشتن اتا پرونده موجود مجاز نی‌یه.',
'api-error-stashfailed' => 'خطای داخلی: کارساز نتونده پرونده موقت ره ذخیره هاکنه.',
'api-error-timeout' => 'کارساز زمون انتظار جواب ندائه.',
'api-error-unclassified' => 'اتا خطای نشناسته دکته.',
'api-error-unknown-code' => 'خطای نشناسی‌یه: " $1 "',
'api-error-unknown-error' => 'خطای داخلی: زمونی که شما تلاش کاردنی باربی‌یشتن پرونده ره انجوم هادین، اتا چی اشتباه پیش بورده.',
'api-error-unknown-warning' => 'اخطار نشناسی‌یه: $1',
'api-error-uploaddisabled' => 'باربی‌یشتن این ویکی دله غیرفعاله.',
'api-error-verification-error' => 'ممکن هسته که پرونده رِقِد بورد بائه یا پسوند غلط داره.',

);
