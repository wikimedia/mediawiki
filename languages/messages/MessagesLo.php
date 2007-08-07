<?php
/** Lao (ພາສາລາວ)
  *
  * @addtogroup Language
  *
  */
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

# Bits of text used by many pages
'categories'      => 'ໝວດ',
'pagecategories'  => 'ໝວດ',
'category_header' => 'ບົດຄວາມໃນໝວດ "$1"',
'subcategories'   => 'ໝວດຍ່ອຍ',

'about'          => 'ກ່ຽວກັບ',
'article'        => 'ໜ້າເນື້ອໃນ',
'newwindow'      => '(ເປີດເປັນ ປ່ອງຢ້ຽມ(ວິນໂດ)ໃໝ່ )',
'cancel'         => 'ລົບລ້າງ',
'qbspecialpages' => 'ໜ້າພິເສດ',
'mypage'         => 'ໜ້າຂ້ອຍ',
'mytalk'         => 'ສົນທະນາຂ້ອຍ',
'anontalk'       => 'ສົນທະນາ ສຳຫຼັບ IP ນີ້',
'navigation'     => 'ນຳທິດ',

'returnto'         => 'ກັບໄປ  $1.',
'help'             => 'ຊ່ອຍເຫຼືອ',
'search'           => 'ຊອກຫາ',
'searchbutton'     => 'ຊອກຫາ',
'searcharticle'    => 'ໄປ',
'history'          => 'ປະຫວັດການດັດແກ້',
'history_short'    => 'ປະຫວັດການດັດແກ້',
'printableversion' => 'ສະບັບພິມໄດ້',
'permalink'        => 'ລິ້ງຄ໌ຖາວອນ',
'edit'             => 'ດັດແກ້',
'editthispage'     => 'ດັດແກ້ໜ້ານີ້',
'delete'           => 'ລຶບ',
'deletethispage'   => 'ລຶບໜ້ານີ້',
'protect'          => 'ປົກປ້ອງ',
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
'viewcount'        => 'ໜ້ານີ້ຖືກເຂົ້າເບິ່ງ {{plural:$1|ເທື່ອໜຶ່ງ|$1 ເທື່ອ}}.',
'protectedpage'    => 'ໜ້າຖືກປົກປ້ອງ',
'jumptonavigation' => 'ນຳທິດ',
'jumptosearch'     => 'ຄົ້ນຫາ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'     => 'ກ່ຽວກັບ {{SITENAME}}',
'aboutpage'     => '{{ns:project}}:ກ່ຽວກັບ',
'copyright'     => 'ເນື້ອໃນ ສະໜອງໃຫ້ ພາຍໃຕ້ $1.',
'copyrightpage' => '{{ns:project}}:ລິຂະສິດ',
'edithelppage'  => '{{ns:project}}:ການດັດແກ້',
'helppage'      => '{{ns:project}}:ເນື້ອໃນ',
'mainpage'      => 'ໜ້າຫຼັກ',
'sitesupport'   => 'ບໍລິຈາກ',

'badaccess'        => 'ການອະນຸມັດບໍ່ຖືກຕ້ອງ',
'badaccess-group0' => 'ທ່ານ ບໍ່ໄດ້ຮັບອະນຸຍາດ ໃຫ້ປະຕິບັດການ ທີ່ ທ່ານ ສັ່ງ.',

'youhavenewmessages'  => 'ທ່ານ ມີ $1 ($2).',
'newmessageslink'     => 'ຂໍ້ຄວາມໃໝ່',
'newmessagesdifflink' => 'ການປ່ຽນແປງຫຼ້າສຸດ',
'editsection'         => 'ດັດແກ້',
'editold'             => 'ດັດແກ້',
'editsectionhint'     => 'ດັດແກ້ ພາກ: $1',
'toc'                 => 'ເນື້ອໃນ',
'showtoc'             => 'ສະແດງ',
'hidetoc'             => 'ເຊື່ອງ',
'viewdeleted'         => 'ເບິ່ງ $1 ບໍ?',

