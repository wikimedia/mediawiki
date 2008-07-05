<?php
/** Egyptian Spoken Arabic (مصرى)
 *
 * @ingroup Language
 * @file
 *
 * @author Ghaly
 * @author Ramsis1978
 * @author Alnokta
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
	'currentversion'      => array( '1', 'نسخة_حالية', 'CURRENTVERSION' ),
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
	'Missingfiles'              => array( 'ملفات_مفقودة', 'صور_مفقودة' ),
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
'tog-underline'               => 'حط خط تحت الوصلات:',
'tog-highlightbroken'         => 'أ بين اللينكات البايظة <a href="" class="new">كدا</a> (البديل: زي دا<a href="" class="internal">؟</a>).',
'tog-justify'                 => 'ساوى الفقرات',
'tog-hideminor'               => 'خبي التعديلات الصغيرة في اجدد التغييرات',
'tog-extendwatchlist'         => 'وسع لستة المراقبة علشان تبين كل التغييرات اللي ممكن تتطبق',
'tog-usenewrc'                => 'شكل قوي من أجدد التغييرات (جافا سكريبت)',
'tog-numberheadings'          => 'رقم العناوين تلقائيا',
'tog-showtoolbar'             => 'بين شريط التحرير (جافا سكريبت)',
'tog-editondblclick'          => 'عدل الصفحات عند الدبل كليك (جافا سكريبت)',
'tog-editsection'             => 'اسمح ب تعديل الأقسام عن طريق وصلات [تعديل]',
'tog-editsectiononrightclick' => 'اسمح ب تعديل الأقسام لما ندوس رايت كليك على الماوس على عناوين الأقسام (جافاسكريبت)',
'tog-showtoc'                 => 'بين جدول المحتويات (ل الصفحات االلي فيها أكتر من 3 عناوين)',
'tog-rememberpassword'        => 'خليك فاكر دخولي على الكمبيوتر دا',
'tog-editwidth'               => 'صندوق التعديل  واخد العرض كله',
'tog-watchcreations'          => 'ضيف الصفحات اللى أنشأتها للستة الصفحات اللى باراقبها',
'tog-watchdefault'            => 'ضيف الصفحات اللى بأعدلها للستة الصفحات اللى باراقبها',
'tog-watchmoves'              => 'ضيف الصفحات اللى بأنقلها للستة الصفحات اللى باراقبها',
'tog-watchdeletion'           => 'ضيف الصفحات اللى بأمسحها للستة الصفحات اللى باراقبها',
'tog-minordefault'            => 'علم  على كل التعديلات كأنها صغيرة افتراضيا',
'tog-previewontop'            => 'بين البروفة قبل صندوق التعديل',
'tog-previewonfirst'          => 'بين البروفة عند أول تعديل',
'tog-nocache'                 => 'عطل تخبية الصفحه',
'tog-enotifwatchlistpages'    => 'ابعت لى ايميل لما تتغير صفحه فى لستة الصفحات اللى باراقبها',
'tog-enotifusertalkpages'     => 'ابعتلى ايميل لما صفحة مناقشتى تتغيير',
'tog-enotifminoredits'        => 'ابعتلى ايميل للتعديلات الصغيره للصفحات',
'tog-enotifrevealaddr'        => 'بين الايميل بتاعى في ايميلات الاعلام',
'tog-shownumberswatching'     => 'بين عدد اليوزرز المراقبين',
'tog-fancysig'                => 'امضاء خام (من غير لينك أوتوماتيكي)',
'tog-externaleditor'          => 'استعمل محرر خارجى افتراضيا',
'tog-externaldiff'            => 'استعمل فرق خارجى افتراضيا',
'tog-showjumplinks'           => 'خلي وصلات "روح لـ" تكون شغالة.',
'tog-uselivepreview'          => 'استخدم البروفة السريعة (جافاسكريبت) (تجريبي)',
'tog-forceeditsummary'        => 'نبهني عند تدخيل ملخص للتعديل  فاضي',
'tog-watchlisthideown'        => ' خبي التعديلات بتاعتي من لستة المراقبة',
'tog-watchlisthidebots'       => 'خبي التعديلات بتاعة البوت من لستة المراقبة',
'tog-watchlisthideminor'      => 'خبي التعديلات البسيطة من لستة المراقبة',
'tog-ccmeonemails'            => 'ابعتلى  نسخ من رسايل الايميل اللى بابعتها لليوزرز التانيين',
'tog-diffonly'                => 'ما تبين ش محتوى الصفحة تحت الفروقات',
'tog-showhiddencats'          => 'بين التّصنيفات المستخبية',

'underline-always'  => 'دايما',
'underline-never'   => 'ابدا',
'underline-default' => 'على حسب إعدادات المتصفح',

'skinpreview' => '(بروفه)',

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
'hidden-category-category'       => 'تصنيفات مستخبية', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2| التصنيف دا فيه  التصنيف الفرعي الجاي بس.|التصنيف دا فيه {{PLURAL:$1|تصنيف فرعي|$1 تصنيف فرعي}}، من إجمالي $2.}}',
'category-subcat-count-limited'  => ' التصنيف دا فيه {{PLURAL:$1|تصنيف فرعي|$1 تصنيف فرعي}} كدا.',
'category-article-count'         => '{{PLURAL:$2| التصنيف دا فيه  الصفحة دي بس.|تحت {{PLURAL:$1|ملف|$1 ملف}} في  التصنيف دا ، من إجمالي $2.}}',
'category-article-count-limited' => 'تحت {{PLURAL:$1|صفحة|$1 صفحة}} في التصنيف الحالي.',
'category-file-count'            => '{{PLURAL:$2| التصنيف دا  فيه الملف الجاي دا بس.|تحت {{PLURAL:$1|ملف|$1 ملف}} في  التصنيف دا، من إجمالي $2.}}',
'category-file-count-limited'    => 'تحت {{PLURAL:$1|ملف|$1 ملف}} في التصنيف الحالي.',
'listingcontinuesabbrev'         => 'متابعه',

'mainpagetext'      => "<big>''' ميدياويكي اتنزلت بنجاح.'''</big>",
'mainpagedocfooter' => 'اسال [http://meta.wikimedia.org/wiki/Help:Contents دليل اليوزر] للمعلومات حوالين استخدام برنامج الويكي.

== البداية ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings لستة اعدادات الضبط]
* [http://www.mediawiki.org/wiki/Manual:FAQ أسئلة بتكرر حوالين الويكي ميديا]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce لستة الايميلات بتاعة اعلانات الويكي ميديا]',

'about'          => 'عن',
'article'        => 'صفحة محتوى',
'newwindow'      => '(بتفتح ويندو جديده)',
'cancel'         => 'كانسل',
'qbfind'         => 'تدوير',
'qbbrowse'       => 'تصفح',
'qbedit'         => 'عدل',
'qbpageoptions'  => ' الصفحه دى',
'qbpageinfo'     => 'السياق',
'qbmyoptions'    => 'صفحاتى',
'qbspecialpages' => 'الصفحات الخاصة',
'moredotdotdot'  => 'اكتر...',
'mypage'         => 'صفحتى',
'mytalk'         => 'مناقشاتى',
'anontalk'       => 'المناقشة مع عنوان الأيبي دا',
'navigation'     => 'ابحار',
'and'            => 'و',

# Metadata in edit box
'metadata_help' => 'ميتا داتا:',

'errorpagetitle'    => 'غلطه',
'returnto'          => 'ارجع ل $1.',
'tagline'           => 'من {{SITENAME}}',
'help'              => 'مساعده',
'search'            => 'تدوير',
'searchbutton'      => 'تدوير',
'go'                => 'روح',
'searcharticle'     => 'روح',
'history'           => 'تاريخ الصفحه',
'history_short'     => 'تاريخ',
'updatedmarker'     => 'اتحدثت بعد زيارتي الأخيرة',
'info_short'        => 'معلومات',
'printableversion'  => 'نسخه للطبع',
'permalink'         => 'وصله مستديمه',
'print'             => 'اطبع',
'edit'              => 'تعديل',
'create'            => 'أنشيء',
'editthispage'      => 'عدل الصفحه دى',
'create-this-page'  => 'أنشيء الصفحه دى',
'delete'            => 'مسح',
'deletethispage'    => 'امسح الصفحه دى',
'undelete_short'    => 'استرجاع {{PLURAL:$1|تعديل واحد|تعديلان|$1 تعديلات|$1 تعديل|$1 تعديلا}}',
'protect'           => 'حمايه',
'protect_change'    => 'غير الحماية',
'protectthispage'   => 'احمى الصفحه دى',
'unprotect'         => 'الغي الحماية',
'unprotectthispage' => 'شيل حماية الصفحه دى',
'newpage'           => 'صفحه جديده',
'talkpage'          => 'ناقش الصفحه دى',
'talkpagelinktext'  => 'مناقشه',
'specialpage'       => 'صفحة مخصوصة',
'personaltools'     => 'ادوات شخصيه',
'postcomment'       => 'ابعت تعليق',
'articlepage'       => 'بين صفحة المحتوى',
'talk'              => 'مناقشه',
'views'             => 'مشاهده',
'toolbox'           => 'علبة العده',
'userpage'          => 'عرض صفحة اليوزر',
'projectpage'       => 'عرض صفحة المشروع',
'imagepage'         => 'عرض صفحة الميديا',
'mediawikipage'     => 'عرض صفحة الرسالة',
'templatepage'      => 'عرض صفحة القالب',
'viewhelppage'      => 'بين صفحة المساعدة',
'categorypage'      => 'عرض صفحة التصنيف',
'viewtalkpage'      => 'بين المناقشة',
'otherlanguages'    => 'بلغات تانيه',
'redirectedfrom'    => '(تحويل من $1)',
'redirectpagesub'   => 'صفحة تحويل',
'lastmodifiedat'    => 'الصفحة دي اتعدلت اخر مرة في $2، $1.', # $1 date, $2 time
'viewcount'         => 'الصفحة دي اتدخل عليها{{PLURAL:$1|مرة واحدة|مرتين|$1 مرات|$1 مرة}}.',
'protectedpage'     => 'صفحه محميه',
'jumpto'            => 'روح على:',
'jumptonavigation'  => 'ناڤيجيشن',
'jumptosearch'      => 'تدوير',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => ' عن {{SITENAME}}',
'aboutpage'            => 'Project:  معلومات عن',
'bugreports'           => 'بلاغ الاخطاء',
'bugreportspage'       => 'Project:تبليغ اخطاء',
'copyright'            => 'المحتوى موجود تحت $1.',
'copyrightpagename'    => 'حقوق النسخ في {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:حقوق النسخ',
'currentevents'        => 'الاحداث الحاليه',
'currentevents-url'    => 'Project:الاحداث الحاليه',
'disclaimers'          => 'اخلاء مسؤوليه',
'disclaimerpage'       => 'Project:اخلاء مسؤوليه عمومى',
'edithelp'             => 'مساعده فى التعديل',
'edithelppage'         => 'Help:تعديل',
'faq'                  => 'اسئله بتتسئل كتير',
'faqpage'              => 'Project:اسئله بتتسئل كتير',
'helppage'             => 'Help:محتويات',
'mainpage'             => 'الصفحه الرئيسيه',
'mainpage-description' => 'الصفحه الرئيسيه',
'policy-url'           => 'Project:سياسة',
'portal'               => 'بوابة المجتمع',
'portal-url'           => 'Project:بوابة المجتمع',
'privacy'              => 'خصوصيه',
'privacypage'          => 'Project:سياسة الخصوصيه',
'sitesupport'          => 'التبرعات',
'sitesupport-url'      => 'Project:دعم الموقع',

'badaccess'        => 'غلطه فى السماح',
'badaccess-group0' => 'انت مش مسموح لك تنفذ الطلب بتاعك',
'badaccess-group1' => 'الفعل االلي طلبته مسموح بس لليوزرز في المجموعة $1.',
'badaccess-group2' => 'الفعل اللي طلبته مسموح بس لليوزرز في واحدة من المجموعات $1.',
'badaccess-groups' => 'الفعل الذي طلبته مسموح بيه بس لليوزرز  اللي في واحدة من المجموعات دي  $1.',

'versionrequired'     => 'لازم نسخة $1 من ميدياويكي',
'versionrequiredtext' => ' النسخة $1 من ميدياويكي لازم علشان تستعمل الصفحة دي . شوف [[Special:Version|صفحة النسخة]]',

'ok'                      => 'موافئ',
'retrievedfrom'           => 'اتجابت من "$1"',
'youhavenewmessages'      => 'عندك $1 ($2).',
'newmessageslink'         => 'رسايل جديده',
'newmessagesdifflink'     => 'اخر تعديل',
'youhavenewmessagesmulti' => 'عندك ميسيدج جديدة في $1',
'editsection'             => 'تعديل',
'editold'                 => 'تعديل',
'viewsourceold'           => 'عرض المصدر',
'editsectionhint'         => 'تعديل جزء : $1',
'toc'                     => 'المحتويات',
'showtoc'                 => 'عرض',
'hidetoc'                 => 'تخبيه',
'thisisdeleted'           => 'عرض او استرجاع $1؟',
'viewdeleted'             => 'عرض $1؟',
'restorelink'             => '{{PLURAL:$1|تعديل واحد ملغي|تعديلين ملغيين|$1 تعديلات ملغية|$1 تعديل ملغي|$1 تعديل ملغي}}',
'feedlinks'               => '(فييد) تلقيم:',
'feed-invalid'            => 'نوع اشتراك التغذية مش صح.',
'feed-unavailable'        => 'التغذية مش متوفرة في {{SITENAME}}',
'site-rss-feed'           => '$1   ار‌ اس‌ اس فييد',
'site-atom-feed'          => '$1 اتوم فييد',
'page-rss-feed'           => '"$1" ار‌ اس‌ اس فييد',
'page-atom-feed'          => '"$1" فييد أتوم',
'red-link-title'          => '$1 (لسة ما اتكتبت ش )',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحة',
'nstab-user'      => 'صفحة يوزر',
'nstab-media'     => 'صفحة ميديا',
'nstab-special'   => 'مخصوص',
'nstab-project'   => 'صفحة مشروع',
'nstab-image'     => 'فايل',
'nstab-mediawiki' => 'رساله',
'nstab-template'  => 'قالب',
'nstab-help'      => 'صفحة مساعدة',
'nstab-category'  => 'تصنيف',

# Main script and global functions
'nosuchaction'      => 'مافيش فعل زى كده',
'nosuchactiontext'  => ' الويكي ما تعرفتش علي الامر في ال URL',
'nosuchspecialpage' => 'مافيش صفحة خاصة بالاسم ده',
'nospecialpagetext' => "<big>'''انت طلبت صفحة مخصوصة مش صحيحة.'''</big>

لستة الصفحات المخصوصة الصحيحة ممكن تلاقيها في [[Special:Specialpages]].",

# General errors
'error'                => 'غلطة',
'databaseerror'        => 'غلط في قاعدة البيانات',
'dberrortext'          => 'حصل غلط في صيغة الاستعلام.
ممكن يكون في عيب في البرنامج.
آخر استعلام اتطلب من قاعدة البيانات كان:
<blockquote><tt>$1</tt></blockquote>
من جوا الدالة "<tt>$2</tt>".
MySQL  رجعت الغلط "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'حصل غلط في صيغة الاستعلام.
آخر استعلام اتطلب من قاعدة البيانات كان:
"$1"
من جوا الدالة "$2".
MySQL رجعت الغلط "$3: $4"',
'noconnect'            => 'متأسفين،الويكي  عندها شوية مشاكل فنية و مش قادرة توصل ب سيرفر الداتابيز. <br />
$1',
'nodb'                 => 'ماقدرناش نختار قاعدة البيانات $1',
'cachederror'          => 'دي نسخة متخبية من الصفحة اللي طلبتها، و ممكن ما تكونش متحدثة.',
'laggedslavemode'      => 'تحذير: الصفحة يمكن ما فيهاش اخر التحديثات.',
'readonly'             => 'قاعدة البيانات مقفولة',
'enterlockreason'      => 'اكتب سبب القفل، وقول امتى تقريبا ح يتلغى القفل',
'readonlytext'         => 'قاعدة البيانات مقفولة دلوقتي قدام المدخلات الجديدة والتعديلات االتانية، يمكن تكون الصيانة الدورية هي السبب ،و بعديها  قاعدة البيانات ح ترجع للوضع الطبيعي .

الإداري اللي قفل قاعدة البيانات هو اللي كتب التفسير دا:
$1',
'missing-article'      => 'قاعدة البيانات ما لقتش النص الخاص بتاع صفحة كان لازم تلاقيها و اسمها "$1" $2.

عادة دا بيحصل لما تدوس على لينكات قديمة، فرق التعديل أو التاريخ، اللي بتوصلك ل صفحة ملغية.

اذا ما كانش هو دا السبب ،ممكن عندك غلط في البرامج .
لو سمحت تبلغ واحد من الاداريين و  تديله ال لينك بتاعة الصفحة .',
'missingarticle-rev'   => '(رقم المراجعة: $1)',
'missingarticle-diff'  => '(فرق: $1، $2)',
'readonly_lag'         => 'قاعدة البيانات  اتقفلت اوتوماتيكي علشان تقدر السيرفرات الفرعية تلحق السيرفر الرئيسي',
'internalerror'        => 'غلط داخلي',
'internalerror_info'   => 'غلط داخلي: $1',
'filecopyerror'        => 'ما قدرنا ش  ننسخ الملف "$1" لـ "$2".',
'filerenameerror'      => 'ما قدر نا ش نغير اسم الملف "$1" لـ "$2".',
'filedeleteerror'      => 'ما قدرنا ش نمسح الملف "$1".',
'directorycreateerror' => 'ما قدرناش نعمل المجلد "$1".',
'filenotfound'         => 'مش قادرين نلاقي الملف "$1".',
'fileexistserror'      => 'ما قدرناش نكتب في الملف "$1": الملف موجود',
'unexpected'           => 'قيمة مش متوقعة: "$1"="$2".',
'formerror'            => 'غلط: مش ممكن تقديم الاستمارة',
'badarticleerror'      => 'مش ممكن ننفذ العملية دي على الصفحة دي',
'cannotdelete'         => 'ما قدرناش نمسح الصفحة أو الملف المطلوب. (ممكن يكون حد تاني مسحه. )',
'badtitle'             => 'عنوان غلط',
'badtitletext'         => 'عنوان الصفحه المطلوب اما مش صحيح او فاضي، و ربما الوصلة بين اللغات أو بين المشاريع غلط. وممكن وجود رموز ماتصلحش للاستخدام في العناوين.',
'perfdisabled'         => 'متأسفين!  الخاصية دي اتعطلت بشكل مؤقت لأنها بتبطئ قاعدة البيانات لدرجة ان مافيش حد ممكن يستخدم الويكي.',
'perfcached'           => 'البيانات دي متخبية و ممكن ما تكونش متحدثة.',
'perfcachedts'         => 'البيانات دي متخبية، آخر تحديث ليها كان في $1.',
'querypage-no-updates' => 'التحديثات بتاعةالصفحة دي متعطلة دلوقتي. البيانات اللي هنا مش ح تتحدث في الوقت الحاضر.',
'wrong_wfQuery_params' => 'محددات غلط في wfQuery()<br />
الدالة: $1<br />
الاستعلام: $2',
'viewsource'           => 'عرض المصدر',
'viewsourcefor'        => 'ل $1',
'actionthrottled'      => 'الامر دا  اتخنق',
'actionthrottledtext'  => 'علشان نمنع ال سبام ،أنت ممنوع تعمل  الفعل دا عدد كبير من المرات في فترة زمنية قصيرة، و انت ا تجاوزت  الحد دا . لو سمحت تحاول مرة ثانية بعد دقائق.',
'protectedpagetext'    => 'الصفحة دي اتقفلت في وش التعديل.',
'viewsourcetext'       => 'ممكن تشوف وتنسخ مصدر  الصفحه دى:',
'protectedinterface'   => 'الصفحة دي هي اللي بتوفر نص الواجهة بتاعة البرنامج،وهي مقفولة لمنع التخريب.',
'editinginterface'     => "'''تحذير''': أنت بتعدل صفحة بتستخدم في الواجهة النصية  بتاعة البرنامج. التغييرات في الصفحة دي ح تأثر على مظهر واجهة المستخدم للمستخدمين االتانيين. للترجمات، لو سمحت استخدم [http://translatewiki.net/wiki/Main_Page?setlang=ar بيتاويكي]، مشروع ترجمة الميدياويكي.",
'sqlhidden'            => '(استعلام إس‌كيو‌إل متخبي)',
'cascadeprotected'     => 'الصفحة دي محمية من التعديل، بسبب انها مدمجة في {{PLURAL:$1|الصفحة|الصفحات}} دي، اللي مستعمل فيها خاصية "حماية الصفحات المدمجة" :
$2',
'namespaceprotected'   => "ما عندكش صلاحية تعديل الصفحات  اللي في نطاق '''$1'''.",
'customcssjsprotected' => 'ماعندكش صلاحية تعديل  الصفحة دي، علشان فيها الإعدادات الشخصية بتاعة يوزر تاني.',
'ns-specialprotected'  => 'الصفحات المخصوصة مش ممكن تعديلها.',
'titleprotected'       => "العنوان دا محمي من الإنشاء بـ[[User:$1|$1]]. السبب هو ''$2''.",

# Virus scanner
'virus-unknownscanner' => 'انتي فيروس مش معروف:',

# Login and logout pages
'logouttitle'                => 'خروج اليوزر',
'logouttext'                 => '<strong>أنت دلوقتي مش مسجل دخولك.</strong><br />
تقدر تكمل استعمال {{SITENAME}} على انك مجهول، أو الدخول مرة تانية بنفس الاسم أو باسم تاني. ممكن تشوف بعض الصفحات  كأنك متسجل ، و دا علشان استعمال الصفحات المتخبية في المتصفح بتاعك.',
'welcomecreation'            => '== اهلاً و سهلاً يا $1! ==

اتفتحلك حساب. ما تنساش تغير تفضيلاتك في {{SITENAME}}.',
'loginpagetitle'             => 'دخول اليوزر',
'yourname'                   => ' اليوزرنيم:',
'yourpassword'               => 'الباسوورد (كلمة السر):',
'yourpasswordagain'          => 'اكتب الباسورد تاني:',
'remembermypassword'         => 'افتكر بيانات دخولى على  الكمبيوتر ده',
'yourdomainname'             => 'النطاق بتاعك:',
'externaldberror'            => 'يا إما في حاجة غلط في الدخول على قاعدة البيانات الخارجية أو انت مش مسموح لك تعمل تحديث لحسابك الخارجي.',
'loginproblem'               => '<b>حصلت مشكلة وانت بتسجل دخولك.</b><br />لو سمحت تحاول مرة تانية!',
'login'                      => 'دخول',
'nav-login-createaccount'    => 'دخول / فتح حساب',
'loginprompt'                => 'لازم تكون الكوكيز عندك مفعله علشان تقدر تدخل ل {{SITENAME}}.',
'userlogin'                  => 'دخول / فتح حساب',
'logout'                     => 'خروج',
'userlogout'                 => 'خروج',
'notloggedin'                => 'انت مش مسجل دخولك',
'nologin'                    => 'مشتركتش لسه؟ $1.',
'nologinlink'                => ' افتح حساب',
'createaccount'              => 'افتح حساب',
'gotaccount'                 => 'عندك حساب؟ $1.',
'gotaccountlink'             => 'دخول',
'createaccountmail'          => 'بـ الايميل',
'badretype'                  => 'كلمتين السر اللى  كتبتهم مش  زى بعضهم',
'userexists'                 => 'اسم اليوزر اللي دخلته ب يستعمله يوزر غيرك. لو سمحت دخل اسم تاني.',
'youremail'                  => 'الايميل:',
'username'                   => 'اسم اليوزر:',
'uid'                        => 'رقم اليوزر:',
'prefs-memberingroups'       => 'عضو في {{PLURAL:$1|مجموعة|مجموعة}}:',
'yourrealname'               => 'الاسم الحقيقى:',
'yourlanguage'               => 'اللغة:',
'yournick'                   => 'الإمضا:',
'badsig'                     => 'الامضا الخام بتاعتك مش صح؛ اتإكد من التاجز بتاعة الHTML.',
'badsiglength'               => 'الإمضا بتاعتك طويلة جدا.
لازم تكون اقل من $1 {{PLURAL:$1|حرف|حروف}}.',
'email'                      => 'الإيميل',
'prefs-help-realname'        => 'الاسم الحقيقي اختيارى ولو اخترت تعرض اسمك هنا هايستخدم في الإشارة لمساهماتك.',
'loginerror'                 => 'غلط في الدخول',
'prefs-help-email'           => 'تدخيل الايميل حاجة اختيارية، بس هو بيخلي اليوزرز التانيين يقدروا يتصلوا بيك  في صفحتك او صفة المناقشة بتاعتك من غير ما يعرفو  انت مين.',
'prefs-help-email-required'  => 'عنوان الإيميل مطلوب.',
'nocookiesnew'               => 'اليوزر خلاص اتفتح له حساب، بس انت لسة ما سجلتش دخولك. بيستخدم {{SITENAME}} كوكيز عشان يسجل الدخول . الكوكيز عندك متعطلة. لو سمحت  تخليها تشتغل، بعدين أدخل ب اسم الحساب و الباسورد الجداد..',
'nocookieslogin'             => '{{SITENAME}} بيستخدم الكوكيز  علشان تسجيل الدخول؛  الكوكيز عندك متعطلة؛ لو سمحت تخليها تشتغل و بعدين حاول مرة تانية.',
'noname'                     => 'انت ما حددتش اسم يوزر صحيح.',
'loginsuccesstitle'          => 'تم الدخول بشكل صحيح',
'loginsuccess'               => "'''تم تسجيل دخولك{{SITENAME}} باسم \"\$1\".'''",
'nosuchuser'                 => 'مافيش يوزر باسم "$1".
اتاكد من تهجية الاسم، او افتح حساب جديد.',
'nosuchusershort'            => 'مافيش يوزر باسم <nowiki>$1</nowiki>". اتاكد من تهجية الاسم.',
'nouserspecified'            => ' لازم تحدد اسم يوزر.',
'wrongpassword'              => 'كلمة السر اللى كتبتها مش صحيحه. من فضلك حاول تانى.',
'wrongpasswordempty'         => 'كلمة السر المدخله كانت فاضيه. من فضلك حاول تانى.',
'passwordtooshort'           => 'كلمة السر اللي اخترتها مش صحيحه أو قصيره قوي. لازم مايقلش طول الكلمه عن {{PLURAL:$1|1 حرف|$1 حرف}} وتكون مختلفه عن اسم اليوزر بتاعك.',
'mailmypassword'             => 'ابعتلى كلمة السر فى ايميل.',
'passwordremindertitle'      => 'كلمة سر مؤقته جديده ل {{SITENAME}}',
'passwordremindertext'       => 'فيه شخص ما (غالبا انت، من عنوان الااى بى $1)  طلب اننا نرسل لك كلمة سر جديده لـ{{SITENAME}} ($4).

كلمة السر لليوزر "$2" الآن هي "$3".
عليك انك تدخل على الموقع وتغير كلمة السر بتاعتك دلوقتى.

لو مكنتش  انت اللى طلب كلمة السر أو انك افتكرت كلمة السر اللى قبل كده ومش عايز تغيرها ممكن تتجاهل الرساله دى وتستمر في استخدام كلمة السر بتاعتك اللى قبل كده.',
'noemail'                    => 'مافيش ايميل متسجل  لليوزر  "$1".',
'passwordsent'               => '
تم إرسال كلمة سر جديدة لعنوان الايميل المتسجل لليوزر "$1".من فضلك حاول تسجيل الدخول مره تانيه بعد استلامها.',
'blocked-mailpassword'       => 'عنوان الايبي بتاعك ممنوع من التحرير، و كمان مش ممكن تسعمل خاصية ترجيع الباسورد علشان نمنع التخريب.',
'eauthentsent'               => 'فيه ايميل تأكيد اتبعت  للعنوان اللى كتبته.  علشان تبعت اي ايميل تانى للحساب ده لازم تتبع التعليمات اللى فى الايميل اللى اتبعتلك  علشان تأكد ان  الحساب ده بتاعك .',
'throttled-mailpassword'     => 'بعتنالك علشان تفتكر الباسورد بتاعتك، في خلال الـ{{PLURAL:$1|ساعة|$1 ساعة}} اللي فاتت.
علشان منع التخريب، ح نفكرك مرة و احدة بس كل
{{PLURAL:$1|ساعة|$1 ساعة}}.',
'mailerror'                  => ' غلط في بعتان الايميل : $1',
'acct_creation_throttle_hit' => 'متأسفين، انت عندك $1 حساب. مش ممكن نفتح واحد تاني.',
'emailauthenticated'         => 'اتأكدنا من الايميل بتاعك  في $1.',
'emailnotauthenticated'      => 'لسة ما اتكدناش من الايميل بتاعك. مش ح يتبعتلك اي  ايميلات بخصوص الميزات دي.',
'noemailprefs'               => 'علشان الخصايص دي تشتغل لازم تحددلك عنوان ايميل.',
'emailconfirmlink'           => 'أكد عنوان الإيميل بتاعك',
'invalidemailaddress'        => 'مش ممكن نقبل عنوان الايميل لانه مش مظبوط. لو سمجت تدخل ايميل مظبوط او تمسحه من الخانة.',
'accountcreated'             => 'الحساب اتفتح',
'accountcreatedtext'         => 'اتفتح حساب لليوزر ب$1.',
'createaccount-title'        => 'فتح حساب في {{SITENAME}}',
'createaccount-text'         => 'في واحد فتح حساب باسم الايمل بتاعك على {{SITENAME}} ($4) بالاسم "$2"، وبباسورد "$3". لازم تسجل دخولك دلوقتي و تغير الباسورد بتاعتك.

لو سمحت تتجاهل الرسالة دي اذا الحساب دا اتفتحلك بالغلط.',
'loginlanguagelabel'         => 'اللغة: $1',

# Password reset dialog
'resetpass'               => 'غير الباسورد بتاعة الحساب',
'resetpass_announce'      => 'اتسجل دخولك دلوقتي بالكود اللي اتبعتلك في الايميل. علشان تخلص عملية الدخول ،لازم تعملك باسورد جديدة هنا:',
'resetpass_header'        => 'غير الباسورد',
'resetpass_submit'        => 'اظبط الباسورد و ادخل',
'resetpass_success'       => 'الباسورد بتاعتك اتغيرت بنجاح! دلوقتي  بنسجل دخولك...',
'resetpass_bad_temporary' => 'الباسورد المؤقتة دي غلط. يمكن الباسورد الاصلية تكون اتغيرت بنحاح أو يمكن انت كنت طلبت باسورد مؤقتة جديدة.',
'resetpass_forbidden'     => 'مش ممكن تغيير الباسورد في {{SITENAME}}',
'resetpass_missing'       => 'مافيش اي بيانات.',

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
'summary'                   => 'ملخص',
'subject'                   => ' راس الموضوع/موضوع',
'minoredit'                 => ' التعديل ده تعديل صغير',
'watchthis'                 => 'راقب الصفحه دى',
'savearticle'               => 'سييف الصفحه',
'preview'                   => 'بروفه',
'showpreview'               => 'عرض البروفه',
'showlivepreview'           => 'بروفه حيه',
'showdiff'                  => 'بيين التعديلات',
'anoneditwarning'           => "'''تحذير:''' انت ما عملتش لوجين؛ عنوان الااى  بى  بتاعك هايتسجل فى تاريخ الصفحه .",
'missingsummary'            => "'''خد بالك:''' انت ما كتبتش ملخص للتعديل. لو دوست على حفظ الصفحة مرة تانية التعديل بتاعك ح يتحفظ من غير ملخص.",
'missingcommenttext'        => 'لو سمحت اكتب تعليق تحت.',
'missingcommentheader'      => "'''خد بالك:''' انت ما كتبتش عنوان\\موضوع للتعليق دا، لو دوست على حفظ الصفحة مرة تانية، تعليقك ح يتحفظ من غير عنوان.",
'summary-preview'           => 'بروفه للملخص',
'subject-preview'           => 'بروفة للعنوان\\الموضوع',
'blockedtitle'              => 'اليوزر ممنوع',
'blockedtext'               => "

<big>'''تم منع اسم اليوزر أو عنوان الااى بى بتاعك .'''</big>

سبب المنع هو: ''$2''. وقام بالمنع $1.

* بداية المنع: $8
* انتهاء المنع: $6
* الممنوع المقصود: $7

ممكن التواصل مع $1 لمناقشة المنع، أو مع واحد من [[{{MediaWiki:Grouppage-sysop}}|الاداريين]] عن المنع>
افتكر انه مش ممكن تبعت ايميل  لليوزرز الا اذا كنت سجلت عنوان ايميل صحيح فى صفحة [[Special:Preferences|التفضيلات]] بتاعتك.
عنوان الااى بى بتاعك حاليا هو $3 وكود المنع هو #$5.من فضلك ضيف اى واحد منهم أو كلاهما في اى رسالة للتساؤل عن المنع.",
'autoblockedtext'           => 'عنوان الأيبي بتاعك اتمنع اتوماتيكي  علشان في يوزر تاني استخدمه واللي هو كمان ممنوع بــ $1.
السبب هو:

:\'\'$2\'\'

* بداية المنع: $8
* انهاية المنع: $6

ممكن تتصل  ب $1 أو واحد من 
[[{{MediaWiki:Grouppage-sysop}}|الإداريين]] االتانيين لمناقشة المنع.

لاحظ أنه مش ممكن استخدام خاصية "ابعت رسالة لليوزر دا" إلا اذا كان عندك ايميل صحيح متسجل في [[Special:Preferences|تفضيلاتك]].

رقم المنع هو $5. لو سمحت تذكر الرقم دا في اي استفسار.',
'blockednoreason'           => 'ما فيش سبب',
'blockedoriginalsource'     => "المصدر بتاع '''$1''' معروض تحت:",
'blockededitsource'         => "نص '''تعديلاتك''' في '''$1''' معروض هنا:",
'whitelistedittitle'        => 'لازم تسجل دخولك علشان تقدر تعدل',
'whitelistedittext'         => 'لازم $1 علشان تقدر تعدل الصفحات.',
'whitelistreadtitle'        => 'تسجيل الدخول لازم  علشان تقرا',
'whitelistreadtext'         => 'لازم [[Special:Userlogin|تسجيل الدخول]] علشان تقرا الصفحات.',
'whitelistacctitle'         => 'انت مش مسموح لك تفتح حساب',
'whitelistacctext'          => 'علشان نسمح لك تفتح حسابات في {{SITENAME}} لازم [[Special:Userlogin|تسجيل الدخول]] وأن يكون عندك الصلاحية المناسبة.',
'confirmedittitle'          => 'علشان تبتدي تعدل، لازم نتاكد من الايميل بتاعك',
'confirmedittext'           => 'قبل ما تبتدي تعدل لازم نتأكد من الايميل بتاعك. لو سمحت تكتب وتأكد الايميل بتاعك  في[[Special:Preferences|تفضيلاتك]]',
'nosuchsectiontitle'        => 'مافيش قسم بالاسم ده',
'nosuchsectiontext'         => 'انت حاولت تعمل تعديل على قسم مش موجود. و علشان القسم $1 مش موجود اصلاً، فمش ممكن نحفظ التعديلات بتاعتك.',
'loginreqtitle'             => 'لازم تسجل دخولك',
'loginreqlink'              => 'ادخل',
'loginreqpagetext'          => 'لازم تكون $1 علشان تشوف صفحات تانية.',
'accmailtitle'              => ' كلمة السر اتبعتت .',
'accmailtext'               => "الباسورد بتاعة '$1' اتبعتت لـ $2.",
'newarticle'                => '(جديد)',
'newarticletext'            => "انت وصلت لصفحه مابتدتش لسه.
علشان  تبتدى الصفحة ابتدى الكتابه في الصندوق اللى تحت.
(بص على [[{{MediaWiki:Helppage}}|صفحة المساعده]] علشان معلومات اكتر)
لو كانت زيارتك للصفحه دى بالخطأ، اضغط على زر ''رجوع'' في متصفح الإنترنت عندك.",
'anontalkpagetext'          => "----'' صفحة النقاش دي بتاعة يوزر مجهول لسة ما فتحش لنفسه حساب أو عنده واحد بس ما بيستعملوش. علشان كدا لازم تستعمل رقم الأيبي علشان تتعرف عليه/عليها. العنوان دا ممكن اكتر من واحد يكونو بيستعملوه. لو انت يوزر مجهول و حاسس  ان في تعليقات بتتوجهلك مع انك مالكش دعوة بيها ،من فضلك [[Special:Userlogin|افتحلك حساب أو سجل الدخول]] علشان تتجنب اللخبطة اللي ممكن تحصل في المستقبل مع يوزرز مجهولين تانيين.''",
'noarticletext'             => 'مافيش  دلوقتى اى نص فى  الصفحه دى ، ممكن [[Special:Search/{{PAGENAME}}|تدور على عنوان الصفحه]] في الصفحات التانيه او [{{fullurl:{{FULLPAGENAME}}|action=edit}} تعدل الصفحه دى].',
'userpage-userdoesnotexist' => 'حساب اليوزر "$1" مش متسجل. لو سمحت تشوف لو عايز تبتدي/تعدل الصفحة دي.',
'updated'                   => '(متحدثة)',
'note'                      => '<strong>ملحوظه:</strong>',
'previewnote'               => '<strong> دى بروفه للصفحه بس، ولسه ما تسييفتش!</strong>',
'previewconflict'           => 'البروفة دي بتبينلك فوق إزاي ح يكون شكل النص لو انت دوست على حفظ',
'editing'                   => 'تعديل $1',
'editingsection'            => 'تعديل $1 (جزء)',
'editingcomment'            => 'تعديل $1 (تعليق)',
'editconflict'              => 'تضارب فى التحرير: $1',
'yourtext'                  => 'النص بتاعك',
'storedversion'             => 'النسخة المخزنة',
'yourdiff'                  => 'الفروق',
'copyrightwarning'          => 'من فضلك لاحظ ان كل المساهمات فى {{SITENAME}} بتتنشر حسب شروط ترخيص $2 (بص على $1 علشان تعرف  تفاصيل اكتر)
لو مش عايز كتابتك تتعدل او تتوزع من غير مقابل و بدون اذنك ، ما تحطهاش هنا<br />. كمان انت  بتتعهد بانك كتبت كلام تعديلك بنفسك، او نسخته من مصدر يعتبر ضمن الملكيه العامه، أو مصدر حر تان.

<strong>ما تحطش اى عمل له حقوق محفوظه بدون اذن صاحب الحق</strong>.',
'copyrightwarning2'         => 'لو سمحت تعمل حسابك ان كل مشاركاتك في {{SITENAME}} ممكن المشاركين التانيين يعدلوها،يغيروها، او يمسحوها خالص. لو مانتش حابب ان كتاباتك تتعدل و تتغير بالشكل دا، فياريت ما تنشرهاش هنا.<br />.
و كمان انت بتدينا كلمة شرف  انك صاحب الكتابات دي، او انك نقلتها من مكان مش خاضع لحقوق النشر .(شوف التفاصيل في $1 ).
<strong>لو سمحت ما تحطش هنا اي نص خاضع لحقوق النشر من غير تصريح!</strong>.',
'longpagewarning'           => '
<strong>تحذير: الصفحه دى حجمها $1 كيلوبايت، بعض المتصفحات (براوزرز) ممكن تواجه مشاكل لما تحاول تعديل صفحات يزيد حجمها عن 32 كيلوبايت. من فضلك ,لو امكن قسم الصفحة لصفحات اصغر فى الحجم.</strong>',
'templatesused'             => 'القوالب المستعمله في الصفحه دى:',
'templatesusedpreview'      => 'القوالب المستعمله فى البروفه دى:',
'templatesusedsection'      => 'القوالب اللي بتستخدم في القسم دا:',
'template-protected'        => '(حمايه كامله)',
'template-semiprotected'    => '(حمايه جزئيه )',
'nocreatetitle'             => 'إنشاء الصفحات اتحدد',
'nocreatetext'              => '{{SITENAME}} حدد القدره على انشاء صفحات جديده.
ممكن ترجع وتحرر صفحه موجوده بالفعل، او [[Special:Userlogin|الدخول / فتح حساب]].',
'nocreate-loggedin'         => 'انت ما عندك ش صلاحية تعمل صفحات جديدة في {{SITENAME}}.',
'permissionserrorstext'     => 'ما عندك ش صلاحية تعمل كدا،{{PLURAL:$1|علشان|علشان}}:',
'recreate-deleted-warn'     => "'''تحذير: انت بتعيد انشاء صفحه اتمسحت قبل كده.'''
لازم تتأكد من ان الاستمرار فى تحرير الصفحه دى ملائم.
سجل الحذف للصفحه دى معروض هنا:",

# Account creation failure
'cantcreateaccounttitle' => 'مش ممكن فتح حساب',

# History pages
'viewpagelogs'        => 'عرض السجلات للصفحه دى',
'nohistory'           => 'الصفحة دي ما لهاش تاريخ تعديل.',
'revnotfound'         => 'النسخة مش موجودة',
'currentrev'          => 'النسخه دلوقتى',
'revisionasof'        => 'تعديلات من $1',
'revision-info'       => 'نسخه $1 بواسطة $2',
'previousrevision'    => '←نسخه اقدم',
'nextrevision'        => 'نسخه احدث→',
'currentrevisionlink' => 'النسخه دلوقتى',
'cur'                 => 'دلوقتى',
'next'                => 'اللى بعد كده',
'last'                => 'قبل كده',
'page_first'          => 'الاولى',
'page_last'           => 'الاخيره',
'histlegend'          => 'اختيار الفرق: علم على صناديق النسخ للمقارنه و اضغط قارن بين النسخ المختاره او الزر اللى تحت.<br />
مفتاح: (دلوقتى) = الفرق مع النسخة دلوقتى
(اللى قبل كده) = الفرق مع النسخة اللى قبل كده، ص = تعديل صغير',
'deletedrev'          => '[ممسوحة]',
'histfirst'           => 'اول',
'histlast'            => 'آخر',
'historyempty'        => '(فاضى)',

# Revision feed
'history-feed-title'          => 'تاريخ المراجعة',
'history-feed-item-nocomment' => '$1 فى $2', # user at time
'history-feed-empty'          => 'الصفحة المطلوبة مش موجودة. من المحتمل تكون الصفحة أتمسحت أو أتنقلت. حاول [[Special:Search|التدوير 
 فى الويكى]] عن صفحات جديدة ذات صلة.',

# Revision deletion
'rev-deleted-comment'    => '(التعليق اتشال)',
'rev-deleted-user'       => '(اسم اليوزر اتشال)',
'rev-deleted-event'      => '(السجل إتشال)',
'rev-delundel'           => 'عرض/إخفاء',
'revisiondelete'         => 'امسح/الغي المسح بتاع المراجعات',
'revdelete-hide-text'    => 'إخفاء نص النسخة',
'revdelete-hide-comment' => 'إخفاء تعليق التعديل',
'revdelete-hide-user'    => 'خبي اسم/عنوان الاي بي بتاع اليوزر',
'revdelete-hide-image'   => 'خبي المحتويات بتاعة الملف',
'revdelete-log'          => 'تعليق  على السجل:',
'revdel-restore'         => 'تغيير الرؤية',
'pagehist'               => 'تاريخ الصفحة',
'deletedhist'            => 'التاريخ الممسوح',
'revdelete-content'      => 'محتويات',
'revdelete-summary'      => 'ملخص التعديل',
'revdelete-uname'        => 'اسم اليوزر',
'revdelete-hid'          => 'أخفى $1',
'revdelete-unhid'        => 'أظهر $1',

# Suppression log
'suppressionlog' => 'سجل الإخفاء',

# History merging
'mergehistory'        => 'دمج تواريخ الصفحة',
'mergehistory-from'   => 'الصفحه المصدر:',
'mergehistory-into'   => 'الصفحه الهدف:',
'mergehistory-list'   => 'تاريخ التعديل اللي ممكن يتدمج',
'mergehistory-go'     => 'عرض التعديلات اللي ممكن تتدمج',
'mergehistory-submit' => 'دمج النسخ',

# Diffs
'history-title'           => 'تاريخ تعديل "$1"',
'difference'              => '(الفرق بين النسخ)',
'lineno'                  => 'سطر $1:',
'compareselectedversions' => 'قارن بين النسختين المختارتين',
'editundo'                => 'استرجاع',
'diff-multi'              => '({{PLURAL:$1|نسخه واحده متوسطه|$1 نسخه متوسطه}} مش معروضه.)',

# Search results
'searchresults'             => 'نتايج التدوير',
'searchresulttext'          => 'لو عايز تعرف اكتر عن التدوير في {{SITENAME}}، شوف [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => "انت كنت بتدور على '''[[:$1]]'''",
'searchsubtitleinvalid'     => "انت دورت على '''$1'''",
'noexactmatch'              => "'''مافيش  صفحه بالاسم \"\$1\"'''. ممكن [[:\$1| تبتدى الصفحه دى]].",
'noexactmatch-nocreate'     => "'''مافيش صفحة بالاسم \"\$1\".'''",
'prevn'                     => '$1 اللى قبل كده',
'nextn'                     => '$1 اللى بعد كده',
'viewprevnext'              => 'بص ($1) ($2) ($3)',
'search-suggest'            => 'قصدك: $1',
'search-interwiki-caption'  => 'المشاريع الشقيقة',
'search-interwiki-default'  => '$1 نتيجة:',
'search-interwiki-more'     => '(اأكتر)',
'search-mwsuggest-enabled'  => 'مع اقتراحات',
'search-mwsuggest-disabled' => 'مافيش اقتراحات',
'searchall'                 => 'الكل',
'powersearch'               => 'تدوير متفصل',
'powersearch-legend'        => 'تدوير متقدم',
'powersearch-redir'         => 'لستة التحويلات',
'powersearch-field'         => 'تدوير على',

# Preferences page
'preferences'           => 'تفضيلات',
'mypreferences'         => 'تفضيلاتى',
'prefs-edits'           => 'عدد التعديلات:',
'prefsnologintext'      => 'لازم تكون [[Special:Userlogin|متسجل]] علشان تقدر تعدل تفضيلاتك .',
'qbsettings-none'       => 'ما في ش',
'qbsettings-fixedleft'  => 'متثبت في الشمال',
'qbsettings-fixedright' => 'متثبت في اليمين',
'changepassword'        => 'غير الباسورد',
'skin'                  => 'الوش',
'math'                  => 'رياضة',
'dateformat'            => 'طريقة كتابة التاريخ',
'datedefault'           => 'مافبش تفضيل',
'datetime'              => 'وقت وتاريخ',
'math_failure'          => 'الاعراب فشل',
'math_unknown_error'    => 'غلط مش معروف',
'math_unknown_function' => 'وظيفة مش معروفة',
'math_lexing_error'     => 'غلط في الكلمة',
'math_syntax_error'     => 'غلط في تركيب الجملة',
'prefs-personal'        => 'البروفيل بتاع اليوزر',
'prefs-rc'              => 'اخر التغييرات',
'prefs-watchlist'       => 'لستة المراقبة',
'prefs-watchlist-days'  => 'عدد الأيام للعرض في لستة المراقبة:',
'prefs-watchlist-edits' => 'عدد التعديلات اللي بتتعرض في لستةالمراقبة المتوسعة:',
'prefs-misc'            => 'متفرقات',
'saveprefs'             => 'حفظ',
'resetprefs'            => 'امسح التغييرات اللي مش المحفوظة',
'oldpassword'           => 'الباسورد القديمة:',
'newpassword'           => 'الباسورد جديدة:',
'retypenew'             => 'اكتب كلمة السر الجديده تانى:',
'textboxsize'           => 'تعديل',
'rows'                  => 'صفوف:',
'columns'               => 'عمدان:',
'searchresultshead'     => 'تدوير',
'resultsperpage'        => 'عدد النتايج في الصفحة:',
'contextlines'          => 'عدد  السطور في كل نتيجة:',
'contextchars'          => 'عدد  الحروف في كل سطر',
'stub-threshold'        => 'الحد لتنسيق <a href="#" class="stub">لينك البذرة</a>:',
'recentchangesdays'     => 'عدد الأيام المعروضة في اخرالتغييرات:',
'recentchangescount'    => 'عدد التعديلات للعرض في اخر التغييرات، صفحات التواريخ والسجلات:',
'savedprefs'            => 'التفضيلات بتاعتك اتحفظت.',
'timezonelegend'        => 'منطقة التوقيت',
'timezonetext'          => '¹الفرق في الساعات بين توقيتك المحلي و توقيت السيرفر (UTC).',
'localtime'             => 'التوقيت المحلي',
'timezoneoffset'        => 'الفرق¹',
'servertime'            => 'توقيت السيرفر',
'guesstimezone'         => 'دخل التوقيت من البراوزر',
'allowemail'            => 'السماح لليوزرز التانيين يبعتولي ايميل',
'prefs-searchoptions'   => 'اختيارات التدوير',
'prefs-namespaces'      => 'أسماء النطاقات',
'defaultns'             => 'دور في النطاقات دي اوتوماتيكي:',
'default'               => 'اوتوماتيكي',
'files'                 => 'ملفات',

# User rights
'userrights'                       => 'إدارة الحقوق بتاعة اليوزر', # Not used as normal message but as header for the special page itself
'userrights-user-editname'         => 'دخل اسم يوزر:',
'userrights-editusergroup'         => 'تعديل مجموعات اليوزر',
'userrights-groupsmember'          => 'عضو في:',
'userrights-groupsavailable'       => 'المجموعات المتوفرة:',
'userrights-reason'                => 'سبب التغيير:',
'userrights-available-remove'      => 'انت تقدر تشيل اي يوزر من {{PLURAL:$2|المجموعة دي|المجموعات دي}}: $1.',
'userrights-available-add-self'    => 'انت تقدر تضيف نفسك لـ {{PLURAL:$2|المجموعة دي|المجموعات دي}}: $1.',
'userrights-available-remove-self' => 'انت تقدر تشيل نفسك من {{PLURAL:$2|المجموعة دي|المجموعات دي}}: $1.',
'userrights-no-interwiki'          => 'أنت  مش من حقك تعدل صلاحيات اليوزرز على الويكيات التانية.',
'userrights-nodatabase'            => 'قاعدة البيانات $1  مش موجودة أو مش محلية.',
'userrights-nologin'               => 'انت لازم [[Special:Userlogin|تسجيل الدخول]] بحساب  مدير لتعديل حقوق اليوزر.',
'userrights-notallowed'            => 'حسابك  ماعندوش  إذن لتعديل حقوق اليوزر.',
'userrights-changeable-col'        => 'المجموعات اللي تقدر تغييرها',

# Groups
'group'      => 'المجموعة:',
'group-user' => 'يوزرز',

'group-user-member'     => 'يوزر',
'group-suppress-member' => 'أوفرسايت',

'grouppage-user'  => '{{ns:project}}:يوزرز',
'grouppage-sysop' => '{{ns:project}}:اداريين',

# Rights
'right-read'           => 'قراية الصفحات',
'right-upload'         => 'حمل الملفات',
'right-autoconfirmed'  => 'تعديل الصفحات  النص محميه',
'right-delete'         => 'مسح الصفحات',
'right-bigdelete'      => 'مسح الصفحات اللي ليها تواريخ كبيرة',
'right-browsearchive'  => 'التدوير في الصفحات الممسوحة',
'right-import'         => 'استيراد الصفحات من ويكيات تانيه',
'right-importupload'   => 'استيراد الصفحات من فايل متحمل',
'right-unwatchedpages' => 'بين لستة الصفحات اللي مش متراقبة',
'right-userrights'     => 'تعديل كل الحقوق بتاعة اليوزر',
'right-siteadmin'      => 'قفل وفتح قاعدة البيانات',

# User rights log
'rightslog'  => 'سجل صلاحيات اليوزرز',
'rightsnone' => '(فاضى)',

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
'rc_categories_any'              => 'أى',
'newsectionsummary'              => '/* $1 */ قسم جديد',

