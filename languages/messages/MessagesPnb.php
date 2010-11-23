<?php
/** Western Punjabi (پنجابی)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Arslan
 * @author Khalid Mahmood
 * @author ZaDiak
 */

$linkPrefixExtension = true;
$fallback8bitEncoding = 'windows-1256';

$rtl = true;
$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
	# Underlines seriously harm legibility. Force off:
	'underline' => 0,
);

$messages = array(
# User preference toggles
'tog-justify'                => 'پیراگراف ثابت کرو',
'tog-hideminor'              => 'چھوٹیاں تبدیلیاں چھپاؤ',
'tog-extendwatchlist'        => 'نظر تھلے رکھے صفحے نوں ودھاو, تاکہ اوہ تبدیلیاں جیڑیاں کم دے قابل نیں ویکھیاں جا سکن',
'tog-watchcreations'         => 'جیڈے صفحے میں بناندا واں اوہ میری اکھ تھلے کر دیو',
'tog-watchdefault'           => 'جیڈے صفحیاں چ میں لکھداں اوہ میری اکھ تھلے کر دیو',
'tog-watchmoves'             => 'جیڈے صفحے میں لے چلداں اوہ میری اکھ تھلے کر دیو',
'tog-watchdeletion'          => 'جیڈے صفحے میں مٹانداں اوہ میری اکھ تھلے کر دیو',
'tog-enotifwatchlistpages'   => 'اگر میری اکھ تھلیاں صفحیاں چوں کسے چ تبدیلی ہوۓ، تے مینوں ای میل کر دیو',
'tog-enotifusertalkpages'    => 'اگر میرے گلاں باتاں آلے صفحے چ کوئی تبدیلی کرے، تے مینوں ای میل کر دیو',
'tog-enotifminoredits'       => 'صفحیاں چ چھوٹیاں موٹیاں تبدیلیاں تے وی مینوں ای میل کر دیو',
'tog-enotifrevealaddr'       => 'میرے ای میل دے پتے نوں سندیسے آلی ای میل دے وچ وکھاؤ۔',
'tog-shownumberswatching'    => 'ویکھن آلے لوکاں دی گنتی وکھاؤ۔',
'tog-oldsig'                 => 'ہلے آلے دستخط وکھاؤ۔',
'tog-fancysig'               => 'دستخط نوں وکی ٹیکسڈ ونگوں؎ ورتو(without an automatic link)',
'tog-watchlisthideown'       => 'میری اپنی لکھائی نوں اکھ تھلیوں لکاؤ',
'tog-watchlisthidebots'      => 'بوٹ دی لکھائی اکھ تھلیوں لکاؤ',
'tog-watchlisthideminor'     => 'چھوٹی موٹی لکھائی اکھ تھلیوں لکاؤ',
'tog-watchlisthidepatrolled' => 'نکی لکھائی اکھ تھلوں لکاؤ',
'tog-ccmeonemails'           => 'مینوں اوہناں ای میلاں دیاں کاپیاں بھیجو جیہڑیاں میں دوجیاں نوں بھیجاں۔',
'tog-showhiddencats'         => 'لکیاں کیٹاگریاں وکھاؤ',

'underline-always' => 'ہمیشہ',
'underline-never'  => 'کدی وی نئیں',

# Dates
'sunday'        => 'اتوار',
'monday'        => 'پیر',
'tuesday'       => 'منگل',
'wednesday'     => 'بدھ',
'thursday'      => 'جمعرات',
'friday'        => 'جمعہ',
'saturday'      => 'ہفتہ',
'sun'           => 'اتوار',
'mon'           => 'سوموار',
'tue'           => 'منگل',
'wed'           => 'بدھ',
'thu'           => 'جمعرات',
'fri'           => 'جمعہ',
'sat'           => 'ہفتہ',
'january'       => 'جنوری',
'february'      => 'فروری',
'march'         => 'مارچ',
'april'         => 'اپریل',
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
'april-gen'     => 'اپریل',
'may-gen'       => 'مئی',
'june-gen'      => 'جون',
'july-gen'      => 'جولائی',
'august-gen'    => 'اگست',
'september-gen' => 'ستمبر',
'october-gen'   => 'اکتوبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'دسمبر',
'jan'           => 'جنوری',
'feb'           => 'فروری',
'mar'           => 'مارچ',
'apr'           => 'اپریل',
'may'           => 'مئی',
'jun'           => 'جون',
'jul'           => 'جولائی',
'aug'           => 'اگست',
'sep'           => 'ستمبر',
'oct'           => 'اکتوبر',
'nov'           => 'نومبر',
'dec'           => 'دسمبر',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|گٹھ|گٹھیاں}}',
'category_header'          => '"$1" کیٹاگری وچ صفحے',
'subcategories'            => 'تھلے آلی کیٹاگری',
'category-media-header'    => 'اس "$1" کیٹاگری وچ میڈيا',
'category-empty'           => "''اس کیٹاگری وچ کوئی صفحہ یا میڈیا موجود نہیں۔''",
'hidden-categories'        => '{{PLURAL:$1|چھپی گٹھ|چھپی گٹھیاں}}',
'hidden-category-category' => 'لکائیاں ٹولیاں',
'category-subcat-count'    => '{{PLURAL:$2|اس گٹھ دی صرف اکو تھلے آلی نکی گٹھ اے|اس گٹھ دیاں $2 چوں   {{PLURAL:$1|نکی گٹھ|$1 نکی گٹھیاں}}}} نیں۔',
'category-article-count'   => '{{PLURAL:$2|اس گٹھ چ اکو تھلے آلا صفحہ اے۔|تھلے {{PLURAL:$1|آلا صفحہ|آلے صفحے}} $2 چوں اس گٹھ دے صفحے نیں۔}}',
'listingcontinuesabbrev'   => 'جاری',

'about'         => 'بارے چ',
'article'       => 'مضمون آلا صفحہ',
'newwindow'     => '(نئی ونڈو چ کھولو)',
'cancel'        => 'ختم',
'moredotdotdot' => 'مزید۔۔۔۔',
'mypage'        => 'میرا صفحہ',
'mytalk'        => 'میریاں گلاں',
'anontalk'      => 'اس آئی پی آسطے گل کرو',
'navigation'    => 'تلاش',

# Cologne Blue skin
'qbfind'         => 'کھوج',
'qbbrowse'       => 'لبو',
'qbedit'         => 'لکھو',
'qbpageoptions'  => 'اے صفحہ',
'qbpageinfo'     => 'ماحول',
'qbmyoptions'    => 'میرے صفحے',
'qbspecialpages' => 'خاص صفحے',

# Vector skin
'vector-action-delete'    => 'مکاؤ',
'vector-action-move'      => 'ٹرو',
'vector-action-protect'   => 'بچاؤ',
'vector-action-unprotect' => 'نا بچاؤ',
'vector-view-create'      => 'بناؤ',
'vector-view-edit'        => 'لکھو',
'vector-view-viewsource'  => 'ویکھو',

'errorpagetitle'    => 'مسئلہ',
'returnto'          => 'واپس $1 چلو',
'tagline'           => 'سے {{SITENAME}}',
'help'              => 'مدد',
'search'            => 'کھوج',
'searchbutton'      => 'کھوج',
'go'                => 'جاؤ',
'searcharticle'     => 'چلو جی',
'history'           => 'پچھلے کم',
'history_short'     => 'ریکارڈ',
'info_short'        => 'معلومات',
'printableversion'  => 'چھپن آلا صفحہ',
'permalink'         => 'پکا تعلق',
'print'             => 'چھاپو',
'edit'              => 'لکھو',
'create'            => 'بناؤ',
'editthispage'      => 'اس صفحہ تے لکھو',
'create-this-page'  => 'اے صفحہ بناؤ',
'delete'            => 'مٹاؤ',
'deletethispage'    => 'اے صفحہ مٹاؤ',
'protect'           => 'بچاؤ',
'protect_change'    => 'تبدیل کرو',
'protectthispage'   => 'اے صفحہ بچاؤ',
'unprotect'         => 'نا بچاؤ',
'unprotectthispage' => 'اے صفحہ نا بچاؤ',
'newpage'           => 'نیا صفحہ',
'talkpage'          => 'اس صفحے دے بارے چ گل بات کرو',
'talkpagelinktext'  => 'گل بات',
'specialpage'       => 'خاص صفحہ',
'personaltools'     => 'ذاتی اوزار',
'postcomment'       => 'نویں ونڈ',
'articlepage'       => 'مضمون آلا صفحہ',
'talk'              => 'گل بات',
'views'             => 'منظر',
'toolbox'           => 'اوزار',
'userpage'          => 'ورتن آلے دا صفحہ ویکھو',
'projectpage'       => 'منصوبے آلا صفحہ ویکھو',
'imagepage'         => 'میڈیا آلا صفحہ ویکھو',
'mediawikipage'     => 'سنیعا آلا صفحہ ویکھو',
'templatepage'      => 'سچے آلا صفحہ ویکھو',
'viewhelppage'      => 'مدد آلا صفحہ ویکھو',
'categorypage'      => 'گٹھ آلا صفحہ ویکھو',
'viewtalkpage'      => 'گلاں باتاں وکھاؤ',
'otherlanguages'    => 'دوجیاں زبانں وچ',
'redirectedfrom'    => '(لیایا گیا $1)',
'redirectpagesub'   => 'صفحہ ریڈائریکٹ کرو',
'lastmodifiedat'    => 'This page was last modified on $1, at $2.
اس صفحے نوں آخری آری $1 تریخ نوں $2 وجے بدلیا گیا۔',
'protectedpage'     => 'بجایا صفحہ',
'jumpto'            => 'جاو:',
'jumptonavigation'  => 'مدد',
'jumptosearch'      => 'کھوج',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'بارے چ {{SITENAME}}',
'aboutpage'            => 'Project:بارے وچ',
'copyright'            => 'مال $1 دے تھلے ہے گا اے۔',
'copyrightpage'        => '{{ns:project}}:نقل دے حق',
'currentevents'        => 'اج کل دے واقعات',
'currentevents-url'    => 'Project:اج کل دے واقعات',
'disclaimers'          => 'منکرنا',
'disclaimerpage'       => 'Project:عام منکرنا',
'edithelp'             => 'لکھن وچ مدد',
'edithelppage'         => 'Help:لکھنا',
'helppage'             => 'Help:فہرست',
'mainpage'             => 'پہلا صفہ',
'mainpage-description' => 'پہلا صفہ',
'policy-url'           => 'Project:پالیسی',
'portal'               => 'بیٹھک',
'portal-url'           => 'Project:بیٹھک',
'privacy'              => 'حفاظتی پالیسی',
'privacypage'          => 'Project:حفاظتی پالیسی',