# Short words for each namespace, by default used in the 'article' tab in monobook
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
'filerenameerror'    => 'ບໍ່ສາມາດ ປ່ຽນ ຊື່ໄຟລ໌  "$1" ໄປເປັນ  "$2" ໄດ້.',
'cannotdelete'       => 'ບໍ່ສາມາດລຶບໜ້າ ຫຼື ໄຟລ໌ ໄດ້. (ມັນອາດຈະຖືກລຶບໂດຍໃຜຜູ້ໜຶ່ງແລ້ວ.)',
'badtitle'           => 'ຫົວຂໍ້ບໍ່ຖືກຕ້ອງ',
'viewsource'         => 'ເບິ່ງ ຊອສ໌',
'editinginterface'   => "'''ເຕືອນ:''' ທ່ານ ພວມດັດແກ້ ໜ້າ ທີ່ ສະໜອງ ອິນເຕີເຟສ ໂຕໜັງສື ໃຫ້ ຊອຟແວ. ການປ່ຽນແປງ ຂອງ ໜ້ານີ້​ ຈະມີຜົນກະທົບ ຕໍ່ ການປະກົດ ຂອງ ອິນເຕີເຟສ ຂອງ ຜູ້ໃຊ້ອື່ນ.",
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
'alreadyloggedin'            => '<strong>ຜູ້ໃຊ້ $1, ທ່ານ ໄດ້ເຊັນເຂົ້າແລ້ວ!</strong><br />',
'login'                      => 'ເຊັນເຂົ້າ',
'loginprompt'                => 'ທ່ານ ຕ້ອງ ອະນຸຍາດ cookies ເພື່ອ ຈະເຊັນເຂົ້າ {{SITENAME}}.',
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
'extlink_tip'     => 'ລິ້ງຄ໌ພາຍນອກ (remember http:// prefix)',
'headline_sample' => 'ສຳຄັນ',
'math_tip'        => '
== Headline text ==
ສູດ ຄະນິດສາດ (LaTeX)',

