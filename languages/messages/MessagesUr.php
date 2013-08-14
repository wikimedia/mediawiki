<?php
/** Urdu (اردو)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Chris H
 * @author Istabani
 * @author Meno25
 * @author Muhammad Shuaib
 * @author Noor2020
 * @author O.bangash
 * @author Rachitrali
 * @author Reedy
 * @author Tahir mq
 * @author Wisesabre
 * @author ZxxZxxZ
 * @author לערי ריינהארט
 * @author زكريا
 * @author سمرقندی
 * @author محبوب عالم
 */

$fallback8bitEncoding = 'windows-1256';
$rtl = true;

$namespaceNames = array(
	NS_MEDIA            => 'وسیط',
	NS_SPECIAL          => 'خاص',
	NS_MAIN             => '',
	NS_TALK             => 'تبادلۂ_خیال',
	NS_USER             => 'صارف',
	NS_USER_TALK        => 'تبادلۂ_خیال_صارف',
	NS_PROJECT_TALK     => 'تبادلۂ_خیال_$1',
	NS_FILE             => 'ملف',
	NS_FILE_TALK        => 'تبادلۂ_خیال_ملف',
	NS_MEDIAWIKI        => 'میڈیاویکی',
	NS_MEDIAWIKI_TALK   => 'تبادلۂ_خیال_میڈیاویکی',
	NS_TEMPLATE         => 'سانچہ',
	NS_TEMPLATE_TALK    => 'تبادلۂ_خیال_سانچہ',
	NS_HELP             => 'معاونت',
	NS_HELP_TALK        => 'تبادلۂ_خیال_معاونت',
	NS_CATEGORY         => 'زمرہ',
	NS_CATEGORY_TALK    => 'تبادلۂ_خیال_زمرہ',
);

