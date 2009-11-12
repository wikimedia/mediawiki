<?php
/**
 * Alternate 1.4 -> 1.5 schema upgrade.
 * This does only the main tables + UTF-8 and is designed to allow upgrades to
 * interleave with other updates on the replication stream so that large wikis
 * can be upgraded without disrupting other services.
 *
 * Note: this script DOES NOT apply every update, nor will it probably handle
 * much older versions, etc.
 * Run this, FOLLOWED BY update.php, for upgrading from 1.4.5 release to 1.5.
 *
 * @file
 * @ingroup Maintenance
 */

$options = array( 'step', 'noimages' );

require_once( dirname(__FILE__) . '/commandLine.inc' );
require_once( 'FiveUpgrade.inc' );

echo "ATTENTION: This script is for upgrades from 1.4 to 1.5 (NOT 1.15) in very special cases.\n";
echo "Use update.php for usual updates.\n";

// Seems to confuse some people
if ( !array_search( '--upgrade', $_SERVER['argv'] ) ) {
	echo "Please run this script with --upgrade key to actually run the updater.\n";
	die;
}

$upgrade = new FiveUpgrade();
$step = isset( $options['step'] ) ? $options['step'] : null;
$upgrade->upgrade( $step );


