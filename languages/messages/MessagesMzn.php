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
 * @author Firuz
 * @author Spacebirdy
 * @author محک
 */

$fallback = 'fa';

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$namespaceNames = array(
	NS_MEDIA            => 'مه‌دیا',
	NS_SPECIAL          => 'شا',
	NS_TALK             => 'گپ',
	NS_USER             => 'کارور',
	NS_USER_TALK        => 'کارور_گپ',
	NS_PROJECT_TALK     => '$1_گپ',
	NS_FILE             => 'پرونده',
	NS_FILE_TALK        => 'پرونده_گپ',
	NS_MEDIAWIKI        => 'مه‌دیاویکی',
	NS_MEDIAWIKI_TALK   => 'مه‌دیاویکی_گپ',
	NS_TEMPLATE         => 'شابلون',
	NS_TEMPLATE_TALK    => 'شابلون_گپ',
	NS_HELP             => 'رانه‌ما',
	NS_HELP_TALK        => 'رانه‌مائه_گپ',
	NS_CATEGORY         => 'رج',
	NS_CATEGORY_TALK    => 'رج_گپ',
);

$namespaceAliases = array(
	'مدیا'          => NS_MEDIA,
	'ویژه'          => NS_SPECIAL,
	'بحث'            => NS_TALK,
	'کاربر'         => NS_USER,
	'بحث_کاربر'      => NS_USER_TALK,
	'بحث_$1'         => NS_PROJECT_TALK,
	'تصویر'         => NS_FILE,
	'پرونده'        => NS_FILE,
	'بحث_تصویر'      => NS_FILE_TALK,
	'بحث_پرونده'     => NS_FILE_TALK,
	'مدیاویکی'      => NS_MEDIAWIKI,
	'مه‌دیا ویکی'    => NS_MEDIAWIKI,
	'بحث_مدیاویکی'   => NS_MEDIAWIKI_TALK,
	'مه‌دیا ویکی گپ' => NS_MEDIAWIKI_TALK,
	'الگو'          => NS_TEMPLATE,
	'بحث_الگو'       => NS_TEMPLATE_TALK,
	'راهنما'        => NS_HELP,
	'بحث_راهنما'     => NS_HELP_TALK,
	'رانه‌مای گپ'    => NS_HELP_TALK,
	'رده'           => NS_CATEGORY,
	'بحث_رده'        => NS_CATEGORY_TALK,
);