$namespaceAliases = array(
	'زریعہ'            => NS_MEDIA,
	'تصویر'            => NS_FILE,
	'تبادلۂ_خیال_تصویر'   => NS_FILE_TALK,
	'میڈیاوکی'          => NS_MEDIAWIKI,
	'تبادلۂ_خیال_میڈیاوکی' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'متحرک_صارفین' ),
	'Allmessages'               => array( 'تمام_پیغامات' ),
	'Allpages'                  => array( 'تمام_صفحات' ),
	'Ancientpages'              => array( 'قدیم_صفحات' ),
	'Badtitle'                  => array( 'خراب_عنوان' ),
	'Blankpage'                 => array( 'خالی_صفحہ' ),
	'Block'                     => array( 'پابندی', 'دستور_شبکی_پابندی', 'پابندی_بر_صارف' ),
	'Blockme'                   => array( 'میری_پابندی' ),
	'Booksources'               => array( 'کتابی_وسائل' ),
	'BrokenRedirects'           => array( 'شکستہ_رجوع_مکررات' ),
	'Categories'                => array( 'زمرہ_جات' ),
	'ChangeEmail'               => array( 'ڈاک_تبدیل' ),
	'ChangePassword'            => array( 'کلمہ_شناخت_تبدیل', 'تنظیم_کلمہ_شناخت' ),
	'ComparePages'              => array( 'موازنہ_صفحات' ),
	'Confirmemail'              => array( 'تصدیق_ڈاک' ),
	'Contributions'             => array( 'شراکتیں' ),
	'CreateAccount'             => array( 'تخلیق_کھاتہ' ),
	'Deadendpages'              => array( 'مردہ_صفحات' ),
	'DeletedContributions'      => array( 'حذف_شدہ_شراکتیں' ),
	'Disambiguations'           => array( 'ضد_ابہام_صفحات' ),
	'DoubleRedirects'           => array( 'دوہرے_رجوع_مکررات' ),
	'EditWatchlist'             => array( 'ترمیم_زیر_نظر' ),
	'Emailuser'                 => array( 'صارف_ڈاک' ),
	'Export'                    => array( 'برآمدگی' ),
	'Fewestrevisions'           => array( 'کم_نظر_ثانی_شدہ' ),
	'FileDuplicateSearch'       => array( 'دہری_ملف_تلاش' ),
	'Filepath'                  => array( 'راہ_ملف' ),
	'Import'                    => array( 'درآمدگی' ),
	'Invalidateemail'           => array( 'ڈاک_تصدیق_منسوخ' ),
	'JavaScriptTest'            => array( 'تجربہ_جاوا_اسکرپٹ' ),
	'BlockList'                 => array( 'فہرست_ممنوع', 'فہرست_دستور_شبکی_ممنوع' ),
	'LinkSearch'                => array( 'تلاش_روابط' ),
	'Listadmins'                => array( 'فہرست_منتظمین' ),
	'Listbots'                  => array( 'فہرست_روبہ_جات' ),
	'Listfiles'                 => array( 'فہرست_املاف', 'فہرست_تصاویر' ),
	'Listgrouprights'           => array( 'فہرست_اختیارات_گروہ', 'صارفی_گروہ_اختیارات' ),
	'Listredirects'             => array( 'فہرست_رجوع_مکررات' ),
	'Listusers'                 => array( 'فہرست_صارفین،_صارف_فہرست' ),
	'Log'                       => array( 'نوشتہ', 'نوشتہ_جات' ),
	'Lonelypages'               => array( 'یتیم_صفحات' ),
	'Longpages'                 => array( 'طویل_صفحات' ),
	'MergeHistory'              => array( 'ضم_تاریخچہ' ),
	'Movepage'                  => array( 'منتقلی_صفحہ' ),
	'Mycontributions'           => array( 'میرا_حصہ' ),
	'Mypage'                    => array( 'میرا_صفحہ' ),
	'Mytalk'                    => array( 'میری_گفتگو' ),
	'Myuploads'                 => array( 'میرے_زبراثقالات' ),
	'Newimages'                 => array( 'جدید_املاف', 'جدید_تصاویر' ),
	'Newpages'                  => array( 'جدید_صفحات' ),
	'PermanentLink'             => array( 'مستقل_ربط' ),
	'Popularpages'              => array( 'مقبول_صفحات' ),
	'Preferences'               => array( 'ترجیحات' ),
	'Prefixindex'               => array( 'اشاریہ_سابقہ' ),
	'Protectedpages'            => array( 'محفوظ_صفحات' ),
	'Protectedtitles'           => array( 'محفوظ_عناوین' ),
	'Randompage'                => array( 'تصادف', 'تصادفی_مقالہ' ),
	'Randomredirect'            => array( 'تصادفی_رجوع_مکرر' ),
	'Recentchanges'             => array( 'حالیہ_تبدیلیاں' ),
	'Recentchangeslinked'       => array( 'متعلقہ_تبدیلیاں' ),
	'Revisiondelete'            => array( 'حذف_اعادہ' ),
	'Search'                    => array( 'تلاش' ),
	'Shortpages'                => array( 'مختصر_صفحات' ),
	'Specialpages'              => array( 'خصوصی_صفحات' ),
	'Statistics'                => array( 'شماریات' ),
	'Uncategorizedcategories'   => array( 'غیر_زمرہ_بند_زمرہ_جات' ),
	'Uncategorizedimages'       => array( 'غیر_زمرہ_بند_املاف', 'غیر_زمرہ_بند_تصاویر' ),
	'Uncategorizedpages'        => array( 'غیر_زمرہ_بند_صفحات' ),
	'Uncategorizedtemplates'    => array( 'غیر_زمرہ_بند_سانچے' ),
	'Undelete'                  => array( 'بحال' ),
	'Unusedcategories'          => array( 'غیر_مستعمل_زمرہ_جات' ),
	'Unusedimages'              => array( 'غیر_مستعمل_املاف', 'غیر_مستعمل_تصاویر' ),
	'Unusedtemplates'           => array( 'غیر_مستعمل_سانچے' ),
	'Unwatchedpages'            => array( 'نادیدہ_صفحات' ),
	'Upload'                    => array( 'زبراثقال' ),
	'Userlogin'                 => array( 'داخل_نوشتگی' ),
	'Userlogout'                => array( 'خارج_نوشتگی' ),
	'Userrights'                => array( 'صارفی_اختیارات' ),
	'Version'                   => array( 'اخراجہ' ),
	'Wantedcategories'          => array( 'مطلوب_زمرہ_جات' ),
	'Wantedfiles'               => array( 'مطلوب_املاف' ),
	'Wantedpages'               => array( 'مطلوب_صفحات', 'شکستہ_روابط' ),
	'Wantedtemplates'           => array( 'مطلوب_سانچے' ),
	'Watchlist'                 => array( 'زیر_نظر_فہرست' ),
	'Whatlinkshere'             => array( 'یہاں_کس_کا_رابطہ' ),
	'Withoutinterwiki'          => array( 'بدون_بین_الویکی' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#رجوع_مکرر', '#REDIRECT' ),
	'notoc'                     => array( '0', '_فہرست_نہیں_', '__NOTOC__' ),
	'toc'                       => array( '0', '__فہرست__', '__TOC__' ),
	'noeditsection'             => array( '0', '__ناتحریرقسم__', '__NOEDITSECTION__' ),
	'pagename'                  => array( '1', 'نام_صفحہ', 'PAGENAME' ),
	'namespace'                 => array( '1', 'نام_فضا', 'NAMESPACE' ),
	'msg'                       => array( '0', 'پیغام:', 'MSG:' ),
	'subst'                     => array( '0', 'جا:', 'نقل:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'محفوظ_جا:', 'محفوظ_نقل:', 'SAFESUBST:' ),
	'img_thumbnail'             => array( '1', 'تصغیر', 'thumbnail', 'thumb' ),
	'img_right'                 => array( '1', 'دائیں', 'right' ),
	'img_left'                  => array( '1', 'بائیں', 'left' ),
	'img_center'                => array( '1', 'درمیان', 'center', 'centre' ),
	'sitename'                  => array( '1', 'نام_موقع', 'SITENAME' ),
	'grammar'                   => array( '0', 'قواعد:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'جنس:', 'GENDER:' ),
	'special'                   => array( '0', 'خاص', 'special' ),
	'speciale'                  => array( '0', 'خاص_عنوان', 'speciale' ),
	'index'                     => array( '1', '__اشاریہ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__نااشاریہ__', '__NOINDEX__' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'ربط کی خط کشیدگی:',
'tog-justify' => 'سطور کی برابری',
'tog-hideminor' => 'حالیہ تبدیلیوں میں معمولی ترمیمات چُھپاؤ',
'tog-hidepatrolled' => 'حالیہ تبدیلیوں میں گشتی ترمیمات چُھپاؤ',
'tog-newpageshidepatrolled' => 'جدید صفحاتی فہرست میں گشتی صفحات چُھپاؤ',
'tog-extendwatchlist' => 'زیرِنظرفہرست کو پھیلاؤ تاکہ اِس میں تمام ترمیمات نظر آئیں، نہ کہ صرف حالیہ ترین',
'tog-usenewrc' => 'افزودہ حالیہ تبدیلیاں استعمال کریں (JavaScript درکار ہوگا)',
'tog-numberheadings' => 'سرخیوں کو خود نمبر دو',
'tog-showtoolbar' => 'تدوینی اوزاردان دکھاؤ ( JavaScript چاہئے)',
'tog-editondblclick' => 'طقین پر صفحات کی ترمیم (JavaScript چاہئے)',
'tog-editsection' => '[ترمیم] روابط کے ذریعے سطری ترمیم کاری فعال کرو',
'tog-editsectiononrightclick' => 'سطری عنوانات پر دایاں طق کے ذریعے سطری ترمیم کاری فعال بناؤ',
'tog-showtoc' => 'فہرستِ مندرجات دکھاؤ (3 سے زیادہ سرخیوں والے صفحات کیلئے)',
'tog-rememberpassword' => 'اِس متصفح پر میرے داخلِ نوشتگی معلومات یاد رکھو (زیادہ سے زیادہ $1 {{PLURAL:$1|دِن|ایام}} کیلئے)',
'tog-watchcreations' => 'میرے مرتب شدہ صفحات کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchdefault' => 'میرے ترمیم شدہ صفحات کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchmoves' => 'میں جن صفحات کو منتقل کرتا ہوں، اُن کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchdeletion' => 'میں جن صفحات کو حذف کروں، اُن کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-minordefault' => 'تمام ترمیمات کو ہمیشہ بطورِ معمولی ترمیم نشانزد کیا کرو',
'tog-previewontop' => 'تدوینی خانہ سے پہلے نمائش دکھاؤ',
'tog-previewonfirst' => 'پہلی ترمیم پر نمائش دکھاؤ',
'tog-nocache' => 'متصفح کا صفحی ابطن سازی غیرفعال',
'tog-enotifwatchlistpages' => 'جب میری زیرِنظرفہرست پر کوئی صفحہ میں تبدیلی واقع ہو تو مجھے برقی ڈاک بھیجو',
'tog-enotifusertalkpages' => 'جب میرا تبادلۂ خیال صفحہ میں تبدیلی واقع ہو تو مجھے برقی ڈاک بھیجو',
'tog-enotifminoredits' => 'صفحات میں معمولی ترمیمات کے بارے میں بھی مجھے برقی ڈاک بھیجو',
'tog-enotifrevealaddr' => 'خبرداری برقی خطوط میں میرا برقی ڈاک پتہ ظاہر کرو',
'tog-shownumberswatching' => 'دیکھنے والے صارفین کی تعداد دکھاؤ',
'tog-oldsig' => 'موجودہ دستخط:',
'tog-fancysig' => '(سادہ دستخط بلا خودکار ربط)',
'tog-uselivepreview' => 'براہِ راست نمائش استعمال کرو (JavaScript چاہئے نیز تجرباتی)',
'tog-forceeditsummary' => 'جب میں ترمیمی خلاصہ خالی چھوڑوں تو مجھے آگاہ کرو',
'tog-watchlisthideown' => 'زیرِنظرفہرست سے میری ترمیمات چھپاؤ',
'tog-watchlisthidebots' => 'زیرِنظرفہرست میں سے روبالی ترمیمات چھپاؤ',
'tog-watchlisthideminor' => 'زیرِنظرفہرست سے معمولی ترمیمات چھپاؤ',
'tog-watchlisthideliu' => 'زیرِنظرفہرست میں سے داخلِ نوشتہ شدہ صارفین کی ترمیمات چھپاؤ',
'tog-watchlisthideanons' => 'زیرِنظرفہرست میں سے نامعلوم صارفین کی ترمیمات چھپاؤ',
'tog-watchlisthidepatrolled' => 'زیرِنظرفہرست میں سے گشت شدہ ترمیمات چھپاؤ',
'tog-ccmeonemails' => 'دیگر صارفین کو ارسال کردہ برقی خطوط کی نقول مجھے ارسال کریں۔',
'tog-diffonly' => 'مختلفات کے نیچے صفحے کی مشمولات مت دکھاؤ',
'tog-showhiddencats' => 'پوشیدہ زمرہ جات دکھاؤ',
'tog-useeditwarning' => 'خبردار مجھے جب میں غیر محفوظ کردہ تبدیلیوں کے ساتھ ایک ترمیم کے صفحے کو چھوڑ دو',

'underline-always' => 'ہمیشہ',
'underline-never' => 'کبھی نہیں',
'underline-default' => 'متصفح کا طے شدہ',

# Font style option in Special:Preferences
'editfont-style' => 'خانۂ تدوین کا اندازِ تحریر:',
'editfont-default' => 'متصفح کا طے شدہ',
'editfont-monospace' => 'یکفضائی نویسہ',
'editfont-sansserif' => 'بےحلیہ نویسہ',
'editfont-serif' => 'حلیہ نویسہ',

# Dates
'sunday' => 'اتوار',
'monday' => 'پير',
'tuesday' => 'منگل',
'wednesday' => 'بدھ',
'thursday' => 'جمعرات',
'friday' => 'جمعہ',
'saturday' => 'ہفتہ',
'sun' => 'اتوار',
'mon' => 'پیر',
'tue' => 'منگل',
'wed' => 'بدھ',
'thu' => 'جمعرات',
'fri' => 'جمعہ',
'sat' => 'ہفتہ',
'january' => 'جنوری',
'february' => 'فروری',
'march' => 'مارچ',
'april' => 'اپريل',
'may_long' => 'مئی',
'june' => 'جون',
'july' => 'جولائی',
'august' => 'اگست',
'september' => 'ستمبر',
'october' => 'اکتوبر',
'november' => 'نومبر',
'december' => 'دسمبر',
'january-gen' => 'جنوری',
'february-gen' => 'فروری',
'march-gen' => 'مارچ',
'april-gen' => 'اپريل',
'may-gen' => 'مئی',
'june-gen' => 'جون',
'july-gen' => 'جولائ',
'august-gen' => 'اگست',
'september-gen' => 'ستمبر',
'october-gen' => 'اکتوبر',
'november-gen' => 'نومبر',
'december-gen' => 'دسمبر',
'jan' => 'جنوری',
'feb' => 'فروری',
'mar' => 'مارچ',
'apr' => 'اپریل',
'may' => 'مئی',
'jun' => 'جون',
'jul' => 'جولائی',
'aug' => 'اگست',
'sep' => 'ستمبر',
'oct' => 'اکتوبر',
'nov' => 'نومبر',
'dec' => 'دسمبر',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|زمرہ|زمرہ جات}}',
'category_header' => 'زمرہ "$1" میں مضامین',
'subcategories' => 'ذیلی زمرہ جات',
'category-media-header' => 'زمرہ "$1" میں وسیط',
'category-empty' => '‘‘اِس زمرہ میں ابھی کوئی صفحات یا وسیط موجود نہیں.’’',
'hidden-categories' => '{{PLURAL:$1|پوشیدہ زمرہ|پوشیدہ زمرہ جات}}',
'hidden-category-category' => 'پوشیدہ زمرہ جات',
'category-subcat-count' => '{{PLURAL:$2|اِس زمرہ میں صرف درج ذیل ذیلی زمرہ ہے.|اِس زمرہ میں درج ذیل {{PLURAL:$1|ذیلی زمرہ|$1 ذیلی زمرہ جات}}, کل $2 میں سے.}}',
'category-subcat-count-limited' => 'اِس زمرہ میں درج ذیل {{PLURAL:$1|ذیلی زمرہ ہے|$1 ذیلی زمرہ جات ہیں}}.',
'listingcontinuesabbrev' => '۔جاری',
'noindex-category' => 'غیر مندرج صفحات',

'about' => 'تعارف',
'article' => 'صفحۂ مشمول',
'newwindow' => '(نـئی ونـڈو میـں)',
'cancel' => 'منسوخ',
'moredotdotdot' => 'اور...',
'mypage' => 'میرا صفحہ',
'mytalk' => 'میری گفتگو',
'anontalk' => 'اس IP کیلیے بات چیت',
'navigation' => 'رہنمائی',
'and' => '&#32;اور',

# Cologne Blue skin
'qbfind' => 'ڈھونڈ',
'qbbrowse' => 'تصفّح',
'qbedit' => 'ترمیم',
'qbpageoptions' => 'صفحۂ ہٰذا',
'qbmyoptions' => 'میرے صفحات',
'qbspecialpages' => 'خاص صفحات',
'faq' => 'معلوماتِ عامہ',
'faqpage' => 'Project:معلوماتِ عامہ',

# Vector skin
'vector-action-addsection' => 'نیا موضوع',
'vector-action-delete' => 'حذف کرو',
'vector-action-move' => 'منتقل کرو',
'vector-action-protect' => 'محفوظ کرو',
'vector-action-undelete' => 'بحال',
'vector-action-unprotect' => 'تحفظ میں تبدیلی',
'vector-view-create' => 'تخلیق',
'vector-view-edit' => 'ترمیم',
'vector-view-history' => 'تاریخ',
'vector-view-view' => 'مطالعہ',
'vector-view-viewsource' => 'مسودہ',
'actions' => 'ایکشنز',
'namespaces' => 'جائے نام',
'variants' => 'متغیرات',

'errorpagetitle' => 'خطاء',
'returnto' => 'واپس $1۔',
'tagline' => '{{SITENAME}} سے',
'help' => 'معاونت',
'search' => 'تلاش',
'searchbutton' => 'تلاش',
'go' => 'چلو',
'searcharticle' => 'چلو',
'history' => 'تاریخچہ ء صفحہ',
'history_short' => 'تاریخچہ',
'updatedmarker' => 'میری آخری آمد تک جدید',
'printableversion' => 'قابل طبع نسخہ',
'permalink' => 'مستقل کڑی',
'print' => 'طباعت',
'view' => 'منظر',
'edit' => 'ترمیم',
'create' => 'تخلیق',
'editthispage' => 'اس صفحہ میں ترمیم کریں',
'create-this-page' => 'صفحہ ہٰذا تخلیق کیجئے',
'delete' => 'حذف',
'deletethispage' => 'یہ صفحہ حذف کریں',
'undelete_short' => 'بحال {{PLURAL:$1|ایک ترمیم|$1 ترامیم}}',
'protect' => 'محفوظ',
'protect_change' => 'تبدیل کرو',
'protectthispage' => 'اس صفحےکومحفوظ کریں',
'unprotect' => 'تحفظ میں تبدیلی',
'unprotectthispage' => 'اِسے صفحے کی تحفظ تبدیل کریں',
'newpage' => 'نیا صفحہ',
'talkpage' => 'اس صفحہ پر تبادلۂ خیال کریں',
'talkpagelinktext' => 'تبادلۂ خیال',
'specialpage' => 'خصوصی صفحہ',
'personaltools' => 'ذاتی اوزار',
'postcomment' => 'اگلا حصّہ',
'articlepage' => 'مندرجاتی صفحہ دیکھیۓ',
'talk' => 'تبادلہٴ خیال',
'views' => 'خیالات',
'toolbox' => 'اوزاردان',
'userpage' => 'صفحۂ صارف دیکھئے',
'projectpage' => 'صفحۂ منصوبہ دیکھئے',
'imagepage' => 'صفحۂ مسل دیکھئے',
'mediawikipage' => 'صفحۂ پیغام دیکھئے',
'templatepage' => 'صفحۂ سانچہ دیکھئے',
'viewhelppage' => 'صفحۂ معاونت دیکھیے',
'categorypage' => 'زمرہ‌جاتی صفحہ دیکھئے',
'viewtalkpage' => 'تبادلۂ خیال دیکھئے',
'otherlanguages' => 'دیگر زبانوں میں',
'redirectedfrom' => '($1 سے پلٹایا گیا)',
'redirectpagesub' => 'لوٹایا گیا صفحہ',
'lastmodifiedat' => 'آخری بار تدوین $2, $1 کو کی گئی۔',
'viewcount' => 'اِس صفحہ تک {{PLURAL:$1|ایک‌بار|$1 مرتبہ}} رسائی کی گئی',
'protectedpage' => 'محفوظ شدہ صفحہ',
'jumpto' => ':چھلانگ بطرف',
'jumptonavigation' => 'رہنمائی',
'jumptosearch' => 'تلاش',
'view-pool-error' => 'معذرت کے ساتھ، تمام معیلات پر اِس وقت اِضافی بوجھ ہے.
بہت زیادہ صارفین اِس وقت یہ صفحہ ملاحظہ کرنے کی کوشش کررہے ہیں.
برائے مہربانی! صفحہ دیکھنے کیلئے دوبارہ کوشش کرنے سے پہلے ذرا انتظار فرمالیجئے.

$1',
'pool-errorunknown' => 'نامعلوم خطا',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'تعارف {{SITENAME}}',
'aboutpage' => 'Project:تعارف',
'copyright' => 'تمام مواد $1 کے تحت میسر ہے۔',
'copyrightpage' => '{{ns:project}}:حقوق تصانیف',
'currentevents' => 'حالیہ واقعات',
'currentevents-url' => 'Project:حالیہ واقعات',
'disclaimers' => 'اعلانات',
'disclaimerpage' => 'Project:عام اعلان',
'edithelp' => 'معاونت براۓ ترمیم',
'helppage' => 'Help:فہرست',
'mainpage' => 'صفحہ اول',
'mainpage-description' => 'صفحہ اول',
'policy-url' => 'Project:حکمتِ عملی',
'portal' => 'دیوان عام',
'portal-url' => 'Project:دیوان عام',
'privacy' => 'اصول براۓ اخفائے راز',
'privacypage' => 'Project:اصولِ اخفائے راز',

'badaccess' => 'خطائے اجازت',
'badaccess-group0' => 'آپ متمنی عمل کا اجراء کرنے کے مُجاز نہیں۔',
'badaccess-groups' => 'آپ کا درخواست‌کردہ عمل {{PLURAL:$2|گروہ|گروہوں میں سے ایک}}: $1 کے صارفین تک محدود ہے.',

'versionrequired' => 'میڈیا ویکی کا $1 نسخہ لازمی چاہئیے.',
'versionrequiredtext' => 'اِس صفحہ کو استعمال کرنے کیلئے میڈیاویکی کا $1 نسخہ چاہئیے.


دیکھئے [[خاص:نسخہ|صفحۂ نسخہ]]',

'ok' => 'ٹھیک ہے',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom' => '‘‘$1’’ مستعادہ منجانب',
'youhavenewmessages' => 'آپکے لیۓ ایک $1 ہے۔ ($2)',
'newmessageslink' => 'نئے پیغامات',
'newmessagesdifflink' => 'تـجـدیـد مـاقـبل آخـر سے فـرق',
'newmessagesdifflinkplural' => 'آخری {{PLURAL:$1|تبدیلی|تبدیلیاں}}',
'youhavenewmessagesmulti' => 'ء$1 پر آپ کیلئے نئے پیغامات ہیں',
'editsection' => 'ترمیم',
'editold' => 'ترمیم',
'viewsourceold' => 'مآخذ دیکھئے',
'editlink' => 'تدوین کریں',
'viewsourcelink' => 'مآخذ دیکھئے',
'editsectionhint' => 'تدوینِ حصّہ: $1',
'toc' => 'فہرست',
'showtoc' => 'دکھائیں',
'hidetoc' => 'چھپائیں',
'collapsible-expand' => 'توسیع',
'thisisdeleted' => 'دیکھیں یا بحال کریں $1؟',
'viewdeleted' => 'دیکھیں $1؟',
'restorelink' => '{{PLURAL:$1|ایک ترمیم حذف ہوچکی|$1 ترامیم حذف ہوچکیں}}',
'feedlinks' => 'خورد:',
'feed-invalid' => 'ناقص خوردی قسم.',
'feed-unavailable' => 'سندیکتی خورد دستیاب نہیں ہیں',
'site-rss-feed' => '$1 آر.ایس.ایس فیڈ',
'site-atom-feed' => '$1 اٹوم فیڈ',
'page-rss-feed' => '"$1" آر.ایس.ایس فیڈ',
'page-atom-feed' => '"$1" اٹوم خورد',
'feed-atom' => 'اٹوم',
'feed-rss' => 'آر ایس ایس',
'red-link-title' => '$1 (صفحہ موجود نہیں)',
'sort-descending' => 'ترتیب نزولی',
'sort-ascending' => 'ترتیب صعودی',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'صفحہ',
'nstab-user' => 'صفحۂ صارف',
'nstab-media' => 'صفحۂ وسیط',
'nstab-special' => 'خاص صفحہ',
'nstab-project' => 'صفحۂ منصوبہ',
'nstab-image' => 'مسل',
'nstab-mediawiki' => 'پیغام',
'nstab-template' => 'سانچہ',
'nstab-help' => 'معاونت',
'nstab-category' => 'زمرہ',

# Main script and global functions
'nosuchaction' => 'کوئی سا عمل نہیں',
'nosuchactiontext' => 'URL کی جانب سے مختص کیا گیا عمل درست نہیں.
آپ نے شاید URL غلط لکھا، یا کسی غیر صحیح ربط کی پیروی کی ہے.
{{اِس سے SITENAME کے زیرِ استعمال مصنع لطیف میں کھٹمل کی نشاندہی کا بھی اندیشہ ہے}}.',
'nosuchspecialpage' => 'کوئی ایسا خاص صفحہ نہیں',
'nospecialpagetext' => '<strong>آپ نے ایک ناقص خاص صفحہ کی درخواست کی ہے.</strong>

{{درست خاص صفحات کی ایک فہرست [[Special:SpecialPages|{{int:specialpages}}]] پر دیکھی جاسکتی ہے}}.',

# General errors
'error' => 'خطاء',
'databaseerror' => 'خطائے ڈیٹابیس',
'laggedslavemode' => 'انتباہ: ممکن ہے کہ صفحہ میں حالیہ بتاریخہ جات شامل نہ ہوں.

Warning: Page may not contain recent updates.',
'readonly' => 'ڈیٹابیس مقفل ہے',
'enterlockreason' => 'قفل کیلئے کوئی وجہ درج کیجئے، بشمولِ تخمینہ کہ قفل کب کھولا جائے گا.',
'readonlytext' => 'ڈیٹابیس  شاید معمول کی اصلاح کیلئے نئے اندراجات اور دوسری ترمیمات کیلئے مقفل ہے، جس کے بعد یہ عام حالت پر آجائے گا.
منتظم، جس نے قفل لگایا، یہ تفصیل فراہم کی ہے: $1',
'missing-article' => 'ڈیٹابیس نے کسی صفحے کا متن بنام "$1" $2  نہیں پایا جو اِسے پانا چاہئے تھا.

یہ عموماً کسی صفحے کے تاریخی یا پرانے حذف شدہ ربط کی وجہ سے ہوسکتا ہے.

اگر یہ وجہ نہیں، تو آپ نے مصنع‌لطیف میں کھٹمل پایا ہے.
برائے مہربانی، URL کی نشاندہی کرتے ہوئے کسی [[Special:ListUsers/sysop|منتظم]] کو اِس کا سندیس کیجئے.',
'missingarticle-rev' => '(نظرثانی#: $1)',
'readonly_lag' => 'ڈیٹابیس خودکار طور پر مقفل ہوچکا ہے تاکہ ماتحت ڈیٹابیسی معیلات کا درجہ آقا کا ہوجائے.',
'internalerror' => 'خطائے اندرونی',
'internalerror_info' => 'خطائے اندرونی: $1',
'filecopyerror' => '"$1" مسل کو "$2" کی طرف نقل نہیں کیا جاسکا.',
'filerenameerror' => 'مسل "$1" کو "$2" میں بازنام نہیں کیا جاسکا.',
'filedeleteerror' => 'مسل "$1" کو حذف نہیں کیا جاسکا.',
'directorycreateerror' => 'رہنامچہ "$1" تخلیق نہیں کیا جاسکا.',
'filenotfound' => 'مسل "$1" ڈھونڈا نہ جاسکا.',
'fileexistserror' => 'مسل "$1" کو لکھنے سے قاصر، مسل پہلے سے موجود',
'unexpected' => 'غیرمتوقع قدر: "$1"="$2"',
'formerror' => 'خطا: ورقہ بھیجا نہ جاسکا.',
'badarticleerror' => 'اس صفحہ پر یہ عمل انجام نہیں دیا جاسکتا۔',
'cannotdelete' => 'صفحہ یا ملف $1 کو حذف نہیں کیا جاسکتا.
ہوسکتا ہے کہ اسے پہلے ہی کسی نے حذف کردیا ہو.',
'cannotdelete-title' => 'صفحہ ھذف نہیں کیا جا سکتا "$1"',
'badtitle' => 'خراب عنوان',
'badtitletext' => 'درخواست شدہ صفحہ کا عنوان ناقص، خالی، یا کوئی غلط ربط شدہ بین لسانی یا بین ویکی عنوان ہے.
شاید اِس میں ایک یا زیادہ ایسے حروف موجود ہوں جو عنوانات میں استعمال نہیں ہوسکتے.',
'perfcached' => 'ذیلی ڈیٹا ابطن شدہ ہے اور اِس کے پُرانے ہونے کا امکان ہے. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => 'ذیلی ڈیٹا ابطن شدہ ہے اور آخری بار اِس کی بتاریخیت $1 کو ہوئی. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => 'اِس صفحہ کیلئے بتاریخات فی الحال ناقابل بنائی گئی ہیں.
یہاں کا ڈیٹا ابھی تازہ نہیں کیا جائے گا.',
'viewsource' => 'مسودہ',
'actionthrottledtext' => 'بطورِ ایک ضدسپم تدبیر، آپ کو مختصر وقت میں کئی بار یہ عمل بجا لانے سے محدود کیا گیا، اور آپ یہ حد پار کرچکے ہیں.
براہِ کرم، کچھ منٹ بعد کوشش کیجئے.',
'protectedpagetext' => 'اس صفحہ کو تدوین سے محفوظ رکھنے کیلیے مقفل کر دیا گیا ہے۔',
'viewsourcetext' => 'آپ صرف مسودہ دیکھ سکتے ہیں اور اسکی نقل اتار سکتے ہیں:',
'protectedinterface' => 'یہ صفحہ مصنع‌لطیف کیلئے سطح‌البینی متن فراہم کرتا ہے، اور ناجائزاستعمال کے سدِباب کیلئے اِسے مقفل کیا گیا ہے.',
'editinginterface' => "'''انتباہ: ''' آپ ایک ایسا صفحہ تدوین کر رہے ہیں جو مصنع‌لطیف کیلئے سطح‌البینی متن فراہم کرتا ہے۔ اس صفحہ میں کی جانے والی ترمیم، دیگر صارفین کیلئے سطح‌البین کو تبدیل کردے گی۔
براہِ کرم، ترجمہ کیلئے [//translatewiki.net/wiki/Main_Page?setlang=en '''ٹرانسلیٹ ویکی.نیٹ'''] (میڈیاویکی مقامیانی منصوبہ) استعمال کیجئے.",
'namespaceprotected' => "آپ کو '''$1''' فضائے نام میں صفحات تدوین کرنے کی اِجازت نہیں ہے.",
'ns-specialprotected' => 'خاص صفحات کی تدوین نہیں کی جاسکتی.',
'titleprotected' => 'اس عنوان کو [[User:$1|$1]] نے تخلیق سے محفوظ کیا ہے.
وجہ یہ بتائی گئی ہے: "\'\'$2\'\'"',
'exception-nologin' => 'غیر داخل نوشتہ',

# Virus scanner
'virus-badscanner' => "خراب وضعیت: انجان وائرسی مفراس: ''$1''",
'virus-scanfailed' => 'تفریس ناکام (رمز $1)',
'virus-unknownscanner' => 'انجان ضدوائرس:',

# Login and logout pages
'logouttext' => "'''اب آپ خارج ہوچکے ہیں'''

آپ گمنام طور پر {{SITENAME}}  کا استعمال جاری رکھ سکتے ہیں، یا دوبارہ اسی نام یا مختلف نام سے <span class='plainlinks'>[$1 دوبارہ داخلِ نوشتہ]</span> بھی ہو سکتے ہیں۔  یہ یاد آوری کرلیجیۓ کہ کچھ صفحات ایسے نظر آتے رہیں گے کہ جیسے ابھی آپ خارج نہیں ہوئے ، جب تک آپ اپنے متصفح کا ابطن صاف نہ کردیں۔",
'yourname' => 'اسمِ رکنیت',
'yourpassword' => 'کلمۂ شناخت',
'createacct-yourpassword-ph' => 'ایک پاس ورڈ داخل کریں',
'yourpasswordagain' => 'کلمۂ شناخت دوبارہ لکھیں',
'createacct-yourpasswordagain' => 'کلمۂ اجازت تصدیق کریں',
'createacct-yourpasswordagain-ph' => 'پاس ورڈ پھر داخل کریں',
'remembermypassword' => 'اِس متصفح پر میرے داخلِ نوشتگی معلومات یاد رکھو (زیادہ سے زیادہ $1 {{PLURAL:$1|دِن|ایام}} کیلئے)',
'yourdomainname' => 'آپکا ڈومین',
'password-change-forbidden' => 'آپ اس ویکی پر پارلفظ (پاس روڈ) تبدیل نہیں کر سکتے',
'externaldberror' => 'یا تو توثیقی ڈیٹابیس میں خطا واقع ہوئی اور یا آپ کو بیرونی کھاتہ بتاریخ کرنے کی اِجازت نہیں ہے.',
'login' => 'داخل ہوں',
'nav-login-createaccount' => 'کھاتہ کھولیں یا اندراج کریں',
'loginprompt' => '{{SITENAME}} میں داخلے کیلۓ آپکے پاس قند (کوکیز) مجازہوناچاہیں۔',
'userlogin' => 'کھاتہ کھولیں یا اندراج کریں',
'userloginnocreate' => 'داخلِ نوشتہ ہوجائیے',
'logout' => 'اخراج',
'userlogout' => 'خارج ہوجائیں',
'notloggedin' => 'داخلہ نہیں ہوا',
'nologin' => "کیا آپ نے کھاتہ نہیں بنایا ہوا؟ '''$1'''۔",
'nologinlink' => 'کھاتا بنائیں',
'createaccount' => 'کھاتہ کھولیں',
'gotaccount' => "پہلے سے کھاتہ بنا ہوا ہے? '''$1'''.",
'gotaccountlink' => 'داخل ہوجائیے',
'userlogin-resetlink' => 'داخلِ نوشتہ ہونے کی تفاصیل بھول گئے ہیں؟',
'createacct-join' => 'اپنی معلومات نیچے لکھیں۔',
'createacct-emailrequired' => 'ای میل پتہ',
'createacct-emailoptional' => 'ای میل ایڈریس (اختیاری)',
'createacct-email-ph' => 'اپنا برقی پتہ لکھیں',
'createaccountmail' => 'بذریعۂ برقی ڈاک',
'createacct-realname' => 'اصلی نام (اختیاری)',
'createaccountreason' => 'وجہ:',
'createacct-reason' => 'وجہ',
'createacct-captcha' => 'حفاظتی تدبیر',
'createacct-imgcaptcha-ph' => 'آپ اوپر دیکھ متن داخل کریں',
'createacct-benefit-heading' => '{{SITENAME}} آپ جیسے لوگوں کی طرف سے بنایا گیا ہے ۔',
'createacct-benefit-body1' => 'ترمیم',
'createacct-benefit-body2' => 'صفحات',
'createacct-benefit-body3' => 'شرکت کرنے والے اس ماہ کے',
'badretype' => 'درج شدہ کلمۂ شناخت اصل سے مطابقت نہیں رکھتا۔',
'userexists' => 'داخل کردہ اسم صارف پہلے سے مستعمل ہے۔
براہِ کرم! کوئی دوسرا اسم منتخب کیجئے۔',
'loginerror' => 'داخلے میں غلطی',
'createacct-error' => 'تخلیق کھاتہ میں نقص',
'createaccounterror' => 'کھاتہ $1 بنایا نہیں جاسکا',
'nocookiesnew' => 'کھاتۂ صارف بنادیا گیا ہے، لیکن آپ کا داخلہ نہیں ہوا.
صارفین کے داخلہ کیلئے {{SITENAME}} کوکیز استعمال کرتا ہے.
آپ کے ہاں کوکیز غیر فعال ہیں.
براہِ کرم، انہیں فعال کیجئے، اور پھر اپنے نئے اسمِ صارف اور کلمۂ شناخت کے ساتھ داخل ہوجائیے.',
'nocookieslogin' => 'صارفین کے داخل ہونے کیلئے {{SITENAME}} کوکیز استعمال کرتا ہے.
آپ کے ہاں کوکیز غیر فعال ہیں.
انہیں فعال کرنے کے بعد پھر کوشش کیجئے.',
'noname' => 'آپ نے صحیح اسم صارف نہیں چنا.',
'loginsuccesstitle' => 'داخلہ کامیاب',
'loginsuccess' => "'''اب آپ {{SITENAME}} میں بنام \"\$1\" داخل ہوچکے ہیں۔'''",
'nosuchuser' => '"$1" کے نام سے کوئی صارف موجود نہیں ہے.
برائے مہربانی! ہجوں کے درست اندراج کی تصدیق کرلیجئے.
اگر آپ چاہیں تو [[Special:UserLogin/signup|نیا کھاتہ بھی بناسکتے ہیں]].',
'nosuchusershort' => '"$1" کے نام سے کوئی صارف موجود نہیں.
اپنا ہجہ جانچئے.',
'nouserspecified' => 'آپ کو ایک اسمِ صارف مخصوص کرنا ہے.',
'login-userblocked' => 'اِس صارف پر پابندی ہے. داخلِ نوشتہ ہونے کی اجازت نہیں.',
'wrongpassword' => 'آپ نے غلط کلمۂ شناخت درج کیا ہے۔ دوبارہ کو شش کریں۔',
'wrongpasswordempty' => 'کلمۂ شناخت ندارد۔ دوبارہ کوشش کریں۔',
'passwordtooshort' => 'آپکا منتخب کردہ پارلفظ مختصر ہے. پارلفظ کم از کم {{PLURAL:$1|1 محرف|$1 محارف}} ہونا چاہئے.',
'password-name-match' => 'آپکا پارلفظ آپکے اسمِ صارف سے مختلف ہونا چاہئے.',
'mailmypassword' => 'نیا پارلفظ برقی ڈاک میں بھیجو',
'passwordremindertitle' => 'نیا عارضی کلمۂ شناخت برائے {{SITENAME}}',
'passwordremindertext' => '(IP پتہ $1 سے) کسی (یا شاید آپ) نے {{SITENAME}} ($4)
کیلئے نئی کلمۂ شناخت کیلئے التماس کیا. ایک عارضی کلمۂ شناخت "$3"
برائے صارف "$2" تخلیق کیا گیا ہے. اگر یہ آپ کا ارادہ تھا، تو آپ
کو چاہئے کہ داخلِ نوشتہ ہونے کے بعد نئے کلمۂ شناخت کا انتخاب کریں.
آپ کا کلمۂ شناخت {{PLURAL:$5|ایک دِن|$5 دِن}} کے بعد ناکارہ ہوجائے گا.

اگر کسی اَور نے یہ التماس کیا ہے، یا آپ کو اپنا کلمۂ شناخت یاد آگیا ہے،
اور آپ اسے تبدیل نہیں کرنا چاہتے، تو آپ یہ پیغام نظر انداز کرسکتے ہیں اور
آپنا پُرانا کلمۂ شناخت کا استعمال جاری رکھ سکتے ہیں.',
'noemail' => 'صارف "$1" کیلئے کوئی برقی پتہ درج نہیں کیا گیا.',
'noemailcreate' => 'صحیح برقی پتہ مہیّا کریں',
'passwordsent' => 'ایک نیا کلمۂ شناخت "$1" کے نام سے بننے والی برقی ڈاک کے پتے کیلیے بھیج دیا گیا ہے۔
جب وہ موصول ہو جاۓ تو براہ کرم اسکے ذریعے دوبارہ داخل ہوں۔',
'blocked-mailpassword' => 'آپ کا آئی.پی پتہ تدوین سے روک لیا گیا ہے، سو، ناجائز استعمال کو روکنے کیلئے، آپ کے آئی.پی پتہ کو کلمۂ شناخت کی بحالی کا فعل استعمال کرنے کی اِجازت نہیں ہے.',
'eauthentsent' => 'ایک تصدیقی برقی خط نامزد کئے گئے برقی پتہ پر ارسال کردیا گیا ہے.
آپ کو موصول ہوئے برقی خط میں ہدایات پر عمل کرکے اس بات کی توثیق کرلیں کہ مذکورہ برقی پتہ آپ کا ہی ہے.',
'throttled-mailpassword' => 'گزشتہ {{PLURAL:$1|گھنٹے|$1 گھنٹوں}} کے دوران پہلے سے ہی پارلفظ کی ایک یادآوری بھیجی جاچکی ہے.
ناجائز استعمال کے سدّباب کیلئے، {{PLURAL:$1|گھنٹہ|$1 گھنٹوں}} کے دوران صرف ایک پارلفظی یادآواری بھیجی جاسکتی ہے.',
'mailerror' => 'مسلہ دوران ترسیل خط:$1',
'acct_creation_throttle_hit' => 'آپکی آئی.پی کے ذریعے اِس ویکی پر آنے والے صارفین نے پچھلے ایک دِن میں {{PLURAL:$1|1 کھاتہ بنایا ہے|$1 کھاتے بنائے ہیں}}، جو کہ مذکورہ وقت میں کافی ہیں.
لہٰذا، آپکی آئی.پی استعمال کرنے والے صارفین اِس وقت مزید کھاتے نہیں بناسکتے.',
'emailauthenticated' => 'آپکے برقی ڈاک پتہ کی تصدیق تاریخ $2 بوقت $3 بجے کو ہوئی.',
'emailnotauthenticated' => 'آپ کے برقی پتہ کی ابھی تصدیق نہیں ہوئی ہے.
درج ذیل میں سے کسی بھی چیز کیلئے آپکے برقی پتہ پر برقی ڈاک ارسال نہیں کیا جائے گا.',
'noemailprefs' => 'اِن خصائص کو کام میں لانے کیلئے اپنے ترجیحات میں برقی ڈاک کا پتہ متعین کیجئے.',
'emailconfirmlink' => 'اپنے برقی پتہ کی تصدیق کیجئے',
'invalidemailaddress' => 'برقی پتہ قبول نہیں کیا جاسکتا کیونکہ یہ غلط شکل میں ہے.
براہِ کرم! ایک برقی پتہ صحیح شکل میں درج کیجئے یا جگہ کو خالی چھوڑ دیجئے.',
'accountcreated' => 'تخلیقِ کھاتہ',
'accountcreatedtext' => 'تخیلقِ کھاتۂ صارف براۓ $1۔',
'createaccount-title' => 'کھاتہ سازی برائے {{SITENAME}}',
'createaccount-text' => 'کسی نے {{SITENAME}} ($4) پر "$2" کے نام سے اور "$3" پارلفظ کے ساتھ آپ کا برقی پتہ استعمال کرتے ہوئے کھاتہ بنایا ہے.
آپ کو چاہئے کہ ابھی داخلِ نوشتہ ہوکر اپنا پارلفظ تبدیل کردیں.

اگر یہ کھاتہ غلطی سے بنا تھا تو آپ یہ پیغام نظرانداز کرسکتے ہیں.',
'usernamehasherror' => 'اسمِ صارف میں خلط ملط محارف استعمال نہیں کئے جاسکتے',
'login-throttled' => 'آپ نے داخلِ نوشتہ ہونے کیلئے بہت زیادہ حالیہ کوششیں کیں.
دوبارہ کوشش کرنے سے پہلے انتظار فرمائیے.',
'loginlanguagelabel' => 'زبان: $1',

# Email sending
'user-mail-no-addy' => 'برقی ڈاک بھیجنے کی کوشش بغیر برقی ڈاک پتہ',

# Change password dialog
'resetpass' => 'پارلفظ تبدیل کریں',
'resetpass_announce' => 'آپ ایک برقی ارسال کردہ عارضی رمز کے ساتھ داخل ہوئے ہیں.
داخلِ نوشتہ کے عمل کو مکمل کرنے کیلئے آپ کو یہاں نیا پارلفظ متعین کرنا ہوگا:',
'resetpass_header' => 'کھاتہ کا پارلفظ تبدیل کریں',
'oldpassword' => 'پرانا کلمۂ شناخت:',
'newpassword' => 'نیا کلمۂ شناخت',
'retypenew' => 'نیا کلمۂ شناخت دوبارہ درج کریں:',
'resetpass_submit' => 'پارلفظ بناؤ اور داخل ہوجاؤ',
'changepassword-success' => 'آپ کا پارلفظ کامیابی سے تبدیل ہوگیا!
اَب داخلِ نوشتہ کیا جارہا ہے...',
'resetpass_forbidden' => 'پارلفظ تبدیل نہیں ہوسکتا',
'resetpass-no-info' => 'اِس صفحہ تک براہِ راست رسائی کیلئے آپ کو داخلِ نوشتہ ہونا پڑے گا.',
'resetpass-submit-loggedin' => 'پارلفظ کی تبدیلی',
'resetpass-submit-cancel' => 'منسوخ',
'resetpass-wrong-oldpass' => 'عارضی یا موجودہ پارلفظ ناقص ہے.
آپ یا تو پہلے ہی سے آپنا پارلفظ کامیابی سے تبدیل کرچکے ہیں اور یا آپ نے نئے عارضی پارلفظ کی درخواست کی ہے.',
'resetpass-temp-password' => 'عارضی پارلفظ:',

# Special:PasswordReset
'passwordreset' => 'پارلفظ کی بازتعینی',
'passwordreset-username' => 'اسمِ صارف:',
'passwordreset-domain' => 'ساحہ:',
'passwordreset-email' => 'برقی ڈاک پتہ:',

# Special:ChangeEmail
'changeemail-oldemail' => 'حالیہ برقی ڈاک پتہ:',
'changeemail-newemail' => 'نیا برقی ڈاک پتہ:',
'changeemail-none' => '(کوئی نہیں)',
'changeemail-submit' => 'برقی ڈاک تبدیل کریں',
'changeemail-cancel' => 'منسوخ',

# Edit page toolbar
'bold_sample' => 'دبیز متن',
'bold_tip' => 'دبیز متن',
'italic_sample' => 'ترچھا متن',
'italic_tip' => 'ترچھی لکھائی',
'link_sample' => 'ربط کا عنوان',
'link_tip' => 'اندرونی ربط',
'extlink_sample' => 'http://www.example.com ربط کا عنوان',
'extlink_tip' => 'بیرونی ربط (یاد رکھئے http:// prefix)',
'headline_sample' => 'شہ سرخی',
'headline_tip' => 'شہ سرخی درجہ دوم',
'nowiki_sample' => 'غیرشکلبندشدہ متن یہاں درج کریں',
'nowiki_tip' => 'ویکی شکلبندی نظرانداز کریں',
'image_tip' => 'پیوستہ ملف',
'media_tip' => 'ربطِ ملف',
'sig_tip' => 'آپکا دستخط بمع مہرِوقت',
'hr_tip' => 'اُفقی لکیر (زیادہ استعمال نہ کریں)',

# Edit pages
'summary' => 'خلاصہ:',
'subject' => 'مضمون/شہ سرخی:',
'minoredit' => 'معمولی ترمیم',
'watchthis' => 'یہ صفحہ زیر نظر کیجیۓ',
'savearticle' => 'محفوظ',
'preview' => 'نمائش',
'showpreview' => 'نمائش',
'showlivepreview' => 'براہِراست پیش منظر',
'showdiff' => 'تبدیلیاں دکھاؤ',
'anoneditwarning' => 'آپ {{SITENAME}} میں داخل نہیں ہوۓ لہذا آپکا IP پتہ اس صفحہ کے تاریخچہ ء ترمیم میں محفوظ ہوجاۓ گا۔',
'missingsummary' => "'''انتباہ:''' آپ نے ترمیمی خلاصہ مہیّا نہیں کیا.
اگر آپ نے محفوظ کا بٹن دوبارہ دبایا تو آپ کی ترمیم بغیر کسی خلاصہ کے محفوظ ہوجائے گی.",
'missingcommenttext' => 'براہِ کرم! تبصرہ نیچے درج کیجئے.',
'missingcommentheader' => "'''انتباہ:''' آپ نے اِس تبصرہ کیلئے عنوان یا شہ سرخی مہیّا نہیں کی.
اگر آپ نے محفوظ کا بٹن دوبارہ دبایا تو آپ کا تبصرہ بغیر کسی عنوان کے محفوظ ہوجائے گا.",
'summary-preview' => 'نمائش خلاصہ:',
'subject-preview' => 'عنوان/شہ سرخی کا پیش منظر:',
'blockedtitle' => 'صارف مسدود ہے',
'blockedtext' => "'''آپکا اسمِ صارف یا آئی پی پتہ پر پابندی ہے.'''

$1 نے پابندی لگائی تھی.
وجہ یہ بتائی گئی کہ ''$2''.

* پابندی کی ابتداء : $8
* پابندی کا اختتام : $6
* Intended blockee: $7

آپ $1 یا کسی دوسرے [[{{MediaWiki:Grouppage-sysop}}|منتظم]] سے رابطہ کرکے پابندی پر بات چیت کرسکتے ہیں.
آپ ‘صارف کو برقی خط ارسال کریں’ کی خاصیت اُس وقت تک استعمال نہیں کرسکتے جب تک آپ اپنے [[Special:Preferences|کھاتہ کے ترجیحات]] میں صحیح برقی پتہ معیّن نہ کریں، اور آپ کو اِسے استعمال کرنے سے پابند نہیں کیا گیا ہے.
آپکا موجودہ آئی پی پتہ $3 ہے، اور پابندی کی شناخت #$5 ہے.
براہِ مہربانی کسی بھی قسم کے استفسار میں درج بالا تمام تفاصیل شامل کریں.",
'blockednoreason' => 'کوئی وجہ نہیں دی گئی',
'whitelistedittext' => 'ترمیم کیلئے $1 ضروری ہے.',
'confirmedittext' => 'صفحات میں ترمیم کرنے سے پہلے آپ اپنے برقی پتہ کی تصدیق کریں.
برائے مہربانی! اپنی [[Special:Preferences|ترجیحات]] کے ذریعے اپنا برقی پتہ کا تعیّن اور تصدیق کیجئے.',
'nosuchsectiontitle' => 'قطعہ نہیں ملا',
'nosuchsectiontext' => 'آپ نے ایسے قطعہ میں ترمیم کی کوشش کی ہے جو کہ موجود نہیں.
ہوسکتا ہے کہ جب آپ صفحہ ملاحظہ فرمارہے تھے اُسی اثناء مذکورہ قطعہ کو منتقل یا حذف کردیا گیا ہو.',
'loginreqtitle' => 'داخلہ / اندراج لازم',
'loginreqlink' => 'داخلہ',
'loginreqpagetext' => 'دوسرے صفحات ملاحظہ کرنے کیلئے آپکا $1 ضروری ہے.',
'accmailtitle' => 'کلمہ شناخت بھیج دیا گیا۔',
'accmailtext' => "[[User talk:$1|$1]] کیلئے خودکار طریقے سے تخلیق کیا گیا پارلفظ $2 کو بھیج دیا گیا ہے.

داخلِ نوشتہ ہونے پر اِس جدید کھاتے کیلئے پارلفظ ''[[Special:ChangePassword|پارلفظ کی تبدیلی]]'' میں تبدیل کیا جاسکتا ہے.",
'newarticle' => '(نیا)',
'newarticletext' => "آپ نے ایک ایسے صفحے کے ربط کی پیروی کی ہے جو کہ ابھی موجود نہیں ہے.
یہ صفحہ تخلیق کرنے کیلئے درج ذیل خانہ میں متن درج کیجئے (مزید معلومات کیلئے [[{{MediaWiki:Helppage}}|صفحۂ معاونت]] ملاحظہ فرمائیے).
اگر آپ یہاں غلطی سے پہنچے ہیں تو پچھلے صفحے پر واپس جانے کیلئے اپنے متصفح پر '''back''' کا بٹن ٹک کیجئے.",
'anontalkpagetext' => "----''یہ صفحہ ایک ایسے صارف کا ہے جنہوں نے یا تو اب تک اپنا کھاتا نہیں بنایا یا پھر وہ اسے استعمال نہیں کر رہے/ رہی ہیں۔ لہٰذا ہمیں انکی شناخت کے لئے ایک عددی آئی پی پتہ استعمال کرنا پڑرہا ہے۔ اس قسم کا آئی پی پتہ ایک سے زائد صارفین کے لئے مشترک بھی ہوسکتا ہے۔ اگر آپکی موجودہ حیثیت ایک گمنام صارف کی ہے اور آپ محسوس کریں کہ اس صفحہ پر آپکی جانب منسوب یہ بیان غیرضروری ہے تو براہ کرم [[Special:UserLogin/signup|کھاتہ بنائیں]] یا [[Special:UserLogin|داخلِ نوشتہ]] ہوجائیے تاکہ مستقبل میں آپکو گمنام صارفین میں شمار کرنے سے پرہیز کیا جاسکے۔\"",
'noarticletext' => 'اِس صفحہ میں فی الحال کوئی متن موجود نہیں ہے.
آپ دیگں صفحات میں [[Special:Search/{{PAGENAME}}|اِس صفحہ کے عنوان کیلئے تلاش کرسکتے ہیں]]، <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} متعلقہ نوشتہ جات تلاش کرسکتے ہیں],
یا [{{fullurl:{{FULLPAGENAME}}|action=edit}} اِس صفحہ میں ترمیم کرسکتے ہیں]</span>',
'noarticletext-nopermission' => 'اس صفحہ میں فی الحال کوئی متن موجود نہیں ہے.
آپ دیگں صفحات میں [[Special:Search/{{PAGENAME}}|اِس صفحہ کے عنوان کیلئے]] یا <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} متعلقہ نوشتہ جات تلاش کرسکتے ہیں]</span>',
'updated' => '(اپ ڈیٹڈ)',
'note' => "'''نوٹ:'''",
'previewnote' => "'''یاد رکھیں، یہ صرف نمائش ہے ۔آپ کی ترامیم ابھی محفوظ نہیں کی گئیں۔'''",
'session_fail_preview' => 'معاف کیجئے! نشست کے مواد میں خامی کی وجہ سے آپکی  ترمیم پر عمل نہیں کیا جاسکا.
برائے مہربانی دوبارہ کوشش کیجئے.
اگر آپکو پھر بھی مشکل پیش آرہی ہے تو [[Special:UserLogout|خارجِ نوشتہ]] ہوکر واپس داخلِ نوشتہ ہوجایئے.',
'editing' => 'آپ "$1" میں ترمیم کر رہے ہیں۔',
'editingsection' => '$1 کے قطعہ کی تدوین',
'editingcomment' => 'زیرترمیم $1 (تبصرہ)',
'editconflict' => 'تنازعہ ترمیم:$1',
'explainconflict' => "آپکی تدوین شروع ہونے کے بعد شاید کسی نے یہ صفحہ تبدیل کردیا ہے.
بالائی خانۂ متن میں صفحہ کا موجودہ مواد ہے.
آپ کی تبدیلیاں نچلے متن خانہ میں دکھائی گئی ہیں.
آپ کو اپنی تبدیلیاں موجودہ متن میں ضم کرنا ہوں گی.
\"محفوظ\" کا بٹن ٹک کرنے سے '''صرف''' بالائی متن محفوظ ہوگا.",
'yourtext' => 'آپ کی تحریر',
'storedversion' => 'ذخیرہ شدہ نظرثانی',
'nonunicodebrowser' => '"انتباہ: آپ کا براؤزر یونی کوڈ کے مطابق نہیں ہے."',
'editingold' => "'''انتباہ: آپ اس صفحے کا ایک پرانا مسودہ مرتب کررہے ہیں۔ اگر آپ اسے محفوظ کرتے ہیں تو اس صفحے کے اس پرانے مسودے سے اب تک کی جانے والی تمام تدوین ضائع ہو جاۓ گی۔'''",
'yourdiff' => 'تضادات',
'copyrightwarning' => "یہ یادآوری کرلیجیۓ کہ {{SITENAME}} میں تمام تحریری شراکت جی این یو آزاد مسوداتی اجازہ ($2)کے تحت تصور کی جاتی ہے (مزید تفصیل کیلیۓ $1 دیکھیۓ)۔ اگر آپ اس بات سے متفق نہیں کہ آپکی تحریر میں ترمیمات کری جائیں اور اسے آزادانہ (جیسے ضرورت ہو) استعمال کیا جاۓ تو براۓ کرم اپنی تصانیف یہاں داخل نہ کیجیۓ۔ اگر آپ یہاں اپنی تحریر جمع کراتے ہیں تو آپ اس بات کا بھی اقرار کر رہے ہیں کہ، اسے آپ نے خود تصنیف کیا ہے یا دائرہ ءعام (پبلک ڈومین) سے حاصل کیا ہے یا اس جیسے کسی اور آذاد وسیلہ سے۔'''بلااجازت ایسا کام داخل نہ کیجیۓ جسکا حق ِطبع و نشر محفوظ ہو!'''",
'templatesused' => 'اِس صفحہ پر مستعمل {{PLURAL:$1|سانچہ|سانچے}}:',
'templatesusedpreview' => 'اِس پیش منظر میں مستعمل {{PLURAL:$1|سانچہ|سانچے}}:',
'templatesusedsection' => 'اِس قطعہ میں مستعمل {{PLURAL:$1|سانچہ|سانچے}}:',
'template-protected' => '(محفوظ شدہ)',
'template-semiprotected' => '(نیم محفوظ)',
'hiddencategories' => 'یہ صفحہ {{PLURAL:$1|1 چُھپے زمرے|$1 چُھپے زمرہ جات}} میں شامل ہے:',
'nocreate-loggedin' => 'آپ کو نئے صفحات تخلیق کرنے کی اجازت نہیں ہے.',
'sectioneditnotsupported-title' => 'قطعہ کی تدوین حمایت شدہ نہیں ہے',
'sectioneditnotsupported-text' => 'اِس صفحہ میں قطعہ کی تدوین حمایت شدہ نہیں ہے.',
'permissionserrors' => 'اخطائے اجازت',
'permissionserrorstext' => 'درج ذیل {{PLURAL:$1|وجہ|وجوہات}} کی بناء پر آپ کو ایسا کرنے کی اجازت نہیں ہے:',
'permissionserrorstext-withaction' => 'درج ذیل {{PLURAL:$1|وجہ|وجوہات}} کی بناء پر آپ کو $2 کرنے کی اجازت نہیں ہے:',
'recreate-moveddeleted-warn' => "''' انتباہ: آپ ایک گزشتہ حذف شدہ صفحہ دوبارہ تخلیق کررہے ہیں. '''

