<?php
/**
 * Router for the php cli-server built-in webserver.
 * http://www.php.net/manual/en/features.commandline.webserver.php
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

if ( PHP_SAPI != 'cli-server' ) {
	die( "This script can only be run by php's cli-server sapi." );
}

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

if ( isset( $_SERVER["SCRIPT_FILENAME"] ) ) {
	# Known resource, sometimes a script sometimes a file
	$file = $_SERVER["SCRIPT_FILENAME"];
} elseif ( isset( $_SERVER["SCRIPT_NAME"] ) ) {
	# Usually unknown, document root relative rather than absolute
	# Happens with some cases like /wiki/File:Image.png
	if ( is_readable( $_SERVER['DOCUMENT_ROOT'] . $_SERVER["SCRIPT_NAME"] ) ) {
		# Just in case this actually IS a file, set it here
		$file = $_SERVER['DOCUMENT_ROOT'] . $_SERVER["SCRIPT_NAME"];
	} else {
		# Otherwise let's pretend that this is supposed to go to index.php
		$file = $_SERVER['DOCUMENT_ROOT'] . '/index.php';
	}
} else {
	# Meh, we'll just give up
	return false;
}

# And now do handling for that $file

if ( !is_readable( $file ) ) {
	# Let the server throw the error if it doesn't exist
	return false;
}
$ext = pathinfo( $file, PATHINFO_EXTENSION );
if ( $ext == 'php' || $ext == 'php5' ) {
	# Execute php files
	# We use require and return true here because when you return false
	# the php webserver will discard post data and things like login
	# will not function in the dev environment.
	require $file;

	return true;
}
$mime = false;
$lines = explode( "\n", file_get_contents( "includes/mime.types" ) );
foreach ( $lines as $line ) {
	$exts = explode( " ", $line );
	$mime = array_shift( $exts );
	if ( in_array( $ext, $exts ) ) {
		break; # this is the right value for $mime
	}
	$mime = false;
}
if ( !$mime ) {
	$basename = basename( $file );
	if ( $basename == strtoupper( $basename ) ) {
		# IF it's something like README serve it as text
		$mime = "text/plain";
	}
}
if ( $mime ) {
	# Use custom handling to serve files with a known MIME type
	# This way we can serve things like .svg files that the built-in
	# PHP webserver doesn't understand.
	# ;) Nicely enough we just happen to bundle a mime.types file
	$f = fopen( $file, 'rb' );
	if ( preg_match( '#^text/#', $mime ) ) {
		# Text should have a charset=UTF-8 (php's webserver does this too)
		header( "Content-Type: $mime; charset=UTF-8" );
	} else {
		header( "Content-Type: $mime" );
	}
	header( "Content-Length: " . filesize( $file ) );
	// Stream that out to the browser
	fpassthru( $f );

	return true;
}

# Let the php server handle things on its own otherwise
return false;
