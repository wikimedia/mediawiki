<?php
#--------------------------------------------------------------------------
# Translated from English by Varakorn Ungvichian
# แปลงจากภาษาอังกฤษโดย นาย วรากร อึ้งวิเชียร
#--------------------------------------------------------------------------

/* private */ $wgNamespaceNamesTh = array(
	-2	=> "Media",
	-1	=> "พิเศษ",
	0	=> "",
	1	=> "พูดคุย",
	2	=> "ผู้ใช้",
	3	=> "คุยเกี่ยวกับผู้ใช้",
	4	=> $wgMetaNamespace,
	5	=> $wgMetaNamespace . "_talk",
	6	=> "ภาพ",
	7	=> "คุยเกี่ยวกับภาพ",
	8	=> "MediaWiki",
	9	=> "คุยเกี่ยวกับ_MediaWiki",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsTh = array(
	"ไม่มี", "อยู่ทางซ้าย", "อยู่ทางขวา", "ลอยทางซ้าย"
);

/* private */ $wgSkinNamesTh = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgia",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin"
);

/* private */ $wgDateFormatsTh = array(
	"No preference",
	"มกราคม 15, 2001",
	"15 มกราคม 2001",
	"2001 มกราคม 15",
	"2001-01-15"
);



# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesTh = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "ตั้งค่าสำหรับผู้ใช้",
	"Watchlist"		=> "Watchlist",
	"Recentchanges" => "หน้าที่ได้รับการแก้ไขเมื่อเร็ว ๆ นี้",
	"Upload"		=> "Upload ภาพ",
	"Imagelist"		=> "รายชื่อภาพ",
	"Listusers"		=> "ผูใช้ที่ลงทะเบียนแล้ว",
	"Statistics"	=> "สถิติของเว็บไซต์",
	"Randompage"	=> "สุ่มบทความ",

	"Lonelypages"	=> "บทความที่ไม่ได้ลิงก์ไว้",
	"Unusedimages"	=> "ภาพที่ไม่ได้ใช้",
	"Popularpages"	=> "บทความที่ได้รับความนิยม",
	"Wantedpages"	=> "บทความที่เป็นที่ต้องการมากที่สุด",
	"Shortpages"	=> "บทความสั้น",
	"Longpages"		=> "บทความยาว",
	"Newpages"		=> "บทความที่สร้างมาใหม่",
	"Ancientpages"	=> "บทความที่เก่าที่สุด",
        "Deadendpages"  => "หน้าทางตัน",
#	"Intl"                => "ลิงก์ระหว่างภาษา",
	"Allpages"		=> "ทุกหน้าตามชื่อ",

	"Ipblocklist"	=> "ผู้ใช้และ IP address ที่ถูกกั้นไว้",
	"Maintenance"	=> "หน้าสำหรับการรักษาบำรุง",
	"Specialpages"  => "",
	"Contributions" => "",
	"Emailuser"		=> "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "แหล่งหนังสือภายนอก",
#	"Categories"	=> "ประเภทของหน้า",
	"Export"		=> "การ export หน้าเป็น XML",
	"Version"		=> "Version",
);

/* private */ $wgSysopSpecialPagesTh = array(
	"Blockip"		=> "กั้นผู้ใช้ หรือ IP address",
	"Asksql"		=> "ค้นหาในฐานข้อมูล",
	"Undelete"		=> "คืนหน้าที่ถูกลบไป"
);

/* private */ $wgDeveloperSpecialPagesTh = array(
	"Lockdb"		=> "ทำให้ฐานข้อมูลอ่านได้อย่างเดียว",
	"Unlockdb"		=> "ทำให้ฐานข้อมูลสามารถเขียนได้",
);

#-------------------------------------------------------------------
# Default messages
#-------------------------------------------------------------------
# Allowed characters in keys are: A-Z, a-z, 0-9, underscore (_) and
# hyphen (-). If you need more characters, you may be able to change
# the regex in MagicWord::initRegex

