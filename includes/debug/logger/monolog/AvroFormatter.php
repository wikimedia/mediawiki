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

namespace MediaWiki\Logger\Monolog;

use AvroNamedSchemata;
use AvroSchema;
use AvroIODatumWriter;
use AvroStringIO;
use AvroIOBinaryEncoder;
use Monolog\Formatter\FormatterInterface;

/**
 * Log message formatter that uses the apache Avro format.
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2013 Erik Bernhardson and Wikimedia Foundation.
 */
class AvroFormatter implements FormatterInterface {
	// @var string[] Map from schema name to schema json string
	protected $schemas;

	// @var AvroIODatumWriter[] Map from schema name to datum writer
	protected $writers = array();

	// @var AvroStringIO
	protected $io;

	// @var AvroIOBinaryEncoder
	protected $encoder;

	public function __construct( array $schemas ) {
		$this->schemas = $schemas;
		$this->io = new AvroStringIO( '' );
		$this->encoder = new AvroIOBinaryEncoder( $this->io );
	}

	/**
	 * Formats a record into a binary string per the configured
	 * schema.
	 *
	 * @param array $record
	 * @return string
	 */
	public function format( array $record ) {
		$this->io->truncate();
		$this->getWriter( $record['channel'] )->write( $record, $this->encoder );
		return $this->io->string();
	}

	/**
	 * Format a set of records into a list of binary strings
	 * conforming to the configured schema.
	 *
	 * @param array $records
	 * @return string[]
	 */
	public function formatBatch( array $records ) {
		$result = array();
		$writer = $this->getWriter( $record['channel'] );
		foreach ( $records as $record ) {
			$this->io->truncate();
			$writer->write( $record, $this->encoder );
			$result[] = $this->io->string();
		}
		return $result;
	}

	/**
	 * Get the writer for the named schema
	 *
	 * @var string $name Name of the schema to write
	 * @return AvroIODatumWriter
	 */
	protected function getWriter( $name ) {
		if ( !isset( $this->schemas[$name] ) ) {
			throw new \RuntimeException( "The schema '$name' is not available" );
		}
		if ( !isset( $this->writers[$name] ) ) {
			$schema = AvroSchema::real_parse(
				$this->schemas[$name],
				null,
				new AvroNamedSchemata()
			);
			$this->writers[$name] = new AvroIODatumWriter( $schema );
		}
		return $this->writers[$name];
	}
}
