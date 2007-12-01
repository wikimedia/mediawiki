<?php
/**
 *
 * @addtogroup Language
 */

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
	NS_IMAGE => 'تصویر',
	NS_IMAGE_TALK => 'تبادلۂ_خیال_تصویر',
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
'tog-fancysig'     => '(سادہ دستخط بلا خودکار ربط)',
'tog-ccmeonemails' => 'دیگر صارفین کو ارسال کردہ برقی خطوط کی نقول مجھے ارسال کریں۔',

'underline-always' => 'ہمیشہ',
'underline-never'  => 'کبھی نہیں',

# Dates
'sunday'    => 'اتوار',
'monday'    => 'پير',
'tuesday'   => 'منگل',
'wednesday' => 'بدھ',
'thursday'  => 'جمعرات',
'friday'    => 'جمعہ',
'saturday'  => 'ہفتہ',
'january'   => 'جنوری',
'february'  => 'فروری',
'march'     => 'مارچ',
'april'     => 'اپريل',
'may_long'  => 'مئی',
'june'      => 'جون',
'july'      => 'جولائی',
'august'    => 'اگست',
'september' => 'ستمبر',
'october'   => 'اکتوبر',
'november'  => 'نومبر',
'december'  => 'دسمبر',
'may'       => 'مئی',

# Bits of text used by many pages
'categories'      => '{{PLURAL:$1|زمرہ|زمرہ جات}}',
'pagecategories'  => '{{PLURAL:$1|زمرہ|زمرہ جات}}',
'category_header' => 'زمرہ "$1" میں مضامین',
'subcategories'   => 'ذیلی ذمرہ جات',

'about'         => 'تعارف',
'newwindow'     => '(نـئی ونـڈو میـں)',
'cancel'        => 'منسوخ',
'moredotdotdot' => 'اور...',
'mypage'        => 'میرا صفحہ',
'mytalk'        => 'میری گفتگو',
'anontalk'      => 'اس IP کیلیے بات چیت',
'navigation'    => 'رہنمائی',

'returnto'          => 'واپس $1۔',
'tagline'           => 'وکیپیڈیا سے',
'help'              => 'معاونت',
'search'            => 'تلاش',
'searchbutton'      => 'تلاش',
'go'                => 'چلو',
'searcharticle'     => 'چلو',
'history'           => 'تاریخچۂ صفحہ',
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
'specialpage'       => 'خصوصی صفحہ',
'articlepage'       => 'مندرجاتی صفحہ دیکھیۓ',
'talk'              => 'تبادلۂ خیال',
'views'             => 'خیالات',
'toolbox'           => 'آلات',
'userpage'          => 'دیکھیں صارف کا صفحہ',
'viewhelppage'      => 'صفحۂ معاونت دیکھیے',
'otherlanguages'    => 'دیگر زبانیں',
'redirectedfrom'    => '($1 سے پلٹایا گیا)',
'redirectpagesub'   => 'لوٹایا گیا صفحہ',
'lastmodifiedat'    => 'آخری بار تدوین $2, $1 کو کی گئی۔', # $1 date, $2 time
'protectedpage'     => 'محفوظ شدہ صفحہ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} کا تعارف',
'aboutpage'         => 'Project:تعارف',
'copyright'         => 'تمام مواد $1 کے تحت میسر ہے۔',
'copyrightpagename' => 'ویـکـیـپـیـڈ یـا حق تصنیف',
'copyrightpage'     => '{{ns:project}}:حقوق تصانیف',
'currentevents'     => 'تعارف وکیپیڈیا',
'currentevents-url' => 'Project:تعارف وکیپیڈیا',
'disclaimers'       => 'اعلانات',
'edithelp'          => 'معاونت براۓ ترمیم',
'faq'               => 'معلوماتِ عامہ',
'faqpage'           => 'Project:معلوماتِ عامہ',
'helppage'          => 'Help:فہرست',
'mainpage'          => 'صفحہ اول',
'portal'            => 'دیوان عام',
'portal-url'        => 'Project:دیوان عام',
'privacy'           => 'اصول براۓ اخفائے راز',
'sitesupport'       => 'رابطہ',
'sitesupport-url'   => 'Project:رابطہ',

'badaccess-group0' => 'آپ متمنی عمل کا اجراء کرنے کے مُجاز نہیں۔',