/* private */ $wgAllMessagesTh = array(
'special_version_prefix' => '',
'special_version_postfix' => '',

# User Toggles
#

"tog-hover"		=> "แสดง hoverbox เหนือลิงก์",
"tog-underline" => "ขีดเส้นใต้ลิงก์",
"tog-highlightbroken" => "จัดลิงก์ที่ไม่มี <a href=\"\" class=\"new\">เป็น ดังนี้</a> (หรือ เป็นดังนี้<a href=\"\" class=\"internal\">?</a>).",
"tog-justify"	=> "จัดย่อหน้าให้เรียบร้อย",
"tog-hideminor" => "ไม่แสดงการแก้ไขย่อยใน recent changes",
"tog-usenewrc" => "Enhanced recent changes (ไม่สามารถใช้ได้กับทุกเว็บบราวเซอร์)",
"tog-numberheadings" => "ใส่ตัวเลขหน้าหัวข้อโดยอัตโนมัติ",
"tog-showtoolbar" => "Show edit toolbar",
"tog-editondblclick" => "แก้ไขหน้าโดยใช้ double click (ผ่าน JavaScript)",
"tog-editsection"=>"สามารถแก้ไขเฉพาะส่วนโดยใช้ลิงก์ [แก้ไข]",
"tog-editsectiononrightclick"=>"สามารถแก้ไขเฉพาะส่วนโดยใช้ right click<br /> บนชื่อส่วนย่อย (ผ่าน JavaScript)",
"tog-showtoc"=>"แสดงสารบัญ<br />(สำหรับบทความที่มีมากกว่า 3 หัวข้อ)",
"tog-rememberpassword" => "จำ password ระหว่าง session",
"tog-editwidth" => "กล่องสำหรับการแก้ไขกว้างเต็มหน้าจอ",
"tog-watchdefault" => "นำหน้าที่แก้ไขไปใส่ watchlist",
"tog-minordefault" => "กำหนด default ให้การแก้ไขทุกครั้งเป็นการแก้ไขย่อย",
"tog-previewontop" => "แสดง preview ก่อนกล่องสำหรับการแก้ไข",
"tog-nocache" => "ไม่นำหน้าต่าง ๆ มาใส่ใน cache",


# Dates
#

'sunday' => "วันอาทิตย์",
'monday' => "วันจันทร์",
'tuesday' => "วันอังคาร",
'wednesday' => "วันพุธ",
'thursday' => "วันพฤหัสบดี",
'friday' => "วันศุกร์",
'saturday' => "วันเสาร์",
'january' => "มกราคม",
'february' => "กุมภาพันธ์",
'march' => "มีนาคม",
'april' => "เมษายน",
'may_long' => "พฤษภาคม",
'june' => "มิถุนายน",
'july' => "กรกฎาคม",
'august' => "สิงหาคม",
'september' => "กันยายน",
'october' => "ตุลาคม",
'november' => "พฤศจิกายน",
'december' => "ธันวาคม",
'jan' => "ม.ค.",
'feb' => "ก.พ.",
'mar' => "มี.ค.",
'apr' => "เม.ย.",
'may' => "พ.ค.",
'jun' => "มิ.ย.",
'jul' => "ก.ค.",
'aug' => "ส.ค.",
'sep' => "ก.ย.",
'oct' => "ต.ค.",
'nov' => "พ.ย.",
'dec' => "ธ.ค.",

# Bits of text used by many pages:
#
"categories" => "ประเภทของหน้า",
"category" => "ประเภท",
"category_header" => "บทความในประเภท \"$1\"",
"subcategories" => "ประเภทย่อย",

"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "หน้าหลัก",
"mainpagetext"	=> "Wiki software  ถูกติดตั้งเรียบร้อยแล้ว",
"about"			=> "เกี่ยวกับ",
"aboutsite"      => "เกี่ยวกับ $wgSitename",
"aboutpage"		=> "$wgMetaNamespace:เกี่ยวกับ",
"help"			=> "ความช่วยเหลือ",
"helppage"		=> "$wgMetaNamespace:ความช่วยเหลือ",
"wikititlesuffix" => "$wgSitename",
"bugreports"	=> "รายงาน bug",
"bugreportspage" => "$wgMetaNamespace:รายงาน bug",
"sitesupport"   => "การบริจาค",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "FAQ",
"faqpage"		=> "$wgMetaNamespace:FAQ",
"edithelp"		=> "ความช่วยเหลือในการแก้ไขหน้า",
"edithelppage"	=> "$wgMetaNamespace:การแก้ไขหน้า",
"cancel"		=> "ยกเลิก",
"qbfind"		=> "ค้นหา",
"qbbrowse"		=> "ค้น",
"qbedit"		=> "แก้ไข",
"qbpageoptions" => "หน้านี้",
"qbpageinfo"	=> "บริบท",
"qbmyoptions"	=> "หน้าของฉัน",
"qbspecialpages"	=> "หน้าพิเศษ",
"moredotdotdot"	=> "อื่น ๆ ...",
"mypage"		=> "หน้าของฉัน",
"mytalk"		=> "หน้าพูดคุยของฉัน",
"currentevents" => "เหตุการณ์ปัจจุบัน",
"errorpagetitle" => "ความผิดพลาด",
"returnto"		=> "กลับไปยัง $1.",
"tagline"      	=> "จาก $wgSitename, สารานุกรมฟรี",
"whatlinkshere"	=> "หน้าที่ลิงก์มายังที่นี่",
"help"			=> "ความช่วยเหลือ",
"search"		=> "ค้นหา",
"go"		=> "ไป",
"history"		=> "ประวัติของหน้านี้",
"printableversion" => "Printable version",
"editthispage"	=> "แก้ไขหน้านี้",
"deletethispage" => "ลบหน้านี้",
"protectthispage" => "ป้องกันหน้านี้",
"unprotectthispage" => "ยกเลิกการป้องกันหน้านี้",
"newpage" => "หน้าใหม่",
"talkpage"		=> "พูดคุยเกี่ยวกับหน้านี้",
"postcomment"   => "Post a comment",
"articlepage"	=> "View article",
"subjectpage"	=> "View subject", # For compatibility
"userpage" => "View user page",
"wikipediapage" => "View meta page",
"imagepage" => 	"View image page",
"viewtalkpage" => "View discussion",
"otherlanguages" => "Other languages",
"redirectedfrom" => "(Redirected from $1)",
"lastmodified"	=> "This page was last modified $1.",
"viewcount"		=> "This page has been accessed $1 times.",
"gnunote" => "All text is available under the terms of the <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(From http://www.wikipedia.org)",
"protectedpage" => "Protected page",
"administrators" => "$wgMetaNamespace:Administrators",
"sysoptitle"	=> "Sysop access required",
"sysoptext"		=> "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.",
"developertitle" => "Developer access required",
"developertext"	=> "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",

"nbytes"		=> "$1 ไบต์",
"go"			=> "ไป",
"ok"			=> "OK",
"sitetitle"		=> "$wgSitename",
"sitesubtitle"	=> "สารานุกรมฟรี",
"retrievedfrom" => "Retrieved from \"$1\"",
"newmessages" => "คุณมีข้อความใหม่ $1.",
"newmessageslink" => "ข้อความ",
"editsection"=>"แก้ไข",
"toc" => "สารบัญ",
"showtoc" => "แสดงสารบัญ",
"hidetoc" => "ซ่อนสารบัญ",
"thisisdeleted" => "แสดงหรือคืน $1?",
"restorelink" => "$1 การแก้ไขที่ลบแล้ว",

# Main script and global functions
#
"nosuchaction"	=> "ไม่มีการกระทำดังกล่าว",
"nosuchactiontext" => "การกระทำที่บอกไว้ใน URL ไม่
เป็นที่ยอมรับของ wiki",
"nosuchspecialpage" => "ไม่มีหน้าพิเศษดังกล่าว",
"nospecialpagetext" => "คุณได้ขอหน้าพิเศษที่ไม่
เป็นที่ยอมรับของ wiki",

# General errors
#
"error"			=> "Error",
"databaseerror" => "Database error",
"dberrortextcl" => "A database query syntax error has occurred.
The last attempted database query was:
\"$1\"
from within function \"$2\".
MySQL returned error \"$3: $4\".\n",
"noconnect"		=> "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server.",
"nodb"			=> "Could not select database $1",
"cachederror"		=> "The following is a cached copy of the requested page, and may not be up to date.",
"readonly"		=> "Database locked",
"enterlockreason" => "Enter a reason for the lock, including an estimate
of when the lock will be released",
"readonlytext"	=> "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",
"missingarticle" => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",
"internalerror" => "Internal error",
"filecopyerror" => "Could not copy file \"$1\" to \"$2\".",
"filerenameerror" => "Could not rename file \"$1\" to \"$2\".",
"filedeleteerror" => "Could not delete file \"$1\".",
"filenotfound"	=> "Could not find file \"$1\".",
"unexpected"	=> "Unexpected value: \"$1\"=\"$2\".",
"formerror"		=> "Error: could not submit form",
"badarticleerror" => "This action cannot be performed on this page.",
"cannotdelete"	=> "Could not delete the page or image specified. (It may have already been deleted by someone else.)",
"badtitle"		=> "Bad title",
"badtitletext"	=> "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.",
"perfdisabled" => "Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.",
"perfdisabledsub" => "Here's a saved copy from $1:",
"wrong_wfQuery_params" => "Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
",
"viewsource" => "View source",
"protectedtext" => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[$wgMetaNamespace:Protected page]].

