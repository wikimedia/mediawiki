<?php

# Wikipedia localization for Persian

require_once("LanguageUtf8.php");

#--------------------------------------------------------------------------
# Language-specific text
#--------------------------------------------------------------------------

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# NOTE: To turn off "Disclaimers" in the title links,
# set "disclaimers" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#

/* private */ $wgNamespaceNamesFa = array(
	-2	=> "مدیا",
	-1	=> "ویژه",
	0	=> "",
	1	=> "بحث",
	2	=> "کاربر",
	3	=> "بحث_کاربر",
	4	=> "ویکی‌پدیا",
	5	=> "بحث_ویکی‌پدیا",
	6	=> "تصویر",
	7	=> "بحث_تصویر",
	8	=> "مدیاویکی",
	9	=> "بحث_مدیاویکی",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFa = array(
	"نباشد", "ثابت چپ", "ثابت راست", "شناور چپ"
);

/* private */ $wgSkinNamesFa = array(
	'standard' => "استاندارد",
	'nostalgia' => "نوستالژی",
	'cologneblue' => "آبی کلون",
	'smarty' => "پدینگتون",
	'montparnasse' => "مون‌پارناس",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesFa = array(
	"همیشه PNG کشیده شود",
	"اگر خیلی ساده بودHTML وگرنه PNG",
	"اگر ممکن بود HTML وگرنه PNG",
	"در قالب TeX باقی بماند (برای مرورگرهای متنی)",
	"توصیه برای مرورگرهای امروزی"
);

/* private */ $wgUserTogglesFa = array(
	"hover"		=> "نمایش جعبه‌ی hover روی پیوندهای ویکی",
	"underline" => "زیر پیوندها خط کشیده شود",
	"highlightbroken" => "قالب‌بندی پیوندهای ناقص
<a href=\"\" class=\"new\">به این شکل</a> (امکان دیگر: به این شکل<a href=\"\" class=\"internal\">؟</a>).",
	"justify"	=> "تنظیم بندها",
	"hideminor" => "نشان ندادن تغییرات جزئی در فهرست تغییرات اخیر",
	"usenewrc" => "تغییرات اخیر گسترش‌یافته (برای هر مرورگری نیست)",
	"numberheadings" => "شماره‌گذاری خودکار عناوین",
	"showtoolbar"=>"نمایش نوار ابزار جعبه‌ی ویرایش",
	"editondblclick" => "ویرایش صفحه‌ها با دوکلیک (جاوااسکریپت)",
	"editsection"=>"به کار انداختن ویرایش قسمت‌ها از طریق پیوندهای [ویرایش]",
	"editsectiononrightclick"=>"به کار انداختن ویرایش قسمت‌ها با کلیک راست<br>روی عناوین قسمت‌ها (جاوااسکریپت)",
	"showtoc"=>"نمایش فهرست مندرجات<br>(برای مقالات با بیش از ۳ سرفصل)",
	"rememberpassword" => "کلمه‌ی عبور برای نشست‌های بعدی بماند",
	"editwidth" => "عرض جعبه‌ی ویرایش کامل باشد",
	"watchdefault" => "افزودن صفحاتی که ویرایش می‌کند به فهرست تعقیبات",
	"minordefault" => "پیش‌فرض همه‌ی ویرایش‌ها «جزئی» باشد",
	"previewontop" => "نمایش پیش‌نمایش قبل از جعبه‌ی ویرایش و نه قبل از آن",
	"nocache" => "از کار انداختن حافظه‌ی نهانی صفحات"
);

/* private */ $wgWeekdayNamesFa = array(
	"یک‌شنبه", "دوشنبه", "سه‌شنبه", "چهارشنبه", "پنجشنبه",
	"جمعه", "شنبه"
);

/* private */ $wgMonthNamesFa = array(
	"ژانویه", "فوریه", "مارس", "آوریل", "مه", "ژوئن",
	"ژوئیه", "اوت", "سپتامبر", "اکتبر", "نوامبر",
	"دسامبر"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesFa = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "تنظیم ترجیحات کاربر",
	"Watchlist"		=> "فهرست تعقیبات من",
	"Recentchanges" => "صفحات اخیراً به‌روزشده",
	"Upload"		=> "بار کردن پرونده‌های تصویری",
	"Imagelist"		=> "فهرست تصاویر",
	"Listusers"		=> "کاربران ثبت‌شده",
	"Statistics"	=> "آمارهای وب‌گاه",
	"Randompage"	=> "مقاله‌ی تصادفی",

	"Lonelypages"	=> "مقاله‌های یتیم",
	"Unusedimages"	=> "تصاویر یتیم",
	"Popularpages"	=> "مقاله‌های محبوب",
	"Wantedpages"	=> "مقاله‌های با بیشترین درخواست",
	"Shortpages"	=> "مقاله‌های کوتاه",
	"Longpages"		=> "مقاله‌های طولانی",
	"Newpages"		=> "مقاله‌های تازه‌ایجادشده",
	"Ancientpages"	=> "قدیمی‌ترین مقاله‌ها",
        "Deadendpages"  => "صفحات بن‌بست",
#	"Intl"                => "پیوندهای بین‌زبانی",
	"Allpages"		=> "همه‌ی صفحات بر اساس عنوان",

	"Ipblocklist"	=> "کاربران/نشانی‌های IP بسته‌شده",
	"Maintenance"	=> "صفحه‌ی نگهداری",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "منابع خارجی کتاب‌ها",
#	"Categories"	=> "رده‌های صفحات",
	"Export"		=> "صدور صفحه‌ی XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesFa = array(
	"Blockip"		=> "بستن کاربر/نشانی IP",
	"Asksql"		=> "پرس‌وجوی پایگاه داده",
	"Undelete"		=> "احیای صفحات حذف‌شده"
);

/* private */ $wgDeveloperSpecialPagesFa = array(
	"Lockdb"		=> "فقط‌خواندنی کردن پایگاه داده",
	"Unlockdb"		=> "احیای دسترسی نوشتن به پایگاه داده",
	"Debug"			=> "اطلاعات اشکال‌زدایی"
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------

/* private */ $wgAllMessagesFa = array(

# Bits of text used by many pages:
#
"categories" => "رده‌های صفحات",
"category" => "رده",
"category_header" => "مقاله‌های رده‌ی «$1»",
"subcategories" => "زیررده‌ها",

"linktrail"		=> "/^([a-z]+)(.*)\$/sD", /* This may need to be changed --RP */
"mainpage"		=> "صفحه‌ی اصلی",
"mainpagetext"	=> "نرم‌افزار ویکی با موفقیت نصب شد.",
"about"			=> "درباره",
"aboutwikipedia" => "درباره‌ی ویکی‌پدیا",
"aboutpage"		=> "ویکی‌پدیا:درباره",
"help"			=> "راهنما",
"helppage"		=> "ویکی‌پدیا:راهنما",
"wikititlesuffix" => "ویکی‌پدیا",
"bugreports"	=> "گزارش اشکالات",
"bugreportspage" => "ویکی‌پدیا:گزارش اشکالات",
"sitesupport"   => "کمک مالی",
"sitesupportpage" => "ویکی‌پدیا:کمک_مالی", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "سؤالات معمول",
"faqpage"		=> "ویکی‌پدیا:سؤالات معمول",
"edithelp"		=> "راهنمای ویرایش کردن",
"edithelppage"	=> "ویکی‌پدیا:چگونه_صفحات_را_ویرایش_کنیم",
"cancel"		=> "لغو",
"qbfind"		=> "یافتن",
"qbbrowse"		=> "مرور",
"qbedit"		=> "ویرایش",
"qbpageoptions" => "این صفحه",
"qbpageinfo"	=> "بافت",
"qbmyoptions"	=> "صفحات من",
"qbspecialpages"	=> "صفحات ویژه",
"moredotdotdot"	=> "بیشتر...",
"mypage"		=> "صفحه‌ی من",
"mytalk"		=> "بحث من",
"currentevents" => "وقایع کنونی",
"disclaimers" => "تکذیب‌نامه‌ها",
"disclaimerpage"		=> "ویکی‌پدیا:تکذیب‌نامه‌ی عمومی",
"errorpagetitle" => "خطا",
"returnto"		=> "بازگشت به $1.",
"fromwikipedia"	=> "از ویکی‌پدیا، دایرةالمعارف آزاد.",
"whatlinkshere"	=> "صفحاتی که به اینجا پیوند دارند",
"help"			=> "راهنما",
"search"		=> "جستجو شود",
"go"		=> "برود",
"history"		=> "تاریخچه‌ی صفحه",
"printableversion" => "نسخه‌ی قابل چاپ",
"editthispage"	=> "ویرایش این صفحه",
"deletethispage" => "حذف این صفحه",
"protectthispage" => "محافظت از این صفحه",
"unprotectthispage" => "از محافظت در آوردن این صفحه",
"newpage" => "صفحه‌ی جدید",
"talkpage"		=> "بحث درباره‌ی این صفحه",
"postcomment"   => "نوشتن نظر",
"articlepage"	=> "نمایش مقاله",
"subjectpage"	=> "نمایش موضوع", # For compatibility
"userpage" => "نمایش صفحه‌ی کاربر",
"wikipediapage" => "نمایش فوق صفحه",
"imagepage" => 	"نمایش صفحه‌ی تصویر",
"viewtalkpage" => "نمایش مباحثات",
"otherlanguages" => "زبان‌های دیگر",
"redirectedfrom" => "(تغییر مسیر از $1)",
"lastmodified"	=> "این صفحه آخرین بار در $1 تغییر کرده است.",
"viewcount"		=> "این صفحه $1 بار دیده شده است.",
"gnunote" => "کلیه‌ی متون تحت شرایط <a class=internal href='http://en.wikipedia.org/wiki/Wikipedia:Text_of_the_GNU_Free_Documentation_License'>اجازه‌نامه‌ی مستندات آزاد گنو</a> در دسترسند.",
"printsubtitle" => "(از http://fa.wikipedia.org)",
"protectedpage" => "صفحه‌ی محافظت‌شده",
"administrators" => "ویکی‌پدیا:مدیران",
"sysoptitle"	=> "دسترسی اپراتور سیستم لازم است",
"sysoptext"		=> "عملی را که شما درخواست کرده‌اید فقط کاربران با وضعیت «اپراتور سیستم» می‌توانند انجام دهند.
به $1 مراجعه کنید.",
"developertitle" => "دسترسی برنامه‌نویس لازم است",
"developertext"	=> "عملی را که شما درخواست کرده‌اید فقط می‌تواند توسط کاربران با وضعیت «برنامه‌نویس» انجام شود.
به $1 مراجعه کنید.",
"nbytes"		=> "$1 بایت",
"go"			=> "برو",
"ok"			=> "باشد",
"sitetitle"		=> "ویکی‌پدیا",
"sitesubtitle"	=> "دایرةالمعارف آزاد",
"retrievedfrom" => "گرفته شده از «$1»",
"newmessages" => "$1 دارید.",
"newmessageslink" => "پیغام جدید",
"editsection"=>"ویرایش",
"toc" => "فهرست مندرجات",
"showtoc" => "نمایش داده شود",
"hidetoc" => "مخفی شود",
"thisisdeleted" => "نمایش یا احیای $1؟",
"restorelink" => "$1 ویرایش حذف‌شده",

# Main script and global functions
#
"nosuchaction"	=> "چنین عملی وجود ندارد",
"nosuchactiontext" => "ویکی عمل مشخص شده در URL را نمی‌شناسد",
"nosuchspecialpage" => "چنین صفحه‌ی ویژه‌ای وجود ندارد",
"nospecialpagetext" => "شما صفحه‌ی ویژه‌ای را درخواست کرده‌اید که ویکی نمی‌شناسد.",

# General errors
#
"error"			=> "خطا",
"databaseerror" => "خطای پایگاه داده",
"dberrortext"	=> "A database query syntax error has occurred.
This could be because of an illegal search query (see $5),
or it may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function \"<tt>$2</tt>\".
MySQL returned error \"<tt>$3: $4</tt>\".",
"dberrortextcl" => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
"noconnect"		=> "شرمنده! ویکی مشکلات فنی دارد، و نمی‌تواند با خادم پایگاه داده تماس بگیرد.",
"nodb"			=> "نمی‌توان پایگاه داده‌ی $1 را انتخاب کرد",
"cachederror"		=> "در زیر یک نسخه‌ی بایگانی‌شده‌ی صفحه‌ی درخواستی می‌آید، و ممکن است به‌روز نباشد.",
"readonly"		=> "پایگاه داده قفل شد",
"enterlockreason" => "دلیلی برای قفل کردن ذکر کنید، شامل تقریبی برای زمانی که قفل برداشته خواهد شد",
"readonlytext"	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
"missingarticle" => "پایگاه داده متن صفحه‌ای به نام «$1» را که باید می‌یافت، نیافت.

<p>این مشکل معمولاٌ بر اثر ادامه دادن پیوندهای تاریخ‌گذشته‌ی تفاوت یا تاریخچه‌ی صفحاتی رخ می‌دهد که حذف شده‌اند.

<p>اگر مورد شما این نیست، ممکن است اشکالی در نرم‌افزار پیدا کرده باشید.
لطفاً این مسئله را، با ذکر URL، به یکی از مدیران گزارش کنید.",
"internalerror" => "خطای داخلی",
"filecopyerror" => "نتوانستم از پرونده‌ی «$1» روی «$2» نسخه‌برداری کنم.",
"filerenameerror" => "نتوانستم پرونده‌ی «$1» را به «$2» تغییر نام دهم.",
"filedeleteerror" => "نتوانستم پرونده‌ی «$1» را حذف کنم",
"filenotfound"	=> "پرونده‌ی «$1» یافت نشد.",
"unexpected"	=> "مقدار غیرمنتظره: «$1»=«$2».",
"formerror"		=> "خطا: نمی‌توان فرم را فرستاد",
"badarticleerror" => "نمی‌توان این عمل را بر این صفحه انجام داد.",
"cannotdelete"	=> "نتوانستم صفحه را تصویر مشخص‌شده را حذف کنم. (ممکن است قبلاً کس دیگری آن را حذف کرده باشد.)",
"badtitle"		=> "عنوان بد",
"badtitletext"	=> "عنوان درخواستی نامعتبر، خالی، یا عنوانی بین زبانی یا بین‌ویکی‌ای با پیوند نادرست بود.",
"perfdisabled" => "شرمنده! این امکان موفقتاً برداشته شده چون پایگاه داده را چنان کند می‌کند
که هیچ کس نمی‌تواند از ویکی استفاده کند.",
"perfdisabledsub" => "این نسخه‌ی ذخیره‌شده‌ای از $1 است: ",
"wrong_wfQuery_params" => "پارامترهای wfQuery() نادرست است<br>
تابع: $1<br>
پرس‌وجو: $2
",
"viewsource" => "نمایش مبدأ",
"protectedtext" => "این صفحه برای جلوگیری از ویرایش قفل شده است؛ این کار ممکن است دلایل مختلفی داشته باشد. لطفاً به
[[ویکی‌پدیا:صفحه‌ی محافظت‌شده]] مراجعه کنید.

شما می‌توانید مبدأ این صفحه را مشاهده و تکثیر کنید:",

# Login and logout pages
#
"logouttitle"	=> "خروج کاربر از سیستم",
"logouttext" => "اکنون از سیستم خارج شدید.
شما می‌توانید به استفاده‌ی گمنام از ویکی‌پدیا ادامه دهید، یا می‌توانید با همین کاربر یا کاربر دیگری
به سیستم وارد شوید. توجه کنید که تا زمانی که cache مرورگرتان را پاک کنید،
بعضی صفحات ممکن است به شکلی نمایش یابند که انگار هنوز وارد سیستم هستید.\n",

"welcomecreation" => "<h2>$1، خوش آمدید!</h2><p>حساب شما ایجاد شد.
فراموش نکنید که ترجیحات ویکی‌پدیاتان را تنظیم کنید.",

"loginpagetitle" => "ورود کاربر به سیستم",
"yourname"		=> "نام کاربری شما",
"yourpassword"	=> "کلمه‌ی عبور شما",
"yourpasswordagain" => "کلمه‌ی عبور را دوباره وارد کنید",
"newusersonly"	=> " (فقط کاربران جدید)",
"remembermypassword" => "کلمه‌ی عبور بین نشست‌ها به خاطر سپرده شود.",
"loginproblem"	=> "<b>ورود شما به سیستم با مشکلی مواجه شد.</b><br>دوباره تلاش کنید!",
"alreadyloggedin" => "<font color=red><b>کاربر $1, شما از قبل وارد سیستم شده‌اید!</b></font><br>\n",

"login"			=> "ورود به سیستم",
"loginprompt"           => "برای ورود به ویکی‌پدیا باید cookieها را فعال کنید.",
"userlogin"		=> "ورود به سیستم",
"logout"		=> "خروج از سیستم",
"userlogout"	=> "خروج از سیستم",
"notloggedin"	=> "به سیستم وارد نشده‌اید",
"createaccount"	=> "ایجاد حساب جدید",
"createaccountmail"	=> "با پست الکترونیکی",
"badretype"		=> "کلمه‌های عبوری که وارد کردید یکسان نیستند.",
"userexists"	=> "نام کاربری‌ای که وارد کردید قبلاً استفاده شده است. لطفاً یک نام دیگر انتخاب کنید.",
"youremail"		=> "پست الکترونیکی شما*",
"yournick"		=> "لقب شما (برای امضاها)",
"emailforlost"	=> "* وارد کردن نشانی پست الکترونیکی اختیاری است. اما اجازه می‌دهد دیگران،
بدون نیاز به این که نشانی پست الکترونیک‌تان را افشا کنید، با شما از طریق وبگاه تماس بگیرند،
و اگر کلمه‌ی عبورتان را فراموش کنید نیز کمکتان می‌کند.",
"loginerror"	=> "خطا در ورود به سیستم",
"nocookiesnew"	=> "حساب کاربری ایجاد شد، اما شما وارد سیستم نشدید. ویکی‌پدیا برای ورود کاربران به سیستم از cookie استفاده می‌کند. شما
cookieها را از کار انداخته‌اید. لطفاً cookieها را به کار بیندازید، و سپس با نام کاربری و کلمه‌ی عبور جدیدتان به سیستم وارد شوید.",
"nocookieslogin"	=> "ویکی‌پدیا برای ورود کاربران به سیستم از cookie استفاده می‌کند. شما cookieها را از کار
انداخته‌اید. لطفاً cookieها را به کار بیندازید و دوباره تلاش کنید.",
"noname"		=> "شما نام کاربری معتبری مشخص نکرده‌اید.",
"loginsuccesstitle" => "ورود موفقیت‌آمیز به سیستم",
"loginsuccess"	=> "شما اکنون با نام «$1» به ویکی‌پدیا وارد شده‌اید.",
"nosuchuser"	=> "کاربری با نام «$1» وجود ندارد.
املای نام را بررسی کنید، یا از فرم زیر برای ایجاد یک حساب کاربری جدید استفاده کنید.",
"wrongpassword"	=> "کلمه‌ی عبوری که وارد کردید نادرست است. لطفاٌ دوباره تلاش کنید.",
"mailmypassword" => "یک کلمه‌ی عبور جدید به شما فرستاده شود",
/* The following two messages should not be translated, in case
   the user doesn't use email software able to read Persian. */
"passwordremindertitle" => "Password reminder from Wikipedia",
"passwordremindertext" => "Someone (probably you, from IP address $1)
requested that we send you a new Wikipedia login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
"noemail"		=> "هیچ نشانی پست الکترونیکی‌ای برای کاربر «$1» ثبت نشده است.",
"passwordsent"	=> "یک کلمه‌ی عبور جدید به نشانی الکترونیکی ثبت شده برای کاربر «$1» فرستاده شد.
لطفاٌ پس از دریافت آن دوباره به سیستم وارد شوید.",
"loginend"		=> "",

# Edit page toolbar
"bold_sample"=>"متن سیاه",
"bold_tip"=>"متن سیاه",
"italic_sample"=>"متن ایتالیک",
"italic_tip"=>"متن ایتالیک",
"link_sample"=>"عنوان پیوند",
"link_tip"=>"پیوند داخلی",
"extlink_sample"=>"http://www.example.com عنوان پیوند",
"extlink_tip"=>"پیوند خارجی (پیشوند http://‎ را فراموش نکنید)",
"headline_sample"=>"متن عنوان",
"headline_tip"=>"عنوان سطح ۲",
"math_sample"=>"درج فرمول در اینجا",
"math_tip"=>"فرمول ریاضی (LaTeX)",
"nowiki_sample"=>"اینجا متن قالب‌بندی‌نشده وارد شود",
"nowiki_tip"=>"نادیده گرفتن قالب‌بندی ویکی",
"image_sample"=>"مثال.jpg",
"image_tip"=>"تصویر داخل متن",
"media_sample"=>"مثال.mp3",
"media_tip"=>"پیوند پرونده‌ی رسانه",
"sig_tip"=>"امضای شما و برچسب زمان",
"hr_tip"=>"خط افقی (با صرفه‌جویی استفاده کنید)",
"infobox"=>"برای درج متن نمونه روی یک دکمه کلیک کنید",

# Edit pages
#
"summary"		=> "خلاصه",
"subject"		=> "موضوع/عنوان",
"minoredit"		=> "این ویرایش جزئی است",
"watchthis"		=> "تعقیب این مقاله",
"savearticle"	=> "صفحه ذخیره شود",
"preview"		=> "پیش‌نمایش",
"showpreview"	=> "پیش‌نمایش نمایش یابد",
"blockedtitle"	=> "کاربر بسته شده است",
"blockedtext"	=> "نام کاربری یا نشانی IP شما توسط $1 بسته شده است.
دلیل داده‌شده این است:<br>''$2''<p>شما می‌توانید با $1 یا یکی از 
[[ویکی‌پدیا:مدیران|مدیران]] تماس بگیرید و در این باره صحبت کنید.

توجه کنید که شما نمی‌توانید از امکان «فرستادن پست الکترونیکی به این کاربر» استفاده کنید مگر اینکه نشانی پست الکترونیکی
معتبری در [[ویژه:ترجیحات|ترجیحات کاربری]]‌تان ثبت کرده باشید.

نشانی IP شما $3 است. لطفاً این نشانی را در کلیه‌ی پرس‌وجوهایتان ذکر کنید.

==نکته برای کاربران AOL==
به خاطر اعمال تخریبی یک کاربر مشخص AOL، ویکی‌پدیا معمولاً proxyهای AOL را می‌بندد.
متأسفانه ممکن است تعداد زیادی از کاربران AOL از یک خادم proxy واحد استفاده کنند، و در نتیجه کاربران بی‌تقصیر AOL معمولاً ندانسته بسته می‌شوند.
از دردسر ایجاد شده عذر می‌خواهیم.

اگر این اتفاق برای شما افتاد، لطفاً به یکی از مدیران از یک نشانی پست الکترونیک AOL پیغام بفرستید. حتماً نشانی IPی را در فوق داده شده
ذکر کنید.
",
"whitelistedittitle" => "برای ویرایش باید به سیستم وارد شوید",
"whitelistedittext" => "برای ویرایش مقاله‌ها باید به سیستم [[ویژه:Userlogin|وارد]] شوید.",
"whitelistreadtitle" => "برای خواندن باید به سیستم وارد شوید",
"whitelistreadtext" => "
برای خواندن مقالات باید [[ویژه:Userlogin|به سیستم وارد شوید]].",
"whitelistacctitle" => "شما مجاز نیستید حساب درست کنید.",
"whitelistacctext" => "برای ایجاد حساب در این ویکی باید [[ویژه:Userlogin|به سیستم وارد شوید]] و اجازه
‌های مربوط به این کار را داشته باشید.",
"accmailtitle" => "کلمه‌ی عبور فرستاده شد.",
"accmailtext" => "کلمه‌ی عبور «$1» به «$2» فرستاده شد.",
"newarticle"	=> "(جدید)",
"newarticletext" =>
"شما پیوندی را دنبال کرده‌اید و به صفحه‌ای رسیده‌اید که هنوز وجود ندارد.
برای ایجاد صفحه، در مستطیل زیر شروع به تایپ کنید
(برای اطلاعات بیشتر به [[ویکی‌پدیا:راهنما|صفحه‌ی راهنما]] مراجعه کنید).
اگر اشتباهاً اینجا آمده‌اید، دکمه‌ی '''back''' مرورگرتان را بزنید.",
"anontalkpagetext" => "---- ''این صفحه‌ی بحث برای کاربر گمنامی است که هنوز حسابی درست نکرده است یا از آن استفاده نمی‌کند.
بنابراین برای شناسایی‌اش مجبوریم از [[نشانی IP]] عددی استفاده کنیم. چنین نشانی‌های IPای ممکن است توسط چندین کاربر به شکل
مشترک استفاده شود.
اگر شما کاربر گمنامی هستید و تصور می‌کنید اظهار نظرات نامربوط به شما صورت گرفته است،
لطفاً برای پیشگیری از اشتباه گرفته شدن با کاربران گمنام دیگر در آیند [[ویژه:Userlogin|حسابی ایجاد کنید یا به سیستم وارد شوید]].'' ",
"noarticletext" => "(این صفحه در حال حاضر متنی ندارد)",
"updated"		=> "(به‌روز شد)",
"note"			=> "<strong>نکته:</strong> ",
"previewnote"	=> "توجه کنید که این فقط پیش‌نمایش است، و ذخیره نشده است!",
"previewconflict" => "این پیش‌نمایش منعکس‌کننده‌ی متن ناحیه‌ی ویرایش متن بالایی است،
به شکلی که اگر بخواهید متن را ذخیره کنید نشان داده خواهد شد.",
"editing"		=> "در حال ویرایش $1",
"sectionedit"	=> " (قسمت)",
"commentedit"	=> " (یادداشت)",
"editconflict"	=> "تعارض ویرایشی: $1",
"explainconflict" => "از وقتی شما ویرایش این صفحه را آغاز کرده‌اید شخص دیگری آن را تغییر داده است.
ناحیه‌ی متنی بالایی شامل متن صفحه به شکل فعلی آن است.
تغییرات شما در ناحیه‌ی متنی پایینی نشان داده شده است.
شما باید تغییراتتان را با متن فعلی ترکیب کنید.
وقتی «ذخیره‌ی صفحه» را فشار دهید، <b>فقط</b> متن ناحیه‌ی متنی بالایی ذخیره خواهد شد.\n<p>",
"yourtext"		=> "متن شما",
"storedversion" => "نسخه‌ی ضبط‌شده",
"editingold"	=> "<strong>هشدار:
شما دارید نسخه‌ی قدیمی‌ای از این صفحه را ویرایش می‌کنید.
اگر ذخیره‌اش کنید، هر تغییری که پس از این نسخه انجام شده از بین خواهد رفت.</strong>\n",
"yourdiff"		=> "تفاوت‌ها",
# FIXME: This is inappropriate for third-party use!
"copyrightwarning" => "لطفاٌ توجه داشته باشید که فرض می‌شود کلیه‌ی مشارکت‌های شما با ویکی‌پدیا
تحت اجازه‌نامه‌ی مستندات آزاد گنو منتشر می‌شوند
(برای جزئیات بیشتر به $1 مراجعه کنید).
اگر نمی‌خواهد نوشته‌هایتان بیرحمانه ویرایش شده و به دلخواه توزیع شود،
اینجا نفرستیدشان.<br>
همینطور شما دارید به ما قول می‌دهید که خودتان این را نوشته‌اید، یا آن را از یک منبع آزاد با
مالکیت عمومی یا مشابه آن برداشته‌اید.
<strong>کارهای دارای حق انحصاری تکثیر (کپی‌رایت) را بی اجازه نفرستید!</strong>",
"longpagewarning" => "هشدار: این صفحه $1 کیلوبایت طول دارد؛
بعضی مرورگرها ممکن با ویرایش صفحات نزدیک به ۳۲ کیلوبایت یا طولانیتر از آن مشکلاتی داشته باشند.
لطفاً درباره‌ی شکستن این صفحه به قسمت‌های کوچکتر فکر کنید.",
"readonlywarning" => "هشدار: پایگاه داده برای نگهداری قفل شده است،
بنابراین نمی‌توانید ویرایش‌هایتان را همین الآن ذخیره کنید.
اگر می‌خواهید متن را در یک پرونده‌ی متنی ببرید و بچسبانید و برای آینده ذخیره‌اش کنید.",
"protectedpagewarning" => "هشدار: این صفحه قفل شده است تا فقط کاربران با امتیاز اپراتور سیستم بتوانند ویرایشش کنند.
مطمئن شوید که از 
<a href='/wiki/ویکی‌پدیا:توصیه‌های صفحات محافظت‌شده'>توصیه‌های صفحات محافظت‌شده</a> پیروی می‌کنید.",

# History pages
#
"revhistory"	=> "تاریخچه‌ی تغییرات",
"nohistory"		=> "این صفحه تاریخچه‌ی ویرایش ندارد.",
"revnotfound"	=> "نسخه یافت نشد",
"revnotfoundtext" => "نسخه‌ی قدیمی‌از از صفحه که درخواست کرده بودید یافت نشد.
لطفاً URLی را که برای دسترسی به این صفحه استفاده کرده‌اید بررسی کنید.\n",
"loadhist"		=> "در حال خواندن تاریخچه‌ی صفحه",
"currentrev"	=> "نسخه‌ی فعلی",
"revisionasof"	=> "نسخه‌ی $1",
"cur"			=> "فعلی",
"next"			=> "بعدی",
"last"			=> "آخرین",
"orig"			=> "اصلی",
"histlegend"	=> "شرح: (فعلی) = تفاوت با نسخه‌ی فعلی،
(آخرین) = تفاوت با نسخه‌ی قبلی، جز = ویرایش جزئی",

# Diffs
#
"difference"	=> "(تفاوت بین نسخه‌ها)",
"loadingrev"	=> "در حال خواندن نسخه برای تفاوت گرفتن",
"lineno"		=> "سطر $1:",
"editcurrent"	=> "ویرایش نسخه‌ی فعلی این صفحه",

# Search results
#
"searchresults" => "نتایج جستجو",
"searchhelppage" => "ویکی‌پدیا:جستجو کردن",
"searchingwikipedia" => "جستجوی ویکی‌پدیا",
"searchresulttext" => "برای اطلاعات بیشتر درباره‌ی جستجوی ویکی‌پدیا، به $1 مراجعه کنید.",
"searchquery"	=> "برای پرس‌وجوی «$1»",
"badquery"		=> "پرس‌وجوی جستجویی بدشکل",
"badquerytext"	=> "نتوانستیم پرس‌وجوی شما را پردازش کنیم.
این مشکل احتمالاً به این دلیل است که سعی کرده‌اید به دنبال کلمه‌ای کوتاهتر از سه حرف
بگردید، که هنوز پشتیبانی نمی‌شود.
همین‌طور ممکن است عبارت را اشتباه وارد کرده باشید، مثلاً «ماهی و و پولک».
لطفاً یک پرس‌وجوی دیگر را امتحان کنید.",
"matchtotals"	=> "پرس‌وجوی «$1» متناظر $2 عنوان مقاله
و $3 متن مقاله است.",
"nogomatch" => "صفحه‌ی با دقیقاً این عنوان وجود ندارد، تلاش برای جستجوی کل متن.",
"titlematches"	=> "Article title matches",
"notitlematches" => "عنوان هیچ مقاله‌ای نمی‌خورد",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "متن هیچ مقاله‌ای نمی‌خورد",
"prevn"			=> "$1تای قبلی",
"nextn"			=> "$1تای بعدی",
"viewprevnext"	=> "نمایش ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>نکته</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "جستجو",
"powersearchtext" => "
جستجو در فضاهای نام :<br>
$1<br>
$2 تغییرمسیرها فهرست شوند &nbsp; جستجو برای $3 $9",
"searchdisabled" => "<p>شرمنده! جستجوی کل متن موقتاً از کار انداخته شده, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>

<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"گوگل\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"جستجوی گوگل\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(اصلی)",

# Preferences page
#
"preferences"	=> "ترجیحات",
"prefsnologin" => "به سیستم وارد نشده‌اید",
"prefsnologintext"	=> "برای تنظیم ترجیحات کاربر باید <a href=\"" .
  wfLocalUrl( "ویژه:Userlogin" ) . "\">به سیستم وارد شوید</a>.",
"prefslogintext" => "شما با نام «$1» وارد سیستم شده‌اید.
شماره‌ی شناسایی داخلی شما $2 است.

برای راهنمایی در فهمیدن گزینه‌ها، به [[ویکی‌پدیا:راهنمای ترجیحات کاربر]] مراجعه کنید.",
"prefsreset"	=> "ترجیحات از حافظه میزان شد.",
"qbsettings"	=> "تنظیمات نوار سریع",
"changepassword" => "تغییر کلمه‌ی عبور",
"skin"			=> "پوسته",
"math"			=> "نمایش ریاضیات",
"dateformat"	=> "قالب تاریخ",
"math_failure"		=> "شکست در تجزیه",
"math_unknown_error"	=> "خطای ناشناخته",
"math_unknown_function"	=> "تابع ناشناخته‌ی ",
"math_lexing_error"	=> "خطای lexing",
"math_syntax_error"	=> "خطای نحوی",
"math_image_error"	=> "تبدیل به PNG شکست خورد",
"saveprefs"		=> "ذخیره‌ی ترجیحات",
"resetprefs"	=> "صفر کردن ترجیحات",
"oldpassword"	=> "کلمه‌ی عبور قدیمی",
"newpassword"	=> "کلمه‌ی عبور جدید",
"retypenew"		=> "کلمه‌ی عبور جدید را دوباره وارد کنید",
"textboxsize"	=> "ابعاد جعبه‌ی متن",
"rows"			=> "تعداد سطرها",
"columns"		=> "تعداد ستون‌ها",
"searchresultshead" => "تنظیمات نتیجه‌ی جستجو",
"resultsperpage" => "تعداد نتایج در هر صفحه",
"contextlines"	=> "تعداد سطرها در هر نتیجه",
"contextchars"	=> "تعداد نویسه‌های اطراف در سطر",
"stubthreshold" => "آستانه‌ی نمایش ناقص‌ها",
"recentchangescount" => "تعداد عناوین در تغییرات اخیر",
"savedprefs"	=> "ترجیحات شما ذخیره شد.",
"timezonetext"	=> "تفاوت تعداد ساعت زمان محلی‌تان با زمان خادم (وقت گرینیچ) را وارد کنید.",
"localtime"	=> "نمایش زمان محلی",
"timezoneoffset" => "تفاوت",
"servertime"	=> "زمان فعلی خادم",
"guesstimezone" => "از مرورگر گرفته شود",
"emailflag"		=> "از کار انداختن پست الکترونیکی از کاربران دیگر",
"defaultns"		=> "به طور پیشفرض در این فضاهای نام جستجو شود:",

# Recent changes
#
"changes" => "تغییرات",
"recentchanges" => "تغییرات اخیر",
"recentchangestext" => "آخرین تغییرات ویکی را در این صفحه تعقیب کنید.",
"rcloaderr"		=> "در حال خواندن تغییرات اخیر",
"rcnote"		=> "در زیر آخرین <strong>$1</strong> تغییر در <strong>$2</strong> روز اخیر آمده است.",
"rcnotefrom"	=> "در زیر تغییرات از تاریخ <b>$2</b> آمده‌اند (تا <b>$1</b> مورد نشان داده می‌شود).",
"rclistfrom"	=> "نمایش تغییرات جدید با شروع از $1",
# "rclinks"		=> "نمایش آخرین $1 تغییر در $2 ساعت اخیر / $3 روز اخیر",
# "rclinks"		=> "نمایش آخرین $1 تغییر در $2 روز اخیر.",
"showhideminor"         => "ویرایش‌های جزئی $1",
"rclinks"		=> "نمایش آخرین $1 تغییر در $2 روز اخیر؛ $3",
"rchide"		=> "به شکل $4؛ $1 ویرایش جزئی؛ $2 فضای نام ثانویه؛ $3 ویرایش چندگانه.",
"rcliu"			=> "؛ $1 ویرایش از کاربران وارد شده به سیستم",
"diff"			=> "تفاوت",
"hist"			=> "تاریخچه",
"hide"			=> "مخفی شود",
"show"			=> "نمایش یابد",
"tableform"		=> "جدول",
"listform"		=> "فهرست",
"nchanges"		=> "$1 تغییر",
"minoreditletter" => "جز",
"newpageletter" => "جد",

# Upload
#
"upload"		=> "بار کردن پرونده",
"uploadbtn"		=> "پرونده بار شود",
"uploadlink"	=> "بار کردن تصاویر",
"reupload"		=> "بار کردن مجدد",
"reuploaddesc"	=> "بازگشت به فرم بار کردن",
"uploadnologin" => "به سیستم وارد نشده‌اید",
"uploadnologintext"	=> "برای بار کردن پرونده‌ها باید <a href=\"" .
  wfLocalUrl( "ویژه:Userlogin" ) . "\">وارد سیستم شوید</a>.",
"uploadfile"	=> "بار کردن تصاویر، اصوات، اسناد، و غیره",
"uploaderror"	=> "خطا در بار کردن",
"uploadtext"	=> "<strong>ایست!</strong> قبل از این که چیزی اینجا بار کنید،
مطمئن شوید که 
<a href=\"" .
wfLocalUrlE( "ویکی‌پدیا:سیاست_استفاده_از_تصاویر" ). "\">سیاست استفاده از تصاویر</a>
را خوانده‌اید و از آن پیروی می‌کنید.
<p>If a file with the name you are specifying already
exists on the wiki, it'll be replaced without warning.
So unless you mean to update a file, it's a good idea
to first check if such a file exists.
<p>To view or search previously uploaded images,
go to the <a href=\"" . wfLocalUrlE( "ویژه:Imagelist" ) .
"\">list of uploaded images</a>.
Uploads and deletions are logged on the <a href=\"" .
wfLocalUrlE( "$wgMetaNamespace:Upload_log" ) . "\">upload log</a>.
<p>Use the form below to upload new image files for use in
illustrating your articles.
On most browsers, you will see a \"Browse...\" button, which will
bring up your operating system's standard file open dialog.
Choosing a file will fill the name of that file into the text
field next to the button.
You must also check the box affirming that you are not
violating any copyrights by uploading the file.
Press the \"Upload\" button to finish the upload.
This may take some time if you have a slow internet connection.
<p>The preferred formats are JPEG for photographic images, PNG
for drawings and other iconic images, and OGG for sounds.
Please name your files descriptively to avoid confusion.
To include the image in an article, use a link in the form
<b>[[image:file.jpg]]</b> or <b>[[image:file.png|alt text]]</b>
or <b>[[media:file.ogg]]</b> for sounds.
<p>Please note that as with wiki pages, others may edit or
delete your uploads if they think it serves the encyclopedia, and
you may be blocked from uploading if you abuse the system.",

"uploadlog"		=> "سیاهه‌ی بارکردن‌ها",
"uploadlogpage" => "سیاهه‌ی_بارکردن‌ها",
"uploadlogpagetext" => "فهرست زیر فهرستی از آخرین بارکردن‌های پرونده‌های است.
همه‌ی زمان‌های نشان‌داده‌شده زمان خادم هستند (وقت گرینیچ).
<ul>
</ul>
",
"filename"		=> "نام پرونده",
"filedesc"		=> "خلاصه",
"filestatus" => "وضعیت حق تکثیر",
"filesource" => "منبع",
"affirmation"	=> "من تأیید می‌کنم که دارنده‌ی حق انحصاری تکثیر این پرونده موافق است که تحت شرایط $1 منتشر شود.",
"copyrightpage" => "ویکی‌پدیا:حق_تکثیر",
"copyrightpagename" => "حق تکثیر ویکی‌پدیا",
"uploadedfiles"	=> "پرونده‌های بارشده",
"noaffirmation" => "شما باید تأیید کنید که این بار کردن شما هیچ حق انحصاری تکثیری را زیر پا نمی‌گذارد.",
"ignorewarning"	=> "هشدار نادیده گرفته شود و پرونده به‌هرحال ذخیره شود.",
"minlength"		=> "نام پرونده باید حداقل سه‌حرفی باشد.",
"badfilename"	=> "نام پرونده به «$1» تغییر کرد.",
"badfiletype"	=> "قالب پرونده‌ای «‎.$1» برای پرونده‌های تصویری توصیه نمی‌شود.",
"largefile"		=> "توصیه می‌شود که اندازه‌ی تصاویر از ۱۰۰ کیلوبایت بیشتر نباشد.",
"successfulupload" => "بار کردن با موفقیت انجام شد",
"fileuploaded"	=> "پرونده‌ی «$1» با موفقیت بار شد.
لطفاً این پیوند را تعقیب کنید: ($2) تا صفحه‌ی توصیف و اطلاعات در مورد
پرونده را، از قبیل این که از کجا آمده است، چه کسی و در چه زمانی آن را ایجاد کرده است،
و هر چیز دیگری که ممکن است در مورد آن بدانید، پر کنید.",
"uploadwarning" => "هشدار بار کردن",
"savefile"		=> "ذخیره‌ی پرونده",
"uploadedimage" => "«$1» بار شد",
"uploaddisabled" => "شرمنده، بار کردن از کار افتاده است.",
				       
# Image list
#
"imagelist"		=> "فهرست تصاویر",
"imagelisttext"	=> "در زیر فهرست $1 تصویری که $2 مرتب شده است آمده است.",
"getimagelist"	=> "در حال اخذ فهرست تصاویر",
"ilshowmatch"	=> "نمایش همه‌ی تصاویر با نام نظیر",
"ilsubmit"		=> "جستجو",
"showlast"		=> "نمایش آخرین $1 تصویر مرتب‌شده $2.",
"all"			=> "همه",
"byname"		=> "از روی نام",
"bydate"		=> "از روی تاریخ",
"bysize"		=> "از روی اندازه",
"imgdelete"		=> "حذف",
"imgdesc"		=> "توصیف",
"imglegend"		=> "شرح: (توصیف) = نمایش/ویرایش توصیف تصویر.",
"imghistory"	=> "تاریخچه‌ی تصویر",
"revertimg"		=> "برگرد",
"deleteimg"		=> "حذف",
"deleteimgcompletely"		=> "حذف",
"imghistlegend" => "شرح: (فعلی) = این تصویر فعلی است، (حذف) = این
نسخه‌ی قدیمی حذف شود، (برگرد) = برگرداندن به این نسخه‌ی قدیمی.
<br><i>برای دیدن تصویر بار شده در تاریخ مشخص، روی تاریخ کلیک کنید</i>.",
"imagelinks"	=> "پیوند‌های تصاویر",
"linkstoimage"	=> "این صفحات به این تصویر پیوند دارند:",
"nolinkstoimage" => "هیچ صفحه‌ای به این تصویر پیوند ندارد.",

# Statistics
#
"statistics"	=> "آمار",
"sitestats"		=> "آمار وبگاه",
"userstats"		=> "آمار کاربران",
"sitestatstext" => "کلاً <b>$1</b> صفحه در پایگاه داده هست.
این شامل صفحات «بحث»، صفحات درباره‌ی ویکی‌پدیا، صفحات «ناقص» کوچک،
تغییرمسیرها، و صفحات دیگری می‌شود که احتمالاً مقاله به حساب نمی‌آیند.
فارق از این‌ها، <b>$2</b> صفحه هست که احتمالاً مقاله‌ی معقول هستند.<p>
از زمانی که نرم‌افزار ارتقا یافته (۲۰ ژوئیه‌ی ۲۰۰۲)، کلاً <b>$3</b> بازدید از صفحات،
و <b>$4</b> ویرایش صفحات صورت گرفته است.
این می‌شود به طور متوسط <b>$5</b> ویرایش برای هر صفحه، و <b>$6</b> بازدید به‌ازای هر ویرایش.",
"userstatstext" => "تعداد <b>$1</b> کاربر ثبت‌شده وجود دارد.
تعداد <b>$2</b> از این کاربران مدیرند (به $3 مراجعه شود).",

# Maintenance Page
#
"maintenance"		=> "صفحه‌ی نگهداری",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "بازگشت به صفحه‌ی نگهداری",
"disambiguations"	=> "صفحات رفع ابهام",
"disambiguationspage"	=> "ویکی‌پدیا:پیوند به صفحات رفع ابهام",
"disambiguationstext"	=> "مقاله‌های زیر به یک <i>صفحه‌ی رفع ابهام</i> پیوند دارند. به جای این، این صفحات باید به
They should link to the appropriate topic instead.<br>A page is treated as dismbiguation if it is linked from $1.<br>Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "تغییرمسیرهای دوتایی",
"doubleredirectstext"	=> "<b>توجه:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br>\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "تغییرمسیرهای خراب",
"brokenredirectstext"	=> "تغییرمسیرهای زیر به یک صفحه‌ی ناموجود پیوند دارند.",
"selflinks"		=> "صفحات با پیوند به خود",
"selflinkstext"		=> "این صفحات به خودشان پیوند دارند، که نباید داشته باشند.",
"mispeelings"           => "صفحات با غلط املایی",
"mispeelingstext"               => "صفحات زیر یک غلط املایی معمول دارند، که در $1 آمده است. ممکن است املای درست (به این شکل) آمده باشد.",
"mispeelingspage"       => "فهرست غلط‌های املایی معمول",
"missinglanguagelinks"  => "پیوندهای زبانی گم‌شده",
"missinglanguagelinksbutton"    => "یافتن پیوندهای زبانی گم‌شده برای",
"missinglanguagelinkstext"      => "این مقاله‌ها به همتایشان در $1 پیوند <i>ندارند</i>. Redirects و زیرصفحه‌ها نشان داده <i>نشده‌اند</i>.",


# Miscellaneous special pages
#
"orphans"		=> "صفحات یتیم",
"lonelypages"	=> "صفحات یتیم",
"unusedimages"	=> "تصاویر بلااستفاده",
"popularpages"	=> "صفحات محبوب",
"nviews"		=> "$1 نمایش",
"wantedpages"	=> "صفحات مورد نیاز",
"nlinks"		=> "$1 پیوند",
"allpages"		=> "همه‌ی صفحات",
"randompage"	=> "صفحه‌ی تصادفی",
"shortpages"	=> "صفحات کوتاه",
"longpages"		=> "صفحات بلند",
"deadendpages"  => "صفحات بن‌بست",				       
"listusers"		=> "فهرست کاربران",
"specialpages"	=> "صفحات ویژه",
"spheading"		=> "صفحات ویژه‌ی همه‌ی کاربران",
"sysopspheading" => "فقط برای استفاده‌ی اپراتور سیستم",
"developerspheading" => "فقط برای استفاده‌ی برنامه‌نویس",
"protectpage"	=> "محافظت از صفحه",
"recentchangeslinked" => "تغییرات مرتبط",
"rclsub"		=> "(به صفحات پیونددار از «$1»)",
"debug"			=> "اشکال‌زدایی",
"newpages"		=> "صفحات جدید",
"ancientpages"		=> "قدیمی‌ترین مقاله‌ها",
"intl"		=> "پیوندهای بین زبانی",
"movethispage"	=> "انتقال این صفحه",
"unusedimagestext" => "<p>لطفاٌ توجه کنید که وبگاه‌های دیگر از جمله ویکی‌پدیاهای بین‌المللی
ممکن است با URL مستقیم به تصاویر پیوند داشته باشند، و نتیجتاً با وجود استفاده‌ی فعال
اینجا فهرست شده باشند.",
"booksources"	=> "منابع کتاب",
# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
"booksourcetext" => "در زیر فهرستی از پیوندها به وبگاه‌های دیگری که کتاب‌های نو و دست دوم می‌فروشند آمده است،
و ممکن است اطلاعات بیشتری نیز درباره‌ی کتاب‌هایی که دنبالشان می‌گردید داشته باشند.
ویکی‌پدیا وابستگی یا ارتباطی با هیچ یک از این کسب‌وکارها ندارد، و این فهرست
نباید به معنی تأیید یا حمایت تعبیر شود.",
"alphaindexline" => "$1 تا $2",

# Email this user
#
"mailnologin"	=> "نشانی فرستنده‌ای نیست",
"mailnologintext" => "برای فرستادن پست الکترونیکی به کاربران دیگر باید <a href=\"" .
  wfLocalUrl( "ویژه:Userlogin" ) . "\">به سیستم وارد شوید</a>
و نشانی پست الکترونیکی معتبری در <a href=\"" .
  wfLocalUrl( "ویژه:ترجیحات" ) . "\">ترجیحات</a>
خود داشته باشید.",
"emailuser"		=> "پست الکترونیکی به این کاربر",
"emailpage"		=> "پست الکترونیکی به کاربر",
"emailpagetext"	=> "اگر این کاربر نشانی پست الکترونیکی معتبری در ترجیحات کاربریش وارد کرده
باشد، فرم زیر یک پیغام می‌فرستد.
نشانی پست الکترونیکی‌ای که در ترجیحات کاربریتان وارد کرده‌اید در نشانی فرستنده (From) نامه
خواهد آمد، تا گیرنده بتواند پاسخ دهد.",
"noemailtitle"	=> "نشانی پست‌الکترونیک موجود نیست",
"noemailtext"	=> "این کاربر نشانی پست الکترونیکی معتبری مشخص نکرده است،
یا تصمیم گرفته از کاربران دیگر پست الکترونیکی دریافت نکند.",
"emailfrom"		=> "از",
"emailto"		=> "به",
"emailsubject"	=> "عنوان",
"emailmessage"	=> "پیغام",
"emailsend"		=> "فرستاده شود",
"emailsent"		=> "پست الکترونیکی فرستاده شد",
"emailsenttext" => "پیغام پست الکترونیکی شما فرستاده شد.",

# Watchlist
#
"watchlist"			=> "فهرست تعقیبات من",
"watchlistsub"		=> "(برای کاربر «$1»)",
"nowatchlist"		=> "در فهرست تعقیبات شما هیچ موردی نیست.",
"watchnologin"		=> "به سیستم وارد نشده‌اید",
"watchnologintext"	=> "برای تغییر فهرست تعقیباتتان باید <a href=\"" .
  wfLocalUrl( "ویژه:Userlogin" ) . "\">به سیستم وارد شوید</a>.",
"addedwatch"		=> "به فهرست تعقیبات اضافه شود",
"addedwatchtext"	=> "صفحه‌ی «$1» به <a href=\"" .
  wfLocalUrl( "ویژه:Watchlist" ) . "\">فهرست تعقیبات</a> شما اضافه شد.
تغییرات این صفحه و صفحه‌ی بحث متناظرش در آینده در اینجا فهرست خواهد شد. به‌علاوه، این صفحه، برای واضحتر دیده شدن در
<a href=\"" .
  wfLocalUrl( "ویژه:Recentchanges" ) . "\">فهرست تغییرات اخیر</a> to
به شکل<b>سیاه</b> خواهد آمد.</p>

<p>اگر بعداً می‌خواستید این صفحه از فهرست تعقیباتتان برداشته شود، روی «تعقیب متوقف شود» در نوار حاشیه کلیک کنید.",
"removedwatch"		=> "از فهرست تعقیبات برداشته شد",
"removedwatchtext" 	=> "صفحه‌ی «$1» از فهرست تعقیبات شما برداشته شد",
"watchthispage"		=> "تعقیب این صفحه",
"unwatchthispage" 	=> "توقف تعقیب",
"notanarticle"		=> "مقاله نیست",
"watchnochange" 	=> "هیچ یک از موارد در حال تعقیب شما در دوره‌ی زمانی نمایش‌یافته ویرایش نشده است.",
"watchdetails"		=> "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>نمایش و ویرایش فهرست کامل</a>.)",
"watchmethod-recent"=> "بررسی ویرایش‌های اخیر برای صفحات در حال تعقیب",
"watchmethod-list"	=> "بررسی صفحات در حال تعقیب برای ویرایش‌های اخیر",
"removechecked" 	=> "برداشتن موارد تیک‌خورده از فهرست تعقیبات",
"watchlistcontains" => "فهرست تعقیبات شما حاوی $1 صفحه است.",
"watcheditlist"		=> "در اینجا فهرست الفبایی‌ای از صفحات در تعقیب شما می‌آید.
در جعبه‌ی صفحاتی که می‌خواهید از فهرست تعقیباتتان حذف شود تیک بزنید و روی دکمه‌ی «برداشتن موارد» در پایین
صفحه کلیک کنید.",
"removingchecked" 	=> "در حال برداشتن موارد درخواستی از فهرست تعقیبات...",
"couldntremove" 	=> "نمی‌توان مورد «$1» را حذف کرد...",
"iteminvalidname" 	=> "مشکل با مورد «$1»، نام نامعتبر است...",
"wlnote" 			=> "در زیر آخرین $1 تغییر در $2 ساعت آخر آمده است.",
"wlshowlast" 		=> "نمایش آخرین $1 ساعت $2 روز $3",
"wlsaved"			=> "این نسخه‌ی ذخیره‌شده‌ای از فهرست تعقیبات شما است.",


# Delete/protect/revert
#
"deletepage"	=> "حذف صفحه",
"confirm"		=> "تأیید",
"excontent" => "content was:",
"exbeforeblank" => "content before blanking was:",
"exblank" => "صفحه خالی بود",
"confirmdelete" => "تأیید حذف",
"deletesub"		=> "(در حال حذف «$1»)",
"historywarning" => "هشدار: صفحه‌ای که دارید حذف می‌کند تاریخچه‌ای دارد: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[$wgMetaNamespace:Policy]].",
"confirmcheck"	=> "بله، جداً می‌خواهم این را حذف کنم.",
"actioncomplete" => "عمل انجام شد",
"deletedtext"	=> "«$1» حذف شده است.
برای سابقه‌ی حذف‌های اخیر به $2 مراجعه کنید.",
"deletedarticle" => "«$1» حذف شد",
"dellogpage"	=> "سیاهه‌ی_حذف",
"dellogpagetext" => "فهرست زیر فهرستی از اخیرترین حذف‌ها است.
همه‌ی زمان‌های نشان‌داده‌شده زمان خادم (وقت گرینیچ) است.
<ul>
</ul>
",
"deletionlog"	=> "سیاهه‌ی حذف",
"reverted"		=> "به نسخه‌ی قدیمی‌تر برگردانده شد",
"deletecomment"	=> "دلیل حذف",
"imagereverted" => "برگرداندن به نسخه‌ی قدیمی‌تر با موفقیت انجام شد.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"rollbackfailed" => "Rollback failed",
"cantrollback"	=> "نمی‌توان ویرایش را برگرداند؛ آخرین مشارکت‌کننده تنها مؤلف این مقاله است.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already. 

آخرین ویرایش توسط [[کاربر:$3|$3]] ([[بحث کاربر:$3|بحث]]). ",
#   only shown if there is an edit comment
"editcomment" => "توضیح ویرایش این بود: \"<i>$1</i>\".", 
"revertpage"	=> "ویرایش $2 برگردانده شد، به آخرین تغییری که  $1 انجام داده است",
"protectlogpage" => "سیاهه‌ی_محافظت",
"protectlogtext" => "در زیر فهرست قفل کردن‌ها/ازقفل‌درآوردن‌های صفحات آمده است.
برای اطلاعات بیشتر به [[ویکی‌پدیا:صفحه‌ی محافظت‌شده]] مراجعه کنید.",
"protectedarticle" => "[[$1]] محافظت شد",
"unprotectedarticle" => "[[$1]] از محافظت در آمد",

# Undelete
"undelete" => "احیای صفحه‌ی حذف شده",
"undeletepage" => "نمایش و احیای صفحات حذف شده",
"undeletepagetext" => "صفحات زیر حذف شده‌اند ولی هنوز در بایگانی هستند و می‌توانند احیا شوند.
این آرشیو ممکن است هر چند وقت تمیز شود.",
"undeletearticle" => "احیای مقاله‌ی حذف‌شده",
"undeleterevisions" => "$1 نسخه بایگانی شده است",
"undeletehistory" => "اگر این صفحه را احیا کنید، همه‌ی نسخه‌های آن در تاریخچه احیا خواهند شد.
اگر صفحه‌ی جدیدی با نام یکسان از زمان حذف ایجاد شده باشد، نسخه‌های احیاشده در تاریخچه‌ی قبلی خواهند آمد،
و نسخه‌ی فعلی صفحه‌ی زنده به طور خودکار جایگزین نخواهد شد.",
"undeleterevision" => "حذف نسخه‌ی به تاریخ $1",
"undeletebtn" => "احیا شود!",
"undeletedarticle" => "«$1» احیا شد",
"undeletedtext"   => "مقاله‌ی [[$1]] با موفقیت احیا شد.
برای سوابق حذف‌ها و احیاهای اخیر به [[ویکی‌پدیا:سیاهه‌ی_حذف]] مراجعه کنید.",

# Contributions
#
"contributions"	=> "مشارکت‌های کاربر",
"mycontris" => "مشارکت‌های من",
"contribsub"	=> "برای $1",
"nocontribs"	=> "هیچ تغییری نظیر این مشخصات یافت نشد.",
"ucnote"		=> "در زیر آخرین <b>$1</b> تغییر این کاربر در <b>$2</b> روز اخیر می‌آید.",
"uclinks"		=> "نمایش آخرین $1 تغییر؛ نمایش $2 روز اخیر.",
"uctop"		=> " (بالا)" ,

# What links here
#
"whatlinkshere"	=> "آنچه به اینجا پیوند دارد",
"notargettitle" => "مقصدی نیست",
"notargettext"	=> "شما صفحه‌ی یا کاربر مقصدی برای انجام این عمل روی آن مشخص نکرده‌اید.",
"linklistsub"	=> "(فهرست پیوندها)",
"linkshere"		=> "صفحات زیر به اینجا پیوند دارند:",
"nolinkshere"	=> "هیچ صفحه‌ای به اینجا پیوند ندارد.",
"isredirect"	=> "صفحه‌ی تغییر مسیر",

# Block/unblock IP
#
"blockip"		=> "بستن کاربر",
"blockiptext"	=> "از فرم زیر برای بستن دسترسی نوشتن از یک نشانی IP یا
نام کاربری مشخص استفاده کنید.
این کار فقط فقط باید برای جلوگیری از خرابکاری انجام شود، و بر اساس
[[ویکی‌پدیا:خط مشی|خط مشی].
دلیل مشخص این کار را در زیر ذکر کنید (مثلاً با ذکر صفحات به‌خصوصی که تخریب شده‌اند).",
"ipaddress"		=> "نشانی IP/نام کاربر",
"ipbreason"		=> "دلیل",
"ipbsubmit"		=> "این کاربر بسته شود",
"badipaddress"	=> "کاربری با این نام وجود ندارد.",
"noblockreason" => "شما باید دلیلی برای بستن مشخص کنید.",
"blockipsuccesssub" => "بستن با موفقیت انجام شد",
"blockipsuccesstext" => "«$1» بسته شده است.
<br>برای بررسی بسته‌شدن‌ها، به [[ویژه:فهرستIPهای‌بسته|فهرست IPهای بسته]] مراجعه کنید.",
"unblockip"		=> "باز کردن کاربر",
"unblockiptext"	=> "برای باز گرداندن دسترسی نوشتن به یک نشانی IP یا نام کاربری بسته‌شده
از فرم زیر استفاده کنید.",
"ipusubmit"		=> "باز کردن این نشانی",
"ipusuccess"	=> "«$1» باز شد",
"ipblocklist"	=> "فهرست نشانی‌های IP و نام‌های کاربری بسته‌شده",
"blocklistline"	=> "$1، $2 بست $3 را",
"blocklink"		=> "بسته شود",
"unblocklink"	=> "باز شود",
"contribslink"	=> "مشارکت‌ها",
"autoblocker"	=> "به طور خودکار بسته شد چون IP شما و «$1» یکی است. دلیل «$2».",
"blocklogpage"	=> "سیاهه‌ی_بسته‌شدن‌ها",
"blocklogentry"	=> '«$1» بسته شد',
"blocklogtext"	=> "این سیاهه‌ای از اعمال بستن و باز کردن کاربرها است. نشانی‌های IPی که به طور
خودکار بسته شده‌اند فهرست نشده‌اند. برای فهرست محرومیت‌ها و بسته‌شدن‌های عملیاتی در لحظه‌ی حاضر،
به [[Special:Ipblocklist|فهرست IPهای بسته]] مراجعه کنید.",
"unblocklogentry"	=> '«$1» باز شد',

# Developer tools
#
"lockdb"		=> "قفل کردن پایگاه داده",
"unlockdb"		=> "از قفل در آوردن پایگاه داده",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "بله، من جداً می‌خواهم پایگاه داده را قفل کنم.",
"unlockconfirm"	=> "بله، من جداً می‌خواهم پایگاه داده را از قفل در آورم.",
"lockbtn"		=> "قفل کردن پایگاه داده",
"unlockbtn"		=> "از قفل درآوردن پایگاه داده",
"locknoconfirm" => "شما در جعبه‌ی تأیید تیک نزدید",
"lockdbsuccesssub" => "قفل کردن پایگاه داده با موفقیت انجام شد",
"unlockdbsuccesssub" => "قفل پایگاه داده برداشته شد",
"lockdbsuccesstext" => "پایگاه داده قفل شد.
<br>فراموش نکنید که پس از اتمام نگهداری قفل را بردارید.",
"unlockdbsuccesstext" => "پایگاه داده از قفل در آمد.",

# SQL query
#
"asksql"		=> "پرس‌وجوی SQL",
"asksqltext"	=> "برای پرس‌وجوی مستقیم پایگاه داده از فرم زیر استفاده کنید.
برای محدود کردن رشته‌های دقیق از نقل قول تکی ('مثل این') استفاده کنید.
این پرس‌وجوها معمولاً بار قابل ملاحظه‌ای به خادم اضافه می‌کند، پس از این توانایی با صرفه‌جویی استفاده کنید.",
"sqlislogged"	=> "لطفاً توجه کنید که همه‌ی پرس‌وجوها ثبت می‌شوند.",
"sqlquery"		=> "ورود پرس‌وجو",
"querybtn"		=> "فرستادن پرس‌وجو",
"selectonly"	=> "فقط پرس‌وجوهای فقط‌خواندنی مجازند.",
"querysuccessful" => "پرس‌وجو با موفقیت انجام شد",

# Move page
#
"movepage"		=> "انتقال صفحه",
"movepagetext"	=> "با استفاده از فرم زیر نام صفحه تغییر خواهد کرد، و تمام تاریخچه‌اش به
نام جدید منتقل خواهد شد.
عنوان قدیمی تبدیل به یک صفحه‌ی تغییر مسیر به عنوان جدید خواهد شد.
پیوندهای به عنوان صفحه‌ی قدیمی تغییر نخواهند کرد؛ حتماً تغییرمسیرهای دوتایی یا خراب را
[[ویژه:نگهداری|بررسی کنید]].
شما مسئول اطمینان از این هستید که پیوندها هنوز به همان‌جایی که قرار است بروند.

توجه کنید که اگر از قبل صفحه‌ای در عنوان جدید وجود داشته باشد صفحه منتقل '''نخواهد شد'''، مگر
این که صفحه خالی یا تغییر مسیر باشد و تاریخچه‌ی ویرایشی نداشته باشد.
این یعنی اگر اشتباه کردید صفحه را به همان جایی که از آن منتقل شده بود برگردانید،
و این که نمی‌توانید روی صفحات موجود بنویسید.

<b>هشدار!</b>
این کار ممکن است تغییر اساسی و غیرمنتظره‌ای برای صفحات محبوب باشد؛
لطفاً مطمئن شوید که قبل از ادامه دادن عواقب این کار را درک می‌کنید.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "انتقال صفحه",
"movenologin"	=> "به سیستم وارد نشده‌اید",
"movenologintext" => "برای انتقال صفحات باید کاربر ثبت‌شده بوده و 
<a href=\"" .
  wfLocalUrl( "ویژه:Userlogin" ) . "\">به سیستم وارد شوید</a>.",
"newtitle"		=> "به عنوان جدید",
"movepagebtn"	=> "صفحه منتقل شود",
"pagemovedsub"	=> "انتقال با موفقیت انجام شد",
"pagemovedtext" => "صفحه‌ی «[[$1]]» به «[[$2]]» منتقل شد.",
"articleexists" => "صفحه‌ای با این نام از قبل وجود دارد، یا نامی که انتخاب کرده‌اید معتبر نیست.
لطفاً نام دیگری انتخاب کنید.",
"talkexists"	=> "صفحه با موفقیت منتقل شد، ولی صفحه‌ی بحث را، به این دلیل که صفحه‌ی بحثی در عنوان جدید
وجود دارد، نمی‌توان منتقل کرد. لطفاً آنها را دستی ترکیب کنید.",
"movedto"		=> "منتقل شد به",
"movetalk"		=> "صفحه‌ی «بحث» هم، در صورت لزوم، منتقل شود.",
"talkpagemoved" => "صفحه‌ی بحث متناظر نیز منتقل شد.",
"talkpagenotmoved" => "صفحه‌ی بحث متناظر منتقل <strong>نشد</strong>.",
"1movedto2"		=> "$1 به $2 منتقل شد",

# Export

"export"		=> "صدور صفحات",
"exporttext"	=> "شما می‌توانید متن و تاریخچه‌ی ویرایش یک صفحه‌ی مشخص یا مجموعه‌ای از صفحات را به شکل پوشیده در XML صادر کنید؛
این اطلاعات را می‌توان وارد ویکی دیگری که نرم‌افزار مدیاویکی اجرا می‌کند کرد، تبدیل کرد، یا برای سرگرمی شخصی نگه داشت.",
"exportcuronly"	=> "فقط نسخه‌ی فعلی بیاید، نه کل تاریخچه",

# Namespace 8 related

"allmessages"	=> "همه‌ی پیغام‌ها",
"allmessagestext"	=> "این فهرستی از همه‌ی پیغام‌های موجود در فضای نام مدیاویکی: است",

# Thumbnails

"thumbnail-more"      => "بزرگ شود"

);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageFa extends LanguageUtf8 {

	function getDefaultUserOptions () {
                $opt = Language::getDefaultUserOptions();
		$opt["quickbar"]=2;
		$opt["underline"]=0;
                return $opt;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFa;
		return $wgNamespaceNamesFa;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesFa;
		return $wgNamespaceNamesFa[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFa;

		foreach ( $wgNamespaceNamesFa as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function specialPage( $name ) {
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFa;
		return $wgQuickbarSettingsFa;
	}

	function getSkinNames() {
		global $wgSkinNamesFa;
		return $wgSkinNamesFa;
	}

	function getMathNames() {
		global $wgMathNamesFa;
		return $wgMathNamesFa;
	}
	
	function getUserToggles() {
		global $wgUserTogglesFa;
		return $wgUserTogglesFa;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesFa;
		return $wgMonthNamesFa[$key-1];
	}
	
	function getMonthAbbreviation( $key )
	{
		/* Persian doesn't use abbreviations for Gregorian month names */
		return $this->getMonthName( $key );
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesFa;
		return $wgWeekdayNamesFa[$key-1];
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFa;
		return $wgValidSpecialPagesFa;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFa;
		return $wgSysopSpecialPagesFa;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFa;
		return $wgDeveloperSpecialPagesFa;
	}

        function getMessage( $key )
        {
                global $wgAllMessagesFa;
                if(array_key_exists($key, $wgAllMessagesFa))
                        return $wgAllMessagesFa[$key];
                else
                        return Language::getMessage($key);
	}
	
	# For right-to-left language support
	function isRTL() { return true; }
}
?>