'ok'                  => 'ٹھیک ہے',
'pagetitle'           => '$1 - وکیپیڈیا',
'youhavenewmessages'  => 'آپکے لیۓ ایک $1 ہے۔ ($2)',
'newmessageslink'     => 'نیا پیغام',
'newmessagesdifflink' => 'تـجـدیـد مـاقـبل آخـر سے فـرق',
'editsection'         => 'ترمیم',
'editold'             => 'ترمیم',
'toc'                 => 'فہرست',
'showtoc'             => 'دکھائیں',
'hidetoc'             => 'غائب کریں',
'thisisdeleted'       => 'دیکھیں یا بحال کریں $1؟',
'restorelink'         => '$1 ترامیم ضائع کردی',
'feed-atom'           => 'ایٹم',
'feed-rss'            => 'آر ایس ایس',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'مضمون',
'nstab-user'      => 'صفحۂ صارف',
'nstab-special'   => 'خاص',
'nstab-project'   => 'صفحۂ منصوبہ',
'nstab-image'     => 'فائل',
'nstab-mediawiki' => 'پیغام',
'nstab-template'  => 'سانچہ',
'nstab-help'      => 'معاونت',
'nstab-category'  => 'زمرہ',

# General errors
'noconnect'         => 'بہ تاسف! ویکی کو چند طرزیاتی (ٹکنیکی) مشکلات کا سامنا ہے، اساسی موادی کمک کنندہ ( ڈیٹا بیس سرور ) سے تک پہنچنے میں ناکامی ہوئی۔ <br />$1',
'badarticleerror'   => 'اس صفحہ پر یہ عمل انجام نہیں دیا جاسکتا۔',
'cannotdelete'      => 'صفحہ یا مِلَف کو حذف نہیں کیا جا سکا۔ (ہوسکتا ہے کہ اسے پہلے ہی کسی نے حذف کردیاہو۔)',
'viewsource'        => 'مسودہ',
'viewsourcefor'     => 'براۓ $1',
'protectedpagetext' => 'اس صفحہ کو تدوین سے محفوظ رکھنے کیلیے مقفل کر دیا گیا ہے۔',
'viewsourcetext'    => 'آپ صرف مسودہ دیکھ سکتے ہیں اور اسکی نقل اتار سکتے ہیں:',
'editinginterface'  => "'''انتباہ:''' آپ ایک ایسا صفحہ مرتب کر رہے ہیں کہ جو مصنع لطیف (سوفٹ ویئر) کے لیۓ وجیھت (انٹرفیس) مہیا کرنے کے لیۓ استعمال کیا جاتا ہے۔ اس صفحہ میں کی جانے والی ترمیم ، دیگر صارفوں کے لیۓ وجیھت کو تبدیل کردے گی۔",

