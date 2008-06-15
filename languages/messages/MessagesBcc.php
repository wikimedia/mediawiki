<?php
/** Southern Balochi (بلوچی)
 *
 * @ingroup Language
 * @file
 *
 * @author Mostafadaneshvar
 * @author Siebrand
 */

$fallback = 'fa';

$specialPageAliases = array(
	'CreateAccount'             => array( 'شرکتن_حساب' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'لینکانآ خط کش',
'tog-highlightbroken'         => 'پرشتگین لینکانآ فرمت کن <a href="" class="new">په داب شی</a> (یا: پی ای داب<a href="" class="internal">?</a>).',
'tog-justify'                 => 'پاراگرافنآ همتراز کن',
'tog-hideminor'               => 'هوردین تغییراتآ ته نوکین تغییرات پناه کن',
'tog-extendwatchlist'         => 'لیست چارگ مزن کن دان کل تغییرات قابل قبول پیش دراگ بیت',
'tog-usenewrc'                => 'تغییرات نوکین بهتر بوتگین(جاوا اکریپت)',
'tog-numberheadings'          => 'اتوماتیک شماره کتن عناوین',
'tog-showtoolbar'             => 'میله ابزار اصلاح پیش درا(جاوا)',
'tog-editondblclick'          => 'صفحات گون دو کلیک اصلاح کن(جاوا)',
'tog-editsection'             => 'فعال کتن کسمت اصلاح از طریق لینکان  [edit]',
'tog-editsectiononrightclick' => 'فعال کتن اصلاح کسمت گون کلیک راست اور کسمت عناوین(جاوا)',
'tog-showtoc'                 => 'جدول محتوای‌ء پیش دار( په صفحیانی که گیش چه 3 عنوانش هست)',
'tog-rememberpassword'        => 'منی وارد بیگ ته ای کامپیوتر هیال کن',
'tog-editwidth'               => 'جعبه اصلاح کل پهنات هست',
'tog-watchcreations'          => 'هور کن منی صفحاتی که من ته لیست چارگ شرکتت',
'tog-watchdefault'            => 'هورکن صفحاتی که من اصلاح کتن ته منی لیست چارگ',
'tog-watchmoves'              => 'هور کن صفحاتی که من جاه په جاه کت ته منی لیست چارگ',
'tog-watchdeletion'           => 'هور کن صفحاتی که من ته لیست چارگ که من حذف کتن',
'tog-minordefault'            => 'په طور پیش فرض کل اصلاحات آ په داب جزی مشخص کن',
'tog-previewontop'            => 'بازبین پیش دار پیش چه جعبه اصلاح',
'tog-previewonfirst'          => 'ته اولین اصلاح بازبینی پیش دار',
'tog-nocache'                 => 'کش کتن صفحه یا غیر فعال کن',
'tog-enotifwatchlistpages'    => 'منی ایمیل جن وهدی که یک صفحه ای ته منی لیست چارگ عوص بیت',
'tog-enotifusertalkpages'     => 'منآ ایمیل جن وهدی که صفحه ی گپ کاربر من عوض بیت',
'tog-enotifminoredits'        => 'من ایمیل جن همی داب په هوردین اصلاحات صفحات',
'tog-enotifrevealaddr'        => 'منی ایمیل پیش دار ته ایمیل أن هوژاری',
'tog-shownumberswatching'     => 'پیش دار تعداد کاربرانی که چارگتن',
'tog-fancysig'                => 'حامین امضا يان(بی اتوماتیکی لینک)',
'tog-externaleditor'          => 'به طور پیش فرض اصلاح کنوک حارجی استفاده کن',
'tog-externaldiff'            => 'به طور پیش فرض چه حارجی تمایز استفاده کن',
'tog-showjumplinks'           => 'فعال کن "jump to" لینکان دست رسی آ',
'tog-uselivepreview'          => 'چه زنده این بازبین استفاده کن(جاوا)(تجربی)',
'tog-forceeditsummary'        => 'من آ هال دی وهدی وارد کتن یک هالیکین خلاصه ی اصلاح',
'tog-watchlisthideown'        => 'منی اصلاحات آ چه لیست چارگ پناه کن',
'tog-watchlisthidebots'       => 'اصلاحات بوت چه لیست چارگ پناه کن',
'tog-watchlisthideminor'      => 'هوردین اصلاحات چه لیست چارگ پناه کن',
'tog-nolangconversion'        => 'غیر فعال کتن بدل کتن مغایرت آن',
'tog-ccmeonemails'            => 'په من یک کپی چه ایمیل آنی که من په دگه کاربران راه داته دیم دی',
'tog-diffonly'                => 'چیر تفاوت محتوای صفحه ی پیش مدار',
'tog-showhiddencats'          => 'پناه ین دسته یان پیش دار',

'underline-always'  => 'یکسره',
'underline-never'   => 'هچ وهد',
'underline-default' => 'پیشفرضین بروزر',

'skinpreview' => '(بازین)',

# Dates
'sunday'        => 'یک شنبه',
'monday'        => 'دوشنبه',
'tuesday'       => 'سی شنبه',
'wednesday'     => 'چارشنبه',
'thursday'      => 'پنچ شنبه',
'friday'        => 'آدینگ',
'saturday'      => 'شنبه',
'sun'           => 'ی.شنبه',
'mon'           => 'د.شنبه',
'tue'           => 'س.شنبه',
'wed'           => 'چ.شنبه',
'thu'           => 'پ.شنبه',
'fri'           => 'آدینگ',
'sat'           => 'شنبه',
'january'       => 'ژانویه',
'february'      => 'فوریه',
'march'         => 'مارس',
'april'         => 'آپریل',
'may_long'      => 'می',
'june'          => 'جون',
'july'          => 'جولای',
'august'        => 'آگوست',
'september'     => 'سپتامبر',
'october'       => 'اکتبر',
'november'      => 'نومبر',
'december'      => 'دسمبر',
'january-gen'   => 'ژانویه',
'february-gen'  => 'فوریه',
'march-gen'     => 'مارس',
'april-gen'     => 'آپریل',
'may-gen'       => 'می',
'june-gen'      => 'جون',
'july-gen'      => 'جولای',
'august-gen'    => 'آگوست',
'september-gen' => 'سپتمبر',
'october-gen'   => 'اکتبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'دسمبر',
'jan'           => 'جن',
'feb'           => 'فب',
'mar'           => 'ما',
'apr'           => 'آپر',
'may'           => 'می',
'jun'           => 'جون',
'jul'           => 'جول',
'aug'           => 'آگ',
'sep'           => 'سپت',
'oct'           => 'اکت',
'nov'           => 'نو',
'dec'           => 'دس',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Category|Categories}}',
'category_header'        => 'صفحات ته دسته "$1"',
'subcategories'          => 'زیردسته جات',
'category-media-header'  => 'مدیا ته دسته "$1"',
'category-empty'         => "''ای دسته ی هچ صفحه یا مدیا نیست''",
'hidden-categories'      => '{{PLURAL:$1|Hidden category|Hidden categories}}',
'listingcontinuesabbrev' => 'ادام.',

'mainpagetext' => "<big>''مدیا وی کی گون موفقیت نصب بون.'''</big>",

'about'          => 'باره',
'newwindow'      => '(ته نوکین پنچره ی پچ کن)',
'cancel'         => 'کنسل',
'qbfind'         => 'درگیزگ',
'qbedit'         => 'اصلاح',
'qbpageoptions'  => 'صفحه',
'qbpageinfo'     => 'متن',
'qbmyoptions'    => 'منی صفحات',
'qbspecialpages' => 'حاصین صفحات',
'moredotdotdot'  => 'گیشتر...',
'mypage'         => 'می صفحه',
'mytalk'         => 'منی گپ',
'anontalk'       => 'گپ کن گون ای آی پی',
'navigation'     => 'گردگ',
'and'            => 'و',

# Metadata in edit box
'metadata_help' => 'متادیتا',

