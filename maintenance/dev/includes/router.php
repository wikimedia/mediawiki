<?php
/**
 * Router for the php cli-server built-in webserver.
 * https://www.php.net/manual/en/features.commandline.webserver.php
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

if ( !isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
	// Let built-in server handle error.
	return false;
}

// The SCRIPT_FILENAME can be one of three things:
// 1. Absolute path to a file in the docroot associated with the
//    path of the current request URL. PHP does this for any file path
//    where it finds a matching file on disk. For both PHP files, and for
//    static files.
// 2. Relative path to router.php (this file), for any unknown URL path
//    that ends in ".php" or another extension that PHP would execute.
// 3. Absolute path to {docroot}/index.php, for any other unknown path.
//    Effectively treating it as a 404 handler.
$file = $_SERVER['SCRIPT_FILENAME'];
if ( !is_readable( $file ) ) {
	// Let built-in server handle error.
	return false;
}

$ext = pathinfo( $file, PATHINFO_EXTENSION );
if ( $ext == 'php' ) {
	// Let built-in server handle script inclusion.
	return false;
} else {
	// Serve static file with appropiate Content-Type headers.
	// The built-in server for PHP 7.0+ supports most files already
	// (contrary to PHP 5.2, which was supported when router.php was created).
	// But it still doesn't support as many MIME types as MediaWiki (e.g. ".json")
	require_once __DIR__ . "/../../../includes/libs/mime/MimeMap.php";

	// Fallback
	$mime = 'text/plain';
	// Borrow from MimeAnalyzer
	foreach ( \Wikimedia\Mime\MimeMap::MIME_EXTENSIONS as $type => $exts ) {
		if ( in_array( $ext, $exts ) ) {
			$mime = $type;
			break;
		}
	}

	if ( preg_match( '#^text/#', $mime ) ) {
		// Text should have a charset=UTF-8 (PHP's webserver does this too)
		header( "Content-Type: $mime; charset=UTF-8" );
	} else {
		header( "Content-Type: $mime" );
	}

	$content = file_get_contents( $file );

	header( 'Vary: Accept-Encoding' );
	$acceptGzip = preg_match( '/\bgzip\b/', $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '' );
	if ( $acceptGzip &&
		// Don't compress binary static files (e.g. png)
		preg_match( '/text|javascript|json|css|xml|svg/', $mime ) &&
		// Tiny files tend to grow instead of shrink. – <https://gerrit.wikimedia.org/r/537974>
		strlen( $content ) > 150
	) {
		$content = gzencode( $content, 9 );
		header( 'Content-Encoding: gzip' );
	}
	header( "Content-Length: " . strlen( $content ) );
	echo $content;

	return true;
}
