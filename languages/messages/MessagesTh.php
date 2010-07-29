<?php
/** Thai (ไทย)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ans
 * @author Harley Hartwell
 * @author Horus
 * @author LMNOP at Thai Wikipedia (manop@itshee.com) since July 2007
 * @author Manop
 * @author Mopza
 * @author Octahedron80
 * @author Passawuth
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
	'DoubleRedirects'           => array( 'เปลี่ยนทางซ้ำซ้อน' ),
	'BrokenRedirects'           => array( 'เปลี่ยนทางเสีย' ),
	'Disambiguations'           => array( 'แก้ความกำกวม' ),
	'Userlogin'                 => array( 'ล็อกอิน' ),
	'Userlogout'                => array( 'ล็อกเอาต์' ),
	'CreateAccount'             => array( 'สร้างบัญชีผู้ใช้ใหม่' ),
	'Preferences'               => array( 'การตั้งค่า', 'ตั้งค่า' ),
	'Watchlist'                 => array( 'รายการเฝ้าดู', 'เฝ้าดู' ),
	'Recentchanges'             => array( 'ปรับปรุงล่าสุด' ),
	'Upload'                    => array( 'อัปโหลด' ),
	'Listfiles'                 => array( 'รายชื่อภาพ' ),
	'Newimages'                 => array( 'ภาพใหม่' ),
	'Listusers'                 => array( 'รายชื่อผู้ใช้' ),
	'Listgrouprights'           => array( 'รายชื่อสิทธิกลุ่มผู้ใช้งาน' ),
	'Statistics'                => array( 'สถิติ' ),
	'Randompage'                => array( 'สุ่ม', 'สุ่มหน้า' ),
	'Lonelypages'               => array( 'หน้าที่โยงไปไม่ถึง' ),
	'Uncategorizedpages'        => array( 'หน้าที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedcategories'   => array( 'หมวดหมู่ที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedimages'       => array( 'ภาพที่ไม่ได้จัดหมวดหมู่' ),
	'Uncategorizedtemplates'    => array( 'แม่แบบที่ไม่ได้จัดหมวดหมู่' ),
	'Unusedcategories'          => array( 'หมวดหมู่ที่ไม่ได้ใช้' ),
	'Unusedimages'              => array( 'ภาพที่ไม่ได้ใช้' ),
	'Wantedpages'               => array( 'หน้าที่ต้องการ', 'การเชื่อมโยงเสีย' ),
	'Wantedcategories'          => array( 'หมวดหมู่ที่ต้องการ' ),
	'Wantedfiles'               => array( 'ไฟล์ที่ต้องการ' ),
	'Wantedtemplates'           => array( 'แม่แบบที่ต้องการ' ),
	'Mostlinked'                => array( 'หน้าที่มีการโยงไปหามาก' ),
	'Mostlinkedcategories'      => array( 'หมวดหมู่ที่มีการโยงไปหามาก', 'หมวดหมู่ที่ใช้มากที่สุด' ),
	'Mostlinkedtemplates'       => array( 'แม่แบบที่มีการโยงไปหามาก', 'แม่แบบที่ใช้มากที่สุด' ),
	'Mostimages'                => array( 'ไฟล์ที่มีการโยงไปหามาก', 'ไฟล์ทีใช้มากที่สุด', 'ภาพที่ใช้มากที่สุด' ),
	'Mostcategories'            => array( 'หมวดหมู่ที่มีการโยงไปหามากที่สุด' ),
	'Mostrevisions'             => array( 'บทความที่ถูกแก้ไขมากที่สุด' ),
	'Fewestrevisions'           => array( 'บทความที่ถูกแก้ไขน้อยที่สุด' ),
	'Shortpages'                => array( 'หน้าที่สั้นที่สุด' ),
	'Longpages'                 => array( 'หน้าที่ยาวที่สุด' ),
	'Newpages'                  => array( 'หน้าใหม่' ),
	'Ancientpages'              => array( 'บทความที่ไม่ได้แก้ไขนานที่สุด' ),
	'Deadendpages'              => array( 'หน้าสุดทาง' ),
	'Protectedpages'            => array( 'หน้าที่ถูกป้องกัน' ),
	'Protectedtitles'           => array( 'หัวเรื่องที่ได้รับการป้องกัน' ),
	'Allpages'                  => array( 'หน้าทั้งหมด' ),
	'Prefixindex'               => array( 'ดัชนีตามคำขึ้นต้น' ),
	'Ipblocklist'               => array( 'รายชื่อผู้ใช้ที่ถูกบล็อก', 'รายการบล็อก', 'รายชื่อไอพีที่ถูกบล็อก' ),
	'Specialpages'              => array( 'หน้าพิเศษ' ),
	'Contributions'             => array( 'เรื่องที่เขียน' ),
	'Emailuser'                 => array( 'อีเมลผู้ใช้' ),
	'Confirmemail'              => array( 'ยืนยันอีเมล' ),
	'Whatlinkshere'             => array( 'บทความที่โยงมา' ),
	'Recentchangeslinked'       => array( 'การปรับปรุงที่โยงมา' ),
	'Movepage'                  => array( 'เปลี่ยนทาง' ),
	'Blockme'                   => array( 'บล็อกฉัน' ),
	'Booksources'               => array( 'แหล่งหนังสือ' ),
	'Categories'                => array( 'หมวดหมู่' ),
	'Export'                    => array( 'ส่งออก' ),
	'Version'                   => array( 'เวอร์ชั่น' ),
	'Allmessages'               => array( 'ข้อความทั้งหมด' ),
	'Log'                       => array( 'ปูม' ),
	'Blockip'                   => array( 'บล็อกไอพี' ),
	'Undelete'                  => array( 'เรียกคืน' ),
	'Import'                    => array( 'นำเข้า' ),
	'Lockdb'                    => array( 'ล็อกฐานข้อมูล' ),
	'Unlockdb'                  => array( 'ปลดล็อกฐานข้อมูล' ),
	'Userrights'                => array( 'สิทธิผู้ใช้' ),
	'MIMEsearch'                => array( 'ค้นหาตามชนิดไมม์' ),
	'FileDuplicateSearch'       => array( 'ค้นหาไฟล์ซ้ำซ้อน' ),
	'Unwatchedpages'            => array( 'หน้าที่ไม่ได้ถูกเฝ้าดู' ),
	'Listredirects'             => array( 'รายชื่อหน้าเปลี่ยนทาง' ),
	'Unusedtemplates'           => array( 'แม่แบบที่ไม่ได้ใช้' ),
	'Randomredirect'            => array( 'สุ่มหน้าเปลี่ยนทาง' ),
	'Mypage'                    => array( 'หน้าของฉัน' ),
	'Mytalk'                    => array( 'หน้าพูดคุยของฉัน' ),
	'Mycontributions'           => array( 'เรื่องที่ฉันเขียน' ),
	'Listadmins'                => array( 'รายชื่อผู้ดูแล' ),
	'Listbots'                  => array( 'รายชื่อบอต' ),
	'Popularpages'              => array( 'หน้าที่ได้รับความนิยม' ),
	'Search'                    => array( 'ค้นหา' ),
	'Resetpass'                 => array( 'ตั้งรหัสผ่านใหม่' ),
	'Withoutinterwiki'          => array( 'หน้าที่ไม่มีลิงก์ข้ามภาษา' ),
	'MergeHistory'              => array( 'รวมประวัติ' ),
	'Filepath'                  => array( 'พาธของไฟล์', 'ตำแหน่งไฟล์' ),
	'Invalidateemail'           => array( 'ยกเลิกการยืนยันทางอีเมล' ),
	'Blankpage'                 => array( 'หน้าว่าง' ),
	'LinkSearch'                => array( 'ค้นหาเว็บลิงก์' ),
	'DeletedContributions'      => array( 'การแก้ไขที่ถูกลบ' ),
	'Tags'                      => array( 'ป้ายกำกับ' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#เปลี่ยนทาง', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ไม่มีสารบัญ__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__ไม่มีแกลเลอรี่__', '__NOGALLERY__' ),
	'noeditsection'         => array( '0', '__ไม่มีแก้เฉพาะส่วน__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'เดือนปัจจุบัน', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'ชื่อเดือนปัจจุบัน', 'CURRENTMONTHNAME' ),
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

$linkTrail = '/^([a-z]+)(.*)\$/sD';

$messages = array(
# User preference toggles
'tog-underline'               => 'ขีดเส้นใต้ลิงก์',
'tog-highlightbroken'         => 'จัดลิงก์ที่ไม่มีใน<a href="" class="new">ลักษณะนี้</a> (หรือลักษณะนี้<a href="" class="internal">?</a>)',
'tog-justify'                 => 'จัดย่อหน้าเต็มบรรทัด',
'tog-hideminor'               => 'ไม่แสดงการแก้ไขเล็กน้อยในหน้าปรับปรุงล่าสุด',
'tog-hidepatrolled'           => 'ซ่อนการแก้ไขที่ตรวจแล้วในหน้าปรับปรุงล่าสุด',
'tog-newpageshidepatrolled'   => 'ซ่อนหน้าที่ตรวจแล้วจากรายชื่อหน้าใหม่',
'tog-extendwatchlist'         => 'คลี่รายการเฝ้าดูออก เพื่อแสดงการเปลี่ยนแปลงทั้งหมด ไม่เพียงแค่การเปลี่ยนแปลงล่าสุด',
'tog-usenewrc'                => 'ใช้หน้าปรับปรุงล่าสุดรุ่นเสริม (ต้องการจาวาสคริปต์)',
'tog-numberheadings'          => 'ใส่ตัวเลขหัวข้อในสารบัญ',
'tog-showtoolbar'             => 'แสดงเครื่องมือแก้ไข',
'tog-editondblclick'          => 'แก้ไขหน้าโดยการดับเบิลคลิก (จาวาสคริปต์)',
'tog-editsection'             => 'เปิดการแก้ไขเฉพาะส่วนโดยใช้ลิงก์ [แก้ไข]',
'tog-editsectiononrightclick' => 'เปิดการแก้ไขเฉพาะส่วนโดยคลิกขวาที่หัวข้อ (จาวาสคริปต์)',
'tog-showtoc'                 => 'แสดงสารบัญ<br />(สำหรับหน้าที่มีมากกว่า 3 หัวข้อ)',
'tog-rememberpassword'        => 'จำการล็อกอินบนคอมพิวเตอร์นี้',
'tog-watchcreations'          => 'นำหน้าที่สร้างใส่รายการเฝ้าดู',
'tog-watchdefault'            => 'นำหน้าที่แก้ไขใส่รายการเฝ้าดู',
'tog-watchmoves'              => 'นำหน้าที่เปลี่ยนชื่อใส่รายการเฝ้าดู',
'tog-watchdeletion'           => 'นำหน้าที่ลบใส่รายการเฝ้าดู',
'tog-previewontop'            => 'แสดงตัวอย่างการแก้ไขก่อนกล่องแก้ไข',
'tog-previewonfirst'          => 'แสดงตัวอย่างการแก้ไขสำหรับการแก้ไขครั้งแรก',
'tog-nocache'                 => 'ระงับการบันทึกหน้าลงแคช',
'tog-enotifwatchlistpages'    => 'หน้าที่เฝ้าดูมีการแก้ไข',
'tog-enotifusertalkpages'     => 'หน้าพูดคุยส่วนตัวมีการแก้ไข',
'tog-enotifminoredits'        => 'แม้ว่าการแก้ไขจะเป็นการแก้ไขเล็กน้อย',
'tog-enotifrevealaddr'        => 'เผยที่อยู่อีเมลในอีเมลที่ชี้แจง',
'tog-shownumberswatching'     => 'แสดงจำนวนผู้ใช้ที่เฝ้าดูหน้านี้',
'tog-oldsig'                  => 'แสดงผลลายเซ็นเดิม:',
'tog-fancysig'                => 'ใช้คำสั่งวิกิที่ปรากฏในลายเซ็นนี้ (ไม่มีการสร้างลิงก์อัตโนมัติ)',
'tog-externaleditor'          => 'กำหนดค่ามาตรฐาน ให้แก้ไขโดยใช้โปรแกรมภายนอกตัวอื่น',
'tog-externaldiff'            => 'ใช้ซอฟต์แวร์ในเครื่องแก้ไขวิกิ',
'tog-showjumplinks'           => 'เปิดใช้งาน "กระโดด" อัตโนมัติไปตามลิงก์',
'tog-uselivepreview'          => 'แสดงตัวอย่างการแก้ไขแบบทันที (จาวาสคริปต์) (ทดลอง)',
'tog-forceeditsummary'        => 'เตือนเมื่อช่องสรุปการแก้ไขว่าง',
'tog-watchlisthideown'        => 'ไม่แสดงการแก้ไขของตนเองจากรายการเฝ้าดูของตนเอง',
'tog-watchlisthidebots'       => 'ไม่แสดงการแก้ไขของบอตจากรายการเฝ้าดูของตนเอง',
'tog-watchlisthideminor'      => 'ไม่แสดงการแก้ไขเล็กน้อยจากรายการเฝ้าดูของตนเอง',
'tog-watchlisthideliu'        => 'ซ่อนการแก้ไขโดยผู้ใช้ล็อกอินจากรายการเฝ้าดู',
'tog-watchlisthideanons'      => 'ซ่อนการแก้ไขโดยผู้ใช้นิรนามจากรายการเฝ้าดู',
'tog-watchlisthidepatrolled'  => 'ซ่อนการแก้ไขที่ตรวจแล้วจากรายการเฝ้าดู',
'tog-ccmeonemails'            => 'ส่งสำเนาอีเมลกลับมาทุกครั้งที่ส่งหาคนอื่น',
'tog-diffonly'                => 'ไม่แสดงเนื้อหาใต้ส่วนต่างการแก้ไข',
'tog-showhiddencats'          => 'แสดงหมวดหมู่ที่ซ่อนอยู่',
'tog-norollbackdiff'          => 'ข้ามแสดงความเปลี่ยนแปลงหลังจากดำเนินการย้อนกลับ',

'underline-always'  => 'เสมอ',
'underline-never'   => 'ไม่เคย',
'underline-default' => 'ค่าปริยายตามเว็บเบราว์เซอร์',

# Font style option in Special:Preferences
'editfont-style'     => 'รูปแบบของแบบตัวอักษรในกล่องแก้ไข:',
'editfont-default'   => 'ค่าตั้งต้นของ browser',
'editfont-monospace' => 'ชุดอักษรแบบความกว้างคงที่',
'editfont-sansserif' => 'ชุดอักษรแบบไม่มีเชิง',
'editfont-serif'     => 'ชุดอักษรแบบมีเชิง',

# Dates
'sunday'        => 'วันอาทิตย์',
'monday'        => 'วันจันทร์',
'tuesday'       => 'วันอังคาร',
'wednesday'     => 'วันพุธ',
'thursday'      => 'วันพฤหัสบดี',
'friday'        => 'วันศุกร์',
'saturday'      => 'วันเสาร์',
'sun'           => 'อา.',
'mon'           => 'จ.',
'tue'           => 'อ.',
'wed'           => 'พ.',
'thu'           => 'พฤ.',
'fri'           => 'ศ.',
'sat'           => 'ส.',
'january'       => 'มกราคม',
'february'      => 'กุมภาพันธ์',
'march'         => 'มีนาคม',
'april'         => 'เมษายน',
'may_long'      => 'พฤษภาคม',
'june'          => 'มิถุนายน',
'july'          => 'กรกฎาคม',
'august'        => 'สิงหาคม',
'september'     => 'กันยายน',
'october'       => 'ตุลาคม',
'november'      => 'พฤศจิกายน',
'december'      => 'ธันวาคม',
'january-gen'   => 'มกราคม',
'february-gen'  => 'กุมภาพันธ์',
'march-gen'     => 'มีนาคม',
'april-gen'     => 'เมษายน',
'may-gen'       => 'พฤษภาคม',
'june-gen'      => 'มิถุนายน',
'july-gen'      => 'กรกฎาคม',
'august-gen'    => 'สิงหาคม',
'september-gen' => 'กันยายน',
'october-gen'   => 'ตุลาคม',
'november-gen'  => 'พฤศจิกายน',
'december-gen'  => 'ธันวาคม',
'jan'           => 'ม.ค.',
'feb'           => 'ก.พ.',
'mar'           => 'มี.ค.',
'apr'           => 'เม.ย.',
'may'           => 'พ.ค.',
'jun'           => 'มิ.ย.',
'jul'           => 'ก.ค.',
'aug'           => 'ส.ค.',
'sep'           => 'ก.ย.',
'oct'           => 'ต.ค.',
'nov'           => 'พ.ย.',
'dec'           => 'ธ.ค.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|หมวดหมู่|หมวดหมู่}}',
'category_header'                => 'เนื้อหาในหมวดหมู่ "$1"',
'subcategories'                  => 'หมวดหมู่ย่อย',
'category-media-header'          => 'สื่อในหมวดหมู่ "$1"',
'category-empty'                 => "''หมวดหมู่นี้ว่าง ไม่มีบทความใดอยู่''",
'hidden-categories'              => '{{PLURAL:$1|หมวดหมู่ที่ซ่อนอยู่|หมวดหมู่ที่ซ่อนอยู่}}',
'hidden-category-category'       => 'หมวดหมู่ที่ซ่อนอยู่',
'category-subcat-count'          => '{{PLURAL:$2|หมวดหมู่นี้มีหมวดหมู่ย่อยเพียงหมวดหมู่เดียว|หมวดหมู่นี้มี {{PLURAL:$1|หมวดหมู่ย่อย|$1 หมวดหมู่ย่อย}} จากทั้งหมด $2 หมวดหมู่}}',
'category-subcat-count-limited'  => 'หมวดหมู่นี้มี {{PLURAL:$1|หมวดหมู่ย่อยเพียงหมวดหมู่เดียว|$1 หมวดหมู่ย่อย}}',
'category-article-count'         => '{{PLURAL:$2|หมวดหมู่นี้มีหน้าอยู่เพียงหน้าเดียว|มี {{PLURAL:$1|หน้าเดียว|$1 หน้า}} ในหมวดหมู่นี้ เต็ม $2}}',
'category-article-count-limited' => '{{PLURAL:$1|หน้า|หน้า}}ต่อไปนี้อยู่ในหมวดหมู่นี้',
'category-file-count'            => '{{PLURAL:$2|มีไฟล์เดียวในหมวดหมู่นี้|มี {{PLURAL:$1|ไฟล์|ไฟล์}} ในหมวดหมู่นี้จากทั้งหมด $2 ไฟล์}}',
'category-file-count-limited'    => '{{PLURAL:$1|ไฟล์|ไฟล์}}ต่อไปนี้อยู่ในหมวดหมู่นี้',
'listingcontinuesabbrev'         => '(ต่อ)',
'index-category'                 => 'หน้าที่มีดัชนี',
'noindex-category'               => 'หน้าที่ไม่มีดัชนี',

'mainpagetext'      => "'''ซอฟต์แวร์มีเดียวิกิได้ถูกติดตั้งเรียบร้อย'''",
'mainpagedocfooter' => 'ศึกษา[http://meta.wikimedia.org/wiki/Help:Contents คู่มือการใช้งาน] สำหรับเริ่มต้นใช้งานซอฟต์แวร์วิกิ

== เริ่มต้น ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings รายการการปรับแต่งระบบ] (ภาษาอังกฤษ)
* [http://www.mediawiki.org/wiki/Manual:FAQ คำถามที่ถามบ่อยในมีเดียวิกิ] (ภาษาอังกฤษ)
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce เมลลิงลิสต์ของมีเดียวิกิ]',

'about'         => 'เว็บไซต์นี้',
'article'       => 'หน้าเนื้อหา',
'newwindow'     => '(เปิดหน้าต่างใหม่)',
'cancel'        => 'ยกเลิก',
'moredotdotdot' => 'ดูเพิ่ม...',
'mypage'        => 'หน้าของฉัน',
'mytalk'        => 'หน้าพูดคุยของฉัน',
'anontalk'      => 'พูดคุยกับไอพีนี้',
'navigation'    => 'ป้ายบอกทาง',
'and'           => '&#32;และ',

# Cologne Blue skin
'qbfind'         => 'ค้นหา',
'qbbrowse'       => 'สืบค้น',
'qbedit'         => 'แก้ไข',
'qbpageoptions'  => 'หน้านี้',
'qbpageinfo'     => 'บริบท',
'qbmyoptions'    => 'หน้าของฉัน',
'qbspecialpages' => 'หน้าพิเศษ',
'faq'            => 'คำถามที่ถามบ่อย',
'faqpage'        => 'Project:คำถามที่ถามบ่อย',

# Vector skin
'vector-action-addsection'   => 'เพิ่มหัวข้อใหม่',
'vector-action-delete'       => 'ลบ',
'vector-action-move'         => 'ย้าย',
'vector-action-protect'      => 'ป้องกัน',
'vector-action-undelete'     => 'ยกเลิกการลบ',
'vector-action-unprotect'    => 'ยกเลิกการป้องกัน',
'vector-namespace-category'  => 'หมวดหมู่',
'vector-namespace-help'      => 'หน้าช่วยเหลือ',
'vector-namespace-image'     => 'ไฟล์',
'vector-namespace-main'      => 'หน้า',
'vector-namespace-media'     => 'หน้าที่เป็นสื่อ',
'vector-namespace-mediawiki' => 'ข้อความ',
'vector-namespace-project'   => 'หน้าโครงการ',
'vector-namespace-special'   => 'หน้าพิเศษ',
'vector-namespace-talk'      => 'สนทนา',
'vector-namespace-template'  => 'แม่แบบ',
'vector-namespace-user'      => 'หน้าของผู้ใช้',
'vector-view-create'         => 'สร้าง',
'vector-view-edit'           => 'แก้ไข',
'vector-view-history'        => 'ดูประวัติ',
'vector-view-view'           => 'อ่าน',
'vector-view-viewsource'     => 'ดูโค้ด',
'actions'                    => 'การกระทำ',
'namespaces'                 => 'เนมสเปซ',
'variants'                   => 'สิ่งที่แตกต่าง',

'errorpagetitle'    => 'มีข้อผิดพลาด',
'returnto'          => 'กลับไปสู่ $1',
'tagline'           => 'จาก {{SITENAME}}',
'help'              => 'ช่วยเหลือ',
'search'            => 'สืบค้น',
'searchbutton'      => 'สืบค้น',
'go'                => 'ไป',
'searcharticle'     => 'ไป',
'history'           => 'ประวัติหน้า',
'history_short'     => 'ประวัติ',
'updatedmarker'     => 'ความเปลี่ยนแปลงตั้งแต่ครั้งล่าสุด',
'info_short'        => 'ข้อมูล',
'printableversion'  => 'หน้าสำหรับพิมพ์',
'permalink'         => 'ลิงก์ถาวร',
'print'             => 'พิมพ์',
'edit'              => 'แก้ไข',
'create'            => 'สร้าง',
'editthispage'      => 'แก้ไขหน้านี้',
'create-this-page'  => 'สร้างหน้านี้',
'delete'            => 'ลบ',
'deletethispage'    => 'ลบหน้านี้',
'undelete_short'    => 'เรียกคืน {{PLURAL:$1|1 การแก้ไข|$1 การแก้ไข}}',
'protect'           => 'ล็อก',
'protect_change'    => 'เปลี่ยน',
'protectthispage'   => 'ล็อกหน้านี้',
'unprotect'         => 'ปลดล็อก',
'unprotectthispage' => 'ปลดล็อกหน้านี้',
'newpage'           => 'หน้าใหม่',
'talkpage'          => 'พูดคุยหน้านี้',
'talkpagelinktext'  => 'พูดคุย',
'specialpage'       => 'หน้าพิเศษ',
'personaltools'     => 'เครื่องมือส่วนตัว',
'postcomment'       => 'หัวข้อใหม่',
'articlepage'       => 'แสดงเนื้อหาของหน้า',
'talk'              => 'อภิปราย',
'views'             => 'ดู',
'toolbox'           => 'เครื่องมือเพิ่ม',
'userpage'          => 'ดูหน้าผู้ใช้',
'projectpage'       => 'ดูหน้าโครงการ',
'imagepage'         => 'ดูหน้ารายละเอียดไฟล์',
'mediawikipage'     => 'ดูหน้าข้อความ',
'templatepage'      => 'ดูหน้าแม่แบบ',
'viewhelppage'      => 'ดูหน้าคำอธิบาย',
'categorypage'      => 'ดูหน้าหมวดหมู่',
'viewtalkpage'      => 'ดูการพูดคุย',
'otherlanguages'    => 'ในภาษาอื่น',
'redirectedfrom'    => '(เปลี่ยนทางจาก $1)',
'redirectpagesub'   => 'หน้าเปลี่ยนทาง',
'lastmodifiedat'    => 'หน้านี้แก้ไขล่าสุดเมื่อวันที่  $1 เวลา $2',
'viewcount'         => 'หน้านี้มีการเข้าชม {{PLURAL:$1|1 ครั้ง|$1 ครั้ง}}',
'protectedpage'     => 'หน้าถูกล็อก',
'jumpto'            => 'ข้ามไปที่:',
'jumptonavigation'  => 'นำทาง',
'jumptosearch'      => 'สืบค้น',
'view-pool-error'   => 'ขออภัย ขณะนี้มีผู้ใช้งานเซิร์ฟเวอร์มากเกินที่จะรับได้
ผู้ที่พยายามเข้าดูหน้านี้มีจำนวนมากจนเกินไป
กรุณารอสักครู่ก่อนที่จะเข้าดูหน้านี้อีกครั้งหนึ่ง

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'เกี่ยวกับ {{SITENAME}}',
'aboutpage'            => 'Project:เกี่ยวกับเว็บไซต์',
'copyright'            => 'เนื้อหาในหน้านี้อยู่ภายใต้ลิขสิทธิ์แบบ $1',
'copyrightpage'        => '{{ns:project}}:ลิขสิทธิ์',
'currentevents'        => 'เหตุการณ์ปัจจุบัน',
'currentevents-url'    => 'Project:เหตุการณ์ปัจจุบัน',
'disclaimers'          => 'ข้อปฏิเสธความรับผิดชอบ',
'disclaimerpage'       => 'Project:ข้อปฏิเสธความรับผิดชอบ',
'edithelp'             => 'คำอธิบายการแก้ไข',
'edithelppage'         => 'Help:การแก้ไข',
'helppage'             => 'Help:วิธีการใช้งาน',
'mainpage'             => 'หน้าหลัก',
'mainpage-description' => 'หน้าหลัก',
'policy-url'           => 'Project:นโยบาย',
'portal'               => 'ศูนย์รวมชุมชน',
'portal-url'           => 'Project:ศูนย์รวมชุมชน',
'privacy'              => 'นโยบายสิทธิส่วนบุคคล',
'privacypage'          => 'Project:นโยบายสิทธิส่วนบุคคล',

'badaccess'        => 'มีข้อผิดพลาดในการใช้สิทธิ',
'badaccess-group0' => 'คุณไม่ได้รับอนุญาตให้กระทำสิ่งที่ร้องขอนี้',
'badaccess-groups' => 'การกระทำที่ร้องขอนี้สงวนไว้เฉพาะผู้ใช้{{PLURAL:$2|จากกลุ่ม|จากกลุ่มใดกลุ่มหนึ่ง ดังนี้}}: $1',

'versionrequired'     => 'ต้องการมีเดียวิกิรุ่น $1',
'versionrequiredtext' => 'ต้องการมีเดียวิกิรุ่น $1 สำหรับใช้งานหน้านี้ ดูเพิ่ม [[Special:Version|รุ่นซอฟต์แวร์]]',

'ok'                      => 'ตกลง',
'retrievedfrom'           => 'รับข้อมูลจาก "$1"',
'youhavenewmessages'      => 'คุณมี $1 ($2)',
'newmessageslink'         => 'ข้อความใหม่',
'newmessagesdifflink'     => 'ข้อความเข้ามาใหม่',
'youhavenewmessagesmulti' => 'คุณมีข้อความใหม่ที่ $1',
'editsection'             => 'แก้ไข',
'editold'                 => 'แก้ไข',
'viewsourceold'           => 'ดูโค้ด',
'editlink'                => 'แก้ไข',
'viewsourcelink'          => 'ดูโค้ด',
'editsectionhint'         => 'แก้ไขส่วน: $1',
'toc'                     => 'เนื้อหา',
'showtoc'                 => 'แสดง',
'hidetoc'                 => 'ซ่อน',
'thisisdeleted'           => 'แสดงหรือเรียกดู $1',
'viewdeleted'             => 'ดู $1',
'restorelink'             => '{{PLURAL:$1|1 การแก้ไขที่ถูกลบ|$1 การแก้ไขที่ถูกลบ}}',
'feedlinks'               => 'ฟีด',
'feed-invalid'            => 'ฟีดที่สมัครไม่ถูกชนิด',
'feed-unavailable'        => 'ฟีดไม่ถูกเปิดการใช้งาน',
'site-rss-feed'           => 'ฟีดอาร์เอสเอส $1',
'site-atom-feed'          => 'ฟีดอะตอม $1',
'page-rss-feed'           => 'ฟีดอาร์เอสเอส "$1"',
'page-atom-feed'          => 'ฟีดอะตอม "$1"',
'red-link-title'          => '$1 (หน้านี้ไม่มี)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'เนื้อหา',
'nstab-user'      => 'หน้าผู้ใช้',
'nstab-media'     => 'หน้าสื่อ',
'nstab-special'   => 'หน้าพิเศษ',
'nstab-project'   => 'หน้าโครงการ',
'nstab-image'     => 'ไฟล์',
'nstab-mediawiki' => 'ข้อความ',
'nstab-template'  => 'แม่แบบ',
'nstab-help'      => 'หน้าคำอธิบาย',
'nstab-category'  => 'หมวดหมู่',

# Main script and global functions
'nosuchaction'      => 'ไม่มีการกระทำดังกล่าว',
'nosuchactiontext'  => 'การกระทำที่กำหนดผ่านยูอาร์แอลดังกล่าวไม่สามารถใช้ได้
คุณอาจกรอกยูอาร์แอลผิด หรือ มาตามลิงก์ที่ไม่ถูกต้อง
หรืออาจจะเกิดจากข้อผิดพลาดในโปรแกรมซึ่ง {{SITENAME}} ใช้อยู่',
'nosuchspecialpage' => 'ไม่มีหน้าพิเศษดังกล่าว',
'nospecialpagetext' => 'หน้าพิเศษตามคำขอไม่ถูกต้อง รายชื่อของหน้าพิเศษดูได้ที่ [[Special:SpecialPages|รายชื่อหน้าพิเศษ]]',

# General errors
'error'                => 'ผิดพลาด',
'databaseerror'        => 'ความผิดพลาดที่ฐานข้อมูล',
'dberrortext'          => 'ไวยากรณ์ในการค้นฐานข้อมูลผิดพลาด
สาเหตุอาจเกิดจากบั๊กของซอฟต์แวร์
การค้นฐานข้อมูลล่าสุดกระทำเมื่อ:
<blockquote><tt>$1</tt></blockquote>
จากฟังก์ชัน "<tt>$2</tt>"
ฐานข้อมูลแจ้งข้อผิดพลาดว่า "<tt>$3: $4</tt>"',
'dberrortextcl'        => 'ไวยากรณ์ในการค้นฐานข้อมูลผิดพลาด
การค้นฐานข้อมูลล่าสุดกระทำเมื่อ:
"$1"
จากฟังก์ชัน "$2"
ฐานข้อมูลแจ้งข้อผิดพลาดว่า "$3: $4"',
'laggedslavemode'      => 'คำเตือน! ข้อมูลในหน้าอาจจะไม่ใช่ข้อมูลล่าสุด',
'readonly'             => 'ฐานข้อมูลถูกล็อก',
'enterlockreason'      => 'ใส่เหตุผลในการล็อก รวมถึงช่วงเวลาที่คาดว่าจะปลดล็อก',
'readonlytext'         => 'ฐานข้อมูลขณะนี้ถูกล็อกสำหรับการปรับปรุง แก้ไข หรือปรับปรุง เป็นระยะ หลังจากเสร็จแล้วสามารถใช้งานได้ตามปกติ

ผู้ดูแลระบบที่ทำการล็อกได้ให้คำอธิบายดังนี้: $1',
'missing-article'      => 'ฐานข้อมูลไม่พบเนื้อหาของหน้าที่ควรจะมี ในชื่อ "$1" $2

สาเหตุมักเกิดจากการเปรียบเทียบที่ล้าสมัย หรือประวัติการเชื่อมโยงไปยังหน้านั้นได้ถูกลบแล้ว

หากไม่ใช่กรณีดังกล่าว คุณอาจจะพบบั๊กในซอฟต์แวร์ กรุณารายงานต่อ[[Special:ListUsers/sysop|ผู้ดูแลระบบ]] โดยระบุ URL ไปด้วย',
'missingarticle-rev'   => '(รุ่น#: $1)',
'missingarticle-diff'  => '(ต่าง: $1, $2)',
'readonly_lag'         => 'ฐานข้อมูลถูกล็อกอัตโนมัติขณะที่เซิร์ฟเวอร์ฐานข้อมูลรองกำลังปรับปรุงตามฐานข้อมูลหลัก',
'internalerror'        => 'เกิดความผิดพลาดภายใน',
'internalerror_info'   => 'เกิดความผิดพลาดภายใน: $1',
'fileappenderror'      => 'ไม่สามารถต่อท้าย "$2" ด้วย "$1"',
'filecopyerror'        => 'ไม่สามารถคัดลอกไฟล์ "$1" ไปที่ "$2"',
'filerenameerror'      => 'ไม่สามารถเปลี่ยนชื่อไฟล์ "$1" เป็น "$2"',
'filedeleteerror'      => 'ไม่สามารถลบไฟล์ "$1"',
'directorycreateerror' => 'ไม่สามารถสร้างไดเรกทอรี "$1"',
'filenotfound'         => 'ไม่พบไฟล์ "$1"',
'fileexistserror'      => 'ไม่สามารถเขียนไฟล์ "$1" ได้ เนื่องจากมีไฟล์อยู่แล้ว',
'unexpected'           => 'ผลที่ไม่คาดคิด: "$1"="$2"',
'formerror'            => 'ผิดพลาด: ไม่สามารถส่งฟอร์มได้',
'badarticleerror'      => 'การกระทำนี้ไม่สามารถดำเนินการในหน้านี้ได้',
'cannotdelete'         => 'ไม่สามารถลบหน้าหรือไฟล์ "$1" (อาจมีผู้อื่นลบไปก่อนหน้านี้แล้ว)',
'badtitle'             => 'ชื่อหน้าไม่เหมาะสม',
'badtitletext'         => 'ชื่อหน้าที่ร้องขอไม่ถูกต้อง เป็นชื่อว่าง หรือชื่อที่ผิดพลาดเนื่องจากลิงก์ข้ามมาจากภาษาอื่น ชื่อที่ใช้อาจจะมีตัวอักษรที่ไม่สามารถถูกใช้เป็นชื่อได้',
'perfcached'           => 'ข้อมูลต่อไปนี้อาจเป็นข้อมูลเก่า ที่เก็บไว้ในแคชของระบบ',
'perfcachedts'         => 'ข้อมูลด้านล่างมาจากแคช ซึ่งปรับปรุงครั้งล่าสุดเมื่อ $1',
'querypage-no-updates' => 'ขณะนี้การปรับปรุงหน้านี้ถูกระงับ ข้อมูลในที่นี่จะไม่รีเฟรชเป็นข้อมูลปัจจุบัน',
'wrong_wfQuery_params' => 'พารามิเตอร์ที่ส่งไป wfQuery() ไม่ถูกต้อง<br />
ฟังก์ชั่น: $1<br />
คำค้น: $2',
'viewsource'           => 'ดูโค้ด',
'viewsourcefor'        => 'สำหรับ $1',
'actionthrottled'      => 'การทำสิ่งนี้ถูกระงับไว้ชั่วคราว',
'actionthrottledtext'  => 'เพื่อเป็นการป้องกันสแปม จึงได้มีการจำกัดสิ่งที่คุณกระทำนี้ไม่ให้ทำติดต่อกันหลายครั้งมากจนเกินไปในช่วงระยะเวลาสั้นๆ และในขณะนี้คุณได้ทำสิ่งนี้เกินขีดจำกัดแล้ว กรุณารอสักครู่แล้วลองใหม่อีกครั้ง',
'protectedpagetext'    => 'หน้านี้ถูกล็อกป้องกันการแก้ไข',
'viewsourcetext'       => 'โค้ดหน้านี้สามารถดูและนำไปคัดลอกได้:',
'protectedinterface'   => 'หน้านี้เป็นข้อความที่ใช้แสดงบนหน้าตาหรือส่วนติดต่อผู้ใช้ของซอฟต์แวร์ ถูกล็อกห้ามแก้ไขเพื่อป้องกันการก่อกวน',
'editinginterface'     => "'''คำเตือน:''' คุณกำลังแก้ไขข้อความที่ใช้แสดงบนหน้าตาหรือส่วนติดต่อผู้ใช้ของซอฟต์แวร์  การแก้ไขหน้านี้จะมีผลต่อการแสดงข้อความบนส่วนติดต่อผู้ใช้ของทุกคน  ถ้าคุณต้องการแปลหน้านี้ ให้ลองใช้บริการของ [http://translatewiki.net/wiki/Main_Page?setlang=th translatewiki.net] ซึ่งเป็นโครงการสำหรับแปลซอฟต์แวร์มีเดียวิกิ",
'sqlhidden'            => '(คำสั่ง SQL ซ่อนอยู่)',
'cascadeprotected'     => 'หน้านี้ได้รับการป้องกันจากการแก้ไข เนื่องจากหน้านี้ถูกใช้เป็นส่วนหนึ่งใน{{PLURAL:$1|หน้า $2 ซึ่งได้รับการป้องกันแบบ "ทบทุกลำดับขั้น"|หน้าซึ่งได้รับการป้องกันแบบ "ทบทุกลำดับขั้น" ดังต่อไปนี้: $2}}',
'namespaceprotected'   => "คุณไม่มีสิทธิในการแก้ไขหน้าในเนมสเปซ '''$1'''",
'customcssjsprotected' => 'คุณไม่ได้รับสิทธิในการแก้ไขหน้านี้ เนื่องจากหน้านี้เก็บการตั้งค่าส่วนตัวของผู้ใช้อื่น',
'ns-specialprotected'  => 'คุณไม่สามารถแก้ไขหน้าพิเศษได้',
'titleprotected'       => "หัวเรื่องนี้ได้รับการป้องกันไม่ให้สร้างใหม่  ผู้ดำเนินการป้องกันคือ [[User:$1|$1]] ได้ให้เหตุผลไว้ว่า ''$2''",

# Virus scanner
'virus-badscanner'     => "การตั้งค่าผิดพลาด: ไม่รู้จักตัวสแกนไวรัส: ''$1''",
'virus-scanfailed'     => 'การสแกนล้มเหลว (โค้ด $1)',
'virus-unknownscanner' => 'ไม่รู้จักโปรแกรมป้องกันไวรัสตัวนี้:',

# Login and logout pages
'logouttext'                 => "'''ขณะนี้คุณได้ล็อกเอาต์ออกจากระบบ'''

คุณสามารถใช้งาน {{SITENAME}} ได้ต่อในฐานะผู้ใช้นิรนาม หรือคุณสามารถ[[Special:UserLogin|ล็อกอินกลับเข้าไป]]ด้วยชื่อผู้ใช้เดิมหรือชื่อผู้ใช้อื่นๆ
อย่างไรก็ตามอาจจะมีบางหน้าที่ยังแสดงข้อความว่าคุณกำลังล็อกอินอยู่ จนกว่าคุณจะล้างแคชออกจากเว็บเบราว์เซอร์",
'welcomecreation'            => '== ยินดีต้อนรับ $1! ==

ชื่อบัญชีผู้ใช้ของคุณถูกสร้างขึ้นแล้ว
อย่าลืมเข้าไป[[Special:Preferences|ตั้งค่าผู้ใช้สำหรับ {{SITENAME}}]]',
'yourname'                   => 'ชื่อผู้ใช้',
'yourpassword'               => 'รหัสผ่าน',
'yourpasswordagain'          => 'พิมพ์รหัสผ่านอีกครั้ง:',
'remembermypassword'         => 'จำการล็อกอินบนคอมพิวเตอร์นี้',
'yourdomainname'             => 'โดเมนของคุณ:',
'externaldberror'            => 'เกิดความผิดพลาดในการระบุตัวตนจากภายนอก หรือคุณไม่มีสิทธิในการแก้ไขบัญชีอื่น',
'login'                      => 'ล็อกอิน',
'nav-login-createaccount'    => 'ล็อกอิน / สร้างบัญชีผู้ใช้',
'loginprompt'                => 'ต้องเปิดใช้คุกกี้ก่อนที่จะล็อกอินเข้าสู่ {{SITENAME}}',
'userlogin'                  => 'ล็อกอิน / สร้างบัญชีผู้ใช้',
'userloginnocreate'          => 'ล็อกอิน',
'logout'                     => 'ล็อกเอาต์',
'userlogout'                 => 'ล็อกเอาต์',
'notloggedin'                => 'ไม่ได้ล็อกอิน',
'nologin'                    => "ล็อกอินด้านล่างหรือ '''$1'''",
'nologinlink'                => 'สร้างบัญชีผู้ใช้',
'createaccount'              => 'สร้างบัญชีผู้ใช้',
'gotaccount'                 => "มีบัญชีผู้ใช้แล้วหรือไม่ '''$1'''",
'gotaccountlink'             => 'ล็อกอิน',
'createaccountmail'          => 'ผ่านทางอีเมล',
'badretype'                  => 'รหัสผ่านที่ใส่ไม่ถูกต้อง',
'userexists'                 => 'ชื่อบัญชีที่ใส่มีผู้อื่นใช้แล้ว กรุณาเลือกชื่ออื่น',
'loginerror'                 => 'ล็อกอินผิดพลาด',
'createaccounterror'         => 'ไม่สามารถสร้างบัญชีผู้ใช้: $1',
'nocookiesnew'               => 'ชื่อบัญชีผู้ใช้ได้ถูกสร้างขึ้นแล้ว แต่ยังไม่ได้ล็อกอินเข้าสู่ {{SITENAME}} เนื่องจากว่าไม่ได้เปิดใช้คุกกี้ ถ้าต้องการล็อกอินให้เปิดใช้งานคุกกี้และทำการล็อกอินโดยใส่ชื่อผู้ใช้พร้อมรหัสผ่าน',
'nocookieslogin'             => '{{SITENAME}} ใช้คุกกี้สำหรับการล็อกอิน ขณะนี้คุกกี้ของคุณไม่เปิดใช้งาน กรุณาเปิดใช้งานและลองอีกครั้ง',
'noname'                     => 'คุณไม่ได้ใส่ชื่อผู้ใช้ที่ถูกต้อง',
'loginsuccesstitle'          => 'ล็อกอินสำเร็จ',
'loginsuccess'               => "'''ขณะนี้คุณล็อกอินเข้าสู่ {{SITENAME}} ด้วยชื่อ \"\$1\"'''",
'nosuchuser'                 => 'ไม่มีผู้ใช้ที่ชื่อ "$1" 
อักษรใหญ่เล็กมีผลต่อชื่อผู้ใช้
กรุณาตรวจการสะกดอีกครั้ง หรือ[[Special:UserLogin/signup|สร้างบัญชีผู้ใช้ใหม่]]',
'nosuchusershort'            => 'ไม่มีชื่อผู้ใช้ในชื่อ "<nowiki>$1</nowiki>" กรุณาตรวจสอบการสะกด',
'nouserspecified'            => 'คุณต้องระบุชื่อผู้ใช้',
'login-userblocked'          => 'ผู้ใช้นี้ถูกบล็อก ไม่อนุญาตให้ทำการล็อกอิน',
'wrongpassword'              => 'รหัสผ่านที่ใส่ไม่ถูกต้อง กรุณาลองใหม่',
'wrongpasswordempty'         => 'ยังไม่ได้ระบุรหัสผ่าน กรุณาลองใหม่',
'passwordtooshort'           => 'รหัสผ่านต้องมีความยาวอย่างน้อย {{PLURAL:$1|$1 ตัวอักษร}}',
'password-name-match'        => 'รหัสผ่านของคุณต้องไม่เหมือนกันกับชื่อผู้ใช้ของคุณ',
'mailmypassword'             => 'อีเมลรหัสผ่านใหม่',
'passwordremindertitle'      => 'คำบอกรหัสผ่านจาก {{SITENAME}}',
'passwordremindertext'       => 'ผู้ใดผู้หนึ่ง (ซึ่งอาจจะเป็นคุณได้ใช้หมายเลขไอพี $1) ขอให้ส่งรหัสผ่านใหม่
สำหรับการล็อกอินบนเว็บไซต์ {{SITENAME}} ($4) รหัสผ่านชั่วคราวสำหรับชื่อผู้ใช้: "$2" 
คือ "$3" หากคุณได้ทำการร้องขอนี้ เราขอแนะนำให้คุณล็อกอินและเปลี่ยนรหัสผ่านทันที 
รหัสผ่านชั่วคราวของคุณจะหมดอายุใน $5 วัน

หากบุคคลอื่นบุคคลใดขอรหัสผ่านใหม่ หรือหากคุณจำรหัสผ่านเก่าของคุณได้แล้ว
และไม่ต้องการเปลี่ยนรหัสผ่านใหม่แต่อย่างใด กรุณาเพิกเฉยต่อข้อความนี้ และ
ใช้รหัสผ่านเดิมต่อไป',
'noemail'                    => 'อีเมลไม่ได้ใส่ไว้สำหรับชื่อผู้ใช้ "$1"',
'noemailcreate'              => 'คุณจำเป็นต้องใส่ที่อยู่อีเมลให้ถูกต้อง',
'passwordsent'               => 'รหัสผ่านใหม่ได้ถูกส่งไปที่อีเมลของผู้ใช้ "$1"
กรุณาล็อกอินหลังจากที่ได้อีเมล',
'blocked-mailpassword'       => 'หมายเลขไอพีของคุณได้ถูกบล็อกจากการแก้ไข ดังนั้นไม่สามารถใช้คำสั่งร้องขอรหัสผ่านได้เพื่อป้องกันปัญหาการก่อกวน',
'eauthentsent'               => 'อีเมลยืนยันได้ถูกส่งไปที่อีเมลที่ได้ถูกเสนอ ก่อนที่อีเมลจะถูกส่งไปที่ชื่อบัญชีนั้น คุณต้องปฏิบัติตามคำแนะนำในอีเมลเพื่อยืนยันว่าหมายเลยบัญชีนั้นเป็นของคุณ',
'throttled-mailpassword'     => 'ตัวเตือนรหัสผ่านได้ถูกส่งไปใน {{PLURAL:$1|1 ชั่วโมงที่ผ่านมา|$1 ชั่วโมงที่ผ่านมา}} ซึ่งตัวเตือนรหัสผ่านนี้จะถูกส่งได้หนึ่งครั้งต่อ {{PLURAL:$1|1 ชั่วโมง|$1 ชั่วโมง}} เท่านั้น เพื่อป้องกันปัญหาการก่อกวน',
'mailerror'                  => 'ไม่สามารถส่งอีเมลเนื่องจาก $1',
'acct_creation_throttle_hit' => 'ผู้เข้าชมที่ใช้หมายเลขไอพีของคุณในวิกินี้ ได้สร้างชื่อบัญชีไว้แล้ว {{PLURAL:$1|1 บัญชี|$1 บัญชี}} ในวันที่ผ่านมา ซึ่งเป็นจำนวนมากที่สุดที่อนุญาตในช่วงเวลาดังกล่าว
จึงส่งผลให้ผู้เข้าชมที่ใช้หมายเลขไอพีนี้ จะไม่สามารถสร้างบัญชีผู้ใช้ได้อีกในตอนนี้',
'emailauthenticated'         => 'อีเมลของคุณได้รับการรับรอง ณ วันที่ $2 เวลา $3',
'emailnotauthenticated'      => 'อีเมลของคุณยังไม่ได้ถูกยืนยัน ดังนั้นคำสั่งพิเศษที่ใช้งานผ่านอีเมลยังไม่เปิดใช้งาน',
'noemailprefs'               => 'รับอีเมลตามเงื่อนไขพิเศษต่อไปนี้',
'emailconfirmlink'           => 'ยืนยันอีเมลของคุณ',
'invalidemailaddress'        => 'รูปแบบอีเมลที่คุณใส่ไม่ถูกต้อง กรุณาใส่อีเมลให้ถูกต้องตามรูปแบบ 
หรือไม่ต้องใส่ข้อความอะไรลงไปเลยในช่องนี้',
'accountcreated'             => 'ชื่อบัญชีได้ถูกสร้างขึ้น',
'accountcreatedtext'         => 'ชื่อบัญชีสำหรับ $1 ได้ถูกสร้างขึ้นแล้ว',
'createaccount-title'        => 'สร้างบัญชีผู้ใช้สำหรับ {{SITENAME}}',
'createaccount-text'         => 'มีใครบางคนสร้างบัญชีผู้ใช้สำหรับที่อยู่อีเมลของคุณไว้บน {{SITENAME}} ($4) โดยใช้ชื่อบัญชีผู้ใช้ "$2" และรหัสผ่าน "$3" คุณควรล็อกอินเพื่อเปลี่ยนรหัสผ่านโดยทันที

ข้อความนี้อาจจะไม่สำคัญสำหรับคุณ หากการสร้างบัญชีผู้ใช้นี้เกิดจากความผิดพลาด',
'usernamehasherror'          => 'ไม่สามารถมีตัวอักษร "#" ในชื่อผู้ใช้ได้',
'login-throttled'            => 'คุณได้พยายามล็อกอินมากครั้งเกินไป
กรุณารอสักครู่แล้วลองใหม่อีกครั้ง',
'loginlanguagelabel'         => 'ภาษา: $1',

# Password reset dialog
'resetpass'                 => 'เปลี่ยนรหัสผ่าน',
'resetpass_announce'        => 'คุณล็อกอินผ่านรหัสอีเมลชั่วคราว คุณต้องใส่ค่ารหัสผ่านใหม่เพื่อเสร็จสิ้นขั้นตอนการล็อกอิน:',
'resetpass_header'          => 'เปลี่ยนรหัสผ่าน',
'oldpassword'               => 'รหัสผ่านเดิม:',
'newpassword'               => 'รหัสผ่านใหม่:',
'retypenew'                 => 'พิมพ์รหัสผ่านใหม่อีกครั้ง:',
'resetpass_submit'          => 'ตั้งรหัสผ่านและล็อกอิน',
'resetpass_success'         => 'รหัสผ่านได้ถูกเปลี่ยนเรียบร้อย ขณะนี้กำลังล็อกอินให้คุณ...',
'resetpass_forbidden'       => 'ไม่สามารถเปลี่ยนรหัสผ่านได้',
'resetpass-no-info'         => 'คุณต้องล็อกอินเพื่อที่จะเข้าถึงหน้านี้โดยตรง',
'resetpass-submit-loggedin' => 'เปลี่ยนรหัสผ่าน',
'resetpass-submit-cancel'   => 'ยกเลิก',
'resetpass-wrong-oldpass'   => 'รหัสผ่านชั่วคราวหรือปัจจุบันไม่ถูกต้อง
คุณอาจเปลี่ยนรหัสผ่านของคุณไปแล้ว หรือร้องขอรหัสผ่านชั่วคราวใหม่แล้ว',
'resetpass-temp-password'   => 'รหัสผ่านชั่วคราว:',

# Edit page toolbar
'bold_sample'     => 'ทำตัวหนา',
'bold_tip'        => 'ทำตัวหนา',
'italic_sample'   => 'ตัวหนังสือที่เป็นตัวเอน',
'italic_tip'      => 'ทำตัวเอน',
'link_sample'     => 'ลิงก์เชื่อมโยง',
'link_tip'        => 'ลิงก์ภายในเว็บ',
'extlink_sample'  => 'http://www.example.com ชื่อคำอธิบายลิงก์',
'extlink_tip'     => 'ลิงก์ไปที่อื่น (อย่าลืมใส่ http:// นำหน้าเสมอ)',
'headline_sample' => 'หัวข้อ',
'headline_tip'    => 'หัวข้อ',
'math_sample'     => 'ใส่สูตรที่นี่',
'math_tip'        => 'ใส่สูตรทางคณิตศาสตร์ (LaTeX)',
'nowiki_sample'   => 'ใส่ข้อความที่ไม่จัดรูปแบบ',
'nowiki_tip'      => 'ข้ามการจัดรูปแบบวิกิ',
'image_tip'       => 'ใส่ภาพ',
'media_tip'       => 'เชื่อมโยงไฟล์สื่อ',
'sig_tip'         => 'ลายเซ็นพร้อมลงเวลา',
'hr_tip'          => 'เส้นนอน',

# Edit pages
'summary'                          => 'คำอธิบายโดยย่อ:',
'subject'                          => 'หัวข้อ:',
'minoredit'                        => 'เป็นการแก้ไขเล็กน้อย',
'watchthis'                        => 'เฝ้าดูหน้านี้',
'savearticle'                      => 'บันทึก',
'preview'                          => 'แสดงตัวอย่าง',
'showpreview'                      => 'แสดงตัวอย่าง',
'showlivepreview'                  => 'แสดงตัวอย่างทันที',
'showdiff'                         => 'แสดงความเปลี่ยนแปลง',
'anoneditwarning'                  => "'''คำเตือน:''' หมายเลขไอพีของคุณจะถูกเก็บไว้ในส่วนประวัติของหน้านี้เนื่องจากคุณไม่ได้ล็อกอิน",
'missingsummary'                   => "'''อย่าลืม:''' คุณยังไม่ได้ระบุคำอธิบายการแก้ไขครั้งนี้ ถ้าคุณกดบันทึกไปส่วนคำอธิบายการแก้ไขนั้นจะว่างและไม่แสดงผล",
'missingcommenttext'               => 'กรุณาใส่ความเห็นด้านล่าง',
'missingcommentheader'             => "'''อย่าลืม:''' คุณยังไม่ได้ใส่หัวข้อ/จ่าหัวสำหรับความเห็นในครั้งนี้ ถ้าคุณกดบันทึกไปส่วนหัวข้อความเห็นจะว่างไม่แสดงผล",
'summary-preview'                  => 'ตัวอย่างคำอธิบายการแก้ไข:',
'subject-preview'                  => 'ตัวอย่างหัวข้อ:',
'blockedtitle'                     => 'ผู้ใช้ถูกห้ามใช้งาน',
'blockedtext'                      => "'''ชื่อผู้ใช้หรือหมายเลขไอพีถูกของคุณถูกบล็อกการใช้งาน'''

$1 เป็นผู้ดำเนินการบล็อกในครั้งนี้ โดยให้เหตุผลไว้ว่า ''$2''

* เริ่มการบล็อก: $8
* สิ้นสุดการบล็อก: $6
* ผู้ถูกบล็อก: $7

คุณสามารถติดต่อ $1 หรือ [[{{MediaWiki:Grouppage-sysop}}|ผู้ดูแลระบบ]]คนอื่นเพื่อหารือเกี่ยวกับการบล็อกนี้ หรือสามารถที่จะอีเมลผ่านระบบวิกิด้วยคำสั่ง 'อีเมลหาผู้ใช้นี้'
(ถ้าคุณได้ตั้งค่ารองรับการใช้คำสั่งพิเศษผ่านทางอีเมลในส่วน [[Special:Preferences|การตั้งค่าผู้ใช้]] และคุณไม่ได้ถูกบล็อกจากการใช้คำสั่งนี้)
หมายเลขไอพีปัจจุบันของคุณคือ $3 และหมายเลขการบล็อกคือ #$5 กรุณาระบุหมายเลขเหล่านี้ในการติดต่อผู้ดูแล",
'autoblockedtext'                  => 'หมายเลขไอพีของคุณถูกบล็อกโดยอัตโนมัติ เนื่องจากมีผู้ใช้อื่นใช้งานผ่านหมายเลขไอพีนี้มาก่อน ซึ่งถูกบล็อกโดย $1
เหตุผลที่ให้ไว้ในการบล็อกคือ:

:\'\'$2\'\'

* เริ่มการบล็อก: $8
* สิ้นสุดการบล็อก: $6
* ผู้ถูกบล็อกโดยเจตนา: $7

คุณอาจติดต่อ $1 หรือ[[{{MediaWiki:Grouppage-sysop}}|ผู้ดูแลระบบ]]คนอื่นเพื่อหารือเกี่ยวกับการบล็อกนี้

โปรดทราบว่าคุณอาจไม่สามารถใช้คำสั่ง "อีเมลหาผู้ใช้นี้" หากคุณไม่มีที่อยู่อีเมลที่ถูกต้อง ดังที่ลงทะเบียนไว้ใน[[Special:Preferences|การตั้งค่าผู้ใช้]] และไม่ถูกบล็อกจากการใช้คำสั่งนี้

หมายเลขไอพีปัจจุบันของคุณคือ $3 หมายเลขการบล็อกคือ #$5
กรุณาระบุรายละเอียดทั้งหมดข้างต้นในการร้องขอใดๆ ที่คุณกระทำ',
'blockednoreason'                  => 'ไม่ได้ให้เหตุผลไว้',
'blockedoriginalsource'            => "โค้ดของ '''$1''' แสดงผลด้านล่าง:",
'blockededitsource'                => "'''ข้อความที่คุณได้แก้ไข''' ใน '''$1''' แสดงผลด้านล่าง:",
'whitelistedittitle'               => 'ไม่อนุญาตให้แก้ไขถ้าไม่ล็อกอิน',
'whitelistedittext'                => 'คุณต้อง $1 เพื่อทำการแก้ไข',
'confirmedittext'                  => 'ไม่อนุญาตให้แก้ไขถ้าไม่ได้ทำการยืนยันอีเมล กรุณายืนยันอีเมลผ่านทาง [[Special:Preferences|การตั้งค่าผู้ใช้]]',
'nosuchsectiontitle'               => 'ไม่มีหัวข้อย่อย',
'nosuchsectiontext'                => 'คุณพยายามแก้ไขหัวข้อย่อยที่ไม่มีอยู่แล้วในขณะนี้ หัวข้อย่อยดังกล่าวอาจถูกย้ายหรือลบในขณะที่คุณดูหน้าเว็บอยู่',
'loginreqtitle'                    => 'จำเป็นต้องล็อกอิน',
'loginreqlink'                     => 'ล็อกอิน',
'loginreqpagetext'                 => 'ถ้าต้องการดูหน้าอื่น คุณต้อง $1',
'accmailtitle'                     => 'ส่งรหัสผ่านแล้ว',
'accmailtext'                      => "มีการสร้างรหัสผ่านแบบสุ่มให้กับ [[User talk:$1|$1]] โดยรหัสผ่านได้รับการจัดส่งไปที่ $2

สามารถเปลี่ยนรหัสผ่านของบัญชีผู้ใช้ใหม่นี้ในหน้า''[[Special:ChangePassword|เปลี่ยนรหัสผ่าน]]'' หลังจากที่ล็อกอินแล้ว",
'newarticle'                       => '(ใหม่)',
'newarticletext'                   => 'หน้านี้ยังไม่มีข้อความใด สามารถเริ่มสร้างหน้านี้โดยการพิมพ์ข้อความลงในกล่องด้านล่าง
(ดูเพิ่มเติมที่ [[{{MediaWiki:Helppage}}|หน้าคำอธิบาย]])
ถ้าไม่ต้องการสร้างให้กดปุ่ม ถอยหลัง (back) ที่เว็บเบราว์เซอร์',
'anontalkpagetext'                 => "----''หน้านี้เป็นหน้าพูดคุยสำหรับผู้ใช้นิรนาม ซึ่งยังไม่ได้สร้างบัญชีผู้ใช้
โดยทางเราจำเป็นต้องระบุตัวตนผ่านทางหมายเลขไอพี
ซึ่งหมายเลขไอพีนี้อาจถูกใช้ร่วมกันโดยผู้ใช้หลายคน
ถ้าคุณเป็นผู้ใช้นิรนาม และรู้สึกว่าความเห็นที่คุณได้รับไม่เกี่ยวข้องกับคุณแต่อย่างใด กรุณา[[Special:UserLogin/signup|สร้างบัญชีผู้ใช้]]หรือ[[Special:UserLogin|ล็อกอิน]] เพื่อป้องกันการสับสนกับผู้ใช้นิรนามรายอื่น''",
'noarticletext'                    => 'ขณะนี้ไม่มีเนื้อหาในหน้านี้
คุณสามารถ [[Special:Search/{{PAGENAME}}|ค้นหาชื่อบทความนี้]] ในหน้าอื่น
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ค้นหาบันทึกที่เกี่ยวข้อง] หรือ[{{fullurl:{{FULLPAGENAME}}|action=edit}} แก้ไขหน้านี้]</span>',
'noarticletext-nopermission'       => 'ขณะนี้ไม่มีเนื้อหาในหน้านี้
คุณสามารถ [[Special:Search/{{PAGENAME}}|ค้นหาชื่อบทความนี้]] ในหน้าอื่น
หรือ <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ค้นหาบันทึกที่เกี่ยวข้อง]</span>',
'userpage-userdoesnotexist'        => 'ไม่มีบัญชีผู้ใช้ "$1" อยู่ในสารบบ  กรุณาตรวจสอบให้แน่ใจว่าคุณต้องการสร้างหรือแก้ไขหน้านี้จริงๆ',
'userpage-userdoesnotexist-view'   => 'ไม่มีบัญชีผู้ใช้ลงทะเบียนในชื่อ "$1"',
'blocked-notice-logextract'        => 'ปัจจุบันผู้ใช้นี้ถูกบล็อก
ปูมการบล็อกรายการล่าสุดแสดงไว้ด้านล่างนี้เพื่อการอ้างอิง:',
'clearyourcache'                   => "'''คำแนะนำ:''' หลังจากบันทึกผลแล้ว คุณอาจจะต้องล้างแคชเว็บเบราว์เซอร์ของคุณเพื่อดูผลการเปลี่ยนแปลง <br />
'''มอซิลลา / ไฟร์ฟอกซ์ / ซาฟารี:''' กดปุ่ม ''Shift'' ค้างไว้ขณะกดปุ่ม ''รีโหลด'' หรือกด  ''Ctrl-F5'' หรือกด ''Ctrl-R'' (''Command-R'' สำหรับเครื่องแมคอินทอช); <br />
'''คองเคอเรอร์:''' กดปุ่ม ''รีโหลด'' หรือกด ''F5;'' <br />
'''โอเปร่า:''' อาจต้องล้างแคชทั้งหมดผ่านเมนู ''Tools → Preferences;'' <br />
'''อินเทอร์เน็ตเอกซ์พลอเรอร์:''' กด  ''Ctrl'' ค้างไว้ขณะที่กดปุ่ม ''รีเฟรช'' หรือกด ''Ctrl-F5;''",
'usercssyoucanpreview'             => "'''คำแนะนำ:''' กดปุ่ม 'แสดงตัวอย่าง' เพื่อทดสอบสไตล์ชีตหรือจาวาสคริปต์ก่อนทำการบันทึก",
'userjsyoucanpreview'              => "'''คำแนะนำ:''' กดปุ่ม 'แสดงตัวอย่าง' เพื่อทดสอบสไตล์ชีตหรือจาวาสคริปต์ก่อนทำการบันทึก",
'usercsspreview'                   => "'''อย่าลืมว่าสไตล์ชีตที่คุณสร้างยังไม่ได้ถูกบันทึก'''
'''นี่คือการแสดงตัวอย่างเท่านั้น!'''",
'userjspreview'                    => "'''อย่าลืมว่าจาวาสคริปต์ยังไม่ได้ถูกบันทึก ขณะนี้แสดงเพียงตัวอย่างเท่านั้น!'''",
'userinvalidcssjstitle'            => "'''คำเตือน:''' ไม่มีแบบหน้าตา \"\$1\" อย่าลืมว่าหน้า .css และ .js ที่ปรับแต่งเอง ใช้เป็นอักษรตัวพิมพ์เล็กทั้งหมด เช่น ใช้ {{ns:user}}:Foo/monobook.css แทนที่จะเป็น {{ns:user}}:Foo/Monobook.css",
'updated'                          => '(ปรับปรุงแล้ว)',
'note'                             => "'''คำแนะนำ:'''",
'previewnote'                      => "'''ตรงนี้เป็นการแสดงตัวอย่างเท่านั้น การเปลี่ยนแปลงยังไม่ได้ถูกบันทึก!'''",
'previewconflict'                  => 'การแสดงผลส่วนนี้เป็นตัวอย่างของการแก้ไขด้านบน  ถ้ากดบันทึกการแสดงผลจะแสดงในลักษณะนี้ทันที',
'session_fail_preview'             => "'''ขออภัย ไม่สามารถดำเนินการแก้ไขต่อได้ เนื่องจากข้อมูลเชื่อมต่อสูญหาย
ให้ทดลองแก้ไขอีกครั้งหนึ่ง ถ้ายังไม่สามารถทำได้ ให้ลองล็อกเอาต์และล็อกอินกลับมาอีกครั้ง'''",
'session_fail_preview_html'        => "'''ขออภัย ไม่สามารถดำเนินการแก้ไขต่อได้ เนื่องจากข้อมูลเชื่อมต่อสูญหาย'''

''เนื่องจาก {{SITENAME}} ใช้รูปแบบเอชทีเอ็มแอลล้วน การแสดงตัวอย่างจะถูกซ่อนไว้เพื่อป้องกันการโตมตีด้วยจาวาสคริปต์''

'''ถ้าการแก้ไขครั้งนี้ถูกต้อง ให้ทดลองแก้ไขอีกครั้งหนึ่ง ถ้ายังไม่สามารถทำได้ ให้ลอง[[Special:UserLogout|ล็อกเอาต์]]และล็อกอินกลับมาอีกครั้ง'''",
'token_suffix_mismatch'            => "'''การแก้ไขของคุณได้ถูกปฏิเสธ เนื่องจากเครื่องลูกข่ายที่คุณใช้อยู่ได้ทำลายรูปแบบเครื่องหมายวรรคตอนในตราสารประจำการแก้ไข (edit token)'''
ระบบไม่รับการแก้ไขของคุณเพื่อป้องกันความผิดพลาดของข้อมูล
ในบางครั้งปัญหานี้จะเกิดขึ้นถ้าคุณใช้บริการเว็บพร็อกซีนิรนามที่มีบั๊ก",
'editing'                          => 'กำลังแก้ไข $1',
'editingsection'                   => 'กำลังแก้ไข $1 (เฉพาะส่วน)',
'editingcomment'                   => 'กำลังแก้ไข $1 (หัวข้อใหม่)',
'editconflict'                     => 'แก้ไขชนกัน: $1',
'explainconflict'                  => "ใครบางคนได้เปลี่ยนแปลงหน้านี้ในขณะที่คุณกำลังแก้ไข
ข้อความส่วนบนเป็นข้อความปัจจุบันของหน้านี้ และส่วนล่างเป็นการแก้ไขของคุณ
คุณต้องทำการรวมการเปลี่ยนแปลงของคุณเข้ากับข้อความปัจจุบัน เพราะ'''ข้อความในส่วนบนเท่านั้น'''ที่จะถูกบันทึก เมื่อกดปุ่ม \"บันทึกหน้านี้\"",
'yourtext'                         => 'ข้อความของคุณ',
'storedversion'                    => 'รุ่นที่เก็บไว้',
'nonunicodebrowser'                => "'''คำเตือน: เว็บเบราว์เซอร์นี้ไม่สนับสนุนการใช้งานแบบยูนิโคด ตัวอักษรที่ไม่ใช่แบบแอสกีจะแสดงในกล่องการแก้ไขในลักษณะรหัสเลขฐานสิบหก'''",
'editingold'                       => "'''คำเตือน: ข้อมูลที่แก้ไขอยู่ไม่ใช่ข้อมูลใหม่ล่าสุดของหน้านี้ ถ้าทำการบันทึกไป การเปลี่ยนแปลงที่เกิดขึ้นระหว่างรุ่นนี้กับรุ่นใหม่จะสูญหาย'''",
'yourdiff'                         => 'ข้อแตกต่าง',
'copyrightwarning'                 => "โปรดอย่าลืมว่างานเขียนทั้งหมดใน {{SITENAME}} ผู้เขียนทั้งหมดยินดีให้งานเก็บไว้ภายใต้สัญญาลิขสิทธิ์ $2 (ดู $1 สำหรับข้อมูลเพิ่มเติม)
ถ้าคุณไม่ต้องการให้งานของคุณถูกแก้ไข หรือไม่ต้องการให้งานเผยแพร่ตามที่ได้กล่าวไว้ อย่าส่งข้อความเข้ามาที่นี่<br />
นอกจากนี้แน่ใจว่าข้อความที่ส่งเข้ามาได้เขียนด้วยตัวเอง ไม่ได้คัดลอก หรือทำซ้ำจากแหล่งอื่น
'''อย่าส่งงานที่มีลิขสิทธิ์เข้ามาก่อนได้รับอนุญาตจากเจ้าของ!'''",
'copyrightwarning2'                => "โปรดอย่าลืมว่างานเขียนทั้งหมดใน {{SITENAME}} อาจจะถูกแก้ไข ดัดแปลง หรือลบออกโดยผู้ร่วมเขียนคนอื่น
ถ้าคุณไม่ต้องการให้งานของคุณถูกแก้ไข หรือไม่ต้องการให้งานเผยแพร่ตามที่กล่าวไว้ อย่าส่งข้อความของคุณเข้ามาที่นี่<br />
นอกจากนี้คุณแน่ใจว่าข้อความที่ส่งเข้ามาคุณได้เขียนด้วยตัวเอง ไม่ได้คัดลอก ทำซ้ำส่วนหนึ่งส่วนใดหรือทั้งหมดจากแหล่งอื่น (ดูรายละเอียดที่ $1)
'''อย่าส่งงานที่มีลิขสิทธิ์เข้ามาก่อนได้รับอนุญาตจากเจ้าของ!'''",
'longpagewarning'                  => "'''คำเตือน: หน้านี้มีความยาว $1 กิโลไบต์ ซึ่งเว็บเบราว์เซอร์บางตัวอาจจะมีปัญหาในการแก้ไขหน้าที่ความยาวเกินกว่า 32 กิโลไบต์

ลองพิจารณาแบ่งหน้าออกเป็นหัวข้อย่อย'''",
'longpageerror'                    => "'''ผิดพลาด: ข้อความที่คุณส่งเข้ามามีขนาด $1 กิโลไบต์
ซึ่งเกินกว่าขนาดที่กำหนดไว้ที่ $2 กิโลไบต์ จึงไม่สามารถบันทึกได้'''",
'readonlywarning'                  => "'''คำเตือน: ขณะนี้ฐานข้อมูลถูกล็อกเพื่อบำรุงรักษา จึงไม่สามารถบันทึกข้อมูลที่แก้ไขได้ แนะนำให้คัดลอกไปเก็บไว้ที่อื่นก่อนแล้วนำมาบันทึกในเว็บไซต์นี้ภายหลัง'''

ผู้ดูแลระบบที่ล็อกฐานข้อมูลได้ให้คำอธิบายดังนี้: $1",
'protectedpagewarning'             => "'''คำเตือน: หน้านี้ถูกล็อก และแก้ไขได้เฉพาะผู้ดูแลระบบเท่านั้น'''
บันทึกการป้องกันล่าสุดถูกแสดงไว้ด้านล่างเพื่อการอ้างอิง",
'semiprotectedpagewarning'         => "'''คำแนะนำ:''' หน้านี้ถูกล็อก และแก้ไขได้เฉพาะผู้ใช้ที่ลงทะเบียนเท่านั้น",
'cascadeprotectedwarning'          => "'''คำเตือน:''' หน้านี้ถูกล็อก และแก้ไขได้เฉพาะผู้ดูแลระบบเท่านั้น เนื่องจากหน้านี้สืบทอดการล็อกมาจาก{{PLURAL:$1|หน้า|หน้า}}ต่อไปนี้:",
'titleprotectedwarning'            => "'''คำเตือน:  หน้านี้ได้รับการป้องกันไว้ให้สร้างได้โดย[[Special:ListGroupRights|ผู้ใช้ที่ได้รับสิทธิ]]เท่านั้น'''",
'templatesused'                    => '{{PLURAL:$1|แม่แบบ}}ที่ใช้ในหน้านี้:',
'templatesusedpreview'             => '{{PLURAL:$1|แม่แบบ}}ที่ใช้ในการแสดงตัวอย่าง:',
'templatesusedsection'             => '{{PLURAL:$1|แม่แบบ}}ที่ใช้ในส่วนนี้:',
'template-protected'               => '(ล็อก)',
'template-semiprotected'           => '(กึ่งล็อก)',
'hiddencategories'                 => 'หน้านี้มี {{PLURAL:$1|1 หมวดหมู่ที่ซ่อนอยู่|$1 หมวดหมู่ที่ซ่อนอยู่}} :',
'edittools'                        => '<!-- ข้อความนี้จะแสดงผลด้านใต้การแก้ไขและฟอร์มสำหรับอัปโหลด -->',
'nocreatetitle'                    => 'จำกัดการสร้างหน้าใหม่',
'nocreatetext'                     => 'เว็บไซต์นี้จำกัดการสร้างหน้าเว็บเพจใหม่
คุณสามารถทำการแก้ไขหน้าที่สร้างไว้แล้ว หรือ [[Special:UserLogin|ล็อกอินหรือสร้างบัญชีผู้ใช้]]',
'nocreate-loggedin'                => 'คุณไม่ได้รับอนุญาตให้สร้างหน้าใหม่ได้',
'sectioneditnotsupported-title'    => 'ไม่สนับสนุนการแก้ไขหัวข้อย่อย',
'sectioneditnotsupported-text'     => 'ไม่สนับสนุนการแก้ไขหัวข้อย่อยในหน้านี้',
'permissionserrors'                => 'ข้อผิดพลาดในการใช้สิทธิ',
'permissionserrorstext'            => 'คุณไม่ได้รับสิทธิในการทำสิ่งนี้ เนื่องจาก{{PLURAL:$1|เหตุผล|เหตุผล}}ต่อไปนี้:',
'permissionserrorstext-withaction' => 'คุณไม่มีสิทธิในการ$2 เนื่องจาก{{PLURAL:$1|เหตุผล|เหตุผล}}ต่อไปนี้:',
'recreate-moveddeleted-warn'       => "'''คำเตือน: คุณกำลังจะสร้างหน้าใหม่ซึ่งก่อนหน้านี้ได้ถูกลบไปแล้ว'''

ขอให้พิจารณาว่าหน้านี้เหมาะสมในการสร้างใหม่หรือไม่
ลองตรวจสอบบันทึกการลบและการโยกย้ายหน้านี้ในอดีตได้ที่นี่:",
'moveddeleted-notice'              => 'หน้านี้ถูกลบ 
บันทึกการลบและการเปลี่ยนชื่อสำหรับหน้านี้ได้แสดงไว้ด้านล่างนี้สำหรับการอ้างอิง',
'log-fulllog'                      => 'ดูบันทึกแบบเต็ม',
'edit-hook-aborted'                => 'การแก้ไขถูกยกเลิก
ไม่มีคำอธิบายสำหรับการยกเลิกนี้',
'edit-gone-missing'                => 'ไม่สามารถปรับแก้หน้าดังกล่าวได้
เนื่องจากหน้านี้ถูกลบไปแล้ว',
'edit-conflict'                    => 'แก้ชนกัน',
'edit-no-change'                   => 'การแก้ไขของคุณถูกเพิกเฉย เพราะข้อความไม่ถูกเปลี่ยนแปลงใด ๆ ทั้งสิ้น',
'edit-already-exists'              => 'ไม่สามารถสร้างหน้าใหม่นี้ได้
เนื่องจากว่าหน้านี้มีอยู่แล้ว',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'คำเตือน: หน้านี้มีการเรียกใช้ฟังก์ชันแจงส่วนมากเกินไป

หน้านี้ควรมีการเรียกใช้น้อยกว่า $2 {{PLURAL:$2|ครั้ง|ครั้ง}} ปัจจุบันมีการเรียกใช้ $1 {{PLURAL:$1|ครั้ง|ครั้ง}}',
'expensive-parserfunction-category'       => 'หน้าที่มีการเรียกใช้ฟังก์ชันแจงส่วนมากเกินไป',
'post-expand-template-inclusion-warning'  => 'คำเตือน: แม่แบบที่นำมารวมมีขนาดใหญ่เกินไป
แม่แบบบางหน้าจะไม่ถูกรวมเข้ามา',
'post-expand-template-inclusion-category' => 'หน้าที่มีแม่แบบที่รวมมาเกินขนาด',
'post-expand-template-argument-warning'   => 'คำเตือน: หน้านี้มีแม่แบบที่มีอาร์กิวเมนต์ขนาดใหญ่เกินไป อาร์กิวเมนต์เหล่านี้จะถูกละทิ้ง',
'post-expand-template-argument-category'  => 'หน้าที่มีแม่แบบซึ่งอาร์กิวเมนต์ถูกละทิ้ง',
'parser-template-loop-warning'            => 'ตรวจพบว่าแม่แบบมีการกลับมาเรียกตัวเอง: [[$1]]',
'parser-template-recursion-depth-warning' => 'เรียกแม่แบบซ้อนหลายชั้นเกินขีดจำกัด ($1)',

# "Undo" feature
'undo-success' => 'การแก้ไขนี้สามารถย้อนกลับได้ กรุณาตรวจสอบข้อแตกต่างด้านล่างแน่ใจว่านี่คือสิ่งที่คุณต้องการทำ หลังจากนั้นให้ทำการบันทึกการเปลี่ยนแปลงที่แสดงผลด้านล่าง และกดบันทึกเพื่อเสร็จสิ้นขั้นตอน',
'undo-failure' => 'การแก้ไขนี้ไม่สามารถย้อนกลับได้ เนื่องจากขัดแย้งกับการแก้ไขปัจจุบัน',
'undo-norev'   => 'การแก้ไขนี้ไม่สามารถย้อนได้เพราะไม่มีหรือถูกลบแล้วในปัจจุบัน',
'undo-summary' => 'ย้อนการแก้ไขรุ่น $1 โดย [[Special:Contributions/$2|$2]] ([[User talk:$2|พูดคุย]])',

# Account creation failure
'cantcreateaccounttitle' => 'ไม่สามารถสร้างบัญชีผู้ใช้ได้',
'cantcreateaccount-text' => "การสร้างบัญชีผู้ใช้ใหม่ผ่านทางหมายเลขไอพีนี้ ('''$1''') ถูกระงับไว้โดย [[User:$3|$3]]

เหตุผลที่ $3 ให้ไว้ คือ ''$2''",

# History pages
'viewpagelogs'           => 'ดูบันทึกของหน้านี้',
'nohistory'              => 'ไม่มีประวัติการแก้ไขสำหรับหน้านี้',
'currentrev'             => 'รุ่นปัจจุบัน',
'currentrev-asof'        => 'รุ่นปัจจุบันของ $1',
'revisionasof'           => 'การปรับปรุง เมื่อ $1',
'revision-info'          => 'การปรับปรุง เมื่อ $1 โดย $2',
'previousrevision'       => '←รุ่นก่อนหน้า',
'nextrevision'           => 'รุ่นถัดไป→',
'currentrevisionlink'    => 'รุ่นปัจจุบัน',
'cur'                    => 'ป',
'next'                   => 'ถัดไป',
'last'                   => 'ก',
'page_first'             => 'แรกสุด',
'page_last'              => 'ท้ายสุด',
'histlegend'             => 'วิธีเปรียบเทียบ: เลือกปุ่มของรุ่นสองรุ่นที่ต้องการเปรียบเทียบ และกดปุ่มเริ่มเปรียบเทียบด้านล่าง<br />
คำอธิบาย: (ป) = เทียบกับรุ่นปัจจุบัน, (ก) = เทียบกับรุ่นก่อนหน้า, ล = การแก้ไขเล็กน้อย',
'history-fieldset-title' => 'ค้นหาประวัติ',
'history-show-deleted'   => 'เฉพาะที่ถูกลบ',
'histfirst'              => 'แรกสุด',
'histlast'               => 'ท้ายสุด',
'historysize'            => '({{PLURAL:$1|1 ไบต์|$1 ไบต์}})',
'historyempty'           => '(ว่าง)',

# Revision feed
'history-feed-title'          => 'ประวัติการปรับปรุง',
'history-feed-description'    => 'ประวัติการปรับปรุงของหน้านี้ในวิกิ',
'history-feed-item-nocomment' => '$1 เมื่อ $2',
'history-feed-empty'          => 'หน้าที่ต้องการไม่มี มันอาจถูกลบหรือถูกเปลี่ยนชื่อไปแล้ว ให้ลอง
[[Special:Search|ค้นหาในวิกินี้]] สำหรับหน้าอื่นที่อาจเกี่ยวข้อง',

# Revision deletion
'rev-deleted-comment'         => '(ความเห็นถูกลบออก)',
'rev-deleted-user'            => '(ชื่อผู้ใช้ถูกลบออก)',
'rev-deleted-event'           => '(หน้าที่ใส่เข้ามาถูกลบออก)',
'rev-deleted-user-contribs'   => '[ชื่อผู้ใช้หรือหมายเลขไอพีถูกลบแล้ว - การแก้ไขถูกซ่อนจากรายการแก้ไข]',
'rev-deleted-text-permission' => "รุ่นการปรับปรุงนี้ของหน้านี้'''ถูกลบแล้ว'''
รายละเอียดอาจยังคงมีอยู่ใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} บันทึกการลบ]",
'rev-deleted-text-unhide'     => "รุ่นการปรับปรุงนี้ของหน้านี้'''ถูกลบแล้ว'''
รายละเอียดอาจยังคงมีอยู่ใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} บันทึกการลบ]
สำหรับผู้ดูแลระบบ คุณยังสามารถ[$1 เรียกดูรุ่นการปรับปรุงนี้]หากคุณต้องการ",
'rev-suppressed-text-unhide'  => "ฉบับปรับปรุงของหน้านี้ได้ถูก'''ยับยั้งแล้ว'''
ซึ่งอาจมีรายละเอียดใน [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ประวัติการยับยั้ง]
ในฐานะที่คุณเป็นผู้ดูแลระบบ คุณยังสามารถ[$1 ดูฉบับปรับปรุงนี้]ได้ถ้าคุณต้องการ",
'rev-deleted-text-view'       => "รุ่นการปรับปรุงนี้ของหน้านี้'''ถูกลบแล้ว'''
สำหรับผู้ดูแลระบบ คุณยังสามารถเรียกดูได้ รายละเอียดอาจยังคงมีอยู่ใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} บันทึกการลบ]",
'rev-suppressed-text-view'    => "ฉบับปรับปรุงของหน้านี้ได้ถูก'''ยับยั้งแล้ว'''
ในฐานะที่คุณเป็นผู้ดูแลระบบคุณสามารถดูฉบับปรับปรุงได้ ซึ่งอาจจะมีรายละเอียดใน[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ประวัติการยับยั้ง]",
'rev-deleted-no-diff'         => "คุณไม่สามารถเรียกดูความเปลี่ยนแปลงนี้ เนื่องจากรุ่นการปรับปรุงที่นำมาเปรียบเทียบมีบางรุ่น'''ถูกลบออก'''
รายละเอียดอาจยังคงมีอยู่ใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} บันทึกการลบ]",
'rev-deleted-unhide-diff'     => "รุ่นการปรับปรุงบางรุ่นของความเปลี่ยนแปลงนี้'''ถูกลบแล้ว'''
รายละเอียดอาจยังคงมีอยู่ใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} บันทึกการลบ]
สำหรับผู้ดูแลระบบ คุณยังสามารถ[$1 เรียกดูรุ่นการปรับปรุงนี้]หากคุณต้องการ",
'rev-suppressed-unhide-diff'  => "หนึ่งในรุ่นปรับปรุงทั้งหมดของรายการความแตกต่างนี้ได้ถูก'''ยับยั้งไว้'''
ซึ่งอาจจะมีรายละเอียดใน[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} รายการยับยั้ง]
ในฐานะที่เป็นผู้ดูแลระบบ คุณสามารถ [$1 ดูความแตกต่างได้]ถ้าคุณต้องการ",
'rev-deleted-diff-view'       => "หนึ่งในรุ่นปรับปรุงทั้งหมดของรายการความแตกต่างนี้ได้ถูก'''ลบออก'''
ในฐานะที่เป็นผู้ดูแลระบบ คุณสามารถดูความแตกต่างนี้ได้ ซึ่งอาจจะมีรายละเอียดใน[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} รายการลบ]",
'rev-suppressed-diff-view'    => "หนึ่งในรุ่นปรับปรุงทั้งหมดของรายการความแตกต่างนี้ได้ถูก'''ยับยั้งไว้'''
ในฐานะที่เป็นผู้ดูแลระบบ คุณสามารถดูความแตกต่างนี้ได้ ซึ่งอาจจะมีรายละเอียดใน[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} รายการยังยั้ง]",
'rev-delundel'                => 'แสดง/ซ่อน',
'rev-showdeleted'             => 'แสดง',
'revisiondelete'              => 'รุ่นการปรับปรุง การลบ/การย้อนการลบ',
'revdelete-nooldid-title'     => 'ไม่มีรุ่นการปรับปรุงที่ต้องการ',
'revdelete-nooldid-text'      => 'รุ่นการปรับปรุงที่ต้องการไม่ได้กำหนด ไม่สามารถใช้คำสั่งนี้ได้',
'revdelete-nologtype-title'   => 'ไม่ได้ระบุประเภทของปูม',
'revdelete-nologtype-text'    => 'คุณไม่ได้ระบุประเภทของปูมที่ใช้ดำเนินการนี้ต่อได้',
'revdelete-nologid-title'     => 'การแก้ไขในรายการแก้ไขไม่ถูกต้อง',
'revdelete-nologid-text'      => 'คุณไม่ได้กำหนดรายการแก้ไขที่ต้องการกระทำกระบวนการนี้หรือไม่พบรายการแก้ไขที่กำหนด',
'revdelete-no-file'           => 'ไม่มีไฟล์ที่ระบุ',
'revdelete-show-file-confirm' => 'คุณแน่ใจหรือไม่ที่จะดูฉบับปรับปรุงที่ถูกลบของไฟล์ "<nowiki>$1</nowiki>" ของวันที่ $2 เวลา $3?',
'revdelete-show-file-submit'  => 'ใช่',
'revdelete-selected'          => "'''{{PLURAL:$2|รุ่นการปรับปรุงที่ถูกเลือก|รุ่นการปรับปรุงที่ถูกเลือก}}ของ [[:$1]] :'''",
'logdelete-selected'          => "'''{{PLURAL:$1|เหตุการณ์บันทึกที่ถูกเลือก|เหตุการณ์บันทึกที่ถูกเลือก}} :'''",
'revdelete-text'              => "'''รุ่นการปรับปรุงและเหตุการณ์ที่ถูกลบ จะยังคงปรากฏในส่วนประวัติและบันทึกของหน้า แต่ส่วนของเนื้อหาจะไม่สามารถเข้าถึงได้โดยสาธารณะ'''
ผู้ดูแลระบบคนอื่นบน {{SITENAME}} จะยังคงสามารถเข้าถึงเนื้อหาที่ถูกซ่อน และสามารถกู้คืนขึ้นมาอีกครั้งในลักษณะเดิมเช่นนี้ เว้นแต่จะมีการตั้งค่าการควบคุมเพิ่มเติม",
'revdelete-confirm'           => 'กรุณายืนยันว่าคุณตั้งใจที่จะลบจริง และเข้าใจผลกระทบหลังจากนี้ที่จะเกิดขึ้น และกระทำกายภายใต้[[{{MediaWiki:Policy-url}}|นโยบาย]]',
'revdelete-suppress-text'     => "การระงับควรใช้ '''เฉพาะ''' กรณีต่อไปนี้:
* ข้อมูลส่วนบุคคลที่ไม่เหมาะสม
*: ''ที่อยู่และหมายเลขโทรศัพท์จากบ้าน, หมายเลขประกันสังคม, ฯลฯ''",
'revdelete-legend'            => 'ระบุการควบคุม:',
'revdelete-hide-text'         => 'ซ่อนข้อความรุ่นที่ปรับปรุง',
'revdelete-hide-image'        => 'ซ่อนเนื้อหาไฟล์',
'revdelete-hide-name'         => 'ซ่อนการกระทำและเป้าหมาย',
'revdelete-hide-comment'      => 'ซ่อนความเห็นการแก้ไข',
'revdelete-hide-user'         => 'ซ่อนชื่อผู้แก้ไขและหมายเลขไอพี',
'revdelete-hide-restricted'   => 'ระงับข้อมูลจากผู้ดูแลระบบเช่นเดียวกับผู้ใช้อื่น',
'revdelete-radio-same'        => '(ไม่เปลี่ยนแปลง)',
'revdelete-radio-set'         => 'ใช่',
'revdelete-radio-unset'       => 'ไม่',
'revdelete-suppress'          => 'ซ่อนข้อมูลจากผู้ดูแลระบบเช่นเดียวกับผู้ใช้ทั่วไป',
'revdelete-unsuppress'        => 'ลบการควบคุมออกสำหรับรุ่นการปรับปรุงที่ถูกเรียกกลับ',
'revdelete-log'               => 'เหตุผล:',
'revdelete-submit'            => 'ใช้กับ{{PLURAL:$1|รุ่น|รุ่น}}ที่เลือก',
'revdelete-logentry'          => 'เปลี่ยนแปลงสถานะการซ่อนรุ่นปรับปรุงของ [[$1]]',
'logdelete-logentry'          => 'การเข้าดูเหตุการณ์ที่ถูกเปลี่ยนของ [[$1]]',
'revdelete-success'           => "'''การแสดงผลของรุ่นปรับปรุงถูกกำหนดค่าเรียบร้อย'''",
'revdelete-failure'           => "'''การแสดงผลของรุ่นปรับปรุงไม่สามารถกำหนดค่าได้:'''
$1",
'logdelete-success'           => 'การเข้าดูเหตุการณ์ถูกกำหนดค่าเรียบร้อย',
'logdelete-failure'           => "'''ไม่สามารถตั้งค่าการแสดงผลของรายการแก้ไขได้:'''
$1",
'revdel-restore'              => 'เปลี่ยนทัศนวิสัย',
'pagehist'                    => 'ประวัติหน้า',
'deletedhist'                 => 'ลบประวัติ',
'revdelete-content'           => 'เนื้อหา',
'revdelete-summary'           => 'คำอธิบายโดยย่อ',
'revdelete-uname'             => 'ชื่อผู้ใช้ :',
'revdelete-restricted'        => 'จำกัดให้เฉพาะผู้ดูแลระบบขั้นพื้นฐาน',
'revdelete-unrestricted'      => 'ยกเลิกการจำกัดให้เฉพาะผู้ดูแลระบบขั้นพื้นฐาน',
'revdelete-hid'               => 'ซ่อน $1',
'revdelete-unhid'             => 'แสดง $1',
'revdelete-log-message'       => '$1 สำหรับ $2 {{PLURAL:$2|รุ่น|รุ่น}}',
'logdelete-log-message'       => '$1 สำหรับ $2 {{PLURAL:$2|เหตุการณ์|เหตุการณ์}}',
'revdelete-hide-current'      => 'เกิดความผิดพลาดในการซ่อนฉบับปรับปรุงในวันที่ $2 เวลา $1: นี่คือฉบับปรังปรุงปัจจุบัน
ไม่สามารถซ่อนได้',
'revdelete-show-no-access'    => 'เกิดความผิดพลาดในการดูฉบับปรับปรุงในวันที่ $2 เวลา $1: ฉบับปรับปรุงนี้ถูกกำหนดให้ "จำกัดการดู"
คุณไม่มีสิทธิ์ดูฉบับปรับปรุงดังกล่าว',
'revdelete-modify-no-access'  => 'เกิดความผิดพลาดในการแก้ไขฉบับปรับปรุงในวันที่ $2 เวลา $1: ฉบับปรับปรุงนี้ถูกกำหนดให้ "จำกัดการแก้ไข"
คุณไม่มีสิทธิ์แก้ไขฉบับปรับปรุงดังกล่าว',
'revdelete-modify-missing'    => 'เิกิดความผิดพลาดในการแก้ไขฉบับปรังปรุงหมายเลข $1: รายการนี้สูญหายจากฐานข้อมูล!',
'revdelete-no-change'         => "'''คำเตือน:''' ฉบับปรับปรุงวันที่ $2 เวลา $1 มีการตั้งค่าการให้ดูที่ร้องขออยู่แล้ว",
'revdelete-concurrent-change' => 'เกิดความผิดพลาดในการแก้ไขฉบับปรับปรุงในวันที่ $2 เวลา $1: สถานะของฉบับปรับปรุงได้ถูกเปลี่ยนโดยใครบางคนในขณะที่คุณพยายามแก้ไขอยู่ 
กรุณาตรวจสอบประวัติการแก้ไข',
'revdelete-only-restricted'   => 'เกิดความผิดพลาดในการซ่อนฉบับปรับปรุงในวันที่ $2 เวลา $1: คุณไม่สามารถยับยั้งผู้ดูแลระบบจากการดูฉบับปรับปรุงนี้โดยที่ไม่ได้เลือกตัวเลือกการให้ดูอื่นๆ',
'revdelete-reason-dropdown'   => '*เหตุผลโดยทั่วไปในการลบ
** ละเมิดลิขสิทธิ์
** มีข้อมูลส่วนบุคคลที่ไม่เหมาะสม
** มีข้อมูลที่อาจสร้างความเสียหาย',
'revdelete-otherreason'       => 'เหตุผลอื่นหรือเหตุผลเพิ่มเติม:',
'revdelete-reasonotherlist'   => 'เหตุผลอื่น',
'revdelete-edit-reasonlist'   => 'แก้ไขรายชื่อเหตุผลในการลบ',
'revdelete-offender'          => 'ผู้เขียนของรุ่น:',

# Suppression log
'suppressionlog'     => 'บันทึกการระงับ',
'suppressionlogtext' => 'ด้านล่างนี้คือรายการลบและระงับ รวมไปถึงเนื้อหาที่ถูกซ่อนโดยผู้ดูแลระบบ
ดู [[Special:IPBlockList|รายการหมายเลขไอพีที่ถูกระงับ]] สำหรับรายการระงับและห้ามใช้ที่ยังมีผลอยู่',

# History merging
'mergehistory'                     => 'ประวัติการรวมหน้า',
'mergehistory-header'              => 'หน้านี้ไว้ให้คุณใช้รวมรุ่นต่างๆ ในประวัติการแก้ไขของหน้าต้นทาง ไปยังหน้าใหม่.
ก่อนดำเนินการ ควรให้แน่ใจก่อนว่าการดำเนินการนี้จะไม่ทำให้ความความต่อเนื่องของประวัติหน้าเก่าๆ เสียไป.',
'mergehistory-box'                 => 'รวมรุ่นต่างๆ ของหน้าทั้งสองเข้าด้วยกัน:',
'mergehistory-from'                => 'หน้าต้นทาง:',
'mergehistory-into'                => 'หน้าปลายทาง:',
'mergehistory-list'                => 'ประวัติการแก้ไขที่สามารถรวมได้',
'mergehistory-merge'               => 'รุ่นต่อไปนี้ของหน้า [[:$1]] สามารถรวมเข้าไปยังหน้า [[:$2]] ได้.  ให้เลือกกดปุ่มเพื่อรวมเฉพาะรุ่นที่สร้างนับตั้งแต่เวลาที่กำหนดขึ้นไป.  อย่าลืมว่าการใช้ลิงก์นำทาง (navigation link) จะไปล้างค่าในช่องนี้กลับเป็นค่าตั้งต้นเหมือนเดิม.',
'mergehistory-go'                  => 'แสดงการแก้ไขที่สามารถรวมได้',
'mergehistory-submit'              => 'รวมรุ่นต่างๆ',
'mergehistory-empty'               => 'ไม่มีรุ่นที่สามารถรวมได้.',
'mergehistory-success'             => '[[:$1]] จำนวน $3 {{PLURAL:$3|รุ่น|รุ่น}} ได้ถูกรวมเข้าไปยัง [[:$2]] เรียบร้อยแล้ว',
'mergehistory-fail'                => 'ไม่สามารถรวมประวัติการแก้ไขได้ โปรดตรวจสอบค่าตัวแปรของ หน้า และ เวลา อีกครั้ง',
'mergehistory-no-source'           => 'ไม่มีหน้าต้นทาง $1 อยู่ในสารบบ',
'mergehistory-no-destination'      => 'ไม่มีหน้าปลายทาง $1 อยู่ในสารบบ',
'mergehistory-invalid-source'      => 'หัวเรื่องของหน้าต้นทางต้องตรงตามข้อกำหนด (เช่น ไม่มีตัวอักษรที่ไม่สามารถใช้ในหัวเรื่องได้)',
'mergehistory-invalid-destination' => 'หัวเรื่องของหน้าปลายทางต้องตรงตามข้อกำหนด (เช่น ไม่มีตัวอักษรที่ไม่สามารถใช้ในหัวเรื่องได้)',
'mergehistory-autocomment'         => 'ย้าย [[:$1]] ไปยัง [[:$2]]',
'mergehistory-comment'             => 'ย้าย [[:$1]] ไปยัง [[:$2]]: $3',
'mergehistory-same-destination'    => 'หน้าต้นทางและปลายทางเป็นหน้าเดียวกันไม่ได้',
'mergehistory-reason'              => 'เหตุผล:',

# Merge log
'mergelog'           => 'ปูมการรวมหน้า',
'pagemerge-logentry' => 'ย้าย [[$1]] ไปยัง [[$2]] (รุ่นขึ้นอยู่กับ $3)',
'revertmerge'        => 'ยกเลิกการรวมหน้า',
'mergelogpagetext'   => 'ด้านล่างนี้แสดงรายการล่าสุดของการรวมประวัติหน้าหนึ่งๆ เข้ากับอีกหน้าหนึ่ง',

# Diffs
'history-title'            => 'ประวัติการแก้ไขหน้า "$1"',
'difference'               => '(ความแตกต่างระหว่างรุ่นปรับปรุง)',
'lineno'                   => 'แถว $1:',
'compareselectedversions'  => 'เปรียบเทียบสองรุ่นที่เลือก',
'showhideselectedversions' => 'แสดง/ซ่อน รุ่นที่เลือก',
'editundo'                 => 'ย้อน',
'diff-multi'               => '({{PLURAL:$1|การแก้ไขหนึ่งรุ่นระหว่างรุ่นที่เปรียบเทียบ|การแก้ไข $1 รุ่นระหว่างรุ่นที่เปรียบเทียบ}}ไม่แสดงผล)',

# Search results
'searchresults'                    => 'ค้นหา',
'searchresults-title'              => 'ผลการค้นหาสำหรับ "$1"',
'searchresulttext'                 => 'วิธีการค้นหาใน {{SITENAME}} ดูวิธีใช้งานเพิ่มที่ [[{{MediaWiki:Helppage}}|{{int:help}}]]',
'searchsubtitle'                   => 'คุณได้สืบค้นเพื่อหา \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ทุกหน้าที่ขึ้นต้นด้วย "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ทุกหน้าที่โยงมาที่ "$1"]])',
'searchsubtitleinvalid'            => "ค้นหาเกี่ยวกับ '''$1'''",
'toomanymatches'                   => 'หัวข้อที่พบมีมากเกินไป กรุณาใช้คำค้นหาอื่น',
'titlematches'                     => 'พบชื่อหัวข้อนี้',
'notitlematches'                   => 'ไม่พบชื่อหัวข้อนี้',
'textmatches'                      => 'พบคำนี้ในหน้า',
'notextmatches'                    => 'ไม่พบข้อความในหน้า',
'prevn'                            => 'ก่อนหน้า {{PLURAL:$1|$1}}',
'nextn'                            => 'ถัดไป {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|ผลลัพธ์|ผลลัพธ์}}ก่อนหน้า',
'nextn-title'                      => '$1 {{PLURAL:$1|ผลลัพธ์|ผลลัพธ์}}ถัดไป',
'shown-title'                      => 'แสดง $1 {{PLURAL:$1|ผลลัพธ์|ผลลัพธ์}}ต่อหน้า',
'viewprevnext'                     => 'ดู ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'ตัวเลือกการค้นหา',
'searchmenu-exists'                => "'''มีหน้าที่ชื่อว่า \"[[:\$1]]\" บนวิกินี้'''",
'searchmenu-new'                   => "'''สร้างหน้า \"[[:\$1]]\" บนวิกินี้'''",
'searchhelp-url'                   => 'Help:วิธีการใช้งาน',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|สืบค้นหน้าที่มีคำขึ้นต้นเหล่านี้]]',
'searchprofile-articles'           => 'หน้าบทความ',
'searchprofile-project'            => 'วิธีใช้และหน้าโครงการ',
'searchprofile-images'             => 'มัลติมีเดีย',
'searchprofile-everything'         => 'ทุกสิ่งทั้งหมด',
'searchprofile-advanced'           => 'ชั้นสูง',
'searchprofile-articles-tooltip'   => 'ค้นหาใน $1',
'searchprofile-project-tooltip'    => 'ค้นหาใน $1',
'searchprofile-images-tooltip'     => 'ค้นหาไฟล์',
'searchprofile-everything-tooltip' => 'ค้นเนื้อหาทั้งหมด (รวมถึงหน้าอภิปราย)',
'searchprofile-advanced-tooltip'   => 'ค้นหาในเนมสเปซที่เลือกเอง',
'search-result-size'               => '$1 ({{PLURAL:$2|1 คำ|$2 คำ}})',
'search-result-score'              => 'ความเกี่ยวข้อง : $1%',
'search-redirect'                  => '(เปลี่ยนทาง $1)',
'search-section'                   => '(ส่วน $1)',
'search-suggest'                   => 'คุณอาจหมายถึง : $1',
'search-interwiki-caption'         => 'โครงการพี่น้อง',
'search-interwiki-default'         => '$1 ผลลัพธ์:',
'search-interwiki-more'            => '(มากกว่า)',
'search-mwsuggest-enabled'         => 'พร้อมคำแนะนำ',
'search-mwsuggest-disabled'        => 'ไม่รวมคำแนะนำ',
'search-relatedarticle'            => 'สัมพันธ์',
'mwsuggest-disable'                => 'ยกเลิกการแนะนำในลักษณะเอแจ็กซ์',
'searcheverything-enable'          => 'สืบค้นในเนมสเปซทั้งหมด',
'searchrelated'                    => 'สัมพันธ์',
'searchall'                        => 'ทั้งหมด',
'showingresults'                   => "แสดง $1 รายการ เริ่มต้นจากรายการที่ '''$2'''",
'showingresultsnum'                => "แสดง $3 รายการ เริ่มต้นจากรายการที่  '''$2'''",
'showingresultsheader'             => "{{PLURAL:$5|ผลการสืบค้น '''$1''' จาก '''$3'''|ผลการสืบค้น '''$1 - $2''' จาก '''$3'''}} สำหรับ '''$4'''",
'nonefound'                        => "'''คำเตือน''': เนมสเปซบางส่วนจะถูกค้นหาเอง
ให้ลองเลือกคำขึ้นต้นการค้นหาด้วย ''all:'' สำหรับค้นหาเนื้อหาทั้งหมด (รวมถึง หน้าอภิปราย แม่แบบ ฯลฯ) หรือเลือกเนมสเปซที่ต้องการ",
'search-nonefound'                 => 'ไม่มีผลลัพธ์ตามคำค้นที่กำหนด',
'powersearch'                      => 'ค้นหาระดับสูง',
'powersearch-legend'               => 'ค้นหาระดับสูง',
'powersearch-ns'                   => 'ค้นหาในเนมสเปซ:',
'powersearch-redir'                => 'รายการหน้าเปลี่ยนทาง',
'powersearch-field'                => 'ค้นหา',
'powersearch-togglelabel'          => 'เลือก:',
'powersearch-toggleall'            => 'ทั้งหมด',
'powersearch-togglenone'           => 'ไม่เลือก',
'search-external'                  => 'ค้นหาจากภายนอก',
'searchdisabled'                   => 'ระบบการค้นหาใน {{SITENAME}} ไม่เปิดการใช้งาน คุณสามารถค้นหาในกูเกิลหรือเซิร์ชเอนจินอื่น โปรดจำไว้ว่าเนื้อหาของ {{SITENAME}} บนเซิร์ชเอนจินอาจเป็นข้อมูลเก่า',

# Quickbar
'qbsettings'               => 'แถบพิเศษ',
'qbsettings-none'          => 'ไม่มี',
'qbsettings-fixedleft'     => 'อยู่ทางซ้าย',
'qbsettings-fixedright'    => 'อยู่ทางขวา',
'qbsettings-floatingleft'  => 'ด้านซ้าย',
'qbsettings-floatingright' => 'ด้านขวา',

# Preferences page
'preferences'                   => 'ตั้งค่าส่วนตัว',
'mypreferences'                 => 'ตั้งค่าส่วนตัว',
'prefs-edits'                   => 'จำนวนการแก้ไข:',
'prefsnologin'                  => 'ไม่ได้ล็อกอิน',
'prefsnologintext'              => 'คุณต้อง<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ล็อกอิน]</span> ก่อนเพื่อที่จะตั้งค่าส่วนตัวได้',
'changepassword'                => 'เปลี่ยนรหัสผ่าน',
'prefs-skin'                    => 'หน้าตา',
'skin-preview'                  => 'แสดงตัวอย่าง',
'prefs-math'                    => 'คณิตศาสตร์',
'datedefault'                   => 'ค่าตั้งต้น',
'prefs-datetime'                => 'วันที่และเวลา',
'prefs-personal'                => 'รายละเอียดผู้ใช้',
'prefs-rc'                      => 'ปรับปรุงล่าสุด',
'prefs-watchlist'               => 'รายการเฝ้าดู',
'prefs-watchlist-days'          => 'จำนวนวันที่แสดงในรายการเฝ้าดู:',
'prefs-watchlist-days-max'      => '(มากสุด 7 วัน)',
'prefs-watchlist-edits'         => 'จำนวนการแก้ไขที่แสดงในรายการเฝ้าดูที่มีการคลี่ออก:',
'prefs-watchlist-edits-max'     => '(จำนวนมากสุด: 1000)',
'prefs-watchlist-token'         => 'สัญลักษณ์รายการเฝ้าดู:',
'prefs-misc'                    => 'เบ็ดเตล็ด',
'prefs-resetpass'               => 'เปลี่ยนรหัสผ่าน',
'prefs-email'                   => 'การตั้งค่าอีเมล',
'prefs-rendering'               => 'รูปลักษณ์',
'saveprefs'                     => 'บันทึก',
'resetprefs'                    => 'กลับไปยังค่าที่บันทึก',
'restoreprefs'                  => 'บันทึกคืนค่าตั้งต้นทั้งหมด',
'prefs-editing'                 => 'การแก้ไข',
'prefs-edit-boxsize'            => 'ขนาดหน้าจอกล่องแก้ไข',
'rows'                          => 'แถว:',
'columns'                       => 'คอลัมน์:',
'searchresultshead'             => 'สืบค้น',
'resultsperpage'                => 'รายการต่อหน้า:',
'contextlines'                  => 'บรรทัดที่แสดงต่อรายการ:',
'contextchars'                  => 'ตัวอักษรต่อบรรทัด:',
'stub-threshold'                => 'ขีดแบ่งสำหรับ <a href="#" class="stub">รูปแบบโครง</a> (ความยาวบทความ):',
'recentchangesdays'             => 'จำนวนวันที่แสดงในปรับปรุงล่าสุด:',
'recentchangesdays-max'         => '(สูงสุด $1 {{PLURAL:$1|วัน|วัน}})',
'recentchangescount'            => 'จำนวนการแก้ไขที่แสดงโดยปริยาย:',
'prefs-help-recentchangescount' => 'นี่รวมไปถึงการแก้ไขล่าสุด, ประวิติของหน้า, และรายการแก้ไขอื่นๆ',
'prefs-help-watchlist-token'    => 'การเติมช่องนี้ด้วยรหัสลับจะสร้าง RSS feed สำหรับรายการเฝ้าดูของคุณ
ผู้ใดที่รู้รหัสในช่องนี้จะสามารถดูรายการเฝ้าดูของคุณได้ ดังนั้นเลือกรหัสที่ปลอดภัย
นี่คือรหัสที่สุ่มเลือกขึ้นมาที่คุณสามารถใช้ได้: $1',
'savedprefs'                    => 'การตั้งค่าของคุณได้ถูกบันทึกแล้ว',
'timezonelegend'                => 'เขตเวลา:',
'localtime'                     => 'เวลาท้องถิ่น',
'timezoneuseserverdefault'      => 'ใช้ค่าตั้งต้นของเซิร์ฟเวอร์',
'timezoneuseoffset'             => 'อื่นๆ (ระบุส่วนต่างเวลา)',
'timezoneoffset'                => 'เวลาต่าง¹:',
'servertime'                    => 'เวลาที่เซิร์ฟเวอร์:',
'guesstimezone'                 => 'เรียกค่าจากเว็บเบราว์เซอร์',
'timezoneregion-africa'         => 'แอฟริกา',
'timezoneregion-america'        => 'อเมริกา',
'timezoneregion-antarctica'     => 'แอนตาร์กติก',
'timezoneregion-arctic'         => 'อาร์กติก',
'timezoneregion-asia'           => 'เอเชีย',
'timezoneregion-atlantic'       => 'มหาสมุทรแอตแลนติก',
'timezoneregion-australia'      => 'ออสเตรเลีย',
'timezoneregion-europe'         => 'ยุโรป',
'timezoneregion-indian'         => 'มหาสมุทรอินเดีย',
'timezoneregion-pacific'        => 'มหาสมุทรแปซิฟิก',
'allowemail'                    => 'เปิดรับอีเมลจากผู้ใช้อื่น',
'prefs-searchoptions'           => 'ตั้งค่าการค้นหา',
'prefs-namespaces'              => 'เนมสเปซ',
'defaultns'                     => 'หรือค้นหาในเนมสเปซต่อไปนี้:',
'default'                       => 'ค่าตั้งต้น',
'prefs-files'                   => 'ไฟล์',
'prefs-custom-css'              => 'สไตล์ชีตปรับแต่งเอง',
'prefs-custom-js'               => 'จาวาสคริปต์ปรับแต่งเอง',
'prefs-reset-intro'             => 'คุณสามารถใช้หน้านี้เพื่อล้างการตั้งค่าของคุณกลับไปเป็นค่าตั้งต้นทั้งหมด
เมื่อล้างแล้วจะไม่สามารถย้อนกลับได้',
'prefs-emailconfirm-label'      => 'การยืนยันอีเมล:',
'prefs-textboxsize'             => 'ขนาดของหน้าต่างแก้ไข',
'youremail'                     => 'อีเมล:',
'username'                      => 'ชื่อผู้ใช้:',
'uid'                           => 'รหัสผู้ใช้:',
'prefs-memberingroups'          => 'สมาชิกใน{{PLURAL:$1|กลุ่ม|กลุ่ม}}:',
'prefs-registration'            => 'วันเวลาที่ลงทะเบียน:',
'yourrealname'                  => 'ชื่อจริง:',
'yourlanguage'                  => 'ภาษา:',
'yourvariant'                   => 'ภาษาอื่น',
'yournick'                      => 'ลายเซ็น:',
'prefs-help-signature'          => 'คอมเมนต์ในหน้าูพูดคุยควรจะเซ็นลงท้ายด้วย "<nowiki>~~~~</nowiki>" ซึ่งจะถูกแปลงเป็นลายเซ็นต์และลงวันที่เขียน',
'badsig'                        => 'ลายเซ็นที่ใช้ผิดพลาด กรุณาตรวจสอบคำสั่งเอชทีเอ็มแอล',
'badsiglength'                  => 'ลายเซ็นยาวเกินไป  ต้องมีความยาวไม่เกิน $1 {{PLURAL:$1|ตัวอักษร|ตัวอักษร}}',
'yourgender'                    => 'เพศ:',
'gender-unknown'                => 'ไม่ระบุ',
'gender-male'                   => 'ชาย',
'gender-female'                 => 'หญิง',
'prefs-help-gender'             => 'เป็นข้อมูลเสริม: ใช้เพื่อให้ซอฟต์แวร์สามารถแยกแยะเพศของผู้ใช้ได้  ข้อมูลนี้จะเป็นที่เปิดเผย',
'email'                         => 'อีเมล',
'prefs-help-realname'           => 'ไม่จำเป็นต้องใส่ชื่อจริง โดยชื่อที่ใส่นั้นจะถูกใช้เพียงแค่แสดงผลงานที่คุณได้ร่วมสร้างไว้',
'prefs-help-email'              => 'ไม่จำเป็นต้องใส่อีเมล แต่กรุณาตระหนักว่าหากคุณลืมรหัสผ่าน รหัสผ่านใหม่จะถูกส่งผ่านอีเมลของคุณ
และผู้ใช้ผู้อื่นสามารถติดต่อคุณผ่านอีเมลที่ใส่นี้ จากหน้าผู้ใช้ หรือคุยกับผู้ใช้ของคุณ แต่อีเมลของคุณจะไม่ปรากฏให้ผู้อื่นเห็นแต่อย่างใด',
'prefs-help-email-required'     => 'ต้องการที่อยู่อีเมล',
'prefs-info'                    => 'ข้อมูลเบื้องต้น',
'prefs-i18n'                    => 'ระบบภาษาหรือเขตพื้นที่',
'prefs-signature'               => 'ลายเซ็น',
'prefs-dateformat'              => 'รูปแบบวันที่',
'prefs-timeoffset'              => 'ส่วนต่างเวลา',
'prefs-advancedediting'         => 'การตั้งค่าขั้นสูง',
'prefs-advancedrc'              => 'การตั้งค่าขั้นสูง',
'prefs-advancedrendering'       => 'การตั้งค่าขั้นสูง',
'prefs-advancedsearchoptions'   => 'การตั้งค่าขั้นสูง',
'prefs-advancedwatchlist'       => 'การตั้งค่าขั้นสูง',
'prefs-displayrc'               => 'ค่าการแสดงผล',
'prefs-diffs'                   => 'ส่วนต่างการแก้ไข',

# User rights
'userrights'                  => 'บริหารสิทธิผู้ใช้',
'userrights-lookup-user'      => 'บริหารสิทธิผู้ใช้',
'userrights-user-editname'    => 'ใส่ชื่อผู้ใช้:',
'editusergroup'               => 'แก้ไขผู้ใช้',
'editinguser'                 => "กำลังแก้ไขสิทธิของผู้ใช้ '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'แก้ไขผู้ใช้',
'saveusergroups'              => 'ตกลง',
'userrights-groupsmember'     => 'สมาชิกในกลุ่ม:',
'userrights-groups-help'      => 'คุณสามารถเปลี่ยนแปลงกลุ่มที่ผู้ใช้รายนี้อยู่ใน:
* กล่องที่ถูกเลือกหมายความว่าผู้ใช้อยู่ในกลุ่มนั้น
* กล่องที่ไม่ถูกเลือกหมายความว่าผู้ใช้ไม่ได้อยู่ในกลุ่มนั้น
* เครื่องหมาย * ชี้ว่าคุณไม่สามารถเอากลุ่มนั้นออกได้เมื่อใดก็ตามที่คุณเพิ่มกลุ่มนั้นไปแล้ว หรือ ในทำนองเดียวกัน',
'userrights-reason'           => 'เหตุผล:',
'userrights-no-interwiki'     => 'คุณไม่ได้รับสิทธิในการแก้ไขสิทธิของผู้ใช้บนวิกิอื่นๆ',
'userrights-nodatabase'       => 'ไม่มีฐานข้อมูล $1 อยู่ หรือ ฐานข้อมูลอยู่บนเครื่องอื่น',
'userrights-nologin'          => 'คุณต้อง[[Special:UserLogin|ล็อกอิน]]ด้วยบัญชีผู้ใช้ที่เป็นผู้ดูแลสิทธิแต่งตั้ง จึงจะสามารถกำหนดสิทธิต่างๆ ของผู้ใช้ได้',
'userrights-notallowed'       => 'บัญชีผู้ใช้ของคุณไม่ได้รับสิทธิในการแก้ไขสิทธิของผู้ใช้',
'userrights-changeable-col'   => 'กลุ่มที่คุณสามารถเปลี่ยนได้',
'userrights-unchangeable-col' => 'กลุ่มที่คุณไม่สามารถเปลี่ยนได้',

# Groups
'group'               => 'กลุ่ม:',
'group-user'          => 'ผู้ใช้ใหม่',
'group-autoconfirmed' => 'ผู้ใช้ทั่วไป',
'group-bot'           => 'บอต',
'group-sysop'         => 'ผู้ดูแล',
'group-bureaucrat'    => 'ผู้ดูแลสิทธิแต่งตั้ง',
'group-suppress'      => 'ผู้ดูแลระดับสูง',
'group-all'           => '(ทั้งหมด)',

'group-user-member'          => 'ผู้ใช้ใหม่',
'group-autoconfirmed-member' => 'ผู้ใช้ทั่วไป',
'group-bot-member'           => 'บอต',
'group-sysop-member'         => 'ผู้ดูแล',
'group-bureaucrat-member'    => 'ผู้ดูแลสิทธิแต่งตั้ง',
'group-suppress-member'      => 'ผู้ดูแลระดับสูง',

'grouppage-user'          => '{{ns:project}}:ผู้ใช้',
'grouppage-autoconfirmed' => '{{ns:project}}:ผู้ใช้ทั่วไป',
'grouppage-bot'           => '{{ns:project}}:บอต',
'grouppage-sysop'         => '{{ns:project}}:ผู้ดูแล',
'grouppage-bureaucrat'    => '{{ns:project}}:ผู้ดูแลสิทธิแต่งตั้ง',
'grouppage-suppress'      => '{{ns:project}}:ผู้ดูแลระดับสูง',

# Rights
'right-read'                  => 'อ่านหน้าต่าง ๆ',
'right-edit'                  => 'แก้ไขหน้า',
'right-createpage'            => 'สร้างหน้า (ที่ไม่ใช่หน้าอภิปราย)',
'right-createtalk'            => 'สร้างหน้าอภิปราย',
'right-createaccount'         => 'สร้างบัญชีผู้ใช้ใหม่',
'right-minoredit'             => 'ทำเครื่องหมายการแก้ไขเล็กน้อย',
'right-move'                  => 'ย้ายหน้า',
'right-move-subpages'         => 'ย้ายหน้าพร้อมกับหน้ารองด้วย',
'right-move-rootuserpages'    => 'ย้ายหน้าผู้ใช้หลัก',
'right-movefile'              => 'ย้ายไฟล์',
'right-suppressredirect'      => 'ไม่สร้างหน้าเปลี่ยนทางเมื่อทำการย้ายหน้าไปยังชื่อใหม่',
'right-upload'                => 'อัปโหลดไฟล์',
'right-reupload'              => 'เซฟทับไฟล์เดิม',
'right-reupload-own'          => 'เขียนทับไฟล์เดิมที่อัปโหลดด้วยตนเอง',
'right-reupload-shared'       => 'เขียนทับไฟล์บนคลังเก็บสื่อส่วนกลาง',
'right-upload_by_url'         => 'อัปโหลดไฟล์จาก URL',
'right-purge'                 => 'ล้างแคชของเว็บไซต์โดยไม่จำเป็นต้องยืนยัน',
'right-autoconfirmed'         => 'แก้ไขหน้าที่ถูกกึ่งล็อก',
'right-bot'                   => 'กำหนดว่าเป็นกระบวนการอัตโนมัติ',
'right-nominornewtalk'        => 'ไม่มีการแก้ไขเล็กน้อยที่หน้าสนทนาที่ทำให้การเตือนข้อความใหม่ปรากฎ',
'right-apihighlimits'         => 'ใช้ข้อจำกัดที่สูงขึ้นในคำสั่งเอพีไอ',
'right-writeapi'              => 'ใช้การเขียนเอพีไอ',
'right-delete'                => 'ลบหน้า',
'right-bigdelete'             => 'ลบหน้าที่มีประวัติหน้าขนาดใหญ่',
'right-deleterevision'        => 'ลบและเรียกคืนรุ่นที่เจาะจงของหน้าต่าง ๆ',
'right-deletedhistory'        => 'ดูรายการประวัติที่ถูกลบ โดยไม่มีข้อความที่เกี่ยวข้อง',
'right-deletedtext'           => 'เรียกดูข้อความที่ถูกลบและความเปลี่ยนแปลงระหว่างรุ่นที่ถูกลบ',
'right-browsearchive'         => 'ค้นหาหน้าที่ถูกลบ',
'right-undelete'              => 'เรียกคืนหน้า',
'right-suppressrevision'      => 'ดูและเรียกคืนรุ่นที่ซ่อนโดยผู้ดูแลระบบขั้นพื้นฐาน',
'right-suppressionlog'        => 'ดูบันทึกส่วนตัว',
'right-block'                 => 'บล็อกผู้ใช้อื่น ๆ จากการแก้ไข',
'right-blockemail'            => 'บล็อกผู้ใช้จากการส่งอีเมล',
'right-hideuser'              => 'บล็อกผู้ใช้และซ่อนไม่ให้ผู้อื่นเห็น',
'right-ipblock-exempt'        => 'ผ่านการบล็อกหมายเลขไอพี บล็อกแบบอัตโนมัติ และบล็อกเป็นช่วง',
'right-proxyunbannable'       => 'ผ่านการบล็อกแบบอัตโนมัติของพร็อกซี',
'right-protect'               => 'เปลี่ยนระดับการล็อกและแก้ไขหน้าที่ถูกล็อก',
'right-editprotected'         => 'แก้ไขหน้าที่ถูกล็อก (ที่ไม่ล็อกแบบสืบทอด)',
'right-editinterface'         => 'แก้ไขอินเตอร์เฟซของผู้ใช้',
'right-editusercssjs'         => 'แก้ไข CSS และ JS ของผู้ใช้คนอื่น',
'right-editusercss'           => 'แก้ไข CSS ของผู้ใช้คนอื่น',
'right-edituserjs'            => 'แก้ไข JS ของผู้ใช้คนอื่น',
'right-rollback'              => 'ย้อนการแก้ไขของผู้ใช้ล่าสุดที่แก้ไขบางหน้าโดยเฉพาะอย่างรวดเร็ว',
'right-markbotedits'          => 'ทำเครื่องหมายการย้อนว่าเป็นการแก้ไขโดยบอต',
'right-noratelimit'           => 'ไม่มีผลกระทบจากการจำกัดสิทธิตามเวลา',
'right-import'                => 'นำเข้าหน้าจากวิกิอื่น',
'right-importupload'          => 'นำเข้าหน้าจากไฟล์ที่อัปโหลด',
'right-patrol'                => 'ทำเครื่องหมายการแก้ไขของผู้อื่นว่าตรวจสอบแล้ว',
'right-autopatrol'            => 'ตั้งให้การแก้ไขของตนเองว่าตรวจสอบแล้วโดยอัตโนมัติ',
'right-patrolmarks'           => 'ดูการเปลี่ยนแปลงล่าสุดของการทำเครื่องหมายตรวจสอบ',
'right-unwatchedpages'        => 'ดูรายชื่อของหน้าที่ไม่ถูกเฝ้าดูโดยผู้ใช้ใด ๆ',
'right-trackback'             => 'ส่งการติดตามกลับ',
'right-mergehistory'          => 'รวมประวัติการแก้ไขหน้า',
'right-userrights'            => 'แก้ไขสิทธิผู้ใช้ทั้งหมด',
'right-userrights-interwiki'  => 'แก้ไขสิทธิของผู้ใช้อื่นบนวิกิอื่น',
'right-siteadmin'             => 'ล็อกและปลดล็อกฐานข้อมูล',
'right-reset-passwords'       => 'ตั้งรหัสผ่านของผู้ใช้อื่นใหม่',
'right-override-export-depth' => 'ส่งออกหน้า รวมหน้าที่เชื่อมโยงกับหน้านี้สูงสุด 5 ลำดับชั้น',
'right-sendemail'             => 'ส่งอีเมลไปยังผู้ใช้อื่นๆ',

# User rights log
'rightslog'      => 'บันทึกการเปลี่ยนสิทธิผู้ใช้',
'rightslogtext'  => 'ส่วนนี้คือบันทึกการเปลี่ยนแปลงของสิทธิผู้ใช้',
'rightslogentry' => '$1 ถูกเปลี่ยนกลุ่มจาก $2 เป็น $3',
'rightsnone'     => '(ไม่มี)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'อ่านหน้านี้',
'action-edit'                 => 'แก้ไขหน้านี้',
'action-createpage'           => 'สร้างหน้า',
'action-createtalk'           => 'สร้างหน้าอภิปราย',
'action-createaccount'        => 'สร้างบัญชีผู้ใช้นี้',
'action-minoredit'            => 'เป็นการแก้ไขเล็กน้อย',
'action-move'                 => 'ย้ายหน้านี้',
'action-move-subpages'        => 'ย้ายหน้านี้และหน้าย่อยของหน้านี้',
'action-move-rootuserpages'   => 'ย้ายหน้าผู้ใช้หลัก',
'action-movefile'             => 'ย้ายไฟล์นี้',
'action-upload'               => 'อัปโหลดไฟล์นี้',
'action-reupload'             => 'อัปโหลดทับไฟล์ที่มีอยู่แล้วนี้',
'action-reupload-shared'      => 'เขียนไฟล์นี้ทับบนคลังส่วนกลาง',
'action-upload_by_url'        => 'อัปโหลดไฟล์นี้จากที่อยู่ยูอาร์แอล',
'action-writeapi'             => 'ใช้การเขียนเอพีไอ',
'action-delete'               => 'ลบหน้านี้',
'action-deleterevision'       => 'ลบรุ่นนี้',
'action-deletedhistory'       => 'ดูประวัติที่ถูกลบของหน้านี้',
'action-browsearchive'        => 'ค้นหาหน้าที่ถูกลบ',
'action-undelete'             => 'เรียกคืนหน้านี้',
'action-suppressrevision'     => 'ตรวจดูและเรียกคืนรุ่นที่ซ่อนอยู่นี้',
'action-suppressionlog'       => 'ดูบันทึกส่วนตัว',
'action-block'                => 'บล็อกผู้ใช้รายนี้จากการแก้ไข',
'action-protect'              => 'เปลี่ยนระดับการล็อกสำหรับหน้านี้',
'action-import'               => 'นำเข้าหน้านี้มาจากวิกิอื่น',
'action-importupload'         => 'นำเข้าหน้านี้จากไฟล์ที่อัปโหลดแล้ว',
'action-patrol'               => 'ทำเครื่องหมายการแก้ไขของผู้ใช้อื่นว่าตรวจแล้ว',
'action-autopatrol'           => 'ทำเครื่องหมายการแก้ไขของคุณว่าตรวจแล้ว',
'action-unwatchedpages'       => 'ดูรายการของหน้าที่ไม่มีผู้เฝ้าดู',
'action-trackback'            => 'ส่งการติดตามกลับ',
'action-mergehistory'         => 'ประสานประวัติของหน้านี้',
'action-userrights'           => 'แก้ไขสิทธิผู้ใช้ทั้งหมด',
'action-userrights-interwiki' => 'แก้ไขสิทธิผู้ใช้สำหรับวิกินี้',
'action-siteadmin'            => 'ล็อกหรือปลดล็อกฐานข้อมูล',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|การแก้ไข|การแก้ไข}}',
'recentchanges'                     => 'ปรับปรุงล่าสุด',
'recentchanges-legend'              => 'ตัวเลือกปรับปรุงล่าสุด',
'recentchangestext'                 => 'ในหน้านี้เป็นรายการล่าสุดที่มีการปรับปรุง',
'recentchanges-feed-description'    => 'ฟีดนี้แสดงการเปลี่ยนแปลงล่าสุด',
'recentchanges-label-legend'        => 'สัญลักษณ์: $1',
'recentchanges-legend-newpage'      => '$1 - หน้าใหม่',
'recentchanges-label-newpage'       => 'การแก้ไขนี้เป็นการสร้างหน้าใหม่',
'recentchanges-legend-minor'        => '$1 - การแก้ไขเล็กน้อย',
'recentchanges-label-minor'         => 'เป็นการแก้ไขเล็กน้อย',
'recentchanges-legend-bot'          => '$1 - การแก้ไขโดยบอต',
'recentchanges-label-bot'           => 'การแก้ไขนี้กระทำโดยบอต',
'recentchanges-legend-unpatrolled'  => '$1 - การแก้ไขที่รอตรวจสอบ',
'recentchanges-label-unpatrolled'   => 'การแก้ไขนี้ยังไม่ได้ตรวจสอบ',
'rcnote'                            => "รายการด้านล่างคือการแก้ไข {{PLURAL:$1|'''1''' รายการ|ล่าสุด '''$1''' รายการ}} ในช่วง {{PLURAL:$2|1 วัน|'''$2''' วัน}}ที่ผ่านมา ตั้งแต่วันที่ $5; $4",
'rcnotefrom'                        => "แสดงการเปลี่ยนแปลงตั้งแต่ '''$2''' (แสดง '''$1''' รายการ)",
'rclistfrom'                        => 'แสดงการเปลี่ยนแปลงตั้งแต่ $1',
'rcshowhideminor'                   => '$1การแก้ไขเล็กน้อย',
'rcshowhidebots'                    => '$1บอต',
'rcshowhideliu'                     => '$1ผู้ใช้ล็อกอิน',
'rcshowhideanons'                   => '$1ผู้ใช้นิรนาม',
'rcshowhidepatr'                    => '$1การตรวจตรา',
'rcshowhidemine'                    => '$1การแก้ไขของฉัน',
'rclinks'                           => 'แสดงการปรับปรุงล่าสุด $1 รายการ ในช่วง $2 วันที่ผ่านมา;<br />$3',
'diff'                              => 'ต่าง',
'hist'                              => 'ประวัติ',
'hide'                              => 'ซ่อน',
'show'                              => 'แสดง',
'minoreditletter'                   => 'ล',
'newpageletter'                     => 'ม',
'boteditletter'                     => 'บ',
'unpatrolledletter'                 => '!',
'number_of_watching_users_pageview' => '[$1 คนเฝ้าดู]',
'rc_categories'                     => 'จำกัดเฉพาะหมวดหมู่ (แยกด้วย "|")',
'rc_categories_any'                 => 'ใดๆ',
'newsectionsummary'                 => '/* $1 */ หัวข้อใหม่',
'rc-enhanced-expand'                => 'แสดงรายละเอียด (จำเป็นต้องใช้จาวาสคริปต์)',
'rc-enhanced-hide'                  => 'ซ่อนรายละเอียด',