# Edit pages
'minoredit'              => 'ນີ້ແມ່ນການດັດແກ້ເລັກນ້ອຍ',
'watchthis'              => 'ຕິດຕາມໜ້ານີ້',
'savearticle'            => 'ບັນທຶກ',
'preview'                => 'ລອງເບິ່ງຜົນ',
'showpreview'            => 'ລອງເບິ່ງຜົນ',
'showdiff'               => 'ສະແດງສ່ວນຕ່າງ',
'anoneditwarning'        => "'''ເຕືອນ:''' ທ່ານ ບໍ່ໄດ້ເຊັນເຂົ້າ. ທີ່ຢູ່ IP ຂອງ ທ່ານ ຈະຖືກບັນທຶກໄວ້ ໃນ ປະຫວັດການດັດແກ້ ຂອງ ໜ້ານີ້.",
'blockedtitle'           => 'ຜູ້ໃຊ້ຖືກຫ້າມ',
'blockededitsource'      => "ເນື້ອໃນ ຂອງ '''ການດັດແປງໂດຍທ່ານ '''ຕໍ່ກັບ '''$1''' ມີດັ່ງລຸ່ມນີ້:",
'whitelistedittext'      => 'ທ່ານ ຈະຕ້ອງ  $1 ເພື່ອ ຈະດັດແກ້.',
'whitelistreadtitle'     => 'ຈະຕ້ອງ ເຊັນເຂົ້າ ເພື່ອອ່ານໜ້ານີ້',
'confirmedittitle'       => 'ຕ້ອງຢືນຢັນ ທີ່ຢູ່ ອີເມລ ຈຶ່ງຈະສາມາດ ດັດແກ້',
'confirmedittext'        => 'ທ່ານ ຕ້ອງຢືນຢັນ ທີ່ຢູ່ອີເມລ ກ່ອນການດັດແກ້ບົດຄວາມຕ່າງໆ. ກະລຸນາ ຂຽນ ທີ່ຢູ່ ອີເມລ ທີ່ໃຊ້ໄດ້ ຢູ່  [[Special:Preferences|user preferences]].',
'loginreqtitle'          => 'ຈຳເປັນຕ້ອງ ເຊັນເຂົ້າ',
'loginreqlink'           => 'ເຊັນເຂົ້າ',
'accmailtitle'           => 'ໄດ້ສົ່ງ ລະຫັດຜ່ານ ໄປແລ້ວ.',
'accmailtext'            => 'ລະຫັດຜ່ານ ຂອງ "$1" ໄດ້ຖືກສົ່ງໄປ  $2 ແລ້ວ.',
'newarticle'             => '(ໃໝ່)',
'newarticletext'         => "== Headline text ==
ທ່ານ ໄດ້ມາຮອດ ໜ້າທີ່ຍັງບໍ່ໄດ້ຖືກສ້າງຂຶ້ນເທື່ອ.
ທ່ານ ສາມາດເລີ່ມ ສ້າງໜ້າ ໂດຍ ພິມໃສ່ ກັບ ຂ້າງລຸ່ມ.
(ເບິ່ງລາຍລະອຽດຕື່ມ ທີ່ [[{{MediaWiki:helppage}}|help page]]).
ຖ້າ ທ່ານ ມາຮອດນີ້ ໂດຍຄວາມຜິດພາດ, ກະລຸນາ ກົດ ປຸ່ມ '''ກັບຄືນ''', ຢູ່ ໂປຣແກຣມ ທ່ອງເວັບ ຂອງທ່ານ.",
'noarticletext'          => 'ຍັງບໍ່ມີເນື້ອໃນ ຢູ່ ໜ້ານີ້, ທ່ານ ສາມາດ [[Special:Search/{{PAGENAME}}|ຊອກຫາຫົວຂໍ້ໜ້ານີ້​]] ຢູ່ ໜ້າອື່ນ ຫຼື [{{fullurl:{{FULLPAGENAME}}|action=edit}} ຂຽນໜ້ານີ້].',
'clearyourcache'         => "'''ເອົາໃຈໃສ່:''' ຫຼັງຈາກບັນທຶກ, ທ່ານອາດຈຳເປັນຕ້ອງເວັ້ນຜ່ານ ຂໍ້ມູນເກົ່າທີ່ ໂປແກມທ່ອງເວັບ ຂອງ ທ່ານບັນທຶກໄວ້ເພື່ອເບິ່ງຜົນການປ່ຽນແປງ. '''Mozilla / Firefox / Safari:''' ກົດ ''Shift'' ໃນເວລາຄລິກ ''Reload'', ຫຼືກົດ ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ໃນ ເມັກຄິນທົສ); '''IE:''' ກົດ ''Ctrl'' ໃນເວລາຄລິກ ''Refresh'', ຫຼືກົດ ''Ctrl-F5''; '''Konqueror:''': ກົດປຸ່ມ ''Reload'' , ຫຼື ກົດ ''F5''; '''Opera''' ຜູ້ໃຊ້ອາດຈຳເປັນຕ້ອງລຶບ ຂໍ້ມູນບັນທຶກໂດຍສິ້ນເຊີງ ໃນ ''Tools→Preferences''.",
'previewnote'            => '<strong>ນີ້ ແມ່ນ ການລອງເບິ່ງຜົນເທົ່ານັ້ນ; ການດັດແກ້ຍັງບໍ່ທັນຖືກບັນທຶກ!</strong>',
'editing'                => 'ພວມດັດແກ້ $1',
'editinguser'            => 'ຜູ້ໃຊ້ <b>$1</b> ພວມດັດແກ້',
'editingsection'         => 'ພວມດັດແກ້ $1 (ພາກ)',
'editingcomment'         => 'ພວມດັດແກ້ $1 (comment)',
'yourtext'               => 'ເນື້ອໃນ',
'yourdiff'               => 'ສ່ວນຕ່າງ',
'copyrightwarning'       => 'ກະລຸນາ ເຂົ້າໃຈວ່າ ທຸກໆ ການປະກອບສ່ວນຕໍ່ກັບ {{SITENAME}} ຈະຖືກ ເປີດເຜີຍ ພາຍໃຕ້  $2 (ເບິ່ງ ລາຍລະອຽດ ທີ່ $1). ຖ້າ ທ່ານ ບໍ່ປະສົງ ໃຫ້ ສິ່ງທີ່ ທ່ານ ຂຽນ ຖືກດັດແກ້ ຫຼື ແຈກຢາຍ ອີກ ຕາມຊອບໃຈ, ທ່ານ ບໍ່ຄວນ ຂຽນຢູ່ນີ້. <br />
ທ່ານ ຈະຕ້ອງ ສັນຍາອີກວ່າ, ທ່ານ ຂຽນມັນຂຶ້ນມາເອງ ຫຼື ກ່າຍມັນມາຈາກ ໂດເມນ ສາທາລະນະ ຫຼື ສິ່ງບໍ່ເສຍຄ່າ ຄ້າຍຄືກັນ.
<strong>ກະລຸນາ ຢ່າ ສົ່ງສິ່ງທີ່ ຖືກສະຫງວນ ລິຂະສິດ ໂດຍ ບໍ່ໄດ້ຮັບອະນຸຍາດ!</strong>',
'copyrightwarning2'      => 'ກະລຸນາ ເຂົ້າໃຈວ່າ ທຸກໆ ການປະກອນສ່ວນ ຕໍ່ {{SITENAME}} ອາດຖືກ ດັດແກ້, ປ່ຽນແປງ, ຫຼື ລຶບ ໂດຍ ຜູ້ປະກອບສ່ວນອື່ນ. ຖ້າ ທ່ານ ບໍ່ປະສົງ ໃຫ້ ສິ່ງທີ່ ທ່ານ ຂຽນ ຖືກດັດແກ້ ຫຼື ແຈກຢາຍ ອີກ ຕາມຊອບໃຈ, ທ່ານ ບໍ່ຄວນ ຂຽນຢູ່ນີ້. <br />
ທ່ານ ຈະຕ້ອງ ສັນຍາອີກວ່າ, ທ່ານ ຂຽນມັນຂຶ້ນມາເອງ ຫຼື ກ່າຍມັນມາຈາກ ໂດເມນ ສາທາລະນະ ຫຼື ສິ່ງບໍ່ເສຍຄ່າ ຄ້າຍຄືກັນ.  (ເບິ່ງ ລາຍລະອຽດ ທີ່ $1).
<strong>ກະລຸນາ ຢ່າ ສົ່ງສິ່ງທີ່່ ຖືກສະຫງວນ ລິຂະສິດ ໂດຍ ບໍ່ໄດ້ຮັບອະນຸຍາດ!</strong>',
'templatesused'          => 'ແມ່ແບບໄດ້ໃຊ້ໃນໜ້ານີ້:',
'templatesusedpreview'   => 'ແມ່ແບບໃຊ້ໃນການລອງເບິ່ງຜົນນີ້:',
'templatesusedsection'   => 'ແມ່ແບບໄດ້ໃຊ້ໃນພາກນີ້:',
'template-protected'     => '(ປົກປ້ອງ)',
'template-semiprotected' => '(ເຄິ່ງປົກປ້ອງ)',

