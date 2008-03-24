<?php
/** Lao (ລາວ)
 *
 * @addtogroup Language
 *
 * @author Passawuth
 * @author Tuinui
 * @author Jon Harald Søby
 * @author Nike
 */

$namespaceNames = array(
	NS_MEDIA          => 'ສື່ອ',
	NS_SPECIAL        => 'ພິເສດ',
	NS_MAIN           => '',
	NS_TALK           => 'ສົນທະນາ',
	NS_USER           => 'ຜູ້ໃຊ້',
	NS_USER_TALK      => 'ສົນທະນາຂອງຜູ້ໃຊ້',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => 'ສົນທະນາກ່ຽວກັບ$1',
	NS_IMAGE          => 'ຮູບ',
	NS_IMAGE_TALK     => 'ສົນທະນາກ່ຽວກັບຮູບ',
	NS_MEDIAWIKI      => 'ມີເດຍວິກິ',
	NS_MEDIAWIKI_TALK => 'ສົນທະນາກ່ຽວກັບມີເດຍວິກິ',
	NS_TEMPLATE       => 'ແມ່ແບບ',
	NS_TEMPLATE_TALK  => 'ສົນທະນາກ່ຽວກັບແມ່ແບບ',
	NS_HELP           => 'ຊ່ວຍເຫຼືອ',
	NS_HELP_TALK      => 'ສົນທະນາກ່ຽວກັບຊ່ວຍເຫຼືອ',
	NS_CATEGORY       => 'ໝວດ',
	NS_CATEGORY_TALK  => 'ສົນທະນາກ່ຽວກັບໝວດ',
);

$digitTransformTable = array(
	'0' => '໐', # &#x0ed0;
	'1' => '໑', # &#x0ed1;
	'2' => '໒', # &#x0ed2;
	'3' => '໓', # &#x0ed3;
	'4' => '໔', # &#x0ed4;
	'5' => '໕', # &#x0ed5;
	'6' => '໖', # &#x0ed6;
	'7' => '໗', # &#x0ed7;
	'8' => '໘', # &#x0ed8;
	'9' => '໙', # &#x0ed9;
);