'badaccess' => 'اجازت دے وچ غلطی اے',

'ok'                  => 'ٹھیک اے',
'retrievedfrom'       => 'توں لیا "$1"',
'youhavenewmessages'  => 'تواڈے لئی $1 ($2).',
'newmessageslink'     => 'نواں سنیآ',
'newmessagesdifflink' => 'آخری تبدیلی',
'editsection'         => 'لکھو',
'editold'             => 'لکھو',
'viewsourceold'       => 'لکھیا ویکھو',
'editlink'            => 'لکھو',
'viewsourcelink'      => 'لکھائی وکھاؤ',
'editsectionhint'     => 'حصہ لکھو: $1',
'toc'                 => 'حصے',
'showtoc'             => 'کھولو',
'hidetoc'             => 'چپھاؤ',
'site-rss-feed'       => '$1 RSS Feed',
'site-atom-feed'      => '$1 Atom Feed',
'page-rss-feed'       => '"$1" RSS Feed',
'page-atom-feed'      => '"$1" Atom Feed',
'red-link-title'      => '$1 (اے صفحہ حلے تک نئیں بنایا گیا)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'صفحہ',
'nstab-user'      => 'ورتن والے دا صفحہ',
'nstab-media'     => 'میڈیا آلا صفحہ',
'nstab-special'   => 'خاص صفحہ',
'nstab-project'   => 'منصوبے دا صفحہ',
'nstab-image'     => 'فائل',
'nstab-mediawiki' => 'سنیعا',
'nstab-template'  => 'سانچہ',
'nstab-help'      => 'مدد آلا صفحہ',
'nstab-category'  => 'کیٹاگری',

# Main script and global functions
'nosuchaction'      => 'کوئی ایسا کم نئیں',
'nosuchspecialpage' => 'انج دا کوئی خاص صفحہ نئیں',

# General errors
'error'              => 'مسئلا',
'databaseerror'      => 'ڈیٹابیس دی غلطی',
'missing-article'    => 'وکیپیڈیا نوں تواڈے لفظ "$1" $2 دے نال دا صفحہ نئیں لبیا جیڑا کے اینوں کھوج لینا چائیدا سی۔

اے مسئلہ عام طور تے اس ویلے ہوندا اے جدوں تسی کسی پرانے جوڑ یا فیر کسی صفحے دی تاریخ چ جا کے جوڑ تے کلک کر دے اوہ۔

اگر انج نئیں فیر تسی سافٹویئر چ اک مسئلا لب لیا اے۔ توانوں اے گل کسی مکھیے نوں دسو۔',
'missingarticle-rev' => '(رویژن#: $1)',
'internalerror'      => 'اندر دا مسئلا',
'internalerror_info' => 'اندر دا مسئلا: $1',
'badtitle'           => 'پیڑا عنوان',
'badtitletext'       => 'منگیا گۓ صفحہ دا ناں غلط اے، خالی اے یا غلط تریقے نال جوڑیا گیا اے۔<div/>
ہوسکدا اے ایدے چ اک دو ھندسے ایسے ہون جیڑے عنوان وچ استعمال نہیں کیتے جاسکدے۔',
'viewsource'         => 'ویکھو',
'viewsourcefor'      => '$1 لئ',
'viewsourcetext'     => 'تسی اس صفحے دی لکھائی نوں ویکھ تے نقل کر سکدے او:',

# Virus scanner
'virus-unknownscanner' => 'اندیکھا اینٹیوائرس:',

# Login and logout pages
'yourname'                => 'ورتن والہ:',
'yourpassword'            => 'کنجی:',
'yourpasswordagain'       => 'کنجی دوبارہ لکھو:',
'remembermypassword'      => 'اس کمپیوٹر تے میرا لاگن یاد رکھو (for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'          => 'تواڈا علاقہ:',
'login'                   => 'اندر آؤ جی',
'nav-login-createaccount' => 'اندر آؤ / کھاتہ کھولو',
'loginprompt'             => 'اندر آنے آستے تواڈیاں کوکیز آن ہونیاں چائیدیاں نے {{SITENAME}}.',
'userlogin'               => 'اندر آؤ / کھاتہ کھولو',
'logout'                  => 'لاگ توں باہر',
'userlogout'              => 'باہر آؤ',
'notloggedin'             => 'لاگ ان نئیں ہوۓ او',
'nologin'                 => "تواڈا کھاتہ نہیں اے؟ '''$1'''۔",
'nologinlink'             => 'کھاتہ بناؤ',
'createaccount'           => 'کھاتہ بناؤ',
'gotaccount'              => "تواڈا پہلے توں کھاتہ ہے؟ '''$1'''",
'gotaccountlink'          => 'اندر آؤ',
'createaccountmail'       => 'ای میل دے نال',
'badretype'               => 'تواڈی کنجی صحیح نئیں۔',
'loginerror'              => 'لاگ ان چ مسئلا اے',
'noname'                  => 'تسی کوئی پکا ورتن آلا ناں نئیں رکھ رۓ۔',
'loginsuccesstitle'       => 'تسی لاگن ہوگۓ او',
'loginsuccess'            => "'''ہن تسی {{SITENAME}} تے \"\$1\" دے ناں توں لاگ ان او'''",
'nosuchuser'              => 'اس $1 ناں نال کوئی ورتن آلا نہیں۔
اپنی لکھائی درست کرو یا نیا [[Special:UserLogin/signup|کھاتہ بناؤ]]۔',
'nosuchusershort'         => 'اس "<nowiki>$1</nowiki>" ناں دا کوئی ورتن آلا نہيں اے۔

اپنی الف، بے چیک کرو۔',
'nouserspecified'         => 'توانوں اپنا ورتن آلا ناں دسنا ہوۓ گا۔',
'wrongpassword'           => 'تواڈی کنجی سہی نہیں۔<br />
فیر سہی ٹرائی مارو۔',
'wrongpasswordempty'      => 'تواڈی کنجی کم نہیں کر رہی۔<br />
فیر ٹرائی مارو۔',
'passwordtooshort'        => 'تواڈی کنجی ٹھیک نہیں یا بہت جھوٹی اے۔
ایدے چ کم از کم {{PLURAL:$1|$1|اک ھندسہ}} تے کنجی تواڈے ورتن آلے ناں تو مختلف ہونی چائیدی اے۔',
'mailmypassword'          => 'نئی کنجی ای میل کرو',
'passwordremindertitle'   => '{{SITENAME}} لئی نوی عارضی کنجی',
'passwordremindertext'    => 'کسے نے (غالبن تسی $1 آئی پی پتے توں) نوی کنجی ($4){{SITENAME}} واسطے منگی۔ اک عارضی کنجی ورتن والے "$2" دے لئی بنائی گئی سی تے "$3" تے سیٹ کر دتی گئی سی۔ اگر اے تواڈا کم اے تے توانوں اندر آکے اک نوی $5 کنجی چننی پۓ گی۔

اگر کسے ہور نے اے درخواست کیتی اے یا تسی اپنی پرانی کنجی لب لئی اے تے تسی اینوں بدلنا نئیں چاندے تے تسی اس سنعے نوں چھڈو تے پرانی کنجی استعمال کرو۔',
'noemail'                 => 'اس ورتن والے "$1" دا کوئی ای میل پتہ نئیں ہے گا۔',
'passwordsent'            => 'اک نوی کنجی اس ای میل "$1" تے پیجی جاچکی اے۔<br />
جدوں توانوں اے ملے تسی دوبارہ لاگن ہو۔',
'eauthentsent'            => 'اک کنفرمیشن ای میل دتے گۓ ای میل پتے تے پیج دتی گئی اے۔ اس توں پہلاں کہ کوئی دوجی ای میل کھاتے تے پیجی جاۓ، توانوں ای میل چ دتیاں ہدایات تے عمل کرنا ہوۓ گا، تا کے اے پکا ہو سکے کہ اے کھاتہ تواڈا ہی اے۔',
'accountcreated'          => 'کھاتہ کھل گیا',
'loginlanguagelabel'      => 'بولی: $1',

# Password reset dialog
'resetpass_header'    => 'کھاتے دی کنجی بدلو',
'oldpassword'         => 'پرانی کنجی:',
'newpassword'         => 'نوی کنجی:',
'retypenew'           => 'نئی کنجی دوبارہ لکھو:',
'resetpass_submit'    => 'کنجی رکھو تے لاگ ان ہو جاو',
'resetpass_forbidden' => 'کنجی بدلی نئیں جاسکدی',

# Edit page toolbar
'bold_sample'     => 'موٹی لکھائی',
'bold_tip'        => 'موٹی لکھائی',
'italic_sample'   => 'ترچھی لکھائی',
'italic_tip'      => 'ترچھی لکھائی',
'link_sample'     => 'جوڑ',
'link_tip'        => 'اندرونی جوڑ',
'extlink_sample'  => 'http://www.example.com جوڑ دا ناں',
'extlink_tip'     => 'بیرونی جوڑ (remember http:// prefix)',
'headline_sample' => 'شہ سرخی',
'headline_tip'    => 'دوسرے درجے دی سرخی',
'math_sample'     => 'ایتھے فارمولا لاؤ',
'math_tip'        => 'ریاضی دا فارمولا (LaTeX)',
'nowiki_sample'   => 'فارمیٹ نہ ہوئی لکھائی ایتھے پاؤ',
'nowiki_tip'      => 'وکی فارمیٹ رھندیو۔',
'image_tip'       => 'وچ مورت لگاؤ',
'media_tip'       => 'فائل دا جوڑ',
'sig_tip'         => 'تواڈے دستخط ویلے دے نال',
'hr_tip'          => 'سدھی لکیر',

# Edit pages
'summary'                          => 'خلاصہ:',
'subject'                          => 'موضوع/شہ صرحی:',
'minoredit'                        => 'اے نکا جیا کم اے',
'watchthis'                        => 'اس صفحے تے نظر رکھو',
'savearticle'                      => 'کم بچاؤ',
'preview'                          => 'وکھاؤ',
'showpreview'                      => 'کچا کم ویکھو',
'showlivepreview'                  => 'جیندا کچا کم',
'showdiff'                         => 'تبدیلیاں وکھاؤ',
'anoneditwarning'                  => "<div/>'''خبردار''' تسی اندر نہیں آۓ
تواڈا ''آئی پی'' پتہ فائل فائل وچ لکھیا جاۓ گا۔",
'missingcommenttext'               => 'تھلے اپنی گل لکھو۔',
'summary-preview'                  => 'کچے کم دا خلاصہ:',
'blockedtitle'                     => 'ورتن آلے نوں روکیا ہویا اے',
'blockedtext'                      => "'''تواڈا ورتن والا ناں یا فیر آئی پی ایڈریس روک دتا گیا اے۔'''

