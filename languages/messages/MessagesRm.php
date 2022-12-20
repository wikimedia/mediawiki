<?php
/** Romansh (rumantsch)
 *
 * @file
 * @ingroup Languages
 *
 * @author Gion
 * @author Gion-andri
 * @author Kaganer
 * @author Kazu89
 * @author Shirayuki
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Multimedia',
	NS_SPECIAL          => 'Spezial',
	NS_TALK             => 'Discussiun',
	NS_USER             => 'Utilisader',
	NS_USER_TALK        => 'Utilisader_discussiun',
	NS_PROJECT_TALK     => '$1_discussiun',
	NS_FILE             => 'Datoteca',
	NS_FILE_TALK        => 'Datoteca_discussiun',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_discussiun',
	NS_TEMPLATE         => 'Model',
	NS_TEMPLATE_TALK    => 'Model_discussiun',
	NS_HELP             => 'Agid',
	NS_HELP_TALK        => 'Agid_discussiun',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Categoria_discussiun',
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'img_manualthumb'           => [ '1', 'miniatura=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_thumbnail'             => [ '1', 'miniatura', 'thumb', 'thumbnail' ],
	'img_upright'               => [ '1', 'sidretg', 'sidretg=$1', 'sidretg_$1', 'upright', 'upright=$1', 'upright $1' ],
	'redirect'                  => [ '0', '#RENVIAMENT', '#REDIRECT' ],
];