آپ کو اِس بات پر غور کرنا چاہئے کہ آیا اِس صفحہ کی تدوین جاری رکھنا موزوں ہے یا نہیں.
صفحہ کا نوشتۂ حذف شدگی و منتقلی یہاں سہولت کی خاطر مہیّا کیا جارہا ہے:",
'moveddeleted-notice' => 'یہ ایک حذف شدہ صفحہ ہے.
صفحہ کا نوشتۂ حذف شدگی و منتقلی ذیل میں بطورِ حوالہ دیا جارہا ہے.',
'log-fulllog' => 'پورا نوشتہ دیکھئے',
'edit-gone-missing' => 'صفحہ تجدید نہیں کیا جاسکتا.
لگتا ہے یہ حذف ہوچکا ہے.',
'edit-conflict' => 'تنازعۂ تدوین.',
'edit-no-change' => 'آپ کی تدوین کو نظرانداز کردیا گیا، کیونکہ متن میں کوئی تبدیلی نہیں ہوئی تھی.',
'postedit-confirmation' => 'آپ کی ترمیم محفوظ ہوگئی۔',
'edit-already-exists' => 'نیا صفحہ تخلیق نہیں کیا جاسکتا.
یہ پہلے سے موجود ہے.',

# Content models
'content-model-text' => 'سادہ متن',
'content-model-javascript' => 'جاوا اسکرپٹ',

