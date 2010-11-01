<?php
/** Egyptian Spoken Arabic (مصرى)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alnokta
 * @author Dudi
 * @author Ghaly
 * @author Meno25
 * @author Ouda
 * @author Ramsis II
 */

$fallback = 'ar';

// (bug 16469) Override Eastern Arabic numberals, use Western
$digitTransformTable = array(
	'0' => '0',
	'1' => '1',
	'2' => '2',
	'3' => '3',
	'4' => '4',
	'5' => '5',
	'6' => '6',
	'7' => '7',
	'8' => '8',
	'9' => '9',
	'.' => '.',
	',' => ',',
);

$namespaceNames = array(
	NS_MEDIA            => 'ميديا',
	NS_SPECIAL          => 'خاص',
	NS_TALK             => 'نقاش',
	NS_USER             => 'مستخدم',
	NS_USER_TALK        => 'نقاش_المستخدم',
	NS_PROJECT_TALK     => 'نقاش_$1',
	NS_FILE             => 'ملف',
	NS_FILE_TALK        => 'نقاش_الملف',
	NS_MEDIAWIKI        => 'ميدياويكى',
	NS_MEDIAWIKI_TALK   => 'نقاش_ميدياويكى',
	NS_TEMPLATE         => 'قالب',
	NS_TEMPLATE_TALK    => 'نقاش_القالب',
	NS_HELP             => 'مساعدة',
	NS_HELP_TALK        => 'نقاش_المساعدة',
	NS_CATEGORY         => 'تصنيف',
	NS_CATEGORY_TALK    => 'نقاش_التصنيف',
);

$namespaceAliases = array(
	'وسائط' => NS_MEDIA,
	'صورة' => NS_FILE,
	'نقاش_الصورة' => NS_FILE_TALK,
);