# Login and logout pages
'logouttitle'                => 'اخراج صارف',
'logouttext'                 => '<strong>اب آپ خارج ہوچکے ہیں</strong><br />
آپ خفی الاسم {{SITENAME}}  کا استعمال جاری رکھ سکتے ہیں، یا دوبارہ اسی نام یا مختلف نام سے داخل بھی ہو سکتے ہیں۔  یہ یاد آوری کرلیجیۓ کہ کچھ صفحات ایسے نظر آتے رہیں گے کہ جیسے ابھی آپ خارج نہیں ہوۓ ، جب تک آپ اپنے تفصحہ (براؤزر) کا ابطن (cache) صاف نہ کردیں۔',
'welcomecreation'            => '== خوش آمدید، $1 ! ==
آپ کا کھاتہ بنا دیا گیا ہے۔ اپنی ویـکـیـپـیـڈ یـا کی ترجیحات تبدیل کرنا مت بھولیں۔',
'loginpagetitle'             => 'داخلہ صارف',
'yourname'                   => 'اسمِ رکنیت',
'yourpassword'               => 'کلمۂ شناخت',
'yourpasswordagain'          => 'کلمۂ شناخت دوبارہ لکھیں',
'remembermypassword'         => 'مجھے یاد رکھیں',
'loginproblem'               => '<b>داخلے میں کوئی مسلہ درپیش ہے</b><br />دوبارہ اندراج کیجیۓ!',
'alreadyloggedin'            => '<strong>$1، آپ پہلے ہی داخل حالت میں ہیں!</strong><br />',
'login'                      => 'داخل ہوں',
'loginprompt'                => 'ویکیپیڈیا میں داخلے کیلۓ آپکے پاس قند (کوکیز) مجازہوناچاہیں۔',
'userlogin'                  => 'کھاتہ بنائیں یا اندراج کریں',
'logout'                     => 'اخراج',
'userlogout'                 => 'خارج ہوجائیں',
'nologin'                    => 'کیا آپ نے کھاتہ نہیں بنایا ہوا؟ $1۔',
'nologinlink'                => 'کھاتا بنائیں',
'createaccount'              => 'نیا کھاتہ بنائیں',
'createaccountmail'          => 'بذریعۂ برقی ڈاک',
'badretype'                  => 'درج شدہ کلمۂ شناخت اصل سے مطابقت نہیں رکھتا۔',
'userexists'                 => 'آپ نےجونام درج کیا ہے پہلے سے زیراستعمال ہے۔ مختلف نام استعمال کریں۔',
'youremail'                  => '٭ برقی خط',
'username'                   => 'اسم صارف',
'uid'                        => 'صارف نمبر:',
'yourrealname'               => '* اصلی نام',
'yourlanguage'               => 'زبان:',
'yournick'                   => 'لقب',
'email'                      => 'برقی خط',
'loginerror'                 => 'داخلے میں غلطی',
'loginsuccesstitle'          => 'داخلہ کامیاب',
'loginsuccess'               => "'''اب آپ ویکیپیڈیا میں بنام \"\$1\" داخل ہوچکے ہیں۔'''",
'nosuchuser'                 => '"$1" کے نام سے کوئی صارف موجود نہیں۔  براۓکرم ہجوں کے درست اندراج کی تصدیق کرلیجیۓ ، یا آپ چاہیں تو نیا کھاتا بھی بنا سکتے ہیں۔',
'wrongpassword'              => 'آپ نے غلط کلمۂ شناخت درج کیا ہے۔ دوبارہ کو شش کریں۔',
'wrongpasswordempty'         => 'کلمۂ شناخت ندارد۔ دوبارہ کوشش کریں۔',
'mailmypassword'             => 'کلمۂ شناخت بذریعہ برقی خط',
'passwordsent'               => 'ایک نیا کلمۂ شناخت "$1" کے نام سے بننے والی برقی ڈاک کے پتے کیلیے بھیج دیا گیا ہے۔ 
جب وہ موصول ہو جاۓ تو براہ کرم اسکے ذریعے دوبارہ داخل ہوں۔',
'mailerror'                  => 'مسلہ دوران ترسیل خط:$1',
'acct_creation_throttle_hit' => 'عرض معذرت، چونکہ آپ پہلے ہی $1 کھاتے بنا چکے ہیں اس لیے مزید نہیں بنا سکتے۔',
'emailauthenticated'         => 'آپ کے برقی خط کے پتے کی تصدیق $1 کو کی گئی۔',
'accountcreated'             => 'تخلیقِ کھاتہ',
'accountcreatedtext'         => 'تخیلقِ کھاتۂ صارف براۓ $1۔',

# Edit page toolbar
'headline_sample' => 'شہ سرخی',
'headline_tip'    => 'شہ سرخی درجہ دوم',