'errorpagetitle'    => 'حطا',
'returnto'          => 'تررگ به $1.',
'tagline'           => 'چه {{sitename}}',
'help'              => 'کمک',
'search'            => 'گردگ',
'searchbutton'      => 'گردگ',
'go'                => 'برو',
'searcharticle'     => 'برو',
'history'           => 'تاریح صفحه',
'history_short'     => 'تاریح',
'updatedmarker'     => 'په روچ بیتگین چه منی اهری  اهری  چارگ',
'info_short'        => 'اطلاعات',
'printableversion'  => 'نسخه چهاپی',
'permalink'         => 'دایمی لینک',
'print'             => 'چهاپ',
'edit'              => 'اصلاح',
'create'            => 'شرکتن',
'editthispage'      => 'ای صفحه اصلاح کن',
'create-this-page'  => 'ای صفحه شرکتن کن',
'delete'            => 'حذف',
'deletethispage'    => 'ای صفحه حذف کن',
'undelete_short'    => 'حذف مکن {{PLURAL:$1|one edit|$1 edits}}',
'protect'           => 'حفاظت',
'protect_change'    => 'عوض کتن حفاظت',
'protectthispage'   => 'ای صفحه حفاظت کن',
'unprotect'         => 'محافظت مکن',
'unprotectthispage' => 'ای صفحه محافظت مکن',
'newpage'           => 'نوکین صفحه',
'talkpage'          => 'ای صفحه بحث کن',
'talkpagelinktext'  => 'گپ کن',
'specialpage'       => 'حاصین صفحه',
'personaltools'     => 'شخصی وسایل',
'postcomment'       => 'یک نظر دیم دی',
'articlepage'       => 'محتوا صفحه به گند',
'talk'              => 'بحث',
'views'             => 'چارگان',
'toolbox'           => 'جعبه ابزار',
'userpage'          => 'به گند صفحه کاربر',
'projectpage'       => 'به گند صفحه',
'imagepage'         => 'به گند صفحه',
'mediawikipage'     => 'به گند صفحه کوله',
'templatepage'      => 'به گند صفحه تمپلت آ',
'viewhelppage'      => 'به گند صفحه کمک آ',
'categorypage'      => 'به گند صفحه دسته آ',
'viewtalkpage'      => 'به گند بحث آ',
'otherlanguages'    => 'ته دگر زبان',
'redirectedfrom'    => '(غیر مستقیم بوتگ چه $1)',
'redirectpagesub'   => 'صفحه غیر مستقیم',
'lastmodifiedat'    => '  $2, $1.ای صفحه اهری تغییر دهگ بیته', # $1 date, $2 time
'viewcount'         => 'ای صفحه دسترسی بیتگ {{PLURAL:$1|بار|$1رند}}.',
'protectedpage'     => 'صفحه محافظتی',
'jumpto'            => 'کپ به:',
'jumptonavigation'  => 'گردگ',
'jumptosearch'      => 'گردگ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'باره {{SITENAME}}',
'aboutpage'            => 'Project:باره',
'bugreports'           => 'گزارشات باگ',
'bugreportspage'       => 'Project:گزارشات باگ',
'copyright'            => 'محتوا موجودانت تحت $1.',
'copyrightpagename'    => 'حق کپی{{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:حق کپی',
'currentevents'        => 'هنوکین رویداد',
'currentevents-url'    => 'Project:هنوکین رویداد',
'disclaimers'          => 'بی میاری گیان',
'disclaimerpage'       => 'Project:عمومی بی میاریگان',
'edithelp'             => 'کمک اصلاح',
'edithelppage'         => 'Help:اصلاح',
'faq'                  => 'ب.ج.س',
'faqpage'              => 'Project:ب.ج.س',
'helppage'             => 'Help:محتوا',
'mainpage'             => 'صفحه اصلی',
'mainpage-description' => 'صفحه اصلی',
'policy-url'           => 'Project:سیاست',
'portal'               => 'پرتال انجمن',
'portal-url'           => 'Project:پرتال انجمن',
'privacy'              => 'سیاست حفظ اسرار',
'privacypage'          => 'Project:سیاست حفظ اسرار',
'sitesupport'          => 'مدتان',
'sitesupport-url'      => 'Project:حمایت سایت',

'badaccess'        => 'حطا اجازت',
'badaccess-group0' => 'شما مجاز نهیت عملی که درخواست کت اجرا کنیت',
'badaccess-group1' => 'عملی که ما درخواست کتت مربوط به گروه کابران $1.',
'badaccess-group2' => 'کاری که شما درخواست کت محدود په کاربران ته یکی چه گروهان $1.',
'badaccess-groups' => 'کاری که شما درخواست کت محدود په کابران ته یکی چه گروهان $1.',

'versionrequired'     => 'نسخه $1. مدیا وی کی نیازنت',
'versionrequiredtext' => 'نسخه $1 چه مدیا وی کی نیازنت په استفاده ای صفحه. بچار [[Special:Version|version page]].',

'ok'                      => 'هوبنت',
'retrievedfrom'           => 'درگیجگ بیت چه  "$1"',
'youhavenewmessages'      => 'شما هست  $1 ($2).',
'newmessageslink'         => 'نوکین کوله یان',
'newmessagesdifflink'     => 'اهری تغییر',
'youhavenewmessagesmulti' => 'شما را نوکین کوله یان هست ته   $1',
'editsection'             => 'اصلاح',
'editold'                 => 'اصلاح',
'viewsourceold'           => 'به گند منبع ا',
'editsectionhint'         => ': $1اصلاح انتخاب',
'toc'                     => 'محتوا',
'showtoc'                 => 'پیش دار',
'hidetoc'                 => 'پناه کن',
'thisisdeleted'           => 'به گند یا پچ ترین $1?',
'viewdeleted'             => 'به گند $1?',
'restorelink'             => '{{PLURAL:$1|یک حذف اصلاح|$1 حذف اصلاح}}',
'feedlinks'               => 'منبع',
'feed-invalid'            => 'نامعتبرنوع  اشتراک منبع',
'feed-unavailable'        => 'سندیکا منبع موجود نهت ته {{SITENAME}}',
'site-rss-feed'           => 'منبع $1 RSS',
'site-atom-feed'          => 'منبع $1 Atom',
'page-rss-feed'           => 'منبع "$1" RSS',
'page-atom-feed'          => 'منبع "$1" Atom',
'feed-atom'               => 'اتم',
'red-link-title'          => '$1(هنگت نویسگ نه بیته)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحه',
'nstab-user'      => 'صفحه کاربر',
'nstab-media'     => 'صفحه مدیا',
'nstab-special'   => 'حاصین',
'nstab-project'   => 'صفحه پروژه',
'nstab-image'     => 'فایل',
'nstab-mediawiki' => 'کوله',
'nstab-template'  => 'تمپلت',
'nstab-help'      => 'صفحه کمک',
'nstab-category'  => 'دسته',

# Main script and global functions
'nosuchaction'      => 'نی چشین عمل',
'nosuchactiontext'  => 'کاری که گون URL مشخص بیته گون وی کی پچاه آرگ نبیت',
'nosuchspecialpage' => 'نی چشین حاصین صفحه',
'nospecialpagetext' => "<big>'''شما یک نامعتبرین صفحه حاصین درخواستکت.'''</big>

یک لیستی چه معتبرین صفحات حاص در کپیت ته [[Special:Specialpages|{{int:specialpages}}]].",

# General errors
'error'                => 'حطا',
'databaseerror'        => 'حطا دیتابیس',
'dberrortext'          => 'یک اشتباه ته درخواست دیتابیس پیش آتک.
شی شاید یک باگی ته نرم افزار پیش داریت.
آهرین تلاش درخواست دیتابیس بوته:
<blockquote><tt>$1</tt></blockquote>
"<tt>$2</tt>".
ته ای عملگر ما اس کیو ال ای حطا پیش داشتت "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'یک اشتباه ته درخواست دیتابیس پیش آتک.
آهری تلاش درخواست دیتابیس بوتت:
"$1"
چه ای عملگر"$2".
مای اس کیو ال ای حطا پیش داشتت  "$3: $4"',
'noconnect'            => 'شرمنده. وی کی تکنیکی مشکلاتی هست و نه تونیت گون دیتابیس اتصال گریت.<br />
$1',
'cachederror'          => 'جهلیگین یک کپی ذخیره ای چه صفحه درخواستین و ممکننت نوک مبیت.',
'laggedslavemode'      => 'هوژاری: صفحه شاید نوکین په روچ بییگان داشته می بیت',
'readonly'             => 'دیتابیس کبلنت',
'enterlockreason'      => 'یک دلیلی په کبل وارد کنیت، شامل یک برآوردی چه وهد کبل ویل بیت',
'readonlytext'         => 'دیتابیس هنو کبلنت اور نوکین ورودیان و تغییرات شابد په داشتن روتین دیتابیس، بعد آی په حالت نرمال تریت.

مدیری که آیآ کبل کتت ای توضیحات داتت: $1',
'missing-article'      => 'دیتابیس نه تونیت متن یک صفحه ی که پیداگ بوتت در گیزیت په نام"$1" $2.

شی معمولای به وسیله ی یک تاریح گوشتگین تاریح یا لینک تاریح به یک صفحه که حذف بوتت پیش کیت.