$magicWords = array(
	'redirect'              => array( '0', '#تحويل', '#REDIRECT' ),
	'notoc'                 => array( '0', '__لافهرس__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__لامعرض__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__لصق_فهرس__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__فهرس__', '__TOC__' ),
	'noeditsection'         => array( '0', '__لاتحريرقسم__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__لاعنوان__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'شهر_حالى', 'شهر_حالي2', 'شهر_حالي', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'شهر_حالي1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'اسم_الشهر_الحالى', 'اسم_الشهر_الحالي', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'اسم_الشهر_الحالى_المولد', 'اسم_الشهر_الحالي_المولد', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'اختصار_الشهر_الحالى', 'اختصار_الشهر_الحالي', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'يوم_حالى', 'يوم_حالي', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'يوم_حالى2', 'يوم_حالي2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'اسم_اليوم_الحالى', 'اسم_اليوم_الحالي', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'عام_حالى', 'عام_حالي', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'وقت_حالى', 'وقت_حالي', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ساعة_حالية', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'شهر_محلى', 'شهر_محلي2', 'شهر_محلي', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'شهر_محلى1', 'شهر_محلي1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'اسم_الشهر_المحلى', 'اسم_شهر_محلى', 'اسم_الشهر_المحلي', 'اسم_شهر_محلي', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'اسم_الشهر_المحلى_المولد', 'اسم_شهر_محلى_مولد', 'اسم_الشهر_المحلي_المولد', 'اسم_شهر_محلي_مولد', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'اختصار_الشهر_المحلى', 'اختصار_شهر_محلى', 'اختصار_الشهر_المحلي', 'اختصار_شهر_محلي', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'يوم_محلى', 'يوم_محلي', 'LOCALDAY' ),
	'localday2'             => array( '1', 'يوم_محلى2', 'يوم_محلي2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'اسم_اليوم_المحلى', 'اسم_يوم_محلى', 'اسم_اليوم_المحلي', 'اسم_يوم_محلي', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'عام_محلى', 'عام_محلي', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'وقت_محلى', 'وقت_محلي', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ساعة_محلية', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'عدد_الصفحات', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'عدد_المقالات', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'عدد_الملفات', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'عدد_المستخدمين', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'عدد_المستخدمين_النشطين', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'عدد_التعديلات', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'عدد_المشاهدات', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'اسم_الصفحة', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'عنوان_الصفحة', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'نطاق', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'عنوان_نطاق', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'نطاق_النقاش', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'عنوان_النقاش', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'نطاق_الموضوع', 'نطاق_المقالة', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'عنوان_نطاق_الموضوع', 'عنوان_نطاق_المقالة', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'اسم_الصفحة_الكامل', 'اسم_صفحة_كامل', 'اسم_كامل', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'عنوان_الصفحة_الكامل', 'عنوان_صفحة_كامل', 'عنوان_كامل', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'اسم_الصفحة_الفرعي', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'عنوان_الصفحة_الفرعى', 'عنوان_الصفحة_الفرعي', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'اسم_الصفحة_الأساسى', 'اسم_الصفحة_الأساسي', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'عنوان_الصفحة_الأساسى', 'عنوان_الصفحة_الأساسي', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'اسم_صفحة_النقاش', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'عنوان_صفحة_النقاش', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'اسم_صفحة_الموضوع', 'اسم_صفحة_المقالة', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'عنوان_صفحة_الموضوع', 'عنوان_صفحة_المقالة', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'رسالة:', 'MSG:' ),
	'subst'                 => array( '0', 'نسخ:', 'إحلال:', 'SUBST:' ),
	'safesubst'             => array( '0', 'نسخ_آمن:', 'SAFESUBST:' ),
	'msgnw'                 => array( '0', 'مصدر:', 'مصدر_قالب:', 'رسالة_بدون_تهيئة:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'تصغير', 'مصغر', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'تصغير=$1', 'مصغر=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'يمين', 'right' ),
	'img_left'              => array( '1', 'يسار', 'left' ),
	'img_none'              => array( '1', 'بدون', 'بلا', 'none' ),
	'img_width'             => array( '1', '$1بك', '$1عن', '$1px' ),
	'img_center'            => array( '1', 'مركز', 'center', 'centre' ),
	'img_framed'            => array( '1', 'إطار', 'بإطار', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'لاإطار', 'frameless' ),
	'img_page'              => array( '1', 'صفحة=$1', 'صفحة $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'معدول', 'معدول=$1', 'معدول $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'حد', 'حدود', 'border' ),
	'img_baseline'          => array( '1', 'خط_أساسى', 'خط_أساسي', 'baseline' ),
	'img_sub'               => array( '1', 'فرعى', 'فرعي', 'sub' ),
	'img_super'             => array( '1', 'سوبر', 'سب', 'super', 'sup' ),
	'img_top'               => array( '1', 'أعلى', 'top' ),
	'img_text_top'          => array( '1', 'نص_أعلى', 'text-top' ),
	'img_middle'            => array( '1', 'وسط', 'middle' ),
	'img_bottom'            => array( '1', 'أسفل', 'bottom' ),
	'img_text_bottom'       => array( '1', 'نص_أسفل', 'text-bottom' ),
	'img_link'              => array( '1', 'وصلة=$1', 'رابط=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'بديل=$1', 'alt=$1' ),
	'int'                   => array( '0', 'محتوى:', 'INT:' ),
	'sitename'              => array( '1', 'اسم_الموقع', 'اسم_موقع', 'SITENAME' ),
	'ns'                    => array( '0', 'نط:', 'NS:' ),
	'nse'                   => array( '0', 'نطم:', 'NSE:' ),
	'localurl'              => array( '0', 'مسار_محلى:', 'مسار_محلي:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'عنوان_المسار_المحلى:', 'عنوان_المسار_المحلي:', 'LOCALURLE:' ),
	'server'                => array( '0', 'خادم', 'SERVER' ),
	'servername'            => array( '0', 'اسم_الخادم', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'مسار_السكريبت', 'مسار_سكريبت', 'SCRIPTPATH' ),
	'stylepath'             => array( '0', 'مسار_الهيئة', 'STYLEPATH' ),
	'grammar'               => array( '0', 'قواعد_اللغة:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'نوع:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__لاتحويل_عنوان__', '__لاتع__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__لاتحويل_محتوى__', '__لاتم__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'أسبوع_حالى', 'أسبوع_حالي', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'يوم_حالى_مأ', 'يوم_حالي_مأ', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'أسبوع_محلى', 'أسبوع_محلي', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'يوم_محلى_مأ', 'يوم_محلي_مأ', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'رقم_المراجعة', 'REVISIONID' ),
	'revisionday'           => array( '1', 'يوم_المراجعة', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'يوم_المراجعة2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'شهر_المراجعة', 'REVISIONMONTH' ),
	'revisionmonth1'        => array( '1', 'شهر_المراجعة1', 'REVISIONMONTH1' ),
	'revisionyear'          => array( '1', 'عام_المراجعة', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'طابع_وقت_المراجعة', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'مستخدم_المراجعة', 'REVISIONUSER' ),
	'plural'                => array( '0', 'جمع:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'مسار_كامل:', 'عنوان_كامل:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'عنوان_كامل:', 'مسار_كامل:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'عنوان_كبير:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'عنوان_صغير:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'صغير:', 'LC:' ),
	'uc'                    => array( '0', 'كبير:', 'UC:' ),
	'raw'                   => array( '0', 'خام:', 'RAW:' ),
	'displaytitle'          => array( '1', 'عرض_العنوان', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'أر', 'آر', 'R' ),
	'newsectionlink'        => array( '1', '__وصلة_قسم_جديد__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__لا_وصلة_قسم_جديد__', 'لا_وصلة_قسم_جديد__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'نسخة_حالية', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'كود_المسار:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'كود_الأنكور', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'طابع_الوقت_الحالي', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'طابع_الوقت_المحلى', 'طابع_الوقت_المحلي', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'علامة_الاتجاه', 'علامة_اتجاه', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#لغة:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'لغة_المحتوى', 'لغة_محتوى', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'صفحات_فى_نطاق:', 'صفحات_فى_نط:', 'صفحات_في_نطاق:', 'صفحات_في_نط:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'عدد_الإداريين', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'صيغة_رقم', 'FORMATNUM' ),
	'padleft'               => array( '0', 'باد_يسار', 'PADLEFT' ),
	'padright'              => array( '0', 'باد_يمين', 'PADRIGHT' ),
	'special'               => array( '0', 'خاص', 'special' ),
	'defaultsort'           => array( '1', 'ترتيب_قياسى:', 'ترتيب_افتراضى:', 'مفتاح_ترتيب_قياسى:', 'مفتاح_ترتيب_افتراضى:', 'ترتيب_تصنيف_قياسى:', 'ترتيب_تصنيف_افتراضى:', 'ترتيب_قياسي:', 'ترتيب_افتراضي:', 'مفتاح_ترتيب_قياسي:', 'مفتاح_ترتيب_افتراضي:', 'ترتيب_تصنيف_قياسي:', 'ترتيب_تصنيف_افتراضي:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'مسار_الملف:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'وسم', 'tag' ),
	'hiddencat'             => array( '1', '__تصنيف_مخفي__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'صفحات_في_التصنيف', 'صفحات_في_تصنيف', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'حجم_الصفحة', 'PAGESIZE' ),
	'index'                 => array( '1', '__فهرسة__', '__INDEX__' ),
	'noindex'               => array( '1', '__لافهرسة__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'عدد_فى_المجموعة', 'عدد_فى_مجموعة', 'عدد_في_المجموعة', 'عدد_في_مجموعة', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__تحويلة_إستاتيكية__', '__تحويلة_ساكنة__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'مستوى_الحماية', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'تهيئة_التاريخ', 'تهيئة_تاريخ', 'formatdate', 'dateformat' ),
	'url_path'              => array( '0', 'مسار', 'PATH' ),
	'url_wiki'              => array( '0', 'ويكى', 'ويكي', 'WIKI' ),
	'url_query'             => array( '0', 'استعلام', 'QUERY' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'تحويلات_دوبل' ),
	'BrokenRedirects'           => array( 'تحويلات_مكسوره' ),
	'Disambiguations'           => array( 'توضيحات' ),
	'Userlogin'                 => array( 'دخول_اليوزر' ),
	'Userlogout'                => array( 'خروج_اليوزر' ),
	'CreateAccount'             => array( 'ابتدى_حساب' ),
	'Preferences'               => array( 'تفضيلات' ),
	'Watchlist'                 => array( 'ليستة_المراقبه' ),
	'Recentchanges'             => array( 'اخر_تعديلات' ),
	'Upload'                    => array( 'رفع' ),
	'Listfiles'                 => array( 'عرض_الفايلات', 'ليستة_الفايلات', 'ليستة_الصور' ),
	'Newimages'                 => array( 'فايلات_جديده', 'صور_جديده' ),
	'Listusers'                 => array( 'عرض_اليوزرات', 'ليستة_اليوزرات' ),
	'Listgrouprights'           => array( 'عرض_حقوق_الجروپات' ),
	'Statistics'                => array( 'احصائيات' ),
	'Randompage'                => array( 'عشوائى', 'صفحه_عشوائيه' ),
	'Lonelypages'               => array( 'صفح_وحدانيه', 'صفح_يتيمه' ),
	'Uncategorizedpages'        => array( 'صفح_مش_متصنفه' ),
	'Uncategorizedcategories'   => array( 'تصانيف_مش_متصنفه' ),
	'Uncategorizedimages'       => array( 'فايلات_مش_متصنفه', 'صور_مش_متصنفه' ),
	'Uncategorizedtemplates'    => array( 'قوالب_مش_متصنفه' ),
	'Unusedcategories'          => array( 'تصانيف_مش_مستعمله' ),
	'Unusedimages'              => array( 'فايلات_مش_مستعمله', 'صور_مش_مستعمله' ),
	'Wantedpages'               => array( 'صفح_مطلوبه', 'لينكات_مكسوره' ),
	'Wantedcategories'          => array( 'تصانيف_مطلوبه' ),
	'Wantedfiles'               => array( 'فايلات_مطلوبه' ),
	'Wantedtemplates'           => array( 'قوالب_مطلوبه' ),
	'Mostlinked'                => array( 'اكتر_صفح_معمول_ليها_لينك' ),
	'Mostlinkedcategories'      => array( 'اكتر_تصانيف_معمول_ليها_لينك', 'اكتر_تصانيف_مستعمله' ),
	'Mostlinkedtemplates'       => array( 'اكتر_قوالب_معمول_ليها_لينك', 'اكتر_قوالب_مستعمله' ),
	'Mostimages'                => array( 'اكتر_فايلات_معمول_ليها_لينك', 'اكتر_فايلات', 'اكتر_صور' ),
	'Mostcategories'            => array( 'اكتر_تصانيف' ),
	'Mostrevisions'             => array( 'اكتر_مراجعات' ),
	'Fewestrevisions'           => array( 'اقل_مراجعات' ),
	'Shortpages'                => array( 'صفح_قصيره' ),
	'Longpages'                 => array( 'صفح_طويله' ),
	'Newpages'                  => array( 'صفح_جديده' ),
	'Ancientpages'              => array( 'صفح_قديمه' ),
	'Deadendpages'              => array( 'صفح_نهايه_مسدوده' ),
	'Protectedpages'            => array( 'صفح_محميه' ),
	'Protectedtitles'           => array( 'عناوين_محميه' ),
	'Allpages'                  => array( 'كل_الصفح' ),
	'Prefixindex'               => array( 'فهرس_بدايه' ),
	'Ipblocklist'               => array( 'ليستة_البلوك', 'بيّن_البلوك', 'ليستة_بلوك_IP' ),
	'Unblock'                   => array( 'رفع_منع' ),
	'Specialpages'              => array( 'صفح_مخصوصه' ),
	'Contributions'             => array( 'مساهمات' ),
	'Emailuser'                 => array( 'ابعت_ايميل_لليوزر' ),
	'Confirmemail'              => array( 'تأكيد_الايميل' ),
	'Whatlinkshere'             => array( 'ايه_بيوصل_هنا' ),
	'Recentchangeslinked'       => array( 'اجدد_التغييرات_اللى_معمول_ليها_لينك', 'تغييرات_مرتبطه' ),
	'Movepage'                  => array( 'نقل_صفحه' ),
	'Blockme'                   => array( 'بلوك_لنفسى' ),
	'Booksources'               => array( 'مصادر_كتاب' ),
	'Categories'                => array( 'تصانيف' ),
	'Export'                    => array( 'تصدير' ),
	'Version'                   => array( 'نسخه' ),
	'Allmessages'               => array( 'كل_الرسايل' ),
	'Log'                       => array( 'سجل', 'سجلات' ),
	'Blockip'                   => array( 'بلوك', 'بلوك_IP', 'بلوك_يوزر' ),
	'Undelete'                  => array( 'استرجاع' ),
	'Import'                    => array( 'استوراد' ),
	'Lockdb'                    => array( 'قفل_قب' ),
	'Unlockdb'                  => array( 'فتح_قب' ),
	'Userrights'                => array( 'حقوق_اليوزر', 'ترقية_سيسوپ', 'ترقية_بوت' ),
	'MIMEsearch'                => array( 'تدوير_MIME' ),
	'FileDuplicateSearch'       => array( 'تدوير_فايل_متكرر' ),
	'Unwatchedpages'            => array( 'صفح_مش_متراقبه' ),
	'Listredirects'             => array( 'عرض_التحويلات' ),
	'Revisiondelete'            => array( 'مسح_نسخه' ),
	'Unusedtemplates'           => array( 'قوالب_مش_مستعمله' ),
	'Randomredirect'            => array( 'تحويله_عشوائيه' ),
	'Mypage'                    => array( 'صفحتى' ),
	'Mytalk'                    => array( 'مناقشتى' ),
	'Mycontributions'           => array( 'مساهماتى' ),
	'Listadmins'                => array( 'عرض_الاداريين' ),
	'Listbots'                  => array( 'عرض_البوتات' ),
	'Popularpages'              => array( 'صفح_مشهوره' ),
	'Search'                    => array( 'تدوير' ),
	'Resetpass'                 => array( 'تغيير_الپاسوورد', 'ظبط_الپاسوورد' ),
	'Withoutinterwiki'          => array( 'من-غير_interwiki' ),
	'MergeHistory'              => array( 'دمج_التاريخ' ),
	'Filepath'                  => array( 'مسار_ملف' ),
	'Invalidateemail'           => array( 'تعطيل_الايميل' ),
	'Blankpage'                 => array( 'صفحه_فارضيه' ),
	'LinkSearch'                => array( 'تدوير_اللينكات' ),
	'DeletedContributions'      => array( 'مساهمات_ممسوحه' ),
	'Tags'                      => array( 'وسوم' ),
	'Activeusers'               => array( 'يوزرات_نشطا' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'حط خط تحت اللينكات:',
'tog-highlightbroken'         => 'بين اللينكات البايظه <a href="" class="new">كدا</a> (البديل: زى دا<a href="" class="internal">؟</a>).',
'tog-justify'                 => 'ساوى الپاراجرافات',
'tog-hideminor'               => 'خبى التعديلات الصغيره من اجدد التغييرات',
'tog-hidepatrolled'           => 'خبى التعديلات المتراجعه من اخر التعديلات',
'tog-newpageshidepatrolled'   => 'خبى الصفح المتراجعه من ليستة الصفح الجديده',
'tog-extendwatchlist'         => 'وسع ليستة المراقبه علشان تبين كل التعديلات, مش بس اجدد تعديلات',
'tog-usenewrc'                => 'استعمل اجدد تغييرات متقويه (بيحتاج JavaScript).',
'tog-numberheadings'          => 'رقم العناوين اوتوماتيك',
'tog-showtoolbar'             => 'بين الtoolbar بتاع التعديل (بيحتاج JavaScript)',
'tog-editondblclick'          => 'عدل الصفح لما تدوس مرتين (بيحتاج JavaScript)',
'tog-editsection'             => 'اسمح بـ تعديل الاجزاء عن طريق لينكات [تعديل]',
'tog-editsectiononrightclick' => 'اسمح بـ تعديل الاجزاء لما تعمل right-click بـ الماوس على عناوين الاجزاء (بيحتاج JavaScript)',
'tog-showtoc'                 => 'بين جدول المحتويات (بتاع الصفح اللى فيها اكتر من 3 عناوين)',
'tog-rememberpassword'        => ' (لمدة   $1 {{PLURAL:$1|يوم|يوم}})خليك فاكر دخولى على الكمبيوتر دا',
'tog-watchcreations'          => 'زوّد الصفح اللى ابتديتها على ليستة الصفح اللى باراقبها',
'tog-watchdefault'            => 'زوّد الصفح اللى باعدلها على ليستة الصفح اللى باراقبها',
'tog-watchmoves'              => 'زوّد الصفح اللى بانقلها على ليستة الصفح اللى باراقبها',
'tog-watchdeletion'           => 'زوّد الصفح اللى بامسحها على ليستة الصفح اللى باراقبها',
'tog-minordefault'            => 'علم على كل التعديلات كإنها صغيره فى الاساس',
'tog-previewontop'            => 'بين الپروڤه قبل علبة التعديل',
'tog-previewonfirst'          => 'بين البروفة عند أول تعديل',
'tog-nocache'                 => 'عطّل تخزين البراوزر للصفحه',
'tog-enotifwatchlistpages'    => 'ابعت لى ايميل لما تتغير صفحه فى لستة الصفحات اللى باراقبها',
'tog-enotifusertalkpages'     => 'ابعتلى ايميل لما صفحة مناقشتى تتغيير',
'tog-enotifminoredits'        => 'ابعتلى ايميل للتعديلات الصغيره للصفحات',
'tog-enotifrevealaddr'        => 'بين الايميل بتاعى فى ايميلات الاعلام',
'tog-shownumberswatching'     => 'بين عدد اليوزرز المراقبين',
'tog-oldsig'                  => 'بروفه للامضا الحاليه',
'tog-fancysig'                => 'امضا خام (من غير لينك أوتوماتيك)',
'tog-externaleditor'          => 'استعمل محرر خارجى افتراضيا',
'tog-externaldiff'            => 'استعمل فرق خارجى افتراضيا',
'tog-showjumplinks'           => 'خلى وصلات "روح لـ" تكون شغالة.',
'tog-uselivepreview'          => 'استخدم البروفة السريعة (جافاسكريبت) (تجريبي)',
'tog-forceeditsummary'        => 'نبهنى عند تدخيل ملخص للتعديل  فاضي',
'tog-watchlisthideown'        => 'خبى التعديلات بتاعتى من لستة المراقبة',
'tog-watchlisthidebots'       => 'خبى التعديلات بتاعة البوت من لستة المراقبة',
'tog-watchlisthideminor'      => 'خبى التعديلات البسيطة من لستة المراقبة',
'tog-watchlisthideliu'        => 'خبى التعديلات بتاعة اليوزرز المتسجل دخولهم دلوقتى من لستة المراقبة',
'tog-watchlisthideanons'      => 'خبى التعديلات بتاعة اليوزرز المجهولين من لستة المراقبة',
'tog-watchlisthidepatrolled'  => 'خبى التعديلات المتراجعه من ليستة المراقبه',
'tog-nolangconversion'        => 'عطل تحويل اللهجات',
'tog-ccmeonemails'            => 'ابعتلى  نسخ من رسايل الايميل اللى بابعتها لليوزرز التانيين',
'tog-diffonly'                => 'ما تبين ش محتوى الصفحة تحت الفروقات',
'tog-showhiddencats'          => 'بين التّصنيفات المستخبية',
'tog-noconvertlink'           => 'عطل تحويل عناوين الوصلات',
'tog-norollbackdiff'          => 'الغى الاختلافات بعد ما تعمل الرول باك',

'underline-always'  => 'دايما',
'underline-never'   => 'ابدا',
'underline-default' => 'على حسب إعدادات المتصفح',

# Font style option in Special:Preferences
'editfont-style'     => ':الفونت بتاع مساحة التعديل',
'editfont-default'   => 'حسب إعدادات البراوزر',
'editfont-monospace' => 'فونت  Monospaced',
'editfont-sansserif' => 'فونت  Sans-serif',
'editfont-serif'     => 'فونت Serif',

# Dates
'sunday'        => 'الحد',
'monday'        => 'الاتنين',
'tuesday'       => 'التلات',
'wednesday'     => 'الاربع',
'thursday'      => 'الخميس',
'friday'        => 'الجمعه',
'saturday'      => 'السبت',
'sun'           => 'الحد',
'mon'           => 'الاتنين',
'tue'           => 'التلات',
'wed'           => 'الاربع',
'thu'           => 'الخميس',
'fri'           => 'الجمعه',
'sat'           => 'السبت',
'january'       => 'يناير',
'february'      => 'فبراير',
'march'         => 'مارس',
'april'         => 'ابريل',
'may_long'      => 'مايو',
'june'          => 'يونيه',
'july'          => 'يوليه',
'august'        => 'اغسطس',
'september'     => 'سبتمبر',
'october'       => 'اكتوبر',
'november'      => 'نوفمبر',
'december'      => 'ديسمبر',
'january-gen'   => 'يناير',
'february-gen'  => 'فبراير',
'march-gen'     => 'مارس',
'april-gen'     => 'ابريل',
'may-gen'       => 'مايو',
'june-gen'      => 'يونيه',
'july-gen'      => 'يوليه',
'august-gen'    => 'اغسطس',
'september-gen' => 'سبتمبر',
'october-gen'   => 'اكتوبر',
'november-gen'  => 'نوفمبر',
'december-gen'  => 'ديسمبر',
'jan'           => 'يناير',
'feb'           => 'فبراير',
'mar'           => 'مارس',
'apr'           => 'ابريل',
'may'           => 'مايو',
'jun'           => 'يونيه',
'jul'           => 'يوليه',
'aug'           => 'اغسطس',
'sep'           => 'سبتمبر',
'oct'           => 'اكتوبر',
'nov'           => 'نوفمبر',
'dec'           => 'ديسمبر',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|تصنيف|تصانيف}}',
'category_header'                => 'الصفحات فى التصنيف "$1"',
'subcategories'                  => 'التصنيفات الفرعيه',
'category-media-header'          => 'ملفات الميديا فى التصنيف "$1"',
'category-empty'                 => "''التصنيف ده مافيهوش حاليا مقالات او ملفات ميديا.''",
'hidden-categories'              => '{{PLURAL:$1|تصنيف مستخبي|تصنيفات مستخبية}}',
'hidden-category-category'       => 'تصنيفات مستخبية',
'category-subcat-count'          => '{{PLURAL:$2| التصنيف دا فيه  التصنيف الفرعى الجاى بس.|التصنيف دا فيه {{PLURAL:$1|تصنيف فرعى|$1 تصنيف فرعى}}، من إجمالى $2.}}',
'category-subcat-count-limited'  => ' التصنيف دا فيه {{PLURAL:$1|تصنيف فرعي|$1 تصنيف فرعي}} كدا.',
'category-article-count'         => '{{PLURAL:$2| التصنيف دا فيه  الصفحة دى بس.|تحت {{PLURAL:$1|ملف|$1 ملف}} فى  التصنيف دا ، من إجمالى $2.}}',
'category-article-count-limited' => 'تحت {{PLURAL:$1|صفحة|$1 صفحة}} فى التصنيف الحالى.',
'category-file-count'            => '{{PLURAL:$2| التصنيف دا  فيه الملف الجاى دا بس.|تحت {{PLURAL:$1|ملف|$1 ملف}} فى  التصنيف دا، من إجمالى $2.}}',
'category-file-count-limited'    => 'تحت {{PLURAL:$1|ملف|$1 ملف}} فى التصنيف الحالى.',
'listingcontinuesabbrev'         => 'متابعه',
'index-category'                 => 'صفحات متفهرسه',
'noindex-category'               => 'صفحات مش متفهرسه',

'mainpagetext'      => "''' ميدياويكى اتنزلت بنجاح.'''",
'mainpagedocfooter' => 'اسال [http://meta.wikimedia.org/wiki/Help:Contents دليل اليوزر] للمعلومات حوالين استخدام برنامج الويكى.

== البداية ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings لستة اعدادات الضبط]
* [http://www.mediawiki.org/wiki/Manual:FAQ أسئلة بتكرر حوالين الميدياويكى]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce لستة الايميلات بتاعة اعلانات الميدياويكى]',

'about'         => 'عن',
'article'       => 'صفحة محتوى',
'newwindow'     => '(بتفتح ويندو جديده)',
'cancel'        => 'كانسل',
'moredotdotdot' => 'اكتر...',
'mypage'        => 'صفحتى',
'mytalk'        => 'مناقشاتى',
'anontalk'      => 'المناقشة مع عنوان الأيبى دا',
'navigation'    => 'استكشاف',
'and'           => '&#32;و',

# Cologne Blue skin
'qbfind'         => 'تدوير',
'qbbrowse'       => 'تصفح',
'qbedit'         => 'عدل',
'qbpageoptions'  => ' الصفحه دى',
'qbpageinfo'     => 'السياق',
'qbmyoptions'    => 'صفحاتى',
'qbspecialpages' => 'الصفحات الخاصة',
'faq'            => 'اسئله بتتسئل كتير',
'faqpage'        => 'Project:اسئله بتتسئل كتير',

# Vector skin
'vector-action-addsection' => 'ضيف موضوع',
'vector-action-delete'     => 'مسح',
'vector-action-move'       => 'نقل',
'vector-action-protect'    => 'حمايه',
'vector-action-undelete'   => 'الغى المسح',
'vector-action-unprotect'  => 'الغى الحمايه',
'vector-view-create'       => 'اعمل',
'vector-view-edit'         => 'تعديل',
'vector-view-history'      => 'استعراض التاريخ',
'vector-view-view'         => 'قرايه',
'vector-view-viewsource'   => 'استعراض المصدر',
'actions'                  => 'أعمال',
'namespaces'               => 'النطاقات',
'variants'                 => 'المتغيرات',

'errorpagetitle'    => 'غلطه',
'returnto'          => 'ارجع ل $1.',
'tagline'           => 'من ويكيبيديا, الموسوعه الحره',
'help'              => 'مساعده',
'search'            => 'تدوير',
'searchbutton'      => 'تدوير',
'go'                => 'روح',
'searcharticle'     => 'روح',
'history'           => 'تاريخ الصفحه',
'history_short'     => 'تاريخ',
'updatedmarker'     => 'اتحدثت بعد زيارتى الأخيرة',
'info_short'        => 'معلومات',
'printableversion'  => 'نسخه للطبع',
'permalink'         => 'لينك دايم',
'print'             => 'اطبع',
'edit'              => 'تعديل',
'create'            => 'إبتدى',
'editthispage'      => 'عدل الصفحه دى',
'create-this-page'  => 'أنشيء الصفحه دى',
'delete'            => 'مسح',
'deletethispage'    => 'امسح الصفحه دى',
'undelete_short'    => 'استرجاع {{PLURAL:$1|تعديل واحد|تعديلان|$1 تعديلات|$1 تعديل|$1 تعديلا}}',
'protect'           => 'حمايه',
'protect_change'    => 'غيّر',
'protectthispage'   => 'احمى الصفحه دى',
'unprotect'         => 'الغى الحماية',
'unprotectthispage' => 'شيل حماية الصفحه دى',
'newpage'           => 'صفحه جديده',
'talkpage'          => 'ناقش الصفحه دى',
'talkpagelinktext'  => 'مناقشه',
'specialpage'       => 'صفحة مخصوصة',
'personaltools'     => 'ادوات شخصيه',
'postcomment'       => 'قسم جديد',
'articlepage'       => 'بين صفحة المحتوى',
'talk'              => 'مناقشه',
'views'             => 'مناظر',
'toolbox'           => 'علبة العده',
'userpage'          => 'عرض صفحة اليوزر',
'projectpage'       => 'عرض صفحة المشروع',
'imagepage'         => 'عرض صفحة الملف',
'mediawikipage'     => 'عرض صفحة الرسالة',
'templatepage'      => 'عرض صفحة القالب',
'viewhelppage'      => 'بين صفحة المساعدة',
'categorypage'      => 'عرض صفحة التصنيف',
'viewtalkpage'      => 'بين المناقشة',
'otherlanguages'    => 'بلغات تانيه',
'redirectedfrom'    => '(تحويل من $1)',
'redirectpagesub'   => 'صفحة تحويل',
'lastmodifiedat'    => 'الصفحه دى اتعدلت اخر مره فى $1,‏ $2.',
'viewcount'         => 'الصفحة دى اتدخل عليها{{PLURAL:$1|مرة واحدة|مرتين|$1 مرات|$1 مرة}}.',
'protectedpage'     => 'صفحه محميه',
'jumpto'            => 'نُط على:',
'jumptonavigation'  => 'استكشاف',
'jumptosearch'      => 'تدوير',
'view-pool-error'   => 'متأسفين, السيرفرات عليها حمل كبير دلوقتى.
فى يوزرات كتير قوى بيحاولو يشوفو الصفحه دى.
لو سمحت تستنا شويه قبل ما تحاول تستعرض الصفحه دى من تانى.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'عن {{SITENAME}}',
'aboutpage'            => 'Project:معلومات عن',
'copyright'            => 'المحتوى موجود تحت $1.',
'copyrightpage'        => '{{ns:project}}:حقوق النسخ',
'currentevents'        => 'الاخبار دلوقتى',
'currentevents-url'    => 'Project:الاخبار دلوقتى',
'disclaimers'          => 'تنازل عن مسئوليه',
'disclaimerpage'       => 'Project:تنازل عن مسئوليه عمومى',
'edithelp'             => 'مساعده فى التعديل',
'edithelppage'         => 'Help:تعديل',
'helppage'             => 'Help:محتويات',
'mainpage'             => 'الصفحه الرئيسيه',
'mainpage-description' => 'الصفحه الرئيسيه',
'policy-url'           => 'Project:سياسة',
'portal'               => 'بوابة المجتمع',
'portal-url'           => 'Project:بوابة المجتمع',
'privacy'              => 'بوليسة الخصوصيه',
'privacypage'          => 'Project:بوليسة الخصوصيه',

'badaccess'        => 'غلطه فى السماح',
'badaccess-group0' => 'انت مش مسموح لك تنفذ الطلب بتاعك',
'badaccess-groups' => 'الفعل اللى طلبته مسموح بيه بس لليوزرز اللى فى {{PLURAL:$2|المجموعة|واحده من المجموعات}}: $1.',

'versionrequired'     => 'لازم نسخة $1 من ميدياويكي',
'versionrequiredtext' => 'النسخة $1 من ميدياويكى لازم علشان تستعمل الصفحة دى.
شوف [[Special:Version|صفحة النسخة]]',

'ok'                      => 'موافئ',
'retrievedfrom'           => 'اتجابت من "$1"',
'youhavenewmessages'      => 'عندك $1 ($2).',
'newmessageslink'         => 'رسايل جديده',
'newmessagesdifflink'     => 'اخر تعديل',
'youhavenewmessagesmulti' => 'عندك ميسيدج جديدة فى $1',
'editsection'             => 'تعديل',
'editold'                 => 'تعديل',
'viewsourceold'           => 'عرض المصدر',
'editlink'                => 'عدل',
'viewsourcelink'          => 'عرض المصدر',
'editsectionhint'         => 'تعديل جزء : $1',
'toc'                     => 'المحتويات',
'showtoc'                 => 'عرض',
'hidetoc'                 => 'تخبية',
'thisisdeleted'           => 'عرض او استرجاع $1؟',
'viewdeleted'             => 'عرض $1؟',
'restorelink'             => '{{PLURAL:$1|تعديل واحد ملغي|تعديلين ملغيين|$1 تعديلات ملغية|$1 تعديل ملغي|$1 تعديل ملغي}}',
'feedlinks'               => 'تلقيم:',
'feed-invalid'            => 'نوع اشتراك التغذية مش صح.',
'feed-unavailable'        => 'التغذية مش متوفرة',
'site-rss-feed'           => '$1 RSS feed',
'site-atom-feed'          => '$1 Atom feed',
'page-rss-feed'           => '"$1" ار‌ اس‌ اس فييد',
'page-atom-feed'          => '"$1" فييد أتوم',
'red-link-title'          => '$1 (الصفحه مالهاش وجود)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحه',
'nstab-user'      => 'صفحة يوزر',
'nstab-media'     => 'صفحة ميديا',
'nstab-special'   => 'صفحه مخصوصه',
'nstab-project'   => 'صفحة مشروع',
'nstab-image'     => 'فايل',
'nstab-mediawiki' => 'رساله',
'nstab-template'  => 'قالب',
'nstab-help'      => 'صفحة مساعده',
'nstab-category'  => 'تصنيف',

# Main script and global functions
'nosuchaction'      => 'مافيش فعل زى كده',
'nosuchactiontext'  => 'العمليه المتحدده فى الـ URL مش موجود.
ممكن تكون كتبت الـ URL غلط, او دوست على لينك مش مظبوط.
دا ممكن كمان يكون معناه انه فيه باج (bug) فى الـ {{SITENAME}}.',
'nosuchspecialpage' => 'مافيش صفحة خاصة بالاسم ده',
'nospecialpagetext' => '<strong>انت طلبت صفحه مخصوصه مش موجوده.</strong>

ليستة الصفحات المخصوصه الموجوده ممكن تلاقيها فى [[Special:SpecialPages]].',

# General errors
'error'                => 'غلطه',
'databaseerror'        => 'غلط فى قاعدة البيانات (database)',
'dberrortext'          => 'حصل غلط فى صيغة الاستعلام فى قاعدة البيانات (database).
ممكن يكون بسبب عيب فى البرنامج.
آخر محاوله استعلام اتطلبت من قاعدة البيانات كانت:
<blockquote><tt>$1</tt></blockquote>
من جوه الخاصيه "<tt>$2</tt>".
قاعدة البيانات رجعت الغلط "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'حصل غلط فى صيغة الاستعلام فى قاعدة البيانات (database).
آخر محاوله استعلام اتطلبت من قاعدة البيانات كانت:
"$1"
من جوه الخاصيه "$2".
قاعدة البيانات رجعت الغلط "$3: $4"',
'laggedslavemode'      => "'''تحذير:''' الصفحه يمكن ما يكونش فيها تحديثات جديده.",
'readonly'             => 'قاعدة البيانات (الـ database) مقفوله',
'enterlockreason'      => 'اكتب سبب القفل, و قول امتى تقريبا ح يتلغى القفل',
'readonlytext'         => 'قاعدة البيانات (الـ database) دلوقتى مقفوله على المدخلات الجديده و التعديلات التانيه, يمكن بسبب الصيانه الروتينيه, و بعد كده ح ترجع للحاله الطبيعيه.

الادارى اللى قفل قاعدة البيانات هو اللى كتب التفسير دا:
$1',
'missing-article'      => ' الداتابيس ما لقتش النص المخصوص بتاع صفحه كان لازم تلاقيها, اسمها "$1" $2.

عادة دا بيحصل لما تدوس على لينكات قديمه, فرق التعديل او التاريخ, اللى بتوصلك لصفحه اتمسحت.

لو ما كانش هو دا السبب, ممكن يكون عندك غلط فى البرامج.
لو سمحت بلغ واحد من [[Special:ListUsers/sysop|الاداريين]], و اديله الـ لينك بتاع الصفحه.',
'missingarticle-rev'   => '(المراجعه نمره: $1)',
'missingarticle-diff'  => '(الفرق: $1, $2)',
'readonly_lag'         => 'قاعدة البيانات (الـ database) اتقفلت اوتوماتيكى علشان تقدر السيرڤرات الـ slave تلحق السيرڤر الـ master',
'internalerror'        => 'غلط جوّانى',
'internalerror_info'   => 'غلط جوّانى: $1',
'fileappenderror'      => 'ماقدرناش نضيف "$1" على "$2".',
'filecopyerror'        => 'ما نفع ش  يتنسخ الفايل "$1" لـ "$2".',
'filerenameerror'      => 'ما نفع ش يتغير اسم الفايل "$1" لـ "$2".',
'filedeleteerror'      => 'ما نفع ش يتمسح الفايل "$1".',
'directorycreateerror' => 'ما نفع ش يتعمل الدليل "$1".',
'filenotfound'         => 'مش نافع يلاقى الفايل "$1".',
'fileexistserror'      => 'ما نفع ش يتكتب للفايل "$1": الفايل موجود',
'unexpected'           => 'قيمه مش متوقعه: "$1"="$2".',
'formerror'            => 'غلط: ما نفعت ش تتقدم الاستماره',
'badarticleerror'      => 'مش ممكن تتفذ العمليه دى على الصفحه دى',
'cannotdelete'         => 'مش نافع مسح الصفحه او الفايل "$1".
ممكن يكون حد تانى مسحها/مسحه قبل كده.',
'badtitle'             => 'عنوان غلط',
'badtitletext'         => 'العنوان المطلوب للصفحه مش موجود او فاضى, او اللينك بين اللغات او بين المشاريع غلط.
ممكن يكون موجود رمز او اكتر ما ينفع ش يستخدم فى العناوين.',
'perfcached'           => 'البيانات (الـ data) دى معمول لها كاش (cache) و ممكن ما تكونش متحدثه.',
'perfcachedts'         => 'البيانات (الـ data) دى معمول لها كاش (cache), و اخر تحديث ليها كان فى $1.',
'querypage-no-updates' => 'التحديثات بتاعة الصفحه دى متعطله دلوقتى.
البيانات (الـ data) اللى هنا مش ح تتحدث فى الوقت الحاضر.',
'wrong_wfQuery_params' => 'محددات غلط فى wfQuery()<br />
الخاصّيه: $1<br />
الاستعلام: $2',
'viewsource'           => 'عرض المصدر',
'viewsourcefor'        => 'لـ $1',
'actionthrottled'      => 'العمليه دى اتزنقت',
'actionthrottledtext'  => 'علشان نمنع ال سبام ،أنت ممنوع تعمل  الفعل دا عدد كبير من المرات فى فترة زمنية قصيرة، و انت ا تجاوزت  الحد دا . لو سمحت تحاول مرة ثانية بعد دقائق.',
'protectedpagetext'    => 'الصفحة دى اتقفلت فى وش التعديل.',
'viewsourcetext'       => 'ممكن تشوف وتنسخ مصدر  الصفحه دى:',
'protectedinterface'   => 'الصفحة دى هى اللى بتوفر نص الواجهة بتاعة البرنامج،وهى مقفولة لمنع التخريب.',
'editinginterface'     => "'''تحذير''': أنت بتعدل صفحة بتستخدم فى الواجهة النصية  بتاعة البرنامج. التغييرات فى الصفحة دى ها تأثر على مظهر واجهة اليوزر لليوزرز التانيين. للترجمات، لو سمحت استخدم [http://translatewiki.net/wiki/Main_Page?setlang=ar بيتاويكى]، مشروع ترجمة الميدياويكى.",
'sqlhidden'            => '(استعلام إس‌كيو‌إل متخبي)',
'cascadeprotected'     => 'الصفحة دى محمية من التعديل، بسبب انها مدمجة فى {{PLURAL:$1|الصفحة|الصفحات}} دي، اللى مستعمل فيها خاصية "حماية الصفحات المدمجة" :
$2',
'namespaceprotected'   => "ما عندكش صلاحية تعديل الصفحات  اللى فى نطاق '''$1'''.",
'customcssjsprotected' => 'ماعندكش صلاحية تعديل  الصفحة دي، علشان فيها الإعدادات الشخصية بتاعة يوزر تاني.',
'ns-specialprotected'  => 'الصفحات المخصوصة مش ممكن تعديلها.',
'titleprotected'       => "العنوان دا محمى من الإنشاء بـ[[User:$1|$1]]. السبب هو ''$2''.",

# Virus scanner
'virus-badscanner'     => "غلطه : ماسح فيروسات مش معروف: ''$1''",
'virus-scanfailed'     => 'المسح فشل(كود $1)',
'virus-unknownscanner' => 'انتى فيروس مش معروف:',

# Login and logout pages
'logouttext'                 => "'''أنت دلوقتى مش مسجل دخولك.'''

تقدر تكمل استعمال {{SITENAME}} على انك مجهول، أو [[Special:UserLogin|الدخول مرة تانيه]] بنفس الاسم أو باسم تاني.
ممكن تشوف بعض الصفحات  كأنك متسجل ، و دا علشان استعمال الصفحات المتخبية فى المتصفح بتاعك.",
'welcomecreation'            => '== اهلاً و سهلا يا $1! ==
اتفتحلك حساب.
ما تنساش تغير [[Special:Preferences|تفضيلاتك في {{SITENAME}}]].',
'yourname'                   => 'اليوزرنيم:',
'yourpassword'               => 'الباسوورد:',
'yourpasswordagain'          => 'اكتب الباسورد تاني:',
'remembermypassword'         => ' (لمدة   $1 {{PLURAL:$1|يوم|يوم}})خليك فاكر دخولى على الكمبيوتر دا',
'yourdomainname'             => 'النطاق بتاعك:',
'externaldberror'            => 'يا إما فى حاجة غلط فى الدخول على قاعدة البيانات الخارجية أو انت مش مسموح لك تعمل تحديث لحسابك الخارجي.',
'login'                      => 'دخول',
'nav-login-createaccount'    => 'تسجيل دخول / فتح حساب',
'loginprompt'                => 'لازم تكون الكوكيز عندك مفعله علشان تقدر تدخل ل {{SITENAME}}.',
'userlogin'                  => 'دخول / فتح حساب',
'userloginnocreate'          => 'دخول',
'logout'                     => 'خروج',
'userlogout'                 => 'خروج',
'notloggedin'                => 'انت مش مسجل دخولك',
'nologin'                    => "معندكش حساب؟ '''$1'''.",
'nologinlink'                => 'افتح حساب',
'createaccount'              => 'افتح حساب',
'gotaccount'                 => "عندك حساب؟ '''$1'''.",
'gotaccountlink'             => 'دخول',
'createaccountmail'          => 'بـ الايميل',
'createaccountreason'        => 'السبب:',
'badretype'                  => 'كلمتين السر اللى  كتبتهم مش  زى بعضهم',
'userexists'                 => 'اسم اليوزر اللى دخلته بيستعمله يوزر غيرك.
دخل اسم تانى.',
'loginerror'                 => 'غلط فى الدخول',
'createaccounterror'         => 'مش قادر يعمل الحساب: $1',
'nocookiesnew'               => 'اليوزر خلاص اتفتح له حساب، بس انت لسة ما سجلتش دخولك.
بيستخدم {{SITENAME}} كوكيز عشان يسجل الدخول.
الكوكيز عندك متعطلة.
لو سمحت  تخليها تشتغل، بعدين أدخل ب اسم الحساب و الباسورد الجداد.',
'nocookieslogin'             => '{{SITENAME}} بيستخدم الكوكيز  علشان تسجيل الدخول.
الكوكيز عندك متعطلة.
لو سمحت تخليها تشتغل و بعدين حاول مرة تانية.',
'noname'                     => 'انت ما حددتش اسم يوزر صحيح.',
'loginsuccesstitle'          => 'تم الدخول بشكل صحيح',
'loginsuccess'               => "'''دخولك   {{SITENAME}} إتسجل بإسم \"\$1\".'''",
'nosuchuser'                 => 'مافيش يوزر اسمه "$1".
اسامى اليوزر بتبقى حساسه لحالة الحرف.
اتأكد من التهجيه, او [[Special:UserLogin/signup|افتح حساب جديد]].',
'nosuchusershort'            => 'مافيش يوزر باسم <nowiki>$1</nowiki>".
اتاكد من تهجية الاسم.',
'nouserspecified'            => 'لازم تحدد اسم يوزر.',
'login-userblocked'          => 'اليوزر دا ممنوع من الدخول.',
'wrongpassword'              => 'كلمة السر اللى كتبتها مش صحيحه. من فضلك حاول تانى.',
'wrongpasswordempty'         => 'كلمة السر المدخله كانت فاضيه.
من فضلك حاول تانى.',
'passwordtooshort'           => 'لازم تكون على الاقل{{PLURAL:$1|1 حرف|$1 حروف}}.',
'password-name-match'        => 'الباسورد بتاعتك لازم تكون مختلفه عن اسم اليوزر بتاعك.',
'mailmypassword'             => 'ابعتلى كلمة سر جديدة',
'passwordremindertitle'      => 'كلمة سر مؤقته جديده ل {{SITENAME}}',
'passwordremindertext'       => 'فيه واحد(غالبا انت، من عنوان الاى بى $1)
طلب باسورد جديده لـ{{SITENAME}} ($4).
فى باسورد مؤقتة لليوزر "$2" اتعملت و و اتظبطت لـ "$3".
لو هوه دا اللى إنت عايزه، لازم تسجل دخولك و تختار باسورد جديده دلوقتي.
الباسورد المؤقته بتاعتك ح تنتهى صلاحيتها فى خلال {{PLURAL:$5|يوم واحد|$5 ايام}}.
اما لو كان فى حد تانى هوه اللى عمل الطلب ده، أو انك افتكرت الباسورد بتاعتك، وخلاص مش عايز تغيرها، ممكن تتجاهل الرساله دى وتستمر فى استخدام الباسورد القديمة بتاعتك.',
'noemail'                    => 'مافيش ايميل متسجل  لليوزر  "$1".',
'noemailcreate'              => 'لازم تكتب عنوان إيميل صح',
'passwordsent'               => 'تم إرسال كلمة سر جديدة لعنوان الايميل المتسجل لليوزر "$1".
من فضلك حاول تسجيل الدخول مره تانيه بعد استلامها.',
'blocked-mailpassword'       => 'عنوان الايبى بتاعك ممنوع من التحرير، و كمان مش ممكن تسعمل خاصية ترجيع الباسورد علشان نمنع التخريب.',
'eauthentsent'               => 'فيه ايميل تأكيد اتبعت  للعنوان اللى كتبته.
علشان تبعت اى ايميل تانى للحساب ده لازم تتبع التعليمات اللى فى الايميل اللى اتبعتلك  علشان تأكد ان  الحساب ده بتاعك .',
'throttled-mailpassword'     => 'بعتنالك علشان تفتكر الباسورد بتاعتك، فى خلال الـ{{PLURAL:$1|ساعة|$1 ساعة}} اللى فاتت.
علشان منع التخريب، ح نفكرك مرة و احدة بس كل
{{PLURAL:$1|ساعة|$1 ساعة}}.',
'mailerror'                  => 'غلط فى بعتان الايميل : $1',
'acct_creation_throttle_hit' => 'الناس اللى دخلت ع الويكى دا باستعمال عنوان الاى بى بتاعك فتحو {{PLURAL:$1|1 حساب|$1 حساب}} ف اليوم اللى فات دا, يعنى وصلو للحد الاقصى المسموح بيه فى الفترة الزمنيه المحدده..
و عشان كدا, الزوار اللى بيدخلو بعنوان الاى بى دا مش مسموح لهم يفتحو حسابات اكتر فى الوقت الحالى .',
'emailauthenticated'         => 'اتأكدنا من الايميل بتاعك فى $2 الساعة $3.',
'emailnotauthenticated'      => 'لسة ما اتكدناش من الايميل بتاعك.
مش ح يتبعتلك اى  ايميلات بخصوص الميزات دي.',
'noemailprefs'               => 'علشان الخصايص دى تشتغل لازم تحددلك عنوان ايميل.',
'emailconfirmlink'           => 'أكد عنوان الإيميل بتاعك',
'invalidemailaddress'        => 'مش ممكن نقبل عنوان الايميل لانه مش مظبوط.
دخل ايميل مظبوط او امسحه من الخانة.',
'accountcreated'             => 'الحساب اتفتح',
'accountcreatedtext'         => 'اتفتح حساب لليوزر ب$1.',
'createaccount-title'        => 'فتح حساب فى {{SITENAME}}',
'createaccount-text'         => 'فى واحد فتح حساب باسم الايمل بتاعك على {{SITENAME}} ($4) بالاسم "$2"، وبباسورد "$3". لازم تسجل دخولك دلوقتى و تغير الباسورد بتاعتك.

لو سمحت تتجاهل الرسالة دى اذا الحساب دا اتفتحلك بالغلط.',
'usernamehasherror'          => 'اسم اليوزر مش ممكن يكون فيه حروف هاش',
'login-throttled'            => 'انت عملت  محاولات لوجين كتيره حديثة على الحساب ده.
من فضلك استنى قبل المحاولة مرة تانيه.',
'loginlanguagelabel'         => 'اللغة: $1',

# JavaScript password checks
'password-strength-acceptable' => 'مقبول',
'password-strength-good'       => 'جيدة',
'password-retype'              => 'اكتب الباسورد تاني',
'password-retype-mismatch'     => 'كلمات السر لا تتطابق',

# Password reset dialog
'resetpass'                 => 'غيّر الباسورد',
'resetpass_announce'        => 'اتسجل دخولك دلوقتى بالكود اللى اتبعتلك فى الايميل. علشان تخلص عملية الدخول ،لازم تعملك باسورد جديدة هنا:',
'resetpass_text'            => '<!-- أضف نصا هنا -->',
'resetpass_header'          => 'غيّر الباسورد بتاعة الحساب',
'oldpassword'               => 'الباسورد القديمة:',
'newpassword'               => 'الباسورد جديدة:',
'retypenew'                 => 'اكتب الباسورد الجديده تانى:',
'resetpass_submit'          => 'اظبط الباسورد و ادخل',
'resetpass_success'         => 'الباسورد بتاعتك اتغيرت بنجاح! دلوقتى  بنسجل دخولك...',
'resetpass_forbidden'       => 'مش ممكن تغيير الباسورد',
'resetpass-no-info'         => 'لازم تسجل دخولك علشان تقدر توصل للصفحة دى على طول.',
'resetpass-submit-loggedin' => 'غير الباسورد',
'resetpass-submit-cancel'   => 'الغى',
'resetpass-wrong-oldpass'   => 'الباسورد الحالية او المؤقته مش صحيحة.
انتا ممكن تكون بالفعل غيرت الباسورد بتاعتك بنجاح يا إما تكون طلبت باسورد مؤقته جديدة..',
'resetpass-temp-password'   => 'باسورد مؤقته:',

# Edit page toolbar
'bold_sample'     => 'حروف عريضه',
'bold_tip'        => 'حروف عريضه',
'italic_sample'   => 'كلام مايل',
'italic_tip'      => 'كلام مايل',
'link_sample'     => 'عنوان وصله',
'link_tip'        => 'وصله داخليه',
'extlink_sample'  => 'http://www.example.com عنوان الوصله',
'extlink_tip'     => 'وصله خارجيه (افتكر تحط http:// قبل عنوان الوصله)',
'headline_sample' => 'راس الموضوع',
'headline_tip'    => 'عنوان فرعى من المستوى التانى',
'math_sample'     => 'اكتب المعادله هنا',
'math_tip'        => 'معادله رياضيه (لا تكس )',
'nowiki_sample'   => 'حط  الكلام اللى مش متنسق هنا',
'nowiki_tip'      => 'ما تستعملش فورمات الويكى',
'image_tip'       => 'ملف مغروس',
'media_tip'       => 'وصلة ملف',
'sig_tip'         => 'امضتك مع الساعه والتاريخ',
'hr_tip'          => 'خط افقى (ما تستعملهموش كتير)',

# Edit pages
'summary'                          => 'ملخص:',
'subject'                          => 'راس الموضوع/موضوع:',
'minoredit'                        => 'التعديل ده تعديل صغير',
'watchthis'                        => 'راقب الصفحه دى',
'savearticle'                      => 'سييڤ الصفحه',
'preview'                          => 'بروفه',
'showpreview'                      => 'عرض البروفه',
'showlivepreview'                  => 'بروفه حيه',
'showdiff'                         => 'بيين التعديلات',
'anoneditwarning'                  => "'''تحذير:''' انت ما عملتش لوجين.
عنوان الاى  بى  بتاعك هايتسجل فى تاريخ الصفحه .",
'missingsummary'                   => "'''خد بالك:''' انت ما كتبتش ملخص للتعديل.
لو دوست على سييڤ الصفحه مرة تانية التعديل بتاعك ح يتحفظ من غير ملخص.",
'missingcommenttext'               => 'لو سمحت اكتب تعليق تحت.',
'missingcommentheader'             => "'''.خد بالك:''' انت ما كتبتش عنوان\\موضوع للتعليق دا
لو دوست على {{int:savearticle}} مرة تانيه، تعليقك ح يتحفظ من غير عنوان.",
'summary-preview'                  => 'بروفه للملخص:',
'subject-preview'                  => 'بروفة للعنوان/للموضوع',
'blockedtitle'                     => 'اليوزر ممنوع',
'blockedtext'                      => "'''تم منع اسم اليوزر أو عنوان الااى بى بتاعك .'''

سبب المنع هو: ''$2''. وقام بالمنع $1.

* بداية المنع: $8
* انتهاء المنع: $6
* الممنوع المقصود: $7

ممكن التواصل مع $1 لمناقشة المنع، أو مع واحد من [[{{MediaWiki:Grouppage-sysop}}|الاداريين]] عن المنع>
افتكر انه مش ممكن تبعت ايميل  لليوزرز الا اذا كنت سجلت عنوان ايميل صحيح فى صفحة [[Special:Preferences|التفضيلات]] بتاعتك.
عنوان الااى بى بتاعك حاليا هو $3 وكود المنع هو #$5.من فضلك ضيف اى واحد منهم أو كلاهما فى اى رسالة للتساؤل عن المنع.",
'autoblockedtext'                  => 'عنوان الأيبى بتاعك اتمنع اتوماتيكى  علشان فى يوزر تانى استخدمه واللى هو كمان ممنوع بــ $1.
السبب هو:

:\'\'$2\'\'

* بداية المنع: $8
* انهاية المنع: $6
* الممنوع المقصود: $7

ممكن تتصل  ب $1 أو واحد من
[[{{MediaWiki:Grouppage-sysop}}|الإداريين]] االتانيين لمناقشة المنع.

لاحظ أنه مش ممكن استخدام خاصية "ابعت رسالة لليوزر دا" إلا اذا كان عندك ايميل صحيح متسجل فى [[Special:Preferences|تفضيلاتك]].

عنوان الأيبى الحالى الخاص بك هو $3، رقم المنع هو $5. لو سمحت تذكر الرقم دا فى اى استفسار.',
'blockednoreason'                  => 'ما فيش سبب',
'blockedoriginalsource'            => "المصدر بتاع '''$1''' معروض تحت:",
'blockededitsource'                => "نص '''تعديلاتك''' فى '''$1''' معروض هنا:",
'whitelistedittitle'               => 'لازم تسجل دخولك علشان تقدر تعدل',
'whitelistedittext'                => 'لازم $1 علشان تقدر تعدل الصفحات.',
'confirmedittext'                  => 'قبل ما تبتدى تعدل لازم نتأكد من الايميل بتاعك. لو سمحت تكتب وتأكد الايميل بتاعك  في[[Special:Preferences|تفضيلاتك]]',
'nosuchsectiontitle'               => 'مافيش قسم بالاسم ده',
'nosuchsectiontext'                => 'انت حاولت تعدّل جزء مش موجود.
ممكن يكون اتنقل او اتمسح وقت ما انت كنت بتشوف الصفحه.',
'loginreqtitle'                    => 'لازم تسجل دخولك',
'loginreqlink'                     => 'ادخل',
'loginreqpagetext'                 => 'لازم تكون $1 علشان تشوف صفحات تانية.',
'accmailtitle'                     => ' كلمة السر اتبعتت .',
'accmailtext'                      => "الباسورد العشوائيه اللى اتعملت لـ[[User talk:$1|$1]]  اتبعتت لـ $2.

الباسورد بتاعة الحساب الجديد دا ممكن تتغير فى صفحة ''[[Special:ChangePassword|تغيير الباسورد]]''  وقت تسجيل الدخول.",
'newarticle'                       => '(جديد)',
'newarticletext'                   => "انت وصلت لصفحه مابتدتش لسه.
علشان  تبتدى الصفحة ابتدى الكتابه فى الصندوق اللى تحت.
(بص على [[{{MediaWiki:Helppage}}|صفحة المساعده]] علشان معلومات اكتر)
لو كانت زيارتك للصفحه دى بالغلط، دوس على زرار ''رجوع'' فى متصفح الإنترنت عندك.",
'anontalkpagetext'                 => "----'' صفحة النقاش دى بتاعة يوزر مجهول لسة ما فتحش لنفسه حساب أو عنده واحد بس ما بيستعملوش.
علشان كدا لازم تستعمل رقم الأيبى علشان تتعرف عليه/عليها.
العنوان دا ممكن اكتر من واحد يكونو بيستعملوه.
لو انت يوزر مجهول و حاسس  ان فى تعليقات بتتوجهلك مع انك مالكش دعوة بيها، من فضلك [[Special:UserLogin/signup|افتحلك حساب]] أو [[Special:UserLogin|سجل الدخول]] علشان تتجنب اللخبطة اللى ممكن تحصل فى المستقبل مع يوزرز مجهولين تانيين.''",
'noarticletext'                    => 'مافيش دلوقتى اى نص فى الصفحه دى.
ممكن [[Special:Search/{{PAGENAME}}|تدور على عنوان الصفحه دى]] فى صفح تانيه,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} تدور فى السجلات اللى ليها علاقه],
او [{{fullurl:{{FULLPAGENAME}}|action=edit}} تعدل الصفحه دى]</span>.',
'noarticletext-nopermission'       => 'مفيش اى نص دلوقتى فى الصفحه دى.
ممكن [[Special:Search/{{PAGENAME}}|تدور على عنوان الصفحه دى]] فى الصفحات التانيه,
او <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} تدور فى السجلات بتاعتها]</span>.',
'userpage-userdoesnotexist'        => 'حساب اليوزر "$1" مش متسجل. لو سمحت تشوف لو عايز تبتدي/تعدل الصفحة دي.',
'userpage-userdoesnotexist-view'   => 'حساب اليوزر "$1" مش متسجل',
'blocked-notice-logextract'        => 'اليوزر ده معمول له بلوك دلوقتى.
اخر بلوك فى السجل موجود تحت للمراجعه:',
'clearyourcache'                   => "'''ملاحظة - بعد التسييف,  يمكن لازم تفرغ كاش متصفحك علشان تشوف التغييرات.''' '''موزيللا / فايرفوكس / سافارى:''' دوس على ''Shift'' فى نفس الوقت دوس على ''Reload,'' أو دوس على اما ''Ctrl-F5'' أو ''Ctrl-R'' (''Command-R'' على ماكنتوش); '''كونكرر: '''دوس على ''Reload'' أو دوس على ''F5;'' '''أوبرا:''' فرغ الكاش فى ''Tools → Preferences;'' '''إنترنت إكسبلورر:''' دوس على ''Ctrl'' فى نفس الوقت دوس على ''Refresh,'' أو دوس على ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''ملاحظة:''' استعمل زرار \"{{int:showpreview}}\" علشان تجرب النمط (CSS) أو الجافا سكريبت الجديد قبل تسييڤ الصفحه.",
'userjsyoucanpreview'              => "'''ملاحظة:''' استعمل زرار \"{{int:showpreview}}\" علشان تجرب النمط (CSS) أو الجافا سكريبت الجديد قبل تسييڤ الصفحه.",
'usercsspreview'                   => "'''افتكر انك  بتعرض  (CSS) بتاع اليوزر بس.
هى لسه ماتسييڤتش!'''",
'userjspreview'                    => "'''أفتكر أنك بس بتجرب/بتعرض الجافا سكريبت بتاع اليوزر بتاعك، و انها لسة ماتحفظتش!'''",
'userinvalidcssjstitle'            => "'''تحذير:'''مافيش واجهة  \"\$1\".
افتكر أن ملفات ال.css و ال.js بتستخدم حروف صغيرة فى العنوان ، مثلا {{ns:user}}:Foo/monobook.css و مش {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(متحدثة)',
'note'                             => "'''ملحوظه:'''",
'previewnote'                      => "''' دى بروفه للصفحه بس،
ولسه ما تسييفتش!'''",
'previewconflict'                  => 'البروفة دى بتبينلك فوق إزاى ح يكون شكل النص لو انت دوست على حفظ',
'session_fail_preview'             => "'''ما قدرناش  نحفظ التعديلات اللى قمت بيها نتيجة لضياع بيانات  الجلسه.
الرجاء المحاولة مرة تانيه.
فى حال استمرار المشكلة حاول  [[Special:UserLogou|تخرج]] وتدخل مرة تانيه .'''",
'session_fail_preview_html'        => "'''ماقدرناش نعالج تعديلك بسبب ضياع بيانات الجلسة.'''

''لأن {{SITENAME}} بها HTML هل الخام شغاله، البروفه مخفيه كاحتياط ضد هجمات الجافا سكريبت.''

'''إذا كانت دى محاولة تعديل صادقه، من فضلك حاول مرة تانيه. إذا كانت لسه مش شغاله، حاول [[Special:UserLogout|تسجيل الخروج]] و تسجيل الدخول من جديد.'''",
'token_suffix_mismatch'            => "'''تعديلك اترفض لأن عميلك غلط فى علامات الترقيم
فى نص التعديل. التعديل اترفض علشان ما يبوظش نص المقالة.
دا ساعات بيحصل لما تستعمل خدمة بروكسى مجهولة بايظة أساسها الويب.'''",
'editing'                          => 'تعديل $1',
'editingsection'                   => 'تعديل $1 (جزء)',
'editingcomment'                   => 'تعديل $1 (قسم جديد)',
'editconflict'                     => 'تضارب فى التحرير: $1',
'explainconflict'                  => "فى واحد تانى عدل الصفحة دى  بعد ما انت ابتديت بتحريرها.
صندوق النصوص الفوقانى فيه النص الموجود دلوقتى فى الصفحة.
والتغييرات انت عملتها موجودة فى الصندوق التحتانى فى الصفحة.
لازم تدمج تغييراتك فى النص الموجود دلوقتي.
'''بس''' اللى موجود فى الصندوق الفوقانى هو اللى ح يتحفظ لما تدوس على زرار \"حفظ الصفحة\".",
'yourtext'                         => 'النص بتاعك',
'storedversion'                    => 'النسخه المتسييڤه',
'nonunicodebrowser'                => "'''تحذير: البراوزر بتاعك مش متوافق مع اليونيكود.
اتعالج الموضوع دا علشان تقدر تعدل الصفحة بامان: الحروف اللى مش ASCII ح تظهر فى صندوق التحرير كأكواد سداسية عشرية.'''",
'editingold'                       => "'''تحذير: انت دلوقتى بتعدل نسخه قديمه من الصفحه دى.‏'''
لو سييڤتها, كل التغييرات اللى اتعملت بعد كده هاتضيع.‏",
'yourdiff'                         => 'الفروق',
'copyrightwarning'                 => "لو سمحت لاحظ ان كل المساهمات فى {{SITENAME}} بتتنشر حسب شروط ترخيص $2 (بص على $1 علشان تعرف تفاصيل اكتر).
لو مش عايز كتابتك تتعدل او تتوزع من غير مقابل و من غير اذنك, يبقى ما تحطهاش هنا.<br />
انت كمان بتوعدنا انك كتبت دا بنفسك, او نسخته من مصدر فى الملكيه العامه او مصدر حر شبهه.
'''ما تحطش اى عمل ليه حقوق محفوظه من غير اذن صاحب الحق!'''",
'copyrightwarning2'                => "لو سمحت تعمل حسابك ان كل مشاركاتك فى {{SITENAME}} ممكن المشاركين التانيين يعدلوها،يغيروها، او يمسحوها خالص. لو مانتش حابب ان كتاباتك تتعدل و تتغير بالشكل دا، فياريت ما تنشرهاش هنا.<br />.
و كمان انت بتدينا كلمة شرف  انك صاحب الكتابات دي، او انك نقلتها من مكان مش خاضع لحقوق النشر .(شوف التفاصيل فى $1 ).
'''لو سمحت ما تحطش هنا اى نص خاضع لحقوق النشر من غير تصريح!'''.",
'longpagewarning'                  => "'''تحذير:''' الصفحه دى حجمها $1 ‏kilobyte;‏
شوية براوزرات ممكن يبقى عندها مشاكل لما تحاول تعديل صفح بيزيد حجمها عن 32 ‏kb.‏
لو سمحت فكر فى تقسيم الصفحه لاجزاء اصغر.",
'longpageerror'                    => "'''غلط: النص اللى دخلته حجمه $1 كيلوبايت، ودا أكبر من الحد الأقصى و اللى هو $2 كيلوبايت.
مش ممكن يتحفظ.'''",
'readonlywarning'                  => "'''تحذير: قاعدة البيانات اتقفلت للصيانة، و علشان كدا انت مش ح تقدر تحفظ التعديلات اللى عملتها دلوقاي.
لو حبيت ممكن  تنسخ النص وتحفظه فى ملف نصى علشان تستعمله بعدين.'''

الإدارى اللى أغلقها أعطى هذا التفسير: $1",
'protectedpagewarning'             => "'''تحذير:الصفحة دى اتقفلت بطريقه تخلى اليوزرات السيسوبات هما بس اللى يقدرو يعدلوها.'''
اخر سجل محطوط تحت علشان المراجعه:",
'semiprotectedpagewarning'         => "'''ملاحظه:''' الصفحه دى اتقفلت بطريقه تخلّى اليوزرات المتسجلين بس هما اللى يقدرو يعدّلوها.
اخر سجل محطوط تحت علشان المراجعه:",
'cascadeprotectedwarning'          => '<strong>تحذير: الصفحة دى اتقفلت بطريقة تخلى اليوزرز السيوبات بس هم اللى يقدرو يعدلوها، ودا علشان هى مدموجة فى {{PLURAL:$1|الصفحة|الصفحات}} التالية واللى اتعملها حمتية بخاصية "حماية الصفحات المدموجة":</strong>',
'titleprotectedwarning'            => "'''تحذير: الصفحه دى اتحمت بطريقه تخلّى [[Special:ListGroupRights|حقوق متحدده]] لازم تحتاجها علشان تعمل الصفحه.'''
اخر سجل محطوط تحت علشان المراجعه:",
'templatesused'                    => '{{PLURAL:$1|القالب المستعمل |القوالب المستعمله }}ا فى الصفحه دى:',
'templatesusedpreview'             => '{{PLURAL:$1|القالب المستعمل |القوالب المستعمله}} فى البروفه دى',
'templatesusedsection'             => '{{PLURAL:$1|القالب|القوالب}} اللى بتستخدم فى القسم دا:',
'template-protected'               => '(حمايه كامله)',
'template-semiprotected'           => '(حمايه جزئيه )',
'hiddencategories'                 => 'الصفحه دى موجوده فى {{PLURAL:$1|تصنيف مخفى واحد|$1 تصنيف مخفى}}:',
'edittools'                        => '<!-- النص هنا هايظهر تحت صندوق التحرير و استمارة  تحميل الصور. -->',
'nocreatetitle'                    => 'إنشاء الصفحات اتحدد',
'nocreatetext'                     => '{{SITENAME}} حدد القدره على انشاء صفحات جديده.
ممكن ترجع وتحرر صفحه موجوده بالفعل، او [[Special:UserLogin|الدخول / فتح حساب]].',
'nocreate-loggedin'                => 'انت ما عندك ش صلاحية تعمل صفحات جديدة.',
'sectioneditnotsupported-title'    => 'تعديل الأقسام مش مدعوم',
'sectioneditnotsupported-text'     => 'تعديل الاقسام مش مدعوم فى الصفحه دى',
'permissionserrors'                => 'غلطات فى السماح',
'permissionserrorstext'            => 'ما عندك ش صلاحية تعمل كدا،{{PLURAL:$1|علشان|علشان}}:',
'permissionserrorstext-withaction' => 'أنت ما عندكش الصلاحيات علشان $2، لل{{PLURAL:$1|سبب|أسباب}} ده:',
'recreate-moveddeleted-warn'       => "'''تحذير: انت بتعيد انشاء صفحه اتمسحت قبل كده.'''
لازم تتأكد من ان الاستمرار فى تحرير الصفحه دى ملائم.
سجلات الحذف و النقل بتوع الصفحه دى معروضه هنا:",
'moveddeleted-notice'              => 'الصفحة دى اتمسحت. سجل المسح و سجل النقل بتوع الصفحة معروضين تحت علشان ترجعلهم.',
'log-fulllog'                      => 'استعراض السجل بالكامل',
'edit-hook-aborted'                => 'الخطاف ساب التعديل من غير مايدى تفسير.',
'edit-gone-missing'                => 'لم يمكن تحديث الصفحة.
يبدو أنه تم حذفها.',
'edit-conflict'                    => 'تضارب تحريرى.',
'edit-no-change'                   => 'تعديلك تم تجاهله، لأن ما حصلش أى تعديل للنص.',
'edit-already-exists'              => 'لم يمكن إنشاء صفحة جديدة.
هى موجودة بالفعل.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'تحذير: الصفحه دى فيهااستدعاءات دالة محلل كثيرة مكلفة.

لازم تكون أقل من $2 {{PLURAL:$2|استدعاء|استدعاء}}، يوجد {{PLURAL:$1|الآن $1 استدعاء|الآن $1 استدعاء}}..',
'expensive-parserfunction-category'       => 'صفحات فيها استدعاءات دوال محلل كثيرة ومكلفة',
'post-expand-template-inclusion-warning'  => 'تحذير: حجم تضمين القالب كبير قوي.
بعض القوالب مش ح تتضمن.',
'post-expand-template-inclusion-category' => 'الصفحات اللى تم تجاوز حجم تضمين القالب فيها',
'post-expand-template-argument-warning'   => 'تحذير: الصفحة  دى فيها عامل قالب واحد على الأقل ليه حجم تمدد كبير قوي.
العوامل دى اتمسحت.',
'post-expand-template-argument-category'  => 'صفحات فيها مناقشات القالب المحذوفة',
'parser-template-loop-warning'            => 'لووب القالب المحدد: [[$1]]',
'parser-template-recursion-depth-warning' => 'حد عمق الريكيرشيون بتاع القالب اتعدى  ($1)',
'language-converter-depth-warning'        => 'حد عمق محول اللغه اتعدى ($1)',

# "Undo" feature
'undo-success' => 'ممكن ترجع فى التعديل.
لو سمحت تشوف المقارنة اللى تحت علشان تتأكد من إن هو دا اللى إنت عايز تعمله ،وبعدين احفظ التغييرات اللى تحت علشان ترجع فى التعديل.',
'undo-failure' => 'الرجوع فى التعديل ما نفعش علشان فى تعديلات متعاكسة حصلت فى الصفحة.',
'undo-norev'   => 'الرجوع فى التعديل ما نفعش علشان هو يا إما مش موجود أو انه إتمسح.',
'undo-summary' => 'الرجوع فى التعديل $1 بتاع [[Special:Contributions/$2|$2]] ([[User talk:$2|نقاش]])',

# Account creation failure
'cantcreateaccounttitle' => 'مش ممكن فتح حساب',
'cantcreateaccount-text' => "فتح الحسابات من عنوان الأيبى دا ('''$1''') منعه [[User:$3|$3]].

السبب إللى إداه $3 هو ''$2''",

# History pages
'viewpagelogs'           => 'عرض السجلات للصفحه دى',
'nohistory'              => 'الصفحة دى ما لهاش تاريخ تعديل.',
'currentrev'             => 'النسخه دلوقتى',
'currentrev-asof'        => 'المراجعة الحالية بتاريخ $1',
'revisionasof'           => 'تعديلات من $1',
'revision-info'          => 'نسخه $1 بواسطة $2',
'previousrevision'       => '←نسخه اقدم',
'nextrevision'           => 'نسخه احدث→',
'currentrevisionlink'    => 'النسخه دلوقتى',
'cur'                    => 'دلوقتى',
'next'                   => 'اللى بعد كده',
'last'                   => 'قبل كده',
'page_first'             => 'الأولانية',
'page_last'              => 'الأخرانية',
'histlegend'             => 'اختيار الفرق: علم على صناديق النسخ للمقارنه و اضغط قارن بين النسخ المختاره او الزرار اللى تحت.<br />
مفتاح: (دلوقتى) = الفرق مع النسخة دلوقتى
(اللى قبل كده) = الفرق مع النسخة اللى قبل كده، ص = تعديل صغير',
'history-fieldset-title' => 'تصفح التاريخ',
'history-show-deleted'   => 'محذوف بس',
'histfirst'              => 'اول',
'histlast'               => 'آخر',
'historysize'            => '({{PLURAL:$1|1 بايت|$1 بايت}})',
'historyempty'           => '(فاضى)',

# Revision feed
'history-feed-title'          => 'تاريخ المراجعة',
'history-feed-description'    => 'تاريخ التعديل بتاع الصفحة دى على الويكي',
'history-feed-item-nocomment' => '$1 فى $2',
'history-feed-empty'          => 'الصفحة المطلوبة مش موجودة.
من المحتمل تكون الصفحة أتمسحت أو أتنقلت.
حاول [[Special:Search|التدوير فى الويكى]] عن صفحات جديدة ليها صلة.',

# Revision deletion
'rev-deleted-comment'         => '(التعليق اتشال)',
'rev-deleted-user'            => '(اسم اليوزر اتشال)',
'rev-deleted-event'           => '(السجل إتشال)',
'rev-deleted-user-contribs'   => '[اسم اليوزر أو الآى بى اتشال - التعديل مخفى من المساهمات]',
'rev-deleted-text-permission' => 'مراجعة الصفحه دى إتمسحت من الأرشيفات العامه.
ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل المسح].',
'rev-deleted-text-unhide'     => "مراجعة الصفحه دى '''اتمسحت'''. ممكن تلاقى تفاصيل فى [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل المسح].
لو كنت انت ادارى ممكن[$1 تشوف المراجعه دى] لو كنت عايز تكمل..",
'rev-suppressed-text-unhide'  => "نسخه الصفحه دى '''اتخبت'''.
ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سجل التخبيه].
كسيسوب إنت ممكن [$1 تشوف النسخه دى] لو إنت عايز تتابع.",
'rev-deleted-text-view'       => 'التعديل ده اتمسح من الأرشيف العام. ممكن تشوف التعديل ده علشان إنت إدارى فى {{SITENAME}} .
ممكن يكون فيه تفاصيل بخصوص ده فى [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل المسح].',
'rev-suppressed-text-view'    => "نسخه الصفحه دى '''اتخبت'''.
كسيسوب ممكن تشوفها؛ ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سجل التخبيه].",
'rev-deleted-no-diff'         => "انت ماينفعش تشوف الفرق دا علشان واحده من المراجعات '''اتمسحت'''. ممكن يكون فيه تفاصيل فى[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل المسح].",
'rev-suppressed-no-diff'      => "'''انت ما تقدرش تستعرض التغيير دا لان واحده من التعديلات''' اتمسحت.",
'rev-deleted-unhide-diff'     => "واحده من مراجعات الفرق ده  '''اتمسحت'''. ممكن تلاقى تفاصيل فى [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل الكبت].
انتا لو ادارى ممكن [$1 تشوف الفرق دا] لو كانت عايز تستمر",
'rev-suppressed-unhide-diff'  => "واحده من نسخ الفرق ده '''اتخبت'''.
ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سجل التخبيه].
كسيسوب ممكن [$1 تشوف الفرق ده] لو إنت عايز تكمل.",
'rev-deleted-diff-view'       => "واحده من نسخ الفرق ده '''اتمسحت'''.
كسيسوب ممكن تشوف الفرق ده؛ ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} سجل الحذف].",
'rev-suppressed-diff-view'    => "واحده من نسخ الفرق ده '''اتخبت'''.
كسيسوب ممكن تشوف الفرق ده؛ ممكن تكون فيه تفاصيل فى [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} سجل التخبيه].",
'rev-delundel'                => 'عرض/تخبيه',
'rev-showdeleted'             => 'اعرض',
'revisiondelete'              => 'امسح/الغى المسح بتاع المراجعات',
'revdelete-nooldid-title'     => 'مراجعة هدف مش صح',
'revdelete-nooldid-text'      => 'أنت ياإما ما حددتش مراجعة (مراجعات) معينة كهدف للفعل دا، المراجعة المحددة مش موجودة، أو أنك بتحاول تخبى المراجعة الحالية.',
'revdelete-nologtype-title'   => 'انت ما اديتش نوع سجل',
'revdelete-nologtype-text'    => 'انت ما اديتش نوع سجل عشان تعمل العمليه دى عليه',
'revdelete-nologid-title'     => 'مدخلة السجل مش صح',
'revdelete-nologid-text'      => 'انتا يا إما ما حددتش حدث سجل مستهدف عشان تعمل العمليه دى يا المدخله اللى انتا حددتها مش موجوده.',
'revdelete-no-file'           => 'الملف المتحدد مالوش وجود',
'revdelete-show-file-confirm' => 'انتا متأكد من انك عايز تشوف المراجعه الممسوحه بتاعة الملف "<nowiki>$1</nowiki>" من  $2 لحد $3?',
'revdelete-show-file-submit'  => 'ايوه',
'revdelete-selected'          => "'''{{PLURAL:$2|المراجعه المختاره|المراجعات المختاره}} بتاعة [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|حدث السجل المختار|أحداث السجل المختارة}}:'''",
'revdelete-text'              => "'''المراجعات و الاحداث الممسوحه ح تنيها تظهر فى تاريخ الصفحه و فى السجلات, بس فى اجزاء من محتواها مش ح تبقا متاحه لكل الناس .'''
الاداريين التانيين فى  {{SITENAME}} ح يقدرو يوصول للمحتوى المخفىو كمان ممكن يرجعو المسح عن طريق نفس الواجه دى ، الا اذا اتحطت قيود اضافيه.",
'revdelete-confirm'           => 'لو سمحت اتأكدد انك ناوى تعمل كدا, و انك فاهم اللى ح يترتب على كدا, و انك بتعمل كدا بالتوافق مع مع [[{{MediaWiki:Policy-url}}|السياسه]].',
'revdelete-suppress-text'     => "الكبت لازم ييتعمل '''بس''' فى الحالات دى:
* معلومات شخصيه مش مناسبه
*: ''عنوان البيت او رقم التليفون, رقم الضمان الاجتماعى, الخ.''",
'revdelete-legend'            => 'وضع حدود رؤية',
'revdelete-hide-text'         => 'إخفاء نص النسخة',
'revdelete-hide-image'        => 'خبى المحتويات بتاعة الملف',
'revdelete-hide-name'         => 'تخبية الإجراء والهدف منه',
'revdelete-hide-comment'      => 'خبى تعليق التعديل',
'revdelete-hide-user'         => 'خبى اسم/عنوان الاى بى بتاع اليوزر',
'revdelete-hide-restricted'   => 'طبق القواعد دى على السيسوبات زى الباقيين',
'revdelete-radio-same'        => '(ماتغيرش)',
'revdelete-radio-set'         => 'أيوه',
'revdelete-radio-unset'       => 'لأ',
'revdelete-suppress'          => 'تخبية البيانات عن السيسوبات و اليوزرز التانيين',
'revdelete-unsuppress'        => 'إزالة الضوابط من المراجعات المسترجعة',
'revdelete-log'               => 'السبب:',
'revdelete-submit'            => 'طبق على {{PLURAL:$1|المراجعه|المراجعه}} المختارة',
'revdelete-logentry'          => 'غير رؤية المراجعة ل[[$1]]',
'logdelete-logentry'          => 'غير رؤية الحدث ل[[$1]]',
'revdelete-success'           => "''' رؤية المراجعه اتظبطت بنجاح.'''",
'revdelete-failure'           => "'''عرض المراجعه ما نفعش يتعاد ظبطه:'''
$1",
'logdelete-success'           => "'''رؤية السجلات اتظبطت بنجاح.'''",
'logdelete-failure'           => "'''مانفعش اعادة ظبط عرض السجل:'''
$1",
'revdel-restore'              => 'تغيير الشوف',
'pagehist'                    => 'تاريخ الصفحة',
'deletedhist'                 => 'التاريخ الممسوح',
'revdelete-content'           => 'محتويات',
'revdelete-summary'           => 'ملخص التعديل',
'revdelete-uname'             => 'اسم اليوزر',
'revdelete-restricted'        => 'طبق التعليمات على السيسوبات',
'revdelete-unrestricted'      => 'شيل الضوابط من على السيسوبات',
'revdelete-hid'               => 'أخفى $1',
'revdelete-unhid'             => 'أظهر $1',
'revdelete-log-message'       => '$1 ل$2 {{PLURAL:$2|مراجعة|مراجعة}}',
'logdelete-log-message'       => '$1 ل$2 {{PLURAL:$2|حدث|حدث}}',
'revdelete-hide-current'      => 'حصل غلط فى تخبية البند اللى بتاريخ $2, الساعه$1: دى هى النسخه بتاعة دلوقتى.
ماينفعش يتخبى.',
'revdelete-show-no-access'    => 'حصل غلط فى عرض البند اللى بتاريخ $2, الساعه $1: البند دا متعلم عليه انه"محظور".
انتا ما عندكش صلاحية الوصول ليه.',
'revdelete-modify-no-access'  => 'حصل غلط فى تعديل البند اللى بتاريخ$2, الساعه $1: البند دا متعلم عليه انه"محظور".
انتا ماعندكش صلاحية الوصول ليه..',
'revdelete-modify-missing'    => 'حصل غلط فى تعديل ال ID بتاعة البند $1: ضايع من قاعدة المعلومات!',
'revdelete-no-change'         => "'''تحذير:''' البند اللى بتاريخ$2, الساعه $1 اعدادات الرؤيه اللى انتا طلبتها موجوده بالفعل.",
'revdelete-concurrent-change' => 'حصل غلط فى تعديل البند اللى بتاريخ $2,الساعه $1: حالته الظاهر فى حد تانى غيرها و انتا بتحاول تعدل فيها..
لو سمحت بص على السجلات.',
'revdelete-only-restricted'   => 'خطأ تخبيه العنصر اللى تاريخه $2, $1: ماينفعش تمنع بنود من ان الاداريين يشوفوها من غير ما تختار كمان واحد من اختيارات الكبت التانيه.',
'revdelete-reason-dropdown'   => '*اسباب المسح المعتاده
** خرق لحقوق النشر
** معلومات شخصيه مش مناسبه
** معلومات للتشهير',
'revdelete-otherreason'       => 'سبب تانى/اضافى:',
'revdelete-reasonotherlist'   => 'سبب تانى',
'revdelete-edit-reasonlist'   => 'عدل أسباب المسح',
'revdelete-offender'          => 'صاحب المراجعة:',

# Suppression log
'suppressionlog'     => 'سجل الإخفاء',
'suppressionlogtext' => 'تحت فى لستة بعمليات المسح والمنع اللى فيها محتوى مستخبى على الإداريين.
شوف [[Special:IPBlockList|للستة المنع]] علشان تشوف عمليات المنع الشغالة دلوقتى .',

# Revision move
'revisionmove'              => ' انقل المراجعات من "$1"',
'revmove-reasonfield'       => 'السبب:',
'revmove-norevisions-title' => 'مراجعة هدف مش صح',
'revmove-nullmove-title'    => 'عنوان غلط',

# History merging
'mergehistory'                     => 'دمج تواريخ الصفحة',
'mergehistory-header'              => ' الصفحةدى  بتسمح لك بدمج نسخ تاريخ صفحة  فى صفحة تانية.
اتأكد من أن التغيير دا ح يحافظ على استمرارية تاريخ الصفحة.',
'mergehistory-box'                 => 'دمج تعديلات صفحتين:',
'mergehistory-from'                => 'الصفحه المصدر:',
'mergehistory-into'                => 'الصفحه الهدف:',
'mergehistory-list'                => 'تاريخ التعديل اللى ممكن يتدمج',
'mergehistory-merge'               => 'المراجعات دى من [[:$1|$1]] ممكن دمجها مع[[:$2|$2]].
استخدم عامود الصناديق لدمج المراجعات التى اتنشأت فى وقبل الوقت المحدد.
خد بالك من إن استخدام وصلات التصفح ح يعيد ضبط  العامود دا.',
'mergehistory-go'                  => 'عرض التعديلات اللى ممكن تتدمج',
'mergehistory-submit'              => 'دمج النسخ',
'mergehistory-empty'               => 'مافيش مراجعات ممكن دمجها.',
'mergehistory-success'             => '$3 {{PLURAL:$3|مراجعة|مراجعة}} من [[:$1]] تم دمجها بنجاح فى [[:$2]].',
'mergehistory-fail'                => 'مش قادر يعمل دمج للتاريخ، لو سمحت تتأكد تانى من محددات الصفحة والزمن.',
'mergehistory-no-source'           => 'الصفحة المصدر $1  مش موجودة.',
'mergehistory-no-destination'      => 'الصفحه الهدف $1 مش موجوده.',
'mergehistory-invalid-source'      => 'الصفحه المصدر لازم تكون عنوان صحيح.',
'mergehistory-invalid-destination' => 'الصفحة الهدف لازم تكون عنوانها صحيح.',
'mergehistory-autocomment'         => 'دمج [[:$1]] فى [[:$2]]',
'mergehistory-comment'             => 'دمج [[:$1]] فى [[:$2]]: $3',
'mergehistory-same-destination'    => 'صفحتا المصدر والهدف لا يمكن أن تكونا نفس الشىء',
'mergehistory-reason'              => 'السبب:',

# Merge log
'mergelog'           => 'سجل الدمج',
'pagemerge-logentry' => 'دمج [[$1]] لـ [[$2]] (النسخ حتى $3)',
'revertmerge'        => 'استرجاع الدمج',
'mergelogpagetext'   => 'فى تحت لستة بأحدث عمليات الدمج لتاريخ صفحة فى التانية.',

# Diffs
'history-title'            => 'تاريخ تعديل "$1"',
'difference'               => '(الفرق بين النسخ)',
'lineno'                   => 'سطر $1:',
'compareselectedversions'  => 'قارن بين النسختين المختارتين',
'showhideselectedversions' => 'عرض/تخبية المراجعات المختاره.',
'editundo'                 => 'استرجاع',
'diff-multi'               => '({{PLURAL:$1|نسخه واحده متوسطه|$1 نسخ متوسطه}} by {{PLURAL:$2|يوزر واحد |$2 يوزرات}}  مش معروضه)',

# Search results
'searchresults'                    => 'نتايج التدوير',
'searchresults-title'              => 'نتايج التدوير على "$1"',
'searchresulttext'                 => 'لو عاوز تعرف اكتر عن التدوير على {{SITENAME}}, شوف [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'التدوير كان على \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|كل الصفح اللى بتبتدى بـ "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|كل الصفح اللى بتوصل لـ "$1"]])',
'searchsubtitleinvalid'            => "انت دورت على '''$1'''",
'toomanymatches'                   => 'لقينا حاجات كتيرة متطابقة، لو سمحت تجرب استعلام مختلف',
'titlematches'                     => 'عنوان الصفحة زى',
'notitlematches'                   => 'ما فيش عنوان صفحه زى كده:',
'textmatches'                      => 'نص الصفحة بيطابق',
'notextmatches'                    => 'ما لقيناش أى نص مطابق',
'prevn'                            => '{{PLURAL:$1|$1}} اللى قبل كده',
'nextn'                            => '{{PLURAL:$1|$1}} اللى بعد كده',
'prevn-title'                      => '$1 {{PLURAL:$1|نتيجه|نتيجه}} سابقه',
'nextn-title'                      => '{{PLURAL:$1|النتيجه|النتايج}}  $1 اللى بعد كدا.',
'shown-title'                      => 'اعرض $1 {{PLURAL:$1|نتيجه|نتايج}} فى كل صفحه',
'viewprevnext'                     => 'شوف ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'اختيارات التدوير',
'searchmenu-exists'                => "*الصفحة '''[[$1]]'''",
'searchmenu-new'                   => "'''ابتدى الصفحه \"[[:\$1]]\" ع الويكى دا!'''",
'searchhelp-url'                   => 'Help:محتويات',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|استعرض الصفحات اللى بتبتدى بالبريفيكس دا]]',
'searchprofile-articles'           => 'صفحات محتوى',
'searchprofile-project'            => 'صفحات  المساعده و المشروع',
'searchprofile-images'             => 'مالتيميديا',
'searchprofile-everything'         => 'كل شىء',
'searchprofile-advanced'           => 'متقدم',
'searchprofile-articles-tooltip'   => 'ابحث فى $1',
'searchprofile-project-tooltip'    => 'دور فى $1',
'searchprofile-images-tooltip'     => 'ابحث عن الصور',
'searchprofile-everything-tooltip' => 'ابحث فى كل المحتوى (شاملا صفحات النقاش)',
'searchprofile-advanced-tooltip'   => 'ابحث فى النطاقات المخصصة',
'search-result-size'               => '$1 ({{PLURAL:$2|1 كلمه|$2 كلام}})',
'search-result-score'              => 'الارتباط: $1%',
'search-redirect'                  => '(تحويله $1)',
'search-section'                   => '(جزء $1)',
'search-suggest'                   => 'قصدك: $1',
'search-interwiki-caption'         => 'المشاريع الشقيقة',
'search-interwiki-default'         => '$1 نتيجة:',
'search-interwiki-more'            => '(اأكتر)',
'search-mwsuggest-enabled'         => 'مع اقتراحات',
'search-mwsuggest-disabled'        => 'مافيش اقتراحات',
'search-relatedarticle'            => 'مرتبطه',
'mwsuggest-disable'                => 'تعطيل اقتراحات أجاكس',
'searcheverything-enable'          => 'دور فى النطاقات كلها.',
'searchrelated'                    => 'مرتبطه',
'searchall'                        => 'الكل',
'showingresults'                   => "القائمة دى بتعرض {{PLURAL:$1|'''1''' نتيجة|'''$1''' نتيجة}} من أول  رقم '''$2'''.",
'showingresultsnum'                => "معروض تحت {{PLURAL:$3|'''نتيجة واحدة'''|'''$3''' نتيجة}} من أول من رقم'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|النتيجه '''$1''' من'''$3'''|النتايج '''$1 - $2''' من'''$3'''}} ل'''$4'''",
'nonefound'                        => "'''ملاحظة''': فى شوية اسامى مساحات بس بيتدور فيها اوتوماتيكى.
حاول تبتدى تدويرك بـ ''all:'' علشان تدور فى المحتوى كله (مع صفح المناقشه, القوالب, الخ), او استعمل اسم المساحه المطلوب اللى تدور فيه.",
'search-nonefound'                 => 'لا توجد نتائج تطابق الاستعلام.',
'powersearch'                      => 'تدوير متفصل',
'powersearch-legend'               => 'تدوير متقدم',
'powersearch-ns'                   => 'تدوير فى اسم المساحه:',
'powersearch-redir'                => 'لستة التحويلات',
'powersearch-field'                => 'تدوير على',
'powersearch-togglelabel'          => 'التشييك:',
'powersearch-toggleall'            => 'الكل',
'powersearch-togglenone'           => 'و لا حاجه',
'search-external'                  => 'تدوير بره',
'searchdisabled'                   => 'التدوير فى {{SITENAME}} متعطل.
ممكن تدور فى جوجل دلوقتي.
لاحظ أن فهارسه لمحتوى {{SITENAME}} يمكن تكون مش متحدثة.',

# Quickbar
'qbsettings'               => 'البار السريع',
'qbsettings-none'          => 'ما فى ش',
'qbsettings-fixedleft'     => 'متثبت فى الشمال',
'qbsettings-fixedright'    => 'متثبت فى اليمين',
'qbsettings-floatingleft'  => 'عايم على الشمال',
'qbsettings-floatingright' => 'عايم على اليمين',

# Preferences page
'preferences'                   => 'تفضيلات',
'mypreferences'                 => 'تفضيلاتى',
'prefs-edits'                   => 'عدد التعديلات:',
'prefsnologin'                  => 'مش متسجل',
'prefsnologintext'              => 'لازم تكون <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} مسجل الدخول]</span> علشان تقدر تعدل تفضيلاتك.',
'changepassword'                => 'غير الباسورد',
'prefs-skin'                    => 'الوش',
'skin-preview'                  => 'بروفه',
'prefs-math'                    => 'رياضة',
'datedefault'                   => 'مافبش تفضيل',
'prefs-datetime'                => 'وقت وتاريخ',
'prefs-personal'                => 'الپروفيل بتاع اليوزر',
'prefs-rc'                      => 'اخر التغييرات',
'prefs-watchlist'               => 'ليستة المراقبه',
'prefs-watchlist-days'          => 'الايام اللى تتعرض فى ليستة المراقبه:',
'prefs-watchlist-days-max'      => '(الحد الاقصى 7 ايام)',
'prefs-watchlist-edits'         => 'عدد التعديلات اللى بتتعرض فى لستةالمراقبة المتوسعة:',
'prefs-watchlist-edits-max'     => '(الرقم الاقصى: 1000)',
'prefs-watchlist-token'         => 'مفتاح قايمة المراقبه:',
'prefs-misc'                    => 'متفرقات',
'prefs-resetpass'               => 'غير الباسورد',
'prefs-email'                   => 'اختيارات الايميل',
'prefs-rendering'               => 'المنظر',
'saveprefs'                     => 'حفظ',
'resetprefs'                    => 'امسح التغييرات اللى مش المحفوظة',
'restoreprefs'                  => 'استرجع التظبيطات الاصليه',
'prefs-editing'                 => 'تعديل',
'prefs-edit-boxsize'            => 'كُبر الويندو بتاعة التحرير',
'rows'                          => 'صفوف:',
'columns'                       => 'عمدان:',
'searchresultshead'             => 'تدوير',
'resultsperpage'                => 'عدد النتايج فى الصفحة:',
'contextlines'                  => 'عدد  السطور فى كل نتيجة:',
'contextchars'                  => 'عدد  الحروف فى كل سطر',
'stub-threshold'                => 'الحد لتنسيق <a href="#" class="stub">لينك البذرة</a>:',
'stub-threshold-disabled'       => 'معطل',
'recentchangesdays'             => 'عدد الأيام المعروضة فى اخرالتغييرات:',
'recentchangesdays-max'         => '(الحد الاقصى $1 {{PLURAL:$1|يوم|ايام}})',
'recentchangescount'            => 'عدد التعديلات اللى بتظهر اوتوماتيكى فى اخر التغييرات, تواريخ الصفحه, و فى السجلات, :',
'prefs-help-recentchangescount' => 'بيحتوى على احدث التغييرات ، تواريخ الصفحات و السجلات.',
'prefs-help-watchlist-token'    => 'ملى الحقل ده بمفتاح سرى حيعمل تلقيم RSS لقايمه مراقبتك.
اى واحد بعرف المفتاح فى الحقل ده ممكن يقرأ قايمه مراقبتك، علشان كده اختار قيمه متأمنه.
دى قيمه متولده عشوائى وممكن تستخدمها: $1',
'savedprefs'                    => 'التفضيلات بتاعتك اتحفظت.',
'timezonelegend'                => 'منطقة التوقيت',
'localtime'                     => 'التوقيت المحلى',
'timezoneuseserverdefault'      => 'استخدم افتراض السرفر',
'timezoneuseoffset'             => 'تانى (حدد الفرق)',
'timezoneoffset'                => 'فرق¹',
'servertime'                    => 'وقت السيرفر',
'guesstimezone'                 => 'دخل التوقيت من البراوزر',
'timezoneregion-africa'         => 'افريقيا',
'timezoneregion-america'        => 'امريكا',
'timezoneregion-antarctica'     => 'انتاركتيكا',
'timezoneregion-arctic'         => 'القطب الشمالى',
'timezoneregion-asia'           => 'اسيا',
'timezoneregion-atlantic'       => 'المحيط الاطلانطى',
'timezoneregion-australia'      => 'اوستراليا',
'timezoneregion-europe'         => 'اوروبا',
'timezoneregion-indian'         => 'المحيط الهندى',
'timezoneregion-pacific'        => 'المحيط الهادى',
'allowemail'                    => 'السماح لليوزرز التانيين يبعتولى ايميل',
'prefs-searchoptions'           => 'اختيارات التدوير',
'prefs-namespaces'              => 'أسماء النطاقات',
'defaultns'                     => 'أو دور فى النطاقات دى:',
'default'                       => 'اوتوماتيكي',
'prefs-files'                   => 'ملفات',
'prefs-custom-css'              => 'CSS مخصص',
'prefs-custom-js'               => 'مخصص JS',
'prefs-reset-intro'             => 'ممكن تستعمل الصفحه دى عشان تعيد ظبط التفضيلات بتاعتك و تخليها زى الحاله الافتراضيه للموقع.
ماينفعش الرجوع فى التعديل دا.',
'prefs-emailconfirm-label'      => 'التأكد من الايميل:',
'prefs-textboxsize'             => 'حجم شباك التعديل',
'youremail'                     => 'الايميل:',
'username'                      => 'اسم اليوزر:',
'uid'                           => 'رقم اليوزر:',
'prefs-memberingroups'          => 'عضو فى {{PLURAL:$1|مجموعة|مجموعة}}:',
'prefs-registration'            => 'وقت التسجيل:',
'yourrealname'                  => 'الاسم الحقيقى:',
'yourlanguage'                  => 'اللغة:',
'yourvariant'                   => 'اللهجة:',
'yournick'                      => 'الإمضا:',
'prefs-help-signature'          => 'التعليقات فى صفحات النقاش لازم تتوقع ب"<nowiki>~~~~</nowiki>" واللى حتتحول لتوقيعك وتاريخ.',
'badsig'                        => 'الامضا الخام بتاعتك مش صح.
اتإكد من التاجز بتاعة الHTML.',
'badsiglength'                  => 'الامضا بتاعتك اطول م اللازم.
لازم تكون اصغر من$1 {{PLURAL:$1|حرف|حرف}}.',
'yourgender'                    => 'النوع:',
'gender-unknown'                => 'مش متحدد',
'gender-male'                   => 'ذكر',
'gender-female'                 => 'انثى',
'prefs-help-gender'             => 'اختياري: بيستعملوه فى  المخاطبة المعتمدة على النوع بالسوفتوير. المعلومه دى ح تكون علنيه.',
'email'                         => 'الإيميل',
'prefs-help-realname'           => 'الاسم الحقيقى اختيارى.
لو إخترت تكتبه, حيستعمل بس علشان شغلك يتنسب لإسمك.',
'prefs-help-email'              => 'الإيميل اختيارى, بس لازم علشان لو نسيت الپاسوورد.
ممكن بردو تختار انك تخلّى اليوزرات تبعتلك إيميل من صفحة اليوزر او المناقشه بتاعتك من غير ما تبقى شخصيتك معروفه.',
'prefs-help-email-required'     => 'عنوان الإيميل مطلوب.',
'prefs-info'                    => 'معلومات اساسيه',
'prefs-i18n'                    => 'التدويل',
'prefs-signature'               => 'الامضا',
'prefs-dateformat'              => 'فورمة التاريخ',
'prefs-timeoffset'              => 'أوفسيت الوقت',
'prefs-advancedediting'         => 'اختيارات متقدمه',
'prefs-advancedrc'              => 'اختيارات متقدمه',
'prefs-advancedrendering'       => 'اختيارات متقدمه',
'prefs-advancedsearchoptions'   => 'اختيارات متقدمه',
'prefs-advancedwatchlist'       => 'اختيارات متقدمه',
'prefs-displayrc'               => 'اختيارات العرض',
'prefs-displaysearchoptions'    => 'اختيارات العرض',
'prefs-displaywatchlist'        => 'اختيارات العرض',
'prefs-diffs'                   => 'التغيير',

# User rights
'userrights'                   => 'إدارة الحقوق بتاعة اليوزر',
'userrights-lookup-user'       => 'إدارة مجموعات اليوزر',
'userrights-user-editname'     => 'دخل اسم يوزر:',
'editusergroup'                => 'تعديل مجموعات اليوزر',
'editinguser'                  => "تغيير حقوق االيوزر بتاعة اليوزر'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'تعديل مجموعات اليوزر',
'saveusergroups'               => 'حفظ مجموعات اليوزر',
'userrights-groupsmember'      => 'عضو في:',
'userrights-groupsmember-auto' => 'عضو ضمنى فى :',
'userrights-groups-help'       => 'إنت ممكن تغير المجموعات اللى اليوزر دا عضو فيها .
* صندوق متعلم يعنى اليوزر دا عضو فى المجموعة دي.
* صندوق مش متعلم يعنى  اليوزر دا مش عضو فى المجموعة دي.
* علامة * يعنى انك مش ممكن تشيل المجموعات بعد ما تضيفها و العكس بالعكس.',
'userrights-reason'            => 'السبب:',
'userrights-no-interwiki'      => 'أنت  مش من حقك تعدل صلاحيات اليوزرز على الويكيات التانية.',
'userrights-nodatabase'        => 'قاعدة البيانات $1  مش موجودة أو مش محلية.',
'userrights-nologin'           => 'انت لازم [[Special:UserLogin|تسجيل الدخول]] بحساب  مدير لتعديل حقوق اليوزر.',
'userrights-notallowed'        => 'حسابك  ماعندوش  إذن لتعديل حقوق اليوزر.',
'userrights-changeable-col'    => 'المجموعات اللى تقدر تغييرها',
'userrights-unchangeable-col'  => 'المجموعات اللى مش ممكن انك تغيرها',

# Groups
'group'               => 'المجموعة:',
'group-user'          => 'يوزرز',
'group-autoconfirmed' => 'يوزرز متأكدين أوتوماتيكي',
'group-bot'           => 'بوتات',
'group-sysop'         => 'سيسوبات',
'group-bureaucrat'    => 'بيروقراطيين',
'group-suppress'      => 'أوفرسايت',
'group-all'           => '(الكل)',

'group-user-member'          => 'يوزر',
'group-autoconfirmed-member' => 'يوزر متأكد أوتوماتيكي',
'group-bot-member'           => 'بوت',
'group-sysop-member'         => 'سيسوب',
'group-bureaucrat-member'    => 'بيروقراط',
'group-suppress-member'      => 'أوفرسايت',

'grouppage-user'          => '{{ns:project}}:يوزرز',
'grouppage-autoconfirmed' => '{{ns:project}}:يوزرز متأكدين أوتوماتيكي',
'grouppage-bot'           => '{{ns:project}}:بوتات',
'grouppage-sysop'         => '{{ns:project}}:اداريين',
'grouppage-bureaucrat'    => '{{ns:project}}:بيروقراطيين',
'grouppage-suppress'      => '{{ns:project}}:أوفرسايت',

# Rights
'right-read'                  => 'قراية الصفحات',
'right-edit'                  => 'تعديل الصفحات',
'right-createpage'            => 'إبتدى الصفحات (اللى مالهاش صفحات نقاش)',
'right-createtalk'            => 'إبتدى صفحات النقاش',
'right-createaccount'         => 'افتح حسابات يوزر جديده',
'right-minoredit'             => 'التعليم على التعديلات كطفيفة',
'right-move'                  => 'انقل الصفحات',
'right-move-subpages'         => 'انقل الصفحات مع صفحاتها الفرعيه',
'right-move-rootuserpages'    => 'انقل صفحات جدر اليوزر',
'right-movefile'              => 'نقل الملفات',
'right-suppressredirect'      => 'ما تعملش تحويلة من الاسم القديم عند نقل صفحة',
'right-upload'                => 'حمل الملفات',
'right-reupload'              => 'الكتابة على ملف موجود',
'right-reupload-own'          => 'الكتابة على ملف موجود اتحمل ب اليوزر نفسه',
'right-reupload-shared'       => 'التحميل على الملفات فى مخزن الملفات المشترك  فى المكان دا بس',
'right-upload_by_url'         => 'تحميل ملف من عنوان مسار',
'right-purge'                 => 'تحديث كاش الموقع لصفحة من غير تأكيد',
'right-autoconfirmed'         => 'تعديل الصفحات  النص محميه',
'right-bot'                   => 'بتتعامل كأنها عملية أوتوماتيكية',
'right-nominornewtalk'        => 'ماتخليش التعديلات الطفيفة لصفحات النقاش تتطلع برواز الرسايل الجديدة',
'right-apihighlimits'         => 'استخدام حدود أعلى فى استعلامات API',
'right-writeapi'              => 'استخدام API الكتابة',
'right-delete'                => 'مسح الصفحات',
'right-bigdelete'             => 'مسح الصفحات اللى ليها تواريخ كبيرة',
'right-deleterevision'        => 'مسح وترجيع مراجعات معينة من الصفحات',
'right-deletedhistory'        => 'شوف مدخلات التاريخ الممسوحة، من غير النصوص اللى معاها',
'right-deletedtext'           => 'شوف النصوص الممسوحة والتغييرات بين المراجعات الممسوحة',
'right-browsearchive'         => 'التدوير فى الصفحات الممسوحة',
'right-undelete'              => 'استرجاع صفحة',
'right-suppressrevision'      => 'مراجعة واسترجاع المراجعات المستخبية عن الإداريين',
'right-suppressionlog'        => 'شوف السجلات الخاصة',
'right-block'                 => 'امنع اليوزرز التانيين من التعديل',
'right-blockemail'            => 'منع يوزر من إنه يبعت إيميل',
'right-hideuser'              => 'منع اسم يوزر، و خبيه عن الناس',
'right-ipblock-exempt'        => 'إتفادى عمليات منع الأيبي، المنع الأوتوماتيكى ومنع النطاق.',
'right-proxyunbannable'       => 'إتفادى عمليات المنع الأوتوماتيكية للبروكسيهات',
'right-protect'               => 'تغيير مستويات الحماية وتعديل الصفحات المحمية',
'right-editprotected'         => 'تعديل الصفحات المحمية (من غير الحماية المتضمنة)',
'right-editinterface'         => 'تعديل الواجهة بتاعة اليوزر',
'right-editusercssjs'         => 'تعديل ملفات CSS و JS لليوزرز التانيين',
'right-editusercss'           => 'تعديل ملفات CSS لليوزرز التانيين',
'right-edituserjs'            => 'تعديل ملفات JS لليوزرز التانيين',
'right-rollback'              => 'رجع بسرعه التعديلات بتاعة آخر يوزر عدل صفحة معينة',
'right-markbotedits'          => 'التعليم على التعديلات المترجعة كتعديلات بوت',
'right-noratelimit'           => 'مش متأثر بحدود المعدل',
'right-import'                => 'استيراد الصفحات من ويكيات تانيه',
'right-importupload'          => 'استيراد الصفحات من فايل متحمل',
'right-patrol'                => 'علم على تعديلات اليوزرز التانيين على انها متراجعة.',
'right-autopatrol'            => 'خلى التعديلات  بتاعتى متعلم عليها كأنها متراجعة أوتوماتيكي',
'right-patrolmarks'           => 'عرض علامات المراجعة فى اخر التعديلات',
'right-unwatchedpages'        => 'بين لستة الصفحات اللى مش متراقبة',
'right-trackback'             => 'تنفيذ تراكباك',
'right-mergehistory'          => 'ادمج تاريخ الصفحات',
'right-userrights'            => 'تعديل كل الحقوق بتاعة اليوزر',
'right-userrights-interwiki'  => 'تعديل صلاحيات اليوزر لليوزرز فى مواقع الويكى التانيه',
'right-siteadmin'             => 'قفل وفتح قاعدة البيانات',
'right-reset-passwords'       => 'تغيير الباوسورد بتاعة اليوزرات التانيين',
'right-override-export-depth' => 'تصدير الصفحات مع الصفحات الموصوله لحد عمق 5',
'right-sendemail'             => 'يبعت إيميل لليوزرز التانيين',

# User rights log
'rightslog'      => 'سجل صلاحيات اليوزرز',
'rightslogtext'  => 'ده سجل بالتغييرات ف صلاحيات اليوزرز .',
'rightslogentry' => 'غير صلاحيات $1 من $2 ل $3',
'rightsnone'     => '(فاضى)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'قراية الصفحه دى',
'action-edit'                 => 'تعديل هذه الصفحة',
'action-createpage'           => 'إنشاء الصفحات',
'action-createtalk'           => 'إنشاء صفحات النقاش',
'action-createaccount'        => 'إنشاء حساب اليوزر ده',
'action-minoredit'            => 'التعليم على هذا التعديل كطفيف',
'action-move'                 => 'نقل هذه الصفحة',
'action-move-subpages'        => 'نقل هذه الصفحة، وصفحاتها الفرعية',
'action-move-rootuserpages'   => 'انقل صفحات جدر اليوزر',
'action-movefile'             => 'انقل الملف ده',
'action-upload'               => 'رفع هذا الملف',
'action-reupload'             => 'الكتابة على هذا الملف الموجود',
'action-reupload-shared'      => 'الكتابة على هذا الملف فى مستودع مشترك',
'action-upload_by_url'        => 'رفع هذا الملف من عنوان مسار',
'action-writeapi'             => 'استخدام API الكتابة',
'action-delete'               => 'حذف هذه الصفحة',
'action-deleterevision'       => 'حذف هذه المراجعة',
'action-deletedhistory'       => 'رؤية تاريخ هذه الصفحة المحذوف',
'action-browsearchive'        => 'البحث فى الصفحات المحذوفة',
'action-undelete'             => 'استرجاع هذه الصفحة',
'action-suppressrevision'     => 'مراجعة واسترجاع هذه المراجعة المخفية',
'action-suppressionlog'       => 'رؤية هذا السجل الخاص',
'action-block'                => 'منع  اليوزر ده من التعديل',
'action-protect'              => 'تغيير مستويات الحماية لهذه الصفحة',
'action-import'               => 'استيراد هذه الصفحة من ويكى آخر',
'action-importupload'         => 'استيراد هذه الصفحة من ملف مرفوع',
'action-patrol'               => 'التعليم على تعديلات الآخرين كمراجعة',
'action-autopatrol'           => 'جعل تعديلك معلم عليه كمراجع',
'action-unwatchedpages'       => 'رؤية لستة الصفحات اللى مش متراقبة',
'action-trackback'            => 'تنفيذ تراكباك',
'action-mergehistory'         => 'دمج تاريخ هذه الصفحة',
'action-userrights'           => 'تعديل كل صلاحيات اليوزر',
'action-userrights-interwiki' => 'تعديل صلاحيات اليوزر لليوزرز فى الويكيات التانية',
'action-siteadmin'            => 'غلق أو رفع غلق قاعدة البيانات',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|تعديل|تعديلين|$1 تعديلات|$1 تعديل|$1 تعديل}}',
'recentchanges'                     => 'اخر التعديلات',
'recentchanges-legend'              => 'اختيارات اخر التعديلات',
'recentchangestext'                 => 'تابع آخر التغييرات فى الويكى على الصفحة دى.',
'recentchanges-feed-description'    => 'تابع اخر التعديلات للويكى ده عن طريق الفييد ده .',
'recentchanges-label-newpage'       => 'التعديل ده عمل صفحه جديده',
'recentchanges-label-minor'         => 'ده تعديل صغير',
'recentchanges-label-bot'           => 'التعديل ده عمله بوت',
'recentchanges-label-unpatrolled'   => 'التعديل ده مإتراجعش لسه',
'rcnote'                            => "فيه تحت {{PLURAL:$1|'''1''' تغيير|آخر '''$1''' تغيير}} فى آخر {{PLURAL:$2|يوم|'''$2''' يوم}}، بدءا من $5، $4.",
'rcnotefrom'                        => "دى التعديلات من '''$2''' (ل '''$1''' معروضه).",
'rclistfrom'                        => 'اظهر التعديلات بدايه من $1',
'rcshowhideminor'                   => '$1 تعديلات صغيره',
'rcshowhidebots'                    => '$1 البوتات',
'rcshowhideliu'                     => '$1 اليوزرز المتسجلين',
'rcshowhideanons'                   => '$1 اليوزرز المجهولين',
'rcshowhidepatr'                    => '$1 التعديلات المتراجعه',
'rcshowhidemine'                    => '$1 تعديلاتى',
'rclinks'                           => 'بيين اخر $1 تعديل فى اخر $2 يوم، $3',
'diff'                              => 'التغيير',
'hist'                              => 'تاريخ',
'hide'                              => 'تخبية',
'show'                              => 'عرض',
'minoreditletter'                   => 'ص',
'newpageletter'                     => 'ج',
'boteditletter'                     => 'ب',
'sectionlink'                       => '←',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1| يوزر مراقب|يوزر مراقب}}]',
'rc_categories'                     => 'حصر لتصنيفات (مفصولة برمز "|")',
'rc_categories_any'                 => 'أى',
'newsectionsummary'                 => '/* $1 */ قسم جديد',
'rc-enhanced-expand'                => 'عرض التفاصيل (يتطلب جافاسكريبت)',
'rc-enhanced-hide'                  => 'إخفاء التفاصيل',