# Edit pages
'summary'              => 'خلاصہ',
'subject'              => 'مضمون/شہ سرخی',
'minoredit'            => 'معمولی ترمیم',
'watchthis'            => 'یہ صفحہ زیر نظر کیجیۓ',
'savearticle'          => 'محفوظ',
'preview'              => 'نمائش',
'showpreview'          => 'نمائش',
'anoneditwarning'      => 'آپ ویکیپیڈیا میں داخل نہیں ہوۓ لہذا آپکا IP پتہ اس صفحہ کے تاریخچہ ء ترمیم میں محفوظ ہوجاۓ گا۔',
'summary-preview'      => 'نمائش خلاصہ',
'whitelistedittext'    => 'ترمیم و تدوین کیلیے آپ کا [[Special:Userlogin|داخل ہونا]] لا زمی ہے۔',
'loginreqtitle'        => 'داخلہ / اندراج لازم',
'loginreqlink'         => 'داخلہ',
'accmailtitle'         => 'کلمہ شناخت بھیج دیا گیا۔',
'accmailtext'          => '"$1" کیلیۓ کلمہ شناخت $2 کو ارسال کردیا گیا۔',
'newarticle'           => '(نیا)',
'newarticletext'       => 'آپ ایک ایسے صفحے کے ربط تک آگۓ ہیں جو ابھی موجود نہیں۔ اگر آپ اس عنوان سے صفحہ بنانا چاہتے ہیں تو اپنا مضمون نیچے دیۓ گۓ احاطہ میں تحریر کیجیۓ اور محفوظ کردیجیۓ (مزید معلومات کیلیۓ معاونت کا صفحہ ملاحظہ کیجیۓ)۔ اگر آپ غلطی سے یہاں پہنچے ہیں تو واپسی کے لیۓ اپنے تصفحہ (براؤزر) کا بیک بٹن ٹک کیجیۓ۔',
'anontalkpagetext'     => "----''یہ صفحہ ایک ایسے صارف کا ہے جنہوں نے یا تو اب تک اپنا کھاتا نہیں بنایا یا پھر وہ اسے استعمال نہیں کر رہے/ رہی ہیں۔ لہذا ہمیں انکی شناخت کے لیۓ ایک اعدادی آئی پی پتہ استعمال کرنا پڑرہا ہے۔ اس قسم کا آئی پی ایک سے زائد صارفین کے لیۓ مشترک بھی ہوسکتا ہے۔ اگر آپکی موجودہ حیثیت ایک گمنام صارف کی ہے اور آپ محسوس کریں کہ اس صفحہ پر آپکی جانب منسوب یہ بیان غیرضروری ہے تو براہ کرم [[Special:Userlogin|کھاتا بنائیے یا داخل نوشتہ (لاگ ان) ہوں]] تاکہ مستقبل میں آپکو، گمنام صارفین میں شمار کرنے سے پرہیز کیا جاسکے۔\"",
'note'                 => '<strong>نوٹ:</strong>',
'previewnote'          => 'یاد رکھیں، یہ صرف نمائش ہے ۔آپ کی ترامیم ابھی محفوظ نہیں کی گئیں۔',
'editing'              => 'آپ "$1" میں ترمیم کر رہے ہیں۔',
'editingsection'       => '$1 کے قطعہ کی تدوین',
'editingcomment'       => 'زیرترمیم $1 (تبصرہ)',
'editconflict'         => 'تنازعہ ترمیم:$1',
'yourtext'             => 'آپ کی تحریر',
'editingold'           => '<strong>انتباہ: آپ اس صفحے کا ایک پرانا مسودہ مرتب کررہے ہیں۔ اگر آپ اسے محفوظ کرتے ہیں تو اس صفحے کے اس پرانے مسودے سے اب تک کی جانے والی تمام تدوین ضائع ہو جاۓ گی۔</strong>',
'yourdiff'             => 'تضادات',
'templatesused'        => 'اس صفحے پر استعمال ہونے والے سانچے:',
'templatesusedsection' => 'اس قطعے میں استعمال ہونے والے سانچے:',

# History pages
'revhistory'       => 'تـجدید تاریخـچہ',
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
'editcurrent'             => 'اس صفحے کا رائج متن مرتب کیجیۓ۔',
'compareselectedversions' => 'منتخب متـن کا موازنہ',

# Search results
'searchresults'         => 'تلاش کا نتیجہ',
'searchresulttext'      => 'ویکیپیڈیا میں تلاش کے بارے میں مزید معلومات کے لیۓ، ویکیپیڈیا میں تلاش کا صفحہ دیکھیۓ۔',
'searchsubtitle'        => "آپ کی تلاش براۓ '''[[:$1]]'''",
'searchsubtitleinvalid' => "آپ کی تلاش براۓ '''$1'''",
'noexactmatch'          => '"$1" کے عنوان سے کوئی صفحہ موجود نہیں۔ آپ اگر چاہیں تو اس نام سے  [[:$1|صفحہ بنا سکتے ہیں]]',
'prevn'                 => 'پچھلے $1',
'nextn'                 => 'اگلے $1',
'viewprevnext'          => 'دیکھیں($1) ($2) ($3)۔',
'powersearch'           => 'تلاش کریں',
'blanknamespace'        => '(مرکز)',

# Preferences page
'preferences'       => 'ترجیحات',
'mypreferences'     => 'میری ترجیہات',
'prefsnologin'      => 'نا داخل شدہ حالت',
'changepassword'    => 'کلمۂ شناخت تبدیل کریں',
'math'              => 'ریاضی',
'datetime'          => 'تاریخ و وقت',
'prefs-rc'          => 'حالیہ تبدیلیاں',
'prefs-misc'        => 'دیگر',
'saveprefs'         => 'محفوظ',
'oldpassword'       => 'پرانا کلمۂ شناخت:',
'newpassword'       => 'نیا کلمۂ شناخت',
'retypenew'         => 'نیا کلمۂ شناخت دوبارہ درج کریں:',
'rows'              => 'قـطاریں:',
'searchresultshead' => 'تلاش',
'savedprefs'        => 'آپ کی ترجیہات محفوظ کر لی گئی ہیں۔',
'timezonelegend'    => 'منطقۂ وقت',
'localtime'         => 'مقامی وقت',
'allowemail'        => 'دوسرے صارفین کو برقی خظ لکھنے کا اختیار دیں',
'default'           => 'طے شدہ',
'files'             => 'فائلیں',