# Recent changes linked
'recentchangeslinked'          => 'ปรับปรุงที่เกี่ยวโยง',
'recentchangeslinked-feed'     => 'ปรับปรุงที่เกี่ยวโยง',
'recentchangeslinked-toolbox'  => 'ปรับปรุงที่เกี่ยวโยง',
'recentchangeslinked-title'    => 'การปรับปรุงที่ "$1" โยงมา',
'recentchangeslinked-noresult' => 'ไม่มีการเปลี่ยนแปลงในหน้าที่ถูกโยงไป ในช่วงเวลาที่กำหนด',
'recentchangeslinked-summary'  => "หน้านี้แสดงรายการปรับปรุงล่าสุดของหน้าที่ถูกโยงไป (หรือไปยังหน้าต่าง ๆ ของหมวดหมู่ที่กำหนด) โดยหน้าที่อยู่ใน[[Special:Watchlist|รายการเฝ้าดู]]แสดงเป็น'''ตัวหนา'''",
'recentchangeslinked-page'     => 'ชื่อหน้า:',
'recentchangeslinked-to'       => 'แสดงการเปลี่ยนแปลงที่เชื่อมโยงมายังหน้านี้แทน',

# Upload
'upload'                      => 'อัปโหลด',
'uploadbtn'                   => 'อัปโหลด',
'reuploaddesc'                => 'กลับไปสู่หน้าอัปโหลด',
'upload-tryagain'             => 'ส่งคำอธิบายไฟล์ที่ปรับแต่งแล้ว',
'uploadnologin'               => 'ไม่ได้ล็อกอิน',
'uploadnologintext'           => 'ต้องทำการ[[Special:UserLogin|ล็อกอิน]]ก่อนถึงจะอัปโหลดไฟล์ได้',
'upload_directory_missing'    => 'ไดเรกทอรีสำหรับอัปโหลด ($1) หายไป และไม่สามารถสร้างขึ้นใหม่โดยเว็บเซิร์ฟเวอร์',
'upload_directory_read_only'  => 'ไม่สามารถเก็บข้อมูลในไดเรกทอรี ($1) ปัญหาเกิดที่เว็บเซิร์ฟเวอร์',
'uploaderror'                 => 'เกิดความขัดข้องในการอัปโหลด',
'uploadtext'                  => "กรุณาใช้แบบฟอร์มด้านล่างในการอัปโหลดไฟล์
สำหรับการดูหรือการค้นหาไฟล์ที่เคยอัปโหลดก่อนหน้านี้ ให้ไปที่[[Special:FileList|รายชื่อไฟล์ที่ถูกอัปโหลด]] การอัปโหลดและการอัปโหลดซ้ำดูได้ที่[[Special:Log/upload|บันทึกการอัปโหลด]] และการลบไฟล์ดูได้ที่[[Special:Log/delete|บันทึกการลบ]]

