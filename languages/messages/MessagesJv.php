<?php
/** Javanese (Basa Jawa)
 *
 * @addtogroup Language
 *
 * @author Niklas Laxström
 *
 * @copyright Copyright © 2006, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
$fallback = 'id';

$quickbarSettings = array(
	'Ora ana', 'Tetep sisih kiwa', 'Tetep sisih tengen', 'Ngambang sisih kiwa'
);
$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Astamiwa',
	NS_MAIN             => '',
	NS_TALK             => 'Dhiskusi',
	NS_USER             => 'Panganggo',
	NS_USER_TALK        => 'Dhiskusi_Panganggo',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Dhiskusi_$1',
	NS_IMAGE            => 'Gambar',
	NS_IMAGE_TALK       => 'Dhiskusi_Gambar',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dhiskusi_MediaWiki',
	NS_TEMPLATE         => 'Cithakan',
	NS_TEMPLATE_TALK    => 'Dhiskusi_Cithakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Dhiskusi_Pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Dhiskusi_Kategori'
);

$namespaceAliases = array(
	'Gambar_Dhiskusi' => NS_IMAGE_TALK,
	'MediaWiki_Dhiskusi' => NS_MEDIAWIKI_TALK,
	'Cithakan_Dhiskusi' => NS_TEMPLATE_TALK,
	'Pitulung_Dhiskusi' => NS_HELP_TALK,
	'Kategori_Dhiskusi' => NS_CATEGORY_TALK,
);
?>