# User rights
'userrights-user-editname' => 'اسمِ رکنیت داخل کریں:',

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
'sectionlink'       => '<font face="Symbol">&amp;#172;</font>',

# Recent changes linked
'recentchangeslinked' => 'متعلقہ تبدیلیاں',

# Upload
'upload'            => 'زبراثقال ِملف (فائل اپ لوڈ)',
'uploadbtn'         => 'زبراثقال ملف (اپ لوڈ فائل)',
'reupload'          => 'زبراثقال مکرر',
'reuploaddesc'      => 'زبراثقال ورقہ (فارم) کیجانب واپس۔',
'uploadnologin'     => 'آپ داخل شدہ حالت میں نہیں',
'uploadnologintext' => 'زبراثقال ملف (فائل اپ لوڈ) کے لیۓ آپکو  [[Special:Userlogin|داخل شدہ]] حالت میں ہونا لازم ہے۔',
'uploadlog'         => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpage'     => 'نوشتۂ زبراثقال (اپ لوڈ لاگ)',
'uploadlogpagetext' => 'درج ذیل میں حالیہ زبراثقال (اپ لوڈ) کی گئی املاف (فائلوں) کی فہرست دی گئی ہے۔',
'filedesc'          => 'خلاصہ',
'fileuploadsummary' => 'خلاصہ :',
'uploadedfiles'     => 'زبراثقال ملف (فائل اپ لوڈ)',
'ignorewarning'     => 'انتباہ نظرانداز کرتے ہوۓ بہرصورت ملف (فائل) کو محفوظ کرلیا جاۓ۔',
'ignorewarnings'    => 'ہر انتباہ نظرانداز کردیا جاۓ۔',
'badfilename'       => 'ملف (فائل) کا نام "$1" ، تبدیل کردیا گیا۔',
'fileexists'        => 'اس نام سے ایک ملف (فائل) پہلے ہی موجود ہے، اگر آپ کو یقین نہ ہو کہ اسے حذف کردیا جانا چاہیۓ تو براہ کرم  $1 کو ایک نظر دیکھ لیجیۓ۔',
'uploadwarning'     => 'انتباہ بہ سلسلۂ زبراثقال',
'savefile'          => 'فائل محفوظ کریں',
'uploadedimage'     => 'زبراثقال (اپ لوڈ) براۓ "[[$1]]"',
'sourcefilename'    => 'اسم ملف (فائل) کا منبع',
'destfilename'      => 'تعین شدہ اسم ملف',
'watchthisupload'   => 'یہ صفحہ زیر نظر کیجیۓ',

# Image list
'imagelist'           => 'فہرست فائل',
'ilsubmit'            => 'تلاش',
'byname'              => 'بالحاظ اسم',
'bydate'              => 'بالحاظ تاریخ',
'bysize'              => 'بالحاظ جسامت',
'deleteimg'           => 'ضائع',
'deleteimgcompletely' => 'اس مِلَف کی تمام تر تجدید ضائع کیجیئے۔',
'imagelinks'          => 'روابط',
'linkstoimage'        => 'اس ملف (فائل) سے درج ذیل صفحات رابطہ رکھتے ہیں:',
'nolinkstoimage'      => 'ایسے کوئی صفحات نہیں جو اس ملف (فائل) سے رابطہ رکھتے ہوں۔',

# MIME search
'download' => 'زیراثقال (ڈاؤن لوڈ)',

# List redirects
'listredirects' => 'فہرست متبادل ربط',

# Unused templates
'unusedtemplates' => 'غیر استعمال شدہ سانچے',

# Statistics
'statistics'    => 'اعداد و شمار',
'sitestats'     => 'وکیپیڈیا کے اعدادوشمار',
'userstats'     => 'ارکان کے اعداد و شمار',
'sitestatstext' => "اردو ویکیپیڈیا کے ذخیرے میں اب تک کل  '''$1''' صفحات ہیں۔ انمیں تبادلہءخیال صفحات ، ویکیپیڈیا کے بارے میں صفحات ، سٹب صفحات ، پلٹائے گۓ صفحات اور چند دیگر ایسے صفحات شامل ہیں جو کہ ممکنہ طور پر مقالات یا مضامین نہیں کہے جاسکتے۔ ان تمام کو نکال کر  '''$2''' ایسے صفحات ہیں جو کہ بجاطور پر مقالات کے زمرے میں شامل کیۓ جاسکتے ہیں۔ 

