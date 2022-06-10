<?php
/** Tachelhit (Shilha) (Taclḥit)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 *
 * @author Ayour2002
 * @author Amire80
 */

$fallback = 'shi-latn, fr';

$namespaceNames = [
	NS_MEDIA            => 'Midya',
	NS_SPECIAL          => 'Amẓli',
	NS_TALK             => 'Amsawal',
	NS_USER             => 'Asmras',
	NS_USER_TALK        => 'Amsawal_n_usmras',
	NS_PROJECT_TALK     => 'Amsawal_n_$1',
	NS_FILE             => 'Afaylu',
	NS_FILE_TALK        => 'Amsawal_n_ufaylu',
	NS_MEDIAWIKI        => 'MidyaWiki',
	NS_MEDIAWIKI_TALK   => 'Amsawal_n_MidyaWiki',
	NS_TEMPLATE         => 'Talɣa',
	NS_TEMPLATE_TALK    => 'Amsawal_n_talɣa',
	NS_HELP             => 'Tiwisi',
	NS_HELP_TALK        => 'Amsawal_n_twisi',
	NS_CATEGORY         => 'Taggayt',
	NS_CATEGORY_TALK    => 'Amsawal_n_taggayt',
];

// Remove French gender aliases
$namespaceGenderAliases = [];

// Includes the whole Tifinagh alphabet, and Latin with all the special characters
// for the French and the Berber Latin alphabets
$linkTrail = '/^([ⴰ-ⵯa-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙḍḥɛṛɣṣṭẓḌḤƐṚƔṢṬẒʷ]+)(.*)$/sDu';