# Recent changes linked
'recentchangeslinked'          => 'تعديلات  ليها علاقه',
'recentchangeslinked-title'    => 'التعديلات المرتبطه  ب "$1"',
'recentchangeslinked-noresult' => 'مافيش تعديلات حصلت فى الصفحات اللى ليها وصلات هنا خلال الفترة المحدده.',
'recentchangeslinked-summary'  => "دى صفحة مخصوصه بتعرض اخر التغييرات في الصفحات الموصوله. الصفحات اللى   فى  لسته بالصفحات اللى انت بتراقب التعديلات فيها معروضه''' بحروف عريضه'''",
'recentchangeslinked-page'     => 'اسم الصفحه :',

# Upload
'upload'             => 'حمل',
'uploadbtn'          => 'حمل الملف',
'reupload'           => 'حمل مره تانيه',
'uploaderror'        => 'غلطه فى التحميل',
'uploadlog'          => 'سجل التحميل',
'uploadlogpage'      => 'سجل التحميل',
'uploadlogpagetext'  => 'تحت في لستة بأحدث عمليات تحميل الملفات.',
'filename'           => 'اسم الملف',
'filedesc'           => 'الخلاصة',
'fileuploadsummary'  => 'الخلاصة:',
'filestatus'         => 'حالة حقوق النسخ:',
'filesource'         => 'مصدر:',
'uploadedfiles'      => 'الملفات المتحملة',
'ignorewarnings'     => 'اتجاهل اى تحذير',
'successfulupload'   => 'التحميل ناجح',
'uploadwarning'      => 'تحذير التحميل',
'savefile'           => 'حفظ الملف',
'uploadedimage'      => 'اتحمل "[[$1]]"',
'overwroteimage'     => 'اتحملت  نسخة جديدة من "[[$1]]"',
'uploaddisabled'     => 'التحميل متعطل',
'uploaddisabledtext' => 'تحميل الملفات متعطل في {{SITENAME}}.',
'uploadscripted'     => 'الملف دا  فيه كود HTML أو كود تاني يمكن البراوزر يفهمه غلط.',
'uploadcorrupt'      => 'الملف دا  بايظ أو ليه امتداد غلط. لو سمحت ا تأكد من الملف و حمله مرة تانية.',
'uploadvirus'        => 'الملف فيه فيروس! التفاصيل: $1',
'sourcefilename'     => 'اسم الملف  بتاع المصدر:',
'watchthisupload'    => 'حط الصفحة دي تحت المراقبة',

