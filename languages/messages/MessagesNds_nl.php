<?php
/** Dutch Lower Saxon (Nedersaksisch)
  *
  * @addtogroup Language
  *
  * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
  * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
  */

$fallback = 'nds';

$skinNames = array(
	'standard'      => 'Klassiek',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Keuls blauw',
	'chick'         => 'Sjiek'
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_MAIN             => '',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_IMAGE            => 'Ofbeelding',
	NS_IMAGE_TALK       => 'Overleg_ofbeelding',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
	NS_HELP             => 'Hulpe',
	NS_HELP_TALK        => 'Overleg_hulpe',
	NS_CATEGORY         => 'Kattegerie',
	NS_CATEGORY_TALK    => 'Overleg_kattegerie'
);

$namespaceAliases = array(
	'Speciaol'          => NS_SPECIAL,
	'Categorie'         => NS_CATEGORY,
	'Overleg_categorie' => NS_CATEGORY_TALK,
	'Overleg_help'      => NS_HELP_TALK,
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

?>