$magicWords = array(
	'redirect'              => array( '0', '#بور', '#تغییرمسیر', '#REDIRECT' ),
	'numberofpages'         => array( '1', 'تعدادصفحه‌ها', 'تعداد_صفحه‌ها', 'ولگ‌ئون_نمره', 'وألگ‌ئون_نومره', 'NUMBEROFPAGES' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'پیوندون زیر خط دکشی بواشه',
'tog-highlightbroken'         => 'ناقص پیوندون قالب بندی<a href="" class="new">اینجوری</a>(امکان دیگه:اینجوری<a href="" class="internal">؟</a>).',
'tog-justify'                 => 'بندون تموم چین هاکرد‌ن',
'tog-hideminor'               => 'نشون‌ندائن کچیک تغییرات تازه دگاردسته‌ئون دله',
'tog-hidepatrolled'           => 'جا بدائن دچی‌یه‌ئون پس بخارد تازه دگاردسته‌ئون ِدله',
'tog-newpageshidepatrolled'   => 'قایم هکردن گشت بخرد ولگون نو ولگون فهرست جا',
'tog-extendwatchlist'         => 'گت تر هکردن دمبال هکرده‌ئون فهرست تموم دگارسه‌ئون سر، و نا فقط آخرین  موردون',
'tog-usenewrc'                => 'استفاده از تازه دگاردسته‌ئون گت‌تر بَیی (نیازمند جاوااسکریپت)',
'tog-numberheadings'          => 'شماره بشتن خدکار عناوین',
'tog-showtoolbar'             => 'نشون هدائن نوار ابزار جعبه دچی ین',
'tog-editondblclick'          => 'دچی ین ولگون با دتا کلیک (نیازمند جاوااسکریپت)',
'tog-editsection'             => 'به کار دمبدائن تیکه‌ئون دچی‌ین از طریق پیوندون [دچی‌ین]',
'tog-editsectiononrightclick' => 'به کار دمبدائن دچی‌ین قسمت‌ئون با راست کیلیک<br />عناوین قسمت‌ئون ِرو (جاوااسکریپت)',
'tog-showtoc'                 => 'نیمایش محتوا<br />(برای مقاله‌ئون با بیشته از ۳ سرفصل)',
'tog-rememberpassword'        => 'مه رمز ره (تا حداکثر $1 {{PLURAL:$1|روز|روز}}) این کامپیتر دله ته ره یاد دواشه',
'tog-watchcreations'          => 'ایضافه بین صفحه‌ئونی که من دِرِس هاکردمه به پیگیری‌ئون ِرج.',
'tog-watchdefault'            => 'اضافه هاکردن صفحه‌هایی که چیمبه به منه پیگری ِرج',
'tog-watchmoves'              => 'صفحه‌ئونی که کشمبه ره منه پِگیری ِرج دله بنویس',
'tog-watchdeletion'           => 'اضافه هاکردن صفحه‌هایی که پاک کامبه به منه پیگری ِرج',
'tog-minordefault'            => 'همه صفحه‌ئون دچیه ره جزئی پیش‌گامون دار',
'tog-previewontop'            => 'نمایش پیش‌نمایش قبل از دچی‌ین ِجعبه(نا قبل از وه).',
'tog-previewonfirst'          => 'پیش نیمایش زمون اولین دچی‌ین',
'tog-nocache'                 => 'حافظه نهانی صفحه ره خنثی هاکن',
'tog-enotifwatchlistpages'    => 'اگه منه پگری‌ئون ره تغییر هدانه مسّه ایمیل بزن',
'tog-enotifusertalkpages'     => 'هر گادر منه کاروری صفخه‌ی گپ دله ات چی بنویشنه مه سّه ایمیل بزن',
'tog-enotifminoredits'        => 'هرگادر صحه ها دله اتا خورد چی ره عوض هکردنه مه وسّه ایمیل بزن',
'tog-enotifrevealaddr'        => 'منه ایمیل نامه ئون ایطیلاع رسونی دله دواشه',
'tog-shownumberswatching'     => 'نشون هدائن کارورن دمبال کوننده',
'tog-oldsig'                  => 'پیش نیمایش ایمضای موجود:',
'tog-fancysig'                => 'ایمضا ره ویکی متن نظر بیرین (بدون لینک هایتن)',
'tog-externaleditor'          => 'به شیکل پیش فرض خارجی ویرایشگرون جه ایستیفاده بواشه',
'tog-externaldiff'            => 'ایستیفاده از تفاوت‌گیر جه (diff) خارجی به‌طور پیش‌فرض.',
'tog-showjumplinks'           => 'فعال هکردن بپرسنی پیوندون مندرجات فهرست دله',
'tog-uselivepreview'          => 'ایستیفاده از پیش نیمایش زنده (جاوا اسکریپ) (آزمایشی)',
'tog-forceeditsummary'        => 'زمونی که خولاصه دچی‌ین ره ننویشتمه مه ره بائو',
'tog-watchlisthideown'        => 'دپوشنی‌ین کارای من پیگریای ِفهرست دله',
'tog-watchlisthidebots'       => 'دپوشنی‌ین کارای روبات‌ئون منه پیگیرایای ِفهرست دله',
'tog-watchlisthideminor'      => 'خورد عوض بیی ها ره منه پیگیری ِرج دله نشون ندده',
'tog-watchlisthideliu'        => 'کارای کارورنی که حیساب دارنه ره دپوشِن',
'tog-watchlisthideanons'      => 'کارای کارورونی که حیساب ندارنه ره منه پیگری ِرج دله دپوشن.',
'tog-watchlisthidepatrolled'  => 'دپوشنی‎ین دچیه‌ئون گشت بخارد منه پیگری ِفهرست دله جه',
'tog-ccmeonemails'            => 'برسنی‌ین رونوشت نامه‌ئونی که به کارورون رسنمبه مه وسه هم برسنی‌یه بواشه.',
'tog-diffonly'                => 'محتوای صفحه ، تفاوت بِن نیمایش هدائه نواشه.',
'tog-showhiddencats'          => 'دپوشونیه رج‌ئون ره نشون هاده',
'tog-norollbackdiff'          => 'بعد واگردونی تفاوت ره نشون نده',

'underline-always'  => 'همیشه مازرونی',
'underline-never'   => 'دکل',
'underline-default' => 'پیش‌فرض مرورگر',

# Font style option in Special:Preferences
'editfont-style'     => 'دچی ین جعبه قلم سبک:',
'editfont-default'   => 'پیش فرض مرورگر',
'editfont-monospace' => 'فونت Monospaced',
'editfont-sansserif' => 'فونت Sans-serif',
'editfont-serif'     => 'فونت Serif',

# Dates
'sunday'        => 'یه‌شنبه',
'monday'        => 'دِشنبه',
'tuesday'       => 'سه‌شنبه',
'wednesday'     => 'چارشنبه',
'thursday'      => 'پنج‌شنبه',
'friday'        => 'جومه',
'saturday'      => 'شنبه',
'sun'           => 'یه‌شنبه',
'mon'           => 'دِشنبه',
'tue'           => 'سه‌شنبه',
'wed'           => 'چارشنبه',
'thu'           => 'پنجشنبه',
'fri'           => 'جـومه',
'sat'           => 'شه‌مه',
'january'       => 'جـانـویـه',
'february'      => 'فـه‌وریـه',
'march'         => 'مـارچ',
'april'         => 'آوریل',
'may_long'      => 'مه',
'june'          => 'ژوئن',
'july'          => 'جـولای',
'august'        => 'ئـوگـه‌سـت',
'september'     => 'سـه‌پـتـه‌مـبـر',
'october'       => 'ئـوکـتـوبـر',
'november'      => 'نـووه‌مـبـر',
'december'      => 'ده‌سـه‌مـبـر',
'january-gen'   => 'جـانـویـه',
'february-gen'  => 'فـه‌وریـه',
'march-gen'     => 'مـارس',
'april-gen'     => 'آوریـل',
'may-gen'       => 'مـه‌ی',
'june-gen'      => 'جـون',
'july-gen'      => 'جـولای',
'august-gen'    => 'ئوگـه‌سـت',
'september-gen' => 'سـه‌پـتـه‌مـبـر',
'october-gen'   => 'ئـوکـتـوبـر',
'november-gen'  => 'نـووه‌مـبـر',
'december-gen'  => 'ده‌سـه‌مـبـر',
'jan'           => 'جانویه',
'feb'           => 'فه‌وریه',
'mar'           => 'مارچ',
'apr'           => 'آوریل',
'may'           => 'مه',
'jun'           => 'ژوئن',
'jul'           => 'جولای',
'aug'           => 'ئوگوست',
'sep'           => 'سه‌پته‌مبر',
'oct'           => 'ئوکتوبر',
'nov'           => 'نووه‌مبر',
'dec'           => 'ده‌سه‌مبر',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|رج|رج‌ئون}}',
'category_header'          => '"$1" ره دله صفحه‌ئون',
'subcategories'            => 'جیر رج‌ئون',
'category-media-header'    => '«$1» رج پرونده‌ئون',
'category-empty'           => 'ای رج ره ده‌له ئه‌سا هیچی دأنیه',
'hidden-categories'        => '{{PLURAL:$1|خف رج|خف رجون}}',
'hidden-category-category' => 'خف رجون',
'category-article-count'   => '{{PLURAL:$2|این رج همینتا صفحه ره دانّه.|ای  {{PLURAL:$1صفحه|صفحه|$1 ئون}}، $2 جه اینجه دَرنه.}}',
'listingcontinuesabbrev'   => '(دمباله)',
'index-category'           => 'صفحه‌ئون نمایه بَیی',
'noindex-category'         => 'صفحه‌ئون نمایه نَیی',

'about'         => 'ده‌لـه‌واره',
'article'       => 'صفحه‌ی بنویشته‌ئون',
'newwindow'     => '(ته‌رنه‌ روجین ده‌له‌ وا بونه)',
'cancel'        => 'وه‌ل هـه‌کـارده‌ن',
'moredotdotdot' => 'ویـشـتـه...',
'mypage'        => 'مه صفحه',
'mytalk'        => 'مه گپ',
'anontalk'      => 'گپ بزوئن اینتا آی‌پی وسّه',
'navigation'    => 'بگردستن',
'and'           => '&#32;و',

# Cologne Blue skin
'qbfind'         => 'پیدا هکردن',
'qbbrowse'       => 'چأرخه‌سه‌ن',
'qbedit'         => 'دأچیه‌ن',
'qbpageoptions'  => 'این صفحه',
'qbpageinfo'     => 'بافت',
'qbmyoptions'    => 'مه صفحه‌ئون',
'qbspecialpages' => 'شا صفحه‌ئون',
'faq'            => 'معمولی سوالا',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'ایضافه هکردن عونوان',
'vector-action-delete'           => 'پاک هاکردن',
'vector-action-move'             => 'دکش هاکردن',
'vector-action-protect'          => 'موحافظت',
'vector-action-undelete'         => 'دباره بنویشته بیّن',
'vector-action-unprotect'        => 'موحافظت نکاردن',
'vector-simplesearch-preference' => 'فعال هاکردن پیشنهادون بگردستن پیشرفته (فقط پوسته برداری دله)',
'vector-view-create'             => 'بساتن',
'vector-view-edit'               => 'دچی‌ین',
'vector-view-history'            => 'تاریخچه ره بَدی‌ین',
'vector-view-view'               => 'بأخـونـه‌سـه‌ن',
'vector-view-viewsource'         => 'چـه‌شــمـه ئـه‌شـه‌نـه‌ن',
'actions'                        => 'عملکاردون',
'namespaces'                     => 'ایسم فضائون',
'variants'                       => 'گویش‌ئون',