# Recent changes linked
'recentchangeslinked'          => 'تعديلات ليها علاقه',
'recentchangeslinked-feed'     => 'تعديلات  ليها علاقه',
'recentchangeslinked-toolbox'  => 'تعديلات  ليها علاقه',
'recentchangeslinked-title'    => 'التعديلات المرتبطه  ب "$1"',
'recentchangeslinked-noresult' => 'مافيش تعديلات حصلت فى الصفحات اللى ليها وصلات هنا خلال الفترة المحدده.',
'recentchangeslinked-summary'  => "دى ليستة تغييرات اتعملت قريب فى صفح معمول ليها لينك من صفح مخصوصه (او لاعضاء فى تصنيف معين).
الصفح اللى فى [[Special:Watchlist|لستة الصفح اللى بتراقبها]] معروضه '''بالـbold'''",
'recentchangeslinked-page'     => 'اسم الصفحه :',
'recentchangeslinked-to'       => 'إظهارالتغييرات للصفحات الموصولة للصفحة اللى انت اديتها',

# Upload
'upload'                      => 'ارفع فايل (upload file)',
'uploadbtn'                   => 'حمل الملف',
'reuploaddesc'                => 'إلغى التحميل وارجع لاستمارة التحميل',
'upload-tryagain'             => 'نفذ وصف الملف المتعدل',
'uploadnologin'               => 'ما سجلتش الدخول',
'uploadnologintext'           => 'لازم تكون [[Special:UserLogin|مسجل الدخول]] علشان تقدر تحمل الملفات.',
'upload_directory_missing'    => 'مجلد التحميل($1) ضايع السيرفير وماقدرش يعمل واحد تاني.',
'upload_directory_read_only'  => 'مجلد التحميل ($1) مش ممكن الكتابة عليه بواسطة سيرڨر الويب.',
'uploaderror'                 => 'غلطه فى التحميل',
'uploadtext'                  => "استخدم الاستمارة علشان تحميل الملفات.
لعرض أو البحث ف الملفات المتحملة سابقا، راجع عمليات المسح [[Special:Log/delete|deletion log]] [[Special:FileList|لستة الملفات المتحملة]]، عمليات التحميل  موجودة فى [[Special:Log/upload|سجل التحميل]].