'upload-proto-error' => 'بروتوكول مش صحيح',

'license'   => 'ترخيص:',
'nolicense' => 'مش متحدد',

# Special:Imagelist
'imgfile'        => 'ملف',
'imagelist'      => 'لستة الملفات',
'imagelist_date' => 'تاريخ',
'imagelist_name' => 'اسم',
'imagelist_user' => 'يوزر',

# Image description page
'filehist'                       => 'تاريخ الملف',
'filehist-help'                  => 'اضغط على الساعه/التاريخ علشان تشوف الفايل زى ما كان فى  الوقت ده.',
'filehist-deleteone'             => 'مسح',
'filehist-current'               => 'دلوقتي',
'filehist-datetime'              => 'الساعه / التاريخ',
'filehist-user'                  => 'يوزر',
'filehist-dimensions'            => 'ابعاد',
'filehist-filesize'              => 'حجم الفايل',
'filehist-comment'               => 'تعليق',
'imagelinks'                     => 'وصلات',
'linkstoimage'                   => '{{PLURAL:$1|الصفحة|ال$1 صفحة}} دى فيها وصله للفايل ده:',
'nolinkstoimage'                 => 'مافيش صفحات بتوصل للفايل ده.',
'sharedupload'                   => 'الملف ده اتحمل علشان التشارك بين المشاريع وممكن استخدامه في المشاريع التانيه.',
'shareduploadduplicate-linktext' => 'ملف تاني',
'noimage'                        => ' مافيش  ملف بالاسم ده ،ممكن انك تقوم بـ$1.',
'noimage-linktext'               => 'تحميله',
'uploadnewversion-linktext'      => 'حمل نسخه جديده من الملف ده',

