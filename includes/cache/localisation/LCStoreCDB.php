<?php
/**
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
use Cdb\Exception;
use Cdb\Reader;
use Cdb\Writer;

/**
 * LCStore implementation which stores data as a collection of CDB files in the
 * directory given by $wgCacheDirectory. If $wgCacheDirectory is not set, this
 * will throw an exception.
 *
 * Profiling indicates that on Linux, this implementation outperforms MySQL if
 * the directory is on a local filesystem and there is ample kernel cache
 * space. The performance advantage is greater when the DBA extension is
 * available than it is with the PHP port.
 *
 * See Cdb.php and https://cr.yp.to/cdb.html
 */
class LCStoreCDB implements LCStore {

	/** @var Reader[] */
	private $readers;

	/** @var Writer */
	private $writer;

	/** @var string Current language code */
	private $currentLang;

	/** @var bool|string Cache directory. False if not set */
	private $directory;

	function __construct( $conf = [] ) {
		global $wgCacheDirectory;

		$this->directory = $conf['directory'] ?? $wgCacheDirectory;
	}

	public function get( $code, $key ) {
		if ( !isset( $this->readers[$code] ) ) {
			$fileName = $this->getFileName( $code );

			$this->readers[$code] = false;
			if ( file_exists( $fileName ) ) {
				try {
					$this->readers[$code] = Reader::open( $fileName );
				} catch ( Exception $e ) {
					wfDebug( __METHOD__ . ": unable to open cdb file for reading\n" );
				}
			}
		}

		if ( !$this->readers[$code] ) {
			return null;
		} else {
			$value = false;
			try {
				$value = $this->readers[$code]->get( $key );
			} catch ( Exception $e ) {
				wfDebug( __METHOD__ . ": \Cdb\Exception caught, error message was "
					. $e->getMessage() . "\n" );
			}
			if ( $value === false ) {
				return null;
			}

			return unserialize( $value );
		}
	}

	public function startWrite( $code ) {
		if ( !file_exists( $this->directory ) && !wfMkdirParents( $this->directory, null, __METHOD__ ) ) {
			throw new MWException( "Unable to create the localisation store " .
				"directory \"{$this->directory}\"" );
		}

		// Close reader to stop permission errors on write
		if ( !empty( $this->readers[$code] ) ) {
			$this->readers[$code]->close();
		}

		try {
			$this->writer = Writer::open( $this->getFileName( $code ) );
		} catch ( Exception $e ) {
			throw new MWException( $e->getMessage() );
		}
		$this->currentLang = $code;
	}

	public function finishWrite() {
		// Close the writer
		try {
			$this->writer->close();
		} catch ( Exception $e ) {
			throw new MWException( $e->getMessage() );
		}
		$this->writer = null;
		unset( $this->readers[$this->currentLang] );
		$this->currentLang = null;
	}

	public function set( $key, $value ) {
		if ( is_null( $this->writer ) ) {
			throw new MWException( __CLASS__ . ': must call startWrite() before calling set()' );
		}
		try {
			$this->writer->set( $key, serialize( $value ) );
		} catch ( Exception $e ) {
			throw new MWException( $e->getMessage() );
		}
	}

	protected function getFileName( $code ) {
		if ( strval( $code ) === '' || strpos( $code, '/' ) !== false ) {
			throw new MWException( __METHOD__ . ": Invalid language \"$code\"" );
		}

		return "{$this->directory}/l10n_cache-$code.cdb";
	}

}
