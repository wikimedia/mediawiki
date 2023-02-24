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
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Emitters;

use InvalidArgumentException;
use UDPTransport;
use Wikimedia\Stats\Formatters\FormatterInterface;
use Wikimedia\Stats\Metrics\NullMetric;
use Wikimedia\Stats\StatsCache;
use Wikimedia\Stats\StatsUtils;

/**
 * Metrics UDP Emitter Implementation
 *
 * Leverages UDPTransport to emit wire-formatted metrics.
 *
 * @author Cole White
 * @since 1.41
 */
class UDPEmitter implements EmitterInterface {

	/** @var string */
	private string $prefix;

	/** @var StatsCache */
	private StatsCache $cache;

	/** @var FormatterInterface */
	private FormatterInterface $formatter;

	/** @var UDPTransport|null */
	private ?UDPTransport $transport;

	/** @var int */
	private int $payloadSize;

	public function __construct( string $prefix, StatsCache $cache, FormatterInterface $formatter, ?string $target ) {
		$this->prefix = $this->normalizePrefix( $prefix );
		$this->cache = $cache;
		$this->formatter = $formatter;
		$this->transport = $target ? UDPTransport::newFromString( $target ) : null;
		$this->payloadSize = UDPTransport::MAX_PAYLOAD_SIZE;
	}

	/**
	 * Sets payload size for batching.
	 *
	 * @param int $payloadSize
	 * @return UDPEmitter
	 */
	public function withPayloadSize( int $payloadSize ): UDPEmitter {
		$this->payloadSize = $payloadSize;
		return $this;
	}

	/**
	 * Overrides the transport.
	 *
	 * @param UDPTransport $transport
	 * @return UDPEmitter
	 */
	public function withTransport( UDPTransport $transport ): UDPEmitter {
		$this->transport = $transport;
		return $this;
	}

	private function normalizePrefix( string $prefix ): string {
		if ( $prefix === '' ) {
			throw new InvalidArgumentException( 'UDPEmitter: Prefix cannot be empty.' );
		}
		return StatsUtils::normalizeString( $prefix );
	}

	/**
	 * Renders metrics and samples through the formatter and returns a string[] of wire-formatted metric samples.
	 *
	 * @return array
	 */
	private function render(): array {
		$output = [];
		foreach ( $this->cache->getAllMetrics() as $metric ) {
			// Skip NullMetric instances.
			if ( get_class( $metric ) === NullMetric::class ) {
				continue;
			}
			foreach ( $this->formatter->getFormattedSamples( $this->prefix, $metric ) as $formatted ) {
				$output[] = $formatted;
			}
		}
		return $output;
	}

	/**
	 * Batch the array of samples into payload of payloadSize and
	 * emit them via the configured transport.
	 *
	 * @param array $samples
	 * @param int $payloadSize
	 * @return void
	 */
	private function batch( array $samples, int $payloadSize ): void {
		$payload = '';
		foreach ( $samples as $sample ) {
			if ( strlen( $payload ) + strlen( $sample ) + 1 < $payloadSize ) {
				$payload .= $sample . "\n";
			} else {
				// Send this payload and make a new one
				$this->transport->emit( $payload );
				$payload = '';
			}
		}
		// Send what is left in the payload
		if ( strlen( $payload ) > 0 ) {
			$this->transport->emit( $payload );
		}
	}

	/**
	 * @inheritDoc
	 */
	public function send(): void {
		$this->batch( $this->render(), $this->payloadSize );
	}
}