# File deletion
'filedelete'         => 'امسح $1',
'filedelete-legend'  => 'امسح الملف',
'filedelete-comment' => 'سبب المسح:',
'filedelete-submit'  => 'مسح',

# MIME search
'mimesearch' => 'تدوير MIME',
'download'   => 'تنزيل',

# List redirects
'listredirects' => 'عرض التحويلات',

# Unused templates
'unusedtemplates' => 'قوالب مش مستعمله',

# Random page
'randompage' => 'صفحة عشوائيه',

# Random redirect
'randomredirect' => 'تحويله عشوائيه',

# Statistics
'statistics'             => 'احصائيات',
'userstats'              => 'الاحصاءات بتاعة اليوزر',
'statistics-mostpopular' => 'اكتر صفحات اتشافت',

'disambiguations'      => 'صفحات التوضيح',
'disambiguationspage'  => 'Template:توضيح',
'disambiguations-text' => "الصفحات دي بتوصل لـ '''صفحة توضيح'''. المفروض على العكس انهم يوصلو ل للصفحات المناسبة. <br />أي صفحة بتتعامل على انها صفحة توضيح إذا كانت بتستعمل قالب موجود في [[MediaWiki:Disambiguationspage]]",

'doubleredirects'     => 'تحويلات مزدوجه',
'doubleredirectstext' => 'الصفحة دي فيها لستة الصفحات اللي فيها تحويلة لصفحة تانية فيها تحويلة. كل سطر في اللستة دي  فيه لينك للتحويلة الأولانية والتانية و كمان للصفحة بتاعة التحويلة التانية و اللي غالبا هي الصفحة الاصلية اللي المفروض التحويلة الاولانية توصل ليها.',

