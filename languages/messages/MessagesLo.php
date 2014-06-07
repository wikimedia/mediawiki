<?php
/** Lao (ລາວ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Passawuth
 * @author Tuinui
 */

$namespaceNames = array(
	NS_MEDIA            => 'ສື່',
	NS_SPECIAL          => 'ພິເສດ',
	NS_TALK             => 'ສົນທະນາ',
	NS_USER             => 'ຜູ້ໃຊ້',
	NS_USER_TALK        => 'ສົນທະນາຂອງຜູ້ໃຊ້',
	NS_PROJECT_TALK     => 'ສົນທະນາກ່ຽວກັບ$1',
	NS_FILE             => 'ຮູບ',
	NS_FILE_TALK        => 'ສົນທະນາກ່ຽວກັບຮູບ',
	NS_MEDIAWIKI        => 'ມີເດຍວິກິ',
	NS_MEDIAWIKI_TALK   => 'ສົນທະນາກ່ຽວກັບມີເດຍວິກິ',
	NS_TEMPLATE         => 'ແມ່ແບບ',
	NS_TEMPLATE_TALK    => 'ສົນທະນາກ່ຽວກັບແມ່ແບບ',
	NS_HELP             => 'ຊ່ວຍເຫຼືອ',
	NS_HELP_TALK        => 'ສົນທະນາກ່ຽວກັບຊ່ວຍເຫຼືອ',
	NS_CATEGORY         => 'ໝວດ',
	NS_CATEGORY_TALK    => 'ສົນທະນາກ່ຽວກັບໝວດ',
);

$namespaceAliases = array(
	'ສື່ອ' => NS_MEDIA,
);

$specialPageAliases = array(
	'Allpages'                  => array( 'ໜ້າທັງໝົດ' ),
	'BrokenRedirects'           => array( 'ໂອນເສຍ' ),
	'Categories'                => array( 'ໝວດ' ),
	'ChangePassword'            => array( 'ປ່ຽນລະຫັດຜ່ານ' ),
	'Contributions'             => array( 'ການປະກອບສ່ວນ' ),
	'CreateAccount'             => array( 'ສ້າງບັນຊີ' ),
	'Deadendpages'              => array( 'ໜ້າທີ່ບໍ່ມີໜ້າໃດໂຍງມາ' ),
	'DoubleRedirects'           => array( 'ໂອນຊ້ອນ' ),
	'Listadmins'                => array( 'ລາຍຊື່ຜູ້ບໍລິຫານລະບົບ' ),
	'Listbots'                  => array( 'ລາຍຊື່ບອຕ' ),
	'Listfiles'                 => array( 'ລາຍຊື່ຮູບ' ),
	'Listusers'                 => array( 'ລາຍຊື່ຜູ້ໃຊ້' ),
	'Longpages'                 => array( 'ໜ້າຍາວ' ),
	'Movepage'                  => array( 'ຍ້າຍ' ),
	'Mycontributions'           => array( 'ປະກອບສ່ວນຂອງຂ້ອຍ' ),
	'Mypage'                    => array( 'ໜ້າຂອງຂ້ອຍ' ),
	'Mytalk'                    => array( 'ສົນທະນາຂອງຂ້ອຍ' ),
	'Newimages'                 => array( 'ຮູບໃໝ່' ),
	'Newpages'                  => array( 'ໜ້າໃໝ່' ),
	'Preferences'               => array( 'ຕັ້ງຄ່າ' ),
	'Protectedpages'            => array( 'ໜ້າທີ່ຖຶກປົກປ້ອງ' ),
	'Protectedtitles'           => array( 'ຊື່ທີ່ຖຶກປົກປ້ອງ' ),
	'Randompage'                => array( 'ບົດຄວາມໃດໜຶ່ງ' ),
	'Randomredirect'            => array( 'ໜ້າໂອນໃດໜຶ່ງ' ),
	'Recentchanges'             => array( 'ການດັດແກ້ຫຼ້າສຸດ' ),
	'Search'                    => array( 'ຊອກຫາ' ),
	'Shortpages'                => array( 'ໜ້າທີ່ສັ້ນ' ),
	'Specialpages'              => array( 'ໜ້າພິເສດ' ),
	'Statistics'                => array( 'ສະຖິຕິ' ),
	'Uncategorizedcategories'   => array( 'ໝວດທີ່ບໍ່ມີໝວດ' ),
	'Uncategorizedimages'       => array( 'ຮູບທີ່ບໍ່ມີໝວດ' ),
	'Uncategorizedpages'        => array( 'ໜ້າທີ່ບໍ່ມີໝວດ' ),
	'Uncategorizedtemplates'    => array( 'ແມ່ແບບທີ່ບໍ່ມີໝວດ' ),
	'Unusedcategories'          => array( 'ໝວດທີ່ບໍ່ໄດ້ໃຊ້' ),
	'Unusedimages'              => array( 'ຮູບທີ່ບໍ່ໄດ້ໃຊ້' ),
	'Unusedtemplates'           => array( 'ແມ່ແບບທີ່ບໍ່ໄດ້ໃຊ້' ),
	'Upload'                    => array( 'ອັປໂຫຼດໄຟລ໌' ),
	'Userlogin'                 => array( 'ເຊັນເຂົ້າ' ),
	'Userlogout'                => array( 'ເຊັນອອກ' ),
	'Wantedcategories'          => array( 'ໝວດທີ່ຕ້ອງການ' ),
	'Wantedpages'               => array( 'ໜ້າທີ່ຕ້ອງການ', 'ລິງກ໌ທີ່້ເສຍ' ),
	'Watchlist'                 => array( 'ຕິດຕາມ' ),
	'Whatlinkshere'             => array( 'ໜ້າທີ່ເຊື່ອມຕໍ່ມາ' ),
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