علشان تحط صورة فى صفحة، استخدم الوصلات فى الصيغ التالية:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' علشان استخدام النسخة الكاملة لملف
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|نص بديل]]</nowiki></tt>''' لاستخدام صورة عرضها 200 بكسل فى صندوق فى الجانب الأيسر مع 'نص بديل' كوصف
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' للوصل للملف مباشرة بدون عرض الملف.",
'upload-permitted'            => 'أنواع الملفات المسموحة: $1.',
'upload-preferred'            => 'أنواع الملفات المفضلة: $1.',
'upload-prohibited'           => 'أنواع الملفات الممنوعة: $1.',
'uploadlog'                   => 'سجل التحميل',
'uploadlogpage'               => 'سجل التحميل',
'uploadlogpagetext'           => 'تحت فية لستة بأحدث عمليات تحميل الملفات.
انظر [[Special:NewFiles|معرض الملفات الجديدة]] لعرض بصرى أكتر',
'filename'                    => 'اسم الملف',
'filedesc'                    => 'الخلاصة',
'fileuploadsummary'           => 'الخلاصة:',
'filereuploadsummary'         => 'تغييرات الملف:',
'filestatus'                  => 'حالة حقوق النسخ:',
'filesource'                  => 'مصدر:',
'uploadedfiles'               => 'الملفات المتحملة',
'ignorewarning'               => 'إتجاهل التحذير و احفظ الملف وخلاص',
'ignorewarnings'              => 'اتجاهل اى تحذير',
'minlength1'                  => 'أسامى الملفات لازم تكون متكونة من حرف واحد على الأقل.',
'illegalfilename'             => 'اسم الملف "$1" فيه علامات  مش مسموح بيها فى عناوين الصفحات.
لو سمحت تختار اسم تانى للمف و بعدين تحمله من اول و جديد.',
'badfilename'                 => ' اسم الملف إتغيير ل "$1".',
'filetype-badmime'            => 'مش مسموح تحميل ملفات من نوع "$1".',
'filetype-bad-ie-mime'        => ' المف دا ماتحملش لأن الإنترنت إكسبلورر ح يكتشفه ك"$1", وهوه نوع ملف ممنوع ومن المحتمل انه يكون خطر.',
'filetype-unwanted-type'      => "'''\".\$1\"''' هو مش نوع ملف مرغوب فيه.
{{PLURAL:\$3|نوع الملف المفضل هو|أنواع الملفات المفضلة هي}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' مش نوع ملف مسموح بيه.
{{PLURAL:\$3|نوع الملف المسموح بيه هو|أنواع الملفات المسموح بيها هي}} \$2.",
'filetype-missing'            => 'الملف مالوش امتدا(مثلا ".jpg").',
'large-file'                  => 'ينصح ان الملفات ماتكونش أكبر من $1؛ الملف ده حجمه $2.',
'largefileserver'             => 'حجم الملف ده أكبر من المسموح بيه على السيرڨر ده .',
'emptyfile'                   => 'الظاهر ان الملف اللى انت حملته طلع فاضي.
يمكن يكون السبب هوه كتابة الاسم غلط.
لو سمحت تتاكد من إنك فعلا عايز تحمل الملف دا..',
'fileexists'                  => "فيه  ملف موجود بالاسم ده  الرجاء التأكد من الملف ده باتباع الوصلة التالية '''<tt>[[:$1]]</tt>''' قبل ما تغيره.
[[$1|thumb]]",
'filepageexists'              => "صفحة الوصف بتاعة المف دا خلاص اتعملها انشاء فى '''<tt>[[:$1]]</tt>'''، بس مافيش ملف بالاسم دا دلوقتى.
الملخص اللى ح تكتبه  مش ح يظهر على صفحة الوصف.
علشان تخلى الملف يظهر هناك، ح تحتاج تعدله يدوي.
[[$1|thumb]]",
'fileexists-extension'        => "فى ملف موجود باسم قريب: [[$2|thumb]]
* اسم الملف اللى انت عايز تحمله: '''<tt>[[:$1]]</tt>'''
* اسم الملف الموجود: '''<tt>[[:$2]]</tt>'''
لو سمحت تختار اسم تاني.",
'fileexists-thumbnail-yes'    => "الظاهر ان الملف دا عبارة عن صورة متصغرة ''(تصغير)''. [[$1|thumb]]
لو سمحت تشيك على الملف '''<tt>[[:$1]]</tt>'''.
لو كان الملف هو نفس الصورة بالحجم الاصلي، ف مافيش داعى تحمله مرة تانية",
'file-thumbnail-no'           => "يبدأ الملف ب '''<tt>$1</tt>'''.
يبدو أن الملف مصتغر لحجم أعلى ''(تصغير)''.
إذا كان عندك الصورة فى درجة دقة كامله حملها، أو غير اسم الملف من فضلك.",
'fileexists-forbidden'        => 'فى ملف بنفس الاسم موجود, و ماينفعش يتكتب عليه.
لو انتا لسه عايز تحمل الملف بتاعك, لو سمحت ترجع لورا و تستعمل اسم جديد. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'فى ملف بنفس الاسم دا فى مخزن الملفات المشترك.
لو كنت لسه عايز ترفعه، لو سمحت ارجع وحمل الملف دا باسم جديد.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'الملف دا تكرار  {{PLURAL:$1|للملف|للملفات}} دي:',
'file-deleted-duplicate'      => 'فى ملف مطابق للملف دا ([[$1]]) اتمسح قبل كدا. انتا لازم تشيك على تاريخ المسح بتاع الملف دا قبل ما تحمله مرة تانية',
'uploadwarning'               => 'تحذير التحميل',
'uploadwarning-text'          => 'لو سمحت عدل وصف الملف اللى تحت وحاول تانى.',
'savefile'                    => 'حفظ الملف',
'uploadedimage'               => 'اتحمل "[[$1]]"',
'overwroteimage'              => 'اتحملت  نسخة جديدة من "[[$1]]"',
'uploaddisabled'              => 'التحميل متعطل',
'uploaddisabledtext'          => 'تحميل الملفات متعطل.',
'php-uploaddisabledtext'      => 'تحميل ملفات PHP متعطل. لو سمحت اتأكدن من إعدادات تحميل الملفات.',
'uploadscripted'              => 'الملف دا  فيه كود HTML أو كود تانى يمكن البراوزر يفهمه غلط.',
'uploadvirus'                 => 'الملف فيه فيروس! التفاصيل: $1',
'upload-source'               => 'الملف المصدر',
'sourcefilename'              => 'اسم الملف  بتاع المصدر:',
'sourceurl'                   => 'URL المصدر:',
'destfilename'                => 'اسم الملف المستهدف:',
'upload-maxfilesize'          => 'حجم الملف الأقصى: $1',
'upload-description'          => 'وصف الملف',
'upload-options'              => 'أوبشنات الرفع',
'watchthisupload'             => 'حط الملف دا تحت المراقبه',
'filewasdeleted'              => 'فيه فايل بنفس الاسم دا اتأپلود قبل كدا و بعدين اتمسح.
لازم تشيّك على $1 قبل ما تأپلود الفايل كمان مره.',
'upload-wasdeleted'           => "'''تحذير: انت بتحمل ملف اتمسح قبل كدا.'''

لازم تتاكد من انك عايز تستمر فى تحميل الملف دا.
سجل المسح بتاع الملف دا معروض هنا علشان تبص عليه:",
'filename-bad-prefix'         => "اسم الملف اللى بتحمله بيبتدى بـ'''\"\$1\"'''، واللى هو اسم مش وصفى بيتحط غالبا من الكاميرات الديجيتال اوتوماتيكي.
لو سمحت تختار اسم يكون بيوصف الملف بتاعك احسن من كدا.",
'filename-prefix-blacklist'   => ' #<!-- سيب السطر ده زى ما هوه --> <pre>
# الصيغة كدا:
#   * كل حاجة من أول علامة "#" لحد أخر السطر هى تعليق
#   * كل سطر مش فاضى هو بريفيكس لأسماء الملفات النمطية اللى بتحطها اوتوماتيكى  الكاميرات الديجيتال
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # بعض التليفونات المحمولة
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- سيب السطر ده زى ما هوه -->',
'upload-success-subj'         => 'التحميل ناجح',
'upload-warning-subj'         => 'تحذير التحميل',

'upload-proto-error'        => 'بروتوكول مش صحيح',
'upload-proto-error-text'   => 'االتحميل عن بعد لازمله يوأرإل بيبتدى بـ <code>http://</code> أو <code>ftp://</code>.',
'upload-file-error'         => 'غلط داخلي',
'upload-file-error-text'    => 'حصل غلط داخلى واحنا بنحاول نعمل ملف مؤقت على السيرفر.
لو سمحت اتصل [[Special:ListUsers/sysop|بسيسوب]].',
'upload-misc-error'         => 'غلط مش معروف فى التحميل',
'upload-misc-error-text'    => 'حصل غلط مش معروف وإنت بتحمل.
لو سمحت تتاكد أن اليوأرإل صح و ممكن تدخل عليه و بعدين حاول تاني.
إذا المشكلة تنتها موجودة،اتصل بإدارى نظام.',
'upload-too-many-redirects' => 'الـ URL فيه تحويلات اكتر من اللازم',
'upload-unknown-size'       => 'حجم مش معروف',
'upload-http-error'         => 'حصل غلط فى الـHTTB :$1',

# img_auth script messages
'img-auth-accessdenied' => 'الوصول مش مسموح بيه',
'img-auth-nopathinfo'   => 'PATH_INFO مش موجود.
الخادم بتاعك مش مضبوط علشان يدى المعلومه دى.
ممكن يكون CGI-based ومايقدرش يدعم img_auth.
بص على http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'المسار المطلوب مش فى مجلد الرفع المضبوط.',
'img-auth-badtitle'     => 'مش قادر يعمل عنوان صحيح من "$1".',
'img-auth-nologinnWL'   => 'إنت مش مسجل الدخول و"$1" مش فى القايمه البيضا.',
'img-auth-nofile'       => 'الملف "$1" مش موجود',
'img-auth-isdir'        => 'إنت بتحاول تدخل مجلد "$1".
دخول الملفات بس مسموح بيه.',
'img-auth-streaming'    => 'بيعرض "$1".',
'img-auth-public'       => 'وظيفة img_auth.php هى إنها تخرج ملفات من ويكى سرى.
الويكى ده مضبوط على إنه ويكى علني.
علشان أمن افضل، img_auth.php متعطله.',
'img-auth-noread'       => 'اليوزر معندوش صلاحية قرايه "$1".',

# HTTP errors
'http-invalid-url'      => 'مش صحيح URL: $1',
'http-invalid-scheme'   => 'URLاللى بنظام "$1" مش مدعومه.',
'http-request-error'    => 'طلب ال HTTP ما نفعش بسبب غلط مش معروف',
'http-read-error'       => 'فى غلط فى قراية ال HTTP',
'http-timed-out'        => 'طلب ال HTTP خلص وقته',
'http-curl-error'       => 'حصل غلط و احنا بنجيب الURL : $1',
'http-host-unreachable' => 'ما قدرناش نوصل لل URL.',
'http-bad-status'       => 'HTTP : حصلت مشكله وقت طلب ال $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'ما قدرناش نوصل لليو أر إل',
'upload-curl-error6-text'  => 'ما قدرناش نوصل لليوأرإل إللى انت عاوزه.
لو سمحت تشيك تانى إن اليوأرإل صح و إن السايت شغال.',
'upload-curl-error28'      => 'انتهاء مهلة التحميل',
'upload-curl-error28-text' => 'السايت خد وقت كبير علشان يستجيب.
لو سمحت اتأكد أن السايت شغال، واستنا شوية و بعدين حاول تاني.
يمكن تجرب تانى فى وقت مايكونش فيه زحمة.',

'license'            => 'ترخيص:',
'license-header'     => 'الترخيص',
'nolicense'          => 'مش متحدد',
'license-nopreview'  => '(البروفه مش متوفره)',
'upload_source_url'  => '  (اليوأرإل صحيح وممكن توصل ليه)',
'upload_source_file' => ' (ملف على الكمبيوتر بتاعك)',

# Special:ListFiles
'listfiles-summary'     => 'الصفحة المخصوصة دى بتعرض كل الملفات المتحملة.
اوتوماتيكى اخر الملفات اللى اتحملت ح تظهر فى اللستة من فوق.
لو دوست على راس العمود الترتيب ح يتغير.',
'listfiles_search_for'  => 'دور على اسم الميديا:',
'imgfile'               => 'ملف',
'listfiles'             => 'لستة الملفات',
'listfiles_date'        => 'تاريخ',
'listfiles_name'        => 'اسم',
'listfiles_user'        => 'يوزر',
'listfiles_size'        => 'حجم',
'listfiles_description' => 'وصف',
'listfiles_count'       => 'نسخ',

# File description page
'file-anchor-link'                  => 'فايل',
'filehist'                          => 'تاريخ الفايل',
'filehist-help'                     => 'اضغط على الساعه/التاريخ علشان تشوف الفايل زى ما كان فى  الوقت ده.',
'filehist-deleteall'                => 'امسح كله',
'filehist-deleteone'                => 'مسح',
'filehist-revert'                   => 'استرجع',
'filehist-current'                  => 'دلوقتي',
'filehist-datetime'                 => 'الساعه / التاريخ',
'filehist-thumb'                    => 'صورة صغيرة',
'filehist-thumbtext'                => 'تصغير للنسخة بتاريخ $1',
'filehist-nothumb'                  => 'لا تصغير',
'filehist-user'                     => 'يوزر',
'filehist-dimensions'               => 'ابعاد',
'filehist-filesize'                 => 'حجم الفايل',
'filehist-comment'                  => 'تعليق',
'filehist-missing'                  => 'ملف مش  موجود',
'imagelinks'                        => 'لينكات الفايل',
'linkstoimage'                      => '{{PLURAL:$1|الصفحة|ال$1 صفحة}} دى فيها وصله للفايل ده:',
'linkstoimage-more'                 => 'أكتر من $1 {{PLURAL:$1|صفحة تصل|صفحة تصل}} للملف ده .
القائمة التالية تعرض {{PLURAL:$1|أول وصلة صفحة|أول $1 وصلة صفحة}} للملف ده بس.
[[Special:WhatLinksHere/$2|قائمة كاملة]] متوفرة.',
'nolinkstoimage'                    => 'مافيش صفحات بتوصل للفايل ده.',
'morelinkstoimage'                  => 'عرض [[Special:WhatLinksHere/$1|لينكات اكتر]] للملف دا.',
'redirectstofile'                   => '{{PLURAL:$1| الملف|ال$1 ملف}} اللى جاى  بيحول للملف دا:',
'duplicatesoffile'                  => '{{PLURAL:$1| الملف|ال$1 ملف اللى بعده}} متكررين من الملف ده:
([[Special:FileDuplicateSearch/$2| تفاصيل اكتر]]):',
'sharedupload'                      => 'الملف دا من  $1 و ممكن تستعمله مشاريع تانيه.',
'sharedupload-desc-there'           => 'الملف دا من $1 و ممكن تستعمله المشاريع التانيه.
لو سمحت تشوف [$2 صفحة وصف الملف] لو عايز معلومات اكتر..',
'sharedupload-desc-here'            => 'الملف دا من $1 و ممكن تستعمله المشاريع التانيه.
الوصف بتاعه [$2 صفحة وصف الملف] هناك معروض تحت..',
'filepage-nofile'                   => 'ما فيش ملف موجود بالاسم دا.',
'filepage-nofile-link'              => 'ما فيش ملف موجود بالاسم دا ، بس انتا ممكن [$1 تحمله].',
'uploadnewversion-linktext'         => 'حمل نسخه جديده من الملف ده',
'shared-repo-from'                  => 'من $1',
'shared-repo'                       => 'مخزن مشترك',
'shared-repo-name-wikimediacommons' => 'ويكيميديا كومنز',

# File reversion
'filerevert'                => 'استرجع $1',
'filerevert-legend'         => 'استرجع الملف',
'filerevert-intro'          => "أنت بترجع '''[[Media:$1|$1]]''' [$4 للنسخةاللى بتاريخ $2، $3].",
'filerevert-comment'        => 'السبب:',
'filerevert-defaultcomment' => 'رجع النسخة اللى بتاريخ $2، $1',
'filerevert-submit'         => 'استرجع',
'filerevert-success'        => "'''[[Media:$1|$1]]''' اترجعت [$4 للنسخةاللى بتاريخ $2، $3].",
'filerevert-badversion'     => 'مافيش نسخة محلية قديمة  للملف دا بالتاريخ المتقدم',

# File deletion
'filedelete'                  => 'امسح $1',
'filedelete-legend'           => 'امسح الملف',
'filedelete-intro'            => "انتا على وشك تمسح الملف'''[[Media:$1|$1]]'''معا كل التاريخ بتاعه.",
'filedelete-intro-old'        => '<span class="plainlinks">أنت بتمسح نسخة \'\'\'[[Media:$1|$1]]\'\'\'اللى  بتاريخ [$4 $3، $2].</span>',
'filedelete-comment'          => 'السبب:',
'filedelete-submit'           => 'مسح',
'filedelete-success'          => "'''$1''' خلاص اتمسح.",
'filedelete-success-old'      => "نسخة الـ'''[[Media:$1|$1]]''' اللى بتاريخ $3، $2 اتمسحت.",
'filedelete-nofile'           => "'''$1''' مش موجود.",
'filedelete-nofile-old'       => "مافيش نسخة فى الارشيف من '''$1''' بالعناصر المتحددة.",
'filedelete-otherreason'      => 'سبب زيادة/تاني:',
'filedelete-reason-otherlist' => 'سبب تانى',
'filedelete-reason-dropdown'  => '*أسباب المسح الشايعة
** مخالفة حقوق النشر
** ملف متكرر',
'filedelete-edit-reasonlist'  => 'عدل أسباب المسح',
'filedelete-maintenance'      => 'مسح و استرجاع الملفات متعطل مؤقتا خلال الصيانه.',

# MIME search
'mimesearch'         => 'تدوير MIME',
'mimesearch-summary' => 'الصفحة دى مهمتها فلترة الملفات على حسب نوعها.
المدخل: نوع المحتوى/النوع الفرعي، يعنى مثلا
<tt>image/jpeg</tt>.',
'mimetype'           => 'نوع الملف:',
'download'           => 'تنزيل',

# Unwatched pages
'unwatchedpages' => 'صفحات مش متراقبة',

# List redirects
'listredirects' => 'عرض التحويلات',

# Unused templates
'unusedtemplates'     => 'قوالب مش مستعمله',
'unusedtemplatestext' => 'الصفحة دى فيها لستة بالصفحات من نطاق {{ns:template}} و اللى مش مستعملة فى صفحات تانية
افتكر قبل ما تمسحها تشوف لو فى وصلات تانية للقوالب دي',
'unusedtemplateswlh'  => 'وصلات  تانيه',

# Random page
'randompage'         => 'صفحة عشوائيه',
'randompage-nopages' => 'مافيش صفحات فى النطاق {{PLURAL:$2|namespace|namespaces}}: $1.',

# Random redirect
'randomredirect'         => 'تحويله عشوائيه',
'randomredirect-nopages' => 'مافيش تحويلات فى النطاق"$1".',

# Statistics
'statistics'                   => 'احصائيات',
'statistics-header-pages'      => 'إحصاءات الصفحات',
'statistics-header-edits'      => 'إحصاءات التعديلات',
'statistics-header-views'      => 'إحصاءات المشاهدة',
'statistics-header-users'      => 'الاحصاءات بتاعة اليوزر',
'statistics-header-hooks'      => 'احصائيات تانيه',
'statistics-articles'          => 'صفحات المحتوى',
'statistics-pages'             => 'الصفحات',
'statistics-pages-desc'        => 'كل الصفحات فى الويكى، بما فيها صفحات النقاش، التحويلات، إلى آخره.',
'statistics-files'             => 'الملفات المتحملة',
'statistics-edits'             => 'تعديلات الصفحات من بداية {{SITENAME}}',
'statistics-edits-average'     => 'متوسط التعديلات لكل صفحة',
'statistics-views-total'       => 'إجمالى المشاهدات',
'statistics-views-peredit'     => 'المشاهدات لكل تعديل',
'statistics-users'             => '[[Special:ListUsers|يوزرز]] مسجلين',
'statistics-users-active'      => 'يوزرز نشطين',
'statistics-users-active-desc' => 'اليوزرز اللى نفذو عمليه فى الـ {{PLURAL:$1|يوم|$1 ايام}} اللى فاتو',
'statistics-mostpopular'       => 'اكتر صفحات اتشافت',

'disambiguations'      => 'صفحات التوضيح',
'disambiguationspage'  => 'Template:توضيح',
'disambiguations-text' => "الصفحات دى بتوصل لـ '''صفحة توضيح'''.
المفروض على العكس انهم يوصلو ل للصفحات المناسبة. <br />
أى صفحة بتتعامل على انها صفحة توضيح إذا كانت بتستعمل قالب موجود فى [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'تحويلات مزدوجه',
'doubleredirectstext'        => 'الصفحة دى فيها لستة الصفحات اللى فيها تحويلة لصفحة تانية فيها تحويلة.
كل سطر فى اللستة دى  فيه لينك للتحويلة الأولانية والتانية و كمان للصفحة بتاعة التحويلة التانية و اللى غالبا هى الصفحة الاصلية اللى المفروض التحويلة الاولانية توصل ليها.
<del>Crossed out</del> اتحلت.',
'double-redirect-fixed-move' => '[[$1]] اتنقلت، هى دلوقتى تحويله ل [[$2]]',
'double-redirect-fixer'      => 'مصلح التحويل',

'brokenredirects'        => 'تحويلات مكسوره',
'brokenredirectstext'    => 'التحويلات دى بتودى لصفحات  مالهاش وجود:',
'brokenredirects-edit'   => 'تحرير',
'brokenredirects-delete' => 'مسح',

'withoutinterwiki'         => 'صفحات من غير وصلات للغات تانيه',
'withoutinterwiki-summary' => 'الصفحات دى  مالهاش لينكات لنسخ بلغات تانية:',
'withoutinterwiki-legend'  => 'بريفيكس',
'withoutinterwiki-submit'  => 'عرض',

'fewestrevisions' => 'اقل المقالات فى عدد التعديلات',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|تصنيف واحد|تصنيفين|تصنيفات|تصنيف}}',
'nlinks'                  => '{{PLURAL:$1|وصله واحده|وصلتين|$1 وصلات|$1 وصله}}',
'nmembers'                => '$1 {{PLURAL:$1|عضو|اعضاء}}',
'nrevisions'              => '{{PLURAL:$1|تعديل وحيد|تعديلين|$1 تعديلات|$1 تعديل|$1}}',
'nviews'                  => '{{PLURAL:$1|مشاهدة واحدة|مشاهدتين|$1 مشاهدات|$1 مشاهدة}}',
'specialpage-empty'       => 'مافيش نتايج للتقرير دا.',
'lonelypages'             => 'صفحات يتيمه',
'lonelypagestext'         => 'الصفحات دى ماعندهاش لينكات أو تضمينات من الصفحات التانية فى {{SITENAME}}.',
'uncategorizedpages'      => 'صفحات مش متصنفه',
'uncategorizedcategories' => 'تصنيفات مش متصنفه',
'uncategorizedimages'     => 'ملفات مش متصنفه',
'uncategorizedtemplates'  => 'قوالب مش متصنفه',
'unusedcategories'        => 'تصانيف مش  مستعمله',
'unusedimages'            => 'صور مش مستعمله',
'popularpages'            => 'صفحات مشهورة',
'wantedcategories'        => 'تصانيف مطلوبه',
'wantedpages'             => 'صفحات مطلوبه',
'wantedpages-badtitle'    => 'عنوان مش صحيح فى مجموعة النتايج: $1',
'wantedfiles'             => 'ملفات مطلوبة',
'wantedtemplates'         => 'قوالب متعازة',
'mostlinked'              => 'اكتر صفحات موصولة بصفحات تانيه',
'mostlinkedcategories'    => 'اكتر التصانيف فى عدد الارتباطات',
'mostlinkedtemplates'     => 'اكتر القوالب فى عدد الوصلات',
'mostcategories'          => 'اكتر الصفحات فى عدد التصانيف',
'mostimages'              => 'اكتر الملفات فى عدد الارتباطات',
'mostrevisions'           => 'اكتر المقالات فى عدد التعديلات',
'prefixindex'             => 'كل الصفحات اللى بالبرفيكس',
'shortpages'              => 'صفحات قصيره',
'longpages'               => 'صفحات طويله',
'deadendpages'            => 'صفحات ما بتوصلش  لحاجه',
'deadendpagestext'        => 'الصفحات دى مابتوصلش  لصفحات تانية فى {{SITENAME}}.',
'protectedpages'          => 'صفحات محميه',
'protectedpages-indef'    => 'عمليات الحماية اللى مش متحددة بس',
'protectedpages-cascade'  => 'الحماية المتضمنة بس',
'protectedpagestext'      => 'الصفحات دى محمية من النقل أو التعديل',
'protectedpagesempty'     => 'مافيش  صفحات محمية دلوقتى  على حسب المحددات دي.',
'protectedtitles'         => 'عناوين محمية',
'protectedtitlestext'     => 'العناوين دى محمية ضد الإنشاء',
'protectedtitlesempty'    => 'مافيش عناوين محمية دلوقتى على حسب المحددات دي.',
'listusers'               => 'لستة الأعضاء',
'listusers-editsonly'     => 'عرض اليوزرز اللى قاموا بتعديلات فقط',
'listusers-creationsort'  => 'رتب على حسب تاريخ الإنشاء',
'usereditcount'           => '$1 {{PLURAL:$1|تعديل|تعديل}}',
'usercreated'             => 'اتعملت فى $1 الساعه $2',
'newpages'                => 'صفحات جديده',
'newpages-username'       => 'اسم اليوزر:',
'ancientpages'            => 'اقدم الصفحات',
'move'                    => 'انقل',
'movethispage'            => 'انقل الصفحه دى',
'unusedimagestext'        => 'الملفات ديه موجوده لكن مش موجودين في أى صفحه.
لو سمحت تاخد بالك إن المواقع التانية ممكن تكون بتوصل لملف عن طريق يوأرإل مباشر، و علشان كدا ممكن يكون لسة معروض هنا مع إنه بيستعمل.',
'unusedcategoriestext'    => 'التصنيفات دى موجودة مع إنها ما فيهاش اى صفحات او تصنيفات تانية.',
'notargettitle'           => 'مافيش هدف',
'notargettext'            => 'انت ما حددتش الصفحة أو اليوزر المستهدف لعمل العملية دي.',
'nopagetitle'             => 'مافيش صفحة هدف بالاسم ده',
'nopagetext'              => 'صفحة الهدف اللى انت طالبها مش موجودة.',
'pager-newer-n'           => '{{PLURAL:$1|أجدد 1|أجدد $1}}',
'pager-older-n'           => '{{PLURAL:$1|أقدم 1|أقدم $1}}',
'suppress'                => 'أوفرسايت',

