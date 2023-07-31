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

// $rtl cannot be set
$rtl['de'] = true;
