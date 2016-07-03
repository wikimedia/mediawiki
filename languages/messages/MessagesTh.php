<?php
/** Thai (ไทย)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Akkhaporn
 * @author Ans
 * @author Ariesanywhere
 * @author Harley Hartwell
 * @author Horus
 * @author Kaganer
 * @author Korrawit
 * @author LMNOP at Thai Wikipedia (manop@itshee.com) since July 2007
 * @author Manop
 * @author Mopza
 * @author Nullzero
 * @author Octahedron80
 * @author Passawuth
 * @author TMo3289
 * @author Taweetham
 * @author Woraponboonkerd
 * @author לערי ריינהארט
 * @author จักรกฤช วงศ์สระหลวง (Jakkrit Vongsraluang) / PaePae
 * @author วรากร อึ้งวิเชียร (Varakorn Ungvichian)
 */

$namespaceNames = [
	NS_MEDIA            => 'สื่อ',
	NS_SPECIAL          => 'พิเศษ',
	NS_TALK             => 'พูดคุย',
	NS_USER             => 'ผู้ใช้',
	NS_USER_TALK        => 'คุยกับผู้ใช้',
	NS_PROJECT_TALK     => 'คุยเรื่อง$1',
	NS_FILE             => 'ไฟล์',
	NS_FILE_TALK        => 'คุยเรื่องไฟล์',
	NS_MEDIAWIKI        => 'มีเดียวิกิ',
	NS_MEDIAWIKI_TALK   => 'คุยเรื่องมีเดียวิกิ',
	NS_TEMPLATE         => 'แม่แบบ',
	NS_TEMPLATE_TALK    => 'คุยเรื่องแม่แบบ',
	NS_HELP             => 'วิธีใช้',
	NS_HELP_TALK        => 'คุยเรื่องวิธีใช้',
	NS_CATEGORY         => 'หมวดหมู่',
	NS_CATEGORY_TALK    => 'คุยเรื่องหมวดหมู่',
];