اگه شی دلیل نهنت، شما شاید یک باگی ته نرم افزار در گهتگ. لطفا په مدیر گزارش دهیت، ای URL یادداشت کینت.',
'missingarticle-rev'   => '(بازبینی#: $1)',
'missingarticle-diff'  => '(تفاوت: $1, $2)',
'readonly_lag'         => 'دیتابیس اتوماتیک کبل بیت وهدی که سرورآن دیتابیس برده مستر بیت.',
'internalerror'        => 'حطادرونی',
'internalerror_info'   => 'حطا درونی: $1',
'filecopyerror'        => ' "$1" به "$2".نه تونیت فایل کپی کنت',
'filerenameerror'      => 'نه تونیت فایل نام عوض کنت  "$1" به "$2".',
'filedeleteerror'      => 'نه تونیت فایل حذف کنت  "$1".',
'directorycreateerror' => 'نه تونیت مسیر شرکتن  "$1".',
'filenotfound'         => 'نه تونیت فایل درگیزگ "$1".',
'fileexistserror'      => 'نه تونیت فایل بنویسیت به  "$1": فایل هستنت',
'unexpected'           => 'ارزش نه لوٹتیگن : "$1"="$2".',
'formerror'            => 'حطا: نه تونیت فرم دیم دنت',
'badarticleerror'      => 'ای کار ته ای صفحه اجرای نه بیت',
'cannotdelete'         => 'نه نونیت فایل یا صفحه مشخص بیتگین آ حذف کن.
شاید گون یکی دگه  حذف بوتت',
'badtitle'             => 'عنوان بد',
'badtitletext'         => 'لوٹتگین عنوان صفحه نامعتبر ،هالیک یا یک عنوان هرابین لینک بین زبانی یا بین وی کی انت.
آی شاید شامل یک یا گیشترین کاراکترانت که ته عناوین استفاده نه بنت.',
'perfdisabled'         => 'شرمنده! ای ویژگی الان غیر فعالنت شاید شی پیش داریت که دیتابیس هاموشنت که هچ کس نه تونیت وی کی استفاده کنت.',
'perfcached'           => 'جهلیگین دیتا ذخیره بیتگنت و شاید نوک می بنت.',
'perfcachedts'         => 'جهلیگین دیتا ذخیره بیتگنت و اهرین په روچ بیگ $1.',
'querypage-no-updates' => 'په روچ بیگان په ای صفحه الان غیر فعالنت. دیتا ادان الان نوکین نهنت.',
'wrong_wfQuery_params' => 'اشتباهین پارامتر به wfQuery()<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'به گند منبع آ',
'viewsourcefor'        => 'په $1',
'actionthrottled'      => 'کار گیر نت',
'actionthrottledtext'  => 'په خاطر یک معیار ضد اسپم شما چه انجام ای کار ته یک کمی زمان محدود بیتگیت، و شما چه ای محدودیت رد بیتگیت.
لطفا چند دقیقه بعد کوشست کن',
'protectedpagetext'    => 'ای صفحه کبل بوتت په حاطر اصلاح بیگ',
'viewsourcetext'       => 'شما تونیت به گند و کپی کنیت منبع ای صفحه آ',
'protectedinterface'   => 'ای صفحه فراهم آریت مداخله ی متنی په برنامه و کبل بیتت په جلوگیری چه سو استفاده.',
'editinginterface'     => "'''Warning:''' شما یک صفحه ای اصلاح کنیت که به عنوان مداخله گر متنی برنامه استفاده بیت.
تغییرات ای صفحه کاربرد مداخله گر په دگه کابران تاثیر هلیت.
  [http://translatewiki.net/wiki/Main_Page?setlang=en Betawiki],  په ترجمه یان لطفا توجه کنیت په استفاده پروژه ملکی کتن مدیا وی کی",
'sqlhidden'            => '(SQL درخواست پناهین)',
'cascadeprotected'     => 'ای صفحه محافظت بیت چه اصلاح چرا که آیی شامل جهلیگین {{PLURAL:$1|صفحه, که|صفحات, که}} محافظتی گون the "cascading" option turned on:
$2',
'namespaceprotected'   => "شما اجازت په اصلاح صفحات ته  '''$1'' نام فضا نیست",
'customcssjsprotected' => 'شما را اجازت په اصلاح ای صفحه نیستپه چی که آی شامل شامل  تنظیمات شخصی دگه کاربر اینت',
'ns-specialprotected'  => 'حاصین صفحات اصلاح نه بنت',
'titleprotected'       => "ای عنوان محافظت بوتت چه سربیگ به وسیله  [[User:$1|$1]].
ای دلیل دییگ بیتت ''$2''.",

# Login and logout pages
'logouttitle'                => 'دربیگ کاربر',
'logouttext'                 => '<strong> شما الان در بوتت.</strong>

شما تونیت چه {{SITENAME}} ناشناس استفاده کنیت یا شما تونیت دگه وراد بیت گون دگه یا هما کاربر.
توجه بیت که لهتی صفحات شاید په داب هما وهدی که شما وراد بوتتیت پیش درگ بند تا وهدی که ذخیره بروزر وتی پاک کنیت.',
'welcomecreation'            => '== وش آتکی،$1! ==
شمی حساب شر بیت.
 مه شموشیت وتی {{SITENAME}} ترجیحات عوض کنیت',
'loginpagetitle'             => 'ورود کاربر',
'yourname'                   => 'نام کاربری',
'yourpassword'               => 'کلمه رمز',
'yourpasswordagain'          => 'کلمه رمز دگه نویس',
'remembermypassword'         => 'می ورود ته ای کامپیوتر په حاطر بدار',
'yourdomainname'             => 'شمی دامین',
'externaldberror'            => 'یک حطا دیتابیس تصدیق هویت دراییگی هست یا شما را اجازت نیست وتی حساب درایی په روچ کنیت.',
'loginproblem'               => '<b>یک مشکلی گون شمی ورود هستت.</b><br /> دگه جهد کن!',
'login'                      => 'ورود',
'nav-login-createaccount'    => 'ورود/شرکتن حساب',
'loginprompt'                => 'شما بایدن په وارد بیگ ته {{SITENAME}} کوکی فعال کنیت',
'userlogin'                  => 'ورود/شرکتن حساب',
'logout'                     => 'در بیگ',
'userlogout'                 => 'در بیگ',
'notloggedin'                => 'وارد نهت',
'nologin'                    => 'ورودی نیست؟$1.',
'nologinlink'                => 'شرکتن یک حساب',
'createaccount'              => 'حساب شرکن',
'gotaccount'                 => 'یک حساب الان هست؟$1.',
'gotaccountlink'             => 'ورود',
'createaccountmail'          => 'گون ایمیل',
'badretype'                  => 'کلماتی رمزی که شما وارد کتگیت یک نهنت.',
'userexists'                 => 'وارد بیتگیت نام کاربری الان استفاده بیت.
لطفا دگه دابین نامی بزوریت',
'youremail'                  => 'ایمیل:',
'username'                   => 'نام کاربری:',
'uid'                        => 'کاربر شناسگ:',
'prefs-memberingroups'       => 'عضو گروه {{PLURAL:$1|group|groups}}:',
'yourrealname'               => 'راستین  نام:',
'yourlanguage'               => 'زبان:',
'yourvariant'                => 'مغایر:',
'yournick'                   => 'امضا:',
'badsig'                     => 'نامعتبرین حامین امضا تگان HTML چک کن',
'badsiglength'               => 'امضا باز مزنتت.
آی بایدن چوشین  $1 {{PLURAL:$1|character|کاراکتران}}.',
'email'                      => 'ایمیل',
'prefs-help-realname'        => 'راستین  نام اهتیاریتن. اگه شما یکی انتخاب کنیت شی په شمی کارء نشان هلگ په روت.',
'loginerror'                 => 'حطا ورود',
'prefs-help-email'           => 'آدرس ایمیل اهتیارینت بله آیی هلیت دگرانا گون شما تماس بگرنت چه طریق شمی کاربر یا صفحه کاربر بی شی که شمی شناسایی ظاهر بیت.',
'prefs-help-email-required'  => 'آدرس ایمیل نیازنت.',
'nocookiesnew'               => 'حساب کاربر شر بوت بله شما وارد نه بیتگیت ته.
{{SITENAME}} چه کوکی په ورود کابران استفاده کنت.
شما کوکی غیر فعال کتت.
لطفا آییآ فعال کنیت رندا گون وتی نوکین نام کاربری و کلمه رمز وارد بیت.',
'nocookieslogin'             => '{{SITENAME}} په ورود کابران چه کوکی استفاده کنت.
شمی کوکی غیر فعالنت.
لطفا آییا فعال کنیت و دگه  سعی کنیت.',
'noname'                     => 'شما یک معتبرین نام کاربر مشخص نه کتت.',
'loginsuccesstitle'          => 'ورود موفقیت آمیز',
'loginsuccess'               => "''''شما الان وارد {{SITENAME}} په عنوان \"\$1\".'''",
'nosuchuser'                 => 'هچ کاربری گون نام "$1".
وتی املايا چک کنیت یا نوکین حسابی شرکنیت',
'nosuchusershort'            => 'هچ کاربری گون نام  "<nowiki>$1</nowiki>"نیستن.
وتی املايا کنترل کنیت',
'nouserspecified'            => 'شما باید یک نام کاربری مشخص کنیت.',
'wrongpassword'              => 'اشتباهین کلمه رمز وارد بوت. دگه سعی کن.',
'wrongpasswordempty'         => 'کلمه رمز وارد بیتگین هالیکنت. دگه سعی کن',
'passwordtooshort'           => 'شمی کلمه رمز نامعتبر یا باز هوردنت.
آیی بایدن حداقل $1 کاراکتر بیت و چه نام کاربری متفاوت بیت.',
'mailmypassword'             => 'کلمه رمز ایمیل کن',
'passwordremindertitle'      => 'نوکین هنگین کلمه رمز په {{SITENAME}}',
'passwordremindertext'       => 'یک نفری(شاید شما، چه آی پی $1)
لوٹتگی که ما شما را یک نوکین کلمه رمز دیم دهین په {{SITENAME}} ($4).
کلمه رمز په کاربر "$2" الان شینت"$3".
شما بایدن وارد بیت و وتی کلمه رمزآ بدل کنیت انو.

اگه دگه کسی په شما ای درخواست دیم داته و یا شما وتی کلمه رمزآ خاطر داریت و نه لوٹتیت آیآ عوض کنیت، شما تونیت این کوله یا شموشیت و گون هما قدیمی کلمه رمز ادامه دهیت',
'noemail'                    => 'هچ آدرس ایمیلی په کاربر "$1" ثبت نه بیتت.',
'passwordsent'               => 'یک نوکین کلمه رمزی په آدرس ایمیل ثبت بوتگین په "$1" دیم دهگ بیت.
لطفا بعد چه دریافت وارد بیت',
'blocked-mailpassword'       => 'شمی آدرس آی پی چه اصلاح کتن محدود بوتت و اجازت نداریت په خاطر جلوگیری چه سو استفاده چه عملگر کلمه رمز استفاده بکنت.',
'eauthentsent'               => 'یک ایمیل تاییدی په نامتگین آدرس ایمیل دیم دهگ بوت.
پیش چه هردابین ایمیلی په حساب دیم دیگ بین، شما بایدن چه دستور العملی که ته ایمیل آتکه پیروی کنیت په شی که شمی حساب که شمی گنت تایید بیت.',
'throttled-mailpassword'     => 'یک کلمه رمز یاد آوری پیش تر دیم دهگ بوتت ته  $1  ساعت پیش.
په جلوگرگ چه سو استفاده فقط یک کلمه رمز یاد آوری هر$1  ساعت دیم دهگ بیت.',
'mailerror'                  => 'حطا دیم دهگ ایمیل:$1',
'acct_creation_throttle_hit' => 'شرمنده! شما پیشتر $1  حسابی شر کتت.
شما نه تونیت گیشتر شرکنیت.',
'emailauthenticated'         => 'شمی آدرس ایمیل ته $1  تصدیق بوت.',
'emailnotauthenticated'      => 'په آدرس ایمیل هنگت تصدیق نه بوتت.
هچ ایمیلی په جهلیگین ویژگی دیم دهگ نه بیت.',
'noemailprefs'               => 'یک آدرس ایمیل په کار کتن ای ویژگیان مشخص کنیت.',
'emailconfirmlink'           => 'وتی آدرس ایمیل تایید کن',
'invalidemailaddress'        => 'آدرس ایمیل قبول نه بیت چوش که جاه کیت یک فرمت نامعتبری هست.
لطفا یک آدرس ایمیل هو-فرمتی وارد کنیت یا ای فیلد هالیک بلیت.',
'accountcreated'             => 'حساب شر بوت',
'accountcreatedtext'         => 'حساب کاربر په $1 شر بوت.',
'createaccount-title'        => 'شرکتن حساب په {{SITENAME}}',
'createaccount-text'         => 'یکی یک حساب په شمی آدرس ایمیل ته  {{SITENAME}} گون نام ($4)  "$2"، گون کلمه رمز "$3" شرکتت.
شما بایدن وارد بیت و وتی کلمه رمز الان عوض کنیت.

شما شاید ای پیام شموشیت اگه ای ای حساب گون حطا شر بوتت.',
'loginlanguagelabel'         => 'زبان: $1',

# Password reset dialog
'resetpass'               => 'عوض کتن کلمه رمز حساب',
'resetpass_announce'      => 'شما گون یک هنوکین کد ایمیل بوتگین وارد بوتءیت.
په تمام کتن ورود، شما باید یک نوکین کلمه رمز اداں شرکنیت',
'resetpass_text'          => '<!-- متن دان هورکن -->',
'resetpass_header'        => 'عوض کتن کلمه رمز',
'resetpass_submit'        => 'تنظیم کلمه رمز و ورود',
'resetpass_success'       => 'شمی کلمه رمز گون موفقیت عوض بون! هنو شما وارد بیگیت...',
'resetpass_bad_temporary' => 'نامعتبر هنوکین کلمه رمز.
شما شاید پیشتر وتی کلمه رمز آ عوض کتت یا یک نوکین هنوکین کلمه رمز لوٹتگیت.',
'resetpass_forbidden'     => 'کلمات رمز نه توننت ته {{SITENAME}} عوض بنت.',
'resetpass_missing'       => 'هچ فرم دیتا',

# Edit page toolbar
'bold_sample'     => 'پررنگین متن',
'bold_tip'        => 'پررنگین متن',
'italic_sample'   => 'ایتالیکی متن',
'italic_tip'      => 'ایتالیکی متن',
'link_sample'     => 'عنوان لینک',
'link_tip'        => 'لینک درونی',
'extlink_sample'  => 'http://www.example.com عنوان لینک',
'extlink_tip'     => 'حارجی لینک(مشموش  http:// پیشوند)',
'headline_sample' => 'متن سرخط',
'headline_tip'    => 'سطح ۲ سرخط',
'math_sample'     => 'فرمول اداں وارد کن',
'math_tip'        => 'فرمول ریاضی  (LaTeX)',
'nowiki_sample'   => 'متن بی فرمتءا ادان وارد کن',
'nowiki_tip'      => 'فرمت وی کی شموش',
'image_tip'       => 'فایل هورگین',
'media_tip'       => 'لینک فایل',
'sig_tip'         => 'شمی امضا گون مهر زمان',
'hr_tip'          => 'خط افقی',

# Edit pages
'summary'                   => 'خلاصه',
'subject'                   => 'موضوع/سرخط',
'minoredit'                 => 'ای شی یک هوردین اصلاحیت',
'watchthis'                 => 'ای صفحه بچار',
'savearticle'               => 'صفحه ذخیره کن',
'preview'                   => 'بازبین',
'showpreview'               => 'بازبین پیش دار',
'showlivepreview'           => 'بازبین زنده',
'showdiff'                  => 'تغییرات پیش دار',
'anoneditwarning'           => "'''Warning:''' شما وارد نه بیتگیت.
شمی آی پی ته تاریح اصلاح ای صفحه ثبت بیت.",
'missingsummary'            => "'''Reminder:''' شما یک خلاصه چه اصلاح وارد نه کرت.
اگر دگه کلیک کنیت ذخیره آ، شمی اصلاح به بی آی ذخیره بنت.",
'missingcommenttext'        => 'لطفا یک نظری وارد کنیت جهل آ',
'missingcommentheader'      => "'''Reminder:'' شما یک موضوع/سرخط په ای نظر وارد نکتت.
اگر شما دگه ذخیره کلیک کنیت، شمی اصلاح بی آی ذخیره بنت.",
'summary-preview'           => 'خلاصه بازبینی',
'subject-preview'           => 'بازبین موضوع/سرخط',
'blockedtitle'              => 'کاربر محدود بوتت',
'blockedtext'               => "<big> شمی نام کاربری یا آی پی محدود بیتت.''''</big>

محدودیت توسط $1 شر بوتت. دلیل داتت ''$2''.

*شروع محدودیت: $8*
*هلاسی محدودیت:$6
* لوٹتگین محدود بی یوک: $7

شما تونیت گون $1 یا دگه [[{{MediaWiki:Grouppage-sysop}}|administrator]] په بحث محدودیت باره تماس گیرت.
شما نه تونیت چه ویژگی'ایمیل ای کاربر' استفاده کنیت مگر شی که یم معتبرین آدرس ایمیلی مشخص بیت ته شمی  [[Special:Preferences|account preferences]] و شما چه استفاده ی آیی محدود نه بیت.
  $3 شمی هنوکین آی پی شی ان و شماره محدودیت شی ینت #$5. لطفا هر دو تا یا یکی په وهد سوال کتن هور کنیت.",
'autoblockedtext'           => 'شمی آی پی اتوماتیکی محدود بوت په چی که آی گون دگر کاربری استفاده بیگت که آیی محدود بوتت گون $1.
دلیل شی انت:

:\'\'$2\'\'

*شروع محدودیت:  $8
* *هلگ محدودیت: $6

شما شاید تماس گریت گون $1 یا یکی دگه چه [[{{MediaWiki:Grouppage-sysop}}|administrators]] په بحث درباره محدودیت.

توجه بیت شما شاید چه ویژگی "e-mail this user" مه تونیت استفاده کینت مگر شی که یک معتبرین آدرس ایمیل ته وتی [[Special:Preferences|user preferences] ثبت کنیت و شما چه استفاده چه آیی محدود نه بیت.

شمی شماره محدودیت $5.
لطفا ای شماره ته هر جوست و پرسی هور کنیت.',
'blockednoreason'           => 'هچ دلیلی دهگ نه بیته',
'blockedoriginalsource'     => "منبع '''$1''' جهلآ پیش دراگ بیت:",
'blockededitsource'         => "متن '''your edits'' به '''$1''' جهلآ پیش دارگ بیت:",
'whitelistedittitle'        => 'په اصلاح کتن ورود نیازنت',
'whitelistedittext'         => 'شما باید $1به اصلاح کتن صفحات.',
'whitelistreadtitle'        => 'ورود نیازنت به وانگ',
'whitelistreadtext'         => 'شما باید  [[Special:Userlogin|login]] به وانگ صفحات',
'whitelistacctitle'         => 'شما نه تونیت حسابی شرکنیت',
'whitelistacctext'          => 'به اجازت بیگ په شرکتن حسابان ته {{SITENAME}} شما باید [[Special:Userlogin|log]] ته و مناسبین اجازت داشته بیت.',
'confirmedittitle'          => 'به اصلاح کتن تایید ایمیل نیازنت',
'confirmedittext'           => 'شما بایدن وتی آدرس ایمیل آ پیش چه اصلاح کتن صفحات تایید کنیت.
لطفا وتی آدرس ایمیل آی چه طریق [[Special:Preferences|user preferences]] تنظیم و معتبر کنیت.',
'nosuchsectiontitle'        => 'هچ چوشن بخش',
'nosuchsectiontext'         => 'شما سعی کت یک بخشی اصلاح کنیت که نیستن.
چوش که هچ بخشی $1 نیست، هچ جاهی په ذخیره کتن شمی اصلاح نیست.',
'loginreqtitle'             => 'ورود نیازنت',
'loginreqlink'              => 'ورود',
'loginreqpagetext'          => 'شما باید $1 په گندگ دگه صفحات.',
'accmailtitle'              => 'کلمه رمز دیم دات',
'accmailtext'               => 'کلمه رمز په "$1"  دیم دهگ بوت په $2.',
'newarticle'                => '(نوکین)',
'newarticletext'            => "شما رند چه یک لینکی په یک صفحه ی که هنو نیستند اتکگیت.
په شر کتن صفحه، شروع کن نوشتن ته جعبه جهلی(بچار  [[{{MediaWiki:Helppage}}|help page]]  په گیشترین اطلاعات).
اگر شما اشتباهی ادانیت ته وتی بروزر دکمه ''Back'' بجن.",
'anontalkpagetext'          => "----'' ای صفحه بحث انت په یک ناشناس کاربری که هنگت یک حسابی شر نه کتت یا آی ا ستفاده نه کتت. اچه ما بایدن آدرس آی پی عددی په پچاه آرگ آیی استفاده کنین.
چوشن آدرس آی پی گون چندین کاربر استفاده بیت.
اگه شما یک کاربر ناشناس ایت وی حس کنیت بی ربطین نظر مربوط شمی هست، لطفا [[Special:Userlogin|create an account or log in]]  دان چه هور بییگ گون ناسناسین کاربران پرهیز بیت.''",
'noarticletext'             => 'هنو هچ متنی ته ای صفحه نیست، شما تونیت  [[Special:Search/{{PAGENAME}}|گردگ په عنوان صفحه]]  ته دگه صفحات یا [{{fullurl:{{FULLPAGENAME}}|action=edit}} ای صفحه اصلاح کن].',
'userpage-userdoesnotexist' => 'حساب کاربر "$1" ثبت نهنت. لطفا کنترل کنیت اگه شما لوٹیت ای صفحه یا شر/اصلاح کنیت.',
'clearyourcache'            => "'''Note:''' بعد چه ذخیره کتن، شما شاید مجبور بیت چه وتی ذخیره ی بروزر رد بیت تا تغییرات بگندیت. '''Mozilla / Firefox / Safari:'' ''Shift'' جهل داریت همی وهدی که کلیک کنیت ''Reload'' یا بداریت ''Ctrl-Shift-R'' (''Cmd-Shift-R'' on Apple Mac);'''IE:''' ''Ctrl''  بداری وهدی که کلیک ''Refresh' یا 'Ctrl-F5''; '''Konqueror:''':  راحت کلیک کن دکمه ''Reload'' یا بدار ''F5''; '''Opera''' کاربر بایدن ته ''Tools→Preferences'' ذخیره پاک کنت.",
'usercssjsyoucanpreview'    => "<strong>نکته:</strong> چه دکمه 'Show preview' په آزمایش کتن  CSS/JS پیش چه ذخیره کتن استفاده کن",
'usercsspreview'            => "''''په یاد دار که شما فقط وتی کاربری  CSS بازبینی کنگیت، هنگت ذخیره نه بوتت!''''",
'userjspreview'             => "''''په یاد دار که شما فقط وتی کاربری  JavaScript بازبینی/آزمایش کنگیت، هنگت ذخیره نه بوتت!''''",
'userinvalidcssjstitle'     => "'''Warning:''هچ جلدی نیست\"\$1\".
بزان که صفحات .css و .js چه عناوین گون هوردین حرف استفاده کننت، مثلا {{ns:user}}:Foo/monobook.css بدل به په {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(په روچ بیتگین)',
'note'                      => '<strong>یادداشت:</strong>',
'previewnote'               => '<strong>شی فقط یک بازبینی انت;
تغییرات هنگت ذخیره نهنت. </strong>',
'previewconflict'           => 'ای بازبین متنء پیش داریت ته منطفه بالدی اصلاحی هنچوش که پیش دارگ بیت اگه شما انتخاب کنیت ذخیره',
'session_fail_preview'      => '<strong>شرمنده! ما نه تونست شمی اصلاحء په خاطر گار کتن دیتا دیوان پردازش کنین.
طلف دگه سعی کنیت. اگر هنگت کار نکنت یک بری در بیت و پیدا وارد بیت.</strong>',
'session_fail_preview_html' => "<strong>شرمنده! ما نه تونست شمی اصلاحء په خاطر گار کتن دیتا دیوان پردازش کنین.</strong>

''په چی که {{SITENAME}} HTML هام فعالنت، بازبین په خاطر حملات JavaScript پناهنت.''

<strong> اگر شی یک قانونی تلاش اصلاحنت، دگه کوشش کنیت. اگر هنگت کار نکنت یک بری در بیت و دگه وارد بیت.</strong>",
'token_suffix_mismatch'     => '<strong> شمی اصلاح رد بوت په چی که شمی کلاینت نویسگ کاراکترانی په هم جتت.
اصلاح رد بوت داں چه هراب بیگ متن صفحه جلوگیری بیت.
شی لهتی وهد پیش کت که شما چه یک هرابین سرویس پروکسی وبی استفاده کنیت.</strong>',
'editing'                   => 'اصلاح $1',
'editingsection'            => 'اصلاح $1(بخش)',
'editingcomment'            => 'اصلاح $1 (نظر)',
'editconflict'              => 'جنگ ورگ اصلاح: $1',
'explainconflict'           => "کسی دگه ای صفحه یا عوض کتت چه وهدی که شما اصلاح آیء شروع کتء.
بالادی ناحیه متن شامل متن صفحه همی داب که هنگت هست.
شمی تغییرات ته جهلیگین ناحیه متن جاه کیت.
شما بایدن وتی تغییرات آن گون هنوکین متن چن و بند کنیت.
'''فقط''' ناحیه بالادی متن وهدی که شما دکمه  \"Save page\" ذخیره بنت.",
'yourtext'                  => 'شمی متن',
'storedversion'             => 'نسخه ی ذخیره ای',
'nonunicodebrowser'         => '<strong>هوژاری: شمی بروزر گون یونی کد تنظیم کار نکنت. یک اطراف-کار جاهینن که شما را اجازه دنت صفحات راحت اصلاح کنیت: non-ASCII کاراتران ته جعبه اصلاح په داب کدان hexadecimal جاه کاینت.',
'editingold'                => '<strong>هوژاری: شما په اصلاح کتن یک قدیمی بازبینی چه ای صفحه ایت.
اگر شما ایء ذخیره کتت، هر تغییری که دهگ بیتء چه ای بازبینی گار بنت.</strong>',
'yourdiff'                  => 'تفاوتان',
'copyrightwarning'          => 'لطفا توجه بیت که کل نوشته یات ته {{SITENAME}}  تحت $2 نشر بنت.(بچار په جزیات$1).
اگه شما لوٹیت شمی نوشتانک اصلاح و دگه چهاپ مبنت، اچه آیانا ادان مهلیت.<b/>
شما ما را قول دهیت که وتی چیزا بنویسیت یا چه یک دامین عمومی کپی کتگیت.
<strong> نوشتانکی که کپی رایت دارند بی اجازه ادا هور مکنیت</strong>',
'longpagewarning'           => '<strong>هوژاری. ای صفحه $1 کیلوبایت نت;
لهتی چه بروزران شاید مشکلاتی چه دست رسی و اصلاح صفحات گیش چه 32ک.ب داشته بنت.
لطفا توجه کنیت په هورد کتن صفحه په هوردترین چنٹ. </strong>',
'templatesused'             => 'تمپلتانی که ته ای صفحه استفاده بیت:',
'templatesusedpreview'      => 'تلمپلت آنی که ته ای بازبینی استفاده بیت',
'template-protected'        => '(محافظتین)',
'template-semiprotected'    => '(نیم محافظتی)',
'nocreatetext'              => '{{SITENAME}} شما را چه شرکتن نوکین صفحه منه کته.
شما تونیت برگردیت و یک پیشگین صفحه ای اصلاح کنیت، یا [[Special:Userlogin|وارد بیت یا حسابی شرکنیت]].',
'recreate-deleted-warn'     => "'''هوژاری: شما یک صفحه ای دگه شرکینت که پیشتر حذف بوتت.'''
شما بایدن توجه کنیت که آیا اصلاح ای صفحه مناسبنت. حذف ورودان په ای صفحه په شمی چارگ  ادان آرگ بیتت:",

# History pages
'viewpagelogs'        => 'ورودان ای صفحه بچار',
'currentrev'          => 'هنوکین بازبینی',
'revisionasof'        => 'بازبینی په عنوان $1',
'revision-info'       => 'بازبینی په داب $1 توسط $2',
'previousrevision'    => '←پیش ترین نسخه',
'nextrevision'        => 'نوکین بازبینی→',
'currentrevisionlink' => 'هنوکین بازبینی',
'cur'                 => 'ترینگ',
'last'                => 'اهری',
'page_first'          => 'اولین',
'page_last'           => 'اهرین',
'histlegend'          => 'بخش تفاوت: په مقایسه کتن نسخه یان گزینه انتخاب کنیت اینتر یا دکمه بجن.<br />
شرح: (هنو) = تفاوت گون هنوکین نسخه,
(آهری) = تفاوت گون بعدی نسخه, جز = هوردین اصلاح.',
'histfirst'           => 'اولین',
'histlast'            => 'اهرین',

# Revision feed
'history-feed-item-nocomment' => '$1 ته $2', # user at time

# Diffs
'history-title'           => 'تاریح بازبینی "$1"',
'difference'              => '(تفاوتان بین نسخه یان)',
'lineno'                  => 'خط$1:',
'compareselectedversions' => 'مقایسه انتخاب بوتگین نسخه یان',
'editundo'                => 'خنثی کتن',
'diff-multi'              => '({{PLURAL:$1|یک ویرایش میانی|$1 }}پیش دارگ میانی نه بوتت.)',

# Search results
'noexactmatch' => "'''صفحه ی گون عنوان نیست\"\$1\".'''
شما تونیت [[:\$1|ای صفحه ی شرکنیت]].",
'prevn'        => 'پیشگین $1',
'nextn'        => 'بعدی $1',
'viewprevnext' => '($1) ($2) ($3) دیدگ',
'powersearch'  => 'پیشرپتگی گردگ',

# Preferences page
'preferences'   => 'ترجیحات',
'mypreferences' => 'منی ترجیحات',
'retypenew'     => 'کلمه رمز دگه بنویس',

'grouppage-sysop' => '{{ns:project}}:مدیران',

# User rights log
'rightslog' => 'ورودان حقوق کاربر',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|تغییر|تغییرات}}',
'recentchanges'                  => 'نوکین تغییرات',
'recentchanges-feed-description' => 'آهرین تغییرات ته وی کی چه ای فید رند گر',
'rcnote'                         => "جهل {{PLURAL:$1|هست '''1''' تغییر|هست آهرین '''$1''' تغییرات}} ته آهرین {{PLURAL:$2|روچ|'''$2''' days}}, په داب $3.",
'rcnotefrom'                     => "جهلا تغییرات هستنت چه '''$2''' (دان '''$1''' پیش دارگنت).",
'rclistfrom'                     => 'پیش دار نوکین تغییراتآ چه $1',
'rcshowhideminor'                => '$1 هوردین تغییرات',
'rcshowhidebots'                 => '$1 روباتان',
'rcshowhideliu'                  => '$1 کاربران وارد بوتگین',
'rcshowhideanons'                => '$1 نا شناسین کاربران',
'rcshowhidepatr'                 => '$1 اصلاحات کنترل بیتگین',
'rcshowhidemine'                 => '$1 اصلاحات من',
'rclinks'                        => 'پیش دار آهرین$1 تغییرات ته آهرین $2 روچان<br />$3',
'diff'                           => 'تفاوت',
'hist'                           => 'تاریخ',
'hide'                           => 'پناه',
'show'                           => 'پیش دراگ',
'minoreditletter'                => 'م',
'newpageletter'                  => 'ن',
'boteditletter'                  => 'ب',

# Recent changes linked
'recentchangeslinked'          => 'مربوطین تغییرات',
'recentchangeslinked-title'    => 'تغییراتی مربوط په "$1"',
'recentchangeslinked-noresult' => 'هچ تغییری ته صفحات لینک بوتگین ته داتگین دوره نیست',
'recentchangeslinked-summary'  => "شی یک لیستی چه تغییراتی هستنت که نوکی اعمال بوتگنت په صفحاتی که چه یک صفحه خاصی لینک بوته( یا په اعضای یک خاصین دسته).
صفحات ته [[Special:Watchlist|شمی لیست چارک]] '''پررنگ''' بنت",

# Upload
'upload'        => 'آپلود کتن فایل',
'uploadbtn'     => 'آپلود فایل',
'uploadlogpage' => 'آپلود ورودان',
'uploadedimage' => 'اپلود بوت "[[$1]]"',

# Special:Imagelist
'imagelist' => 'لیست فایل',

# Image description page
'filehist'                  => 'تاریح فایل',
'filehist-help'             => 'اور تاریح/زمان کلیک کنیت دان فایلا په داب هما تاریح بگندیت',
'filehist-current'          => 'هنو',
'filehist-datetime'         => 'تاریح/زمان',
'filehist-user'             => 'کاربر',
'filehist-dimensions'       => 'جنبه یان',
'filehist-filesize'         => 'اندازه فایل',
'filehist-comment'          => 'نظر',
'imagelinks'                => 'لینکان',
'linkstoimage'              => 'جهلیگی {{PLURAL:$1|لینکان صفحه|$1 لینک صفحات}} په ای فایل:',
'nolinkstoimage'            => 'هچ صفحه ای نیست که به ای فایل لینک بوت.',
'sharedupload'              => 'ای فایل یک مشترکین آپلودی فایلیت و شاید گون دگه پروژه یان استفاده بیت.',
'noimage'                   => 'چشین فایل گون ای نام نیست، شما تونیت $1',
'noimage-linktext'          => 'آپلود کن',
'uploadnewversion-linktext' => 'یک نوکین نسخه ای چه ای فایل آپلود کن',

# MIME search
'mimesearch' => 'گردگ MIME',

# List redirects
'listredirects' => 'لیست غیر مستقیمان',

# Unused templates
'unusedtemplates' => 'تمپلتان بی استفاده',

# Random page
'randompage' => 'تصادفی صفحه',

# Random redirect
'randomredirect' => 'تصادفی غیر مستقیم',

# Statistics
'statistics' => 'آمار',

'disambiguations' => 'صفحات رفع ابهام',

'doubleredirects' => 'دوبل غیر مستقیم',

'brokenredirects' => 'پروشتگین غیر مستقیمان',

'withoutinterwiki' => 'صفحاتی بی لینکان زبان',

'fewestrevisions' => 'صفحات گون کمترین بازبینی',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|لینک}}',
'nmembers'                => '$1 {{PLURAL:$1|member|اعضا}}',
'lonelypages'             => 'صفحات یتیم',
'uncategorizedpages'      => 'صفحات بی دسته',
'uncategorizedcategories' => 'دسته جات دسته بندی نه بوتگین',
'uncategorizedimages'     => 'فایلان بی دسته',
'uncategorizedtemplates'  => 'تمپلتان بی دسته',
'unusedcategories'        => 'بی استفاده این دسته جات',
'unusedimages'            => 'بی استفاده این فایلان',
'wantedcategories'        => 'لوٹتگین دسته جات',
'wantedpages'             => 'لوٹتگین صفحات',
'mostlinked'              => 'صفحاتی که گیشنر لینک دیگ بیتگنت',
'mostlinkedcategories'    => 'دسته جاتی که گیشتر لینک دیگ بیتگنت',
'mostlinkedtemplates'     => 'تمپلتانی که گیشتر لینک بیتگنت',
'mostcategories'          => 'صفحات گون گیشترین دسته جات',
'mostimages'              => 'فایلان گیشنر لینک بوتیگن',
'mostrevisions'           => 'صفحاتی گون گیشترین بازبینی',
'prefixindex'             => 'اندیکس پیش وند',
'shortpages'              => 'هوردین صفحه',
'longpages'               => 'صفحات مزنین',
'deadendpages'            => 'مردتیگ صفحات',
'protectedpages'          => 'صفحات حفاظت بیتگین',
'listusers'               => 'لیست کاربر',
'newpages'                => 'نوکین صفحات',
'ancientpages'            => 'صفحات قدیمی',
'move'                    => 'جاه په جاه',
'movethispage'            => 'ای صفحه جاه په جاه کن',