# Account creation failure
'cantcreateaccounttitle' => 'ບໍ່ສາມາດສ້າງ ບັນຊີ ໄດ້',
'cantcreateaccounttext'  => 'ການສ້າງບັນຊີ ຈາກ ທີ່ຢູ່ IP (<b>$1</b>) ນີ້​ໄດ້ຖືກຫ້າມ. 
ອັນນີ້​ ອາດແມ່ນຍ້ອນມີການສ້າງບັນຫາ ຫຼື ຂຽນໂລເລ ຈາກ ໂຮງຮຽນ ຫຼື ຜູ້ສະໜອງການເຂົ້າຫາ ອິນເຕີເນັດ ທີ່ທ່ານໃຊ້.',

# History pages
'nohistory'  => 'ຍັງບໍ່ມີ ປະຫວັດການດັດແກ້ໜ້ານີ້ເທື່ອ.',
'next'       => 'ຕໍ່ໄປ',
'page_first' => 'ທຳອິດ',
'page_last'  => 'ສຸດທ້າຍ',

# Revision deletion
'rev-delundel' => 'ສະແດງ/ເຊື່ອງ',

# Diffs
'compareselectedversions' => 'ສົມທຽບ ລະຫວ່າງ ສະບັບເລືອກ',

# Search results
'searchresulttext' => 'ສຳຫຼັບ ຂໍ້ມູນເພີ່ມເຕີມ ກ່ຽວກັບ ການຊອກຫາ ຢູ່ {{SITENAME}}, ກະລຸນາເບິ່ງ [[{{MediaWiki:helppage}}|{{int:help}}]].',
'searchsubtitle'   => "ທ່ານ ຊອກຫາ '''[[:$1]]'''",
'badquerytext'     => 'ບໍ່ສາມາດດຳເນີນ ການຄົ້ນຫາ ທີ່ທ່ານກຳນົດໄດ້.
ອັນນີ້​ ອາດແມ່ນຍ້ອນ ທ່ານພະຍາຍາມ ຄົ້ນຫາໂດຍຄຳສັບທີ່ໜ້ອຍກ່ວາ 3​ ໂຕໜັງສື, ທີ່ພວກເຮົາຍັງບໍ່ສາມາດຕອບສະໜອງໄດ້ເທື່ອ..
ຫຼື ທ່ານອາດຈະພິມຜິດ ເຊັ່ນ  "ປາ ແລະ ແລະ ຂະໜາດ".
ກະລຸນາ ທົດລອງ ໂດຍ ຄຳສັບອື່ນໆ.',
'noexactmatch'     => "'''ຍັງບໍ່ມີໜ້າ ຊື່ວ່າ  \"\$1\".''' ທ່ານ ສາມາດ [[:\$1|ສ້າງໜ້ານີ້ໄດ້]].",
'nextn'            => '$1 ຕໍ່ໄປ',
'viewprevnext'     => 'ເບິ່ງ ($1) ($2) ($3).',
'powersearch'      => 'ຊອກຫາ',
'blanknamespace'   => '(ຫຼັກ)',