اگر صفحات کے تناسب سے دیکھا جاۓ تو ، اردو ویکیپیڈیا کی ابتداء سے اب تک کل '''$4''' صفحات صارفین کی جانب سے  مرتب کیۓ گۓ۔ گویا فی صفحہ '''$5''' بار تدوین ہوئی۔  مزید تفصیل دیکھیں",
'userstatstext' => "اردو ویکیپیڈیا میں '''$1''' مـثـبوت (رجسٹرڈ) صارف ہیں ، جنمیں  '''$2''' (یعنی '''$4%''') منتظمین میں شامل ہیں ، (دیکھیۓ $3) ۔",

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
'allpages'                => 'تمام صفحات',
'randompage'              => 'بےترتیب صفحہ',
'shortpages'              => 'چھوٹے صفحات',
'longpages'               => 'طویل ترین صفحات',
'deadendpages'            => 'مردہ صفحات',
'listusers'               => 'فہرست ارکان',
'specialpages'            => 'خصوصی صفحات',
'spheading'               => 'خصوصی صفحات براۓ تمام صارفین',
'restrictedpheading'      => 'ممنوعہ خاص صفحہ',
'newpages'                => 'جدید صفحات',
'ancientpages'            => 'قدیم ترین صفحات',
'move'                    => 'منتقـل',

# Book sources
'booksources' => 'کتابی وسائل',

'categoriespagetext' => 'مندرجہ ذیل زمرہ جات وکیپیڈیا میں موجود ہیں۔',
'userrights'         => 'صارف کے حقوق کا انتظام',
'version'            => 'ورژن',

# Special:Log
'specialloguserlabel'  => 'صارف:',
'speciallogtitlelabel' => 'عنوان:',
'log'                  => 'نوشتہ جات',

# Special:Allpages
'nextpage'       => 'اگلا صفحہ ($1)',
'prevpage'       => 'پچھلا صفحہ ($1)',
'allpagesfrom'   => 'مطلوبہ حرف شروع ہونے والے صفحات کی نمائش:',
'allarticles'    => 'تمام مقالات',
'allpagesprev'   => 'پچھلا',
'allpagesnext'   => 'اگلا',
'allpagesprefix' => 'مطلوبہ سابقہ سے شروع ہونے والے صفحات کی نمائش:',

# E-mail user
'mailnologintext' => 'دیگر ارکان کو برقی خط ارسال کرنے کیلیۓ لازم ہے کہ آپ [[Special:Userlogin|داخل شدہ]] حالت میں ہوں اور آپ کی [[Special:Preferences|ترجیحات]] ایک درست برقی خط کا پتا درج ہو۔',
'emailuser'       => 'صارف کو برقی خط لکھیں',
'noemailtext'     => 'اس صارف نے برقی خط کے لیے کوئی پتہ فراہم نہیں کیا، یا یہ چاہتا ہے کا اس سے کوئی صارف رابطہ نہ کرے۔',
'emailsubject'    => 'عنوان',
'emailmessage'    => 'پیغام',

# Watchlist
'watchlist'         => 'میری زیرنظرفہرست',
'mywatchlist'         => 'میری زیرنظرفہرست',
'watchlistfor'      => "(براۓ '''$1''')",
'addedwatch'        => 'زیر نظر فہرست میں اندراج کردیاگیا',
'removedwatch'      => 'زیرنظرفہرست سے خارج کر دیا گیا',
'removedwatchtext'  => 'صفحہ "$1" آپ کی زیر نظر فہرست سے خارج کر دیا گیا۔',
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

