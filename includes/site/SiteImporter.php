<?php

/**
 * Utility for importing site entries from a stream.
 * For the expected file format of the input stream, see SiteFormat.wiki.
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class SiteImporter {

	/**
	 * @var SiteStore
	 */
	private $store;

	/**
	 * @var callable
	 */
	private $exceptionCallback;

	public function __construct( SiteStore $store ) {

		$this->store = $store;
	}

	public function importFile( $file ) {
		$lines = file( $file );
		$this->importLines( new ArrayIterator( $lines ) );
	}

	/**
	 * @param Iterator $stream <string> $stream
	 */
	public function importLines( Iterator $stream ) {
		do {
			try {
				$section = $this->readSection( $stream );

				if ( $section === false ) {
					break;
				}

				$data = $this->parseSection( $section );
				$site = $this->deserializeSite( $data );

				$this->store->saveSite( $site );
			} catch ( Exception $ex ) {
				call_user_func( $this->exceptionCallback, $ex );
			}
		} while ( true );
	}

	/**
	 * @param Iterator $stream <string> $stream
	 *
	 * @return string[]|false
	 */
	private function readSection( Iterator $stream ) {
		$buffer = array();

		do {
			$line = $this->readNextLine( $stream );

			if ( $line === false ) {
				return empty( $buffer ) ? false : $buffer;
			}

			if ( $line === '' && !empty( $buffer ) ) {
				return $buffer;
			}

			$buffer[] = $line;
		} while ( true );

		throw new RuntimeException( 'Unexpected control flow!' );
	}

	/**
	 * @param Iterator<string> $stream
	 *
	 * @return string|false
	 */
	private function readNextLine( Iterator $stream ) {
		$stream->rewind();

		do {
			$stream->next();
			$line = $stream->current();

			if ( $line === null || $line === false ) {
				return false;
			}

			$line = trim( $line );

			if ( $line !== '' && $line[0] === '#' ) {
				continue;
			}

			return $line;
		} while ( false );
	}

	/**
	 * @param string[] $lines
	 *
	 * @return array
	 */
	private function parseSection( array $lines ) {
		$data = array();

		foreach ( $lines as $line ) {
			list( $path, $value ) = $this->splitLine( $line );
			$this->setField( $data, $path, $value );
		}

		return $data;
	}

	private function setField( &$data, $path, $value ) {
		$name = array_pop( $path );
		$target = &$data;

		foreach ( $path as $key ) {
			if ( $key === '+' ) {
				$key = count( $target );
			}

			if ( !isset( $target[$key] ) ) {
				$target[$key] = array();
			}

			if ( !is_array( $target[$key] ) ) {
				throw new SiteImportException( 'Cannot use subkey on a non-array value: ' . $key );
			}

			$target = &$target[$key];
		}

		if ( $name === '+' ) {
			$target[] = $value;
		} else {
			$target[$name] = $value;
		}
	}

	private function splitLine( $line ) {
		$bits = explode( '=', $line, 2 );

		if ( count( $bits ) !== 2 ) {
			throw new SiteImportException( 'Missing "=" in line: ' . $line );
		}

		list( $key, $value ) = $bits;
		$path = explode( '.', $key );

		return array( $path, $value );
	}

	/**
	 * @param array $data
	 *
	 * @return Site
	 */
	private function deserializeSite( array $data ) {
		$site = Site::newForType( isset( $data['type'] ) ? $data['type'] : Site::TYPE_UNKNOWN );
		$site->setFields( $data );
		return $site;
	}

}