$messages = array(
# User preference toggles
'tog-underline'               => 'ຂີດເສ້ນໃຕ້ລິງກ໌:',
'tog-hideminor'               => 'ເຊື່ອງການດັດແກ້ເລັກນ້ອຍ ໃນ ການປ່ຽນແປງຫຼ້າສຸດ',
'tog-extendwatchlist'         => 'ຂະຫຍາຍ ລາຍການຕິດຕາມ ເພື່ອ ສະແດງທຸກໆການປ່ຽນແປງ',
'tog-usenewrc'                => 'ສະແດງ ການປ່ຽນແປງຫຼ້າສຸດ ເປັນສີເຂັ້ມ (JavaScript)',
'tog-showtoolbar'             => 'ສະແດງ ທູລບາດັດແກ້ (JavaScript)',
'tog-editondblclick'          => 'ໃຫ້ສາມາດດັດແກ້ ໂດຍ ດັບເບິລ໌ ຄລິກ (JavaScript)',
'tog-editsection'             => 'ໃຫ້ສາມາດ ດັດແກ້ເປັນພາກ ໂດຍ ກົດລິ້ງຄ໌ [ດັດແກ້]',
'tog-editsectiononrightclick' => 'ໃຫ້ສາມາດ ດັດແກ້ ໂດຍກົດປຸ່ມຂວາ ຂອງເມົາສ໌ ຢູ່ <br /> ຫົວຂໍ້ຂອງພາກ (JavaScript)',
'tog-rememberpassword'        => 'ຈົດຈໍາການເຊັນເຂົ້າຂອງຂ້ອຍ ຢູ່ ຄອມພິວເຕີໜ່ວຍນີ້',
'tog-editwidth'               => 'ໃຫ້ ຊ່ອງດັດແກ້ ກ້ວາງທີ່ສຸດ',
'tog-watchcreations'          => 'ເພີ່ມ ໜ້າທີ່ຂ້ອຍສ້າງ ເຂົ້າໃນ ລາຍການຕິດຕາມ ຂອງ ຂ້ອຍ',
'tog-watchdefault'            => 'ເພີ່ມໜ້າ ທີ່ ຂ້ອຍດັດແກ້ ໃສ່ ລາຍຕິດຕາມ ຂອງ ຂ້ອຍ',
'tog-watchmoves'              => 'ເພີ່ມ ໜ້າທີ່ຂອ້ຍຍ້າຍ ໃສ່ ລາຍການຕິດຕາມຂອງຂ້ອຍ',
'tog-watchdeletion'           => 'ເພີ່ມ ໜ້າ ທີ່ຂ້ອຍລຶບ ໃສ່ ລາຍການຕິດຕາມ ຂອງ ຂ້ອຍ',
'tog-minordefault'            => 'ຖ້າບໍ່ມີການຕັ້ງ ກຳໜົດໃຫ້ທຸກໆການດັດແກ້ ເປັນ ການດັດແກ້ເລັກນ້ອຍ',
'tog-previewontop'            => 'ສະແດງ ຜົນການດັດແກ້ ເທິງ ຊ່ອງດັດແກ້',
'tog-previewonfirst'          => 'ສະແດງ ຜົນການດັດແກ້ ໃນ ເວລາດັດແກ້ເທື່ອທຳອິດ',
'tog-enotifwatchlistpages'    => 'ສົ່ງອີເມລມາໃຫ້ຂ້ອຍ ເມື່ອ ໜ້າທີ່ຂ້ອຍຕິດຕາມໄດ້ຖືກປ່ຽນແປງ',
'tog-enotifusertalkpages'     => 'ສົ່ງ ອີເມລ ມາໃຫ້ ຂ້ອຍ ເວລາ ໜ້າສົນທະນາຂ້ອຍ ມີການປ່ຽນແປງ',
'tog-externaleditor'          => 'ໃຊ້ ເອດິດເຕີທາງນອກ ຖ້າບໍ່ມີການຕັ້ງ',
'tog-externaldiff'            => 'ໃຊ້ ສ່ວນຕ່າງທາງນອກ ຖ້າບໍ່ມີ ການກຳໜົດ',
'tog-forceeditsummary'        => 'ເຕື່ອນຂ້ອຍ ຖ້າບໍ່ໄດ້ຂຽນຫຍັງໃສ່ ຊ່ອງ ສະຫຼຸບການດັດແກ້',
'tog-watchlisthideown'        => 'ເຊື່ອງ ການດັດແກ້ໂດຍຂ້ອຍ ໃນ ລາຍການຕິດຕາມຂອງຂ້ອຍ',
'tog-watchlisthidebots'       => 'ເຊື່ອງ ການດັດແກ້ ໂດຍ ບອທ໌ ໃນລາຍການຕິດຕາມຂອງຂ້ອຍ',
'tog-watchlisthideminor'      => 'ເຊື່ອງ ການດັດແກ້ເລັກນ້ອຍ ໃນ ລາຍການຕິດຕາມຂອງຂ້ອຍ',
'tog-ccmeonemails'            => 'ສົ່ງສຳເນົາ ອີເມລ ທີ່ ຂ້ອຍສົ່ງຫາຜູ້ອື່ນ ໃຫ້ ຂ້ອຍ',
'tog-showhiddencats'          => 'ສະແດງໝວດທີ່ຊ່ອນ',

'underline-always' => 'ທຸກຄັ້ງ',
'underline-never'  => 'ບໍ່ຂີດ',

'skinpreview' => '(ລອງເບິ່ງ)',

# Dates
'january'   => 'ມັງກອນ',
'february'  => 'ກຸມພາ',
'march'     => 'ມີນາ',
'april'     => 'ເມສາ',
'may_long'  => 'ພຶດສະພາ',
'june'      => 'ມີຖຸນາ',
'july'      => 'ກໍລະກົດ',
'august'    => 'ສິງຫາ',
'september' => 'ກັນຍາ',
'october'   => 'ຕຸລາ',
'november'  => 'ພະຈິກ',
'december'  => 'ທັນວາ',
'may-gen'   => 'ພຶດສະພາ',
'jan'       => 'ມັງກອນ',
'feb'       => 'ກຸມພາ',
'may'       => 'ພຶດສະພາ',

# Categories related messages
'categories'             => 'ໝວດ',
'categoriespagetext'     => 'ມີ ບັນດາ ໝວດ ຕໍ່ໄປນີ້ ຢູ່ ວິກີ.',
'pagecategories'         => '{{PLURAL:$1|ໝວດ|ໝວດ}}',
'category_header'        => 'ບົດຄວາມໃນໝວດ "$1"',
'subcategories'          => 'ໝວດຍ່ອຍ',
'category-media-header'  => 'ໜ້າຕ່າງ າ ໃນ "$1"',
'category-empty'         => "''ບໍ່ມີໜ້າໃດຢູ່ໃນໝວດນີ້''",
'category-article-count' => '{{PLURAL:$2|ໝວດນີ້ມີໜ້າຢູ່ພຽງໜ້າດຽວ|ມີ {{PLURAL:$1|ໜ້າດຽວ|$1 ໜ້າ}} ໃນໝວດນີ້ ເຕັມ $2}}',

'mainpagetext' => "<big>'''ຕິດຕັ້ງມີເດຍວິກິນີ້ສຳເລັດແລ້ວ'''</big>",

'about'          => 'ກ່ຽວກັບ',
'article'        => 'ບົດຄວາມ',
'newwindow'      => '(ເປີດເປັນ ປ່ອງຢ້ຽມ(ວິນໂດ)ໃໝ່ )',
'cancel'         => 'ລົບລ້າງ',
'qbfind'         => 'ຊອກຫາ',
'qbedit'         => 'ດັດແກ້',
'qbpageoptions'  => 'ໜ້ານີ້',
'qbspecialpages' => 'ໜ້າພິເສດ',
'mypage'         => 'ໜ້າຂ້ອຍ',
'mytalk'         => 'ສົນທະນາ',
'anontalk'       => 'ສົນທະນາ ສຳຫຼັບ IP ນີ້',
'navigation'     => 'ນຳທິດ',
'and'            => 'ແລະ',

'returnto'         => 'ກັບໄປ  $1.',
'tagline'          => 'ຈາກ {{SITENAME}}',
'help'             => 'ຊ່ວຍເຫຼືອ',
'search'           => 'ຊອກຫາ',
'searchbutton'     => 'ຊອກຫາ',
'go'               => 'ໄປ',
'searcharticle'    => 'ໄປ',
'history'          => 'ປະຫວັດ',
'history_short'    => 'ປະຫວັດການດັດແກ້',
'info_short'       => 'ຂ້ອມູນ',
'printableversion' => 'ສະບັບພິມໄດ້',
'permalink'        => 'ລິ້ງຄ໌ຖາວອນ',
'edit'             => 'ດັດແກ້',
'create'           => 'ສ້າງ',
'editthispage'     => 'ດັດແກ້ໜ້ານີ້',
'create-this-page' => 'ສ້າງໜ້ານີ້',
'delete'           => 'ລຶບ',
'deletethispage'   => 'ລຶບໜ້ານີ້',
'protect'          => 'ປົກປ້ອງ',
'protect_change'   => 'ປ່ຽນຮູບແບບການປົກປ້ອງ',
'protectthispage'  => 'ປ້ອງກັນໜ້ານີ້',
'unprotect'        => 'ເຊົາປົກປ້ອງ',
'newpage'          => 'ໜ້າໃໝ່',
'talkpage'         => 'ປຶກສາຫາລືໜ້ານີ້',
'talkpagelinktext' => 'ສົນທະນາ',
'articlepage'      => 'ເບິ່ງໜ້າເນື້ອໃນ',
'talk'             => 'ສົນທະນາ',
'toolbox'          => 'ກັບອຸບປະກອນ',
'templatepage'     => 'ເບິ່ງໜ້າແມ່ແບບ',
'categorypage'     => 'ເບິ່ງ ໜ້າ ໝວດ',
'otherlanguages'   => 'ເປັນພາສາອື່ນໆ',
'redirectedfrom'   => '(ໂອນມາຈາກ $1)',
'redirectpagesub'  => 'ໜ້າໂອນ',
'lastmodifiedat'   => 'ໜ້ານີ້ຖຶກດັດແກ້ຫຼ້າສຸດ $2, $1.', # $1 date, $2 time
'viewcount'        => 'ໜ້ານີ້ຖືກເຂົ້າເບິ່ງ {{plural:$1|ເທື່ອໜຶ່ງ|$1 ເທື່ອ}}.',
'protectedpage'    => 'ໜ້າຖືກປົກປ້ອງ',
'jumpto'           => 'ໄປຫາ:',
'jumptonavigation' => 'ນຳທິດ',
'jumptosearch'     => 'ຄົ້ນຫາ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => 'ກ່ຽວກັບ{{SITENAME}}',
'aboutpage'      => 'Project:ກ່ຽວກັບ',
'bugreports'     => 'ລາຍງານ ບັນຫາທາງໂປແກມ',
'bugreportspage' => 'ໂຄງການ:ລາຍງານບັນຫາທາງໂປແກມ',
'copyright'      => 'ເນື້ອໃນ ສະໜອງໃຫ້ ພາຍໃຕ້ $1.',
'copyrightpage'  => '{{ns:project}}:ລິຂະສິດ',
'currentevents'  => 'ກິດຈະກຳພວມດຳເນີນ',
'disclaimers'    => 'ຂໍ້ປະຕິເສດຄວາມຮັບຜິດຊອບ',
'disclaimerpage' => 'Project:ຂໍ້ປະຕິເສດຄວາມຮັບຜິດຊອບ',
'edithelp'       => 'ວິທີດັດແກ້ໜ້າ',
'edithelppage'   => 'Help:ການດັດແກ້',
'helppage'       => 'Help:ເນື້ອໃນ',
'mainpage'       => 'ໜ້າຫຼັກ',
'portal'         => 'ປະຕູຊຸມຊົນ',
'privacy'        => 'ນະໂຍບາຍຄວາມເປັນສ່ວນຕັວ',
'privacypage'    => 'Project:ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ',
'sitesupport'    => 'ບໍລິຈາກ',

'badaccess'        => 'ການອະນຸມັດບໍ່ຖືກຕ້ອງ',
'badaccess-group0' => 'ທ່ານ ບໍ່ໄດ້ຮັບອະນຸຍາດ ໃຫ້ປະຕິບັດການ ທີ່ ທ່ານ ສັ່ງ.',

'versionrequired' => 'ຕ້ອງການເວີຣ໌ຊັ່ນ $1 ຂອງມີເດຍວິກິ',

'retrievedfrom'           => 'ຖອນມາຈາກ "$1"',
'youhavenewmessages'      => 'ທ່ານ ມີ $1 ($2).',
'newmessageslink'         => 'ຂໍ້ຄວາມໃໝ່',
'newmessagesdifflink'     => 'ການປ່ຽນແປງຫຼ້າສຸດ',
'youhavenewmessagesmulti' => 'ທ່ານມີຂໍ້ຄວາມໃໝ່ ຢູ່ $1',
'editsection'             => 'ດັດແກ້',
'editold'                 => 'ດັດແກ້',
'editsectionhint'         => 'ດັດແກ້ ພາກ: $1',
'toc'                     => 'ເນື້ອໃນ',
'showtoc'                 => 'ສະແດງ',
'hidetoc'                 => 'ເຊື່ອງ',
'viewdeleted'             => 'ເບິ່ງ $1 ບໍ?',
'red-link-title'          => '$1 (ຍັງບໍ່ໄດ້ສ້າງ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ບົດຄວາມ',
'nstab-user'      => 'ໜ້າຜູ້ໃຊ້',
'nstab-special'   => 'ພິເສດ',
'nstab-project'   => 'ໜ້າໂຄງການ',
'nstab-image'     => 'ໄຟລ໌',
'nstab-mediawiki' => 'ຂໍ້ຄວາມ',
'nstab-template'  => 'ແມ່ແບບ',
'nstab-help'      => 'ໜ້າຊ່ອຍເຫຼືອ',
'nstab-category'  => 'ໝວດ',

# General errors
'noconnect'          => 'ຂໍອະໄພ! ວິກີ ພວມປະສົບບັນຫາ ທາງດ້ານເຕັກນິກ ແລະ ບໍ່ສາມາດເຂົ້າຫາ ຖານຂໍ້ມູນ.<br />$1',
'cachederror'        => 'ທາງລຸ່ມນີ້ ແມ່ນ ບັນທຶກຂໍ້ມູນ ຂອງ ໜ້າທີ່ທ່ານຊອກຫາ ເຊິ່ງອາດຈະບໍ່ແມ່ນສະບັບຫຼ້າສຸດ.',
'filerenameerror'    => 'ບໍ່ສາມາດ ປ່ຽນ ຊື່ໄຟລ໌  "$1" ໄປເປັນ  "$2" ໄດ້.',
'cannotdelete'       => 'ບໍ່ສາມາດລຶບໜ້າ ຫຼື ໄຟລ໌ ໄດ້. (ມັນອາດຈະຖືກລຶບໂດຍໃຜຜູ້ໜຶ່ງແລ້ວ.)',
'badtitle'           => 'ຫົວຂໍ້ບໍ່ຖືກຕ້ອງ',
'viewsource'         => 'ເບິ່ງຊອສ໌',
'namespaceprotected' => "ທ່ານບໍ່ໄດ້ຮັບອະນຸຍາດ ໃຫ້ ດັດແກ້ ໜ້າ ໃນ  '''$1'''.",

# Login and logout pages
'logouttitle'                => 'ຜູ້ໃຊ້ ເຊັນອອກ',
'logouttext'                 => '<strong>ທ່່ານ ໄດ້ ເຊັນອອກແລ້ວ.</strong><br />
ທ່ານ ສາມາດສືບຕໍ່ໃຊ້ {{SITENAME}} ແບບບໍ່ສະແດງຊື່, ຫຼື ທ່ານ ສາມາດ ເຊັນເຂົ້າ
ອີກ ໂດຍ ຊື່ຜູ້ໃຊ້ ເກົ່າ ຫຼື ໃໝ່ ກໍ່ໄດ້. ກະລຸນາ ຮັບຊາບວ່າ ບາງໜ້າ ອາດຈະສືບຕໍ່ ສະແດງ
ຄືວ່າ ທ່ານ ຍັງເຊັນເຂົ້າຢູ່ ຈົນກວ່າ ທ່ານ ຂໍ້ມູນບັນທຶກຊົ່ວຄາວ ໃນ ໂປຣແກຣມທ່ອງເວັບ 
ຂອງ ທ່ານ ຈະຖືກລຶບ.',
'welcomecreation'            => '== ຍິນດີຕ້ອນຮັບ, $1! ==

ບັນຊີ ຂອງທ່ານ ຖືກສ້າງຂຶ້ນແລ້ວ. ຢ່າລືມ ຕັ້ງຄ່າ ທ່ານ ຢູ່ {{SITENAME}}.',
'loginpagetitle'             => 'ຜູ້ໃຊ້ ເຊັນເຂົ້າ',
'yourname'                   => 'ຊື່ຜູ້ໃຊ້',
'yourpassword'               => 'ລະຫັດຜ່ານ',
'yourpasswordagain'          => 'ພິມລະຫັດຜ່ານອີກ',
'remembermypassword'         => 'ຈົດຈໍາການເຊັນເຂົ້າຂອງຂ້ອຍ ຢູ່ ຄອມພິວເຕີໜ່ວຍນີ້',
'yourdomainname'             => 'ໂດເມນ ຂອງ ທ່ານ',
'login'                      => 'ເຊັນເຂົ້າ',
'userlogin'                  => 'ເຊັນເຂົ້າ / ສ້າງບັນຊີ',
'logout'                     => 'ເຊັນອອກ',
'userlogout'                 => 'ເຊັນອອກ',
'nologin'                    => 'ບໍ່ມີບັນຊີເທື່ອບໍ? $1.',
'nologinlink'                => 'ສ້າງບັນຊີໃໝ່',
'createaccount'              => 'ສ້າງບັນຊີ',
'gotaccount'                 => 'ມີ ບັນຊີແລ້ວບໍ? $1.',
'gotaccountlink'             => 'ເຊັນເຂົ້າ',
'createaccountmail'          => 'ໂດຍ ອີເມລ',
'badretype'                  => 'ລະຫັດຜ່ານ ທີ່ ທ່ານພິມຄືນ ບໍ່ຖືກກັບ ທີ່ພິມກ່ອນ.',
'youremail'                  => 'ອີເມລ *:',
'username'                   => 'ຊື່ຜູ້ໃຊ້:',
'uid'                        => 'ເລກປະຈຳໂຕຜູ້ໃຊ້:',
'yourrealname'               => 'ຊື່ແທ້ *:',
'yourlanguage'               => 'ພາສາ:',
'yournick'                   => 'ຊື່ຫຼິ້ນ:',
'email'                      => 'ອີເມລ',
'loginerror'                 => 'ມີບັນຫາໃນການເຊັນເຂົ້າ',
'prefs-help-email'           => '* ອີເມລ (ເລືອກໄດ້): ໃຫ້ຜູ້ອື່ນ ສາມາດຕິດຕໍ່ຫາ ທ່ານ ຜ່ານ ໜ້າຜູ້ໃຊ້ ຫຼື ໜ້າສົນທະນາຜູ້ໃຊ້ ຂອງ ທ່ານ ໂດຍ ບໍ່ຈຳເປັນຕ້ອງ ເປີດເຜີຍ ອີເມລຂອງທ່ານ.',
'loginsuccesstitle'          => 'ເຊັນເຂົ້າສຳເລັດ',
'loginsuccess'               => "'''ທ່ານ ເຊັນເຂົ້າ  {{SITENAME}} ໃນນາມ \"\$1\".'''",
'wrongpassword'              => 'ລະຫັດຜ່ານບໍ່ຖືກ. ກະລຸນາເຮັດຄືນໃໝ່.',
'wrongpasswordempty'         => 'ບໍ່ມີລະຫັດຜ່ານຖືກພິມເຂົ້າ. ກະລຸນາເຮັດຄືນໃໝ່.',
'mailmypassword'             => 'ຂໍລະຫັດຜ່ານທາງອີເມລ',
'acct_creation_throttle_hit' => 'ຂໍໂທດຫຼາຍໆ, ທ່ານ ໄດ້ສ້າງ $1 ບັນຊີແລ້ວ. ທ່ານ ບໍ່ສາມາດ ສ້ງບັນຊີໄດ້ອີກ.',
'emailauthenticated'         => 'ອີເມລ ຂອງ ທ່ານ ຖືກຢືນຢັນ ເມື່ອ $1.',
'emailconfirmlink'           => 'ຢືນຢັນ ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານ',
'accountcreated'             => 'ບັນຊີ ໄດ້ຖືກສ້າງ ແລ້ວ',
'accountcreatedtext'         => 'ບັນຊີ ຂອງ  $1 ໄດ້ ຖືກສ້າງແລ້ວ.',

# Password reset dialog
'resetpass_submit'  => 'ຕັ້ງລະຫັດຜ່ານ ແລະ ເຊັນເຂົ້າ',
'resetpass_success' => 'ສຳເລັດການປ່ຽນແປງ ລະຫັດຜ່ານຂອງທ່ານແລ້ວ! ດຽວນີ້ ທ່ານ ເຊັ່ນເຂົ້າ ໃນ ...',

# Edit page toolbar
'bold_sample'     => 'ໂຕໜັງສືເຂັ້ມ',
'bold_tip'        => 'ໂຕໜັງສືເຂັ້ມ',
'headline_sample' => 'ສຳຄັນ',

# Edit pages
'summary'                  => 'ຄຳອະທິບາຍໂດຍຍໍ່',
'minoredit'                => 'ນີ້ແມ່ນການດັດແກ້ເລັກນ້ອຍ',
'watchthis'                => 'ຕິດຕາມໜ້ານີ້',
'savearticle'              => 'ບັນທຶກ',
'preview'                  => 'ລອງເບິ່ງຜົນ',
'showpreview'              => 'ລອງເບິ່ງຜົນ',
'showdiff'                 => 'ສະແດງສ່ວນຕ່າງ',
'anoneditwarning'          => "'''ເຕືອນ:''' ທ່ານ ບໍ່ໄດ້ເຊັນເຂົ້າ. ທີ່ຢູ່ IP ຂອງ ທ່ານ ຈະຖືກບັນທຶກໄວ້ ໃນ ປະຫວັດການດັດແກ້ ຂອງ ໜ້ານີ້.",
'blockedtitle'             => 'ຜູ້ໃຊ້ຖືກຫ້າມ',
'blockededitsource'        => "ເນື້ອໃນ ຂອງ '''ການດັດແປງໂດຍທ່ານ '''ຕໍ່ກັບ '''$1''' ມີດັ່ງລຸ່ມນີ້:",
'whitelistedittitle'       => 'ຕ້ອງເຊັນເຂົ້າ ຈຶ່ງຈະສາມາດດັດແກ້ໄດ້',
'whitelistedittext'        => 'ທ່ານ ຈະຕ້ອງ  $1 ເພື່ອ ຈະດັດແກ້.',
'whitelistreadtitle'       => 'ຈະຕ້ອງ ເຊັນເຂົ້າ ເພື່ອອ່ານໜ້ານີ້',
'whitelistacctitle'        => 'ທ່ານຖືກຫ້າມສ້າງບັນຊີ',
'confirmedittitle'         => 'ຕ້ອງຢືນຢັນ ທີ່ຢູ່ ອີເມລ ຈຶ່ງຈະສາມາດ ດັດແກ້',
'loginreqtitle'            => 'ຈຳເປັນຕ້ອງ ເຊັນເຂົ້າ',
'loginreqlink'             => 'ເຊັນເຂົ້າ',
'accmailtitle'             => 'ໄດ້ສົ່ງ ລະຫັດຜ່ານ ໄປແລ້ວ.',
'accmailtext'              => 'ລະຫັດຜ່ານ ຂອງ "$1" ໄດ້ຖືກສົ່ງໄປ  $2 ແລ້ວ.',
'newarticle'               => '(ໃໝ່)',
'newarticletext'           => "ທ່ານ ໄດ້ມາຮອດ ໜ້າທີ່ຍັງບໍ່ໄດ້ຖືກສ້າງຂຶ້ນເທື່ອ. ທ່ານ ສາມາດເລີ່ມ ສ້າງໜ້າ ໂດຍ ພິມໃສ່ ກັບ ຂ້າງລຸ່ມ.(ເບິ່ງລາຍລະອຽດຕື່ມ ທີ່ [[{{MediaWiki:helppage}}|ໜ້າຊ່ວຍເຫຼືອ]]).
ຖ້າ ທ່ານ ມາຮອດນີ້ ໂດຍຄວາມຜິດພາດ, ກະລຸນາ ກົດ ປຸ່ມ '''ກັບຄືນ''', ຢູ່ ໂປຣແກຣມ ທ່ອງເວັບ ຂອງທ່ານ.",
'previewnote'              => '<strong>ນີ້ ແມ່ນ ການລອງເບິ່ງຜົນເທົ່ານັ້ນ; ການດັດແກ້ຍັງບໍ່ທັນຖືກບັນທຶກ!</strong>',
'editing'                  => 'ພວມດັດແກ້ $1',
'editingsection'           => 'ພວມດັດແກ້ $1 (ພາກ)',
'yourtext'                 => 'ເນື້ອໃນ',
'yourdiff'                 => 'ສ່ວນຕ່າງ',
'copyrightwarning'         => 'ກະລຸນາ ເຂົ້າໃຈວ່າ ທຸກໆ ການປະກອບສ່ວນຕໍ່ກັບ {{SITENAME}} ຈະຖືກ ເປີດເຜີຍ ພາຍໃຕ້  $2 (ເບິ່ງ ລາຍລະອຽດ ທີ່ $1). ຖ້າ ທ່ານ ບໍ່ປະສົງ ໃຫ້ ສິ່ງທີ່ ທ່ານ ຂຽນ ຖືກດັດແກ້ ຫຼື ແຈກຢາຍ ອີກ ຕາມຊອບໃຈ, ທ່ານ ບໍ່ຄວນ ຂຽນຢູ່ນີ້. <br />
ທ່ານ ຈະຕ້ອງ ສັນຍາອີກວ່າ, ທ່ານ ຂຽນມັນຂຶ້ນມາເອງ ຫຼື ກ່າຍມັນມາຈາກ ໂດເມນ ສາທາລະນະ ຫຼື ສິ່ງບໍ່ເສຍຄ່າ ຄ້າຍຄືກັນ.
<strong>ກະລຸນາ ຢ່າ ສົ່ງສິ່ງທີ່ ຖືກສະຫງວນ ລິຂະສິດ ໂດຍ ບໍ່ໄດ້ຮັບອະນຸຍາດ!</strong>',
'copyrightwarning2'        => 'ກະລຸນາ ເຂົ້າໃຈວ່າ ທຸກໆ ການປະກອນສ່ວນ ຕໍ່ {{SITENAME}} ອາດຖືກ ດັດແກ້, ປ່ຽນແປງ, ຫຼື ລຶບ ໂດຍ ຜູ້ປະກອບສ່ວນອື່ນ. ຖ້າ ທ່ານ ບໍ່ປະສົງ ໃຫ້ ສິ່ງທີ່ ທ່ານ ຂຽນ ຖືກດັດແກ້ ຫຼື ແຈກຢາຍ ອີກ ຕາມຊອບໃຈ, ທ່ານ ບໍ່ຄວນ ຂຽນຢູ່ນີ້. <br />
ທ່ານ ຈະຕ້ອງ ສັນຍາອີກວ່າ, ທ່ານ ຂຽນມັນຂຶ້ນມາເອງ ຫຼື ກ່າຍມັນມາຈາກ ໂດເມນ ສາທາລະນະ ຫຼື ສິ່ງບໍ່ເສຍຄ່າ ຄ້າຍຄືກັນ.  (ເບິ່ງ ລາຍລະອຽດ ທີ່ $1).
<strong>ກະລຸນາ ຢ່າ ສົ່ງສິ່ງທີ່່ ຖືກສະຫງວນ ລິຂະສິດ ໂດຍ ບໍ່ໄດ້ຮັບອະນຸຍາດ!</strong>',
'protectedpagewarning'     => '<strong>ເອົາໃຈໃສ່ : ໜ້ານີ້ໄດ້ຖຶກປົກປ້ອງ ສະເພາະຜູ້ບໍລິຫານລະບົບເທົ່ານັ້ນທີ່ສາມາດດັດແກ້ໜ້ານີ້ໄດ້ ຫາກກະລຸນາອຍາກປ່ຽນໜ້ານີ້ ຕິດຕໍ່ຜູ້ບໍລິຫານລະບົບ</strong>',
'semiprotectedpagewarning' => "'''ໝາຍເຫດ:''' ໜ້ານີ້ໄດ້ຖືກປົກປ້ອງ ແລະ ມີແຕ່ຜູ້ໃຊ້ທີ່ສ້າງບັນຊີແລ້ວເທົ່ານັ້ນ ສາມາດດັດແກ້ໄດ້.",
'templatesused'            => 'ແມ່ແບບໄດ້ໃຊ້ໃນໜ້ານີ້:',
'templatesusedpreview'     => 'ແມ່ແບບໃຊ້ໃນການລອງເບິ່ງຜົນນີ້:',
'templatesusedsection'     => 'ແມ່ແບບໄດ້ໃຊ້ໃນພາກນີ້:',
'template-protected'       => '(ປົກປ້ອງ)',
'template-semiprotected'   => '(ເຄິ່ງປົກປ້ອງ)',

# Account creation failure
'cantcreateaccounttitle' => 'ບໍ່ສາມາດສ້າງ ບັນຊີ ໄດ້',
'cantcreateaccount-text' => "ການສ້າງບັນຊີ ຈາກ IP ນີ້ (<b>$1</b>) ໄດ້ຖືກຫ້າມ ໂດຍ [[ຜູ້ໃຊ້:$3|$3]].

ເຫດຜົນ ໃຫ້ໄວ້ ໂດຍ $3 ແມ່ນ ''$2''",

# History pages
'nohistory'  => 'ຍັງບໍ່ມີ ປະຫວັດການດັດແກ້ໜ້ານີ້ເທື່ອ.',
'cur'        => 'ດຽວນີ້',
'next'       => 'ຕໍ່ໄປ',
'last'       => 'ຫຼ້າສຸດ',
'page_first' => 'ທຳອິດ',
'page_last'  => 'ສຸດທ້າຍ',
'histlegend' => 'ເລືອກສ່ວນຕ່າງ: ເລືອກກັບວົງມົນ ລະຫວ່າງສະບັບ ຢາກສົມທຽບ ແລ້ວ ກົດເອັນເຕີ ຫຼື ປຸ່ນຢູ່ທາງລຸ່ມ.<br />
ຄວາມໝາຍ: (ດຽວນີ້) = ສ່ວນຕ່າງສົມທຽບໃສ່ສະບັບດຽວນີ້,
(ຫຼ້າສຸດ) = ສ່ວນຕ່າງສົມທຽບໃສ່ສະບັບກ່ອນໜ້ານີ້, M = ດັດແກ້ເລັກນ້ອຍ.',
'histfirst'  => 'ທຳອິດ',
'histlast'   => 'ຫຼ້າສຸດ',

# Revision deletion
'rev-delundel' => 'ສະແດງ/ເຊື່ອງ',

# Diffs
'compareselectedversions' => 'ສົມທຽບ ລະຫວ່າງ ສະບັບເລືອກ',

# Search results
'searchresulttext' => 'ສຳຫຼັບ ຂໍ້ມູນເພີ່ມເຕີມ ກ່ຽວກັບ ການຊອກຫາ ຢູ່ {{SITENAME}}, ກະລຸນາເບິ່ງ [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'   => "ທ່ານ ຊອກຫາ '''[[:$1]]'''",
'noexactmatch'     => "'''ຍັງບໍ່ມີໜ້າ ຊື່ວ່າ  \"\$1\".''' ທ່ານ ສາມາດ [[:\$1|ສ້າງໜ້ານີ້ໄດ້]].",
'prevn'            => '$1 ກ່ອນໜ້າ',
'nextn'            => '$1 ຕໍ່ໄປ',
'viewprevnext'     => 'ເບິ່ງ ($1) ($2) ($3).',
'powersearch'      => 'ຊອກຫາ',

# Preferences page
'preferences'           => 'ການຕັ້ງຄ່າ',
'mypreferences'         => 'ຕັ້ງຄ່າ',
'changepassword'        => 'ປ່ຽນລະຫັດຜ່ານ',
'skin'                  => 'ລວດລາຍ',
'math'                  => 'ຄະນິດສາດ',
'dateformat'            => 'ແບບວັນເດືອນປີ',
'datedefault'           => 'ແນວໃດກໍ່ໄດ້',
'datetime'              => 'ວັນ ແລະ ເວລາ',
'math_syntax_error'     => 'ຜິດຫຼັກໄວຍະກອນ',
'prefs-personal'        => 'ຂໍ້ມູນຫຼັກຜູ້ໃຊ້',
'prefs-rc'              => 'ການດັດແກ້ຫຼ້າສຸດ',
'prefs-watchlist'       => 'ລາຍການຕິດຕາມ',
'prefs-watchlist-days'  => 'ຈຳນວນມື້ສະແດງ ໃນ ລາຍການຕິດຕາມ:',
'prefs-watchlist-edits' => 'ຈຳນວນການດັດແກ້ ສະແດງ ໃນ ລາຍການຕິດຕາມທີ່ຂະຫຍາຍອອກ:',
'prefs-misc'            => 'ແລະອື່ນໆ',
'saveprefs'             => 'ບັນທຶກ',
'resetprefs'            => 'ເລີ່ມຄືນໃໝ່',
'oldpassword'           => 'ລະຫັດຜ່ານເກົ່າ:',
'newpassword'           => 'ລະຫັດຜ່ານໃໝ່:',
'retypenew'             => 'ພິມລະຫັດຜ່ານໃໝ່ອີກ:',
'textboxsize'           => 'ການດັດແກ້',
'rows'                  => 'ແຖວ:',
'columns'               => 'ຖັນ:',
'searchresultshead'     => 'ຊອກຫາ',
'resultsperpage'        => 'ຈຳນວນຜົນການຊອກເຫັນ ຕໍ່ ໜ້າ:',
'contextlines'          => 'ຈຳນວນແຖວ ຕໍ່ ຜົນການຊອກເຫັນ:',
'recentchangesdays'     => 'ຈຳນວນມື້ສະແດງໃນການປ່ຽນແປງຫຼ້າສຸດ:',
'recentchangescount'    => 'ຈຳນວນການດັດແກ້ສະແດງໃນການປ່ຽນແປງຫຼ້າສຸດ:',
'savedprefs'            => 'ການຕັ້ງຄ່າຂອງທ່ານໄດ້ຖືກບັນທຶກແລ້ວ.',
'timezonelegend'        => 'ເຂດເວລາ',
'timezonetext'          => 'ຈຳນວນຊົ່ວໂມງແຕກຕ່າງ ລະຫວ່າງເວລາທ້ອງຖິ່ນ ແລະ ເວລາ ຂອງ ເຊີບເວີ (UTC).',
'localtime'             => 'ເວລາທ້ອງຖິ່ນ',
'timezoneoffset'        => 'ຫ່າງກັນ¹',
'servertime'            => 'ເວລາເຊີເວີ',
'guesstimezone'         => 'ເອົາເວລາຈາກໂປຣແກຣມທ່ອງເວັບ',
'allowemail'            => 'ອະນຸຍາດ ໃຫ້ຜູ້ໃຊ້ອື່ນ ສົ່ງອີເມລຫາຂ້ອຍ',
'defaultns'             => 'ຄົ້ນຫາ ໃນ ຂອບເຂດຊື່ ນີ້ ເວລາບໍ່ມີການລະບຸ:',
'files'                 => 'ໄຟລ໌',

# User rights
'editinguser' => 'ຜູ້ໃຊ້ <b>$1</b> ພວມດັດແກ້',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|ການປ່ຽນແປງ|ການປ່ຽນແປງ}}',
'recentchanges'     => 'ການດັດແກ້ຫຼ້າສຸດ',
'recentchangestext' => 'ເບິ່ງ ການປ່ຽນແປງ ຫຼ້າສຸດ ຢູ່ ໜ້າວິກີນີ້.',
'rclistfrom'        => 'ສະແດງ ການປ່ຽນແປງ ເລີ່ມແຕ່  $1',
'rcshowhideminor'   => '$1 ການດັດແກ້ເລັກນ້ອຍ',
'rcshowhideliu'     => '$1 ຜູ້ໃຊ້ໄດ້ເຊັນເຂົ້າ',
'rcshowhideanons'   => '$1 ຜູ້ໃຊ້ບໍ່ສະແດງຊື່',
'rcshowhidemine'    => '$1 ການດັດແກ້ໂດຍຂ້ອຍ',
'rclinks'           => 'ສະແດງ $1ການປ່ຽນແປງ ຫຼ້າສຸດ ໃນ  $2ມື້ຫຼ້າສຸດ<br />$3',
'diff'              => 'ສ່ວນຕ່າງ',
'hist'              => 'ປະຫວັດ',
'hide'              => 'ເຊື່ອງ',
'show'              => 'ສະແດງ',
'newpageletter'     => 'ໃໝ່',