# Delete/protect/revert
'deletepage'           => 'صفحہ ضائع کریں',
'confirm'              => 'یقین',
'excontent'            => "'$1':مواد تھا",
'excontentauthor'      => "حذف شدہ مواد: '$1' (اور صرف '[[Special:Contributions/$2|$2]]' نے حصہ ڈالا)",
'exblank'              => 'صفحہ خالی تھا',
'confirmdelete'        => 'تائید تنسیخ',
'deletesub'            => '(حذف کیا جارہاہے "$1")',
'historywarning'       => 'انتباہ: جو صفحہ آپ حذف کرنے جارہے ہیں اس سے ایک تاریخچہ منسلک ہے۔',
'confirmdeletetext'    => 'آپ نے اس صفحے کو اس سے ملحقہ تاریخچہ سمیت حذف کرنے کا ارادہ کیا ہے۔ براۓ مہربانی تصدیق کرلیجیۓ کہ آپ اس عمل کے نتائج سے بخوبی آگاہ ہیں، اور یہ بھی یقین کرلیجیۓ کہ آپ ایسا [[{{MediaWiki:Policy-url}}|ویکیپیڈیا کی حکمت عملی]] کے دائرے میں رہ کر کر رہے ہیں۔',
'actioncomplete'       => 'اقدام تکمیل کو پہنچا',
'deletedtext'          => '"$1" کو حذف کر دیا گیا ہے ۔
حالیہ حذف شدگی کے تاریخ نامہ کیلیۓ  $2  دیکھیۓ',
'deletedarticle'       => 'حذف شدہ "[[$1]]"',
'dellogpage'           => 'نوشتۂ حذف شدگی',
'dellogpagetext'       => 'حالیہ حذف شدگی کی فہرست درج ذیل ہے۔',
'deletionlog'          => 'نوشتۂ حذف شدگی',
'deletecomment'        => 'حذف کرنے کی وجہ',
'rollback'             => 'ترمیمات سابقہ حالت پرواپس',
'rollback_short'       => 'واپس سابقہ حالت',
'rollbacklink'         => 'واپس سابقہ حالت',
'rollbackfailed'       => 'سابقہ حالت پر واپسی ناکام',
'cantrollback'         => 'تدوین ثانی کا اعادہ نہیں کیا جاسکتا؛ کیونکہ اس میں آخری بار حصہ لینے والا ہی اس صفحہ کا واحد کاتب ہے۔',
'protectlogpage'       => 'نوشتۂ محفوظ شدگی',
'protectedarticle'     => '"[[$1]]" کومحفوظ کردیا',
'unprotectedarticle'   => '"[[$1]]" کوغیر محفوظ کیا',
'protectcomment'       => 'محفوظ کرنے کی وجہ',
'unprotectsub'         => '("$1" غیر محفوظ کی جا رہی ہے۔)',
'protect-default'      => '(طے شدہ)',
'protect-level-sysop'  => 'صرف منتظمین',

# Undelete
'undelete'         => 'ضائع کردہ صفحات دیکھیں',
'undeletepage'     => 'معائنہ خذف شدہ صفحات',
'viewdeletedpage'  => 'حذف شدہ صفحات دیکھیے',
'undeletebtn'      => 'بحال',
'undeletecomment'  => 'تبصرہ:',
'undeletedarticle' => 'بحال "[[$1]]"',

# Namespace form on various pages
'namespace' => 'جاۓ نام:',
'invert'    => 'انتخاب بالعکس',

# Contributions
'contributions' => 'صارف کا حصہ',
'mycontris'     => 'میرا حصہ',
'contribsub2'    => 'براۓ $1 ($2)',
'uclinks'       => 'دیکھیں آخری $1 تبدیلیاں؛ دیکھیں آخری $2 دن.',
'uctop'         => ' (اوپر)',

'sp-contributions-blocklog' => 'نوشتۂ پابندی',

# What links here
'whatlinkshere' => 'یہاں کس کا رابطہ ہے',
'linklistsub'   => '(فہرست روابط)',
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
'movepage'                => 'منتقلئ صفحہ',
'movepagetext'            => 'نیچے دیا گیا تشکیلہ (فـارم) استعمال کرکے اس صفحہ کا عنوان دوبارہ منتخب کیا جاسکتا ہے، ساتھ ہی اس سے منسلک تاریخچہ بھی نۓ نام پر منتقل ہوجاۓ گا۔ اسکے بعد سے اس صفحے کا پرانا نام ، نۓ نام کی جانب -- لوٹایا گیا صفحہ -- کی حیثیت اختیار کرلے گا۔ لیکن یادآوری کرلیجیۓ دیگر صفحات پر ، پرانے صفحہ کی جانب دیۓ گۓ روابط (لنکس) تبدیل نہیں ہونگے؛ اس بات کو یقینی بنانا ضروری ہے کہ کوئی دوہرا یا شکستہ -- پلٹایا گیا ربط -- نہ رہ جاۓ۔ 

لہذا یہ یقینی بنانا آپکی ذمہ داری ہے کہ تمام روابط درست صفحات کی جانب رہنمائی کرتے رہیں۔

