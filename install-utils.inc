<?php

function install_version_checks() {
	# We dare not turn output buffer _off_ since this will break completely
	# if PHP is globally configured to run through a gzip filter.
	@ob_implicit_flush( true );
	
	if( !function_exists( "version_compare" ) ) {
		# version_compare was introduced in 4.1.0
		die( "Your PHP version is much too old; 4.0.x will _not_ work. 4.3.2 or higher is recommended. ABORTING.\n" );
	}
	if( version_compare( phpversion(), "4.3.2" ) < 0 ) {
		echo "WARNING: PHP 4.3.2 or higher is recommended. Older versions from 4.1.x up may work but are not actively supported.\n\n";
	}
	
	if (!extension_loaded('mysql')) {
		if (!dl('mysql.so')) {
			print "Could not load MySQL driver! Please compile ".
				  "php --with-mysql or install the mysql.so module.\n";
		exit;
		}
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
		if ( $f{0} !="." && is_file( $fullname ) ) {
			copyfile( $source, $f, $dest );
		}
	}
}

function readconsole( $prompt = "" ) {
	if ( function_exists( "readline" ) ) {
		return readline( $prompt );
	} else {
		print $prompt;
		$fp = fopen( "php://stdin", "r" );
		$resp = trim( fgets( $fp, 1024 ) );
		fclose( $fp );
		return $resp;
	}
}

function replacevars( $ins ) {
	$varnames = array(
		"wgDBserver", "wgDBname", "wgDBintlname", "wgDBuser",
		"wgDBpassword", "wgDBsqluser", "wgDBsqlpassword",
		"wgDBadminuser", "wgDBadminpassword", "wgDBprefix"
	);

	foreach ( $varnames as $var ) {
		global $$var;
		$ins = str_replace( '{$' . $var . '}', $$var, $ins );
		$ins = str_replace( '/*$' . $var . '*/`', '`' . $$var, $ins );
		$ins = str_replace( '/*$' . $var . '*/', $$var, $ins );
	}
	return $ins;
}

#
# Read and execute SQL commands from a file
#
function dbsource( $fname, $database = false ) {
	$fp = fopen( $fname, "r" );
	if ( false === $fp ) {
		print "Could not open \"{$fname}\".\n";
		exit();
	}

	$cmd = "";
	$done = false;

	while ( ! feof( $fp ) ) {
		$line = trim( fgets( $fp, 1024 ) );
		$sl = strlen( $line ) - 1;

		if ( $sl < 0 ) { continue; }
		if ( "-" == $line{0} && "-" == $line{1} ) { continue; }

		if ( ";" == $line{$sl} ) {
			$done = true;
			$line = substr( $line, 0, $sl );
		}

		if ( "" != $cmd ) { $cmd .= " "; }
		$cmd .= $line;

		if ( $done ) {
			$cmd = replacevars( $cmd );
			if( $database )
				$res = $database->query( $cmd );
			else
				$res = mysql_query( $cmd );

			if ( false === $res ) {
				$err = mysql_error();
				print "Query \"{$cmd}\" failed with error code \"$err\".\n";
				exit();
			}

			$cmd = "";
			$done = false;
		}
	}
	fclose( $fp );
}

# Obsolete, use Database::fieldExists()
function field_exists( $table, $field ) {
	$fname = "Update script: field_exists";
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