# Recent changes linked
'recentchangeslinked' => 'ການດັດແກ້ທີ່ກ່ຽວຂ້ອງ',

# Upload
'upload'            => 'ອັບໂຫຼດໄຟລ໌',
'filedesc'          => 'ສະຫຼຸບ',
'fileuploadsummary' => 'ສະຫຼຸບ:',
'badfilename'       => 'ຊື່ໄຟລ໌ ໄດ້ຖືກປ່ຽນເປັນ  "$1".',
'large-file'        => 'ໄຟລ໌ບໍ່ຄວນມີຂະໜາດໃຫຍ່ກວ່າ $1 ແຕ່ໄຟລ໌ນີ້ມີຂະໜາດ  $2',
'largefileserver'   => 'ໄຟລ໌ມີຂະໜາດໃຫຍ່ກວ່າຄ່າທີ່ໃຊ້ໄດ້',
'filepageexists'    => 'ມີໜ້າສຳຫຼັບຫັວເຮື່ອງນີ້ອຍູ່ແລ້ວ (ບໍ່ໃຊ່ໄຟລ໌) ກະລຸນາຕວດສອບ <strong>$1</strong> ຫາກຄຸນບໍ່ແນ່ໃຈວ່າຕ້ອງການຈະປ່ຽນໜ້ານີ້',
'savefile'          => 'ບັນທຶກໄຟລ໌',
'watchthisupload'   => 'ຕິດຕາມໜ້ານີ້',