# Preferences page
'preferences'           => 'ການຕັ້ງຄ່າ',
'mypreferences'         => 'ຕັ້ງຄ່າຂອງຂ້ອຍ',
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

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|ການປ່ຽນແປງ|ການປ່ຽນແປງ}}',
'recentchanges'     => 'ການດັດແກ້ຫຼ້າສຸດ',
'recentchangestext' => 'ເບິ່ງ ການປ່ຽນແປງ ຫຼ້າສຸດ ຢູ່ ໜ້າວິກີນີ້.',
'rcnote'            => 'ຂ້າງລຸ່ມ ແມ່ນ <strong>$1</strong>ການປ່ຽນແປງ ຫຼ້າສຸດ ໃນ <strong>$2</strong>ມື້ຫຼ້າສຸດ, ມື້ນີ້​ ແມ່ນ  $3.',
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
'savefile'          => 'ບັນທຶກໄຟລ໌',
'watchthisupload'   => 'ຕິດຕາມໜ້ານີ້',

# Image list
'imagelist'        => 'ລາຍຊື່ໄຟລ໌',
'ilsubmit'         => 'ຄົ້ນຫາ',
'byname'           => 'ຕາມຊື່',
'bydate'           => 'ຕາມວັນເວລາ',
'bysize'           => 'ຕາມ ຂະໜາດ',
'nolinkstoimage'   => 'ບໍ່ມີໜ້າໃດ ລິ້ງຄ໌ ຫາ ໄຟລ໌ນີ້.',
'noimage'          => 'ຍັງບໍ່ມີ ໄຟລ໌ຊື່ນີ້ ເທື່ອ, ທ່ານ ສາມາດ  $1.',
'noimage-linktext' => 'ອັບໂຫຼດມັນ',

# List redirects
'listredirects' => 'ລາຍການການໂອນໜ້າ',

# Unused templates
'unusedtemplates' => 'ແມ່ແບບບໍ່ໄດ້ໃຊ້',

# Random redirect
'randomredirect' => 'ໂອນໄປໜ້າໃດໜຶ່ງ',

# Statistics
'statistics' => 'ສະຖິຕິ',

'doubleredirects' => 'ໂອນໜ້າ 2 ຄັ້ງ',

'brokenredirects'        => 'ການໂອນບໍ່ມີໜ້າປາຍທາງ',
'brokenredirectstext'    => 'ການໂອນໜ້ານີ້ ເຊື່ອມຕໍ່ໄປຫາໜ້າ ທີ່ ຍັງບໍ່ມີເທື່ອ:',
'brokenredirects-edit'   => '(ດັດແກ້)',
'brokenredirects-delete' => '(ລຶບ)',

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
'allpages'                => 'ທຸກໆໜ້າ',
'prefixindex'             => 'ດັດຊະນີຄຳນຳໜ້າ',
'randompage'              => 'ໜ້າໃດໜຶ່ງ',
'shortpages'              => 'ໜ້າເນື້ອໃນສັ້ນ',
'longpages'               => 'ໜ້າເນື້ອໃນຍາວ',
'deadendpages'            => 'ໜ້າບໍ່ການເຊື່ອມຕໍ່ຫາໜ້າອື່ນ',
'protectedpages'          => 'ບັນດາໜ້າຖືກປົກປ້ອງ',
'listusers'               => 'ລາຍຊື່ຜູ້ໃຊ້',
'specialpages'            => 'ໜ້າພິເສດ',
'spheading'               => 'ໜ້າພິເສດ ສຳຫຼັບ ຜູ້ໃຊ້ທຸກໆທ່ານ',
'newpages'                => 'ໜ້າສ້າງໃໝ່',
'newpages-username'       => 'ຊື່ຜູ້ໃຊ້:',
'ancientpages'            => 'ບັນດາໜ້າເກົ່າທີ່ສຸດ',
'move'                    => 'ຍ້າຍ',

# Book sources
'booksources'    => 'ປຶ້ມອ້າງອີງ',
'booksources-go' => 'ໄປ',

'categoriespagetext' => 'ມີ ບັນດາ ໝວດ ຕໍ່ໄປນີ້ ຢູ່ ວິກີ.',
'alphaindexline'     => '$1 ຫາ $2',
'version'            => 'ສະບັບ',

# Special:Log
'log'               => 'ບັນທຶກ',
'all-logs-page'     => 'ທຸກໆຂໍ້ມູນບັນທຶກການນຳໃຊ້',
'log-search-legend' => 'ຊອກຫາ ໃນ ບັນທຶກການນຳໃຊ້',
'log-search-submit' => 'ໄປ',
'alllogstext'       => 'ສະແດງ ບັນທຶກ ການອັບໂຫຼດ, ການລຶບ, ການປົກປ້ອງ, ການຫ້າມ ແລະ sysop.
ທ່ານ ສາມາດຄັດຈ້ອນ ການສະແດງ ໂດຍ ເລືອກ ປະເພດການບັນທຶກ, ຊື່ຜູ້ໃຊ້ ຫຼື ໜ້າທີ່ມີຜົນກະທົບ.',

