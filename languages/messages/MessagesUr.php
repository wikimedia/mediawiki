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
 * @author Meno25
 * @author Wisesabre
 * @author לערי ריינהארט
 * @author محبوب عالم
 */

$fallback8bitEncoding = 'windows-1256';
$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$namespaceNames = array(
	NS_MEDIA => 'زریعہ',
	NS_SPECIAL => 'خاص',
	NS_MAIN => '',
	NS_TALK => 'تبادلۂ_خیال',
	NS_USER => 'صارف',
	NS_USER_TALK => 'تبادلۂ_خیال_صارف',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK => 'تبادلۂ_خیال_$1',
	NS_FILE => 'تصویر',
	NS_FILE_TALK => 'تبادلۂ_خیال_تصویر',
	NS_MEDIAWIKI => 'میڈیاوکی',
	NS_MEDIAWIKI_TALK => 'تبادلۂ_خیال_میڈیاوکی',
	NS_TEMPLATE => 'سانچہ',
	NS_TEMPLATE_TALK => 'تبادلۂ_خیال_سانچہ',
	NS_HELP => 'معاونت',
	NS_HELP_TALK => 'تبادلۂ_خیال_معاونت',
	NS_CATEGORY => 'زمرہ',
	NS_CATEGORY_TALK => 'تبادلۂ_خیال_زمرہ',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'ربط کی خط کشیدگی:',
'tog-highlightbroken'         => 'غیر فعال روابط کی شکلبندی <a href="" class="new">اس طرح</a> (alternative: اس طرح<a href="" class="internal">?</a>)
کرو',
'tog-justify'                 => 'سطور کی برابری',
'tog-hideminor'               => 'حالیہ تبدیلیوں میں معمولی ترمیمات چُھپاؤ',
'tog-hidepatrolled'           => 'حالیہ تبدیلیوں میں گشتی ترمیمات چُھپاؤ',
'tog-newpageshidepatrolled'   => 'جدید صفحاتی فہرست میں گشتی صفحات چُھپاؤ',
'tog-extendwatchlist'         => 'زیرِنظرفہرست کو پھیلاؤ تاکہ اِس میں تمام ترمیمات نظر آئیں، نہ کہ صرف حالیہ ترین',
'tog-usenewrc'                => 'افزودہ حالیہ تبدیلیاں استعمال کرو (JavaScript چاہئے ہوگا)',
'tog-numberheadings'          => 'سرخیوں کو خود نمبر دو',
'tog-showtoolbar'             => 'تدوینی اوزاردان دکھاؤ ( JavaScript چاہئے)',
'tog-editondblclick'          => 'طقین پر صفحات کی ترمیم (JavaScript چاہئے)',
'tog-editsection'             => '[ترمیم] روابط کے ذریعے سطری ترمیم کاری فعال کرو',
'tog-editsectiononrightclick' => 'سطری عنوانات پر دایاں طق کے ذریعے سطری ترمیم کاری فعال بناؤ',
'tog-showtoc'                 => 'فہرستِ مندرجات دکھاؤ (3 سے زیادہ سرخیوں والے صفحات کیلئے)',
'tog-rememberpassword'        => 'اِس شمارندہ پر میری داخلہ کاری معلومات یاد رکھو',
'tog-editwidth'               => 'تدوینی خانہ کو اتنا چوڑا کرو کہ یہ پوری سکرین پر محیط ہوجائے',
'tog-watchcreations'          => 'میرے مرتب شدہ صفحات کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchdefault'            => 'میرے ترمیم شدہ صفحات کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchmoves'              => 'میں جن صفحات کو منتقل کرتا ہوں، اُن کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-watchdeletion'           => 'میں جن صفحات کو حذف کروں، اُن کو میری زیرِنظرفہرست میں شامل کیا کرو',
'tog-minordefault'            => 'تمام ترمیمات کو ہمیشہ بطورِ معمولی ترمیم نشانزد کیا کرو',
'tog-previewontop'            => 'تدوینی خانہ سے پہلے نمائش دکھاؤ',
'tog-previewonfirst'          => 'پہلی ترمیم پر نمائش دکھاؤ',
'tog-nocache'                 => 'بطن کارئ صفحہ غیر فعال بناؤ',
'tog-enotifwatchlistpages'    => 'جب میری زیرِنظرفہرست پر کوئی صفحہ میں تبدیلی واقع ہو تو مجھے برقی ڈاک بھیجو',
'tog-enotifusertalkpages'     => 'جب میرا تبادلۂ خیال صفحہ میں تبدیلی واقع ہو تو مجھے برقی ڈاک بھیجو',
'tog-enotifminoredits'        => 'صفحات میں معمولی ترمیمات کے بارے میں بھی مجھے برقی ڈاک بھیجو',
'tog-enotifrevealaddr'        => 'خبرداری برقی خطوط میں میرا برقی ڈاک پتہ ظاہر کرو',
'tog-shownumberswatching'     => 'دیکھنے والے صارفین کی تعداد دکھاؤ',
'tog-fancysig'                => '(سادہ دستخط بلا خودکار ربط)',
'tog-externaleditor'          => 'ہمیشہ بیرونی تدوین کار استعمال کرو (صرف ماہرین کیلئے، اِس کیلئے شمارندہ پر خاص ترتیبات درکار ہوتی ہیں)',
'tog-externaldiff'            => 'Use external diff by default (for experts only, needs special settings on your computer)',
'tog-showjumplinks'           => 'Enable "jump to" accessibility links',
'tog-ccmeonemails'            => 'دیگر صارفین کو ارسال کردہ برقی خطوط کی نقول مجھے ارسال کریں۔',

'underline-always' => 'ہمیشہ',
'underline-never'  => 'کبھی نہیں',

# Dates
'sunday'        => 'اتوار',
'monday'        => 'پير',
'tuesday'       => 'منگل',
'wednesday'     => 'بدھ',
'thursday'      => 'جمعرات',
'friday'        => 'جمعہ',
'saturday'      => 'ہفتہ',
'january'       => 'جنوری',
'february'      => 'فروری',
'march'         => 'مارچ',
'april'         => 'اپريل',
'may_long'      => 'مئی',
'june'          => 'جون',
'july'          => 'جولائی',
'august'        => 'اگست',
'september'     => 'ستمبر',
'october'       => 'اکتوبر',
'november'      => 'نومبر',
'december'      => 'دسمبر',
'january-gen'   => 'جنوری',
'february-gen'  => 'فروری',
'march-gen'     => 'مارچ',
'april-gen'     => 'اپريل',
'may-gen'       => 'مئی',
'june-gen'      => 'جون',
'july-gen'      => 'جولائ',
'august-gen'    => 'اگست',
'september-gen' => 'ستمبر',
'october-gen'   => 'اکتوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'دسمبر',
'may'           => 'مئی',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|زمرہ|زمرہ جات}}',
'category_header'        => 'زمرہ "$1" میں مضامین',
'subcategories'          => 'ذیلی ذمرہ جات',
'listingcontinuesabbrev' => '۔جاری',

'mainpagetext' => "<big>'''میڈیاوکی کو کامیابی سے چالو کردیا گیا ہے۔.'''</big>",

'about'         => 'تعارف',
'newwindow'     => '(نـئی ونـڈو میـں)',
'cancel'        => 'منسوخ',
'moredotdotdot' => 'اور...',
'mypage'        => 'میرا صفحہ',
'mytalk'        => 'میری گفتگو',
'anontalk'      => 'اس IP کیلیے بات چیت',
'navigation'    => 'رہنمائی',
'and'           => '&#32;اور',

