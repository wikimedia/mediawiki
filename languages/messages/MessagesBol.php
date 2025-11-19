<?php
/** Bole (bòo pìkkà)
 *
 * @file
 * @ingroup Languages
 *
 * @author ToluAyo
 */

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Inmusamman',
	NS_TALK             => 'Mossa',
	NS_USER             => 'Ruta',
	NS_USER_TALK        => 'Rutta_mossa',
	NS_PROJECT_TALK     => '$1_mossa',
	NS_FILE             => 'Fayil',
	NS_FILE_TALK        => 'Fayil_mossa',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_mossa',
	NS_TEMPLATE         => 'Tempilet',
	NS_TEMPLATE_TALK    => 'Tempilet_mossa',
	NS_HELP             => 'Taimako',
	NS_HELP_TALK        => 'Taimako_mossa',
	NS_CATEGORY         => 'Panni',
	NS_CATEGORY_TALK    => 'Panni_mossa',
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'An_ruta', 'female' => 'Ani_ruta' ],
	NS_USER_TALK => [ 'male' => 'An_ruta_mossa', 'female' => 'Ani_ruta_mossa' ],
];

$linkTrail = '/^((?:[a-zƁɓƊɗ]|ʼ[yY])+)(.*)$/sDu';
