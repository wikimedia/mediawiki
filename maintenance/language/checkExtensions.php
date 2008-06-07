<?php
/**
 * Check the extensions language files.
 *
 * @file
 * @ingroup MaintenanceLanguage
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'checkLanguage.inc' );

if( !class_exists( 'MessageGroups' ) || !class_exists( 'PremadeMediawikiExtensionGroups' ) ) {
	echo <<<END
Please add the Translate extension to LocalSettings.php, and enable the extension groups:
	require_once( 'extensions/Translate/Translate.php' );
	\$wgTranslateEC = array_keys( \$wgTranslateAC );

END;
	exit(-1);
}

$cli = new CheckExtensionsCLI( $options, $argv[0] );
$cli->execute();