'returnto'          => 'واپس $1۔',
'tagline'           => '{{SITENAME}} سے',
'help'              => 'معاونت',
'search'            => 'تلاش',
'searchbutton'      => 'تلاش',
'go'                => 'چلو',
'searcharticle'     => 'چلو',
'history'           => 'تاریخچہ ء صفحہ',
'history_short'     => 'تاریخچہ',
'printableversion'  => 'قابل طبع نسخہ',
'permalink'         => 'مستقل کڑی',
'print'             => 'طباعت',
'edit'              => 'ترمیم',
'editthispage'      => 'اس صفحہ میں ترمیم کریں',
'delete'            => 'حذف',
'deletethispage'    => 'یہ صفحہ حذف کریں',
'undelete_short'    => 'بحال {{PLURAL:$1|ایک ترمیم|$1 ترامیم}}',
'protect'           => 'محفوظ',
'protectthispage'   => 'اس صفحےکومحفوظ کریں',
'unprotect'         => 'غیر محفوظ',
'unprotectthispage' => 'اس صفحےکو غیر محفوظ کریں',
'newpage'           => 'نیا صفحہ',
'talkpage'          => 'اس صفحہ پر تبادلۂ خیال کریں',
'talkpagelinktext'  => 'گفتگو',
'specialpage'       => 'خصوصی صفحہ',
'personaltools'     => 'ذاتی اوزار',
'postcomment'       => 'اگلا حصّہ',
'articlepage'       => 'مندرجاتی صفحہ دیکھیۓ',
'talk'              => 'تبادلہٴ خیال',
'views'             => 'خیالات',
'toolbox'           => 'اوزاردان',
'userpage'          => 'صفحۂ صارف دیکھئے',
'projectpage'       => 'صفحۂ منصوبہ دیکھئے',
'imagepage'         => 'صفحۂ مسل دیکھئے',
'mediawikipage'     => 'صفحۂ پیغام دیکھئے',
'templatepage'      => 'صفحۂ سانچہ دیکھئے',
'viewhelppage'      => 'صفحۂ معاونت دیکھیے',
'categorypage'      => 'زمرہ‌جاتی صفحہ دیکھئے',
'viewtalkpage'      => 'تبادلۂ خیال دیکھئے',
'otherlanguages'    => 'دیگر زبانوں میں',
'redirectedfrom'    => '($1 سے پلٹایا گیا)',
'redirectpagesub'   => 'لوٹایا گیا صفحہ',
'lastmodifiedat'    => 'آخری بار تدوین $2, $1 کو کی گئی۔', # $1 date, $2 time
'viewcount'         => 'اِس صفحہ تک {{PLURAL:$1|ایک‌بار|$1 مرتبہ}} رسائی کی گئی',
'protectedpage'     => 'محفوظ شدہ صفحہ',
'jumpto'            => ':چھلانگ بطرف',
'jumptonavigation'  => 'رہنمائی',
'jumptosearch'      => 'تلاش',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'کا تعارف {{SITENAME}}',
'aboutpage'            => 'Project:تعارف',
'copyright'            => 'تمام مواد $1 کے تحت میسر ہے۔',
'copyrightpagename'    => '{{SITENAME}} حق تصنیف',
'copyrightpage'        => '{{ns:project}}:حقوق تصانیف',
'currentevents'        => 'حالیہ واقعات',
'currentevents-url'    => 'Project:حالیہ واقعات',
'disclaimers'          => 'اعلانات',
'disclaimerpage'       => 'Project:عام اعلان',
'edithelp'             => 'معاونت براۓ ترمیم',
'edithelppage'         => 'Help:ترمیم',
'faq'                  => 'معلوماتِ عامہ',
'faqpage'              => 'Project:معلوماتِ عامہ',
'helppage'             => 'Help:فہرست',
'mainpage'             => 'سرورق',
'mainpage-description' => 'سرورق',
'policy-url'           => 'Project:حکمتِ عملی',
'portal'               => 'دیوان عام',
'portal-url'           => 'Project:دیوان عام',
'privacy'              => 'اصول براۓ اخفائے راز',
'privacypage'          => 'Project:اصولِ اخفائے راز',

'badaccess'        => 'خطائے اجازت',
'badaccess-group0' => 'آپ متمنی عمل کا اجراء کرنے کے مُجاز نہیں۔',
'badaccess-groups' => 'آپ کا درخواست‌کردہ عمل {{PLURAL:$2|گروہ|گروہوں میں سے ایک}}: $1 کے صارفین تک محدود ہے.',

'versionrequired'     => 'میڈیا ویکی کا $1 نسخہ لازمی چاہئیے.',
'versionrequiredtext' => 'اِس صفحہ کو استعمال کرنے کیلئے میڈیاویکی کا $1 نسخہ چاہئیے.


دیکھئے [[خاص:نسخہ|صفحۂ نسخہ]]',

'ok'                      => 'ٹھیک ہے',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => '‘‘$1’’ مستعادہ منجانب',
'youhavenewmessages'      => 'آپکے لیۓ ایک $1 ہے۔ ($2)',
'newmessageslink'         => 'نئے پیغامات',
'newmessagesdifflink'     => 'تـجـدیـد مـاقـبل آخـر سے فـرق',
'youhavenewmessagesmulti' => 'ء$1 پر آپ کیلئے نئے پیغامات ہیں',
'editsection'             => 'ترمیم',
'editsection-brackets'    => '[$1]',
'editold'                 => 'ترمیم',
'viewsourceold'           => 'مآخذ دیکھئے',
'editlink'                => 'تدوین کریں',
'viewsourcelink'          => 'مآخذ دیکھئے',
'editsectionhint'         => 'تدوینِ حصّہ: $1',
'toc'                     => 'فہرست',
'showtoc'                 => 'دکھائیں',
'hidetoc'                 => 'چھپائیں',
'thisisdeleted'           => 'دیکھیں یا بحال کریں $1؟',
'viewdeleted'             => 'دیکھیں $1؟',
'restorelink'             => '$1 ترامیم ضائع کردی',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Invalid subscription feed type.',
'feed-unavailable'        => 'Syndication feeds are not available',
'site-rss-feed'           => '$1 آر.ایس.ایس فیڈ',
'site-atom-feed'          => '$1 اٹوم فیڈ',
'page-rss-feed'           => '"$1" آر.ایس.ایس فیڈ',
'page-atom-feed'          => '"$1" اٹوم فیڈ',
'feed-atom'               => 'اٹوم',
'feed-rss'                => 'آر ایس ایس',
'red-link-title'          => '$1 (صفحہ موجود نہیں)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحہ',
'nstab-user'      => 'صفحۂ صارف',
'nstab-media'     => 'صفحۂ وسیط',
'nstab-special'   => 'خاص صفحہ',
'nstab-project'   => 'صفحۂ منصوبہ',
'nstab-image'     => 'مسل',
'nstab-mediawiki' => 'پیغام',
'nstab-template'  => 'سانچہ',
'nstab-help'      => 'معاونت',
'nstab-category'  => 'زمرہ',

# Main script and global functions
'nosuchaction'      => 'کوئی سا عمل نہیں',
'nosuchactiontext'  => 'URL کی جانب سے مختص کیا گیا عمل درست نہیں.
آپ نے شاید URL غلط لکھا، یا کسی غیر صحیح ربط کی پیروی کی ہے.
{{اِس سے SITENAME کے زیرِ استعمال مصنع لطیف میں کھٹمل کی نشاندہی کا بھی اندیشہ ہے}}.',
'nosuchspecialpage' => 'کوئی ایسا خاص صفحہ نہیں',
'nospecialpagetext' => "<big>'''آپ نے ایک ناقص خاص صفحہ کی درخواست کی ہے.'''</big>

{{درست خاص صفحات کی ایک فہرست [[خاص:خاص‌صفحات|خاص صفحات]] پر دیکھی جاسکتی ہے}}.",

# General errors
'error'                => 'خطاء',
'databaseerror'        => 'خطائے ڈیٹابیس',
'dberrortext'          => 'ڈیٹابیس کے استفسارہ میں ایک خطائے نحوی واقع ہوئی ہے. 
اِس سے مصنع‌لطیف میں کھٹمل کی نشاندہی کا اندیشہ ہے. 
پچھلا سعی‌شدہ ڈیٹابیسی استفسارہ یہ تھا:
<blockquote><tt>$1</tt></blockquote>
فعلیت میں سے "<tt>$2</tt>".
MySQL نے خطائی جواب دیا "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'ڈیٹابیس کے استفسارہ میں ایک خطائے نحوی واقع ہوئی ہے. 
پچھلا سعی‌شدہ ڈیٹابیسی استفسارہ یہ تھا:
"$1"
"$2" فعلیت میں سے.
MySQL نے جوابِ خطاء دیا "$3: $4"',
'noconnect'            => 'بہ تاسف! ویکی کو چند تکنیکی مشکلات کا سامنا ہے جس کی وجہ سے ڈیٹابیسی معیل کے ساتھ رابطہ نہیں کرسکتا۔ <br />$1',
'nodb'                 => '$1 ڈیٹابیس منتخب نہ ہوسکا.',
'cachederror'          => 'نیچے التماس شدہ صفحے کا ایک ابطن‌شدہ نسخہ ہے، اور اِس کے بتاریخی (اپ ٹو ڈیٹ) ہونے میں شک ہے.',
'laggedslavemode'      => 'انتباہ: ممکن ہے کہ صفحہ میں حالیہ بتاریخہ جات شامل نہ ہوں.