ถ้าต้องการแทรกไฟล์ลงในหน้าหนึ่งๆ ให้ใช้คำสั่งหนึ่งในรูปแบบต่อไปนี้
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' เพื่อใช้รูปขนาดเต็ม
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|ข้อความอธิบาย]]</nowiki></tt>''' เพื่อใช้รูปย่อขนาดกว้าง 200 พิกเซลในกล่องที่จัดชิดซ้าย โดยมี \"ข้อความอธิบาย\" เป็นคำบรรยายใต้ภาพ
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' สำหรับการเชื่อมโยงไฟล์โดยตรง โดยไม่ปรากฏไฟล์นั้นออกมา",
'upload-permitted'            => 'ชนิดชองไฟล์ที่อนุญาตให้ใช้ได้: $1',
'upload-preferred'            => 'ชนิดของไฟล์ที่ควรใช้: $1',
'upload-prohibited'           => 'ชนิดของไฟล์ที่ไม่อนุญาตให้ใช้: $1',
'uploadlog'                   => 'บันทึกการอัปโหลด',
'uploadlogpage'               => 'บันทึกการอัปโหลด',
'uploadlogpagetext'           => 'รายการแสดงไฟล์ที่อัปโหลดล่าสุด',
'filename'                    => 'ชื่อไฟล์',
'filedesc'                    => 'รายละเอียดไฟล์',
'fileuploadsummary'           => 'รายละเอียดไฟล์:',
'filereuploadsummary'         => 'ไฟล์เปลี่ยนแปลง:',
'filestatus'                  => 'สถานะลิขสิทธิ์:',
'filesource'                  => 'แหล่งที่มา:',
'uploadedfiles'               => 'ไฟล์ที่อัปโหลดแล้ว',
'ignorewarning'               => 'ทำการบันทึกไฟล์โดยไม่สนคำเตือน',
'ignorewarnings'              => 'ไม่แสดงคำเตือน',
'minlength1'                  => 'ชื่อไฟล์ต้องมีตัวอักษรอย่างน้อยหนึ่งตัวอักษร',
'illegalfilename'             => 'ชื่อไฟล์  "$1" มีตัวอักษรที่ไม่สามารถนำมาใช้ได้ กรุณาเปลี่ยนชื่อไฟล์และอัปโหลดอีกครั้งหนึ่ง',
'badfilename'                 => 'ชื่อไฟล์ถูกเปลี่ยนเป็น "$1"',
'filetype-badmime'            => 'ไม่อนุญาตให้อัปโหลดไฟล์ที่เป็นไมม์ชนิด "$1"',
'filetype-bad-ie-mime'        => 'ไม่สามารถอัปโหลดไฟล์นี้เนื่องจาก Internet Explorer จะตรวจจับว่าเป็น "$1" ซึ่งเป็นชนิดไฟล์ที่ไม่อนุญาตและอาจเป็นอันตราย',
'filetype-unwanted-type'      => "{{PLURAL:\$3|ไฟล์|ไฟล์}}ชนิด '''\".\$1\"''' เป็นไฟล์ที่ไม่สามารถอัปโหลดได้ ไฟล์ที่สามารถใช้ได้ ได้แก่ \$2",
'filetype-banned-type'        => "ไม่อนุญาตให้ใช้ไฟล์ชนิด '''\".\$1\"''' {{PLURAL:\$3|ชนิดของไฟล์|ชนิดของไฟล์}}ที่อนุญาตให้ใช้ได้คือ \$2",
'filetype-missing'            => 'นามสกุลไฟล์หายไป (เช่น ".jpg")',
'large-file'                  => 'ไฟล์ไม่ควรมีขนาดใหญ่กว่า $1 ไฟล์นี้มีขนาด $2',
'largefileserver'             => 'ไฟล์นี้มีขนาดใหญ่กว่าค่าที่อนุญาตให้ใช้ได้',
'emptyfile'                   => 'ไฟล์ที่อัปโหลดมาเหมือนไฟล์ว่าง อาจเกิดจากปัญหาพิมพ์ชื่อไฟล์ผิด กรุณาตรวจสอบไฟล์อีกครั้ง และแน่ใจว่าต้องการที่จะอัปโหลดไฟล์นี้',
'fileexists'                  => "มีไฟล์ชื่อนี้อยู่แล้ว กรุณาตรวจสอบ '''<tt>[[:$1]]</tt>''' หากคุณไม่แน่ใจว่าต้องการเปลี่ยนแปลงไฟล์นี้หรือไม่ [[$1|thumb]]",
'filepageexists'              => "หน้าคำอธิบายสำหรับไฟล์นี้ได้ถูกสร้างไว้แล้วที่ '''<tt>[[:$1]]</tt>''' แต่ไฟล์ชื่อนี้ไม่มีอยู่ในปัจจุบัน
สาระสำคัญที่คุณบันทึกจะไม่ปรากฏบนหน้าคำอธิบาย
เพื่อให้สาระสำคัญปรากฏขึ้น คุณจำเป็นต้องแก้ไขด้วยตนเอง
[[$1|thumb]]",
'fileexists-extension'        => "ไฟล์ที่โหลดมีชื่อใกล้เคียง: [[$2|thumb]]
* ชื่อไฟล์ที่กำลังอัปโหลด: '''<tt>[[:$1]]</tt>'''
* ชื่อไฟล์ที่มีอยู่แล้ว: '''<tt>[[:$2]]</tt>'''
กรุณาเลือกชื่อไฟล์ใหม่",
'fileexists-thumbnail-yes'    => "ไฟล์นี้ดูเหมือนจะเป็นภาพที่ถูกลดขนาดมา ''(รูปย่อ)''
[[$1|thumb]]
กรุณาตรวจสอบไฟล์ '''<tt>[[:$1]]</tt>'''
ถ้าตรวจสอบแล้วและพบว่าเป็นภาพเดียวกันกับภาพต้นฉบับ ไฟล์นั้นไม่จำเป็นต้องอัปโหลดเพิ่ม",
'file-thumbnail-no'           => "ชื่อไฟล์ขึ้นต้นด้วย '''<tt>$1</tt>''' 
ภาพนี้ดูเหมือนว่าจะเป็นภาพที่ถูกลดขนาดมา ''(thumbnail)'' 
ถ้าคุณมีไฟล์ต้นฉบับขนาดใหญ่กว่านี้ กรุณาอัปโหลดไฟล์ต้นฉบับ หรือเปลี่ยนชื่อไฟล์ด้วย",
'fileexists-forbidden'        => 'ไฟล์ชื่อนี้มีอยู่แล้วในระบบ และไม่สามารถอัปโหลดทับได้
หากคุณยังคงต้องการอัปโหลดไฟล์ของคุณ กรุณาย้อนกลับและใช้ชื่อใหม่ [[ไฟล์:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'ไฟล์ที่ใช้ชื่อนี้มีอยู่แล้วในระบบเก็บไฟล์ในส่วนกลาง
ถ้าคุณยังคงต้องการอัปโหลดไฟล์ของคุณ กรุณาย้อนกลับไปตั้งชื่อใหม่
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ไฟล์นี้ซ้ำกับ{{PLURAL:$1|ไฟล์|ไฟล์}}ต่อไปนี้:',
'file-deleted-duplicate'      => 'ไฟล์ที่ตรงกับชื่อนี้ ([[$1]]) เคยถูกลบไปก่อนหน้า
คุณควรตรวจสอบว่าประวัติการลบของไฟล์ก่อนดำเนินการอัปโหลดใหม่',
'upload-success-subj'            => 'อัปโหลดสำเร็จ',
'uploadwarning'               => 'คำเตือนการอัปโหลด',
'uploadwarning-text'          => 'กรุณาแก้ไขคำอธิบายไฟล์ด้านล่างนี้ แล้วลองใหม่อีกครั้ง',
'savefile'                    => 'บันทึกไฟล์',
'uploadedimage'               => '"[[$1]]" ถูกอัปโหลด',
'overwroteimage'              => 'อัปโหลดรุ่นใหม่ของ "[[$1]]"',
'uploaddisabled'              => 'อัปโหลดปิดการใช้งาน',
'uploaddisabledtext'          => 'การอัปโหลดไฟล์ถูกปิดการใช้งาน',
'php-uploaddisabledtext'      => 'การอัปโหลดไฟล์ถูกปิดการใช้งานใน PHP
กรุณาตรวจสอบการตั้งค่า file_uploads',
'uploadscripted'              => 'ไฟล์นี้มีส่วนประกอบของโค้ดเอชทีเอ็มแอลหรือสคริปต์ ซึ่งอาจก่อให้เกิดความผิดพลาดในการแสดงผลของเว็บเบราว์เซอร์',
'uploadvirus'                 => 'ไฟล์นี้มีไวรัส! รายละเอียด: $1',
'upload-source'               => 'ไฟล์ต้นทาง',
'sourcefilename'              => 'ไฟล์ที่ต้องการ:',
'sourceurl'                   => 'URL ที่มา:',
'destfilename'                => 'ชื่อไฟล์ที่ต้องการ:',
'upload-maxfilesize'          => 'ขนาดไฟล์ที่ใหญ่ที่สุดที่อนุญาต: $1',
'upload-description'          => 'คำอธิบายไฟล์',
'upload-options'              => 'ตัวเลือกอัปโหลด',
'watchthisupload'             => 'เฝ้าดูไฟล์นี้',
'filewasdeleted'              => 'ไฟล์ในชื่อนี้ได้ถูกอัปโหลดก่อนหน้าและถูกลบไปแล้ว กรุณาตรวจสอบ $1 ก่อนที่จะอัปโหลดใหม่อีกครั้ง',
'upload-wasdeleted'           => "'''คำเตือน: คุณกำลังจะอัปโหลดไฟล์ที่เคยถูกลบไปแล้ว'''

โปรดพิจารณาความเหมาะสมว่าจะยังอัปโหลดไฟล์นี้ต่อหรือไม่
นี่คือปูมการลบของไฟล์เพื่อประกอบการตัดสินใจ:",
'filename-bad-prefix'         => "ไฟล์ที่คุณกำลังจะอัปโหลดเข้ามานี้มีชื่อที่ขึ้นต้นด้วย '''\"\$1\"''' ซึ่งเป็นชื่อที่ไม่สื่อความหมายใดๆ (โดยปกติแล้วชื่อนี้จะถูกตั้งมาโดยกล้องถ่ายรูปดิจิทัล).  กรุณาตั้งชื่อไฟล์ใหม่ที่สื่อความหมายมากกว่าเดิม",

'upload-proto-error'        => 'โพรโทคอลไม่ถูกต้อง',
'upload-proto-error-text'   => 'การอัปโหลดโดยตรงจากเว็บต้องการยูอาร์แอลที่ขึ้นต้นด้วย <code>http://</code> หรือ <code>ftp://</code>',
'upload-file-error'         => 'เกิดความผิดพลาดภายใน',
'upload-file-error-text'    => 'เกิดความผิดพลาดภายใน จากปัญหาการสร้างไฟล์ชั่วคราวที่เซิร์ฟเวอร์ กรุณาติดต่อ[[Special:ListUsers/sysop|ผู้ดูแลระบบ]]',
'upload-misc-error'         => 'เกิดปัญหาอัปโหลด',
'upload-misc-error-text'    => 'เกิดปัญหาระหว่างการอัปโหลด กรุณาตรวจสอบว่ายูอาร์แอลนั้นถูกต้อง ถ้ายังคงมีปัญหาให้ติดต่อผู้ดูแลระบบ',
'upload-too-many-redirects' => 'URL ที่ระบุมีการเปลี่ยนทางมากเกินไป',
'upload-unknown-size'       => 'ไม่ทราบขนาด',
'upload-http-error'         => 'เกิดข้อผิดพลาด HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'การเข้าถึงถูกจำกัด',
'img-auth-nopathinfo'   => 'ค่า PATH_INFO สูญหาย
เซิร์ฟเวอร์ของคุณอาจไม่ได้ถูกตั้งให้ส่งข้อมูลนี้
หรือเซิร์ฟเวอร์อาจจะเป็นแบบ CGI-based และไม่สนับสนุนข้อมูล img_auth
ดูที่ http://www.mediawiki.org/wiki/Manual:Image_Authorization',
'img-auth-notindir'     => 'ที่อยู่ที่ร้องขอไม่ได้อยู่ในไดเร็กทอรีอัพโหลดที่กำหนดไ้ว้',
'img-auth-badtitle'     => 'ไม่สามารถสร้างชื่อเรื่องที่ถูกต้องจาก "$1" ได้',
'img-auth-nologinnWL'   => 'คุณไม่ได้ลงชื่อเข้าใช้และ "$1" ไม่ได้อยู่ในรายชื่อผู้ใช้ที่ดี (whitelist)',
'img-auth-nofile'       => 'ไม่มีไฟล์ "$1"',
'img-auth-isdir'        => 'คุณกำลังพยายามเข้าถึงไดเร็กทอรี "$1"
ซึ่งคุณสามารถเข้าถึงได้เฉพาะไฟล์เท่านั้น',
'img-auth-streaming'    => 'กำลังดึงข้อมูล "$1"',
'img-auth-public'       => 'ฟังก็ชันของ img_auth.php คือเพื่อส่งไฟล์ขาออกจากวิกิส่วนตัว
วิกินี้ถูกกำหนดเป็นวิกิส่วนตัว
เพื่อความปลอดภัยสูงสุด img_auth.php จึงถูกปิด',
'img-auth-noread'       => 'ผู้ใช้ไม่ได้รับสิทธิ์ในการอ่าน "$1"',

# HTTP errors
'http-host-unreachable' => 'ไม่สามารถเข้าถึง URL',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'ไม่สามารถติดต่อยูอาร์แอลได้',
'upload-curl-error6-text'  => 'ยูอาร์แอลที่ใส่ค่ามาไม่สามารถติดต่อได กรุณาตรวจสอบอีกครั้งว่ายูอาร์แอลนั้นถูกต้อง และเว็บไซต์นั้นยังใช้งานได้ตามปกติ',
'upload-curl-error28'      => 'เวลาอัปโหลดถูกตัด',
'upload-curl-error28-text' => 'เว็บไซต์นี้ใช้เวลานานเกินไปในการเชื่อมต่อ กรุณาตรวจสอบว่าเว็บนี้ยังใช้งานได้ตามปกติ หรืออาจจะรอสักครู่แล้วลองอัปโหลดใหม่',

'license'            => 'ลิขสิทธิ์:',
'license-header'     => 'การอนุญาตโดยเจ้าของลิขสิทธิ์',
'nolicense'          => 'ไม่ได้เลือก',
'license-nopreview'  => '(ไม่สามารถแสดงตัวอย่าง)',
'upload_source_url'  => ' (ยูอาร์แอลที่บุคคลทั่วไปสามารถเข้าถึงได้)',
'upload_source_file' => ' (ไฟล์จากคอมพิวเตอร์คุณ)',

# Special:ListFiles
'listfiles-summary'     => 'หน้าพิเศษนี้แสดงไฟล์ทั้งหมดที่ถูกอัปโหลด
โดยปริยาย ไฟล์ที่ถูกอัปโหลดล่าสุด จะแสดงอยู่บนสุดของรายการไฟล์
คลิกที่คอมลัมน์บนสุดจะเปลี่ยนการจัดแยกประเภท',
'listfiles_search_for'  => 'ค้นหาชื่อภาพ:',
'imgfile'               => 'ไฟล์',
'listfiles'             => 'รายชื่อไฟล์',
'listfiles_date'        => 'วันที่',
'listfiles_name'        => 'ชื่อ',
'listfiles_user'        => 'ผู้ใช้',
'listfiles_size'        => 'ขนาด',
'listfiles_description' => 'คำอธิบาย',
'listfiles_count'       => 'รุ่น',

# File description page
'file-anchor-link'          => 'ไฟล์',
'filehist'                  => 'ประวัติไฟล์',
'filehist-help'             => 'กดเลือก วัน/เวลา เพื่อดูไฟล์ที่แสดงในวันนั้น',
'filehist-deleteall'        => 'ลบทั้งหมด',
'filehist-deleteone'        => 'ลบ',
'filehist-revert'           => 'ย้อน',
'filehist-current'          => 'ปัจจุบัน',
'filehist-datetime'         => 'วันที่/เวลา',
'filehist-thumb'            => 'รูปย่อ',
'filehist-thumbtext'        => 'รูปย่อสำหรับรุ่น $1',
'filehist-nothumb'          => 'ไม่มีรูปย่อ',
'filehist-user'             => 'ผู้ใช้',
'filehist-dimensions'       => 'ขนาด',
'filehist-filesize'         => 'ขนาดไฟล์',
'filehist-comment'          => 'ความเห็น',
'filehist-missing'          => 'ไฟล์หายไป',
'imagelinks'                => 'หน้าที่มีไฟล์นี้',
'linkstoimage'              => '{{PLURAL:$1|หน้า|หน้า}}ที่ลิงก์มายังไฟล์นี้:',
'linkstoimage-more'         => 'ไฟล์นี้มีการเชื่อมโยงมากกว่า $1 {{PLURAL:$1|แห่ง|แห่ง}}
รายชื่อต่อไปนี้แสดงการเชื่อมโยง $1 {{PLURAL:$1|แห่งแรก|แห่งแรก}}ที่มายังไฟล์นี้เท่านั้น
ดูเพิ่มได้ที่[[Special:WhatLinksHere/$2|รายชื่อเต็ม]]',
'nolinkstoimage'            => 'ไม่มีหน้าที่ใช้ภาพนี้',
'morelinkstoimage'          => 'ดู[[Special:WhatLinksHere/$1|หน้าที่ลิงก์]]มายังไฟล์นี้เพิ่มเติม',
'redirectstofile'           => '{{PLURAL:$1|ไฟล์|$1 ไฟล์}}ดังต่อไปนี้เปลี่ยนทางมาที่ไฟล์นี้:',
'duplicatesoffile'          => '{{PLURAL:$1|ไฟล์|$1 ไฟล์}}ต่อไปนี้ เป็นไฟล์เดียวกับไฟล์นี้ ([[Special:FileDuplicateSearch/$2|รายละเอียดเพิ่ม]]):',
'sharedupload'              => 'ไฟล์นี้มาจาก $1 และอาจมีการใช้ในโครงการอื่น',
'sharedupload-desc-there'   => 'ไฟล์นี้มาจาก $1 และอาจถูกใช้บนโครงการอื่น ๆ
กรุณาดู [หน้าคำอธิบายของไฟล์ $2] สำหรับข้อมูลเพิ่มเติม',
'sharedupload-desc-here'    => 'ไฟล์นี้มาจาก $1 และอาจมีใช้ในโครงการอื่น
คำอธิบายใน[$2 หน้าไฟล์]ได้แสดงไว้ข้างล่างนี้',
'filepage-nofile'           => 'ไม่มีไฟล์ชื่อนี้',
'filepage-nofile-link'      => 'ไม่มีไฟล์ชื่อนี้ อย่างไรก็ตามคุณสามารถ[$1 อัปโหลด]ได้',
'uploadnewversion-linktext' => 'อัปโหลดรุ่นใหม่ของไฟล์นี้',
'shared-repo-from'          => 'จาก $1',
'shared-repo'               => 'คลังที่ใช้ร่วมกัน',

# File reversion
'filerevert'                => 'ย้อน $1',
'filerevert-legend'         => 'ย้อนไฟล์กลับ',
'filerevert-intro'          => '<span class="plainlinks">คุณกำลังย้อนไฟล์ \'\'\'[[Media:$1|$1]]\'\'\' ไปยัง [รุ่น $4 วันที่ $2, $3]</span>',
'filerevert-comment'        => 'ความเห็น:',
'filerevert-defaultcomment' => 'ย้อนไปรุ่น $1, $2',
'filerevert-submit'         => 'ย้อน',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' ถูกย้อนไปยัง [รุ่น $4 วันที่ $2, $3]</span>',
'filerevert-badversion'     => 'ไม่มีรุ่นก่อนหน้าของไฟล์นี้ในเวลาที่กำหนดไว้',

# File deletion
'filedelete'                  => 'ลบ $1',
'filedelete-legend'           => 'ลบไฟล์',
'filedelete-intro'            => "คุณกำลังจะลบไฟล์ '''[[Media:$1|$1]]''' ไปพร้อมกับประวัติของไฟล์นี้",
'filedelete-intro-old'        => "คุณกำลังลบ '''[[Media:$1|$1]]''' รุ่น [$4 $3, $2]",
'filedelete-comment'          => 'เหตุผล:',
'filedelete-submit'           => 'ลบ',
'filedelete-success'          => "ลบไฟล์ '''$1''' เรียบร้อยแล้ว",
'filedelete-success-old'      => "ไฟล์ '''[[Media:$1|$1]]''' รุ่นเมื่อ $3, $2 ถูกลบเรียบร้อยแล้ว",
'filedelete-nofile'           => "ไม่มีไฟล์ '''$1'''",
'filedelete-nofile-old'       => "ไม่มี '''$1''' ตามคุณลักษณะที่กำหนด อยู่ในกรุ",
'filedelete-otherreason'      => 'เหตุผลอื่นเพิ่มเติม:',
'filedelete-reason-otherlist' => 'เหตุผลอื่น',
'filedelete-reason-dropdown'  => '* เหตุผลทั่วไปของการลบ
** ละเมิดลิขสิทธิ์
** ไฟล์ซ้ำ',
'filedelete-edit-reasonlist'  => 'แก้ไขรายชื่อเหตุผลในการลบ',
'filedelete-maintenance'      => 'ปิดการลบและเรียกคืนไฟล์ไว้ชั่วคราว ในระหว่างการซ่อมบำรุง',

# MIME search
'mimesearch'         => 'ค้นหาตามชนิดไมม์',
'mimesearch-summary' => 'หน้านี้แสดงไฟล์ตามการแบ่งของชนิดไมม์ (MIME) ของแต่ละไฟล์ ใส่ค่า: contenttype/subtype เช่น <tt>image/jpeg</tt>.',
'mimetype'           => 'ชนิดไมม์:',
'download'           => 'ดาวน์โหลด',

# Unwatched pages
'unwatchedpages' => 'หน้าที่ไม่มีการเฝ้าดู',

# List redirects
'listredirects' => 'รายการหน้าเปลี่ยนทาง',

# Unused templates
'unusedtemplates'     => 'แม่แบบไม่ได้ใช้',
'unusedtemplatestext' => 'หน้านี้แสดงรายการบทความทั้งหมดในเนมสเปซ {{ns:template}} ซึ่งไม่ได้ถูกรวมอยู่ในหน้าอื่น ก่อนที่จะลบส่วนนี้ให้ทำการตรวจสอบหน้าที่ลิงก์มาก่อนทุกครั้ง',
'unusedtemplateswlh'  => 'ลิงก์มา',

# Random page
'randompage'         => 'สุ่มหน้า',
'randompage-nopages' => 'ไม่มีหน้าใดๆ ใน{{PLURAL:$2|เนมสเปซ}}ต่อไปนี้: "$1"',

# Random redirect
'randomredirect'         => 'สุ่มหน้าเปลี่ยนทาง',
'randomredirect-nopages' => 'ไม่มีหน้าเปลี่ยนทางในเนมสเปซ "$1"',

# Statistics
'statistics'                   => 'สถิติ',
'statistics-header-pages'      => 'สถิติของหน้าต่าง ๆ',
'statistics-header-edits'      => 'สถิติการแก้ไข',
'statistics-header-views'      => 'สถิติการเข้าชม',
'statistics-header-users'      => 'สถิติผู้ใช้',
'statistics-header-hooks'      => 'สถิติอื่นๆ',
'statistics-articles'          => 'จำนวนเนื้อหา',
'statistics-pages'             => 'หน้าทั้งหมด',
'statistics-pages-desc'        => 'หน้าทั้งหมดในเว็บไซต์นี้ รวมไปถึงหน้าต่าง ๆ เช่น หน้าสนทนา และหน้าเปลี่ยนทาง เป็นต้น',
'statistics-files'             => 'จำนวนไฟล์ที่ถูกอัปโหลด',
'statistics-edits'             => 'แก้ไขทั้งหมดตั้งแต่{{SITENAME}}ภาษาไทยถูกก่อตั้งขึ้นมา',
'statistics-edits-average'     => 'จำนวนแก้ไขต่อหน้าโดยเฉลี่ย',
'statistics-views-total'       => 'จำนวนการเข้าชมทั้งหมด',
'statistics-views-peredit'     => 'จำนวนการเข้าดูต่อการแก้ไข:',
'statistics-users'             => '[[Special:ListUsers|ผู้ใช้]]ที่ลงทะเบียน',
'statistics-users-active'      => 'ผู้ใช้ที่ยังแก้ไขอยู่',
'statistics-users-active-desc' => 'ผู้ใช้ที่ได้แก้ไขในช่วง $1 วันที่ผ่านมา',
'statistics-mostpopular'       => 'หน้าที่มีการเข้าชมมากที่สุด',

'disambiguations'      => 'หน้าแก้ความกำกวม',
'disambiguationspage'  => 'Template:แก้กำกวม',
'disambiguations-text' => "หน้าต่อไปนี้เชื่อมโยงไปยัง '''หน้าคำกำกวม''' ซึ่งเนื้อหาในหน้าเหล่านั้นควรถูกเชื่อมโยงไปยังหัวข้อที่เหมาะสมแทนที่<br />

หน้าใดที่เรียกใช้ [[MediaWiki:Disambiguationspage]] หน้าเหล่านั้นจะถูกนับเป็นหน้าคำกำกวม",

'doubleredirects'            => 'หน้าเปลี่ยนทางซ้ำซ้อน',
'doubleredirectstext'        => 'หน้านี้แสดงรายการชื่อที่เปลี่ยนทางไปยังหน้าเปลี่ยนทางอื่น
แต่ละแถวคือลิงก์ของการเปลี่ยนทางครั้งแรกและครั้งที่สอง พร้อมกับหน้าปลายทางของการเปลี่ยนทางครั้งที่สอง ซึ่งควรแก้ไขการเปลี่ยนทางครั้งแรกเป็นหน้าปลายทางดังกล่าว
รายการที่ <del>ขีดฆ่า</del> คือรายการที่แก้ไขแล้ว',
'double-redirect-fixed-move' => '[[$1]] ถูกเปลี่ยนชื่อแล้ว และเปลี่ยนทางไปยัง [[$2]]',
'double-redirect-fixer'      => 'Redirect fixer',

'brokenredirects'        => 'หน้าเปลี่ยนทางเสีย',
'brokenredirectstext'    => 'หน้าเปลี่ยนทางต่อไปนี้เชื่อมโยงไปยังหน้าที่ไม่มี:',
'brokenredirects-edit'   => 'แก้ไข',
'brokenredirects-delete' => 'ลบ',

'withoutinterwiki'         => 'หน้าที่ไม่มีลิงก์ข้ามภาษา',
'withoutinterwiki-summary' => 'หน้าต่อไปนี้ไม่มีลิงก์ข้ามไปภาษาอื่น',
'withoutinterwiki-legend'  => 'คำนำหน้า',
'withoutinterwiki-submit'  => 'แสดง',

'fewestrevisions' => 'หน้าที่มีการแก้ไขน้อย',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ไบต์|ไบต์}}',
'ncategories'             => '$1 หมวดหมู่',
'nlinks'                  => '$1 {{PLURAL:$1|ลิงก์|ลิงก์}}',
'nmembers'                => '$1 {{PLURAL:$1|หน้า|หน้า}}',
'nrevisions'              => '$1 ครั้ง',
'nviews'                  => '$1 ครั้ง',
'specialpage-empty'       => 'ไม่มีหน้าที่เรียกดู',
'lonelypages'             => 'หน้าสุดทาง',
'lonelypagestext'         => 'หน้าต่อไปนี้ไม่มีการเชื่อมโยงหรือถูกรวมไว้ในหน้าอื่นใน {{SITENAME}}',
'uncategorizedpages'      => 'หน้าที่ไม่ได้จัดหมวดหมู่',
'uncategorizedcategories' => 'หมวดหมู่ที่ไม่ได้จัดหมวดหมู่',
'uncategorizedimages'     => 'ภาพที่ไม่ได้จัดหมวดหมู่',
'uncategorizedtemplates'  => 'แม่แบบที่ไม่ได้จัดหมวดหมู่',
'unusedcategories'        => 'หมวดหมู่ที่ไม่ได้ใช้',
'unusedimages'            => 'ไฟล์ไม่ได้ใช้',
'popularpages'            => 'หน้าที่มีการเข้าดูมาก',
'wantedcategories'        => 'หมวดหมู่ที่ต้องการ',
'wantedpages'             => 'หน้าที่ต้องการ',
'wantedpages-badtitle'    => 'ชื่อเรื่อง $1 ไม่ถูกต้องในรายการผลลัพธ์',
'wantedfiles'             => 'ไฟล์ที่ต้องการ',
'wantedtemplates'         => 'แม่แบบที่ต้องการ',
'mostlinked'              => 'หน้าที่มีการลิงก์หามาก',
'mostlinkedcategories'    => 'หมวดหมู่ที่มีการโยงหามาก',
'mostlinkedtemplates'     => 'แม่แบบที่ใช้มาก',
'mostcategories'          => 'หน้าที่มีหมวดหมู่มาก',
'mostimages'              => 'ภาพที่ใช้มาก',
'mostrevisions'           => 'หน้าที่มีการแก้ไขมาก',
'prefixindex'             => 'หน้าทั้งหมดตามดัชนีคำนำหน้า',
'shortpages'              => 'หน้าสั้นมาก',
'longpages'               => 'หน้ายาวมาก',
'deadendpages'            => 'หน้าสุดทาง',
'deadendpagestext'        => 'หน้าต่อไปนี้ไม่ได้ลิงก์ไปหน้าหน้าใดในวิกิ',
'protectedpages'          => 'หน้าถูกล็อก',
'protectedpages-indef'    => 'การล็อกแบบไม่จำกัดเท่านั้น',
'protectedpages-cascade'  => 'การล็อกแบบสืบทอดเท่านั้น',
'protectedpagestext'      => 'หน้าต่อไปนี้ถูกล็อกห้ามแก้ไขหรือห้ามเปลี่ยนชื่อ',
'protectedpagesempty'     => 'ไม่มีหน้าใดที่ถูกล็อกตามค่าที่เลือก',
'protectedtitles'         => 'หัวเรื่องที่ได้รับการป้องกัน',
'protectedtitlestext'     => 'หัวเรื่องต่อไปนี้ได้รับการป้องกันไม่ให้สร้างใหม่',
'protectedtitlesempty'    => 'ปัจจุบันไม่มีหัวเรื่องที่ได้รับการป้องกันด้วยค่าต่อไปนี้',
'listusers'               => 'รายนามผู้ใช้',
'listusers-editsonly'     => 'แสดงเฉพาะผู้ใช้ที่ร่วมแก้ไข',
'listusers-creationsort'  => 'เรียงลำดับตามวันสร้าง',
'usereditcount'           => 'การแก้ไข $1 {{PLURAL:$1|ครั้ง|ครั้ง}}',
'usercreated'             => 'ถูกสร้างเมื่อ $1 เวลา $2',
'newpages'                => 'หน้าใหม่',
'newpages-username'       => 'ชื่อผู้ใช้:',
'ancientpages'            => 'หน้าที่ไม่ได้แก้ไขนานสุด',
'move'                    => 'เปลี่ยนชื่อ',
'movethispage'            => 'เปลี่ยนชื่อหน้านี้',
'unusedimagestext'        => 'ไฟล์ดังต่อไปนี้ปรากฎแต่ไม่มีการเรียกใช้ที่หน้าใดๆ เลย
ภาพนี้อาจจะถูกใช้จากเว็บไซต์อื่น ซึ่งลิงก์มาภาพในหน้านี้โดยตรง ดังนั้นไฟล์ดังกล่าวจะยังปรากฎในรายการนี้แม้ว่าจะมีการใช้อย่างต่อเนื่อง',
'unusedcategoriestext'    => 'หมวดหมู่ต่อไปนี้ยังมีอยู่ถึงแม้ว่าจะไม่มีว่าไม่มีหน้าไหนหรือบทความไหนใช้ส่วนนี้',
'notargettitle'           => 'ไม่พบหน้าปลายทาง',
'notargettext'            => 'ไม่ได้ใส่หน้าปลายทางหรือชื่อผู้ใช้ที่ต้องการใช้คำสั่งนี้',
'nopagetitle'             => 'ไม่มีหน้าเป้าหมายดังกล่าว',
'nopagetext'              => 'หน้าเป้าหมายที่คุณระบุไม่มีอยู่',
'pager-newer-n'           => '{{PLURAL:$1|ใหม่กว่า 1|ใหม่กว่า $1}}',
'pager-older-n'           => '{{PLURAL:$1|เก่ากว่า 1|เก่ากว่า $1}}',
'suppress'                => 'ความผิดพลาดที่ไม่ทันสังเกต',

