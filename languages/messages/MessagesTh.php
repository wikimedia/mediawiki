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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'ภาพ' => NS_FILE,
	'คุยเรื่องภาพ' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ผู้ใช้ที่มีความเคลื่อนไหว' ),
	'Allmessages'               => array( 'ข้อความทั้งหมด' ),
	'Allpages'                  => array( 'หน้าทั้งหมด' ),
	'Ancientpages'              => array( 'บทความที่ไม่ได้แก้ไขนานที่สุด' ),
	'Badtitle'                  => array( 'ชื่อเรื่องไม่เหมาะสม' ),
	'Blankpage'                 => array( 'หน้าว่าง' ),
	'Block'                     => array( 'บล็อกไอพี' ),
	'Booksources'               => array( 'แหล่งหนังสือ' ),
	'BrokenRedirects'           => array( 'เปลี่ยนทางเสีย' ),
	'Categories'                => array( 'หมวดหมู่' ),
	'ChangeEmail'               => array( 'เปลี่ยนอีเมล' ),
	'ChangePassword'            => array( 'เปลี่ยนรหัสผ่าน' ),
	'ComparePages'              => array( 'เปรียบเทียบหน้า' ),
	'Confirmemail'              => array( 'ยืนยันอีเมล' ),
	'Contributions'             => array( 'เรื่องที่เขียน' ),
	'CreateAccount'             => array( 'สร้างบัญชีผู้ใช้ใหม่' ),
	'Deadendpages'              => array( 'หน้าสุดทาง' ),
	'DeletedContributions'      => array( 'การแก้ไขที่ถูกลบ' ),
	'DoubleRedirects'           => array( 'เปลี่ยนทางซ้ำซ้อน' ),
	'EditWatchlist'             => array( 'แก้ไขรายการเฝ้าดู' ),
	'Emailuser'                 => array( 'อีเมลผู้ใช้' ),
	'Export'                    => array( 'ส่งออก' ),
	'Fewestrevisions'           => array( 'บทความที่ถูกแก้ไขน้อยที่สุด' ),
	'FileDuplicateSearch'       => array( 'ค้นหาไฟล์ซ้ำซ้อน' ),
	'Filepath'                  => array( 'พาธของไฟล์', 'ตำแหน่งไฟล์' ),
	'Import'                    => array( 'นำเข้า' ),
	'Invalidateemail'           => array( 'ยกเลิกการยืนยันทางอีเมล' ),
	'JavaScriptTest'            => array( 'ทดสอบจาวาสคริปต์' ),
	'BlockList'                 => array( 'รายชื่อผู้ใช้ที่ถูกบล็อก', 'รายการบล็อก', 'รายชื่อไอพีที่ถูกบล็อก' ),
	'LinkSearch'                => array( 'ค้นหาเว็บลิงก์' ),
	'Listadmins'                => array( 'รายชื่อผู้ดูแล' ),
	'Listbots'                  => array( 'รายชื่อบอต' ),
	'Listfiles'                 => array( 'รายชื่อภาพ' ),
	'Listgrouprights'           => array( 'รายชื่อสิทธิกลุ่มผู้ใช้งาน' ),
	'Listredirects'             => array( 'รายชื่อหน้าเปลี่ยนทาง' ),
	'Listusers'                 => array( 'รายชื่อผู้ใช้' ),
	'Lockdb'                    => array( 'ล็อกฐานข้อมูล' ),
	'Log'                       => array( 'ปูม' ),
	'Lonelypages'               => array( 'หน้าที่โยงไปไม่ถึง' ),
	'Longpages'                 => array( 'หน้าที่ยาวที่สุด' ),
	'MergeHistory'              => array( 'รวมประวัติ' ),
	'MIMEsearch'                => array( 'ค้นหาตามชนิดไมม์' ),
	'Mostcategories'            => array( 'หมวดหมู่ที่มีการโยงไปหามากที่สุด' ),
	'Mostimages'                => array( 'ไฟล์ที่มีการโยงไปหามาก', 'ไฟล์ทีใช้มากที่สุด', 'ภาพที่ใช้มากที่สุด' ),
	'Mostlinked'                => array( 'หน้าที่มีการโยงไปหามาก' ),
	'Mostlinkedcategories'      => array( 'หมวดหมู่ที่มีการโยงไปหามาก', 'หมวดหมู่ที่ใช้มากที่สุด' ),
	'Mostlinkedtemplates'       => array( 'แม่แบบที่มีการโยงไปหามาก', 'แม่แบบที่ใช้มากที่สุด' ),
	'Mostrevisions'             => array( 'บทความที่ถูกแก้ไขมากที่สุด' ),
	'Movepage'                  => array( 'เปลี่ยนทาง' ),
	'Mycontributions'           => array( 'เรื่องที่ฉันเขียน' ),
	'MyLanguage'                => array( 'ภาษาของฉัน' ),
	'Mypage'                    => array( 'หน้าของฉัน' ),
	'Mytalk'                    => array( 'หน้าพูดคุยของฉัน' ),
	'Myuploads'                 => array( 'ไฟล์ที่อัปโหลดของฉัน' ),
	'Newimages'                 => array( 'ภาพใหม่' ),
	'Newpages'                  => array( 'หน้าใหม่' ),
	'PasswordReset'             => array( 'ตั้งรหัสผ่านใหม่' ),
	'PermanentLink'             => array( 'ลิงก์ถาวร' ),

	'Preferences'               => array( 'การตั้งค่า', 'ตั้งค่า' ),
	'Prefixindex'               => array( 'ดัชนีตามคำขึ้นต้น' ),
	'Protectedpages'            => array( 'หน้าที่ถูกป้องกัน' ),
	'Protectedtitles'           => array( 'หัวเรื่องที่ได้รับการป้องกัน' ),
	'Randompage'                => array( 'สุ่ม', 'สุ่มหน้า' ),
	'Randomredirect'            => array( 'สุ่มหน้าเปลี่ยนทาง' ),
	'Recentchanges'             => array( 'ปรับปรุงล่าสุด' ),
	'Recentchangeslinked'       => array( 'การปรับปรุงที่โยงมา' ),
	'Revisiondelete'            => array( 'ลบรุ่นการแก้ไข' ),
	'Search'                    => array( 'ค้นหา' ),
	'Shortpages'                => array( 'หน้าที่สั้นที่สุด' ),
	'Specialpages'              => array( 'หน้าพิเศษ' ),
	'Statistics'                => array( 'สถิติ' ),
	'Tags'                      => array( 'ป้ายกำกับ' ),
	'Unblock'                   => array( 'เลิกบล็อก' ),
	'Uncategorizedcategories'   => array( 'หมวดหมู่ที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedimages'       => array( 'ภาพที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedpages'        => array( 'หน้าที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedtemplates'    => array( 'แม่แบบที่ไม่ได้จัดหมวดหมู่' ),
	'Undelete'                  => array( 'เรียกคืน' ),
	'Unlockdb'                  => array( 'ปลดล็อกฐานข้อมูล' ),
	'Unusedcategories'          => array( 'หมวดหมู่ที่ไม่ได้ใช้' ),
	'Unusedimages'              => array( 'ภาพที่ไม่ได้ใช้' ),
	'Unusedtemplates'           => array( 'แม่แบบที่ไม่ได้ใช้' ),
	'Unwatchedpages'            => array( 'หน้าที่ไม่ได้ถูกเฝ้าดู' ),
	'Upload'                    => array( 'อัปโหลด' ),
	'Userlogin'                 => array( 'ล็อกอิน' ),
	'Userlogout'                => array( 'ล็อกเอาต์' ),
	'Userrights'                => array( 'สิทธิผู้ใช้' ),
	'Version'                   => array( 'เวอร์ชัน', 'เวอร์ชั่น' ),
	'Wantedcategories'          => array( 'หมวดหมู่ที่ต้องการ' ),
	'Wantedfiles'               => array( 'ไฟล์ที่ต้องการ' ),
	'Wantedpages'               => array( 'หน้าที่ต้องการ', 'การเชื่อมโยงเสีย' ),
	'Wantedtemplates'           => array( 'แม่แบบที่ต้องการ' ),
	'Watchlist'                 => array( 'รายการเฝ้าดู', 'เฝ้าดู' ),
	'Whatlinkshere'             => array( 'บทความที่โยงมา' ),
	'Withoutinterwiki'          => array( 'หน้าที่ไม่มีลิงก์ข้ามภาษา' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#เปลี่ยนทาง', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ไม่มีสารบัญ__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__ไม่มีแกลเลอรี่__', '__NOGALLERY__' ),
	'noeditsection'             => array( '0', '__ไม่มีแก้เฉพาะส่วน__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'เดือนปัจจุบัน', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'ชื่อเดือนปัจจุบัน', 'CURRENTMONTHNAME' ),
);

$datePreferences = array(
	'default',
	'thai',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'thai';

$dateFormats = array(
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
);