'brokenredirects'        => 'تحويلات مكسوره',
'brokenredirectstext'    => 'التحويلات دي بتوصل لصفحات مش موجودة:',
'brokenredirects-edit'   => '(تحرير)',
'brokenredirects-delete' => '(مسح)',

'withoutinterwiki'         => 'صفحات بدون وصلات للغات تانيه',
'withoutinterwiki-summary' => 'الصفحات دي  مالهاش لينكات لنسخ بلغات تانية:',
'withoutinterwiki-legend'  => 'بريفيكس',
'withoutinterwiki-submit'  => 'عرض',

'fewestrevisions' => 'اقل المقالات فى عدد التعديلات',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بايت|بايت}}',
'ncategories'             => '$1 {{PLURAL:$1|تصنيف واحد|تصنيفين|تصنيفات|تصنيف}}',
'nlinks'                  => '{{PLURAL:$1|وصله واحده|وصلتين|$1 وصلات|$1 وصله}}',
'nmembers'                => '$1 {{PLURAL:$1|عضو|اعضاء}}',
'nrevisions'              => '{{PLURAL:$1|تعديل وحيد|تعديلين|$1 تعديلات|$1 تعديل|$1}}',
'nviews'                  => '{{PLURAL:$1|مشاهدة واحدة|مشاهدتين|$1 مشاهدات|$1 مشاهدة}}',
'specialpage-empty'       => 'مافيش نتايج للتقرير دا.',
'lonelypages'             => 'صفحات يتيمه',
'lonelypagestext'         => 'الصفحات دي ماعندهاش لينكات  من الصفحات التانية في {{SITENAME}}.',
'uncategorizedpages'      => 'صفحات مش متصنفه',
'uncategorizedcategories' => 'تصنيفات مش متصنفه',
'uncategorizedimages'     => 'ملفات مش متصنفه',
'uncategorizedtemplates'  => 'قوالب مش متصنفه',
'unusedcategories'        => 'تصانيف مش  مستعمله',
'unusedimages'            => 'صور مش مستعمله',
'popularpages'            => 'صفحات مشهورة',
'wantedcategories'        => 'تصانيف مطلوبه',
'wantedpages'             => 'صفحات مطلوبه',
'missingfiles'            => 'ملفات ضايعة',
'mostlinked'              => 'اكتر صفحات موصولة بصفحات تانيه',
'mostlinkedcategories'    => 'اكتر التصانيف فى عدد الارتباطات',
'mostlinkedtemplates'     => 'اكتر القوالب فى عدد الوصلات',
'mostcategories'          => 'اكتر الصفحات فى عدد التصانيف',
'mostimages'              => 'اكتر الملفات فى عدد الارتباطات',
'mostrevisions'           => 'اكتر المقالات فى عدد التعديلات',
'prefixindex'             => 'فهرس البريفكسات',
'shortpages'              => 'صفحات قصيره',
'longpages'               => 'صفحات طويله',
'deadendpages'            => 'صفحات ما بتوصلش  لحاجه',
'deadendpagestext'        => 'الصفحات دي مابتوصلش  لصفحات تانية في {{SITENAME}}.',
'protectedpages'          => 'صفحات محميه',
'protectedpages-indef'    => 'عمليات الحماية اللي مش متحددة بس',
'protectedpagestext'      => 'الصفحات دي محمية من النقل أو التعديل',
'protectedpagesempty'     => 'مافيش  صفحات محمية دلوقتي  على حسب المحددات دي.',
'protectedtitles'         => 'عناوين محمية',
'listusers'               => 'لستة الأعضاء',
'newpages'                => 'صفحات جديده',
'ancientpages'            => 'اقدم الصفحات',
'move'                    => 'انقل',
'movethispage'            => 'انقل الصفحه دى',
'nopagetitle'             => 'مافيش صفحة هدف بالاسم ده',