'errorpagetitle'    => 'خطا!',
'returnto'          => 'بردگستن تا $1',
'tagline'           => '{{SITENAME}} جه',
'help'              => 'کمک و راهنمایی',
'search'            => 'چـأرخـه تـو',
'searchbutton'      => 'چـأرخـه‌تـو',
'go'                => 'بـور',
'searcharticle'     => 'بور',
'history'           => 'صفحه‌ی تاریخچه',
'history_short'     => 'تاریخچه',
'updatedmarker'     => 'عوض بَیی پس از آخرین بار که بی‌یمومه',
'info_short'        => 'اطیلاعات',
'printableversion'  => 'په‌رینت ده‌لـماج',
'permalink'         => 'مـونـده‌نـه‌سـی لـیـنـک',
'print'             => 'په‌ریـنت',
'view'              => 'نمایش',
'edit'              => 'دچی‌ین',
'create'            => 'بـأئـیـتـه‌ن',
'editthispage'      => 'این صفحه ره دچی‌ین',
'create-this-page'  => 'این صفحه ره شِما بساجین',
'delete'            => 'پاک هاکردن',
'deletethispage'    => 'این صفحه ره پاک هاکردن',
'undelete_short'    => 'احیای {{PLURAL:$1|ات دچی‌یه|$1 دچی‌یه}}',
'viewdeleted_short' => 'نمایش {{PLURAL:$1|اتا دچی‌یه حذف بَیی|$1 دچی‌یه حذف بَیی}}',
'protect'           => 'موحافظت',
'protect_change'    => 'ده‌گـه‌ره‌سـه‌ن',
'protectthispage'   => 'این صفحه جه موحافظت هکردن',
'unprotect'         => 'دیگه محافظت نکان',
'unprotectthispage' => 'این صفحه جه موحافظت نکاردن',
'newpage'           => 'نو صفحه',
'talkpage'          => 'این صفحه درباره گپ بَزوئِن',
'talkpagelinktext'  => 'گپ',
'specialpage'       => 'شا صفحه',
'personaltools'     => 'مه‌شه ابزار',
'postcomment'       => 'نو تیکه',
'articlepage'       => 'نمایش صفحه',
'talk'              => 'گپ',
'views'             => 'هارشی‌ئون',
'toolbox'           => 'أبـزار جـا',
'userpage'          => 'کارور صفحه ره نشون هدائن',
'projectpage'       => 'بدی‌ین پروژه‌ی ِصفحه',
'imagepage'         => 'بدی‌ین ِعکس ِصفحه',
'mediawikipage'     => 'پیغوم ره بدی‌ین',
'templatepage'      => 'بدی‌ین شابلون',
'viewhelppage'      => 'بدی‌ین رانما',
'categorypage'      => 'بدی‌ین رج',
'viewtalkpage'      => 'گپ ئون ره نشون هدائن',
'otherlanguages'    => 'بقیه زوون‌ئون',
'redirectedfrom'    => '($1 جه بموئه)',
'redirectpagesub'   => 'گجگی‌بَیتـِن',
'lastmodifiedat'    => 'این صفحه ره آخرین جورهکاردن ره بنه وخت ره وند بونه:
$2، $1',
'viewcount'         => 'این صفحه {{PLURAL:$1|ات|$1}} بار بدی‌یه بیّه',
'protectedpage'     => 'صفحه محافظت‌بَیی',
'jumpto'            => 'کپّل بیّن به:',
'jumptonavigation'  => 'بگردستن',
'jumptosearch'      => 'بخوندستن',
'pool-errorunknown' => 'خطای ناشناخته',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} درباره',
'aboutpage'            => 'Project:About',
'copyright'            => 'این صفحه ره بَنوشته‌ئون  $1  زیر شِمه دسـت دَرنه.',
'copyrightpage'        => '{{ns:project}}:کـوپـی‌راسـت‌ئون',
'currentevents'        => 'اسایی دکته‌ئون',
'currentevents-url'    => 'پروژه:اسایی دکته‌ئون',
'disclaimers'          => 'تکذیب‌نومه‌ئون',
'disclaimerpage'       => 'پروژه:تکذیب‌نومهٔ همه‌گونی',
'edithelp'             => 'دأچـیه‌ن ره رانـه‌ما',
'edithelppage'         => 'Help:دَچی‌ین',
'helppage'             => 'Help:رونما',
'mainpage'             => 'گت ولگ',
'mainpage-description' => 'گت صفحه',
'policy-url'           => 'Project:سیاستون',
'portal'               => 'کارورون ِلوش',
'portal-url'           => 'Project:کارورون لوش',
'privacy'              => 'سیاست حفظ اسرار',
'privacypage'          => 'Project:سیاست_حفظ_اسرار',

'badaccess'        => 'نتوندی هچی ره هارشی',
'badaccess-group0' => 'شما این کار ره نتونی هاکنین.',

'versionrequired' => 'نوسخهٔ $1 نرم‌افزار مدیاویکی جه لازم هسّه',

'ok'                      => 'خا',
'retrievedfrom'           => '"$1" جه بیته بیّه',
'youhavenewmessages'      => 'شما اتا $1 دانّی ($2).',
'newmessageslink'         => 'ترنه پیغوم‌ئون',
'newmessagesdifflink'     => 'پایانی ده‌گارده‌سه‌ن',
'youhavenewmessagesmulti' => 'شه مه وسه ترنه پیغوم بی یه موئه ای جه $1',
'editsection'             => 'دچی‌ین',
'editold'                 => 'دچی‌ین',
'viewsourceold'           => 'منبع ره هارشائن',
'editlink'                => 'دچی‌ین',
'viewsourcelink'          => 'منبع بدی‌ین',
'editsectionhint'         => 'تیکه: $1 ره دچی‌ین',
'toc'                     => 'بـه‌تـیـم',
'showtoc'                 => 'نشون هاده',
'hidetoc'                 => 'فرو بور',
'collapsible-collapse'    => 'دوستن',
'collapsible-expand'      => 'گت هاکردن',
'thisisdeleted'           => 'نیمایش یا دِباره دربیاردنِ $1؟',
'viewdeleted'             => 'نمایش $1؟',
'restorelink'             => '{{PLURAL:$1|$1|$1}} دچی‌ین پاک بیّه',
'feedlinks'               => 'خَوِرخون:',
'feed-invalid'            => 'خراب بیّن آبونمان ِخَوِرخون',
'feed-unavailable'        => 'خَوِرخونا قابل ایستیفاده نینه',
'site-rss-feed'           => '$1 ره  آراس‌اس خه‌راک',
'site-atom-feed'          => '$1 ره اتم خه‌راک',
'page-rss-feed'           => '"$1" RSS خه‌راک',
'page-atom-feed'          => '"$1" Atom خه‌راک',
'red-link-title'          => '$1 (این صفحه دَنی‌یه)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحه',
'nstab-user'      => 'کارور صفحه',
'nstab-media'     => 'رسانه',
'nstab-special'   => 'شاء صفحه',
'nstab-project'   => 'پروژه',
'nstab-image'     => 'فایل',
'nstab-mediawiki' => 'پیغوم',
'nstab-template'  => 'شابلون',
'nstab-help'      => 'رونما',
'nstab-category'  => 'رج',