Warning: Page may not contain recent updates.',
'readonly'             => 'ڈیٹابیس مقفل ہے',
'enterlockreason'      => 'قفل کیلئے کوئی وجہ درج کیجئے، بشمولِ تخمینہ کہ قفل کب کھولا جائے گا.',
'readonlytext'         => 'ڈیٹابیس نئے اندراجات اور دوسری ترمیمات کیلئے مقفل ہے، شاید معمول کے ڈیٹابیسی اصلاح کیلئے، جس کے بعد یہ عام حالت پر آجائے گا. 
منتظم، جس نے قفل لگایا، یہ تفصیل فراہم کی ہے:',
'missing-article'      => 'ڈیٹابیس نے کسی صفحے کا متن بنام "$1" $2  نہیں پایا جو اِسے پانا چاہئے تھا.

یہ عموماً کسی صفحے کے تاریخی یا پرانے حذف شدہ ربط کی وجہ سے ہوسکتا ہے. 

اگر یہ وجہ نہیں، تو آپ نے مصنع‌لطیف میں کھٹمل پایا ہے.
برائے مہربانی، URL کی نشاندہی کرتے ہوئے کسی [[Special:ListUsers/sysop|منتظم]] کو اِس کا سندیس کیجئے.',
'missingarticle-rev'   => '(نظرثانی#: $1)',
'readonly_lag'         => 'ڈیٹابیس خودکار طور پر مقفل ہوچکا ہے تاکہ ماتحت ڈیٹابیسی معیلات کا درجہ آقا کا ہوجائے.',
'internalerror'        => 'خطائے اندرونی',
'internalerror_info'   => 'خطائے اندرونی: $1',
'filecopyerror'        => '"$1" مسل کو "$2" کی طرف نقل نہیں کیا جاسکا.',
'filerenameerror'      => 'مسل "$1" کو "$2" میں بازنام نہیں کیا جاسکا.',
'filedeleteerror'      => 'مسل "$1" کو حذف نہیں کیا جاسکا.',
'directorycreateerror' => 'رہنامچہ "$1" تخلیق نہیں کیا جاسکا.',
'filenotfound'         => 'مسل "$1" ڈھونڈا نہ جاسکا.',
'fileexistserror'      => 'مسل "$1" کو لکھنے سے قاصر، مسل پہلے سے موجود',
'unexpected'           => 'غیرمتوقع قدر: "$1"="$2"',
'formerror'            => 'خطا: ورقہ بھیجا نہ جاسکا.',
'badarticleerror'      => 'اس صفحہ پر یہ عمل انجام نہیں دیا جاسکتا۔',
'cannotdelete'         => 'صفحہ یا مِلَف کو حذف نہیں کیا جا سکا۔ (ہوسکتا ہے کہ اسے پہلے ہی کسی نے حذف کردیاہو۔)',
'badtitle'             => 'خراب عنوان',
'badtitletext'         => 'درخواست شدہ صفحہ کا عنوان ناقص، خالی، یا کوئی غلط ربط شدہ بین لسانی یا بین ویکی عنوان ہے.
شاید اِس میں ایک یا زیادہ ایسے حروف موجود ہوں جو عنوانات میں استعمال نہیں ہوسکتے.',
'perfcached'           => 'ذیلی ڈیٹا ابطن شدہ ہے اور اِس کے پُرانے ہونے کا امکان ہے.',
'perfcachedts'         => 'ذیلی ڈیٹا ابطن شدہ ہے اور آخری بار اِس کی بتاریخیت $1 کو ہوئی.',
'querypage-no-updates' => 'اِس صفحہ کیلئے بتاریخات فی الحال ناقابل بنائی گئی ہیں. 
یہاں کا ڈیٹا ابھی تازہ نہیں کیا جائے گا.',
'viewsource'           => 'مسودہ',
'viewsourcefor'        => 'براۓ $1',
'actionthrottledtext'  => 'بطورِ ایک ضدسپم تدبیر، آپ کو مختصر وقت میں کئی بار یہ عمل بجا لانے سے محدود کیا گیا، اور آپ یہ حد پار کرچکے ہیں.
براہِ کرم، کچھ منٹ بعد کوشش کیجئے.',
'protectedpagetext'    => 'اس صفحہ کو تدوین سے محفوظ رکھنے کیلیے مقفل کر دیا گیا ہے۔',
'viewsourcetext'       => 'آپ صرف مسودہ دیکھ سکتے ہیں اور اسکی نقل اتار سکتے ہیں:',
'protectedinterface'   => 'یہ صفحہ مصنع‌لطیف کیلئے سطح‌البینی متن فراہم کرتا ہے، اور ناجائزاستعمال کے سدِباب کیلئے اِسے مقفل کیا گیا ہے.',
'editinginterface'     => "'''انتباہ:''' آپ ایک ایسا صفحہ تدوین کر رہے ہیں جو مصنع‌لطیف کیلئے سطح‌البینی متن فراہم کرتا ہے۔ اس صفحہ میں کی جانے والی ترمیم، دیگر صارفین کیلئے سطح‌البین کو تبدیل کردے گی۔
براہِ کرم، ترجمہ کیلئے [http://translatewiki.net/wiki/Main_Page?setlang=en '''بیٹاویکی'''] (میڈیاویکی مقامیانی منصوبہ) استعمال کیجئے.",
'sqlhidden'            => '(SQL استفسارہ پوشیدہ)',
'cascadeprotected'     => 'This page has been protected from editing, because it is included in the following {{PLURAL:$1|page, which is|pages, which are}} protected with the "cascading" option turned on:
$2',
'namespaceprotected'   => "آپ کو '''$1''' فضائے نام میں صفحات تدوین کرنے کی اِجازت نہیں ہے.",
'customcssjsprotected' => 'آب کو اِس صفحہ کی تدوین کی اِجازت نہیں ہے، کیونکہ اِس میں دوسرے صارف کی ذاتی ترتیبات موجود ہیں.',
'ns-specialprotected'  => 'خاص صفحات کی تدوین نہیں کی جاسکتی.',
'titleprotected'       => 'This title has been protected from creation by [[User:$1|$1]].
The reason given is "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "خراب وضعیت: انجان وائرسی مفراس: ''$1''",
'virus-scanfailed'     => 'تفریس ناکام (رمز $1)',
'virus-unknownscanner' => 'انجان ضدوائرس:',

# Login and logout pages
'logouttitle'                => 'اخراج صارف',
'logouttext'                 => "'''اب آپ خارج ہوچکے ہیں'''<br />
آپ خفی الاسم {{SITENAME}}  کا استعمال جاری رکھ سکتے ہیں، یا دوبارہ اسی نام یا مختلف نام سے داخل بھی ہو سکتے ہیں۔  یہ یاد آوری کرلیجیۓ کہ کچھ صفحات ایسے نظر آتے رہیں گے کہ جیسے ابھی آپ خارج نہیں ہوۓ ، جب تک آپ اپنے تفصحہ (براؤزر) کا ابطن (cache) صاف نہ کردیں۔",
'welcomecreation'            => '== خوش آمدید، $1 ! ==
آپ کا کھاتہ بنا دیا گیا ہے۔ اپنی ویـکـیـپـیـڈ یـا کی ترجیحات تبدیل کرنا مت بھولیں۔',
'loginpagetitle'             => 'داخلہ صارف',
'yourname'                   => 'اسمِ رکنیت',
'yourpassword'               => 'کلمۂ شناخت',
'yourpasswordagain'          => 'کلمۂ شناخت دوبارہ لکھیں',
'remembermypassword'         => 'مجھے یاد رکھیں',
'yourdomainname'             => 'آپکا ڈومین',
'externaldberror'            => 'یا تو توثیقی ڈیٹابیس میں خطا واقع ہوئی اور یا آپ کو بیرونی کھاتہ بتاریخ کرنے کی اِجازت نہیں ہے.',
'login'                      => 'داخل ہوں',
'nav-login-createaccount'    => 'کھاتہ کھولیں یا اندراج کریں',
'loginprompt'                => '{{SITENAME}} میں داخلے کیلۓ آپکے پاس قند (کوکیز) مجازہوناچاہیں۔',
'userlogin'                  => 'کھاتہ کھولیں یا اندراج کریں',
'logout'                     => 'اخراج',
'userlogout'                 => 'خارج ہوجائیں',
'notloggedin'                => 'داخلہ نہیں ہوا',
'nologin'                    => 'کیا آپ نے کھاتہ نہیں بنایا ہوا؟ $1۔',
'nologinlink'                => 'کھاتا بنائیں',
'createaccount'              => 'کھاتہ کھولیں',
'gotaccount'                 => 'پہلے سے کھاتہ بنا ہوا ہے? $1.',
'gotaccountlink'             => 'داخل ہوجائیے',
'createaccountmail'          => 'بذریعۂ برقی ڈاک',
'badretype'                  => 'درج شدہ کلمۂ شناخت اصل سے مطابقت نہیں رکھتا۔',
'userexists'                 => 'آپ نےجونام درج کیا ہے پہلے سے زیراستعمال ہے۔ مختلف نام استعمال کریں۔',
'youremail'                  => '٭ برقی خط',
'username'                   => 'اسم صارف',
'uid'                        => 'صارف نمبر:',
'prefs-memberingroups'       => '{{PLURAL:$1|گروہ|گروہوں}} کا رُکن:',
'yourrealname'               => '* اصلی نام',
'yourlanguage'               => 'زبان:',
'yourvariant'                => 'متغیّر:',
'yournick'                   => 'دستخط',
'badsig'                     => 'ناقص خام دستخط.
HTML tags جانچئے.',
'badsiglength'               => 'آپ کا دستخط کافی طویل ہے.
یہ $1 {{PLURAL:$1|حرف|حروف}} سے زیادہ نہیں ہونا چاہئے.',
'yourgender'                 => 'جنس:',
'gender-unknown'             => 'غیرمختص شدہ',
'gender-male'                => 'مرد',
'gender-female'              => 'عورت',
'prefs-help-gender'          => 'اختیاری: مصنع‌لطیف کی طرف سے صحیح‌الجنس تخاطب کیلئے استعمال ہوتا ہے. یہ معلومات عام ہوگی.',
'email'                      => 'برقی خط',
'prefs-help-realname'        => 'حقیقی نام اختیاری ہے. 
اگر آپ اِسے مہیّا کرتے ہیں، تو اِسے آپ کے کام کیلئے آپ کو انتساب دینے کیلئے استعمال کیا جائے گا.',
'loginerror'                 => 'داخلے میں غلطی',
'prefs-help-email'           => 'برقی ڈاک کا پتہ اختیاری ہے، لیکن یہ اُس وقت مفید ثابت ہوسکتا ہے جب آپ اپنا کلمۂ شناخت بھول جائیں. 
آپ یہ بھی منتخب کرسکتے ہیں کہ دوسرے صارفین، آپ کی شناخت کو افشا کئے بغیر، آپ کے تبادلۂ خیال صفحہ پر آپ سے رابطہ کریں.',
'prefs-help-email-required'  => 'برقی ڈاک پتہ چاہئے.',
'nocookiesnew'               => 'کھاتۂ صارف بنادیا گیا ہے، لیکن آپ کا داخلہ نہیں ہوا. 
صارفین کے داخلہ کیلئے {{SITENAME}} کوکیز استعمال کرتا ہے.
آپ کے ہاں کوکیز غیر فعال ہیں.
براہِ کرم، انہیں فعال کیجئے، اور پھر اپنے نئے اسمِ صارف اور کلمۂ شناخت کے ساتھ داخل ہوجائیے.',
'nocookieslogin'             => 'صارفین کے داخل ہونے کیلئے {{SITENAME}} کوکیز استعمال کرتا ہے.
آپ کے ہاں کوکیز غیر فعال ہیں.
انہیں فعال کرنے کے بعد پھر کوشش کیجئے.',
'noname'                     => 'آپ نے صحیح اسم صارف نہیں چنا.',
'loginsuccesstitle'          => 'داخلہ کامیاب',
'loginsuccess'               => "'''اب آپ {{SITENAME}} میں بنام \"\$1\" داخل ہوچکے ہیں۔'''",
'nosuchuser'                 => '"$1" کے نام سے کوئی صارف موجود نہیں۔  براۓکرم ہجوں کے درست اندراج کی تصدیق کرلیجیۓ ، یا آپ چاہیں تو نیا کھاتا بھی بنا سکتے ہیں۔',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" کے نام سے کوئی صارف موجود نہیں.
اپنا ہجہ جانچئے.',
'nouserspecified'            => 'آپ کو ایک اسمِ صارف مخصوص کرنا ہے.',
'wrongpassword'              => 'آپ نے غلط کلمۂ شناخت درج کیا ہے۔ دوبارہ کو شش کریں۔',
'wrongpasswordempty'         => 'کلمۂ شناخت ندارد۔ دوبارہ کوشش کریں۔',
'passwordtooshort'           => 'آپکا منتخب کردہ کلمۂ شناخت بہت مختصر ہے۔ اسے کم از کم $1 حروف پر مشتمل ہونا چاہیۓ۔',
'mailmypassword'             => 'کلمۂ شناخت بذریعہ برقی خط',
'passwordremindertitle'      => 'نیا عارضی کلمۂ شناخت برائے {{SITENAME}}',
'passwordremindertext'       => '(IP پتہ $1 سے) کسی (یا شاید آپ) نے {{SITENAME}} ($4) 
کیلئے نئی کلمۂ شناخت کیلئے التماس کیا. ایک عارضی کلمۂ شناخت "$3" 
برائے صارف "$2" تخلیق کیا گیا ہے. اگر یہ آپ کا ارادہ تھا، تو آپ
کو چاہئے کہ داخلِ نوشتہ ہونے کے بعد نئے کلمۂ شناخت کا انتخاب کریں.
آپ کا کلمۂ شناخت {{PLURAL:$5|ایک دِن|$5 دِن}} کے بعد ناکارہ ہوجائے گا.

اگر کسی اَور نے یہ التماس کیا ہے، یا آپ کو اپنا کلمۂ شناخت یاد آگیا ہے،
اور آپ اسے تبدیل نہیں کرنا چاہتے، تو آپ یہ پیغام نظر انداز کرسکتے ہیں اور
آپنا پُرانا کلمۂ شناخت کا استعمال جاری رکھ سکتے ہیں.',
'noemail'                    => 'صارف "$1" کیلئے کوئی برقی پتہ درج نہیں کیا گیا.',
'passwordsent'               => 'ایک نیا کلمۂ شناخت "$1" کے نام سے بننے والی برقی ڈاک کے پتے کیلیے بھیج دیا گیا ہے۔ 
جب وہ موصول ہو جاۓ تو براہ کرم اسکے ذریعے دوبارہ داخل ہوں۔',
'blocked-mailpassword'       => 'آپ کا آئی.پی پتہ تدوین سے روک لیا گیا ہے، سو، ناجائز استعمال کو روکنے کیلئے، آپ کے آئی.پی پتہ کو کلمۂ شناخت کی بحالی کا فعل استعمال کرنے کی اِجازت نہیں ہے.',
'mailerror'                  => 'مسلہ دوران ترسیل خط:$1',
'acct_creation_throttle_hit' => 'عرض معذرت، چونکہ آپ پہلے ہی $1 کھاتے بنا چکے ہیں اس لیے مزید نہیں بنا سکتے۔',
'emailauthenticated'         => 'آپ کے برقی خط کے پتے کی تصدیق $1 کو کی گئی۔',
'accountcreated'             => 'تخلیقِ کھاتہ',
'accountcreatedtext'         => 'تخیلقِ کھاتۂ صارف براۓ $1۔',

# Password reset dialog
'oldpassword' => 'پرانا کلمۂ شناخت:',
'newpassword' => 'نیا کلمۂ شناخت',
'retypenew'   => 'نیا کلمۂ شناخت دوبارہ درج کریں:',

# Edit page toolbar
'headline_sample' => 'شہ سرخی',
'headline_tip'    => 'شہ سرخی درجہ دوم',

# Edit pages
'summary'              => 'خلاصہ:',
'subject'              => 'مضمون/شہ سرخی:',
'minoredit'            => 'معمولی ترمیم',
'watchthis'            => 'یہ صفحہ زیر نظر کیجیۓ',
'savearticle'          => 'محفوظ',
'preview'              => 'نمائش',
'showpreview'          => 'نمائش',
'anoneditwarning'      => 'آپ {{SITENAME}} میں داخل نہیں ہوۓ لہذا آپکا IP پتہ اس صفحہ کے تاریخچہ ء ترمیم میں محفوظ ہوجاۓ گا۔',
'summary-preview'      => 'نمائش خلاصہ:',
'whitelistedittext'    => 'ترمیم و تدوین کے لیۓ آپکا [[Special:UserLogin|داخل ہونا]] لا زمی ہے۔',
'loginreqtitle'        => 'داخلہ / اندراج لازم',
'loginreqlink'         => 'داخلہ',
'accmailtitle'         => 'کلمہ شناخت بھیج دیا گیا۔',
'accmailtext'          => '"$1" کیلیۓ کلمہ شناخت $2 کو ارسال کردیا گیا۔',
'newarticle'           => '(نیا)',
'newarticletext'       => 'آپ ایک ایسے صفحے کے ربط تک آگۓ ہیں جو ابھی موجود نہیں۔ اگر آپ اس عنوان سے صفحہ بنانا چاہتے ہیں تو اپنا مضمون نیچے دیۓ گۓ احاطہ میں تحریر کیجیۓ اور محفوظ کردیجیۓ (مزید معلومات کیلیۓ معاونت کا صفحہ ملاحظہ کیجیۓ)۔ اگر آپ غلطی سے یہاں پہنچے ہیں تو واپسی کے لیۓ اپنے تصفحہ (براؤزر) کا بیک بٹن ٹک کیجیۓ۔',
'anontalkpagetext'     => "----''یہ صفحہ ایک ایسے صارف کا ہے جنہوں نے یا تو اب تک اپنا کھاتا نہیں بنایا یا پھر وہ اسے استعمال نہیں کر رہے/ رہی ہیں۔ لہذا ہمیں انکی شناخت کے لیۓ ایک اعدادی آئی پی پتہ استعمال کرنا پڑرہا ہے۔ اس قسم کا آئی پی ایک سے زائد صارفین کے لیۓ مشترک بھی ہوسکتا ہے۔ اگر آپکی موجودہ حیثیت ایک گمنام صارف کی ہے اور آپ محسوس کریں کہ اس صفحہ پر آپکی جانب منسوب یہ بیان غیرضروری ہے تو براہ کرم [[Special:UserLogin|کھاتا بنائیے یا داخل نوشتہ (لاگ ان) ہوں]] تاکہ مستقبل میں آپکو، گمنام صارفین میں شمار کرنے سے پرہیز کیا جاسکے۔\"",
'note'                 => "'''نوٹ:'''",
'previewnote'          => "'''یاد رکھیں، یہ صرف نمائش ہے ۔آپ کی ترامیم ابھی محفوظ نہیں کی گئیں۔'''",
'editing'              => 'آپ "$1" میں ترمیم کر رہے ہیں۔',
'editingsection'       => '$1 کے قطعہ کی تدوین',
'editingcomment'       => 'زیرترمیم $1 (تبصرہ)',
'editconflict'         => 'تنازعہ ترمیم:$1',
'yourtext'             => 'آپ کی تحریر',
'editingold'           => "'''انتباہ: آپ اس صفحے کا ایک پرانا مسودہ مرتب کررہے ہیں۔ اگر آپ اسے محفوظ کرتے ہیں تو اس صفحے کے اس پرانے مسودے سے اب تک کی جانے والی تمام تدوین ضائع ہو جاۓ گی۔'''",
'yourdiff'             => 'تضادات',
'copyrightwarning'     => "یہ یادآوری کرلیجیۓ کہ {{SITENAME}} میں تمام تحریری شراکت جی این یو آزاد مسوداتی اجازہ ($2)کے تحت تصور کی جاتی ہے (مزید تفصیل کیلیۓ $1 دیکھیۓ)۔ اگر آپ اس بات سے متفق نہیں کہ آپکی تحریر میں ترمیمات کری جائیں اور اسے آزادانہ (جیسے ضرورت ہو) استعمال کیا جاۓ تو براۓ کرم اپنی تصانیف یہاں داخل نہ کیجیۓ۔ اگر آپ یہاں اپنی تحریر جمع کراتے ہیں تو آپ اس بات کا بھی اقرار کر رہے ہیں کہ، اسے آپ نے خود تصنیف کیا ہے یا دائرہ ءعام (پبلک ڈومین) سے حاصل کیا ہے یا اس جیسے کسی اور آذاد وسیلہ سے۔'''بلااجازت ایسا کام داخل نہ کیجیۓ جسکا حق ِطبع و نشر محفوظ ہو!'''",
'templatesused'        => 'اس صفحے پر استعمال ہونے والے سانچے:',
'templatesusedsection' => 'اس قطعے میں استعمال ہونے والے سانچے:',

# History pages
'viewpagelogs'     => 'اس صفحہ کیلیے نوشتہ جات دیکھیے',
'currentrev'       => 'حـالیـہ تـجدید',
'revisionasof'     => 'تـجدید بـمطابق $1',
'previousrevision' => '←پرانی تدوین',
'nextrevision'     => '→اگلا اعادہ',
'cur'              => ' رائج',
'next'             => 'آگے',
'last'             => 'سابقہ',
'histlegend'       => "انتخاب: مختلف نسخوں کا موازنہ کرنے کیلیے، پیامی خانوں کو نشان زد کر کے نیچے دیے گئے بٹن پر کلک کیجیئے۔

'''علامات:'''

(رائج) = موجودہ متن سے اخـتلاف، (سابقہ) = گزشتہ متن سے اختلاف ، م = معمولی ترمیم۔",
'deletedrev'       => '[حذف کردیا گیا]',
'histfirst'        => 'قدیم ترین',
'histlast'         => 'تازہ ترین',

# Diffs
'difference'              => '(اصلاحات میں فرق)',
'compareselectedversions' => 'منتخب متـن کا موازنہ',

# Search results
'searchresults'             => 'تلاش کا نتیجہ',
'searchresulttext'          => 'ویکیپیڈیا میں تلاش کے بارے میں مزید معلومات کے لیۓ، ویکیپیڈیا میں تلاش کا صفحہ دیکھیۓ۔',
'searchsubtitle'            => "آپ کی تلاش براۓ '''[[:$1]]'''",
'searchsubtitleinvalid'     => "آپ کی تلاش براۓ '''$1'''",
'noexactmatch'              => '"$1" کے عنوان سے کوئی صفحہ موجود نہیں۔ آپ اگر چاہیں تو اس نام سے  [[:$1|صفحہ بنا سکتے ہیں]]',
'prevn'                     => 'پچھلے $1',
'nextn'                     => 'اگلے $1',
'viewprevnext'              => 'دیکھیں($1) ($2) ($3)۔',
'searchhelp-url'            => 'Help:فہرست',
'search-result-size'        => '$1 ({{PLURAL:$2|1 لفظ|$2 الفاظ}})',
'search-result-score'       => 'توافق: $1%',
'search-redirect'           => '(رجوع مکرر $1)',
'search-section'            => '(حصہ $1)',
'search-suggest'            => 'کیا آپ کا مطلب تھا: $1',
'search-interwiki-caption'  => 'ساتھی منصوبے',
'search-interwiki-default'  => '$1 نتائج:',
'search-interwiki-more'     => '(مزید)',
'search-mwsuggest-enabled'  => 'بمع تجاویز',
'search-mwsuggest-disabled' => 'تجاویز نہیں',
'search-relatedarticle'     => 'متعلقہ',
'mwsuggest-disable'         => 'AJAX تجاویز غیرفعال',
'searchrelated'             => 'متعلقہ',
'searchall'                 => 'تمام',
'showingresultstotal'       => "نیچے دکھارہا ہے {{PLURAL:$4|'''$3''' میں سے '''$3''' نتیجہ|'''$3''' میں سے '''$1 - $2''' نتائج}}",
'search-nonefound'          => 'استفسار کے مطابق نتائج نہیں ملے.',
'powersearch'               => 'پیشرفتہ تلاش',
'powersearch-legend'        => 'پیشرفتہ تلاش',
'powersearch-ns'            => 'جائے نام میں تلاش:',
'powersearch-redir'         => 'فہرستِ رجوع مکرر',
'powersearch-field'         => 'تلاش برائے',
'search-external'           => 'بیرونی تلاش',
'searchdisabled'            => '{{SITENAME}} تلاش غیرفعال.
آپ فی الحال گوگل کے ذریعے تلاش کرسکتے ہیں.
یاد رکھئے کہ اُن کے {{SITENAME}} اشاریے ممکناً پرانے ہوسکتے ہیں.',

# Preferences page
'preferences'               => 'ترجیحات',
'mypreferences'             => 'میری ترجیہات',
'prefs-edits'               => 'تدوینات کی تعداد:',
'prefsnologin'              => 'نا داخل شدہ حالت',
'prefsnologintext'          => 'ترجیحات ترتیب دینے کیلئے <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} داخل نوشتہ]</span> ہونا لازمی ہے.',
'prefsreset'                => 'ترجیحات کو ذخیر سے نوطاقم کیا جاچکا ہے.',
'qbsettings'                => 'فوری‌بار',
'qbsettings-none'           => 'ہیچ',
'changepassword'            => 'کلمۂ شناخت تبدیل کریں',
'skin-preview'              => 'پیش منظر',
'math'                      => 'ریاضی',
'dateformat'                => 'شکلبندِ تاریخ',
'datedefault'               => 'کوئی ترجیحات نہیں',
'datetime'                  => 'تاریخ و وقت',
'math_failure'              => 'تجزیہ میں ناکام',
'math_unknown_error'        => 'نامعلوم غلطی',
'math_unknown_function'     => 'نامعلوم فعل',
'math_syntax_error'         => 'نحوی غلطی',
'math_image_error'          => 'PNG; کی تحویل ناکام
latex، dvips، gs کی صحیح تنصیب کی جانچ کرنے کے بعد دوبارہ تحویل کی کوشش کیجئے.',
'prefs-personal'            => 'نمایۂ صارف',
'prefs-rc'                  => 'حالیہ تبدیلیاں',
'prefs-watchlist'           => 'زیرِنظر فہرست',
'prefs-watchlist-days'      => 'زیرِنظر فہرست میں نظر آنے والے ایام:',
'prefs-watchlist-days-max'  => '(زیادہ سے زیادہ 7 دِن)',
'prefs-watchlist-edits'     => 'عریض زیرِنظرفہرست میں نظر آنے والی تبدیلیوں کی زیادہ سے زیادہ تعداد:',
'prefs-watchlist-edits-max' => '(زیادہ سے زیادہ تعداد: 1000)',
'prefs-misc'                => 'دیگر',
'prefs-resetpass'           => 'کلمۂ شناخت تبدیل کیجئے',
'saveprefs'                 => 'محفوظ',
'resetprefs'                => 'نامحفوظ تبدیلیاں صاف کرو',
'restoreprefs'              => 'تمام بےنقص ترتیبات بحال کیجئے',
'prefs-edit-boxsize'        => 'تدوینی کھڑکی کی جسامت.',
'rows'                      => 'صفیں:',
'columns'                   => 'قطاریں:',
'searchresultshead'         => 'تلاش',
'recentchangesdays'         => 'حالیہ تبدیلیوں میں دکھائی جانے والے ایّام:',
'recentchangesdays-max'     => '(زیادہ سے زیادہ $1 {{PLURAL:$1|دن|ایام}})',
'recentchangescount'        => 'حالیہ تبدیلیاں، تواریخِ صفحہ اور نوشتہ جات میں دکھائی جانے والی ترمیمات کی تعداد:',
'savedprefs'                => 'آپ کی ترجیحات محفوظ ہوگئیں۔',
'timezonelegend'            => 'منطقۂ وقت',
'localtime'                 => 'مقامی وقت:',
'timezoneselect'            => 'منطقۂ وقت:',
'timezoneregion-africa'     => 'افریقہ',
'timezoneregion-america'    => 'امریکہ',
'timezoneregion-antarctica' => 'انٹارکٹیکا',
'timezoneregion-arctic'     => 'قطب شمالی',
'timezoneregion-asia'       => 'ایشیاء',
'timezoneregion-atlantic'   => 'بحر اوقیانوس',
'timezoneregion-australia'  => 'آسٹریلیا',
'timezoneregion-europe'     => 'یورپ',
'timezoneregion-indian'     => 'بحر ہند',
'timezoneregion-pacific'    => 'بحر الکاہل',
'allowemail'                => 'دوسرے صارفین کو برقی خظ لکھنے کا اختیار دیں',
'prefs-searchoptions'       => 'اختیاراتِ تلاش',
'prefs-namespaces'          => 'جائے نام',
'default'                   => 'طے شدہ',
'files'                     => 'فائلیں',
'prefs-custom-css'          => 'خودساختہ CSS',
'prefs-custom-js'           => 'خودساختہ JS',

# User rights
'userrights'               => 'حقوقِ صارف کی نظامت', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'   => 'گروہائے صارف کا انتظام',
'userrights-user-editname' => 'کوئی اسم‌صارف داخل کیجئے:',
'editinguser'              => "تبدیلئ حقوق برائے صارف '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-groupsmember'  => 'رکنِ:',
'userrights-reason'        => 'وجۂ تبدیلی:',

'group-bot-member' => 'خودکار صارف',

'grouppage-sysop' => '{{ns:project}}:منتظمین',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|تبدیلی|تبدیلیاں}}',
'recentchanges'     => 'حالیہ تبدیلیاں',
'recentchangestext' => 'اس صفحے پر ویکی میں ہونے والی تازہ تریں تبدیلیوں کا مشاہدہ کیجیۓ۔',
'rcnote'            => 'مندرجہ ذیل گذ شتہ <strong>$2</strong> روز میں ہونے والی آخری <strong>$1</strong> تبدیلیاں ہیں',
'rclistfrom'        => '$1 سےنئی تبدیلیاں دکھانا شروع کریں',
'rcshowhideminor'   => 'معمولی ترامیم $1',
'rcshowhidebots'    => 'خودکار صارف $1',
'rcshowhideliu'     => 'داخل شدہ صارف $1',
'rcshowhideanons'   => 'گمنام صارف $1',
'rcshowhidemine'    => 'ذاتی ترامیم $1',
'rclinks'           => 'آخری $2 روز میں ہونے والی $1 تبدیلیوں کا مشاہدہ کریں<br />$3',
'diff'              => 'فرق',
'hist'              => 'تاریخچہ',
'hide'              => 'چھـپائیں',
'minoreditletter'   => 'م',
'newpageletter'     => 'نیا ..',
'boteditletter'     => ' خودکار',