You can view and copy the source of this page:",

# Login and logout pages
#
"logouttitle"	=> "User logout",
"logouttext" => "You are now logged out.
You can continue to use $wgSitename anonymously, or you can log in
again as the same or as a different user. Note that some pages may
continue to be displayed as if you were still logged in, until you clear
your browser cache\n",

"welcomecreation" => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to personalize your wikipedia preferences.",

"loginpagetitle" => "ล็อกอินผู้ใช้",
"yourname"		=> "ชื่อผู้ใช้",
"yourpassword"	=> "รหัสผ่าน",
"yourpasswordagain" => "พิมพ์รหัสผ่านอีกครั้ง",
"newusersonly"	=> " (เฉพาะผู้ใช้ใหม่)",
"remembermypassword" => "Remember my password across sessions.",
"loginproblem"	=> "<b>There has been a problem with your login.</b><br />Try again!",
"alreadyloggedin" => "<font color=red><b>User $1, you are already logged in!</b></font><br />\n",

"login"			=> "ล็อกอิน",
"loginprompt"           => "ต้อง enable cookie เพื่อล็อกอินสู่ $wgSitename ได้",
"userlogin"		=> "ล็อกอิน",
"logout"		=> "ล็อกเอาท์",
"userlogout"	=> "ล็อกเอาท์",
"notloggedin"	=> "ไม่ได้ล็อกอินไว้",
"createaccount"	=> "สร้าง account ใหม่",
"createaccountmail"	=> "ผ่านอีเมล์",
"badretype"		=> "รหัสผ่านที่พิมพ์ไว้ไม่เหมือนกัน",
"userexists"	=> "ชื่อผู้ใช้ที่พิมพ์ไว้ถูกใช้แล้ว โปรดเลือกชื่ออื่น",
"youremail"		=> "อีเมล์ของคุณ*",
"yournick"		=> "Your nickname (for signatures)",
"emailforlost"	=> "* Entering an email address is optional.  But it enables people to
contact you through the website without you having to reveal your
email address to them, and it also helps you if you forget your
password.",
"loginerror"	=> "Login error",
"nocookiesnew"	=> "The user account was created, but you are not logged in. $wgSitename uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
"nocookieslogin"	=> "$wgSitename uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
"noname"		=> "You have not specified a valid user name.",
"loginsuccesstitle" => "Login successful",
"loginsuccess"	=> "You are now logged in to $wgSitename as \"$1\".",
"nosuchuser"	=> "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",
"wrongpassword"	=> "The password you entered is incorrect. Please try again.",
"mailmypassword" => "Mail me a new password",
"passwordremindertitle" => "Password reminder from $wgSitename",
"passwordremindertext" => "Someone (probably you, from IP address $1)
requested that we send you a new $wgSitename login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
"noemail"		=> "There is no e-mail address recorded for user \"$1\".",
"passwordsent"	=> "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",