# History pages
'viewpagelogs' => 'اس صفحہ کیلیے نوشتہ جات دیکھیے',
'nohistory' => 'اِس صفحہ کیلئے کوئی تدوینی تاریخچہ موجود نہیں ہے.',
'currentrev' => 'حـالیـہ تـجدید',
'currentrev-asof' => 'حالیہ نظرثانی بمطابق $1',
'revisionasof' => 'تـجدید بـمطابق $1',
'revision-info' => 'نظرثانی بتاریخ $1 از $2',
'previousrevision' => '←پرانی تدوین',
'nextrevision' => '→اگلا اعادہ',
'currentrevisionlink' => 'حالیہ نظرثانی',
'cur' => ' رائج',
'next' => 'آگے',
'last' => 'سابقہ',
'page_first' => 'پہلا',
'page_last' => 'آخری',
'histlegend' => "انتخاب: مختلف نسخوں کا موازنہ کرنے کیلیے، پیامی خانوں کو نشان زد کر کے نیچے دیے گئے بٹن پر کلک کیجیئے۔

'''علامات:'''

(رائج) = موجودہ متن سے اخـتلاف، (سابقہ) = گزشتہ متن سے اختلاف ، م = معمولی ترمیم۔",
'history-fieldset-title' => 'تاریخ ملاحظہ کریں',
'history-show-deleted' => 'صرف حذف شدہ',
'histfirst' => 'قدیم ترین',
'histlast' => 'تازہ ترین',
'historysize' => '({{PLURAL:$1|1 لکمہ|$1 لکم}})',
'historyempty' => '(خالی)',