# Recent changes linked
'recentchangeslinked' => 'متعلقہ تبدیلیاں',

# Upload
'upload'            => 'فائل بھیجیں',
'uploadbtn'         => 'زبراثقال ملف (اپ لوڈ فائل)',
'reupload'          => 'زبراثقال مکرر',
'reuploaddesc'      => 'زبراثقال ورقہ (فارم) کیجانب واپس۔',
'uploadnologin'     => 'آپ داخل شدہ حالت میں نہیں',
'uploadnologintext' => 'زبراثقال ملف (فائل اپ لوڈ) کے لیۓ آپکو  [[Special:UserLogin|داخل شدہ]] حالت میں ہونا لازم ہے۔',
'uploadtext'        => "
<big>'''یادآوری''': اگر آپ اپنی ملف (فائل) زبراثقال کرتے وقت ، خلاصہ کے خانے میں ،  درج ذیل دو باتوں کی وضاحت نہیں کرتے تو ملف کو حذف کیا جاسکتا ہے:</big>
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
کی طرز میں ربط استعمال کیجیۓ۔ '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''
* ملف کا نام ؛ حرف ابجد کے لیۓ حساس ہے لہذا اگر زبراثقال کرتے وقت ملف کا نام -- name:JPG  ہے اور آپ رابطہ رکھتے وقت name:jpg یــا Name:jpg رکھتے ہیں تو ربط کام نہیں کرے گا",
'uploadlog'         => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpage'     => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpagetext' => 'درج ذیل میں حالیہ زبراثقال (اپ لوڈ) کی گئی املاف (فائلوں) کی فہرست دی گئی ہے۔',
'filedesc'          => 'خلاصہ',
'fileuploadsummary' => 'خلاصہ :',
'uploadedfiles'     => 'زبراثقال ملف (فائل اپ لوڈ)',
'ignorewarning'     => 'انتباہ نظرانداز کرتے ہوۓ بہرصورت ملف (فائل) کو محفوظ کرلیا جاۓ۔',
'ignorewarnings'    => 'ہر انتباہ نظرانداز کردیا جاۓ۔',
'badfilename'       => 'ملف (فائل) کا نام "$1" ، تبدیل کردیا گیا۔',
'fileexists'        => "اس نام سے ایک ملف (فائل) پہلے ہی موجود ہے، اگر آپ کو یقین نہ ہو کہ اسے حذف کردیا جانا چاہیۓ تو براہ کرم  '''<tt>$1</tt>''' کو ایک نظر دیکھ لیجیۓ۔",
'uploadwarning'     => 'انتباہ بہ سلسلۂ زبراثقال',
'savefile'          => 'فائل محفوظ کریں',
'uploadedimage'     => 'زبراثقال (اپ لوڈ) براۓ "[[$1]]"',
'sourcefilename'    => 'اسم ملف (فائل) کا منبع:',
'destfilename'      => 'تعین شدہ اسم ملف:',
'watchthisupload'   => 'یہ صفحہ زیر نظر کیجیۓ',