$namespaceAliases = [
	'ภาพ' => NS_FILE,
	'คุยเรื่องภาพ' => NS_FILE_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'ผู้ใช้ที่มีความเคลื่อนไหว' ],
	'Allmessages'               => [ 'ข้อความทั้งหมด' ],
	'Allpages'                  => [ 'หน้าทั้งหมด' ],
	'Ancientpages'              => [ 'บทความที่ไม่ได้แก้ไขนานที่สุด' ],
	'Badtitle'                  => [ 'ชื่อเรื่องไม่เหมาะสม' ],
	'Blankpage'                 => [ 'หน้าว่าง' ],
	'Block'                     => [ 'บล็อกไอพี' ],
	'Booksources'               => [ 'แหล่งหนังสือ' ],
	'BrokenRedirects'           => [ 'เปลี่ยนทางเสีย' ],
	'Categories'                => [ 'หมวดหมู่' ],
	'ChangeEmail'               => [ 'เปลี่ยนอีเมล' ],
	'ChangePassword'            => [ 'เปลี่ยนรหัสผ่าน' ],
	'ComparePages'              => [ 'เปรียบเทียบหน้า' ],
	'Confirmemail'              => [ 'ยืนยันอีเมล' ],
	'Contributions'             => [ 'เรื่องที่เขียน' ],
	'CreateAccount'             => [ 'สร้างบัญชีผู้ใช้ใหม่' ],
	'Deadendpages'              => [ 'หน้าสุดทาง' ],
	'DeletedContributions'      => [ 'การแก้ไขที่ถูกลบ' ],
	'DoubleRedirects'           => [ 'เปลี่ยนทางซ้ำซ้อน' ],
	'EditWatchlist'             => [ 'แก้ไขรายการเฝ้าดู' ],
	'Emailuser'                 => [ 'อีเมลผู้ใช้' ],
	'Export'                    => [ 'ส่งออก' ],
	'Fewestrevisions'           => [ 'บทความที่ถูกแก้ไขน้อยที่สุด' ],
	'FileDuplicateSearch'       => [ 'ค้นหาไฟล์ซ้ำซ้อน' ],
	'Filepath'                  => [ 'พาธของไฟล์', 'ตำแหน่งไฟล์' ],
	'Import'                    => [ 'นำเข้า' ],
	'Invalidateemail'           => [ 'ยกเลิกการยืนยันทางอีเมล' ],
	'JavaScriptTest'            => [ 'ทดสอบจาวาสคริปต์' ],
	'BlockList'                 => [ 'รายชื่อผู้ใช้ที่ถูกบล็อก', 'รายการบล็อก', 'รายชื่อไอพีที่ถูกบล็อก' ],
	'LinkSearch'                => [ 'ค้นหาเว็บลิงก์' ],
	'Listadmins'                => [ 'รายชื่อผู้ดูแล' ],
	'Listbots'                  => [ 'รายชื่อบอต' ],
	'Listfiles'                 => [ 'รายชื่อภาพ' ],
	'Listgrouprights'           => [ 'รายชื่อสิทธิกลุ่มผู้ใช้งาน' ],
	'Listredirects'             => [ 'รายชื่อหน้าเปลี่ยนทาง' ],
	'Listusers'                 => [ 'รายชื่อผู้ใช้' ],
	'Lockdb'                    => [ 'ล็อกฐานข้อมูล' ],
	'Log'                       => [ 'ปูม' ],
	'Lonelypages'               => [ 'หน้าที่โยงไปไม่ถึง' ],
	'Longpages'                 => [ 'หน้าที่ยาวที่สุด' ],
	'MergeHistory'              => [ 'รวมประวัติ' ],
	'MIMEsearch'                => [ 'ค้นหาตามชนิดไมม์' ],
	'Mostcategories'            => [ 'หมวดหมู่ที่มีการโยงไปหามากที่สุด' ],
	'Mostimages'                => [ 'ไฟล์ที่มีการโยงไปหามาก', 'ไฟล์ทีใช้มากที่สุด', 'ภาพที่ใช้มากที่สุด' ],
	'Mostlinked'                => [ 'หน้าที่มีการโยงไปหามาก' ],
	'Mostlinkedcategories'      => [ 'หมวดหมู่ที่มีการโยงไปหามาก', 'หมวดหมู่ที่ใช้มากที่สุด' ],
	'Mostlinkedtemplates'       => [ 'แม่แบบที่มีการโยงไปหามาก', 'แม่แบบที่ใช้มากที่สุด' ],
	'Mostrevisions'             => [ 'บทความที่ถูกแก้ไขมากที่สุด' ],
	'Movepage'                  => [ 'เปลี่ยนทาง' ],
	'Mycontributions'           => [ 'เรื่องที่ฉันเขียน' ],
	'MyLanguage'                => [ 'ภาษาของฉัน' ],
	'Mypage'                    => [ 'หน้าของฉัน' ],
	'Mytalk'                    => [ 'หน้าพูดคุยของฉัน' ],
	'Myuploads'                 => [ 'ไฟล์ที่อัปโหลดของฉัน' ],
	'Newimages'                 => [ 'ภาพใหม่' ],
	'Newpages'                  => [ 'หน้าใหม่' ],
	'PasswordReset'             => [ 'ตั้งรหัสผ่านใหม่' ],
	'PermanentLink'             => [ 'ลิงก์ถาวร' ],
	'Preferences'               => [ 'การตั้งค่า', 'ตั้งค่า' ],
	'Prefixindex'               => [ 'ดัชนีตามคำขึ้นต้น' ],
	'Protectedpages'            => [ 'หน้าที่ถูกป้องกัน' ],
	'Protectedtitles'           => [ 'หัวเรื่องที่ได้รับการป้องกัน' ],
	'Randompage'                => [ 'สุ่ม', 'สุ่มหน้า' ],
	'Randomredirect'            => [ 'สุ่มหน้าเปลี่ยนทาง' ],
	'Recentchanges'             => [ 'ปรับปรุงล่าสุด' ],
	'Recentchangeslinked'       => [ 'การปรับปรุงที่โยงมา' ],
	'Revisiondelete'            => [ 'ลบรุ่นการแก้ไข' ],
	'Search'                    => [ 'ค้นหา' ],
	'Shortpages'                => [ 'หน้าที่สั้นที่สุด' ],
	'Specialpages'              => [ 'หน้าพิเศษ' ],
	'Statistics'                => [ 'สถิติ' ],
	'Tags'                      => [ 'ป้ายกำกับ' ],
	'Unblock'                   => [ 'เลิกบล็อก' ],
	'Uncategorizedcategories'   => [ 'หมวดหมู่ที่ไม่ได้จัดหมวดหมู่' ],
	'Uncategorizedimages'       => [ 'ภาพที่ไม่ได้จัดหมวดหมู่' ],
	'Uncategorizedpages'        => [ 'หน้าที่ไม่ได้จัดหมวดหมู่' ],
	'Uncategorizedtemplates'    => [ 'แม่แบบที่ไม่ได้จัดหมวดหมู่' ],
	'Undelete'                  => [ 'เรียกคืน' ],
	'Unlockdb'                  => [ 'ปลดล็อกฐานข้อมูล' ],
	'Unusedcategories'          => [ 'หมวดหมู่ที่ไม่ได้ใช้' ],
	'Unusedimages'              => [ 'ภาพที่ไม่ได้ใช้' ],
	'Unusedtemplates'           => [ 'แม่แบบที่ไม่ได้ใช้' ],
	'Unwatchedpages'            => [ 'หน้าที่ไม่ได้ถูกเฝ้าดู' ],
	'Upload'                    => [ 'อัปโหลด' ],
	'Userlogin'                 => [ 'ล็อกอิน' ],
	'Userlogout'                => [ 'ล็อกเอาต์' ],
	'Userrights'                => [ 'สิทธิผู้ใช้' ],
	'Version'                   => [ 'เวอร์ชัน', 'เวอร์ชั่น' ],
	'Wantedcategories'          => [ 'หมวดหมู่ที่ต้องการ' ],
	'Wantedfiles'               => [ 'ไฟล์ที่ต้องการ' ],
	'Wantedpages'               => [ 'หน้าที่ต้องการ', 'การเชื่อมโยงเสีย' ],
	'Wantedtemplates'           => [ 'แม่แบบที่ต้องการ' ],
	'Watchlist'                 => [ 'รายการเฝ้าดู', 'เฝ้าดู' ],
	'Whatlinkshere'             => [ 'บทความที่โยงมา' ],
	'Withoutinterwiki'          => [ 'หน้าที่ไม่มีลิงก์ข้ามภาษา' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#เปลี่ยนทาง', '#REDIRECT' ],
	'notoc'                     => [ '0', '__ไม่มีสารบัญ__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__ไม่มีแกลเลอรี่__', '__NOGALLERY__' ],
	'noeditsection'             => [ '0', '__ไม่มีแก้เฉพาะส่วน__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'เดือนปัจจุบัน', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'ชื่อเดือนปัจจุบัน', 'CURRENTMONTHNAME' ],
];

$datePreferences = [
	'default',
	'thai',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

$defaultDateFormat = 'thai';

$dateFormats = [
	'thai time' => 'H:i',
	'thai date' => 'j F xkY',
	'thai both' => 'H:i, j F xkY',

	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'H:i, F j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'H:i, j F Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'H:i, Y F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