یہ بات بھی ذہن نشین کرلیجیۓ کہ اگر نۓ منتخب کردہ نام کا صفحہ پہلے سے ہی موجود ہو تو ہوسکتا ہے کہ صفحہ منتقل نہ ہو ، ؛ ہاں اگر پہلے سے موجود صفحہ خالی ہے ، یا وہ صرف ایک -- لوٹایا گیا صفحہ -- ہو اور اس سے کوئی تاریخچہ منسلک نہ ہو تو منتقلی ہوجاۓ گی۔ گویا ، کسی خامی کی صورت میں آپ صفحہ کو دوبارہ اسی پرانے نام کی جانب منتقل کرسکتے ہیں اور اس طرح پہلے سے موجود کسی صفحہ میں کوئی حذف و خامی نہیں ہوگی۔

<b><font face="times new roman"> انـتـبـاہ !</font></b> کسی اہم اور مقبول صفحہ کی منتقلی ، غیرمتوقع اور پریشان کن بھی ہی ہوسکتی ہے اس لیۓ ؛ منتقلی سے قبل براہ کرم یقین کرلیجۓ کہ آپ اسکے منطقی نتائج سے باخبر ہیں۔',
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

منتقلی کے سلسلے میں انتخاب کردہ مضمون "[[$1]]" پہلے ہی موجود ہے۔ کیا آپ اسے حذف کرکے منتقلی کیلیۓ راستہ بنانا چاہتے ہیں؟',
'delete_and_move_confirm' => 'ہاں، صفحہ حذف کر دیا جائے',
'delete_and_move_reason'  => 'منتقلی کے سلسلے میں حذف',

# Export
'export' => 'برآمد صفحات',

# Namespace 8 related
'allmessages'               => 'نظامی پیغامات',
'allmessagesname'           => 'نام',
'allmessagesdefault'        => 'طے شدہ متن',
'allmessagescurrent'        => 'موجودہ متن',
'allmessagestext'           => 'یہ میڈیاویکی: جاۓ نام میں دستیاب نظامی پیغامات کی فہرست ہے۔',
'allmessagesnotsupportedUI' => 'آپکی بین السطحی زبان <b>$1</b> اس وقوع پر  Special:AllMessages میں قابل شناخت نہیں۔',
'allmessagesfilter'         => 'مِصفاہ اسم پیغام:',
'allmessagesmodified'       => 'فقط ترامیم کا اظہار',

# Special:Import
'import' => 'درآمد صفحات',

# Attribution
'anonymous' => '{{SITENAME}} گمنام صارف',
'and'       => 'اور',
'others'    => 'دیگر',

# Spam protection
'subcategorycount'       => 'اس زمرے  {{PLURAL:$1|کا ایک ذیلی زمرہ ہے|کے $1 ذیلی زمرہ جات ہیں}}۔',
'categoryarticlecount'   => 'اس زمرے میں {{PLURAL:$1|ایک مضمون ہے|$1 مضامین ہیں}}۔',
'listingcontinuesabbrev' => '۔جاری',

# Image deletion
'deletedrevision' => 'حذف شدہ پرانی ترمیم $1۔',

# Browsing diffs
'previousdiff' => '> گذشتہ فرق',
'nextdiff'     => '< اگلا فرق',

'newimages'    => 'نئی فائلوں کی گیلری',
'showhidebots' => '($1 بوٹ)',

'passwordtooshort' => 'آپکا منتخب کردہ کلمۂ شناخت بہت مختصر ہے۔ اسے کم از کم $1 حروف پر مشتمل ہونا چاہیۓ۔',

# Metadata
'metadata' => 'میٹا ڈیٹا',

'exif-meteringmode-0' => 'نامعلوم',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'تمام',
'watchlistall2'    => 'تمام',
'namespacesall'    => 'تمام',

# Delete conflict
'deletedwhileediting' => 'انتباہ: آپ کے ترمیم شروع کرنے کے بعد یہ صفحہ حذف کیا جا چکا ہے!',

# HTML dump
'redirectingto' => 'کی جانب پلٹایا گیا [[$1]]...',

# action=purge
'confirm_purge_button' => 'جی!',

'searchnamed'   => "مضمون بنام ''$1'' کیلیۓ تلاش۔",
'articletitles' => "''$1'' سے شروع ہونے والے مضامین",
'hideresults'   => 'نتیجہ چھپائیں',

# Auto-summaries
'autosumm-blank'   => 'تمام مندرجات حذف',
'autoredircomment' => '[[$1]] سے رجوع مکرر', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'نیا صفحہ: $1',

);