توانوں $1 نے روکیا اے۔<br />
ایدی وجہ ''$2'' اے۔

* رکوائی دی پہل:$8
* رکوائی دا انت:$6
* روکیا جان آلا:$7

تسی $1 نال مل ملسکدے او یا اک ہور [[{{MediaWiki:Grouppage-sysop}}|ایڈمنسٹریٹر]] نال روک دے بارے چ گل بات کر سکدے او۔<br />
تسی اس ورتن آلے نوں ای میل نئیں کر سکدے جدوں تک توانوں کوئی ای میل ایڈریس نا دتا جاۓ تے توانوں اس دے استعمال توں روکیا نا گیا ہوۓ۔
تواڈا موجودہ آئی پی پتہ $3 اے تے روکی گئی آئی ڈی #$5 اے۔
مہربانی کر کے کوئی وی سوال جواب کرن آسطے اتے دتیاں گئیاں تفصیلات ضرور دیو۔",
'blockednoreason'                  => 'کوئی وجہ نئیں دسی گئی',
'nosuchsectiontitle'               => 'اے ہو جیا کوئی ٹوٹا نئیں',
'loginreqtitle'                    => 'لاگ ان چائیدا اے',
'loginreqlink'                     => 'لاگ ان ہو جاو',
'accmailtitle'                     => 'کنجی پیج دتی گئی اے۔',
'newarticle'                       => '(نواں)',
'newarticletext'                   => 'تسی ایسے صفحے دے جوڑ توں ایتھے پہنچے او جیڑا ھلے تک نہیں بنیا۔<br />
اس صفحہ بنانے آسطے تھلے دتے گۓ ڈبے وچ لکھنا شروع کر دیو(زیادہ رہنمائی آستے اے ویکھو [[{{MediaWiki:Helppage}}|<br />مدد دا صفحہ]])۔
اگر تسی ایتھے غلطی نال پہنچے او تے اپنے کھوجی توں "بیک" دا بٹن دبا دیو۔',
'noarticletext'                    => 'اس ویلے اس صفحے تے کج نہیں لکھیا ہویا تسیں [[Special:Search/{{PAGENAME}}|اس صفحے دے ناں نوں دوجے صفحیاں تے کھوج سکدے او]] یا فیر [{{fullurl:{{FULLPAGENAME}}|action=edit}} اس صفحے نوں لکھ سکدے او۔]',
'updated'                          => '(نواں کیتا گیا)',
'previewnote'                      => "'''اے ہلے کچا کم اے؛ تبدیلیاں بچائیاں نہیں گئیاں'''",
'editing'                          => 'تسی "$1" لکھ رہے او',
'editingsection'                   => '$1 دا حصہ لکھ رہے او',
'yourtext'                         => 'تواڈی لکھائی',
'storedversion'                    => 'سانبیا ورژن',
'yourdiff'                         => 'تبدیلیاں',
'copyrightwarning'                 => "مہربانی کر کے اے گل یاد رکھ لو کے سارے کم {{SITENAME}} ایتھے $2 دے تھلے آن گے (زیادہ علم واسطے $1 تکو)۔<br />
اگر تسی نئیں چاندے کے تواڑی لکھائی نوں بے رحمی نال ٹھیک کیتا جاۓ تے نالے اپنی مرضی نال اونھوں چھاپیا جاۓ تے ایتدے مت لکھو۔<br />
تسی اے وی ساڈے نال وعدہ کر رہے او کہ اینوں تسی آپ لکھیا اے یا فیر کسی پبلک ڈومین توں یا ایہو جۓ کسے آزاد ذریعے توں نقل کیتا اے۔<br />
'''ایتھے او کم بغیر اجازت توں نا لکھو جیدے حق راکھویں نے '''",
'templatesused'                    => 'اس صفحے تے  ورتے گۓ {{PLURAL:$1|سانچے|سانچہ}}:',
'templatesusedpreview'             => 'اس کچے کم تے ورتے گئے {{PLURAL:$1|سانچے|سانچہ}} :',
'templatesusedsection'             => 'اس ٹوٹے چ استعمال کیتے گۓ سچے:',
'template-protected'               => '(بچایا گیا)',
'template-semiprotected'           => '(کج بچایا ہویا)',
'hiddencategories'                 => 'اے صفحہ {{PLURAL:$1|1 چھپی گٹھ|$1 چپھی گٹھیاں}} دا رکن اے:',
'nocreatetitle'                    => 'صفحہ بنانے دی حد اے',
'nocreatetext'                     => '{{SITENAME}} نے نۓ صفحے بنانے تے پابندی لائی اے۔<br />
تسی واپس جا کے پہلاں توں موجود صفحیاں تے لکھ سکدے او یا فیر [[Special:UserLogin|اندر آؤ یا نواں کھاتہ کھولو۔]]',
'nocreate-loggedin'                => 'توانوں نواں صفحہ بنانے دی اجازت نئیں۔',
'permissionserrors'                => 'توانوں اجازت چ کوئی مسئلا اے',
'permissionserrorstext-withaction' => 'تواڈے کول $2 کرن دی اجازت نئیں اے۔ اس دی {{PLURAL:$1|وجہ|وجوہات}} نیں۔',
'recreate-moveddeleted-warn'       => "'''خبردار: تسی اک پہلاں توں مٹایا ہویا صفحہ دوبارا لکھ رہے او۔'''

توانوں اے گل سوچنی چائیدی اے کہ اینو لکھنا کوئی عقلمنداں دا کم اے۔<div/>
تواڈی سہولت آسطے مٹان دا لاگ ایتھے موجود اے۔",
'moveddeleted-notice'              => 'اس صفحے نوں مٹا دتا گیا اے۔
مٹان دا لاگ تھلے دتا گیا اے۔',

# Account creation failure
'cantcreateaccounttitle' => 'کھاتہ نئیں کھول سکدے',

# History pages
'viewpagelogs'           => 'صفحے دے لاگ ویکھو',
'nohistory'              => 'اس صفحے دی پرانی لکھائی دی کوئی تاریخ نئیں۔',
'currentrev'             => 'ہن آلی تبدیلی',
'currentrev-asof'        => '$1 ویلے دا صفحہ',
'revisionasof'           => 'دی تبدیلیاں $1',
'revision-info'          => '$2 نے $1 تے اے لکھیا',
'previousrevision'       => '← اوس توں پچھلا کم',
'nextrevision'           => 'نویں تبدیلی →',
'currentrevisionlink'    => 'موجودہ حالت',
'cur'                    => 'موجودہ',
'next'                   => 'اگلا',
'last'                   => 'آخری',
'page_first'             => 'پہلا',
'page_last'              => 'آخری',
'histlegend'             => 'ڈف سلیکشن: وکھری تبدیلیاں دا مقابلا کرن واسطے ریڈیو ڈبیاں تے نشان لاؤ تے اینٹر یا تھلے دتا گیا بٹن دباؤ۔<br />
لیجنڈ: (موجودہ) = موجودہ تبدیلی نال مقابلہ،
(آخری) = پچھلی تبدیلی توں فرق، M = تھوڑی تبدیلی',
'history-fieldset-title' => 'ریکارڈ ویکھو',
'histfirst'              => 'سب توں پہلا',
'histlast'               => 'سب توں نواں',
'historyempty'           => '(خالی)',

# Revision feed
'history-feed-item-nocomment' => '$2 نوں $1',

# Revision deletion
'rev-deleted-comment'       => '(صلاع مٹ گئی)',
'rev-deleted-user'          => '(ورتن آلا ناں مٹ گیا)',
'rev-delundel'              => 'وکھاؤ/لکاؤ',
'revisiondelete'            => 'ریوژن مٹاؤ یا واپس کرو',
'revdelete-hide-text'       => 'ریوژن ٹیکسٹ لکاؤ',
'revdelete-hide-image'      => 'فائل دا مواد لکاؤ',
'revdelete-hide-name'       => 'کم تے نشانہ چھپاؤ',
'revdelete-hide-comment'    => 'لکھن دے بارے چ صلاع لکاؤ',
'revdelete-hide-user'       => 'لکھن آلے دا ناں/آئی پی پتہ لکاؤ',
'revdel-restore'            => 'وکھالا بدلو',
'pagehist'                  => 'صفحے دی تاریخ',
'deletedhist'               => 'مٹائی گئی تاریخ',
'revdelete-content'         => 'مواد',
'revdelete-summary'         => 'لکھائی دا خلاصہ',
'revdelete-uname'           => 'ورتن آلے دا ناں',
'revdelete-edit-reasonlist' => 'مٹانے دی وجہ لکھو',

# History merging
'mergehistory-from' => 'ذریعے آلا صفحہ:',
'mergehistory-into' => 'اصلی صفحہ:',

# Merge log
'revertmerge' => 'وکھریاں کرو',

# Diffs
'history-title'           => '"$1" دا ریکارڈ',
'difference'              => '(صفحیاں وچ فرق)',
'lineno'                  => 'لیک $1:',
'compareselectedversions' => 'چنے صفحے آپنے سامنے کرو',
'editundo'                => 'واپس',
'diff-multi'              => '({{PLURAL:$1|One intermediate revision|$1 درمیانی تبدیلی}} نئیں وکھائی گئی۔)',

