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
# Dates
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
'april'         => 'آوریل',
'may_long'      => 'می',
'june'          => 'جون',
'july'          => 'جولای',
'august'        => 'آگوست',
'september'     => 'سپتامبر',
'october'       => 'اوکتبر',
'november'      => 'نوامبر',
'december'      => 'دسامبر',
'january-gen'   => 'ژانویه',
'february-gen'  => 'فوریه',
'march-gen'     => 'مارس',
'april-gen'     => 'آوریل',
'may-gen'       => 'می',
'june-gen'      => 'جون',
'july-gen'      => 'جولای',
'august-gen'    => 'آگوست',
'september-gen' => 'سپتامبر',
'october-gen'   => 'اوکتبر',
'november-gen'  => 'نومبر',
'december-gen'  => 'دسمبر',
'jan'           => 'ژان',
'feb'           => 'فب',
'mar'           => 'ما',
'apr'           => 'آپ',
'may'           => 'می',
'jun'           => 'جون',
'jul'           => 'جول',
'aug'           => 'آگو',
'sep'           => 'سپت',
'oct'           => 'اوکت',
'nov'           => 'نوو',
'dec'           => 'دس',

# Categories related messages
'category_header'        => 'صفحات ته دسته "$1"',
'subcategories'          => 'زیرمجموعه',
'category-media-header'  => 'مدیا ته دسته "$1"',
'category-empty'         => "''ای دسته شامل هچ صفحه یا مدیایی نهنت''",
'listingcontinuesabbrev' => 'cont.',

'about'     => 'باره',
'newwindow' => '(ته نوکین پنچره ی پچ کن)',
'cancel'    => 'کنسل',
'qbfind'    => 'درگیزگ',
'qbedit'    => 'اصلاح',
'mytalk'    => 'منی گپ',

'errorpagetitle'   => 'خطا',
'returnto'         => 'برگشتن په $1.',
'tagline'          => 'چه {{SITENAME}}',
'help'             => 'کمک',
'search'           => 'گردگ',
'searchbutton'     => 'گردگ',
'searcharticle'    => 'برو',
'history'          => 'تاریح صفحه',
'history_short'    => 'تاریح',
'printableversion' => 'نسخه چهاپی',
'permalink'        => 'دایمی لینک',
'edit'             => 'اصلاح',
'editthispage'     => 'ای صفحه یا اصلاح کن',
'delete'           => 'حذف',
'protect'          => 'حفاظت',
'newpage'          => 'نوکین صفحه',
'talkpage'         => 'ای صفحه باره بحث کن',
'talkpagelinktext' => 'گپ',
'personaltools'    => 'شخصی وسایل',
'talk'             => 'بحث',
'views'            => 'دیدگ',
'toolbox'          => 'جعبه ابزار',
'redirectedfrom'   => '(ورود غیر مستقیم چه $1)',
'redirectpagesub'  => 'صفحه غیر مستقیم',
'jumpto'           => 'کپ کتن په:',
'jumptonavigation' => 'ترگ',
'jumptosearch'     => 'گردگ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'باره {{SITENAME}}',
'aboutpage'            => 'پروژه:باره',
'bugreports'           => 'گزارشات باگ',
'bugreportspage'       => 'پروژه:گزارشات باگ',
'copyrightpage'        => '{{ns:project}}:حقوق کپی',
'currentevents'        => 'هنوکین رویداد',
'currentevents-url'    => 'پروژه: رویدادان هنوکین',
'disclaimers'          => 'بی میاری گان',
'disclaimerpage'       => 'پروژه: عمومی بی میاری',
'edithelp'             => 'کمک اصلاح',
'edithelppage'         => 'کمک:اصلاح',
'helppage'             => 'کمک:محتوا',
'mainpage'             => 'صفحه اصلی',
'mainpage-description' => 'صفحه اصلی',
'portal'               => 'پورتال انجمن',
'portal-url'           => 'پروژه:انجمن پورتال',
'privacy'              => 'سیاست حفظ اسرار',
'privacypage'          => 'پروژه: سیاست حفظ اسرار',
'sitesupport'          => 'مدتان',
'sitesupport-url'      => 'پروژه: ساپورت سایت',

