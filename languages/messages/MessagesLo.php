<?php
/** Lao (ລາວ)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Passawuth
 * @author Tuinui
 */

$namespaceNames = [
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
];

$namespaceAliases = [
	'ສື່ອ' => NS_MEDIA,
];

$specialPageAliases = [
	'Allpages'                  => [ 'ໜ້າທັງໝົດ' ],
	'BrokenRedirects'           => [ 'ໂອນເສຍ' ],
	'Categories'                => [ 'ໝວດ' ],
	'ChangePassword'            => [ 'ປ່ຽນລະຫັດຜ່ານ' ],
	'Contributions'             => [ 'ການປະກອບສ່ວນ' ],
	'CreateAccount'             => [ 'ສ້າງບັນຊີ' ],
	'Deadendpages'              => [ 'ໜ້າທີ່ບໍ່ມີໜ້າໃດໂຍງມາ' ],
	'DoubleRedirects'           => [ 'ໂອນຊ້ອນ' ],
	'Listadmins'                => [ 'ລາຍຊື່ຜູ້ບໍລິຫານລະບົບ' ],
	'Listbots'                  => [ 'ລາຍຊື່ບອຕ' ],
	'Listfiles'                 => [ 'ລາຍຊື່ຮູບ' ],
	'Listusers'                 => [ 'ລາຍຊື່ຜູ້ໃຊ້' ],
	'Longpages'                 => [ 'ໜ້າຍາວ' ],
	'Movepage'                  => [ 'ຍ້າຍ' ],
	'Mycontributions'           => [ 'ປະກອບສ່ວນຂອງຂ້ອຍ' ],
	'Mypage'                    => [ 'ໜ້າຂອງຂ້ອຍ' ],
	'Mytalk'                    => [ 'ສົນທະນາຂອງຂ້ອຍ' ],
	'Newimages'                 => [ 'ຮູບໃໝ່' ],
	'Newpages'                  => [ 'ໜ້າໃໝ່' ],
	'Preferences'               => [ 'ຕັ້ງຄ່າ' ],
	'Protectedpages'            => [ 'ໜ້າທີ່ຖຶກປົກປ້ອງ' ],
	'Protectedtitles'           => [ 'ຊື່ທີ່ຖຶກປົກປ້ອງ' ],
	'Randompage'                => [ 'ບົດຄວາມໃດໜຶ່ງ' ],
	'Randomredirect'            => [ 'ໜ້າໂອນໃດໜຶ່ງ' ],
	'Recentchanges'             => [ 'ການດັດແກ້ຫຼ້າສຸດ' ],
	'Search'                    => [ 'ຊອກຫາ' ],
	'Shortpages'                => [ 'ໜ້າທີ່ສັ້ນ' ],
	'Specialpages'              => [ 'ໜ້າພິເສດ' ],
	'Statistics'                => [ 'ສະຖິຕິ' ],
	'Uncategorizedcategories'   => [ 'ໝວດທີ່ບໍ່ມີໝວດ' ],
	'Uncategorizedimages'       => [ 'ຮູບທີ່ບໍ່ມີໝວດ' ],
	'Uncategorizedpages'        => [ 'ໜ້າທີ່ບໍ່ມີໝວດ' ],
	'Uncategorizedtemplates'    => [ 'ແມ່ແບບທີ່ບໍ່ມີໝວດ' ],
	'Unusedcategories'          => [ 'ໝວດທີ່ບໍ່ໄດ້ໃຊ້' ],
	'Unusedimages'              => [ 'ຮູບທີ່ບໍ່ໄດ້ໃຊ້' ],
	'Unusedtemplates'           => [ 'ແມ່ແບບທີ່ບໍ່ໄດ້ໃຊ້' ],
	'Upload'                    => [ 'ອັປໂຫຼດໄຟລ໌' ],
	'Userlogin'                 => [ 'ເຊັນເຂົ້າ' ],
	'Userlogout'                => [ 'ເຊັນອອກ' ],
	'Wantedcategories'          => [ 'ໝວດທີ່ຕ້ອງການ' ],
	'Wantedpages'               => [ 'ໜ້າທີ່ຕ້ອງການ', 'ລິງກ໌ທີ່້ເສຍ' ],
	'Watchlist'                 => [ 'ຕິດຕາມ' ],
	'Whatlinkshere'             => [ 'ໜ້າທີ່ເຊື່ອມຕໍ່ມາ' ],
];

$digitTransformTable = [
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
];
