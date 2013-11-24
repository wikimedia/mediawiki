<?php
/**
 * Object caching using DBA backend.
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
 * @ingroup Cache
 */

/**
 * Cache that uses DBA as a backend.
 * Slow due to the need to constantly open and close the file to avoid holding
 * writer locks. Intended for development use only,  as a memcached workalike
 * for systems that don't have it.
 *
 * On construction you can pass array( 'dir' => '/some/path' ); as a parameter
 * to override the default DBA files directory (wfTempDir()).
 *
 * @ingroup Cache
 */
class DBABagOStuff extends BagOStuff {
	var $mHandler, $mFile, $mReader, $mWriter, $mDisabled;

	/**
	 * @param $params array
	 */
	public function __construct( $params ) {
		global $wgDBAhandler;

		if ( !isset( $params['dir'] ) ) {
			$params['dir'] = wfTempDir();
		}

		$this->mFile = $params['dir'] . '/mw-cache-' . wfWikiID() . '.db';
		wfDebug( __CLASS__ . ": using cache file {$this->mFile}\n" );
		$this->mHandler = $wgDBAhandler;
	}

	/**
	 * Encode value and expiry for storage
	 * @param $value
	 * @param $expiry
	 *
	 * @return string
	 */
	protected function encode( $value, $expiry ) {
		# Convert to absolute time
		$expiry = $this->convertExpiry( $expiry );

		return sprintf( '%010u', intval( $expiry ) ) . ' ' . serialize( $value );
	}

	/**
	 * @param $blob string
	 * @return array list containing value first and expiry second
	 */
	protected function decode( $blob ) {
		if ( !is_string( $blob ) ) {
			return array( false, 0 );
		} else {
			return array(
				unserialize( substr( $blob, 11 ) ),
				intval( substr( $blob, 0, 10 ) )
			);
		}
	}

	/**
	 * @return resource
	 */
	protected function getReader() {
		if ( file_exists( $this->mFile ) ) {
			$handle = dba_open( $this->mFile, 'rl', $this->mHandler );
		} else {
			$handle = $this->getWriter();
		}

		if ( !$handle ) {
			wfDebug( "Unable to open DBA cache file {$this->mFile}\n" );
		}

		return $handle;
	}

	/**
	 * @return resource
	 */
	protected function getWriter() {
		$handle = dba_open( $this->mFile, 'cl', $this->mHandler );

		if ( !$handle ) {
			wfDebug( "Unable to open DBA cache file {$this->mFile}\n" );
		}

		return $handle;
	}

	/**
	 * @param $key string
	 * @param $casToken[optional] mixed
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$handle = $this->getReader();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$val = dba_fetch( $key, $handle );
		list( $val, $expiry ) = $this->decode( $val );

		# Must close ASAP because locks are held
		dba_close( $handle );

		if ( $val !== false && $expiry && $expiry < time() ) {
			# Key is expired, delete it
			$handle = $this->getWriter();
			dba_delete( $key, $handle );
			dba_close( $handle );
			wfDebug( __METHOD__ . ": $key expired\n" );
			$val = false;
		}

		$casToken = $val;

		wfProfileOut( __METHOD__ );

		return $val;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$blob = $this->encode( $value, $exptime );

		$handle = $this->getWriter();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_replace( $key, $blob, $handle );
		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$blob = $this->encode( $value, $exptime );

		$handle = $this->getWriter();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		// DBA is locked to any other write connection, so we can safely
		// compare the current & previous value before saving new value
		$val = dba_fetch( $key, $handle );
		list( $val, $exptime ) = $this->decode( $val );
		if ( $casToken !== $val ) {
			dba_close( $handle );
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_replace( $key, $blob, $handle );
		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		wfProfileIn( __METHOD__ );
		wfDebug( __METHOD__ . "($key)\n" );

		$handle = $this->getWriter();
		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = !dba_exists( $key, $handle ) || dba_delete( $key, $handle );
		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function add( $key, $value, $exptime = 0 ) {
		wfProfileIn( __METHOD__ );

		$blob = $this->encode( $value, $exptime );

		$handle = $this->getWriter();

		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		$ret = dba_insert( $key, $blob, $handle );

		# Insert failed, check to see if it failed due to an expired key
		if ( !$ret ) {
			list( , $expiry ) = $this->decode( dba_fetch( $key, $handle ) );

			if ( $expiry && $expiry < time() ) {
				# Yes expired, delete and try again
				dba_delete( $key, $handle );
				$ret = dba_insert( $key, $blob, $handle );
				# This time if it failed then it will be handled by the caller like any other race
			}
		}

		dba_close( $handle );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $key string
	 * @param $step integer
	 * @return integer|bool
	 */
	public function incr( $key, $step = 1 ) {
		wfProfileIn( __METHOD__ );

		$handle = $this->getWriter();

		if ( !$handle ) {
			wfProfileOut( __METHOD__ );
			return false;
		}

		list( $value, $expiry ) = $this->decode( dba_fetch( $key, $handle ) );
		if ( $value !== false ) {
			if ( $expiry && $expiry < time() ) {
				# Key is expired, delete it
				dba_delete( $key, $handle );
				wfDebug( __METHOD__ . ": $key expired\n" );
				$value = false;
			} else {
				$value += $step;
				$blob = $this->encode( $value, $expiry );

				$ret = dba_replace( $key, $blob, $handle );
				$value = $ret ? $value : false;
			}
		}

		dba_close( $handle );

		wfProfileOut( __METHOD__ );

		return ( $value === false ) ? false : (int)$value;
	}
}