# Book sources
'booksources'    => 'مصادر من كتب',
'booksources-go' => 'روح',

# Special:Log
'specialloguserlabel'  => 'اليوزر:',
'speciallogtitlelabel' => 'العنوان:',
'log'                  => 'سجلات',
'all-logs-page'        => 'كل السجلات',
'log-search-legend'    => 'دور على سجلات',
'log-search-submit'    => 'روح',
'log-title-wildcard'   => 'التدوير على عناوين تبتدي بالنص دا',

# Special:Allpages
'allpages'          => 'كل الصفحات',
'alphaindexline'    => '$1 ل $2',
'nextpage'          => 'الصفحه اللى بعد كده ($1)',
'prevpage'          => 'الصفحه اللى قبل كده ($1)',
'allpagesfrom'      => 'عرض الصفحات بدايه من:',
'allarticles'       => 'كل المقالات',
'allinnamespace'    => 'كل الصفحات (في نطاق $1)',
'allnotinnamespace' => 'كل الصفحات (مش في نطاق $1)',
'allpagesprev'      => 'اللي فلت',
'allpagesnext'      => 'اللى بعد كده',
'allpagessubmit'    => 'روح',
'allpagesprefix'    => 'عرض الصفحات  اللى تبتدى بـ:',
'allpagesbadtitle'  => 'العنوان االلي اديته للصفحة مش نافع أو فيه لغات تانية أو بريفيكس إنترويكي. يمكن فيه حروف ماينفعش تنكتب بيها العناوين.',
'allpages-bad-ns'   => '{{SITENAME}} مافيهاش نطاق "$1".',