# Special:Imagelist
'imagelist' => 'ລາຍຊື່ໄຟລ໌',

# Image description page
'nolinkstoimage'   => 'ບໍ່ມີໜ້າໃດ ລິ້ງຄ໌ ຫາ ໄຟລ໌ນີ້.',
'noimage'          => 'ຍັງບໍ່ມີ ໄຟລ໌ຊື່ນີ້ ເທື່ອ, ທ່ານ ສາມາດ  $1.',
'noimage-linktext' => 'ອັບໂຫຼດມັນ',

# List redirects
'listredirects' => 'ລາຍການການໂອນໜ້າ',

# Unused templates
'unusedtemplates' => 'ແມ່ແບບບໍ່ໄດ້ໃຊ້',

# Random page
'randompage' => 'ໜ້າໃດໜຶ່ງ',

# Random redirect
'randomredirect' => 'ໂອນໄປໜ້າໃດໜຶ່ງ',

# Statistics
'statistics' => 'ສະຖິຕິ',

'disambiguations'      => 'ໜ້າແກ້ຄວາມກຳກວມ',
'disambiguations-text' => "ໜ້າຕໍ່ໄປນີ້ເຊື່ອມໂຍງໄປຍັງ '''ໜ້າແກ້ຄວາມກຳກວມ'''&nbsp;ຊຶ່ງຄວນດັດແກ້ລິງຄ໌ໃຫ້ເຊື່ອມໂຍງໄປທີ່ໜ້າອື່ນທີ່ເໝາະສົມ<br />
ໜ້າໃດທີ່ຮຽກໃຊ້ແມ່ແບບ [[{{ns:mediawiki}}:Disambiguationspage|ແກ້ກຳກວມ]] ໜ້າເຫຼ່ານັ້ນຖຶກເປັນໜ້າແກ້ຄວາມກຳກວມ",