# Special:ListFiles
'listfiles' => 'فہرست فائل',

# File description page
'imagelinks'     => 'روابط',
'linkstoimage'   => 'اس ملف (فائل) سے درج ذیل صفحات رابطہ رکھتے ہیں:',
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
'statistics'              => 'اعداد و شمار',
'statistics-header-users' => 'ارکان کے اعداد و شمار',

'disambiguations' => 'ضد ابہام صفحات',

'doubleredirects' => 'دوہرے متبادل ربط',

'brokenredirects' => 'نامکمل متبادل ربط',

# Miscellaneous special pages
'ncategories'             => '{{PLURAL:$1|زمرہ|زمرہ جات}} $1',
'lonelypages'             => 'يتيم صفحات',
'lonelypagestext'         => 'مندرجہ ذیل صفحات وہ صفحات ہیں جنھیں اس وکی میں موجود صفحوں سے کوئی ربط حاصل نہیں ہوپارہا۔',
'uncategorizedpages'      => 'بے زمرہ صفحات',
'uncategorizedcategories' => 'بے زمرہ زمرہ جات',
'uncategorizedimages'     => 'بے زمرہ تصاویر',
'unusedcategories'        => 'غیر استعمال شدہ زمرہ جات',
'unusedimages'            => 'غیر استعمال شدہ فائلیں',
'popularpages'            => 'مقبول صفحات',
'wantedcategories'        => 'طلب شدہ زمرہ جات',
'wantedpages'             => 'درخواست شدہ مضامین',
'mostlinked'              => 'سب سے زیادہ ربط والے مضامین',
'mostlinkedcategories'    => 'سب سے زیادہ ربط والے زمرہ جات',
'mostcategories'          => 'سب سے زیادہ زمرہ جات والے مضامین',
'mostimages'              => 'سب سے زیادہ استعمال کردہ تصاویر',
'mostrevisions'           => 'زیادہ تجدید نظر کیے جانے والے صفحات',
'shortpages'              => 'چھوٹے صفحات',
'longpages'               => 'طویل ترین صفحات',
'deadendpages'            => 'مردہ صفحات',
'listusers'               => 'فہرست ارکان',
'newpages'                => 'جدید صفحات',
'ancientpages'            => 'قدیم ترین صفحات',
'move'                    => 'منتقـل',