# Search results
'searchresults'             => 'کھوج دا نتارا',
'searchresults-title'       => '"$1" دے کھوج نتارے',
'searchresulttext'          => 'وکیپیڈیا چ کھوجن دے بارے چ ہور معلومات آستے کھوجن دا صفحہ ویکھو',
'searchsubtitle'            => "تواڈی لفظ '''[[:$1]] آستے کھوج",
'searchsubtitleinvalid'     => "'''$1''' آستے کھوج کیتی",
'notitlematches'            => 'اے لفظ کسی صفحے دے ناں چ نئیں اے۔',
'notextmatches'             => 'کوئی صفح نئیں لبیا',
'prevn'                     => 'پہلا {{PLURAL:$1|$1}}',
'nextn'                     => 'اگلا {{PLURAL:$1|$1}}',
'viewprevnext'              => 'ویکھو ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url'            => 'Help:فہرست',
'search-result-size'        => '$1 ({{PLURAL:$2|1 لفظ|$2 الفاظ}})',
'search-redirect'           => '($1 ریڈائریکٹ)',
'search-section'            => '($1 ٹوٹا)',
'search-suggest'            => 'تسی $1 دی گل تے نئیں کر رۓ:',
'search-interwiki-caption'  => 'نال دے منصوبے',
'search-interwiki-default'  => '$1 نتارے:',
'search-interwiki-more'     => '(اور)',
'search-mwsuggest-enabled'  => 'صلاع دے نال',
'search-mwsuggest-disabled' => 'کوئی صلاع نئیں',
'search-relatedarticle'     => 'جڑیاں',
'searchrelated'             => 'جڑیا',
'searchall'                 => 'سارے',
'nonefound'                 => "'''صفحیاں دے ناں ڈیفالٹ تے کھوجے جاندے نیں'''
اپنے لفظ توں پہلاں ''all:'' لا کے کھوجو۔ اس نال گلاں باتاں آلے صفحے، سچے وغیرہ سب چ تواڈا لفظ کھوجیا جاۓ گل۔",
'powersearch'               => 'ودیا کھوج',
'powersearch-legend'        => 'ہور کھوج',
'powersearch-ns'            => 'ناں الیاں جگہاں چ لبو:',
'powersearch-redir'         => 'ریڈائریکٹس دی لسٹ وکھاؤ',
'powersearch-field'         => 'لئی کھوج',
'search-external'           => 'باہر دی کھوج',

# Quickbar
'qbsettings-none' => 'کوئی نئیں',

# Preferences page
'preferences'               => 'تانگاں',
'mypreferences'             => 'میریاں تانگاں',
'prefs-edits'               => 'تبدیلیاں دی گنتی:',
'prefsnologin'              => 'لاگ ان نئیں او',
'changepassword'            => 'کنجی بدلو',
'prefs-skin'                => 'کھل',
'prefs-math'                => 'حساب کتاب',
'prefs-datetime'            => 'تاریخ تے ویلہ',
'prefs-personal'            => 'ورتن آلے دا پروفائل',
'prefs-rc'                  => 'نویاں تبدیلیاں',
'prefs-watchlist'           => 'نظر تھلے صفحے',
'saveprefs'                 => 'بچاؤ',
'prefs-editing'             => 'لکھائی',
'rows'                      => 'قطار:',
'columns'                   => 'کالم:',
'searchresultshead'         => 'کھوج',
'timezonelegend'            => 'ویلے دا علاقہ',
'localtime'                 => 'مقامی ویلا:',
'prefs-files'               => 'فائلاں',
'youremail'                 => 'ای میل:',
'username'                  => 'ورتن آلے دا ناں:',
'yourrealname'              => 'اصلی ناں:',
'yourlanguage'              => 'بولی:',
'yournick'                  => 'دسخط:',
'email'                     => 'ای میل',
'prefs-help-realname'       => 'اصل ناں تواڈی مرزی تے اے۔<br />
اگر تسی اینو دے دیو گۓ تے اے تواڈا کم اس ناں نال لکھیا جاۓ گا۔',
'prefs-help-email-required' => 'ای میل پتہ چائیدا اے۔',

# User rights
'userrights-groupsmember' => 'سنگی اے:',
'userrights-reason'       => 'وجہ:',

# Groups
'group'            => 'ٹولی:',
'group-user'       => 'ورتن آلے',
'group-bot'        => 'بوٹ',
'group-sysop'      => 'مکھیۓ',
'group-bureaucrat' => 'بیوروکریٹ',
'group-all'        => '(سارے)',

'group-user-member'       => 'ورتن آلا',
'group-bureaucrat-member' => 'بیوروکریٹ',

'grouppage-sysop' => '{{ns:project}}:ایڈمنسٹریٹر',

# Rights
'right-read'          => 'صفحے پڑھو',
'right-edit'          => 'صفحے لکھو',
'right-createpage'    => 'صفحے بناؤ (جیڑے کے گلاں باتاں آلے نئیں نیں)۔',
'right-createtalk'    => 'گلاں باتاں آلے صفحے بناؤ',
'right-createaccount' => 'ورتن آلیاں دے نوے اکاونٹ کھولو',
'right-minoredit'     => 'لکھائی نوں چھوٹیاں موٹیاں قرار دے دیو',
'right-move'          => 'صفحے لے چلو',
'right-upload'        => 'فائل چڑہاؤ',
'right-reupload'      => 'پہلاں دی لکھی ہوئی فائل دے اتے لکھو',
'right-delete'        => 'صفحے مٹاؤ',
'right-bigdelete'     => 'لمبیاں تاریخاں آلے صفحے مٹاؤ',
'right-browsearchive' => 'مٹاۓ ہوۓ صفحے کھوجو',
'right-undelete'      => 'مٹایا صفحہ واپس لیاو',
'right-blockemail'    => 'ورتن آلے نوں ای میل پیجن توں روکو',
'right-hideuser'      => 'لوکاں توں چھپاندے ہویاں اک ورتن آلے نوں روکو',
'right-userrights'    => 'تمام ورتن آلیاں دے حق رلکھو',

# User rights log
'rightslog'  => 'ورتن والے دے حقاں دی لاگ',
'rightsnone' => '(کوئی وی نئیں)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'          => 'اس صفحے نوں پڑھو',
'action-edit'          => 'اس صفحے تے لکھو',
'action-createpage'    => 'صفحے بناؤ',
'action-createtalk'    => 'گلاں باتاں آلا صفحہ بناؤ',
'action-move'          => 'اس صفحے نوں لے جاؤ',
'action-move-subpages' => 'اس صفحے نوں تے ایدے نال دے جڑے صفحیاں نوں لے چلو',
'action-upload'        => 'اس فائل نوں اتے چاڑو',
'action-reupload'      => 'اس پہلاں توں موجود فائل دے اتے لکھو',
'action-delete'        => 'اس صفحے نوں مٹا دیو',
'action-browsearchive' => 'مٹاۓ گۓ صفحے کھوجو',
'action-undelete'      => 'اس صفحے نوں واپس لیاؤ',
'action-block'         => 'اس ورتن آلے نوں لکھن توں روکو',
'action-protect'       => 'اس صفحے دے بچاؤ دا درجہ بدلو',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|change|تبدیلیاں}}',
'recentchanges'                  => 'نویاں تبدیلیاں',
'recentchanges-legend'           => 'نویاں تبدیلیاں دیاں راواں',
'recentchanges-feed-description' => 'اس فیڈ وچ وکی تے ہوئیاں نویاں تبدیلیاں نو ویکھو۔',
'rcnote'                         => "تھلے $5،$4 تک {{PLURAL:$2|آخری '''$2''' دناں دی }} {{PLURAL:$1|'''$1''' تبدیلیاں نیں}}۔",
'rcnotefrom'                     => "ہلے تک '''$2''' توں '''$1''' تبدیلیاں تھلے دتیاں گئیاں نیں۔",
'rclistfrom'                     => '$1 توں ہونے آلیاں نویاں تبدیلیاں وکھاؤ',
'rcshowhideminor'                => '$1 معمولی تبدیلیاں',
'rcshowhidebots'                 => '$1 بوٹ',
'rcshowhideliu'                  => '$1 ورتن آلے اندر نیں',
'rcshowhideanons'                => '$1 گمنام ورتن والے',
'rcshowhidepatr'                 => '$1 ویکھی گئی لکھائی',
'rcshowhidemine'                 => '$1 میرے کم',
'rclinks'                        => 'آخری $2 دناں دیاں $1 تبدیلیاں وکھاؤ<br />$3',
'diff'                           => 'فرق',
'hist'                           => 'پچھلا کم',
'hide'                           => 'چھپاؤ',
'show'                           => 'وکھاؤ',
'minoreditletter'                => 'چھوٹا کم',
'newpageletter'                  => 'نواں',
'boteditletter'                  => 'بوٹ',
'rc_categories_any'              => 'کوئی',
'rc-enhanced-expand'             => 'لمبی کہانی وکھاؤ (جاوا سکرپٹ چائیدا اے)',
'rc-enhanced-hide'               => 'لمبی کہانی لکاؤ',

# Recent changes linked
'recentchangeslinked'          => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-feed'     => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-toolbox'  => 'ملدیاں جلدیاں تبدیلیاں',
'recentchangeslinked-title'    => '"$1" نال تعلق آلیاں تبدیلیاں',
'recentchangeslinked-noresult' => 'جڑیاں صفحیاں چ دتے ہوۓ ویلے چ کوئی تبدیلیاں نہیں۔',
'recentchangeslinked-summary'  => "اے اوناں تبدیلیاں دی لسٹ اے جیڑیاں تھوڑا چر پہلاں بنائیاں گئیاں اوناں صفحیاں تے جیڑے خاص صفحے تے جڑدے نے یا کسی خاص کیٹاگری دے ممبراں نوں۔<br />
تواڈی [[Special:Watchlist|اکھ تھلے صفحے]] '''موٹے''' نیں۔",
'recentchangeslinked-page'     => 'صفحے دا ناں:',
'recentchangeslinked-to'       => 'کھلے ہوۓ صفحے دی بجاۓ ایدے نال جڑے صفحے دیاں نویاں تبدیلیاں وکھاؤ',

# Upload
'upload'              => 'فائل چڑھاؤ',
'uploadbtn'           => 'فائل چڑھاؤ',
'reuploaddesc'        => 'فائل چڑانا چھڑو تے فائل چڑانے آلے فارم تے واپس ٹرو',
'uploadnologin'       => 'لاگ ان نئیں ہوۓ',
'uploaderror'         => 'فائل چڑاندیاں مسئلا ہویا اے',
'uploadlogpage'       => 'اپلوڈ لاگ',
'filename'            => 'فائل دا ناں',
'filedesc'            => 'خلاصہ',
'fileuploadsummary'   => 'خلاصہ:',
'filesource'          => 'ذریعہ:',
'uploadedfiles'       => 'اتے چڑھائیاں گئیاں فائلاں',
'uploadwarning'       => 'فائل چڑانے توں خبردار',
'savefile'            => 'فائل بچاؤ',
'uploadedimage'       => 'چڑھائی گئی"[[$1]]"',
'uploaddisabled'      => 'فائل چڑانا بند اے',
'uploaddisabledtext'  => 'فائل چڑانے چ رکاوٹ اے۔',
'uploadvirus'         => 'اس فائل چ وائرس اے! تفصیل: $1',
'sourcefilename'      => 'فائل دے ذریعے دا ناں:',
'destfilename'        => 'وکی دے اتے فائل دا ناں:',
'upload-maxfilesize'  => 'فائل دا زيادہ توں زيادہ ناپ: $1',
'watchthisupload'     => 'اس صفحے تے نظر رکھو',
'upload-success-subj' => 'فائل چڑھ گئی اے',