# Book sources
'booksources'               => 'ค้นหาหนังสือ',
'booksources-search-legend' => 'ค้นหาหนังสือ',
'booksources-go'            => 'ค้นหา',
'booksources-text'          => 'รายการด้านล่างแสดงเว็บไซต์ที่ขายหนังสือใหม่หรือหนังสือใช้แล้ว ซึ่งอาจมีข้อมูลของหนังสือที่คุณกำลังค้นหา:',
'booksources-invalid-isbn'  => 'รหัส ISBN ที่ให้ไว้ไม่ถูกต้อง กรุณาตรวจสอบจากต้นฉบับอีกครั้ง',

# Special:Log
'specialloguserlabel'  => 'ผู้ใช้:',
'speciallogtitlelabel' => 'ชื่อเรื่อง:',
'log'                  => 'บันทึก',
'all-logs-page'        => 'บันทึกสาธารณะทั้งหมด',
'alllogstext'          => 'แสดงปูมทั้งหมดของ{{SITENAME}} 
คุณสามารถค้นหาให้ละเอียดมากขึ้นโดยเลือกประเภทของปูม ชื่อผู้ใช้ (ตัวเล็กใหญ่ในภาษาอังกฤษมีค่าไม่เท่ากัน) หรือหน้าที่ต้องการ',
'logempty'             => 'ไม่มีในบันทึกก่อนหน้า',
'log-title-wildcard'   => 'ค้นหาชื่อเรื่องด้วยคำขึ้นต้น',

