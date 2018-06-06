<?php

$smwgDefaultStore = "SMWSQLStore3";
//require_once "$IP/extensions/SemanticMediaWiki/SemanticMediaWiki.php"; //autoloaded by composer
require_once "$IP/extensions/ExternalData/ExternalData.php";
require_once "$IP/extensions/PageSchemas/PageSchemas.php";
require_once "$IP/extensions/SemanticInternalObjects/SemanticInternalObjects.php";
require_once "$IP/extensions/OpenLayers/OpenLayers.php";
require_once "$IP/extensions/SemanticCompoundQueries/SemanticCompoundQueries.php";
//require_once "$IP/extensions/SemanticExtraSpecialProperties/vendor/autoload.php"; //autoloaded by composer
require_once "$IP/extensions/SemanticExtraSpecialProperties/SemanticExtraSpecialProperties.php";
require_once "$IP/extensions/PageForms/PageForms.php";
//require_once "$IP/extensions/SemanticResultFormats/vendor/autoload.php"; //autoloaded by composer
require_once "$IP/extensions/BlueSpiceSMWConnector/BlueSpiceSMWConnector.php";

enableSemantics( 'localhost' );

$GLOBALS[ 'smwgPageSpecialProperties' ] = array_merge(
	$GLOBALS[ 'smwgPageSpecialProperties' ],
	array( '_CDAT', '_LEDT', '_NEWP', '_MIME', '_MEDIA' )
);

$GLOBALS[ 'smwgEnabledEditPageHelp' ] = false;

$GLOBALS[ 'sespSpecialProperties' ] = array(
	'_EUSER', '_CUSER', '_REVID', '_PAGEID', '_VIEWS', '_NREV', '_TNREV',
	'_SUBP', '_USERREG', '_USEREDITCNT', '_EXIFDATA'
);

$GLOBALS[ 'bssSpecialProperties' ] = array(
	'_RESPEDITOR', '_PARENTPAGE', '_CHECKLIST', '_PAGEASSIGN', '_REVIEW', '_SHOUTBOX', '_FRCDOCSTATE', '_FRCAPPROVALDATE', '_FRCAPPROVALUSER', '_FRCDOCVERSION'
);

$GLOBALS[ 'sespUseAsFixedTables' ] = true;

$GLOBALS[ 'wgSESPExcludeBots' ] = true;

$GLOBALS["wgAutoloadClasses"]["SMW\Test\QueryPrinterRegistryTestCase"] = $IP . "/extensions/SemanticMediaWiki/tests/phpunit/QueryPrinterRegistryTestCase.php";
$GLOBALS["wgAutoloadClasses"]["SMW\Test\QueryPrinterTestCase"] = $IP . "/extensions/SemanticMediaWiki/tests/phpunit/QueryPrinterTestCase.php";
