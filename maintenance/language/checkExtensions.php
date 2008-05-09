<?php
/**
 * Check the extensions language files.
 *
 * @addtogroup Maintenance
 */

require_once( dirname(__FILE__).'/../commandLine.inc' );
require_once( 'languages.inc' );
require_once( 'checkLanguage.inc' );

if( !class_exists( 'MessageGroups' ) ) {
	echo <<<END
Please add the Translate extension to LocalSettings.php, and enable the extension groups:
	require_once( 'extensions/Translate/Translate.php' );
	\$wgTranslateEC = array_keys( \$wgTranslateAC );

END;
	exit(-1);
}

$cli = new CheckExtensionsCLI( $options, $argv[0] );
$cli->execute();
