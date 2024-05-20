<?php
/** Betawi
 *
 * @file
 * @ingroup Languages
 *
 * @author Amir E. Aharoni
 */

$fallback = 'id';

$namespaceNames = [
	NS_MEDIA            => 'Wasilah',
	NS_SPECIAL          => 'Istimèwa',
	NS_TALK             => 'Kongko',
	NS_USER             => 'Pemaké',
	NS_USER_TALK        => 'Kongko_pemaké',
	NS_PROJECT_TALK     => 'Kongko_$1',
	NS_FILE             => 'Gepokan',
	NS_FILE_TALK        => 'Kongko_gepokan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kongko_MediaWiki',
	NS_TEMPLATE         => 'Sablonan',
	NS_TEMPLATE_TALK    => 'Kongko_sablonan',
	NS_HELP             => 'Pertulungan',
	NS_HELP_TALK        => 'Kongko_pertulungan',
	NS_CATEGORY         => 'Bangsaan',
	NS_CATEGORY_TALK    => 'Kongko_bangsaan',
];

$namespaceAliases = [];

$linkTrail = '/^([a-zéè]+)(.*)$/sDu';