# Special:Allpages
'nextpage'          => 'ໜ້າຕໍ່ໄປ ($1)',
'allpagesfrom'      => 'ສະແດງທຸກໆໜ້າ ເລີ່ມແຕ່:',
'allarticles'       => 'ທຸກໆບົດຄວາມ',
'allinnamespace'    => 'ທຸກໆໜ້າ ຂອງ ($1 namespace)',
'allnotinnamespace' => 'ທຸກໆໜ້າ  (ນອກຈາກ ໃນ $1 namespace)',
'allpagesprev'      => 'ກ່ອນໜ້າ',
'allpagesnext'      => 'ຕໍ່ໄປ',
'allpagessubmit'    => 'ໄປ',
'allpagesprefix'    => 'ສະແດງ ໜ້າ ຂຶ້ນຕົ້ນດ້ວຍ:',
'allpagesbadtitle'  => 'ຫົວຂໍ້ ຂອງ ໜ້າດັງກ່າວ ບໍ່ຖືກຕ້ອງ ຫຼື ອາດໃຊ້ ຄຳນຳໜ້າ ທີ່ inter-language ຫຼື inter-wiki ໄດ້ສະຫງວນໄວ້. ມັນອາດຈະມີ ໜຶ່ງ ຫຼື ຫຼາຍໂຕໜັງສື ທີ່ ບໍ່ສາມາດໃຊ້ ໃນ ຫົວຂໍ້ໄດ້.',

# Special:Listusers
'listusers-submit' => 'ສະແດງ',

# E-mail user
'mailnologintext' => 'ທ່ານ ຈະຕ້ອງ [[Special:Userlogin|logged in]]
ແລະ ມີ ອີເມລ ທີ່ໃຊ້ໄດ້ ຢູ່  [[Special:Preferences|preferences]]
ຂອງ ທ່ານ ເພື່ອຈະ ສົ່ງ ອີເມລ ຫາ ຜູ້ໃຊ້ອື່ນ.',
'emailuser'       => 'ສົ່ງອີເມລ ຫາ ຜູ້ໃຊ້ນີ້',
'emailpage'       => 'ສົ່ງອີເມລ ຫາ ຜູ້ໃຊ້',
'emailpagetext'   => 'ຖ້າຜູ້ໃຊ້ນີ້ ຂຽນທີ່ຢູ່ອີເມລຢ່າງຖືກຕ້ອງຢູ່ການຕັ້ງຄ່າຂອງລາວ, ຟອມທາງລຸ່ມນີ້​ ຈະສົ່ງຂໍ້ຄວາມໜຶ່ງຫາທີ່ຢູ່ອີເມລນັ້ນ.
ທີ່ຢູ່ອີເມລ ທີ່ທ່ານຂຽນຢູ່ການຕັ້ງຄ່າຂອງທ່ານ, ຈະປະກົດຂຶ້ນ ຢູ່ຊ່ອງ "From" ຂອງ ອີເມລ ເພື່ອໃຫ້ ຜູ້ຮັບສາມາດທຳການຕອບໄດ້.',
'noemailtitle'    => 'ບໍ່ມີ ທີ່ຢູ່ອີເມລ',
'emailfrom'       => 'ຈາກ',
'emailto'         => 'ຫາ',
'emailsubject'    => 'ຫົວເລື້ອງ',
'emailmessage'    => 'ຂໍ້ຄວາມ',
'emailsend'       => 'ສົ່ງ',
'emailccme'       => 'ສົ່ງສຳເນົາ ຂໍ້ຄວາມ ມາໃຫ້ ຂ້ອຍ.',
'emailccsubject'  => 'ສຳເນົາ ຂໍ້ຄວາມທ່ານ ຫາ $1: $2',
'emailsent'       => 'ອີເມລ ໄດ້ຖືກສົ່ງໄປແລ້ວ',
'emailsenttext'   => 'ອີເມລ ຂອງ ທ່ານ ໄດ້ຖືກສົ່ງໄປແລ້ວ.',