# Book sources
'booksources'               => 'مصادر من كتب',
'booksources-search-legend' => 'التدوير على مصادر الكتب',
'booksources-go'            => 'روح',
'booksources-text'          => 'فى تحت لستة بوصلات لمواقع تانية بتبيع الكتب الجديدة والمستعملة، كمان ممكن تلاقى معلومات إضافية عن الكتب اللى يتدور عليها :',
'booksources-invalid-isbn'  => 'رقم الـ ISBN اللى كتبته شكله مش صحيح؛ اتإكد من الغلطات بتاعة النسخ من المصدر الاصلى.',

# Special:Log
'specialloguserlabel'  => 'اليوزر:',
'speciallogtitlelabel' => 'العنوان:',
'log'                  => 'سجلات',
'all-logs-page'        => 'كل السجلات العامه',
'alllogstext'          => 'عرض شامل لكل السجلات الموجودة فى {{SITENAME}}.
ممكن تخلى اللستة متحددة اكتر لو تختار نوع العملية، أو اسم اليوزر (حساس لحالة الحروف)، أو الصفحة المتأثرة (برضه حساس لحالة الحروف).',
'logempty'             => 'مافيش  سجلات مطابقة فى السجل.',
'log-title-wildcard'   => 'التدوير على عناوين تبتدى بالنص دا',

# Special:AllPages
'allpages'          => 'كل الصفحات',
'alphaindexline'    => '$1 ل $2',
'nextpage'          => 'الصفحه اللى بعد كده ($1)',
'prevpage'          => 'الصفحه اللى قبل كده ($1)',
'allpagesfrom'      => 'عرض الصفحات بدايه من:',
'allpagesto'        => 'اعرض الصفحات اللى بتنتهى عند:',
'allarticles'       => 'كل المقالات',
'allinnamespace'    => 'كل الصفحات (فى نطاق $1)',
'allnotinnamespace' => 'كل الصفحات (مش فى نطاق $1)',
'allpagesprev'      => 'اللى فلت',
'allpagesnext'      => 'اللى بعد كده',
'allpagessubmit'    => 'روح',
'allpagesprefix'    => 'عرض الصفحات  اللى تبتدى بـ:',
'allpagesbadtitle'  => 'العنوان االلى اديته للصفحة مش نافع أو فيه لغات تانية أو بريفيكس إنترويكي.
يمكن فيه حروف ماينفعش تنكتب بيها العناوين.',
'allpages-bad-ns'   => '{{SITENAME}} مافيهاش نطاق "$1".',

# Special:Categories
'categories'                    => 'تصانيف',
'categoriespagetext'            => '{{PLURAL:$1|التصنيف دا بيحتوى على|التصنيفات دى بتحتوى على}} صفحات او ميديا.
[[Special:UnusedCategories|التصنيفات اللى مش مستعمله]] مش معروضه  هنا.
شوف كمان [[Special:WantedCategories|التصنيفات المتعازه]].',
'categoriesfrom'                => 'اعرض التصانيف من أول:',
'special-categories-sort-count' => 'رتب بالعدد',
'special-categories-sort-abc'   => 'ترتيب ابجدي',

# Special:DeletedContributions
'deletedcontributions'             => 'تعديلات اليوزر الممسوحة',
'deletedcontributions-title'       => 'تعديلات اليوزر الممسوحة',
'sp-deletedcontributions-contribs' => 'المساهمات',

# Special:LinkSearch
'linksearch'       => 'لينكات خارجيه',
'linksearch-pat'   => 'نظام التدوير:',
'linksearch-ns'    => 'النطاق:',
'linksearch-ok'    => 'تدوير',
'linksearch-text'  => 'الكروت الخاصة زى "*.wikipedia.org" ممكن تستعمل.<br />
البروتوكولات المدعومة: <tt>$1</tt>',
'linksearch-line'  => '$1 موصوله من $2',
'linksearch-error' => 'الكروت الخاصة ممكن تبان بس  فى بداية اسم المضيف',

# Special:ListUsers
'listusersfrom'      => 'عرض اليوزرز من أول:',
'listusers-submit'   => 'عرض',
'listusers-noresult' => 'ما فى ش يوزر',
'listusers-blocked'  => '(ممنوع)',

# Special:ActiveUsers
'activeusers'            => 'ليستة اليوزرات اللى ليهم نشاط',
'activeusers-intro'      => 'دى قايمه اليوزرات اللى عملوا نشاط فى آخر $1 {{PLURAL:$1|يوم|يوم}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|تعديل|تعديل}} فى آخر {{PLURAL:$3|يوم|$3 يوم}}',
'activeusers-from'       => 'عرض اليوزرات بداية من:',
'activeusers-hidebots'   => 'خبى البوتات',
'activeusers-hidesysops' => 'خبى السيسوبات',
'activeusers-noresult'   => 'مالقيناش اى يوزر',

# Special:Log/newusers
'newuserlogpage'              => 'سجل اليوزرز الجداد',
'newuserlogpagetext'          => 'دا سجل لليوزرز الجداد',
'newuserlog-byemail'          => 'الباسورد اتبعتت بالايميل',
'newuserlog-create-entry'     => 'يوزر جديد',
'newuserlog-create2-entry'    => 'الحساب الجديد المعمول $1',
'newuserlog-autocreate-entry' => 'الحساب اتفتح اوتوماتيكي',

