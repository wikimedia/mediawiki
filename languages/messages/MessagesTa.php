<?php
/** Tamil (தமிழ்)
 *
 * @file
 * @ingroup Languages
 *
 * @author Aswn
 * @author Balajijagadesh
 * @author Caliberoviv
 * @author ElangoRamanujam
 * @author Jayarathina
 * @author Kaganer
 * @author Kanags
 * @author Karthi.dr
 * @author Krishnaprasaths
 * @author Logicwiki
 * @author Mahir78
 * @author Mayooranathan
 * @author Naveen
 * @author Planemad
 * @author Sank
 * @author Shanmugamp7
 * @author Shirayuki
 * @author Sodabottle
 * @author Sundar
 * @author Surya Prakash.S.A.
 * @author TRYPPN
 * @author Theni.M.Subramani
 * @author Trengarasu
 * @author Ulmo
 * @author Urhixidur
 * @author לערי ריינהארט
 * @author கோபி
 * @author கௌசிக் பிரபு
 * @author செல்வா
 * @author மதனாஹரன்
 * @author බිඟුවා
 */

$namespaceNames = [
	NS_MEDIA            => 'ஊடகம்',
	NS_SPECIAL          => 'சிறப்பு',
	NS_TALK             => 'பேச்சு',
	NS_USER             => 'பயனர்',
	NS_USER_TALK        => 'பயனர்_பேச்சு',
	NS_PROJECT_TALK     => '$1_பேச்சு',
	NS_FILE             => 'படிமம்',
	NS_FILE_TALK        => 'படிமப்_பேச்சு',
	NS_MEDIAWIKI        => 'மீடியாவிக்கி',
	NS_MEDIAWIKI_TALK   => 'மீடியாவிக்கி_பேச்சு',
	NS_TEMPLATE         => 'வார்ப்புரு',
	NS_TEMPLATE_TALK    => 'வார்ப்புரு_பேச்சு',
	NS_HELP             => 'உதவி',
	NS_HELP_TALK        => 'உதவி_பேச்சு',
	NS_CATEGORY         => 'பகுப்பு',
	NS_CATEGORY_TALK    => 'பகுப்பு_பேச்சு',
];

$namespaceAliases = [
	'விக்கிபீடியா' => NS_PROJECT,
	'விக்கிபீடியா_பேச்சு' => NS_PROJECT_TALK,
	'உருவப்_பேச்சு' => NS_FILE_TALK
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'img_bottom'                => [ '1', 'கீழ்', 'bottom' ],
	'img_center'                => [ '1', 'மையம்', 'center', 'centre' ],
	'img_left'                  => [ '1', 'இடது', 'left' ],
	'img_middle'                => [ '1', 'மத்தியில்', 'middle' ],
	'img_none'                  => [ '1', 'ஒன்றுமில்லை', 'none' ],
	'img_right'                 => [ '1', 'வலது', 'right' ],
	'img_top'                   => [ '1', 'மேல்', 'top' ],
	'pagesize'                  => [ '1', 'பக்க_அளவு', 'PAGESIZE' ],
	'plural'                    => [ '0', 'பன்மை', 'PLURAL:' ],
	'redirect'                  => [ '0', '#வழிமாற்று', '#REDIRECT' ],
	'special'                   => [ '0', 'சிறப்பு', 'special' ],
	'url_path'                  => [ '0', 'வழி', 'PATH' ],
	'url_wiki'                  => [ '0', 'விக்கி', 'WIKI' ],
];

$linkTrail = "/^([\u{0B80}-\u{0BFF}]+)(.*)$/sDu";

$digitGroupingPattern = "#,##,##0.###";