'upload-proto-error' => 'غلط پروٹوکول',
'upload-file-error'  => 'اندر دا مسئلا',
'upload-misc-error'  => 'اتے چڑاندیاں انجان مسئلا اے',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error28' => 'فائل اتے چڑانے دا ویلا مک گیا اے',

'nolicense'          => 'انچنی',
'license-nopreview'  => '(کچا کم نئیں ویکھ سکدے او)',
'upload_source_file' => ' (تواڈے کمپیوٹر تے اک فائل)',

# Special:ListFiles
'imgfile'               => 'فائل',
'listfiles'             => 'فائل لسٹ',
'listfiles_date'        => 'تاریخ',
'listfiles_name'        => 'ناں',
'listfiles_user'        => 'ورتن آلا',
'listfiles_size'        => 'ناپ',
'listfiles_description' => 'تفصیل',

# File description page
'file-anchor-link'          => 'فائل',
'filehist'                  => 'پچھلی حالت',
'filehist-help'             => 'فائل نو اس ویلے دی حالت وچ ویکھن واسطے تاریخ/ویلے تے کلک کرو۔',
'filehist-deleteall'        => 'سب نوں مٹاؤ',
'filehist-deleteone'        => 'مٹاؤ',
'filehist-revert'           => 'واپس',
'filehist-current'          => 'موجودہ',
'filehist-datetime'         => 'تاریخ/ویلہ',
'filehist-thumb'            => 'نکی مورت',
'filehist-thumbtext'        => '$1 ورثن دی نکی مورت',
'filehist-user'             => 'ورتن والا',
'filehist-dimensions'       => 'پاسے',
'filehist-filesize'         => 'فائل دا ناپ',
'filehist-comment'          => 'راۓ',
'imagelinks'                => 'کتھے کتھے جوڑ اے',
'linkstoimage'              => 'تھلے دتے گۓ {{PLURAL:$1|$1 صفحے}} اس فائل نال جڑدے نے',
'nolinkstoimage'            => 'اس فائل نال جڑیا کوئی صفحہ نہیں۔',
'sharedupload'              => 'اے فائل $1 مشترکہ اپلوڈ اے تے اے دوجے منصوبے وی استعمال کر سکدے نے۔',
'uploadnewversion-linktext' => 'اس فائل دا نوا ورژن چھڑھاؤ',

# File reversion
'filerevert-legend'  => 'فائل پچھلی حالت چ لے جاؤ',
'filerevert-comment' => 'صلاع:',
'filerevert-submit'  => 'واپس',

# File deletion
'filedelete'                  => '$1 مٹاؤ',
'filedelete-legend'           => 'فائل مٹاؤ',
'filedelete-intro'            => "تسی '''[[Media:$1|$1]]''' مٹا رۓ او۔",
'filedelete-comment'          => 'وجہ:',
'filedelete-submit'           => 'مٹاؤ',
'filedelete-success'          => "'''$1''' مٹایا جا چکیا اے۔",
'filedelete-otherreason'      => ':دوجی وجہ',
'filedelete-reason-otherlist' => 'ہور وجہ',
'filedelete-edit-reasonlist'  => 'مٹانے دی وجہ لکھو',

# MIME search
'mimesearch' => 'MIME کھوج',
'download'   => 'فائل کاپی کرو',

# Unwatched pages
'unwatchedpages' => 'اندیکھے صفحے',

# List redirects
'listredirects' => 'لسٹ ریڈائریکٹس',

# Unused templates
'unusedtemplates'    => 'نا استعمال ہوۓ سچے',
'unusedtemplateswlh' => 'دوجے جوڑ',

# Random page
'randompage' => 'ملے جلے صفحے',

# Random redirect
'randomredirect' => 'بے پترتیب ریڈائریکٹ',

# Statistics
'statistics'             => 'حساب کتاب',
'statistics-mostpopular' => 'سب توں بوتے ویکھے گۓ صفجے',

'disambiguations' => 'اک جۓ صفحے',

'doubleredirects' => 'دوہری ریڈیرکٹس',

'brokenredirects'        => 'ٹٹے ہوۓ ریڈائریکٹس',
'brokenredirects-edit'   => 'لکھو',
'brokenredirects-delete' => 'مٹاؤ',

'withoutinterwiki'        => 'او صفحہ جناں دا دوجی بولیاں نال جوڑ نہیں',
'withoutinterwiki-submit' => 'وکھاو',

'fewestrevisions' => 'سب توں کٹ تبدیلیاں والے صفحے',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|بائیٹ|بائیٹ}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|جوڑ}}',
'nmembers'                => '$1 {{PLURAL:$1|member|ممبران}}',
'lonelypages'             => 'لاوارث صفحے',
'uncategorizedpages'      => 'بغیر کیٹاگریاں والے صفحے',
'uncategorizedcategories' => 'بفیر کیٹاگریاں دے کیٹاگری',
'uncategorizedimages'     => 'بغیر کیٹاگریاں آلیاں فائلاں',
'uncategorizedtemplates'  => 'بغیر کیٹاگریاں آلے سچے',
'unusedcategories'        => 'نا استعمال ہوئیاں کیٹاگریاں',
'unusedimages'            => 'نا استعمال ہوئیاں فائلاں',
'popularpages'            => 'مشہور صفحے',
'wantedcategories'        => 'چاھیدیاں کیٹاگریاں',
'wantedpages'             => 'چائیدے صفحے',
'mostlinked'              => 'سب توں بوتے جوڑاں آۂے صفحے',
'mostlinkedcategories'    => 'سب توں بوتیاں جڑیاں کیٹاگریاں',
'mostlinkedtemplates'     => 'سب توں زیادہ جوڑ والے سچے',
'mostcategories'          => 'سب توں بوتیاں کیٹاگریاں آلے صفحے',
'mostimages'              => 'سب توں زیادہ تعلق آلیاں فائلاں',
'mostrevisions'           => 'سب توں بوتے تبدیلیاں آلے صفحے',
'prefixindex'             => 'سابقہ انڈیکس',
'shortpages'              => 'چھوٹے صفحے',
'longpages'               => 'لمبے صفحے',
'deadendpages'            => 'لاتعلق صفحے',
'protectedpages'          => 'بچاۓ گۓ صفحے',
'protectedtitles'         => 'بچاۓ ہوۓ صفحے',
'listusers'               => 'ورتن والیاں دے ناں',
'newpages'                => 'نوے صفحے',
'newpages-username'       => 'ورتن آلا ناں:',
'ancientpages'            => 'سب توں پرانے صفحے',
'move'                    => 'لے چلو',
'movethispage'            => 'اس صفحے نوں لے چلو',
'notargettitle'           => 'بغیر نشانے توں',
'nopagetitle'             => 'اس طرح دا کوئی صفحہ نئیں اے',
'pager-newer-n'           => '{{PLURAL:$1|newer 1|زیادہ نواں $1}}',
'pager-older-n'           => '{{PLURAL:$1|older 1|زیادہ پرانا $1}}',

# Book sources
'booksources'               => 'حوالہ کتاب',
'booksources-search-legend' => 'اس مضمون تے کتاباں لبو',
'booksources-go'            => 'جاؤ',

# Special:Log
'specialloguserlabel'  => 'ورتن والا:',
'speciallogtitlelabel' => 'ناں:',
'log'                  => 'لاگز',
'all-logs-page'        => 'سارے لاگ',

# Special:AllPages
'allpages'       => 'سارے صفحے',
'alphaindexline' => '$1 توں $2',
'nextpage'       => 'اگلا صفحہ ($1)',
'prevpage'       => 'پچھلا صفحہ ($1)',
'allpagesfrom'   => 'اس جگہ توں شروع ہونے آلے صفحے وکھاؤ:',
'allpagesto'     => 'اس تے ختم ہون آلے صفحے وکھاؤ:',
'allarticles'    => 'سارے صفحے',
'allpagesprev'   => 'پچھلا',
'allpagesnext'   => 'اگلا',
'allpagessubmit' => 'چلو',
'allpagesprefix' => 'پریفکس نال صفحے وکھاؤ:',

# Special:Categories
'categories' => 'کیٹاگریاں',

# Special:LinkSearch
'linksearch' => 'باہر دے جوڑ',

# Special:ListUsers
'listusers-submit'   => 'وکھاؤ',
'listusers-noresult' => 'ورتن آلا نئیں لبیا۔',

# Special:Log/newusers
'newuserlogpage'          => 'ورتاوا بنان آلی لاگ',
'newuserlog-create-entry' => 'نوا ورتن آلا',

# Special:ListGroupRights
'listgrouprights-group'   => 'ٹولی',
'listgrouprights-rights'  => 'حق',
'listgrouprights-members' => '(رکناں دی لسٹ)',

# E-mail user
'emailuser'    => 'اس ورتن والے نو ای میل کرو',
'emailfrom'    => 'توں:',
'emailto'      => 'نوں:',
'emailsubject' => 'مضمون:',
'emailmessage' => 'سنیعا:',
'emailsend'    => 'پیجو',

# Watchlist
'watchlist'         => 'میریاں اکھاں تھلے وچ',
'mywatchlist'       => 'میری نظر وچ',
'addedwatch'        => 'اکھ تھلے آگیا',
'addedwatchtext'    => 'اے صفحہ "[[:$1]] تواڈیاں اکھاں تھلے آگیا اے۔<br />
مستقبل وچ اس صفحہ تے ایدے بارے چ گل بات نویاں تبدیلیاں وچ موٹے نظر آن گے تا کہ آسانی نال کھوجیا جا سکے۔',
'removedwatch'      => 'اکھ تھلیوں ہٹا لیا گیا',
'removedwatchtext'  => 'ایہ صفحہ "[[:$1]]" [[Special:Watchlist|تہاڈی اکھ ]]تھلوں ہٹا لیتا گیا اے۔',
'watch'             => 'نظر رکھو',
'watchthispage'     => 'اس صفحے تے اکھ رکھو',
'unwatch'           => 'نظر ھٹاؤ',
'unwatchthispage'   => 'اکھ رکھنا چھڈو',
'watchlist-details' => '{{PLURAL:$1|$1 صفحہ|$1 صفحہ}} تواڈی اکھ تھلے گلاں باتاں شامل نہیں۔',
'wlshowlast'        => 'آخری $1 گھنٹے $2 دن $3 وکھاؤ',
'watchlist-options' => 'نظر تھلے رکھن دیاں راہواں',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'نظر تھلے۔۔۔۔',
'unwatching' => 'نظروں اولے',