# Special:ListGroupRights
'listgrouprights'                      => 'حقوق مجموعات اليوزرز',
'listgrouprights-summary'              => 'دى لستة بمجموعات اليوزرز المتعرفة فى الويكى دا، بالحقوق اللى معاهم.
ممكن تلاقى معلومات زيادة عن الحقوق بتاعة كل واحد  [[{{MediaWiki:Listgrouprights-helppage}}|هنا]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">حق ممنوح</span>
* <span class="listgrouprights-revoked">حق متصادر</span>',
'listgrouprights-group'                => 'المجموعة',
'listgrouprights-rights'               => 'الحقوق',
'listgrouprights-helppage'             => 'Help: حقوق المجموعات',
'listgrouprights-members'              => '(لستة الأعضاء)',
'listgrouprights-addgroup'             => 'ممكن تضيف {{PLURAL:$2|المجموعة|المجموعات}}: $1',
'listgrouprights-removegroup'          => 'ممكن تشيل {{PLURAL:$2|المجموعة|المجموعات}}: $1',
'listgrouprights-addgroup-all'         => 'ممكن تضيف كل المجموعات',
'listgrouprights-removegroup-all'      => 'ممكن تشيل كل المجموعات',
'listgrouprights-addgroup-self'        => 'اضافة {{PLURAL:$2|مجموعه|مجموعات}} لحسابى الخاص: $1',
'listgrouprights-removegroup-self'     => 'مسح {{PLURAL:$2|مجموعه|مجموعات}} من حسابى الخاص: $1',
'listgrouprights-addgroup-self-all'    => 'اضافة كل المجموعات للحساب بتاعى',
'listgrouprights-removegroup-self-all' => 'مسح كل المجموعات من الحساب بتاعى',

# E-mail user
'mailnologin'      => 'مافيش عنوان نبعت عليه',
'mailnologintext'  => 'لازم تعمل [[Special:UserLogin|تسجيل الدخول]] و تدخل ايميل صحيح فى صفحة [[Special:Preferences|التفضيلات]] علشان تقدر تبعت ايميلات لليوزرز التانيين.',
'emailuser'        => 'ابعت ايميل لليوزر دا',
'emailpage'        => 'ابعت ايميل لليوزر ده',
'emailpagetext'    => 'ممكن تستعمل الاستمارة اللى تحت دى عشان تيعت ايميل لليوزر دا.
عنوان الايميل اللى كتبته فى [[Special:Preferences|التفضيلات بتاعتك]] ح يظهر على انه عنوان الاستمارة و بكدة اللى حيستقبله ح يقدر يرد على الايميل.',
'usermailererror'  => 'البريد رجع غلط:',
'defemailsubject'  => 'إيميل من {{SITENAME}}',
'noemailtitle'     => 'مافيش  عنوان ايميل',
'noemailtext'      => 'اليوزر دا ما كتبش االايميل بتاعه صح .',
'nowikiemailtitle' => 'الايميلات مش مسموح بيها',
'nowikiemailtext'  => 'اليوزر دا اختار انه ما يستقبلش ايميلات من اليوزرز التانيين.',
'email-legend'     => 'ابعت إيميل ليوزر {{SITENAME}} تانى',
'emailfrom'        => 'من:',
'emailto'          => 'لـ:',
'emailsubject'     => 'الموضوع:',
'emailmessage'     => 'الرساله:',
'emailsend'        => 'إبعت',
'emailccme'        => 'ابعتلى نسخة من الايميل اللى بعته.',
'emailccsubject'   => 'نسخة من رسالتك ل $1: $2',
'emailsent'        => 'الإيميل اتبعت',
'emailsenttext'    => 'الايميل بتاعك اتبعت خلاص.',
'emailuserfooter'  => 'الايميل دا بعته $1 لـ $2 عن طريق خاصية "مراسلة اليوزر" فى {{SITENAME}}.',

# Watchlist
'watchlist'            => 'لستة الصفحات اللى باراقبها',
'mywatchlist'          => 'لستة  الصفح اللى باراقبها',
'nowatchlist'          => 'مافيش حاجة فى لستة مراقبتك.',
'watchlistanontext'    => 'لو سمحت $1 لعرض أو تعديل الصفحات فى لستة مراقبتك.',
'watchnologin'         => 'مش متسجل',
'watchnologintext'     => 'لازم تكون [[Special:UserLogin|مسجل الدخول]] علشان تعدل لستة المراقبة بتاعتك.',
'addedwatch'           => 'تمت الاضافه للستة الصفحات اللى بتراقبها',
'addedwatchtext'       => 'تمت إضافة الصفحه  "$1"  [[Special:Watchlist|للستة الصفحات اللى بتراقبها]].
التعديلات اللى بعد كده ها تتحط على الصفحه دى، وصفحة المناقش الخاصه بها ها تتحط هناك. واسم الصفحة هايظهر  بخط <b>عريض</b> فى صفحة [[Special:RecentChanges|أحدث التعديلات]] لتسهيل تحديدها واكتشافها.',
'removedwatch'         => 'اتشالت  من لستة الصفحات اللى بتراقبها',
'removedwatchtext'     => 'الصفحه دى اتشالت "[[:$1]]" من [[Special:Watchlist|لستة الصفحات اللى بتراقبها]].',
'watch'                => 'راقب',
'watchthispage'        => 'راقب الصفحه دى',
'unwatch'              => 'بطل مراقبه',
'unwatchthispage'      => 'اتوقف عن المراقبة',
'notanarticle'         => 'دى مش صفحة بتاعة محتوى',
'notvisiblerev'        => 'النسحة اتمسحت',
'watchnochange'        => 'مافيش ولا صفحة اتعدلت فى لستة مراقبتك فى الفترة الزمنية اللى حددتها.',
'watchlist-details'    => '{{PLURAL:$1|$1 صفحه|$1 صفحه}} فى قايمه مراقبتك، بدون عد صفحات المناقشه.',
'wlheader-enotif'      => '*خاصية الاعلام بالايميل متفعلة',
'wlheader-showupdated' => "* الصفحات اللى اتغيرت  بعد زيارتك ليها اخر مرة معروضة بالخط '''العريض'''",
'watchmethod-recent'   => 'التشييك على التعديلات الاخيرة للصفحات المتراقبة',
'watchmethod-list'     => 'التشييك فى الصفحات المتراقبة على التعديلات الاخيرة',
'watchlistcontains'    => 'لستة المراقبة بتاعتك فيها $1 {{PLURAL:$1|صفحة|صفحات}}.',
'iteminvalidname'      => "مشكلة فى '$1'، اسم مش صحيح...",
'wlnote'               => "تحت فى {{PLURAL:$1|آخر تغيير|آخر '''$1''' تغيير}} فى آخر {{PLURAL:$2|ساعة|'''$2''' ساعة}}.",
'wlshowlast'           => 'عرض اخر $1 ساعات $2 ايام $3',
'watchlist-options'    => 'اختيارات قايمة المراقبة',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'بيراقب...',
'unwatching' => 'بيبطل مراقبه...',

'enotif_mailer'                => 'نظام {{SITENAME}} البريدى للإخطارات',
'enotif_reset'                 => 'علم على كل الصفحات كأنك خلاص زرتها',
'enotif_newpagetext'           => 'دى صفحه جديده.',
'enotif_impersonal_salutation' => 'يوزر {{SITENAME}}',
'changed'                      => 'اتغيرت',
'created'                      => 'إتنشأت',
'enotif_subject'               => 'صفحة {{SITENAME}} $PAGETITLE تم $CHANGEDORCREATED بواسطة $PAGEEDITOR',
'enotif_lastvisited'           => 'شوف $1 لمراجعة كل التغييرات اللى حصلت من أخر زيارة ليك.',
'enotif_lastdiff'              => 'شوف $1 علشان تبص على التغيير دا.',
'enotif_anon_editor'           => 'يوزر مش معروف $1',
'enotif_body'                  => 'عزيزى $WATCHINGUSERNAME,


الصفحه {{SITENAME}} $PAGETITLE اتغيّرت $CHANGEDORCREATED فى $PAGEEDITDATE من $PAGEEDITOR, شوف $PAGETITLE_URL علشان تعرف مراجعة دلوقتى.

$NEWPAGE

ملخص تعديل المحرر: $PAGESUMMARY $PAGEMINOREDIT

اتصل بالمحرر:
ايميل: $PAGEEDITOR_EMAIL
صفحة اليوزر: $PAGEEDITOR_WIKI

مش ح يكون فيه تنبيهات تانيه فى حالة لو حصل تغييرات اكتر الا اذا زورت الصفحه دى.
تقدر بردو ترجّع اعلمة التنبيه ع الزيرو لكل الصفح المتراقبه بتاعتك على ليستة المراقبه.


             نظام التنبيه {{SITENAME}} السهل

--
علشان تغيّر ليستة المراقبه بتاعتك, زور
{{fullurl:{{#special:Watchlist}}/edit}}

علشان تمسح الصفحه من على ليستة مراقبتك, زور
$UNWATCHURL

الfeedback و مساعده اكتر:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'امسح الصفحه',
'confirm'                => 'أكد',
'excontent'              => "المحتوى كان: '$1'",
'excontentauthor'        => "المحتوى كان: '$1' (والمساهم الوحيد كان '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "المحتوى قبل التفضيه كان: '$1'",
'exblank'                => 'الصفحه كانت فاضيه',
'delete-confirm'         => 'مسح"$1"',
'delete-legend'          => 'مسح',
'historywarning'         => "'''تحذير:''' الصفحه اللى ها  تمسحها ليها تاريخ فيه تقريبا $1 {{PLURAL:$1|مراجعة|مراجعة}}:",
'confirmdeletetext'      => 'انت على وشك انك تمسح صفحه أو صوره و كل تاريخها.
من فضلك  اتأكد انك عايز المسح وبأنك فاهم نتايج  العمليه  دى. عمليات الحذف لازم تتم بناء على [[{{MediaWiki:Policy-url}}|القواعد المتفق عليها]].',
'actioncomplete'         => 'العمليه خلصت',
'actionfailed'           => 'الفعل فشل',
'deletedtext'            => '"<nowiki>$1</nowiki>" اتمسحت.
بص على $2 علشان تشوف سجل آخر عمليات المسح.',
'deletedarticle'         => 'اتمسحت "[[$1]]"',
'suppressedarticle'      => 'خببى "[[$1]]"',
'dellogpage'             => 'سجل المسح',
'dellogpagetext'         => 'لسته بأحدث عمليات المسح.',
'deletionlog'            => 'سجل المسح',
'reverted'               => 'استرجع لنسخة أقدم',
'deletecomment'          => 'السبب:',
'deleteotherreason'      => 'سبب تانى/اضافي:',
'deletereasonotherlist'  => 'سبب تانى',
'deletereason-dropdown'  => '*أسباب المسح المشهوره
** طلب المؤلف
** مخالفة قواعد حقوق النشر
** التخريب',
'delete-edit-reasonlist' => 'عدل اسباب المسح',
'delete-toobig'          => 'الصفحه دى  ليها تاريخ تعديل كبير، أكتر من $1 {{PLURAL:$1|مراجعة|مراجعة}}.
مسح الصفحات اللى زى دى تم تحديده لمنع الاضطراب العرضى فى {{SITENAME}}.',
'delete-warning-toobig'  => 'الصفحة دى ليها تاريخ تعديل كبير، أكتر من $1 {{PLURAL:$1|مراجعة|مراجعة}}.
ممكن مسحها يعمل اضطراب  فى عمليات قاعدة البيانات فى {{SITENAME}}؛
استمر بس خد بالك.',

# Rollback
'rollback'          => 'إرجع فى التعديلات',
'rollback_short'    => 'إرجع لـ ورا',
'rollbacklink'      => 'ترجيع',
'rollbackfailed'    => 'الترجيع ما نفعش',
'cantrollback'      => 'ماقدرناش نرجع فى التعديل؛ آخر مساهم هوه الوحيد اللى ساهم فى الصفحة دي.',
'alreadyrolled'     => 'ماقدرناش نرجع التعديل الاخير لـ [[:$1]] بتاع [[User:$2|$2]] ([[User talk:$2|نقاش]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
فى واحد تانى عدل الصفحه او عمل استرجاع قبل كده.

اخر تعديل للصفحه دى عمله [[User:$3|$3]] ([[User talk:$3|نقاش]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "ملخص التعديل كان: \"''\$1''\".",
'revertpage'        => 'استرجع التعديلات بتاعة [[Special:Contributions/$2|$2]] ([[User talk:$2|مناقشة]]) لآخر نسخة بتاعة [[User:$1|$1]]',
'revertpage-nouser' => 'استرجع التعديلات بتاعه (اسم اليوزر اتمسح) لغايه آخر نسخه بتاعه [[User:$1|$1]]',
'rollback-success'  => 'استرجع تعديلات $1؛
استرجع لآخر نسخة بواسطة $2.',

# Edit tokens
'sessionfailure' => 'الظاهر انه فى مشكلة فى جلسة دخولك دى ؛
وعلشان كدا العملية دى اتلغت كإجراء احترازى ضد الاختراق.
لو سمحت دوس على زرار"رجوع" علشان تحمل الصفحة اللى جيت منها مرة تانية، و بعدين حاول تاني.',

# Protect
'protectlogpage'              => 'سجل الحمايه',
'protectlogtext'              => 'تحت فى لستة بالصفحات اللى اعملها حماية او اتشالت منها الحماية.
شوف [[Special:ProtectedPages|لستة الصفحات المحمية]] لستة بعمليات حماية الصفحات الشغالة دلوقتي.',
'protectedarticle'            => 'حمى "[[$1]]"',
'modifiedarticleprotection'   => 'غير مستوى الحماية ل"[[$1]]"',
'unprotectedarticle'          => 'شال حماية [[$1]]',
'movedarticleprotection'      => 'نقل إعدادات الحماية من "[[$2]]" ل "[[$1]]"',
'protect-title'               => 'غير مستوى الحماية ل"$1"',
'prot_1movedto2'              => '[[$1]] اتنقلت ل [[$2]]',
'protect-legend'              => 'تأكيد الحماية',
'protectcomment'              => 'السبب:',
'protectexpiry'               => 'تنتهى فى:',
'protect_expiry_invalid'      => 'وقت الانتهاء مش صحيح.',
'protect_expiry_old'          => 'وقت انتهاء المنع قديم.',
'protect-unchain-permissions' => 'شيل حماية أوبشنات الحمايه التانيه',
'protect-text'                => "ممكن هنا تعرض و تغير مستوى الحمايه للصفحه '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "انت مش ممكن تغير مستويات الحماية وأنت ممنوع.
الإعدادات بتاعة الصفحة '''$1''' دلوقتى هي:",
'protect-locked-dblock'       => "ما ينفعش تغير مستويات الحماية بسبب قفل قاعدة البيانات دلوقتي.
الإعدادات بتاعة الصفحة '''$1''' دلوقتى هي:",
'protect-locked-access'       => "حسابك ما لوش  صلاحية تغيير مستوى حماية الصفحه.
الاعدادات الحالية للصفحه '''$1''' هى:",
'protect-cascadeon'           => 'الصفحه دى محميه لكونها متضمنه فى {{PLURAL:$1|الصفحه|الصفحات}} دى، واللى  فيها اختيار حماية الصفحات المتضمنه شغال.
ممكن تغير مستوى حماية الصفحه دى بدون التأثير على حماية الصفحات المتضمنه التانيه.',
'protect-default'             => 'السماح لكل اليوزرات',
'protect-fallback'            => 'محتاج  اذن "$1"',
'protect-level-autoconfirmed' => 'منع اليوزرات الجداد و اللى مش متسجلين',
'protect-level-sysop'         => 'سيسوب بس',
'protect-summary-cascade'     => 'متضمنه',
'protect-expiring'            => 'تنتهى فى $1 (UTC)',
'protect-expiry-indefinite'   => 'مش محدد',
'protect-cascade'             => 'احمى الصفحات المتضمنه فى الصفحه دى (حمايه مضمنه)',
'protect-cantedit'            => 'مش ممكن تغير مستويات الحمايه للصفحه دى، لانك ماعندكش صلاحية تعديلها.',
'protect-othertime'           => 'وقت آخر:',
'protect-othertime-op'        => 'وقت آخر',
'protect-existing-expiry'     => 'تاريخ الانتهاء الموجود: $3، $2',
'protect-otherreason'         => 'سبب آخر/إضافى:',
'protect-otherreason-op'      => 'سبب تانى',
'protect-dropdown'            => '*أسباب الحماية الشايعة
** تخريب شديد
** سبام شديد
** حرب تحرير بتعطل العمل المنتج
** صفحة زوارها كتير',
'protect-edit-reasonlist'     => 'عدل أسباب الحماية',
'protect-expiry-options'      => '1 ساعة:1 hour,1 يوم:1 day,1 أسبوع:1 week,2 أسبوع:2 weeks,1 شهر:1 month,3 شهر:3 months,6 شهر:6 months,1 سنة:1 year,لا نهائى:infinite',
'restriction-type'            => 'سماح:',
'restriction-level'           => 'مستوى القيود :',
'minimum-size'                => 'أقل حجم',
'maximum-size'                => 'أكبر حجم:',
'pagesize'                    => '(بايت)',

# Restrictions (nouns)
'restriction-edit'   => 'تعديل',
'restriction-move'   => 'انقل',
'restriction-create' => 'اعمل',
'restriction-upload' => 'تحميل',

# Restriction levels
'restriction-level-sysop'         => 'محمية بالكامل',
'restriction-level-autoconfirmed' => 'نص محمية',
'restriction-level-all'           => 'أى مستوى',

# Undelete
'undelete'                     => 'عرض الصفحات الممسوحة',
'undeletepage'                 => 'عرض واسترجاع الصفحات المسوحة',
'undeletepagetitle'            => "'''دا بيتكون من النسخ الممسوحة لـ[[:$1]]'''.",
'viewdeletedpage'              => 'عرض الصفحات الممسوحة',
'undeletepagetext'             => '{{PLURAL:$1|الصفحة دى اتمسحت بس ليه|$1الصفحات دى اتمسحت بس ليه}} موجودة فى الارشيف و ممكن تترجع.


الأرشيف ممكن يتنضف كل شوية.',
'undelete-fieldset-title'      => 'رجع النسخ',
'undeleteextrahelp'            => "علشان ترجع تاريخ الصفحة كله، سيب كل الصناديق فاضية و دوس '''''ترجيع'''''.
علشان ترجع جزء من الصفحة، حط علامة فى الصناديق أدام التعديلات اللى عايز  ترجعهاو دوس '''''ترجيع'''''.
لو دوست على  '''''إبتدى تاني'''''  التعليق ح يتمسح و كل العلامات  اللى فى الصناديق ح تتحذف.",
'undeleterevisions'            => '$1 {{PLURAL:$1|نسخة|نسخة}} اتحطت فى  الارشيف',
'undeletehistory'              => 'لو رجعت الصفحة، كل المراجعات ح تترجع للتاريخ دا
لو فى صفحة جديدة اتعملت بنفس الاسم بعد المسح، المراجعات المترجعة ح تبان فى التاريخ اللى فات.',
'undeleterevdel'               => 'الترجيع مش ح يحصل لو كان ح يسبب ان المراجعة تيجى فى راس الصفحة أو ان الملف يتمسح حتة منه .
فى الحالات اللى زى كدا، لازم تبين أخر المراجعات الممسوحة.',
'undeletehistorynoadmin'       => 'الصفحة دى اتمسحت.
سبب المسح موجود فى الملخص اللى تحت، كمان فى تفاصيل اليوزرز اللى عملو تعديل على الصفحة دى قبل ما تتمسح.
نص المراجعات الممسوحة دى متوفرة بس للاداريين.',
'undelete-revision'            => 'المراجعة الممسوحة ل$1 (بتاريخ $4، الساعة $5) عن طريق $3:',
'undeleterevision-missing'     => 'مراجعة مش صحيحة أو ضايعة.
يمكن اللينك بتاعتك بايظة، أو يمكن المراجعة اترجعت او اتشالت من الارشيف.',
'undelete-nodiff'              => 'ما لقيناش نسخة قديمة.',
'undeletebtn'                  => 'ترجيع',
'undeletelink'                 => 'عرض/رجع تانى',
'undeleteviewlink'             => 'عرض',
'undeletereset'                => 'ابتدى من الأول',
'undeleteinvert'               => 'اعكس الاختيار',
'undeletecomment'              => 'السبب:',
'undeletedarticle'             => 'رجع  "[[$1]]" تاني',
'undeletedrevisions'           => 'رجع تانى {{PLURAL:$1|تعديل واحد|تعديلين|$1 تعديلات|$1 تعديل|$1 تعديل}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 نسخة|$1 نسخة}} و {{PLURAL:$2|1 ملف|$2 ملف}} رجعو تاني',
'undeletedfiles'               => '{{PLURAL:$1|ملف|ملفات}} $1 رجعو تاني',
'cannotundelete'               => 'الترجيع ما نفعش :ممكن يكون فى حد تانى رجع الصفحة قبل كدا.',
'undeletedpage'                => "'''اترجع $1'''

بص على [[Special:Log/delete|سجل المسح]] علشان تشوف عمليات المسح و الترجيع الاخيرة.",
'undelete-header'              => 'شوف الصفحات الممسوحة قريب فى [[Special:Log/delete|سجل المسح]].',
'undelete-search-box'          => 'دور فى الصفحات الممسوحة',
'undelete-search-prefix'       => 'عرض الصفحات اللى بتبتدى بـ:',
'undelete-search-submit'       => 'دور',
'undelete-no-results'          => 'مالقيناش صفحات مطابقة فى أرشيف المسح.',
'undelete-filename-mismatch'   => 'ماقدرناش نرجع المراجعة بتاعة الملف بتاريخ $1: اسم الملف مش مطابق',
'undelete-bad-store-key'       => 'ما قدرناش نرجع المراجعة بتاعة الملف بتاريخ $1: الملف كان ضايع قبل المسح',
'undelete-cleanup-error'       => 'خطأ مسح ملف أرشيف مش بيستعمل"$1".',
'undelete-missing-filearchive' => 'مش قادرين نرجع ملف الأرشيف رقم $1 لأنه مش موجود فى قاعدة البيانات.
يمكن يكون اترجع قبل كدا.',
'undelete-error-short'         => 'غلطة ترجيع ملف: $1',
'undelete-error-long'          => 'حصلت غلطات و الملف بيترجع:

$1',
'undelete-show-file-confirm'   => 'انتا متأكد من انك عايز تشوف المراجعة الملغية بتاعة الملف "<nowiki>$1</nowiki>" من $2 فى $3؟',
'undelete-show-file-submit'    => 'ايوه',

# Namespace form on various pages
'namespace'      => 'اسم المساحه:',
'invert'         => 'عكس الاختيار',
'blanknamespace' => '(رئيسى)',

# Contributions
'contributions'       => 'تعديلات اليوزر',
'contributions-title' => 'مساهمات اليوزر ل$1',
'mycontris'           => 'تعديلاتى',
'contribsub2'         => 'لليوزر $1 ($2)',
'nocontribs'          => 'مالقيناش   تغييرات تطابق المحددات دي.',
'uctop'               => '(فوق)',
'month'               => 'من شهر (واللى قبل كده):',
'year'                => 'من سنة (واللى قبل كده):',

'sp-contributions-newbies'        => 'عرض مساهمات الحسابات الجديدة بس',
'sp-contributions-newbies-sub'    => 'للحسابات الجديده',
'sp-contributions-newbies-title'  => 'مساهمات  اليوزر للحسابات الجديدة',
'sp-contributions-blocklog'       => 'سجل المنع',
'sp-contributions-deleted'        => 'تعديلات اليوزر الممسوحه',
'sp-contributions-logs'           => 'السجلات',
'sp-contributions-talk'           => 'مناقشه',
'sp-contributions-userrights'     => 'ادارة حقوق اليوزر',
'sp-contributions-blocked-notice' => 'اليوزر ده ممنوع دلوقتى.
آخر عمليه منع في السجل موجوده تحت كمرجع:',
'sp-contributions-search'         => 'دور على مساهمات',
'sp-contributions-username'       => 'عنوان أيبى أو اسم يوزر:',
'sp-contributions-submit'         => 'تدوير',

# What links here
'whatlinkshere'            => 'ايه بيوصل هنا',
'whatlinkshere-title'      => 'الصفحات اللى بتوصل لـ "$1"',
'whatlinkshere-page'       => 'الصفحة:',
'linkshere'                => "الصفحات دى فيها وصله ل '''[[:$1]]''':",
'nolinkshere'              => "مافيش صفحات بتوصل ل '''[[:$1]]'''.",
'nolinkshere-ns'           => "مافيش صفحات بتوصل لـ '''[[:$1]]''' فى النطاق اللى انت اختارته.",
'isredirect'               => 'صفحة تحويل',
'istemplate'               => 'متضمن',
'isimage'                  => 'لينك صورة',
'whatlinkshere-prev'       => '{{PLURAL:$1|اللى قبل كده|الـ $1 اللى قبل كده}}',
'whatlinkshere-next'       => '{{PLURAL:$1|اللى بعد كده|الـ $1 اللى بعد كده}}',
'whatlinkshere-links'      => '← وصلات',
'whatlinkshere-hideredirs' => '$1 التحويلات',
'whatlinkshere-hidetrans'  => '$1 التضمينات',
'whatlinkshere-hidelinks'  => '$1 لينكات',
'whatlinkshere-hideimages' => '$1 وصلة صورة',
'whatlinkshere-filters'    => 'فلاتر',

# Block/unblock
'blockip'                         => 'منع يوزر',
'blockip-title'                   => 'منع اليوزر',
'blockip-legend'                  => 'منع اليوزر',
'blockiptext'                     => 'استخدم الاستمارة اللى تحت لمنع عنوان أيبى أو يوزر معين من الكتابة.
دا لازم يحصل بس علشان تمنع التخريب ،و على حسب
[[{{MediaWiki:Policy-url}}|السياسة]].
اكتب سبب محدد تحت (يعنى مثلا، اكتب الصفحات المعينة اللى اتخربت بسببه).',
'ipaddress'                       => 'عنوان الأيبي:',
'ipadressorusername'              => 'عنوان الأيبى أو اسم اليوزر:',
'ipbexpiry'                       => 'مدة المنع:',
'ipbreason'                       => 'السبب:',
'ipbreasonotherlist'              => 'سبب تاني',
'ipbreason-dropdown'              => '*أسباب المنع المشهورة
** تدخيل معلومات غلط
** مسح المحتوى من الصفحات
** سبام لينك لمواقع خارجية
** كتابة كلام مالوش معنى فى الصفحات
** سلوك عدواني/تحرش
** إساءة استخدام اكتر من حسابات
** اسم يوزر مش مقبول',
'ipbanononly'                     => 'امنع اليوزرز المجهولين بس',
'ipbcreateaccount'                => 'امنع فتح الحسابات',
'ipbemailban'                     => 'منع اليوزر ده من بعتان إيميل',
'ipbenableautoblock'              => ' امنع آخر عنوان أيبى استخدمه اليوزر دا اوتوماتيكي، وأى عناوين أيبى تانية يحاول التحرير منها',
'ipbsubmit'                       => 'منع اليوزر دا',
'ipbother'                        => 'وقت تاني:',
'ipboptions'                      => '2 ساعه:2 hours,1 يوم:1 day,3 يوم:3 days,1 اسبوع:1 week,2 اسبوع:2 weeks,1 شهر:1 month,3 شهر:3 months,6 شهر:6 months,1 سنه:1 year,على طول:infinite',
'ipbotheroption'                  => 'كمان',
'ipbotherreason'                  => 'سبب تاني:',
'ipbhidename'                     => 'خبى اسم اليوزر من التعديلات و الليستات.',
'ipbwatchuser'                    => 'راقب صفحات اليوزر و النقاش بتوع اليوزر دا',
'ipballowusertalk'                => 'السماح لليوزر ده بتعديل صفحة نقاشه الخاصة أثناء المنع',
'ipb-change-block'                => 'عيد منع اليوزر بالإعدادات دى',
'badipaddress'                    => 'عنوان أيبى مش صحيح',
'blockipsuccesssub'               => 'المنع حصل بنجاح',
'blockipsuccesstext'              => 'اتمنع [[Special:Contributions/$1|$1]].<br />
شوف [[Special:IPBlockList|لستة منع الأيبي]] علشان تراجع حالات المنع.',
'ipb-edit-dropdown'               => 'عدل أسباب المنع',
'ipb-unblock-addr'                => 'رفع منع $1',
'ipb-unblock'                     => 'رفع المنع عن يوزر أو عنوان أيبي',
'ipb-blocklist-addr'              => 'عرض المنع الموجود دلوقتى  ل$1',
'ipb-blocklist'                   => 'عرض حالات المنع الموجودة دلوقتي',
'ipb-blocklist-contribs'          => 'مساهمات $1',
'unblockip'                       => 'رفع منع يوزر',
'unblockiptext'                   => 'استخدم الاستمارة اللى تحت علشان ترجع حق الكتابة بتاعة عنوان أيبى أو يوزر اتسحب منه الحق دا قبل كدا.',
'ipusubmit'                       => 'ارفع المنع دا',
'unblocked'                       => 'المنع اترفع عن [[User:$1|$1]]',
'unblocked-id'                    => 'منع $1 اترفع',
'ipblocklist'                     => 'لستة عناوين الااى بى واسامى اليوزر الممنوعه',
'ipblocklist-legend'              => 'دور على يوزر ممنوع',
'ipblocklist-username'            => 'اسم اليوزر او عنوان ال اى بي.',
'ipblocklist-sh-userblocks'       => '$1 عمليات منع الحسابات',
'ipblocklist-sh-tempblocks'       => '$1 عمليات المنع المؤقتة',
'ipblocklist-sh-addressblocks'    => '$1 عمليات منع الأيبى المفردة',
'ipblocklist-submit'              => 'تدوير',
'ipblocklist-localblock'          => 'منع محلى',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|المنع|المنع}} التانى',
'blocklistline'                   => '$1, $2 منع $3 ($4)',
'infiniteblock'                   => 'دايم',
'expiringblock'                   => 'بيخلص يوم $1 الساعه $2',
'anononlyblock'                   => 'مجهول بس',
'noautoblockblock'                => 'المنع الاوتوماتيكى متعطل',
'createaccountblock'              => ' فتح الحسابات ممنوع',
'emailblock'                      => 'الإيميل ممنوع',
'blocklist-nousertalk'            => 'لا يمكنه تعديل صفحة نقاشه الخاصة',
'ipblocklist-empty'               => 'لستة المنع فاضية.',
'ipblocklist-no-results'          => 'عنوان الأيبى أو اسم اليوزر المطلوب مش ممنوع.',
'blocklink'                       => 'بلوك',
'unblocklink'                     => 'شيل البلوك',
'change-blocklink'                => 'غير البلوك',
'contribslink'                    => 'تعديلات',
'autoblocker'                     => 'انت اتمنعت اوتوماتيكى لأن الأيبى بتاعك استعمله "[[User:$1|$1]]" فى الفترة الاخيرة.
السبب اللى خلا $1 يتمنع هو: "$2"',
'blocklogpage'                    => 'سجل المنع',
'blocklog-showlog'                => 'اليوزر ده اتمنع قبل كده.
سجل المنع موجود هنا كمرجع:',
'blocklog-showsuppresslog'        => 'المستخدم ده اتمنع واتخفى قبل كده.
سجل التخبيه موجود تحت كمرجع:',
'blocklogentry'                   => 'منع "[[$1]]" لفتره زمنيه مدتها $2 $3',
'reblock-logentry'                => 'غير إعدادات المنع ل[[$1]] بتاريخ انتهاء $2 $3',
'blocklogtext'                    => 'دا سجل بعمليات المنع ورفع المنع.
عناوين الأيبى اللى اتمنعت اوتوماتيكى مش معروضة.
شوف [[Special:IPBlockList|عناوين الأيبى الممنوعة]] علشان تشوف عمليات المنع الشغالة دلوقتي.',
'unblocklogentry'                 => 'رفع منع $1',
'block-log-flags-anononly'        => 'اليوزرز المجهولين  بس',
'block-log-flags-nocreate'        => ' فتح الحسابات ممنوع',
'block-log-flags-noautoblock'     => 'المنع التلقائى متعطل',
'block-log-flags-noemail'         => 'الإيميل ممنوع',
'block-log-flags-nousertalk'      => 'لا يمكن تعديل صفحة النقاش الخاصة',
'block-log-flags-angry-autoblock' => 'المنع الاوتوماتيكى المتقدم متفعل',
'block-log-flags-hiddenname'      => 'اسم اليوزر مخفى',
'range_block_disabled'            => 'إمكانيةالسيسوب لمنع نطاق متعطلة.',
'ipb_expiry_invalid'              => 'تاريخ الانتهاء مش صحيح.',
'ipb_expiry_temp'                 => 'عمليات منع أسماء اليوزرز المستخبية لازم تكون على طول.',
'ipb_hide_invalid'                => 'ماقدرناش نخفى الحساب دا; يمكن يكون عنده تعديلات كتيره قوى.',
'ipb_already_blocked'             => '"$1" ممنوع فعلا',
'ipb-needreblock'                 => '== ممنوع بالفعل ==
$1 ممنوع فعلا. عايز تغير الإعدادات؟',
'ipb-otherblocks-header'          => '{{PLURAL:$1||المنع التانى|المنعين التانيين|المنوعات التانيين}}',
'ipb_cant_unblock'                => 'غلطه: عنوان الااى بى الممنوع  مش موجود  $1.
يمكن اترفع منعه فعلا.',
'ipb_blocked_as_range'            => 'غلط: الأيبى $1 مش ممنوع مباشرةو مش ممكن رفع المنع عنه.
بس هو، على الرغم من كدا،ممنوع لانه جزء من النطاق $2، و اللى ممكن رفع المنع عنه.',
'ip_range_invalid'                => 'نطاق عناوين الأيبى مش صحيح.',
'ip_range_toolarge'               => 'حدود المنع اللى اكبر من /$1 مش مسموح بيها.',
'blockme'                         => 'امنعنى',
'proxyblocker'                    => 'مانع البروكسي',
'proxyblocker-disabled'           => 'الخاصية دى متعطلة.',
'proxyblockreason'                => 'عنوان الأيبى بتاعك اتمنع لانه بروكسى مفتوح.
لو سمحت تتصل بمزود خدمة الإنترنت بتاعك أو الدعم الفنى و قولهم على المشكلة الامنية الخطيرة دي.',
'proxyblocksuccess'               => 'خلاص.',
'sorbs'                           => 'دى إن إس بى إل',
'sorbsreason'                     => 'عنوان الأيبى بتاعك موجود كبروكسى مفتوح فى DNSBL اللى بيستعمله{{SITENAME}}.',
'sorbs_create_account_reason'     => 'عنوان الأيبى بتاعك موجود كبروكسى مفتوح فى ال DNSBL اللى بيستعمله {{SITENAME}}.
ما ينفعش تفتح حساب.',
'cant-block-while-blocked'        => 'أنت لا يمكنك منع اليوزرز التانين و أنت ممنوع.',
'cant-see-hidden-user'            => 'اليوزر اللى انت بتحاول تعمل له منع اصلا ممنوع و مخفى.اكمنك ما عندكش صلاحية تخبية اليوزرات، ما ينفعش تشوف المنع او تعدله.',

# Developer tools
'lockdb'              => 'اقفل قاعدة البيانات',
'unlockdb'            => 'افتح قاعدة البيانات',
'lockdbtext'          => 'قفل قاعدة البيانات ح يمنع كل اليوزرز من تحرير الصفحات وتغيير التفضيلات بتاعتهم وتعديل لستة المراقبة حاجات تانية بتحتاج تغيير قاعدة البيانات.
لو سمحت تتأكد من  ان هو دا اللى انت عايز تعمله فعلا، ومن إنك ح تشيل القفل بعد ما تخلص الصيانة.',
'unlockdbtext'        => 'فتح قاعدة البيانات ح يخلى كل اليوزرز يقدرو يحررو الصفحات، يغيرو  تفضيلاتهم،يعدلو لستة المراقبة  بتاعتهم، و حاجات تانية محتاجين يغيروها فى قاعدة البانات.
. لو سمحت تتاكد ان هو دا اللى انت عايز تعمله',
'lockconfirm'         => 'أيوه، أنا فعلا عايز اقفل قاعدة البيانات.',
'unlockconfirm'       => 'أيوه، أنا فعلا عايز افتح قاعدة البيانات.',
'lockbtn'             => 'قفل قاعدة البيانات',
'unlockbtn'           => 'افتح قاعدة البيانات',
'locknoconfirm'       => 'انت ما علمتش على صندوق التأكيد.',
'lockdbsuccesssub'    => 'نجح قفل قاعدة البيانات',
'unlockdbsuccesssub'  => 'قفل قاعدة البيانات إتشال.',
'lockdbsuccesstext'   => 'قاعدة البانات اتقفلت خلاص.<br />
ماتنساش [[Special:UnlockDB|تشيل القفل]] بعد أعمال الصيانة ما تخلص .',
'unlockdbsuccesstext' => 'قاعدة البيانات إتفتحت تانى',
'lockfilenotwritable' => 'ملف قفل قاعدة البيانات مش ممكن يتكتب عليه.
علشان تقفل قاعدة البيانات أو تشيل القفل لازم سيرفر الويب يسمح بالكتابة على الملف دا .',
'databasenotlocked'   => 'قاعدة البيانات بتاعتك مش  مقفولة.',

# Move page
'move-page'                    => 'انقل $1',
'move-page-legend'             => 'انقل الصفحة',
'movepagetext'                 => "لو استعملت النموذج ده ممكن تغير اسم الصفحه، و تنقل تاريخها للاسم الجديد.
هاتبتدى تحويله من العنوان القديم للصفحه بالعنوان الجديد.
لكن،  الوصلات فى الصفحات اللى بتتوصل بالصفحه دى مش ها تتغير؛ اتأكد من ان مافيش  [[Special:BrokenRedirects|وصلات مقطوعه]] ، أو [[Special:DoubleRedirects|وصلات متتاليه]] ، للتأكد من أن المقالات تتصل مع بعضها بشكل مناسب.

لاحظ ان الصفحه مش هاتتنقل لو كان فيه صفحه بالاسم الجديد، إلا إذا كانت صفحة فاضيه، أو صفحة تحويل، ومالهاش تاريخ. و ده معناه أنك مش ها تقدر تحط صفحه مكان صفحه، كمان ممكن ارجاع الصفحه لمكانها فى حال تم النقل بشكل غلط.

'''تحذير!'''
نقل الصفحه ممكن يكون له اثار كبيرة، وتغييرات مش متوقعه بالنسبة للصفحات المشهوره. من فضلك  اتأكد من فهم عواقب نقل الصفحات قبل ما تقوم بنقل الصفحه.",
'movepagetalktext'             => "صفحة المناقشه بتاعة المقاله هاتتنقل برضه، لو كانت موجوده. لكن صفحة المناقشه '''مش''' هاتتنقل فى الحالات دى:
* نقل الصفحة عبر نطاقات  مختلفه.
*فيه  صفحة مناقشه موجوده تحت العنوان الجديد للمقاله.
* لو انت شلت اختيار نقل صفحة المناقشه .

وفى الحالات  دى، لو عايز  تنقل صفحة المناقشه  لازم تنقل أو تدمج محتوياتها  يدويا.",
'movearticle'                  => 'انقل الصفحه:',
'moveuserpage-warning'         => "'''خد بالك:''' انت ح تعمل نقل لصفحه بتاعة يوزر. لو سمحت تعمل حسابك ان الصفحه هى بس اللى ح تتنقل و اسم اليوزر''مش'' ح يتغير.",
'movenologin'                  => 'مش متسجل',
'movenologintext'              => 'لازم تكون يوزر متسجل و تعمل [[Special:UserLogin|دخول]] علشان تنقل الصفحة.',
'movenotallowed'               => 'ماعندكش الصلاحية لنقل الصفحات.',
'movenotallowedfile'           => 'معندكش اذن تنقل الملف ده.',
'cant-move-user-page'          => 'أنت لا تمتلك الصلاحية لنقل صفحات اليوزر الرئيسية.',
'cant-move-to-user-page'       => 'أنت لا تمتلك الصلاحية لنقل صفحة لصفحة يوزر (ماعدا لصفحة يوزر فرعية).',
'newtitle'                     => 'للعنوان الجديد:',
'move-watch'                   => 'راقب الصفحه دى',
'movepagebtn'                  => 'نقل الصفحه',
'pagemovedsub'                 => 'تم  النقل بنجاح',
'movepage-moved'               => '\'\'\'"$1" خلاص اتنقلت لـ "$2"\'\'\'',
'movepage-moved-redirect'      => 'فى تحويله اتعملت.',
'movepage-moved-noredirect'    => 'التحويله ما اتعملتش.',
'articleexists'                => 'يا اما فيه صفحه  بالاسم ده، او ان الاسم اللى  تم اختياره مش صالح.
لو سمحت اختار اسم تانى.',
'cantmove-titleprotected'      => 'ما ينفعش تنقل صفحة للمكان دا،لأن العنوان الجديد محمى ضد الانشاء',
'talkexists'                   => "'''الصفحه دى اتنقلت لصفحة بنجاح، ولكن صفحة المناقشه بتاعتها ما اتنقلتش  علشان فيه صفحة مناقشه تحت العنوان الجديد.
من فضلك انقل محتويات صفحة المناقشه يدويا، وادمجها مع المحتويات اللى قبل كده.'''",
'movedto'                      => 'اتنقلت ل',
'movetalk'                     => 'انقل صفحة المناقشه.',
'move-subpages'                => 'نقل الصفحات الفرعيه (لحد $1)',
'move-talk-subpages'           => 'نقل الصفحات الفرعيه بتاعة صفحة النقاش (لحد $1)',
'movepage-page-exists'         => 'الصفحة $1 موجودة فعلا ومش ممكن الكتابة عليها اوتوماتيكي..',
'movepage-page-moved'          => 'الصفحة $1 اتنقلت لـ $2.',
'movepage-page-unmoved'        => 'ماقدرناش ننقل الصفحة $1 لـ $2.',
'movepage-max-pages'           => 'الحد الأقصى $1 {{PLURAL:$1|صفحة|صفحة}} اتنقل. و مافيش حاجة تانى ح تتنقل اوتوماتيكي.',
'1movedto2'                    => '[[$1]] اتنقلت ل [[$2]]',
'1movedto2_redir'              => '[[$1]] اتنقلت لـ[[$2]] فوق التحويله',
'move-redirect-suppressed'     => ' التحويل ممنوع.',
'movelogpage'                  => 'سجل النقل',
'movelogpagetext'              => 'تحت فى لستة الصفحات اللى اتنقلت.',
'movesubpage'                  => '{{PLURAL:$1|صفحه فرعيه|صفحات فرعيه}}',
'movesubpagetext'              => 'الصفحه دى فيها $1 {{PLURAL:$1|صفحه فرعيه|صفحات فرعيه}} معروضه تحت.',
'movenosubpage'                => 'الصفحه دى مافيهاش صفحات فرعيه.',
'movereason'                   => 'السبب:',
'revertmove'                   => 'رجّع',
'delete_and_move'              => 'مسح ونقل',
'delete_and_move_text'         => '==المسح مطلوب==
الصفحة الهدف "[[:$1]]" موجودة فعلا.
انت عايز تمسحها علشان تقدر تنقلها؟',
'delete_and_move_confirm'      => 'ايوة، امسح الصفحة',
'delete_and_move_reason'       => 'اتمسحت علشان تسمح للنقل',
'selfmove'                     => 'عنوان المصدر والهدف هو نفسه؛
مش ممكن نقل الصفحة على نفسها.',
'immobile-source-namespace'    => 'غير قادر على نقل الصفحات فى النطاق "$1"',
'immobile-target-namespace'    => 'غير قادر على نقل الصفحات إلى النطاق "$1"',
'immobile-target-namespace-iw' => 'لينك الانترويكى ماينفعش تكون هدف لنقل الصفحه',
'immobile-source-page'         => 'الصفحه دى مش قابلة للنقل.',
'immobile-target-page'         => 'غير قادر على النقل إلى العنوان الوجهة هذا.',
'imagenocrossnamespace'        => 'مش ممكن تنقل الملف لنطاق غير نطاق الملفات',
'imagetypemismatch'            => 'امتداد الملف الجديد مش ماشى مع نوعه',
'imageinvalidfilename'         => 'اسم الملف الهدف مش صحيح',
'fix-double-redirects'         => 'اعمل تحديث لاى تحويلات بتشاور على العنوان الاصلي',
'move-leave-redirect'          => 'سيب تحويله فى الصفحه',
'protectedpagemovewarning'     => "'''تحذير:''' الصفحه دى اتقفلت بطريقه تخلّى اليوزرات اللى عندهم صلاحيات اداريه هما بس اللى يقدرو ينقلوها.
اخر سجل محطوط تحت علشان المراجعه:",
'semiprotectedpagemovewarning' => "'''ملاحظه:''' الصفحه دى اتقفلت بطريقه تخلّى اليوزرات المتسجلين بس هما اللى يقدرو ينقلوها.
اخر سجل محطوط تحت علشان المراجعه:",
'move-over-sharedrepo'         => '==الملف موجود==
[[:$1]] موجود فى مخزن مشترك.لو نقلت ملف للاسم دا ح يلغى الملف المشترك.',
'file-exists-sharedrepo'       => 'اسم الملف اللى اخترته موجود من قبل كده فى مخزن مشترك.
لو سمحت تختار اسم تانى.',

# Export
'export'            => 'تصدير صفحات',
'exporttext'        => 'انت ممكن تصدر النص وتاريخ تعديلات صفحة معينة أو مجموعة صفحات فى صيغة إكس إم إل. لو قصدكو بكدا ممكن استيرادها فى ويكى تانى بيستعمل ميدياويكى عن طريق الصفحة [[Special:Import|صفحة الاستيراد]].

علشان تصدر الصفحات، اكتب العناوين فى الصندوق اللى تحت، عنوان واحد فى كل السطر، و اختار اذا كنت عايز  النسخة الحالية بالإضافة  للنسخ القديمة كاملة أو مع معلومات تاريخ الصفحة عنها ولا بس النسخة الحالية مع معلومات عن التعديل الأخير.

فى الحالة التانية ممكن تستخدم لينك مباشرة، مثلا [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] للصفحة [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'ضمن المراجعة دى بس، ومش التاريخ الكامل',
'exportnohistory'   => "----
ملاحظة:''' التصدير الكامل لتاريخ الصفحة  بالطريقة دى مش شغال بسبب الاداء'''",
'export-submit'     => 'تصدير',
'export-addcattext' => 'ضيف صفحات من تصنيف:',
'export-addcat'     => 'زيادة',
'export-addnstext'  => 'اضافة صفحات من نطاق:',
'export-addns'      => 'اضافه',
'export-download'   => 'احفظ كملف',
'export-templates'  => 'دخل القوالب',
'export-pagelinks'  => 'تضمين الصفحات المتوصله لحد عمق :',

# Namespace 8 related
'allmessages'                   => 'رسايل النظام',
'allmessagesname'               => 'اسم',
'allmessagesdefault'            => 'النص الاوتوماتيكي',
'allmessagescurrent'            => 'النص دلوقتى',
'allmessagestext'               => 'دى لستة برسايل النظام المتوفرة فى نطاق ميدياويكي.
لو سمحت تزور[http://www.mediawiki.org/wiki/Localisation ترجمة ميدياويكي] و [http://translatewiki.net بيتاويكي] لو كنت عايز تساهم فى ترجمة ميدياويكى الاصلية.',
'allmessagesnotsupportedDB'     => "الصفحة دى مش يمكن حد يستعملها علشان'''\$wgUseDatabaseMessages''' متعطل.",
'allmessages-filter-legend'     => 'فيلتر',
'allmessages-filter'            => 'فلتره بحالة التهيئه:',
'allmessages-filter-unmodified' => 'مش متعدل',
'allmessages-filter-all'        => 'الكل',
'allmessages-filter-modified'   => 'متعدل',
'allmessages-prefix'            => 'فلتره بالبريفيكس:',
'allmessages-language'          => 'اللغه:',
'allmessages-filter-submit'     => 'روح',

# Thumbnails
'thumbnail-more'           => 'كبر',
'filemissing'              => 'الملف ضايع',
'thumbnail_error'          => 'غلطه فى انشاء صوره مصغره: $1',
'djvu_page_error'          => 'صفحة DjVu بره النطاق',
'djvu_no_xml'              => 'مش ممكن تجيب XML لملف DjVu',
'thumbnail_invalid_params' => 'محددات التصغير مش صحيحة',
'thumbnail_dest_directory' => 'مش قادر ينشئ المجلد الهدف',
'thumbnail_image-type'     => 'نوع الصوره مش مدعوم',
'thumbnail_gd-library'     => 'ضبط المكتبه GD مش كامل:داله مش موجوده $1',
'thumbnail_image-missing'  => 'الظاهر ان الملف ضايع: $1',

# Special:Import
'import'                     => 'استيراد صفحات',
'importinterwiki'            => 'استيراد ترانسويكي',
'import-interwiki-text'      => 'اختار الويكى و عنوان الصفحة اللى عاوز تستوردها.
تواريخ التعديلات و اسامى المحررين  ح يتحافظ عليها.
كل عمليات الاستيراد للترانسويكى بتتسجل فى [[Special:Log/import|سجل الاستيراد]].',
'import-interwiki-source'    => 'مصدر ويكي/صفحه:',
'import-interwiki-history'   => 'انسخ كل نسخ التاريخ للصفحة دي',
'import-interwiki-templates' => 'اشمل كل القوالب',
'import-interwiki-submit'    => 'استيراد',
'import-interwiki-namespace' => 'النطاق الهدف:',
'import-upload-filename'     => 'اسم الملف:',
'import-comment'             => 'تعليق:',
'importtext'                 => 'لو سمحت تصدّر الملف من الويكى المصدر عن طريق [[Special:Export|خاصية التصدير]].
احفظه على جهازك و بعدين حمله هنا.',
'importstart'                => 'استيراد صفحات...',
'import-revision-count'      => '{{PLURAL:$1|نسخة واحدة|نسخة}} $1',
'importnopages'              => 'مافيش صفحات للاستيراد',
'importfailed'               => 'فشل استيراد: $1',
'importunknownsource'        => 'نوع مصدر الاستيراد مش معروف',
'importcantopen'             => 'ماقدرناش نفتح ملف الاستيراد',
'importbadinterwiki'         => 'اللينك بتاعة الانترويكى دى غلط',
'importnotext'               => 'فاضى او مافيش نص',
'importsuccess'              => 'الاستيراد خلص!',
'importhistoryconflict'      => 'فى تاريخ تعديلات متعارض مع بعضه(يمكن الصفحة دى تكون استوردت قبل كدا)',
'importnosources'            => ' مصادر استيراد الترانسويكى ما تحددتش  و الاستيراد المباشر عن طريق التحميل مش شغال.',
'importnofile'               => 'ملف الاستيراد ما تحملش.',
'importuploaderrorsize'      => 'تحميل ملف الاستيراد فشل.
الملف أكبر من حجم التحميل المسموح.',
'importuploaderrorpartial'   => 'تحميل ملف الاستيراد فشل.
جزء من الملف بس اتحمل.',
'importuploaderrortemp'      => 'تحميل ملف الاستيراد فشل.
فى مجلد مؤقت ضايع.',
'import-parse-failure'       => 'فشل بارس استيراد XML',
'import-noarticle'           => 'مافيش صفحة للاستيراد!',
'import-nonewrevisions'      => 'كل النسخ استوردت قبل كدا.',
'xml-error-string'           => '$1 عند السطر $2، العمود $3 (بايت $4): $5',
'import-upload'              => 'حمل بيانات إكس إم إل',
'import-token-mismatch'      => 'الداتا بتاعة الجلسة ضاعت. لو سمحت تحاول تاني.',
'import-invalid-interwiki'   => 'ماينفعش تستورد من الويكى المتحدد.',

# Import log
'importlogpage'                    => 'سجل الاستيراد',
'importlogpagetext'                => 'استيرادات إدارية لصفحات ليها تاريخ تعديل من مواقع ويكى تانية.',
'import-logentry-upload'           => 'استورد [[$1]] بواسطة تحميل ملف',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|تعديل واحد|تعديل}}',
'import-logentry-interwiki'        => 'استيراد ويكى $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|تعديل واحد|تعديل}} من $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'صفحة اليوزر بتاعتك',
'tooltip-pt-anonuserpage'         => 'صفحة اليوزر للأيبى اللى انت بتعمل منه تحرير',
'tooltip-pt-mytalk'               => 'صفحة المنقاشه بتاعتك',
'tooltip-pt-anontalk'             => 'نقاش حوالين التعديلات من عنوان الأيبى دا',
'tooltip-pt-preferences'          => 'تفضيلاتى',
'tooltip-pt-watchlist'            => 'ليستة الصفح اللى بتراقب التعديلات فيها',
'tooltip-pt-mycontris'            => 'ليستة تعديلاتك',
'tooltip-pt-login'                => 'يستحسن تسجل دخولك; لكن, ده مش اجبارى',
'tooltip-pt-anonlogin'            => 'من الأفضل انك تسجل دخولك، لكن ده مش إجبارى.',
'tooltip-pt-logout'               => 'خروج',
'tooltip-ca-talk'                 => 'مناقشة صفحة الموضوع',
'tooltip-ca-edit'                 => 'ممكن تعدل الصفحه دى.
بس لو سمحت استعمل زرار الپروڤه قبل ما تسييڤها.',
'tooltip-ca-addsection'           => 'ابتدى قسم جديد',
'tooltip-ca-viewsource'           => 'الصفحه دى محميه.
ممكن تشوف مصدرها.',
'tooltip-ca-history'              => 'نسخ قديمه من الصفحه دى',
'tooltip-ca-protect'              => 'احمى الصفحه دى',
'tooltip-ca-unprotect'            => 'شيل  الحمايه من الصفحه دى',
'tooltip-ca-delete'               => 'امسح الصفحه دى',
'tooltip-ca-undelete'             => 'رجع التعديلات اللى حصلت على الصفحة دى قبل ما تتمسح',
'tooltip-ca-move'                 => 'انقل الصفحه دى',
'tooltip-ca-watch'                => 'زوّد الصفحه دى على ليستة الصفح اللى بتراقب التعديل فيها',
'tooltip-ca-unwatch'              => 'شيل الصفحه دى من لستة الصفحات اللى بتراقبها',
'tooltip-search'                  => 'دور فى {{SITENAME}}',
'tooltip-search-go'               => 'روح لصفحه بالاسم دا بالظبط لو موجوده',
'tooltip-search-fulltext'         => 'دور فى الصفحات على النَص دا',
'tooltip-p-logo'                  => 'الصفحه الرئيسيه',
'tooltip-n-mainpage'              => 'زور الصفحه الرئيسيه',
'tooltip-n-mainpage-description'  => 'زور الصفحه الرئيسيه',
'tooltip-n-portal'                => 'عن المشروع, ممكن تعمل ايه, و فين تلاقى اللى بتدور عليه',
'tooltip-n-currentevents'         => 'شوف معلومات على الاحداث اللى بتحصل دلوقتى',
'tooltip-n-recentchanges'         => 'ليستة التعديلات الاخرانيه فى الويكى',
'tooltip-n-randompage'            => 'لوّد صفحه عشوائيه',
'tooltip-n-help'                  => 'لو محتاج مساعده بص هنا',
'tooltip-t-whatlinkshere'         => 'ليستة كل الصفح اللى بتوصل هنا',
'tooltip-t-recentchangeslinked'   => 'اخر التغييرات فى صفح معمول ليها لينك من الصفحه دى',
'tooltip-feed-rss'                => 'تلقيم أر إس إس للصفحة دي',
'tooltip-feed-atom'               => 'تلقيم أتوم للصفحة دي',
'tooltip-t-contributions'         => 'عرض مساهمات اليوزر ده',
'tooltip-t-emailuser'             => 'ابعت ايميل لليوزر ده',
'tooltip-t-upload'                => 'ارفع فايلات (upload files)',
'tooltip-t-specialpages'          => 'ليستة كل الصفح المخصوصه',
'tooltip-t-print'                 => 'نسخه تنفع تتطبع للصفحه دى',
'tooltip-t-permalink'             => 'لينك دايم للنسخه دى من الصفحه',
'tooltip-ca-nstab-main'           => 'اعرض صفحة المحتوى',
'tooltip-ca-nstab-user'           => 'اعرض صفحة اليوزر',
'tooltip-ca-nstab-media'          => 'اعرض صفحة الميديا',
'tooltip-ca-nstab-special'        => 'دى صفحه مخصوصه, ما تقدر ش تعدل الصفحه نفسها',
'tooltip-ca-nstab-project'        => 'اعرض صفحة المشروع',
'tooltip-ca-nstab-image'          => 'اعرض صفحة الفايل',
'tooltip-ca-nstab-mediawiki'      => 'اعرض رسالة النظام',
'tooltip-ca-nstab-template'       => 'اعرض القالب',
'tooltip-ca-nstab-help'           => 'اعرض صفحة المساعده',
'tooltip-ca-nstab-category'       => 'اعرض صفحة التصنيف',
'tooltip-minoredit'               => 'علم على ده كتعديل صغير',
'tooltip-save'                    => ' سييڤ تعديلاتك',
'tooltip-preview'                 => 'اعرض بروفه لتعديلاتك، من فضلك شوف البروفه قبل ما تسييف!',
'tooltip-diff'                    => 'اعرض التعديلات اللى انت عملتها على النص.',
'tooltip-compareselectedversions' => 'شوف الفروق بين النسختين المختارتين للصفحه دى.',
'tooltip-watch'                   => 'ضم الصفحه دى للستة الصفحات اللى بتراقبها',
'tooltip-recreate'                => 'إنشيء الصفحة تانى مع انها اتمسحت قبل كدا',
'tooltip-upload'                  => 'ابتدى التحميل',
'tooltip-rollback'                => "\"'''ترجيع'''\" بيرجع بدوسه واحده التعديل (التعديلات) فى الصفحه دى لاخر واحد عدل الصفحه.",
'tooltip-undo'                    => '"رجوع" بترجع  التعديل دا وبتفتح استمارة التعديل فى شكل البروفة. بتسمح بإضافة سبب فى الملخص.',

# Stylesheets
'common.css'      => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على كل الواجهات */',
'standard.css'    => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة ستاندرد */',
'nostalgia.css'   => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة نوستالشيا */',
'cologneblue.css' => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة كولون بلو */',
'monobook.css'    => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة مونوبوك */',
'myskin.css'      => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة ماى سكين */',
'chick.css'       => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة تشيك */',
'simple.css'      => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة سيمبل */',
'modern.css'      => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على مستخدمى واجهة مودرن */',
'vector.css'      => '/* CSS اللى هنا حتأثر على اليوزرز اللى بيستخدموا واجهة فكتور */',
'print.css'       => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على ناتج الطباعة */',
'handheld.css'    => '/* الأنماط المتراصة CSS المعروضة هنا ستؤثر على الأجهزة المحمولة بالاعتماد على الواجهة المضبوطة فى $wgHandheldStyle */',

# Scripts
'common.js'      => '/*  أى جافاسكريبت  هناح يتحمل لكل اليوزرز مع كل تحميل للصفحة. */',
'standard.js'    => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة ستاندرد */',
'nostalgia.js'   => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة نوستالجيا */',
'cologneblue.js' => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة كولون بلو */',
'monobook.js'    => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة مونوبوك */',
'myskin.js'      => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة ماى سكين */',
'chick.js'       => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة تشيك */',
'simple.js'      => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة سيمبل */',
'modern.js'      => '/* أى جافاسكريبت هنا ح تتحمل لليوزرز اللى بيستعملو واجهة مودرن */',
'vector.js'      => '/* اى جافاسكريبت هنا حتتحمل لكل يوزر بيستخدم واجهة فكتور */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata متعطلة للسيرفر دا.',
'nocreativecommons' => 'Creative Commons RDF metadata متعطلة  للسيرفر دا.',
'notacceptable'     => 'السيرفر بتاع الويكى مش ممكن يديلك بيانات بصيغة ممكن عميلك يقراها.',

# Attribution
'anonymous'        => '{{PLURAL:$1|يوزر مجهول|يوزرز مجهولين}} ل {{SITENAME}}',
'siteuser'         => 'يوزر {{SITENAME}} $1',
'anonuser'         => '{{SITENAME}} يوزر مجهول $1',
'lastmodifiedatby' => 'آخر تعديل  للصفحة دى كان فى $2، $1 عن طريق $3.',
'othercontribs'    => 'بناء على عمل $1.',
'others'           => 'تانيين',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|يوزر|يوزرز}} $1',
'anonusers'        => '{{SITENAME}} مجهول {{PLURAL:$2|يوزر|يوزرات}} $1',
'creditspage'      => 'حقوق الصفحة',
'nocredits'        => 'مافيش معلومات حقوق متوفرة للصفحة دي.',

# Spam protection
'spamprotectiontitle' => 'فلتر الحمايه من السبام',
'spamprotectiontext'  => 'السبام فيلتر منعك من إنك تحفظ الصفحة دى.
السبب يمكن علشان فى لينك لسايت خارجى فى القايمة السودة.',
'spamprotectionmatch' => 'النص دا هو اللى نشط السبام فيلتر بتاعنا: $1',
'spambot_username'    => 'تنظيف سبام ميدياويكى',
'spam_reverting'      => 'ترجيع آخر نسخة مافيهاش لينكات لـ $1',
'spam_blanking'       => 'كل النسخ فيها لينكات ل $1، فضيها',

# Info page
'infosubtitle'   => 'معلومات للصفحه',
'numedits'       => 'عدد التعديلات (صفحة): $1',
'numtalkedits'   => 'عدد التعديلات (صفحة نقاش): $1',
'numwatchers'    => 'عدد المراقبين: $1',
'numauthors'     => 'عدد المؤلفين المميزين (صفحة): $1',
'numtalkauthors' => 'عدد المؤلفين المميزين (صحفة نقاش): $1',

# Skin names
'skinname-standard'    => 'كلاسيك',
'skinname-nostalgia'   => 'نوستالجيا',
'skinname-cologneblue' => 'كولون بلو',
'skinname-monobook'    => 'مونوبوك',
'skinname-myskin'      => 'ماى سكين',
'skinname-chick'       => 'تشيك',
'skinname-simple'      => 'سيمبل',
'skinname-modern'      => 'مودرن',

# Math options
'mw_math_png'    => 'دايما اعرض PNG',
'mw_math_simple' => 'يا إما HTML لو بسيطة قوى أو PNG',
'mw_math_html'   => 'ياإما HTML لو ممكن أو PNG',
'mw_math_source' => 'اعرض على هيئة TeX (للبراوزرات النصية)',
'mw_math_modern' => 'أحسن للبراوزرات الحديثة',
'mw_math_mathml' => 'اعرض بصيغة MathML لو ممكن (تحت التجريب)',

# Math errors
'math_failure'          => 'الاعراب فشل',
'math_unknown_error'    => 'غلط مش معروف',
'math_unknown_function' => 'وظيفة مش معروفة',
'math_lexing_error'     => 'غلط فى الكلمة',
'math_syntax_error'     => 'غلط فى تركيب الجملة',
'math_image_error'      => 'فشل التحويل لـ PNG ؛
اتاكد من التثبيت المضبوط لـ :Latex و dvips و gs و convert.',
'math_bad_tmpdir'       => 'مش ممكن الكتابة أو انشاء مجلد الرياضة الموؤقت',
'math_bad_output'       => 'مش ممكن الكتابة لـ أو إنشاء مجلد الخرج للرياضيات',
'math_notexvc'          => 'ضايعtexvc executable ؛ لو سمحت شوفmath/README للضبط.',

# Patrolling
'markaspatrolleddiff'                 => 'علم عليها انها متراجعة',
'markaspatrolledtext'                 => 'علم على المقاله دى إنها متراجعة',
'markedaspatrolled'                   => 'اتعلم عليها متراجعة',
'markedaspatrolledtext'               => 'النسخه اللى مختارها من [[:$1]] اتعلّم عيها انها متراجعه.',
'rcpatroldisabled'                    => 'مراجعة أخر التغييرات متعطلة',
'rcpatroldisabledtext'                => 'خاصية مراجعة أحدث التغييرات متعطلة  دلوقتي',
'markedaspatrollederror'              => 'مش ممكن تعلم علها إنها متراجعة',
'markedaspatrollederrortext'          => 'لازم تختار النسخة اللى عاوز تعلم عليها إنها متراجعة',
'markedaspatrollederror-noautopatrol' => 'مش مسموح ليك تعلم على تغييراتك الشخصية كأنها متراجعة.',

# Patrol log
'patrol-log-page'      => 'سجل المراجعة',
'patrol-log-header'    => 'دا سجل بالنسخ المتراجعة',
'patrol-log-line'      => 'علم على $1 من $2 كأنها متراجعة $3',
'patrol-log-auto'      => '(اوتوماتيكي)',
'patrol-log-diff'      => 'ن$1',
'log-show-hide-patrol' => '$1 سجل المراجعة',

# Image deletion
'deletedrevision'                 => 'مسح النسخة القديمة $1',
'filedeleteerror-short'           => 'غلط مسح الملف: $1',
'filedeleteerror-long'            => 'حصلت غلطات و الملف دا بيتمسح :

$1',
'filedelete-missing'              => 'الملف "$1" ما ينفعش يتمسح لأنه مش موجود.',
'filedelete-old-unregistered'     => 'نسخة الملف المحددة "$1" مش فى قاعدة البيانات.',
'filedelete-current-unregistered' => 'الملف المحدد "$1" مش فى قاعدة البيانات.',
'filedelete-archive-read-only'    => 'مش ممكن تكتب على مجلد الأرشيف "$1" بالويب سيرفر',

# Browsing diffs
'previousdiff' => '→ التعديل اللى قبل كده',
'nextdiff'     => 'التعديل اللى بعد كده ←',

# Media information
'mediawarning'         => "'''تحذير''': الملف دا ممكن يكون فيه كود مضر.
لو شغلته,الكومبيوتر بتاعك ممكن يخرب.",
'imagemaxsize'         => "حد حجم الصوره:<br />''(لصفحات الوصف بتاع الملفات)''",
'thumbsize'            => 'حجم العرض المتصغر:',
'widthheightpage'      => '$1×$2، $3 {{PLURAL:$3|صفحة|صفحة}}',
'file-info'            => '(حجم الملف: $1، نوع MIME: $2)',
'file-info-size'       => '($1 × $2 بكسل حجم الفايل: $3، نوع MIME: $4)',
'file-nohires'         => '<small>مافيش  ريزوليوشن اعلى متوفر.</small>',
'svg-long-desc'        => '(ملف SVG، اساسا $1 × $2 بكسل، حجم الملف: $3)',
'show-big-image'       => 'الصورة بدقه كامله',
'show-big-image-thumb' => '<small>حجم البروفه دى: $1 × $2 بكسل</small>',
'file-info-gif-looped' => 'ملفوف',
'file-info-gif-frames' => '$1 {{PLURAL:$1|برواز|براويز}}',

# Special:NewFiles
'newimages'             => 'جاليرى الصور الجديده',
'imagelisttext'         => 'دى لستة بـ$1 {{PLURAL:$1|ملف|ملفات}} مترتبة $2.',
'newimages-summary'     => 'الصفحةالمخصوصة دى بتعرض آخر الملفات المتحملة',
'newimages-legend'      => 'اسم الملف',
'newimages-label'       => 'اسم الملف (او حتة منه):',
'showhidebots'          => '($1 بوتات)',
'noimages'              => 'مافيش حاجة للعرض.',
'ilsubmit'              => 'تدوير',
'bydate'                => 'على حسب التاريخ',
'sp-newimages-showfrom' => 'بين الملفات الجديدة  من أول $2، $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1، $2×$3',
'seconds-abbrev' => 'ث',
'minutes-abbrev' => 'ق',
'hours-abbrev'   => 'س',

# Bad image list
'bad_image_list' => 'التصميم (الـ format) بالشكل ده:

عناصر الليسته بس (السطور اللى بتبتدى بـ *) بتتاخد فى الاعتبار.
اول لينك فى سطر لازم يكون لينك لـ فايل مش شغال.
اى لينكات فى نفس السطر, تعتبر انها استثناء, يعنى صفح ممكن يكون موجود فيها الفايل جوّا سطر.',

# Metadata
'metadata'          => 'بيانات ميتا',
'metadata-help'     => 'الملف ده فيه معلومات إضافيه، غالبا ما تكون أضيفت من الديجيتال كاميرا أو السكانر ح الضوئى المستخدم فى نقل الملف للكومبيوتر.
إذا كان الملف اتعدل عن حالته الأصلية، فبعض التفاصيل مش ها تعبر عن الملف المعدل.',
'metadata-expand'   => 'عرض التفاصيل الاضافيه',
'metadata-collapse' => 'تخبية التفاصيل الاضافيه',
'metadata-fields'   => 'حقول معطيات الميتا EXIF الموجوده فى الرساله دى هاتتعرض فى صفحة الصوره لما يكون جدول معطيات الميتا مضغوط. الحقول التانيه هاتكون مخفيه افتراضيا.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'العرض',
'exif-imagelength'                 => 'الطول',
'exif-bitspersample'               => 'بتس لكل مكون',
'exif-compression'                 => 'نظام الضغط',
'exif-photometricinterpretation'   => 'تركيب البكسل',
'exif-orientation'                 => 'التوجيه',
'exif-samplesperpixel'             => 'عدد المكونات',
'exif-planarconfiguration'         => 'ترتيب البيانات',
'exif-ycbcrsubsampling'            => 'نسبة العينة الفرعية بتاعة Y لـ C',
'exif-ycbcrpositioning'            => 'وضع Y و C',
'exif-xresolution'                 => 'الدقة الأفقية',
'exif-yresolution'                 => 'الدقة الرأسية',
'exif-resolutionunit'              => 'وحدة تحليل X و Y',
'exif-stripoffsets'                => 'موقع بيانات الصورة',
'exif-rowsperstrip'                => 'عدد الصفوف لكل ستريب',
'exif-stripbytecounts'             => 'بايت لكل ستريب مضغوط',
'exif-jpeginterchangeformat'       => 'الحد ل JPEG SOI',
'exif-jpeginterchangeformatlength' => 'بايت من بيانات JPEG',
'exif-transferfunction'            => 'وظيفة النقل',
'exif-whitepoint'                  => 'ألوان النقطة البيضا',
'exif-primarychromaticities'       => 'ألوان الأساسيات',
'exif-ycbcrcoefficients'           => 'معاملات مصفوفة تحويل فراغ اللون',
'exif-referenceblackwhite'         => 'جوز من قيم المرجع السودا والبيضا',
'exif-datetime'                    => 'تاريخ و وقت تغيير الملف',
'exif-imagedescription'            => 'عنوان الصورة',
'exif-make'                        => 'منتج الكاميرا',
'exif-model'                       => 'موديل الكاميرا',
'exif-software'                    => 'البرمجيات المستخدمة',
'exif-artist'                      => 'المؤلف',
'exif-copyright'                   => 'صاحب الحقوق الممحفوظة',
'exif-exifversion'                 => 'نسخة Exif',
'exif-flashpixversion'             => 'نسخة فلاش بكس المدعومة',
'exif-colorspace'                  => 'فرق اللون',
'exif-componentsconfiguration'     => 'معنى كل مكون',
'exif-compressedbitsperpixel'      => 'طور ضغط الصورة',
'exif-pixelydimension'             => 'عرض صورة صحيح',
'exif-pixelxdimension'             => 'ارتفاع صورة صحيح',
'exif-makernote'                   => 'ملاحظات الصانع',
'exif-usercomment'                 => 'تعليقات اليوزر',
'exif-relatedsoundfile'            => 'ملف صوت مرتبط',
'exif-datetimeoriginal'            => 'تاريخ و وقت الإنتاج',
'exif-datetimedigitized'           => 'تاريخ و وقت التحويل الرقمى',
'exif-subsectime'                  => 'وقت تاريخ ثوانى فرعية',
'exif-subsectimeoriginal'          => 'وقت تاريخ أصلى ثوانى فرعية',
'exif-subsectimedigitized'         => 'وقت تاريخ رقمى ثوانى فرعية',
'exif-exposuretime'                => 'مدة التعرض',
'exif-exposuretime-format'         => '$1 ثانية ($2)',
'exif-fnumber'                     => 'العدد البؤرى',
'exif-fnumber-format'              => 'البعد البؤرى/$1',
'exif-exposureprogram'             => 'برنامج التعرض',
'exif-spectralsensitivity'         => 'الحساسية الطيفية',
'exif-isospeedratings'             => 'تقييم سرعة أيزو',
'exif-oecf'                        => 'عامل التحويل الكهروضوئى',
'exif-shutterspeedvalue'           => 'سرعة القافل',
'exif-aperturevalue'               => 'فتحة القافل',
'exif-brightnessvalue'             => 'الضي',
'exif-exposurebiasvalue'           => 'تعويض التعرض',
'exif-maxaperturevalue'            => 'أقصى قافل أرضى',
'exif-subjectdistance'             => 'مسافة من الجسم',
'exif-meteringmode'                => 'طور القياس بالمتر',
'exif-lightsource'                 => 'مصدر النور',
'exif-flash'                       => 'فلاش',
'exif-focallength'                 => 'البعد البؤرى  للعدسة',
'exif-focallength-format'          => '$1 ملم',
'exif-subjectarea'                 => 'منطقة الجسم',
'exif-flashenergy'                 => 'طاقة الفلاش',
'exif-spatialfrequencyresponse'    => 'استجابة التردد الفراغي',
'exif-focalplanexresolution'       => 'تحليل المستوى البؤرى X',
'exif-focalplaneyresolution'       => 'تحليل المستوى البؤرى Y',
'exif-focalplaneresolutionunit'    => 'وحدة تحليل المستوى البؤرى',
'exif-subjectlocation'             => 'مكان الجسم',
'exif-exposureindex'               => 'فهرس التعرض',
'exif-sensingmethod'               => 'وسيلة الاستشعار',
'exif-filesource'                  => 'مصدر الملف',
'exif-scenetype'                   => 'نوع المشهد',
'exif-cfapattern'                  => 'نمط سى إف إيه',
'exif-customrendered'              => 'تظبيط الصورة حسب الطلب',
'exif-exposuremode'                => 'طريقة التعرض',
'exif-whitebalance'                => 'توازن الأبيض',
'exif-digitalzoomratio'            => 'نسبة الزوم الرقمية',
'exif-focallengthin35mmfilm'       => 'البعد البؤرى فى فيلم 35 مم',
'exif-scenecapturetype'            => 'نوع تصوير المشهد',
'exif-gaincontrol'                 => 'التحكم فى المشهد',
'exif-contrast'                    => 'التعارض',
'exif-saturation'                  => 'التشبع',
'exif-sharpness'                   => 'الحده',
'exif-devicesettingdescription'    => 'وصف ظبط الأداة',
'exif-subjectdistancerange'        => 'المسافه اللى بين  الهدف و الكاميرا',
'exif-imageuniqueid'               => 'رقم الصورة الفريد',
'exif-gpsversionid'                => 'نسخة علامة ال چى بى إس',
'exif-gpslatituderef'              => 'شمال أو جنوب خطوط العرض',
'exif-gpslatitude'                 => 'خط العرض',
'exif-gpslongituderef'             => 'خط الطول الشرقى أو الغربى',
'exif-gpslongitude'                => 'خط الطول',
'exif-gpsaltituderef'              => 'مرجع الارتفاع',
'exif-gpsaltitude'                 => 'الارتفاع',
'exif-gpstimestamp'                => 'وقت ال چى پى إس (ساعه ذريه)',
'exif-gpssatellites'               => 'الأقمار الصناعيه المستخدمه للقياس',
'exif-gpsstatus'                   => 'حالة جهاز الاستقبال',
'exif-gpsmeasuremode'              => 'طريقة القياس',
'exif-gpsdop'                      => 'دقة القياس',
'exif-gpsspeedref'                 => 'وحدة السرعة',
'exif-gpsspeed'                    => 'سرعة مستقبل ال چى بى إس',
'exif-gpstrackref'                 => 'المرجع لاتجاه الحركة',
'exif-gpstrack'                    => 'اتجاه الحركه',
'exif-gpsimgdirectionref'          => 'المرجع لاتجاه الصوره',
'exif-gpsimgdirection'             => 'اتجاه الصوره',
'exif-gpsmapdatum'                 => 'بيانات استطلاع الجيوديسيك المستخدمة',
'exif-gpsdestlatituderef'          => 'المرجع لخط عرض الهدف',
'exif-gpsdestlatitude'             => 'خط عرض الهدف',
'exif-gpsdestlongituderef'         => 'المرجع لخط طول الهدف',
'exif-gpsdestlongitude'            => 'خط طول الهدف',
'exif-gpsdestbearingref'           => 'المرجع لتحمل الهدف',
'exif-gpsdestbearing'              => 'تحمل الهدف',
'exif-gpsdestdistanceref'          => 'المرجع للمسافه للهدف',
'exif-gpsdestdistance'             => 'المسافه للهدف',
'exif-gpsprocessingmethod'         => 'اسم طريقة معالجة جى بى إس',
'exif-gpsareainformation'          => 'اسم لمنطقة ال چى پى إس',
'exif-gpsdatestamp'                => 'تاريخ GPS',
'exif-gpsdifferential'             => 'تصحيح GPS التفاضلي',

# EXIF attributes
'exif-compression-1' => 'مش مضغوط',
'exif-compression-6' => 'جيه بى إى جي',

'exif-photometricinterpretation-2' => 'آر جى بى',
'exif-photometricinterpretation-6' => 'واى سب سر',

'exif-unknowndate' => 'تاريخ مش معروف',

'exif-orientation-1' => 'عادي',
'exif-orientation-2' => 'دار بالعرض',
'exif-orientation-3' => 'دار 180°',
'exif-orientation-4' => 'دار بالطول',
'exif-orientation-5' => 'اتلفت 90° CW 90° CW و اتقلب على جنبه',
'exif-orientation-6' => 'اتلفت 90° CW',
'exif-orientation-7' => 'اتلفت 90° CW 90° CW و اتقلب على راسه',
'exif-orientation-8' => 'اتلفت 90° عكس عقارب الساعة',

'exif-planarconfiguration-1' => 'صيغه تخينه',
'exif-planarconfiguration-2' => 'الصيغه المستويه',

'exif-componentsconfiguration-0' => 'مش موجود',

'exif-exposureprogram-0' => 'مش معروف',
'exif-exposureprogram-1' => 'يدوي',
'exif-exposureprogram-2' => 'برنامج عادى',
'exif-exposureprogram-3' => 'أولوية القافل',
'exif-exposureprogram-4' => 'أولوية القفل',
'exif-exposureprogram-5' => 'برنامج صنع (بيميل ناحية عمق الفيلد)',
'exif-exposureprogram-6' => 'برنامج الفعل (بيميل ناحية سرعة القفل)',
'exif-exposureprogram-7' => 'وضع البورتريه (لصور القفل مع الخلفية بعيدة عن البؤرة)',
'exif-exposureprogram-8' => 'وضع الأرضية (لصور الأرضية مع الخلفية فى البؤرة)',

'exif-subjectdistance-value' => '$1 متر',

'exif-meteringmode-0'   => 'مش معروف',
'exif-meteringmode-1'   => 'متوسط',
'exif-meteringmode-2'   => 'متوسط موزون بالمركز',
'exif-meteringmode-3'   => 'بقعة',
'exif-meteringmode-4'   => 'مالتى سبوت',
'exif-meteringmode-5'   => 'نمط',
'exif-meteringmode-6'   => 'جزئي',
'exif-meteringmode-255' => 'تاني',

'exif-lightsource-0'   => 'مش معروف',
'exif-lightsource-1'   => 'نورالنهار',
'exif-lightsource-2'   => 'فلورسنت',
'exif-lightsource-3'   => 'تنجستين (ضوء مشع)',
'exif-lightsource-4'   => 'فلاش',
'exif-lightsource-9'   => 'جو صحو',
'exif-lightsource-10'  => 'جو مغيم',
'exif-lightsource-11'  => 'ضل',
'exif-lightsource-12'  => 'فلورسنت نور النهار (D 5700 – 7100K)',
'exif-lightsource-13'  => 'فلورسنت نهار أبيض (N 4600 – 5400K)',
'exif-lightsource-14'  => 'فلورسنت أبيض كوول(W 3900 – 4500K)',
'exif-lightsource-15'  => 'فلورسنت أبيض (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'ضوء قياسى  A',
'exif-lightsource-18'  => 'ضوء قياسى B',
'exif-lightsource-19'  => 'ضوء قياسى C',
'exif-lightsource-20'  => 'دي55',
'exif-lightsource-21'  => 'دي65',
'exif-lightsource-22'  => 'دي75',
'exif-lightsource-23'  => 'دي50',
'exif-lightsource-24'  => 'تنجستين ستوديو أيزو',
'exif-lightsource-255' => 'مصدر  نور تانى',

# Flash modes
'exif-flash-fired-0'    => 'الفلاش ما بدأش',
'exif-flash-fired-1'    => 'الفلاش ابتدى',
'exif-flash-return-0'   => 'مفيش دالة كشف رجوع وميض',
'exif-flash-return-2'   => 'ضوء رجوع الوميض ما اتكشفش',
'exif-flash-return-3'   => 'ضوء رجوع الوميض تم اتكشف',
'exif-flash-mode-1'     => 'بدء فلاش إجبارى',
'exif-flash-mode-2'     => 'ضغط فلاش إجبارى',
'exif-flash-mode-3'     => 'نمط أوتوماتيك',
'exif-flash-function-1' => 'لا وظيفة فلاش',
'exif-flash-redeye-1'   => 'نمط اختزال العين الحمرا',

'exif-focalplaneresolutionunit-2' => 'بوصة',

'exif-sensingmethod-1' => 'مش متعرف',
'exif-sensingmethod-2' => 'حساس لون المساحة من راق واحد',
'exif-sensingmethod-3' => 'حساس لون المساحة من راقين',
'exif-sensingmethod-4' => 'حساس لون المساحة من تلات راقات',
'exif-sensingmethod-5' => 'حساس لون مساحة متتابع',
'exif-sensingmethod-7' => 'حساس بتلات خطوط',
'exif-sensingmethod-8' => 'حساس لون خطى متتابع',

'exif-filesource-3' => 'دى إس سي',

'exif-scenetype-1' => 'صورة متاخدة على طول',

'exif-customrendered-0' => 'عملية عادية',
'exif-customrendered-1' => 'عملية حسب الطلب',

'exif-exposuremode-0' => 'تعرض أوتوماتيكي',
'exif-exposuremode-1' => 'تعرض باللإيد',
'exif-exposuremode-2' => 'اقواس أوتوماتيكي',

'exif-whitebalance-0' => 'توازن الأبيض اوتوماتيكي',
'exif-whitebalance-1' => 'توازن الأبيض بالإيد',

'exif-scenecapturetype-0' => 'مظبوط',
'exif-scenecapturetype-1' => 'أرضية',
'exif-scenecapturetype-2' => 'بورتوريه',
'exif-scenecapturetype-3' => 'منظر بالليل',

'exif-gaincontrol-0' => 'مافيش',
'exif-gaincontrol-1' => 'تحكم لفوق واطي',
'exif-gaincontrol-2' => 'تحكم لفوق عالي',
'exif-gaincontrol-3' => 'تحكم تحت واطي',
'exif-gaincontrol-4' => 'تحكم تحت  عالي',

'exif-contrast-0' => 'طبيعي',
'exif-contrast-1' => 'ناعم',
'exif-contrast-2' => 'ناشف',

'exif-saturation-0' => 'عادي',
'exif-saturation-1' => 'تشبع واطي',
'exif-saturation-2' => 'تشبع عالي',

'exif-sharpness-0' => 'عادي',
'exif-sharpness-1' => 'ناعم',
'exif-sharpness-2' => 'ناشف',

'exif-subjectdistancerange-0' => 'مش معروف',
'exif-subjectdistancerange-1' => 'ماكرو',
'exif-subjectdistancerange-2' => 'منظر من قريب',
'exif-subjectdistancerange-3' => 'منظر من بعيد',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'دايرة العرض الشمالية',
'exif-gpslatitude-s' => 'دايرة العرض الجنوبية',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'خط الطول الشرقي',
'exif-gpslongitude-w' => 'خط الطول الغربي',

'exif-gpsstatus-a' => 'القياس شغال',
'exif-gpsstatus-v' => 'شمول القياس',

'exif-gpsmeasuremode-2' => 'قياس ببعدين',
'exif-gpsmeasuremode-3' => 'قياس  تلاتى الابعاد',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'كيلومتر فى الساعة',
'exif-gpsspeed-m' => 'ميل فى الساعة',
'exif-gpsspeed-n' => 'عقد',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'الاتجاه الحقيقي',
'exif-gpsdirection-m' => 'الاتجاه المغناطيسي',

# External editor support
'edit-externally'      => 'استعمل تطبيق من بره علشان تعدل الملف دا',
'edit-externally-help' => '(بص على [http://www.mediawiki.org/wiki/Manual:External_editors  تعليمات الاعداد] علشان معلومات اكتر.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'الكل',
'imagelistall'     => 'الكل',
'watchlistall2'    => 'الكل',
'namespacesall'    => 'الكل',
'monthsall'        => 'الكل',
'limitall'         => 'الكل',

# E-mail address confirmation
'confirmemail'             => 'اعمل تأكيد للأيميل بتاعك',
'confirmemail_noemail'     => 'إنت ما عندكش ايميل صحيح متسجل فى [[Special:Preferences|تفضيلاتك]].',
'confirmemail_text'        => '{{SITENAME}} بيطلب انك تعمل تأكيد للأيميل قبل ما تستعمل الخصايص المرتبطة بالايميل.
دوس على زرار التفعيل اللى تحت علشان يتبعتلك ايميل التأكيد.
الايميل ح يكون فيه لينك فيها كود تفعيل؛
دوس على اللينك  علشان نتأكد إن ايميلك صحيح.',
'confirmemail_pending'     => 'كود التأكيد خلاص اتبعت للأيميل بتاعك;
لو كنت لسة فاتح حسابك من شوية صغيرة، لو سمحت تستنى دقيقتين تلاتة قبل ما تطلب كود تاني.',
'confirmemail_send'        => 'ابعت كود التأكيد',
'confirmemail_sent'        => 'إيميل التأكيد خلاص اتبعت.',
'confirmemail_oncreate'    => 'كود التأكيد اتبعت للأيميل بتاعك.
مش لازم تستعمل الكود دا علشان تسجل دخولك ،بس ح تحتاج تدخله بعدين قبل ما تقدر  تستفيد من أى خاصية مربوطة بالايميل فى الويكي.',
'confirmemail_sendfailed'  => '{{SITENAME}} ماقدرش يبعت ايميل التأكيد.
لو سمحت تتأكد من الايميل بتاعك.

الغلط اللى حصل: $1',
'confirmemail_invalid'     => 'كود تفعيل غلط.
يمكن صلاحيته تكون انتهت.',
'confirmemail_needlogin'   => 'لازم $1 علشان تأكد الايميل بتاعك.',
'confirmemail_success'     => 'الايميل بتاعك اتأكد خلاص.
ممكن دلوقتى تسجل دخولك و تستمتع بالويكي.',
'confirmemail_loggedin'    => 'الايميل بتاعك اتأكد خلاص.',
'confirmemail_error'       => 'حصلت حاجة غلط و احنا بنحفظ التأكيد بتاعك.',
'confirmemail_subject'     => 'تأكيد الايميل من {{SITENAME}}',
'confirmemail_body'        => 'فى واحد، ممكن يكون إنتا، من عنوان الأيبى $1،
فتح حساب "$2" بعنوان الايميل دا فى {{SITENAME}}.

علشان نتأكد أن  الحساب دا بتاعك فعلا و علشان كمان تفعيل خواص الايميل فى {{SITENAME}}، افتح اللينك دى فى البراوزر بتاعك :

$3

إذا *ماكنتش* إنتا اللى فتحت الحساب ، دوس على اللينك دى علشان تلغى تأكيد الايميل
:

$5

كود التفعيل دا ح ينتهى $4.',
'confirmemail_invalidated' => 'تأكيد عنوان الايميل اتلغى',
'invalidateemail'          => 'إلغى تأكيد الايميل',

# Scary transclusion
'scarytranscludedisabled' => '[التضمين  فى الإنترويكى متعطل]',
'scarytranscludefailed'   => '[التدوير على القالب فشل ل$1]',
'scarytranscludetoolong'  => '[عنوان طويل جدا]',

# Trackbacks
'trackbackbox'      => 'التراكباك بتاع الصفحة دي:<br />
$1',
'trackbackremove'   => '([$1 امسح])',
'trackbacklink'     => 'تراكباك',
'trackbackdeleteok' => 'التراكباك اتمسح بنجاح.',

# Delete conflict
'deletedwhileediting' => "'''تحذير''':  الصفحة دى اتمسحت بعد ما بدأت أنت  فى تحريرها!",
'confirmrecreate'     => "اليوزر [[User:$1|$1]] ([[User talk:$1|مناقشة]]) مسح المقالة دى بعد ما انت بدأت فى تحريرها علشان:
:''$2''
لو سمحت تتأكد من أنك عايز تبتدى المقالة دى تاني.",
'recreate'            => 'ابتدى تاني',

'unit-pixel' => 'بيكس',

# action=purge
'confirm_purge_button' => 'طيب',
'confirm-purge-top'    => 'امسح الكاش بتاع الصفحة دي؟',
'confirm-purge-bottom' => 'إفراغ كاش صفحة يمحو الكاش ويجبر أحدث نسخة على الظهور.',

# Separators for various lists, etc.
'semicolon-separator' => '؛&#32;',
'comma-separator'     => '،&#32;',

# Multipage image navigation
'imgmultipageprev' => '← الصفحة اللى فاتت',
'imgmultipagenext' => 'الصفحه اللى بعد كده →',
'imgmultigo'       => 'روح!',
'imgmultigoto'     => 'روح لصفحة $1',

# Table pager
'ascending_abbrev'         => 'طالع',
'descending_abbrev'        => 'نازل',
'table_pager_next'         => 'الصفحه اللى بعد كده',
'table_pager_prev'         => 'الصفحة اللى فاتت',
'table_pager_first'        => 'أول صفحة',
'table_pager_last'         => 'آخر صفحة',
'table_pager_limit'        => 'اعرض $1 عنصر فى الصفحة',
'table_pager_limit_submit' => 'روح',
'table_pager_empty'        => 'ما فى ش نتايج',

# Auto-summaries
'autosumm-blank'   => 'مسح كل اللى فى الصفحة',
'autosumm-replace' => "تبديل الصفحة ب'$1'",
'autoredircomment' => 'تحويل لـ [[$1]]',
'autosumm-new'     => "ابتدا صفحه جديده بـ '$1'",

# Size units
'size-bytes'     => '$1 بايت',
'size-kilobytes' => '$1 كيلوبايت',
'size-megabytes' => '$1 ميجابايت',
'size-gigabytes' => '$1 جيجابايت',

# Live preview
'livepreview-loading' => 'تحميل…',
'livepreview-ready'   => 'تحميل… جاهز!',
'livepreview-failed'  => 'البروفة الحية مانفعتش!
جرب البروفة العادية.',
'livepreview-error'   => 'الاتصال مانفعش: $1 "$2"
جرب البروفة العادية.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'التغييرات الأحدث من $1 {{PLURAL:$1|ثانية|ثانية}} ثانية ممكن ما تظهرش فى اللستة دي.',
'lag-warn-high'   => 'علشان فى تأخير كبير فى تحديث قاعدة البيانات بتاعة السيرفر،  التعديلات  اللى أحدث من $1 {{PLURAL:$1|ثانية|ثانية}}
ممكن ما تظهرش فى اللستة دى.',

# Watchlist editor
'watchlistedit-numitems'       => 'لستة المراقبة بتاعتك  فيها{{PLURAL:$1|عنوان واحد|$1 عنوان}}، من غير صفحات المناقشة.',
'watchlistedit-noitems'        => 'لستة الرقابة بتاعتك  مافيهاش ولا عنوان.',
'watchlistedit-normal-title'   => 'تعديل لستة المراقبة',
'watchlistedit-normal-legend'  => 'شيل العناوين من لستة المراقبة',
'watchlistedit-normal-explain' => 'العناوين فى لستة المراقبة بتاعتك معروضة تحت.
علشان تشيل عنوان، دوس على الصندوق اللى جنبه، ودوس على شيل العناوين"{{int:Watchlistedit-normal-submit}}".
ممكن كمان [[Special:Watchlist/raw|تعديل اللستة الخام]].',
'watchlistedit-normal-submit'  => 'شيل العناوين',
'watchlistedit-normal-done'    => '{{PLURAL:$1|عنوان واحد|$1 عنوان}} اتشال من لستة المراقبة بتاعتك:',
'watchlistedit-raw-title'      => 'تعديل لستة المراقبة الخام',
'watchlistedit-raw-legend'     => 'تعديل لستة المراقبة الخام',
'watchlistedit-raw-explain'    => 'العناوين فى لستة مراقبتك معروضه تحت، وممكن تعدلها لما تزود او تشيل من اللستة؛
عنوان واحد فى السطر.
لما تخلص، دوس تحديث لستة المراقبه "{{int:Watchlistedit-raw-submit}}".
ممكن كمان [[Special:Watchlist/edit|تستعمل المحرر القياسي]].',
'watchlistedit-raw-titles'     => 'العناوين:',
'watchlistedit-raw-submit'     => 'تحديث لستة المراقبة',
'watchlistedit-raw-done'       => 'لستة المراقبة بتاعتك اتحدثت خلاص.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|عنوان واحد|$1 عنوان}} اتزود:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|عنوان واحد|$1 عنوان}} اتشال:',

# Watchlist editing tools
'watchlisttools-view' => 'عرض التعديلات المرتبطه',
'watchlisttools-edit' => 'عرض وتعديل لستة الصفحات اللى باراقبها',
'watchlisttools-raw'  => 'عدل لستة المراقبه الخام',

# Iranian month names
'iranian-calendar-m1'  => 'فروردین',
'iranian-calendar-m2'  => 'أردیبهشت',
'iranian-calendar-m3'  => 'خرداد',
'iranian-calendar-m4'  => 'تیر',
'iranian-calendar-m5'  => 'امرداد',
'iranian-calendar-m6'  => 'شهریور',
'iranian-calendar-m7'  => 'مهر',
'iranian-calendar-m8'  => 'آبان',
'iranian-calendar-m9'  => 'آذر',
'iranian-calendar-m10' => 'دی',
'iranian-calendar-m11' => 'بهمن',
'iranian-calendar-m12' => 'إسفند',

# Hijri month names
'hijri-calendar-m1'  => 'محرم',
'hijri-calendar-m2'  => 'صفر',
'hijri-calendar-m3'  => 'ربيع الأول',
'hijri-calendar-m4'  => 'ربيع الثانى',
'hijri-calendar-m5'  => 'جمادى الأول',
'hijri-calendar-m6'  => 'جمادى الثانى',
'hijri-calendar-m7'  => 'رجب',
'hijri-calendar-m8'  => 'شعبان',
'hijri-calendar-m9'  => 'رمضان',
'hijri-calendar-m10' => 'شوال',
'hijri-calendar-m11' => 'ذو القعدة',
'hijri-calendar-m12' => 'ذو الحجة',

# Hebrew month names
'hebrew-calendar-m1'      => 'تيشرى',
'hebrew-calendar-m2'      => 'تيشفان',
'hebrew-calendar-m3'      => 'كيسليف',
'hebrew-calendar-m4'      => 'تيفيت',
'hebrew-calendar-m5'      => 'شيفات',
'hebrew-calendar-m6'      => 'أدار',
'hebrew-calendar-m6a'     => 'أدار الأول',
'hebrew-calendar-m6b'     => 'أدار الثانى',
'hebrew-calendar-m7'      => 'نيزان',
'hebrew-calendar-m8'      => 'أيار',
'hebrew-calendar-m9'      => 'سيفان',
'hebrew-calendar-m10'     => 'تموز',
'hebrew-calendar-m11'     => 'آف',
'hebrew-calendar-m12'     => 'أيلول',
'hebrew-calendar-m1-gen'  => 'تيشرى',
'hebrew-calendar-m2-gen'  => 'تيشفان',
'hebrew-calendar-m3-gen'  => 'كيسليف',
'hebrew-calendar-m4-gen'  => 'تيفيت',
'hebrew-calendar-m5-gen'  => 'شيفات',
'hebrew-calendar-m6-gen'  => 'أدار',
'hebrew-calendar-m6a-gen' => 'أدار الأول',
'hebrew-calendar-m6b-gen' => 'أدار الثانى',
'hebrew-calendar-m7-gen'  => 'نيزان',
'hebrew-calendar-m8-gen'  => 'أيار',
'hebrew-calendar-m9-gen'  => 'سيفان',
'hebrew-calendar-m10-gen' => 'تموز',
'hebrew-calendar-m11-gen' => 'آب',
'hebrew-calendar-m12-gen' => 'أيلول',

# Signatures
'timezone-utc' => 'يو تى سى',

# Core parser functions
'unknown_extension_tag' => 'تاج بتاع امتداد مش معروف "$1"',
'duplicate-defaultsort' => 'تحزير: زرار الترتيب الاوتوماتيكي"$2" بيوقف زرار الترتيب الاوتوماتيكي"$1" القديم.',

# Special:Version
'version'                          => 'نسخه',
'version-extensions'               => 'الامتدادات المتثبتة',
'version-specialpages'             => 'صفحات مخصوصة',
'version-parserhooks'              => 'خطاطيف البريزر',
'version-variables'                => 'المتغيرات',
'version-other'                    => 'تانية',
'version-mediahandlers'            => 'متحكمات الميديا',
'version-hooks'                    => 'الخطاطيف',
'version-extension-functions'      => 'وظايف الامتداد',
'version-parser-extensiontags'     => 'التاجز بتوع امتداد البريزر',
'version-parser-function-hooks'    => 'خطاطيف دالة المحلل',
'version-skin-extension-functions' => 'الوظايف بتاعة امتداد الواجهة',
'version-hook-name'                => 'اسم الخطاف',
'version-hook-subscribedby'        => 'اشتراك باسم',
'version-version'                  => '(نسخه $1)',
'version-license'                  => 'الترخيص',
'version-software'                 => 'السوفتوير المتستاب',
'version-software-product'         => 'المنتج',
'version-software-version'         => 'النسخه',

# Special:FilePath
'filepath'         => 'مسار ملف',
'filepath-page'    => 'الملف:',
'filepath-submit'  => 'المسار',
'filepath-summary' => 'الصفحة المخصوصة دى بتعرض المسار الكامل  بتاع ملف.
الصور بتتعرض  بدقة كاملة، أنواع الملفات التانية ح تشتغل فى البرنامج بتاعهم مباشرة.
دخل اسم الملف  من غير البريفيكس "{{ns:file}}:"',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'دور على الملفات المتكررة',
'fileduplicatesearch-summary'  => 'دور على الملفات المتكررة على اساس قيمة الهاش بتاعتها.

دخل اسم الملف من غير البريفكس "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'تدوير على متكرر',
'fileduplicatesearch-filename' => 'اسم الملف:',
'fileduplicatesearch-submit'   => 'تدوير',
'fileduplicatesearch-info'     => '$1 × $2 بكسل<br />حجم الملف: $3<br />نوع MIME: $4',
'fileduplicatesearch-result-1' => 'الملف "$1" ما لهو ش تكرار متطابق.',
'fileduplicatesearch-result-n' => 'الملف "$1" فيه {{PLURAL:$2|1 تكرار متطابق|$2 تكرار متطابق}}.',

# Special:SpecialPages
'specialpages'                   => 'صفح مخصوصه',
'specialpages-note'              => '----
* صفحات خاصة عادية.
* <strong class="mw-specialpagerestricted">صفحات خاصة للناس اللى مسموح لهم.</strong>',
'specialpages-group-maintenance' => 'تقارير الصيانة',
'specialpages-group-other'       => 'صفحات خاصه تا نيه',
'specialpages-group-login'       => 'ادخل / سجل',
'specialpages-group-changes'     => 'السجلات واحدث التغييرات',
'specialpages-group-media'       => 'تقارير الميديا وعمليات التحميل',
'specialpages-group-users'       => 'اليوزرز و الحقوق',
'specialpages-group-highuse'     => 'صفحات بتستخدم كتير',
'specialpages-group-pages'       => 'ليستات الصفحات',
'specialpages-group-pagetools'   => 'أدوات الصفحات',
'specialpages-group-wiki'        => 'بيانات وأدوات الويكى',
'specialpages-group-redirects'   => 'صفحات  التحويل الخاصه',
'specialpages-group-spam'        => 'أدوات السبام',

# Special:BlankPage
'blankpage'              => 'صفحة فاضية',
'intentionallyblankpage' => 'الصفحة دى متسابة فاضية بالقصد',

# External image whitelist
'external_image_whitelist' => ' # سيب السطر دا زى ما هو كدا<pre>
#حط حتت التعبيرات المنتظمه (بس الجزء اللى بيروح بين //) تحت
# ح يحصل تطابق بينهم و بين ال URLs بتاع الصور الخارجيه (هوت لينك)
#اذا حصل تطابق ح يتعرضو ك صور ، و اذا ما حصلش ف ح تظهر بس لينك للصوره
#السطور اللى بتبتدى بـ # بتتعامل كأنها تعليقات
#دا ما بيتأثرش بحالة الحروف

#حط كل حتت الريجيكس فوق . سيب السطر دا زى ما هو كدا</pre>',

# Special:Tags
'tags'                    => 'وسوم التغيير الصحيحة',
'tag-filter'              => 'فلتر [[Special:Tags|الوسم]]:',
'tag-filter-submit'       => 'فلتر',
'tags-title'              => 'وسوم',
'tags-intro'              => 'الصفحه دى فيها ليستة الوسوم اللى ممكن البرنامج يعلم عى التعديل بيها، و معانيهم',
'tags-tag'                => 'اسم الوسم',
'tags-display-header'     => 'المظهر على ليستات التغيير',
'tags-description-header' => 'وصف كامل للمعنى',
'tags-hitcount-header'    => 'تغييرات موسومة',
'tags-edit'               => 'تعديل',
'tags-hitcount'           => '$1 {{PLURAL:$1|تغيير|تغيير}}',

# Special:ComparePages
'compare-page1'  => 'صفحه 1',
'compare-page2'  => 'صفحه 2',
'compare-submit' => 'قارن',

# Database error messages
'dberr-header'      => 'الويكى دا فيه مشكله',
'dberr-problems'    => 'متأسفين، السايت دا بيعانى من صعوبات فنيه',
'dberr-again'       => 'حاول تستنا كام دقيقه و بعدين اعمل تحميل من تانى',
'dberr-info'        => '(مش قادرين نتصل بـ السيرفر بتاع قاعدة البيانات: $1)',
'dberr-usegoogle'   => 'ممكن تحاول تدور باستعمال جوجل دلوقتى.',
'dberr-outofdate'   => 'خد بالك فهارس المحتوى بتاعنا اللى عندهم ممكن تكون مش متحدثه.',
'dberr-cachederror' => 'دى نسخه متخزنه من الصفحه المطلوبه، و ممكن ما تكونش متحدثه.',

# HTML forms
'htmlform-invalid-input'       => 'فى مشاكل فى المدخلات بتاعتك',
'htmlform-select-badoption'    => 'القيمه اللى حددتها ما تنفعش كاختيار.',
'htmlform-int-invalid'         => 'القيمه اللى حددتها ما هياش عدد صحيح',
'htmlform-float-invalid'       => 'القيمه اللى انتا حددتها ما هياش رقم',
'htmlform-int-toolow'          => 'القيمه اللى حددتها اصغر من الحد الادنى اللى هو $1',
'htmlform-int-toohigh'         => 'القيمه اللى حددتها اكبر من الحد الاقصى اللى هو $1',
'htmlform-submit'              => 'تقديم',
'htmlform-reset'               => 'الرجوع فى التغييرات',
'htmlform-selectorother-other' => 'تانيين',

);