# Special:AllPages
'allpages'          => 'หน้าทุกหน้า',
'alphaindexline'    => '$1 ไป $2',
'nextpage'          => 'ถัดไป ($1)',
'prevpage'          => 'ก่อนหน้า ($1)',
'allpagesfrom'      => 'เริ่มแสดงผลจาก:',
'allpagesto'        => 'จบการแสดงผลที่:',
'allarticles'       => 'หน้าทุกหน้า',
'allinnamespace'    => 'หน้าทุกหน้า ($1 เนมสเปซ)',
'allnotinnamespace' => 'หน้าทุกหน้า (ไม่อยู่ใน $1 เนมสเปซ)',
'allpagesprev'      => 'ก่อนหน้า',
'allpagesnext'      => 'ถัดไป',
'allpagessubmit'    => 'ค้นหา',
'allpagesprefix'    => 'แสดงหน้าที่ขึ้นต้นด้วย:',
'allpagesbadtitle'  => 'ชื่อเรื่องนี้ไม่ถูกต้อง อาจจะสะกดผิด ลิงก์มาจากภาษาอื่นที่ไม่ถูกต้อง หรือมีตัวอักษรที่ไม่สามารถใช้เป็นชื่อเรื่องได้',
'allpages-bad-ns'   => '{{SITENAME}} ไม่มีเนมสเปซในชื่อ "$1"',

# Special:Categories
'categories'                    => 'หมวดหมู่',
'categoriespagetext'            => '{{PLURAL:$1|หมวดหมู่ต่อไปนี้}}มีหน้าหรือสื่อต่างๆ
[[Special:UnusedCategories|หมวดหมู่ที่ไม่ได้ใช้]]จะไม่แสดงในที่นี้
ดูเพิ่มที่[[Special:WantedCategories|หมวดหมู่ที่ต้องการ]]',
'categoriesfrom'                => 'แสดงหมวดหมู่โดยเริ่มจาก:',
'special-categories-sort-count' => 'เรียงตามจำนวน',
'special-categories-sort-abc'   => 'เรียงลำดับตามตัวอักษร',

# Special:DeletedContributions
'deletedcontributions'             => 'การแก้ไขที่ถูกลบ',
'deletedcontributions-title'       => 'การแก้ไขที่ถูกลบ',
'sp-deletedcontributions-contribs' => 'เรื่องที่เขียน',

# Special:LinkSearch
'linksearch'       => 'แหล่งข้อมูลอื่น',
'linksearch-pat'   => 'รูปแบบการค้นหา:',
'linksearch-ns'    => 'เนมสเปซ:',
'linksearch-ok'    => 'สืบค้น',
'linksearch-text'  => 'สามารถใช้เครื่องหมายแทนอักขระใดๆ (wildcard) ได้ เช่น "*.wikipedia.org"<br />
โปรโตคอลที่รองรับ: <tt>$1</tt>',
'linksearch-line'  => '$1 ถูกลิงก์จาก $2',
'linksearch-error' => 'เครื่องหมายแทนอักขระใดๆ (wildcard) สามารถจะอยู่ด้านหน้าของชื่อโฮสต์เท่านั้น',

# Special:ListUsers
'listusersfrom'      => 'แสดงชื่อผู้ใช้โดยเริ่มต้นจาก:',
'listusers-submit'   => 'แสดง',
'listusers-noresult' => 'ไม่พบชื่อผู้ใช้ที่ต้องการ',
'listusers-blocked'  => '(ถูกระงับ)',

# Special:ActiveUsers
'activeusers'            => 'รายการผู้ใช้ประจำ',
'activeusers-intro'      => 'นี่คือรายการผู้ใช้ที่มีกิจกรรมใดๆ ในรอบ $1 {{PLURAL:$1|วัน|วัน}}ที่ผ่านมา',
'activeusers-count'      => '{{PLURAL:$1|การแก้ไขล่าสุด|การแก้ไขล่าสุด $1 รายการ}} ใน{{PLURAL:$3|ช่วงวัน|ช่วง $3 วัน}}ที่ผ่านมา',
'activeusers-from'       => 'แสดงชื่อผู้ใช้โดยเริ่มจาก:',
'activeusers-hidebots'   => 'ซ่อนบ็อต',
'activeusers-hidesysops' => 'ซ่อนผู้ดูแลระบบ',
'activeusers-noresult'   => 'ไม่พบชื่อผู้ใช้',

# Special:Log/newusers
'newuserlogpage'              => 'ปูมการสร้างบัญชีผู้ใช้ใหม่',
'newuserlogpagetext'          => 'นี่คือบันทึกการสร้างบัญชีผู้ใช้',
'newuserlog-byemail'          => 'รหัสผ่านถูกส่งทางอีเมล',
'newuserlog-create-entry'     => 'ผู้ใช้ใหม่',
'newuserlog-create2-entry'    => 'ได้สร้างบัญชีผู้ใช้ใหม่ชื่อ $1',
'newuserlog-autocreate-entry' => 'ชื่อบัญชีถูกสร้างอัตโนมัติ',

# Special:ListGroupRights
'listgrouprights'                      => 'สิทธิของกลุ่มผู้ใช้',
'listgrouprights-summary'              => 'รายชื่อกลุ่มผู้ใช้ต่อไปนี้ถูกกำหนดไว้บน {{SITENAME}} โดยมีสิทธิการเข้าถึงที่เกี่ยวข้อง และอาจมี[[{{MediaWiki:Listgrouprights-helppage}}|ข้อมูลเพิ่มเติม]]เกี่ยวกับสิทธิของแต่ละบุคคล',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">สิทธิ์ที่ถูกให้</span>
* <span class="listgrouprights-revoked">สิทธิ์ที่ถูกยกเลิก</span>',
'listgrouprights-group'                => 'กลุ่ม',
'listgrouprights-rights'               => 'สิทธิ',
'listgrouprights-helppage'             => 'Help:สิทธิของกลุ่ม',
'listgrouprights-members'              => '(รายชื่อสมาชิก)',
'listgrouprights-addgroup'             => 'สามารถเพิ่ม{{PLURAL:$2|กลุ่มนี้|กลุ่มเหล่านี้}}ได้: $1',
'listgrouprights-removegroup'          => 'สามารถลบ{{PLURAL:$2|กลุ่มนี้|กลุ่มเหล่านี้}}ได้: $1',
'listgrouprights-addgroup-all'         => 'สามารถเพิ่มกลุ่มทั้งหมดได้',
'listgrouprights-removegroup-all'      => 'สามารถลบกลุ่มทั้งหมดได้',
'listgrouprights-addgroup-self'        => 'เพิ่ม {{PLURAL:$2|กลุ่ม|กลุ่ม}} เข้าไปในชื่อผู้ใช้: $1',
'listgrouprights-removegroup-self'     => 'ลบ {{PLURAL:$2|กลุ่ม|กลุ่ม}} ออกจากชื่อผู้ใช้: $1',
'listgrouprights-addgroup-self-all'    => 'เพิ่มทุกกลุ่มเข้าไปในชื่อผู้ใช้นี้',
'listgrouprights-removegroup-self-all' => 'ลบทุกกลุ่มออกจากชื่อผู้ใช้นี้',

# E-mail user
'mailnologin'      => 'ไม่มีการส่งอีเมล',
'mailnologintext'  => 'ต้องการทำ[[Special:UserLogin|ล็อกอิน]]และตั้งค่าอีเมลในส่วน[[Special:Preferences|การตั้งค่า]] เพื่อจะส่งอีเมลหาผู้ใช้คนอื่น',
'emailuser'        => 'ส่งอีเมลหาผู้ใช้นี้',
'emailpage'        => 'อีเมลผู้ใช้',
'emailpagetext'    => 'คุณสามารถใช้แบบฟอร์มด้านล่างส่งอีเมลหาผู้ใช้คนนี้
ชื่ออีเมลผู้ส่งจะใช้ชื่ออีเมลที่ได้ระบุไว้แล้วใน[[Special:Preferences|การตั้งค่าส่วนตัวของคุณ]] ซึ่งผู้รับสามารถตอบกลับได้',
'usermailererror'  => 'การส่งอีเมลผิดพลาด:',
'defemailsubject'  => '{{SITENAME}} อีเมล',
'noemailtitle'     => 'ไม่ได้ตั้งอีเมล',
'noemailtext'      => 'ผู้ใช้คนนี้ไม่ได้ตั้งค่าอีเมล',
'nowikiemailtitle' => 'ไม่อนุญาตให้ใช้อีเมล',
'nowikiemailtext'  => 'ผู้ใช้ท่านนี้เลือกไม่รับอีเมล์จากผู้ใช้อื่น',
'email-legend'     => 'ส่งอีเมลถึงผู้ใช้อื่นใน {{SITENAME}}',
'emailfrom'        => 'จาก:',
'emailto'          => 'ถึง:',
'emailsubject'     => 'หัวเรื่อง:',
'emailmessage'     => 'ข้อความ:',
'emailsend'        => 'ส่ง',
'emailccme'        => 'ส่งอีเมลสำเนากลับมา',
'emailccsubject'   => 'ส่งข้อความซ้ำไปที่$1: $2',
'emailsent'        => 'อีเมลได้ถูกส่งเรียบร้อย',
'emailsenttext'    => 'อีเมลได้ถูกส่งเรียบร้อย',
'emailuserfooter'  => 'อีเมลฉบับนี้ถูกส่งโดย $1 ถึง $2 ด้วยฟังก์ชัน "อีเมลผู้ใช้รายนี้" ที่ {{SITENAME}}',

# Watchlist
'watchlist'            => 'รายการเฝ้าดู',
'mywatchlist'          => 'รายการเฝ้าดู',
'watchlistfor'         => "(สำหรับ '''$1''')",
'nowatchlist'          => 'ไม่ได้ใส่หน้าไหนเข้ารายการเฝ้าดู',
'watchlistanontext'    => 'กรุณา $1 เพื่อที่จะดูหรือแก้ไขหน้าในรายการเฝ้าดู',
'watchnologin'         => 'ไม่ได้ล็อกอิน',
'watchnologintext'     => 'ต้อง[[Special:UserLogin|ล็อกอิน]]เพื่อที่จะแก้ไขรายการเฝ้าดู',
'addedwatch'           => 'ถูกใส่เข้ารายการเฝ้าดู',
'addedwatchtext'       => 'หน้า "[[:$1]]" ถูกใส่เข้าไปใน[[Special:Watchlist|รายการเฝ้าดู]]ของคุณ ถ้ามีการเปลี่ยนแปลงเกิดขึ้นในหน้าเหล่านี้ รวมถึงหน้าพูดคุยของหน้านี้
รายชื่อหน้าจะแสดงเป็นตัวหนาในส่วนของ[[Special:RecentChanges|หน้าการเปลี่ยนแปลงล่าสุด]]เพื่อให้โดดเด่นเป็นที่สังเกต
ถ้าไม่ต้องการเฝ้าดูให้กดที่  "เลิกเฝ้าดู" ในส่วนของเมนู',
'removedwatch'         => 'ถูกนำออกจากรายการเฝ้าดู',
'removedwatchtext'     => 'หน้า "[[:$1]]" ถูกนำออกจาก[[Special:Watchlist|รายการเฝ้าดูของท่าน]]',
'watch'                => 'เฝ้าดู',
'watchthispage'        => 'เฝ้าดูหน้านี้',
'unwatch'              => 'เลิกเฝ้าดู',
'unwatchthispage'      => 'เลิกเฝ้าดูหน้านี้',
'notanarticle'         => 'ไม่ใช่หน้าเนื้อหา',
'notvisiblerev'        => 'รุ่นดังกล่าวถูกลบเรียบร้อยแล้ว',
'watchnochange'        => 'ไม่มีการแก้ไขในรายการเฝ้าดูในช่วงเวลาที่กำหนด',
'watchlist-details'    => 'มีหน้าทั้งหมด {{PLURAL:$1|$1 หน้า|$1 หน้า}} ที่อยู่ในรายชื่อเฝ้าดูของคุณ โดยไม่รวมหน้าอภิปราย',
'wlheader-enotif'      => '* แจ้งเตือนผ่านอีเมลถูกเปิดใช้งาน',
'wlheader-showupdated' => "* หน้าที่ถูกเปลี่ยนแปลงตั้งแต่การใช้งานครั้งล่าสุดแสดงใน'''ตัวหนา'''",
'watchmethod-recent'   => 'ตรวจสอบการปรับปรุงล่าสุดกับหน้าเฝ้าดู',
'watchmethod-list'     => 'ตรวจสอบหน้าเฝ้าดูกับการแก้ไขล่าสุด',
'watchlistcontains'    => 'รายการเฝ้าดูของคุณมี $1 หน้า',
'iteminvalidname'      => "เกิดปัญหาชื่อไม่ถูกต้องกับ '$1'...",
'wlnote'               => 'ด้านล่างเป็นการแก้ไข $1 รายการ ในช่วง $2 ชั่วโมงที่ผ่านมา',
'wlshowlast'           => 'แสดงล่าสุดใน $1 ชั่วโมง $2 วัน $3',
'watchlist-options'    => 'ตัวเลือกรายการเฝ้าดู',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'เฝ้าดู...',
'unwatching' => 'เลิกเฝ้าดู...',

'enotif_mailer'                => 'แจ้งการแก้ไขจาก {{SITENAME}}',
'enotif_reset'                 => 'กำหนดทุกหน้าว่าผ่านตาแล้ว',
'enotif_newpagetext'           => 'นี่คือหน้าใหม่',
'enotif_impersonal_salutation' => 'ผู้ใช้งาน {{SITENAME}}',
'changed'                      => 'ถูกเปลี่ยนแปลง',
'created'                      => 'ถูกสร้าง',
'enotif_subject'               => '{{SITENAME}} หน้า $PAGETITLE ได้ $CHANGEDORCREATED โดย $PAGEEDITOR',
'enotif_lastvisited'           => 'ดู $1 สำหรับการเปลี่ยนแปลงตั้งแต่ครั้งล่าสุดที่แวะมา',
'enotif_lastdiff'              => 'ดู $1 สำหรับดูการเปลี่ยนแปลง',
'enotif_anon_editor'           => 'ผู้ใช้นิรนาม $1',
'enotif_body'                  => 'เรียน $WATCHINGUSERNAME,


