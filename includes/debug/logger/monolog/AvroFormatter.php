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
 * @copyright © 2015 Erik Bernhardson and Wikimedia Foundation.
 */
class AvroFormatter implements FormatterInterface {
	// @var string[] Map from schema name to schema definition
	protected $schemas;

	// @var AvroIODatumWriter[] Map from monolog channel to datum writer
	protected $writers = array();

	// @var AvroStringIO
	protected $io;

	// @var AvroIOBinaryEncoder
	protected $encoder;

	/**
	 * @var array $schemas Map from monolog channel to avro schema.
	 *  Each schema can be either the json string or decoded into php
	 *  arrays.
	 */
	public function __construct( array $schemas ) {
		$this->schemas = $schemas;
		$this->io = new AvroStringIO( '' );
		$this->encoder = new AvroIOBinaryEncoder( $this->io );
	}

	/**
	 * Formats the record context into a binary string per the
	 * schema configured for the records channel.
	 *
	 * @param array $record
	 * @return string
	 */
	public function format( array $record ) {
		$this->io->truncate();
		$this->getWriter( $record['channel'] )
			->write( $record['context'], $this->encoder );
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
			$this->io->truncate();
			$this->getWriter( $record['channel'] )
				->write( $record['context'], $this->encoder );
			$result[] = $this->io->string();
		}
		return $result;
	}

	/**
	 * Get the writer for the named channel
	 *
	 * @var string $channel Name of the schema to write
	 * @return AvroIODatumWriter
	 */
	protected function getWriter( $channel ) {
		if ( !isset( $this->schemas[$channel] ) ) {
			throw new \RuntimeException( "The schema for channel '$channel' is not available" );
		}
		if ( !isset( $this->writers[$channel] ) ) {
			if ( is_string( $this->schemas[$channel] ) ) {
				$schema = AvroSchema::parse( $this->schemas[$channel] );
			} else {
				$schema = AvroSchema::real_parse(
					$this->schemas[$channel],
					null,
					new AvroNamedSchemata()
				);
			}
			$this->writers[$channel] = new AvroIODatumWriter( $schema );
		}
		return $this->writers[$channel];
	}
}