# Main script and global functions
'nosuchaction'      => 'نونه اینتی هاکردن',
'nosuchactiontext'  => 'اینتا کار اینترنتی ِنشونی دله غیرموجازه
شما احتمالا اینترنتی آدرس ره ایشتباه بنویشنی یا لینک ایشتبا ره کلیک هاکردنی
همینتی ممکن هسته ایرادی {{SITENAME}} دله داره.',
'nosuchspecialpage' => 'اینتا شاء صفحه وجود ندانّه',
'nospecialpagetext' => '<strong>شما اتا غیرموجاز صفحه ره بخاسنی.</strong>

اینان شاء صفحه‌ئون هستنه: [[Special:SpecialPages|{{int:specialpages}}]]',

# General errors
'error'           => 'خِطا',
'databaseerror'   => 'خطای داده‌ئون پایگا',
'badtitle'        => 'نقش عنوان',
'viewsource'      => 'منبع ره بدی‌ین',
'viewsourcefor'   => '$1 ره وسه',
'actionthrottled' => 'شمه پیش ره بیتنه',

# Login and logout pages
'welcomecreation'         => '==$1، خِش بمونی!==
شِمه حساب بساته بیّه.
فراموش نکانین که [[Special:Preferences|شه ترجیحات {{SITENAME}}]] ره تنظیم هاکنین.',
'yourname'                => 'شمه کاروری‌نوم:',
'yourpassword'            => 'شمه پسورد',
'yourpasswordagain'       => 'پسورد ره دِباره بنویس',
'remembermypassword'      => 'مـه کاروری نوم ئو پـأس‌واجه ره، ای کـامـپـیـوتـه‌ر ده‌لـه وه‌سـه، شـه یـاد بیـه‌ل (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'          => 'شمه کاروری نوم',
'login'                   => 'دله بوردن',
'nav-login-createaccount' => 'دله بوردن / عضو بیّن',
'loginprompt'             => '{{SITENAME}} ره ده‌لـه بیـه‌موئـه‌ن وه‌سه، وه‌نـه cookieئون  کـارسأر بـوئـه‌ن.',
'userlogin'               => 'ده‌لـه‌بـوری / اکـانـت بـأئـیـتـه‌ن',
'userloginnocreate'       => 'دله بموئن',
'logout'                  => 'دربوردن',
'userlogout'              => 'دربوردن',
'notloggedin'             => 'سیستم ره دله نی یه موئین',
'nologin'                 => 'عضو نی؟ $1.',
'nologinlink'             => 'عضو بواشین',
'createaccount'           => 'ترنه حساب وا هکاردن',
'gotaccount'              => 'عضو هسنی؟ $1.',
'gotaccountlink'          => 'بورین دله',
'createaccountmail'       => 'ایمیل ِهمراه',
'createaccountreason'     => 'دلیل:',
'badretype'               => 'دِتا پسوردی که بنویشتی اتجور نینه',
'userexists'              => 'این کاروری نوم وجود داشته.
لطفأ ات نوم دیگه انتخاب هاکنین.',
'loginerror'              => 'دله نشی‌یه',
'nocookiesnew'            => 'حساب کاروری بساته بیّه، اما شِما دله نشینی.
{{SITENAME}} ورود کارورون به سامانه از کوکی استفاده کانده.
شِما کوکی‌ئون ره کار جه دمبدایی.
لطفاً کوکی‌ئون ره به کار دمبده، و سپس با اسم کاروری و پسورد جدید برو دله.',
'nocookieslogin'          => '‏{{SITENAME}} کوکی‌ئون ره کارورون دله بوردن سر کار زنّه. شِما جا خاهش دارمی که وشون ره کار بی‌یلین و دباره سعی هاکنین.‎‎',
'loginsuccess'            => 'شِما إسا با اسم «$1» به {{SITENAME}} دله بمونی.',
'nouserspecified'         => 'شِما ونه أتا کارور نوم مشخص هاکنی.',
'mailmypassword'          => 'اتا نو پسورد بساز و برسِن',
'accountcreated'          => 'کاروری نوم دِرِس بیّه',
'accountcreatedtext'      => 'کاروری نوم، $1 بساته بیّه.',

# JavaScript password checks
'password-strength-bad'  => 'بد',
'password-strength-good' => 'خار',

# Password reset dialog
'newpassword' => 'نو پسورد:',

# Edit page toolbar
'bold_sample'     => 'ضخیم',
'bold_tip'        => 'ضخیم',
'italic_sample'   => 'کج',
'italic_tip'      => 'کج',
'link_sample'     => 'لینک ِسرنوم',
'link_tip'        => 'درونی لینک',
'extlink_sample'  => 'http://www.example.com لینک ره نوم',
'extlink_tip'     => 'دأیـا لـیـنـک (شـه‌مـه یـاد بـوئـه <span dir="ltr">http://</span> ره بـیـه‌لـیـن)',
'headline_sample' => 'متن عنوان',
'headline_tip'    => 'عنوان بند ۲',
'math_sample'     => 'فورمـول ره ایجـه دأکـه‌ن',
'math_tip'        => 'ریاضی فورمول',
'nowiki_sample'   => 'شـه فـورمـأت‌نـه‌دار تـه‌کـسـت ره ایـجـه دأکـه‌نـیـن',
'nowiki_tip'      => 'ویـکـی فـورمـأت ره نـأدیهـه‌ن',
'media_tip'       => 'فایل لینک',

# Edit pages
'summary'                          => 'گوزارش کار:',
'subject'                          => 'موضوع یا عنوان:',
'minoredit'                        => 'اینتا اتّا پـچیک دچی‌یه هسته',
'watchthis'                        => 'این صفحه ره دمبال هـاکاردن',
'savearticle'                      => 'جادکتن ِصفحه',
'preview'                          => 'پیش نمایش',
'showpreview'                      => 'پیش‌نمایش نشون هدائن',
'showlivepreview'                  => 'پیش‌نمایش آنلاین',
'showdiff'                         => 'نمایش تغییرات',
'blockedtext'                      => "'''شمه آی پی دوسته بیّه.'''

این کار ره $1 انجام هدائه.
اینت وسه که ته این کار ره هکردی: $2''

* شروع دوسته بین: $8
* زمون پایان این دوسته گی: $6
* کاوری که خاستمی ونه آی پی ره دوندیم: $7

شما بتونی با $1 با اتا از [[{{MediaWiki:Grouppage-sysop}}|مدیر|مدیرا]] تماس بیرین و ونجه گپ بزنین.

 شمه یاد دواشه که اگه شه ایمیل ره ننوشت بائین نتونی مدیرا وسه ایمیل بزنین اگه ایمیل ره ننوشنی ترجیحات دله بنویسین[[Special:Preferences|اینجه ایمیل ره بنویس]]
نشونی آی‌پی شما $3 و شماره قطع دسترسی شما $5 هسته. حتما این دِتا شوماره ره گپ بزوئن دله به کار بورین.",
'blockednoreason'                  => 'معلوم نی‌یه چچی وسه اینتی بیّه!',
'whitelistedittitle'               => 'جور هکاردن ره وسه ونه سیستم ره دله ئه نین',
'newarticle'                       => '(ترنه)',
'blocked-notice-logextract'        => 'دسترسی اینتا کارور الآن دوستوئه.
آخرین مورد سیاهه قطع دسترسی زیر بموئه:',
'previewnote'                      => 'شِمه یاد بوئه که اینتا اتا پیش‌نمایِش هسه. 
 شِمه دگاردسته‌ئون جانـَکِته که و‌نه، ونه اِسا ذخیره‌بیّـِن دوکمه ره بَزنین!',
