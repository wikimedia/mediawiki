<?php
/** Fon (fɔ̀ngbè)
 *
 * @file
 * @ingroup Languages
 *
 * @author Mah3110
 * @author Gbehlon
 * @author Amir E. Aharoni
 */

$fallback = 'fr';

$namespaceNames = [
	NS_MEDIA            => "Yɛwliɖonuji",
	NS_SPECIAL          => "Ɖéɖovo",
	NS_TALK             => "Xoɖɔtɛn",
	NS_USER             => "Wikizantɔ",
	NS_USER_TALK        => "Xoɖɔtɛn_wikizantɔ_lɛ_tɔn",
	NS_PROJECT_TALK     => "Xoɖɔtɛn_$1_tɔn",
	NS_FILE             => "Wékpo",
	NS_FILE_TALK        => "Xoɖɔtɛn_wékpo_wú_tɛ̀n",
	NS_MEDIAWIKI        => "MediaWiki",
	NS_MEDIAWIKI_TALK   => "Xoɖɔtɛn_MediaWiki_tɔn",
	NS_TEMPLATE         => "Kpɔnd'ewu_bo_blo",
	NS_TEMPLATE_TALK    => "Xoɖɔtɛn_kpɔnd'ewu_ɔ_tɔ̀n",
	NS_HELP             => "Alɔgɔ",
	NS_HELP_TALK        => "Xoɖɔtɛn_alɔgɔtɛn_ɔ_tɔn",
	NS_CATEGORY         => "Akpaxwé",
	NS_CATEGORY_TALK    => "Akpaxwé_lɛ_sín_xoɖɔtɛn",
];

// Remove French gendered user namespace aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙƉɖ̀Ɛ̌ɛ̂Ɔ́ɔ̄]+)(.*)$/sDu';
