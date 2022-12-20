<?php
/** Wallisian (Fakaʻuvea)
 *
 * @file
 * @ingroup Languages
 *
 * @author Lea.Fakauvea
 * @author Amir E. Aharoni
 */

$fallback = 'fr';

$namespaceNames = [
	NS_MEDIA            => 'Pāki',
	NS_SPECIAL          => 'Makehe',
	NS_TALK             => 'Palalau',
	NS_USER             => 'Kaiga',
	NS_USER_TALK        => 'Palalau_ki_te_kaiga',
	NS_PROJECT_TALK     => 'Palalau_ki_te_$1',
	NS_FILE             => 'Koga',
	NS_FILE_TALK        => 'Palalau_ki_te_koga',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Palalau_ki_te_MediaWiki',
	NS_TEMPLATE         => 'Kupesi',
	NS_TEMPLATE_TALK    => 'Palalau_ki_te_kupesi',
	NS_HELP             => 'Tokoni',
	NS_HELP_TALK        => 'Palalau_ki_te_tokoni',
	NS_CATEGORY         => 'Faʻahiga',
	NS_CATEGORY_TALK    => 'Palalau_ki_te_faʻahiga',
];

// Remove French aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-zA-ZàâāçéèêēîīôōûäëïöüùūÇÉÂĀÊĒÎĪÔŌÛŪÄËÏÖÜÀÈÙʻ]+)(.*)$/sDu';