# Book sources
'booksources' => 'منابع کتاب',

# Special:Log
'specialloguserlabel'  => 'کاربر:',
'speciallogtitlelabel' => 'عنوان:',
'log'                  => 'ورودان',
'all-logs-page'        => 'کل ورودان',

# Special:Allpages
'allpages'       => 'کل صفحات',
'alphaindexline' => '$1 په $2',
'nextpage'       => 'صفحه ی بعدی ($1)',
'prevpage'       => ' ($1)پیشگین صفحه',
'allpagesfrom'   => 'پیش در صفحات شروع بنت ته:',
'allarticles'    => 'کل صفحات',
'allpagessubmit' => 'برو',
'allpagesprefix' => 'صفحات پیش دار گون پیشوند:',

# Special:Categories
'categories' => 'دسته یان',

# E-mail user
'emailuser' => 'په ای کابر ایمیل دیم دی',

# Watchlist
'watchlist'            => 'منی لیست چارگ',
'mywatchlist'          => 'منی لیست چارگ',
'watchlistfor'         => "(په '''$1''')",
'addedwatch'           => 'په لیست چارگ هور بوت',
'addedwatchtext'       => 'صفحه  "[[:$1]]"  په شمی [[Special:Watchlist|watchlist]] هور بیت.
دیمگی تغییرات په ای صفحه و آیاء صفحه گپ ادان لیست بنت، و صفحه پررنگ جاه کیت ته [[Special:Recentchanges|لیست نوکیت تغییرات]] په راحتر کتن شی که آی زورگ بیت.',
'removedwatch'         => 'چه لیست چارگ زورگ بیت',
'removedwatchtext'     => 'صفحه"[[:$1]]"  چه شمی لیست چارگ دربیت.',
'watch'                => 'به چار',
'watchthispage'        => 'ای صفحه ی بچار',
'unwatch'              => 'نه چارگ',
'watchlist-details'    => '{{PLURAL:$1|$1 page|$1 pages}} چارتگ بیت صفحات گپ حساب نه بیگن',
'wlshowlast'           => 'پیش دار آهرین $1  ساعات $2 روچان $3',
'watchlist-hide-bots'  => 'اصلاحات بت پناه کن',
'watchlist-hide-own'   => 'منی اصلاحات آ پناه کن',
'watchlist-hide-minor' => 'هوردین تغییرات پناه کن',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'چارگ بین',
'unwatching' => 'نه چارگ بیت',