'doubleredirects' => 'ໂອນໜ້າ 2 ຄັ້ງ',

'brokenredirects'        => 'ການໂອນບໍ່ມີໜ້າປາຍທາງ',
'brokenredirectstext'    => 'ການໂອນໜ້ານີ້ ເຊື່ອມຕໍ່ໄປຫາໜ້າ ທີ່ ຍັງບໍ່ມີເທື່ອ:',
'brokenredirects-edit'   => '(ດັດແກ້)',
'brokenredirects-delete' => '(ລຶບ)',

'withoutinterwiki'        => 'ບັນດາໜ້າ ທີ່ບໍ່ມີລິ້ງຄ໌ ຫາ ສະບັບພາສາອື່ນ',
'withoutinterwiki-header' => 'ໜ້າ ຕໍ່ໄປນີ້ ບໍ່ມີ ລິ້ງຄ໌ ຫາ ບົດຄວາມສະບັບພາສາອື່ນໆ:',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ໄບຕ໌|ໄບຕ໌}}',
'ncategories'             => '$1 {{PLURAL:$1|ໝວດ|ໝວດ}}',
'nlinks'                  => '$1 {{PLURAL:$1|ລິ້ງຄ໌|ລິ້ງຄ໌}}',
'lonelypages'             => 'ໜ້າບໍ່ມີໜ້າໃດເຊື່ອມຕໍ່ຫາ',
'uncategorizedpages'      => 'ໜ້າບໍ່ໄດ້ຈັດໝວດ',
'uncategorizedcategories' => 'ໝວດບໍ່ໄດ້ຈັດໝວດ',
'uncategorizedimages'     => 'ຮູບບໍ່ໄດ້ຈັດໝວດ',
'unusedcategories'        => 'ໝວດບໍ່ໄດ້ໃຊ້',
'unusedimages'            => 'ໄຟລ໌ບໍ່ໄດ້ໃຊ້',
'wantedcategories'        => 'ໝວດ ພວມຕ້ອງການ',
'wantedpages'             => 'ໜ້າ ພວມຕ້ອງການ',
'mostlinked'              => 'ໜ້າທີ່ມີການເຊື່ອມຕໍ່ຫາຫຼາຍທີ່ສຸດ',
'mostlinkedcategories'    => 'ໝວດທີ່ມີການເຊື່ອມຕໍ່ຫາຫຼາຍທີ່ສຸດ',
'mostcategories'          => 'ບົດຄວາມ ທີ່ມີຫຼາຍ ໝວດ ທີ່ສຸດ',
'mostimages'              => 'ຮູບທີ່ມີການເຊື່ອມຕໍ່ຫາຫຼາຍທີ່ສຸດ',
'mostrevisions'           => 'ບົດຄວາມ ທີ່ມີການດັດແກ້ຫຼາຍເທື່ອທີ່ສຸດ',
'prefixindex'             => 'ດັດຊະນີຄຳນຳໜ້າ',
'shortpages'              => 'ໜ້າເນື້ອໃນສັ້ນ',
'longpages'               => 'ໜ້າເນື້ອໃນຍາວ',
'deadendpages'            => 'ໜ້າບໍ່ການເຊື່ອມຕໍ່ຫາໜ້າອື່ນ',
'protectedpages'          => 'ບັນດາໜ້າຖືກປົກປ້ອງ',
'listusers'               => 'ລາຍຊື່ຜູ້ໃຊ້',
'specialpages'            => 'ໜ້າພິເສດ',
'spheading'               => 'ໜ້າພິເສດ ສຳຫຼັບ ຜູ້ໃຊ້ທຸກໆທ່ານ',
'restrictedpheading'      => 'ບັນດາໜ້າຈຳກັດພິເສດ',
'newpages'                => 'ໜ້າສ້າງໃໝ່',
'newpages-username'       => 'ຊື່ຜູ້ໃຊ້:',
'ancientpages'            => 'ບັນດາໜ້າເກົ່າທີ່ສຸດ',
'move'                    => 'ຍ້າຍ',
'movethispage'            => 'ຍ້າຍໜ້ານີ້',

