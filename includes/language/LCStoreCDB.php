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
use Cdb\Exception as CdbException;
use Cdb\Reader;
use Cdb\Writer;

/**
 * LCStore implementation which stores data as a collection of CDB files.
 *
 * Profiling indicates that on Linux, this implementation outperforms MySQL if
 * the directory is on a local filesystem and there is ample kernel cache
 * space. The performance advantage is greater when the DBA extension is
 * available than it is with the PHP port.
 *
 * See Cdb.php and https://cr.yp.to/cdb.html
 *
 * @ingroup Language
 */
class LCStoreCDB implements LCStore {

	/** @var Reader[]|false[] */
	private $readers;

	/** @var Writer|null */
	private $writer;

	/** @var string|null Current language code */
	private $currentLang;

	/** @var string Cache directory */
	private $directory;

	public function __construct( array $conf = [] ) {
		$this->directory = $conf['directory'];
	}

	/** @inheritDoc */
	public function get( $code, $key ) {
		if ( !isset( $this->readers[$code] ) ) {
			$fileName = $this->getFileName( $code );

			$this->readers[$code] = false;
			if ( is_file( $fileName ) ) {
				try {
					$this->readers[$code] = Reader::open( $fileName );
				} catch ( CdbException ) {
					wfDebug( __METHOD__ . ": unable to open cdb file for reading" );
				}
			}
		}

		if ( !$this->readers[$code] ) {
			return null;
		} else {
			$value = false;
			try {
				$value = $this->readers[$code]->get( $key );
			} catch ( CdbException $e ) {
				wfDebug( __METHOD__ . ": \Cdb\Exception caught, error message was "
					. $e->getMessage() );
			}
			if ( $value === false ) {
				return null;
			}

			return unserialize( $value );
		}
	}

	/** @inheritDoc */
	public function startWrite( $code ) {
		if ( !is_dir( $this->directory ) && !wfMkdirParents( $this->directory, null, __METHOD__ ) ) {
			throw new RuntimeException( "Unable to create the localisation store " .
				"directory \"{$this->directory}\"" );
		}

		// Close reader to stop permission errors on write
		if ( !empty( $this->readers[$code] ) ) {
			$this->readers[$code]->close();
		}

		$this->writer = Writer::open( $this->getFileName( $code ) );
		$this->currentLang = $code;
	}

	public function finishWrite() {
		$this->writer->close();
		$this->writer = null;
		unset( $this->readers[$this->currentLang] );
		$this->currentLang = null;
	}

	/** @inheritDoc */
	public function set( $key, $value ) {
		if ( $this->writer === null ) {
			throw new LogicException( __CLASS__ . ': must call startWrite() before calling set()' );
		}
		$this->writer->set( $key, serialize( $value ) );
	}

	protected function getFileName( $code ) {
		if ( strval( $code ) === '' || strpos( $code, '/' ) !== false ) {
			throw new InvalidArgumentException( __METHOD__ . ": Invalid language \"$code\"" );
		}

		return "{$this->directory}/l10n_cache-$code.cdb";
	}

}
