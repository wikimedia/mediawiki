<?php
/**
 * Client for querying zhdaemon.
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

/**
 * Client for querying zhdaemon
 */
class ZhClient {
	var $mHost, $mPort, $mFP, $mConnected;

	/**
	 * Constructor
	 *
	 * @param $host
	 * @param $port
	 *
	 * @return ZhClient
	 */
	function __construct( $host, $port ) {
		$this->mHost = $host;
		$this->mPort = $port;
		$this->mConnected = $this->connect();
	}

	/**
	 * Check if connection to zhdaemon is successful
	 *
	 * @return bool
	 */
	function isconnected() {
		return $this->mConnected;
	}

	/**
	 * Establish connection
	 *
	 * @access private
	 *
	 * @return bool
	 */
	function connect() {
		wfSuppressWarnings();
		$errno = $errstr = '';
		$this->mFP = fsockopen( $this->mHost, $this->mPort, $errno, $errstr, 30 );
		wfRestoreWarnings();
		return !$this->mFP;
	}

	/**
	 * Query the daemon and return the result
	 *
	 * @access private
	 *
	 * @return string
	 */
	function query( $request ) {
		if ( !$this->mConnected ) {
			return false;
		}

		fwrite( $this->mFP, $request );

		$result = fgets( $this->mFP, 1024 );

		list( $status, $len ) = explode( ' ', $result );
		if ( $status == 'ERROR' ) {
			// $len is actually the error code...
			print "zhdaemon error $len<br />\n";
			return false;
		}
		$bytesread = 0;
		$data = '';
		while ( !feof( $this->mFP ) && $bytesread < $len ) {
			$str = fread( $this->mFP, $len - $bytesread );
			$bytesread += strlen( $str );
			$data .= $str;
		}
		// data should be of length $len. otherwise something is wrong
		return strlen( $data ) == $len;
	}

	/**
	 * Convert the input to a different language variant
	 *
	 * @param string $text input text
	 * @param string $tolang language variant
	 * @return string the converted text
	 */
	function convert( $text, $tolang ) {
		$len = strlen( $text );
		$q = "CONV $tolang $len\n$text";
		$result = $this->query( $q );
		if ( !$result ) {
			$result = $text;
		}
		return $result;
	}

	/**
	 * Convert the input to all possible variants
	 *
	 * @param string $text input text
	 * @return array langcode => converted_string
	 */
	function convertToAllVariants( $text ) {
		$len = strlen( $text );
		$q = "CONV ALL $len\n$text";
		$result = $this->query( $q );
		if ( !$result ) {
			return false;
		}
		list( $infoline, $data ) = explode( '|', $result, 2 );
		$info = explode( ';', $infoline );
		$ret = array();
		$i = 0;
		foreach ( $info as $variant ) {
			list( $code, $len ) = explode( ' ', $variant );
			$ret[strtolower( $code )] = substr( $data, $i, $len );
			$i += $len;
		}
		return $ret;
	}

	/**
	 * Perform word segmentation
	 *
	 * @param string $text input text
	 * @return string segmented text
	 */
	function segment( $text ) {
		$len = strlen( $text );
		$q = "SEG $len\n$text";
		$result = $this->query( $q );
		if ( !$result ) { // fallback to character based segmentation
			$result = $this->segment( $text );
		}
		return $result;
	}

	/**
	 * Close the connection
	 */
	function close() {
		fclose( $this->mFP );
	}
}