ทางระบบจากเว็บ {{SITENAME}} ต้องการแจ้งให้ทราบว่า หน้า $PAGETITLE ได้ $CHANGEDORCREATED เมื่อ $PAGEEDITDATE โดย $PAGEEDITOR ดูรุ่นปัจจุบันได้ที่ $PAGETITLE_URL

$NEWPAGE

คำสรุปการแก้ไข: $PAGESUMMARY $PAGEMINOREDIT

ติดต่อผู้แก้ไข:
อีเมล: $PAGEEDITOR_EMAIL
วิกิ: $PAGEEDITOR_WIKI

จะไม่มีการแจ้งเพิ่มเติมจนกว่าคุณจะได้แวะเข้าไปที่หน้านี้
นอกจากนี้คุณสามารถตั้งค่ายกเลิกการแจ้งของหน้าที่อยู่ในรายการเฝ้าดูได้

             ระบบแจ้งอัตโนมัติจาก {{SITENAME}}

--
ถ้าต้องการเปลี่ยนแปลงรายการเฝ้าดู ให้เข้าที่:
{{fullurl:{{#special:Watchlist}}/edit}}

ถ้าต้องการความช่วยเหลือเพิ่มเติม ให้เข้าที่:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'ลบหน้า',
'confirm'                => 'ยืนยัน',
'excontent'              => "เนื้อหาเดิม: '$1'",
'excontentauthor'        => "เนื้อหาเดิม: '$1' (และมีผู้เขียนคนเดียว คือ '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "เนื้อหาเดิมก่อนหน้าถูกทำให้ว่าง: '$1'",
'exblank'                => 'หน้าว่าง',
'delete-confirm'         => 'ลบ "$1"',
'delete-legend'          => 'ลบ',
'historywarning'         => 'คำเตือน: หน้าที่คุณกำลังจะลบ มีประวัติการแก้ไขโดยประมาณ $1 {{PLURAL:$1|รุ่น}}:',
'confirmdeletetext'      => 'คุณกำลังจะลบหน้าหรือภาพนี้ รวมไปถึงประวัติหน้าออกจากระบบ
กรุณายืนยันว่าต้องการดำเนินการต่อและแน่ใจว่าได้เข้าใจและการลบครั้งนี้สอดคล้องกับ[[{{MediaWiki:Policy-url}}]]',
'actioncomplete'         => 'จัดการสำเร็จ',
'actionfailed'           => 'การกระทำล้มเหลว',
'deletedtext'            => '"<nowiki>$1</nowiki>" ถูกลบ
ดู $2 สำหรับบันทึกการลบล่าสุด',
'deletedarticle'         => '"[[$1]]" ถูกลบ',
'suppressedarticle'      => '"[[$1]]" ระงับแล้ว',
'dellogpage'             => 'บันทึกการลบ',
'dellogpagetext'         => 'ด้านล่างเป็นรายการของการลบล่าสุด',
'deletionlog'            => 'บันทึกการลบ',
'reverted'               => 'ย้อนไปรุ่นก่อนหน้า',
'deletecomment'          => 'เหตุผล:',
'deleteotherreason'      => 'เหตุผลอื่นเพิ่มเติม:',
'deletereasonotherlist'  => 'เหตุผลอื่น',
'deletereason-dropdown'  => '* เหตุผลทั่วไปของการลบ
** รับแจ้งจากผู้เขียน
** ละเมิดลิขสิทธิ์
** ก่อกวน',
'delete-edit-reasonlist' => 'แก้ไขรายชื่อเหตุผลในการลบ',
'delete-toobig'          => 'หน้านี้มีประวัติการแก้ไขมากเกินกว่า $1 {{PLURAL:$1|รุ่น|รุ่น}} ซึ่งถือว่าเยอะมาก เพื่อป้องกันไม่ให้ {{SITENAME}} ได้รับความเสียหายอย่างที่ไม่เคยคาดคิดมาก่อน จึงไม่อนุญาตให้ลบหน้านี้',
'delete-warning-toobig'  => 'หน้านี้มีประวัติการแก้ไขมากเกินกว่า $1 {{PLURAL:$1|รุ่น|รุ่น}} ซึ่งถือว่าเยอะมาก การลบหน้านี้อาจทำให้ {{SITENAME}} ได้รับความเสียหายอย่างที่ไม่เคยคาดคิดมาก่อน จึงได้เตือนไว้ ก่อนที่จะกระทำสิ่งนี้',

# Rollback
'rollback'          => 'ถอยการแก้ไขกลับฉุกเฉิน',
'rollback_short'    => 'ถอยกลับฉุกเฉิน',
'rollbacklink'      => 'ถอยกลับฉุกเฉิน',
'rollbackfailed'    => 'ย้อนไม่สำเร็จ',
'cantrollback'      => 'ไม่สามารถย้อนการแก้ไขได้ เนื่องจากหน้านี้ไม่มีผู้แก้ไขรายอื่นอีก',
'alreadyrolled'     => 'ไม่สามารถย้อนรุ่นล่าสุด
ที่แก้โดย [[User:$2|$2]] ([[User talk:$2|พูดคุย]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) ของหน้า [[:$1]] มีใครบางคนได้แก้ไขหรือย้อนหน้านี้ไปก่อนแล้ว

ผู้แก้ไขล่าสุดของหน้านี้คือ [[User:$3|$3]] ([[User talk:$3|พูดคุย]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]])',
'editcomment'       => "สรุปการแก้ไข: \"''\$1''\"",
'revertpage'        => 'ย้อนการแก้ไขของ [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) ไปยังรุ่นของ [[User:$1|$1]]',
'revertpage-nouser' => 'ย้อนการแก้ไขโดย (ชื่อผู้ใช้ถูกลบออก) ไปยังรุ่นล่าสุดโดย [[User:$1|$1]]',
'rollback-success'  => 'ย้อนรุ่นที่แก้ไขโดย $1 ไปยังรุ่นล่าสุดที่แก้ไขโดย $2 แล้ว',

# Edit tokens
'sessionfailure' => 'ท่าทางจะมีปัญหาเกี่ยวการล็อกอินในช่วงเวลานี้ เกิดจากทางระบบป้องกันการลักลอบการขโมยล็อกอิน กรุณาย้อนกลับไปหน้าก่อนหน้า และลองโหลดใหม่อีกครั้ง',

# Protect
'protectlogpage'              => 'บันทึกการล็อก',
'protectlogtext'              => 'รายการด้านล่างแสดงการล็อกหน้าและการปลดล็อก สำหรับหน้าที่โดนล็อกในปัจจุบันดูที่ [[Special:ProtectedPages|รายการหน้าที่ถูกล็อก]]',
'protectedarticle'            => '"[[$1]]" ถูกล็อก',
'modifiedarticleprotection'   => 'เปลี่ยนระดับการล็อกสำหรับ "[[$1]]"',
'unprotectedarticle'          => '"[[$1]]" ถูกปลดล็อก',
'movedarticleprotection'      => 'ย้ายการตั้งค่าการล็อกจาก "[[$2]]" ไปยัง "[[$1]]"',
'protect-title'               => 'กำลังล็อกหน้า "$1"',
'prot_1movedto2'              => '[[$1]] ถูกเปลี่ยนชื่อเป็น [[$2]]',
'protect-legend'              => 'ยืนยันการล็อก',
'protectcomment'              => 'เหตุผล:',
'protectexpiry'               => 'หมดอายุ:',
'protect_expiry_invalid'      => 'เวลาหมดอายุไม่ถูกต้อง',
'protect_expiry_old'          => 'เวลาหมดอายุผ่านมาแล้ว',
'protect-unchain-permissions' => 'ปลดล็อกตัวเลือกป้องกันอื่นๆ',
'protect-text'                => "ดูและเปลี่ยนระดับการล็อกสำหรับหน้า '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "ไม่สามารถเปลี่ยนระดับการล็อกหน้าขณะที่ถูกบล็อกได้ ดูระดับการล็อกของหน้า '''$1''':",
'protect-locked-dblock'       => "ไม่สามารถเปลี่ยนระดับการล็อกหน้าได้เนื่องจากฐานข้อมูลถูกล็อก ดูระดับการล็อกของหน้า '''$1''':",
'protect-locked-access'       => "คุณไม่สามารถเปลี่ยนระดับการล็อกหน้าได้ เนื่องจากคุณไม่มีสิทธิ  ดูระดับการล็อกของหน้า '''$1''':",
'protect-cascadeon'           => 'หน้านี้ถูกล็อกเนื่องจากเป็นส่วนหนึ่งของ{{PLURAL:$1|หน้า|หน้า}}ที่ถูกล็อกแบบสืบทอด
คุณสามารถเปลี่ยนระดับการล็อกได้ แต่จะไม่มีผลต่อการล็อกแบบสืบทอด',
'protect-default'             => 'อนุญาตให้ใช้งานสำหรับผู้ใช้ทั้งหมด',
'protect-fallback'            => 'จำเป็นต้องใช้สิทธิในการ "$1"',
'protect-level-autoconfirmed' => 'บล็อกผู้ใช้ใหม่และผู้ใช้ไม่ลงทะเบียน',
'protect-level-sysop'         => 'ผู้ดูแลระบบแก้ไขเท่านั้น',
'protect-summary-cascade'     => 'สืบทอด',
'protect-expiring'            => 'หมดอายุ $1 (UTC)',
'protect-expiry-indefinite'   => 'ตลอดกาล',
'protect-cascade'             => 'ล็อกหน้าที่เป็นส่วนหนึ่งของหน้านี้ (ล็อกแบบสืบทอด)',
'protect-cantedit'            => 'คุณไม่สามารถเปลี่ยนระดับการป้องกันของหน้านี้ เนื่องจากคุณไม่ได้รับสิทธิในการแก้ไขสิ่งนั้น',
'protect-othertime'           => 'ระยะเวลาอื่น:',
'protect-othertime-op'        => 'ระยะเวลาอื่น',
'protect-existing-expiry'     => 'ระยะเวลาการป้องกัน: $3, $2',
'protect-otherreason'         => 'เหตุผลอื่นเพิ่มเติม:',
'protect-otherreason-op'      => 'เหตุผลอื่นหรือเหตุผลเพิ่มเติม',
'protect-dropdown'            => '* เหตุผลโดยทั่วไปสำหรับการป้องกัน
** การก่อกวนจำนวนมาก
** สแปมจำนวนมาก
** สงครามการแก้ไข
** หน้าสำคัญ',
'protect-edit-reasonlist'     => 'สาเหตุการป้องกันการแก้ไข',
'protect-expiry-options'      => '1 ชั่วโมง:1 hour,1 วัน:1 day,1 สัปดาห์:1 week,2 สัปดาห์:2 weeks,1 เดือน:1 month,3 เดือน:3 months,6 เดือน:6 months,1 ปี:1 year,ตลอดกาล:infinite',
'restriction-type'            => 'อนุญาต',
'restriction-level'           => 'ระดับการล็อก',
'minimum-size'                => 'ขนาดอย่างน้อย',
'maximum-size'                => 'ขนาดอย่างมาก',
'pagesize'                    => '(ไบต์)',

# Restrictions (nouns)
'restriction-edit'   => 'แก้ไข',
'restriction-move'   => 'เปลี่ยนชื่อ',
'restriction-create' => 'สร้าง',
'restriction-upload' => 'อัปโหลด',

# Restriction levels
'restriction-level-sysop'         => 'ล็อกเต็มที่',
'restriction-level-autoconfirmed' => 'ล็อกผู้ไม่ล็อกอิน',
'restriction-level-all'           => 'ระดับ',

# Undelete
'undelete'                     => 'เรียกคืน',
'undeletepage'                 => 'ดูและเรียกคืนหน้าที่ถูกลบ',
'undeletepagetitle'            => "'''ต่อไปนี้เป็นรุ่นการแก้ไขของ [[:$1|$1]] ที่ถูกลบ'''",
'viewdeletedpage'              => 'หน้าที่ถูกลบ',
'undeletepagetext'             => '{{PLURAL:$1|หน้า|หน้า}}ต่อไปนี้ถูกลบไปแล้ว แต่ยังคงอยู่ในกรุซึ่งสามารถเรียกคืนได้ กรุข้อมูลอาจถูกลบเป็นระยะ',
'undelete-fieldset-title'      => 'เรียกคืนรุ่นต่างๆ',
'undeleteextrahelp'            => "ถ้าต้องการเรียกคืนประวัติของหน้าทั้งหมด ไม่ต้องเลือกกล่องใดเลย แล้วกดปุ่ม '''''เรียกคืน'''''
ถ้าต้องการเรียกคืนประวัติเฉพาะส่วนใดส่วนหนึ่ง เลือกกล่องที่มีประวัติส่วนที่ต้องการจะเรียกคืน แล้วกด'''''เรียกคืน''''' 
กด '''''ล้างค่า''''' เพื่อลบค่าในกล่องความเห็นและกล่องตัวเลือกทั้งหมด",
'undeleterevisions'            => '$1 รุ่นการแก้ไขถูกเก็บไว้',
'undeletehistory'              => 'เมื่อคุณเรียกคืนหน้าใดหน้าหนึ่ง รุ่นการแก้ไขทั้งหมดจะถูกเรียกคืนไปยังประวัติ หากมีหน้าใหม่ในชื่อเดียวกันถูกสร้างขึ้นหลังจากการลบ รุ่นที่เรียกคืนจะปรากฏในช่วงประวัติที่มีมาก่อน',
'undeleterevdel'               => 'จะเรียกคืนไม่ได้ถ้ารุ่นในส่วนที่ใหม่ถูกลบไปบางส่วน ถ้าเกิดขึ้นในกรณีนี้ ต้องกดแสดงในส่วนใหม่ก่อน',
'undeletehistorynoadmin'       => 'หน้านี้ถูกลบก่อนหน้านี้ โดยสาเหตุการลบและรายชื่อผู้ร่วมแก้ไขก่อนหน้าแสดงผลด้านล่าง สำหรับข้อมูลที่ถูกลบจะดูได้เฉพาะผู้ดูแลระบบ',
'undelete-revision'            => 'รุ่นที่ถูกลบของหน้า $1 (ตั้งแต่ $4 เมื่อ $5) โดย $3:',
'undeleterevision-missing'     => 'รุ่นที่ต้องการดูไม่มี ข้อมูลอาจจะโดนลบ',
'undelete-nodiff'              => 'ไม่พบรุ่นก่อนหน้า',
'undeletebtn'                  => 'เรียกคืน',
'undeletelink'                 => 'เรียกดู/เรียกคืน',
'undeleteviewlink'             => 'เรียกดู',
'undeletereset'                => 'ล้างค่า',
'undeleteinvert'               => 'กลับค่าที่เลือก',
'undeletecomment'              => 'ความเห็น:',
'undeletedarticle'             => 'เรียกคืน "[[$1]]"',
'undeletedrevisions'           => '$1 รุ่นการแก้ไขถูกเรียกคืน',
'undeletedrevisions-files'     => '$1 รุ่นการแก้ไข และ $2 ไฟล์ถูกเรียกคืน',
'undeletedfiles'               => '$1 ไฟล์ถูกเรียกคืน',
'cannotundelete'               => 'เรียกคืนไม่สำเร็จ อาจมีใครบางคนเรียกคืนหน้านั้นแล้ว',
'undeletedpage'                => "'''$1 ถูกเรียกคืน'''

ดูเพิ่มเติม [[Special:Log/delete|บันทึกการลบ]] สำหรับรายชื่อการลบและการเรียกคืนที่ผ่านมา",
'undelete-header'              => 'ดู [[Special:Log/delete|บันทึกการลบ]] สำหรับหน้าที่ถูกลบล่าสุด',
'undelete-search-box'          => 'ค้นหาหน้าที่ถูกลบ',
'undelete-search-prefix'       => 'ค้นหาหน้าที่เริ่มต้นด้วย:',
'undelete-search-submit'       => 'สืบค้น',
'undelete-no-results'          => 'ไม่พบหน้าที่ต้องการจากบันทึกการลบ',
'undelete-filename-mismatch'   => 'ไม่สามารถกู้คืนไฟล์ $1: ชื่อไฟล์ไม่ถูกต้อง',
'undelete-bad-store-key'       => 'ไม่สามารถกู้คืนไฟล์ $1: ไม่มีไฟล์ก่อนที่จะถูกลบ',
'undelete-cleanup-error'       => 'เกิดปัญหาการลบไฟล์เก่า "$1"',
'undelete-missing-filearchive' => 'ไม่สามารถกู้คืนไฟล์เก่ารุ่น $1 เพราะว่าไม่มีไฟล์อยู่ในฐานข้อมูล  ไฟล์อาจจะถูกกู้คืนไปก่อนหน้า',
'undelete-error-short'         => 'เกิดปัญหาในการกู้คืนไฟล์: $1',
'undelete-error-long'          => 'เกิดความผิดพลาดระหว่างการลบไฟล์:

$1',
'undelete-show-file-confirm'   => 'แน่ใจหรือไม่ว่าคุณต้องการจะดูรุ่นที่ถูกลบไป สำหรับไฟล์ "<nowiki>$1</nowiki>" ตั้งแต่ $2 เมื่อ $3',
'undelete-show-file-submit'    => 'ใช่',

# Namespace form on various pages
'namespace'      => 'เนมสเปซ:',
'invert'         => 'ทั้งหมดที่ไม่ได้เลือก',
'blanknamespace' => '(หลัก)',

# Contributions
'contributions'       => 'เรื่องที่เขียนโดยผู้ใช้นี้',
'contributions-title' => 'เรื่องที่เขียนโดย $1',
'mycontris'           => 'เรื่องที่เขียน',
'contribsub2'         => 'สำหรับ $1 ($2)',
'nocontribs'          => 'ไม่มีการเปลี่ยนแปลงตามเงื่อนไขที่ใส่มา',
'uctop'               => ' (บนสุด)',
'month'               => 'จากเดือน (และก่อนหน้า):',
'year'                => 'จากปี (และก่อนหน้า):',

'sp-contributions-newbies'        => 'แสดงการแก้ไขของผู้ใช้ใหม่เท่านั้น',
'sp-contributions-newbies-sub'    => 'สำหรับผู้ใช้ใหม่',
'sp-contributions-newbies-title'  => 'เรื่องที่เขียนโดยบัญชีผู้ใช้ใหม่',
'sp-contributions-blocklog'       => 'บันทึกการบล็อก',
'sp-contributions-deleted'        => 'การแก้ไขที่ถูกลบ',
'sp-contributions-logs'           => 'ปูม',
'sp-contributions-talk'           => 'พูดคุย',
'sp-contributions-userrights'     => 'บริหารสิทธิผู้ใช้',
'sp-contributions-blocked-notice' => 'ปัจจุบันผู้ใช้นี้ถูกสกัดกั้น
ปูมการสกัดกั้นรายการล่าสุดแสดงไว้ด้านล่างนี้เพื่อการอ้างอิง:',
'sp-contributions-search'         => 'ค้นหาการแก้ไข',
'sp-contributions-username'       => 'หมายเลขไอพีหรือชื่อผู้ใช้:',
'sp-contributions-submit'         => 'สืบค้น',

# What links here
'whatlinkshere'            => 'หน้าที่ลิงก์มา',
'whatlinkshere-title'      => 'หน้าที่โยงมาที่ "$1"',
'whatlinkshere-page'       => 'หน้า:',
'linkshere'                => "หน้าต่อไปนี้ลิงก์มาที่ '''[[:$1]]''':",
'nolinkshere'              => "ไม่มีหน้าใดลิงก์มาที่ '''[[:$1]]'''",
'nolinkshere-ns'           => "ไม่มีหน้าใดลิงก์มาที่'''[[:$1]]''' ในเนมสเปซที่เลือกไว้",
'isredirect'               => 'หน้าเปลี่ยนทาง',
'istemplate'               => 'รวมอยู่',
'isimage'                  => 'ลิงก์ภาพ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ก่อนหน้า|ก่อนหน้า $1 หน้า}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ถัดไป|ถัดไป $1 หน้า}}',
'whatlinkshere-links'      => '← ลิงก์',
'whatlinkshere-hideredirs' => '$1 หน้าเปลี่ยนทาง',
'whatlinkshere-hidetrans'  => '$1 ถูกรวมอยู่',
'whatlinkshere-hidelinks'  => '$1 ลิงก์',
'whatlinkshere-hideimages' => '$1 ภาพที่ลิงก์',
'whatlinkshere-filters'    => 'ตัวกรอง',

# Block/unblock
'blockip'                         => 'บล็อกผู้ใช้',
'blockip-title'                   => 'ระงับผู้ใช้',
'blockip-legend'                  => 'บล็อกผู้ใช้',
'blockiptext'                     => 'ใช้ฟอร์มด้านล่างสำหรับการบล็อกหมายเลขไอพีหรือผู้ใช้ ซึ่งก่อกวนระบบ โดยแน่ใจว่าได้ทำตาม [[{{MediaWiki:Policy-url}}|นโยบาย]]
ใส่สาเหตุด้านล่าง (ตัวอย่าง หน้าที่ถูกก่อกวน)',
'ipaddress'                       => 'หมายเลขไอพี:',
'ipadressorusername'              => 'หมายเลขไอพีหรือชื่อผู้ใช้',
'ipbexpiry'                       => 'หมดอายุ',
'ipbreason'                       => 'เหตุผล:',
'ipbreasonotherlist'              => 'เลือกสาเหตุ',
'ipbreason-dropdown'              => '*สาเหตุการบล็อกทั่วไป
** ใส่ข้อมูลเท็จ
** ลบเนื้อหาในหน้าออก
** ใส่ลิงก์สแปม
** ใส่ข้อความขยะเข้ามา
** คุกคามผู้อื่น
** ก่อกวนผู้อื่น
** ชื่อผู้ใช้ที่ไม่สุภาพหรือไม่ควรใช้',
'ipbanononly'                     => 'บล็อกผู้ใช้นิรนามเท่านั้น',
'ipbcreateaccount'                => 'ป้องกันการสร้างบัญชีผู้ใช้',
'ipbemailban'                     => 'ป้องกันผู้ใช้ส่งอีเมลผ่านระบบ',
'ipbenableautoblock'              => 'บล็อกหมายเลขไอพีนี้และไอพีที่ผู้ใช้นี้อาจจะใช้',
'ipbsubmit'                       => 'บล็อกชื่อผู้ใช้',
'ipbother'                        => 'เวลาอื่น',
'ipboptions'                      => '2 ชั่วโมง:2 hours,1 วัน:1 day,3 วัน:3 days,1 สัปดาห์:1 week,2 สัปดาห์:2 weeks,1 เดือน:1 month,3 เดือน:3 months,6 เดือน:6 months,1 ปี:1 year,ตลอดกาล:infinite',
'ipbotheroption'                  => 'เลือกเวลา',
'ipbotherreason'                  => 'เหตุผลอื่น',
'ipbhidename'                     => 'ซ่อนผู้้ใช้จากบันทึกการบล็อก และรายการผู้ที่ถูกบล็อก',
'ipbwatchuser'                    => 'เฝ้าดูหน้าผู้ใช้และหน้าคุยกับผู้ใช้ของผู้ใช้รายนี้',
'ipballowusertalk'                => 'อนุญาตให้ผู้ใช้รายนี้แก้ไขหน้าพูดคุยของตนเอง ขณะที่ถูกบล็อก',
'ipb-change-block'                => 'บล็อกผู้ใช้อีกครั้งด้วยการตั้งค่าเหล่านี้',
'badipaddress'                    => 'หมายเลขไอพีไม่ถูกต้อง',
'blockipsuccesssub'               => 'บล็อกเรียบร้อย',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] ถูกบล็อก
<br />ดู  [[Special:IPBlockList|รายการไอพีที่ถูกบล็อก]] เพื่อตรวจสอบการบล็อก',
'ipb-edit-dropdown'               => 'แก้ไขสาเหตุการบล็อก',
'ipb-unblock-addr'                => 'เลิกบล็อก $1',
'ipb-unblock'                     => 'เลิกบล็อกผู้ใช้หรือหมายเลขไอพี',
'ipb-blocklist-addr'              => 'ดูการบล็อกที่มีอยู่สำหรับ $1',
'ipb-blocklist'                   => 'ดูการปล็อกปัจจุบัน',
'ipb-blocklist-contribs'          => 'ผลงานที่สร้างสรรค์โดย $1',
'unblockip'                       => 'ปลดบล็อกผู้ใช้',
'unblockiptext'                   => 'ใช้แบบฟอร์มด้านล่างสำหรับบล็อกหรือเลิกบล็อกหมายเลขไอพี หรือผู้ใช้',
'ipusubmit'                       => 'ยกเลิกการบล็อกนี้',
'unblocked'                       => '[[User:$1|$1]] ถูกบล็อก',
'unblocked-id'                    => 'เลิกบล็อก $1',
'ipblocklist'                     => 'หมายเลขไอพีและผู้ใช้ที่ถูกบล็อก',
'ipblocklist-legend'              => 'ค้นหาผู้ใช้ที่ถูกระงับการใช้งาน',
'ipblocklist-username'            => 'ชื่อผู้ใช้หรือหมายเลขไอพี:',
'ipblocklist-sh-userblocks'       => '$1 การบล็อกชื่อบัญชี',
'ipblocklist-sh-tempblocks'       => '$1 การบล็อกชั่วคราว',
'ipblocklist-sh-addressblocks'    => '$1 การบล็อกของไอพีเดี่ยว',
'ipblocklist-submit'              => 'สืบค้น',
'ipblocklist-localblock'          => 'การสกัดกั้นภายในวิกินี้',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|การสกัดกั้น}}อื่นๆ',
'blocklistline'                   => '$1, $2 บล็อก $3 ($4)',
'infiniteblock'                   => 'ตลอดกาล',
'expiringblock'                   => 'หมดอายุ $1 เวลา $2',
'anononlyblock'                   => 'ไม่ล็อกอินเท่านั้น',
'noautoblockblock'                => 'ยกเลิกการบล็อกอัตโนมัติ',
'createaccountblock'              => 'บล็อกการสร้างบัญชีผู้ใช้ใหม่',
'emailblock'                      => 'บล็อกการส่งอีเมล',
'blocklist-nousertalk'            => 'ไม่สามารถแก้ไขหน้าอภิปรายของตนเอง',
'ipblocklist-empty'               => 'รายการบล็อกว่าง',
'ipblocklist-no-results'          => 'หมายเลขไอพีหรือชื่อผู้ใช้ที่ต้องการไม่ได้ถูกบล็อก',
'blocklink'                       => 'บล็อก',
'unblocklink'                     => 'เลิกบล็อก',
'change-blocklink'                => 'เปลี่ยนการบล็อก',
'contribslink'                    => 'เรื่องที่เขียน',
'autoblocker'                     => 'ถูกบล็อกอัตโนมัติเนื่องจากหมายเลขไอพีของคุณตรงกับ "[[User:$1|$1]]" ถูกบล็อกกล่อนหน้านี้เนื่องจากสาเหตุ: "$2"',
'blocklogpage'                    => 'บันทึกการบล็อก',
'blocklog-showlog'                => 'ผู้ใช้นี้ถูกสกัดกั้นมาก่อน
ปูมการสกัดกั้นแสดงไว้ด้านล่างนี้เพื่อการอ้างอิง:',
'blocklog-showsuppresslog'        => 'ผู้ใช้นี้ถูกสกัดกั้นและถูกซ่อนมาก่อน
ปูมการระงับแสดงไว้ด้านล่างนี้เพื่อการอ้างอิง:',
'blocklogentry'                   => 'บล็อก "[[$1]]" หมดอายุ $2 $3',
'reblock-logentry'                => 'เปลี่ยนระดับการบล็อกสำหรับ [[$1]] หมดอายุ $2 $3',
'blocklogtext'                    => 'ด้านล่างเป็นบันทึกการบล็อกและการเลิกบล็อก  ส่วนการบล็อกอัตโนมัติจะไม่ถูกรวมอยู่ในรายการนี้ ดู [[Special:IPBlockList|รายการบล็อกไอพี]] สำหรับการบล็อกทั้งหมด',
'unblocklogentry'                 => 'เลิกบล็อก $1',
'block-log-flags-anononly'        => 'ผู้ใช้นิรนามเท่านั้น',
'block-log-flags-nocreate'        => 'ห้ามสร้างบัญชีผู้ใช้',
'block-log-flags-noautoblock'     => 'ยกเลิกการบล็อกอัตโนมัติ',
'block-log-flags-noemail'         => 'บล็อกการส่งอีเมล',
'block-log-flags-nousertalk'      => 'ไม่สามารถแก้ไขหน้าอภิปรายของตนเอง',
'block-log-flags-angry-autoblock' => 'การบล็อกอัตโนมัติขั้นสูงเปิดใช้งาน',
'block-log-flags-hiddenname'      => 'ชื่อผู้ใช้ถูกซ่อน',
'range_block_disabled'            => 'ยกเลิกการบล็อกช่วงไอพีของผู้ดูแลระบบ',
'ipb_expiry_invalid'              => 'ค่าวันหมดอายุไม่ถูกต้อง',
'ipb_expiry_temp'                 => 'ผู้ใช้ที่ถูกบล็อกจะซ่อนโดยถาวร',
'ipb_hide_invalid'                => 'ไม่สามารถยับยั้งชื่อผู้ใช้นี้ได้; เนื่องจากอาจจะมีการแก้ไขมากเกินไป',
'ipb_already_blocked'             => '"$1" ถูกบล็อกแล้วก่อนหน้านี้',
'ipb-needreblock'                 => '== ถูกบล็อกแล้ว ==
$1 ถูกบล็อกแล้ว คุณต้องการแก้ไขหรือไม่',
'ipb-otherblocks-header'          => '{{PLURAL:$1|การระงับ|การระงับ}}อื่นๆ',
'ipb_cant_unblock'                => 'ปัญหา: หมายเลขบล็อก $1 ไม่พบ อาจเกิดจากได้ถูกยกเลิกการบล็อกแล้ว',
'ipb_blocked_as_range'            => 'มีข้อผิดพลาด: หมายเลขไอพี $1 ไม่ได้ถูกระงับโดยตรงและไม่สามารถยกเลิกการระงับโดยตรงได้.  อย่างไรก็ตาม ไอพีนี้ถูกระงับในฐานะที่เป็นส่วนหนึ่งของหมายเลขไอพีในช่วง $2 ซึ่งสามารถยกเลิกการระงับได้',
'ip_range_invalid'                => 'ช่วงไอพีไม่ถูกต้อง',
'blockme'                         => 'บล็อกฉัน',
'proxyblocker'                    => 'บล็อกพร็อกซี',
'proxyblocker-disabled'           => 'ฟังก์ชั่นนี้ไม่สามารถใช้ได้',
'proxyblockreason'                => 'หมายเลขไอพีของคุณถูกบล็อกเนื่องจากเป็นพร็อกซีเปิด กรุณาติดต่อผู้ให้บริการอินเทอร์เน็ตที่คุณใช้งานอยู่เกี่ยวกับปัญหานี้',
'proxyblocksuccess'               => 'บล็อกสำเร็จ',
'sorbsreason'                     => 'หมายเลขไอพีของคุณอยู่ในพร็อกซีเปิดในส่วน DNSBL ที่ถูกใช้งานในเว็บไซต์',
'sorbs_create_account_reason'     => 'หมายเลขไอพีของคุณอยู่ในพร็อกซีเปิดในส่วน DNSBL ที่ถูกใช้งานในเว็บไซต์ ดังนั้นคุณไม่สามารถสร้างชื่อบัญชีผู้ใช้ได้',
'cant-block-while-blocked'        => 'คุณไม่สามารถบล็อกผู้ใช้อื่นในขณะที่คุณกำลังถูกบล็อก',
'cant-see-hidden-user'            => 'ผู้ใช่ที่คุณกำลังพยายามระงับนั้นได้ถูกระงับหรือซ่อนเดิมอยู่แล้ว ในขณะที่ีคุณไม่มีสิทธิ์ในการซ่อนผู้ใช้ คุณไม่สามารถดูหรือแก้ไขการระงับผู้ใช้ได้',

# Developer tools
'lockdb'              => 'ล็อกฐานข้อมูล',
'unlockdb'            => 'ปลดล็อกฐานข้อมูล',
'lockdbtext'          => 'เมื่อล็อกฐานข้อมูลจะส่งผลให้ไม่สามารถแก้ไขทุกหน้า หรือแม้แต่เปลี่ยนแปลงการตั้งค่า ตรวจสอบให้แน่ใจว่าต้องการล็อกฐานข้อมูล และอย่าลืมปลดล็อกเมื่อตรวจสอบฐานข้อมูลเรียบร้อย',
'unlockdbtext'        => 'เมื่อปลดล็อกฐานข้อมูลจะส่งผลให้ ผู้ใช้สามารถเริ่มแก้ไขหน้าได้เหมือนเดิม รวมถึงการตั้งค่าทุกอย่าง ตรวจสอบให้แน่ใจว่าต้องการปลดล็อกฐานข้อมูล',
'lockconfirm'         => 'ยืนยัน ต้องการล็อกฐานข้อมูล',
'unlockconfirm'       => 'ยืนยัน ต้องการปลดล็อกฐานข้อมูล',
'lockbtn'             => 'ล็อกฐานข้อมูล',
'unlockbtn'           => 'ปลดล็อกฐานข้อมูล',
'locknoconfirm'       => 'ค่าาตัวเลือกไม่ได้ถูกเลือก',
'lockdbsuccesssub'    => 'ล็อกฐานข้อมูลเรียบร้อย',
'unlockdbsuccesssub'  => 'ปลดล็อกฐานข้อมูลเรียบร้อย',
'lockdbsuccesstext'   => 'ล็อกฐานข้อมูลเรียบร้อย
<br />อย่าลืมที่จะ [[Special:UnlockDB|ปลดล็อก]] เพื่อให้ใช้งานได้ตามปกติ',
'unlockdbsuccesstext' => 'ปลดล็อกฐานข้อมูลเรียบร้อย',
'lockfilenotwritable' => 'ไม่สามารถล็อกฐานข้อมูลได้ เนื่องจากการเขียนลงฐานข้อมูล การล็อกและการปลดล็อกจำเป็นต้องทำที่เว็บเซิร์ฟเวอร์',
'databasenotlocked'   => 'ฐานข้อมูลไม่ได้ล็อก',

# Move page
'move-page'                    => 'ย้าย $1',
'move-page-legend'             => 'เปลี่ยนชื่อ',
'movepagetext'                 => "ใช้แบบฟอร์มด้านล่างในการเปลี่ยนชื่อหน้า ซึ่งประวัติการแก้ไขของหน้านี้จะถูกย้ายตามไปด้วย
นอกจากนี้ชื่อของหน้าเดิมจะถูกเปลี่ยนเป็นหน้าเปลี่ยนทาง ซึ่งหน้าที่ลิงก์มายังหน้าเก่าจะลิงก์ต่อมาที่หน้าใหม่ แต่ยังคงที่ชื่อเดิม
อย่าลืมตรวจสอบหน้าเปลี่ยนทางซ้ำซ้อนที่อาจจะเกิดขึ้น

การเปลี่ยนชื่อจะ'''ไม่'''สามารถเปลี่ยนทับชื่อเดิมได้ หากหน้านั้นไม่ใช่หน้าว่างหรือหน้าเปลี่ยนทาง

<b>คำเตือน!</b>
การเปลี่ยนชื่อจะมีผลอย่างมากกับสถิติของหน้านิยมที่มีคนเข้าดูมาก ให้แน่ใจว่าต้องการเปลี่ยนชื่อในครั้งนี้",
'movepagetalktext'             => "หน้าพูดคุยของหน้านี้จะถูกเปลี่ยนชื่อตามไปด้วย '''เว้นเสียแต่:'''
*หน้าพูดคุยไม่ว่างมีแล้วที่ชื่อใหม่ หรือ
*ได้เลือกไม่ต้องการเปลี่ยนชื่อด้านล่าง

ในกรณีนั้นให้เปลี่ยนชื่อหน้าเอง",
'movearticle'                  => 'เปลี่ยนชื่อ',
'moveuserpage-warning'         => "'''คำเตือน''' คุณกำลังจะย้ายหน้าผู้ใช้ โปรดทราบว่าหน้าผู้ใช้เท่านั้นที่จะถูกเปลี่ยนชื่อ แต่ผู้ใช้จะ'''ไม่ได้ถูกเปลี่ยนชื่อแต่อย่างใด'''",
'movenologin'                  => 'ไม่ได้ล็อกอิน',
'movenologintext'              => 'ถ้าต้องการเปลี่ยนชื่อหน้านี้ ต้องลงทะเบียนและให้ทำการ[[Special:UserLogin|ล็อกอิน]]',
'movenotallowed'               => 'คุณไม่ได้รับอนุญาตให้ทำการย้ายหน้าต่าง ๆ',
'movenotallowedfile'           => 'คุณไม่มีสิทธิ์ที่จะย้ายไฟล์',
'cant-move-user-page'          => 'คุณไม่มีสิทธิในการย้ายหน้าผู้ใช้ (แยกจากหน้าย่อย)',
'cant-move-to-user-page'       => 'คุณไม่มีสิทธิในการย้ายหน้าใด ๆ ไปเป็นหน้าผู้ใช้ (ยกเว้นหน้าย่อยของผู้ใช้)',
'newtitle'                     => 'ชื่อใหม่',
'move-watch'                   => 'เฝ้าดูหน้านี้',
'movepagebtn'                  => 'เปลี่ยนชื่อ',
'pagemovedsub'                 => 'เปลี่ยนชื่อสำเร็จ',
'movepage-moved'               => '\'\'\'"$1" ถูกเปลี่ยนชื่อเป็น "$2"\'\'\'',
'movepage-moved-redirect'      => 'หน้าเปลี่ยนทางถูกสร้างขึ้น',
'movepage-moved-noredirect'    => 'หน้าเปลี่ยนทางไม่ได้ถูกสร้าง',
'articleexists'                => 'หน้าที่ต้องการมีอยู่แล้ว หรือชื่อที่เลือกไม่ถูกต้อง กรุณาเลือกชื่อใหม่',
'cantmove-titleprotected'      => 'คุณไม่สามารถเปลี่ยนชื่อหน้าเป็นชื่อนี้ได้ เนื่องจากชื่อใหม่นี้ได้รับการป้องกันไม่ให้สร้างใหม่',
'talkexists'                   => "'''หน้าได้ถูกเปลี่ยนชื่อเรียบร้อย แต่หน้าพูดคุยไม่ได้ถูกเปลี่ยนตามไปด้วยเนื่องจากมีหน้าพูดคุยซ้ำแล้ว ให้ตรวจสอบและย้ายเองอีกครั้ง'''",
'movedto'                      => 'เปลี่ยนชื่อเป็น',
'movetalk'                     => 'เปลี่ยนชื่อหน้าพูดคุยพร้อมกัน',
'move-subpages'                => 'ย้ายหน้าย่อยทั้งหมด (มากถึง $1 หน้า)',
'move-talk-subpages'           => 'ย้ายหน้าย่อยทั้งหมดของหน้าอภิปราย (มากถึง $1 หน้า)',
'movepage-page-exists'         => 'หน้า $1 มีอยู่แล้วและไม่สามารถเขียนทับได้โดยอัตโนมัติ',
'movepage-page-moved'          => 'หน้า $1 ถูกเปลี่ยนชื่อเป็น $2',
'movepage-page-unmoved'        => 'หน้า $1 ไม่สามารถเปลี่ยนชื่อเป็น $2 ได้',
'movepage-max-pages'           => 'หน้าทั้งหมด $1 {{PLURAL:$1|หน้า|หน้า}} ถูกย้ายไป ซึ่งนับได้ว่าเป็นจำนวนที่มากที่สุดเท่าที่จะทได้ และหยุดการย้ายหน้าอย่างอัตโนมัติแล้ว',
'1movedto2'                    => '[[$1]] ถูกเปลี่ยนชื่อเป็น [[$2]]',
'1movedto2_redir'              => '[[$1]] ถูกเปลี่ยนชื่อเป็น [[$2]] ทับหน้าเปลี่ยนทาง',
'move-redirect-suppressed'     => 'หน้าเปลี่ยนทางไม่ถูกสร้าง',
'movelogpage'                  => 'บันทึกการเปลี่ยนชื่อ',
'movelogpagetext'              => 'ด้านล่างแสดงรายการ การเปลี่ยนชื่อ',
'movesubpage'                  => '{{PLURAL:$1|หน้าย่อย|หน้าย่อย}}',
'movesubpagetext'              => 'หน้านี้มีหน้าย่อย $1 หน้า ดังแสดงด้านล่าง',
'movenosubpage'                => 'หน้านี้ไม่มีหน้าย่อย',
'movereason'                   => 'เหตุผล:',
'revertmove'                   => 'ย้อน',
'delete_and_move'              => 'ลบและย้าย',
'delete_and_move_text'         => '== จำเป็นต้องลบ ==

ชื่อหัวข้อที่ต้องการ "[[:$1]]" มีอยู่แล้ว แน่ใจหรือไม่ว่าต้องการลบเพื่อที่จะให้การเปลี่ยนชื่อสำเร็จ',
'delete_and_move_confirm'      => 'ยืนยัน ต้องการลบ',
'delete_and_move_reason'       => 'ถูกลบสำหรับการเปลี่ยนชื่อ',
'selfmove'                     => 'ชื่อหน้าเดิมและหน้าใหม่เป็นชื่อเดียวกัน ไม่สามารถเปลี่ยนชื่อได้',
'immobile-source-namespace'    => 'ไม่สามารถเปลี่ยนชื่อหน้าในเนมสเปซ "$1"',
'immobile-target-namespace'    => 'ไม่สามารถย้ายหน้าไปยังเนมสเปซ "$1" ได้',
'immobile-target-namespace-iw' => 'ไม่สามารถย้ายไปยังหน้าปลายทางที่เป็นลิงก์ interwiki ได้',
'immobile-source-page'         => 'หน้านี้ไม่สามารถเปลี่ยนชื่อได้',
'immobile-target-page'         => 'ไม่สามารถเปลี่ยนไปยังชื่อที่ต้องการได้',
'imagenocrossnamespace'        => 'ไม่สามารถย้ายไฟล์ไปยังเนมสเปซที่ไม่รองรับ',
'imagetypemismatch'            => 'นามสกุลของไฟล์ใหม่ไม่ตรงกับชนิดของไฟล์',
'imageinvalidfilename'         => 'ชื่อไฟล์เป้าหมายไม่ถูกต้อง',
'fix-double-redirects'         => 'อัปเดตหน้าเปลี่ยนทางทุกหน้าที่โอนไปยังชื่อเดิม',
'move-leave-redirect'          => 'สร้างหน้าเปลี่ยนทางตามมา',
'protectedpagemovewarning'     => "'''คำเตือน:''' หน้านี้ถูกล็อก ดังนั้นผู้ใช้ที่มีสิทธิ์ดูแลระบบเท่านั้นที่สามารถย้ายได้",
'semiprotectedpagemovewarning' => "'''หมายเหตุ:''' หน้านี้ถูกล็อก ดังนั้นผู้ใช้ที่ลงทะเบียนแล้วเท่านั้นที่จะสามารถย้ายได้",
'move-over-sharedrepo'         => '== มีไฟล์เดิมปรากฎ ==
ไฟล์ [[:$1]] มีปรากฎเดิมอยู่แล้วในคลังเก็บภาพส่วนกลาง การย้ายไฟล์ที่มีชื่อเรื่องนี้อาจจะเป็นการเขียนทับไฟล์เดิมในคลับเก็บได้',
'file-exists-sharedrepo'       => 'ชื่อไฟล์นี้มีปรากฎเดิมอยู่แล้วในคลังเก็บภาพส่วนกลาง
กรุณาเลือกชื่ออื่น',

# Export
'export'            => 'ส่งออกหน้า',
'exporttext'        => 'คุณสามารถส่งออก (export) ข้อความต้นฉบับและประวัติการแก้ไขของหน้าใดๆ มากกว่าหนึ่งหน้าในคราวเดียว ออกมาในรูปแบบ XML ซึ่งสามารถนำไปใส่เข้าไว้ในเว็บไซต์วิกิแห่งอื่นที่ใช้ซอฟต์แวร์มีเดียวิกิได้ ผ่านทางคำสั่ง[[Special:Import|การนำเข้าหน้า]]

การจะส่งออกหน้านั้นสามารถทำได้โดยใส่ชื่อหัวเรื่องของหน้าที่ต้องการ ลงในกล่องข้อความด้านล่าง หนึ่งชื่อต่อหนึ่งบรรทัด จากนั้นเลือกว่าต้องการทั้งรุ่นปัจจุบันและรุ่นเก่าๆ ทั้งหมดพร้อมกับประวัติของหน้านั้น หรือต้องการเพียงแต่เนื้อหารุ่นปัจจุบันพร้อมกับรายละเอียดของรุ่นนั้นเท่านั้น

ในกรณีที่ต้องการเฉพาะรุ่นปัจจุบัน คุณสามารถใช้ในรูปแบบของลิงก์ เช่น [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] สำหรับหน้า "[[{{MediaWiki:Mainpage}}]]"',
'exportcuronly'     => 'เอาเฉพาะรุ่นปัจจุบันเท่านั้น ไม่เอาประวัติทั้งหมด',
'exportnohistory'   => "----
'''หมายเหตุ:''' การส่งออกประวัติหน้าทั้งหมดผ่านช่องทางนี้ได้ถูกปิดไว้ เนื่องจากปัญหาทางเทคนิคในด้านประสิทธิภาพ",
'export-submit'     => 'ส่งออกมา',
'export-addcattext' => 'รวมหน้าจากหมวดหมู่:',
'export-addcat'     => 'เพิ่ม',
'export-addnstext'  => 'เพิ่มหน้าจากเนมสเปซ:',
'export-addns'      => 'เพิ่ม',
'export-download'   => 'ส่งออกมาเป็นไฟล์',
'export-templates'  => 'รวมแม่แบบมาด้วย',
'export-pagelinks'  => 'จำนวนลำดับของหน้าที่ถูกเชื่อมโยงทั้งหมด:',

# Namespace 8 related
'allmessages'                   => 'ข้อความของระบบ',
'allmessagesname'               => 'ชื่อ',
'allmessagesdefault'            => 'ข้อความตั้งต้น',
'allmessagescurrent'            => 'ข้อความปัจจุบัน',
'allmessagestext'               => 'รายการข้อความของระบบ อยู่ในเนมสเปซมีเดียวิกิ
กรุณาไปที่ [http://www.mediawiki.org/wiki/Localisation มีเดียวิกิ] และ [http://translatewiki.new translatewiki.net] ถ้าคุณยังอยากที่จะแปลข้อความของระบบมีเดียวิกิ',
'allmessagesnotsupportedDB'     => "หน้านี้ไม่สามารถใช้งานได้เนื่องจาก '''\$wgUseDatabaseMessages''' ถูกระงับการใช้งาน",
'allmessages-filter-legend'     => 'กรอง',
'allmessages-filter'            => 'กรองตามสถานะที่เลือก:',
'allmessages-filter-unmodified' => 'มีการแก้ไข',
'allmessages-filter-all'        => 'ทั้งหมด',
'allmessages-filter-modified'   => 'ไม่มีการแก้ไข',
'allmessages-prefix'            => 'กรองด้วยคำข้างหน้า:',
'allmessages-language'          => 'ภาษา:',
'allmessages-filter-submit'     => 'ไป',

# Thumbnails
'thumbnail-more'           => 'ขยาย',
'filemissing'              => 'ไม่เจอไฟล์',
'thumbnail_error'          => 'เกิดปัญหาไม่สามารถทำรูปย่อได้: $1',
'djvu_page_error'          => 'หน้าเดจาวู (DjVu) เกินขนาด',
'djvu_no_xml'              => 'ไม่สามารถส่งเอกซ์เอ็มแอล (XML) สำหรับไฟล์เดจาวู (DjVu)',
'thumbnail_invalid_params' => 'พารามิเตอร์ของธัมบ์เนลไม่ถูกต้อง',
'thumbnail_dest_directory' => 'ไม่สามารถสร้างไดเรกทอรีภาพได้',
'thumbnail_image-type'     => 'ไม่รองรับรูปแบบของไฟล์รูปภาพนี้',
'thumbnail_gd-library'     => 'การตั้งค่าไลบรารี GD ไม่สมบูรณ์: ไม่พบฟังก์ชัน $1',
'thumbnail_image-missing'  => 'ดูเหมือนว่าไฟล์จะหายไป: $1',

# Special:Import
'import'                     => 'หน้านำเข้า',
'importinterwiki'            => 'นำเข้าข้ามวิกิ',
'import-interwiki-text'      => 'เลือกวิกิและชื่อหัวข้อที่ต้องการนำเข้า วันที่และชื่อผู้แก้ไขทั้งหมดจะถูกเก็บไว้ โดยการนำเข้าทุกส่วนจะถูกเก็บไว้ใน [[Special:Log/import|บันทึกการนำเข้า]]',
'import-interwiki-source'    => 'หน้า/วิกิ ต้นฉบับ:',
'import-interwiki-history'   => 'คัดลอกประวัติทั้งหมดในหน้านี้',
'import-interwiki-templates' => 'รวมแม่แบบทั้งหมด',
'import-interwiki-submit'    => 'นำเข้า',
'import-interwiki-namespace' => 'เนมสเปซปลายทาง:',
'import-upload-filename'     => 'ชื่อไฟล์:',
'import-comment'             => 'ความเห็น:',
'importtext'                 => 'กรุณาส่งออกไฟล์จากวิกิอื่นโดยใช้[[Special:Export|เครื่องมือส่งออก]] บันทึก และทำการอัปโหลดมาที่นี่',
'importstart'                => 'กำลังนำเข้าหน้า...',
'import-revision-count'      => '$1 {{PLURAL:$1|รุ่นการแก้ไข|รุ่นการแก้ไข}}',
'importnopages'              => 'ไม่มีหน้าให้นำเข้า',
'importfailed'               => 'การนำเข้าไม่สำเร็จ: <nowiki>$1</nowiki>',
'importunknownsource'        => 'ไม่ทราบชนิดของไฟล์นำเข้า',
'importcantopen'             => 'ไม่สามารถเปิดไฟล์นำเข้าได้',
'importbadinterwiki'         => 'ลิงก์เชื่อมโยงข้ามภาษาเสีย',
'importnotext'               => 'ไฟล์ว่างหรือไฟล์ไม่มีข้อความ',
'importsuccess'              => 'นำเข้าไฟล์สำเร็จ!',
'importhistoryconflict'      => 'ประวัติหน้าขัดแย้งกัน (ซึ่งอาจเคยนำเข้าหน้านี้มาก่อน)',
'importnosources'            => 'ไม่มีการกำหนดแหล่งนำเข้าข้ามวิกิ และการอัปโหลดประวัติหน้าโดยตรงถูกปิดการใช้งาน',
'importnofile'               => 'ไฟล์นำเข้าไม่ได้ถูกอัปโหลด',
'importuploaderrorsize'      => 'อัปโหลดไฟล์ข้อมูลนำเข้าไม่สำเร็จ
ขนาดไฟล์ใหญ่เกินกว่าที่อนุญาตไว้',
'importuploaderrorpartial'   => 'อัปโหลดไฟล์ข้อมูลนำเข้าไม่สำเร็จ
ไฟล์ได้รับการอัปโหลดขึ้นไปเพียงบางส่วนเท่านั้น',
'importuploaderrortemp'      => 'อัปโหลดไฟล์ข้อมูลนำเข้าไม่สำเร็จ
ไม่พบโฟลเดอร์ชั่วคราว',
'import-parse-failure'       => 'มีข้อผิดพลาดในการแปลโครงสร้างของข้อมูลนำเข้า XML',
'import-noarticle'           => 'ไม่มีข้อมูลหน้าให้นำเข้า!',
'import-nonewrevisions'      => 'ทุกรุ่นมาจากการนำเข้าข้อมูลก่อนหน้านี้',
'xml-error-string'           => '$1 ที่บรรทัด $2 คอลัมน์ $3 (ไบต์ที่ $4): $5',
'import-upload'              => 'อัปโหลดข้อมูล XML',
'import-token-mismatch'      => 'ข้อมูลเซชชันสูญหาย ให้ลองใหม่อีกครั้ง',
'import-invalid-interwiki'   => 'ไม่สามารถนำข้อมูลเข้าจากวิกิที่กำหนดได้',

# Import log
'importlogpage'                    => 'บันทึกการนำเข้า',
'importlogpagetext'                => 'นำเข้าไฟล์จากวิกิอื่น โดยผ่านทางผู้ดูแลระบบ',
'import-logentry-upload'           => 'นำเข้า [[$1]] ผ่านการอัปโหลดแล้ว',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|รุ่นการแก้ไข|รุ่นการแก้ไข}}',
'import-logentry-interwiki'        => 'นำเข้าข้ามวิกิ $1 แล้ว',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|รุ่นการแก้ไข|รุ่นการแก้ไข}}จาก $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'หน้าผู้ใช้ของคุณ',
'tooltip-pt-anonuserpage'         => 'หน้าผู้ใช้ของหมายเลขไอพีที่แก้ไข',
'tooltip-pt-mytalk'               => 'หน้าอภิปรายของคุณ',
'tooltip-pt-anontalk'             => 'พูดคุยเกี่ยวกับการแก้ไขจากหมายเลขไอพี',
'tooltip-pt-preferences'          => 'ตั้งค่าการใช้งานส่วนตัว',
'tooltip-pt-watchlist'            => 'รายการที่เฝ้าดูการแก้ไข',
'tooltip-pt-mycontris'            => 'รายการหน้าที่ได้มีส่วนร่วม',
'tooltip-pt-login'                => 'ไม่จำเป็นต้องล็อกอินในการแก้ไข แต่แนะนำอย่างยิ่งให้ล็อกอิน',
'tooltip-pt-anonlogin'            => 'ไม่จำเป็นต้องล็อกอินในการแก้ไข แต่แนะนำอย่างยิ่งให้ล็อกอิน',
'tooltip-pt-logout'               => 'ล็อกเอาต์',
'tooltip-ca-talk'                 => 'พูคุยเกี่ยวกับเนื้อหา',
'tooltip-ca-edit'                 => 'หน้านี้แก้ไขได้ ก่อนทำการบันทึกให้กรุณากดปุ่มดูตัวอย่างก่อน แน่ใจว่าได้ตามที่ต้องการ',
'tooltip-ca-addsection'           => 'เริ่มหัวข้อย่อยใหม่',
'tooltip-ca-viewsource'           => 'หน้านี้ถูกล็อก แต่ยังคงดูโค้ดได้',
'tooltip-ca-history'              => 'รุ่นที่แล้วของหน้านี้',
'tooltip-ca-protect'              => 'ล็อกหน้านี้',
'tooltip-ca-unprotect'            => 'ยกเลิกการป้องกันหน้านี้',
'tooltip-ca-delete'               => 'ลบหน้านี้',
'tooltip-ca-undelete'             => 'เรียกคืนการแก้ไขหน้านี้กลับมาเป็นรุ่นก่อนที่ถูกลบ',
'tooltip-ca-move'                 => 'เปลี่ยนชื่อหน้านี้',
'tooltip-ca-watch'                => 'เพิ่มหน้านี้เข้ารายการเฝ้าดู',
'tooltip-ca-unwatch'              => 'นำหน้านี้ออกจากรายการเฝ้าดู',
'tooltip-search'                  => 'ค้นหา {{SITENAME}}',
'tooltip-search-go'               => 'ตรงไปยังหน้าที่ตรงกับชื่อนี้ (ถ้ามี)',
'tooltip-search-fulltext'         => 'ค้นหาหน้าที่มีข้อความนี้',
'tooltip-p-logo'                  => 'หน้าหลัก',
'tooltip-n-mainpage'              => 'แวะหน้าหลัก',
'tooltip-n-mainpage-description'  => 'เข้าสู่หน้าหลัก',
'tooltip-n-portal'                => 'เรื่องเฉพาะโครงการ วิธีการใช้ วิธีการค้นหา สิ่งที่ควรทำ',
'tooltip-n-currentevents'         => 'ค้นหาเหตุการณ์ปัจจุบัน',
'tooltip-n-recentchanges'         => 'รายการปรับปรุงล่าสุดในวิกินี้',
'tooltip-n-randompage'            => 'สุ่มหน้าขึ้นมา',
'tooltip-n-help'                  => 'อธิบายการใช้งาน',
'tooltip-t-whatlinkshere'         => 'รายการหน้าวิกิที่ลิงก์มาที่นี่',
'tooltip-t-recentchangeslinked'   => 'รายการหน้าที่ลิงก์มาที่นี่ และเพิ่งถูกแก้ไข',
'tooltip-feed-rss'                => 'ฟีดชนิดอาร์เอสเอส (RSS) ของหน้านี้',
'tooltip-feed-atom'               => 'ฟีดชนิดอะตอม (Atom) ของหน้านี้',
'tooltip-t-contributions'         => 'ดูหน้าที่ผู้ใช้คนนี้เขียน',
'tooltip-t-emailuser'             => 'ส่งอีเมลหาคนนี้',
'tooltip-t-upload'                => 'อัปโหลดภาพหรือไฟล์',
'tooltip-t-specialpages'          => 'แสดงรายการหน้าพิเศษ',
'tooltip-t-print'                 => 'หน้าที่แสดงผลพร้อมสำหรับพิมพ์ออกมา',
'tooltip-t-permalink'             => 'ลิงก์มาที่เฉพาะรุ่นนี้ในหน้านี้',
'tooltip-ca-nstab-main'           => 'ดูหน้าเนื้อหา',
'tooltip-ca-nstab-user'           => 'ดูหน้าผู้ใช้',
'tooltip-ca-nstab-media'          => 'ดูหน้าสื่อ ภาพ เพลง',
'tooltip-ca-nstab-special'        => 'ไม่สามารถแก้ไขหน้านี้ได้ หน้านี้เป็นหน้าพิเศษ',
'tooltip-ca-nstab-project'        => 'ดูหน้าโครงการ',
'tooltip-ca-nstab-image'          => 'ดูหน้าภาพ',
'tooltip-ca-nstab-mediawiki'      => 'ดูข้อความระบบ',
'tooltip-ca-nstab-template'       => 'ดูหน้าแม่แบบ',
'tooltip-ca-nstab-help'           => 'ดูหน้าคำอธิบาย',
'tooltip-ca-nstab-category'       => 'ดูหน้าหมวดหมู่',
'tooltip-minoredit'               => 'กำหนดเป็นการแก้ไขเล็กน้อย',
'tooltip-save'                    => 'บันทึกการแก้ไข',
'tooltip-preview'                 => 'แสดงตัวอย่างการเปลี่ยนแปลงที่เกิดขึ้น กรุณาใช้คำสั่งนี้ก่อนทำการบันทึก!',
'tooltip-diff'                    => 'แสดงการเปลี่ยนการต่อข้อความ',
'tooltip-compareselectedversions' => 'แสดงความแตกต่างของรุ่นสองรุ่นที่เลือก',
'tooltip-watch'                   => 'เพิ่มหน้านี้เข้ารายการเฝ้าดู',
'tooltip-recreate'                => 'สร้างหน้านี้อีกครั้งแม้ว่าจะถูกลบ',
'tooltip-upload'                  => 'เริ่มอัปโหลด',
'tooltip-rollback'                => '"ถอยกลับฉุกเฉิน" ใช้ย้อนการแก้ไขของหน้านี้ไปยังรุ่นโดยผู้ใช้ก่อนหน้าในคลิกเดียว',
'tooltip-undo'                    => '"ย้อน" ใช้ย้อนการแก้ไขครั้งนี้และเปิดฟอร์มให้แก้ไข สามารถเพิ่มคำอธิบายในตอนท้าย',