# Book sources
'booksources'    => 'ປຶ້ມອ້າງອີງ',
'booksources-go' => 'ໄປ',

# Special:Log
'log'               => 'ບັນທຶກ',
'all-logs-page'     => 'ທຸກໆຂໍ້ມູນບັນທຶກການນຳໃຊ້',
'log-search-legend' => 'ຊອກຫາ ໃນ ບັນທຶກການນຳໃຊ້',
'log-search-submit' => 'ໄປ',
'logempty'          => 'ຍັງບໍ່ພົບເນື້ອໃນບັນທຶກ.',

# Special:Allpages
'allpages'       => 'ທຸກໆໜ້າ',
'alphaindexline' => '$1 ຫາ $2',
'nextpage'       => 'ໜ້າຕໍ່ໄປ ($1)',
'allpagesfrom'   => 'ສະແດງທຸກໆໜ້າ ເລີ່ມແຕ່:',
'allarticles'    => 'ທຸກໆບົດຄວາມ',
'allpagesprev'   => 'ກ່ອນໜ້າ',
'allpagesnext'   => 'ຕໍ່ໄປ',
'allpagessubmit' => 'ໄປ',
'allpagesprefix' => 'ສະແດງ ໜ້າ ຂຶ້ນຕົ້ນດ້ວຍ:',

# Special:Listusers
'listusers-submit' => 'ສະແດງ',

# E-mail user
'emailuser'      => 'ສົ່ງອີເມລ ຫາ ຜູ້ໃຊ້ນີ້',
'emailpage'      => 'ສົ່ງອີເມລ ຫາ ຜູ້ໃຊ້',
'noemailtitle'   => 'ບໍ່ມີ ທີ່ຢູ່ອີເມລ',
'emailfrom'      => 'ຈາກ',
'emailto'        => 'ຫາ',
'emailsubject'   => 'ຫົວເລື້ອງ',
'emailmessage'   => 'ຂໍ້ຄວາມ',
'emailsend'      => 'ສົ່ງ',
'emailccme'      => 'ສົ່ງສຳເນົາ ຂໍ້ຄວາມ ມາໃຫ້ ຂ້ອຍ.',
'emailccsubject' => 'ສຳເນົາ ຂໍ້ຄວາມທ່ານ ຫາ $1: $2',
'emailsent'      => 'ອີເມລ ໄດ້ຖືກສົ່ງໄປແລ້ວ',
'emailsenttext'  => 'ອີເມລ ຂອງ ທ່ານ ໄດ້ຖືກສົ່ງໄປແລ້ວ.',

# Watchlist
'watchlist'            => 'ລາຍການຕິດຕາມ',
'mywatchlist'          => 'ລາຍການຕິດຕາມ',
'watchnologin'         => 'ບໍ່ໄດ້ເຊັນເຂົ້າ',
'addedwatch'           => 'ເພີ່ມໃສ່ລາຍການຕິດຕາມແລ້ວ',
'addedwatchtext'       => "ໜ້າ \"[[:\$1]]\" ໄດ້ຖືກເພີ່ມໃສ່  [[Special:Watchlist|ລາຍການຕິດຕາມ]] ຂອງ ທ່ານ.
ການປ່ຽນແປງ ໃນໜ້ານີ້ ແລະ ໜ້າສົນທະນາ ຂອງ ມັນ ຈະຖືກ ສະແດງຢູ່ຫັ້ນ,
ແລະ ໜ້າ ຈະປະກົດ '''ເຂັ້ມ'''ຂຶ້ນ ໃນ [[Special:Recentchanges|ລາຍການການດັດແກ້ຫຼ້າສຸດ]] ເພື່ອ ໃຫ້ ສາມາດຈຳແນກໄດ້ງ່າຍຂຶ້ນ.

