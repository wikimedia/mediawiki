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

use AvroIODatumWriter;
use AvroIOBinaryEncoder;
use AvroIOTypeException;
use AvroNamedSchemata;
use AvroSchema;
use AvroStringIO;
use AvroValidator;
use Monolog\Formatter\FormatterInterface;

/**
 * Log message formatter that uses the apache Avro format.
 *
 * @since 1.26
 * @author Erik Bernhardson <ebernhardson@wikimedia.org>
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class AvroFormatter implements FormatterInterface {
	/**
	 * @var array Map from schema name to schema definition
	 */
	protected $schemas;

	/**
	 * @var AvroStringIO
	 */
	protected $io;

	/**
	 * @var AvroIOBinaryEncoder
	 */
	protected $encoder;

	/**
	 * @var AvroIODatumWriter
	 */
	protected $writer;

	/**
	 * @var array $schemas Map from Monolog channel to Avro schema.
	 *  Each schema can be either the JSON string or decoded into PHP
	 *  arrays.
	 */
	public function __construct( array $schemas ) {
		$this->schemas = $schemas;
		$this->io = new AvroStringIO( '' );
		$this->encoder = new AvroIOBinaryEncoder( $this->io );
		$this->writer = new AvroIODatumWriter();
	}

	/**
	 * Formats the record context into a binary string per the
	 * schema configured for the records channel.
	 *
	 * @param array $record
	 * @return string|null The serialized record, or null if
	 *  the record is not valid for the selected schema.
	 */
	public function format( array $record ) {
		$this->io->truncate();
		$schema = $this->getSchema( $record['channel'] );
		if ( $schema === null ) {
			trigger_error( "The schema for channel '{$record['channel']}' is not available" );
			return null;
		}
		try {
			$this->writer->write_data( $schema, $record['context'], $this->encoder );
		} catch ( AvroIOTypeException $e ) {
			$errors = AvroValidator::getErrors( $schema, $record['context'] );
			$json = json_encode( $errors );
			trigger_error( "Avro failed to serialize record for {$record['channel']} : {$json}" );
			return null;
		}
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
		foreach ( $records as $record ) {
			$message = $this->format( $record );
			if ( $message !== null ) {
				$result[] = $message;
			}
		}
		return $result;
	}

	/**
	 * Get the writer for the named channel
	 *
	 * @var string $channel Name of the schema to fetch
	 * @return AvroSchema|null
	 */
	protected function getSchema( $channel ) {
		if ( !isset( $this->schemas[$channel] ) ) {
			return null;
		}
		if ( !$this->schemas[$channel] instanceof AvroSchema ) {
			if ( is_string( $this->schemas[$channel] ) ) {
				$this->schemas[$channel] = AvroSchema::parse( $this->schemas[$channel] );
			} else {
				$this->schemas[$channel] = AvroSchema::real_parse(
					$this->schemas[$channel],
					null,
					new AvroNamedSchemata()
				);
			}
		}
		return $this->schemas[$channel];
	}
}