'retrievedfrom'       => 'درگیزگ بیته چه"$1"',
'youhavenewmessages'  => 'شما داریت $1 ($2).',
'newmessageslink'     => 'نوکین کوله',
'newmessagesdifflink' => 'اهرین تغییر',
'editsection'         => 'اصلاح',
'editold'             => 'اصلاح',
'editsectionhint'     => 'کسمت اصلاح:$1',
'toc'                 => 'محتوا',
'showtoc'             => 'پیش دار',
'hidetoc'             => 'پناه کن',
'site-rss-feed'       => '$۱ منبع RSS',
'site-atom-feed'      => '$1 منبع Atom',
'page-rss-feed'       => '"$1" RSS منبع',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'صفحه کاربر',
'nstab-project'  => 'صفحه پروژه',
'nstab-image'    => 'فایل',
'nstab-template' => 'تمپلت',
'nstab-category' => 'دسته',

# General errors
'badtitle'       => 'بدین عنوان',
'badtitletext'   => 'لوٹگی عنوان صفحه نامعتبر انت، هالیک یا په طور غلط لینک بین زبانی بوتت یا بین وی کی لیننت..
شاید شامل یک یا گیشتر کاراکترنت که ته عنوانان استفاده نه بیت.',
'viewsource'     => 'دیدگ منبع',
'viewsourcefor'  => 'په $1',
'viewsourcetext' => 'شما تونیت منبع ای صفحه یا کپی کنیت:',

# Login and logout pages
'yourname'                => 'نام کاربر:',
'yourpassword'            => 'کمله رمز:',
'remembermypassword'      => 'منی ورود آ ته ای کامپیوتر حفظ کن',
'login'                   => 'وارد بو',
'nav-login-createaccount' => 'وارد بیگ/شرکتن حساب',
'loginprompt'             => 'شمی باید کوکوی آنا په وارد بوتن {{SITENAME}} فعال کنیت',
'userlogin'               => 'وارد بیگ/شرکتن حساب',
'logout'                  => 'در بیگ',
'userlogout'              => 'در بیگ',
'nologin'                 => 'یک ورودی نیستت؟ $1.',
'nologinlink'             => 'شرکتن یک حساب',
'createaccount'           => 'شرکتن حساب',
'gotaccount'              => 'پیشگین حساب هست؟ $1',
'gotaccountlink'          => 'وارد بیگ',
'yourrealname'            => 'راستین نام',
'prefs-help-realname'     => 'راستین نام اختیاری انت.
اگه شما انتخاب کنیت شی په عنوان یک نشانی په شمی کاران کارمرز بیت.',
'loginsuccesstitle'       => 'گون موفقیت وارد بوت',
'loginsuccess'            => "'''' شما الان وارد {{SITENAME}} بیتگیت په عنوان\"\$1\".'''",
'nosuchuser'              => 'کاربری گون نام"$1" نیست.
وتی املایا کنترل کنیت یا یک نوکین حسابی شرکنیت',
'nosuchusershort'         => 'کاربری گون نام "<nowiki>$1</nowiki>" نیست.
وتی املایا کنترل کنیت.',
'nouserspecified'         => 'شما بایدن یک نام کابری مشخص کنیت.',
'wrongpassword'           => 'اشتباهین کلمه رمز وارد بوت. دگه کوشست کن',
'wrongpasswordempty'      => 'کمله رمز وارد بیتگین هالیکت. دیکه کوشست کن.',
'passwordtooshort'        => 'شمی کلمه رمز نامعتبر یا سک هوردنت.
آن بایدن حداقل $1 کاراکتر بیت و گون شمی کاربری نام پرک داشته بیت.',
'mailmypassword'          => 'کلمه رمز آ ایمیل کن',
'passwordremindertitle'   => 'کلمه رمز هنوگین په {{SITENAME}}',
'passwordremindertext'    => 'یک نفری(شاید شما، چه آی پی $1)
لوٹتگی که ما شما را یک نوکین کلمه رمز دیم دهین په {{SITENAME}} ($4).
کلمه رمز په کاربر "$2" الان شینت"$3".
شما بایدن وارد بیت و وتی کلمه رمزآ بدل کنیت انو.

اگه دگه کسی په شما ای درخواست دیم داته و یا شما وتی کلمه رمزآ خاطر داریت و نه لوٹتیت آیآ عوض کنیت، شما تونیت این کوله یا شموشیت و گون هما قدیمی کلمه رمز ادامه دهیت',
'noemail'                 => 'هچ ایمیل په ای کاربر ضبط نه بیته  "$1".',
'passwordsent'            => 'یک نوکین کلمه رمزی په ایمیل ثبت بوتگین په "$1" دیم دهگ بوت.
لطفا دگه وارد بیت باد چه شی که ايآ دریافت کت.',
'eauthentsent'            => 'یک ایمیل تاییدی په نامتگین آدرس ایمیل دیم دهگ بوت.
پیش چه هردابین ایمیلی په حساب دیم دیگ بین، شما بایدن چه دستور العملی که ته ایمیل آتکه پیروی کنیت په شی که شمی حساب که شمی گنت تایید بیت.',