'editing'                          => 'دچی‌ین => $1',
'editingsection'                   => 'دچی‌ین $1 (تیکه)',
'yourtext'                         => 'شمه بنویشته',
'copyrightwarning'                 => 'خـاهـه‌ش بـونـه شـه یـاد ده‌لـه دأکـه‌نـیـن کـه هـأمـه کـایـه‌رئونی کـه {{SITENAME}} ده‌لـه بـونـه، $2 جـیـر ره‌هـا بـونـه. (ویـشـتـه‌ر وه‌سـه $1 ره بـأویـنـیـن)<br />
أگـه نـه‌خـانـه‌نـی شـه‌مـه بـأنـویـشـتـه‌ئون ایـجـه دسـت بـأخـوره ئو أتـا جـا دیـگـه پـخـش بـأوه، بـه‌تـه‌ر هـأسـه کـه وه‌شـون ره ایـجـه نـیـه‌لـیـن.',
'templatesused'                    => '{{PLURAL:$1|شابلون|شابلونای}} استفاده بَیی این صفحه دله:',
'templatesusedpreview'             => 'شـابـلـون‌ئونی کی ای پـیـش‌نـه‌مـایـه‌ش ده‌لـه کـار بـورده‌نـه:',
'permissionserrorstext-withaction' => 'ته اجازهٔ $2 ره به {{PLURAL:$1|دلیل|دلایل}} رو به رو ندانی:',
'recreate-moveddeleted-warn'       => "'''هشدار: ته دری اتا صفحه ره نویسنی که قبلا پاک بیّه.'''

شه فکر هاکن که اینتا کار که دری کانده درسته یا نا؟
اینجه توندی پاک بیی صفحه ره هارشی:",
'moveddeleted-notice'              => 'اینتا صفحه پاک بی بی‌یه
اینجه بتوندی قبلی صفحه که پاک بیّه ره هارشی',
'log-fulllog'                      => 'بدی‌ین سیاهه کامل',
'edit-gone-missing'                => '.شما نتوندی صفحه ره دباره هارشی
احتمالا صفحه پاک بیه.',
'edit-conflict'                    => 'دِ نفر با هم درنه نویسنه.
اتا ته هستی.',

# History pages
'revisionasof'     => 'دچی‌یه‌ئونی که $1 جا دکتنه',
'previousrevision' => '→ پـیـشـیـن ده‌گه‌ره‌سه‌ن',
'cur'              => 'ئه‌سا',
'last'             => 'تاریخچه',
'histfirst'        => 'کـوهـنـه تـریـن',
'histlast'         => 'نـو تـریـن',
'historysize'      => '({{PLURAL:$1|۱ بایت|$1 بایت}})',
'historyempty'     => '(خالی)',

# Revision deletion
'rev-delundel'   => 'نشون هدائن/فرو بوردن',
'revdel-restore' => 'دیاری تغییر هدائن',

# Merge log
'revertmerge' => 'سـه‌وا  هـه‌کارده‌ن',

# Diffs
'lineno'   => 'بند  $1:',
'editundo' => 'واچیه‌ن',

# Search results
'searchresults'             => 'چرخه‌توی هه‌دایی‌ئون',
'searchsubtitle'            => 'شـه‌مـا \'\'\'[[:$1]]\'\'\' ره ده‌مـبـال بـورده‌نـی ([[Special:Prefixindex/$1|هـأمـه ولـگ‌ئونـی کـه وه‌شـون نـوم  "$1" هـه‌مـرا سـأر گـیـرنـه ره بـأویـنـه‌ن]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|هـأمه ولـگ‌ئونـی که  "$1" ره لـیـنـک وه‌شـون ده‌لـه دأره]])',
'notitlematches'            => 'هـیـچ ولـگـی شه‌مه گـب ره نـه‌مـاسـتـه',
'prevn'                     => 'پـیـشـیـن {{PLURAL:$1|$1}}',
'nextn'                     => 'تا پَس‌تر {{PLURAL:$1|$1}}',
'viewprevnext'              => 'بـأویـنـه‌ن ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'            => "'''«[[:$1]]» ِصفحه ره این ویکی دله بساجین!'''",
'search-result-size'        => '$1 ({{PLURAL:$2|1 واجه|$2 واجه}})',
'search-redirect'           => '(بـأره‌سـیـه $1 جـه)',
'search-section'            => '(تیکه $1)',
'search-suggest'            => 'شه‌مـا ایـنـتـا ره نـه‌خـاسـه‌نی؟ $1',
'search-interwiki-caption'  => 'خاخه‌ر په‌روجه‌ئون',
'search-interwiki-more'     => '(ویشته‌ر)',
'search-mwsuggest-enabled'  => 'پیشنهاد هه‌مرا',
'search-mwsuggest-disabled' => 'هیچ پیشنهادی دنیه',
'powersearch'               => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',
'powersearch-legend'        => 'سه‌ره‌ک به‌نه‌ک  (پیـش‌بـورده چـأرخـه‌تو)',
'powersearch-ns'            => 'سه‌ره‌ک به‌نه‌ک، نوم‌جائون ده‌له:',
'powersearch-redir'         => '',
'powersearch-field'         => 'سه‌ره‌ک به‌نه‌ک',

# Preferences page
'mypreferences'             => 'مـه خـاسـته‌نی‌ئون',
'prefs-edits'               => 'نومـه‌ره دأچیه‌ن‌ئون:',
'prefsnologin'              => 'سیـستـه‌م ره ده‌لـه نـی‌یـه‌نـی',
'youremail'                 => 'شه مه Email:',
'username'                  => 'کاروری نوم:',
'uid'                       => 'کاروری إشماره:',
'yourrealname'              => 'شیمه راستین ره نوم :',
'yourlanguage'              => 'زیوون:',
'badsig'                    => 'ایمضا بی اعتبار هسه. html کودون ره أی هارشین.',
'email'                     => 'رایانومه',
'prefs-help-realname'       => 'اصلی نوم اختیاری هسه. اگه شه‌ما بنویسین شمه کارون ونه جا ثبت بونه.',
'prefs-help-email'          => 'ایمیل اختیاری هسته. ولی اگه شِما شه پاسورد ره یاد بکارد‌نی نو پاسورد ره شِمسه ایمیل کامبی. شِما همینتی توندی بی‌یلین که دیگه کارورون شمه وسّه کاروری صفحه و کاروری گپ جه ایمیل بَزنن بی اونکه شِمه ایمیل معلوم بَواشه.',
'prefs-help-email-required' => 'ایمیل نشونی لازم هسه.',

# User rights
'userrights-user-editname' => 'کارور نوم ره بنویش هاکنین',

# Groups
'group-sysop' => 'کـاره‌ئون',
'group-all'   => '(هـأمـه)',

'grouppage-sysop' => '{{ns:project}}:کـاره‌ئون',

