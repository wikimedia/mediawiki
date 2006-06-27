<?php

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
		echo "PHP 5.0.0 or higher is required. ABORTING.\n";
		die( -1 );
	}

	global $wgCommandLineMode;
	$wgCommandLineMode = true;
	umask( 000 );
	set_time_limit( 0 );
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
	static $isatty = null, $fp = null;
	if ( is_null( $fp ) ) {
		$fp = fopen( 'php://stdin', 'r' );
	}
	if ( is_null( $isatty ) ) {
		if ( !function_exists( 'posix_isatty' ) || posix_isatty( $fp ) ) {
			$isatty = true;
		} else {
			$isatty = false;
		}
	}

	if ( $isatty && function_exists( 'readline' ) ) {
		return readline( $prompt );
	} else {
		if ( $isatty ) {
			print $prompt;
		}
		if ( feof( $fp ) ) {
			return false;
		}
		$st = fgets($fp, 1024);
		if ($st === false) return false;
		$resp = trim( $st );
		return $resp;
	}
}

#
# Read and execute SQL commands from a file
#
function dbsource( $fname, $db = false ) {
	if ( !$db ) {
		// Try $wgDatabase, which is used in the install and update scripts
		global $wgDatabase;
		if ( isset( $wgDatabase ) ) {
			$db =& $wgDatabase;
		} else {
			// No? Well, we must be outside of those scripts, so use the standard method
			$db =& wfGetDB( DB_MASTER );
		}
	}
	$error = $db->sourceFile( $fname );
	if ( $error !== true ) {
		print $error;
		exit(1);
	}
}

# Obsolete, use Database::fieldExists()
function field_exists( $table, $field ) {
	$fname = 'Update script: field_exists';
	$db =& wfGetDB( DB_SLAVE );
	$res = $db->query( "DESCRIBE $table", $fname );
	$found = false;

	while ( $row = $db->fetchObject( $res ) ) {
		if ( $row->Field == $field ) {
			$found = true;
			break;
		}
	}
	return $found;
}

# Obsolete Database::tableExists()
function table_exists( $db ) {
	global $wgDBname;
	$res = mysql_list_tables( $wgDBname );
	if( !$res ) {
		echo "** " . mysql_error() . "\n";
		return false;
	}
	for( $i = mysql_num_rows( $res ) - 1; $i--; $i > 0 ) {
		if( mysql_tablename( $res, $i ) == $db ) return true;
	}
	return false;
}

# Obsolete, use Database:fieldInfo()
function field_info( $table, $field ) {
	$res = mysql_query( "SELECT * FROM $table LIMIT 1" );
	$n = mysql_num_fields( $res );
	for( $i = 0; $i < $n; $i++ ) {
		$meta = mysql_fetch_field( $res, $i );
		if( $field == $meta->name ) {
			return $meta;
		}
	}
	return false;
}

?>