'enotif_reset'       => 'سارے ویکھے گۓ صفحیاں تے نشان لاؤ',
'enotif_newpagetext' => 'اے نواں صفحہ اے۔',
'changed'            => 'بدلیا',
'created'            => 'بن گیا',
'enotif_anon_editor' => 'گم نام ورتن آلا $1',

# Delete
'deletepage'             => 'صفحہ مٹاؤ',
'confirm'                => 'پکا کرو',
'exblank'                => 'صفحہ خالی سی',
'delete-confirm'         => '"$1" مٹاؤ',
'delete-legend'          => 'مٹاؤ',
'historywarning'         => 'خوشیار: او صفحہ جس نوں تسی مٹانے لگے او دا ریکارڈ موجود اے۔',
'confirmdeletetext'      => 'تسی اک صفحہ اسدی تاریخ دے نال مٹان لگے او۔
کیا تسی اے ای کرنا چاہندے او کیا تسی اس دے نتیجے نوں جاندے او کہ تسی اے کم [[{{MediaWiki:Policy-url}}|پالیسی]] دے مطابق کر رہے او۔',
'actioncomplete'         => 'کم ہوگیا',
'deletedtext'            => '"<nowiki>$1</nowiki>" مٹایا جا چکیا اے۔<br />
نیڑے نیڑے مٹاۓ گۓ ریکارڈ نوں دیکن آسطے $2 ایتھے چلو۔',
'deletedarticle'         => '"[[$1]]" مٹا دتا گیا',
'dellogpage'             => 'مٹان آلی لاگ',
'dellogpagetext'         => 'تھلے نویاں مٹائے گۓ صفحیاں دی لسٹ اے۔',
'deletecomment'          => 'وجہ:',
'deleteotherreason'      => 'دوجی/ہور وجہ:',
'deletereasonotherlist'  => 'ہور وجہ',
'delete-edit-reasonlist' => 'مٹانے دیاں وجہ لکھو',

# Rollback
'rollback'       => 'لکھائیاں واپس کرو',
'rollback_short' => 'واپس کرو',
'rollbacklink'   => 'واپس',

# Protect
'protectlogpage'              => 'بچت لاگ',
'protectedarticle'            => '"[[$1]]" بچایا گیا اے',
'modifiedarticleprotection'   => '"[[$1]]" آستے بچاؤ بدلیا',
'unprotectedarticle'          => '"[[$1]]" نئیں بچایا گیا',
'protect-legend'              => 'بچاؤ پکا کرو',
'protectcomment'              => 'وجہ:',
'protectexpiry'               => 'انت ہوندا اے:',
'protect_expiry_invalid'      => 'اکسپائری ٹیم غلط اے۔',
'protect_expiry_old'          => 'ایدا اکسپائری ٹائم گزر چکیا اے۔',
'protect-text'                => "تسی اس صفحے دے حفاظتی درجے نوں تک تے تبدیل کر سکدے او'''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "تواڈا کھاتہ اجازت نہیں دیندا کہ تسی صفحے دے حفاظتی درجے نوں تبدیل کرو۔<br />
ایتھے صفحے آسطے موجودہ ترتیب نے '''$1''':",
'protect-cascadeon'           => 'اے صفحہ ایس ویلے بچایا گیا کیوجہ اے اونھاں {{PLURAL:$1|page, which has|صفحیاں وچ شامل اے }} جیناں دی کسکیڈنگ حفاظت آن اے۔

تسی اس صفحے دا بچاؤ لیول نوں تبدیل کرسکدے او لیکن اے اودھے کسکیڈنگ بچاؤ تے اثر نئیں کریگی۔',
'protect-default'             => 'ساریاں نوں جان دیو',
'protect-fallback'            => '"$1" دی اجازت دی لوڑ اے',
'protect-level-autoconfirmed' => 'غیر تسلیم شدہ ورتن والے نوں روکو',
'protect-level-sysop'         => 'صرف سائسوپس',
'protect-summary-cascade'     => 'کسکیڈنگ',
'protect-expiring'            => 'ختم ہوندا اے $1 (UTC)',
'protect-cascade'             => 'اس صفحے وچ شامل صفحیاں نوں بچاؤ (کسکیڈنگ ح‌فاظت)۔',
'protect-cantedit'            => 'تسی اس صفحے دے حفاظتی درجے نوں نہیں بدل سکدے کیونکہ توانوں اس کم دی اجازت نہیں اے۔',
'restriction-type'            => 'اجازت:',
'restriction-level'           => 'حفاظتی درجہ:',
'minimum-size'                => 'چھوٹا ترین ناپ',
'maximum-size'                => 'وڈآ ترین ناپ:',
'pagesize'                    => '(بائٹ)',

# Restrictions (nouns)
'restriction-edit'   => 'لکھو',
'restriction-move'   => 'لے چلو',
'restriction-create' => 'بناؤ',
'restriction-upload' => 'اتے چاڑو',

# Restriction levels
'restriction-level-sysop'         => 'پوری طرح بچایا ہویا',
'restriction-level-autoconfirmed' => 'کج بچایا گیا',
'restriction-level-all'           => 'کسے وی درجے تے',

# Undelete
'undelete'                  => 'مٹاۓ گۓ صفحے ویکھو',
'undeletepage'              => 'مٹاۓ گۓ صفحے ویکھو تے واپس لے آؤ',
'viewdeletedpage'           => 'مٹاۓ گۓ صفحے ویکھو',
'undeletebtn'               => 'بحال کرو',
'undeletelink'              => 'ویکھو/بحال کرو',
'undeletereset'             => 'پہلی حالت تے لے آؤ',
'undeletecomment'           => 'صلاع:',
'undeletedarticle'          => '"[[$1]]" بحال کر دتا گیا اے',
'undelete-search-box'       => 'مٹاۓ گۓ صفحے کھوجو',
'undelete-search-submit'    => 'کھوجو',
'undelete-show-file-submit' => 'ہاں جی',

# Namespace form on various pages
'namespace'      => 'ناں دی جگہ:',
'invert'         => 'وچوں چناؤ',
'blanknamespace' => '(خاص)',

# Contributions
'contributions'       => 'ورتن آلے دا حصہ',
'contributions-title' => '$1 دے کم',
'mycontris'           => 'میرا کم',
'contribsub2'         => '$1 آستے ($2)',
'uctop'               => '(اتے)',
'month'               => 'مہینے توں (تے پہلاں):',
'year'                => 'سال توں (تے پہلاں):',

'sp-contributions-newbies'     => 'صرف نویں ورتن والیاں دے کم وکھاؤ',
'sp-contributions-newbies-sub' => 'نویں کھاتیاں آستے',
'sp-contributions-blocklog'    => 'لاگ روکو',
'sp-contributions-talk'        => 'گل بات',
'sp-contributions-search'      => 'حصے پان آلیاں دی تلاش',
'sp-contributions-username'    => 'آئی پی پتہ یا ورتن آلا ناں:',
'sp-contributions-submit'      => 'کھوجو',

# What links here
'whatlinkshere'            => 'ایتھے کیدا تعلق اے',
'whatlinkshere-title'      => 'او صفحات جیڑے "$1" نال جڑے نے',
'whatlinkshere-page'       => 'صفحہ:',
'linkshere'                => "تھلے دتے گۓ صفحے اس دے نال جڑدے نے '''[[:$1]]''':",
'nolinkshere'              => "'''[[:$1]]''' دے نال کسے دا جوڑ نہیں",
'isredirect'               => 'ریڈائرکٹ صفحہ',
'istemplate'               => 'ملن',
'isimage'                  => 'مورت دا جوڑ',
'whatlinkshere-prev'       => '{{PLURAL:$1|پچھل $1ا|پچھلا}}',
'whatlinkshere-next'       => '{{PLURAL:$1|اگلا $1|اگلا}}',
'whatlinkshere-links'      => '← تعلق',
'whatlinkshere-hideredirs' => '$1 ریڈائریکٹس',
'whatlinkshere-hidetrans'  => '$1 ٹرانسکلوژن',
'whatlinkshere-hidelinks'  => '$1 جوڑ',
'whatlinkshere-filters'    => 'نتارے',

# Block/unblock
'blockip'                  => 'اس ورتن والے نو روکو',
'blockip-legend'           => 'ورتن آلے نوں روکو',
'ipaddress'                => 'آئی پی پتہ:',
'ipadressorusername'       => 'آئی پی پتہ یا ورتن آلے دا ناں:',
'ipbexpiry'                => 'انت:',
'ipbreason'                => 'وجہ:',
'ipbreasonotherlist'       => 'ہور وجہ',
'ipbanononly'              => 'انجان ورتن آلیاں نوں روکو',
'ipbcreateaccount'         => 'کھاتہ کھولنا روکو',
'ipbemailban'              => 'ورتن آلے نوں ای میل پیجن توں روکو',
'ipbsubmit'                => 'اس ورتن آلے نوں روکو',
'ipbother'                 => 'دوجے ویلے:',
'ipboptions'               => 'دو کینٹے:2 hours,1 دن:1 day,3 دن:3 days,1 ہفتہ:1 week,2 ہفتے:2 weeks,1 مہینہ:1 month,3 مہینے:3 months,6 مہینے:6 months,1 سال:1 year,بے انت:infinite',
'ipbotheroption'           => 'دوجا',
'ipbotherreason'           => 'دوجیاں ہور وجہ:',
'badipaddress'             => 'آئی پی پتہ ٹھیک نئیں',
'blockipsuccesssub'        => 'روک کامیاب',
'ipb-blocklist'            => 'روکیاں گياں نوں ویکھو',
'unblockip'                => 'ورتن آلے تے روک بند کرو',
'ipblocklist'              => 'بند کیتے گۓ آئی پی پتے تے ورتن والیاں دے ناں',
'ipblocklist-username'     => 'ورتن آلے دا ناں یا آئی پی پتہ:',
'ipblocklist-submit'       => 'کھوجو',
'infiniteblock'            => 'بے انت',
'emailblock'               => 'ای میل روک دتی گئی اے',
'blocklink'                => 'روک',
'unblocklink'              => 'روک ختم',
'change-blocklink'         => 'روک نوں بدلو',
'contribslink'             => 'حصے داری',
'blocklogpage'             => 'لاگ روکو',
'blocklogentry'            => 'روک دتا گیا تے اے رکاوٹ دا ویلا $2 $3 مک جاۓ گا [[$1]]',
'unblocklogentry'          => '$1 توں روک ہٹا لئی گئی اے',
'block-log-flags-nocreate' => 'کھاتا کھولنے تے پابندی اے',
'block-log-flags-noemail'  => 'ای میل روکی گئی اے',
'ipb_already_blocked'      => '"$1" پہلاں توں ہی روکیا ہویا اے۔',
'blockme'                  => 'مینوں روکو',
'proxyblocker-disabled'    => 'اس کم نوں روک دتا گیا اے۔',
'proxyblocksuccess'        => 'ہوگیا۔',