# Stylesheets
'common.css'   => '/** CSS ที่อยู่ในหน้านี้จะมีผลต่อทุกสกินในเว็บไซต์ */',
'monobook.css' => '/* CSS ที่อยู่ในหน้านี้จะมีผลต่อสกิน Monobook */',

# Scripts
'common.js'   => '/* จาวาสคริปต์ในหน้านี้จะถูกใช้งานต่อผู้ใช้ทุกคน */',
'monobook.js' => '/* ถ้าไม่เห็นด้วย ให้ใช้ [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'ข้อมูลอาร์ดีเอฟของดับลินคอร์ไม่สามารถใช้งานได้ในเซิร์ฟเวอร์นี้',
'nocreativecommons' => 'ข้อมูลอาร์ดีเอฟของครีเอทีฟคอมมอนส์ไม่สามารถใช้งานได้ในเซิร์ฟเวอร์นี้',
'notacceptable'     => 'เซิร์ฟเวอร์ของวิกิไม่สามารถให้ข้อมูลในรูปแบบที่ไคลเอนต์สามารถอ่านได้',

# Attribution
'anonymous'        => '{{PLURAL:$1|ผู้ใช้}}นิรนามของ {{SITENAME}}',
'siteuser'         => 'ผู้ใช้ $1 จาก {{SITENAME}}',
'anonuser'         => 'ผู้ใช้นิรนามจาก {{SITENAME}} $1',
'lastmodifiedatby' => 'แก้ไขล่าสุดเมื่อเวลา $2 $1 โดย $3',
'othercontribs'    => 'พัฒนาจากงานเขียนของ $1',
'others'           => 'ผู้อื่น',
'siteusers'        => '{{PLURAL:$2|ผู้ใช้|ผู้ใช้}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|ผู้ใช้|บรรดาผู้ใช้}}นิรนามจาก {{SITENAME}} $1',
'creditspage'      => 'เกียรติแก่ผู้ร่วมสร้าง',
'nocredits'        => 'ไม่มีรายชื่อผู้เป็นเกียรติที่ร่วมสร้างหน้านี้',

# Spam protection
'spamprotectiontitle' => 'ตัวกรองป้องกันสแปม',
'spamprotectiontext'  => 'หน้าที่คุณต้องการบันทึกโดนบล็อกด้วยตัวกรองสแปม ซึ่งอาจเกิดจากมีลิงก์ไปยังเว็บไซต์ภายนอกที่อยู่ในบัญชีดำ',
'spamprotectionmatch' => 'ข้อความต่อไปนี้ได้ทำให้ตัวกรองสแปมของเราทำงาน: $1',
'spambot_username'    => 'กวาดล้างมีเดียวิกิสแปม',
'spam_reverting'      => 'ย้อนกลับไปรุ่นก่อนหน้าที่ไม่มีลิงก์ไปยังเว็บ $1',
'spam_blanking'       => 'รุ่นการปรับปรุงทุกรุ่นประกอบไปด้วยลิงก์ไปยังเว็บ $1 (ทำหน้าว่าง)',

# Info page
'infosubtitle'   => 'ข้อมูลของหน้า',
'numedits'       => 'จำนวนการแก้ไข (เนื้อหา): $1',
'numtalkedits'   => 'จำนวนการแก้ไข (หน้าพูดคุย): $1',
'numwatchers'    => 'จำนวนผู้เฝ้าดู: $1',
'numauthors'     => 'จำนวนผู้เขียน (เนื้อหา): $1',
'numtalkauthors' => 'จำนวนผู้เขียน (หน้าพูดคุย): $1',

# Skin names
'skinname-standard'    => 'คลาสสิก',
'skinname-nostalgia'   => 'นอสตัลเจีย',
'skinname-cologneblue' => 'โคโลญจ์บลู',
'skinname-monobook'    => 'โมโนบุ๊ก',
'skinname-myskin'      => 'มายสกิน',
'skinname-chick'       => 'ชิก',
'skinname-simple'      => 'ซิมเปิล',
'skinname-modern'      => 'โมเดิร์น',

# Math options
'mw_math_png'    => 'เรนเดอร์เป็น PNG เสมอ',
'mw_math_simple' => 'ใช้พื้นฐานเป็น HTML ถ้าไม่ได้ใช้ PNG',
'mw_math_html'   => 'ถ้าเป็นไปได้ใช้เป็น HTML ถ้าไม่ได้ใช้ PNG',
'mw_math_source' => 'ปล่อยข้อมูลเป็น TeX (สำหรับเว็บเบราว์เซอร์แบบข้อความ)',
'mw_math_modern' => 'แนะนำสำหรับเว็บเบราว์เซอร์สมัยใหม่',
'mw_math_mathml' => 'ถ้าเป็นไปได้ใช้ MathML (ขั้นทดลอง)',

# Math errors
'math_failure'          => 'ส่งผ่านค่าไม่ได้',
'math_unknown_error'    => 'ข้อผิดพลาดที่ไม่ทราบ',
'math_unknown_function' => 'คำสั่งที่ไม่ทราบ',
'math_lexing_error'     => 'การจำแนกสูตรผิดพลาด',
'math_syntax_error'     => 'ไวยากรณ์ผิดพลาด',
'math_image_error'      => 'การแปลงเป็นไฟล์ PNG ขัดข้อง กรุณาตรวจสอบการติดตั้ง LaTex, dvips, gs, และ convert',
'math_bad_tmpdir'       => 'ไม่สามารถเขียนค่าหรือสร้าง ลงไดเรกทอรีชั่วคราวสำหรับเก็บค่าทางคณิตศาสตร์ได้',
'math_bad_output'       => 'ไม่สามารถเขียนค่าหรือสร้าง ลงไดเรกทอรีปลายทางสำหรับเก็บค่าทางคณิตศาสตร์ได้',
'math_notexvc'          => 'เกิดข้อความผิดพลาด texvc ไม่พบ กรุณาตรวจสอบ math/README เพื่อตั้งค่า',

# Patrolling
'markaspatrolleddiff'                 => 'ทำเครื่องหมายว่าตรวจสอบแล้ว',
'markaspatrolledtext'                 => 'กำหนดว่าบทความนี้ถูกตรวจสอบแล้ว',
'markedaspatrolled'                   => 'ตรวจสอบแล้ว',
'markedaspatrolledtext'               => 'รุ่นการแก้ไขที่เลือกถูกกำหนดว่าตรวจสอบแล้ว',
'rcpatroldisabled'                    => 'การตรวจสอบหน้าปรับปรุงล่าสุดปิดใช้งาน',
'rcpatroldisabledtext'                => 'ฟังก์ชันการตรวจสอบหน้าปรับปรุงล่าสุดขณะนี้ไม่เปิดการใช้งาน',
'markedaspatrollederror'              => 'ไม่สามารถทำเครื่องหมายว่าตรวจสอบแล้ว',
'markedaspatrollederrortext'          => 'คุณจำเป็นต้องระบุรุ่นการแก้ไขที่กำหนดว่าตรวจสอบแล้ว',
'markedaspatrollederror-noautopatrol' => 'คุณไม่สามารถทำเครื่องหมายการแก้ไขของคุณเองว่าตรวจสอบแล้ว',

# Patrol log
'patrol-log-page'      => 'บันทึกการตรวจสอบ',
'patrol-log-header'    => 'หน้านี้คือบันทึกรุ่นการแก้ไขที่กำหนดว่าตรวจสอบแล้ว',
'patrol-log-line'      => 'ทำเครื่องหมาย $1 ของ $2 ว่าถูกตรวจสอบ $3 แล้ว',
'patrol-log-auto'      => '(อัตโนมัติ)',
'patrol-log-diff'      => 'รุ่น $1',
'log-show-hide-patrol' => '$1 บันทึกการตรวจตรา',

# Image deletion
'deletedrevision'                 => 'รุ่นเก่าที่ถูกลบ $1',
'filedeleteerror-short'           => 'เกิดปัญหาการลบไฟล์: $1',
'filedeleteerror-long'            => 'เกิดปัญหาขณะที่ทำการลบไฟล์:

$1',
'filedelete-missing'              => 'ไม่สามารถลบไฟล์ "$1" ได้ เนื่องจากไม่มีไฟล์ชื่อนี้อยู่',
'filedelete-old-unregistered'     => 'ไฟล์ที่ระบุรุ่น "$1" ไม่มีในฐานข้อมูล',
'filedelete-current-unregistered' => 'ไฟล์ที่ระบุ "$1" ไม่มีในฐานข้อมูล',
'filedelete-archive-read-only'    => 'ไดเรกทอรีกรุชื่อ "$1" ไม่สามารถเขียนลงได้โดยเว็บเซิร์ฟเวอร์',

# Browsing diffs
'previousdiff' => '← แตกต่างก่อนหน้า',
'nextdiff'     => 'แตกต่างถัดไป →',

# Media information
'mediawarning'         => "'''คำเตือน''': ไฟล์รูปแบบนี้อาจมีโค้ดที่ไม่พึงประสงค์
ระบบของท่านอาจเสียหายอันเนื่องจากโค้ดทำงาน",
'imagemaxsize'         => "ขนาดภาพที่จำกัด:<br />''(สำหรับหน้าอธิบายภาพ)''",
'thumbsize'            => 'ขนาดรูปย่อ:',
'widthheightpage'      => '{{PLURAL:$3|หน้า|หน้า}} $1×$2, $3',
'file-info'            => '(ขนาดไฟล์: $1, ชนิดไมม์: $2)',
'file-info-size'       => '($1 × $2 พิกเซล, ขนาดไฟล์: $3, ชนิดไมม์: $4)',
'file-nohires'         => '<small>ไม่มีภาพความละเอียดสูงกว่านี้</small>',
'svg-long-desc'        => '(ไฟล์ SVG, $1 × $2 พิกเซล (พอเป็นพิธี), ขนาดไฟล์: $3)',
'show-big-image'       => 'ความละเอียดสูงสุด',
'show-big-image-thumb' => '<small>ขนาดของภาพแสดงผล: $1 × $2 พิกเซล</small>',
'file-info-gif-looped' => 'วนซ้ำ',
'file-info-gif-frames' => '$1 {{PLURAL:$1|เฟรม|เฟรม}}',

# Special:NewFiles
'newimages'             => 'แกลลอรีภาพใหม่',
'imagelisttext'         => "รายชื่อไฟล์ '''$1''' รายการ เรียงตาม$2",
'newimages-summary'     => 'หน้าพิเศษนี้แสดงไฟล์ที่ถูกอัปโหลดล่าสุด',
'newimages-legend'      => 'ตัวกรอง',
'newimages-label'       => 'ชื่อไฟล์ (หรือส่วนหนึ่งของชื่อ):',
'showhidebots'          => '($1 บอต)',
'noimages'              => 'ไม่มีให้ดู',
'ilsubmit'              => 'สืบค้น',
'bydate'                => 'วันที่',
'sp-newimages-showfrom' => 'แสดงภาพใหม่เริ่มต้นจาก $2, $1',

# Bad image list
'bad_image_list' => 'รูปแบบแสดงต่อไปนี้:

เฉพาะรายการที่แสดง (ในแถวขึ้นต้นด้วย *) โดยลิงก์แรกของแต่ละแถวเป็นลิงก์ไปยังภาพที่เสีย
โดยลิงก์ถัดไปเป็นข้อยกเว้น เช่น บทความที่ภาพถูกจัดในบรรทัดเดียวกับส่วนข้อความ',