# Book sources
'booksources' => 'کتابی وسائل',

# Special:Log
'specialloguserlabel'  => 'صارف:',
'speciallogtitlelabel' => 'عنوان:',
'log'                  => 'نوشتہ جات',

# Special:AllPages
'allpages'       => 'تمام صفحات',
'nextpage'       => 'اگلا صفحہ ($1)',
'prevpage'       => 'پچھلا صفحہ ($1)',
'allpagesfrom'   => 'مطلوبہ حرف شروع ہونے والے صفحات کی نمائش:',
'allarticles'    => 'تمام مقالات',
'allpagesprev'   => 'پچھلا',
'allpagesnext'   => 'اگلا',
'allpagesprefix' => 'مطلوبہ سابقہ سے شروع ہونے والے صفحات کی نمائش:',

# Special:Categories
'categories'         => 'زمرہ',
'categoriespagetext' => 'مندرجہ ذیل زمرہ جات اس وکی میں موجود ہیں۔',

# Special:Log/newusers
'newuserlogpage'          => 'نوشتۂ آمد صارف',
'newuserlogpagetext'      => 'یہ نۓ صارفوں کی آمد کا نوشتہ ہے',
'newuserlog-create-entry' => 'صارف جدید',

# E-mail user
'mailnologintext' => 'دیگر ارکان کو برقی خط ارسال کرنے کیلیۓ لازم ہے کہ آپ [[Special:UserLogin|داخل شدہ]] حالت میں ہوں اور آپ کی [[Special:Preferences|ترجیحات]] ایک درست برقی خط کا پتا درج ہو۔',
'emailuser'       => 'صارف کو برقی خط لکھیں',
'defemailsubject' => '{{SITENAME}} سے برقی خط',
'noemailtext'     => 'اس صارف نے برقی خط کے لیے کوئی پتہ فراہم نہیں کیا، یا یہ چاہتا ہے کا اس سے کوئی صارف رابطہ نہ کرے۔',
'emailsubject'    => 'عنوان',
'emailmessage'    => 'پیغام',