# Special:Categories
'categories'                    => 'تصانيف',
'categoriespagetext'            => 'التصنيفات دي فيها صفحات أو ميديا.',
'categoriesfrom'                => 'اعرض التصانيف من أول:',
'special-categories-sort-count' => 'رتب بالعدد',
'special-categories-sort-abc'   => 'ترتيب ابجدي',

# Special:Listusers
'listusersfrom'      => 'عرض اليوزرز من أول:',
'listusers-submit'   => 'عرض',
'listusers-noresult' => 'ما في ش يوزر',

# Special:Listgrouprights
'listgrouprights'          => 'حقوق مجموعات اليوزرز',
'listgrouprights-summary'  => 'دي لستة بمجموعات اليوزرز المتعرفة في الويكي دا، بالحقوق اللي معاهم.
ممكن تلاقي معلومات زيادة عن الحقوق بتاعة كل واحد  [[{{MediaWiki:Listgrouprights-helppage}}|هنا]].',
'listgrouprights-group'    => 'المجموعة',
'listgrouprights-rights'   => 'الحقوق',
'listgrouprights-helppage' => 'Help: حقوق المجموعات',
'listgrouprights-members'  => '(لستة الأعضاء)',

# E-mail user
'mailnologin'     => 'مافيش عنوان نبعت عليه',
'mailnologintext' => 'لازم تعمل [[Special:Userlogin|تسجيل الدخول]] و تدخل ايميل صحيح في صفحة [[Special:Preferences|التفضيلات]] علشان تقدر تبعت ايميلات لليوزرز التانيين.',
'emailuser'       => 'ابعت ايميل لليوزر دا',
'emailpage'       => 'ابعت ايميل لليوزر ده',
'emailpagetext'   => 'لو اليوزر دا دخل ايميل صحيح في التفضيلات بتاعته،
ف حيتبعت له رسالة واحدة بس حسب الاستمارة اللي تحت دي.
عنوان الايميل اللي دخلته في التفضيلات بتاعتك
ح يظهر في  على انه عنوان الاستمارة و بكدة اللي حيستقبله ح يقدر يرد على الايميل.',
'noemailtitle'    => 'مافيش  عنوان ايميل',
'emailfrom'       => 'من',
'emailto'         => 'لـ',
'emailsubject'    => 'الموضوع',
'emailsend'       => 'إبعت',
'emailccme'       => 'ابعتلي نسخة من الايميل اللي بعته.',
'emailsent'       => 'الإيميل اتبعت',

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

'enotif_newpagetext' => 'دى صفحه جديده.',
'changed'            => 'اتغيرت',
'enotif_anon_editor' => 'يوزر مش معروف $1',

# Delete/protect/revert
'deletepage'                  => 'امسح الصفحه',
'exblank'                     => 'الصفحه كانت فاضيه',
'delete-confirm'              => 'مسح"$1"',
'delete-legend'               => 'مسح',
'historywarning'              => 'تحذير: الصفحه اللى ها  تمسحها ليها تاريخ:',
'confirmdeletetext'           => 'انت على وشك انك تمسح صفحه أو صوره و كل التعديلات عليها بشكل دايم من قاعدة البيانات.  من فضلك  اتأكد انك عايز المسح وبأنك فاهم نتايج  العمليه  دى. عمليات الحذف لازم تتم بناء على [[{{MediaWiki:Policy-url}}|القواعد المتفق عليها]].',
'actioncomplete'              => ' العمليه خلصت',
'deletedtext'                 => '"<nowiki>$1</nowiki>" اتمسحت.
بص على $2 لسجل آخر عمليات المسح.',
'deletedarticle'              => 'اتمسحت "[[$1]]"',
'dellogpage'                  => 'سجل المسح',
'deletionlog'                 => 'سجل المسح',
'deletecomment'               => 'سبب المسح:',
'deleteotherreason'           => 'سبب تانى/اضافي:',
'deletereasonotherlist'       => 'سبب تانى',
'deletereason-dropdown'       => '*أسباب المسح المشهورة
** طلب المؤلف
** التعدي على حقوق النشر
** التخريب',
'delete-edit-reasonlist'      => 'عدل اسباب المسح',
'rollbacklink'                => 'استعاده',
'editcomment'                 => 'تعليق التعديل كان: "<i>$1</i>".', # only shown if there is an edit comment
'protectlogpage'              => 'سجل الحمايه',
'protectedarticle'            => 'حمى "[[$1]]"',
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
'minimum-size'                => 'أقل حجم',
'maximum-size'                => 'أكبر حجم',

# Restrictions (nouns)
'restriction-edit'   => 'تعديل',
'restriction-upload' => 'تحميل',

# Undelete
'undeletebtn'         => 'استعاده',
'undeletecomment'     => 'تعليق:',
'undelete-search-box' => 'دور في الصفحات الممسوحة',

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
'whatlinkshere'           => 'ايه بيوصل هنا',
'whatlinkshere-title'     => 'الصفحات اللي بتودي ل $1',
'whatlinkshere-page'      => 'الصفحة:',
'linklistsub'             => '(لسته بالوصلات)',
'linkshere'               => "الصفحات دى فيها وصله ل '''[[:$1]]''':",
'nolinkshere'             => "مافيش صفحات بتوصل ل '''[[:$1]]'''.",
'isredirect'              => 'صفحة تحويل',
'istemplate'              => 'متضمن',
'isimage'                 => 'لينك صورة',
'whatlinkshere-prev'      => '{{PLURAL:$1|اللى قبل كده|الـ $1 اللى قبل كده}}',
'whatlinkshere-next'      => '{{PLURAL:$1|اللى بعد كده|الـ $1 اللى بعد كده}}',
'whatlinkshere-links'     => '← وصلات',
'whatlinkshere-hidelinks' => '$1 لينكات',

# Block/unblock
'blockip'                 => 'منع يوزر',
'ipbexpiry'               => 'مدة المنع:',
'ipbreason'               => 'السبب:',
'ipbother'                => 'وقت تاني:',
'ipboptions'              => 'ربع ساعة:15 minutes,ساعة واحدة:1 hour,ساعتين:2 hours,يوم:1 day,ثلاثة أيام:3 days,أسبوع:1 week,أسبوعان:2 weeks,شهر:1 month,ثلاثة شهور:3 months,ستة شهور:6 months,عام واحد:1 year,دائم:infinite', # display1:time1,display2:time2,...
'ipblocklist'             => 'لستة عناوين الااى بى واسامى اليوزر الممنوعه',
'ipblocklist-submit'      => 'تدوير',
'blocklink'               => 'منع',
'unblocklink'             => 'رفع المنع',
'contribslink'            => 'تعديلات',
'blocklogpage'            => 'سجل المنع',
'blocklogentry'           => 'منع "[[$1]]" لفترةه زمنيه مدتها $2 $3',
'block-log-flags-noemail' => 'الإيميل ممنوع',
'proxyblocksuccess'       => 'خلاص.',

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
'1movedto2'        => '[[$1]] اتنقلت ل [[$2]]',
'movelogpage'      => 'سجل النقل',
'movereason'       => 'السبب:',
'revertmove'       => 'استعاده',

# Export
'export'        => 'تصدير صفحات',
'export-addcat' => 'زيادة',

# Namespace 8 related
'allmessages'     => 'رسايل النظام',
'allmessagesname' => 'اسم',

# Thumbnails
'thumbnail-more'  => 'كبر',
'filemissing'     => 'الملف ضايع',
'thumbnail_error' => 'غلطه فى انشاء صوره مصغره: $1',

# Special:Import
'import'                  => 'استيراد صفحات',
'import-interwiki-submit' => 'استيراد',
'importbadinterwiki'      => 'اللينك بتاعة الانترويكي دي غلط',
'importnotext'            => 'فاضي او مافيش نص',

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
'tooltip-p-logo'                  => 'الصفحه الرئيسيه',
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
'tooltip-ca-nstab-special'        => 'الصفحة دي صفحة مخصوصة ، مش ممكن تعدل الصفحة نفسها',
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
'tooltip-upload'                  => 'ابتدي التحميل',

# Attribution
'others' => 'تانيين',

# Patrol log
'patrol-log-auto' => '(اوتوماتيكي)',

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
'ilsubmit'  => 'تدوير',
'bydate'    => 'على حسب التاريخ',

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

# EXIF tags
'exif-imagewidth'       => 'العرض',
'exif-imagedescription' => 'عنوان الصورة',
'exif-model'            => 'موديل الكاميرا',
'exif-copyright'        => 'صاحب الحقوق الممحفوظة',
'exif-colorspace'       => 'فرق اللون',
'exif-usercomment'      => 'تعليقات اليوزر',
'exif-brightnessvalue'  => 'الضي',
'exif-flash'            => 'فلاش',
'exif-flashenergy'      => 'طاقة الفلاش',
'exif-filesource'       => 'مصدر الملف',
'exif-gpsstatus'        => 'حالة جهاز الاستقبال',
'exif-gpsspeedref'      => 'وحدة السرعة',

'exif-unknowndate' => 'تاريخ مش معروف',