# User rights log
'rightslog'     => 'سیاهه اختیارای کاروری',
'rightslogtext' => 'اینتا سیاهه تغییرای اختیارای کاروری هسته.',
'rightsnone'    => '(هچّی)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'               => 'این صفحه ره دچی‌ین',
'action-createtalk'         => 'دِرِس هاکردن اتا صفحه که ونه دله بنشنه گپ بزوئن',
'action-createaccount'      => 'درِس هکردن این حساب کاروری',
'action-minoredit'          => 'علامت بزوئن اینتا دچی‌ین به عونوان جوزئی',
'action-move'               => 'دکشی‌ین اینتا صفحه',
'action-move-subpages'      => 'دکشی‌ین اینتا صفحه و ونه زیر رج‌ئون',
'action-move-rootuserpages' => 'دکشی‌ین صفحه‌ئون کاروری سرچله',

# Recent changes
'recentchanges'   => 'تازه دگاردسته‌ئون',
'rcnote'          => "ایجه هأمه {{PLURAL:$1| '''اتا''' ده‌گـه‌ره‌سـه‌نـی|هأمه '''$1''' ده‌گـه‌ره‌سـه‌ئـونـی}} ده‌گه‌ره‌سونی کـه $4، $5 جـه، '''$2''' روز پـیـش‌تـه‌ر دأکـه‌تـه‌نـه ره ویـنـده‌نـی",
'rclistfrom'      => 'نِمایش تازه‌دگاردسته‌ئون با شروع از $1',
'rcshowhideminor' => 'پچیک دچی‌یه‌ئون $1',
'rcshowhidebots'  => 'ربوت‌ئون $1',
'rcshowhideliu'   => 'ثبت‌نوم هاکرده کارورون $1',
'rcshowhideanons' => 'ناشناس ِکارورون $1',
'rcshowhidepatr'  => 'گشت‌بخارد ِدچی‌یه‌ئون $1',
'rcshowhidemine'  => 'منه دچی‌یه‌ئون $1',
'rclinks'         => 'نـِشون هـِدائن  $1 پایانی دَچی‌‌یه‌ئون، $2 اِسـا روز ره دلـه؛ $3',
'diff'            => 'فرق',
'hist'            => 'تاریخچه',
'hide'            => 'فـِرو بوردن',
'show'            => 'نـِشـون هـاده',
'minoreditletter' => 'خورد',
'newpageletter'   => 'نو',
'boteditletter'   => 'ربات',

# Recent changes linked
'recentchangeslinked'         => 'واری دأچیـه‌ن‌ئون',
'recentchangeslinked-feed'    => 'واری دچی‌یه‌ئون',
'recentchangeslinked-toolbox' => 'واری دچی‌یه‌ئون',
'recentchangeslinked-page'    => 'صفحه ایسم:',

# Upload
'upload'        => 'باربی‌یشتن فـایـل',
'uploadbtn'     => 'باربی‌یشتن فایل',
'uploadtext'    => "فرم زیر جه باربی‌یشتن نو پرونده‌ئون وسّه استفاده هاکنین.
بدی‌ین پرونده‌ئونی که قبلاً باربی‌یشته بَینه به [[Special:FileList|فهرست پرونده‌ها]] بورین. باربی‌یشتن مجدد [[Special:Log/upload|سیاههٔ بارگذاری‌ها]] و حذف پرونده‌ئون [[Special:Log/delete|deletion log]] دله ثبت وانه.

بعد از این که پرونده‌یی ره باربی‌یشتنی، به این سه شکل بنشنه وه ره صفحه‌ئون دله بی‌یشتن:

