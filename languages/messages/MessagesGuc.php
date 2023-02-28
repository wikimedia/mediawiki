<?php
/** Wayuu (wayuunaiki)
 *
 * @file
 * @ingroup Languages
 *
 * @author Amire80
 * @author Leonfd1992
 */

$fallback = 'es';

$namespaceNames = [
	NS_MEDIA            => 'Ayaakuwapülee',
	NS_SPECIAL          => 'Analayaapülee_kasa',
	NS_TALK             => 'Yootirawaa',
	NS_USER             => 'Ka\'yataayakalü',
	NS_USER_TALK        => 'Yootirawaa_nümaa_ka\'yataayakalü',
	NS_PROJECT_TALK     => 'Yootirawaa_süchiki_$1',
	NS_FILE             => 'Anaajaalaa',
	NS_FILE_TALK        => 'Yootirawaa_süchiki_anaajaalaa',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Yootirawaa_süchiki_MediaWiki',
	NS_TEMPLATE         => 'Anouktia_sukua\'ipa',
	NS_TEMPLATE_TALK    => 'Yootirawaa_süchiki_anouktia_akua\'ipa',
	NS_HELP             => 'Akaaliijiaa',
	NS_HELP_TALK        => 'Yootirawaa_süchiki_akaaliijiaa',
	NS_CATEGORY         => 'Akotchajülee_sünülia',
	NS_CATEGORY_TALK    => 'Yootirawaa_süchiki_akotchajülee_anülia',
];

// Remove Spanish gender aliases
$namespaceGenderAliases = [];

$linkTrail = '/^([a-záéíóúüñ]+)(.*)$/sDu';