# Developer tools
'lockdb'              => 'ڈیٹابیس تے تالا لاؤ',
'unlockdb'            => 'ڈیٹابیس دا تالا کھولو',
'lockconfirm'         => 'ہاں، میں ڈیٹابیس تے تالا لانا چاندا واں۔',
'unlockconfirm'       => 'ہاں، میں سچی ڈیٹابیس دا تالا کھولنا چاندا واں۔',
'lockbtn'             => 'ڈیٹابیس تے تالا لاؤ',
'unlockbtn'           => 'ڈیٹابیس دا تالا کھولو',
'lockdbsuccesssub'    => 'ڈیٹابیس تے تالا لگ گیا',
'unlockdbsuccesssub'  => 'ڈیٹابیس دا تالا کھل گیا',
'unlockdbsuccesstext' => 'ڈیٹابیس دا تالا کھل گیا اے۔',
'databasenotlocked'   => 'ڈیٹابیس تے تالا نئیں لگیا۔',

# Move page
'move-page'               => '$1 لے چلو',
'move-page-legend'        => 'صفحے لے چلو',
'movepagetext'            => "تھلے دتے گۓ فـارم نوں استعمال کرکے اس صفحہ دا ناں دوبارہ رکھیا جا سکدا اے، نال ہی اس نال جڑے تاریخچہ وی نۓ ناں نال جڑ جاۓ گی۔ اسدے بعد توں اس صفحے دا پرانا ناں ، نۓ ناں دی جانب -- ریڈائریکٹ کیتے گۓ صفحہ -- بن جاۓ گا۔ لیکن اے یاد رکھو کہ دوجے صفحیاں تے، پرانے صفحہ دی جانب دتے گۓ جوڑ تبدیل نئیں ہونگے؛ اس بات نوں یقینی بنانا ضروری اے کہ کوئی دوہرہ -- پلٹایا گیا جوڑ -- نہ رہ جاۓ۔

لہذا اے یقینی بنانا تواڈی ذمہ داری اے کہ سارے جوڑ ٹھیک صفحیاں دی جانب رہنمائی کردے رین۔

اے گل وی ذہن نشین کرلو کہ اگر نۓ منتخب کردہ ناں دا صفحہ پہلاں توں ہی موجود ہو تو ہوسکدا اے کہ صفحہ منتقل نہ ہوۓ ؛ ہاں اگر پہلے توں موجود صفحہ خالی اے  یا اوہ صرف اک -- ریڈائیرکٹ کیتا گیا صفحہ -- ہوۓ تے اس دے نال کوئی تاریخچہ جڑیا نہ ہووے تے ناں بدلیا جاۓ گا۔ گویا، کسی غلطی دی صورت وچ تسی صفحہ نوں دوبارہ اسی پرانے ناں دی جانب منتقل کرسکدے اوہ تے اس طرح پہلے توں موجود کسی صفحہ وچ کوئی مٹانا یا غلطی نئیں ہوۓ گی۔

''' خبردار '''
 کسی اہم تے مشہور صفحہ دے ناں دی تبدیلی، اچانک تے پریشانی آلی گل وی ہوسکدی اے اس لئی؛ تبدیلی توں پہلاں مہربانی کر کے یقین کرلو کہ تسی اسدے نتائج جاندے او۔",
'movepagetalktext'        => "ایس نال جڑیا ہویا گلاں باتاں آلا صفحہ خودبخود ہی ایدھے نال ٹر جاۓ گا
'''اگر نئیں تے'''
*اک لکھیا گیا گلاں باتاں والا صفحہ نۓ ناں توں پہلاں توں ہی موجود اے۔
*تسی تھلے دتے گۓ ڈبے نوں مٹا دیو۔

ایوجیاں مسئلیاں چ توانوں دوویں صفحیاں نوں آپے ہی ملانے ہوۓ گا اگر تسی چاندے او۔",
'movearticle'             => 'صفحہ لے چلو:',
'movenotallowed'          => 'تواڈے کول صفحے لے چلن دی اجازت نئیں اے۔',
'newtitle'                => 'نوے عنوان ول:',
'move-watch'              => 'صفحے اکھ تھلے رکھو',
'movepagebtn'             => 'صفحہ لے جاؤ',
'pagemovedsub'            => 'لے جانا کامیاب ریا',
'movepage-moved'          => '\'\'\'"$1" نوں "$2" لے جایا گیا اے\'\'\'',
'articleexists'           => 'اس ناں دا صفحہ یا تے پہلاں توں ہی موجود اے یا فیر جیڑا ناں تسی چنیا اے درست نہیں۔<br />
کوئی دوجا ناں چنو۔',
'talkexists'              => "'''اے صفحہ کامیابی دے نال ے جایا گیا مگر ایدا گلاں باتاں آلا صفحہ رنہیں لے جایا جا سکدا کیونکہ اک نیا اسی ناں نال موجود اے۔ ایناں نوں ہتھ نال ملا دیو۔'''",
'movedto'                 => 'لے جایا گیا',
'movetalk'                => 'تبدیلی نال جڑیاں گلاں باتاں والا صفحہ',
'1movedto2'               => '[[$1]] نوں لیجایا گیا [[$2]] تک',
'1movedto2_redir'         => '[[$1]] نوں [[$2]] ریڈائریکٹ کر دتا گیا اے',
'movelogpage'             => 'ناں تبدیل کرن دا لاگ',
'movereason'              => 'وجہ:',
'revertmove'              => 'واپس',
'delete_and_move'         => 'مٹاؤ تے لے جاؤ',
'delete_and_move_confirm' => 'آہو، صفحہ مٹا دیو',

# Export
'export'            => 'صفحے باہر پیجو',
'export-submit'     => 'برامد کرو',
'export-addcattext' => 'اس ٹولی توں صفحے شامل کرو:',
'export-addcat'     => 'شامل کرو',
'export-download'   => 'فائل دے طور تے بچاؤ',
'export-templates'  => 'سچہ شامل کرو',

# Namespace 8 related
'allmessages'        => 'سسٹم سنیآ',
'allmessagesname'    => 'ناں',
'allmessagesdefault' => 'ڈیفالٹ لکھائی',
'allmessagescurrent' => 'موجودہ لکھائی',

# Thumbnails
'thumbnail-more'  => 'وڈا کرو',
'filemissing'     => 'فائل گواچی ہوئی اے',
'thumbnail_error' => '$1 دی نکی مورت بناندیاں مسئلہ',

# Special:Import
'import'                  => 'صفحے لیاؤ',
'import-interwiki-submit' => 'لے آؤ',
'importstart'             => 'صفحے لیا رۓ آں۔۔۔۔۔',
'importnopages'           => 'لانے آسطے کوئی صفحہ نئیں۔',
'importcantopen'          => 'لیاندی گئی فائل نئیں کھولی جاسکی',
'importnotext'            => 'خالی یا کوئی لکھائی نئیں',
'importsuccess'           => 'لے کے آگۓ آں!',
'importnofile'            => 'لیاندی ہوئی کوئی فائل نئیں چڑہائی گئی۔',
'import-noarticle'        => 'لیانے آسطے کوئی صفحہ نئیں!',

# Import log
'importlogpage' => 'لاگ لے کے آؤ',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'میرا صفحہ',
'tooltip-pt-mytalk'               => 'میریاں گلاں',
'tooltip-pt-preferences'          => 'میریاں تانگاں',
'tooltip-pt-watchlist'            => 'او صفحے جنہاں وچ تبدیلیاں تسی ویکھ رہے او',
'tooltip-pt-mycontris'            => 'میرے کم',
'tooltip-pt-login'                => 'جی صدقے اندر آؤ، پر اے لازمی نہیں۔',
'tooltip-pt-anonlogin'            => 'اے بہتر اے کہ لاگ ان ہو جاؤ، لیکن فیر وی اے لازمی نئیں۔',
'tooltip-pt-logout'               => 'باہر آؤ',
'tooltip-ca-talk'                 => 'اس صفحے دے بار وچ گل بات',
'tooltip-ca-edit'                 => 'تسیں اس صفحے تے لکھ سکدے او۔
محفوظ کرن توں پہلاں کچے کم نوں ویکھ لو۔',
'tooltip-ca-addsection'           => 'اس گل بات وچ حصہ لے لو۔',
'tooltip-ca-viewsource'           => 'اے صفحہ بچایا گیا اے۔
تسی اینو صرف ویکھ سکدے او۔',
'tooltip-ca-history'              => 'اس صفحے دا پرانہ ورژن۔',
'tooltip-ca-protect'              => 'اس صفحے نوں بچاؤ',
'tooltip-ca-delete'               => 'اس صفحے نوں مٹاؤ',
'tooltip-ca-move'                 => 'اس صفحے نوں لے چلو',
'tooltip-ca-watch'                => 'اس صفحہ تے نظر رکھو',
'tooltip-ca-unwatch'              => 'اس صفحے توں نظر ہٹاؤ',
'tooltip-search'                  => 'کھوج {{SITENAME}}',
'tooltip-search-go'               => 'اس ناں دے صفحے تے چلو، اگر اے ہے گا اے',
'tooltip-search-fulltext'         => 'اس لفظ نوں صفحیاں چ لبو',
'tooltip-p-logo'                  => 'پہلا صفہ',
'tooltip-n-mainpage'              => 'پہلے صفحے دی سیر',
'tooltip-n-mainpage-description'  => 'پہلے ورقے تے جاؤ',
'tooltip-n-portal'                => 'منصوبے دے بارے وچ، توسی کی کر سکدے او تے کنج کھوج سکدے او',
'tooltip-n-currentevents'         => 'موجودہ حالات تے پچھلیاں معلومات دیکھو',
'tooltip-n-recentchanges'         => 'وکی تے نویاں تبدیلیاں۔',
'tooltip-n-randompage'            => 'بیترتیب صفحے کھولو۔',
'tooltip-n-help'                  => 'مدد لینے آلی جگہ۔',
'tooltip-t-whatlinkshere'         => 'اس نال جڑے سارے وکی صفحے۔',
'tooltip-t-recentchangeslinked'   => 'اس صفحے توں جڑے صفحیاں چ نویاں تبدیلیاں',
'tooltip-feed-rss'                => 'RSS feed for this page',
'tooltip-feed-atom'               => 'Atom feed for this page',
'tooltip-t-contributions'         => 'اس ورتن والے دے کم ویکھو',
'tooltip-t-emailuser'             => 'اس ورتن والے نو ای میل کرو',
'tooltip-t-upload'                => 'فائل چڑھاؤ',
'tooltip-t-specialpages'          => 'سارے خاص صفحے',
'tooltip-t-print'                 => 'اس صفحے دا چھپنے آلا ورژن ویکھو',
'tooltip-t-permalink'             => 'اس صفحے دے اس ورژن نال پرماننٹ لنک',
'tooltip-ca-nstab-main'           => 'مواد آلا صفحہ ویکھو',
'tooltip-ca-nstab-user'           => 'ورتن آلے دا صفحہ ویکھو',
'tooltip-ca-nstab-media'          => 'میڈیا آلا صفحہ ویکھو',
'tooltip-ca-nstab-special'        => 'اے اک خاص صفحہ اے، تےی اے صفحہ آپے نئیں لکھ سکدے',
'tooltip-ca-nstab-project'        => 'منصوبے دا صفحہ ویکھو',
'tooltip-ca-nstab-image'          => 'فائل دا صفحہ ویکھو',
'tooltip-ca-nstab-template'       => 'سانچہ تکو',
'tooltip-ca-nstab-help'           => 'مدد دا صفحہ ویکھو',
'tooltip-ca-nstab-category'       => 'کیٹاگری آلا صفحہ ویکھو',
'tooltip-minoredit'               => 'انیو نکے کم چ گنو',
'tooltip-save'                    => 'اپنا کم بچالو',
'tooltip-preview'                 => 'کچا کم ویکھو، اس بٹن نوں بچان توں پہلاں استعمال کرو!۔',
'tooltip-diff'                    => 'اس عبارت وچ کیتیاں تبدیلیاں وکھاؤ۔',
'tooltip-compareselectedversions' => 'چنے ہوۓ صفحیاں وچ فرق ویکھو۔',
'tooltip-watch'                   => 'اس صفحے تے نظر رکھو',
'tooltip-upload'                  => 'فائل چڑھانا شروع کرو',
'tooltip-rollback'                => '"رول بیک" اک کلک چ صفحے نوں پچھلی حالت چ لے چلے گا',
'tooltip-undo'                    => '"واپس" تے کلک کرن نال توانوں صفحہ کچا وکھایا جاۓ گا۔
اس نال تسی واپس کرن دی وجہ لکھ سکو گے۔',