*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' استفاده از نسخه کامل پرونده وسّه
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' استفاده از اتا نسخه ۲۰۰ پیکسلی از پرونده که اتا جعبه سمت چپ متن دله دره و عبارت alt text ونه دله به عنوان توضیح استفاده بیّه وسّه
*'''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' بساتن اتا پیوند مستقیم به پرونده بدون نمایش پرونده",
'uploadlogpage' => 'باربی‌یشتن گزارش',
'uploadedimage' => 'بـاربـیـه‌شـتـه بـأیـه "[[$1]]"',

# Special:ListFiles
'imgfile'        => 'فایل',
'listfiles'      => 'هارشی ئون ره لیست',
'listfiles_name' => 'نـوم',
'listfiles_user' => 'کارور',
'listfiles_size' => 'اندازه',

# File description page
'file-anchor-link'  => 'فایل',
'filehist'          => 'فایل تاریخچه',
'filehist-current'  => 'ئـه‌سـا',
'filehist-datetime' => 'تاریخ/زمون',
'filehist-thumb'    => 'انگوس گتی',
'filehist-user'     => 'کارور',
'filehist-comment'  => 'هارشا',
'imagelinks'        => 'لینک‌ئون',
'linkstoimage'      => 'این {{PLURAL:$1|صفحه|$1 صفحه‌ئون}} لینک هِدانه این فایل ره:',

# Random page
'randompage' => 'شانسی صفحه',

# Statistics
'statistics' => 'آمار',

'disambiguations' => 'گجگجی‌بَیری صفحه‌ئون',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|بایت|بایت}}',
'unusedcategories'  => 'کـار نـأزو رج‌ئون',
'unusedimages'      => 'کـار نأزو فایل‌ئون',
'popularpages'      => 'خاسگار هدار صفحه‌ئون',
'wantedpages'       => 'صفحه‌ئونی که خامبی',
'prefixindex'       => 'هـأمـه ولـگ‌ئونی کـه وه‌شـون سـأرنـوم هـأسـه',
'shortpages'        => 'پچیک صفحه‌ئون',
'longpages'         => 'بِلند صفحه‌ئون',
'listusers'         => 'کارور ئون ره لیست',
'newpages'          => 'نو بساته صفحه‌ئون',
'newpages-username' => 'کارور نوم:',
'ancientpages'      => 'كوهنه صفحه‌ئون',
'move'              => 'دکش هاکردن',
'pager-newer-n'     => '{{PLURAL:$1|أتـا نـه‌ته‌ر|$1 تـا نـه‌ته‌ر}}',
'pager-older-n'     => '{{PLURAL:$1|أتـا کـوهـنـه‌ته‌ر|$1 تـا کوهـنـه‌ته‌ر}}',

# Book sources
'booksources-go' => 'بـور',

# Special:Log
'specialloguserlabel' => 'کارور:',

# Special:AllPages
'allpages'       => 'همه صفحه‌ئون',
'alphaindexline' => '$1 تا  $2',
'prevpage'       => 'پیشین صفحه ($1)',
'allarticles'    => 'همه صفحه‌ئون',
'allpagessubmit' => 'بـور',

# Special:Categories
'categories' => 'رج‌ئون',

# Special:LinkSearch
'linksearch' => 'دأیا لـیـنـک‌ئون',

# Special:ListGroupRights
'listgrouprights-members' => '(کارورئون ره لیست)',

# E-mail user
'mailnologintext' => 'برای برسنی‌ین پوست الکترونیکی به کارورون دیگه ونه [[Special:UserLogin|بورین سامانه دله]] و نشونی پوست الکترونیکی معتبری تو [[Special:Preferences|ترجیحات]] خادت ره داشته بایی.',
'emailuser'       => 'ئـه‌لـه‌کـتـه‌ریـکـی‌ نـومـه ای کـارور وه‌سه',
'emailpage'       => 'ئـی-مه‌یـل ای کـارور وه‌سه',

# Watchlist
'watchlist'            => 'مـه ده‌مـبـالـه‌ئون ره لـیـسـت',
'mywatchlist'          => 'مـه ده‌مـبـال‌هـه‌کـاردن لـیـسـت',
'watchnologin'         => 'سیستم ره دله نی ئه موئین',
'watch'                => 'ده‌مـبال هـاکه‌ردن',
'watchthispage'        => 'این صفحه ره دِمبال هاکارد‌ن',
'unwatch'              => 'ده‌مـبـال نـه‌کـارده‌ن',
'unwatchthispage'      => 'دیگه این صفحه ره دمبال نکاردن',
'watchnochange'        => 'هیچ‌کادوم از چیزایی که شِما دمبال کانـّی چن وقته عوض نینه.',
'watchlist-details'    => 'بدون حیساب گپ ولگ‌ئون، {{PLURAL:$1|$1 صفحه|$1 صفحه}} شمه دمبال‌هاکردنی‌ئون میون قرار {{PLURAL:$1|دارنه|دانه}}.',
'wlheader-enotif'      => '*تونی ایمیل جه مطلع بواشین.',
'wlheader-showupdated' => "*صفحه‌ئونی که بعد از آخرین سربزوئنتون عوض بینه '''پر رنگ''' نشون هدائه بیّه.",
'wlnote'               => "ایجه {{PLURAL:$1|پایانی دأچیه‌ن|پایانی '''$1''' دأچیه‌ن‌ئونی}} هأسه که ای $2 ساعت ده‌له دأکه‌ته.",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ده‌مـبـال هـه‌کـارده‌ن...',
'unwatching' => 'ده‌مـبـال نـه‌کـارده‌ن...',

'enotif_newpagetext' => 'این صفحه نوبساته هسته',
'created'            => 'وا بایه',

# Delete
'deletepage'     => 'صفحه پاک هاکردن',
'deletedarticle' => 'وربـأئـیـتـه بأیه "[[$1]]"',
'dellogpage'     => 'وربأئیته‌نه‌ئون گوزارش',

# Rollback
'rollback'          => 'واچیه‌ن دأچیه‌ن‌ئون',
'rollback_short'    => 'واچیه‌ن',
'rollbacklink'      => 'واچیه‌ن',
'revertpage'        => '"چـیـزونی که [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) دأچـیـه ده‌گـه‌ره‌س بـأیـه هـأمونـتـایی که [[User:$1|$1]] ای وألگ ده‌لـه، پـایـانی بـار هـه‌کـارده"',
'revertpage-nouser' => '"چیزونی که (ونـه کـاروری نـوم پـاک بَیّه) دچی‌یه دگـاردسته بیّه همونتایی که [[User:$1|$1]] آخرسری دچی‌ین دلـه هاکرده"',
'rollback-success'  => 'چیزونی که $1 دچی‌ین دگاردسته بیّه همونتایی که $2 آخرسری دچی‌ین دلـه هاکرده',

# Restrictions (nouns)
'restriction-edit'   => 'دچی‌ین',
'restriction-upload' => 'باربی‌یشتن',

# Undelete
'undeletelink'     => 'بـأویـنـه‌ن / ده‌واره جـا بـیـه‌شـتـه‌ن',
'undeletedarticle' => 'جـا دأکـه‌فـتـه "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'نوم‌جا:',
'blanknamespace' => '(مـار)',

# Contributions
'contributions'       => 'کارور کایه‌رئون',
'contributions-title' => 'کارور کایه‌رئون $1 وه‌سه',
'mycontris'           => 'مـه کـایـه‌رئون',
'contribsub2'         => '$1 ($2) وه‌سه',
'uctop'               => '(سه‌ر)',

'sp-contributions-newbies'  => 'نـه وا بـأیـه ئـه‌کـانـت‌ئون دأچـیـه‌ن‌ئون ره نـه‌شـون هـاده',
'sp-contributions-talk'     => 'گپ',
'sp-contributions-username' => 'IP نـه‌شـونـی یا کـاروری‌نوم',
'sp-contributions-submit'   => 'چـأرخـه‌تـو',

# What links here
'whatlinkshere'       => 'لینک‌ئون ِاینتا صفحه',
'whatlinkshere-title' => 'وألـگ‌ئونی که "$1" ره لـیـنک هه‌دانه',
'whatlinkshere-page'  => 'صفحه:',
'linkshere'           => "ولـگ‌ئـونی کـه لـیـنـک هـه‌دائـه‌نـه '''[[:$1]]''' ره:",
'whatlinkshere-prev'  => '{{PLURAL:$1|پـیـشـیـن|$1 تـای پـیـشـیـن}}',
'whatlinkshere-next'  => '{{PLURAL:$1|پَس|$1 تا پَس‌تر}}',
'whatlinkshere-links' => '← لـیـنـک‌ئون',

# Block/unblock
'blockip'          => 'کارور دأبه‌سته‌ن',
'ipbsubmit'        => 'ای کارور دأبه‌س بأوه',
'ipblocklist'      => 'IP نـه‌شـونـی‌ئون ئو کـارورنـوم‌ئونی کـه دأبـه‌سـتـوونـه',
'blocklink'        => 'دأبـه‌سـتـه‌ن',
'unblocklink'      => 'وا هـه‌کـارده‌ن',
'change-blocklink' => 'قطع دسترسی تغییر هدائن',
'contribslink'     => 'کایه‌رئون',
'blocklogentry'    => '[[$1]] دأبـه‌سـتـو بـأیـه ئو وه‌نـه دأبه‌ستو بوئه‌ن زأمـون، تـا  $2 $3 هـأسـه',

# Move page
'newtitle'                => 'ته‌رنـه نـوم:',
'movepage-moved'          => "'''ای «$1» ولـگ،  بورده «$2» ره.'''",
'movetalk'                => '«گپ» صفحه هم، اگه وانه، بوره.',
'1movedto2'               => '[[$1]] بـورده [[$2]] ره',
'revertmove'              => 'واچـیـه‌ن',
'delete_and_move_confirm' => 'أره، پاک هاکه‌ن وه ره',

# Export
'export' => 'دأیابأبه‌رده‌ن ولـگ‌ئون',

# Thumbnails
'thumbnail-more' => 'گت بأوه',

# Special:Import
'import-interwiki-submit' => 'بیاردن',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'مه کاروری صفحه',
'tooltip-pt-mytalk'              => 'مه گپ صفحه',
'tooltip-pt-preferences'         => 'مه خواسته‌نی‌ئون',
'tooltip-pt-watchlist'           => 'لیست ولـگ‌ئونی که وه‌شون ره دچیه‌ن‌ئون وه‌سه ده‌مـبـال که‌نده‌نی',
'tooltip-pt-mycontris'           => 'مه کایه‌رئون ره لیست',
'tooltip-pt-login'               => 'شه‌ما به‌ته‌ر هـأسـه که سـیـسـتـه‌م ده‌لـه بـیـه‌ئی، هـرچـأن زوری نـیـه',
'tooltip-pt-logout'              => 'سیستم جه دأیابـوری',
'tooltip-ca-talk'                => 'صفحه درباره گپ بَزوئن',
'tooltip-ca-edit'                => 'شِما بتوندی این صفحه ره دَچینی.',
'tooltip-ca-addsection'          => 'أتـا نـه گـب را دأکـه‌تـه‌ن',
'tooltip-ca-viewsource'          => 'این صفحه ره نتوندی دَچینی.
شِما بِتوندی ونه منبع ره هارشی.',
'tooltip-ca-history'             => 'کهنه دگاردسته‌ئونی که این صفحه دله دکته',
'tooltip-ca-delete'              => 'این صفحه ره پاک هاکردن',
'tooltip-ca-watch'               => 'این صفحه ره شه دمبال‌هاکردن لیست دله بی‌یشتن',
'tooltip-search'                 => '{{SITENAME}} ره چـأرخـه‌تـو',
'tooltip-search-go'              => 'بـور اتـا ولـگـی کـه وه‌نـه نـوم هـأمـیـنـتـا بـوئـه',
'tooltip-search-fulltext'        => 'ولـگ‌ئـون ره ایـنـتـا تـه‌کـسـت وه‌سـه چـأرخ بـأزوئـه‌ن',
'tooltip-p-logo'                 => 'گَت صفحه ره بَدی‌ین',
'tooltip-n-mainpage'             => 'گت صفحه ره بدی‌ین',
'tooltip-n-mainpage-description' => 'گَت ِصفحه ره هارشائن',
'tooltip-n-portal'               => 'په‌روجه ده‌له‌واره، چه‌شی به‌توده‌نی هاکه‌نی ئو که‌جه چیزئون ره بأره‌سی',
'tooltip-n-currentevents'        => 'تازه چی‌ئون درباره بدونستن',
'tooltip-n-recentchanges'        => 'ای ویکی ده‌له، ئه‌سا دچیه‌نون ره لیست',
'tooltip-n-randompage'           => 'اتت شانسی صفحه بَدی‌ین',
'tooltip-n-help'                 => 'أتـا جـا کـه...',
'tooltip-t-whatlinkshere'        => 'هأمو ولـگ‌ئونی که ایجه ره لینک هه‌دانه',
'tooltip-t-recentchangeslinked'  => 'اسایی دگاردسته‌ئون صفحه‌ئونی دله، که این صفحه جه لینک دارنه',
'tooltip-feed-rss'               => 'RSS خوراک این صفحه وسّه',
'tooltip-feed-atom'              => 'Atom خوراک این صفحه وسّه',
'tooltip-t-emailuser'            => 'ای کـارور ره اتـا ئـه‌لـه‌کـتـه‌رونـیـکـی‌نـومـه راهـی هـه‌کـارده‌ن',
'tooltip-t-upload'               => 'بـاربـیـه‌شـتـه‌ن فـایـل‌ئون',
'tooltip-t-specialpages'         => 'همه شا صفحه‌ئون ِلیسـت',
'tooltip-t-print'                => 'پِرینت هـاکاردن صفحه دگاردسته',
'tooltip-t-permalink'            => 'موندستنی لینک این صفحه ره اینتا محتوا وسّه',
'tooltip-ca-nstab-main'          => 'بدی‌ین ِصفحه',
'tooltip-ca-nstab-user'          => 'کاروری صفحه ره بَدی‌ین',
'tooltip-ca-nstab-special'       => 'اینتا اتا شا صفحه هسته که شِما نتوندی وه ره دچینی',
'tooltip-ca-nstab-image'         => 'عکس ِصفحه ره بدی‌ین',
'tooltip-ca-nstab-template'      => 'شـابـلـون بـأویـنـه‌ن',
'tooltip-preview'                => 'شـه ده‌گـه‌ره‌سـه‌ئون ره پـیـشـاپـیـش بـأویـنـه‌ن،
 خـا‌هـه‌ش بـونـه، شـه کـارئون ره جـا دأکـه‌تـه‌ن پـیـش، ای ره کـار بـأزه‌نـی.',

# Browsing diffs
'previousdiff' => 'کوهنه‌تر دچی‌ین ←',
'nextdiff'     => 'ته‌رنه دأچیه‌ن ←',

# Media information
'thumbsize'            => 'أنـگـوسـی گأتی:',
'file-info-size'       => '$1 × $2 پـیـکـسه‌ل, فـایـل گـأتـی: $3, MIME مـونـد: $4',
'file-info-png-frames' => '$1 {{PLURAL:$1|قاب|قاب}}',

# Special:NewFiles
'newimages'             => 'گالری نو عکس‌ئون',
'imagelisttext'         => 'فهرست بن $1 {{PLURAL:$1|عکسی|عکسی}} که $2 مرتب بیی‌یه بموئه.',
'newimages-summary'     => 'این صفحه شا آخرین عکس‌ئون بار بی‌یشته ره نیمایش دنه',
'newimages-label'       => 'ایسم عکس (یا ات تیکه که ونه شه):',
'showhidebots'          => '(دچی‌یه‌ن روباتا $1)',
'noimages'              => 'هچی دنی‌یه که هارشی.',
'ilsubmit'              => 'بگردستن',
'bydate'                => 'تاریخ رو جه',
'sp-newimages-showfrom' => 'نشون‌هدائن عکسای نو $2، $1 جه به بعد',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2×$3',

# EXIF tags
'exif-gpsareainformation' => 'جی پی اس ناحیه نوم',
'exif-gpsdatestamp'       => 'جی پی اس روز',
'exif-gpsdifferential'    => 'جی پی اس په‌چه‌ک درس هأکه‌ردن',

# EXIF attributes
'exif-compression-1' => 'فه‌شورده نئی',

'exif-unknowndate' => 'نه‌شناسی روز',

'exif-orientation-1' => 'معمولی',
'exif-orientation-3' => '180 درجه چرخ بزوئن',
'exif-orientation-4' => 'عمودی په‌شت ئو روبئی',

# External editor support
'edit-externally' => 'ای فـایـل ره، أتـا دأیـا بـه‌رنـومـه هـه‌مـرا، دأچـیـه‌نـیـن',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'هـأمـه',
'namespacesall' => 'هأمه',
'monthsall'     => 'هـأمـه',

# E-mail address confirmation
'confirmemail_body_changed' => 'ات نفر، احتمالأ خاد شمِا، از نشونی آی‌پی $1 نشونی پوست ایلکتورونیک حیساب «$2» {{SITENAME}} ره تغییر هدائه.

برای تایید این که این حیساب واقعاً شمه شه و فعال هکردن دبارهٔ ویژگی پوست ایلکتورونیک {{SITENAME}}، پیوند زیر دله ره شه مرورگر دله وا هکنین:

$3

اگه این حساب شه مه نی‌یه، پیوند زیر ره دنبال هکنین تا تغییر پوست ایلیکتورونیک ره لغو هکنین:

$5

این تایید یه در $4 منقضی وانه.',

# Multipage image navigation
'imgmultigo' => 'بور!',

# Auto-summaries
'autosumm-blank'   => 'صفحه ره اسپه هاکرده',
'autosumm-replace' => "صفحه ره اینتا جه عوض هاکرد: '$1'",

# Special:SpecialPages
'specialpages' => 'شا صفحه‌ئون',

);