# Watchlist
'watchlist'         => 'میری زیرنظرفہرست',
'mywatchlist'       => 'میری زیرنظرفہرست',
'watchlistfor'      => "(براۓ '''$1''')",
'addedwatch'        => 'زیر نظر فہرست میں اندراج کردیاگیا',
'addedwatchtext'    => "یہ صفحہ \"<nowiki>\$1</nowiki>\" آپکی [[Special:Watchlist|زیرنظر]] فہرست میں شامل کردیا گیا ہے۔ اب مستقل میں اس صفحے اور اس سے ملحقہ تبادلہ خیال کا صفحے میں کی جانے والی تبدیلوں کا اندراج کیا جاتا رہے گا، اور ان صفحات کی شناخت کو سہل بنانے کے لیۓ [[Special:حالیہ تبدیلیاں|حالیہ تبدیلیوں کی فہرست]] میں انکو '''مُتَجَل''' (bold) تحریر کیا جاۓ گا۔ <p> اگر آپ کسی وقت اس صفحہ کو زیرنظرفہرست سے خارج کرنا چاہیں تو اوپر دیۓ گۓ \"زیرنظرمنسوخ\" پر ٹک کیجیۓ۔",
'removedwatch'      => 'زیرنظرفہرست سے خارج کر دیا گیا',
'removedwatchtext'  => 'صفحہ "<nowiki>$1</nowiki>" آپ کی زیر نظر فہرست سے خارج کر دیا گیا۔',
'watch'             => 'زیرنظر',
'watchthispage'     => 'یہ صفحہ زیر نظر کیجیۓ',
'unwatch'           => 'زیرنظرمنسوخ',
'watchlist-details' => '$1 زیرنظر صفحات (صفحات تبادلۃ خیال کا شمار نہیں).',
'watchlistcontains' => 'آپ کی زیرنظرفہرست میں $1 صفحات ہیں۔',
'wlnote'            => 'نیچےآخری $1 تبدیلیاں ہیں جو کے پیچھلے <b>$2</b> گھنٹوں میں کی گئیں۔',
'wlshowlast'        => 'دکھائیں آخری $1 گھنٹے $2 دن $3',

'enotif_newpagetext' => 'یہ نیا صفحہ ہے.',
'changed'            => 'تبدیل کردیاگیا',
'created'            => 'بنا دیا گیا',

# Delete
'deletepage'        => 'صفحہ ضائع کریں',
'confirm'           => 'یقین',
'excontent'         => "'$1':مواد تھا",
'excontentauthor'   => "حذف شدہ مواد: '$1' (اور صرف '[[Special:Contributions/$2|$2]]' نے حصہ ڈالا)",
'exblank'           => 'صفحہ خالی تھا',
'historywarning'    => 'انتباہ: جو صفحہ آپ حذف کرنے جارہے ہیں اس سے ایک تاریخچہ منسلک ہے۔',
'confirmdeletetext' => 'آپ نے اس صفحے کو اس سے ملحقہ تاریخچہ سمیت حذف کرنے کا ارادہ کیا ہے۔ براۓ مہربانی تصدیق کرلیجیۓ کہ آپ اس عمل کے نتائج سے بخوبی آگاہ ہیں، اور یہ بھی یقین کرلیجیۓ کہ آپ ایسا [[{{MediaWiki:Policy-url}}|ویکیپیڈیا کی حکمت عملی]] کے دائرے میں رہ کر کر رہے ہیں۔',
'actioncomplete'    => 'اقدام تکمیل کو پہنچا',
'deletedtext'       => '"<nowiki>$1</nowiki>" کو حذف کر دیا گیا ہے ۔
حالیہ حذف شدگی کے تاریخ نامہ کیلیۓ  $2  دیکھیۓ',
'deletedarticle'    => 'حذف شدہ "[[$1]]"',
'dellogpage'        => 'نوشتۂ حذف شدگی',
'dellogpagetext'    => 'حالیہ حذف شدگی کی فہرست درج ذیل ہے۔',
'deletionlog'       => 'نوشتۂ حذف شدگی',
'deletecomment'     => 'حذف کرنے کی وجہ',