# Attribution
'others' => 'دوجے',

# Info page
'infosubtitle' => 'صفحے آسطے معلومات',
'numedits'     => 'لکھائی دی گنتی (صفحہ): $1',
'numwatchers'  => 'ویکھنے آلیاں دی گنتی: $1',

# Math errors
'math_unknown_error'    => 'انجان مسئلہ',
'math_unknown_function' => 'انجان کم',

# Browsing diffs
'previousdiff' => '← پرانی لکھائی',
'nextdiff'     => 'نویں لکھائی →',

# Media information
'file-info-size'       => '(پکسل:$1 × $2, فائل سائز: $3, مائم ٹائپ: $4)',
'file-nohires'         => '<small>اس توں وڈی فوٹو موجود نہیں۔</small>',
'svg-long-desc'        => '(ایس وی جی فائل، پکسل:$1 × $2، فائل سائز: $3)',
'show-big-image'       => 'وڈی مورت',
'show-big-image-thumb' => '<small>کچے کم دے پکسل:$1 × $2</small>',

# Special:NewFiles
'newimages' => 'نئی فائلاں دی نگری',
'noimages'  => 'ویکھن آسطے کج نئیں۔',
'ilsubmit'  => 'کھوجو',
'bydate'    => 'تاریخ نال',

# Bad image list
'bad_image_list' => 'فارمیٹ اینج اے:۔

صرف لسٹ آلیاں چیزاں (او لائناں جیڑیاں * توں شروع ہوندیاں نے) تے فور کیتا جاندا اے۔ لائن تے پہلا جوڑ لازمی طور تے غلط فائل نال جڑدا اے۔ اسے لائن تے کوئی بعد چ آنے آلا جوڑ خاص سمجھیا جاۓ گا یعنی او صفحے جتھے فائل ان لائن ہو سکدی اے۔',

# Metadata
'metadata'          => 'میٹا ڈیٹا',
'metadata-help'     => 'اس فائل وچ ہور وی معلومات نے، شاید او ڈیجیٹل کیمرے یا سکینر نے پائیاں گئیاں نے جس نال اینو کچھیا یا ڈیجیٹل بنایا گیا اے۔<div/>
اگر فائل نو ایدی اصلی حالت توں تبدیل کیتا گیا اے تے کجھ تفصیلات تبدیل ہوئی فائل دے بارے چ نئیں دسن گیاں۔',
'metadata-expand'   => 'ہور تفصیلات دسو',
'metadata-collapse' => 'تفصیلات چھپاؤ',
'metadata-fields'   => 'ایگزف میٹاڈیٹا ایتھے دتے گۓ مورت آلے صفحے تے دتے جان گے جدوں میٹاڈیٹا ٹیبل کھلیا ہوۓ گا۔ باقی چیزاں بائی ڈیفالٹ چھپیاں رہن گئیاں
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'        => 'چوڑائی',
'exif-imagelength'       => 'اچائی',
'exif-datetime'          => 'فائل بدلن دی تاریخ تے ویلا',
'exif-imagedescription'  => 'مورت دا ناں',
'exif-make'              => 'کیمرہ بنانے آلا',
'exif-model'             => 'کیمرا ماڈل',
'exif-software'          => 'استعمال ہویا سافٹویر',
'exif-artist'            => 'بنانے آلا',
'exif-usercomment'       => 'ورتن آلے دی صلاع',
'exif-fnumber'           => 'ایف نمبر',
'exif-shutterspeedvalue' => 'شٹر دی تیزی',
'exif-aperturevalue'     => 'اپرچر',
'exif-lightsource'       => 'روشنی دا ذریعہ',
'exif-flash'             => 'فلیش',
'exif-flashenergy'       => 'فلیش دی طاقت',
'exif-filesource'        => 'فائل دا ذریعہ',
'exif-contrast'          => 'فرق',
'exif-sharpness'         => 'صفائی',
'exif-gpslongitude'      => 'طول بلد',
'exif-gpsaltitude'       => 'اچائی',
'exif-gpsspeedref'       => 'تیزی دا ناپ',
'exif-gpstrack'          => 'چلن دی راہ',
'exif-gpsimgdirection'   => 'مورت دی راہ',

'exif-unknowndate' => 'انجان تاریخ',

'exif-orientation-1' => 'عام',

'exif-exposureprogram-0' => 'بیان نئیں کیتا گیا',
'exif-exposureprogram-1' => 'طریقہ',
'exif-exposureprogram-2' => 'عام پروگرام',

'exif-subjectdistance-value' => '$1 میٹر',

'exif-meteringmode-0'   => 'انجان',
'exif-meteringmode-1'   => 'اوسط',
'exif-meteringmode-3'   => 'جگہ',
'exif-meteringmode-6'   => 'کج حصہ',
'exif-meteringmode-255' => 'دوجے',

'exif-lightsource-9'   => 'چنگا موسم',
'exif-lightsource-10'  => 'بدل آلا موسم',
'exif-lightsource-11'  => 'سایہ',
'exif-lightsource-255' => 'روشنی دے ہور ذریعے',

'exif-focalplaneresolutionunit-2' => 'انچ',

'exif-sensingmethod-1' => 'غیر واضح',

'exif-customrendered-0' => 'عام طریقہ',
'exif-customrendered-1' => 'اپنی مرضی دا طریقہ',

'exif-scenecapturetype-0' => 'معیاری',
'exif-scenecapturetype-1' => 'لینڈسکیپ',
'exif-scenecapturetype-2' => 'پورٹریٹ',
'exif-scenecapturetype-3' => 'رات دا منظر',

'exif-gaincontrol-0' => 'کوئی نئیں',

'exif-contrast-0' => 'عام',
'exif-contrast-1' => 'نرم',
'exif-contrast-2' => 'سخت',

'exif-saturation-0' => 'عام',

'exif-sharpness-0' => 'عام',
'exif-sharpness-1' => 'نرم',
'exif-sharpness-2' => 'سخت',

'exif-subjectdistancerange-0' => 'انجان',
'exif-subjectdistancerange-1' => 'ماکرو',
'exif-subjectdistancerange-2' => 'نیڑے دا منظر',
'exif-subjectdistancerange-3' => 'دور دا منظر',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'کلومیٹر فی کینٹہ',
'exif-gpsspeed-m' => 'میل فی کینٹہ',
'exif-gpsspeed-n' => 'ناٹ',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'سدھا راہ',
'exif-gpsdirection-m' => 'مقناطیسی راہ',

# External editor support
'edit-externally'      => 'بارلا سافٹ ویئر استعال کردے ہوۓ اے فائل لکھو',
'edit-externally-help' => 'زیادہ معلومات آسطے اے [http://www.mediawiki.org/wiki/Manual:External_editors] ویکھو۔',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'سارے',
'imagelistall'     => 'سارے',
'watchlistall2'    => 'سارے',
'namespacesall'    => 'سارے',
'monthsall'        => 'سارے',

# E-mail address confirmation
'confirmemail' => 'ای میل پتہ پکا کرو',

# Delete conflict
'recreate' => 'دوبارہ بناؤ',

# action=purge
'confirm_purge_button' => 'ٹھیکھ ہے',

# Multipage image navigation
'imgmultipageprev' => '← پچھلا صفحہ',
'imgmultipagenext' => 'اگلا صفحہ →',
'imgmultigo'       => 'جاؤ!',
'imgmultigoto'     => '$1 تے جاؤ',

# Table pager
'table_pager_next'  => 'اگلا صفحہ',
'table_pager_prev'  => 'پچھلا صفحہ',
'table_pager_first' => 'پہلا صفہ',

# Watchlist editing tools
'watchlisttools-view' => 'ملدیاں ہوئیاں تبدیلیاں ویکھو',
'watchlisttools-edit' => 'اکھ تھلے رکھے ہوۓ صفحیاں نوں ویکھو تے تبدیل کرو',
'watchlisttools-raw'  => 'کچی اکھ تھلے رکھی ہوئی نو تبدیل کرو',

# Special:Version
'version' => 'ورژن',

# Special:SpecialPages
'specialpages' => 'خاص صفحے',

);