# Revision feed
'history-feed-title' => 'تاریخچۂ نظرثانی',
'history-feed-description' => 'ویکی پر اِس صفحہ کا تاریخچۂ نظرثانی',
'history-feed-item-nocomment' => 'بہ $2 $1',
'history-feed-empty' => 'درخواست شدہ صفحہ موجود نہیں.
یا تو یہ ویکی سے حذف کیا گیا ہے اور یا اِس کا نام تبدیل کردیا گیا ہے.
آپ متعلقہ نئے صفحات کیلئے [[Special:Search|ویکی پر تلاش]] کرسکتے ہیں.',

# Revision deletion
'rev-deleted-comment' => '(تبصرہ ہٹایا گیا ہے)',
'rev-delundel' => 'دکھاؤ/چھپاؤ',
'rev-showdeleted' => 'دکھاؤ',
'revisiondelete' => 'نظرثانی حذف کریں/واپس لائیں',
'revdelete-nooldid-title' => 'ناقص مقصود نظرثانی',
'revdelete-nologtype-title' => 'کوئی نوشتی قِسم مہیّا نہیں کی گئی',
'revdelete-nologid-title' => 'ناقص اندراجِ نوشتہ',
'revdelete-show-file-submit' => 'ہاں',
'revdelete-selected' => "'''[[:$1]] کی {{PLURAL:$2|منتخب نظرثانی|منتخب نظرثانیاں}}:'''",
'logdelete-selected' => "'''{{PLURAL:$1|منتخب واقعۂ نوشتہ|منتخب واقعاتِ نوشتہ}}:'''",
'revdelete-confirm' => 'برائے مہربانی! یقین دِہانی کرلیجئے کہ آپ واقعی ایسا کرنا چاہتے ہیں، آپ اِس کے نتائج سے باخبر ہیں، اور آپ یہ [[{{MediaWiki:Policy-url}}|پالیسی]] کے مطابق کررہے ہیں.',
'revdelete-legend' => 'رویتی پابندیاں لگائیں',
'revdelete-hide-text' => 'نظرثانی متن چھپاؤ',
'revdelete-hide-image' => 'مشمولاتِ ملف چھپاؤ',
'revdelete-hide-name' => 'عمل اور ہدف کو چھپاؤ',
'revdelete-hide-comment' => 'ترمیمی تبصرہ چھپاؤ',
'revdelete-hide-user' => 'ترمیم کار کا اسمِ صارف / آئی.پی پتہ چُھپاؤ',
'revdelete-radio-same' => '(تبدیل مت کرو)',
'revdelete-radio-set' => 'ہاں',
'revdelete-radio-unset' => 'نہیں',
'revdelete-unsuppress' => 'بحال شدہ نظرثانیوں پر پابندیاں ہٹاؤ',
'revdelete-log' => 'وجہ',
'revdelete-success' => "'''رؤیتِ نظرثانی کی تجدید کامیابی سے ہوئی.'''",
'logdelete-success' => "'''نوشتۂ رویت کامیابی سے مرتب.'''",
'logdelete-failure' => "'''نوشتۂ رویت مرتب نہیں کیا جاسکتا:'''

$1",
'revdel-restore' => 'ظاہریت تبدیل کرو',
'revdel-restore-deleted' => 'حذف شدہ نظرثانیاں',
'revdel-restore-visible' => 'نظر آنے والی نظرثانیاں',
'pagehist' => 'تاریخچۂ صفحہ',
'deletedhist' => 'حذف شدہ تاریخچہ',
'revdelete-otherreason' => 'دوسری/اضافی وجہ:',
'revdelete-reasonotherlist' => 'کوئی اَور وجہ',
'revdelete-edit-reasonlist' => 'تحذیفی وجوہات کی تدوین',
'revdelete-offender' => 'نظرثانی مصنف:',

# History merging
'mergehistory' => 'تواریخِ صفحہ کا انضمام',
'mergehistory-box' => 'دو صفحات کی نظرثانیوں کا انضمام:',
'mergehistory-from' => 'مآخذ صفحہ:',
'mergehistory-into' => 'صفحۂ مقصود:',
'mergehistory-go' => 'ضم پذیر ترامیم دِکھاؤ',
'mergehistory-submit' => 'نظرثانیاں ضم کرو',
'mergehistory-empty' => 'نظرثانیاں ضم نہیں کی جاسکتیں.',
'mergehistory-no-source' => 'مآخذ صفحہ $1 موجود نہیں.',
'mergehistory-no-destination' => 'مقصود صفحہ $1 موجود نہیں.',
'mergehistory-invalid-source' => 'مآخذ صفحہ کا عنوان صحیح ہونا چاہئے.',
'mergehistory-invalid-destination' => 'مقصود صفحہ کا عنوان صحیح ہونا چاہئے.',
'mergehistory-same-destination' => 'مآخذ اور مقصود صفحات ایک جیسے نہیں ہوسکتے.',
'mergehistory-reason' => 'وجہ:',

# Merge log
'mergelog' => 'نوشتہ کا انضمام',
'revertmerge' => 'غیر ضم',

# Diffs
'history-title' => '"$1" کا نظرثانی تاریخچہ',
'difference-multipage' => '(فرق مابین صفحات)',
'lineno' => 'لکیر $1:',
'compareselectedversions' => 'منتخب متـن کا موازنہ',
'editundo' => 'استرجع',

# Search results
'searchresults' => 'تلاش کا نتیجہ',
'searchresults-title' => 'نتائجِ تلاش برائے "$1"',
'searchresulttext' => 'ویکیپیڈیا میں تلاش کے بارے میں مزید معلومات کے لیۓ، ویکیپیڈیا میں تلاش کا صفحہ دیکھیۓ۔',
'searchsubtitle' => 'آپ کی تلاش برائے \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" سے شروع ہونے والے تمام صفحات]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" سے مربوط تمام صفحات]])',
'searchsubtitleinvalid' => "آپ کی تلاش براۓ '''$1'''",
'notitlematches' => 'کوئی بھی مماثل عنوان صفحہ نہیں ملا',
'notextmatches' => 'کوئی بھی مماثل متن موجود نہیں',
'prevn' => 'پچھلے {{PLURAL:$1|$1}}',
'nextn' => 'اگلے {{PLURAL:$1|$1}}',
'prevn-title' => 'پچھلے $1 {{PLURAL:$1|نتیجہ|نتائج}}',
'nextn-title' => 'آگے $1 {{PLURAL:$1|نتیجہ|نتائج}}',
'shown-title' => 'فی صفحہ $1 {{PLURAL:$1|نتیجہ|نتائج}} دِکھاؤ',
'viewprevnext' => 'دیکھیں($1 {{int:pipe-separator}} $2) ($3)۔',
'searchmenu-legend' => 'اختیاراتِ تلاش',
'searchmenu-exists' => "'''اِس ویکی پر \"[[:\$1]]\" نامی ایک صفحہ موجود ہے'''",
'searchmenu-new' => "'''اِس ویکی پر صفحہ \"[[:\$1]]\" تخلیق کیجئے!'''",
'searchprofile-articles' => 'مشمولاتی صفحات',
'searchprofile-project' => 'صفحاتِ مدد و منصوبہ',
'searchprofile-images' => 'کثیرالوسیط',
'searchprofile-everything' => 'سب کچھ',
'searchprofile-advanced' => 'پیشرفتہ',
'searchprofile-articles-tooltip' => '$1 میں تلاش',
'searchprofile-project-tooltip' => '$1 میں تلاش',
'searchprofile-images-tooltip' => 'تلاش برائے ملفات',
'searchprofile-everything-tooltip' => ' تلاش تمام مشمولات (بشمول تبادلۂ خیال صفحات) میں',
'searchprofile-advanced-tooltip' => 'اپنی پسند کے جائے نام میں تلاش',
'search-result-size' => '$1 ({{PLURAL:$2|1 لفظ|$2 الفاظ}})',
'search-result-category-size' => '{{PLURAL:$1|1 رُکن|$1 اراکین}} ({{PLURAL:$2|1 ذیلی زمرہ|$2 ذیلی زمرہ جات}}, {{PLURAL:$3|1 ملف|$3 ملفات}})',
'search-result-score' => 'توافق: $1%',
'search-redirect' => '(رجوع مکرر $1)',
'search-section' => '(حصہ $1)',
'search-suggest' => 'کیا آپ کا مطلب تھا: $1',
'search-interwiki-caption' => 'ساتھی منصوبے',
'search-interwiki-default' => '$1 نتائج:',
'search-interwiki-more' => '(مزید)',
'search-relatedarticle' => 'متعلقہ',
'mwsuggest-disable' => 'AJAX تجاویز غیرفعال',
'searchrelated' => 'متعلقہ',
'searchall' => 'تمام',
'nonefound' => "'''یاددہانی''': عموماً صرف چند جائے نام تلاش کئے جاتے ہیں۔
تمام مواد (بشمول تبادلۂ خیال صحات، سانچہ جات وغیرہ) میں تلاش کیلئے اپنے استفسار سے پہلے ''all:'' لگائیے، یا اپنی پسند کا جائے نام بطور سابقہ استعمال کیجئے۔",
'search-nonefound' => 'استفسار کے مطابق نتائج نہیں ملے.',
'powersearch' => 'پیشرفتہ تلاش',
'powersearch-legend' => 'پیشرفتہ تلاش',
'powersearch-ns' => 'جائے نام میں تلاش:',
'powersearch-redir' => 'فہرستِ رجوع مکرر',
'powersearch-field' => 'تلاش برائے',
'powersearch-togglelabel' => 'جانچ',
'powersearch-toggleall' => 'تمام',
'powersearch-togglenone' => 'کوئی نہیں',
'search-external' => 'بیرونی تلاش',
'searchdisabled' => '{{SITENAME}} تلاش غیرفعال.
آپ فی الحال گوگل کے ذریعے تلاش کرسکتے ہیں.
یاد رکھئے کہ اُن کے {{SITENAME}} اشاریے ممکناً پرانے ہوسکتے ہیں.',