# Edit page toolbar
'bold_sample'     => 'پررنگ نوشته',
'bold_tip'        => 'متن پررنگ',
'italic_sample'   => 'ایتالیکی نوشته',
'italic_tip'      => 'ایتالیک نوشته',
'link_sample'     => 'عنوان لینک',
'link_tip'        => 'لینک داحلی',
'extlink_sample'  => 'http://www.example.com عنوان لینک',
'extlink_tip'     => 'لینک خارجی',
'headline_sample' => 'نوشته عنوان',
'headline_tip'    => 'عنوان سطح 2',
'math_sample'     => 'فرمولا ادان هور کن',
'math_tip'        => 'فرمول ریاضی(LATeX)',
'nowiki_sample'   => 'وارد کن نوشته بی فرمت ادا',
'nowiki_tip'      => 'فرمت وی کی آ شموش',
'image_tip'       => 'هوریگی فایل',
'media_tip'       => 'لینک فایل',
'sig_tip'         => 'شمی امضا گون تاریخ',
'hr_tip'          => 'خط عمودی',

# Edit pages
'summary'                => 'خلاصه',
'subject'                => 'موضوع/عنوان',
'minoredit'              => 'شی یک هوردین اصلاحی ایت',
'watchthis'              => 'ای صفحات به چار',
'savearticle'            => 'ذخیره صفحه',
'preview'                => 'بازبین',
'showpreview'            => 'پیش دارگ بازبین',
'showdiff'               => 'تغییرات پیش دار',
'anoneditwarning'        => "''هوژاری: ''شما وارد نه بیتگیت.
شمی آدرس آی پی ته تاریح اصلاح صفحه ذخیره بیت.",
'summary-preview'        => 'بازبینی خلاصه',
'blockedtext'            => "<big>'''Your user name or IP address has been blocked.'''</big>

The block was made by $1. The reason given is ''$2''.

* Start of block: $8
* Expiry of block: $6
* Intended blockee: $7

You can contact $1 or another [[{{MediaWiki:Grouppage-sysop}}|administrator]] to discuss the block.
You cannot use the 'e-mail this user' feature unless a valid e-mail address is specified in your [[Special:Preferences|account preferences]] and you have not been blocked from using it.
Your current IP address is $3, and the block ID is #$5. Please include either or both of these in any queries.",
'newarticle'             => '(نوکین)',
'newarticletext'         => "شما په یک رند یک لینک په صفحه ای  آتکگیت که هنگت شر نه بوتت.
په شرکتن صفحه ته جهلیگی جعبه متن آ بنویسیت(see the [[{{MediaWiki:Helppage}}|help page]] for more info).
اگه شما اشتباهی ادانیت، فقط ته وتی بروزر دکمه ''''back'''' بجن.",
'noarticletext'          => 'الان ته ای صفحه هچ متنی نیست، شما تونیت  [[Special:Search/{{PAGENAME}}| په عنوان ای صفحه گردیت]] ته دگه صفحات یا [{{fullurl:{{FULLPAGENAME}}|action=edit}} اصلاح کینت ای صفحه یا].',
'previewnote'            => '<strong> شی یک باز بینی انت:
تغییرات هنگت ذخیره نه بوتگنت!</strong>',
'editing'                => 'اصلاح $1',
'editingsection'         => 'اصلاح $1)بخش)',
'copyrightwarning'       => 'لطفا توجه بیت که کل نوشته یات ته {{SITENAME}}  تحت $2 نشر بنت.(بچار په جزیات$1).
اگه شما لوٹیت شمی نوشتانک اصلاح و دگه چهاپ مبنت، اچه آیانا ادان مهلیت.<b/>
شما ما را قول دهیت که وتی چیزا بنویسیت یا چه یک دامین عمومی کپی کتگیت.
<strong> نوشتانکی که کپی رایت دارند بی اجازه ادا هور مکنیت</strong>',
'longpagewarning'        => '<strong>هوژاری. ای صفحه $1 کیلوبایت نت;
لهتی چه بروزران شاید مشکلاتی چه دست رسی و اصلاح صفحات گیش چه 32ک.ب داشته بنت.
لطفا توجه کنیت په هورد کتن صفحه په هوردترین چنٹ. </strong>',
'templatesused'          => 'تمپلتانی که ته ای صفحه استفاده بیت:',
'templatesusedpreview'   => 'تلمپلت آنی که ته ای بازبینی استفاده بیت',
'template-protected'     => '(محافظتین)',
'template-semiprotected' => '(نیم محافظتی)',
'nocreatetext'           => '{{SITENAME}} شما را چه شرکتن نوکین صفحه منه کته.
شما تونیت برگردیت و یک پیشگین صفحه ای اصلاح کنیت، یا [[Special:Userlogin|log in or create an account]].',
'recreate-deleted-warn'  => "'''Warning: You are recreating a page that was previously deleted.'''