ຖ້າ ທ່ານ ຢາກເອົາມັນອອກ ຈາກ ລາຍການຕິດຕາມ ຂອງ ທ່ານ, ທ່ານ ສາມາດ ກົດ \"ເຊົາຕິດຕາມ\" ຢູ່ ທາງຂ້າງ.",
'removedwatch'         => 'ເອົາອອກຈາກລາຍການຕິດຕາມ',
'removedwatchtext'     => 'ໜ້າ "[[:$1]]" ໄດ້ຖືກເອົາອອກຈາກ ລາຍການຕິດຕາມ ຂອງ ທ່ານ.',
'watch'                => 'ຕິດຕາມ',
'watchthispage'        => 'ຕິດຕາມໜ້ານີ້',
'unwatch'              => 'ເຊົາຕິດຕາມ',
'watchnochange'        => 'ບໍ່ມີໜ້າທີ່ທ່ານຕິດຕາມໃດໆ ຖືກດັດແປງໃນໄລຍະສະແດງຂ້າງເທິງນີ້.',
'wlheader-showupdated' => "* ບັນດາ ໜ້າ ທີ່ມີການປ່ຽນແປງ ຫຼັງຈາກທີ່ທ່ານ ເຂົ້າຊົມຫຼ້າສຸດ ຈະຖືກສະແດງເປັນ ໂຕໜັງສື  '''ເຂັ້ມ'''",
'wlshowlast'           => 'ສະແດງ $1 ຊົ່ວໂມງຫຼ້າສຸດ $2 ມື້ຫຼ້າສຸດ $3',
'watchlist-show-bots'  => 'ສະແດງການດັດແກ້ ໂດຍ ບອທ໌',
'watchlist-hide-bots'  => 'ເຊື່ອງການດັດແກ້ ໂດຍ ບອທ໌',
'watchlist-show-own'   => 'ສະແດງການດັດແກ້ໂດຍຂ້ອຍ',
'watchlist-hide-own'   => 'ເຊື່ອງການດັດແກ້ ໂດຍ ຂ້ອຍ',
'watchlist-show-minor' => 'ສະແດງການດັດແກ້ເລັກນ້ອຍ',
'watchlist-hide-minor' => 'ເຊື່ອງການດັດແກ້ເລັກນ້ອຍ',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'ພວມຕິດຕາມ...',

'enotif_newpagetext' => 'ນີ້ແມ່ນໜ້າໃໝ່.',
'changed'            => 'ປ່ຽນແລ້ວ',
'created'            => 'ສ້າງແລ້ວ',
'enotif_lastvisited' => 'ເບິ່ງ $1 ເພື່ອ ທຸກໆການປ່ຽນແປງ ຕັ້ງແຕ່ເທື່ອສຸດທ້າຍ ທີ່ ທ່ານເຂົ້າຫາ.',

# Delete/protect/revert
'confirm'            => 'ຢືນຢັນ',
'actioncomplete'     => 'ສຳເລັດ',
'dellogpage'         => 'ບັນທຶກ ການລຶບ',
'dellogpagetext'     => 'ຂ້າງລຸ່ມ ແມ່ນ ລາຍການ ການລຶບຫຼ້າສຸດ.',
'deletionlog'        => 'ບັນທຶກ ການລຶບ',
'deletecomment'      => 'ເຫດຜົນໃນການລຶບ',
'cantrollback'       => 'ບໍ່ສາມາດ ປ່ຽນການດັດແກ້ກັບຄືນໄດ້; ຜູ້ປະກອບສ່ວນຫຼ້າສຸດ ແມ່ນ ຜູ້ຂຽນພຽງຜູ້ດຽວ ຂອງ ໜ້ານີ້.',
'protectedarticle'   => 'ໄດ້ປົກປ້ອງ "[[$1]]"',
'unprotectedarticle' => 'ໄດ້ເຊົາປົກປ້ອງ "[[$1]]"',
'protect-title'      => 'ກຳລັງປົກປ້ອງໜ້າ "$1"',
'protect-legend'     => 'ຢືນຢັນ ການປົກປ້ອງ',
'restriction-level'  => 'ລະດັບການຈຳກັດ:',

# Restrictions (nouns)
'restriction-edit' => 'ດັດແກ້',

# Undelete
'undelete'               => 'ຮຽກຄືນ',
'viewdeletedpage'        => 'ເບິ່ງໜ້າທີ່ຖືກລຶບ',
'undeletepagetext'       => 'ໜ້າຕ່ອໄປນີ້ຖຶກລຶບໄປແຕ່ຍັງຄົງຢູ່ໃນກຸທີ່ສາມາດຮຽກຄືນໄດ້ (ກຸຂໍ້ມູນອາດຖຶກລຶບເປັນລະຍະ)',
'cannotundelete'         => 'ບໍ່ສາມາດ ກັບຄືນ ຫາສະບັບກ່ອນການລຶບ; ບາງຄົນອາດເຮັດກ່ອນແລ້ວ.',
'undelete-search-submit' => 'ຊອກຫາ',

# Namespace form on various pages
'namespace'      => 'ຂອບເຂດຊື່:',
'blanknamespace' => '(ຫຼັກ)',

# Contributions
'contributions' => 'ການປະກອບສ່ວນ',
'mycontris'     => 'ປະກອບສ່ວນ',
'contribsub2'   => 'ສຳຫຼັບ $1 ($2)',
'nocontribs'    => 'ຍັງບໍ່ມີການປ່ຽນແປງ ໃນນີ້.',
'month'         => 'ແຕ່ເດືອນ (ແລະກ່ອນໜ້ານັ້ນ):',
'year'          => 'ແຕ່ປີ (ແລະກ່ອນໜ້ານັ້ນ):',

'sp-contributions-newbies'  => 'ສະແດງສະເພາະ ການປະກອບສ່ວນ ໂດຍ ບັນຊີໃໝ່',
'sp-contributions-blocklog' => 'ບັນທຶກການຫ້າມ',
'sp-contributions-search'   => 'ຊອກຫາ ການປະກອບສ່ວນ',
'sp-contributions-username' => 'ທີ່ຢູ່ IP ຫຼື ຊື່ຜູ້ໃຊ້:',
'sp-contributions-submit'   => 'ຊອກຫາ',

# What links here
'whatlinkshere'  => 'ໜ້າທີ່ເຊື່ອມຕໍ່ມາ',
'nolinkshere'    => "ບໍ່ມີໜ້າລິ້ງຄ໌ ຫາ '''[[:$1]]'''.",
'nolinkshere-ns' => "ບໍ່ມີໜ້າລິ້ງຄ໌ ຫາ '''[[:$1]]''' ໃນ ຂອບເຂດຊື່ ທີ່ ທ່ານເລືອກ.",

# Block/unblock
'blockip'           => 'ຫ້າມຜູ້ໃຊ້ນີ້',
'ipbsubmit'         => 'ຫ້າມຜູ້ໃຊ້ນີ້',
'badipaddress'      => 'ທີ່ຢູ່ IP ບໍ່ຖືກຕ້ອງ',
'blockipsuccesssub' => 'ຫ້າມສຳເລັດ',
'ipblocklist'       => 'ລາຍການ ທີ່ຢູ່ IP ແລະ ຊື່ຜູ້ໃຊ້ ທີ່ຖືກຫ້າມ',
'blocklistline'     => '$1, $2 ຫ້າມ $3 ($4)',
'blocklink'         => 'ຫ້າມ',
'contribslink'      => 'ປະກອບສ່ວນ',
'autoblocker'       => 'ຫ້າມໂດຍອັດຕາໂນມັດ ຍ້ອນ ທີ່ຢູ່ IP ຂອງທ່ານ ຖືກໃຊ້ໂດຍ "[[User:$1|$1]]" ໃນໄລຍະຫຼັງ. ເຫດຜົນໃນການຫ້າມ  $1 ແມ່ນ : "$2"',
'blocklogpage'      => 'ບັນທຶກການຫ້າມ',
'blocklogentry'     => 'ໄດ້ຫ້າມ "[[$1]]" ຈົນຮອດ $2 $3',