# Watchlist
'watchlist'            => 'ລາຍການຕິດຕາມ',
'mywatchlist'          => 'ລາຍການຕິດຕາມຂອງຂ້ອຍ',
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
'wlnote'               => 'ຂ້າງລຸ່ມ ແມ່ນ  $1 ການປ່ຽນແປງຫຼ້າສຸດ ໃນ <b>$2</b> ຊົ່ວໂມງຫຼ້າສຸດ.',
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
'confirm'        => 'ຢືນຢັນ',
'confirmdelete'  => 'ຢືນຢັນການລຶບ',
'deletesub'      => '(ພວມລຶບ "$1")',
'actioncomplete' => 'ສຳເລັດ',
'dellogpage'     => 'ບັນທຶກ ການລຶບ',
'dellogpagetext' => 'ຂ້າງລຸ່ມ ແມ່ນ ລາຍການ ການລຶບຫຼ້າສຸດ.',
'deletionlog'    => 'ບັນທຶກ ການລຶບ',
'deletecomment'  => 'ເຫດຜົນໃນການລຶບ',
'cantrollback'   => 'ບໍ່ສາມາດ ປ່ຽນການດັດແກ້ກັບຄືນໄດ້; ຜູ້ປະກອບສ່ວນຫຼ້າສຸດ ແມ່ນ ຜູ້ຂຽນພຽງຜູ້ດຽວ ຂອງ ໜ້ານີ້.',
'alreadyrolled'  => 'ບໍ່ສາມາດປ່ຽນກັບຄືນ ຫາ ສະບັບຫຼ້າສຸດ ຂອງ  [[:$1]]
ໂດຍ [[User:$2|$2]] ([[User talk:$2|Talk]]); ບາງຄົນ ອາດຈະດັດແກ້ ຫຼື ປ່ຽນກັບຄືນແລ້ວ.

ການດັດແກ້ຫຼ້າສຸດ ແມ່ນ ເຮັດໂດຍ [[User:$3|$3]] ([[User talk:$3|Talk]]).',
'confirmprotect' => 'ຢືນຢັນ ການປົກປ້ອງ',

# Undelete
'viewdeletedpage'        => 'ເບິ່ງໜ້າທີ່ຖືກລຶບ',
'cannotundelete'         => 'ບໍ່ສາມາດ ກັບຄືນ ຫາສະບັບກ່ອນການລຶບ; ບາງຄົນອາດເຮັດກ່ອນແລ້ວ.',
'undelete-search-submit' => 'ຊອກຫາ',

# Namespace form on various pages
'namespace' => 'ຂອບເຂດຊື່:',

# Contributions
'contributions' => 'ການປະກອບສ່ວນຂອງຜູ້ໃຊ້ນີ້',
'mycontris'     => 'ປະກອບສ່ວນຂອງຂ້ອຍ',
'contribsub2'   => 'ສຳຫຼັບ $1 ($2)',
'nocontribs'    => 'ຍັງບໍ່ມີການປ່ຽນແປງ ໃນນີ້.',
'year'          => 'ແຕ່ປີ (ແລະກ່ອນໜ້ານັ້ນ):',

'sp-contributions-newest'   => 'ໃໝ່ສຸດ',
'sp-contributions-oldest'   => 'ເກົ່າສຸດ',
'sp-contributions-newer'    => 'ໃໝ່ກວ່າ $1',
'sp-contributions-older'    => 'ເກົ່າກວ່າ $1',
'sp-contributions-newbies'  => 'ສະແດງສະເພາະ ການປະກອບສ່ວນ ໂດຍ ບັນຊີໃໝ່',
'sp-contributions-blocklog' => 'ບັນທຶກການຫ້າມ',
'sp-contributions-search'   => 'ຊອກຫາ ການປະກອບສ່ວນ',
'sp-contributions-username' => 'ທີ່ຢູ່ IP ຫຼື ຊື່ຜູ້ໃຊ້:',
'sp-contributions-submit'   => 'ຊອກຫາ',

# What links here
'whatlinkshere'  => 'ໜ້າທີ່ເຊື່ອມຕໍ່ມາໜ້ານີ້',
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
'newtitle'                => 'ຫາ ຫົວຂໍ້ໃໝ່',
'move-watch'              => 'ຕິດຕາມໜ້ານີ້',
'articleexists'           => 'ມີໜ້າຊືນີ້ແລ້ວ, ຫຼື 
ຊື່ ທີ່ທ່ານເລືອກບໍ່ຖືກຕ້ອງ.
ກະລຸນາເລືອກຊື່ໃໝ່.',
'talkpagemoved'           => 'ໜ້າສົນທະນາ ໄດ້ຖືກຍ້າຍແລ້ວ ເຊັ່ນດຽວກັນ.',
'1movedto2'               => '[[$1]] ຍ້າຍໄປ [[$2]]',
'1movedto2_redir'         => '[[$1]] ຍ້າຍໄປ [[$2]] ທັບ ການໂອນໜ້າ',
'delete_and_move'         => 'ລຶບ ແລະ ຍ້າຍ',
'delete_and_move_confirm' => 'ແມ່ນແລ້ວ, ລຶບໜ້ານີ້',
'delete_and_move_reason'  => 'ລຶບແລ້ວ ເພື່ອ ຍ້າຍໜ້າອື່ນ ມານີ້',

