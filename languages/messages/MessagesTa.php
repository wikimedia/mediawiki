<?php
/** Tamil (தமிழ்)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'விக்கிபீடியா' => NS_PROJECT,
	'விக்கிபீடியா_பேச்சு' => NS_PROJECT_TALK,
	'உருவப்_பேச்சு' => NS_FILE_TALK
);

$magicWords = array(
	'redirect'                  => array( '0', '#வழிமாற்று', '#REDIRECT' ),
	'img_right'                 => array( '1', 'வலது', 'right' ),
	'img_left'                  => array( '1', 'இடது', 'left' ),
	'img_none'                  => array( '1', 'ஒன்றுமில்லை', 'none' ),
	'img_center'                => array( '1', 'மையம்', 'center', 'centre' ),
	'img_top'                   => array( '1', 'மேல்', 'top' ),
	'img_middle'                => array( '1', 'மத்தியில்', 'middle' ),
	'img_bottom'                => array( '1', 'கீழ்', 'bottom' ),
	'plural'                    => array( '0', 'பன்மை', 'PLURAL:' ),
	'special'                   => array( '0', 'சிறப்பு', 'special' ),
	'pagesize'                  => array( '1', 'பக்க_அளவு', 'PAGESIZE' ),
	'url_path'                  => array( '0', 'வழி', 'PATH' ),
	'url_wiki'                  => array( '0', 'விக்கி', 'WIKI' ),
);

$linkTrail = "/^([\xE0\xAE\x80-\xE0\xAF\xBF]+)(.*)$/sDu";

$digitGroupingPattern = "##,##,###";