# Rollback
'rollback'       => 'ترمیمات سابقہ حالت پرواپس',
'rollback_short' => 'واپس سابقہ حالت',
'rollbacklink'   => 'واپس سابقہ حالت',
'rollbackfailed' => 'سابقہ حالت پر واپسی ناکام',
'cantrollback'   => 'تدوین ثانی کا اعادہ نہیں کیا جاسکتا؛ کیونکہ اس میں آخری بار حصہ لینے والا ہی اس صفحہ کا واحد کاتب ہے۔',

# Protect
'protectlogpage'      => 'نوشتۂ محفوظ شدگی',
'protectedarticle'    => '"[[$1]]" کومحفوظ کردیا',
'unprotectedarticle'  => '"[[$1]]" کوغیر محفوظ کیا',
'prot_1movedto2'      => '[[$1]] بجانب [[$2]] منتقل',
'protectcomment'      => 'محفوظ کرنے کی وجہ',
'protect-default'     => '(طے شدہ)',
'protect-level-sysop' => 'صرف منتظمین',

# Undelete
'undelete'         => 'ضائع کردہ صفحات دیکھیں',
'undeletepage'     => 'معائنہ خذف شدہ صفحات',
'viewdeletedpage'  => 'حذف شدہ صفحات دیکھیے',
'undeletebtn'      => 'بحال',
'undeletecomment'  => 'تبصرہ:',
'undeletedarticle' => 'بحال "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'جاۓ نام:',
'invert'         => 'انتخاب بالعکس',
'blanknamespace' => '(مرکز)',

# Contributions
'contributions' => 'صارف کا حصہ',
'mycontris'     => 'میرا حصہ',
'contribsub2'   => 'براۓ $1 ($2)',
'uctop'         => ' (اوپر)',

'sp-contributions-blocklog' => 'نوشتۂ پابندی',

# What links here
'whatlinkshere' => 'ادھر کس کا جوڑ ہے',
'linkshere'     => 'یہاں درج ذیل صفحات رابطہ رکھتے ہیں:',
'nolinkshere'   => 'یہاں کسی صفحہ کا ربط نہیں۔',

# Block/unblock
'blockip'           => 'داخلہ ممنوع براۓ صارف',
'ipbreason'         => 'وجہ',
'ipbsubmit'         => 'اس صارف کا داخلہ ممنوع کریں',
'ipblocklist'       => 'فہرست ممنوع صارفین',
'blocklink'         => 'پابندی لگائیں',
'contribslink'      => 'شـراکـت',
'blocklogpage'      => 'نوشتۂ پابندی',
'proxyblocksuccess' => 'کردیا.',

# Move page
'move-page-legend'        => 'منتقلئ صفحہ',
'movepagetext'            => "نیچے دیا گیا تشکیلہ (فـارم) استعمال کرکے اس صفحہ کا عنوان دوبارہ منتخب کیا جاسکتا ہے، ساتھ ہی اس سے منسلک تاریخچہ بھی نۓ نام پر منتقل ہوجاۓ گا۔ اسکے بعد سے اس صفحے کا پرانا نام ، نۓ نام کی جانب -- لوٹایا گیا صفحہ -- کی حیثیت اختیار کرلے گا۔ لیکن یادآوری کرلیجیۓ دیگر صفحات پر ، پرانے صفحہ کی جانب دیۓ گۓ روابط (لنکس) تبدیل نہیں ہونگے؛ اس بات کو یقینی بنانا ضروری ہے کہ کوئی دوہرا یا شکستہ -- پلٹایا گیا ربط -- نہ رہ جاۓ۔ 

لہذا یہ یقینی بنانا آپکی ذمہ داری ہے کہ تمام روابط درست صفحات کی جانب رہنمائی کرتے رہیں۔

یہ بات بھی ذہن نشین کرلیجیۓ کہ اگر نۓ منتخب کردہ نام کا صفحہ پہلے سے ہی موجود ہو تو ہوسکتا ہے کہ صفحہ منتقل نہ ہو ، ؛ ہاں اگر پہلے سے موجود صفحہ خالی ہے ، یا وہ صرف ایک -- لوٹایا گیا صفحہ -- ہو اور اس سے کوئی تاریخچہ منسلک نہ ہو تو منتقلی ہوجاۓ گی۔ گویا ، کسی خامی کی صورت میں آپ صفحہ کو دوبارہ اسی پرانے نام کی جانب منتقل کرسکتے ہیں اور اس طرح پہلے سے موجود کسی صفحہ میں کوئی حذف و خامی نہیں ہوگی۔

''' انـتـبـاہ !'''
 کسی اہم اور مقبول صفحہ کی منتقلی ، غیرمتوقع اور پریشان کن بھی ہی ہوسکتی ہے اس لیۓ ؛ منتقلی سے قبل براہ کرم یقین کرلیجۓ کہ آپ اسکے منطقی نتائج سے باخبر ہیں۔",
'movearticle'             => 'مـنـتـقـل کـریں',
'newtitle'                => 'نـیــا عـنــوان',
'move-watch'              => 'صفحہ زیر نظر',
'movepagebtn'             => 'مـنـتـقـل',
'articleexists'           => 'اس عنوان سے کوئی صفحہ پہلے ہی موجود ہے، یا آپکا منتخب کردہ نام مستعمل نہیں۔ براۓ مہربانی دوسرا نام منتخب کیجیۓ۔',
'1movedto2'               => '[[$1]] بجانب [[$2]] منتقل',
'movelogpage'             => 'نوشتۂ منتقلی',
'movereason'              => 'وجہ',
'delete_and_move'         => 'حذف اور منتقل',
'delete_and_move_text'    => '==حذف شدگی لازم==

منتقلی کے سلسلے میں انتخاب کردہ مضمون "[[:$1]]" پہلے ہی موجود ہے۔ کیا آپ اسے حذف کرکے منتقلی کیلیۓ راستہ بنانا چاہتے ہیں؟',
'delete_and_move_confirm' => 'ہاں، صفحہ حذف کر دیا جائے',
'delete_and_move_reason'  => 'منتقلی کے سلسلے میں حذف',

# Export
'export' => 'برآمد صفحات',

# Namespace 8 related
'allmessages'         => 'نظامی پیغامات',
'allmessagesname'     => 'نام',
'allmessagesdefault'  => 'طے شدہ متن',
'allmessagescurrent'  => 'موجودہ متن',
'allmessagestext'     => 'یہ میڈیاویکی: جاۓ نام میں دستیاب نظامی پیغامات کی فہرست ہے۔',
'allmessagesfilter'   => 'مِصفاہ اسم پیغام:',
'allmessagesmodified' => 'فقط ترامیم کا اظہار',

# Special:Import
'import' => 'درآمد صفحات',

# Attribution
'anonymous' => '{{SITENAME}} گمنام صارف',
'others'    => 'دیگر',

# Image deletion
'deletedrevision' => 'حذف شدہ پرانی ترمیم $1۔',

# Browsing diffs
'previousdiff' => '> گذشتہ فرق',
'nextdiff'     => '< اگلا فرق',

# Special:NewFiles
'newimages'    => 'نئی فائلوں کی گیلری',
'showhidebots' => '($1 بوٹ)',
'ilsubmit'     => 'تلاش',
'bydate'       => 'بالحاظ تاریخ',

# Metadata
'metadata' => 'میٹا ڈیٹا',

'exif-meteringmode-0' => 'نامعلوم',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'تمام',
'watchlistall2'    => 'تمام',
'namespacesall'    => 'تمام',

# Delete conflict
'deletedwhileediting' => 'انتباہ: آپ کے ترمیم شروع کرنے کے بعد یہ صفحہ حذف کیا جا چکا ہے!',

# action=purge
'confirm_purge_button' => 'جی!',

# Auto-summaries
'autosumm-blank'   => 'تمام مندرجات حذف',
'autoredircomment' => '[[$1]] سے رجوع مکرر',
'autosumm-new'     => 'نیا صفحہ: $1',

# Special:Version
'version' => 'ورژن', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'خصوصی صفحات',

);
