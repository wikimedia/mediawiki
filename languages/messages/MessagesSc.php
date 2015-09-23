<?php
/** Sardinian (sardu)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andria
 * @author L2212
 * @author Marzedu
 * @author Node ue
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Ispetziale',
	NS_TALK             => 'Cuntierra',
	NS_USER             => 'Usuàriu',
	NS_USER_TALK        => 'Cuntierra_usuàriu',
	NS_PROJECT_TALK     => 'Cuntierra_$1',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'Cuntierra_file',
	NS_MEDIAWIKI_TALK   => 'Cuntierra_MediaWiki',
	NS_TEMPLATE_TALK    => 'Cuntierra_template',
	NS_HELP             => 'Agiudu',
	NS_HELP_TALK        => 'Cuntierra_agiudu',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Cuntierra_categoria',
);

$namespaceAliases = array(
	'Speciale'            => NS_SPECIAL,
	'Contièndha'          => NS_TALK,
	'Utente'              => NS_USER,
	'Utente_discussioni'  => NS_USER_TALK,
	'$1_discussioni'      => NS_PROJECT_TALK,
	'Immàgini'            => NS_FILE,
	'Immàgini_contièndha' => NS_FILE_TALK
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

$linkTrail = "/^([a-z]+)(.*)$/sD";