# Delete/protect/revert
'deletepage'                  => 'حذف صفحه',
'historywarning'              => 'هوژاری: صفحه ای که شما لوٹتیت آیآ حذف کنیت یک تاریحی داریت:',
'confirmdeletetext'           => 'شما لوٹیت یک صفحه ای گون کل تاریحانی حذف کنیت.
لطفا تایید کنیت که شما چوش کنیت که شما زانیت آی ء عاقبتانآ و شی که شما ای کارآ گون [[{{MediaWiki:Policy-url}}|سیاست]] انجام دهیت',
'actioncomplete'              => 'کار انجام بیت',
'deletedtext'                 => '"<nowiki>$1</nowiki>" حذف بیت.
بگندیت $2 په ثبتی که نوکین حذفیات',
'deletedarticle'              => 'حذف بوت "[[$1]]"',
'dellogpage'                  => 'حذف ورودان',
'deletecomment'               => 'دلیل حذف:',
'deleteotherreason'           => 'دگه/گیشترین دلیل:',
'deletereasonotherlist'       => 'دگه دلیل',
'rollbacklink'                => 'عقب ترگ',
'protectlogpage'              => 'ورودان حفاظت',
'protectcomment'              => 'نظر:',
'protectexpiry'               => 'منقضی بیت:',
'protect_expiry_invalid'      => 'تاریح انقضای معتبر نهنت.',
'protect_expiry_old'          => 'تاریخ انقصا ته گذشته انت.',
'protect-unchain'             => 'اجازه یان جاه په جاهی پچ کن',
'protect-text'                => 'شما شاید ادان سطح حفاظت بگندیت و تغییر دیهت په صفحه <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'شمی حساب اجازه نداریت سطوح حفاظت صفحه ی عوض کنت.
ادان هنوکین تنظیمات هست په صفحه <strong>$1</strong>:',
'protect-cascadeon'           => 'ای صفحه الان محافظت بیت چوش که آی شامل جهلی {{PLURAL:$1|صفحات| درانت  که }} حفاظت آبشار روشن.
شما تونیت ای صفحه ی سطح حفاظت آ عوص کنیت، بله آی ء حفاظت آبشاریء تاثیر نهلیت.',
'protect-default'             => '(پیش فرض)',
'protect-fallback'            => 'اجازه "$1" لازم داریت',
'protect-level-autoconfirmed' => 'کابران ثبت نام نه بوتگینآ محدود کن',
'protect-level-sysop'         => 'فقط کاربران سیستمی',
'protect-summary-cascade'     => 'آبشاری',
'protect-expiring'            => 'منقضی بوت $1 (UTC)',
'protect-cascade'             => 'حفاظت کن صفحاتی په داب ای صفحه (محافظت آبشاری)',
'protect-cantedit'            => 'شما نه تونیت سطح حمایت ای صفحه یا عوض کنیت، چون شما اجازه اصلاح کتن نیست',
'restriction-type'            => 'اجازت',
'restriction-level'           => 'سطح محدود',

