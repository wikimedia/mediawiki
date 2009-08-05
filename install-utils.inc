<?php

/**
 * This file contains functions used by the install script (config/index.php)
 * and maintenance scripts. It is not loaded in normal web requests.
 *
 * @file
 */

function install_version_checks() {
	# We dare not turn output buffer _off_ since this will break completely
	# if PHP is globally configured to run through a gzip filter.
	@ob_implicit_flush( true );

	if( !function_exists( 'version_compare' ) ) {
		# version_compare was introduced in 4.1.0
		echo "Your PHP version is much too old; 4.0.x will _not_ work. 5.0.0 or higher is required. ABORTING.\n";
		die( -1 );
	}
	if( version_compare( phpversion(), '5.0.0' ) < 0 ) {
		echo "PHP 5.0.0 or higher is required. If PHP 5 is available only when \n".
   "PHP files have a .php5 extension, please navigate to <a href=\"index.php5\">index.php5</a> \n".
   "to continue installation. ABORTING.\n";
		die( -1 );
	}
	
	// Test for PHP bug which breaks PHP 5.0.x on 64-bit...
	// As of 1.8 this breaks lots of common operations instead
	// of just some rare ones like export.
	$borked = str_replace( 'a', 'b', array( -1 => -1 ) );
	if( !isset( $borked[-1] ) ) {
		echo "PHP 5.0.x is buggy on your 64-bit system; you must upgrade to PHP 5.1.x\n" .
			"or higher. ABORTING. (http://bugs.php.net/bug.php?id=34879 for details)\n";
		die( -1 );
	}

	global $wgCommandLineMode;
	$wgCommandLineMode = true;
	umask( 000 );
	@set_time_limit( 0 );
}

function copyfile( $sdir, $name, $ddir, $perms = 0664 ) {
	copyfileto( $sdir, $name, $ddir, $name, $perms );
}

function copyfileto( $sdir, $sname, $ddir, $dname, $perms = 0664 ) {
	global $wgInstallOwner, $wgInstallGroup;

	$d = "{$ddir}/{$dname}";
	if ( copy( "{$sdir}/{$sname}", $d ) ) {
		if ( isset( $wgInstallOwner ) ) { chown( $d, $wgInstallOwner ); }
		if ( isset( $wgInstallGroup ) ) { chgrp( $d, $wgInstallGroup ); }
		chmod( $d, $perms );
		# print "Copied \"{$sname}\" to \"{$d}\".\n";
	} else {
		print "Failed to copy file \"{$sname}\" to \"{$ddir}/{$dname}\".\n";
		exit();
	}
}

function copydirectory( $source, $dest ) {
	$handle = opendir( $source );
	while ( false !== ( $f = readdir( $handle ) ) ) {
		$fullname = "$source/$f";
		if ( $f{0} != '.' && is_file( $fullname ) ) {
			copyfile( $source, $f, $dest );
		}
	}
}

function readconsole( $prompt = '' ) {
	static $isatty = null;
	if ( is_null( $isatty ) ) {
		if ( !function_exists( 'posix_isatty' ) || posix_isatty( 0 /*STDIN*/ ) ) {
			$isatty = true;
		} else {
			$isatty = false;
		}
	}

	if ( $isatty && function_exists( 'readline' ) ) {
		return readline( $prompt );
	} else {
		if ( $isatty ) {
			$st = readlineEmulation( $prompt );
		} else {
			if ( feof( STDIN ) ) {
				$st = false;
			} else {
				$st = fgets(STDIN, 1024);
			}
		}
		if ($st === false) return false;
		$resp = trim( $st );
		return $resp;
	}
}

function findExecutable( $name ) {
	$paths = explode( PATH_SEPARATOR, getenv( "PATH" ) );
	foreach( $paths as $path ) {
		$full = $path . DIRECTORY_SEPARATOR . $name;
		if( file_exists( $full ) ) {
			if( wfIsWindows() || is_executable( $full ) ) {
				return $full;
			}
		}
	}
	return false;
}

function readlineEmulation( $prompt ) {
	$bash = "xbash";
	if( !wfIsWindows() && findExecutable( $bash ) ) {
		$retval = false;
		$encPrompt = wfEscapeShellArg( $prompt );
		$command = "read -er -p $encPrompt && echo \"\$REPLY\"";
		$encCommand = wfEscapeShellArg( $command );
		$line = wfShellExec( "$bash -c $encCommand", $retval );
		
		if( $retval == 0 ) {
			return $line;
		} elseif( $retval == 127 ) {
			// Couldn't execute bash even though we thought we saw it.
			// Shell probably spit out an error message, sorry :(
			// Fall through to fgets()...
		} else {
			// EOF/ctrl+D
			return false;
		}
	}
	
	// Fallback... we'll have no editing controls, EWWW
	if ( feof( STDIN ) ) {
		return false;
	}
	print $prompt;
	return fgets(STDIN, 1024);
}

function readlineEmulationX() {
	// Yes this is GODAWFUL. But it's better than nothing.
	$chars = array();
	while( true ) {
		if( feof( STDIN ) ) {
			// ctrl+D?
			echo "[eof]";
			break;
		}
		$inchar = fread( STDIN, 1 );
		
		// Horrible hack... assume input is UTF-8
		$inbyte = ord( $inchar );
		if( $inbyte >= 0xf0 ) {
			$remaining = 3;
		} elseif( $inbyte >= 0xe0 ) {
			$remaining = 2;
		} elseif( $inbyte >= 0xc0 ) {
			$remaining = 1;
		} else {
			$remaining = 0;
		}
		if( $remaining ) {
			$inchar .= fread( STDIN, $remaining );
		}
		
		if( $inchar == "\n" ) {
			// newline
			echo "[newline]";
			break;
		}
		
		$chars[] = $inchar;
		echo "[char: $inchar]";
	}
	return implode( '', $chars );
}

#
# Read and execute SQL commands from a file
#
function dbsource( $fname, $db = false ) {
	wfDeprecated( __METHOD__ );
	if ( !$db ) {
		// Try $wgDatabase, which is used in the install and update scripts
		global $wgDatabase;
		if ( isset( $wgDatabase ) ) {
			$db = $wgDatabase;
		} else {
			// No? Well, we must be outside of those scripts, so use the standard method
			$db = wfGetDB( DB_MASTER );
		}
	}
	$error = $db->sourceFile( $fname );
	if ( $error !== true ) {
		print $error;
		exit(1);
	}
}

/**
 * Get the value of session.save_path
 *
 * Per http://www.php.net/manual/en/ref.session.php#ini.session.save-path,
 * this might have some additional preceding parts which need to be
 * ditched
 *
 * @return string
 */
function mw_get_session_save_path() {
	$path = ini_get( 'session.save_path' );
	$path = substr( $path, strrpos( $path, ';' ) );
	return $path;
}

/**
 * Is dl() available to us?
 *
 * According to http://www.php.net/manual/en/function.dl.php, dl()
 * is *not* available when `enable_dl` is off, or under `safe_mode`
 *
 * @return bool
 */
function mw_have_dl() {
	return function_exists( 'dl' )
		&& is_callable( 'dl' )
		&& wfIniGetBool( 'enable_dl' )
		&& !wfIniGetBool( 'safe_mode' );
}