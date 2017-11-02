<?php

require_once __DIR__ . "/../extensions/BlueSpiceFoundation/BlueSpiceFoundation.php";
require_once __DIR__ . "/../extensions/BlueSpiceExtensions/BlueSpiceExtensions.php";
wfLoadExtension("BlueSpiceInterWikiLinks");
wfLoadExtension("BlueSpicePagesVisited");
wfLoadExtension("BlueSpiceSmartList");
wfLoadExtension("BlueSpiceExtendedStatistics");
wfLoadExtension("BlueSpiceAuthors");
wfLoadExtension("BlueSpiceUserManager");
wfLoadExtension("BlueSpicePageTemplates");
wfLoadExtension("BlueSpiceExtendedSearch");
require_once __DIR__ . "/../extensions/BlueSpiceTagCloud/BlueSpiceTagCloud.php";
require_once __DIR__ . "/../skins/BlueSpiceSkin/BlueSpiceSkin.php";