# Undelete
'undeletebtn' => 'باز گردینگ',

# Namespace form on various pages
'namespace'      => 'فاصله نام',
'invert'         => 'برگردینگ انتخاب',
'blanknamespace' => '(اصلی)',

# Contributions
'contributions' => 'مشارکتان کاربر',
'mycontris'     => 'می مشارکتان',
'contribsub2'   => 'په $1 ($2)',
'uctop'         => '(بالا)',
'month'         => 'چه ماه(و پیش تر):',
'year'          => 'چه سال(و پیشتر)',

'sp-contributions-newbies-sub' => 'په نوکین حسابان',
'sp-contributions-blocklog'    => 'محدود کتن ورود',

# What links here
'whatlinkshere'       => 'ای لینکی که ادا هست',
'whatlinkshere-title' => 'صفحاتی که لینگ بوتگنت په $1',
'linklistsub'         => '(لیست کل لینکان)',
'linkshere'           => "جهلیگی صفحات لینک بوت '''[[:$1]]''':",
'nolinkshere'         => "هچ لینک صفحه ای په '''[[:$1]]'''.",
'isredirect'          => 'صفحه غیر مستقیم',
'istemplate'          => 'همراهی',
'whatlinkshere-prev'  => '{{PLURAL:$1|فبلی|قبلی $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|بعدی|بعدی $1}}',
'whatlinkshere-links' => '← لینکان',