# Preferences page
'preferences' => 'ترجیحات',
'mypreferences' => 'میری ترجیہات',
'prefs-edits' => 'تدوینات کی تعداد:',
'prefsnologin' => 'نا داخل شدہ حالت',
'prefsnologintext' => 'ترجیحات ترتیب دینے کیلئے <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} داخل نوشتہ]</span> ہونا لازمی ہے.',
'changepassword' => 'کلمۂ شناخت تبدیل کریں',
'prefs-skin' => 'جِلد',
'skin-preview' => 'پیش منظر',
'datedefault' => 'کوئی ترجیحات نہیں',
'prefs-datetime' => 'تاریخ و وقت',
'prefs-user-pages' => 'صارف صفحات',
'prefs-personal' => 'نمایۂ صارف',
'prefs-rc' => 'حالیہ تبدیلیاں',
'prefs-watchlist' => 'زیرِنظر فہرست',
'prefs-watchlist-days' => 'زیرِنظر فہرست میں نظر آنے والے ایام:',
'prefs-watchlist-days-max' => 'Maximum $1 {{PLURAL:$1|day|days}}',
'prefs-watchlist-edits' => 'عریض زیرِنظرفہرست میں نظر آنے والی تبدیلیوں کی زیادہ سے زیادہ تعداد:',
'prefs-watchlist-edits-max' => '(زیادہ سے زیادہ تعداد: 1000)',
'prefs-misc' => 'دیگر',
'prefs-resetpass' => 'کلمۂ شناخت تبدیل کیجئے',
'prefs-email' => 'اختیاراتِ برقی ڈاک',
'prefs-rendering' => 'ظاہریت',
'saveprefs' => 'محفوظ',
'resetprefs' => 'نامحفوظ تبدیلیاں صاف کرو',
'restoreprefs' => 'تمام بےنقص ترتیبات بحال کیجئے',
'prefs-editing' => 'تدوین',
'rows' => 'صفیں:',
'columns' => 'قطاریں:',
'searchresultshead' => 'تلاش',
'stub-threshold-disabled' => 'غیر فعال',
'recentchangesdays' => 'حالیہ تبدیلیوں میں دکھائی جانے والے ایّام:',
'recentchangesdays-max' => '(زیادہ سے زیادہ $1 {{PLURAL:$1|دن|ایام}})',
'recentchangescount' => 'دکھائی جانے والی ترامیم کی تعداد:',
'prefs-help-recentchangescount' => 'اِس میں حالیہ تبدیلیاں، تواریخِ صفحہ اور نوشتہ جات شامل ہیں.',
'savedprefs' => 'آپ کی ترجیحات محفوظ ہوگئیں۔',
'timezonelegend' => 'منطقۂ وقت:',
'localtime' => 'مقامی وقت:',
'timezoneregion-africa' => 'افریقہ',
'timezoneregion-america' => 'امریکہ',
'timezoneregion-antarctica' => 'انٹارکٹیکا',
'timezoneregion-arctic' => 'قطب شمالی',
'timezoneregion-asia' => 'ایشیاء',
'timezoneregion-atlantic' => 'بحر اوقیانوس',
'timezoneregion-australia' => 'آسٹریلیا',
'timezoneregion-europe' => 'یورپ',
'timezoneregion-indian' => 'بحر ہند',
'timezoneregion-pacific' => 'بحر الکاہل',
'allowemail' => 'دوسرے صارفین کو برقی خظ لکھنے کا اختیار دیں',
'prefs-searchoptions' => 'اختیاراتِ تلاش',
'prefs-namespaces' => 'جائے نام',
'default' => 'طے شدہ',
'prefs-files' => 'مسلات',
'prefs-custom-css' => 'خودساختہ CSS',
'prefs-custom-js' => 'خودساختہ JS',
'prefs-emailconfirm-label' => 'برقی پتہ کی تصدیق:',
'youremail' => '٭ برقی خط',
'username' => 'اسم صارف',
'uid' => 'صارف نمبر:',
'prefs-memberingroups' => '{{PLURAL:$1|گروہ|گروہوں}} کا رُکن:',
'prefs-registration' => 'وقتِ اندراج:',
'yourrealname' => '* اصلی نام',
'yourlanguage' => 'زبان:',
'yourvariant' => 'متغیّر:',
'yournick' => 'دستخط',
'badsig' => 'ناقص خام دستخط.
HTML tags جانچئے.',
'badsiglength' => 'آپ کا دستخط کافی طویل ہے.
یہ $1 {{PLURAL:$1|حرف|حروف}} سے زیادہ نہیں ہونا چاہئے.',
'yourgender' => 'جنس:',
'gender-unknown' => 'غیرمختص شدہ',
'gender-male' => 'مرد',
'gender-female' => 'عورت',
'prefs-help-gender' => 'اختیاری: مصنع‌لطیف کی طرف سے صحیح‌الجنس تخاطب کیلئے استعمال ہوتا ہے. یہ معلومات عام ہوگی.',
'email' => 'برقی خط',
'prefs-help-realname' => 'حقیقی نام اختیاری ہے.
اگر آپ اِسے مہیّا کرتے ہیں، تو اِسے آپ کے کام کیلئے آپ کو انتساب دینے کیلئے استعمال کیا جائے گا.',
'prefs-help-email' => 'برقی ڈاک کا پتہ اختیاری ہے، لیکن یہ اُس وقت مفید ثابت ہوسکتا ہے جب آپ اپنا پارلفظ بھول گئے ہوں.',
'prefs-help-email-others' => 'آپ یہ بھی منتخب کرسکتے ہیں کہ دوسرے صارفین آپ کے تبادلۂ خیال صفحہ پر ایک ربط کے ذریعے آپ کو برقی ڈاک بھیجیں.
جب دوسرے صارفین آپ سے رابطہ کرتے ہیں تو آپ کا برقی ڈاک کا پتہ افشا نہیں کیا جاتا۔',
'prefs-help-email-required' => 'برقی ڈاک پتہ چاہئے.',
'prefs-info' => 'بنیادی معلومات',
'prefs-i18n' => 'بین الاقوامیت',
'prefs-signature' => 'دستخط',
'prefs-dateformat' => 'شکلبندِ تاریخ',
'prefs-advancedediting' => 'اعلی اختیارات',
'prefs-advancedrc' => 'اعلی اختیارات',
'prefs-advancedrendering' => 'اعلی اختیارات',
'prefs-advancedsearchoptions' => 'اعلی اختیارات',
'prefs-advancedwatchlist' => 'اعلی اختیارات',
'prefs-diffs' => 'فروق',

# User rights
'userrights' => 'حقوقِ صارف کی نظامت',
'userrights-lookup-user' => 'گروہائے صارف کا انتظام',
'userrights-user-editname' => 'کوئی اسم‌صارف داخل کیجئے:',
'editusergroup' => 'ترمیم گروہائے صارف',
'editinguser' => "تبدیلئ حقوق برائے صارف '''[[صارف:$1|$1]]''' $2",
'userrights-editusergroup' => 'ترمیم گروہائے صارف',
'saveusergroups' => 'گروہائے صارف محفوظ',
'userrights-groupsmember' => 'رکنِ:',
'userrights-groupsmember-auto' => 'اعتباری صارف در',
'userrights-groups-help' => 'آپ ان گروہان میں تبدیلی کرسکتے ہیں جن سے صارف متعلق ہے: 
* نشان زد خانہ کا مطلب یہ ہے کہ صارف کا تعلق اس گروہ سے ہے۔ 
* غیر نشان زد خانہ کا مطلب یہ ہے کہ صارف کا تعلق اس گروہ سے نہیں ہے۔ 
* یہ * علامت اس بات کا اشارہ ہے کہ آپ اس گروہ کو نہیں ہٹا سکتے جسے ایک مرتبہ آپ نے شامل کردیا ہو، یا اس کے بر عکس۔',
'userrights-reason' => 'وجہ:',
'userrights-no-interwiki' => 'دوسرے ویکیوں پر حقوقِ صارف میں ترمیم کی آپ کو اجازت نہیں ہے.',
'userrights-changeable-col' => 'مجموعات جو آپ تبدیل کرسکتے ہیں',
'userrights-unchangeable-col' => 'مجموعات جو آپ تبدیل نہیں کرسکتے',

# Groups
'group' => 'گروہ:',
'group-user' => 'صارفین',
'group-autoconfirmed' => 'خود توثیق شدہ صارفین',
'group-bot' => 'روبالات',
'group-sysop' => 'منتظمین',
'group-bureaucrat' => 'مامورین اداری',
'group-suppress' => 'نگران',
'group-all' => '(تمام)',

'group-user-member' => 'صارف',
'group-autoconfirmed-member' => 'خودتصدیق شدہ صارف',
'group-bot-member' => 'خودکار صارف',
'group-sysop-member' => 'منتظم',
'group-bureaucrat-member' => '{{GENDER:$1|مامور اداری}}',
'group-suppress-member' => '{{GENDER:$1|نگران}}',

'grouppage-user' => '{{ns:project}}:صارفین',
'grouppage-autoconfirmed' => '{{ns:project}}:خود توثیق شدہ صارف',
'grouppage-bot' => '{{ns:project}}:روبہ جات',
'grouppage-sysop' => '{{ns:project}}:منتظمین',

# Rights
'right-upload' => 'ملفات زبراثقال (اپ لوڈ) کریں',
'right-delete' => 'صفحات حذف کریں',
'right-sendemail' => 'دیگر صارفین کو برقی ڈاک بھیجیں',

# Special:Log/newusers
'newuserlogpage' => 'نوشتۂ آمد صارف',
'newuserlogpagetext' => 'یہ نۓ صارفوں کی آمد کا نوشتہ ہے',

# User rights log
'rightslog' => 'نوشتہ صارفی اختیارات',
'rightslogtext' => 'یہ صارفی اختیارات میں تبدیلیوں کا نوشتہ ہے۔',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'اس صفحہ میں ترمیم کریں',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|تبدیلی|تبدیلیاں}}',
'recentchanges' => 'حالیہ تبدیلیاں',
'recentchanges-legend' => 'اِختیاراتِ حالیہ تبدیلیاں',
'recentchanges-summary' => 'اس صفحے پر ویکی میں ہونے والی تازہ تریں تبدیلیوں کا مشاہدہ کیجیۓ۔',
'recentchanges-feed-description' => 'اس خورد میں ویکی پر ہونے والی تازہ تریں تبدیلیوں کا مشاہدہ کیجیۓ۔',
'recentchanges-label-newpage' => 'اِس ترمیم نے نیا صفحہ تخلیق کردیا',
'recentchanges-label-minor' => 'یہ ایک معمولی ترمیم ہے',
'recentchanges-label-bot' => 'یہ ایک روبالہ سے سرانجام شدہ ترمیم ہے',
'recentchanges-label-unpatrolled' => 'اس ترمیم کی اب تک مراجعت نہیں کی گئی',
'rcnote' => "درج ذیل گزشتہ {{PLURAL:$2|دِن|'''$2''' ایام}} میں ہونے والی {{PLURAL:$1|'''ایک''' تبدیلی ہے|آخری '''$1''' تبدیلیاں ہیں}}، $5، $4.",
'rcnotefrom' => "ذیل میں '''$2''' سے کی گئی تبدیلیاں ہیں ('''$1''' تبدیلیاں دکھائی جارہی ہیں)۔",
'rclistfrom' => '$1 سےنئی تبدیلیاں دکھانا شروع کریں',
'rcshowhideminor' => 'معمولی ترامیم $1',
'rcshowhidebots' => 'خودکار صارف $1',
'rcshowhideliu' => 'داخل شدہ صارف $1',
'rcshowhideanons' => 'گمنام صارف $1',
'rcshowhidepatr' => '$1 مراجعت شدہ ترامیم',
'rcshowhidemine' => 'ذاتی ترامیم $1',
'rclinks' => 'آخری $2 روز میں ہونے والی $1 تبدیلیوں کا مشاہدہ کریں<br />$3',
'diff' => 'فرق',
'hist' => 'تاریخچہ',
'hide' => 'چھـپائیں',
'show' => 'دکھاؤ',
'minoreditletter' => 'م',
'newpageletter' => 'نیا ..',
'boteditletter' => ' خودکار',
'rc-enhanced-expand' => 'تفصیلات دِکھائیں (JavaScript درکار)',
'rc-enhanced-hide' => 'تفصیلات چھپائیے',

# Recent changes linked
'recentchangeslinked' => 'متعلقہ تبدیلیاں',
'recentchangeslinked-feed' => 'متعلقہ تبدیلیاں',
'recentchangeslinked-toolbox' => 'متعلقہ تبدیلیاں',
'recentchangeslinked-title' => '"$1" سے متعلقہ تبدیلیاں',
'recentchangeslinked-summary' => 'یہ ان تبدیلیوں کی فہرست ہے جو حال ہی میں کسی مخصوص صفحہ سے مربوط صفحات (یا مخصوص زمرہ کے اراکین) میں کی گئی ہیںـ 

[[SpecialWatchlist | آپ کی زیر نظر فہرست]] میں یہ صفحات متجل (bold) نظر آئیں گےـ',
'recentchangeslinked-page' => 'صفحۂ منصوبہ دیکھئے',

# Upload
'upload' => 'فائل اثقال',
'uploadbtn' => 'زبراثقال ملف (اپ لوڈ فائل)',
'reuploaddesc' => 'زبراثقال ورقہ (فارم) کیجانب واپس۔',
'uploadnologin' => 'آپ داخل شدہ حالت میں نہیں',
'uploadnologintext' => 'زبراثقال ملف (فائل اپ لوڈ) کے لیۓ آپکو  [[Special:UserLogin|داخل شدہ]] حالت میں ہونا لازم ہے۔',
'uploadtext' => "
'''یادآوری''': اگر آپ اپنی ملف (فائل) زبراثقال کرتے وقت ، خلاصہ کے خانے میں ،  درج ذیل دو باتوں کی وضاحت نہیں کرتے تو ملف کو حذف کیا جاسکتا ہے:
#ملف یا فائل کا '''مـاخـذ''' ، یعنی:
#*اگر یہ آپ نے خود تخلیق کی ہے تو بیان کردیجیۓ۔
#*اگر یہ روۓ خط (آن لائن) دستیاب ہے ، تو اس وقوع یعنی سائٹ کا  '''رابطہ (لنک)''' دیجیۓ۔
#*اگر آپ نے اسے کسی دوسری زبان کے {{SITENAME}} سے لیا ہے تو اسکا نام تحریر کردیجیۓ۔
#صاحب ِحق ِطبع و نشر اور ملف کے اجازہ (لائسنس) کے بارے میں:
#*ملف کے اجازہ کے بارے میں یہ تحریر کیجیۓ کہ اسکی موجودہ حیثیت کیا ہے۔
#*اگر آپ خود اسکا حق ِطبع و نشر رکھتے ہیں تو آپ پر لازم ہے کہ آپ اسے ٹ [[دائرۂ عام]] ن (پبلک ڈومین) میں بھی آذاد کردیں۔

جب کوئی صارف مستقل ایسی ملف زبراثقال کرتا رہے کہ جس کے اجازہ کے بارے میں غلط بیانی کی گئی ہو یا وہ مستقل ایسے عکس زبراثقال کرتا رہے کہ جنکے بارے میں کوئی بیان تحریر نہ کیا گیا ہو تو ایسی صورت میں پابندی لگاۓ جانے کا قوی امکان موجود ہے۔

مِلَف (فائل) بھیجنے کیلیے درج ذیل ورقہ (فارم) استعمال کیجیے، اگر آپ اب تک ارسال کردہ تصاویر کو دیکھنا یا تلاش کرنا چاہتے ہیں تو [[Special:FileList|ارسال کردہ تصاویر]] کے ربط پر جائیے۔ <br /> تمام ارسال و حذف کی گئی تصاویر کو [[Special:Log/upload|نوشتۂ منتقلی]] میں درج کر لیا جاتا ہے۔

تصویر کی منتقلی کے بعد، اسکو کسی صفحہ پر رکھنے کیلیے مندرجہ ذیل صورت میں رمـز (کوڈ) استعمال کیجیۓ۔

'''<nowiki>[[تصویر:ملف کا نام|متبادل متن]]</nowiki>'''

* مندرجہ بالا رموز آپ  انگریزی میں بھی درج کرسکتے ہیں، یعنی
<nowiki>[[Image:File name|Alt.text]]</nowiki>
* ملف کے ساتھ براہ راست رابطہ کیلیے
کی طرز میں ربط استعمال کیجیۓ۔ '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>'''
* ملف کا نام ؛ حرف ابجد کے لیۓ حساس ہے لہذا اگر زبراثقال کرتے وقت ملف کا نام -- name:JPG  ہے اور آپ رابطہ رکھتے وقت name:jpg یــا Name:jpg رکھتے ہیں تو ربط کام نہیں کرے گا",
'uploadlog' => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpage' => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpagetext' => 'درج ذیل میں حالیہ زبراثقال (اپ لوڈ) کی گئی املاف (فائلوں) کی فہرست دی گئی ہے۔',
'filedesc' => 'خلاصہ',
'fileuploadsummary' => 'خلاصہ :',
'filesource' => 'ذرائع',
'uploadedfiles' => 'زبراثقال ملف (فائل اپ لوڈ)',
'ignorewarning' => 'انتباہ نظرانداز کرتے ہوۓ بہرصورت ملف (فائل) کو محفوظ کرلیا جاۓ۔',
'ignorewarnings' => 'ہر انتباہ نظرانداز کردیا جاۓ۔',
'badfilename' => 'ملف (فائل) کا نام "$1" ، تبدیل کردیا گیا۔',
'fileexists' => 'اس نام سے ایک ملف (فائل) پہلے ہی موجود ہے، اگر آپ کو یقین نہ ہو کہ اسے حذف کردیا جانا چاہیۓ تو براہ کرم  <strong>[[:$1]]</strong> کو ایک نظر دیکھ لیجیۓ۔ [[$1|thumb]]',
'uploadwarning' => 'انتباہ بہ سلسلۂ زبراثقال',
'savefile' => 'فائل محفوظ کریں',
'uploadedimage' => 'زبراثقال (اپ لوڈ) براۓ "[[$1]]"',
'sourcefilename' => 'اسم ملف (فائل) کا منبع:',
'destfilename' => 'تعین شدہ اسم ملف:',
'watchthisupload' => 'یہ صفحہ زیر نظر کیجیۓ',

