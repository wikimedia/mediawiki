<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
# Abort if called from a web server
if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
	print "This script must be run from the command line\n";
	exit();
}

define("MEDIAWIKI",true);

# Process command line arguments
# $options becomes an array with keys set to the option names
# $optionsWithArgs is an array of GNU-style options that take an argument. The arguments are returned
# in the values of $options.
# $args becomes a zero-based array containing the non-option arguments

if ( !isset( $optionsWithArgs ) ) {
	$optionsWithArgs = array();
}
$optionsWithArgs[] = 'conf'; # For specifying the location of LocalSettings.php

$self = array_shift( $argv );
$self = __FILE__;
$IP = realpath( dirname( $self ) . "/.." );
chdir( $IP );

$options = array();
$args = array();

for( $arg = reset( $argv ); $arg !== false; $arg = next( $argv ) ) {
	if ( substr( $arg, 0, 2 ) == '--' ) {
		# Long options
		$option = substr( $arg, 2 );
		if ( in_array( $option, $optionsWithArgs ) ) {
			$param = next( $argv );
			if ( $param === false ) {
				die( "$arg needs an value after it\n" );
			}
			$options[$option] = $param;
		} else {
			$bits = explode( '=', $option, 2 );
			if( count( $bits ) > 1 ) {
				$option = $bits[0];
				$param = $bits[1];
			} else {
				$param = 1;
			}
			$options[$option] = $param;
		}
	} elseif ( $arg{0} == '-' ) {
		# Short options
		for ( $p=1; $p<strlen( $arg ); $p++ ) {
			$option = $arg{$p};
			if ( in_array( $option, $optionsWithArgs ) ) {
				$param = next( $argv );
				if ( $param === false ) {
					die( "$arg needs an value after it\n" );
				}
				$options[$option] = $param;
			} else {
				$options[$option] = 1;
			}
		}
	} else {
		$args[] = $arg;
	}
}

# General initialisation

$wgCommandLineMode = true;
# Turn off output buffering if it's on
@ob_end_flush();
$sep = strchr( $include_path = ini_get( "include_path" ), ";" ) ? ";" : ":";

if (!isset( $wgUseNormalUser ) ) {
	$wgUseNormalUser = false;
}

if ( file_exists( '/home/wikipedia/common/langlist' ) ) {
	$wgWikiFarm = true;
	require_once( "$IP/includes/SiteConfiguration.php" );

	# Get $conf
	require( "$IP/InitialiseSettings.php" );

	if ( empty( $wgNoDBParam ) ) {
		# Check if we were passed a db name
		$db = array_shift( $args );
		list( $site, $lang ) = $wgConf->siteFromDB( $db );

		# If not, work out the language and site the old way
		if ( is_null( $site ) || is_null( $lang ) ) {
			if ( !$db ) {	
				$lang = "aa";
			} else {
				$lang = $db;
			}
			if ( isset( $args[0] ) ) {
				$site = array_shift( $args );
			} else {
				$site = "wikipedia";
			}
		}
	} else {
		$lang = "aa";
		$site = "wikipedia";
	}

	# This is for the IRC scripts, which now run as the apache user
	# The apache user doesn't have access to the wikiadmin_pass command
	if ( $_ENV['USER'] == "apache" ) {
		$wgUseNormalUser = true;
	}

	putenv( "wikilang=$lang");

	$DP = $IP;
	ini_set( "include_path", ".:$IP:$IP/includes:$IP/languages:$IP/maintenance" );

	require_once( "$IP/includes/Defines.php" );
	require_once( "$IP/CommonSettings.php" );

	if ( !$wgUseNormalUser ) {
		$wgDBuser = $wgDBadminuser = "wikiadmin";
		$wgDBpassword = $wgDBadminpassword = trim(`wikiadmin_pass`);
	}
} else {
	$wgWikiFarm = false;
	if ( isset( $options['conf'] ) ) {
		$settingsFile = $options['conf'];
	} else {
		$settingsFile = "$IP/LocalSettings.php";
	}

	if ( ! is_readable( $settingsFile ) ) {
		print "A copy of your installation's LocalSettings.php\n" .
		  "must exist in the source directory.\n";
		exit();
	}
	$wgCommandLineMode = true;
	$DP = $IP;
	require_once( "$IP/includes/Defines.php" );
	require_once( $settingsFile );
	ini_set( "include_path", ".$sep$IP$sep$IP/includes$sep$IP/languages$sep$IP/maintenance" );
	
	if ( is_readable( "$IP/AdminSettings.php" ) ) {
		require_once( "$IP/AdminSettings.php" );
	}
}

# Turn off output buffering again, it might have been turned on in the settings files
@ob_end_flush();
# Same with these
$wgCommandLineMode = true;

if ( empty( $wgUseNormalUser ) && isset( $wgDBadminuser ) && $wgDBservers ) {
	$wgDBuser = $wgDBadminuser;
	$wgDBpassword = $wgDBadminpassword;

	foreach ( $wgDBservers as $i => $server ) {
		$wgDBservers[$i]['user'] = $wgDBuser;
		$wgDBservers[$i]['password'] = $wgDBpassword;
	}
}

ini_set( 'memory_limit', -1 );

require_once( "Setup.php" );
require_once( "install-utils.inc" );
$wgTitle = Title::newFromText( "Command line script" );
set_time_limit(0);

// --------------------------------------------------------------------
// Functions
// --------------------------------------------------------------------

function wfWaitForSlaves( $maxLag ) {
	global $wgLoadBalancer;
	if ( $maxLag ) {
		list( $host, $lag ) = $wgLoadBalancer->getMaxLag();
		while ( $lag > $maxLag ) {
			$name = @gethostbyaddr( $host );
			if ( $name !== false ) {
				$host = $name;
			}
			print "Waiting for $host (lagged $lag seconds)...\n";
			sleep($maxLag);
			list( $host, $lag ) = $wgLoadBalancer->getMaxLag();
		}
	}
}



?>