# Block/unblock
'blockip'       => 'محدود کتن کاربر',
'ipboptions'    => '2 ساعت: 2 ساعت، 1 روچ: 1 روچ، 3 روچ: 3 روچ، 1 هفته: 1 هفته، 2 هفته: 2هفته، 1 ماه: 1 ماه: 2ماه، 3 ماه: 3 ماه، 6 ماه: 6 ماه، 1 سال: 1 سال، بی حد: بی حد', # display1:time1,display2:time2,...
'ipblocklist'   => 'لیست محدود بیتگین آی پی و نام کاربران',
'blocklink'     => 'محدود',
'unblocklink'   => 'رفع محدودیت',
'contribslink'  => 'مشارکتان',
'blocklogpage'  => 'بلاک ورود',
'blocklogentry' => 'محدود بوته [[$1]] گون یک زمان انقاضای $2 $3',

# Move page
'movepagetext'     => "استفاده چه جهلگی فرم یک صفحه ای نامی آ بدل کنت، کل تاریح آیآ په نوکین نام جاه په جاه کنت.
گهنگین عنوان یک صفحه غیر مستقیمی په نوکین عنوان بیت.
لینکان په کهنگین عوض نبنت;
مطمین بیت په خاطر دوتای یا پرشتگین غیرمستقیمین.
شما مسولیت که مطمین بیت که لینکان ادامه دهنت روگ په جاهی که قرار برونت.

توجه کینت صفحه جاه په جاه نه بیت اگه یک صفحه ای گون نوکین عنوان هست، مگر شی که آی هالیک بیت یا یک غیرمسقیم و پی سرین تاریح اصلاح می بیت. شی په ای معنی اینت که شما تونیت یک صفحه ای آ نامی بدل کینت که  آی نام په خطا عوض بیت و شما نه توینت یک صفحه ی نامی بازنویسی کنیت.

