<?php
/**
 * Command line script to check for an open proxy at a specified location.
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
 * @ingroup Maintenance
 */

if( php_sapi_name() != 'cli' ) {
	die( 1 );
}

/**
 *
 */
$output = '';

/**
 * Exit if there are not enough parameters, or if it's not command line mode
 */
if ( ( isset( $_REQUEST ) && array_key_exists( 'argv', $_REQUEST ) ) || count( $argv ) < 4 ) {
	$output .= "Incorrect parameters\n";
} else {
	/**
	 * Get parameters
	 */
	$ip = $argv[1];
	$port = $argv[2];
	$url = $argv[3];
	$host = trim(`hostname`);
	$output = "Connecting to $ip:$port, target $url, this hostname $host\n";

	# Open socket
	$sock = @fsockopen($ip, $port, $errno, $errstr, 5);
	if ($errno == 0 ) {
		$output .= "Connected\n";
		# Send payload
		$request = "GET $url HTTP/1.0\r\n";
#		$request .= "Proxy-Connection: Keep-Alive\r\n";
#		$request .= "Pragma: no-cache\r\n";
#		$request .= "Host: ".$url."\r\n";
#		$request .= "User-Agent: MediaWiki open proxy check\r\n";
		$request .= "\r\n";
		@fputs($sock, $request);
		$response = fgets($sock, 65536);
		$output .= $response;
		@fclose($sock);
	} else {
		$output .= "No connection\n";
	}
}

$output = escapeshellarg( $output );

#`echo $output >> /home/tstarling/open/proxy.log`;
