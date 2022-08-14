<?php
/** Fulah (Fulfulde)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 */

// French used to be the fallback for years,
// and these namespace names were used quite a lot
// including the older ones with the capital letter
// in the second word
$namespaceAliases = [
	'Média'                  => NS_MEDIA,
	'Spécial'                => NS_SPECIAL,
	'Discussion'             => NS_TALK,
	'Utilisateur'            => NS_USER,
	'Discussion_utilisateur' => NS_USER_TALK,
	'Discussion_$1'          => NS_PROJECT_TALK,
	'Fichier'                => NS_FILE,
	'Discussion_fichier'     => NS_FILE_TALK,
	'MediaWiki'              => NS_MEDIAWIKI,
	'Discussion_MediaWiki'   => NS_MEDIAWIKI_TALK,
	'Modèle'                 => NS_TEMPLATE,
	'Discussion_modèle'      => NS_TEMPLATE_TALK,
	'Aide'                   => NS_HELP,
	'Discussion_aide'        => NS_HELP_TALK,
	'Catégorie'              => NS_CATEGORY,
	'Discussion_catégorie'   => NS_CATEGORY_TALK,

	'Discuter'               => NS_TALK,
	'Discussion_Utilisateur' => NS_USER_TALK,
	'Discussion_Fichier'     => NS_FILE_TALK,
	'Discussion_Image'       => NS_FILE_TALK,
	'Discussion_Modèle'      => NS_TEMPLATE_TALK,
	'Discussion_Aide'        => NS_HELP_TALK,
	'Discussion_Catégorie'   => NS_CATEGORY_TALK
];

$linkTrail = '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙƁƊŊƝƳɓɗŋɲƴ]+)(.*)$/sDu';