# Edit pages
#
"summary"		=> "Summary",
"subject"		=> "Subject/headline",
"minoredit"		=> "This is a minor edit",
"watchthis"		=> "Watch this article",
"savearticle"	=> "Save page",
"preview"		=> "Preview",
"showpreview"	=> "Show preview",
"blockedtitle"	=> "User is blocked",
"blockedtext"	=> "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[$wgMetaNamespace:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.

==Note to AOL users==
Due to continuing acts of vandalism by one particular AOL user, Wikipedia often blocks AOL proxies. Unfortunately, a single proxy server may be used by a large number of AOL users, and hence innocent AOL users are often inadvertently blocked. We apologise for any inconvenience caused.

If this happens to you, please email an administrator, using an AOL email address. Be sure to include the IP address given above.
",
"whitelistedittitle" => "Login required to edit",
"whitelistedittext" => "You have to [[Special:Userlogin|login]] to edit articles.",
"whitelistreadtitle" => "Login required to read",
"whitelistreadtext" => "You have to [[Special:Userlogin|login]] to read articles.",
"whitelistacctitle" => "You are not allowed to create an account",
"whitelistacctext" => "To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.",
"accmailtitle" => "Password sent.",
"accmailtext" => "The Password for '$1' has been sent to $2.",
"newarticle"	=> "(New)",
"newarticletext" =>
"คุณได้ตามลิงก์ที่นำไปยังหน้าที่ยังไม่ปรากฏอยู่
เพื่อเริ่มสร้างหน้าใหม่ พิมพ์ลงในกล่องข้างล่างนี้
(ดู[[$wgMetaNamespace:ความช่วยเหลือ|หน้าความช่วยเหลือ]]สำหรับข้อมูลเพิ่มเติม)
If you are here by mistake, just click your browser's '''back''' button.",
"anontalkpagetext" => "---- ''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
"noarticletext" => "(ไม่มีข้อความในหน้านี้)",
"updated"		=> "(ได้รับการแก้ไขแล้ว)",
"note"			=> "<strong>Note:</strong> ",
"previewnote"	=> "Remember that this is only a preview, and has not yet been saved!",
"previewconflict" => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.",
"editing"		=> "Editing $1",
"editingsection"	=> "Editing $1 (section)",
"editingcomment"	=> "Editing $1 (comment)",
"editconflict"	=> "Edit conflict: $1",
"explainconflict" => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".\n<p>",
"yourtext"		=> "Your text",
"storedversion" => "Stored version",
"editingold"	=> "<strong>WARNING: You are editing an out-of-date
revision of this page.
If you save it, any changes made since this revision will be lost.</strong>\n",
"yourdiff"		=> "Differences",
# FIXME: This is inappropriate for third-party use!
"copyrightwarning" => "Please note that all contributions to $wgSitename are
considered to be released under the GNU Free Documentation License
(see $1 for details).
If you don't want your writing to be edited mercilessly and redistributed
at will, then don't submit it here.<br />
You are also promising us that you wrote this yourself, or copied it from a
public domain or similar free resource.
<strong>DO NOT SUBMIT COPYRIGHTED WORK WITHOUT PERMISSION!</strong>",
"longpagewarning" => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",
"readonlywarning" => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.",
"protectedpagewarning" => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/wiki/$wgMetaNamespace:Protected_page_guidelines'>protected page
guidelines</a>.",

# History pages
#
"revhistory"	=> "Revision history",
"nohistory"		=> "There is no edit history for this page.",
"revnotfound"	=> "Revision not found",
"revnotfoundtext" => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.\n",
"loadhist"		=> "Loading page history",
"currentrev"	=> "Current revision",
"revisionasof"	=> "Revision as of $1",
"cur"			=> "cur",
"next"			=> "next",
"last"			=> "last",
"orig"			=> "orig",
"histlegend"	=> "Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit",

# Diffs
#
"difference"	=> "(Difference between revisions)",
"loadingrev"	=> "loading revision for diff",
"lineno"		=> "Line $1:",
"editcurrent"	=> "Edit the current version of this page",

# Search results
#
"searchresults" => "Search results",
"searchresulttext" => "For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].",
"searchquery"	=> "For query \"$1\"",
"badquery"		=> "Badly formed search query",
"badquerytext"	=> "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",
"matchtotals"	=> "The query \"$1\" matched $2 article titles
and the text of $3 articles.",
"nogomatch" => "No page with this exact title exists, trying full text search.",
"titlematches"	=> "Article title matches",
"notitlematches" => "No article title matches",
"textmatches"	=> "Article text matches",
"notextmatches"	=> "No article text matches",
"prevn"			=> "previous $1",
"nextn"			=> "next $1",
"viewprevnext"	=> "View ($1) ($2) ($3).",
"showingresults" => "Showing below <b>$1</b> results starting with #<b>$2</b>.",
"showingresultsnum" => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
"nonefound"		=> "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",
"powersearch" => "Search",
"powersearchtext" => "
Search in namespaces :<br />
$1<br />
$2 List redirects &nbsp; Search for $3 $9",
"searchdisabled" => "<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
"blanknamespace" => "(Main)",

# Preferences page
#
"preferences"	=> "Preferences",
"prefsnologin" => "Not logged in",
"prefsnologintext"	=> "You must be <a href=\"" .
  "{{localurle:Special:Userlogin}}\">logged in</a>
to set user preferences.",
"prefslogintext" => "You are logged in as \"$1\".
Your internal ID number is $2.

See [[$wgMetaNamespace:User preferences help]] for help deciphering the options.",
"prefsreset"	=> "Preferences have been reset from storage.",
"qbsettings"	=> "Quickbar settings",
"changepassword" => "Change password",
"skin"			=> "Skin",
"math"			=> "Rendering math",
"dateformat"	=> "Date format",
"math_failure"		=> "Failed to parse",
"math_unknown_error"	=> "unknown error",
"math_unknown_function"	=> "unknown function ",
"math_lexing_error"	=> "lexing error",
"math_syntax_error"	=> "syntax error",
"saveprefs"		=> "Save preferences",
"resetprefs"	=> "Reset preferences",
"oldpassword"	=> "Old password",
"newpassword"	=> "New password",
"retypenew"		=> "Retype new password",
"textboxsize"	=> "Editing",
"rows"			=> "Rows",
"columns"		=> "Columns",
"searchresultshead" => "Search result settings",
"resultsperpage" => "Hits to show per page",
"contextlines"	=> "Lines to show per hit",
"contextchars"	=> "Characters of context per line",
"stubthreshold" => "Threshold for stub display",
"recentchangescount" => "Number of titles in recent changes",
"savedprefs"	=> "Your preferences have been saved.",
"timezonetext"	=> "Enter number of hours your local time differs
from server time (UTC).",
"localtime"	=> "Local time display",
"timezoneoffset" => "Offset",
"servertime"	=> "Server time is now",
"guesstimezone" => "Fill in from browser",
"emailflag"		=> "Disable e-mail from other users",
"defaultns"		=> "Search in these namespaces by default:",

# Recent changes
#
"changes" => "changes",
"recentchanges" => "Recent changes",
"recentchangestext" => "Track the most recent changes to the wiki on this page.",
"rcloaderr"		=> "Loading recent changes",
"rcnote"		=> "Below are the last <strong>$1</strong> changes in last <strong>$2</strong> days.",
"rcnotefrom"	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
"rclistfrom"	=> "Show new changes starting from $1",
# "rclinks"		=> "Show last $1 changes in last $2 hours / last $3 days",
# "rclinks"		=> "Show last $1 changes in last $2 days.",
"showhideminor"         => "$1 minor edits",
"rclinks"		=> "Show last $1 changes in last $2 days; $3",
"rchide"		=> "in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 edits from logged in users",
"diff"			=> "diff",
"hist"			=> "hist",
"hide"			=> "hide",
"show"			=> "show",
"tableform"		=> "table",
"listform"		=> "list",
"nchanges"		=> "$1 changes",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Upload file",
"uploadbtn"		=> "Upload file",
"uploadlink"	=> "Upload images",
"reupload"		=> "Re-upload",
"reuploaddesc"	=> "Return to the upload form.",
"uploadnologin" => "Not logged in",
"uploadnologintext"	=> "You must be <a href=\"" .
  "{{localurle:Special:Userlogin}}\">logged in</a>
to upload files.",
"uploadfile"	=> "Upload images, sounds, documents etc.",
"uploaderror"	=> "Upload error",

"uploadlog"		=> "upload log",
"uploadlogpage" => "Upload_log",
"uploadlogpagetext" => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",
"filename"		=> "Filename",
"filedesc"		=> "Summary",
"filestatus" => "Copyright status",
"filesource" => "Source",
"affirmation"	=> "I affirm that the copyright holder of this file
agrees to license it under the terms of the $1.",
"copyrightpage" => "$wgMetaNamespace:Copyrights",
"copyrightpagename" => "$wgSitename copyright",
"uploadedfiles"	=> "Uploaded files",
"noaffirmation" => "You must affirm that your upload does not violate
any copyrights.",
"ignorewarning"	=> "Ignore warning and save file anyway.",
"minlength"		=> "Image names must be at least three letters.",
"badfilename"	=> "Image name has been changed to \"$1\".",
"badfiletype"	=> "\".$1\" is not a recommended image file format.",
"largefile"		=> "It is recommended that images not exceed 100k in size.",
"successfulupload" => "Successful upload",
"fileuploaded"	=> "File \"$1\" uploaded successfully.
Please follow this link: ($2) to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
"uploadwarning" => "Upload warning",
"savefile"		=> "Save file",
"uploadedimage" => "uploaded \"$1\"",

# Image list
#
"imagelist"		=> "Image list",
"imagelisttext"	=> "Below is a list of $1 images sorted $2.",
"getimagelist"	=> "fetching image list",
"ilshowmatch"	=> "Show all images with names matching",
"ilsubmit"		=> "Search",
"showlast"		=> "Show last $1 images sorted $2.",
"all"			=> "all",
"byname"		=> "by name",
"bydate"		=> "by date",
"bysize"		=> "by size",
"imgdelete"		=> "del",
"imgdesc"		=> "desc",
"imglegend"		=> "Legend: (desc) = show/edit image description.",
"imghistory"	=> "Image history",
"revertimg"		=> "rev",
"deleteimg"		=> "del",
"deleteimgcompletely"		=> "del",
"imghistlegend" => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Image links",
"linkstoimage"	=> "The following pages link to this image:",
"nolinkstoimage" => "There are no pages that link to this image.",

# Statistics
#
"statistics"	=> "Statistics",
"sitestats"		=> "Site statistics",
"userstats"		=> "User statistics",
"sitestatstext" => "There are <b>$1</b> total pages in the database.
This includes \"talk\" pages, pages about $wgSitename, minimal \"stub\"
pages, redirects, and others that probably don't qualify as articles.
Excluding those, there are <b>$2</b> pages that are probably legitimate
articles.<p>
There have been a total of <b>$3</b> page views, and <b>$4</b> page edits
since the software was upgraded (July 20, 2002).
That comes to <b>$5</b> average edits per page, and <b>$6</b> views per edit.",
"userstatstext" => "There are <b>$1</b> registered users.
<b>$2</b> of these are administrators (see $3).",

# Maintenance Page
#
"maintenance"		=> "Maintenance page",
"maintnancepagetext"	=> "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
"maintenancebacklink"	=> "Back to Maintenance Page",
"disambiguations"	=> "Disambiguation pages",
"disambiguationspage"	=> "$wgMetaNamespace:Links_to_disambiguating_pages",
"disambiguationstext"	=> "The following articles link to a <i>disambiguation page</i>. They should link to the appropriate topic instead.<br />A page is treated as dismbiguation if it is linked from $1.<br />Links from other namespaces are <i>not</i> listed here.",
"doubleredirects"	=> "Double Redirects",
"doubleredirectstext"	=> "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br />\nEach row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" taget article, which the first redirect should point to.",
"brokenredirects"	=> "Broken Redirects",
"brokenredirectstext"	=> "The following redirects link to a non-existing article.",
"selflinks"		=> "Pages with Self Links",
"selflinkstext"		=> "The following pages contain a link to themselves, which they should not.",
"mispeelings"           => "Pages with misspellings",
"mispeelingstext"               => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
"mispeelingspage"       => "List of common misspellings",
"missinglanguagelinks"  => "Missing Language Links",
"missinglanguagelinksbutton"    => "Find missing language links for",
"missinglanguagelinkstext"      => "These articles do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",


# Miscellaneous special pages
#
"orphans"		=> "Orphaned pages",
"lonelypages"	=> "Orphaned pages",
"unusedimages"	=> "Unused images",
"popularpages"	=> "Popular pages",
"nviews"		=> "$1 views",
"wantedpages"	=> "Wanted pages",
"nlinks"		=> "$1 links",
"allpages"		=> "All pages",
"randompage"	=> "Random page",
"shortpages"	=> "Short pages",
"longpages"		=> "Long pages",
"deadendpages"  => "Dead-end pages",
"listusers"		=> "User list",
"specialpages"	=> "Special pages",
"spheading"		=> "Special pages for all users",
"sysopspheading" => "For sysop use only",
"developerspheading" => "For developer use only",
"protectpage"	=> "Protect page",
"recentchangeslinked" => "Related changes",
"rclsub"		=> "(to pages linked from \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "New pages",
"ancientpages"		=> "Oldest articles",
"intl"		=> "Interlanguage links",
"movethispage"	=> "Move this page",
"unusedimagestext" => "<p>Please note that other web sites
such as the international Wikipedias may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",
"booksources"	=> "Book sources",
# FIXME: Other sites, of course, may have affiliate relations with the booksellers list
"booksourcetext" => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.
$wgSitename is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
"alphaindexline" => "$1 to $2",

# Email this user
#
"mailnologin"	=> "No send address",
"mailnologintext" => "You must be <a href=\"" .
  "{{localurle:Special:Userlogin}}\">logged in</a>
and have a valid e-mail address in your <a href=\"" .
  "{{localurle:Special:Preferences}}\">preferences</a>
to send e-mail to other users.",
"emailuser"		=> "E-mail this user",
"emailpage"		=> "E-mail user",
"emailpagetext"	=> "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",
"noemailtitle"	=> "No e-mail address",
"noemailtext"	=> "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",
"emailfrom"		=> "From",
"emailto"		=> "To",
"emailsubject"	=> "Subject",
"emailmessage"	=> "Message",
"emailsend"		=> "Send",
"emailsent"		=> "E-mail sent",
"emailsenttext" => "Your e-mail message has been sent.",

# Watchlist
#
"watchlist"		=> "My watchlist",
"watchlistsub"	=> "(for user \"$1\")",
"nowatchlist"	=> "You have no items on your watchlist.",
"watchnologin"	=> "Not logged in",
"watchnologintext"	=> "You must be <a href=\"" .
  "{{localurle:Special:Userlogin}}\">logged in</a>
to modify your watchlist.",
"addedwatch"	=> "Added to watchlist",
"addedwatchtext" => "The page \"$1\" has been added to your <a href=\"" .
  "{{localurle:Special:Watchlist}}\">watchlist</a>.
Future changes to this page and its associated Talk page will be listed there,
and the page will appear <b>bolded</b> in the <a href=\"" .
  "{{localurle:Special:Recentchanges}}\">list of recent changes</a> to
make it easier to pick out.</p>

<p>If you want to remove the page from your watchlist later, click \"Stop watching\" in the sidebar.",
"removedwatch"	=> "Removed from watchlist",
"removedwatchtext" => "The page \"$1\" has been removed from your watchlist.",
"watchthispage"	=> "Watch this page",
"unwatchthispage" => "Stop watching",
"notanarticle"	=> "Not an article",
"watchnochange" => "None of your watched items were edited in the time period displayed.",
"watchdetails" => "($1 pages watched not counting talk pages;
$2 total pages edited since cutoff;
$3...
<a href='$4'>show and edit complete list</a>.)",
"watchmethod-recent" => "checking recent edits for watched pages",
"watchmethod-list" => "checking watched pages for recent edits",
"removechecked" => "Remove checked items from watchlist",
"watchlistcontains" => "Your watchlist contains $1 pages.",
"watcheditlist" => "Here's an alphabetical list of your
watched pages. Check the boxes of pages you want to remove
from your watchlist and click the 'remove checked' button
at the bottom of the screen.",
"removingchecked" => "Removing requested items from watchlist...",
"couldntremove" => "Couldn't remove item '$1'...",
"iteminvalidname" => "Problem with item '$1', invalid name...",
"wlnote" => "Below are the last $1 changes in the last <b>$2</b> hours.",
"wlshowlast" => "Show last $1 hours $2 days $3",

# Delete/protect/revert
#
"deletepage"	=> "Delete page",
"confirm"		=> "Confirm",
"excontent" => "content was:",
"exbeforeblank" => "content before blanking was:",
"exblank" => "page was empty",
"confirmdelete" => "Confirm delete",
"deletesub"		=> "(Deleting \"$1\")",
"historywarning" => "Warning: The page you are about to delete has a history: ",
"confirmdeletetext" => "You are about to permanently delete a page
or image along with all of its history from the database.
Please confirm that you intend to do this, that you understand the
consequences, and that you are doing this in accordance with
[[$wgMetaNamespace:Policy]].",
"confirmcheck"	=> "Yes, I really want to delete this.",
"actioncomplete" => "Action complete",
"deletedtext"	=> "\"$1\" has been deleted.
See $2 for a record of recent deletions.",
"deletedarticle" => "deleted \"$1\"",
"dellogpage"	=> "Deletion_log",
"dellogpagetext" => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",
"deletionlog"	=> "deletion log",
"reverted"		=> "Reverted to earlier revision",
"deletecomment"	=> "Reason for deletion",
"imagereverted" => "Revert to earlier version was successful.",
"rollback"		=> "Roll back edits",
"rollbacklink"	=> "rollback",
"rollbackfailed" => "Rollback failed",
"cantrollback"	=> "Cannot revert edit; last contributor is only author of this article.",
"alreadyrolled"	=> "Cannot rollback last edit of [[$1]]
by [[User:$2|$2]] ([[User talk:$2|Talk]]); someone else has edited or rolled back the article already.

Last edit was by [[User:$3|$3]] ([[User talk:$3|Talk]]). ",
#   only shown if there is an edit comment
"editcomment" => "The edit comment was: \"<i>$1</i>\".",
"revertpage"	=> "Reverted to last edit by $1",
"protectlogpage" => "Protection_log",
"protectlogtext" => "Below is a list of page locks/unlocks.
See [[$wgMetaNamespace:Protected page]] for more information.",
"protectedarticle" => "protected $1",
"unprotectedarticle" => "unprotected $1",

# Undelete
"undelete" => "Restore deleted page",
"undeletepage" => "View and restore deleted pages",
"undeletepagetext" => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",
"undeletearticle" => "Restore deleted article",
"undeleterevisions" => "$1 revisions archived",
"undeletehistory" => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",
"undeleterevision" => "Deleted revision as of $1",
"undeletebtn" => "Restore!",
"undeletedarticle" => "restored \"$1\"",
"undeletedtext"   => "The article [[$1]] has been successfully restored.
See [[$wgMetaNamespace:Deletion_log]] for a record of recent deletions and restorations.",

# Contributions
#
"contributions"	=> "User contributions",
"mycontris" => "My contributions",
"contribsub"	=> "For $1",
"nocontribs"	=> "No changes were found matching these criteria.",
"ucnote"		=> "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
"uclinks"		=> "View the last $1 changes; view the last $2 days.",
"uctop"		=> " (top)" ,

# What links here
#
"whatlinkshere"	=> "What links here",
"notargettitle" => "No target",
"notargettext"	=> "You have not specified a target page or user
to perform this function on.",
"linklistsub"	=> "(List of links)",
"linkshere"		=> "The following pages link to here:",
"nolinkshere"	=> "No pages link to here.",
"isredirect"	=> "redirect page",

# Block/unblock IP
#
"blockip"		=> "Block user",
"blockiptext"	=> "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[$wgMetaNamespace:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",
"ipaddress"		=> "IP Address/username",
"ipbreason"		=> "Reason",
"ipbsubmit"		=> "Block this user",
"badipaddress"	=> "No user exists by that name",
"noblockreason" => "You must supply a reason for the block.",
"blockipsuccesssub" => "Block succeeded",
"blockipsuccesstext" => "\"$1\" has been blocked.
<br />See [[Special:Ipblocklist|IP block list]] to review blocks.",
"unblockip"		=> "Unblock user",
"unblockiptext"	=> "Use the form below to restore write access
to a previously blocked IP address or username.",
"ipusubmit"		=> "Unblock this address",
"ipusuccess"	=> "\"$1\" unblocked",
"ipblocklist"	=> "List of blocked IP addresses and usernames",
"blocklistline"	=> "$1, $2 blocked $3",
"blocklink"		=> "block",
"unblocklink"	=> "unblock",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autoblocked because you share an IP address with \"$1\". Reason \"$2\".",
"blocklogpage"	=> "Block_log",
"blocklogentry"	=> 'blocked "$1"',
"blocklogtext"	=> "This is a log of user blocking and unblocking actions. Automatically
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
"unblocklogentry"	=> 'unblocked "$1"',

# Developer tools
#
"lockdb"		=> "Lock database",
"unlockdb"		=> "Unlock database",
"lockdbtext"	=> "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",
"unlockdbtext"	=> "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",
"lockconfirm"	=> "Yes, I really want to lock the database.",
"unlockconfirm"	=> "Yes, I really want to unlock the database.",
"lockbtn"		=> "Lock database",
"unlockbtn"		=> "Unlock database",
"locknoconfirm" => "You did not check the confirmation box.",
"lockdbsuccesssub" => "Database lock succeeded",
"unlockdbsuccesssub" => "Database lock removed",
"lockdbsuccesstext" => "The database has been locked.
<br />Remember to remove the lock after your maintenance is complete.",
"unlockdbsuccesstext" => "The database has been unlocked.",

# SQL query
#
"asksql"		=> "SQL query",
"asksqltext"	=> "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",
"sqlislogged"	=> "Please note that all queries are logged.",
"sqlquery"		=> "Enter query",
"querybtn"		=> "Submit query",
"selectonly"	=> "Only read-only queries are allowed.",
"querysuccessful" => "Query successful",

# Move page
#
"movepage"		=> "Move page",
"movepagetext"	=> "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
[[Special:Maintenance|check]] for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will '''not''' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
"movepagetalktext" => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",
"movearticle"	=> "Move page",
"movenologin"	=> "Not logged in",
"movenologintext" => "You must be a registered user and <a href=\"" .
  "{{localurle:Special:Userlogin}}\">logged in</a>
to move a page.",
"newtitle"		=> "To new title",
"movepagebtn"	=> "Move page",
"pagemovedsub"	=> "Move succeeded",
"pagemovedtext" => "Page \"[[$1]]\" moved to \"[[$2]]\".",
"articleexists" => "A page of that name already exists, or the
name you have chosen is not valid.
Please choose another name.",
"talkexists"	=> "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",
"movedto"		=> "moved to",
"movetalk"		=> "Move \"talk\" page as well, if applicable.",
"talkpagemoved" => "The corresponding talk page was also moved.",
"talkpagenotmoved" => "The corresponding talk page was <strong>not</strong> moved.",

"export"		=> "Export pages",
"exporttext"	=> "You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.",
"exportcuronly"	=> "Include only the current revision, not the full history",

# Namespace 8 related

"allmessages"	=> "All_messages",
"allmessagestext"	=> "This is a list of all messages available in the MediaWiki: namespace",

# Math options
'mw_math_png' => 'Always render PNG',
'mw_math_simple' => 'HTML if very simple or else PNG',
'mw_math_html' => 'HTML if possible or else PNG',
'mw_math_source' => 'Leave it as TeX (for text browsers)',
'mw_math_modern' => 'Recommended for modern browsers',
'mw_math_mathml' => 'MathML if possible (experimental)',

);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

require_once( "LanguageUtf8.php" );

class LanguageTh extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesTh;
		return $wgNamespaceNamesTh;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesTh;
		return $wgNamespaceNamesTh[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesTh;

		foreach ( $wgNamespaceNamesTh as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsTh;
		return $wgQuickbarSettingsTh;
	}

	function getSkinNames() {
		global $wgSkinNamesTh;
		return $wgSkinNamesTh;
	}

	function getDateFormats() {
		global $wgDateFormatsTh;
		return $wgDateFormatsTh;
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesTh;
		return $wgValidSpecialPagesTh;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesTh;
		return $wgSysopSpecialPagesTh;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesTh;
		return $wgDeveloperSpecialPagesTh;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesTh;
		if( isset( $wgAllMessagesTh[$key] ) ) {
			return $wgAllMessagesTh[$key];
		} else {
			return Language::getMessage( $key );
		}
	}

	function getAllMessages()
	{
		global $wgAllMessagesTh;
		return $wgAllMessagesTh;
	}

}

?>