'exif-orientation-1' => 'عادي', # 0th row: top; 0th column: left
'exif-orientation-2' => 'دار بالعرض', # 0th row: top; 0th column: right
'exif-orientation-3' => 'دار 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'دار بالطول', # 0th row: bottom; 0th column: left

'exif-componentsconfiguration-0' => 'مش موجود',

'exif-exposureprogram-1' => 'يدوي',

'exif-subjectdistance-value' => '$1 متر',

'exif-lightsource-0' => 'مش معروف',
'exif-lightsource-4' => 'فلاش',

'exif-focalplaneresolutionunit-2' => 'بوصة',

'exif-sensingmethod-1' => 'مش معرف',

'exif-contrast-0' => 'طبيعي',

# External editor support
'edit-externally'      => 'عدل هذا الملف باستخدام تطبيق خارجي',
'edit-externally-help' => 'بص على [http://meta.wikimedia.org/wiki/Help:External_editors  تعليمات الاعداد] علشان معلومات اكتر.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'الكل',
'imagelistall'     => 'الكل',
'watchlistall2'    => 'الكل',
'namespacesall'    => 'الكل',
'monthsall'        => 'الكل',

# E-mail address confirmation
'confirmemail_invalid'     => 'كود تفعيل غلط. يمكن صلاحيته تكون انتهت.',
'confirmemail_needlogin'   => 'لازم $1 علشان تأكد الايميل بتاعك.',
'confirmemail_success'     => 'الايميل بتاعك اتأكد خلاص.
ممكن دلوقتي تسجل دخولك و تستمتع بالويكي.',
'confirmemail_loggedin'    => 'الايميل بتاعك اتأكد خلاص.',
'confirmemail_error'       => 'حصلت حاجة غلط و احنا بنحفظ التأكيد بتاعك.',
'confirmemail_subject'     => 'تأكيد الايميل من {{SITENAME}}',
'confirmemail_body'        => 'في واحد، ممكن يكون إنتا، من عنوان الأيبي $1،
فتح حساب "$2" بعنوان الايميل دا في {{SITENAME}}.

علشان نتأكد أن  الحساب دا بتاعك فعلا و علشان كمان تفعيل خواص الايميل في {{SITENAME}}، افتح اللينك دي في البراوزر بتاعك :

$3

إذا *ماكنتش* إنتا اللي فتحت الحساب ، دوس على اللينك دي علشان تلغي تأكيد الايميل
:

$5

كود التفعيل دا ح ينتهي $4.',
'confirmemail_invalidated' => 'تأكيد عنوان الايميل اتلغى',
'invalidateemail'          => 'إلغى تأكيد الايميل',

# Scary transclusion
'scarytranscludedisabled' => '[التضمين  في الإنترويكي متعطل]',
'scarytranscludefailed'   => '[التدوير على القالب فشل ل$1؛ متأسفين]',
'scarytranscludetoolong'  => '[عنوان طويل جدا؛ متأسفين]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
التراكباك بتاع الصفحة دي:<br />
$1
</div>',
'trackbackremove'   => '([$1 امسح])',
'trackbacklink'     => 'تراكباك',
'trackbackdeleteok' => 'التراكباك اتمسح بنجاح.',

# Delete conflict
'deletedwhileediting' => 'تحذير:  الصفحة دي اتمسحت بعد ما بدأت أنت  في تحريرها!',
'confirmrecreate'     => "اليوزر [[User:$1|$1]] ([[User talk:$1|مناقشة]]) مسح المقالة دي بعد ما انت بدأت في تحريرها علشان:
:''$2''
لو سمحت تتأكد من أنك عايز تبتدي المقالة دي تاني.",
'recreate'            => 'ابتدي تاني',

'unit-pixel' => 'بيكس',

# HTML dump
'redirectingto' => 'بتتحول لـ [[$1]]...',

# action=purge
'confirm_purge'        => 'امسح الكاش بتاع الصفحة دي؟

$1',
'confirm_purge_button' => 'طيب',

# AJAX search
'searchcontaining' => "دور على الصفحات اللي فيها ''$1''.",
'searchnamed'      => "دور على الصفحات اللي اسمها ''$1''.",
'articletitles'    => "الصفحات اللي بتبتدي بـ''$1''",
'hideresults'      => 'خبي النتايج',
'useajaxsearch'    => 'دور بـ أجاكس',

# Separators for various lists, etc.
'semicolon-separator' => '؛',
'comma-separator'     => '،',
'autocomment-prefix'  => '-',

# Multipage image navigation
'imgmultipageprev' => '← الصفحة اللي فاتت',
'imgmultipagenext' => 'الصفحه اللى بعد كده →',
'imgmultigo'       => 'روح!',
'imgmultigoto'     => 'روح لصفحة $1',

# Table pager
'ascending_abbrev'         => 'طالع',
'descending_abbrev'        => 'نازل',
'table_pager_next'         => 'الصفحه اللى بعد كده',
'table_pager_prev'         => 'الصفحة اللي فاتت',
'table_pager_first'        => 'أول صفحة',
'table_pager_last'         => 'آخر صفحة',
'table_pager_limit'        => 'اعرض $1 عنصر في الصفحة',
'table_pager_limit_submit' => 'روح',
'table_pager_empty'        => 'ما في ش نتايج',

# Auto-summaries
'autosumm-blank'   => 'مسح كل اللي في الصفحة',
'autosumm-replace' => "تبديل الصفحة ب'$1'",
'autoredircomment' => 'تحويل لـ [[$1]]',
'autosumm-new'     => 'صفحه جديده: $1',

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
'lag-warn-normal' => 'التغييرات الأحدث من $1 ثانية ممكن ما تظهرش في اللستة دي.',
'lag-warn-high'   => 'علشان في تأخير كبير في تحديث قاعدة البيانات بتاعة السيرفر، التغييرات أحدث من $1 ثانية
ممكن ما تظهرش في اللستة دي.',

# Watchlist editor
'watchlistedit-numitems'       => 'لستة المراقبة بتاعتك  فيها{{PLURAL:$1|عنوان واحد|$1 عنوان}}، من غير صفحات المناقشة.',
'watchlistedit-noitems'        => 'لستة الرقابة بتاعتك  مافيهاش ولا عنوان.',
'watchlistedit-normal-title'   => 'تعديل لستة المراقبة',
'watchlistedit-normal-legend'  => 'شيل العناوين من لستة المراقبة',
'watchlistedit-normal-explain' => 'العناوين في لستة المراقبة بتاعتك معروضة تحت. علشان تشيل عنوان، دوس على
	الصندوق اللي جنبه، ودوس على شيل العناوين. ممكن كمان [[Special:Watchlist/raw|تعديل اللستة الخام]].',
'watchlistedit-normal-submit'  => 'شيل العناوين',
'watchlistedit-normal-done'    => '{{PLURAL:$1|عنوان واحد|$1 عنوان}} اتشال من لستة المراقبة بتاعتك:',
'watchlistedit-raw-title'      => 'تعديل لستة المراقبة الخام',
'watchlistedit-raw-legend'     => 'تعديل لستة المراقبة الخام',
'watchlistedit-raw-explain'    => 'العناوين في لستة مراقبتك معروضة تحت، وممكن تعدلها لما تزود او تشيل من اللستة؛ عنوان واحد في السطر. لما تخلص، دوس تحديث لستة المراقبة.
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

# Hebrew month names
'hebrew-calendar-m11-gen' => 'آب',
'hebrew-calendar-m12-gen' => 'أيلول',

# Signatures
'timezone-utc' => 'يو تي سي',

# Core parser functions
'unknown_extension_tag' => 'تاج بتاع امتداد مش معروف "$1"',

# Special:Version
'version'                          => 'نسخه', # Not used as normal message but as header for the special page itself
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
'version-version'                  => 'نسخه',
'version-license'                  => 'الترخيص',
'version-software'                 => 'السوفتوير المتستاب',
'version-software-product'         => 'المنتج',
'version-software-version'         => 'النسخه',

# Special:Filepath
'filepath'         => 'مسار ملف',
'filepath-page'    => 'الملف:',
'filepath-submit'  => 'المسار',
'filepath-summary' => 'الصفحة المخصوصة دي بتعرض المسار الكامل  بتاع ملف. الصور بتتعرض  بدقة كاملة، أنواع الملفات التانية ح تشتغل في البرنامج بتاعهم مباشرة؛ دخل اسم الملف  من غير البريفيكس"{{ns:image}}:"',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'دور على الملفات المتكررة',
'fileduplicatesearch-summary'  => 'دور على الملفات المتكررة على اساس قيمة الهاش بتاعتها.

دخل اسم الملف من غير البريفكس "{{ns:image}}:".',
'fileduplicatesearch-legend'   => 'تدوير على متكرر',
'fileduplicatesearch-filename' => 'اسم الملف:',
'fileduplicatesearch-submit'   => 'تدوير',
'fileduplicatesearch-info'     => '$1 × $2 بكسل<br />حجم الملف: $3<br />نوع MIME: $4',
'fileduplicatesearch-result-1' => 'الملف "$1" ما لهو ش تكرار متطابق.',
'fileduplicatesearch-result-n' => 'الملف "$1" فيه {{PLURAL:$2|1 تكرار متطابق|$2 تكرار متطابق}}.',

# Special:SpecialPages
'specialpages'                   => 'صفحات مخصوصه',
'specialpages-note'              => '----
* صفحات خاصة عادية.
* <span class="mw-specialpagerestricted">صفحات خاصة للناس اللي مسموح لهم.</span>',
'specialpages-group-maintenance' => 'تقارير الصيانة',
'specialpages-group-other'       => 'صفحات خاصه تا نيه',
'specialpages-group-login'       => 'ادخل / سجل',
'specialpages-group-changes'     => 'السجلات واحدث التغييرات',
'specialpages-group-media'       => 'تقارير الميديا وعمليات التحميل',
'specialpages-group-users'       => 'اليوزرز و الحقوق',
'specialpages-group-highuse'     => 'صفحات بتستخدم كتير',
'specialpages-group-pages'       => 'لستات الصفحة',
'specialpages-group-pagetools'   => 'أدوات الصفحات',
'specialpages-group-wiki'        => 'بيانات وأدوات الويكى',
'specialpages-group-redirects'   => 'صفحات  التحويل الخاصه',
'specialpages-group-spam'        => 'أدوات السبام',

# Special:Blankpage
'blankpage'              => 'صفحة فاضية',
'intentionallyblankpage' => 'الصفحة دي متسابة فاضية بالقصد',

);
