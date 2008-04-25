<?php
/** Egyptian Spoken Arabic (مصرى)
 *
 * @addtogroup Language
 *
 * @author Ghaly
 * @author Alnokta
 * @author Nike
 * @author Meno25
 */

$fallback = 'ar';

$namespaceNames = array(
	NS_MEDIA          => 'ميديا',
	NS_SPECIAL        => 'خاص',
	NS_TALK           => 'نقاش',
	NS_USER           => 'مستخدم',
	NS_USER_TALK      => 'نقاش_المستخدم',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => 'نقاش_$1',
	NS_IMAGE          => 'صورة',
	NS_IMAGE_TALK     => 'نقاش_الصورة',
	NS_MEDIAWIKI      => 'ميدياويكى',
	NS_MEDIAWIKI_TALK => 'نقاش_ميدياويكى',
	NS_TEMPLATE       => 'قالب',
	NS_TEMPLATE_TALK  => 'نقاش_القالب',
	NS_HELP           => 'مساعدة',
	NS_HELP_TALK      => 'نقاش_المساعدة',
	NS_CATEGORY       => 'تصنيف',
	NS_CATEGORY_TALK  => 'نقاش_التصنيف',
);

$namespaceAliases = array(
	'ملف'             => NS_MEDIA,
);

$magicWords = array(
	'redirect'            => array( '0', '#تحويل', '#REDIRECT' ),
	'notoc'               => array( '0', '__لافهرس__', '__NOTOC__' ),
	'nogallery'           => array( '0', '__لامعرض__', '__NOGALLERY__' ),
	'forcetoc'            => array( '0', '__لصق_فهرس__', '__FORCETOC__' ),
	'toc'                 => array( '0', '__فهرس__', '__TOC__' ),
	'noeditsection'       => array( '0', '__لاتحريرقسم__', '__NOEDITSECTION__' ),
	'currentmonth'        => array( '1', 'شهر_حالى', 'شهر', 'CURRENTMONTH' ),
	'currentmonthname'    => array( '1', 'اسم_الشهر_الحالى', 'اسم_شهر_حالى', 'اسم_شهر', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen' => array( '1', 'اسم_الشهر_الحالى_المولد', 'اسم_شهر_حالى_مولد', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'  => array( '1', 'اختصار_الشهر_الحالى', 'اختصار_شهر_حالى', 'CURRENTMONTHABBREV' ),
	'currentday'          => array( '1', 'يوم_حالى', 'يوم', 'CURRENTDAY' ),
	'currentday2'         => array( '1', 'يوم_حالى2', 'يوم2', 'CURRENTDAY2' ),
	'currentdayname'      => array( '1', 'اسم_اليوم_الحالى', 'اسم_يوم_حالى', 'اسم_يوم', 'CURRENTDAYNAME' ),
	'currentyear'         => array( '1', 'عام_حالى', 'عام', 'CURRENTYEAR' ),
	'currenttime'         => array( '1', 'وقت_حالى', 'وقت', 'CURRENTTIME' ),
	'currenthour'         => array( '1', 'ساعة_حالية', 'ساعة', 'CURRENTHOUR' ),
	'localmonth'          => array( '1', 'شهر_محلى', 'LOCALMONTH' ),
	'localmonthname'      => array( '1', 'اسم_الشهر_المحلى', 'اسم_شهر_محلى', 'LOCALMONTHNAME' ),
	'localmonthnamegen'   => array( '1', 'اسم_الشهر_المحلى_المولد', 'اسم_شهر_محلى_مولد', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'    => array( '1', 'اختصار_الشهر_المحلى', 'اختصار_شهر_محلى', 'LOCALMONTHABBREV' ),
	'localday'            => array( '1', 'يوم_محلى', 'LOCALDAY' ),
	'localday2'           => array( '1', 'يوم_محلى2', 'LOCALDAY2' ),
	'localdayname'        => array( '1', 'اسم_اليوم_المحلى', 'اسم_يوم_محلى', 'LOCALDAYNAME' ),
	'localyear'           => array( '1', 'عام_محلى', 'LOCALYEAR' ),
	'localtime'           => array( '1', 'وقت_محلى', 'LOCALTIME' ),
	'localhour'           => array( '1', 'ساعة_محلية', 'LOCALHOUR' ),
	'numberofpages'       => array( '1', 'عدد_الصفحات', 'عدد_صفحات', 'NUMBEROFPAGES' ),
	'numberofarticles'    => array( '1', 'عدد_المقالات', 'عدد_مقالات', 'NUMBEROFARTICLES' ),
	'numberoffiles'       => array( '1', 'عدد_الملفات', 'عدد_ملفات', 'NUMBEROFFILES' ),
	'numberofusers'       => array( '1', 'عدد_المستخدمين', 'عدد_مستخدمين', 'NUMBEROFUSERS' ),
	'numberofedits'       => array( '1', 'عدد_التعديلات', 'عدد_تعديلات', 'NUMBEROFEDITS' ),
	'pagename'            => array( '1', 'اسم_الصفحة', 'اسم_صفحة', 'PAGENAME' ),
	'pagenamee'           => array( '1', 'عنوان_الصفحة', 'عنوان_صفحة', 'PAGENAMEE' ),
	'namespace'           => array( '1', 'نطاق', 'NAMESPACE' ),
	'namespacee'          => array( '1', 'عنوان_نطاق', 'NAMESPACEE' ),
	'talkspace'           => array( '1', 'نطاق_النقاش', 'نطاق_نقاش', 'TALKSPACE' ),
	'talkspacee'          => array( '1', 'عنوان_النقاش', 'عنوان_نقاش', 'TALKSPACEE' ),
	'subjectspace'        => array( '1', 'نطاق_الموضوع', 'نطاق_المقالة', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'       => array( '1', 'عنوان_نطاق_الموضوع', 'عنوان_نطاق_المقالة SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'        => array( '1', 'اسم_الصفحة_الكامل', 'اسم_صفحة_كامل', 'اسم_كامل', 'FULLPAGENAME' ),
	'fullpagenamee'       => array( '1', 'عنوان_الصفحة_الكامل', 'عنوان_صفحة_كامل', 'عنوان_كامل', 'FULLPAGENAMEE' ),
	'subpagename'         => array( '1', 'اسم_الصفحة_الفرعي', 'اسم_صفحة_فرعي', 'SUBPAGENAME' ),
	'subpagenamee'        => array( '1', 'عنوان_الصفحة_الفرعى', 'عنوان_صفحة_فرعى', 'SUBPAGENAMEE' ),
	'basepagename'        => array( '1', 'اسم_الصفحة_الأساسى', 'اسم_صفحة_أساسى', 'BASEPAGENAME' ),
	'basepagenamee'       => array( '1', 'عنوان_الصفحة_الأساسى', 'عنوان_صفحة_أساسى', 'BASEPAGENAMEE' ),
	'talkpagename'        => array( '1', 'اسم_صفحة_النقاش', 'اسم_صفحة_نقاش', 'TALKPAGENAME' ),
	'talkpagenamee'       => array( '1', 'عنوان_صفحة_النقاش', 'عنوان_صفحة_نقاش', 'TALKPAGENAMEE' ),
	'subjectpagename'     => array( '1', 'اسم_صفحة_الموضوع', 'اسم_صفحة_المقالة', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'    => array( '1', 'عنوان_صفحة_الموضوع', 'عنوان_صفحة_المقالة SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                 => array( '0', 'رسالة:', 'MSG:' ),
	'subst'               => array( '0', 'نسخ:', 'إحلال:', 'SUBST:' ),
	'msgnw'               => array( '0', 'مصدر:', 'مصدر_قالب:', 'MSGNW:' ),
	'img_thumbnail'       => array( '1', 'تصغير', 'thumbnail', 'thumb' ),
	'img_manualthumb'     => array( '1', 'تصغير=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'           => array( '1', 'يمين', 'right' ),
	'img_left'            => array( '1', 'يسار', 'left' ),
	'img_none'            => array( '1', 'بدون', 'بلا', 'none' ),
	'img_width'           => array( '1', '$1بك', '$1px' ),
	'img_center'          => array( '1', 'مركز', 'center', 'centre' ),
	'img_framed'          => array( '1', 'إطار', 'framed', 'enframed', 'frame' ),
	'img_frameless'       => array( '1', 'لاإطار', 'frameless' ),
	'img_page'            => array( '1', 'صفحة=$1', 'صفحة $1', 'page=$1', 'page $1' ),
	'img_upright'         => array( '1', 'معدول', 'معدول=$1', 'معدول $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'          => array( '1', 'حد', 'حدود', 'border' ),
	'img_baseline'        => array( '1', 'خط_أساسى', 'baseline' ),
	'img_sub'             => array( '1', 'فرعى', 'sub' ),
	'img_super'           => array( '1', 'سوبر', 'سب', 'super', 'sup' ),
	'img_top'             => array( '1', 'أعلى', 'top' ),
	'img_text_top'        => array( '1', 'نص_أعلى', 'text-top' ),
	'img_middle'          => array( '1', 'وسط', 'middle' ),
	'img_bottom'          => array( '1', 'أسفل', 'bottom' ),
	'img_text_bottom'     => array( '1', 'نص_أسفل', 'text-bottom' ),
	'int'                 => array( '0', 'محتوى:', 'INT:' ),
	'sitename'            => array( '1', 'اسم_الموقع', 'اسم_موقع', 'SITENAME' ),
	'ns'                  => array( '0', 'نط:', 'NS:' ),
	'localurl'            => array( '0', 'مسار_محلى:', 'LOCALURL:' ),
	'localurle'           => array( '0', 'عنوان_المسار_المحلى:', 'عنوان_مسار_محلى:', 'LOCALURLE:' ),
	'server'              => array( '0', 'خادم', 'SERVER' ),
	'servername'          => array( '0', 'اسم_الخادم', 'اسم_خادم', 'SERVERNAME' ),
	'scriptpath'          => array( '0', 'مسار_السكريبت', 'مسار_سكريبت', 'SCRIPTPATH' ),
	'grammar'             => array( '0', 'قواعد_اللغة:', 'قواعد_لغة: GRAMMAR:' ),
	'notitleconvert'      => array( '0', '__لاتحويل_عنوان__', '__لاتع__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'    => array( '0', '__لاتحويل_محتوى__', '__لاتم__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'         => array( '1', 'أسبوع_حالى', 'أسبوع', 'CURRENTWEEK' ),
	'currentdow'          => array( '1', 'يوم_حالي_مأ', 'CURRENTDOW' ),
	'localweek'           => array( '1', 'أسبوع_محلى', 'LOCALWEEK' ),
	'localdow'            => array( '1', 'يوم_محلى_مأ', 'LOCALDOW' ),
	'revisionid'          => array( '1', 'رقم_النسخة', 'رقم_نسخة', 'REVISIONID' ),
	'revisionday'         => array( '1', 'يوم_النسخة', 'يوم_نسخة', 'REVISIONDAY' ),
	'revisionday2'        => array( '1', 'يوم_النسخة2', 'يوم_نسخة2', 'REVISIONDAY2' ),
	'revisionmonth'       => array( '1', 'شهر_النسخة', 'شهر_نسخة', 'REVISIONMONTH' ),
	'revisionyear'        => array( '1', 'عام_النسخة', 'عام_نسخة', 'REVISIONYEAR' ),
	'revisiontimestamp'   => array( '1', 'طابع_وقت_النسخة', 'طابع_وقت_نسخة', 'REVISIONTIMESTAMP' ),
	'plural'              => array( '0', 'جمع:', 'PLURAL:' ),
	'fullurl'             => array( '0', 'عنوان_كامل:', 'FULLURL:' ),
	'fullurle'            => array( '0', 'مسار_كامل:', 'FULLURLE:' ),
	'lcfirst'             => array( '0', 'عنوان_كبير:', 'LCFIRST:' ),
	'ucfirst'             => array( '0', 'عنوان_صغير:', 'UCFIRST:' ),
	'lc'                  => array( '0', 'صغير:', 'LC:' ),
	'uc'                  => array( '0', 'كبير:', 'UC:' ),
	'raw'                 => array( '0', 'خام:', 'RAW:' ),
	'displaytitle'        => array( '1', 'عرض_العنوان', 'عرض_عنوان', 'DISPLAYTITLE' ),
	'rawsuffix'           => array( '1', 'أر', 'آر', 'R' ),
	'newsectionlink'      => array( '1', '__وصلة_قسم_جديد__', '__NEWSECTIONLINK__' ),
	'currentversion'      => array( '1', 'إصدار_حالي', 'CURRENTVERSION' ),
	'urlencode'           => array( '0', 'كود_المسار:', 'كود_مسار:', 'URLENCODE:' ),
	'anchorencode'        => array( '0', 'كود_الأنكور', 'كود_أنكور', 'ANCHORENCODE' ),
	'currenttimestamp'    => array( '1', 'طابع_الوقت_الحالي', 'طابع_وقت_حالي', 'CURRENTTIMESTAMP' ),
	'localtimestamp'      => array( '1', 'طابع_الوقت_المحلى', 'طابع_وقت_محلى', 'LOCALTIMESTAMP' ),
	'directionmark'       => array( '1', 'علامة_الاتجاه', 'علامة_اتجاه', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'            => array( '0', '#لغة:', '#LANGUAGE:' ),
	'contentlanguage'     => array( '1', 'لغة_المحتوى', 'لغة_محتوى', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'    => array( '1', 'صفحات_في_نطاق:', 'صفحات_في_نط:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'      => array( '1', 'عدد_الإداريين', 'عدد_إداريين', 'NUMBEROFADMINS' ),
	'formatnum'           => array( '0', 'صيغة_رقم', 'FORMATNUM' ),
	'padleft'             => array( '0', 'باد_يسار', 'PADLEFT' ),
	'padright'            => array( '0', 'باد_يمين', 'PADRIGHT' ),
	'special'             => array( '0', 'خاص', 'special' ),
	'defaultsort'         => array( '1', 'ترتيب_قياسى:', 'ترتيب_افتراضى:', 'مفتاح_ترتيب_قياسى:', 'مفتاح_ترتيب_افتراضى:', 'ترتيب_تصنيف_قياسى:', 'ترتيب_تصنيف_افتراضى:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'            => array( '0', 'مسار_الملف:', 'مسار_ملف:', 'FILEPATH:' ),
	'tag'                 => array( '0', 'وسم', 'tag' ),
	'hiddencat'           => array( '1', '__تصنيف_مخفي__', '__HIDDENCAT__' ),
	'pagesincategory'     => array( '1', 'صفحات_في_التصنيف', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'            => array( '1', 'حجم_الصفحة', 'حجم_صفحة', 'PAGESIZE' ),
);

$skinNames = array(
	'standard'    => 'كلاسيك',
	'nostalgia'   => 'نوستالجيا',
	'cologneblue' => 'كولون بلو',
	'monobook'    => 'مونوبوك',
	'myskin'      => 'ماى سكين',
	'chick'       => 'تشيك',
	'simple'      => 'سيمبل',
	'modern'      => 'مودرن',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'تحويلات_مزدوجة' ),
	'BrokenRedirects'           => array( 'تحويلات_مكسورة' ),
	'Disambiguations'           => array( 'توضيحات' ),
	'Userlogin'                 => array( 'دخول_المستخدم' ),
	'Userlogout'                => array( 'خروج_المستخدم' ),
	'CreateAccount'             => array( 'إنشاء_حساب' ),
	'Preferences'               => array( 'تفضيلات' ),
	'Watchlist'                 => array( 'قايمة_المراقبة' ),
	'Recentchanges'             => array( 'أحدث_التغييرات' ),
	'Upload'                    => array( 'رفع' ),
	'Imagelist'                 => array( 'قايمة_الصور' ),
	'Newimages'                 => array( 'صور_جديدة' ),
	'Listusers'                 => array( 'عرض_المستخدمين', 'قايمة_المستخدمين' ),
	'Listgrouprights'           => array( 'عرض_صلاحيات_المجموعات' ),
	'Statistics'                => array( 'إحصائيات' ),
	'Randompage'                => array( 'عشوائي', 'صفحة_عشوائية' ),
	'Lonelypages'               => array( 'صفحات_وحيدة', 'صفحات_يتيمة' ),
	'Uncategorizedpages'        => array( 'صفحات_غير_مصنفة' ),
	'Uncategorizedcategories'   => array( 'تصنيفات_غير_مصنفة' ),
	'Uncategorizedimages'       => array( 'صور_غير_مصنفة' ),
	'Uncategorizedtemplates'    => array( 'قوالب_غير_مصنفة' ),
	'Unusedcategories'          => array( 'تصنيفات_غير_مستخدمة' ),
	'Unusedimages'              => array( 'صور_غير_مستخدمة' ),
	'Wantedpages'               => array( 'صفحات_مطلوبة', 'وصلات_مكسورة' ),
	'Wantedcategories'          => array( 'تصنيفات_مطلوبة' ),
	'Mostlinked'                => array( 'الأكثر_وصلا' ),
	'Mostlinkedcategories'      => array( 'أكثر_التصنيفات_وصلا', 'أكثر_التصنيفات_استخداما' ),
	'Mostlinkedtemplates'       => array( 'أكثر_القوالب_وصلا', 'أكثر_القوالب_استخداما' ),
	'Mostcategories'            => array( 'أكثر_التصنيفات' ),
	'Mostimages'                => array( 'أكثر_الصور' ),
	'Mostrevisions'             => array( 'أكثر_المراجعات' ),
	'Fewestrevisions'           => array( 'أقل_المراجعات' ),
	'Shortpages'                => array( 'صفحات_قصيرة' ),
	'Longpages'                 => array( 'صفحات_طويلة' ),
	'Newpages'                  => array( 'صفحات_جديدة' ),
	'Ancientpages'              => array( 'صفحات_قديمة' ),
	'Deadendpages'              => array( 'صفحات_نهاية_مسدودة' ),
	'Protectedpages'            => array( 'صفحات_محمية' ),
	'Protectedtitles'           => array( 'عناوين_محمية' ),
	'Allpages'                  => array( 'كل_الصفحات' ),
	'Prefixindex'               => array( 'فهرس_بادئة' ),
	'Ipblocklist'               => array( 'قائمة_منع_أيبى' ),
	'Specialpages'              => array( 'صفحات_خاصة' ),
	'Contributions'             => array( 'مساهمات' ),
	'Emailuser'                 => array( 'مراسلة_المستخدم' ),
	'Confirmemail'              => array( 'تأكيد_البريد' ),
	'Whatlinkshere'             => array( 'ماذا_يصل_هنا' ),
	'Recentchangeslinked'       => array( 'أحدث_التغييرات_الموصولة', 'تغييرات_مرتبطة' ),
	'Movepage'                  => array( 'نقل_صفحة' ),
	'Blockme'                   => array( 'منعى' ),
	'Booksources'               => array( 'مصادر_كتاب' ),
	'Categories'                => array( 'تصنيفات' ),
	'Export'                    => array( 'تصدير' ),
	'Version'                   => array( 'إصدار' ),
	'Allmessages'               => array( 'كل_الرسايل' ),
	'Log'                       => array( 'سجل', 'سجلات' ),
	'Blockip'                   => array( 'منع_أيبى' ),
	'Undelete'                  => array( 'استرجاع' ),
	'Import'                    => array( 'استيراد' ),
	'Lockdb'                    => array( 'قفل_قب' ),
	'Unlockdb'                  => array( 'فتح_قب' ),
	'Userrights'                => array( 'صلاحيات_المستخدم' ),
	'MIMEsearch'                => array( 'بحث_ميم' ),
	'FileDuplicateSearch'       => array( 'بحث_ملف_مكرر' ),
	'Unwatchedpages'            => array( 'صفحات_غير_مراقبة' ),
	'Listredirects'             => array( 'عرض_التحويلات' ),
	'Revisiondelete'            => array( 'حذف_نسخة' ),
	'Unusedtemplates'           => array( 'قوالب_غير_مستخدمة' ),
	'Randomredirect'            => array( 'تحويلة_عشوائية' ),
	'Mypage'                    => array( 'صفحتى' ),
	'Mytalk'                    => array( 'نقاشى' ),
	'Mycontributions'           => array( 'مساهماتى' ),
	'Listadmins'                => array( 'عرض_الإداريين' ),
	'Listbots'                  => array( 'عرض_البوتات' ),
	'Popularpages'              => array( 'صفحات_مشهورة' ),
	'Search'                    => array( 'بحث' ),
	'Resetpass'                 => array( 'ضبط_كلمة_السر' ),
	'Withoutinterwiki'          => array( 'بدون_إنترويكى' ),
	'MergeHistory'              => array( 'دمج_التاريخ' ),
	'Filepath'                  => array( 'مسار_ملف' ),
	'Invalidateemail'           => array( 'تعطيل_البريد_الإلكترونى' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'حط خط تحت الوصلات:',

# Dates
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
'categories'             => 'تصانيف',
'category_header'        => 'الصفحات فى التصنيف "$1"',
'subcategories'          => 'التصنيفات الفرعيه',
'category-media-header'  => 'ملفات الميديا فى التصنيف "$1"',
'category-empty'         => "''التصنيف ده مافيهوش حاليا مقالات او ملفات ميديا.''",
'listingcontinuesabbrev' => 'متابعه',

'about'     => 'عن',
'newwindow' => '(بتفتح ويندو جديده)',
'cancel'    => 'كانسل',
'qbfind'    => 'تدوير',
'qbedit'    => 'عدل',
'mytalk'    => 'مناقشاتى',

'errorpagetitle'   => 'غلطه',
'returnto'         => 'ارجع ل $1.',
'tagline'          => 'من {{SITENAME}}',
'help'             => 'مساعده',
'search'           => 'تدوير',
'searchbutton'     => 'تدوير',
'searcharticle'    => 'روح',
'history'          => 'تاريخ الصفحه',
'history_short'    => 'تاريخ',
'printableversion' => 'نسخه للطبع',
'permalink'        => 'وصله مستديمه',
'edit'             => 'تعديل',
'editthispage'     => 'عدل الصفحه دى',
'delete'           => 'مسح',
'protect'          => 'حمايه',
'newpage'          => 'صفحه جديده',
'talkpage'         => 'ناقش الصفحه دى',
'talkpagelinktext' => 'مناقشه',
'personaltools'    => 'ادوات شخصيه',
'talk'             => 'مناقشه',
'views'            => 'مشاهده',
'toolbox'          => 'علبة العده',
'redirectedfrom'   => '(تحويل من $1)',
'redirectpagesub'  => 'صفحة تحويل',
'jumpto'           => 'روح على:',
'jumptonavigation' => 'ناڤيجيشن',
'jumptosearch'     => 'تدوير',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => ' عن {{SITENAME}}',
'aboutpage'            => 'Project:  معلومات عن',
'bugreports'           => 'بلاغ الاخطاء',
'bugreportspage'       => 'Project:تبليغ اخطاء',
'copyrightpage'        => '{{ns:project}}:حقوق النسخ',
'currentevents'        => 'الاحداث الحاليه',
'currentevents-url'    => 'Project:الاحداث الحاليه',
'disclaimers'          => 'اخلاء مسؤوليه',
'disclaimerpage'       => 'Project:اخلاء مسؤوليه عمومى',
'edithelp'             => 'مساعده فى التعديل',
'edithelppage'         => 'Help:تعديل',
'helppage'             => 'Help:محتويات',
'mainpage'             => 'الصفحه الرئيسيه',
'mainpage-description' => 'الصفحه الرئيسيه',
'portal'               => 'بوابة المجتمع',
'portal-url'           => 'Project:بوابة المجتمع',
'privacy'              => 'خصوصيه',
'privacypage'          => 'Project:سياسة الخصوصيه',
'sitesupport'          => 'التبرعات',
'sitesupport-url'      => 'Project:دعم الموقع',

'retrievedfrom'       => 'اتجابت من "$1"',
'youhavenewmessages'  => 'عندك $1 ($2).',
'newmessageslink'     => 'رسايل جديده',
'newmessagesdifflink' => 'اخر تعديل',
'editsection'         => 'تعديل',
'editold'             => 'تعديل',
'editsectionhint'     => 'تعديل جزء : $1',
'toc'                 => 'المحتويات',
'showtoc'             => 'عرض',
'hidetoc'             => 'تخبيه',
'site-rss-feed'       => '$1   ار‌ اس‌ اس فييد',
'site-atom-feed'      => '$1 اتوم فييد',
'page-rss-feed'       => '"$1" ار‌ اس‌ اس فييد',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'صفحة يوزر',
'nstab-project'  => 'صفحة مشروع',
'nstab-image'    => 'فايل',
'nstab-template' => 'قالب',
'nstab-category' => 'تصنيف',

# General errors
'badtitle'       => 'عنوان غلط',
'badtitletext'   => 'عنوان الصفحه المطلوب اما مش صحيح او فاضي، و ربما الوصلة بين اللغات أو بين المشاريع غلط. وممكن وجود رموز ماتصلحش للاستخدام في العناوين.',
'viewsource'     => 'عرض المصدر',
'viewsourcefor'  => 'ل $1',
'viewsourcetext' => 'ممكن تشوف وتنسخ مصدر  الصفحه دى:',

# Login and logout pages
'yourname'                => ' اليوزرنيم:',
'yourpassword'            => 'الباسوورد (كلمة السر):',
'remembermypassword'      => 'افتكر بيانات دخولى على  الكمبيوتر ده',
'login'                   => 'دخول',
'nav-login-createaccount' => 'دخول / فتح حساب',
'loginprompt'             => 'لازم تكون الكوكيز عندك مفعله علشان تقدر تدخل ل {{SITENAME}}.',
'userlogin'               => 'دخول / فتح حساب',
'logout'                  => 'خروج',
'userlogout'              => 'خروج',
'nologin'                 => 'مشتركتش لسه؟ $1.',
'nologinlink'             => ' افتح حساب',
'createaccount'           => 'افتح حساب',
'gotaccount'              => 'عندك حساب؟ $1.',
'gotaccountlink'          => 'دخول',
'yourrealname'            => 'الاسم الحقيقى:',
'prefs-help-realname'     => 'الاسم الحقيقي اختيارى ولو اخترت تعرض اسمك هنا هايستخدم في الإشارة لمساهماتك.',
'loginsuccesstitle'       => 'تم الدخول بشكل صحيح',
'loginsuccess'            => "'''تم تسجيل دخولك{{SITENAME}} باسم \"\$1\".'''",
'nosuchuser'              => 'مافيش يوزر باسم "$1".
اتاكد من تهجية الاسم، او افتح حساب جديد.',
'nosuchusershort'         => 'مافيش يوزر باسم <nowiki>$1</nowiki>". اتاكد من تهجية الاسم.',
'nouserspecified'         => ' لازم تحدد اسم يوزر.',
'wrongpassword'           => 'كلمة السر اللى كتبتها مش صحيحه. من فضلك حاول تانى.',
'wrongpasswordempty'      => 'كلمة السر المدخله كانت فاضيه. من فضلك حاول تانى.',
'passwordtooshort'        => 'كلمة السر التي اخترتها مش صحيحه أو قصيره جدا. لازم مايقلش طول الكلمه عن $1 حرف وتكون مختلفه عن اسم اليوزر بتاعك.',
'mailmypassword'          => 'ابعتلى كلمة السر فى ايميل.',
'passwordremindertitle'   => 'كلمة سر مؤقته جديده ل {{SITENAME}}',
'passwordremindertext'    => 'فيه شخص ما (غالبا انت، من عنوان الااى بى $1)  طلب اننا نرسل لك كلمة سر جديده لـ{{SITENAME}} ($4).

كلمة السر لليوزر "$2" الآن هي "$3".
عليك انك تدخل على الموقع وتغير كلمة السر بتاعتك دلوقتى.

لو مكنتش  انت اللى طلب كلمة السر أو انك افتكرت كلمة السر اللى قبل كده ومش عايز تغيرها ممكن تتجاهل الرساله دى وتستمر في استخدام كلمة السر بتاعتك اللى قبل كده.',
'noemail'                 => 'مافيش ايميل متسجل  لليوزر  "$1".',
'passwordsent'            => '
تم إرسال كلمة سر جديدة لعنوان الايميل المتسجل لليوزر "$1".من فضلك حاول تسجيل الدخول مره تانيه بعد استلامها.',
'eauthentsent'            => 'فيه ايميل تأكيد اتبعت  للعنوان اللى كتبته.  علشان تبعت اي ايميل تانى للحساب ده لازم تتبع التعليمات اللى فى الايميل اللى اتبعتلك  علشان تأكد ان  الحساب ده بتاعك .',

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
'math_sample'     => ' اكتب المعادله هنا',
'math_tip'        => 'معادله رياضيه (لا تكس )',
'nowiki_sample'   => 'حط  الكلام اللي مش متنسق هنا',
'nowiki_tip'      => 'ما تستعملش فورمات الويكى',
'image_tip'       => 'ملف مغروس',
'media_tip'       => 'وصلة ملف',
'sig_tip'         => 'امضتك مع الساعه والتاريخ',
'hr_tip'          => 'خط افقى (ما تستعملهموش كتير)',

# Edit pages
'summary'                => 'ملخص',
'subject'                => ' راس الموضوع/موضوع',
'minoredit'              => ' التعديل ده تعديل صغير',
'watchthis'              => 'راقب الصفحه دى',
'savearticle'            => 'سييف الصفحه',
'preview'                => 'بروفه',
'showpreview'            => 'عرض البروفه',
'showdiff'               => 'بيين التعديلات',
'anoneditwarning'        => "'''تحذير:''' انت ما عملتش لوجين؛ عنوان الااى  بى  بتاعك هايتسجل فى تاريخ الصفحه .",
'summary-preview'        => 'بروفه للملخص',
'blockedtext'            => "

<big>'''تم منع اسم اليوزر أو عنوان الااى بى بتاعك .'''</big>

سبب المنع هو: ''$2''. وقام بالمنع $1.

* بداية المنع: $8
* انتهاء المنع: $6
* الممنوع المقصود: $7

ممكن التواصل مع $1 لمناقشة المنع، أو مع واحد من [[{{MediaWiki:Grouppage-sysop}}|الاداريين]] عن المنع>
افتكر انه مش ممكن تبعت ايميل  لليوزرز الا اذا كنت سجلت عنوان ايميل صحيح فى صفحة [[Special:Preferences|التفضيلات]] بتاعتك.
عنوان الااى بى بتاعك حاليا هو $3 وكود المنع هو #$5.من فضلك ضيف اى واحد منهم أو كلاهما في اى رسالة للتساؤل عن المنع.",
'newarticle'             => '(جديد)',
'newarticletext'         => "انت وصلت لصفحه مابتدتش لسه.
علشان  تبتدى الصفحة ابتدى الكتابه في الصندوق اللى تحت.
(بص على [[{{MediaWiki:Helppage}}|صفحة المساعده]] علشان معلومات اكتر)
لو كانت زيارتك للصفحه دى بالخطأ، اضغط على زر ''رجوع'' في متصفح الإنترنت عندك.",
'noarticletext'          => 'مافيش  دلوقتى اى نص فى  الصفحه دى ، ممكن [[Special:Search/{{PAGENAME}}|تدور على عنوان الصفحه]] في الصفحات التانيه او [{{fullurl:{{FULLPAGENAME}}|action=edit}} تعدل الصفحه دى].',
'previewnote'            => '<strong> دى بروفه للصفحه بس، ولسه ما تسييفتش!</strong>',
'editing'                => 'تعديل $1',
'editingsection'         => 'تعديل $1 (جزء)',
'copyrightwarning'       => 'من فضلك لاحظ ان كل المساهمات فى {{SITENAME}} بتتنشر حسب شروط ترخيص $2 (بص على $1 علشان تعرف  تفاصيل اكتر)
لو مش عايز كتابتك تتعدل او تتوزع من غير مقابل و بدون اذنك ، ما تحطهاش هنا<br />. كمان انت  بتتعهد بانك كتبت كلام تعديلك بنفسك، او نسخته من مصدر يعتبر ضمن الملكيه العامه، أو مصدر حر تان.

<strong>ما تحطش اى عمل له حقوق محفوظه بدون اذن صاحب الحق</strong>.',
'longpagewarning'        => '
<strong>تحذير: الصفحه دى حجمها $1 كيلوبايت، بعض المتصفحات (براوزرز) ممكن تواجه مشاكل لما تحاول تعديل صفحات يزيد حجمها عن 32 كيلوبايت. من فضلك ,لو امكن قسم الصفحة لصفحات اصغر فى الحجم.</strong>',
'templatesused'          => 'القوالب المستعمله في الصفحه دى:',
'templatesusedpreview'   => 'القوالب المستعمله فى البروفه دى:',
'template-protected'     => '(حمايه كامله)',
'template-semiprotected' => '(حمايه جزئيه )',
'nocreatetext'           => '{{SITENAME}} حدد القدره على انشاء صفحات جديده.
ممكن ترجع وتحرر صفحه موجوده بالفعل، او [[Special:Userlogin|الدخول / فتح حساب]].',
'recreate-deleted-warn'  => "'''تحذير: انت بتعيد انشاء صفحه اتمسحت قبل كده.'''
لازم تتأكد من ان الاستمرار فى تحرير الصفحه دى ملائم.
سجل الحذف للصفحه دى معروض هنا:",

# History pages
'viewpagelogs'        => 'عرض السجلات للصفحه دى',
'currentrev'          => 'النسخه دلوقتى',
'revisionasof'        => 'تعديلات من $1',
'revision-info'       => 'نسخه $1 بواسطة $2',
'previousrevision'    => '←نسخه اقدم',
'nextrevision'        => 'نسخه احدث→',
'currentrevisionlink' => 'النسخه دلوقتى',
'cur'                 => 'دلوقتى',
'last'                => 'قبل كده',
'page_first'          => 'الاولى',
'page_last'           => 'الاخيره',
'histlegend'          => 'اختيار الفرق: علم على صناديق النسخ للمقارنه و اضغط قارن بين النسخ المختاره او الزر اللى تحت.<br />
مفتاح: (دلوقتى) = الفرق مع النسخة دلوقتى
(اللى قبل كده) = الفرق مع النسخة اللى قبل كده، ص = تعديل صغير',
'histfirst'           => 'اول',
'histlast'            => 'آخر',

# Revision feed
'history-feed-item-nocomment' => '$1 فى $2', # user at time

# Diffs
'history-title'           => 'تاريخ تعديل "$1"',
'difference'              => '(الفرق بين النسخ)',
'lineno'                  => 'سطر $1:',
'compareselectedversions' => 'قارن بين النسختين المختارتين',
'editundo'                => 'استرجاع',
'diff-multi'              => '({{PLURAL:$1|نسخه واحده متوسطه|$1 نسخه متوسطه}} مش معروضه.)',

# Search results
'noexactmatch' => "'''مافيش  صفحه بالاسم \"\$1\"'''. ممكن [[:\$1| تبتدى الصفحه دى]].",
'prevn'        => '$1 اللى قبل كده',
'nextn'        => '$1 اللى بعد كده',
'viewprevnext' => 'بص ($1) ($2) ($3)',
'powersearch'  => 'تدوير متفصل',

# Preferences page
'preferences'   => 'تفضيلات',
'mypreferences' => 'تفضيلاتى',
'retypenew'     => 'اكتب كلمة السر الجديده تانى:',

'grouppage-sysop' => '{{ns:project}}:اداريين',

# User rights log
'rightslog' => 'سجل صلاحيات اليوزرز',

# Recent changes
'nchanges'                       => '{{PLURAL:$1|تعديل|تعديلين|$1 تعديلات|$1 تعديل|$1 تعديل}}',
'recentchanges'                  => 'احدث التعديلات',
'recentchanges-feed-description' => 'تابع احدث التعديلات للويكى ده عن طريق الفييد ده .',
'rcnote'                         => "
فيه تحت {{PLURAL:$1|'''1''' تعديل|آخر '''$1''' تعديل}} في اخر {{PLURAL:$2|يوم|'''$2''' يوم}}، بدايه من $3.",
'rcnotefrom'                     => "دى التعديلات من '''$2''' (ل '''$1''' معروضه).",
'rclistfrom'                     => 'اظهر التعديلات بدايه من $1',
'rcshowhideminor'                => '$1 تعديلات صغيره',
'rcshowhidebots'                 => '$1 البوتات',
'rcshowhideliu'                  => '$1 اليوزرز المتسجلين',
'rcshowhideanons'                => '$1 اليوزرز المجهولين',
'rcshowhidepatr'                 => '$1 التعديلات المتراجعه',
'rcshowhidemine'                 => '$1 تعديلاتى',
'rclinks'                        => 'بيين اخر $1 تعديل في اخر $2 يوم، $3',
'diff'                           => 'التغيير',
'hist'                           => 'تاريخ',
'hide'                           => 'تخبيه',
'show'                           => 'عرض',
'minoreditletter'                => 'ص',
'newpageletter'                  => 'ج',
'boteditletter'                  => 'ب',

# Recent changes linked
'recentchangeslinked'          => 'تعديلات  ليها علاقه',
'recentchangeslinked-title'    => 'التعديلات المرتبطه  ب "$1"',
'recentchangeslinked-noresult' => 'مافيش تعديلات حصلت فى الصفحات اللى ليها وصلات هنا خلال الفترة المحدده.',
'recentchangeslinked-summary'  => "دى صفحة مخصوصه بتعرض اخر التغييرات في الصفحات الموصوله. الصفحات اللى   فى  لسته بالصفحات اللى انت بتراقب التعديلات فيها معروضه''' بحروف عريضه'''",

# Upload
'upload'        => 'حمل',
'uploadbtn'     => 'حمل الملف',
'uploadlogpage' => 'سجل التحميل',
'uploadedimage' => 'اتحمل "[[$1]]"',

# Special:Imagelist
'imagelist' => 'لستة الملفات',

# Image description page
'filehist'                  => 'تاريخ الملف',
'filehist-help'             => 'اضغط على الساعه/التاريخ علشان تشوف الفايل زى ما كان فى  الوقت ده.',
'filehist-current'          => 'دلوقتي',
'filehist-datetime'         => 'الساعه / التاريخ',
'filehist-user'             => 'يوزر',
'filehist-dimensions'       => 'ابعاد',
'filehist-filesize'         => 'حجم الفايل',
'filehist-comment'          => 'تعليق',
'imagelinks'                => 'وصلات',
'linkstoimage'              => 'الصفحات دى فيها وصله للفايل دا:',
'nolinkstoimage'            => 'مافيش صفحات بتوصل للفايل ده.',
'sharedupload'              => 'الملف ده اتحمل علشان التشارك بين المشاريع وممكن استخدامه في المشاريع التانيه.',
'noimage'                   => ' مافيش  ملف بالاسم ده ،ممكن انك تقوم بـ$1.',
'noimage-linktext'          => 'تحميله',
'uploadnewversion-linktext' => 'حمل نسخه جديده من الملف ده',

# MIME search
'mimesearch' => 'تدوير MIME',

# List redirects
'listredirects' => 'عرض التحويلات',

# Unused templates
'unusedtemplates' => 'قوالب مش مستعمله',

# Random page
'randompage' => 'صفحة عشوائيه',

# Random redirect
'randomredirect' => 'تحويله عشوائيه',

# Statistics
'statistics' => 'احصائيات',

'disambiguations' => 'صفحات التوضيح',

'doubleredirects' => 'تحويلات مزدوجه',

'brokenredirects' => 'تحويلات مكسوره',

'withoutinterwiki' => 'صفحات بدون وصلات للغات تانيه',

'fewestrevisions' => ' اقل المقالات فى عدد التعديلات',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بايت|بايت}}',
'nlinks'                  => '{{PLURAL:$1|وصله واحده|وصلتين|$1 وصلات|$1 وصله}}',
'nmembers'                => '$1 {{PLURAL:$1|عضو|اعضاء}}',
'lonelypages'             => 'صفحات يتيمه',
'uncategorizedpages'      => 'صفحات مش متصنفه',
'uncategorizedcategories' => 'تصنيفات مش متصنفه',
'uncategorizedimages'     => 'ملفات مش متصنفه',
'uncategorizedtemplates'  => 'قوالب مش متصنفه',
'unusedcategories'        => 'تصانيف مش  مستعمله',
'unusedimages'            => 'صور مش مستعمله',
'wantedcategories'        => 'تصانيف مطلوبه',
'wantedpages'             => 'صفحات مطلوبه',
'mostlinked'              => 'اكتر صفحات مرتبطه بصفحات تانيه',
'mostlinkedcategories'    => 'اكتر التصانيف فى عدد الارتباطات',
'mostlinkedtemplates'     => 'اكتر القوالب فى عدد الوصلات',
'mostcategories'          => 'اكتر الصفحات فى عدد التصانيف',
'mostimages'              => 'اكتر الملفات فى عدد الارتباطات',
'mostrevisions'           => 'اكتر المقالات فى عدد التعديلات',
'prefixindex'             => 'فهرس',
'shortpages'              => 'صفحات قصيره',
'longpages'               => 'صفحات طويله',
'deadendpages'            => 'صفحات ما بتوصلش  لحاجه',
'protectedpages'          => 'صفحات محميه',
'listusers'               => 'لستة الأعضاء',
'specialpages'            => 'صفحات مخصوصه',
'newpages'                => 'صفحات جديده',
'ancientpages'            => 'اقدم الصفحات',
'move'                    => 'انقل',
'movethispage'            => 'انقل الصفحه دى',

# Book sources
'booksources' => 'مصادر من كتب',

# Special:Log
'specialloguserlabel'  => 'اليوزر:',
'speciallogtitlelabel' => 'العنوان:',
'log'                  => 'سجلات',
'all-logs-page'        => 'كل السجلات',

# Special:Allpages
'allpages'       => 'كل الصفحات',
'alphaindexline' => '$1 ل $2',
'nextpage'       => 'الصفحه اللى بعد كده ($1)',
'prevpage'       => 'الصفحه اللى قبل كده ($1)',
'allpagesfrom'   => 'عرض الصفحات بدايه من:',
'allarticles'    => 'كل المقالات',
'allpagessubmit' => 'روح',
'allpagesprefix' => 'عرض الصفحات  اللى تبتدى بـ:',

# E-mail user
'emailuser' => ' ابعت ايميل لليوزر ده',

# Watchlist
'watchlist'            => 'لستة الصفحات اللى باراقبها',
'mywatchlist'          => 'لستة  الصفحات اللى باراقبها',
'watchlistfor'         => "(ل '''$1''')",
'addedwatch'           => 'تمت الاضافه للستة الصفحات اللى بتراقبها',
'addedwatchtext'       => '
تمت إضافة الصفحه  "$1"  [[Special:Watchlist|للستة الصفحات اللى بتراقبها]] . التعديلات اللى بعد كده ها تتحط على الصفحه دى، وصفحة المناقش الخاصه بها ها تتحط هناك. واسم الصفحة هايظهر  بخط <b>عريض</b> في صفحة [[Special:Recentchanges|أحدث التعديلات]] لتسهيل تحديدها واكتشافها.

علشان تشيل الصفحة من لستة الصفحات اللى بتراقبها، اضغط على "توقف عن المراقبة" فوق.',
'removedwatch'         => 'اتشالت  من لستة الصفحات اللى بتراقبها',
'removedwatchtext'     => ' الصفحه دى اتشالت "[[:$1]]" من لستة الصفحات اللى بتراقبها.',
'watch'                => 'راقب',
'watchthispage'        => 'راقب الصفحه دى',
'unwatch'              => 'بطل مراقبه',
'watchlist-details'    => '{{PLURAL:$1|$1 صفحه|$1 صفحه}} متراقبه بدون عد صفحات المناقشه.',
'wlshowlast'           => 'عرض اخر $1 ساعات $2 ايام $3',
'watchlist-hide-bots'  => 'تخبية تعديلات البوت',
'watchlist-hide-own'   => 'اخفاء تعديلاتى',
'watchlist-hide-minor' => 'خبى التعديلات الصغيره',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'راقب...',
'unwatching' => 'بطل مراقبه...',

# Delete/protect/revert
'deletepage'                  => 'امسح الصفحه',
'historywarning'              => 'تحذير: الصفحه اللى ها  تمسحها ليها تاريخ:',
'confirmdeletetext'           => 'انت على وشك انك تمسح صفحه أو صوره و كل التعديلات عليها بشكل دايم من قاعدة البيانات.  من فضلك  اتأكد انك عايز المسح وبأنك فاهم نتايج  العمليه  دى. عمليات الحذف لازم تتم بناء على [[{{MediaWiki:Policy-url}}|القواعد المتفق عليها]].',
'actioncomplete'              => ' العمليه خلصت',
'deletedtext'                 => '"<nowiki>$1</nowiki>" اتمسحت.
بص على $2 لسجل آخر عمليات المسح.',
'deletedarticle'              => 'اتمسحت "[[$1]]"',
'dellogpage'                  => 'سجل المسح',
'deletecomment'               => 'سبب المسح:',
'deleteotherreason'           => 'سبب تانى/اضافي:',
'deletereasonotherlist'       => 'سبب تانى',
'rollbacklink'                => 'استعاده',
'protectlogpage'              => 'سجل الحمايه',
'protectcomment'              => 'تعليق:',
'protectexpiry'               => 'تنتهى فى:',
'protect_expiry_invalid'      => 'وقت الانتهاء مش صحيح.',
'protect_expiry_old'          => 'وقت انتهاء المنع قديم.',
'protect-unchain'             => 'استعادة سماح النقل',
'protect-text'                => 'ممكن هنا تعرض و تغير مستوى الحمايه للصفحه <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'حسابك ما لوش  صلاحية تغيير مستوى حماية الصفحه.
الاعدادات الحالية للصفحه <strong>$1</strong> هى:',
'protect-cascadeon'           => 'الصفحه دى محميه لكونها متضمنه فى {{PLURAL:$1|الصفحه|الصفحات}} دى، واللى  فيها اختيار حماية الصفحات المتضمنه شغال. ممكن تغير مستوى حماية الصفحه دى بدون التأثير على حماية الصفحات المتضمنه التانيه.',
'protect-default'             => '(افتراضى)',
'protect-fallback'            => 'محتاج  اذن "$1"',
'protect-level-autoconfirmed' => 'منع الوزرز غير المسجلين',
'protect-level-sysop'         => 'سيسوب بس',
'protect-summary-cascade'     => 'متضمنه',
'protect-expiring'            => 'تنتهى فى $1 (UTC)',
'protect-cascade'             => 'احمى الصفحات المتضمنه في الصفحه دى (حمايه مضمنه)',
'protect-cantedit'            => 'مش ممكن تغير مستويات الحمايه للصفحه دى، لانك ماعندكش صلاحية تعديلها.',
'restriction-type'            => 'سماح:',
'restriction-level'           => 'مستوى القيود :',

# Undelete
'undeletebtn' => 'استعاده',

# Namespace form on various pages
'namespace'      => 'النيمسبيس:',
'invert'         => 'عكس الاختيار',
'blanknamespace' => '(رئيسى)',

# Contributions
'contributions' => 'مساهمات اليوزر',
'mycontris'     => 'تعديلاتى',
'contribsub2'   => 'لليوزر $1 ($2)',
'uctop'         => '(فوق)',
'month'         => 'من شهر (واللى قبل كده):',
'year'          => 'من سنة (واللى قبل كده):',

'sp-contributions-newbies-sub' => 'للحسابات الجديده',
'sp-contributions-blocklog'    => 'سجل المنع',

# What links here
'whatlinkshere'       => 'ايه بيوصل هنا',
'whatlinkshere-title' => 'الصفحات اللي بتودي ل $1',
'linklistsub'         => '(لسته بالوصلات)',
'linkshere'           => "الصفحات دى فيها وصله ل '''[[:$1]]''':",
'nolinkshere'         => "مافيش صفحات بتوصل ل '''[[:$1]]'''.",
'isredirect'          => 'صفحة تحويل',
'istemplate'          => 'متضمن',
'whatlinkshere-prev'  => '{{PLURAL:$1|اللى قبل كده|الـ $1 اللى قبل كده}}',
'whatlinkshere-next'  => '{{PLURAL:$1|اللى بعد كده|الـ $1 اللى بعد كده}}',
'whatlinkshere-links' => '← وصلات',

# Block/unblock
'blockip'       => 'منع يوزر',
'ipboptions'    => 'ربع ساعة:15 minutes,ساعة واحدة:1 hour,ساعتين:2 hours,يوم:1 day,ثلاثة أيام:3 days,أسبوع:1 week,أسبوعان:2 weeks,شهر:1 month,ثلاثة شهور:3 months,ستة شهور:6 months,عام واحد:1 year,دائم:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => 'لستة عناوين الااى بى واسامى اليوزر الممنوعه',
'blocklink'     => 'منع',
'unblocklink'   => 'رفع المنع',
'contribslink'  => 'تعديلات',
'blocklogpage'  => 'سجل المنع',
'blocklogentry' => 'منع "[[$1]]" لفترةه زمنيه مدتها $2 $3',

# Move page
'movepagetext'     => "
لو استعملت النموذج ده ممكن تغير اسم الصفحه، و تنقل تاريخها للاسم الجديد.
هاتبتدى تحويله من العنوان القديم للصفحه بالعنوان الجديد.
لكن،  الوصلات في الصفحات اللى تتصل بالصفحه دى مش ها تتغير؛ اتأكد من ان مافيش وصلات مقطوعه، أو وصلات متتاليه، للتأكد من أن المقالات تتصل مع بعضها بشكل مناسب.

لاحظ ان الصفحه مش هاتتنقل لو كان فيه صفحه بالاسم الجديد، إلا إذا كانت صفحة فاضيه، أو صفحة تحويل، ومالهاش تاريخ. و ده معناه أنك مش ها تقدر تحط صفحه مكان صفحه، كمان ممكن ارجاع الصفحه لمكانها في حال تم النقل بشكل غلط.

'''تحذير!'''
نقل الصفحه ممكن يكون له اثار كبيرة، وتغييرات مش متوقعه بالنسبة للصفحات المشهوره. من فضلك  اتأكد من فهم عواقب نقل الصفحات قبل ما تقوم بنقل الصفحه.",
'movepagetalktext' => '

صفحة المناقشه بتاعة المقاله هاتتنقل برضه، لو كانت موجوده. لكن صفحة المناقشه مش هاتتنقل فى الحالات  دى:
* نقل الصفحة عبر نطاقات  مختلفه.
*فيه  صفحة مناقشه موجوده تحت العنوان الجديد للمقاله.
* لو انت شلت اختيار نقل صفحة المناقشه .

وفي الحالات  دى، لو عايز  تنقل صفحة المناقشه  لازم تنقل أو تدمج محتوياتها  يدويا.',
'movearticle'      => 'انقل الصفحه:',
'newtitle'         => 'للعنوان الجديد:',
'move-watch'       => 'راقب الصفحه دى',
'movepagebtn'      => 'نقل الصفحه',
'pagemovedsub'     => 'تم  النقل بنجاح',
'movepage-moved'   => '<big>\'\'\'"$1" اتنقلت ل"$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => '

يا اما فيه صفحه  بالاسم ده ،او ان الاسم اللى  تم اختياره مش صالح. لو سمحت اختار اسم تانى .',
'talkexists'       => "'''الصفحه دى اتنقلت لصفحة بنجاح، ولكن صفحة المناقشه بتاعتها ما اتنقلتش  علشان فيه صفحة مناقشه تحت العنوان الجديد. من فضلك انقل محتويات صفحة المناقشه يدويا، وادمجها مع المحتويات اللى قبل كده.'''",
'movedto'          => 'اتنقلت ل',
'movetalk'         => 'انقل صفحة المناقشه.',
'talkpagemoved'    => 'اتنقلت صفحة المناقشه كمان.',
'talkpagenotmoved' => '<strong>متنقلتش</strong>  صفحة المناقشه .',
'1movedto2'        => '[[$1]] اتنقلت ل [[$2]]',
'movelogpage'      => 'سجل النقل',
'movereason'       => 'السبب:',
'revertmove'       => 'استعاده',

# Export
'export' => 'تصدير صفحات',

# Namespace 8 related
'allmessages' => 'رسايل النظام',

# Thumbnails
'thumbnail-more'  => 'كبر',
'thumbnail_error' => 'غلطه فى انشاء صوره مصغره: $1',

# Import log
'importlogpage' => 'سجل الاستيراد',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'صفحتى الخاصه',
'tooltip-pt-mytalk'               => 'صفحة مناقشاتى',
'tooltip-pt-preferences'          => 'تفضيلاتى',
'tooltip-pt-watchlist'            => 'لسته بالصفحات اللى انت بتراقب التعديلات فيها',
'tooltip-pt-mycontris'            => ' لسته بتعديلاتى',
'tooltip-pt-login'                => 'من الافضل انك تسجل دخولك، لكن ده مش شرط',
'tooltip-pt-logout'               => 'خروج',
'tooltip-ca-talk'                 => 'مناقشة صفحة الموضوع',
'tooltip-ca-edit'                 => 'ممكن تعدل  الصفحه دى، بس لو سمحت استعمل زرار البروفه قبل ما تسييفها',
'tooltip-ca-addsection'           => 'ضيف تعليق للمناقشه دى.',
'tooltip-ca-viewsource'           => 'الصفحه دى محميه. ممكن تشوف مصدرها.',
'tooltip-ca-protect'              => 'احمى الصفحه دى',
'tooltip-ca-delete'               => 'امسح الصفحه دى',
'tooltip-ca-move'                 => 'انقل الصفحه دى',
'tooltip-ca-watch'                => 'حط الصفحة دى فى لسته الصفحات اللى باراقب التعديلات فيها',
'tooltip-ca-unwatch'              => 'شيل الصفحه دى من لستة الصفحات اللى بتراقبها',
'tooltip-search'                  => 'دور فى {{SITENAME}}',
'tooltip-n-mainpage'              => 'زور الصفحه الرئيسيه',
'tooltip-n-portal'                => 'عن المشروع، ممكن تعمل ايه، و فين تلاقى اللى بتدور عليه',
'tooltip-n-currentevents'         => 'مطالعه سريعه لاهم الاحداث دلوقتى',
'tooltip-n-recentchanges'         => 'لسته بالتعديلات الجديده فى الويكى',
'tooltip-n-randompage'            => 'حمل صفحة عشوائيه',
'tooltip-n-help'                  => 'لو محتاج مساعده بص هنا',
'tooltip-n-sitesupport'           => 'ساندنا',
'tooltip-t-whatlinkshere'         => 'صفحات الويكى اللى بتوصل هنا',
'tooltip-t-contributions'         => 'عرض مساهمات اليوزر ده',
'tooltip-t-emailuser'             => 'ابعت ايميل لليوزر ده',
'tooltip-t-upload'                => 'حمل ملفات',
'tooltip-t-specialpages'          => 'لسته بكل الصفحات المخصوصه',
'tooltip-ca-nstab-user'           => 'اعرض صفحة اليوزر',
'tooltip-ca-nstab-project'        => 'اعرض صفحة المشروع',
'tooltip-ca-nstab-image'          => 'اعرض صفحة الفايل',
'tooltip-ca-nstab-template'       => 'اعرض القالب',
'tooltip-ca-nstab-help'           => 'اعرض صفحة المساعده',
'tooltip-ca-nstab-category'       => 'اعرض صفحة التصنيف',
'tooltip-minoredit'               => 'علم على ده كتعديل صغير',
'tooltip-save'                    => ' سييف تعديلاتك',
'tooltip-preview'                 => 'اعرض بروفه لتعديلاتك، من فضلك شوف البروفه قبل ما تسييف!',
'tooltip-diff'                    => 'اعرض التعديلات اللى انت عملتها على النص.',
'tooltip-compareselectedversions' => 'شوف الفروق بين النسختين المختارتين للصفحه دى.',
'tooltip-watch'                   => 'ضم الصفحه دى للستة الصفحات اللى بتراقبها',

# Browsing diffs
'previousdiff' => '→ الفرق اللى قبل كده',
'nextdiff'     => 'الفرق اللى بعد كده ←',

# Media information
'file-info-size'       => '($1 × $2 بكسل حجم الفايل: $3، نوع MIME: $4)',
'file-nohires'         => '<small>مافيش  ريزوليوشن اعلى متوفر.</small>',
'svg-long-desc'        => '(ملف SVG، اساسا $1 × $2 بكسل، حجم الملف: $3)',
'show-big-image'       => 'الصورة بدقه كامله',
'show-big-image-thumb' => '<small>حجم البروفه دى: $1 × $2 بكسل</small>',

# Special:Newimages
'newimages' => 'جاليرى الصور الجديده',

# Bad image list
'bad_image_list' => 'الصيغه بالشكل ده:

عناصر اللسته  بس (السطور اللى تبتدى ب *) ها تتاخد في الاعتبار. أول وصلة في السطر لازم تكون وصله لملف سيىء.
أي وصلات بعد كده في نفس السطر هاتعتبر استثناءات، بمعنى تانى  مقالات ممكن الملف يكون موجود فيها.',

# Metadata
'metadata'          => 'بيانات ميتا',
'metadata-help'     => '
الملف ده فيه معلومات إضافيه، غالبا ما تكون أضيفت من الديجيتال كاميرا أو السكانر ح الضوئي المستخدم في نقل الملف للكومبيوتر. إذا كان الملف اتعدل عن حالته الأصلية، فبعض التفاصيل مش ها تعبر عن الملف المعدل.',
'metadata-expand'   => 'عرض التفاصيل الاضافيه',
'metadata-collapse' => 'تخبية التفاصيل الاضافيه',
'metadata-fields'   => 'حقول معطيات الميتا EXIF الموجوده فى الرساله دى هاتتعرض في صفحة الصوره لما يكون جدول معطيات الميتا مضغوط. الحقول التانيه هاتكون مخفيه افتراضيا.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'عدل هذا الملف باستخدام تطبيق خارجي',
'edit-externally-help' => 'بص على [http://meta.wikimedia.org/wiki/Help:External_editors  تعليمات الاعداد] علشان معلومات اكتر.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'الكل',
'namespacesall' => 'الكل',
'monthsall'     => 'الكل',

# Watchlist editing tools
'watchlisttools-view' => 'عرض التعديلات المرتبطه',
'watchlisttools-edit' => 'عرض وتعديل لستة الصفحات اللى باراقبها',
'watchlisttools-raw'  => 'عدل لستة المراقبه الخام',

# Special:Version
'version' => 'نسخه', # Not used as normal message but as header for the special page itself

);