# Metadata
'metadata'          => 'ข้อมูลแนบ',
'metadata-help'     => 'ไฟล์นี้มีข้อมูลเพิ่มเติมแนบไว้ อาจจะมาจาก กล้องดิจิทัล สแกนเนอร์ หรือเครื่องรับส่งจีพีเอส อย่างไรก็ตามข้อมูลที่เก็บไว้อาจถูกดัดแปลงถ้าไฟล์ต้นฉบับถูกแก้ไขจากซอฟต์แวร์อื่น',
'metadata-expand'   => 'แสดงข้อมูลเพิ่มเติม',
'metadata-collapse' => 'ซ่อนข้อมูลเพิ่มเติม',
'metadata-fields'   => 'ค่าเอกซิฟ (Exif) ของภาพด้านล่างจะแสดงควบคู่ไปกับภาพ
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'ความกว้าง',
'exif-imagelength'                 => 'ความสูง',
'exif-bitspersample'               => 'บิต ต่อคอมโพเนนต์',
'exif-compression'                 => 'รูปแบบการบีบอัด',
'exif-photometricinterpretation'   => 'พิกเซลคอมโพซิชัน',
'exif-orientation'                 => 'การจัดวางภาพ',
'exif-samplesperpixel'             => 'จำนวนคอมโพเนนต์',
'exif-planarconfiguration'         => 'การจัดเรียงข้อมูล',
'exif-ycbcrsubsampling'            => 'อัตราซับแซมปริง ของ Y และ C',
'exif-ycbcrpositioning'            => 'ตำแหน่ง Y และ C',
'exif-xresolution'                 => 'ความละเอียดแนวนอน',
'exif-yresolution'                 => 'ความละเอียดแนวตั้ง',
'exif-resolutionunit'              => 'หน่วยของความละเอียดของ X และ Y',
'exif-stripoffsets'                => 'ตำแหน่งข้อมูลภาพ',
'exif-rowsperstrip'                => 'จำนวนแถวต่อสตริป',
'exif-stripbytecounts'             => 'ไบต์ต่อสตริป',
'exif-jpeginterchangeformat'       => 'ออฟเซตไปที่ JPEG SOI',
'exif-jpeginterchangeformatlength' => 'ไบต์ของข้อมูล JPEG',
'exif-transferfunction'            => 'ฟังก์ชันทรานส์เฟอร์',
'exif-whitepoint'                  => 'ไวต์พอยต์โครมาติก',
'exif-primarychromaticities'       => 'โครมาติกของไพรมาริตี',
'exif-ycbcrcoefficients'           => 'สัมประสิทธิเมทริกซ์การเปลียนแปลงของสเปซสี',
'exif-referenceblackwhite'         => 'คู่จุดสีขาวและดำสำหรับอ้างอิง',
'exif-datetime'                    => 'วันที่และเวลาปรับปรุง',
'exif-imagedescription'            => 'ชื่อภาพ',
'exif-make'                        => 'ผู้ผลิตกล้อง',
'exif-model'                       => 'รุ่นกล้อง',
'exif-software'                    => 'ซอฟต์แวร์ที่ใช้',
'exif-artist'                      => 'ผู้สร้างสรรค์',
'exif-copyright'                   => 'ผู้ถือลิขสิทธิ์',
'exif-exifversion'                 => 'รุ่นเอกซิฟ (Exif)',
'exif-flashpixversion'             => 'รุ่นแฟลชพิกซ์ที่รองรับ',
'exif-colorspace'                  => 'สเปซสี',
'exif-componentsconfiguration'     => 'ความหมายของแต่ละคอมโพเนนต์',
'exif-compressedbitsperpixel'      => 'โหมดการบีบอัดภาพ',
'exif-pixelydimension'             => 'ความกว้างที่นำไปใช้ได้',
'exif-pixelxdimension'             => 'ความยาวที่นำไปใช้ได้',
'exif-makernote'                   => 'ข้อมูลเพิ่มจากผู้ผลิตกล้อง',
'exif-usercomment'                 => 'ความเห็นผู้ใช้',
'exif-relatedsoundfile'            => 'ไฟล์เสียงที่เกี่ยวข้อง',
'exif-datetimeoriginal'            => 'วันที่และเวลาที่สร้าง',
'exif-datetimedigitized'           => 'วันที่และเวลาที่ดิจิไทซ์',
'exif-subsectime'                  => 'เสี้ยววินาที วันที่ เวลา',
'exif-subsectimeoriginal'          => 'เสี้ยววินาที วันที่ เวลาต้นฉบับ',
'exif-subsectimedigitized'         => 'เสี้ยววินาที วันที่ เวลาที่ดิจิไทซ์',
'exif-exposuretime'                => 'เวลารับแสง',
'exif-exposuretime-format'         => '$1 วินาที ($2)',
'exif-fnumber'                     => 'ค่าเอฟ',
'exif-exposureprogram'             => 'โปรแกรมเอกซ์โพเชอร์',
'exif-spectralsensitivity'         => 'ความไวสเปกตรัม',
'exif-isospeedratings'             => 'อัตราความเร็ว ISO',
'exif-oecf'                        => 'อัตราเปลี่ยนออปโตอิเล็กทรอนิก',
'exif-shutterspeedvalue'           => 'ความไวชัตเตอร์',
'exif-aperturevalue'               => 'รูรับแสง',
'exif-brightnessvalue'             => 'ความสว่าง',
'exif-exposurebiasvalue'           => 'เอกซ์โพเชอร์ไบแอส',
'exif-maxaperturevalue'            => 'รูรับแสงกว้างสุด',
'exif-subjectdistance'             => 'ระยะวัตถุ',
'exif-meteringmode'                => 'โหมดมิเตอริง',
'exif-lightsource'                 => 'แสง',
'exif-flash'                       => 'แฟลช',
'exif-focallength'                 => 'ระยะโฟกัส',
'exif-subjectarea'                 => 'จุดวัตถุ',
'exif-flashenergy'                 => 'พลังงานแฟลช',
'exif-spatialfrequencyresponse'    => 'การตอบสนองความถี่ของสเปซ',
'exif-focalplanexresolution'       => 'ความละเอียระนาบโฟกัส X',
'exif-focalplaneyresolution'       => 'ความละเอียระนาบโฟกัส Y',
'exif-focalplaneresolutionunit'    => 'หน่วยความละเอียดระนาบโฟกัส',
'exif-subjectlocation'             => 'ตำแหน่งวัตถุ',
'exif-exposureindex'               => 'ดัชนีเอกซ์โพเชอร์',
'exif-sensingmethod'               => 'วิถีการวัด',
'exif-filesource'                  => 'ต้นฉบับไฟล์',
'exif-scenetype'                   => 'ชนิดซีน',
'exif-cfapattern'                  => 'รูปแบบ CFA',
'exif-customrendered'              => 'การประมวณภาพ',
'exif-exposuremode'                => 'โหมดเอกซ์โพเชอร์',
'exif-whitebalance'                => 'ไวต์บาลานซ์',
'exif-digitalzoomratio'            => 'อัตราซูมดิจิทัล',
'exif-focallengthin35mmfilm'       => 'ระยะโฟกัสในฟิล์ม 35 มม.',
'exif-scenecapturetype'            => 'ชนิดซีนแคปเจอร์',
'exif-gaincontrol'                 => 'ซีนคอนโทรล',
'exif-contrast'                    => 'ความเปรีบบต่าง',
'exif-saturation'                  => 'ความอิ่มสี',
'exif-sharpness'                   => 'ความคม',
'exif-devicesettingdescription'    => 'รายละเอียดการตั้งค่าอุปกรณ์',
'exif-subjectdistancerange'        => 'ระยะห่างวัตถุ',
'exif-imageuniqueid'               => 'รหัสภาพ',
'exif-gpsversionid'                => 'รุ่นจีพีเอสแท็ก',
'exif-gpslatituderef'              => 'ละติจูดเหนือหรือใต้',
'exif-gpslatitude'                 => 'ละติจูด',
'exif-gpslongituderef'             => 'ลองจิจูดตะวันออกหรือตะวันตก',
'exif-gpslongitude'                => 'ลองจิจูด',
'exif-gpsaltituderef'              => 'ระดับความสูงอ้างอิง',
'exif-gpsaltitude'                 => 'ระดับความสูงจากน้ำทะเล',
'exif-gpstimestamp'                => 'เวลาจีพีเอส (นาฬิกาอะตอม)',
'exif-gpssatellites'               => 'จำนวนดาวเทียมที่ใช้วัดค่า',
'exif-gpsstatus'                   => 'สถานีเครื่องรับสัญญาณ',
'exif-gpsmeasuremode'              => 'โหมดการวัดค่า',
'exif-gpsdop'                      => 'ความละเอียดการวัดค่า',
'exif-gpsspeedref'                 => 'หน่วยความเร็ว',
'exif-gpsspeed'                    => 'ความเร็วของเครื่องรับสัญญาณจีพีเอส',
'exif-gpstrackref'                 => 'จุดอ้างอิงของทิศทางการเคลื่อนไหว',
'exif-gpstrack'                    => 'ทิศทางการเคลื่อนไหว',
'exif-gpsimgdirectionref'          => 'จุดอ้างอิงของทิศทางภาพ',
'exif-gpsimgdirection'             => 'ทิศทางของภาพ',
'exif-gpsmapdatum'                 => 'ข้อมูลการสำรวจจีโอเดติกที่ถูกใช้',
'exif-gpsdestlatituderef'          => 'จุดอ้างอิงของละติจูดเป้าหมาย',
'exif-gpsdestlatitude'             => 'ละติจูดเป้าหมาย',
'exif-gpsdestlongituderef'         => 'จุดอ้างอิงของลองจิจูดเป้าหมาย',
'exif-gpsdestlongitude'            => 'ลองจิจูดเป้าหมาย',
'exif-gpsdestbearingref'           => 'จุดอ้างอิงของทิศทางเป้าหมาย',
'exif-gpsdestbearing'              => 'ทิศทางของเป้าหมาย',
'exif-gpsdestdistanceref'          => 'จุดอ้างอิงของระยะทางเป้าหมาย',
'exif-gpsdestdistance'             => 'ระยะทางของเป้าหมาย',
'exif-gpsprocessingmethod'         => 'ชื่อวิธีประมวดผลจีพีเอส',
'exif-gpsareainformation'          => 'ชื่อของพื้นที่จีพีเอส',
'exif-gpsdatestamp'                => 'วันที่จีพีเอส',
'exif-gpsdifferential'             => 'การปรับแค่ข้อแตกต่างจีพีเอส',

# EXIF attributes
'exif-compression-1' => 'ไม่ได้บีบอัด',

'exif-unknowndate' => 'ไม่ทราบวัน',

'exif-orientation-1' => 'ปกติ',
'exif-orientation-2' => 'ถูกสลับแนวนอน',
'exif-orientation-3' => 'ถูกหมุน 180°',
'exif-orientation-4' => 'ถูกสลับแนวตั้ง',
'exif-orientation-5' => 'ถูกหมุน 90° ทวนเข็มนาฬิกา และถูกสลับแนวตั้ง',
'exif-orientation-6' => 'ถูกหมุน 90° ตามเข็มนาฬิกา',
'exif-orientation-7' => 'ถูกหมุน 90° ตามเข็มนาฬิกา และถูกสลับแนวตั้ง',
'exif-orientation-8' => 'ถูกหมุน 90° ทวนเข็มนาฬิกา',

'exif-planarconfiguration-1' => 'รูปแบบชังกี',
'exif-planarconfiguration-2' => 'รูปแบบเพลนาร์',

'exif-componentsconfiguration-0' => 'ไม่มีค่า',

'exif-exposureprogram-0' => 'ไม่กำหนด',
'exif-exposureprogram-1' => 'ตั้งค่าเอง',
'exif-exposureprogram-2' => 'โปรแกรมปกติ',
'exif-exposureprogram-3' => 'กำหนดรูรับแสงเป็นหลัก (a)',
'exif-exposureprogram-4' => 'กำหนดความไวชัตเตอร์เป็นหลัก (s)',
'exif-exposureprogram-5' => 'โปรแกรมครีเอทีฟ (ความชัดตื้นหลากหลาย)',
'exif-exposureprogram-6' => 'โปรแกรมแอกชัน (ค่าชัตเตอร์สปีดเร็ว)',
'exif-exposureprogram-7' => 'พอร์เทรต (สำหรับภาพโคลสอัปที่พื้นหลังไม่โฟกัส)',
'exif-exposureprogram-8' => 'แลนด์สเคป (สำหรับภาพวิวทิวทัศน์โฟกัสพื้นหลัง)',

'exif-subjectdistance-value' => '$1 เมตร',

'exif-meteringmode-0'   => 'ไม่ทราบ',
'exif-meteringmode-1'   => 'เฉลี่ย',
'exif-meteringmode-2'   => 'เซนเตอร์',
'exif-meteringmode-3'   => 'สปอต',
'exif-meteringmode-4'   => 'มัลติสปอต',
'exif-meteringmode-5'   => 'แพตเทิร์น',
'exif-meteringmode-6'   => 'พาร์เชียล',
'exif-meteringmode-255' => 'อื่น',

'exif-lightsource-0'   => 'ไม่ทราบ',
'exif-lightsource-1'   => 'เดย์ไลต์',
'exif-lightsource-2'   => 'ฟลูออเรสเซนต์',
'exif-lightsource-3'   => 'ทังสเตน (แสดงจากหลอดไฟ)',
'exif-lightsource-4'   => 'แฟลช',
'exif-lightsource-9'   => 'อากาศปกติ',
'exif-lightsource-10'  => 'เมฆมาก',
'exif-lightsource-11'  => 'ในร่ม',
'exif-lightsource-12'  => 'เดย์ไลต์ฟลูออเรสเซนต์ (D 5700 – 7100K)',
'exif-lightsource-13'  => 'เดย์ไวต์ฟลูออเรสเซนต์ (N 4600 – 5400K)',
'exif-lightsource-14'  => 'คูลไวต์ฟลูออเรสเซนต์ (W 3900 – 4500K)',
'exif-lightsource-15'  => 'ไวต์ฟลูออเรสเซนต์ (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'แสงมาตรฐาน A',
'exif-lightsource-18'  => 'แสงมาตรฐาน B',
'exif-lightsource-19'  => 'แสงมาตรฐาน C',
'exif-lightsource-24'  => 'ไอเอสโอสตูดิโอทังสเตน',
'exif-lightsource-255' => 'แสงลักษณะอื่น',

# Flash modes
'exif-flash-fired-0'    => 'ไม่ใช้แฟลช',
'exif-flash-fired-1'    => 'ใช้แฟลช',
'exif-flash-return-0'   => 'ไม่มีฟังก์ชันตรวจจับการย้อนแสงจากแฟลช',
'exif-flash-return-2'   => 'ไม่พบแสงแฟลชย้อนกลับ',
'exif-flash-return-3'   => 'พบแสงแฟลชย้อนกลับ',
'exif-flash-mode-1'     => 'บังคับใช้แฟลช',
'exif-flash-mode-2'     => 'ระงับใช้แฟลช',
'exif-flash-mode-3'     => 'โหมดอัตโนมัติ',
'exif-flash-function-1' => 'ฟังก์ชันไม่มีแฟลช',
'exif-flash-redeye-1'   => 'โหมดลบตาแดง',

'exif-focalplaneresolutionunit-2' => 'นิ้ว',

'exif-sensingmethod-1' => 'ไม่กำหนด',
'exif-sensingmethod-2' => 'เซนเซอร์จุดเดียว',
'exif-sensingmethod-3' => 'เซนเซอร์สองจุด',
'exif-sensingmethod-4' => 'เซนเซอร์สามจุด',
'exif-sensingmethod-5' => 'เซนเซอร์ลำดับสี',
'exif-sensingmethod-7' => 'เซนเซอร์สามแนว',
'exif-sensingmethod-8' => 'เซนเซอร์สามแนวสี',

'exif-scenetype-1' => 'ภาพถ่ายโดยตรง',

'exif-customrendered-0' => 'โพลเซสส์ปกติ',
'exif-customrendered-1' => 'โพลเซสส์ตั้งค่า',

'exif-exposuremode-0' => 'เอกซ์โพเชอร์อัตโนมัติ',
'exif-exposuremode-1' => 'เอกซ์โพเชอร์ตั้งค่าเอง',
'exif-exposuremode-2' => 'แบรกเกตอัตโนมัติ',

'exif-whitebalance-0' => 'ไวต์บาลานซ์อัตโนมัติ',
'exif-whitebalance-1' => 'ไวต์บาลานซ์ตั้งค่าเอง',

'exif-scenecapturetype-0' => 'ปกติ',
'exif-scenecapturetype-1' => 'แลนด์สเคป',
'exif-scenecapturetype-2' => 'พอร์เทรต',
'exif-scenecapturetype-3' => 'ไนต์ซีน',

'exif-gaincontrol-0' => 'ไม่มี',
'exif-gaincontrol-1' => 'เกน ต่ำ-ขึ้น',
'exif-gaincontrol-2' => 'เกน สูง-ขึ้น',
'exif-gaincontrol-3' => 'เกน ต่ำ-ลง',
'exif-gaincontrol-4' => 'เกน สูง-ลง',

'exif-contrast-0' => 'ปกติ',
'exif-contrast-1' => 'ซอฟต์',
'exif-contrast-2' => 'ฮาร์ด',

'exif-saturation-0' => 'ปกติ',
'exif-saturation-1' => 'ความเข้มสีต่ำ',
'exif-saturation-2' => 'ความเข้มสีสูง',

'exif-sharpness-0' => 'ปกติ',
'exif-sharpness-1' => 'ซอฟต์',
'exif-sharpness-2' => 'ฮาร์ด',

'exif-subjectdistancerange-0' => 'ไม่ทราบ',
'exif-subjectdistancerange-1' => 'มาโคร',
'exif-subjectdistancerange-2' => 'ภาพใกล้',
'exif-subjectdistancerange-3' => 'ภาพไกล',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'ละติจูดเหนือ',
'exif-gpslatitude-s' => 'ละติจูดใต้',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'ลองจิจูดตะวันออก',
'exif-gpslongitude-w' => 'ลองจิจูดตะวันตก',

'exif-gpsstatus-a' => 'กำลังทำการวัดอยู่',
'exif-gpsstatus-v' => 'ความสามารถในการวัดตำแหน่ง',

'exif-gpsmeasuremode-2' => 'การวัดสองมิติ',
'exif-gpsmeasuremode-3' => 'การวัดสามมิติ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'กิโลเมตรต่อชั่วโมง',
'exif-gpsspeed-m' => 'ไมล์ต่อชั่วโมง',
'exif-gpsspeed-n' => 'นอตส์',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ทิศทางจริง',
'exif-gpsdirection-m' => 'ทิศทางแม่เหล็ก',

# External editor support
'edit-externally'      => 'แก้ไขไฟล์นี้โดยใช้ซอฟต์แวร์ตัวอื่น',
'edit-externally-help' => '(ดูเพิ่ม [http://www.mediawiki.org/wiki/Manual:External_editors วิธีการตั้งค่า] สำหรับข้อมูลเพิ่มเติม)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ทั้งหมด',
'imagelistall'     => 'ทั้งหมด',
'watchlistall2'    => 'ทั้งหมด',
'namespacesall'    => 'ทั้งหมด',
'monthsall'        => 'ทั้งหมด',
'limitall'         => 'ทั้งหมด',

# E-mail address confirmation
'confirmemail'             => 'ยืนยันอีเมล',
'confirmemail_noemail'     => 'ไม่ได้ใส่อีเมลในส่วน [[Special:Preferences|การตั้งค่าส่วนตัว]]',
'confirmemail_text'        => 'ถ้าต้องการใช้คำสั่งพิเศษในด้านอีเมสล จำเป็นต้องใส่ค่าอีเมลก่อน โดยกดที่ปุ่มด้านล่าง และทางระบบจะส่งไปที่อีเมลนี้ ในอีเมลจะมีลิงก์ซึ่งมีรหัสสำหรับยืนยันอีเมล',
'confirmemail_pending'     => 'รหัสยืนยันได้ถูกส่งไปที่อีเมลของคุณ ถ้าได้สร้างบัญชีเร็วนี้ ให้รอซักครู่ก่อนที่จะขอรหัสอีกครั้งหนึ่ง',
'confirmemail_send'        => 'ส่งรหัสยืนยันผ่านทางอีเมล',
'confirmemail_sent'        => 'อีเมลยืนยันได้ส่งเรียบร้อย',
'confirmemail_oncreate'    => 'รหัสยืนยันได้ถูกส่งไปที่อีเมล อย่างไรก็ตามรหัสนี้ไม่จำเป็นสำหรับการล็อกอิน เว้นเสียแต่ว่าต้องการใช้คำสั่งพิเศษในด้านอีเมลของวิกินี้',
'confirmemail_sendfailed'  => 'ขออภัย {{SITENAME}}ภาษาไทยไม่สามารถส่งอีเมลให้คุณยืนยันการใช้งานได้
กรุณาตรวจสอบอีเมลว่าถูกต้อง และไม่มีอักขระที่ไม่สามารถใช้ได้

ข้อความตีกลับ: $1',
'confirmemail_invalid'     => 'รหัสยืนยันไม่ถูกต้อง หรือรหัสหมดอายุ',
'confirmemail_needlogin'   => 'ต้องทำการ $1 เพื่อยืนยันอีเมลของคุณว่าถูกต้อง',
'confirmemail_success'     => 'อีเมลคุณได้รับการยืนยันแล้ว คุณอาจจะล็อกอินและมีความสุขกับวิกิ',
'confirmemail_loggedin'    => 'อีเมลคุณได้รับการยืนยันแล้ว',
'confirmemail_error'       => 'มีปัญหาเกิดขึ้นในการยืนยันอีเมล',
'confirmemail_subject'     => '{{SITENAME}} ยืนยันการใช้งานอีเมล',
'confirmemail_body'        => 'ใครบางคนซึ่งอาจจะเป็นคนจากหมายเลขไอพี $1 ได้ลงทะเบียนในชื่อ "$2" โดยใช้อีเมลนี้ที่ {{SITENAME}}

ถ้านี่เป็นการลงทะเบียนของคุณ คุณสามารถยืนยันได้โดยการกดลิงก์ที่เว็บเบราว์เซอร์ :

$3

ถ้านี่ไม่ใช่บัญชีคุณ กรุณา*อย่า*กดลิงก์ด้านบน แต่ขอให้กดตามลิงก์ข้างล่างแทน

$5

รหัสยืนยันนี้จะหมดอายุเมื่อ $4

ขอขอบคุณในความร่วมมือของคุณ',
'confirmemail_invalidated' => 'การยืนยันทางอีเมลได้ถูกยกเลิกแล้ว',
'invalidateemail'          => 'ยกเลิกการยืนยันทางอีเมล',

# Scary transclusion
'scarytranscludedisabled' => '[ส่งค่าของอินเตอร์วิกิถูกระงับ]',
'scarytranscludefailed'   => '[ไม่สามารถดึงแม่แบบมาได้สำหรับ $1]',
'scarytranscludetoolong'  => '[ที่อยู่เว็บไซต์ยาวเกินไป]',

# Trackbacks
'trackbackbox'      => 'ตามรอยหน้านี้:<br />
$1',
'trackbackremove'   => '([$1 ลบ])',
'trackbacklink'     => 'ตามรอย',
'trackbackdeleteok' => 'รอยที่เก็บไว้ถูกลบ',

# Delete conflict
'deletedwhileediting' => "'''คำเตือน''': หน้านี้ถูกลบไปแล้วในขณะที่คุณกำลังแก้ไข!",
'confirmrecreate'     => "ผู้ใช้ [[User:$1|$1]] ([[User talk:$1|พูดคุย]]) ได้ลบหน้านี้ในช่วงที่คุณกำลังแก้ไข ด้วยเหตุผลว่า:
: ''$2''
กรุณายืนยันว่าต้องการสร้างหน้านี้ขึ้นมาใหม่",
'recreate'            => 'สร้างใหม่',

# action=purge
'confirm_purge_button' => 'ตกลง',
'confirm-purge-top'    => 'ล้างแคชสำหรับหน้านี้หรือไม่',
'confirm-purge-bottom' => 'การกำจัดหน้าจะล้างแคชของหน้านี้และบังคับให้ฉบับปรับปรุงล่าสุดปรากฎขึ้น',

# Multipage image navigation
'imgmultipageprev' => '← หน้าก่อนหน้า',
'imgmultipagenext' => 'หน้าถัดไป →',
'imgmultigo'       => 'ไป!',
'imgmultigoto'     => 'ไปที่หน้า $1',

# Table pager
'ascending_abbrev'         => 'หน้าไปหลัง',
'descending_abbrev'        => 'หลังมาหน้า',
'table_pager_next'         => 'หน้าถัดไป',
'table_pager_prev'         => 'หน้าก่อนหน้า',
'table_pager_first'        => 'หน้าแรก',
'table_pager_last'         => 'หน้าสุดท้าย',
'table_pager_limit'        => 'แสดง $1 รายการต่อหน้า',
'table_pager_limit_submit' => 'ค้นหา',
'table_pager_empty'        => 'ไม่พบที่ต้องการ',

# Auto-summaries
'autosumm-blank'   => 'ทำหน้าว่าง',
'autosumm-replace' => "แทนทีข้อความทั้งหมดด้วย '$1'",
'autoredircomment' => 'เปลี่ยนทางไปที่ [[$1]]',
'autosumm-new'     => "หน้าที่ถูกสร้างด้วย '$1'",

# Size units
'size-bytes'     => '$1 ไบต์',
'size-kilobytes' => '$1 กิโลไบต์',
'size-megabytes' => '$1 เมกะไบต์',
'size-gigabytes' => '$1 กิกะไบต์',

# Live preview
'livepreview-loading' => 'กำลังโหลด…',
'livepreview-ready'   => 'กำลังโหลด… เสร็จ!',
'livepreview-failed'  => 'แสดงตัวอย่างทันทีไม่ได้ ให้ลองใช้การแสดงตัวอย่างแบบธรรมดา',
'livepreview-error'   => 'เชื่อมต่อไม่ได้: $1 "$2" ให้ลองใช้แสดงตัวอย่างแบบธรรมดา',

# Friendlier slave lag warnings
'lag-warn-normal' => 'การปรับปรุงที่ใหม่กว่า $1 วินาที อาจไม่แสดงผลในรายการนี้',
'lag-warn-high'   => 'เนื่องจากปัญหาการล่าช้าของเซิร์ฟเวอร์ฐานข้อมูล การปรับปรุงที่ใหม่กว่า $1 วินาที อาจไม่แสดงผลในรายการนี้',

# Watchlist editor
'watchlistedit-numitems'       => 'รายการเฝ้าดูมี $1 รายการ ไม่รวมหน้าพูดคุย',
'watchlistedit-noitems'        => 'ไม่มีหัวข้อใดในรายการเฝ้าดู',
'watchlistedit-normal-title'   => 'แก้ไขรายการเฝ้าดู',
'watchlistedit-normal-legend'  => 'ลบชื่อหัวข้อออกจากรายการเฝ้าดู',
'watchlistedit-normal-explain' => 'หัวข้อที่อยู่ในรายการเฝ้าดูแสดงด้านล่าง ถ้าต้องการลบออกให้เลือกที่กล่องด้านข้างแต่ละหัวข้อ และกดลบออก หรืออาจจะ[[Special:Watchlist/raw|แก้ไขรายการทั้งหมด]]',
'watchlistedit-normal-submit'  => 'ลบหัวข้อ',
'watchlistedit-normal-done'    => '$1 รายการได้ถูกนำออกจากรายการเฝ้าดู:',
'watchlistedit-raw-title'      => 'แก้ไขรายการเฝ้าดูทั้งหมด',
'watchlistedit-raw-legend'     => 'แก้ไขรายการเฝ้าดูทั้งหมด',
'watchlistedit-raw-explain'    => 'หัวข้อในรายการเฝ้าดูแสดงด้านล่าง ซึ่งสามารถเพิ่มหรือนำออกได้ หนึ่งหัวข้อต่อหนึ่งแถว เมื่อแก้ไขเสร็จแล้ว ให้กดอัปเดตรายการเฝ้าดู ซึ่งอาจแก้ไขผ่าน [[Special:Watchlist/edit|โปรแกรมพแก้ไขข้อความทั่วไป]]',
'watchlistedit-raw-titles'     => 'หัวข้อ:',
'watchlistedit-raw-submit'     => 'อัปเดตรายการเฝ้าดู',
'watchlistedit-raw-done'       => 'รายการเฝ้าดูได้ถูกอัปเดต',
'watchlistedit-raw-added'      => '$1 หัวข้อได้ถูกเพิ่มเข้าไป:',
'watchlistedit-raw-removed'    => '$1 หัวข้อได้ถูกนำออกไป:',

# Watchlist editing tools
'watchlisttools-view' => 'ดูการเปลี่ยนแปลงที่เกี่ยวข้อง',
'watchlisttools-edit' => 'ดูและแก้ไขรายการเฝ้าดู',
'watchlisttools-raw'  => 'แก้ไขรายการเฝ้าดูทั้งหมด',

# Core parser functions
'unknown_extension_tag' => 'ไม่รู้จัก tag ส่วนขยาย (extension tag) "$1"',
'duplicate-defaultsort' => 'คำเตือน: หลักเรียงลำดับปริยาย "$2" ได้ลบล้างหลักเรียงลำดับปริยาย "$1" ที่มีอยู่ก่อนหน้า',

# Special:Version
'version'                          => 'รุ่นซอฟต์แวร์',
'version-extensions'               => 'ส่วนขยายเพิ่ม (extension) ที่ติดตั้ง',
'version-specialpages'             => 'หน้าพิเศษ',
'version-parserhooks'              => 'ฮุกที่มีการพาร์สค่า',
'version-variables'                => 'ตัวแปร',
'version-other'                    => 'อื่นๆ',
'version-mediahandlers'            => 'ตัวจัดการเกี่ยวกับสื่อ (media handler)',
'version-hooks'                    => 'ฮุก',
'version-extension-functions'      => 'ฟังก์ชันจากส่วนขยายเพิ่ม (extension function)',
'version-parser-extensiontags'     => 'แท็กที่มีการใช้งานของพาร์สเซอร์',
'version-parser-function-hooks'    => 'ฮุกที่มีฟังก์ชันพาร์สเซอร์',
'version-skin-extension-functions' => 'ฟังก์ชันส่วนขยายเพิ่ม (extension function) สำหรับสกินหรือแบบหน้าตา',
'version-hook-name'                => 'ชื่อฮุก',
'version-hook-subscribedby'        => 'สนับสนุนโดย',
'version-version'                  => '(รุ่น $1)',
'version-license'                  => 'สัญญาอนุญาต',
'version-software'                 => 'ซอฟต์แวร์ที่ติดตั้ง',
'version-software-product'         => 'ชื่อ',
'version-software-version'         => 'รุ่น',

# Special:FilePath
'filepath'         => 'พาธของไฟล์',
'filepath-page'    => '{{ns:file}}:',
'filepath-submit'  => 'ไป',
'filepath-summary' => 'หน้าพิเศษนี้คืนค่าเป็นเส้นทางเต็มของไฟล์
ไฟล์ภาพจะถูกแสดงในขนาดเต็ม และไฟล์ประเภทอื่นจะถูกเปิดด้วยโปรแกรมที่เกี่ยวข้องโดยตรง

กรุณาป้อนชื่อไฟล์โดยไม่มี "{{ns:file}}:" นำหน้า',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'ค้นหาไฟล์ที่ซ้ำซ้อน',
'fileduplicatesearch-summary'  => 'ค้นหาไฟล์ที่ซ้ำซ้อนด้วยค่าแฮชของไฟล์

กรุณาป้อนชื่อไฟล์โดยไม่มี "{{ns:file}}:" นำหน้า',
'fileduplicatesearch-legend'   => 'ค้นหาไฟล์ที่ซ้ำกัน',
'fileduplicatesearch-filename' => 'ชื่อไฟล์ :',
'fileduplicatesearch-submit'   => 'สืบค้น',
'fileduplicatesearch-info'     => '$1 × $2 พิกเซล<br />ขนาดไฟล์: $3<br />ชนิดของไมม์: $4',
'fileduplicatesearch-result-1' => 'ไม่มีไฟล์ที่ซ้ำกับไฟล์ "$1"',
'fileduplicatesearch-result-n' => 'มี {{PLURAL:$2|ไฟล์เดียว|$2 ไฟล์}}ที่ซ้ำกับไฟล์ "$1"',

# Special:SpecialPages
'specialpages'                   => 'หน้าพิเศษ',
'specialpages-note'              => '----
* หน้าพิเศษปกติ
* <strong class="mw-specialpagerestricted">หน้าพิเศษสำหรับผู้ดูแล</strong>',
'specialpages-group-maintenance' => 'รายงานการเก็บกวาด',
'specialpages-group-other'       => 'หน้าพิเศษอื่น ๆ',
'specialpages-group-login'       => 'ล็อกอิน / สร้างบัญชีผู้ใช้ใหม่',
'specialpages-group-changes'     => 'ปรับปรุงล่าสุดและปูมต่าง ๆ',
'specialpages-group-media'       => 'รายงานเรื่องสื่อและการอัปโหลด',
'specialpages-group-users'       => 'ผู้ใช้และสิทธิ',
'specialpages-group-highuse'     => 'หน้าที่มีการใช้งานสูง',
'specialpages-group-pages'       => 'รายชื่อหน้า',
'specialpages-group-pagetools'   => 'เครื่องมือเกี่ยวกับหน้าต่าง ๆ',
'specialpages-group-wiki'        => 'เครื่องมือและข้อมูลวิกิ',
'specialpages-group-redirects'   => 'เปลี่ยนทางหน้าพิเศษ',
'specialpages-group-spam'        => 'เครื่องมือเกี่ยวกับสแปม',

# Special:BlankPage
'blankpage'              => 'หน้าว่างเปล่า',
'intentionallyblankpage' => 'หน้านี้ถูกทิ้งว่างโดยเจตนา',

# External image whitelist
'external_image_whitelist' => '  #เว้นบรรทัดนี้ไว้จากการแก้ไข<pre>
#ใส่คำอธิบายปกติ (เฉพาะในส่วนที่อยู่ระหว่างสัญลักษณ์ //) ด้านล่างนี้
#ซึ่งคำอธิบายดังกล่าวจะถูกจับคู่กับ URL ของรูปถ่ายภายนอก
#ถ้าตรงกันจะปรากฎเป็นภาพออกมา หรือมิเช่นนั้นจะปรากฎเป็นลิงก์ไปยังรูปภาพนั้น
#บรรทัดที่ขึ้นต้นด้วย # จะถูกกำหนดเป็นหมายเหตุเพิ่มเติม
#กรุณาพิมพ์ตัวพิมพ์เล็ก-ใหญ่ตามชื่อไฟล์ให้ตรงกัน

#ใส่ส่วนของคำอธิบายด้านบนของบรรทัดนี้และเว้นบรรทัดนี้จากการแก้ไข</pre>',

# Special:Tags
'tags'                    => 'ป้ายกำกับการเปลี่ยนแปลง (ที่สามารถใช้ได้)',
'tag-filter'              => 'ตัวกรอง[[Special:Tags|ป้ายกำกับ]]:',
'tag-filter-submit'       => 'กรอง',
'tags-title'              => 'ป้ายกำกับ',
'tags-intro'              => 'หน้านี้แสดงรายการและความหมายของป้ายกำกับต่างๆ ที่ซอฟต์แวร์อาจจะใช้ทำเครื่องหมายกำกับการแก้ไข',
'tags-tag'                => 'ชื่อป้ายกำกับ',
'tags-display-header'     => 'สิ่งที่แสดงในรายการการเปลี่ยนแปลง',
'tags-description-header' => 'คำอธิบายความหมายโดยละเอียด',
'tags-hitcount-header'    => 'การเปลี่ยนแปลงที่มีป้ายนี้กำกับ',
'tags-edit'               => 'แก้ไข',
'tags-hitcount'           => '$1 การเปลี่ยนแปลง',

# Database error messages
'dberr-header'      => 'วิกินี้กำลังประสบปัญหา',
'dberr-problems'    => 'ขออภัย เว็บไซต์นี้กำลังพบกับข้อผิดพลาดทางเทคนิค',
'dberr-again'       => 'กรุณารอสักครู่แล้วจึงโหลดใหม่',
'dberr-info'        => '(ไม่สามารถติดต่อเซิร์ฟเวอร์ฐานข้อมูลได้: $1)',
'dberr-usegoogle'   => 'คุณสามารถลองสืบค้นผ่านกูเกิลในระหว่างนี้',
'dberr-outofdate'   => 'โปรดทราบว่าดัชนีเนื้อหาของเราในกูเกิลอาจล้าสมัยแล้ว',
'dberr-cachederror' => 'นี่คือข้อมูลคัดลอกชั่วคราวของหน้าที่ร้องขอ และอาจไม่เป็นปัจจุบัน',

# HTML forms
'htmlform-invalid-input'       => 'เกิดปัญหาในค่าบางค่าที่คุณใส่เข้ามา',
'htmlform-select-badoption'    => 'ค่าที่คุณใส่ไม่ใช่การตั้งค่าที่ถูกต้อง',
'htmlform-int-invalid'         => 'ค่าที่คุณกำหนดไม่ใช่ตัวเลขจำนวนเต็ม',
'htmlform-float-invalid'       => 'ค่าที่คุณกำนหดไม่ใช่ตัวเลข',
'htmlform-int-toolow'          => 'ค่าที่คุณกำหนดนั้นต่ำกว่าค่าต่ำสุดที่ $1',
'htmlform-int-toohigh'         => 'ค่าที่คุณกำหนดนั้นเกินกว่าค่าสูงสุดที่ $1',
'htmlform-submit'              => 'ส่งข้อมูล',
'htmlform-reset'               => 'ยกเลิกการเปลื่ยนแปลง',
'htmlform-selectorother-other' => 'อื่นๆ',

);
