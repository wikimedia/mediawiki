<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
#--------------------------------------------------------------------------
# Translated from English by Varakorn Ungvichian
# แปลงจากภาษาอังกฤษโดย นาย วรากร อึ้งวิเชียร
#--------------------------------------------------------------------------

/* private */ $wgNamespaceNamesTh = array(
	NS_MEDIA		=> "Media",
	NS_SPECIAL		=> "พิเศษ",
	NS_MAIN			=> "",
	NS_TALK			=> "พูดคุย",
	NS_USER			=> "ผู้ใช้",
	NS_USER_TALK		=> "คุยเกี่ยวกับผู้ใช้",
	NS_PROJECT		=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> $wgMetaNamespace . "_talk",
	NS_IMAGE		=> "ภาพ",
	NS_IMAGE_TALK		=> "คุยเกี่ยวกับภาพ",
	NS_MEDIAWIKI		=> "MediaWiki",
	NS_MEDIAWIKI_TALK	=> "คุยเกี่ยวกับ_MediaWiki",

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsTh = array(
	"ไม่มี", "อยู่ทางซ้าย", "อยู่ทางขวา", "ลอยทางซ้าย"
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

# User Toggles
#

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

# Login and logout pages
#
"loginpagetitle" => "ล็อกอินผู้ใช้",
"yourname"		=> "ชื่อผู้ใช้",
"yourpassword"	=> "รหัสผ่าน",
"yourpasswordagain" => "พิมพ์รหัสผ่านอีกครั้ง",
"newusersonly"	=> " (เฉพาะผู้ใช้ใหม่)",

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

# Edit pages
#
"newarticletext" =>
"คุณได้ตามลิงก์ที่นำไปยังหน้าที่ยังไม่ปรากฏอยู่
เพื่อเริ่มสร้างหน้าใหม่ พิมพ์ลงในกล่องข้างล่างนี้
(ดู[[$wgMetaNamespace:ความช่วยเหลือ|หน้าความช่วยเหลือ]]สำหรับข้อมูลเพิ่มเติม)
If you are here by mistake, just click your browser's '''back''' button.",
"noarticletext" => "(ไม่มีข้อความในหน้านี้)",
"updated"		=> "(ได้รับการแก้ไขแล้ว)",

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
			return parent::getMessage( $key );
		}
	}

	function getAllMessages()
	{
		global $wgAllMessagesTh;
		return $wgAllMessagesTh;
	}

}

?>
