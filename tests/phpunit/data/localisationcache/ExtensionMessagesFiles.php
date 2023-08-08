<?php

/**
 * This file is loaded in LocalisationCacheTest::testRecacheExtensionMessagesFiles().
 */

// $specialPageAliases can be set
$specialPageAliases['de'] = [
	// new key not found in core message files
	'LocalisationCacheTest' => [ 'LokalisierungsPufferTest' ],
	// merged with fallback languages (below) and core message files
	'Activeusers' => [ 'Aktive_Benutzer*innen' ],
];
$specialPageAliases['en'] = [
	'Activeusers' => [ 'ActiveFolx' ],
];

// $namespaceNames can be set
$namespaceNames['en'] = [
	98 => 'LocalisationCacheTest',
	99 => 'LocalisationCacheTest_talk',
];
$namespaceNames['de'] = [
	98 => 'LokalisierungsPufferTest',
	99 => 'LokalisierungsPufferTest_Diskussion',
];

// $rtl cannot be set
$rtl['de'] = true;