'license' => 'اجازہ:',
'license-header' => 'اجازہ کاری',

# Special:ListFiles
'listfiles' => 'فہرست فائل',

# File description page
'file-anchor-link' => 'مسل',
'filehist' => 'ملف کی تاریخ',
'filehist-help' => 'یہ دیکھنے کیلئے کہ کسی خاص وقت پر ملف کس طرح ظاہر ہوتا تھا اُس تاریخ یا وقت پر طق کیجئے۔',
'filehist-revert' => 'رجوع',
'filehist-current' => 'حالیہ',
'filehist-datetime' => 'تاریخ/وقت',
'filehist-thumb' => 'اظفورہ',
'filehist-user' => 'صارف',
'filehist-dimensions' => 'ابعاد',
'filehist-comment' => 'تبصرہ',
'imagelinks' => 'ملف کا استعمال',
'linkstoimage' => 'اِس ملف کے ساتھ درج ذیل {{PLURAL:$1|صفحہ مربوط ہے|$1 صفحات مربوط ہیں}}',
'nolinkstoimage' => 'ایسے کوئی صفحات نہیں جو اس ملف (فائل) سے رابطہ رکھتے ہوں۔',

# MIME search
'download' => 'زیراثقال (ڈاؤن لوڈ)',

# List redirects
'listredirects' => 'فہرست متبادل ربط',

# Unused templates
'unusedtemplates' => 'غیر استعمال شدہ سانچے',

# Random page
'randompage' => 'بےترتیب صفحہ',

# Statistics
'statistics' => 'اعداد و شمار',
'statistics-header-users' => 'ارکان کے اعداد و شمار',

'doubleredirects' => 'دوہرے متبادل ربط',

'brokenredirects' => 'نامکمل متبادل ربط',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|لکمہ|لکمہ جات}}',
'ncategories' => '{{PLURAL:$1|زمرہ|زمرہ جات}} $1',
'nmembers' => '{{PLURAL:$1|رکن|اراکین}}',
'lonelypages' => 'يتيم صفحات',
'lonelypagestext' => 'مندرجہ ذیل صفحات وہ صفحات ہیں جنھیں اس وکی میں موجود صفحوں سے کوئی ربط حاصل نہیں ہوپارہا۔',
'uncategorizedpages' => 'بے زمرہ صفحات',
'uncategorizedcategories' => 'بے زمرہ زمرہ جات',
'uncategorizedimages' => 'بے زمرہ تصاویر',
'unusedcategories' => 'غیر استعمال شدہ زمرہ جات',
'unusedimages' => 'غیر استعمال شدہ فائلیں',
'popularpages' => 'مقبول صفحات',
'wantedcategories' => 'طلب شدہ زمرہ جات',
'wantedpages' => 'درخواست شدہ مضامین',
'mostlinked' => 'سب سے زیادہ ربط والے مضامین',
'mostlinkedcategories' => 'سب سے زیادہ ربط والے زمرہ جات',
'mostcategories' => 'سب سے زیادہ زمرہ جات والے مضامین',
'mostimages' => 'سب سے زیادہ استعمال کردہ تصاویر',
'mostrevisions' => 'زیادہ تجدید نظر کیے جانے والے صفحات',
'prefixindex' => 'تمام صفحات بمع سابقہ',
'shortpages' => 'چھوٹے صفحات',
'longpages' => 'طویل ترین صفحات',
'deadendpages' => 'مردہ صفحات',
'listusers' => 'فہرست ارکان',
'usercreated' => '{{GENDER:$3|تخلیق شدہ}}  بتاریخ $1 بوقت $2',
'newpages' => 'جدید صفحات',
'ancientpages' => 'قدیم ترین صفحات',
'move' => 'منتقـل',
'movethispage' => 'یہ صفحہ منتقل کیجئے',
'pager-newer-n' => '{{PLURAL:$1|جدید 1|جدید $1}}',
'pager-older-n' => '{{PLURAL:$1|پُرانا 1|پُرانے $1}}',

# Book sources
'booksources' => 'کتابی وسائل',
'booksources-search-legend' => 'تلاش برائے مآخذاتِ کتاب',
'booksources-go' => 'چلو',

# Special:Log
'specialloguserlabel' => 'صارف:',
'speciallogtitlelabel' => 'عنوان:',
'log' => 'نوشتہ جات',

# Special:AllPages
'allpages' => 'تمام صفحات',
'alphaindexline' => '$1 تا $2',
'nextpage' => 'اگلا صفحہ ($1)',
'prevpage' => 'پچھلا صفحہ ($1)',
'allpagesfrom' => 'مطلوبہ حرف شروع ہونے والے صفحات کی نمائش:',
'allarticles' => 'تمام مقالات',
'allpagesprev' => 'پچھلا',
'allpagesnext' => 'اگلا',
'allpagessubmit' => 'چلو',
'allpagesprefix' => 'مطلوبہ سابقہ سے شروع ہونے والے صفحات کی نمائش:',

# Special:Categories
'categories' => 'زمرہ',
'categoriespagetext' => 'مندرجہ ذیل زمرہ جات اس وکی میں موجود ہیں۔
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:LinkSearch
'linksearch-line' => '$1 مربوط ہے $2 سے',

# Special:ListGroupRights
'listgrouprights-members' => '(اراکین کی فہرست)',

# Email user
'mailnologintext' => 'دیگر ارکان کو برقی خط ارسال کرنے کیلیۓ لازم ہے کہ آپ [[Special:UserLogin|داخل شدہ]] حالت میں ہوں اور آپ کی [[Special:Preferences|ترجیحات]] ایک درست برقی خط کا پتا درج ہو۔',
'emailuser' => 'صارف کو برقی خط لکھیں',
'defemailsubject' => '{{SITENAME}} سے برقی خط',
'noemailtext' => 'اس صارف نے برقی خط کے لیے کوئی پتہ فراہم نہیں کیا، یا یہ چاہتا ہے کا اس سے کوئی صارف رابطہ نہ کرے۔',
'emailsubject' => 'عنوان',
'emailmessage' => 'پیغام:',

# Watchlist
'watchlist' => 'میری زیرنظرفہرست',
'mywatchlist' => 'میری زیرنظرفہرست',
'watchlistfor2' => 'براۓ $1 ($2)',
'addedwatchtext' => "یہ صفحہ \"<nowiki>\$1</nowiki>\" آپکی [[Special:Watchlist|زیرنظر]] فہرست میں شامل کردیا گیا ہے۔ اب مستقل میں اس صفحے اور اس سے ملحقہ تبادلہ خیال کا صفحے میں کی جانے والی تبدیلوں کا اندراج کیا جاتا رہے گا، اور ان صفحات کی شناخت کو سہل بنانے کے لیۓ [[Special:حالیہ تبدیلیاں|حالیہ تبدیلیوں کی فہرست]] میں انکو '''مُتَجَل''' (bold) تحریر کیا جاۓ گا۔ <p> اگر آپ کسی وقت اس صفحہ کو زیرنظرفہرست سے خارج کرنا چاہیں تو اوپر دیۓ گۓ \"زیرنظرمنسوخ\" پر ٹک کیجیۓ۔",
'removedwatchtext' => 'صفحہ "[[:$1]]" آپ کی زیر نظر فہرست سے خارج کر دیا گیا۔',
'watch' => 'زیرنظر',
'watchthispage' => 'یہ صفحہ زیر نظر کیجیۓ',
'unwatch' => 'زیرنظرمنسوخ',
'watchlist-details' => 'آپ کی زیرِنظرفہرست پر {{PLURAL:$1|$1 صفحہ ہے|$1 صفحات ہیں}}، اِس میں تبادلۂ خیال صفحات کی تعداد شامل نہیں.',
'watchlistcontains' => 'آپ کی زیرنظرفہرست میں $1 صفحات ہیں۔',
'wlnote' => 'نیچےآخری $1 تبدیلیاں ہیں جو کے پیچھلے <b>$2</b> گھنٹوں میں کی گئیں۔',
'wlshowlast' => 'دکھائیں آخری $1 گھنٹے $2 دن $3',
'watchlist-options' => 'اختیارات برائے زیرِنظرفہرست',

'created' => 'بنا دیا گیا',
'changed' => 'تبدیل کردیاگیا',

# Delete
'deletepage' => 'صفحہ ضائع کریں',
'confirm' => 'یقین',
'excontent' => "'$1':مواد تھا",
'excontentauthor' => "حذف شدہ مواد: '$1' (اور صرف '[[Special:Contributions/$2|$2]]' نے حصہ ڈالا)",
'exblank' => 'صفحہ خالی تھا',
'historywarning' => 'انتباہ: جو صفحہ آپ حذف کرنے جارہے ہیں اس سے ایک تاریخچہ منسلک ہے۔',
'confirmdeletetext' => 'آپ نے اس صفحے کو اس سے ملحقہ تاریخچہ سمیت حذف کرنے کا ارادہ کیا ہے۔ براۓ مہربانی تصدیق کرلیجیۓ کہ آپ اس عمل کے نتائج سے بخوبی آگاہ ہیں، اور یہ بھی یقین کرلیجیۓ کہ آپ ایسا [[{{MediaWiki:Policy-url}}|ویکیپیڈیا کی حکمت عملی]] کے دائرے میں رہ کر کر رہے ہیں۔',
'actioncomplete' => 'اقدام تکمیل کو پہنچا',
'actionfailed' => 'عمل ناکام',
'deletedtext' => '"$1" کو حذف کر دیا گیا ہے ۔
حالیہ حذف شدگی کے تاریخ نامہ کیلیۓ  $2  دیکھیۓ',
'dellogpage' => 'نوشتۂ حذف شدگی',
'dellogpagetext' => 'حالیہ حذف شدگی کی فہرست درج ذیل ہے۔',
'deletionlog' => 'نوشتۂ حذف شدگی',
'deletecomment' => 'وجہ:',
'deleteotherreason' => 'دوسری/اِضافی وجہ:',
'deletereasonotherlist' => 'دوسری وجہ',

# Rollback
'rollback' => 'ترمیمات سابقہ حالت پرواپس',
'rollback_short' => 'واپس سابقہ حالت',
'rollbacklink' => 'واپس سابقہ حالت',
'rollbackfailed' => 'سابقہ حالت پر واپسی ناکام',
'cantrollback' => 'تدوین ثانی کا اعادہ نہیں کیا جاسکتا؛ کیونکہ اس میں آخری بار حصہ لینے والا ہی اس صفحہ کا واحد کاتب ہے۔',

# Protect
'protectlogpage' => 'نوشتۂ محفوظ شدگی',
'protectedarticle' => '"[[$1]]" کومحفوظ کردیا',
'unprotectedarticle' => '"[[$1]]" کوغیر محفوظ کیا',
'prot_1movedto2' => '[[$1]] بجانب [[$2]] منتقل',
'protectcomment' => 'وجہ:',
'protect-default' => 'تمام صارفین کو اہل بناؤ',
'protect-level-sysop' => 'صرف منتظمین',

# Undelete
'undelete' => 'ضائع کردہ صفحات دیکھیں',
'undeletepage' => 'معائنہ خذف شدہ صفحات',
'undeletepagetitle' => "'''ذیل میں [[:$1|$1]] کے حذف شدہ ترامیم درج ہیں۔'''",
'viewdeletedpage' => 'حذف شدہ صفحات دیکھیے',
'undelete-fieldset-title' => 'ترامیم بحال کریں',
'undeletehistory' => 'اگر آپ اس صفحہ کو بحال کرتے ہیں، تو اس صفحہ کے تاریخچہ میں تمام ترامیم بھی بحال ہوجائیگی۔
اگر حذف شدگی کے بعد کوئی نیا صفحہ اسی نام سے تخلیق کیا گیا ہو، تو تمام بحال شدہ ترامیم گذشتہ تاریخچہ میں ظاہر ہوگی۔',
'undeleterevdel' => 'بحالیٔ صفحہ کا اقدام مکمل نہیں ہوگا اگر اس کا تنیجہ صفحہ کے اوپر کے حصہ کی ترمیم یا ملف کا اعادہ جزوی طور پر حذف کیا جارہا ہو۔
ایسی صورت میں لازمی طور آپ حالیہ حذف شدہ اعادے کو ظاہر کریں۔',
'undeletebtn' => 'بحال',
'undeletelink' => 'دیکھو/بحال کرو',
'undeleteviewlink' => 'دکھاؤ',
'undeleteinvert' => 'انتخاب بالعکس',
'undeletecomment' => 'وجہ:',

# Namespace form on various pages
'namespace' => 'جاۓ نام:',
'invert' => 'انتخاب بالعکس',
'blanknamespace' => '(مرکز)',

# Contributions
'contributions' => 'صارف کا حصہ',
'contributions-title' => 'مساہماتِ صارف برائے $1',
'mycontris' => 'میرا حصہ',
'contribsub2' => 'براۓ $1 ($2)',
'uctop' => ' (اوپر)',
'month' => 'مہینہ (اور اُس سے قبل):',
'year' => 'سال (اور اُس سے قبل):',

'sp-contributions-newbies' => 'صرف نئے کھاتوں کے مساہمات دکھاؤ',
'sp-contributions-blocklog' => 'نوشتۂ پابندی',
'sp-contributions-uploads' => 'اثقالات',
'sp-contributions-logs' => 'نوشتہ جات',
'sp-contributions-talk' => 'گفتگو',
'sp-contributions-userrights' => 'صارف کے حقوق کا انتظام',
'sp-contributions-search' => 'تلاش برائے مساہمات',
'sp-contributions-username' => 'آئی.پی پتہ یا اسمِ صارف:',
'sp-contributions-toponly' => 'صرف حالیہ ترین نظرثانی ترمیمات دِکھاؤ',
'sp-contributions-submit' => 'تلاش',

# What links here
'whatlinkshere' => 'ادھر کونسا ربط ہے',
'whatlinkshere-title' => '"$1" سے مربوط صفحات',
'whatlinkshere-page' => 'صفحہ:',
'linkshere' => "'''[[:$1]]''' سے درج ذیل صفحات مربوط ہیں:",
'nolinkshere' => "'''[[:$1]]''' سے کوئی روابط نہیں۔",
'isredirect' => 'لوٹایا گیا صفحہ',
'istemplate' => 'شامل شدہ',
'isimage' => 'ربطِ ملف',
'whatlinkshere-links' => 'روابط ←',
'whatlinkshere-hideredirs' => 'رجوع مکررات $1',
'whatlinkshere-hidetrans' => 'تضمینات',
'whatlinkshere-hidelinks' => 'روابط $1',
'whatlinkshere-hideimages' => 'روابطِ تصاویر $1',
'whatlinkshere-filters' => 'فلٹرذ',