# Move page
'move-page-legend'        => 'ຍ້າຍໜ້າ',
'movearticle'             => 'ຍ້າຍໜ້າ:',
'movenologin'             => 'ຍັງບໍ່ໄດ້ເຊັນເຂົ້າເທື່ອ',
'newtitle'                => 'ຫາ ຫົວຂໍ້ໃໝ່',
'move-watch'              => 'ຕິດຕາມໜ້ານີ້',
'movepagebtn'             => 'ຍ້າຍໜ້າ',
'movepage-moved'          => '<big>\'\'\'"$1" ໄດ້ຖືກຍ້າຍໄປ "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'ມີໜ້າຊືນີ້ແລ້ວ, ຫຼື 
ຊື່ ທີ່ທ່ານເລືອກບໍ່ຖືກຕ້ອງ.
ກະລຸນາເລືອກຊື່ໃໝ່.',
'movedto'                 => 'ໄດ້ຖືກຍ້າຍໄປ',
'movetalk'                => 'ຍ້າຍທັງ ໜ້າສົນທະນາ ທີ່ ກ່ຽວພັນ.',
'talkpagemoved'           => 'ໜ້າສົນທະນາ ໄດ້ຖືກຍ້າຍແລ້ວ ເຊັ່ນດຽວກັນ.',
'1movedto2'               => '[[$1]] ຍ້າຍໄປ [[$2]]',
'1movedto2_redir'         => '[[$1]] ຍ້າຍໄປ [[$2]] ທັບ ການໂອນໜ້າ',
'movelogpage'             => 'ບັນທຶກການຍ້າຍ',
'movereason'              => 'ເຫດຜົນ:',
'delete_and_move'         => 'ລຶບ ແລະ ຍ້າຍ',
'delete_and_move_confirm' => 'ແມ່ນແລ້ວ, ລຶບໜ້ານີ້',
'delete_and_move_reason'  => 'ລຶບແລ້ວ ເພື່ອ ຍ້າຍໜ້າອື່ນ ມານີ້',

# Namespace 8 related
'allmessages'         => 'ຂໍ້ຄວາມ ຂອງ ລະບົບ',
'allmessagesname'     => 'ຊື່',
'allmessagesdefault'  => 'ຂໍ້ຄວາມເດີມ',
'allmessagescurrent'  => 'ຂໍ້ຄວາມປະຈຸບັນ',
'allmessagesmodified' => 'ສະແດງແຕ່ອັນທີ່ຖືກດັດແປງ',

# Tooltip help for the actions
'tooltip-pt-mytalk'         => 'ໜ້າສົນທະນາຂ້ອຍ',
'tooltip-ca-talk'           => 'ສົນທະນາ ກ່ຽວກັບ ເນື້ອໃນຂອງໜ້າ',
'tooltip-ca-addsection'     => 'ເພີ່ມ ຄຳເຫັນ ໃສ່ ການສົນທະນານີ້.',
'tooltip-ca-move'           => 'ຍ້າຍໜ້ານີ້',
'tooltip-search'            => 'ຄົ້ນຫາ {{SITENAME}}',
'tooltip-p-logo'            => 'ໜ້າຫຼັກ',
'tooltip-n-mainpage'        => 'ໄປເບິ່ງໜ້າຫຼັກ',
'tooltip-t-specialpages'    => 'ລາຍການໜ້າພິເສດທັງໝົດ',
'tooltip-ca-nstab-category' => 'ເບິ່ງ ໜ້າໝວດ',

# Attribution
'anonymous' => 'ຜູ້ໃຊ້ ບໍ່ສະແດງຊື່ ຈາກ {{SITENAME}}',

# Browsing diffs
'nextdiff' => 'ສ່ວນຕ່າງຕໍ່ໄປ →',

# Media information
'imagemaxsize' => 'ກຳນົດຄວາມໃຫຍ່ຂອງ ຮູບ ຫຼື ໜ້າອະທິບາຍຮູບ ບໍ່ໃຫ້ກາຍ:',
'thumbsize'    => 'ຂະໜາດສະແດງ:',

# Special:Newimages
'newimages'         => 'ໄຟລ໌ຮູບໃໝ່',
'newimages-summary' => 'ໜ້າພິເສດນີ້ສະແດງໄຟລ໌ທີ່ຖຶກອັປໂຫຼດຫຼ້າສຸດ',
'noimages'          => 'ບໍ່ມີຫຍັງເບິ່ງ.',
'ilsubmit'          => 'ຄົ້ນຫາ',
'bydate'            => 'ຕາມວັນເວລາ',

# EXIF tags
'exif-imagewidth'       => 'ຄວາມກ້ວາງ',
'exif-imagelength'      => 'ຄວາມສູງ',
'exif-imagedescription' => 'ຫົວຂໍ້ຮູບ',
'exif-artist'           => 'ຜູ້ຂຽນ',
'exif-copyright'        => 'ເຈົ້າຂອງລິຂະສິດ',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ທັງໝົດ',
'imagelistall'     => 'ທັງໝົດ',
'namespacesall'    => 'ທັງໝົດ',

# E-mail address confirmation
'confirmemail'            => 'ຢືນຢັນ ທີ່ຢູ່ ອີເມລ',
'confirmemail_pending'    => '<div class="error">
ລະຫັດຢືນຢັນ ໄດ້ຖືກສົ່ງໄປ ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານ; ຖ້າ ທ່ານ ຫາກໍ່ສ້າງບັນຊີ
, ທ່ານ ຄວນຈະລໍຖ້າໜ້ອຍໜຶ່ງ ກ່ອນຈະ ພະຍາຍາມ
ຂໍລະຫັດຢືນຢັນໃໝ່.
</div>',
'confirmemail_send'       => 'ສົ່ງ ລະຫັດ ຢືນຢັນ',
'confirmemail_sent'       => 'ອີເມລຢືນຢັນ ໄດ້ຖືກສົ່ງໄປແລ້ວ.',
'confirmemail_oncreate'   => 'ລະຫັດ ຢືນຢັນ ໄດ້ຖືກສົງໄປ ຫາ ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານແລ້ວ.
ລະຫັດດັ່ງກ່າວ ບໍ່ຈຳເປັນ ສຳຫຼັບ ການເຊັນເຂົ້າ, ແຕ່ ທ່ານ ຕ້ອງໃຊ້ມັນ ເພື່ອ ໃຊ້ 
ການບໍລິການຕ່າງໆ ທີ່ ອີງໃສ່ ອີເມລ ຢູ່ ວິກີ.',
'confirmemail_sendfailed' => 'ບໍ່ສາມາດ ສົ່ງອີເມລ ຢືນຢັນ. ກະລຸນາກວດເບິ່ງວ່າ ມີໂຕໜັງສືບໍ່ຖືກຕ້ອງ ໃນ ທີ່ຢູ່ອີເມລບໍ່.

ອີເມລ ຖືກສົ່ງກັບຄືນ: $1',
'confirmemail_invalid'    => 'ລະຫັດຢືນຢັນ ບໍ່ຖືກຕ້ອງ. ລະຫັດຢືນຢັນ ອາດຈະກາຍກຳນົດເວລາແລ້ວ.',
'confirmemail_needlogin'  => 'ທ່ານ ຈະຕ້ອງ $1 ເພື່ອ ຢືນຢັນ ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານ.',
'confirmemail_success'    => 'ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານ ໄດ້ຮັບການຢືນຢັນແລ້ວ. ທ່ານ ສາມາດ ເຊັນເຂົ້າ ແລະ ມ່ວນຊື່ນກັບ ວີກີ ໄດ້.',
'confirmemail_loggedin'   => 'ທີ່ຢູ່ ອີເມລ ຂອງ ທ່ານໄດ້ຖືກຢືນຢັນແລ້ວ.',
'confirmemail_error'      => 'ມີອັນໃດອັນໜຶ່ງຜິດພາດ ໃນ ເວລາບັນທຶກ ການຢືນຢັນຂອງທ່ານ.',
'confirmemail_subject'    => '{{SITENAME}} ຢືນຢັນ ທີ່ຢູ່ ອີເມລ',

# Delete conflict
'recreate' => 'ສ້າງຄືນ',

# HTML dump
'redirectingto' => 'ພວມໂອນໄປ ຫາ [[$1]]...',

# action=purge
'confirm_purge_button' => 'ຕົກລົງ',

# AJAX search
'searchcontaining' => "ຊອກຫາ ບົດຄວາມ ທີ່ປະກອບມີ  ''$1''.",
'searchnamed'      => "ຊອກຫາ ບົດຄວາມ ຊື່ ''$1''.",
'articletitles'    => "ບົດຄວາມ ເລີ່ມດ້ວຍ ''$1''",

# Multipage image navigation
'imgmultigo'      => 'ໄປ!',
'imgmultigotopre' => 'ໄປ ຫາ ໜ້າ',

# Auto-summaries
'autosumm-blank'   => 'ພວມລຶບ ທຸກໆເນື້ອໃນ ຈາກ ໜ້ານີ້',
'autosumm-replace' => "ປ່ຽນ ໜ້ານີ້ ໂດຍ  '$1'",
'autoredircomment' => 'ພວມໂອນໄປ ຫາ [[$1]]',
'autosumm-new'     => 'ໜ້າໃໝ່: $1',

# Special:Version
'version' => 'ສະບັບ', # Not used as normal message but as header for the special page itself

# Special:Filepath
'filepath' => 'ທາງໄປຫາໄຟລ໌',

);
