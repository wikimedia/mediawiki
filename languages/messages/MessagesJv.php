<?php
/** Javanese (Basa Jawa)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'id';

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Astamiwa',
	NS_TALK             => 'Parembugan',
	NS_USER             => 'Panganggo',
	NS_USER_TALK        => 'Parembugan_Panganggo',
	NS_PROJECT_TALK     => 'Parembugan_$1',
	NS_FILE             => 'Gambar',
	NS_FILE_TALK        => 'Parembugan_Gambar',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Parembugan_MediaWiki',
	NS_TEMPLATE         => 'Cithakan',
	NS_TEMPLATE_TALK    => 'Parembugan_Cithakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Parembugan_Pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Parembugan_Kategori',
];

$namespaceAliases = [ // Kept former namespaces for backwards compatibility - T155957
	'Cithakan_Dhiskusi'  => NS_TEMPLATE_TALK,
	'Dhiskusi'           => NS_TALK,
	'Dhiskusi_$1'        => NS_PROJECT_TALK,
	'Dhiskusi_Cithakan'  => NS_TEMPLATE_TALK,
	'Dhiskusi_Gambar'    => NS_FILE_TALK,
	'Dhiskusi_Kategori'  => NS_CATEGORY_TALK,
	'Dhiskusi_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Dhiskusi_Panganggo' => NS_USER_TALK,
	'Dhiskusi_Pitulung'  => NS_HELP_TALK,
	'Kategori_Dhiskusi'  => NS_CATEGORY_TALK,
	'MediaWiki_Dhiskusi' => NS_MEDIAWIKI_TALK,
	'Pitulung_Dhiskusi'  => NS_HELP_TALK,
	'Gambar_Dhiskusi'    => NS_FILE_TALK,
];