# Block/unblock
'blockip' => 'داخلہ ممنوع براۓ صارف',
'ipbreason' => 'وجہ:',
'ipbsubmit' => 'اس صارف کا داخلہ ممنوع کریں',
'ipboptions' => '2 گھنٹے:2 hours,1 یوم:1 day,3 ایام:3 days,1 ہفتہ:1 week,2 ہفتے:2 weeks,1 مہینہ:1 month,3 مہینے:3 months,6 مہینے:6 months,1 سال:1 year,لامحدود:infinite',
'ipblocklist' => 'ممنوع صارفین',
'blocklink' => 'پابندی لگائیں',
'unblocklink' => 'پابندی ختم',
'change-blocklink' => 'پابندی میں تبدیلی',
'contribslink' => 'شـراکـت',
'blocklogpage' => 'نوشتۂ پابندی',
'block-log-flags-nocreate' => 'کھاتے کی تخلیق غیرفعال',

# Move page
'move-page' => 'منتقلی',
'move-page-legend' => 'منتقلئ صفحہ',
'movepagetext' => "نیچے دیا گیا تشکیلہ (فـارم) استعمال کرکے اس صفحہ کا عنوان دوبارہ منتخب کیا جاسکتا ہے، ساتھ ہی اس سے منسلک تاریخچہ بھی نۓ نام پر منتقل ہوجاۓ گا۔ اسکے بعد سے اس صفحے کا پرانا نام ، نۓ نام کی جانب -- لوٹایا گیا صفحہ -- کی حیثیت اختیار کرلے گا۔ لیکن یادآوری کرلیجیۓ دیگر صفحات پر ، پرانے صفحہ کی جانب دیۓ گۓ روابط (لنکس) تبدیل نہیں ہونگے؛ اس بات کو یقینی بنانا ضروری ہے کہ کوئی دوہرا یا شکستہ -- پلٹایا گیا ربط -- نہ رہ جاۓ۔

لہذا یہ یقینی بنانا آپکی ذمہ داری ہے کہ تمام روابط درست صفحات کی جانب رہنمائی کرتے رہیں۔

یہ بات بھی ذہن نشین کرلیجیۓ کہ اگر نۓ منتخب کردہ نام کا صفحہ پہلے سے ہی موجود ہو تو ہوسکتا ہے کہ صفحہ منتقل نہ ہو ، ؛ ہاں اگر پہلے سے موجود صفحہ خالی ہے ، یا وہ صرف ایک -- لوٹایا گیا صفحہ -- ہو اور اس سے کوئی تاریخچہ منسلک نہ ہو تو منتقلی ہوجاۓ گی۔ گویا ، کسی خامی کی صورت میں آپ صفحہ کو دوبارہ اسی پرانے نام کی جانب منتقل کرسکتے ہیں اور اس طرح پہلے سے موجود کسی صفحہ میں کوئی حذف و خامی نہیں ہوگی۔

''' انـتـبـاہ !'''
 کسی اہم اور مقبول صفحہ کی منتقلی ، غیرمتوقع اور پریشان کن بھی ہی ہوسکتی ہے اس لیۓ ؛ منتقلی سے قبل براہ کرم یقین کرلیجۓ کہ آپ اسکے منطقی نتائج سے باخبر ہیں۔",
'movepagetext-noredirectfixer' => "درج ذیل ورقہ کے ذریعہ صفحہ کو نیا نام دیا جاسکتا ہے، اس کے ساتھ صفحہ کا تاریخچہ بھی منتقل ہوجائیگا۔
نئے عنوان کے جانب قدیم عنوان کو رجوع مکرر کردیا جائیگا۔

یقین کرلیں کہ [[Special:DoubleRedirects|مکرر]] یا [[Special:BrokenRedirects|شکستہ رجوع مکررات]] موجود نہیں ہیں۔
آپ اس بات کو یقینی بنانے کے ذمہ دار ہیں کہ روابط انہیں جگہوں سے مربوط ہیں جن کو فرض کیا گیا ہے۔

خیال رہے کہ یہ صفحہ منتقل '''نہیں''' ہوگا اگر نئے عنوان کے ساتھ صفحہ پہلے سے موجود ہو، سوائے اس کے کہ صفحہ خالی ہو اور اس کا گذشتہ ترمیمی تاریخچہ موجود نہ ہو۔
اس کا مطلب ہے آپ سے اگر غلطی ہوجائے تو آپ صفحہ کو اسی جگہ لوٹا سکتے ہیں، تاہم موجود صفحہ پر برتحریر (overwrite) نہیں کرسکتے۔

'''انتباہ!'''
کسی اہم اور مقبول صفحہ کی منتقلی، غیرمتوقع اور پریشان کن بھی ہی ہوسکتی ہے اس لیۓ؛ 
منتقلی سے قبل براہ کرم یقین کرلیجۓ کہ آپ اسکے منطقی نتائج سے باخبر ہیں۔",
'movearticle' => 'مـنـتـقـل کـریں',
'newtitle' => 'نـیــا عـنــوان',
'move-watch' => 'صفحہ زیر نظر',
'movepagebtn' => 'مـنـتـقـل',
'pagemovedsub' => 'انتقال کامیاب',
'movepage-moved' => '\'\'\'"$1" منتقل کردیا گیا بطرف "$2"\'\'\'',
'articleexists' => 'اس عنوان سے کوئی صفحہ پہلے ہی موجود ہے، یا آپکا منتخب کردہ نام مستعمل نہیں۔ براۓ مہربانی دوسرا نام منتخب کیجیۓ۔',
'movelogpage' => 'نوشتۂ منتقلی',
'movereason' => 'وجہ:',
'revertmove' => 'رجوع',
'delete_and_move' => 'حذف اور منتقل',
'delete_and_move_text' => '==حذف شدگی لازم==

منتقلی کے سلسلے میں انتخاب کردہ مضمون "[[:$1]]" پہلے ہی موجود ہے۔ کیا آپ اسے حذف کرکے منتقلی کیلیۓ راستہ بنانا چاہتے ہیں؟',
'delete_and_move_confirm' => 'ہاں، صفحہ حذف کر دیا جائے',
'delete_and_move_reason' => 'منتقلی کے سلسلے میں حذف',

# Export
'export' => 'برآمد صفحات',

# Namespace 8 related
'allmessages' => 'نظامی پیغامات',
'allmessagesname' => 'نام',
'allmessagesdefault' => 'طے شدہ متن',
'allmessagescurrent' => 'موجودہ متن',
'allmessagestext' => 'یہ میڈیاویکی: جاۓ نام میں دستیاب نظامی پیغامات کی فہرست ہے۔',

# Thumbnails
'thumbnail-more' => 'چوڑا کریں',

# Special:Import
'import' => 'درآمد صفحات',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'آپ کا صارفی صفحہ',
'tooltip-pt-mytalk' => 'آپ کا صفحۂ گفتگو',
'tooltip-pt-preferences' => 'آپ کی ترجیحات',
'tooltip-pt-watchlist' => 'اُن صفحات کی فہرست جن کی تبدیلیاں آپ کی زیرِنظر ہیں',
'tooltip-pt-mycontris' => 'آپ کی شراکت کی فہرست',
'tooltip-pt-login' => 'آپ کیلئے داخلِ نوشتہ ہونا اچھا ہے؛ تاہم، یہ ضروری نہیں',
'tooltip-pt-logout' => 'خارجِ نوشتہ ہوجائیں',
'tooltip-ca-talk' => 'مضمون بارے تبادلۂ خیال',
'tooltip-ca-edit' => 'آپ اس صفحہ میں ترمیم کرسکتے ہیں.
برائے مہربانی! اپنی ترمیمات محفوظ کرنے سے پہلے نمائش کا بٹن استعمال کیجئے',
'tooltip-ca-addsection' => 'نیا قطعہ شروع کیجئے',
'tooltip-ca-viewsource' => 'یہ ایک محفوظ شدہ صفحہ ہے.
آپ اِس کا مآخذ دیکھ سکتے ہیں',
'tooltip-ca-history' => 'صفحۂ ہٰذا کی سابقہ نظرثانی',
'tooltip-ca-protect' => 'یہ صفحہ محفوظ کیجئے',
'tooltip-ca-delete' => 'یہ صفحہ حذف کریں',
'tooltip-ca-move' => 'یہ صفحہ منتقل کریں',
'tooltip-ca-watch' => 'اِس صفحہ کو اپنی زیرِنظرفہرست میں شامل کریں',
'tooltip-ca-unwatch' => 'اِس صفحہ کو اپنی زیرِنظرفہرست سے ہٹائیں',
'tooltip-search' => 'تلاش {{SITENAME}}',
'tooltip-search-go' => 'اگر بالکل اِسی نام کا صفحہ موجود ہو تو اُس صفحہ پر جاؤ',
'tooltip-search-fulltext' => 'اس متن کیلئے صفحات تلاش کریں',
'tooltip-p-logo' => 'سرورق پر جائیے',
'tooltip-n-mainpage' => 'اصل صفحہ پر جائیے',
'tooltip-n-mainpage-description' => 'اصل صفحہ پر جائیے',
'tooltip-n-portal' => 'منصوبہ کے متعلق، آپ کیا کرسکتے ہیں، چیزیں کہاں ڈھونڈنی ہیں',
'tooltip-n-currentevents' => 'حالیہ واقعات پر پس منظری معلومات دیکھیئے',
'tooltip-n-recentchanges' => 'ویکی میں حالیہ تبدیلیوں کی فہرست',
'tooltip-n-randompage' => 'ایک تصادفی صفحہ لائیے',
'tooltip-n-help' => 'ڈھونڈ نکالنے کی جگہ',
'tooltip-t-whatlinkshere' => 'اُن تمام ویکی صفحات کی فہرست جن کا یہاں ربط ہے',
'tooltip-t-recentchangeslinked' => 'اِس صفحہ سے مربوط صفحات میں حالیہ تبدیلیاں',
'tooltip-feed-rss' => 'اِس صفحہ کیلئے اسس خورد',
'tooltip-feed-atom' => 'اِس صفحہ کیلئے اٹوم خورد',
'tooltip-t-contributions' => 'نئی تدوین →',
'tooltip-t-emailuser' => 'اِس صارف کو برقی خط ارسال کریں',
'tooltip-t-upload' => 'زبراثقالِ ملفات',
'tooltip-t-specialpages' => 'تمام خاص صفحات کی فہرست',
'tooltip-t-print' => 'اِس صفحہ کا قابلِ طبعہ نسخہ',
'tooltip-t-permalink' => 'صفحہ کے موجودہ نظرثانی کا مستقل ربط',
'tooltip-ca-nstab-main' => 'صفحۂ مضمون دیکھئے',
'tooltip-ca-nstab-user' => 'اِس صارف کے مساہمات کی فہرست دیکھئے',
'tooltip-ca-nstab-special' => 'یہ ایک خاص صفحہ ہے، آپ اِس میں ترمیم نہیں کرسکتے',
'tooltip-ca-nstab-project' => 'صفحۂ صارف دیکھئے',
'tooltip-ca-nstab-image' => 'صفحۂ ملف دیکھئے',
'tooltip-ca-nstab-template' => 'سانچہ دیکھئے',
'tooltip-ca-nstab-category' => 'زمرہ‌جاتی صفحہ دیکھئے',
'tooltip-minoredit' => 'اِس تدوین کو بطورِ معمولی ترمیم نشانزد کیجئے',
'tooltip-save' => 'تبدیلیاں محفوظ کیجئے',
'tooltip-preview' => 'برائے مہربانی! محفوظ کرنے سے پہلے تبدیلیوں کا پیش منظر دیکھيے',
'tooltip-diff' => 'دیکھئے کہ اپنے متن میں کیا تبدیلیاں کیں',
'tooltip-compareselectedversions' => 'اِس صفحہ کی دو منتخب نظرثانیوں میں فرق دیکھئے',
'tooltip-watch' => 'اِس صفحہ کو اپنی زیرِنظرفہرست میں شامل کریں',
'tooltip-undo' => "''استرجع'' اس ترمیم کو پچھلی ترمیم کے جانب واپس کردیگا اور نمائشی انداز میں خانہ ترمیم کھول دے گا۔ آپ مختصراً سبب بیان کرنے کے بھی مجاز ہونگے۔",
'tooltip-summary' => 'مختصر خلاصہ درج کریں',

# Attribution
'anonymous' => '{{SITENAME}} گمنام صارف',
'others' => 'دیگر',

# Patrolling
'markaspatrolledtext' => 'اس صفحہ کو بطور مراجعت شدہ نشان زد کریں',

# Image deletion
'deletedrevision' => 'حذف شدہ پرانی ترمیم $1۔',

# Browsing diffs
'previousdiff' => '← پُرانی تدوین',
'nextdiff' => 'صفحہ کا نام:',

# Media information
'file-info-size' => '
$1 × $2 عکصر (پکسلز)، حجم ملف: $3، MIME قسم: $4',
'file-nohires' => 'اس سے بڑی تصمیم دستیاب نہیں۔',
'show-big-image' => 'مکمل تصمیم',

# Special:NewFiles
'newimages' => 'نئی فائلوں کی گیلری',
'showhidebots' => '($1 بوٹ)',
'ilsubmit' => 'تلاش',
'bydate' => 'بالحاظ تاریخ',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'weeks' => '{{PLURAL:$1|$1ہفتہ| $1  ہفتے}}',

# Bad image list
'bad_image_list' => 'شکلبند درج ذیل ہے:

صرف فہرستی عناصر (* سے شروع ہونے والی لکیری) شامل کی جاتی ہیں۔
کسی لکیر میں پہلا ربط کوئی خراب ملف کا ہونا چاہئے۔
اُسی لکیر میں باقی آنے والے ربط کو مستثنیٰ قرار دیا جاتا ہے، مثلاً صفحات جہاں ملف لکیر کے وسط میں آسکتا ہے۔',

# Metadata
'metadata' => 'میٹا ڈیٹا',
'metadata-collapse' => 'طویل تفاصیل چھپاؤ',

'exif-meteringmode-0' => 'نامعلوم',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'تمام',
'namespacesall' => 'تمام',
'monthsall' => 'تمام',

# Delete conflict
'deletedwhileediting' => 'انتباہ: آپ کے ترمیم شروع کرنے کے بعد یہ صفحہ حذف کیا جا چکا ہے!',

# action=purge
'confirm_purge_button' => 'جی!',

# Auto-summaries
'autosumm-blank' => 'تمام مندرجات حذف',
'autoredircomment' => '[[$1]] سے رجوع مکرر',
'autosumm-new' => 'نیا صفحہ: $1',

# Watchlist editing tools
'watchlisttools-view' => 'متعلقہ تبدیلیاں دیکھیں',
'watchlisttools-edit' => 'زیرِنظرفہرست دیکھیں اور تدوین کریں',
'watchlisttools-raw' => 'خام زیرِنظرفہرست تدوین کریں',

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
'hijri-calendar-m1' => 'محرم',
'hijri-calendar-m2' => 'صفر',
'hijri-calendar-m3' => 'ربیع الاول',
'hijri-calendar-m4' => 'ربیع الثانی',
'hijri-calendar-m5' => 'جمادی الاول',
'hijri-calendar-m6' => 'جمادی الثانی',
'hijri-calendar-m7' => 'رجب',
'hijri-calendar-m8' => 'شعبان',
'hijri-calendar-m9' => 'رمضان',
'hijri-calendar-m10' => 'شوال',
'hijri-calendar-m11' => 'ذوالقعدہ',
'hijri-calendar-m12' => 'ذوالحجہ',

# Special:Version
'version' => 'ورژن',

# Special:SpecialPages
'specialpages' => 'خصوصی صفحات',

# New logging system
'rightsnone' => '(کچھ نہیں)',

# Search suggestions
'searchsuggest-search' => 'تلاش',

);