You should consider whether it is appropriate to continue editing this page.
The deletion log for this page is provided here for convenience:",

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
'histlegend'          => 'بخش تفاوت: په مقایسه کتن نسخه یان گزینه انتخاب کنیت اینتر یا دکمه بجن.<br/>
Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit.',
'histfirst'           => 'اولین',
'histlast'            => 'اهرین',

# Revision feed
'history-feed-item-nocomment' => '$1 ته $2',

# Diffs
'history-title'           => 'تاریح بازبینی "$1"',
'difference'              => '(تفاوتان بین نسخه یان)',
'lineno'                  => 'خط$1:',
'compareselectedversions' => 'مقایسه انتخاب بوتگین نسخه یان',
'editundo'                => 'خنثی کتن',
'diff-multi'              => '({{PLURAL:$1|One intermediate revision|$1 بازبینیان میانی}} پیش دارگ نه بیت.)',

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
'nchanges'                       => '$1 {{PLURAL:$1|change|تغییرات}}',
'recentchanges'                  => 'نوکین تغییرات',
'recentchanges-feed-description' => 'آهرین تغییرات ته وی کی چه ای فید رند گر',
'rcnote'                         => "جهل {{PLURAL:$1|هست '''1''' تغییر|هست آهرین '''$1''' تغییرات}} ته آهرین {{PLURAL:$2|روچ|'''$2''' days}}, په داب $3.",
'rcnotefrom'                     => "جهلا تغییرات چه '''$2''' (up to '''$1''' shown). هست",
'rclistfrom'                     => 'پیش دار نوکین تغییراتآ چه $1',
'rcshowhideminor'                => '$1 هوردین تغییرات',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 کاربران وارد بوتگین',
'rcshowhideanons'                => '$1 نا شناسین کاربران',
'rcshowhidepatr'                 => '$1 اصلاحات کنترل بیتگین',
'rcshowhidemine'                 => '$1 اصلاحات من',
'rclinks'                        => 'پیش دار آهرین$1 تغییرات ته آهرین $2 روچان<br/>$3',
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
'recentchangeslinked-summary'  => 'شی یک لیستی چه تغییراتی هستنت که نوکی اعمال بوتگنت په صفحاتی که چه یک صفحه خاصی لینک بوته( یا په اعضای یک خاصین دسته).
صفحات ته [[ خاص: لیست چارگ| شمی لیست چارگ[[ پررنگنت',

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
'linkstoimage'              => 'جهلیگین صفحات پی ای فایل لینک بوتگنت.',
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
لطفا تایید کنیت که شما چوش کنیت که شما زانیت آی ء عاقبتانآ و شی که شما ای کارآ گون [[{{MediaWiki:Policy-url}}|the policy] انجام دهیت',
'actioncomplete'              => 'کار انجام بیت',
'deletedtext'                 => '"<nowiki>$1</nowiki>" حذف بیت.
بگندیت $1 په ثبتی که نوکین حذفیات',
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
'whatlinkshere-prev'  => '{{PLURAL:$1|next|next $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|next|next $1}}',
'whatlinkshere-links' => '← لینکان',

# Block/unblock
'blockip'       => 'محدود کتن کاربر',
'ipboptions'    => '2 ساعت: 2 ساعت، 1 روچ: 1 روچ، 3 روچ: 3 روچ، 1 هفته: 1 هفته، 2 هفته: 2هفته، 1 ماه: 1 ماه: 2ماه، 3 ماه: 3 ماه، 6 ماه: 6 ماه، 1 سال: 1 سال، بی حد: بی حد',
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
'movepage-moved'   => '<big>\'\'\'"$1" جاه په اجه بوت په"$2"\'\'\'</big>',
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
'file-info-size'       => '($1 × $2 pixel, file size: $3, MIME type: $4)',
'file-nohires'         => '<small>مزنترین رزلوشن نیست.</small>',
'svg-long-desc'        => '(SVG file, nominally $1 × $2 pixels, file size: $3)',
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
'metadata-fields'   => 'EXIF metadata fields listed in this message will be included on image page display when the metadata table is collapsed.
Others will be hidden by default.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

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
'version' => 'نسخه',

# Special:SpecialPages
'specialpages' => 'حاصین صفحه',

);