''''هوژاری!''''  
شی ممکننت یک تغییر آنی و نه لوٹتگین په یک معروفین صفحه ای بیت;
لصفا مطمین بیت شما عواقب شی زانیت پیش چه دیم روگآ",
'movepagetalktext' => "همراهی گپان صفحه اتوماتیک گون آی جاه په چاه بنت ''''مگر:''''
یک ناهالیکین صفحه گپی چیر آی ء نوکین نام بیت، یا
شما جهلیگین باکس آ تیک مجنیت.
ته ای موراد شما بایدن صفحه یا دسته جاه په جاه کنی و یا آیآ چن و بند کینت.",
'movearticle'      => 'جاه په چاهی صفحه:',
'newtitle'         => 'په نوکین عنوان:',
'move-watch'       => 'این صفحه یا بچار',
'movepagebtn'      => 'جاه په جاه کن صفحه',
'pagemovedsub'     => 'جاه په جاهی موفقیت بود',
'movepage-moved'   => '<big>\'\'\'"$1" جاه په اجه بوت په"$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'صفحه گون آن نام پیش تر هستت، یا نامی که شما زورتت نامعتبرنت.
یک دگه نامی بزوریت.',
'talkexists'       => "''''صفحه وتی گون موفقیت جاه په جاه بوت، بله صفحه گپ نه نویت جاه  په جاه بیت چون که یکی ته نوکین عنوان هست.
لطفا آیآ دستی چند و بند کنیت.''''",
'movedto'          => 'جاه په جاه بیت په',
'movetalk'         => 'جاه په جاه کتن صفحه کپ همراه',
'1movedto2'        => '[[$1]] چاه په چاه بوت په [[$2]]',
'movelogpage'      => 'جاه په جاهی ورود',
'movereason'       => 'دلیل:',
'revertmove'       => 'برگردینگ',

# Export
'export' => 'خروج صفحات',

# Namespace 8 related
'allmessages' => 'پیامان سیستم',

# Thumbnails
'thumbnail-more'  => 'مزن',
'thumbnail_error' => 'خطا ته شرکتن هوردوکین$1',

# Import log
'importlogpage' => 'ورودان وارد کن',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'صفحه کاربری من',
'tooltip-pt-mytalk'               => 'صفحه گپ من',
'tooltip-pt-preferences'          => 'منی ترجیحات',
'tooltip-pt-watchlist'            => 'لیست صفحیانی که شما تغییرات آیانا رند گرگیت',
'tooltip-pt-mycontris'            => 'لیست منی مشارکتان',
'tooltip-pt-login'                => 'شر ترنت که وارد بیت، بله شی اجبار نهنت',
'tooltip-pt-logout'               => 'در بیگ',
'tooltip-ca-talk'                 => 'بحث دباره محتوای صفحه',
'tooltip-ca-edit'                 => 'شما تونیت ای صفحه یا اصلاح کنیت. لطفا چه بازبین دکمه پیش چه ذخیره کتن استفاده کنیت.',
'tooltip-ca-addsection'           => 'په ای بحث یک نظر هور کن',
'tooltip-ca-viewsource'           => 'ای صفحه محافظت بوتت. شما تونیت آیی منبع آ بچاریت',
'tooltip-ca-protect'              => 'ای صفحه یا حفاظت کن',
'tooltip-ca-delete'               => 'ای صفحه حذف کن',
'tooltip-ca-move'                 => 'ای صفحه یا جاه په جاه کن',
'tooltip-ca-watch'                => 'ای صفحه یا ته شمی لیست چارگ هور کنت',
'tooltip-ca-unwatch'              => 'ای صفحه یا چه وتی لیست چارگ در کن',
'tooltip-search'                  => 'گردگ {{SITENAME}}',
'tooltip-n-mainpage'              => 'صفحه اصلی بچار',
'tooltip-n-portal'                => 'پروژه ی باره: هرچی که شما تونیت انجام دهیت، جاهی که چیزانا درگیزیت',
'tooltip-n-currentevents'         => 'در گیزگ اطلاعات پیش زمینه ته هنوکین رویدادآن',
'tooltip-n-recentchanges'         => 'لیست نوکین تغییر ته وی کی',
'tooltip-n-randompage'            => 'یک شانسی صفحه پچ کن',
'tooltip-n-help'                  => 'جاهی په زانگ',
'tooltip-n-sitesupport'           => 'ما را حمایت کنیت',
'tooltip-t-whatlinkshere'         => 'لیست کل صفحات وی کی که ادان لینک بوتگنت',
'tooltip-t-contributions'         => 'لیست مشارکتان ای کاربر بچار',
'tooltip-t-emailuser'             => 'په ای کاربر یک ایمیل دیم دی',
'tooltip-t-upload'                => 'آپلود فایلان',
'tooltip-t-specialpages'          => 'لیست کل حصاین صفحات',
'tooltip-ca-nstab-user'           => 'چارگ صفحه کاربر',
'tooltip-ca-nstab-project'        => 'بچار صفحه پروژه یا',
'tooltip-ca-nstab-image'          => 'صفحه فایل بگند',
'tooltip-ca-nstab-template'       => 'چارگ تمپلت',
'tooltip-ca-nstab-help'           => 'صفحه کمک بچار',
'tooltip-ca-nstab-category'       => 'دسته صفحه ی بچار',
'tooltip-minoredit'               => 'شی آ په داب یک اصلاح جزی نشان بل',
'tooltip-save'                    => 'وتی تغییرات ذخیره کن',
'tooltip-preview'                 => 'بازبین کن وتی تغییراتا، لطفا پیش چه ذخیره کتن شیا استفاده کن.',
'tooltip-diff'                    => 'پیش دار تغییراتی که شما په نوشته دات.',
'tooltip-compareselectedversions' => 'بچار تفاوتان بین دو انتخاب بوتگین نسخه یان این صفحه',
'tooltip-watch'                   => 'ای صفحه یانا ته وتی لیست چارگ هور کن',

# Browsing diffs
'previousdiff' => '← پیشگین تفاوت',
'nextdiff'     => 'تفاوت بعدی→',

# Media information
'file-info-size'       => '($1 × $2 پیکسل, اندازه فایل: $3, نوع مایم: $4)',
'file-nohires'         => '<small>مزنترین رزلوشن نیست.</small>',
'svg-long-desc'        => '(اس وی جی فایل, گون $1 × $2 پیکسل, اندازه فایل: $3)',
'show-big-image'       => 'کل صفحه',
'show-big-image-thumb' => '<small>اندازه ای بازبین:$1× $2 pixels</small>',

# Special:Newimages
'newimages' => 'گالری نوکین فایلان',

# Bad image list
'bad_image_list' => 'فرمت په داب جهلیگی انت:

فقط ایتمان لیست چارگ بنت(خطانی که گون * شروع بنت).
اولین لینک ته یک خط باید یک لینکی په یک بدین فایلی بیت.
هر لینکی که کیت ته هما خط اسنثتا بینت.',

# Metadata
'metadata'          => 'متا دیتا',
'metadata-help'     => 'ای فایل شامل مزیدین اطلاعاتنیت، شاید چه یک دوربین یا اسکنر په شرکتن و دیجیتالی کتن هور بیتت.
اگه فایل چه اولیگین حالتی تغییر داته بوته شاید لهتی کل جزییات شر پیش مداریت.',
'metadata-expand'   => 'پیش دار گیشترین جزییات',
'metadata-collapse' => 'پناه کن مزیدین جزییاتا',
'metadata-fields'   => 'EXIF فیلدان متادیتا ی که ته ای کوله لیست بوتگینت شامل تصویر وهدی پیش دارگ بنت که جدول هراب بیت. دگه په طور پیش فرض پناهنت.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'ای صفحه یا اصلاح کن گون یک درآین برنامه ای',
'edit-externally-help' => 'په گیشترین اطلاعات بچار[http://meta.wikimedia.org/wiki/Help:External_editors setup instructions]',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'کل',
'namespacesall' => 'کل',
'monthsall'     => 'کل',

# Watchlist editing tools
'watchlisttools-view' => 'مربوطین تغییرات بچار',
'watchlisttools-edit' => 'به چار و اصلاح کن لیست چارگ آ',
'watchlisttools-raw'  => 'هامین لیست چارگ آ اصلاح کن',

# Special:Version
'version' => 'نسخه', # Not used as normal message but as header for the special page itself

# Special:Filepath
'filepath-summary' => 'ای حاصین صفحه مسیر کامل په یک فایل پیش داریت.
تصاویر گون وضوح کامل پیش دارگ بنت و دگه نوع فایلان گون وتی برنامه یانش مستقیما پچ بنت.

نام فایل بی پسوند "{{ns:image}}:" وارد کن',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'گردگ په کپی  فایلان',
'fileduplicatesearch-summary'  => 'گردگ په کپی فایلان په اساس درهمین ارزش.

نا فایل بی پیش وند "{{ns:image}}:" وارد کنیت',
'fileduplicatesearch-filename' => 'نام فایل',
'fileduplicatesearch-submit'   => 'گردگ',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />اندازه فایل: $3<br />MIME type: $4',
'fileduplicatesearch-result-n' => 'فایل "$1" has {{PLURAL:$2|1 identical duplication|$2 مشخصین کپی بوتن}}.',

# Special:SpecialPages
'specialpages'                   => 'حاصین صفحات',
'specialpages-group-maintenance' => 'گزارشات دارگ',
'specialpages-group-other'       => 'دگر حاصین صفحات',
'specialpages-group-login'       => 'ورود/ثبت نام',
'specialpages-group-changes'     => 'نوکین تغییرات و ورودان',
'specialpages-group-media'       => 'گزارشات مدیا و آپلودان',
'specialpages-group-users'       => 'کابران و حقوق',
'specialpages-group-highuse'     => 'کاربرد بالای صفحات',
'specialpages-group-pages'       => 'لیست صفحات',
'specialpages-group-pagetools'   => 'وسایل صفحه',
'specialpages-group-wiki'        => 'وسایل و دیتا وی کی',
'specialpages-group-redirects'   => 'غیر مستقیم بیگنت صفحات حاصین',
'specialpages-group-spam'        => 'وسایل اسپم',

);