# Namespace 8 related
'allmessages'         => 'ຂໍ້ຄວາມ ຂອງ ລະບົບ',
'allmessagesname'     => 'ຊື່',
'allmessagesdefault'  => 'ຂໍ້ຄວາມເດີມ',
'allmessagescurrent'  => 'ຂໍ້ຄວາມປະຈຸບັນ',
'allmessagestext'     => 'ນີ້ ແມ່ນ ລາຍການຂໍ້ຄວາມລະບົບ ໃນ MediaWiki namespace.',
'allmessagesmodified' => 'ສະແດງແຕ່ອັນທີ່ຖືກດັດແປງ',

# Tooltip help for the actions
'tooltip-pt-mytalk'         => 'ໜ້າສົນທະນາຂ້ອຍ',
'tooltip-ca-addsection'     => 'ເພີ່ມ ຄຳເຫັນ ໃສ່ ການສົນທະນານີ້.',
'tooltip-ca-move'           => 'ຍ້າຍໜ້ານີ້',
'tooltip-search'            => 'ຄົ້ນຫາ {{SITENAME}}',
'tooltip-p-logo'            => 'ໜ້າຫຼັກ',
'tooltip-ca-nstab-category' => 'ເບິ່ງ ໜ້າໝວດ',

# Attribution
'anonymous' => 'ຜູ້ໃຊ້ ບໍ່ສະແດງຊື່ ຈາກ {{SITENAME}}',
'and'       => 'ແລະ',

# Spam protection
'subcategorycount'     => 'ມີ $1 ໝວດຍ່ອຍ ໃນ ໝວດນີ້.',
'categoryarticlecount' => 'ມີ $1 ບົດຄວາມ ໃນ ໝວດນີ້.',
'category-media-count' => 'ມີ $1 ບົດຄວາມ ໃນ ໝວດນີ້.',

# Browsing diffs
'nextdiff' => 'ສ່ວນຕ່າງຕໍ່ໄປ →',

# Media information
'imagemaxsize' => 'ກຳນົດຄວາມໃຫຍ່ຂອງ ຮູບ ຫຼື ໜ້າອະທິບາຍຮູບ ບໍ່ໃຫ້ກາຍ:',
'thumbsize'    => 'ຂະໜາດສະແດງ:',

'newimages' => 'ໄຟລ໌ຮູບໃໝ່',

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
'confirmemail_noemail'    => 'ທ່ານ ບໍ່ໄດ້ຕັ້ງ ທີ່ຢູ່ອີເມລ ທີ່ໃຊ້ໄດ້ ຢູ່ [[Special:Preferences|user preferences]] ຂອງທ່ານ.',
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
'confirmemail_body'       => 'ຜູ້ໃດຜູ້ໜຶ່ງ, ຈາກ ທີ່ຢູ່ IP $1 ຫຼື ອາດຈະແມ່ນ ທ່ານເອງ ໄດ້ທຳການ ຈົດທະບຽນ 
ບັນຊີ "$2" ໂດຍ ທີ່ຢູ່ ອີເມລນີ້ ຢູ່ {{SITENAME}}.

Someone, probably you from IP address $1, has registered an
account "$2" with this e-mail address on {{SITENAME}}.

ເພື່ອ ຈະຢືນຢັນ ວ່າ ທີ່ຢູ່ ອີເມລນີ້ ແມ່ນ ຂອງ ທ່ານແທ້ ແລະ ເປີດນຳໃຊ້ສິ່ງ ທີ່ ຈຳເປັນ ຕ້ອງ
ມີ ອີເມລ ຢູ່ {{SITENAME}}, ກະລຸນາ ກົດ ລິ້ງຄ໌ ຕໍ່ໄປນີ້:

$3

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

ຖ້າ ທ່ານ ບໍ່ໄດ້ທຳການ ຈົດທະບຽນນີ້, ຢ່າ ກົດລິ້ງຄ໌ນີ້. ລະຫັດລິ້ງຄ໌ນີ້ ຈະໝົດ ຜົນບັງຄັບ ໃນ $4.

If this is *not* you, don\'t follow the link. This confirmation code
will expire at $4.',

# action=purge
'confirm_purge_button' => 'ຕົກລົງ',

'youhavenewmessagesmulti' => 'ທ່ານມີຂໍ້ຄວາມໃໝ່ ຢູ່ $1',

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

);
